<?php

class App
{   
    // Default controller
    protected $controller = 'userAuth';

    // Default method
    protected $method = 'index';

    // Parameters passed
    protected $params = [];

    public function __construct()
    {
        // Get url components
        $urlContent = $this->parseUrl();

        /*
            If the first parameter passed in the url is a valid controller and user is authorised, set the
            App's controller to the one that's been requested, otherwise go with the default controller (auth).
        */
        if(file_exists('../app/controllers/' . $urlContent[0] . '.php'))
        {
            $this->controller = $urlContent[0];
            unset($urlContent[0]);
        }

        // Include the controller
        require_once '../app/controllers/' . $this->controller . '.php';

        // Instantiate the controller
        $this->controller = new $this->controller;

        /*
            If a second parameter is passed in the url, is a valid method and the user is authorised, set the
            App's method to the one passed in the url. Otherwise, go with the default method (index).
        */
        if(isset($urlContent[1]))
        {
            if(method_exists($this->controller, $urlContent[1]))
            {
                $this->method = $urlContent[1];
                unset($urlContent[1]);
            }
        }

        // If there are any more parameters passed and the user is authorised, add the parameters to the app
        if (isset($_SESSION['isAuth']))
        {
            $this->params = $urlContent ? array_values($urlContent) : [];
        }
        
        // Construct the view once parameters established
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl()
    {
        if(isset($_GET['url']))
        {
            return $urlContent = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }
}