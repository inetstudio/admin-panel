<?php

namespace InetStudio\AdminPanel\Controllers\ACL;

use App\Permission;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use InetStudio\AdminPanel\Traits\DatatablesTrait;
use InetStudio\AdminPanel\Requests\ACL\SavePermissionRequest;
use InetStudio\AdminPanel\Transformers\PermissionTransformer;

class PermissionsController extends Controller
{
    use DatatablesTrait;

    /**
     * Список прав.
     *
     * @param Datatables $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Datatables $dataTable)
    {
        $table = $this->generateTable($dataTable, 'admin', 'permissions_index');

        return view('admin::pages.acl.permissions.index', compact('table'));
    }

    /**
     * Datatables serverside.
     *
     * @return mixed
     */
    public function data()
    {
        $items = Permission::query();

        return Datatables::of($items)
            ->setTransformer(new PermissionTransformer)
            ->escapeColumns(['actions'])
            ->make();
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
        if (! is_null($id) && $id > 0 && $item = Permission::find($id)) {
            return view('admin::pages.acl.permissions.form', [
                'item' => $item,
            ]);
        } else {
            abort(404);
        }
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
     * @param SavePermissionRequest $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    private function save($request, $id = null)
    {
        if (! is_null($id) && $id > 0 && $item = Permission::find($id)) {
            $action = 'отредактировано';
        } else {
            $action = 'создано';
            $item = new Permission();
        }

        $item->name = trim(strip_tags($request->get('name')));
        $item->display_name = trim(strip_tags($request->get('display_name')));
        $item->description = $request->input('description.text');
        $item->save();

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
        if (! is_null($id) && $id > 0 && $item = Permission::find($id)) {
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
     * Возвращаем права для поля.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuggestions(Request $request)
    {
        $search = $request->get('q');
        $data = [];

        $data['items'] = Permission::select(['id', 'display_name as name'])->where('display_name', 'LIKE', '%'.$search.'%')->get()->toArray();

        return response()->json($data);
    }
}
