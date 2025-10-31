<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Deferred\LinksUpdate\PageLinksTable;
use MediaWiki\Deferred\LinksUpdate\TemplateLinksTable;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\ParamValidator\TypeDef\NamespaceDef;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * A query module to list all wiki links on a given set of pages.
 *
 * @ingroup API
 */
class ApiQueryLinks extends ApiQueryGeneratorBase {

	private const LINKS = 'links';
	private const TEMPLATES = 'templates';

	private string $table;
	private string $prefix;
	private string $titlesParam;
	private string $helpUrl;
	private ?string $virtualdomain;

	private LinkBatchFactory $linkBatchFactory;
	private LinksMigration $linksMigration;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		LinkBatchFactory $linkBatchFactory,
		LinksMigration $linksMigration
	) {
		switch ( $moduleName ) {
			case self::LINKS:
				$this->table = 'pagelinks';
				$this->prefix = 'pl';
				$this->titlesParam = 'titles';
				$this->helpUrl = 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Links';
				$this->virtualdomain = PageLinksTable::VIRTUAL_DOMAIN;
				break;
			case self::TEMPLATES:
				$this->table = 'templatelinks';
				$this->prefix = 'tl';
				$this->titlesParam = 'templates';
				$this->helpUrl = 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Templates';
				$this->virtualdomain = TemplateLinksTable::VIRTUAL_DOMAIN;
				break;
			default:
				ApiBase::dieDebug( __METHOD__, 'Unknown module name' );
		}

		parent::__construct( $query, $moduleName, $this->prefix );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->linksMigration = $linksMigration;
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
	 */
	private function run( $resultPageSet = null ) {
		$pages = $this->getPageSet()->getGoodPages();
		if ( $pages === [] ) {
			return; // nothing to do
		}

		$params = $this->extractRequestParams();

		$this->setVirtualDomain( $this->virtualdomain );

		if ( isset( $this->linksMigration::$mapping[$this->table] ) ) {
			[ $nsField, $titleField ] = $this->linksMigration->getTitleFields( $this->table );
			$queryInfo = $this->linksMigration->getQueryInfo( $this->table );
			$this->addTables( $queryInfo['tables'] );
			$this->addJoinConds( $queryInfo['joins'] );
		} else {
			$this->addTables( $this->table );
			$nsField = $this->prefix . '_namespace';
			$titleField = $this->prefix . '_title';
		}

		$this->addFields( [
			'pl_from' => $this->prefix . '_from',
			'pl_namespace' => $nsField,
			'pl_title' => $titleField,
		] );

		$this->addWhereFld( $this->prefix . '_from', array_keys( $pages ) );

		$multiNS = true;
		$multiTitle = true;
		if ( $params[$this->titlesParam] ) {
			// Filter the titles in PHP so our ORDER BY bug avoidance below works right.
			$filterNS = $params['namespace'] ? array_fill_keys( $params['namespace'], true ) : false;

			$lb = $this->linkBatchFactory->newLinkBatch();
			foreach ( $params[$this->titlesParam] as $t ) {
				$title = Title::newFromText( $t );
				if ( !$title || $title->isExternal() ) {
					$this->addWarning( [ 'apiwarn-invalidtitle', wfEscapeWikiText( $t ) ] );
				} elseif ( !$filterNS || isset( $filterNS[$title->getNamespace()] ) ) {
					$lb->addObj( $title );
				}
			}
			if ( $lb->isEmpty() ) {
				// No titles, no results!
				return;
			}
			$cond = $lb->constructSet( $this->prefix, $this->getDB() );
			$this->addWhere( $cond );
			$multiNS = count( $lb->data ) !== 1;
			$multiTitle = count( array_merge( ...$lb->data ) ) !== 1;
		} elseif ( $params['namespace'] ) {
			$this->addWhereFld( $nsField, $params['namespace'] );
			$multiNS = $params['namespace'] === null || count( $params['namespace'] ) !== 1;
		}

		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'int', 'int', 'string' ] );
			$op = $params['dir'] == 'descending' ? '<=' : '>=';
			$this->addWhere( $this->getDB()->buildComparison( $op, [
				"{$this->prefix}_from" => $cont[0],
				$nsField => $cont[1],
				$titleField => $cont[2],
			] ) );
		}

		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		// Here's some MySQL craziness going on: if you use WHERE foo='bar'
		// and later ORDER BY foo MySQL doesn't notice the ORDER BY is pointless
		// but instead goes and filesorts, because the index for foo was used
		// already. To work around this, we drop constant fields in the WHERE
		// clause from the ORDER BY clause
		$order = [];
		if ( count( $pages ) !== 1 ) {
			$order[] = $this->prefix . '_from' . $sort;
		}
		if ( $multiNS ) {
			$order[] = $nsField . $sort;
		}
		if ( $multiTitle ) {
			$order[] = $titleField . $sort;
		}
		if ( $order ) {
			$this->addOption( 'ORDER BY', $order );
		}
		$this->addOption( 'LIMIT', $params['limit'] + 1 );

		$res = $this->select( __METHOD__ );

		if ( $resultPageSet === null ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__, 'pl' );

			$count = 0;
			foreach ( $res as $row ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'continue',
						"{$row->pl_from}|{$row->pl_namespace}|{$row->pl_title}" );
					break;
				}
				$vals = [];
				ApiQueryBase::addTitleInfo( $vals, Title::makeTitle( $row->pl_namespace, $row->pl_title ) );
				$fit = $this->addPageSubItem( $row->pl_from, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue',
						"{$row->pl_from}|{$row->pl_namespace}|{$row->pl_title}" );
					break;
				}
			}
		} else {
			$titles = [];
			$count = 0;
			foreach ( $res as $row ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'continue',
						"{$row->pl_from}|{$row->pl_namespace}|{$row->pl_title}" );
					break;
				}
				$titles[] = Title::makeTitle( $row->pl_namespace, $row->pl_title );
			}
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'namespace' => [
				ParamValidator::PARAM_TYPE => 'namespace',
				ParamValidator::PARAM_ISMULTI => true,
				NamespaceDef::PARAM_EXTRA_NAMESPACES => [ NS_MEDIA, NS_SPECIAL ],
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			$this->titlesParam => [
				ParamValidator::PARAM_ISMULTI => true,
			],
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'ascending',
				ParamValidator::PARAM_TYPE => [
					'ascending',
					'descending'
				]
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		$name = $this->getModuleName();
		$path = $this->getModulePath();
		$title = Title::newMainPage()->getPrefixedText();
		$mp = rawurlencode( $title );

		return [
			"action=query&prop={$name}&titles={$mp}"
				=> "apihelp-{$path}-example-simple",
			"action=query&generator={$name}&titles={$mp}&prop=info"
				=> "apihelp-{$path}-example-generator",
			"action=query&prop={$name}&titles={$mp}&{$this->prefix}namespace=2|10"
				=> "apihelp-{$path}-example-namespaces",
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return $this->helpUrl;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryLinks::class, 'ApiQueryLinks' );
