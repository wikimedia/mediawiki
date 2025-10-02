<?php
/**
 * Copyright © 2009
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\ChangeTags\ChangeTagsStore;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * Query module to enumerate change tags.
 *
 * @ingroup API
 */
class ApiQueryTags extends ApiQueryBase {

	private ChangeTagsStore $changeTagsStore;

	public function __construct( ApiQuery $query, string $moduleName, ChangeTagsStore $changeTagsStore ) {
		parent::__construct( $query, $moduleName, 'tg' );
		$this->changeTagsStore = $changeTagsStore;
	}

	public function execute() {
		$params = $this->extractRequestParams();

		$prop = array_fill_keys( $params['prop'], true );

		$fld_displayname = isset( $prop['displayname'] );
		$fld_description = isset( $prop['description'] );
		$fld_hitcount = isset( $prop['hitcount'] );
		$fld_defined = isset( $prop['defined'] );
		$fld_source = isset( $prop['source'] );
		$fld_active = isset( $prop['active'] );

		$limit = $params['limit'];
		$result = $this->getResult();

		$softwareDefinedTags = array_fill_keys( $this->changeTagsStore->listSoftwareDefinedTags(), 0 );
		$explicitlyDefinedTags = array_fill_keys( $this->changeTagsStore->listExplicitlyDefinedTags(), 0 );
		$softwareActivatedTags = array_fill_keys( $this->changeTagsStore->listSoftwareActivatedTags(), 0 );

		$tagHitcounts = array_merge(
			$softwareDefinedTags,
			$explicitlyDefinedTags,
			$this->changeTagsStore->tagUsageStatistics()
		);
		$tags = array_keys( $tagHitcounts );

		# Fetch defined tags that aren't past the continuation
		if ( $params['continue'] !== null ) {
			$cont = $params['continue'];
			$tags = array_filter( $tags, static function ( $v ) use ( $cont ) {
				return $v >= $cont;
			} );
		}

		# Now make sure the array is sorted for proper continuation
		sort( $tags );

		$count = 0;
		foreach ( $tags as $tagName ) {
			if ( ++$count > $limit ) {
				$this->setContinueEnumParameter( 'continue', $tagName );
				break;
			}

			$tag = [];
			$tag['name'] = $tagName;

			if ( $fld_displayname ) {
				$tag['displayname'] = ChangeTags::tagDescription( $tagName, $this );
			}

			if ( $fld_description ) {
				$msg = $this->msg( "tag-$tagName-description" );
				$tag['description'] = $msg->exists() ? $msg->text() : '';
			}

			if ( $fld_hitcount ) {
				$tag['hitcount'] = (int)$tagHitcounts[$tagName];
			}

			$isSoftware = isset( $softwareDefinedTags[$tagName] );
			$isExplicit = isset( $explicitlyDefinedTags[$tagName] );

			if ( $fld_defined ) {
				$tag['defined'] = $isSoftware || $isExplicit;
			}

			if ( $fld_source ) {
				$tag['source'] = [];
				if ( $isSoftware ) {
					$tag['source'][] = 'software';
					// @TODO: remove backwards compatibility entry (T247552)
					$tag['source'][] = 'extension';
				}
				if ( $isExplicit ) {
					$tag['source'][] = 'manual';
				}
			}

			if ( $fld_active ) {
				$tag['active'] = $isExplicit || isset( $softwareActivatedTags[$tagName] );
			}

			$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $tag );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', $tagName );
				break;
			}
		}

		$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'tag' );
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'public';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
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
				ParamValidator::PARAM_DEFAULT => '',
				ParamValidator::PARAM_TYPE => [
					'displayname',
					'description',
					'hitcount',
					'defined',
					'source',
					'active',
				],
				ParamValidator::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			]
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=tags&tgprop=displayname|description|hitcount|defined'
				=> 'apihelp-query+tags-example-simple',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Tags';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryTags::class, 'ApiQueryTags' );
