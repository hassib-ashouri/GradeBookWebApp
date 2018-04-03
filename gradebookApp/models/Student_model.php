<?php namespace Models;

/**
 * Database interaction for students;
 * Class Student_model
 */
class Student_model extends \MY_Model
{
    // model is in charge of crud: create, read, update, delete
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets a student from the db
     *      if doesn't exist, returns null
     * @param $studentId
     * @return \Objects\Student|null
     */
    public function getStudent($studentId)
    {
        $student = $this->db
            ->select("student_id, name_first, name_last")
            ->from("students")
            ->where("student_id", $studentId)
            ->get()->row(0, "\Objects\Student");
        return $student;
    }

    /**
     * Creates $students from "students" and "students_enrolled"
     *      matches against $classId
     * @param string $classId
     * @return \Objects\Student[]
     */
    public function getStudents($classId)
    {
        $students = $this->db
            ->select("students.student_id, name_first, name_last")
            ->from("students_enrolled")
            ->where("class_id", $classId)
            ->join("students", "students_enrolled.student_id = students.student_id")
            ->get()->result("\Objects\Student");

        return $students;
    }
}