<?php

/*
 * Created on July 6, 2007
 *
 * API for MediaWiki 1.8+
 *
 * Copyright (C) 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 */

if (!defined('MEDIAWIKI')) {
	// Eclipse helper - will be ignored in production
	require_once ('ApiQueryBase.php');
}

/**
 * A query action to get image information and upload history.
 *
 * @ingroup API
 */
class ApiQueryImageInfo extends ApiQueryBase {

	public function __construct($query, $moduleName) {
		parent :: __construct($query, $moduleName, 'ii');
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$prop = array_flip($params['prop']);

		if($params['urlheight'] != -1 && $params['urlwidth'] == -1)
			$this->dieUsage("iiurlheight cannot be used without iiurlwidth", 'iiurlwidth');
		
		if ( $params['urlwidth'] != -1 ) {
			$scale = array();
			$scale['width'] = $params['urlwidth'];
			$scale['height'] = $params['urlheight'];
		} else {
			$scale = null;
		}

		$pageIds = $this->getPageSet()->getAllTitlesByNamespace();
		if ( !empty( $pageIds[NS_FILE] ) ) {
			$titles = array_keys($pageIds[NS_FILE]);
			asort($titles); // Ensure the order is always the same

			$skip = false;
			if(!is_null($params['continue']))
			{
				$skip = true;
				$cont = explode('|', $params['continue']);
				if(count($cont) != 2)
					$this->dieUsage("Invalid continue param. You should pass the original " .
							"value returned by the previous query", "_badcontinue");
				$fromTitle = strval($cont[0]);
				$fromTimestamp = $cont[1];
				// Filter out any titles before $fromTitle
				foreach($titles as $key => $title)
					if($title < $fromTitle)
						unset($titles[$key]);
					else
						break;
			}

			$result = $this->getResult();
			$images = RepoGroup::singleton()->findFiles( $titles );
			foreach ( $images as $img ) {
				// Skip redirects
				if($img->getOriginalTitle()->isRedirect())
					continue;
				
				$start = $skip ? $fromTimestamp : $params['start'];
				$pageId = $pageIds[NS_IMAGE][ $img->getOriginalTitle()->getDBkey() ];

				$fit = $result->addValue(
					array('query', 'pages', intval($pageId)),
					'imagerepository', $img->getRepoName()
				);
				if(!$fit)
				{
					if(count($pageIds[NS_IMAGE]) == 1)
						# The user is screwed. imageinfo can't be solely
						# responsible for exceeding the limit in this case,
						# so set a query-continue that just returns the same
						# thing again. When the violating queries have been
						# out-continued, the result will get through
						$this->setContinueEnumParameter('start',
							wfTimestamp(TS_ISO_8601, $img->getTimestamp()));
					else
						$this->setContinueEnumParameter('continue',
							$this->getContinueStr($img));
					break;
				}

				// Get information about the current version first
				// Check that the current version is within the start-end boundaries
				$gotOne = false;
				if((is_null($start) || $img->getTimestamp() <= $start) &&
						(is_null($params['end']) || $img->getTimestamp() >= $params['end'])) {
					$gotOne = true;
					$fit = $this->addPageSubItem($pageId,
						self::getInfo( $img, $prop, $result, $scale));
					if(!$fit)
					{
						if(count($pageIds[NS_IMAGE]) == 1)
							# See the 'the user is screwed' comment above
							$this->setContinueEnumParameter('start',
								wfTimestamp(TS_ISO_8601, $img->getTimestamp()));
						else
							$this->setContinueEnumParameter('continue',
								$this->getContinueStr($img));
						break;
					}
				}

				// Now get the old revisions
				// Get one more to facilitate query-continue functionality
				$count = ($gotOne ? 1 : 0);
				$oldies = $img->getHistory($params['limit'] - $count + 1, $start, $params['end']);
				foreach($oldies as $oldie) {
					if(++$count > $params['limit']) {
						// We've reached the extra one which shows that there are additional pages to be had. Stop here...
						// Only set a query-continue if there was only one title
						if(count($pageIds[NS_FILE]) == 1)
						{
							$this->setContinueEnumParameter('start',
								wfTimestamp(TS_ISO_8601, $oldie->getTimestamp()));
						}
						break;
					}
					$fit = $this->addPageSubItem($pageId,
						self::getInfo($oldie, $prop, $result));
					if(!$fit)
					{
						if(count($pageIds[NS_IMAGE]) == 1)
							$this->setContinueEnumParameter('start',
								wfTimestamp(TS_ISO_8601, $oldie->getTimestamp()));
						else
							$this->setContinueEnumParameter('continue',
								$this->getContinueStr($oldie));
						break;
					}
				}
				if(!$fit)
					break;
				$skip = false;
			}
			
			$data = $this->getResultData();
			foreach($data['query']['pages'] as $pageid => $arr) {
				if(!isset($arr['imagerepository']))
					$result->addValue(
						array('query', 'pages', $pageid),
						'imagerepository', ''
					);
					// The above can't fail because it doesn't increase the result size
			}
		}
	}

