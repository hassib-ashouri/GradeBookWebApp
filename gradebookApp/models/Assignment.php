<?php

class Assignment
{
    public $student_id;
    public $assignment_id;
    public $assignment_name;
    public $points;
    public $max_points;
    public $graded;

    public function __set($name, $value)
    {
    }
}