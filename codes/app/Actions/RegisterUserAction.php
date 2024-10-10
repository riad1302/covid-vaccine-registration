<?php

namespace App\Actions;

use App\Jobs\RegisterUserJob;
use App\Models\User;
use App\Services\RegisterUserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterUserAction
{
    public function execute(array $request): bool
    {
        try {
            $user = User::create([
                'vaccine_center_id' => $request['vaccine_center_id'],
                'name' => $request['name'],
                'email' => $request['email'],
                'mobile_number' => $request['mobile_number'],
                'nid' => $request['nid'],
                'password' => Hash::make($request['password']),
            ]);
            //app(RegisterUserService::class)->handle($user);
            RegisterUserJob::dispatch($user);

            return true;
        } catch (\Throwable $th) {
            Log::error('User registration failed: '.$th->getMessage(), ['exception' => $th]);

            return false;
        }
    }
}
