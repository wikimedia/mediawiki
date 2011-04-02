<?php

/**
 * Provides the UI through which users can perform editing
 * operations on their watchlist
 *
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

	public function __construct(){
		parent::__construct( 'EditWatchlist' );
	}

	/**
	 * Main execution point
	 *
	 * @param $user User
	 * @param $output OutputPage
	 * @param $request WebRequest
	 * @param $mode int
	 */
	public function execute( $mode ) {
		global $wgUser, $wgOut, $wgRequest;
		if( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}

		# Anons don't get a watchlist
		if( $wgUser->isAnon() ) {
			$wgOut->setPageTitle( wfMsg( 'watchnologin' ) );
			$llink = $wgUser->getSkin()->linkKnown(
				SpecialPage::getTitleFor( 'Userlogin' ),
				wfMsgHtml( 'loginreqlink' ),
				array(),
				array( 'returnto' => $this->getTitle()->getPrefixedText() )
			);
			$wgOut->addWikiMsgArray( 'watchlistanontext', array( $llink ), array( 'replaceafter' ) );
			return;
		}

		$sub  = wfMsgExt(
			'watchlistfor2',
			array( 'parseinline', 'replaceafter' ),
			$wgUser->getName(),
			SpecialEditWatchlist::buildTools( $wgUser->getSkin() )
		);
		$wgOut->setSubtitle( $sub );

		# B/C: $mode used to be waaay down the parameter list, and the first parameter
		# was $wgUser
		if( $mode instanceof User ){
			$args = func_get_args();
			if( count( $args >= 4 ) ){
				$mode = $args[3];
			}
		}
		$mode = self::getMode( $wgRequest, $mode );

		switch( $mode ) {
			case self::EDIT_CLEAR:
				// The "Clear" link scared people too much.
				// Pass on to the raw editor, from which it's very easy to clear.

			case self::EDIT_RAW:
				$wgOut->setPageTitle( wfMsg( 'watchlistedit-raw-title' ) );
				$form = $this->getRawForm( $wgUser );
				if( $form->show() ){
					$wgOut->addHTML( $this->successMessage );
					$wgOut->returnToMain();
				}
				break;

			case self::EDIT_NORMAL:
			default:
				$wgOut->setPageTitle( wfMsg( 'watchlistedit-normal-title' ) );
				$form = $this->getNormalForm( $wgUser );
				if( $form->show() ){
					$wgOut->addHTML( $this->successMessage );
					$wgOut->returnToMain();
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

	public function submitRaw( $data ){
		global $wgUser, $wgLang;
		$wanted = $this->extractTitles( $data['Titles'] );
		$current = $this->getWatchlist( $wgUser );

		if( count( $wanted ) > 0 ) {
			$toWatch = array_diff( $wanted, $current );
			$toUnwatch = array_diff( $current, $wanted );
			$this->watchTitles( $toWatch, $wgUser );
			$this->unwatchTitles( $toUnwatch, $wgUser );
			$wgUser->invalidateCache();

			if( count( $toWatch ) > 0 || count( $toUnwatch ) > 0 ){
				$this->successMessage = wfMessage( 'watchlistedit-raw-done' )->parse();
			} else {
				return false;
			}

			if( count( $toWatch ) > 0 ) {
				$this->successMessage .= wfMessage(
					'watchlistedit-raw-added',
					$wgLang->formatNum( count( $toWatch ) )
				);
				$this->showTitles( $toWatch, $this->successMessage, $wgUser->getSkin() );
			}

			if( count( $toUnwatch ) > 0 ) {
				$this->successMessage .= wfMessage(
					'watchlistedit-raw-removed',
					$wgLang->formatNum( count( $toUnwatch ) )
				);
				$this->showTitles( $toUnwatch, $this->successMessage, $wgUser->getSkin() );
			}
		} else {
			$this->clearWatchlist( $wgUser );
			$wgUser->invalidateCache();
			$this->successMessage .= wfMessage(
				'watchlistedit-raw-removed',
				$wgLang->formatNum( count( $current ) )
			);
			$this->showTitles( $current, $this->successMessage, $wgUser->getSkin() );
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
	 * @param $skin Skin
	 */
	private function showTitles( $titles, &$output, $skin ) {
		$talk = wfMsgHtml( 'talkpagelinktext' );
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
					. $skin->link( $title )
					. ' (' . $skin->link( $title->getTalkPage(), $talk )
					. ")</li>\n";
			}
		}
		$output .= "</ul>\n";
	}

	/**
	 * Count the number of titles on a user's watchlist, excluding talk pages
	 *
	 * @param $user User
	 * @return int
	 */
	private function countWatchlist( $user ) {
		$dbr = wfGetDB( DB_MASTER );
		$res = $dbr->select( 'watchlist', 'COUNT(*) AS count', array( 'wl_user' => $user->getId() ), __METHOD__ );
		$row = $dbr->fetchObject( $res );
		return ceil( $row->count / 2 ); // Paranoia
	}

	/**
	 * Prepare a list of titles on a user's watchlist (excluding talk pages)
	 * and return an array of (prefixed) strings
	 *
	 * @param $user User
	 * @return array
	 */
	private function getWatchlist( $user ) {
		$list = array();
		$dbr = wfGetDB( DB_MASTER );
		$res = $dbr->select(
			'watchlist',
			'*',
			array(
				'wl_user' => $user->getId(),
			),
			__METHOD__
		);
		if( $res->numRows() > 0 ) {
			foreach ( $res as $row ) {
				$title = Title::makeTitleSafe( $row->wl_namespace, $row->wl_title );
				if( $title instanceof Title && !$title->isTalkPage() )
					$list[] = $title->getPrefixedText();
			}
			$res->free();
		}
		return $list;
	}

	/**
	 * Get a list of titles on a user's watchlist, excluding talk pages,
	 * and return as a two-dimensional array with namespace, title and
	 * redirect status
	 *
	 * @param $user User
	 * @return array
	 */
	private function getWatchlistInfo( $user ) {
		$titles = array();
		$dbr = wfGetDB( DB_MASTER );

		$res = $dbr->select(
			array( 'watchlist', 'page' ),
			array(
				'wl_namespace',
				'wl_title',
				'page_id',
				'page_len',
				'page_is_redirect',
				'page_latest'
			),
			array( 'wl_user' => $user->getId() ),
			__METHOD__,
			array( 'ORDER BY' => 'wl_namespace, wl_title' ),
			array( 'page' => array(
				'LEFT JOIN',
				'wl_namespace = page_namespace AND wl_title = page_title'
			) )
		);

		if( $res && $dbr->numRows( $res ) > 0 ) {
			$cache = LinkCache::singleton();
			foreach ( $res as $row ) {
				$title = Title::makeTitleSafe( $row->wl_namespace, $row->wl_title );
				if( $title instanceof Title ) {
					// Update the link cache while we're at it
					if( $row->page_id ) {
						$cache->addGoodLinkObj( $row->page_id, $title, $row->page_len, $row->page_is_redirect, $row->page_latest );
					} else {
						$cache->addBadLinkObj( $title );
					}
					// Ignore non-talk
					if( !$title->isTalkPage() ) {
						$titles[$row->wl_namespace][$row->wl_title] = $row->page_is_redirect;
					}
				}
			}
		}
		return $titles;
	}

	/**
	 * Show a message indicating the number of items on the user's watchlist,
	 * and return this count for additional checking
	 *
	 * @param $output OutputPage
	 * @param $user User
	 * @return int
	 */
	private function showItemCount( $output, $user ) {
		if( ( $count = $this->countWatchlist( $user ) ) > 0 ) {
			$output->addHTML( wfMsgExt( 'watchlistedit-numitems', 'parse',
				$GLOBALS['wgLang']->formatNum( $count ) ) );
		} else {
			$output->addHTML( wfMsgExt( 'watchlistedit-noitems', 'parse' ) );
		}
		return $count;
	}

	/**
	 * Remove all titles from a user's watchlist
	 *
	 * @param $user User
	 */
	private function clearWatchlist( $user ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->delete(
			'watchlist',
			array( 'wl_user' => $user->getId() ),
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
	 * @param $user User
	 */
	private function watchTitles( $titles, $user ) {
		$dbw = wfGetDB( DB_MASTER );
		$rows = array();
		foreach( $titles as $title ) {
			if( !$title instanceof Title ) {
				$title = Title::newFromText( $title );
			}
			if( $title instanceof Title ) {
				$rows[] = array(
					'wl_user' => $user->getId(),
					'wl_namespace' => ( $title->getNamespace() & ~1 ),
					'wl_title' => $title->getDBkey(),
					'wl_notificationtimestamp' => null,
				);
				$rows[] = array(
					'wl_user' => $user->getId(),
					'wl_namespace' => ( $title->getNamespace() | 1 ),
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
	 * @param $user User
	 */
	private function unwatchTitles( $titles, $user ) {
		$dbw = wfGetDB( DB_MASTER );
		foreach( $titles as $title ) {
			if( !$title instanceof Title ) {
				$title = Title::newFromText( $title );
			}
			if( $title instanceof Title ) {
				$dbw->delete(
					'watchlist',
					array(
						'wl_user' => $user->getId(),
						'wl_namespace' => ( $title->getNamespace() & ~1 ),
						'wl_title' => $title->getDBkey(),
					),
					__METHOD__
				);
				$dbw->delete(
					'watchlist',
					array(
						'wl_user' => $user->getId(),
						'wl_namespace' => ( $title->getNamespace() | 1 ),
						'wl_title' => $title->getDBkey(),
					),
					__METHOD__
				);
				$article = new Article($title);
				wfRunHooks('UnwatchArticleComplete',array(&$user,&$article));
			}
		}
	}

	public function submitNormal( $data ) {
		global $wgUser;
		$removed = array();

		foreach( $data as $titles ) {
			$this->unwatchTitles( $titles, $wgUser );
			$removed += $titles;
		}

		if( count( $removed ) > 0 ) {
			global $wgLang;
			$this->successMessage = wfMessage(
				'watchlistedit-normal-done',
				$wgLang->formatNum( count( $removed ) )
			);
			$this->showTitles( $removed, $this->successMessage, $wgUser->getSkin() );
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get the standard watchlist editing form
	 *
	 * @param $user User
	 * @return HTMLForm
	 */
	protected function getNormalForm( $user ){
		global $wgContLang;
		$skin = $user->getSkin();
		$fields = array();

		foreach( $this->getWatchlistInfo( $user ) as $namespace => $pages ){

			$namespace == NS_MAIN
				? wfMsgHtml( 'blanknamespace' )
				: htmlspecialchars( $wgContLang->getFormattedNsText( $namespace ) );

			$fields['TitlesNs'.$namespace] = array(
				'type' => 'multiselect',
				'options' => array(),
				'section' => "ns$namespace",
			);

			foreach( $pages as $dbkey => $redirect ){
				$title = Title::makeTitleSafe( $namespace, $dbkey );
				$text = $this->buildRemoveLine( $title, $redirect, $skin );
				$fields['TitlesNs'.$namespace]['options'][$text] = $title->getEscapedText();
			}
		}

		$form = new EditWatchlistNormalHTMLForm( $fields );
		$form->setTitle( $this->getTitle() );
		$form->setSubmitText( wfMessage( 'watchlistedit-normal-submit' )->text() );
		$form->setWrapperLegend( wfMessage( 'watchlistedit-normal-legend' )->text() );
		$form->addHeaderText( wfMessage( 'watchlistedit-normal-explain' )->parse() );
		$form->setSubmitCallback( array( $this, 'submitNormal' ) );
		return $form;
	}

	/**
	 * Build the label for a checkbox, with a link to the title, and various additional bits
	 *
	 * @param $title Title
	 * @param $redirect bool
	 * @param $skin Skin
	 * @return string
	 */
	private function buildRemoveLine( $title, $redirect, $skin ) {
		global $wgLang;

		$link = $skin->link( $title );
		if( $redirect ) {
			$link = '<span class="watchlistredir">' . $link . '</span>';
		}
		$tools[] = $skin->link( $title->getTalkPage(), wfMsgHtml( 'talkpagelinktext' ) );
		if( $title->exists() ) {
			$tools[] = $skin->link(
				$title,
				wfMsgHtml( 'history_short' ),
				array(),
				array( 'action' => 'history' ),
				array( 'known', 'noclasses' )
			);
		}
		if( $title->getNamespace() == NS_USER && !$title->isSubpage() ) {
			$tools[] = $skin->link(
				SpecialPage::getTitleFor( 'Contributions', $title->getText() ),
				wfMsgHtml( 'contributions' ),
				array(),
				array(),
				array( 'known', 'noclasses' )
			);
		}

		wfRunHooks( 'WatchlistEditorBuildRemoveLine', array( &$tools, $title, $redirect, $skin ) );

		return $link . " (" . $wgLang->pipeList( $tools ) . ")";
	}

	/**
	 * Get a form for editing the watchlist in "raw" mode
	 *
	 * @param $user User
	 * @return HTMLForm
	 */
	protected function getRawForm( $user ){
		$titles = implode( array_map( 'htmlspecialchars', $this->getWatchlist( $user ) ), "\n" );
		$fields = array(
			'Titles' => array(
				'type' => 'textarea',
				'label-message' => 'watchlistedit-raw-titles',
				'default' => $titles,
			),
		);
		$form = new HTMLForm( $fields );
		$form->setTitle( $this->getTitle( 'raw' ) );
		$form->setSubmitText( wfMessage( 'watchlistedit-raw-submit' )->text() );
		$form->setWrapperLegend( wfMessage( 'watchlistedit-raw-legend' )->text() );
		$form->addHeaderText( wfMessage( 'watchlistedit-raw-explain' )->parse() );
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
	 * @param $skin Skin to use
	 * @return string
	 */
	public static function buildTools( $skin ) {
		global $wgLang;

		$tools = array();
		$modes = array(
			'view' => array( 'Watchlist', false ),
			'edit' => array( 'EditWatchlist', false ),
			'raw' => array( 'EditWatchlist', 'raw' ),
		);
		foreach( $modes as $mode => $arr ) {
			// can use messages 'watchlisttools-view', 'watchlisttools-edit', 'watchlisttools-raw'
			$tools[] = $skin->linkKnown(
				SpecialPage::getTitleFor( $arr[0], $arr[1] ),
				wfMsgHtml( "watchlisttools-{$mode}" )
			);
		}
		return Html::rawElement( 'span',
					array( 'class' => 'mw-watchlist-toollinks' ),
					wfMsg( 'parentheses', $wgLang->pipeList( $tools ) ) );
	}
}

# B/C since 1.18
class WatchlistEditor extends SpecialEditWatchlist {}

/**
 * Extend HTMLForm purely so we can have a more sane way of getting the section headers
 */
class EditWatchlistNormalHTMLForm extends HTMLForm {
	public function getLegend( $namespace ){
		global $wgLang;
		$namespace = substr( $namespace, 2 );
		return $namespace == NS_MAIN
			? wfMsgHtml( 'blanknamespace' )
			: htmlspecialchars( $wgLang->getFormattedNsText( $namespace ) );
	}
}