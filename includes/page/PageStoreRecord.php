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

use MWTimestamp;
use stdClass;
use Wikimedia\Assert\Assert;

/**
 * Immutable data record representing an editable page on a wiki.
 * Corresponds to a row in the page table.
 *
 * @since 1.36
 */
class PageStoreRecord extends PageIdentityValue implements ExistingPageRecord {

	/**
	 * Fields from the page table.
	 *
	 * @var stdClass
	 */
	private $row;

	/**
	 * The $row object must provide the following fields:
	 * - page_id: the page ID
	 * - page_namespace: the page's namespace
	 * - page_title: the page's title in normalized DB key form.
	 * - page_latest: the revision ID of the page's current revision
	 * - page_is_new: whether the page is new and only has one edit
	 * - page_is_redirect: whether the page is a redirect
	 * - page_touched: the time at which the page was last re-parsed
	 * - page_lang: the page's primary language (supply the content language if not known)
	 *
	 * @param stdClass $row A row from the page table
	 * @param string|bool $wikiId The Id of the wiki this page belongs to,
	 *        or self::LOCAL for the local wiki.
	 */
	public function __construct( stdClass $row, $wikiId ) {
		Assert::parameter( isset( $row->page_id ), '$row->page_id', 'is required' );
		Assert::parameter( isset( $row->page_namespace ), '$row->page_namespace', 'is required' );
		Assert::parameter( isset( $row->page_title ), '$row->page_title', 'is required' );
		Assert::parameter( isset( $row->page_latest ), '$row->page_latest', 'is required' );
		Assert::parameter( isset( $row->page_is_new ), '$row->page_is_new', 'is required' );
		Assert::parameter( isset( $row->page_is_redirect ), '$row->page_is_redirect', 'is required' );
		Assert::parameter( isset( $row->page_touched ), '$row->page_touched', 'is required' );
		Assert::parameter( isset( $row->page_lang ), '$row->page_lang', 'is required' );

		Assert::parameter( $row->page_id > 0, '$pageId', 'must be greater than zero (page must exist)' );

		parent::__construct( $row->page_id, $row->page_namespace, $row->page_title, $wikiId );

		$this->row = $row;
	}

	/**
	 * False if the page has had more than one edit.
	 *
	 * @return bool
	 */
	public function isNew(): bool {
		return (bool)$this->row->page_is_new;
	}

	/**
	 * True if the page is a redirect.
	 *
	 * @return bool
	 */
	public function isRedirect(): bool {
		return (bool)$this->row->page_is_redirect;
	}

	/**
	 * The ID of the page'S latest revision.
	 *
	 * @param bool $wikiId
	 *
	 * @return int
	 */
	public function getLatest( $wikiId = self::LOCAL ): int {
		$this->assertWiki( $wikiId );
		return (int)$this->row->page_latest;
	}

	/**
	 * Timestamp at which the page was last rerendered.
	 *
	 * @return string
	 */
	public function getTouched(): string {
		return MWTimestamp::convert( TS_MW, $this->row->page_touched );
	}

	/**
	 * Language in which the page is written.
	 *
	 * @return string
	 */
	public function getLanguage(): string {
		return (string)$this->row->page_lang;
	}

}
