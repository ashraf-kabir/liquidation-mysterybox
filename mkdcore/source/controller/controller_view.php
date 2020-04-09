	public function view($id)
	{
        $model = $this->{{{model}}}_model->get($id);

		if (!$model)
		{
			$this->error('xyzError');
			return redirect('/{{{portal}}}{{{route}}}');
		}

{{{all_records}}}
        include_once __DIR__ . '/../../view_models/{{{uc_name}}}_{{{portal}}}_view_view_model.php';
		$this->_data['view_model'] = new {{{uc_name}}}_{{{portal}}}_view_view_model($this->{{{model}}}_model);
		$this->_data['view_model']->set_heading('{{{page_name}}}');
        $this->_data['view_model']->set_model($model);{{{method_view}}}{{{method_view_success}}}
        {{{dynamic_mapping_view}}}
		return $this->render('{{{uc_portal}}}/{{{uc_name}}}View', $this->_data);
	}