<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\HTMLForm\HTMLFormField;

/**
 * Wrapper for ChangeTags::buildTagFilterSelector to use in HTMLForm
 *
 * @stable to extend
 */
class HTMLTagFilter extends HTMLFormField {
	/** @var array */
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

	public function getTableRow( $value ) {
		$this->tagFilter = ChangeTags::buildTagFilterSelector(
			$value,
			false,
			$this->mParent->getContext(),
			$this->activeOnly,
			$this->useAllTags
		);
		if ( $this->tagFilter ) {
			return parent::getTableRow( $value );
		}
		return '';
	}

	public function getDiv( $value ) {
		$this->tagFilter = ChangeTags::buildTagFilterSelector(
			$value,
			false,
			$this->mParent->getContext(),
			$this->activeOnly,
			$this->useAllTags
		);
		if ( $this->tagFilter ) {
			return parent::getDiv( $value );
		}
		return '';
	}

	public function getOOUI( $value ) {
		$this->tagFilter = ChangeTags::buildTagFilterSelector(
			$value,
			true,
			$this->mParent->getContext(),
			$this->activeOnly,
			$this->useAllTags
		);
		if ( $this->tagFilter ) {
			return parent::getOOUI( $value );
		}
		return new \OOUI\FieldLayout( new \OOUI\Widget() );
	}

	public function getInputHTML( $value ) {
		if ( $this->tagFilter ) {
			// we only need the select field, HTMLForm should handle the label
			return $this->tagFilter[1];
		}
		return '';
	}

	public function getInputOOUI( $value ) {
		if ( $this->tagFilter ) {
			// we only need the select field, HTMLForm should handle the label
			return $this->tagFilter[1];
		}
		return '';
	}

	protected function shouldInfuseOOUI() {
		return true;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLTagFilter::class, 'HTMLTagFilter' );
