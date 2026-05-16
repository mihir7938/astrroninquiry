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
                    <form method="POST" action="{{route('users.inquiries.fetch')}}" class="form" id="fetch-inquiry" enctype="multipart/form-data">
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
                                            <label for="status">Status</label>
                                            <select id="status" name="status" class="form-control">
                                                <option value="">Select Status</option>
                                                @foreach($statuses as $status)
                                                    <option value="{{$status->id}}" @if($status_id == $status->id) selected @endif>{{$status->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="followup_start_date">Followup Start Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" id="followup_start_date" name="followup_start_date" class="form-control followup_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="followup_end_date">Followup End Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" id="followup_end_date" name="followup_end_date" class="form-control followup_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary" id="btnsubmit" name="btnsubmit">Submit</button>
                            </div>
                        </div>
                    </form>
                    <div id="inquiry_result">
                        @include('users.list', ['inquiries' => $inquiries, 'flag' => $flag])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
<script>
    $(document).ready(function() {
        $('.followup_date').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
        $('#dataTableInquiry').DataTable({
            "buttons": ["csv", "excel"],
            "destroy": true, 
            "paging": true,
            "lengthChange": false,
            "ordering": true,
            "info": true,
            "responsive": true,
        }).buttons().container().appendTo('#dataTableInquiry_wrapper .col-md-6:eq(0)');

        $(document).on('click', '#btnsubmit', function(){
            $('.loader').show();
            $.ajax({
                url: "{{ route('users.inquiries.fetch') }}",
                method: "POST",
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                  'status_id' : $("#status").val(),
                  'followup_start_date' : $("#followup_start_date").val(),
                  'followup_end_date' : $("#followup_end_date").val(),
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