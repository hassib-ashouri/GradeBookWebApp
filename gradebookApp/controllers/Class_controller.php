<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Class_controller extends MY_Controller
{
    public function index()
    {
        redirect("Login_controller/newUserView");
    }

    /**
     * View methods
     */

    /**
     * todo describe function
     * @param string $tableName
     */
    public function displayClassInfo($tableName)
    {
        $header = array(
            // todo change me!
            "title" => "Class Test",
        );

        $this->load->model('class_model');
        $classObj = $this->class_model->getClass($tableName);

        $info = array();
        $stats = array(
            'highGrade' => number_format($classObj->getHighGrade(), 2),
            'lowGrade' => number_format($classObj->getLowGrade(), 2),
            'meanGrade' => number_format($classObj->getMeanGrade(), 2),
            'medianGrade' => number_format($classObj->getMedianGrade(), 2),
            'varGrade' => number_format($classObj->getVarGrade(), 2),
            'stdDevGrade' => number_format($classObj->getStdDevGrade(), 2),
        );
        $classInfo = array(
            'infoComponent' => $this->load->view("class/info", $info, true),
            'statsComponent' => $this->load->view("class/stats", $stats, true),
        );

        $view_components["header"] = $this->load->view("header", $header, true);
        $view_components["partialViews"] = array(
            $this->load->view("class/class_info", $classInfo, true),
        );
        $this->load->view("main", $view_components);
    }

    /**
     * Action methods
     */

    /**
     * Private methods
     */
}