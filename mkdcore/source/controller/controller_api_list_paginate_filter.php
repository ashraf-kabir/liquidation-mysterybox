	public function index($page)
	{
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/{{{uc_name}}}_{{{portal}}}_list_paginate_view_model.php';

        $this->_data['view_model'] = new {{{uc_name}}}_{{{portal}}}_list_paginate_view_model(
            $this->{{{model}}}_model,
            $this->pagination,
            '/{{{portal}}}{{{route}}}');
        $this->_data['view_model']->set_heading('{{{page_name}}}');
        {{{list_paginate_filter_post}}}
        $where = [
            {{{list_paginate_filter_where}}}
        ];

        $this->_data['view_model']->set_total_rows($this->{{{model}}}_model->count($where));

        $this->_data['view_model']->set_per_page(10);
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->{{{model}}}_model->{{{paginate}}}(
            $this->_data['view_model']->get_page(),
            $this->_data['view_model']->get_per_page(),
            $where));{{{method_list}}}
        return $this->success($this->_data['view_model']->to_json(), 200);
	}