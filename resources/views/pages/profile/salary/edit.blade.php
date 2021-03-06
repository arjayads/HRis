@extends(\Request::is('*pim/*') ? 'master.adm-master' : 'master.default')

@section('content')
    @include('partials.notification')
    <div class="row">
        {!! HRis\Navlink::profileLinks($pim) !!}
        @include('pages.profile.salary.form')
    </div>
@stop

@section('custom_css')
    {!! Html::style('/css/plugins/chosen/chosen.css') !!}
@stop

@section('custom_js')
    <!-- Input Mask-->
    {!! Html::script('/js/plugins/jasny/jasny-bootstrap.min.js') !!}
    <!-- iCheck -->
    {!! Html::script('/js/plugins/iCheck/icheck.min.js') !!}
    <!-- Chosen -->
    {!! Html::script('/js/plugins/chosen/chosen.jquery.js') !!}

    <script>

        function getValues(type)
        {
            var data = 0;
            $('.' + type).each(function () {
                data += parseFloat($(this).val());
            });

            return data;
        }

        function display()
        {
            earnings = getValues('earnings') + parseFloat($('#salary').val() / 2);
            deductions = getValues('deductions') + parseFloat($('.tax').val());

            $('#total-earnings').html(parseFloat(earnings).toFixed(2));
            $('#total-deductions').html(parseFloat(deductions).toFixed(2));
            $('#total-salary').html(parseFloat(earnings - deductions).toFixed(2));
        }

        function updateSalary(type)
        {
            var datas = { salary: $('#salary').val(), status: '{{$tax_status}}', deductions: getValues('deductions'), sss: $('#sss').val(), type: type }
                $.ajax({
                    type: "GET",
                    url: '/ajax/' + '{{\Request::path()}}',
                    data: datas
                }).done(function( response ) {
                    var values = jQuery.parseJSON(response);
                    $('#sss').val(parseFloat(values.sss).toFixed(2));
                    $('.tax').val(parseFloat(values.tax).toFixed(2));
                });
            display();
        }

        $(document).ready(function () {

            $('#salary').change(function () {
                updateSalary('tax');
            });

            $('#rfrsh-sss').click(function () {
                updateSalary('sss');
            });

            $('.fields').change(function () {
                display();
            });

            $('.chosen-select').chosen();

            display();

        });

    </script>

@stop

@section('action_area')
    @include('pages.profile.partials.avatar')
@stop