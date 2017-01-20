<?php

/**
 * Minimal set of classes necessary for Bitmap & SVG to be happy.
 * Types taken from documentation at https://secure.php.net/manual/en/book.imagick.php
 * @codingStandardsIgnoreFile
 */

class Imagick {

	const INTERLACE_GIF = 5;
	const INTERLACE_JPEG = 6;
	const INTERLACE_PNG = 7;

	const CHANNEL_ALL = 134217727;

	/**
	 * @param string $filename
	 * @return bool <b>TRUE</b> on success.
	 */
	public function readImage ($filename) {}

	/**
	 * @param float $radius
	 * @param float $sigma
	 * @param int $channel [optional]
	 * @return bool <b>TRUE</b> on success.
	 */
	public function sharpenImage ($radius, $sigma, $channel = Imagick::CHANNEL_ALL) {}

	/**
	 * @param int $quality
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setCompressionQuality ($quality) {}

	/**
	 * @return array the ImageMagick API version as a string and as a number.
	 */
	public static function getVersion () {}

	/**
	 * @return Imagick a new Imagick object on success.
	 */
	public function coalesceImages () {}

	/**
	 * @param mixed $background
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageBackgroundColor ($background) {}

	/**
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
	 * @param string $filename
	 * @param bool $adjoin
	 * @return bool <b>TRUE</b> on success.
	 */
	public function writeImages ($filename, $adjoin) {}

	/**
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
	 * @param string $format <p>
	 * String presentation of the image format. Format support
	 * depends on the ImageMagick installation.
	 * </p>
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setImageFormat ($format) {}

	/**
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
	 * @param int $interlace_scheme
	 * @return bool <b>TRUE</b> on success.
	 */
	public function setInterlaceScheme ($interlace_scheme) {}

}

class ImagickPixel {}

class ImagickException extends Exception {}
