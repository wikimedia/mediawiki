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
	/** @var array A map of action to restriction level, from request or default */
	protected $mRestrictions = array();

	/** @var string The custom/additional protection reason */
	protected $mReason = '';

	/** @var string The reason selected from the list, blank for other/additional */
	protected $mReasonSelection = '';

	/** @var bool True if the restrictions are cascading, from request or existing protection */
	protected $mCascade = false;

	/** @var array Map of action to "other" expiry time. Used in preference to mExpirySelection. */
	protected $mExpiry = array();

	/**
	 * @var array Map of action to value selected in expiry drop-down list.
	 * Will be set to 'othertime' whenever mExpiry is set.
	 */
	protected $mExpirySelection = array();

	/** @var array Permissions errors for the protect action */
	protected $mPermErrors = array();

	/** @var array Types (i.e. actions) for which levels can be selected */
	protected $mApplicableTypes = array();

	/** @var array Map of action to the expiry time of the existing protection */
	protected $mExistingExpiry = array();

	/** @var IContextSource */
	private $mContext;

	function __construct( Article $article ) {
		// Set instance variables.
		$this->mArticle = $article;
		$this->mTitle = $article->getTitle();
		$this->mApplicableTypes = $this->mTitle->getRestrictionTypes();
		$this->mContext = $article->getContext();

		// Check if the form should be disabled.
		// If it is, the form will be available in read-only to show levels.
		$this->mPermErrors = $this->mTitle->getUserPermissionsErrors(
			'protect', $this->mContext->getUser()
		);
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
		$levels = MWNamespace::getRestrictionLevels(
			$this->mTitle->getNamespace(), $this->mContext->getUser()
		);
		$this->mCascade = $this->mTitle->areRestrictionsCascading();

		$request = $this->mContext->getRequest();
		$this->mReason = $request->getText( 'mwProtect-reason' );
		$this->mReasonSelection = $request->getText( 'wpProtectReasonSelection' );
		$this->mCascade = $request->getBool( 'mwProtect-cascade', $this->mCascade );

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

			$requestExpiry = $request->getText( "mwProtect-expiry-$action" );
			$requestExpirySelection = $request->getVal( "wpProtectExpirySelection-$action" );

			if ( $requestExpiry ) {
				// Custom expiry takes precedence
				$this->mExpiry[$action] = $requestExpiry;
				$this->mExpirySelection[$action] = 'othertime';
			} elseif ( $requestExpirySelection ) {
				// Expiry selected from list
				$this->mExpiry[$action] = '';
				$this->mExpirySelection[$action] = $requestExpirySelection;
			} elseif ( $existingExpiry ) {
				// Use existing expiry in its own list item
				$this->mExpiry[$action] = '';
				$this->mExpirySelection[$action] = $existingExpiry;
			} else {
				// Catches 'infinity' - Existing expiry is infinite, use "infinite" in drop-down
				// Final default: infinite
				$this->mExpiry[$action] = '';
				$this->mExpirySelection[$action] = 'infinite';
			}

			$val = $request->getVal( "mwProtect-level-$action" );
			if ( isset( $val ) && in_array( $val, $levels ) ) {
				$this->mRestrictions[$action] = $val;
			}
		}
	}

	/**
	 * Get the expiry time for a given action, by combining the relevant inputs.
	 *
	 * @param string $action
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
		if ( wfIsInfinity( $value ) ) {
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
		if ( MWNamespace::getRestrictionLevels( $this->mTitle->getNamespace() ) === array( '' ) ) {
			throw new ErrorPageError( 'protect-badnamespace-title', 'protect-badnamespace-text' );
		}

		if ( $this->mContext->getRequest()->wasPosted() ) {
			if ( $this->save() ) {
				$q = $this->mArticle->isRedirect() ? 'redirect=no' : '';
				$this->mContext->getOutput()->redirect( $this->mTitle->getFullURL( $q ) );
			}
		} else {
			$this->show();
		}
	}

	/**
	 * Show the input form with optional error message
	 *
	 * @param string $err Error message or null if there's no error
	 */
	function show( $err = null ) {
		$out = $this->mContext->getOutput();
		$out->setRobotPolicy( 'noindex,nofollow' );
		$out->addBacklinkSubtitle( $this->mTitle );

		if ( is_array( $err ) ) {
			$out->wrapWikiMsg( "<p class='error'>\n$1\n</p>\n", $err );
		} elseif ( is_string( $err ) ) {
			$out->addHTML( "<p class='error'>{$err}</p>\n" );
		}

		if ( $this->mTitle->getRestrictionTypes() === array() ) {
			// No restriction types available for the current title
			// this might happen if an extension alters the available types
			$out->setPageTitle( $this->mContext->msg(
				'protect-norestrictiontypes-title',
				$this->mTitle->getPrefixedText()
			) );
			$out->addWikiText( $this->mContext->msg( 'protect-norestrictiontypes-text' )->plain() );

			// Show the log in case protection was possible once
			$this->showLogExtract( $out );
			// return as there isn't anything else we can do
			return;
		}

		list( $cascadeSources, /* $restrictions */ ) = $this->mTitle->getCascadeProtectionSources();
		if ( $cascadeSources && count( $cascadeSources ) > 0 ) {
			$titles = '';

			foreach ( $cascadeSources as $title ) {
				$titles .= '* [[:' . $title->getPrefixedText() . "]]\n";
			}

			/** @todo FIXME: i18n issue, should use formatted number. */
			$out->wrapWikiMsg(
				"<div id=\"mw-protect-cascadeon\">\n$1\n" . $titles . "</div>",
				array( 'protect-cascadeon', count( $cascadeSources ) )
			);
		}

		# Show an appropriate message if the user isn't allowed or able to change
		# the protection settings at this time
		if ( $this->disabled ) {
			$out->setPageTitle(
				$this->mContext->msg( 'protect-title-notallowed',
					$this->mTitle->getPrefixedText() )
			);
			$out->addWikiText( $out->formatPermissionsErrorMessage( $this->mPermErrors, 'protect' ) );
		} else {
			$out->setPageTitle( $this->mContext->msg( 'protect-title', $this->mTitle->getPrefixedText() ) );
			$out->addWikiMsg( 'protect-text',
				wfEscapeWikiText( $this->mTitle->getPrefixedText() ) );
		}

		$out->addHTML( $this->buildForm() );
		$this->showLogExtract( $out );
	}

	/**
	 * Save submitted protection form
	 *
	 * @return bool Success
	 */
	function save() {
		# Permission check!
		if ( $this->disabled ) {
			$this->show();
			return false;
		}

		$request = $this->mContext->getRequest();
		$user = $this->mContext->getUser();
		$out = $this->mContext->getOutput();
		$token = $request->getVal( 'wpEditToken' );
		if ( !$user->matchEditToken( $token, array( 'protect', $this->mTitle->getPrefixedDBkey() ) ) ) {
			$this->show( array( 'sessionfailure' ) );
			return false;
		}

		# Create reason string. Use list and/or custom string.
		$reasonstr = $this->mReasonSelection;
		if ( $reasonstr != 'other' && $this->mReason != '' ) {
			// Entry from drop down menu + additional comment
			$reasonstr .= $this->mContext->msg( 'colon-separator' )->text() . $this->mReason;
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

		$this->mCascade = $request->getBool( 'mwProtect-cascade' );

		$status = $this->mArticle->doUpdateRestrictions(
			$this->mRestrictions,
			$expiry,
			$this->mCascade,
			$reasonstr,
			$user
		);

		if ( !$status->isOK() ) {
			$this->show( $out->parseInline( $status->getWikiText() ) );
			return false;
		}

		/**
		 * Give extensions a change to handle added form items
		 *
		 * @since 1.19 you can (and you should) return false to abort saving;
		 *             you can also return an array of message name and its parameters
		 */
		$errorMsg = '';
		if ( !Hooks::run( 'ProtectionForm::save', array( $this->mArticle, &$errorMsg, $reasonstr ) ) ) {
			if ( $errorMsg == '' ) {
				$errorMsg = array( 'hookaborted' );
			}
		}
		if ( $errorMsg != '' ) {
			$this->show( $errorMsg );
			return false;
		}

		WatchAction::doWatchOrUnwatch( $request->getCheck( 'mwProtectWatch' ), $this->mTitle, $user );

		return true;
	}

	/**
	 * Build the input form
	 *
	 * @return string HTML form
	 */
	function buildForm() {
		$context = $this->mContext;
		$user = $context->getUser();
		$output = $context->getOutput();
		$lang = $context->getLanguage();
		$cascadingRestrictionLevels = $context->getConfig()->get( 'CascadingRestrictionLevels' );
		$out = '';
		if ( !$this->disabled ) {
			$output->addModules( 'mediawiki.legacy.protect' );
			$output->addJsConfigVars( 'wgCascadeableLevels', $cascadingRestrictionLevels );
			$out .= Xml::openElement( 'form', array( 'method' => 'post',
				'action' => $this->mTitle->getLocalURL( 'action=protect' ),
				'id' => 'mw-Protect-Form' ) );
		}

		$out .= Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, $context->msg( 'protect-legend' )->text() ) .
			Xml::openElement( 'table', array( 'id' => 'mwProtectSet' ) ) .
			Xml::openElement( 'tbody' );

		$scExpiryOptions = wfMessage( 'protect-expiry-options' )->inContentLanguage()->text();
		$showProtectOptions = $scExpiryOptions !== '-' && !$this->disabled;

		// Not all languages have V_x <-> N_x relation
		foreach ( $this->mRestrictions as $action => $selected ) {
			// Messages:
			// restriction-edit, restriction-move, restriction-create, restriction-upload
			$msg = $context->msg( 'restriction-' . $action );
			$out .= "<tr><td>" .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, $msg->exists() ? $msg->text() : $action ) .
			Xml::openElement( 'table', array( 'id' => "mw-protect-table-$action" ) ) .
				"<tr><td>" . $this->buildSelector( $action, $selected ) . "</td></tr><tr><td>";

			$mProtectexpiry = Xml::label(
				$context->msg( 'protectexpiry' )->text(),
				"mwProtectExpirySelection-$action"
			);
			$mProtectother = Xml::label(
				$context->msg( 'protect-othertime' )->text(),
				"mwProtect-$action-expires"
			);

			$expiryFormOptions = '';
			if ( $this->mExistingExpiry[$action] ) {
				if ( $this->mExistingExpiry[$action] == 'infinity' ) {
					$existingExpiryMessage = $context->msg( 'protect-existing-expiry-infinity' );
				} else {
					$timestamp = $lang->userTimeAndDate( $this->mExistingExpiry[$action], $user );
					$d = $lang->userDate( $this->mExistingExpiry[$action], $user );
					$t = $lang->userTime( $this->mExistingExpiry[$action], $user );
					$existingExpiryMessage = $context->msg( 'protect-existing-expiry', $timestamp, $d, $t );
				}
				$expiryFormOptions .=
					Xml::option(
						$existingExpiryMessage->text(),
						'existing',
						$this->mExpirySelection[$action] == 'existing'
					) . "\n";
			}

			$expiryFormOptions .= Xml::option(
				$context->msg( 'protect-othertime-op' )->text(),
				"othertime"
			) . "\n";
			foreach ( explode( ',', $scExpiryOptions ) as $option ) {
				if ( strpos( $option, ":" ) === false ) {
					$show = $value = $option;
				} else {
					list( $show, $value ) = explode( ":", $option );
				}
				$expiryFormOptions .= Xml::option(
					$show,
					htmlspecialchars( $value ),
					$this->mExpirySelection[$action] === $value
				) . "\n";
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
									'tabindex' => '2' ) + $this->disabledAttrib,
								$expiryFormOptions ) .
						"</td>
					</tr></table>";
			}
			# Add custom expiry field
			$attribs = array( 'id' => "mwProtect-$action-expires" ) + $this->disabledAttrib;
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
		Hooks::run( 'ProtectionForm::buildForm', array( $this->mArticle, &$out ) );

		$out .= Xml::closeElement( 'tbody' ) . Xml::closeElement( 'table' );

		// JavaScript will add another row with a value-chaining checkbox
		if ( $this->mTitle->exists() ) {
			$out .= Xml::openElement( 'table', array( 'id' => 'mw-protect-table2' ) ) .
				Xml::openElement( 'tbody' );
			$out .= '<tr>
					<td></td>
					<td class="mw-input">' .
						Xml::checkLabel(
							$context->msg( 'protect-cascade' )->text(),
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
			$mProtectreasonother = Xml::label(
				$context->msg( 'protectcomment' )->text(),
				'wpProtectReasonSelection'
			);

			$mProtectreason = Xml::label(
				$context->msg( 'protect-otherreason' )->text(),
				'mwProtect-reason'
			);

			$reasonDropDown = Xml::listDropDown( 'wpProtectReasonSelection',
				wfMessage( 'protect-dropdown' )->inContentLanguage()->text(),
				wfMessage( 'protect-otherreason-op' )->inContentLanguage()->text(),
				$this->mReasonSelection,
				'mwProtect-reason', 4 );

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
			if ( $user->isLoggedIn() ) {
				$out .= "
				<tr>
					<td></td>
					<td class='mw-input'>" .
						Xml::checkLabel( $context->msg( 'watchthis' )->text(),
							'mwProtectWatch', 'mwProtectWatch',
							$user->isWatched( $this->mTitle ) || $user->getOption( 'watchdefault' ) ) .
					"</td>
				</tr>";
			}
			$out .= "
				<tr>
					<td></td>
					<td class='mw-submit'>" .
						Xml::submitButton(
							$context->msg( 'confirm' )->text(),
							array( 'id' => 'mw-Protect-submit' )
						) .
					"</td>
				</tr>\n";
			$out .= Xml::closeElement( 'tbody' ) . Xml::closeElement( 'table' );
		}
		$out .= Xml::closeElement( 'fieldset' );

		if ( $user->isAllowed( 'editinterface' ) ) {
			$title = Title::makeTitle( NS_MEDIAWIKI, 'Protect-dropdown' );
			$link = Linker::link(
				$title,
				$context->msg( 'protect-edit-reasonlist' )->escaped(),
				array(),
				array( 'action' => 'edit' )
			);
			$out .= '<p class="mw-protect-editreasons">' . $link . '</p>';
		}

		if ( !$this->disabled ) {
			$out .= Html::hidden(
				'wpEditToken',
				$user->getEditToken( array( 'protect', $this->mTitle->getPrefixedDBkey() ) )
			);
			$out .= Xml::closeElement( 'form' );
		}

		return $out;
	}

	/**
	 * Build protection level selector
	 *
	 * @param string $action Action to protect
	 * @param string $selected Current protection level
	 * @return string HTML fragment
	 */
	function buildSelector( $action, $selected ) {
		// If the form is disabled, display all relevant levels. Otherwise,
		// just show the ones this user can use.
		$levels = MWNamespace::getRestrictionLevels( $this->mTitle->getNamespace(),
			$this->disabled ? null : $this->mContext->getUser()
		);

		$id = 'mwProtect-level-' . $action;
		$attribs = array(
			'id' => $id,
			'name' => $id,
			'size' => count( $levels ),
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
	 * @param string $permission Permission required
	 * @return string
	 */
	private function getOptionLabel( $permission ) {
		if ( $permission == '' ) {
			return $this->mContext->msg( 'protect-default' )->text();
		} else {
			// Messages: protect-level-autoconfirmed, protect-level-sysop
			$msg = $this->mContext->msg( "protect-level-{$permission}" );
			if ( $msg->exists() ) {
				return $msg->text();
			}
			return $this->mContext->msg( 'protect-fallback', $permission )->text();
		}
	}

	/**
	 * Show protection long extracts for this page
	 *
	 * @param OutputPage $out
	 * @access private
	 */
	function showLogExtract( &$out ) {
		# Show relevant lines from the protection log:
		$protectLogPage = new LogPage( 'protect' );
		$out->addHTML( Xml::element( 'h2', null, $protectLogPage->getName()->text() ) );
		LogEventsList::showLogExtract( $out, 'protect', $this->mTitle );
		# Let extensions add other relevant log extracts
		Hooks::run( 'ProtectionForm::showLogExtract', array( $this->mArticle, $out ) );
	}
}
