@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 col-md-8" style="margin-left:8%">
            <div class="panel panel-danger">
                <div class="panel-heading">提醒</div>

                <div class="panel-body">
                您沒有此功能的權限!
                &nbsp;&nbsp;
                <a href="{{ url('/') }}">回首頁</a>
                </div>
            </div>
        </div>
    </div>
@endsection

