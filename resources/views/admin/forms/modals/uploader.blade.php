<div class="modal inmodal fade" id="uploader_modal" tabindex="-1" role="dialog" aria-hidden="true" ref="vuemodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                <h4 class="modal-title">Загрузка изображений</h4>
            </div>
            <div class="modal-body">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div v-show="progress.state" class="progress progress-bar-default">
                                <div :style="progress.style" aria-valuemax="100" aria-valuemin="0" :aria-valuenow="progress.percents" role="progressbar" class="progress-bar">
                                    <span>@{{ progress.text }}</span>
                                </div>
                            </div>
                            <div v-show="upload" id="uploader-area" data-target="{{ route('back.upload') }}">Перенесите изображения в область</div>
                        </div>
                    </div>
                    <template v-for="image in images">
                        <div class="row upload-image m-t-md" :data-hash="image.hash">
                            <div class="col-md-3">
                                <img :src="image.src" class="m-b-md img-responsive placeholder" :data-tempname="image.tempname" :data-filename="image.filename">
                            </div>
                            <div class="col-md-9 form-horizontal">
                                <div class="form-group" v-for="input in inputs">
                                    <label :for="input.name" class="col-sm-2 control-label">@{{ input.title }}</label>
                                    <div class="col-sm-10">
                                        <input class="form-control" v-model="image.properties[input.name]" :name="input.name" type="text" value="" :id="input.name">
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                    </template>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                <a href="#" class="btn btn-primary" @click.prevent="save">Сохранить</a>
            </div>
        </div>
    </div>
</div>
