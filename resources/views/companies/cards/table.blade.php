<table class="table table-responsive" id="cards-table">
    <thead>
    <tr>
        <th>Card Number</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i=1;
    ?>
    @if($cards != "")
    @foreach($cards as $card)
        <?php
            $card_number = \Illuminate\Support\Facades\Crypt::decrypt($card->card_detail);
            $card_month = \Illuminate\Support\Facades\Crypt::decrypt($card->card_detail_m);
            $card_year = \Illuminate\Support\Facades\Crypt::decrypt($card->card_detail_y);
            $card_number_show = 'XXXX-XXXX-XXXX-'.substr($card_number,-4);
        ?>
        <tr>
            <td>{{$card_number_show}}</td>
            <td>
                @if($card->status == 1)
                    <h4 class="card-active"><b>Active</b></h4>
                @else
                    <h4 class="card-inactive"><b>Not Active</b></h4>
                @endif
            </td>
            <td>
                <div class='btn-group'>

                    <a class="btn btn-default btn-xs" href="{{url('editCard').'/'.$card->id}}"><i class="fa fa-edit"></i></a>
                    @if($card->status == 0)
                        <a class="btn btn-danger btn-xs" href="{{url('deletecard').'/'.$card->id}}" onclick="return confirm('Are you sure?')"><i class="glyphicon glyphicon-trash"></i></a>
                        <button class="btn btn-success btn-xs" type="button" onclick="activateCard('{{$card->id}}')"><i class="fa fa-check"></i></button>
                    @elseif($card->status == 1)
                        <button class="btn btn-danger btn-xs activeBtn"><i class="glyphicon glyphicon-trash"></i></button>
                        <button class="btn btn-success btn-xs activeBtn" type="button" ><i class="fa fa-check"></i></button>
                    @endif
                </div>
            </td>
        </tr>
        <?php
        $i++;
        ?>
    @endforeach
    @else
    @endif
    </tbody>
</table>