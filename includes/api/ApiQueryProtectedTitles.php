<?php
/**
 *
 *
 * Created on Feb 13, 2009
 *
 * Copyright © 2009 Roan Kattouw <Firstname>.<Lastname>@gmail.com
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
 * Query module to enumerate all create-protected pages.
 *
 * @ingroup API
 */
class ApiQueryProtectedTitles extends ApiQueryGeneratorBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'pt' );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param $resultPageSet ApiPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		$this->addTables( 'protected_titles' );
		$this->addFields( array( 'pt_namespace', 'pt_title', 'pt_timestamp' ) );

		$prop = array_flip( $params['prop'] );
		$this->addFieldsIf( 'pt_user', isset( $prop['user'] ) || isset( $prop['userid'] ) );
		$this->addFieldsIf( 'pt_reason', isset( $prop['comment'] ) || isset( $prop['parsedcomment'] ) );
		$this->addFieldsIf( 'pt_expiry', isset( $prop['expiry'] ) );
		$this->addFieldsIf( 'pt_create_perm', isset( $prop['level'] ) );

		$this->addTimestampWhereRange( 'pt_timestamp', $params['dir'], $params['start'], $params['end'] );
		$this->addWhereFld( 'pt_namespace', $params['namespace'] );
		$this->addWhereFld( 'pt_create_perm', $params['level'] );

		if ( isset( $prop['user'] ) ) {
			$this->addTables( 'user' );
			$this->addFields( 'user_name' );
			$this->addJoinConds( array( 'user' => array( 'LEFT JOIN',
				'user_id=pt_user'
			) ) );
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$res = $this->select( __METHOD__ );

		$count = 0;
		$result = $this->getResult();

		$titles = array();

		foreach ( $res as $row ) {
			if ( ++ $count > $params['limit'] ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'start', wfTimestamp( TS_ISO_8601, $row->pt_timestamp ) );
				break;
			}

			$title = Title::makeTitle( $row->pt_namespace, $row->pt_title );
			if ( is_null( $resultPageSet ) ) {
				$vals = array();
				ApiQueryBase::addTitleInfo( $vals, $title );
				if ( isset( $prop['timestamp'] ) ) {
					$vals['timestamp'] = wfTimestamp( TS_ISO_8601, $row->pt_timestamp );
				}

				if ( isset( $prop['user'] ) && !is_null( $row->user_name ) ) {
					$vals['user'] = $row->user_name;
				}

				if ( isset( $prop['user'] ) ) {
					$vals['userid'] = $row->pt_user;
				}

				if ( isset( $prop['comment'] ) ) {
					$vals['comment'] = $row->pt_reason;
				}

				if ( isset( $prop['parsedcomment'] ) ) {
					$vals['parsedcomment'] = Linker::formatComment( $row->pt_reason, $title );
				}

				if ( isset( $prop['expiry'] ) ) {
					global $wgContLang;
					$vals['expiry'] = $wgContLang->formatExpiry( $row->pt_expiry, TS_ISO_8601 );
				}

				if ( isset( $prop['level'] ) ) {
					$vals['level'] = $row->pt_create_perm;
				}

				$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'start',
						wfTimestamp( TS_ISO_8601, $row->pt_timestamp ) );
					break;
				}
			} else {
				$titles[] = $title;
			}
		}

		if ( is_null( $resultPageSet ) ) {
			$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), $this->getModulePrefix() );
		} else {
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	public function getCacheMode( $params ) {
		if ( !is_null( $params['prop'] ) && in_array( 'parsedcomment', $params['prop'] ) ) {
			// formatComment() calls wfMsg() among other things
			return 'anon-public-user-private';
		} else {
			return 'public';
		}
	}

	public function getAllowedParams() {
		global $wgRestrictionLevels;
		return array(
			'namespace' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'namespace',
			),
			'level' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array_diff( $wgRestrictionLevels, array( '' ) )
			),
			'limit' => array (
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'dir' => array(
				ApiBase::PARAM_DFLT => 'older',
				ApiBase::PARAM_TYPE => array(
					'newer',
					'older'
				)
			),
			'start' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'end' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'timestamp|level',
				ApiBase::PARAM_TYPE => array(
					'timestamp',
					'user',
					'userid',
					'comment',
					'parsedcomment',
					'expiry',
					'level'
				)
			),
		);
	}

	public function getParamDescription() {
		return array(
			'namespace' => 'Only list titles in these namespaces',
			'start' => 'Start listing at this protection timestamp',
			'end' => 'Stop listing at this protection timestamp',
			'dir' => $this->getDirectionDescription( $this->getModulePrefix() ),
			'limit' => 'How many total pages to return',
			'prop' => array(
				'Which properties to get',
				' timestamp      - Adds the timestamp of when protection was added',
				' user           - Adds the user that added the protection',
				' userid         - Adds the user id that added the protection',
				' comment        - Adds the comment for the protection',
				' parsedcomment  - Adds the parsed comment for the protection',
				' expiry         - Adds the timestamp of when the protection will be lifted',
				' level          - Adds the protection level',
			),
			'level' => 'Only list titles with these protection levels',
		);
	}

	public function getDescription() {
		return 'List all titles protected from creation';
	}

	public function getExamples() {
		return array(
			'api.php?action=query&list=protectedtitles',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Protectedtitles';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
