<?php

require_once "GradeStatistics.php";

/**
 * Class AssignmentList
 * Represents all students' copies of a single assignment in a class
 *      mainly used for statistics
 */
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
     * Creates a new assignment for a student with a specified point value
     *      todo could be rendered unnecessary depending on how the db is structured
     *      todo if we create an entry for every student for every assignment in the db...
     * @param string $studentId
     * @param int|number $points
     * @return Assignment
     */
    public function getNewAssignment($studentId, $points = 0)
    {
        $assignment = new Assignment();

        $assignment->student_id = $studentId;
        $assignment->points = $points;

        $assignment->assignment_id = $this->assignments[0]->assignment_id;
        $assignment->assignment_name = $this->assignments[0]->assignment_name;
        $assignment->description = $this->assignments[0]->description;
        $assignment->type = $this->assignments[0]->type;
        $assignment->weight = $this->assignments[0]->weight;
        $assignment->max_points = $this->assignments[0]->max_points;
        $assignment->graded = $this->assignments[0]->graded;

        $this->addAssignment($assignment);

        return $assignment;
    }

    /**
     * Checks if there is an assignment for a given student
     *      todo could be rendered unnecessary depending on how the db is structured
     *      todo if we create an entry for every student for every assignment in the db...
     * @param string $studentId
     * @return bool
     */
    public function studentHasAssignment($studentId)
    {
        foreach ($this->assignments as $assignment) {
            if ($assignment->student_id === $studentId) {
                return true;
            }
        }
        return false;
    }

    /**
     * Gets the lowest of the grades
     * @return number
     */
    public function getLowGrade()
    {
        $this->_setAssignmentPoints();
        return gradeLow($this->assignmentPoints);
    }

    /**
     * Gets the highest of the grades
     * @return number
     */
    public function getHighGrade()
    {
        $this->_setAssignmentPoints();
        return gradeHigh($this->assignmentPoints);
    }

    /**
     * Gets the mean of the grades
     * @return number
     */
    public function getMeanGrade()
    {
        $this->_setAssignmentPoints();
        return gradeMean($this->assignmentPoints);
    }

    /**
     * Gets the median of the grades
     * @return number
     */
    public function getMedianGrade()
    {
        $this->_setAssignmentPoints();
        return gradeMedian($this->assignmentPoints);
    }

    /**
     * Gets the variance of the grades
     * @return number
     */
    public function getVarGrade()
    {
        $this->_setAssignmentPoints();
        return gradeVar($this->assignmentPoints);
    }

    /**
     * Gets the standard deviation of the grades
     * @return number
     */
    public function getStdDevGrade()
    {
        $this->_setAssignmentPoints();
        return gradeStdDev($this->assignmentPoints);
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