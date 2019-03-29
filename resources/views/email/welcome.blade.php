<style>
    .mail-btn
    {
        background-color: #158585;
        color: white;
    }
    .mail-btn:hover
    {
        background-color: #158585;
        color: white;
    }
</style>
<center>
    <h3>Hello {{$array['name']}}</h3> <br/>
    <p>Welcome to MLM! <br/> Please Verify Your Email For Using Our Services by <a class="mail-btn" href="{{url('confirm/email').'/'.$array['hash']}}">Clicking here</a></p>
</center>