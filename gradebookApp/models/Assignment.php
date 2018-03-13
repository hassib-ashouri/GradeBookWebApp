<?php

require_once "AssignmentGeneric.php";
require_once "GradeStatistics.php";

class Assignment extends AssignmentGeneric implements GradeStatistics
{
    public $student_id;
    public $points;
    /**
     * @var AssignmentList
     */
    private $assignmentList;

    public function __set($name, $value)
    {
    }

    /**
     * @param AssignmentList $assignmentList
     */
    public function setAssignmentList($assignmentList)
    {
        $this->assignmentList = $assignmentList;
    }

    /**
     * Gets a generic copy of the assignment
     *      without student_id, or points assigned
     * @return AssignmentGeneric
     */
    public function getGenericAssignment()
    {
        $assignment = new AssignmentGeneric();
        $assignment->createFromAssignment($this);
        return $assignment;
    }

    /**
     * Gets the lowest of the grades
     * @return number
     */
    public function getLowGrade()
    {
        return $this->assignmentList->getLowGrade();
    }

    /**
     * Gets the highest of the grades
     * @return number
     */
    public function getHighGrade()
    {
        return $this->assignmentList->getHighGrade();
    }

    /**
     * Gets the mean of the grades
     * @return number
     */
    public function getMeanGrade()
    {
        return $this->assignmentList->getMeanGrade();
    }

    /**
     * Gets the median of the grades
     * @return number
     */
    public function getMedianGrade()
    {
        return $this->assignmentList->getMedianGrade();
    }

    /**
     * Gets the variance of the grades
     * @return number
     */
    public function getVarGrade()
    {
        return $this->assignmentList->getVarGrade();
    }

    /**
     * Gets the standard deviation of the grades
     * @return number
     */
    public function getStdDevGrade()
    {
        return $this->assignmentList->getStdDevGrade();
    }
}