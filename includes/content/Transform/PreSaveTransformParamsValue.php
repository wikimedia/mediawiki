<?php
namespace MediaWiki\Content\Transform;

use MediaWiki\Page\PageReference;
use MediaWiki\User\UserIdentity;
use ParserOptions;

/**
 * @internal
 * An object to hold pre-save transform params.
 */
class PreSaveTransformParamsValue implements PreSaveTransformParams {
	/** @var PageReference */
	private $page;

	/** @var UserIdentity */
	private $user;

	/** @var ParserOptions */
	private $parserOptions;

	public function __construct( PageReference $page, UserIdentity $user, ParserOptions $parserOptions ) {
		$this->page = $page;
		$this->user = $user;
		$this->parserOptions = $parserOptions;
	}

	/**
	 *
	 * @return PageReference
	 */
	public function getPage(): PageReference {
		return $this->page;
	}

	/**
	 *
	 * @return UserIdentity
	 */
	public function getUser(): UserIdentity {
		return $this->user;
	}

	/**
	 *
	 * @return ParserOptions
	 */
	public function getParserOptions(): ParserOptions {
		return $this->parserOptions;
	}
}
