<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->load->model('key_model', 'key');
		$this->load->model('log_model', 'access');
	}
	public function index(){
		if (!$this->ion_auth->logged_in()){
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		$data = array(
			'title' 	=> 'Create API Key',
			'user'		=> $this->ion_auth->user()->row(),
		);
		$this->load->view('create', $data);
	}
	public function api_key(){
		if (!$this->ion_auth->logged_in()){
			// redirect them to the login page
			redirect('auth/login', 'refresh');
		}
		$this->form_validation->set_rules('user_id', 'User ID', 'required');
		$this->form_validation->set_rules('domain', 'Domain', 'required');
		if ($this->form_validation->run() == true){
			$user_id = $this->input->post('user_id');
			$domain = remove_http($this->input->post('domain'));
			$data = array(
				'user_id' 			=> $user_id,
				'key' 				=> sha1($domain),
				'domain' 			=> $domain,
				'level' 			=> 0,
				'ignore_limits' 	=> 0,
				'is_private_key' 	=> 0,
				'ip_addresses' 		=> get_client_ip(),
				'date_created' 		=> date('Y-m-d H:i:s'),
			);
			$find_key = $this->key->find("domain = '$domain'");
			if($find_key){
				$this->session->set_flashdata('error', "Create API Key Failed. Domain $domain already registerd!");
			} else {
				if($this->key->insert($data)){
					$this->session->set_flashdata('success', 'Create API Key Success');
				} else {
					$this->session->set_flashdata('error', 'Create API Key Failed. Try again!');
				}
			}
			redirect('create', 'refresh');
		} else {
			$user = $this->ion_auth->user()->row();
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['domain'] = array(
				'name' 			=> 'domain',
				'id'   			=> 'domain',
				'type'			=> 'text',
				'class'			=> 'form-control',
				'placeholder' 	=> 'Your domain (without http:// or https://)',
				'value' 		=> $this->form_validation->set_value('domain'),
			);
			$this->data['title'] = 'Create API Key';
			$this->data['user_id'] = array(
				'name' 			=> 'user_id',
				'id'   			=> 'user_id',
				'type'			=> 'hidden',
				'class'			=> 'form-control',
				'value' 		=> $user->id,
			);
			$this->load->view('form', $this->data);
		}
	}
}
