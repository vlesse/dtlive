@extends('admin.layouts.master')

@section('title', 'Upcoming TV Show')

@section('content')
    <div class="body-content">
        <!-- mobile title -->
        <h1 class="page-title-sm">@yield('title')</h1>

        <div class="border-bottom row mb-3">
            <div class="col-sm-10">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{__('Label.Dashboard')}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Upcoming TV Show</li>
                </ol>
            </div>
            <div class="col-sm-2 d-flex align-items-center justify-content-end">
                <a href="{{ route('upcomingtvshowAdd') }}" class="btn btn-default mw-150" style="margin-top: -14px;">{{__('Label.Add TV Show')}}</a>
            </div>
        </div>

        <!-- Search -->
        <div class="page-search mb-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1"><img src="{{ asset('assets/imgs/search.png') }}"></span>
                </div>
                <input type="text" id="input_search" class="form-control" placeholder="Search Upcoming TV Show" aria-label="Search" aria-describedby="basic-addon1">
            </div>
            <div class="sorting mr-3" style="width: 450px;">
                <label>Sort by :</label>
                <select class="form-control" id="input_type">
                    <option value="all">All Type</option>
                    @for ($i = 0; $i < count($type); $i++) 
                        <option value="{{ $type[$i]['id'] }}" @if(isset($_GET['input_type'])){{ $_GET['input_type'] == $type[$i]['id'] ? 'selected' : ''}} @endif>
                            {{ $type[$i]['name'] }}
                        </option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped TVShow-table text-center table-bordered">
                <thead>
                    <tr style="background: #F9FAFF;">
                        <th> {{__('Label.#')}} </th>
                        <th> {{__('Label.Image')}} </th>
                        <th> {{__('Label.Name')}} </th>
                        <th> {{__('Label.Type')}} </th>
                        <th> {{__('Label.Details')}} </th>
                        <th> Episodes </th>
                        <th> {{__('Label.Action')}} </th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        $(document).ready(function() {
            $(function() {
                var table = $('.TVShow-table').DataTable({
                    dom: "<'top'f>rt<'row'<'col-2'i><'col-1'l><'col-9'p>>",
                    searching: false,
                    responsive: true,
                    autoWidth: false,
                    processing: true,
                    serverSide: true,
                    lengthMenu: [[10, 100, 500, -1], [10, 100, 500, "All"]],
                    language: {
                        paginate: {
                            previous: "<img src='{{url('assets/imgs/left-arrow.png')}}' >",
                            next: "<img src='{{url('assets/imgs/left-arrow.png')}}' style='transform: rotate(180deg)'>"
                        }
                    },
                    ajax: {
                        url: "{{ route('upcomingTVShowData') }}",
                        data: function(d) {
                            d.input_search = $('#input_search').val();
                            d.input_type = $('#input_type').val();
                        },
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'thumbnail',
                            name: 'thumbnail',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, full, meta) {
                                return "<a href='" + data + "' target='_blank' title='Watch'><img src='" + data + "' class='img-thumbnail' style='height:55px; width:55px'></a>";
                            },
                        },
                        {
                            data: 'name',
                            name: 'name',
                            render: function(data, type, full, meta) {
                                if (data) {
                                    return data;
                                } else {
                                    return "-";
                                }
                            }
                        },
                        {
                            data: 'type.name',
                            name: 'type.name'
                        },
                        {
                            data: 'details',
                            name: 'details',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'season',
                            name: 'season',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                });

                $('#input_type').change(function(){
                    table.draw();
                });
                $('#input_search').keyup(function() {
                    table.draw();
                });
            });
        });
    </script>
@endsection