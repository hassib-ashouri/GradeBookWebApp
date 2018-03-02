<?php

require_once "GradeStatistics.php";

class Assignment implements GradeStatistics
{
    public $student_id;
    public $assignment_id;
    public $assignment_name;
    public $description;
    public $type;
    public $weight;
    public $points;
    public $max_points;
    public $graded;
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