<?php
/**
 *
 *
 * Created on July 6, 2007
 *
 * Copyright © 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiQueryBase.php' );
}

/**
 * A query action to get image information and upload history.
 *
 * @ingroup API
 */
class ApiQueryImageInfo extends ApiQueryBase {

	public function __construct( $query, $moduleName, $prefix = 'ii' ) {
		// We allow a subclass to override the prefix, to create a related API module.
		// Some other parts of MediaWiki construct this with a null $prefix, which used to be ignored when this only took two arguments
		if ( is_null( $prefix ) ) {
			$prefix = 'ii';
		}
		parent::__construct( $query, $moduleName, $prefix );
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$prop = array_flip( $params['prop'] );

		$scale = $this->getScale( $params );

		$pageIds = $this->getPageSet()->getAllTitlesByNamespace();
		if ( !empty( $pageIds[NS_FILE] ) ) {
			$titles = array_keys( $pageIds[NS_FILE] );
			asort( $titles ); // Ensure the order is always the same

			$skip = false;
			if ( !is_null( $params['continue'] ) ) {
				$skip = true;
				$cont = explode( '|', $params['continue'] );
				if ( count( $cont ) != 2 ) {
					$this->dieUsage( 'Invalid continue param. You should pass the original ' .
							'value returned by the previous query', '_badcontinue' );
				}
				$fromTitle = strval( $cont[0] );
				$fromTimestamp = $cont[1];
				// Filter out any titles before $fromTitle
				foreach ( $titles as $key => $title ) {
					if ( $title < $fromTitle ) {
						unset( $titles[$key] );
					} else {
						break;
					}
				}
			}

			$result = $this->getResult();
			$images = RepoGroup::singleton()->findFiles( $titles );
			foreach ( $images as $img ) {
				// Skip redirects
				if ( $img->getOriginalTitle()->isRedirect() ) {
					continue;
				}

				$start = $skip ? $fromTimestamp : $params['start'];
				$pageId = $pageIds[NS_IMAGE][ $img->getOriginalTitle()->getDBkey() ];

				$fit = $result->addValue(
					array( 'query', 'pages', intval( $pageId ) ),
					'imagerepository', $img->getRepoName()
				);
				if ( !$fit ) {
					if ( count( $pageIds[NS_IMAGE] ) == 1 ) {
						// The user is screwed. imageinfo can't be solely
						// responsible for exceeding the limit in this case,
						// so set a query-continue that just returns the same
						// thing again. When the violating queries have been
						// out-continued, the result will get through
						$this->setContinueEnumParameter( 'start',
							wfTimestamp( TS_ISO_8601, $img->getTimestamp() ) );
					} else {
						$this->setContinueEnumParameter( 'continue',
							$this->getContinueStr( $img ) );
					}
					break;
				}

				// Check if we can make the requested thumbnail, and get transform parameters.
				$finalThumbParams = $this->mergeThumbParams( $img, $scale, $params['urlparam'] );

				// Get information about the current version first
				// Check that the current version is within the start-end boundaries
				$gotOne = false;
				if (
					( is_null( $start ) || $img->getTimestamp() <= $start ) &&
					( is_null( $params['end'] ) || $img->getTimestamp() >= $params['end'] )
				) {
					$gotOne = true;

					$fit = $this->addPageSubItem( $pageId,
						self::getInfo( $img, $prop, $result,
							$finalThumbParams, $params['metadataversion'] ) );
					if ( !$fit ) {
						if ( count( $pageIds[NS_IMAGE] ) == 1 ) {
							// See the 'the user is screwed' comment above
							$this->setContinueEnumParameter( 'start',
								wfTimestamp( TS_ISO_8601, $img->getTimestamp() ) );
						} else {
							$this->setContinueEnumParameter( 'continue',
								$this->getContinueStr( $img ) );
						}
						break;
					}
				}

				// Now get the old revisions
				// Get one more to facilitate query-continue functionality
				$count = ( $gotOne ? 1 : 0 );
				$oldies = $img->getHistory( $params['limit'] - $count + 1, $start, $params['end'] );
				foreach ( $oldies as $oldie ) {
					if ( ++$count > $params['limit'] ) {
						// We've reached the extra one which shows that there are additional pages to be had. Stop here...
						// Only set a query-continue if there was only one title
						if ( count( $pageIds[NS_FILE] ) == 1 ) {
							$this->setContinueEnumParameter( 'start',
								wfTimestamp( TS_ISO_8601, $oldie->getTimestamp() ) );
						}
						break;
					}
					$fit = $this->addPageSubItem( $pageId,
						self::getInfo( $oldie, $prop, $result,
							$finalThumbParams, $params['metadataversion'] ) );
					if ( !$fit ) {
						if ( count( $pageIds[NS_IMAGE] ) == 1 ) {
							$this->setContinueEnumParameter( 'start',
								wfTimestamp( TS_ISO_8601, $oldie->getTimestamp() ) );
						} else {
							$this->setContinueEnumParameter( 'continue',
								$this->getContinueStr( $oldie ) );
						}
						break;
					}
				}
				if ( !$fit ) {
					break;
				}
				$skip = false;
			}

			$data = $this->getResultData();
			foreach ( $data['query']['pages'] as $pageid => $arr ) {
				if ( !isset( $arr['imagerepository'] ) ) {
					$result->addValue(
						array( 'query', 'pages', $pageid ),
						'imagerepository', ''
					);
				}
				// The above can't fail because it doesn't increase the result size
			}
		}
	}

