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

    public function loadTable($tableName, $studentId = null)
    {
        $al = "assignment_list";
        $sl = "student_list";
        $selectStudent = "$tableName.student_id, name_first, name_last";
        $selectAssignment = "assignment_id, $al.name as assignment_name, $tableName.points, $al.points as max_points, graded";

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

    public function getStudent($studentId)
    {
        foreach ($this->students as $student) {
            if ($studentId == $student->student_id) {
                return $student;
            }
        }
        return null;
    }
}