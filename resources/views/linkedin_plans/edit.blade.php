@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Linkedin Plans
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($linkedinPlans, ['route' => ['linkedinPlans.update', $linkedinPlans->id], 'method' => 'patch']) !!}

                        <div class="form-group col-sm-12">
                            {!! Form::label('name', 'Name:') !!}
                            {!! Form::text('name', $linkedinPlans->name, ['class' => 'form-control','placeholder' => 'Plan Name']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            <label>Type</label>
                            <select class="form-control" name="type">
                                <option value="" selected disabled>Select Type</option>
                                <option value="starter" <?php if ($linkedinPlans->type == 'starter') {echo "selected";} ?>>Starter</option>
                                <option value="business" <?php if ($linkedinPlans->type == 'business') {echo "selected";} ?>>Business</option>
                                <option value="agency" <?php if ($linkedinPlans->type == 'agency') {echo "selected";} ?>>Agency</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('term', 'Term:') !!}
                            <select class="form-control" name="term">
                                <option value="" selected disabled>Select a Term</option>
                                <option value="month" <?php if ($linkedinPlans->term == 'month') { echo "selected";} ?>>Month</option>
                                <option value="year" <?php if ($linkedinPlans->term == 'year') { echo "selected"; } ?>>Year</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('amount', 'Amount:') !!}
                            {!! Form::text('amount', $linkedinPlans->amount, ['class' => 'form-control','Placeholder' => 'Amount']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('campaigns', 'Campaigns:') !!}
                            {!! Form::text('campaigns', $linkedinPlans->campaigns, ['class' => 'form-control']) !!}
                        </div>

                   <!-- Contacts Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::label('contacts', 'Contacts:') !!} <button type="button" class="btn btn-success" onclick="unlimitedAffiliates()">Unlimited</button>
                            <input type="text" value="{{$linkedinPlans->contacts}}" class="form-control" id="unlmt" placeholder="Maximum Contacts" onchange="Affiliates(this.value)">
                            <input type="hidden" value="{{$linkedinPlans->contacts}}" name="contacts" id="mu">
                        </div>

                        <!-- Linkedin Accounts Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::label('linkedIn_accounts', 'Linkedin Accounts:') !!}
                            {!! Form::text('linkedIn_accounts', $linkedinPlans->linkedIn_accounts, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group col-sm-12">
                            {!! Form::label('automated_msg', 'Automated Message:') !!}
                            {!! Form::text('automated_msg', $linkedinPlans->linkedIn_accounts, ['class' => 'form-control']) !!}
                        </div>


                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                            <a href="{!! route('linkedinPlans.index') !!}" class="btn btn-default">Cancel</a>
                        </div>

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection

<script>
    function unlimitedAffiliates()
    {
        var val = 'unlimited';
        $('#unlmt').val(val);
        $('#unlmt').attr('readonly','true');
        $('#mu').val(val);
    }
    function Affiliates(val)
    {
        $('#unlmt').val(val);
        $('#mu').val(val);
    }
</script>