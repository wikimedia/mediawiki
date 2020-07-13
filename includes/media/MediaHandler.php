<?php
/**
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
use MediaWiki\MediaWikiServices;

/**
 * @defgroup Media Media
 *
 * Media handlers and other classes relating to Multimedia support,
 * with the exception of FileRepo and FileBackend, which have their own groups.
 */

/**
 * Base media handler class
 *
 * @stable to extend
 *
 * @ingroup Media
 */
abstract class MediaHandler {
	public const TRANSFORM_LATER = 1;
	public const METADATA_GOOD = true;
	public const METADATA_BAD = false;
	public const METADATA_COMPATIBLE = 2; // for old but backwards compatible.
	/**
	 * Max length of error logged by logErrorForExternalProcess()
	 */
	private const MAX_ERR_LOG_SIZE = 65535;

	/**
	 * Get a MediaHandler for a given MIME type from the instance cache
	 *
	 * @param string $type
	 * @return MediaHandler|bool
	 */
	public static function getHandler( $type ) {
		return MediaWikiServices::getInstance()
			->getMediaHandlerFactory()->getHandler( $type );
	}

	/**
	 * Get an associative array mapping magic word IDs to parameter names.
	 * Will be used by the parser to identify parameters.
	 */
	abstract public function getParamMap();

	/**
	 * Validate a thumbnail parameter at parse time.
	 * Return true to accept the parameter, and false to reject it.
	 * If you return false, the parser will do something quiet and forgiving.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	abstract public function validateParam( $name, $value );

	/**
	 * Merge a parameter array into a string appropriate for inclusion in filenames
	 *
	 * @param array $params Array of parameters that have been through normaliseParams.
	 * @return string
	 */
	abstract public function makeParamString( $params );

	/**
	 * Parse a param string made with makeParamString back into an array
	 *
	 * @param string $str The parameter string without file name (e.g. 122px)
	 * @return array|bool Array of parameters or false on failure.
	 */
	abstract public function parseParamString( $str );

	/**
	 * Changes the parameter array as necessary, ready for transformation.
	 * Should be idempotent.
	 * Returns false if the parameters are unacceptable and the transform should fail
	 * @param File $image
	 * @param array &$params
	 */
	abstract public function normaliseParams( $image, &$params );

	/**
	 * Get an image size array like that returned by getimagesize(), or false if it
	 * can't be determined.
	 *
	 * This function is used for determining the width, height and bitdepth directly
	 * from an image. The results are stored in the database in the img_width,
	 * img_height, img_bits fields.
	 *
	 * @note If this is a multipage file, return the width and height of the
	 *  first page.
	 *
	 * @param File|FSFile $image The image object, or false if there isn't one.
	 *   Warning, FSFile::getPropsFromPath might pass an FSFile instead of File (!)
	 * @param string $path The filename
	 * @return array|false Follow the format of PHP getimagesize() internal function.
	 *   See https://www.php.net/getimagesize. MediaWiki will only ever use the
	 *   first two array keys (the width and height), and the 'bits' associative
	 *   key. All other array keys are ignored. Returning a 'bits' key is optional
	 *   as not all formats have a notion of "bitdepth". Returns false on failure.
	 */
	abstract public function getImageSize( $image, $path );

	/**
	 * Get handler-specific metadata which will be saved in the img_metadata field.
	 * @stable to override
	 *
	 * @param File|FSFile $image The image object, or false if there isn't one.
	 *   Warning, FSFile::getPropsFromPath might pass an FSFile instead of File (!)
	 * @param string $path The filename
	 * @return string A string of metadata in php serialized form (Run through serialize())
	 */
	public function getMetadata( $image, $path ) {
		return '';
	}

	/**
	 * Get metadata version.
	 *
	 * This is not used for validating metadata, this is used for the api when returning
	 * metadata, since api content formats should stay the same over time, and so things
	 * using ForeignApiRepo can keep backwards compatibility
	 *
	 * All core media handlers share a common version number, and extensions can
	 * use the GetMetadataVersion hook to append to the array (they should append a unique
	 * string so not to get confusing). If there was a media handler named 'foo' with metadata
	 * version 3 it might add to the end of the array the element 'foo=3'. if the core metadata
	 * version is 2, the end version string would look like '2;foo=3'.
	 *
	 * @stable to override
	 *
	 * @return string Version string
	 */
	public static function getMetadataVersion() {
		$version = [ '2' ]; // core metadata version
		Hooks::runner()->onGetMetadataVersion( $version );

		return implode( ';', $version );
	}

