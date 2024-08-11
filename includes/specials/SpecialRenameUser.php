<?php

namespace MediaWiki\Specials;

use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\MovePageFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\RenameUser\RenameuserSQL;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserNamePrefixSearch;
use MediaWiki\User\UserNameUtils;
use UserBlockedError;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Rename a user account.
 *
 * @ingroup SpecialPage
 */
class SpecialRenameUser extends SpecialPage {
	private IConnectionProvider $dbConns;
	private Language $contentLanguage;
	private MovePageFactory $movePageFactory;
	private PermissionManager $permissionManager;
	private TitleFactory $titleFactory;
	private UserFactory $userFactory;
	private UserNamePrefixSearch $userNamePrefixSearch;
	private UserNameUtils $userNameUtils;

	/**
	 * @param IConnectionProvider $dbConns
	 * @param Language $contentLanguage
	 * @param MovePageFactory $movePageFactory
	 * @param PermissionManager $permissionManager
	 * @param TitleFactory $titleFactory
	 * @param UserFactory $userFactory
	 * @param UserNamePrefixSearch $userNamePrefixSearch
	 * @param UserNameUtils $userNameUtils
	 */
	public function __construct(
		IConnectionProvider $dbConns,
		Language $contentLanguage,
		MovePageFactory $movePageFactory,
		PermissionManager $permissionManager,
		TitleFactory $titleFactory,
		UserFactory $userFactory,
		UserNamePrefixSearch $userNamePrefixSearch,
		UserNameUtils $userNameUtils
	) {
		parent::__construct( 'Renameuser', 'renameuser' );

		$this->dbConns = $dbConns;
		$this->contentLanguage = $contentLanguage;
		$this->movePageFactory = $movePageFactory;
		$this->permissionManager = $permissionManager;
		$this->titleFactory = $titleFactory;
		$this->userFactory = $userFactory;
		$this->userNamePrefixSearch = $userNamePrefixSearch;
		$this->userNameUtils = $userNameUtils;
	}

	public function doesWrites() {
		return true;
	}

