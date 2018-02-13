<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IndexController extends MY_Controller
{
    public function index()
    {
        echo "Hello world!";
    }

    public function loginView($errorMessage = "")
    {
        echo "<div>display login screen</div>";
        echo "<div>and allow for some sort of error message</div>";
        echo "<div>" . urldecode($errorMessage) . "</div>";
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