	/**
	 * From parameters, construct a 'scale' array
	 * @param $params Array: Parameters passed to api.
	 * @return Array or Null: key-val array of 'width' and 'height', or null
	 */
	public function getScale( $params ) {
		$p = $this->getModulePrefix();

		// Height and width.
		if ( $params['urlheight'] != -1 && $params['urlwidth'] == -1 ) {
			$this->dieUsage( "{$p}urlheight cannot be used without {$p}urlwidth", "{$p}urlwidth" );
		}

		if ( $params['urlwidth'] != -1 ) {
			$scale = array();
			$scale['width'] = $params['urlwidth'];
			$scale['height'] = $params['urlheight'];
		} else {
			$scale = null;
			if ( $params['urlparam'] ) {
				$this->dieUsage( "{$p}urlparam requires {$p}urlwidth", "urlparam_no_width" );
			}
			return $scale;
		}

		return $scale;
	}

	/** Validate and merge scale parameters with handler thumb parameters, give error if invalid.
	 *
	 * We do this later than getScale, since we need the image
	 * to know which handler, since handlers can make their own parameters.
	 * @param File $image Image that params are for.
	 * @param Array $thumbParams thumbnail parameters from getScale
	 * @param String $otherParams of otherParams (iiurlparam).
	 * @return Array of parameters for transform.
	 */
	protected function mergeThumbParams ( $image, $thumbParams, $otherParams ) {
		if ( !$otherParams ) {
			return $thumbParams;
		}
		$p = $this->getModulePrefix();

		$h = $image->getHandler();
		if ( !$h ) {
			$this->setWarning( 'Could not create thumbnail because ' .
				$image->getName() . ' does not have an associated image handler' );
			return $thumbParams;
		}

		$paramList = $h->parseParamString( $otherParams );
		if ( !$paramList ) {
			// Just set a warning (instead of dieUsage), as in many cases
			// we could still render the image using width and height parameters,
			// and this type of thing could happen between different versions of
			// handlers.
			$this->setWarning( "Could not parse {$p}urlparam for " . $image->getName()
				. '. Using only width and height' );
			return $thumbParams;
		}

		if ( isset( $paramList['width'] ) ) {
			if ( intval( $paramList['width'] ) != intval( $thumbParams['width'] ) ) {
				$this->dieUsage( "{$p}urlparam had width of {$paramList['width']} but "
					. "{$p}urlwidth was {$thumbParams['width']}", "urlparam_urlwidth_mismatch" );
			}
		}

		foreach ( $paramList as $name => $value ) {
			if ( !$h->validateParam( $name, $value ) ) {
				$this->dieUsage( "Invalid value for {$p}urlparam ($name=$value)", "urlparam" );
			}
		}

		return $thumbParams + $paramList;
	}

	/**
	 * Get result information for an image revision
	 *
	 * @param $file File object
	 * @param $prop Array of properties to get (in the keys)
	 * @param $result ApiResult object
	 * @param $thumbParams Array containing 'width' and 'height' items, or null
	 * @param $version string Version of image metadata (for things like jpeg which have different versions).
	 * @return Array: result array
	 */
	static function getInfo( $file, $prop, $result, $thumbParams = null, $version = 'latest' ) {
		$vals = array();
		// Timestamp is shown even if the file is revdelete'd in interface
		// so do same here.
		if ( isset( $prop['timestamp'] ) ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $file->getTimestamp() );
		}

		$user = isset( $prop['user'] );
		$userid = isset( $prop['userid'] );

		if ( $user || $userid ) {
			if ( $file->isDeleted( File::DELETED_USER ) ) {
				$vals['userhidden'] = '';
			} else {
				if ( $user ) {
					$vals['user'] = $file->getUser();
				}
				if ( $userid ) {
					$vals['userid'] = $file->getUser( 'id' );
				}
				if ( !$file->getUser( 'id' ) ) {
					$vals['anon'] = '';
				}
			}
		}

