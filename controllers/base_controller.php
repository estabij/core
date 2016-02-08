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
    private $globals;
    private $globalFilters;

    public function __construct() {
        $this->addGlobalViewVariable('base_url', $this->base_url());
        $this->addGlobalViewVariable('page_url', $this->page_url());
        $this->globalFilters = [];
    }

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

        $data = array_merge($data, $this->globals);

        //filters are not supported in the bareView

        ob_start();
        include(APPLICATION_PATH.'views/'.$template.'.html');
        return ob_get_clean();
    }

    //here template needs to be a template string
    protected function renderTwigViewFromString($template, array $data = array(), $cache=true) {

        if ( ENVIRONMENT == 'development' ) {

            $cache = false;

        } else if ( $cache ) {

            $cache = APPLICATION_PATH.'views/compilation_cache';

        }

        $twig = new Twig_Environment(new \Twig_Loader_String(), array(
            'cache' => $cache,
        ));

        $data = array_merge($data, $this->globals);

        foreach($this->globalFilters as $key=>$value) {
            $twig->addFilter(new Twig_SimpleFilter($key, $value));
        }

        return $twig->render($template, $data);
    }

    //here template needs to be a template file
    protected function renderTwigView($template, array $data = array(), $cache=true) {

        if ( ENVIRONMENT == 'development' ) {

            $cache = false;

        } else if ( $cache ) {

            $cache = APPLICATION_PATH.'views/compilation_cache';

        }

        $loader = new Twig_Loader_Filesystem(APPLICATION_PATH.'views/');

        $twig = new Twig_Environment($loader, array(
            'cache' => $cache,
        ));

        $data = array_merge($data, $this->globals);

        foreach($this->globalFilters as $key=>$value) {
            $twig->addFilter(new Twig_SimpleFilter($key, $value));
        }

        return $twig->render($template.'.html.twig', $data);
    }

    protected function base_url(){
        return sprintf(
          "%s://%s%s",
          isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
          $_SERVER['HTTP_HOST'],
          $_SERVER['REQUEST_URI']
        );
    }

    protected function page_url(){
        return $_SERVER['REQUEST_URI'];
    }

    protected function dumpMethodInfo($methodName, $param) {
        echo $methodName.PHP_EOL;
        var_dump($param);
        var_dump($_GET);
        var_dump($_POST);
    }

    /**
     * addGlobalViewVariable() can be used to pass a string or an object to the view
     *
     * @param $key = name of variable or name of object
     * @param $value = variable or object
     */
    protected function addGlobalViewVariable($key, $value) {
        $this->globals[$key] = $value;
    }

    protected function addGlobalViewFilter($key, $function) {
        $this->globalFilters[$key] = $function;
    }
}
/* End of file base_controller.php */
/* Location: ./core/controllers/base_controller.php */
