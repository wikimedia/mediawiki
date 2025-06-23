<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Page;

use InvalidArgumentException;
use Wikimedia\Assert\Assert;

/**
 * Immutable value object representing a page identity.
 *
 * Instances of this class are expected to always represent proper pages, that is,
 * pages that can at least potentially exist as editable pages on the wiki.
 * This class cannot represent Special pages, interwiki links, section links, etc.
 *
 * Code that deserializes instances of PageIdentityValue must ensure that the original
 * meaning of the "local" Wiki ID is preserved: When an instance of PageIdentityValue
 * is created with self::LOCAL as the Wiki ID on one wiki, gets serialized,
 * stored, and later read and unserialized on another wiki, the value of the Wiki ID
 * must be adjusted to refer to the original wiki.
 *
 * @see https://www.mediawiki.org/wiki/Manual:Modeling_pages
 *
 * @since 1.36
 */
class PageIdentityValue extends PageReferenceValue implements ProperPageIdentity {

	/** @var int */
	private $pageId;

	/**
	 * Constructs a PageIdentityValue, or returns null if the parameters are not valid.
	 *
	 * @note This does not perform any normalization, and only basic validation.
	 * For full normalization and validation, use TitleParser::makeTitleValueSafe()
	 * together with PageLookup::getPageForLink().
	 *
	 * @param int $pageId The ID of this page, or 0 if the page does not exist.
	 * @param int $namespace A valid namespace ID. Validation is the caller's responsibility!
	 * @param string $dbKey A valid DB key. Validation is the caller's responsibility!
	 * @param string|false $wikiId The Id of the wiki this page belongs to,
	 *        or self::LOCAL for the local wiki. The method {@link PageIdentityValue::localIdentity}
	 *        is available as a shorthand for local wikis (only requires 3 parameters).
	 *
	 * @return PageIdentityValue|null
	 */
	public static function tryNew( int $pageId, int $namespace, string $dbKey, $wikiId ) {
		try {
			return new static( $pageId, $namespace, $dbKey, $wikiId );
		} catch ( InvalidArgumentException ) {
			return null;
		}
	}

	/**
	 * @param int $pageId The ID of this page, or 0 if the page does not exist.
	 * @param int $namespace A valid namespace ID. Validation is the caller's responsibility!
	 * @param string $dbKey A valid DB key. Validation is the caller's responsibility!
	 * @param string|false $wikiId The Id of the wiki this page belongs to,
	 *        or self::LOCAL for the local wiki.
	 */
	public function __construct( int $pageId, int $namespace, string $dbKey, $wikiId ) {
		Assert::parameter( $pageId >= 0, '$pageId', 'must not be negative' );
		Assert::parameter( $namespace >= 0, '$namespace', 'must not be negative' );

		// Not full validation, intended to help detect lack of validation in the caller.
		Assert::parameter(
			!preg_match( '/[#|]/', $dbKey ),
			'$dbKey',
			'must not contain pipes or hashes: ' . $dbKey
		);

		parent::__construct( $namespace, $dbKey, $wikiId );

		$this->pageId = $pageId;
	}

	/**
	 * Create PageIdentity for a local page.
	 *
	 * @param int $pageId
	 * @param int $namespace
	 * @param string $dbKey
	 * @return PageIdentityValue
	 */
	public static function localIdentity( int $pageId, int $namespace, string $dbKey ): self {
		return new self( $pageId, $namespace, $dbKey, self::LOCAL );
	}

	/**
	 * The numerical page ID provided to the constructor.
	 *
	 * @param string|false $wikiId The wiki ID expected by the caller.
	 *        Omit if expecting the local wiki.
	 *
	 * @return int
	 */
	public function getId( $wikiId = self::LOCAL ): int {
		$this->assertWiki( $wikiId );
		return $this->pageId;
	}

	/**
	 * Returns whether the page currently exists.
	 * Returns true if getId() returns a value greater than zero.
	 */
	public function exists(): bool {
		return $this->getId( $this->getWikiId() ) > 0;
	}

	/**
	 * @return bool always true
	 */
	public function canExist(): bool {
		return true;
	}

}
