<!-- Name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control','placeholder' => 'Plan Name']) !!}
</div>
<div class="form-group col-sm-12">
    <label>Type</label>
    <select class="form-control" name="type">
        <option value="" selected disabled>Select Type</option>
        <option value="starter">Starter</option>
        <option value="business">Business</option>
        <option value="growth">Growth</option>
    </select>
</div>
<!-- Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('amount', 'Amount:') !!}
    {!! Form::text('amount', null, ['class' => 'form-control','Placeholder' => 'Amount']) !!}
</div>
<!-- Stripe Plan  Field -->
<div class="form-group col-sm-6">
    {!! Form::label('stripe_plan_id', 'Stripe Plan id:') !!}
    {!! Form::text('stripe_plan_id', null, ['class' => 'form-control','Placeholder' => 'Eg: SASM1']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('levels', 'Maximum Levels:') !!}
    {!! Form::text('levels', null, ['class' => 'form-control','Placeholder' => 'Maximum Levels']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('affiliates', 'Maximum Affiliates:') !!} <button type="button" class="btn btn-success" onclick="unlimitedAffiliates()">Unlimited</button>
    <br/> <br/>
    <input type="text" name="affiliatess" class="form-control" placeholder="Maximum Affiliates" value="" id="unlmt" onchange="assignVal(this.value)">
    <input type="hidden" name="affiliates" value="" id="mu">
</div>

<div class="form-group col-sm-12">
    {!! Form::label('commission', 'Commission:') !!} <button type="button" class="btn btn-success" onclick="nocommission()">Zero Commission</button>
        <br/><br/>
    {!! Form::text('commission', null, ['class' => 'form-control','Placeholder' => 'Commission','id' => 'cmsn']) !!}
</div>

<!-- Term Field -->
<div class="form-group col-sm-12">
    {!! Form::label('term', 'Term:') !!}
    <select class="form-control" name="term">
        <option value="" selected disabled>Select a Term</option>
        <option value="month">Month</option>
        <option value="year">Year</option>
    </select>
</div>
<!-- Sharing Amount Field -->

<!-- Image Field -->
<div class="form-group col-sm-12">
    {!! Form::label('image', 'Image:') !!}
    <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg,image/PNG" class="form-control">
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('plantables.index') !!}" class="btn btn-default">Cancel</a>
</div>


    <script>
        function unlimitedAffiliates() {
            var val = 'unlimited';
            $('#unlmt').val(val);
            $('#unlmt').attr('readonly','true');
            $('#mu').val(val);
        }
        function nocommission() {
            var val = 0;
            $('#cmsn').val(val);
        }
        function assignVal(val) {
            $('#mu').val(val);
        }
    </script>
