<?php

$company_name = $company->name;
?>

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {{$company_name}}
        </h1>
    </section>
    <div class="content">
        @if ($message = Session::get('success'))

            <div class="custom-alerts alert alert-success fade in">

                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>

                {!! $message !!}

            </div>

            <?php Session::forget('success');?>

        @endif
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <form method="post" enctype="multipart/form-data" action="{{url('edit/smtp/company').'/'.$company->id}}">
                        @if ($message = Session::get('error'))

                            <div class="custom-alerts alert alert-danger fade in">

                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>

                                {!! $message !!}

                            </div>

                            <?php Session::forget('error');?>

                        @endif
                        {{csrf_field()}}
                        @if($smtp != "")
                            <div class="form-group col-sm-12">
                                <label>SMTP: </label>
                                <input type="text" name="smtp" class="form-control" value="{{$smtp->smtp}}" >
                            </div>
                            <div class="form-group col-sm-12">
                                <label>SMTP USER ID: </label>
                                <input type="text" name="smtp_user_id" class="form-control" value="{{$smtp->smtp_user_id}}" >
                            </div>
                            <div class="form-group col-sm-12">
                                <label>SMTP PASSWORD: </label>
                                <input type="text" name="smtp_password" class="form-control" value="{{$smtp->smtp_password}}" >
                            </div>
                        @else
                            <div class="form-group col-sm-12">
                                <label>SMTP: </label>
                                <input type="text" name="smtp" class="form-control" placeholder="smtp">
                            </div>
                            <div class="form-group col-sm-12">
                                <label>SMTP USER ID: </label>
                                <input type="text" name="smtp_user_id" class="form-control" placeholder="smtp user id">
                            </div>
                            <div class="form-group col-sm-12">
                                <label>SMTP PASSWORD: </label>
                                <input type="text" name="smtp_password" class="form-control" placeholder="smtp password">
                            </div>
                        @endif
                        <div class="form-group col-sm-12">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{!! url('dashboard') !!}" class="btn btn-default">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var html = '<img class="table-img" src="'+e.target.result+'">';
                    $('#image')
                        .html(html);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection


