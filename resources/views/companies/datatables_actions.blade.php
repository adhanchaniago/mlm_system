<?php
    $user = \App\User::where('company_id',$id)->where('status','1')->first();

?>
{!! Form::open(['route' => ['companies.destroy', $id], 'method' => 'delete']) !!}
    <div class='btn-group'>
    <center>
        <a href="{{ route('companies.show', $id) }}" class='btn btn-default btn-xs'>
            <i class="glyphicon glyphicon-eye-open"></i>
        </a>
        @if($user->disabled == 0)
            <button type="button" class="btn btn-danger btn-xs" onclick="disableCompany('{{$id}}',1)" title="Disable User"><i class="fa fa-ban"></i></button>
        @else
            <button type="button" class="btn btn-success btn-xs" onclick="disableCompany('{{$id}}',0)" title="Enable User"><i class="fa fa-check"></i></button>
        @endif
    </center>
</div>
{!! Form::close() !!}
