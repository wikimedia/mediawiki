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
 */

namespace MediaWiki\Specials;

use MediaWiki\Auth\AuthManager;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\Field\HTMLRestrictionsField;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Password\InvalidPassword;
use MediaWiki\Password\PasswordError;
use MediaWiki\Password\PasswordFactory;
use MediaWiki\Permissions\GrantsInfo;
use MediaWiki\Permissions\GrantsLocalization;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\User\BotPassword;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\User;
use Psr\Log\LoggerInterface;

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

	/** @var string|null Operation being performed: create, update, delete */
	private $operation = null;

	/** @var string|null New password set, for communication between onSubmit() and onSuccess() */
	private $password = null;

	private LoggerInterface $logger;
	private PasswordFactory $passwordFactory;
	private CentralIdLookup $centralIdLookup;
	private GrantsInfo $grantsInfo;
	private GrantsLocalization $grantsLocalization;

	public function __construct(
		PasswordFactory $passwordFactory,
		AuthManager $authManager,
		CentralIdLookup $centralIdLookup,
		GrantsInfo $grantsInfo,
		GrantsLocalization $grantsLocalization
	) {
		parent::__construct( 'BotPasswords', 'editmyprivateinfo' );
		$this->logger = LoggerFactory::getInstance( 'authentication' );
		$this->passwordFactory = $passwordFactory;
		$this->centralIdLookup = $centralIdLookup;
		$this->setAuthManager( $authManager );
		$this->grantsInfo = $grantsInfo;
		$this->grantsLocalization = $grantsLocalization;
	}

	/**
	 * @return bool
	 */
	public function isListed() {
		return $this->getConfig()->get( MainConfigNames::EnableBotPasswords );
	}

	protected function getLoginSecurityLevel() {
		return $this->getName();
	}

	/**
	 * Main execution point
	 * @param string|null $par
	 */
	public function execute( $par ) {
		$this->requireNamedUser();
		$this->getOutput()->disallowUserJs();
		$this->getOutput()->addModuleStyles( 'mediawiki.special' );
		$this->addHelpLink( 'Manual:Bot_passwords' );

		if ( $par !== null ) {
			$par = trim( $par );
			if ( $par === '' ) {
				$par = null;
			} elseif ( strlen( $par ) > BotPassword::APPID_MAXLENGTH ) {
				throw new ErrorPageError(
					'botpasswords', 'botpasswords-bad-appid', [ htmlspecialchars( $par ) ]
				);
			}
		}

		parent::execute( $par );
	}

	protected function checkExecutePermissions( User $user ) {
		parent::checkExecutePermissions( $user );

		if ( !$this->getConfig()->get( MainConfigNames::EnableBotPasswords ) ) {
			throw new ErrorPageError( 'botpasswords', 'botpasswords-disabled' );
		}

		$this->userId = $this->centralIdLookup->centralIdFromLocalUser( $this->getUser() );
		if ( !$this->userId ) {
			throw new ErrorPageError( 'botpasswords', 'botpasswords-no-central-id' );
		}
	}

	protected function getFormFields() {
		$fields = [];

		if ( $this->par !== null ) {
			$this->botPassword = BotPassword::newFromCentralId( $this->userId, $this->par );
			if ( !$this->botPassword ) {
				$this->botPassword = BotPassword::newUnsaved( [
					'centralId' => $this->userId,
					'appId' => $this->par,
				] );
			}

			$sep = BotPassword::getSeparator();
			$fields[] = [
				'type' => 'info',
				'label-message' => 'username',
				'default' => $this->getUser()->getName() . $sep . $this->par
			];

			if ( $this->botPassword->isSaved() ) {
				$fields['resetPassword'] = [
					'type' => 'check',
					'label-message' => 'botpasswords-label-resetpassword',
				];
				if ( $this->botPassword->isInvalid() ) {
					$fields['resetPassword']['default'] = true;
				}
			}

			$showGrants = $this->grantsInfo->getValidGrants();
			$grantNames = $this->grantsLocalization->getGrantDescriptionsWithClasses(
				$showGrants, $this->getLanguage() );

			$fields[] = [
				'type' => 'info',
				'default' => '',
				'help-message' => 'botpasswords-help-grants',
			];
			$fields['grants'] = [
				'type' => 'checkmatrix',
				'label-message' => 'botpasswords-label-grants',
				'columns' => [
					$this->msg( 'botpasswords-label-grants-column' )->escaped() => 'grant'
				],
				'rows' => array_combine(
					$grantNames,
					$showGrants
				),
				'default' => array_map(
					static function ( $g ) {
						return "grant-$g";
					},
					$this->botPassword->getGrants()
				),
				'tooltips-html' => array_combine(
					$grantNames,
					array_map(
						fn ( $rights ) => Html::rawElement( 'ul', [], implode( '', array_map(
							fn ( $right ) => Html::rawElement( 'li', [], $this->msg( "right-$right" )->parse() ),
							$rights
						) ) ),
						array_intersect_key( $this->grantsInfo->getRightsByGrant(),
							array_fill_keys( $showGrants, true ) )
					)
				),
				'force-options-on' => array_map(
					static function ( $g ) {
						return "grant-$g";
					},
					$this->grantsInfo->getHiddenGrants()
				),
			];

			$fields['restrictions'] = [
				'class' => HTMLRestrictionsField::class,
				'required' => true,
				'default' => $this->botPassword->getRestrictions(),
			];

		} else {
			$linkRenderer = $this->getLinkRenderer();

			$dbr = BotPassword::getReplicaDatabase();
			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'bp_app_id', 'bp_password' ] )
				->from( 'bot_passwords' )
				->where( [ 'bp_user' => $this->userId ] )
				->caller( __METHOD__ )->fetchResultSet();
			foreach ( $res as $row ) {
				try {
					$password = $this->passwordFactory->newFromCiphertext( $row->bp_password );
					$passwordInvalid = $password instanceof InvalidPassword;
					unset( $password );
				} catch ( PasswordError ) {
					$passwordInvalid = true;
				}

				$text = $linkRenderer->makeKnownLink(
					$this->getPageTitle( $row->bp_app_id ),
					$row->bp_app_id
				);
				if ( $passwordInvalid ) {
					$text .= $this->msg( 'word-separator' )->escaped()
						. $this->msg( 'botpasswords-label-needsreset' )->parse();
				}

				$fields[] = [
					'section' => 'existing',
					'type' => 'info',
					'raw' => true,
					'default' => $text,
				];
			}

			$fields['appId'] = [
				'section' => 'createnew',
				'type' => 'textwithbutton',
				'label-message' => 'botpasswords-label-appid',
				'buttondefault' => $this->msg( 'botpasswords-label-create' )->text(),
				'buttonflags' => [ 'progressive', 'primary' ],
				'required' => true,
				'size' => BotPassword::APPID_MAXLENGTH,
				'maxlength' => BotPassword::APPID_MAXLENGTH,
				'validation-callback' => static function ( $v ) {
					$v = trim( $v );
					return $v !== '' && strlen( $v ) <= BotPassword::APPID_MAXLENGTH;
				},
			];

			$fields[] = [
				'type' => 'hidden',
				'default' => 'new',
				'name' => 'op',
			];
		}

		return $fields;
	}

	protected function alterForm( HTMLForm $form ) {
		$form->setId( 'mw-botpasswords-form' );
		$form->setTableId( 'mw-botpasswords-table' );
		$form->suppressDefaultSubmit();

		if ( $this->par !== null ) {
			if ( $this->botPassword->isSaved() ) {
				$form->setWrapperLegendMsg( 'botpasswords-editexisting' );
				$form->addButton( [
					'name' => 'op',
					'value' => 'update',
					'label-message' => 'botpasswords-label-update',
					'flags' => [ 'primary', 'progressive' ],
				] );
				$form->addButton( [
					'name' => 'op',
					'value' => 'delete',
					'label-message' => 'botpasswords-label-delete',
					'flags' => [ 'destructive' ],
				] );
			} else {
				$form->setWrapperLegendMsg( 'botpasswords-createnew' );
				$form->addButton( [
					'name' => 'op',
					'value' => 'create',
					'label-message' => 'botpasswords-label-create',
					'flags' => [ 'primary', 'progressive' ],
				] );
			}

			$form->addButton( [
				'name' => 'op',
				'value' => 'cancel',
				'label-message' => 'botpasswords-label-cancel'
			] );
		}
	}

	public function onSubmit( array $data ) {
		$op = $this->getRequest()->getVal( 'op', '' );

		switch ( $op ) {
			case 'new':
				$this->getOutput()->redirect( $this->getPageTitle( $data['appId'] )->getFullURL() );
				return false;

			case 'create':
				$this->operation = 'insert';
				return $this->save( $data );

			case 'update':
				$this->operation = 'update';
				return $this->save( $data );

			case 'delete':
				$this->operation = 'delete';
				$bp = BotPassword::newFromCentralId( $this->userId, $this->par );
				if ( $bp ) {
					$bp->delete();
					$this->logger->info(
						"Bot password {op} for {user}@{app_id}",
						[
							'app_id' => $this->par,
							'user' => $this->getUser()->getName(),
							'centralId' => $this->userId,
							'op' => 'delete',
							'client_ip' => $this->getRequest()->getIP()
						]
					);
				}
				return Status::newGood();

			case 'cancel':
				$this->getOutput()->redirect( $this->getPageTitle()->getFullURL() );
				return false;
		}

		return false;
	}

	private function save( array $data ): Status {
		$bp = BotPassword::newUnsaved( [
			'centralId' => $this->userId,
			'appId' => $this->par,
			'restrictions' => $data['restrictions'],
			'grants' => array_merge(
				$this->grantsInfo->getHiddenGrants(),
				// @phan-suppress-next-next-line PhanTypeMismatchArgumentInternal See phan issue #3163,
				// it's probably failing to infer the type of $data['grants']
				preg_replace( '/^grant-/', '', $data['grants'] )
			)
		] );

		if ( $bp === null ) {
			// Messages: botpasswords-insert-failed, botpasswords-update-failed
			return Status::newFatal( "botpasswords-{$this->operation}-failed", $this->par );
		}

		if ( $this->operation === 'insert' || !empty( $data['resetPassword'] ) ) {
			$this->password = BotPassword::generatePassword( $this->getConfig() );
			$password = $this->passwordFactory->newFromPlaintext( $this->password );
		} else {
			$password = null;
		}

		$res = $bp->save( $this->operation, $password );

		$success = $res->isGood();

		$this->logger->info(
			'Bot password {op} for {user}@{app_id} ' . ( $success ? 'succeeded' : 'failed' ),
			[
				'op' => $this->operation,
				'user' => $this->getUser()->getName(),
				'app_id' => $this->par,
				'centralId' => $this->userId,
				'restrictions' => $data['restrictions'],
				'grants' => $bp->getGrants(),
				'client_ip' => $this->getRequest()->getIP(),
				'success' => $success,
			]
		);

		return $res;
	}

	public function onSuccess() {
		$out = $this->getOutput();

		$username = $this->getUser()->getName();
		switch ( $this->operation ) {
			case 'insert':
				$out->setPageTitleMsg( $this->msg( 'botpasswords-created-title' ) );
				$out->addWikiMsg( 'botpasswords-created-body', $this->par, $username );
				break;

			case 'update':
				$out->setPageTitleMsg( $this->msg( 'botpasswords-updated-title' ) );
				$out->addWikiMsg( 'botpasswords-updated-body', $this->par, $username );
				break;

			case 'delete':
				$out->setPageTitleMsg( $this->msg( 'botpasswords-deleted-title' ) );
				$out->addWikiMsg( 'botpasswords-deleted-body', $this->par, $username );
				$this->password = null;
				break;
		}

		if ( $this->password !== null ) {
			$sep = BotPassword::getSeparator();
			$out->addWikiMsg(
				'botpasswords-newpassword',
				htmlspecialchars( $username . $sep . $this->par ),
				htmlspecialchars( $this->password ),
				htmlspecialchars( $username ),
				htmlspecialchars( $this->par . $sep . $this->password )
			);
			$this->password = null;
		}

		$out->addReturnTo( $this->getPageTitle() );
	}

	protected function getGroupName() {
		return 'login';
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

	/**
	 * @codeCoverageIgnore Merely declarative
	 * @inheritDoc
	 */
	public function doesWrites() {
		return true;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialBotPasswords::class, 'SpecialBotPasswords' );
