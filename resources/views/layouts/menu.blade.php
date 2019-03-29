@if(Auth::user()->status == '0')
    <li class="treeview {{ Request::is('timeout*') || Request::is('activation*') || Request::is('paypal_email*') || Request::is('shipping*') || Request::is('email*') ? 'active' : '' }}">
        <a href="#"><i class="fa fa-cog"></i> <span>Settings</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
        </a>
        <ul class="treeview-menu">
            <li class="{{ Request::is('timeout*') ? 'active' : '' }}">
                <a href="{!! url('timeout') !!}"><i class="fa fa-angle-right"></i>Time to Block</a></li>
            <li><a href="{{url('activateCharge')}}"><i class="fa fa-angle-right"></i>Activate Charges</a></li>
            <li><a href="{{url('shipping')}}"><i class="fa fa-angle-right"></i>Shipping Charges</a></li>
            <li><a href="{{url('email')}}"><i class="fa fa-angle-right"></i>Email Content</a></li>
            <li><a href="{{url('paypal_email')}}"><i class="fa fa-angle-right"></i>Paypal Email</a></li>
        </ul>
    </li>
    <li class="{{ Request::is('plantables*') ? 'active' : '' }}">
        <a href="{!! route('plantables.index') !!}"><i class="fa fa-tasks"></i><span>Set up a Affiliate Plan</span></a>
    </li>
    <li class="{{ Request::is('SamyBotPlans*') ? 'active' : '' }}">
        <a href="{!! route('SamyBotPlans.index') !!}"><i class="fa fa-tasks"></i><span>Set up a SamyBot Plan</span></a>
    </li>
    <li class="{{ Request::is('linkedinPlans*') ? 'active' : '' }}">
        <a href="{!! route('linkedinPlans.index') !!}"><i class="fa fa-linkedin"></i><span>Linkedin Plans</span></a>
    </li>
    <li class="{{ Request::is('companies*') ? 'active' : '' }}">
        <a href="{!! route('companies.index') !!}"><i class="fa fa-building-o"></i><span>Companies</span></a>
    </li>

    <li class="treeview {{ Request::is('payment/pending*') ? 'active' : '' }}">
        <a href="#"><i class="fa fa-cog"></i> <span>Unpaid Companies</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
        </a>
        <ul class="treeview-menu">
            <li class="{{ Request::is('pending*') ? 'active' : '' }}">
                <a href="{!! url('pending/affiliate') !!}"><i class="fa fa-angle-right"></i>Samy Affiliate</a></li>
            <li><a href="{{url('pending/samybot')}}"><i class="fa fa-angle-right"></i>SamyBot</a></li>
            <li><a href="{{url('pending/linkedin')}}"><i class="fa fa-angle-right"></i>Samy LinkedIn</a></li>
        </ul>
    </li>
    <li class="{{ Request::is('message*') ? 'active' : '' }}">
        <a href="{!! url('messages') !!}"><i class="fa fa-comments"></i><span>Messages</span></a>
    </li>
@elseif(Auth::user()->status == '1')
    <li class="treeview {{ Request::is('edit/details*') || Request::is('billing/company*')|| Request::is('savedCards*')|| Request::is('billing/company*')|| Request::is('edit/smtp/company*') ? 'active' : '' }}">
        <a href="#"><i class="fa fa-cog"></i> <span>Settings</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
        </a>
        <ul class="treeview-menu">
            <li class="{{ Request::is('billing/company*') ? 'active' : '' }}"><a href="{!! url('billing/company') !!}"><i class="fa fa-angle-right"></i>Billing</a></li>
            <li><a href="{{url('edit/details/user')}}"><i class="fa fa-angle-right"></i>Profile</a></li>
            <li><a href="{{url('savedCards')}}"><i class="fa fa-angle-right"></i>Saved Cards</a></li>
            <li><a href="{{url('edit/smtp/company')}}"><i class="fa fa-angle-right"></i>SMTP</a></li>
        </ul>
    </li>
    <li class="{{ Request::is('affiliates*') ? 'active' : '' }}">
        <a href="{!! route('affiliates.index') !!}"><i class="fa fa-certificate"></i><span>Affiliates</span></a>
    </li>
    <li class="{{ Request::is('levels*') ? 'active' : '' }}">
        <a href="{!! route('levels.index') !!}"><i class="fa fa-link"></i><span>Levels</span></a>
    </li>
    <li class="{{ Request::is('ranks*') ? 'active' : '' }}">
        <a href="{!! route('ranks.index') !!}"><i class="fa  fa-star"></i><span>Ranks</span></a>
    </li>
    <li class="{{ Request::is('revenuehistories*') ? 'active' : '' }}">
        <a href="{!! route('revenuehistories.index') !!}"><i class="fa fa-money"></i><span>Revenue History</span></a>
    </li>
    <li class="{{ Request::is('payouthistories*') ? 'active' : '' }}">
        <a href="{!! route('payouthistories.index') !!}"><i class="fa fa-money"></i><span>Payout History</span></a>
    </li>
    <li class="{{ Request::is('salescontents*') ? 'active' : '' }}">
        <a href="{!! route('salescontents.index') !!}"><i class="fa fa-history"></i><span>Sales Contents</span></a>
    </li>
    <li class="{{ Request::is('emailcontents*') ? 'active' : '' }}">
        <a href="{!! url('emailcontentsedit') !!}"><i class="fa fa-envelope"></i><span>Email Contents</span></a>
    </li>
    <li class="{{ Request::is('weeklyfees*') ? 'active' : '' }}">
        <a href="{!! route('weeklyfees.index') !!}"><i class="fa fa-money"></i><span>Weekly Fees</span></a>
    </li>
@elseif(Auth::user()->status == '2')
    <li class="treeview {{ Request::is('edit/details*') ? 'active' : '' }}">
        <a href="#"><i class="fa fa-cog"></i> <span>Settings</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{url('edit/details/user')}}"><i class="fa fa-angle-right"></i>Profile</a></li>
        </ul>
    </li>
    <li class="{{ Request::is('affiliates*') ? 'active' : '' }}">
        <a href="{!! route('affiliates.index') !!}"><i class="fa fa-certificate"></i><span>Affiliates</span></a>
    </li>
    <li class="{{ Request::is('weeklyfees*') ? 'active' : '' }}">
        <a href="{!! route('weeklyfees.index') !!}"><i class="fa fa-money"></i><span>Weekly Fees</span></a>
    </li>
@endif



