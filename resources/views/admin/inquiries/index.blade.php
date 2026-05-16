@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <div class="d-flex justify-content-between">
                        <h1 class="m-0">Inquiries</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{route('admin.inquiries.fetch')}}" class="form" id="fetch-inquiry" enctype="multipart/form-data">
                        @csrf
                        @include('shared.alert')
                        @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Select Status</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="status">Status*</label>
                                            <select id="status" name="status" class="form-control">
                                                <option value="">Select Status</option>
                                                @foreach($statuses as $status)
                                                    <option value="{{$status->id}}" @if($status_id == $status->id) selected @endif>{{$status->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div id="inquiry_result">
                        @include('admin.inquiries.list', ['inquiries' => $inquiries])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
<script>
    $(document).ready(function() {
        $('#dataTableInquiry').DataTable({
            "buttons": ["csv", "excel"],
            "destroy": true, 
            "paging": true,
            "lengthChange": false,
            "ordering": true,
            "info": true,
            "responsive": true,
        }).buttons().container().appendTo('#dataTableInquiry_wrapper .col-md-6:eq(0)');

        $(document).on('change', '#status', function(){
            $('.loader').show();
            $.ajax({
                url: "{{ route('admin.inquiries.fetch') }}",
                method: "POST",
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                  'status_id' : $(this).val(),
                },
                success: function (data) {
                    $('.loader').hide();
                    $("#inquiry_result").html('');
                    $('#inquiry_result').append(data);
                    $('#dataTableInquiry').DataTable({
                        "buttons": ["csv", "excel"],
                        "destroy": true, 
                        "paging": true,
                        "lengthChange": false,
                        "ordering": true,
                        "info": true,
                        "responsive": true,
                    }).buttons().container().appendTo('#dataTableInquiry_wrapper .col-md-6:eq(0)');
                },
            });
        });
    });
</script>
@endsection