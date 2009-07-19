(function($) {

var PE = PixasticEditor;

PE.UI.data = {
	tabs : [
		{
			title : "Reshape",
			id : "reshape",
			actions : [
				{
					title : "Resize",
					id : "resize",
					isAction : true,
					controls : [
						{
							type : "output",
							content : "Enter new dimensions below."
						},
						{
							label : "Width",
							labelRight : "px",
							option : "width",
							type : "number", 
							range : [1,10000], 
							step : 1,
							defaultValue : function() { return PE.getImageWidth(); },
							ui : "text"
						},
						{
							label : "Height",
							labelRight : "px",
							option : "height",
							type : "number", 
							range : [1,10000], 
							step : 1,
							defaultValue : function() { return PE.getImageHeight(); },
							ui : "text"
						}
					]
				},
				{
					title : "Crop",
					id : "crop",
					isAction : true,
					controls : [
						{
							type : "output",
							content : "Enter new crop values below or use mouse to select crop area."
						},
						{
							label : "X",
							labelRight : "px",
							option : "left",
							type : "number", 
							range : [0,10000], 
							step : 1,
							defaultValue : 0,
							ui : "text"
						},
						{
							label : "Y",
							labelRight : "px",
							option : "top",
							type : "number", 
							range : [0,10000], 
							step : 1,
							defaultValue : 0,
							ui : "text"
						},
						{
							label : "Width",
							labelRight : "px",
							option : "width",
							type : "number", 
							range : [1,10000], 
							step : 1,
							defaultValue : function() { return PE.getImageWidth(); },
							ui : "text"
						},
						{
							label : "Height",
							labelRight : "px",
							option : "height",
							type : "number", 
							range : [1,10000], 
							step : 1,
							defaultValue : function() { return PE.getImageHeight(); },
							ui : "text"
						}
					],
					onactivate : function() {
						var $canvas = PE.getDisplayCanvas();
						var onchange = function(c) {
							var doc = PE.getDocument();
							$j("#input-numeric-crop-left", doc).val(c.x).change();
							$j("#input-numeric-crop-top", doc).val(c.y).change();
							$j("#input-numeric-crop-width", doc).val(c.w).change();
							$j("#input-numeric-crop-height", doc).val(c.h).change();
							$j("#input-hidden-crop-left", doc).val(c.x).change();
							$j("#input-hidden-crop-top", doc).val(c.y).change();
							$j("#input-hidden-crop-width", doc).val(c.w).change();
							$j("#input-hidden-crop-height", doc).val(c.h).change();
						}
						$canvas.data("Jcrop-onchange", onchange);
						$canvas.Jcrop({onChange:onchange}, PE.getDocument());
					},
					ondeactivate : function() {
						var $canvas = PE.getDisplayCanvas();
						if ($canvas.data("Jcrop") && $canvas.data("Jcrop").destroy)
							$canvas.data("Jcrop").destroy();
					},
					onafteraction : function(action, isPreview) {
						action.ondeactivate();
						action.onactivate();
						/*
						var $canvas = PE.getDisplayCanvas();
						if ($canvas.data("Jcrop") && $canvas.data("Jcrop").destroy)
							$canvas.data("Jcrop").destroy();
						var onchange = $canvas.data("Jcrop-onchange");
						$canvas.Jcrop({onChange:onchange});
						*/
					}
				},
				{
					title : "Rotate",
					id : "rotate",
					isAction : true,
					preview : true,
					forcePreview : true,
					controls : [
						{
							type : "output",
							content : "Enter the angle (-180&deg; to 180&deg;) you want to rotate the picture. Use negative values for clockwise rotation, positive for counterclockwise."
						},
						{
							label : "Angle",
							labelRight : "&deg;",
							option : "angle",
							type : "number", 
							range : [-180,180], 
							step : 1,
							defaultValue : 0,
							ui : "text"
						}
					],
					onactivate : function() {
						var doc = PE.getDocument();
						var $displayCanvas = PE.getDisplayCanvas();
						var dim = Math.min($displayCanvas.attr("height"), 200);
						var $canvas = $j("<canvas></canvas>", doc);
						PE.getOverlay().append($canvas);

						$canvas.attr("width", dim);
						$canvas.attr("height", dim);
						$canvas.width(dim);
						$canvas.height(dim);

						$canvas.css("marginTop", (($displayCanvas.attr("height") - dim) * 0.5) + "px");

						var lineWidth = 20;
						var radius = dim/2 - lineWidth;
						if (radius < 1) radius = 1;

						var ctx = $canvas.get(0).getContext("2d");
						ctx.beginPath()
						ctx.arc(dim/2, dim/2, radius, 0, Math.PI*2, true);
						ctx.closePath();
						ctx.fillStyle = "rgba(200,200,200,0.2)";
						ctx.fill();
						ctx.strokeStyle = "rgba(200,200,200,0.5)";
						ctx.lineWidth = 20;
						ctx.stroke();

						$j("#image-area", doc).css("cursor", "move");

						$overlay = PE.getOverlay();

						$canvas.get(0).ondragstart = function() {return false;}
						$canvas.get(0).onselectstart = function() {return false;}

						var mx = 0, my = 0;
						var startMouseAngle = 0;
						var startAngle = 0;
						var deltaAngle = 0;
						var angle = 0;

						var mouseIsDown = false;
						var onmousedown = function(e) {
							mouseIsDown = true;
							var offset = $displayCanvas.offset();
							mx = (e.pageX - offset.left) - $displayCanvas.attr("width")*0.5;
							my = (e.pageY - offset.top) - $displayCanvas.attr("height")*0.5;
							startMouseAngle = Math.atan2(my, mx);
							startAngle = parseInt($j("#input-numeric-rotate-angle", doc).val(), 10) * Math.PI / 180;
						}
						var onmousemove = function(e) {
							if (!mouseIsDown) return;

							var offset = $displayCanvas.offset();
							mx = (e.pageX - offset.left) - $displayCanvas.attr("width")*0.5;
							my = (e.pageY - offset.top) - $displayCanvas.attr("height")*0.5;
							deltaAngle = Math.atan2(my, mx) - startMouseAngle;
							angle = startAngle - deltaAngle;
							if (angle < -Math.PI) angle += 2*Math.PI;
							if (angle > Math.PI) angle -= 2*Math.PI;
							$j("#input-numeric-rotate-angle", doc).val(Math.round(angle * 180 / Math.PI));
							$j("#input-numeric-rotate-angle", doc).change();
						}
						var onmouseup = function() {
							mouseIsDown = false;
						}

						$j("#image-area", doc).bind("mousedown", onmousedown);
						$j("#image-area", doc).bind("mousemove", onmousemove);
						$j("#image-area", doc).bind("mouseup", onmouseup);
						$canvas.data("onmousedown", onmousedown);
						$canvas.data("onmousemove", onmousemove);
						$canvas.data("onmouseup", onmouseup);
						$displayCanvas.data("rotateCanvas", $canvas);
					},
					ondeactivate : function() {
						var doc = PE.getDocument();
						var $displayCanvas = PE.getDisplayCanvas();
						$overlay = PE.getOverlay();
						$j("#image-area", doc).css("cursor", "default");

						var $canvas = $displayCanvas.data("rotateCanvas");

						$j("#image-area", doc).unbind("mousedown", $canvas.data("onmousedown"));
						$j("#image-area", doc).unbind("mousemove", $canvas.data("onmousemove"));
						$j("#image-area", doc).unbind("mouseup", $canvas.data("onmouseup"));
						$displayCanvas.removeData("rotateCanvas");
						$canvas.remove();
					},
					onafteraction : function(action, isPreview) {
						if (!isPreview) { // rebuild the rotate widget
							action.ondeactivate();
							action.onactivate();
						}
					},
					onoverlayupdate : function() {
						var $canvas = PE.getDisplayCanvas().data("rotateCanvas");
						if ($canvas) {
							$canvas.css("marginTop", ((PE.getDisplayCanvas().get(0).height - $canvas.get(0).height) * 0.5) + "px");
						}
					}
				},
				{
					title : "Flip",
					id : "flip",
					isAction : true,
					controls : [
						{
							label : "Axis",
							option : "axis",
							type : "string", 
							values : [
								{name:"Horizontal", value:"horizontal"},
								{name:"Vertical", value:"vertical"}
							],
							defaultValue : "vertical",
							ui : "select"
						}
					]
				}
			]
		},
		{
			title : "Develop",
			id : "develop",
			actions : [
				{
					title : "Brightness & Contrast",
					id : "brightness",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "Use the sliders below to adjust the brightness and/or contrast of the image."
						},
						{
							label : "Brightness",
							option : "brightness",
							type : "number", 
							range : [-100,100], 
							defaultValue : 0,
							ui : "slider",
							step : 1
						},
						{
							label : "Contrast",
							option : "contrast",
							type : "number", 
							range : [-1,1], 
							defaultValue : 0,
							ui : "slider",
							step : 0.01
						},
						{
							label : "Legacy mode",
							option : "legacy",
							type : "boolean", 
							defaultValue : false,
							ui : "checkbox"
						}
					]
				},
				{
					title : "Hue/Saturation/Lightness",
					id : "hsl",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "Use the sliders below to adjust the hue, saturation and/or lightness of the image."
						},
						{
							label : "Hue",
							option : "hue",
							type : "number", 
							range : [-180,180], 
							defaultValue : 0,
							ui : "slider",
							step : 1
						},
						{
							label : "Saturation",
							option : "saturation",
							type : "number", 
							range : [-100,100], 
							defaultValue : 0,
							ui : "slider",
							step : 1
						},
						{
							label : "Lightness",
							option : "lightness",
							type : "number", 
							range : [-100,100], 
							defaultValue : 0,
							ui : "slider",
							step : 1
						}
					]
				},
				{
					title : "Adjust colors",
					id : "coloradjust",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "Use the sliders below to shift the R, G and B channels of the image."
						},
						{
							label : "Red",
							option : "red",
							type : "number", 
							range : [-1,1], 
							defaultValue : 0,
							ui : "slider",
							step : 0.01
						},
						{
							label : "Green",
							option : "green",
							type : "number", 
							range : [-1,1], 
							defaultValue : 0,
							ui : "slider",
							step : 0.01
						},
						{
							label : "Blue",
							option : "blue",
							type : "number", 
							range : [-1,1], 
							defaultValue : 0,
							ui : "slider",
							step : 0.01
						}
					]
				},
				{
					title : "Desaturate",
					id : "desaturate",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "This will desaturate the image. Select \"Use average\" to use the average value of the R, G and B channels rather than the default mix of 30% red, 59% green and 11% blue."
						},
						{
							label : "Use average",
							option : "average",
							type : "boolean", 
							defaultValue : false,
							ui : "checkbox"
						}
					]
				},
				{
					title : "Sepia toning",
					id : "sepia",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "Applies a sepia toning effect to the image."
						}
					]
				},
				{
					title : "Invert",
					id : "invert",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "This will invert the colors of the image."
						}
					]
				},
				{
					title : "Lighten",
					id : "lighten",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "Use the slider below to lighten or darken the image."
						},
						{
							label : "Amount",
							option : "amount",
							type : "number", 
							range : [-1,1], 
							defaultValue : 0,
							ui : "slider",
							step : 0.01
						}
					]
				},
				{
					title : "Unsharp mask",
					id : "unsharpmask",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "Use the sliders below to adjust the unsharp mask parameters."
						},
						{
							label : "Amount",
							option : "amount",
							type : "number", 
							range : [0,500], 
							defaultValue : 200,
							ui : "slider",
							step : 2
						},
						{
							label : "Radius",
							option : "radius",
							type : "number", 
							range : [0,5], 
							defaultValue : 2,
							ui : "slider",
							step : 0.1
						},
						{
							label : "Threshold",
							option : "amount",
							type : "number", 
							range : [0,255], 
							defaultValue : 25,
							ui : "slider",
							step : 1
						}
					]
				}

			]
		},
		{
			title : "Effects",
			id : "effects",
			actions : [
				{
					title : "Blur",
					id : "blurfast",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "Use the slider to set the blur amount."
						},
						{
							label : "Amount",
							option : "amount",
							type : "number", 
							range : [0,1], 
							defaultValue : 0.5,
							ui : "slider",
							step : 0.01
						}
					]

				},
				{
					title : "Edge detection",
					id : "edges",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "Performs edge detection on the image."
						},
						{
							label : "Greyscale",
							option : "mono",
							type : "boolean", 
							defaultValue : false,
							ui : "checkbox"
						},
						{
							label : "Invert",
							option : "invert",
							type : "boolean", 
							defaultValue : false,
							ui : "checkbox"
						}
					]
				},
				{
					title : "Emboss",
					id : "emboss",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "Adds an emboss-like effect to the image. Use the controls below to control the appearance of the effect. Choose \"Blend\" to blend the effect with the original image."
						},
						{
							label : "Strength",
							option : "strength",
							type : "number", 
							range : [0,10], 
							defaultValue : 1,
							ui : "slider",
							step : 0.1
						},
						{
							label : "Grey level",
							option : "greyLevel",
							type : "number", 
							range : [0,255], 
							defaultValue : 180,
							ui : "slider",
							step : 1
						},
						{
							label : "Direction",
							option : "direction",
							type : "string", 
							values : [
								{name:"Top left", value:"topleft"},
								{name:"Top", value:"top"},
								{name:"Top right", value:"topright"},
								{name:"Right", value:"right"},
								{name:"Bottom right", value:"bottomright"},
								{name:"Bottom", value:"bottom"},
								{name:"Bottom left", value:"bottomleft"},
								{name:"Left", value:"left"}
							],
							defaultValue : "topleft",
							ui : "select"
						},
						{
							label : "Blend",
							option : "blend",
							type : "boolean", 
							defaultValue : false,
							ui : "checkbox"
						}
					]

				},
				{
					title : "Glow",
					id : "glow",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "Creates a glowing effect on the image."
						},
						{
							label : "Amount",
							option : "amount",
							type : "number", 
							range : [0,1], 
							defaultValue : 0.5,
							ui : "slider",
							step : 0.01
						},
						{
							label : "Radius",
							option : "radius",
							type : "number", 
							range : [0,1], 
							defaultValue : 0.5,
							ui : "slider",
							step : 0.01
						}
					]
				},
				{
					title : "Add noise",
					id : "noise",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "Add random noise to the image."
						},
						{
							label : "Amount",
							option : "amount",
							type : "number", 
							range : [0,1], 
							defaultValue : 0.5,
							ui : "slider",
							step : 0.01
						},
						{
							label : "Strength",
							option : "strength",
							type : "number", 
							range : [0,1], 
							defaultValue : 0.5,
							ui : "slider",
							step : 0.01
						},
						{
							label : "Greyscale",
							option : "mono",
							type : "boolean", 
							defaultValue : false,
							ui : "checkbox"
						}
					]
				},
				{
					title : "Remove noise",
					id : "removenoise",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "Attempts to remove noise from the image. Works best for getting rid of single pixels that stand out."
						}
					]
				},
				{
					title : "Pointillize",
					id : "pointillize",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "Paints the picture with circular points."
						},
						{
							label : "Point radius",
							option : "radius",
							type : "number", 
							range : [1,50], 
							defaultValue : 5,
							ui : "slider",
							step : 1
						},
						{
							label : "Density",
							option : "density",
							type : "number", 
							range : [0,5], 
							defaultValue : 1,
							ui : "slider",
							step : 0.01
						},
						{
							label : "Noise",
							option : "noise",
							type : "number", 
							range : [0,2], 
							defaultValue : 1,
							ui : "slider",
							step : 0.01
						},
						{
							label : "Transparent",
							option : "transparent",
							type : "boolean", 
							defaultValue : false,
							ui : "checkbox"
						}
					]
				},
				{
					title : "Posterize",
					id : "posterize",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "Reduces the number of colours to a specified number of levels."
						},
						{
							label : "Levels",
							option : "levels",
							type : "number", 
							range : [1,32], 
							defaultValue : 5,
							ui : "slider",
							step : 1
						}
					]
				},
				{
					title : "Solarize",
					id : "solarize",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "Applies a solarize effect to the image."
						}
					]
				},
				{
					title : "Mosaic",
					id : "mosaic",
					isAction : true,
					preview : true,
					controls : [
						{
							type : "output",
							content : "Creates a pixelated look."
						},
						{
							label : "Block size",
							option : "blockSize",
							type : "number", 
							range : [1,100], 
							defaultValue : 5,
							ui : "slider",
							step : 1
						}
					]
				}


			]
		},
		{
			title : "Done",
			id : "done",
			actions : [
				{
					title : "Save to page",
					id : "savepage",
					content : function($ctr) {
						var doc = PE.getDocument();
						$j("<div></div>", doc)
							.addClass("action-output-text")
							.html("This will save the image to the page.")
							.appendTo($ctr);

						var $buttonCtr = $j("<div></div>", doc).appendTo($ctr);
						var $saveButton = $j("<button></button>", doc)
							.html("Save image")
							.appendTo($buttonCtr)
							.click(function() {
								PE.saveToPage();
							});

					}
				},
				{
					title : "Save to file",
					id : "savefile",
					content : function(ctr) {
						var doc = PE.getDocument();
						$j("<div></div>", doc)
							.addClass("action-output-text")
							.html("This will save the image to your local computer.")
							.appendTo(ctr);

						var formats = PE.validSaveFormats();

						var selectHtml = "<select>";
						for (var i=0;i<formats.length;i++) {
							selectHtml += "<option value='" + formats[i].mime + "'>" + formats[i].name + "</option>";
						}
						selectHtml += "</select>";

						var selectCtr = $j("<div></div>", doc)
							.addClass("ui-select-container");


						var label = $j("<div></div>", doc)
							.addClass("ui-select-label")
							.html("Format:")
							.appendTo(selectCtr);

						var formatSelect = $j(selectHtml, doc).appendTo(selectCtr);


						selectCtr.appendTo(ctr);

						var buttonCtr = $j("<div></div>", doc).appendTo(ctr);
						var saveButton = $j("<button></button>", doc)
							.html("Save file")
							.appendTo(buttonCtr)

						saveButton.click(function() {
							var selectElement = formatSelect.get(0);
							var formatMime = selectElement.options[selectElement.selectedIndex].value;
							var dataString = PE.getDataURI(formatMime);

							var dialog = $j("<div></div>", doc)
								.attr("id", "save-dialog")
								.attr("title", "Download file")
								.html(
									"Right click the link below and select \"Save as...\" to save your file.<br/>"
									+ "<br/>"
									+ "<a href=\"" + dataString + "\">Image Link</a>"
								)
								.dialog();

							// the dialog is added outside the Pixastic container, so get it back in.
							var dialogParent = $j(dialog.get(0).parentNode);
							$j("#pixastic-editor", doc).append(dialogParent);
						});
					}
				},
				/*
				{
					title : "Upload to Flickr",
					id : "flickrupload",
					content : function($ctr) {
						var doc = PE.getDocument();

						function flickrAuthed() {
							var $text = $j("<div />", doc)
								.addClass("action-output-text")
								.html("Authorized as: " + PE.Flickr.getAuthName());

							var $buttonCtr = $j("<div></div>", doc);
							var $uploadButton = $j("<button></button>", doc)
								.html("Upload image")
								.appendTo($buttonCtr)

							$uploadButton.click(function() {
								PE.Flickr.uploadImage(PE.getDataURI());
							});

							$ctr.append($text, $buttonCtr);
						}

						var $authCtr = $j("<div />", doc).appendTo($ctr);

						$j("<div />", doc)
							.addClass("action-output-text")
							.html("If you have a Flickr account you can now upload your image to Flickr. You will need to give access to your account first. Click the button below to open an authorization window.")
							.appendTo($authCtr);

						var $buttonCtr = $j("<div></div>", doc).appendTo($authCtr);
						var $authButton = $j("<button></button>", doc)
							.html("Authenticate")
							.appendTo($buttonCtr)

						var checkButtonAdded = false;
						$authButton.click(function() {
							PE.Flickr.auth();
							if (!checkButtonAdded) {
								checkButtonAdded = true;

								var $text = $j("<div />", doc)
									.addClass("action-output-text")
									.html("Now click the button below when you have authorized access to your Flickr account.");
	
								var $buttonCtr = $j("<div></div>", doc);
	
								$authCtr.append($text, $buttonCtr);
	
								var $checkButton = $j("<button></button>", doc)
									.html("I have authenticated!")
									.appendTo($buttonCtr);
	
								$checkButton.click(function() {
									PE.Flickr.checkAuth(function(res) {
										if (res.stat == "ok") {
											$authCtr.remove();
											flickrAuthed();
										}
									});
								});
							}

						});
					}
				},
				*/
				{
					title : "Quit",
					id : "quit", 
					content : function(ctr) {
						var doc = PE.getDocument();

						$j("<div>Are you sure you want to quit?</div>", doc)
							.addClass("action-output-text")
							.appendTo(ctr);
						var $buttonCtr = $j("<div></div>", doc).appendTo(ctr);

						var $quitButton = PE.UI.makeButton("Yes, quit now!")
							.appendTo($buttonCtr)

						$quitButton.click(function() {
							PE.unload();
						});

						var $saveButton = PE.UI.makeButton("Save to page and quit")
							.appendTo($buttonCtr)
							.click(function() {
								PE.saveToPage();
								PE.unload();
							});
					}
				}
			]
		}
	]
};


})(PixasticEditor.jQuery);