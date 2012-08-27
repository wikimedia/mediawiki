<?php

/**
 * RandomImageGenerator -- does what it says on the tin.
 * Requires Imagick, the ImageMagick library for PHP, or the command line equivalent (usually 'convert').
 *
 * Because MediaWiki tests the uniqueness of media upload content, and filenames, it is sometimes useful to generate
 * files that are guaranteed (or at least very likely) to be unique in both those ways.
 * This generates a number of filenames with random names and random content (colored triangles)
 *
 * It is also useful to have fresh content because our tests currently run in a "destructive" mode, and don't create a fresh new wiki for each
 * test run.
 * Consequently, if we just had a few static files we kept re-uploading, we'd get lots of warnings about matching content or filenames,
 * and even if we deleted those files, we'd get warnings about archived files.
 *
 * This can also be used with a cronjob to generate random files all the time -- I use it to have a constant, never ending supply when I'm
 * testing interactively.
 *
 * @file
 * @author Neil Kandalgaonkar <neilk@wikimedia.org>
 */

/**
 * RandomImageGenerator: does what it says on the tin.
 * Can fetch a random image, or also write a number of them to disk with random filenames.
 */
class RandomImageGenerator {

	private $dictionaryFile;
	private $minWidth     = 400 ;
	private $maxWidth     = 800 ;
	private $minHeight    = 400 ;
	private $maxHeight    = 800 ;
	private $shapesToDraw =   5 ;

	/**
	 * Orientations: 0th row, 0th column, EXIF orientation code, rotation 2x2 matrix that is opposite of orientation
	 * n.b. we do not handle the 'flipped' orientations, which is why there is no entry for 2, 4, 5, or 7. Those
	 * seem to be rare in real images anyway
	 * (we also would need a non-symmetric shape for the images to test those, like a letter F)
	 */
	private static $orientations = array(
		array(
			'0thRow' => 'top',
			'0thCol' => 'left',
			'exifCode' => 1,
			'counterRotation' => array( array( 1, 0 ), array( 0, 1 ) )
		),
		array(
			'0thRow' => 'bottom',
			'0thCol' => 'right',
			'exifCode' => 3,
			'counterRotation' => array( array( -1, 0 ), array( 0, -1 ) )
		),
		array(
			'0thRow' => 'right',
			'0thCol' => 'top',
			'exifCode' => 6,
			'counterRotation' => array( array( 0, 1 ), array( 1, 0 ) )
		),
		array(
			'0thRow' => 'left',
			'0thCol' => 'bottom',
			'exifCode' => 8,
			'counterRotation' => array( array( 0, -1 ), array( -1, 0 ) )
		)
	);


	public function __construct( $options = array() ) {
		foreach ( array( 'dictionaryFile', 'minWidth', 'minHeight', 'maxWidth', 'maxHeight', 'shapesToDraw' ) as $property ) {
			if ( isset( $options[$property] ) ) {
				$this->$property = $options[$property];
			}
		}

		// find the dictionary file, to generate random names
		if ( !isset( $this->dictionaryFile ) ) {
			foreach ( array(
					'/usr/share/dict/words',
					'/usr/dict/words',
					__DIR__ . '/words.txt' )
					as $dictionaryFile ) {
				if ( is_file( $dictionaryFile ) and is_readable( $dictionaryFile ) ) {
					$this->dictionaryFile = $dictionaryFile;
					break;
				}
			}
		}
		if ( !isset( $this->dictionaryFile ) ) {
			throw new Exception( "RandomImageGenerator: dictionary file not found or not specified properly" );
		}
	}

	/**
	 * Writes random images with random filenames to disk in the directory you specify, or current working directory
	 *
	 * @param $number Integer: number of filenames to write
	 * @param $format String: optional, must be understood by ImageMagick, such as 'jpg' or 'gif'
	 * @param $dir String: directory, optional (will default to current working directory)
	 * @return Array: filenames we just wrote
	 */
	function writeImages( $number, $format = 'jpg', $dir = null ) {
		$filenames = $this->getRandomFilenames( $number, $format, $dir );
		$imageWriteMethod = $this->getImageWriteMethod( $format );
		foreach( $filenames as $filename ) {
			$this->{$imageWriteMethod}( $this->getImageSpec(), $format, $filename );
		}
		return $filenames;
	}


