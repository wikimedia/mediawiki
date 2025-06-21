<?php

namespace MediaWiki\Tests\Api;

/**
 * RandomImageGenerator -- does what it says on the tin.
 * Requires Imagick, the ImageMagick library for PHP, or the command line
 * equivalent (usually 'convert').
 *
 * @file
 * @author Neil Kandalgaonkar <neilk@wikimedia.org>
 */

use Exception;
use Imagick;
use ImagickPixel;
use MediaWiki\Shell\Shell;
use SimpleXMLElement;
use UnexpectedValueException;

/**
 * RandomImageGenerator: does what it says on the tin.
 * Can fetch a random image, or also write a number of them to disk with random filenames.
 */
class RandomImageGenerator {
	/** @var int */
	private $minWidth = 16;
	/** @var int */
	private $maxWidth = 16;
	/** @var int */
	private $minHeight = 16;
	/** @var int */
	private $maxHeight = 16;

	public function __construct( array $options = [] ) {
		foreach ( [ 'minWidth', 'minHeight',
			'maxWidth', 'maxHeight' ] as $property
		) {
			if ( isset( $options[$property] ) ) {
				$this->$property = $options[$property];
			}
		}
	}

	/**
	 * Writes random images with random filenames to disk in the directory you
	 * specify, or current working directory.
	 *
	 * @param int $number Number of filenames to write
	 * @param string $format Optional, must be understood by ImageMagick, such as 'jpg' or 'gif'
	 * @param string|null $dir Directory, optional (will default to current working directory)
	 * @return string[] Filenames we just wrote
	 */
	public function writeImages( int $number, string $format = 'svg', ?string $dir = null ): array {
		$filenames = $this->getRandomFilenames( $number, $format, $dir ?? getcwd() );
		$imageWriteMethod = $this->getImageWriteMethod( $format );
		foreach ( $filenames as $filename ) {
			$imageWriteMethod( $this->getImageSpec(), $format, $filename );
		}

		return $filenames;
	}

	/**
	 * Figure out how we write images. This is a factor of both format and the local system
	 *
	 * @param string $format (a typical extension like 'svg', 'jpg', etc.)
	 *
	 * @throws Exception
	 */
	private function getImageWriteMethod( string $format ): callable {
		global $wgUseImageMagick, $wgImageMagickConvertCommand;
		if ( $format === 'svg' ) {
			return $this->writeSvg( ... );
		} else {
			// figure out how to write images
			global $wgExiv2Command;
			if ( class_exists( Imagick::class ) && $wgExiv2Command && is_executable( $wgExiv2Command ) ) {
				return $this->writeImageWithApi( ... );
			} elseif ( $wgUseImageMagick
				&& $wgImageMagickConvertCommand
				&& is_executable( $wgImageMagickConvertCommand )
			) {
				return $this->writeImageWithCommandLine( ... );
			}
		}
		throw new Exception( "RandomImageGenerator: could not find a suitable "
			. "method to write images in '$format' format" );
	}

	/**
	 * Return a number of randomly-generated filenames.
	 *
	 * Each filename uses follows the pattern "hex_timestamp_1.jpg".
	 *
	 * @return string[]
	 */
	private function getRandomFilenames( int $number, string $extension, string $dir ): array {
		$filenames = [];
		$prefix = wfRandomString( 3 ) . '_' . gmdate( 'YmdHis' ) . '_';
		foreach ( range( 1, $number ) as $offset ) {
			$filename = $prefix . $offset . '.' . $extension;
			$filenames[] = "$dir/$filename";
		}

		return $filenames;
	}

	/**
	 * Generate data representing an image of random size (within limits),
	 * consisting of randomly colored and sized upward pointing triangles
	 * against a random background color. (This data is used in the
	 * writeImage* methods).
	 */
	private function getImageSpec(): array {
		return [
			'width' => mt_rand( $this->minWidth, $this->maxWidth ),
			'height' => mt_rand( $this->minHeight, $this->maxHeight ),
			'fill' => '#f0f',
		];
	}

	/**
	 * Based on image specification, write a very simple SVG file to disk.
	 * Ignores the background spec because transparency is cool. :)
	 *
	 * @throws Exception
	 */
	private function writeSvg( array $spec, string $format, string $filename ): void {
		$svg = new SimpleXmlElement( '<svg/>' );
		$svg->addAttribute( 'xmlns', 'http://www.w3.org/2000/svg' );
		$svg->addAttribute( 'width', $spec['width'] );
		$svg->addAttribute( 'height', $spec['height'] );

		$fh = fopen( $filename, 'w' );
		if ( !$fh ) {
			throw new UnexpectedValueException( "couldn't open $filename for writing" );
		}
		fwrite( $fh, $svg->asXML() );
		if ( !fclose( $fh ) ) {
			throw new UnexpectedValueException( "couldn't close $filename" );
		}
	}

	/**
	 * Based on an image specification, write such an image to disk, using Imagick PHP extension
	 */
	private function writeImageWithApi( array $spec, string $format, string $filename ): void {
		$image = new Imagick();
		$image->newImage( $spec['width'], $spec['height'], new ImagickPixel( $spec['fill'] ) );
		$image->setImageFormat( $format );
		$image->writeImage( $filename );
	}

	/**
	 * Based on an image specification, write such an image to disk, using the
	 * command line ImageMagick program ('convert').
	 *
	 * Sample command line:
	 *  $ convert -size 100x60 xc:rgb(90,87,45) \
	 *      -draw 'fill rgb(12,34,56)   polygon 41,39 44,57 50,57 41,39' \
	 *   -draw 'fill rgb(99,123,231) circle 59,39 56,57' \
	 *   -draw 'fill rgb(240,12,32)  circle 50,21 50,3'  filename.png
	 */
	private function writeImageWithCommandLine( array $spec, string $format, string $filename ): void {
		global $wgImageMagickConvertCommand;

		$args = [
			$wgImageMagickConvertCommand,
			'-size',
			$spec['width'] . 'x' . $spec['height'],
			"xc:{$spec['fill']}",
			$filename,
		];
		Shell::command( $args )->execute();
	}

}

/** @deprecated class alias since 1.42 */
class_alias( RandomImageGenerator::class, 'RandomImageGenerator' );
