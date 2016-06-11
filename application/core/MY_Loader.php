<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader 
{
	
	/**
	 * List of paths to load services from
	 *
	 * @var array
	 * @access protected
	 */
	protected $_ci_service_paths		= array();
	
	/**
	 * List of loaded services
	 *
	 * @var array
	 * @access protected
	 */
	protected $_ci_services			= array();
	
	function __construct(){
		parent::__construct();
		
		$this->_ci_service_paths = array(APPPATH);
	}
	
	/**
	 * Service Loader
	 *
	 * This function lets users load and instantiate services.
	 *
	 * @param	string	the name of the class
	 * @param	mixed	the optional parameters
	 * @param	string	name for the service
	 * @return	void
	 */
	public function service($service, $params = NULL, $name = ''){
		if (is_array($service))
		{
			foreach ($service as $babe)
			{
				$this->service($babe);
			}
		}

		if ($service == '')
		{
			return ;
		}

		$path = '';

		// Is the service in a sub-folder? If so, parse out the filename and path.
		if (($last_slash = strrpos($service, '/')) !== FALSE)
		{
			// The path is in front of the last slash
			$path = substr($service, 0, $last_slash + 1);

			// And the service name behind it
			$service = substr($service, $last_slash + 1);
		}

		if (empty($name))
		{
			$name = $service;
		}

		if (in_array($name, $this->_ci_services, TRUE))
		{
			log_message('debug', $model.' has been loaded,no need to re-load.');
			return $this;
		}

		$CI =& get_instance();
		if (isset($CI->$name))
		{
			throw new Exception('The service name you are loading is the name of a resource that is already being used: '.$name);
		}
		
		$service = ucfirst(strtolower($service));

		foreach ($this->_ci_service_paths as $mod_path)
		{
			if ( ! file_exists($mod_path.'services/'.$path.$service.'.php'))
			{
				continue;
			}

			if ( ! class_exists('MY_Service'))
			{
				load_class('Service', 'core');
			}

			require_once($mod_path.'services/'.$path.$service.'.php');
			
			$this->_ci_services[] = $name;
			$CI->$name = new $service($params);
			
			return;
		}

		// couldn't find the service
		throw new Exception('The service is not exist: '.$service);
	}
	
}
	