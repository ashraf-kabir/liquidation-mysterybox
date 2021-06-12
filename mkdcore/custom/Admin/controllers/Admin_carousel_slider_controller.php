<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * CarosalSlider Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_carousel_slider_controller extends Admin_controller
{
    protected $_model_file = 'carousel_slider_model';
    public $_page_name = 'Carousel Slider';

    public function __construct()
    {
        parent::__construct();



    }



    	public function index($page)
	{
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/CarosalSlider_admin_list_paginate_view_model.php';
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';
        $session = $this->get_session();
        $where = [];
        $this->_data['view_model'] = new CarosalSlider_admin_list_paginate_view_model(
            $this->carousel_slider_model,
            $this->pagination,
            '/admin/carosal_slider/0');
        $this->_data['view_model']->set_heading('Carousel Slider');
        $this->_data['view_model']->set_total_rows($this->carousel_slider_model->count($where));

        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_page($page);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/carosal_slider/0');
		$this->_data['view_model']->set_list($this->carousel_slider_model->get_paginated(
            $this->_data['view_model']->get_page(),
            $this->_data['view_model']->get_per_page(),
            $where,
            $order_by,
            $direction));

        if ($format == 'csv')
        {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="export.csv"');

            echo $this->_data['view_model']->to_csv();
            exit();
        }

        if ($format != 'view')
        {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->_data['view_model']->to_json()));
        }

        return $this->render('Admin/CarosalSlider', $this->_data);
	}

    	public function add()
	{
        include_once __DIR__ . '/../../view_models/CarosalSlider_admin_add_view_model.php';
        $session = $this->get_session();
        $this->form_validation = $this->carousel_slider_model->set_form_validation(
        $this->form_validation, $this->carousel_slider_model->get_all_validation_rule());
        $this->_data['view_model'] = new CarosalSlider_admin_add_view_model($this->carousel_slider_model);
        $this->_data['view_model']->set_heading('Carousel Slider');


		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/CarosalSliderAdd', $this->_data);
        }

        $feature_image = $this->input->post('feature_image', TRUE);
		$feature_image_id = $this->input->post('feature_image_id', TRUE);

        $result = $this->carousel_slider_model->create([
            'feature_image' => $feature_image,
			'feature_image_id' => $feature_image_id,

        ]);

        if ($result)
        {


            return $this->redirect('/admin/carosal_slider/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/CarosalSliderAdd', $this->_data);
	}

	public function edit($id)
	{
        $model = $this->carousel_slider_model->get($id);
        $session = $this->get_session();
		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/carosal_slider/0');
        }

        include_once __DIR__ . '/../../view_models/CarosalSlider_admin_edit_view_model.php';
        $this->form_validation = $this->carousel_slider_model->set_form_validation(
        $this->form_validation, $this->carousel_slider_model->get_all_edit_validation_rule());
        $this->_data['view_model'] = new CarosalSlider_admin_edit_view_model($this->carousel_slider_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('Carousel Slider');


		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/CarosalSliderEdit', $this->_data);
        }

        $feature_image = $this->input->post('feature_image', TRUE);
		$feature_image_id = $this->input->post('feature_image_id', TRUE);

        $result = $this->carousel_slider_model->edit([
            'feature_image' => $feature_image,
			'feature_image_id' => $feature_image_id,

        ], $id);

        if ($result)
        {


            return $this->redirect('/admin/carosal_slider/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/CarosalSliderEdit', $this->_data);
	}






    public function view($id)
	{
        $model = $this->carousel_slider_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/carosal_slider/0');
		}


        include_once __DIR__ . '/../../view_models/CarosalSlider_admin_view_view_model.php';
		$this->_data['view_model'] = new CarosalSlider_admin_view_view_model($this->carousel_slider_model);
		$this->_data['view_model']->set_heading('Carousel Slider');
        $this->_data['view_model']->set_model($model);

		return $this->render('Admin/CarosalSliderView', $this->_data);
	}

	public function delete($id)
	{
        $model = $this->carousel_slider_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/carosal_slider/0');
        }

        $result = $this->carousel_slider_model->real_delete($id);

        if ($result)
        {

            return $this->redirect('/admin/carosal_slider/0', 'refresh');
        }

        $this->error('Error');
        return redirect('/admin/carosal_slider/0');
	}











    public function top_bar_setting()
    {
        $this->_page_name = "Top Bar";
        $this->_data['page_name'] = "Top Bar";
        $id = 1;
        $this->load->model('home_page_setting_model');
        $model = $this->home_page_setting_model->get($id);
        $session = $this->get_session();
        if (!$model)
        {
            $this->error('Error! Please try again later.');
            return redirect('/admin/dashboard');
        }


        $this->form_validation = $this->home_page_setting_model->set_form_validation(
        $this->form_validation, $this->home_page_setting_model->get_all_edit_validation_rule());


        $this->_data['home_page_top_text']  = $model->home_page_top_text;
        $this->_data['home_page_top_color'] = $model->home_page_top_color;
        $this->_data['home_page_top_bg']    = $model->home_page_top_bg;
        $this->_data['heading']             = "Top Bar";


        if ($this->form_validation->run() === FALSE)
        {
            return $this->render('Admin/TopBarSettingEdit', $this->_data);
        }

        $home_page_top_text  = $this->input->post('home_page_top_text', TRUE);
        $home_page_top_color = $this->input->post('home_page_top_color', TRUE);
        $home_page_top_bg    = $this->input->post('home_page_top_bg', TRUE);

        $result = $this->home_page_setting_model->edit([
            'home_page_top_text' => $home_page_top_text,
            'home_page_top_color' => $home_page_top_color,
            'home_page_top_bg' => $home_page_top_bg,
        ], $id);

        if ($result)
        {

            $this->success("Success! Data has been updated successfully.");
            return $this->redirect('/admin/top_bar_setting', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/TopBarSettingEdit', $this->_data);
    }






    public function home_page_setting()
    {
        $this->_page_name = "Home Page Setting";
        $this->_data['page_name'] = "Home Page Setting";
        $id = 1;
        $this->load->model('home_page_setting_model');
        $model = $this->home_page_setting_model->get($id);
        $session = $this->get_session();
        if (!$model)
        {
            $this->error('Error! Please try again later.');
            return redirect('/admin/dashboard');
        }


        $this->form_validation = $this->home_page_setting_model->set_form_validation(
        $this->form_validation, $this->home_page_setting_model->get_all_edit_validation_rule());


        $this->_data['home_page_address']  = $model->home_page_address;
        $this->_data['home_page_phone_no'] = $model->home_page_phone_no;
        $this->_data['home_page_time']     = $model->home_page_time;
        $this->_data['home_page_support_email']   = $model->home_page_support_email;
        $this->_data['home_page_fb_link']         = $model->home_page_fb_link;
        $this->_data['home_page_tiktok_link']     = $model->home_page_tiktok_link;
        $this->_data['home_page_insta_link']      = $model->home_page_insta_link;
        $this->_data['home_page_twitter_link']    = $model->home_page_twitter_link;
        $this->_data['home_page_pintrest_link']   = $model->home_page_pintrest_link;
        $this->_data['product_text_note']         = $model->product_text_note;
        $this->_data['heading']                   = "Home Page Setting";


        if ($this->form_validation->run() === FALSE)
        {
            return $this->render('Admin/HomePageSettingEdit', $this->_data);
        }

        $home_page_address  = $this->input->post('home_page_address', TRUE);
        $home_page_phone_no = $this->input->post('home_page_phone_no', TRUE);
        $home_page_time     = $this->input->post('home_page_time', TRUE);
        $home_page_support_email    = $this->input->post('home_page_support_email', TRUE);
        $home_page_fb_link          = $this->input->post('home_page_fb_link', TRUE);
        $home_page_tiktok_link      = $this->input->post('home_page_tiktok_link', TRUE);
        $home_page_insta_link       = $this->input->post('home_page_insta_link', TRUE);
        $home_page_twitter_link     = $this->input->post('home_page_twitter_link', TRUE);
        $home_page_pintrest_link    = $this->input->post('home_page_pintrest_link', TRUE);
        $product_text_note          = $this->input->post('product_text_note', TRUE);

        $result = $this->home_page_setting_model->edit([
            'home_page_address'       => $home_page_address,
            'home_page_phone_no'      => $home_page_phone_no,
            'home_page_time'          => $home_page_time,
            'home_page_support_email' => $home_page_support_email,
            'home_page_fb_link'       => $home_page_fb_link,
            'home_page_tiktok_link'   => $home_page_tiktok_link,
            'home_page_insta_link'    => $home_page_insta_link,
            'home_page_twitter_link'  => $home_page_twitter_link,
            'home_page_pintrest_link' => $home_page_pintrest_link,
            'product_text_note'       => $product_text_note,
        ], $id);

        if ($result)
        {

            $this->success("Success! Data has been updated successfully.");
            return $this->redirect('/admin/home_page_setting', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/HomePageSettingEdit', $this->_data);
    }



}