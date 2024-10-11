<?php

namespace App\Actions;
use App\Models\User;
use App\DTOs\UserData;
class StoreUserAction
{
    public function execute(UserData $data): User
    {
       
        $user = User::create([
            'vaccine_center_id'=> $data->vaccine_center_id,
			'name'=> $data->name,
			'email'=> $data->email,
			'nid'=> $data->nid,
			'mobile'=> $data->mobile,
			'status'=> 'Not scheduled',
			'scheduled_date'=> 'null'
        ]);

       return $user;

        // others works go here...
    }
}