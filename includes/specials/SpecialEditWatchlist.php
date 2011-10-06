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
	 * @param $mode int
	 */
	public function execute( $mode ) {
		if( wfReadOnly() ) {
			throw new ReadOnlyError;
		}

		$this->setHeaders();

		$out = $this->getOutput();

		# Anons don't get a watchlist
		if( $this->getUser()->isAnon() ) {
			$out->setPageTitle( wfMsg( 'watchnologin' ) );
			$llink = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Userlogin' ),
				wfMsgHtml( 'loginreqlink' ),
				array(),
				array( 'returnto' => $this->getTitle()->getPrefixedText() )
			);
			$out->addHTML( wfMessage( 'watchlistanontext' )->rawParams( $llink )->parse() );
			return;
		}

		$this->outputHeader();

		$sub  = wfMsgExt(
			'watchlistfor2',
			array( 'parseinline', 'replaceafter' ),
			$this->getUser()->getName(),
			SpecialEditWatchlist::buildTools( null )
		);
		$out->setSubtitle( $sub );

		# B/C: $mode used to be waaay down the parameter list, and the first parameter
		# was $wgUser
		if( $mode instanceof User ){
			$args = func_get_args();
			if( count( $args >= 4 ) ){
				$mode = $args[3];
			}
		}
		$mode = self::getMode( $this->getRequest(), $mode );

		switch( $mode ) {
			case self::EDIT_CLEAR:
				// The "Clear" link scared people too much.
				// Pass on to the raw editor, from which it's very easy to clear.

			case self::EDIT_RAW:
				$out->setPageTitle( wfMsg( 'watchlistedit-raw-title' ) );
				$form = $this->getRawForm();
				if( $form->show() ){
					$out->addHTML( $this->successMessage );
					$out->returnToMain();
				}
				break;

			case self::EDIT_NORMAL:
			default:
				$out->setPageTitle( wfMsg( 'watchlistedit-normal-title' ) );
				$form = $this->getNormalForm();
				if( $form->show() ){
					$out->addHTML( $this->successMessage );
					$out->returnToMain();
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
		$wanted = $this->extractTitles( $data['Titles'] );
		$current = $this->getWatchlist();

		if( count( $wanted ) > 0 ) {
			$toWatch = array_diff( $wanted, $current );
			$toUnwatch = array_diff( $current, $wanted );
			$this->watchTitles( $toWatch );
			$this->unwatchTitles( $toUnwatch );
			$this->getUser()->invalidateCache();

			if( count( $toWatch ) > 0 || count( $toUnwatch ) > 0 ){
				$this->successMessage = wfMessage( 'watchlistedit-raw-done' )->parse();
			} else {
				return false;
			}

			if( count( $toWatch ) > 0 ) {
				$this->successMessage .= wfMessage(
					'watchlistedit-raw-added',
					$this->getLang()->formatNum( count( $toWatch ) )
				);
				$this->showTitles( $toWatch, $this->successMessage );
			}

			if( count( $toUnwatch ) > 0 ) {
				$this->successMessage .= wfMessage(
					'watchlistedit-raw-removed',
					$this->getLang()->formatNum( count( $toUnwatch ) )
				);
				$this->showTitles( $toUnwatch, $this->successMessage );
			}
		} else {
			$this->clearWatchlist();
			$this->getUser()->invalidateCache();
			$this->successMessage .= wfMessage(
				'watchlistedit-raw-removed',
				$this->getLang()->formatNum( count( $current ) )
			);
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
			'*',
			array(
				'wl_user' => $this->getUser()->getId(),
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
	 * @return array
	 */
	private function getWatchlistInfo() {
		$titles = array();
		$dbr = wfGetDB( DB_MASTER );

		$res = $dbr->select(
			array( 'watchlist' ),
			array( 'wl_namespace',  'wl_title' ),
			array( 'wl_user' => $this->getUser()->getId() ),
			__METHOD__,
			array( 'ORDER BY' => 'wl_namespace, wl_title' )
		);

		$lb = new LinkBatch();
		foreach ( $res as $row ) {
			$lb->add( $row->wl_namespace, $row->wl_title );
			if ( !MWNamespace::isTalk( $row->wl_namespace ) ) {
				$titles[$row->wl_namespace][$row->wl_title] = false;
			}
		}

		$lb->execute();
		return $titles;
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
	private function watchTitles( $titles ) {
		$dbw = wfGetDB( DB_MASTER );
		$rows = array();
		foreach( $titles as $title ) {
			if( !$title instanceof Title ) {
				$title = Title::newFromText( $title );
			}
			if( $title instanceof Title ) {
				$rows[] = array(
					'wl_user' => $this->getUser()->getId(),
					'wl_namespace' => ( $title->getNamespace() & ~1 ),
					'wl_title' => $title->getDBkey(),
					'wl_notificationtimestamp' => null,
				);
				$rows[] = array(
					'wl_user' => $this->getUser()->getId(),
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
						'wl_namespace' => ( $title->getNamespace() & ~1 ),
						'wl_title' => $title->getDBkey(),
					),
					__METHOD__
				);
				$dbw->delete(
					'watchlist',
					array(
						'wl_user' => $this->getUser()->getId(),
						'wl_namespace' => ( $title->getNamespace() | 1 ),
						'wl_title' => $title->getDBkey(),
					),
					__METHOD__
				);
				$article = new Article( $title, 0 );
				wfRunHooks( 'UnwatchArticleComplete', array( $this->getUser(), &$article ) );
			}
		}
	}

	public function submitNormal( $data ) {
		$removed = array();

		foreach( $data as $titles ) {
			$this->unwatchTitles( $titles );
			$removed += $titles;
		}

		if( count( $removed ) > 0 ) {
			$this->successMessage = wfMessage(
				'watchlistedit-normal-done',
				$this->getLang()->formatNum( count( $removed ) )
			);
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
	protected function getNormalForm(){
		global $wgContLang;

		$fields = array();

		foreach( $this->getWatchlistInfo() as $namespace => $pages ){

			$namespace == NS_MAIN
				? wfMsgHtml( 'blanknamespace' )
				: htmlspecialchars( $wgContLang->getFormattedNsText( $namespace ) );

			$fields['TitlesNs'.$namespace] = array(
				'type' => 'multiselect',
				'options' => array(),
				'section' => "ns$namespace",
			);

			foreach( array_keys( $pages ) as $dbkey ){
				$title = Title::makeTitleSafe( $namespace, $dbkey );
				$text = $this->buildRemoveLine( $title );
				$fields['TitlesNs'.$namespace]['options'][$text] = $title->getEscapedText();
			}
		}

		$form = new EditWatchlistNormalHTMLForm( $fields, $this->getContext() );
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
	 * @return string
	 */
	private function buildRemoveLine( $title ) {
		$link = Linker::link( $title );
		if( $title->isRedirect() ) {
			// Linker already makes class mw-redirect, so this is redundant
			$link = '<span class="watchlistredir">' . $link . '</span>';
		}
		$tools[] = Linker::link( $title->getTalkPage(), wfMsgHtml( 'talkpagelinktext' ) );
		if( $title->exists() ) {
			$tools[] = Linker::linkKnown(
				$title,
				wfMsgHtml( 'history_short' ),
				array(),
				array( 'action' => 'history' )
			);
		}
		if( $title->getNamespace() == NS_USER && !$title->isSubpage() ) {
			$tools[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Contributions', $title->getText() ),
				wfMsgHtml( 'contributions' )
			);
		}

		wfRunHooks( 'WatchlistEditorBuildRemoveLine', array( &$tools, $title, $title->isRedirect(), $this->getSkin() ) );

		return $link . " (" . $this->getLang()->pipeList( $tools ) . ")";
	}

	/**
	 * Get a form for editing the watchlist in "raw" mode
	 *
	 * @return HTMLForm
	 */
	protected function getRawForm(){
		$titles = implode( $this->getWatchlist(), "\n" );
		$fields = array(
			'Titles' => array(
				'type' => 'textarea',
				'label-message' => 'watchlistedit-raw-titles',
				'default' => $titles,
			),
		);
		$form = new HTMLForm( $fields, $this->getContext() );
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
	 * @param $unused Unused
	 * @return string
	 */
	public static function buildTools( $unused ) {
		global $wgLang;

		$tools = array();
		$modes = array(
			'view' => array( 'Watchlist', false ),
			'edit' => array( 'EditWatchlist', false ),
			'raw' => array( 'EditWatchlist', 'raw' ),
		);
		foreach( $modes as $mode => $arr ) {
			// can use messages 'watchlisttools-view', 'watchlisttools-edit', 'watchlisttools-raw'
			$tools[] = Linker::linkKnown(
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
		$namespace = substr( $namespace, 2 );
		return $namespace == NS_MAIN
			? wfMsgHtml( 'blanknamespace' )
			: htmlspecialchars( $this->getContext()->getLang()->getFormattedNsText( $namespace ) );
	}
}
