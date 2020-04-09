	public function edit($id)
	{
        $model = $this->{{{model}}}_model->get($id);

		if (!$model)
		{
            return $this->_render_custom_error([
                'error' => 'xyzError'
            ]);
        }

        $this->form_validation = $this->{{{model}}}_model->set_form_validation(
        $this->form_validation, $this->{{{model}}}_model->get_all_edit_validation_rule());
        {{{dynamic_mapping_edit}}}
		if ($this->form_validation->run() === FALSE)
		{
			return $this->_render_validation_error();
        }

        {{{input_post_edit}}}
        $result = $this->{{{model}}}_model->edit([
            {{{model_array_value}}}{{{method_edit}}}
        ], $id);

        if ($result)
        {
            {{{method_edit_success}}}
            {{{activity_log}}}
            return $this->success([], 200);
        }

        return $this->_render_custom_error([
            'error' => 'xyzError'
        ]);
	}