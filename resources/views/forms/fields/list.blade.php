@php
    $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);

    $propertiesArr = (isset($attributes['fields'])) ? $attributes['fields'] : [];
    $properties = json_encode($propertiesArr);

    $items = (! empty($value)) ? $value->map(function ($item) use ($propertiesArr) {
        $data = [
            'id' => $item->id,
            'properties' => [],
        ];

        foreach ($propertiesArr as $property) {
            $data['properties'][$property['name']] = $item[$property['name']];
        }

        return $data;
    })->toArray() : [];

    $items = json_encode($items);
@endphp

<div class="form-group @if ($errors->filled($transformName)){!! "has-error" !!}@endif editable-list" id="{{ $name }}_field_block" data-properties="{{ $properties }}" data-items="{{ $items }}">

    @if (isset($attributes['label']['title']))
        {!! Form::label($name, $attributes['label']['title'], (isset($attributes['label']['options'])) ? $attributes['label']['options'] : ['class' => 'col-sm-2 control-label']) !!}
    @endif

    <div class="col-sm-10">
        <div class="ibox float-e-margins">
            <div class="ibox-content no-borders">
                <a href="#" class="btn btn-sm btn-primary btn-xs" @click.prevent="add">Добавить</a>
                <ul class="answer-list m-t small-list">
                    <li v-for="(item, index) in items">
                        <span class="m-l-xs">@{{ itemTitles[index] }}</span>
                        <div class="btn-group pull-right">
                            <a href="#" class="btn btn-xs btn-default m-r-xs" @click.prevent="edit(index)"><i class="fa fa-pencil"></i></a>
                            <a href="#" class="btn btn-xs btn-danger delete" @click.prevent="remove(index)"><i class="fa fa-times"></i></a>
                        </div>
                        <input :name="'{{ $name }}[' + index + '][id]'" type="hidden" :value="item.id">
                        <input v-for="(value, key) in item.properties" :name="'{{ $name }}[' + index + '][properties][' + key + ']'" type="hidden" :value="value">
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="hr-line-dashed"></div>

@pushonce('modals:edit_list_item')
    <div class="modal inmodal fade" id="edit_list_item_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h1 class="modal-title" v-if="mode == 'add'">Создание элемента</h1>
                    <h1 class="modal-title" v-else>Редактирование элемента</h1>
                </div>
                <div class="modal-body">
                    <div class="ibox-content form-horizontal">
                        <div class="row">
                            <template v-for="input in inputs">
                                <div class="form-group">
                                    <label :for="input.name" class="col-sm-2 control-label">@{{ input.title }}</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" :name="input.name" type="text" :value="item.properties[input.name]" :id="input.name">
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                    <a href="#" class="btn btn-primary" @click.prevent="save">Сохранить</a>
                </div>
            </div>
        </div>
    </div>
@endpushonce