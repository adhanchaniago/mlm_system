<!-- Campaigns Field -->

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
        <option value="agency">Agency</option>
    </select>
</div>

<div class="form-group col-sm-12">
    {!! Form::label('term', 'Term:') !!}
    <select class="form-control" name="term">
        <option value="" selected disabled>Select a Term</option>
        <option value="month">Month</option>
        <option value="year">Year</option>
    </select>
</div>

<div class="form-group col-sm-12">
    {!! Form::label('amount', 'Amount:') !!}
    {!! Form::text('amount', null, ['class' => 'form-control','Placeholder' => 'Amount']) !!}
</div>

<div class="form-group col-sm-12">
    {!! Form::label('campaigns', 'Campaigns:') !!}
    {!! Form::text('campaigns', null, ['class' => 'form-control']) !!}
</div>



<!-- Contacts Field -->
<div class="form-group col-sm-12">
    {!! Form::label('contacts', 'Contacts:') !!} <button type="button" class="btn btn-success" onclick="unlimitedAffiliates()">Unlimited</button>
    <input type="text" class="form-control" id="unlmt" placeholder="Maximum Contacts"  onchange="Affiliates(this.value)">
    <input type="hidden" name="contacts" value="" id="mu">
</div>

<!-- Linkedin Accounts Field -->
<div class="form-group col-sm-12">
    {!! Form::label('linkedIn_accounts', 'Linkedin Accounts:') !!}
    {!! Form::text('linkedIn_accounts', null, ['class' => 'form-control']) !!}
</div>
<!-- Linkedin Accounts Field -->
<div class="form-group col-sm-12">
    {!! Form::label('automated_msg', 'Automated Message:') !!}
    {!! Form::text('automated_msg', null, ['class' => 'form-control']) !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('linkedinPlans.index') !!}" class="btn btn-default">Cancel</a>
</div>

<script>
    function unlimitedAffiliates() {
        var val = 'unlimited';
        $('#unlmt').val(val);
        $('#unlmt').attr('readonly','true');
        $('#mu').val(val);
    }
    function Affiliates(val) {
        $('#unlmt').val(val);
//        $('#unlmt').attr('readonly','true');
        $('#mu').val(val);
    }
</script>