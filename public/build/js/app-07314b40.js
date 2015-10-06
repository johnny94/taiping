var TAIPING = TAIPING || {};

(function ($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
})(jQuery);

TAIPING.select2 = (function ($) {

    var ajaxDefaultSetting = {
        minimumInputLength: 1,
        ajax: {
            dataType: 'json',
            delay: 250,
            processResults: function (data, page) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            id: item.id,
                            text: item.name
                        };
                    })
                };
            }
        },
        language: {
            inputTooShort: function () {
                return "最少須輸入 1 個字";
            }
        }
    };

    return {
        init: function (selector, autocomplete, settings) {
            this.config = settings || {};

            if (autocomplete) {
                $.extend(true, this.config, ajaxDefaultSetting);
            }

            this.setup(selector);
        },
        setup: function (selector) {
            $(selector).select2(this.config);
        }
    };

})(jQuery);

// Create Switchings
TAIPING.createClassSwitching = (function ($) {

    var count = 0,

        initSelect2 = function () {
            var settings = {
                ajax: {
                    url: '/api/users/names'
                },
                placeholder: "請輸入老師的姓名"
            };

            TAIPING.select2.init('.class_list', false);
            TAIPING.select2.init('.teacher_list', true, settings);
            TAIPING.select2.init('.period', false, {
                minimumResultsForSearch: Infinity
            });
        },

        destroySelect2 = function () {
            var i = 0,
                className = ['.class_list', '.teacher_list', '.period'];

            for (i = 0; i < className.length; i++) {
                $(className[i]).each(function (index, element) {
                    $(this).select2('destroy');
                });
            }
        },

        addClassSwitching = function () {
            $('#addClassSwitchingForm').click(function () {
                destroySelect2();

                var $cloneForm = $('.classSwitchingForm').first().clone(true);
                count += 1;
                $cloneForm.insertBefore('#back');

                initSelect2();
            });
        },

        removeClassSwitching = function () {
            $('.panel-body').on('click', 'button.close', function () {
                if (count === 0) {
                    return;
                }
                $(this).parents('.classSwitchingForm').remove();
                count -= 1;
            });
        },

        setRequestFieldName = function () {
            $('.classSwitchingForm').each(function (index, val) {
                $(val).find('.form-control').attr('name', function (i, val) {
                    return val.replace(/(classSwitching)\[\d+\]\[(\w+)\]/g, '$1[' + index + '][$2]');
                });
            });
        },

        submitForm = function () {
            $('button[type=submit]').click(function (event) {
                destroySelect2();
                setRequestFieldName();
            });
        };

    return {
        init: function () {
            initSelect2();
            addClassSwitching();
            removeClassSwitching();
            submitForm();
        }
    };

})(jQuery);


TAIPING.bootgrid = (function ($) {

    var config = {
        ajaxSettings: {
            method: 'GET'
        },
        labels: {
            refresh: '重新載入',
            loading: '載入中...',
            search: '搜尋老師',
            noResults: '無搜尋結果！',
            infos: '第 {{ctx.start}} 筆 至 第 {{ctx.end}} 筆，共 {{ctx.total}} 筆'
        }
    };

    return {
        init: function (selector, settings, loadedCallback) {
            var settings = settings || {},
                grid;

            $.extend(true, config, settings);

            grid = $(selector).bootgrid(config);
            if (loadedCallback !== undefined) {
                grid.on('loaded.rs.jquery.bootgrid', loadedCallback);
            }

            return grid;
        }
    };
})(jQuery);

TAIPING.downloadRequest = (function ($) {

    return {
        init: function (link, params) {

            var dlink = link;
            if (params !== undefined) {
                dlink += '?' + $.param(params);
            }

            var $iframe = $("<iframe style='display:none' />");
            $iframe.attr("src", dlink);
            $iframe.appendTo("body");
            $iframe.on('load', function () {
                // The load event will be triggered if the download link return a page.
                alert('下載失敗！');
            });
        }
    };
})(jQuery);

// TODO: Pass a modal trigger as argument when show this modal.
TAIPING.deleteResourceModal = (function ($) {
    var $modal = null,
        resourceUrl = '',
        config = {
            method: 'DELETE',
            dataType: 'json',
            success: function (data) {
                var noTarget = '-1';
                $modal.modal('hide');
                $modal.data('target-id', noTarget);
            },
            error: function (xhr, textStatus) {
                alert(textStatus);
                console.log(xhr.responseText);
            }
        };

    var setResourceUrl = function (url) {
            if (url === undefined || !url) {
                throw {
                    message: 'Resource url is invalid.'
                };
            }

            if (url.indexOf('/', 1) === -1) { // Ignore first slash.
                resourceUrl = url + '/';
            }

            resourceUrl = url;
        },

        setRequestUrl = function (targetID) {
            config.url = resourceUrl + targetID;
        };

    return {
        init: function (selector, url, complete) {
            $modal = $(selector);
            $modal.find('.confirm').on('click', function (e) {
                $.ajax(config);
            });

            setResourceUrl(url);

            if (complete !== undefined && typeof complete === 'function') {
                config.complete = complete;
            }

            return this;
        },
        show: function (targetID) {
            setRequestUrl(targetID);

            $modal.data('target-id', targetID);
            $modal.modal('show');
        }
    };
})(jQuery);

TAIPING.postResourceRequest = (function ($) {
    var config = {
        method: 'POST',
        dataType: 'json',
        beforeSend: function () {
            $('p.collapse').hide();
            var subject = $('#content').val().trim();
            if (subject === '') {
                $('p.text-danger').fadeIn(150);
                return false;
            }
        },
        success: function (data) {
            $('p.text-success').fadeIn(150);
        },
        error: function (xhr, textStatus) {
            $('p.text-danger').fadeIn(150);
            console.log(xhr.responseText);
        }
    };

    return {
        send: function (url, data) {
            if (!url || url === undefined ||
                !data || data === undefined) {
                throw {
                    message: 'Invalid url or data.'
                }
            }

            config.url = url;
            config.data = data;

            $.ajax(config);
        }
    };

})(jQuery);
//# sourceMappingURL=app.js.map