<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once '{{{ucportal}}}_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/

/**
 * {{{ucportal}}} Setting Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{ucportal}}}_setting_controller extends {{{portal}}}_controller
{
    protected $_model_file = 'setting_model';
    public $_page_name = 'Settings';

    public function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
        $this->_data['list'] = $this->_to_key_pair($this->setting_model->get_all());
        return $this->render('{{{ucportal}}}/Setting', $this->_data);
	}

	public function edit($id)
	{
        $model = $this->setting_model->get($id);

		if (!$model)
		{
            return $this->_render_custom_error([
                'error' => 'xyzError'
            ]);
        }

        $this->form_validation = $this->setting_model->set_form_validation(
        $this->form_validation, $this->setting_model->get_all_edit_validation_rule());

		if ($this->form_validation->run() === FALSE)
		{
			return $this->_render_validation_error();
        }

		$value = $this->input->post('value');

        $result = $this->setting_model->edit([
			'value' => $value
        ], $id);

        if ($result)
        {
            $settings = $this->_to_key_pair($this->setting_model->get_all());
            file_put_contents(__DIR__ . '/../../config/setting.php', $this->_cache_settings($settings));
            return $this->success([], 200);
        }

        return $this->_render_custom_error([
            'error' => 'xyzError'
        ]);
	}

    private function _to_key_pair ($data)
    {
        $result = [];
        foreach ($data as $key => $value)
        {
            $result[$value->key] = $value;
        }
        return $result;
    }
    /**
     * User token invalid
     *
     * @return string
     */
    public function unauthorize_error_message()
    {
        $this->api_render([
            'code' => 401,
            'success' => FALSE,
            'message' => 'xyzinvalid credentials'
        ], 401);
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
        $this->api_render($success, 200);
    }

    /**
     * Invalid form input
     *
     * @return string
     */
    protected function _render_validation_error ()
	{
        $data = [
            'code' => 403,
            'success' => FALSE,
            'error' => $this->form_validation->error_array()
        ];
        return $this->api_render($data, 403);
    }

    /**
     * Render Custom Error
     *
     * @return string
     */
    protected function _render_custom_error ($errors)
	{
        $data = [
            'code' => 403,
            'success' => FALSE,
            'error' => $errors
        ];
        return $this->api_render($data, 403);
    }

    public function api_render ($data, $code)
    {
        return $this->output->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output(json_encode($data));
    }

    private function _cache_settings ($settings)
    {
        $code = "<?php defined('BASEPATH') OR exit('No direct script access allowed');\n";
        $code .= "\t\$config['setting'] = array(\n";

        foreach ($settings as $key => $value)
        {
            $code .="\t'{$key}' => '{$value->value}',\n";
        }

        $code .= ");\n";
        return $code;
    }
}