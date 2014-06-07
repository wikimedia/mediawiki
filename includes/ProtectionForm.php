<?php
/**
 * Page protection
 *
 * Copyright © 2005 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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
 * Handles the page protection UI and backend
 */
class ProtectionForm {
	/** A map of action to restriction level, from request or default */
	var $mRestrictions = array();

	/** The custom/additional protection reason */
	var $mReason = '';

	/** The reason selected from the list, blank for other/additional */
	var $mReasonSelection = '';

	/** True if the restrictions are cascading, from request or existing protection */
	var $mCascade = false;

	/** Map of action to "other" expiry time. Used in preference to mExpirySelection. */
	var $mExpiry = array();

	/**
	 * Map of action to value selected in expiry drop-down list.
	 * Will be set to 'othertime' whenever mExpiry is set.
	 */
	var $mExpirySelection = array();

	/** Permissions errors for the protect action */
	var $mPermErrors = array();

	/** Types (i.e. actions) for which levels can be selected */
	var $mApplicableTypes = array();

	/** Map of action to the expiry time of the existing protection */
	var $mExistingExpiry = array();

	function __construct( Page $article ) {
		global $wgUser;
		// Set instance variables.
		$this->mArticle = $article;
		$this->mTitle = $article->getTitle();
		$this->mApplicableTypes = $this->mTitle->getRestrictionTypes();

		// Check if the form should be disabled.
		// If it is, the form will be available in read-only to show levels.
		$this->mPermErrors = $this->mTitle->getUserPermissionsErrors( 'protect', $wgUser );
		if ( wfReadOnly() ) {
			$this->mPermErrors[] = array( 'readonlytext', wfReadOnlyReason() );
		}
		$this->disabled = $this->mPermErrors != array();
		$this->disabledAttrib = $this->disabled
			? array( 'disabled' => 'disabled' )
			: array();

		$this->loadData();
	}

	/**
	 * Loads the current state of protection into the object.
	 */
	function loadData() {
		global $wgRequest, $wgUser;

		$levels = MWNamespace::getRestrictionLevels( $this->mTitle->getNamespace(), $wgUser );
		$this->mCascade = $this->mTitle->areRestrictionsCascading();

		$this->mReason = $wgRequest->getText( 'mwProtect-reason' );
		$this->mReasonSelection = $wgRequest->getText( 'wpProtectReasonSelection' );
		$this->mCascade = $wgRequest->getBool( 'mwProtect-cascade', $this->mCascade );

		foreach ( $this->mApplicableTypes as $action ) {
			// @todo FIXME: This form currently requires individual selections,
			// but the db allows multiples separated by commas.

			// Pull the actual restriction from the DB
			$this->mRestrictions[$action] = implode( '', $this->mTitle->getRestrictions( $action ) );

			if ( !$this->mRestrictions[$action] ) {
				// No existing expiry
				$existingExpiry = '';
			} else {
				$existingExpiry = $this->mTitle->getRestrictionExpiry( $action );
			}
			$this->mExistingExpiry[$action] = $existingExpiry;

			$requestExpiry = $wgRequest->getText( "mwProtect-expiry-$action" );
			$requestExpirySelection = $wgRequest->getVal( "wpProtectExpirySelection-$action" );

			if ( $requestExpiry ) {
				// Custom expiry takes precedence
				$this->mExpiry[$action] = $requestExpiry;
				$this->mExpirySelection[$action] = 'othertime';
			} elseif ( $requestExpirySelection ) {
				// Expiry selected from list
				$this->mExpiry[$action] = '';
				$this->mExpirySelection[$action] = $requestExpirySelection;
			} elseif ( $existingExpiry == 'infinity' ) {
				// Existing expiry is infinite, use "infinite" in drop-down
				$this->mExpiry[$action] = '';
				$this->mExpirySelection[$action] = 'infinite';
			} elseif ( $existingExpiry ) {
				// Use existing expiry in its own list item
				$this->mExpiry[$action] = '';
				$this->mExpirySelection[$action] = $existingExpiry;
			} else {
				// Final default: infinite
				$this->mExpiry[$action] = '';
				$this->mExpirySelection[$action] = 'infinite';
			}

			$val = $wgRequest->getVal( "mwProtect-level-$action" );
			if ( isset( $val ) && in_array( $val, $levels ) ) {
				$this->mRestrictions[$action] = $val;
			}
		}
	}

