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

        $numParam = count($param);

        switch ( $numParam ) {

            case 0:
                $this->renderView('index_view');
            break;
        
            case 1:
                $note = "This is ". $param[0];
                $this->renderView('index_view', array('note'=>$note));
            break;

            case 2:
                $note = "This is ".$param[0].'/'.$param[1];
                $this->renderView('index_view', array('note'=>$note));
            break;

            case 3:
                $note = "This is ".$param[0].'/'.$param[1] .'/'.$param[2];
                $this->renderView('index_view', array('note'=>$note));
            break;

            default:
                $note = "Extra parameters provided which we accept";
                $this->renderView('index_view', array('note'=>$note));
        }
    }

}
/* End of file index_controller.php */
/* Location: ./application/controllers/index_controller.php */

