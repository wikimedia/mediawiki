<?php
/**
 * MediaWiki is the to-be base class for this whole project
 */

class MediaWiki {

	var $GET ; # Stores the $_GET variables at time of creation, can be changed
	var $params = array();
	
	/**
	 * Constructor
	 */
	function MediaWiki () {
		$this->GET = $_GET ;
	}
	
	function setVal( $key, &$value ) {
		$this->param[strtolower( $key )] = $value;
	}
	
	function getVal( $key, $default = "" ) {
		$key = strtolower( $key );
		if( isset( $this->params[$key] ) ) {
			return $this->params[$key];
		}
		return $default;
	}
	
	/**
	 * Initialize the object to be known as $wgArticle for special cases
	 */
	function initializeSpecialCases ( &$title , &$output , $action ) {
		if ( ( $action == 'view' ) &&
			(!isset( $this->GET['title'] ) || $title->getPrefixedDBKey() != $this->GET['title'] ) &&
			!count( array_diff( array_keys( $this->GET ), array( 'action', 'title' ) ) ) )
		{
			/* Redirect to canonical url, make it a 301 to allow caching */
			$output->setSquidMaxage( 1200 );
			$output->redirect( $title->getFullURL(), '301');
		} else if ( NS_SPECIAL == $title->getNamespace() ) {
			# actions that need to be made when we have a special pages
			SpecialPage::executePath( $title );
		} else {
			/* No match to special cases */
			return false;
		}
		/* Did match a special case */
		return true;
	}

	/**
	 * Initialize the object to be known as $wgArticle for "standard" actions
	 */
	function initializeArticle( &$title, $request, $action ) {
		if( NS_MEDIA == $title->getNamespace() ) {
			$title = Title::makeTitle( NS_IMAGE, $title->getDBkey() );
		}
	
		$ns = $title->getNamespace();
	
		/* Namespace might change when using redirects */
		$article = new Article( $title );
		if( $action == 'view' && !$request->getVal( 'oldid' ) ) {
			$rTitle = Title::newFromRedirect( $article->fetchContent() );
			if( $rTitle ) {
				# Reload from the page pointed to later
				$article->mContentLoaded = false;
				$ns = $rTitle->getNamespace();
				$wasRedirected = true;
				}
		}

		/* Categories and images are handled by a different class */
		if( $ns == NS_IMAGE ) {
			$b4 = $title->getPrefixedText();
			unset( $article );
			require_once( 'includes/ImagePage.php' );
			$article = new ImagePage( $title );
			if( isset( $wasRedirected ) && $request->getVal( 'redirect' ) != 'no' ) {
				$article->mTitle = $rTitle;
				$article->mRedirectedFrom = $b4;
			}
		} elseif( $ns == NS_CATEGORY ) {
			unset( $article );
			require_once( 'includes/CategoryPage.php' );
			$article = new CategoryPage( $title );
		}
		return $article;
	}

	/**
	 * Perform one of the "standard" actions
	 */
	function performAction( $action, &$output, &$article, &$title, &$user, &$request ) {
		switch( $action ) {
			case 'view':
				$output->setSquidMaxage( $this->getVal( 'SquidMaxage' ) );
				$article->view();
				break;
			case 'watch':
			case 'unwatch':
			case 'delete':
			case 'revert':
			case 'rollback':
			case 'protect':
			case 'unprotect':
			case 'info':
			case 'markpatrolled':
			case 'validate':
			case 'render':
			case 'deletetrackback':
			case 'purge':
				$article->$action();
				break;
			case 'print':
				$article->view();
				break;
			case 'dublincore':
				if( !$this->getVal( 'EnableDublinCoreRdf' ) ) {
					wfHttpError( 403, 'Forbidden', wfMsg( 'nodublincore' ) );
				} else {
					require_once( 'includes/Metadata.php' );
					wfDublinCoreRdf( $article );
				}
				break;
			case 'creativecommons':
				if( !$this->getVal( 'EnableCreativeCommonsRdf' ) ) {
					wfHttpError( 403, 'Forbidden', wfMsg( 'nocreativecommons' ) );
				} else {
					require_once( 'includes/Metadata.php' );
					wfCreativeCommonsRdf( $article );
				}
				break;
			case 'credits':
				require_once( 'includes/Credits.php' );
				showCreditsPage( $article );
				break;
			case 'submit':
				if( !$this->getVal( 'CommandLineMode' ) && !$request->checkSessionCookie() ) {
					# Send a cookie so anons get talk message notifications
					User::SetupSession();
				}
				# Continue...
			case 'edit':
				$internal = $request->getVal( 'internaledit' );
				$external = $request->getVal( 'externaledit' );
				$section = $request->getVal( 'section' );
				$oldid = $request->getVal( 'oldid' );
				if( !$this->getVal( 'UseExternalEditor' ) || $action=='submit' || $internal ||
				   $section || $oldid ||( !$user->getOption( 'externaleditor' ) && !$external ) ) {
					require_once( 'includes/EditPage.php' );
					$editor = new EditPage( $article );
					$editor->submit();
				} elseif( $this->getVal( 'UseExternalEditor' ) && ( $external || $user->getOption( 'externaleditor' ) ) ) {
					require_once( 'includes/ExternalEdit.php' );
					$mode = $request->getVal( 'mode' );
					$extedit = new ExternalEdit( $article, $mode );
					$extedit->edit();
				}
				break;
			case 'history':
				if( $_SERVER['REQUEST_URI'] == $title->getInternalURL( 'action=history' ) ) {
					$output->setSquidMaxage( $this->getVal( 'SquidMaxage' ) );
				}
				require_once( 'includes/PageHistory.php' );
				$history = new PageHistory( $article );
				$history->history();
				break;
			case 'raw':
				require_once( 'includes/RawPage.php' );
				$raw = new RawPage( $article );
				$raw->view();
				break;
			default:
				if( wfRunHooks( 'UnknownAction', array( $action, $article ) ) ) {
					$output->errorpage( 'nosuchaction', 'nosuchactiontext' );
				}
		}
	}

}; # End of class MediaWiki

?>

