<?php
namespace MediaWiki\Content\Transform;

use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\User\UserIdentity;

/**
 * @since 1.37
 * An interface to hold pre-save transform params.
 */
interface PreSaveTransformParams {

	public function getPage(): PageReference;

	public function getUser(): UserIdentity;

	public function getParserOptions(): ParserOptions;
}
