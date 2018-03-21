<?php

require_once "GradeStatistics.php";

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
     * @var number[]
     */
    private $grades = array();
    /**
     * @var number[]
     */
    private $points;

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
        $this->_setPoints();
        return gradeLow($this->points);
    }

    /**
     * Gets the highest of the grades
     * @return number
     */
    public function getHighGrade()
    {
        $this->_setPoints();
        return gradeHigh($this->points);
    }

    /**
     * Gets the mean of the grades
     * @return number
     */
    public function getMeanGrade()
    {
        $this->_setPoints();
        return gradeMean($this->points);
    }

    /**
     * Gets the median of the grades
     * @return number
     */
    public function getMedianGrade()
    {
        $this->_setPoints();
        return gradeMedian($this->points);
    }

    /**
     * Gets the variance of the grades
     * @return number
     */
    public function getVarGrade()
    {
        $this->_setPoints();
        return gradeVar($this->points);
    }

    /**
     * Gets the standard deviation of the grades
     * @return number
     */
    public function getStdDevGrade()
    {
        $this->_setPoints();
        return gradeStdDev($this->points);
    }

    /**
     * Initializes the assignmentPoints array
     */
    private function _setPoints()
    {
        if (!isset($this->points)) {
            $this->points = array();
            foreach ($this->grades as $grade) {
                array_push($this->points, $grade);
            }
        }
    }
}