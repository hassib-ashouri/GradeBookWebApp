<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends MY_Controller
{
    public function loginView($userName = "", $userId = "")
    {
        $this->_loginView("", $userName, $userId);
    }

    public function loginErrorView($errorMessage, $userName = "", $userId = "")
    {
        $this->_loginView($errorMessage, $userName, $userId);
    }

    private function _loginView($errorMessage, $userName, $userId)
    {
        $login = array(
            "errorMessage" => urldecode($errorMessage),
            "userName" => urldecode($userName),
            "userId" => urldecode($userId),
            "formAction" => base_url() . "LoginController/loginUser",
            "buttonText" => "Next",
        );

        if (strlen($userId) > 0) {
            $login["formAction"] = base_url() . "LoginController/loginPassword";
            $login["buttonText"] = "Log In";
        }

        $header["title"] = "Login - GradeBook";

        $data["header"] = $this->load->view("header", $header, true);
        $data["mainContent"] = $this->load->view("login", $login, true);
        $this->load->view("main", $data);
    }

    public function createPasswordView($userName = "", $userId = "")
    {
        $this->_createPasswordView("", $userName, $userId);
    }

    public function createPasswordErrorView($errorMessage, $userName = "", $userId = "")
    {
        $this->_createPasswordView($errorMessage, $userName, $userId);
    }

    private function _createPasswordView($errorMessage, $userName, $userId)
    {
        $login = array(
            "errorMessage" => urldecode($errorMessage),
            "userName" => urldecode($userName),
            "userId" => urldecode($userId),
            "formAction" => base_url() . "LoginController/createPassword",
            "buttonText" => "Next",
        );

        $header["title"] = "Create Password - GradeBook";

        $data["header"] = $this->load->view("header", $header, true);
        $data["mainContent"] = $this->load->view("createPassword", $login, true);
        $this->load->view("main", $data);
    }

    public function loginUser()
    {
        $post = $this->input->post();
        $userIsValid = false;

        if (isset($post["username"])) {
            $user = $post["username"];
            $this->load->model("password_model");
            $userIsValid = $this->password_model->verifyUser($user);
        }
        if (!$userIsValid) {
            $view = "loginErrorView";
            $errorMessage = "No Such ID Exists";
            redirect(sprintf("LoginController/%s/%s",
                $view, $errorMessage));
        } else {
            $view = ($this->password_model->hasPassword()) ? "loginView" : "createPasswordView";
            $userName = $this->password_model->getUserName();
            $userId = $this->password_model->getUserId();
            redirect(sprintf("LoginController/%s/%s/%s",
                $view, $userName, $userId));
        }
    }

    public function loginPassword()
    {
        $post = $this->input->post();
        $passwordIsValid = false;

        if (isset($post["username"]) && isset($post["password"])) {
            $user = $post["username"];
            $password = $post["password"];
            $this->load->model("password_model");
            $this->password_model->verifyUser($user);
            $passwordIsValid = $this->password_model->verifyPassword($password);
        }

        if (!$passwordIsValid) {
            $view = "loginErrorView";
            $errorMessage = "Incorrect Password";
            $userName = $this->password_model->getUserName();
            $userId = $this->password_model->getUserId();
            redirect(sprintf("LoginController/%s/%s/%s/%s",
                $view, $errorMessage, $userName, $userId));
        } else {
            if ($this->password_model->isProfessor()) {
                // load professor view
            } else {
                // load student view
            }
        }
    }

    public function createPassword()
    {
        $post = $this->input->post();

        if (isset($post["username"]) && isset($post["password"]) && isset($post["passwordConfirm"])) {
            $user = $post["username"];
            $password = $post["password"];
            $passwordConfirm = $post["passwordConfirm"];

            if ($password == $passwordConfirm) {
                $this->load->model("password_model");
                $this->password_model->verifyUser($user);
                $this->password_model->setPassword($password);
            } else {
                $view = "createPasswordErrorView";
                $errorMessage = "Passwords Don't Match";
                $userName = $this->password_model->getUserName();
                $userId = $this->password_model->getUserId();
                redirect(sprintf("LoginController/%s/%s/%s/%s",
                    $view, $errorMessage, $userName, $userId));
            }
        }
    }
}