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
	 * Editing modes
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
		parent::__construct( 'EditWatchlist' );
	}

	/**
	 * Main execution point
	 *
	 * @param $par string: This could be the mode OR a watchlist query by user/group
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

		$out = $this->getOutput();

		# Anons don't get a watchlist
		if( $this->getUser()->isAnon() ) {
			$out->setPageTitle( $this->msg( 'watchnologin' ) );
			$llink = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Userlogin' ),
				$this->msg( 'loginreqlink' )->escaped(),
				array(),
				array( 'returnto' => $this->getTitle()->getPrefixedText() )
			);
			$out->addHTML( $this->msg( 'watchlistanontext' )->rawParams( $llink )->parse() );
			return;
		}

		$this->checkPermissions();

		$this->outputHeader();

		$out->addSubtitle( $this->msg( 'watchlistfor2', $this->getUser()->getName()
			)->rawParams( SpecialEditWatchlist::buildTools( null ) ) );

		# B/C: $mode used to be waaay down the parameter list, and the first parameter
		# was $wgUser
		if( $mode instanceof User ) {
			$args = func_get_args();
			if( count( $args >= 4 ) ) {
				$mode = $args[3];
			}
		}
		$mode = self::getMode( $this->getRequest(), $mode );

		switch( $mode ) {
			case self::EDIT_CLEAR:
				// The "Clear" link scared people too much.
				// Pass on to the raw editor, from which it's very easy to clear.

			case self::EDIT_RAW:
				$out->setPageTitle( $this->msg( 'watchlistedit-raw-title' ) );
				$form = $this->getRawForm();
				if( $form->show() ) {
					$out->addHTML( $this->successMessage );
					$out->addReturnTo( SpecialPage::getTitleFor( 'Watchlist' ) );
				}
				break;

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
				}
				break;
		}
	}

	/**
	 * Extract a list of titles from a blob of text, returning
	 * (prefixed) strings; unwatchable titles are ignored
	 *
	 * @param $list String
	 * @return array
	 */
	private function extractTitles( $list ) {
		$titles = array();
		$list = explode( "\n", trim( $list ) );
		if( !is_array( $list ) ) {
			return array();
		}
		foreach( $list as $text ) {
			$text = trim( $text );
			if( strlen( $text ) > 0 ) {
				$title = Title::newFromText( $text );
				if( $title instanceof Title && $title->isWatchable() ) {
					$titles[] = $title->getPrefixedText();
				}
			}
		}
		return array_unique( $titles );
	}

	public function submitRaw( $data ) {
		$wanted = array();
		$current = $this->getWatchlist();
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
				$this->successMessage = $this->msg( 'watchlistedit-raw-done' )->parse();
			} else {
				return false;
			}

			if( count( $allWatched ) > 0 ) {
				$this->successMessage .= ' ' . $this->msg( 'watchlistedit-raw-added'
					)->numParams( count( $allWatched ) )->parse();
				$this->showTitles( $allWatched, $this->successMessage );
			}

			if( count( $allUnwatched ) > 0 ) {
				$this->successMessage .= ' ' . $this->msg( 'watchlistedit-raw-removed'
					)->numParams( count( $allUnwatched ) )->parse();
				$this->showTitles( $allUnwatched, $this->successMessage );
			}
		} else {
			$this->clearWatchlist();
			$this->getUser()->invalidateCache();

			if( count( $current, 1 ) > count( $current ) ) {
				$this->successMessage = $this->msg( 'watchlistedit-raw-done' )->parse();
			} else {
				return false;
			}

			$this->successMessage .= ' ' . $this->msg( 'watchlistedit-raw-removed'
				)->numParams( count( $current ) )->parse();
			$this->showTitles( $current, $this->successMessage );
		}
		return true;
	}

	/**
	 * Print out a list of linked titles
	 *
	 * $titles can be an array of strings or Title objects; the former
	 * is preferred, since Titles are very memory-heavy
	 *
	 * @param $titles array of strings, or Title objects
	 * @param $output String
	 */
	private function showTitles( $titles, &$output ) {
		$talk = $this->msg( 'talkpagelinktext' )->escaped();
		// Do a batch existence check
		$batch = new LinkBatch();
		foreach( $titles as $title ) {
			if( !$title instanceof Title ) {
				$title = Title::newFromText( $title );
			}
			if( $title instanceof Title ) {
				$batch->addObj( $title );
				$batch->addObj( $title->getTalkPage() );
			}
		}
		$batch->execute();
		// Print out the list
		$output .= "<ul>\n";
		foreach( $titles as $title ) {
			if( !$title instanceof Title ) {
				$title = Title::newFromText( $title );
			}
			if( $title instanceof Title ) {
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
		if( $res->numRows() > 0 ) {
			foreach ( $res as $row ) {
				$title = Title::makeTitleSafe( $row->wl_namespace, $row->wl_title );
				if ( $this->checkTitle( $title, $row->wl_namespace, $row->wl_title )
					&& !$title->isTalkPage()
				) {
					$list[$row->wl_group][] = $title->getPrefixedText();
				}
			}
			$res->free();
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
	private function getWatchlistInfo() {
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
	 * @param String $dbKey
	 * @return bool: Whether this item is valid
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
		if( !count( $this->badItems ) ) {
			return; //nothing to do
		}
		$dbw = wfGetDB( DB_MASTER );
		$user = $this->getUser();
		foreach ( $this->badItems as $row ) {
			list( $title, $namespace, $dbKey ) = $row;
			wfDebug( "User {$user->getName()} has broken watchlist item ns($namespace):$dbKey, "
				. ( $title ? 'cleaning up' : 'deleting' ) . ".\n"
			);

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
	 * @param $titles Array of strings, or Title objects
	 */
	private function watchTitles( $titles, $group = 0 ) {
		$dbw = wfGetDB( DB_MASTER );
		$rows = array();
		foreach( $titles as $title ) {
			if( !$title instanceof Title ) {
				$title = Title::newFromText( $title );
			}
			if( $title instanceof Title ) {
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
	 * @param $titles Array of strings, or Title objects
	 */
	private function unwatchTitles( $titles ) {
		$dbw = wfGetDB( DB_MASTER );
		foreach( $titles as $title ) {
			if( !$title instanceof Title ) {
				$title = Title::newFromText( $title );
			}
			if( $title instanceof Title ) {
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
			$this->unwatchTitles( $titles );
			$removed = array_merge( $removed, $titles );
		}

		if( count( $removed ) > 0 ) {
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

		foreach( $this->getWatchlistInfo() as $namespace => $pages ) {
			if ( $namespace >= 0 ) {
				$fields['TitlesNs'.$namespace] = array(
					'class' => 'EditWatchlistCheckboxSeriesField',
					'options' => array(),
					'section' => "ns$namespace",
				);
			}

			foreach( $pages as $dbkey => $group ) {
				$title = Title::makeTitleSafe( $namespace, $dbkey );
				if ( $this->checkTitle( $title, $namespace, $dbkey )  ) {
					$text = $this->buildRemoveLine( $title, $group );
					$fields['TitlesNs'.$namespace]['options'][$text] = htmlspecialchars( $title->getPrefixedText() );
					$count++;
				}
			}
		}
		$this->cleanupWatchlist();

		if ( count( $fields ) > 1 && $count > 30 ) {
			$this->toc = Linker::tocIndent();
			$tocLength = 0;
			foreach( $fields as $data ) {

				# strip out the 'ns' prefix from the section name:
				$ns = substr( $data['section'], 2 );

				$nsText = ($ns == NS_MAIN)
					? $this->msg( 'blanknamespace' )->escaped()
					: htmlspecialchars( $wgContLang->getFormattedNsText( $ns ) );
				$this->toc .= Linker::tocLine( "editwatchlist-{$data['section']}", $nsText,
					$this->getLanguage()->formatNum( ++$tocLength ), 1 ) . Linker::tocLineEnd();
			}
			//$this->toc = Linker::tocList( $this->toc );
		} else {
			$this->toc = false;
		}

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
		$form->setSubmitTextMsg( 'watchlistedit-normal-submit' );
		# Used message keys: 'accesskey-watchlistedit-normal-submit', 'tooltip-watchlistedit-normal-submit'
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
	 * @param $title Title
	 * @param $group int
	 * @return string
	 */
	private function buildRemoveLine( $title, $gid ) {
		$link = Linker::link( $title );
		if( $title->isRedirect() ) {
			// Linker already makes class mw-redirect, so this is redundant
			$link = '<span class="watchlistredir">' . $link . '</span>';
		}
		$tools[] = Linker::link( $title->getTalkPage(), $this->msg( 'talkpagelinktext' )->escaped() );
		if( $title->exists() ) {
			$tools[] = Linker::linkKnown(
				$title,
				$this->msg( 'history_short' )->escaped(),
				array(),
				array( 'action' => 'history' )
			);
		}
		if( $title->getNamespace() == NS_USER && !$title->isSubpage() ) {
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Contributions', $title->getText() ),
				$this->msg( 'contributions' )->escaped()
			);
		}

		$groups = $this->wg_obj->getGroups( true );
		$wgroup = $groups[$gid];
		if( $gid == 0 ){
			$url = 'none';
		} else {
			$url = $wgroup[0];
		}
		$wgrouplink = Linker::link( SpecialPage::getTitleFor( 'EditWatchlist', $url ), $wgroup[0] );

		wfRunHooks( 'WatchlistEditorBuildRemoveLine', array( &$tools, $title, $title->isRedirect(), $this->getSkin() ) );

		return $link . " (" . $this->getLanguage()->pipeList( $tools ) . ") (" . $wgrouplink . ")";
	}

	/**
	 * Get a form for editing the watchlist in "raw" mode
	 *
	 * @return HTMLForm
	 */
	protected function getRawForm() {
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
		$form->setSubmitTextMsg( 'watchlistedit-raw-submit' );
		# Used message keys: 'accesskey-watchlistedit-raw-submit', 'tooltip-watchlistedit-raw-submit'
		$form->setSubmitTooltip('watchlistedit-raw-submit');
		$form->setWrapperLegendMsg( 'watchlistedit-raw-legend' );
		$form->addHeaderText( $this->msg( 'watchlistedit-raw-explain' )->parse() );
		$form->setSubmitCallback( array( $this, 'submitRaw' ) );
		return $form;
	}

	/**
	 * Determine whether we are editing the watchlist, and if so, what
	 * kind of editing operation
	 *
	 * @param $request WebRequest
	 * @param $par mixed
	 * @return int
	 */
	public static function getMode( $request, $par ) {
		$mode = strtolower( $request->getVal( 'action', $par ) );
		switch( $mode ) {
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
	 * @param $unused
	 * @return string
	 */
	public static function buildTools( $unused ) {
		global $wgLang;

		$tools = array();
		$modes = array(
			'view' => array( 'Watchlist', false ),
			'edit' => array( 'EditWatchlist', false ),
			'raw' => array( 'EditWatchlist', 'raw' ),
			'group' => array( 'EditWatchlistGroup', false )
		);
		foreach( $modes as $mode => $arr ) {
			// can use messages 'watchlisttools-view', 'watchlisttools-edit', 'watchlisttools-raw'
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( $arr[0], $arr[1] ),
				wfMessage( "watchlisttools-{$mode}" )->escaped()
			);
		}
		return Html::rawElement( 'span',
					array( 'class' => 'mw-watchlist-toollinks' ),
					wfMessage( 'parentheses', $wgLang->pipeList( $tools ) )->text() );
	}
}

# B/C since 1.18
class WatchlistEditor extends SpecialEditWatchlist {}

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
	 * @param $value String the value the field was submitted with
	 * @param $alldata Array the data collected from the form
	 * @return Mixed Bool true on success, or String error to display.
	 */
	function validate( $value, $alldata ) {
		// Need to call into grandparent to be a good citizen. :)
		return HTMLFormField::validate( $value, $alldata );
	}
}
