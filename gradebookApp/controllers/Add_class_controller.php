<?php
/**
 * Created by IntelliJ IDEA.
 * User: soulstaker
 * Date: 3/28/18
 * Time: 12:31 AM
 */

/**
 * The controller responsible for creating the view responsible
 * for adding a new class.
 * Class Add_class_controller
 */
class Add_class_controller extends MY_Controller
{
    /**
     * default method. If method is not specified, take to login page.
     */
    public function index()
    {
        redirect("Login_controller/newUserView");
    }

    /**
     * View methods
     */

    /**
     * Generates the add class view.
     */
    public function addClassView()
    {
        $view_components["header"] = $this->load->view("header", array("title" => "Add class"), true);
        $view_components["mainContent"] = $this->load->view("addClass/basic_info_comp", array(), true);

        $this->load->view("main", $view_components);
    }



    /**
     * Action methods
     */

    /**
     * Private methods
     */
}
