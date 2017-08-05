<?php

namespace InetStudio\AdminPanel\Controllers\ACL;

use App\Role;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use InetStudio\AdminPanel\Requests\ACL\SaveRoleRequest;
use InetStudio\AdminPanel\Transformers\RoleTransformer;

class RolesController extends Controller
{
    /**
     * Список ролей.
     *
     * @param Datatables $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Datatables $dataTable)
    {
        $table = $dataTable->getHtmlBuilder();

        $table->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID', 'orderable' => true],
            ['data' => 'display_name', 'name' => 'display_name', 'title' => 'Название'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Алиас'],
            ['data' => 'description', 'name' => 'description', 'title' => 'Описание'],
            ['data' => 'actions', 'name' => 'actions', 'title' => 'Действия', 'orderable' => false, 'searchable' => false],
        ]);

        $table->ajax([
            'url' => route('back.acl.roles.data'),
            'type' => 'POST',
            'data' => 'function(data) { data._token = $(\'meta[name="csrf-token"]\').attr(\'content\'); }',
        ]);

        $table->parameters([
            'paging' => true,
            'pagingType' => 'full_numbers',
            'searching' => true,
            'info' => false,
            'searchDelay' => 350,
            'language' => [
                'url' => asset('admin/js/plugins/datatables/locales/russian.json'),
            ],
        ]);

        return view('admin::pages.acl.roles.index', compact('table'));
    }

    /**
     * Datatables serverside.
     *
     * @return mixed
     */
    public function data()
    {
        $items = Role::query();

        return Datatables::of($items)
            ->setTransformer(new RoleTransformer)
            ->escapeColumns(['actions'])
            ->make();
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

    /**
     * Возвращаем роли для поля.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuggestions(Request $request)
    {
        $search = $request->get('q');
        $data['items'] = Role::select(['id', 'display_name as name'])->where('display_name', 'LIKE', '%'.$search.'%')->get()->toArray();

        return response()->json($data);
    }
}
