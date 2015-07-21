<?php
/**
 *
 *
 * Created on Feb 13, 2009
 *
 * Copyright © 2009 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'pt' );
	}

	public function execute() {
		$this->run();
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet $resultPageSet
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

		// Include in ORDER BY for uniqueness
		$this->addWhereRange( 'pt_namespace', $params['dir'], null, null );
		$this->addWhereRange( 'pt_title', $params['dir'], null, null );

		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 3 );
			$op = ( $params['dir'] === 'newer' ? '>' : '<' );
			$db = $this->getDB();
			$continueTimestamp = $db->addQuotes( $db->timestamp( $cont[0] ) );
			$continueNs = (int)$cont[1];
			$this->dieContinueUsageIf( $continueNs != $cont[1] );
			$continueTitle = $db->addQuotes( $cont[2] );
			$this->addWhere( "pt_timestamp $op $continueTimestamp OR " .
				"(pt_timestamp = $continueTimestamp AND " .
				"(pt_namespace $op $continueNs OR " .
				"(pt_namespace = $continueNs AND " .
				"pt_title $op= $continueTitle)))"
			);
		}

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
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue',
					"$row->pt_timestamp|$row->pt_namespace|$row->pt_title"
				);
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

				if ( isset( $prop['userid'] ) || /*B/C*/isset( $prop['user'] ) ) {
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
					$this->setContinueEnumParameter( 'continue',
						"$row->pt_timestamp|$row->pt_namespace|$row->pt_title"
					);
					break;
				}
			} else {
				$titles[] = $title;
			}
		}

		if ( is_null( $resultPageSet ) ) {
			$result->addIndexedTagName(
				array( 'query', $this->getModuleName() ),
				$this->getModulePrefix()
			);
		} else {
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	public function getCacheMode( $params ) {
		if ( !is_null( $params['prop'] ) && in_array( 'parsedcomment', $params['prop'] ) ) {
			// formatComment() calls wfMessage() among other things
			return 'anon-public-user-private';
		} else {
			return 'public';
		}
	}

	public function getAllowedParams() {
		return array(
			'namespace' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'namespace',
			),
			'level' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array_diff( $this->getConfig()->get( 'RestrictionLevels' ), array( '' ) )
			),
			'limit' => array(
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
				),
				ApiBase::PARAM_HELP_MSG => 'api-help-param-direction',
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
			'continue' => array(
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			),
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&list=protectedtitles'
				=> 'apihelp-query+protectedtitles-example-simple',
			'action=query&generator=protectedtitles&gptnamespace=0&prop=linkshere'
				=> 'apihelp-query+protectedtitles-example-generator',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Protectedtitles';
	}
}
