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
     * Inserts rows into students_enrolled for each student in the class;
     * Also creates assignments for each student
     * @param \Objects\Student[]|string[] $students
     * @param \Objects\ClassObj $classObj
     */
    public function addStudents($students, $classObj)
    {
        /**
         * Checks students for validity
         */
        $this->load->model("student_model");
        $students = $this->student_model->asStudents($students);

        /**
         * Determines which students are enrolled already
         */
        $blacklist = $this->db
            ->select("student_id")
            ->from("students_enrolled")
            ->where("class_id", $classObj->class_id)
            ->get()->result("\Objects\Student");

        /**
         * Only enrolls new students
         */
        $studentsEnrolledData = array();
        foreach ($students as $student) {
            $blacklisted = false;
            foreach ($blacklist as $bStudent) {
                if ($bStudent->student_id == $student->student_id) {
                    $blacklisted = true;
                    break;
                }
            }

            if (!$blacklisted) {
                array_push($studentsEnrolledData, array(
                    "student_id" => $student->student_id,
                    "class_id" => $classObj->class_id,
                ));
            }
        }

        if (count($studentsEnrolledData) > 0) {
            $this->db->insert_batch("students_enrolled", $studentsEnrolledData);
        }

        /**
         * Creates assignments
         */
        $this->load->model("assignment_model");
        $this->assignment_model->createAssignments($classObj);
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
        $this->load->model("class_list_model");

        $students = $this->student_model->getStudents($classId);
        $assignments = $this->assignment_model->getAssignments($classTableName);
        $assignmentList = $this->_getAssignmentList($assignments);

        $this->_setAssignmentList($assignmentList, $students);

        $this->classObj = new \Objects\ClassObj($assignmentList, $students);
        $this->classObj->table_name = $classTableName;

        $classes = $this->class_list_model->getClassesBy("table_name", $classTableName);
        if (isset($classes[0])) {
            foreach ($classes[0] as $propertyName => $value) {
                if (isset($value)) {
                    $this->classObj->$propertyName = $value;
                }
            }
        }

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

    //todo public function removeStudents($studentIds)
}