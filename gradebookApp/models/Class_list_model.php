<?php

/**
 * Database interaction for class lists;
 * In charge of "meta data" interactions,
 *      such as creating and deleting whole classes,
 *      as well as just listing out basic info.
 * Class Class_list_model
 */
class Class_list_model extends MY_Model
{
    // model is in charge of crud: create, read, update, delete
    public function __construct()
    {
        parent::__construct();

        require_once "helpers/ClassObj.php";
    }

    /**
     * Gets the array of classes for a professor
     * @param string $userId
     * @return ClassObj[]
     */
    public function readProfessorClassList($userId)
    {
        $query = $this->db
            ->select("*")
            ->where("professor_id", $userId)
            ->get("classes");
        $classes = $query->result("ClassObj");

        return $classes;
    }

    /**
     * Checks for a class in the db with specified table_name
     *      checks single row exists in classes
     *      checks table exists for class
     * @param ClassObj $classObj
     * @return bool[]
     */
    public function classData($classObj)
    {
        /**
         * Gets the row from classes for the class
         */
        $classRow = $this->db
            ->where("table_name", $classObj->table_name)
            ->get("classes")->result_array();
        /**
         * Gets the rows from students_enrolled for each student in the class
         */
        $studentsEnrolled = $this->db
            ->where("class_id", $classObj->class_id)
            ->count_all_results("students_enrolled");
        $rowExists = count($classRow) == 1;
        $tableExists = $this->db->table_exists($classObj->table_name);

        return array(
            "rowExists" => $rowExists,
            "tableExists" => $tableExists,
            "studentsEnrolled" => $studentsEnrolled,
        );
    }

    /**
     * Creates a class in the db with specified table_name
     *      inserts row into classes
     *      creates table for class
     * @param ClassObj $classObj
     */
    public function createClass($classObj)
    {
        $classData = $this->classData($classObj);

        if (!$classData["rowExists"]) {
            /**
             * Inserts row into classes containing info on class
             */
            $classesData = array(
                "class_id" => $classObj->class_id,
                "professor_id" => $classObj->professor_id,
                "class_name" => $classObj->class_name,
                "section" => $classObj->section,
                "class_title" => $classObj->class_title,
                "meeting_times" => $classObj->meeting_times,
                "table_name" => $classObj->table_name,
            );
            $this->db->insert("classes", $classesData);
        }

        if ($classData["studentsEnrolled"] == 0) {
            /**
             * Inserts rows into students_enrolled for each student in the class
             */
            $students = $classObj->getStudents();
            $studentsEnrolledData = array();
            foreach ($students as $student) {
                array_push($studentsEnrolledData, array(
                    "student_id" => $student->student_id,
                    "class_id" => $classObj->class_id,
                ));
            }
            if (count($studentsEnrolledData) > 0) {
                $this->db->insert_batch("students_enrolled", $studentsEnrolledData);
            }
        }

        if (!$classData["tableExists"]) {
            /**
             * Creates table for class in db
             */
            $fields = array(
                "id" => array("type" => "int", "unsigned" => true, "auto_increment" => true),
                "student_id" => array("type" => "char", "constraint" => 9),
                "assignment_id" => array("type" => "int"),
                "points" => array("type" => "float"),
            );
            $this->load->dbforge();
            $this->dbforge
                ->add_field($fields)
                ->add_key("id", true)
                ->create_table($classObj->table_name);
        }
    }

    /**
     * Deletes a class in the db with specified table_name
     *      deletes row from classes
     *      drops table for class
     * @param ClassObj $classObj
     */
    public function deleteClass($classObj)
    {
        /**
         * Deletes row from classes for class
         */
        $this->db
            ->where("table_name", $classObj->table_name)
            ->delete("classes");

        /**
         * Deletes all rows from students_enrolled that match:
         *      class_id and section
         */
        $this->db
            ->where("class_id", $classObj->class_id)
            ->delete("students_enrolled");

        /**
         * Drops table for class from db
         */
        $this->load->dbforge();
        $this->dbforge->drop_table($classObj->table_name, true);
    }
}