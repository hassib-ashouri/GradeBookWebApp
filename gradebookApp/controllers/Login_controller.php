<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_controller extends MY_Controller
{
    /**
     * View methods
     */
    public function createPasswordView($userName, $userId)
    {
        $errorMessage = $this->session->flashdata("errorMessage");

        $login = array(
            "errorMessage" => urldecode($errorMessage),
            "userName" => urldecode($userName),
            "userId" => urldecode($userId),
            "formAction" => base_url() . "Login_controller/createPassword",
            "buttonText" => "Next",
        );

        $header["title"] = "Create Password - GradeBook";

        $data["header"] = $this->load->view("header", $header, true);
        $data["mainContent"] = $this->load->view("createPassword", $login, true);
        $this->load->view("main", $data);
    }

    public function loginView($userName = "", $userId = "")
    {
        $errorMessage = $this->session->flashdata("errorMessage");

        $login = array(
            "errorMessage" => urldecode($errorMessage),
            "userName" => urldecode($userName),
            "userId" => urldecode($userId),
            "formAction" => base_url() . "Login_controller/loginUser",
            "buttonText" => "Next",
        );

        if (strlen($userId) > 0) {
            $login["formAction"] = base_url() . "Login_controller/loginPassword";
            $login["buttonText"] = "Log In";
        }

        $header["title"] = "Login - GradeBook";

        $data["header"] = $this->load->view("header", $header, true);
        $data["mainContent"] = $this->load->view("login", $login, true);
        $this->load->view("main", $data);
    }

    /**
     * Action methods
     */
    public function createPassword()
    {
        $post = $this->input->post();

        if (isset($post["username"]) && isset($post["password"]) && isset($post["passwordConfirm"])) {
            $user = $post["username"];
            $password = $post["password"];
            $passwordConfirm = $post["passwordConfirm"];

            if ($password == $passwordConfirm) {
                $this->load->model("login_model");
                $this->login_model->verifyUser($user);
                $this->login_model->setPassword($password);
            } else {
                $this->session->set_flashdata("errorMessage", "Passwords Don't Match");
                $user = $this->login_model->getUser();
                $userName = $user->name_first . " " . $user->name_last;
                $userId = $user->user_id;
                redirect(sprintf("Login_controller/createPasswordView/%s/%s", $userName, $userId));
            }
        }
    }

    public function loginPassword()
    {
        $post = $this->input->post();
        $passwordIsValid = false;

        if (isset($post["username"]) && isset($post["password"])) {
            $user = $post["username"];
            $password = $post["password"];
            $this->load->model("login_model");
            $this->login_model->verifyUser($user);
            $passwordIsValid = $this->login_model->verifyPassword($password);
        }

        if (!$passwordIsValid) {
            $this->session->set_flashdata("errorMessage", "Incorrect Password");
            $user = $this->login_model->getUser();
            $userName = $user->name_first . " " . $user->name_last;
            $userId = $user->user_id;
            redirect(sprintf("Login_controller/loginView/%s/%s", $userName, $userId));
        } else {
            $this->session->set_userdata("userId", $user);
            if ($this->login_model->isProfessor()) {
                // load professor view
            } else {
                // load student view
            }
        }
    }

    public function loginUser()
    {
        $post = $this->input->post();
        $userIsValid = false;

        if (isset($post["username"])) {
            $user = $post["username"];
            $this->load->model("login_model");
            $userIsValid = $this->login_model->verifyUser($user);
        }
        if (!$userIsValid) {
            $this->session->set_flashdata("errorMessage", "No Such ID Exists");
            redirect("Login_controller/loginView");
        } else {
            $view = ($this->login_model->hasPassword()) ? "loginView" : "createPasswordView";
            $user = $this->login_model->getUser();
            $userName = $user->name_first . " " . $user->name_last;
            $userId = $user->user_id;
            redirect(sprintf("Login_controller/%s/%s/%s",
                $view, $userName, $userId));
        }
    }

    /**
     * Private methods
     */
}