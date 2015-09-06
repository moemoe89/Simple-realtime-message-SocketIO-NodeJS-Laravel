<?php namespace App\Http\Controllers;

use App\Models\Message;
use App\Http\Controllers\Controller;
use \Input;

class MessageController extends Controller {

	public function index(){

		$contents['ListMessage']      = Message::ListMessage();
		$contents['CountNewMessage']  = count(Message::CountNewMessage());

		return view('message',$contents);
	
	}

	public function updateSeen(){

		$id = Input::get('id');
		if($id){

			Message::UpdateSeen($id);
			$arr = Message::DetailMessage($id);
			$arr['update_count_message'] = count(Message::CountNewMessage());
			return json_encode($arr);

		}
		
	}

}
