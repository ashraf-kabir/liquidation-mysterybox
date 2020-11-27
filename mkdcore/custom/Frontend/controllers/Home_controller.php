<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

/**
 * Home Controller to Manage all Frontend pages
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Home_controller extends Manaknight_Controller
{
     
    public $_data = [
        'error' => '',
        'success' => ''
    ];

    public function __construct()
    {
        parent::__construct();  
        $this->load->model('category_model');
        $this->load->model('inventory_model');
    }

    public function index($offset = 0)
    { 
        // $this->load->model('inventory_model');
        

        // $search_term = $this->input->get('search_term', TRUE);
        // $category_id = $this->input->get('category', TRUE);
        // $type_id     = $this->input->get('type', TRUE);

        // if($search_term != '')
        // {
        //     $this->load->model('search_terms_model');
        //     $this->load->library('search_record_service');

        //     $this->search_record_service->set_search_terms_model($this->search_terms_model); 
        //     $this->search_record_service->add_search_record($search_term); 
        // }

        // $this->load->library('pagination');
        // $rows_data = $this->inventory_model->all_products_list($type_id, $search_term, $category_id);

        // $total_rows = 0;
        // if(!empty($rows_data))
        // {
        //     $total_rows = count($rows_data);
        // }
        // $limit = 3;
        // $this->pagination->initialize([
        //     'reuse_query_string' => TRUE,
        //     'base_url' => 'http://localhost:9000/',
        //     'total_rows' => $total_rows,
        //     'per_page' => $limit,
        //     'num_links' => 4,
        //     'full_tag_open' => '<ul class="pagination justify-content-end">',
        //     'full_tag_close' => '</ul>',
        //     'attributes' => ['class' => 'page-link'],
        //     'first_link' => FALSE,
        //     'last_link' => FALSE,
        //     'first_tag_open' => '<li class="page-item">',
        //     'first_tag_close' => '</li>',
        //     'prev_link' => '&laquo',
        //     'prev_tag_open' => '<li class="page-item">',
        //     'prev_tag_close' => '</li>',
        //     'next_link' => '&raquo',
        //     'next_tag_open' => '<li class="page-item">',
        //     'next_tag_close' => '</li>',
        //     'last_tag_open' => '<li class="page-item">',
        //     'last_tag_close' => '</li>',
        //     'cur_tag_open' => '<li class="page-item active"><a href="#" class="page-link">',
        //     'cur_tag_close' => '<span class="sr-only">(current)</span></a></li>',
        //     'num_tag_open' => '<li class="page-item">',
        //     'num_tag_close' => '</li>'
        // ]);
        

        // $data['all_products']    = $this->inventory_model->all_products_list($type_id, $search_term, $category_id,$offset,$limit);
        
        

         
        $data['layout_clean_mode'] = FALSE;
        $this->_render('Guest/Home',$data);
    }

    public function categories()
    {   
        $this->load->library('pagination');
        $this->_data['category_id']    =     $this->input->get('category_id', TRUE) != NULL  ? $this->input->get('category_id', TRUE) : NULL ;
        $this->_data['search_query']   =     $this->input->get('search_query', TRUE) != NULL  ? $this->input->get('search_query', TRUE) : NULL ;
          
        $where = [
            'category_id'    => $this->_data['category_id'], 
            'product_name'   => $this->_data['search_query'],  
            'sku'            => $this->_data['search_query'] 
        ];
 
        $total_rows = 44; 
        $limit = 3;
        $this->pagination->initialize([
            'reuse_query_string' => TRUE,
            'base_url'      => base_url() . "categories",
            'total_rows'    => $total_rows,
            'per_page'      => $limit,
            'num_links'     => 4,
            'full_tag_open' => '<ul class="pagination justify-content-end">',
            'full_tag_close' => '</ul>',
            'attributes' => ['class' => 'page-link'],
            'first_link' => FALSE,
            'last_link' => FALSE,
            'first_tag_open' => '<li class="page-item">',
            'first_tag_close' => '</li>',
            'prev_link' => '&laquo',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',
            'next_link' => '&raquo',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',
            'last_tag_open' => '<li class="page-item">',
            'last_tag_close' => '</li>',
            'cur_tag_open' => '<li class="page-item active"><a href="#" class="page-link">',
            'cur_tag_close' => '<span class="sr-only">(current)</span></a></li>',
            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>'
        ]);

        $data['products_list']  = $this->inventory_model->get_all($where); 

        $data['all_categories']  = $this->category_model->get_all(['status' => 1]);


        $data['layout_clean_mode'] = FALSE;
        $data['active'] = 'home';
         
        $this->_render('Guest/Categories',$data);
             
    }
    



    public function contacts()
    { 
         
        if($this->input->post('email', TRUE))
        {
            $name         =  $this->input->post('name', TRUE);
            $from_email   =  $this->input->post('email', TRUE);
            $subject      =  $this->input->post('subject', TRUE);
            $subject      =  $subject . ' - ' . $name;
            $message      =  $this->input->post('message', TRUE); 

            if( $this->_send_email($from_email, $subject, $message, $name) )
            {
                $this->session->set_flashdata('success','Your message has been sent successfully.');
            } else{
                $this->session->set_flashdata('error','Error! Please try again later.');
            }  

            return redirect($_SERVER['HTTP_REFERER']);
        }
        

        $data['active'] = 'contact';
        $data['layout_clean_mode'] = FALSE;
        $this->_render('Guest/Contact',$data);
    }


    public function cart()
    { 
        $data['active'] = 'about';
        $data['layout_clean_mode'] = FALSE;
        $this->_render('Guest/Cart',$data);
    }



    public function checkout()
    { 
        $data['active'] = 'about';
        $data['layout_clean_mode'] = FALSE;
        $this->_render('Guest/Checkout',$data);
    }

    

    public function product($id = 0)
    { 
        $data['layout_clean_mode'] = FALSE;
         
        
        $this->_render('Guest/Product',$data);
    }

    
     

     

    protected function _render($template, $data)
    {
        
        $data['all_categories']  = $this->category_model->get_all(['status' => 1]);
        $data['page_section'] = $template;
        $data['contact_us_email'] = $this->config->item('contact_us_email'); 
         

        $this->load->view('Guest/Header', $data);
        $this->load->view($template, $data);
        $this->load->view('Guest/Footer', $data);
    }

    protected function _send_email( $from_email ,$subject, $template, $name)
    { 
        $this->load->library('mail_service');
        $this->mail_service->set_adapter('smtp'); 
         
        $email = $this->config->item('contact_us_email'); 
        return $this->mail_service->send($from_email, $email, $subject, $template); 
        return FALSE;
    }




    public function sign_up()
    { 
         
        if($this->input->post('email', TRUE))
        {
            $name       = $this->input->post('first_name', TRUE);
            $email      = $this->input->post('email', TRUE);
            $password   = $this->input->post('password', TRUE); 

            $this->load->model('customer_model');

            $user = $this->customer_model->get_by_fields([
                'email'  => $email,  
            ]);

            if ($user)
            {
                $output['status'] = 0;
                $output['error'] = 'Error! Email already exists.';
                echo json_encode($output);
                exit();
            }


            $result = $this->customer_model->create([
                'name'      => $name,
                'email'     => $email, 
                'password'  => password_hash($password, PASSWORD_BCRYPT),  
                'status'    => 1,  
            ]);
    
            if($result)
            {
                $output['status']   = 200;
                $output['success']  =  "Your account has been registered successfully,you can login now.";  
                echo json_encode($output);
                exit();
            }else{
                $output['status'] = 0;
                $output['error'] = 'Error! Please try again later.';
                echo json_encode($output);
                exit();
            }
        } 
    }



    public function login_customer()
    {
        if($this->input->post('email', TRUE))
        { 
            $email      = $this->input->post('email', TRUE);
            $password   = $this->input->post('password', TRUE); 

            $this->load->model('customer_model');

            $user = $this->customer_model->get_by_fields([
                'email'  => $email, 
                'status' => 1,
            ]);

            if ($user)
            {
                if(password_verify($password, $user->password))
                {
 
                    $this->set_session('user_id', (int) $user->id); 
                    $this->set_session('email', (string) $user->email); 
                    $this->set_session('customer_login', 1); 

                    $output['status'] = 0;
                    $output['success'] = 'Success!.';
                    echo json_encode($output);
                    exit();

                }else{
                    $output['status'] = 0;
                    $output['error'] = 'Error! Invalid email or password.';
                    echo json_encode($output);
                    exit();
                }
               
            }else{
                $output['status'] = 0;
                $output['error'] = 'Error! Invalid email or password.';
                echo json_encode($output);
                exit();
            } 
        }
    }


    public function logout ()
    {
        $this->destroy_session();
		return $this->redirect('pos/login');
    }

}