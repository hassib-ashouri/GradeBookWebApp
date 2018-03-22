<?php

require_once "GradeStatistics.php";

/**
 * Represents an assignment
 *      with all students' grades included
 * Class Assignment
 */
class Assignment implements GradeStatistics
{
    public $assignment_id;
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
     * Sets the points for a student
     * @param string $studentId
     * @param number $points
     */
    public function setPoints($studentId, $points)
    {
        $this->grades[$studentId] = +$points;
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
}