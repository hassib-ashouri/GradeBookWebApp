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
        $tableName = $this->validateClass($classId);

        //i should check if the class exists first.
        if($tableName != null)
        {
            //get the class obj.
            $this->load->model("Class_model");
            /**
             * @var \Objects\ClassObj $classObj
             */
            $classObj = $this->Class_model->getClass($tableName);

            // get the ids of the students
            $studentIds = array();
            foreach ($classObj->getStudents() as $student)
            {
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
        }
        else
        {
            //load a class does not exist view.
        }


    }

    /**
     * Action methods
     */

    /**
     * verifies the class id in sent in the request.
     * @param string $classId represent the class id.
     * @return null | string representing the tablename of the class.
     */
    public function validateClass($classId)
    {
        $userId = $this->session->get_userdata()["loggedUser"];
        $this->load->model("class_list_model");
        $classes = $this->class_list_model->readProfessorClassList($userId);

        foreach($classes as $class)
        {
            if($classId == $class->class_id)
                return $class->table_name;
        }

        return null;
    }

    public function recieveClassInfo()
    {
        $postData = $this->input->post();

        pretty_dump($postData);
        $assignmentGroups = $postData["assignmentGroups"];

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

        //get the class obj.
        $tableName = $this->validateClass($postData["classId"]);
        $this->load->model("Class_model");
        /**
         * @var \Objects\ClassObj $classObj
         */
        $classObj = $this->class_model->getClass($tableName);

        $this->load->model("Assignment_model");
        $this->assignment_model->updateAssignments($assignmentsToBeProcessed, $classObj);



        // get the ids of the students
        $oldStudentIds = array();
        foreach ($classObj->getStudents() as $student)
        {
            array_push($oldStudentIds, $student->student_id);
        }

        /**
         * @var string[] representing the ids of the new students.
         */
        $newStudents = array();
        foreach ($postData["students"] as $index => $studentId) {
            if(array_search($studentId, $oldStudentIds) == null)
            {//if the student is new.
                array_push($newStudents,$studentId);
            }
            else
            {// if the student exists, remove from these lists.
                unset($oldStudentIds[$index]);
            }
        }
        // remove old students and add new students.
        $this->load->model("class_model");
        $this->class_model->removeStudents($oldStudentIds,$classObj);
        $this->class_model->addStudents($newStudents,$classObj);

        //creates a class object to update the meta data only.
        $classObject = new \Objects\ClassObj(null, null);
        $classObject->class_id = $postData["classId"];
        $classObject->professor_id = null;
        $classObject->class_name = $postData["className"];
        $classObject->section = $postData["section"];
        $classObject->class_title = $postData["classTitle"];
        $classObject->meeting_times = $postData["meetingTimes"];

        $this->load->model("Class_list_model");
        $this->class_list_model->updateClass($classObject);
    }

    public function recieveClassInfoM() {
        $postData = $this->input->post();

        pretty_dump($postData);
    }

    /**
     * Private methods
     */
}