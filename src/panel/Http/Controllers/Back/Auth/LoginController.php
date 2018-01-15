<?php

namespace InetStudio\AdminPanel\Http\Controllers\Back\Auth;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Куда редиректим пользователя после авторизации.
     *
     * @var string
     */
    protected $redirectTo = '/back/';

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
        $this->middleware('back.guest', [
            'except' => 'logout',
        ]);
    }

    /**
     * Отображаем страницу авторизации.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm(): View
    {
        return view('admin::back.auth.login');
    }

    /**
     * Возвращаем поле, по которому происходит авторизация.
     *
     * @return string
     */
    public function username()
    {
        $login = request()->input('login');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        request()->merge([$field => $login]);

        return $field;
    }
}
