<?php
namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use \Firebase\JWT\JWT;
use App\Controllers\BaseController; 
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control");

class User extends ResourceController
{
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();
    }
	use ResponseTrait;
    public function createUser(){
		$rules = [
		'name' => 'required|min_length[3]|max_length[20]',
		'email' => 'required|valid_email|is_unique[tbl_users.email]',
		'phone_no' => 'required|is_unique[tbl_users.phone_no]',
		'password' => 'required|min_length[4]', 
		];

			if(! $this->validate($rules)){
				return $this->fail($this->validator->getErrors());
		}else{
			$userModel = new UserModel(); 
			$data = [
				"name" => $this->request->getVar("name"),
				"email" => $this->request->getVar("email"),
				"phone_no" => $this->request->getVar("phone_no"),
				"password" => password_hash($this->request->getVar("password"), PASSWORD_DEFAULT),
			];

			if ($userModel->insert($data)) {

				$response = [
					'status' => 200,
					"response" => TRUE,
					'messages' => 'User created',
				];
			} else {

				$response = [
					'status' => 404,
					"response" => FALSE,
					'messages' => 'Failed to create',
				];
			}

			return $this->respondCreated($response);
		}
    }
    private function getKey(){
        return "my_application_secret";
    }
    public function validateUser(){
        $userModel = new UserModel();

        $userdata = $userModel->where("email", $this->request->getVar("email"))->first();
		 
        if (!empty($userdata)) {
            if (password_verify($this->request->getVar("password"), $userdata['password'])) {
                $key = $this->getKey();
                $iat = time(); 
                $exp = $iat + 3600;
                $payload = array( 
                    "iat" => $iat, 
                    "exp" => $exp,
                    "data" => $userdata['id'].$userdata['mobile'],
                );
                $token = JWT::encode($payload, $key);
				$data = [
					"latitude" => $this->request->getVar("latitude"),
					"longitude" => $this->request->getVar("longitude"), 
				];
				//print_r($userdata['id']);die;
				$userModel->update($userdata['id'], $data);
                $response = [
                    'status' => 200,
                    'response' => TRUE,
                    'messages' => 'User logged In successfully',
                    'token' => $token, 
					'data'=>$userdata 
				];
                $this->session->set($response);
                return $this->respondCreated($response);
            } else {

                $response = [
                    'status' => 500,
                    'response' => FALSE,
                    'messages' => 'Incorrect details'
                ];
                return $this->respondCreated($response);
            }
        } else {
            $response = [
                'status' => 500,
                'response' => FALSE,
                'messages' => 'User not found'
            ];
            return $this->respondCreated($response);
        }
		
    }
    public function userDetails(){
        $key = $this->getKey();
        $authHeader = $this->request->getHeader("Authorization");
        $authHeader = $authHeader->getValue(); 
        $token = $authHeader;  
        $sessiontoken = $this->session->get("token");
        if ($authHeader == $sessiontoken) { 
        try {
            $decoded = JWT::decode($token, $key, array("HS256"));
            $responseData=$this->session->get($response);
            if ($decoded){
               // print_r($responseData['data']);exit;
                $newarray=[
                    'name'=>$responseData['data']['id'],
                    'email'=>$responseData['data']['email'],
                    'Phone'=>$responseData['data']['phone_no'],
                    'latitude'=>$responseData['data']['latitude'],
                    'longitude'=>$responseData['data']['longitude'],
                    'CreateDate'  =>$responseData['data']['created_at']
                ];
                $response = [
                    'status' => 200, 
                    'messages' => 'User details',
                    'data' =>  $newarray
                ];
                return $this->respondCreated($response);
            }
        } catch (Exception $ex) {
            $response = [
                'status' => 401, 
                'messages' => 'Access denied '
            ];
            return $this->respondCreated($response);
        }  
        } else {
            $response = [
                'status' => 401, 
                'messages' => 'Token Not Matched!!'
            ];
            return $this->respondCreated($response);
        }
        
    }
    public  function forgetPassword(){ 
        $userModel = new UserModel();
        $userdata = $userModel->where("email", $this->request->getVar("email"))->first();
        if (!empty($userdata)) {
            $otp = mt_rand(100000, 999999); 
            $data = [
                "otp" => $otp,
                "sent_otp" =>1, 
            ];
             //$this->emailsent($data,$userdata);
            $userModel->update($userdata['id'], $data);
            $response = [
                'status' => 200,
                'response' => TRUE,
                'messages' => 'Otp Send Successfully Please Check Emial!!', 
                'data'=>$data 
            ]; 
            return $this->respondCreated($response);
        } else {
            $response = [
                        'status' => 500,
                        'response' => FALSE,
                        'messages' => 'Incorrect details'
                    ]; 
            return $this->respondCreated($response);
        }   
    }
    public function emailsent($data,$userdata){ 
        
        $message = "Please Enter  This OTP  ".$data['otp'];
        $email = \Config\Services::email();
        $email->setFrom('info@developerhub.in', 'your Title Here');
        $email->setTo($userdata['email']);
        $email->setSubject('OTP For RESET PASSWORD');
        $email->setMessage($message);//your message here
      
        //$email->setCC('another@emailHere');//CC
        //$email->setBCC('thirdEmail@emialHere');// and BCC
        //$filename = '/img/yourPhoto.jpg'; //you can use the App patch 
        //$email->attach($filename);
         
        
        //$email->printDebugger(['headers']);
        if ($email->send()) {
            $response = [
                'status' => 200,
                'response' => TRUE,
                'messages' => 'Mail Sent'
            ]; 
             return $this->respondCreated($response);
        } else {
            $response = [
                'status' => 404,
                'response' => FALSE,
                'messages' => 'Mail Not Sent'
            ]; 
            return $this->respondCreated($response);
        } 
    }

}

