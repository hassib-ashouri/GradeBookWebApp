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
}