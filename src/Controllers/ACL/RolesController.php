<?php

namespace InetStudio\AdminPanel\Controllers\ACL;

use App\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use InetStudio\AdminPanel\Requests\ACL\SaveRoleRequest;

class RolesController extends Controller
{
    /**
     * Список ролей.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::pages.acl.roles.index', [
            'items' => Role::get(),
        ]);
    }

    /**
     * Добавление роли.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin::pages.acl.roles.form', [
            'item' => new Role(),
        ]);
    }

    /**
     * Создание роли.
     *
     * @param SaveRoleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SaveRoleRequest $request)
    {
        return $this->save($request);
    }

    /**
     * Редактирование роли.
     *
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = null)
    {
        if (! is_null($id) && $id > 0) {
            $item = Role::where('id', '=', $id)->first();
        } else {
            abort(404);
        }

        if (empty($item)) {
            abort(404);
        }

        return view('admin::pages.acl.roles.form', [
            'item' => $item,
        ]);
    }

    /**
     * Обновление роли.
     *
     * @param SaveRoleRequest $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SaveRoleRequest $request, $id = null)
    {
        return $this->save($request, $id);
    }

    /**
     * Сохранение роли.
     *
     * @param $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    private function save($request, $id = null)
    {
        if (! is_null($id) && $id > 0) {
            $edit = true;
            $item = Role::where('id', '=', $id)->first();

            if (empty($item)) {
                abort(404);
            }
        } else {
            $edit = false;
            $item = new Role();
        }

        $item->name = trim(strip_tags($request->get('name')));
        $item->display_name = trim(strip_tags($request->get('display_name')));
        $item->description = trim(strip_tags($request->get('description')));
        $item->save();

        $item->syncPermissions($request->get('permissions_id'));

        $action = ($edit) ? 'отредактирована' : 'создана';
        Session::flash('success', 'Роль «'.$item->display_name.'» успешно '.$action);

        return redirect()->to(route('back.acl.roles.edit', $item->fresh()->id));
    }

    /**
     * Удаление роли.
     *
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id = null)
    {
        if (! is_null($id) && $id > 0) {
            $item = Role::where('id', '=', $id)->first();
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
