<?php

/**
 * Database interaction for login;
 * Verifies that a user exists, and that a valid password was input;
 * Also used for setting password, etc.
 * Class Login_model
 */
class Login_model extends MY_Model
{
    /**
     * The user,
     *      created by reading the user_id and matching with a user in the database
     * @var User
     */
    private $user;

    // model is in charge of crud: create, read, update, delete
    public function __construct()
    {
        parent::__construct();

        require_once "helpers/User.php";
    }

    /**
     * Verifies that a user exists for the given userId
     * @param string $userId
     * @return bool
     */
    public function verifyUser($userId)
    {
        $query = $this->db
            ->select("*")
            ->where("professor_id", $userId)
            ->get("professors");
        if ($query->num_rows() == 0) {
            $query = $this->db
                ->select("*")
                ->where("student_id", $userId)
                ->get("students");
            if ($query->num_rows() == 0) {
                return false;
            }
        }
        $this->user = $query->row(0, "User");

        return true;
    }

    /**
     * Tests the password against the database;
     *      many thanks to: https://stackoverflow.com/a/6337021
     * @param string $password
     * @return bool
     */
    public function verifyPassword($password)
    {
        $hashed = $this->user->password_hash;
        return password_verify($password, $hashed);
    }

    /**
     * Sets the password for the user
     * @param string $password
     */
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

    /**
     * Checks if the user has a password_hash set
     * @return bool
     */
    public function hasPassword()
    {
        return strlen($this->user->password_hash) > 0;
    }

    /**
     * Getter for user
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}