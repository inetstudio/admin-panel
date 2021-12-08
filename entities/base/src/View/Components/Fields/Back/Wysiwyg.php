<?php

namespace InetStudio\AdminPanel\Base\View\Components\Fields\Back;

use stdClass;
use Illuminate\View\Component;
use InetStudio\UploadsPackage\Uploads\View\Components\Fields\Back\Media;

class Wysiwyg extends Component
{
    public function __construct(
        public $fieldName,
        public $item,
        public $value = '',
        public $label = '',
        public $textAreaAttributes = [],
        public $editorOptions = [],
        public $simple = false
    ) {
        $this->fieldName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $fieldName);

        $this->value = $value ?: $item->$fieldName;

        $defaultTextAreaAttributes = [
            'id' => $fieldName,
        ];
        $this->textAreaAttributes = array_merge($defaultTextAreaAttributes, $textAreaAttributes);

        $this->editorOptions = (! empty($editorOptions)) ? $editorOptions : new stdClass;

        $this->simple = ($simple) ? 'true' : 'false';
    }

    public function render()
    {
        $mediaData = (new Media($this->fieldName, $this->item))->getMediaData();

        $data = [
            'mediaCollections' => empty($mediaData) ? new stdClass : $mediaData['mediaCollections'],
        ];

        return view('inetstudio.admin-panel.base::back.components.fields.wysiwyg', compact('data'));
    }
}
