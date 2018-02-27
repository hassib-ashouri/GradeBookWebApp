<?php

require_once "GradeStatistics.php";

class ClassObj implements GradeStatistics
{
    public $class_id;
    public $professor_id;
    public $class_name;
    public $section;
    public $class_title;
    public $meeting_times;
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
        require_once "Assignment.php";
        require_once "Student.php";

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

    /**
     * Gets the lowest of the grades
     * @return number
     */
    public function getLowGrade()
    {
        // TODO: Implement getLowGrade() method.
    }

    /**
     * Gets the highest of the grades
     * @return number
     */
    public function getHighGrade()
    {
        // TODO: Implement getHighGrade() method.
    }

    /**
     * Gets the mean of the grades
     * @return number
     */
    public function getMeanGrade()
    {
        // TODO: Implement getMeanGrade() method.
    }

    /**
     * Gets the standard deviation of the grades
     * @return number
     */
    public function getStdDevGrade()
    {
        // TODO: Implement getStdDevGrade() method.
    }
}