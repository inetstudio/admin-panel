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
    public function activate(UsersActivationsService $usersActivationsService, string $token = ''): View
    {
        $seoService = app()->make('SEOService');

        $activation = $usersActivationsService->getActivationByToken($token);

        if ($activation !== null) {
            $user = User::find($activation->user_id);
            $user->activated = 1;
            $user->save();

            $usersActivationsService->deleteActivation($token);

            $this->updateRelatedItems($user);

            $activation = [
                'success' => true,
                'message' => trans('admin::activation.activationSuccess'),
            ];
        } else {
            $activation = [
                'success' => false,
                'message' => trans('admin::activation.activationFail'),
            ];
        }

        return view('admin::front.auth.activate', [
            'SEO' => $seoService->getTags(null),
            'activation' => $activation,
        ]);
    }

    /**
     * Присваиваем пользователю связанные с его почтой сущности.
     *
     * @param User $user
     */
    private function updateRelatedItems(User $user): void
    {
        $items = config('admin.relatedItems');

        if ($items) {
            foreach ($items as $itemClass) {
                if (class_exists($itemClass)) {
                    $model = new $itemClass();

                    $model::where('email', $user->email)->where('user_id', 0)->update([
                        'user_id' => $user->id,
                        'name' => $user->name,
                    ]);
                }
            }
        }
    }
}
