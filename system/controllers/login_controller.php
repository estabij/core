<?php
/*
* base_controller
* adds common functionality to controllers
*
* @author estabij
*/

class login_controller extends base_controller {

    protected $loggedIn = false;

    public function __construct() {
        session_start();
        if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
            $this->loggedIn = false;
        } else {
            $this->loggedIn = true;
        }
    }

    public function login(array $param = array()) {
        if (isset( $_POST['username']) && $_POST['username'] != '' ) {
            if (isset( $_POST['password']) && $_POST['password'] != '' ) {
                if ( $this->validateUser($_POST['username'], $_POST['password'])) {
                    $_SESSION['login'] = $_POST['username'];
                }
            }
        }
        $this->renderView('login_view', array('page'=>'', 'message' => GetText('Messages')));
    }
//
//    protected function validateUser($name, $password) {
//        $myConfig = file_get_contents(APPLICATION_PATH.'config/users.json');
//        if ( $myConfig === FALSE) {
//            throw new Exception('ERROR: Cannot read users.json');
//        }
//        $users = json_decode($myConfig);
//        foreach($users as $user_name => $user_password) {
//             if (strcmp($user_name, $name)==0) {
//                if (strcmp($user_password, $password)==0) {
//                    return true;
//                }
//            }
//        }
//        return false;
//    }
//
//    public function logout() {
//        $_SESSION['login'] = '';
//        $this->login();
 //   }

}
/* End of login_controller.php */
/* Location: ./system/controllers/login_controller.php */
