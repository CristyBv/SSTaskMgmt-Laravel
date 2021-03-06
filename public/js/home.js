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

    // Set DataTable for all task tables
    if ($('#switch_dataTable').is(':checked')) {
        $('.task-table').each(function () {
            $(this).DataTable({
                responsive: true,
                "ordering": false
            });
            $(this).addClass('table-responsive');
        });
        $('.dataTables_length').each(function () {
            $(this).addClass('bs-select');
        });
    }

    // set display none for groups without rows (with or without data table structure)

    $('.dataTables_empty').each(function () {
        $(this).closest('.groupRow').css('display', 'none');
    });

    $('tbody').each(function () {
        if ($(this).children().length == 0) $(this).closest('.groupRow').css('display', 'none');
    });

    // tooltip for data table switch

    $('#switch_span').tooltip();

    // set css and hover for first td of each row of task table

    $('.taskrow').find('td:lt(1)').css({
        'cursor': 'pointer',
        'overflow': 'hidden'
    });

    $('.taskrow').find('td:lt(1)').hover(function () {
        $(this).css('font-weight', 'bold');
    }, function () {
        $(this).css('font-weight', 'normal');
    });

    // redirect to task show if click on first td of each row

    $(".taskrow").find('td:lt(1)').on('click', function (e) {
        taskShow = taskShow.replace(':id', $(this).parent().data('id'));
        window.location.replace(taskShow);
    });

    // set property of popover with manual trigger

    $('[data-toggle="popover"]').popover({
        html: true,
        container: 'body',
        placement: 'right',
        trigger: 'manual',
        content: function content() {
            return $('.popover_content').html();
        }
    });

    // dinamic set of popover content with select2 ajax request

    $(document).on('click', ".popover_button", function () {

        if ($('.popover_content_form_div').children().length != 0) {
            $('.popover_content_form_div').empty();
            $('.popover_button').popover('hide');
        } else {
            $(this).popover('show');
            var select = $('<select class=\"popover_users_select\" name=\"forwarduser\"></select>');
            var id = $(this).data('id');
            select.on('change', function () {
                if ($(this).val() != null) {
                    var submit = $('<input type=\"submit\" class=\"btn btn-success\" value=\"Forward\">');
                    var idField = $('<input>', { 'type': 'hidden', 'name': 'id', 'value': id });
                    $('.popover_content_form_div input').remove();
                    $('.popover_content_form_div').append(idField);
                    $('.popover_content_form_div').append(submit);
                } else {
                    $('.popover_content_form_div input').remove();
                }
            });
            $('.popover_content_form_div').append(select);
            $(".popover_users_select").select2({
                width: '100%',
                placeholder: "Select a Name",
                allowClear: true,
                ajax: {
                    url: userRoute,
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

    // auto submit on filter change

    $('[name=group], [name=group_mytask], [name=groupdesc], [name=groupdesc_mytask], [name=sorttask], [name=sorttask_mytask], [name=taskdesc], [name=taskdesc_mytask], [name=searchtask], [name=searchtask_mytask], [name=switchDataTable]').change(function () {
        $('#filterform').submit();
    });

    // submit on change for status select in task row

    $('.selectstatus').change(function () {
        $(this).parent().submit();
    });

    // redirect to task show if a task is selected in search tasks live

    $('#search_task_live').change(function () {
        taskShow = taskShow.replace(':id', $(this).val());
        window.location.replace(taskShow);
    });
});

$("#search_task_live").select2({
    width: '100%',
    placeholder: "Select a Task",
    allowClear: true,
    ajax: {
        url: taskSearch,
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

// select2 for status select in task row

$(".selectstatus").select2({
    placeholder: "Select a Status",
    allowClear: true,
    width: 'auto'
});

/***/ })

/******/ });