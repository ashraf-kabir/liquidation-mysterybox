	public function delete($id)
	{
      $model = $this->{{{model}}}_model->get($id);
      $session = $this->get_session();
      if (!$model)
      {
          $this->error('xyzError');
          return redirect('/{{{portal}}}{{{route}}}');
      }

      $result = $this->{{{model}}}_model->delete($id);

      if ($result)
      {
          {{{method_delete_success}}}
          return $this->redirect('/{{{portal}}}{{{route}}}', 'refresh');
      }

      $this->error('xyzError');
      return redirect('/{{{portal}}}{{{route}}}');
	}