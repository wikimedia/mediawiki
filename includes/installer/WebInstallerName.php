<?php
/**
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
 * @ingroup Deployment
 */

class WebInstallerName extends WebInstallerPage {

	/**
	 * @return string
	 */
	public function execute() {
		$r = $this->parent->request;
		if ( $r->wasPosted() ) {
			if ( $this->submit() ) {
				return 'continue';
			}
		}

		$this->startForm();

		// Encourage people to not name their site 'MediaWiki' by blanking the
		// field. I think that was the intent with the original $GLOBALS['wgSitename']
		// but these two always were the same so had the effect of making the
		// installer forget $wgSitename when navigating back to this page.
		if ( $this->getVar( 'wgSitename' ) == 'MediaWiki' ) {
			$this->setVar( 'wgSitename', '' );
		}

		// Set wgMetaNamespace to something valid before we show the form.
		// $wgMetaNamespace defaults to $wgSiteName which is 'MediaWiki'
		$metaNS = $this->getVar( 'wgMetaNamespace' );
		$this->setVar(
			'wgMetaNamespace',
			wfMessage( 'config-ns-other-default' )->inContentLanguage()->text()
		);

		$pingbackInfo = ( new Pingback() )->getSystemInfo();
		// Database isn't available in config yet, so take it
		// from the installer
		$pingbackInfo['database'] = $this->getVar( 'wgDBtype' );

		$this->addHTML(
			$this->parent->getTextBox( [
				'var' => 'wgSitename',
				'label' => 'config-site-name',
				'help' => $this->parent->getHelpBox( 'config-site-name-help' )
			] ) .
			// getRadioSet() builds a set of labeled radio buttons.
			// For grep: The following messages are used as the item labels:
			// config-ns-site-name, config-ns-generic, config-ns-other
			$this->parent->getRadioSet( [
				'var' => '_NamespaceType',
				'label' => 'config-project-namespace',
				'itemLabelPrefix' => 'config-ns-',
				'values' => [ 'site-name', 'generic', 'other' ],
				'commonAttribs' => [ 'class' => 'enableForOther',
					'rel' => 'config_wgMetaNamespace' ],
				'help' => $this->parent->getHelpBox( 'config-project-namespace-help' )
			] ) .
			$this->parent->getTextBox( [
				'var' => 'wgMetaNamespace',
				'label' => '', // @todo Needs a label?
				'attribs' => [ 'readonly' => 'readonly', 'class' => 'enabledByOther' ]
			] ) .
			$this->getFieldsetStart( 'config-admin-box' ) .
			$this->parent->getTextBox( [
				'var' => '_AdminName',
				'label' => 'config-admin-name',
				'help' => $this->parent->getHelpBox( 'config-admin-help' )
			] ) .
			$this->parent->getPasswordBox( [
				'var' => '_AdminPassword',
				'label' => 'config-admin-password',
			] ) .
			$this->parent->getPasswordBox( [
				'var' => '_AdminPasswordConfirm',
				'label' => 'config-admin-password-confirm'
			] ) .
			$this->parent->getTextBox( [
				'var' => '_AdminEmail',
				'attribs' => [
					'dir' => 'ltr',
				],
				'label' => 'config-admin-email',
				'help' => $this->parent->getHelpBox( 'config-admin-email-help' )
			] ) .
			$this->parent->getCheckBox( [
				'var' => '_Subscribe',
				'label' => 'config-subscribe',
				'help' => $this->parent->getHelpBox( 'config-subscribe-help' )
			] ) .
			$this->parent->getCheckBox( [
				'var' => 'wgPingback',
				'label' => 'config-pingback',
				'help' => $this->parent->getHelpBox(
					'config-pingback-help',
					FormatJson::encode( $pingbackInfo, true )
				),
				'value' => true,
			] ) .
			$this->getFieldsetEnd() .
			$this->parent->getInfoBox( wfMessage( 'config-almost-done' )->text() ) .
			// getRadioSet() builds a set of labeled radio buttons.
			// For grep: The following messages are used as the item labels:
			// config-optional-continue, config-optional-skip
			$this->parent->getRadioSet( [
				'var' => '_SkipOptional',
				'itemLabelPrefix' => 'config-optional-',
				'values' => [ 'continue', 'skip' ]
			] )
		);

		// Restore the default value
		$this->setVar( 'wgMetaNamespace', $metaNS );

		$this->endForm();

		return 'output';
	}

