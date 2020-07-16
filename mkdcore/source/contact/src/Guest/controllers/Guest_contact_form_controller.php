<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Guest_contact_form_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        $this->load->library('form_validation');
        $this->load->library('mail_service');
        $this->load->model('contact_form_blacklist_model');
        $this->load->model('email_model');
        $this->load->library('mail_service');
        $this->mail_service->set_adapter('smtp');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('message', 'Message', 'required');

        $data = [
            'title' => 'Contact Us',
            'error' => '',
            'success' => ''
        ];

        if ($this->form_validation->run() === FALSE)
        {
            if(isset($_POST['btn-send-contact']))
            {
               $data['contact_email_sent_error'] = TRUE;
               $data['error_message'] = validation_errors();
            }

            $this->load->view('Guest/Contact', $data);
            return;
        }

        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $message = $this->input->post('message');
        $honey_pot = $this->input->post('h_pot');
        $blacklist = $this->contact_form_blacklist_model->get(1);
        $message_words = explode(' ', $message);
        $blacklist_words =  explode(',',$blacklist->words);
        $from_email = $this->config->item('from_email');
        $domain = explode("@", $from_email)[1];
      
        if(!empty($honey_pot) || !empty(array_intersect($message_words, $blacklist_words)))
        {
            $data['success'] = 'Message Sent';
            $this->load->view('Guest/Contact', $data);
            return;
        }  

        $template = $this->email_model->get_template('contact', [
            'message' => $message,
            'name' => $name,
            'email' => $email
        ]);
         $this->mail_service->send('no-reply' . $domain , $from_email , $template->subject, $template->html);
         $data['success'] = 'Message Sent';
         $this->load->view('Guest/Contact', $data);
         return;
    }
}
