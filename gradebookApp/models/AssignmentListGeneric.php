<?php

class AssignmentListGeneric
{
    /**
     * @var bool
     */
    public $doGroup = true;

    /**
     * @var AssignmentGeneric[]
     */
    private $genericAssignments = array();
    /**
     * @var AssignmentListGeneric[]
     */
    private $grouped = array();

    public function __construct()
    {
        require_once "AssignmentGeneric.php";
    }

    /**
     * Adds a new assignment to the list
     * @param Assignment|AssignmentGeneric $assignment
     */
    public function addAssignment($assignment)
    {
        if (is_a($assignment, "Assignment")) {
            $generic = $assignment->getGenericAssignment();
        } else {
            $generic = $assignment;
        }

        $id = $generic->assignment_id;
        $group = $generic->type;
        if (!isset($this->genericAssignments[$id])) {
            $this->genericAssignments[$id] = $generic;
            // only applies if top level
            if ($this->doGroup) {
                if (!isset($this->grouped[$group])) {
                    $this->grouped[$group] = new AssignmentListGeneric();
                    $this->grouped[$group]->doGroup = false;
                }
                $this->grouped[$group]->addAssignment($generic);
            }
        }
    }

    /**
     * Gets the array of Generic Assignments
     * @return AssignmentGeneric[]
     */
    public function getGenericAssignments()
    {
        return $this->genericAssignments;
    }

    /**
     * Gets the array of Generic Assignment Lists
     *      where the keys are the groups of the assignments
     * @return AssignmentListGeneric[]
     */
    public function getGroupedAssignments()
    {
        return $this->grouped;
    }

    /**
     * Gets the name of the groups
     * @return string[]
     */
    public function getGroupNames()
    {
        return array_keys($this->grouped);
    }

    /**
     * Gets the type of the group
     *      Many thanks: https://stackoverflow.com/a/3771228
     * @return string
     */
    public function getGroupName()
    {
        // doesn't apply to top level
        if ($this->doGroup) {
            return "";
        }
        return array_values($this->genericAssignments)[0]->type;
    }

    /**
     * Gets the weight of the group
     *      Many thanks: https://stackoverflow.com/a/3771228
     * @return float
     */
    public function getGroupWeight()
    {
        // doesn't apply to top level
        if ($this->doGroup) {
            return 0;
        }
        return array_values($this->genericAssignments)[0]->weight;
    }
}