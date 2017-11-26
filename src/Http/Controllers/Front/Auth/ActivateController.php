<?php

namespace InetStudio\AdminPanel\Http\Controllers\Front\Auth;

use App\User;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use InetStudio\AdminPanel\Services\Front\Auth\UsersActivationsService;

class ActivateController extends Controller
{
    /**
     * Активируем аккаунт.
     *
     * @param UsersActivationsService $usersActivationsService
     * @param string $token
     * @return View
     */
    public function activate(UsersActivationsService $usersActivationsService, string $token): View
    {
        $activation = $usersActivationsService->getActivationByToken($token);

        if ($activation !== null) {
            $user = User::find($activation->user_id);
            $user->activated = true;
            $user->save();

            $usersActivationsService->deleteActivation($token);

            $status = trans('admin::activation.activationSuccess');
        } else {
            $status = trans('admin::activation.activationFail');
        }

        return view('admin::front.auth.activate', [
            'activationStatus' => $status,
        ]);
    }
}
