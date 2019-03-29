@include('samybot.samybot_topbar')
<div class="container samy_campians">
    <br>
    @include('flash::message')
    <div class="row">
        <form method="post" action="{{url('samybot/create_campaign')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="col-md-10 col-sm-12 col-xs-12 col-md-offset-1">
            <div class="col-md-5 col-sm-5 col-xs-12">
                <input type="file" name="campaign_image" id="imgupload" style="display:none">
                <input type="hidden" name="campaign_id" value="111">
                <input type="text" name="title" class="form-control samy_campians_form"  placeholder="{{trans('samybot/my_campaigns.title')}}" required>
                {{--<div class="col-md-6 col-sm-6 col-xs-6 samy_zero_padding">--}}
                    <span id="Error" style="color:red"></span>
                    <img src="{{asset('public/image/star.png')}}" id="preview" style="height: 180px;width: 235px;">
                {{--</div>--}}
                {{--<div class="col-md-1">--}}
                    <button class="samy_plus" id="OpenImgUpload" type="button"><i class="fa fa-plus"></i></button>
                {{--</div>--}}
                <div class="col-md-12 col-sm-12 col-xs-12 samy_zero_padding">
                <label>{{trans('samybot/my_campaigns.headline')}}<span id="rchars">0</span><span>/144</span></label>
                <textarea rows="5" name="heading" class="samy_campians_textarea" required maxlength="144"></textarea>
                </div>

                <input type="text" required name="link" class="form-control samy_campians_form samy_campians" placeholder="{{trans('samybot/my_campaigns.link')}}">

                <select name="category" required class="form-control samy_campians_form" style="font-size: 17px;">
                    <option disabled selected value="">{{trans('samybot/my_campaigns.category')}}</option>
                    @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->category_name}}</option>
                    @endforeach
                </select>

                <h4>{{trans('samybot/my_campaigns.bots')}}</h4>
                <table class="col-md-12 col-sm-12 col-xs-12 samy_campians_table table-responsive">
                    <tbody>
                    @foreach($bots as $bot)
                    <tr>
                        <td class="col-md-8 col-sm-8 col-xs-8">{{$bot->bot_name}}</td>
                        <td class="col-md-2 col-sm-2 col-xs-2">{{$bot->bot_type}}</td>
                        <td class="col-md-2 col-sm-2 col-xs-2">
                            <input type="checkbox" name="bots[]" value="{{$bot->bot_id}}">
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="samy_zero_padding">
                    <br>
                <button class="samy_campians_btn" id="launch" type="button" onclick="validateForm()">{{trans('samybot/my_campaigns.launch')}}</button>
                </div>
            </div>
            <div class="col-md-6 col-xs-12 col-sm-6 col-md-offset-1">
                {{--<iframe class="samy_campians_video" src="https://www.youtube.com/embed/PvPxFQAnczE"></iframe>--}}
                <iframe class="samy_campians_video" src="https://www.youtube.com/embed/j42q4K-touY"></iframe>
            </div>
        </div>
        </form>
    </div>
</div>
@include('frontEnd.footer')
<script>
    $('#OpenImgUpload').click(function(){
        $('#Error').text('');
        $('#imgupload').trigger('click');
    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imgupload").change(function() {
        readURL(this);
    });

    function validateForm() {
        var img = $('#imgupload').val()
        if(img == "" || img == null){
            $('#Error').text('Image Field is Required');
            $('#launch').attr("type", "button");
        }else{
            $('#launch').attr("type", "submit");
        }
    }
    var maxLength = 144;
    $('textarea').keyup(function() {
        if($(this).val().length < maxLength){
            var textlen = $(this).val().length + 1;
            $('#rchars').text(textlen);
        }
    });
</script>