<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller for Arthur to play with code,
 *      No one else should upload here
 * Class Arthur_controller
 */
class Arthur_controller extends MY_Controller
{
    public function index()
    {
        redirect("Login_controller/newUserView");
    }
}