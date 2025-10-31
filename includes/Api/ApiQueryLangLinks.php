<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use MediaWiki\Utils\UrlUtils;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * A query module to list all langlinks (links to corresponding foreign language pages).
 *
 * @ingroup API
 */
class ApiQueryLangLinks extends ApiQueryBase {

	private LanguageNameUtils $languageNameUtils;
	private Language $contentLanguage;
	private UrlUtils $urlUtils;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		LanguageNameUtils $languageNameUtils,
		Language $contentLanguage,
		UrlUtils $urlUtils
	) {
		parent::__construct( $query, $moduleName, 'll' );
		$this->languageNameUtils = $languageNameUtils;
		$this->contentLanguage = $contentLanguage;
		$this->urlUtils = $urlUtils;
	}

	public function execute() {
		$pages = $this->getPageSet()->getGoodPages();
		if ( $pages === [] ) {
			return;
		}

		$params = $this->extractRequestParams();
		$prop = array_fill_keys( (array)$params['prop'], true );

		if ( isset( $params['title'] ) && !isset( $params['lang'] ) ) {
			$this->dieWithError(
				[
					'apierror-invalidparammix-mustusewith',
					$this->encodeParamName( 'title' ),
					$this->encodeParamName( 'lang' ),
				],
				'invalidparammix'
			);
		}

		// Handle deprecated param
		$this->requireMaxOneParameter( $params, 'url', 'prop' );
		if ( $params['url'] ) {
			$prop = [ 'url' => 1 ];
		}

		$this->addFields( [
			'll_from',
			'll_lang',
			'll_title'
		] );

		$this->addTables( 'langlinks' );
		$this->addWhereFld( 'll_from', array_keys( $pages ) );
		if ( $params['continue'] !== null ) {
			$db = $this->getDB();
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'int', 'string' ] );
			$op = $params['dir'] == 'descending' ? '<=' : '>=';
			$this->addWhere( $db->buildComparison( $op, [
				'll_from' => $cont[0],
				'll_lang' => $cont[1],
			] ) );
		}

		// FIXME: (follow-up) To allow extensions to add to the language links, we need
		//       to load them all, add the extra links, then apply paging.
		//       Should not be terrible, it's not going to be more than a few hundred links.

		// Note that, since (ll_from, ll_lang) is a unique key, we don't need
		// to sort by ll_title to ensure deterministic ordering.
		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		if ( isset( $params['lang'] ) ) {
			$this->addWhereFld( 'll_lang', $params['lang'] );
			if ( isset( $params['title'] ) ) {
				$this->addWhereFld( 'll_title', $params['title'] );
			}
			$this->addOption( 'ORDER BY', 'll_from' . $sort );
		} else {
			// Don't order by ll_from if it's constant in the WHERE clause
			if ( count( $pages ) === 1 ) {
				$this->addOption( 'ORDER BY', 'll_lang' . $sort );
			} else {
				$this->addOption( 'ORDER BY', [
					'll_from' . $sort,
					'll_lang' . $sort
				] );
			}
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$res = $this->select( __METHOD__ );

		$count = 0;
		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that
				// there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', "{$row->ll_from}|{$row->ll_lang}" );
				break;
			}

			$languageNameMap = $this->getConfig()->get( MainConfigNames::InterlanguageLinkCodeMap );
			$displayLanguageCode = $languageNameMap[ $row->ll_lang ] ?? $row->ll_lang;

			// This is potentially risky and confusing (request `no`, but get `nb` in the result).
			$entry = [ 'lang' => $displayLanguageCode ];
			if ( isset( $prop['url'] ) ) {
				$title = Title::newFromText( "{$row->ll_lang}:{$row->ll_title}" );
				if ( $title ) {
					$entry['url'] = (string)$this->urlUtils->expand( $title->getFullURL(), PROTO_CURRENT );
				}
			}

			if ( isset( $prop['langname'] ) ) {
				$entry['langname'] = $this->languageNameUtils
					->getLanguageName( $displayLanguageCode, $params['inlanguagecode'] );
			}
			if ( isset( $prop['autonym'] ) ) {
				$entry['autonym'] = $this->languageNameUtils->getLanguageName( $displayLanguageCode );
			}
			ApiResult::setContentValue( $entry, 'title', $row->ll_title );
			$fit = $this->addPageSubItem( $row->ll_from, $entry );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', "{$row->ll_from}|{$row->ll_lang}" );
				break;
			}
		}
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'public';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => [
					'url',
					'langname',
					'autonym',
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'lang' => null,
			'title' => null,
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'ascending',
				ParamValidator::PARAM_TYPE => [
					'ascending',
					'descending'
				]
			],
			'inlanguagecode' => $this->contentLanguage->getCode(),
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
			'url' => [
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
			"action=query&prop=langlinks&titles={$mp}&redirects="
				=> 'apihelp-query+langlinks-example-simple',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Langlinks';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryLangLinks::class, 'ApiQueryLangLinks' );
