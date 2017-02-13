<?php
/**
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

/**
 * Class encapsulating the form data needed for an edit, read/write
 * Extensions can dynamically add extra fields.
 */
class EditFormData {

	/** @var bool */
	public $save = false;

	/** @var bool */
	public $preview = false;

	/** @var bool */
	public $diff = false;

	/** @var bool */
	public $minorEdit = false;

	/** @var bool */
	public $watchThis = false;

	/** @var string */
	public $textbox1 = '';

	/** @var string */
	public $textbox2 = '';

	/** @var string */
	public $summary = '';

	/** @var bool */
	public $noSummary = false;

	/** @var string */
	public $editTime = '';

	/** @var integer */
	public $editRevId = null;

	/** @var string */
	public $section = '';

	/** @var string */
	public $sectionTitle = '';

	/** @var string */
	public $startTime = '';

	/** @var int */
	public $oldid = 0;

	/** @var int */
	public $parentRevId = 0;

	/** @var int */
	public $undidRevId = 0;

	/** @var null */
	public $scrollTop = null;

	/** @var bool */
	public $bot = true;

	/** @var null|string */
	public $contentModel = null;

	/** @var null|string */
	public $contentFormat = null;

	/** @var null|array */
	public $changeTags = null;

}

/**
 * Class providing access to an edit form data, read-only
 */
class EditFormDataSource {

	/** @var EditFormData */
	private $data;

	/**
	 * @param EditFormData $data
	 */
	public function __construct( EditFormData $data ) {
		$this->data = $data;
	}

	/**
	 * Magic accessor
	 * @param string $field
	 */
	public function __get( $field ) {
		return $this->data->$field;
	}

	/**
	 * Magic isset handler
	 * @param string $field
	 */
	public function __isset( $field ) {
		return isset( $this->data->$field );
	}

}
