</div>
</div>
{{--Footer Start--}}

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
        crossorigin="anonymous"></script>

<script src="{{ asset('polished_asset/select2/js/select2.full.js') }}"></script>
@yield('js_after')

{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>--}}

{{--<script>--}}
{{--    $(function() {--}}

{{--        // $('input[name="datefilter"]').daterangepicker({--}}
{{--        //     autoUpdateInput: false,--}}
{{--        //     locale: {--}}
{{--        //         cancelLabel: 'Clear'--}}
{{--        //     }--}}
{{--        // });--}}
{{--        //--}}
{{--        // $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {--}}
{{--        //     $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));--}}
{{--        // });--}}
{{--        //--}}
{{--        // $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {--}}
{{--        //     $(this).val('');--}}
{{--        // });--}}

{{--        if($('body').find('#canvas-graph-one').length > 0){--}}

{{--            var config = {--}}
{{--                type: 'bar',--}}
{{--                data: {--}}
{{--                    labels: JSON.parse($('#canvas-graph-one').attr('data-labels')),--}}
{{--                    datasets: [{--}}
{{--                        label: 'Order Count',--}}
{{--                        backgroundColor: '#00e2ff',--}}
{{--                        borderColor: '#00e2ff',--}}
{{--                        data: JSON.parse($('#canvas-graph-one').attr('data-values')),--}}
{{--                        fill: false,--}}
{{--                    }]--}}
{{--                },--}}
{{--                options: {--}}
{{--                    responsive: true,--}}
{{--                    title: {--}}
{{--                        display: true,--}}
{{--                        text: 'Summary Orders Count'--}}
{{--                    },--}}
{{--                    tooltips: {--}}
{{--                        mode: 'index',--}}
{{--                        intersect: false,--}}
{{--                    },--}}
{{--                    hover: {--}}
{{--                        mode: 'nearest',--}}
{{--                        intersect: true--}}
{{--                    },--}}
{{--                    scales: {--}}
{{--                        xAxes: [{--}}
{{--                            display: true,--}}
{{--                            scaleLabel: {--}}
{{--                                display: true,--}}
{{--                                labelString: 'Date'--}}
{{--                            }--}}
{{--                        }],--}}
{{--                        yAxes: [{--}}
{{--                            display: true,--}}
{{--                            ticks: {--}}
{{--                                beginAtZero: true,--}}
{{--                                stepSize: 11,--}}
{{--                                min: 0,--}}
{{--                                max: 88--}}
{{--                            },--}}
{{--                            scaleLabel: {--}}
{{--                                display: true,--}}
{{--                                labelString: 'Value'--}}
{{--                            }--}}
{{--                        }]--}}
{{--                    }--}}
{{--                }--}}
{{--            };--}}


{{--            var ctx = document.getElementById('canvas-graph-one').getContext('2d');--}}
{{--            console.log(ctx)--}}
{{--            window.myBar = new Chart(ctx, config);--}}

{{--        }--}}

{{--    });--}}
{{--</script>--}}

<script>
    $(document).ready(function() {
        $('.custom-select2').select2();

    });

    $('body').find('.ckeditors').each(function() {
        CKEDITOR.replace($(this).attr('id'));
    });
    $('.custom-ckeditor').find('.ckeditors').each(function() {
        CKEDITOR.replace($(this).attr('id'));
    });

    $('.subscribe').click(function(){
        var check = $(this).attr('check');
        console.log(check)
        if(check == 0){
            alert("You can't buy new plan till, your credits left 0 and before month");
        }

    });



{{--</script>--}}
{{--@if(config('shopify-app.appbridge_enabled'))--}}
{{--    <script src="https://unpkg.com/@shopify/app-bridge@0.8.2/index.js"></script>--}}
{{--    <script src="https://unpkg.com/@shopify/app-bridge@0.8.2/actions.js"></script>--}}
{{--    <script>--}}
{{--        var AppBridge = window['app-bridge'];--}}
{{--        var createApp = AppBridge.default;--}}
{{--        var actions = window['app-bridge']['actions'];--}}
{{--        var Toast=actions.Toast;--}}
{{--        var Loading=actions.Loading;--}}
{{--        var TitleBar = actions.TitleBar;--}}
{{--        var Button = actions.Button;--}}
{{--        var app = createApp({--}}
{{--            apiKey: '{{ config('shopify-app.api_key') }}',--}}
{{--            shopOrigin: '{{ \Illuminate\Support\Facades\Auth::user()->name }}',--}}
{{--            forceRedirect: true,--}}
{{--        });--}}
{{--        var msg = '{{\Illuminate\Support\Facades\Session::get('msg')}}'--}}
{{--        var error='{{\Illuminate\Support\Facades\Session::get('error')}}';--}}
{{--        if(msg!=='')--}}
{{--        {--}}
{{--            const toastOptions = {--}}
{{--                message: msg,--}}
{{--                duration: 3000,--}}
{{--            };--}}
{{--            const toastNotice = Toast.create(app, toastOptions);--}}
{{--            toastNotice.dispatch(Toast.Action.SHOW);--}}
{{--        }--}}
{{--        if(error!=='')--}}
{{--        {--}}
{{--            const toastOptions = {--}}
{{--                message: msg,--}}
{{--                duration: 3000,--}}
{{--                isError: true--}}
{{--            };--}}
{{--            const toastNotice = Toast.create(app, toastOptions);--}}
{{--            toastNotice.dispatch(Toast.Action.SHOW);--}}
{{--        }--}}
{{--    </script>--}}
{{--    @include('shopify-app::partials.flash_messages')--}}
{{--    @endif--}}

</body>
</html>
{{--Footer End--}}
