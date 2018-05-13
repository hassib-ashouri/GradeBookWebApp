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

        $this->load->model('class_model');
        $classObj = $this->class_model->getClass($tableName);

        $header = array(
            'title' => "Class $classObj->class_name",
            'name' => $this->session->userdata('userName'),
        );
        $view_components["header"] = $this->load->view("header", $header, true);
        $view_components["partialViews"] = array(
            $this->_classInfoComp($classObj),
            $this->_classMainComp($classObj),
        );
        $this->load->view("main", $view_components);
    }

    /**
     * Action methods
     */

    /**
     * Private methods
     */

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

    /**
     * Creates and returns the assignment_list component
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

    /**
     * Creates and returns the class_info component
     * @param \Objects\ClassObj $classObj
     * @return string
     */
    private function _classInfoComp($classObj)
    {
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

        return $this->load->view("class/class_info", $classInfo, true);
    }

    /**
     * Creates and returns the detailed component
     * @param \Objects\ClassObj $classObj
     * @return string
     */
    private function _detailedComp($classObj)
    {
        $assignmentsNames = array();
        $assignments = $classObj->getAssignments();
        foreach ($assignments as $assignment) {
            array_push($assignmentsNames, array(
                'alias' => $this->_aliasAssignmentName($assignment->assignment_name),
                'assignId' => $assignment->assignment_id,
                'name' => $assignment->assignment_name,
            ));
        }

        $grades = array();
        $students = $classObj->getStudents();
        foreach ($students as $student) {
            $studentNameKey = $student->name_last . ", " . $student->name_first;
            $studentId = $student->student_id;
            $grades[$studentNameKey] = array(
                'studentId' => $studentId,
                'grades' => array(),
            );
            foreach ($assignments as $assignment) {
                array_push($grades[$studentNameKey]['grades'], $assignment->getPoints($studentId));
            }
        }

        $data = array(
            'assignmentsNames' => $assignmentsNames,
            'grades' => $grades,
        );

        return $this->load->view("class/main/detailed", $data, true);
    }

    /**
     * Creates and returns the main class component
     * @param \Objects\ClassObj $classObj
     * @return string
     */
    private function _classMainComp($classObj)
    {
        // we need to add the three different partial views to mainPartialView.
        $main["detailedGrades"] = $this->_detailedComp($classObj);
        $main["gradesOverview"] = $this->_overviewComp($classObj);
        $main["assignments"] = $this->_assignmentListComp($classObj);

        return $this->load->view("class/main", $main, true);
    }

    /**
     * Creates and returns the overview component
     * @param \Objects\ClassObj $classObj
     * @return string
     */
    private function _overviewComp($classObj)
    {
        //Manuel's implementation for Overview component
        $students = $classObj->getStudents();

        $overview = array(
            'students' => $students,
        );

        return $this->load->view('class/main/overview', $overview, true);
    }
}