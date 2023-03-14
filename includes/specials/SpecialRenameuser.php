<?php

use MediaWiki\Html\Html;
use MediaWiki\Page\MovePageFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\RenameUser\RenameuserSQL;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserNamePrefixSearch;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Special page that allows authorised users to rename
 * user accounts
 */
class SpecialRenameuser extends SpecialPage {
	/** @var IConnectionProvider */
	private $dbConns;

	/** @var Language */
	private $contentLanguage;

	/** @var MovePageFactory */
	private $movePageFactory;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var TitleFactory */
	private $titleFactory;

	/** @var UserFactory */
	private $userFactory;

	/** @var UserNamePrefixSearch */
	private $userNamePrefixSearch;

	/**
	 * @param IConnectionProvider $dbConns
	 * @param Language $contentLanguage
	 * @param MovePageFactory $movePageFactory
	 * @param PermissionManager $permissionManager
	 * @param TitleFactory $titleFactory
	 * @param UserFactory $userFactory
	 * @param UserNamePrefixSearch $userNamePrefixSearch
	 */
	public function __construct(
		IConnectionProvider $dbConns,
		Language $contentLanguage,
		MovePageFactory $movePageFactory,
		PermissionManager $permissionManager,
		TitleFactory $titleFactory,
		UserFactory $userFactory,
		UserNamePrefixSearch $userNamePrefixSearch
	) {
		parent::__construct( 'Renameuser', 'renameuser' );

		$this->dbConns = $dbConns;
		$this->contentLanguage = $contentLanguage;
		$this->movePageFactory = $movePageFactory;
		$this->permissionManager = $permissionManager;
		$this->titleFactory = $titleFactory;
		$this->userFactory = $userFactory;
		$this->userNamePrefixSearch = $userNamePrefixSearch;
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

		if ( $request->getText( 'token' ) === '' ) {
			# They probably haven't even submitted the form, so don't go further.
			return;
		} elseif ( $warnings ) {
			# Let user read warnings
			return;
		} elseif ( !$request->wasPosted() || !$performer->matchEditToken( $request->getVal( 'token' ) ) ) {
			$out->addHTML( Html::errorBox( $out->msg( 'renameuser-error-request' )->parse() ) );

			return;
		} elseif ( !$newTitle ) {
			$out->addHTML( Html::errorBox(
				$out->msg( 'renameusererrorinvalid' )->params( $request->getText( 'newusername' ) )->parse()
			) );

			return;
		} elseif ( $oldName === $newName ) {
			$out->addHTML( Html::errorBox( $out->msg( 'renameuser-error-same-user' )->parse() ) );

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
				if ( !$this->getConfig()->get( 'CapitalLinks' ) ) {
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

		// If this user is renaming his/herself, make sure that MovePage::move()
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
		if ( $oldUser && $oldUser->getBlock() ) {
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
		$token = $performer->getEditToken();
		$out = $this->getOutput();

		$out->addHTML(
			Xml::openElement( 'form', [
				'method' => 'post',
				'action' => $this->getPageTitle()->getLocalURL(),
				'id' => 'renameuser'
			] ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, $this->msg( 'renameuser' )->text() ) .
			Xml::openElement( 'table', [ 'id' => 'mw-renameuser-table' ] ) .
			"<tr>
				<td class='mw-label'>" .
			Xml::label( $this->msg( 'renameuserold' )->text(), 'oldusername' ) .
			"</td>
				<td class='mw-input'>" .
			Xml::input( 'oldusername', 20, $oldName, [ 'type' => 'text', 'tabindex' => '1' ] ) . ' ' .
			"</td>
			</tr>
			<tr>
				<td class='mw-label'>" .
			Xml::label( $this->msg( 'renameusernew' )->text(), 'newusername' ) .
			"</td>
				<td class='mw-input'>" .
			Xml::input( 'newusername', 20, $newName, [ 'type' => 'text', 'tabindex' => '2' ] ) .
			"</td>
			</tr>
			<tr>
				<td class='mw-label'>" .
			Xml::label( $this->msg( 'renameuserreason' )->text(), 'reason' ) .
			"</td>
				<td class='mw-input'>" .
			Xml::input(
				'reason',
				40,
				$reason,
				[ 'type' => 'text', 'tabindex' => '3', 'maxlength' => 255 ]
			) .
			'</td>
			</tr>'
		);
		if ( $this->permissionManager->userHasRight( $performer, 'move' ) ) {
			$out->addHTML( "
				<tr>
					<td>&#160;
					</td>
					<td class='mw-input'>" .
				Xml::checkLabel( $this->msg( 'renameusermove' )->text(), 'movepages', 'movepages',
					$moveChecked, [ 'tabindex' => '4' ] ) .
				'</td>
				</tr>'
			);

			if ( $this->permissionManager->userHasRight( $performer, 'suppressredirect' ) ) {
				$out->addHTML( "
					<tr>
						<td>&#160;
						</td>
						<td class='mw-input'>" .
					Xml::checkLabel(
						$this->msg( 'renameusersuppress' )->text(),
						'suppressredirect',
						'suppressredirect',
						$suppressChecked,
						[ 'tabindex' => '5' ]
					) .
					'</td>
					</tr>'
				);
			}
		}
		if ( $warnings ) {
			$warningsHtml = [];
			foreach ( $warnings as $warning ) {
				$warningsHtml[] = is_array( $warning ) ?
					$this->msg( $warning[0] )->params( array_slice( $warning, 1 ) )->parse() :
					$this->msg( $warning )->parse();
			}

			$out->addHTML( "
				<tr>
					<td class='mw-label'>" . $this->msg( 'renameuserwarnings' )->escaped() . "
					</td>
					<td class='mw-input'>" .
				'<ul class="error"><li>' .
				implode( '</li><li>', $warningsHtml ) . '</li></ul>' .
				'</td>
				</tr>'
			);
			$out->addHTML( "
				<tr>
					<td>&#160;
					</td>
					<td class='mw-input'>" .
				Xml::checkLabel(
					$this->msg( 'renameuserconfirm' )->text(),
					'confirmaction',
					'confirmaction',
					false,
					[ 'tabindex' => '6' ]
				) .
				'</td>
				</tr>'
			);
		}
		$out->addHTML( "
			<tr>
				<td>&#160;
				</td>
				<td class='mw-submit'>" .
			Xml::submitButton(
				$this->msg( 'renameusersubmit' )->text(),
				[
					'name' => 'submit',
					'tabindex' => '7',
					'id' => 'submit'
				]
			) .
			' ' .
			'</td>
			</tr>' .
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'fieldset' ) .
			Html::hidden( 'token', $token ) .
			Xml::closeElement( 'form' ) . "\n"
		);
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
