<?php namespace Objects;

/**
 * Represents an assignment
 *      with all students' grades included
 * Class Assignment
 * @package Objects
 */
class Assignment implements \Interfaces\GradeStatistics, \JsonSerializable
{
    public $assignment_id;
    public $class_id;
    public $assignment_name;
    public $description;
    public $type;
    public $weight;
    public $max_points;
    public $graded;

    /**
     * Previous value of max_points
     * @var number
     */
    public $max_points_old;
    /**
     * Array of students' points for assignment
     *      where keys are student ids
     * @var number[]
     */
    private $grades = array();

    /**
     * Static identifier for new assignment ids
     * @var int
     */
    const NEW_ASSIGNMENT_ID = -1;

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
     * Gets the grades array;
     *      only used for updating database
     * @return number[]
     */
    public function getAllPoints() {
        return $this->grades;
    }

    /**
     * Gets the points of a student
     * @param string $studentId
     * @return number
     */
    public function getPoints($studentId)
    {
        if (isset($this->grades[$studentId])) {
            return $this->grades[$studentId];
        } else {
            return 0;
        }
    }

    /**
     * Sets this assignment as new
     */
    public function markAsNew()
    {
        $this->assignment_id = self::NEW_ASSIGNMENT_ID;
    }

    /**
     * Sets the points for a student
     * @param string $studentId
     * @param number $points
     */
    public function setPoints($studentId, $points)
    {
        if (strlen($studentId) == 9) {
            $this->grades[$studentId] = +$points;
        }
    }

    /**
     * Gets the lowest of the grades
     * @return number
     */
    public function getLowGrade()
    {
        return gradeLow($this->grades);
    }

    /**
     * Gets the highest of the grades
     * @return number
     */
    public function getHighGrade()
    {
        return gradeHigh($this->grades);
    }

    /**
     * Gets the mean of the grades
     * @return number
     */
    public function getMeanGrade()
    {
        return gradeMean($this->grades);
    }

    /**
     * Gets the median of the grades
     * @return number
     */
    public function getMedianGrade()
    {
        return gradeMedian($this->grades);
    }

    /**
     * Gets the variance of the grades
     * @return number
     */
    public function getVarGrade()
    {
        return gradeVar($this->grades);
    }

    /**
     * Gets the standard deviation of the grades
     * @return number
     */
    public function getStdDevGrade()
    {
        return gradeStdDev($this->grades);
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
}