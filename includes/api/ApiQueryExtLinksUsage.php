<?php

/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Deferred\LinksUpdate\ExternalLinksTable;
use MediaWiki\ExternalLinks\LinkFilter;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Parser;
use MediaWiki\Title\Title;
use MediaWiki\Utils\UrlUtils;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

/**
 * @ingroup API
 */
class ApiQueryExtLinksUsage extends ApiQueryGeneratorBase {

	private UrlUtils $urlUtils;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		UrlUtils $urlUtils
	) {
		parent::__construct( $query, $moduleName, 'eu' );

		$this->urlUtils = $urlUtils;
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
		$params = $this->extractRequestParams();
		$db = $this->getDB();

		$query = $params['query'];
		$protocol = LinkFilter::getProtocolPrefix( $params['protocol'] );

		$this->addTables( [ 'externallinks', 'page' ] );
		$this->addJoinConds( [ 'page' => [ 'JOIN', 'page_id=el_from' ] ] );
		$fields = [ 'el_to_domain_index', 'el_to_path' ];

		$miser_ns = [];
		if ( $this->getConfig()->get( MainConfigNames::MiserMode ) ) {
			$miser_ns = $params['namespace'] ?: [];
		} else {
			$this->addWhereFld( 'page_namespace', $params['namespace'] );
		}
		if ( $query !== null && $query !== '' ) {
			// Normalize query to match the normalization applied for the externallinks table
			$query = Parser::normalizeLinkUrl( $query );
			$conds = LinkFilter::getQueryConditions( $query, [
				'protocol' => $protocol,
				'oneWildcard' => true,
				'db' => $db
			] );
			if ( !$conds ) {
				$this->dieWithError( 'apierror-badquery' );
			}
			$this->addWhere( $conds );
		} else {
			if ( $protocol !== null ) {
				$this->addWhere(
					$db->expr( 'el_to_domain_index', IExpression::LIKE, new LikeValue( "$protocol", $db->anyString() ) )
				);
			}
		}
		$orderBy = [ 'el_id' ];

		$this->addOption( 'ORDER BY', $orderBy );
		$this->addFields( $orderBy ); // Make sure

		$prop = array_fill_keys( $params['prop'], true );
		$fld_ids = isset( $prop['ids'] );
		$fld_title = isset( $prop['title'] );
		$fld_url = isset( $prop['url'] );

		if ( $resultPageSet === null ) {
			$this->addFields( [
				'page_id',
				'page_namespace',
				'page_title'
			] );
			foreach ( $fields as $field ) {
				$this->addFieldsIf( $field, $fld_url );
			}
		} else {
			$this->addFields( $resultPageSet->getPageTableFields() );
		}

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );

		// T244254: Avoid MariaDB deciding to scan all of `page`.
		$this->addOption( 'STRAIGHT_JOIN' );

		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'],
				array_fill( 0, count( $orderBy ), 'string' ) );
			$conds = array_combine( $orderBy, array_map( 'rawurldecode', $cont ) );
			$this->addWhere( $db->buildComparison( '>=', $conds ) );
		}

		$this->setVirtualDomain( ExternalLinksTable::VIRTUAL_DOMAIN );
		$res = $this->select( __METHOD__ );
		$this->resetVirtualDomain();

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
					$to = LinkFilter::reverseIndexes( $row->el_to_domain_index ) . $row->el_to_path;
					// expand protocol-relative urls
					if ( $params['expandurl'] ) {
						$to = (string)$this->urlUtils->expand( $to, PROTO_CANONICAL );
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

	private function setContinue( array $orderBy, \stdClass $row ) {
		$fields = [];
		foreach ( $orderBy as $field ) {
			$fields[] = strtr( $row->$field, [ '%' => '%25', '|' => '%7C' ] );
		}
		$this->setContinueEnumParameter( 'continue', implode( '|', $fields ) );
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		$ret = [
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEFAULT => 'ids|title|url',
				ParamValidator::PARAM_TYPE => [
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
				ParamValidator::PARAM_TYPE => LinkFilter::prepareProtocols(),
				ParamValidator::PARAM_DEFAULT => '',
			],
			'query' => null,
			'namespace' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => 'namespace'
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'expandurl' => [
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_DEPRECATED => true,
			],
		];

		if ( $this->getConfig()->get( MainConfigNames::MiserMode ) ) {
			$ret['namespace'][ApiBase::PARAM_HELP_MSG_APPEND] = [
				'api-help-param-limited-in-miser-mode',
			];
		}

		return $ret;
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=exturlusage&euquery=www.mediawiki.org'
				=> 'apihelp-query+exturlusage-example-simple',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Exturlusage';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryExtLinksUsage::class, 'ApiQueryExtLinksUsage' );
