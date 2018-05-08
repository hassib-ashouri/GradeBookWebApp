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
        redirectNonUser();

        $header = array(
            // todo change me!
            "title" => "Class Test",
        );

        $this->load->model('class_model');
        $classObj = $this->class_model->getClass($tableName);

        $info = array(
            'className' => $classObj->class_name,
            'section' => $classObj->section,
            'schedule' => $classObj->meeting_times,
        );
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

        //we need to add the three different partail views to mainPatialView.
        //this view loading will eventually be methods.
        $mainPartialViewData["detailedGrades"] = $this->loadStudentsGradesPartialView($tableName);
        $mainPartialViewData["gradesOverview"] = $this->load->view("class/main/overview", null,true);
        $mainPartialViewData["assignments"] = $this->_assignmentListComp($classObj);
        $mainPartialView = $this->load->view("class/main", $mainPartialViewData, true);


        $view_components["header"] = $this->load->view("header", $header, true);
        $view_components["partialViews"] = array(
            $this->load->view("class/class_info", $classInfo, true),
            $mainPartialView,
        );
        $this->load->view("main", $view_components);
    }

    /**
     * Action methods
     */

    /**
     * todo remove if not needed
     */
    public function testAlias()
    {
        $tableName = "class_29506_SE-131_02_table";
        $this->load->model("class_model");
        $classObj = $this->class_model->getClass($tableName);

        foreach ($classObj->getAssignments() as $assignment) {
            pretty_dump($assignment->assignment_name);
            $this->_aliasAssignmentName($assignment->assignment_name);
        }
    }

    /**
     * Private methods
     */

    /**
     * Returns the assignment_list component view
     * @param \Objects\ClassObj $classObj
     * @return string
     */
    private function _assignmentListComp($classObj)
    {
        $assignmentGroups = $classObj->getAssignmentList()->getGroupedAssignments();
        $assignmentList = array(
            'assignmentGroups' => $assignmentGroups,
        );
        return $this->load->view('class/main/assignment_list', $assignmentList, true);
    }

    private function loadStudentsGradesPartialView($tableName)
    {


        return $this->load->view("class/main/detailed",null,true);
    }

    /**
     * Transforms an $assignmentName into something more 'table-friendly';
     *      maximum length of 4 characters
     * @param string $assignmentName
     * @return string
     */
    private function _aliasAssignmentName($assignmentName)
    {
        $ALIAS_LENGTH = 4;

        $matches = array();
        $pattern = '/(?:(\b.)\w*?\s)(?:(\b.)\w*?\s)?(?:(\b.)\w*?\s)?(?:(\b.)\w*?\s)?(\d*)/';
        preg_match($pattern, $assignmentName, $matches);
        $alias = '';

        // try with regex
        if (isset($matches[5])) {
            $number = $matches[5];
            $numberLength = strlen($number);
        } else {
            $number = '';
            $numberLength = 0;
        }
        for ($i = 1; $i < $ALIAS_LENGTH - $numberLength + 1; $i++) {
            if (isset($matches[$i])) {
                $alias .= $matches[$i];
            }
        }
        $alias .= $number;

        // try with first char only
        if (strlen($alias) == 0) {
            $alias = substr($assignmentName, 0, 1);
        }

        return $alias;
    }
}