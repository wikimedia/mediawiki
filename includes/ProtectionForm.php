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
 * @addtogroup SpecialPage
 */
class ProtectionForm {
	var $mRestrictions = array();
	var $mReason = '';
	var $mCascade = false;
	var $mExpiry = null;

	function __construct( &$article ) {
		global $wgRequest, $wgUser;
		global $wgRestrictionTypes, $wgRestrictionLevels;
		$this->mArticle =& $article;
		$this->mTitle =& $article->mTitle;

		if( $this->mTitle ) {
			$this->mTitle->loadRestrictions();

			foreach( $wgRestrictionTypes as $action ) {
				// Fixme: this form currently requires individual selections,
				// but the db allows multiples separated by commas.
				$this->mRestrictions[$action] = implode( '', $this->mTitle->getRestrictions( $action ) );
			}

			$this->mCascade = $this->mTitle->areRestrictionsCascading();

			if ( $this->mTitle->mRestrictionsExpiry == 'infinity' ) {
				$this->mExpiry = 'infinite';
			} else if ( strlen($this->mTitle->mRestrictionsExpiry) == 0 ) {
				$this->mExpiry = '';
			} else {
				$this->mExpiry = wfTimestamp( TS_RFC2822, $this->mTitle->mRestrictionsExpiry );
			}
		}

		// The form will be available in read-only to show levels.
		$this->disabled = !$wgUser->isAllowed( 'protect' ) || wfReadOnly() || $wgUser->isBlocked();
		$this->disabledAttrib = $this->disabled
			? array( 'disabled' => 'disabled' )
			: array();

		if( $wgRequest->wasPosted() ) {
			$this->mReason = $wgRequest->getText( 'mwProtect-reason' );
			$this->mCascade = $wgRequest->getBool( 'mwProtect-cascade' );
			$this->mExpiry = $wgRequest->getText( 'mwProtect-expiry' );

			foreach( $wgRestrictionTypes as $action ) {
				$val = $wgRequest->getVal( "mwProtect-level-$action" );
				if( isset( $val ) && in_array( $val, $wgRestrictionLevels ) ) {
					$this->mRestrictions[$action] = $val;
				}
			}
		}
	}
	
	function execute() {
		global $wgRequest;
		if( $wgRequest->wasPosted() ) {
			if( $this->save() ) {
				global $wgOut;
				$wgOut->redirect( $this->mTitle->getFullUrl() );
			}
		} else {
			$this->show();
		}
	}

	function show( $err = null ) {
		global $wgOut, $wgUser;

		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		if( is_null( $this->mTitle ) ||
			!$this->mTitle->exists() ||
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

			$notice = wfMsgExt( 'protect-cascadeon', array('parsemag'), count($cascadeSources) ) . "\r\n$titles";

			$wgOut->addWikiText( $notice );
		}

		$wgOut->setPageTitle( wfMsg( 'confirmprotect' ) );
		$wgOut->setSubtitle( wfMsg( 'protectsub', $this->mTitle->getPrefixedText() ) );

		# Show an appropriate message if the user isn't allowed or able to change
		# the protection settings at this time
		if( $this->disabled ) {
			if( $wgUser->isAllowed( 'protect' ) ) {
				if( $wgUser->isBlocked() ) {
					# Blocked
					$message = 'protect-locked-blocked';
				} else {
					# Database lock
					$message = 'protect-locked-dblock';
				}
			} else {
				# Permission error
				$message = 'protect-locked-access';
			}
		} else {
			$message = 'protect-text';
		}
		$wgOut->addWikiText( wfMsg( $message, wfEscapeWikiText( $this->mTitle->getPrefixedText() ) ) );

		$wgOut->addHTML( $this->buildForm() );

		$this->showLogExtract( $wgOut );
	}

	function save() {
		global $wgRequest, $wgUser, $wgOut;
		
		if( $this->disabled ) {
			$this->show();
			return false;
		}

		$token = $wgRequest->getVal( 'wpEditToken' );
		if( !$wgUser->matchEditToken( $token ) ) {
			$this->show( wfMsg( 'sessionfailure' ) );
			return false;
		}

		if ( strlen( $this->mExpiry ) == 0 ) {
			$this->mExpiry = 'infinite';
		}

		if ( $this->mExpiry == 'infinite' || $this->mExpiry == 'indefinite' ) {
			$expiry = Block::infinity();
		} else {
			# Convert GNU-style date, on error returns -1 for PHP <5.1 and false for PHP >=5.1
			$expiry = strtotime( $this->mExpiry );

			if ( $expiry < 0 || $expiry === false ) {
				$this->show( wfMsg( 'protect_expiry_invalid' ) );
				return false;
			}

			$expiry = wfTimestamp( TS_MW, $expiry );

			if ( $expiry < wfTimestampNow() ) {
				$this->show( wfMsg( 'protect_expiry_old' ) );
				return false;
			}

		}

		$ok = $this->mArticle->updateRestrictions( $this->mRestrictions, $this->mReason, $this->mCascade, $expiry );
		if( !$ok ) {
			throw new FatalError( "Unknown error at restriction save time." );
		}
		return $ok;
	}

