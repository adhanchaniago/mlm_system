<?php
use App\Models\botCampaign;
use App\Models\bot;
?>
@include('samybot.samybot_topbar')
<div class="container">
    <br>
    @include('flash::message')
    <?php $i = 1; ?>
    @foreach($campaigns as $campaign)
        <?php
        $bots = \App\Models\botCampaign::where('campaign_id', $campaign->id)->get();
        $bots_count = count($bots);
        ?>
        <div class="col-md-12 col-sm-12 col-xs-12 samy_row samy_zero_padding">
            <div class="col-md-8 col-sm-12 col-xs-12 samy_row samy_zero_padding">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-4 col-sm-4 col-xs-12 samy_zero_padding">
                        <img class="samybot_img" src="{{$campaign->campaign_image}}">
                    </div>

                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <h4 class="col-md-4 col-sm-12 col-xs-7">{{$campaign->campaign_title}}</h4>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <button class="samy_btn" onclick="countChar({{$campaign->id}})" data-toggle="modal" data-target="#edit{{$campaign->id}}">{{trans('samybot/my_campaigns.edit_campaign')}}</button>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <button class="samy_btn" data-toggle="modal" data-target="#delete{{$campaign->id}}">{{trans('samybot/my_campaigns.delete_campaign')}}</button></a>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 samy_font">
                            <p style="min-height:45px;">{{$campaign->campaign_name}}</p>
                            <p><a href="{{$campaign->campaign_link}}">{{$campaign->campaign_link}}</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="col-md-12 col-sm-4 col-xs-12">
                <h4>{{trans('samybot/my_campaigns.stats')}}</h4>
                <ul class="samy_ul">
                    <li>{{trans('samybot/my_campaigns.started')}} : {{date('d/m/Y',strtotime($campaign->created_at))}}</li>
                    <li>{{trans('samybot/my_campaigns.views')}} : @if($campaign->campaign_views == '')
                            0 @else{{$campaign->campaign_views}} @endif</li>
                    <li>{{trans('samybot/my_campaigns.clicks')}} : @if($campaign->campaign_clicks == '')
                            0 @else{{$campaign->campaign_clicks}} @endif</li>
                    <li>{{trans('samybot/my_campaigns.samy_bots')}} : {{$bots_count}}</li>
                </ul>
                {{--<div class="col-md-8 col-sm-8 col-xs-8">--}}
                {{--<span onclick="get_lifetime_graph('{{$campaign->id}}');" class="graph_text samy_heading" style="color:#ff5722;">Lifetime</span>--}}
                {{--<span onclick="get_30days_grapg('{{$campaign->id}}');" class="graph_text samy_font">30days</span>--}}
                {{--<span onclick="get_7days_graph('{{$campaign->id}}');" class="graph_text samy_font">7 days</span>--}}
                {{--<div id="area-chart{{$campaign->id}}" data-id="{{$campaign->id}}" class="samy_graph"></div>--}}
                {{--</div>--}}
            </div>
        </div>
        </div>
        <?php
        $bots = bot::where('company_id',Auth::user()->company_id)->get();
        $campcategory = DB::table('categories')->where('id', $campaign->campaign_category)->first();
        $categoryId = $campcategory->id;
        ?>
        <div class="modal fade" id="edit{{$campaign->id}}" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">{{trans('samybot/my_campaigns.edit_campaign')}}</h4>
                    </div>
                    <form method="post" action="{{url('samybot/update_campaign')}}" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="modal-body">
                            <center>
                                <img src="{{$campaign->campaign_image}}" id="preview{{$campaign->id}}" class="samybot_img" style="display: inline;">
                                <button class="samy_plus" id="OpenImgUpload{{$campaign->id}}" onclick="triggerFile({{$campaign->id}})" type="button"><i class="fa fa-pencil"></i></button>
                            </center>
                            <input type="file" name="campaign_image" id="imgupload{{$campaign->id}}" class="imgupload" data-img="{{$campaign->id}}" style="display:none">
                            <div class="col-md-12">
                                <span id="Error" style="color:red"></span>
                                <input type="hidden" name="id" value="{{$campaign->id}}">
                                <input type="text" required name="title" class="form-control samy_campians_form"
                                       placeholder="{{trans('samybot/my_campaigns.title')}}" value="{{$campaign->campaign_title}}">
                                <select name="category" required class="form-control samy_campians_form" style="font-size: 17px;">
                                    <option selected disabled value="">{{trans('samybot/my_campaigns.category')}}</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}" @if($categoryId == $category->id) selected @endif>{{$category->category_name}}</option>
                                    @endforeach
                                </select>
                                <label class="samy_campians">{{trans('samybot/my_campaigns.headline')}}<span id="rchars{{$campaign->id}}">0</span><span>/144</span></label>
                                <textarea rows="5" name="heading" class="samy_campians_textarea" maxlength="144" id="textarea{{$campaign->id}}" data-id="{{$campaign->id}}">{{$campaign->campaign_name}}</textarea>
                                <input type="text" name="link" required class="form-control samy_campians_form samy_campians"
                                       value="{{$campaign->campaign_link}}">
                                <h4>{{trans('samybot/my_campaigns.bots')}}</h4>
                                <table class="col-md-12 col-sm-12 col-xs-12 samy_campians_table table-responsive">
                                    <tbody>
                                    <?php $c=0; ?>
                                    @foreach($bots as $bot)
                                        {{--@if(botCampaign::where('bot_id',$bot->bot_id)->exists())--}}
                                            @if(botCampaign::where('bot_id',$bot->bot_id)->where('campaign_id',$campaign->id)->exists())
                                                <tr>
                                                    <td class="col-md-8 col-sm-8 col-xs-8">{{$bot->bot_name}}</td>
                                                    <td class="col-md-2 col-sm-2 col-xs-2">
                                                        {{$bot->bot_type}}
                                                    </td>
                                                    <td class="col-md-2 col-sm-2 col-xs-2">
                                                        <input type="checkbox" onclick="releaseBot('{{$bot->bot_id}}','{{$campaign->id}}')" name="bots" value="{{$bot->bot_id}}" id="botId{{$bot->bot_id}}-{{$campaign->id}}" checked>
                                                    </td>
                                                <tr>
                                            @else
                                                @if($bot->bot_type == "idle")
                                                <tr>
                                                    <td class="col-md-8 col-sm-8 col-xs-8">{{$bot->bot_name}}</td>
                                                    <td class="col-md-2 col-sm-2 col-xs-2">
                                                        {{$bot->bot_type}}
                                                    </td>
                                                    <td class="col-md-2 col-sm-2 col-xs-2">
                                                        <input type="checkbox" onclick="saveBot('{{$bot->bot_id}}','{{$campaign->id}}')" name="bots" value="{{$bot->bot_id}}" id="botId{{$bot->bot_id}}-{{$campaign->id}}">
                                                    </td>
                                                </tr>
                                                @endif
                                            @endif
                                        {{--@endif--}}
                                    <?php $c++; ?>
                                    @endforeach
                                    @if($c ==0)
                                        <tr><td><center>{{trans('samybot/my_campaigns.no_bots_available')}}</center></td></tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <br>
                            <button class="btn samy_btn" type="submit" style="width:auto;">{{trans('samybot/my_campaigns.launch')}}</button>
                            <button type="button" class="btn btn-default samy_footer_close" data-dismiss="modal">{{trans('samybot/my_campaigns.close')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="delete{{$campaign->id}}" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                        <div class="modal-body">
                            {{trans('samybot/my_campaigns.are_you_sure')}}
                        </div>
                        <div class="modal-footer">
                            <br>
                            <a href="{{url('samybot/campaign/delete').'/'.$campaign->id}}">{{trans('samybot/my_campaigns.yes')}}</a>
                            <button type="button" class="btn btn-default samy_footer_close" data-dismiss="modal">{{trans('samybot/my_campaigns.no')}}</button>
                        </div>
                </div>
            </div>
        </div>
        <?php $i++; ?>
    @endforeach
</div>
<link rel="stylesheet" href="https://cdn.oesmith.co.uk/morris-0.5.1.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.0/morris.min.js"></script>
<script>
    $(".samybot_img").each(function() {
        $(this).on("error", function () {
            $(this).unbind("error").attr("src", "{{asset('public/campaign_images/default.png')}}");
        });
    });

    function countChar(id) {
        var val = $('#textarea'+id).val();
        var len = val.length;
        $('#rchars'+id).text(len);
    }
    function triggerFile(Id) {
        $('#imgupload'+Id).trigger('click');
    }
    function readURL(input,id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview'+id).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".imgupload").change(function () {
        var id = $(this).data("img");
        readURL(this,id);
    });
    var maxLength = 144;
    $("textarea").keyup(function () {
        var id = $(this).data("id");
        if($(this).val().length <= maxLength){
            var textlen = $(this).val().length + 1;
            $('#rchars'+id).text(textlen);
        }
    });

    $(".samy_graph").each(function () {
        var campaign_id = $(this).data("id");
        $.ajax({
            type: "get",
            url: "{{url('samybot/lifetime_graph')}}" + '/' + campaign_id,
            success: function (result) {
                get_graph(result, campaign_id);
            }
        });
    });

    function get_graph(result, campaign_id) {
        var data = result,
            config = {
                data: data,
                xkey: 'y',
                ykeys: ['a', 'b', 'c'],
                labels: ['lifetime', '30days', '7days'],
                fillOpacity: 0.6,
                hideHover: 'auto',
                behaveLikeLine: true,
                resize: true,
                pointFillColors: ['#ffffff'],
                pointStrokeColors: ['black'],
                lineColors: ['#55aaff', '#43409f', '#8faede']
            };
        config.element = 'area-chart' + campaign_id;
        Morris.Area(config);
        hideHover = 'always';
    }
</script>
<script>
    function saveBot(bot_id, camp_id) {
        $.ajax({
            type: "get",
            url: "{{url('samybot/saveBot')}}" + '/' + bot_id + '/' + camp_id,
            success: function (result) {
            }
        });
    }
    function releaseBot(bot_id, camp_id) {
        $.ajax({
            type: "get",
            url: "{{url('samybot/releaseBot')}}" + '/' + bot_id + '/' + camp_id,
            success: function (result) {
            }
        });
    }

    var maxLength = 144;
    $('textarea').keyup(function() {
        if($(this).val().length < maxLength){
            var textlen = $(this).val().length + 1;
            $('#rchars').text(textlen);
        }
    });
</script>
@include('frontEnd.footer')