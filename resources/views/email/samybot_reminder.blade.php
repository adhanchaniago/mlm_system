<center>
    <?php $plan = App\Models\SamyBotPlans::whereId($bot_plan->plan)->first(); ?>
    @if(isset($last_day) && $last_day == 1)
        <div style="padding-top: 5%;padding-bottom: 5%;">
            <h2>Hello {{$company['name']}}</h2><br/>
            <h3>Your Current Samy Bot plan {{$plan->name}} has expired today.<br> Please return the device back.And purchase a new Plan to enjoy Samybot facillities.</h3><br/>
            <h4>Thnak You <br>Team Samy Bot</h4>
        </div>
    @else
        <div style="padding-top: 5%;padding-bottom: 5%;">
            <h2>Hello {{$company['name']}}</h2><br/>
            <h3>Your Current Samy Bot plan {{$plan->name}} will expires in {{$days_left}} days.<br> To Enjoy all Samy bot Benifits like before Please Renew the Subscription!</h3><br/>
            <h4>Thnak You <br>Team Samy Bot</h4>
        </div>
    @endif
</center>