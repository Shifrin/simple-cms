/**
 * Created by Mohammad on 06/11/2016.
 */

$(function () {
    var oldFileName,
        inputText,
        matching = $(),
        mixitup = $('#mixitup');

    /**
     * Check the given name is allowed
     * @param string string
     * @returns {boolean}
     */
    function isAllowedName(string) {
        var alphaExp = /^[-_0-9a-zA-Z]+$/;

        if (string.match(alphaExp)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get a param value by using it's name from the given url or current url.
     * @param name string
     * @param url string
     * @returns {*}
     */
    function getParameterByName(name, url) {
        if (!url)
            url = window.location.href;

        name = name.replace(/[\[\]]/g, "\\$&");

        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);

        if (!results)
            return null;

        if (!results[2])
            return '';

        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    /**
     * Update count text
     */
    function updateSelectCount() {
        var checkedBoxeslength = $('.file-details > input[type="checkbox"]:checked').length,
            fileCount = $('.files-count > span'),
            defaultText = fileCount.text(),
            spanEdit = $('span.file-edit');

        if (checkedBoxeslength > 0) {
            fileCount.text(checkedBoxeslength + ' Selected');
        } else {
            fileCount.text(defaultText);
        }

        if (checkedBoxeslength > 1) {
            spanEdit.hide();
        } else {
            spanEdit.show();
        }
    }

    /**
     * Delay function
     */
    var delay = (function () {
        var timer = 0;

        return function (callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    // Initialize mixitup
    mixitup.mixItUp({
        load: {
            filter: 'all'
        }
    });

    // Handle each request of folders
    $('.folder > div').each(function () {
        $(this).click(function () {
            window.location.href = $(this).data('path');
        });
    });

    $('#delete-modal').on('show.bs.modal', function (e) {
        var checkedBoxeslength = $('.file-checkbox:checked').length;

        if (checkedBoxeslength == 0) {
            var n = Noty();

            $.noty.setText(n.options.id, '<i class="fa fa-times-circle"></i> No files selected.');
            $.noty.setType(n.options.id, 'error');

            return false;
        } else {
            return true;
        }
    });

    $('#folder-modal').on('shown.bs.modal', function (e) {
        var input = $('input#new-folder');

        input.focus();
        return true;
    });

    $('body').on('click', '.btn-delete', function () {
        var checkedBoxes = $('.file-checkbox:checked'),
            files = [];

        checkedBoxes.each(function () {
            files.push($(this).data('value'));
        });

        $.post($(this).data('url'), {
            files: files,
            path: getParameterByName('path')
        }, function (response) {
            if (response.error) {
                var n = Noty();

                $.noty.setText(n.options.id, '<i class="fa fa-times-circle"></i>' + response.error);
                $.noty.setType(n.options.id, 'error');
            }
        });
    });

    $('#edit-modal').on('show.bs.modal', function (e) {
        var checkedBoxes = $('.file-checkbox:checked');

        if (checkedBoxes.length == 0) {
            var n = Noty();

            $.noty.setText(n.options.id, '<i class="fa fa-times-circle"></i> No files selected.');
            $.noty.setType(n.options.id, 'error');

            return false;
        } else {
            var checkBox = $(checkedBoxes[0]),
                fileName = checkBox.data('value').split('.');

            oldFileName = fileName[0];

            $('#file-name').attr('value', fileName[0]);
            $('#file-name').attr('data-ext', 1 in fileName ? fileName[1] : '');

            return true;
        }
    });

    $('body').on('click', '.btn-edit', function () {
        var fileInput = $('#file-name'),
            fileName = fileInput.val(),
            fileExt = fileInput.data('ext') == '' ? '' : '.' + fileInput.data('ext'),
            errorMessage = '';

        if (fileName == '' || fileName == oldFileName) {
            fileInput.closest('.form-group').addClass('has-error');
            fileInput.focus();
            errorMessage = '<i class="fa fa-times-circle"></i> Please write a new name.';
        } else if (isAllowedName(fileName)) {
            var data = {
                fileName: fileName + fileExt,
                oldName: oldFileName + fileExt,
                path: getParameterByName('path')
            };

            $.post($(this).data('url'), data, function (resposne) {
                if (resposne.error) {
                    fileInput.closest('.form-group').addClass('has-error');
                    fileInput.focus();
                    errorMessage = '<i class="fa fa-times-circle"></i> ' + resposne.error;
                }
            });
        } else {
            fileInput.closest('.form-group').addClass('has-error');
            fileInput.focus();
            errorMessage = '<i class="fa fa-times-circle"></i> Please write a valid folder name.';
        }

        if (errorMessage !== '') {
            n = Noty();
            $.noty.setText(n.options.id, errorMessage);
            $.noty.setType(n.options.id, 'error');
        }

        return;
    });

    $('body').on('click', '.btn-create', function () {
        var form = $('#folder-modal').find('form'),
            folderName = $('#new-folder').val(),
            formGroup = $('#folder-modal').find('.form-group'),
            errorMessage = '';

        if (folderName == '' || folderName == 'undefined') {
            formGroup.addClass('has-error');
            formGroup.find('input').focus();
            errorMessage = '<i class="fa fa-times-circle"></i> Please write a folder name.';
        } else if (!isAllowedName(folderName)) {
            formGroup.addClass('has-error');
            formGroup.find('input').focus();
            errorMessage = '<i class="fa fa-times-circle"></i> Please write a valid folder name.';
        } else {
            $.post(form.attr('action'), {
                folder: folderName,
                path: getParameterByName('path')
            }, function (resposne) {
                if (resposne.error) {
                    formGroup.addClass('has-error');
                    formGroup.find('input').focus();
                    errorMessage = '<i class="fa fa-times-circle"></i> ' + resposne.error;
                }
            });
        }

        if (errorMessage !== '') {
            n = Noty();
            $.noty.setText(n.options.id, errorMessage);
            $.noty.setType(n.options.id, 'error');
        }

        return;
    });

    $('.file-checkbox').iCheck({
        checkboxClass: 'icheckbox_polaris'
    }).on('ifChecked', function (e) {
        updateSelectCount();
    }).on('ifUnchecked', function (e) {
        updateSelectCount();
    });

    $("#file-search").on('keyup', function () {
        // Delay function invoked to make sure user stopped typing
        delay(function () {
            inputText = $("#file-search").val().toLowerCase();

            // Check to see if input field is empty
            if ((inputText.length) > 0) {
                $('.mix').each(function () {
                    var name = $(this).find('.name').text().toLowerCase();

                    // add item to be filtered out if input text matches items inside the title   
                    if (name.match(inputText)) {
                        matching = matching.add(this);
                    } else {
                        // removes any previously matched item
                        matching = matching.not(this);
                    }
                });

                mixitup.mixItUp('filter', matching);
            } else {
                // resets the filter to show all item if input is empty
                mixitup.mixItUp('filter', 'all');
            }
        }, 200);
    });

    $('#file-input').on('filebatchuploadcomplete', function () {
        location.reload();
    });
});
