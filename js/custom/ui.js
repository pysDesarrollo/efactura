/**
 * Created with IntelliJ IDEA.
 * User: luz
 * Date: 11/12/13
 * Time: 1:05 PM
 * To change this template use File | Settings | File Templates.
 */

$(function () {
//hace q todos los elementos con un atributo title tengan el title bonito de twitter bootstrap
//$('[title!=]').tooltip({});

    //hace q todos los elementos con un atributo title tengan el title bonito de qtip2
    $('[title!=""]').qtip({
        style    : {
            classes : 'qtip-tipsy'
        },
        position : {
            viewport : $(window),
            my       : "bottom center",
            at       : "top center",
            adjust   : {
                method : 'shift shift'
            }
        }
    });

    //hace q los inputs q tienen maxlenght muestren la cantidad de caracteres utilizados/caracterres premitidos
    $('[maxlength]').maxlength({
        alwaysShow        : true,
        warningClass      : "label label-success",
        limitReachedClass : "label label-danger",
        placement         : 'top'
    });

    //para los dialogs, setea los defaults
    bootbox.setDefaults({
        locale      : "es",
        closeButton : false,
        show        : true
    });

////para el context menu deshabilita el click derecho en las paginas
//$("html").contextMenu({
//    items  : {
//        header : {
//            label  : "No click derecho!!",
//            header : true
//        }
//    }
//});

    $(".digits").keydown(function (ev) {
        return validarInt(ev);
    });

    $(".number").keydown(function (ev) {
        return validarDec(ev);
    });

    $(".noEspacios").keydown(function (ev) {
        return validarEspacios(ev);
    });

    $(".money").inputmask({
        alias          : 'numeric',
        placeholder    : '0',
        digitsOptional : false,
        digits         : 2,
        autoGroup      : true,
        groupSeparator : ','
    });

    function doSearch($btn) {
        var str = $.trim($btn.parent().prev().val());
        var url = $btn.attr("href") + "?search=" + str;
        if (str == "") {
            url = $btn.attr("href");
        }
        location.href = url;
    }

    $(".btn-search").click(function () {
        doSearch($(this));
        return false;
    });
    $(".input-search").focus().keyup(function (ev) {
        if (ev.keyCode == 13) {
            doSearch($(this).next().children("a"));
        }
    });
});


