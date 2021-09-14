<?php
namespace MediaWiki\Content\Transform;

use MediaWiki\Page\PageReference;
use MediaWiki\User\UserIdentity;
use ParserOptions;

/**
 * @since 1.37
 * An interface to hold pre-save transform params.
 */
interface PreSaveTransformParams {

	/**
	 * @return PageReference
	 */
	public function getPage(): PageReference;

	/**
	 * @return UserIdentity
	 */
	public function getUser(): UserIdentity;

	/**
	 * @return ParserOptions
	 */
	public function getParserOptions(): ParserOptions;
}
