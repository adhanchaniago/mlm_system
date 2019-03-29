<table class="table table-responsive" id="billing-table">
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Amount</th>
        <th>Card Number</th>
        <th>Plan</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i=1;
    ?>
    @foreach($stripes as $stripe)
        <?php
           if(\App\Models\plantable::whereId($stripe->planid)->exists())
           {
                $plan = \App\Models\plantable::whereId($stripe->planid)->first();
                $plan_name = $plan->name;
           }
           else
           {
               $plan_name = "";
           }
        ?>
        <tr>
            <td>{{$i}}</td>
            <td>{{$stripe->name}}</td>
            <td>{{$stripe->email}}</td>
            <td>{{$stripe->amount}}</td>
            <td>{{$stripe->card_number}}</td>
            <td>{{$plan_name}}</td>
            <td>{{$stripe->date}}</td>
            <td>
                <div class='btn-group'>
                    <button class="btn btn-danger btn-xs" onclick="deleteBill('{{$stripe->id}}')"><i class="glyphicon glyphicon-trash"></i></button>
                </div>
            </td>
        </tr>
        <?php
        $i++;
        ?>
    @endforeach
    </tbody>
</table>