		// This is shown even if the file is revdelete'd in interface
		// so do same here.
		if ( isset( $prop['size'] ) || isset( $prop['dimensions'] ) ) {
			$vals['size'] = intval( $file->getSize() );
			$vals['width'] = intval( $file->getWidth() );
			$vals['height'] = intval( $file->getHeight() );

			$pageCount = $file->pageCount();
			if ( $pageCount !== false ) {
				$vals['pagecount'] = $pageCount;
			}
		}

		$pcomment = isset( $prop['parsedcomment'] );
		$comment = isset( $prop['comment'] );

		if ( $pcomment || $comment ) {
			if ( $file->isDeleted( File::DELETED_COMMENT ) ) {
				$vals['commenthidden'] = '';
			} else {
				if ( $pcomment ) {
					global $wgUser;
					$vals['parsedcomment'] = $wgUser->getSkin()->formatComment(
						$file->getDescription(), $file->getTitle() );
				}
				if ( $comment ) {
					$vals['comment'] = $file->getDescription();
				}
			}
		}

		$url = isset( $prop['url'] );
		$sha1 = isset( $prop['sha1'] );
		$meta = isset( $prop['metadata'] );
		$mime = isset( $prop['mime'] );
		$mediatype = isset( $prop['mediatype'] );
		$archive = isset( $prop['archivename'] );
		$bitdepth = isset( $prop['bitdepth'] );

		if ( ( $url || $sha1 || $meta || $mime || $mediatype || $archive || $bitdepth )
				&& $file->isDeleted( File::DELETED_FILE ) ) {
			$vals['filehidden'] = '';

			//Early return, tidier than indenting all following things one level
			return $vals;
		}

		if ( $url ) {
			if ( !is_null( $thumbParams ) ) {
				$mto = $file->transform( $thumbParams );
				if ( $mto && !$mto->isError() ) {
					$vals['thumburl'] = wfExpandUrl( $mto->getUrl() );

					// bug 23834 - If the URL's are the same, we haven't resized it, so shouldn't give the wanted
					// thumbnail sizes for the thumbnail actual size
					if ( $mto->getUrl() !== $file->getUrl() ) {
						$vals['thumbwidth'] = intval( $mto->getWidth() );
						$vals['thumbheight'] = intval( $mto->getHeight() );
					} else {
						$vals['thumbwidth'] = intval( $file->getWidth() );
						$vals['thumbheight'] = intval( $file->getHeight() );
					}

					if ( isset( $prop['thumbmime'] ) && $file->getHandler() ) {
						list( $ext, $mime ) = $file->getHandler()->getThumbType(
							substr( $mto->getPath(), strrpos( $mto->getPath(), '.' ) + 1 ),
							$file->getMimeType(), $thumbParams );
						$vals['thumbmime'] = $mime;
					}
				} elseif ( $mto && $mto->isError() ) {
					$vals['thumberror'] = $mto->toText();
				}
			}
			$vals['url'] = wfExpandUrl( $file->getFullURL() );
			$vals['descriptionurl'] = wfExpandUrl( $file->getDescriptionUrl() );
		}

		if ( $sha1 ) {
			$vals['sha1'] = wfBaseConvert( $file->getSha1(), 36, 16, 40 );
		}

		if ( $meta ) {
			$metadata = unserialize( $file->getMetadata() );
			if ( $version !== 'latest' ) {
				$metadata = $file->convertMetadataVersion( $metadata, $version );
			}
			$vals['metadata'] = $metadata ? self::processMetaData( $metadata, $result ) : null;
		}

		if ( $mime ) {
			$vals['mime'] = $file->getMimeType();
		}

		if ( $mediatype ) {
			$vals['mediatype'] = $file->getMediaType();
		}

		if ( $archive && $file->isOld() ) {
			$vals['archivename'] = $file->getArchiveName();
		}

		if ( $bitdepth ) {
			$vals['bitdepth'] = $file->getBitDepth();
		}

