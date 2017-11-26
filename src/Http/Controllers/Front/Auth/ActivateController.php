<?php

namespace InetStudio\AdminPanel\Http\Controllers\Front\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use InetStudio\AdminPanel\Services\Front\Auth\UsersActivationsService;

class ActivateController extends Controller
{

    public function activate(UsersActivationsService $usersActivationsService, $token)
    {
        $activation = $usersActivationsService->getActivationByToken($token);

        if ($activation === null) {
            return;
        }

        $user = User::find($activation->user_id);
        $user->activated = true;
        $user->save();

        $usersActivationsService->deleteActivation($token);

        return 'test';
    }
}
