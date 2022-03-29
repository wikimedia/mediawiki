module.exports =
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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 26);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

module.exports = require("vue");

/***/ }),
/* 1 */
/***/ (function(module, exports) {

module.exports = require("@vue/composition-api");

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),
/* 6 */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),
/* 7 */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),
/* 8 */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),
/* 9 */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),
/* 10 */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),
/* 13 */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),
/* 14 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Button_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(2);
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Button_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Button_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */
 /* unused harmony default export */ var _unused_webpack_default_export = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Button_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),
/* 15 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Checkbox_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(3);
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Checkbox_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Checkbox_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */
 /* unused harmony default export */ var _unused_webpack_default_export = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Checkbox_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),
/* 16 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Icon_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(4);
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Icon_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Icon_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */
 /* unused harmony default export */ var _unused_webpack_default_export = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Icon_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),
/* 17 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_OptionsMenu_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(5);
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_OptionsMenu_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_OptionsMenu_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */
 /* unused harmony default export */ var _unused_webpack_default_export = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_OptionsMenu_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),
/* 18 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Dropdown_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(6);
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Dropdown_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Dropdown_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */
 /* unused harmony default export */ var _unused_webpack_default_export = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Dropdown_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),
/* 19 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Input_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(7);
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Input_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Input_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */
 /* unused harmony default export */ var _unused_webpack_default_export = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Input_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),
/* 20 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_ProgressBar_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(8);
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_ProgressBar_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_ProgressBar_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */
 /* unused harmony default export */ var _unused_webpack_default_export = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_ProgressBar_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),
/* 21 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Radio_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(9);
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Radio_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Radio_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */
 /* unused harmony default export */ var _unused_webpack_default_export = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_Radio_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),
/* 22 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_ToggleButton_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(10);
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_ToggleButton_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_ToggleButton_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */
 /* unused harmony default export */ var _unused_webpack_default_export = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_ToggleButton_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),
/* 23 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_TypeaheadSuggestionTitle_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(11);
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_TypeaheadSuggestionTitle_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_TypeaheadSuggestionTitle_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */
 /* unused harmony default export */ var _unused_webpack_default_export = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_TypeaheadSuggestionTitle_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),
/* 24 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_TypeaheadSuggestion_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(12);
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_TypeaheadSuggestion_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_TypeaheadSuggestion_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */
 /* unused harmony default export */ var _unused_webpack_default_export = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_TypeaheadSuggestion_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),
/* 25 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_TypeaheadSearch_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(13);
/* harmony import */ var _node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_TypeaheadSearch_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_TypeaheadSearch_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0__);
/* unused harmony reexport * */
 /* unused harmony default export */ var _unused_webpack_default_export = (_node_modules_mini_css_extract_plugin_dist_loader_js_ref_1_0_node_modules_css_loader_dist_cjs_js_ref_1_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_node_modules_less_loader_dist_cjs_js_node_modules_vue_loader_lib_index_js_vue_loader_options_TypeaheadSearch_vue_vue_type_style_index_0_lang_less___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),
/* 26 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, "version", function() { return /* binding */ version; });

// CONCATENATED MODULE: ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./src/components/button/Button.vue?vue&type=template&id=000e54fa&
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('button',{staticClass:"wvui-button",class:_vm.rootClasses,on:{"click":_vm.onClick}},[_vm._t("default")],2)}
var staticRenderFns = []


// CONCATENATED MODULE: ./src/components/button/Button.vue?vue&type=template&id=000e54fa&

// CONCATENATED MODULE: ./src/components/button/ButtonType.ts
/**
 * Which button type to use. These types are styled differently, to communicate the different
 * roles they play in the UI.
 */
var ButtonType;
(function (ButtonType) {
    /**
     * A normal button that has a frame and is not the most important action.
     */
    ButtonType["Normal"] = "normal";
    /**
     * A primary button triggers the most important action. There should be only one primary button
     * in every view. When using this, also set the type prop to a non-default value, to indicate
     * what kind of action will be taken.
     *
     * When the action prop is set to its default value, a primary button looks the same as a
     * secondary button.
     */
    ButtonType["Primary"] = "primary";
    /**
     * A frameless button. Use this sparingly, in situations where a framed button would distract
     * too much from the surrounding content. This is most often used with icon-only buttons.
     */
    ButtonType["Quiet"] = "quiet";
})(ButtonType || (ButtonType = {}));
function isButtonType(val) {
    // This could just be Object.values( ButtonType ).includes( val ), but we are limited to ES6.
    return Object.keys(ButtonType).some(function (key) { return ButtonType[key] === val; });
}

// CONCATENATED MODULE: ./src/components/button/ButtonAction.ts
/**
 * Signals the consequence of proceeding in a given view. Do not use more than one non-default
 * action per layout as they should guide the user to the most important action (“call to action”).
 */
var ButtonAction;
(function (ButtonAction) {
    /**
     * A generic action that is neither progressive nor destructive. For example,
     * notice dismissal.
     */
    ButtonAction["Default"] = "default";
    /**
     * The consequence of this action is to proceed to the next step in or conclude the current
     * process. For example, creation of a page or submitting data.
     */
    ButtonAction["Progressive"] = "progressive";
    /**
     * The consequence of this action is irreversible, data loss, or is difficult to undo. For
     * example, deleting a page, discarding a draft edit, or blocking a user. **Never** use
     * Destructive for cancellation.
     */
    ButtonAction["Destructive"] = "destructive";
})(ButtonAction || (ButtonAction = {}));
/**
 * @param val
 * @return whether an input is a ButtonAction.
 */
function isButtonAction(val) {
    return Object.keys(ButtonAction).some(function (key) { return ButtonAction[key] === val; });
}

// EXTERNAL MODULE: external "vue"
var external_vue_ = __webpack_require__(0);
var external_vue_default = /*#__PURE__*/__webpack_require__.n(external_vue_);

// CONCATENATED MODULE: ./node_modules/ts-loader??ref--0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/button/Button.vue?vue&type=script&lang=ts&



/**
 * A button wrapping slotted content.
 *
 * @fires {Event} click
 */
/* harmony default export */ var Buttonvue_type_script_lang_ts_ = (external_vue_default.a.extend({
    name: 'WvuiButton',
    props: {
        /**
         * What type of action the button will cause to be taken when clicked.
         * See ButtonAction for what each value means.
         */
        action: {
            type: String,
            default: ButtonAction.Default,
            // use arrow function for type inference of property
            validator: function (value) { return isButtonAction(value); }
        },
        /**
         * Button type. See ButtonType for what each value means.
         */
        type: {
            type: String,
            default: ButtonType.Normal,
            // use arrow function for type inference of property
            validator: function (value) { return isButtonType(value); }
        }
    },
    computed: {
        rootClasses: function () {
            return {
                'wvui-button--action-default': this.action === ButtonAction.Default,
                'wvui-button--action-progressive': this.action === ButtonAction.Progressive,
                'wvui-button--action-destructive': this.action === ButtonAction.Destructive,
                'wvui-button--type-primary': this.type === ButtonType.Primary,
                'wvui-button--type-normal': this.type === ButtonType.Normal,
                'wvui-button--type-quiet': this.type === ButtonType.Quiet,
                'wvui-button--framed': this.type !== ButtonType.Quiet
            };
        }
    },
    methods: {
        onClick: function (event) {
            this.$emit('click', event);
        }
    }
}));

// CONCATENATED MODULE: ./src/components/button/Button.vue?vue&type=script&lang=ts&
 /* harmony default export */ var button_Buttonvue_type_script_lang_ts_ = (Buttonvue_type_script_lang_ts_); 
// EXTERNAL MODULE: ./src/components/button/Button.vue?vue&type=style&index=0&lang=less&
var Buttonvue_type_style_index_0_lang_less_ = __webpack_require__(14);

// CONCATENATED MODULE: ./node_modules/vue-loader/lib/runtime/componentNormalizer.js
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () {
        injectStyles.call(
          this,
          (options.functional ? this.parent : this).$root.$options.shadowRoot
        )
      }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functional component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}

// CONCATENATED MODULE: ./src/components/button/Button.vue






/* normalize component */

var component = normalizeComponent(
  button_Buttonvue_type_script_lang_ts_,
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var Button = (component.exports);
// CONCATENATED MODULE: ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./src/components/checkbox/Checkbox.vue?vue&type=template&id=166bc27a&
var Checkboxvue_type_template_id_166bc27a_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('label',{ref:"label",staticClass:"wvui-checkbox",class:_vm.rootClasses,attrs:{"aria-disabled":_vm.disabled},on:{"click":_vm.focusInput,"keydown":function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"enter",13,$event.key,"Enter")){ return null; }$event.preventDefault();return _vm.clickLabel($event)}}},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.wrappedModel),expression:"wrappedModel"}],ref:"input",staticClass:"wvui-checkbox__input",attrs:{"type":"checkbox","disabled":_vm.disabled},domProps:{"value":_vm.inputValue,"indeterminate":_vm.indeterminate,"checked":Array.isArray(_vm.wrappedModel)?_vm._i(_vm.wrappedModel,_vm.inputValue)>-1:(_vm.wrappedModel)},on:{"change":function($event){var $$a=_vm.wrappedModel,$$el=$event.target,$$c=$$el.checked?(true):(false);if(Array.isArray($$a)){var $$v=_vm.inputValue,$$i=_vm._i($$a,$$v);if($$el.checked){$$i<0&&(_vm.wrappedModel=$$a.concat([$$v]))}else{$$i>-1&&(_vm.wrappedModel=$$a.slice(0,$$i).concat($$a.slice($$i+1)))}}else{_vm.wrappedModel=$$c}}}}),_vm._v(" "),_c('span',{staticClass:"wvui-checkbox__icon"}),_vm._v(" "),_c('span',{staticClass:"wvui-checkbox__label-content"},[_vm._t("default")],2)])}
var Checkboxvue_type_template_id_166bc27a_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/checkbox/Checkbox.vue?vue&type=template&id=166bc27a&

// EXTERNAL MODULE: external "@vue/composition-api"
var composition_api_ = __webpack_require__(1);
var composition_api_default = /*#__PURE__*/__webpack_require__.n(composition_api_);

// CONCATENATED MODULE: ./src/composables/useModelWrapper.ts

/**
 * Provide a computed property that models an input value.
 *
 * This is useful when setting v-model on a component, which then needs to set
 * v-model on an input that it contains. We can't just reuse the first v-model
 * because that would mean mutating a prop. Instead, we need a separate computed
 * property that manually handles setting the value and emitting an event.
 *
 * See the Radio component for sample usage.
 *
 * @param modelValueRef A reactive reference of the modelValue prop provided by
 *                      the parent component via v-model.
 * @param emit Vue's $emit function
 * @return The computed property
 */
function useModelWrapper(modelValueRef, 
// This is Vue's emit function; we don't need to type it more specifically.
// eslint-disable-next-line @typescript-eslint/no-explicit-any
emit) {
    return Object(composition_api_["computed"])({
        get: function () { return modelValueRef.value; },
        set: function (value) { return emit('input', value); }
    });
}
// Prop type definition for the component's modelValue prop.
var modelValueProp = {
    type: [String, Number, Boolean, Array],
    default: false
};

// CONCATENATED MODULE: ./node_modules/ts-loader??ref--0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/checkbox/Checkbox.vue?vue&type=script&lang=ts&



external_vue_default.a.use(composition_api_default.a);
/**
 * A binary input that can exist by itself or in a group. When in a group, any
 * number of checkboxes can be checked at a time.
 *
 * Typical use will involve using v-for to loop through an array of items and
 * output a Checkbox component for each one. Each Checkbox will have the same
 * v-model prop, but different inputValue props and label content.
 *
 * For a single checkbox, the v-model value will be a boolean true when the box
 * is checked and false when unchecked.
 *
 * For multiple checkboxes, the v-model value will be an array of the
 * inputValues of any current checked boxes (or an empty array if no boxes are
 * checked).
 *
 * @fires {Event} input
 */
/* harmony default export */ var Checkboxvue_type_script_lang_ts_ = (Object(composition_api_["defineComponent"])({
    name: 'WvuiCheckbox',
    model: {
        prop: 'modelValue',
        event: 'input'
    },
    props: {
        /**
         * Value provided by v-model in a parent component.
         *
         * Rather than directly binding a value prop to this component, use
         * v-model on this component in the parent component.
         */
        modelValue: modelValueProp,
        /**
         * HTML "value" attribute to assign to the input.
         *
         * A unique inputValue is required when using the same v-model for
         * multiple inputs. If this is a standalone checkbox, the inputValue
         * prop can be ommitted and will default to false.
         */
        inputValue: {
            type: [String, Number, Boolean],
            default: false
        },
        /**
         * Whether the disabled attribute should be added to the input.
         */
        disabled: {
            type: Boolean,
            default: false
        },
        /**
         * Whether the indeterminate visual state should be displayed.
         *
         * The indeterminate state indicates that a checkbox is neither on nor
         * off. Within this component, this state is purely visual. The parent
         * component must house the logic to set a checkbox to the indeterminate
         * state via this prop (e.g. in the case of a set of nested checkboxes
         * where some boxes are checked and some are not, making the parent
         * checkbox neither fully on nor fully off).
         *
         * This prop is independent of the value provided by v-model. If
         * indeterminate is set to true, the indeterminate visual state will
         * display, but the value will not be affected. Nor will the value
         * affect the visual state: indeterminate overrides the checked and
         * unchecked visual states. If indeterminate changes to false, the
         * visual state will reflect the current v-model value.
         */
        indeterminate: {
            type: Boolean,
            default: false
        },
        /**
         * Whether the component should display inline.
         *
         * By default, `display: block` is set and a margin exists between
         * sibling components, for a stacked layout.
         */
        inline: {
            type: Boolean,
            default: false
        }
    },
    setup: function (props, _a) {
        var emit = _a.emit;
        var rootClasses = Object(composition_api_["computed"])(function () {
            return {
                'wvui-checkbox--inline': !!props.inline
            };
        });
        // Declare template refs.
        var input = Object(composition_api_["ref"])();
        var label = Object(composition_api_["ref"])();
        /**
         * When the label is clicked, focus on the input.
         *
         * This doesn't happen automatically in Firefox or Safari.
         */
        var focusInput = function () {
            input.value.focus();
        };
        /**
         * On enter keydown, click the label to toggle the input.
         */
        var clickLabel = function () {
            label.value.click();
        };
        // Take the modelValue provided by the parent component via v-model and
        // generate a wrapped model that we can use for the input element in
        // this component.
        var modelValueRef = Object(composition_api_["toRef"])(props, 'modelValue');
        var wrappedModel = useModelWrapper(modelValueRef, emit);
        return {
            rootClasses: rootClasses,
            input: input,
            label: label,
            wrappedModel: wrappedModel,
            focusInput: focusInput,
            clickLabel: clickLabel
        };
    }
}));

// CONCATENATED MODULE: ./src/components/checkbox/Checkbox.vue?vue&type=script&lang=ts&
 /* harmony default export */ var checkbox_Checkboxvue_type_script_lang_ts_ = (Checkboxvue_type_script_lang_ts_); 
// EXTERNAL MODULE: ./src/components/checkbox/Checkbox.vue?vue&type=style&index=0&lang=less&
var Checkboxvue_type_style_index_0_lang_less_ = __webpack_require__(15);

// CONCATENATED MODULE: ./src/components/checkbox/Checkbox.vue






/* normalize component */

var Checkbox_component = normalizeComponent(
  checkbox_Checkboxvue_type_script_lang_ts_,
  Checkboxvue_type_template_id_166bc27a_render,
  Checkboxvue_type_template_id_166bc27a_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var Checkbox = (Checkbox_component.exports);
// CONCATENATED MODULE: ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./src/components/dropdown/Dropdown.vue?vue&type=template&id=119ed76a&
var Dropdownvue_type_template_id_119ed76a_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"wvui-dropdown",class:_vm.rootClasses},[_c('div',{ref:"handle",staticClass:"wvui-dropdown__handle",attrs:{"tabindex":"0","role":"combobox","aria-autocomplete":"list","aria-owns":_vm.menuId,"aria-haspopup":"listbox","aria-disabled":_vm.disabled ? 'true' : null,"aria-expanded":_vm.showMenu ? 'true' : 'false'},on:{"mousedown":function($event){$event.preventDefault();},"click":_vm.onClick,"blur":function($event){_vm.showMenu = false},"keydown":[function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"enter",13,$event.key,"Enter")&&_vm._k($event.keyCode,"up",38,$event.key,["Up","ArrowUp"])&&_vm._k($event.keyCode,"down",40,$event.key,["Down","ArrowDown"])){ return null; }$event.preventDefault();$event.stopPropagation();return _vm.onKeyNavigation($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"esc",27,$event.key,["Esc","Escape"])){ return null; }$event.preventDefault();$event.stopPropagation();_vm.showMenu = false}]}},[_vm._t("selectedItem",[_vm._v("\n\t\t\t"+_vm._s(_vm.selectedItem ? _vm.selectedItem.label : _vm.defaultLabel)+"\n\t\t")],{"item":_vm.selectedItem,"defaultLabel":_vm.defaultLabel}),_vm._v(" "),_c('wvui-icon',{staticClass:"wvui-dropdown__indicator",attrs:{"icon":_vm.wvuiIconExpand}})],2),_vm._v(" "),_c('wvui-options-menu',{directives:[{name:"show",rawName:"v-show",value:(_vm.showMenu),expression:"showMenu"}],ref:"menu",staticClass:"wvui-dropdown__menu",attrs:{"id":_vm.menuId,"items":_vm.items},on:{"select":function($event){_vm.showMenu = false}},scopedSlots:_vm._u([{key:"default",fn:function(ref){
var item = ref.item;
return [_vm._t("menuItem",null,{"item":item})]}}],null,true),model:{value:(_vm.wrappedModel),callback:function ($$v) {_vm.wrappedModel=$$v},expression:"wrappedModel"}})],1)}
var Dropdownvue_type_template_id_119ed76a_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/dropdown/Dropdown.vue?vue&type=template&id=119ed76a&

// CONCATENATED MODULE: ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./src/components/icon/Icon.vue?vue&type=template&id=883488d6&
var Iconvue_type_template_id_883488d6_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('span',{staticClass:"wvui-icon",class:_vm.rootClasses,on:{"click":_vm.onClick}},[_c('svg',{attrs:{"xmlns":"http://www.w3.org/2000/svg","width":"20","height":"20","viewBox":"0 0 20 20","aria-hidden":_vm.lacksTitle}},[(_vm.iconTitle)?_c('title',[_vm._v(_vm._s(_vm.iconTitle))]):_vm._e(),_vm._v(" "),_c('path',{attrs:{"fill":"currentColor","d":_vm.iconPath}})])])}
var Iconvue_type_template_id_883488d6_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/icon/Icon.vue?vue&type=template&id=883488d6&

// CONCATENATED MODULE: ./src/components/icon/iconTypes.ts
/**
 * @param icon The icon string or object.
 * @param langCode The HTMLElement.lang code.
 * @param dir The HTMLElement.dir (ltr, rtl, or auto).
 * @return The appropriate SVG path.
 */
function getIconPath(icon, langCode, dir) {
    if (typeof icon === 'string') {
        return icon;
    }
    // Icon with a single path.
    if ('path' in icon) {
        return icon.path;
    }
    // Icon that differs per language.
    if ('langCodeMap' in icon) {
        var langCodeIcon = langCode in icon.langCodeMap ?
            icon.langCodeMap[langCode] :
            icon.default;
        return typeof langCodeIcon === 'string' ? langCodeIcon : langCodeIcon.path;
    }
    // Icon that differs between LTR and RTL languages but can't just
    // be flipped horizontally.
    return dir === 'rtl' ? icon.rtl : icon.default;
}
/**
 * @param icon The icon string or object.
 * @param langCode The HTMLElement.lang code.
 * @return Whether the icon should be flipped horizontally in RTL mode.
 */
function shouldFlip(icon, langCode) {
    var _a;
    if (typeof icon === 'string') {
        return false;
    }
    if ('shouldFlipExceptions' in icon) {
        // Don't flip if the current language is listed as an exception.
        var exception = (_a = icon.shouldFlipExceptions) === null || _a === void 0 ? void 0 : _a.indexOf(langCode);
        return exception === undefined || exception === -1;
    }
    if ('shouldFlip' in icon) {
        return !!icon.shouldFlip;
    }
    return false;
}

// CONCATENATED MODULE: ./node_modules/ts-loader??ref--0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/icon/Icon.vue?vue&type=script&lang=ts&


/**
 * SVG icon.
 *
 * See src/themes/icons.ts for a list of all icons. To use an icon, import it,
 * assign it to a name in your component's data option, then use v-bind
 * to set the icon attribute of the <wvui-icon> element to that name.
 *
 * Alternately, custom or third-party icons could be used as long as the icon
 * prop provided to this component is either a string containing the icon's SVG
 * path or one of the icon types described in ./iconTypes.ts.
 */
/* harmony default export */ var Iconvue_type_script_lang_ts_ = (external_vue_default.a.extend({
    name: 'WvuiIcon',
    props: {
        /** The SVG path or an object containing that path plus other data. */
        icon: {
            type: [String, Object],
            required: true
        },
        /**
         * Accessible title for SVG. String or message object. If not included,
         * the SVG will be hidden from screen readers via aria-hidden="true".
         */
        iconTitle: {
            type: [String, Object],
            default: ''
        },
        /**
         * Explicitly set the current HTMLElement.lang. See
         * https://developer.mozilla.org/en-US/docs/Web/API/HTMLElement/lang.
         * Defaults to the document lang.
         */
        langCode: {
            type: String,
            default: function () { return document.documentElement.lang; }
        }
    },
    data: function () {
        return {
            // Initially, use the document dir. Once the component mounts, we'll
            // check the element's computed style and update dir if needed.
            dir: document.documentElement.dir
        };
    },
    computed: {
        rootClasses: function () {
            return {
                'wvui-icon--flip-for-rtl': shouldFlip(this.icon, this.langCode)
            };
        },
        lacksTitle: function () {
            return !this.iconTitle;
        },
        iconPath: function () {
            return getIconPath(this.icon, this.langCode, this.dir);
        }
    },
    mounted: function () {
        // Now that the component is mounted, check its computed style and update dir
        var computedStyle = window.getComputedStyle(this.$el);
        this.dir = (computedStyle === null || computedStyle === void 0 ? void 0 : computedStyle.direction) || this.dir;
    },
    methods: {
        onClick: function (event) {
            this.$emit('click', event);
        }
    }
}));

// CONCATENATED MODULE: ./src/components/icon/Icon.vue?vue&type=script&lang=ts&
 /* harmony default export */ var icon_Iconvue_type_script_lang_ts_ = (Iconvue_type_script_lang_ts_); 
// EXTERNAL MODULE: ./src/components/icon/Icon.vue?vue&type=style&index=0&lang=less&
var Iconvue_type_style_index_0_lang_less_ = __webpack_require__(16);

// CONCATENATED MODULE: ./src/components/icon/Icon.vue






/* normalize component */

var Icon_component = normalizeComponent(
  icon_Iconvue_type_script_lang_ts_,
  Iconvue_type_template_id_883488d6_render,
  Iconvue_type_template_id_883488d6_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var Icon = (Icon_component.exports);
// CONCATENATED MODULE: ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./src/components/options-menu/OptionsMenu.vue?vue&type=template&id=74359c5d&
var OptionsMenuvue_type_template_id_74359c5d_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('ul',{staticClass:"wvui-options-menu",attrs:{"role":"listbox","aria-activedescendant":_vm.activeDescendantId}},_vm._l((_vm.items),function(item,index){return _c('li',{key:item.id,staticClass:"wvui-options-menu__item",class:_vm.itemClasses( item, index ),attrs:{"id":_vm.prefixId( item.id ),"role":"option","aria-disabled":item.disabled ? true : null,"aria-selected":_vm.selectedItemId === item.id},on:{"click":function($event){return _vm.onItemClick( item )},"mousedown":function($event){$event.preventDefault();return _vm.onItemMousedown( item, index )}}},[_vm._t("default",[_vm._v("\n\t\t\t"+_vm._s(item.label)+"\n\t\t")],{"item":item})],2)}),0)}
var OptionsMenuvue_type_template_id_74359c5d_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/options-menu/OptionsMenu.vue?vue&type=template&id=74359c5d&

// CONCATENATED MODULE: ./src/components/options-menu/OptionsMenuItem.ts


// CONCATENATED MODULE: ./src/composables/useGeneratedId.ts


var counter = 0;
/**
 * Composable for automatic ID generation. Provides a computed property called `id` that returns an
 * automatically generated unique ID to use for the HTML `id` attribute. If an `id` attribute is
 * already set on the component by its parent, the manually set ID is used instead of an
 * automatically generated one. Generated IDs look like 'wvui-my-component-42'.
 *
 * Also provides a utility method called `prefixId` that can be used to generate sub-IDs for
 * elements in the component. For example, if the component's ID is 'wvui-my-component-42', then
 * `prefixId( 'foo' )` will return 'wvui-my-component-42-foo'.
 *
 * @param componentName Component name to use in the generated ID, typically in kebab-case
 * @return Object with a computed property called ID and a function called prefixId
 */
