<?php
/**
 * Controller for page editing via a web UI.
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
 * Class that imports data into the controller from a WebRequest
 */
class WebEditController extends EditController {

	/**
	 * Gathered during import of form data
	 */

	/** @var bool */
	protected $triedSave = false;

	/** @var bool Has a summary been preset using GET parameter &summary= ? */
	protected $hasPresetSummary = false;

	/** @var bool */
	protected $incompleteForm = false;

	/**
	 * Validation
	 */

	/** @var bool */
	protected $tokenOk = false;

	/** @var bool */
	protected $tokenOkExceptSuffix = false;

	/**
	 * This function collects the form data and uses it to populate various member variables.
	 * @param WebRequest $request
	 * @throws ErrorPageError
	 */
	final public function importFormData( WebRequest $request ) {
		global $wgContLang;

		# Section edit can come from either the form or a link
		$this->data->section = $request->getVal( 'wpSection', $request->getVal( 'section' ) );
		if ( $this->data->section === null ) {
			$this->data->section = '';
		}

		if ( $this->data->section !== '' && !$this->isSectionEditSupported() ) {
			throw new ErrorPageError( 'sectioneditnotsupported-title',
				'sectioneditnotsupported-text' );
		}

		if ( $request->wasPosted() ) {
			# These fields need to be checked for encoding.
			# Also remove trailing whitespace, but don't remove _initial_
			# whitespace from the text boxes. This may be significant formatting.
			$this->data->textbox1 = $this->safeUnicodeInput( $request, 'wpTextbox1' );
			if ( !$request->getCheck( 'wpTextbox2' ) ) {
				// Skip this if wpTextbox2 has input, it indicates that we came
				// from a conflict page with raw page text, not a custom form
				// modified by subclasses
				$textbox1 = $this->importContentFormData( $request );
				if ( $textbox1 !== null ) {
					$this->data->textbox1 = $textbox1;
				}
			}

			# Truncate for whole multibyte characters
			$this->data->summary = $wgContLang->truncate( $request->getText( 'wpSummary' ), 255 );

			# If the summary consists of a heading, e.g. '==Foobar==', extract the title from the
			# header syntax, e.g. 'Foobar'. This is mainly an issue when we are using wpSummary for
			# section titles.
			$this->data->summary = preg_replace( '/^\s*=+\s*(.*?)\s*=+\s*$/', '$1', $this->data->summary );

			# Treat sectiontitle the same way as summary.
			# Note that wpSectionTitle is not yet a part of the actual edit form, as wpSummary is
			# currently doing double duty as both edit summary and section title. Right now this
			# is just to allow API edits to work around this limitation, but this should be
			# incorporated into the actual edit form when EditPage is rewritten (Bugs 18654, 26312).
			$this->data->sectionTitle = $wgContLang->truncate( $request->getText( 'wpSectionTitle' ), 255 );
			$this->data->sectionTitle = preg_replace( '/^\s*=+\s*(.*?)\s*=+\s*$/', '$1',
				$this->data->sectionTitle );

			$this->data->editTime = $request->getVal( 'wpEdittime' );
			$this->data->editRevId = $request->getIntOrNull( 'editRevId' );
			$this->data->startTime = $request->getVal( 'wpStarttime' );

			$undidRevId = $request->getInt( 'wpUndidRevision' );
			if ( $undidRevId ) {
				$this->data->undidRevId = $undidRevId;
			}

			$this->data->scrollTop = $request->getIntOrNull( 'wpScrolltop' );

			if ( $this->data->textbox1 === '' && $request->getVal( 'wpTextbox1' ) === null ) {
				// wpTextbox1 field is missing, possibly due to being "too big"
				// according to some filter rules such as Suhosin's setting for
				// suhosin.request.max_value_length (d'oh)
				$this->incompleteForm = true;
			} else {
				// If we receive the last parameter of the request, we can fairly
				// claim the POST request has not been truncated.
				$this->incompleteForm = !$request->getVal( 'wpUltimateParam' );
			}
			if ( $this->incompleteForm ) {
				# If the form is incomplete, force to preview.
				wfDebug( __METHOD__ . ": Form data appears to be incomplete\n" );
				wfDebug( "POST DATA: " . var_export( $_POST, true ) . "\n" );
				$this->data->preview = true;
			} else {
				$this->data->preview = $request->getCheck( 'wpPreview' );
				$this->data->diff = $request->getCheck( 'wpDiff' );

				// Remember whether a save was requested, so we can indicate
				// if we forced preview due to session failure.
				$this->triedSave = !$this->data->preview;

				if ( $this->tokenOk( $request ) ) {
					# Some browsers will not report any submit button
					# if the user hits enter in the comment box.
					# The unmarked state will be assumed to be a save,
					# if the form seems otherwise complete.
					wfDebug( __METHOD__ . ": Passed token check.\n" );
				} elseif ( $this->data->diff ) {
					# Failed token check, but only requested "Show Changes".
					wfDebug( __METHOD__ . ": Failed token check; Show Changes requested.\n" );
				} else {
					# Page might be a hack attempt posted from
					# an external site. Preview instead of saving.
					wfDebug( __METHOD__ . ": Failed token check; forcing preview\n" );
					$this->data->preview = true;
				}
			}
			$this->data->save = !$this->data->preview && !$this->data->diff;
			if ( !preg_match( '/^\d{14}$/', $this->data->editTime ) ) {
				$this->data->editTime = null;
			}

			if ( !preg_match( '/^\d{14}$/', $this->data->startTime ) ) {
				$this->data->startTime = null;
			}

			$this->recreate = $request->getCheck( 'wpRecreate' );

			$this->data->minorEdit = $request->getCheck( 'wpMinoredit' );
			$this->data->watchThis = $request->getCheck( 'wpWatchthis' );

			# Don't force edit summaries when a user is editing their own user or talk page
			if ( ( $this->title->mNamespace === NS_USER || $this->title->mNamespace === NS_USER_TALK )
				&& $this->title->getText() === $this->user->getName()
			) {
				$this->allowBlankSummary = true;
			} else {
				$this->allowBlankSummary = $request->getBool( 'wpIgnoreBlankSummary' )
					|| !$this->user->getOption( 'forceeditsummary' );
			}

			$this->autoSumm = $request->getText( 'wpAutoSummary' );

			$this->allowBlankArticle = $request->getBool( 'wpIgnoreBlankArticle' );
			$this->allowSelfRedirect = $request->getBool( 'wpIgnoreSelfRedirect' );

			$changeTags = $request->getVal( 'wpChangeTags' );
			if ( is_null( $changeTags ) || $changeTags === '' ) {
				$this->data->changeTags = [];
			} else {
				$this->data->changeTags = array_filter( array_map( 'trim', explode( ',',
					$changeTags ) ) );
			}
		} else {
			# Not a posted form? Start with nothing.
			wfDebug( __METHOD__ . ": Not a posted form.\n" );
			$this->data->textbox1 = '';
			$this->data->summary = '';
			$this->data->sectionTitle = '';
			$this->data->editTime = '';
			$this->data->editRevId = null;
			$this->data->startTime = wfTimestampNow();
			$this->data->preview = false;
			$this->data->save = false;
			$this->data->diff = false;
			$this->data->minorEdit = false;
			// Watch may be overridden by request parameters
			$this->data->watchThis = $request->getBool( 'watchthis', false );
			$this->data->recreate = false;

			// When creating a new section, we can preload a section title by passing it as the
			// preloadtitle parameter in the URL (Bug 13100)
			if ( $this->data->section === 'new' && $request->getVal( 'preloadtitle' ) ) {
				$this->data->sectionTitle = $request->getVal( 'preloadtitle' );
				// Once wpSummary isn't being use for setting section titles, we should delete this.
				$this->data->summary = $request->getVal( 'preloadtitle' );
			} elseif ( $this->data->section !== 'new' && $request->getVal( 'summary' ) ) {
				$this->data->summary = $request->getText( 'summary' );
				if ( $this->data->summary !== '' ) {
					$this->hasPresetSummary = true;
				}
			}

			if ( $request->getVal( 'minor' ) ) {
				$this->data->minoredit = true;
			}
		}

		$this->data->oldid = $request->getInt( 'oldid' );
		$this->data->parentRevId = $request->getInt( 'parentRevId' );

		$this->data->bot = $request->getBool( 'bot', true );
		$this->data->noSummary = $request->getBool( 'nosummary' );

		// May be overridden by revision.
		$this->data->contentModel = $request->getText( 'model', $this->data->contentModel );
		// May be overridden by revision.
		$this->data->contentFormat = $request->getText( 'format', $this->data->contentFormat );

		$this->validateContentModelAndFormat();

		// Allow extensions to modify form data
		$hookArgs = [ $this->page, $this->user, $this->data, $request ];
		Hooks::run( 'WebEditController::importFormData', $hookArgs );
	}

