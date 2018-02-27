<?php

class ClassObj
{
    public $class_id;
    public $professor_id;
    public $class_name;
    public $table_name;

    /**
     * @var Assignment[]
     */
    private $assignments;
    /**
     * @var Student[]
     */
    private $students;

    /**
     * ClassObj constructor.
     * @param Assignment[] $assignments
     * @param Student[] $students
     */
    public function __construct($assignments = array(), $students = array())
    {
        $this->assignments = $assignments;
        $this->students = $students;
    }

    public function __set($name, $value)
    {
    }

    /**
     * Gets the array of assignments
     * @return Assignment[]
     */
    public function getAssignments()
    {
        return $this->assignments;
    }

    /**
     * Gets the array of students
     * @return Student[]
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * Gets an individual student
     *      returns null if student not found
     * @param $studentId
     * @return null|Student
     */
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