function useGeneratedId(componentName) {
    var componentNameWithDash = componentName === undefined ? '' : componentName + '-';
    var generatedId = "wvui-" + componentNameWithDash + counter++;
    var id = Object(composition_api_["computed"])(function (vm) { return vm.$attrs.id || generatedId; });
    var prefixId = function (suffix) {
        // Don't use `this.id` here, because we want `prefixId` to work even if `id` isn't
        // composed in.
        return (this.$attrs.id || generatedId) + "-" + suffix;
    };
    return {
        id: id,
        prefixId: prefixId
    };
}

// CONCATENATED MODULE: ./node_modules/ts-loader??ref--0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/options-menu/OptionsMenu.vue?vue&type=script&lang=ts&




external_vue_default.a.use(composition_api_default.a);
/**
 * Menu that displays a set of options, and lets the user select one.
 *
 * This component is designed to be used inside other components. The logic for keyboard navigation
 * between items is implemented here, but this component doesn't attach keyboard event listeners.
 * The parent component is expected to listen for keyboard events and call handleKeyboardEvent().
 *
 * Set the available items through the items prop, and get/set the ID of the selected item through
 * v-model. The v-model value will be the .id property of the selected item, or null if no item
 * is selected.
 *
 * How items are displayed in the menu can be customized through the main slot. By default, the
 * item's label is used.
 *
 * @example
 *     <wvui-options-menu
 *         v-model="number"
 *         :items="[{id: 1, label: 'One'}, {id: 2, label: 'Two'}]"
 *     />
 *
 * @example
 *     <wvui-options-menu
 *         #default="{ item }"
 *         v-model="number"
 *         :items="[{id: 1, label: 'One'}, {id: 2, label: 'Two'}]"
 *     >
 *         {{ item.label }} (id: {{ item.id }})
 *     </wvui-options-menu>
 */
/* harmony default export */ var OptionsMenuvue_type_script_lang_ts_ = (Object(composition_api_["defineComponent"])({
    name: 'WvuiOptionsMenu',
    model: {
        prop: 'selectedItemId',
        event: 'select'
    },
    props: {
        /**
         * Items to list in the menu. Item IDs must be unique within each menu.
         */
        items: {
            type: Array,
            required: true,
            validator: function (items) {
                if (!Array.isArray(items)) {
                    return false;
                }
                // Check for duplicate item IDs
                var seenIDs = {};
                for (var _i = 0, _a = items; _i < _a.length; _i++) {
                    var item = _a[_i];
                    if (seenIDs[item.id]) {
                        // eslint-disable-next-line no-console
                        console.error("Duplicate item ID " + item.id);
                        return false;
                    }
                    seenIDs[item.id] = true;
                }
                return true;
            }
        },
        /**
         * The ID of the selected item, or null if no item is selected. This is the v-model value.
         */
        selectedItemId: {
            type: String,
            default: null
        }
    },
    setup: function () {
        var prefixId = useGeneratedId('options-menu').prefixId;
        return {
            prefixId: prefixId
        };
    },
    data: function () {
        return {
            // Index of the active item (item the mouse is being held down on), or null if none
            activeItemIndex: null,
            // Index of the item currently highlighted with keyboard navigation, or null if none
            highlightedItemIndex: null
        };
    },
    computed: {
        activeDescendantId: function () {
            if (this.highlightedItemIndex !== null) {
                return this.prefixId(this.items[this.highlightedItemIndex].id);
            }
            if (this.selectedItemId) {
                return this.prefixId(this.selectedItemId);
            }
            return null;
        }
    },
    watch: {
        items: function () {
            // If the items array changes, the indexes may not point to the same items anymore,
            // and may be out of bounds
            this.activeItemIndex = null;
            this.highlightedItemIndex = null;
        }
    },
    methods: {
        itemClasses: function (item, index) {
            return {
                'wvui-options-menu__item--selected': this.selectedItemId === item.id,
                'wvui-options-menu__item--active': this.activeItemIndex === index,
                'wvui-options-menu__item--highlighted': this.highlightedItemIndex === index,
                'wvui-options-menu__item--enabled': !item.disabled,
                'wvui-options-menu__item--disabled': !!item.disabled
            };
        },
        onItemMousedown: function (item, index) {
            var _this = this;
            if (item.disabled) {
                return;
            }
            this.activeItemIndex = index;
            var mouseupHandler = function () {
                _this.activeItemIndex = null;
                document.documentElement.removeEventListener('mouseup', mouseupHandler);
            };
            document.documentElement.addEventListener('mouseup', mouseupHandler);
        },
        onItemClick: function (item) {
            if (!item.disabled) {
                this.$emit('select', item.id);
                this.highlightedItemIndex = null;
            }
        },
        // This is a method instead of a computed property because it's needed so infrequently
        getSelectedItemIndex: function () {
            if (this.selectedItemId === null) {
                return null;
            }
            // We're not allowed to use .findIndex() because we're targeting ES5
            var selectedItemIndex = -1;
            for (var i = 0; i < this.items.length; i++) {
                if (this.items[i].id === this.selectedItemId) {
                    selectedItemIndex = i;
                }
            }
            return selectedItemIndex === -1 ? null : selectedItemIndex;
        },
        moveHighlight: function (direction) {
            var _this = this;
            if (this.items.length === 0) {
                return;
            }
            var move = direction === 'backward' ? -1 : 1;
            // Function that returns the previous/next index, wrapping around the start/end.
            // ( i + move ) % length doesn't work, because -1 % length is -1, but we need length-1.
            // Adding length to the left-hand side gets us the right result when i=0 and move=-1.
            var nextIndex = function (i) {
                return (i + move + _this.items.length) % _this.items.length;
            };
            var startIndex;
            if (this.highlightedItemIndex === null) {
                // Start at the selected item, if there is one, and move by one.
                // If no item is selected, start at the first item and don't move.
                var selectedItemIndex = this.getSelectedItemIndex();
                startIndex = selectedItemIndex === null ? 0 : nextIndex(selectedItemIndex);
            }
            else {
                startIndex = nextIndex(this.highlightedItemIndex);
            }
            // startIndex is the item we would like to highlight next, but it may be disabled.
            // If it is, keep stepping until we find a non-disabled item, or until we loop
            // back around to startIndex
            var potentialIndex = startIndex;
            if (this.items[potentialIndex].disabled) {
                potentialIndex = nextIndex(potentialIndex);
                while (this.items[potentialIndex].disabled && potentialIndex !== startIndex) {
                    potentialIndex = nextIndex(potentialIndex);
                }
                if (this.items[potentialIndex].disabled) {
                    // We looped around and didn't find a non-disabled item: all items are disabled
                    potentialIndex = null;
                }
            }
            this.highlightedItemIndex = potentialIndex;
        },
        selectHighlightedItem: function () {
            if (this.highlightedItemIndex !== null) {
                this.$emit('select', this.items[this.highlightedItemIndex].id);
                this.highlightedItemIndex = null;
            }
        },
        handleKeyboardEvent: function (event) {
            if (event.key === 'Enter') {
                this.selectHighlightedItem();
            }
            else if (event.key === 'ArrowUp') {
                this.moveHighlight('backward');
            }
            else if (event.key === 'ArrowDown') {
                this.moveHighlight('forward');
            }
        }
    }
}));

// CONCATENATED MODULE: ./src/components/options-menu/OptionsMenu.vue?vue&type=script&lang=ts&
 /* harmony default export */ var options_menu_OptionsMenuvue_type_script_lang_ts_ = (OptionsMenuvue_type_script_lang_ts_); 
// EXTERNAL MODULE: ./src/components/options-menu/OptionsMenu.vue?vue&type=style&index=0&lang=less&
var OptionsMenuvue_type_style_index_0_lang_less_ = __webpack_require__(17);

// CONCATENATED MODULE: ./src/components/options-menu/OptionsMenu.vue






/* normalize component */

