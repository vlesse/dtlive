@extends('admin.layouts.master')

@section('title', 'Add Upcoming TVShow')

@section('content')
<div class="body-content">
    <!-- mobile title -->
    <h1 class="page-title-sm">@yield('title')</h1>

    <div class="border-bottom row mb-3">
        <div class="col-sm-10">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">{{__('Label.Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('upcomingTVShow') }}">TV Show</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Add Upcoming TVShow
                </li>
            </ol>
        </div>
        <div class="col-sm-2 d-flex align-items-center justify-content-end">
            <a href="{{ route('upcomingTVShow') }}" class="btn btn-default mw-120" style="margin-top:-14px">{{__('Label.TV Show')}}</a>
        </div>
    </div>

    <div class="card custom-border-card mt-3">
        <form enctype="multipart/form-data" id="save_TVShow">
            @csrf
            <input name="release_year" type="hidden" class="form-control" id="release_year">
            <input name="imdb_rating" type="hidden" class="form-control" id="imdb_rating">

            <div class="custom-border-card">
                <div class="form-row">
                    <div class="col-md-2">
                        <div class="form-group pt-3">
                            <label>Import From IMDb</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" name="Imdb_id" id="Imdb_id" class="form-control" placeholder="Enter IMDb ID (e.g. tt15477488)">
                            <label class="mt-1 text-gray">Recommended : Search by IMDb ID for better result <a href="https://developer.imdb.com/documentation/key-concepts" target="_blank" class="btn-link">Click Here</a> </label>
                        </div>
                    </div>
                    <div class="col-md-2 ml-5">
                        <div class="form-group">
                            <button type="button" class="btn btn-default mw-120" onclick="imdb_data_fetch()">Fetch</button>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 ml-5">
                        <div class="form-group">
                            <label>OR</label>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-2 pt-3">
                        <div class="form-group">
                            <label>TV Show {{__('Label.Name')}}</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <input type="text" name="name" id="Imdb_name" list="Imdb_name_list" class="form-control" placeholder="Enter TV Show Name">
                            <datalist id="Imdb_name_list"></datalist>
                        </div>
                    </div>
                </div>
            </div>
            <div class="custom-border-card">
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__('Label.Type')}}</label>
                            <select class="form-control" name="type_id" id="type_id">
                                <option value=""> {{__('Label.Select Type')}}</option>
                                @foreach ($type as $key => $value)
                                <option value="{{ $value->id}}">
                                    {{ $value->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__('Label.Channel')}}</label>
                            <select class="form-control" id="channel_id" name="channel_id">
                                <option value="">{{__('Label.Select Channel')}}</option>
                                @foreach ($channel as $key => $value)
                                <option value="{{ $value->id}}">
                                    {{ $value->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category_id">{{__('Label.Category')}}</label>
                            <select class="form-control selectd2" style="width:100%!important;" name="category_id[]" multiple id="category_id">
                                <option value="" hidden>{{__('Label.Select Category')}}</option>
                                @foreach ($category as $key => $value)
                                <option value="{{ $value->id}}">
                                    {{ $value->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="language_id">{{__('Label.Language')}}</label>
                            <select class="form-control  selectd2_1" style="width:100%!important;" name="language_id[]" id="language_id" multiple>
                                <option value="" hidden>{{__('Label.Select Language')}}</option>
                                @foreach ($language as $key => $value)
                                <option value="{{ $value->id}}">
                                    {{ $value->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cast_id">{{__('Label.Cast')}}</label>
                            <select class="form-control selectd2_2" style="width:100%!important;" name="cast_id[]" multiple id="cast_id">
                                @foreach ($cast as $key => $value)
                                <option value="{{ $value->id}}">
                                    {{ $value->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Release Date</label>
                        <input name="release_date" type="date" class="form-control">
                    </div>
                </div>
            </div>
            <div class="custom-border-card">
                <div class="form-row">
                    <div class="form-group col-lg-6">
                        <label>Trailer Type</label>
                        <select name="trailer_type" id="trailer_type" class="form-control">
                            <option selected="selected" value="server_video">{{__('Label.Server Video')}}</option>
                            <option value="external">External URL</option>
                            <option value="youtube">Youtube</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-6 trailer_box">
                        <div style="display: block;">
                            <label>Trailer</label>
                            <div id="filelist5"></div>
                            <div id="container5" style="position: relative;">
                                <div class="form-group">
                                    <input type="file" id="uploadFile5" name="uploadFile5" style="position: relative; z-index: 1;" class="form-control">
                                </div>
                                <input type="hidden" name="trailer" id="mp3_file_name5" class="form-control">

                                <div class="form-group">
                                    <a id="upload5" class="btn text-white" style="background-color:#4e45b8;">{{__('Label.Upload Files')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 trailer_url_box">
                        <label>Trailer</label>
                        <input name="trailer_url" type="url" class="form-control" placeholder="Enter Trailer URL">
                    </div>
                </div>
            </div>
            <div class="custom-border-card">
                <div class="form-row">
                    <div class="form-group col-lg-12">
                        <label>{{__('Label.Description')}}</label>
                        <textarea name="description" class="form-control" rows="3" id="description" placeholder="{{__('Label.Hello,')}}"></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__('Label.Is Premium')}}</label>
                            <select class="form-control" name="is_premium">
                                <option value="0">{{__('Label.No')}}</option>
                                <option value="1">{{__('Label.Yes')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__('Label.Is Title')}}</label>
                            <select class="form-control" name="is_title">
                                <option value="0">{{__('Label.No')}}</option>
                                <option value="1">{{__('Label.Yes')}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="thumbnail">{{__('Label.Thumbnail Image')}}</label>
                            <input type="file" class="form-control" id="thumbnail" name="thumbnail">
                            <input type="hidden" class="form-control" id="thumbnail_imdb" name="thumbnail_imdb">
                            <label class="mt-1 text-gray">{{__('Label.Note_Image')}}</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="landscape">{{__('Label.Landscape Image')}}</label>
                            <input type="file" class="form-control" id="landscape" name="landscape">
                            <input type="hidden" class="form-control" id="landscape_imdb" name="landscape_imdb">
                            <label class="mt-1 text-gray">{{__('Label.Note_Image')}}</label>
                        </div>
                    </div>
                </div>
                <div class="form-row mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="custom-file">
                                <img src="{{asset('assets/imgs/no_img.png')}}" style="height: 130px; width: 120px;" class="img-thumbnail" id="preview-image-before-upload">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="custom-file">
                                <img src="{{asset('assets/imgs/no_img.png')}}" style="height: 100px; width: 150px;" class="img-thumbnail" id="preview-image-before-upload1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-top mt-2 pt-3 text-right">
                <button type="button" class="btn btn-default mw-120" onclick="save_TVShow()">{{__('Label.SAVE')}}</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('pagescript')
<script>
    function save_TVShow() {
        var Check_Admin = '<?php echo Check_Admin_Access(); ?>';
        if(Check_Admin == 1){
            var formData = new FormData($("#save_TVShow")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("upcomingtvshowSave") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_TVShow', '{{ route("upcomingTVShow") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg, 'failed');
                }
            });
        } else {
            toastr.error('You have no right to add, edit, and delete.');
        }
    }

    $(document).ready(function() {
        $("#category_id").select2();
        $(".selectd2").select2({
            placeholder: "{{__('Label.Select Category')}}"
        });

        $("#language_od").select2();
        $(".selectd2_1").select2({
            placeholder: "{{__('Label.Select Language')}}"
        });

        $("#cast_id").select2();
        $(".selectd2_2").select2({
            placeholder: "{{__('Label.Select Cast')}}"
        });

        $(".trailer_url_box").hide();
        $('#trailer_type').change(function() {
            var optionValue = $(this).val();

            if (optionValue == 'server_video') {
                $(".trailer_box").show();
                $(".trailer_url_box").hide();
            } else {
                $(".trailer_url_box").show();
                $(".trailer_box").hide();
            }
        });
    });

    $('#Imdb_name').keyup(function() {
        var txtVal = this.value;

        if (txtVal.length >= 3) {
            var url = "{{route('TVshowSerachName', '')}}" + "/" + txtVal;
            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: txtVal,
                success: function(resp) {
                    if (resp.status == 200) {
                        if (resp.data.Response = "True") {
                            var Title_Data = resp.data.results;

                            $('#Imdb_name_list').empty();
                            for (let i = 0; i < Title_Data.length; i++) {
                                $('#Imdb_name_list').append('<option id="' + resp.data.results[i].id + '" value="' + resp.data.results[i].title + '"></option>');
                            }
                        }
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastr.error(errorThrown.msg, 'failed');
                }
            });
        }
    });
    $('#Imdb_name').on('input', function() {
        var userText = $(this).val();

        $("#Imdb_name_list").find("option").each(function() {
            if ($(this).val() == userText) {

                var Name = $("#Imdb_name").val();
                c_id = $('#Imdb_name_list').find('option[value="' + Name + '"]').attr('id');

                $("#dvloader").show();
                var url = "{{route('TVshowGetData', '')}}" + "/" + c_id;
                $.ajax({
                    type: "POST",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: c_id,
                    success: function(resp) {
                        $("#dvloader").hide();

                        if (resp.status == 200) {

                            var C_Id = resp.C_Id;
                            var L_Id = resp.L_Id;
                            var C_Insert_Data = resp.C_Insert_Data;
                            var L_Insert_Data = resp.L_Insert_Data;
                            var Poster_img = resp.Poster_img;
                            var Description = resp.Description;
                            var Cast_Id = resp.Cast_Id;
                            var Cast_Insert_Data = resp.Cast_Insert_Data;
                            var Year = resp.Year;
                            var imdbRating = resp.imdbRating;

                            // Append New Category
                            for (let i = 0; i < C_Insert_Data.length; i++) {
                                var data = '<option value="' + C_Insert_Data[i].id + '">' + C_Insert_Data[i].name + '</option>';
                                $('.selectd2').append(data);
                            }
                            $(".selectd2").val(C_Id).trigger("change");

                            // Append New Language
                            for (let i = 0; i < L_Insert_Data.length; i++) {
                                var data = '<option value="' + L_Insert_Data[i].id + '">' + L_Insert_Data[i].name + '</option>';
                                $('.selectd2_1').append(data);
                            }
                            $(".selectd2_1").val(L_Id).trigger("change");

                            // Append New Cast
                            for (let i = 0; i < Cast_Insert_Data.length; i++) {
                                var data = '<option value="' + Cast_Insert_Data[i].id + '">' + Cast_Insert_Data[i].name + '</option>';
                                $('.selectd2_2').append(data);
                            }
                            $(".selectd2_2").val(Cast_Id).trigger("change");

                            // Image 
                            $('#preview-image-before-upload').attr('src', Poster_img);
                            $('#preview-image-before-upload1').attr('src', Poster_img);
                            $('#thumbnail_imdb').attr('value', Poster_img);
                            $('#landscape_imdb').attr('value', Poster_img);
                            // Desctiption
                            $('#description').val(Description);
                            // Year
                            $("#release_year").attr('value', Year);
                            // imdb_rating
                            $("#imdb_rating").attr('value', imdbRating);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown.msg, 'failed');
                    }
                });

            }
        })
    })

    function imdb_data_fetch() {

        var id = $("#Imdb_id").val();

        if (id != "") {
            $("#dvloader").show();
            var url = "{{route('TVshowGetData', '')}}" + "/" + id;
            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: id,
                success: function(resp) {
                    $("#dvloader").hide();

                    if (resp.status == 200) {

                        var C_Id = resp.C_Id;
                        var L_Id = resp.L_Id;
                        var C_Insert_Data = resp.C_Insert_Data;
                        var L_Insert_Data = resp.L_Insert_Data;
                        var Poster_img = resp.Poster_img;
                        var Title = resp.title;
                        var Description = resp.Description;
                        var Cast_Id = resp.Cast_Id;
                        var Cast_Insert_Data = resp.Cast_Insert_Data;
                        var Year = resp.Year;
                        var imdbRating = resp.imdbRating;

                        // Append New Category
                        for (let i = 0; i < C_Insert_Data.length; i++) {
                            var data = '<option value="' + C_Insert_Data[i].id + '">' + C_Insert_Data[i].name + '</option>';
                            $('.selectd2').append(data);
                        }
                        $(".selectd2").val(C_Id).trigger("change");

                        // Append New Language
                        for (let i = 0; i < L_Insert_Data.length; i++) {
                            var data = '<option value="' + L_Insert_Data[i].id + '">' + L_Insert_Data[i].name + '</option>';
                            $('.selectd2_1').append(data);
                        }
                        $(".selectd2_1").val(L_Id).trigger("change");

                        // Append New Cast
                        for (let i = 0; i < Cast_Insert_Data.length; i++) {
                            var data = '<option value="' + Cast_Insert_Data[i].id + '">' + Cast_Insert_Data[i].name + '</option>';
                            $('.selectd2_2').append(data);
                        }
                        $(".selectd2_2").val(Cast_Id).trigger("change");

                        // Image 
                        $('#preview-image-before-upload').attr('src', Poster_img);
                        $('#preview-image-before-upload1').attr('src', Poster_img);
                        $('#thumbnail_imdb').attr('value', Poster_img);
                        $('#landscape_imdb').attr('value', Poster_img);
                        // Title
                        $('#Imdb_name').val(Title);
                        // Desctiption
                        $('#description').val(Description);
                        // Year
                        $("#release_year").attr('value', Year);
                        // imdb_rating
                        $("#imdb_rating").attr('value', imdbRating);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown.msg, 'failed');
                }
            });
        } else {
            alert("Please input IMDb ID");
        }
    }
</script>
@endsection