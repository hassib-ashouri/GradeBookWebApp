<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller for Manuel to play with code,
 *      No one else should upload here
 * Class Manuel_controller
 */
class Manuel_controller extends MY_Controller
{
    public function index()
    {
        redirect("Login_controller/loginView");
    }
}