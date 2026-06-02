@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <div class="d-flex justify-content-between">
                        <h1 class="m-0">Home</h1>
                        <a href="{{route('users.inquiry.add')}}" class="btn btn-primary">Add New Inquiry</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $total_inquiry }}</h3>
                            <p>All Inquiries</p>
                        </div>
                        <a href="{{route('users.inquiries')}}" class="small-box-footer py-3">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                @php
                    $bgClasses = [
                        'bg-dark',
                        'bg-primary',
                        'bg-warning',
                        'bg-success',
                        'bg-danger',
                        'bg-secondary',
                        'bg-info',
                        'bg-dark',
                        'bg-primary',
                        'bg-success',
                    ];
                @endphp
                @foreach($statuses as $key => $status)
                    <div class="col-lg-3 col-6">
                        <div class="small-box {{ $bgClasses[$key % count($bgClasses)] }}">
                            <div class="inner">
                                <h3>{{ $status->inquiries_count }}</h3>
                                <p>{{ $status->name }}</p>
                            </div>
                            <a href="{{route('users.inquiries')}}?status={{$status->id}}" class="small-box-footer py-3">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endforeach
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $total_assign_inquiry }}</h3>
                            <p>Assign In/Out</p>
                        </div>
                        <a href="{{route('users.assign.inquiries')}}" class="small-box-footer py-3">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
@endsection