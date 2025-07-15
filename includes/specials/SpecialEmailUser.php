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

use MediaWiki\Exception\ErrorPageError;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Mail\EmailUserFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use StatusValue;

/**
 * Send an e-mail from one user to another.
 *
 * This is discoverable via the sidebar on any user's User namespace page.
 *
 * @ingroup SpecialPage
 * @ingroup Mail
 */
class SpecialEmailUser extends SpecialPage {

	private UserNameUtils $userNameUtils;
	private UserNamePrefixSearch $userNamePrefixSearch;
	private UserOptionsLookup $userOptionsLookup;
	private EmailUserFactory $emailUserFactory;
	private UserFactory $userFactory;

	public function __construct(
		UserNameUtils $userNameUtils,
		UserNamePrefixSearch $userNamePrefixSearch,
		UserOptionsLookup $userOptionsLookup,
		EmailUserFactory $emailUserFactory,
		UserFactory $userFactory
	) {
		parent::__construct( 'Emailuser' );
		$this->userNameUtils = $userNameUtils;
		$this->userNamePrefixSearch = $userNamePrefixSearch;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->emailUserFactory = $emailUserFactory;
		$this->userFactory = $userFactory;
	}

	public function doesWrites() {
		return true;
	}

	public function getDescription() {
		return $this->msg( 'emailuser-title-notarget' );
	}

	protected function getFormFields( User $target ): array {
		$linkRenderer = $this->getLinkRenderer();
		$user = $this->getUser();
		return [
			'From' => [
				'type' => 'info',
				'raw' => 1,
				'default' => $linkRenderer->makeLink(
					$user->getUserPage(),
					$user->getName()
				),
				'label-message' => 'emailfrom',
				'id' => 'mw-emailuser-sender',
			],
			'To' => [
				'type' => 'info',
				'raw' => 1,
				'default' => $linkRenderer->makeLink(
					$target->getUserPage(),
					$target->getName()
				),
				'label-message' => 'emailto',
				'id' => 'mw-emailuser-recipient',
			],
			'Target' => [
				'type' => 'hidden',
				'default' => $target->getName(),
			],
			'Subject' => [
				'type' => 'text',
				'default' => $this->msg( 'defemailsubject', $user->getName() )->inContentLanguage()->text(),
				'label-message' => 'emailsubject',
				'maxlength' => 200,
				'size' => 60,
				'required' => true,
			],
			'Text' => [
				'type' => 'textarea',
				'rows' => 20,
				'label-message' => 'emailmessage',
				'required' => true,
			],
			'CCMe' => [
				'type' => 'check',
				'label-message' => 'emailccme',
				'default' => $this->userOptionsLookup->getBoolOption( $user, 'ccmeonemails' ),
			],
		];
	}

	/**
	 * Handles a {@link StatusValue} from {@link EmailUser::canSend}.
	 */
	private function handleCanSendEmailStatus( StatusValue $status ): void {
		if ( !$status->isGood() ) {
			if ( $status instanceof PermissionStatus ) {
				$status->throwErrorPageError();
			} elseif ( $status->hasMessage( 'mailnologin' ) ) {
				throw new ErrorPageError( 'mailnologin', 'mailnologintext' );
			} elseif ( $status->hasMessage( 'usermaildisabled' ) ) {
				throw new ErrorPageError( 'usermaildisabled', 'usermaildisabledtext' );
			} elseif ( $status->getValue() !== null ) {
				// BC for deprecated hook errors
				// (to be removed when UserCanSendEmail and EmailUserPermissionsErrors are removed)
				$msg = $status->getMessages()[0];
				throw new ErrorPageError( $status->getValue(), $msg );
			} else {
				// Fallback in case new error types are added in EmailUser
				throw new ErrorPageError( $this->getDescription(), Status::wrap( $status )->getMessage() );
			}
		}
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$request = $this->getRequest();
		$out->addModuleStyles( 'mediawiki.special' );

		// Check if the user can send emails without authorizing the action.
		$emailUser = $this->emailUserFactory->newEmailUserBC(
			$this->getUser(),
			$this->getConfig()
		);
		$emailUser->setEditToken( (string)$request->getVal( 'wpEditToken' ) );
		$status = $emailUser->canSend();

		// If user emailing is disabled, then prioritise this error over anything else (including the
		// ::requireNamedUser check). This is because a user would still be unable access the form if they were to
		// log in or create account.
		if ( !$status->isGood() && $status->hasMessage( 'usermaildisabled' ) ) {
			$this->handleCanSendEmailStatus( $status );
		}

		// If the user is not logged into a named account, then display this error. This should redirect to
		// Special:UserLogin or Special:CreateAccount to not interrupt the flow.
		$this->requireNamedUser( 'mailnologintext', 'mailnologin' );

		$this->handleCanSendEmailStatus( $status );

		// Always go through the userform, it will do validations on the target
		// and display the emailform for us.
		$target = $par ?? $request->getVal( 'wpTarget', $request->getVal( 'target', '' ) );
		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable Defaults to empty string
		$this->userForm( $target );
	}

