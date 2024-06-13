	public function index()
	{
		$this->load->library('pagination');
		$session = $this->get_session();
    include_once __DIR__ . '/../../view_models/{{{uc_name}}}_{{{portal}}}_list_view_model.php';
    $this->_data['view_model'] = new {{{uc_name}}}_{{{portal}}}_list_view_model($this->{{{model}}}_model);
		$this->_data['view_model']->set_list($this->{{{model}}}_model->get_all({{{all_records}}}));
		$this->_data['view_model']->set_heading('{{{page_name}}}');{{{method_list}}}
    return $this->render('{{{uc_portal}}}/{{{uc_name}}}', $this->_data);
	}