	/**
	 * @return bool
	 */
	public function submit() {
		global $wgPasswordPolicy;

		$retVal = true;
		$this->parent->setVarsFromRequest( [ 'wgSitename', '_NamespaceType',
			'_AdminName', '_AdminPassword', '_AdminPasswordConfirm', '_AdminEmail',
			'_Subscribe', '_SkipOptional', 'wgMetaNamespace', 'wgPingback' ] );

		// Validate site name
		if ( strval( $this->getVar( 'wgSitename' ) ) === '' ) {
			$this->parent->showError( 'config-site-name-blank' );
			$retVal = false;
		}

		// Fetch namespace
		$nsType = $this->getVar( '_NamespaceType' );
		if ( $nsType == 'site-name' ) {
			$name = $this->getVar( 'wgSitename' );
			// Sanitize for namespace
			// This algorithm should match the JS one in WebInstallerOutput.php
			$name = preg_replace( '/[\[\]\{\}|#<>%+? ]/', '_', $name );
			$name = str_replace( '&', '&amp;', $name );
			$name = preg_replace( '/__+/', '_', $name );
			$name = ucfirst( trim( $name, '_' ) );
		} elseif ( $nsType == 'generic' ) {
			$name = wfMessage( 'config-ns-generic' )->text();
		} else { // other
			$name = $this->getVar( 'wgMetaNamespace' );
		}

		// Validate namespace
		if ( strpos( $name, ':' ) !== false ) {
			$good = false;
		} else {
			// Title-style validation
			$title = Title::newFromText( $name );
			if ( !$title ) {
				$good = $nsType == 'site-name';
			} else {
				$name = $title->getDBkey();
				$good = true;
			}
		}
		if ( !$good ) {
			$this->parent->showError( 'config-ns-invalid', $name );
			$retVal = false;
		}

		// Make sure it won't conflict with any existing namespaces
		global $wgContLang;
		$nsIndex = $wgContLang->getNsIndex( $name );
		if ( $nsIndex !== false && $nsIndex !== NS_PROJECT ) {
			$this->parent->showError( 'config-ns-conflict', $name );
			$retVal = false;
		}

		$this->setVar( 'wgMetaNamespace', $name );

		// Validate username for creation
		$name = $this->getVar( '_AdminName' );
		if ( strval( $name ) === '' ) {
			$this->parent->showError( 'config-admin-name-blank' );
			$cname = $name;
			$retVal = false;
		} else {
			$cname = User::getCanonicalName( $name, 'creatable' );
			if ( $cname === false ) {
				$this->parent->showError( 'config-admin-name-invalid', $name );
				$retVal = false;
			} else {
				$this->setVar( '_AdminName', $cname );
			}
		}

		// Validate password
		$msg = false;
		$pwd = $this->getVar( '_AdminPassword' );
		$user = User::newFromName( $cname );
		if ( $user ) {
			$upp = new UserPasswordPolicy(
				$wgPasswordPolicy['policies'],
				$wgPasswordPolicy['checks']
			);
			$status = $upp->checkUserPasswordForGroups(
				$user,
				$pwd,
				[ 'bureaucrat', 'sysop' ]  // per Installer::createSysop()
			);
			$valid = $status->isGood() ? true : $status->getMessage();
		} else {
			$valid = 'config-admin-name-invalid';
		}
		if ( strval( $pwd ) === '' ) {
			// Provide a more specific and helpful message if password field is left blank
			$msg = 'config-admin-password-blank';
		} elseif ( $pwd !== $this->getVar( '_AdminPasswordConfirm' ) ) {
			$msg = 'config-admin-password-mismatch';
		} elseif ( $valid !== true ) {
			$msg = $valid;
		}
		if ( $msg !== false ) {
			call_user_func( [ $this->parent, 'showError' ], $msg );
			$this->setVar( '_AdminPassword', '' );
			$this->setVar( '_AdminPasswordConfirm', '' );
			$retVal = false;
		}

		// Validate e-mail if provided
		$email = $this->getVar( '_AdminEmail' );
		if ( $email && !Sanitizer::validateEmail( $email ) ) {
			$this->parent->showError( 'config-admin-error-bademail' );
			$retVal = false;
		}
		// If they asked to subscribe to mediawiki-announce but didn't give
		// an e-mail, show an error. T31332
		if ( !$email && $this->getVar( '_Subscribe' ) ) {
			$this->parent->showError( 'config-subscribe-noemail' );
			$retVal = false;
		}

		return $retVal;
	}

}
