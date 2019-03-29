<table class="table table-responsive" id="samyBotPlans-table">
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Image</th>
        <th>Term</th>
        <th>Amount for 1 unit</th>
        <th>Amount for 5 Device</th>
        <th>Amount for 10 Device</th>
        <th>Amount for 20 Device</th>
        <th>Display</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i=1;
    ?>
    @foreach($SamyBotPlans as $SamyBotPlan)
        <tr>
            <td>{{$i}}</td>
            <td>{{$SamyBotPlan->name}}</td>
            <td>
                @if(isset($SamyBotPlan->image))
                    <img class="edit-image" src="{{asset('public/avatars').'/'.$SamyBotPlan->image}}">
                @endif
            </td>
            <td>{{$SamyBotPlan->term}}</td>
            <td>{{$SamyBotPlan->amount_1}}</td>
            <td>{{$SamyBotPlan->amount_5}}</td>
            <td>{{$SamyBotPlan->amount_10}}</td>
            <td>{{$SamyBotPlan->amount_20}}</td>
            <td>
                @if($SamyBotPlan->status == 0)
                    <button type="button" class="btn btn-success" onclick="changeStatus('{{$SamyBotPlan->id}}',1)">Show</button>
                @else
                    <button type="button" class="btn btn-danger" onclick="changeStatus('{{$SamyBotPlan->id}}',0)">Hide</button>
                @endif
            </td>
            <td>
                <div class='btn-group'>
                    {{--<a href="{{ route('SamyBotPlans.show', $SamyBotPlan->id) }}" class='btn btn-default btn-xs'>--}}
                    {{--<i class="glyphicon glyphicon-eye-open"></i>--}}
                    {{--</a>--}}
                    <a href="{{ route('SamyBotPlans.edit', $SamyBotPlan->id) }}" class='btn btn-default btn-xs'>
                        <i class="glyphicon glyphicon-edit"></i>
                    </a>
                    {!! Form::open(['route' => ['SamyBotPlans.destroy', $SamyBotPlan->id], 'method' => 'delete']) !!}
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
                        'type' => 'submit',
                        'class' => 'btn btn-danger btn-xs',
                        'onclick' => "return confirm('Are you sure?')"
                    ]) !!}
                    {!! Form::close() !!}
                </div>
            </td>
        </tr>
        <?php
        $i++;
        ?>
    @endforeach
    </tbody>
</table>