	/**
	 * Get the expiry time for a given action, by combining the relevant inputs.
	 *
	 * @param $action string
	 *
	 * @return string 14-char timestamp or "infinity", or false if the input was invalid
	 */
	function getExpiry( $action ) {
		if ( $this->mExpirySelection[$action] == 'existing' ) {
			return $this->mExistingExpiry[$action];
		} elseif ( $this->mExpirySelection[$action] == 'othertime' ) {
			$value = $this->mExpiry[$action];
		} else {
			$value = $this->mExpirySelection[$action];
		}
		if ( $value == 'infinite' || $value == 'indefinite' || $value == 'infinity' ) {
			$time = wfGetDB( DB_SLAVE )->getInfinity();
		} else {
			$unix = strtotime( $value );

			if ( !$unix || $unix === -1 ) {
				return false;
			}

			// @todo FIXME: Non-qualified absolute times are not in users specified timezone
			// and there isn't notice about it in the ui
			$time = wfTimestamp( TS_MW, $unix );
		}
		return $time;
	}

	/**
	 * Main entry point for action=protect and action=unprotect
	 */
	function execute() {
		global $wgRequest, $wgOut;

		if ( MWNamespace::getRestrictionLevels( $this->mTitle->getNamespace() ) === array( '' ) ) {
			throw new ErrorPageError( 'protect-badnamespace-title', 'protect-badnamespace-text' );
		}

		if ( $wgRequest->wasPosted() ) {
			if ( $this->save() ) {
				$q = $this->mArticle->isRedirect() ? 'redirect=no' : '';
				$wgOut->redirect( $this->mTitle->getFullURL( $q ) );
			}
		} else {
			$this->show();
		}
	}

	/**
	 * Show the input form with optional error message
	 *
	 * @param string $err error message or null if there's no error
	 */
	function show( $err = null ) {
		global $wgOut;

		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->addBacklinkSubtitle( $this->mTitle );

		if ( is_array( $err ) ) {
			$wgOut->wrapWikiMsg( "<p class='error'>\n$1\n</p>\n", $err );
		} elseif ( is_string( $err ) ) {
			$wgOut->addHTML( "<p class='error'>{$err}</p>\n" );
		}

		if ( $this->mTitle->getRestrictionTypes() === array() ) {
			// No restriction types available for the current title
			// this might happen if an extension alters the available types
			$wgOut->setPageTitle( wfMessage( 'protect-norestrictiontypes-title', $this->mTitle->getPrefixedText() ) );
			$wgOut->addWikiText( wfMessage( 'protect-norestrictiontypes-text' )->text() );

			// Show the log in case protection was possible once
			$this->showLogExtract( $wgOut );
			// return as there isn't anything else we can do
			return;
		}

		list( $cascadeSources, /* $restrictions */ ) = $this->mTitle->getCascadeProtectionSources();
		if ( $cascadeSources && count( $cascadeSources ) > 0 ) {
			$titles = '';

			foreach ( $cascadeSources as $title ) {
				$titles .= '* [[:' . $title->getPrefixedText() . "]]\n";
			}

			$wgOut->wrapWikiMsg( "<div id=\"mw-protect-cascadeon\">\n$1\n" . $titles . "</div>", array( 'protect-cascadeon', count( $cascadeSources ) ) );
		}

		# Show an appropriate message if the user isn't allowed or able to change
		# the protection settings at this time
		if ( $this->disabled ) {
			$wgOut->setPageTitle( wfMessage( 'protect-title-notallowed', $this->mTitle->getPrefixedText() ) );
			$wgOut->addWikiText( $wgOut->formatPermissionsErrorMessage( $this->mPermErrors, 'protect' ) );
		} else {
			$wgOut->setPageTitle( wfMessage( 'protect-title', $this->mTitle->getPrefixedText() ) );
			$wgOut->addWikiMsg( 'protect-text',
				wfEscapeWikiText( $this->mTitle->getPrefixedText() ) );
		}

		$wgOut->addHTML( $this->buildForm() );
		$this->showLogExtract( $wgOut );
	}

