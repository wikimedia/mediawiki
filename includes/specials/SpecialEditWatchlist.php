<?php
/**
 * @defgroup Watchlist Users watchlist handling
 */

/**
 * Implements Special:EditWatchlist
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
 * @ingroup Watchlist
 */

/**
 * Provides the UI through which users can perform editing
 * operations on their watchlist
 *
 * @ingroup SpecialPage
 * @ingroup Watchlist
 * @author Rob Church <robchur@gmail.com>
 */
class SpecialEditWatchlist extends UnlistedSpecialPage {
	/**
	 * Editing modes. EDIT_CLEAR is no longer used; the "Clear" link scared people
	 * too much. Now it's passed on to the raw editor, from which it's very easy to clear.
	 */
	const EDIT_CLEAR = 1;
	const EDIT_RAW = 2;
	const EDIT_NORMAL = 3;

	protected $successMessage;

	protected $toc;

	private $badItems = array();
	private $wg_obj;
	private $groupName = '';

	public function __construct() {
		parent::__construct( 'EditWatchlist', 'editmywatchlist' );
	}

	/**
	 * Main execution point
	 *
<<<<<<< HEAD
	 * @param int $mode
=======
	 * @param $par string: This could be the mode OR a watchlist query by user/group
>>>>>>> 4e55649... Watchlist grouping
	 */
	public function execute( $par ) {
		$this->wg_obj = WatchlistGroup::newFromUser( $this->getUser() );
		// Set the mode
		if( $par == 'clear' || $par == 'raw' || $par == 'edit'
			|| $par == '0' || $par == '1' || $par == '2' ) {
			$mode = $par;
		} else {
			$mode = '';
			if( $this->wg_obj->isGroup( $this->wg_obj->getGroupFromName( $par ) ) ) {
				$this->groupName = $par;
			}
		}

		$this->setHeaders();

		# Anons don't get a watchlist
		$this->requireLogin( 'watchlistanontext' );

		$out = $this->getOutput();

		$this->checkPermissions();
		$this->checkReadOnly();

		$this->outputHeader();
		$this->outputSubtitle();

		# B/C: $mode used to be waaay down the parameter list, and the first parameter
		# was $wgUser
		if ( $mode instanceof User ) {
			$args = func_get_args();
			if ( count( $args ) >= 4 ) {
				$mode = $args[3];
			}
		}
		$mode = self::getMode( $this->getRequest(), $mode );

		switch ( $mode ) {
			case self::EDIT_RAW:
				$out->setPageTitle( $this->msg( 'watchlistedit-raw-title' ) );
				$form = $this->getRawForm();
				if ( $form->show() ) {
					$out->addHTML( $this->successMessage );
					$out->addReturnTo( SpecialPage::getTitleFor( 'Watchlist' ) );
				}
				break;
<<<<<<< HEAD
			case self::EDIT_CLEAR:
				$out->setPageTitle( $this->msg( 'watchlistedit-clear-title' ) );
				$form = $this->getClearForm();
				if ( $form->show() ) {
					$out->addHTML( $this->successMessage );
					$out->addReturnTo( SpecialPage::getTitleFor( 'Watchlist' ) );
=======

			case self::EDIT_NORMAL:
			default:
				if( $this->getWatchlistInfo() == array() ) {
					$out->setPageTitle( $this->msg( 'watchlistedit-normal-title' ) );
					$out->addWikiMsg( 'watchlistedit-noitems' );
				} else {
					$out->setPageTitle( $this->msg( 'watchlistedit-normal-title' ) );
					$form = $this->getNormalForm();
					if( $form->show() ) {
						$out->addHTML( $this->successMessage );
						$out->addReturnTo( SpecialPage::getTitleFor( 'Watchlist' ) );
					} elseif ( $this->toc !== false ) {
						$out->prependHTML( $this->toc );
					}
>>>>>>> 4e55649... Watchlist grouping
				}
				break;

			case self::EDIT_NORMAL:
			default:
			$this->executeViewEditWatchlist();
				break;
		}
	}

	/**
	 * Renders a subheader on the watchlist page.
	 */
	protected function outputSubtitle() {
		$out = $this->getOutput();
		$out->addSubtitle( $this->msg( 'watchlistfor2', $this->getUser()->getName() )
			->rawParams( SpecialEditWatchlist::buildTools( null ) ) );
	}

	/**
	 * Executes an edit mode for the watchlist view, from which you can manage your watchlist
	 *
	 */
	protected function executeViewEditWatchlist() {
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'watchlistedit-normal-title' ) );
		$form = $this->getNormalForm();
		if ( $form->show() ) {
			$out->addHTML( $this->successMessage );
			$out->addReturnTo( SpecialPage::getTitleFor( 'Watchlist' ) );
		} elseif ( $this->toc !== false ) {
			$out->prependHTML( $this->toc );
			$out->addModules( 'mediawiki.toc' );
		}
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit = 10 ) {
		return self::prefixSearchArray(
			$search,
			$limit,
			// SpecialWatchlist uses SpecialEditWatchlist::getMode, so new types should be added
			// here and there - no 'edit' here, because that the default for this page
			array(
				'clear',
				'raw',
			)
		);
	}

	/**
	 * Extract a list of titles from a blob of text, returning
	 * (prefixed) strings; unwatchable titles are ignored
	 *
	 * @param string $list
	 * @return array
	 */
	private function extractTitles( $list ) {
		$titles = array();
		$list = explode( "\n", trim( $list ) );
		if ( !is_array( $list ) ) {
			return array();
		}
<<<<<<< HEAD

		$titles = array();

		foreach ( $list as $text ) {
=======
		foreach( $list as $text ) {
>>>>>>> 4e55649... Watchlist grouping
			$text = trim( $text );
			if ( strlen( $text ) > 0 ) {
				$title = Title::newFromText( $text );
<<<<<<< HEAD
				if ( $title instanceof Title && $title->isWatchable() ) {
					$titles[] = $title;
				}
			}
		}

		GenderCache::singleton()->doTitlesArray( $titles );

		$list = array();
		/** @var Title $title */
		foreach ( $titles as $title ) {
			$list[] = $title->getPrefixedText();
		}

		return array_unique( $list );
=======
				if( $title instanceof Title && $title->isWatchable() ) {
					$titles[] = $title->getPrefixedText();
				}
			}
		}
		return array_unique( $titles );
