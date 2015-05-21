<?php
/*
* base_controller
* adds common functionality to controllers
*
* @author estabij
*/

require_once '../vendor/autoload.php'; //for Twig templating

class base_controller {

    private $templating = 'Twig';

    public function __construct() {}

    protected function renderView($template, array $data = array(), $render=true, $cache=true) {
        $output = '';
        if ( $this->templating == 'Twig' ) {
            $output = $this->renderTwigView($template, $data, $cache);
        } else {
            $output = $this->renderBareView($template, $data);
        }
        if ( $render ) {
            echo $output;
        } else {
            return $output;
        }
    }

    protected function renderBareView($template, array $data = array()) {

        $base_url = $this->base_url();
        extract($data);

        ob_start();
        include(APPLICATION_PATH.'views/'.$template.'.html');
        return ob_get_clean();
    }

    protected function renderTwigView($template, array $data = array(), $cache=true) {

        $cache = false; //handy for development

        $loader = new Twig_Loader_Filesystem(APPLICATION_PATH.'views/');

        if ( $cache ) {
            $cache = APPLICATION_PATH.'views/compilation_cache';
        }

        $twig = new Twig_Environment($loader, array(
            'cache' => $cache,
        ));

        $base_url = $this->base_url();
        extract($data);

        return $twig->render($template.'.html', $data);
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
