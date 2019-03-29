@include('frontEnd.main_div')


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
                <table class="levels-table">
                    @include('flash::message')
                    @include('adminlte-templates::common.errors')
                    @foreach($levels as $level)
                        <tr>
                            <td class="levels-td-50p text-left"><h2>{{trans('level.level')}} {{$level->level}}</h2></td>
                            <td class="levels-td-50p text-right"><h2>{{$level->share_to_team_revenue}}%</h2></td>
                        </tr>
                    @endforeach
                    @if($level_count < $max_level)
                        <tr>
                            <td class="levels-td-50p text-left">
                                <button type="button" class="btn btn-primaryy" data-toggle="modal" data-target="#addLevel">{{trans('level.add_level')}}</button>
                            </td>
                            <td class="levels-td-50p text-right">
                                @if($level_count>0)
                                <a href="{{url('deleteLevel')}}" type="button" class="btn btn-primaryy" onclick="return confirm('{{trans('myProfile.sure')}}')">{{trans('level.delete_level')}}</a>
                                @endif

                                <div class="modal fade" id="addLevel" role="dialog">
                                    <div class="modal-dialog add-level-modal">
                                        <div class="modal-content add-level-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <center><h2 class="modal-title">{{trans('level.level')}} {{$next_level}}</h2></center>
                                            </div>
                                            <div class="modal-body">
                                                {!! Form::open(['route' => 'levels.store']) !!}
                                                <div class="row">
                                                    <div class="col-md-3 col-sm-3"></div>
                                                    <div class="form-group col-sm-6">
                                                        <input type="text" class="form-control form-no-border"
                                                               name="share_to_team_revenue" placeholder="%">

                                                        <input type="hidden" name="level" value="{{$next_level}}">
                                                        <input type="hidden" name="company_id" value="{{$id}}">
                                                    </div>
                                                    <div class="col-md-3 col-sm-3"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2 col-sm-2"></div>
                                                    <div class="col-md-8 col-sm-8">
                                                        <center><h5>{{trans('level.add_level_description')}}</h5></center>
                                                    </div>
                                                    <div class="col-md-2 col-sm-2"></div>
                                                </div>
                                                <div class="col-md-3 col-sm-3"></div>
                                                <div class="form-group col-sm-6">
                                                    <center>
                                                        <button type="submit" class="btn btn-save-level">{{trans('myProfile.save')}}</button>
                                                    </center>
                                                </div>
                                                <div class="col-md-3 col-sm-3"></div>
                                                {!! Form::close() !!}

                                            </div>
                                            <div class="modal-footer">

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </td>
                        </tr>
                    @else
                        <center>
                            <tr>
                                <td class="text-center">
                                    <a href="{{url('deleteLevel')}}" type="button" class="btn btn-primaryy" onclick="return confirm('{{trans('myProfile.sure')}}')">{{trans('level.delete_level')}}</a>
                                </td>
                            </tr>
                        </center>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@include('frontEnd.footer')