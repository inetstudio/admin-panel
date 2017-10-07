<?php

return [

    /*
     * Настройки таблиц
     */

    'datatables' => [
        'ajax' => [
            'permissions_index' => [
                'url' => 'back.acl.permissions.data',
                'type' => 'POST',
                'data' => 'function(data) { data._token = $(\'meta[name="csrf-token"]\').attr(\'content\'); }',
            ],
            'roles_index' => [
                'url' => 'back.acl.roles.data',
                'type' => 'POST',
                'data' => 'function(data) { data._token = $(\'meta[name="csrf-token"]\').attr(\'content\'); }',
            ],
            'users_index' => [
                'url' => 'back.acl.users.data',
                'type' => 'POST',
                'data' => 'function(data) { data._token = $(\'meta[name="csrf-token"]\').attr(\'content\'); }',
            ],
        ],
        'table' => [
            'default' => [
                'paging' => true,
                'pagingType' => 'full_numbers',
                'searching' => true,
                'info' => false,
                'searchDelay' => 350,
                'language' => [
                    'url' => '/admin/js/plugins/datatables/locales/russian.json',
                ],
            ],
        ],
        'columns' => [
            'permissions_index' => [
                ['data' => 'id', 'name' => 'id', 'title' => 'ID', 'orderable' => true],
                ['data' => 'display_name', 'name' => 'display_name', 'title' => 'Название'],
                ['data' => 'name', 'name' => 'name', 'title' => 'Алиас'],
                ['data' => 'description', 'name' => 'description', 'title' => 'Описание'],
                ['data' => 'actions', 'name' => 'actions', 'title' => 'Действия', 'orderable' => false, 'searchable' => false],
            ],
            'roles_index' => [
                ['data' => 'id', 'name' => 'id', 'title' => 'ID', 'orderable' => true],
                ['data' => 'display_name', 'name' => 'display_name', 'title' => 'Название'],
                ['data' => 'name', 'name' => 'name', 'title' => 'Алиас'],
                ['data' => 'description', 'name' => 'description', 'title' => 'Описание'],
                ['data' => 'actions', 'name' => 'actions', 'title' => 'Действия', 'orderable' => false, 'searchable' => false],
            ],
            'users_index' => [
                ['data' => 'id', 'name' => 'id', 'title' => 'ID', 'orderable' => true],
                ['data' => 'name', 'name' => 'name', 'title' => 'Имя'],
                ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
                ['data' => 'roles', 'name' => 'roles.display_name', 'title' => 'Роли'],
                ['data' => 'actions', 'name' => 'actions', 'title' => 'Действия', 'orderable' => false, 'searchable' => false],
            ],
        ],
    ],

];