	function buildForm() {
		global $wgUser;

		$out = '';
		if( !$this->disabled ) {
			$out .= $this->buildScript();
			// The submission needs to reenable the move permission selector
			// if it's in locked mode, or some browsers won't submit the data.
			$out .= wfOpenElement( 'form', array(
				'id' => 'mw-Protect-Form',
				'action' => $this->mTitle->getLocalUrl( 'action=protect' ),
				'method' => 'post',
				'onsubmit' => 'protectEnable(true)' ) );

			$out .= wfElement( 'input', array(
				'type' => 'hidden',
				'name' => 'wpEditToken',
				'value' => $wgUser->editToken() ) );
		}

		$out .= "<table id='mwProtectSet'>";
		$out .= "<tbody>";
		$out .= "<tr>\n";
		foreach( $this->mRestrictions as $action => $required ) {
			/* Not all languages have V_x <-> N_x relation */
			$out .= "<th>" . wfMsgHtml( 'restriction-' . $action ) . "</th>\n";
		}
		$out .= "</tr>\n";
		$out .= "<tr>\n";
		foreach( $this->mRestrictions as $action => $selected ) {
			$out .= "<td>\n";
			$out .= $this->buildSelector( $action, $selected );
			$out .= "</td>\n";
		}
		$out .= "</tr>\n";

		// JavaScript will add another row with a value-chaining checkbox

		$out .= "</tbody>\n";
		$out .= "</table>\n";

		global $wgEnableCascadingProtection;

		if ($wgEnableCascadingProtection)
			$out .= $this->buildCascadeInput();

		$out .= "<table>\n";
		$out .= "<tbody>\n";

		$out .= $this->buildExpiryInput();

		if( !$this->disabled ) {
			$out .= "<tr><td>" . $this->buildReasonInput() . "</td></tr>\n";
			$out .= "<tr><td></td><td>" . $this->buildSubmit() . "</td></tr>\n";
		}

		$out .= "</tbody>\n";
		$out .= "</table>\n";

		if ( !$this->disabled ) {
			$out .= "</form>\n";
			$out .= $this->buildCleanupScript();
		}

		return $out;
	}

	function buildSelector( $action, $selected ) {
		global $wgRestrictionLevels;
		$id = 'mwProtect-level-' . $action;
		$attribs = array(
			'id' => $id,
			'name' => $id,
			'size' => count( $wgRestrictionLevels ),
			'onchange' => 'protectLevelsUpdate(this)',
			) + $this->disabledAttrib;

		$out = wfOpenElement( 'select', $attribs );
		foreach( $wgRestrictionLevels as $key ) {
			$out .= $this->buildOption( $key, $selected );
		}
		$out .= "</select>\n";
		return $out;
	}

	function buildOption( $key, $selected ) {
		$text = ( $key == '' )
			? wfMsg( 'protect-default' )
			: wfMsg( "protect-level-$key" );
		$selectedAttrib = ($selected == $key)
			? array( 'selected' => 'selected' )
			: array();
		return wfElement( 'option',
			array( 'value' => $key ) + $selectedAttrib,
			$text );
	}

	function buildReasonInput() {
		$id = 'mwProtect-reason';
		return wfElement( 'label', array(
				'id' => "$id-label",
				'for' => $id ),
				wfMsg( 'protectcomment' ) ) .
			'</td><td>' .
			wfElement( 'input', array(
				'size' => 60,
				'name' => $id,
				'id' => $id,
				'value' => $this->mReason ) );
	}

	function buildCascadeInput() {
		$id = 'mwProtect-cascade';
		$ci = wfCheckLabel( wfMsg( 'protect-cascade' ), $id, $id, $this->mCascade, $this->disabledAttrib);
		return $ci;
	}

	function buildExpiryInput() {
		$id = 'mwProtect-expiry';

		$ci = "<tr> <td align=\"right\">";
		$ci .= wfElement( 'label', array (
				'id' => "$id-label",
				'for' => $id ),
				wfMsg( 'protectexpiry' ) );
		$ci .= "</td> <td align=\"left\">";
		$ci .= wfElement( 'input', array(
				'size' => 60,
				'name' => $id,
				'id' => $id,
				'value' => $this->mExpiry ) + $this->disabledAttrib );
		$ci .= "</td></tr>";

		return $ci;
	}

	function buildSubmit() {
		return wfElement( 'input', array(
			'id' => 'mw-Protect-submit',
			'type' => 'submit',
			'value' => wfMsg( 'confirm' ) ) );
	}

	function buildScript() {
		global $wgStylePath, $wgStyleVersion;
		return '<script type="text/javascript" src="' .
			htmlspecialchars( $wgStylePath . "/common/protect.js?$wgStyleVersion" ) .
			'"></script>';
	}

	function buildCleanupScript() {
		global $wgRestrictionLevels, $wgGroupPermissions;
		$script = 'var wgCascadeableLevels=';
		$CascadeableLevels = array();
		foreach( $wgRestrictionLevels as $key ) {
			if ( isset($wgGroupPermissions[$key]['protect']) && $wgGroupPermissions[$key]['protect'] ) {
				$CascadeableLevels[]="'" . wfEscapeJsString($key) . "'";
			}
		}
		$script .= "[" . implode(',',$CascadeableLevels) . "];\n";
		$script .= 'protectInitialize("mwProtectSet","' . wfEscapeJsString( wfMsg( 'protect-unchain' ) ) . '")';
		return '<script type="text/javascript">' . $script . '</script>';
	}

	/**
	 * @param OutputPage $out
	 * @access private
	 */
	function showLogExtract( &$out ) {
		# Show relevant lines from the deletion log:
		$out->addHTML( "<h2>" . htmlspecialchars( LogPage::logName( 'protect' ) ) . "</h2>\n" );
		$logViewer = new LogViewer(
			new LogReader(
				new FauxRequest(
					array( 'page' => $this->mTitle->getPrefixedText(),
					       'type' => 'protect' ) ) ) );
		$logViewer->showList( $out );
	}
}

?>
