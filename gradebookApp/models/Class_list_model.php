<?php

class Class_list_model extends MY_Model
{
    // model is in charge of crud: create, read, update, delete
    public function __construct()
    {
        parent::__construct();

        require_once "ClassObj.php";
    }

    /**
     * @param $userId
     * @return array
     */
    public function readProfessorClassList($userId)
    {
        $query = $this->db
            ->select("*")
            ->where("professor_id", $userId)
            ->get("class_list");
        $classes = $query->result("ClassObj");

        return $classes;
    }
}