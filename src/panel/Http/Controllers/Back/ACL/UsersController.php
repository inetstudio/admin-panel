<?php

namespace InetStudio\AdminPanel\Http\Controllers\Back\ACL;

use App\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use InetStudio\AdminPanel\Transformers\Back\ACL\UserTransformer;
use InetStudio\AdminPanel\Http\Requests\Back\ACL\SaveUserRequest;
use InetStudio\AdminPanel\Http\Controllers\Back\Traits\DatatablesTrait;

class UsersController extends Controller
{
    use DatatablesTrait;

    /**
     * Список сайтов с отзывами.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(): View
    {
        $table = $this->generateTable('admin', 'users_index');

        return view('admin::back.pages.acl.users.index', compact('table'));
    }

    /**
     * DataTables ServerSide.
     *
     * @return mixed
     */
    public function data()
    {
        $items = User::with('roles');

        return DataTables::of($items)
            ->setTransformer(new UserTransformer)
            ->rawColumns(['roles', 'actions'])
            ->make();
    }

    /**
     * Добавление пользователя.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(): View
    {
        return view('admin::back.pages.acl.users.form', [
            'item' => new User(),
        ]);
    }

    /**
     * Создание пользователя.
     *
     * @param SaveUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SaveUserRequest $request): RedirectResponse
    {
        return $this->save($request);
    }

    /**
     * Редактирование пользователя.
     *
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = null): View
    {
        if (! is_null($id) && $id > 0 && $item = User::find($id)) {
            return view('admin::back.pages.acl.users.form', [
                'item' => $item,
            ]);
        } else {
            abort(404);
        }
    }

    /**
     * Обновление пользователя.
     *
     * @param SaveUserRequest $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SaveUserRequest $request, $id = null): RedirectResponse
    {
        return $this->save($request, $id);
    }

    /**
     * Сохранение пользователя.
     *
     * @param SaveUserRequest $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    private function save($request, $id = null): RedirectResponse
    {
        if (! is_null($id) && $id > 0 && $item = User::find($id)) {
            $action = 'отредактирован';
        } else {
            $action = 'создан';
            $item = new User();
        }

        $item->name = trim(strip_tags($request->get('name')));
        $item->email = trim(strip_tags($request->get('email')));
        if ($request->filled('password')) {
            $item->password = Hash::make(trim($request->get('password')));
        }
        $item->save();

        $item->syncRoles(collect($request->get('roles_id'))->toArray());
        $item->syncPermissions(collect($request->get('permissions_id'))->toArray());

        Session::flash('success', 'Пользователь «'.$item->name.'» успешно '.$action);

        return redirect()->to(route('back.acl.users.edit', $item->fresh()->id));
    }

    /**
     * Удаление пользователя.
     *
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id = null): JsonResponse
    {
        if (! is_null($id) && $id > 0 && $item = User::find($id)) {
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
     * Возвращаем пользователей для поля.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuggestions(Request $request): JsonResponse
    {
        $search = $request->get('q');
        $data = [];

        $data['items'] = User::select(['id', 'name'])
            ->where('name', 'LIKE', '%'.$search.'%')
            ->orWhere('email', 'LIKE', '%'.$search.'%')
            ->get()->toArray();

        return response()->json($data);
    }
}
