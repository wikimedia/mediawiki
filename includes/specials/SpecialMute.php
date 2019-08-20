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

	const PAGE_NAME = 'Mute';

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

		parent::__construct( self::PAGE_NAME, '', false );
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
		$out->addModules( 'mediawiki.misc-authed-ooui' );
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
		$hookData = [];
		foreach ( $data as $userOption => $value ) {
			$hookData[$userOption]['before'] = $this->isTargetBlacklisted( $userOption );
			if ( $value ) {
				$this->muteTarget( $userOption );
			} else {
				$this->unmuteTarget( $userOption );
			}
			$hookData[$userOption]['after'] = (bool)$value;
		}

		// NOTE: this hook is temporary
		Hooks::run( 'SpecialMuteSubmit', [ $hookData ] );

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getDescription() {
		return $this->msg( 'specialmute' )->text();
	}

	/**
	 * Un-mute target
	 *
	 * @param string $userOption up_property key that holds the blacklist
	 */
	private function unmuteTarget( $userOption ) {
		$blacklist = $this->getBlacklist( $userOption );

		$key = array_search( $this->targetCentralId, $blacklist );
		if ( $key !== false ) {
			unset( $blacklist[$key] );
			$blacklist = implode( "\n", $blacklist );

			$user = $this->getUser();
			$user->setOption( $userOption, $blacklist );
			$user->saveSettings();
		}
	}

	/**
	 * Mute target
	 * @param string $userOption up_property key that holds the blacklist
	 */
	private function muteTarget( $userOption ) {
		// avoid duplicates just in case
		if ( !$this->isTargetBlacklisted( $userOption ) ) {
			$blacklist = $this->getBlacklist( $userOption );

			$blacklist[] = $this->targetCentralId;
			$blacklist = implode( "\n", $blacklist );

			$user = $this->getUser();
			$user->setOption( $userOption, $blacklist );
			$user->saveSettings();
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function getForm() {
		$form = parent::getForm();
		$form->setId( 'mw-specialmute-form' );
		$form->setHeaderText( $this->msg( 'specialmute-header', $this->target )->parse() );
		$form->setSubmitTextMsg( 'specialmute-submit' );
		$form->setSubmitID( 'save' );

		return $form;
	}

	/**
	 * @inheritDoc
	 */
	protected function getFormFields() {
		$fields = [];
		if (
			$this->enableUserEmailBlacklist &&
			$this->enableUserEmail &&
			$this->getUser()->getEmailAuthenticationTimestamp()
		) {
			$fields['email-blacklist'] = [
				'type' => 'check',
				'label-message' => 'specialmute-label-mute-email',
				'default' => $this->isTargetBlacklisted( 'email-blacklist' ),
			];
		}

		Hooks::run( 'SpecialMuteModifyFormFields', [ $this, &$fields ] );

		if ( count( $fields ) == 0 ) {
			throw new ErrorPageError( 'specialmute', 'specialmute-error-no-options' );
		}

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
	 * @param string $userOption
	 * @return bool
	 */
	public function isTargetBlacklisted( $userOption ) {
		$blacklist = $this->getBlacklist( $userOption );
		return in_array( $this->targetCentralId, $blacklist, true );
	}

	/**
	 * @param string $userOption
	 * @return array
	 */
	private function getBlacklist( $userOption ) {
		$blacklist = $this->getUser()->getOption( $userOption );
		if ( !$blacklist ) {
			return [];
		}

		return MultiUsernameFilter::splitIds( $blacklist );
	}
}
