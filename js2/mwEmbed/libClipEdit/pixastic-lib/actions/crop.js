/*
 * Pixastic Lib - Crop - v0.1.0
 * Copyright (c) 2008 Jacob Seidelin, jseidelin@nihilogic.dk, http://blog.nihilogic.dk/
 * MIT License [http://www.opensource.org/licenses/mit-license.php]
 */

Pixastic.Actions.crop = {
	process : function(params) {
		if (Pixastic.Client.hasCanvas()) {
			var rect = params.options.rect;

			var copy = document.createElement("canvas");
			copy.width = params.width;
			copy.height = params.height;
			copy.getContext("2d").drawImage(params.canvas,0,0);

			params.canvas.width = rect.width;
			params.canvas.height = rect.height;
			params.canvas.getContext("2d").clearRect(0,0,rect.width,rect.height);

			params.canvas.getContext("2d").drawImage(copy,
				rect.left,rect.top,rect.width,rect.height,
				0,0,rect.width,rect.height
			);

			params.useData = false;
			return true;
		}
	},
	checkSupport : function() {
		return Pixastic.Client.hasCanvas();
	}
}


