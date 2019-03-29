@include('frontEnd.admin_header')
<style>
    .samy_btn{
        padding: 7px;
        font-size: medium;
        color: white;
        background-color: #ff5722;
        border: 1px solid lightgray;
    }
</style>
<div class="container">
    <div class="row">
        <center><h2>{{trans('samybot/mailchimp.mailchip_list')}}</h2></center>
        <br/>
        @include('flash::message')
        <div class="col-md-12" style="display: flex;align-items: center;justify-content: center">
            @if(!isset($mailChimp))
            <div class="col-md-8">
                <form action="{{url('mailchimp/create')}}" method="post">
                    {{csrf_field()}}
                    <label>{{trans('samybot/mailchimp.api_key')}}</label>
                        <input type="text" name="api_key" class="form-control" placeholder="{{trans('samybot/mailchimp.api_key')}}" required>
                    <label>{{trans('samybot/mailchimp.data_center')}}</label>
                        <input type="text" name="data_center" class="form-control" placeholder="Eg: us7" required>
                    <br>
                    <a href="{{url('mailchimp/create')}}">
                        <button class="samy_btn pull-right">{{trans('samybot/mailchimp.next')}}</button>
                    </a>
                </form>
            </div>
            @else
            <div class="col-md-8">
                    <form action="{{url('mailchimp/update')}}" method="post">
                        {{csrf_field()}}
                        <label>{{trans('samybot/mailchimp.api_key')}}</label>
                        <input type="text" name="api_key" class="form-control" value="{{$apiData->api_key}}" required>
                        <label>{{trans('samybot/mailchimp.data_center')}}</label>
                        <input type="text" name="data_center" class="form-control" value="{{$apiData->data_center}}" required>
                        <label>{{trans('samybot/mailchimp.favorite_list')}}</label>
                        <select name="fav_list" class="form-control">
                            @foreach($mailChimp as $Lists)
                                <option value="{{$Lists->list_id}}" @if($Lists->Is_favorite == 1) selected @endif>{{$Lists->list_id}} - {{$Lists->name}}</option>
                            @endforeach
                        </select>
                        <label>{{trans('samybot/mailchimp.prospect_list')}}</label>
                        <select name="pros_list" class="form-control">
                            @foreach($mailChimp as $Lists)
                                <option value="{{$Lists->list_id}}" @if($Lists->Is_Prospect == 1) selected @endif>{{$Lists->list_id}}- {{$Lists->name}}</option>
                            @endforeach
                        </select>
                        <br>
                        <a href="{{url('mailchimp/create')}}">
                            <button class="samy_btn pull-right">{{trans('home.save')}}</button>
                        </a>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
{{--<table class="col-md-12 table table-striped">--}}
{{--<thead>--}}
{{--<th class="col-md-3">List Id</th>--}}
{{--<th class="col-md-3">Name</th>--}}
{{--<th class="col-md-3">Web Id</th>--}}
{{--<th class="col-md-2">Date</th>--}}
{{--<th class="col-md-1">Action</th>--}}
{{--</thead>--}}
{{--<tbody>--}}
{{--@foreach($mailChimp as $chimp)--}}
{{--<tr>--}}
{{--<td class="col-md-3">{{$chimp->list_id}}</td>--}}
{{--<td class="col-md-3">{{$chimp->name}}</td>--}}
{{--<td class="col-md-3">{{$chimp->web_id}}</td>--}}
{{--<td class="col-md-2">{{$chimp->date}}</td>--}}
{{--                    <td class="col-md-1"><a href="{{url('mailchimp/edit').'/'.$chimp->id}}"></a><i class="fa fa-edit"></i></td>--}}
{{--</tr>--}}
{{--@endforeach--}}
{{--</tbody>--}}
{{--</table>--}}
@include('frontEnd.footer')

