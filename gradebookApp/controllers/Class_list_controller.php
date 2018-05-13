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
        redirectNonUser();

        $userId = $this->session->userdata('loggedUser');

        // prepare the header.
        $header = array(
            'title' => 'Class List',
            'javascripts' => array('class_list.js',),
            'name' => $this->session->userdata('userName'),
        );
        $view_components["header"] = $this->load->view("header", $header, true);

        $view_components["mainContent"] = $this->_classListComp($userId);
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
        redirectNonUser();

        $this->load->model("class_model");
        $this->load->model("class_list_model");

        $classObj = $this->class_model->getClass($tableName);
        $this->class_list_model->deleteClass($classObj);

        redirect('Class_list_controller/classListView');
    }

    /**
     * Private methods
     */

    /**
     * Creates and returns the overview component
     * @param string $userId
     * @return string
     */
    private function _classListComp($userId)
    {
        // model name should be lower case.
        $this->load->model("class_list_model");
        $this->load->model("class_model");

        // prepare the data for the main body.
        $classes = $this->class_list_model->readProfessorClassList($userId);
        $classObjects = array();
        foreach ($classes as $classObj) {
            try {
                $tempClassObj = $this->class_model->getClassByTableName($classObj->table_name);
                array_push($classObjects, $tempClassObj);
            } catch (Exception $e) {
                // do nothing
            }
        }

        $data = array(
            'addClassLink' => base_url() . 'Add_class_controller/addClassView',
            'classObjects' => $classObjects,
        );

        return $this->load->view('classlist/classlist_comp', $data, true);
    }
}