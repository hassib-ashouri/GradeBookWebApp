<?php

if (!function_exists('asset_url')) {
    /**
     * Gets the absolute url to the assets directory
     * @return string
     */
    function asset_url()
    {
        return base_url() . 'assets/';
    }
}

if (!function_exists('s4')) {
    /**
     * Generates a 4 digit hex number as a string
     * @return string
     */
    function s4()
    {
        $rand = rand(65536, 131071);
        $hex = dechex(floor($rand));
        return substr($hex, 1);
    }
}

if (!function_exists('guid')) {
    /**
     * Generates a unique(ish) id
     *      credit: https://stackoverflow.com/a/105074
     * @return string
     */
    function guid()
    {
        return s4() . s4() . '-' . s4() . '-' . s4() . '-' .
            s4() . '-' . s4() . s4() . s4();
    }
}

if (!function_exists('pretty_dump')) {
    /**
     * Var_dumps an $object in a <pre></pre> block
     * @param $object
     */
    function pretty_dump($object)
    {
        echo "<pre>";
        var_dump($object);
        echo "</pre>";
    }
}

if (!function_exists('redirectNonUser')) {
    function redirectNonUser()
    {
        $CI =& get_instance();
        if (!isset($CI->session->get_userdata()["loggedUser"])) {
            redirect("Login_controller/existingUserView");
        }
    }
}

if (!function_exists('images_url')) {
    /**
     * Gets the absolute url to the assets/images directory
     * @return string
     */
    function images_url()
    {
        return asset_url() . 'images/';
    }
}

if (!function_exists('javascripts_url')) {
    /**
     * Gets the absolute url to the assets/javascripts directory
     * @return string
     */
    function javascripts_url()
    {
        return asset_url() . 'javascripts/';
    }
}

if (!function_exists('stylesheets_url')) {
    /**
     * Gets the absolute url to the assets/stylesheets directory
     * @return string
     */
    function stylesheets_url()
    {
        return asset_url() . 'stylesheets/';
    }
}