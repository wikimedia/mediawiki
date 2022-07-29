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

use MediaWiki\Linker\LinksMigration;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * This is a three-in-one module to query:
 *   * backlinks  - links pointing to the given page,
 *   * embeddedin - what pages transclude the given page within themselves,
 *   * imageusage - what pages use the given image
 *
 * @ingroup API
 */
class ApiQueryBacklinks extends ApiQueryGeneratorBase {

	/**
	 * @var Title
	 */
	private $rootTitle;

	/**
	 * @var LinksMigration
	 */
	private $linksMigration;

	private $params;
	/** @var array */
	private $cont;
	private $redirect;
	private $bl_ns, $bl_from, $bl_from_ns, $bl_table, $bl_code, $bl_title, $hasNS;

	/** @var string */
	private $helpUrl;

	/**
	 * Maps ns and title to pageid
	 *
	 * @var array
	 */
	private $pageMap = [];
	private $resultArr;

	private $redirTitles = [];
	private $continueStr = null;

	/** @var string[][] output element name, database column field prefix, database table */
	private $backlinksSettings = [
		'backlinks' => [
			'code' => 'bl',
			'prefix' => 'pl',
			'linktbl' => 'pagelinks',
			'helpurl' => 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Backlinks',
		],
		'embeddedin' => [
			'code' => 'ei',
			'prefix' => 'tl',
			'linktbl' => 'templatelinks',
			'helpurl' => 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Embeddedin',
		],
		'imageusage' => [
			'code' => 'iu',
			'prefix' => 'il',
			'linktbl' => 'imagelinks',
			'helpurl' => 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Imageusage',
		]
	];

	/**
	 * @param ApiQuery $query
	 * @param string $moduleName
	 * @param LinksMigration $linksMigration
	 */
	public function __construct( ApiQuery $query, $moduleName, LinksMigration $linksMigration ) {
		$settings = $this->backlinksSettings[$moduleName];
		$prefix = $settings['prefix'];
		$code = $settings['code'];
		$this->resultArr = [];

		parent::__construct( $query, $moduleName, $code );
		$this->bl_table = $settings['linktbl'];
		$this->hasNS = $moduleName !== 'imageusage';
		$this->linksMigration = $linksMigration;
		if ( isset( $this->linksMigration::$mapping[$this->bl_table] ) ) {
			list( $this->bl_ns, $this->bl_title ) = $this->linksMigration->getTitleFields( $this->bl_table );
		} else {
			$this->bl_ns = $prefix . '_namespace';
			if ( $this->hasNS ) {
				$this->bl_title = $prefix . '_title';
			} else {
				$this->bl_title = $prefix . '_to';
			}
		}
		$this->bl_from = $prefix . '_from';
		$this->bl_from_ns = $prefix . '_from_namespace';
		$this->bl_code = $code;
		$this->helpUrl = $settings['helpurl'];
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
	private function runFirstQuery( $resultPageSet = null ) {
		$this->addTables( [ $this->bl_table, 'page' ] );
		$this->addWhere( "{$this->bl_from}=page_id" );
		if ( $resultPageSet === null ) {
			$this->addFields( [ 'page_id', 'page_title', 'page_namespace' ] );
		} else {
			$this->addFields( $resultPageSet->getPageTableFields() );
		}
		$this->addFields( [ 'page_is_redirect', 'from_ns' => 'page_namespace' ] );

		if ( isset( $this->linksMigration::$mapping[$this->bl_table] ) ) {
			$conds = $this->linksMigration->getLinksConditions( $this->bl_table, $this->rootTitle );
			$this->addWhere( $conds );
		} else {
			$this->addWhereFld( $this->bl_title, $this->rootTitle->getDBkey() );
			if ( $this->hasNS ) {
				$this->addWhereFld( $this->bl_ns, $this->rootTitle->getNamespace() );
			}
		}

		$this->addWhereFld( $this->bl_from_ns, $this->params['namespace'] );

		if ( count( $this->cont ) >= 2 ) {
			$op = $this->params['dir'] == 'descending' ? '<' : '>';
			if ( $this->params['namespace'] !== null && count( $this->params['namespace'] ) > 1 ) {
				$this->addWhere(
					"{$this->bl_from_ns} $op {$this->cont[0]} OR " .
					"({$this->bl_from_ns} = {$this->cont[0]} AND " .
					"{$this->bl_from} $op= {$this->cont[1]})"
				);
			} else {
				$this->addWhere( "{$this->bl_from} $op= {$this->cont[1]}" );
			}
		}

		if ( $this->params['filterredir'] == 'redirects' ) {
			$this->addWhereFld( 'page_is_redirect', 1 );
		} elseif ( $this->params['filterredir'] == 'nonredirects' && !$this->redirect ) {
			// T24245 - Check for !redirect, as filtering nonredirects, when
			// getting what links to them is contradictory
			$this->addWhereFld( 'page_is_redirect', 0 );
		}

		$this->addOption( 'LIMIT', $this->params['limit'] + 1 );
		$sort = ( $this->params['dir'] == 'descending' ? ' DESC' : '' );
		$orderBy = [];
		if ( $this->params['namespace'] !== null && count( $this->params['namespace'] ) > 1 ) {
			$orderBy[] = $this->bl_from_ns . $sort;
		}
		$orderBy[] = $this->bl_from . $sort;
		$this->addOption( 'ORDER BY', $orderBy );
		$this->addOption( 'STRAIGHT_JOIN' );

		$res = $this->select( __METHOD__ );

		if ( $resultPageSet === null ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__ );
		}

		$count = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $this->params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				// Continue string may be overridden at a later step
				$this->continueStr = "{$row->from_ns}|{$row->page_id}";
				break;
			}

