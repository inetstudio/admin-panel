<div class="modal inmodal fade" id="modal_edit_item" tabindex="-1" role="dialog" aria-hidden="true">
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
