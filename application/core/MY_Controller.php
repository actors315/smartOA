<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
    protected $render_type;
    
    public function __construct() {        
        parent::__construct();
        
    }
    
    /**
     * @param   array   $vars   An associative array of data to be extracted for use in the view
     * @param   bool    $return Whether to return the view output or leave it to the Output class
     */
    protected function render($data,$return = FALSE){
        
        $view = $this->render_type == 'json' ? 'default':$this->router->fetch_class().'/'.$this->router->fetch_method();
        
        $this -> load -> view($this->render_type.'/'.$view,$data,$return);
    }

}
