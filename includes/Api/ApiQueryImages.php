<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Deferred\LinksUpdate\ImageLinksTable;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * This query adds an "<images>" subelement to all pages with the list of
 * images embedded into those pages.
 *
 * @ingroup API
 */
class ApiQueryImages extends ApiQueryGeneratorBase {

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		private readonly LinksMigration $linksMigration,
	) {
		parent::__construct( $query, $moduleName, 'im' );
	}

	public function execute() {
		$this->run();
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
		$queryInfo = $this->linksMigration->getQueryInfo( 'imagelinks' );

		$this->addTables( $queryInfo['tables'] );
		$this->addFields( [ 'il_from', 'lt_title' ] );
		$this->addJoinConds( $queryInfo['joins'] );

		$this->addWhereFld( 'il_from', array_keys( $pages ) );
		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'int', 'string' ] );
			$op = $params['dir'] == 'descending' ? '<=' : '>=';
			$comparison = [
				'il_from' => $cont[0],
				'lt_namespace' => NS_FILE,
				'lt_title' => $cont[1],
			];
			$this->addWhere( $this->getDB()->buildComparison( $op, $comparison ) );
		}

		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		// Don't order by il_from if it's constant in the WHERE clause
		if ( count( $pages ) === 1 ) {
			$this->addOption( 'ORDER BY', 'lt_title' . $sort );
		} else {
			$this->addOption( 'ORDER BY', [
				'il_from' . $sort,
				'lt_title' . $sort
			] );
		}
		$this->addOption( 'LIMIT', $params['limit'] + 1 );

		if ( $params['images'] ) {
			$images = [];
			foreach ( $params['images'] as $img ) {
				$title = Title::newFromText( $img );
				if ( !$title || $title->getNamespace() !== NS_FILE ) {
					$this->addWarning( [ 'apiwarn-notfile', wfEscapeWikiText( $img ) ] );
				} else {
					$images[] = $title->getDBkey();
				}
			}
			if ( !$images ) {
				// No titles so no results
				return;
			}
			$this->addWhereFld( 'lt_title', $images );
			$this->addWhereFld( 'lt_namespace', NS_FILE );
		}

		$this->setVirtualDomain( ImageLinksTable::VIRTUAL_DOMAIN );
		$res = $this->select( __METHOD__ );
		$this->resetVirtualDomain();

		if ( $resultPageSet === null ) {
			$count = 0;
			foreach ( $res as $row ) {
				if ( ++$count > $params['limit'] ) {
					// We've reached the one extra which shows that
					// there are additional pages to be had. Stop here...
					$this->setContinueEnumParameter( 'continue', $row->il_from . '|' . $row->lt_title );
					break;
				}
				$vals = [];
				ApiQueryBase::addTitleInfo( $vals, Title::makeTitle( NS_FILE, $row->lt_title ) );
				$fit = $this->addPageSubItem( $row->il_from, $vals );
				if ( !$fit ) {
					$this->setContinueEnumParameter( 'continue', $row->il_from . '|' . $row->lt_title );
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
					$this->setContinueEnumParameter( 'continue', $row->il_from . '|' . $row->lt_title );
					break;
				}
				$titles[] = Title::makeTitle( NS_FILE, $row->lt_title );
			}
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'public';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
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
			'images' => [
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
		$title = Title::newMainPage()->getPrefixedText();
		$mp = rawurlencode( $title );

		return [
			"action=query&prop=images&titles={$mp}"
				=> 'apihelp-query+images-example-simple',
			"action=query&generator=images&titles={$mp}&prop=info"
				=> 'apihelp-query+images-example-generator',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Images';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryImages::class, 'ApiQueryImages' );
