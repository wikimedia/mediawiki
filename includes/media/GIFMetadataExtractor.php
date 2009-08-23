<?php
/**
  * GIF frame counter.
  * Originally written in Perl by Steve Sanbeg.
  * Ported to PHP by Andrew Garrett
  * Deliberately not using MWExceptions to avoid external dependencies, encouraging
  * redistribution.
  */

class GIFMetadataExtractor {
	static $gif_frame_sep;
	static $gif_extension_sep;
	static $gif_term;

	static function getMetadata( $filename ) {
		self::$gif_frame_sep = pack( "C", ord("," ) );
		self::$gif_extension_sep = pack( "C", ord("!" ) );
		self::$gif_term = pack( "C", ord(";" ) );
		
		$frameCount = 0;
		$duration = 0.0;
		$isLooped = false;
		
		if (!$filename)
			throw new Exception( "No file name specified" );
		elseif ( !file_exists($filename) || is_dir($filename) )
			throw new Exception( "File $filename does not exist" );
		
		$fh = fopen( $filename, 'r' );
		
		if (!$fh)
			throw new Exception( "Unable to open file $filename" );
		
		// Check for the GIF header
		$buf = fread( $fh, 6 );
		if ( !($buf == 'GIF87a' || $buf == 'GIF89a') ) {
			throw new Exception( "Not a valid GIF file; header: $buf" );
		}
		
		// Skip over width and height.
		fread( $fh, 4 );
		
		// Read BPP
		$buf = fread( $fh, 1 );
		$bpp = self::decodeBPP( $buf );
		
		// Skip over background and aspect ratio
		fread( $fh, 2 );
		
		// Skip over the GCT
		self::readGCT( $fh, $bpp );
		
		while( !feof( $fh ) ) {
			$buf = fread( $fh, 1 );
			
			if ($buf == self::$gif_frame_sep) {
				// Found a frame
				$frameCount++;
				
				## Skip bounding box
				fread( $fh, 8 );
				
				## Read BPP
				$buf = fread( $fh, 1 );
				$bpp = self::decodeBPP( $buf );
				
				## Read GCT
				self::readGCT( $fh, $bpp );
				fread( $fh, 1 );
				self::skipBlock( $fh );	
			} elseif ( $buf == self::$gif_extension_sep ) {
				$buf = fread( $fh, 1 );
				$extension_code = unpack( 'C', $buf );
				$extension_code = $extension_code[1];
				
				if ($extension_code == 0xF9) {
					// Graphics Control Extension.
					fread( $fh, 1 ); // Block size
					
					fread( $fh, 1 ); // Transparency, disposal method, user input
					
					$buf = fread( $fh, 2 ); // Delay, in hundredths of seconds.
					$delay = unpack( 'v', $buf );
					$delay = $delay[1];
					$duration += $delay * 0.01;
					
					fread( $fh, 1 ); // Transparent colour index
					
					$term = fread( $fh, 1 ); // Should be a terminator
					$term = unpack( 'C', $term );
					$term = $term[1];
					if ($term != 0 )
						throw new Exception( "Malformed Graphics Control Extension block" );
				} elseif ($extension_code == 0xFF) {
					// Application extension (Netscape info about the animated gif)
					$blockLength = fread( $fh, 1 );
					$blockLength = unpack( 'C', $blockLength );
					$blockLength = $blockLength[1];
					$data = fread( $fh, $blockLength );
					
					// NETSCAPE2.0 (application name)
					if ($blockLength != 11 || $data != 'NETSCAPE2.0') {
						fseek( $fh, -($blockLength + 1), SEEK_CUR );
						self::skipBlock( $fh );
						continue;
					}
					
					$data = fread( $fh, 2 ); // Block length and introduction, should be 03 01
					
					if ($data != "\x03\x01") {
						throw new Exception( "Expected \x03\x01, got $data" );
					}
					
					// Unsigned little-endian integer, loop count or zero for "forever"
					$loopData = fread( $fh, 2 );
					$loopData = unpack( 'v', $loopData );
					$loopCount = $loopData[1];
					
					if ($loopCount != 1) {
						$isLooped = true;
					}
					
					// Read out terminator byte
					fread( $fh, 1 );
				} else {
					self::skipBlock( $fh );
				}
			} elseif ( $buf == self::$gif_term ) {
				break;
			} else {
				$byte = unpack( 'C', $buf );
				$byte = $byte[1];
				throw new Exception( "At position: ".ftell($fh). ", Unknown byte ".$byte );
			}
		}
		
		return array(
			'frameCount' => $frameCount,
			'looped' => $isLooped,
			'duration' => $duration
		);
		
	}
	
	static function readGCT( $fh, $bpp ) {
		if ($bpp > 0) {
			for( $i=1; $i<=pow(2,$bpp); ++$i ) {
				fread( $fh, 3 );
			}
		}
	}
	
	static function decodeBPP( $data ) {
		$buf = unpack( 'C', $data );
		$buf = $buf[1];
		$bpp = ( $buf & 7 ) + 1;
		$buf >>= 7;
		
		$have_map = $buf & 1;
		
		return $have_map ? $bpp : 0;
	}
	
	static function skipBlock( $fh ) {
		while ( !feof( $fh ) ) {
			$buf = fread( $fh, 1 );
			$block_len = unpack( 'C', $buf );
			$block_len = $block_len[1];
			if ($block_len == 0)
				return;
			fread( $fh, $block_len );
		}
	}

}
