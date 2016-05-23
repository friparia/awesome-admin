<i class="close icon"></i>
<div class="header">
添加
</div>
<div class="content">
    <form id="admin-form" class="ui form" action="{{ action($controller."@admin", ["action" => "create", "id" => $instance->id]) }}" method="POST">
        @foreach ($instance->getEditableColumns() as $column)
        <div class="field">
            <label>{{ $column->description }}</label>
            @if ($column->type == 'boolean')
            <div class="ui toggle checkbox">
                <input data-id="{{ $instance->id }}" type="checkbox" name="{{ $column->name }}">
                <label></label>
            </div>
            @elseif($column->type == 'enum')
            <select class="ui dropdown" name="{{ $column->name }}">
                @foreach($column->values as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            @elseif($column->type == 'extended')
            @if(is_array($instance->getExtendedType($column->name)))
            <select class="ui dropdown" name="{{ $instance->getExtendedName($column->name) }}">
                @foreach($instance->getExtendedType($column->name) as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            @else
//TODO
            @endif
            @else
            <input name="{{ $column->name }}" placeholder="{{ $column->description }}" type="text">
            @endif
        </div>
        @endforeach
    </form>
</div>
<script>
$(function(){
    $('#admin-form .ui.checkbox').checkbox();
    $('#admin-form .ui.dropdown').dropdown();
});
</script>

