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

		$thumbParams = $this->makeThumbParams( $params );

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
				
				// Check if we can make the requested thumbnail
				$this->validateThumbParams( $img, $thumbParams );

				// Get information about the current version first
				// Check that the current version is within the start-end boundaries
				$gotOne = false;
				if (
					( is_null( $start ) || $img->getTimestamp() <= $start ) &&
					( is_null( $params['end'] ) || $img->getTimestamp() >= $params['end'] )
				)
				{
					$gotOne = true;
					
					$fit = $this->addPageSubItem( $pageId,
						self::getInfo( $img, $prop, $result, $thumbParams ) );
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
						self::getInfo( $oldie, $prop, $result, $thumbParams ) );
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
	 * @param $params Array:
	 * @return Array or Null: key-val array of 'width' and 'height', or null
	 */
	public function getScale( $params ) {
		wfDeprecated( __METHOD__ );
		if ( !isset( $params['urlparam'] ) ) {
			// In case there are subclasses that
			// don't have this param set to anything.
			$params['urlparam'] = null;
		}
		return $this->makeThumbParams( $params );
	}

	/* Take parameters for transforming thumbnail, validate and turn into array.
	 * @param $params Array: Parameters from the request.
	 * @return Array or null: If array, suitable to passing to $file->transform.
	 */
	public function makeThumbParams( $params ) {
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

		// Other parameters.
		if ( is_array( $params['urlparam'] ) ) {
			foreach( $params['urlparam'] as $item ) {
				$parameter = explode( '=', $item, 2 );

				if ( count( $parameter ) !== 2
					|| $parameter[0] === 'width'
					|| $parameter[0] === 'height'
				) {
					$this->dieUsage( "Invalid value for {$p}urlparam ($item)", "urlparam" );
				}
				$scale[$parameter[0]] = $parameter[1];
			}
		}
		return $scale;
	}

	/** Validate the thumb parameters, give error if invalid.
	 *
	 * We do this later than makeThumbParams, since we need the image
	 * to know which handler, since handlers can make their own parameters.
	 * @param File $image Image that params are for.
	 * @param Array $thumbParams thumbnail parameters
	 */
	protected function validateThumbParams ( $image, $thumbParams ) {
		if ( !$thumbParams ) return;
		$p = $this->getModulePrefix();

		$h = $image->getHandler();
		if ( !$h ) {
			$this->setWarning( 'Could not create thumbnail because ' . 
				$image->getName() . ' does not have an associated image handler' );
			return;
		}
		foreach ( $thumbParams as $name => $value ) {
			if ( !$h->validateParam( $name, $value ) ) {
				/* This doesn't work well with height=-1 placeholder */
				if ( $name === 'height' ) continue;
				$this->dieUsage( "Invalid value for {$p}urlparam ($name=$value)", "urlparam" );
			}
		}
		// This could also potentially check normaliseParams as well, However that seems
		// to fall more into a thumbnail rendering error than a user input error, and
		// will be checked by the transform functions.
	}

	/**
	 * Get result information for an image revision
	 *
	 * @param $file File object
	 * @param $prop Array of properties to get (in the keys)
	 * @param $result ApiResult object
	 * @param $thumbParams Array containing 'width' and 'height' items, or null
	 * @return Array: result array
	 */
	static function getInfo( $file, $prop, $result, $thumbParams = null ) {
		$vals = array();
		if ( isset( $prop['timestamp'] ) ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $file->getTimestamp() );
		}
		if ( isset( $prop['user'] ) || isset( $prop['userid'] ) ) {

			if ( isset( $prop['user'] ) ) {
				$vals['user'] = $file->getUser();
			}
			if ( isset( $prop['userid'] ) ) {
				$vals['userid'] = $file->getUser( 'id' );
			}
			if ( !$file->getUser( 'id' ) ) {
				$vals['anon'] = '';
			}
		}
		if ( isset( $prop['size'] ) || isset( $prop['dimensions'] ) ) {
			$vals['size'] = intval( $file->getSize() );
			$vals['width'] = intval( $file->getWidth() );
			$vals['height'] = intval( $file->getHeight() );

			$pageCount = $file->pageCount();
			if ( $pageCount !== false ) {
				$vals['pagecount'] = $pageCount;
			}
		}
		if ( isset( $prop['url'] ) ) {
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

					if ( isset( $prop['thumbmime'] ) ) {
						$thumbFile = UnregisteredLocalFile::newFromPath( $mto->getPath(), false );
						$vals['thumbmime'] = $thumbFile->getMimeType();
					}
				}
				if ( $mto && $mto->isError() ) {
					$this->setWarning( 'Error creating thumbnail for ' . $file->getName . 
						': ' . $mto->toText() );
				}
			}
			$vals['url'] = $file->getFullURL();
			$vals['descriptionurl'] = wfExpandUrl( $file->getDescriptionUrl() );
		}
		if ( isset( $prop['comment'] ) ) {
			$vals['comment'] = $file->getDescription();
		}
		if ( isset( $prop['parsedcomment'] ) ) {
			global $wgUser;
			$vals['parsedcomment'] = $wgUser->getSkin()->formatComment(
					$file->getDescription(), $file->getTitle() );
		}

		if ( isset( $prop['sha1'] ) ) {
			$vals['sha1'] = wfBaseConvert( $file->getSha1(), 36, 16, 40 );
		}
		if ( isset( $prop['metadata'] ) ) {
			$metadata = $file->getMetadata();
			$vals['metadata'] = $metadata ? self::processMetaData( unserialize( $metadata ), $result ) : null;
		}
		if ( isset( $prop['mime'] ) ) {
			$vals['mime'] = $file->getMimeType();
		}

		if ( isset( $prop['archivename'] ) && $file->isOld() ) {
			$vals['archivename'] = $file->getArchiveName();
		}

		if ( isset( $prop['bitdepth'] ) ) {
			$vals['bitdepth'] = $file->getBitDepth();
		}

		return $vals;
	}

	/*
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
			'urlparam' => array(
				ApiBase::PARAM_ISMULTI => true,
			),
			'continue' => null,
		);
	}

	/**
	 * Returns all possible parameters to iiprop
	 * @static
	 * @return Array
	 */
	public static function getPropertyNames() {
		return array(
			'timestamp',
			'user',
			'userid',
			'comment',
			'parsedcomment',
			'url',
			'size',
			'dimensions', // For backwards compatibility with Allimages
			'sha1',
			'mime',
			'thumbmime',
			'metadata',
			'archivename',
			'bitdepth',
		);
	}

	/**
	 * Returns the descriptions for the properties provided by getPropertyNames()
	 *
	 * @static
	 * @return array
	 */
	public static function getPropertyDescriptions() {
		return array(
				'What image information to get:',
				' timestamp     - Adds timestamp for the uploaded version',
				' user          - Adds the user who uploaded the image version',
				' userid        - Add the user ID that uploaded the image version',
				' comment       - Comment on the version',
				' parsedcomment - Parse the comment on the version',
				' url           - Gives URL to the image and the description page',
				' size          - Adds the size of the image in bytes and the height and width',
				' dimensions    - Alias for size',
				' sha1          - Adds SHA-1 hash for the image',
				' mime          - Adds MIME type of the image',
				' thumbmime     - Adds MIME type of the image thumbnail (requires url)',
				' metadata      - Lists EXIF metadata for the version of the image',
				' archivename   - Adds the file name of the archive version for non-latest versions',
				' bitdepth      - Adds the bit depth of the version',
			);
	}

	/**
	 * Return the API documentation for the parameters.
	 * @return {Array} parameter documentation.
	 */
	public function getParamDescription() {
		$p = $this->getModulePrefix();
		return array(
			'prop' => self::getPropertyDescriptions(),
			'urlwidth' => array( "If {$p}prop=url is set, a URL to an image scaled to this width will be returned.",
					    'Only the current version of the image can be scaled' ),
			'urlheight' => "Similar to {$p}urlwidth. Cannot be used without {$p}urlwidth",
			'urlparam' => array( "Other rending parameters, such as page=2 for multipaged documents.",
					"Multiple parameters should be seperated with a |. {$p}urlwidth must also be used"),
			'limit' => 'How many image revisions to return',
			'start' => 'Timestamp to start listing from',
			'end' => 'Timestamp to stop listing at',
			'continue' => 'If the query response includes a continue value, use it here to get another page of results'
		);
	}

	public function getDescription() {
		return 'Returns image information and upload history';
	}

	public function getPossibleErrors() {
		$p = $this->getModulePrefix();
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'iiurlwidth', 'info' => 'iiurlheight cannot be used without iiurlwidth' ),
			array( 'code' => 'urlparam', 'info' => "Invalid value for {$p}urlparam" ),
			array( 'code' => 'urlparam_no_width', 'info' => "iiurlparam requires {$p}urlwidth" ),
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
