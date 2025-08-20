@section('Applications-li', 'mm-active')
@section('Applications-ul', 'mm-show')
@section('assgin-bus', 'mm-active')
@include('layouts.header')

@include('layouts.sidebar')
<style type="text/css">
    .select2-selection__rendered {
        line-height: 35px !important;
    }

    .select2-container .select2-selection--single {
        height: 36px !important;
    }

    .select2-selection__arrow {
        height: 38px !important;
    }
</style>
<!-- Dashboard Header  section -->
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title app-page-title-simple">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <div class="page-title-head center-elem">
                            <span class="d-inline-block pr-2">
                                <i class="lnr-car opacity-6"></i>
                            </span>
                            <span class="d-inline-block"> Bus Driver & Route Assign</span>
                        </div>
                        <div class="page-title-subheading opacity-10">
                            <nav class="" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a>
                                            <i aria-hidden="true" class="fa fa-home"></i>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a>Dashboards</a>
                                    </li>
                                    <li class="active breadcrumb-item" aria-current="page">
                                        Bus Driver & Route Assign
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dashboard Row section  -->
        @include("layouts.includes.flash")
        <div class="row">
            <div class="col-md-12">

                <div id="expense_details_msg"></div>

                <div class="main-card mb-3 card">

                    <form method="post" action="{{ route('bus.assign') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <button type="button" class="close" aria-label="Close"><span aria-hidden="true"
                                style="font-weight: bold;">&times;</span></button>
                        <div class="card-body ">

                            <div class="section">
                                <h5 class="card-title" style="margin-top: 7px;">Select Assign Bus Route & Driver: </h5>
                                <div class="form-row">
                                    <div class="form-group form-line-height col-lg-3 col-md-12">
                                        <label class="input-label" for="bus">Bus Name <span
                                                class="important text-danger">*</span></label>
                                        <select id="bus" class="js-example-basic-single js-states form-control"
                                            name="bus_id">
                                            <option value="">Select A Bus</option>
                                            @foreach ($busList as $bus)
                                            <option value="{{ $bus->id}}">{{ $bus->bus_name }} ({{ $bus->bus_number }})
                                            </option>
                                            @endforeach

                                        </select>

                                    </div>
                                    <div class="form-group form-line-height col-lg-3 col-md-12">
                                        <label class="input-label" for="driver">Driver Name <span
                                                class="important text-danger">*</span>
                                        </label>

                                        <select id="driver" class="js-example-basic-single js-states form-control"
                                            name="driver_user_id">
                                            <option value="">Select A Driver</option>
                                            @foreach ($driver_list as $driver)
                                            <option value="{{ $driver->id}}">{{ $driver->name }} ({{ $driver->mobile }})
                                            </option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="form-group form-line-height col-lg-3 col-md-12">
                                        <label class="input-label" for="route">Route Name <span
                                                class="important text-danger">*</span>
                                        </label>

                                        <select id="route" class="js-example-basic-single js-states form-control"
                                            name="route_id">
                                            <option value="">Select Your Route</option>
                                            @foreach ($routeList as $route)
                                            <option value="{{ $route->id}}">{{ $route->route_name }} ({{
                                                $route->route_code }})
                                            </option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <div class="form-group form-line-height offset-1 col-lg-2 col-md-12">
                                        <div class="position-relative form-group" id="submit_button">
                                            <input value="Submit" id="date"
                                                class="mr-2 mt-4 btn btn-md btn-shadow btn-gradient-info btn-lg"
                                                type="submit">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h6 class="page-header text-primary">Note: Please reassign the bus after deleting the assign
                                driver !
                            </h6>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <!-- table Section -->
        <!-- <div class="mbg-3 h-auto pl-0 pr-0 bg-transparent no-border card-header">
            <div class="card-header-title fsize-5 text-capitalize font-weight-normal"></div>
        </div> -->

        <div class="main-card mb-3 card">
            <div class="card-body">
                <table style="width: 100%;" id="process_data_table"
                    class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Bus Name</th>
                            <th>Bus Number</th>
                            <th>Driver Name</th>
                            <th>Driver Mobile</th>
                            <th>Route Name</th>
                            <th>Route Code</th>
                            <th>Assigned Date</th>
                            <th>
                                <center>Action</center>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <script>
            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });
            function ajaxStatus(id, the, action) {

                Swal.fire({
                                title: 'Are you sure?',
                                text: 'This activity will effected on Assign Bus & Route',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'No'
                            }).then((result) => {
                                if (result.isConfirmed) {

                                    $.ajax({

                                        url: '{{ route('assign.bus.delete') }}',
                                        type: 'post',
                                        data: {
                                            id: id,
                                            type: 'status',
                                            action: action

                                        },
                                        dataType: 'json',
                                        success: function(response) {

                                            if(response.action=='1'){
                                              Swal.fire("Done", "Assign Bus Route & Driver Remove Successfully", "success");
                                              $('#process_data_table').DataTable().ajax.reload();
                                            }else{
                                                Swal.fire(
                                                    'Action Error',
                                                    'Your imaginary status is not change.',
                                                    'error'
                                                )
                                            }

                                        }
                                    });
                                } else if (result.dismiss === Swal.DismissReason.cancel) {
                                    Swal.fire(
                                        'Cancelled',
                                        'Your imaginary status is safe.',
                                        'error'
                                    )
                                }
                    });

            }
        </script>
    </div>
