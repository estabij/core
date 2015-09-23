<?php
/*
 * The login_controller provides login and logout functionality
 * In its controller a check is done if the user is logged in
 * If not then the user is redirected to the homepage
 *
 * Common use:
 * (1) index_controller calls login_controller::login().
 * index_controller extends base_controller
 * (2) member_controller extends login_controller
 * The member_controller calls the constructor of the login_controller
 */

class login_controller extends base_controller {

    protected $user;

    public function __construct() {
        session_start();
        $this->user = $this->checkSession();
    }

    public function login($username, $password) {
        $userModel = new user_model();
        $this->user = $userModel->getUserByUserNameAndPassword($username, $password);

        if ($this->user) {

            $_SESSION['logged_in'] = time();
            $_SESSION['user_id'] = $this->user['id'];

            /* begin login registration part */
            $loginModel = new login_model();
            $loginModel->insertLogin($this->user['id']);
            /* end login registration part */

            return true;
        }
        return false;
    }

    public function checkSession() {

        if(!isset($_SESSION['logged_in'])) {
            return false;
        }

        $config = config::getInstance();
        $session_time = $config->getConfig('session_time');

        if($_SESSION['logged_in'] < time() - $session_time) {
            $this->logout();
            return false;
        }

        $_SESSION['logged_in'] = time();
        $user_id = $_SESSION['user_id'];

        $userModel = new user_model();
        $user = $userModel->getUserById($user_id);

        /* begin login registration part */
        $loginModel = new login_model();
        $last_login = $loginModel->getLastLoginByUser($this->user['id']);
        $this->user['lastlogin'] = 'Last login at '.$last_login['time'].' with IP: '.$last_login['ip'];
        /* end login registration part */

        return $this->user;
    }

    public function logout() {
        unset($_SESSION['logged_in']);
        $whereami=$_SERVER['REQUEST_URI'];
        $loc='/';
        if ($whereami!='/logout') {
            $loc = "/?w=".$whereami;
        }
        header('location: '.$loc);
    }

}
/* End of file login_controller.php */
/* Location: ./application/controllers/login_controller.php */

