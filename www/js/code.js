$(document).ready(function () {

    // confirmator

    $('a[data-confirm], button[data-confirm], input[data-confirm]').click(function () {
        return confirm($(this).data().confirm);
    });

    /* Volání AJAXu u všech odkazů s třídou ajax */
    $('a.ajax').live('click', function (e) {
        e.preventDefault();
        $.get(this.href);
    });

    /* AJAXové odeslání formulářů */
    $("form.ajax").live("submit", function () {
        $(this).ajaxSubmit();
        return false;
    });

    $("form.ajax :submit").live("click", function () {
        $(this).ajaxSubmit();
        return false;
    });

    $.nette.init();
});