var OptionsMenu_component = normalizeComponent(
  options_menu_OptionsMenuvue_type_script_lang_ts_,
  OptionsMenuvue_type_template_id_74359c5d_render,
  OptionsMenuvue_type_template_id_74359c5d_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var OptionsMenu = (OptionsMenu_component.exports);
// CONCATENATED MODULE: ./src/themes/icons.ts
/**
 * Wikimedia icons.
 *
 * If tree-shaking cannot be relied upon, consider creating an entry point that
 * defines the icons that are needed instead of including them all.
 */

/* eslint-disable max-len */
var wvuiIconAdd = 'M11 9V4H9v5H4v2h5v5h2v-5h5V9z';
var wvuiIconAlert = 'M11.53 2.3A1.85 1.85 0 0010 1.21 1.85 1.85 0 008.48 2.3L.36 16.36C-.48 17.81.21 19 1.88 19h16.24c1.67 0 2.36-1.19 1.52-2.64zM11 16H9v-2h2zm0-4H9V6h2z';
var wvuiIconAlignCenter = 'M1 15h18v2H1zM1 3h18v2H1z M7 7 H13 A1 1 0 0 1 14 8 V12 A1 1 0 0 1 13 13 H7 A1 1 0 0 1 6 12 V8 A1 1 0 0 1 7 7 z';
var wvuiIconAlignLeft = 'M1 15h18v2H1zm11-8h7v2h-7zm0 4h7v2h-7zM1 3h18v2H1z M2 7 H8 A1 1 0 0 1 9 8 V12 A1 1 0 0 1 8 13 H2 A1 1 0 0 1 1 12 V8 A1 1 0 0 1 2 7 z';
var wvuiIconAlignRight = 'M1 15h18v2H1zm0-8h7v2H1zm0 4h7v2H1zm0-8h18v2H1z M12 7 H18 A1 1 0 0 1 19 8 V12 A1 1 0 0 1 18 13 H12 A1 1 0 0 1 11 12 V8 A1 1 0 0 1 12 7 z';
var wvuiIconArrowNext = {
    path: 'M8.59 3.42L14.17 9H2v2h12.17l-5.58 5.59L10 18l8-8-8-8z',
    shouldFlip: true
};
var wvuiIconArrowPrevious = {
    path: 'M5.83 9l5.58-5.58L10 2l-8 8 8 8 1.41-1.41L5.83 11H18V9z',
    shouldFlip: true
};
var wvuiIconArticle = {
    path: 'M5 1a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V3a2 2 0 00-2-2zm0 3h5v1H5zm0 2h5v1H5zm0 2h5v1H5zm10 7H5v-1h10zm0-2H5v-1h10zm0-2H5v-1h10zm0-2h-4V4h4z',
    shouldFlip: true
};
var wvuiIconArticleAdd = 'M5 1c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-2-2-2zm10 10h-4v4H9v-4H5V9h4V5h2v4h4z';
var wvuiIconArticleCheck = 'M9 17l-4.59-4.59L5.83 11 9 14.17l8-8V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V9z';
var wvuiIconArticleDisambiguation = {
    path: 'M15 1H5c-1.1 0-2 .9-2 2v6h4.6l3.7-3.7L10 4h4v4l-1.3-1.3L9.4 10l3.3 3.3L14 12v4h-4l1.3-1.3L7.6 11H3v6c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-2-2-2z',
    shouldFlip: true
};
var wvuiIconArticleNotFound = {
    path: 'M15 1H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V3a2 2 0 00-2-2zm-4 15H9v-2h2zm2.7-7.6a4.88 4.88 0 01-.3.7 2.65 2.65 0 01-.5.6l-.5.5a2.65 2.65 0 01-.6.5c-.2.2-.3.4-.5.6a1.91 1.91 0 00-.3.8 3.4 3.4 0 00-.1 1H9.1a4.87 4.87 0 01.1-1.2 2.92 2.92 0 01.2-.9 2.51 2.51 0 01.4-.7l.6-.6a1.76 1.76 0 01.5-.4c.2-.1.3-.3.4-.4l.3-.6a1.7 1.7 0 00.1-.7 2.92 2.92 0 00-.2-.9 2.19 2.19 0 00-1-.9.9.9 0 00-.5-.1 1.68 1.68 0 00-1.5.7A2.86 2.86 0 008 8.1H6.2a5.08 5.08 0 01.3-1.7 3.53 3.53 0 01.8-1.3 3.6 3.6 0 011.2-.8 5.08 5.08 0 011.7-.3 5.9 5.9 0 011.4.2 2.59 2.59 0 011.1.7 4.44 4.44 0 01.8 1.1 4 4 0 01.3 1.5 3.08 3.08 0 01-.1.9z',
    shouldFlip: true,
    shouldFlipExceptions: ['he', 'yi']
};
var wvuiIconArticleRedirect = {
    path: 'M5 1a2 2 0 00-2 2v1c0 5 2 8 7 8V9l5 4-5 4v-3c-3.18 0-5.51-.85-7-2.68V17a2 2 0 002 2h10a2 2 0 002-2V3a2 2 0 00-2-2z',
    shouldFlip: true
};
var wvuiIconArticleSearch = 'M12.43 14.34A5 5 0 0110 15a5 5 0 113.95-2L17 16.09V3a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 001.45-.63z M13 10 A3 3 0 0 1 10 13 A3 3 0 0 1 7 10 A3 3 0 0 1 13 10 z';
var wvuiIconArticles = {
    path: 'M7 0a2 2 0 00-2 2h9a2 2 0 012 2v12a2 2 0 002-2V2a2 2 0 00-2-2z M13 20a2 2 0 002-2V5a2 2 0 00-2-2H4a2 2 0 00-2 2v13a2 2 0 002 2zM9 5h4v5H9zM4 5h4v1H4zm0 2h4v1H4zm0 2h4v1H4zm0 2h9v1H4zm0 2h9v1H4zm0 2h9v1H4z',
    shouldFlip: true
};
var wvuiIconArticlesSearch = {
    rtl: 'M13 0a2 2 0 012 2H6a2 2 0 00-2 2v12a2 2 0 01-2-2V2a2 2 0 012-2z M13.8 15.5a4.6 4.7 0 01-2.3.6 4.6 4.7 0 113.7-1.9l2.8 3V5a1.9 1.9 0 00-1.9-2H7a1.9 1.9 0 00-2 1.9V18a1.9 1.9 0 001.9 2H16a1.9 1.9 0 001.4-.6z M14.5 11.5 A3 3 0 0 1 11.5 14.5 A3 3 0 0 1 8.5 11.5 A3 3 0 0 1 14.5 11.5 z',
    default: 'M7 0a2 2 0 00-2 2h9a2 2 0 012 2v12a2 2 0 002-2V2a2 2 0 00-2-2z M10.8 15.6a4.6 4.7 0 01-2.3.6 4.6 4.7 0 113.7-1.9l2.8 3V4.9A1.9 1.9 0 0013.1 3H4a1.9 1.9 0 00-2 1.9V18a1.9 1.9 0 001.9 2H13a1.9 1.9 0 001.4-.6z M11.5 11.5 A3 3 0 0 1 8.5 14.5 A3 3 0 0 1 5.5 11.5 A3 3 0 0 1 11.5 11.5 z'
};
var wvuiIconAttachment = 'M9.5 19.75a4.25 4.25 0 01-4.25-4.25V9a2.75 2.75 0 015.5 0v6h-1.5V9a1.25 1.25 0 00-2.5 0v6.5a2.75 2.75 0 005.5 0V4a2.25 2.25 0 00-4.5 0v1h-1.5V4a3.75 3.75 0 017.5 0v11.5a4.25 4.25 0 01-4.25 4.25z';
var wvuiIconBell = 'M16 7a5.38 5.38 0 00-4.46-4.85C11.6 1.46 11.53 0 10 0S8.4 1.46 8.46 2.15A5.38 5.38 0 004 7v6l-2 2v1h16v-1l-2-2zm-6 13a3 3 0 003-3H7a3 3 0 003 3z';
var wvuiIconBellOutline = 'M11.5 2.19C14.09 2.86 16 5.2 16 8v6l2 2v1H2v-1l2-2V8c0-2.8 1.91-5.14 4.5-5.81V1.5C8.5.67 9.17 0 10 0s1.5.67 1.5 1.5v.69zM10 4C7.79 4 6 5.79 6 8v7h8V8c0-2.21-1.79-4-4-4zM8 18h4c0 1.1-.9 2-2 2s-2-.9-2-2z';
var wvuiIconBigger = 'M14 18h-1.57a.66.66 0 01-.44-.13.87.87 0 01-.25-.34l-1-2.77H5.3l-1 2.77a.83.83 0 01-.24.32.65.65 0 01-.44.15H2L7 5.47h2zm-3.85-4.7L8.42 8.72A12.66 12.66 0 018 7.37q-.1.41-.21.75t-.21.6L5.85 13.3zM15 2l3 4h-6z';
var wvuiIconBlock = 'M10 1a9 9 0 109 9 9 9 0 00-9-9zm5 10H5V9h10z';
var wvuiIconBoldA = 'M8.326 11.274l1.722-4.908s1.305 3.843 1.626 4.907zM13.7 17H17L11.5 3h-3L3 17h3.3l1.24-3.496h4.92z';
var wvuiIconBoldArabAin = 'M6.886 11.891c0 1.576 1.618 2.451 4.856 2.636l2.552-.035.37.052c-.034.14-.29.397-.77.759l-.105.07C12.35 16.458 10.965 17 9.634 17c-1.33 0-2.385-.385-3.163-1.156-.759-.77-1.136-1.82-1.136-3.151.007-1.58.662-3.002 1.967-4.269v-.051l-.707-.642a1.111 1.111 0 01-.26-.735c0-.572.28-1.296.84-2.175C7.936 3.6 8.696 2.994 9.46 3c1.034.006 1.889.49 2.561 1.454.38.56-.035.642-1.242.257-.984-.385-1.783-.058-2.399.964l.02.087 1.312 1.01.059.006c1.64-.58 2.824-.863 3.553-.846-.07.136-.164.42-.285.855a32.391 32.391 0 01-.355 1.146l-.146.434-.45.058c-2.035.28-3.493.836-4.372 1.67-.542.54-.817 1.134-.821 1.778';
var wvuiIconBoldArabDad = 'M15.148 6.191l-1.954-.776.81-1.828 1.97.747-.826 1.857m.905 3.591c-.596-.333-1.167-.498-1.723-.493-.557 0-1.155.24-1.796.719l-.59.443.007.028a52.27 52.27 0 002.976.117h.367c.665-.026 1.16-.076 1.491-.154-.078-.199-.32-.42-.729-.66h-.007M8.113 13.62c-.019-1.056-.385-2.182-1.093-3.384L8.53 8.22l.137.175c.312.393.588 1.08.832 2.062l.075.058a2.51 2.51 0 001.56-.565v-.007l2.021-1.785c.794-.7 1.496-1.05 2.109-1.05.447.004.992.226 1.626.665.642.441 1.032.813 1.167 1.12.074.175.11.45.11.829 0 .81-.129 1.431-.385 1.862a2.314 2.314 0 01-.986.856c-.511.243-1.809.364-3.889.364a96.11 96.11 0 01-3.48-.075l-.165.502c-.296.782-.54 1.298-.73 1.544-.844 1.093-2.082 1.639-3.712 1.639-1.995-.007-2.987-1.073-2.987-3.197.004-1.097.325-2.116.962-3.054.175-.252.347-.429.518-.53.262-.155.336-.105.22.145-.463 1.005-.696 1.806-.7 2.4.008 1.374.876 2.064 2.603 2.068 1.211-.004 2.104-.212 2.678-.624';
var wvuiIconBoldArabJeem = 'M12.33 11.99l-2.26-.9.94-2.14 2.28.87zm2.43 1.34q.2.01.04.3-.16.28-1.12 1.06-1.06.86-1.28 1-1.37.86-2.96.85-1.6 0-2.66-1.06-1.23-1.25-1.23-3.45 0-2.8 2.26-5.32l-.05-.1q-1.18 0-1.53.06-.34.05-.57.25-.76.64-.77.31 0-.06.07-.33.43-1.22 1.23-2.05.8-.83 1.85-.83.7 0 2.21.22 1.52.22 2.18.22h1.67l-1.22 2.47-.36.01-1.27.08-1.43.11q-.92.28-1.77 1.18Q7 9.45 7 10.7q.02 3.27 3.82 3.28 1.42 0 3.7-.63.16-.04.25-.03z';
var wvuiIconBoldArmnTo = 'M12.34 14.929c.148 0 .302-.031.464-.093.16-.071.305-.178.436-.333a1.7 1.7 0 00.324-.614c.087-.254.131-.57.131-.948v-1.883H12.34a1.51 1.51 0 00-.51.093 1.075 1.075 0 00-.456.307 1.79 1.79 0 00-.325.603c-.08.253-.12.579-.12.975 0 .404.042.558.13.826.094.25.213.463.344.63.142.154.297.273.463.344.167.06.328.083.483.083m-3.53-9.317a3.173 3.173 0 00-1.158.535 2.496 2.496 0 00-.799.966c-.19.407-.287.927-.287 1.557V17H3V8.458c0-.922.178-1.729.538-2.424A5.023 5.023 0 015.005 4.29c.618-.464 1.343-.814 2.174-1.05a9.642 9.642 0 012.645-.353c.625 0 1.235.053 1.83.158a8.646 8.646 0 011.699.473 6.81 6.81 0 011.465.773c.446.309.83.67 1.153 1.085.327.404.582.867.76 1.39.179.511.269 1.295.269 1.913h1.616v2.425H17v1.9c0 .69-.121 1.296-.368 1.831-.25.523-.583.963-1.003 1.32-.416.358-.991.629-1.542.815a5.65 5.65 0 01-1.747.269 5.432 5.432 0 01-1.735-.28 4.112 4.112 0 01-1.45-.835 4.102 4.102 0 01-.993-1.395c-.241-.56-.361-1.22-.361-1.975 0-.754.119-1.402.38-1.949.25-.546.583-.813 1.01-1.16.416-.352.903-.61 1.45-.77a5.719 5.719 0 011.7-.25h1.342c-.012-.581-.047-1.24-.285-1.615a2.686 2.686 0 00-.915-.912 3.843 3.843 0 00-1.172-.507c-.445-.107-.687-.112-1.307-.112-.618 0-.76.024-1.2.121z';
var wvuiIconBoldB = 'M9.93 3a9.34 9.34 0 012.39.27 4.53 4.53 0 011.62.73 2.87 2.87 0 01.91 1.18 4 4 0 01.29 1.55 3.09 3.09 0 01-.14.93 2.77 2.77 0 01-.43.83 3.21 3.21 0 01-.75.71 4.56 4.56 0 01-1.09.54 4 4 0 012.1 1.1 2.86 2.86 0 01.68 2 4 4 0 01-.34 1.65 3.73 3.73 0 01-1 1.32 4.66 4.66 0 01-1.6.87 7 7 0 01-2.19.31H5V3zM7.87 5.2V9h1.89a5.1 5.1 0 001.07-.1 2.13 2.13 0 00.78-.32A1.44 1.44 0 0012.1 8a2.07 2.07 0 00.17-.87 2.51 2.51 0 00-.14-.89 1.31 1.31 0 00-.43-.59 1.86 1.86 0 00-.7-.35 4.72 4.72 0 00-1-.1zm2.46 9.58a3.24 3.24 0 001.13-.17 1.91 1.91 0 00.71-.45 1.54 1.54 0 00.37-.64 2.66 2.66 0 00.11-.75 2.2 2.2 0 00-.12-.76 1.36 1.36 0 00-.4-.57 1.89 1.89 0 00-.72-.36 4.09 4.09 0 00-1.1-.13H7.87v3.83z';
var wvuiIconBoldCyrlBe = 'M4.002 3h10.5v2.333h-7v3.5h3.092c1.04 0 1.904.129 2.59.382.685.254 1.268.725 1.75 1.411.49.689.735 1.386.735 2.31 0 .948-.245 1.63-.735 2.306-.488.674-1.046 1.136-1.675 1.385-.622.248-1.511.373-2.667.373H4m5.563-2.333c.875 0 1.493-.059 1.848-.14.356-.09.665-.288.924-.595.268-.304.4-.551.4-.997 0-.65-.233-1.012-.695-1.306-.467-.298-1.249-.463-2.357-.463h-2.18v3.5';
var wvuiIconBoldCyrlPalochka = 'M14 3v1l-1.7.1-.3.3v11.2l.3.3 1.7.1v1H6v-1l1.7-.1.3-.3V4.4l-.3-.3L6 4V3z';
var wvuiIconBoldCyrlTe = 'M8.25 17V5.333H3.583V3h12.834v2.333H11.75V17';
var wvuiIconBoldCyrlZhe = 'M11.167 3v6.013c.382-.038.626-.21.822-.521.196-.31.467-1.019.814-2.124.455-1.449.922-2.373 1.397-2.77.47-.393 1.254-.589 2.35-.589L17 3v2.077l-.45-.01c-.467 0-.805.073-1.025.219-.217.13-.393.35-.527.641-.134.292-.334.887-.597 1.789-.14.478-.292.88-.458 1.204-.16.32-.447.625-.86.91.513.182.933.542 1.264 1.08.336.53.704 1.287 1.101 2.267L17 17h-2.7l-1.365-3.593-.132-.295-.28-.654c-.288-.665-.524-1.088-.711-1.271a.847.847 0 00-.645-.277V17H8.833v-6.09c-.263 0-.445.09-.637.268-.191.175-.429.603-.714 1.28l-.287.653-.131.296L5.699 17H3l1.552-3.812c.381-.942.74-1.688 1.077-2.24.341-.555.773-.925 1.295-1.108-.415-.284-.704-.583-.87-.9a7.417 7.417 0 01-.457-1.214c-.259-.886-.455-1.47-.59-1.773a1.522 1.522 0 00-.524-.665c-.21-.14-.572-.21-1.064-.21H3V3l.45.01c1.112 0 1.902.198 2.373.597.467.404.922 1.325 1.373 2.76.35 1.113.624 1.825.815 2.135.196.303.44.472.822.511v-6.01';
var wvuiIconBoldF = 'M15 5V3H5v14h3v-6h5.833V9H8V5z';
var wvuiIconBoldG = 'M10.168 12.333V10H16v4.903c-.58.554-1.423 1.043-2.527 1.47a9.325 9.325 0 01-3.34.626c-1.435 0-2.687-.295-3.753-.887a5.725 5.725 0 01-2.405-2.548 8.175 8.175 0 01-.806-3.61c0-1.41.303-2.663.898-3.759.604-1.097 1.481-1.936 2.636-2.52C7.583 3.225 8.678 3 9.988 3c1.703 0 3.033.354 3.987 1.061.962.704 1.58 1.675 1.855 2.919l-2.753.507a2.838 2.838 0 00-1.097-1.57c-.53-.396-1.192-.583-1.99-.583-1.212 0-2.175.373-2.894 1.131-.711.759-1.066 1.878-1.066 3.372 0 1.603.362 2.81 1.085 3.616.723.801 1.673 1.201 2.846 1.201.58 0 1.16-.11 1.738-.332.59-.229 1.556-.665 1.972-.987V12.32';
var wvuiIconBoldGeorMan = 'M11.554 12.403c0-2-.46-3-1.379-3-1.013 0-1.519.91-1.519 2.727-.012 1.895.49 2.842 1.51 2.842.926 0 1.39-.856 1.39-2.566m2.527 0c0 3.061-1.302 4.594-3.908 4.594-2.837 0-4.258-1.617-4.258-4.853 0-3.193 1.42-4.79 4.258-4.79.982 0 1.38.736 1.38.736V6.249c0-.92-.525-1.38-1.571-1.38-.668 0-1.001.436-1.001 1.31h-2.73C6.255 4.06 7.492 3 9.956 3c2.762 0 4.139 1.104 4.127 3.313';
var wvuiIconBoldL = 'M5 17V3h3v12h7v2z';
var wvuiIconBoldN = 'M4 3h3l6 9.333V3h3v14h-3L7 7.667V17H4z';
var wvuiIconBoldV = 'M3 3h3.5l3.5 9.333L13.5 3H17l-5.25 14h-3.5z';
var wvuiIconBold = {
    langCodeMap: {
        ar: wvuiIconBoldArabAin,
        be: wvuiIconBoldCyrlTe,
        ce: wvuiIconBoldCyrlPalochka,
        cs: wvuiIconBoldB,
        en: wvuiIconBoldB,
        he: wvuiIconBoldB,
        ml: wvuiIconBoldB,
        pl: wvuiIconBoldB,
        sco: wvuiIconBoldB,
        da: wvuiIconBoldF,
        de: wvuiIconBoldF,
        hu: wvuiIconBoldF,
        ksh: wvuiIconBoldF,
        nn: wvuiIconBoldF,
        no: wvuiIconBoldF,
        sv: wvuiIconBoldF,
        es: wvuiIconBoldN,
        gl: wvuiIconBoldN,
        pt: wvuiIconBoldN,
        eu: wvuiIconBoldL,
        fi: wvuiIconBoldL,
        fa: wvuiIconBoldArabDad,
        fr: wvuiIconBoldG,
        it: wvuiIconBoldG,
        hy: wvuiIconBoldArmnTo,
        ka: wvuiIconBoldGeorMan,
        ky: wvuiIconBoldCyrlZhe,
        ru: wvuiIconBoldCyrlZhe,
        uk: wvuiIconBoldCyrlZhe,
        nl: wvuiIconBoldV,
        os: wvuiIconBoldCyrlBe,
        ur: wvuiIconBoldArabJeem
    },
    default: wvuiIconBoldA
};
var wvuiIconBook = {
    path: 'M15 2a7.65 7.65 0 00-5 2 7.65 7.65 0 00-5-2H1v15h4a7.65 7.65 0 015 2 7.65 7.65 0 015-2h4V2zm2.5 13.5H14a4.38 4.38 0 00-3 1V5s1-1.5 4-1.5h2.5z',
    shouldFlip: true
};
var wvuiIconBookmark = 'M5 1a2 2 0 00-2 2v16l7-5 7 5V3a2 2 0 00-2-2z';
var wvuiIconBookmarkOutline = 'M5 1a2 2 0 00-2 2v16l7-5 7 5V3a2 2 0 00-2-2zm10 14.25l-5-3.5-5 3.5V3h10z';
var wvuiIconBright = 'M17.07 7.07V2.93h-4.14L10 0 7.07 2.93H2.93v4.14L0 10l2.93 2.93v4.14h4.14L10 20l2.93-2.93h4.14v-4.14L20 10zM10 16a6 6 0 116-6 6 6 0 01-6 6z M14.5 10 A4.5 4.5 0 0 1 10 14.5 A4.5 4.5 0 0 1 5.5 10 A4.5 4.5 0 0 1 14.5 10 z';
var wvuiIconBrowser = {
    path: 'M2 2a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V4a2 2 0 00-2-2zm2 1.5A1.5 1.5 0 112.5 5 1.5 1.5 0 014 3.5zM18 16H2V8h16z',
    shouldFlip: true
};
var wvuiIconCalendar = 'M15 3V1h-2v2H7V1H5v2H2a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V5a2 2 0 00-2-2zm3 14H2V8h16zm-2-6h-4v4h4z';
var wvuiIconCamera = 'M10 16c-4.455 0-6.685-5.386-3.535-8.535C9.615 4.315 15 6.545 15 11a5 5 0 01-5 5zM6.42 2.56l-.67.64c-.37.357-.865.808-1.38.81H2C.914 4 0 4.712 0 5.76v10.48C0 17.27 1 18 2 18h16c1 0 2-.716 2-1.76V5.76C20 4.723 19 4 18 4h-2.37c-.515-.002-1.01-.453-1.38-.81l-.67-.64A2 2 0 0012.2 2H7.8a2 2 0 00-1.38.56z M13 11 A3 3 0 0 1 10 14 A3 3 0 0 1 7 11 A3 3 0 0 1 13 11 z';
var wvuiIconCancel = 'M10 0a10 10 0 1010 10A10 10 0 0010 0zM2 10a8 8 0 011.69-4.9L14.9 16.31A8 8 0 012 10zm14.31 4.9L5.1 3.69A8 8 0 0116.31 14.9z';
var wvuiIconChart = 'M3 3H1v16h18v-2H3z M11 11L8 9l-4 4v3h14V5z';
var wvuiIconCheck = 'M7 14.17L2.83 10l-1.41 1.41L7 17 19 5l-1.41-1.42z';
var wvuiIconCheckAll = 'M.29 12.71l1.42-1.42 2.22 2.22 8.3-10.14 1.54 1.26-9.7 11.86zM12 10h5v2h-5zm-3 4h5v2H9zm6-8h5v2h-5z';
var wvuiIconClear = 'M10 0a10 10 0 1010 10A10 10 0 0010 0zm5.66 14.24l-1.41 1.41L10 11.41l-4.24 4.25-1.42-1.42L8.59 10 4.34 5.76l1.42-1.42L10 8.59l4.24-4.24 1.41 1.41L11.41 10z';
var wvuiIconClock = 'M10 0a10 10 0 1010 10A10 10 0 0010 0zm2.5 14.5L9 11V4h2v6l3 3z';
var wvuiIconClose = 'M4.34 2.93l12.73 12.73-1.41 1.41L2.93 4.35z M17.07 4.34L4.34 17.07l-1.41-1.41L15.66 2.93z';
var wvuiIconCode = 'M1 10.08V8.92h1.15c1.15 0 1.15 0 1.15-1.15V5a7.42 7.42 0 01.09-1.3 2 2 0 01.3-.7 1.84 1.84 0 01.93-.68A6.44 6.44 0 016.74 2h1.18v1.15h-.86A1.32 1.32 0 006 3.62a1.71 1.71 0 00-.36 1.23V7a3.22 3.22 0 01-.28 1.72 2 2 0 01-1.26.77 2.15 2.15 0 011.26.79A3.26 3.26 0 015.62 12v3.15A1.67 1.67 0 006 16.37a1.31 1.31 0 001.08.47h.87V18H6.74a6.3 6.3 0 01-2.12-.29 1.82 1.82 0 01-.93-.71 1.94 1.94 0 01-.3-.72A7.46 7.46 0 013.31 15v-3.77c0-1.15 0-1.15-1.15-1.15zm18 0V8.92h-1.15c-1.15 0-1.15 0-1.15-1.15V5a7.42 7.42 0 00-.08-1.32 2 2 0 00-.3-.73 1.84 1.84 0 00-.93-.68A6.44 6.44 0 0013.26 2h-1.18v1.15h.87a1.32 1.32 0 011.05.47 1.71 1.71 0 01.36 1.23V7a3.22 3.22 0 00.28 1.72 2 2 0 001.26.77 2.15 2.15 0 00-1.26.79 3.26 3.26 0 00-.26 1.72v3.15a1.67 1.67 0 01-.38 1.22 1.31 1.31 0 01-1.08.47h-.87V18h1.19a6.3 6.3 0 002.12-.29 1.82 1.82 0 00.93-.68 1.94 1.94 0 00.3-.72 7.46 7.46 0 00.1-1.31v-3.77c0-1.15 0-1.15 1.15-1.15z';
var wvuiIconCollapse = 'M2.5 15.25l7.5-7.5 7.5 7.5 1.5-1.5-9-9-9 9z';
var wvuiIconDie = 'M3 1a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V3a2 2 0 00-2-2zm2 16a2 2 0 112-2 2 2 0 01-2 2zM5 7a2 2 0 112-2 2 2 0 01-2 2zm5 5a2 2 0 112-2 2 2 0 01-2 2zm5 5a2 2 0 112-2 2 2 0 01-2 2zm0-10a2 2 0 112-2 2 2 0 01-2 2z';
var wvuiIconDoubleChevronEnd = {
    path: 'M11 2L9.7 3.3l6.6 6.7-6.6 6.7L11 18l8-8zM2.5 2L1 3.3 7.8 10l-6.7 6.7L2.5 18l8-8z',
    shouldFlip: true
};
var wvuiIconDoubleChevronStart = {
    path: 'M9 2l1.3 1.3L3.7 10l6.6 6.7L9 18l-8-8 8-8zm8.5 0L19 3.3 12.2 10l6.7 6.7-1.4 1.3-8-8 8-8z',
    shouldFlip: true
};
var wvuiIconDownTriangle = 'M10 15L2 5h16z';
var wvuiIconDownload = 'M17 12v5H3v-5H1v5a2 2 0 002 2h14a2 2 0 002-2v-5z M15 9h-4V1H9v8H5l5 6z';
var wvuiIconDraggable = 'M2 11h16v2H2zm0-4h16v2H2zm11 8H7l3 3zM7 5h6l-3-3z';
var wvuiIconEdit = 'M16.77 8l1.94-2a1 1 0 000-1.41l-3.34-3.3a1 1 0 00-1.41 0L12 3.23zM1 14.25V19h4.75l9.96-9.96-4.75-4.75z';
var wvuiIconEditLock = 'M12 12a2 2 0 01-2-2V5.25l-9 9V19h4.75l7-7zm7-8h-.5V2.5a2.5 2.5 0 00-5 0V4H13a1 1 0 00-1 1v4a1 1 0 001 1h6a1 1 0 001-1V5a1 1 0 00-1-1zm-3 4a1 1 0 111-1 1 1 0 01-1 1zm1.5-4h-3V2.75C14.5 2 14.5 1 16 1s1.5 1 1.5 1.75z';
var wvuiIconEditUndoLtr = 'M1 14.25V19h4.75l8.33-8.33-5.27-4.23zM13 2.86V0L8 4l5 4V5h.86c2.29 0 4 1.43 4 4.29H20a6.51 6.51 0 00-6.14-6.43z';
var wvuiIconEditUndoRtl = 'M3 15.25V20h4.75l8.33-8.33-5.27-4.23z M13 2.86V0l5 4-5 4V5h-.86c-2.28 0-4 1.43-4 4.29H6a6.51 6.51 0 016.14-6.43z';
var wvuiIconEditUndo = {
    rtl: wvuiIconEditUndoRtl,
    default: wvuiIconEditUndoLtr
};
var wvuiIconEllipsis = 'M12 10 A2 2 0 0 1 10 12 A2 2 0 0 1 8 10 A2 2 0 0 1 12 10 z M5 10 A2 2 0 0 1 3 12 A2 2 0 0 1 1 10 A2 2 0 0 1 5 10 z M19 10 A2 2 0 0 1 17 12 A2 2 0 0 1 15 10 A2 2 0 0 1 19 10 z';
var wvuiIconError = 'M13.728 1H6.272L1 6.272v7.456L6.272 19h7.456L19 13.728V6.272zM11 15H9v-2h2zm0-4H9V5h2z';
var wvuiIconExitFullscreen = 'M7 7V1H5v4H1v2zM5 19h2v-6H1v2h4zm10-4h4v-2h-6v6h2zm0-8h4V5h-4V1h-2v6z';
var wvuiIconExpand = 'M17.5 4.75l-7.5 7.5-7.5-7.5L1 6.25l9 9 9-9z';
var wvuiIconEye = 'M10 14.5a4.5 4.5 0 114.5-4.5 4.5 4.5 0 01-4.5 4.5zM10 3C3 3 0 10 0 10s3 7 10 7 10-7 10-7-3-7-10-7z M12.5 10 A2.5 2.5 0 0 1 10 12.5 A2.5 2.5 0 0 1 7.5 10 A2.5 2.5 0 0 1 12.5 10 z';
var wvuiIconEyeClosed = 'M12.49 9.94A2.5 2.5 0 0010 7.5z M8.2 5.9a4.38 4.38 0 011.8-.4 4.5 4.5 0 014.5 4.5 4.34 4.34 0 01-.29 1.55L17 14.14A14 14 0 0020 10s-3-7-10-7a9.63 9.63 0 00-4 .85zM2 2L1 3l2.55 2.4A13.89 13.89 0 000 10s3 7 10 7a9.67 9.67 0 004.64-1.16L18 19l1-1zm8 12.5A4.5 4.5 0 015.5 10a4.45 4.45 0 01.6-2.2l1.53 1.44a2.47 2.47 0 00-.13.76 2.49 2.49 0 003.41 2.32l1.54 1.45a4.47 4.47 0 01-2.45.73z';
var wvuiIconFeedback = {
    path: 'M19 16L2 12a3.83 3.83 0 01-1-2.5A3.83 3.83 0 012 7l17-4z M6 9 H6 A2 2 0 0 1 8 11 V15 A2 2 0 0 1 6 17 H6 A2 2 0 0 1 4 15 V11 A2 2 0 0 1 6 9 z',
    shouldFlip: true
};
var wvuiIconFirst = {
    path: 'M3 1h2v18H3zm13.5 1.5L15 1l-9 9 9 9 1.5-1.5L9 10z',
    shouldFlip: true
};
var wvuiIconFlag = {
    path: 'M17 6L3 1v18h2v-6.87z',
    shouldFlip: true
};
var wvuiIconFolderPlaceholder = {
    path: 'M8 2H2A2 2 0 0 0 0 4V16a2 2 0 0 0 2 2H18a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H10Z',
    shouldFlip: true
};
var wvuiIconFullScreen = 'M1 1v6h2V3h4V1zm2 12H1v6h6v-2H3zm14 4h-4v2h6v-6h-2zm0-16h-4v2h4v4h2V1z';
var wvuiIconFunnel = {
    path: 'M19 1l-7 9.33V19L8 17V10.33L1 1Z',
    shouldFlip: true
};
var wvuiIconGlobe = 'M12.2 17.94c1.26-2 2-4.45 2.14-7.06h3.86a8.26 8.26 0 01-6 7.06M1.8 10.88h3.86c.14 2.6.88 5.06 2.14 7.06a8.26 8.26 0 01-6-7.06m6-8.82c-1.26 2-2 4.45-2.14 7.07H1.8a8.26 8.26 0 016-7.07m4.79 8.82A12.5 12.5 0 0110 18a12.51 12.51 0 01-2.59-7.13zM7.4 9.13A12.51 12.51 0 0110 1.99a12.5 12.5 0 012.59 7.14zm10.8 0h-3.87a14.79 14.79 0 00-2.14-7.07 8.26 8.26 0 016 7.07M10 0a10 10 0 100 20 10 10 0 000-20';
var wvuiIconHalfBright = {
    path: 'M17 6.67V3h-4.2L9.87.07 6.94 3H3v3.67L.07 9.6 3 12.53V17h3.94l2.93 2.93L12.8 17H17v-4.47l2.93-2.93zm-7 8.93v-12a6.21 6.21 0 016 6 6.21 6.21 0 01-6 6z',
    shouldFlip: true
};
var wvuiIconHalfStar = {
    path: 'M20 7h-7L10 .5 7 7H0l5.46 5.47-1.64 7 6.18-3.7 6.18 3.73-1.63-7zm-10 6.9V4.6l1.9 3.9h4.6l-3.73 3.4 1 4.28z',
    shouldFlip: true
};
var wvuiIconHeart = 'M14.75 1A5.24 5.24 0 0010 4 5.24 5.24 0 000 6.25C0 11.75 10 19 10 19s10-7.25 10-12.75A5.25 5.25 0 0014.75 1z';
var wvuiIconHelp = {
    path: 'M10.06 1C13 1 15 2.89 15 5.53a4.59 4.59 0 01-2.29 4.08c-1.42.92-1.82 1.53-1.82 2.71V13H8.38v-.81a3.84 3.84 0 012-3.84c1.34-.9 1.79-1.53 1.79-2.71a2.1 2.1 0 00-2.08-2.14h-.17a2.3 2.3 0 00-2.38 2.22v.17H5A4.71 4.71 0 019.51 1a5 5 0 01.55 0z M12 17 A2 2 0 0 1 10 19 A2 2 0 0 1 8 17 A2 2 0 0 1 12 17 z',
    shouldFlip: true,
    shouldFlipExceptions: ['he', 'yi']
};
var wvuiIconHelpNotice = {
    path: 'M10 0a10 10 0 1010 10A10 10 0 0010 0zm1 16H9v-2h2zm2.71-7.6a2.64 2.64 0 01-.33.74 3.16 3.16 0 01-.48.55l-.54.48c-.21.18-.41.35-.58.52a2.54 2.54 0 00-.47.56A2.3 2.3 0 0011 12a3.79 3.79 0 00-.11 1H9.08a8.9 8.9 0 01.07-1.25 3.28 3.28 0 01.25-.9 2.79 2.79 0 01.41-.67 4 4 0 01.58-.58c.17-.16.34-.3.51-.44a3 3 0 00.43-.44 1.83 1.83 0 00.3-.55 2 2 0 00.11-.72 2.06 2.06 0 00-.17-.86 1.71 1.71 0 00-1-.9 1.7 1.7 0 00-.5-.1 1.77 1.77 0 00-1.53.68 3 3 0 00-.5 1.82H6.16a4.74 4.74 0 01.28-1.68 3.56 3.56 0 01.8-1.29 3.88 3.88 0 011.28-.83A4.59 4.59 0 0110.18 4a4.44 4.44 0 011.44.23 3.51 3.51 0 011.15.65 3.08 3.08 0 01.78 1.06 3.54 3.54 0 01.29 1.45 3.39 3.39 0 01-.13 1.01z',
    shouldFlip: true,
    shouldFlipExceptions: ['he', 'yi']
};
var wvuiIconHieroglyph = 'M15 11h-3.75l2.55-3.4a4.75 4.75 0 10-7.6 0L8.75 11H5v2h4v7h2v-7h4zM7.54 3.52A2.75 2.75 0 1112.2 6.4L10 9.33 7.8 6.4a2.69 2.69 0 01-.26-2.88z';
var wvuiIconHighlight = 'M15.14 2.27a1 1 0 00-1.41 0l-10 10a1 1 0 000 1.41L4 14l-3 4h5l1-1 .29.29a1 1 0 001.41 0l10-10a1 1 0 00.03-1.43zM7 15l-2-2 9-9 2 2z';
var wvuiIconHistory = 'M9 6v5h.06l2.48 2.47 1.41-1.41L11 10.11V6z M10 1a9 9 0 00-7.85 13.35L.5 16H6v-5.5l-2.38 2.38A7 7 0 1110 17v2a9 9 0 000-18z';
var wvuiIconHome = 'M10 1L0 10h3v9h4v-4.6c0-1.47 1.31-2.66 3-2.66s3 1.19 3 2.66V19h4v-9h3L10 1z';
var wvuiIconImage = 'M2 2a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V4a2 2 0 00-2-2zm-.17 13l4.09-5.25 2.92 3.51L12.92 8l5.25 7z';
var wvuiIconImageAdd = {
    rtl: 'M12 6v2H8v4H2v6a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2zM3.83 17l3.55-4.5 2.52 3 3.55-4.5L18 17zM4 10h2V6h4V4H6V0H4v4H0v2h4z',
    default: 'M16 17H2l3.5-4.5 2.5 3 3.5-4.5.5.67V8H8V6H2a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-6h-5.75z M16 4V0h-2v4h-4v2h4v4h2V6h4V4z'
};
var wvuiIconImageBroken = 'M16.67 9.47L20 12.13v4.09A2 2 0 0117.78 18H2.22A2 2 0 010 16.22v-5.86L3.33 13l4.45-3.53L12.22 13z M20 9.64L16.67 7l-4.44 3.56L7.78 7l-4.45 3.53L0 7.87V3.78A2 2 0 012.22 2h15.56A2 2 0 0120 3.78z';
var wvuiIconImageGallery = 'M3 5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2zm0 11l3.5-4.5 2.5 3 3.5-4.5 4.5 6zM16 2a2 2 0 012 2H2a2 2 0 012-2z';
var wvuiIconImageLayoutBasic = 'M1 3v14h18V3zm17 13H2V4h16z M8.58 14h.81l3.11-4 3 4H17l-4.5-6L9 12.51 6.5 9.5 3 14h1.56l1.94-2.5z';
var wvuiIconImageLayoutFrame = 'M3 2a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V4a2 2 0 00-2-2zm0 15a1 1 0 01-1-1V4a1 1 0 011-1h14a1 1 0 011 1v12a1 1 0 01-1 1z M17 4H3v12h14zM5 13l2.5-3 2 2L12 9l3 4z';
var wvuiIconImageLayoutFrameless = 'M19 3H1v14h18zM3 14l3.5-4.5 2.5 3L12.5 8l4.5 6z';
var wvuiIconImageLayoutThumbnail = 'M3 2a2 2 0 00-2 2v12a2 2 0 002 2h14a2 2 0 002-2V4a2 2 0 00-2-2zm0 15a1 1 0 01-1-1V4a1 1 0 011-1h14a1 1 0 011 1v12a1 1 0 01-1 1z M17 4H3v10h14zM5 12l2.5-3 2 2L12 8l3 4zm-1 3h12v1H4z';
var wvuiIconImageLock = {
    rtl: 'M8 5a1 1 0 00-1-1h-.5V2.5A2.45 2.45 0 004 0a2.45 2.45 0 00-2.5 2.5V4H1a1 1 0 00-1 1v4a1 1 0 001 1h6a1 1 0 001-1zM4 8a1 1 0 111-1 1 1 0 01-1 1zm1.5-4h-3V2.75C2.5 2 2.5 1 4 1s1.5 1 1.5 1.75zM10 6v4a2 2 0 01-2 2H2v6a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2zM4 17l3.54-4.5 2.53 3 3.54-4.5 4.56 6z',
    default: 'M16 17H2l3.5-4.5 2.5 3 3-3.81A2 2 0 0110 10V6H2a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2v-6h-5.75z M19 4h-.5V2.5a2.5 2.5 0 00-5 0V4H13a1 1 0 00-1 1v4a1 1 0 001 1h6a1 1 0 001-1V5a1 1 0 00-1-1zm-3 4a1 1 0 111-1 1 1 0 01-1 1zm1.5-4h-3V2.75C14.5 2 14.5 1 16 1s1.5 1 1.5 1.75z'
};
var wvuiIconIndent = {
    path: 'M1 16h18v2H1zm8-9h10v2H9zm0 4h10v2H9zM1 2h18v2H1zm5 8l-5 4V6z',
    shouldFlip: true
};
var wvuiIconLightbulb = 'M8 19a1 1 0 001 1h2a1 1 0 001-1v-1H8zm9-12a7 7 0 10-12 4.9S7 14 7 15v1a1 1 0 001 1h4a1 1 0 001-1v-1c0-1 2-3.1 2-3.1A7 7 0 0017 7z';
var wvuiIconInfoDefault = 'M9.5 16A6.61 6.61 0 013 9.5 6.61 6.61 0 019.5 3 6.61 6.61 0 0116 9.5 6.63 6.63 0 019.5 16zm0-14A7.5 7.5 0 1017 9.5 7.5 7.5 0 009.5 2zm.5 6v4.08h1V13H8.07v-.92H9V9H8V8zM9 6h1v1H9z';
var wvuiIconInfo = {
    langCodeMap: {
        ar: wvuiIconLightbulb
    },
    default: wvuiIconInfoDefault
};
var wvuiIconInfoFilledDefault = 'M10 0C4.477 0 0 4.477 0 10s4.477 10 10 10 10-4.477 10-10S15.523 0 10 0zM9 5h2v2H9zm0 4h2v6H9z';
var wvuiIconInfoFilled = {
    langCodeMap: {
        ar: wvuiIconLightbulb
    },
    default: wvuiIconInfoFilledDefault
};
var wvuiIconItalicA = 'M8.605 11.274l3.326-6.543 1.266 6.543zM14.322 17H17L13.703 3h-3L3 17h2.678l2.047-3.995h5.808z';
var wvuiIconItalicArabKehehJeem = 'M17.132 2.845c-1.977.647-3.846 1.355-5.36 2.26-.57.35-.897.831-1.014 1.313a1.47 1.47 0 00.076.91c.221.474.63.671.984.95l.109-.14.618.731c.163.193.403.6.548 1.097.161.539.094.845 0 1.311H9.08c-.396 0-.69.008-.893-.023-.397-.062-.3-.245-.273-.397.385-.148.653-.202 1.09-.163.338-.578.691-1.034 1.056-1.533-1.143.043-2.19.017-3.135-.11-.403-.053-.814-.216-1.276-.18-.42.03-.898.28-1.201.84-.292.52-.51.977-.77 1.493l.875-.549c.268-.163.567-.263.84-.254.184.005.322.062.474.109-.273.238-.595.466-.84.653-.35.303-.82.805-1.059 1.167-.47.72-.81 1.267-1.02 2.076-.21.805.003 1.563.545 2.042.497.443.987.606 1.494.66.758.075 1.406.107 2.333-.222.767-.268 1.192-.644 1.75-1.131-1.03.128-2.119.105-2.952.038-1.015-.081-1.48-.45-1.715-.695-.315-.33-.357-.747-.18-1.423a1.68 1.68 0 01.291-.619c.198-.266.424-.507.692-.765.525-.509 1.178-.86 1.703-1.097-.049.242-.121.518-.06.805.058.269.291.444.513.549.303.14.59.177.805.178 1.656.012 3.336 0 4.993 0 .287 0 .525-.19.692-.437.163-.245.291-.56.4-.986.152-.583.11-1.24-.11-1.896a5.614 5.614 0 00-.84-1.64c-.392-.518-.787-.968-1.166-1.423 1.465-.951 3.167-1.447 4.631-1.97.14-.527.26-1.08.362-1.531zM6.084 12.689c-.303.46-.68.805-1.02 1.166.444.334.876.65 1.284.949.392-.354.731-.786 1.022-1.132-.455-.311-.898-.684-1.275-.983z';
var wvuiIconItalicArabMeem = 'M15.25 6.767l-1.085 2.555h-5.44c-.56 0-1 .14-1.324.427l-.07.128c-.216 2.352-.587 4.151-1.115 5.398a9.694 9.694 0 01-1.262 2.139c-.207.263-.257.217-.147-.14l.165-.587.199-.782.273-1.015.002-.009.236-1.219.3-1.645.412-2.225c.222-.364.49-.744.808-1.143a28.115 28.115 0 011.096-1.272c.152-.107.813-.21 1.99-.31 1.224-.1 1.913-.213 2.058-.342l.076-.149a1.34 1.34 0 00-.06-.46 2.803 2.803 0 00-.271-.609c-.257-.499-.511-.746-.763-.746-.343 0-1.068.312-2.175.939-.42.243-.44.146-.058-.288 1.814-1.995 3.156-2.994 4.025-2.994.443 0 .781.152 1.003.46.156.227.292.7.4 1.411l.236 1.4c.122.684.28 1.045.476 1.08';
var wvuiIconItalicArabTeh = 'M15.68 11.05q0 .82-.04 1.36-.02.55-.08.85-.03.12-2.25.4-1.56.19-3 .19-3.28 0-4.3-.69-1.13-.72-1.13-2.15 0-1 .87-2.33.16-.25.25-.35l-.02.16q-.16.8-.16 1.08 0 .58.17 1 .19.43.58.72.39.29 1.01.43 1.44.33 5.24.13.78-.04 1.63-.18l.1-.14q-.14-.46-.43-.94-.3-.5-.87-1.08l1.18-1.56.01-.01q0-.01 0 0 .03 0 .04.02 1.2 1.25 1.2 3.1zm-3.3-4.08l-.97 1.15-1.2-1.06.94-1.14zm-2.7-.01L8.7 8.1 7.52 7.04l.93-1.13z';
var wvuiIconItalicArmnSha = 'M9.362 4.959a3.587 3.587 0 00-1.086-.313 10.614 10.614 0 00-1.397-.082H5.583L5.886 3h2.012c.651 0 1.216.037 1.693.11a4.93 4.93 0 011.326.386L16.15 5.87l-.385 1.95-3.064-1.36a2.18 2.18 0 00-.506-.156 2.86 2.86 0 00-.672-.07 5.697 5.697 0 00-1.942.326 5.57 5.57 0 00-1.666.948c-.49.409-.905.911-1.249 1.498a6.397 6.397 0 00-.735 1.996c-.28 1.466-.175 2.58.315 3.35.495.76 1.39 1.14 2.676 1.14a5.11 5.11 0 001.727-.275 4.072 4.072 0 001.326-.77c.379-.34.688-.74.928-1.208a5.65 5.65 0 00.534-1.543l.129-.654h1.868l-.14.689a6.917 6.917 0 01-.79 2.153 6.059 6.059 0 01-1.417 1.66 6.49 6.49 0 01-1.978 1.078A7.673 7.673 0 018.651 17c-.896 0-1.674-.133-2.335-.397-.663-.264-1.197-.647-1.602-1.15-.405-.51-.669-1.133-.791-1.877-.123-.748-.091-1.595.093-2.552a7.09 7.09 0 01.77-2.144 7.392 7.392 0 011.34-1.747A6.952 6.952 0 017.872 5.88a6.853 6.853 0 012.022-.665l-.543-.269';
var wvuiIconItalicC = 'M13.509 12.003l1.726.25c-.545 1.562-1.341 2.745-2.385 3.545A5.64 5.64 0 019.333 17c-1.587 0-2.844-.502-3.776-1.505-.923-1.005-1.39-2.442-1.39-4.316 0-2.438.708-4.452 2.12-6.047C7.547 3.712 9.11 3 10.98 3c1.383 0 2.502.385 3.357 1.155.86.77 1.359 1.803 1.495 3.103l-1.63.157c-.172-.98-.528-1.708-1.068-2.188-.534-.49-1.225-.735-2.076-.735-1.596 0-2.887.735-3.873 2.205-.855 1.267-1.283 2.772-1.283 4.514 0 1.392.33 2.454.99 3.187.658.732 1.516 1.098 2.573 1.098.91 0 1.726-.303 2.45-.915.734-.607 1.259-1.47 1.597-2.585';
var wvuiIconItalicD = 'M3.587 17L6.46 3h4.15c.991 0 1.75.073 2.275.22.749.198 1.39.55 1.925 1.061.53.502.933 1.132 1.201 1.89.269.758.402 1.608.402 2.55 0 1.127-.17 2.155-.51 3.085a8.895 8.895 0 01-1.314 2.444c-.537.7-1.104 1.25-1.697 1.652-.588.385-1.284.68-2.093.875-.613.142-1.365.222-2.264.222H3.587m2.17-1.587h2.177c.983 0 1.855-.093 2.62-.28a3.803 3.803 0 001.225-.509 4.888 4.888 0 001.213-1.137 7.76 7.76 0 001.137-2.13c.289-.8.432-1.71.432-2.73 0-1.13-.194-2.001-.583-2.607-.388-.609-.881-1.015-1.482-1.213-.443-.145-1.136-.217-2.077-.217H8.254L6.032 15.413';
var wvuiIconItalicE = 'M3.583 17L6.47 3h9.947l-.33 1.595H7.983l-.875 4.238h7.105l-.327 1.595H6.78l-1.027 4.985h8.68L14.104 17z';
var wvuiIconItalicGeorKan = 'M13.567 13.107C13.053 15.703 11.427 17 8.693 17c-2.24 0-3.36-.918-3.36-2.753 0-.348.042-.728.126-1.14.097-.502.286-.975.57-1.42l1.446.706-.24.723a4.04 4.04 0 00-.097.829c0 1.131.607 1.703 1.825 1.703 1.528 0 2.46-.845 2.788-2.532l.068-.385a3.698 3.698 0 00.077-.717c0-1.082-.637-1.622-1.913-1.622H8.682l.288-1.47h1.304c1.404-.005 2.229-.642 2.474-1.913.046-.21.066-.415.066-.607 0-1.335-1.05-2-3.145-2L9.93 3c3.157 0 4.737 1.023 4.737 3.065 0 .29-.032.602-.096.937-.238 1.274-1.225 2.128-2.963 2.56l-.039.193c1.435.233 2.153.96 2.153 2.184 0 .245-.03.505-.087.782l-.067.387';
var wvuiIconItalicI = 'M14 3v1l-1.32.08-.33.29-2.11 11.23.25.29L12 16v1H6v-1l1.32-.1.31-.31L9.74 4.38l-.21-.29L8 4V3z';
var wvuiIconItalicK = 'M10.313 8.427L16.125 3h-2.333L7.597 9.106 9.125 3h-1.75l-3.5 14h1.75l1.369-5.475L8.79 9.847C9.125 14.667 12.625 17 12.625 17h2.333s-4.666-2.333-4.645-8.573z';
var wvuiIconItalicS = 'M15.526 3.702l-.353 1.779a8.585 8.585 0 00-1.816-.733 6.336 6.336 0 00-1.734-.253c-1.09 0-1.96.238-2.601.714-.646.476-.968 1.108-.968 1.898 0 .431.117.758.352 1.003.242.222.855.466 1.843.735l1.093.268c1.236.32 2.094.725 2.575 1.22.482.49.723 1.175.723 2.06 0 1.361-.536 2.469-1.61 3.324C11.967 16.573 10.559 17 8.813 17c-.717 0-1.437-.07-2.16-.216a11.97 11.97 0 01-2.178-.641l.362-1.878a8.88 8.88 0 002.006.939 6.77 6.77 0 002.018.315c1.138 0 2.053-.252 2.737-.758.689-.507 1.03-1.167 1.03-1.98 0-.542-.14-.952-.413-1.23-.271-.281-.86-.536-1.763-.765l-1.093-.28c-1.248-.327-2.1-.7-2.554-1.125-.455-.429-.682-1.026-.682-1.79 0-1.344.516-2.443 1.546-3.299C8.706 3.43 10.05 3 11.707 3a10.985 10.985 0 013.815.686';
var wvuiIconItalic = {
    langCodeMap: {
        ar: wvuiIconItalicArabMeem,
        cs: wvuiIconItalicI,
        en: wvuiIconItalicI,
        fr: wvuiIconItalicI,
        he: wvuiIconItalicI,
        ml: wvuiIconItalicI,
        pl: wvuiIconItalicI,
        pt: wvuiIconItalicI,
        sco: wvuiIconItalicI,
        be: wvuiIconItalicK,
        ce: wvuiIconItalicK,
        da: wvuiIconItalicK,
        de: wvuiIconItalicK,
        fi: wvuiIconItalicK,
        ky: wvuiIconItalicK,
        nn: wvuiIconItalicK,
        no: wvuiIconItalicK,
        os: wvuiIconItalicK,
        sv: wvuiIconItalicK,
        ru: wvuiIconItalicK,
        uk: wvuiIconItalicK,
        es: wvuiIconItalicC,
        gl: wvuiIconItalicC,
        it: wvuiIconItalicC,
        nl: wvuiIconItalicC,
        eu: wvuiIconItalicE,
        fa: wvuiIconItalicArabKehehJeem,
        hu: wvuiIconItalicD,
        hy: wvuiIconItalicArmnSha,
        ksh: wvuiIconItalicS,
        ka: wvuiIconItalicGeorKan,
        ur: wvuiIconItalicArabTeh
    },
    default: wvuiIconItalicA
};
var wvuiIconJournal = {
    path: 'M2 18.5A1.5 1.5 0 003.5 20H5V0H3.5A1.5 1.5 0 002 1.5zM6 0v20h10a2 2 0 002-2V2a2 2 0 00-2-2zm7 8H8V7h5zm3-2H8V5h8z',
    shouldFlip: true
};
var wvuiIconKey = 'M15 6a1.54 1.54 0 01-1.5-1.5 1.5 1.5 0 013 0A1.54 1.54 0 0115 6zm-1.5-5A5.55 5.55 0 008 6.5a6.81 6.81 0 00.7 2.8L1 17v2h4v-2h2v-2h2l3.2-3.2a5.85 5.85 0 001.3.2A5.55 5.55 0 0019 6.5 5.55 5.55 0 0013.5 1z';
var wvuiIconKeyboard = 'M0 15a2 2 0 002 2h16a2 2 0 002-2V5a2 2 0 00-2-2H2a2 2 0 00-2 2zm9-9h2v2H9zm0 3h2v2H9zM6 6h2v2H6zm0 3h2v2H6zm-1 5H3v-2h2zm0-3H3V9h2zm0-3H3V6h2zm9 6H6v-2h8zm0-3h-2V9h2zm0-3h-2V6h2zm3 6h-2v-2h2zm0-3h-2V9h2zm0-3h-2V6h2z';
var wvuiIconLabFlask = 'M13 7.61V3h1V1H6v2h1v4.61l-5.86 9.88A1 1 0 002 19h16a1 1 0 00.86-1.51zm-4.2.88a1 1 0 00.2-.6V3h2v4.89a1 1 0 00.14.51l2.14 3.6H6.72z';
var wvuiIconLanguage = 'M20 18h-1.44a.61.61 0 01-.4-.12.81.81 0 01-.23-.31L17 15h-5l-1 2.54a.77.77 0 01-.22.3.59.59 0 01-.4.14H9l4.55-11.47h1.89zm-3.53-4.31L14.89 9.5a11.62 11.62 0 01-.39-1.24q-.09.37-.19.69l-.19.56-1.58 4.19zm-6.3-1.58a13.43 13.43 0 01-2.91-1.41 11.46 11.46 0 002.81-5.37H12V4H7.31a4 4 0 00-.2-.56C6.87 2.79 6.6 2 6.6 2l-1.47.5s.4.89.6 1.5H0v1.33h2.15A11.23 11.23 0 005 10.7a17.19 17.19 0 01-5 2.1q.56.82.87 1.38a23.28 23.28 0 005.22-2.51 15.64 15.64 0 003.56 1.77zM3.63 5.33h4.91a8.11 8.11 0 01-2.45 4.45 9.11 9.11 0 01-2.46-4.45z';
var wvuiIconLargerText = 'M17.66 18h-2a.85.85 0 01-.56-.17 1.11 1.11 0 01-.32-.43l-1.33-3.53h-6.9L5.22 17.4a1.06 1.06 0 01-.31.41.83.83 0 01-.56.19h-2L8.68 2h2.63zm-4.92-6l-2.2-5.84A16.17 16.17 0 0110 4.43q-.12.52-.27 1t-.27.77L7.26 12z';
var wvuiIconLast = {
    path: 'M15 1h2v18h-2zM3.5 2.5L11 10l-7.5 7.5L5 19l9-9-9-9z',
    shouldFlip: true
};
var wvuiIconLayout = {
    path: 'M8 12V1H1v18h18v-7z M11 1v8h8V1zm6 6h-4V3h4z',
    shouldFlip: true
};
var wvuiIconLink = 'M4.83 15h2.91a4.88 4.88 0 01-1.55-2H5a3 3 0 110-6h3a3 3 0 012.82 4h2.1a4.82 4.82 0 00.08-.83v-.34A4.83 4.83 0 008.17 5H4.83A4.83 4.83 0 000 9.83v.34A4.83 4.83 0 004.83 15z M15.17 5h-2.91a4.88 4.88 0 011.55 2H15a3 3 0 110 6h-3a3 3 0 01-2.82-4h-2.1a4.82 4.82 0 00-.08.83v.34A4.83 4.83 0 0011.83 15h3.34A4.83 4.83 0 0020 10.17v-.34A4.83 4.83 0 0015.17 5z';
var wvuiIconLinkExternal = {
    path: 'M17 17H3V3h5V1H3a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-5h-2z M11 1l3.29 3.29-5.73 5.73 1.42 1.42 5.73-5.73L19 9V1z',
    shouldFlip: true
};
var wvuiIconLinkSecure = 'M16.07 8H15V5s0-5-5-5-5 5-5 5v3H3.93A1.93 1.93 0 002 9.93v8.15A1.93 1.93 0 003.93 20h12.14A1.93 1.93 0 0018 18.07V9.93A1.93 1.93 0 0016.07 8zM7 5.5C7 4 7 2 10 2s3 2 3 3.5V8H7zM10 16a2 2 0 112-2 2 2 0 01-2 2z';
var wvuiIconListBullet = {
    path: 'M7 15h12v2H7zm0-6h12v2H7zm0-6h12v2H7z M5 4 A2 2 0 0 1 3 6 A2 2 0 0 1 1 4 A2 2 0 0 1 5 4 z M5 10 A2 2 0 0 1 3 12 A2 2 0 0 1 1 10 A2 2 0 0 1 5 10 z M5 16 A2 2 0 0 1 3 18 A2 2 0 0 1 1 16 A2 2 0 0 1 5 16 z',
    shouldFlip: true
};
var wvuiIconListNumberedLtr = 'M7 15h12v2H7zm0-6h12v2H7zm0-6h12v2H7zM2 6h1V1H1v1h1zm1 9v1H2v1h1v1H1v1h3v-5H1v1zM1 8v1h2v1H1.5a.5.5 0 00-.5.5V13h3v-1H2v-1h1.5a.5.5 0 00.5-.5v-2a.5.5 0 00-.5-.5z';
var wvuiIconListNumberedRtl = 'M2 15h11v2H2zm0-6h11v2H2zm0-6h11v2H2zm15-2h-1v1h1v4h1V1zm-2 12v1h2v1h-1v1h1v1h-2v1h3v-5zm0-6v1h2v1h-1.5c-.3 0-.5.2-.5.5V12h3v-1h-2v-1h1.5c.3 0 .5-.2.5-.5v-2c0-.3-.2-.5-.5-.5z';
var wvuiIconListNumbered = {
    rtl: wvuiIconListNumberedRtl,
    default: wvuiIconListNumberedLtr
};
var wvuiIconLock = 'M16.07 8H15V5s0-5-5-5-5 5-5 5v3H3.93A1.93 1.93 0 002 9.93v8.15A1.93 1.93 0 003.93 20h12.14A1.93 1.93 0 0018 18.07V9.93A1.93 1.93 0 0016.07 8zM10 16a2 2 0 112-2 2 2 0 01-2 2zm3-8H7V5.5C7 4 7 2 10 2s3 2 3 3.5z';
var wvuiIconLogIn = {
    path: 'M1 11v6c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V3c0-1.1-.9-2-2-2H3c-1.1 0-2 .9-2 2v6h8V5l4.75 5L9 15v-4H1z',
    shouldFlip: true
};
var wvuiIconLogOut = {
    path: 'M3 3h8V1H3a2 2 0 00-2 2v14a2 2 0 002 2h8v-2H3z M13 5v4H5v2h8v4l6-5z',
    shouldFlip: true
};
var wvuiIconLogoCC = 'M10 18a8 8 0 118-8 8 8 0 01-8 8zm0-18A9.94 9.94 0 000 10a9.94 9.94 0 0010 10 9.94 9.94 0 0010-10A9.94 9.94 0 0010 0z M13.49 11.67c-1 0-1.43-.57-1.43-1.71s.43-1.71 1.43-1.71c.57 0 .86.29 1.14.86l1.29-.71A2.8 2.8 0 0013.2 7a2.91 2.91 0 00-2.14.86A2.7 2.7 0 0010.2 10a3 3 0 00.86 2.29 2.91 2.91 0 002.14.86 3.24 3.24 0 002.71-1.57L14.63 11a1.46 1.46 0 01-1.14.71zm-6 0c-1 0-1.43-.57-1.43-1.71s.43-1.71 1.43-1.71c.57 0 .86.29 1.14.86l1.29-.71A2.8 2.8 0 007.2 7a2.91 2.91 0 00-2.14.86A2.7 2.7 0 004.2 10a3 3 0 00.86 2.29 2.91 2.91 0 002.14.86 3.24 3.24 0 002.71-1.57L8.63 11a1.46 1.46 0 01-1.14.71z';
var wvuiIconLogoWikidata = 'M0 4v12.258h.742V4zm1.482 0v12.258h2.223V4zm2.96 0v12.258H6.67V4zm2.964 0v12.258h.744V4zm1.48 0v12.258h.745V4zm1.483 0v12.258h2.224V4zm2.962 0v12.258h.742V4zm1.482 0v12.258h2.223V4zm2.96 0v12.258h.744V4zm1.484 0v12.258H20V4z';
var wvuiIconLogoWikimediaCommons = 'M13.09 6.18a3.68 3.68 0 01-2.18-2.55c.09.09 1.82.91 1.82.91L10 0 7.27 4.55l1.82-.91a5.08 5.08 0 00.55 1.91 5.13 5.13 0 002 2 8.86 8.86 0 012 1.18l-.64.63-.45-.45-.26 1.54 1.54-.26-.45-.45.62-.65a5.69 5.69 0 011.45 3.45h-.91v-.73l-1.26.91 1.26.91v-.73h.91A5.21 5.21 0 0114 16.36l-.64-.64.45-.45-1.53-.27.26 1.54.45-.45.64.64a5.69 5.69 0 01-3.45 1.45v-.91h.73L10 16l-.91 1.27h.73v.91a5.21 5.21 0 01-3.45-1.45l.63-.64.45.45.27-1.54-1.54.26.45.45-.63.65a5.69 5.69 0 01-1.45-3.45h.91v.73l1.26-.91-1.26-.91v.73h-.91A5.21 5.21 0 016 9.09l.64.64-.45.45 1.54.26-.28-1.53-.45.45-.64-.64L5 7.45a7.29 7.29 0 108.09-1.27z M12.5 12.7 A2.5 2.5 0 0 1 10 15.2 A2.5 2.5 0 0 1 7.5 12.7 A2.5 2.5 0 0 1 12.5 12.7 z';
var wvuiIconLogoWikimediaDiscovery = 'M12 17c0 1.1-2 2-2 2s-2-.9-2-2m2-10a1.54 1.54 0 01-1.5-1.5 1.5 1.5 0 013 0A1.54 1.54 0 0110 7zm3.3 4.7C14.1 7.9 12.7 1 10 1S5.8 7.7 6.6 11.5L5 15h2.7l.3 1h4c.2-.3.1-.5.3-1H15z';
var wvuiIconLogoWikimedia = 'M6.08 5.555a6.048 6.048 0 003.055 10.593v-7.54L6.08 5.556zm7.828.004l-3.05 3.05v7.536a6.048 6.048 0 003.05-10.587z M3.414 2.89C1.424 4.69.164 7.287.168 10.173c.007 5.406 4.42 9.806 9.828 9.806 5.407 0 9.82-4.4 9.828-9.806.004-2.886-1.255-5.482-3.246-7.285L14.865 4.6a7.355 7.355 0 012.524 5.568c-.007 4.09-3.3 7.375-7.394 7.375S2.61 14.26 2.604 10.17a7.355 7.355 0 012.523-5.568L3.414 2.89z M13.32 3.32 A3.32 3.32 0 0 1 10 6.64 A3.32 3.32 0 0 1 6.68 3.32 A3.32 3.32 0 0 1 13.32 3.32 z';
var wvuiIconLogoWikipedia = 'M11.14 4H14a.69.69 0 010 .65c-1 .16-1.36.91-1.81 1.83l-1.4 2.75 2.35 5.21h.07l3.52-8.1c.44-1.07.4-1.59-.79-1.7a.68.68 0 010-.65h3.45a.68.68 0 010 .65c-1.21.16-1.42.91-1.81 1.83l-4.37 10.08c-.13.3-.24.45-.44.45s-.33-.16-.42-.45l-2.48-5.73-2.72 5.73c-.11.3-.24.45-.44.45s-.31-.16-.42-.45l-4-10.09c-.57-1.4-.6-1.7-1.65-1.8A.68.68 0 01.62 4h3.91a.68.68 0 010 .65c-1.16.13-1.21.45-.74 1.58l3.41 8.19h.05L9.3 10 7.78 6.45C7.17 5.05 7 4.77 6.24 4.66a.69.69 0 010-.65h3.32a.68.68 0 010 .65c-.74.12-.7.45-.19 1.58l.87 2 .08.09 1-2c.57-1.14.64-1.58-.15-1.7a.69.69 0 01-.03-.63z';
var wvuiIconMap = {
    path: 'M13 3L7 1 1 3v16l6-2 6 2 6-2V1zM7 14.89l-4 1.36V4.35L7 3zm10 .75L13 17V5.1l4-1.36z',
    shouldFlip: true
};
var wvuiIconMapPin = 'M10 0a7.65 7.65 0 00-8 8c0 2.52 2 5 3 6s5 6 5 6 4-5 5-6 3-3.48 3-6a7.65 7.65 0 00-8-8zm0 11.25A3.25 3.25 0 1113.25 8 3.25 3.25 0 0110 11.25z';
var wvuiIconMapPinAdd = 'M10 0a7.65 7.65 0 00-8 8c0 2.52 2 5 3 6s5 6 5 6 4-5 5-6 3-3.48 3-6a7.65 7.65 0 00-8-8zm5 9h-4v4H9V9H5V7h4V3h2v4h4z';
var wvuiIconMapTrail = 'M20 6l-1-1-1.5 1.5L16 5l-1 1 1.5 1.5L15 9l1 1 1.5-1.5L19 10l1-1-1.5-1.5z M11 14.5 A3.5 3.5 0 0 1 7.5 18 A3.5 3.5 0 0 1 4 14.5 A3.5 3.5 0 0 1 11 14.5 z M9 3 A2 2 0 0 1 7 5 A2 2 0 0 1 5 3 A2 2 0 0 1 9 3 z M14 7 A1 1 0 0 1 13 8 A1 1 0 0 1 12 7 A1 1 0 0 1 14 7 z M11 6 A1 1 0 0 1 10 7 A1 1 0 0 1 9 6 A1 1 0 0 1 11 6 z M4 3 A1 1 0 0 1 3 4 A1 1 0 0 1 2 3 A1 1 0 0 1 4 3 z M2 6 A1 1 0 0 1 1 7 A1 1 0 0 1 0 6 A1 1 0 0 1 2 6 z M2 9 A1 1 0 0 1 1 10 A1 1 0 0 1 0 9 A1 1 0 0 1 2 9 z M4 12 A1 1 0 0 1 3 13 A1 1 0 0 1 2 12 A1 1 0 0 1 4 12 z';
var wvuiIconMarkup = 'M6.5 3.5L0 10l1.5 1.5 5 5L8 15l-5-5 5-5zm7 0L12 5l5 5-5 5 1.5 1.5L20 10z';
var wvuiIconMathematics = 'M14 2H4l5 8-5 8h12v-4h-2v2H8.25L12 10 8.25 4H14v2h2V2z';
var wvuiIconMathematicsDisplayBlock = 'M13 5H5l3 5-3 5h10v-3h-2v1H9.2l1.8-3-1.8-3H13v1h2V5zM2 1h16v2H2zm0 16h16v2H2z';
var wvuiIconMathematicsDisplayDefault = 'M12 5H4l3 5-3 5h10v-3h-2v1H8.2l1.8-3-1.8-3H12v1h2V5zM1 9h3v2H1zm15 0h3v2h-3z';
var wvuiIconMathematicsDisplayInline = 'M4 13H0V7h4zm12-6h4v6h-4zM6 6l3 4-3 4h8v-3h-2v1H9.5l1.5-2-1.5-2H12v1h2V6z';
var wvuiIconMenu = 'M1 3v2h18V3zm0 8h18V9H1zm0 6h18v-2H1z';
var wvuiIconMessage = 'M0 8v8a2 2 0 002 2h16a2 2 0 002-2V8l-10 4z M2 2a2 2 0 00-2 2v2l10 4 10-4V4a2 2 0 00-2-2z';
var wvuiIconMoon = 'M17.39 15.14A7.33 7.33 0 0111.75 1.6c.23-.11.56-.23.79-.34a8.19 8.19 0 00-5.41.45 9 9 0 107 16.58 8.42 8.42 0 004.29-3.84 5.3 5.3 0 01-1.03.69z';
var wvuiIconMove = 'M19 10l-4-3v2h-4V5h2l-3-4-3 4h2v4H5V7l-4 3 4 3v-2h4v4H7l3 4 3-4h-2v-4h4v2z';
var wvuiIconMusicalScore = 'M6 2v10.2c-.3-.1-.6-.2-1-.2-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3V5h8v7.2c-.3-.1-.6-.2-1-.2-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3V2z';
var wvuiIconNewWindow = {
    path: 'M17 17H3V3h5V1H3a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-5h-2z M11 1l3.29 3.29-5.73 5.73 1.42 1.42 5.73-5.73L19 9V1z',
    shouldFlip: true
};
var wvuiIconNewline = {
    path: 'M17 4v6H7V6l-6 5 6 5v-4h12V4z',
    shouldFlip: true
};
var wvuiIconNewspaper = {
    path: 'M5 2a2 2 0 00-2 2v12a1 1 0 01-1-1V5h-.5A1.5 1.5 0 000 6.5v10A1.5 1.5 0 001.5 18H18a2 2 0 002-2V4a2 2 0 00-2-2zm1 2h11v4H6zm0 6h6v1H6zm0 2h6v1H6zm0 2h6v1H6zm7-4h4v5h-4z',
    shouldFlip: true
};
var wvuiIconNext = {
    path: 'M7 1L5.6 2.5 13 10l-7.4 7.5L7 19l9-9z',
    shouldFlip: true
};
var wvuiIconNoWikiText = 'M16 3v2h1v10l2 2V3zM9 5V3H5l2 2zM1 1L0 2l1 1v14h3v-2H3V5l2 2v10h4v-2H7V9l6 6h-2v2h4l3 3 1-1zm12 10l2 2V3h-4v2h2z';
var wvuiIconNotBright = 'M18.85 10 A9 9 0 0 1 9.85 19 A9 9 0 0 1 0.8499999999999996 10 A9 9 0 0 1 18.85 10 z';
var wvuiIconNotice = 'M10 0a10 10 0 1010 10A10 10 0 0010 0zm1 16H9v-2h2zm0-4H9V4h2z';
var wvuiIconOngoingConversation = {
    path: 'M2 0a2 2 0 00-2 2v18l4-4h14a2 2 0 002-2V2a2 2 0 00-2-2zm3 9.06a1.39 1.39 0 111.37-1.39A1.39 1.39 0 015 9.06zm5.16 0a1.39 1.39 0 111.39-1.39 1.39 1.39 0 01-1.42 1.39zm5.16 0a1.39 1.39 0 111.39-1.39 1.39 1.39 0 01-1.42 1.39z',
    shouldFlip: true
};
var wvuiIconOutdent = {
    path: 'M1 16h18v2H1zm8-9h10v2H9zm0 4h10v2H9zM1 2h18v2H1zm0 8l5 4V6z',
    shouldFlip: true
};
var wvuiIconOutline = {
    path: 'M1 12h18v7H1zM1 1v8h8V1zm6 6H3V3h4z',
    shouldFlip: true
};
var wvuiIconPageSettings = 'M15 1a2 2 0 0 1 2 2h0V17a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2H3V3A2 2 0 0 1 5 1H15ZM10.75 5H9.25L9 6.37a4 4 0 0 0-.88.38h0L7 5.93 5.93 7l.82 1.07A3.44 3.44 0 0 0 6.37 9h0L5 9.25v1.5L6.37 11a4 4 0 0 0 .38.88h0L5.93 13 7 14.07l1.07-.82a3.44 3.44 0 0 0 .93.38H9L9.25 15h1.5l.2-1.37a3.44 3.44 0 0 0 .93-.38h0l1.12.82L14.07 13l-.82-1.07a3.44 3.44 0 0 0 .38-.93h0L15 10.75V9.25l-1.37-.2a3.44 3.44 0 0 0-.38-.93h0L14.07 7 13 5.93l-1.07.82A3.44 3.44 0 0 0 11 6.37h0ZM10 8.25A1.75 1.75 0 1 1 8.25 10 1.75 1.75 0 0 1 10 8.25Z';
var wvuiIconPause = 'M4 2 H8 A1 1 0 0 1 9 3 V17 A1 1 0 0 1 8 18 H4 A1 1 0 0 1 3 17 V3 A1 1 0 0 1 4 2 z M12 2 H16 A1 1 0 0 1 17 3 V17 A1 1 0 0 1 16 18 H12 A1 1 0 0 1 11 17 V3 A1 1 0 0 1 12 2 z';
var wvuiIconPlay = 'M4.55 19A1 1 0 013 18.13V1.87A1 1 0 014.55 1l12.2 8.13a1 1 0 010 1.7z';
var wvuiIconPrevious = {
    path: 'M4 10l9 9 1.4-1.5L7 10l7.4-7.5L13 1z',
    shouldFlip: true
};
var wvuiIconPrinter = 'M5 1h10v4H5zM3 6a2 2 0 00-2 2v7h4v4h10v-4h4V8a2 2 0 00-2-2zm11 12H6v-6h8zm2-8a1 1 0 111-1 1 1 0 01-1 1z';
var wvuiIconPushPin = 'M13 8V2a2 2 0 002-2H5a2 2 0 002 2v6H6a2 2 0 00-2 2v1h5v5l1 4 1-4v-5h5v-1a2 2 0 00-2-2z';
var wvuiIconPuzzle = {
    path: 'M5.42 3a3 3 0 1 0 5.16 0H15V7.76a3 3 0 1 1 0 4.48V17H3a2 2 0 0,1-2-2H1V3Z',
    shouldFlip: true
};
var wvuiIconQuotes = {
    path: 'M7 6l1-2H6C3.79 4 2 6.79 2 9v7h7V9H5c0-3 2-3 2-3zm7 3c0-3 2-3 2-3l1-2h-2c-2.21 0-4 2.79-4 5v7h7V9z',
    shouldFlip: true
};
var wvuiIconRecentChangesLtr = 'M6 15h2v2H6zm0-6h6v2H6zm0-6h11v2H6z M4 4 A2 2 0 0 1 2 6 A2 2 0 0 1 0 4 A2 2 0 0 1 4 4 z M4 10 A2 2 0 0 1 2 12 A2 2 0 0 1 0 10 A2 2 0 0 1 4 10 z M4 16 A2 2 0 0 1 2 18 A2 2 0 0 1 0 16 A2 2 0 0 1 4 16 z M18.76 11.89l1.078-1.112a.556.556 0 000-.783l-1.855-1.833a.556.556 0 00-.783 0l-1.09 1.077 2.65 2.65zm-3.227-2.062L10 15.361V18h2.639l5.533-5.533z';
var wvuiIconRecentChangesRtl = 'M14 15h-4v2h4zm0-6h-2v2h2zm0-6H3v2h11z M20 4 A2 2 0 0 1 18 6 A2 2 0 0 1 16 4 A2 2 0 0 1 20 4 z M20 10 A2 2 0 0 1 18 12 A2 2 0 0 1 16 10 A2 2 0 0 1 20 10 z M20 16 A2 2 0 0 1 18 18 A2 2 0 0 1 16 16 A2 2 0 0 1 20 16 z M6.11 9.24l1.113-1.08a.556.556 0 01.783 0l1.833 1.857a.556.556 0 010 .782l-1.078 1.09-2.65-2.65zm2.062 3.226L2.64 18H0v-2.64l5.533-5.532z';
var wvuiIconRecentChanges = {
    rtl: wvuiIconRecentChangesRtl,
    default: wvuiIconRecentChangesLtr
};
var wvuiIconRedo = {
    path: 'M19 8.5L12 3v11zM12 7v3h-1c-4 0-7 2-7 6v1H1v-1c0-6 5-9 10-9z',
    shouldFlip: true
};
var wvuiIconReference = 'M15 10l-2.78-2.78L9.44 10V1H5a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V3a2 2 0 00-2-2z';
var wvuiIconReferenceExistingLtr = 'M7 0a2 2 0 00-2 2h9a2 2 0 012 2v12a2 2 0 002-2V2a2 2 0 00-2-2z M13 12l-2.8-2.8L7.4 12V3H4c-1.1 0-2 .9-2 2v13c0 1.1.9 2 2 2h9c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2v9z';
var wvuiIconReferenceExistingRtl = 'M13 0a2 2 0 012 2H6a2 2 0 00-2 2v12a2 2 0 01-2-2V2a2 2 0 012-2z M5 18c0 1.1.9 2 2 2h9c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2v9l-2.8-2.8-2.8 2.8V3H7C5.4 3 5 4.6 5 5v13z';
var wvuiIconReferenceExisting = {
    rtl: wvuiIconReferenceExistingRtl,
    default: wvuiIconReferenceExistingLtr
};
var wvuiIconReferences = {
    path: 'M0 3v16h5V3zm4 12H1v-1h3zm0-3H1v-1h3zm2-9v16h5V3zm4 12H7v-1h3zm0-3H7v-1h3zm1-8.5l4.1 15.4 4.8-1.3-4-15.3zm7 10.6l-2.9.8-.3-1 2.9-.8zm-.8-2.9l-2.9.8-.2-1 2.9-.8z',
    shouldFlip: true
};
var wvuiIconReload = 'M15.65 4.35A8 8 0 1017.4 13h-2.22a6 6 0 11-1-7.22L11 9h7V2z';
var wvuiIconRestore = 'M1.22 0L0 1.22l4 4V17a2 2 0 002 2h8a2 2 0 002-1.8l2.8 2.8 1.2-1.22zM17 4V2h-3.5l-1-1h-5l-1 1h-.84l2 2zM8.66 5H16v7.34z';
var wvuiIconRobot = 'M10.5 5h6.505C18.107 5 19 5.896 19 6.997V14h-7v2h5.005c1.102 0 1.995.888 1.995 2v2H1v-2c0-1.105.893-2 1.995-2H8v-2H1V6.997C1 5.894 1.893 5 2.995 5H9.5V2.915a1.5 1.5 0 111 0zm-4 6a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm7 0a1.5 1.5 0 100-3 1.5 1.5 0 000 3z';
var wvuiIconSearch = 'M12.2 13.6a7 7 0 111.4-1.4l5.4 5.4-1.4 1.4-5.4-5.4zM3 8a5 5 0 1010 0A5 5 0 103 8z';
var wvuiIconSearchCaseSensitive = 'M11.59 15.87h-1.52a.64.64 0 01-.42-.13.84.84 0 01-.24-.32l-1-2.67H3.18l-1 2.67a.8.8 0 01-.23.31.63.63 0 01-.42.14H0L4.8 3.76h2zm-3.72-4.54L6.2 6.91a12.12 12.12 0 01-.41-1.3q-.09.4-.2.73c-.07.22-.14.42-.2.58l-1.67 4.41zm5.58-2.84a4.91 4.91 0 013.46-1.35 3.41 3.41 0 011.32.24 2.62 2.62 0 011 .68 3 3 0 01.6 1 4.08 4.08 0 01.17 1.36v5.45h-.81a.78.78 0 01-.39-.08.61.61 0 01-.23-.32l-.18-.7a7.87 7.87 0 01-.65.53 4.12 4.12 0 01-.66.39 3.3 3.3 0 01-.73.24 4.3 4.3 0 01-.86.08 3.18 3.18 0 01-1-.14 2.12 2.12 0 01-.78-.43 2 2 0 01-.52-.72 2.48 2.48 0 01-.19-1 2 2 0 01.26-1 2.42 2.42 0 01.87-.85 5.66 5.66 0 011.6-.62 11.7 11.7 0 012.51-.25v-.57A2.06 2.06 0 0017.85 9a1.46 1.46 0 00-1.16-.45 2.53 2.53 0 00-.87.13 3.9 3.9 0 00-.62.32l-.46.28a.77.77 0 01-.43.13.52.52 0 01-.32-.1.81.81 0 01-.21-.24zm4.79 3.63a11.49 11.49 0 00-1.63.15 4.61 4.61 0 00-1.08.31 1.42 1.42 0 00-.59.45 1 1 0 00-.18.57 1.25 1.25 0 00.1.52.94.94 0 00.27.35 1.08 1.08 0 00.4.2 1.93 1.93 0 00.51.06 2.59 2.59 0 001.21-.27 3.79 3.79 0 001-.77z';
var wvuiIconSearchDiacritics = 'M5.31 7.87a7.27 7.27 0 015.13-2 5.06 5.06 0 011.95.35 3.91 3.91 0 011.43 1 4.44 4.44 0 01.88 1.54 6.05 6.05 0 01.3 2v8.04h-1.2a1.18 1.18 0 01-.58-.12.91.91 0 01-.34-.48l-.26-1a11.5 11.5 0 01-1 .78 6 6 0 01-1 .58 4.81 4.81 0 01-1.08.35 6.39 6.39 0 01-1.21.09 4.72 4.72 0 01-1.44-.21 3.14 3.14 0 01-1.15-.64A3 3 0 015 17.08a3.67 3.67 0 01-.28-1.49 2.89 2.89 0 01.39-1.43 3.58 3.58 0 011.29-1.25A8.37 8.37 0 018.76 12a17.22 17.22 0 013.64-.41v-.85a3 3 0 00-.59-2A2.15 2.15 0 0010.1 8a3.77 3.77 0 00-1.29.19 5.87 5.87 0 00-.91.42L7.21 9a1.15 1.15 0 01-.63.19.76.76 0 01-.47-.14 1.17 1.17 0 01-.32-.36zm6.2-5.8a.83.83 0 00.62-.23 1.11 1.11 0 00.24-.77H14a3.75 3.75 0 01-.17 1.18 2.74 2.74 0 01-.49.91 2.19 2.19 0 01-.76.59 2.27 2.27 0 01-1 .2 2 2 0 01-.82-.17 6.55 6.55 0 01-.72-.37L9.43 3a1.16 1.16 0 00-.56-.17.8.8 0 00-.62.24A1.12 1.12 0 008 3.9H6.37a3.67 3.67 0 01.18-1.18A2.81 2.81 0 017 1.8a2.25 2.25 0 01.76-.59 2.22 2.22 0 011-.21 2.06 2.06 0 01.83.17 6.42 6.42 0 01.72.37l.69.36a1.12 1.12 0 00.51.17zm.9 11.18a17 17 0 00-2.42.23 6.87 6.87 0 00-1.59.46 2.1 2.1 0 00-.88.67 1.45 1.45 0 00-.27.85 1.85 1.85 0 00.14.77 1.39 1.39 0 00.4.52 1.6 1.6 0 00.6.3 2.85 2.85 0 00.75.09 3.84 3.84 0 001.8-.39 5.61 5.61 0 001.46-1.14z';
var wvuiIconSearchRegularExpression = 'M1.62 10a13.63 13.63 0 00.45 3.51A13.39 13.39 0 003.4 16.7a.91.91 0 01.1.27.41.41 0 010 .21.38.38 0 01-.1.15l-.14.11-.83.5a14.89 14.89 0 01-1.11-2 13.62 13.62 0 01-.74-2 13.22 13.22 0 01-.42-2 16.4 16.4 0 010-4.14 13.22 13.22 0 01.42-2 13.84 13.84 0 01.74-2A14.94 14.94 0 012.4 2l.83.51.14.11a.4.4 0 01.1.15.41.41 0 010 .21.93.93 0 01-.1.27A13.6 13.6 0 001.62 10zM15.8 8.79l-.54.94-1.75-1-.34-.23a1.38 1.38 0 01-.27-.26A1.84 1.84 0 0113 9v2h-1V9a2.16 2.16 0 01.12-.76 1.82 1.82 0 01-.58.48l-1.74 1-.54-.94 1.73-1a2.25 2.25 0 01.75-.29 1.77 1.77 0 01-.75-.28L9.2 6.2l.54-.94 1.75 1 .33.24a1.64 1.64 0 01.27.27A2 2 0 0112 6V4h1v2a2.93 2.93 0 010 .4 1.36 1.36 0 01-.1.36 2.24 2.24 0 01.59-.49l1.74-1 .54.94-1.73 1-.36.18a1.29 1.29 0 01-.36.1 2.11 2.11 0 01.36.1 2 2 0 01.36.19zM18.37 10a13.65 13.65 0 00-.45-3.51 13.81 13.81 0 00-1.32-3.27.93.93 0 01-.1-.27.45.45 0 010-.21.36.36 0 01.1-.15l.14-.11.86-.48a15.54 15.54 0 011.1 2 13.79 13.79 0 01.74 2 13.18 13.18 0 01.42 2 16.16 16.16 0 01.14 2 16.21 16.21 0 01-.13 2 13.18 13.18 0 01-.42 2 13.57 13.57 0 01-.74 2 15.49 15.49 0 01-1.1 2l-.84-.5-.14-.11a.35.35 0 01-.1-.15.44.44 0 010-.21.91.91 0 01.1-.27 13.62 13.62 0 001.31-3.23 13.69 13.69 0 00.43-3.53z M8 13.5 A1.5 1.5 0 0 1 6.5 15 A1.5 1.5 0 0 1 5 13.5 A1.5 1.5 0 0 1 8 13.5 z';
var wvuiIconSettings = 'M11.5 0l.42 2.75a7.67 7.67 0 0 1 1.87.77L16 1.87 18.16 4 16.49 6.23a7.67 7.67 0 0 1 .76 1.85L20 8.5v3l-2.75.42a7.67 7.67 0 0 1-.77 1.87L18.13 16 16 18.16l-2.24-1.67a7.67 7.67 0 0 1-1.85.76L11.5 20h-3l-.42-2.75a7.45 7.45 0 0 1-1.86-.77L4 18.13 1.87 16l1.65-2.23a7 7 0 0 1-.77-1.85L0 11.5v-3l2.75-.42a7.45 7.45 0 0 1 .77-1.86L1.87 4 4 1.87 6.23 3.52a7.17 7.17 0 0 1 1.85-.77L8.5 0ZM10 6.5A3.5 3.5 0 1 0 13.5 10 3.5 3.5 0 0 0 10 6.5Z';
var wvuiIconShare = 'M12 6V2l7 7-7 7v-4c-5 0-8.5 1.5-11 5l.8-3 .2-.4A12 12 0 0112 6z';
var wvuiIconSignature = {
    path: 'M0 18h20v1H0zm-.003-6.155l1.06-1.06 4.363 4.362-1.06 1.06z M.004 15.147l4.363-4.363 1.06 1.061-4.362 4.363zM17 5c0 9-11 9-11 9v-1.5s8 .5 9.5-6.5C16 4 15 2.5 14 2.5S11 4 10.75 10c-.08 2 .75 4.5 3.25 4.5 1.5 0 2-1 3.5-1a2.07 2.07 0 012.25 2.5h-1.5s.13-1-.5-1C16 15 16 16 14 16c0 0-4.75 0-4.75-6S12 1 14 1c.5 0 3 0 3 4z',
    shouldFlip: true
};
var wvuiIconSmaller = 'M12 16h-1.05a.44.44 0 01-.29-.09.58.58 0 01-.17-.22l-.7-1.84H6.2l-.7 1.84a.56.56 0 01-.16.21.43.43 0 01-.29.1H4l3.31-8.35h1.38zm-2.57-3.13L8.28 9.82a8.5 8.5 0 01-.28-.9q-.06.27-.14.5l-.14.4-1.15 3zM15 6l3-4h-6z';
var wvuiIconSmallerText = 'M15.75 18h-1.51a.64.64 0 01-.42-.13.83.83 0 01-.24-.32l-1-2.65H7.41l-1 2.65a.79.79 0 01-.23.31.62.62 0 01-.42.14H4.25L9 6h2zm-3.69-4.5L10.4 9.12a12.13 12.13 0 01-.4-1.3q-.09.39-.2.72t-.2.58L7.95 13.5z';
var wvuiIconSpecialCharacter = 'M19 15.9v1.29a.77.77 0 01-.23.58.86.86 0 01-.63.23h-6.76v-2.87a4.41 4.41 0 001.74-.71 5.51 5.51 0 001.4-1.42 6.92 6.92 0 00.93-1.91 7.47 7.47 0 00.34-2.28 6.15 6.15 0 00-.47-2.48 5.1 5.1 0 00-1.26-1.78 5.2 5.2 0 00-1.85-1.07 7.15 7.15 0 00-4.43 0 5.08 5.08 0 00-3.11 2.87 6.08 6.08 0 00-.47 2.48 7.47 7.47 0 00.34 2.28A6.81 6.81 0 005.47 13a5.59 5.59 0 001.41 1.39 4.41 4.41 0 001.74.71V18H1.86a.86.86 0 01-.63-.23.77.77 0 01-.23-.58V15.9h4.76l1 .12a6.94 6.94 0 01-2-1.05 7.39 7.39 0 01-1.58-1.63 7.75 7.75 0 01-1-2.1 8 8 0 01-.38-2.47 7.61 7.61 0 01.65-3.17A7.48 7.48 0 014.1 3.17a8.14 8.14 0 012.65-1.6A9.19 9.19 0 0110 1a9.18 9.18 0 013.25.57 8.14 8.14 0 012.65 1.6 7.48 7.48 0 011.78 2.47 7.61 7.61 0 01.65 3.17 8 8 0 01-.33 2.48 7.74 7.74 0 01-1 2.1A7.37 7.37 0 0115.33 15a7 7 0 01-2 1.05l1-.12h1z';
var wvuiIconSpecialPages = {
    path: 'M7 0a2 2 0 00-2 2h9a2 2 0 012 2v12a2 2 0 002-2V2a2 2 0 00-2-2z M13 20H4a2 2 0 01-2-2V5a2 2 0 012-2h9a2 2 0 012 2v13a2 2 0 01-2 2zm-6.5-3.5l.41-1.09L8 15l-1.09-.41-.41-1.09-.41 1.09L5 15l1.09.41.41 1.09zm2.982-.949l.952-2.561 2.53-.964-2.53-.964L9.482 8.5l-.952 2.562-2.53.964 2.53.964.952 2.561zM6 10.5l.547-1.453L8 8.5l-1.453-.547L6 6.5l-.547 1.453L4 8.5l1.453.547L6 10.5z',
    shouldFlip: true
};
var wvuiIconSpeechBubble = {
    path: 'M18 0H2A2 2 0 0 0 0 2V20l4-4H18a2 2 0 0 0 2-2V2A2 2 0 0 0 18 0Z',
    shouldFlip: true
};
var wvuiIconSpeechBubbleAdd = {
    path: 'M3 1a2 2 0 00-2 2v16l4-4h12a2 2 0 002-2V3a2 2 0 00-2-2zm12 8h-4v4H9V9H5V7h4V3h2v4h4z',
    shouldFlip: true
};
var wvuiIconSpeechBubbles = {
    path: 'M18 4a2 2 0 0 1 2 2h0V20l-4-4H6a2 2 0 0 1-2-2H4V13H15a2 2 0 0 0 2-2h0V4ZM14 0a2 2 0 0 1 2 2h0v8a2 2 0 0 1-2 2H4L0 16V2A2 2 0 0 1 2 0H14Z',
    shouldFlip: true
};
var wvuiIconStar = 'M20 7h-7L10 .5 7 7H0l5.46 5.47-1.64 7 6.18-3.7 6.18 3.73-1.63-7zm-10 6.9l-3.76 2.27 1-4.28L3.5 8.5h4.61L10 4.6l1.9 3.9h4.6l-3.73 3.4 1 4.28z';
var wvuiIconStop = 'M3 2 H17 A1 1 0 0 1 18 3 V17 A1 1 0 0 1 17 18 H3 A1 1 0 0 1 2 17 V3 A1 1 0 0 1 3 2 z';
var wvuiIconStopHand = 'M13.2 11.8c0-.2-.4-.6-.7-.8V1.9c0-.5-.5-.9-1-.9s-.9.4-.9.9v8c-.3 0-.6-.1-.9-.1V1c0-.5-.4-.9-.9-.9s-.9.4-.9.9v8.8H7V1.7c0-.5-.4-.9-.9-.9s-.9.4-.9.9V10c-.3 0-.7.1-.9.1V4.7c0-.5-.3-.9-.7-.9-.4 0-.7.4-.7.9v7.5c0 8.9 5.8 7.7 5.8 7.7 5 0 6.2-6.2 6.2-6.2.2-1.1 2.4-4.2 2.4-4.2-1.9-2.3-4.1 2.3-4.1 2.3';
var wvuiIconStrikethroughA = 'M5.928 8H8.59L10 3.979 11.41 8h2.662l-2.357-6h-3.43zM1 10v2h3.357L2 18h3.06l1.622-4.566h6.636L14.94 18H18l-2.357-6H19v-2z';
var wvuiIconStrikethroughS = 'M5.6 9h5.89l-.39-.16-1.43-.47a7.06 7.06 0 01-1.25-.52 2.86 2.86 0 01-.88-.72 1.64 1.64 0 01-.34-1.06 2.32 2.32 0 01.18-.92 2 2 0 01.54-.73 2.53 2.53 0 01.88-.48 3.89 3.89 0 011.2-.18 3.9 3.9 0 011.3.24 5.83 5.83 0 01.94.41q.4.22.67.41a.9.9 0 00.49.18.58.58 0 00.35-.1 1 1 0 00.25-.34l.66-1.29A6 6 0 0012.61 2 7.37 7.37 0 0010 1.54a6.11 6.11 0 00-2.26.39A5 5 0 006.07 3a4.48 4.48 0 00-1.38 3.21A4.66 4.66 0 005 8.09a4 4 0 00.6.91zM19 11H1v2h11.4a2.27 2.27 0 01.08.58 2.5 2.5 0 01-.8 2 3.29 3.29 0 01-2.26.71A4.31 4.31 0 017.87 16a6 6 0 01-1.1-.54q-.46-.29-.78-.54a1 1 0 00-.59-.25.7.7 0 00-.36.09.76.76 0 00-.27.25L4 16.32a6.43 6.43 0 001.07.9 7.18 7.18 0 001.28.69 8 8 0 001.44.44 7.67 7.67 0 001.55.16 6.53 6.53 0 002.4-.41 5.11 5.11 0 001.77-1.13 4.88 4.88 0 001.1-1.69 5.62 5.62 0 00.39-2.09V13h4z';
var wvuiIconStrikethroughY = 'M7.338 8h2.29L5.634 2h-2.3zm3.06 0h2.264l4.004-6h-2.299zM1 10v2h7.926v6h2.148v-6H19v-2z';
var wvuiIconStrikethrough = {
    langCodeMap: {
        en: wvuiIconStrikethroughS,
        fi: wvuiIconStrikethroughY
    },
    default: wvuiIconStrikethroughA
};
var wvuiIconSubscript = {
    path: 'M13.68 16h-2.42a.67.67 0 01-.46-.15 1.33 1.33 0 01-.28-.34l-2.77-4.44a2.65 2.65 0 01-.28.69L5 15.51a2.22 2.22 0 01-.29.34.58.58 0 01-.42.15H2l4.15-6.19L2.17 4h2.42a.81.81 0 01.41.09.8.8 0 01.24.26L8 8.59a2.71 2.71 0 01.33-.74L10.6 4.4a.69.69 0 01.6-.4h2.32l-4 5.71zm3.82-4h.5v-1h-.5a1.49 1.49 0 00-1 .39 1.49 1.49 0 00-1-.39H15v1h.5a.5.5 0 01.5.5v6a.5.5 0 01-.5.5H15v1h.5a1.49 1.49 0 001-.39 1.49 1.49 0 001 .39h.5v-1h-.5a.5.5 0 01-.5-.5v-6a.5.5 0 01.5-.5z',
    shouldFlip: true
};
var wvuiIconSubtract = 'M4 9h12v2H4z';
var wvuiIconSuperscript = {
    path: 'M18 1V0h-.5a1.49 1.49 0 00-1 .39 1.49 1.49 0 00-1-.39H15v1h.5a.5.5 0 01.5.5v6a.5.5 0 01-.5.5H15v1h.5a1.49 1.49 0 001-.39 1.49 1.49 0 001 .39h.5V8h-.5a.5.5 0 01-.5-.5v-6a.5.5 0 01.5-.5zm-4.32 15h-2.42a.67.67 0 01-.46-.15 1.33 1.33 0 01-.28-.34l-2.77-4.44a2.65 2.65 0 01-.28.69L5 15.51a2.22 2.22 0 01-.29.34.58.58 0 01-.42.15H2l4.15-6.19L2.17 4h2.42a.81.81 0 01.41.09.8.8 0 01.24.26L8 8.59a2.71 2.71 0 01.33-.74L10.6 4.4a.69.69 0 01.6-.4h2.32l-4 5.71z',
    shouldFlip: true
};
var wvuiIconTable = 'M2 2a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V4a2 2 0 00-2-2zm0 4h7v4H2zm0 10v-4h7v4zm16 0h-7v-4h7zm0-6h-7V6h7z';
var wvuiIconTableAddColumnAfter = {
    path: 'M0 3v14h8v1h12V2H8v1zm10 6h3V6h2v3h3v2h-3v3h-2v-3h-3zM6 5h2v10H6zM2 5h2v10H2z',
    shouldFlip: true
};
var wvuiIconTableAddColumnBefore = {
    path: 'M18 3h-6V2H0v16h12v-1h8V3zm-8 8H7v3H5v-3H2V9h3V6h2v3h3zm4 4h-2V5h2zm4 0h-2V5h2z',
    shouldFlip: true
};
var wvuiIconTableAddRowAfter = 'M3 0v8H2v12h16V8h-1V0zm8 10v3h3v2h-3v3H9v-3H6v-2h3v-3zm4-4v2H5V6zm0-4v2H5V2z';
var wvuiIconTableAddRowBefore = 'M17 20v-8h1V0H2v12h1v8zM9 10V7H6V5h3V2h2v3h3v2h-3v3zm-4 4v-2h10v2zm0 4v-2h10v2z';
var wvuiIconTableCaption = 'M2 8a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2v-6a2 2 0 00-2-2zm0 2h7v2H2zm0 6v-2h7v2zm16 0h-7v-2h7zm0-4h-7v-2h7zM2 2h16v4H2z';
var wvuiIconTableMergeCells = 'M2 13.11v3H8v2H0v-5Zm18 0v5H12v-2h6v-3Zm-16-7 5 4-5 4v-3H0v-2H4Zm12 0v3h4v2H16v3l-5-4Zm-8-4v2H2v3H0v-5Zm12 0v5H18v-3H12v-2Z';
var wvuiIconTableMoveColumnAfter = {
    path: 'M16 10l-5-4v3H6v2h5v3z M0 2h20v16H0zm5 6v4h5v4h8V4h-8v4z',
    shouldFlip: true
};
var wvuiIconTableMoveColumnBefore = {
    path: 'M4 10l5-4v3h5v2H9v3z M0 2v16h20V2zm2 2h8v4h5v4h-5v4H2z',
    shouldFlip: true
};
var wvuiIconTableMoveRowAfter = 'M10 16l-4-5h3V6h2v5h3z M2 0v20h16V0zm2 10h4V5h4v5h4v8H4z';
var wvuiIconTableMoveRowBefore = 'M9 9H6l4-5 4 5h-3v5H9z M2 0h16v20H2zm2 2v8h4v5h4v-5h4V2z';
var wvuiIconTag = {
    path: 'M9 1.28A1 1 0 008.35 1H2a1 1 0 00-1 1v6.35a1 1 0 00.28.65L11 18.72a1 1 0 001.37 0l6.38-6.38a1 1 0 00-.03-1.34zM5 7a2 2 0 112-2 2 2 0 01-2 2z',
    shouldFlip: true
};
var wvuiIconTemplateAdd = {
    path: 'M16 5V1h-2v4h-4v2h4v4h2V7h4V5z M0 17V5h8v2H2v8h12v-2h2v4z',
    shouldFlip: true
};
var wvuiIconTextDirLTR = 'M19 10l-6-5v4H6v2h7v4zM6 2V1H4.5a1.49 1.49 0 00-1 .39 1.49 1.49 0 00-1-.39H1v1h1.5a.5.5 0 01.5.5v15a.5.5 0 01-.5.5H1v1h1.5a1.49 1.49 0 001-.39 1.49 1.49 0 001 .39H6v-1H4.5a.5.5 0 01-.5-.5v-15a.5.5 0 01.5-.5z';
var wvuiIconTextDirRTL = 'M1 10l6-5v4h7v2H7v4zm13-8V1h1.5a1.49 1.49 0 011 .39 1.49 1.49 0 011-.39H19v1h-1.5a.5.5 0 00-.5.5v15a.5.5 0 00.5.5H19v1h-1.5a1.49 1.49 0 01-1-.39 1.49 1.49 0 01-1 .39H14v-1h1.5a.5.5 0 00.5-.5v-15a.5.5 0 00-.5-.5z';
var wvuiIconTextFlow = {
    path: 'M1 3h18v2H1zm0 4h14v2H1zm0 4h10v2H1zm0 4h18v2H1z',
    shouldFlip: true
};
var wvuiIconTextStyle = 'M2 17h16v2H2zm9.34-15h3.31l2 14h-3.19l-.29-2.88H8L6.43 16H3.37zm-2 8.71h3.55l-.61-5.51z';
var wvuiIconTextSummary = {
    path: 'M1 7h18v2H1zm0 4h14v2H1z',
    shouldFlip: true
};
var wvuiIconTrash = 'M17 2h-3.5l-1-1h-5l-1 1H3v2h14zM4 17a2 2 0 002 2h8a2 2 0 002-2V5H4z';
var wvuiIconTray = 'M3 1a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V3a2 2 0 00-2-2zm14 12h-4l-1 2H8l-1-2H3V3h14z';
var wvuiIconUnBlock = 'M1.22 0L0 1.22l3.06 3.06a9 9 0 0012.66 12.66L18.78 20 20 18.78zM5 11V9h2.78l2 2zm5-10a9 9 0 00-4.26 1.08L12.66 9H15v2h-.34l3.26 3.26A9 9 0 0010 1z';
var wvuiIconUnFlag = {
    rtl: 'M0 1.2l4.27 4.27L0 7l11.84 6.04.16.16V20h2v-4.8l4.74 4.74 1.198-1.198L1.198.002zM14 2L7.809 4.209 14 10.399z',
    default: 'M12.14 8.48L17 6 5.58 1.92zM1.22 0L0 1.22l3 3V19h2v-6.87l3.91-2L18.78 20 20 18.78z'
};
var wvuiIconUnLink = 'M4.83 5A4.83 4.83 0 000 9.83v.34A4.83 4.83 0 004.83 15h2.91a4.88 4.88 0 01-1.55-2H5c-4 0-4-6 0-6h3c.075.001.15.005.225.012L6.215 5zm7.43 0a4.88 4.88 0 011.55 2H15c3.179.003 4.17 4.3 1.314 5.695l1.508 1.508A4.83 4.83 0 0020 10.17v-.34A4.83 4.83 0 0015.17 5zm-3.612.03l4.329 4.327A4.83 4.83 0 008.648 5.03zM7.227 8.411C7.17 8.595 7.08 9 7.08 9c-.045.273-.08.584-.08.83v.34A4.83 4.83 0 0011.83 15h3.34c.316 0 .631-.032.941-.094L14.205 13H12c-2.067-.006-3.51-2.051-2.82-4zm3.755 1.36A3 3 0 0110.82 11h1.389z M1.22 0L0 1.22 18.8 20l1.2-1.22z';
var wvuiIconUnLock = 'M15 8V5s0-5-5-5a4.63 4.63 0 00-4.88 4h2C7.31 2.93 8 2 10 2c3 0 3 2 3 3.5V8H3.93A1.93 1.93 0 002 9.93v8.15A1.93 1.93 0 003.93 20h12.14A1.93 1.93 0 0018 18.07V9.93A1.93 1.93 0 0016.07 8zm-5 8a2 2 0 112-2 2 2 0 01-2 2z';
var wvuiIconUnStar = 'M20 7h-7L10 .5 7 7H0l5.46 5.47-1.64 7 6.18-3.7 6.18 3.73-1.63-7z';
var wvuiIconUnderlineA = 'M3 17h14v2H3zm4.704-6.726L10 3.731l2.296 6.543zM14.322 16H17L11.5 2h-3L3 16h2.678l1.418-3.995h5.808z';
var wvuiIconUnderlineU = 'M3 17h14v2H3zm2.61-2.71a5.46 5.46 0 001.89 1.26A6.56 6.56 0 0010 16a6.56 6.56 0 002.5-.45 5.37 5.37 0 003.08-3.17A6.78 6.78 0 0016 10V2h-2.2v8a5 5 0 01-.26 1.66 3.73 3.73 0 01-.75 1.29 3.33 3.33 0 01-1.19.84 4.06 4.06 0 01-1.6.3 4.06 4.06 0 01-1.6-.3 3.33 3.33 0 01-1.19-.84 3.65 3.65 0 01-.74-1.3A5.18 5.18 0 016.21 10V2H4v8a6.78 6.78 0 00.42 2.41 5.49 5.49 0 001.19 1.88z';
var wvuiIconUnderline = {
    langCodeMap: {
        en: wvuiIconUnderlineU,
        de: wvuiIconUnderlineU
    },
    default: wvuiIconUnderlineA
};
var wvuiIconUndo = {
    path: 'M1 8.5L8 14v-4h1c4 0 7 2 7 6v1h3v-1c0-6-5-9-10-9H8V3z',
    shouldFlip: true
};
var wvuiIconUpTriangle = 'M10 5l8 10H2z';
var wvuiIconUpload = 'M17 12v5H3v-5H1v5a2 2 0 002 2h14a2 2 0 002-2v-5z M10 1L5 7h4v8h2V7h4z';
var wvuiIconUserActive = 'M10 12.5c-5.92 0-9 3.5-9 5.5v1h18v-1c0-2-3.08-5.5-9-5.5z M15 6 A5 5 0 0 1 10 11 A5 5 0 0 1 5 6 A5 5 0 0 1 15 6 z';
var wvuiIconUserAdd = {
    path: 'M14 0v4h-4v2h4v4h2V6h4V4h-4V0h-2zM8.5 7C6.57 7 5 8.57 5 10.5S6.57 14 8.5 14s3.5-1.57 3.5-3.5S10.43 7 8.5 7zM8 15c-4.6 0-7 2.69-7 4.23V20h14v-.77C15 17.69 12.6 15 8 15z',
    shouldFlip: true
};
var wvuiIconUserAnonymous = 'M15 2H5L4 8h12zM0 10s2 1 10 1 10-1 10-1l-4-2H4zm8 4h4v1H8z M9 15 A3 3 0 0 1 6 18 A3 3 0 0 1 3 15 A3 3 0 0 1 9 15 z M17 15 A3 3 0 0 1 14 18 A3 3 0 0 1 11 15 A3 3 0 0 1 17 15 z';
var wvuiIconUserAvatar = 'M10 11c-5.92 0-8 3-8 5v3h16v-3c0-2-2.08-5-8-5z M14.5 5.5 A4.5 4.5 0 0 1 10 10 A4.5 4.5 0 0 1 5.5 5.5 A4.5 4.5 0 0 1 14.5 5.5 z';
var wvuiIconUserAvatarOutline = 'M10 8c1.7 0 3.06-1.35 3.06-3S11.7 2 10 2 6.94 3.35 6.94 5 8.3 8 10 8zm0 2c-2.8 0-5.06-2.24-5.06-5S7.2 0 10 0s5.06 2.24 5.06 5-2.26 5-5.06 5zm-7 8h14v-1.33c0-1.75-2.31-3.56-7-3.56s-7 1.81-7 3.56V18zm7-6.89c6.66 0 9 3.33 9 5.56V20H1v-3.33c0-2.23 2.34-5.56 9-5.56z';
var wvuiIconUserContributions = {
    path: 'M6 15h3v2H6zm0-6h5v2H6zm0-6h11v2H6z M4 4 A2 2 0 0 1 2 6 A2 2 0 0 1 0 4 A2 2 0 0 1 4 4 z M4 10 A2 2 0 0 1 2 12 A2 2 0 0 1 0 10 A2 2 0 0 1 4 10 z M4 16 A2 2 0 0 1 2 18 A2 2 0 0 1 0 16 A2 2 0 0 1 4 16 z M15.5 13.556c-3.33 0-4.5 1.666-4.5 2.777V18h9v-1.667c0-1.11-1.17-2.777-4.5-2.777z M18 10.5 A2.5 2.5 0 0 1 15.5 13 A2.5 2.5 0 0 1 13 10.5 A2.5 2.5 0 0 1 18 10.5 z',
    shouldFlip: true
};
var wvuiIconUserGroup = {
    path: 'M14 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zM6 3c1.66 0 3 1.34 3 3S7.66 9 6 9 3 7.66 3 6s1.34-3 3-3zm8 7c3.31 0 6 1.79 6 4v2h-6v-2c0-1.48-1.21-2.77-3-3.46.88-.35 1.91-.54 3-.54zm-8 0c3.31 0 6 1.79 6 4v2H0v-2c0-2.21 2.69-4 6-4z',
    shouldFlip: true
};
var wvuiIconUserTalk = {
    path: 'M18 0H2a2 2 0 00-2 2v18l4-4h14a2 2 0 002-2V2a2 2 0 00-2-2zm-4 4a1.5 1.5 0 11-1.5 1.5A1.5 1.5 0 0114 4zM6 4a1.5 1.5 0 11-1.5 1.5A1.5 1.5 0 016 4zm4 8c-2.61 0-4.83-.67-5.65-3h11.3c-.82 2.33-3.04 3-5.65 3z',
    shouldFlip: true
};
var wvuiIconViewCompact = 'M2 2h4v4H2zm12 0h4v4h-4zM8 2h4v4H8zM2 14h4v4H2zm12 0h4v4h-4zm-6 0h4v4H8zM2 8h4v4H2zm12 0h4v4h-4zM8 8h4v4H8z';
var wvuiIconViewDetails = {
    path: 'M8 6h9v2H8zm0-3h11v2H8zM1 3h6v6H1zm7 11h9v2H8zm0-3h11v2H8zm-7 0h6v6H1z',
    shouldFlip: true
};
var wvuiIconVisionSimulator = 'M17.5 11.83a.79.79 0 01-.83.83h-3.34A1.49 1.49 0 0111.67 11V9.33a.79.79 0 01.83-.83h4.17a.79.79 0 01.83.83zM8.33 11a1.49 1.49 0 01-1.67 1.67H3.33a.79.79 0 01-.83-.83V9.33a.79.79 0 01.83-.83H7.5a.79.79 0 01.83.83zM0 6.2v6.28a.2.2 0 00.2.2h1.72a1.61 1.61 0 001.42.83h3.33A2.46 2.46 0 009.13 12a.19.19 0 01.18-.13h1.39a.19.19 0 01.18.13 2.46 2.46 0 002.46 1.53h3.33c.55 0 1.1 0 1.37-.7a.2.2 0 01.18-.13h1.58a.2.2 0 00.2-.2V6.2a.2.2 0 00-.2-.2H.2a.2.2 0 00-.2.2z';
var wvuiIconWikiText = 'M1 3v14h3v-2H3V5h1V3zm4 0v14h4v-2H7V5h2V3zm11 0v2h1v10h-1v2h3V3zm-5 0v2h2v10h-2v2h4V3z';
var wvuiIconWindow = 'M2 2a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V4a2 2 0 00-2-2zm0 2h16v12H2z M4 6h12v2H4z';
var wvuiIconZoomIn = 'M19 17l-5.15-5.15a7 7 0 10-2 2L17 19zM3.5 8A4.5 4.5 0 118 12.5 4.49 4.49 0 013.5 8z M11 7.25H8.75V5h-1.5v2.25H5v1.5h2.25V11h1.5V8.75H11z';
var wvuiIconZoomOut = 'M19 17l-5.15-5.15a7 7 0 10-2 2L17 19zM3.5 8A4.5 4.5 0 118 12.5 4.49 4.49 0 013.5 8z M5 7.25h6v1.5H5z';

// CONCATENATED MODULE: ./node_modules/ts-loader??ref--0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/dropdown/Dropdown.vue?vue&type=script&lang=ts&







external_vue_default.a.use(composition_api_default.a);
/**
 * Dropdown menu, like HTML `<select>`. Displays the selected item (or a default label, if no item
 * is selected), and expands on click to show all available items.
 *
 * Set the available items through the items prop, and get/set the ID of the selected item through
 * v-model. The v-model value will be the .id property of the selected item, or null if no item
 * is selected.
 *
 * How items are displayed can be customized through named slots. The menuItem slot is used for the
 * display of items in the menu, and the selectedItem slot is used for the display of the currently
 * selected item. Note that the item passed to the selectedItem slot will be null if no item is
 * selected.
 *
 * @example
 *     <wvui-dropdown v-model="number" :items="[{id: 1, label: 'One'}, {id: 2, label: 'Two'}]" />
 *
 * @example
 *     <wvui-dropdown v-model="..." :items="...">
 *         <template #menuItem="{ item }">
 *             {{ item.label }} (id: {{item.id}})
 *         </template>
 *         <template #selectedItem="{ item, defaultLabel }">
 *             <template v-if="item !== null">
 *                 {{ item.label }} (id: {{item.id }})
 *             </template>
 *             <template v-else>
 *                 {{ defaultLabel }}
 *             </template>
 *         </template>
 *     </wvui-dropdown>
 */
/* harmony default export */ var Dropdownvue_type_script_lang_ts_ = (Object(composition_api_["defineComponent"])({
    name: 'WvuiDropdown',
    components: { WvuiIcon: Icon, WvuiOptionsMenu: OptionsMenu },
    model: {
        prop: 'selectedItemId',
        event: 'change'
    },
    props: {
        /**
         * Available items in the menu. The items' IDs must be unique within each dropdown.
         */
        items: {
            type: Array,
            required: true
            // No validation here; if the value is invalid, the OptionsMenu validator will catch it
        },
        /**
         * The ID of the selected item, or null if no item is selected. This is the v-model value.
         */
        selectedItemId: {
            type: String,
            default: null
        },
        /**
         * Label to display when no item is selected.
         */
        defaultLabel: {
            type: String,
            default: ''
        },
        /**
         * Whether the dropdown is disabled. Disabled dropdowns can't be interacted with.
         */
        disabled: {
            type: Boolean,
            default: false
        }
    },
    setup: function () {
        var prefixId = useGeneratedId('dropdown').prefixId;
        return {
            prefixId: prefixId
        };
    },
    data: function () { return ({
        wvuiIconExpand: wvuiIconExpand,
        // Whether the menu is visible
        showMenu: false
    }); },
    computed: {
        wrappedModel: {
            get: function () {
                return this.selectedItemId;
            },
            set: function (newValue) {
                /**
                 * Emitted when the selected item changes
                 *
                 * @param {string|null} newValue ID of the selected item, or null if no selection
                 */
                this.$emit('change', newValue);
            }
        },
        rootClasses: function () {
            return {
                'wvui-dropdown--disabled': this.disabled,
                'wvui-dropdown--open': this.showMenu,
                'wvui-dropdown--value-selected': this.selectedItemId !== null,
                'wvui-dropdown--no-selections': this.selectedItemId === null
            };
        },
        menuId: function () {
            return this.prefixId('menu');
        },
        itemsById: function () {
            var result = {};
            for (var _i = 0, _a = this.items; _i < _a.length; _i++) {
                var item = _a[_i];
                result[item.id] = item;
            }
            return result;
        },
        selectedItem: function () {
            return this.selectedItemId !== null && this.itemsById[this.selectedItemId] || null;
        }
    },
    methods: {
        onKeyNavigation: function (event) {
            if (this.disabled) {
                return;
            }
            var wasShown = this.showMenu;
            if (!this.showMenu) {
                this.showMenu = true;
                if (event.key === 'Enter') {
                    // This Enter keypress means we wanted to open the menu, not select anything, so
                    // don't delegate this keypress to the menu. If we do, it could trigger a
                    // selection and immediately close the menu again.
                    return;
                }
            }
            // Delegate to the menu component
            this.$refs.menu
                .handleKeyboardEvent(event);
            if (event.key === 'Enter' && wasShown) {
                // Make sure the menu is hidden. handleKeyboardEvent() may have emitted a
                // select event, in which case onSelect() will already have hidden the menu.
                // But if this enter keypress didn't cause anything to be selected, we
                // still want to hide the menu.
                this.showMenu = false;
            }
        },
        onClick: function () {
            if (this.disabled) {
                return;
            }
            this.showMenu = !this.showMenu;
            this.$refs.handle.focus();
        }
    }
}));

// CONCATENATED MODULE: ./src/components/dropdown/Dropdown.vue?vue&type=script&lang=ts&
 /* harmony default export */ var dropdown_Dropdownvue_type_script_lang_ts_ = (Dropdownvue_type_script_lang_ts_); 
// EXTERNAL MODULE: ./src/components/dropdown/Dropdown.vue?vue&type=style&index=0&lang=less&
var Dropdownvue_type_style_index_0_lang_less_ = __webpack_require__(18);

// CONCATENATED MODULE: ./src/components/dropdown/Dropdown.vue






/* normalize component */

var Dropdown_component = normalizeComponent(
  dropdown_Dropdownvue_type_script_lang_ts_,
  Dropdownvue_type_template_id_119ed76a_render,
  Dropdownvue_type_template_id_119ed76a_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var Dropdown = (Dropdown_component.exports);
// CONCATENATED MODULE: ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./src/components/input/Input.vue?vue&type=template&id=726d0109&
var Inputvue_type_template_id_726d0109_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"wvui-input",class:_vm.rootClasses},[_c('input',_vm._b({ref:"input",staticClass:"wvui-input__input",attrs:{"disabled":_vm.disabled,"type":_vm.type},domProps:{"value":_vm.computedValue},on:{"input":_vm.onInput,"change":_vm.onChange,"focus":_vm.onFocus,"blur":_vm.onBlur}},'input',_vm.$attrs,false)),_vm._v(" "),(_vm.startIcon)?_c('wvui-icon',{staticClass:"wvui-input__start-icon",attrs:{"icon":_vm.startIcon}}):_vm._e(),_vm._v(" "),(_vm.isClearable || _vm.endIcon)?_c('wvui-icon',{staticClass:"wvui-input__end-icon",attrs:{"icon":_vm.endIcon || _vm.clearIcon},on:{"click":_vm.onEndIconClick}}):_vm._e()],1)}
var Inputvue_type_template_id_726d0109_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/input/Input.vue?vue&type=template&id=726d0109&

