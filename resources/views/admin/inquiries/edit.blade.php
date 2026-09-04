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
                    <form method="POST" action="{{route('admin.inquiries.update.save')}}" class="form" id="edit-inquiry-form" enctype="multipart/form-data">
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
                                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name*" value="{{$inquiry->company_name}}">
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
                                        <input type="hidden" id="total_products" value="{{$inquiry->products->count()}}">
                                        <div id="product-wrapper" class="border border-dark bg-light px-3 py-2 mt-2 mb-3">
                                            @if($inquiry->products->count() > 0)
                                                @foreach($inquiry->products as $key => $row)
                                                    <div class="row product-row">
                                                        <input type="hidden" name="row_id[]" value="{{$row->id}}">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                @if($key == 0)<label class="text-primary field-label">Product</label>@endif
                                                                <select name="product[{{$key}}]" class="form-control product border border-primary">
                                                                    <option value="">Select Product</option>
                                                                    @foreach($products as $product)
                                                                        <option value="{{$product->id}}" @if($row->product_id == $product->id) selected @endif>{{$product->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                @if($key == 0)<label class="text-primary field-label">Price</label>@endif
                                                                <input type="text" class="form-control price border border-primary" name="price[{{$key}}]" placeholder="Price" value="{{$row->price}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                @if($key == 0)<label class="text-primary field-label">Quantity</label>@endif
                                                                <input type="text" class="form-control quantity border border-primary" name="quantity[{{$key}}]" placeholder="Quantity" value="{{$row->quantity}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <div class="form-group">
                                                                @if($key == 0)<label class="d-block invisible field-label">Buttons</label>@endif
                                                                <div class="d-flex">
                                                                    <button type="button" class="btn btn-success add-row mr-1">+</button>
                                                                    <button type="button" class="btn btn-danger remove-row">-</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="row product-row">
                                                    <input type="hidden" name="row_id[]" value="">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label class="text-primary field-label">Product</label>
                                                            <select name="product[]" class="form-control product border border-primary">
                                                                <option value="">Select Product</option>
                                                                @foreach($products as $product)
                                                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="text-primary field-label">Price</label>
                                                            <input type="text" class="form-control price border border-primary" name="price[]" placeholder="Price">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="text-primary field-label">Quantity</label>
                                                            <input type="text" class="form-control quantity border border-primary" name="quantity[]" placeholder="Quantity">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <label class="d-block invisible field-label">Buttons</label>
                                                            <div class="d-flex">
                                                                <button type="button" class="btn btn-success add-row mr-1">+</button>
                                                                <button type="button" class="btn btn-danger remove-row">-</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
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
                                        <div class="form-group">
                                            <label for="assign">Assign*</label>
                                            <select id="assign" name="assign" class="form-control select2">
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
                                                    <div class="image-box" id="img_{{$row->id}}">
                                                        <a href="{{asset('assets/'.$row->image)}}" data-toggle="lightbox" data-gallery="gallery1">
                                                            <img src="{{asset('assets/'.$row->image)}}" class="mr-2 mt-4 my-2" width="150px" />
                                                        </a>
                                                        <br>
                                                        <button type="button" class="delete-image" data-id="{{$row->id}}">
                                                            <i class="far fa-window-close"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="requirements">Requirements (allowed only PDF file)</label>
                                                    <div class="input-group requirements_div">
                                                        <div class="custom-file">             
                                                            <input type="file" class="custom-file-input" id="requirements" name="requirements[]" multiple>
                                                            <label class="custom-file-label" for="requirements">Choose file</label>
                                                        </div>              
                                                    </div>
                                                    @if($inquiry->requirements)
                                                        <div id="req_pdf">
                                                            @foreach(explode(',', $inquiry->requirements) as $requirement)
                                                                @php
                                                                    $requirement = trim($requirement);
                                                                    $filename = basename($requirement);
                                                                @endphp
                                                                @if($requirement)
                                                                    <div class="my-2 d-flex align-items-center">
                                                                        <a href="{{ asset('assets' . $requirement) }}" class="btn btn-primary mr-1" target="_blank">
                                                                            {{ $filename }}
                                                                        </a>
                                                                        <button type="button"
                                                                                class="btn btn-danger btn-circle delete-requirements"
                                                                                data-id="{{ $inquiry->id }}"
                                                                                data-file="{{ $requirement }}">
                                                                            <i class="far fa-window-close"></i>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="quotation">Quotation (allowed only PDF file)</label>
                                                    <div class="input-group quotation_div">
                                                        <div class="custom-file">             
                                                            <input type="file" class="custom-file-input" id="quotation" name="quotations[]" multiple>
                                                            <label class="custom-file-label" for="quotation">Choose file</label>
                                                        </div>              
                                                    </div>
                                                    @if($inquiry->quotation)
                                                        <div id="quo_pdf">
                                                            @foreach(explode(',', $inquiry->quotation) as $quotation)
                                                                @php
                                                                    $quotation = trim($quotation);
                                                                    $filename_quotation = basename($quotation);
                                                                @endphp
                                                                @if($quotation)
                                                                    <div class="my-2 d-flex align-items-center">
                                                                        <a href="{{ asset('assets' . $quotation) }}" class="btn btn-primary mr-1" target="_blank">
                                                                            {{ $filename_quotation }}
                                                                        </a>
                                                                        <button type="button"
                                                                                class="btn btn-danger btn-circle delete-quotation"
                                                                                data-id="{{ $inquiry->id }}"
                                                                                data-file="{{ $quotation }}">
                                                                            <i class="far fa-window-close"></i>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
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
    function applyRowValidation(row) {
        row.find('.product').rules('add', {
            required: true,
            messages: {
                required: "Please select product."
            }
        });
        row.find('.price').rules('add', {
            required: true,
            digits: true,
            messages: {
                required: "Please enter price."
            }
        });
        row.find('.quantity').rules('add', {
            required: true,
            digits: true,
            messages: {
                required: "Please enter quantity."
            }
        });
    }
    $(function () {
        $('.select2').select2();
        $('.followup_date').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });
        $(document).on('click', '.delete-image', function () {
            let id = $(this).data('id');
            if(confirm('Are you sure you want to delete this image?')) {
                $.ajax({
                    url: "{{ route('admin.inquiries.image.delete') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        $('#img_' + id).remove();
                    },
                    error: function () {
                        alert('Something went wrong.');
                    }
                });
            }
        });
        $(document).on('click', '.delete-requirements', function () {
            var button = $(this);
            var id = button.data('id');
            var file = button.data('file');
            if(confirm('Are you sure you want to delete this pdf?')) {
                $.ajax({
                    url: "{{ route('admin.inquiries.requirements.delete') }}",
                    type: "POST",
                    data: {
                        id: id,
                        file: file,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            button.closest('.my-2').remove();
                        }
                    },
                    error: function () {
                        alert('Something went wrong.');
                    }
                });
            }
        });
        $(document).on('click', '.delete-quotation', function () {
            var button = $(this);
            var id = button.data('id');
            var file = button.data('file');
            if(confirm('Are you sure you want to delete this pdf?')) {
                $.ajax({
                    url: "{{ route('admin.inquiries.quotation.delete') }}",
                    type: "POST",
                    data: {
                        id: id,
                        file: file,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            button.closest('.my-2').remove();
                        }
                    },
                    error: function () {
                        alert('Something went wrong.');
                    }
                });
            }
        });
        bsCustomFileInput.init();
        $('#edit-inquiry-form').validate({
            ignore: [],
            rules:{
                company_name: {
                    required: true
                },
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
				'requirements[]': {
                    extension: "pdf",
                    maxsize: 1000000,
                },
				'quotations[]': {
                    extension: "pdf",
                    maxsize: 1000000,
                }
            },
            messages:{
                company_name: {
                    required: "Please enter company name."
                },
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
				'requirements[]': {
                    extension: "Please select valid pdf.",
                    maxsize: "File size must be less than 1MB."
                },
				'quotations[]': {
                    extension: "Please select valid pdf.",
                    maxsize: "File size must be less than 1MB."
                }
            },
            errorPlacement: function(error, element) {
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else if (element.attr("name") == "image[]" ) {
                    $(".image_div").after(error);
                } else if (element.attr("name") == "requirements[]" ) {
                    $(".requirements_div").after(error);
                } else if (element.attr("name") == "quotations[]" ) {
                    $(".quotation_div").after(error);
                } else {
                    error.insertAfter(element);
                }
            }
        });
        let rowIndex = $('#total_products').val();
        $('.product-row').each(function () {
            applyRowValidation($(this));
        });
        $(document).on('click', '.add-row', function () {
            let clone = $('.product-row:first').clone();
            clone.find('input').val('');
            clone.find('select').prop('selectedIndex', 0);
            clone.find('.field-label').remove();
            clone.find('label.error').remove();
            clone.find('.product').attr('name', 'product['+rowIndex+']');
            clone.find('.price').attr('name', 'price['+rowIndex+']');
            clone.find('.quantity').attr('name', 'quantity['+rowIndex+']');
            $('#product-wrapper').append(clone);
            applyRowValidation(clone);
            rowIndex++;
        });
        $(document).on('click', '.remove-row', function () {
            if ($('.product-row').length > 1) {
                $(this).closest('.product-row').remove();
            } else {
                alert('At least one row required.');
            }
        });
    });
</script>
@endsection