<?php
/**
 * Media-handling base classes and generic functionality.
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
 * Base media handler class
 *
 * @ingroup Media
 */
abstract class MediaHandler {
	const TRANSFORM_LATER = 1;
	const METADATA_GOOD = true;
	const METADATA_BAD = false;
	const METADATA_COMPATIBLE = 2; // for old but backwards compatible.
	/**
	 * Instance cache
	 */
	static $handlers = array();

	/**
	 * Get a MediaHandler for a given MIME type from the instance cache
	 *
	 * @param $type string
	 *
	 * @return MediaHandler
	 */
	static function getHandler( $type ) {
		global $wgMediaHandlers;
		if ( !isset( $wgMediaHandlers[$type] ) ) {
			wfDebug( __METHOD__ . ": no handler found for $type.\n" );
			return false;
		}
		$class = $wgMediaHandlers[$type];
		if ( !isset( self::$handlers[$class] ) ) {
			self::$handlers[$class] = new $class;
			if ( !self::$handlers[$class]->isEnabled() ) {
				self::$handlers[$class] = false;
			}
		}
		return self::$handlers[$class];
	}

	/**
	 * Get an associative array mapping magic word IDs to parameter names.
	 * Will be used by the parser to identify parameters.
	 */
	abstract function getParamMap();

	/**
	 * Validate a thumbnail parameter at parse time.
	 * Return true to accept the parameter, and false to reject it.
	 * If you return false, the parser will do something quiet and forgiving.
	 *
	 * @param $name
	 * @param $value
	 */
	abstract function validateParam( $name, $value );

	/**
	 * Merge a parameter array into a string appropriate for inclusion in filenames
	 *
	 * @param $params array Array of parameters that have been through normaliseParams.
	 * @return String
	 */
	abstract function makeParamString( $params );

	/**
	 * Parse a param string made with makeParamString back into an array
	 *
	 * @param $str string The parameter string without file name (e.g. 122px)
	 * @return Array|Boolean Array of parameters or false on failure.
	 */
	abstract function parseParamString( $str );

	/**
	 * Changes the parameter array as necessary, ready for transformation.
	 * Should be idempotent.
	 * Returns false if the parameters are unacceptable and the transform should fail
	 * @param $image
	 * @param $params
	 */
	abstract function normaliseParams( $image, &$params );

	/**
	 * Get an image size array like that returned by getimagesize(), or false if it
	 * can't be determined.
	 *
	 * @param $image File: the image object, or false if there isn't one
	 * @param string $path the filename
	 * @return Array Follow the format of PHP getimagesize() internal function. See http://www.php.net/getimagesize
	 */
	abstract function getImageSize( $image, $path );

	/**
	 * Get handler-specific metadata which will be saved in the img_metadata field.
	 *
	 * @param $image File: the image object, or false if there isn't one.
	 *   Warning, FSFile::getPropsFromPath might pass an (object)array() instead (!)
	 * @param string $path the filename
	 * @return String
	 */
	function getMetadata( $image, $path ) {
		return '';
	}

	/**
	 * Get metadata version.
	 *
	 * This is not used for validating metadata, this is used for the api when returning
	 * metadata, since api content formats should stay the same over time, and so things
	 * using ForiegnApiRepo can keep backwards compatibility
	 *
	 * All core media handlers share a common version number, and extensions can
	 * use the GetMetadataVersion hook to append to the array (they should append a unique
	 * string so not to get confusing). If there was a media handler named 'foo' with metadata
	 * version 3 it might add to the end of the array the element 'foo=3'. if the core metadata
	 * version is 2, the end version string would look like '2;foo=3'.
	 *
	 * @return string version string
	 */
	static function getMetadataVersion() {
		$version = Array( '2' ); // core metadata version
		wfRunHooks( 'GetMetadataVersion', Array( &$version ) );
		return implode( ';', $version );
	}

