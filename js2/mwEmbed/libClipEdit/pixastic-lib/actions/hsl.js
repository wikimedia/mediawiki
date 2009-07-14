/*
 * Pixastic Lib - HSL Adjust  - v0.1.0
 * Copyright (c) 2008 Jacob Seidelin, jseidelin@nihilogic.dk, http://blog.nihilogic.dk/
 * MIT License [http://www.opensource.org/licenses/mit-license.php]
 */

Pixastic.Actions.hsl = {
	process : function(params) {

		var hue = parseInt(params.options.hue,10)||0;
		var saturation = (parseInt(params.options.saturation,10)||0) / 100;
		var lightness = (parseInt(params.options.lightness,10)||0) / 100;


		// this seems to give the same result as Photoshop
		if (saturation < 0) {
			var satMul = 1+saturation;
		} else {
			var satMul = 1+saturation*2;
		}

		hue = (hue%360) / 360;
		var hue6 = hue * 6;

		var rgbDiv = 1 / 255;

		var light255 = lightness * 255;
		var lightp1 = 1 + lightness;
		var lightm1 = 1 - lightness;
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
					var offset = offsetY + (x*4-4);

					var r = data[offset];
					var g = data[offset+1];
					var b = data[offset+2];

					if (hue != 0 || saturation != 0) {
						// ok, here comes rgb to hsl + adjust + hsl to rgb, all in one jumbled mess. 
						// It's not so pretty, but it's been optimized to get somewhat decent performance.
						// The transforms were originally adapted from the ones found in Graphics Gems, but have been heavily modified.
						var vs = r;
						if (g > vs) vs = g;
						if (b > vs) vs = b;
						var ms = r;
						if (g < ms) ms = g;
						if (b < ms) ms = b;
						var vm = (vs-ms);
						var l = (ms+vs)/255 * 0.5;
						if (l > 0) {
							if (vm > 0) {
								if (l <= 0.5) {
									var s = vm / (vs+ms) * satMul;
									if (s > 1) s = 1;
									var v = (l * (1+s));
								} else {
									var s = vm / (510-vs-ms) * satMul;
									if (s > 1) s = 1;
									var v = (l+s - l*s);
								}
								if (r == vs) {
									if (g == ms)
										var h = 5 + ((vs-b)/vm) + hue6;
									else
										var h = 1 - ((vs-g)/vm) + hue6;
								} else if (g == vs) {
									if (b == ms)
										var h = 1 + ((vs-r)/vm) + hue6;
									else
										var h = 3 - ((vs-b)/vm) + hue6;
								} else {
									if (r == ms)
										var h = 3 + ((vs-g)/vm) + hue6;
									else
										var h = 5 - ((vs-r)/vm) + hue6;
								}
								if (h < 0) h+=6;
								if (h >= 6) h-=6;
								var m = (l+l-v);
								var sextant = h>>0;
								switch (sextant) {
									case 0: r = v*255; g = (m+((v-m)*(h-sextant)))*255; b = m*255; break;
									case 1: r = (v-((v-m)*(h-sextant)))*255; g = v*255; b = m*255; break;
									case 2: r = m*255; g = v*255; b = (m+((v-m)*(h-sextant)))*255; break;
									case 3: r = m*255; g = (v-((v-m)*(h-sextant)))*255; b = v*255; break;
									case 4: r = (m+((v-m)*(h-sextant)))*255; g = m*255; b = v*255; break;
									case 5: r = v*255; g = m*255; b = (v-((v-m)*(h-sextant)))*255; break;
								}
							}
						}
					}

					if (lightness < 0) {
						r *= lightp1;
						g *= lightp1;
						b *= lightp1;
					} else if (lightness > 0) {
						r = r * lightm1 + light255;
						g = g * lightm1 + light255;
						b = b * lightm1 + light255;
					}

					if (r < 0) r = 0;
					if (g < 0) g = 0;
					if (b < 0) b = 0;
					if (r > 255) r = 255;
					if (g > 255) g = 255;
					if (b > 255) b = 255;

					data[offset] = r;
					data[offset+1] = g;
					data[offset+2] = b;

				} while (--x);
			} while (--y);
			return true;
		}
	},
	checkSupport : function() {
		return Pixastic.Client.hasCanvasImageData();
	}

}
