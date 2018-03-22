<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller for Mitchell to play with code,
 *      No one else should upload here,
 *      but feel free to use these methods to help you understand
 * Class Mitchell_controller
 */
class Mitchell_controller extends MY_Controller
{
    public function index()
    {
        redirect("Login_controller/loginView");
    }
}