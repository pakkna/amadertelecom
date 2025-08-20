@section('Applications-li', 'mm-active')
@section('Applications-ul', 'mm-show')
@section('packages', 'mm-active')
@include('layouts.header')

@include('layouts.sidebar')

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
                            <span class="d-inline-block"> Packages</span>
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
                                        Package List
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-primary btn-lg" id="add_package_open">
            <span class="mr-2 opacity-7">
                <i class="fa fa-plus"></i>
            </span>
            <span class="mr-1 ">Add Package</span>
        </button>
        <br /><br />
        @include("layouts.includes.flash")
        <div class="row" id="add_package_form_view" style="display:none">
            <div class="col-md-12">
                <div class="main-card mb-3 card">

                    <form method="POST" action="{{ route('package.store') }}" enctype="multipart/form-data">
                        @csrf
                        <button type="button" class="close position-block top-0 end-0 m-2" aria-label="Close">
                            <span aria-hidden="true" style="font-weight: bold;" id="add_package_close">&times;</span>
                        </button>

                        <div class="card-body py-2 px-3">
                            <h6 class="mb-3">Package Details</h6>

                            <div class="row g-2">
                                <!-- Package Name -->
                                <div class="col-md-6 col-lg-3">
                                    <label class="small">Package Name <span class="text-danger">*</span></label>
                                    <input type="text" name="package_name" class="form-control form-control-sm"
                                        value="{{ old('package_name') }}" required>
                                </div>

                                <!-- Package Duration -->
                                <div class="col-md-6 col-lg-3">
                                    <label class="small">Duration <span class="text-danger">*</span></label>
                                    <input type="text" name="duration" class="form-control form-control-sm"
                                        value="{{ old('duration') }}" required>
                                </div>

                                <!-- Operator -->
                                <div class="col-md-6 col-lg-3">
                                    <label class="small">Operator <span class="text-danger">*</span></label>
                                    <select name="operator_id" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        @foreach($operators as $operator)
                                        <option value="{{ $operator->id }}" {{ old('operator_id')==$operator->id ?
                                            'selected' : '' }}>
                                            {{ $operator->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Category -->
                                <div class="col-md-6 col-lg-3">
                                    <label class="small">Category <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id')==$category->id ?
                                            'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Actual Price -->
                                <div class="col-md-6 col-lg-3">
                                    <label class="small">Actual Price <span class="text-danger">*</span></label>
                                    <input type="number" name="actual_price" step="0.01"
                                        class="form-control form-control-sm" value="{{ old('actual_price') }}" required>
                                </div>

                                <!-- Offer Price -->
                                <div class="col-md-6 col-lg-3">
                                    <label class="small">Offer Price <span class="text-danger">*</span></label>
                                    <input type="number" name="offer_price" step="0.01"
                                        class="form-control form-control-sm" value="{{ old('offer_price') }}" required>
                                </div>

                                <!-- Tag -->
                                <div class="col-md-6 col-lg-3">
                                    <label class="small">Tag</label>
                                    <input type="text" name="tag" class="form-control form-control-sm"
                                        value="{{ old('tag') }}">
                                </div>

                                <!-- Status -->
                                <div class="col-md-6 col-lg-3">
                                    <label class="small">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control form-control-sm" required>
                                        <option value="1" {{ old('status')=='1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status')=='0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="mt-3 text-end">
                                <button type="submit" class="btn btn-sm btn-info px-4">Submit</button>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <form method="post" action="" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="card-body">
                    <table style="width: 100%;" id="process_data_table"
                        class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Serial</th>
                                <th>Package Name</th>
                                <th>Package Duration</th>
                                <th style="width: 100px;">Operator</th>
                                <th>Category</th>
                                <th>Actual Price</th>
                                <th>Offer Price</th>
                                <th>Tag</th>
                                <th>Status</th>
                                <th>
                                    <center>Action</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($getPackages as $key => $package)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $package->package_name }}</td>
                                <td>{{ $package->duration }}</td>
                                <td>
                                    @php
                                    $operatorName = strtolower($package->operator->name ?? '');

                                    $operatorInfo = match($operatorName) {
                                    'robi' => [
                                    'logo' =>asset('assets/images/robi.png'),
                                    ],
                                    'airtel' => [
                                    'logo' => asset('assets/images/airtel.svg'),
                                    ],
                                    'gp', 'grameenphone' => [
                                    'logo' => asset('assets/images/gp.png'),
                                    ],
                                    'banglalink' => [
                                    'logo' => asset('assets/images/banglalink.png'),
                                    ],
                                    'taletalk' => [
                                    'logo' => asset('assets/images/taletalk.png'),
                                    ],
                                    };
                                    @endphp

                                    <span>
                                        <img src="{{ $operatorInfo['logo'] }}" alt="logo" style="width:40px;"
                                            class="me-1" style="object-fit: contain;">
                                    </span>

                                </td>

                                <td>{{ $package->category->name ?? 'N/A' }}</td>
                                <td>{{ $package->actual_price }}</td>
                                <td>{{ $package->offer_price }}</td>
                                <td><span class="badge bg-warning">{{$package->tag?? '' }}</span></td>
                                <td>
                                    @php
                                    $status = $package->status ?? null;

                                    $statusInfo = match($status) {
                                    1 => ['label' => 'Active', 'class' => 'badge bg-success'],
                                    0 => ['label' => 'Inactive', 'class' => 'badge bg-danger'],
                                    default => ['label' => 'Unknown', 'class' => 'badge bg-secondary'],
                                    };
                                    @endphp

                                    <span class="{{ $statusInfo['class'] }}">
                                        {{ $statusInfo['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <center>
                                        <a href="{{ route('package.edit', $package->id) }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                        <a href="{{ route('package.delete', $package->id) }}"
                                            onclick="return confirm('Are you sure you want to delete this package?');"
                                            class="btn btn-sm btn-danger">
                                            Delete
                                        </a>
                                    </center>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
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
                                text: 'This activity will effected on assign bus & route !',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'No'
                            }).then((result) => {
                                if (result.isConfirmed) {

                                    $.ajax({

                                        url: '{{ route('bus.delete') }}',
                                        type: 'post',
                                        data: {
                                            id: id,
                                            type: 'status',
                                            action: action

                                        },
                                        dataType: 'json',
                                        success: function(response) {

                                            if(response.action=='1'){
                                              Swal.fire("Done", "Bus Deleted Successfully", "success");
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
    $("#add_package_open").click(function(event) {
        $("#add_package_form_view").show("slow");
    });

    $("#add_package_close").click(function(event) {
        $("#add_package_form_view").hide("slow");
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
                url: '{{ route("bus.list")}}',
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
                    searchable: true
                },
                {
                    data: 'bus_number',
                    name: 'bus_number',
                    searchable: true,
                },
                {
                    data: 'is_active',
                    name: 'is_active',
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
