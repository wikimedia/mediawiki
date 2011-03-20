<?php

/**
 * API for MediaWiki 1.12+
 *
 * Created on Mar 16, 2008
 *
 * Copyright © 2008 Vasiliev Victor vasilvv@gmail.com,
 * based on ApiQueryAllpages.php
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
 * Query module to enumerate all available pages.
 *
 * @ingroup API
 */
class ApiQueryAllimages extends ApiQueryGeneratorBase {

	protected $mRepo;

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'ai' );
		$this->mRepo = RepoGroup::singleton()->getLocalRepo();
	}

	/**
	 * Override parent method to make sure to make sure the repo's DB is used
	 * which may not necesarilly be the same as the local DB.
	 *
	 * TODO: allow querying non-local repos.
	 * @return DatabaseBase
	 */
	protected function getDB() {
		return $this->mRepo->getSlaveDB();
	}

	public function execute() {
		$this->run();
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	/**
	 * @param $resultPageSet ApiPageSet
	 * @return void
	 */
	public function executeGenerator( $resultPageSet ) {
		if ( $resultPageSet->isResolvingRedirects() ) {
			$this->dieUsage( 'Use "gaifilterredir=nonredirects" option instead of "redirects" when using allimages as a generator', 'params' );
		}

		$this->run( $resultPageSet );
	}

	/**
	 * @param $resultPageSet ApiPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$repo = $this->mRepo;
		if ( !$repo instanceof LocalRepo ) {
			$this->dieUsage( 'Local file repository does not support querying all images', 'unsupportedrepo' );
		}

		$db = $this->getDB();

		$params = $this->extractRequestParams();

		// Image filters
		$dir = ( $params['dir'] == 'descending' ? 'older' : 'newer' );
		$from = ( is_null( $params['from'] ) ? null : $this->titlePartToKey( $params['from'] ) );
		$to = ( is_null( $params['to'] ) ? null : $this->titlePartToKey( $params['to'] ) );
		$this->addWhereRange( 'img_name', $dir, $from, $to );

		if ( isset( $params['prefix'] ) )
			$this->addWhere( 'img_name' . $db->buildLike( $this->titlePartToKey( $params['prefix'] ), $db->anyString() ) );

		if ( isset( $params['minsize'] ) ) {
			$this->addWhere( 'img_size>=' . intval( $params['minsize'] ) );
		}

		if ( isset( $params['maxsize'] ) ) {
			$this->addWhere( 'img_size<=' . intval( $params['maxsize'] ) );
		}

		$sha1 = false;
		if ( isset( $params['sha1'] ) ) {
			$sha1 = wfBaseConvert( $params['sha1'], 16, 36, 31 );
		} elseif ( isset( $params['sha1base36'] ) ) {
			$sha1 = $params['sha1base36'];
		}
		if ( $sha1 ) {
			$this->addWhere( 'img_sha1=' . $db->addQuotes( $sha1 ) );
		}

		if ( !is_null( $params['mime'] ) ) {
			global $wgMiserMode;
			if ( $wgMiserMode  ) {
				$this->dieUsage( 'MIME search disabled in Miser Mode', 'mimeearchdisabled' );
			}

			list( $major, $minor ) = File::splitMime( $params['mime'] );

			$this->addWhereFld( 'img_major_mime', $major );
			$this->addWhereFld( 'img_minor_mime', $minor );
		}

		$this->addTables( 'image' );

		$prop = array_flip( $params['prop'] );
		$this->addFields( LocalFile::selectFields() );

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );
		$this->addOption( 'ORDER BY', 'img_name' .
						( $params['dir'] == 'descending' ? ' DESC' : '' ) );

		$res = $this->select( __METHOD__ );

		$titles = array();
		$count = 0;
		$result = $this->getResult();
		foreach ( $res as $row ) {
			if ( ++ $count > $limit ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				// TODO: Security issue - if the user has no right to view next title, it will still be shown
				$this->setContinueEnumParameter( 'from', $this->keyToTitle( $row->img_name ) );
				break;
			}

			if ( is_null( $resultPageSet ) ) {
				$file = $repo->newFileFromRow( $row );
				$info = array_merge( array( 'name' => $row->img_name ),
					ApiQueryImageInfo::getInfo( $file, $prop, $result ) );
				self::addTitleInfo( $info, $file->getTitle() ); 
					
				$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $info );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'from', $this->keyToTitle( $row->img_name ) );
					break;
				}
			} else {
				$titles[] = Title::makeTitle( NS_IMAGE, $row->img_name );
			}
		}

		if ( is_null( $resultPageSet ) ) {
			$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'img' );
		} else {
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	public function getAllowedParams() {
		return array (
			'from' => null,
			'to' => null,
			'prefix' => null,
			'minsize' => array(
				ApiBase::PARAM_TYPE => 'integer',
			),
			'maxsize' => array(
				ApiBase::PARAM_TYPE => 'integer',
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'dir' => array(
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => array(
					'ascending',
					'descending'
				)
			),
			'sha1' => null,
			'sha1base36' => null,
			'prop' => array(
				ApiBase::PARAM_TYPE => ApiQueryImageInfo::getPropertyNames(),
				ApiBase::PARAM_DFLT => 'timestamp|url',
				ApiBase::PARAM_ISMULTI => true
			),
			'mime' => null,
		);
	}

	public function getParamDescription() {
		return array(
			'from' => 'The image title to start enumerating from',
			'to' => 'The image title to stop enumerating at',
			'prefix' => 'Search for all image titles that begin with this value',
			'dir' => 'The direction in which to list',
			'minsize' => 'Limit to images with at least this many bytes',
			'maxsize' => 'Limit to images with at most this many bytes',
			'limit' => 'How many images in total to return',
			'sha1' => "SHA1 hash of image. Overrides {$this->getModulePrefix()}sha1base36",
			'sha1base36' => 'SHA1 hash of image in base 36 (used in MediaWiki)',
			'prop' => ApiQueryImageInfo::getPropertyDescriptions(),
			'mime' => 'What MIME type to search for. e.g. image/jpeg. Disabled in Miser Mode',
		);
	}

	public function getDescription() {
		return 'Enumerate all images sequentially';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'params', 'info' => 'Use "gaifilterredir=nonredirects" option instead of "redirects" when using allimages as a generator' ),
			array( 'code' => 'unsupportedrepo', 'info' => 'Local file repository does not support querying all images' ),
			array( 'code' => 'mimeearchdisabled', 'info' => 'MIME search disabled in Miser Mode' ),
		) );
	}

	protected function getExamples() {
		return array(
			'Simple Use',
			' Show a list of images starting at the letter "B"',
			'  api.php?action=query&list=allimages&aifrom=B',
			'Using as Generator',
			' Show info about 4 images starting at the letter "T"',
			'  api.php?action=query&generator=allimages&gailimit=4&gaifrom=T&prop=imageinfo',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
