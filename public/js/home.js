/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 47);
/******/ })
/************************************************************************/
/******/ ({

/***/ 47:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(48);


/***/ }),

/***/ 48:
/***/ (function(module, exports) {

$(document).ready(function () {

    $('.task-table').each(function () {
        $(this).DataTable({
            responsive: true
        });
        $(this).addClass('table-responsive');
    });
    $('.dataTables_length').each(function () {
        $(this).addClass('bs-select');
    });

    $('.taskrow').find('td:lt(1)').css({
        'cursor': 'pointer',
        'overflow': 'hidden'
    });

    $('.taskrow').find('td:lt(1)').hover(function () {
        $(this).css('font-weight', 'bold');
    }, function () {
        $(this).css('font-weight', 'normal');
    });

    $('.task-table tbody tr').each(function () {
        if (!$(this).hasClass('taskrow')) $(this).closest('table').closest('tr').css('display', 'none');
    });
    $(".taskrow").find('td:lt(1)').on('click', function (e) {
        taskshow = taskshow.replace(':id', $(this).parent().data('id'));
        window.location.replace(taskshow);
    });

    $('[data-toggle="popover"]').popover({
        html: true,
        container: 'body',
        placement: 'right',
        trigger: 'manual',
        content: function content() {
            return $('.popover_content').html();
        }
    });

    $(document).on('click', ".popoverbutton", function () {
        if ($('.formforward').children().length != 0) {
            $('.formforward').empty();
            $('.popoverbutton').popover('hide');
        } else {
            $(this).popover('show');
            var select = $('<select class=\"usersselect\" name=\"forwarduser\"></select>');
            var id = $(this).data('id');
            select.on('change', function () {
                if ($(this).val() != null) {
                    var submit = $('<input type=\"submit\" class=\"btn btn-success\" value=\"Forward\">');
                    var idfield = $('<input>', { 'type': 'hidden', 'name': 'id', 'value': id });
                    $('.formforward input').remove();
                    $('.formforward').append(idfield);
                    $('.formforward').append(submit);
                } else {
                    $('.formforward input').remove();
                }
            });
            $('.formforward').append(select);
            $(".usersselect").select2({
                width: '100%',
                placeholder: "Select a Name",
                allowClear: true,
                ajax: {
                    url: userroute,
                    dataType: 'json',
                    data: function data(params) {
                        var query = {
                            search: params.term,
                            type: 'public'
                        };
                        return query;
                    },
                    processResults: function processResults(data) {
                        return {
                            results: data
                        };
                    }
                }
            });
        }
    });

    $('[name=group], [name=group_mytask], [name=groupdesc], [name=groupdesc_mytask], [name=sorttask], [name=sorttask_mytask], [name=taskdesc], [name=taskdesc_mytask], [name=searchtask], [name=searchtask_mytask]').change(function () {
        $('#filterform').submit();
    });

    $('.selectstatus').change(function () {
        $(this).parent().submit();
    });
});

$(".selectstatus").select2({
    placeholder: "Select a Status",
    allowClear: true,
    width: 'auto'
});

/***/ })

/******/ });