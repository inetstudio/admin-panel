<?php

namespace InetStudio\AdminPanel\Controllers\ACL;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use InetStudio\AdminPanel\Requests\ACL\SaveUserRequest;

class UsersController extends Controller
{
    /**
     * Список сайтов с отзывами.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::pages.acl.users.index', [
            'items' => User::get(),
        ]);
    }

    /**
     * Добавление пользователя.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin::pages.acl.users.form', [
            'item' => new User(),
        ]);
    }

    /**
     * Создание пользователя.
     *
     * @param SaveUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SaveUserRequest $request)
    {
        return $this->save($request);
    }

    /**
     * Редактирование пользователя.
     *
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = null)
    {
        if (! is_null($id) && $id > 0) {
            $item = User::where('id', '=', $id)->first();
        } else {
            abort(404);
        }

        if (empty($item)) {
            abort(404);
        }

        return view('admin::pages.acl.users.form', [
            'item' => $item,
        ]);
    }

    /**
     * Обновление пользователя.
     *
     * @param SaveUserRequest $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SaveUserRequest $request, $id = null)
    {
        return $this->save($request, $id);
    }

    /**
     * Сохранение пользователя.
     *
     * @param $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    private function save($request, $id = null)
    {
        if (! is_null($id) && $id > 0) {
            $edit = true;
            $item = User::where('id', '=', $id)->first();

            if (empty($item)) {
                abort(404);
            }
        } else {
            $edit = false;
            $item = new User();
        }

        $params = [
            'name' => trim(strip_tags($request->get('name'))),
            'email' => trim(strip_tags($request->get('email'))),
        ];

        if ($request->has('password')) {
            $params['password'] = bcrypt(trim($request->get('password')));
        }

        $item->fill($params);
        $item->save();

        $item->syncRoles($request->get('roles_id'));
        $item->syncPermissions($request->get('permissions_id'));

        $action = ($edit) ? 'отредактирован' : 'создан';
        Session::flash('success', 'Пользователь «'.$item->name.'» успешно '.$action);

        return redirect()->to(route('back.acl.users.edit', $item->fresh()->id));
    }

    /**
     * Удаление пользователя.
     *
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id = null)
    {
        if (! is_null($id) && $id > 0) {
            $item = User::where('id', '=', $id)->first();
        } else {
            return response()->json([
                'success' => false,
            ]);
        }

        if (empty($item)) {
            return response()->json([
                'success' => false,
            ]);
        }

        $item->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}