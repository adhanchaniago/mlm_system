@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Email Content
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <form method="post" action="{{url('email/edit')}}">
                        {{csrf_field()}}
                        @if($email != "")
                            <div class="form-group col-sm-12">
                                <label>Welcome Text: </label>
                                <textarea  class="form-control" name="welcome_text" rows="5">{{$email->welcome_text}}</textarea>
                            </div>
                            <div class="form-group col-sm-12">
                                <label>Password Reset Text: </label>
                                <textarea type="text" rows="5" class="form-control" name="password_reset_text">{{$email->password_reset_text}}</textarea>
                            </div>
                            <div class="form-group col-sm-12">
                                <label>Account Delete Text: </label>
                                <textarea type="text" rows="5" class="form-control" name="account_delete_text">{{$email->account_delete_text}}</textarea>
                            </div>
                            <div class="form-group col-sm-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{url('home')}}" class="btn btn-default">Cancel</a>
                            </div>
                        @else
                            <div class="form-group col-sm-12">
                                <label>Welcome Text: </label>
                                <textarea type="text" rows="5" class="form-control" name="welcome_text"></textarea>
                            </div>
                            <div class="form-group col-sm-12">
                                <label>Password Reset Text: </label>
                                <textarea type="text" rows="5" class="form-control" name="password_reset_text"></textarea>
                            </div>
                            <div class="form-group col-sm-12">
                                <label>Account Delete Text: </label>
                                <textarea type="text" rows="5" class="form-control" name="account_delete_text"></textarea>
                            </div>
                            <div class="form-group col-sm-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{url('home')}}" class="btn btn-default">Cancel</a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection