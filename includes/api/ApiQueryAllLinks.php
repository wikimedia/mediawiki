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

namespace MediaWiki\Api;

use MediaWiki\Cache\GenderCache;
use MediaWiki\Deferred\LinksUpdate\TemplateLinksTable;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\ParamValidator\TypeDef\NamespaceDef;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

/**
 * Query module to enumerate links from all pages together.
 *
 * @ingroup API
 */
class ApiQueryAllLinks extends ApiQueryGeneratorBase {

	private string $table;
	private string $tablePrefix;
	private string $indexTag;
	/** @var string */
	private $fieldTitle = 'title';
	/** @var int */
	private $dfltNamespace = NS_MAIN;
	/** @var bool */
	private $hasNamespace = true;
	/** @var string|null */
	private $useIndex = null;
	/** @var array */
	private $props = [];
	/** @var string|bool */
	private $virtualDomain = false;

	private NamespaceInfo $namespaceInfo;
	private GenderCache $genderCache;
	private LinksMigration $linksMigration;
	private IConnectionProvider $dbProvider;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		NamespaceInfo $namespaceInfo,
		GenderCache $genderCache,
		LinksMigration $linksMigration,
		IConnectionProvider $dbProvider
	) {
		switch ( $moduleName ) {
			case 'alllinks':
				$prefix = 'al';
				$this->table = 'pagelinks';
				$this->tablePrefix = 'pl_';
				$this->useIndex = 'pl_namespace';
				$this->indexTag = 'l';
				break;
			case 'alltransclusions':
				$prefix = 'at';
				$this->table = 'templatelinks';
				$this->tablePrefix = 'tl_';
				$this->dfltNamespace = NS_TEMPLATE;
				$this->indexTag = 't';
				$this->virtualDomain = TemplateLinksTable::VIRTUAL_DOMAIN;
				break;
			case 'allfileusages':
				$prefix = 'af';
				$this->table = 'imagelinks';
				$this->tablePrefix = 'il_';
				$this->fieldTitle = 'to';
				$this->dfltNamespace = NS_FILE;
				$this->hasNamespace = false;
				$this->indexTag = 'f';
				break;
			case 'allredirects':
				$prefix = 'ar';
				$this->table = 'redirect';
				$this->tablePrefix = 'rd_';
				$this->indexTag = 'r';
				$this->props = [
					'fragment' => 'rd_fragment',
					'interwiki' => 'rd_interwiki',
				];
				break;
			default:
				ApiBase::dieDebug( __METHOD__, 'Unknown module name' );
		}

		parent::__construct( $query, $moduleName, $prefix );
		$this->namespaceInfo = $namespaceInfo;
		$this->genderCache = $genderCache;
		$this->linksMigration = $linksMigration;
		$this->dbProvider = $dbProvider;
	}

	public function execute() {
		$this->run();
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'public';
	}

	/** @inheritDoc */
	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$db = $this->getDB();
		$params = $this->extractRequestParams();

		$pfx = $this->tablePrefix;

		$nsField = $pfx . 'namespace';
		$titleField = $pfx . $this->fieldTitle;
		$linktargetReadNew = false;
		$targetIdColumn = '';
		if ( isset( $this->linksMigration::$mapping[$this->table] ) ) {
			[ $nsField, $titleField ] = $this->linksMigration->getTitleFields( $this->table );
			$queryInfo = $this->linksMigration->getQueryInfo( $this->table, 'linktarget', 'STRAIGHT_JOIN' );
			$this->addTables( $queryInfo['tables'] );
			$this->addJoinConds( $queryInfo['joins'] );
			if ( in_array( 'linktarget', $queryInfo['tables'] ) ) {
				$linktargetReadNew = true;
				$targetIdColumn = "{$pfx}target_id";
				$this->addFields( [ $targetIdColumn ] );
			}
		} else {
			if ( $this->useIndex ) {
				$this->addOption( 'USE INDEX', $this->useIndex );
			}
			$this->addTables( $this->table );
		}

		$prop = array_fill_keys( $params['prop'], true );
		$fld_ids = isset( $prop['ids'] );
		$fld_title = isset( $prop['title'] );
		if ( $this->hasNamespace ) {
			$namespace = $params['namespace'];
		} else {
			$namespace = $this->dfltNamespace;
		}

		if ( $params['unique'] ) {
			$matches = array_intersect_key( $prop, $this->props + [ 'ids' => 1 ] );
			if ( $matches ) {
				$p = $this->getModulePrefix();
				$this->dieWithError(
					[
						'apierror-invalidparammix-cannotusewith',
						"{$p}prop=" . implode( '|', array_keys( $matches ) ),
						"{$p}unique"
					],
					'invalidparammix'
				);
			}
			$this->addOption( 'DISTINCT' );
		}

		if ( $this->hasNamespace ) {
			$this->addWhereFld( $nsField, $namespace );
		}

		$continue = $params['continue'] !== null;
		if ( $continue ) {
			$op = $params['dir'] == 'descending' ? '<=' : '>=';
			if ( $params['unique'] ) {
				$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'string' ] );
				$this->addWhere( $db->expr( $titleField, $op, $cont[0] ) );
			} elseif ( !$linktargetReadNew ) {
				$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'string', 'int' ] );
				$this->addWhere( $db->buildComparison( $op, [
					$titleField => $cont[0],
					"{$pfx}from" => $cont[1],
				] ) );
			} else {
				$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'int', 'int' ] );
				$this->addWhere( $db->buildComparison( $op, [
					$targetIdColumn => $cont[0],
					"{$pfx}from" => $cont[1],
				] ) );
			}
		}

		// 'continue' always overrides 'from'
		$from = $continue || $params['from'] === null ? null :
			$this->titlePartToKey( $params['from'], $namespace );
		$to = $params['to'] === null ? null :
			$this->titlePartToKey( $params['to'], $namespace );
		$this->addWhereRange( $titleField, 'newer', $from, $to );

		if ( isset( $params['prefix'] ) ) {
			$this->addWhere(
				$db->expr(
					$titleField,
					IExpression::LIKE,
					new LikeValue( $this->titlePartToKey( $params['prefix'], $namespace ), $db->anyString() )
				)
			);
		}

		$this->addFields( [ 'pl_title' => $titleField ] );
		$this->addFieldsIf( [ 'pl_from' => $pfx . 'from' ], !$params['unique'] );
		foreach ( $this->props as $name => $field ) {
			$this->addFieldsIf( $field, isset( $prop[$name] ) );
		}

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );

		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		$orderBy = [];
		if ( $linktargetReadNew ) {
			$orderBy[] = $targetIdColumn;
		} else {
			$orderBy[] = $titleField . $sort;
		}
		if ( !$params['unique'] ) {
			$orderBy[] = $pfx . 'from' . $sort;
		}
		$this->addOption( 'ORDER BY', $orderBy );

		$this->getQueryBuilder()->connection(
			$this->dbProvider->getReplicaDatabase( $this->virtualDomain, 'api' )
		);
		$res = $this->select( __METHOD__ );
		$this->getQueryBuilder()->connection( $this->getDB() );

		// Get gender information
		if ( $resultPageSet === null && $res->numRows() && $this->namespaceInfo->hasGenderDistinction( $namespace ) ) {
			$users = [];
			foreach ( $res as $row ) {
				$users[] = $row->pl_title;
			}
			if ( $users !== [] ) {
				$this->genderCache->doQuery( $users, __METHOD__ );
			}
		}

		$pageids = [];
		$titles = [];
		$count = 0;
		$result = $this->getResult();

		foreach ( $res as $row ) {
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				if ( $params['unique'] ) {
					$this->setContinueEnumParameter( 'continue', $row->pl_title );
				} elseif ( $linktargetReadNew ) {
					$this->setContinueEnumParameter( 'continue', $row->{$targetIdColumn} . '|' . $row->pl_from );
				} else {
					$this->setContinueEnumParameter( 'continue', $row->pl_title . '|' . $row->pl_from );
				}
				break;
			}

			if ( $resultPageSet === null ) {
				$vals = [
					ApiResult::META_TYPE => 'assoc',
				];
				if ( $fld_ids ) {
					$vals['fromid'] = (int)$row->pl_from;
				}
				if ( $fld_title ) {
					$title = Title::makeTitle( $namespace, $row->pl_title );
					ApiQueryBase::addTitleInfo( $vals, $title );
				}
				foreach ( $this->props as $name => $field ) {
					if ( isset( $prop[$name] ) && $row->$field !== null && $row->$field !== '' ) {
						$vals[$name] = $row->$field;
					}
				}
				$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $vals );
				if ( !$fit ) {
					if ( $params['unique'] ) {
						$this->setContinueEnumParameter( 'continue', $row->pl_title );
					} elseif ( $linktargetReadNew ) {
						$this->setContinueEnumParameter( 'continue', $row->{$targetIdColumn} . '|' . $row->pl_from );
					} else {
						$this->setContinueEnumParameter( 'continue', $row->pl_title . '|' . $row->pl_from );
					}
					break;
				}
			} elseif ( $params['unique'] ) {
				$titles[] = Title::makeTitle( $namespace, $row->pl_title );
			} else {
				$pageids[] = $row->pl_from;
			}
		}

		if ( $resultPageSet === null ) {
			$result->addIndexedTagName( [ 'query', $this->getModuleName() ], $this->indexTag );
		} elseif ( $params['unique'] ) {
			$resultPageSet->populateFromTitles( $titles );
		} else {
			$resultPageSet->populateFromPageIDs( $pageids );
		}
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		$allowedParams = [
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'from' => null,
			'to' => null,
			'prefix' => null,
			'unique' => false,
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEFAULT => 'title',
				ParamValidator::PARAM_TYPE => array_merge(
					[ 'ids', 'title' ], array_keys( $this->props )
				),
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'namespace' => [
				ParamValidator::PARAM_DEFAULT => $this->dfltNamespace,
				ParamValidator::PARAM_TYPE => 'namespace',
				NamespaceDef::PARAM_EXTRA_NAMESPACES => [ NS_MEDIA, NS_SPECIAL ],
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'ascending',
				ParamValidator::PARAM_TYPE => [
					'ascending',
					'descending'
				]
			],
		];
		if ( !$this->hasNamespace ) {
			unset( $allowedParams['namespace'] );
		}

		return $allowedParams;
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		$p = $this->getModulePrefix();
		$name = $this->getModuleName();
		$path = $this->getModulePath();

		return [
			"action=query&list={$name}&{$p}from=B&{$p}prop=ids|title"
				=> "apihelp-$path-example-b",
			"action=query&list={$name}&{$p}unique=&{$p}from=B"
				=> "apihelp-$path-example-unique",
			"action=query&generator={$name}&g{$p}unique=&g{$p}from=B"
				=> "apihelp-$path-example-unique-generator",
			"action=query&generator={$name}&g{$p}from=B"
				=> "apihelp-$path-example-generator",
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		$name = ucfirst( $this->getModuleName() );

		return "https://www.mediawiki.org/wiki/Special:MyLanguage/API:{$name}";
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryAllLinks::class, 'ApiQueryAllLinks' );
