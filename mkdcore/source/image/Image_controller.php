<?php
use Aws\S3\S3Client;

if (!defined('BASEPATH')) exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Image Abstract Controller
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Image_controller extends CI_Controller
{
    public $_data = [
        'error' => '',
        'success' => ''
    ];

    //testMode flag
    protected $_test_mode = FALSE;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index ()
    {
        $image_upload_type = $this->config->item('image_upload');

        if ($image_upload_type == 's3')
        {
            return $this->s3_upload();
        }

        $this->load->model('image_model');
        $data_uri = $this->input->post('image');
        $base_url = $this->config->item('base_url');
        $image_path = __DIR__ . '/../../../uploads/';
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data_uri));
        $filename = md5(uniqid() . time()) . '.png';
        file_put_contents($image_path . $filename, $data);
        list($width, $height) = @getimagesize( $image_path .$filename );
        $session = $this->get_session();
        $user_id = isset($session['user_id']) ? $session['user_id'] : 0;

        $image_id = $this->image_model->create([
            'url' => '/uploads/' . $filename,
			'type' => 0,
			'user_id' => $user_id,
            'width' => $width,
            'caption' => '',
			'height' => $height
        ]);

        return $this->output->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode([
            'id' => $image_id,
            'image' => $base_url . '/uploads/' . $filename,
            'width' => $width,
            'height' => $height
        ]));
    }

    public function s3_upload ()
    {
        $s3 = new S3Client([
            'version' => $this->config->item('aws_version'),
            'region'  => $this->config->item('aws_region'),
            'credentials' => [
                'key'    => $this->config->item('aws_key'),
                'secret' => $this->config->item('aws_secret'),
            ]
        ]);

        $this->load->model('image_model');
        $data_uri = $this->input->post('image');
        $image_path = __DIR__ . '/../../../uploads/';
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data_uri));
        $filename = md5(uniqid() . time()) . '.png';
        file_put_contents($image_path . $filename, $data);
        
        list($width, $height) = getimagesize( $image_path . $filename );
        $session = $this->get_session();
        $user_id = isset($session['user_id']) ? $session['user_id'] : 0;

        try
        {
            $result = $s3->putObject([
                'Bucket' => $this->config->item('aws_bucket'),
                'Key'    => $filename,
                'Body'   => fopen($image_path . $filename, 'r'),
                'ACL'    => 'public-read',
            ]);

            $image_id = $this->image_model->create([
                'url' => $result->get('ObjectURL'),
                'type' => 0,
                'user_id' => $user_id,
                'width' => $width,
                'caption' => '',
                'height' => $height
            ]);

            return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'id' => $image_id,
                'image' => $result->get('ObjectURL'),
                'width' => $width,
                'height' => $height
            ]));
        }
        catch (Aws\S3\Exception\S3Exception $e)
        {
            return $this->output->set_content_type('application/json')
            ->set_status_header(403)
            ->set_output(json_encode([
                'message' => 'xyzUpload to S3 Failed'
            ]));
        }
    }

    public function file_upload ()
    {
        $file_upload_type = $this->config->item('file_upload');
        $this->load->library('mime_service');
        if ($file_upload_type == 's3')
        {
            return $this->s3_file_upload();
        }

        $this->load->model('image_model');

        if  (!(isset($_FILES) && count($_FILES) > 0 && isset($_FILES['file'])))
        {
            return $this->output->set_content_type('application/json')
                ->set_status_header(403)
                ->set_output(json_encode([
                    'message' => 'xyzUpload file missing'
                ]));
        }

        $file = $_FILES['file'];
        $size = $file['size'];
        $path = $file['tmp_name'];
        $type = $file['type'];
        $extension = $this->mime_service->get_extension($type);

        if ($size > $this->config->item('upload_byte_size_limit'))
        {
            return $this->output->set_content_type('application/json')
            ->set_status_header(403)
            ->set_output(json_encode([
                'message' => 'xyzUpload file size too big'
            ]));
        }

        $filename = md5(uniqid() . time()) . $extension;
        $width = 0;
        $height = 0;
        $session = $this->get_session();
        $user_id = isset($session['user_id']) ? $session['user_id'] : 0;
        $image_path = __DIR__ . '/../../../uploads/';

        // if (!move_uploaded_file($path, $image_path . $filename))
        // {
        //     return $this->output->set_content_type('application/json')
        //     ->set_status_header(403)
        //     ->set_output(json_encode([
        //         'message' => 'xyzUpload file failed'
        //     ]));
        // }
        if (!compressImage($path, $image_path . $filename, 70))
        {
            return $this->output->set_content_type('application/json')
            ->set_status_header(403)
            ->set_output(json_encode([
                'message' => 'xyzUpload file failed'
            ]));
        }

        $image_id = $this->image_model->create([
            'url' => '/uploads/' . $filename,
            'type' => 4,
            'user_id' => $user_id,
            'width' => $width,
            'caption' => '',
            'height' => $height
        ]);

        return $this->output->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode([
            'id' => $image_id,
            'file' => '/uploads/' . $filename,
            'width' => $width,
            'height' => $height
        ]));
    }

    public function s3_file_upload ()
    {
        $this->load->model('image_model');
        $this->load->library('mime_service');

        $s3 = new S3Client([
            'version' => $this->config->item('aws_version'),
            'region'  => $this->config->item('aws_region'),
            'credentials' => [
                'key'    => $this->config->item('aws_key'),
                'secret' => $this->config->item('aws_secret'),
            ]
        ]);

        if (!(isset($_FILES) && count($_FILES) > 0 && isset($_FILES['file'])))
        {
            return $this->output->set_content_type('application/json')
            ->set_status_header(403)
            ->set_output(json_encode([
                'message' => 'xyzUpload file failed'
            ]));
        }

        $file = $_FILES['file'];
        $size = $file['size'];
        $path = $file['tmp_name'];
        $type = $file['type'];
        $extension = $this->mime_service->get_extension($type);

        if ($size > $this->config->item('upload_byte_size_limit'))
        {
            return $this->output->set_content_type('application/json')
            ->set_status_header(403)
            ->set_output(json_encode([
                'message' => 'xyzUpload file size too big'
            ]));
        }

        $filename = md5(uniqid() . time()) . $extension;
        $width = 0;
        $height = 0;
        $session = $this->get_session();
        $user_id = isset($session['user_id']) ? $session['user_id'] : 0;
        $image_path = __DIR__ . '/../../../uploads/';
        echo $path;
        echo $image_path;
        echo $filename;die;
        try {
            $compressedImage = compressImage($path, $image_path . $filename, 70);
        } catch (Exception $th) {
            echo json_encode($th->getMessage()); exit;
        }

        try
        {
            $result = $s3->putObject([
                'Bucket' => $this->config->item('aws_bucket'),
                'Key'    => $filename,
                'Body'   => fopen($$image_path . $filename, 'r'),
                'ACL'    => 'public-read',
            ]);

            $image_id = $this->image_model->create([
                'url' => $result->get('ObjectURL'),
                'type' => 5,
                'user_id' => $user_id,
                'width' => $width,
                'caption' => '',
                'height' => $height
            ]);

            return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'id' => $image_id,
                'file' => $result->get('ObjectURL'),
                'width' => $width,
                'height' => $height
            ]));
        }
        catch (Aws\S3\Exception\S3Exception $e)
        {
            return $this->output->set_content_type('application/json')
            ->set_status_header(403)
            ->set_output(json_encode([
                'message' => 'xyzUpload to S3 Failed'
            ]));
        }
    }

    public function paginate($page)
	{
        $this->load->library('pagination');
        $this->load->model('image_model');
        include_once __DIR__ . '/../../view_models/Image_asset_paginate_view_model.php';
        $where = [];
        $this->_data['view_model'] = new Image_asset_paginate_view_model(
            $this->image_model,
            $this->pagination,
            '/v1/api/assets/0');

        $this->_data['view_model']->set_heading('Images');

        $this->_data['view_model']->set_total_rows($this->image_model->count($where));

        $this->_data['view_model']->set_per_page(10);
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->image_model->get_paginated(
            $this->_data['view_model']->get_page(),
            $this->_data['view_model']->get_per_page(),
            $where));

        return $this->success($this->_data['view_model']->to_json(), 200);
	}

    public function file_import ($model)
    {
        $model_name = $model . '_model';
        $this->load->library('mime_service');
        $this->load->library('csv_import_service');
        $this->load->model($model_name);

        $this->csv_import_service->set_model($this->$model_name, $model);

       /* if  ($this->csv_import_service->csv_file_exist($_FILES))
        {
            return $this->output->set_content_type('application/json')
                ->set_status_header(403)
                ->set_output(json_encode([
                    'message' => 'xyzUpload CSV File missing'
                ]));
        }*/

        $file = $_FILES['file'];
        $size = $file['size'];
        $path = $file['tmp_name'];
        $type = $file['type'];
        $extension = $this->mime_service->get_extension($type);
        //$extension = ucfirst(str_replace('.', '', $this->mime_service->get_extension($type)));
        $save_as = FCPATH . 'uploads/' . $file["name"];

        if ($size > $this->config->item('upload_byte_size_limit'))
        {
            return $this->output->set_content_type('application/json')
            ->set_status_header(403)
            ->set_output(json_encode([
                'message' => 'xyzUpload file size too big'
            ]));
        }


        $save_as = FCPATH . 'uploads/temp' . $extension;

        if ($size > $this->config->item('upload_byte_size_limit'))
        {
            return $this->output->set_content_type('application/json')
            ->set_status_header(403)
            ->set_output(json_encode([
                'message' => 'xyzUpload file size too big'
            ]));
        }

        if (move_uploaded_file($path, $save_as))
        {
            $data =  $this->csv_import_service->_import_data( $save_as );

            if($data)
            {
                unlink($save_as);
                return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'status' => TRUE
                ]));
            }
        }

        return $this->output->set_content_type('application/json')
        ->set_status_header(403)
        ->set_output(json_encode([
            'message' => 'xyzGenerating SQL worked but insert error to the database'
        ]));
    }

    public function preview_csv()
    {
        $this->load->library('mime_service');
        $this->load->library('csv_import_service');

        $file = $_FILES['file'];
        $size = $file['size'];
        $path = $file['tmp_name'];
        $type = $file['type'];
        $save_as = FCPATH . 'uploads/' . $file["name"];

        if ($size > $this->config->item('upload_byte_size_limit'))
        {
            return $this->output->set_content_type('application/json')
            ->set_status_header(403)
            ->set_output(json_encode([
                'message' => 'xyzUpload file size too big'
            ]));
        }

        if (move_uploaded_file($path, $save_as))
        {
            $data =  $this->csv_import_service->_get_file_data( $save_as );
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'message' => 'xyzFile loaded',
                    'data' => $data,
                    'preview' => TRUE
            ]));
        }



    }

    function compressImage($source, $destination, $quality = 70) { 
        $imgInfo = getimagesize($source); 
        $mime = $imgInfo['mime']; 
         
        switch($mime){ 
            case 'image/jpeg': 
                $image = imagecreatefromjpeg($source); 
               imagejpeg($image, $destination, $quality);
                break; 
            case 'image/png': 
                $image = imagecreatefrompng($source); 
                imagepng($image, $destination, $quality);
                break; 
            case 'image/gif': 
                $image = imagecreatefromgif($source); 
                imagegif($image, $destination, $quality);
                break; 
            default: 
                $image = imagecreatefromjpeg($source); 
               imagejpeg($image, $destination, $quality);
        } 
         
        return $destination; 
    } 

    /**
     * Debug Controller to error_log and turn off in production
     *
     * @param mixed $data
     * @return void
     */
    public function dl($key, $data)
    {
        if (ENVIRONMENT == 'development')
        {
            error_log($key . ' CONTROLLER : <pre>' . print_r($data, TRUE) . '</pre>');
        }
    }

    /**
     * Debug json Controller to error_log and turn off in production
     *
     * @param mixed $data
     * @return void
     */
    public function dj($key, $data)
    {
        if (ENVIRONMENT == 'development')
        {
            error_log($key . ' CONTROLLER : ' . json_encode($data));
        }
    }

    public function get_session()
    {
        if (!$this->_test_mode)
        {
            return $_SESSION;
        }

        $session = $this->config->item('session_test');

        if (!$session)
        {
            $session = [];
        }

        return $session;
    }

    /**
     * Success API Call
     *
     * @return string
     */
    public function success($success)
    {
        $success['code'] = 200;
        $success['success'] = TRUE;
        return $this->output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($success));
    }
}