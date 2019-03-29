@include('frontEnd.mainHeader')
<div class="container">
    <div class="text-center">
        <h1>{{trans('plan.choosePlans')}}</h1>
    </div>
    <div class="text-center">
        <div class="">
            <button type="button" class="btn-clr active-btn" onclick="changeTerm('month')">{{trans('plan.monthly')}}</button>
            <button type="button" class="btn-clr" onclick="changeTerm('year')">{{trans('plan.yearly')}}</button>
        </div>
    </div>
    <div class="text-center">
        <h3>{{trans('plan.save_20%')}}</h3>
    </div>
    <?php $i = 1; $j = 1; ?>
    <div class="col-md-10 col-md-offset-1" id="monthly">
        @if($monthly_plans != "")
            @foreach($monthly_plans as $plan)
                @if($i%2==0)
                    <div class="col-md-4">
                        <div class="card1">
                            <div class="starter-bsns">
                                <h3 class="name-padding1">{{strtoupper($plan->type)}}</h3>
                            </div>
                            <div class="starter-middel1">
                                @if($plan->campaigns == 1)
                                <h4>- {{$plan->campaigns}} campaign at a time</h4>
                                @else
                                <h4>- {{$plan->campaigns}} simultaneous campaigns</h4>
                                @endif
                                <h4>- {{$plan->contacts}} contacts/month max</h4>
                                <h4>- {{$plan->automated_msg}} automated messages</h4>
                                <h4>- {{$plan->linkedIn_account}} LinkedIn account</h4>
                            </div>
                            <div class="starter-middel2 rate-outermrgn">
                                <h3 class="rate-padding">${{$plan->amount}}/{{trans('myProfile.month')}}</h3>
                            </div>
                            <a href="{{url('samylinkedIn/checkout').'/'.$plan->id}}"><button><h4>{{trans('plan.order_now')}}</h4></button></a>

                        </div>
                    </div>
                @else
                    <div class="col-md-4 side-div-margin">
                        <div class="card">
                            <div class="starter">
                                <h3 class="name-padding">{{strtoupper($plan->type)}}</h3>
                            </div>
                            <div class="starter-middel1">
                                @if($plan->campaigns == 1)
                                    <h4>- {{$plan->campaigns}} campaign at a time</h4>
                                @else
                                    <h4>- {{$plan->campaigns}} simultaneous campaigns</h4>
                                @endif
                                <h4>- {{$plan->contacts}} contacts/month max</h4>
                                <h4>- {{$plan->automated_msg}} automated messages</h4>
                                <h4>- {{$plan->linkedIn_account}} LinkedIn account</h4>
                            </div>
                            <div class="starter-middel2 rate-outermrgn">
                                <h3 class="rate-padding">${{$plan->amount}}/{{trans('myProfile.month')}}</h3>
                            </div>
                            <a href="{{url('samylinkedIn/checkout').'/'.$plan->id}}"><button><h4>{{trans('plan.order_now')}}</h4></button></a>
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
   {{--`````````````````````````````````````````````````````````````````````--}}
    <div class="col-md-10 col-md-offset-1" style="display: none" id="anuual">
        @if($yearly_plans != "")
            @foreach($yearly_plans as $anuual)
                @if($j%2==0)
                    <div class="col-md-4">
                        <div class="card1">
                            <div class="starter-bsns">
                                <h3 class="name-padding1">{{strtoupper($anuual->type)}}</h3>
                            </div>
                            <div class="starter-middel1">
                                @if($anuual->campaigns == 1)
                                <h4>- {{$anuual->campaigns}} campaign at a time</h4>
                                @else
                                <h4>- {{$anuual->campaigns}} simultaneous campaigns</h4>
                                @endif
                                <h4>- {{$anuual->contacts}} contacts/month max</h4>
                                <h4>- {{$anuual->automated_msg}} automated messages</h4>
                                <h4>- {{$anuual->linkedIn_account}} LinkedIn account</h4>
                            </div>
                            <div class="starter-middel2 rate-outermrgn">
                                <h3 class="rate-padding">${{$anuual->amount}}/{{trans('myProfile.month')}}</h3>
                            </div>
                            <a href="{{url('samylinkedIn/checkout').'/'.$anuual->id}}"><button><h4>{{trans('plan.order_now')}}</h4></button></a>

                        </div>
                    </div>
                @else
                    <div class="col-md-4 side-div-margin">
                        <div class="card">
                            <div class="starter">
                                <h3 class="name-padding">{{strtoupper($anuual->type)}}</h3>
                            </div>
                            <div class="starter-middel1">
                                @if($anuual->campaigns == 1)
                                    <h4>- {{$anuual->campaigns}} campaign at a time</h4>
                                @else
                                    <h4>- {{$anuual->campaigns}} simultaneous campaigns</h4>
                                @endif
                                <h4>- {{$anuual->contacts}} contacts/month max</h4>
                                <h4>- {{$anuual->automated_msg}} automated messages</h4>
                                <h4>- {{$anuual->linkedIn_account}} LinkedIn account</h4>
                            </div>
                            <div class="starter-middel2 rate-outermrgn">
                                <h3 class="rate-padding">${{$anuual->amount}}/{{trans('myProfile.month')}}</h3>
                            </div>
                            <a href="{{url('samylinkedIn/checkout').'/'.$anuual->id}}"><button><h4>{{trans('plan.order_now')}}</h4></button></a>
                        </div>
                    </div>
                @endif
                <?php
                $j++;
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
    function changeTerm(term){
        if(term == "month"){
            $('#monthly').show();
            $('#anuual').hide();
        }else{
            $('#monthly').hide();
            $('#anuual').show();
        }
    }
</script>