		return $vals;
	}

	/**
	 *
	 * @param $metadata Array
	 * @param $result ApiResult
	 * @return Array
	 */
	public static function processMetaData( $metadata, $result ) {
		$retval = array();
		if ( is_array( $metadata ) ) {
			foreach ( $metadata as $key => $value ) {
				$r = array( 'name' => $key );
				if ( is_array( $value ) ) {
					$r['value'] = self::processMetaData( $value, $result );
				} else {
					$r['value'] = $value;
				}
				$retval[] = $r;
			}
		}
		$result->setIndexedTagName( $retval, 'metadata' );
		return $retval;
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	/**
	 * @param $img File
	 * @return string
	 */
	private function getContinueStr( $img ) {
		return $img->getOriginalTitle()->getText() .
			'|' .  $img->getTimestamp();
	}

	public function getAllowedParams() {
		return array(
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'timestamp|user',
				ApiBase::PARAM_TYPE => self::getPropertyNames()
			),
			'limit' => array(
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_DFLT => 1,
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'start' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'end' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'urlwidth' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_DFLT => -1
			),
			'urlheight' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_DFLT => -1
			),
			'metadataversion' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_DFLT => '1',
			),
			'urlparam' => array(
				ApiBase::PARAM_DFLT => '',
				ApiBase::PARAM_TYPE => 'string',
			),
			'continue' => null,
		);
	}

	/**
	 * Returns all possible parameters to iiprop
	 *
	 * @param array $filter List of properties to filter out
	 *
	 * @return Array
	 */
	public static function getPropertyNames( $filter = array() ) {
		return array_diff( array_keys( self::getProperties() ), $filter );
	}

	/**
	 * Returns array key value pairs of properties and their descriptions
	 *
	 * @return array
	 */
	private static function getProperties() {
		return array(
			'timestamp' =>      ' timestamp     - Adds timestamp for the uploaded version',
			'user' =>           ' user          - Adds the user who uploaded the image version',
			'userid' =>         ' userid        - Add the user ID that uploaded the image version',
			'comment' =>        ' comment       - Comment on the version',
			'parsedcomment' =>  ' parsedcomment - Parse the comment on the version',
			'url' =>            ' url           - Gives URL to the image and the description page',
			'size' =>           ' size          - Adds the size of the image in bytes and the height, width and page count (if applicable)',
			'dimensions' =>     ' dimensions    - Alias for size', // For backwards compatibility with Allimages
			'sha1' =>           ' sha1          - Adds SHA-1 hash for the image',
			'mime' =>           ' mime          - Adds MIME type of the image',
			'thumbmime' =>      ' thumbmime     - Adds MIME type of the image thumbnail (requires url)',
			'mediatype' =>      ' mediatype     - Adds the media type of the image',
			'metadata' =>       ' metadata      - Lists EXIF metadata for the version of the image',
			'archivename' =>    ' archivename   - Adds the file name of the archive version for non-latest versions',
			'bitdepth' =>       ' bitdepth      - Adds the bit depth of the version',
		);
	}

	/**
	 * Returns the descriptions for the properties provided by getPropertyNames()
	 *
	 * @param array $filter List of properties to filter out
	 *
	 * @return array
	 */
	public static function getPropertyDescriptions( $filter = array() ) {
		return array_merge(
			array( 'What image information to get:' ),
			array_values( array_diff_key( self::getProperties(), array_flip( $filter ) ) )
		);
	}

	/**
	 * Return the API documentation for the parameters.
	 * @return Array parameter documentation.
	 */
	public function getParamDescription() {
		$p = $this->getModulePrefix();
		return array(
			'prop' => self::getPropertyDescriptions(),
			'urlwidth' => array( "If {$p}prop=url is set, a URL to an image scaled to this width will be returned.",
					    'Only the current version of the image can be scaled' ),
			'urlheight' => "Similar to {$p}urlwidth. Cannot be used without {$p}urlwidth",
			'urlparam' => array( "A handler specific parameter string. For example, pdf's ",
				"might use 'page15-100px'. {$p}urlwidth must be used and be consistent with {$p}urlparam" ),
			'limit' => 'How many image revisions to return',
			'start' => 'Timestamp to start listing from',
			'end' => 'Timestamp to stop listing at',
			'metadataversion' => array( "Version of metadata to use. if 'latest' is specified, use latest version.",
						"Defaults to '1' for backwards compatibility" ),
			'continue' => 'If the query response includes a continue value, use it here to get another page of results'
		);
	}

	public function getDescription() {
		return 'Returns image information and upload history';
	}

	public function getPossibleErrors() {
		$p = $this->getModulePrefix();
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => "{$p}urlwidth", 'info' => "{$p}urlheight cannot be used without {$p}urlwidth" ),
			array( 'code' => 'urlparam', 'info' => "Invalid value for {$p}urlparam" ),
			array( 'code' => 'urlparam_no_width', 'info' => "{$p}urlparam requires {$p}urlwidth" ),
			array( 'code' => 'urlparam_urlwidth_mismatch', 'info' => "The width set in {$p}urlparm doesnt't " .
				"match the one in {$p}urlwidth" ),
		) );
	}

	protected function getExamples() {
		return array(
			'api.php?action=query&titles=File:Albert%20Einstein%20Head.jpg&prop=imageinfo',
			'api.php?action=query&titles=File:Test.jpg&prop=imageinfo&iilimit=50&iiend=20071231235959&iiprop=timestamp|user|url',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
