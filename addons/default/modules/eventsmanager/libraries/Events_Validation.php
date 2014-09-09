<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Events Manager Library
 *
 * @author   Tristan Jahier
 */
class Events_Validation
{

    private static $validation_rules = array(
        'category_id'     => array(
            'field' => 'category_id',
            'label' => 'lang:cat:category_label',
            'rules' => 'required'
        ),
        'sub_category_id' => array(
            'field' => 'sub_category_id',
            'label' => 'lang:sub_cat:category_label',
            'rules' => 'required'
        ),
        'title'           => array(
            'field' => 'title',
            'label' => 'lang:eventsmanager:title_label',
            'rules' => 'trim|max_length[300]|required'
        ),
        'website'           => array(
            'field' => 'website',
            'label' => 'Website',
            'rules' => 'trim|valid_url'
        ),
        'affiliations'           => array(
            'field' => 'affiliations',
            'label' => 'Website',
            'rules' => 'trim|max_length[300]'
        ),
        'slug'            => array(
            'field' => 'slug',
            'label' => 'lang:eventsmanager:slug_label',
            'rules' => 'trim|required|callback_slug_check'
        ),
        'about'           => array(
            'field' => 'about',
            'label' => 'about',
            'rules' => 'trim|required'
        ),
        'description'     => array(
            'field' => 'description',
            'label' => 'lang:eventsmanager:description_label',
            'rules' => 'trim|required'
        ),
        'place'           => array(
            'field' => 'place',
            'label' => 'lang:eventsmanager:place_label',
            'rules' => 'trim'
        ),
        'start_date'      => array(
            'field' => 'start_date',
            'label' => 'lang:eventsmanager:date_label',
            'rules' => 'trim',
        ),
        'end_date'        => array(
            'field' => 'end_date',
            'label' => 'lang:eventsmanager:end_date_label',
            'rules' => 'trim|callback_date_check'
        )
            );

    ////////////////////////////////////////////////////////////////

    private static function init()
    {
        ci()->load->library('settings');
        ci()->load->library('form_validation');
        ci()->load->model('eventsmanager/eventsmanager_m');
    }

    ////////////////////////////////////////////////////////////////
    // Form validation callbacks

    public static function slug_check($slug)
    {
        self::init();
        $exist = (int) ci()->eventsmanager_m->count_by('slug', $slug) ? true : false;

        if ($slug == 'past') {
            ci()->form_validation->set_message('slug_check', lang('eventsmanager:slug_past_error'));
            return false;
        } elseif ($exist && ci()->router->fetch_method() != 'edit') {
            ci()->form_validation->set_message('slug_check', $slug . ' has already been created. Please change the title.');
            return false;
        }
        return true;
    }

    public static function date_check($end_date)
    {
        self::init();

        $input = ci()->input->post();
        if (!empty($input['start_date'])) {
            // Format the dates
            $DATE_FORMAT    = ci()->settings->get('date_format');
            $start_datetime = date_create_from_format($DATE_FORMAT, $input['start_date']);
            $start_datetime->setTime($input['start_time_hour'], $input['start_time_minute']);
            if ($input['end_date_defined']) {
                $end_datetime = date_create_from_format($DATE_FORMAT, $input['end_date']);
                $end_datetime->setTime($input['end_time_hour'], $input['end_time_minute']);
            }
            if ($input['end_date_defined'] && strtotime($end_datetime->format('Y-m-d')) < strtotime($start_datetime->format('Y-m-d'))) { // If end date is prior to start date
                ci()->form_validation->set_message('date_check', lang('eventsmanager:date_logic_error'));
                return false;
            }
        }
        return true;
    }

    public static function rules()
    {
        self::$validation_rules['start_date']['default'] = date("Y-m-d H:i");
        return self::$validation_rules;
    }

    function valid_url($str)
    {
        if (filter_var($str, FILTER_VALIDATE_URL)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
