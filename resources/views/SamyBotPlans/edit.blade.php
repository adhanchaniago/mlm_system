@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            SamyBot Plans
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       @include('flash::message')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($SamyBotPlans, ['route' => ['SamyBotPlans.update', $SamyBotPlans->id], 'method' => 'patch','files'=>true]) !!}
                        <div class="form-group col-sm-12">
                            {!! Form::label('name', 'Name:') !!}
                            {!! Form::text('name', $SamyBotPlans->name, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group col-sm-12">
                            <input type="file" accept="image/x-png,image/gif,image/jpeg,image/jpg,image/PNG" class="form-control" name="image" onchange="readURL(this)">
                            <div id="image">
                                @if(isset($SamyBotPlans->image))
                                    <img src="{{asset('public/avatars').'/'.$SamyBotPlans->image}}" class="edit-image">
                                @endif
                            </div>
                        </div>
                        <!-- Address Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::label('term', 'Term:') !!}
                            <select name="term" class="form-control">
                                <option value="" selected disabled>Select the Term</option>
                                <option value="month" <?php if($SamyBotPlans->term == 'month'){ echo "selected"; } ?>>Monthly</option>
                                <option value="year" <?php if($SamyBotPlans->term == 'year'){ echo "selected"; } ?>>Yearly</option>
                            </select>
                        </div>

                        <!-- Email Field -->
                        <div class="form-group col-sm-6">
                            {!! Form::label('amount_1', 'Amount per Unit:') !!}
                            {!! Form::text('amount_1', $SamyBotPlans->amount_1, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('plan_id_1', 'Stripe Plan For 1 Unit:') !!}
                            {!! Form::text('plan_id_1', $SamyBotPlans->plan_id_1, ['class' => 'form-control','readonly' => 'true']) !!}
                        </div>

                        <div class="form-group col-sm-6">
                            {!! Form::label('amount_5', 'Amount per 5 Device:') !!}
                            {!! Form::text('amount_5', $SamyBotPlans->amount_5, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('plan_id_5', 'Stripe Plan For 5 Devices:') !!}
                            {!! Form::text('plan_id_5', $SamyBotPlans->plan_id_5, ['class' => 'form-control','readonly' => 'true']) !!}
                        </div>

                        <div class="form-group col-sm-6">
                            {!! Form::label('amount_10', 'Amount per 10 Device:') !!}
                            {!! Form::text('amount_10', $SamyBotPlans->amount_10, ['class' => 'form-control'])  !!}
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('plan_id_10', 'Stripe Plan For 10 Devices:') !!}
                            {!! Form::text('plan_id_10', $SamyBotPlans->plan_id_10, ['class' => 'form-control','readonly' => 'true']) !!}
                        </div>


                        <div class="form-group col-sm-6">
                            {!! Form::label('amount_20', 'Amount per 20 Device:') !!}
                            {!! Form::text('amount_20', $SamyBotPlans->amount_20, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('plan_id_20', 'Stripe Plan For 20 Devices:') !!}
                            {!! Form::text('plan_id_20', $SamyBotPlans->plan_id_20, ['class' => 'form-control','readonly' => 'true']) !!}
                        </div>

                        <!-- Range Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::label('ad_feat', 'Range:') !!}
                            {!! Form::text('ad_feat', $SamyBotPlans->ad_feat, ['class' => 'form-control']) !!}
                        </div>

                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                            <a href="{!! route('SamyBotPlans.index') !!}" class="btn btn-default">Cancel</a>
                        </div>
                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var html = '<img class="edit-image" src="' + e.target.result + '">';
                    $('#image')
                        .html(html);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
