<?php
/**
 *  Copyright 2016 JetBrains
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
// Start of imagick v.3.2.0RC1

class ImagickException extends Exception  {
}

class ImagickDrawException extends Exception  {
}

class ImagickPixelIteratorException extends Exception  {
}

class ImagickPixelException extends Exception  {
}

/**
 * @method Imagick clone() (PECL imagick 2.0.0)<br/>Makes an exact copy of the Imagick object
 * @link http://php.net/manual/en/class.imagick.php
 */
class Imagick implements Iterator, Traversable, Countable {
	const COLOR_BLACK = 11;
	const COLOR_BLUE = 12;
	const COLOR_CYAN = 13;
	const COLOR_GREEN = 14;
	const COLOR_RED = 15;
	const COLOR_YELLOW = 16;
	const COLOR_MAGENTA = 17;
	const COLOR_OPACITY = 18;
	const COLOR_ALPHA = 19;
	const COLOR_FUZZ = 20;
	const IMAGICK_EXTNUM = 30200;
	const IMAGICK_EXTVER = "3.2.0RC1";
	const QUANTUM_RANGE = 65535;
	const USE_ZEND_MM = 0;
	const COMPOSITE_DEFAULT = 40;
	const COMPOSITE_UNDEFINED = 0;
	const COMPOSITE_NO = 1;
	const COMPOSITE_ADD = 2;
	const COMPOSITE_ATOP = 3;
	const COMPOSITE_BLEND = 4;
	const COMPOSITE_BUMPMAP = 5;
	const COMPOSITE_CLEAR = 7;
	const COMPOSITE_COLORBURN = 8;
	const COMPOSITE_COLORDODGE = 9;
	const COMPOSITE_COLORIZE = 10;
	const COMPOSITE_COPYBLACK = 11;
	const COMPOSITE_COPYBLUE = 12;
	const COMPOSITE_COPY = 13;
	const COMPOSITE_COPYCYAN = 14;
	const COMPOSITE_COPYGREEN = 15;
	const COMPOSITE_COPYMAGENTA = 16;
	const COMPOSITE_COPYOPACITY = 17;
	const COMPOSITE_COPYRED = 18;
	const COMPOSITE_COPYYELLOW = 19;
	const COMPOSITE_DARKEN = 20;
	const COMPOSITE_DSTATOP = 21;
	const COMPOSITE_DST = 22;
	const COMPOSITE_DSTIN = 23;
	const COMPOSITE_DSTOUT = 24;
	const COMPOSITE_DSTOVER = 25;
	const COMPOSITE_DIFFERENCE = 26;
	const COMPOSITE_DISPLACE = 27;
	const COMPOSITE_DISSOLVE = 28;
	const COMPOSITE_EXCLUSION = 29;
	const COMPOSITE_HARDLIGHT = 30;
	const COMPOSITE_HUE = 31;
	const COMPOSITE_IN = 32;
	const COMPOSITE_LIGHTEN = 33;
	const COMPOSITE_LUMINIZE = 35;
	const COMPOSITE_MINUS = 36;
	const COMPOSITE_MODULATE = 37;
	const COMPOSITE_MULTIPLY = 38;
	const COMPOSITE_OUT = 39;
	const COMPOSITE_OVER = 40;
	const COMPOSITE_OVERLAY = 41;
	const COMPOSITE_PLUS = 42;
	const COMPOSITE_REPLACE = 43;
	const COMPOSITE_SATURATE = 44;
	const COMPOSITE_SCREEN = 45;
	const COMPOSITE_SOFTLIGHT = 46;
	const COMPOSITE_SRCATOP = 47;
	const COMPOSITE_SRC = 48;
	const COMPOSITE_SRCIN = 49;
	const COMPOSITE_SRCOUT = 50;
	const COMPOSITE_SRCOVER = 51;
	const COMPOSITE_SUBTRACT = 52;
	const COMPOSITE_THRESHOLD = 53;
	const COMPOSITE_XOR = 54;
	const COMPOSITE_CHANGEMASK = 6;
	const COMPOSITE_LINEARLIGHT = 34;
	const COMPOSITE_DIVIDE = 55;
	const COMPOSITE_DISTORT = 56;
	const COMPOSITE_BLUR = 57;
	const COMPOSITE_PEGTOPLIGHT = 58;
	const COMPOSITE_VIVIDLIGHT = 59;
	const COMPOSITE_PINLIGHT = 60;
	const COMPOSITE_LINEARDODGE = 61;
	const COMPOSITE_LINEARBURN = 62;
	const COMPOSITE_MATHEMATICS = 63;
	const COMPOSITE_MODULUSADD = 2;
	const COMPOSITE_MODULUSSUBTRACT = 52;
	const COMPOSITE_MINUSDST = 36;
	const COMPOSITE_DIVIDEDST = 55;
	const COMPOSITE_DIVIDESRC = 64;
	const COMPOSITE_MINUSSRC = 65;
	const COMPOSITE_DARKENINTENSITY = 66;
	const COMPOSITE_LIGHTENINTENSITY = 67;
	const MONTAGEMODE_FRAME = 1;
	const MONTAGEMODE_UNFRAME = 2;
	const MONTAGEMODE_CONCATENATE = 3;
	const STYLE_NORMAL = 1;
	const STYLE_ITALIC = 2;
	const STYLE_OBLIQUE = 3;
	const STYLE_ANY = 4;
	const FILTER_UNDEFINED = 0;
	const FILTER_POINT = 1;
	const FILTER_BOX = 2;
	const FILTER_TRIANGLE = 3;
	const FILTER_HERMITE = 4;
	const FILTER_HANNING = 5;
	const FILTER_HAMMING = 6;
	const FILTER_BLACKMAN = 7;
	const FILTER_GAUSSIAN = 8;
	const FILTER_QUADRATIC = 9;
	const FILTER_CUBIC = 10;
	const FILTER_CATROM = 11;
	const FILTER_MITCHELL = 12;
	const FILTER_LANCZOS = 22;
	const FILTER_BESSEL = 13;
	const FILTER_SINC = 14;
	const FILTER_KAISER = 16;
	const FILTER_WELSH = 17;
	const FILTER_PARZEN = 18;
	const FILTER_LAGRANGE = 21;
	const FILTER_SENTINEL = 30;
	const FILTER_BOHMAN = 19;
	const FILTER_BARTLETT = 20;
	const FILTER_JINC = 13;
	const FILTER_SINCFAST = 15;
	const FILTER_ROBIDOUX = 26;
	const FILTER_LANCZOSSHARP = 23;
	const FILTER_LANCZOS2 = 24;
	const FILTER_LANCZOS2SHARP = 25;
	const FILTER_ROBIDOUXSHARP = 27;
	const FILTER_COSINE = 28;
	const IMGTYPE_UNDEFINED = 0;
	const IMGTYPE_BILEVEL = 1;
	const IMGTYPE_GRAYSCALE = 2;
	const IMGTYPE_GRAYSCALEMATTE = 3;
	const IMGTYPE_PALETTE = 4;
	const IMGTYPE_PALETTEMATTE = 5;
	const IMGTYPE_TRUECOLOR = 6;
	const IMGTYPE_TRUECOLORMATTE = 7;
	const IMGTYPE_COLORSEPARATION = 8;
	const IMGTYPE_COLORSEPARATIONMATTE = 9;
	const IMGTYPE_OPTIMIZE = 10;
	const IMGTYPE_PALETTEBILEVELMATTE = 11;
	const RESOLUTION_UNDEFINED = 0;
	const RESOLUTION_PIXELSPERINCH = 1;
	const RESOLUTION_PIXELSPERCENTIMETER = 2;
	const COMPRESSION_UNDEFINED = 0;
	const COMPRESSION_NO = 1;
	const COMPRESSION_BZIP = 2;
	const COMPRESSION_FAX = 6;
	const COMPRESSION_GROUP4 = 7;
	const COMPRESSION_JPEG = 8;
	const COMPRESSION_JPEG2000 = 9;
	const COMPRESSION_LOSSLESSJPEG = 10;
	const COMPRESSION_LZW = 11;
	const COMPRESSION_RLE = 12;
	const COMPRESSION_ZIP = 13;
	const COMPRESSION_DXT1 = 3;
	const COMPRESSION_DXT3 = 4;
	const COMPRESSION_DXT5 = 5;
	const COMPRESSION_ZIPS = 14;
	const COMPRESSION_PIZ = 15;
	const COMPRESSION_PXR24 = 16;
	const COMPRESSION_B44 = 17;
	const COMPRESSION_B44A = 18;
	const COMPRESSION_LZMA = 19;
	const COMPRESSION_JBIG1 = 20;
	const COMPRESSION_JBIG2 = 21;
	const PAINT_POINT = 1;
	const PAINT_REPLACE = 2;
	const PAINT_FLOODFILL = 3;
	const PAINT_FILLTOBORDER = 4;
	const PAINT_RESET = 5;
	const GRAVITY_NORTHWEST = 1;
	const GRAVITY_NORTH = 2;
	const GRAVITY_NORTHEAST = 3;
	const GRAVITY_WEST = 4;
	const GRAVITY_CENTER = 5;
	const GRAVITY_EAST = 6;
	const GRAVITY_SOUTHWEST = 7;
	const GRAVITY_SOUTH = 8;
	const GRAVITY_SOUTHEAST = 9;
	const STRETCH_NORMAL = 1;
	const STRETCH_ULTRACONDENSED = 2;
	const STRETCH_CONDENSED = 4;
	const STRETCH_SEMICONDENSED = 5;
	const STRETCH_SEMIEXPANDED = 6;
	const STRETCH_EXPANDED = 7;
	const STRETCH_EXTRAEXPANDED = 8;
	const STRETCH_ULTRAEXPANDED = 9;
	const STRETCH_ANY = 10;
	const ALIGN_UNDEFINED = 0;
	const ALIGN_LEFT = 1;
	const ALIGN_CENTER = 2;
	const ALIGN_RIGHT = 3;
	const DECORATION_NO = 1;
	const DECORATION_UNDERLINE = 2;
	const DECORATION_OVERLINE = 3;
	const DECORATION_LINETROUGH = 4;
	const NOISE_UNIFORM = 1;
	const NOISE_GAUSSIAN = 2;
	const NOISE_MULTIPLICATIVEGAUSSIAN = 3;
	const NOISE_IMPULSE = 4;
	const NOISE_LAPLACIAN = 5;
	const NOISE_POISSON = 6;
	const NOISE_RANDOM = 7;
	const CHANNEL_UNDEFINED = 0;
	const CHANNEL_RED = 1;
	const CHANNEL_GRAY = 128;
	const CHANNEL_CYAN = 1;
	const CHANNEL_GREEN = 2;
	const CHANNEL_MAGENTA = 2;
	const CHANNEL_BLUE = 4;
	const CHANNEL_YELLOW = 4;
	const CHANNEL_ALPHA = 8;
	const CHANNEL_OPACITY = 8;
	const CHANNEL_MATTE = 8;
	const CHANNEL_BLACK = 32;
	const CHANNEL_INDEX = 32;
	const CHANNEL_ALL = 134217727;
	const CHANNEL_DEFAULT = 134217719;
	const CHANNEL_TRUEALPHA = 64;
	const CHANNEL_RGBS = 128;
	const CHANNEL_SYNC = 256;
	const CHANNEL_COMPOSITES = 47;
	const METRIC_UNDEFINED = 0;
	const METRIC_MEANABSOLUTEERROR = 2;
	const METRIC_MEANSQUAREERROR = 4;
	const METRIC_PEAKABSOLUTEERROR = 5;
	const METRIC_PEAKSIGNALTONOISERATIO = 6;
	const METRIC_ROOTMEANSQUAREDERROR = 7;
	const PIXEL_CHAR = 1;
	const PIXEL_DOUBLE = 2;
	const PIXEL_FLOAT = 3;
	const PIXEL_INTEGER = 4;
	const PIXEL_LONG = 5;
	const PIXEL_QUANTUM = 6;
	const PIXEL_SHORT = 7;
	const EVALUATE_UNDEFINED = 0;
	const EVALUATE_ADD = 1;
	const EVALUATE_AND = 2;
	const EVALUATE_DIVIDE = 3;
	const EVALUATE_LEFTSHIFT = 4;
	const EVALUATE_MAX = 5;
	const EVALUATE_MIN = 6;
	const EVALUATE_MULTIPLY = 7;
	const EVALUATE_OR = 8;
	const EVALUATE_RIGHTSHIFT = 9;
	const EVALUATE_SET = 10;
	const EVALUATE_SUBTRACT = 11;
	const EVALUATE_XOR = 12;
	const EVALUATE_POW = 13;
	const EVALUATE_LOG = 14;
	const EVALUATE_THRESHOLD = 15;
	const EVALUATE_THRESHOLDBLACK = 16;
	const EVALUATE_THRESHOLDWHITE = 17;
	const EVALUATE_GAUSSIANNOISE = 18;
	const EVALUATE_IMPULSENOISE = 19;
	const EVALUATE_LAPLACIANNOISE = 20;
	const EVALUATE_MULTIPLICATIVENOISE = 21;
	const EVALUATE_POISSONNOISE = 22;
	const EVALUATE_UNIFORMNOISE = 23;
	const EVALUATE_COSINE = 24;
	const EVALUATE_SINE = 25;
	const EVALUATE_ADDMODULUS = 26;
	const EVALUATE_MEAN = 27;
	const EVALUATE_ABS = 28;
	const EVALUATE_EXPONENTIAL = 29;
	const EVALUATE_MEDIAN = 30;
	const EVALUATE_SUM = 31;
	const COLORSPACE_UNDEFINED = 0;
	const COLORSPACE_RGB = 1;
	const COLORSPACE_GRAY = 2;
	const COLORSPACE_TRANSPARENT = 3;
	const COLORSPACE_OHTA = 4;
	const COLORSPACE_LAB = 5;
	const COLORSPACE_XYZ = 6;
	const COLORSPACE_YCBCR = 7;
	const COLORSPACE_YCC = 8;
	const COLORSPACE_YIQ = 9;
	const COLORSPACE_YPBPR = 10;
	const COLORSPACE_YUV = 11;
	const COLORSPACE_CMYK = 12;
	const COLORSPACE_SRGB = 13;
	const COLORSPACE_HSB = 14;
	const COLORSPACE_HSL = 15;
	const COLORSPACE_HWB = 16;
	const COLORSPACE_REC601LUMA = 17;
	const COLORSPACE_REC709LUMA = 19;
	const COLORSPACE_LOG = 21;
	const COLORSPACE_CMY = 22;
	const VIRTUALPIXELMETHOD_UNDEFINED = 0;
	const VIRTUALPIXELMETHOD_BACKGROUND = 1;
	const VIRTUALPIXELMETHOD_CONSTANT = 2;
	const VIRTUALPIXELMETHOD_EDGE = 4;
	const VIRTUALPIXELMETHOD_MIRROR = 5;
	const VIRTUALPIXELMETHOD_TILE = 7;
	const VIRTUALPIXELMETHOD_TRANSPARENT = 8;
	const VIRTUALPIXELMETHOD_MASK = 9;
	const VIRTUALPIXELMETHOD_BLACK = 10;
	const VIRTUALPIXELMETHOD_GRAY = 11;
	const VIRTUALPIXELMETHOD_WHITE = 12;
	const VIRTUALPIXELMETHOD_HORIZONTALTILE = 13;
	const VIRTUALPIXELMETHOD_VERTICALTILE = 14;
	const VIRTUALPIXELMETHOD_HORIZONTALTILEEDGE = 15;
	const VIRTUALPIXELMETHOD_VERTICALTILEEDGE = 16;
	const VIRTUALPIXELMETHOD_CHECKERTILE = 17;
	const PREVIEW_UNDEFINED = 0;
	const PREVIEW_ROTATE = 1;
	const PREVIEW_SHEAR = 2;
	const PREVIEW_ROLL = 3;
	const PREVIEW_HUE = 4;
	const PREVIEW_SATURATION = 5;
	const PREVIEW_BRIGHTNESS = 6;
	const PREVIEW_GAMMA = 7;
	const PREVIEW_SPIFF = 8;
	const PREVIEW_DULL = 9;
	const PREVIEW_GRAYSCALE = 10;
	const PREVIEW_QUANTIZE = 11;
	const PREVIEW_DESPECKLE = 12;
	const PREVIEW_REDUCENOISE = 13;
	const PREVIEW_ADDNOISE = 14;
	const PREVIEW_SHARPEN = 15;
	const PREVIEW_BLUR = 16;
	const PREVIEW_THRESHOLD = 17;
	const PREVIEW_EDGEDETECT = 18;
	const PREVIEW_SPREAD = 19;
	const PREVIEW_SOLARIZE = 20;
	const PREVIEW_SHADE = 21;
	const PREVIEW_RAISE = 22;
	const PREVIEW_SEGMENT = 23;
	const PREVIEW_SWIRL = 24;
	const PREVIEW_IMPLODE = 25;
	const PREVIEW_WAVE = 26;
	const PREVIEW_OILPAINT = 27;
	const PREVIEW_CHARCOALDRAWING = 28;
	const PREVIEW_JPEG = 29;
	const RENDERINGINTENT_UNDEFINED = 0;
	const RENDERINGINTENT_SATURATION = 1;
	const RENDERINGINTENT_PERCEPTUAL = 2;
	const RENDERINGINTENT_ABSOLUTE = 3;
	const RENDERINGINTENT_RELATIVE = 4;
	const INTERLACE_UNDEFINED = 0;
	const INTERLACE_NO = 1;
	const INTERLACE_LINE = 2;
	const INTERLACE_PLANE = 3;
	const INTERLACE_PARTITION = 4;
	const INTERLACE_GIF = 5;
	const INTERLACE_JPEG = 6;
	const INTERLACE_PNG = 7;
	const FILLRULE_UNDEFINED = 0;
	const FILLRULE_EVENODD = 1;
	const FILLRULE_NONZERO = 2;
	const PATHUNITS_UNDEFINED = 0;
	const PATHUNITS_USERSPACE = 1;
	const PATHUNITS_USERSPACEONUSE = 2;
	const PATHUNITS_OBJECTBOUNDINGBOX = 3;
	const LINECAP_UNDEFINED = 0;
	const LINECAP_BUTT = 1;
	const LINECAP_ROUND = 2;
	const LINECAP_SQUARE = 3;
	const LINEJOIN_UNDEFINED = 0;
	const LINEJOIN_MITER = 1;
	const LINEJOIN_ROUND = 2;
	const LINEJOIN_BEVEL = 3;
	const RESOURCETYPE_UNDEFINED = 0;
	const RESOURCETYPE_AREA = 1;
	const RESOURCETYPE_DISK = 2;
	const RESOURCETYPE_FILE = 3;
	const RESOURCETYPE_MAP = 4;
	const RESOURCETYPE_MEMORY = 5;
	const DISPOSE_UNRECOGNIZED = 0;
	const DISPOSE_UNDEFINED = 0;
	const DISPOSE_NONE = 1;
	const DISPOSE_BACKGROUND = 2;
	const DISPOSE_PREVIOUS = 3;
	const INTERPOLATE_UNDEFINED = 0;
	const INTERPOLATE_AVERAGE = 1;
	const INTERPOLATE_BICUBIC = 2;
	const INTERPOLATE_BILINEAR = 3;
	const INTERPOLATE_FILTER = 4;
	const INTERPOLATE_INTEGER = 5;
	const INTERPOLATE_MESH = 6;
	const INTERPOLATE_NEARESTNEIGHBOR = 7;
	const INTERPOLATE_SPLINE = 8;
	const LAYERMETHOD_UNDEFINED = 0;
	const LAYERMETHOD_COALESCE = 1;
	const LAYERMETHOD_COMPAREANY = 2;
	const LAYERMETHOD_COMPARECLEAR = 3;
	const LAYERMETHOD_COMPAREOVERLAY = 4;
	const LAYERMETHOD_DISPOSE = 5;
	const LAYERMETHOD_OPTIMIZE = 6;
	const LAYERMETHOD_OPTIMIZEPLUS = 8;
	const LAYERMETHOD_OPTIMIZETRANS = 9;
	const LAYERMETHOD_COMPOSITE = 12;
	const LAYERMETHOD_OPTIMIZEIMAGE = 7;
	const LAYERMETHOD_REMOVEDUPS = 10;
	const LAYERMETHOD_REMOVEZERO = 11;
	const LAYERMETHOD_MERGE = 13;
	const LAYERMETHOD_FLATTEN = 14;
	const LAYERMETHOD_MOSAIC = 15;
	const LAYERMETHOD_TRIMBOUNDS = 16;
	const ORIENTATION_UNDEFINED = 0;
	const ORIENTATION_TOPLEFT = 1;
	const ORIENTATION_TOPRIGHT = 2;
	const ORIENTATION_BOTTOMRIGHT = 3;
	const ORIENTATION_BOTTOMLEFT = 4;
	const ORIENTATION_LEFTTOP = 5;
	const ORIENTATION_RIGHTTOP = 6;
	const ORIENTATION_RIGHTBOTTOM = 7;
	const ORIENTATION_LEFTBOTTOM = 8;
	const DISTORTION_UNDEFINED = 0;
	const DISTORTION_AFFINE = 1;
	const DISTORTION_AFFINEPROJECTION = 2;
	const DISTORTION_ARC = 9;
	const DISTORTION_BILINEAR = 6;
	const DISTORTION_PERSPECTIVE = 4;
	const DISTORTION_PERSPECTIVEPROJECTION = 5;
	const DISTORTION_SCALEROTATETRANSLATE = 3;
	const DISTORTION_POLYNOMIAL = 8;
	const DISTORTION_POLAR = 10;
	const DISTORTION_DEPOLAR = 11;
	const DISTORTION_BARREL = 14;
	const DISTORTION_BARRELINVERSE = 15;
	const DISTORTION_SHEPARDS = 16;
	const DISTORTION_SENTINEL = 18;
	const DISTORTION_BILINEARFORWARD = 6;
	const DISTORTION_BILINEARREVERSE = 7;
	const DISTORTION_RESIZE = 17;
	const DISTORTION_CYLINDER2PLANE = 12;
	const DISTORTION_PLANE2CYLINDER = 13;
	const ALPHACHANNEL_ACTIVATE = 1;
	const ALPHACHANNEL_DEACTIVATE = 4;
	const ALPHACHANNEL_RESET = 7;
	const ALPHACHANNEL_SET = 8;
	const ALPHACHANNEL_UNDEFINED = 0;
	const ALPHACHANNEL_COPY = 3;
	const ALPHACHANNEL_EXTRACT = 5;
	const ALPHACHANNEL_OPAQUE = 6;
	const ALPHACHANNEL_SHAPE = 9;
	const ALPHACHANNEL_TRANSPARENT = 10;
	const SPARSECOLORMETHOD_UNDEFINED = 0;
	const SPARSECOLORMETHOD_BARYCENTRIC = 1;
	const SPARSECOLORMETHOD_BILINEAR = 7;
	const SPARSECOLORMETHOD_POLYNOMIAL = 8;
	const SPARSECOLORMETHOD_SPEPARDS = 16;
	const SPARSECOLORMETHOD_VORONOI = 18;
	const DITHERMETHOD_UNDEFINED = 0;
	const DITHERMETHOD_NO = 1;
	const DITHERMETHOD_RIEMERSMA = 2;
	const DITHERMETHOD_FLOYDSTEINBERG = 3;
	const FUNCTION_UNDEFINED = 0;
	const FUNCTION_POLYNOMIAL = 1;
	const FUNCTION_SINUSOID = 2;


	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Removes repeated portions of images to optimize
	 * @link http://php.net/manual/en/imagick.optimizeimagelayers.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function optimizeImageLayers () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the maximum bounding region between images
	 * @link http://php.net/manual/en/imagick.compareimagelayers.php
	 * @param int $method <p>
	 * One of the layer method constants.
	 * </p>
	 * @return Imagick <b>TRUE</b> on success.
	 */
	public function compareImageLayers ($method) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Quickly fetch attributes
	 * @link http://php.net/manual/en/imagick.pingimageblob.php
	 * @param string $image <p>
	 * A string containing the image.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function pingImageBlob ($image) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Get basic image attributes in a lightweight manner
	 * @link http://php.net/manual/en/imagick.pingimagefile.php
	 * @param resource $filehandle <p>
	 * An open filehandle to the image.
	 * </p>
	 * @param string $fileName [optional] <p>
	 * Optional filename for this image.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function pingImageFile ($filehandle, $fileName = null) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Creates a vertical mirror image
	 * @link http://php.net/manual/en/imagick.transposeimage.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function transposeImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Creates a horizontal mirror image
	 * @link http://php.net/manual/en/imagick.transverseimage.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function transverseImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Remove edges from the image
	 * @link http://php.net/manual/en/imagick.trimimage.php
	 * @param float $fuzz <p>
	 * By default target must match a particular pixel color exactly.
	 * However, in many cases two colors may differ by a small amount.
	 * The fuzz member of image defines how much tolerance is acceptable
	 * to consider two colors as the same. This parameter represents the variation
	 * on the quantum range.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function trimImage ($fuzz) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Applies wave filter to the image
	 * @link http://php.net/manual/en/imagick.waveimage.php
	 * @param float $amplitude <p>
	 * The amplitude of the wave.
	 * </p>
	 * @param float $length <p>
	 * The length of the wave.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function waveImage ($amplitude, $length) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adds vignette filter to the image
	 * @link http://php.net/manual/en/imagick.vignetteimage.php
	 * @param float $blackPoint <p>
	 * The black point.
	 * </p>
	 * @param float $whitePoint <p>
	 * The white point
	 * </p>
	 * @param int $x <p>
	 * X offset of the ellipse
	 * </p>
	 * @param int $y <p>
	 * Y offset of the ellipse
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function vignetteImage ($blackPoint, $whitePoint, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Discards all but one of any pixel color
	 * @link http://php.net/manual/en/imagick.uniqueimagecolors.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function uniqueImageColors () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Return if the image has a matte channel
	 * @link http://php.net/manual/en/imagick.getimagematte.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function getImageMatte () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image matte channel
	 * @link http://php.net/manual/en/imagick.setimagematte.php
	 * @param bool $matte <p>
	 * True activates the matte channel and false disables it.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageMatte ($matte) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adaptively resize image with data dependent triangulation
	 * @link http://php.net/manual/en/imagick.adaptiveresizeimage.php
	 * @param int $columns <p>
	 * The number of columns in the scaled image.
	 * </p>
	 * @param int $rows <p>
	 * The number of rows in the scaled image.
	 * </p>
	 * @param bool $bestfit [optional] <p>
	 * Whether to fit the image inside a bounding box.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function adaptiveResizeImage ($columns, $rows, $bestfit = false) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Simulates a pencil sketch
	 * @link http://php.net/manual/en/imagick.sketchimage.php
	 * @param float $radius <p>
	 * The radius of the Gaussian, in pixels, not counting the center pixel
	 * </p>
	 * @param float $sigma <p>
	 * The standard deviation of the Gaussian, in pixels.
	 * </p>
	 * @param float $angle <p>
	 * Apply the effect along this angle.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function sketchImage ($radius, $sigma, $angle) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Creates a 3D effect
	 * @link http://php.net/manual/en/imagick.shadeimage.php
	 * @param bool $gray <p>
	 * A value other than zero shades the intensity of each pixel.
	 * </p>
	 * @param float $azimuth <p>
	 * Defines the light source direction.
	 * </p>
	 * @param float $elevation <p>
	 * Defines the light source direction.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function shadeImage ($gray, $azimuth, $elevation) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the size offset
	 * @link http://php.net/manual/en/imagick.getsizeoffset.php
	 * @return int the size offset associated with the Imagick object.
	 */
	public function getSizeOffset () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the size and offset of the Imagick object
	 * @link http://php.net/manual/en/imagick.setsizeoffset.php
	 * @param int $columns <p>
	 * The width in pixels.
	 * </p>
	 * @param int $rows <p>
	 * The height in pixels.
	 * </p>
	 * @param int $offset <p>
	 * The image offset.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setSizeOffset ($columns, $rows, $offset) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adds adaptive blur filter to image
	 * @link http://php.net/manual/en/imagick.adaptiveblurimage.php
	 * @param float $radius <p>
	 * The radius of the Gaussian, in pixels, not counting the center pixel.
	 * Provide a value of 0 and the radius will be chosen automagically.
	 * </p>
	 * @param float $sigma <p>
	 * The standard deviation of the Gaussian, in pixels.
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To apply to more than one channel, combine channel constants using bitwise operators. Defaults to <b>Imagick::CHANNEL_DEFAULT</b>. Refer to this list of channel constants
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function adaptiveBlurImage ($radius, $sigma, $channel = Imagick::CHANNEL_DEFAULT) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Enhances the contrast of a color image
	 * @link http://php.net/manual/en/imagick.contraststretchimage.php
	 * @param float $black_point <p>
	 * The black point.
	 * </p>
	 * @param float $white_point <p>
	 * The white point.
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. <b>Imagick::CHANNEL_ALL</b>. Refer to this
	 * list of channel constants.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function contrastStretchImage ($black_point, $white_point, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adaptively sharpen the image
	 * @link http://php.net/manual/en/imagick.adaptivesharpenimage.php
	 * @param float $radius <p>
	 * The radius of the Gaussian, in pixels, not counting the center pixel. Use 0 for auto-select.
	 * </p>
	 * @param float $sigma <p>
	 * The standard deviation of the Gaussian, in pixels.
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To apply to more than one channel, combine channel constants using bitwise operators. Defaults to <b>Imagick::CHANNEL_DEFAULT</b>. Refer to this list of channel constants
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function adaptiveSharpenImage ($radius, $sigma, $channel = Imagick::CHANNEL_DEFAULT) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Creates a high-contrast, two-color image
	 * @link http://php.net/manual/en/imagick.randomthresholdimage.php
	 * @param float $low <p>
	 * The low point
	 * </p>
	 * @param float $high <p>
	 * The high point
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function randomThresholdImage ($low, $high, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * @param $xRounding
	 * @param $yRounding
	 * @param $strokeWidth [optional]
	 * @param $displace [optional]
	 * @param $sizeCorrection [optional]
	 */
	public function roundCornersImage ($xRounding, $yRounding, $strokeWidth, $displace, $sizeCorrection) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Rounds image corners
	 * @link http://php.net/manual/en/imagick.roundcorners.php
	 * @param float $x_rounding <p>
	 * x rounding
	 * </p>
	 * @param float $y_rounding <p>
	 * y rounding
	 * </p>
	 * @param float $stroke_width [optional] <p>
	 * stroke width
	 * </p>
	 * @param float $displace [optional] <p>
	 * image displace
	 * </p>
	 * @param float $size_correction [optional] <p>
	 * size correction
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function roundCorners ($x_rounding, $y_rounding, $stroke_width = 10.0, $displace = 5.0, $size_correction = -6.0) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Set the iterator position
	 * @link http://php.net/manual/en/imagick.setiteratorindex.php
	 * @param int $index <p>
	 * The position to set the iterator to
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setIteratorIndex ($index) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the index of the current active image
	 * @link http://php.net/manual/en/imagick.getiteratorindex.php
	 * @return int an integer containing the index of the image in the stack.
	 */
	public function getIteratorIndex () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Convenience method for setting crop size and the image geometry
	 * @link http://php.net/manual/en/imagick.transformimage.php
	 * @param string $crop <p>
	 * A crop geometry string. This geometry defines a subregion of the image to crop.
	 * </p>
	 * @param string $geometry <p>
	 * An image geometry string. This geometry defines the final size of the image.
	 * </p>
	 * @return Imagick <b>TRUE</b> on success.
	 */
	public function transformImage ($crop, $geometry) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image opacity level
	 * @link http://php.net/manual/en/imagick.setimageopacity.php
	 * @param float $opacity <p>
	 * The level of transparency: 1.0 is fully opaque and 0.0 is fully
	 * transparent.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageOpacity ($opacity) {}

	/**
	 * (PECL imagick 2.2.2)<br/>
	 * Performs an ordered dither
	 * @link http://php.net/manual/en/imagick.orderedposterizeimage.php
	 * @param string $threshold_map <p>
	 * A string containing the name of the threshold dither map to use
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function orderedPosterizeImage ($threshold_map, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Simulates a Polaroid picture
	 * @link http://php.net/manual/en/imagick.polaroidimage.php
	 * @param ImagickDraw $properties <p>
	 * The polaroid properties
	 * </p>
	 * @param float $angle <p>
	 * The polaroid angle
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function polaroidImage (ImagickDraw $properties, $angle) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the named image property
	 * @link http://php.net/manual/en/imagick.getimageproperty.php
	 * @param string $name <p>
	 * name of the property (for example Exif:DateTime)
	 * </p>
	 * @return string a string containing the image property, false if a
	 * property with the given name does not exist.
	 */
	public function getImageProperty ($name) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets an image property
	 * @link http://php.net/manual/en/imagick.setimageproperty.php
	 * @param string $name
	 * @param string $value
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageProperty ($name, $value) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image interpolate pixel method
	 * @link http://php.net/manual/en/imagick.setimageinterpolatemethod.php
	 * @param int $method <p>
	 * The method is one of the <b>Imagick::INTERPOLATE_*</b> constants
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageInterpolateMethod ($method) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the interpolation method
	 * @link http://php.net/manual/en/imagick.getimageinterpolatemethod.php
	 * @return int the interpolate method on success.
	 */
	public function getImageInterpolateMethod () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Stretches with saturation the image intensity
	 * @link http://php.net/manual/en/imagick.linearstretchimage.php
	 * @param float $blackPoint <p>
	 * The image black point
	 * </p>
	 * @param float $whitePoint <p>
	 * The image white point
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function linearStretchImage ($blackPoint, $whitePoint) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the image length in bytes
	 * @link http://php.net/manual/en/imagick.getimagelength.php
	 * @return int an int containing the current image size.
	 */
	public function getImageLength () {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Set image size
	 * @link http://php.net/manual/en/imagick.extentimage.php
	 * @param int $width <p>
	 * The new width
	 * </p>
	 * @param int $height <p>
	 * The new height
	 * </p>
	 * @param int $x <p>
	 * X position for the new size
	 * </p>
	 * @param int $y <p>
	 * Y position for the new size
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function extentImage ($width, $height, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the image orientation
	 * @link http://php.net/manual/en/imagick.getimageorientation.php
	 * @return int an int on success.
	 */
	public function getImageOrientation () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image orientation
	 * @link http://php.net/manual/en/imagick.setimageorientation.php
	 * @param int $orientation <p>
	 * One of the orientation constants
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageOrientation ($orientation) {}

	/**
	 * (PECL imagick 2.1.0)<br/>
	 * Changes the color value of any pixel that matches target
	 * @link http://php.net/manual/en/imagick.paintfloodfillimage.php
	 * @param mixed $fill <p>
	 * ImagickPixel object or a string containing the fill color
	 * </p>
	 * @param float $fuzz <p>
	 * The amount of fuzz. For example, set fuzz to 10 and the color red at
	 * intensities of 100 and 102 respectively are now interpreted as the
	 * same color for the purposes of the floodfill.
	 * </p>
	 * @param mixed $bordercolor <p>
	 * ImagickPixel object or a string containing the border color
	 * </p>
	 * @param int $x <p>
	 * X start position of the floodfill
	 * </p>
	 * @param int $y <p>
	 * Y start position of the floodfill
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To apply to more than one channel, combine channel constants using bitwise operators. Defaults to <b>Imagick::CHANNEL_DEFAULT</b>. Refer to this list of channel constants
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function paintFloodfillImage ($fill, $fuzz, $bordercolor, $x, $y, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Replaces colors in the image
	 * @link http://php.net/manual/en/imagick.clutimage.php
	 * @param Imagick $lookup_table <p>
	 * Imagick object containing the color lookup table
	 * </p>
	 * @param int $channel [optional] <p>
	 * The Channeltype
	 * constant. When not supplied, default channels are replaced.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function clutImage (Imagick $lookup_table, $channel = Imagick::CHANNEL_DEFAULT) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the image properties
	 * @link http://php.net/manual/en/imagick.getimageproperties.php
	 * @param string $pattern [optional] <p>
	 * The pattern for property names.
	 * </p>
	 * @param bool $only_names [optional] <p>
	 * Whether to return only property names. If <b>FALSE</b> then also the values are returned
	 * </p>
	 * @return array an array containing the image properties or property names.
	 */
	public function getImageProperties ($pattern = "*", $only_names = true) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the image profiles
	 * @link http://php.net/manual/en/imagick.getimageprofiles.php
	 * @param string $pattern [optional] <p>
	 * The pattern for profile names.
	 * </p>
	 * @param bool $only_names [optional] <p>
	 * Whether to return only profile names. If <b>FALSE</b> then values are returned as well
	 * </p>
	 * @return array an array containing the image profiles or profile names.
	 */
	public function getImageProfiles ($pattern = "*", $only_names = true) {}

	/**
	 * (PECL imagick 2.0.1)<br/>
	 * Distorts an image using various distortion methods
	 * @link http://php.net/manual/en/imagick.distortimage.php
	 * @param int $method <p>
	 * The method of image distortion. See distortion constants
	 * </p>
	 * @param array $arguments <p>
	 * The arguments for this distortion method
	 * </p>
	 * @param bool $bestfit <p>
	 * Attempt to resize destination to fit distorted source
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function distortImage ($method, array $arguments, $bestfit) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Writes an image to a filehandle
	 * @link http://php.net/manual/en/imagick.writeimagefile.php
	 * @param resource $filehandle <p>
	 * Filehandle where to write the image
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function writeImageFile ($filehandle) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Writes frames to a filehandle
	 * @link http://php.net/manual/en/imagick.writeimagesfile.php
	 * @param resource $filehandle <p>
	 * Filehandle where to write the images
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function writeImagesFile ($filehandle) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Reset image page
	 * @link http://php.net/manual/en/imagick.resetimagepage.php
	 * @param string $page <p>
	 * The page definition. For example 7168x5147+0+0
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function resetImagePage ($page) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Sets image clip mask
	 * @link http://php.net/manual/en/imagick.setimageclipmask.php
	 * @param Imagick $clip_mask <p>
	 * The Imagick object containing the clip mask
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageClipMask (Imagick $clip_mask) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Gets image clip mask
	 * @link http://php.net/manual/en/imagick.getimageclipmask.php
	 * @return Imagick an Imagick object containing the clip mask.
	 */
	public function getImageClipMask () {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Animates an image or images
	 * @link http://php.net/manual/en/imagick.animateimages.php
	 * @param string $x_server <p>
	 * X server address
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function animateImages ($x_server) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Recolors image
	 * @link http://php.net/manual/en/imagick.recolorimage.php
	 * @param array $matrix <p>
	 * The matrix containing the color values
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function recolorImage (array $matrix) {}

	/**
	 * (PECL imagick 2.1.0)<br/>
	 * Sets font
	 * @link http://php.net/manual/en/imagick.setfont.php
	 * @param string $font <p>
	 * Font name or a filename
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setFont ($font) {}

	/**
	 * (PECL imagick 2.1.0)<br/>
	 * Gets font
	 * @link http://php.net/manual/en/imagick.getfont.php
	 * @return string the string containing the font name or <b>FALSE</b> if not font is set.
	 */
	public function getFont () {}

	/**
	 * (PECL imagick 2.1.0)<br/>
	 * Sets point size
	 * @link http://php.net/manual/en/imagick.setpointsize.php
	 * @param float $point_size <p>
	 * Point size
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setPointSize ($point_size) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Gets point size
	 * @link http://php.net/manual/en/imagick.getpointsize.php
	 * @return float a float containing the point size.
	 */
	public function getPointSize () {}

	/**
	 * (PECL imagick 2.1.0)<br/>
	 * Merges image layers
	 * @link http://php.net/manual/en/imagick.mergeimagelayers.php
	 * @param int $layer_method <p>
	 * One of the <b>Imagick::LAYERMETHOD_*</b> constants
	 * </p>
	 * @return Imagick Returns an Imagick object containing the merged image.
	 * @throws ImagickException
	 */
	public function mergeImageLayers ($layer_method) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Sets image alpha channel
	 * @link http://php.net/manual/en/imagick.setimagealphachannel.php
	 * @param int $mode <p>
	 * One of the <b>Imagick::ALPHACHANNEL_*</b> constants
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageAlphaChannel ($mode) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Changes the color value of any pixel that matches target
	 * @link http://php.net/manual/en/imagick.floodfillpaintimage.php
	 * @param mixed $fill <p>
	 * ImagickPixel object or a string containing the fill color
	 * </p>
	 * @param float $fuzz <p>
	 * The amount of fuzz. For example, set fuzz to 10 and the color red at intensities of 100 and 102 respectively are now interpreted as the same color.
	 * </p>
	 * @param mixed $target <p>
	 * ImagickPixel object or a string containing the target color to paint
	 * </p>
	 * @param int $x <p>
	 * X start position of the floodfill
	 * </p>
	 * @param int $y <p>
	 * Y start position of the floodfill
	 * </p>
	 * @param bool $invert <p>
	 * If <b>TRUE</b> paints any pixel that does not match the target color.
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To apply to more than one channel, combine channel constants using bitwise operators. Defaults to <b>Imagick::CHANNEL_DEFAULT</b>. Refer to this list of channel constants
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function floodFillPaintImage ($fill, $fuzz, $target, $x, $y, $invert, $channel = Imagick::CHANNEL_DEFAULT) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Changes the color value of any pixel that matches target
	 * @link http://php.net/manual/en/imagick.opaquepaintimage.php
	 * @param mixed $target <p>
	 * ImagickPixel object or a string containing the color to change
	 * </p>
	 * @param mixed $fill <p>
	 * The replacement color
	 * </p>
	 * @param float $fuzz <p>
	 * The amount of fuzz. For example, set fuzz to 10 and the color red at intensities of 100 and 102 respectively are now interpreted as the same color.
	 * </p>
	 * @param bool $invert <p>
	 * If <b>TRUE</b> paints any pixel that does not match the target color.
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To apply to more than one channel, combine channel constants using bitwise operators. Defaults to <b>Imagick::CHANNEL_DEFAULT</b>. Refer to this list of channel constants
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function opaquePaintImage ($target, $fill, $fuzz, $invert, $channel = Imagick::CHANNEL_DEFAULT) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Paints pixels transparent
	 * @link http://php.net/manual/en/imagick.transparentpaintimage.php
	 * @param mixed $target <p>
	 * The target color to paint
	 * </p>
	 * @param float $alpha <p>
	 * The level of transparency: 1.0 is fully opaque and 0.0 is fully transparent.
	 * </p>
	 * @param float $fuzz <p>
	 * The amount of fuzz. For example, set fuzz to 10 and the color red at intensities of 100 and 102 respectively are now interpreted as the same color.
	 * </p>
	 * @param bool $invert <p>
	 * If <b>TRUE</b> paints any pixel that does not match the target color.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function transparentPaintImage ($target, $alpha, $fuzz, $invert) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Animates an image or images
	 * @link http://php.net/manual/en/imagick.liquidrescaleimage.php
	 * @param int $width <p>
	 * The width of the target size
	 * </p>
	 * @param int $height <p>
	 * The height of the target size
	 * </p>
	 * @param float $delta_x <p>
	 * How much the seam can traverse on x-axis.
	 * Passing 0 causes the seams to be straight.
	 * </p>
	 * @param float $rigidity <p>
	 * Introduces a bias for non-straight seams. This parameter is
	 * typically 0.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function liquidRescaleImage ($width, $height, $delta_x, $rigidity) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Enciphers an image
	 * @link http://php.net/manual/en/imagick.encipherimage.php
	 * @param string $passphrase <p>
	 * The passphrase
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function encipherImage ($passphrase) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Deciphers an image
	 * @link http://php.net/manual/en/imagick.decipherimage.php
	 * @param string $passphrase <p>
	 * The passphrase
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function decipherImage ($passphrase) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Sets the gravity
	 * @link http://php.net/manual/en/imagick.setgravity.php
	 * @param int $gravity <p>
	 * The gravity property. Refer to the list of
	 * gravity constants.
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setGravity ($gravity) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Gets the gravity
	 * @link http://php.net/manual/en/imagick.getgravity.php
	 * @return int the gravity property. Refer to the list of
	 * gravity constants.
	 */
	public function getGravity () {}

	/**
	 * (PECL imagick 2.2.1)<br/>
	 * Gets channel range
	 * @link http://php.net/manual/en/imagick.getimagechannelrange.php
	 * @param int $channel <p>
	 * Provide any channel constant that is valid for your channel mode. To apply to more than one channel, combine channel constants using bitwise operators. Defaults to <b>Imagick::CHANNEL_DEFAULT</b>. Refer to this list of channel constants
	 * </p>
	 * @return array an array containing minima and maxima values of the channel(s).
	 */
	public function getImageChannelRange ($channel) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Gets the image alpha channel
	 * @link http://php.net/manual/en/imagick.getimagealphachannel.php
	 * @return int a constant defining the current alpha channel value. Refer to this
	 * list of alpha channel constants.
	 */
	public function getImageAlphaChannel () {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Gets channel distortions
	 * @link http://php.net/manual/en/imagick.getimagechanneldistortions.php
	 * @param Imagick $reference <p>
	 * Imagick object containing the reference image
	 * </p>
	 * @param int $metric <p>
	 * Refer to this list of metric type constants.
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To apply to more than one channel, combine channel constants using bitwise operators. Defaults to <b>Imagick::CHANNEL_DEFAULT</b>. Refer to this list of channel constants
	 * </p>
	 * @return float a double describing the channel distortion.
	 */
	public function getImageChannelDistortions (Imagick $reference, $metric, $channel = Imagick::CHANNEL_DEFAULT) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Sets the image gravity
	 * @link http://php.net/manual/en/imagick.setimagegravity.php
	 * @param int $gravity <p>
	 * The gravity property. Refer to the list of
	 * gravity constants.
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setImageGravity ($gravity) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Gets the image gravity
	 * @link http://php.net/manual/en/imagick.getimagegravity.php
	 * @return int the images gravity property. Refer to the list of
	 * gravity constants.
	 */
	public function getImageGravity () {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Imports image pixels
	 * @link http://php.net/manual/en/imagick.importimagepixels.php
	 * @param int $x <p>
	 * The image x position
	 * </p>
	 * @param int $y <p>
	 * The image y position
	 * </p>
	 * @param int $width <p>
	 * The image width
	 * </p>
	 * @param int $height <p>
	 * The image height
	 * </p>
	 * @param string $map <p>
	 * Map of pixel ordering as a string. This can be for example RGB.
	 * The value can be any combination or order of R = red, G = green, B = blue, A = alpha (0 is transparent),
	 * O = opacity (0 is opaque), C = cyan, Y = yellow, M = magenta, K = black, I = intensity (for grayscale), P = pad.
	 * </p>
	 * @param int $storage <p>
	 * The pixel storage method.
	 * Refer to this list of pixel constants.
	 * </p>
	 * @param array $pixels <p>
	 * The array of pixels
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function importImagePixels ($x, $y, $width, $height, $map, $storage, array $pixels) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Removes skew from the image
	 * @link http://php.net/manual/en/imagick.deskewimage.php
	 * @param float $threshold <p>
	 * Deskew threshold
	 * </p>
	 * @return bool
	 */
	public function deskewImage ($threshold) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Segments an image
	 * @link http://php.net/manual/en/imagick.segmentimage.php
	 * @param int $COLORSPACE <p>
	 * One of the COLORSPACE constants.
	 * </p>
	 * @param float $cluster_threshold <p>
	 * A percentage describing minimum number of pixels
	 * contained in hexedra before it is considered valid.
	 * </p>
	 * @param float $smooth_threshold <p>
	 * Eliminates noise from the histogram.
	 * </p>
	 * @param bool $verbose [optional] <p>
	 * Whether to output detailed information about recognised classes.
	 * </p>
	 * @return bool
	 */
	public function segmentImage ($COLORSPACE, $cluster_threshold, $smooth_threshold, $verbose = false) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Interpolates colors
	 * @link http://php.net/manual/en/imagick.sparsecolorimage.php
	 * @param int $SPARSE_METHOD <p>
	 * Refer to this list of sparse method constants
	 * </p>
	 * @param array $arguments <p>
	 * An array containing the coordinates.
	 * The array is in format array(1,1, 2,45)
	 * </p>
	 * @param int $channel [optional]
	 * @return bool <b>TRUE</b> on success.
	 */
	public function sparseColorImage ($SPARSE_METHOD, array $arguments, $channel = Imagick::CHANNEL_DEFAULT) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Remaps image colors
	 * @link http://php.net/manual/en/imagick.remapimage.php
	 * @param Imagick $replacement <p>
	 * An Imagick object containing the replacement colors
	 * </p>
	 * @param int $DITHER <p>
	 * Refer to this list of dither method constants
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function remapImage (Imagick $replacement, $DITHER) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Exports raw image pixels
	 * @link http://php.net/manual/en/imagick.exportimagepixels.php
	 * @param int $x <p>
	 * X-coordinate of the exported area
	 * </p>
	 * @param int $y <p>
	 * Y-coordinate of the exported area
	 * </p>
	 * @param int $width <p>
	 * Width of the exported aread
	 * </p>
	 * @param int $height <p>
	 * Height of the exported area
	 * </p>
	 * @param string $map <p>
	 * Ordering of the exported pixels. For example "RGB".
	 * Valid characters for the map are R, G, B, A, O, C, Y, M, K, I and P.
	 * </p>
	 * @param int $STORAGE <p>
	 * Refer to this list of pixel type constants
	 * </p>
	 * @return array an array containing the pixels values.
	 */
	public function exportImagePixels ($x, $y, $width, $height, $map, $STORAGE) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * The getImageChannelKurtosis purpose
	 * @link http://php.net/manual/en/imagick.getimagechannelkurtosis.php
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To apply to more than one channel, combine channel constants using bitwise operators. Defaults to <b>Imagick::CHANNEL_DEFAULT</b>. Refer to this list of channel constants
	 * </p>
	 * @return array an array with kurtosis and skewness
	 * members.
	 */
	public function getImageChannelKurtosis ($channel = Imagick::CHANNEL_DEFAULT) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Applies a function on the image
	 * @link http://php.net/manual/en/imagick.functionimage.php
	 * @param int $function <p>
	 * Refer to this list of function constants
	 * </p>
	 * @param array $arguments <p>
	 * Array of arguments to pass to this function.
	 * </p>
	 * @param int $channel [optional]
	 * @return bool <b>TRUE</b> on success.
	 */
	public function functionImage ($function, array $arguments, $channel = Imagick::CHANNEL_DEFAULT) {}

	/**
	 * @param $COLORSPACE
	 */
	public function transformImageColorspace ($COLORSPACE) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Replaces colors in the image
	 * @link http://php.net/manual/en/imagick.haldclutimage.php
	 * @param Imagick $clut <p>
	 * Imagick object containing the Hald lookup image.
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To apply to more than one channel, combine channel constants using bitwise operators. Defaults to <b>Imagick::CHANNEL_DEFAULT</b>. Refer to this list of channel constants
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function haldClutImage (Imagick $clut, $channel = Imagick::CHANNEL_DEFAULT) {}

	/**
	 * @param $CHANNEL [optional]
	 */
	public function autoLevelImage ($CHANNEL) {}

	/**
	 * @param $factor [optional]
	 */
	public function blueShiftImage ($factor) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Get image artifact
	 * @link http://php.net/manual/en/imagick.getimageartifact.php
	 * @param string $artifact <p>
	 * The name of the artifact
	 * </p>
	 * @return string the artifact value on success.
	 */
	public function getImageArtifact ($artifact) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Set image artifact
	 * @link http://php.net/manual/en/imagick.setimageartifact.php
	 * @param string $artifact <p>
	 * The name of the artifact
	 * </p>
	 * @param string $value <p>
	 * The value of the artifact
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageArtifact ($artifact, $value) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Delete image artifact
	 * @link http://php.net/manual/en/imagick.deleteimageartifact.php
	 * @param string $artifact <p>
	 * The name of the artifact to delete
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function deleteImageArtifact ($artifact) {}

	/**
	 * (PECL imagick 0.9.10-0.9.9)<br/>
	 * Gets the colorspace
	 * @link http://php.net/manual/en/imagick.getcolorspace.php
	 * @return int an integer which can be compared against COLORSPACE constants.
	 */
	public function getColorspace () {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Set colorspace
	 * @link http://php.net/manual/en/imagick.setcolorspace.php
	 * @param int $COLORSPACE <p>
	 * One of the COLORSPACE constants
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setColorspace ($COLORSPACE) {}

	/**
	 * @param $CHANNEL [optional]
	 */
	public function clampImage ($CHANNEL) {}

	/**
	 * @param $stack
	 * @param $offset
	 */
	public function smushImages ($stack, $offset) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * The Imagick constructor
	 * @link http://php.net/manual/en/imagick.construct.php
	 * @param mixed $files <p>
	 * The path to an image to load or an array of paths. Paths can include
	 * wildcards for file names, or can be URLs.
	 * </p>
	 */
	public function __construct ($files = null) {}

    /**
     * @return string
     */
	public function __toString () {}

	public function count () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns a MagickPixelIterator
	 * @link http://php.net/manual/en/imagick.getpixeliterator.php
	 * @return ImagickPixelIterator an ImagickPixelIterator on success.
	 */
	public function getPixelIterator () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Get an ImagickPixelIterator for an image section
	 * @link http://php.net/manual/en/imagick.getpixelregioniterator.php
	 * @param int $x <p>
	 * The x-coordinate of the region.
	 * </p>
	 * @param int $y <p>
	 * The y-coordinate of the region.
	 * </p>
	 * @param int $columns <p>
	 * The width of the region.
	 * </p>
	 * @param int $rows <p>
	 * The height of the region.
	 * </p>
	 * @return ImagickPixelIterator an ImagickPixelIterator for an image section.
	 */
	public function getPixelRegionIterator ($x, $y, $columns, $rows) {}

	/**
	 * (PECL imagick 0.9.0-0.9.9)<br/>
	 * Reads image from filename
	 * @link http://php.net/manual/en/imagick.readimage.php
	 * @param string $filename
	 * @return bool <b>TRUE</b> on success.
	 */
	public function readImage ($filename) {}

	/**
	 * @param $filenames
	 */
	public function readImages ($filenames) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Reads image from a binary string
	 * @link http://php.net/manual/en/imagick.readimageblob.php
	 * @param string $image
	 * @param string $filename [optional]
	 * @return bool <b>TRUE</b> on success.
	 */
	public function readImageBlob ($image, $filename = null) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the format of a particular image
	 * @link http://php.net/manual/en/imagick.setimageformat.php
	 * @param string $format <p>
	 * String presentation of the image format. Format support
	 * depends on the ImageMagick installation.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageFormat ($format) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Scales the size of an image
	 * @link http://php.net/manual/en/imagick.scaleimage.php
	 * @param int $cols
	 * @param int $rows
	 * @param bool $bestfit [optional]
	 * @return bool <b>TRUE</b> on success.
	 */
	public function scaleImage ($cols, $rows, $bestfit = false) {}

	/**
	 * (PECL imagick 0.9.0-0.9.9)<br/>
	 * Writes an image to the specified filename
	 * @link http://php.net/manual/en/imagick.writeimage.php
	 * @param string $filename [optional] <p>
	 * Filename where to write the image. The extension of the filename
	 * defines the type of the file.
	 * Format can be forced regardless of file extension using format: prefix,
	 * for example "jpg:test.png".
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function writeImage ($filename = NULL) {}

	/**
	 * (PECL imagick 0.9.0-0.9.9)<br/>
	 * Writes an image or image sequence
	 * @link http://php.net/manual/en/imagick.writeimages.php
	 * @param string $filename
	 * @param bool $adjoin
	 * @return bool <b>TRUE</b> on success.
	 */
	public function writeImages ($filename, $adjoin) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adds blur filter to image
	 * @link http://php.net/manual/en/imagick.blurimage.php
	 * @param float $radius <p>
	 * Blur radius
	 * </p>
	 * @param float $sigma <p>
	 * Standard deviation
	 * </p>
	 * @param int $channel [optional] <p>
	 * The Channeltype
	 * constant. When not supplied, all channels are blurred.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function blurImage ($radius, $sigma, $channel = null) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Changes the size of an image
	 * @link http://php.net/manual/en/imagick.thumbnailimage.php
	 * @param int $columns <p>
	 * Image width
	 * </p>
	 * @param int $rows <p>
	 * Image height
	 * </p>
	 * @param bool $bestfit [optional] <p>
	 * Whether to force maximum values
	 * </p>
	 * @param bool $fill [optional]
	 * @return bool <b>TRUE</b> on success.
	 */
	public function thumbnailImage ($columns, $rows, $bestfit = false, $fill = false) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Creates a crop thumbnail
	 * @link http://php.net/manual/en/imagick.cropthumbnailimage.php
	 * @param int $width <p>
	 * The width of the thumbnail
	 * </p>
	 * @param int $height <p>
	 * The Height of the thumbnail
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function cropThumbnailImage ($width, $height) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the filename of a particular image in a sequence
	 * @link http://php.net/manual/en/imagick.getimagefilename.php
	 * @return string a string with the filename of the image.
	 */
	public function getImageFilename () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the filename of a particular image
	 * @link http://php.net/manual/en/imagick.setimagefilename.php
	 * @param string $filename
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageFilename ($filename) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the format of a particular image in a sequence
	 * @link http://php.net/manual/en/imagick.getimageformat.php
	 * @return string a string containing the image format on success.
	 */
	public function getImageFormat () {}

	/**
	 * @link http://lv.php.net/manual/en/imagick.getimagemimetype.php
	 * @return string Returns the image mime-type.
	 */
	public function getImageMimeType () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Removes an image from the image list
	 * @link http://php.net/manual/en/imagick.removeimage.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function removeImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Destroys the Imagick object
	 * @link http://php.net/manual/en/imagick.destroy.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function destroy () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Clears all resources associated to Imagick object
	 * @link http://php.net/manual/en/imagick.clear.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function clear () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the image length in bytes
	 * @link http://php.net/manual/en/imagick.getimagesize.php
	 * @return int an int containing the current image size.
	 * @deprecated use {@see Imagick::getImageLength()} instead
	 */
	public function getImageSize () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the image sequence as a blob
	 * @link http://php.net/manual/en/imagick.getimageblob.php
	 * @return string a string containing the image.
	 */
	public function getImageBlob () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns all image sequences as a blob
	 * @link http://php.net/manual/en/imagick.getimagesblob.php
	 * @return string a string containing the images. On failure, throws
	 * ImagickException.
	 */
	public function getImagesBlob () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the Imagick iterator to the first image
	 * @link http://php.net/manual/en/imagick.setfirstiterator.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setFirstIterator () {}

	/**
	 * (PECL imagick 2.0.1)<br/>
	 * Sets the Imagick iterator to the last image
	 * @link http://php.net/manual/en/imagick.setlastiterator.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setLastIterator () {}

	public function resetIterator () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Move to the previous image in the object
	 * @link http://php.net/manual/en/imagick.previousimage.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function previousImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Moves to the next image
	 * @link http://php.net/manual/en/imagick.nextimage.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function nextImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Checks if the object has a previous image
	 * @link http://php.net/manual/en/imagick.haspreviousimage.php
	 * @return bool <b>TRUE</b> if the object has more images when traversing the list in the
	 * reverse direction, returns <b>FALSE</b> if there are none.
	 */
	public function hasPreviousImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Checks if the object has more images
	 * @link http://php.net/manual/en/imagick.hasnextimage.php
	 * @return bool <b>TRUE</b> if the object has more images when traversing the list in the
	 * forward direction, returns <b>FALSE</b> if there are none.
	 */
	public function hasNextImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Set the iterator position
	 * @link http://php.net/manual/en/imagick.setimageindex.php
	 * @param int $index <p>
	 * The position to set the iterator to
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageIndex ($index) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the index of the current active image
	 * @link http://php.net/manual/en/imagick.getimageindex.php
	 * @return int an integer containing the index of the image in the stack.
	 */
	public function getImageIndex () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adds a comment to your image
	 * @link http://php.net/manual/en/imagick.commentimage.php
	 * @param string $comment <p>
	 * The comment to add
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function commentImage ($comment) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Extracts a region of the image
	 * @link http://php.net/manual/en/imagick.cropimage.php
	 * @param int $width <p>
	 * The width of the crop
	 * </p>
	 * @param int $height <p>
	 * The height of the crop
	 * </p>
	 * @param int $x <p>
	 * The X coordinate of the cropped region's top left corner
	 * </p>
	 * @param int $y <p>
	 * The Y coordinate of the cropped region's top left corner
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function cropImage ($width, $height, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adds a label to an image
	 * @link http://php.net/manual/en/imagick.labelimage.php
	 * @param string $label <p>
	 * The label to add
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function labelImage ($label) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the width and height as an associative array
	 * @link http://php.net/manual/en/imagick.getimagegeometry.php
	 * @return array an array with the width/height of the image.
	 */
	public function getImageGeometry () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Renders the ImagickDraw object on the current image
	 * @link http://php.net/manual/en/imagick.drawimage.php
	 * @param ImagickDraw $draw <p>
	 * The drawing operations to render on the image.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function drawImage (ImagickDraw $draw) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Sets the image compression quality
	 * @link http://php.net/manual/en/imagick.setimagecompressionquality.php
	 * @param int $quality <p>
	 * The image compression quality as an integer
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageCompressionQuality ($quality) {}

	/**
	 * (PECL imagick 2.2.2)<br/>
	 * Gets the current image's compression quality
	 * @link http://php.net/manual/en/imagick.getimagecompressionquality.php
	 * @return int integer describing the images compression quality
	 */
	public function getImageCompressionQuality () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Annotates an image with text
	 * @link http://php.net/manual/en/imagick.annotateimage.php
	 * @param ImagickDraw $draw_settings <p>
	 * The ImagickDraw object that contains settings for drawing the text
	 * </p>
	 * @param float $x <p>
	 * Horizontal offset in pixels to the left of text
	 * </p>
	 * @param float $y <p>
	 * Vertical offset in pixels to the baseline of text
	 * </p>
	 * @param float $angle <p>
	 * The angle at which to write the text
	 * </p>
	 * @param string $text <p>
	 * The string to draw
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function annotateImage (ImagickDraw $draw_settings, $x, $y, $angle, $text) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Composite one image onto another
	 * @link http://php.net/manual/en/imagick.compositeimage.php
	 * @param Imagick $composite_object <p>
	 * Imagick object which holds the composite image
	 * </p>
	 * @param int $composite
	 * @param int $x <p>
	 * The column offset of the composited image
	 * </p>
	 * @param int $y <p>
	 * The row offset of the composited image
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this list of channel constants.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function compositeImage (Imagick $composite_object, $composite, $x, $y, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Control the brightness, saturation, and hue
	 * @link http://php.net/manual/en/imagick.modulateimage.php
	 * @param float $brightness
	 * @param float $saturation
	 * @param float $hue
	 * @return bool <b>TRUE</b> on success.
	 */
	public function modulateImage ($brightness, $saturation, $hue) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the number of unique colors in the image
	 * @link http://php.net/manual/en/imagick.getimagecolors.php
	 * @return int <b>TRUE</b> on success.
	 */
	public function getImageColors () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Creates a composite image
	 * @link http://php.net/manual/en/imagick.montageimage.php
	 * @param ImagickDraw $draw <p>
	 * The font name, size, and color are obtained from this object.
	 * </p>
	 * @param string $tile_geometry <p>
	 * The number of tiles per row and page (e.g. 6x4+0+0).
	 * </p>
	 * @param string $thumbnail_geometry <p>
	 * Preferred image size and border size of each thumbnail
	 * (e.g. 120x120+4+3>).
	 * </p>
	 * @param int $mode <p>
	 * Thumbnail framing mode, see Montage Mode constants.
	 * </p>
	 * @param string $frame <p>
	 * Surround the image with an ornamental border (e.g. 15x15+3+3). The
	 * frame color is that of the thumbnail's matte color.
	 * </p>
	 * @return Imagick <b>TRUE</b> on success.
	 */
	public function montageImage (ImagickDraw $draw, $tile_geometry, $thumbnail_geometry, $mode, $frame) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Identifies an image and fetches attributes
	 * @link http://php.net/manual/en/imagick.identifyimage.php
	 * @param bool $appendRawOutput [optional]
	 * @return array Identifies an image and returns the attributes. Attributes include
	 * the image width, height, size, and others.
	 */
	public function identifyImage ($appendRawOutput = false) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Changes the value of individual pixels based on a threshold
	 * @link http://php.net/manual/en/imagick.thresholdimage.php
	 * @param float $threshold
	 * @param int $channel [optional]
	 * @return bool <b>TRUE</b> on success.
	 */
	public function thresholdImage ($threshold, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Selects a threshold for each pixel based on a range of intensity
	 * @link http://php.net/manual/en/imagick.adaptivethresholdimage.php
	 * @param int $width <p>
	 * Width of the local neighborhood.
	 * </p>
	 * @param int $height <p>
	 * Height of the local neighborhood.
	 * </p>
	 * @param int $offset <p>
	 * The mean offset
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function adaptiveThresholdImage ($width, $height, $offset) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Forces all pixels below the threshold into black
	 * @link http://php.net/manual/en/imagick.blackthresholdimage.php
	 * @param mixed $threshold <p>
	 * The threshold below which everything turns black
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function blackThresholdImage ($threshold) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Force all pixels above the threshold into white
	 * @link http://php.net/manual/en/imagick.whitethresholdimage.php
	 * @param mixed $threshold
	 * @return bool <b>TRUE</b> on success.
	 */
	public function whiteThresholdImage ($threshold) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Append a set of images
	 * @link http://php.net/manual/en/imagick.appendimages.php
	 * @param bool $stack [optional] <p>
	 * Whether to stack the images vertically.
	 * By default (or if <b>FALSE</b> is specified) images are stacked left-to-right.
	 * If <i>stack</i> is <b>TRUE</b>, images are stacked top-to-bottom.
	 * </p>
	 * @return Imagick Imagick instance on success.
	 */
	public function appendImages ($stack = false) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Simulates a charcoal drawing
	 * @link http://php.net/manual/en/imagick.charcoalimage.php
	 * @param float $radius <p>
	 * The radius of the Gaussian, in pixels, not counting the center pixel
	 * </p>
	 * @param float $sigma <p>
	 * The standard deviation of the Gaussian, in pixels
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function charcoalImage ($radius, $sigma) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Enhances the contrast of a color image
	 * @link http://php.net/manual/en/imagick.normalizeimage.php
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function normalizeImage ($channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Simulates an oil painting
	 * @link http://php.net/manual/en/imagick.oilpaintimage.php
	 * @param float $radius <p>
	 * The radius of the circular neighborhood.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function oilPaintImage ($radius) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Reduces the image to a limited number of color level
	 * @link http://php.net/manual/en/imagick.posterizeimage.php
	 * @param int $levels
	 * @param bool $dither
	 * @return bool <b>TRUE</b> on success.
	 */
	public function posterizeImage ($levels, $dither) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Radial blurs an image
	 * @link http://php.net/manual/en/imagick.radialblurimage.php
	 * @param float $angle
	 * @param int $channel [optional]
	 * @return bool <b>TRUE</b> on success.
	 */
	public function radialBlurImage ($angle, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Creates a simulated 3d button-like effect
	 * @link http://php.net/manual/en/imagick.raiseimage.php
	 * @param int $width
	 * @param int $height
	 * @param int $x
	 * @param int $y
	 * @param bool $raise
	 * @return bool <b>TRUE</b> on success.
	 */
	public function raiseImage ($width, $height, $x, $y, $raise) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Resample image to desired resolution
	 * @link http://php.net/manual/en/imagick.resampleimage.php
	 * @param float $x_resolution
	 * @param float $y_resolution
	 * @param int $filter
	 * @param float $blur
	 * @return bool <b>TRUE</b> on success.
	 */
	public function resampleImage ($x_resolution, $y_resolution, $filter, $blur) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Scales an image
	 * @link http://php.net/manual/en/imagick.resizeimage.php
	 * @param int $columns <p>
	 * Width of the image
	 * </p>
	 * @param int $rows <p>
	 * Height of the image
	 * </p>
	 * @param int $filter <p>
	 * Refer to the list of filter constants.
	 * </p>
	 * @param float $blur <p>
	 * The blur factor where &gt; 1 is blurry, &lt; 1 is sharp.
	 * </p>
	 * @param bool $bestfit [optional] <p>
	 * Optional fit parameter.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function resizeImage ($columns, $rows, $filter, $blur, $bestfit = false) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Offsets an image
	 * @link http://php.net/manual/en/imagick.rollimage.php
	 * @param int $x <p>
	 * The X offset.
	 * </p>
	 * @param int $y <p>
	 * The Y offset.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function rollImage ($x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Rotates an image
	 * @link http://php.net/manual/en/imagick.rotateimage.php
	 * @param mixed $background <p>
	 * The background color
	 * </p>
	 * @param float $degrees <p>
	 * The number of degrees to rotate the image
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function rotateImage ($background, $degrees) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Scales an image with pixel sampling
	 * @link http://php.net/manual/en/imagick.sampleimage.php
	 * @param int $columns
	 * @param int $rows
	 * @return bool <b>TRUE</b> on success.
	 */
	public function sampleImage ($columns, $rows) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Applies a solarizing effect to the image
	 * @link http://php.net/manual/en/imagick.solarizeimage.php
	 * @param int $threshold
	 * @return bool <b>TRUE</b> on success.
	 */
	public function solarizeImage ($threshold) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Simulates an image shadow
	 * @link http://php.net/manual/en/imagick.shadowimage.php
	 * @param float $opacity
	 * @param float $sigma
	 * @param int $x
	 * @param int $y
	 * @return bool <b>TRUE</b> on success.
	 */
	public function shadowImage ($opacity, $sigma, $x, $y) {}

	/**
	 * @param $key
	 * @param $value
	 */
	public function setImageAttribute ($key, $value) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image background color
	 * @link http://php.net/manual/en/imagick.setimagebackgroundcolor.php
	 * @param mixed $background
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageBackgroundColor ($background) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image composite operator
	 * @link http://php.net/manual/en/imagick.setimagecompose.php
	 * @param int $compose
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageCompose ($compose) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image compression
	 * @link http://php.net/manual/en/imagick.setimagecompression.php
	 * @param int $compression <p>
	 * One of the <b>COMPRESSION</b> constants
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageCompression ($compression) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image delay
	 * @link http://php.net/manual/en/imagick.setimagedelay.php
	 * @param int $delay <p>
	 * The amount of time expressed in 'ticks' that the image should be
	 * displayed for. For animated GIFs there are 100 ticks per second, so a
	 * value of 20 would be 20/100 of a second aka 1/5th of a second.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageDelay ($delay) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image depth
	 * @link http://php.net/manual/en/imagick.setimagedepth.php
	 * @param int $depth
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageDepth ($depth) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image gamma
	 * @link http://php.net/manual/en/imagick.setimagegamma.php
	 * @param float $gamma
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageGamma ($gamma) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image iterations
	 * @link http://php.net/manual/en/imagick.setimageiterations.php
	 * @param int $iterations <p>
	 * The number of iterations the image should loop over. Set to '0' to loop
	 * continuously.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageIterations ($iterations) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image matte color
	 * @link http://php.net/manual/en/imagick.setimagemattecolor.php
	 * @param mixed $matte
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageMatteColor ($matte) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the page geometry of the image
	 * @link http://php.net/manual/en/imagick.setimagepage.php
	 * @param int $width
	 * @param int $height
	 * @param int $x
	 * @param int $y
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImagePage ($width, $height, $x, $y) {}

	/**
	 * @param $filename
	 */
	public function setImageProgressMonitor ($filename) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image resolution
	 * @link http://php.net/manual/en/imagick.setimageresolution.php
	 * @param float $x_resolution
	 * @param float $y_resolution
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageResolution ($x_resolution, $y_resolution) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image scene
	 * @link http://php.net/manual/en/imagick.setimagescene.php
	 * @param int $scene
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageScene ($scene) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image ticks-per-second
	 * @link http://php.net/manual/en/imagick.setimagetickspersecond.php
	 * @param int $ticks_per_second <p>
	 * The duration for which an image should be displayed expressed in ticks
	 * per second.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageTicksPerSecond ($ticks_per_second) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image type
	 * @link http://php.net/manual/en/imagick.setimagetype.php
	 * @param int $image_type
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageType ($image_type) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image units of resolution
	 * @link http://php.net/manual/en/imagick.setimageunits.php
	 * @param int $units
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageUnits ($units) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sharpens an image
	 * @link http://php.net/manual/en/imagick.sharpenimage.php
	 * @param float $radius
	 * @param float $sigma
	 * @param int $channel [optional]
	 * @return bool <b>TRUE</b> on success.
	 */
	public function sharpenImage ($radius, $sigma, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Shaves pixels from the image edges
	 * @link http://php.net/manual/en/imagick.shaveimage.php
	 * @param int $columns
	 * @param int $rows
	 * @return bool <b>TRUE</b> on success.
	 */
	public function shaveImage ($columns, $rows) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Creating a parallelogram
	 * @link http://php.net/manual/en/imagick.shearimage.php
	 * @param mixed $background <p>
	 * The background color
	 * </p>
	 * @param float $x_shear <p>
	 * The number of degrees to shear on the x axis
	 * </p>
	 * @param float $y_shear <p>
	 * The number of degrees to shear on the y axis
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function shearImage ($background, $x_shear, $y_shear) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Splices a solid color into the image
	 * @link http://php.net/manual/en/imagick.spliceimage.php
	 * @param int $width
	 * @param int $height
	 * @param int $x
	 * @param int $y
	 * @return bool <b>TRUE</b> on success.
	 */
	public function spliceImage ($width, $height, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Fetch basic attributes about the image
	 * @link http://php.net/manual/en/imagick.pingimage.php
	 * @param string $filename <p>
	 * The filename to read the information from.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function pingImage ($filename) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Reads image from open filehandle
	 * @link http://php.net/manual/en/imagick.readimagefile.php
	 * @param resource $filehandle
	 * @param string $fileName [optional]
	 * @return bool <b>TRUE</b> on success.
	 */
	public function readImageFile ($filehandle, $fileName = null) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Displays an image
	 * @link http://php.net/manual/en/imagick.displayimage.php
	 * @param string $servername <p>
	 * The X server name
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function displayImage ($servername) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Displays an image or image sequence
	 * @link http://php.net/manual/en/imagick.displayimages.php
	 * @param string $servername <p>
	 * The X server name
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function displayImages ($servername) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Randomly displaces each pixel in a block
	 * @link http://php.net/manual/en/imagick.spreadimage.php
	 * @param float $radius
	 * @return bool <b>TRUE</b> on success.
	 */
	public function spreadImage ($radius) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Swirls the pixels about the center of the image
	 * @link http://php.net/manual/en/imagick.swirlimage.php
	 * @param float $degrees
	 * @return bool <b>TRUE</b> on success.
	 */
	public function swirlImage ($degrees) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Strips an image of all profiles and comments
	 * @link http://php.net/manual/en/imagick.stripimage.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function stripImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns formats supported by Imagick
	 * @link http://php.net/manual/en/imagick.queryformats.php
	 * @param string $pattern [optional]
	 * @return array an array containing the formats supported by Imagick.
	 */
	public static function queryFormats ($pattern = "*") {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the configured fonts
	 * @link http://php.net/manual/en/imagick.queryfonts.php
	 * @param string $pattern [optional] <p>
	 * The query pattern
	 * </p>
	 * @return array an array containing the configured fonts.
	 */
	public static function queryFonts ($pattern = "*") {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns an array representing the font metrics
	 * @link http://php.net/manual/en/imagick.queryfontmetrics.php
	 * @param ImagickDraw $properties <p>
	 * ImagickDraw object containing font properties
	 * </p>
	 * @param string $text <p>
	 * The text
	 * </p>
	 * @param bool $multiline [optional] <p>
	 * Multiline parameter. If left empty it is autodetected
	 * </p>
	 * @return array a multi-dimensional array representing the font metrics.
	 */
	public function queryFontMetrics (ImagickDraw $properties, $text, $multiline = null) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Hides a digital watermark within the image
	 * @link http://php.net/manual/en/imagick.steganoimage.php
	 * @param Imagick $watermark_wand
	 * @param int $offset
	 * @return Imagick <b>TRUE</b> on success.
	 */
	public function steganoImage (Imagick $watermark_wand, $offset) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adds random noise to the image
	 * @link http://php.net/manual/en/imagick.addnoiseimage.php
	 * @param int $noise_type <p>
	 * The type of the noise. Refer to this list of
	 * noise constants.
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To apply to more than one channel, combine channel constants using bitwise operators. Defaults to <b>Imagick::CHANNEL_DEFAULT</b>. Refer to this list of channel constants
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function addNoiseImage ($noise_type, $channel = Imagick::CHANNEL_DEFAULT) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Simulates motion blur
	 * @link http://php.net/manual/en/imagick.motionblurimage.php
	 * @param float $radius <p>
	 * The radius of the Gaussian, in pixels, not counting the center pixel.
	 * </p>
	 * @param float $sigma <p>
	 * The standard deviation of the Gaussian, in pixels.
	 * </p>
	 * @param float $angle <p>
	 * Apply the effect along this angle.
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * The channel argument affects only if Imagick is compiled against ImageMagick version
	 * 6.4.4 or greater.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function motionBlurImage ($radius, $sigma, $angle, $channel = Imagick::CHANNEL_DEFAULT) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Forms a mosaic from images
	 * @link http://php.net/manual/en/imagick.mosaicimages.php
	 * @return Imagick <b>TRUE</b> on success.
	 */
	public function mosaicImages () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Method morphs a set of images
	 * @link http://php.net/manual/en/imagick.morphimages.php
	 * @param int $number_frames <p>
	 * The number of in-between images to generate.
	 * </p>
	 * @return Imagick This method returns a new Imagick object on success.
	 * Throw an
	 * <b>ImagickException</b> on error.
	 */
	public function morphImages ($number_frames) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Scales an image proportionally to half its size
	 * @link http://php.net/manual/en/imagick.minifyimage.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function minifyImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Transforms an image
	 * @link http://php.net/manual/en/imagick.affinetransformimage.php
	 * @param ImagickDraw $matrix <p>
	 * The affine matrix
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function affineTransformImage (ImagickDraw $matrix) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Average a set of images
	 * @link http://php.net/manual/en/imagick.averageimages.php
	 * @return Imagick a new Imagick object on success.
	 */
	public function averageImages () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Surrounds the image with a border
	 * @link http://php.net/manual/en/imagick.borderimage.php
	 * @param mixed $bordercolor <p>
	 * ImagickPixel object or a string containing the border color
	 * </p>
	 * @param int $width <p>
	 * Border width
	 * </p>
	 * @param int $height <p>
	 * Border height
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function borderImage ($bordercolor, $width, $height) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Removes a region of an image and trims
	 * @link http://php.net/manual/en/imagick.chopimage.php
	 * @param int $width <p>
	 * Width of the chopped area
	 * </p>
	 * @param int $height <p>
	 * Height of the chopped area
	 * </p>
	 * @param int $x <p>
	 * X origo of the chopped area
	 * </p>
	 * @param int $y <p>
	 * Y origo of the chopped area
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function chopImage ($width, $height, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Clips along the first path from the 8BIM profile
	 * @link http://php.net/manual/en/imagick.clipimage.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function clipImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Clips along the named paths from the 8BIM profile
	 * @link http://php.net/manual/en/imagick.clippathimage.php
	 * @param string $pathname <p>
	 * The name of the path
	 * </p>
	 * @param bool $inside <p>
	 * If <b>TRUE</b> later operations take effect inside clipping path.
	 * Otherwise later operations take effect outside clipping path.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function clipPathImage ($pathname, $inside) {}

	/**
	 * @param $pathname
	 * @param $inside
	 */
	public function clipImagePath ($pathname, $inside) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Composites a set of images
	 * @link http://php.net/manual/en/imagick.coalesceimages.php
	 * @return Imagick a new Imagick object on success.
	 */
	public function coalesceImages () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Changes the color value of any pixel that matches target
	 * @link http://php.net/manual/en/imagick.colorfloodfillimage.php
	 * @param mixed $fill <p>
	 * ImagickPixel object containing the fill color
	 * </p>
	 * @param float $fuzz <p>
	 * The amount of fuzz. For example, set fuzz to 10 and the color red at
	 * intensities of 100 and 102 respectively are now interpreted as the
	 * same color for the purposes of the floodfill.
	 * </p>
	 * @param mixed $bordercolor <p>
	 * ImagickPixel object containing the border color
	 * </p>
	 * @param int $x <p>
	 * X start position of the floodfill
	 * </p>
	 * @param int $y <p>
	 * Y start position of the floodfill
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function colorFloodfillImage ($fill, $fuzz, $bordercolor, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Blends the fill color with the image
	 * @link http://php.net/manual/en/imagick.colorizeimage.php
	 * @param mixed $colorize <p>
	 * ImagickPixel object or a string containing the colorize color
	 * </p>
	 * @param mixed $opacity <p>
	 * ImagickPixel object or an float containing the opacity value.
	 * 1.0 is fully opaque and 0.0 is fully transparent.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function colorizeImage ($colorize, $opacity) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the difference in one or more images
	 * @link http://php.net/manual/en/imagick.compareimagechannels.php
	 * @param Imagick $image <p>
	 * Imagick object containing the image to compare.
	 * </p>
	 * @param int $channelType <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * </p>
	 * @param int $metricType <p>
	 * One of the metric type constants.
	 * </p>
	 * @return array Array consisting of new_wand and
	 * distortion.
	 */
	public function compareImageChannels (Imagick $image, $channelType, $metricType) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Compares an image to a reconstructed image
	 * @link http://php.net/manual/en/imagick.compareimages.php
	 * @param Imagick $compare <p>
	 * An image to compare to.
	 * </p>
	 * @param int $metric <p>
	 * Provide a valid metric type constant. Refer to this
	 * list of metric constants.
	 * </p>
	 * @return array <b>TRUE</b> on success.
	 */
	public function compareImages (Imagick $compare, $metric) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Change the contrast of the image
	 * @link http://php.net/manual/en/imagick.contrastimage.php
	 * @param bool $sharpen <p>
	 * The sharpen value
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function contrastImage ($sharpen) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Combines one or more images into a single image
	 * @link http://php.net/manual/en/imagick.combineimages.php
	 * @param int $channelType <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * </p>
	 * @return Imagick <b>TRUE</b> on success.
	 */
	public function combineImages ($channelType) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Applies a custom convolution kernel to the image
	 * @link http://php.net/manual/en/imagick.convolveimage.php
	 * @param array $kernel <p>
	 * The convolution kernel
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function convolveImage (array $kernel, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Displaces an image's colormap
	 * @link http://php.net/manual/en/imagick.cyclecolormapimage.php
	 * @param int $displace <p>
	 * The amount to displace the colormap.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function cycleColormapImage ($displace) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns certain pixel differences between images
	 * @link http://php.net/manual/en/imagick.deconstructimages.php
	 * @return Imagick a new Imagick object on success.
	 */
	public function deconstructImages () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Reduces the speckle noise in an image
	 * @link http://php.net/manual/en/imagick.despeckleimage.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function despeckleImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Enhance edges within the image
	 * @link http://php.net/manual/en/imagick.edgeimage.php
	 * @param float $radius <p>
	 * The radius of the operation.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function edgeImage ($radius) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns a grayscale image with a three-dimensional effect
	 * @link http://php.net/manual/en/imagick.embossimage.php
	 * @param float $radius <p>
	 * The radius of the effect
	 * </p>
	 * @param float $sigma <p>
	 * The sigma of the effect
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function embossImage ($radius, $sigma) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Improves the quality of a noisy image
	 * @link http://php.net/manual/en/imagick.enhanceimage.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function enhanceImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Equalizes the image histogram
	 * @link http://php.net/manual/en/imagick.equalizeimage.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function equalizeImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Applies an expression to an image
	 * @link http://php.net/manual/en/imagick.evaluateimage.php
	 * @param int $op <p>
	 * The evaluation operator
	 * </p>
	 * @param float $constant <p>
	 * The value of the operator
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function evaluateImage ($op, $constant, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Merges a sequence of images
	 * @link http://php.net/manual/en/imagick.flattenimages.php
	 * @return Imagick <b>TRUE</b> on success.
	 */
	public function flattenImages () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Creates a vertical mirror image
	 * @link http://php.net/manual/en/imagick.flipimage.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function flipImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Creates a horizontal mirror image
	 * @link http://php.net/manual/en/imagick.flopimage.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function flopImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adds a simulated three-dimensional border
	 * @link http://php.net/manual/en/imagick.frameimage.php
	 * @param mixed $matte_color <p>
	 * ImagickPixel object or a string representing the matte color
	 * </p>
	 * @param int $width <p>
	 * The width of the border
	 * </p>
	 * @param int $height <p>
	 * The height of the border
	 * </p>
	 * @param int $inner_bevel <p>
	 * The inner bevel width
	 * </p>
	 * @param int $outer_bevel <p>
	 * The outer bevel width
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function frameImage ($matte_color, $width, $height, $inner_bevel, $outer_bevel) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Evaluate expression for each pixel in the image
	 * @link http://php.net/manual/en/imagick.fximage.php
	 * @param string $expression <p>
	 * The expression.
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * </p>
	 * @return Imagick <b>TRUE</b> on success.
	 */
	public function fxImage ($expression, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gamma-corrects an image
	 * @link http://php.net/manual/en/imagick.gammaimage.php
	 * @param float $gamma <p>
	 * The amount of gamma-correction.
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function gammaImage ($gamma, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Blurs an image
	 * @link http://php.net/manual/en/imagick.gaussianblurimage.php
	 * @param float $radius <p>
	 * The radius of the Gaussian, in pixels, not counting the center pixel.
	 * </p>
	 * @param float $sigma <p>
	 * The standard deviation of the Gaussian, in pixels.
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function gaussianBlurImage ($radius, $sigma, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * @param $key
	 */
	public function getImageAttribute ($key) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the image background color
	 * @link http://php.net/manual/en/imagick.getimagebackgroundcolor.php
	 * @return ImagickPixel an ImagickPixel set to the background color of the image.
	 */
	public function getImageBackgroundColor () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the chromaticy blue primary point
	 * @link http://php.net/manual/en/imagick.getimageblueprimary.php
	 * @return array Array consisting of "x" and "y" coordinates of point.
	 */
	public function getImageBluePrimary () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the image border color
	 * @link http://php.net/manual/en/imagick.getimagebordercolor.php
	 * @return ImagickPixel <b>TRUE</b> on success.
	 */
	public function getImageBorderColor () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the depth for a particular image channel
	 * @link http://php.net/manual/en/imagick.getimagechanneldepth.php
	 * @param int $channel <p>
	 * Provide any channel constant that is valid for your channel mode. To apply to more than one channel, combine channel constants using bitwise operators. Defaults to <b>Imagick::CHANNEL_DEFAULT</b>. Refer to this list of channel constants
	 * </p>
	 * @return int <b>TRUE</b> on success.
	 */
	public function getImageChannelDepth ($channel) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Compares image channels of an image to a reconstructed image
	 * @link http://php.net/manual/en/imagick.getimagechanneldistortion.php
	 * @param Imagick $reference <p>
	 * Imagick object to compare to.
	 * </p>
	 * @param int $channel <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * </p>
	 * @param int $metric <p>
	 * One of the metric type constants.
	 * </p>
	 * @return float <b>TRUE</b> on success.
	 */
	public function getImageChannelDistortion (Imagick $reference, $channel, $metric) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the extrema for one or more image channels
	 * @link http://php.net/manual/en/imagick.getimagechannelextrema.php
	 * @param int $channel <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * </p>
	 * @return array <b>TRUE</b> on success.
	 */
	public function getImageChannelExtrema ($channel) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the mean and standard deviation
	 * @link http://php.net/manual/en/imagick.getimagechannelmean.php
	 * @param int $channel <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * </p>
	 * @return array <b>TRUE</b> on success.
	 */
	public function getImageChannelMean ($channel) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns statistics for each channel in the image
	 * @link http://php.net/manual/en/imagick.getimagechannelstatistics.php
	 * @return array <b>TRUE</b> on success.
	 */
	public function getImageChannelStatistics () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the color of the specified colormap index
	 * @link http://php.net/manual/en/imagick.getimagecolormapcolor.php
	 * @param int $index <p>
	 * The offset into the image colormap.
	 * </p>
	 * @return ImagickPixel <b>TRUE</b> on success.
	 */
	public function getImageColormapColor ($index) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the image colorspace
	 * @link http://php.net/manual/en/imagick.getimagecolorspace.php
	 * @return int <b>TRUE</b> on success.
	 */
	public function getImageColorspace () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the composite operator associated with the image
	 * @link http://php.net/manual/en/imagick.getimagecompose.php
	 * @return int <b>TRUE</b> on success.
	 */
	public function getImageCompose () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the image delay
	 * @link http://php.net/manual/en/imagick.getimagedelay.php
	 * @return int the image delay.
	 */
	public function getImageDelay () {}

	/**
	 * (PECL imagick 0.9.1-0.9.9)<br/>
	 * Gets the image depth
	 * @link http://php.net/manual/en/imagick.getimagedepth.php
	 * @return int The image depth.
	 */
	public function getImageDepth () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Compares an image to a reconstructed image
	 * @link http://php.net/manual/en/imagick.getimagedistortion.php
	 * @param Imagick $reference <p>
	 * Imagick object to compare to.
	 * </p>
	 * @param int $metric <p>
	 * One of the metric type constants.
	 * </p>
	 * @return float the distortion metric used on the image (or the best guess
	 * thereof).
	 */
	public function getImageDistortion (Imagick $reference, $metric) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the extrema for the image
	 * @link http://php.net/manual/en/imagick.getimageextrema.php
	 * @return array an associative array with the keys "min" and "max".
	 */
	public function getImageExtrema () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the image disposal method
	 * @link http://php.net/manual/en/imagick.getimagedispose.php
	 * @return int the dispose method on success.
	 */
	public function getImageDispose () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the image gamma
	 * @link http://php.net/manual/en/imagick.getimagegamma.php
	 * @return float the image gamma on success.
	 */
	public function getImageGamma () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the chromaticy green primary point
	 * @link http://php.net/manual/en/imagick.getimagegreenprimary.php
	 * @return array an array with the keys "x" and "y" on success, throws an
	 * ImagickException on failure.
	 */
	public function getImageGreenPrimary () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the image height
	 * @link http://php.net/manual/en/imagick.getimageheight.php
	 * @return int the image height in pixels.
	 */
	public function getImageHeight () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the image histogram
	 * @link http://php.net/manual/en/imagick.getimagehistogram.php
	 * @return array the image histogram as an array of ImagickPixel objects.
	 */
	public function getImageHistogram () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the image interlace scheme
	 * @link http://php.net/manual/en/imagick.getimageinterlacescheme.php
	 * @return int the interlace scheme as an integer on success.
	 * Throw an
	 * <b>ImagickException</b> on error.
	 */
	public function getImageInterlaceScheme () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the image iterations
	 * @link http://php.net/manual/en/imagick.getimageiterations.php
	 * @return int the image iterations as an integer.
	 */
	public function getImageIterations () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the image matte color
	 * @link http://php.net/manual/en/imagick.getimagemattecolor.php
	 * @return ImagickPixel ImagickPixel object on success.
	 */
	public function getImageMatteColor () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the page geometry
	 * @link http://php.net/manual/en/imagick.getimagepage.php
	 * @return array the page geometry associated with the image in an array with the
	 * keys "width", "height", "x", and "y".
	 */
	public function getImagePage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the color of the specified pixel
	 * @link http://php.net/manual/en/imagick.getimagepixelcolor.php
	 * @param int $x <p>
	 * The x-coordinate of the pixel
	 * </p>
	 * @param int $y <p>
	 * The y-coordinate of the pixel
	 * </p>
	 * @return ImagickPixel an ImagickPixel instance for the color at the coordinates given.
	 */
	public function getImagePixelColor ($x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the named image profile
	 * @link http://php.net/manual/en/imagick.getimageprofile.php
	 * @param string $name <p>
	 * The name of the profile to return.
	 * </p>
	 * @return string a string containing the image profile.
	 */
	public function getImageProfile ($name) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the chromaticity red primary point
	 * @link http://php.net/manual/en/imagick.getimageredprimary.php
	 * @return array the chromaticity red primary point as an array with the keys "x"
	 * and "y".
	 * Throw an
	 * <b>ImagickException</b> on error.
	 */
	public function getImageRedPrimary () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the image rendering intent
	 * @link http://php.net/manual/en/imagick.getimagerenderingintent.php
	 * @return int the image rendering intent.
	 */
	public function getImageRenderingIntent () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the image X and Y resolution
	 * @link http://php.net/manual/en/imagick.getimageresolution.php
	 * @return array the resolution as an array.
	 */
	public function getImageResolution () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the image scene
	 * @link http://php.net/manual/en/imagick.getimagescene.php
	 * @return int the image scene.
	 */
	public function getImageScene () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Generates an SHA-256 message digest
	 * @link http://php.net/manual/en/imagick.getimagesignature.php
	 * @return string a string containing the SHA-256 hash of the file.
	 */
	public function getImageSignature () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the image ticks-per-second
	 * @link http://php.net/manual/en/imagick.getimagetickspersecond.php
	 * @return int the image ticks-per-second.
	 */
	public function getImageTicksPerSecond () {}

	/**
	 * (PECL imagick 0.9.10-0.9.9)<br/>
	 * Gets the potential image type
	 * @link http://php.net/manual/en/imagick.getimagetype.php
	 * @return int the potential image type.
	 * <b>imagick::IMGTYPE_UNDEFINED</b>
	 * <b>imagick::IMGTYPE_BILEVEL</b>
	 * <b>imagick::IMGTYPE_GRAYSCALE</b>
	 * <b>imagick::IMGTYPE_GRAYSCALEMATTE</b>
	 * <b>imagick::IMGTYPE_PALETTE</b>
	 * <b>imagick::IMGTYPE_PALETTEMATTE</b>
	 * <b>imagick::IMGTYPE_TRUECOLOR</b>
	 * <b>imagick::IMGTYPE_TRUECOLORMATTE</b>
	 * <b>imagick::IMGTYPE_COLORSEPARATION</b>
	 * <b>imagick::IMGTYPE_COLORSEPARATIONMATTE</b>
	 * <b>imagick::IMGTYPE_OPTIMIZE</b>
	 */
	public function getImageType () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the image units of resolution
	 * @link http://php.net/manual/en/imagick.getimageunits.php
	 * @return int the image units of resolution.
	 */
	public function getImageUnits () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the virtual pixel method
	 * @link http://php.net/manual/en/imagick.getimagevirtualpixelmethod.php
	 * @return int the virtual pixel method on success.
	 */
	public function getImageVirtualPixelMethod () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the chromaticity white point
	 * @link http://php.net/manual/en/imagick.getimagewhitepoint.php
	 * @return array the chromaticity white point as an associative array with the keys
	 * "x" and "y".
	 */
	public function getImageWhitePoint () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the image width
	 * @link http://php.net/manual/en/imagick.getimagewidth.php
	 * @return int the image width.
	 */
	public function getImageWidth () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the number of images in the object
	 * @link http://php.net/manual/en/imagick.getnumberimages.php
	 * @return int the number of images associated with Imagick object.
	 */
	public function getNumberImages () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the image total ink density
	 * @link http://php.net/manual/en/imagick.getimagetotalinkdensity.php
	 * @return float the image total ink density of the image.
	 * Throw an
	 * <b>ImagickException</b> on error.
	 */
	public function getImageTotalInkDensity () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Extracts a region of the image
	 * @link http://php.net/manual/en/imagick.getimageregion.php
	 * @param int $width <p>
	 * The width of the extracted region.
	 * </p>
	 * @param int $height <p>
	 * The height of the extracted region.
	 * </p>
	 * @param int $x <p>
	 * X-coordinate of the top-left corner of the extracted region.
	 * </p>
	 * @param int $y <p>
	 * Y-coordinate of the top-left corner of the extracted region.
	 * </p>
	 * @return Imagick Extracts a region of the image and returns it as a new wand.
	 */
	public function getImageRegion ($width, $height, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Creates a new image as a copy
	 * @link http://php.net/manual/en/imagick.implodeimage.php
	 * @param float $radius <p>
	 * The radius of the implode
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function implodeImage ($radius) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adjusts the levels of an image
	 * @link http://php.net/manual/en/imagick.levelimage.php
	 * @param float $blackPoint <p>
	 * The image black point
	 * </p>
	 * @param float $gamma <p>
	 * The gamma value
	 * </p>
	 * @param float $whitePoint <p>
	 * The image white point
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function levelImage ($blackPoint, $gamma, $whitePoint, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Scales an image proportionally 2x
	 * @link http://php.net/manual/en/imagick.magnifyimage.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function magnifyImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Replaces the colors of an image with the closest color from a reference image.
	 * @link http://php.net/manual/en/imagick.mapimage.php
	 * @param Imagick $map
	 * @param bool $dither
	 * @return bool <b>TRUE</b> on success.
	 */
	public function mapImage (Imagick $map, $dither) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Changes the transparency value of a color
	 * @link http://php.net/manual/en/imagick.mattefloodfillimage.php
	 * @param float $alpha <p>
	 * The level of transparency: 1.0 is fully opaque and 0.0 is fully
	 * transparent.
	 * </p>
	 * @param float $fuzz <p>
	 * The fuzz member of image defines how much tolerance is acceptable to
	 * consider two colors as the same.
	 * </p>
	 * @param mixed $bordercolor <p>
	 * An <b>ImagickPixel</b> object or string representing the border color.
	 * </p>
	 * @param int $x <p>
	 * The starting x coordinate of the operation.
	 * </p>
	 * @param int $y <p>
	 * The starting y coordinate of the operation.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function matteFloodfillImage ($alpha, $fuzz, $bordercolor, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Applies a digital filter
	 * @link http://php.net/manual/en/imagick.medianfilterimage.php
	 * @param float $radius <p>
	 * The radius of the pixel neighborhood.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function medianFilterImage ($radius) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Negates the colors in the reference image
	 * @link http://php.net/manual/en/imagick.negateimage.php
	 * @param bool $gray <p>
	 * Whether to only negate grayscale pixels within the image.
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function negateImage ($gray, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Change any pixel that matches color
	 * @link http://php.net/manual/en/imagick.paintopaqueimage.php
	 * @param mixed $target <p>
	 * Change this target color to the fill color within the image. An
	 * ImagickPixel object or a string representing the target color.
	 * </p>
	 * @param mixed $fill <p>
	 * An ImagickPixel object or a string representing the fill color.
	 * </p>
	 * @param float $fuzz <p>
	 * The fuzz member of image defines how much tolerance is acceptable to
	 * consider two colors as the same.
	 * </p>
	 * @param int $channel [optional] <p>
	 * Provide any channel constant that is valid for your channel mode. To
	 * apply to more than one channel, combine channeltype constants using
	 * bitwise operators. Refer to this
	 * list of channel constants.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function paintOpaqueImage ($target, $fill, $fuzz, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Changes any pixel that matches color with the color defined by fill
	 * @link http://php.net/manual/en/imagick.painttransparentimage.php
	 * @param mixed $target <p>
	 * Change this target color to specified opacity value within the image.
	 * </p>
	 * @param float $alpha <p>
	 * The level of transparency: 1.0 is fully opaque and 0.0 is fully
	 * transparent.
	 * </p>
	 * @param float $fuzz <p>
	 * The fuzz member of image defines how much tolerance is acceptable to
	 * consider two colors as the same.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function paintTransparentImage ($target, $alpha, $fuzz) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Quickly pin-point appropriate parameters for image processing
	 * @link http://php.net/manual/en/imagick.previewimages.php
	 * @param int $preview <p>
	 * Preview type. See Preview type constants
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function previewImages ($preview) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adds or removes a profile from an image
	 * @link http://php.net/manual/en/imagick.profileimage.php
	 * @param string $name
	 * @param string $profile
	 * @return bool <b>TRUE</b> on success.
	 */
	public function profileImage ($name, $profile) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Analyzes the colors within a reference image
	 * @link http://php.net/manual/en/imagick.quantizeimage.php
	 * @param int $numberColors
	 * @param int $colorspace
	 * @param int $treedepth
	 * @param bool $dither
	 * @param bool $measureError
	 * @return bool <b>TRUE</b> on success.
	 */
	public function quantizeImage ($numberColors, $colorspace, $treedepth, $dither, $measureError) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Analyzes the colors within a sequence of images
	 * @link http://php.net/manual/en/imagick.quantizeimages.php
	 * @param int $numberColors
	 * @param int $colorspace
	 * @param int $treedepth
	 * @param bool $dither
	 * @param bool $measureError
	 * @return bool <b>TRUE</b> on success.
	 */
	public function quantizeImages ($numberColors, $colorspace, $treedepth, $dither, $measureError) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Smooths the contours of an image
	 * @link http://php.net/manual/en/imagick.reducenoiseimage.php
	 * @param float $radius
	 * @return bool <b>TRUE</b> on success.
	 */
	public function reduceNoiseImage ($radius) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Removes the named image profile and returns it
	 * @link http://php.net/manual/en/imagick.removeimageprofile.php
	 * @param string $name
	 * @return string a string containing the profile of the image.
	 */
	public function removeImageProfile ($name) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Separates a channel from the image
	 * @link http://php.net/manual/en/imagick.separateimagechannel.php
	 * @param int $channel
	 * @return bool <b>TRUE</b> on success.
	 */
	public function separateImageChannel ($channel) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sepia tones an image
	 * @link http://php.net/manual/en/imagick.sepiatoneimage.php
	 * @param float $threshold
	 * @return bool <b>TRUE</b> on success.
	 */
	public function sepiaToneImage ($threshold) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image bias for any method that convolves an image
	 * @link http://php.net/manual/en/imagick.setimagebias.php
	 * @param float $bias
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageBias ($bias) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image chromaticity blue primary point
	 * @link http://php.net/manual/en/imagick.setimageblueprimary.php
	 * @param float $x
	 * @param float $y
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageBluePrimary ($x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image border color
	 * @link http://php.net/manual/en/imagick.setimagebordercolor.php
	 * @param mixed $border <p>
	 * The border color
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageBorderColor ($border) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the depth of a particular image channel
	 * @link http://php.net/manual/en/imagick.setimagechanneldepth.php
	 * @param int $channel
	 * @param int $depth
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageChannelDepth ($channel, $depth) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the color of the specified colormap index
	 * @link http://php.net/manual/en/imagick.setimagecolormapcolor.php
	 * @param int $index
	 * @param ImagickPixel $color
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageColormapColor ($index, ImagickPixel $color) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image colorspace
	 * @link http://php.net/manual/en/imagick.setimagecolorspace.php
	 * @param int $colorspace <p>
	 * One of the COLORSPACE constants
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageColorspace ($colorspace) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image disposal method
	 * @link http://php.net/manual/en/imagick.setimagedispose.php
	 * @param int $dispose
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageDispose ($dispose) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image size
	 * @link http://php.net/manual/en/imagick.setimageextent.php
	 * @param int $columns
	 * @param int $rows
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageExtent ($columns, $rows) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image chromaticity green primary point
	 * @link http://php.net/manual/en/imagick.setimagegreenprimary.php
	 * @param float $x
	 * @param float $y
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageGreenPrimary ($x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image compression
	 * @link http://php.net/manual/en/imagick.setimageinterlacescheme.php
	 * @param int $interlace_scheme
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageInterlaceScheme ($interlace_scheme) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adds a named profile to the Imagick object
	 * @link http://php.net/manual/en/imagick.setimageprofile.php
	 * @param string $name
	 * @param string $profile
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageProfile ($name, $profile) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image chromaticity red primary point
	 * @link http://php.net/manual/en/imagick.setimageredprimary.php
	 * @param float $x
	 * @param float $y
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageRedPrimary ($x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image rendering intent
	 * @link http://php.net/manual/en/imagick.setimagerenderingintent.php
	 * @param int $rendering_intent
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageRenderingIntent ($rendering_intent) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image virtual pixel method
	 * @link http://php.net/manual/en/imagick.setimagevirtualpixelmethod.php
	 * @param int $method
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageVirtualPixelMethod ($method) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image chromaticity white point
	 * @link http://php.net/manual/en/imagick.setimagewhitepoint.php
	 * @param float $x
	 * @param float $y
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageWhitePoint ($x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adjusts the contrast of an image
	 * @link http://php.net/manual/en/imagick.sigmoidalcontrastimage.php
	 * @param bool $sharpen
	 * @param float $alpha
	 * @param float $beta
	 * @param int $channel [optional]
	 * @return bool <b>TRUE</b> on success.
	 */
	public function sigmoidalContrastImage ($sharpen, $alpha, $beta, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Composites two images
	 * @link http://php.net/manual/en/imagick.stereoimage.php
	 * @param Imagick $offset_wand
	 * @return bool <b>TRUE</b> on success.
	 */
	public function stereoImage (Imagick $offset_wand) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Repeatedly tiles the texture image
	 * @link http://php.net/manual/en/imagick.textureimage.php
	 * @param Imagick $texture_wand
	 * @return bool <b>TRUE</b> on success.
	 */
	public function textureImage (Imagick $texture_wand) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Applies a color vector to each pixel in the image
	 * @link http://php.net/manual/en/imagick.tintimage.php
	 * @param mixed $tint
	 * @param mixed $opacity
	 * @return bool <b>TRUE</b> on success.
	 */
	public function tintImage ($tint, $opacity) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sharpens an image
	 * @link http://php.net/manual/en/imagick.unsharpmaskimage.php
	 * @param float $radius
	 * @param float $sigma
	 * @param float $amount
	 * @param float $threshold
	 * @param int $channel [optional]
	 * @return bool <b>TRUE</b> on success.
	 */
	public function unsharpMaskImage ($radius, $sigma, $amount, $threshold, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns a new Imagick object
	 * @link http://php.net/manual/en/imagick.getimage.php
	 * @return Imagick a new Imagick object with the current image sequence.
	 */
	public function getImage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adds new image to Imagick object image list
	 * @link http://php.net/manual/en/imagick.addimage.php
	 * @param Imagick $source <p>
	 * The source Imagick object
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function addImage (Imagick $source) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Replaces image in the object
	 * @link http://php.net/manual/en/imagick.setimage.php
	 * @param Imagick $replace <p>
	 * The replace Imagick object
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImage (Imagick $replace) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Creates a new image
	 * @link http://php.net/manual/en/imagick.newimage.php
	 * @param int $cols <p>
	 * Columns in the new image
	 * </p>
	 * @param int $rows <p>
	 * Rows in the new image
	 * </p>
	 * @param mixed $background <p>
	 * The background color used for this image
	 * </p>
	 * @param string $format [optional] <p>
	 * Image format. This parameter was added in Imagick version 2.0.1.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function newImage ($cols, $rows, $background, $format = null) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Creates a new image
	 * @link http://php.net/manual/en/imagick.newpseudoimage.php
	 * @param int $columns <p>
	 * columns in the new image
	 * </p>
	 * @param int $rows <p>
	 * rows in the new image
	 * </p>
	 * @param string $pseudoString <p>
	 * string containing pseudo image definition.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function newPseudoImage ($columns, $rows, $pseudoString) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the object compression type
	 * @link http://php.net/manual/en/imagick.getcompression.php
	 * @return int the compression constant
	 */
	public function getCompression () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the object compression quality
	 * @link http://php.net/manual/en/imagick.getcompressionquality.php
	 * @return int integer describing the compression quality
	 */
	public function getCompressionQuality () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the ImageMagick API copyright as a string
	 * @link http://php.net/manual/en/imagick.getcopyright.php
	 * @return string a string containing the copyright notice of Imagemagick and
	 * Magickwand C API.
	 */
	public static function getCopyright () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * The filename associated with an image sequence
	 * @link http://php.net/manual/en/imagick.getfilename.php
	 * @return string a string on success.
	 */
	public function getFilename () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the format of the Imagick object
	 * @link http://php.net/manual/en/imagick.getformat.php
	 * @return string the format of the image.
	 */
	public function getFormat () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the ImageMagick home URL
	 * @link http://php.net/manual/en/imagick.gethomeurl.php
	 * @return string a link to the imagemagick homepage.
	 */
	public static function getHomeURL () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the object interlace scheme
	 * @link http://php.net/manual/en/imagick.getinterlacescheme.php
	 * @return int Gets the wand interlace
	 * scheme.
	 */
	public function getInterlaceScheme () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns a value associated with the specified key
	 * @link http://php.net/manual/en/imagick.getoption.php
	 * @param string $key <p>
	 * The name of the option
	 * </p>
	 * @return string a value associated with a wand and the specified key.
	 */
	public function getOption ($key) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the ImageMagick package name
	 * @link http://php.net/manual/en/imagick.getpackagename.php
	 * @return string the ImageMagick package name as a string.
	 */
	public static function getPackageName () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the page geometry
	 * @link http://php.net/manual/en/imagick.getpage.php
	 * @return array the page geometry associated with the Imagick object in
	 * an associative array with the keys "width", "height", "x", and "y",
	 * throwing ImagickException on error.
	 */
	public function getPage () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the quantum depth
	 * @link http://php.net/manual/en/imagick.getquantumdepth.php
	 * @return array the Imagick quantum depth as a string.
	 */
	public static function getQuantumDepth () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the Imagick quantum range
	 * @link http://php.net/manual/en/imagick.getquantumrange.php
	 * @return array the Imagick quantum range as a string.
	 */
	public static function getQuantumRange () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the ImageMagick release date
	 * @link http://php.net/manual/en/imagick.getreleasedate.php
	 * @return string the ImageMagick release date as a string.
	 */
	public static function getReleaseDate () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the specified resource's memory usage
	 * @link http://php.net/manual/en/imagick.getresource.php
	 * @param int $type <p>
	 * Refer to the list of resourcetype constants.
	 * </p>
	 * @return int the specified resource's memory usage in megabytes.
	 */
	public static function getResource ($type) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the specified resource limit
	 * @link http://php.net/manual/en/imagick.getresourcelimit.php
	 * @param int $type <p>
	 * Refer to the list of resourcetype constants.
	 * </p>
	 * @return int the specified resource limit in megabytes.
	 */
	public static function getResourceLimit ($type) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the horizontal and vertical sampling factor
	 * @link http://php.net/manual/en/imagick.getsamplingfactors.php
	 * @return array an associative array with the horizontal and vertical sampling
	 * factors of the image.
	 */
	public function getSamplingFactors () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the size associated with the Imagick object
	 * @link http://php.net/manual/en/imagick.getsize.php
	 * @return array the size associated with the Imagick object as an array with the
	 * keys "columns" and "rows".
	 */
	public function getSize () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the ImageMagick API version
	 * @link http://php.net/manual/en/imagick.getversion.php
	 * @return array the ImageMagick API version as a string and as a number.
	 */
	public static function getVersion () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the object's default background color
	 * @link http://php.net/manual/en/imagick.setbackgroundcolor.php
	 * @param mixed $background
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setBackgroundColor ($background) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the object's default compression type
	 * @link http://php.net/manual/en/imagick.setcompression.php
	 * @param int $compression
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setCompression ($compression) {}

	/**
	 * (PECL imagick 0.9.10-0.9.9)<br/>
	 * Sets the object's default compression quality
	 * @link http://php.net/manual/en/imagick.setcompressionquality.php
	 * @param int $quality
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setCompressionQuality ($quality) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the filename before you read or write the image
	 * @link http://php.net/manual/en/imagick.setfilename.php
	 * @param string $filename
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setFilename ($filename) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the format of the Imagick object
	 * @link http://php.net/manual/en/imagick.setformat.php
	 * @param string $format
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setFormat ($format) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image compression
	 * @link http://php.net/manual/en/imagick.setinterlacescheme.php
	 * @param int $interlace_scheme
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setInterlaceScheme ($interlace_scheme) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Set an option
	 * @link http://php.net/manual/en/imagick.setoption.php
	 * @param string $key
	 * @param string $value
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setOption ($key, $value) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the page geometry of the Imagick object
	 * @link http://php.net/manual/en/imagick.setpage.php
	 * @param int $width
	 * @param int $height
	 * @param int $x
	 * @param int $y
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setPage ($width, $height, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the limit for a particular resource in megabytes
	 * @link http://php.net/manual/en/imagick.setresourcelimit.php
	 * @param int $type <p>
	 * Refer to the list of resourcetype constants.
	 * </p>
	 * @param int $limit <p>
	 * The resource limit. The unit depends on the type of the resource being limited.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public static function setResourceLimit ($type, $limit) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image resolution
	 * @link http://php.net/manual/en/imagick.setresolution.php
	 * @param float $x_resolution <p>
	 * The horizontal resolution.
	 * </p>
	 * @param float $y_resolution <p>
	 * The vertical resolution.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setResolution ($x_resolution, $y_resolution) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image sampling factors
	 * @link http://php.net/manual/en/imagick.setsamplingfactors.php
	 * @param array $factors
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setSamplingFactors (array $factors) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the size of the Imagick object
	 * @link http://php.net/manual/en/imagick.setsize.php
	 * @param int $columns
	 * @param int $rows
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setSize ($columns, $rows) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the image type attribute
	 * @link http://php.net/manual/en/imagick.settype.php
	 * @param int $image_type
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setType ($image_type) {}

	public function key () {}

	public function next () {}

	public function rewind () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Checks if the current item is valid
	 * @link http://php.net/manual/en/imagick.valid.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function valid () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns a reference to the current Imagick object
	 * @link http://php.net/manual/en/imagick.current.php
	 * @return Imagick self on success.
	 */
	public function current () {}

}

/**
 * @method ImagickDraw clone() (PECL imagick 2.0.0)<br/>Makes an exact copy of the specified ImagickDraw object
 * @link http://php.net/manual/en/class.imagickdraw.php
 */
class ImagickDraw  {

	public function resetVectorGraphics () {}

	public function getTextKerning () {}

	/**
	 * @param $kerning
	 */
	public function setTextKerning ($kerning) {}

	public function getTextInterWordSpacing () {}

	/**
	 * @param $spacing
	 */
	public function setTextInterWordSpacing ($spacing) {}

	public function getTextInterLineSpacing () {}

	/**
	 * @param $spacing
	 */
	public function setTextInterLineSpacing ($spacing) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * The ImagickDraw constructor
	 * @link http://php.net/manual/en/imagickdraw.construct.php
	 */
	public function __construct () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the fill color to be used for drawing filled objects
	 * @link http://php.net/manual/en/imagickdraw.setfillcolor.php
	 * @param ImagickPixel $fill_pixel <p>
	 * ImagickPixel to use to set the color
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setFillColor (ImagickPixel $fill_pixel) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the opacity to use when drawing using the fill color or fill texture
	 * @link http://php.net/manual/en/imagickdraw.setfillalpha.php
	 * @param float $opacity <p>
	 * fill alpha
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setFillAlpha ($opacity) {}

	/**
	 * @param $x_resolution
	 * @param $y_resolution
	 */
	public function setResolution ($x_resolution, $y_resolution) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the color used for stroking object outlines
	 * @link http://php.net/manual/en/imagickdraw.setstrokecolor.php
	 * @param ImagickPixel $stroke_pixel <p>
	 * the stroke color
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setStrokeColor (ImagickPixel $stroke_pixel) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Specifies the opacity of stroked object outlines
	 * @link http://php.net/manual/en/imagickdraw.setstrokealpha.php
	 * @param float $opacity <p>
	 * opacity
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setStrokeAlpha ($opacity) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the width of the stroke used to draw object outlines
	 * @link http://php.net/manual/en/imagickdraw.setstrokewidth.php
	 * @param float $stroke_width <p>
	 * stroke width
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setStrokeWidth ($stroke_width) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Clears the ImagickDraw
	 * @link http://php.net/manual/en/imagickdraw.clear.php
	 * @return bool an ImagickDraw object.
	 */
	public function clear () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a circle
	 * @link http://php.net/manual/en/imagickdraw.circle.php
	 * @param float $ox <p>
	 * origin x coordinate
	 * </p>
	 * @param float $oy <p>
	 * origin y coordinate
	 * </p>
	 * @param float $px <p>
	 * perimeter x coordinate
	 * </p>
	 * @param float $py <p>
	 * perimeter y coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function circle ($ox, $oy, $px, $py) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws text on the image
	 * @link http://php.net/manual/en/imagickdraw.annotation.php
	 * @param float $x <p>
	 * The x coordinate where text is drawn
	 * </p>
	 * @param float $y <p>
	 * The y coordinate where text is drawn
	 * </p>
	 * @param string $text <p>
	 * The text to draw on the image
	 * </p>
	 * @return bool No value is returned.
	 */
	public function annotation ($x, $y, $text) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Controls whether text is antialiased
	 * @link http://php.net/manual/en/imagickdraw.settextantialias.php
	 * @param bool $antiAlias
	 * @return bool No value is returned.
	 */
	public function setTextAntialias ($antiAlias) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Specifies specifies the text code set
	 * @link http://php.net/manual/en/imagickdraw.settextencoding.php
	 * @param string $encoding <p>
	 * the encoding name
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setTextEncoding ($encoding) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the fully-specified font to use when annotating with text
	 * @link http://php.net/manual/en/imagickdraw.setfont.php
	 * @param string $font_name
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setFont ($font_name) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the font family to use when annotating with text
	 * @link http://php.net/manual/en/imagickdraw.setfontfamily.php
	 * @param string $font_family <p>
	 * the font family
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setFontFamily ($font_family) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the font pointsize to use when annotating with text
	 * @link http://php.net/manual/en/imagickdraw.setfontsize.php
	 * @param float $pointsize <p>
	 * the point size
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setFontSize ($pointsize) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the font style to use when annotating with text
	 * @link http://php.net/manual/en/imagickdraw.setfontstyle.php
	 * @param int $style <p>
	 * STYLETYPE_ constant
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setFontStyle ($style) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the font weight
	 * @link http://php.net/manual/en/imagickdraw.setfontweight.php
	 * @param int $font_weight
	 * @return bool
	 */
	public function setFontWeight ($font_weight) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the font
	 * @link http://php.net/manual/en/imagickdraw.getfont.php
	 * @return string a string on success and false if no font is set.
	 */
	public function getFont () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the font family
	 * @link http://php.net/manual/en/imagickdraw.getfontfamily.php
	 * @return string the font family currently selected or false if font family is not set.
	 */
	public function getFontFamily () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the font pointsize
	 * @link http://php.net/manual/en/imagickdraw.getfontsize.php
	 * @return float the font size associated with the current ImagickDraw object.
	 */
	public function getFontSize () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the font style
	 * @link http://php.net/manual/en/imagickdraw.getfontstyle.php
	 * @return int the font style constant (STYLE_) associated with the ImagickDraw object
	 * or 0 if no style is set.
	 */
	public function getFontStyle () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the font weight
	 * @link http://php.net/manual/en/imagickdraw.getfontweight.php
	 * @return int an int on success and 0 if no weight is set.
	 */
	public function getFontWeight () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Frees all associated resources
	 * @link http://php.net/manual/en/imagickdraw.destroy.php
	 * @return bool No value is returned.
	 */
	public function destroy () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a rectangle
	 * @link http://php.net/manual/en/imagickdraw.rectangle.php
	 * @param float $x1 <p>
	 * x coordinate of the top left corner
	 * </p>
	 * @param float $y1 <p>
	 * y coordinate of the top left corner
	 * </p>
	 * @param float $x2 <p>
	 * x coordinate of the bottom right corner
	 * </p>
	 * @param float $y2 <p>
	 * y coordinate of the bottom right corner
	 * </p>
	 * @return bool No value is returned.
	 */
	public function rectangle ($x1, $y1, $x2, $y2) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a rounded rectangle
	 * @link http://php.net/manual/en/imagickdraw.roundrectangle.php
	 * @param float $x1 <p>
	 * x coordinate of the top left corner
	 * </p>
	 * @param float $y1 <p>
	 * y coordinate of the top left corner
	 * </p>
	 * @param float $x2 <p>
	 * x coordinate of the bottom right
	 * </p>
	 * @param float $y2 <p>
	 * y coordinate of the bottom right
	 * </p>
	 * @param float $rx <p>
	 * x rounding
	 * </p>
	 * @param float $ry <p>
	 * y rounding
	 * </p>
	 * @return bool No value is returned.
	 */
	public function roundRectangle ($x1, $y1, $x2, $y2, $rx, $ry) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws an ellipse on the image
	 * @link http://php.net/manual/en/imagickdraw.ellipse.php
	 * @param float $ox
	 * @param float $oy
	 * @param float $rx
	 * @param float $ry
	 * @param float $start
	 * @param float $end
	 * @return bool No value is returned.
	 */
	public function ellipse ($ox, $oy, $rx, $ry, $start, $end) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Skews the current coordinate system in the horizontal direction
	 * @link http://php.net/manual/en/imagickdraw.skewx.php
	 * @param float $degrees <p>
	 * degrees to skew
	 * </p>
	 * @return bool No value is returned.
	 */
	public function skewX ($degrees) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Skews the current coordinate system in the vertical direction
	 * @link http://php.net/manual/en/imagickdraw.skewy.php
	 * @param float $degrees <p>
	 * degrees to skew
	 * </p>
	 * @return bool No value is returned.
	 */
	public function skewY ($degrees) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Applies a translation to the current coordinate system
	 * @link http://php.net/manual/en/imagickdraw.translate.php
	 * @param float $x <p>
	 * horizontal translation
	 * </p>
	 * @param float $y <p>
	 * vertical translation
	 * </p>
	 * @return bool No value is returned.
	 */
	public function translate ($x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a line
	 * @link http://php.net/manual/en/imagickdraw.line.php
	 * @param float $sx <p>
	 * starting x coordinate
	 * </p>
	 * @param float $sy <p>
	 * starting y coordinate
	 * </p>
	 * @param float $ex <p>
	 * ending x coordinate
	 * </p>
	 * @param float $ey <p>
	 * ending y coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function line ($sx, $sy, $ex, $ey) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws an arc
	 * @link http://php.net/manual/en/imagickdraw.arc.php
	 * @param float $sx <p>
	 * Starting x ordinate of bounding rectangle
	 * </p>
	 * @param float $sy <p>
	 * starting y ordinate of bounding rectangle
	 * </p>
	 * @param float $ex <p>
	 * ending x ordinate of bounding rectangle
	 * </p>
	 * @param float $ey <p>
	 * ending y ordinate of bounding rectangle
	 * </p>
	 * @param float $sd <p>
	 * starting degrees of rotation
	 * </p>
	 * @param float $ed <p>
	 * ending degrees of rotation
	 * </p>
	 * @return bool No value is returned.
	 */
	public function arc ($sx, $sy, $ex, $ey, $sd, $ed) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Paints on the image's opacity channel
	 * @link http://php.net/manual/en/imagickdraw.matte.php
	 * @param float $x <p>
	 * x coordinate of the matte
	 * </p>
	 * @param float $y <p>
	 * y coordinate of the matte
	 * </p>
	 * @param int $paintMethod <p>
	 * PAINT_ constant
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function matte ($x, $y, $paintMethod) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a polygon
	 * @link http://php.net/manual/en/imagickdraw.polygon.php
	 * @param array $coordinates <p>
	 * multidimensional array like array( array( 'x' => 3, 'y' => 4 ), array( 'x' => 2, 'y' => 6 ) );
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function polygon (array $coordinates) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a point
	 * @link http://php.net/manual/en/imagickdraw.point.php
	 * @param float $x <p>
	 * point's x coordinate
	 * </p>
	 * @param float $y <p>
	 * point's y coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function point ($x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the text decoration
	 * @link http://php.net/manual/en/imagickdraw.gettextdecoration.php
	 * @return int one of the DECORATION_ constants
	 * and 0 if no decoration is set.
	 */
	public function getTextDecoration () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the code set used for text annotations
	 * @link http://php.net/manual/en/imagickdraw.gettextencoding.php
	 * @return string a string specifying the code set
	 * or false if text encoding is not set.
	 */
	public function getTextEncoding () {}

	public function getFontStretch () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the font stretch to use when annotating with text
	 * @link http://php.net/manual/en/imagickdraw.setfontstretch.php
	 * @param int $fontStretch <p>
	 * STRETCH_ constant
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setFontStretch ($fontStretch) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Controls whether stroked outlines are antialiased
	 * @link http://php.net/manual/en/imagickdraw.setstrokeantialias.php
	 * @param bool $stroke_antialias <p>
	 * the antialias setting
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setStrokeAntialias ($stroke_antialias) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Specifies a text alignment
	 * @link http://php.net/manual/en/imagickdraw.settextalignment.php
	 * @param int $alignment <p>
	 * ALIGN_ constant
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setTextAlignment ($alignment) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Specifies a decoration
	 * @link http://php.net/manual/en/imagickdraw.settextdecoration.php
	 * @param int $decoration <p>
	 * DECORATION_ constant
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setTextDecoration ($decoration) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Specifies the color of a background rectangle
	 * @link http://php.net/manual/en/imagickdraw.settextundercolor.php
	 * @param ImagickPixel $under_color <p>
	 * the under color
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setTextUnderColor (ImagickPixel $under_color) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the overall canvas size
	 * @link http://php.net/manual/en/imagickdraw.setviewbox.php
	 * @param int $x1 <p>
	 * left x coordinate
	 * </p>
	 * @param int $y1 <p>
	 * left y coordinate
	 * </p>
	 * @param int $x2 <p>
	 * right x coordinate
	 * </p>
	 * @param int $y2 <p>
	 * right y coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setViewbox ($x1, $y1, $x2, $y2) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adjusts the current affine transformation matrix
	 * @link http://php.net/manual/en/imagickdraw.affine.php
	 * @param array $affine <p>
	 * Affine matrix parameters
	 * </p>
	 * @return bool No value is returned.
	 */
	public function affine (array $affine) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a bezier curve
	 * @link http://php.net/manual/en/imagickdraw.bezier.php
	 * @param array $coordinates <p>
	 * Multidimensional array like array( array( 'x' => 1, 'y' => 2 ),
	 * array( 'x' => 3, 'y' => 4 ) )
	 * </p>
	 * @return bool No value is returned.
	 */
	public function bezier (array $coordinates) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Composites an image onto the current image
	 * @link http://php.net/manual/en/imagickdraw.composite.php
	 * @param int $compose <p>
	 * composition operator. One of COMPOSITE_ constants
	 * </p>
	 * @param float $x <p>
	 * x coordinate of the top left corner
	 * </p>
	 * @param float $y <p>
	 * y coordinate of the top left corner
	 * </p>
	 * @param float $width <p>
	 * width of the composition image
	 * </p>
	 * @param float $height <p>
	 * height of the composition image
	 * </p>
	 * @param Imagick $compositeWand <p>
	 * the Imagick object where composition image is taken from
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function composite ($compose, $x, $y, $width, $height, Imagick $compositeWand) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws color on image
	 * @link http://php.net/manual/en/imagickdraw.color.php
	 * @param float $x <p>
	 * x coordinate of the paint
	 * </p>
	 * @param float $y <p>
	 * y coordinate of the paint
	 * </p>
	 * @param int $paintMethod <p>
	 * one of the PAINT_ constants
	 * </p>
	 * @return bool No value is returned.
	 */
	public function color ($x, $y, $paintMethod) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adds a comment
	 * @link http://php.net/manual/en/imagickdraw.comment.php
	 * @param string $comment <p>
	 * The comment string to add to vector output stream
	 * </p>
	 * @return bool No value is returned.
	 */
	public function comment ($comment) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Obtains the current clipping path ID
	 * @link http://php.net/manual/en/imagickdraw.getclippath.php
	 * @return string a string containing the clip path ID or false if no clip path exists.
	 */
	public function getClipPath () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the current polygon fill rule
	 * @link http://php.net/manual/en/imagickdraw.getcliprule.php
	 * @return int one of the FILLRULE_ constants.
	 */
	public function getClipRule () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the interpretation of clip path units
	 * @link http://php.net/manual/en/imagickdraw.getclipunits.php
	 * @return int an int on success.
	 */
	public function getClipUnits () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the fill color
	 * @link http://php.net/manual/en/imagickdraw.getfillcolor.php
	 * @return ImagickPixel an ImagickPixel object.
	 */
	public function getFillColor () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the opacity used when drawing
	 * @link http://php.net/manual/en/imagickdraw.getfillopacity.php
	 * @return float The opacity.
	 */
	public function getFillOpacity () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the fill rule
	 * @link http://php.net/manual/en/imagickdraw.getfillrule.php
	 * @return int a FILLRULE_ constant
	 */
	public function getFillRule () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the text placement gravity
	 * @link http://php.net/manual/en/imagickdraw.getgravity.php
	 * @return int a GRAVITY_ constant on success and 0 if no gravity is set.
	 */
	public function getGravity () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the current stroke antialias setting
	 * @link http://php.net/manual/en/imagickdraw.getstrokeantialias.php
	 * @return bool <b>TRUE</b> if antialiasing is on and false if it is off.
	 */
	public function getStrokeAntialias () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the color used for stroking object outlines
	 * @link http://php.net/manual/en/imagickdraw.getstrokecolor.php
	 * @return ImagickPixel an ImagickPixel object which describes the color.
	 */
	public function getStrokeColor () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns an array representing the pattern of dashes and gaps used to stroke paths
	 * @link http://php.net/manual/en/imagickdraw.getstrokedasharray.php
	 * @return array an array on success and empty array if not set.
	 */
	public function getStrokeDashArray () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the offset into the dash pattern to start the dash
	 * @link http://php.net/manual/en/imagickdraw.getstrokedashoffset.php
	 * @return float a float representing the offset and 0 if it's not set.
	 */
	public function getStrokeDashOffset () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the shape to be used at the end of open subpaths when they are stroked
	 * @link http://php.net/manual/en/imagickdraw.getstrokelinecap.php
	 * @return int one of the LINECAP_ constants or 0 if stroke linecap is not set.
	 */
	public function getStrokeLineCap () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the shape to be used at the corners of paths when they are stroked
	 * @link http://php.net/manual/en/imagickdraw.getstrokelinejoin.php
	 * @return int one of the LINEJOIN_ constants or 0 if stroke line join is not set.
	 */
	public function getStrokeLineJoin () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the stroke miter limit
	 * @link http://php.net/manual/en/imagickdraw.getstrokemiterlimit.php
	 * @return int an int describing the miter limit
	 * and 0 if no miter limit is set.
	 */
	public function getStrokeMiterLimit () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the opacity of stroked object outlines
	 * @link http://php.net/manual/en/imagickdraw.getstrokeopacity.php
	 * @return float a double describing the opacity.
	 */
	public function getStrokeOpacity () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the width of the stroke used to draw object outlines
	 * @link http://php.net/manual/en/imagickdraw.getstrokewidth.php
	 * @return float a double describing the stroke width.
	 */
	public function getStrokeWidth () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the text alignment
	 * @link http://php.net/manual/en/imagickdraw.gettextalignment.php
	 * @return int one of the ALIGN_ constants and 0 if no align is set.
	 */
	public function getTextAlignment () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the current text antialias setting
	 * @link http://php.net/manual/en/imagickdraw.gettextantialias.php
	 * @return bool <b>TRUE</b> if text is antialiased and false if not.
	 */
	public function getTextAntialias () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns a string containing vector graphics
	 * @link http://php.net/manual/en/imagickdraw.getvectorgraphics.php
	 * @return string a string containing the vector graphics.
	 */
	public function getVectorGraphics () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the text under color
	 * @link http://php.net/manual/en/imagickdraw.gettextundercolor.php
	 * @return ImagickPixel an ImagickPixel object describing the color.
	 */
	public function getTextUnderColor () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adds a path element to the current path
	 * @link http://php.net/manual/en/imagickdraw.pathclose.php
	 * @return bool No value is returned.
	 */
	public function pathClose () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a cubic Bezier curve
	 * @link http://php.net/manual/en/imagickdraw.pathcurvetoabsolute.php
	 * @param float $x1 <p>
	 * x coordinate of the first control point
	 * </p>
	 * @param float $y1 <p>
	 * y coordinate of the first control point
	 * </p>
	 * @param float $x2 <p>
	 * x coordinate of the second control point
	 * </p>
	 * @param float $y2 <p>
	 * y coordinate of the first control point
	 * </p>
	 * @param float $x <p>
	 * x coordinate of the curve end
	 * </p>
	 * @param float $y <p>
	 * y coordinate of the curve end
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathCurveToAbsolute ($x1, $y1, $x2, $y2, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a cubic Bezier curve
	 * @link http://php.net/manual/en/imagickdraw.pathcurvetorelative.php
	 * @param float $x1 <p>
	 * x coordinate of starting control point
	 * </p>
	 * @param float $y1 <p>
	 * y coordinate of starting control point
	 * </p>
	 * @param float $x2 <p>
	 * x coordinate of ending control point
	 * </p>
	 * @param float $y2 <p>
	 * y coordinate of ending control point
	 * </p>
	 * @param float $x <p>
	 * ending x coordinate
	 * </p>
	 * @param float $y <p>
	 * ending y coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathCurveToRelative ($x1, $y1, $x2, $y2, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a quadratic Bezier curve
	 * @link http://php.net/manual/en/imagickdraw.pathcurvetoquadraticbezierabsolute.php
	 * @param float $x1 <p>
	 * x coordinate of the control point
	 * </p>
	 * @param float $y1 <p>
	 * y coordinate of the control point
	 * </p>
	 * @param float $x <p>
	 * x coordinate of the end point
	 * </p>
	 * @param float $y <p>
	 * y coordinate of the end point
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathCurveToQuadraticBezierAbsolute ($x1, $y1, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a quadratic Bezier curve
	 * @link http://php.net/manual/en/imagickdraw.pathcurvetoquadraticbezierrelative.php
	 * @param float $x1 <p>
	 * starting x coordinate
	 * </p>
	 * @param float $y1 <p>
	 * starting y coordinate
	 * </p>
	 * @param float $x <p>
	 * ending x coordinate
	 * </p>
	 * @param float $y <p>
	 * ending y coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathCurveToQuadraticBezierRelative ($x1, $y1, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a quadratic Bezier curve
	 * @link http://php.net/manual/en/imagickdraw.pathcurvetoquadraticbeziersmoothabsolute.php
	 * @param float $x <p>
	 * ending x coordinate
	 * </p>
	 * @param float $y <p>
	 * ending y coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathCurveToQuadraticBezierSmoothAbsolute ($x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a quadratic Bezier curve
	 * @link http://php.net/manual/en/imagickdraw.pathcurvetoquadraticbeziersmoothrelative.php
	 * @param float $x <p>
	 * ending x coordinate
	 * </p>
	 * @param float $y <p>
	 * ending y coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathCurveToQuadraticBezierSmoothRelative ($x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a cubic Bezier curve
	 * @link http://php.net/manual/en/imagickdraw.pathcurvetosmoothabsolute.php
	 * @param float $x2 <p>
	 * x coordinate of the second control point
	 * </p>
	 * @param float $y2 <p>
	 * y coordinate of the second control point
	 * </p>
	 * @param float $x <p>
	 * x coordinate of the ending point
	 * </p>
	 * @param float $y <p>
	 * y coordinate of the ending point
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathCurveToSmoothAbsolute ($x2, $y2, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a cubic Bezier curve
	 * @link http://php.net/manual/en/imagickdraw.pathcurvetosmoothrelative.php
	 * @param float $x2 <p>
	 * x coordinate of the second control point
	 * </p>
	 * @param float $y2 <p>
	 * y coordinate of the second control point
	 * </p>
	 * @param float $x <p>
	 * x coordinate of the ending point
	 * </p>
	 * @param float $y <p>
	 * y coordinate of the ending point
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathCurveToSmoothRelative ($x2, $y2, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws an elliptical arc
	 * @link http://php.net/manual/en/imagickdraw.pathellipticarcabsolute.php
	 * @param float $rx <p>
	 * x radius
	 * </p>
	 * @param float $ry <p>
	 * y radius
	 * </p>
	 * @param float $x_axis_rotation <p>
	 * x axis rotation
	 * </p>
	 * @param bool $large_arc_flag <p>
	 * large arc flag
	 * </p>
	 * @param bool $sweep_flag <p>
	 * sweep flag
	 * </p>
	 * @param float $x <p>
	 * x coordinate
	 * </p>
	 * @param float $y <p>
	 * y coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathEllipticArcAbsolute ($rx, $ry, $x_axis_rotation, $large_arc_flag, $sweep_flag, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws an elliptical arc
	 * @link http://php.net/manual/en/imagickdraw.pathellipticarcrelative.php
	 * @param float $rx <p>
	 * x radius
	 * </p>
	 * @param float $ry <p>
	 * y radius
	 * </p>
	 * @param float $x_axis_rotation <p>
	 * x axis rotation
	 * </p>
	 * @param bool $large_arc_flag <p>
	 * large arc flag
	 * </p>
	 * @param bool $sweep_flag <p>
	 * sweep flag
	 * </p>
	 * @param float $x <p>
	 * x coordinate
	 * </p>
	 * @param float $y <p>
	 * y coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathEllipticArcRelative ($rx, $ry, $x_axis_rotation, $large_arc_flag, $sweep_flag, $x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Terminates the current path
	 * @link http://php.net/manual/en/imagickdraw.pathfinish.php
	 * @return bool No value is returned.
	 */
	public function pathFinish () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a line path
	 * @link http://php.net/manual/en/imagickdraw.pathlinetoabsolute.php
	 * @param float $x <p>
	 * starting x coordinate
	 * </p>
	 * @param float $y <p>
	 * ending x coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathLineToAbsolute ($x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a line path
	 * @link http://php.net/manual/en/imagickdraw.pathlinetorelative.php
	 * @param float $x <p>
	 * starting x coordinate
	 * </p>
	 * @param float $y <p>
	 * starting y coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathLineToRelative ($x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a horizontal line path
	 * @link http://php.net/manual/en/imagickdraw.pathlinetohorizontalabsolute.php
	 * @param float $x <p>
	 * x coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathLineToHorizontalAbsolute ($x) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a horizontal line
	 * @link http://php.net/manual/en/imagickdraw.pathlinetohorizontalrelative.php
	 * @param float $x <p>
	 * x coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathLineToHorizontalRelative ($x) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a vertical line
	 * @link http://php.net/manual/en/imagickdraw.pathlinetoverticalabsolute.php
	 * @param float $y <p>
	 * y coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathLineToVerticalAbsolute ($y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a vertical line path
	 * @link http://php.net/manual/en/imagickdraw.pathlinetoverticalrelative.php
	 * @param float $y <p>
	 * y coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathLineToVerticalRelative ($y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Starts a new sub-path
	 * @link http://php.net/manual/en/imagickdraw.pathmovetoabsolute.php
	 * @param float $x <p>
	 * x coordinate of the starting point
	 * </p>
	 * @param float $y <p>
	 * y coordinate of the starting point
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathMoveToAbsolute ($x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Starts a new sub-path
	 * @link http://php.net/manual/en/imagickdraw.pathmovetorelative.php
	 * @param float $x <p>
	 * target x coordinate
	 * </p>
	 * @param float $y <p>
	 * target y coordinate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pathMoveToRelative ($x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Declares the start of a path drawing list
	 * @link http://php.net/manual/en/imagickdraw.pathstart.php
	 * @return bool No value is returned.
	 */
	public function pathStart () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Draws a polyline
	 * @link http://php.net/manual/en/imagickdraw.polyline.php
	 * @param array $coordinates <p>
	 * array of x and y coordinates: array( array( 'x' => 4, 'y' => 6 ), array( 'x' => 8, 'y' => 10 ) )
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function polyline (array $coordinates) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Terminates a clip path definition
	 * @link http://php.net/manual/en/imagickdraw.popclippath.php
	 * @return bool No value is returned.
	 */
	public function popClipPath () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Terminates a definition list
	 * @link http://php.net/manual/en/imagickdraw.popdefs.php
	 * @return bool No value is returned.
	 */
	public function popDefs () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Terminates a pattern definition
	 * @link http://php.net/manual/en/imagickdraw.poppattern.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function popPattern () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Starts a clip path definition
	 * @link http://php.net/manual/en/imagickdraw.pushclippath.php
	 * @param string $clip_mask_id <p>
	 * Clip mask Id
	 * </p>
	 * @return bool No value is returned.
	 */
	public function pushClipPath ($clip_mask_id) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Indicates that following commands create named elements for early processing
	 * @link http://php.net/manual/en/imagickdraw.pushdefs.php
	 * @return bool No value is returned.
	 */
	public function pushDefs () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Indicates that subsequent commands up to a ImagickDraw::opPattern() command comprise the definition of a named pattern
	 * @link http://php.net/manual/en/imagickdraw.pushpattern.php
	 * @param string $pattern_id <p>
	 * the pattern Id
	 * </p>
	 * @param float $x <p>
	 * x coordinate of the top-left corner
	 * </p>
	 * @param float $y <p>
	 * y coordinate of the top-left corner
	 * </p>
	 * @param float $width <p>
	 * width of the pattern
	 * </p>
	 * @param float $height <p>
	 * height of the pattern
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function pushPattern ($pattern_id, $x, $y, $width, $height) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Renders all preceding drawing commands onto the image
	 * @link http://php.net/manual/en/imagickdraw.render.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function render () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Applies the specified rotation to the current coordinate space
	 * @link http://php.net/manual/en/imagickdraw.rotate.php
	 * @param float $degrees <p>
	 * degrees to rotate
	 * </p>
	 * @return bool No value is returned.
	 */
	public function rotate ($degrees) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Adjusts the scaling factor
	 * @link http://php.net/manual/en/imagickdraw.scale.php
	 * @param float $x <p>
	 * horizontal factor
	 * </p>
	 * @param float $y <p>
	 * vertical factor
	 * </p>
	 * @return bool No value is returned.
	 */
	public function scale ($x, $y) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Associates a named clipping path with the image
	 * @link http://php.net/manual/en/imagickdraw.setclippath.php
	 * @param string $clip_mask <p>
	 * the clipping path name
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setClipPath ($clip_mask) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Set the polygon fill rule to be used by the clipping path
	 * @link http://php.net/manual/en/imagickdraw.setcliprule.php
	 * @param int $fill_rule <p>
	 * FILLRULE_ constant
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setClipRule ($fill_rule) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the interpretation of clip path units
	 * @link http://php.net/manual/en/imagickdraw.setclipunits.php
	 * @param int $clip_units <p>
	 * the number of clip units
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setClipUnits ($clip_units) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the opacity to use when drawing using the fill color or fill texture
	 * @link http://php.net/manual/en/imagickdraw.setfillopacity.php
	 * @param float $fillOpacity <p>
	 * the fill opacity
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setFillOpacity ($fillOpacity) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the URL to use as a fill pattern for filling objects
	 * @link http://php.net/manual/en/imagickdraw.setfillpatternurl.php
	 * @param string $fill_url <p>
	 * URL to use to obtain fill pattern.
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function setFillPatternURL ($fill_url) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the fill rule to use while drawing polygons
	 * @link http://php.net/manual/en/imagickdraw.setfillrule.php
	 * @param int $fill_rule <p>
	 * FILLRULE_ constant
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setFillRule ($fill_rule) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the text placement gravity
	 * @link http://php.net/manual/en/imagickdraw.setgravity.php
	 * @param int $gravity <p>
	 * GRAVITY_ constant
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setGravity ($gravity) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the pattern used for stroking object outlines
	 * @link http://php.net/manual/en/imagickdraw.setstrokepatternurl.php
	 * @param string $stroke_url <p>
	 * stroke URL
	 * </p>
	 * @return bool imagick.imagickdraw.return.success;
	 */
	public function setStrokePatternURL ($stroke_url) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Specifies the offset into the dash pattern to start the dash
	 * @link http://php.net/manual/en/imagickdraw.setstrokedashoffset.php
	 * @param float $dash_offset <p>
	 * dash offset
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setStrokeDashOffset ($dash_offset) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Specifies the shape to be used at the end of open subpaths when they are stroked
	 * @link http://php.net/manual/en/imagickdraw.setstrokelinecap.php
	 * @param int $linecap <p>
	 * LINECAP_ constant
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setStrokeLineCap ($linecap) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Specifies the shape to be used at the corners of paths when they are stroked
	 * @link http://php.net/manual/en/imagickdraw.setstrokelinejoin.php
	 * @param int $linejoin <p>
	 * LINEJOIN_ constant
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setStrokeLineJoin ($linejoin) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Specifies the miter limit
	 * @link http://php.net/manual/en/imagickdraw.setstrokemiterlimit.php
	 * @param int $miterlimit <p>
	 * the miter limit
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setStrokeMiterLimit ($miterlimit) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Specifies the opacity of stroked object outlines
	 * @link http://php.net/manual/en/imagickdraw.setstrokeopacity.php
	 * @param float $stroke_opacity <p>
	 * stroke opacity. 1.0 is fully opaque
	 * </p>
	 * @return bool No value is returned.
	 */
	public function setStrokeOpacity ($stroke_opacity) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the vector graphics
	 * @link http://php.net/manual/en/imagickdraw.setvectorgraphics.php
	 * @param string $xml <p>
	 * xml containing the vector graphics
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function setVectorGraphics ($xml) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Destroys the current ImagickDraw in the stack, and returns to the previously pushed ImagickDraw
	 * @link http://php.net/manual/en/imagickdraw.pop.php
	 * @return bool <b>TRUE</b> on success and false on failure.
	 */
	public function pop () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Clones the current ImagickDraw and pushes it to the stack
	 * @link http://php.net/manual/en/imagickdraw.push.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function push () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Specifies the pattern of dashes and gaps used to stroke paths
	 * @link http://php.net/manual/en/imagickdraw.setstrokedasharray.php
	 * @param array $dashArray <p>
	 * array of floats
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setStrokeDashArray (array $dashArray) {}

}

/**
 * @link http://php.net/manual/en/class.imagickpixeliterator.php
 */
class ImagickPixelIterator implements Iterator, Traversable {

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * The ImagickPixelIterator constructor
	 * @link http://php.net/manual/en/imagickpixeliterator.construct.php
	 * @param Imagick $wand
	 */
	public function __construct (Imagick $wand) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns a new pixel iterator
	 * @link http://php.net/manual/en/imagickpixeliterator.newpixeliterator.php
	 * @param Imagick $wand
	 * @return bool <b>TRUE</b> on success. Throwing ImagickPixelIteratorException.
	 */
	public function newPixelIterator (Imagick $wand) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns a new pixel iterator
	 * @link http://php.net/manual/en/imagickpixeliterator.newpixelregioniterator.php
	 * @param Imagick $wand
	 * @param int $x
	 * @param int $y
	 * @param int $columns
	 * @param int $rows
	 * @return bool a new ImagickPixelIterator on success; on failure, throws
	 * ImagickPixelIteratorException.
	 */
	public function newPixelRegionIterator (Imagick $wand, $x, $y, $columns, $rows) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the current pixel iterator row
	 * @link http://php.net/manual/en/imagickpixeliterator.getiteratorrow.php
	 * @return int the integer offset of the row, throwing
	 * ImagickPixelIteratorException on error.
	 */
	public function getIteratorRow () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Set the pixel iterator row
	 * @link http://php.net/manual/en/imagickpixeliterator.setiteratorrow.php
	 * @param int $row
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setIteratorRow ($row) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the pixel iterator to the first pixel row
	 * @link http://php.net/manual/en/imagickpixeliterator.setiteratorfirstrow.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setIteratorFirstRow () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the pixel iterator to the last pixel row
	 * @link http://php.net/manual/en/imagickpixeliterator.setiteratorlastrow.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setIteratorLastRow () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the previous row
	 * @link http://php.net/manual/en/imagickpixeliterator.getpreviousiteratorrow.php
	 * @return array the previous row as an array of ImagickPixelWand objects from the
	 * ImagickPixelIterator, throwing ImagickPixelIteratorException on error.
	 */
	public function getPreviousIteratorRow () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the current row of ImagickPixel objects
	 * @link http://php.net/manual/en/imagickpixeliterator.getcurrentiteratorrow.php
	 * @return array a row as an array of ImagickPixel objects that can themselves be iterated.
	 */
	public function getCurrentIteratorRow () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the next row of the pixel iterator
	 * @link http://php.net/manual/en/imagickpixeliterator.getnextiteratorrow.php
	 * @return array the next row as an array of ImagickPixel objects, throwing
	 * ImagickPixelIteratorException on error.
	 */
	public function getNextIteratorRow () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Resets the pixel iterator
	 * @link http://php.net/manual/en/imagickpixeliterator.resetiterator.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function resetIterator () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Syncs the pixel iterator
	 * @link http://php.net/manual/en/imagickpixeliterator.synciterator.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function syncIterator () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Deallocates resources associated with a PixelIterator
	 * @link http://php.net/manual/en/imagickpixeliterator.destroy.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function destroy () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Clear resources associated with a PixelIterator
	 * @link http://php.net/manual/en/imagickpixeliterator.clear.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function clear () {}

	/**
	 * @param Imagick $Imagick
	 */
	public static function getpixeliterator (Imagick $Imagick) {}

	/**
	 * @param Imagick $Imagick
	 * @param $x
	 * @param $y
	 * @param $columns
	 * @param $rows
	 */
	public static function getpixelregioniterator (Imagick $Imagick, $x, $y, $columns, $rows) {}

	public function key () {}

	public function next () {}

	public function rewind () {}

	public function current () {}

	public function valid () {}

}

/**
 * @method clone()
 * @link http://php.net/manual/en/class.imagickpixel.php
 */
class ImagickPixel  {

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the normalized HSL color of the ImagickPixel object
	 * @link http://php.net/manual/en/imagickpixel.gethsl.php
	 * @return array the HSL value in an array with the keys "hue",
	 * "saturation", and "luminosity". Throws ImagickPixelException on failure.
	 */
	public function getHSL () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the normalized HSL color
	 * @link http://php.net/manual/en/imagickpixel.sethsl.php
	 * @param float $hue <p>
	 * The normalized value for hue, described as a fractional arc
	 * (between 0 and 1) of the hue circle, where the zero value is
	 * red.
	 * </p>
	 * @param float $saturation <p>
	 * The normalized value for saturation, with 1 as full saturation.
	 * </p>
	 * @param float $luminosity <p>
	 * The normalized value for luminosity, on a scale from black at
	 * 0 to white at 1, with the full HS value at 0.5 luminosity.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setHSL ($hue, $saturation, $luminosity) {}

	public function getColorValueQuantum () {}

	/**
	 * @param $color_value
	 */
	public function setColorValueQuantum ($color_value) {}

	public function getIndex () {}

	/**
	 * @param $index
	 */
	public function setIndex ($index) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * The ImagickPixel constructor
	 * @link http://php.net/manual/en/imagickpixel.construct.php
	 * @param string $color [optional] <p>
	 * The optional color string to use as the initial value of this object.
	 * </p>
	 */
	public function __construct ($color = null) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the color
	 * @link http://php.net/manual/en/imagickpixel.setcolor.php
	 * @param string $color <p>
	 * The color definition to use in order to initialise the
	 * ImagickPixel object.
	 * </p>
	 * @return bool <b>TRUE</b> if the specified color was set, <b>FALSE</b> otherwise.
	 */
	public function setColor ($color) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Sets the normalized value of one of the channels
	 * @link http://php.net/manual/en/imagickpixel.setcolorvalue.php
	 * @param int $color <p>
	 * One of the Imagick color constants e.g. \Imagick::COLOR_GREEN or \Imagick::COLOR_ALPHA.
	 * </p>
	 * @param float $value <p>
	 * The value to set this channel to, ranging from 0 to 1.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setColorValue ($color, $value) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Gets the normalized value of the provided color channel
	 * @link http://php.net/manual/en/imagickpixel.getcolorvalue.php
	 * @param int $color <p>
	 * The color to get the value of, specified as one of the Imagick color
	 * constants. This can be one of the RGB colors, CMYK colors, alpha and
	 * opacity e.g (Imagick::COLOR_BLUE, Imagick::COLOR_MAGENTA).
	 * </p>
	 * @return float The value of the channel, as a normalized floating-point number, throwing
	 * ImagickPixelException on error.
	 */
	public function getColorValue ($color) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Clears resources associated with this object
	 * @link http://php.net/manual/en/imagickpixel.clear.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function clear () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Deallocates resources associated with this object
	 * @link http://php.net/manual/en/imagickpixel.destroy.php
	 * @return bool <b>TRUE</b> on success.
	 */
	public function destroy () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Check the distance between this color and another
	 * @link http://php.net/manual/en/imagickpixel.issimilar.php
	 * @param ImagickPixel $color <p>
	 * The ImagickPixel object to compare this object against.
	 * </p>
	 * @param float $fuzz <p>
	 * The maximum distance within which to consider these colors as similar.
	 * The theoretical maximum for this value is the square root of three
	 * (1.732).
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function isSimilar (ImagickPixel $color, $fuzz) {}

	/**
	 * (No version information available, might only be in SVN)<br/>
	 * Check the distance between this color and another
	 * @link http://php.net/manual/en/imagickpixel.ispixelsimilar.php
	 * @param ImagickPixel $color <p>
	 * The ImagickPixel object to compare this object against.
	 * </p>
	 * @param float $fuzz <p>
	 * The maximum distance within which to consider these colors as similar.
	 * The theoretical maximum for this value is the square root of three
	 * (1.732).
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function isPixelSimilar (ImagickPixel $color, $fuzz) {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the color
	 * @link http://php.net/manual/en/imagickpixel.getcolor.php
	 * @param bool $normalized [optional] <p>
	 * Normalize the color values
	 * </p>
	 * @return array An array of channel values, each normalized if <b>TRUE</b> is given as param. Throws
	 * ImagickPixelException on error.
	 */
	public function getColor ($normalized = false) {}

	/**
	 * (PECL imagick 2.1.0)<br/>
	 * Returns the color as a string
	 * @link http://php.net/manual/en/imagickpixel.getcolorasstring.php
	 * @return string the color of the ImagickPixel object as a string.
	 */
	public function getColorAsString () {}

	/**
	 * (PECL imagick 2.0.0)<br/>
	 * Returns the color count associated with this color
	 * @link http://php.net/manual/en/imagickpixel.getcolorcount.php
	 * @return int the color count as an integer on success, throws
	 * ImagickPixelException on failure.
	 */
	public function getColorCount () {}

	/**
	 * @param $colorCount
	 */
	public function setColorCount ($colorCount) {}

}
// End of imagick v.3.2.0RC1

