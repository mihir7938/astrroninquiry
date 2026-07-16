@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <div class="d-flex justify-content-between">
                        <h1 class="m-0">Deleted Inquiries</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Inquiries</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTableInquiry" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Inquiry No</th>
                                            <th>Date</th>
                                            <th>Company</th>
                                            <th>Contact Name</th>
                                            <th>Mobile Number</th>
                                            <th>User</th>
                                            <th>Assign</th>
                                            <th>Status</th>
                                            <th>Business</th>
                                            <th>Products</th>
                                            <th>City</th>
                                            <th>Reff</th>
                                            <th>Remarks</th>
                                            <th>1st Followup Date</th>
                                            <th>1st Followup Remarks</th>
                                            <th>2nd Followup Date</th>
                                            <th>2nd Followup Remarks</th>
                                            <th>3rd Followup Date</th>
                                            <th>3rd Followup Remarks</th>
                                            <th>4th Followup Date</th>
                                            <th>4th Followup Remarks</th>
                                            <th>5th Followup Date</th>
                                            <th>5th Followup Remarks</th>
                                            <th>Image</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th>Inquiry No</th>
                                            <th>Date</th>
                                            <th>Company</th>
                                            <th>Contact Name</th>
                                            <th>Mobile Number</th>
                                            <th>User</th>
                                            <th>Assign</th>
                                            <th>Status</th>
                                            <th>Business</th>
                                            <th>Products</th>
                                            <th>City</th>
                                            <th>Reff</th>
                                            <th>Remarks</th>
                                            <th>1st Followup Date</th>
                                            <th>1st Followup Remarks</th>
                                            <th>2nd Followup Date</th>
                                            <th>2nd Followup Remarks</th>
                                            <th>3rd Followup Date</th>
                                            <th>3rd Followup Remarks</th>
                                            <th>4th Followup Date</th>
                                            <th>4th Followup Remarks</th>
                                            <th>5th Followup Date</th>
                                            <th>5th Followup Remarks</th>
                                            <th>Image</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach($inquiries as $inquiry)
                                            <tr>
                                                <td style="width: 10px;text-align: center;"></td>
                                                <td>{{$inquiry->id}}</td>
                                                <td>{{Carbon\Carbon::parse($inquiry->inquiry_date)->format('d-m-Y')}}</td>
                                                <td>{{$inquiry->company_name}}</td>
                                                <td>{{$inquiry->contact_person}}</td>
                                                <td>{{$inquiry->phone}}</td>
                                                <td>{{optional($inquiry->user)->contact_person}}</td>
                                                <td>{{optional($inquiry->assign)->contact_person}}</td>
                                                <td>{{$inquiry->status->name}}</td>
                                                <td>{{$inquiry->business->name}}</td>
                                                <td>
                                                    {{
                                                        $inquiry->products->map(function($item){
                                                            if (!$item->product) {
                                                                return null;
                                                            }
                                                            return $item->product->name . 
                                                                   '(Price:' . $item->price . 
                                                                   ', Qty:' . $item->quantity . ')';
                                                        })->filter()->implode(', ')
                                                    }}
                                                </td>
                                                <td>{{$inquiry->city}}</td>
                                                <td>{{$inquiry->reff}}</td>
                                                <td>{{$inquiry->remarks}}</td>
                                                <td>{{$inquiry->followup_date_1}}</td>
                                                <td>{{$inquiry->followup_remarks_1}}</td>
                                                <td>{{$inquiry->followup_date_2}}</td>
                                                <td>{{$inquiry->followup_remarks_2}}</td>
                                                <td>{{$inquiry->followup_date_3}}</td>
                                                <td>{{$inquiry->followup_remarks_3}}</td>
                                                <td>{{$inquiry->followup_date_4}}</td>
                                                <td>{{$inquiry->followup_remarks_4}}</td>
                                                <td>{{$inquiry->followup_date_5}}</td>
                                                <td>{{$inquiry->followup_remarks_5}}</td>
                                                <td>
                                                    @php
                                                        $inquiry_image = $inquiry->photos()->get();
                                                    @endphp
                                                    @if(($inquiry_image->count() > 0))
                                                        @foreach($inquiry_image as $row)
                                                            <img src="{{asset('assets/'.$row->image)}}" class="mr-2 my-2" width="100px" />
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
    });
</script>
@endsection