@include('frontEnd.header')
<div class="container">
    <div class="row">
        <br>
        @include('flash::message')
        <div class="col-md-12 col-xs-12 col-sm-12">
            <h1 class="text-center">{{trans('samybot/plans.build_your_plan')}}</h1>
            <div class="col-md-12 col-xs-12 col-sm-12 plans_toggle">
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="col-md-6 col-sm-6 col-xs-6 plans_zero_padding">
                        <button type="button" class="plans_button term-btn" id="monthly" onclick="changePlan('month')">{{trans('samybot/plans.monthly')}}</button>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6 plans_zero_padding">
                        <button type="button" class="plans_button term-btn" onclick="changePlan('year')">{{trans('samybot/plans.yearly')}}</button>
                    </div>
                </div>
            </div>
            <h4 class="text-center">{{trans('plan.save_20%')}}</h4>
        </div>
    </div>
    <div class="row">
        <div id="monthly_plan">
            <form method="post" action="{{url('samybot/proceed_to_order')}}" id="plan_form">
                {{ csrf_field() }}
                <input type="hidden" name="rowCount" id="rowCount" value="{{$rowCount}}">
                <input type="hidden" class="ResetClass" name="number_of_devices" id="number_of_devices" value="0">
                <input type="hidden" name="grand_activation_charge" id="grand_activation_charge" value="0">
                <input type="hidden" name="final_grand_total" id="final_grand_total" value="0">
                @if($monthly_plans->count() >= 1)
                    @foreach($monthly_plans  as $plans)
                        <div class="col-md-12 col-sm-12 col-xs-12 plans_toggle ">
                            <div class="col-md-10 col-sm-12 col-xs-12 plans_division">
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    @if(isset($plans->image) || !empty($plans->image))
                                        <img src="{{asset('public/avatars/').'/'.$plans->image}}" class="plans_images">
                                    @else
                                        <img src="{{asset('public/avatars/default.jpg')}}" class="plans_images">
                                    @endif
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="col-md-3 col-sm-3 col-xs-6 plans_button_padding">
                                        <button type="button" class="plans_button month_plan_btn{{$plans->id}} default-btn" onclick="setValues('{{$plans->id}}',1)">1 {{trans('samybot/common.unit')}}</button>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6 plans_button_padding">
                                        <button type="button" class="plans_button month_plan_btn{{$plans->id}}" onclick="setValues('{{$plans->id}}',5)">5 {{trans('samybot/common.pack')}}</button>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6 plans_button_padding">
                                        <button type="button" class="plans_button month_plan_btn{{$plans->id}}" onclick="setValues('{{$plans->id}}',10)">10 {{trans('samybot/common.pack')}}</button>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6 plans_button_padding">
                                        <button type="button" class="plans_button month_plan_btn{{$plans->id}}" onclick="setValues('{{$plans->id}}',20)">20 {{trans('samybot/common.pack')}}</button>
                                    </div>
                                </div>
                                <input type="hidden" name="stripe_plan{{$plans->id}}"  id="stripe_plan{{$plans->id}}" class="">
                                <input type="hidden" name="selected_plan{{$plans->id}}"  id="selected_plan{{$plans->id}}" class="">
                                <input type="hidden" name="selected_qty{{$plans->id}}"   id="selected_qty{{$plans->id}}" class="ResetClass">
                                <input type="hidden" name="selected_price{{$plans->id}}" id="selected_price{{$plans->id}}" class="">
                                <input type="hidden" name="selected_pack{{$plans->id}}"  id="selected_pack{{$plans->id}}" class="device ResetClass1" value="0">
                                <input type="hidden" name="selected_plan_total{{$plans->id}}"  id="selected_plan_total{{$plans->id}}" class="ResetClass">
                                <div class="col-md-9 col-sm-12 col-xs-12">
                                    <div class="col-md-5 col-sm-5 col-xs-6">
                                        <h4 class="text-left">{{$plans->name}}</h4>
                                        <p class="plans_discription">{{trans('samybot/plans.description_1')}}<b>{{trans('samybot/plans.up_to')}} {{$plans->ad_feat}} ft</b>
                                            {{trans('samybot/plans.description_2')}}</p>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6">
                                        <h4>{{trans('samybot/common.price')}}</h4>
                                        <h4>$<span id="price{{$plans->id}}">{{$plans->amount_1}}</span>/{{trans('samybot/common.month')}}</h4>
                                        <p>$<span id="price_per_device_{{$plans->id}}">{{$plans->amount_1}}</span>/{{trans('samybot/common.device')}}</p>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-8" style="padding: 0;">
                                        <h4>{{trans('samybot/common.quantity')}}</h4>
                                        {{--<form class="plans_form">--}}
                                        <div class="value-button" id="decrease" onclick="decreaseValue({{$plans->id}})" title="Decrease Value">
                                            <i class="fa fa-minus"></i>
                                        </div>
                                        <input type="number" class="number ResetClass" id="number{{$plans->id}}" value="0"/>
                                        <div class="value-button" id="increase" onclick="increaseValue({{$plans->id}})" title="Increase Value">
                                            <i class="fa fa-plus"></i>
                                        </div>
                                        {{--</form>--}}
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-4">
                                        <h4>{{trans('samybot/common.total')}}</h4>
                                        <h4>$<span id="total{{$plans->id}}" class="totalAmount ResetClass3">0</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(".month_plan_btn"+{{$plans->id}}).click(function(e) {
                                $(".month_plan_btn"+{{$plans->id}}).removeClass("samy_btn_active");
                                $(this).addClass("samy_btn_active");
                            });
                        </script>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <center><h2>{{trans('home.no_plans')}}</h2></center>
                    </div>
                @endif
                @if(isset($activation))
                    <div class="row">
                        <div class="col-md-10">
                            <div class="col-md-4 plans_float_align">
                                <table class="plans_table">
                                    <tbody>
                                    <tr>
                                        <td class="col-md-6"><h4>{{trans('samybot/plans.your_plan')}}</h4></td>
                                        <td class="col-md-6 text-right"><h4>$<span  id="total_plan_amount" class="ResetClass3">0</span></h4></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-6"><h4>{{trans('samybot/common.activation')}}($20/{{trans('samybot/common.device')}})</h4></td>
                                        <td class="col-md-6 text-right"><h4>$<span id="totalDevice" class="ResetClass3">0</span></h4></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-6"><h4 class="plans_total_division">{{trans('samybot/plans.TOTAL')}}</h4></td>
                                        <td class="col-md-6 text-right"><h4 class="plans_total_division">$<span id="grand_total" class="ResetClass3">0</span></h4></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="col-md-12">
                                    <button class="plans_order-button" type="button" id="order_submit" onclick="submitForm()">{{trans('samybot/plans.order_now')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </form>
        </div>
        {{---------------------------------------------------------------------------------------------------------------------------}}

        <div id="annual_plan" style="display:none;">
            <form method="post" action="{{url('samybot/proceed_to_order')}}" id="annual_plan_form">
                {{ csrf_field() }}
                <input type="hidden" name="rowCount" id="annual_rowCount" value="{{$rowCount}}">
                <input type="hidden" class="ResetClass" name="number_of_devices" id="annual_number_of_devices" value="0">
                <input type="hidden" name="grand_activation_charge" id="annual_grand_activation_charge" value="0">
                <input type="hidden" name="final_grand_total" id="annual_final_grand_total" value="0">
                @if($yearly_plans->count() >= 1)
                    @foreach($yearly_plans  as $annual)
                        <div class="col-md-12 col-sm-12 col-xs-12 plans_toggle ">
                            <div class="col-md-10 col-sm-12 col-xs-12 plans_division">
                                <div class="col-md-3 col-sm-3 col-xs-12">
                                    @if(isset($annual->image) || !empty($annual->image))
                                        <img src="{{asset('public/avatars/').'/'.$annual->image}}" class="plans_images">
                                    @else
                                        <img src="{{asset('public/avatars/default.jpg')}}" class="plans_images">
                                    @endif
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="col-md-3 col-sm-3 col-xs-6 plans_button_padding">
                                        <button type="button" class="plans_button annual_plan_btn{{$annual->id}} default-btn" onclick="annual_setValues('{{$annual->id}}',1)">1 {{trans('samybot/common.unit')}}</button>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6 plans_button_padding">
                                        <button type="button" class="plans_button annual_plan_btn{{$annual->id}}" onclick="annual_setValues('{{$annual->id}}',5)">5 {{trans('samybot/common.pack')}}</button>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6 plans_button_padding">
                                        <button type="button" class="plans_button annual_plan_btn{{$annual->id}}" onclick="annual_setValues('{{$annual->id}}',10)">10 {{trans('samybot/common.pack')}}</button>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6 plans_button_padding">
                                        <button type="button" class="plans_button annual_plan_btn{{$annual->id}}" onclick="annual_setValues('{{$annual->id}}',20)">20 {{trans('samybot/common.pack')}}</button>
                                    </div>
                                </div>

                                <input type="hidden" name="selected_plan{{$annual->id}}" id="annual_selected_plan{{$annual->id}}" class="">
                                <input type="hidden" name="selected_qty{{$annual->id}}" id="annual_selected_qty{{$annual->id}}" class="ResetClass">
                                <input type="hidden" name="stripe_plan{{$annual->id}}" id="annual_stripe_plan{{$annual->id}}" class="">
                                <input type="hidden" name="selected_price{{$annual->id}}" id="annual_selected_price{{$annual->id}}">
                                <input type="hidden" name="selected_pack{{$annual->id}}" id="annual_selected_pack{{$annual->id}}" class="annual_device ResetClass1" value="0">
                                <input type="hidden" name="selected_plan_total{{$annual->id}}" id="annual_selected_plan_total{{$annual->id}}" class="ResetClass">
                                <div class="col-md-9 col-sm-12 col-xs-12">
                                    <div class="col-md-5 col-sm-5 col-xs-6">
                                        <h4 class="text-left">{{$annual->name}}</h4>
                                        <p class="plans_discription">{{trans('samybot/plans.description_1')}}<b>{{trans('samybot/plans.up_to')}} {{$annual->ad_feat}} ft</b>
                                            {{trans('samybot/plans.description_2')}}</p>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-6">
                                        <h4>{{trans('samybot/common.price')}}</h4>
                                        <h4>$<span id="annual_price{{$annual->id}}">{{$annual->amount_1}}</span>/{{trans('samybot/common.year')}}</h4>
                                        <p>$<span id="annual_price_per_device_{{$annual->id}}">{{$annual->amount_1}} </span>/{{trans('samybot/common.device')}}/{{trans('samybot/common.month')}}</p>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-8" style="padding: 0;">
                                        <h4>{{trans('samybot/common.quantity')}}</h4>
                                        {{--<form class="plans_form">--}}
                                        <div class="value-button" id="annual_decrease" onclick="annual_decreaseValue({{$annual->id}})" title="Decrease Value">
                                            <i class="fa fa-minus"></i>
                                        </div>
                                        <input type="number" class="number ResetClass" id="annual_number{{$annual->id}}" value="0"/>
                                        <div class="value-button" id="annual_increase" onclick="annual_increaseValue({{$annual->id}})" title="Increase Value">
                                            <i class="fa fa-plus"></i>
                                        </div>
                                        {{--</form>--}}
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-4">
                                        <h4>{{trans('samybot/common.total')}}</h4>
                                        <h4>$<span id="annual_total{{$annual->id}}" class="annual_totalAmount ResetClass3">0</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(".annual_plan_btn"+{{$annual->id}}).click(function(e) {
                                $(".annual_plan_btn"+{{$annual->id}}).removeClass("samy_btn_active");
                                $(this).addClass("samy_btn_active");
                            });
                        </script>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <center><h2>{{trans('home.no_plans')}}</h2></center>
                    </div>
                @endif
                {{---------------------------------------------------------------------------------------------------------------------------}}
                @if(isset($activation))
                    <div class="row">
                        <div class="col-md-10">
                            <div class="col-md-4 plans_float_align">
                                <table class="plans_table">
                                    <tbody>
                                    <tr>
                                        <td class="col-md-6"><h4>{{trans('samybot/plans.your_plan')}}</h4></td>
                                        <td class="col-md-6 text-right"><h4>$<span  id="annual_total_plan_amount" class="ResetClass3">0</span></h4></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-6"><h4>{{trans('samybot/common.activation')}}($20/{{trans('samybot/common.device')}})</h4></td>
                                        <td class="col-md-6 text-right"><h4>$<span id="annual_totalDevice" class="ResetClass3">0</span></h4></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-6"><h4 class="plans_total_division">{{trans('samybot/plans.TOTAL')}}</h4></td>
                                        <td class="col-md-6 text-right"><h4 class="plans_total_division">$<span id="annual_grand_total" class="ResetClass3">0</span></h4></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="col-md-12">
                                    <button class="plans_order-button" id="order_submit" type="button" onclick="annual_submitForm()">{{trans('samybot/plans.order_now')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(".default-btn").trigger( "click" );
        $("#monthly").trigger( "click" );
        planSum();
    });

    function annual_calculate_device() {
        var totalDevice = 0;
        $('.annual_device').each(function(){
            var numbers = this.id;
            var id = parseFloat(numbers.match(/-*[0-9]+/));
            var qty = $('#annual_selected_qty'+id).val();
            var pack = $('#annual_selected_pack'+id).val();
            if(qty == 0 || qty == null || qty == ""){
                totalDevice = totalDevice ;
            }else{
                var devices = parseInt(qty) * parseInt(pack);
                totalDevice += parseInt(devices);
            }
        });
        $('#annual_number_of_devices').val(totalDevice);
    }

    @if(isset($activation))
    function annual_calculate_activation_charge() {
        var activation_charge = '{{$activation->amount}}';
        var totalDevices = $('#annual_number_of_devices').val();
        var totalactvation = parseInt(activation_charge) * parseInt(totalDevices);
        $('#annual_totalDevice').text(totalactvation);
        $('#annual_grand_activation_charge').val(totalactvation);
        annual_planSum();
    }
    @endif
    function annual_planSum() {
        var totalAmount = 0;
        var totalDevice = parseInt($('#annual_totalDevice').text());
        $('.annual_totalAmount').each(function(){
            totalAmount += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
        });
        $('#annual_total_plan_amount').text(totalAmount);
        var grand_total = totalAmount + totalDevice;
        $('#annual_grand_total').text(grand_total);
        $('#annual_final_grand_total').val(grand_total);
    }

    function annual_setValues(plan,pack) {
        var qty = $('#annual_number'+plan).val();
        $.ajax({
            url: "{{url('samybot/fetch_plan_data')}}"+'/'+plan+'/'+pack,
            cache: false,
            success: function(response){
                $('#annual_stripe_plan'+plan).val(response.stripe_paln_id);
                $('#annual_selected_plan'+plan).val(response.plan);
                $('#annual_selected_price'+plan).val(response.amount);
                $('#annual_selected_pack'+plan).val(pack);
                $('#annual_selected_qty'+plan).val(qty);
                $('#annual_price'+plan).text(response.amount);
                var plan_total = parseFloat(response.amount)*parseInt(qty);
                $('#annual_selected_plan_total'+plan).val(plan_total);
                $('#annual_total'+plan).text(plan_total);

                var per_device1 = parseInt(response.amount)/ parseInt(pack);
                var per_device = (parseInt(per_device1) / 12).toFixed(2);
                $('#annual_price_per_device_'+plan).text(per_device);
                annual_planSum();
                annual_calculate_device();
                annual_calculate_activation_charge();
            }
        });
    }

    function annual_increaseValue(id) {
        var value = parseInt(document.getElementById('annual_number'+id).value, 10);
        var price = $('#annual_price'+id).text();
        value = isNaN(value) ? 0 : value;
        value++;
        var sum = parseFloat(price) * parseFloat(value);
        document.getElementById('annual_number'+id).value = value;
        $('#annual_total'+id).text(sum);
        $('#annual_selected_qty'+id).val(value);
        $('#annual_selected_plan_total'+id).val(sum);
        annual_calculate_device();
        annual_calculate_activation_charge();
        annual_planSum();
    }

    function annual_decreaseValue(id) {
        var price = $('#annual_price'+id).text();
        var value = parseInt(document.getElementById('annual_number'+id).value, 10);
        value = isNaN(value) ? 0 : value;
        value < 1 ? value = 1 : '';
        value--;
        var sum = parseFloat(price) * parseFloat(value);
        document.getElementById('annual_number'+id).value = value;
        $('#annual_total'+id).text(sum);
        $('#annual_selected_qty'+id).val(value);
        $('#annual_selected_plan_total'+id).val(sum);
        annual_planSum();
        annual_calculate_device();
        annual_calculate_activation_charge();
    }


    //    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~Annual Plan Ends Here!~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


    function calculate_device() {
        var totalDevice = 0;
        $('.device').each(function(){
            var numbers = this.id;
            var id = parseFloat(numbers.match(/-*[0-9]+/));
            var qty = $('#selected_qty'+id).val();
            var pack = $('#selected_pack'+id).val();
            if(qty == 0 || qty == null || qty == ""){
                totalDevice = totalDevice ;
            }else{
                var devices = parseInt(qty) * parseInt(pack);
                totalDevice += parseInt(devices);
            }
        });
        $('#number_of_devices').val(totalDevice);
    }

    @if(isset($activation))
    function calculate_activation_charge() {
        var activation_charge = '{{$activation->amount}}';
        var totalDevices = $('#number_of_devices').val();
        var totalactvation = parseInt(activation_charge) * parseInt(totalDevices);
        $('#totalDevice').text(totalactvation);
        $('#grand_activation_charge').val(totalactvation);
        planSum();
    }
    @endif
    function planSum() {
        var totalAmount = 0;
        var totalDevice = parseInt($('#totalDevice').text());
        $('.totalAmount').each(function(){
            totalAmount += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
        });
        $('#total_plan_amount').text(totalAmount);
        var grand_total = totalAmount + totalDevice;
        $('#grand_total').text(grand_total);
        $('#final_grand_total').val(grand_total);
    }

    function setValues(plan,pack) {
        var qty = $('#number'+plan).val();
        $.ajax({
            url: "{{url('samybot/fetch_plan_data')}}"+'/'+plan+'/'+pack,
            cache: false,
            success: function(response){
                $('#stripe_plan'+plan).val(response.stripe_paln_id);
                $('#selected_plan'+plan).val(response.plan);
                $('#selected_price'+plan).val(response.amount);
                $('#selected_pack'+plan).val(pack);
                $('#selected_qty'+plan).val(qty);
                $('#price'+plan).text(response.amount);
                var plan_total = parseFloat(response.amount)*parseInt(qty);
                $('#selected_plan_total'+plan).val(plan_total);
                $('#total'+plan).text(plan_total);
                if(response.term == "month"){
                    var per_device = parseInt(response.amount)/ parseInt(pack);
                    $('#price_per_device_'+plan).text(per_device);
                }else{
                    var per_device1 = parseInt(response.amount)/ parseInt(pack);
                    var per_device = (parseInt(per_device1) / 12).toFixed(2);
                    $('#price_per_device_'+plan).text(per_device);
                }
                planSum();
                calculate_device();
                calculate_activation_charge();
            }
        });
    }

    function increaseValue(id) {
        var value = parseInt(document.getElementById('number'+id).value, 10);
        var price = $('#price'+id).text();
        value = isNaN(value) ? 0 : value;
        value++;
        var sum = parseFloat(price) * parseFloat(value);
        document.getElementById('number'+id).value = value;
        $('#total'+id).text(sum);
        $('#selected_qty'+id).val(value);
        $('#selected_plan_total'+id).val(sum);
        calculate_device();
        calculate_activation_charge();
        planSum();
    }

    function decreaseValue(id) {
        var price = $('#price'+id).text();
        var value = parseInt(document.getElementById('number'+id).value, 10);
        value = isNaN(value) ? 0 : value;
        value < 1 ? value = 1 : '';
        value--;
        var sum = parseFloat(price) * parseFloat(value);
        document.getElementById('number'+id).value = value;
        $('#total'+id).text(sum);
        $('#selected_qty'+id).val(value);
        $('#selected_plan_total'+id).val(sum);
        planSum();
        calculate_device();
        calculate_activation_charge();
    }

    function changePlan(term) {
        if(term == "month"){
            $('#monthly_plan').show();
            $('#annual_plan').hide();
            $('.ResetClass').val(0);
            $('.ResetClass1').val(1);
            $('.ResetClass3').text(0);
        }else{
            $('#monthly_plan').hide();
            $('#annual_plan').show();
            $('.ResetClass').val(0);
            $('.ResetClass3').text(0);
        }
    }

    $(".term-btn").click(function(e) {
        $(".term-btn").removeClass("samy_btn_active");
        $(this).addClass("samy_btn_active");
    });

    function submitForm() {
        var count = $('#number_of_devices').val();
        if(count == "" || count == 0){
            alert('Select atleast one plan');
        }else{
            $('#plan_form').submit();
        }
    }
    function annual_submitForm() {
        var count = $('#annual_number_of_devices').val();
        if(count == "" || count == 0){
            alert('Select atleast one plan');
        }else{
            $('#annual_plan_form').submit();
        }
    }
</script>
@include('frontEnd.mainFooter')