	/**
	 * Subclass overridable method for extracting the page content data from the
	 * posted form to be placed in $this->data->textbox1, if using customized input
	 * this method should be overridden and return the page text that will be used
	 * for saving, preview parsing and so on...
	 *
	 * @param WebRequest $request
	 * @return string|null
	 */
	protected function importContentFormData( &$request ) {
		return null; // Don't do anything, EditPage already extracted wpTextbox1
	}

	/**
	 * Filter an input field through a Unicode de-armoring process if it
	 * came from an old browser with known broken Unicode editing issues.
	 *
	 * @param WebRequest $request
	 * @param string $field
	 * @return string
	 */
	final protected function safeUnicodeInput( $request, $field ) {
		$text = rtrim( $request->getText( $field ) );
		return $request->getBool( 'safemode' )
			? EditUtilities::unmakeSafe( $text )
			: $text;
	}

	/**
	 * Make sure the form isn't faking a user's credentials.
	 *
	 * @param WebRequest $request
	 * @return bool
	 * @private
	 */
	final protected function tokenOk( &$request ) {
		$token = $request->getVal( 'wpEditToken' );
		$this->tokenOk = $this->user->matchEditToken( $token );
		$this->tokenOkExceptSuffix = $this->user->matchEditTokenNoSuffix( $token );
		return $this->tokenOk;
	}

	final public function triedSave() {
		return $this->triedSave;
	}

	final public function hasPresetSummary() {
		return $this->hasPresetSummary;
	}

	final public function isIncompleteForm() {
		return $this->incompleteForm;
	}

	final public function isTokenOk() {
		return $this->tokenOk;
	}

	final public function isTokenOkExceptSuffix() {
		return $this->tokenOkExceptSuffix;
	}

}
