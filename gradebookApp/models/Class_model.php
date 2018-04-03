<?php namespace Models;

/**
 * Database interaction for classes;
 * Used for reading, and editing class info,
 *      such as enrolled students
 * Class Class_model
 */
class Class_model extends \MY_Model
{
    /**
     * Class object,
     *      created from class table in the database
     * @var \Objects\ClassObj
     */
    private $classObj;

    // model is in charge of crud: create, read, update, delete
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Loads a table, creates and returns a classObj
     * @param string $classTableName
     * @return \Objects\ClassObj
     */
    public function getClass($classTableName)
    {
        $matches = array();
        preg_match("/class_(\d{5})_.*?_\d{2}_table/", $classTableName, $matches);
        $classId = $matches[1];

        $this->load->model("student_model");
        $this->load->model("assignment_model");

        $students = $this->student_model->getStudents($classId);
        $assignments = $this->assignment_model->getAssignments($classTableName);
        $assignmentList = $this->_getAssignmentList($assignments);

        $this->_setAssignmentList($assignmentList, $students);

        $this->classObj = new \Objects\ClassObj($assignmentList, $students);
        $this->classObj->table_name = $classTableName;

        return $this->classObj;
    }

    /**
     * Creates $assignmentList from $assignments
     * @param \Objects\Assignment[] $assignments
     * @return \Objects\AssignmentList
     */
    private function _getAssignmentList($assignments)
    {
        $assignmentList = new \Objects\AssignmentList();
        foreach ($assignments as $assignment) {
            $assignmentList->addAssignment($assignment);
        }

        return $assignmentList;
    }

    /**
     * Sets the assignment list for each student
     * @param \Objects\AssignmentList $assignmentList
     * @param \Objects\Student[] $students
     */
    private function _setAssignmentList($assignmentList, $students)
    {
        foreach ($students as $student) {
            $student->assignmentList = $assignmentList;
        }
    }

    //todo public function addStudents($studentIds)
    //todo public function removeStudents($studentIds)
}