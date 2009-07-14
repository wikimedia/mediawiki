/*
 * Pixastic Lib - Histogram - v0.1.0
 * Copyright (c) 2008 Jacob Seidelin, jseidelin@nihilogic.dk, http://blog.nihilogic.dk/
 * MIT License [http://www.opensource.org/licenses/mit-license.php]
 */

Pixastic.Actions.histogram = {
	process : function(params) {

		var average = !!(params.options.average);
		var paint = !!(params.options.paint);
		var color = params.options.color || "rgba(255,255,255,0.5)";
		var values = [];
		if (typeof params.options.returnValue != "object") {
			params.options.returnValue = {values:[]};
		}
		var returnValue = params.options.returnValue;
		if (typeof returnValue.values != "array") {
			returnValue.values = [];
		}
		values = returnValue.values;

		if (Pixastic.Client.hasCanvasImageData()) {
			var data = Pixastic.prepareData(params);
			params.useData = false;

			for (var i=0;i<256;i++) {
				values[i] = 0;
			}

			var rect = params.options.rect;
			var w = rect.width;
			var h = rect.height;
			var w4 = w*4;
			var y = h;
			do {
				var offsetY = (y-1)*w4;
				var x = w;
				do {
					var offset = offsetY + (x*4-4);
					var brightness = average ? 
						Math.round((data[offset]+data[offset+1]+data[offset+2])/3)
						: Math.round(data[offset]*0.3 + data[offset+1]*0.59 + data[offset+2]*0.11);
					values[brightness]++;

				} while (--x);
			} while (--y);

			if (paint) {
				var maxValue = 0;
				for (var i=0;i<256;i++) {
					if (values[i] > maxValue) {
						maxValue = values[i];
					}
				}
				var heightScale = params.height / maxValue;
				var widthScale = params.width / 256;
				var ctx = params.canvas.getContext("2d");
				ctx.fillStyle = color;
				for (var i=0;i<256;i++) {
					ctx.fillRect(
						i * widthScale, params.height - heightScale * values[i],
						widthScale, values[i] * heightScale
					);
				}
			}

			returnValue.values = values;

			return true;
		}
	},
	checkSupport : function() {
		return Pixastic.Client.hasCanvasImageData();
	}
}