	/**
	 * Get result information for an image revision
	 * @param File f The image
	 * @return array Result array
	 */
	static function getInfo($file, $prop, $result, $scale = null) {
		$vals = array();
		if( isset( $prop['timestamp'] ) )
			$vals['timestamp'] = wfTimestamp(TS_ISO_8601, $file->getTimestamp());
		if( isset( $prop['user'] ) ) {
			$vals['user'] = $file->getUser();
			if( !$file->getUser( 'id' ) )
				$vals['anon'] = '';
		}
		if( isset( $prop['size'] ) || isset( $prop['dimensions'] ) ) {
			$vals['size'] = intval( $file->getSize() );
			$vals['width'] = intval( $file->getWidth() );
			$vals['height'] = intval( $file->getHeight() );
		}
		if( isset( $prop['url'] ) ) {
			if( !is_null( $scale ) && !$file->isOld() ) {
				$mto = $file->transform( array( 'width' => $scale['width'], 'height' => $scale['height'] ) );
				if( $mto && !$mto->isError() )
				{
					$vals['thumburl'] = $mto->getUrl();
					$vals['thumbwidth'] = intval( $mto->getWidth() );
					$vals['thumbheight'] = intval( $mto->getHeight() );
				}
			}
			$vals['url'] = $file->getFullURL();
			$vals['descriptionurl'] = wfExpandUrl( $file->getDescriptionUrl() );
		}
		if( isset( $prop['comment'] ) )
			$vals['comment'] = $file->getDescription();
		if( isset( $prop['sha1'] ) )
			$vals['sha1'] = wfBaseConvert( $file->getSha1(), 36, 16, 40 );
		if( isset( $prop['metadata'] ) ) {
			$metadata = $file->getMetadata();
			$vals['metadata'] = $metadata ? self::processMetaData( unserialize( $metadata ), $result ) : null;
		}
		if( isset( $prop['mime'] ) ) 
			$vals['mime'] = $file->getMimeType();
		
		if( isset( $prop['archivename'] ) && $file->isOld() )
			$vals['archivename'] = $file->getArchiveName();
			
		if( isset( $prop['bitdepth'] ) )
			$vals['bitdepth'] = $file->getBitDepth();

		return $vals;
	}
	
	public static function processMetaData($metadata, $result)
	{
		$retval = array();
		if ( is_array( $metadata ) ) {
			foreach($metadata as $key => $value)
			{
				$r = array('name' => $key);
				if(is_array($value))
					$r['value'] = self::processMetaData($value, $result);
				else
					$r['value'] = $value;
				$retval[] = $r;
			}
		}
		$result->setIndexedTagName($retval, 'metadata');
		return $retval;
	}

	private function getContinueStr($img)
	{
		return $img->getOriginalTitle()->getText() .
			'|' .  $img->getTimestamp();
	}

	public function getAllowedParams() {
		return array (
			'prop' => array (
				ApiBase :: PARAM_ISMULTI => true,
				ApiBase :: PARAM_DFLT => 'timestamp|user',
				ApiBase :: PARAM_TYPE => self::getPropertyNames()
			),
			'limit' => array(
				ApiBase :: PARAM_TYPE => 'limit',
				ApiBase :: PARAM_DFLT => 1,
				ApiBase :: PARAM_MIN => 1,
				ApiBase :: PARAM_MAX => ApiBase :: LIMIT_BIG1,
				ApiBase :: PARAM_MAX2 => ApiBase :: LIMIT_BIG2
			),
			'start' => array(
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'end' => array(
				ApiBase :: PARAM_TYPE => 'timestamp'
			),
			'urlwidth' => array(
				ApiBase :: PARAM_TYPE => 'integer',
				ApiBase :: PARAM_DFLT => -1
			),
			'urlheight' => array(
				ApiBase :: PARAM_TYPE => 'integer',
				ApiBase :: PARAM_DFLT => -1
			),
			'continue' => null,
		);
	}
	
	/**
	 * Returns all possible parameters to iiprop
	 */
	public static function getPropertyNames() {
		return array (
					'timestamp',
					'user',
					'comment',
					'url',
					'size',
					'dimensions', // For backwards compatibility with Allimages
					'sha1',
					'mime',
					'metadata',
					'archivename',
					'bitdepth',
				);
	}

	public function getParamDescription() {
		return array (
			'prop' => 'What image information to get.',
			'limit' => 'How many image revisions to return',
			'start' => 'Timestamp to start listing from',
			'end' => 'Timestamp to stop listing at',
			'urlwidth' => array('If iiprop=url is set, a URL to an image scaled to this width will be returned.',
					    'Only the current version of the image can be scaled.'),
			'urlheight' => 'Similar to iiurlwidth. Cannot be used without iiurlwidth',
			'continue' => 'When more results are available, use this to continue',
		);
	}

	public function getDescription() {
		return array (
			'Returns image information and upload history'
		);
	}

	protected function getExamples() {
		return array (
			'api.php?action=query&titles=File:Albert%20Einstein%20Head.jpg&prop=imageinfo',
			'api.php?action=query&titles=File:Test.jpg&prop=imageinfo&iilimit=50&iiend=20071231235959&iiprop=timestamp|user|url',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
