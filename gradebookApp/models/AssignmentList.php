<?php

require_once "GradeStatistics.php";

class AssignmentList implements GradeStatistics
{
    /**
     * @var Assignment[]
     */
    private $assignments = array();
    /**
     * @var number[]
     */
    private $assignmentPoints;

    /**
     * @param Assignment $assignment
     */
    public function addAssignment($assignment)
    {
        array_push($this->assignments, $assignment);
        $assignment->setAssignmentList($this);
    }

    /**
     * Gets the name of the Assignment
     * @return string
     */
    public function getAssignmentName()
    {
        return $this->assignments[0]->assignment_name;
    }

    /**
     * Gets the lowest of the grades
     * @return number
     */
    public function getLowGrade()
    {
        $this->_setAssignmentPoints();
        return min($this->assignmentPoints);
    }

    /**
     * Gets the highest of the grades
     * @return number
     */
    public function getHighGrade()
    {
        $this->_setAssignmentPoints();
        return max($this->assignmentPoints);
    }

    /**
     * Gets the mean of the grades
     * @return number
     */
    public function getMeanGrade()
    {
        $this->_setAssignmentPoints();
        return array_sum($this->assignmentPoints) / count($this->assignmentPoints);
    }

    /**
     * Gets the variance of the grades
     * @return number
     */
    public function getVarGrade()
    {
        $this->_setAssignmentPoints();
        $mean = $this->getMeanGrade();
        $sumSquares = 0;
        foreach ($this->assignmentPoints as $assignmentPoint) {
            $sumSquares += pow($assignmentPoint - $mean, 2);
        }
        return $sumSquares / count($this->assignmentPoints);
    }

    /**
     * Gets the standard deviation of the grades
     * @return number
     */
    public function getStdDevGrade()
    {
        $variance = $this->getVarGrade();
        return sqrt($variance);
    }

    /**
     * Initializes the assignmentPoints array
     */
    private function _setAssignmentPoints()
    {
        if (!isset($this->assignmentPoints)) {
            $this->assignmentPoints = array();
            foreach ($this->assignments as $assignment) {
                array_push($this->assignmentPoints, $assignment->points);
            }
        }
    }
}