// CONCATENATED MODULE: ./src/components/input/InputType.ts
/**
 * Defines types for text inputs.
 */
var InputType;
(function (InputType) {
    InputType["Text"] = "text";
    InputType["Search"] = "search";
})(InputType || (InputType = {}));
/**
 * @param val
 * @return whether an input is a InputType.
 */
function isInputType(val) {
    return Object.keys(InputType).some(function (key) { return InputType[key] === val; });
}

// CONCATENATED MODULE: ./node_modules/ts-loader??ref--0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/input/Input.vue?vue&type=script&lang=ts&





/* harmony default export */ var Inputvue_type_script_lang_ts_ = (external_vue_default.a.extend({
    name: 'WvuiInput',
    components: { WvuiIcon: Icon },
    /**
     * All attributes set on the components such as disabled and type are passed to the underlying
     * input.
     */
    inheritAttrs: false,
    props: {
        value: {
            type: [String, Number],
            default: ''
        },
        type: {
            type: String,
            default: InputType.Text,
            // use arrow function for type inference of property
            validator: function (value) { return isInputType(value); }
        },
        disabled: {
            type: Boolean,
            default: false
        },
        /** An icon at the start of the input element. Similar to a ::before pseudo-element. */
        startIcon: {
            type: [String, Object],
            default: undefined
        },
        /** An icon at the end of the input element. Similar to an ::after pseudo-element. */
        endIcon: {
            type: [String, Object],
            default: undefined
        },
        /**
         * Override end icon with a clear button at the end of the input element. When clear is
         * pressed the input's contents is deleted. The elements automatically hides and appears
         * based on input state.
         */
        clearable: {
            type: Boolean,
            default: false
        }
    },
    data: function () {
        return {
            newValue: this.value,
            clearIcon: wvuiIconClear
        };
    },
    computed: {
        isClearable: function () {
            return this.clearable &&
                !!this.computedValue &&
                !this.disabled;
        },
        rootClasses: function () {
            return {
                'wvui-input--has-start-icon': !!this.startIcon,
                'wvui-input--has-end-icon': !!this.endIcon || this.clearable,
                'wvui-input--clearable': this.clearable
            };
        },
        computedValue: {
            get: function () {
                return this.newValue;
            },
            set: function (value) {
                this.newValue = value;
                this.$emit('input', value);
            }
        }
    },
    watch: {
        // Update input value on v-model change
        value: function (value) {
            this.newValue = value;
        }
    },
    methods: {
        onInput: function (event) {
            var target = event.target;
            var value = target.value;
            this.setCurrentValue(value);
        },
        onChange: function (event) {
            this.$emit('change', event);
        },
        onFocus: function (event) {
            this.$emit('focus', event);
        },
        onBlur: function (event) {
            this.$emit('blur', event);
        },
        onEndIconClick: function () {
            if (this.clearable) {
                this.setCurrentValue('');
                this.$emit('input', '');
            }
        },
        setCurrentValue: function (value) {
            this.computedValue = value;
        }
    }
}));

