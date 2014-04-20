<?php
/*
* base_controller
* adds common functionality to controllers
*
* @author estabij
*/
class base_controller {
        
    public function __construct() {}
    
    protected function renderView($template, array $data = array(), $render=true) {
        
        $base_url = $this->base_url();
        extract($data);
        
        ob_start();
        include(APPLICATION_PATH.'views/'.$template.'.php');
        if ( $render ) {
            ob_end_flush();
            return true;
        } else {
            return ob_get_clean();
        }
    }
    
    protected function base_url(){
        return sprintf(
          "%s://%s%s",
          isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
          $_SERVER['HTTP_HOST'],
          $_SERVER['REQUEST_URI']
        );
    }
    
    protected function dumpMethodInfo($methodName, $param) {
        echo $methodName.PHP_EOL;
        var_dump($param);
        var_dump($_GET);
        var_dump($_POST);
    }
}
/* End of file base_controller.php */
/* Location: ./system/controllers/base_controller.php */