</div>

@include('layouts.footer')
<script type="text/javascript">
    $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var table =
        $('#process_data_table').DataTable({
            processing: false,
            serverSide: true,
            paging: true,
            pageLength: 10,
            lengthChange: true,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            dom: 'l<"#date-filter">frtip',
            ajax: {
                url: '{{ route("assign.bus.data")}}',
                type: 'POST',
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns: [
                {
                    "data": 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'bus_name',
                    name: 'bus_name',
                    searchable: false,

                },
                {
                    data: 'bus_number',
                    name: 'bus_number',
                    searchable: true,
                },
                {
                    data: 'name',
                    name: 'name',
                    searchable: true,
                },
                {
                    data: 'mobile',
                    name: 'mobile',
                    searchable: true,

                },
                {
                    data: 'route_name',
                    name: 'route_name',
                    searchable: false,
                },
                {
                    data: 'route_code',
                    name: 'route_code',
                    searchable: false,
                },
                {
                    data: 'created_at',

                    name: 'created_at',

                    searchable: false,

                    render: function(data, type, row) {

                        return moment(row.created_at).format("Do MMMM YYYY");
                    }

                },
                {
                    data: 'action',
                    name: 'action',
                    searchable: false,
                }
            ]
        });

        $("table").wrapAll("<div style='overflow-x:auto;width:100%' />");
        $('.dataTables_wrapper').addClass('row');
        $('#process_data_table_length').addClass('col-lg-3 col-md-3 col-sm-3');
        $('#process_data_table_length select').addClass('custom-select custom-select-sm form-control form-control-sm');
        $('#date-filter').addClass('col-lg-4 col-md-4 col-sm-4 adjust');
        $('#process_data_table_filter').addClass('offset-2 col-md-2 col-sm-2');
        $('#process_data_table_filter input').addClass('form-control form-control-sm');

        var date_picker_html = '<div id="date_range" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;"> <i class="fa fa-calendar"> </i>&nbsp; <span> </span> <i class="fa fa-caret-down"></i></div>';
        $('#date-filter').append(date_picker_html);

        $(function() {
            var start = moment().subtract(29, 'days');
            var end = moment();
            function cb(start, end) {
                $('#date_range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                    'MMMM D, YYYY'));
                var range = start.format("YYYY-MM-DD") + "~" + end.format("YYYY-MM-DD");
                table.columns(5).search(range).draw();
            }

            $('#date_range').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                        .subtract(1, 'month').endOf('month')
                    ]
                }
            }, cb);
            $('#date_range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                'MMMM D, YYYY'));
        });
    });
</script>
<script>
    $("#add_expense_open").click(function(event) {
        $("#add_expense_form_view").show("slow");
    });

    $("#add_expense_close").click(function(event) {
        $("#add_expense_form_view").hide("slow");
    });

    $(".numeric").keydown(function(event) {
        if ( event.keyCode == 46 || event.keyCode == 8 ) {
            // 46 => for delete button
            // 8 => for backspace
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.keyCode < 48 || event.keyCode > 57) {
                if(event.keyCode < 97 || event.keyCode > 105){
                    event.preventDefault();
                }
            }
        }
    });


</script>
