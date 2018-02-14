<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IndexController extends MY_Controller
{
    public function index()
    {
        redirect("LoginController/loginView");
    }
}