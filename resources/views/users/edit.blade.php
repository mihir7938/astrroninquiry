@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Inquiry Form</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{route('users.inquiries.update.save')}}" class="form" id="edit-inquiry-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$inquiry->id}}" />
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
                                <h3 class="card-title">Edit Inquiry</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="company_name">Company Name*</label>
                                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name*" value="{{$inquiry->company_name}}" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="contact_person">Contact Person*</label>
                                            <input type="text" class="form-control" id="contact_person" name="contact_person" placeholder="Contact Person*" value="{{$inquiry->contact_person}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Mobile Number*</label>
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Mobile Number*" value="{{$inquiry->phone}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{$inquiry->email}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="business">Business*</label>
                                            <select id="business" name="business" class="form-control select2">
					                            <option value="">Select Business*</option>
					                            @foreach($businesses as $business)
					                                <option value="{{$business->id}}" @if($inquiry->business_id == $business->id) selected @endif>{{$business->name}}</option>
					                            @endforeach
					                        </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="city">City (Eg: GJ-Surat)*</label>
                                            <input type="text" class="form-control" id="city" name="city" placeholder="City (Eg: GJ-Surat)*" value="{{$inquiry->city}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="product">Product*</label>
                                            <select id="product" name="product" class="form-control select2">
					                            <option value="">Select Product*</option>
					                            @foreach($products as $product)
					                                <option value="{{$product->id}}" @if($inquiry->product_id == $product->id) selected @endif>{{$product->name}}</option>
					                            @endforeach
					                        </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="status">Status*</label>
                                            <select id="status" name="status" class="form-control select2">
					                            <option value="">Select Status*</option>
					                            @foreach($statuses as $status)
					                                <option value="{{$status->id}}" @if($inquiry->status_id == $status->id) selected @endif>{{$status->name}}</option>
					                            @endforeach
					                        </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="reff">Reff Name & Contact (Self or Reff)</label>
                                            <input type="text" class="form-control" id="reff" name="reff" placeholder="Reff Name & Contact" value="{{$inquiry->reff}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="user">User</label>
                                            <input type="text" class="form-control" id="user" name="user" value="{{$inquiry->user->name}} - {{$inquiry->user->contact_person}}" disabled>
                                        </div>
                                        @php
                                            if($inquiry->user_id == Auth::user()->id) {
                                                $disabled = '';
                                            } else {
                                                $disabled = 'disabled';
                                            }
                                        @endphp
                                        <div class="form-group">
                                            <label for="assign">Assign*</label>
                                            <select id="assign" name="assign" class="form-control select2" {{ $disabled }}>
					                            <option value="">Select Assign*</option>
					                            @foreach($users as $user)
					                            	@if($user->isUser())
					                                	<option value="{{$user->id}}" @if($inquiry->assign_id == $user->id) selected @endif>{{$user->name}} - {{$user->contact_person}}</option>
					                                @endif
					                            @endforeach
					                        </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="remarks">Remarks</label>
                                            <textarea class="form-control" id="remarks" name="remarks" rows="4" cols="50" placeholder="Remarks">{{$inquiry->remarks}}</textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="btn btn-outline-primary">1st Followup</h5>
                                                <div class="form-group">
                                                    <label for="followup_date_1">Date</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input type="text" id="followup_date_1" name="followup_date_1" class="form-control followup_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" value="{{$inquiry->followup_date_1 ? Carbon\Carbon::parse($inquiry->followup_date_1)->format('d/m/Y') : ''}}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="followup_remarks_1">Remarks</label>
                                                    <textarea class="form-control" id="followup_remarks_1" name="followup_remarks_1" rows="4" cols="50" placeholder="Followup Remarks">{{$inquiry->followup_remarks_1}}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="btn btn-outline-primary">2nd Followup</h5>
                                                <div class="form-group">
                                                    <label for="followup_date_2">Date</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input type="text" id="followup_date_2" name="followup_date_2" class="form-control followup_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" value="{{$inquiry->followup_date_2 ? Carbon\Carbon::parse($inquiry->followup_date_2)->format('d/m/Y') : ''}}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="followup_remarks_2">Remarks</label>
                                                    <textarea class="form-control" id="followup_remarks_2" name="followup_remarks_2" rows="4" cols="50" placeholder="Followup Remarks">{{$inquiry->followup_remarks_2}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="btn btn-outline-primary">3rd Followup</h5>
                                                <div class="form-group">
                                                    <label for="followup_date_3">Date</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input type="text" id="followup_date_3" name="followup_date_3" class="form-control followup_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" value="{{$inquiry->followup_date_3 ? Carbon\Carbon::parse($inquiry->followup_date_3)->format('d/m/Y') : ''}}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="followup_remarks_3">Remarks</label>
                                                    <textarea class="form-control" id="followup_remarks_3" name="followup_remarks_3" rows="4" cols="50" placeholder="Followup Remarks">{{$inquiry->followup_remarks_3}}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="btn btn-outline-primary">4th Followup</h5>
                                                <div class="form-group">
                                                    <label for="followup_date_4">Date</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input type="text" id="followup_date_4" name="followup_date_4" class="form-control followup_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" value="{{$inquiry->followup_date_4 ? Carbon\Carbon::parse($inquiry->followup_date_4)->format('d/m/Y') : ''}}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="followup_remarks_4">Remarks</label>
                                                    <textarea class="form-control" id="followup_remarks_4" name="followup_remarks_4" rows="4" cols="50" placeholder="Followup Remarks">{{$inquiry->followup_remarks_4}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="btn btn-outline-primary">5th Followup</h5>
                                                <div class="form-group">
                                                    <label for="followup_date_5">Date</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                        </div>
                                                        <input type="text" id="followup_date_5" name="followup_date_5" class="form-control followup_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" value="{{$inquiry->followup_date_5 ? Carbon\Carbon::parse($inquiry->followup_date_5)->format('d/m/Y') : ''}}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="followup_remarks_5">Remarks</label>
                                                    <textarea class="form-control" id="followup_remarks_5" name="followup_remarks_5" rows="4" cols="50" placeholder="Followup Remarks">{{$inquiry->followup_remarks_5}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Image (allowed only JPG,JPEG &amp; PNG files)</label>
                                            <div class="input-group image_div">
                                                <div class="custom-file">             
                                                    <input type="file" class="custom-file-input" id="image" name="image[]" multiple="multiple">
                                                    <label class="custom-file-label" for="image">Choose file</label>
                                                </div>              
                                            </div>
                                            @php
                                                $inquiry_image = $inquiry->photos()->get();
                                            @endphp
                                            @if(($inquiry_image->count() > 0))
                                                @foreach($inquiry_image as $row)
                                                    <a href="{{asset('assets/'.$row->image)}}" data-toggle="lightbox" data-gallery="gallery1">
                                                        <img src="{{asset('assets/'.$row->image)}}" class="mr-2 mt-4 my-2" width="150px" />
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="requirements">Requirements (allowed only PDF file)</label>
                                                    <div class="input-group requirements_div">
                                                        <div class="custom-file">             
                                                            <input type="file" class="custom-file-input" id="requirements" name="requirements">
                                                            <label class="custom-file-label" for="requirements">Choose file</label>
                                                        </div>              
                                                    </div>
                                                    @if($inquiry->requirements)
                                                        <a href="{{asset('assets/'.$inquiry->requirements)}}" class="btn btn-primary btn-circle my-2 float-right" download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="quotation">Quotation (allowed only PDF file)</label>
                                                    <div class="input-group quotation_div">
                                                        <div class="custom-file">             
                                                            <input type="file" class="custom-file-input" id="quotation" name="quotation">
                                                            <label class="custom-file-label" for="quotation">Choose file</label>
                                                        </div>              
                                                    </div>
                                                    @if($inquiry->quotation)
                                                        <a href="{{asset('assets/'.$inquiry->quotation)}}" class="btn btn-primary btn-circle my-2 float-right" download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="btnsubmit" name="btnsubmit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
<script>
    $(function () {
        $('.select2').select2();
        $('.followup_date').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });
        bsCustomFileInput.init();
        $('#edit-inquiry-form').validate({
            rules:{
                contact_person: {
                    required: true
                },
                phone: {
                    required: true,
                    digits: true,
                    minlength: 10
                },
                email: {
                    alphanumeric: true
                },
                business: {
                    required: true
                },
                city: {
                    required: true
                },
                product: {
                    required: true
                },
                status: {
                    required: true
                },
                assign: {
                    required: true
                },
                'image[]': {
                    extension: "png|jpg|jpeg",
                    maxsize: 5000000,
                },
                requirements: {
                    extension: "pdf",
                    maxsize: 1000000,
                },
                quotation: {
                    extension: "pdf",
                    maxsize: 1000000,
                }
            },
            messages:{
                contact_person: {
                    required: "Please enter contact person."
                },
                phone: {
                    required: "Plese enter mobile number.",
                },
                email:{
                    email: "Please provide a valid email."
                },
                business: {
                    required: "Please select business."
                },
                city: {
                    required: "Please enter city."
                },
                product: {
                    required: "Please select product."
                },
                status: {
                    required: "Please select status."
                },
                assign: {
                    required: "Please select assign."
                },
                'image[]': {
                    extension: "Please select valid image.",
                    maxsize: "File size must be less than 5MB."
                },
                requirements: {
                    extension: "Please select valid pdf.",
                    maxsize: "File size must be less than 1MB."
                },
                quotation: {
                    extension: "Please select valid pdf.",
                    maxsize: "File size must be less than 1MB."
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "image[]" ) {
                    $(".image_div").after(error);
                } else if (element.attr("name") == "requirements" ) {
                    $(".requirements_div").after(error);
                } else if (element.attr("name") == "quotation" ) {
                    $(".quotation_div").after(error);
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });
</script>
@endsection