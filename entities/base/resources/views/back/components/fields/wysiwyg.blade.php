<div class="vue-wysiwyg-field">
    <base-wysiwyg
        :label="'{{ $label }}'"
        :name="'{{ $fieldName }}'"
        :value="'{{ $value }}'"
        :simple="{{ $simple }}"
        :attributes="{{ json_encode($textAreaAttributes) }}"
        :options="{{ json_encode($editorOptions) }}"
        :media-collections="{{ json_encode($data['mediaCollections']) }}"
    />
</div>
