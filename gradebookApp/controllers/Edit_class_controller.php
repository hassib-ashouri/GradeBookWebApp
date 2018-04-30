<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_class_controller extends MY_Controller
{
    public function index()
    {
        redirect("Login_controller/newUserView");
    }

    /**
     * View methods
     */

    /**
     * loads the view for the class that should be edited.
     * @param string $classId
     */
    public function editClassView($classId)
    {
        $this->load->model("class_model");

        try {
            $classObj = $this->class_model->getClassById($classId);

            // get the ids of the students
            $studentIds = array();
            foreach ($classObj->getStudents() as $student) {
                array_push($studentIds, $student->student_id);
            }


            $headerData = array(
                "title" => "Edit Class View",
                "javascripts" => array(
                    "add_assignments.js",
                ),
                "classObj" => $classObj,
                "loggedUser" => $this->session->get_userdata()["loggedUser"],
                "studentIds" => $studentIds,
                "Assignments" => $classObj->getAssignmentList()->getGroupedAssignments(),
            );
            $mainViewComponents["header"] = $this->load->view("header", $headerData, true);


            $mainViewComponents["partialViews"] = array(
                $this->load->view("editClass/basic_info_comp", null, true),
                $this->load->view("editClass/add_students_table_comp", null, true),
                $this->load->view("editClass/add_assignments_comp", null, true),
            );

            $this->load->view("main", $mainViewComponents);
        } catch (Exception $e) {
            // load a class does not exist view.
        }
    }

    /**
     * Action methods
     */

    /**
     * todo please comment
     */
    public function recieveClassInfo()
    {
        $postData = $this->input->post();

        try {
            $this->load->model("class_model");
            $classObj = $this->class_model->getClassById($postData["classId"]);

            $this->_updateAssignments($classObj, $postData["assignmentGroups"]);
            $this->_updateStudents($classObj, $postData["students"]);
            $this->_updateClass($postData);
        } catch (Exception $e) {
            // suppress errors?
        }
    }

    /**
     * Private methods
     */

    /**
     * Processes assignments;
     *      todo Hassib comment more please
     * @param \Objects\ClassObj $classObj
     * @param array $assignmentGroups
     */
    private function _updateAssignments($classObj, $assignmentGroups)
    {
        //prepare the grouped assignments that will go in the classobj
        $assignmentsToBeProcessed = array();

        //loop through each group
        foreach ($assignmentGroups as $index => $group) {
            //loop through each assignment in each group.
            $groupName = $group["groupName"];
            $groupWeight = $group["weight"];
            $groupStatus = $group["status"]; //it look like we dont need it.

            if (isset($group["assignmentsArr"])) {
                foreach ($group["assignmentsArr"] as $assignment) {

                    $assignmentObj = new \Objects\Assignment();
                    $assignmentObj->assignment_name = $assignment["assignmentName"];
                    $assignmentObj->type = $groupName;
                    $assignmentObj->weight = $groupWeight;
                    $assignmentObj->max_points = $assignment["assignmentGrade"];
                    $assignmentObj->graded = false;
                    $assignmentObj->max_points_old = $assignmentObj->max_points;
                    if ($assignment["assignmentId"] == "new") {
                        $assignmentObj->markAsNew();
                    } else {
                        $assignmentObj->assignment_id = $assignment["assignmentId"];
                    }
                    //add to the assignment list
                    array_push($assignmentsToBeProcessed, $assignmentObj);
                }
            }
        }

        $this->load->model("Assignment_model");
        $this->assignment_model->updateAssignments($assignmentsToBeProcessed, $classObj);
    }

    /**
     * Creates a classObj to update the meta data only.
     * @param $postData
     */
    private function _updateClass($postData)
    {
        $classObject = new \Objects\ClassObj(null, null);
        $classObject->class_id = $postData["classId"];
        $classObject->professor_id = null;
        $classObject->class_name = $postData["className"];
        $classObject->section = $postData["section"];
        $classObject->class_title = $postData["classTitle"];
        $classObject->meeting_times = $postData["meetingTimes"];

        $this->load->model("class_list_model");
        $this->class_list_model->updateClass($classObject);
    }

    /**
     * Adds and removes students from the class
     * @param \Objects\ClassObj $classObj
     * @param string[] $studentIds
     */
    private function _updateStudents($classObj, $studentIds)
    {
        // get the ids of the students
        $oldStudentIds = array();
        foreach ($classObj->getStudents() as $student) {
            array_push($oldStudentIds, $student->student_id);
        }

        /**
         * @var string[] representing the ids of the new students.
         */
        $newStudents = array();
        foreach ($studentIds as $studentId) {
            $index = array_search($studentId, $oldStudentIds);
            if ($index !== false) {
                // if the student exists, remove from these lists.
                unset($oldStudentIds[$index]);
            } else {
                //if the student is new.
                array_push($newStudents, $studentId);
            }
        }

        // remove old students and add new students.
        $this->class_model->removeStudents($oldStudentIds, $classObj);
        $this->class_model->addStudents($newStudents, $classObj);
    }
}