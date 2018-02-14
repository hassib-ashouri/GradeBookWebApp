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
        $points = 0;
        $maxPoints = 0;
        foreach ($this->assignments as $assignment) {
            if ($assignment->graded) {
                $points += intval($assignment->points);
                $maxPoints += intval($assignment->max_points);
            }
        }

        if ($maxPoints > 0) {
            return round($points / $maxPoints * 100, 2);
        } else {
            return 100;
        }
    }

    public function getAssignments()
    {
        return $this->assignments;
    }
}