<?php

namespace InetStudio\AdminPanel\Http\Controllers\Back\ACL;

use App\Permission;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use InetStudio\AdminPanel\Transformers\Back\ACL\PermissionTransformer;
use InetStudio\AdminPanel\Http\Controllers\Back\Traits\DatatablesTrait;
use InetStudio\AdminPanel\Http\Requests\Back\ACL\SavePermissionRequest;

class PermissionsController extends Controller
{
    use DatatablesTrait;

    /**
     * Список прав.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(): View
    {
        $table = $this->generateTable('admin', 'permissions_index');

        return view('admin::back.pages.acl.permissions.index', compact('table'));
    }

    /**
     * DataTables ServerSide.
     *
     * @return mixed
     */
    public function data()
    {
        $items = Permission::query();

        return DataTables::of($items)
            ->setTransformer(new PermissionTransformer)
            ->rawColumns(['actions'])
            ->make();
    }

    /**
     * Добавление права.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(): View
    {
        return view('admin::back.pages.acl.permissions.form', [
            'item' => new Permission(),
        ]);
    }

    /**
     * Создание права.
     *
     * @param SavePermissionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SavePermissionRequest $request): RedirectResponse
    {
        return $this->save($request);
    }

    /**
     * Редактирование права.
     *
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = null): View
    {
        if (! is_null($id) && $id > 0 && $item = Permission::find($id)) {
            return view('admin::back.pages.acl.permissions.form', [
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
    public function update(SavePermissionRequest $request, $id = null): RedirectResponse
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
    private function save(SavePermissionRequest $request, $id = null): RedirectResponse
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
    public function destroy($id = null): JsonResponse
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
    public function getSuggestions(Request $request): JsonResponse
    {
        $search = $request->get('q');
        $data = [];

        $data['items'] = Permission::select(['id', 'display_name as name'])->where('display_name', 'LIKE', '%'.$search.'%')->get()->toArray();

        return response()->json($data);
    }
}
