<?php

class Password_model extends MY_Model
{
    /**
     * @var $professor bool
     */
    private $professor;

    // model is in charge of crud: create, read, update, delete
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Tests the password against the database
     *      many thanks to: https://stackoverflow.com/a/6337021
     * @param $user string
     * @param $password string
     * @return bool
     */
    public function verifyPassword($user, $password)
    {
        // to set password in database, use this
        // $newHashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // do something in database to retrieve hashed password of correct user
        // $hashed = "";
        // also note whether specified user is a professor of student
        // or just is a professor for simplicity's sake
        $this->professor = true;

        // return password_verify($password, $hashed);

        return true;
    }

    /**
     * Checks if the tested user is a professor
     * @return bool
     */
    public function isProfessor()
    {
        return $this->professor;
    }
}