	/**
	 * Validate target User
	 *
	 * @param string $target Target user name
	 * @param User $sender User sending the email
	 * @return User|string User object on success or a string on error
	 * @deprecated since 1.42 Use UserFactory::newFromName() and EmailUser::validateTarget()
	 */
	public static function getTarget( $target, User $sender ) {
		$targetObject = MediaWikiServices::getInstance()->getUserFactory()->newFromName( $target );
		if ( !$targetObject instanceof User ) {
			return 'notarget';
		}

		$status = MediaWikiServices::getInstance()->getEmailUserFactory()
			->newEmailUser( $sender )
			->validateTarget( $targetObject );
		if ( !$status->isGood() ) {
			$msg = $status->getMessages()[0]->getKey();
			$ret = $msg === 'emailnotarget' ? 'notarget' : preg_replace( '/text$/', '', $msg );
		} else {
			$ret = $targetObject;
		}
		return $ret;
	}

	/**
	 * Form to ask for target user name.
	 *
	 * @param string $name User name submitted.
	 */
	protected function userForm( $name ) {
		$htmlForm = HTMLForm::factory( 'ooui', [
			'Target' => [
				'type' => 'user',
				'exists' => true,
				'required' => true,
				'label-message' => 'emailusername',
				'id' => 'emailusertarget',
				'autofocus' => true,
				// Exclude temporary accounts from the autocomplete, as they cannot have email addresses.
				'excludetemp' => true,
				// Skip validation when visit directly without subpage (T347854)
				'default' => '',
				// Prefill for subpage syntax and old target param.
				'filter-callback' => static function ( $value ) use ( $name ) {
					return str_replace( '_', ' ',
						( $value !== '' && $value !== false && $value !== null ) ? $value : $name );
				},
				'validation-callback' => function ( $value ) {
					// HTMLForm checked that this is a valid user name
					$target = $this->userFactory->newFromName( $value );
					$statusValue = $this->emailUserFactory
						// @phan-suppress-next-line PhanTypeMismatchArgumentNullable
						->newEmailUser( $this->getUser() )->validateTarget( $target );
					if ( !$statusValue->isGood() ) {
						// TODO: Return Status instead of StatusValue from validateTarget() method?
						return Status::wrap( $statusValue )->getMessage();
					}
					return true;
				}
			]
		], $this->getContext() );

		$htmlForm
			->setMethod( 'GET' )
			->setTitle( $this->getPageTitle() ) // Remove subpage
			->setSubmitCallback( $this->sendEmailForm( ... ) )
			->setId( 'askusername' )
			->setWrapperLegendMsg( 'emailtarget' )
			->setSubmitTextMsg( 'emailusernamesubmit' )
			->show();
	}

	/**
	 * @param array $data
	 * @return bool
	 */
	private function sendEmailForm( array $data ) {
		$out = $this->getOutput();

		// HTMLForm checked that this is a valid user name, the return value can never be null.
		$target = $this->userFactory->newFromName( $data['Target'] );
		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable
		$htmlForm = HTMLForm::factory( 'ooui', $this->getFormFields( $target ), $this->getContext() );
		$htmlForm
			->setTitle( $this->getPageTitle() ) // Remove subpage
			->addPreHtml( $this->msg( 'emailpagetext', $target->getName() )->parse() )
			->setSubmitTextMsg( 'emailsend' )
			->setSubmitCallback( $this->onFormSubmit( ... ) )
			->setWrapperLegendMsg( 'email-legend' )
			->prepareForm();

		if ( !$this->getHookRunner()->onEmailUserForm( $htmlForm ) ) {
			return false;
		}

		$result = $htmlForm->show();

		if ( $result === true || ( $result instanceof Status && $result->isGood() ) ) {
			$out->setPageTitleMsg( $this->msg( 'emailsent' ) );
			$out->addWikiMsg( 'emailsenttext', $target->getName() );
			$out->returnToMain( false, $target->getUserPage() );
		} else {
			$out->setPageTitleMsg( $this->msg( 'emailuser-title-target', $target->getName() ) );
		}
		return true;
	}

	/**
	 * @param array $data
	 * @return StatusValue|false
	 */
	private function onFormSubmit( array $data ) {
		// HTMLForm checked that this is a valid user name, the return value can never be null.
		$target = $this->userFactory->newFromName( $data['Target'] );

		$emailUser = $this->emailUserFactory->newEmailUser( $this->getAuthority() );
		$emailUser->setEditToken( $this->getRequest()->getVal( 'wpEditToken' ) );

		// Fully authorize on sending emails.
		$status = $emailUser->authorizeSend();

		if ( !$status->isOK() ) {
			return $status;
		}

		// @phan-suppress-next-next-line PhanTypeMismatchArgumentNullable
		$res = $emailUser->sendEmailUnsafe(
			$target,
			$data['Subject'],
			$data['Text'],
			$data['CCMe'],
			$this->getLanguage()->getCode()
		);
		if ( $res->hasMessage( 'hookaborted' ) ) {
			// BC: The method could previously return false if the EmailUser hook set the error to false. Preserve
			// that behaviour until we replace the hook.
			$res = false;
		} else {
			$res = Status::wrap( $res );
		}
		return $res;
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		$search = $this->userNameUtils->getCanonical( $search );
		if ( !$search ) {
			// No prefix suggestion for invalid user
			return [];
		}
		// Autocomplete subpage as user list - public to allow caching
		return $this->userNamePrefixSearch
			->search( UserNamePrefixSearch::AUDIENCE_PUBLIC, $search, $limit, $offset );
	}

	/**
	 * @return bool
	 */
	public function isListed() {
		return $this->getConfig()->get( MainConfigNames::EnableUserEmail );
	}

	protected function getGroupName() {
		return 'users';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialEmailUser::class, 'SpecialEmailUser' );
