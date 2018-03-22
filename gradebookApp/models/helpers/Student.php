<?php

/**
 * Represents a student
 * Class Student
 */
class Student
{
    public $student_id;
    public $name_first;
    public $name_last;

    /**
     * Assignment list object,
     *      matches copy in class object
     * @var AssignmentList
     */
    public $assignmentList;
    /**
     * Contains information used to calculate grades for each assignment category
     *      where keys are assignment categories
     * @var array
     */
    public $groups = array();

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
     * Gets the assignments with the student
     * @return Assignment[]
     */
    public function getAssignments()
    {
        return $this->assignmentList->getAssignments();
    }

    /**
     * Gets the overall grade of the student (0-100)
     * @return number
     */
    public function getGrade()
    {
        $this->_initGroups();
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
        if (isset($groupInfo["grade"])) {
            return $groupInfo["grade"];
        } else {
            return 0;
        }
    }

    /**
     * Gets whether or not an assignment group is graded
     * @param $group
     * @return boolean
     */
    public function getGroupGraded($group)
    {
        $groupInfo = $this->_getGroupInfo($group);
        return $groupInfo["graded"];
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
     *      for each assignment group or for a single assignment group
     * @param string $groupName
     */
    private function _calculatePoints($groupName = null)
    {
        $assignmentGroups = $this->assignmentList->getGroupedAssignments();
        if (is_null($groupName)) {
            foreach ($assignmentGroups as $groupName => $assignmentGroup) {
                $this->_calculatePointsGroup($groupName, $assignmentGroup);
            }
        } else {
            $assignmentGroup = $assignmentGroups[$groupName];
            $this->_calculatePointsGroup($groupName, $assignmentGroup);
        }
    }

    /**
     * Calculates the points and maxPoints for the student
     *      for a single assignment group
     * @param string $groupName
     * @param AssignmentList $assignmentGroup
     */
    private function _calculatePointsGroup($groupName, $assignmentGroup)
    {
        $studentId = $this->student_id;
        $assignments = $assignmentGroup->getAssignments();
        $this->groups[$groupName] = array(
            "graded" => 0,
            "points" => 0,
            "maxPoints" => 0,
            "weight" => $assignmentGroup->getGroupWeight(),
        );

        foreach ($assignments as $assignment) {
            /**
             * @var Assignment $assignment
             */
            if ($assignment->graded) {
                $this->groups[$groupName]["graded"] = 1;
                $this->groups[$groupName]["points"] += +$assignment->getPoints($studentId);
                $this->groups[$groupName]["maxPoints"] += +$assignment->max_points;
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
            if ($groupInfo["graded"]) {
                $grade += $groupInfo["weightedGrade"];
                $totalWeight += $groupInfo["weight"];
            }
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
            $this->_calculatePoints($group);
            $temp = $this->groups[$group];
        }
        $groupInfo = array(
            "graded" => $temp["graded"],
            "points" => $temp["points"],
            "maxPoints" => $temp["maxPoints"],
            "weight" => $temp["weight"] / 100,
        );
        if ($temp["maxPoints"] != 0) {
            $groupInfo["grade"] = $temp["points"] / $temp["maxPoints"] * 100;
            $groupInfo["weightedGrade"] = $groupInfo["grade"] * $groupInfo["weight"];
        }
        return $groupInfo;
    }

    /**
     * Initializes the groups as necessary
     */
    private function _initGroups()
    {
        $assignmentGroups = $this->assignmentList->getGroupedAssignments();
        $groupNames = array_keys($assignmentGroups);

        foreach ($groupNames as $groupName) {
            if (!isset($this->groups[$groupName])) {
                $this->groups[$groupName] = array(
                    "graded" => 0,
                    "points" => 0,
                    "maxPoints" => 0,
                    "weight" => 0,
                );
            }
        }
    }
}