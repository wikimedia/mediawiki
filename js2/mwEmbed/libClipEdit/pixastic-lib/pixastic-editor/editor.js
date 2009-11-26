
var PixasticEditor = (function () {

	var $frame;	// iframe container element
	var $editor;	// editor container element

	// various UI structures
	var accordionElements = {};
	var tabElements = {};
	var activeTabId;
	var $activeTabContent;

	var isRunning = false;

	var $loadingScreen;

	var $imageCanvas;	// the canvas holding the current state of the image
	var $displayCanvas;	// the canvas element displayed on the screen, also the working canvas (where preview operations are performed)
	var imageCtx;

	var imageWidth = 0;	// dimensions of the current image state
	var imageHeight = 0;

	var undoImages = [];	// canvas elements holding previous image states
	var undoLevels = 10;

	var doc;

	var $;

	// test for valid file formats for toDataURL()
	// we do that by calling it with each of the mime types in testFormats
	// and then doing string checking on the resulting data: URI to see if it succeeded
	var saveFormats = [];
	var testFormats = [["image/jpeg", "JPEG"], ["image/png", "PNG"]];
	var testCanvas = document.createElement("canvas");
	if (testCanvas.toDataURL) {
		testCanvas.width = testCanvas.height = 1;
		for (var i=0;i<testFormats.length;i++) {
			var data = testCanvas.toDataURL(testFormats[i][0]);
			if (data.substr(0, 5 + testFormats[i][0].length) == "data:" + testFormats[i][0])
				saveFormats.push({mime:testFormats[i][0], name:testFormats[i][1]});
		}
	}


	// pops up an error dialog with the specified text (errTxt),
	// if no context is provided, the name of the calling function is used.
	// The final message is returned for easy throwing of actual errors
	function errorDialog(errTxt, context) {
		if (!($editor && $editor.get && $editor.get(0)))
			throw new Error("errorDialog(): $editor doesn't exist");

		var caller = errorDialog.caller.toString().split(" ")[1];
		caller = caller.substring(0, caller.indexOf("("));
		context = context || caller;
		errTxt = context + "(): " + errTxt;
		var dialog = $j("<div></div>", doc)
			.addClass("error-dialog")
			.attr("title", "Oops!")
			.html(errTxt)
			.dialog();
		// the dialog is added outside the Pixastic container, so get it back in.
		var dialogParent = $j(dialog.get(0).parentNode);
		dialogParent.appendTo($editor);

		return errTxt;
	}
	
	function enableTab(id, refresh) {
		if (id == activeTabId && !refresh)
			return;

		activeTabId = id;

		var activeIndex = 0;

		if ($activeTabContent) {
			if ($activeTabContent.get(0)) {
				var $parent = $j($activeTabContent.get(0).parentNode);
				activeIndex = $parent.data("accordionindex");
				if ($parent.data("ondeactivate")) {
					$parent.data("ondeactivate")();
				}
				if ($parent.data("previewCheckbox"))
					$parent.data("previewCheckbox").attr("checked", false);
				$parent.data("uidesc").previewEnabled = false;
				if ($parent.data("uidesc").forcePreview)
					$parent.data("uidesc").previewEnabled = true;
			}
		}


		for (var a in accordionElements) {
			if (accordionElements.hasOwnProperty(a)) {
				accordionElements[a].accordion("option", "animated", false);
				accordionElements[a].accordion("activate", -1);
				accordionElements[a].hide();
				tabElements[a].removeClass("active");

			}
		}

		accordionElements[id].accordion("option", "animated", false);
		accordionElements[id].accordion("activate", refresh ? activeIndex : 0);
		tabElements[id].addClass("active");
		accordionElements[id].show();
		accordionElements[id].accordion("option", "animated", "slide");
		resetDisplayCanvas();
	}

	// revert to a previous image state
	function undo(idx) {
		var undoImage = undoImages[idx];

		if (!undoImage) 
			throw new Error(errorDialog("Invalid undo state"));
		if (!($imageCanvas && $imageCanvas.get && $imageCanvas.get(0)))
			throw new Error(errorDialog("$imageCanvas doesn't exist"));

		var canvas = $imageCanvas.get(0);
		addUndo(canvas);
		canvas.width = imageWidth = undoImage.width;
		canvas.height = imageHeight = undoImage.height;
		canvas.getContext("2d").drawImage(undoImage,0,0);

		enableTab(activeTabId, true);
		resetDisplayCanvas();
	}

	function addUndo(canvasElement) {
		if (!canvasElement)
			throw new Error(errorDialog("No undo image state provided"));

		if (undoImages.length == undoLevels) {
			undoImages.shift();
		}
		var undoCanvas = document.createElement("canvas");
		undoCanvas.width = canvasElement.width;
		undoCanvas.height = canvasElement.height;
		undoCanvas.getContext("2d").drawImage(canvasElement,0,0);
		$j(undoCanvas).addClass("undo-canvas");
		undoImages.push(undoCanvas);
		updateUndoList();
	}

	function updateUndoList() {
		var $listCtr = $j("#undo-bar", doc)
			.html("");

		var ctrHeight = $listCtr.height();

		var $testCanvas = $j("<canvas></canvas>", doc)
			.addClass("undo-canvas-small")
			.addClass("far-far-away")
			.appendTo("body");

		var canvasHeight = $testCanvas.height();
		var canvasWidth = $testCanvas.width();
		var canvasCSSHeight = canvasHeight + parseInt($testCanvas.css("margin-top"),10) + parseInt($testCanvas.css("margin-bottom"),10);

		$testCanvas.remove();

		var undoRatio = canvasWidth / canvasHeight;

		for (var i=undoImages.length-1;i>=0;i--) {
			(function(){
				var canvas = document.createElement("canvas");
				$j(canvas)
					.addClass("undo-canvas-small")
					.attr("width", canvasWidth)
					.attr("height", canvasHeight);

				var image = undoImages[i];
				$j(image).show();
				
				var undoWidth, undoHeight;
				var imageRatio = image.width / image.height;

				if (imageRatio > undoRatio) {	// image too wide
					undoWidth = canvasWidth;
					undoHeight = canvasWidth / imageRatio;
				} else {
					undoWidth = canvasHeight * imageRatio;
					undoHeight = canvasHeight;
				}

				var restWidth = canvasWidth - undoWidth;
				var restHeight = canvasHeight - undoHeight;

				canvas.getContext("2d").drawImage(
					image,
					0,0,image.width,image.height,
					restWidth*0.5, restHeight*0.5,
					undoWidth, undoHeight
				);


				$link = $j("<a href='#'></a>", doc)
					.addClass("undo-link")
					.appendTo($listCtr)
					.mouseover(function(){ $j(this).addClass("hover") })
					.mouseout(function(){ $j(this).removeClass("hover") });
				$j(canvas).appendTo($link);

				var displayShowing;
				var undoIndex = i;
				$link.click(function() {
					$j(image).hide();
					$j(image).remove();
					undo(undoIndex);
					if (displayShowing)
						$displayCanvas.show();
					$j(".jcrop-holder", doc).show();
				});

				$link.mouseover(function() {
					displayShowing = $displayCanvas.css("display") != "none";
					var $imagectr = $j("#image-container", doc);

					$j(".jcrop-holder", doc).hide();
					$displayCanvas.hide();
					$j(image).appendTo($imagectr);

					var h1 = $j("#image-area", doc).height();
					var h2 = image.height;
					var m = Math.max(0, (h1 - h2) / 2);
					$imagectr.css("marginTop", m);
			
					$imagectr.height(image.height);
				});

				$link.mouseout(function() {
					$j(image).remove();
					if (displayShowing)
						$displayCanvas.show();
					$j(".jcrop-holder", doc).show();
					updateDisplayCanvas();
				});


				$j(canvas).attr("title", "Click to revert to this previous image");

			})();
		}
	}


	function applyAction(id, options, afteraction) {
		if (!Pixastic.Actions[id])
			throw new Error("applyAction(): unknown action [" + id + "]");

		$j("#action-bar-overlay", doc).show();

		setTimeout(function() {
			options.leaveDOM = true;
			var canvasElement = $imageCanvas.get(0);
			addUndo(canvasElement)
	
			var res = Pixastic.process(
				canvasElement, id, options,
				function(resCanvas) {
					canvasElement.width = imageWidth = resCanvas.width;
					canvasElement.height = imageHeight = resCanvas.height;
	
					var ctx = canvasElement.getContext("2d");
					ctx.clearRect(0,0,imageWidth,imageHeight);
					ctx.drawImage(resCanvas,0,0);
					$imageCanvas = $j(canvasElement);
					resetDisplayCanvas();
	
					$j("#action-bar-overlay", doc).hide();

					if (afteraction)
						afteraction();
				}
			);
			if (!res)
				throw new Error("applyAction(): Pixastic.process() failed for action [" + id + "]");
		},1);
	}


	function previewAction(id, options, afteraction) {
		if (!Pixastic.Actions[id])
			throw new Error("applyAction(): unknown action [" + id + "]");

		$j("#action-bar-overlay", doc).show();

		resetDisplayCanvas();

		options.leaveDOM = true;
		var canvasElement = $displayCanvas.get(0);

		var res = Pixastic.process(
			canvasElement, id, options,
			function(resCanvas) {

				canvasElement.width = resCanvas.width;
				canvasElement.height = resCanvas.height;

				var ctx = canvasElement.getContext("2d");
				ctx.clearRect(0,0,canvasElement.width,canvasElement.height);
				ctx.drawImage(resCanvas,0,0);
				updateDisplayCanvas();
				updateOverlay();

				$j("#action-bar-overlay", doc).hide();

				if (afteraction)
					afteraction();
			}
		);
	}

	var onwindowresize = function() {
		updateDisplayCanvas();
		updateOverlay();
	}

	var baseUrl = ""

	function buildEditor() {
		var styles = [
			"jquery-ui-1.7.1.custom.css",
			"jquery.Jcrop.css",
			"pixastic.css"
		];

		for (var i=0;i<styles.length;i++) {
			var s = doc.createElement("link");
			s.href = baseUrl + styles[i];
			s.type = "text/css";
			s.rel = "stylesheet";
			doc.getElementsByTagName("head")[0].appendChild( s );
		}

		undoImages = [];
		accordionElements = {};
		tabElements = {};
		activeTabId = -1;
		$activeTabContent = null;

		// setup DOM UI skeleton
		$editor = $j("<div />", doc)
			.attr("id", "pixastic-editor")
			.appendTo($j(doc.body));

		$editor.append(
			$j("<div id='background' />", doc),
			$j("<div id='edit-ctr-1' />", doc).append(
				$j("<div id='edit-ctr-2' />", doc).append(
					$j("<div id='controls-bar' />", doc).append(
						$j("<div id='action-bar' />", doc).append(
							$j("<div id='action-bar-overlay' />", doc)
						),
						$j("<div id='undo-bar' />", doc)
					),
					$j("<div id='image-area' />", doc).append(
						$j("<div id='image-area-sub' />", doc).append(
							$j("<div id='image-container' />", doc),
							$j("<div id='image-overlay-container' />", doc).append(
								$j("<div id='image-overlay' />", doc)
							)
						)
					)
				)
			),
			$j("<div id='main-bar' />", doc),
			$j("<div id='powered-by-pixastic'><a href=\"http://www.pixastic.com/\" target=\"_blank\">Powered by Pixastic</a></div>", doc)
		);

		$j("#image-container", doc).append(
			$displayCanvas = $j("<canvas />", doc)
				.addClass("display-canvas")
		);

		// loop through all  defined UI action controls
		var tabs = PixasticEditor.UI.data.tabs;

		for (var i=0;i<tabs.length;i++) {
			(function() {
	
			var tab = tabs[i];

			var $tabElement = $j("<a href=\"#\">" + tab.title + "</a>", doc)
				.attr("id", "main-tab-button-" + tab.id)
				.addClass("main-tab")
				.click(function() {
					enableTab(tab.id);
				})
				.mouseover(function(){ $j(this).addClass("hover") })
				.mouseout(function(){ $j(this).removeClass("hover") });
	
			$j("#main-bar", doc).append($tabElement);

			tabElements[tab.id] = $tabElement;

			var $menu = $j("<div/>", doc);
			accordionElements[tab.id] = $menu;

			for (var j=0;j<tab.actions.length;j++) {
				(function() {

				var action = tab.actions[j];

				var $actionElement = $j("<div><h3><a href=\"#\">" + action.title + "</a></h3></div>", doc)

				$menu.append($actionElement);

				var $content = $j("<div></div>", doc)
					.attr("id", "pixastic-action-tab-content-" + action.id)
					.appendTo($actionElement);

				var controlOptions = [];

				action.previewEnabled = false;
				if (action.forcePreview)
					action.previewEnabled = true;

				function togglePreview(enable, doAction) {
					if (enable && !action.previewEnabled && doAction)
						doAction(true);
					if (!enable && action.previewEnabled)
						resetDisplayCanvas();
			
					action.previewEnabled = enable;
				}

				var reset = function() {
					for (var i in controlOptions) {
						if (controlOptions.hasOwnProperty(i)) {
							controlOptions[i].reset();
						}
					}
					if (action.previewEnabled)
						doAction(true);
				}
				var doAction = function(isPreview) {
					var options = {};
					for (var i in controlOptions) {
						if (controlOptions.hasOwnProperty(i)) {
							options[i] = controlOptions[i].valueField.val();
						}
					}

					var afteraction = function() {
						if (action.onafteraction)
							action.onafteraction(action, isPreview);
						if (!isPreview)
							resetDisplayCanvas();
	
						if (!isPreview && !action.forcePreview) {
							$j("#pixastic-input-preview-" + action.id, doc).attr("checked", false);
							togglePreview(false);
							reset();
						}
					}

					if (isPreview) {
						previewAction(action.id, options, afteraction);
					} else {
						applyAction(action.id, options, afteraction);
					}

				}

				var hadInputs = false;

				if (action.controls) {
					var onChange = function() {};
					if (action.isAction && action.preview) {
						onChange = function() {
							if (action.previewEnabled)
								doAction(true)
						};
					}

					for (var k=0;k<action.controls.length;k++) {
						var control = action.controls[k];
						if (typeof control.defaultValue != "function") {
							(function(){
							var defVal = control.defaultValue;
							control.defaultValue = function() {
								return defVal;
							}
							})();
						}
						var controlId = action.id + "-" + control.option;

						if (control.type != "output")
							hadInputs = true;

						switch (control.type) {
							case "number" :
								switch (control.ui) {
									case "slider" : 
										var slider = PixasticEditor.UI.makeSlider(
											control.label, controlId, 
											control.range[0], control.range[1], control.step, control.defaultValue, onChange
										);
		
										slider.container.appendTo($content);
										controlOptions[control.option] = slider;
										break;
									case "text" : 
										var text = PixasticEditor.UI.makeNumericInput(
											control.label, control.labelRight, controlId, 
											control.range[0], control.range[1], control.step, control.defaultValue, onChange
										);
										text.container.appendTo($content);
										controlOptions[control.option] = text;
										break;
								}
								break;
							case "boolean" :
								switch (control.ui) {
									case "checkbox" : 
										var checkbox = PixasticEditor.UI.makeCheckbox(
											control.label, controlId, control.defaultValue, onChange
										);
		
										checkbox.container.appendTo($content);
										controlOptions[control.option] = checkbox;
										break;
								}
							case "string" :
								switch (control.ui) {
									case "select" : 
										var select = PixasticEditor.UI.makeSelect(
											control.label, controlId, control.values, control.defaultValue, onChange
										);
		
										select.container.appendTo($content);
										controlOptions[control.option] = select;
										break;
								}
								break;
							case "output" :
								var outputText = $j("<div></div>", doc)
									.addClass("ui-action-output")
									.html(control.content)
									.appendTo($content);
								break;
						}
					}
				}

				if (action.isAction) {

					var $applyButton = PixasticEditor.UI.makeButton("Apply")
						.addClass("pixastic-option-button-apply")
						.click(function() {doAction();});

					$content.append($applyButton);

					if (hadInputs) {
						var $resetButton = PixasticEditor.UI.makeButton("Reset")
							.addClass("pixastic-option-button-reset")
							.click(reset);
	
						$content.append($resetButton)
					}

					if (action.preview && !action.forcePreview) {
						var $checkctr = $j("<div></div>", doc)
							.addClass("ui-checkbox-container")
							.addClass("ui-preview-checkbox-container");

						var $label = $j("<label></label>", doc)
							.addClass("ui-checkbox-label")
							.attr("for", "pixastic-input-preview-" + action.id)
							.html("Preview:")
							.appendTo($checkctr);

						var $checkbox = $j("<input type=\"checkbox\"></input>", doc)
							.addClass("ui-checkbox")
							.attr("id", "pixastic-input-preview-" + action.id)
							.appendTo($checkctr)
							.change(function() {
								togglePreview(this.checked, doAction)
							});

						$content.append($checkctr);

						$content.data("previewCheckbox", $checkbox);
					}

				}


				if (typeof action.content == "function") {
					action.content($content);
				}

				// stupid hack to make it possible to get $content in change event (below)
				$j("<span></span>", doc).appendTo($content);

				$content.data("controlOptions", controlOptions);
				$content.data("onactivate", action.onactivate);
				$content.data("ondeactivate", action.ondeactivate);
				$content.data("onoverlayupdate", action.onoverlayupdate);
				$content.data("accordionindex", j);
				$content.data("uidesc", action);

				})();
			}
	
			$j("#action-bar", doc).append($menu);

			$menu.hide().accordion({
				header: "h3",
				autoHeight : false,
				collapsible : true,
				active: -1
			})
			.bind("accordionchange", 
				function(event, ui) {
					resetDisplayCanvas();

					// oldContent / newContent are arrays of whatever elements are present in the content area
					// We need the parent element (the one holding the content) but if there is no content, how do we get it?
					// fixed above by always appending a <span> but that's ugly and needs to be done in some other way
					if (ui.oldContent.get(0)) {
						var $parent = $j(ui.oldContent.get(0).parentNode);
						if ($parent.data("ondeactivate")) {
							$parent.data("ondeactivate")();
						}
					}
					$activeTabContent = ui.newContent;

					if (ui.newContent.get(0)) {
						var $parent = $j(ui.newContent.get(0).parentNode);
						if ($parent.data("previewCheckbox"))
							$parent.data("previewCheckbox").attr("checked", false);
						$parent.data("uidesc").previewEnabled = false;
						if ($parent.data("uidesc").forcePreview)
							$parent.data("uidesc").previewEnabled = true;

						var controlOptions = $parent.data("controlOptions");
						for (var i in controlOptions) {
							if (controlOptions.hasOwnProperty(i)) {
								controlOptions[i].reset();
							}
						}
						if ($parent.data("onactivate")) {
							$parent.data("onactivate")();
						}
					}
					updateDisplayCanvas();

				}
			);

	
			})();
		}

		$j(window).bind("resize", onwindowresize);
	}

	function showLoadingScreen() {
		if ($loadingScreen) {
			$loadingScreen.show();
			return;
		}
		$loadingScreen = $j("<div id=\"loading-screen\" />")
		var $ctr = $j("<div id=\"loading-screen-cell\" />");
		$j("<div />")
			.addClass("spinner")
			.appendTo($ctr);
		$loadingScreen.append($ctr);
		$loadingScreen.appendTo("body");
	}

	function hideLoadingScreen() {
		setTimeout(function() {
			$loadingScreen.hide();
		}, 1);
	}

	var oldScrollLeft;
	var oldScrollTop;
	var oldOverflow;

	// fire it up
	function init(callback) {
		isRunning = true;

		showLoadingScreen();

		oldScrollLeft = document.body.scrollLeft;
		oldScrollTop = document.body.scrollTop;
		oldOverflow = document.body.style.overflow;

		document.body.scrollLeft = 0;
		document.body.scrollTop = 0;
		document.body.style.overflow = "hidden";

		$frame = $j("<iframe />");
		$frame.hide();
		$frame.css({
			position : "absolute",
			left : document.body.scrollLeft + "px",
			top : document.body.scrollTop + "px",
			width : "100%",
			height : "100%",
			zIndex : "11"
		});
		$frame.load(function(){
			doc = $frame.get(0).contentDocument;

			buildEditor();
			callback();
			$frame.show();
			hideLoadingScreen();
			setTimeout(function(){
				updateDisplayCanvas();
			},10);
		});
		$frame.appendTo("body");
	}

	// unload the editor, remove all elements added to the page and restore whatever properties we messed with
	function unload() {
		$j(window).unbind("resize", onwindowresize);
		$frame.hide();
		$editor.hide();
		$editor.remove();
		$frame.remove();

		document.body.scrollLeft = oldScrollLeft;
		document.body.scrollTop = oldScrollTop;
		document.body.style.overflow = oldOverflow;

		isRunning = false;
	}


	// resets the display canvas (clears the canvas and repaints the current state)
	// then updates display and overlay
	function resetDisplayCanvas() {
		if (!($displayCanvas && $displayCanvas.get))	throw new Error(errorDialog("$displayCanvas doesn't exist"));
		if (!($imageCanvas && $imageCanvas.get))	throw new Error(errorDialog("$imageCanvas doesn't exist"));

		var display = $displayCanvas.get(0);
		var image = $imageCanvas.get(0);

		if (!display) 	throw new Error(errorDialog("resetDisplayCanvas(): No elements in $displayCanvas"));
		if (!image) 	throw new Error(errorDialog("resetDisplayCanvas(): No elements in $imageCanvas"));

		display.width = imageWidth;
		display.height = imageHeight;
		display.getContext("2d").drawImage( image, 0, 0 );

		updateDisplayCanvas();
		updateOverlay();
	}

	// updates the display by resetting the height and margin of the image container
	// this is mainly to keep vertical centering
	function updateDisplayCanvas() {
		var $imageCtr = $j("#image-container", doc);
		var $editArea = $j("#image-area", doc);

		if (!$imageCtr.get(0)) 		throw new Error(errorDialog("updateDisplayCanvas(): $imageCtr doesn't exist"));
		if (!$displayCanvas.get(0)) 	throw new Error(errorDialog("updateDisplayCanvas(): $displayCanvas doesn't exist"));
		if (!$editArea.get(0))	 	throw new Error(errorDialog("updateDisplayCanvas(): $editArea doesn't exist"));

		var h2 = $displayCanvas.get(0).height;
		var h1 = $j("#image-area", doc).height();
		var m = Math.max(0, (h1 - h2) / 2);
		$imageCtr.height(h2);
		$imageCtr.css("marginTop", m);
	}

	// basically the same as updateDisplayCanvas but for the image overlay
	function updateOverlay() {
		var $overlay = $j("#image-overlay-container", doc);
		var $imagectr = $j("#image-container", doc);
		$overlay.height($imagectr.height());
		$overlay.css("marginTop", $imagectr.css("marginTop"));

		if ($activeTabContent && $activeTabContent.get(0)) {
			var $tabContent = $j($activeTabContent.get(0).parentNode);
			if (typeof $tabContent.data("onoverlayupdate") == "function")
				$tabContent.data("onoverlayupdate")();
		}
	}

	var imageIsLoading = false;
	var originalImageElement;
	var $tmpImg;

	function loadImage(imgEl) {
		if (imageIsLoading) 
			return;

		imageIsLoading = true;

		originalImageElement = imgEl;

		$imageCanvas = $j("<canvas />", doc);
		imageCtx = $imageCanvas.get(0).getContext("2d");

		imageWidth = 0;
		imageHeight = 0;
		$imageCanvas.attr("width", 0);
		$imageCanvas.attr("height", 0);

		if (imgEl.tagName.toLowerCase() == "img" && !imgEl._pixasticCanvas) {
			var onload = function(el) {
				imageWidth = el.offsetWidth;
				imageHeight = el.offsetHeight;
				$imageCanvas.attr("width", imageWidth);
				$imageCanvas.attr("height", imageHeight);
				imageCtx.drawImage(el,0,0);
				$tmpImg.remove();
				imageIsLoading = false;
				enableTab("reshape");
				setTimeout(function() {
					resetDisplayCanvas();
				}, 10);
			}
			$tmpImg = $j("<img />", doc)
				.css("position", "absolute")
				.css("left", "-9999px")
				.css("top", "-9999px")
				.appendTo("body")
				.load(function(){onload(this);})
				.error(function(){
					throw new Error("Could not load temporary copy image. Is provided image valid?");
					unload();
				})
				.attr("src", imgEl.src);
				if ($tmpImg.attr("complete")) {
					onload($tmpImg.get(0));
				}
		} else {
			var $canvas = imgEl._pixasticCanvas || imgEl;
			imageWidth = $canvas.attr("width");
			imageHeight = $canvas.attr("height");
			$imageCanvas.attr("width", imageWidth);
			$imageCanvas.attr("height", imageHeight);
			imageCtx.drawImage($canvas.get(0), 0, 0);
			imageIsLoading = false;
			enableTab("reshape");
			resetDisplayCanvas();
		}
	}

	// return public interface
	return {
		/*
		// don't call. For now we must load the image immediately via load()
		loadImage : function(imgEl) {
			if (!isRunning) return false;
			loadImage(imgEl);
		},
		*/
		saveToPage : function() {
			if (!isRunning) throw new Error("PixasticEditor::saveToPage(): Editor is not running");

			var $canvas = PixasticEditor.getImageCanvas();
			var img = PixasticEditor.getOriginalImage();
			if (img.tagName.toLowerCase() == "canvas") {
				img.width = $canvas.attr("width");
				img.height = $canvas.attr("height");
				img.getContext("2d").drawImage($canvas.get(0), 0, 0);
			} else {
				img.src = PixasticEditor.getDataURI();
			}
			img._pixasticCanvas = PixasticEditor.getImageCanvas();
		},
		load : function(img, customBaseUrl) {
			if (isRunning) return false;

			if (!img)
				throw new Error("Must be called with an image or canvas as its first argument", "PixasticEditor::load")

			$ = PixasticEditor.jQuery;

			baseUrl = customBaseUrl || "http://www.pixastic.com/editor-test/";

			init(function() {
				if (img && img.tagName.toLowerCase() == "img" || img.tagName.toLowerCase() == "canvas") {
					loadImage(img);
				}
			});
		},

		unload : function() {
			if (!isRunning) throw new Error("PixasticEditor::unload(): Editor is not running");
			unload();
		},

		getDocument : function() {
			if (!isRunning) throw new Error("PixasticEditor::getDocument(): Editor is not running");

			return doc;
		},

		validSaveFormats : function() {
			return saveFormats;
		},

		getOriginalImage : function() {
			if (!isRunning) throw new Error("PixasticEditor::getOriginalImage(): Editor is not running");
			return originalImageElement;
		},

		getDataURI : function(mime) {
			if (!isRunning) throw new Error("PixasticEditor::getDataURI(): Editor is not running");

			if (!($imageCanvas && $imageCanvas.get && $imageCanvas.get(0)))
				throw new Error(errorDialog("$imageCanvas doesn't exist", "getImageCanvas"));

			return $imageCanvas.get(0).toDataURL(mime||"image/png");
		},

		getImageCanvas : function() {
			if (!isRunning) throw new Error("PixasticEditor::getImageCanvas(): Editor is not running");

			if (!($imageCanvas && $imageCanvas.get && $imageCanvas.get(0)))
				throw new Error(errorDialog("$imageCanvas doesn't exist", "getImageCanvas"));

			return $imageCanvas;
		},
		getOverlay : function() {
			if (!isRunning) throw new Error("PixasticEditor::getOverlay(): Editor is not running");

			return $j("#image-overlay", doc);
		},
		getDisplayCanvas : function() {
			if (!isRunning) throw new Error("PixasticEditor::getDisplayCanvas(): Editor is not running");

			if (!($displayCanvas && $displayCanvas.get && $displayCanvas.get(0)))
				throw new Error(errorDialog("$displayCanvas doesn't exist", "getDisplayCanvas"));
			return $displayCanvas;
		},
		getDisplayWidth : function() {
			if (!isRunning) throw new Error("PixasticEditor::getDisplayWidth(): Editor is not running");

			return displayWidth;
		},
		getDisplayHeight : function() {
			if (!isRunning) throw new Error("PixasticEditor::getDisplayHeight(): Editor is not running");

			return displayHeight;
		},
		getImageWidth : function() {
			if (!isRunning) throw new Error("PixasticEditor::getImageWidth(): Editor is not running");

			return imageWidth;
		},
		getImageHeight : function() {
			if (!isRunning) throw new Error("PixasticEditor::getImageHeight(): Editor is not running");

			return imageHeight;
		},
		errorDialog : function() {
			if (!isRunning) throw new Error("PixasticEditor::errorDialog(): Editor is not running");

			return errorDialog.apply(null, arguments);
		}
	}

})();