	/**
	 * Figure out how we write images. This is a factor of both format and the local system
	 * @param $format (a typical extension like 'svg', 'jpg', etc.)
	 */
	function getImageWriteMethod( $format ) {
		global $wgUseImageMagick, $wgImageMagickConvertCommand;
		if ( $format === 'svg' ) {
			return 'writeSvg';
		} else {
			// figure out how to write images
			global $wgExiv2Command;
			if ( class_exists( 'Imagick' ) && $wgExiv2Command && is_executable( $wgExiv2Command ) ) {
				return 'writeImageWithApi';
			} elseif ( $wgUseImageMagick && $wgImageMagickConvertCommand && is_executable( $wgImageMagickConvertCommand ) ) {
				return 'writeImageWithCommandLine';
			}
		}
		throw new Exception( "RandomImageGenerator: could not find a suitable method to write images in '$format' format" );
	}

	/**
	 * Return a number of randomly-generated filenames
	 * Each filename uses two words randomly drawn from the dictionary, like elephantine_spatula.jpg
	 *
	 * @param $number Integer: of filenames to generate
	 * @param $extension String: optional, defaults to 'jpg'
	 * @param $dir String: optional, defaults to current working directory
	 * @return Array: of filenames
	 */
	private function getRandomFilenames( $number, $extension = 'jpg', $dir = null ) {
		if ( is_null( $dir ) ) {
			$dir = getcwd();
		}
		$filenames = array();
		foreach( $this->getRandomWordPairs( $number ) as $pair ) {
			$basename = $pair[0] . '_' . $pair[1];
			if ( !is_null( $extension ) ) {
				$basename .= '.' . $extension;
			}
			$basename = preg_replace( '/\s+/', '', $basename );
			$filenames[] = "$dir/$basename";
		}

		return $filenames;

	}


	/**
	 * Generate data representing an image of random size (within limits),
	 * consisting of randomly colored and sized upward pointing triangles against a random background color
	 * (This data is used in the writeImage* methods).
	 * @return {Mixed}
	 */
	public function getImageSpec() {
		$spec = array();

		$spec['width'] = mt_rand( $this->minWidth, $this->maxWidth );
		$spec['height'] = mt_rand( $this->minHeight, $this->maxHeight );
		$spec['fill'] = $this->getRandomColor();

		$diagonalLength = sqrt( pow( $spec['width'], 2 ) + pow( $spec['height'], 2 ) );

		$draws = array();
		for ( $i = 0; $i <= $this->shapesToDraw; $i++ ) {
			$radius = mt_rand( 0, $diagonalLength / 4 );
			if ( $radius == 0 ) {
				continue;
			}
			$originX = mt_rand( -1 * $radius, $spec['width'] + $radius );
			$originY = mt_rand( -1 * $radius, $spec['height'] + $radius );
			$angle = mt_rand( 0, ( 3.141592/2 ) * $radius ) / $radius;
			$legDeltaX = round( $radius * sin( $angle ) );
			$legDeltaY = round( $radius * cos( $angle ) );

			$draw = array();
			$draw['fill'] = $this->getRandomColor();
			$draw['shape'] = array(
				array( 'x' => $originX, 		'y' => $originY - $radius ),
				array( 'x' => $originX + $legDeltaX, 	'y' => $originY + $legDeltaY ),
				array( 'x' => $originX - $legDeltaX, 	'y' => $originY + $legDeltaY ),
				array( 'x' => $originX, 		'y' => $originY - $radius )
			);
			$draws[] = $draw;

		}

		$spec['draws'] = $draws;

		return $spec;
	}

	/**
	 * Given array( array('x' => 10, 'y' => 20), array( 'x' => 30, y=> 5 ) )
	 * returns "10,20 30,5"
	 * Useful for SVG and imagemagick command line arguments
	 * @param $shape: Array of arrays, each array containing x & y keys mapped to numeric values
	 * @return string
	 */
	static function shapePointsToString( $shape ) {
		$points = array();
		foreach ( $shape as $point ) {
			$points[] = $point['x'] . ',' . $point['y'];
		}
		return join( " ", $points );
	}

