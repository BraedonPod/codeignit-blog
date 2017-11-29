<?php
	class Posts extends CI_Controller{
		public function index($offset = 0){	
			// Pagination Config	
			$config['base_url'] = base_url() . 'posts/index';
			$config['total_rows'] = $this->db->count_all('posts');
			$config['per_page'] = 3;
			$config['uri_segment'] = 3;
			$config['attributes'] = array('class' => 'pagination-link');
			$config['use_page_numbers'] = TRUE;
			
			$config['full_tag_open'] = "<ul class='pagination'>";
			$config['full_tag_close'] ="</ul>";
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
			$config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
			$config['next_tag_open'] = "<li>";
			$config['next_tagl_close'] = "</li>";
			$config['prev_tag_open'] = "<li>";
			$config['prev_tagl_close'] = "</li>";
			$config['first_tag_open'] = "<li>";
			$config['first_tagl_close'] = "</li>";
			$config['last_tag_open'] = "<li>";
			$config['last_tagl_close'] = "</li>";
			// $config['next_link'] = "&raquo;";
			// $config['prev_link'] = "&laquo;";

			// Init Pagination
			$this->pagination->initialize($config);

			$data['title'] = 'Latest Posts';

			$data['posts'] = $this->post_model->get_posts(FALSE, $config['per_page'], $offset);

			$this->load->view('templates/header');
			$this->load->view('posts/index', $data);
			$this->load->view('templates/footer');
		}

		public function view($slug = NULL){
			$data['post'] = $this->post_model->get_posts($slug);
			$post_id = $data['post']['id'];
			$data['comments'] = $this->comment_model->get_comments($post_id);

			if(empty($data['post'])){
				show_404();
			}

			$data['title'] = $data['post']['title'];

			$this->load->view('templates/header');
			$this->load->view('posts/view', $data);
			$this->load->view('templates/footer');
		}

		public function create(){
			// Check login
			if(!$this->session->userdata('logged_in')){
				redirect('users/login');
			}

			$data['title'] = 'Create Post';

			$data['categories'] = $this->post_model->get_categories();

			$this->form_validation->set_rules('title', 'Title', 'required');
			$this->form_validation->set_rules('body', 'Body', 'required');

			if($this->form_validation->run() === FALSE){
				$this->load->view('templates/header');
				$this->load->view('posts/create', $data);
				$this->load->view('templates/footer');
			} else {
				$this->post_model->create_post();

				// Set message
				$this->session->set_flashdata('post_created', 'Your post has been created');

				redirect('posts');
			}
		}

		public function delete($id){
			// Check login
			if(!$this->session->userdata('logged_in')){
				redirect('users/login');
			}

			$this->post_model->delete_post($id);

			// Set message
			$this->session->set_flashdata('post_deleted', 'Your post has been deleted');

			redirect('posts');
		}

		public function edit($slug){
			// Check login
			if(!$this->session->userdata('logged_in')){
				redirect('users/login');
			}

			$data['post'] = $this->post_model->get_posts($slug);

			// Check user
			if($this->session->userdata('user_id') != $this->post_model->get_posts($slug)['user_id']){
				redirect('posts');

			}

			$data['categories'] = $this->post_model->get_categories();

			if(empty($data['post'])){
				show_404();
			}

			$data['title'] = 'Edit Post';

			$this->load->view('templates/header');
			$this->load->view('posts/edit', $data);
			$this->load->view('templates/footer');
		}

		public function update(){
			// Check login
			if(!$this->session->userdata('logged_in')){
				redirect('users/login');
			}

			$this->post_model->update_post();

			// Set message
			$this->session->set_flashdata('post_updated', 'Your post has been updated');

			redirect('posts');
		}
	}