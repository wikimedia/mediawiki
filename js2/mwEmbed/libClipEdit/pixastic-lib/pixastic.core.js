/*
 * Pixastic Lib - Core Functions - v0.1.1
 * Copyright (c) 2008 Jacob Seidelin, cupboy@gmail.com, http://blog.nihilogic.dk/
 * MIT License [http://www.opensource.org/licenses/mit-license.php]
 */

var Pixastic = (function() {



	function addEvent(el, event, handler) {
		if (el.addEventListener)
			el.addEventListener(event, handler, false); 
		else if (el.attachEvent)
			el.attachEvent("on" + event, handler); 
	}

	function onready(handler) {
		var handlerDone = false;
		var execHandler = function() {
			if (!handlerDone) {
				handlerDone = true;
				handler();
			}
		}
		document.write("<"+"script defer src=\"//:\" id=\"__onload_ie_sumbox__\"></"+"script>");
		var script = document.getElementById("__onload_ie_sumbox__");
		script.onreadystatechange = function() {
			if (script.readyState == "complete") {
				script.parentNode.removeChild(script);
				execHandler();
			}
		}
		if (document.addEventListener)
			document.addEventListener("DOMContentLoaded", execHandler, false); 
		addEvent(window, "load", execHandler);
	}


	function init() {
		if (!Pixastic.parseOnLoad) return;
		var imgEls = getElementsByClass("pixastic", null, "img");
		var canvasEls = getElementsByClass("pixastic", null, "canvas");
		var elements = imgEls.concat(canvasEls);
		for (var i=0;i<elements.length;i++) {
			(function() {

			var el = elements[i];
			var actions = [];
			var classes = el.className.split(" ");
			for (var c=0;c<classes.length;c++) {
				var cls = classes[c];
				if (cls.substring(0,9) == "pixastic-") {
					var actionName = cls.substring(9);
					if (actionName != "")
						actions.push(actionName);
				}
			}
			if (actions.length) {
				if (el.tagName == "IMG") {
					var dataImg = new Image();
					dataImg.src = el.src;
					if (dataImg.complete) {
						for (var a=0;a<actions.length;a++) {
							var res = Pixastic.applyAction(el, el, actions[a], null);
							if (res) 
								el = res;
						}
					} else {
						dataImg.onload = function() {
							for (var a=0;a<actions.length;a++) {
								var res = Pixastic.applyAction(el, el, actions[a], null)
								if (res) 
									el = res;
							}
						}
					}
				} else {
					setTimeout(function() {
						for (var a=0;a<actions.length;a++) {
							var res = Pixastic.applyAction(
								el, el, actions[a], null
							);
							if (res) 
								el = res;
						}
					},1);
				}
			}

			})();
		}
	}

	onready(init);


	// getElementsByClass by Dustin Diaz, http://www.dustindiaz.com/getelementsbyclass/
	function getElementsByClass(searchClass,node,tag) {
			var classElements = new Array();
			if ( node == null )
					node = document;
			if ( tag == null )
					tag = '*';

			var els = node.getElementsByTagName(tag);
			var elsLen = els.length;
			var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
			for (i = 0, j = 0; i < elsLen; i++) {
					if ( pattern.test(els[i].className) ) {
							classElements[j] = els[i];
							j++;
					}
			}
			return classElements;
	}

	var debugElement;

	function writeDebug(text, level) {
		if (!Pixastic.debug) return;

		try {
			switch (level) {
				case "warn" : 
					console.warn("Pixastic:", text);
					break;
				case "error" :
					console.error("Pixastic:", text);
					break;
				default:
					console.log("Pixastic:", text);
			}
		} catch(e) {
		}
		if (!debugElement) {
			
		}
	}


	return {

		parseOnLoad : false,

		debug : false,
		
		applyAction : function(img, dataImg, actionName, options) {

			options = options || {};

			var imageIsCanvas = (img.tagName == "CANVAS");
			if (imageIsCanvas && Pixastic.Client.isIE()) {
				if (Pixastic.debug) writeDebug("Tried to process a canvas element but browser is IE.");
				return false;
			}

			var canvas, ctx;
			if (Pixastic.Client.hasCanvas()) {
				canvas = document.createElement("canvas");
				ctx = canvas.getContext("2d");
			}

			var w = parseInt(img.offsetWidth);
			var h = parseInt(img.offsetHeight);

			if (actionName.indexOf("(") > -1) {
				var tmp = actionName;
				actionName = tmp.substr(0, tmp.indexOf("("));
				var arg = tmp.match(/\((.*?)\)/);
				if (arg[1]) {
					arg = arg[1].split(";");
					for (var a=0;a<arg.length;a++) {
						thisArg = arg[a].split("=");
						if (thisArg.length == 2) {
							if (thisArg[0] == "rect") {
								var rectVal = thisArg[1].split(",");
								options[thisArg[0]] = {
									left : parseInt(rectVal[0],10)||0,
									top : parseInt(rectVal[1],10)||0,
									width : parseInt(rectVal[2],10)||0,
									height : parseInt(rectVal[3],10)||0
								}
							} else {
								options[thisArg[0]] = thisArg[1];
							}
						}
					}
				}
			}

			if (!options.rect) {
				options.rect = {
					left : 0, top : 0, width : w, height : h
				};
			}
			var validAction = false;
			if (Pixastic.Actions[actionName] && typeof Pixastic.Actions[actionName].process == "function") {
				validAction = true;
			}
			if (!validAction) {
				if (Pixastic.debug) writeDebug("Invalid action \"" + actionName + "\". Maybe file not included?");
				return false;
			}
			if (!Pixastic.Actions[actionName].checkSupport()) {
				if (Pixastic.debug) writeDebug("Action \"" + actionName + "\" not supported by this browser.");
				return false;
			}

			if (Pixastic.Client.hasCanvas()) {
				canvas.width = w;
				canvas.height = h;
				canvas.style.width = w+"px";
				canvas.style.height = h+"px";
				ctx.drawImage(dataImg,0,0,w,h);
			}

			var params = {
				image : img,
				canvas : canvas,
				width : w,
				height : h,
				useData : true,
				options : options
			}
	
			var res = Pixastic.Actions[actionName].process(params);

			if (!res) {
				return false;
			}

			if (Pixastic.Client.hasCanvas()) {
				if (params.useData) {
					if (Pixastic.Client.hasCanvasImageData()) {
						canvas.getContext("2d").putImageData(params.canvasData, options.rect.left, options.rect.top);
						// Opera doesn't seem to update the canvas until we draw something on it, lets draw a 0x0 rectangle.
						canvas.getContext("2d").fillRect(0,0,0,0);
					}
				}
				// copy properties and stuff from the source image
				canvas.title = img.title;
				canvas.imgsrc = img.imgsrc;
				if (!imageIsCanvas) canvas.alt  = img.alt;
				if (!imageIsCanvas) canvas.imgsrc = img.src;
				canvas.className = img.className;
				if (img.getAttribute("style"))
					canvas.setAttribute("style", img.getAttribute("style"));
				canvas.cssText = img.cssText;
				canvas.name = img.name;
				canvas.tabIndex = img.tabIndex;
				canvas.id = img.id;

				if (img.parentNode && img.parentNode.replaceChild) {
					img.parentNode.replaceChild(canvas, img);
				}

				return canvas;
			}

			return img;
		},

		prepareData : function(params, getCopy) {
			var ctx = params.canvas.getContext("2d");
			var rect = params.options.rect;
			var dataDesc = ctx.getImageData(rect.left, rect.top, rect.width, rect.height);
			var data = dataDesc.data;
			if (!getCopy) params.canvasData = dataDesc;
			return data;
		},

		// load the image file
		process : function(img, actionName, options, callback)
		{
			if (img.tagName == "IMG") {
				var dataImg = new Image();
				dataImg.src = img.src;
				if (dataImg.complete) {
					var res = Pixastic.applyAction(img, dataImg, actionName, options);
					if (callback) callback(res);
					return res;
				} else {
					dataImg.onload = function() {
						var res = Pixastic.applyAction(img, dataImg, actionName, options)
						if (callback) callback(res);
					}
				}
			}
			if (img.tagName == "CANVAS") {
				var res = Pixastic.applyAction(img, img, actionName, options);
				if (callback) callback(res);
				return res;
			}
		},

		Client : {
			hasCanvas : (function() {
				var c = document.createElement("canvas");
				var val = false;
				try {
					val = !!((typeof c.getContext == "function") && c.getContext("2d"));
				} catch(e) {}
				return function() {
					return val;
				}
			})(),

			hasCanvasImageData : (function() {
				var c = document.createElement("canvas");
				var val = false;
				var ctx;
				try {
					if (typeof c.getContext == "function" && (ctx = c.getContext("2d"))) {
						val = (typeof ctx.getImageData == "function");
					}
				} catch(e) {}
				return function() {
					return val;
				}
			})(),

			isIE : function() {
				return !!document.all && !!window.attachEvent && !window.opera;
			}
		},

		Actions : {}
	}


})();
