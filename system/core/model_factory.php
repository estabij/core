<?php
/**
 * model_factory
 *
 * @author estabij
 */
class model_factory {
    public static function create($modelName){
        $className = $modelName.'_model'; 
        return new $className();
    }
}
/* End of file model_factory.php */
/* Location: ./system/core/model_factory.php */
