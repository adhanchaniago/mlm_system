<div class="form-group col-sm-12">
    <label>Card Number: </label>
    <input type="text" class="form-control" name="card_no" placeholder="Card Number">
    <input type="hidden" value="{{Auth::user()->company_id}}" name="company_id">
</div>
<div class="form-group col-sm-12">
    <label>Expiry Month: </label>
    <input type="text" class="form-control" name="ccExpiryMonth" placeholder="Expiry Month">
</div>
<div class="form-group col-sm-12">
    <label>Expiry year: </label>
    <input type="text" class="form-control" name="ccExpiryYear" placeholder="Expiry Year">
</div>
<div class="form-group col-sm-12">
    <label>CVV: </label>
    <input type="password" class="form-control" name="cvvNumber" placeholder="CVV">
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('companies.index') !!}" class="btn btn-default">Cancel</a>
</div>
