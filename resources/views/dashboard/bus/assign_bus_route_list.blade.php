@section('Applications-li', 'mm-active')
@section('Applications-ul', 'mm-show')
@section('assign-route-list', 'mm-active')
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
                                <i class="lnr-car opacity-6"></i>
                            </span>
                            <span class="d-inline-block"> Assigned Bus Routes </span>
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
                                        Assigned Bus Routes
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="main-card mb-3 card">
            <div class="card-body">
                <table style="width: 100%;" id="process_data_table"
                    class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Serial</th>
                            <th>Bus Name</th>
                            <th>Driver Name</th>
                            <th>Route Name</th>
                            <th>Start Time</th>
                            <th>Departure Time</th>
                            <th>Route Map</th>
                            <th>Assigned Date</th>
                            <th>
                                <center>Action</center>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
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
                url: '{{ route("assign.route.data")}}',
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
</script>