// CONCATENATED MODULE: ./src/components/input/Input.vue?vue&type=script&lang=ts&
 /* harmony default export */ var input_Inputvue_type_script_lang_ts_ = (Inputvue_type_script_lang_ts_); 
// EXTERNAL MODULE: ./src/components/input/Input.vue?vue&type=style&index=0&lang=less&
var Inputvue_type_style_index_0_lang_less_ = __webpack_require__(19);

// CONCATENATED MODULE: ./src/components/input/Input.vue






/* normalize component */

var Input_component = normalizeComponent(
  input_Inputvue_type_script_lang_ts_,
  Inputvue_type_template_id_726d0109_render,
  Inputvue_type_template_id_726d0109_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var Input = (Input_component.exports);
// CONCATENATED MODULE: ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./src/components/progress-bar/ProgressBar.vue?vue&type=template&id=94844768&
var ProgressBarvue_type_template_id_94844768_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _vm._m(0)}
var ProgressBarvue_type_template_id_94844768_staticRenderFns = [function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"wvui-progress-bar",attrs:{"role":"progressbar","aria-valuemin":"0","aria-valuemax":"100"}},[_c('div',{staticClass:"wvui-progress-bar__bar"})])}]


// CONCATENATED MODULE: ./src/components/progress-bar/ProgressBar.vue?vue&type=template&id=94844768&

