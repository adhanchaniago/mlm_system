@include('samyLinkedIn.linkedIn_nav')
<div class="container samy_campians">
    <br>
    @include('flash::message')
    <div class="row">

    </div>
</div>
@include('frontEnd.footer')
<script>
    $('#OpenImgUpload').click(function(){
        $('#Error').text('');
        $('#imgupload').trigger('click');
    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imgupload").change(function() {
        readURL(this);
    });
    function validateForm() {
        var img = $('#imgupload').val()
        if(img == "" || img == null){
            $('#Error').text('Image Field is Required');
            $('#launch').attr("type", "button");
        }else{
            $('#launch').attr("type", "submit");
        }
    }
    var maxLength = 144;
    $('textarea').keyup(function() {
        if($(this).val().length < maxLength){
            var textlen = $(this).val().length + 1;
            $('#rchars').text(textlen);
        }
    });
</script>