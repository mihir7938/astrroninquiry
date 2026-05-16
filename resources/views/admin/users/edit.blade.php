@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit User</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{route('admin.users.update.save')}}" class="form" id="edit-users-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$user->id}}" />
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
                                <h3 class="card-title">Mobile Number : {{$user->phone}}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Firm Name*</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{$user->name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contact_person">Contact Person*</label>
                                            <input type="text" class="form-control" id="contact_person" name="contact_person" placeholder="Contact Person" value="{{$user->contact_person}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" id="city" name="city" placeholder="City" value="{{$user->city}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="state">State</label>
                                            <input type="text" class="form-control" id="state" name="state" placeholder="State" value="{{$user->state}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="country">Country</label>
                                            <input type="text" class="form-control" id="country" name="country" placeholder="Country" value="{{$user->country}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{$user->email}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="work_with">Work with</label>
                                            <select id="work_with" name="work_with" class="form-control">
                                                <option value="">Select</option>
                                                <option value="Lead generation" @if($user->work_with == 'Lead generation') selected @endif>Lead Generation</option>
                                                <option value="With Service" @if($user->work_with == 'With Service') selected @endif>With Service</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="current_work">Current Work</label>
                                            <input type="text" class="form-control" id="current_work" name="current_work" placeholder="Current Work" value="{{$user->current_work}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="up_line">Up Line*</label>
                                            <select id="up_line" name="up_line" class="form-control select2">
                                                <option value="">Select Up Line*</option>
                                                @foreach($users as $userdata)
                                                    <option value="{{$userdata->id}}" @if($userdata->id == $user->up_line) selected @endif>{{$userdata->name}} - {{$userdata->contact_person}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pan_card_number">Pan Card Number</label>
                                            <input type="text" class="form-control" id="pan_card_number" name="pan_card_number" placeholder="Pan Card Number" value="{{$user->pan_card_number}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="remarks">Remarks</label>
                                            <textarea class="form-control" id="remarks" name="remarks" rows="4" cols="50" placeholder="Remarks">{{$user->remarks}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pan_card_attachment">Pan Card Attachment (allowed only JPG,JPEG &amp; PNG files)</label>
                                            <div class="input-group image_div">
                                                <div class="custom-file">             
                                                    <input type="file" class="custom-file-input" id="pan_card_attachment" name="pan_card_attachment">
                                                    <label class="custom-file-label" for="pan_card_attachment">Choose file</label>
                                                </div>              
                                            </div>
                                            @if($user->pan_card_attachment)
                                                <a href="{{asset('assets/'.$user->pan_card_attachment)}}" data-toggle="lightbox" data-title="Document">
                                                    <img src="{{asset('assets/'.$user->pan_card_attachment)}}" class="img-fluid mt-4 d-block" width="100px"/>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        @if($user->isUser())
                                            <div class="form-group">
                                                <label for="active">Active</label>
                                                <div class="group">
                                                    <input type="radio" id="yes" name="active" value="1" @if($user->status == 1) checked @endif>
                                                    <label for="yes">Yes</label>
                                                    <span class="mx-2"></span>
                                                    <input type="radio" id="no" name="active" value="0" @if($user->status == 0) checked @endif>
                                                    <label for="no">No</label>
                                                </div>
                                            </div>
                                        @endif
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
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });
        bsCustomFileInput.init();
        $('#edit-users-form').validate({
            rules:{
                name:{
                    required: true
                },
                contact_person:{
                    required: true
                },
                email: {
                    alphanumeric: true
                },
                up_line:{
                    required: true
                },
                pan_card_attachment: {
                    extension: "png|jpg|jpeg",
                    maxsize: 2000000,
                }
            },
            messages:{
                name:{
                    required: "Please enter name."
                },
                contact_person:{
                    required: "Please enter contact person name."
                },
                email:{
                    email: "Please provide a valid email."
                },
                up_line:{
                    required: "Plese select up line.",
                },
                pan_card_attachment: {
                    extension: "Please select valid image.",
                    maxsize: "File size must be less than 2MB."
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "pan_card_attachment" ) {
                    $(".image_div").after(error);
                } else if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });
</script>
@endsection