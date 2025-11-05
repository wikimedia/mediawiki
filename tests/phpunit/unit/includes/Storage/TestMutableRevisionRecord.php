<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\MutableRevisionRecord;

class TestMutableRevisionRecord extends MutableRevisionRecord {
	/** @var string|null */
	private $sha1;

	/**
	 * @param PageIdentity $page
	 * @param int $wikiId
	 * @param string|null $sha1
	 */
	public function __construct( $page, $wikiId = self::LOCAL, ?string $sha1 = null ) {
		parent::__construct( $page, $wikiId );
		$this->sha1 = $sha1;
	}

	/**
	 * @return string|null
	 */
	public function getSha1() {
		return $this->sha1 ?? parent::getSha1();
	}
}
