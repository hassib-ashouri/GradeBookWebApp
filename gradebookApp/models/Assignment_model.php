<?php

/**
 * Class Assignment_model
 * Not used for reading assignments
 *      only create, update, and delete
 */
class Assignment_model extends MY_Model
{
    /**
     * @var Assignment[]
     */
    private $assignments = array();

    // model is in charge of crud: create, read, update, delete
    public function __construct()
    {
        parent::__construct();

        require_once "Assignment.php";
    }

    /**
     * Reads in assignments from post
     *      to use in other methods
     * Only assigns what is available from post
     * Requires that the assignType match some groupName
     * @param $post
     */
    public function readPost($post)
    {
        $assignCount = count($post["assignId"]);
        $groupCount = count($post["groupName"]);
        for ($i = 0; $i < $assignCount; $i++) {
            $this->assignments[$i] = new Assignment();
            if (isset($post["assignId"])) {
                $this->assignments[$i]->assignment_id = $post["assignId"][$i];
            }
            if (isset($post["assignName"])) {
                $this->assignments[$i]->assignment_name = $post["assignName"][$i];
            }
            if (isset($post["assignDesc"])) {
                $this->assignments[$i]->description = $post["assignDesc"][$i];
            }
            if (isset($post["assignType"])) {
                $this->assignments[$i]->type = $post["assignType"][$i];
            }
            if (isset($post["assignMaxPts"])) {
                $this->assignments[$i]->max_points = $post["assignMaxPts"][$i];
            }
            if (isset($post["assignGraded"])) {
                $this->assignments[$i]->graded = $post["assignGraded"][$i];
            }

            if (isset($post["groupName"]) && isset($post["groupWeight"])) {
                for ($j = 0; $j < $groupCount; $j++) {
                    if ($post["assignType"][$i] == $post["groupName"][$j]) {
                        $this->assignments[$i]->weight = $post["groupWeight"][$j];
                    }
                }
            }
        }
    }

    /**
     * Updates the assignments in the db
     *      with assignments created with readPost
     */
    public function updateAssignments()
    {
        $batch = array();

        foreach ($this->assignments as $assignment) {
            $temp = array();
            foreach ($assignment as $propertyName => $value) {
                if (isset($value)) {
                    switch ($propertyName) {
                        case "assignment_id":
                            $temp["id"] = $value;
                            break;
                        default:
                            $temp[$propertyName] = $value;
                            break;
                    }
                }
            }
            array_push($batch, $temp);
        }

        // todo account for changing max_points

        if (count($batch) > 0) {
            $this->db->update_batch("assignments", $batch, "id");
        }
    }
}