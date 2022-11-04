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
 *
 * Corresponds to a row in the page table.
 *
 * @since 1.36
 */
class PageStoreRecord extends PageIdentityValue implements ExistingPageRecord {

	/**
	 * Fields that must be present in the row object passed to the constructor.
	 * Note that page_lang is optional, so it is not included here.
	 *
	 * @since 1.37
	 */
	public const REQUIRED_FIELDS = [
		'page_id',
		'page_namespace',
		'page_title',
		'page_is_redirect',
		'page_is_new',
		'page_latest',
		'page_touched',
	];

	/**
	 * Fields from the page table.
	 *
	 * @var stdClass
	 */
	private $row;

	/**
	 * The $row object must provide all fields listed in PageStoreRecord::REQUIRED_FIELDS.
	 *
	 * @param stdClass $row A row from the page table
	 * @param string|false $wikiId The Id of the wiki this page belongs to,
	 *        or self::LOCAL for the local wiki.
	 */
	public function __construct( stdClass $row, $wikiId ) {
		foreach ( self::REQUIRED_FIELDS as $field ) {
			Assert::parameter( isset( $row->$field ), '$row->' . $field, 'is required' );
		}

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
	 * @param string|false $wikiId
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
	 * @return ?string
	 */
	public function getLanguage(): ?string {
		return $this->getField( 'page_lang' );
	}

	/**
	 * Return the raw value for the given field as returned by the database query.
	 *
	 * Numeric values may be encoded as strings.
	 * Boolean values may be represented as integers (or numeric strings).
	 * Timestamps will use the database's native format.
	 *
	 * @internal
	 *
	 * @param string $field
	 *
	 * @return string|int|bool|null
	 */
	public function getField( string $field ) {
		// Field may be missing entirely.
		return $this->row->$field ?? null;
	}

}
