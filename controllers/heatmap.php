<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Heatmap Controller
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package	   Ushahidi - http://source.ushahididev.com
 * @module	   Heatmap Controller	
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

class Heatmap_Controller extends Template_Controller {

	/**
	 * Automatically render the views loaded in this controller
	 * @var bool
	 */
	public $auto_render = TRUE;

	/**
	 * Name of the template view
	 * @var string
	 */
	public $template = 'heatmap/heatmap';


	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Create The Heatmap
	 */
	public function index()
	{
		$this->template->site_name = Kohana::config('settings.site_name');
		$this->template->site_tagline = Kohana::config('settings.site_tagline');
		$this->template->css_url = url::file_loc('css');
		$this->template->js_url = url::file_loc('js');
		$this->template->data= $this->_data();
	}

	/**
	 * Generate Clustered Data
	 * 
	 * @return array $data
	 */
	private function _data()
	{
		$data = array();
		$markers = reports::fetch_incidents();
		
		foreach ($markers as $marker)
		{
			$skip = FALSE;
			// To generate a good heatmap we need to combine lat/lons
			// at 3 decimal place values
			$marker->latitude = round($marker->latitude, 3);
			$marker->longitude = round($marker->longitude, 3);

			// Find item with similar lat/lon?
			foreach ($data as $key => $value)
			{
				if ($data[$key]['lat'] == $marker->latitude 
					AND $data[$key]['lon'] == $marker->longitude)
				{
					$data[$key]['count'] = $data[$key]['count'] + 1;
					$skip = TRUE;
					break 1;
				}
			}

			if ( ! $skip)
			{
				$data[] = array(
					'lat' => round($marker->latitude, 3),
					'lon' => round($marker->longitude, 3),
					'count' => 1
					);
			}
		}

		return json_encode($data);
	}
}