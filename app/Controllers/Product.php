<?php namespace App\Controllers;


use CodeIgniter\API\ResponseTrait; 

class Product extends BaseController
{
    public function index()
	{
        $data =[
            "data1"=>"Going",
            "data2"=>"Going TO Commit"
        ];
        $this->response =$data ;
        echo json_encode($this->response);
		  
	}
}