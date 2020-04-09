	public function delete($id)
	{
        $model = $this->{{{model}}}_model->get($id);

		if (!$model)
		{
            return $this->_render_custom_error([
                'error' => 'xyzError'
            ]);
        }

        $result = $this->{{{model}}}_model->real_delete($id);

        if ($result)
        {
            {{{method_delete_success}}}
            return $this->success([], 200);
        }

        return $this->_render_custom_error([
            'error' => 'xyzError'
        ]);
	}