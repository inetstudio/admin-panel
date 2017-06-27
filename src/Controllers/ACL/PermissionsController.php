<?php

namespace InetStudio\AdminPanel\Controllers\ACL;

use App\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use InetStudio\AdminPanel\Requests\ACL\SavePermissionRequest;

class PermissionsController extends Controller
{
    /**
     * Список прав.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::pages.acl.permissions.index', [
            'items' => Permission::get(),
        ]);
    }

    /**
     * Добавление права.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin::pages.acl.permissions.form', [
            'item' => new Permission(),
        ]);
    }

    /**
     * Создание права.
     *
     * @param SavePermissionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SavePermissionRequest $request)
    {
        return $this->save($request);
    }

    /**
     * Редактирование права.
     *
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = null)
    {
        if (! is_null($id) && $id > 0) {
            $item = Permission::where('id', '=', $id)->first();
        } else {
            abort(404);
        }

        if (empty($item)) {
            abort(404);
        }

        return view('admin::pages.acl.permissions.form', [
            'item' => $item,
        ]);
    }

    /**
     * Обновление права.
     *
     * @param SavePermissionRequest $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SavePermissionRequest $request, $id = null)
    {
        return $this->save($request, $id);
    }

    /**
     * Сохранение права.
     *
     * @param $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    private function save($request, $id = null)
    {
        if (! is_null($id) && $id > 0) {
            $edit = true;
            $item = Permission::where('id', '=', $id)->first();

            if (empty($item)) {
                abort(404);
            }
        } else {
            $edit = false;
            $item = new Permission();
        }

        $item->name = trim(strip_tags($request->get('name')));
        $item->display_name = trim(strip_tags($request->get('display_name')));
        $item->description = trim(strip_tags($request->get('description')));

        $item->save();

        $action = ($edit) ? 'отредактировано' : 'создано';
        Session::flash('success', 'Право «'.$item->display_name.'» успешно '.$action);

        return redirect()->to(route('back.acl.permissions.edit', $item->fresh()->id));
    }

    /**
     * Удаление права.
     *
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id = null)
    {
        if (! is_null($id) && $id > 0) {
            $item = Permission::where('id', '=', $id)->first();
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
