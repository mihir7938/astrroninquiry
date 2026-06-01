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
                    <form method="POST" action="{{route('admin.inquiry.save')}}" class="form" id="add-inquiry-form" enctype="multipart/form-data">
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
                                <h3 class="card-title">Add Inquiry</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="company_name">Company Name*</label>
                                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name*">
                                        </div>
                                        <div class="form-group">
                                            <label for="contact_person">Contact Person*</label>
                                            <input type="text" class="form-control" id="contact_person" name="contact_person" placeholder="Contact Person*">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Mobile Number*</label>
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Mobile Number*">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <label for="business">Business*</label>
                                            <select id="business" name="business" class="form-control select2">
					                            <option value="">Select Business*</option>
					                            @foreach($businesses as $business)
					                                <option value="{{$business->id}}">{{$business->name}}</option>
					                            @endforeach
					                        </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="city">City (Eg: GJ-Surat)*</label>
                                            <input type="text" class="form-control" id="city" name="city" placeholder="City (Eg: GJ-Surat)*">
                                        </div>
                                        <div id="product-wrapper" class="border border-dark bg-light px-3 py-2 mt-2 mb-3">
                                            <div class="row product-row">
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
                                        </div>
                                        <div class="form-group">
                                            <label for="status">Status*</label>
                                            <select id="status" name="status" class="form-control select2">
					                            <option value="">Select Status*</option>
					                            @foreach($statuses as $status)
					                                <option value="{{$status->id}}">{{$status->name}}</option>
					                            @endforeach
					                        </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="reff">Reff Name & Contact (Self or Reff)</label>
                                            <input type="text" class="form-control" id="reff" name="reff" placeholder="Reff Name & Contact">
                                        </div>
                                        <div class="form-group">
                                            <label for="user">User</label>
                                            <input type="text" class="form-control" id="user" name="user" value="{{Auth::user()->name}} - {{Auth::user()->contact_person}}" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="assign">Assign*</label>
                                            <select id="assign" name="assign" class="form-control select2">
					                            <option value="">Select Assign*</option>
					                            @foreach($users as $user)
					                            	@if($user->isUser())
					                                	<option value="{{$user->id}}" @if(Auth::user()->id == $user->id) selected @endif>{{$user->name}} - {{$user->contact_person}}</option>
					                                @endif
					                            @endforeach
					                        </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="remarks">Remarks</label>
                                            <textarea class="form-control" id="remarks" name="remarks" rows="4" cols="50" placeholder="Remarks"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Image (allowed only JPG,JPEG &amp; PNG files)</label>
                                            <div class="input-group image_div">
                                                <div class="custom-file">             
                                                    <input type="file" class="custom-file-input" id="image" name="image[]" multiple="multiple">
                                                    <label class="custom-file-label" for="image">Choose file</label>
                                                </div>              
                                            </div>
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
        bsCustomFileInput.init();
        $('#add-inquiry-form').validate({
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
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else if (element.attr("name") == "image[]" ) {
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
        let rowIndex = 1;
        applyRowValidation($('.product-row:first'));
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