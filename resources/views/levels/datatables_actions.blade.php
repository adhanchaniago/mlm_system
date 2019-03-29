{!! Form::open(['route' => ['levels.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('levels.show', $id) }}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    <a href="{{ route('levels.edit', $id) }}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
</div>
{!! Form::close() !!}
