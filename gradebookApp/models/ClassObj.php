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
     * @var AssignmentList
     */
    private $assignmentList;
    /**
     * @var Student[]
     */
    private $students;
    /**
     * @var number[]
     */
    private $studentGrades;

    /**
     * ClassObj constructor.
     * @param AssignmentList $assignmentList
     * @param Student[] $students
     */
    public function __construct($assignmentList = null, $students = array())
    {
        require_once "Assignment.php";
        require_once "AssignmentList.php";
        require_once "Student.php";

        if (is_null($assignmentList)) {
            $assignmentList = new AssignmentList();
        }

        $this->assignmentList = $assignmentList;
        $this->students = $students;
    }

    public function __set($name, $value)
    {
    }

    /**
     * Gets the Assignment List
     * @return AssignmentList
     */
    public function getAssignmentList()
    {
        return $this->assignmentList;
    }

    /**
     * Gets the array of Assignments from Assignment List
     * @return Assignment[]
     */
    public function getAssignments()
    {
        return $this->assignmentList->getAssignments();
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
        $this->_setStudentGrades();
        return gradeLow($this->studentGrades);
    }

    /**
     * Gets the highest of the grades
     * @return number
     */
    public function getHighGrade()
    {
        $this->_setStudentGrades();
        return gradeHigh($this->studentGrades);
    }

    /**
     * Gets the mean of the grades
     * @return number
     */
    public function getMeanGrade()
    {
        $this->_setStudentGrades();
        return gradeMean($this->studentGrades);
    }

    /**
     * Gets the median of the grades
     * @return number
     */
    public function getMedianGrade()
    {
        $this->_setStudentGrades();
        return gradeMedian($this->studentGrades);
    }

    /**
     * Gets the variance of the grades
     * @return number
     */
    public function getVarGrade()
    {
        $this->_setStudentGrades();
        return gradeVar($this->studentGrades);
    }

    /**
     * Gets the standard deviation of the grades
     * @return number
     */
    public function getStdDevGrade()
    {
        $this->_setStudentGrades();
        return gradeStdDev($this->studentGrades);
    }

    /**
     * Initializes the studentGrades array
     */
    private function _setStudentGrades()
    {
        if (!isset($this->studentGrades)) {
            $this->studentGrades = array();
            foreach ($this->students as $student) {
                array_push($this->studentGrades, $student->getGrade());
            }
        }
    }
}