	public function add()
	{
        include_once __DIR__ . '/../../view_models/{{{uc_name}}}_{{{portal}}}_add_view_model.php';
        $session = $this->get_session();
        $this->form_validation = $this->{{{model}}}_model->set_form_validation(
        $this->form_validation, $this->{{{model}}}_model->get_all_validation_rule());
        $this->_data['view_model'] = new {{{uc_name}}}_{{{portal}}}_add_view_model($this->{{{model}}}_model);
        $this->_data['view_model']->set_heading('{{{page_name}}}');
        {{{dynamic_mapping_add}}}

		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('{{{uc_portal}}}/{{{uc_name}}}Add', $this->_data);
        }

        {{{input_post_add}}}
        $result = $this->{{{model}}}_model->create([
            {{{model_array_value}}}{{{method_add}}}
        ]);

        if ($result)
        {
            {{{method_add_success}}}
            {{{activity_log}}}
            return $this->redirect('/{{{portal}}}{{{route}}}', 'refresh');
        }

        $this->_data['error'] = 'xyzError';
        return $this->render('{{{uc_portal}}}/{{{uc_name}}}Add', $this->_data);
	}