<?php
/*
 * index controller 
 * @author estabij
 *
 */

require_once(SYSTEM_PATH.'controllers/base_controller.php');

class index_controller extends base_controller {
	
    public function __construct() {}

    public function index(array $param = array()) {

        if ( count($param)==0 ) {
        
            $this->renderView('index_view');
        
        } else {
        
            switch ($param[0]) {
                case 'foo':
                    $note = "This is foo";
                    $this->renderView('index_view', array('note'=>$note));
                break;
                case 'bar':
                    $note = "This is bar";
                    $this->renderView('index_view', array('note'=>$note));
                break;
            }
        }
    }

}
/* End of file index_controller.php */
/* Location: ./application/controllers/index_controller.php */