	/**
	 * Convert metadata version.
	 *
	 * By default just returns $metadata, but can be used to allow
	 * media handlers to convert between metadata versions.
	 * @stable to override
	 *
	 * @param string|array $metadata Metadata array (serialized if string)
	 * @param int $version Target version
	 * @return array Serialized metadata in specified version, or $metadata on fail.
	 */
	public function convertMetadataVersion( $metadata, $version = 1 ) {
		if ( !is_array( $metadata ) ) {
			// unserialize to keep return parameter consistent.
			Wikimedia\suppressWarnings();
			$ret = unserialize( $metadata );
			Wikimedia\restoreWarnings();

			return $ret;
		}

		return $metadata;
	}

	/**
	 * Get a string describing the type of metadata, for display purposes.
	 * @stable to override
	 *
	 * @note This method is currently unused.
	 * @param File $image
	 * @return string
	 */
	public function getMetadataType( $image ) {
		return false;
	}

	/**
	 * Check if the metadata string is valid for this handler.
	 * If it returns MediaHandler::METADATA_BAD (or false), Image
	 * will reload the metadata from the file and update the database.
	 * MediaHandler::METADATA_GOOD for if the metadata is a-ok,
	 * MediaHandler::METADATA_COMPATIBLE if metadata is old but backwards
	 * compatible (which may or may not trigger a metadata reload).
	 *
	 * @note Returning self::METADATA_BAD will trigger a metadata reload from
	 *  file on page view. Always returning this from a broken file, or suddenly
	 *  triggering as bad metadata for a large number of files can cause
	 *  performance problems.
	 *
	 * @stable to override
	 * @param File $image
	 * @param string $metadata The metadata in serialized form
	 * @return bool|int
	 */
	public function isMetadataValid( $image, $metadata ) {
		return self::METADATA_GOOD;
	}

	/**
	 * Get an array of standard (FormatMetadata type) metadata values.
	 *
	 * The returned data is largely the same as that from getMetadata(),
	 * but formatted in a standard, stable, handler-independent way.
	 * The idea being that some values like ImageDescription or Artist
	 * are universal and should be retrievable in a handler generic way.
	 *
	 * The specific properties are the type of properties that can be
	 * handled by the FormatMetadata class. These values are exposed to the
	 * user via the filemetadata parser function.
	 *
	 * Details of the response format of this function can be found at
	 * https://www.mediawiki.org/wiki/Manual:File_metadata_handling
	 * tl/dr: the response is an associative array of
	 * properties keyed by name, but the value can be complex. You probably
	 * want to call one of the FormatMetadata::flatten* functions on the
	 * property values before using them, or call
	 * FormatMetadata::getFormattedData() on the full response array, which
	 * transforms all values into prettified, human-readable text.
	 *
	 * Subclasses overriding this function must return a value which is a
	 * valid API response fragment (all associative array keys are valid
	 * XML tagnames).
	 *
	 * Note, if the file simply has no metadata, but the handler supports
	 * this interface, it should return an empty array, not false.
	 *
	 * @stable to override
	 *
	 * @param File $file
	 * @return array|bool False if interface not supported
	 * @since 1.23
	 */
	public function getCommonMetaArray( File $file ) {
		return false;
	}

	/**
	 * Get a MediaTransformOutput object representing an alternate of the transformed
	 * output which will call an intermediary thumbnail assist script.
	 *
	 * Used when the repository has a thumbnailScriptUrl option configured.
	 *
	 * Return false to fall back to the regular getTransform().
	 *
	 * @stable to override
	 *
	 * @param File $image
	 * @param string $script
	 * @param array $params
	 * @return bool|ThumbnailImage
	 */
	public function getScriptedTransform( $image, $script, $params ) {
		return false;
	}

	/**
	 * Get a MediaTransformOutput object representing the transformed output. Does not
	 * actually do the transform.
	 *
	 * @stable to override
	 *
	 * @param File $image
	 * @param string $dstPath Filesystem destination path
	 * @param string $dstUrl Destination URL to use in output HTML
	 * @param array $params Arbitrary set of parameters validated by $this->validateParam()
	 * @return MediaTransformOutput
	 */
	final public function getTransform( $image, $dstPath, $dstUrl, $params ) {
		return $this->doTransform( $image, $dstPath, $dstUrl, $params, self::TRANSFORM_LATER );
	}

