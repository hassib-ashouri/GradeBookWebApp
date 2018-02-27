<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IndexController extends MY_Controller
{
    public function index()
    {
        redirect("LoginController/loginView");
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
            "student_id" => array("type" => "tinytext"),
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
        $this->class_model->loadTable($tableName);
        $classObj = $this->class_model->getClass();
        $students = $classObj->getStudents();

        foreach ($students as $student) {
            $grade = sprintf("%.2f%%", $student->getGrade());
            echo "<pre>$grade - $student</pre>";
        }
    }

    public function studentTableTest($studentId = "000000001")
    {
        $tableName = "class_29506_SE-131_02_table";

        $this->load->model("class_model");
        $this->class_model->loadTable($tableName, $studentId);
        $classObj = $this->class_model->getClass();
        $student = $classObj->getStudent($studentId);
        if (!is_null($student)) {
            $assignments = $student->getAssignments();

            echo "<h1>Grades for $student->name_first $student->name_last</h1>";
            echo "<table>";
            echo "<tr><th>Name</th><th>Score</th><th>Out Of</th></tr>";
            foreach ($assignments as $assignment) {
                $gradedPoints = ($assignment->graded) ? $assignment->points : "N/A";

                echo "<tr>";
                echo "<td>$assignment->assignment_name</td>";
                echo "<td>$gradedPoints</td>";
                echo "<td>$assignment->max_points</td>";
                echo "</tr>";
            }
            foreach ($student->getGroupNames() as $groupName) {
                $points = $student->getGroupPoints($groupName);
                $maxPoints = $student->getGroupMaxPoints($groupName);
                $percent = sprintf("%.2f%%", $student->getGroupGrade($groupName));
                echo "<tr><td><b>$groupName</b></td><td>$percent</td><td>$points / $maxPoints</td></tr>";
            }
            echo "</table>";
            $percent = sprintf("%.2f%%", $student->getGrade());
            echo "<div><b>Grade: $percent</b></div>";
        } else {
            echo "<h2>No such student exists</h2>";
        }
    }

    public function generateNewStudentId()
    {
        $row = $this->db->get("student_list")->last_row("array");
        $new = intval($row["student_id"]) + 1;
        echo sprintf("%'09s", $new);
    }

    public function classStatsTest()
    {
        $tableName = "class_29506_SE-131_02_table";

        $this->load->model("class_model");
        $this->class_model->loadTable($tableName);
        $classObj = $this->class_model->getClass();

        $low = sprintf("%.2f%%", $classObj->getLowGrade());
        $high = sprintf("%.2f%%", $classObj->getHighGrade());
        $mean = sprintf("%.2f%%", $classObj->getMeanGrade());
        $var = sprintf("%.2f", $classObj->getVarGrade());
        $stdDev = sprintf("%.2f", $classObj->getStdDevGrade());

        echo "<pre><b>Low</b>: <span>$low</span></pre>";
        echo "<pre><b>High</b>: <span>$high</span></pre>";
        echo "<pre><b>Mean</b>: <span>$mean</span></pre>";
        echo "<pre><b>Variance</b>: <span>$var</span></pre>";
        echo "<pre><b>Standard Deviation</b>: <span>$stdDev</span></pre>";
    }
}