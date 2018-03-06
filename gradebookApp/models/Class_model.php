<?php

class Class_model extends MY_Model
{
    /**
     * @var ClassObj
     */
    private $classObj;

    // model is in charge of crud: create, read, update, delete
    public function __construct()
    {
        parent::__construct();

        require_once "Assignment.php";
        require_once "Student.php";
        require_once "ClassObj.php";
    }

    /**
     * Loads a table and creates a classObj out of it
     * @param string $classTable tableName
     */
    public function loadTable($classTable)
    {
        $matches = array();
        preg_match("/class_(\d{5})_.*?_(\d{2})_table/", $classTable, $matches);
        $classId = $matches[1];
        $section = $matches[2];

        /**
         * Creates $students from "students" and "students_enrolled"
         *      matches against $classId and $section
         * @var Student[] $students
         */
        $students = $this->db
            ->select("students.student_id, name_first, name_last")
            ->from("students_enrolled")
            ->where(array(
                "class_id" => $classId,
                "section" => $section,
                "enrolled" => 1,
            ))
            ->join("students", "students_enrolled.student_id = students.student_id")
            ->get()->result("Student");
        /**
         * Creates $assignments from $classTable and "assignments"
         * @var Assignment[] $assignments
         */
        $assignments = $this->db
            ->select("student_id, assignment_id, assignment_name, description, type, weight, points, max_points, graded")
            ->from($classTable)
            ->join("assignments", "assignment_id = assignments.id")
            ->get()->result("Assignment");

        foreach ($students as $student) {
            foreach ($assignments as $assignment) {
                $student->addAssignment($assignment);
            }
        }

        $this->classObj = new ClassObj($assignments, $students);
    }

    /**
     * @return ClassObj
     */
    public function getClass()
    {
        return $this->classObj;
    }
}