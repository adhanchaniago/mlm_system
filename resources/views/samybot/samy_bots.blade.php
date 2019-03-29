<style>
    .samy_heading{
        font-size: large;
    }
</style>
@include('samybot.samybot_topbar')
<div class="container">
    <div class="col-md-12 samy_zero_padding">
    <br>
    @foreach($bots as $bot)
        <?php
            if(\App\Models\botCampaign::where('bot_id' ,$bot->bot_id)->exists()){
                $campaign = \App\Models\botCampaign::where('bot_id' ,$bot->bot_id)->first();
                $camp = \App\Models\campaigns::whereId($campaign->campaign_id)->first();
            }
            else{
                $campaign = "";
                $camp = "";
            }
        ?>
        @if(empty($campaign) || $campaign == "")
            <div class="col-md-6 col-sm-6 col-xs-12" style="margin: 3px 0px">
                <div class="col-md-5 col-xs-12 samy_zero_padding">
                    <img class="samybot_img" src="{{asset('public/campaign_images/default.png')}}" style="border: 1px solid darkgray">
                </div>
                <div class="col-md-7 col-xs-12">
                    <div class="col-md-8 col-sm-8 col-xs-12 samy_heading"><h4>{{$bot->bot_name}}</h4></div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <button type="button" class="samy_btn" style="width: auto;padding: 5px 30px;">{{$bot->bot_type}}</button>
                    </div>
                    <div class="col-md-12  col-sm-12 col-xs-12 samy_font">
                        <h4>{{trans('samybot/my_campaigns.id')}} : {{$bot->instance_id}}</h4>
                        <h4>{{trans('samybot/my_campaigns.campaign')}} : {{trans('samybot/my_campaigns.none')}}</h4>
                    </div>
                </div>
            </div>
        @else
            <div class="col-md-6 col-sm-6 col-xs-12" style="margin: 3px 0px">
                <div class="col-md-5 col-xs-12 samy_zero_padding">
                    @if(!empty($camp))
                    <img class="samybot_img" src="{{$camp->campaign_image}}" style="border: 1px solid darkgray;">
                    @else
                    <img class="samybot_img" src="{{asset('public/campaign_images/default.png')}}" style="border: 1px solid darkgray">
                    @endif
                </div>
                <div class="col-md-7 col-xs-12">
                    <div class="col-md-8 col-sm-8 col-xs-12 samy_heading"><h4>{{$bot->bot_name}}</h4></div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        @if($bot->bot_type == "idle")
                            <button type="button" class="samy_btn" style="width: auto;padding: 5px 30px;">{{$bot->bot_type}}</button>
                        @else
                            <a href="{{url('samybot/release').'/'.$bot->bot_id.'/'.$campaign->campaign_id}}"><button type="button" class="samy_btn" style="width: auto;padding: 5px 30px;">{{$bot->bot_type}}</button></a>
                        @endif
                    </div>
                    <div class="col-md-12  col-sm-12 col-xs-12 samy_font">
                        <h4>{{trans('samybot/my_campaigns.id')}} : {{$bot->instance_id}}</h4>
                        <h4>{{trans('samybot/my_campaigns.campaign')}} : {{$camp->campaign_title}}</h4>
                        <p>
                        <a href="{{$camp->campaign_link}}">{{$camp->campaign_link}}</a>
                        </p>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
    </div>
</div>
@include('frontEnd.mainFooter')
<script>
    $(".samybot_img").each(function() {
        $(this).on("error", function () {
            $(this).unbind("error").attr("src", "{{asset('public/campaign_images/default.png')}}");
        });
    });
</script>