	/**
	 * Save submitted protection form
	 *
	 * @return Boolean: success
	 */
	function save() {
		global $wgRequest, $wgUser, $wgOut;

		# Permission check!
		if ( $this->disabled ) {
			$this->show();
			return false;
		}

		$token = $wgRequest->getVal( 'wpEditToken' );
		if ( !$wgUser->matchEditToken( $token, array( 'protect', $this->mTitle->getPrefixedDBkey() ) ) ) {
			$this->show( array( 'sessionfailure' ) );
			return false;
		}

		# Create reason string. Use list and/or custom string.
		$reasonstr = $this->mReasonSelection;
		if ( $reasonstr != 'other' && $this->mReason != '' ) {
			// Entry from drop down menu + additional comment
			$reasonstr .= wfMessage( 'colon-separator' )->text() . $this->mReason;
		} elseif ( $reasonstr == 'other' ) {
			$reasonstr = $this->mReason;
		}
		$expiry = array();
		foreach ( $this->mApplicableTypes as $action ) {
			$expiry[$action] = $this->getExpiry( $action );
			if ( empty( $this->mRestrictions[$action] ) ) {
				continue; // unprotected
			}
			if ( !$expiry[$action] ) {
				$this->show( array( 'protect_expiry_invalid' ) );
				return false;
			}
			if ( $expiry[$action] < wfTimestampNow() ) {
				$this->show( array( 'protect_expiry_old' ) );
				return false;
			}
		}

		$this->mCascade = $wgRequest->getBool( 'mwProtect-cascade' );

		$status = $this->mArticle->doUpdateRestrictions( $this->mRestrictions, $expiry, $this->mCascade, $reasonstr, $wgUser );

		if ( !$status->isOK() ) {
			$this->show( $wgOut->parseInline( $status->getWikiText() ) );
			return false;
		}

		/**
		 * Give extensions a change to handle added form items
		 *
		 * @since 1.19 you can (and you should) return false to abort saving;
		 *             you can also return an array of message name and its parameters
		 */
		$errorMsg = '';
		if ( !wfRunHooks( 'ProtectionForm::save', array( $this->mArticle, &$errorMsg, $reasonstr ) ) ) {
			if ( $errorMsg == '' ) {
				$errorMsg = array( 'hookaborted' );
			}
		}
		if ( $errorMsg != '' ) {
			$this->show( $errorMsg );
			return false;
		}

		WatchAction::doWatchOrUnwatch( $wgRequest->getCheck( 'mwProtectWatch' ), $this->mTitle, $wgUser );

		return true;
	}

	/**
	 * Build the input form
	 *
	 * @return String: HTML form
	 */
	function buildForm() {
		global $wgUser, $wgLang, $wgOut;

		$mProtectreasonother = Xml::label(
			wfMessage( 'protectcomment' )->text(),
			'wpProtectReasonSelection'
		);
		$mProtectreason = Xml::label(
			wfMessage( 'protect-otherreason' )->text(),
			'mwProtect-reason'
		);

		$out = '';
		if ( !$this->disabled ) {
			$wgOut->addModules( 'mediawiki.legacy.protect' );
			$out .= Xml::openElement( 'form', array( 'method' => 'post',
				'action' => $this->mTitle->getLocalURL( 'action=protect' ),
				'id' => 'mw-Protect-Form', 'onsubmit' => 'ProtectionForm.enableUnchainedInputs(true)' ) );
		}

		$out .= Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMessage( 'protect-legend' )->text() ) .
			Xml::openElement( 'table', array( 'id' => 'mwProtectSet' ) ) .
			Xml::openElement( 'tbody' );

		// Not all languages have V_x <-> N_x relation
		foreach ( $this->mRestrictions as $action => $selected ) {
			// Messages:
			// restriction-edit, restriction-move, restriction-create, restriction-upload
			$msg = wfMessage( 'restriction-' . $action );
			$out .= "<tr><td>" .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, $msg->exists() ? $msg->text() : $action ) .
			Xml::openElement( 'table', array( 'id' => "mw-protect-table-$action" ) ) .
				"<tr><td>" . $this->buildSelector( $action, $selected ) . "</td></tr><tr><td>";

			$reasonDropDown = Xml::listDropDown( 'wpProtectReasonSelection',
				wfMessage( 'protect-dropdown' )->inContentLanguage()->text(),
				wfMessage( 'protect-otherreason-op' )->inContentLanguage()->text(),
				$this->mReasonSelection,
				'mwProtect-reason', 4 );
			$scExpiryOptions = wfMessage( 'protect-expiry-options' )->inContentLanguage()->text();

			$showProtectOptions = $scExpiryOptions !== '-' && !$this->disabled;

			$mProtectexpiry = Xml::label(
				wfMessage( 'protectexpiry' )->text(),
				"mwProtectExpirySelection-$action"
			);
			$mProtectother = Xml::label(
				wfMessage( 'protect-othertime' )->text(),
				"mwProtect-$action-expires"
			);

