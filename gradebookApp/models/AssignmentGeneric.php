<?php

class AssignmentGeneric
{
    public $assignment_id;
    public $assignment_name;
    public $description;
    public $type;
    public $weight;
    public $max_points;
    public $graded;

    public function createFromAssignment($assignment)
    {
        $this->assignment_id = $assignment->assignment_id;
        $this->assignment_name = $assignment->assignment_name;
        $this->description = $assignment->description;
        $this->type = $assignment->type;
        $this->weight = $assignment->weight;
        $this->max_points = $assignment->max_points;
        $this->graded = $assignment->graded;

        return $this;
    }
}