			// Fill in continuation fields for later steps
			if ( count( $this->cont ) < 2 ) {
				$this->cont[] = $row->from_ns;
				$this->cont[] = $row->page_id;
			}

			$this->pageMap[$row->page_namespace][$row->page_title] = $row->page_id;
			$t = Title::makeTitle( $row->page_namespace, $row->page_title );
			if ( $row->page_is_redirect ) {
				$this->redirTitles[] = $t;
			}

			if ( $resultPageSet === null ) {
				$a = [ 'pageid' => (int)$row->page_id ];
				ApiQueryBase::addTitleInfo( $a, $t );
				if ( $row->page_is_redirect ) {
					$a['redirect'] = true;
				}
				// Put all the results in an array first
				$this->resultArr[$a['pageid']] = $a;
			} else {
				$resultPageSet->processDbRow( $row );
			}
		}
	}

	/**
	 * @todo This should support links migration but since it's unreachable for templatelinks
	 *     it's not needed right now.
	 * @param ApiPageSet|null $resultPageSet
	 * @return void
	 */
	private function runSecondQuery( $resultPageSet = null ) {
		$db = $this->getDB();
		$this->addTables( [ $this->bl_table, 'page' ] );
		$this->addWhere( "{$this->bl_from}=page_id" );

		if ( $resultPageSet === null ) {
			$this->addFields( [ 'page_id', 'page_title', 'page_namespace', 'page_is_redirect' ] );
		} else {
			$this->addFields( $resultPageSet->getPageTableFields() );
		}

		$this->addFields( [ $this->bl_title, 'from_ns' => 'page_namespace' ] );
		if ( $this->hasNS ) {
			$this->addFields( $this->bl_ns );
		}

		// We can't use LinkBatch here because $this->hasNS may be false
		$titleWhere = [];
		$allRedirNs = [];
		$allRedirDBkey = [];
		/** @var Title $t */
		foreach ( $this->redirTitles as $t ) {
			$redirNs = $t->getNamespace();
			$redirDBkey = $t->getDBkey();
			$titleWhere[] = "{$this->bl_title} = " . $db->addQuotes( $redirDBkey ) .
				( $this->hasNS ? " AND {$this->bl_ns} = {$redirNs}" : '' );
			$allRedirNs[$redirNs] = true;
			$allRedirDBkey[$redirDBkey] = true;
		}
		$this->addWhere( $db->makeList( $titleWhere, LIST_OR ) );
		$this->addWhereFld( 'page_namespace', $this->params['namespace'] );

		if ( count( $this->cont ) >= 6 ) {
			$op = $this->params['dir'] == 'descending' ? '<' : '>';

			$where = "{$this->bl_from} $op= {$this->cont[5]}";
			// Don't bother with namespace, title, or from_namespace if it's
			// otherwise constant in the where clause.
			if ( $this->params['namespace'] !== null && count( $this->params['namespace'] ) > 1 ) {
				$where = "{$this->bl_from_ns} $op {$this->cont[4]} OR " .
					"({$this->bl_from_ns} = {$this->cont[4]} AND ($where))";
			}
			if ( count( $allRedirDBkey ) > 1 ) {
				$title = $db->addQuotes( $this->cont[3] );
				$where = "{$this->bl_title} $op $title OR " .
					"({$this->bl_title} = $title AND ($where))";
			}
			if ( $this->hasNS && count( $allRedirNs ) > 1 ) {
				$where = "{$this->bl_ns} $op {$this->cont[2]} OR " .
					"({$this->bl_ns} = {$this->cont[2]} AND ($where))";
			}

			$this->addWhere( $where );
		}
		if ( $this->params['filterredir'] == 'redirects' ) {
			$this->addWhereFld( 'page_is_redirect', 1 );
		} elseif ( $this->params['filterredir'] == 'nonredirects' ) {
			$this->addWhereFld( 'page_is_redirect', 0 );
		}

		$this->addOption( 'LIMIT', $this->params['limit'] + 1 );
		$orderBy = [];
		$sort = ( $this->params['dir'] == 'descending' ? ' DESC' : '' );
		// Don't order by namespace/title/from_namespace if it's constant in the WHERE clause
		if ( $this->hasNS && count( $allRedirNs ) > 1 ) {
			$orderBy[] = $this->bl_ns . $sort;
		}
		if ( count( $allRedirDBkey ) > 1 ) {
			$orderBy[] = $this->bl_title . $sort;
		}
		if ( $this->params['namespace'] !== null && count( $this->params['namespace'] ) > 1 ) {
			$orderBy[] = $this->bl_from_ns . $sort;
		}
		$orderBy[] = $this->bl_from . $sort;
		$this->addOption( 'ORDER BY', $orderBy );
		$this->addOption( 'USE INDEX', [ 'page' => 'PRIMARY' ] );
		// T290379: Avoid MariaDB deciding to scan all of `page`.
		$this->addOption( 'STRAIGHT_JOIN' );

		$res = $this->select( __METHOD__ );

		if ( $resultPageSet === null ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__ );
		}

		$count = 0;
		foreach ( $res as $row ) {
			$ns = $this->hasNS ? $row->{$this->bl_ns} : NS_FILE;

			if ( ++$count > $this->params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				// Note we must keep the parameters for the first query constant
				// This may be overridden at a later step
				$title = $row->{$this->bl_title};
				$this->continueStr = implode( '|', array_slice( $this->cont, 0, 2 ) ) .
					"|$ns|$title|{$row->from_ns}|{$row->page_id}";
				break;
			}

			// Fill in continuation fields for later steps
			if ( count( $this->cont ) < 6 ) {
				$this->cont[] = $ns;
				$this->cont[] = $row->{$this->bl_title};
				$this->cont[] = $row->from_ns;
				$this->cont[] = $row->page_id;
			}

			if ( $resultPageSet === null ) {
				$a = [ 'pageid' => (int)$row->page_id ];
				ApiQueryBase::addTitleInfo( $a, Title::makeTitle( $row->page_namespace, $row->page_title ) );
				if ( $row->page_is_redirect ) {
					$a['redirect'] = true;
				}
				$parentID = $this->pageMap[$ns][$row->{$this->bl_title}];
				// Put all the results in an array first
				$this->resultArr[$parentID]['redirlinks'][$row->page_id] = $a;
			} else {
				$resultPageSet->processDbRow( $row );
			}
		}
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$this->params = $this->extractRequestParams( false );
		$this->redirect = isset( $this->params['redirect'] ) && $this->params['redirect'];
		$userMax = ( $this->redirect ? ApiBase::LIMIT_BIG1 / 2 : ApiBase::LIMIT_BIG1 );
		$botMax = ( $this->redirect ? ApiBase::LIMIT_BIG2 / 2 : ApiBase::LIMIT_BIG2 );

		$result = $this->getResult();

		if ( $this->params['limit'] == 'max' ) {
			$this->params['limit'] = $this->getMain()->canApiHighLimits() ? $botMax : $userMax;
			$result->addParsedLimit( $this->getModuleName(), $this->params['limit'] );
		} else {
			$this->params['limit'] = $this->getMain()->getParamValidator()->validateValue(
				$this, 'limit', (int)$this->params['limit'], [
					ParamValidator::PARAM_TYPE => 'limit',
					IntegerDef::PARAM_MIN => 1,
					IntegerDef::PARAM_MAX => $userMax,
					IntegerDef::PARAM_MAX2 => $botMax,
					IntegerDef::PARAM_IGNORE_RANGE => true,
				]
			);
		}

		$this->rootTitle = $this->getTitleFromTitleOrPageId( $this->params );

		// only image titles are allowed for the root in imageinfo mode
		if ( !$this->hasNS && $this->rootTitle->getNamespace() !== NS_FILE ) {
			$this->dieWithError(
				[ 'apierror-imageusage-badtitle', $this->getModuleName() ],
				'bad_image_title'
			);
		}

		// Parse and validate continuation parameter
		$this->cont = [];
		if ( $this->params['continue'] !== null ) {
			$cont = explode( '|', $this->params['continue'] );

			switch ( count( $cont ) ) {
				case 8:
					// redirect page ID for result adding
					$this->cont[7] = (int)$cont[7];
					$this->dieContinueUsageIf( $cont[7] !== (string)$this->cont[7] );

					/* Fall through */

				case 7:
					// top-level page ID for result adding
					$this->cont[6] = (int)$cont[6];
					$this->dieContinueUsageIf( $cont[6] !== (string)$this->cont[6] );

					/* Fall through */

				case 6:
					// ns for 2nd query (even for imageusage)
					$this->cont[2] = (int)$cont[2];
					$this->dieContinueUsageIf( $cont[2] !== (string)$this->cont[2] );

					// title for 2nd query
					$this->cont[3] = $cont[3];

					// from_ns for 2nd query
					$this->cont[4] = (int)$cont[4];
					$this->dieContinueUsageIf( $cont[4] !== (string)$this->cont[4] );

					// from_id for 1st query
					$this->cont[5] = (int)$cont[5];
					$this->dieContinueUsageIf( $cont[5] !== (string)$this->cont[5] );

					/* Fall through */

				case 2:
					// from_ns for 1st query
					$this->cont[0] = (int)$cont[0];
					$this->dieContinueUsageIf( $cont[0] !== (string)$this->cont[0] );

					// from_id for 1st query
					$this->cont[1] = (int)$cont[1];
					$this->dieContinueUsageIf( $cont[1] !== (string)$this->cont[1] );

					break;

				default:
					// @phan-suppress-next-line PhanImpossibleCondition
					$this->dieContinueUsageIf( true );
			}

			ksort( $this->cont );
		}

		$this->runFirstQuery( $resultPageSet );
		if ( $this->redirect && count( $this->redirTitles ) ) {
			$this->resetQueryParams();
			$this->runSecondQuery( $resultPageSet );
		}

		// Fill in any missing fields in case it's needed below
		$this->cont += [ 0, 0, 0, '', 0, 0, 0 ];

		if ( $resultPageSet === null ) {
			// Try to add the result data in one go and pray that it fits
			$code = $this->bl_code;
			$data = array_map( static function ( $arr ) use ( $code ) {
				if ( isset( $arr['redirlinks'] ) ) {
					$arr['redirlinks'] = array_values( $arr['redirlinks'] );
					ApiResult::setIndexedTagName( $arr['redirlinks'], $code );
				}
				return $arr;
			}, array_values( $this->resultArr ) );
			$fit = $result->addValue( 'query', $this->getModuleName(), $data );
			if ( !$fit ) {
				// It didn't fit. Add elements one by one until the
				// result is full.
				ksort( $this->resultArr );
				// @phan-suppress-next-line PhanSuspiciousValueComparison
				if ( count( $this->cont ) >= 7 ) {
					$startAt = $this->cont[6];
				} else {
					reset( $this->resultArr );
					$startAt = key( $this->resultArr );
				}
				$idx = 0;
				foreach ( $this->resultArr as $pageID => $arr ) {
					if ( $pageID < $startAt ) {
						continue;
					}

					// Add the basic entry without redirlinks first
					$fit = $result->addValue(
						[ 'query', $this->getModuleName() ],
						$idx, array_diff_key( $arr, [ 'redirlinks' => '' ] ) );
					if ( !$fit ) {
						$this->continueStr = implode( '|', array_slice( $this->cont, 0, 6 ) ) .
							"|$pageID";
						break;
					}

					$hasRedirs = false;
					$redirLinks = isset( $arr['redirlinks'] ) ? (array)$arr['redirlinks'] : [];
					ksort( $redirLinks );
					// @phan-suppress-next-line PhanSuspiciousValueComparisonInLoop
					if ( count( $this->cont ) >= 8 && $pageID == $startAt ) {
						$redirStartAt = $this->cont[7];
					} else {
						reset( $redirLinks );
						$redirStartAt = key( $redirLinks );
					}
					foreach ( $redirLinks as $key => $redir ) {
						if ( $key < $redirStartAt ) {
							continue;
						}

						$fit = $result->addValue(
							[ 'query', $this->getModuleName(), $idx, 'redirlinks' ],
							null, $redir );
						if ( !$fit ) {
							$this->continueStr = implode( '|', array_slice( $this->cont, 0, 6 ) ) .
								"|$pageID|$key";
							break;
						}
						$hasRedirs = true;
					}
					if ( $hasRedirs ) {
						$result->addIndexedTagName(
							[ 'query', $this->getModuleName(), $idx, 'redirlinks' ],
							$this->bl_code );
					}
					if ( !$fit ) {
						break;
					}

					$idx++;
				}
			}

			$result->addIndexedTagName(
				[ 'query', $this->getModuleName() ],
				$this->bl_code
			);
		}
		if ( $this->continueStr !== null ) {
			$this->setContinueEnumParameter( 'continue', $this->continueStr );
		}
	}

	public function getAllowedParams() {
		$retval = [
			'title' => [
				ParamValidator::PARAM_TYPE => 'string',
			],
			'pageid' => [
				ParamValidator::PARAM_TYPE => 'integer',
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'namespace' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => 'namespace'
			],
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'ascending',
				ParamValidator::PARAM_TYPE => [
					'ascending',
					'descending'
				]
			],
			'filterredir' => [
				ParamValidator::PARAM_DEFAULT => 'all',
				ParamValidator::PARAM_TYPE => [
					'all',
					'redirects',
					'nonredirects'
				]
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			]
		];
		if ( $this->getModuleName() !== 'embeddedin' ) {
			$retval['redirect'] = false;
		}

		return $retval;
	}

	protected function getExamplesMessages() {
		static $examples = [
			'backlinks' => [
				'action=query&list=backlinks&bltitle=Main%20Page'
					=> 'apihelp-query+backlinks-example-simple',
				'action=query&generator=backlinks&gbltitle=Main%20Page&prop=info'
					=> 'apihelp-query+backlinks-example-generator',
			],
			'embeddedin' => [
				'action=query&list=embeddedin&eititle=Template:Stub'
					=> 'apihelp-query+embeddedin-example-simple',
				'action=query&generator=embeddedin&geititle=Template:Stub&prop=info'
					=> 'apihelp-query+embeddedin-example-generator',
			],
			'imageusage' => [
				'action=query&list=imageusage&iutitle=File:Albert%20Einstein%20Head.jpg'
					=> 'apihelp-query+imageusage-example-simple',
				'action=query&generator=imageusage&giutitle=File:Albert%20Einstein%20Head.jpg&prop=info'
					=> 'apihelp-query+imageusage-example-generator',
			]
		];

		return $examples[$this->getModuleName()];
	}

	public function getHelpUrls() {
		return $this->helpUrl;
	}
}
