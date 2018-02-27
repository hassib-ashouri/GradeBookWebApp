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
    private $groups = array();

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
            if (!isset($this->groups[$assignment->type])) {
                $this->groups[$assignment->type] = array(
                    "points" => 0,
                    "maxPoints" => 0,
                    "weight" => $assignment->weight,
                );
            }
        }
    }

    /**
     * Gets the assignments associated with the student
     * @return Assignment[]
     */
    public function getAssignments()
    {
        return $this->assignments;
    }

    /**
     * Gets the overall grade of the student (0-100)
     * @return number
     */
    public function getGrade()
    {
        foreach ($this->groups as $group) {
            if ($group["maxPoints"] == 0) {
                $this->_calculatePoints();
                break;
            }
        }

        return $this->_getGrade();
    }

    /**
     * Gets the group grade of the student (0-100)
     *      for a specified group
     * @param string $group
     * @return number
     */
    public function getGroupGrade($group)
    {
        $groupInfo = $this->_getGroupInfo($group);
        return $groupInfo["grade"];
    }

    /**
     * Gets the points for a specified group
     * @param $group
     * @return number
     */
    public function getGroupPoints($group)
    {
        $groupInfo = $this->_getGroupInfo($group);
        return $groupInfo["points"];
    }

    /**
     * Gets the maxPoints for a specified group
     * @param $group
     * @return number
     */
    public function getGroupMaxPoints($group)
    {
        $groupInfo = $this->_getGroupInfo($group);
        return $groupInfo["maxPoints"];
    }

    /**
     * Gets the names of the groups
     * @return string[]
     */
    public function getGroupNames()
    {
        return array_keys($this->groups);
    }

    /**
     * Calculates the points and maxPoints for the student
     *      for each assignment group
     */
    private function _calculatePoints()
    {
        foreach ($this->assignments as $assignment) {
            if ($assignment->graded) {
                $this->groups[$assignment->type]["points"] += +$assignment->points;
                $this->groups[$assignment->type]["maxPoints"] += +$assignment->max_points;
            }
        }
    }

    /**
     * Gets the overall grade of the student (0-100)
     * @return number
     */
    private function _getGrade()
    {
        $grade = 0;
        $totalWeight = 0;
        foreach ($this->groups as $group => $unused) {
            $groupInfo = $this->_getGroupInfo($group);
            $grade += $groupInfo["weightedGrade"];
            $totalWeight += $groupInfo["weight"];
        }
        if (count($this->groups) == 0) {
            return 100;
        }
        return $grade / $totalWeight;
    }

    /**
     * Gets info about the group,
     *      includes grade, weight, and weightedGrade
     * @param string $group
     * @return array
     */
    private function _getGroupInfo($group)
    {
        $temp = $this->groups[$group];
        if ($temp["maxPoints"] == 0) {
            $this->_calculatePoints();
            $temp = $this->groups[$group];
        }
        $groupInfo = array(
            "grade" => $temp["points"] / $temp["maxPoints"] * 100,
            "points" => $temp["points"],
            "maxPoints" => $temp["maxPoints"],
            "weight" => $temp["weight"] / 100,
        );
        $groupInfo["weightedGrade"] = $groupInfo["grade"] * $groupInfo["weight"];
        return $groupInfo;
    }
}