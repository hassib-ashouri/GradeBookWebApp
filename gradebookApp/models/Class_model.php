<?php

/**
 * Database interaction for classes;
 * Used for reading, and editing class info,
 *      such as enrolled students
 * Class Class_model
 */
class Class_model extends MY_Model
{
    /**
     * Class object,
     *      created from class table in the database
     * @var ClassObj
     */
    private $classObj;

    // model is in charge of crud: create, read, update, delete
    public function __construct()
    {
        parent::__construct();

        require_once "helpers/Assignment.php";
        require_once "helpers/AssignmentList.php";
        require_once "helpers/Student.php";
        require_once "helpers/ClassObj.php";
    }

    /**
     * Loads a table, creates and returns a classObj
     * @param string $classTableName
     * @return ClassObj
     */
    public function getClass($classTableName)
    {
        $matches = array();
        preg_match("/class_(\d{5})_.*?_\d{2}_table/", $classTableName, $matches);
        $classId = $matches[1];

        /**
         * Creates $students from "students" and "students_enrolled"
         *      matches against $classId and $section
         * @var Student[] $students
         */
        $students = $this->db
            ->select("students.student_id, name_first, name_last")
            ->from("students_enrolled")
            ->where("class_id", $classId)
            ->join("students", "students_enrolled.student_id = students.student_id")
            ->get()->result("Student");
        /**
         * Creates $assignmentResult from $classTable and "assignments"
         */
        $assignmentResult = $this->db
            ->select("student_id, assignment_id, assignment_name, description, type, weight, points, max_points, graded")
            ->from($classTableName)
            ->join("assignments", "assignment_id = assignments.id")
            ->get()->result_array();

        /**
         * Creates $assignments from $assignmentResult
         * @var Assignment[] $assignments
         */
        $assignments = array();
        foreach ($assignmentResult as $assignment) {
            $assignId = $assignment["assignment_id"];
            $studentId = $assignment["student_id"];
            $points = $assignment["points"];

            if (!isset($assignments[$assignId])) {
                $assignments[$assignId] = new Assignment();
                foreach ($assignment as $key => $value) {
                    $assignments[$assignId]->$key = $value;
                }
            }
            $assignments[$assignId]->setPoints($studentId, $points);
        }

        /**
         * Creates $assignmentList from $assignments
         * @var AssignmentList $assignmentList
         */
        $assignmentList = new AssignmentList();
        foreach ($assignments as $assignment) {
            $assignmentList->addAssignment($assignment);
        }
        foreach ($students as $student) {
            $student->assignmentList = $assignmentList;
        }

        $this->classObj = new ClassObj($assignmentList, $students);
        $this->classObj->table_name = $classTableName;

        return $this->classObj;
    }

    //todo public function addStudents($studentIds)
    //todo public function removeStudents($studentIds)
}