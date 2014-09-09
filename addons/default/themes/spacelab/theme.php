<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @date	14 Mar 2013
 * @author	Antoine Benevaut
 */

class Theme_spacelab extends Theme
{
  public $version		= '1.0';

  public $name			= 'spacelab';
  public $author		= 'Antoine Benevaut';
  public $author_website	= 'http://cavaencoreparlerdebits.fr';
  public $description	= 'Bootswatch HTML responsive template, based on Bootstrap.';
  public $website		= 'http://bootswatch.com';
  public $options 		= array(
		'show_breadcrumbs' 	=> array(
			'title'         => 'Do you want to show breadcrumbs?',
			'description'   => 'If selected it shows a string of breadcrumbs at the top of the page.',
			'default'       => 'yes',
			'type'          => 'radio',
			'options'       => 'yes=Yes|no=No',
			'is_required'   => TRUE
		),
	);
}
/* End of file theme.php */
/* ./Bootstrap/theme.php */
