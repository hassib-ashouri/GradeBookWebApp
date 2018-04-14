<?php namespace Objects;

/**
 * Represents a grouping of assignments
 * Class AssignmentList
 * @package Objects
 */
class AssignmentList implements \JsonSerializable
{
    /**
     * Whether or not to group assignments by category
     * @var bool
     */
    public $doGroup = true;
    /**
     * Array of all assignments in the list
     * @var Assignment[]
     */
    private $assignments = array();
    /**
     * Array of assignment lists
     *      where keys are names of groups
     * @var AssignmentList[]
     */
    private $grouped = array();

    /**
     * AssignmentList constructor.
     */
    public function __construct()
    {
    }

    /**
     * Adds a new assignment to the list
     * @param Assignment $assignment
     */
    public function addAssignment($assignment)
    {
        $id = $assignment->assignment_id;
        $group = $assignment->type;

        if (is_null($id)) {
            array_push($this->assignments, $assignment);
        } else {
            $this->assignments[$id] = $assignment;
        }
        // only applies if top level
        if ($this->doGroup) {
            if (!isset($this->grouped[$group])) {
                $this->grouped[$group] = new AssignmentList();
                $this->grouped[$group]->doGroup = false;
            }
            $this->grouped[$group]->addAssignment($assignment);
        }
    }

    /**
     * Gets the array of Assignments
     * @return Assignment[]
     */
    public function getAssignments()
    {
        return $this->assignments;
    }

    /**
     * Gets the array of Assignments
     *      where the keys are the groups of the assignments
     * @return AssignmentList[]
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
     *      doesn't apply to top level
     *      Many thanks: https://stackoverflow.com/a/3771228
     * @return string
     */
    public function getGroupName()
    {
        if ($this->doGroup) {
            return "";
        }
        return array_values($this->assignments)[0]->type;
    }

    /**
     * Gets the weight of the group
     *      doesn't apply to top level
     *      Many thanks: https://stackoverflow.com/a/3771228
     * @return float
     */
    public function getGroupWeight()
    {
        if ($this->doGroup) {
            return 0;
        }
        return array_values($this->assignments)[0]->weight;
    }

    /**
     * Specify data which should be serialized to JSON
     * @return mixed data which can be serialized by json_encode,
     *      which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }
}