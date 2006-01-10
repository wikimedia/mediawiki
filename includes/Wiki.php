<?php

class MediaWiki {

	var $params = array () ;
	
	function setVal ( $key , &$value ) {
		$this->param[strtolower($key)] = $value ;
	}
	
	function getVal ( $key , $default = "" ) {
		$key = strtolower ( $key ) ;
		if ( isset ( $this->params[$key] ) ) {
			return $this->params[$key] ;
		}
		return $default ;
	}

	function performAction ( $action , &$output , &$article , &$title , &$user ) {
		global $wgRequest ; # Unavoidable for now
		
		switch( $action ) {
			case 'view':
				$output->setSquidMaxage( $this->getVal('SquidMaxage') );
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
				if( !$this->getVal('EnableDublinCoreRdf') ) {
					wfHttpError( 403, 'Forbidden', wfMsg( 'nodublincore' ) );
				} else {
					require_once( 'includes/Metadata.php' );
					wfDublinCoreRdf( $article );
				}
				break;
			case 'creativecommons':
				if( !$this->getVal('EnableCreativeCommonsRdf') ) {
					wfHttpError( 403, 'Forbidden', wfMsg('nocreativecommons') );
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
				if( !$this->getVal('CommandLineMode') && !$wgRequest->checkSessionCookie() ) {
					# Send a cookie so anons get talk message notifications
					User::SetupSession();
				}
				# Continue...
			case 'edit':
				$internal = $wgRequest->getVal( 'internaledit' );
				$external = $wgRequest->getVal( 'externaledit' );
				$section = $wgRequest->getVal( 'section' );
				$oldid = $wgRequest->getVal( 'oldid' );
				if(!$this->getVal('UseExternalEditor') || $action=='submit' || $internal ||
				   $section || $oldid || (!$user->getOption('externaleditor') && !$external)) {
					require_once( 'includes/EditPage.php' );
					$editor = new EditPage( $article );
					$editor->submit();
				} elseif($this->getVal('UseExternalEditor') && ($external || $user->getOption('externaleditor'))) {
					require_once( 'includes/ExternalEdit.php' );
					$mode = $wgRequest->getVal( 'mode' );
					$extedit = new ExternalEdit( $article, $mode );
					$extedit->edit();
				}
				break;
			case 'history':
				if ($_SERVER['REQUEST_URI'] == $title->getInternalURL('action=history')) {
					$output->setSquidMaxage( $this->getVal('SquidMaxage') );
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
				if (wfRunHooks('UnknownAction', array($action, $article))) {
					$output->errorpage( 'nosuchaction', 'nosuchactiontext' );
				}
		}
	}

} ; # End of class MediaWiki

?>