	/**
	 * Based on image specification, write a very simple SVG file to disk.
	 * Ignores the background spec because transparency is cool. :)
	 * @param $spec: spec describing background and shapes to draw
	 * @param $format: file format to write (which is obviously always svg here)
	 * @param $filename: filename to write to
	 */
	public function writeSvg( $spec, $format, $filename ) {
		$svg = new SimpleXmlElement( '<svg/>' );
		$svg->addAttribute( 'xmlns', 'http://www.w3.org/2000/svg' );
		$svg->addAttribute( 'version', '1.1' );
		$svg->addAttribute( 'width', $spec['width'] );
		$svg->addAttribute( 'height', $spec['height'] );
		$g = $svg->addChild( 'g' );
		foreach ( $spec['draws'] as $drawSpec ) {
			$shape = $g->addChild( 'polygon' );
			$shape->addAttribute( 'fill', $drawSpec['fill'] );
			$shape->addAttribute( 'points', self::shapePointsToString( $drawSpec['shape'] ) );
		};
		if ( ! $fh = fopen( $filename, 'w' ) ) {
			throw new Exception( "couldn't open $filename for writing" );
		}
		fwrite( $fh, $svg->asXML() );
		if ( !fclose($fh) ) {
			throw new Exception( "couldn't close $filename" );
		}
	}

	/**
	 * Based on an image specification, write such an image to disk, using Imagick PHP extension
	 * @param $spec: spec describing background and circles to draw
	 * @param $format: file format to write
	 * @param $filename: filename to write to
	 */
	public function writeImageWithApi( $spec, $format, $filename ) {
		// this is a hack because I can't get setImageOrientation() to work. See below.
		global $wgExiv2Command;

		$image = new Imagick();
		/**
		 * If the format is 'jpg', will also add a random orientation -- the image will be drawn rotated with triangle points
		 * facing in some direction (0, 90, 180 or 270 degrees) and a countering rotation should turn the triangle points upward again
		 */
		$orientation = self::$orientations[0]; // default is normal orientation
		if ( $format == 'jpg' ) {
			$orientation = self::$orientations[ array_rand( self::$orientations ) ];
			$spec = self::rotateImageSpec( $spec, $orientation['counterRotation'] );
		}

		$image->newImage( $spec['width'], $spec['height'], new ImagickPixel( $spec['fill'] ) );

		foreach ( $spec['draws'] as $drawSpec ) {
			$draw = new ImagickDraw();
			$draw->setFillColor( $drawSpec['fill'] );
			$draw->polygon( $drawSpec['shape'] );
			$image->drawImage( $draw );
		}

		$image->setImageFormat( $format );

		// this doesn't work, even though it's documented to do so...
		// $image->setImageOrientation( $orientation['exifCode'] );

		$image->writeImage( $filename );

		// because the above setImageOrientation call doesn't work... nor can I get an external imagemagick binary to do this either...
		// hacking this for now (only works if you have exiv2 installed, a program to read and manipulate exif)
		if ( $wgExiv2Command ) {
			$cmd = wfEscapeShellArg( $wgExiv2Command )
				. " -M "
				. wfEscapeShellArg( "set Exif.Image.Orientation " . $orientation['exifCode'] )
				. " "
				. wfEscapeShellArg( $filename );

			$retval = 0;
			$err = wfShellExec( $cmd, $retval );
			if ( $retval !== 0 ) {
				print "Error with $cmd: $retval, $err\n";
			}
		}
	}

	/**
	 * Given an image specification, produce rotated version
	 * This is used when simulating a rotated image capture with EXIF orientation
	 * @param $spec Object returned by getImageSpec
	 * @param $matrix 2x2 transformation matrix
	 * @return transformed Spec
	 */
	private static function rotateImageSpec( &$spec, $matrix ) {
		$tSpec = array();
		$dims = self::matrixMultiply2x2( $matrix, $spec['width'], $spec['height'] );
		$correctionX = 0;
		$correctionY = 0;
		if ( $dims['x'] < 0 ) {
			$correctionX = abs( $dims['x'] );
		}
		if ( $dims['y'] < 0 ) {
			$correctionY = abs( $dims['y'] );
		}
		$tSpec['width'] = abs( $dims['x'] );
		$tSpec['height'] = abs( $dims['y'] );
		$tSpec['fill'] = $spec['fill'];
		$tSpec['draws'] = array();
		foreach( $spec['draws'] as $draw ) {
			$tDraw = array(
				'fill' => $draw['fill'],
				'shape' => array()
			);
			foreach( $draw['shape'] as $point ) {
				$tPoint = self::matrixMultiply2x2( $matrix, $point['x'], $point['y'] );
				$tPoint['x'] += $correctionX;
				$tPoint['y'] += $correctionY;
				$tDraw['shape'][] = $tPoint;
			}
			$tSpec['draws'][] = $tDraw;
		}
		return $tSpec;
	}

