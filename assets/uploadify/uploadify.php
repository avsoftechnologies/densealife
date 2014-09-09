<?php

class Uploadify
{

    private $_files = null;
    private $_post = null;

    public function __construct($files, $post=null)
    {
        $this->_files = $files ;
        $this->_post = $post;
        ci()->load->library('files/files');
    }
    
    public function upload()
    {
        // this is just a safeguard if they circumvent the JS permissions
        if ( !in_array('upload', Files::allowed_actions()) AND
                // replacing files needs upload and delete permission
                !( $this->input->post('replace_id') && !in_array('delete', Files::allowed_actions()) )
        ) {
            show_error(lang('files:no_permissions')) ;
        }

        $result = null ;
        $input  = ci()->input->post() ;

        if ( $input['replace_id'] > 0 ) {
            $result = Files::replace_file($input['replace_id'], $input['folder_id'], $input['name'], 'file', $input['width'], $input['height'], $input['ratio'], null, $input['alt_attribute']) ;
            //$result['status'] AND Events::trigger('file_replaced', $result['data']) ;
        } elseif ($input['folder_id'] and $input['name'] ) {
            $result = Files::upload($input['folder_id'], $input['name'], 'file', $input['width'], $input['height'], $input['ratio'], null, $input['alt_attribute']) ;
           $result['status'] AND Events::trigger('file_uploaded', $result['data']) ;
        }

        ob_end_flush() ;
        //echo json_encode($result) ;
        echo '1';
    }

//    public function upload()
//    {
//        $verifyToken = md5('unique_salt' . $this->_post['timestamp']) ;
//        if ( !empty($this->_files) ) {
//            $tempFile  = $this->_files['Filedata']['tmp_name'] ;
//            // Validate the file type
//            $fileTypes = array( 'jpg', 'jpeg', 'gif', 'png' ) ; // File extensions
//            $fileParts = pathinfo($this->_files['Filedata']['name']) ;
//
//            if ( in_array($fileParts['extension'], $fileTypes) ) {
//                move_uploaded_file($tempFile, "uploads/" . $this->_files["Filedata"]["name"]) ;
//                echo '1' ;
//            } else {
//                echo 'Invalid file type.' ;
//            }
//        }
//    }

}

$uploadify = new Uploadify($_FILES, $_POST) ;
$uploadify->upload() ;
