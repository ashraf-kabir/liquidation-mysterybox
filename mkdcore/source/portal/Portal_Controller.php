<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * {{{ucname}}} Controller
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{ucname}}}_controller extends {{{subclass_prefix}}}Controller
{

    public $_page_name ='dashboard';

    public $_valid_roles = [{{{valid_roles}}}];

    public function __construct()
    {
        parent::__construct();

        $this->_data['page_name'] = $this->_page_name;
        $this->_data['setting'] = $this->_setting;
        $this->_data['layout_clean_mode'] = FALSE;
        $this->_run_middlewares();
        $layout_mode = $this->input->get('layout_clean_mode', TRUE);
        if (isset($layout_mode) && $layout_mode === '1')
        {
            $this->_data['layout_clean_mode'] = TRUE;
        }
    }

    protected function _middleware()
    {
        return [
            {{{middleware}}}
        ];
    }

    public function render($template, $data)
    {
        return (!$this->_test_mode) ? $this->_render($template, $data) : $this->_render_test($template, $data);
    }

    protected function _render_test($template, $data)
    {
        return [
            'header' => $this->load->view('Layout/{{{ucname}}}Header', $data, TRUE),
            'body' => $this->load->view($template, $data, TRUE),
            'footer' => $this->load->view('Layout/{{{ucname}}}Footer', $data, TRUE),
            'data' => $data,
        ];
    }

    protected function _render($template, $data)
    {
        $this->load->view('Layout/{{{ucname}}}Header', $data);
        $this->load->view($template, $data);
        $this->load->view('Layout/{{{ucname}}}Footer');
    }
}