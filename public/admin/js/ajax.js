
/**
 * Created by Mohammad on 01/06/2016.
 */

$(function() {
    var cache = {};

    loadView = function (id, data, url, doneCallback, failCallback) {
        if (!cache[id]) {
            cache[id] = $.get(url, data).promise();
        }

        if (typeof(doneCallback) == 'function') {
            cache[id].done(doneCallback);
        }

        if (typeof(failCallback) == 'function') {
            cache[id].fail(failCallback);
        }
    }

    $(document).on('click', '#open-modal', function(e) {
        e.preventDefault();

        var Modal = $('#' + $(this).data('modal-id')),
            url = $(this).attr('href'),
            title = $(this).data('title'),
            viewId = $(this).data('view-id'),
            viewFile = $(this).data('view-file');

        Modal.modal('show');
        Modal.find('.overlay').fadeIn();

        loadView(viewId, {'file': viewFile}, url, function(data) {
            Modal.find('.overlay').fadeOut();
            Modal.find('#modal-title').text(title);
            Modal.find('#modal-content').html(data);
        }, function() {
            Modal.find('.overlay').fadeOut();
            Modal.find('#modal-content').html('<p>Form failed to load, please try again!</p>');
        });
    });
});