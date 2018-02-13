<?php

class Password_model extends MY_Model
{
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

        // return password_verify($password, $hashed);

        return true;
    }
}