<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Share model
 * 
 * @author		Ankit Vishwakarma
 * @package		PyroCMS\Core\Modules\Comments\Models
 */
class Share_m extends MY_Model
{
    public function insert($data, $skip_validation = false)
    {
        return parent::insert($data, $skip_validation);
    }
}
