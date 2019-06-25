<?php
/*
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
 * @ingroup SpecialPage
 */
use MediaWiki\Preferences\MultiUsernameFilter;

/**
 * A special page that allows users to modify their notification
 * preferences
 *
 * @ingroup SpecialPage
 */
class SpecialMute extends FormSpecialPage {

	/** @var User */
	private $target;

	/** @var int */
	private $targetCentralId;

	/** @var bool */
	private $enableUserEmailBlacklist;

	/** @var bool */
	private $enableUserEmail;

	/** @var CentralIdLookup */
	private $centralIdLookup;

	public function __construct() {
		// TODO: inject all these dependencies once T222388 is resolved
		$config = RequestContext::getMain()->getConfig();
		$this->enableUserEmailBlacklist = $config->get( 'EnableUserEmailBlacklist' );
		$this->enableUserEmail = $config->get( 'EnableUserEmail' );

		$this->centralIdLookup = CentralIdLookup::factory();

		parent::__construct( 'Mute', '', false );
	}

	/**
	 * Entry point for special pages
	 *
	 * @param string $par
	 */
	public function execute( $par ) {
		$this->requireLogin( 'specialmute-login-required' );
		$this->loadTarget( $par );

		parent::execute( $par );

		$out = $this->getOutput();
		$out->addModules( 'mediawiki.special.pageLanguage' );
	}

	/**
	 * @inheritDoc
	 */
	public function requiresUnblock() {
		return false;
	}

	/**
	 * @inheritDoc
	 */
	protected function getDisplayFormat() {
		return 'ooui';
	}

	/**
	 * @inheritDoc
	 */
	public function onSuccess() {
		$out = $this->getOutput();
		$out->addWikiMsg( 'specialmute-success' );
	}

	/**
	 * @param array $data
	 * @param HTMLForm|null $form
	 * @return bool
	 */
	public function onSubmit( array $data, HTMLForm $form = null ) {
		if ( !empty( $data['MuteEmail'] ) ) {
			$this->muteEmailsFromTarget();
		} else {
			$this->unmuteEmailsFromTarget();
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getDescription() {
		return $this->msg( 'specialmute' )->text();
	}

	/**
	 * Un-mute emails from target
	 */
	private function unmuteEmailsFromTarget() {
		$blacklist = $this->getBlacklist();

		$key = array_search( $this->targetCentralId, $blacklist );
		if ( $key !== false ) {
			unset( $blacklist[$key] );
			$blacklist = implode( "\n", $blacklist );

			$user = $this->getUser();
			$user->setOption( 'email-blacklist', $blacklist );
			$user->saveSettings();
		}
	}

	/**
	 * Mute emails from target
	 */
	private function muteEmailsFromTarget() {
		// avoid duplicates just in case
		if ( !$this->isTargetBlacklisted() ) {
			$blacklist = $this->getBlacklist();

			$blacklist[] = $this->targetCentralId;
			$blacklist = implode( "\n", $blacklist );

			$user = $this->getUser();
			$user->setOption( 'email-blacklist', $blacklist );
			$user->saveSettings();
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function alterForm( HTMLForm $form ) {
		$form->setId( 'mw-specialmute-form' );
		$form->setHeaderText( $this->msg( 'specialmute-header', $this->target )->parse() );
		$form->setSubmitTextMsg( 'specialmute-submit' );
		$form->setSubmitID( 'save' );
	}

	/**
	 * @inheritDoc
	 */
	protected function getFormFields() {
		if ( !$this->enableUserEmailBlacklist || !$this->enableUserEmail ) {
			throw new ErrorPageError( 'specialmute', 'specialmute-error-email-blacklist-disabled' );
		}

		if ( !$this->getUser()->getEmailAuthenticationTimestamp() ) {
			throw new ErrorPageError( 'specialmute', 'specialmute-error-email-preferences' );
		}

		$fields['MuteEmail'] = [
			'type' => 'check',
			'label-message' => 'specialmute-label-mute-email',
			'default' => $this->isTargetBlacklisted(),
		];

		return $fields;
	}

	/**
	 * @param string $username
	 */
	private function loadTarget( $username ) {
		$target = User::newFromName( $username );
		if ( !$target || !$target->getId() ) {
			throw new ErrorPageError( 'specialmute', 'specialmute-error-invalid-user' );
		} else {
			$this->target = $target;
			$this->targetCentralId = $this->centralIdLookup->centralIdFromLocalUser( $target );
		}
	}

	/**
	 * @return bool
	 */
	private function isTargetBlacklisted() {
		$blacklist = $this->getBlacklist();
		return in_array( $this->targetCentralId, $blacklist );
	}

	/**
	 * @return array
	 */
	private function getBlacklist() {
		$blacklist = $this->getUser()->getOption( 'email-blacklist' );
		if ( !$blacklist ) {
			return [];
		}

		return MultiUsernameFilter::splitIds( $blacklist );
	}
}
