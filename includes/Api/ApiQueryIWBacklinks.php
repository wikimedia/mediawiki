<?php
/**
 * API for MediaWiki 1.17+
 *
 * Copyright © 2010 Sam Reed
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Deferred\LinksUpdate\InterwikiLinksTable;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * This gives links pointing to the given interwiki
 * @ingroup API
 */
class ApiQueryIWBacklinks extends ApiQueryGeneratorBase {

	public function __construct( ApiQuery $query, string $moduleName ) {
		parent::__construct( $query, $moduleName, 'iwbl' );
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
	 * @return void
	 */
	public function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();

		if ( isset( $params['title'] ) && !isset( $params['prefix'] ) ) {
			$this->dieWithError(
				[
					'apierror-invalidparammix-mustusewith',
					$this->encodeParamName( 'title' ),
					$this->encodeParamName( 'prefix' ),
				],
				'invalidparammix'
			);
		}

		$this->setVirtualDomain( InterwikiLinksTable::VIRTUAL_DOMAIN );
		$db = $this->getDB();

		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'string', 'string', 'int' ] );
			$op = $params['dir'] == 'descending' ? '<=' : '>=';
			$this->addWhere( $db->buildComparison( $op, [
				'iwl_prefix' => $cont[0],
				'iwl_title' => $cont[1],
				'iwl_from' => $cont[2],
			] ) );
		}

		$prop = array_fill_keys( $params['prop'], true );
		$iwprefix = isset( $prop['iwprefix'] );
		$iwtitle = isset( $prop['iwtitle'] );

		$this->addTables( [ 'iwlinks', 'page' ] );
		$this->addWhere( 'iwl_from = page_id' );

		$this->addFields( [ 'page_id', 'page_title', 'page_namespace', 'page_is_redirect',
			'iwl_from', 'iwl_prefix', 'iwl_title' ] );

		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		if ( isset( $params['prefix'] ) ) {
			$this->addWhereFld( 'iwl_prefix', $params['prefix'] );
			if ( isset( $params['title'] ) ) {
				$this->addWhereFld( 'iwl_title', $params['title'] );
				$this->addOption( 'ORDER BY', 'iwl_from' . $sort );
			} else {
				$this->addOption( 'ORDER BY', [
					'iwl_title' . $sort,
					'iwl_from' . $sort
				] );
			}
		} else {
			$this->addOption( 'ORDER BY', [
				'iwl_prefix' . $sort,
				'iwl_title' . $sort,
				'iwl_from' . $sort
			] );
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );

		$res = $this->select( __METHOD__ );

		$pages = [];

		$count = 0;
		$result = $this->getResult();

		if ( $resultPageSet === null ) {
			$this->executeGenderCacheFromResultWrapper( $res, __METHOD__ );
		}

		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				// Continue string preserved in case the redirect query doesn't
				// pass the limit
				$this->setContinueEnumParameter(
					'continue',
					"{$row->iwl_prefix}|{$row->iwl_title}|{$row->iwl_from}"
				);
				break;
			}

			if ( $resultPageSet !== null ) {
				$pages[] = Title::newFromRow( $row );
			} else {
				$entry = [ 'pageid' => (int)$row->page_id ];

				$title = Title::makeTitle( $row->page_namespace, $row->page_title );
				ApiQueryBase::addTitleInfo( $entry, $title );

				if ( $row->page_is_redirect ) {
					$entry['redirect'] = true;
				}

				if ( $iwprefix ) {
					$entry['iwprefix'] = $row->iwl_prefix;
				}

				if ( $iwtitle ) {
					$entry['iwtitle'] = $row->iwl_title;
				}

				$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $entry );
				if ( !$fit ) {
					$this->setContinueEnumParameter(
						'continue',
						"{$row->iwl_prefix}|{$row->iwl_title}|{$row->iwl_from}"
					);
					break;
				}
			}
		}

		if ( $resultPageSet === null ) {
			$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'iw' );
		} else {
			$resultPageSet->populateFromTitles( $pages );
		}
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'public';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'prefix' => null,
			'title' => null,
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEFAULT => '',
				ParamValidator::PARAM_TYPE => [
					'iwprefix',
					'iwtitle',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
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
		return [
			'action=query&list=iwbacklinks&iwbltitle=Test&iwblprefix=wikibooks'
				=> 'apihelp-query+iwbacklinks-example-simple',
			'action=query&generator=iwbacklinks&giwbltitle=Test&giwblprefix=wikibooks&prop=info'
				=> 'apihelp-query+iwbacklinks-example-generator',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Iwbacklinks';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryIWBacklinks::class, 'ApiQueryIWBacklinks' );
