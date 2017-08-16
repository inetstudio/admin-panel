@php
    $item = $value;
@endphp

<div class="row">
    <div class="col-lg-12">
        <div class="panel-group float-e-margins" id="metaAccordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5 class="panel-title">
                        <a data-toggle="collapse" data-parent="#metaAccordion" href="#collapseMeta" aria-expanded="false" class="collapsed">Мета теги</a>
                    </h5>
                </div>
                <div id="collapseMeta" class="panel-collapse collapse" aria-expanded="false">
                    <div class="panel-body">

                        {!! Form::string('meta[title]', $item->getMeta('title'), [
                            'label' => [
                                'title' => 'Title',
                            ],
                        ]) !!}

                        {!! Form::string('meta[description]', $item->getMeta('description'), [
                            'label' => [
                                'title' => 'Description',
                            ],
                        ]) !!}

                        {!! Form::string('meta[keywords]', $item->getMeta('keywords'), [
                            'label' => [
                                'title' => 'Keywords',
                            ],
                        ]) !!}

                        {!! Form::radios('meta[robots]', $item->getMeta('robots'), [
                            'label' => [
                                'title' => 'Индексировать',
                            ],
                            'radios' => [
                                [
                                    'label' => 'Да',
                                    'value' => 'index, follow',
                                    'options' => [
                                        'class' => 'i-checks',
                                    ],
                                ],
                                [
                                    'label' => 'Нет',
                                    'value' => 'noindex, nofollow',
                                    'options' => [
                                        'class' => 'i-checks',
                                    ],
                                ],
                            ],
                        ]) !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
