<?php

namespace MediaWiki\Cdn;
use Wikimedia\Assert\Assert;

/**
 * CdnResourceSet
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class CdnResourceSet {

	/**
	 * @var string[] URLs to be purged regardless of xkey support
	 */
	private $untaggedUrls = [];

	/**
	 * @var string[]  URLs to be purged unless xkey support is enabled.
	 */
	private $taggedUrls = [];

	/**
	 * @var string[] Keys to be purged, if xkey support is enabled.
	 */
	private $keys = [];

	/**
	 * @param string[] $urls URLs to be purged regardless of xkey support
	 */
	public function addUntaggedUrls( array $urls ) {
		Assert::parameterElementType( 'string', $urls, '$urls' );
		$this->untaggedUrls = array_merge( $this->untaggedUrls, $urls );
	}

	/**
	 * @param string[] $urls URLs to be purged unless xkey support is enabled.
	 */
	public function addTaggedUrls( array $urls ) {
		Assert::parameterElementType( 'string', $urls, '$urls' );
		$this->taggedUrls = array_merge( $this->taggedUrls, $urls );
	}

	/**
	 * @param string[] $keys Keys to be purged, if xkey support is enabled.
	 */
	public function addKeys( array $keys ) {
		Assert::parameterElementType( 'string', $keys, '$keys' );
		$this->keys = array_merge( $this->keys, $keys );
	}

	/**
	 * @return string[]
	 */
	public function getUntaggedUrls() {
		return $this->untaggedUrls;
	}

	/**
	 * @return string[]
	 */
	public function getTaggedUrls() {
		return $this->taggedUrls;
	}

	/**
	 * @return string[]
	 */
	public function getKeys() {
		return $this->keys;
	}

}
