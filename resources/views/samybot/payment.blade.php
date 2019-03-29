@if(Auth::user())
    @include('frontEnd.admin_header')
@else
    @include('frontEnd.header')
@endif
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQ_zcalLsl2Lrma87qgAs9QtM-0NQLmYs&libraries=places&callback=initAutocomplete"
        async defer></script>
<style>
    .thirtypx {
        height: 30px;
    }

    .tenpx {
        height: 10px;
    }

    .btn-row {
        margin-top: 5%;
        float: right;
    }
    .button-row
    {
        width: 45%;
        margin-top: 5%;
    }

    td, th {
        padding: 0px 15px;
    }

    tr {
        line-height: 2;
    }

    .stripe-pay_section {
        margin: 10%;
    }

    .activation_model {
        float: right;
        margin-top: 20px;
    }
</style>
<div class="container">
    <div class="row section2_numbers">
        <div class="col-md-12 stripe-pay_section">
            <center>
                @include('flash::message')
                <h1>{{trans('stripe.pay_with_stripe')}}</h1></center>
            <div class="panel">
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" id="payment-form" role="form" action="{!! URL('samybot/stripe') !!}">
                        {{ csrf_field() }}
                        <center>
                            @if ($message = Session::get('success'))
                                <div class="custom-alerts alert alert-success fade in">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                                    {!! $message !!}
                                </div>
                                <?php Session::forget('success');?>
                            @endif
                            @if ($message_verification = Session::get('activated'))
                                <div class="custom-alerts alert alert-success fade in">
                                    <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true"></button>
                                    {!! $message_verification !!}
                                </div>
                                <?php Session::forget('activated');?>
                            @endif
                            @if ($message = Session::get('error'))
                                <div class="custom-alerts alert alert-danger fade in">
                                    <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true"></button>
                                    {!! $message !!}
                                </div>
                                <?php Session::forget('error');?>
                            @endif
                        </center>
                        @if($stripe_card == "" || empty($stripe_card))
                            <center><h3>{{trans('card.card_details')}}</h3></center>
                            <div class="form-group{{ $errors->has('card_no') ? ' has-error' : '' }}">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <input id="card_no" type="text" class="form-control" placeholder="{{trans('card.card_number')}}" name="card_no" value="{{ old('card_no') }}" autofocus>
                                    @if ($errors->has('card_no'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('card_no') }}</strong>
                                        </span>
                                    @endif
                                    <p class="help-block" id="cardNo">{{trans('card.card_number_required')}}</p>
                                </div>

                            </div>
                            <div class="form-group{{ $errors->has('ccExpiryMonth') ? ' has-error' : '' }}">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <select id="ccExpiryMonth" type="text" class="form-control" name="ccExpiryMonth" value="{{ old('ccExpiryMonth') }}" autofocus>
                                        <?php
                                        for ($m=1; $m<=12; $m++) {
                                            $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
                                            if($m < 10){
                                                echo "<option value=\"0$m\">$month</option>";
                                            }else{
                                                echo "<option value=\"$m\">$month</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    @if ($errors->has('ccExpiryMonth'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('ccExpiryMonth') }}</strong>
                                        </span>
                                    @endif
                                    <p class="help-block" id="ExpiryMonth">{{trans('card.expire_month_required')}}</p>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('ccExpiryYear') ? ' has-error' : '' }}">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <select id="ccExpiryYear" class="form-control" name="ccExpiryYear" value="{{ old('ccExpiryYear') }}" autofocus>
                                        <?php
                                        $date_future = date("Y", strtotime('+12 year'));
                                        $date_year = date("Y");
                                        for($i=$date_year;$i<$date_future;$i++){
                                            if($date_year == $i){
                                                echo "<option value=\"$i\" selected=\"selected\">$i</option> \n";
                                            } else {
                                                echo "<option value=\"$i\">$i</option> \n";
                                            }
                                        }
                                        ?>
                                    </select>
                                    @if ($errors->has('ccExpiryYear'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('ccExpiryYear') }}</strong>
                                        </span>
                                    @endif
                                    <p class="help-block" id="ExpiryYear">{{trans('card.expire_year_required')}}</p>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('cvvNumber') ? ' has-error' : '' }}">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <input id="cvvNumber" type="text" class="form-control" placeholder="{{trans('card.cvv')}}" name="cvvNumber" value="{{ old('cvvNumber') }}" autofocus>
                                    @if ($errors->has('cvvNumber'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('cvvNumber') }}</strong>
                                        </span>
                                    @endif
                                    <p class="help-block" id="cvv_Number">{{trans('card.cvv_required')}}</p>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    @if(isset($total))
                                        <input id="amount" readonly type="text" class="form-control" name="amount" value="&dollar;{{ $total }}" autofocus>
                                        @if ($errors->has('amount'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                        @endif
                                    @else
                                        <input id="amount" readonly type="text" class="form-control" name="amount" value="&dollar;{{ $amount }}" autofocus>
                                        @if ($errors->has('amount'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                        @endif
                                    @endif

                                </div>
                            </div>
                        @else
                            <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    @if(isset($total))
                                        <input id="amount" readonly type="text" class="form-control" name="amount"
                                               value="&dollar;{{ $total }}" autofocus>
                                        @if ($errors->has('amount'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                        @endif
                                    @else
                                        <input id="amount" readonly type="text" class="form-control" name="amount"
                                               value="&dollar;{{ $amount }}" autofocus>
                                        @if ($errors->has('amount'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <center>
                                <h2>{{trans('stripe.saved_card')}}</h2>
                                <div class="form-group">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <input type="text" readonly class="form-control" value="{{$show_card_number}}">
                                        <input type="hidden" readonly name="fingerprint" value="{{$stripe_card['fingerprint']}}">
                                        <input type="hidden" readonly name="cardNo" value="{{$stripe_card['id']}}">
                                    </div>
                                </div>
                            </center>
                        @endif
                        <div class="form-group">
                            <center>
                                <button type="button" class="btn-save-level button-row" id="checkout">
                                    {{trans('stripe.pay_with_stripe')}}
                                </button>
                            </center>
                        </div>
                        <div class="modal fade" id="stripeModal" role="dialog">
                            <div class="modal-dialog add-level-modal">
                                <!-- Modal content-->
                                <div class="modal-content add-level-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <center>
                                            <h2 class="modal-title"><b>{{trans('stripe.payment_overview')}}</b></h2>
                                        </center>
                                    </div>
                                    <div class="modal-body stripe-modal-body">
                                        <table class="col-md-12 col-xs-12 summary table-bordered"
                                               style="text-align: right">
                                            <tbody style="border: 1px solid">
                                            @foreach($plans as $plan)
                                                <?php
                                                $device = $plan->unit * $plan->quantity;
                                                $current = App\Models\SamyBotPlans::whereId($plan->plan)->first();
                                                $start = date('d/m/Y', time());
                                                if ($current->term == 'month') {
                                                    $expire = date('d/m/Y', strtotime("+30 days"));
                                                } else {
                                                    $expire = date('d/m/Y', strtotime("+365 days"));
                                                }
                                                ?>
                                                <tr>
                                                    <td class="text-left">{{$current->name}}</td>
                                                    <td class="text-center">{{$device}}</td>
                                                    <td class="text-center">{{$plan->price}}</td>
                                                    <td>{{$start}}</td>
                                                    <td>{{$expire}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <table class="table-bordered activation_model">
                                            <tbody>
                                            <tr>
                                                <td colspan="4">{{trans('samybot/common.activation')}}(&dollar;{{$act_amt}}/{{trans('samybot/checkout.device')}})</td>
                                                <td class="text-right">&dollar; {{$activation_charge}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">{{trans('samybot/checkout.shipping')}}(&dollar;{{$ship_amt}}/{{trans('samybot/checkout.device')}})</td>
                                                <td class="text-right">&dollar;<span
                                                            id="shipping_charge">{{$total_ShipAmt}}</span></td>
                                            </tr>
                                            <tr class="tenpx">
                                                <td colspan="5"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4"><b>{{trans('home.total')}}</b></td>
                                                <td class="text-right">&dollar;<span
                                                            id="grand_total">{{$total}}</span></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div class="row">
                                                <br><br/>
                                                <div class="col-md-12">
                                                    <button type="submit" id="paywithStripe"
                                                            class="btn-save-level btn-row">{{trans('home.proceed_to_pay')}}</button>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="LoaderBalls">
                        <div class="LoaderBalls__item"></div>
                        <div class="LoaderBalls__item"></div>
                        <div class="LoaderBalls__item"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('frontEnd.footer')
<script>
    // Restricts input for each element in the set of matched elements to the given inputFilter.
    (function($) {
        $.fn.inputFilter = function(inputFilter) {
            return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                }
            });
        };
    }(jQuery));

    $("#card_no").inputFilter(function(value) {
        return /^-?\d*$/.test(value);
    });
    $("#cvvNumber").inputFilter(function(value) {
        return /^-?\d*$/.test(value);
    });
    $("#card_no").focusin(function() {
        $("input[id=card_no]").attr("maxlength", "16");
    });
    $("#cvvNumber").focusin(function() {
        $("input[id=cvvNumber]").attr("maxlength", "4");
    });
    $('#checkout').click(function () {
        if ($('#card_no').val() == '') {
            $('#cardNo').css('display', 'block');
        }
        else if ($('#ccExpiryMonth').val() == '') {
            $('#ExpiryMonth').css('display', 'block');
        }
        else if ($('#ccExpiryYear').val() == '') {
            $('#ExpiryYear').css('display', 'block');
        }
        else if ($('#cvvNumber').val() == '') {
            $('#cvv_Number').css('display', 'block');
        }
        else {
            $('#stripeModal').modal('show');
        }
    });
    $('#paywithStripe').click(function () {
        $('.LoaderBalls').css('display', 'flex');
        $('#stripeModal').css('display', 'none');
    });
</script>