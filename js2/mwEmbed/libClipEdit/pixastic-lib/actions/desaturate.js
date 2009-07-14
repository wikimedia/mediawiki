/*
 * Pixastic Lib - Desaturation filter - v0.1.0
 * Copyright (c) 2008 Jacob Seidelin, jseidelin@nihilogic.dk, http://blog.nihilogic.dk/
 * MIT License [http://www.opensource.org/licenses/mit-license.php]
 */

Pixastic.Actions.desaturate = {

	process : function(params) {
		var useAverage = !!params.options.average;

		if (Pixastic.Client.hasCanvasImageData()) {
			var data = Pixastic.prepareData(params);
			var rect = params.options.rect;
			var w = rect.width;
			var h = rect.height;
			var w4 = w*4;
			var y = h;
			do {
				var offsetY = (y-1)*w4;
				var x = w;
				do {
					var offset = offsetY + (x-1)*4;
					var brightness = useAverage ?
						(data[offset]+data[offset+1]+data[offset+2])/3
						: (data[offset]*0.3 + data[offset+1]*0.59 + data[offset+2]*0.11);
					data[offset] = data[offset+1] = data[offset+2] = brightness;
				} while (--x);
			} while (--y);
			return true;
		} else if (Pixastic.Client.isIE()) {
			params.image.style.filter += " gray";
			return true;
		}
	},
	checkSupport : function() {
		return (Pixastic.Client.hasCanvasImageData() || Pixastic.Client.isIE());
	}
}