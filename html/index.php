<?php
/*
    Core Frameworks - version 0.10 - copyright Erik Stabij 
    
    Core -or Core Frameworks- is an ultra fast PHP framework with a minimal footprint.

    It is designed to be ultra fast, easy to learn and is easily extensible by third party packages.

    For more information see:   https://github.com/estabij/core
                                http://www.erikstabij.com/core
*/

/*
 * set error reporting
 */
define('ENVIRONMENT', 'development');
if (defined('ENVIRONMENT'))
{
    switch (ENVIRONMENT)
    {
        case 'development':
            error_reporting(E_ALL);
        break;
    
        case 'production':
            error_reporting(0);
        break;

        default:
            exit('The application environment is not set correctly.');
    }
}

/*
 * set the two required paths
 */
define('APPLICATION_PATH', '../application/');
define('SYSTEM_PATH', '../system/');

/*
 * load the autoloader
 */
require_once(SYSTEM_PATH."core/autoloader.php");
autoloader::getInstance()->registerLoader();

/*
 * load the front end controller
 * and start your web application...
 */
require_once(SYSTEM_PATH."controllers/front_end_controller.php");
front_end_controller::getInstance()->run();