	/**
	 * Convert metadata version.
	 *
	 * By default just returns $metadata, but can be used to allow
	 * media handlers to convert between metadata versions.
	 *
	 * @param $metadata Mixed String or Array metadata array (serialized if string)
	 * @param $version Integer target version
	 * @return Array serialized metadata in specified version, or $metadata on fail.
	 */
	function convertMetadataVersion( $metadata, $version = 1 ) {
		if ( !is_array( $metadata ) ) {

			//unserialize to keep return parameter consistent.
			wfSuppressWarnings();
			$ret = unserialize( $metadata );
			wfRestoreWarnings();
			return $ret;
		}
		return $metadata;
	}

	/**
	 * Get a string describing the type of metadata, for display purposes.
	 *
	 * @return string
	 */
	function getMetadataType( $image ) {
		return false;
	}

	/**
	 * Check if the metadata string is valid for this handler.
	 * If it returns MediaHandler::METADATA_BAD (or false), Image
	 * will reload the metadata from the file and update the database.
	 * MediaHandler::METADATA_GOOD for if the metadata is a-ok,
	 * MediaHanlder::METADATA_COMPATIBLE if metadata is old but backwards
	 * compatible (which may or may not trigger a metadata reload).
	 * @return bool
	 */
	function isMetadataValid( $image, $metadata ) {
		return self::METADATA_GOOD;
	}

	/**
	 * Get a MediaTransformOutput object representing an alternate of the transformed
	 * output which will call an intermediary thumbnail assist script.
	 *
	 * Used when the repository has a thumbnailScriptUrl option configured.
	 *
	 * Return false to fall back to the regular getTransform().
	 * @return bool
	 */
	function getScriptedTransform( $image, $script, $params ) {
		return false;
	}

	/**
	 * Get a MediaTransformOutput object representing the transformed output. Does not
	 * actually do the transform.
	 *
	 * @param $image File: the image object
	 * @param string $dstPath filesystem destination path
	 * @param string $dstUrl Destination URL to use in output HTML
	 * @param array $params Arbitrary set of parameters validated by $this->validateParam()
	 * @return MediaTransformOutput
	 */
	final function getTransform( $image, $dstPath, $dstUrl, $params ) {
		return $this->doTransform( $image, $dstPath, $dstUrl, $params, self::TRANSFORM_LATER );
	}

