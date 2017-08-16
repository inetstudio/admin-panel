<div class="modal inmodal fade" id="crop_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                <h4 class="modal-title"></h4>
                <small class="font-bold">Выберите область изображения</small>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <small class="label label-warning description"></small>
                        <p class="m-t-lg">Размер выбранной области: <span class="label crop-size"></span></p>

                        <div class="m-b-xs">
                            <img src="" class="m-b-md img-responsive center-block" id="crop_image">
                        </div>

                        <div class="btn-group m-b-xs">
                            <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="move" title="Переместить">
                                <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить">
                                  <span class="fa fa-arrows"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="crop" title="Выбрать область">
                                <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Выбрать область">
                                  <span class="fa fa-crop"></span>
                                </span>
                            </button>
                        </div>

                        <div class="btn-group m-b-xs">
                            <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Увеличить">
                                <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Увеличить">
                                  <span class="fa fa-search-plus"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Уменьшить">
                                <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Уменьшить">
                                  <span class="fa fa-search-minus"></span>
                                </span>
                            </button>
                        </div>

                        <div class="btn-group m-b-xs">
                            <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Переместить влево">
                                <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить влево">
                                  <span class="fa fa-arrow-left"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Переместить вправо">
                                <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить вправо">
                                  <span class="fa fa-arrow-right"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Переместить вверх">
                                <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить вверх">
                                  <span class="fa fa-arrow-up"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Переместить вниз">
                                <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Переместить вниз">
                                  <span class="fa fa-arrow-down"></span>
                                </span>
                            </button>
                        </div>

                        <div class="btn-group m-b-xs">
                            <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Повернуть влево">
                                <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Повернуть влево">
                                  <span class="fa fa-rotate-left"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Повернуть вправо">
                                <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Повернуть вправо">
                                  <span class="fa fa-rotate-right"></span>
                                </span>
                            </button>
                        </div>

                        <div class="btn-group m-b-xs">
                            <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Отразить горизонтально">
                                <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Отразить горизонтально">
                                  <span class="fa fa-arrows-h"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Отразить вертикально">
                                <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Отразить вертикально">
                                  <span class="fa fa-arrows-v"></span>
                                </span>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="img-preview">
                            <img src="" id="crop_preview" />
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                <a href="#" class="btn btn-primary save">Сохранить</a>
            </div>
        </div>
    </div>
</div>