<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\HTMLForm\HTMLFormField;
use MediaWiki\MediaWikiServices;

/**
 * Wrapper for ChangeTags::buildTagFilterSelector to use in HTMLForm
 *
 * @stable to extend
 */
class HTMLTagFilter extends HTMLFormField {
	/** @var array|null */
	protected $tagFilter;

	/** @var bool */
	protected $activeOnly = ChangeTags::TAG_SET_ACTIVE_ONLY;

	/** @var bool */
	protected $useAllTags = ChangeTags::USE_ALL_TAGS;

	/**
	 * @inheritDoc
	 *
	 * Supported parameters:
	 * - activeOnly: true to filter for tags actively used (has hits), false for all
	 * - useAllTags: true to use all on-wiki tags, false to use software-defined tags only
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		if ( array_key_exists( 'activeOnly', $params ) ) {
			$this->activeOnly = $params['activeOnly'];
		}
		if ( array_key_exists( 'useAllTags', $params ) ) {
			$this->useAllTags = $params['useAllTags'];
		}
	}

	private function buildTagFilter( string $value, string $format ): bool {
		$this->tagFilter = MediaWikiServices::getInstance()->getChangeTagsFormatter()->buildTagFilter(
			$value,
			$format,
			$this->mParent->getContext(),
			$this->activeOnly,
			$this->useAllTags
		);
		return (bool)$this->tagFilter;
	}

	/** @inheritDoc */
	public function getTableRow( $value ) {
		if ( $this->buildTagFilter( $value, 'other' ) ) {
			return parent::getTableRow( $value );
		}
		return '';
	}

	/** @inheritDoc */
	public function getDiv( $value ) {
		if ( $this->buildTagFilter( $value, 'other' ) ) {
			return parent::getDiv( $value );
		}
		return '';
	}

	/** @inheritDoc */
	public function getOOUI( $value ) {
		if ( $this->buildTagFilter( $value, 'ooui' ) ) {
			return parent::getOOUI( $value );
		}
		return new \OOUI\FieldLayout( new \OOUI\Widget() );
	}

	/** @inheritDoc */
	public function getCodex( $value ) {
		if ( $this->buildTagFilter( $value, 'codex' ) ) {
			return parent::getCodex( $value );
		}
		return '';
	}

	/** @inheritDoc */
	public function getInputHTML( $value ) {
		if ( $this->tagFilter ) {
			// we only need the select field, HTMLForm should handle the label
			return $this->tagFilter[1];
		}
		return '';
	}

	/** @inheritDoc */
	public function getInputOOUI( $value ) {
		if ( $this->tagFilter ) {
			// we only need the select field, HTMLForm should handle the label
			return $this->tagFilter[1];
		}
		return '';
	}

	/** @inheritDoc */
	protected function shouldInfuseOOUI() {
		return true;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLTagFilter::class, 'HTMLTagFilter' );
