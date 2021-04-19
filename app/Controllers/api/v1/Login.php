<?php namespace App\Controllers\api\v1;
  use App\Controllers\BaseController; 
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class Login extends BaseController
{
	use ResponseTrait;

	public function index()
	{
	   return view('test');
	}
	public function login(){
		helper('url'); 
		return $this->respondCreated('hello');
	}
	public function register(){
		helper('form');
		$data = [];
		if($this->request->getMethod() != 'post')
			return $this->fail('Only post request is allowed');
		$rules = [
			'firstname' => 'required|min_length[3]|max_length[20]',
			'lastname' => 'required|min_length[3]|max_length[20]',
			'email' => 'required|valid_email|is_unique[users.email]',
			'password' => 'required|min_length[8]',
			'password_confirm' => 'matches[password]',
		];
		if(! $this->validate($rules)){
			return $this->fail($this->validator->getErrors());
		}else{
			$model = new UserModel();
			$data = [
			'firstname' => $this->request->getVar('firstname'),
			'lastname' => $this->request->getVar('lastname'),
			'email' => $this->request->getVar('email'),
			'password' => $this->request->getVar('password'),
			];
			$user_id = $model->insert($data);
			$data['id'] = $user_id;
			unset($data['password']);
			return $this->respondCreated($data);
		}

	}



}
