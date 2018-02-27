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
            "points" => array("type" => "int"),
        );

        $classId = "29506";
        $className = "SE 131";
        $className = preg_replace("/\s/", "-", $className);
        $className = preg_replace("/[^A-Za-z\-\d]/", "", $className);
        $tableName = sprintf("class_%s_%s_table", $classId, $className);

        $this->load->dbforge();
        $this->dbforge
            ->add_field($fields)
            ->add_key("id", true)
            ->create_table($tableName);
    }

    public function classTableTest()
    {
        $tableName = "class_29506_SE-131_table";

        $this->load->model("class_model");
        $this->class_model->loadTable($tableName);
        $classObj = $this->class_model->getClass();
        $students = $classObj->getStudents();

        foreach ($students as $student) {
            $grade = $student->getGrade();
            echo "<pre>$grade% - $student</pre>";
        }
    }

    public function studentTableTest($studentId = "000000001")
    {
        $tableName = "class_29506_SE-131_table";

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
            $points = $student->getPoints();
            $maxPoints = $student->getMaxPoints();
            $percent = $student->getGrade();

            echo "<tr><td><b>Total</b></td><td>$points</td><td>$maxPoints</td></tr>";
            echo "</table>";
            echo "<div><b>Grade: $percent%</b></div>";
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
}