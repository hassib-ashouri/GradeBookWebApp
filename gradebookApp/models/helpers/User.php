<?php

/**
 * Represents a user of the system
 *      either a professor or a student
 * Class User
 */
class User
{
    public $user_id;
    public $name_first;
    public $name_last;
    public $password_hash;
    public $type;

    /**
     * Maps professor_id and student_id to user_id,
     *      also saves user_type
     * __set is typically used to set values where an error would otherwise be thrown
     *      such as when accessing a private field, or a field that doesn't exist
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if ($name === "professor_id" || $name === "student_id") {
            $this->user_id = $value;

            if ($name === "professor_id") {
                $this->type = "professor";
            }
            if ($name === "student_id") {
                $this->type = "student";
            }
        }
    }
}