	/**
	 * Given a matrix and a pair of images, return new position
	 * @param $matrix: 2x2 rotation matrix
	 * @param $x: x-coordinate number
	 * @param $y: y-coordinate number
	 * @return Array transformed with properties x, y
	 */
	private static function matrixMultiply2x2( $matrix, $x, $y ) {
		return array(
			'x' => $x * $matrix[0][0] + $y * $matrix[0][1],
			'y' => $x * $matrix[1][0] + $y * $matrix[1][1]
		);
	}


	/**
	 * Based on an image specification, write such an image to disk, using the command line ImageMagick program ('convert').
	 *
	 * Sample command line:
	 *  $ convert -size 100x60 xc:rgb(90,87,45) \
	 * 	 -draw 'fill rgb(12,34,56)   polygon 41,39 44,57 50,57 41,39' \
	 *   -draw 'fill rgb(99,123,231) circle 59,39 56,57' \
	 *   -draw 'fill rgb(240,12,32)  circle 50,21 50,3'  filename.png
	 *
	 * @param $spec: spec describing background and shapes to draw
	 * @param $format: file format to write (unused by this method but kept so it has the same signature as writeImageWithApi)
	 * @param $filename: filename to write to
	 */
	public function writeImageWithCommandLine( $spec, $format, $filename ) {
		global $wgImageMagickConvertCommand;
		$args = array();
		$args[] = "-size " . wfEscapeShellArg( $spec['width'] . 'x' . $spec['height'] );
		$args[] = wfEscapeShellArg( "xc:" . $spec['fill'] );
		foreach( $spec['draws'] as $draw ) {
			$fill = $draw['fill'];
			$polygon = self::shapePointsToString( $draw['shape'] );
			$drawCommand = "fill $fill  polygon $polygon";
			$args[] = '-draw ' . wfEscapeShellArg( $drawCommand );
		}
		$args[] = wfEscapeShellArg( $filename );

		$command = wfEscapeShellArg( $wgImageMagickConvertCommand ) . " " . implode( " ", $args );
		$retval = null;
		wfShellExec( $command, $retval );
		return ( $retval === 0 );
	}

	/**
	 * Generate a string of random colors for ImageMagick or SVG, like "rgb(12, 37, 98)"
	 *
	 * @return {String}
	 */
	public function getRandomColor() {
		$components = array();
		for ($i = 0; $i <= 2; $i++ ) {
			$components[] = mt_rand( 0, 255 );
		}
		return 'rgb(' . join(', ', $components) . ')';
	}

	/**
	 * Get an array of random pairs of random words, like array( array( 'foo', 'bar' ), array( 'quux', 'baz' ) );
	 *
	 * @param $number Integer: number of pairs
	 * @return Array: of two-element arrays
	 */
	private function getRandomWordPairs( $number ) {
		$lines = $this->getRandomLines( $number * 2 );
		// construct pairs of words
		$pairs = array();
		$count = count( $lines );
		for( $i = 0; $i < $count; $i += 2 )  {
			$pairs[] = array( $lines[$i], $lines[$i+1] );
		}
		return $pairs;
	}


	/**
	 * Return N random lines from a file
	 *
	 * Will throw exception if the file could not be read or if it had fewer lines than requested.
	 *
	 * @param $number_desired Integer: number of lines desired
	 * @return Array: of exactly n elements, drawn randomly from lines the file
	 */
	private function getRandomLines( $number_desired ) {
		$filepath = $this->dictionaryFile;

		// initialize array of lines
		$lines = array();
		for ( $i = 0; $i < $number_desired; $i++ ) {
			$lines[] = null;
		}

		/*
		 * This algorithm obtains N random lines from a file in one single pass. It does this by replacing elements of
		 * a fixed-size array of lines, less and less frequently as it reads the file.
		 */
		$fh = fopen( $filepath, "r" );
		if ( !$fh ) {
			 throw new Exception( "couldn't open $filepath" );
		}
		$line_number = 0;
		$max_index = $number_desired - 1;
		while( !feof( $fh ) ) {
			$line = fgets( $fh );
			if ( $line !== false ) {
				$line_number++;
				$line = trim( $line );
				if ( mt_rand( 0, $line_number ) <= $max_index ) {
					$lines[ mt_rand( 0, $max_index ) ] = $line;
				}
			}
		}
		fclose( $fh );
		if ( $line_number < $number_desired ) {
			throw new Exception( "not enough lines in $filepath" );
		}

		return $lines;
	}

}
