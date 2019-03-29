<style>
    .rank-model-button{
        margin-top: 7px;
    }
</style>
@include('frontEnd.main_div')
<head>
</head>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('flash::message')
            @include('adminlte-templates::common.errors')
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-6">
                        <button class="btn btn-primaryy rank-btn" data-toggle="modal" data-target="#addRank">
                            {{trans('myProfile.add_new')}}
                        </button>
                    </div>
                    <div class="col-md-6"></div>
                </div>
                <div class="col-md-6"></div>
            </div>
            @foreach($ranks as $rank)
            <div class="col-md-6 col-sm-6 col-xs-12 rankdivision">
                <div class="col-md-5 col-sm-6 col-xs-12 rank-image-div">
                    <img src="{{asset('public/avatars').'/'.$rank->image}}" class="rank-image  rank-display-div">
                </div>
                <div class="col-md-7 col-sm-6 col-xs-12 rank-image-div">
                    <h4 class="rank_margin"><b>{{$rank->name}}</b></h4>
                    <h4 class="rank_margin">{{trans('rank.revenue_trigger')}} <b>&dollar;{{number_format($rank->revenue_trigger)}}</b>
                    </h4>
                    <h4 class="rank_margin"><b>{{trans('rank.payout')}} &dollar;{{number_format($rank->payout_amount)}}</b></h4>
                    <button class="btn btn-primaryy rank-edit-btn" data-toggle="modal"
                            data-target="#editRank{{$rank->id}}">{{trans('rank.edit_rank')}}
                    </button>
                </div>
            </div>

            <div class="modal fade" id="editRank{{$rank->id}}" role="dialog">
                <div class="modal-dialog edit-rank-modal">
                    <div class="modal-content model_clr rankadd-level-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <center><h2 class="modal-title">{{trans('rank.rank')}} {{$rank->rank}}</h2></center>
                        </div>
                        <div class="modal-body">
                            {!! Form::model($rank, ['route' => ['ranks.update', $rank->id], 'method' => 'patch','files'
                            => true]) !!}
                            <div class="row stats_section1">
                                <div class="form-group col-md-6">
                                    @if(isset($rank->image))
                                    <img id="image2" src="{{asset('public/avatars').'/'.$rank->image}}"
                                         class="table-img">
                                    @endif
                                    <input type="file" id="file" class="form-control image_style" accept="image/x-png,image/gif,image/jpeg,image/jpg,image/PNG" name="image">


                                    <input type="hidden" name="company_id" value="{{$id}}">
                                    <input type="hidden" name="rank" value="{{$rank->rank}}">

                                    <label>{{trans('rank.rank_name')}}</label>
                                    <input type="text" class="form-control" name="name" value="{{$rank->name}}"
                                           placeholder="{{trans('auth.name')}}">
                                    <label>{{trans('rank.revenue_trigger')}} </label>
                                    <input type="text" class="form-control" name="revenue_trigger"
                                           value="{{($rank->revenue_trigger)}}"
                                           placeholder="{{trans('rank.revenue_trigger')}} &dollar;">

                                    <label>{{trans('rank.payout_amount')}}: </label>
                                    <input type="text" class="form-control" name="payout_amount"
                                           value="{{($rank->payout_amount)}}"
                                           placeholder="{{trans('rank.payout_amount')}} &dollar;">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-save-level rank-model-button">
                                            {{trans('myProfile.save')}}
                                        </button>
                                    </div>
                                    <div class="modal-footer">

                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>


<div class="modal fade" id="addRank" role="dialog">
    <div class="modal-dialog add-rank-modal">
        <div class="modal-content model_clr add-rank-modal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <center><h2 class="modal-title">{{trans('rank.rank')}} {{$next_rank}}</h2></center>
            </div>
            <div class="modal-body section2_numbers">
                {!! Form::open(['route' => 'ranks.store','files'=>true]) !!}

                <div class="row">
                    <div class="form-group col-sm-12">
                        <input type="file" class="form-control" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg,image/PNG" onchange="readURL(this)"> <br/>
                        <div id="image">

                        </div>
                    </div>
                </div>
                <input type="hidden" name="company_id" value="{{$id}}">
                <input type="hidden" name="rank" value="{{$next_rank}}">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <input type="text" class="form-control" name="name" placeholder="{{trans('auth.name')}}">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12">
                        <input type="text" class="form-control" name="revenue_trigger"
                               placeholder="{{trans('rank.revenue_trigger')}} &dollar;">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12">
                        <input type="text" class="form-control" name="payout_amount"
                               placeholder="{{trans('rank.payout_amount')}} &dollar;">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12">
                        <center>
                            <button type="submit" class="btn btn-save-level">{{trans('myProfile.save')}}</button>
                        </center>
                    </div>
                </div>

                {!! Form::close() !!}

            </div>
            <div class="modal-footer">
            </div>
        </div>

    </div>
</div>
@include('frontEnd.footer')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                var html = '<img class="table-img" src="' + e.target.result + '">';
                $('#image')
                    .html(html);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>