	/**
	 * Show the special page
	 *
	 * @param null|string $par Parameter passed to the page
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->addHelpLink( 'Help:Renameuser' );

		$this->checkPermissions();
		$this->checkReadOnly();

		$performer = $this->getUser();

		$block = $performer->getBlock();
		if ( $block ) {
			throw new UserBlockedError( $block );
		}

		$out = $this->getOutput();
		$out->addWikiMsg( 'renameuser-summary' );

		$this->useTransactionalTimeLimit();

		$request = $this->getRequest();

		// This works as "/" is not valid in usernames
		$userNames = $par !== null ? explode( '/', $par, 2 ) : [];

		// Get the old name, applying minimal validation or canonicalization
		$oldName = $request->getText( 'oldusername', $userNames[0] ?? '' );
		$oldName = trim( str_replace( '_', ' ', $oldName ) );
		$oldTitle = $this->titleFactory->makeTitle( NS_USER, $oldName );

		// Get the new name and canonicalize it
		$origNewName = $request->getText( 'newusername', $userNames[1] ?? '' );
		$origNewName = trim( str_replace( '_', ' ', $origNewName ) );
		// Force uppercase of new username, otherwise wikis
		// with wgCapitalLinks=false can create lc usernames
		$newTitle = $this->titleFactory->makeTitleSafe( NS_USER, $this->contentLanguage->ucfirst( $origNewName ) );
		$newName = $newTitle ? $newTitle->getText() : '';

		$reason = $request->getText( 'reason' );
		$moveChecked = $request->getBool( 'movepages', !$request->wasPosted() );
		$suppressChecked = $request->getCheck( 'suppressredirect' );

		if ( $oldName !== '' && $newName !== '' && !$request->getCheck( 'confirmaction' ) ) {
			$warnings = $this->getWarnings( $oldName, $newName );
		} else {
			$warnings = [];
		}

		$this->showForm( $oldName, $newName, $warnings, $reason, $moveChecked, $suppressChecked );

		if ( $request->getText( 'wpEditToken' ) === '' ) {
			# They probably haven't even submitted the form, so don't go further.
			return;
		}
		if ( $warnings ) {
			# Let user read warnings
			return;
		}
		if (
			!$request->wasPosted() ||
			!$performer->matchEditToken( $request->getVal( 'wpEditToken' ) )
		) {
			$out->addHTML( Html::errorBox( $out->msg( 'renameuser-error-request' )->parse() ) );

			return;
		}
		if ( !$newTitle ) {
			$out->addHTML( Html::errorBox(
				$out->msg( 'renameusererrorinvalid' )->params( $request->getText( 'newusername' ) )->parse()
			) );

			return;
		}
		if ( $oldName === $newName ) {
			$out->addHTML( Html::errorBox( $out->msg( 'renameuser-error-same-user' )->parse() ) );

			return;
		}

		// Do not act on temp users
		if ( $this->userNameUtils->isTemp( $oldName ) ) {
			$out->addHTML( Html::errorBox(
				$out->msg( 'renameuser-error-temp-user' )->plaintextParams( $oldName )->parse()
			) );
			return;
		}
		if ( $this->userNameUtils->isTemp( $newName ) ||
			$this->userNameUtils->isTempReserved( $newName )
		) {
			$out->addHTML( Html::errorBox(
				$out->msg( 'renameuser-error-temp-user-reserved' )->plaintextParams( $newName )->parse()
			) );
			return;
		}

		// Suppress username validation of old username
		$oldUser = $this->userFactory->newFromName( $oldName, $this->userFactory::RIGOR_NONE );
		$newUser = $this->userFactory->newFromName( $newName, $this->userFactory::RIGOR_CREATABLE );

		// It won't be an object if for instance "|" is supplied as a value
		if ( !$oldUser ) {
			$out->addHTML( Html::errorBox(
				$out->msg( 'renameusererrorinvalid' )->params( $oldTitle->getText() )->parse()
			) );

			return;
		}
		if ( !$newUser ) {
			$out->addHTML( Html::errorBox(
				$out->msg( 'renameusererrorinvalid' )->params( $newTitle->getText() )->parse()
			) );

			return;
		}

		// Check for the existence of lowercase old username in database.
		// Until r19631 it was possible to rename a user to a name with first character as lowercase
		if ( $oldName !== $this->contentLanguage->ucfirst( $oldName ) ) {
			// old username was entered as lowercase -> check for existence in table 'user'
			$dbr = $this->dbConns->getReplicaDatabase();
			$uid = $dbr->newSelectQueryBuilder()
				->select( 'user_id' )
				->from( 'user' )
				->where( [ 'user_name' => $oldName ] )
				->caller( __METHOD__ )
				->fetchField();
			if ( $uid === false ) {
				if ( !$this->getConfig()->get( MainConfigNames::CapitalLinks ) ) {
					$uid = 0; // We are on a lowercase wiki but lowercase username does not exist
				} else {
					// We are on a standard uppercase wiki, use normal
					$uid = $oldUser->idForName();
					$oldTitle = $this->titleFactory->makeTitleSafe( NS_USER, $oldUser->getName() );
					if ( !$oldTitle ) {
						$out->addHTML( Html::errorBox(
							$out->msg( 'renameusererrorinvalid' )->params( $oldName )->parse()
						) );
						return;
					}
					$oldName = $oldTitle->getText();
				}
			}
		} else {
			// old username was entered as uppercase -> standard procedure
			$uid = $oldUser->idForName();
		}

		if ( $uid === 0 ) {
			$out->addHTML( Html::errorBox(
				$out->msg( 'renameusererrordoesnotexist' )->params( $oldName )->parse()
			) );

			return;
		}

		if ( $newUser->idForName() !== 0 ) {
			$out->addHTML( Html::errorBox(
				$out->msg( 'renameusererrorexists' )->params( $newName )->parse()
			) );

			return;
		}

		if ( $oldUser->equals( $performer ) ) {
			$out->addHTML( Html::errorBox(
				$out->msg( 'renameuser-error-self-rename' )->parse()
			) );

			return;
		}

		// Give other affected extensions a chance to validate or abort
		if ( !$this->getHookRunner()->onRenameUserAbort( $uid, $oldName, $newName ) ) {
			return;
		}

		// Do the heavy lifting...
		$rename = new RenameuserSQL(
			$oldTitle->getText(),
			$newTitle->getText(),
			$uid,
			$this->getUser(),
			[ 'reason' => $reason ]
		);
		if ( !$rename->rename() ) {
			return;
		}

		// If this user is renaming themself, make sure that MovePage::move()
		// doesn't make a bunch of null move edits under the old name!
		if ( $performer->getId() === $uid ) {
			$performer->setName( $newTitle->getText() );
		}

		// Move any user pages
		if ( $moveChecked && $this->permissionManager->userHasRight( $performer, 'move' ) ) {
			$suppressRedirect = $suppressChecked
				&& $this->permissionManager->userHasRight( $performer, 'suppressredirect' );
			$this->movePages( $oldTitle, $newTitle, $suppressRedirect );
		}

		// Output success message stuff :)
		$out->addHTML(
			Html::successBox(
				$out->msg( 'renameusersuccess' )
					->params( $oldTitle->getText(), $newTitle->getText() )
					->parse()
			)
		);
	}

	private function getWarnings( $oldName, $newName ) {
		$warnings = [];
		$oldUser = $this->userFactory->newFromName( $oldName, $this->userFactory::RIGOR_NONE );
		if ( $oldUser && !$oldUser->isTemp() && $oldUser->getBlock() ) {
			$warnings[] = [
				'renameuser-warning-currentblock',
				SpecialPage::getTitleFor( 'Log', 'block' )->getFullURL( [ 'page' => $oldName ] )
			];
		}
		$this->getHookRunner()->onRenameUserWarning( $oldName, $newName, $warnings );
		return $warnings;
	}

	private function showForm( $oldName, $newName, $warnings, $reason, $moveChecked, $suppressChecked ) {
		$performer = $this->getUser();

		$formDescriptor = [
			'oldusername' => [
				'type' => 'user',
				'name' => 'oldusername',
				'label-message' => 'renameuserold',
				'default' => $oldName,
				'required' => true,
			],
			'newusername' => [
				'type' => 'text',
				'name' => 'newusername',
				'label-message' => 'renameusernew',
				'default' => $newName,
				'required' => true,
			],
			'reason' => [
				'type' => 'text',
				'name' => 'reason',
				'label-message' => 'renameuserreason',
				'maxlength' => CommentStore::COMMENT_CHARACTER_LIMIT,
				'maxlength-unit' => 'codepoints',
				'infusable' => true,
				'default' => $reason,
				'required' => true,
			],
		];

		if ( $this->permissionManager->userHasRight( $performer, 'move' ) ) {
			$formDescriptor['confirm'] = [
				'type' => 'check',
				'id' => 'movepages',
				'name' => 'movepages',
				'label-message' => 'renameusermove',
				'default' => $moveChecked,
			];
		}
		if ( $this->permissionManager->userHasRight( $performer, 'suppressredirect' ) ) {
			$formDescriptor['suppressredirect'] = [
				'type' => 'check',
				'id' => 'suppressredirect',
				'name' => 'suppressredirect',
				'label-message' => 'renameusersuppress',
				'default' => $suppressChecked,
			];
		}

		if ( $warnings ) {
			$warningsHtml = [];
			foreach ( $warnings as $warning ) {
				$warningsHtml[] = is_array( $warning ) ?
					$this->msg( $warning[0] )->params( array_slice( $warning, 1 ) )->parse() :
					$this->msg( $warning )->parse();
			}

			$formDescriptor['renameuserwarnings'] = [
				'type' => 'info',
				'label-message' => 'renameuserwarnings',
				'raw' => true,
				'default' => Html::warningBox( '<ul><li>' .
					implode( '</li><li>', $warningsHtml ) . '</li></ul>' ),
			];

			$formDescriptor['confirmaction'] = [
				'type' => 'check',
				'name' => 'confirmaction',
				'id' => 'confirmaction',
				'label-message' => 'renameuserconfirm',
			];
		}

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setMethod( 'post' )
			->setId( 'renameuser' )
			->setSubmitTextMsg( 'renameusersubmit' );

		$this->getOutput()->addHTML( $htmlForm->prepareForm()->getHTML( false ) );
	}

	/**
	 * Move the specified user page, its associated talk page, and any subpages
	 *
	 * @param Title $oldTitle
	 * @param Title $newTitle
	 * @param bool $suppressRedirect
	 * @return void
	 */
	private function movePages( Title $oldTitle, Title $newTitle, $suppressRedirect ) {
		$output = $this->movePageAndSubpages( $oldTitle, $newTitle, $suppressRedirect );
		$oldTalkTitle = $oldTitle->getTalkPageIfDefined();
		$newTalkTitle = $newTitle->getTalkPageIfDefined();
		if ( $oldTalkTitle && $newTalkTitle ) { // always true
			$output .= $this->movePageAndSubpages( $oldTalkTitle, $newTalkTitle, $suppressRedirect );
		}

		if ( $output !== '' ) {
			$this->getOutput()->addHTML( Html::rawElement( 'ul', [], $output ) );
		}
	}

