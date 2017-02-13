<?php
/**
 * Controller for page editing via the API
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
 * Class that imports data into the controller from an array
 */
class ApiEditController extends AbstractEditController {

	/**
	 * This function collects the form data and uses it to populate various member variables.
	 *
	 * @param array $requestArray
	 * @throws ErrorPageError
	 */
	final public function importFormData( array $requestArray ) {
		global $wgContLang;

		$this->data->section = $requestArray['wpSection'];

		if ( $this->data->section !== null && $this->data->section !== '' && !$this->isSectionEditSupported() ) {
			throw new ErrorPageError( 'sectioneditnotsupported-title', 'sectioneditnotsupported-text' );
		}

		# These fields need to be checked for encoding.
		# Also remove trailing whitespace, but don't remove _initial_
		# whitespace from the text boxes. This may be significant formatting.
		$this->data->textbox1 = $requestArray['wpTextbox1'];
		$textbox1 = $this->importContentFormData( $requestArray );
		if ( $textbox1 !== null ) {
			$this->data->textbox1 = $textbox1;
		}

		if ( isset( $requestArray['wpSummary'] ) ) {
			# Truncate for whole multibyte characters
			$this->data->summary = $wgContLang->truncate( $requestArray['wpSummary'], 255 );

			# If the summary consists of a heading, e.g. '==Foobar==', extract the title from the
			# header syntax, e.g. 'Foobar'. This is mainly an issue when we are using wpSummary for
			# section titles.
			$this->data->summary = preg_replace( '/^\s*=+\s*(.*?)\s*=+\s*$/', '$1', $this->data->summary );
		}

		if ( isset( $requestArray['wpSectionTitle'] ) ) {
			# Treat sectiontitle the same way as summary.
			# Note that wpSectionTitle is not yet a part of the actual edit form, as wpSummary is
			# currently doing double duty as both edit summary and section title. Right now this
			# is just to allow API edits to work around this limitation, but this should be
			# incorporated into the actual edit form when EditPage is rewritten (Bugs 18654, 26312).
			$this->data->sectionTitle = $wgContLang->truncate( $requestArray['wpSectionTitle'], 255 );
			$this->data->sectionTitle = preg_replace( '/^\s*=+\s*(.*?)\s*=+\s*$/', '$1', $this->data->sectionTitle );
		}

		$this->data->editTime = $requestArray['wpEdittime'];
		$this->data->startTime = $requestArray['wpStarttime'];
		$this->data->save = true;

		if ( isset( $requestArray['wpUndidRevision'] ) ) {
			$this->data->undidRevId = $requestArray['wpUndidRevision'];
		}

		if ( !preg_match( '/^\d{14}$/', $this->data->editTime ) ) {
			$this->data->editTime = null;
		}

		if ( !preg_match( '/^\d{14}$/', $this->data->startTime ) ) {
			$this->data->startTime = null;
		}

		if ( isset( $requestArray['wpRecreate'] ) ) {
			$this->data->recreate = $requestArray['wpRecreate'];
		}

		if ( isset( $requestArray['wpMinoredit'] ) ) {
			$this->data->minorEdit = $requestArray['wpMinoredit'];
		}

		if ( isset( $requestArray['wpWatchthis'] ) ) {
			$this->data->watchThis = $requestArray['wpWatchthis'];
		}

		$this->allowBlankSummary = true;
		$this->allowBlankArticle = true;
		$this->allowSelfRedirect = true;

		if ( isset( $requestArray['wpChangeTags'] ) ) {
			$changeTags = $requestArray['wpChangeTags'];
			if ( is_null( $changeTags ) || $changeTags === '' ) {
				$this->data->changeTags = [];
			} else {
				$this->data->changeTags = array_filter( array_map( 'trim', explode( ',',
					$changeTags ) ) );
			}
		}

		$this->data->parentRevId = $this->page->getLatest();

		$this->data->bot = $requestArray['bot'];

		// May be overridden by revision.
		$this->data->contentModel = $requestArray['model'];
		// May be overridden by revision.
		$this->data->contentFormat = $requestArray['format'];

		$this->validateContentModelAndFormat();

		// Allow extensions to modify form data
		Hooks::run( 'ApiEditController::importFormData', [ $this->page, $this->user, $this->data, $requestArray ] );
	}

	/**
	 * Subclass overridable method for extracting the page content data from the
	 * posted form to be placed in $this->data->textbox1, if using customized input
	 * this method should be overridden and return the page text that will be used
	 * for saving...
	 *
	 * @param array $requestArray
	 * @return string|null
	 */
	protected function importContentFormData( &$requestArray ) {
		return null; // Don't do anything, EditPage already extracted wpTextbox1
	}

}
