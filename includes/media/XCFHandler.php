<?php
/**
 * Handler for the Gimp's native file format (XCF)
 *
 * Overview:
 *   https://en.wikipedia.org/wiki/XCF_(file_format)
 * Specification in Gnome repository:
 *   http://svn.gnome.org/viewvc/gimp/trunk/devel-docs/xcf.txt?view=markup
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Media
 */

/**
 * Handler for the Gimp's native file format; getimagesize() doesn't
 * support these files
 *
 * @ingroup Media
 */
class XCFHandler extends BitmapHandler {
	/**
	 * @param File $file
	 * @return bool
	 */
	public function mustRender( $file ) {
		return true;
	}

	/**
	 * Render files as PNG
	 *
	 * @param string $ext
	 * @param string $mime
	 * @param array|null $params
	 * @return array
	 */
	public function getThumbType( $ext, $mime, $params = null ) {
		return [ 'png', 'image/png' ];
	}

	/**
	 * Get width and height from the XCF header.
	 *
	 * @param File|FSFile $image
	 * @param string $filename
	 * @return array
	 */
	function getImageSize( $image, $filename ) {
		$header = self::getXCFMetaData( $filename );
		if ( !$header ) {
			return false;
		}

		# Forge a return array containing metadata information just like getimagesize()
		# See PHP documentation at: https://secure.php.net/getimagesize
		return [
			0 => $header['width'],
			1 => $header['height'],
			2 => null, # IMAGETYPE constant, none exist for XCF.
			3 => "height=\"{$header['height']}\" width=\"{$header['width']}\"",
			'mime' => 'image/x-xcf',
			'channels' => null,
			'bits' => 8, # Always 8-bits per color
		];
	}

	/**
	 * Metadata for a given XCF file
	 *
	 * Will return false if file magic signature is not recognized
	 * @author Hexmode
	 * @author Hashar
	 *
	 * @param string $filename Full path to a XCF file
	 * @return bool|array Metadata Array just like PHP getimagesize()
	 */
	static function getXCFMetaData( $filename ) {
		# Decode master structure
		$f = fopen( $filename, 'rb' );
		if ( !$f ) {
			return false;
		}
		# The image structure always starts at offset 0 in the XCF file.
		# So we just read it :-)
		$binaryHeader = fread( $f, 26 );
		fclose( $f );

		/**
		 * Master image structure:
		 *
		 * byte[9] "gimp xcf "  File type magic
		 * byte[4] version      XCF version
		 *                        "file" - version 0
		 *                        "v001" - version 1
		 *                        "v002" - version 2
		 * byte    0            Zero-terminator for version tag
		 * uint32  width        With of canvas
		 * uint32  height       Height of canvas
		 * uint32  base_type    Color mode of the image; one of
		 *                         0: RGB color
		 *                         1: Grayscale
		 *                         2: Indexed color
		 *        (enum GimpImageBaseType in libgimpbase/gimpbaseenums.h)
		 */
		try {
			$header = wfUnpack(
				"A9magic" . # A: space padded
					"/a5version" . # a: zero padded
					"/Nwidth" . # \
					"/Nheight" . # N: unsigned long 32bit big endian
					"/Nbase_type", # /
				$binaryHeader
			);
		} catch ( Exception $mwe ) {
			return false;
		}

		# Check values
		if ( $header['magic'] !== 'gimp xcf' ) {
			wfDebug( __METHOD__ . " '$filename' has invalid magic signature.\n" );

			return false;
		}
		# TODO: we might want to check for sane values of width and height

		wfDebug( __METHOD__ .
			": canvas size of '$filename' is {$header['width']} x {$header['height']} px\n" );

		return $header;
	}

	/**
	 * Store the channel type
	 *
	 * Greyscale files need different command line options.
	 *
	 * @param File|FSFile $file The image object, or false if there isn't one.
	 *   Warning, FSFile::getPropsFromPath might pass an (object)array() instead (!)
	 * @param string $filename
	 * @return string
	 */
	public function getMetadata( $file, $filename ) {
		$header = self::getXCFMetaData( $filename );
		$metadata = [];
		if ( $header ) {
			// Try to be consistent with the names used by PNG files.
			// Unclear from base media type if it has an alpha layer,
			// so just assume that it does since it "potentially" could.
			switch ( $header['base_type'] ) {
				case 0:
					$metadata['colorType'] = 'truecolour-alpha';
					break;
				case 1:
					$metadata['colorType'] = 'greyscale-alpha';
					break;
				case 2:
					$metadata['colorType'] = 'index-coloured';
					break;
				default:
					$metadata['colorType'] = 'unknown';
			}
		} else {
			// Marker to prevent repeated attempted extraction
			$metadata['error'] = true;
		}
		return serialize( $metadata );
	}

	/**
	 * Should we refresh the metadata
	 *
	 * @param File $file The file object for the file in question
	 * @param string $metadata Serialized metadata
	 * @return bool One of the self::METADATA_(BAD|GOOD|COMPATIBLE) constants
	 */
	public function isMetadataValid( $file, $metadata ) {
		if ( !$metadata ) {
			// Old metadata when we just put an empty string in there
			return self::METADATA_BAD;
		} else {
			return self::METADATA_GOOD;
		}
	}

	/**
	 * Must use "im" for XCF
	 *
	 * @param string $dstPath
	 * @param bool $checkDstPath
	 * @return string
	 */
	protected function getScalerType( $dstPath, $checkDstPath = true ) {
		return "im";
	}

	/**
	 * Can we render this file?
	 *
	 * Image magick doesn't support indexed xcf files as of current
	 * writing (as of 6.8.9-3)
	 * @param File $file
	 * @return bool
	 */
	public function canRender( $file ) {
		Wikimedia\suppressWarnings();
		$xcfMeta = unserialize( $file->getMetadata() );
		Wikimedia\restoreWarnings();
		if ( isset( $xcfMeta['colorType'] ) && $xcfMeta['colorType'] === 'index-coloured' ) {
			return false;
		}
		return parent::canRender( $file );
	}
}
