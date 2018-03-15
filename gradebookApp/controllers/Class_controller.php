<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Class_controller extends MY_Controller
{
    public function index()
    {
        redirect("Login_controller/loginView");
    }
}