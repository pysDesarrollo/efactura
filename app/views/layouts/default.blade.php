<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Facturaci&oacute;n Electr&oacute;nica - Petr&oacute;leos y Servicios C.A.</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width" />

    <link rel="stylesheet" href="{{ URL::to('/') }}/css/bootstrap.min.css" />

    <link rel="stylesheet" href="{{ URL::to('/') }}/css/custom.css" />
    <link rel="stylesheet" href="{{ URL::to('/') }}/css/icomoon-social.css" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,600,800' rel='stylesheet' type='text/css' />

    <!--        Font awesome-->
    <link rel="stylesheet" href="{{ URL::to('/') }}/fonts/font-awesome-4.3.0/css/font-awesome.min.css" />


    <link rel="stylesheet" href="{{ URL::to('/') }}/css/leaflet.css" />
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="css/leaflet.ie.css" />
    <![endif]-->
    <link rel="stylesheet" href="{{ URL::to('/') }}/css/main.css" />

    <script src="{{ URL::to('/') }}/js/jquery.js"></script>

    <script src="{{ URL::to('/') }}/js/jquery.numeric.js"></script>
    <script src="{{ URL::to('/') }}/js/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/1.4.5/numeral.min.js"></script>

    <!--  Validator  -->
    <script src="{{ URL::to('/') }}/js/plugins/jquery-validation-1.13.1/dist/jquery.validate.min.js"></script>
    <script src="{{ URL::to('/') }}/js/plugins/jquery-validation-1.13.1/dist/localization/messages_es.js"></script>

    <!--   Input mask     -->
    <script src="{{ URL::to('/') }}/js/plugins/jquery-inputmask-3.1.49/dist/jquery.inputmask.bundle.js"></script>

    <!--    Qtip    -->
    <script src="{{ URL::to('/') }}/js/plugins/jquery-qtip-2.2.1/jquery.qtip.min.js"></script>
    <link rel="stylesheet" href="{{ URL::to('/') }}/js/plugins/jquery-qtip-2.2.1/jquery.qtip.min.css" />

    <!--    Max Length  -->
    <script src="{{ URL::to('/') }}/js/plugins/bootstrap-maxlength/js/bootstrap-maxlength.min.js"></script>

    <!--    bootbox -->
    <script src="{{ URL::to('/') }}/js/plugins/bootbox-4.3.0/js/bootbox.min.js"></script>
    <!--  UI  -->
    <script src="{{ URL::to('/') }}/js/custom/funciones.js"></script>

    <script src="{{ URL::to('/') }}/js/bootstrap.min.js"></script>

    <!--  CheckBox  -->
    <script src="{{ URL::to('/') }}/js/plugins/bootstrap-switch-master/dist/js/bootstrap-switch.min.js"></script>
    <link rel="stylesheet" href="{{ URL::to('/') }}/js/plugins/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.min.css" />
    @yield('header')
    <script type="text/javascript">
        var img ='<img id="loading"   style="margin-left: 90px" src="{{URL::to("/") }}/img/download.GIF">'
        var dialog
        function openLoader(){
           dialog= bootbox.dialog({
                message: img,
                title: "Cargando, espere por favor",
                size:"small",
                closeButton:false,
                className : "loading",
               class:"loading",
                buttons: {

                }
            });
        }

        function closeLoader(){
//            dialog.hide()
//            bootbox.hideAll()
            dialog.modal("hide")
        }

    </script>
</head>
<body>
<!--[if lt IE 7]>
<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
<![endif]-->
@include('includes.header')

@yield('content')

@include('includes.footer')

</body>
</html>

