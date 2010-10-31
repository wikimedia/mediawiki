<?php
/**
 * Page protection
 *
 * Copyright © 2005 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
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

	function __construct( Article $article ) {
		global $wgUser;
		// Set instance variables.
		$this->mArticle = $article;
		$this->mTitle = $article->mTitle;
		$this->mApplicableTypes = $this->mTitle->getRestrictionTypes();
		
		// Check if the form should be disabled.
		// If it is, the form will be available in read-only to show levels.
		$this->mPermErrors = $this->mTitle->getUserPermissionsErrors('protect',$wgUser);
		$this->disabled = wfReadOnly() || $this->mPermErrors != array();
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
		global $wgRestrictionLevels;
		
		$this->mCascade = $this->mTitle->areRestrictionsCascading();

		$this->mReason = $wgRequest->getText( 'mwProtect-reason' );
		$this->mReasonSelection = $wgRequest->getText( 'wpProtectReasonSelection' );
		$this->mCascade = $wgRequest->getBool( 'mwProtect-cascade', $this->mCascade );

		foreach( $this->mApplicableTypes as $action ) {
			// Fixme: this form currently requires individual selections,
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
			if( isset( $val ) && in_array( $val, $wgRestrictionLevels ) ) {
				// Prevent users from setting levels that they cannot later unset
				if( $val == 'sysop' ) {
					// Special case, rewrite sysop to either protect and editprotected
					if( !$wgUser->isAllowed('protect') && !$wgUser->isAllowed('editprotected') )
						continue;
				} else {
					if( !$wgUser->isAllowed($val) )
						continue;
				}
				$this->mRestrictions[$action] = $val;
			}
		}
	}

	/**
	 * Get the expiry time for a given action, by combining the relevant inputs.
	 *
	 * @return 14-char timestamp or "infinity", or false if the input was invalid
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
			$time = Block::infinity();
		} else {
			$unix = strtotime( $value );

			if ( !$unix || $unix === -1 ) {
				return false;
			}

			// Fixme: non-qualified absolute times are not in users specified timezone
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
		if( $wgRequest->wasPosted() ) {
			if( $this->save() ) {
				$q = $this->mArticle->isRedirect() ? 'redirect=no' : '';
				$wgOut->redirect( $this->mTitle->getFullUrl( $q ) );
			}
		} else {
			$this->show();
		}
	}

	/**
	 * Show the input form with optional error message
	 *
	 * @param $err String: error message or null if there's no error
	 */
	function show( $err = null ) {
		global $wgOut, $wgUser;

		$wgOut->setRobotPolicy( 'noindex,nofollow' );

		if( is_null( $this->mTitle ) ||
			$this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
			$wgOut->showFatalError( wfMsg( 'badarticleerror' ) );
			return;
		}

		list( $cascadeSources, /* $restrictions */ ) = $this->mTitle->getCascadeProtectionSources();

		if ( $err != "" ) {
			$wgOut->setSubtitle( wfMsgHtml( 'formerror' ) );
			$wgOut->addHTML( "<p class='error'>{$err}</p>\n" );
		}

		if ( $cascadeSources && count($cascadeSources) > 0 ) {
			$titles = '';

			foreach ( $cascadeSources as $title ) {
				$titles .= '* [[:' . $title->getPrefixedText() . "]]\n";
			}

			$wgOut->wrapWikiMsg( "<div id=\"mw-protect-cascadeon\">\n$1\n" . $titles . "</div>", array( 'protect-cascadeon', count($cascadeSources) ) );
		}

		$sk = $wgUser->getSkin();
		$titleLink = $sk->link( $this->mTitle );
		$wgOut->setPageTitle( wfMsg( 'protect-title', $this->mTitle->getPrefixedText() ) );
		$wgOut->setSubtitle( wfMsg( 'protect-backlink', $titleLink ) );

		# Show an appropriate message if the user isn't allowed or able to change
		# the protection settings at this time
		if( $this->disabled ) {
			if( wfReadOnly() ) {
				$wgOut->readOnlyPage();
			} elseif( $this->mPermErrors ) {
				$wgOut->addWikiText( $wgOut->formatPermissionsErrorMessage( $this->mPermErrors ) );
			}
		} else {
			$wgOut->addWikiMsg( 'protect-text', $this->mTitle->getPrefixedText() );
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
		global $wgRequest, $wgUser;

		# Permission check!
		if ( $this->disabled ) {
			$this->show();
			return false;
		}

		$token = $wgRequest->getVal( 'wpEditToken' );
		if ( !$wgUser->matchEditToken( $token ) ) {
			$this->show( wfMsg( 'sessionfailure' ) );
			return false;
		}

		# Create reason string. Use list and/or custom string.
		$reasonstr = $this->mReasonSelection;
		if ( $reasonstr != 'other' && $this->mReason != '' ) {
			// Entry from drop down menu + additional comment
			$reasonstr .= wfMsgForContent( 'colon-separator' ) . $this->mReason;
		} elseif ( $reasonstr == 'other' ) {
			$reasonstr = $this->mReason;
		}
		$expiry = array();
		foreach( $this->mApplicableTypes as $action ) {
			$expiry[$action] = $this->getExpiry( $action );
			if( empty($this->mRestrictions[$action]) )
				continue; // unprotected
			if ( !$expiry[$action] ) {
				$this->show( wfMsg( 'protect_expiry_invalid' ) );
				return false;
			}
			if ( $expiry[$action] < wfTimestampNow() ) {
				$this->show( wfMsg( 'protect_expiry_old' ) );
				return false;
			}
		}

		# They shouldn't be able to do this anyway, but just to make sure, ensure that cascading restrictions aren't being applied
		#  to a semi-protected page.
		global $wgGroupPermissions;

		$edit_restriction = isset( $this->mRestrictions['edit'] ) ? $this->mRestrictions['edit'] : '';
		$this->mCascade = $wgRequest->getBool( 'mwProtect-cascade' );
		if ($this->mCascade && ($edit_restriction != 'protect') &&
			!(isset($wgGroupPermissions[$edit_restriction]['protect']) && $wgGroupPermissions[$edit_restriction]['protect'] ) )
			$this->mCascade = false;

		if ($this->mTitle->exists()) {
			$ok = $this->mArticle->updateRestrictions( $this->mRestrictions, $reasonstr, $this->mCascade, $expiry );
		} else {
			$ok = $this->mTitle->updateTitleProtection( $this->mRestrictions['create'], $reasonstr, $expiry['create'] );
		}

		if( !$ok ) {
			throw new FatalError( "Unknown error at restriction save time." );
		}

		$errorMsg = '';
		# Give extensions a change to handle added form items
		if( !wfRunHooks( 'ProtectionForm::save', array($this->mArticle,&$errorMsg) ) ) {
			throw new FatalError( "Unknown hook error at restriction save time." );
		}
		if( $errorMsg != '' ) {
			$this->show( $errorMsg );
			return false;
		}

		if( $wgRequest->getCheck( 'mwProtectWatch' ) && $wgUser->isLoggedIn() ) {
			$this->mArticle->doWatch();
		} elseif( $this->mTitle->userIsWatching() ) {
			$this->mArticle->doUnwatch();
		}
		return $ok;
	}

	/**
	 * Build the input form
	 *
	 * @return String: HTML form
	 */
	function buildForm() {
		global $wgUser, $wgLang, $wgOut;

		$mProtectreasonother = Xml::label( wfMsg( 'protectcomment' ), 'wpProtectReasonSelection' );
		$mProtectreason = Xml::label( wfMsg( 'protect-otherreason' ), 'mwProtect-reason' );

		$out = '';
		if( !$this->disabled ) {
			$wgOut->addModules( 'mediawiki.legacy.protect' );
			$out .= Xml::openElement( 'form', array( 'method' => 'post',
				'action' => $this->mTitle->getLocalUrl( 'action=protect' ),
				'id' => 'mw-Protect-Form', 'onsubmit' => 'ProtectionForm.enableUnchainedInputs(true)' ) );
			$out .= Html::hidden( 'wpEditToken',$wgUser->editToken() );
		}

		$out .= Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'protect-legend' ) ) .
			Xml::openElement( 'table', array( 'id' => 'mwProtectSet' ) ) .
			Xml::openElement( 'tbody' );

		foreach( $this->mRestrictions as $action => $selected ) {
			/* Not all languages have V_x <-> N_x relation */
			$msg = wfMsg( 'restriction-' . $action );
			if( wfEmptyMsg( 'restriction-' . $action, $msg ) ) {
				$msg = $action;
			}
			$out .= "<tr><td>".
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, $msg ) .
			Xml::openElement( 'table', array( 'id' => "mw-protect-table-$action" ) ) .
				"<tr><td>" . $this->buildSelector( $action, $selected ) . "</td></tr><tr><td>";

			$reasonDropDown = Xml::listDropDown( 'wpProtectReasonSelection',
				wfMsgForContent( 'protect-dropdown' ),
				wfMsgForContent( 'protect-otherreason-op' ),
				$this->mReasonSelection,
				'mwProtect-reason', 4 );
			$scExpiryOptions = wfMsgForContent( 'protect-expiry-options' );

			$showProtectOptions = ($scExpiryOptions !== '-' && !$this->disabled);

			$mProtectexpiry = Xml::label( wfMsg( 'protectexpiry' ), "mwProtectExpirySelection-$action" );
			$mProtectother = Xml::label( wfMsg( 'protect-othertime' ), "mwProtect-$action-expires" );

			$expiryFormOptions = '';
			if ( $this->mExistingExpiry[$action] && $this->mExistingExpiry[$action] != 'infinity' ) {
				$timestamp = $wgLang->timeanddate( $this->mExistingExpiry[$action] );
				$d = $wgLang->date( $this->mExistingExpiry[$action] );
				$t = $wgLang->time( $this->mExistingExpiry[$action] );
				$expiryFormOptions .=
					Xml::option(
						wfMsg( 'protect-existing-expiry', $timestamp, $d, $t ),
						'existing',
						$this->mExpirySelection[$action] == 'existing'
					) . "\n";
			}

			$expiryFormOptions .= Xml::option( wfMsg( 'protect-othertime-op' ), "othertime" ) . "\n";
			foreach( explode(',', $scExpiryOptions) as $option ) {
				if ( strpos($option, ":") === false ) {
					$show = $value = $option;
				} else {
					list($show, $value) = explode(":", $option);
				}
				$show = htmlspecialchars($show);
				$value = htmlspecialchars($value);
				$expiryFormOptions .= Xml::option( $show, $value, $this->mExpirySelection[$action] === $value ) . "\n";
			}
			# Add expiry dropdown
			if( $showProtectOptions && !$this->disabled ) {
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
		wfRunHooks( 'ProtectionForm::buildForm', array($this->mArticle,&$out) );

		$out .= Xml::closeElement( 'tbody' ) . Xml::closeElement( 'table' );

		// JavaScript will add another row with a value-chaining checkbox
		if( $this->mTitle->exists() ) {
			$out .= Xml::openElement( 'table', array( 'id' => 'mw-protect-table2' ) ) .
				Xml::openElement( 'tbody' );
			$out .= '<tr>
					<td></td>
					<td class="mw-input">' .
						Xml::checkLabel( wfMsg( 'protect-cascade' ), 'mwProtect-cascade', 'mwProtect-cascade',
							$this->mCascade, $this->disabledAttrib ) .
					"</td>
				</tr>\n";
			$out .= Xml::closeElement( 'tbody' ) . Xml::closeElement( 'table' );
		}

		# Add manual and custom reason field/selects as well as submit
		if( !$this->disabled ) {
			$out .=  Xml::openElement( 'table', array( 'id' => 'mw-protect-table3' ) ) .
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
							'id' => 'mwProtect-reason', 'maxlength' => 255 ) ) .
					"</td>
				</tr>";
			# Disallow watching is user is not logged in
			if( $wgUser->isLoggedIn() ) {
				$out .= "
				<tr>
					<td></td>
					<td class='mw-input'>" .
						Xml::checkLabel( wfMsg( 'watchthis' ),
							'mwProtectWatch', 'mwProtectWatch',
							$this->mTitle->userIsWatching() || $wgUser->getOption( 'watchdefault' ) ) .
					"</td>
				</tr>";
			}
			$out .= "
				<tr>
					<td></td>
					<td class='mw-submit'>" .
						Xml::submitButton( wfMsg( 'confirm' ), array( 'id' => 'mw-Protect-submit' ) ) .
					"</td>
				</tr>\n";
			$out .= Xml::closeElement( 'tbody' ) . Xml::closeElement( 'table' );
		}
		$out .= Xml::closeElement( 'fieldset' );

		if ( $wgUser->isAllowed( 'editinterface' ) ) {
			$title = Title::makeTitle( NS_MEDIAWIKI, 'Protect-dropdown' );
			$link = $wgUser->getSkin()->link(
				$title,
				wfMsgHtml( 'protect-edit-reasonlist' ),
				array(),
				array( 'action' => 'edit' )
			);
			$out .= '<p class="mw-protect-editreasons">' . $link . '</p>';
		}

		if ( !$this->disabled ) {
			$out .= Xml::closeElement( 'form' );
			$wgOut->addScript( $this->buildCleanupScript() );
		}

		return $out;
	}

	/**
	 * Build protection level selector
	 *
	 * @param $action String: action to protect
	 * @param $selected String: current protection level
	 * @return String: HTML fragment
	 */
	function buildSelector( $action, $selected ) {
		global $wgRestrictionLevels, $wgUser;

		$levels = array();
		foreach( $wgRestrictionLevels as $key ) {
			//don't let them choose levels above their own (aka so they can still unprotect and edit the page). but only when the form isn't disabled
			if( $key == 'sysop' ) {
				//special case, rewrite sysop to protect and editprotected
				if( !$wgUser->isAllowed('protect') && !$wgUser->isAllowed('editprotected') && !$this->disabled )
					continue;
			} else {
				if( !$wgUser->isAllowed($key) && !$this->disabled )
					continue;
			}
			$levels[] = $key;
		}

		$id = 'mwProtect-level-' . $action;
		$attribs = array(
			'id' => $id,
			'name' => $id,
			'size' => count( $levels ),
			'onchange' => 'ProtectionForm.updateLevels(this)',
			) + $this->disabledAttrib;

		$out = Xml::openElement( 'select', $attribs );
		foreach( $levels as $key ) {
			$out .= Xml::option( $this->getOptionLabel( $key ), $key, $key == $selected );
		}
		$out .= Xml::closeElement( 'select' );
		return $out;
	}

	/**
	 * Prepare the label for a protection selector option
	 *
	 * @param $permission String: permission required
	 * @return String
	 */
	private function getOptionLabel( $permission ) {
		if( $permission == '' ) {
			return wfMsg( 'protect-default' );
		} else {
			$key = "protect-level-{$permission}";
			$msg = wfMsg( $key );
			if( wfEmptyMsg( $key, $msg ) )
				$msg = wfMsg( 'protect-fallback', $permission );
			return $msg;
		}
	}
	
	function buildCleanupScript() {
		global $wgRestrictionLevels, $wgGroupPermissions;
		$script = 'var wgCascadeableLevels=';
		$CascadeableLevels = array();
		foreach( $wgRestrictionLevels as $key ) {
			if ( (isset($wgGroupPermissions[$key]['protect']) && $wgGroupPermissions[$key]['protect']) || $key == 'protect' ) {
				$CascadeableLevels[] = "'" . Xml::escapeJsString( $key ) . "'";
			}
		}
		$script .= "[" . implode(',',$CascadeableLevels) . "];\n";
		$options = (object)array(
			'tableId' => 'mwProtectSet',
			'labelText' => wfMsg( 'protect-unchain-permissions' ),
			'numTypes' => count($this->mApplicableTypes),
			'existingMatch' => 1 == count( array_unique( $this->mExistingExpiry ) ),
		);
		$encOptions = Xml::encodeJsVar( $options );

		$script .= "ProtectionForm.init($encOptions)";
		return Html::inlineScript( "if ( window.mediaWiki ) { $script }" );
	}

	/**
	 * Show protection long extracts for this page
	 *
	 * @param $out OutputPage
	 * @access private
	 */
	function showLogExtract( &$out ) {
		# Show relevant lines from the protection log:
		$out->addHTML( Xml::element( 'h2', null, LogPage::logName( 'protect' ) ) );
		LogEventsList::showLogExtract( $out, 'protect', $this->mTitle->getPrefixedText() );
		# Let extensions add other relevant log extracts
		wfRunHooks( 'ProtectionForm::showLogExtract', array($this->mArticle,$out) );
	}
}
