<div class="modal inmodal fade" id="edit_image_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                <h4 class="modal-title">Редактирование изображения</h4>
            </div>
            <div class="modal-body">
                <div class="ibox-content form-horizontal">
                    <div class="row m-b-md">
                        <img :src="image.src" class="img-responsive" style="max-height: 400px; display: block; margin: auto" />
                        <div class="hr-line-dashed"></div>
                    </div>
                    <div class="row">
                        <template v-for="input in inputs">
                            <div class="form-group">
                                <label :for="input.name" class="col-sm-2 control-label">@{{ input.title }}</label>
                                <div class="col-sm-10">
                                    <input class="form-control slugify" v-model="image.properties[input.name]" :name="input.name" type="text" :value="image.properties[input.name]" :id="input.name">
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
