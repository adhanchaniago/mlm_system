<?php
use App\Models\sliderImages;
?>
@extends('layouts.app')
<style>
    .updateSliderImg{
        width: 100%;
        height: 250px;
        padding: 1%;
    }
    .deletesliderBtn{
        background: red;
        color: white;
        border: none;
    }
    .aboutImgEditPage{
        width: 300px;
        height: 250px;
    }
</style>
@section('content')
    <section class="content-header">
        <h1>
            Front Page
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($frontPage, ['route' => ['frontPages.update', $frontPage->id], 'method' => 'patch','files' => true]) !!}
                   <!-- Slider Image Field -->
                   <?php
                   $sliderImage=sliderImages::whereParentId($frontPage->id)->get();
                   foreach ($sliderImage as $slides) {
                       ?>
                       <input type="hidden" name="sliderId[]" value="{{$slides->id}}">
                       <div class="col-md-12">
                           <div class="col-md-3">
                               <img src="{{asset('public/avatars/'.$slides->image)}}" class="updateSliderImg">
                           </div>
                           <div class="col-md-4">
                               <label>Slider Heading:</label>
                               <input type="text" name="updated_heading[]" id="updatedHeading" class="form-control" value="{{$slides->heading }}">
                           </div>
                           <div class="col-md-4">
                               <label>Slider Text:</label>
                               <input type="text" name="updated_text[]" id="updatedHeading" class="form-control" value="{{ $slides->text }}">
                           </div>
                           <div class="col-md-1">
                               <br/>
                               <button type="button" class="deletesliderBtn" onclick="deleteSlider({{$slides->id}})"><b>X</b></button>
                           </div>

                       </div>
                       <br/>
                       <?php
                   }
                   ?>

                   <div class="form-group col-sm-12" id="InputsWrapper">
                       <div id="AddMoreFileId"> <br>

                       </div>
                       {!! Form::label('slider_image', 'Slider Image:') !!} <a href="#" id="AddMoreFileBox" class="btn btn-success ">+</a><br><br>
                       <input type="file" name="slider_image[]" class="form-control" id="docs"> <br/>
                       <label>Slider Heading:</label>
                       <input type="text" name="slider_heading[]" id="docHeading" class="form-control" placeholder="Slider Heading">
                       <label>Slider Text:</label>  <a href="#"  class="removeclass"></a>
                       <input type="text" name="slider_text[]" class="form-control" id="docName" placeholder="Slider Text">
                   </div>

                   <div id="lineBreak"></div>
                   <!-- Aboutus Main Description Field -->
                   <div class="form-group col-sm-12">
                       {!! Form::label('aboutUs_main_description', 'About Us Main Description:') !!}
                       <textarea id="desc1" name="aboutUs_main_description" cols="50" rows="4">{{$frontPage->aboutUs_main_description}}</textarea>
                   </div>

                   <!-- Aboutus Sub Description Field -->
                   <div class="form-group col-sm-12">
                       {!! Form::label('aboutUs_sub_description', 'About Us Sub Description:') !!}
                       <textarea id="desc2" name="aboutUs_sub_description" cols="50" rows="4">{{$frontPage->aboutUs_sub_description}}</textarea>
                   </div>

                   <!-- Aboutus Image Field -->
                   <div class="form-group col-sm-12">
                       {!! Form::label('aboutUs_image', 'About us Image:') !!}
                       <input type="file" name="aboutUs_image" class="form-control">
                       <img src="{{asset('public/avatars').'/'.$frontPage->aboutUs_image}}" class="aboutImgEditPage">
                   </div>

                   <!-- Submit Field -->
                   <div class="form-group col-sm-12">
                       {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                       <a href="{!! route('frontPages.index') !!}" class="btn btn-default">Cancel</a>
                   </div>

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
@section('scripts')
<link rel="stylesheet" href="{{asset('public/css/editor.css')}}">
<script src="https://cdn.ckeditor.com/4.10.0/full-all/ckeditor.js"></script>
<script>
    // CKEDITOR.replace('brf_desc');
    CKEDITOR.replace('desc1');
    CKEDITOR.replace('desc2');
</script>
<script>
    $(document).ready(function() {

        var MaxInputs       = 1000; //maximum extra input boxes allowed
        var InputsWrapper   = $("#InputsWrapper"); //Input boxes wrapper ID
        var AddButton       = $("#AddMoreFileBox"); //Add button ID

        var x = InputsWrapper.length; //initlal text box count
        var FieldCount=1; //to keep track of text box added

//on add input button click
        $(AddButton).click(function (e) {
            //max input box allowed
            if(x <= MaxInputs) {
                FieldCount++; //text box added ncrement
                //add input box
                $(InputsWrapper).append('<div><br><label>Slider Image:</label>&ensp;<a href="#" class="removeclass"><button type="button" class="btn btn-danger">-</button> </a><br><br/><input type="file" class="form-control" required name="slider_image[]" id="docs'+ FieldCount +'"/><br/><label>Slider Heading:</label>' +
                    '<input type="text" name="slider_heading[]" id="docHeading'+FieldCount+'" required class="form-control" placeholder="Slider Heading"> <label>Slider Text: </label> <br/> <input type="text" name="slider_text[]" class="form-control" required id="docName'+FieldCount+'" placeholder="Slider Text"></div>');
                x++; //text box increment

                $("#AddMoreFileId").show();

                $('AddMoreFileBox').html("Add field");

                // Delete the "add"-link if there is 3 fields.
                if(x == 1000) {
                    $("#AddMoreFileId").hide();
                    $("#lineBreak").html("<br>");
                }
            }
            return false;
        });

        $("body").on("click",".removeclass", function(e){ //user click on remove text
            if( x > 1 ) {
                $(this).parent('div').remove(); //remove text box
                x--; //decrement textbox

                $("#AddMoreFileId").show();

                $("#lineBreak").html("");

                // Adds the "add" link again when a field is removed.
                $('AddMoreFileBox').html("Add field");
            }
            return false;
        })

    });
</script>
<script>
    function deleteSlider(id) {
        // alert(id)
        $.ajax({
            url: '{{url('deleteSliderData')}}' + '/' + id,

            success: function (data) {
                window.location.reload();
            }

            })

    }
</script>
@endsection