<?php
/**
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
 * @ingroup API
 */
class ApiQueryExtLinksUsage extends ApiQueryGeneratorBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'eu' );
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
	 * @param ApiPageSet|null $resultPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();
		$db = $this->getDB();

		$query = $params['query'];
		$protocol = self::getProtocolPrefix( $params['protocol'] );

		$this->addTables( [ 'externallinks', 'page' ] );
		$this->addJoinConds( [ 'page' => [ 'JOIN', 'page_id=el_from' ] ] );

		$miser_ns = [];
		if ( $this->getConfig()->get( 'MiserMode' ) ) {
			$miser_ns = $params['namespace'] ?: [];
		} else {
			$this->addWhereFld( 'page_namespace', $params['namespace'] );
		}

		$orderBy = [];

		if ( $query !== null && $query !== '' ) {
			if ( $protocol === null ) {
				$protocol = 'http://';
			}

			// Normalize query to match the normalization applied for the externallinks table
			$query = Parser::normalizeLinkUrl( $protocol . $query );

			$conds = LinkFilter::getQueryConditions( $query, [
				'protocol' => '',
				'oneWildcard' => true,
				'db' => $db
			] );
			if ( !$conds ) {
				$this->dieWithError( 'apierror-badquery' );
			}
			$this->addWhere( $conds );
			if ( !isset( $conds['el_index_60'] ) ) {
				$orderBy[] = 'el_index_60';
			}
		} else {
			$orderBy[] = 'el_index_60';

			if ( $protocol !== null ) {
				$this->addWhere( 'el_index_60' . $db->buildLike( "$protocol", $db->anyString() ) );
			} else {
				// We're querying all protocols, filter out duplicate protocol-relative links
				$this->addWhere( $db->makeList( [
					'el_to NOT' . $db->buildLike( '//', $db->anyString() ),
					'el_index_60 ' . $db->buildLike( 'http://', $db->anyString() ),
				], LIST_OR ) );
			}
		}

		$orderBy[] = 'el_id';
		$this->addOption( 'ORDER BY', $orderBy );
		$this->addFields( $orderBy ); // Make sure

		$prop = array_flip( $params['prop'] );
		$fld_ids = isset( $prop['ids'] );
		$fld_title = isset( $prop['title'] );
		$fld_url = isset( $prop['url'] );

		if ( $resultPageSet === null ) {
			$this->addFields( [
				'page_id',
				'page_namespace',
				'page_title'
			] );
			$this->addFieldsIf( 'el_to', $fld_url );
		} else {
			$this->addFields( $resultPageSet->getPageTableFields() );
		}

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );

		// T244254: Avoid MariaDB deciding to scan all of `page`.
		$this->addOption( 'STRAIGHT_JOIN' );

		if ( $params['continue'] !== null ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) !== count( $orderBy ) );
			$i = count( $cont ) - 1;
			$cond = $orderBy[$i] . ' >= ' . $db->addQuotes( rawurldecode( $cont[$i] ) );
			while ( $i-- > 0 ) {
				$field = $orderBy[$i];
				$v = $db->addQuotes( rawurldecode( $cont[$i] ) );
				$cond = "($field > $v OR ($field = $v AND $cond))";
			}
			$this->addWhere( $cond );
		}

		$res = $this->select( __METHOD__ );

		$result = $this->getResult();

		if ( $resultPageSet === null ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__ );
		}

		$count = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinue( $orderBy, $row );
				break;
			}

			if ( count( $miser_ns ) && !in_array( $row->page_namespace, $miser_ns ) ) {
				continue;
			}

			if ( $resultPageSet === null ) {
				$vals = [
					ApiResult::META_TYPE => 'assoc',
				];
				if ( $fld_ids ) {
					$vals['pageid'] = (int)$row->page_id;
				}
				if ( $fld_title ) {
					$title = Title::makeTitle( $row->page_namespace, $row->page_title );
					ApiQueryBase::addTitleInfo( $vals, $title );
				}
				if ( $fld_url ) {
					$to = $row->el_to;
					// expand protocol-relative urls
					if ( $params['expandurl'] ) {
						$to = wfExpandUrl( $to, PROTO_CANONICAL );
					}
					$vals['url'] = $to;
				}
				$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $vals );
				if ( !$fit ) {
					$this->setContinue( $orderBy, $row );
					break;
				}
			} else {
				$resultPageSet->processDbRow( $row );
			}
		}

		if ( $resultPageSet === null ) {
			$result->addIndexedTagName( [ 'query', $this->getModuleName() ],
				$this->getModulePrefix() );
		}
	}

	private function setContinue( $orderBy, $row ) {
		$fields = [];
		foreach ( $orderBy as $field ) {
			$fields[] = strtr( $row->$field, [ '%' => '%25', '|' => '%7C' ] );
		}
		$this->setContinueEnumParameter( 'continue', implode( '|', $fields ) );
	}

	public function getAllowedParams() {
		$ret = [
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'ids|title|url',
				ApiBase::PARAM_TYPE => [
					'ids',
					'title',
					'url'
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'protocol' => [
				ApiBase::PARAM_TYPE => self::prepareProtocols(),
				ApiBase::PARAM_DFLT => '',
			],
			'query' => null,
			'namespace' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => 'namespace'
			],
			'limit' => [
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'expandurl' => false,
		];

		if ( $this->getConfig()->get( 'MiserMode' ) ) {
			$ret['namespace'][ApiBase::PARAM_HELP_MSG_APPEND] = [
				'api-help-param-limited-in-miser-mode',
			];
		}

		return $ret;
	}

	public static function prepareProtocols() {
		global $wgUrlProtocols;
		$protocols = [ '' ];
		foreach ( $wgUrlProtocols as $p ) {
			if ( $p !== '//' ) {
				$protocols[] = substr( $p, 0, strpos( $p, ':' ) );
			}
		}

		return $protocols;
	}

	public static function getProtocolPrefix( $protocol ) {
		// Find the right prefix
		global $wgUrlProtocols;
		if ( $protocol && !in_array( $protocol, $wgUrlProtocols ) ) {
			foreach ( $wgUrlProtocols as $p ) {
				if ( substr( $p, 0, strlen( $protocol ) ) === $protocol ) {
					$protocol = $p;
					break;
				}
			}

			return $protocol;
		} else {
			return null;
		}
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=exturlusage&euquery=www.mediawiki.org'
				=> 'apihelp-query+exturlusage-example-simple',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Exturlusage';
	}
}
