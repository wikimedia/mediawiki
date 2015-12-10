<?php
/**
 * Implements Special:BotPasswords
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
 * @ingroup SpecialPage
 */

/**
 * Let users manage bot passwords
 *
 * @ingroup SpecialPage
 */
class SpecialBotPasswords extends FormSpecialPage {

	/** @var int Central user ID */
	private $userId = 0;

	/** @var BotPassword|null Bot password being edited, if any */
	private $botPassword = null;

	/** @var string Operation being performed: create, update, delete */
	private $operation = null;

	/** @var string New password set, for communication between onSubmit() and onSuccess() */
	private $password = null;

	public function __construct() {
		parent::__construct( 'BotPasswords', 'editmyprivateinfo' );
	}

	/**
	 * @return bool
	 */
	public function isListed() {
		return $this->getConfig()->get( 'EnableBotPasswords' );
	}

	/**
	 * Main execution point
	 * @param string|null $par
	 */
	function execute( $par ) {
		$this->getOutput()->disallowUserJs();

		$par = trim( $par );
		if ( strlen( $par ) === 0 ) {
			$par = null;
		} elseif ( strlen( $par ) > BotPassword::APPID_LENGTH ) {
			throw new ErrorPageError( 'botpasswords', 'botpasswords-bad-appid',
				array( htmlspecialchars( $par ) ) );
		}

		parent::execute( $par );
	}

	protected function checkExecutePermissions( User $user ) {
		parent::checkExecutePermissions( $user );

		if ( !$this->getConfig()->get( 'EnableBotPasswords' ) ) {
			throw new ErrorPageError( 'botpasswords', 'botpasswords-disabled' );
		}

		$this->userId = CentralIdLookup::factory()->centralIdFromLocalUser( $this->getUser() );
		if ( !$this->userId ) {
			throw new ErrorPageError( 'botpasswords', 'botpasswords-no-central-id' );
		}
	}

	protected function getFormFields() {
		$that = $this;
		$user = $this->getUser();
		$request = $this->getRequest();

		$dbr = wfGetDB( DB_SLAVE, array(), $this->getConfig()->get( 'BotPasswordsDatabase' ) );

		$fields = array();

		if ( $this->par !== null ) {
			$this->botPassword = BotPassword::newFromCentralId( $this->userId, $this->par );

			$sep = BotPassword::getSeparator();
			$fields[] = array(
				'type' => 'info',
				'label-message' => 'username',
				'default' => $this->getUser()->getName() . $sep . $this->par
			);

			if ( $this->botPassword ) {
				$fields['resetPassword'] = array(
					'type' => 'check',
					'label-message' => 'botpasswords-label-resetpassword',
				);
			}

			$lang = $this->getLanguage();
			$showGrants = MWGrants::getValidGrants();
			$fields['grants'] = array(
				'type' => 'checkmatrix',
				'label-message' => 'botpasswords-label-grants',
				'help-message' => 'botpasswords-help-grants',
				'columns' => array(
					$this->msg( 'botpasswords-label-grants-column' )->escaped() => 'grant'
				),
				'rows' => array_combine(
					array_map( 'MWGrants::getGrantsLink', $showGrants ),
					$showGrants
				),
				'default' => array_map(
					function( $g ) {
						return "grant-$g";
					},
					$this->botPassword ? $this->botPassword->getGrants() : array()
				),
				'tooltips' => array_combine(
					array_map( 'MWGrants::getGrantsLink', $showGrants ),
					array_map(
						function( $rights ) use ( $lang ) {
							return $lang->semicolonList( array_map( 'User::getRightDescription', $rights ) );
						},
						array_intersect_key( MWGrants::getRightsByGrant(), array_flip( $showGrants ) )
					)
				),
				'force-options-on' => array_map(
					function( $g ) {
						return "grant-$g";
					},
					MWGrants::getHiddenGrants()
				),
			);

			$restrictions = $this->botPassword
				? $this->botPassword->getRestrictions()
				: MWRestrictions::newDefault();
			$fields['restrictions'] = array(
				'type' => 'textarea',
				'label-message' => 'botpasswords-label-restrictions',
				'required' => true,
				'default' => $restrictions->toJson( true ),
				'rows' => 5,
				'validation-callback' => function ( $v ) {
					try {
						MWRestrictions::newFromJson( $v );
						return true;
					} catch ( InvalidArgumentException $ex ) {
						return $ex->getMessage();
					}
				},
			);

		} else {
			$res = $dbr->select(
				'bot_passwords',
				array( 'bp_app_id' ),
				array( 'bp_user' => $this->userId ),
				__METHOD__
			);
			foreach ( $res as $row ) {
				$fields[] = array(
					'section' => 'existing',
					'type' => 'info',
					'raw' => true,
					'default' => Linker::link(
						$this->getPageTitle( $row->bp_app_id ),
						htmlspecialchars( $row->bp_app_id ),
						array(),
						array(),
						array( 'known' )
					),
				);
			}

			$fields['appId'] = array(
				'section' => 'createnew',
				'type' => 'textwithbutton',
				'label-message' => 'botpasswords-label-appid',
				'buttondefault' => $this->msg( 'botpasswords-label-create' )->text(),
				'required' => true,
				'size' => BotPassword::APPID_LENGTH,
				'maxlength' => BotPassword::APPID_LENGTH,
				'validation-callback' => function ( $v ) {
					$v = trim( $v );
					return $v !== '' && strlen( $v ) <= BotPassword::APPID_LENGTH;
				},
			);

			$fields[] = array(
				'type' => 'hidden',
				'default' => 'new',
				'name' => 'op',
			);
		}

		return $fields;
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setId( 'mw-botpasswords-form' );
		$form->setTableId( 'mw-botpasswords-table' );
		$form->addPreText( $this->msg( 'botpasswords-summary' )->parseAsBlock() );

		if ( $this->par !== null ) {
			$form->setSubmitName( 'op' );
			if ( $this->botPassword ) {
				$form->setWrapperLegendMsg( 'botpasswords-editexisting' );
				$form->setSubmitTextMsg( 'botpasswords-label-update' );
				$form->addButton( 'op', $this->msg( 'botpasswords-label-delete' )->text() );
			} else {
				$form->setWrapperLegendMsg( 'botpasswords-createnew' );
				$form->setSubmitTextMsg( 'botpasswords-label-create' );
			}

			$form->addButton( 'op', $this->msg( 'botpasswords-label-cancel' )->text() );
		} else {
			$form->suppressDefaultSubmit();
		}
	}