// CONCATENATED MODULE: ./node_modules/ts-loader??ref--0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/progress-bar/ProgressBar.vue?vue&type=script&lang=ts&

/**
 * Progress bar, currently only supports indeterminate type (i.e. the bar will scroll across the
 * width of the display and then reappear at the start, rather than reflecting a specific amount
 * of progress having been made).
 *
 * @author DannyS712
 */
/* harmony default export */ var ProgressBarvue_type_script_lang_ts_ = (external_vue_default.a.extend({
    name: 'WvuiProgressBar'
}));

// CONCATENATED MODULE: ./src/components/progress-bar/ProgressBar.vue?vue&type=script&lang=ts&
 /* harmony default export */ var progress_bar_ProgressBarvue_type_script_lang_ts_ = (ProgressBarvue_type_script_lang_ts_); 
// EXTERNAL MODULE: ./src/components/progress-bar/ProgressBar.vue?vue&type=style&index=0&lang=less&
var ProgressBarvue_type_style_index_0_lang_less_ = __webpack_require__(20);

// CONCATENATED MODULE: ./src/components/progress-bar/ProgressBar.vue






/* normalize component */

var ProgressBar_component = normalizeComponent(
  progress_bar_ProgressBarvue_type_script_lang_ts_,
  ProgressBarvue_type_template_id_94844768_render,
  ProgressBarvue_type_template_id_94844768_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var ProgressBar = (ProgressBar_component.exports);
// CONCATENATED MODULE: ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./src/components/radio/Radio.vue?vue&type=template&id=ab831004&
var Radiovue_type_template_id_ab831004_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('label',{staticClass:"wvui-radio",class:_vm.rootClasses,attrs:{"aria-disabled":_vm.disabled},on:{"click":_vm.focusInput}},[_c('input',{directives:[{name:"model",rawName:"v-model",value:(_vm.wrappedModel),expression:"wrappedModel"}],ref:"input",staticClass:"wvui-radio__input",attrs:{"type":"radio","name":_vm.name,"disabled":_vm.disabled},domProps:{"value":_vm.inputValue,"checked":_vm._q(_vm.wrappedModel,_vm.inputValue)},on:{"change":function($event){_vm.wrappedModel=_vm.inputValue}}}),_vm._v(" "),_c('span',{staticClass:"wvui-radio__icon"}),_vm._v(" "),_c('span',{staticClass:"wvui-radio__label-content"},[_vm._t("default")],2)])}
var Radiovue_type_template_id_ab831004_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/radio/Radio.vue?vue&type=template&id=ab831004&

