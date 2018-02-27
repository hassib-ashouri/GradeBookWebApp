<?php

class Student
{
    public $student_id;
    public $name_first;
    public $name_last;
    /**
     * @var Assignment[]
     */
    private $assignments = array();
    private $points;
    private $maxPoints;

    public function __set($name, $value)
    {
    }

    public function __toString()
    {
        return "$this->name_last, $this->name_first $this->student_id";
    }

    /**
     * @param $assignment Assignment
     */
    public function addAssignment($assignment)
    {
        if ($this->student_id === $assignment->student_id) {
            array_push($this->assignments, $assignment);
        }
    }

    /**
     * Gets the grade of the student (0-100)
     * @return number
     */
    public function getGrade()
    {
        if (!isset($this->points) || !isset($this->maxPoints)) {
            $this->_calculatePoints();
        }

        return $this->_getGrade();
    }

    /**
     * Calculates the points and maxPoints for the student
     */
    private function _calculatePoints()
    {
        $this->points = 0;
        $this->maxPoints = 0;
        foreach ($this->assignments as $assignment) {
            if ($assignment->graded) {
                $this->points += +$assignment->points;
                $this->maxPoints += +$assignment->max_points;
            }
        }
    }

    /**
     * Gets the grade of the student (0-100)
     * @return number
     */
    private function _getGrade()
    {
        if ($this->maxPoints > 0) {
            return round($this->points / $this->maxPoints * 100, 2);
        } else {
            return 100;
        }
    }

    /**
     * @return number
     */
    public function getPoints()
    {
        if (isset($this->points)) {
            return $this->points;
        } else {
            $this->_calculatePoints();
            return $this->points;
        }
    }

    /**
     * @return number
     */
    public function getMaxPoints()
    {
        if (isset($this->maxPoints)) {
            return $this->maxPoints;
        } else {
            $this->_calculatePoints();
            return $this->maxPoints;
        }
    }

    public function getAssignments()
    {
        return $this->assignments;
    }
}