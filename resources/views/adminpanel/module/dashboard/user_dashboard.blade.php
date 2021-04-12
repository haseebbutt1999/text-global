@extends('adminpanel.user-layout.default')
@section('content')

    <div class="col-lg-12 col-md-12 p-4">
        <!-- start info box -->
        <div class="row ">

            <div class="col-lg-3 mb-2">
                <div class="card shadow-sm ">
                    <div class="card-header bg-primary text-light">
                        <h6>Total SMS Send</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
{{--                            @dd(json_encode($graph_send_sms_dates))--}}
                            <h3>{{$total_send_sms}}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mb-2">
                <div class="card shadow-sm ">
                    <div class="card-header bg-primary text-light">
                        <h6>Total Triggered SMS</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
{{--                            @dd(json_encode($graph_send_sms_dates))--}}
                            <h3>{{$total_triggered_sms}}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mb-2">
                <div class="card shadow-sm ">
                    <div class="card-header bg-primary text-light">
                        <h6>Total Subscribers</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
{{--                            @dd(json_encode($graph_send_sms_dates))--}}
                            <h3>{{$total_customers}}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mb-2">
                <div class="card shadow-sm ">
                    <div class="card-header bg-primary text-light">
                        <h6>Total SMS Send</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
{{--                            @dd(json_encode($graph_send_sms_dates))--}}
                            <h3>{{$total_send_sms}}</h3>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        <div class="row mt-2">

            <div class="col-lg-6 mb-2">
                <div class="card shadow-sm ">
                    <div class="card-header bg-primary text-light">
                        <h6>Total SMS Send</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
{{--                                                        @dd(json_encode($graph_send_sms_dates))--}}
                            <canvas id="chartjs-bar" class="canvas-graph-one" data-total_sms_sended="{{$total_send_sms}}" data-labels="{{json_encode($graph_send_sms_dates)}}" data-values="{{json_encode($pushed_nmbr_sms)}}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-2">
                <div class="card shadow-sm ">
                    <div class="card-header bg-primary text-light">
                        <h6>Subscribers</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            {{--                            @dd(json_encode($graph_send_sms_dates))--}}
                            <canvas id="customer-chartjs-bar" class="customer-canvas-graph" data-total_customers="{{$total_customers}}" data-labels="{{json_encode($graph_customer_created_dates)}}" data-values="{{json_encode($pushed_customer)}}"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-2">

            <div class="col-lg-6 mb-2">
                <div class="card shadow-sm ">
                    <div class="card-header bg-primary text-light">
                        <h6>Total Triggered SMS</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
{{--                                                        @dd(json_encode($total_triggered_sms))--}}
                            <canvas id="trigger-sms-chartjs-bar" class="trigger-sms-canvas" data-total_trigger_sms="{{$total_triggered_sms}}" data-labels="{{json_encode($graph_trigger_sms_dates)}}" data-values="{{json_encode($pushed_trigger_sms)}}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-2">
                <div class="card shadow-sm ">
                    <div class="card-header bg-primary text-light">
                        <h6>Abandoned Cart Conversations</h6>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
{{--                                                        @dd(json_encode($graph_send_sms_dates))--}}
                            <canvas id="customer1-chartjs-bar" class="customer-canvas-graph" data-total_customers="{{$total_customers}}" data-labels="{{json_encode($graph_customer_created_dates)}}" data-values="{{json_encode($pushed_customer)}}"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        </div>
    </div>
@endsection
@section('js_after')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
    <script>
        // console.log($('.canvas-graph-one').data('labels'))
        // console.log($('.canvas-graph-one').data('values'))
        var ctx = document.getElementById('chartjs-bar');
        // console.log(ctx);
        var data = {
            labels: $('.canvas-graph-one').data('labels'),
            datasets: [{
                label: '# of Votes',
                data: $('.canvas-graph-one').data('values'),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                ],
                borderWidth: 1
            }],
        }

        var options1 =  {
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Date'
                    }
                }],
                yAxes: [{
                    display: true,
                    ticks: {
                        beginAtZero: true,
                        stepSize: 2,
                        min: 0,
                        max: $('.canvas-graph-one').data('total_sms_sended')
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'SMS Send'
                    }
                }]
            }
        }

        var myBarChart1 = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options1,
        });

        var ctx2 = document.getElementById('customer-chartjs-bar');
        var data2 = {
            labels: $('.customer-canvas-graph').data('labels'),
            datasets: [{
                label: '# of Votes',
                data: $('.customer-canvas-graph').data('values'),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }],
        }
        var options2 =  {
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Date'
                    }
                }],
                yAxes: [{
                    display: true,
                    ticks: {
                        beginAtZero: true,
                        stepSize: 2,
                        min: 0,
                        max: $('.customer-canvas-graph').data('total_customers')
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Subscribers'
                    }
                }]
            }
        }

        var myBarChart2 = new Chart(ctx2, {
            type: 'bar',
            data: data2,
            options: options2,
        });
        console.log($('.trigger-sms-canvas').data('labels') )
        console.log($('.trigger-sms-canvas').data('values') )
        var ctx3 = document.getElementById('trigger-sms-chartjs-bar');
        var data3 = {
            labels: $('.trigger-sms-canvas').data('labels'),
            datasets: [{
                label: '# of Votes',
                data: $('.trigger-sms-canvas').data('values'),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }],
        }
        console.log($('.trigger-sms-canvas').data('total_trigger_sms'))
        var options3 =  {
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Date'
                    }
                }],
                yAxes: [{
                    display: true,
                    ticks: {
                        beginAtZero: true,
                        stepSize: 2,
                        min: 0,
                        max: $('.trigger-sms-chartjs-bar').data('total_trigger_sms')
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Triggered SMS'
                    }
                }]
            }
        }

        var myBarChart3 = new Chart(ctx3, {
            type: 'bar',
            data: data3,
            options: options3,
        });

    </script>
@endsection
{{--<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>--}}


