<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendor_payment_data extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }         
    
    public function vendorPaymentReport($tracking_id){
        $CI = & get_instance();
        $CI->db = $this->load->database('default', true);

        $sql = "sp_VendorBillsPaymentTracking '$tracking_id'";
        $result['success'] = false;
        $query = $this->db->query($sql);
        $data = array();
        $error = $this->db->_error_message();
        //exit();
        if(!empty($error)){
            return false;
        }
        if ($query) {
			//echo "sp_VendorBillsPaymentTracking '27J495L24916X35'";
            $data['one'] = $query->result_array();
            $data['two'] = $query->next_result();
            return $data;
        }else{
            return false;
        }
    }
    
}

?>