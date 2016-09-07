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
	function __construct() {
		parent::__construct( 'DeletedContributions', 'deletedhistory',
			/*listed*/true, /*function*/false, /*file*/false );
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

		$user = $this->getUser();

		if ( !$this->userCanExecute( $user ) ) {
			$this->displayRestrictionError();

			return;
		}

		$request = $this->getRequest();
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'deletedcontributions-title' ) );

		$options = [];

		if ( $par !== null ) {
			$target = $par;
		} else {
			$target = $request->getVal( 'target' );
		}

		if ( !strlen( $target ) ) {
			$out->addHTML( $this->getForm( '' ) );

			return;
		}

		$options['limit'] = $request->getInt( 'limit',
			$this->getConfig()->get( 'QueryPageDefaultLimit' ) );
		$options['target'] = $target;

		$userObj = User::newFromName( $target, false );
		if ( !$userObj ) {
			$out->addHTML( $this->getForm( '' ) );

			return;
		}
		$this->getSkin()->setRelevantUser( $userObj );

		$target = $userObj->getName();
		$out->addSubtitle( $this->getSubTitle( $userObj ) );

		$ns = $request->getVal( 'namespace', null );
		if ( $ns !== null && $ns !== '' ) {
			$options['namespace'] = intval( $ns );
		} else {
			$options['namespace'] = '';
		}

		$out->addHTML( $this->getForm( $options ) );

		$pager = new DeletedContribsPager( $this->getContext(), $target, $options['namespace'] );
		if ( !$pager->getNumRows() ) {
			$out->addWikiMsg( 'nocontribs' );

			return;
		}

		# Show a message about replica DB lag, if applicable
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
	 */
	function getSubTitle( $userObj ) {
		$linkRenderer = $this->getLinkRenderer();
		if ( $userObj->isAnon() ) {
			$user = htmlspecialchars( $userObj->getName() );
		} else {
			$user = $linkRenderer->makeLink( $userObj->getUserPage(), $userObj->getName() );
		}
		$links = '';
		$nt = $userObj->getUserPage();
		$talk = $nt->getTalkPage();
		if ( $talk ) {
			$tools = SpecialContributions::getUserLinks( $this, $userObj );

			# Link to contributions
			$insert['contribs'] = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'Contributions', $nt->getDBkey() ),
				$this->msg( 'sp-deletedcontributions-contribs' )->text()
			);

			// Swap out the deletedcontribs link for our contribs one
			$tools = wfArrayInsertAfter( $tools, $insert, 'deletedcontribs' );
			unset( $tools['deletedcontribs'] );

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
	 * @param array $options The options to be included.
	 * @return string
	 */
	function getForm( $options ) {
		$options['title'] = $this->getPageTitle()->getPrefixedText();
		if ( !isset( $options['target'] ) ) {
			$options['target'] = '';
		} else {
			$options['target'] = str_replace( '_', ' ', $options['target'] );
		}

		if ( !isset( $options['namespace'] ) ) {
			$options['namespace'] = '';
		}

		if ( !isset( $options['contribs'] ) ) {
			$options['contribs'] = 'user';
		}

		if ( $options['contribs'] == 'newbie' ) {
			$options['target'] = '';
		}

		$f = Xml::openElement( 'form', [ 'method' => 'get', 'action' => wfScript() ] );

		foreach ( $options as $name => $value ) {
			if ( in_array( $name, [ 'namespace', 'target', 'contribs' ] ) ) {
				continue;
			}
			$f .= "\t" . Html::hidden( $name, $value ) . "\n";
		}

		$this->getOutput()->addModules( 'mediawiki.userSuggest' );

		$f .= Xml::openElement( 'fieldset' );
		$f .= Xml::element( 'legend', [], $this->msg( 'sp-contributions-search' )->text() );
		$f .= Xml::tags(
			'label',
			[ 'for' => 'target' ],
			$this->msg( 'sp-contributions-username' )->parse()
		) . ' ';
		$f .= Html::input(
			'target',
			$options['target'],
			'text',
			[
				'size' => '20',
				'required' => '',
				'class' => [
					'mw-autocomplete-user', // used by mediawiki.userSuggest
				],
			] + ( $options['target'] ? [] : [ 'autofocus' ] )
		) . ' ';
		$f .= Html::namespaceSelector(
			[
				'selected' => $options['namespace'],
				'all' => '',
				'label' => $this->msg( 'namespace' )->text()
			],
			[
				'name' => 'namespace',
				'id' => 'namespace',
				'class' => 'namespaceselector',
			]
		) . ' ';
		$f .= Xml::submitButton( $this->msg( 'sp-contributions-submit' )->text() );
		$f .= Xml::closeElement( 'fieldset' );
		$f .= Xml::closeElement( 'form' );

		return $f;
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
