<?php

class Class_model extends MY_Model
{
    /**
     * @var ClassObj
     */
    private $classObj;

    // model is in charge of crud: create, read, update, delete
    public function __construct()
    {
        parent::__construct();

        require_once "Assignment.php";
        require_once "Student.php";
        require_once "ClassObj.php";
    }

    /**
     * Loads a table and creates a classObj out of it
     * @param $tableName
     * @param null $studentId
     */
    public function loadTable($tableName, $studentId = null)
    {
        $al = "assignment_list";
        $sl = "student_list";
        $selectStudent = "$tableName.student_id, name_first, name_last";
        $selectAssignment = "assignment_id, $al.name as assignment_name, type, weight, $tableName.points, $al.points as max_points, graded";

        if (!is_null($studentId)) {
            $this->db->where("$tableName.student_id", $studentId);
        }

        $query = $this->db
            ->select("$selectStudent, $selectAssignment")
            ->from($tableName)
            ->join($al, "$tableName.assignment_id = $al.id")
            ->join($sl, "$tableName.student_id = $sl.student_id")
            ->get();
        // needed for the other two to behave
        $query->result_array();

        /**
         * @var Assignment[] $assignments
         */
        $assignments = $query->result("Assignment");
        /**
         * @var Student[] $students
         */
        $students = $query->result("Student");
        $students = array_unique($students, SORT_STRING);

        foreach ($students as $student) {
            foreach ($assignments as $assignment) {
                $student->addAssignment($assignment);
            }
        }

        $this->classObj = new ClassObj($assignments, $students);
    }

    /**
     * @return ClassObj
     */
    public function getClass()
    {
        return $this->classObj;
    }
}