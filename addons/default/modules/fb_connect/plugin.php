<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Friends Plugin
 *
 * @author   PyroCMS Dev Team
 * @package  PyroCMS\Core\Modules\Comments\Plugins
 */
class Plugin_fb_connect extends Plugin
{

    public $version     = '1.0.0';
    public $name        = array(
        'en' => 'Facebook Login',
    );
    public $description = array(
        'en' => 'Facebook Login',
    );
    
    public function login()
    {
        $this->load->spark('facebook-sdk/0.0.1');
        $loginUrl = $this->facebook->getLoginUrl(array(
            'scope'        => Settings::get('login_fb_scope'),
            'redirect_uri' => base_url(Settings::get('login_fb_redirecturl')),
            'client_id'    => Settings::get('login_fb_appid')
        ));
        return $loginUrl;
    }

}

/* End of file plugin.php */