	public function onSubmit( array $data ) {
		$op = $this->getRequest()->getVal( 'op', '' );

		switch ( $op ) {
			case 'new':
				$this->getOutput()->redirect( $this->getPageTitle( $data['appId'] )->getFullURL() );
				return false;

			case $this->msg( 'botpasswords-label-create' )->text():
				$this->operation = 'create';
				return $this->save( $data );

			case $this->msg( 'botpasswords-label-update' )->text():
				$this->operation = 'update';
				return $this->save( $data );

			case $this->msg( 'botpasswords-label-delete' )->text():
				$dbw = wfGetDB( DB_MASTER, array(), $this->getConfig()->get( 'BotPasswordsDatabase' ) );
				$dbw->delete(
					'bot_passwords',
					array( 'bp_user' => $this->userId, 'bp_app_id' => $this->par ),
					__METHOD__
				);
				$this->operation = 'delete';
				return Status::newGood();

			case $this->msg( 'botpasswords-label-cancel' )->text():
				$this->getOutput()->redirect( $this->getPageTitle()->getFullURL() );
				return false;
		}

		return false;
	}

	private function save( array $data ) {
		$conds = array(
			'bp_user' => $this->userId,
			'bp_app_id' => $this->par,
		);
		$fields = array(
			'bp_token' => MWCryptRand::generateHex( User::TOKEN_LENGTH ),
			'bp_restrictions' => MWRestrictions::newFromJson( $data['restrictions'] )->toJson(),
			'bp_grants' => FormatJson::encode( array_merge(
				MWGrants::getHiddenGrants(),
				preg_replace( '/^grant-/', '', $data['grants'] )
			) ),
		);
		if ( $this->operation === 'create' || !empty( $data['resetPassword'] ) ) {
			$this->password = PasswordFactory::generateRandomPasswordString(
				max( 32, $this->getConfig()->get( 'MinimalPasswordLength' ) )
			);
			$passwordFactory = new PasswordFactory();
			$passwordFactory->init( RequestContext::getMain()->getConfig() );
			$fields['bp_password'] = $passwordFactory->newFromPlaintext( $this->password )->toString();
		}

		$dbw = wfGetDB( DB_MASTER, array(), $this->getConfig()->get( 'BotPasswordsDatabase' ) );
		if ( $this->operation === 'create' ) {
			$dbw->insert(
				'bot_passwords',
				$fields + $conds,
				__METHOD__,
				array( 'IGNORE' )
			);
			if ( !$dbw->affectedRows() ) {
				return Status::newFatal( 'botpasswords-insert-failed', $this->par );
			}
		} else {
			$dbw->update(
				'bot_passwords',
				$fields,
				$conds,
				__METHOD__
			);
			if ( !$dbw->affectedRows() ) {
				return Status::newFatal( 'botpasswords-update-failed', $this->par );
			}
		}

		return Status::newGood();
	}

	public function onSuccess() {
		$out = $this->getOutput();

		switch ( $this->operation ) {
			case 'create':
				$out->setPageTitle( $this->msg( 'botpasswords-created-title' )->text() );
				$out->addWikiMsg( 'botpasswords-created-body', $this->par );
				break;

			case 'update':
				$out->setPageTitle( $this->msg( 'botpasswords-updated-title' )->text() );
				$out->addWikiMsg( 'botpasswords-updated-body', $this->par );
				break;

			case 'delete':
				$out->setPageTitle( $this->msg( 'botpasswords-deleted-title' )->text() );
				$out->addWikiMsg( 'botpasswords-deleted-body', $this->par );
				$this->password = null;
				break;
		}

		if ( $this->password !== null ) {
			$sep = BotPassword::getSeparator();
			$out->addWikiMsg(
				'botpasswords-newpassword',
				htmlspecialchars( $this->getUser()->getName() . $sep . $this->par ),
				htmlspecialchars( $this->password )
			);
			$this->password = null;
		}

		$out->addReturnTo( $this->getPageTitle() );
	}

	protected function getGroupName() {
		return 'users';
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}
}
