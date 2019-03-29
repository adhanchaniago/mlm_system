@include('frontEnd.mainHeader')
<div class="container">
    <div class="text-center">
        <h1>{{trans('plan.choosePlans')}}</h1>
    </div>
    <div class="text-center">
        <div class="">
            <button type="button" class="btn-clr active-btn" onclick="changeTerm('one')">{{trans('plan.monthly')}}</button>
            <button type="button" class="btn-clr" onclick="changeTerm('two')">{{trans('plan.yearly')}}</button>
        </div>
    </div>
    <div class="text-center">
        <h3>{{trans('plan.save_20%')}}</h3>
    </div>

    <div class="row" id="plansList">
        @if($plans != "")
            @foreach($plans as $plan)
                @if($i%2==0)
                    <div class="col-md-4">
                        <div class="card1">
                            <div class="starter-bsns">
                                <h3 class="name-padding1">{{strtoupper($plan->type)}}</h3>
                            </div>
                            <div class="starter-middel1">
                                <h4>- {{trans('home.up_to')}} <b>{{$plan->levels}} {{trans('level.levels')}}</b> {{trans('home.of')}} {{trans('plan.commissions')}}</h4>
                                @if($plan->affiliates == 'unlimited ')
                                    <h4>- <b>{{$plan->affiliates}} {{trans('home.affiliates')}}</b></h4>
                                @else
                                    <h4>- {{trans('home.up_to')}} <b>{{$plan->affiliates}} {{trans('home.affiliates')}}</b></h4>
                                @endif
                                <h4>- {{trans('plan.unlimited_products')}}</h4>
                                <h4>- {{trans('plan.unlimited_sales')}}</h4>
                                <h4>- {{trans('plan.woocommerce_module')}}</h4>
                                <h4>- {{trans('plan.shopify_module')}}</h4>
                                @if($plan->commission == 0)
                                    <h4>- <b>{{trans('plan.no_commission')}}</b></h4>
                                @else
                                    <h4>- {{trans('plan.commissions')}}:  <b>{{$plan->commission}}%</b> {{trans('home.on')}} {{trans('plan.every_sale')}}</h4>
                                @endif
                            </div>
                            <div class="starter-middel2 rate-outermrgn">
                                <h2 class="rate-padding">${{$plan->amount}}/{{trans('myProfile.month')}}</h2>
                            </div>
                            <a href="{{url('register').'/'.$plan->id}}"><button><h4>{{trans('plan.order_now')}}</h4></button></a>

                        </div>
                    </div>
                @else
                    <div class="col-md-4 side-div-margin">
                        <div class="card">
                            <div class="starter">
                                <h3 class="name-padding">{{strtoupper($plan->type)}}</h3>
                            </div>
                            <div class="starter-middel1">
                                <h4>- {{trans('home.up_to')}} <b>{{$plan->levels}} {{trans('level.levels')}}</b> {{trans('home.of')}} {{trans('plan.commissions')}}</h4>
                                @if($plan->affiliates == 'unlimited ')
                                    <h4>- <b>{{$plan->affiliates}} {{trans('home.affiliates')}}</b></h4>
                                @else
                                    <h4>- {{trans('home.up_to')}} <b>{{$plan->affiliates}} {{trans('home.affiliates')}}</b></h4>
                                @endif
                                <h4>- {{trans('plan.unlimited_products')}}</h4>
                                <h4>- {{trans('plan.unlimited_sales')}}</h4>
                                <h4>- {{trans('plan.woocommerce_module')}}</h4>
                                <h4>- {{trans('plan.shopify_module')}}</h4>
                                @if($plan->commission == 0)
                                    <h4>- <b>{{trans('plan.no_commission')}}</b></h4>
                                @else
                                    <h4>- {{trans('plan.commissions')}}:  <b>{{$plan->commission}}%</b> {{trans('home.on')}} {{trans('plan.every_sale')}}</h4>
                                @endif
                            </div>

                            <div class="starter-middel2 rate-outermrgn">
                                <h2 class="rate-padding">${{$plan->amount}}/{{trans('myProfile.month')}}</h2>
                            </div>
                            <a href="{{url('register').'/'.$plan->id}}"><button><h4>{{trans('plan.order_now')}}</h4></button></a>

                        </div>
                    </div>
                @endif
                <?php
                $i++;
                ?>
            @endforeach
        @else
            <div>
                <center>
                    <h3><b>{{trans('home.no_plans')}}</b></h3>
                </center>
            </div>
        @endif
    </div>
</div>
@include('frontEnd.footer')
<script>
    $(function(){
        var $h3s = $('.btn-clr').click(function(){
            $h3s.removeClass('active-btn');
            $(this).addClass('active-btn');
        });
    });
    function changeTerm(val)
    {
        $.ajax({
            url: "{{url('changeTerm')}}"+"/"+val,
            success: function(result)
            {
                if (result!="")
                {
                    $('#plansList').html(result);
                }
                else
                {
                    var html = '<center><h3><b>{{trans('home.no_plans')}}</b></h3></center>';
                    $('#plansList').html(html);
                }
            }
        });
    }
</script>