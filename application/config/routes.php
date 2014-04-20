<?php
/*
 * route configuration file 
 * @author estabij
 *
 */

//static routes
$sroute["/"]                        = "index_controller/index";

// regexp routes
$rroute["#^/(foo|bar)$#"]        	= "index_controller/index/$1";


/* End of file routes.php */
/* Location: ./application/config/routes.php */
