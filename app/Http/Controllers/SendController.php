<?php namespace App\Http\Controllers;

use App\Models\Message;
use App\Http\Controllers\Controller;
use \Validator, \Input, \Request, \DB;

class SendController extends Controller {

	public function postCreate(){

		 $validator = Validator::make(Input::all(), Message::rules(),[],Message::niceNames());
		 
		 	$arr['name'] = Input::get('name');
			$arr['email'] = Input::get('email');
			$arr['subject'] = Input::get('subject');
			$arr['message'] = Input::get('message');

		    if ($validator->fails()) {
				
				$error = $validator->errors();
				$arr['success'] = false;
				$arr['notif'] = '<div class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 alert alert-danger alert-dismissable"><i class="fa fa-ban"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' .implode('', $error->all(':message<br />')). '</div>';
				
				
		    } else {
	
		    	$message=Request::all();
				Message::create($message);
				$id = DB::getPdo()->lastInsertId();  
				$arr = Message::DetailMessage($id);
		    	$arr['new_count_message'] = count(Message::CountNewMessage());
				$arr['success'] = true;
				$arr['notif'] = '<div class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 alert alert-success" role="alert"> <i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Message sent ...</div>';

		    }

		    return json_encode($arr);
	
	}
	
}
