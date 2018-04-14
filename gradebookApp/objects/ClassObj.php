<?php namespace Objects;

/**
 * Represents a class
 *      includes the assignment list, students, and the students' grades
 * Class ClassObj
 * @package Objects
 */
class ClassObj implements \Interfaces\GradeStatistics, \JsonSerializable
{
    public $class_id;
    public $professor_id;
    public $class_name;
    public $section;
    public $class_title;
    public $meeting_times;
    public $table_name;

    /**
     * Assignment list object
     * @var AssignmentList
     */
    private $assignmentList;
    /**
     * Array of student objects
     * @var Student[]
     */
    private $students;
    /**
     * Array of students' grades
     *      used for statistics
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
        if (is_null($assignmentList)) {
            $assignmentList = new AssignmentList();
        }

        $this->assignmentList = $assignmentList;
        $this->students = $students;
    }

    /**
     * Do nothing
     * __set is typically used to set values where an error would otherwise be thrown
     *      such as when accessing a private field, or a field that doesn't exist
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
    }

    /**
     * Gets the Assignment list object
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
     * Gets the array of student objects
     * @return Student[]
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * Gets an individual student object
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
     * Specify data which should be serialized to JSON
     * many thanks to: https://stackoverflow.com/a/20117815
     * @return mixed data which can be serialized by json_encode,
     *      which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
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