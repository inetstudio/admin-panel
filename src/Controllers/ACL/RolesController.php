<?php

namespace InetStudio\AdminPanel\Controllers\ACL;

use App\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use InetStudio\AdminPanel\Traits\DatatablesTrait;
use InetStudio\AdminPanel\Requests\ACL\SaveRoleRequest;
use InetStudio\AdminPanel\Transformers\RoleTransformer;

class RolesController extends Controller
{
    use DatatablesTrait;

    /**
     * Список ролей.
     *
     * @param DataTables $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(DataTables $dataTable)
    {
        $table = $this->generateTable($dataTable, 'admin', 'roles_index');

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

        return DataTables::of($items)
            ->setTransformer(new RoleTransformer)
            ->rawColumns(['actions'])
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
        if (! is_null($id) && $id > 0 && $item = Role::find($id)) {
            return view('admin::pages.acl.roles.form', [
                'item' => $item,
            ]);
        } else {
            abort(404);
        }
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
     * @param SaveRoleRequest $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    private function save($request, $id = null)
    {
        if (! is_null($id) && $id > 0 && $item = Role::find($id)) {
            $action = 'отредактирована';
        } else {
            $action = 'создана';
            $item = new Role();
        }

        $item->name = trim(strip_tags($request->get('name')));
        $item->display_name = trim(strip_tags($request->get('display_name')));
        $item->description = $request->input('description.text');
        $item->save();

        $item->syncPermissions(collect($request->get('permissions_id'))->toArray());

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
        if (! is_null($id) && $id > 0 && $item = Role::find($id)) {
            $item->delete();

            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
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
        $data = [];

        $data['items'] = Role::select(['id', 'display_name as name'])->where('display_name', 'LIKE', '%'.$search.'%')->get()->toArray();

        return response()->json($data);
    }
}
