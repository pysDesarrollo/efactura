/*
 48-57      -> numeros
 96-105     -> teclado numerico
 188        -> , (coma)
 190        -> . (punto) teclado
 110        -> . (punto) teclado numerico
 8          -> backspace
 46         -> delete
 9          -> tab
 37         -> flecha izq
 39         -> flecha der
 */
/**
 * retorna true en caso de q la tecla presionada sea un numero (teclado numerico o no), un punto (teclado numerico o no), backspace, delete, tab, flecha izq, flecha der
 *
 * para usar:
 * $(".number").keydown(function (ev) {
 *     return validarInt(ev);
 * });
 *
 * @param ev
 * @returns {boolean}
 */
function validarDec(ev) {
    return ((ev.keyCode >= 48 && ev.keyCode <= 57) ||
            (ev.keyCode >= 96 && ev.keyCode <= 105) ||
            ev.keyCode == 190 || ev.keyCode == 110 ||
            ev.keyCode == 8 || ev.keyCode == 46 || ev.keyCode == 9 ||
            ev.keyCode == 37 || ev.keyCode == 39);
}

/**
 * retorna true en caso de q la tecla presionada sea un numero (teclado numerico o no), backspace, delete, tab, flecha izq, flecha der
 * @param ev
 * @returns {boolean}
 */
function validarInt(ev) {
    return ((ev.keyCode >= 48 && ev.keyCode <= 57) ||
            (ev.keyCode >= 96 && ev.keyCode <= 105) ||
            ev.keyCode == 8 || ev.keyCode == 46 || ev.keyCode == 9 ||
            ev.keyCode == 37 || ev.keyCode == 39);
}

/**
 * retorna true en caso de q la tecla presionada no sea espacio
 * @param ev
 * @returns {boolean}
 */
function validarEspacios(ev) {
    return (ev.keyCode != 32);
}

/**
 * muestra notificaciones flotantes
 * @param msg: el mensaje a mostrar
 * @param type: tipo de mensaje: 'error' o 'success'
 * @param title: el titulo del mensaje (opcional)
 * @param hide: si se oculta solo o no (opcional)
 */
function log(msg, type, title, hide) {
    if (hide === undefined) {
        hide = type != "error";
    }
    if (!title) {
        title = type == 'error' ? "Ha ocurrido un error" : "Transacción exitosa";
    }
    var icon = type == 'error' ? "fa fa-warning fa-2x" : "fa fa-check fa-2x";
    if (msg === undefined) {
        msg = "";
    }
    new PNotify({
        title   : title,
        icon    : icon,
        buttons : {
            closer_hover  : false,
            sticker_hover : false
        },
        styling : 'fontawesome',
        text    : msg,
        type    : type,
        hide    : hide
    });
}

function openLoader(msg, title) {
    msg = $.trim(msg);
    title = $.trim(title);
    var $msg = $("<div/>");
    $msg.addClass("text-center");
    if (msg !== undefined && msg != "") {
        $msg.append("<p>" + msg + "</p>");
    }
    if (title === undefined || title == "") {
        title = "Por favor espere";
    }
    $msg.append(spinnerSquare64);

    bootbox.dialog({
        id          : 'dlgLoader',
        title       : title,
        message     : $msg,
        closeButton : false,
        class       : "modal-sm"
    });
    $("#dlgLoader").css({zIndex:1061})
}
function closeLoader() {
    $("#dlgLoader").modal('hide');
}