	/**
	 * Get a MediaTransformOutput object representing the transformed output. Does the
	 * transform unless $flags contains self::TRANSFORM_LATER.
	 *
	 * @stable to override
	 *
	 * @param File $image
	 * @param string $dstPath Filesystem destination path
	 * @param string $dstUrl Destination URL to use in output HTML
	 * @param array $params Arbitrary set of parameters validated by $this->validateParam()
	 *   Note: These parameters have *not* gone through $this->normaliseParams()
	 * @param int $flags A bitfield, may contain self::TRANSFORM_LATER
	 * @return MediaTransformOutput
	 */
	abstract public function doTransform( $image, $dstPath, $dstUrl, $params, $flags = 0 );

	/**
	 * Get the thumbnail extension and MIME type for a given source MIME type
	 *
	 * @stable to override
	 *
	 * @param string $ext Extension of original file
	 * @param string $mime MIME type of original file
	 * @param array|null $params Handler specific rendering parameters
	 * @return array Thumbnail extension and MIME type
	 */
	public function getThumbType( $ext, $mime, $params = null ) {
		$magic = MediaWiki\MediaWikiServices::getInstance()->getMimeAnalyzer();
		if ( !$ext || $magic->isMatchingExtension( $ext, $mime ) === false ) {
			// The extension is not valid for this MIME type and we do
			// recognize the MIME type
			$knownExt = $magic->getExtensionFromMimeTypeOrNull( $mime );
			if ( $knownExt !== null ) {
				return [ $knownExt, $mime ];
			}
		}

		// The extension is correct (true) or the MIME type is unknown to
		// MediaWiki (null)
		return [ $ext, $mime ];
	}

	/**
	 * True if the handled types can be transformed
	 *
	 * @stable to override
	 *
	 * @param File $file
	 * @return bool
	 */
	public function canRender( $file ) {
		return true;
	}

	/**
	 * True if handled types cannot be displayed directly in a browser
	 * but can be rendered
	 *
	 * @stable to override
	 *
	 * @param File $file
	 * @return bool
	 */
	public function mustRender( $file ) {
		return false;
	}

	/**
	 * True if the type has multi-page capabilities
	 *
	 * @stable to override
	 *
	 * @param File $file
	 * @return bool
	 */
	public function isMultiPage( $file ) {
		return false;
	}

	/**
	 * Page count for a multi-page document, false if unsupported or unknown
	 *
	 * @stable to override
	 *
	 * @param File $file
	 * @return int|false
	 */
	public function pageCount( File $file ) {
		return false;
	}

	/**
	 * The material is vectorized and thus scaling is lossless
	 *
	 * @stable to override
	 *
	 * @param File $file
	 * @return bool
	 */
	public function isVectorized( $file ) {
		return false;
	}

	/**
	 * The material is an image, and is animated.
	 * In particular, video material need not return true.
	 * @note Before 1.20, this was a method of ImageHandler only
	 *
	 * @stable to override
	 *
	 * @param File $file
	 * @return bool
	 */
	public function isAnimatedImage( $file ) {
		return false;
	}

	/**
	 * If the material is animated, we can animate the thumbnail
	 * @since 1.20
	 *
	 * @stable to override
	 *
	 * @param File $file
	 * @return bool If material is not animated, handler may return any value.
	 */
	public function canAnimateThumbnail( $file ) {
		return true;
	}

