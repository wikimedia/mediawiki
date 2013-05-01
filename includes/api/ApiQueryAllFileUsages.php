<?php
/**
 *
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
 * Query module to enumerate file usages from all files together.
 *
 * @ingroup API
 */
class ApiQueryAllFileUsages extends ApiQueryGeneratorBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'af' );
	}

	public function execute() {
		$this->run();
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param $resultPageSet ApiPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$db = $this->getDB();
		$params = $this->extractRequestParams();

		$prop = array_flip( $params['prop'] );
		$fld_ids = isset( $prop['ids'] );
		$fld_title = isset( $prop['title'] );

		if ( $params['unique'] ) {
			if ( $fld_ids ) {
				$this->dieUsage(
					"{$this->getModuleName()} cannot return corresponding page ids in unique mode",
					'params' );
			}
			$this->addOption( 'DISTINCT' );
		}

		$this->addTables( 'imagelinks' );

		$continue = !is_null( $params['continue'] );
		if ( $continue ) {
			$continueArr = explode( '|', $params['continue'] );
			$op = $params['dir'] == 'descending' ? '<' : '>';
			if ( $params['unique'] ) {
				$this->dieContinueUsageIf( count( $continueArr ) != 1 );
				$continueTitle = $db->addQuotes( $continueArr[0] );
				$this->addWhere( "il_to $op= $continueTitle" );
			} else {
				$this->dieContinueUsageIf( count( $continueArr ) != 2 );
				$continueTitle = $db->addQuotes( $continueArr[0] );
				$continueFrom = intval( $continueArr[1] );
				$this->addWhere(
					"il_to $op $continueTitle OR " .
					"(il_to = $continueTitle AND " .
					"il_from $op= $continueFrom)"
				);
			}
		}

		// 'continue' always overrides 'from'
		$from = ( $continue || is_null( $params['from'] ) ? null : $this->titlePartToKey( $params['from'] ) );
		$to = ( is_null( $params['to'] ) ? null : $this->titlePartToKey( $params['to'] ) );
		$this->addWhereRange( 'il_to', 'newer', $from, $to );

		if ( isset( $params['prefix'] ) ) {
			$this->addWhere( 'il_to' . $db->buildLike( $this->titlePartToKey( $params['prefix'] ), $db->anyString() ) );
		}

		$this->addFields( array( 'il_to' ) );
		$this->addFieldsIf( array( 'il_from' ), !$params['unique'] );

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );

		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		$orderBy = array();
		$orderBy[] = 'il_to' . $sort;
		if ( !$params['unique'] ) {
			$orderBy[] = 'il_from' . $sort;
		}
		$this->addOption( 'ORDER BY', $orderBy );

		$res = $this->select( __METHOD__ );

		$pageids = array();
		$titles = array();
		$count = 0;
		$result = $this->getResult();
		foreach ( $res as $row ) {
			if ( ++ $count > $limit ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				if ( $params['unique'] ) {
					$this->setContinueEnumParameter( 'continue', $row->il_to );
				} else {
					$this->setContinueEnumParameter( 'continue', $row->il_to . '|' . $row->il_from );
				}
				break;
			}

			if ( is_null( $resultPageSet ) ) {
				$vals = array();
				if ( $fld_ids ) {
					$vals['fromid'] = intval( $row->il_from );
				}
				if ( $fld_title ) {
					$title = Title::makeTitle( NS_FILE, $row->il_to );
					ApiQueryBase::addTitleInfo( $vals, $title );
				}
				$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $vals );
				if ( !$fit ) {
					if ( $params['unique'] ) {
						$this->setContinueEnumParameter( 'continue', $row->il_to );
					} else {
						$this->setContinueEnumParameter( 'continue', $row->il_to . '|' . $row->il_from );
					}
					break;
				}
			} elseif ( $params['unique'] ) {
				$titles[] = Title::makeTitle( NS_FILE, $row->il_to );
			} else {
				$pageids[] = $row->il_from;
			}
		}

		if ( is_null( $resultPageSet ) ) {
			$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'f' );
		} elseif ( $params['unique'] ) {
			$resultPageSet->populateFromTitles( $titles );
		} else {
			$resultPageSet->populateFromPageIDs( $pageids );
		}
	}

	public function getAllowedParams() {
		return array(
			'continue' => null,
			'from' => null,
			'to' => null,
			'prefix' => null,
			'unique' => false,
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'title',
				ApiBase::PARAM_TYPE => array(
					'ids',
					'title'
				)
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
		);
	}

	public function getParamDescription() {
		$p = $this->getModulePrefix();
		return array(
			'from' => 'The title of the file to start enumerating from',
			'to' => 'The title of the file to stop enumerating at',
			'prefix' => 'Search for all used file titles that begin with this value',
			'unique' => array(
					"Only show distinct file usage titles. Cannot be used with {$p}prop=ids.",
					'When used as a generator, yields target pages instead of source pages.',
			),
			'prop' => array(
				'What pieces of information to include',
				" ids    - Adds the pageid of the pages used the file (Cannot be used with {$p}unique)",
				' title  - Adds the title of the file',
			),
			'limit' => 'How many total items to return',
			'continue' => 'When more results are available, use this to continue',
			'dir' => 'The direction in which to list',
		);
	}

	public function getResultProperties() {
		return array(
			'ids' => array(
				'fromid' => 'integer'
			),
			'title' => array(
				'ns' => 'namespace',
				'title' => 'string'
			)
		);
	}

	public function getDescription() {
		return 'Enumerate all file usages';
	}

	public function getPossibleErrors() {
		$m = $this->getModuleName();
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'params', 'info' => "{$m} cannot return corresponding page ids in unique mode" ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&list=allfileusages&affrom=B&afprop=ids|title'
					=> 'List file titles with page ids they are used, including missing ones. Start at B',
			'api.php?action=query&list=allfileusages&afunique=&affrom=B'
					=> 'List unique used file titles',
			'api.php?action=query&generator=allfileusages&gafunique=&gaffrom=B'
					=> 'Gets all files, marking the missing ones',
			'api.php?action=query&generator=allfileusages&gafunique=&gaffrom=B&prop=imageinfo'
					=> 'Gets extra information of used files',
			'api.php?action=query&generator=allfileusages&gaffrom=B'
					=> 'Gets pages containing the files',
		);
	}

	public function getHelpUrls() {
		return "https://www.mediawiki.org/wiki/API:AllFileUsages";
	}
}
