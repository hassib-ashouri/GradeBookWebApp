<?php

class Login_model extends MY_Model
{
    /**
     * @var $user User
     */
    private $user;

    // model is in charge of crud: create, read, update, delete
    public function __construct()
    {
        parent::__construct();

        require_once "helpers/User.php";
    }

    public function verifyUser($user)
    {
        $query = $this->db
            ->select("*")
            ->where("professor_id", $user)
            ->get("professors");
        if ($query->num_rows() == 0) {
            $query = $this->db
                ->select("*")
                ->where("student_id", $user)
                ->get("students");
            if ($query->num_rows() == 0) {
                return false;
            }
        }
        $this->user = $query->row(0, "User");

        return true;
    }

    /**
     * Tests the password against the database
     *      many thanks to: https://stackoverflow.com/a/6337021
     * @param $password string
     * @return bool
     */
    public function verifyPassword($password)
    {
        $hashed = $this->user->password_hash;
        return password_verify($password, $hashed);
    }

    public function setPassword($password)
    {
        $data = array(
            "password_hash" => password_hash($password, PASSWORD_DEFAULT),
        );
        if ($this->user->type === "professor") {
            $this->db
                ->where("professor_id", $this->user->user_id)
                ->update("professors", $data);
        } else {
            $this->db
                ->where("student_id", $this->user->user_id)
                ->update("students", $data);
        }
    }

    /**
     * Sets the user for debug purposes
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Checks if the tested user is a professor
     *      must be run after verifyPassword for accurate results
     * @return bool
     */
    public function isProfessor()
    {
        return $this->user->type === "professor";
    }

    public function hasPassword()
    {
        return strlen($this->user->password_hash) > 0;
    }

    public function getUserId()
    {
        return $this->user->user_id;
    }

    public function getUserName()
    {
        return $this->user->name_first . " " . $this->user->name_last;
    }
}