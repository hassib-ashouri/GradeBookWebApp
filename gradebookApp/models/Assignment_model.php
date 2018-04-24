<?php namespace Models;

/**
 * Database interaction for assignments;
 * Class Assignment_model
 */
class Assignment_model extends \MY_Model
{
    private $NEW_ASSIGNMENT_ID = -1;

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
        $this->_insertAssignment($assignment);
        $this->_initAssignment($assignment, $classObj);
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
        $this->db->delete($classObj->table_name, array("assignment_id" => $assignment->assignment_id));
    }

    /**
     * Deletes all specified assignments for a class
     * @param \Objects\ClassObj $classObj
     */
    public function deleteAssignments($classObj)
    {
        $assignments = $classObj->getAssignments();

        /**
         * Deletes from assignments
         */
        foreach ($assignments as $assignment) {
            $this->db->or_where(array("id" => $assignment->assignment_id));
        }
        if (count($assignments) > 0) {
            $this->db->delete("assignments");
        }

        /**
         * Deletes from class table
         */
        foreach ($assignments as $assignment) {
            $this->db->or_where(array("assignment_id" => $assignment->assignment_id));
        }
        if (count($assignments) > 0) {
            $this->db->delete($classObj->table_name);
        }
    }

    /**
     * Creates $assignments from $assignmentResult;
     * Creates $assignmentResult from $classTable and "assignments"
     * @param \Objects\ClassObj $classObj
     * @return \Objects\Assignment[]
     */
    public function getAssignments($classObj)
    {
        $assignmentResult = $this->db
            ->select("student_id, assignments.id as assignment_id, class_id, assignment_name, description, type, weight, points, max_points, graded")
            ->where("class_id", $classObj->class_id)
            ->from("assignments")
            ->join($classObj->table_name, "assignments.id = assignment_id", "left")
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
     * Sets the assignment as new
     * @param \Objects\Assignment $assignment
     */
    public function markAsNew($assignment)
    {
        $assignment->assignment_id = $this->NEW_ASSIGNMENT_ID;
    }

    /**
     * Updates the assignments in the db
     * @param \Objects\Assignment[] $assignments
     * @param string $tableName
     */
    public function updateAssignments($assignments, $tableName)
    {
        $this->_updateClassAssignments($assignments, $tableName);
        $this->_updateBatchAssignments($assignments);
    }

    /**
     * Initializes the assignment for each student in the class
     * @param \Objects\Assignment $assignment
     * @param \Objects\ClassObj $classObj
     */
    private function _initAssignment($assignment, $classObj)
    {
        $tableBatch = array();
        $students = $classObj->getStudents();

        /**
         * Determines which students have the assignment already
         */
        $blacklist = $this->db
            ->select("student_id")
            ->from($classObj->table_name)
            ->where("assignment_id", $assignment->assignment_id)
            ->get()->result("\Objects\Student");

        /**
         * Only initializes assignment for students that need it
         */
        foreach ($students as $student) {
            $blacklisted = false;
            foreach ($blacklist as $bStudent) {
                if ($bStudent->student_id == $student->student_id) {
                    $blacklisted = true;
                    break;
                }
            }

            if (!$blacklisted) {
                array_push($tableBatch, array(
                    "student_id" => $student->student_id,
                    "assignment_id" => $assignment->assignment_id,
                    "points" => 0,
                ));
            }
        }

        if (count($tableBatch) > 0) {
            $this->db->insert_batch($classObj->table_name, $tableBatch);
        }
    }

    /**
     * Inserts an assignment into the database,
     *      and saves the newly assigned id
     * @param \Objects\Assignment $assignment
     */
    private function _insertAssignment($assignment)
    {
        /**
         * Checks if assignment already exists
         */
        $exists = false;
        if (isset($assignment->assignment_id)) {
            $assignmentCount = $this->db
                ->from("assignments")
                ->where("id", $assignment->assignment_id)
                ->count_all_results();
            $exists = $assignmentCount > 0;
        }

        if (!$exists) {
            /**
             * Prepares the assignment for insertion into the db
             */
            $temp = array();
            foreach ($assignment as $propertyName => $value) {
                if (isset($value)) {
                    switch ($propertyName) {
                        case "assignment_id":
                            // shouldn't know id of a new assignment
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
             * Inserts the assignment into the db
             */
            $this->db->insert("assignments", $temp);

            /**
             * And saves the assigned id
             */
            $assignmentArr = $this->db
                ->from("assignments")
                ->where($temp)
                ->limit(1)
                ->order_by("id", "DESC")
                ->get()->row_array();
            $assignment->assignment_id = $assignmentArr["id"];
        }
    }

    /**
     * Updates the batch of assignment meta data
     * @param \Objects\Assignment[] $assignments
     */
    private function _updateBatchAssignments($assignments)
    {
        $batchAssignments = array();

        foreach ($assignments as $assignment) {
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
     *      for manipulating max points only
     * @param \Objects\Assignment[] $assignments
     * @param string $tableName
     */
    private function _updateClassAssignments($assignments, $tableName)
    {
        $classAssignments = array();

        if (isset($tableName)) {
            $ratios = array();
            foreach ($assignments as $assignment) {
                $ratio = +$assignment->max_points / +$assignment->max_points_old;
                $ratios[$assignment->assignment_id] = $ratio;
            }

            $assignmentsData = $this->db
                ->select("id, assignment_id, student_id, points")
                ->from($tableName)
                ->get()->result_array();

            foreach ($assignmentsData as $assignmentData) {
                $tempPoints = +$assignmentData["points"] *
                    $ratios[$assignmentData["assignment_id"]];
                $temp = array(
                    "id" => $assignmentData["id"],
                    "points" => $tempPoints,
                );
                array_push($classAssignments, $temp);
            }
        }

        if (count($classAssignments) > 0) {
            $this->db->update_batch($tableName, $classAssignments, "id");
        }
    }

    // todo remove assignments from assignments table based on what ids are not present in the array that are present in the database
}