<?php
/**
 * Implements Special:DeletedContributions
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
 * Implements Special:DeletedContributions to display archived revisions
 * @ingroup SpecialPage
 */
class DeletedContributionsPage extends SpecialPage {
	/** @var FormOptions */
	protected $mOpts;

	function __construct() {
		parent::__construct( 'DeletedContributions', 'deletedhistory' );
	}

	/**
	 * Special page "deleted user contributions".
	 * Shows a list of the deleted contributions of a user.
	 *
	 * @param string $par (optional) user name of the user for which to show the contributions
	 */
	function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->checkPermissions();

		$user = $this->getUser();

		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'deletedcontributions-title' ) );

		$opts = new FormOptions();

		$opts->add( 'target', '' );
		$opts->add( 'namespace', '' );
		$opts->add( 'limit', 20 );

		$opts->fetchValuesFromRequest( $this->getRequest() );
		$opts->validateIntBounds( 'limit', 0, $this->getConfig()->get( 'QueryPageDefaultLimit' ) );

		if ( $par !== null ) {
			$opts->setValue( 'target', $par );
		}

		$ns = $opts->getValue( 'namespace' );
		if ( $ns !== null && $ns !== '' ) {
			$opts->setValue( 'namespace', intval( $ns ) );
		}

		$this->mOpts = $opts;

		$target = $opts->getValue( 'target' );
		if ( !strlen( $target ) ) {
			$this->getForm();

			return;
		}

		$userObj = User::newFromName( $target, false );
		if ( !$userObj ) {
			$this->getForm();

			return;
		}
		$this->getSkin()->setRelevantUser( $userObj );

		$target = $userObj->getName();
		$out->addSubtitle( $this->getSubTitle( $userObj ) );

		$this->getForm();

		$pager = new DeletedContribsPager( $this->getContext(), $target, $opts->getValue( 'namespace' ) );
		if ( !$pager->getNumRows() ) {
			$out->addWikiMsg( 'nocontribs' );

			return;
		}

		# Show a message about slave lag, if applicable
		$lag = wfGetLB()->safeGetLag( $pager->getDatabase() );
		if ( $lag > 0 ) {
			$out->showLagWarning( $lag );
		}

		$out->addHTML(
			'<p>' . $pager->getNavigationBar() . '</p>' .
				$pager->getBody() .
				'<p>' . $pager->getNavigationBar() . '</p>' );

		# If there were contributions, and it was a valid user or IP, show
		# the appropriate "footer" message - WHOIS tools, etc.
		if ( $target != 'newbies' ) {
			$message = IP::isIPAddress( $target ) ?
				'sp-contributions-footer-anon' :
				'sp-contributions-footer';

			if ( !$this->msg( $message )->isDisabled() ) {
				$out->wrapWikiMsg(
					"<div class='mw-contributions-footer'>\n$1\n</div>",
					[ $message, $target ]
				);
			}
		}
	}

	/**
	 * Generates the subheading with links
	 * @param User $userObj User object for the target
	 * @return string Appropriately-escaped HTML to be output literally
	 * @todo FIXME: Almost the same as contributionsSub in SpecialContributions.php. Could be combined.
	 */
	function getSubTitle( $userObj ) {
		if ( $userObj->isAnon() ) {
			$user = htmlspecialchars( $userObj->getName() );
		} else {
			$user = Linker::link( $userObj->getUserPage(), htmlspecialchars( $userObj->getName() ) );
		}
		$links = '';
		$nt = $userObj->getUserPage();
		$id = $userObj->getId();
		$talk = $nt->getTalkPage();
		if ( $talk ) {
			# Talk page link
			$tools[] = Linker::link( $talk, $this->msg( 'sp-contributions-talk' )->escaped() );
			if ( ( $id !== null ) || ( $id === null && IP::isIPAddress( $nt->getText() ) ) ) {
				# Block / Change block / Unblock links
				if ( $this->getUser()->isAllowed( 'block' ) ) {
					if ( $userObj->isBlocked() && $userObj->getBlock()->getType() !== Block::TYPE_AUTO ) {
						$tools[] = Linker::linkKnown( # Change block link
							SpecialPage::getTitleFor( 'Block', $nt->getDBkey() ),
							$this->msg( 'change-blocklink' )->escaped()
						);
						$tools[] = Linker::linkKnown( # Unblock link
							SpecialPage::getTitleFor( 'BlockList' ),
							$this->msg( 'unblocklink' )->escaped(),
							[],
							[
								'action' => 'unblock',
								'ip' => $nt->getDBkey()
							]
						);
					} else {
						# User is not blocked
						$tools[] = Linker::linkKnown( # Block link
							SpecialPage::getTitleFor( 'Block', $nt->getDBkey() ),
							$this->msg( 'blocklink' )->escaped()
						);
					}
				}
				# Block log link
				$tools[] = Linker::linkKnown(
					SpecialPage::getTitleFor( 'Log' ),
					$this->msg( 'sp-contributions-blocklog' )->escaped(),
					[],
					[
						'type' => 'block',
						'page' => $nt->getPrefixedText()
					]
				);
				# Suppression log link (bug 59120)
				if ( $this->getUser()->isAllowed( 'suppressionlog' ) ) {
					$tools[] = Linker::linkKnown(
						SpecialPage::getTitleFor( 'Log', 'suppress' ),
						$this->msg( 'sp-contributions-suppresslog' )->escaped(),
						[],
						[ 'offender' => $userObj->getName() ]
					);
				}
			}

			# Uploads
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Listfiles', $userObj->getName() ),
				$this->msg( 'sp-contributions-uploads' )->escaped()
			);

			# Other logs link
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Log' ),
				$this->msg( 'sp-contributions-logs' )->escaped(),
				[],
				[ 'user' => $nt->getText() ]
			);
			# Link to contributions
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Contributions', $nt->getDBkey() ),
				$this->msg( 'sp-deletedcontributions-contribs' )->escaped()
			);

			# Add a link to change user rights for privileged users
			$userrightsPage = new UserrightsPage();
			$userrightsPage->setContext( $this->getContext() );
			if ( $userrightsPage->userCanChangeRights( $userObj ) ) {
				$tools[] = Linker::linkKnown(
					SpecialPage::getTitleFor( 'Userrights', $nt->getDBkey() ),
					$this->msg( 'sp-contributions-userrights' )->escaped()
				);
			}

			Hooks::run( 'ContributionsToolLinks', [ $id, $nt, &$tools ] );

			$links = $this->getLanguage()->pipeList( $tools );

			// Show a note if the user is blocked and display the last block log entry.
			$block = Block::newFromTarget( $userObj, $userObj );
			if ( !is_null( $block ) && $block->getType() != Block::TYPE_AUTO ) {
				if ( $block->getType() == Block::TYPE_RANGE ) {
					$nt = MWNamespace::getCanonicalName( NS_USER ) . ':' . $block->getTarget();
				}

				// LogEventsList::showLogExtract() wants the first parameter by ref
				$out = $this->getOutput();
				LogEventsList::showLogExtract(
					$out,
					'block',
					$nt,
					'',
					[
						'lim' => 1,
						'showIfEmpty' => false,
						'msgKey' => [
							'sp-contributions-blocked-notice',
							$userObj->getName() # Support GENDER in 'sp-contributions-blocked-notice'
						],
						'offset' => '' # don't use $this->getRequest() parameter offset
					]
				);
			}
		}

		return $this->msg( 'contribsub2' )->rawParams( $user, $links )->params( $userObj->getName() );
	}

	/**
	 * Generates the namespace selector form with hidden attributes.
	 */
	function getForm() {
		$opts = $this->mOpts;

		$formDescriptor = [
			'target' => [
				'type' => 'user',
				'name' => 'target',
				'label-message' => 'sp-contributions-username',
				'default' => $opts->getValue( 'target' ),
				'ipallowed' => true,
			],

			'namespace' => [
				'type' => 'namespaceselect',
				'name' => 'namespace',
				'label-message' => 'namespace',
				'all' => '',
			],
		];

		$form = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() )
			->setWrapperLegendMsg( 'sp-contributions-search' )
			->setSubmitTextMsg( 'sp-contributions-submit' )
			// prevent setting subpage and 'target' parameter at the same time
			->setAction( $this->getPageTitle()->getLocalURL() )
			->setMethod( 'get' )
			->prepareForm()
			->displayForm( false );
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
		$user = User::newFromName( $search );
		if ( !$user ) {
			// No prefix suggestion for invalid user
			return [];
		}
		// Autocomplete subpage as user list - public to allow caching
		return UserNamePrefixSearch::search( 'public', $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'users';
	}
}
