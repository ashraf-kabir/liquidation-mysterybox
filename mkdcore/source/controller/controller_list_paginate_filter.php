	public function index($page)
	{
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/{{{uc_name}}}_{{{portal}}}_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new {{{uc_name}}}_{{{portal}}}_list_paginate_view_model(
            $this->{{{model}}}_model,
            $this->pagination,
            '/{{{portal}}}{{{route}}}');
        $this->_data['view_model']->set_heading('{{{page_name}}}');
        {{{list_paginate_filter_post}}}
        $where = [
            {{{list_paginate_filter_where}}}
            {{{all_records}}}
        ];

        $this->_data['view_model']->set_total_rows($this->{{{model}}}_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/{{{portal}}}{{{route}}}');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->{{{model}}}_model->{{{paginate}}}(
            $this->_data['view_model']->get_page(),
            $this->_data['view_model']->get_per_page(),
            $where,
            $order_by,
            $direction));{{{method_list}}}

        if ($format != 'view')
        {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->_data['view_model']->to_json()));
        }

        return $this->render('{{{uc_portal}}}/{{{uc_name}}}', $this->_data);
	}