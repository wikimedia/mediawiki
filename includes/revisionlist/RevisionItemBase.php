<?php
/**
 * Holders of revision list for a single page
 *
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

use MediaWiki\MediaWikiServices;

/**
 * Abstract base class for revision items
 */
abstract class RevisionItemBase {
	/** @var RevisionListBase The parent */
	protected $list;

	/** The database result row */
	protected $row;

	/**
	 * @param RevisionListBase $list
	 * @param object $row DB result row
	 */
	public function __construct( $list, $row ) {
		$this->list = $list;
		$this->row = $row;
	}

	/**
	 * Get the DB field name associated with the ID list.
	 * Override this function.
	 * @return null
	 */
	public function getIdField() {
		return null;
	}

	/**
	 * Get the DB field name storing timestamps.
	 * Override this function.
	 * @return bool
	 */
	public function getTimestampField() {
		return false;
	}

	/**
	 * Get the DB field name storing user ids.
	 * Override this function.
	 * @return bool
	 */
	public function getAuthorIdField() {
		return false;
	}

	/**
	 * Get the DB field name storing user names.
	 * Override this function.
	 * @return bool
	 */
	public function getAuthorNameField() {
		return false;
	}

	/**
	 * Get the DB field name storing actor ids.
	 * Override this function.
	 * @since 1.31
	 * @return bool
	 */
	public function getAuthorActorField() {
		return false;
	}

	/**
	 * Get the ID, as it would appear in the ids URL parameter
	 * @return int
	 */
	public function getId() {
		$field = $this->getIdField();
		return $this->row->$field;
	}

	/**
	 * Get the date, formatted in user's language
	 * @return string
	 */
	public function formatDate() {
		return $this->list->getLanguage()->userDate( $this->getTimestamp(),
			$this->list->getUser() );
	}

	/**
	 * Get the time, formatted in user's language
	 * @return string
	 */
	public function formatTime() {
		return $this->list->getLanguage()->userTime( $this->getTimestamp(),
			$this->list->getUser() );
	}

	/**
	 * Get the timestamp in MW 14-char form
	 * @return mixed
	 */
	public function getTimestamp() {
		$field = $this->getTimestampField();
		return wfTimestamp( TS_MW, $this->row->$field );
	}

	/**
	 * Get the author user ID
	 * @return int
	 */
	public function getAuthorId() {
		$field = $this->getAuthorIdField();
		return intval( $this->row->$field );
	}

	/**
	 * Get the author user name
	 * @return string
	 */
	public function getAuthorName() {
		$field = $this->getAuthorNameField();
		return strval( $this->row->$field );
	}

	/**
	 * Get the author actor ID
	 * @since 1.31
	 * @return string
	 */
	public function getAuthorActor() {
		$field = $this->getAuthorActorField();
		return strval( $this->row->$field );
	}

	/**
	 * Returns true if the current user can view the item
	 */
	abstract public function canView();

	/**
	 * Returns true if the current user can view the item text/file
	 */
	abstract public function canViewContent();

	/**
	 * Get the HTML of the list item. Should be include "<li></li>" tags.
	 * This is used to show the list in HTML form, by the special page.
	 */
	abstract public function getHTML();

	/**
	 * Returns an instance of LinkRenderer
	 * @return \MediaWiki\Linker\LinkRenderer
	 */
	protected function getLinkRenderer() {
		return MediaWikiServices::getInstance()->getLinkRenderer();
	}
}
