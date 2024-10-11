<?php

namespace App\DTOs;
use Illuminate\Http\Request;

class UserData
{
    // dto properties
    public $vaccine_center_id;
	public $name;
	public $email;
	public $nid;
	public $mobile;
	public $status;
	public $scheduled_date;
    // DTO properties and methods...
    public function __construct($vaccine_center_id,$name,$email,$nid,$mobile,$status,$scheduled_date)
    {
        $this->vaccine_center_id = $vaccine_center_id;
		$this->name = $name;
		$this->email = $email;
		$this->nid = $nid;
		$this->mobile = $mobile;
		$this->status = $status;
		$this->scheduled_date = $scheduled_date;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->vaccine_center_id,
			$request->name,
			$request->email,
			$request->nid,
			$request->mobile,
			$request->status,
			$request->scheduled_date,
        );
    }
}