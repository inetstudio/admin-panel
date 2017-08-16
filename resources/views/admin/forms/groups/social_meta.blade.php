@php
    $item = $value;
@endphp

<div class="row">
    <div class="col-lg-12">
        <div class="panel-group float-e-margins" id="socialMetaAccordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5 class="panel-title">
                        <a data-toggle="collapse" data-parent="#socialMetaAccordion" href="#collapseSocialMeta" aria-expanded="false" class="collapsed">Социальные мета теги</a>
                    </h5>
                </div>
                <div id="collapseSocialMeta" class="panel-collapse collapse" aria-expanded="false">
                    <div class="panel-body">

                        {!! Form::string('meta[og:title]', $item->getMeta('og:title'), [
                            'label' => [
                                'title' => 'og:title',
                            ],
                        ]) !!}

                        {!! Form::string('meta[og:description]', $item->getMeta('og:description'), [
                            'label' => [
                                'title' => 'og:description',
                            ],
                        ]) !!}

                        @php
                            $ogImageMedia = $item->getFirstMedia('og_image');
                        @endphp

                        {!! Form::crop('og_image', $ogImageMedia, [
                            'label' => [
                                'title' => 'og:image',
                            ],
                            'image' => [
                                'src' => isset($ogImageMedia) ? url($ogImageMedia->getUrl()) : '',
                            ],
                            'crops' => [
                                [
                                    'title' => 'Размер 968х475',
                                    'name' => 'default',
                                    'ratio' => '968/475',
                                    'value' => isset($ogImageMedia) ? $ogImageMedia->getCustomProperty('crop.default') : '',
                                    'size' => [
                                        'width' => 968,
                                        'height' => 475,
                                        'type' => 'fixed',
                                        'description' => 'Фиксированный размер области — 968x475 пикселей'
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
