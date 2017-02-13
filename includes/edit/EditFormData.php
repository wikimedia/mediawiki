<?php
/**
 * Model for an edit
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
 * Class encapsulating the form data needed for an edit (read/write)
 * Extensions can dynamically add extra fields.
 */
class EditFormData {

	/** @var string */
	public $textbox1 = '';

	/** @var string */
	public $textbox2 = '';

	/** @var string */
	public $summary = '';

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

	/** @var null|string */
	public $contentModel = null;

	/** @var null|string */
	public $contentFormat = null;

}

/**
 * Class wrapping an EditFormData to safely add user input to it
 */
class EditFormDataWrapper {
	/** @var Title */
	private $title;

	/** @var EditFormData */
	private $data;

	/** @var bool|null */
	private $pageDeletedWhileEditing = null;

	/** @var bool|stdClass */
	private $lastDelete = null;

	public function __construct( Title $title ) {
		$this->title = $title;
		$this->data = new EditFormData();
	}

	public function getData() {
		return $this->data;
	}

	public function setTextbox1( $text ) {
		# Remove trailing whitespace, but don't remove _initial_
		# whitespace from the text boxes. This may be significant formatting.
		$this->data->textbox1 = rtrim( $text );
	}

	public function setSection( $section ) {
		$this->data->section = $section;
	}

	public function setEditRevId( $id ) {
		$this->data->editRevId = $id;
	}

	public function setEditTime( $time ) {
		if ( preg_match( '/^\d{14}$/', $time ) ) {
			$this->data->editTime = $time;
		}
	}

	public function setStartTime( $time ) {
		if ( preg_match( '/^\d{14}$/', $time ) ) {
			$this->data->startTime = $time;
		}
	}

	public function setSummary( $summary ) {
		global $wgContLang;
		# Truncate for whole multibyte characters
		$summary = $wgContLang->truncate( $summary, 255 );

		# If the summary consists of a heading, e.g. '==Foobar==', extract the title from the
		# header syntax, e.g. 'Foobar'. This is mainly an issue when we are using wpSummary for
		# section titles.
		$this->data->summary = preg_replace( '/^\s*=+\s*(.*?)\s*=+\s*$/', '$1', $summary );
	}

	public function setSectionTitle( $sectionTitle ) {
		global $wgContLang;
		# Treat sectiontitle the same way as summary.
		# Note that wpSectionTitle is not yet a part of the actual edit form, as wpSummary is
		# currently doing double duty as both edit summary and section title. Right now this
		# is just to allow API edits to work around this limitation, but this should be
		# incorporated into the actual edit form when EditPage is rewritten (Bugs 18654, 26312).
		// @todo: Do this
		$sectionTitle = $wgContLang->truncate( $sectionTitle, 255 );
		$this->data->sectionTitle = preg_replace( '/^\s*=+\s*(.*?)\s*=+\s*$/', '$1', $sectionTitle );
	}

	public function setContentModelAndFormat( $model, $format ) {
		$this->data->contentModel = $model;
		$this->data->contentFormat = $format;
		try {
			$handler = ContentHandler::getForModelID( $this->data->contentModel );
		} catch ( MWUnknownContentModelException $e ) {
			throw new ErrorPageError(
				'editpage-invalidcontentmodel-title',
				'editpage-invalidcontentmodel-text',
				[ $this->data->contentModel ]
			);
		}

		if ( !$handler->isSupportedFormat( $this->data->contentFormat ) ) {
			throw new ErrorPageError(
				'editpage-notsupportedcontentformat-title',
				'editpage-notsupportedcontentformat-text',
				[ $this->data->contentFormat,
					ContentHandler::getLocalizedName( $this->data->contentModel ) ]
			);
		}

		/**
		 * @todo Check if the desired model is allowed in this namespace, and if
		 *   a transition from the page's current model to the new model is
		 *   allowed.
		 */
	}

	/**
	 * Check if a page was deleted while the user was editing it, before submit.
	 * Note that we rely on the logging table, which hasn't been always there,
	 * but that doesn't matter, because this only applies to brand new
	 * deletes.
	 * @param bool|stdClass &$lastDelete Gives the raw db result for the last deletion
	 * @return bool
	 */
	public function wasDeletedWhileEditing( &$lastDelete = null ) {
		if ( $this->pageDeletedWhileEditing !== null ) {
			$lastDelete = $this->lastDelete;
			return $this->pageDeletedWhileEditing;
		}

		$this->pageDeletedWhileEditing = false;

		if ( !$this->title->exists() && $this->title->isDeletedQuick() ) {
			$this->lastDelete = $this->getLastDelete();
			if ( $this->lastDelete ) {
				$deleteTime = wfTimestamp( TS_MW, $this->lastDelete->log_timestamp );
				if ( $deleteTime > $this->data->startTime ) {
					$this->pageDeletedWhileEditing = true;
				}
			}
		}

		$lastDelete = $this->lastDelete;
		return $this->pageDeletedWhileEditing;
	}

	/**
	 * @return bool|stdClass
	 */
	private function getLastDelete() {
		$dbr = wfGetDB( DB_REPLICA );
		return $dbr->selectRow(
			[ 'logging', 'user' ],
			[
				'log_type',
				'log_action',
				'log_timestamp',
				'log_user',
				'log_namespace',
				'log_title',
				'log_comment',
				'log_params',
				'log_deleted',
				'user_name'
			],
			[
				'log_namespace' => $this->title->getNamespace(),
				'log_title' => $this->title->getDBkey(),
				'log_type' => 'delete',
				'log_action' => 'delete',
				'user_id=log_user'
			],
			__METHOD__,
			[ 'LIMIT' => 1, 'ORDER BY' => 'log_timestamp DESC' ]
		);
	}
}

/**
 * Class providing access to an edit form data (read-only)
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