			$expiryFormOptions = '';
			if ( $this->mExistingExpiry[$action] && $this->mExistingExpiry[$action] != 'infinity' ) {
				$timestamp = $wgLang->timeanddate( $this->mExistingExpiry[$action], true );
				$d = $wgLang->date( $this->mExistingExpiry[$action], true );
				$t = $wgLang->time( $this->mExistingExpiry[$action], true );
				$expiryFormOptions .=
					Xml::option(
						wfMessage( 'protect-existing-expiry', $timestamp, $d, $t )->text(),
						'existing',
						$this->mExpirySelection[$action] == 'existing'
					) . "\n";
			}

			$expiryFormOptions .= Xml::option(
				wfMessage( 'protect-othertime-op' )->text(),
				"othertime"
			) . "\n";
			foreach ( explode( ',', $scExpiryOptions ) as $option ) {
				if ( strpos( $option, ":" ) === false ) {
					$show = $value = $option;
				} else {
					list( $show, $value ) = explode( ":", $option );
				}
				$show = htmlspecialchars( $show );
				$value = htmlspecialchars( $value );
				$expiryFormOptions .= Xml::option( $show, $value, $this->mExpirySelection[$action] === $value ) . "\n";
			}
			# Add expiry dropdown
			if ( $showProtectOptions && !$this->disabled ) {
				$out .= "
					<table><tr>
						<td class='mw-label'>
							{$mProtectexpiry}
						</td>
						<td class='mw-input'>" .
							Xml::tags( 'select',
								array(
									'id' => "mwProtectExpirySelection-$action",
									'name' => "wpProtectExpirySelection-$action",
									'onchange' => "ProtectionForm.updateExpiryList(this)",
									'tabindex' => '2' ) + $this->disabledAttrib,
								$expiryFormOptions ) .
						"</td>
					</tr></table>";
			}
			# Add custom expiry field
			$attribs = array( 'id' => "mwProtect-$action-expires",
				'onkeyup' => 'ProtectionForm.updateExpiry(this)',
				'onchange' => 'ProtectionForm.updateExpiry(this)' ) + $this->disabledAttrib;
			$out .= "<table><tr>
					<td class='mw-label'>" .
						$mProtectother .
					'</td>
					<td class="mw-input">' .
						Xml::input( "mwProtect-expiry-$action", 50, $this->mExpiry[$action], $attribs ) .
					'</td>
				</tr></table>';
			$out .= "</td></tr>" .
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'fieldset' ) .
			"</td></tr>";
		}
		# Give extensions a chance to add items to the form
		wfRunHooks( 'ProtectionForm::buildForm', array( $this->mArticle, &$out ) );

		$out .= Xml::closeElement( 'tbody' ) . Xml::closeElement( 'table' );

		// JavaScript will add another row with a value-chaining checkbox
		if ( $this->mTitle->exists() ) {
			$out .= Xml::openElement( 'table', array( 'id' => 'mw-protect-table2' ) ) .
				Xml::openElement( 'tbody' );
			$out .= '<tr>
					<td></td>
					<td class="mw-input">' .
						Xml::checkLabel(
							wfMessage( 'protect-cascade' )->text(),
							'mwProtect-cascade',
							'mwProtect-cascade',
							$this->mCascade, $this->disabledAttrib
						) .
					"</td>
				</tr>\n";
			$out .= Xml::closeElement( 'tbody' ) . Xml::closeElement( 'table' );
		}

		# Add manual and custom reason field/selects as well as submit
		if ( !$this->disabled ) {
			$out .= Xml::openElement( 'table', array( 'id' => 'mw-protect-table3' ) ) .
				Xml::openElement( 'tbody' );
			$out .= "
				<tr>
					<td class='mw-label'>
						{$mProtectreasonother}
					</td>
					<td class='mw-input'>
						{$reasonDropDown}
					</td>
				</tr>
				<tr>
					<td class='mw-label'>
						{$mProtectreason}
					</td>
					<td class='mw-input'>" .
						Xml::input( 'mwProtect-reason', 60, $this->mReason, array( 'type' => 'text',
							'id' => 'mwProtect-reason', 'maxlength' => 180 ) ) .
							// Limited maxlength as the database trims at 255 bytes and other texts
							// chosen by dropdown menus on this page are also included in this database field.
							// The byte limit of 180 bytes is enforced in javascript
					"</td>
				</tr>";
			# Disallow watching is user is not logged in
			if ( $wgUser->isLoggedIn() ) {
				$out .= "
				<tr>
					<td></td>
					<td class='mw-input'>" .
						Xml::checkLabel( wfMessage( 'watchthis' )->text(),
							'mwProtectWatch', 'mwProtectWatch',
							$wgUser->isWatched( $this->mTitle ) || $wgUser->getOption( 'watchdefault' ) ) .
					"</td>
				</tr>";
			}
			$out .= "
				<tr>
					<td></td>
					<td class='mw-submit'>" .
						Xml::submitButton(
							wfMessage( 'confirm' )->text(),
							array( 'id' => 'mw-Protect-submit' )
						) .
					"</td>
				</tr>\n";
			$out .= Xml::closeElement( 'tbody' ) . Xml::closeElement( 'table' );
		}
		$out .= Xml::closeElement( 'fieldset' );

		if ( $wgUser->isAllowed( 'editinterface' ) ) {
			$title = Title::makeTitle( NS_MEDIAWIKI, 'Protect-dropdown' );
			$link = Linker::link(
				$title,
				wfMessage( 'protect-edit-reasonlist' )->escaped(),
				array(),
				array( 'action' => 'edit' )
			);
			$out .= '<p class="mw-protect-editreasons">' . $link . '</p>';
		}

		if ( !$this->disabled ) {
			$out .= Html::hidden( 'wpEditToken', $wgUser->getEditToken( array( 'protect', $this->mTitle->getPrefixedDBkey() ) ) );
			$out .= Xml::closeElement( 'form' );
			$wgOut->addScript( $this->buildCleanupScript() );
		}

		return $out;
	}

	/**
	 * Build protection level selector
	 *
	 * @param string $action action to protect
	 * @param string $selected current protection level
	 * @return String: HTML fragment
	 */
	function buildSelector( $action, $selected ) {
		global $wgUser;

		// If the form is disabled, display all relevant levels. Otherwise,
		// just show the ones this user can use.
		$levels = MWNamespace::getRestrictionLevels( $this->mTitle->getNamespace(),
			$this->disabled ? null : $wgUser
		);

		$id = 'mwProtect-level-' . $action;
		$attribs = array(
			'id' => $id,
			'name' => $id,
			'size' => count( $levels ),
			'onchange' => 'ProtectionForm.updateLevels(this)',
			) + $this->disabledAttrib;

		$out = Xml::openElement( 'select', $attribs );
		foreach ( $levels as $key ) {
			$out .= Xml::option( $this->getOptionLabel( $key ), $key, $key == $selected );
		}
		$out .= Xml::closeElement( 'select' );
		return $out;
	}

	/**
	 * Prepare the label for a protection selector option
	 *
	 * @param string $permission permission required
	 * @return String
	 */
	private function getOptionLabel( $permission ) {
		if ( $permission == '' ) {
			return wfMessage( 'protect-default' )->text();
		} else {
			// Messages: protect-level-autoconfirmed, protect-level-sysop
			$msg = wfMessage( "protect-level-{$permission}" );
			if ( $msg->exists() ) {
				return $msg->text();
			}
			return wfMessage( 'protect-fallback', $permission )->text();
		}
	}

	function buildCleanupScript() {
		global $wgCascadingRestrictionLevels, $wgOut;

		$cascadeableLevels = $wgCascadingRestrictionLevels;
		$options = array(
			'tableId' => 'mwProtectSet',
			'labelText' => wfMessage( 'protect-unchain-permissions' )->plain(),
			'numTypes' => count( $this->mApplicableTypes ),
			'existingMatch' => count( array_unique( $this->mExistingExpiry ) ) === 1,
		);

		$wgOut->addJsConfigVars( 'wgCascadeableLevels', $cascadeableLevels );
		$script = Xml::encodeJsCall( 'ProtectionForm.init', array( $options ) );
		return Html::inlineScript( ResourceLoader::makeLoaderConditionalScript( $script ) );
	}

	/**
	 * Show protection long extracts for this page
	 *
	 * @param $out OutputPage
	 * @access private
	 */
	function showLogExtract( &$out ) {
		# Show relevant lines from the protection log:
		$protectLogPage = new LogPage( 'protect' );
		$out->addHTML( Xml::element( 'h2', null, $protectLogPage->getName()->text() ) );
		LogEventsList::showLogExtract( $out, 'protect', $this->mTitle );
		# Let extensions add other relevant log extracts
		wfRunHooks( 'ProtectionForm::showLogExtract', array( $this->mArticle, $out ) );
	}
}
