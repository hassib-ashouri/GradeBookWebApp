<?php

class User
{
    public $userId;
    public $name_first;
    public $name_last;
    public $password_hash;
    public $type;

    public function __set($name, $value)
    {
        if ($name === "professor_id" || $name === "student_id") {
            $this->userId = $value;

            if ($name === "professor_id") {
                $this->type = "professor";
            }
            if ($name === "student_id") {
                $this->type = "student";
            }
        }
    }
}