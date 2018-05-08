<?php namespace Models;

/**
 * Database interaction for classes;
 * Used for reading, and editing class info,
 *      such as enrolled students
 * Class Class_model
 */
class Class_model extends \MY_Model
{
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
        $this->assignment_model->createAssignments($classObj, $students);
    }

    /**
     * Wrapper for getClassByTableName
     * @param string $classTableName
     * @return \Objects\ClassObj
     * @throws \Exception
     */
    public function getClass($classTableName)
    {
        return $this->getClassByTableName($classTableName);
    }

    /**
     * Loads a table by class_id, creates and returns a classObj;
     *      throws an exception if class not found
     * @param string $classId
     * @return \Objects\ClassObj
     * @throws \Exception
     */
    public function getClassById($classId)
    {
        return $this->_getClassBy("class_id", $classId);
    }

    /**
     * Loads a table by table_name, creates and returns a classObj;
     *      throws an exception if class not found
     * @param string $classTableName
     * @return \Objects\ClassObj
     * @throws \Exception
     */
    public function getClassByTableName($classTableName)
    {
        return $this->_getClassBy("table_name", $classTableName);
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
     * Updates the db with contents of $assignments;
     *      for updating grades only
     * @param \Objects\Assignment[] $assignments
     */
    public function updateStudentAssignments($assignments)
    {
        if (count($assignments) > 0) {
            $classId = $assignments[0]->class_id;

            try {
                $this->load->model('class_model');
                $classObj = $this->class_model->getClassById($classId);
                $tableName = $classObj->table_name;

                $changedAssignments = $this->_getChangedAssignments($classObj, $assignments);
                foreach ($changedAssignments as $assignment) {
                    $grades = $assignment->getAllPoints();
                    $assignmentId = $assignment->assignment_id;
                    foreach ($grades as $studentId => $points) {
                        $this->db
                            ->set('points', $points)
                            ->where('student_id', $studentId)
                            ->where('assignment_id', $assignmentId)
                            ->update($tableName);
                    }
                }
            } catch (\Exception $e) {
            }
        }
    }

    /**
     * Creates a copy of an assignment,
     *      without copying grades
     * @param \Objects\Assignment $assignment
     * @return \Objects\Assignment
     */
    private function _cloneAssignment($assignment)
    {
        $tempAssignment = new \Objects\Assignment();
        foreach ($assignment as $key => $value) {
            $tempAssignment->$key = $value;
        }
        return $tempAssignment;
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
     * Determines which assignments for which students need to be changed;
     *      returns newly constructed assignment objects with the results
     * @param \Objects\ClassObj $classObj
     * @param \Objects\Assignment[] $assignments
     * @return \Objects\Assignment[]
     */
    private function _getChangedAssignments($classObj, $assignments)
    {
        $changedAssignments = array();

        $classAssignments = $classObj->getAssignments();
        foreach ($classAssignments as $classAssignment) {
            foreach ($assignments as $assignment) {
                if ($classAssignment->assignment_id === $assignment->assignment_id) {
                    $classGrades = $classAssignment->getAllPoints();
                    $grades = $assignment->getAllPoints();

                    $tempAssignment = $this->_cloneAssignment($assignment);
                    foreach ($classGrades as $classStudentId => $classPoints) {
                        foreach ($grades as $studentId => $points) {
                            if ($classStudentId === $studentId) {
                                if ($classPoints !== $points) {
                                    $tempAssignment->setPoints($studentId, $points);
                                }

                                break;
                            }
                        }
                    }
                    array_push($changedAssignments, $tempAssignment);

                    break;
                }
            }
        }

        return $changedAssignments;
    }

    /**
     * Creates and returns a fully formed class object
     * @param \Objects\ClassObj $tempClassObj
     * @return \Objects\ClassObj
     */
    private function _getClass($tempClassObj)
    {
        $this->load->model("student_model");
        $this->load->model("assignment_model");

        $students = $this->student_model->getStudents($tempClassObj->class_id);
        $assignments = $this->assignment_model->getAssignments($tempClassObj);
        $assignmentList = $this->_getAssignmentList($assignments);

        $this->_setAssignmentList($assignmentList, $students);

        $classObj = new \Objects\ClassObj($assignmentList, $students);

        /**
         * Copies all properties from $tempClassObj to $classObj
         */
        foreach ($tempClassObj as $propertyName => $value) {
            if (isset($value)) {
                $classObj->$propertyName = $value;
            }
        }

        return $classObj;
    }

    /**
     * Loads a table, creates and returns a classObj;
     *      throws an exception if class not found
     * @param string $propertyName best options are 'class_id', and 'table_name'
     * @param string $property
     * @return \Objects\ClassObj
     * @throws \Exception
     */
    private function _getClassBy($propertyName, $property)
    {
        /**
         * Gets classes from the db that match the table name
         */
        $this->load->model("class_list_model");
        $classes = $this->class_list_model->getClassesBy($propertyName, $property);

        if (isset($classes[0])) {
            return $this->_getClass($classes[0]);
        }

        throw new \Exception("No class with such $propertyName found: '$property'");
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