// CONCATENATED MODULE: ./node_modules/ts-loader??ref--0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/radio/Radio.vue?vue&type=script&lang=ts&



external_vue_default.a.use(composition_api_default.a);
/**
 * A binary input that always exists in a group, in which only one input can be
 * on at a time.
 *
 * Typical use will involve using v-for to loop through an array of items and
 * output a Radio component for each one. Each Radio will have the same v-model
 * and name props, but different inputValue props and label content.
 *
 * The v-model value is the inputValue of the Radio that is currently on.
 *
 * @fires {Event} input
 */
/* harmony default export */ var Radiovue_type_script_lang_ts_ = (Object(composition_api_["defineComponent"])({
    name: 'WvuiRadio',
    model: {
        prop: 'modelValue',
        event: 'input'
    },
    props: {
        /**
         * Value provided by v-model in a parent component.
         *
         * Rather than directly binding a value prop to this component, use
         * v-model to bind a string, number, or boolean value. This value
         * represents the value of the radio input that is currently on.
         */
        modelValue: modelValueProp,
        /**
         * HTML "value" attribute to assign to the input.
         *
         * Required for input groups.
         */
        inputValue: {
            type: [String, Number, Boolean],
            default: false
        },
        /**
         * Whether the disabled attribute should be added to the input.
         */
        disabled: {
            type: Boolean,
            default: false
        },
        /**
         * HTML "name" attribute to assign to the input.
         *
         * Required for input groups
         */
        name: {
            type: String,
            default: ''
        },
        /**
         * Whether the component should display inline.
         *
         * By default, `display: block` is set and a margin exists between
         * sibling components, for a stacked layout.
         */
        inline: {
            type: Boolean,
            default: false
        }
    },
    setup: function (props, _a) {
        var emit = _a.emit;
        var rootClasses = Object(composition_api_["computed"])(function () {
            return {
                'wvui-radio--inline': !!props.inline
            };
        });
        // Declare template ref.
        var input = Object(composition_api_["ref"])();
        /**
         * When the label is clicked, focus on the input.
         *
         * This doesn't happen automatically in Firefox or Safari.
         */
        var focusInput = function () {
            input.value.focus();
        };
        // Take the modelValue provided by the parent component via v-model and
        // generate a wrapped model that we can use for the input element in
        // this component.
        var modelValueRef = Object(composition_api_["toRef"])(props, 'modelValue');
        var wrappedModel = useModelWrapper(modelValueRef, emit);
        return {
            rootClasses: rootClasses,
            input: input,
            focusInput: focusInput,
            wrappedModel: wrappedModel
        };
    }
}));

// CONCATENATED MODULE: ./src/components/radio/Radio.vue?vue&type=script&lang=ts&
 /* harmony default export */ var radio_Radiovue_type_script_lang_ts_ = (Radiovue_type_script_lang_ts_); 
// EXTERNAL MODULE: ./src/components/radio/Radio.vue?vue&type=style&index=0&lang=less&
var Radiovue_type_style_index_0_lang_less_ = __webpack_require__(21);

// CONCATENATED MODULE: ./src/components/radio/Radio.vue






/* normalize component */

var Radio_component = normalizeComponent(
  radio_Radiovue_type_script_lang_ts_,
  Radiovue_type_template_id_ab831004_render,
  Radiovue_type_template_id_ab831004_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var Radio = (Radio_component.exports);
// CONCATENATED MODULE: ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./src/components/toggle-button/ToggleButton.vue?vue&type=template&id=002cf358&
var ToggleButtonvue_type_template_id_002cf358_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('button',{staticClass:"wvui-toggle-button",class:_vm.rootClasses,attrs:{"aria-pressed":_vm.isActive},on:{"mousedown":function($event){$event.preventDefault();},"click":_vm.onClick}},[_vm._t("default")],2)}
var ToggleButtonvue_type_template_id_002cf358_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/toggle-button/ToggleButton.vue?vue&type=template&id=002cf358&

// CONCATENATED MODULE: ./node_modules/ts-loader??ref--0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/toggle-button/ToggleButton.vue?vue&type=script&lang=ts&

/**
 * A toggle button wrapping slotted content.
 *
 * @author DannyS712
 *
 * @fires {Event} change
 */
/* harmony default export */ var ToggleButtonvue_type_script_lang_ts_ = (external_vue_default.a.extend({
    name: 'WvuiToggleButton',
    props: {
        /**
         * Whether the button should be set to "on" or not. It is the responsibility
         * of the calling code to handle updating this property each time the button
         * is toggled, by listening to the 'change' event.
         */
        isActive: {
            type: Boolean,
            required: true
        }
    },
    computed: {
        rootClasses: function () {
            // Provide --inactive too so that we can simplify selectors
            return {
                'wvui-toggle-button--active': this.isActive,
                'wvui-toggle-button--inactive': !this.isActive
            };
        }
    },
    methods: {
        onClick: function () {
            // Event provides the new value, listener should save this in a property
            // that is bound to isActive so that the toggle gets updated as well.
            this.$emit('change', !this.isActive);
        }
    }
}));

// CONCATENATED MODULE: ./src/components/toggle-button/ToggleButton.vue?vue&type=script&lang=ts&
 /* harmony default export */ var toggle_button_ToggleButtonvue_type_script_lang_ts_ = (ToggleButtonvue_type_script_lang_ts_); 
// EXTERNAL MODULE: ./src/components/toggle-button/ToggleButton.vue?vue&type=style&index=0&lang=less&
var ToggleButtonvue_type_style_index_0_lang_less_ = __webpack_require__(22);

// CONCATENATED MODULE: ./src/components/toggle-button/ToggleButton.vue






/* normalize component */

var ToggleButton_component = normalizeComponent(
  toggle_button_ToggleButtonvue_type_script_lang_ts_,
  ToggleButtonvue_type_template_id_002cf358_render,
  ToggleButtonvue_type_template_id_002cf358_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var ToggleButton = (ToggleButton_component.exports);
// CONCATENATED MODULE: ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./src/components/typeahead-search/TypeaheadSearch.vue?vue&type=template&id=12b7de50&
var TypeaheadSearchvue_type_template_id_12b7de50_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"wvui-typeahead-search",class:_vm.rootClasses,attrs:{"role":"combobox","aria-expanded":_vm.isExpandedString,"aria-haspopup":"listbox","aria-owns":_vm.suggestionsId},on:{"mouseover":_vm.onRootMouseOver,"mouseout":_vm.onRootMouseOut,"keydown":[function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"up",38,$event.key,["Up","ArrowUp"])){ return null; }return _vm.onKeyDownUp($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"down",40,$event.key,["Down","ArrowDown"])){ return null; }return _vm.onKeyDownDown($event)},function($event){if(!$event.type.indexOf('key')&&_vm._k($event.keyCode,"escape",undefined,$event.key,undefined)){ return null; }return _vm.onKeyDownEscape($event)}]}},[_c('form',{staticClass:"wvui-typeahead-search__form",attrs:{"id":_vm.id,"action":_vm.formAction},on:{"submit":_vm.onSubmit}},[_c('div',{staticClass:"wvui-typeahead-search__wrapper"},[_c('wvui-input',_vm._b({staticClass:"wvui-typeahead-search__input",attrs:{"start-icon":_vm.startIcon,"value":_vm.inputValue,"type":_vm.InputType.Search,"name":"search","autocapitalize":"off","autocomplete":"off","aria-autocomplete":"list","aria-controls":_vm.suggestionsId,"aria-activedescendant":_vm.activeSuggestionId},on:{"input":_vm.onInput,"blur":_vm.onInputBlur,"focus":_vm.onInputFocus}},'wvui-input',_vm.$attrs,false)),_vm._v(" "),_vm._t("default"),_vm._v(" "),_c('ol',{staticClass:"wvui-typeahead-search__suggestions",attrs:{"id":_vm.suggestionsId,"role":"listbox","aria-label":_vm.suggestionsLabel}},[_vm._l((_vm.suggestionsList),function(suggestion,index){return _c('li',{key:index,attrs:{"role":"option","aria-selected":_vm.isSuggestionSelected(index)}},[_c('wvui-typeahead-suggestion',{key:suggestion.id,staticClass:"wvui-typeahead-search__suggestion",attrs:{"id":_vm.getSuggestionId( suggestion ),"search-page-title":_vm.searchPageTitle,"article-path":_vm.formAction,"query":_vm.searchQuery,"active":_vm.suggestionActiveIndex === index,"suggestion":suggestion,"show-thumbnail":_vm.showThumbnail,"show-description":_vm.showDescription,"highlight-query":_vm.highlightQuery},on:{"mouseover":function($event){return _vm.onSuggestionMouseOver( index )},"mousedown":_vm.onSuggestionMouseDown,"click":function($event){return _vm.onSuggestionClick( suggestion )}}})],1)}),_vm._v(" "),_c('li',{attrs:{"role":"option"}},[_c('a',{ref:"footer",staticClass:"wvui-typeahead-search__suggestions__footer",class:_vm.footerClasses,attrs:{"id":_vm.footerId,"tabindex":"-1","href":_vm.footerUrl},on:{"mouseover":_vm.onFooterHover,"mousedown":_vm.onSuggestionMouseDown,"click":function($event){return _vm.onSuggestionClick()}}},[_c('wvui-icon',{staticClass:"wvui-typeahead-search__suggestions__footer__icon",attrs:{"icon":_vm.articleIcon}}),_vm._v(" "),_c('span',{staticClass:"wvui-typeahead-search__suggestions__footer__text"},[_vm._t("search-footer-text",[_c('strong',{staticClass:"wvui-typeahead-search__suggestions__footer__text__query"},[_vm._v("\n\t\t\t\t\t\t\t\t\t"+_vm._s(_vm.searchQuery)+"\n\t\t\t\t\t\t\t\t")])],{"searchQuery":_vm.searchQuery})],2)],1)])],2)],2),_vm._v(" "),_c('wvui-button',{staticClass:"wvui-typeahead-search__submit"},[_vm._v("\n\t\t\t"+_vm._s(_vm.buttonLabel)+"\n\t\t")])],1)])}
var TypeaheadSearchvue_type_template_id_12b7de50_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/typeahead-search/TypeaheadSearch.vue?vue&type=template&id=12b7de50&

// CONCATENATED MODULE: ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./src/components/typeahead-suggestion/TypeaheadSuggestion.vue?vue&type=template&id=e306f976&
var TypeaheadSuggestionvue_type_template_id_e306f976_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return (_vm.suggestion)?_c('a',{staticClass:"wvui-typeahead-suggestion",class:_vm.rootClasses,attrs:{"href":_vm.suggestionWikiLink},on:{"mouseover":_vm.onMouseOver,"mousedown":_vm.onMouseDown,"click":_vm.onClick}},[(_vm.showThumbnail && _vm.suggestion.thumbnail)?_c('span',{staticClass:"wvui-typeahead-suggestion__thumbnail",style:({backgroundImage: _vm.thumbnailBackgroundImage})}):(_vm.showThumbnail)?_c('span',{staticClass:"wvui-typeahead-suggestion__thumbnail-placeholder"},[_c('wvui-icon',{staticClass:"wvui-typeahead-suggestion__thumbnail-icon",attrs:{"icon":_vm.defaultThumbnailIcon}})],1):_vm._e(),_vm._v(" "),_c('span',{staticClass:"wvui-typeahead-suggestion__text"},[_c('wvui-typeahead-suggestion-title',{attrs:{"query":_vm.query,"title":_vm.suggestion.title,"highlight-query":_vm.highlightQuery}}),_vm._v(" "),(_vm.showDescription && _vm.suggestion.description)?_c('span',{staticClass:"wvui-typeahead-suggestion__description"},[_vm._v(_vm._s(_vm.suggestion.description))]):_vm._e()],1)]):_vm._e()}
var TypeaheadSuggestionvue_type_template_id_e306f976_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/typeahead-suggestion/TypeaheadSuggestion.vue?vue&type=template&id=e306f976&

// CONCATENATED MODULE: ./src/components/typeahead-search/http/SearchClient.ts


// CONCATENATED MODULE: ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./src/components/typeahead-suggestion-title/TypeaheadSuggestionTitle.vue?vue&type=template&id=6a5e47ec&
var TypeaheadSuggestionTitlevue_type_template_id_6a5e47ec_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('span',{staticClass:"wvui-typeahead-suggestion__title"},[_vm._v("\n\t"+_vm._s(_vm.titleChunks[ 0 ])),_c('span',{staticClass:"wvui-typeahead-suggestion__match"},[_vm._v(_vm._s(_vm.titleChunks[ 1 ]))]),_vm._v(_vm._s(_vm.titleChunks[ 2 ])+"\n")])}
var TypeaheadSuggestionTitlevue_type_template_id_6a5e47ec_staticRenderFns = []


// CONCATENATED MODULE: ./src/components/typeahead-suggestion-title/TypeaheadSuggestionTitle.vue?vue&type=template&id=6a5e47ec&

// CONCATENATED MODULE: ./src/utils/StringUtils.ts
/**
 * Escapes special characters.
 *
 * @param {string} value Value to be escaped
 *
 * @return {string}
 */
function regExpEscape(value) {
    return value.replace(/([\\{}()|.?*+\-^$[\]])/g, '\\$1');
}
/**
 * Serializes a JS object into a URL query string.
 *
 * @param obj Object whose properties will be serialized. A subset of
 *     ConstructorParameters<typeof URLSearchParams>.
 * @return the query string (without the '?')
 */
function buildQueryString(obj) {
    return Object
        .keys(obj)
        .map(function (prop) { return prop + "=" + encodeURIComponent(obj[prop]); })
        .join('&');
}

// CONCATENATED MODULE: ./src/components/typeahead-suggestion-title/TypeaheadSuggestionTitleUtils.ts

