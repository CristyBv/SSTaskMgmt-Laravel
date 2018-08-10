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
/******/ 	return __webpack_require__(__webpack_require__.s = 45);
/******/ })
/************************************************************************/
/******/ ({

/***/ 45:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(46);


/***/ }),

/***/ 46:
/***/ (function(module, exports) {

$(document).ready(function () {

    // constructor for datepicker, on select put the value in the date input

    $("#datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        dateFormat: 'yy-mm-dd',
        orientation: 'bottom auto',
        onSelect: function onSelect(dateText, inst) {
            $("input[name='date']").val(dateText);
        }
    });

    // set for datepicker the last data input

    var lastDeadlineDate = $(this).find('#deadline_date').data('last-deadline-date');
    $("#datepicker").datepicker("setDate", lastDeadlineDate);
});

// function for radio inputs, it taks the id and value and make an option for select

window.radiochange = function radiochange(name, id) {
    var radio = $('input[type=radio][name=\"' + name + '\"]:checked');
    var data = {
        id: radio.val(),
        text: radio.attr('id').substr(radio.val().length)
    };

    var newOption = new Option(data.text, data.id, true, true);
    $('#' + id).empty();
    $('#' + id).append(newOption).trigger('change');
};

// constructor with ajax requests for user input

$("#user").select2({
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

// constructor with ajax requests for project input

$("#project").select2({
    placeholder: "Select a Title",
    allowClear: true,
    ajax: {
        url: projectroute,
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

/***/ })

/******/ });