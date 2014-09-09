<?php

function load_view($module, $view, $data = array())
{
    $ext = pathinfo($view, PATHINFO_EXTENSION) ? '' : '.php';

    if ( file_exists(ci()->template->get_views_path() . 'modules/'.$module.'/' . $view . $ext) ) {
        // look in the theme for overloaded views
        $path = ci()->template->get_views_path() . 'modules/'.$module.'/';
    } else {
        // or look in the module
        list($path, $view) = Modules::find($view, $module, 'views/');
    }

    // add this view location to the array
    ci()->load->set_view_path($path);
    ci()->load->vars($data);

    return ci()->load->_ci_load(array( '_ci_view' => $view, '_ci_return' => true ));
}


function p($data)
{
    echo '<pre>'; 
    print_r($data); 
    echo '</pre>'; 
    exit; 
}

function parse_comment($text) {
    return preg_replace('@(http)?(s)?(://)?(([-\w]+\.)+([^\s]+)+[^,.\s])@', '<a class="color-blue" href="http$2://$4">$1$2$3$4</a>', $text);
}