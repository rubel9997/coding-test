<?php


namespace App\Services;


use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRegisterService
{

    public function userRegister(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'account_type' => $data['account_type'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }

}