	/**
	 * Move a specified page and its subpages
	 *
	 * @param Title $oldTitle
	 * @param Title $newTitle
	 * @param bool $suppressRedirect
	 * @return string
	 */
	private function movePageAndSubpages( Title $oldTitle, Title $newTitle, $suppressRedirect ) {
		$performer = $this->getUser();
		$logReason = $this->msg(
			'renameuser-move-log', $oldTitle->getText(), $newTitle->getText()
		)->inContentLanguage()->text();
		$movePage = $this->movePageFactory->newMovePage( $oldTitle, $newTitle );

		$output = '';
		if ( $oldTitle->exists() ) {
			$status = $movePage->moveIfAllowed( $performer, $logReason, !$suppressRedirect );
			$output .= $this->getMoveStatusHtml( $status, $oldTitle, $newTitle );
		}

		$oldLength = strlen( $oldTitle->getText() );
		$batchStatus = $movePage->moveSubpagesIfAllowed( $performer, $logReason, !$suppressRedirect );
		foreach ( $batchStatus->getValue() as $titleText => $status ) {
			$oldSubpageTitle = Title::newFromText( $titleText );
			$newSubpageTitle = $newTitle->getSubpage(
				substr( $oldSubpageTitle->getText(), $oldLength + 1 ) );
			$output .= $this->getMoveStatusHtml( $status, $oldSubpageTitle, $newSubpageTitle );
		}
		return $output;
	}

