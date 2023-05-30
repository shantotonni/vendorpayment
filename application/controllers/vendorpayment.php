<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vendorpayment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database('default', true);
        $this->load->model('Vendor_payment_data');
    }

    public function index() {

        $data               = array();
        $data['head']       = $this->load->view('login/head', $data, true);
        $data['footer']     = $this->load->view('login/footer', $data, true);
        $data['content']    = $this->load->view('login/content', $data, true);
        $data['tracking_id'] = $this->uri->segment(2);

        if (!empty($data['tracking_id'])) {
				//echo "FOUND";
            $data['report'] = $this->Vendor_payment_data->vendorPaymentReport($data['tracking_id']);
            //echo "<pre />";print_r($data['report']);die();
        }
        $this->load->view('vendor_payment', $data);
    }

    public function demo() {
        echo "<pre />"; print_r('ok'); exit();
        $data               = array();
        $data['head']       = $this->load->view('login/head', $data, true);
        $data['footer']     = $this->load->view('login/footer', $data, true);
        $data['content']    = $this->load->view('login/content', $data, true);
        $data['tracking_id'] = $this->input->post("TrackingID", TRUE);

        if (!empty($_POST['TrackingID'])) {
            $data['report'] = $this->Vendor_payment_data->vendorPaymentReport($data['tracking_id']);
        }

        $this->load->view('vendor_payment', $data);
    }

    public function paymenttrackingprint(){

        $this->load->library('Pdf');
        $options = new Dompdf\Options();

        $options->setIsRemoteEnabled(true);
        $options->setDpi(100);
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsJavascriptEnabled(true);
        $options->setIsPhpEnabled(true);
        $options->setIsFontSubsettingEnabled(true);
        $this->pdf->setOptions($options);
        $this->pdf->set_option('defaultMediaType', 'all');

        $data               = array();
        $data['tracking_id'] = $this->input->post("TrackingID", TRUE);

        $data['report'] = $this->Vendor_payment_data->vendorPaymentReport($data['tracking_id']);

        $html = $this->load->view('vendor_payment_print', $data, true);

        $html.="<style>
            @font-face { font-family: 'Roboto Regular'; font-weight: normal; src: url(\'fonts/Roboto-Regular.ttf\') format(\'truetype\'); } 
            @font-face { font-family: 'Roboto Bold'; font-weight: bold; src: url(\'fonts/Roboto-Bold.ttf\') format(\'truetype\'); } 
            body{ font-family: 'Helvetica, Arial, sans-serif; font-size: 5pt;}
            td{font-size: 6pt !important;}
            th{font-size: 7pt !important;}
            #pdf-title{font-size: 15px !important;}
            #parta{font-size: 8pt !important;}
            #partb{font-size: 8pt !important;}
            #partc{font-size: 8pt !important;}
            #partd{font-size: 8pt !important;}
            table {border-collapse: collapse;}
            table, th, td {border: 1px solid black;}
            .main-title{display:none;}
            .btn-pdfdownload{display:none;}
            </style>";

        $this->pdf->loadHtml($html, 'UTF-8');
        $this->pdf->setPaper('A4', 'Portrait');
        $this->pdf->render();
        $this->pdf->stream(date("D-M-d-Y-G-i", time()) . ".pdf", array("Attachment" => 0));

    }

}
