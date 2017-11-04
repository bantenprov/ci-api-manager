<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delete_key extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('key_model', 'key');
	}
	public function index($id = NULL){
		if (!$this->ion_auth->logged_in()){
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		if($id){
			if($this->key->delete($id)){
				$this->session->set_flashdata('success', 'API Key was deleted');
			} else {
				$this->session->set_flashdata('error', 'API Key failed to delete. Try again!');
			}
		} else {
			$this->session->set_flashdata('error', 'Unauthorized Access!');
		}
		redirect('create', 'refresh');
	}
}
