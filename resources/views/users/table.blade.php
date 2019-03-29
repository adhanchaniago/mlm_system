<?php
$users=\App\User::get();
?>
<table class="table table-responsive" id="users-table">
    <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Type</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $userr)
    <tr>
        <td>{!! $userr->name !!}</td>
        <td>{!! $userr->email !!}</td>
        <td>
            <?php
            if ($userr->status != 'superadmin') {
                ?>
                <select class="form-control" id="type{{$userr->id}}" onchange="selectuser(<?php echo $userr->id; ?>)">
                    <?php
                    if ($userr->status == NULL) {
                        ?>
                        <option disabled selected>Choose One</option>
                        <?php
                    } else {
                        ?>
                        <option disabled selected value="{{$userr->status}}">{{$userr->status}}</option>
                        <?php
                    }
                    ?>
                    <option value="admin">Admin</option>
                    <option value="affiliate">Affiliate</option>
                </select>
                <?php
            }
            else {
                ?>
        {!! $userr->status !!}
                <?php
            }
            ?>
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
<script>
    function selectuser(id) {
        var val = $('#type'+id).val();
        $.ajax({
            url:'{{'selectType'}}'+'/'+id+'/'+val,
            success: function (data) {
                console.log();
            }
        });
    }
</script>