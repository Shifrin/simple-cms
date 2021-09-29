/**
 * Application
 */

$(function() {
    var $body = $('body');
    //
    // $(document).bind('ajaxStart.request', function () {
    //     $body.addClass('loading');
    // });
    // $(document).bind('ajaxStop.request', function () {
    //     $body.removeClass('loading');
    // });
    $(document).on('ajaxStop', function () {
        $body.removeClass('loading');
    });

    // $(document).on({
    //     ajaxStart: function() {
    //         $body.addClass('loading');
    //     }, ajaxStop: function() {
    //         $body.removeClass('loading');
    //     }
    // });
});