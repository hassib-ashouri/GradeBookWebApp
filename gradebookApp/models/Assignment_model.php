<?php namespace Models;

/**
 * Database interaction for assignments;
 * Class Assignment_model
 */
class Assignment_model extends \MY_Model
{
    /**
     * Array of assignments
     *      read in from post
     * @var \Objects\Assignment[]
     */
    private $assignments = array();
    /**
     * Name of the database table
     * @var string
     */
    private $tableName;

    // model is in charge of crud: create, read, update, delete
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Creates an assignment,
     *      also initializes grades for students in a class
     * @param \Objects\Assignment $assignment
     * @param \Objects\ClassObj $classObj
     */
    public function createAssignment($assignment, $classObj)
    {
        /**
         * Prepares the assignment for insertion into the db
         */
        $temp = array();
        foreach ($assignment as $propertyName => $value) {
            if (isset($value)) {
                switch ($propertyName) {
                    case "assignment_id":
                        $temp["id"] = $value;
                        break;
                    case "max_points_old":
                        // not a column in the db
                        break;
                    default:
                        $temp[$propertyName] = $value;
                        break;
                }
            }
        }

        /**
         * Inserts the assignment into the db and saves the assigned id
         */
        $this->db->insert("assignments", $temp);
        $assignmentArr = $this->db
            ->from("assignments")
            ->where($temp)
            ->limit(1)
            ->order_by("id", "DESC")
            ->get()->row_array();
        $assignment->assignment_id = $assignmentArr["id"];

        /**
         * Initializes the assignment for each student in the class
         */
        $tableBatch = array();
        $students = $classObj->getStudents();
        foreach ($students as $student) {
            array_push($tableBatch, array(
                "student_id" => $student->student_id,
                "assignment_id" => $assignment->assignment_id,
                "points" => 0,
            ));
        }
        if (count($tableBatch) > 0) {
            $this->db->insert_batch($classObj->table_name, $tableBatch);
        }
    }

    /**
     * Creates all specified assignments for a class
     * @param \Objects\ClassObj $classObj
     */
    public function createAssignments($classObj)
    {
        $assignments = $classObj->getAssignments();
        foreach ($assignments as $assignment) {
            $this->createAssignment($assignment, $classObj);
        }
    }

    /**
     * Deletes an assignment
     *      from both class_table and assignments
     * @param \Objects\Assignment $assignment
     * @param \Objects\ClassObj $classObj
     */
    public function deleteAssignment($assignment, $classObj)
    {
        $this->db->delete("assignments", array("id" => $assignment->assignment_id));
        $this->db->delete($classObj->table_name, array("id" => $assignment->assignment_id));
    }

    /**
     * Deletes all specified assignments for a class
     * @param \Objects\ClassObj $classObj
     */
    public function deleteAssignments($classObj)
    {
        $assignments = $classObj->getAssignments();
        foreach ($assignments as $assignment) {
            $this->deleteAssignment($assignment, $classObj);
        }
    }

    /**
     * Creates $assignments from $assignmentResult;
     * Creates $assignmentResult from $classTable and "assignments"
     * @param string $classTableName
     * @return \Objects\Assignment[]
     */
    public function getAssignments($classTableName)
    {
        $assignmentResult = $this->db
            ->select("student_id, assignment_id, assignment_name, description, type, weight, points, max_points, graded")
            ->from($classTableName)
            ->join("assignments", "assignment_id = assignments.id")
            ->get()->result_array();

        $assignments = array();
        foreach ($assignmentResult as $assignment) {
            $assignId = $assignment["assignment_id"];
            $studentId = $assignment["student_id"];
            $points = $assignment["points"];

            if (!isset($assignments[$assignId])) {
                $assignments[$assignId] = new \Objects\Assignment();
                foreach ($assignment as $key => $value) {
                    $assignments[$assignId]->$key = $value;
                }
            }
            $assignments[$assignId]->setPoints($studentId, $points);
        }

        return $assignments;
    }

    /**
     * Reads in assignments from post
     *      to use in other methods;
     * Only assigns what is available from post;
     * Requires that the assignType match some groupName.
     * Saves tableName as well
     * @param $post
     */
    public function readPost($post)
    {
        $assignCount = count($post["assignId"]);
        $groupCount = count($post["groupName"]);
        for ($i = 0; $i < $assignCount; $i++) {
            $this->assignments[$i] = new \Objects\Assignment();
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
            if (isset($post["assignMaxPtsOld"])) {
                $this->assignments[$i]->max_points_old = $post["assignMaxPtsOld"][$i];
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

        if (isset($post["tableName"])) {
            $this->tableName = $post["tableName"];
        }
    }

    /**
     * Updates the assignments in the db
     *      with assignments created with readPost
     */
    public function updateAssignments()
    {
        $this->_updateClassAssignments();
        $this->_updateBatchAssignments();
    }

    /**
     * Updates the batch of assignment meta data
     */
    private function _updateBatchAssignments()
    {
        $batchAssignments = array();

        foreach ($this->assignments as $assignment) {
            $temp = array();
            foreach ($assignment as $propertyName => $value) {
                if (isset($value)) {
                    switch ($propertyName) {
                        case "assignment_id":
                            $temp["id"] = $value;
                            break;
                        case "max_points_old":
                            // not a column in the db
                            break;
                        default:
                            $temp[$propertyName] = $value;
                            break;
                    }
                }
            }
            array_push($batchAssignments, $temp);
        }

        if (count($batchAssignments) > 0) {
            $this->db->update_batch("assignments", $batchAssignments, "id");
        }
    }

    /**
     * Updates the batch of assignments from a class
     */
    private function _updateClassAssignments()
    {
        $classAssignments = array();

        if (isset($this->tableName)) {
            $ratios = array();
            foreach ($this->assignments as $assignment) {
                $ratio = +$assignment->max_points / +$assignment->max_points_old;
                $ratios[$assignment->assignment_id] = $ratio;
            }

            $assignments = $this->db
                ->select("id, assignment_id, student_id, points")
                ->from($this->tableName)
                ->get()->result_array();

            foreach ($assignments as $assignment) {
                $tempPoints = +$assignment["points"] * $ratios[$assignment["assignment_id"]];
                $temp = array(
                    "id" => $assignment["id"],
                    "points" => $tempPoints,
                );
                array_push($classAssignments, $temp);
            }
        }

        if (count($classAssignments) > 0) {
            $this->db->update_batch($this->tableName, $classAssignments, "id");
        }
    }
}