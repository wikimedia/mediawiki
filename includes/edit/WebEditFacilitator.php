<?php
/**
 * Imports edit form data from a Web request
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
 * Class that imports data into the Edit object from a WebRequest
 */
class WebEditFacilitator extends EditFacilitator {

	/**
	 * Gathered during import of form data
	 */

	/** @var bool */
	protected $triedSave = false;

	/** @var bool Has a summary been preset using GET parameter &summary= ? */
	protected $hasPresetSummary = false;

	/** @var bool */
	protected $incompleteForm = false;

	/** @var string */
	protected $requestType = '';

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
		# Section edit can come from either the form or a link
		$this->edit->setSection( $request->getVal( 'wpSection', $request->getVal( 'section' ) ) );

		if ( $this->edit->getSection() !== '' &&
			!ContentHandler::getForTitle( $this->title )->supportsSections()
		) {
			throw new ErrorPageError( 'sectioneditnotsupported-title',
				'sectioneditnotsupported-text' );
		}

		if ( $request->wasPosted() ) {
			# These fields need to be checked for encoding.
			$this->edit->setText( $this->safeUnicodeInput( $request, 'wpTextbox1' ) );
			if ( !$request->getCheck( 'wpTextbox2' ) ) {
				// Skip this if wpTextbox2 has input, it indicates that we came
				// from a conflict page with raw page text, not a custom form
				// modified by subclasses
				$textbox1 = $this->importContentFormData( $request );
				if ( $textbox1 !== null ) {
					$this->edit->setText( $textbox1 );
				}
			}

			$this->edit->setSummary( $request->getText( 'wpSummary' ) );
			$this->edit->setSectionTitle( $request->getText( 'wpSectionTitle' ) );

			$this->edit->setLastRevisionTime( $request->getVal( 'wpEdittime' ) );
			$this->edit->setLastRevisionId( $request->getIntOrNull( 'editRevId' ) );
			$this->edit->setStartTime( $request->getVal( 'wpStarttime' ) );

			$undidRevId = $request->getInt( 'wpUndidRevision' );
			if ( $undidRevId ) {
				$this->undidRevId = $undidRevId;
			}

			if ( $this->edit->getText() === '' && $request->getVal( 'wpTextbox1' ) === null ) {
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
				$this->requestType = 'preview';
			} else {
				if ( $request->getCheck( 'wpPreview' ) ) {
					$this->requestType = 'preview';
				} elseif ( $request->getCheck( 'wpDiff' ) ) {
					$this->requestType = 'diff';
				} else {
					$this->requestType = 'save';
				}

				// Remember whether a save was requested, so we can indicate
				// if we forced preview due to session failure.
				$this->triedSave = $this->requestType !== 'preview';

				if ( $this->tokenOk( $request ) ) {
					# Some browsers will not report any submit button
					# if the user hits enter in the comment box.
					# The unmarked state will be assumed to be a save,
					# if the form seems otherwise complete.
					wfDebug( __METHOD__ . ": Passed token check.\n" );
				} elseif ( $this->requestType = 'diff' ) {
					# Failed token check, but only requested "Show Changes".
					wfDebug( __METHOD__ . ": Failed token check; Show Changes requested.\n" );
				} else {
					# Page might be a hack attempt posted from
					# an external site. Preview instead of saving.
					wfDebug( __METHOD__ . ": Failed token check; forcing preview\n" );
					$this->requestType = 'preview';
				}
			}

			$this->recreate = $request->getCheck( 'wpRecreate' );

			$this->minorEdit = $request->getCheck( 'wpMinoredit' );
			$this->watchThis = $request->getCheck( 'wpWatchthis' );

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
		} else {
			# Not a posted form? Start with nothing.
			wfDebug( __METHOD__ . ": Not a posted form.\n" );
			$this->edit->setStartTime( wfTimestampNow() );
			$this->requestType = 'initial';
			$this->minorEdit = false;
			// Watch may be overridden by request parameters
			$this->watchThis = $request->getBool( 'watchthis', false );
			$this->recreate = false;

			// When creating a new section, we can preload a section title by passing it as the
			// preloadtitle parameter in the URL (Bug 13100)
			if ( $this->edit->getSection() === 'new' && $request->getVal( 'preloadtitle' ) ) {
				$this->edit->setSectionTitle( $request->getVal( 'preloadtitle' ) );
				// Once wpSummary isn't being use for setting section titles, we should delete this.
				$this->edit->setSummary( $request->getVal( 'preloadtitle' ) );
			} elseif ( $this->edit->getSection() !== 'new' && $request->getVal( 'summary' ) ) {
				$this->edit->setSummary( $request->getText( 'summary' ) );
				if ( $this->edit->getSummary() !== '' ) {
					$this->hasPresetSummary = true;
				}
			}

			if ( $request->getVal( 'minor' ) ) {
				$this->minorEdit = true;
			}
		}

		$this->bot = $request->getBool( 'bot', true );

		// May be overridden by revision.
		$contentModel = $request->getText( 'model', $this->title->getContentModel() );
		// May be overridden by revision.
		$defaultFormat = ContentHandler::getForModelID( $contentModel )->getDefaultFormat();
		$contentFormat = $request->getText( 'format', $defaultFormat );

		$this->edit->setContentModelAndFormat( $contentModel, $contentFormat );

		/**
		 * @todo Check if the desired model is allowed in this namespace, and if
		 *   a transition from the page's current model to the new model is
		 *   allowed.
		 */

		// Allow extensions to modify form data
		$hookArgs = [ $this->page, $this->user, $this->edit, $request ];
		Hooks::run( 'WebEditFacilitatorImportFormData', $hookArgs );
	}

	/**
	 * Subclass overridable method for extracting the page content data from the
	 * posted form to be placed as the text to be edited, if using customized input
	 * this method should be overridden and return the page text that will be used
	 * for saving, preview parsing and so on...
	 *
	 * @param WebRequest $request
	 * @return string|null
	 */
	protected function importContentFormData( &$request ) {
		return null; // Don't do anything, importFormData already extracted wpTextbox1
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
		$text = $request->getText( $field );
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

	final public function getRequestType() {
		return $this->requestType;
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