	/**
	 * False if the handler is disabled for all files
	 * @stable to override
	 *
	 * @return bool
	 */
	public function isEnabled() {
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
	 * @stable to override
	 *
	 * @param File $image
	 * @param int $page What page to get dimensions of
	 * @return array|bool
	 */
	public function getPageDimensions( File $image, $page ) {
		$gis = $this->getImageSize( $image, $image->getLocalRefPath() );
		if ( $gis ) {
			return [
				'width' => $gis[0],
				'height' => $gis[1]
			];
		} else {
			return false;
		}
	}

	/**
	 * Generic getter for text layer.
	 * Currently overloaded by PDF and DjVu handlers
	 * @stable to override
	 *
	 * @param File $image
	 * @param int $page Page number to get information for
	 * @return bool|string Page text or false when no text found or if
	 *   unsupported.
	 */
	public function getPageText( File $image, $page ) {
		return false;
	}

	/**
	 * Get the text of the entire document.
	 * @param File $file
	 * @return bool|string The text of the document or false if unsupported.
	 */
	public function getEntireText( File $file ) {
		$numPages = $file->pageCount();
		if ( !$numPages ) {
			// Not a multipage document
			return $this->getPageText( $file, 1 );
		}
		$document = '';
		for ( $i = 1; $i <= $numPages; $i++ ) {
			$curPage = $this->getPageText( $file, $i );
			if ( is_string( $curPage ) ) {
				$document .= $curPage . "\n";
			}
		}
		if ( $document !== '' ) {
			return $document;
		}
		return false;
	}

	/**
	 * Get an array structure that looks like this:
	 *
	 * [
	 *    'visible' => [
	 *       'Human-readable name' => 'Human readable value',
	 *       ...
	 *    ],
	 *    'collapsed' => [
	 *       'Human-readable name' => 'Human readable value',
	 *       ...
	 *    ]
	 * ]
	 * The UI will format this into a table where the visible fields are always
	 * visible, and the collapsed fields are optionally visible.
	 *
	 * The function should return false if there is no metadata to display.
	 */

	/**
	 * @todo FIXME: This interface is not very flexible. The media handler
	 * should generate HTML instead. It can do all the formatting according
	 * to some standard. That makes it possible to do things like visual
	 * indication of grouped and chained streams in ogg container files.
	 * @stable to override
	 *
	 * @param File $image
	 * @param bool|IContextSource $context Context to use (optional)
	 * @return array|bool
	 */
	public function formatMetadata( $image, $context = false ) {
		return false;
	}

	/** sorts the visible/invisible field.
	 * Split off from ImageHandler::formatMetadata, as used by more than
	 * one type of handler.
	 *
	 * This is used by the media handlers that use the FormatMetadata class
	 *
	 * @stable to override
	 *
	 * @param array $metadataArray
	 * @param bool|IContextSource $context Context to use (optional)
	 * @return array Array for use displaying metadata.
	 */
	protected function formatMetadataHelper( $metadataArray, $context = false ) {
		$result = [
			'visible' => [],
			'collapsed' => []
		];

		$formatted = FormatMetadata::getFormattedData( $metadataArray, $context );
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
	 * @stable to override
	 *
	 * @return array Array of strings
	 */
	protected function visibleMetadataFields() {
		return FormatMetadata::getVisibleFields();
	}

	/**
	 * This is used to generate an array element for each metadata value
	 * That array is then used to generate the table of metadata values
	 * on the image page
	 *
	 * @param array &$array An array containing elements for each type of visibility
	 *   and each of those elements being an array of metadata items. This function adds
	 *   a value to that array.
	 * @param string $visibility ('visible' or 'collapsed') if this value is hidden
	 *   by default.
	 * @param string $type Type of metadata tag (currently always 'exif')
	 * @param string $id The name of the metadata tag (like 'artist' for example).
	 *   its name in the table displayed is the message "$type-$id" (Ex exif-artist ).
	 * @param string $value Thingy goes into a wikitext table; it used to be escaped but
	 *   that was incompatible with previous practise of customized display
	 *   with wikitext formatting via messages such as 'exif-model-value'.
	 *   So the escaping is taken back out, but generally this seems a confusing
	 *   interface.
	 * @param bool|string $param Value to pass to the message for the name of the field
	 *   as $1. Currently this parameter doesn't seem to ever be used.
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
			wfDebug( __METHOD__ . ' Unknown metadata name: ' . $id );
			$name = wfEscapeWikiText( $id );
		}
		$array[$visibility][] = [
			'id' => "$type-$id",
			'name' => $name,
			'value' => $value
		];
	}

	/**
	 * Short description. Shown on Special:Search results.
	 *
	 * @stable to override
	 *
	 * @param File $file
	 * @return string
	 */
	public function getShortDesc( $file ) {
		return self::getGeneralShortDesc( $file );
	}

	/**
	 * Long description. Shown under image on image description page surounded by ().
	 *
	 * @stable to override
	 *
	 * @param File $file
	 * @return string
	 */
	public function getLongDesc( $file ) {
		return self::getGeneralLongDesc( $file );
	}

	/**
	 * Used instead of getShortDesc if there is no handler registered for file.
	 *
	 * @param File $file
	 * @return string
	 */
	public static function getGeneralShortDesc( $file ) {
		global $wgLang;

		return htmlspecialchars( $wgLang->formatSize( $file->getSize() ) );
	}

	/**
	 * Used instead of getLongDesc if there is no handler registered for file.
	 *
	 * @param File $file
	 * @return string
	 */
	public static function getGeneralLongDesc( $file ) {
		return wfMessage( 'file-info' )->sizeParams( $file->getSize() )
			->params( '<span class="mime-type">' . $file->getMimeType() . '</span>' )->parse();
	}

	/**
	 * Calculate the largest thumbnail width for a given original file size
	 * such that the thumbnail's height is at most $maxHeight.
	 * @param int $boxWidth Width of the thumbnail box.
	 * @param int $boxHeight Height of the thumbnail box.
	 * @param int $maxHeight Maximum height expected for the thumbnail.
	 * @return int
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
	 * @stable to override
	 *
	 * @param File $file
	 * @return string Dimensions
	 */
	public function getDimensionsString( $file ) {
		return '';
	}

	/**
	 * Modify the parser object post-transform.
	 *
	 * This is often used to do $parser->addOutputHook(),
	 * in order to add some javascript to render a viewer.
	 * See TimedMediaHandler or OggHandler for an example.
	 *
	 * @stable to override
	 *
	 * @param Parser $parser
	 * @param File $file
	 */
	public function parserTransformHook( $parser, $file ) {
	}

	/**
	 * File validation hook called on upload.
	 *
	 * If the file at the given local path is not valid, or its MIME type does not
	 * match the handler class, a Status object should be returned containing
	 * relevant errors.
	 *
	 * @stable to override
	 *
	 * @param string $fileName The local path to the file.
	 * @return Status
	 */
	public function verifyUpload( $fileName ) {
		return Status::newGood();
	}

	/**
	 * Check for zero-sized thumbnails. These can be generated when
	 * no disk space is available or some other error occurs
	 *
	 * @stable to override
	 *
	 * @param string $dstPath The location of the suspect file
	 * @param int $retval Return value of some shell process, file will be deleted if this is non-zero
	 * @return bool True if removed, false otherwise
	 */
	public function removeBadFile( $dstPath, $retval = 0 ) {
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
	 * @stable to override
	 *
	 * @param array &$files
	 * @param array $options Purge options. Currently will always be
	 *  an array with a single key 'forThumbRefresh' set to true.
	 */
	public function filterThumbnailPurgeList( &$files, $options ) {
		// Do nothing
	}

	/**
	 * True if the handler can rotate the media
	 * @since 1.24 non-static. From 1.21-1.23 was static
	 * @stable to override
	 *
	 * @return bool
	 */
	public function canRotate() {
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
	 * @stable to override
	 *
	 * @param File $file
	 * @return int 0, 90, 180 or 270
	 */
	public function getRotation( $file ) {
		return 0;
	}

	/**
	 * Log an error that occurred in an external process
	 *
	 * Moved from BitmapHandler to MediaHandler with MediaWiki 1.23
	 *
	 * @since 1.23
	 * @param int $retval
	 * @param string $err Error reported by command. Anything longer than
	 * MediaHandler::MAX_ERR_LOG_SIZE is stripped off.
	 * @param string $cmd
	 */
	protected function logErrorForExternalProcess( $retval, $err, $cmd ) {
		# Keep error output limited (T59985)
		$errMessage = trim( substr( $err, 0, self::MAX_ERR_LOG_SIZE ) );

		wfDebugLog( 'thumbnail',
			sprintf( 'thumbnail failed on %s: error %d "%s" from "%s"',
					wfHostname(), $retval, $errMessage, $cmd ) );
	}

	/**
	 * Get list of languages file can be viewed in.
	 *
	 * @stable to override
	 *
	 * @param File $file
	 * @return string[] Array of language codes, or empty array if unsupported.
	 * @since 1.23
	 */
	public function getAvailableLanguages( File $file ) {
		return [];
	}

	/**
	 * When overridden in a descendant class, returns a language code most suiting
	 *
	 * @stable to override
	 *
	 * @since 1.32
	 *
	 * @param string $userPreferredLanguage Language code requesed
	 * @param string[] $availableLanguages Languages present in the file
	 * @return string|null Language code picked or null if not supported/available
	 */
	public function getMatchedLanguage( $userPreferredLanguage, array $availableLanguages ) {
		return null;
	}

	/**
	 * On file types that support renderings in multiple languages,
	 * which language is used by default if unspecified.
	 *
	 * If getAvailableLanguages returns a non-empty array, this must return
	 * a valid language code. Otherwise can return null if files of this
	 * type do not support alternative language renderings.
	 *
	 * @stable to override
	 *
	 * @param File $file
	 * @return string|null Language code or null if multi-language not supported for filetype.
	 * @since 1.23
	 */
	public function getDefaultRenderLanguage( File $file ) {
		return null;
	}

	/**
	 * If its an audio file, return the length of the file. Otherwise 0.
	 *
	 * File::getLength() existed for a long time, but was calling a method
	 * that only existed in some subclasses of this class (The TMH ones).
	 *
	 * @stable to override
	 *
	 * @param File $file
	 * @return float Length in seconds
	 * @since 1.23
	 */
	public function getLength( $file ) {
		return 0.0;
	}

	/**
	 * True if creating thumbnails from the file is large or otherwise resource-intensive.
	 * @stable to override
	 *
	 * @param File $file
	 * @return bool
	 */
	public function isExpensiveToThumbnail( $file ) {
		return false;
	}

	/**
	 * Returns whether or not this handler supports the chained generation of thumbnails according
	 * to buckets
	 * @stable to override
	 *
	 * @return bool
	 * @since 1.24
	 */
	public function supportsBucketing() {
		return false;
	}

	/**
	 * Returns a normalised params array for which parameters have been cleaned up for bucketing
	 * purposes
	 * @stable to override
	 *
	 * @param array $params
	 * @return array
	 */
	public function sanitizeParamsForBucketing( $params ) {
		return $params;
	}

	/**
	 * Gets configuration for the file warning message. Return value of
	 * the following structure:
	 *   [
	 *     // Required, module with messages loaded for the client
	 *     'module' => 'example.filewarning.messages',
	 *     // Required, array of names of messages
	 *     'messages' => [
	 *       // Required, main warning message
	 *       'main' => 'example-filewarning-main',
	 *       // Optional, header for warning dialog
	 *       'header' => 'example-filewarning-header',
	 *       // Optional, footer for warning dialog
	 *       'footer' => 'example-filewarning-footer',
	 *       // Optional, text for more-information link (see below)
	 *       'info' => 'example-filewarning-info',
	 *     ],
	 *     // Optional, link for more information
	 *     'link' => 'http://example.com',
	 *   ]
	 *
	 * Returns null if no warning is necessary.
	 * @stable to override
	 * @param File $file
	 * @return array|null
	 */
	public function getWarningConfig( $file ) {
		return null;
	}

	/**
	 * Converts a dimensions array about a potentially multipage document from an
	 * exhaustive list of ordered page numbers to a list of page ranges
	 * @param array[] $pagesByDimensions
	 * @return string
	 * @since 1.30
	 */
	public static function getPageRangesByDimensions( $pagesByDimensions ) {
		$pageRangesByDimensions = [];

		foreach ( $pagesByDimensions as $dimensions => $pageList ) {
			$ranges = [];
			$firstPage = $pageList[0];
			$lastPage = $firstPage - 1;

			foreach ( $pageList as $page ) {
				if ( $page > $lastPage + 1 ) {
					if ( $firstPage != $lastPage ) {
						$ranges[] = "$firstPage-$lastPage";
					} else {
						$ranges[] = "$firstPage";
					}

					$firstPage = $page;
				}

				$lastPage = $page;
			}

			if ( $firstPage != $lastPage ) {
				$ranges[] = "$firstPage-$lastPage";
			} else {
				$ranges[] = "$firstPage";
			}

			$pageRangesByDimensions[ $dimensions ] = $ranges;
		}

		$dimensionsString = [];
		foreach ( $pageRangesByDimensions as $dimensions => $pageRanges ) {
			$dimensionsString[] = "$dimensions:" . implode( ',', $pageRanges );
		}

		return implode( '/', $dimensionsString );
	}

	/**
	 * Get useful response headers for GET/HEAD requests for a file with the given metadata
	 * @stable to override
	 *
	 * @param array $metadata Contains this handler's unserialized getMetadata() for a file
	 * @return array
	 * @since 1.30
	 */
	public function getContentHeaders( $metadata ) {
		return [ 'X-Content-Dimensions' => '' ]; // T175689
	}
}
