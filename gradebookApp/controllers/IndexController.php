<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IndexController extends MY_Controller
{
    public function index()
    {
        redirect("IndexController/loginView");
    }

    public function loginView($errorMessage = "")
    {
        $login = array("errorMessage" => urldecode($errorMessage));

        $header["title"] = "Login - GradeBook";

        $data["header"] = $this->load->view("header", $header, true);
        $data["mainContent"] = $this->load->view("login", $login, true);
        $this->load->view("main", $data);
    }

    public function login()
    {
        $post = $this->input->post();
        $passwordIsValid = false;

        if (isset($post["username"]) && isset($post["password"])) {
            $user = $post["username"];
            $password = $post["password"];
            $this->load->model("password_model");
            $passwordIsValid = $this->password_model->verifyPassword($user, $password);
        }

        if (!$passwordIsValid) {
            redirect("IndexController/loginView/Incorrect Email or Password");
        } else {
            // load proper view
        }
    }
}
