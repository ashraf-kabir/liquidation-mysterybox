	public function view($id)
	{
        $model = $this->{{{model}}}_model->get($id);
		{{{dynamic_mapping_view}}}
		if (!$model)
		{
			return $this->_render_custom_error([
				'error' => 'xyzError'
			]);
		}

{{{all_records}}}
        include_once __DIR__ . '/../../view_models/{{{uc_name}}}_{{{portal}}}_view_view_model.php';
        $this->_data['view_model'] = new {{{uc_name}}}_{{{portal}}}_view_view_model($this->{{{model}}}_model);
        $this->_data['view_model']->set_model($model);{{{method_view}}}{{{method_view_success}}}
        return $this->success(['data' => $this->_data['view_model']->to_json()], 200);
	}