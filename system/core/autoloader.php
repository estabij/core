<?php
/**
 * classAutoloader
 *
 * @author estabij
 */
class autoloader {
    
    protected $dirs = array();

    private static $_autoloader; 

    // singleton
    public static function getInstance() {
        if ( ! isset(autoloader::$_autoloader) ) {
          autoloader::$_autoloader = new autoloader();
        }
        return autoloader::$_autoloader;
    }

    private function __construct() {}

    public function registerLoader() {
        $this->init();
        spl_autoload_register(array($this, 'loader'));
    }
    
    // we could very well store the directories the autoloader searches
    // into memcache or redis 
    protected function init() {
        $parentDirs = array(SYSTEM_PATH, APPLICATION_PATH);
        foreach ($parentDirs as $parentDir) {
            $dh = opendir($parentDir);
            while (false !== ($entry = readdir($dh))) {
                if (( $entry!='.' ) && ( $entry!='..' )) {
                    $full = $parentDir.$entry;
                    if (is_dir($full)) {
                        $this->dirs[] = $full;
                    }
                }
            }
        }        
    }
    
    protected function loader($className) {
        $classFileName = strtolower($className).'.php';
        foreach ($this->dirs as $dir) {
            $classFile=$dir.'/'.$classFileName;
            if(is_file($classFile)&&!class_exists($classFile)) {
                include $classFile;
                break;
            }
        }
    }
}
/* End of file autoloader.php */
/* Location: ./system/core/autoloader.php */