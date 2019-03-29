
@include('frontEnd.affiliate.header')
<style>
    ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: gray !important;
        font-size: 17px;
    }
</style>
<div class="container">
    @if ($message = Session::get('success'))

        <div class="custom-alerts alert alert-success fade in">

            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>

            {!! $message !!}

        </div>

        <?php Session::forget('success');?>

    @endif
    @include('adminlte-templates::common.errors')
    @include('flash::message')
    <div class="row">
        <h1 class="text-center">{{trans('header.samy_MyAccount')}}</h1>
        <div class="col-md-12 section2_numbers">
            <div class="col-md-8 col-xs-12 col-sm-12">
                <form method="post" enctype="multipart/form-data"
                      action="{{url('affiliate/details').'/'.$affliate->id}}">
                    {{csrf_field()}}
                    <div class="col-md-4 col-xs-12 col-sm-4">
                        <input type="text" name="fname" value="{{$affliate->fname}}" class="form-control Account_inputs" placeholder="{{trans('myProfile.first_name')}}">
                        <input type="text" name="lname" value="{{$affliate->lname}}" class="form-control Account_inputs" placeholder="{{trans('myProfile.last_name')}}">
                        <input type="email" readonly name="email" value="{{$affliate->email}}" class="form-control Account_inputs" placeholder="{{trans('auth.email')}}">
                        <input type="text" name="phone" value="{{$affliate->phone}}" id="phone_number" class="form-control Account_inputs" placeholder="{{trans('myProfile.phone')}}">
                        <p class="help-block" id="invalidPhone"></p>
                        <input type="file" class="form-control Account_inputs" name="photo" accept="image/x-png,image/gif,image/jpeg,image/jpg,image/PNG" onchange="readURL(this)">
                        <div id="image">
                            @if(isset($affliate->photo))
                                <img src="{{asset('public/avatars').'/'.$affliate->photo}}" class="edit-image">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8 col-xs-12 col-sm-8">
                        <input type="text" value="{{$affliate->address}}" name="address" class="form-control Account_inputs" placeholder="{{trans('myProfile.address')}}">
                        <input type="text" value="{{$affliate->address2}}" name="address2" class="form-control Account_inputs" placeholder="{{trans('myProfile.address2')}}">
                        <input type="text" value="{{$affliate->city}}" name="city" class="form-control Account_inputs" placeholder="{{trans('myProfile.city')}}">
                        <input type="text" value="{{$affliate->state}}" name="state" class="form-control Account_inputs" placeholder="{{trans('myProfile.state')}}">
                        <input type="text" value="{{$affliate->zip}}" name="zip" class="form-control Account_inputs" placeholder="{{trans('myProfile.zip')}}">
                        <select class="form-control Account_inputs" name="country">
                            <option value="" selected disabled>{{trans('home.select_country')}}</option>
                            @foreach($countries as $key => $value)
                                <option value="{{$value}}" <?php if ($affliate->country == $value) {
                                    echo "selected";
                                } ?>>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 col-xs-12 col-sm-12 account_division">
                        <div class="col-md-2 col-xs-5 col-sm-4 zeropadding marketing_section">
                            <h4>{{trans('myProfile.your_payouts')}}</h4>
                        </div>
                        <div class="col-md-3 col-xs-7 col-sm-4 marketing_section">
                            <button type="button" class="accountbtn" data-toggle="modal" data-target="#myModal">{{trans('payout.payout_history')}}</button>
                            <!-- Modal -->
                            <div class="modal fade" id="myModal" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content account-model">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title text-center">{{trans('payout.payout_breakdown')}}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <table>
                                                <tbody>
                                                @if($payouts != "")
                                                    @foreach($payouts as $payout)
                                                        <?php
                                                        if (\App\Models\rank::where('company_id', $company->id)->where('rank', $payout->rankid)->exists()) {
                                                            $rank = \App\Models\rank::where('company_id', $company->id)->where('rank', $payout->rankid)->first();
                                                        } else {
                                                            $rank = "";
                                                        }
                                                        ?>
                                                        <tr class="col-md-12">
                                                            <td class="col-md-3">{{date('d/m/Y',strtotime($payout->created_at))}}</td>
                                                            @if($rank != "")
                                                                <td class="col-md-3">{{strtoupper($rank->name)}}</td>
                                                                <td class="col-md-2">${{$payout->amount}}</td>
                                                            @else
                                                                <td class="col-md-3">-</td>
                                                                <td class="col-md-3">$0</td>
                                                            @endif
                                                            <td class="col-md-4"><a
                                                                        href="{{url('exportSales').'/'.$affliate->id}}">
                                                                    <button type="button" class="tableexport">{{trans('myProfile.export_list_sales')}}</button>
                                                                </a></td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <center>
                                                            <h4>{{trans('home.no_data')}}</h4>
                                                        </center>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <input type="email" name="paypal_email" value="{{$affliate->paypal_email}}"
                               class="form-control Account_inputs" placeholder="{{trans('myProfile.paypal_email')}}">
                        <label class="acc_info">{{trans('myProfile.bank_accounts')}}</label>
                        <textarea name="acc_info" class="accinfoarea" rows="6">{{$affliate->acc_info}}</textarea>
                    </div>
                    <div class="col-sm-12 col-xs-12 col-sm-12">
                        <center>
                            <button type="submit" class="btn btn-save">{{trans('myProfile.save')}}</button>
                        </center>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!--Footer section-->
@include('frontEnd.footer')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                var html = '<img class="edit-image" src="' + e.target.result + '">';
                $('#image')
                    .html(html);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    function validatePhone() {
        var phone_number = $('#phone_number').val();
//        alert(phone_number.charAt(0));
        var phone_number_1 = phone_number.substring(1);
        if (/^\d+$/.test(phone_number_1) && phone_number.length > 10) {
            if (phone_number.charAt(0) == '+')
            {
                $('#invalidPhone').css('display', 'none');
                $.ajax({
                    url: "{{url('validatePhone')}}" + '/' + phone_number,
                    success: function (result) {
                        if (result == "success") {
                            $('#aff-reg-btn').prop('type', 'submit');
                            $('#invalidPhone').css('display', 'none');
                        }
                        else {
                            $('#aff-reg-btn').prop('type', 'button');
                            $('#invalidPhone').text('{{trans('phoneError.phone_exists')}}');
                            $('#invalidPhone').css('display', 'block');
                        }
                    }
                });
            }
            else
            {
                $('#aff-reg-btn').prop('type', 'button');
                $('#invalidPhone').text('{{trans('phoneError.phone_valid')}}');
                $('#invalidPhone').css('display', 'block');
            }
        }
        else {
            $('#aff-reg-btn').prop('type', 'button');
            $('#invalidPhone').text('{{trans('phoneError.phone_valid')}}');
            $('#invalidPhone').css('display', 'block');
        }

    }
</script>
