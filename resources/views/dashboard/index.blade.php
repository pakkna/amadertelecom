@section('dashboard', 'mm-active')
@section('Applications-li', 'mm-active')
@section('Applications-ul', 'mm-show')
@include('layouts.header')

@include('layouts.sidebar')
<script type="text/javascript">
    function go_map(params) {
        let settings = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,
        width=800,height=600,left=100,top=100`;
        if (params.indexOf('http') > -1) {
            open(params, 'test',settings);
        }else{
            alert("Map Url Is Not Valid !");
        }

    }
</script>
<!-- Dashboard Header  section -->

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title app-page-title-simple">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <div class="page-title-head center-elem">
                            <span class="d-inline-block pr-2">
                                <i class="lnr-bus opacity-6"></i>
                            </span>
                            <span class="d-inline-block">Amader Telecom Recharge System</span>
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
                                        Home
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Dashboard Row section -->

        <div class="mbg-3 h-auto pl-0 pr-0 bg-transparent no-border card-header">
            <div class="card-header-title fsize-2 text-capitalize font-weight-normal">Overview Section</div>
        </div>

        <div class="row">
            <div class="col-md-2">
                <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
                    <div class="widget-chat-wrapper-outer">
                        <div class="widget-chart-content">
                            <h6 class="widget-subheading">Total Package</h6>
                            <div class="widget-chart-flex">
                                <div class="widget-numbers mb-0 w-100">
                                    <div class="widget-chart-flex text-primary">
                                        <div class="fsize-4">
                                            <small><i class="fa fa-box"> </i></small>
                                            {{ $totalBus }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
                    <div class="widget-chat-wrapper-outer">
                        <div class="widget-chart-content">
                            <h6 class="widget-subheading">Order Completed</h6>
                            <div class="widget-chart-flex">
                                <div class="widget-numbers mb-0 w-100">
                                    <div class="widget-chart-flex">
                                        <div class="fsize-4 text-success">
                                            <small class="text-success"><i class="fa fa-shopping-cart"></i></small>
                                            {{ $unAssignBus }}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
                    <div class="widget-chat-wrapper-outer">
                        <div class="widget-chart-content">
                            <h6 class="widget-subheading">Pending Request</h6>
                            <div class="widget-chart-flex">
                                <div class="widget-numbers mb-0 w-100">
                                    <div class="widget-chart-flex">
                                        <div class="fsize-4 text-warning">
                                            <small class=" text-warning"><i class="fa fa-hourglass-half"></i></small>
                                            {{ $assignBus }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
                    <div class="widget-chat-wrapper-outer">
                        <div class="widget-chart-content">
                            <h6 class="widget-subheading">Order Cancel</h6>
                            <div class="widget-chart-flex">
                                <div class="widget-numbers mb-0 ">
                                    <div class="widget-chart-flex text-danger">
                                        <div class="fsize-4">
                                            <small class="text-danger"> <i class="fa fa-times-circle"></i></small>
                                            {{ $totalRoute }}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
                    <div class="widget-chat-wrapper-outer">
                        <div class="widget-chart-content">
                            <h6 class="widget-subheading">Refund Request</h6>
                            <div class="widget-chart-flex">
                                <div class="widget-numbers mb-0 ">
                                    <div class="widget-chart-flex text-success">
                                        <div class="fsize-4">
                                            <small class="text-success"><i class="fa fa-undo-alt"></i>
                                            </small>
                                            {{ $totalUser }}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-left card">
                    <div class="widget-chat-wrapper-outer">
                        <div class="widget-chart-content">
                            <h6 class="widget-subheading">Registered Users</h6>
                            <div class="widget-chart-flex">
                                <div class="widget-numbers mb-0 w-100">
                                    <div class="widget-chart-flex text-primary">
                                        <div class="fsize-4">
                                            <small><i class="fa fa-users"></i></small>
                                            {{ $totalDriver }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-header">
                <div class="card-header-title font-size-lg text-capitalize font-weight-normal">Pending Order Request
                </div>

            </div>
            <div class="table-responsive">

                <div class="card-body">

                    <table style="width: 100%;" id="process_data_table"
                        class="table table-hover table-striped table-bordered">

                        <thead>
                            <th>Order Number</th>
                            <th>Username</th>
                            <th>Request Mobile</th>
                            <th>Package Name</th>
                            <th>Package Info</th>
                            <th>Request Status</th>
                            <th>Request Time</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#ORD12345</td>
                                <td>Samim</td>
                                <td>+8801712345678</td>
                                <td>ROBI DHAMAKA</td>
                                <td>50GB Data, 5 Users</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>2025-07-20 14:35</td>
                            </tr>
                            <tr>
                                <td>#ORD12346</td>
                                <td>Mehedi</td>
                                <td>+8801912345678</td>
                                <td>Dual Combo</td>
                                <td>10GB Data, 1 User</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>2025-07-20 15:10</td>
                            </tr>
                            <tr>
                                <td>#ORD12347</td>
                                <td>Rudro</td>
                                <td>+8801812345678</td>
                                <td>30GB DATA PLAN</td>
                                <td>20GB Data, 3 Users</td>
                                <td><span class="badge bg-danger text-white">Cancelled</span></td>
                                <td>2025-07-20 16:00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-block p-4 text-center card-footer">

                <a class="btn-pill btn-shadow btn-wide fsize-1 btn btn-dark btn-lg"
                    href="{{url('/pending-order-list')}}">
                    <span class="mr-2 opacity-7"><i class="fa fa-cog fa-spin"></i>
                    </span>
                    <span class="mr-1">Show All Pedning List</span>
                </a>

            </div>

        </div>

    </div>

</div>

@include('layouts.footer')
{{-- <script type="text/javascript">
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
                    url: '{{ route("assign.route.data")}}',
                    type: 'POST',
                    data: function(d) {
                        d._token = "{{ csrf_token() }}";
                    }
                },
                columns: [
                    {
                        data: 'bus_name',
                        name: 'bus_name',
                        searchable: false,
                        render: function(data, type, row) {
                            return row.bus_name+' ('+row.bus_number+')';
                        }

                    },

                    {
                        data: 'name',
                        name: 'name',
                        searchable: false,

                        render: function(data, type, row) {
                            return row.name+' ('+row.mobile+')';
                        }
                    },
                    {
                        data: 'route_name',
                        name: 'route_name',
                        searchable: false,
                        render: function(data, type, row) {
                            return row.route_name+' ('+row.route_code+')';
                        }
                    },
                    {
                        data: 'start_time_slot',
                        name: 'start_time_slot',
                        searchable: false,
                    },
                    {
                        data: 'departure_time_slot',
                        name: 'departure_time_slot',
                        searchable: false,
                    },
                    {
                        data: 'route_map_url',
                        name: 'route_map_url',
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
</script> --}}
