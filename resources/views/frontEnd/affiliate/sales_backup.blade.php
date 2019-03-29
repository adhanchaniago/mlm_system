
@include('frontEnd.affiliate.header')
<div class="container-fluid">

    <!--Section-1-->
@include('frontEnd.affiliate.section')
<!--Section-1 end-->

    <!--Section-2-->
    <div class="row">
        <!--SideBar-->
    @include('frontEnd.affiliate.sidebar')
    <!--SideBar end-->
        <div class="col-md-10 col-xs-12 marketing_section">
            @include('flash::message')
            @include('adminlte-templates::common.errors')
            <button type="button" class="sales_btn col-md-offset-2" data-toggle="modal" data-target="#salesModal">{{trans('home.see_my_sales')}}</button>

            <!-- Modal -->
            <div class="modal fade" id="salesModal" role="dialog">
                <div class="modal-dialog add-level-modal">

                    <div class="modal-content add-level-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <center><b><h2 class="modal-title">{{trans('affiliate.direct_sales')}}</h2></b></center>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <center>
                                    <a href="{{url('exportSales').'/'.$affiliate->id}}"
                                       class="btn btn-primaryy">{{trans('home.export_csv')}}</a>
                                    <a href="{{url('exportSalesPdf').'/'.$affiliate->id}}" class="btn btn-primaryy">{{trans('home.export_pdf')}}</a>
                                </center>
                            </div>
                            @if($sales != "")
                                <div class="row">
                                    @foreach($sales as $sale)
                                        <center>
                                            <div class="col-md-12 col-sm-12 col-xs-12 affiliate-sales">
                                                <div class="col-md-4 col-sm-4">
                                                    <h5><b>{{date('m/d/Y',strtotime($sale->date))}}</b></h5>
                                                </div>
                                                <div class="col-md-4 col-sm-4">
                                                    <h5><b>{{$affiliate->name}}</b></h5>
                                                </div>
                                                <div class="col-md-4 col-sm-4">
                                                    <h5><b>&dollar;{{number_format($sale->price)}}</b></h5>
                                                </div>
                                            </div>
                                        </center>
                                    @endforeach
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-12 affiliate-sales">
                                        <h4><b>{{trans('affiliate.no_sale')}}</b></h4>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>

                </div>
            </div>
            @if($level <= $max_levels && ($current_affiliates < $max_affiliates || $max_affiliates == 'unlimited'))
                <div class="col-md-11 col-xs-12">
                    <h3 class="marketing_heading">{{trans('home.my_invitation_link')}}</h3>
                    <h3>{{trans('home.my_invitation_link_details')}}</h3>
                    <div class="form-group">
                        <div class="col-md-8 col-xs-12 col-sm-8 zeropadding">
                            @if(!empty($company->domain_name))
                                <input type="text" class="link_field" name="country" value="{{$company->domain_name}}/invite-user/{{encrypt($company->id)}}/{{encrypt(Auth::user()->id)}}" id="myInviteLink" readonly>
                            @else
                                <input type="text" class="link_field" name="country" value="{{env('APP_ALT_DOMAIN')}}/invite-user/{{encrypt($company->id)}}/{{encrypt(Auth::user()->id)}}" id="myInviteLink" readonly>
                            @endif
                        </div>
                        <div class="col-md-2 col-xs-6 col-sm-2">
                            <button type="button" class="copybtn" onclick="CopyFunction('myInviteLink')">{{trans('home.copy')}}</button>
                        </div>
                        <div class="col-md-2 col-xs-6 col-sm-2">
                            <button type="button" class="copybtn" data-toggle="modal" data-target="#addNewAff">{{trans('auth.email')}}</button>
                            <div class="modal fade" id="addNewAff" role="dialog">
                                <div class="modal-dialog">

                                    <div class="modal-content add-level-content">
                                        <div class="modal-header">
                                            <center>
                                                <h4>{{trans('invite_affiliate')}}</h4>
                                            </center>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" id="invite-form" enctype="multipart/form-data" action="{{url('invite-link')}}">
                                                {{csrf_field()}}
                                                <div class="row">
                                                    <center><p class="help-block" id="error-invite"></p></center>
                                                    <div class="col-sm-2"></div>
                                                    <div class="form-group col-sm-8">
                                                        <label>{{trans('auth.name')}} : </label>
                                                        <input type="text" name="name" id="name" class="form-control" placeholder="{{trans('auth.name')}}" required>

                                                    </div>
                                                    <div class="col-sm-2"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-2"></div>
                                                    <div class="form-group col-sm-8">
                                                        <label>{{trans('auth.email')}} : </label>
                                                        <input type="email" id="email" name="email" class="form-control" placeholder="{{trans('auth.email')}}" required>
                                                        <input type="hidden" readonly name="invitee" value="{{Auth::user()->id}}">
                                                    </div>
                                                    <div class="col-sm-2"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-2"></div>
                                                    <div class="form-group col-sm-8">
                                                        <center>
                                                            <button type="submit" id="save-btn" class="btn btn-primaryy">{{trans('home.send_link')}}</button>
                                                        </center>
                                                    </div>
                                                    <div class="col-sm-2"></div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-11 col-xs-12 section1_logo">
                <h3 class="marketing_heading">{{trans('home.my_affiliate_link')}}</h3>
                <h3>{{trans('home.my_affiliate_link_details')}}</h3>
                <div class="form-group">
                    <div class="col-md-8 col-xs-12 col-sm-8 zeropadding">
                        @if(!empty($company->actual_domain))
                            <input type="text" class="link_field" name="country" value="{{$company->actual_domain}}?affiliate_id={{Auth::user()->typeid}}" id="myPurchaseLink" readonly>
                        @else
                            <input type="text" class="link_field" name="country" value="{{env('APP_ALT_DOMAIN')}}?affiliate_id={{Auth::user()->typeid}}" id="myPurchaseLink" readonly>
                        @endif
                    </div>
                    <div class="col-md-2 col-xs-6 col-sm-2">
                        <button type="button" class="copybtn" onclick="CopyFunction('myPurchaseLink')">{{trans('home.copy')}}</button>
                    </div>
                    <div class="col-md-2 col-xs-6 col-sm-2">
                        <button type="button" class="copybtn" data-toggle="modal" data-target="#purchaseLink">{{trans('auth.email')}}</button>
                    </div>
                </div>


                <div class="modal fade" id="purchaseLink" role="dialog">
                    <div class="modal-dialog add-level-modal">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <center>
                                    <h4 class="modal-title">{{trans('sales.purchase_link')}}</h4>
                                </center>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="{{url('purchaseLink')}}">
                                    {{csrf_field()}}
                                    <div class="form-group col-sm-12">
                                        <label>{{trans('auth.email')}}:</label>
                                        <input type="text" class="form-control" name="email" Placeholder="{{trans('auth.email')}}">
                                        <input type="hidden" name="link" value="{{$company->actual_domain}}?affiliate_id={{Auth::user()->typeid}}">
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <button type="submit" class="btn btn-save">{{trans('home.send_link')}}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                            </div>
                        </div>

                    </div>
                </div>


            </div>
            <div class="col-md-11 col-xs-12 section1_logo">
                <h3 class="marketing_heading">{{trans('home.my_samybot_link')}}</h3>
                <h3>{{trans('home.my_affiliate_link_details')}}</h3>
                <div class="form-group">
                    <div class="col-md-8 col-xs-12 col-sm-8 zeropadding">
                        <input type="text" class="link_field" name="country" value="{{env('APP_DOMAIN')}}/samybot/plan?affiliate_id={{Auth::user()->typeid}}" id="myBotPurchaseLink" readonly>
                    </div>
                    <div class="col-md-2 col-xs-6 col-sm-2">
                        <button type="button" class="copybtn" onclick="CopyFunction('myBotPurchaseLink')">{{trans('home.copy')}}</button>
                    </div>
                    <div class="col-md-2 col-xs-6 col-sm-2">
                        <button type="button" class="copybtn" data-toggle="modal" data-target="#botLink">{{trans('auth.email')}}</button>
                    </div>
                </div>
                <div class="modal fade" id="botLink" role="dialog">
                    <div class="modal-dialog add-level-modal">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <center>
                                    <h4 class="modal-title">{{trans('sales.bot_link')}}</h4>
                                </center>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="{{url('samyBotLink')}}">
                                    {{csrf_field()}}
                                    <div class="form-group col-sm-12">
                                        <label>{{trans('auth.email')}}:</label>
                                        <input type="text" class="form-control" name="email" Placeholder="{{trans('auth.email')}}">
                                        <input type="hidden" name="link" value="{{env('APP_DOMAIN')}}/samybot/plan?affiliate_id={{Auth::user()->typeid}}">
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <button type="submit" class="btn btn-save">{{trans('home.send_link')}}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Section-2 end-->
</div>

<script>
    function CopyFunction(val) {
        var copyText = document.getElementById(val);

        /* Select the text field */
        copyText.select();

        /* Copy the text inside the text field */
        document.execCommand("copy");
    }
</script>
<script>
    function validateMail() {
        var email = $('#email').val();
        if ($('#name').val() == '')
        {
            $('#error-invite').text('{{trans('auth.name_required')}}');
        }
        else if($('#email').val() == '')
        {
            $('#error-invite').text('{{trans('auth.email_required')}}');
        }
        else {

            $('#save-btn').prop('type','submit');
        }
    }
</script>
<!--Footer section-->
@include('frontEnd.mainFooter')