	/**
	 * Get a MediaTransformOutput object representing the transformed output. Does the
	 * transform unless $flags contains self::TRANSFORM_LATER.
	 *
	 * @param $image File: the image object
	 * @param string $dstPath filesystem destination path
	 * @param string $dstUrl destination URL to use in output HTML
	 * @param array $params arbitrary set of parameters validated by $this->validateParam()
	 *   Note: These parameters have *not* gone through $this->normaliseParams()
	 * @param $flags Integer: a bitfield, may contain self::TRANSFORM_LATER
	 *
	 * @return MediaTransformOutput
	 */
	abstract function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 );

	/**
	 * Get the thumbnail extension and MIME type for a given source MIME type
	 *
	 * @param String $ext Extension of original file
	 * @param String $mime Mime type of original file
	 * @param Array $params Handler specific rendering parameters
	 * @return array thumbnail extension and MIME type
	 */
	function getThumbType( $ext, $mime, $params = null ) {
		$magic = MimeMagic::singleton();
		if ( !$ext || $magic->isMatchingExtension( $ext, $mime ) === false ) {
			// The extension is not valid for this mime type and we do
			// recognize the mime type
			$extensions = $magic->getExtensionsForType( $mime );
			if ( $extensions ) {
				return array( strtok( $extensions, ' ' ), $mime );
			}
		}

		// The extension is correct (true) or the mime type is unknown to
		// MediaWiki (null)
		return array( $ext, $mime );
	}

	/**
	 * Get useful response headers for GET/HEAD requests for a file with the given metadata
	 * @param $metadata mixed Result of the getMetadata() function of this handler for a file
	 * @return Array
	 */
	public function getStreamHeaders( $metadata ) {
		return array();
	}

	/**
	 * True if the handled types can be transformed
	 * @return bool
	 */
	function canRender( $file ) {
		return true;
	}

	/**
	 * True if handled types cannot be displayed directly in a browser
	 * but can be rendered
	 * @return bool
	 */
	function mustRender( $file ) {
		return false;
	}

	/**
	 * True if the type has multi-page capabilities
	 * @return bool
	 */
	function isMultiPage( $file ) {
		return false;
	}

	/**
	 * Page count for a multi-page document, false if unsupported or unknown
	 * @return bool
	 */
	function pageCount( $file ) {
		return false;
	}

	/**
	 * The material is vectorized and thus scaling is lossless
	 * @return bool
	 */
	function isVectorized( $file ) {
		return false;
	}

	/**
	 * The material is an image, and is animated.
	 * In particular, video material need not return true.
	 * @note Before 1.20, this was a method of ImageHandler only
	 * @return bool
	 */
	function isAnimatedImage( $file ) {
		return false;
	}

	/**
	 * If the material is animated, we can animate the thumbnail
	 * @since 1.20
	 * @return bool If material is not animated, handler may return any value.
	 */
	function canAnimateThumbnail( $file ) {
		return true;
	}

	/**
	 * False if the handler is disabled for all files
	 * @return bool
	 */
	function isEnabled() {
		return true;
	}

	/**
	 * Get an associative array of page dimensions
	 * Currently "width" and "height" are understood, but this might be
	 * expanded in the future.
	 * Returns false if unknown.
	 *
	 * It is expected that handlers for paged media (e.g. DjVuHandler)
	 * will override this method so that it gives the correct results
	 * for each specific page of the file, using the $page argument.
	 *
	 * @note For non-paged media, use getImageSize.
	 *
	 * @param $image File
	 * @param $page What page to get dimensions of
	 * @return array|bool
	 */
	function getPageDimensions( $image, $page ) {
		$gis = $this->getImageSize( $image, $image->getLocalRefPath() );
		if ( $gis ) {
			return array(
				'width' => $gis[0],
				'height' => $gis[1]
			);
		} else {
			return false;
		}
	}

	/**
	 * Generic getter for text layer.
	 * Currently overloaded by PDF and DjVu handlers
	 * @return bool
	 */
	function getPageText( $image, $page ) {
		return false;
	}

	/**
	 * Get an array structure that looks like this:
	 *
	 * array(
	 *    'visible' => array(
	 *       'Human-readable name' => 'Human readable value',
	 *       ...
	 *    ),
	 *    'collapsed' => array(
	 *       'Human-readable name' => 'Human readable value',
	 *       ...
	 *    )
	 * )
	 * The UI will format this into a table where the visible fields are always
	 * visible, and the collapsed fields are optionally visible.
	 *
	 * The function should return false if there is no metadata to display.
	 */

	/**
	 * @todo FIXME: I don't really like this interface, it's not very flexible
	 * I think the media handler should generate HTML instead. It can do
	 * all the formatting according to some standard. That makes it possible
	 * to do things like visual indication of grouped and chained streams
	 * in ogg container files.
	 * @return bool
	 */
	function formatMetadata( $image ) {
		return false;
	}

	/** sorts the visible/invisible field.
	 * Split off from ImageHandler::formatMetadata, as used by more than
	 * one type of handler.
	 *
	 * This is used by the media handlers that use the FormatMetadata class
	 *
	 * @param array $metadataArray metadata array
	 * @return array for use displaying metadata.
	 */
	function formatMetadataHelper( $metadataArray ) {
		$result = array(
			'visible' => array(),
			'collapsed' => array()
		);

		$formatted = FormatMetadata::getFormattedData( $metadataArray );
		// Sort fields into visible and collapsed
		$visibleFields = $this->visibleMetadataFields();
		foreach ( $formatted as $name => $value ) {
			$tag = strtolower( $name );
			self::addMeta( $result,
				in_array( $tag, $visibleFields ) ? 'visible' : 'collapsed',
				'exif',
				$tag,
				$value
			);
		}
		return $result;
	}

	/**
	 * Get a list of metadata items which should be displayed when
	 * the metadata table is collapsed.
	 *
	 * @return array of strings
	 * @access protected
	 */
	function visibleMetadataFields() {
		$fields = array();
		$lines = explode( "\n", wfMessage( 'metadata-fields' )->inContentLanguage()->text() );
		foreach ( $lines as $line ) {
			$matches = array();
			if ( preg_match( '/^\\*\s*(.*?)\s*$/', $line, $matches ) ) {
				$fields[] = $matches[1];
			}
		}
		$fields = array_map( 'strtolower', $fields );
		return $fields;
	}

	/**
	 * This is used to generate an array element for each metadata value
	 * That array is then used to generate the table of metadata values
	 * on the image page
	 *
	 * @param &$array Array An array containing elements for each type of visibility
	 * and each of those elements being an array of metadata items. This function adds
	 * a value to that array.
	 * @param string $visibility ('visible' or 'collapsed') if this value is hidden
	 * by default.
	 * @param string $type type of metadata tag (currently always 'exif')
	 * @param string $id the name of the metadata tag (like 'artist' for example).
	 * its name in the table displayed is the message "$type-$id" (Ex exif-artist ).
	 * @param string $value thingy goes into a wikitext table; it used to be escaped but
	 * that was incompatible with previous practise of customized display
	 * with wikitext formatting via messages such as 'exif-model-value'.
	 * So the escaping is taken back out, but generally this seems a confusing
	 * interface.
	 * @param string $param value to pass to the message for the name of the field
	 * as $1. Currently this parameter doesn't seem to ever be used.
	 *
	 * Note, everything here is passed through the parser later on (!)
	 */
	protected static function addMeta( &$array, $visibility, $type, $id, $value, $param = false ) {
		$msg = wfMessage( "$type-$id", $param );
		if ( $msg->exists() ) {
			$name = $msg->text();
		} else {
			// This is for future compatibility when using instant commons.
			// So as to not display as ugly a name if a new metadata
			// property is defined that we don't know about
			// (not a major issue since such a property would be collapsed
			// by default).
			wfDebug( __METHOD__ . ' Unknown metadata name: ' . $id . "\n" );
			$name = wfEscapeWikiText( $id );
		}
		$array[$visibility][] = array(
			'id' => "$type-$id",
			'name' => $name,
			'value' => $value
		);
	}

	/**
	 * Used instead of getLongDesc if there is no handler registered for file.
	 *
	 * @param $file File
	 * @return string
	 */
	function getShortDesc( $file ) {
		global $wgLang;
		return htmlspecialchars( $wgLang->formatSize( $file->getSize() ) );
	}

	/**
	 * Short description. Shown on Special:Search results.
	 *
	 * @param $file File
	 * @return string
	 */
	function getLongDesc( $file ) {
		global $wgLang;
		return wfMessage( 'file-info', htmlspecialchars( $wgLang->formatSize( $file->getSize() ) ),
			$file->getMimeType() )->parse();
	}

	/**
	 * Long description. Shown under image on image description page surounded by ().
	 *
	 * @param $file File
	 * @return string
	 */
	static function getGeneralShortDesc( $file ) {
		global $wgLang;
		return $wgLang->formatSize( $file->getSize() );
	}

	/**
	 * Used instead of getShortDesc if there is no handler registered for file.
	 *
	 * @param $file File
	 * @return string
	 */
	static function getGeneralLongDesc( $file ) {
		global $wgLang;
		return wfMessage( 'file-info', $wgLang->formatSize( $file->getSize() ),
			$file->getMimeType() )->parse();
	}

	/**
	 * Calculate the largest thumbnail width for a given original file size
	 * such that the thumbnail's height is at most $maxHeight.
	 * @param $boxWidth Integer Width of the thumbnail box.
	 * @param $boxHeight Integer Height of the thumbnail box.
	 * @param $maxHeight Integer Maximum height expected for the thumbnail.
	 * @return Integer.
	 */
	public static function fitBoxWidth( $boxWidth, $boxHeight, $maxHeight ) {
		$idealWidth = $boxWidth * $maxHeight / $boxHeight;
		$roundedUp = ceil( $idealWidth );
		if ( round( $roundedUp * $boxHeight / $boxWidth ) > $maxHeight ) {
			return floor( $idealWidth );
		} else {
			return $roundedUp;
		}
	}

	/**
	 * Shown in file history box on image description page.
	 *
	 * @param File $file
	 * @return String Dimensions
	 */
	function getDimensionsString( $file ) {
		return '';
	}

	/**
	 * Modify the parser object post-transform.
	 *
	 * This is often used to do $parser->addOutputHook(),
	 * in order to add some javascript to render a viewer.
	 * See TimedMediaHandler or OggHandler for an example.
	 *
	 * @param Parser $parser
	 * @param File $file
	 */
	function parserTransformHook( $parser, $file ) {}

	/**
	 * File validation hook called on upload.
	 *
	 * If the file at the given local path is not valid, or its MIME type does not
	 * match the handler class, a Status object should be returned containing
	 * relevant errors.
	 *
	 * @param string $fileName The local path to the file.
	 * @return Status object
	 */
	function verifyUpload( $fileName ) {
		return Status::newGood();
	}

	/**
	 * Check for zero-sized thumbnails. These can be generated when
	 * no disk space is available or some other error occurs
	 *
	 * @param string $dstPath The location of the suspect file
	 * @param int $retval Return value of some shell process, file will be deleted if this is non-zero
	 * @return bool True if removed, false otherwise
	 */
	function removeBadFile( $dstPath, $retval = 0 ) {
		if ( file_exists( $dstPath ) ) {
			$thumbstat = stat( $dstPath );
			if ( $thumbstat['size'] == 0 || $retval != 0 ) {
				$result = unlink( $dstPath );

				if ( $result ) {
					wfDebugLog( 'thumbnail',
						sprintf( 'Removing bad %d-byte thumbnail "%s". unlink() succeeded',
							$thumbstat['size'], $dstPath ) );
				} else {
					wfDebugLog( 'thumbnail',
						sprintf( 'Removing bad %d-byte thumbnail "%s". unlink() failed',
							$thumbstat['size'], $dstPath ) );
				}
				return true;
			}
		}
		return false;
	}

	/**
	 * Remove files from the purge list.
	 *
	 * This is used by some video handlers to prevent ?action=purge
	 * from removing a transcoded video, which is expensive to
	 * regenerate.
	 *
	 * @see LocalFile::purgeThumbnails
	 *
	 * @param array $files
	 * @param array $options Purge options. Currently will always be
	 *  an array with a single key 'forThumbRefresh' set to true.
	 */
	public function filterThumbnailPurgeList( &$files, $options ) {
		// Do nothing
	}

	/*
	 * True if the handler can rotate the media
	 * @since 1.21
	 * @return bool
	 */
	public static function canRotate() {
		return false;
	}

	/**
	 * On supporting image formats, try to read out the low-level orientation
	 * of the file and return the angle that the file needs to be rotated to
	 * be viewed.
	 *
	 * This information is only useful when manipulating the original file;
	 * the width and height we normally work with is logical, and will match
	 * any produced output views.
	 *
	 * For files we don't know, we return 0.
	 *
	 * @param $file File
	 * @return int 0, 90, 180 or 270
	 */
	public function getRotation( $file ) {
		return 0;
	}

}
