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
 * Class modelling an edit
 */
class Edit {

	/** @var string */
	private $text = '';

	/** @var string */
	private $summary = '';

	/** @var string */
	private $lastRevTime = '';

	/** @var integer */
	private $lastRevId = null;

	/** @var string */
	private $section = '';

	/** @var string */
	private $sectionTitle = '';

	/** @var string */
	private $startTime = '';

	/** @var null|string */
	private $contentModel = null;

	/** @var null|string */
	private $contentFormat = null;

	public function setText( $text ) {
		# Remove trailing whitespace, but don't remove _initial_
		# whitespace from the text boxes. This may be significant formatting.
		$this->text = rtrim( $text );
	}

	public function setSection( $section ) {
		if ( $section === null ) {
			$section = '';
		}
		$this->section = $section;
	}

	public function setLastRevisionId( $id ) {
		$this->lastRevId = $id;
	}

	public function setLastRevisionTime( $time ) {
		if ( preg_match( '/^\d{14}$/', $time ) ) {
			$this->lastRevTime = $time;
		}
	}

	public function setStartTime( $time ) {
		if ( preg_match( '/^\d{14}$/', $time ) ) {
			$this->startTime = $time;
		}
	}

	public function setSummary( $summary ) {
		global $wgContLang;
		# Truncate for whole multibyte characters
		$summary = $wgContLang->truncate( $summary, 255 );

		# If the summary consists of a heading, e.g. '==Foobar==', extract the title from the
		# header syntax, e.g. 'Foobar'. This is mainly an issue when we are using wpSummary for
		# section titles.
		$this->summary = preg_replace( '/^\s*=+\s*(.*?)\s*=+\s*$/', '$1', $summary );
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
		$this->sectionTitle = preg_replace( '/^\s*=+\s*(.*?)\s*=+\s*$/', '$1', $sectionTitle );
	}

	public function setContentModelAndFormat( $model, $format ) {
		$this->contentModel = $model;
		$this->contentFormat = $format;
		try {
			$handler = ContentHandler::getForModelID( $this->contentModel );
		} catch ( MWUnknownContentModelException $e ) {
			throw new ErrorPageError(
				'editpage-invalidcontentmodel-title',
				'editpage-invalidcontentmodel-text',
				[ $this->contentModel ]
			);
		}

		if ( !$handler->isSupportedFormat( $this->contentFormat ) ) {
			throw new ErrorPageError(
				'editpage-notsupportedcontentformat-title',
				'editpage-notsupportedcontentformat-text',
				[ $this->contentFormat,
					ContentHandler::getLocalizedName( $this->contentModel ) ]
			);
		}
	}

	public function getSection() {
		return $this->section;
	}

	public function getText() {
		return $this->text;
	}

	public function getSummary() {
		return $this->summary;
	}

	public function getSectionTitle() {
		return $this->sectionTitle;
	}

	public function getLastRevisionId() {
		return $this->lastRevId;
	}

	public function getLastRevisionTime() {
		return $this->lastRevTime;
	}

	public function getStartTime() {
		return $this->startTime;
	}

	public function getContentModel() {
		return $this->contentModel;
	}

	public function getContentFormat() {
		return $this->contentFormat;
	}

}
