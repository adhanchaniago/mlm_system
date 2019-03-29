

@if(Auth::user()->status == '0')

    <li class="{{ Request::is('plantables*') ? 'active' : '' }}">

        <a href="{!! route('plantables.index') !!}"><i class="fa fa-tasks"></i><span>Set up a Plan</span></a>

    </li>

    <li class="{{ Request::is('companies*') ? 'active' : '' }}">

        <a href="{!! route('companies.index') !!}"><i class="fa fa-building-o"></i><span>Companies</span></a>

    </li>

    <li class="{{ Request::is('timeout*') ? 'active' : '' }}">

        <a href="{!! url('timeout') !!}"><i class="fa fa-bell"></i><span>Time to Block</span></a>

    </li>

    <li class="{{ Request::is('frontPages*') ? 'active' : '' }}">

        <a href="{!! route('frontPages.index') !!}"><i class="fa fa-file"></i><span>Front Pages</span></a>

    </li>

    <li class="{{ Request::is('message*') ? 'active' : '' }}">

        <a href="{!! url('messages') !!}"><i class="fa fa-comments"></i><span>Messages</span></a>

    </li>

@elseif(Auth::user()->status == '1')

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

        <a href="{!! route('emailcontents.index') !!}"><i class="fa fa-envelope"></i><span>Email Contents</span></a>

    </li>



    <li class="{{ Request::is('weeklyfees*') ? 'active' : '' }}">

        <a href="{!! route('weeklyfees.index') !!}"><i class="fa fa-money"></i><span>Weekly Fees</span></a>

    </li>



@elseif(Auth::user()->status == '2')

    <li class="{{ Request::is('affiliates*') ? 'active' : '' }}">

        <a href="{!! route('affiliates.index') !!}"><i class="fa fa-certificate"></i><span>Affiliates</span></a>

    </li>

    <li class="{{ Request::is('weeklyfees*') ? 'active' : '' }}">

        <a href="{!! route('weeklyfees.index') !!}"><i class="fa fa-money"></i><span>Weekly Fees</span></a>

    </li>



@endif



