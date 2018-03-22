<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index_controller extends MY_Controller
{
    public function index()
    {
        redirect("Login_controller/loginView");
    }
}