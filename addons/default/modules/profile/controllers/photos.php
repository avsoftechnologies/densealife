<?php

class Photos extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template->set_layout('photos');
    }
    
    public function index()
    {
        $this->template->build('photos/index');
    }
}

