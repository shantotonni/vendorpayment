<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class MY_Controller extends CI_Controller {	
	public $page = '';
	public $menuid = 0;
    function __construct() {
        parent::__construct();
        $this->load->database('default',true);  
        
        //$this->has_access();
        
        $this->data['ux_js'] = $this->config->item('ux_js');
        $this->data['ux_css'] = $this->config->item('ux_css');
        
        $alldata = $this->session->userdata('alldata');
        $userid = $this->session->userdata('userid');

        $local = gmmktime(gmdate("H"),gmdate("i")-360);
        $this->session->set_userdata('localdatetime', gmdate('Y-m-d h:i:s', $local));


        if (!$alldata) {
            if (!$this->session->userdata('login')){
                if ($this->input->is_ajax_request()){
                    echo "valid_script = true; window.location = '".site_url('/vendorpayment')."'";
                    exit();
                } else {
                    redirect('/vendorpayment');
                }
            }
        }
    }
    public function has_access(){
        $this->load->model('usermanager_data', 'usermanager');
        $userid     = $this->session->userdata('userid');
        $controller = $this->uri->segment(1);
        $method     = $this->uri->segment(2);
        $method2nd  = $this->uri->segment(3);
        $menuid = '';
        if(!empty($controller)){
            $link = $controller;
            if(!empty($method)){
                $link = $controller.'/'.$method;
                if(!empty($method2nd)){
                    $link = $controller.'/'.$method.'/'.$method2nd;
                }
            }
            $menuid = $this->usermanager->getMenuId($link, $userid);
        }
        
        if(!empty($menuid)){
            $permission = $this->usermanager->CheckMenupermission($userid, $menuid[0]['MenuId']);
           // print_r($permission); exit();
            if(count($permission) == 0){
                echo "You are not authorized for the page.<br />"; 
                header('Refresh:5; url= '. base_url()); 
                echo "You will be redirected in 5 seconds...";
                exit();
            }else{
                return true;
            }
        }else{
            return true;
        }
        //print "check access";  
    }
}
