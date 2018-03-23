<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller for Mitchell to play with code,
 *      No one else should upload here,
 *      but feel free to use these methods to help you understand
 * Class Mitchell_controller
 */
class Mitchell_controller extends MY_Controller
{
    public function index()
    {
        redirect("Login_controller/loginView");
    }

    public function sessionTest1()
    {
        $user = "000000001";
        $this->session->set_userdata("user", $user);

        echo "<pre>";
        var_dump($this->session);
        echo "</pre>";
    }

    public function sessionTest2()
    {
        echo "<pre>";
        var_dump($this->session);
        echo "</pre>";
    }

    public function classListTest()
    {
        $professorId = "0123";
        $this->load->model("class_list_model");
        $classes = $this->class_list_model->readProfessorClassList($professorId);

        echo "<pre>";
        var_dump($classes);
        echo "</pre>";
    }

    public function createClassTable()
    {
        $fields = array(
            "id" => array("type" => "int", "unsigned" => true, "auto_increment" => true),
            "student_id" => array("type" => "char", "constraint" => 9),
            "assignment_id" => array("type" => "int"),
            "points" => array("type" => "float"),
        );

        $classId = "29507";
        $className = "SE 132";
        $classSection = "02";
        $className = preg_replace("/\s/", "-", $className);
        $className = preg_replace("/[^A-Za-z\-\d]/", "", $className);
        $tableName = sprintf("class_%s_%s_%s_table", $classId, $className, $classSection);

        $this->load->dbforge();
        $this->dbforge
            ->add_field($fields)
            ->add_key("id", true)
            ->create_table($tableName);
    }

    public function classTableTest()
    {
        $tableName = "class_29506_SE-131_02_table";

        $this->load->model("class_model");
        $classObj = $this->class_model->getClass($tableName);
        $students = $classObj->getStudents();

        foreach ($students as $student) {
            $grade = sprintf("%.2f%%", $student->getGrade());
            $studentName = sprintf("%s, %s", $student->name_last, $student->name_first);
            $studentId = $student->student_id;
            echo "<pre>$grade - $studentName, $studentId</pre>";
        }
    }

    public function studentTableTest($studentId = "000000001")
    {
        $tableName = "class_29506_SE-131_02_table";

        $this->load->model("class_model");
        $classObj = $this->class_model->getClass($tableName);
        $assignments = $classObj->getAssignments();
        $student = $classObj->getStudent($studentId);
        if (!is_null($student)) {
            echo "<h1>Grades for $student->name_first $student->name_last</h1>";
            echo "<table>";
            echo "<tr><th>Name</th><th>Score</th><th>Out Of</th></tr>";
            /**
             * @var Objects\Assignment $assignment
             */
            foreach ($assignments as $assignment) {
                $gradedPoints = ($assignment->graded) ? $assignment->getPoints($studentId) : "N/A";

                echo "<tr>";
                echo "<td>$assignment->assignment_name</td>";
                echo "<td>$gradedPoints</td>";
                echo "<td>$assignment->max_points</td>";
                echo "</tr>";
            }
            foreach ($student->getGroupNames() as $groupName) {
                $points = $student->getGroupPoints($groupName);
                $maxPoints = $student->getGroupMaxPoints($groupName);
                $graded = $student->getGroupGraded($groupName);
                if ($graded) {
                    $percent = sprintf("%.2f%%", $student->getGroupGrade($groupName));
                } else {
                    $percent = "N/A";
                }
                echo "<tr><td><b>$groupName</b></td><td>$percent</td><td>$points / $maxPoints</td></tr>";
            }
            echo "</table>";
            $percent = sprintf("%.2f%%", $student->getGrade());
            echo "<div><b>Grade: $percent</b></div>";
        } else {
            echo "<h2>No such student exists</h2>";
        }
    }

    public function genericAssignmentTest()
    {
        $tableName = "class_29506_SE-131_02_table";

        $this->load->model("class_model");
        $classObj = $this->class_model->getClass($tableName);
        $assignmentList = $classObj->getAssignmentList();
        $groups = $assignmentList->getGroupNames();
        $grouped = $assignmentList->getGroupedAssignments();

        $formAction = base_url() . "Index_controller/submitGenericAssignmentTest";
        echo "<form action='$formAction' method='post'>";
        echo "<input name='tableName' value='$tableName' type='hidden'>";
        foreach ($grouped as $group) {
            $groupName = $group->getGroupName();
            $groupWeight = $group->getGroupWeight();
            echo "<input name='groupName[]' value='$groupName'>";
            echo "<input name='groupWeight[]' value='$groupWeight'>";

            $assignments = $group->getAssignments();
            foreach ($assignments as $assignment) {
                echo "<div>";
                echo "<input name='assignId[]' value='$assignment->assignment_id' type='hidden'>";
                echo "<input name='assignName[]' value='$assignment->assignment_name'>";
                echo "<textarea name='assignDesc[]'>$assignment->description</textarea>";

                echo "<select name='assignType[]'>";
                foreach ($groups as $groupName) {
                    $selected = ($assignment->type == $groupName) ? "selected" : "";
                    echo "<option value='$groupName' $selected>$groupName</option>";
                }
                echo "</select>";

                echo "<input name='assignMaxPts[]' value='$assignment->max_points'>";
                echo "<input name='assignMaxPtsOld[]' value='$assignment->max_points' type='hidden'>";
                echo "<input name='assignGraded[]' value='$assignment->graded' type='hidden'>";
                echo "</div>";
            }

            echo "<br>";
        }
        echo "<button type='submit'>Submit</button>";
        echo "</form>";
    }

