<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Class_list_controller extends MY_Controller
{
    public function index()
    {
        redirect("Login_controller/newUserView");
    }

    /**
     * View methods
     */

    /**
     * this loads a simple class list view. with names of tables of the classes.
     * @author Hassib Ashouri
     */
    public function classListView()
    {
        $userId = $this->session->get_userdata()["loggedUser"];

        //prepare the header.
        $header["title"] = "professor view with list";
        $header["javascripts"] = array(
            "class_list.js",
        );
        $view_components["header"] = $this->load->view("header", $header, true);
        //prepare the date for the main body.
        //model name should be lower case.
        $this->load->model("class_list_model");
        $classes = $this->class_list_model->readProfessorClassList($userId);
        $classesTableNames = array();

        //todo: find a better type of information to be displayed.
        //get the table names.
        foreach ($classes as $classObj) {
            array_push($classesTableNames, $classObj->table_name);
        }

        $mainComponentData = array(
            "addClassLink" => base_url() . "Add_class_controller/addClassView",
            "editClassLink" => base_url() . "Edit_class_controller/editClassView/12345",
            "classes" => $classesTableNames,
            "loggedUser" => $userId,
        );


        $view_components["mainContent"] = $this->load->view("classlist/classlist_comp", $mainComponentData, true);
        $this->load->view("main", $view_components);
    }

    /**
     * Action methods
     */

    /**
     * @param string $tableName
     */
    public function deleteClass($tableName)
    {
        $this->load->model("class_model");
        $this->load->model("class_list_model");

        $classObj = $this->class_model->getClass($tableName);
        $this->class_list_model->deleteClass($classObj);

        redirect('Class_list_controller/classListView');
    }

    /**
     * Private methods
     */
}