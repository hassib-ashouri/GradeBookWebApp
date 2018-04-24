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
     * @param \Objects\Student[]|string[] $students string[] should be student ids to add
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
         * Enrolls new students into the class
         */
        $this->_enrollStudents($students, $classObj->class_id);

        /**
         * Creates assignments
         */
        $this->load->model("assignment_model");
        $this->assignment_model->createAssignments($classObj);
    }

    /**
     * Loads a table, creates and returns a classObj;
     *      throws an exception if class not found
     * @param $classTableName
     * @return \Objects\ClassObj
     * @throws \Exception
     */
    public function getClass($classTableName)
    {
        /**
         * Gets classes from the db that match the table name
         */
        $this->load->model("class_list_model");
        $classes = $this->class_list_model->getClassesBy("table_name", $classTableName);

        if (isset($classes[0])) {
            /**
             * @var \Objects\ClassObj $classObj
             */
            $classObj = $classes[0];

            $this->load->model("student_model");
            $this->load->model("assignment_model");

            $students = $this->student_model->getStudents($classObj->class_id);
            $assignments = $this->assignment_model->getAssignments($classObj);
            $assignmentList = $this->_getAssignmentList($assignments);

            $this->_setAssignmentList($assignmentList, $students);

            $this->classObj = new \Objects\ClassObj($assignmentList, $students);

            /**
             * Copies all properties from $classObj to $this->classObj
             */
            foreach ($classObj as $propertyName => $value) {
                if (isset($value)) {
                    $this->classObj->$propertyName = $value;
                }
            }

            return $this->classObj;
        }

        throw new \Exception("No class with such table found: '$classTableName'");
    }

    /**
     * Removes rows from students_enrolled for each student in the class;
     * Also removes assignments for each student
     * @param \Objects\Student[]|string[] $students string[] should be student ids to remove
     * @param \Objects\ClassObj $classObj needs class_id, and table_name
     */
    public function removeStudents($students, $classObj)
    {
        /**
         * Checks students for validity
         */
        $this->load->model("student_model");
        $students = $this->student_model->asStudents($students);

        /**
         * Unenrolls students from the class
         */
        $this->_unenrollStudents($students, $classObj->class_id);

        /**
         * Removes assignments for students
         */
        $this->_deleteStudentsAssignments($students, $classObj->table_name);
    }

    /**
     * Removes assignment entries for each student
     * @param \Objects\Student[] $students
     * @param string $tableName
     */
    private function _deleteStudentsAssignments($students, $tableName)
    {
        foreach ($students as $student) {
            $this->db->or_where(array("student_id" => $student->student_id));
        }
        if (count($students) > 0) {
            $this->db->delete($tableName);
        }
    }

    /**
     * Enrolls new students into the specified class
     * @param \Objects\Student[] $students
     * @param string $classId
     */
    private function _enrollStudents($students, $classId)
    {
        /**
         * Determines which students are enrolled already
         */
        $blacklist = $this->db
            ->select("student_id")
            ->from("students_enrolled")
            ->where("class_id", $classId)
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
                    "class_id" => $classId,
                ));
            }
        }

        if (count($studentsEnrolledData) > 0) {
            $this->db->insert_batch("students_enrolled", $studentsEnrolledData);
        }
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

    /**
     * Enrolls students from the specified class
     * @param \Objects\Student[] $students
     * @param string $classId
     */
    private function _unenrollStudents($students, $classId)
    {
        foreach ($students as $student) {
            $this->db
                ->or_group_start()
                ->where(array(
                    "student_id" => $student->student_id,
                    "class_id" => $classId,
                ))
                ->group_end();
        }
        if (count($students) > 0) {
            $this->db->delete("students_enrolled");
        }
    }
}