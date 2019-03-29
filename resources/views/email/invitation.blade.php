<?php use App\Models\company;
use App\User;

$user = User::whereId($data['invitee'])->first();
if ($user->status == '1') {
    $company = company::whereId($user->company_id)->first();
} elseif ($user->status == '2') {
    $affiliate = \App\Models\affiliate::whereId($user->affiliate_id)->first();
    $company = company::whereId($affiliate->company_id)->first();
} elseif ($user->status == '4')
{
    $domain = request()->getHost();
    if ($domain != ''.env('APP_DOMAIN').'')
    {
        $affiliate = \App\Models\affiliate::whereId($user->affiliate_id)->first();
        $company = company::whereId($affiliate->company_id)->first();
    }
    else
    {
        $company = company::whereId($user->company_id)->first();
    }
}
?>

    @if(isset($data['affiliate_text']))
        <body style="width: 100%;line-height: 25px;background-color: #eff0f7">
        <center>
            <div style="padding-top: 5%;padding-bottom: 5%;">
                <h3>{{trans('mail.hello')}} {{$data['name']}}</h3>
                <p>{{$data['affiliate_text']}}</p>
                @if(isset($company->domain_name) && !empty($company->domain_name))
                    <a style="color: black" href="{{$company->domain_name.'/affliate/register'.'/'.$data['enc_company'].'/'.$data['enc_invitee'].'/'.$data['enc_email'].'/'.$data['special']}}"><button type="button" style="background-color: #ff5722;color: white;border: 1px solid gray;cursor: pointer;height: 30px;">{{trans('mail.join_here')}}</button></a>
                @else
                    <a style="color: black" href="{{'http://affiliate.samybot.com/affliate/register'.'/'.$data['enc_company'].'/'.$data['enc_invitee'].'/'.$data['enc_email'].'/'.$data['special']}}"><button type="button" style="background-color: #ff5722;color: white;border: 1px solid gray;cursor: pointer;height: 30px;">{{trans('mail.join_here')}}</button></a>
                @endif
            </div>
        </center>
        </body>


    @else

        <body style="width: 100%;line-height: 25px;background-color: #eff0f7">
        <center>
            <div style="padding-top: 5%;padding-bottom: 5%;">
                <h3>{{trans('mail.hello')}} {{$data['name']}}</h3>
                <p>{{trans('mail.affiliate_invite_text')}}</p>
                @if(isset($company->domain_name) && !empty($company->domain_name))
                    <a style="color: black" href="{{$company->domain_name.'/affliate/register'.'/'.$data['enc_company'].'/'.$data['enc_invitee'].'/'.$data['enc_email'].'/'.$data['special']}}"><button type="button" style="background-color: #ff5722;color: white;border: 1px solid gray;cursor: pointer;height: 30px;">{{trans('mail.join_here')}}</button></a>
                @else
                    <a style="color: black" href="{{'http://affiliate.samybot.com/affliate/register'.'/'.$data['enc_company'].'/'.$data['enc_invitee'].'/'.$data['enc_email'].'/'.$data['special']}}"><button type="button" style="background-color: #ff5722;color: white;border: 1px solid gray;cursor: pointer;height: 30px;">{{trans('mail.join_here')}}</button></a>
                @endif
            </div>
        </center>
        </body>

    @endif
