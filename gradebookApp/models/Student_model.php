<?php

/**
 * Database interaction for students;
 * Not used for reading students,
 *      only create, update, and delete
 * Class Student_model
 */
class Student_model extends MY_Model
{
    // model is in charge of crud: create, read, update, delete
    public function __construct()
    {
        parent::__construct();

        require_once "helpers/Student.php";
    }

    /**
     * Gets a student from the db
     *      if doesn't exist, returns null
     * @param $studentId
     * @return Student|null
     */
    public function getStudent($studentId)
    {
        $student = $this->db
            ->select("student_id, name_first, name_last")
            ->from("students")
            ->where("student_id", $studentId)
            ->get()->row(0, "Student");
        return $student;
    }
}