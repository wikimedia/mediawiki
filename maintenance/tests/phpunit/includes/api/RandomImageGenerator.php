<?php

/* 
 * RandomImageGenerator -- does what it says on the tin.
 * Requires Imagick, the ImageMagick library for PHP, or the command line equivalent (usually 'convert').
 *
 * Because MediaWiki tests the uniqueness of media upload content, and filenames, it is sometimes useful to generate
 * files that are guaranteed (or at least very likely) to be unique in both those ways.
 * This generates a number of filenames with random names and random content (colored circles) 
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
	private $minWidth = 400;
	private $maxWidth = 800;
	private $minHeight = 400;
	private $maxHeight = 800;
	private $circlesToDraw = 5;
	private $imageWriteMethod;
	
	public function __construct( $options ) {
		global $wgUseImageMagick, $wgImageMagickConvertCommand;
		foreach ( array( 'dictionaryFile', 'minWidth', 'minHeight', 'maxHeight', 'circlesToDraw' ) as $property ) {
			if ( isset( $options[$property] ) ) {
				$this->$property = $options[$property];
			}
		}

		// find the dictionary file, to generate random names
		if ( !isset( $this->dictionaryFile ) ) {
			foreach ( array( '/usr/share/dict/words', '/usr/dict/words' ) as $dictionaryFile ) {
				if ( is_file( $dictionaryFile ) and is_readable( $dictionaryFile ) ) {
					$this->dictionaryFile = $dictionaryFile;
					break;
				}
			}
		}
		if ( !isset( $this->dictionaryFile ) ) {
			throw new Exception( "RandomImageGenerator: dictionary file not found or not specified properly" );
		}

		// figure out how to write images
		if ( class_exists( 'Imagick' ) ) {
			$this->imageWriteMethod = 'writeImageWithApi';
		} elseif ( $wgUseImageMagick && $wgImageMagickConvertCommand && is_executable( $wgImageMagickConvertCommand ) ) {
			$this->imageWriteMethod = 'writeImageWithCommandLine';
		} else {
			throw new Exception( "RandomImageGenerator: could not find a suitable method to write images" );
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
		foreach( $filenames as $filename ) {
			$this->{$this->imageWriteMethod}( $this->getImageSpec(), $format, $filename );
		}
		return $filenames;
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
	 * consisting of randomly colored and sized circles against a random background color
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
		for ( $i = 0; $i <= $this->circlesToDraw; $i++ ) {
			$radius = mt_rand( 0, $diagonalLength / 4 );
			$originX = mt_rand( -1 * $radius, $spec['width'] + $radius );
			$originY = mt_rand( -1 * $radius, $spec['height'] + $radius );
			$perimeterX = $originX + $radius;
			$perimeterY = $originY + $radius;

			$draw = array();
			$draw['fill'] = $this->getRandomColor();
			$draw['circle'] = array( 
				'originX' => $originX, 
				'originY' => $originY, 
				'perimeterX' => $perimeterX, 
				'perimeterY' => $perimeterY 
			);
			$draws[] = $draw;
			
		}

		$spec['draws'] = $draws;

		return $spec;
	}


	/**
	 * Based on an image specification, write such an image to disk, using Imagick PHP extension
	 * @param $spec: spec describing background and circles to draw
	 * @param $format: file format to write
	 * @param $filename: filename to write to
	 */
	public function writeImageWithApi( $spec, $format, $filename ) { 
		$image = new Imagick();
		$image->newImage( $spec['width'], $spec['height'], new ImagickPixel( $spec['fill'] ) );

		foreach ( $spec['draws'] as $drawSpec ) {
			$draw = new ImagickDraw(); 
			$draw->setFillColor( $drawSpec['fill'] );
			$circle = $drawSpec['circle'];
			$draw->circle( $circle['originX'], $circle['originY'], $circle['perimeterX'], $circle['perimeterY'] );
			$image->drawImage( $draw );
		}

		$image->setImageFormat( $format );
		$image->writeImage( $filename );
	}


	/**
	 * Based on an image specification, write such an image to disk, using the command line ImageMagick program ('convert').
	 *
	 * Sample command line:
	 *    $ convert -size 100x60 xc:rgb(90,87,45)  \
         * 	-draw 'fill rgb(12,34,56)   circle 41,39 44,57' \
         *      -draw 'fill rgb(99,123,231) circle 59,39 56,57' \
         *      -draw 'fill rgb(240,12,32)  circle 50,21 50,3'  filename.png
	 *
	 * @param $spec: spec describing background and circles to draw
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
			$originX = $draw['circle']['originX'];
			$originY = $draw['circle']['originY'];
			$perimeterX = $draw['circle']['perimeterX'];
			$perimeterY = $draw['circle']['perimeterY'];
			$drawCommand = "fill $fill  circle $originX,$originY $perimeterX,$perimeterY";
			$args[] = '-draw ' . wfEscapeShellArg( $drawCommand );
		}
		$args[] = $filename;

		$command = wfEscapeShellArg( $wgImageMagickConvertCommand ) . " " . implode( " ", $args );
		$retval = null;
		wfShellExec( $command, $retval );
		return ( $retval === 0 );
	}

	/**
	 * Generate a string of random colors for ImageMagick, like "rgb(12, 37, 98)"
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
