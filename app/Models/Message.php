<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {

	protected $fillable = ['name','email','subject','message'];

	public static function rules ($merge=[]) {
		return array_merge(
			[
				'name'      => 'required|max:100',
				'email'     => 'required|email|max:100',
				'subject'   => 'required|max:100',
				'message'   => 'required',
			], 
        $merge);
	}
	
	public static function niceNames () {
		return [
			'name'     => '<b>Name</b>',
			'email'    => '<b>Email</b>',
			'subject'  => '<b>Subject</b>',
			'message'  => '<b>Message</b>',
		];
	}
	
	public function scopeListMessage()
	{
		return $this->get();
	}
	
	public function scopeCountNewMessage()
	{
		return $this->where('seen',0)->get();
	}

	public function scopeUpdateSeen($query,$id)
	{
		return $query->where('id',$id)->update(['seen'=>1]);
	}

	public function scopeDetailMessage($query,$id)
	{
		return $query->find($id)->toArray();
	}

}