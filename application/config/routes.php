<?php
/*
 * route configuration file 
 * @author estabij
 *
 */

//static routes
$sroute["/"]                    	= "index_controller/index";
$sroute["/foo/bar"]             	= "index_controller/index/foo/bar";

// regexp routes
$rroute["#^/(test1|test2)$#"]   	= "index_controller/index/$1";
$rroute["#^/(.*)?/(.*)?/(.*)?$#"]  	= "index_controller/index/$1/$2/$3";

/* End of file routes.php */
/* Location: ./application/config/routes.php */
