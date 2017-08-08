<?php

namespace InetStudio\AdminPanel\Controllers\ACL;

use App\User;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use InetStudio\AdminPanel\Requests\ACL\SaveUserRequest;
use InetStudio\AdminPanel\Transformers\UserTransformer;

class UsersController extends Controller
{
    /**
     * Список сайтов с отзывами.
     *
     * @param Datatables $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Datatables $dataTable)
    {
        $table = $dataTable->getHtmlBuilder();

        $table->columns($this->getColumns());
        $table->ajax($this->getAjaxOptions());
        $table->parameters($this->getTableParameters());

        return view('admin::pages.acl.users.index', compact('table'));
    }

    /**
     * Свойства колонок datatables.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => 'ID', 'orderable' => true],
            ['data' => 'name', 'name' => 'name', 'title' => 'Имя'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'roles', 'name' => 'roles.display_name', 'title' => 'Роли'],
            ['data' => 'actions', 'name' => 'actions', 'title' => 'Действия', 'orderable' => false, 'searchable' => false],
        ];
    }

    /**
     * Свойства ajax datatables.
     *
     * @return array
     */
    private function getAjaxOptions()
    {
        return [
            'url' => route('back.acl.users.data'),
            'type' => 'POST',
            'data' => 'function(data) { data._token = $(\'meta[name="csrf-token"]\').attr(\'content\'); }',
        ];
    }

    /**
     * Свойства datatables.
     *
     * @return array
     */
    private function getTableParameters()
    {
        return [
            'paging' => true,
            'pagingType' => 'full_numbers',
            'searching' => true,
            'info' => false,
            'searchDelay' => 350,
            'language' => [
                'url' => asset('admin/js/plugins/datatables/locales/russian.json'),
            ],
        ];
    }

    /**
     * Datatables serverside.
     *
     * @return mixed
     */
    public function data()
    {
        $items = User::with('roles');

        return Datatables::of($items)
            ->setTransformer(new UserTransformer)
            ->escapeColumns(['roles', 'actions'])
            ->make();
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
        if (! is_null($id) && $id > 0 && $item = User::find($id)) {
            return view('admin::pages.acl.users.form', [
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
    public function update(SaveUserRequest $request, $id = null)
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
    private function save($request, $id = null)
    {
        if (! is_null($id) && $id > 0 && $item = User::find($id)) {
            $action = 'отредактирован';
        } else {
            $action = 'создан';
            $item = new User();
        }

        $item->name = trim(strip_tags($request->get('name')));
        $item->email = trim(strip_tags($request->get('email')));
        if ($request->has('password')) {
            $item->password = bcrypt(trim($request->get('password')));
        }
        $item->save();

        $item->syncRoles($request->get('roles_id'));
        $item->syncPermissions($request->get('permissions_id'));

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
    public function getSuggestions(Request $request)
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
