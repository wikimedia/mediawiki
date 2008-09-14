<?php
/**
 * Copyright (C) 2005 Brion Vibber <brion@pobox.com>
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
 */

/**
 * @todo document, briefly.
 */
class ProtectionForm {
	var $mRestrictions = array();
	var $mReason = '';
	var $mReasonList = '';
	var $mCascade = false;
	var $mExpiry =array();
	var $mExpiryList = array();
	var $mPermErrors = array();
	var $mApplicableTypes = array();

	function __construct( Article $article ) {
		global $wgRequest, $wgUser;
		global $wgRestrictionTypes, $wgRestrictionLevels;
		$this->mArticle = $article;
		$this->mTitle = $article->mTitle;
		$this->mApplicableTypes = $this->mTitle->exists() ? $wgRestrictionTypes : array('create');

		if( $this->mTitle ) {
			$this->mTitle->loadRestrictions();

			foreach( $this->mApplicableTypes as $action ) {
				// Fixme: this form currently requires individual selections,
				// but the db allows multiples separated by commas.
				$this->mRestrictions[$action] = implode( '', $this->mTitle->getRestrictions( $action ) );
				
				if ( $this->mTitle->mRestrictionsExpiry[$action] == 'infinity' ) {
					$this->mExpiry[$action] = 'infinite';
				} else if ( strlen($this->mTitle->mRestrictionsExpiry[$action]) == 0 ) {
					$this->mExpiry[$action] = '';
				} else {
					// FIXME: this format is not user friendly
					$this->mExpiry[$action] = wfTimestamp( TS_ISO_8601, $this->mTitle->mRestrictionsExpiry[$action] );
				}
			}
			$this->mCascade = $this->mTitle->areRestrictionsCascading();
		}

		// The form will be available in read-only to show levels.
		$this->disabled = wfReadOnly() || ($this->mPermErrors = $this->mTitle->getUserPermissionsErrors('protect',$wgUser)) != array();
		$this->disabledAttrib = $this->disabled
			? array( 'disabled' => 'disabled' )
			: array();

		$this->mReason = $wgRequest->getText( 'mwProtect-reason' );
		$this->mReasonList = $wgRequest->getText( 'wpProtectReasonList' );
		$this->mCascade = $wgRequest->getBool( 'mwProtect-cascade', $this->mCascade );
		
		foreach( $this->mApplicableTypes as $action ) {
			// Let dropdown have 'infinite' for unprotected pages
			if( !($expiry[$action] = $wgRequest->getText( "mwProtect-expiry-$action" )) && $this->mExpiry[$action] != 'infinite' ) {
				$expiry[$action] = $this->mExpiry[$action];
			}
			$this->mExpiry[$action] = $expiry[$action];
			$this->mExpiryList[$action] = $wgRequest->getText( "wpProtectExpiryList-$action", $this->mExpiry[$action] ? '' : 'infinite' );

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

	function show( $err = null ) {
		global $wgOut, $wgUser;

		$wgOut->setRobotPolicy( 'noindex,nofollow' );

		if( is_null( $this->mTitle ) ||
			$this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
			$wgOut->showFatalError( wfMsg( 'badarticleerror' ) );
			return;
		}

		list( $cascadeSources, /* $restrictions */ ) = $this->mTitle->getCascadeProtectionSources();

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsgHtml( 'formerror' ) );
			$wgOut->addHTML( "<p class='error'>{$err}</p>\n" );
		}

		if ( $cascadeSources && count($cascadeSources) > 0 ) {
			$titles = '';

			foreach ( $cascadeSources as $title ) {
				$titles .= '* [[:' . $title->getPrefixedText() . "]]\n";
			}

			$wgOut->wrapWikiMsg( "$1\n$titles", array( 'protect-cascadeon', count($cascadeSources) ) );
		}

		$sk = $wgUser->getSkin();
		$titleLink = $sk->makeLinkObj( $this->mTitle );
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

	function save() {
		global $wgRequest, $wgUser, $wgOut;
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
		$reasonstr = $this->mReasonList;
		if ( $reasonstr != 'other' && $this->mReason != '' ) {
			// Entry from drop down menu + additional comment
			$reasonstr .= ': ' . $this->mReason;
		} elseif ( $reasonstr == 'other' ) {
			$reasonstr = $this->mReason;
		}
		$expiry = array();
		foreach( $this->mApplicableTypes as $action ) {
			# Custom expiry takes precedence
			if ( strlen( $wgRequest->getText( "mwProtect-expiry-$action" ) ) == 0 ) {
				$this->mExpiry[$action] = strlen($wgRequest->getText( "wpProtectExpiryList-$action")) ? $wgRequest->getText( "wpProtectExpiryList-$action") : 'infinite';
			} else {
				$this->mExpiry[$action] = $wgRequest->getText( "mwProtect-expiry-$action" );
			}
			if ( $this->mExpiry[$action] == 'infinite' || $this->mExpiry[$action] == 'indefinite' ) {
				$expiry[$action] = Block::infinity();
			} else {
				# Convert GNU-style date, on error returns -1 for PHP <5.1 and false for PHP >=5.1
				$expiry[$action] = strtotime( $this->mExpiry[$action] );

				if ( $expiry[$action] < 0 || $expiry[$action] === false ) {
					$this->show( wfMsg( 'protect_expiry_invalid' ) );
					return false;
				}

				// Fixme: non-qualified absolute times are not in users specified timezone
				// and there isn't notice about it in the ui
				$expiry[$action] = wfTimestamp( TS_MW, $expiry[$action] );

				if ( $expiry[$action] < wfTimestampNow() ) {
					$this->show( wfMsg( 'protect_expiry_old' ) );
					return false;
				}
			}
		}
		# They shouldn't be able to do this anyway, but just to make sure, ensure that cascading restrictions aren't being applied
		#  to a semi-protected page.
		global $wgGroupPermissions;

		$edit_restriction = $this->mRestrictions['edit'];
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

		if( $wgRequest->getCheck( 'mwProtectWatch' ) ) {
			$this->mArticle->doWatch();
		} elseif( $this->mTitle->userIsWatching() ) {
			$this->mArticle->doUnwatch();
		}

		return $ok;
	}

	/**
	 * Build the input form
	 *
	 * @return $out string HTML form
	 */
	function buildForm() {
		global $wgUser;

		$mProtectreasonother = Xml::label( wfMsg( 'protectcomment' ), 'wpProtectReasonList' );
		$mProtectreason = Xml::label( wfMsg( 'protect-otherreason' ), 'mwProtect-reason' );

		$out = '';
		if( !$this->disabled ) {
			$out .= $this->buildScript();
			// The submission needs to reenable the move permission selector
			// if it's in locked mode, or some browsers won't submit the data.
			$out .= Xml::openElement( 'form', array( 'method' => 'post', 
				'action' => $this->mTitle->getLocalUrl( 'action=protect' ), 
				'id' => 'mw-Protect-Form', 'onsubmit' => 'protectEnable(true)' ) );
			$out .= Xml::hidden( 'wpEditToken',$wgUser->editToken() );
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
			$label = Xml::element( 'label', array( 'for' => "mwProtect-level-$action" ), $msg );
			$out .= "<tr><td><table>" .
				"<tr><th>$label</th><th></th></tr>" .
				"<tr><td>" . $this->buildSelector( $action, $selected ) . "</td><td>";

			$reasonDropDown = Xml::listDropDown( 'wpProtectReasonList',
				wfMsgForContent( 'protect-dropdown' ),
				wfMsgForContent( 'protect-otherreason-op' ), '', 'mwProtect-reason', 4 );
			$scExpiryOptions = wfMsgForContent( 'ipboptions' ); // FIXME: use its own message

			$showProtectOptions = ($scExpiryOptions !== '-' && !$this->disabled);

			$mProtectexpiry = Xml::label( wfMsg( 'protectexpiry' ), "mwProtectExpiryList-$action" );
			$mProtectother = Xml::label( wfMsg( 'protect-othertime' ), "mwProtect-$action-expires" );
			$expiryFormOptions = Xml::option( wfMsg( 'protect-othertime-op' ), "othertime" );
			foreach( explode(',', $scExpiryOptions) as $option ) {
				if ( strpos($option, ":") === false ) $option = "$option:$option";
				list($show, $value) = explode(":", $option);
				$show = htmlspecialchars($show);
				$value = htmlspecialchars($value);
				$expiryFormOptions .= Xml::option( $show, $value, $this->mExpiryList[$action] === $value ? true : false ) . "\n";
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
									'id' => "mwProtectExpiryList-$action",
									'name' => "wpProtectExpiryList-$action",
									'onchange' => "protectExpiryListUpdate(this)",
									'tabindex' => '2' ) + $this->disabledAttrib,
								$expiryFormOptions ) .
						"</td>
					</tr></table>";
			}
			# Add custom expiry field
			$attribs = array( 'id' => "mwProtect-$action-expires", 'onkeyup' => 'protectExpiryUpdate(this)' ) + $this->disabledAttrib;
			$out .= "<table><tr>
					<td class='mw-label'>" .
						$mProtectother .
					'</td>
					<td class="mw-input">' .
						Xml::input( "mwProtect-expiry-$action", 60, $this->mExpiry[$action], $attribs ) .
					'</td>
				</tr></table>';
			$out .= "</td></tr></table></td></tr>";
		}

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
				</tr>
				<tr>
					<td></td>
					<td class='mw-input'>" .
						Xml::checkLabel( wfMsg( 'watchthis' ),
							'mwProtectWatch', 'mwProtectWatch',
							$this->mTitle->userIsWatching() || $wgUser->getOption( 'watchdefault' ) ) .
					"</td>
				</tr>
				<tr>
					<td></td>
					<td class='mw-submit'>" .
						Xml::submitButton( wfMsg( 'confirm' ), array( 'id' => 'mw-Protect-submit' ) ) .
					"</td>
				</tr>\n";
			$out .= Xml::closeElement( 'tbody' ) . Xml::closeElement( 'table' );
		}
		$out .= Xml::closeElement( 'fieldset' );

		if ( !$this->disabled ) {
			$out .= Xml::closeElement( 'form' ) .
				$this->buildCleanupScript();
		}

		return $out;
	}

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
			'onchange' => 'protectLevelsUpdate(this)',
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
	 * @param string $permission Permission required
	 * @return string
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

	function buildScript() {
		global $wgStylePath, $wgStyleVersion;
		return Xml::tags( 'script', array(
			'type' => 'text/javascript',
			'src' => $wgStylePath . "/common/protect.js?$wgStyleVersion" ), '' );
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
		$script .= 'protectInitialize("mw-protect-table2","' . Xml::escapeJsString( wfMsg( 'protect-unchain' ) ) . 
			'","' . count($this->mApplicableTypes) . '")';
		return Xml::tags( 'script', array( 'type' => 'text/javascript' ), $script );
	}

	/**
	 * @param OutputPage $out
	 * @access private
	 */
	function showLogExtract( &$out ) {
		# Show relevant lines from the protection log:
		$out->addHTML( Xml::element( 'h2', null, LogPage::logName( 'protect' ) ) );
		LogEventsList::showLogExtract( $out, 'protect', $this->mTitle->getPrefixedText() );
	}
}