>>>>>>> 4e55649... Watchlist grouping
	}

	public function submitRaw( $data ) {
		$wanted = array();
		$current = $this->getWatchlist();
<<<<<<< HEAD

		if ( count( $wanted ) > 0 ) {
			$toWatch = array_diff( $wanted, $current );
			$toUnwatch = array_diff( $current, $wanted );
			$this->watchTitles( $toWatch );
			$this->unwatchTitles( $toUnwatch );
			$this->getUser()->invalidateCache();

			if ( count( $toWatch ) > 0 || count( $toUnwatch ) > 0 ) {
=======
		$allWatched = array();
		$allUnwatched = array();
		foreach( $data as $key => $d ) {
			if( substr( $key, 0, 12 ) == 'Titles_group' ) {
				$group = intval( substr( $key, 12, 13 ) );
				$wanted[$group] = $this->extractTitles( $data['Titles_group' . $group] );
			}
		}
		$groups = array_unique( array_keys( $wanted ) + array_keys( $current ) );
		foreach( $wanted as $gid => $titles ) {
			if( count( $wanted ) > 0 ) {
				$toUnwatch = array_diff( $current[$gid], $wanted[$gid] );
				$this->unwatchTitles( $toUnwatch );
				$this->getUser()->invalidateCache();
				$allUnwatched += $toUnwatch;
			}
		}
		foreach( $wanted as $gid => $titles ) {
			if( count( $wanted ) > 0 ) {
				$toWatch = array_diff( $wanted[$gid], $current[$gid] );
				$this->watchTitles( $toWatch, $gid );
				$this->getUser()->invalidateCache();
				$allWatched += $toWatch;
			}
		}
		if( count( $wanted, 1 ) > count( $wanted ) ) {
			if( count( $allWatched ) > 0 || count( $allUnwatched ) > 0 ) {
>>>>>>> 4e55649... Watchlist grouping
				$this->successMessage = $this->msg( 'watchlistedit-raw-done' )->parse();
			} else {
				return false;
			}

<<<<<<< HEAD
			if ( count( $toWatch ) > 0 ) {
				$this->successMessage .= ' ' . $this->msg( 'watchlistedit-raw-added' )
					->numParams( count( $toWatch ) )->parse();
				$this->showTitles( $toWatch, $this->successMessage );
			}

			if ( count( $toUnwatch ) > 0 ) {
				$this->successMessage .= ' ' . $this->msg( 'watchlistedit-raw-removed' )
					->numParams( count( $toUnwatch ) )->parse();
				$this->showTitles( $toUnwatch, $this->successMessage );
=======
			if( count( $allWatched ) > 0 ) {
				$this->successMessage .= ' ' . $this->msg( 'watchlistedit-raw-added'
					)->numParams( count( $allWatched ) )->parse();
				$this->showTitles( $allWatched, $this->successMessage );
			}

			if( count( $allUnwatched ) > 0 ) {
				$this->successMessage .= ' ' . $this->msg( 'watchlistedit-raw-removed'
					)->numParams( count( $allUnwatched ) )->parse();
				$this->showTitles( $allUnwatched, $this->successMessage );
>>>>>>> 4e55649... Watchlist grouping
			}
		} else {
			$this->clearWatchlist();
			$this->getUser()->invalidateCache();

<<<<<<< HEAD
			if ( count( $current ) > 0 ) {
=======
			if( count( $current, 1 ) > count( $current ) ) {
>>>>>>> 4e55649... Watchlist grouping
				$this->successMessage = $this->msg( 'watchlistedit-raw-done' )->parse();
			} else {
				return false;
			}

			$this->successMessage .= ' ' . $this->msg( 'watchlistedit-raw-removed' )
				->numParams( count( $current ) )->parse();
			$this->showTitles( $current, $this->successMessage );
		}

		return true;
	}

	public function submitClear( $data ) {
		$current = $this->getWatchlist();
		$this->clearWatchlist();
		$this->getUser()->invalidateCache();
		$this->successMessage = $this->msg( 'watchlistedit-clear-done' )->parse();
		$this->successMessage .= ' ' . $this->msg( 'watchlistedit-clear-removed' )
			->numParams( count( $current ) )->parse();
		$this->showTitles( $current, $this->successMessage );

		return true;
	}

	/**
	 * Print out a list of linked titles
	 *
	 * $titles can be an array of strings or Title objects; the former
	 * is preferred, since Titles are very memory-heavy
	 *
	 * @param array $titles Array of strings, or Title objects
	 * @param string $output
	 */
	private function showTitles( $titles, &$output ) {
		$talk = $this->msg( 'talkpagelinktext' )->escaped();
		// Do a batch existence check
		$batch = new LinkBatch();
		if ( count( $titles ) >= 100 ) {
			$output = wfMessage( 'watchlistedit-too-many' )->parse();
			return;
		}
		foreach ( $titles as $title ) {
			if ( !$title instanceof Title ) {
				$title = Title::newFromText( $title );
			}

			if ( $title instanceof Title ) {
				$batch->addObj( $title );
				$batch->addObj( $title->getTalkPage() );
			}
		}

		$batch->execute();

		// Print out the list
		$output .= "<ul>\n";

		foreach ( $titles as $title ) {
			if ( !$title instanceof Title ) {
				$title = Title::newFromText( $title );
			}

			if ( $title instanceof Title ) {
				$output .= "<li>"
					. Linker::link( $title )
					. ' (' . Linker::link( $title->getTalkPage(), $talk )
					. ")</li>\n";
			}
		}

		$output .= "</ul>\n";
	}

	/**
	 * Prepare a list of titles on a user's watchlist (excluding talk pages)
	 * and return an array of (prefixed) strings
	 *
	 * @return array
	 */
	private function getWatchlist() {
		$list = array();
		$dbr = wfGetDB( DB_MASTER );

		$res = $dbr->select(
			'watchlist',
			array(
				'wl_group', 'wl_namespace', 'wl_title'
			), array(
				'wl_user' => $this->getUser()->getId(),
			),
			__METHOD__
		);
<<<<<<< HEAD

		if ( $res->numRows() > 0 ) {
			$titles = array();
=======
		if( $res->numRows() > 0 ) {
>>>>>>> 4e55649... Watchlist grouping
			foreach ( $res as $row ) {
				$title = Title::makeTitleSafe( $row->wl_namespace, $row->wl_title );

				if ( $this->checkTitle( $title, $row->wl_namespace, $row->wl_title )
					&& !$title->isTalkPage()
				) {
					$list[$row->wl_group][] = $title->getPrefixedText();
				}
			}
			$res->free();
<<<<<<< HEAD

			GenderCache::singleton()->doTitlesArray( $titles );

			foreach ( $titles as $title ) {
				$list[] = $title->getPrefixedText();
			}
=======
>>>>>>> 4e55649... Watchlist grouping
		}

		$this->cleanupWatchlist();

		return $list;
	}

	/**
	 * Get a list of titles on a user's watchlist, excluding talk pages,
	 * and return as a two-dimensional array with namespace and title.
	 *
	 * @return array
	 */
	protected function getWatchlistInfo() {
		$titles = array();
		$where = array( 'wl_user' => $this->getUser()->getId() );
		if ( $this->groupName != '' ) {
			$where['wl_group'] = $this->wg_obj->getGroupFromName( $this->groupName );
		}
		$dbr = wfGetDB( DB_MASTER );
		$res = $dbr->select(
			array( 'watchlist' ),
			array( 'wl_namespace', 'wl_group', 'wl_title' ),
			$where,
			__METHOD__,
			array( 'ORDER BY' => array( 'wl_namespace', 'wl_title' ) )
		);

		$lb = new LinkBatch();

		foreach ( $res as $row ) {
			$lb->add( $row->wl_namespace, $row->wl_title );
			if ( !MWNamespace::isTalk( $row->wl_namespace ) ) {
				$titles[$row->wl_namespace][$row->wl_title] = intval( $row->wl_group );
			}
		}

		$lb->execute();

		return $titles;
	}

	/**
	 * Validates watchlist entry
	 *
	 * @param Title $title
	 * @param int $namespace
	 * @param string $dbKey
	 * @return bool Whether this item is valid
	 */
	private function checkTitle( $title, $namespace, $dbKey ) {
		if ( $title
			&& ( $title->isExternal()
				|| $title->getNamespace() < 0
			)
		) {
			$title = false; // unrecoverable
		}

		if ( !$title
			|| $title->getNamespace() != $namespace
			|| $title->getDBkey() != $dbKey
		) {
			$this->badItems[] = array( $title, $namespace, $dbKey );
		}

		return (bool)$title;
	}

	/**
	 * Attempts to clean up broken items
	 */
	private function cleanupWatchlist() {
		if ( !count( $this->badItems ) ) {
			return; //nothing to do
		}

		$dbw = wfGetDB( DB_MASTER );
		$user = $this->getUser();

		foreach ( $this->badItems as $row ) {
			list( $title, $namespace, $dbKey ) = $row;
			$action = $title ? 'cleaning up' : 'deleting';
			wfDebug( "User {$user->getName()} has broken watchlist item ns($namespace):$dbKey, $action.\n" );

			$dbw->delete( 'watchlist',
				array(
					'wl_user' => $user->getId(),
					'wl_namespace' => $namespace,
					'wl_title' => $dbKey,
				),
				__METHOD__
			);

			// Can't just do an UPDATE instead of DELETE/INSERT due to unique index
			if ( $title ) {
				$user->addWatch( $title );
			}
		}
	}

	/**
	 * Remove all titles from a user's watchlist
	 */
	private function clearWatchlist() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete(
			'watchlist',
			array( 'wl_user' => $this->getUser()->getId() ),
			__METHOD__
		);
	}

	/**
	 * Add a list of titles to a user's watchlist
	 *
	 * $titles can be an array of strings or Title objects; the former
	 * is preferred, since Titles are very memory-heavy
	 *
	 * @param array $titles Array of strings, or Title objects
	 */
	private function watchTitles( $titles, $group = 0 ) {
		$dbw = wfGetDB( DB_MASTER );
		$rows = array();

		foreach ( $titles as $title ) {
			if ( !$title instanceof Title ) {
				$title = Title::newFromText( $title );
			}

			if ( $title instanceof Title ) {
				$rows[] = array(
					'wl_user' => $this->getUser()->getId(),
					'wl_group' => $group,
					'wl_namespace' => MWNamespace::getSubject( $title->getNamespace() ),
					'wl_title' => $title->getDBkey(),
					'wl_notificationtimestamp' => null,
				);
				$rows[] = array(
					'wl_user' => $this->getUser()->getId(),
					'wl_group' => $group,
					'wl_namespace' => MWNamespace::getTalk( $title->getNamespace() ),
					'wl_title' => $title->getDBkey(),
					'wl_notificationtimestamp' => null,
				);
			}
		}

		$dbw->insert( 'watchlist', $rows, __METHOD__, 'IGNORE' );
	}

	/**
	 * Remove a list of titles from a user's watchlist
	 *
	 * $titles can be an array of strings or Title objects; the former
	 * is preferred, since Titles are very memory-heavy
	 *
	 * @param array $titles Array of strings, or Title objects
	 */
	private function unwatchTitles( $titles ) {
		$dbw = wfGetDB( DB_MASTER );

		foreach ( $titles as $title ) {
			if ( !$title instanceof Title ) {
				$title = Title::newFromText( $title );
			}

			if ( $title instanceof Title ) {
				$dbw->delete(
					'watchlist',
					array(
						'wl_user' => $this->getUser()->getId(),
						'wl_namespace' => MWNamespace::getSubject( $title->getNamespace() ),
						'wl_title' => $title->getDBkey(),
					),
					__METHOD__
				);

				$dbw->delete(
					'watchlist',
					array(
						'wl_user' => $this->getUser()->getId(),
						'wl_namespace' => MWNamespace::getTalk( $title->getNamespace() ),
						'wl_title' => $title->getDBkey(),
					),
					__METHOD__
				);

				$page = WikiPage::factory( $title );
				wfRunHooks( 'UnwatchArticleComplete', array( $this->getUser(), &$page ) );
			}
		}
	}

	public function submitNormal( $data ) {
		$removed = array();

<<<<<<< HEAD
		foreach ( $data as $titles ) {
=======
		// Regrouping submission
		$group = intval( $data['group'] );
		unset($data['group']);
		if( $group > -1 ) {
			foreach( $data as $ns => $title_strings ) {
				$nsid = intval( str_replace( 'TitlesNs', '', $ns ) );
				$titles = array();
				foreach( $title_strings as $t ) {
					$title = Title::newFromText( $t );
					$titles[] = $title;
					$titles[] = $title->getTalkPage();
				}
				if( count( $titles ) > 0 ) {
					$this->wg_obj->regroupTitles( $titles, $group );
				}
			}
			$this->successMessage = $this->msg( 'watchlistedit-normal-donegrouping' )->escaped();
			return true;
		}

		foreach( $data as $titles ) {
>>>>>>> 4e55649... Watchlist grouping
			$this->unwatchTitles( $titles );
			$removed = array_merge( $removed, $titles );
		}

		if ( count( $removed ) > 0 ) {
			$this->successMessage = $this->msg( 'watchlistedit-normal-done'
			)->numParams( count( $removed ) )->parse();
			$this->showTitles( $removed, $this->successMessage );

			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get the standard watchlist editing form
	 *
	 * @return HTMLForm
	 */
	protected function getNormalForm() {
		global $wgContLang;

		$fields = array();

		$count = 0;

		// Allow subscribers to manipulate the list of watched pages (or use it
		// to preload lots of details at once)
		$watchlistInfo = $this->getWatchlistInfo();
		wfRunHooks(
			'WatchlistEditorBeforeFormRender',
			array( &$watchlistInfo )
		);

		foreach ( $watchlistInfo as $namespace => $pages ) {
			$options = array();

<<<<<<< HEAD
			foreach ( array_keys( $pages ) as $dbkey ) {
				$title = Title::makeTitleSafe( $namespace, $dbkey );

				if ( $this->checkTitle( $title, $namespace, $dbkey ) ) {
					$text = $this->buildRemoveLine( $title );
					$options[$text] = $title->getPrefixedText();
=======
			foreach( $pages as $dbkey => $group ) {
				$title = Title::makeTitleSafe( $namespace, $dbkey );
				if ( $this->checkTitle( $title, $namespace, $dbkey )  ) {
					$text = $this->buildRemoveLine( $title, $group );
					$fields['TitlesNs'.$namespace]['options'][$text] = htmlspecialchars( $title->getPrefixedText() );
>>>>>>> 4e55649... Watchlist grouping
					$count++;
				}
			}

			// checkTitle can filter some options out, avoid empty sections
			if ( count( $options ) > 0 ) {
				$fields['TitlesNs' . $namespace] = array(
					'class' => 'EditWatchlistCheckboxSeriesField',
					'options' => $options,
					'section' => "ns$namespace",
				);
			}
		}
		$this->cleanupWatchlist();

		if ( count( $fields ) > 1 && $count > 30 ) {
			$this->toc = Linker::tocIndent();
			$tocLength = 0;

			foreach ( $fields as $data ) {
				# strip out the 'ns' prefix from the section name:
				$ns = substr( $data['section'], 2 );

				$nsText = ( $ns == NS_MAIN )
					? $this->msg( 'blanknamespace' )->escaped()
					: htmlspecialchars( $wgContLang->getFormattedNsText( $ns ) );
				$this->toc .= Linker::tocLine( "editwatchlist-{$data['section']}", $nsText,
					$this->getLanguage()->formatNum( ++$tocLength ), 1 ) . Linker::tocLineEnd();
			}
<<<<<<< HEAD

			$this->toc = Linker::tocList( $this->toc );
=======
			//$this->toc = Linker::tocList( $this->toc );
>>>>>>> 4e55649... Watchlist grouping
		} else {
			$this->toc = false;
		}

<<<<<<< HEAD
		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle() ); // Remove subpage
		$form = new EditWatchlistNormalHTMLForm( $fields, $context );
=======
		$groups = $this->wg_obj->getGroups();
		foreach( $groups as &$g ) {
			$g = $this->msg( 'watchlistedit-normal-change' )->rawParams( $g )->parse();
		}

		$gsOptions = array_merge( array( $this->msg( 'watchlistedit-normal-remove' )->escaped() => -1,
						$this->msg( 'watchlistedit-normal-ungroup' )->escaped() => 0 ), array_flip( $groups ) );

		$fields['group'] = array(
			'type' => 'select',
			'options' => $gsOptions,
			'label' => $this->msg( 'watchlistedit-normal-action' )->escaped()
		);

		$form = new EditWatchlistNormalHTMLForm( $fields, $this->getContext()->getUser()->getId() );
		$form->setTitle( $this->getTitle() );
>>>>>>> 4e55649... Watchlist grouping
		$form->setSubmitTextMsg( 'watchlistedit-normal-submit' );
		$form->setSubmitDestructive();
		# Used message keys:
		# 'accesskey-watchlistedit-normal-submit', 'tooltip-watchlistedit-normal-submit'
		$form->setSubmitTooltip( 'watchlistedit-normal-submit' );
		$form->setWrapperLegendMsg( 'watchlistedit-normal-legend' );
		$form->addHeaderText( $this->msg( 'watchlistedit-normal-explain' )->parse() );
		if( $this->groupName != '' ) {
			$form->addHeaderText( '<p>' . $this->msg( 'watchlistedit-normal-onlygroup' )->rawParams( $this->groupName )->parse() . '</p>' );
		}
		$form->setSubmitCallback( array( $this, 'submitNormal' ) );

		return $form;
	}

	/**
	 * Build the label for a checkbox, with a link to the title, and various additional bits
	 *
<<<<<<< HEAD
	 * @param Title $title
=======
	 * @param $title Title
	 * @param $group int
>>>>>>> 4e55649... Watchlist grouping
	 * @return string
	 */
	private function buildRemoveLine( $title, $gid ) {
		$link = Linker::link( $title );

		$tools['talk'] = Linker::link(
			$title->getTalkPage(),
			$this->msg( 'talkpagelinktext' )->escaped()
		);

		if ( $title->exists() ) {
			$tools['history'] = Linker::linkKnown(
				$title,
				$this->msg( 'history_short' )->escaped(),
				array(),
				array( 'action' => 'history' )
			);
		}

		if ( $title->getNamespace() == NS_USER && !$title->isSubpage() ) {
			$tools['contributions'] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Contributions', $title->getText() ),
				$this->msg( 'contributions' )->escaped()
			);
		}

<<<<<<< HEAD
		wfRunHooks(
			'WatchlistEditorBuildRemoveLine',
			array( &$tools, $title, $title->isRedirect(), $this->getSkin(), &$link )
		);

		if ( $title->isRedirect() ) {
			// Linker already makes class mw-redirect, so this is redundant
			$link = '<span class="watchlistredir">' . $link . '</span>';
		}
=======
		$groups = $this->wg_obj->getGroups( true );
		$wgroup = $groups[$gid];
		if( $gid == 0 ){
			$url = 'none';
		} else {
			$url = $wgroup[0];
		}
		$wgrouplink = Linker::link( SpecialPage::getTitleFor( 'EditWatchlist', $url ), $wgroup[0] );

		wfRunHooks( 'WatchlistEditorBuildRemoveLine', array( &$tools, $title, $title->isRedirect(), $this->getSkin() ) );
>>>>>>> 4e55649... Watchlist grouping

		return $link . " (" . $this->getLanguage()->pipeList( $tools ) . ") (" . $wgrouplink . ")";
	}

	/**
	 * Get a form for editing the watchlist in "raw" mode
	 *
	 * @return HTMLForm
	 */
	protected function getRawForm() {
<<<<<<< HEAD
		$titles = implode( $this->getWatchlist(), "\n" );
		$fields = array(
			'Titles' => array(
				'type' => 'textarea',
				'label-message' => 'watchlistedit-raw-titles',
				'default' => $titles,
			),
		);
		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle( 'raw' ) ); // Reset subpage
		$form = new HTMLForm( $fields, $context );
=======
		$titles = $this->getWatchlist();
		$fields = array();
		$groups = array( 0 => array( $this->msg( 'watchlistedit-nogroup' )->parse(), 0 ) );
		$groups = $groups + $this->wg_obj->getGroups();
		foreach( $groups as $gid => $g ) {
			if( !empty( $titles[$gid] ) ) {
				$fields['Titles_group' . $gid] = array(
					//'type' => 'textarea',
					'class' => 'HTMLTextAreaField',
					'section' => $g[0],
					'cssclass' => 'mw-collapsible mw-collapsed',
					'rows' => 16,
					'label-message' => 'watchlistedit-raw-titles',
					'default' => implode( $titles[$gid], "\n" ),
				);
			}
		}
		$form = new EditWatchlistRawHTMLForm( $fields, $this->getContext() );
		$form->setTitle( $this->getTitle( 'raw' ) );
>>>>>>> 4e55649... Watchlist grouping
		$form->setSubmitTextMsg( 'watchlistedit-raw-submit' );
		# Used message keys: 'accesskey-watchlistedit-raw-submit', 'tooltip-watchlistedit-raw-submit'
		$form->setSubmitTooltip( 'watchlistedit-raw-submit' );
		$form->setWrapperLegendMsg( 'watchlistedit-raw-legend' );
		$form->addHeaderText( $this->msg( 'watchlistedit-raw-explain' )->parse() );
		$form->setSubmitCallback( array( $this, 'submitRaw' ) );

		return $form;
	}

	/**
	 * Get a form for clearing the watchlist
	 *
	 * @return HTMLForm
	 */
	protected function getClearForm() {
		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle( 'clear' ) ); // Reset subpage
		$form = new HTMLForm( array(), $context );
		$form->setSubmitTextMsg( 'watchlistedit-clear-submit' );
		# Used message keys: 'accesskey-watchlistedit-clear-submit', 'tooltip-watchlistedit-clear-submit'
		$form->setSubmitTooltip( 'watchlistedit-clear-submit' );
		$form->setWrapperLegendMsg( 'watchlistedit-clear-legend' );
		$form->addHeaderText( $this->msg( 'watchlistedit-clear-explain' )->parse() );
		$form->setSubmitCallback( array( $this, 'submitClear' ) );

		return $form;
	}

	/**
	 * Determine whether we are editing the watchlist, and if so, what
	 * kind of editing operation
	 *
	 * @param WebRequest $request
	 * @param string $par
	 * @return int
	 */
	public static function getMode( $request, $par ) {
		$mode = strtolower( $request->getVal( 'action', $par ) );

		switch ( $mode ) {
			case 'clear':
			case self::EDIT_CLEAR:
				return self::EDIT_CLEAR;
			case 'raw':
			case self::EDIT_RAW:
				return self::EDIT_RAW;
			case 'edit':
			case self::EDIT_NORMAL:
				return self::EDIT_NORMAL;
			default:
				return false;
		}
	}

	/**
	 * Build a set of links for convenient navigation
	 * between watchlist viewing and editing modes
	 *
	 * @param null $unused
	 * @return string
	 */
	public static function buildTools( $unused ) {
		global $wgLang;

		$tools = array();
		$modes = array(
			'view' => array( 'Watchlist', false ),
			'edit' => array( 'EditWatchlist', false ),
			'raw' => array( 'EditWatchlist', 'raw' ),
<<<<<<< HEAD
			'clear' => array( 'EditWatchlist', 'clear' ),
=======
			'group' => array( 'EditWatchlistGroup', false )
>>>>>>> 4e55649... Watchlist grouping
		);

		foreach ( $modes as $mode => $arr ) {
			// can use messages 'watchlisttools-view', 'watchlisttools-edit', 'watchlisttools-raw'
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( $arr[0], $arr[1] ),
				wfMessage( "watchlisttools-{$mode}" )->escaped()
			);
		}

		return Html::rawElement(
			'span',
			array( 'class' => 'mw-watchlist-toollinks' ),
			wfMessage( 'parentheses', $wgLang->pipeList( $tools ) )->text()
		);
	}
}

/**
 * Extend HTMLForm purely so we can have a more sane way of getting the section headers
 */
class EditWatchlistNormalHTMLForm extends HTMLForm {
	public function getLegend( $namespace ) {
		$namespace = substr( $namespace, 2 );

		return $namespace == NS_MAIN
			? $this->msg( 'blanknamespace' )->escaped()
			: htmlspecialchars( $this->getContext()->getLanguage()->getFormattedNsText( $namespace ) );
	}

	public function getBody() {
		return $this->displaySection( $this->mFieldTree, '', 'editwatchlist-' );
	}
}

class EditWatchlistRawHTMLForm extends HTMLForm {
	public function getLegend( $group ) {
		return $group;
	}
}

class EditWatchlistCheckboxSeriesField extends HTMLMultiSelectField {
	/**
	 * HTMLMultiSelectField throws validation errors if we get input data
	 * that doesn't match the data set in the form setup. This causes
	 * problems if something gets removed from the watchlist while the
	 * form is open (bug 32126), but we know that invalid items will
	 * be harmless so we can override it here.
	 *
	 * @param string $value The value the field was submitted with
	 * @param array $alldata The data collected from the form
	 * @return bool|string Bool true on success, or String error to display.
	 */
	function validate( $value, $alldata ) {
		// Need to call into grandparent to be a good citizen. :)
		return HTMLFormField::validate( $value, $alldata );
	}
}
