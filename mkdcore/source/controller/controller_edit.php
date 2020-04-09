	public function edit($id)
	{
        $model = $this->{{{model}}}_model->get($id);

		if (!$model)
		{
			$this->error('xyzError');
			return redirect('/{{{portal}}}{{{route}}}');
        }

        include_once __DIR__ . '/../../view_models/{{{uc_name}}}_{{{portal}}}_edit_view_model.php';
        $this->form_validation = $this->{{{model}}}_model->set_form_validation(
        $this->form_validation, $this->{{{model}}}_model->get_all_edit_validation_rule());
        $this->_data['view_model'] = new {{{uc_name}}}_{{{portal}}}_edit_view_model($this->{{{model}}}_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('{{{page_name}}}');
        {{{dynamic_mapping_edit}}}
        {{{method_edit_pre}}}
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('{{{uc_portal}}}/{{{uc_name}}}Edit', $this->_data);
        }

        {{{input_post_edit}}}
        $result = $this->{{{model}}}_model->edit([
            {{{model_array_value}}}{{{method_edit}}}
        ], $id);

        if ($result)
        {
            {{{method_edit_success}}}
            {{{activity_log}}}
            return $this->redirect('/{{{portal}}}{{{route}}}', 'refresh');
        }

        $this->_data['error'] = 'xyzError';
        return $this->render('{{{uc_portal}}}/{{{uc_name}}}Edit', $this->_data);
	}