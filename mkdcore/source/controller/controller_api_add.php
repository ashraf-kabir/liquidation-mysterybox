	public function add()
	{
        $this->form_validation = $this->{{{model}}}_model->set_form_validation(
        $this->form_validation, $this->{{{model}}}_model->get_all_validation_rule());
        {{{dynamic_mapping_add}}}
		if ($this->form_validation->run() === FALSE)
		{
			return $this->_render_validation_error();
        }

        {{{input_post_add}}}
        $result = $this->{{{model}}}_model->create([
            {{{model_array_value}}}{{{method_add}}}
        ]);

        if ($result)
        {
            {{{method_add_success}}}
            {{{activity_log}}}
            return $this->success([], 200);
        }

        return $this->_render_custom_error([
            'error' => 'xyzError'
        ]);
	}