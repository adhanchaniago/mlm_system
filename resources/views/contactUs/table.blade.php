<table class="table table-responsive" id="contactUs-table">
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Message</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php
       $i=1;
    ?>
    @foreach($contacts as $contact)
    <tr>
        <td>{{$i}}</td>
        <td>{{$contact->name}}</td>
        <td>{{$contact->email}}</td>
        <td>{{$contact->msg}}</td>
        <td>
            <div class='btn-group'>
                <button class="btn btn-danger btn-xs" onclick="deleteMsg('{{$contact->id}}')"><i class="glyphicon glyphicon-trash"></i></button>
            </div>
        </td>
    </tr>
    <?php
       $i++;
    ?>
    @endforeach
    </tbody>
</table>