<?php
session_start();

class UserAuth extends Controller
{
    protected $user;

    function __construct()
    {
        // Include and instantiate the User Class
        $this->user = parent::model('user');
    }

    public function index()
    {
        /*   If login form has been submitted   */
        if (isset($_POST['user'], $_POST['passwd']) && $_POST['user'] != '' && $_POST['passwd'] != '')
        {
            $this->user->setUsername($_POST['user']);
            unset($_POST['user']);
            $this->user->setPasshash(md5($_POST['passwd']));
            unset($_POST['passwd']);

            if($this->user->logIn())
            {
                // Login succesfull, go to dashboard
                header("Location: https://www.galeadigital.com/dashboard/index");
                
            } else
            {
                // Wrong credentials entered, back to login, show bad credentials error
                parent::view('login');
                
            }
        } else if (isset($_SESSION['isAuth']) && $_SESSION['isAuth'] === true)
        {
            /*   If there user has valid session data, proceed to dashboard   */
            header("Location: https://www.galeadigital.com/dashboard/index");

        } else if (!isset($_SESSION['user']))
        {
            /*   If no session data & no form submitted, go straight to login form   */
            parent::view('login');
        }
    }

    public function logout()
    {
        $_SESSION = array();
        session_destroy();
        parent::view('confLogOut');
    }
}