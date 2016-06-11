<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Common Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		xie.hj@thinkalways.net
 * @link		https://github.com/actors315/smartOA.git
 */

// ------------------------------------------------------------------------

if ( ! function_exists('get_client_ip'))
{
	/**
	 * Site URL
	 *
	 * Create a local URL based on your basepath. Segments can be passed via the
	 * first parameter either as a string or an array.
	 *
	 * @param	string	$uri
	 * @param	string	$protocol
	 * @return	string
	 */
	function get_client_ip($uri = '', $protocol = NULL)
	{
		return get_instance()->config->site_url($uri, $protocol);
	}
}