	private function getMoveStatusHtml( Status $status, Title $oldTitle, Title $newTitle ) {
		$linkRenderer = $this->getLinkRenderer();
		if ( $status->hasMessage( 'articleexists' ) || $status->hasMessage( 'redirectexists' ) ) {
			$link = $linkRenderer->makeKnownLink( $newTitle );
			return Html::rawElement(
				'li',
				[ 'class' => 'mw-renameuser-pe' ],
				$this->msg( 'renameuser-page-exists' )->rawParams( $link )->escaped()
			);
		} else {
			if ( $status->isOK() ) {
				// oldPage is not known in case of redirect suppression
				$oldLink = $linkRenderer->makeLink( $oldTitle, null, [], [ 'redirect' => 'no' ] );

				// newPage is always known because the move was successful
				$newLink = $linkRenderer->makeKnownLink( $newTitle );

				return Html::rawElement(
					'li',
					[ 'class' => 'mw-renameuser-pm' ],
					$this->msg( 'renameuser-page-moved' )->rawParams( $oldLink, $newLink )->escaped()
				);
			} else {
				$oldLink = $linkRenderer->makeKnownLink( $oldTitle );
				$newLink = $linkRenderer->makeLink( $newTitle );
				return Html::rawElement(
					'li', [ 'class' => 'mw-renameuser-pu' ],
					$this->msg( 'renameuser-page-unmoved' )->rawParams( $oldLink, $newLink )->escaped()
				);
			}
		}
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
		$user = $this->userFactory->newFromName( $search );
		if ( !$user ) {
			// No prefix suggestion for invalid user
			return [];
		}
		// Autocomplete subpage as user list - public to allow caching
		return $this->userNamePrefixSearch->search( 'public', $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'users';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialRenameUser::class, 'SpecialRenameuser' );
