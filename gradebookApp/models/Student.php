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

    public function getGrade()
    {
        if (!isset($this->points) || !isset($this->maxPoints)) {
            $this->_calculatePoints();
        }

        return $this->_getGrade();
    }

    private function _calculatePoints()
    {
        $this->points = 0;
        $this->maxPoints = 0;
        foreach ($this->assignments as $assignment) {
            if ($assignment->graded) {
                $this->points += intval($assignment->points);
                $this->maxPoints += intval($assignment->max_points);
            }
        }
    }

    private function _getGrade()
    {
        if ($this->maxPoints > 0) {
            return round($this->points / $this->maxPoints * 100, 2);
        } else {
            return 100;
        }
    }

    public function getPoints()
    {
        if (isset($this->points)) {
            return $this->points;
        } else {
            $this->_calculatePoints();
            return $this->points;
        }
    }

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