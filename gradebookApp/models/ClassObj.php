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
     * @var AssignmentList[]
     */
    private $assignmentLists;
    /**
     * @var number[]
     */
    private $studentGrades;

    /**
     * ClassObj constructor.
     * @param Assignment[] $assignments
     * @param Student[] $students
     */
    public function __construct($assignments = array(), $students = array())
    {
        require_once "Assignment.php";
        require_once "AssignmentList.php";
        require_once "Student.php";

        $this->assignments = $assignments;
        $this->students = $students;

        $this->_setAssignmentLists();
    }

    public function __set($name, $value)
    {
    }

    /**
     * Gets the array of AssignmentLists
     * @return AssignmentList[]
     */
    public function getAssignmentLists()
    {
        return $this->assignmentLists;
    }

    /**
     * Gets the array of Assignments
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
        $this->_setStudentGrades();
        return min($this->studentGrades);
    }

    /**
     * Gets the highest of the grades
     * @return number
     */
    public function getHighGrade()
    {
        $this->_setStudentGrades();
        return max($this->studentGrades);
    }

    /**
     * Gets the mean of the grades
     * @return number
     */
    public function getMeanGrade()
    {
        $this->_setStudentGrades();
        return array_sum($this->studentGrades) / count($this->studentGrades);
    }

    /**
     * Gets the variance of the grades
     * @return number
     */
    public function getVarGrade()
    {
        $this->_setStudentGrades();
        $mean = $this->getMeanGrade();
        $sumSquares = 0;
        foreach ($this->studentGrades as $studentGrade) {
            $sumSquares += pow($studentGrade - $mean, 2);
        }
        return $sumSquares / count($this->studentGrades);
    }

    /**
     * Gets the standard deviation of the grades
     * @return number
     */
    public function getStdDevGrade()
    {
        $variance = $this->getVarGrade();
        return sqrt($variance);
    }

    /**
     * Populates assignmentLists
     */
    private function _setAssignmentLists()
    {
        $this->assignmentLists = array();
        foreach ($this->assignments as $assignment) {
            $id = $assignment->assignment_id;
            if (!isset($this->assignmentLists[$id])) {
                $this->assignmentLists[$id] = new AssignmentList();
            }
            $this->assignmentLists[$id]->addAssignment($assignment);
        }
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