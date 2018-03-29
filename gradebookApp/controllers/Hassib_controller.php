<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller for Hassib to play with code,
 *      No one else should upload here
 * Class Hassib_controller
 */
class Hassib_controller extends MY_Controller
{
    public function index()
    {
        redirect("Login_controller/newUserView");
    }
}