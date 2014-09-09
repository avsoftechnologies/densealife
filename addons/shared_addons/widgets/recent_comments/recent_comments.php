<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * Show Recent Comments in your site with a widget. 
 * 
 * Intended for use on cms pages. Usage : 
 * on a CMS page add:
 * 
 *		{widget_area('name_of_area')} 
 * 
 * 'name_of_area' is the name of the widget area you created in the  admin 
 * control panel
 * 
 * @author		DaPrimitive Soource
 * @package		PyroCMS\Addons\Shared_Addons\Widgets
 */

class Widget_Recent_comments extends Widgets
{
	public $title		= array(
		'en' => 'Recent Comments',
		'ar' => 'التعليقات الأخيرة',
		'bn' => 'সাম্প্রতিক মন্তব্যসমূহ',
		'pt' => 'Recentes Comentários',
		'ru' => 'Последние комментарии',
		'id' => 'Komentar Terakhir',
	);
	public $description	= array(
		'en' => 'Display recent comments with a widget',
		'ar' => 'عرض التعليقات الأخيرة مع تطبيق مصغر',
		'bn' => 'উইজেটের সাহায্যে সাম্প্রতিক মন্তব্য দেখান',
		'pt' => 'Exibir comentários recentes com um widget',
		'ru' => 'Показать последних комментариев с виджетом',
		'id' => 'Tampilan komentar baru-baru ini dengan widget',
	);
	public $author		= 'DaPrimitive Soource';
	public $website		= 'http://www.psoource.com';
	public $version		= '1.0';

	// build form fields for the backend
	// MUST match the field name declared in the form.php file
	public $fields = array(
		array(
			'field' => 'limit',
			'label' => 'Number of comments',
		)
	);

	public function form($options)
	{
		!empty($options['limit']) OR $options['limit'] = 5;

		return array(
			'options' => $options
		);
	}

	public function run($options)
	{		
		// load the comments module's model
		class_exists('Comments_m') OR $this->load->model('comments/comments_m');

		// sets default number of comments to be shown
		empty($options['limit']) AND $options['limit'] = 5;

		// retrieve the recent comments using the comments module's model
		$recent_comments = $this->comments_m->get_recent($options['limit']);

		// process the comments using the comments module's helper
		$blog_widget = self::process_comment_items($recent_comments);

		// returns the variables to be used within the widget's view
		return array('blog_widget' => $blog_widget);
	}
	
	/**
	 * Function to process the items in an X amount of comments
	 *
	 * @param array $comments The comments to process
	 * @return array
	 */
	function process_comment_items($comments)
	{
		$ci =& get_instance();

		foreach ($comments as &$comment)
		{
			// work out who did the commenting
			if ($comment->user_id > 0)
			{
				$comment->name = anchor('user/'.$comment->user_id, $comment->name);
			}

			if (module_exists($comment->module))
			{
				$model_name = singular($comment->module).'_m';
				
				if ( ! isset($ci->{$model_name.'_m'}))
				{
					$ci->load->model($comment->module.'/'.$model_name);
				}

				if ($item = (object) $ci->{$model_name}->get($comment->module_id) AND isset($item->id))
				{
					$comment->item = anchor($comment->module. '/' . date('Y', $item->created_on) . '/' . date('m', $item->created_on) . '/' . $item->slug, nl2br($comment->comment));
				}
			}
			else
			{
				$comment->item = $comment->module .' #'. $comment->module_id;
			}
		}
		
		return $comments;
	}
}
