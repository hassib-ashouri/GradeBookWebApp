<?php
/**
 * Created by IntelliJ IDEA.
 * User: soulstaker
 * Date: 3/28/18
 * Time: 12:31 AM
 */

/**
 * The controller responsible for creating the view responsible
 * for adding a new class.
 * Class Add_class_controller
 */
class Add_class_controller extends MY_Controller
{
    /**
     * default method. If method is not specified, take to login page.
     */
    public function index()
    {
        redirect("Login_controller/newUserView");
    }

    /**
     * View methods
     */

    /**
     * Generates the add class view.
     */
    public function addClassView()
    {
        redirectNonUser();

        //header component.
        $header = array(
            'title' => 'Add class',
            'javascripts' => array('add_assignments.js',),
            'name' => $this->session->userdata('userName'),
        );
        $view_components["header"] = $this->load->view("header", $header, true);
        //prepare the mainContenet variable.
        $data = array(
            "loggedUser" => $this->session->userdata("loggedUser"),
        );
        $view_components["partialViews"] = array(
            $this->load->view("addClass/basic_info_comp", $data, true),
            $this->load->view("addClass/add_students_table_comp", null, true),
            $this->load->view("addClass/add_assignments_comp", null, true),
        );

        $this->load->view("main", $view_components);
    }


    /**
     * Action methods
     */

    public function recieveClassInfo()
    {
        redirectNonUser();

        $postData = $this->input->post();

        $assignmentGroups = $postData["assignmentGroups"];
        $numberOfAssignments = 0;
        //prepare the grouped assignments that will go in the classobj
        $assignmnetList = new \Objects\AssignmentList();

        //loop through each group
        foreach ($assignmentGroups as $group) {
            //loop through each assignment in each group.
            if (isset($group["assignmentsArr"])) {
                foreach ($group["assignmentsArr"] as $assignment) {
                    $numberOfAssignments++;
                    $assignmentObj = new \Objects\Assignment();
                    $assignmentObj->assignment_name = $assignment["assignmentName"];
                    $assignmentObj->type = $group["groupName"];
                    $assignmentObj->weight = $group["weight"];
                    $assignmentObj->max_points = $assignment["assignmentGrade"];
                    $assignmentObj->graded = false;
                    //add to the assignment list
                    $assignmnetList->addAssignment($assignmentObj);
                }
            }
        }

        $students = array();
        foreach ($postData["students"] as $studentId) {
            $student = new \Objects\Student();
            $student->student_id = $studentId;
            array_push($students, $student);
        }

        $classObject = new \Objects\ClassObj($assignmnetList, $students);
        $classObject->class_id = $postData["classId"];
        $classObject->professor_id = $postData["professorId"];
        $classObject->class_name = $postData["className"];
        $classObject->section = $postData["section"];
        $classObject->class_title = $postData["classTitle"];
        $classObject->meeting_times = $postData["meetingTimes"];
        $classObject->table_name = sprintf("class_%s_%s_%s_table",
            $classObject->class_id, $classObject->class_name, $classObject->section);

        $this->load->model("class_list_model");
        $this->class_list_model->createClass($classObject);
    }

    /**
     * Private methods
     */
}
