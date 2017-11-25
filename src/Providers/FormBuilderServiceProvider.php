<?php

namespace InetStudio\AdminPanel\Providers;

use Collective\Html\FormBuilder;
use Illuminate\Support\ServiceProvider;

class FormBuilderServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        FormBuilder::component('info', 'admin::back.forms.blocks.info', ['name' => null, 'value' => null, 'attributes' => null]);
        FormBuilder::component('buttons', 'admin::back.forms.blocks.buttons', ['name', 'value', 'attributes']);

        FormBuilder::component('string', 'admin::back.forms.fields.string', ['name', 'value', 'attributes']);
        FormBuilder::component('passwords', 'admin::back.forms.fields.passwords', ['name', 'value', 'attributes']);
        FormBuilder::component('radios', 'admin::back.forms.fields.radios', ['name', 'value', 'attributes']);
        FormBuilder::component('checks', 'admin::back.forms.fields.checks', ['name', 'value', 'attributes']);
        FormBuilder::component('datepicker', 'admin::back.forms.fields.datepicker', ['name', 'value', 'attributes']);
        FormBuilder::component('wysiwyg', 'admin::back.forms.fields.wysiwyg', ['name', 'value', 'attributes']);
        FormBuilder::component('dropdown', 'admin::back.forms.fields.dropdown', ['name', 'value', 'attributes']);
        FormBuilder::component('crop', 'admin::back.forms.fields.crop', ['name', 'value', 'attributes']);
        FormBuilder::component('list', 'admin::back.forms.fields.list', ['name', 'value', 'attributes']);

        FormBuilder::component('meta', 'admin::back.forms.groups.meta', ['name' => null, 'value' => null, 'attributes' => null]);
        FormBuilder::component('social_meta', 'admin::back.forms.groups.social_meta', ['name' => null, 'value' => null, 'attributes' => null]);
    }

    /**
     * Регистрация привязки в контейнере.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }
}
