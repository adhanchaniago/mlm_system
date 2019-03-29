@extends('layouts.app')

@section('css')
    <style>
        td,th{
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <section class="content-header">
        <h1>
            Company
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="col-md-12" style="padding-left: 20px">
                    @include('companies.show_fields')
                    <a href="{{\Illuminate\Support\Facades\URL::previous()}}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
