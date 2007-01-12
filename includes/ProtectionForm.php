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
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

class ProtectionForm {
	var $mRestrictions = array();
	var $mReason = '';
	var $mCascade = false;

	function ProtectionForm( &$article ) {
		global $wgRequest, $wgUser;
		global $wgRestrictionTypes, $wgRestrictionLevels;
		$this->mArticle =& $article;
		$this->mTitle =& $article->mTitle;

		if( $this->mTitle ) {
			foreach( $wgRestrictionTypes as $action ) {
				// Fixme: this form currently requires individual selections,
				// but the db allows multiples separated by commas.
				$this->mRestrictions[$action] = implode( '', $this->mTitle->getRestrictions( $action ) );
			}

			$this->mCascade = $this->mTitle->areRestrictionsCascading();
		}

		// The form will be available in read-only to show levels.
		$this->disabled = !$wgUser->isAllowed( 'protect' ) || wfReadOnly() || $wgUser->isBlocked();
		$this->disabledAttrib = $this->disabled
			? array( 'disabled' => 'disabled' )
			: array();

		if( $wgRequest->wasPosted() ) {
			$this->mReason = $wgRequest->getText( 'mwProtect-reason' );
			$this->mCascade = $wgRequest->getBool( 'mwProtect-cascade' );
			foreach( $wgRestrictionTypes as $action ) {
				$val = $wgRequest->getVal( "mwProtect-level-$action" );
				if( isset( $val ) && in_array( $val, $wgRestrictionLevels ) ) {
					$this->mRestrictions[$action] = $val;
				}
			}
		}
	}

	function show() {
		global $wgOut;

		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		if( is_null( $this->mTitle ) ||
			!$this->mTitle->exists() ||
			$this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
			$wgOut->showFatalError( wfMsg( 'badarticleerror' ) );
			return;
		}

		if( $this->save() ) {
			$wgOut->redirect( $this->mTitle->getFullUrl() );
			return;
		}

		$wgOut->setPageTitle( wfMsg( 'confirmprotect' ) );
		$wgOut->setSubtitle( wfMsg( 'protectsub', $this->mTitle->getPrefixedText() ) );

		$wgOut->addWikiText(
			wfMsg( $this->disabled ? "protect-viewtext" : "protect-text",
				wfEscapeWikiText( $this->mTitle->getPrefixedText() ) ) );

		$wgOut->addHTML( $this->buildForm() );

		$this->showLogExtract( $wgOut );
	}

	function save() {
		global $wgRequest, $wgUser, $wgOut;
		if( !$wgRequest->wasPosted() ) {
			return false;
		}

		if( $this->disabled ) {
			return false;
		}

		$token = $wgRequest->getVal( 'wpEditToken' );
		if( !$wgUser->matchEditToken( $token ) ) {
			throw new FatalError( wfMsg( 'sessionfailure' ) );
		}

		$ok = $this->mArticle->updateRestrictions( $this->mRestrictions, $this->mReason, $this->mCascade );
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

		if( !$this->disabled ) {
			$out .= "<table>\n";
			$out .= "<tbody>\n";
			$out .= "<tr><td>" . $this->buildReasonInput() . "</td></tr>\n";
			$out .= "<tr><td></td><td>" . $this->buildSubmit() . "</td></tr>\n";
			$out .= "</tbody>\n";
			$out .= "</table>\n";
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
				'id' => $id ) );
	}

	function buildCascadeInput() {
		$id = 'mwProtect-cascade';
		$ci = wfCheckLabel( wfMsg( 'protect-cascade' ), $id, $id, $this->mCascade, $this->disabledAttrib);
		
		return $ci;
	}

	function buildSubmit() {
		return wfElement( 'input', array(
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
		return '<script type="text/javascript">protectInitialize("mwProtectSet","' .
			wfEscapeJsString( wfMsg( 'protect-unchain' ) ) . '")</script>';
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
