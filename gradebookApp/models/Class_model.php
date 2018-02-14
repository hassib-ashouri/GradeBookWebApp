<?php

class Class_model extends MY_Model
{
    /**
     * @var Assignment[]
     */
    private $assignments;
    /**
     * @var Student[]
     */
    private $students;

    // model is in charge of crud: create, read, update, delete
    public function __construct()
    {
        parent::__construct();

        require_once "Assignment.php";
        require_once "Student.php";
    }

    public function loadTable($tableName)
    {
        $selectStudent = "$tableName.student_id, name_first, name_last";
        $selectAssignment = "assignment_id, $tableName.points, assignment_list.points as max_points, graded";

        $query = $this->db
            ->select("$selectStudent, $selectAssignment")
            ->from($tableName)
            ->join("assignment_list", "$tableName.assignment_id = assignment_list.id")
            ->join("student_list", "$tableName.student_id = student_list.student_id")
            ->get();
        // needed for the other two to behave
        $query->result_array();

        $this->assignments = $query->result("Assignment");
        $this->students = $query->result("Student");
        $this->students = array_unique($this->students, SORT_STRING);

        foreach ($this->students as $student) {
            foreach ($this->assignments as $assignment) {
                $student->addAssignment($assignment);
            }
        }
    }

    public function getStudents()
    {
        return $this->students;
    }
}