    public function submitGenericAssignmentTest()
    {
        $post = $this->input->post();

        $this->load->model("assignment_model");
        $this->assignment_model->readPost($post);
        $this->assignment_model->updateAssignments();

        redirect("Index_controller/genericAssignmentDisplay");
    }

    public function genericAssignmentDisplay()
    {
        $tableName = "class_29506_SE-131_02_table";

        $this->load->model("class_model");
        $classObj = $this->class_model->getClass($tableName);
        $assignmentList = $classObj->getAssignmentList();
        $grouped = $assignmentList->getGroupedAssignments();

        foreach ($grouped as $group) {
            $groupName = $group->getGroupName();
            $groupWeight = $group->getGroupWeight();
            echo "<h3>Group: $groupName - Weight: $groupWeight%</h3>";

            $assignments = $group->getAssignments();
            foreach ($assignments as $assignment) {
                echo "<div>";
                echo "ID: $assignment->assignment_id";
                echo " - ";
                echo "$assignment->assignment_name";
                echo " - ";
                echo "MaxPts: $assignment->max_points";
                echo " - ";
                echo "Graded: $assignment->graded";
                echo "</div>";

                echo "<div>";
                echo "<p>$assignment->description</p>";
                echo "</div>";
            }
        }
    }

    public function generateNewStudentId()
    {
        $row = $this->db->get("students")->last_row("array");
        $new = intval($row["student_id"]) + 1;
        echo sprintf("%'09s", $new);
    }

    public function classStatsTest()
    {
        $tableName = "class_29506_SE-131_02_table";

        $this->load->model("class_model");
        $classObj = $this->class_model->getClass($tableName);

        $low = sprintf("%.2f%%", $classObj->getLowGrade());
        $high = sprintf("%.2f%%", $classObj->getHighGrade());
        $mean = sprintf("%.2f%%", $classObj->getMeanGrade());
        $median = sprintf("%.2f%%", $classObj->getMedianGrade());
        $var = sprintf("%.2f", $classObj->getVarGrade());
        $stdDev = sprintf("%.2f", $classObj->getStdDevGrade());

        echo "<pre><b>Low</b>: <span>$low</span></pre>";
        echo "<pre><b>High</b>: <span>$high</span></pre>";
        echo "<pre><b>Mean</b>: <span>$mean</span></pre>";
        echo "<pre><b>Median</b>: <span>$median</span></pre>";
        echo "<pre><b>Variance</b>: <span>$var</span></pre>";
        echo "<pre><b>Standard Deviation</b>: <span>$stdDev</span></pre>";
    }

    public function assignmentStatsTest()
    {
        $tableName = "class_29506_SE-131_02_table";

        $this->load->model("class_model");
        $classObj = $this->class_model->getClass($tableName);
        $assignments = $classObj->getAssignments();

        foreach ($assignments as $assignment) {
            /**
             * @var Objects\Assignment $assignment
             */
            $name = $assignment->assignment_name;
            $low = sprintf("%.2f", $assignment->getLowGrade());
            $high = sprintf("%.2f", $assignment->getHighGrade());
            $mean = sprintf("%.2f", $assignment->getMeanGrade());
            $median = sprintf("%.2f", $assignment->getMedianGrade());
            $var = sprintf("%.2f", $assignment->getVarGrade());
            $stdDev = sprintf("%.2f", $assignment->getStdDevGrade());

            echo "<pre>$name</pre>";
            echo "<pre><b>Low</b>: <span>$low</span></pre>";
            echo "<pre><b>High</b>: <span>$high</span></pre>";
            echo "<pre><b>Mean</b>: <span>$mean</span></pre>";
            echo "<pre><b>Median</b>: <span>$median</span></pre>";
            echo "<pre><b>Variance</b>: <span>$var</span></pre>";
            echo "<pre><b>Standard Deviation</b>: <span>$stdDev</span></pre>";
            echo "<br>";
        }
    }

    public function passwordTest()
    {
        $post = $this->input->post();
        $formAction = base_url() . "Index_controller/passwordTest";

        echo "<form method='post' action='$formAction'>";
        echo "<input name='password'>";
        echo "</form>";

        if (isset($post["password"])) {
            $password = $post["password"];
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            echo "<div>'$passwordHash',</div>";
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            echo "<div>'$passwordHash',</div>";
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            echo "<div>'$passwordHash',</div>";
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            echo "<div>'$passwordHash',</div>";
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            echo "<div>'$passwordHash',</div>";
            $characterCount = strlen($password);
            $hashedCharacterCount = strlen($passwordHash);
            $works = password_verify($password, $passwordHash);
            echo "<div>Password Input Length: $characterCount</div>";
            echo "<div>Password Hash Length: $hashedCharacterCount</div>";
            echo "<div>Works: $works</div>";
        }
    }

    public function testVerify($studentId = "000000001")
    {
        $this->load->model("student_model");
        $student = $this->student_model->getStudent($studentId);

        echo "<pre>";
        var_dump($student);
        echo "</pre>";
    }
}