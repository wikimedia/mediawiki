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
use MediaWiki\Parser\Parser;
use MediaWiki\Title\Title;
use MediaWiki\Utils\UrlUtils;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

/**
 * A query module to list all external URLs found on a given set of pages.
 *
 * @ingroup API
 */
class ApiQueryExternalLinks extends ApiQueryBase {

	private UrlUtils $urlUtils;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		UrlUtils $urlUtils
	) {
		parent::__construct( $query, $moduleName, 'el' );

		$this->urlUtils = $urlUtils;
	}

	public function execute() {
		$pages = $this->getPageSet()->getGoodPages();
		if ( $pages === [] ) {
			return;
		}

		$params = $this->extractRequestParams();
		$db = $this->getDB();

		$query = $params['query'];
		$protocol = LinkFilter::getProtocolPrefix( $params['protocol'] );

		$fields = [ 'el_from' ];
		$fields[] = 'el_to_domain_index';
		$fields[] = 'el_to_path';
		$continueField = 'el_to_domain_index';
		$this->addFields( $fields );

		$this->addTables( 'externallinks' );
		$this->addWhereFld( 'el_from', array_keys( $pages ) );

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
					$db->expr( $continueField, IExpression::LIKE, new LikeValue( "$protocol", $db->anyString() ) )
				);
			}
		}

		$orderBy = [ 'el_id' ];

		$this->addOption( 'ORDER BY', $orderBy );
		$this->addFields( $orderBy ); // Make sure

		$this->addOption( 'LIMIT', $params['limit'] + 1 );

		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'],
				array_fill( 0, count( $orderBy ), 'string' ) );
			$conds = array_combine( $orderBy, array_map( 'rawurldecode', $cont ) );
			$this->addWhere( $db->buildComparison( '>=', $conds ) );
		}

		$this->setVirtualDomain( ExternalLinksTable::VIRTUAL_DOMAIN );
		$res = $this->select( __METHOD__ );
		$this->resetVirtualDomain();

		$count = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that
				// there are additional pages to be had. Stop here...
				$this->setContinue( $orderBy, $row );
				break;
			}
			$entry = [];
			$to = LinkFilter::reverseIndexes( $row->el_to_domain_index ) . $row->el_to_path;
			// expand protocol-relative urls
			if ( $params['expandurl'] ) {
				$to = (string)$this->urlUtils->expand( $to, PROTO_CANONICAL );
			}
			ApiResult::setContentValue( $entry, 'url', $to );
			$fit = $this->addPageSubItem( $row->el_from, $entry );
			if ( !$fit ) {
				$this->setContinue( $orderBy, $row );
				break;
			}
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
			'protocol' => [
				ParamValidator::PARAM_TYPE => LinkFilter::prepareProtocols(),
				ParamValidator::PARAM_DEFAULT => '',
			],
			'query' => null,
			'expandurl' => [
				ParamValidator::PARAM_TYPE => 'boolean',
				ParamValidator::PARAM_DEFAULT => false,
				ParamValidator::PARAM_DEPRECATED => true,
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		$title = Title::newMainPage()->getPrefixedText();
		$mp = rawurlencode( $title );

		return [
			"action=query&prop=extlinks&titles={$mp}"
				=> 'apihelp-query+extlinks-example-simple',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Extlinks';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryExternalLinks::class, 'ApiQueryExternalLinks' );
