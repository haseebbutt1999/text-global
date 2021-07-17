{{--Header Start--}}
    <!DOCTYPE html>
<!--[if IE 9]>
{{--<html class="ie9 no-js" lang="en"> <![endif]-->--}}
{{--<!--[if (gt IE 9)|!(IE)]><!-->--}}
{{--<html class="no-js" lang="en">--}}
<!--<![endif]-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Text Global Shopify</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('/custom_assets/css/style.css')}}?{{ now() }}">
{{--    datepicker css--}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- <link rel="stylesheet" href="http://localhost:3000/css/bootstrap4/dist/css/bootstrap-custom.css?v=datetime"> -->
    <link rel="stylesheet" href="{{asset('polished_asset/polished.min.css')}}">
    <!-- <link rel="stylesheet" href="polaris-navbar.css"> -->
    <link rel="stylesheet" href="{{asset('polished_asset/iconic/css/open-iconic-bootstrap.min.css')}}">
{{--select2 css--}}
    <link rel="stylesheet" href="{{ asset('polished_asset/select2/css/select2.min.css') }}">
{{--    ckeditor cdn--}}
    <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
{{--    toaster cdn to display response--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

{{--    chart.js css --}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" integrity="sha256-aa0xaJgmK/X74WM224KMQeNQC2xYKwlAt08oZqjeF0E=" crossorigin="anonymous" />


</head>
<style>
    .navbar-brand {
        padding-top: 0;
        padding-bottom: 0; }
    .btn-primary, .dataTables_wrapper .dataTables_paginate a.current {
        border-color: #4C74B8;
    }
    body .bg-primary {
        background-color: #4C74B8 !important;
    }
    .btn-primary{
        background-color: #4C74B8 !important;
    }
    .navbar {
        padding: 0 1rem; }
</style>

{{--Header End--}}