// The following was written by Trey Jones as part of https://phabricator.wikimedia.org/T35242. See
// https://gerrit.wikimedia.org/r/c/mediawiki/core/+/530929/ for additional detail.
//
// Equivalent to \p{Mark} (which is not currently available in JavaScript).
//
// eslint-disable-next-line max-len
var COMBINING_MARK = '[\u0300-\u036F\u0483-\u0489\u0591-\u05BD\u05BF\u05C1\u05C2\u05C4\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7\u06E8\u06EA-\u06ED\u0711\u0730-\u074A\u07A6-\u07B0\u07EB-\u07F3\u07FD\u0816-\u0819\u081B-\u0823\u0825-\u0827\u0829-\u082D\u0859-\u085B\u08D3-\u08E1\u08E3-\u0903\u093A-\u093C\u093E-\u094F\u0951-\u0957\u0962\u0963\u0981-\u0983\u09BC\u09BE-\u09C4\u09C7\u09C8\u09CB-\u09CD\u09D7\u09E2\u09E3\u09FE\u0A01-\u0A03\u0A3C\u0A3E-\u0A42\u0A47\u0A48\u0A4B-\u0A4D\u0A51\u0A70\u0A71\u0A75\u0A81-\u0A83\u0ABC\u0ABE-\u0AC5\u0AC7-\u0AC9\u0ACB-\u0ACD\u0AE2\u0AE3\u0AFA-\u0AFF\u0B01-\u0B03\u0B3C\u0B3E-\u0B44\u0B47\u0B48\u0B4B-\u0B4D\u0B56\u0B57\u0B62\u0B63\u0B82\u0BBE-\u0BC2\u0BC6-\u0BC8\u0BCA-\u0BCD\u0BD7\u0C00-\u0C04\u0C3E-\u0C44\u0C46-\u0C48\u0C4A-\u0C4D\u0C55\u0C56\u0C62\u0C63\u0C81-\u0C83\u0CBC\u0CBE-\u0CC4\u0CC6-\u0CC8\u0CCA-\u0CCD\u0CD5\u0CD6\u0CE2\u0CE3\u0D00-\u0D03\u0D3B\u0D3C\u0D3E-\u0D44\u0D46-\u0D48\u0D4A-\u0D4D\u0D57\u0D62\u0D63\u0D82\u0D83\u0DCA\u0DCF-\u0DD4\u0DD6\u0DD8-\u0DDF\u0DF2\u0DF3\u0E31\u0E34-\u0E3A\u0E47-\u0E4E\u0EB1\u0EB4-\u0EB9\u0EBB\u0EBC\u0EC8-\u0ECD\u0F18\u0F19\u0F35\u0F37\u0F39\u0F3E\u0F3F\u0F71-\u0F84\u0F86\u0F87\u0F8D-\u0F97\u0F99-\u0FBC\u0FC6\u102B-\u103E\u1056-\u1059\u105E-\u1060\u1062-\u1064\u1067-\u106D\u1071-\u1074\u1082-\u108D\u108F\u109A-\u109D\u135D-\u135F\u1712-\u1714\u1732-\u1734\u1752\u1753\u1772\u1773\u17B4-\u17D3\u17DD\u180B-\u180D\u1885\u1886\u18A9\u1920-\u192B\u1930-\u193B\u1A17-\u1A1B\u1A55-\u1A5E\u1A60-\u1A7C\u1A7F\u1AB0-\u1ABE\u1B00-\u1B04\u1B34-\u1B44\u1B6B-\u1B73\u1B80-\u1B82\u1BA1-\u1BAD\u1BE6-\u1BF3\u1C24-\u1C37\u1CD0-\u1CD2\u1CD4-\u1CE8\u1CED\u1CF2-\u1CF4\u1CF7-\u1CF9\u1DC0-\u1DF9\u1DFB-\u1DFF\u20D0-\u20F0\u2CEF-\u2CF1\u2D7F\u2DE0-\u2DFF\u302A-\u302F\u3099\u309A\uA66F-\uA672\uA674-\uA67D\uA69E\uA69F\uA6F0\uA6F1\uA802\uA806\uA80B\uA823-\uA827\uA880\uA881\uA8B4-\uA8C5\uA8E0-\uA8F1\uA8FF\uA926-\uA92D\uA947-\uA953\uA980-\uA983\uA9B3-\uA9C0\uA9E5\uAA29-\uAA36\uAA43\uAA4C\uAA4D\uAA7B-\uAA7D\uAAB0\uAAB2-\uAAB4\uAAB7\uAAB8\uAABE\uAABF\uAAC1\uAAEB-\uAAEF\uAAF5\uAAF6\uABE3-\uABEA\uABEC\uABED\uFB1E\uFE00-\uFE0F\uFE20-\uFE2F]';
/**
 * Formats title adding highlighted query if it matches.
 *
 * @param {string} query String to match with
 * @param {string} title Suggestion title
 * @return [string, string, string]
 */
function splitStringAtMatch(query, title) {
    if (!query) {
        return [title, '', ''];
    }
    var sanitizedQuery = regExpEscape(query);
    var match = title.match(new RegExp(
    // Per https://www.regular-expressions.info/unicode.html, "any code point that is not a
    // combining mark can be followed by any number of combining marks." See also the discussion
    // in https://phabricator.wikimedia.org/T35242.
    sanitizedQuery + COMBINING_MARK + '*', 'i'));
    // Note well that index is an optional property that could be zero.
    if (!match || match.index === undefined) {
        return [title, '', ''];
    }
    var matchStartIndex = match.index;
    var matchEndIndex = matchStartIndex + match[0].length;
    var highlightedTitle = title.substring(matchStartIndex, matchEndIndex);
    var beforeHighlight = title.substring(0, matchStartIndex);
    var afterHighlight = title.substring(matchEndIndex, title.length);
    return [beforeHighlight, highlightedTitle, afterHighlight];
}

// CONCATENATED MODULE: ./node_modules/ts-loader??ref--0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/typeahead-suggestion-title/TypeaheadSuggestionTitle.vue?vue&type=script&lang=ts&


/* harmony default export */ var TypeaheadSuggestionTitlevue_type_script_lang_ts_ = (external_vue_default.a.extend({
    name: 'WvuiTypeaheadSuggestionTitle',
    props: {
        query: {
            type: String,
            default: ''
        },
        title: {
            type: String,
            required: true
        },
        highlightQuery: {
            type: Boolean,
            default: true
        }
    },
    computed: {
        /**
         * If highlighting is enabled, returns the title with the part that matches the query
         * highlighted. If highlighting is disabled, returns the unmodified title in a form
         * compatible with the template above.
         *
         * @return [ string, string, string ]
         */
        titleChunks: function () {
            if (this.highlightQuery) {
                return splitStringAtMatch(this.query, this.title);
            }
            return ['', this.title, ''];
        }
    }
}));

// CONCATENATED MODULE: ./src/components/typeahead-suggestion-title/TypeaheadSuggestionTitle.vue?vue&type=script&lang=ts&
 /* harmony default export */ var typeahead_suggestion_title_TypeaheadSuggestionTitlevue_type_script_lang_ts_ = (TypeaheadSuggestionTitlevue_type_script_lang_ts_); 
// EXTERNAL MODULE: ./src/components/typeahead-suggestion-title/TypeaheadSuggestionTitle.vue?vue&type=style&index=0&lang=less&
var TypeaheadSuggestionTitlevue_type_style_index_0_lang_less_ = __webpack_require__(23);

// CONCATENATED MODULE: ./src/components/typeahead-suggestion-title/TypeaheadSuggestionTitle.vue






/* normalize component */

var TypeaheadSuggestionTitle_component = normalizeComponent(
  typeahead_suggestion_title_TypeaheadSuggestionTitlevue_type_script_lang_ts_,
  TypeaheadSuggestionTitlevue_type_template_id_6a5e47ec_render,
  TypeaheadSuggestionTitlevue_type_template_id_6a5e47ec_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var TypeaheadSuggestionTitle = (TypeaheadSuggestionTitle_component.exports);
// CONCATENATED MODULE: ./src/components/typeahead-suggestion/UrlGenerator.ts
var __assign = (undefined && undefined.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};


/**
 * Generates URLs for suggestions like those in MediaWiki's mediawiki.searchSuggest implementation.
 *
 * @return The URL generator
 */
function createDefaultUrlGenerator() {
    return {
        generateUrl: function (suggestion, params, articlePath) {
            if (params === void 0) { params = {
                title: 'Special:Search'
            }; }
            if (articlePath === void 0) { articlePath = '/w/index.php'; }
            if (typeof suggestion !== 'string') {
                suggestion = suggestion.title;
            }
            else {
                // Add `fulltext` query param to search within pages and for navigation
                // to the search results page (prevents being redirected to a certain
                // article).
                params.fulltext = '1';
            }
            return articlePath + "?" + buildQueryString(__assign(__assign({}, params), { search: suggestion }));
        }
    };
}

// CONCATENATED MODULE: ./node_modules/ts-loader??ref--0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/typeahead-suggestion/TypeaheadSuggestion.vue?vue&type=script&lang=ts&






/* harmony default export */ var TypeaheadSuggestionvue_type_script_lang_ts_ = (external_vue_default.a.extend({
    name: 'WvuiTypeaheadSuggestion',
    components: { WvuiTypeaheadSuggestionTitle: TypeaheadSuggestionTitle, WvuiIcon: Icon },
    props: {
        active: {
            type: Boolean,
            default: false
        },
        articlePath: {
            type: String,
            default: '/w/index.php'
        },
        query: {
            type: String,
            default: ''
        },
        suggestion: {
            type: Object,
            required: true
        },
        searchPageTitle: {
            type: String,
            default: 'Special:Search'
        },
        urlGenerator: {
            type: Object,
            default: createDefaultUrlGenerator
        },
        showThumbnail: {
            type: Boolean,
            default: true
        },
        showDescription: {
            type: Boolean,
            default: true
        },
        highlightQuery: {
            type: Boolean,
            default: true
        }
    },
    data: function () {
        return {
            defaultThumbnailIcon: wvuiIconImageLayoutFrameless
        };
    },
    computed: {
        rootClasses: function () {
            return {
                'wvui-typeahead-suggestion--active': this.active
            };
        },
        /**
         * Generates wikipedia link for a suggestion.
         *
         * @return {string}
         * */
        suggestionWikiLink: function () {
            return this.urlGenerator.generateUrl(this.suggestion, {
                title: this.searchPageTitle
            }, this.articlePath);
        },
        /**
         * Generates a proper value for background-image.
         *
         * @return {string}
         * */
        thumbnailBackgroundImage: function () {
            var _a;
            return "url(" + ((_a = this.suggestion.thumbnail) === null || _a === void 0 ? void 0 : _a.url) + ")";
        }
    },
    methods: {
        onMouseOver: function (event) {
            this.$emit('mouseover', event);
        },
        onMouseDown: function (event) {
            this.$emit('mousedown', event);
        },
        onClick: function (event) {
            this.$emit('click', event);
        }
    }
}));

// CONCATENATED MODULE: ./src/components/typeahead-suggestion/TypeaheadSuggestion.vue?vue&type=script&lang=ts&
 /* harmony default export */ var typeahead_suggestion_TypeaheadSuggestionvue_type_script_lang_ts_ = (TypeaheadSuggestionvue_type_script_lang_ts_); 
// EXTERNAL MODULE: ./src/components/typeahead-suggestion/TypeaheadSuggestion.vue?vue&type=style&index=0&lang=less&
var TypeaheadSuggestionvue_type_style_index_0_lang_less_ = __webpack_require__(24);

// CONCATENATED MODULE: ./src/components/typeahead-suggestion/TypeaheadSuggestion.vue






/* normalize component */

var TypeaheadSuggestion_component = normalizeComponent(
  typeahead_suggestion_TypeaheadSuggestionvue_type_script_lang_ts_,
  TypeaheadSuggestionvue_type_template_id_e306f976_render,
  TypeaheadSuggestionvue_type_template_id_e306f976_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var TypeaheadSuggestion = (TypeaheadSuggestion_component.exports);
// CONCATENATED MODULE: ./src/components/typeahead-search/lifecycle-events/index.ts


// CONCATENATED MODULE: ./src/components/typeahead-search/TypeaheadSearch.constants.ts
/**
 * The length of the delay (ms) before dispatching a request via the SearchClient.
 */
var DEBOUNCE_INTERVAL = 120;

// CONCATENATED MODULE: ./node_modules/ts-loader??ref--0!./node_modules/vue-loader/lib??vue-loader-options!./src/components/typeahead-search/TypeaheadSearch.vue?vue&type=script&lang=ts&











/* harmony default export */ var TypeaheadSearchvue_type_script_lang_ts_ = (external_vue_default.a.extend({
    name: 'WvuiTypeaheadSearch',
    components: { WvuiTypeaheadSuggestion: TypeaheadSuggestion, WvuiButton: Button, WvuiInput: Input, WvuiIcon: Icon },
    // Pass all attributes to input
    inheritAttrs: false,
    props: {
        initialInputValue: {
            type: String,
            default: ''
        },
        buttonLabel: {
            type: String,
            required: true
        },
        formAction: {
            type: String,
            required: true
        },
        client: {
            type: Object,
            required: true
        },
        urlGenerator: {
            type: Object,
            default: function () { return createDefaultUrlGenerator(); }
        },
        domain: {
            type: String,
            default: 'en.wikipedia.org'
        },
        /**
         * Used on projects where search does not match the MediaWiki default
         * e.g. Wikimedia Commons
         */
        searchPageTitle: {
            type: String,
            default: 'Special:Search'
        },
        suggestionsLabel: {
            type: String,
            required: true
        },
        focused: {
            type: Boolean,
            default: false
        },
        id: {
            type: String,
            required: true
        },
        showThumbnail: {
            type: Boolean,
            default: true
        },
        showDescription: {
            type: Boolean,
            default: true
        },
        highlightQuery: {
            type: Boolean,
            default: true
        },
        autoExpandWidth: {
            type: Boolean,
            default: false
        }
    },
    data: function () {
        return {
            startIcon: wvuiIconSearch,
            articleIcon: wvuiIconArticleSearch,
            isHovered: false,
            suggestionActiveIndex: -1,
            suggestionsList: [],
            isFocused: this.focused,
            searchQuery: '',
            inputValue: this.initialInputValue,
            InputType: InputType,
            isExpanded: false,
            request: null,
            debounceId: null
        };
    },
    computed: {
        rootClasses: function () {
            return {
                'wvui-typeahead-search--active': this.isHovered,
                'wvui-typeahead-search--focused': this.isFocused,
                'wvui-typeahead-search--has-value': !!this.searchQuery,
                'wvui-typeahead-search--expanded': this.isExpanded,
                'wvui-typeahead-search--show-thumbnail': this.showThumbnail,
                'wvui-typeahead-search--auto-expand-width': this.showThumbnail && this.autoExpandWidth,
                'wvui-typeahead-search--full-width': this.showThumbnail && !this.autoExpandWidth
            };
        },
        footerClasses: function () {
            return {
                'wvui-typeahead-search__suggestions__footer--active': this.isFooterActive
            };
        },
        footerUrl: function () {
            return this.urlGenerator.generateUrl(this.searchQuery, {
                title: this.searchPageTitle
            }, this.formAction);
        },
        isFooterActive: function () {
            return this.suggestionActiveIndex === this.suggestionsList.length;
        },
        suggestionsId: function () {
            return this.id + "-suggestions";
        },
        activeSuggestionId: function () {
            if (!this.isExpanded) {
                return '';
            }
            if (this.suggestionActiveIndex < 0 ||
                this.suggestionActiveIndex > this.suggestionsList.length) {
                return '';
            }
            if (this.isFooterActive) {
                return this.footerId;
            }
            return this.getSuggestionId(this.suggestionsList[this.suggestionActiveIndex]);
        },
        footerId: function () {
            return this.suggestionsId + "-footer";
        },
        isExpandedString: function () {
            return this.isExpanded ? 'true' : 'false';
        }
    },
    mounted: function () {
        if (this.initialInputValue) {
            // Programmatic changes to the input don't trigger the input event so we
            // manually call the onInput method here.
            this.onInput(this.initialInputValue);
        }
    },
    methods: {
        /**
         * Return value of "aria-selected" for a given suggestion
         *
         * Suggestion is considered "selected" if input value matches suggestion
         * "selected" is distinct from "active", 'suggestionActiveIndex' updates on hover and
         * doesn't affect the input value, This definition means a user can enter a value that
         * matches a suggestion exactly and that suggestion is considered "selected" even
         * though the user doesn't interact with the list via keyboard or mouse.
         *
         * This behavior is approximately equivalent to
         * W3's "List Autocomplete with Automatic Selection" example
         * https://www.w3.org/TR/wai-aria-practices-1.1/examples/combobox/aria1.1pattern/listbox-combo.html#ex2_label
         *
         * @param {number} index
         * @return {string} either 'true' or 'false'
         */
        isSuggestionSelected: function (index) {
            var suggestionTitle = this.suggestionsList[index].title;
            var isSelected = this.inputValue.toLowerCase() === suggestionTitle.toLowerCase();
            return isSelected && !this.isFooterActive ? 'true' : 'false';
        },
        /**
         * A convenience method to update those properties that should be updated when new
         * suggestions are available.
         *
         * @param {string} query
         * @param {SearchResult[]} suggestions
         */
        updateSuggestions: function (query, suggestions) {
            this.searchQuery = query;
            this.suggestionsList = suggestions;
            this.suggestionActiveIndex = -1;
            this.isExpanded = !!this.searchQuery && this.isFocused;
        },
        /**
         * A convenience method to update those properties that should be update when clearing the
         * suggestions.
         */
        clearSuggestions: function () {
            this.updateSuggestions('', []);
        },
        onInput: function (value) {
            var _this = this;
            this.inputValue = value;
            if (this.debounceId) {
                // Cancel the last setTimeout callback in case it hasn't executed yet.
                clearTimeout(this.debounceId);
            }
            this.debounceId = setTimeout(function () {
                _this.inputValue = value;
                var query = value.trim();
                if (_this.request) {
                    // Cancel the last request before making a new one in case it is still
                    // pending. This call is expected to be inert if the request has
                    // already finished.
                    _this.request.abort();
                }
                if (!query) {
                    _this.clearSuggestions();
                    return;
                }
                _this.$emit('fetch-start');
                _this.request = _this.client.fetchByTitle(value, _this.domain);
                _this.request.fetch.then(function (_a) {
                    var results = _a.results;
                    _this.updateSuggestions(query, results);
                    // Event
                    var event = {
                        numberOfResults: results.length,
                        query: query
                    };
                    _this.$emit('fetch-end', event);
                })
                    .catch(function () {
                    // Error handling?
                });
            }, DEBOUNCE_INTERVAL);
        },
        onSuggestionMouseOver: function (index) {
            this.suggestionActiveIndex = index;
        },
        onInputFocus: function () {
            this.isHovered = true;
            this.isFocused = true;
            this.isExpanded = !!this.searchQuery;
        },
        onInputBlur: function () {
            this.isFocused = false;
            this.isHovered = false;
            this.isExpanded = false;
        },
        onFooterHover: function () {
            this.suggestionActiveIndex = this.suggestionsList.length;
        },
        onRootMouseOver: function () {
            this.isHovered = true;
        },
        onRootMouseOut: function () {
            this.isHovered = this.isFocused;
            this.suggestionActiveIndex = -1;
        },
        // A click event is only fired after "a mousedown and mouseup over the same screen
        // location" [0].
        //
        // A blur event is fired "when an element loses focus either via the pointing device..."
        // [1].
        //
        // When the user clicks a search suggestion, the browser will fire a mousedown event and
        // then a blur event as the input loses focus. Therefore, we cancel the mousedown event so
        // that the input never loses focus. Moreover, there's a mouseup over the same screen
        // location, then a click event is fired.
        //
        // This solution was written up by Alexey Lebedev here: https://stackoverflow.com/a/10653160
        //
        // [0] https://www.w3.org/TR/DOM-Level-2-Events/events.html#Events-eventgroupings-mouseevents
        // [1] https://www.w3.org/TR/DOM-Level-2-Events/events.html#Events-eventgroupings-htmlevents-h3
        onSuggestionMouseDown: function (event) {
            event.preventDefault();
        },
        onSuggestionClick: function (suggestion) {
            var event = {
                index: this.suggestionActiveIndex,
                numberOfResults: this.suggestionsList.length
            };
            // State updates
            this.inputValue = suggestion ? suggestion.title : this.searchQuery;
            this.updateSuggestions(this.inputValue, []);
            this.isFocused = true;
            this.isExpanded = false;
            // Event
            this.$emit('suggestion-click', event);
        },
        onKeyDownUp: function (event) { this.handleKeyUpDown(event, -1); },
        onKeyDownDown: function (event) { this.handleKeyUpDown(event, 1); },
        handleKeyUpDown: function (event, offset) {
            if (!this.isFocused || !this.isExpanded) {
                return;
            }
            // By default, when the focuses an input and presses the up key, the caret is moved to
            // the beginning of the input. Cancel the event as we always want the caret to be at the
            // end of the input.
            event.preventDefault();
            this.nudgeActiveSuggestion(offset);
        },
        nudgeActiveSuggestion: function (offset) {
            var newSuggestionActiveIndex = this.suggestionActiveIndex;
            newSuggestionActiveIndex += offset;
            // Remember that -1 means that no suggestion is currently highlighted. The next
            // suggestion "above" is actually the bottommost suggestion.
            if (newSuggestionActiveIndex < -1) {
                newSuggestionActiveIndex = this.suggestionsList.length;
                // We use the > operator here (rather than >=) to replicate the behavior of the current
                // search widget in MediaWiki Core - if the user has highlighted the bottommost
                // suggestion, they have to press down _twice_ in order to highlight the topmost
                // suggestion.
            }
            else if (newSuggestionActiveIndex > this.suggestionsList.length + 1) {
                newSuggestionActiveIndex = 0;
            }
            this.suggestionActiveIndex = newSuggestionActiveIndex;
            // Update the input's value
            var item = this.suggestionsList[newSuggestionActiveIndex];
            this.inputValue = item ? item.title : this.searchQuery;
        },
        onKeyDownEscape: function (event) {
            event.preventDefault();
            // Per https://www.w3.org/TR/wai-aria-practices/examples/combobox/aria1.1pattern/listbox-combo.html#kbd_label_textbox,
            // all variations of the Combobox with Listbox Popup pattern should clear the textbox
            // when the escape key is pressed. However, this is not the case in the current search
            // widget in MediaWiki Core.
            // this.inputValue = '';
            this.isExpanded = false;
        },
        // Per https://www.w3.org/TR/wai-aria-practices/examples/combobox/aria1.1pattern/listbox-combo.html#kbd_label_textbox,
        // when the user pressed the enter key:
        //
        // - If the suggestions list (the listbox) isn't displayed, then nothing should happen; or
        //
        // - If the suggestions list is displayed and a suggestion hasn't been highlighted, then
        //   the first suggestion should be highlighed
        //
        // However, the search search widget in MediaWiki Core, simply submits the parent form.
        //
        // onKeyDownEnter( _event: KeyboardEvent ) { /* ... */ }
        getSuggestionId: function (suggestion) {
            return "wvui-typeahead-search-suggestion-" + suggestion.id;
        },
        onSubmit: function (event) {
            // When the user presses the enter key, we prevent form
            // submission when the suggestion footer is active.
            // Instead, we directly navigate to `footerUrl` to ensure
            // the link is the same on both mouse and keyboard
            if (this.suggestionActiveIndex === this.suggestionsList.length) {
                event.preventDefault();
                window.location.assign(this.footerUrl);
            }
            var submitEvent = {
                index: this.suggestionActiveIndex,
                numberOfResults: this.suggestionsList.length
            };
            this.$emit('submit', submitEvent);
        }
    }
}));
// ESLint will also check the styles below (and in other Single File
// Components). Because there are numerous max-len error-causing lines from
// long `calc` styles/variables below, conflicts with competing stylelint
// rules, and because ESLint ignores `eslint-disable` comments placed in the
// styles themselves, we regrettably set it here instead.
//
// See "Known Limitations" from vue-eslint-parser:
// https://github.com/vuejs/vue-eslint-parser#%EF%B8%8F-known-limitations
//
/* eslint-disable max-len */

// CONCATENATED MODULE: ./src/components/typeahead-search/TypeaheadSearch.vue?vue&type=script&lang=ts&
 /* harmony default export */ var typeahead_search_TypeaheadSearchvue_type_script_lang_ts_ = (TypeaheadSearchvue_type_script_lang_ts_); 
// EXTERNAL MODULE: ./src/components/typeahead-search/TypeaheadSearch.vue?vue&type=style&index=0&lang=less&
var TypeaheadSearchvue_type_style_index_0_lang_less_ = __webpack_require__(25);

// CONCATENATED MODULE: ./src/components/typeahead-search/TypeaheadSearch.vue






/* normalize component */

var TypeaheadSearch_component = normalizeComponent(
  typeahead_search_TypeaheadSearchvue_type_script_lang_ts_,
  TypeaheadSearchvue_type_template_id_12b7de50_render,
  TypeaheadSearchvue_type_template_id_12b7de50_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var TypeaheadSearch = (TypeaheadSearch_component.exports);
// CONCATENATED MODULE: ./src/entries/wvui.ts











// Export version as a named export so that the default export can be
// passed to the Vue app instance's components directly.
var version = "0.4.0";
// Export all components available in the library.
/* harmony default export */ var wvui = __webpack_exports__["default"] = ({
    WvuiButton: Button,
    WvuiCheckbox: Checkbox,
    WvuiDropdown: Dropdown,
    WvuiInput: Input,
    WvuiIcon: Icon,
    WvuiOptionsMenu: OptionsMenu,
    WvuiProgressBar: ProgressBar,
    WvuiRadio: Radio,
    WvuiToggleButton: ToggleButton,
    WvuiTypeaheadSearch: TypeaheadSearch,
    WvuiTypeaheadSuggestion: TypeaheadSuggestion
});


/***/ })
/******/ ]);