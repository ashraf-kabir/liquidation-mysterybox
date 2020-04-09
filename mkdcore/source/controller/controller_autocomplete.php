	public function autocomplete($text)
	{
		if (strlen($text) < 2)
		{
			echo '[]';
			exit;
		}

		$where = [
			{{{autocomplete_statement}}}
		];

		$result = $this->{{{model}}}_model->get_paginated(0, 25, $where);

		echo json_encode($result);
		exit;
	}