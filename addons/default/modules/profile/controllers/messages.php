<?php

class Messages extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template->set_layout('messages');
    }
    
    public function index()
    {
        $this->template->build('messages/index');
    }
}

