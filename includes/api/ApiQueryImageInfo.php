<?php
/**
 *
 *
 * Created on July 6, 2007
 *
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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

/**
 * A query action to get image information and upload history.
 *
 * @ingroup API
 */
class ApiQueryImageInfo extends ApiQueryBase {
	const TRANSFORM_LIMIT = 50;
	private static $transformCount = 0;

	public function __construct( $query, $moduleName, $prefix = 'ii' ) {
		// We allow a subclass to override the prefix, to create a related API
		// module. Some other parts of MediaWiki construct this with a null
		// $prefix, which used to be ignored when this only took two arguments
		if ( is_null( $prefix ) ) {
			$prefix = 'ii';
		}
		parent::__construct( $query, $moduleName, $prefix );
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$prop = array_flip( $params['prop'] );

		$scale = $this->getScale( $params );

		$opts = array(
			'version' => $params['metadataversion'],
			'language' => $params['extmetadatalanguage'],
			'multilang' => $params['extmetadatamultilang'],
			'extmetadatafilter' => $params['extmetadatafilter'],
			'revdelUser' => $this->getUser(),
		);

		$pageIds = $this->getPageSet()->getAllTitlesByNamespace();
		if ( !empty( $pageIds[NS_FILE] ) ) {
			$titles = array_keys( $pageIds[NS_FILE] );
			asort( $titles ); // Ensure the order is always the same

			$fromTitle = null;
			if ( !is_null( $params['continue'] ) ) {
				$cont = explode( '|', $params['continue'] );
				$this->dieContinueUsageIf( count( $cont ) != 2 );
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

			$user = $this->getUser();
			$findTitles = array_map( function ( $title ) use ( $user ) {
				return array(
					'title' => $title,
					'private' => $user,
				);
			}, $titles );

			if ( $params['localonly'] ) {
				$images = RepoGroup::singleton()->getLocalRepo()->findFiles( $findTitles );
			} else {
				$images = RepoGroup::singleton()->findFiles( $findTitles );
			}

			$result = $this->getResult();
			foreach ( $titles as $title ) {
				$pageId = $pageIds[NS_FILE][$title];
				$start = $title === $fromTitle ? $fromTimestamp : $params['start'];

				if ( !isset( $images[$title] ) ) {
					if ( isset( $prop['uploadwarning'] ) ) {
						// Uploadwarning needs info about non-existing files
						$images[$title] = wfLocalFile( $title );
					} else {
						$result->addValue(
							array( 'query', 'pages', intval( $pageId ) ),
							'imagerepository', ''
						);
						// The above can't fail because it doesn't increase the result size
						continue;
					}
				}

				/** @var $img File */
				$img = $images[$title];

				if ( self::getTransformCount() >= self::TRANSFORM_LIMIT ) {
					if ( count( $pageIds[NS_FILE] ) == 1 ) {
						// See the 'the user is screwed' comment below
						$this->setContinueEnumParameter( 'start',
							$start !== null ? $start : wfTimestamp( TS_ISO_8601, $img->getTimestamp() )
						);
					} else {
						$this->setContinueEnumParameter( 'continue',
							$this->getContinueStr( $img, $start ) );
					}
					break;
				}

				$fit = $result->addValue(
					array( 'query', 'pages', intval( $pageId ) ),
					'imagerepository', $img->getRepoName()
				);
				if ( !$fit ) {
					if ( count( $pageIds[NS_FILE] ) == 1 ) {
						// The user is screwed. imageinfo can't be solely
						// responsible for exceeding the limit in this case,
						// so set a query-continue that just returns the same
						// thing again. When the violating queries have been
						// out-continued, the result will get through
						$this->setContinueEnumParameter( 'start',
							$start !== null ? $start : wfTimestamp( TS_ISO_8601, $img->getTimestamp() )
						);
					} else {
						$this->setContinueEnumParameter( 'continue',
							$this->getContinueStr( $img, $start ) );
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
							$finalThumbParams, $opts
						)
					);
					if ( !$fit ) {
						if ( count( $pageIds[NS_FILE] ) == 1 ) {
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
				/** @var $oldie File */
				foreach ( $oldies as $oldie ) {
					if ( ++$count > $params['limit'] ) {
						// We've reached the extra one which shows that there are
						// additional pages to be had. Stop here...
						// Only set a query-continue if there was only one title
						if ( count( $pageIds[NS_FILE] ) == 1 ) {
							$this->setContinueEnumParameter( 'start',
								wfTimestamp( TS_ISO_8601, $oldie->getTimestamp() ) );
						}
						break;
					}
					$fit = self::getTransformCount() < self::TRANSFORM_LIMIT &&
						$this->addPageSubItem( $pageId,
							self::getInfo( $oldie, $prop, $result,
								$finalThumbParams, $opts
							)
						);
					if ( !$fit ) {
						if ( count( $pageIds[NS_FILE] ) == 1 ) {
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
			}
		}
	}

	/**
	 * From parameters, construct a 'scale' array
	 * @param array $params Parameters passed to api.
	 * @return Array or Null: key-val array of 'width' and 'height', or null
	 */
	public function getScale( $params ) {
		$p = $this->getModulePrefix();

		if ( $params['urlwidth'] != -1 ) {
			$scale = array();
			$scale['width'] = $params['urlwidth'];
			$scale['height'] = $params['urlheight'];
		} elseif ( $params['urlheight'] != -1 ) {
			// Height is specified but width isn't
			// Don't set $scale['width']; this signals mergeThumbParams() to fill it with the image's width
			$scale = array();
			$scale['height'] = $params['urlheight'];
		} else {
			$scale = null;
			if ( $params['urlparam'] ) {
				$this->dieUsage( "{$p}urlparam requires {$p}urlwidth", "urlparam_no_width" );
			}
		}

		return $scale;
	}

	/** Validate and merge scale parameters with handler thumb parameters, give error if invalid.
	 *
	 * We do this later than getScale, since we need the image
	 * to know which handler, since handlers can make their own parameters.
	 * @param File $image Image that params are for.
	 * @param array $thumbParams thumbnail parameters from getScale
	 * @param string $otherParams of otherParams (iiurlparam).
	 * @return Array of parameters for transform.
	 */
	protected function mergeThumbParams( $image, $thumbParams, $otherParams ) {
		global $wgThumbLimits;

		if ( !isset( $thumbParams['width'] ) && isset( $thumbParams['height'] ) ) {
			// We want to limit only by height in this situation, so pass the
			// image's full width as the limiting width. But some file types
			// don't have a width of their own, so pick something arbitrary so
			// thumbnailing the default icon works.
			if ( $image->getWidth() <= 0 ) {
				$thumbParams['width'] = max( $wgThumbLimits );
			} else {
				$thumbParams['width'] = $image->getWidth();
			}
		}

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
				$this->setWarning( "Ignoring width value set in {$p}urlparam ({$paramList['width']}) "
					. "in favor of width value derived from {$p}urlwidth/{$p}urlheight "
					. "({$thumbParams['width']})" );
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
	 * @param array $prop of properties to get (in the keys)
	 * @param $result ApiResult object
	 * @param array $thumbParams containing 'width' and 'height' items, or null
	 * @param array|bool|string $opts Options for data fetching.
	 *   This is an array consisting of the keys:
	 *    'version': The metadata version for the metadata option
	 *    'language': The language for extmetadata property
	 *    'multilang': Return all translations in extmetadata property
	 *    'revdelUser': User to use when checking whether to show revision-deleted fields.
	 * @return Array: result array
	 */
	static function getInfo( $file, $prop, $result, $thumbParams = null, $opts = false ) {
		global $wgContLang;

		$anyHidden = false;

		if ( !$opts || is_string( $opts ) ) {
			$opts = array(
				'version' => $opts ?: 'latest',
				'language' => $wgContLang,
				'multilang' => false,
				'extmetadatafilter' => array(),
				'revdelUser' => null,
			);
		}
		$version = $opts['version'];
		$vals = array();
		// Timestamp is shown even if the file is revdelete'd in interface
		// so do same here.
		if ( isset( $prop['timestamp'] ) ) {
			$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $file->getTimestamp() );
		}

		// Handle external callers who don't pass revdelUser
		if ( isset( $opts['revdelUser'] ) && $opts['revdelUser'] ) {
			$revdelUser = $opts['revdelUser'];
			$canShowField = function ( $field ) use ( $file, $revdelUser ) {
				return $file->userCan( $field, $revdelUser );
			};
		} else {
			$canShowField = function ( $field ) use ( $file ) {
				return !$file->isDeleted( $field );
			};
		}

		$user = isset( $prop['user'] );
		$userid = isset( $prop['userid'] );

		if ( $user || $userid ) {
			if ( $file->isDeleted( File::DELETED_USER ) ) {
				$vals['userhidden'] = '';
				$anyHidden = true;
			}
			if ( $canShowField( File::DELETED_USER ) ) {
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
				$anyHidden = true;
			}
			if ( $canShowField( File::DELETED_COMMENT ) ) {
				if ( $pcomment ) {
					$vals['parsedcomment'] = Linker::formatComment(
						$file->getDescription( File::RAW ), $file->getTitle() );
				}
				if ( $comment ) {
					$vals['comment'] = $file->getDescription( File::RAW );
				}
			}
		}

		$canonicaltitle = isset( $prop['canonicaltitle'] );
		$url = isset( $prop['url'] );
		$sha1 = isset( $prop['sha1'] );
		$meta = isset( $prop['metadata'] );
		$extmetadata = isset( $prop['extmetadata'] );
		$commonmeta = isset( $prop['commonmetadata'] );
		$mime = isset( $prop['mime'] );
		$mediatype = isset( $prop['mediatype'] );
		$archive = isset( $prop['archivename'] );
		$bitdepth = isset( $prop['bitdepth'] );
		$uploadwarning = isset( $prop['uploadwarning'] );

		if ( $uploadwarning ) {
			$vals['html'] = SpecialUpload::getExistsWarning( UploadBase::getExistsWarning( $file ) );
		}

		if ( $file->isDeleted( File::DELETED_FILE ) ) {
			$vals['filehidden'] = '';
			$anyHidden = true;
		}

		if ( $anyHidden && $file->isDeleted( File::DELETED_RESTRICTED ) ) {
			$vals['suppressed'] = true;
		}

		if ( !$canShowField( File::DELETED_FILE ) ) {
			//Early return, tidier than indenting all following things one level
			return $vals;
		}

		if ( $canonicaltitle ) {
			$vals['canonicaltitle'] = $file->getTitle()->getPrefixedText();
		}

		if ( $url ) {
			if ( !is_null( $thumbParams ) ) {
				$mto = $file->transform( $thumbParams );
				self::$transformCount++;
				if ( $mto && !$mto->isError() ) {
					$vals['thumburl'] = wfExpandUrl( $mto->getUrl(), PROTO_CURRENT );

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
						list( , $mime ) = $file->getHandler()->getThumbType(
							$mto->getExtension(), $file->getMimeType(), $thumbParams );
						$vals['thumbmime'] = $mime;
					}
				} elseif ( $mto && $mto->isError() ) {
					$vals['thumberror'] = $mto->toText();
				}
			}
			$vals['url'] = wfExpandUrl( $file->getFullURL(), PROTO_CURRENT );
			$vals['descriptionurl'] = wfExpandUrl( $file->getDescriptionUrl(), PROTO_CURRENT );
		}

		if ( $sha1 ) {
			$vals['sha1'] = wfBaseConvert( $file->getSha1(), 36, 16, 40 );
		}

		if ( $meta ) {
			wfSuppressWarnings();
			$metadata = unserialize( $file->getMetadata() );
			wfRestoreWarnings();
			if ( $metadata && $version !== 'latest' ) {
				$metadata = $file->convertMetadataVersion( $metadata, $version );
			}
			$vals['metadata'] = $metadata ? self::processMetaData( $metadata, $result ) : null;
		}
		if ( $commonmeta ) {
			$metaArray = $file->getCommonMetaArray();
			$vals['commonmetadata'] = $metaArray ? self::processMetaData( $metaArray, $result ) : array();
		}

		if ( $extmetadata ) {
			// Note, this should return an array where all the keys
			// start with a letter, and all the values are strings.
			// Thus there should be no issue with format=xml.
			$format = new FormatMetadata;
			$format->setSingleLanguage( !$opts['multilang'] );
			$format->getContext()->setLanguage( $opts['language'] );
			$extmetaArray = $format->fetchExtendedMetadata( $file );
			if ( $opts['extmetadatafilter'] ) {
				$extmetaArray = array_intersect_key(
					$extmetaArray, array_flip( $opts['extmetadatafilter'] )
				);
			}
			$vals['extmetadata'] = $extmetaArray;
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
	 * Get the count of image transformations performed
	 *
	 * If this is >= TRANSFORM_LIMIT, you should probably stop processing images.
	 *
	 * @return integer count
	 */
	static function getTransformCount() {
		return self::$transformCount;
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
		if ( $this->userCanSeeRevDel() ) {
			return 'private';
		}

		return 'public';
	}

	/**
	 * @param $img File
	 * @param null|string $start
	 * @return string
	 */
	protected function getContinueStr( $img, $start = null ) {
		if ( $start === null ) {
			$start = $img->getTimestamp();
		}

		return $img->getOriginalTitle()->getDBkey() . '|' . $start;
	}

	public function getAllowedParams() {
		global $wgContLang;

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
			'extmetadatalanguage' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_DFLT => $wgContLang->getCode(),
			),
			'extmetadatamultilang' => array(
				ApiBase::PARAM_TYPE => 'boolean',
				ApiBase::PARAM_DFLT => false,
			),
			'extmetadatafilter' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_ISMULTI => true,
			),
			'urlparam' => array(
				ApiBase::PARAM_DFLT => '',
				ApiBase::PARAM_TYPE => 'string',
			),
			'continue' => null,
			'localonly' => false,
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
	 * @param string $modulePrefix
	 * @return array
	 */
	private static function getProperties( $modulePrefix = '' ) {
		return array(
			'timestamp' =>      ' timestamp     - Adds timestamp for the uploaded version',
			'user' =>           ' user          - Adds the user who uploaded the image version',
			'userid' =>         ' userid        - Add the user ID that uploaded the image version',
			'comment' =>        ' comment       - Comment on the version',
			'parsedcomment' =>  ' parsedcomment - Parse the comment on the version',
			'canonicaltitle' => ' canonicaltitle - Adds the canonical title of the image file',
			'url' =>            ' url           - Gives URL to the image and the description page',
			'size' =>           ' size          - Adds the size of the image in bytes ' .
				'and the height, width and page count (if applicable)',
			'dimensions' =>     ' dimensions    - Alias for size', // B/C with Allimages
			'sha1' =>           ' sha1          - Adds SHA-1 hash for the image',
			'mime' =>           ' mime          - Adds MIME type of the image',
			'thumbmime' =>      ' thumbmime     - Adds MIME type of the image thumbnail' .
				' (requires url and param ' . $modulePrefix . 'urlwidth)',
			'mediatype' =>      ' mediatype     - Adds the media type of the image',
			'metadata' =>       ' metadata      - Lists Exif metadata for the version of the image',
			'commonmetadata' => ' commonmetadata - Lists file format generic metadata ' .
				'for the version of the image',
			'extmetadata' =>    ' extmetadata   - Lists formatted metadata combined ' .
				'from multiple sources. Results are HTML formatted.',
			'archivename' =>    ' archivename   - Adds the file name of the archive ' .
				'version for non-latest versions',
			'bitdepth' =>       ' bitdepth      - Adds the bit depth of the version',
			'uploadwarning' =>  ' uploadwarning - Used by the Special:Upload page to ' .
				'get information about an existing file. Not intended for use outside MediaWiki core',
		);
	}

	/**
	 * Returns the descriptions for the properties provided by getPropertyNames()
	 *
	 * @param array $filter List of properties to filter out
	 * @param string $modulePrefix
	 * @return array
	 */
	public static function getPropertyDescriptions( $filter = array(), $modulePrefix = '' ) {
		return array_merge(
			array( 'What image information to get:' ),
			array_values( array_diff_key( self::getProperties( $modulePrefix ), array_flip( $filter ) ) )
		);
	}

	/**
	 * Return the API documentation for the parameters.
	 * @return Array parameter documentation.
	 */
	public function getParamDescription() {
		$p = $this->getModulePrefix();

		return array(
			'prop' => self::getPropertyDescriptions( array(), $p ),
			'urlwidth' => array(
				"If {$p}prop=url is set, a URL to an image scaled to this width will be returned.",
				'For performance reasons if this option is used, ' .
					'no more than ' . self::TRANSFORM_LIMIT . ' scaled images will be returned.'
			),
			'urlheight' => "Similar to {$p}urlwidth.",
			'urlparam' => array( "A handler specific parameter string. For example, pdf's ",
				"might use 'page15-100px'. {$p}urlwidth must be used and be consistent with {$p}urlparam" ),
			'limit' => 'How many image revisions to return per image',
			'start' => 'Timestamp to start listing from',
			'end' => 'Timestamp to stop listing at',
			'metadataversion'
				=> array( "Version of metadata to use. if 'latest' is specified, use latest version.",
				"Defaults to '1' for backwards compatibility" ),
			'extmetadatalanguage' => array(
				'What language to fetch extmetadata in. This affects both which',
				'translation to fetch, if multiple are available, as well as how things',
				'like numbers and various values are formatted.'
			),
			'extmetadatamultilang'
				=>'If translations for extmetadata property are available, fetch all of them.',
			'extmetadatafilter'
				=> "If specified and non-empty, only these keys will be returned for {$p}prop=extmetadata",
			'continue' => 'If the query response includes a continue value, ' .
				'use it here to get another page of results',
			'localonly' => 'Look only for files in the local repository',
		);
	}

	public static function getResultPropertiesFiltered( $filter = array() ) {
		$props = array(
			'timestamp' => array(
				'timestamp' => 'timestamp'
			),
			'user' => array(
				'userhidden' => 'boolean',
				'user' => 'string',
				'anon' => 'boolean'
			),
			'userid' => array(
				'userhidden' => 'boolean',
				'userid' => 'integer',
				'anon' => 'boolean'
			),
			'size' => array(
				'size' => 'integer',
				'width' => 'integer',
				'height' => 'integer',
				'pagecount' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'dimensions' => array(
				'size' => 'integer',
				'width' => 'integer',
				'height' => 'integer',
				'pagecount' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'comment' => array(
				'commenthidden' => 'boolean',
				'comment' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'parsedcomment' => array(
				'commenthidden' => 'boolean',
				'parsedcomment' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'canonicaltitle' => array(
				'canonicaltitle' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'url' => array(
				'filehidden' => 'boolean',
				'thumburl' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'thumbwidth' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				),
				'thumbheight' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				),
				'thumberror' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'url' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				),
				'descriptionurl' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'sha1' => array(
				'filehidden' => 'boolean',
				'sha1' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'mime' => array(
				'filehidden' => 'boolean',
				'mime' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'thumbmime' => array(
				'filehidden' => 'boolean',
				'thumbmime' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'mediatype' => array(
				'filehidden' => 'boolean',
				'mediatype' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'archivename' => array(
				'filehidden' => 'boolean',
				'archivename' => array(
					ApiBase::PROP_TYPE => 'string',
					ApiBase::PROP_NULLABLE => true
				)
			),
			'bitdepth' => array(
				'filehidden' => 'boolean',
				'bitdepth' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				)
			),
		);

		return array_diff_key( $props, array_flip( $filter ) );
	}

	public function getResultProperties() {
		return self::getResultPropertiesFiltered();
	}

	public function getDescription() {
		return 'Returns image information and upload history.';
	}

	public function getPossibleErrors() {
		$p = $this->getModulePrefix();

		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => "{$p}urlwidth", 'info' => "{$p}urlheight cannot be used without {$p}urlwidth" ),
			array( 'code' => 'urlparam', 'info' => "Invalid value for {$p}urlparam" ),
			array( 'code' => 'urlparam_no_width', 'info' => "{$p}urlparam requires {$p}urlwidth" ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&titles=File:Albert%20Einstein%20Head.jpg&prop=imageinfo',
			'api.php?action=query&titles=File:Test.jpg&prop=imageinfo&iilimit=50&' .
				'iiend=20071231235959&iiprop=timestamp|user|url',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Properties#imageinfo_.2F_ii';
	}
}
