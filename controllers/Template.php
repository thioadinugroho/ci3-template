<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	// public function index()
	// {
	// 	$this->load->library('form_validation');
	// 	$val = $this->form_validation;
	// 	$val->set_rules('username','Username','trim|required|max_length[50]');
	// 	if(!$val->run()) {
	// 		$return['status_code'] = '101';
	// 		$return['status'] = 'Invalid Data';
	// 		$return['error'] = validation_errors();
	// 	} else {

	// 		$return['status_code'] = '000';
	// 		$return['status_code'] = '000';
	// 		$return['session.'] = 'Success';
	// 	}
	// }

	//---------------------------------------------------------------------

	/**
	 * Index Page for this controller.
	 *
	 */
	public function index()
	{
		$this->benchmark->mark('code_start');
		$this->output->enable_profiler(false);
		header('Content-Type:application/json');
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('username','Username','trim|required|max_length[50]');
		$val->set_rules('password','Password','trim|required|max_length[50]');
		if(!$val->run()) {
			$return['status_code'] = '001';
			$return['status'] = get_status_code($return['status_code']);
			$return['error'] = validation_errors();
		} else {

			
			
		}

		echo json_encode($return);

		$this->benchmark->mark('code_end');
		$elapstime = $this->benchmark->elapsed_time('code_start', 'code_end');
		writelog(__CLASS__." | ".__FUNCTION__,'bmm',$elapstime);  
	}

	//---------------------------------------------------------------------

	/**
	 * Index Page for this controller.
	 *
	 */
	public function tambah()
	{
		$this->benchmark->mark('code_start');
		$this->output->enable_profiler(false);
		header('Content-Type:application/json');
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('session','Session','trim|required|exact_length[12]');
        $val->set_rules('nama','Nama','trim|required|max_length[100]');
        
		if(!$val->run()) {
			$return['status_code'] = '001';
			$return['status'] = get_status_code($return['status_code']);
			$return['error'] = validation_errors();
		} else {

			$sess = $this->input->post('session');
			$this->load->model('User_sessions_model');
			$usm = $this->User_sessions_model;
			$usm->set_sess_id($sess);
			$usm->set_expired(date('U')); 
			
			if(!$usm->is_valid()) {
				$return['status_code'] = '003';
				$return['status'] = get_status_code($return['status_code']);
				$return['session'] = $sess;	
			} else {

                $this->load->model('Template_model');
                $cab = $this->Template_model;
                $cab->set_nama($this->input->post('nama'));
                
                if(!$cab->insert()) {
                    $return['error'] = ($cab->get_error() == '')?validation_errors():$cab->get_error();
                } else {
                    $return['status_code'] = '000';
                    $return['status'] = get_status_code($return['status_code']);
                    $return['id'] = $cab->get_id();
                }
			}

			
		}

		echo json_encode($return);

		$this->benchmark->mark('code_end');
		$elapstime = $this->benchmark->elapsed_time('code_start', 'code_end');
		writelog(__CLASS__." | ".__FUNCTION__,'bmm',$elapstime);  
	}

	//---------------------------------------------------------------------

	/**
	 * Index Page for this controller.
	 *
	 */
	public function list()
	{
		$this->benchmark->mark('code_start');
		$this->output->enable_profiler(false);
		header('Content-Type:application/json');
		$this->load->library('get_validation');
		$val = $this->get_validation;
		$val->set_rules('session','Session','trim|required|exact_length[12]');
		$val->set_rules('p','Halaman','trim|required|max_length[12]');
        
		if(!$val->run()) {
			$return['status_code'] = '001';
			$return['status'] = get_status_code($return['status_code']);
			$return['error'] = validation_errors();
		} else {

			$sess = $this->input->get('session');
			$this->load->model('User_sessions_model');
			$usm = $this->User_sessions_model;
			$usm->set_sess_id($sess);
			$usm->set_expired(date('U')); 
			
			if(!$usm->is_valid()) {
				$return['status_code'] = '003';
				$return['status'] = get_status_code($return['status_code']);
				$return['session'] = $sess;	
			} else {

				$p = $this->input->get('p');

                $this->load->model('Template_model');
				$cab = $this->Template_model;
				$limit_min = $p * 50 - 50;

				$cab->set_limit_min($limit_min);
				$list = $cab->get_list();
				
				$return['status_code'] = '000';
				$return['status'] = get_status_code($return['status_code']);
				$return['list'] = $list;
			}

			
		}

		echo json_encode($return);

		$this->benchmark->mark('code_end');
		$elapstime = $this->benchmark->elapsed_time('code_start', 'code_end');
		writelog(__CLASS__." | ".__FUNCTION__,'bmm',$elapstime);  
	}

	//---------------------------------------------------------------------

	/**
	 * Index Page for this controller.
	 *
	 */
	public function detail()
	{
		$this->benchmark->mark('code_start');
		$this->output->enable_profiler(false);
		header('Content-Type:application/json');
		$this->load->library('get_validation');
		$val = $this->get_validation;
		$val->set_rules('session','Session','trim|required|exact_length[12]');
		$val->set_rules('id','ID','trim|required|numeric|max_length[12]');
        
		if(!$val->run()) {
			$return['status_code'] = '001';
			$return['status'] = get_status_code($return['status_code']);
			$return['error'] = validation_errors();
		} else {

			$sess = $this->input->get('session');
			$this->load->model('User_sessions_model');
			$usm = $this->User_sessions_model;
			$usm->set_sess_id($sess);
			$usm->set_expired(date('U')); 
			
			if(!$usm->is_valid()) {
				$return['status_code'] = '003';
				$return['status'] = get_status_code($return['status_code']);
				$return['session'] = $sess;	
			} else {

				$p = $this->input->get('id');
			
                $this->load->model('Template_model');
				$cab = $this->Template_model;

				$cab->set_id((int)$p);
				$detail = $cab->detail();
				
				$return['status_code'] = '000';
				$return['status'] = get_status_code($return['status_code']);
				$return['detail'] = $detail;
			}

			
		}

		echo json_encode($return);

		$this->benchmark->mark('code_end');
		$elapstime = $this->benchmark->elapsed_time('code_start', 'code_end');
		writelog(__CLASS__." | ".__FUNCTION__,'bmm',$elapstime);  
	}

	//---------------------------------------------------------------------

	/**
	 * Index Page for this controller.
	 *
	 */
	public function update()
	{
		$this->benchmark->mark('code_start');
		$this->output->enable_profiler(false);
		header('Content-Type:application/json');
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('session','Session','trim|required|exact_length[12]');
        $val->set_rules('nama','Nama','trim|required|max_length[100]');
        
		if(!$val->run()) {
			$return['status_code'] = '001';
			$return['status'] = get_status_code($return['status_code']);
			$return['error'] = validation_errors();
		} else {

			$sess = $this->input->get('session');
			$this->load->model('User_sessions_model');
			$usm = $this->User_sessions_model;
			$usm->set_sess_id($sess);
			$usm->set_expired(date('U')); 
			
			if(!$usm->is_valid()) {
				$return['status_code'] = '003';
				$return['status'] = get_status_code($return['status_code']);
				$return['session'] = $sess;	
			} else {

                $this->load->model('Template_model');
                $cab = $this->Template_model;
                $cab->set_nama($this->input->post('nama'));
                
                if(!$cab->update()) {
                    $return['error'] = ($cab->get_error() == '')?validation_errors():$cab->get_error();
                } else {
                    $return['status_code'] = '000';
                    $return['status'] = get_status_code($return['status_code']);
                }
			}

			
		}

		echo json_encode($return);

		$this->benchmark->mark('code_end');
		$elapstime = $this->benchmark->elapsed_time('code_start', 'code_end');
		writelog(__CLASS__." | ".__FUNCTION__,'bmm',$elapstime);  
	}

	//---------------------------------------------------------------------

	/**
	 * Index Page for this controller.
	 *
	 */
	public function hapus()
	{
		$this->benchmark->mark('code_start');
		$this->output->enable_profiler(false);
		header('Content-Type:application/json');
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('session','Session','trim|required|exact_length[12]');
		$val->set_rules('id','ID','trim|required|numeric|max_length[12]');
        
		if(!$val->run()) {
			$return['status_code'] = '001';
			$return['status'] = get_status_code($return['status_code']);
			$return['error'] = validation_errors();
		} else {

			$sess = $this->input->get('session');
			$this->load->model('User_sessions_model');
			$usm = $this->User_sessions_model;
			$usm->set_sess_id($sess);
			$usm->set_expired(date('U')); 
			
			if(!$usm->is_valid()) {
				$return['status_code'] = '003';
				$return['status'] = get_status_code($return['status_code']);
				$return['session'] = $sess;	
			} else {

                $this->load->model('Template_model');
				$cab = $this->Template_model;

				$cab->set_id($this->input->post('id'));
				$list = $cab->remove();
				
				$return['status_code'] = '000';
				$return['status'] = get_status_code($return['status_code']);
			}

			
		}

		echo json_encode($return);

		$this->benchmark->mark('code_end');
		$elapstime = $this->benchmark->elapsed_time('code_start', 'code_end');
		writelog(__CLASS__." | ".__FUNCTION__,'bmm',$elapstime);  
	}

	//---------------------------------------------------------------------
}
