<?php

/**
 *
 *
 * Created on Sep 11, 2012
 *
 * Copyright © 2012 Jesús Martínez Novo martineznovo@gmail.com
 *  Based on ApiQueryAllimages and SpecialNewFiles
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
 * Query action to provide the functionality of Special:NewFiles.
 *
 * @ingroup API
 */
class ApiQueryNewFiles extends ApiQueryGeneratorBase {

	protected $mRepo;

	private $propertyFilter = array();

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'nf' );
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
		$this->run( $resultPageSet );
	}

	/**
	 * @param $resultPageSet ApiPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		global $wgMiserMode;
		$repo = $this->mRepo;
		if ( !$repo instanceof LocalRepo ) {
			$this->dieUsage( 'File repository is not a local file repository', 'unsupportedrepo' );
		}

		$params = $this->extractRequestParams();

		if (!is_null( $params['user'] ) && $params['nobots']) {
			$this->dieUsage( "Parameters 'user' and 'nobots' cannot be used together", 'badparams' );
		}

		if ( !is_null( $params['mime'] ) && $wgMiserMode ) {
			$this->dieUsage( 'MIME search disabled in Miser Mode', 'mimesearchdisabled' );
		}

		$this->addTables( 'image' );

		// Image filters
		$this->addTimestampWhereRange( 'img_timestamp', $params['dir'], $params['start'], $params['end'] );

		$prop = array_flip( $params['prop'] );
		$this->addFields( LocalFile::selectFields() );

		if ( $params['user'] ) {
			$this->addWhereFld( 'img_user_text', $params['user'] );
		}

		if ( $params['nobots'] ) {
			$this->addTables( 'user_groups' );
			$this->addWhere( 'ug_group IS NULL' );
			$this->addJoinConds( array( 'user_groups' => array(
				'LEFT JOIN',
				array(
					'ug_group' => User::getGroupsWithPermission( 'bot' ),
					'ug_user = img_user'
				)
			) ) );
		}

		if ( !is_null( $params['mime'] ) ) {
			list( $major, $minor ) = File::splitMime( $params['mime'] );

			$this->addWhereFld( 'img_major_mime', $major );
			$this->addWhereFld( 'img_minor_mime', $minor );
		}

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );
		$this->addOption( 'ORDER BY', 'img_timestamp' .
						( $params['dir'] == 'older' ? ' DESC' : '' ) );

		$res = $this->select( __METHOD__ );

		$titles = array();
		$count = 0;
		$result = $this->getResult();
		foreach ( $res as $row ) {
			if ( ++ $count > $limit ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->img_timestamp ) );
				break;
			}

			if ( is_null( $resultPageSet ) ) {
				$file = $repo->newFileFromRow( $row );
				$info = array_merge( array( 'name' => $row->img_name ),
					ApiQueryImageInfo::getInfo( $file, $prop, $result ) );
				self::addTitleInfo( $info, $file->getTitle() );

				$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $info );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->img_timestamp ) );
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
			'start' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'end' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'dir' => array(
				ApiBase::PARAM_DFLT => 'older',
				ApiBase::PARAM_TYPE => array(
					'newer',
					'older'
				)
			),
			'user' => array(
				ApiBase::PARAM_TYPE => 'user'
			),
			'nobots' => array(
				ApiBase::PARAM_TYPE => 'boolean'
			),
			'mime' => null,
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'prop' => array(
				ApiBase::PARAM_TYPE => ApiQueryImageInfo::getPropertyNames( $this->propertyFilter ),
				ApiBase::PARAM_DFLT => 'archivename|user|timestamp|url',
				ApiBase::PARAM_ISMULTI => true
			),
		);
	}

	public function getParamDescription() {
		$p = $this->getModulePrefix();
		return array(
			'start' => 'The timestamp to start enumerating from',
			'end' => 'The timestamp to end enumerating',
			'dir' => $this->getDirectionDescription( $p ),
			'user' => 'Only list files uploaded by this user. Cannot be used together with nobots',
			'nobots' => 'Do not return uploads made by bots. Cannot be used together with user',
			'mime' => 'What MIME type to search for. e.g. image/jpeg. Disabled in Miser Mode',
			'limit' => 'How many images in total to return',
			'prop' => ApiQueryImageInfo::getPropertyDescriptions( $this->propertyFilter ),
		);
	}

	public function getDescription() {
		return 'Enumerate recent uploads from the local file repository. Only the last version of a file is displayed.';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'unsupportedrepo', 'info' => 'File repository is not a local file repository' ),
			array( 'code' => 'badparams', 'info' => "Parameters 'user' and 'nobots' cannot be used together" ),
			array( 'code' => 'mimesearchdisabled', 'info' => 'MIME search disabled in Miser Mode' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&list=newfiles' => array(
				'Simple Use',
				'Show a list of recent uploaded files',
			),
			'api.php?action=query&generator=newfiles&prop=imageinfo&iiprop=timestamp|user|url&iiurlwidth=120' => array(
				'Using as Generator',
				'Get file, file description and thumb URLs of recent uploaded files',
			),
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Newfiles';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
