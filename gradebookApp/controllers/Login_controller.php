<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * The controller responsible for handling the login logic.
 * Class Login_controller
 */
class Login_controller extends MY_Controller
{
    /**
     * when no method is specidied, direct to the login view.
     */
    public function index()
    {
        redirect("Login_controller/existingUserView");
    }

    /**
     * View methods
     */

    /**
     * This method loads the view responsible for creating a password
     * for a new user.
     * @param string $userName the name of the user.
     * @param string $userId the id number of the user.
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
            "existing_user_view" => base_url() . "Login_controller/existingUserView",
            "new_user_view" => base_url() . "Login_controller/newUserView",

        );

        $header["title"] = "Create Password - GradeBook";

        $data["header"] = $this->load->view("header", $header, true);
        $data["mainContent"] = $this->load->view("login/create_password_comp", $login, true);
        $this->load->view("main", $data);
    }

    /**
     * @param string $userName the name of the user
     * @param string $userId the id number of the user.
     */
    public function newUserView($userName = "", $userId = "")
    {
        $errorMessage = $this->session->flashdata("errorMessage");

        $login = array(
            "errorMessage" => urldecode($errorMessage),
            "userName" => urldecode($userName),
            "userId" => urldecode($userId),
            "formAction" => base_url() . "Login_controller/loginUser",
            "buttonText" => "Log In",
            "existing_user_view" => base_url() . "Login_controller/existingUserView"
        );

        if (strlen($userId) > 0) {
            $login["formAction"] = base_url() . "Login_controller/loginPassword";
            $login["buttonText"] = "Log In";
        }

        $header["title"] = "New User Login - GradeBook";

        $data["header"] = $this->load->view("header", $header, true);
        $data["mainContent"] = $this->load->view("login/new_user_login_comp", $login, true);
        $this->load->view("main", $data);
    }

    public function existingUserView()
    {
        $errorMessage = $this->session->flashdata("errorMessage");

        $main = array(
            "errorMessage" => urldecode($errorMessage),
            "formAction" => base_url() . "Login_controller/loginPassword",
            "new_user_view" => base_url() . "Login_controller/newUserView"
        );

        //add components to the page
        $view_components["header"] = $this->load->view("header", array("title" => "Existing User - IDGF"), true);
        $view_components["mainContent"] = $this->load->view("login/existing_user_login_comp", $main, true);
        //view the components combined
        $this->load->view("main", $view_components);
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
            //load the login model to be used to verify the existence of a user.
            $this->load->model("login_model");
            $this->login_model->verifyUser($user);
            //if the passwords match.
            if ($password == $passwordConfirm) {
                $this->login_model->setPassword($password);
                redirect("Login_controller/existingUserView");
            } else {//if they dont match
                $this->session->set_flashdata("errorMessage", "Passwords Don't Match");
                $user = $this->login_model->getUser();
                $userName = $user->name_first . " " . $user->name_last;
                $userId = $user->user_id;
                redirect(sprintf("Login_controller/createPasswordView/%s/%s", $userName, $userId));
            }
        }
    }

    /**
     * It logs the user in if the user exists and the password in correct.
     * it also passes the id of the logged user to the session data through the variabel "loggedUser".
     */
    public function loginPassword()
    {
        $post = $this->input->post();
        $isUser = false;
        $hasPassword = false;
        $validPassword = false;

        //checking is also done in the front end
        if (isset($post["username"]) && isset($post["password"])) {
            $username = $post["username"];
            $password = $post["password"];
            $this->load->model("login_model");
            // what if this method returned a false and the user does not exist
            $isUser = $this->login_model->verifyUser($username);
            $hasPassword = $this->login_model->hasPassword();
            $validPassword = $this->login_model->verifyPassword($password);
        }

        if ($isUser && !$hasPassword) {
            $user = $this->login_model->getUser();
            $userName = $user->name_first . " " . $user->name_last;
            $userId = $user->user_id;
            redirect("Login_controller/newUserView");
        } else if (!$isUser || !$validPassword) {
            $this->session->set_flashdata("errorMessage", "Incorrect User ID or Password");
            redirect('Login_controller/existingUserView');
        } else {
            $user = $this->login_model->getUser();
            $this->session->set_userdata("userId", $user->user_id);
            $this->session->set_userdata("userName", $user->name_first . " " . $user->name_last);
            if ($this->login_model->isProfessor()) {
                // load professor view
                //since the user is logged in add the id in the session data
                $this->session->set_userdata("loggedUser", $this->login_model->getUser()->user_id);
                redirect("Class_list_controller/classListView");
            } else {
                // load student view
            }
        }
    }

    public function loginUser()
    {
        $post = $this->input->post();
        $userIsValid = false;

        //verify that the user exists
        if (isset($post["username"])) {
            $user = $post["username"];
            $this->load->model("login_model");
            $userIsValid = $this->login_model->verifyUser($user);
        }

        if (!$userIsValid) {
            $this->session->set_flashdata("errorMessage", "No Such ID Exists");
            redirect("Login_controller/newUserView");
        } else {
            $view = ($this->login_model->hasPassword()) ? "newUserView" : "createPasswordView";
            $user = $this->login_model->getUser();
            $userName = $user->name_first . " " . $user->name_last;
            $userId = $user->user_id;
            redirect(sprintf("Login_controller/%s/%s/%s",
                $view, $userName, $userId));
        }
    }

    /**
     * Logs the user out, removes session data, and redirects to login page
     */
    public function logout()
    {
        $this->session->unset_userdata(array(
            'loggedUser',
            'userId',
            'userName'
        ));

        redirect("Login_controller/existingUserView");
    }

    /**
     * Private methods
     */
}