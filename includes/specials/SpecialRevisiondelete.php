<?php
/**
 * Special page allowing users with the appropriate permissions to view
 * and hide revisions. Log items can also be hidden.
 *
 * @file
 * @ingroup SpecialPage
 */

class SpecialRevisionDelete extends UnlistedSpecialPage {
	/** Skin object */
	var $skin;

	/** True if the submit button was clicked, and the form was posted */
	var $submitClicked;

	/** Target ID list */
	var $ids;

	/** Archive name, for reviewing deleted files */
	var $archiveName;

	/** Edit token for securing image views against XSS */
	var $token;

	/** Title object for target parameter */
	var $targetObj;

	/** Deletion type, may be revision, archive, oldimage, filearchive, logging. */
	var $typeName;

	/** Array of checkbox specs (message, name, deletion bits) */
	var $checks;

	/** Information about the current type */
	var $typeInfo;

	/** The RevDel_List object, storing the list of items to be deleted/undeleted */
	var $list;

	/**
	 * Assorted information about each type, needed by the special page.
	 * TODO Move some of this to the list class
	 */
	static $allowedTypes = array(
		'revision' => array(
			'check-label' => 'revdelete-hide-text',
			'deletion-bits' => Revision::DELETED_TEXT,
			'success' => 'revdelete-success',
			'failure' => 'revdelete-failure',
			'list-class' => 'RevDel_RevisionList',
		),
		'archive' => array(
			'check-label' => 'revdelete-hide-text',
			'deletion-bits' => Revision::DELETED_TEXT,
			'success' => 'revdelete-success',
			'failure' => 'revdelete-failure',
			'list-class' => 'RevDel_ArchiveList',
		),
		'oldimage'=> array(
			'check-label' => 'revdelete-hide-image',
			'deletion-bits' => File::DELETED_FILE,
			'success' => 'revdelete-success',
			'failure' => 'revdelete-failure',
			'list-class' => 'RevDel_FileList',
		),
		'filearchive' => array(
			'check-label' => 'revdelete-hide-image',
			'deletion-bits' => File::DELETED_FILE,
			'success' => 'revdelete-success',
			'failure' => 'revdelete-failure',
			'list-class' => 'RevDel_ArchivedFileList',
		),
		'logging' => array(
			'check-label' => 'revdelete-hide-name',
			'deletion-bits' => LogPage::DELETED_ACTION,
			'success' => 'logdelete-success',
			'failure' => 'logdelete-failure',
			'list-class' => 'RevDel_LogList',
		),
	);

	/** Type map to support old log entries */
	static $deprecatedTypeMap = array(
		'oldid' => 'revision',
		'artimestamp' => 'archive',
		'oldimage' => 'oldimage',
		'fileid' => 'filearchive',
		'logid' => 'logging',
	);

	public function __construct() {
		parent::__construct( 'Revisiondelete', 'deletedhistory' );
	}

	public function execute( $par ) {
		global $wgOut, $wgUser, $wgRequest;
		if( !$wgUser->isAllowed( 'deletedhistory' ) ) {
			$wgOut->permissionRequired( 'deletedhistory' );
			return;
		} else if( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		$this->mIsAllowed = $wgUser->isAllowed('deleterevision'); // for changes
		$this->skin = $wgUser->getSkin();
		$this->setHeaders();
		$this->outputHeader();
		$this->submitClicked = $wgRequest->wasPosted() && $wgRequest->getBool( 'wpSubmit' );
		# Handle our many different possible input types.
		$ids = $wgRequest->getVal( 'ids' );
		if ( !is_null( $ids ) ) {
			# Allow CSV, for backwards compatibility, or a single ID for show/hide links
			$this->ids = explode( ',', $ids );
		} else {
			# Array input
			$this->ids = array_keys( $wgRequest->getArray('ids',array()) );
		}
		// $this->ids = array_map( 'intval', $this->ids );
		$this->ids = array_unique( array_filter( $this->ids ) );

		if ( $wgRequest->getVal( 'action' ) == 'historysubmit' ) {
			# For show/hide form submission from history page
			$this->targetObj = $GLOBALS['wgTitle'];
			$this->typeName = 'revision';
		} else {
			$this->typeName = $wgRequest->getVal( 'type' );
			$this->targetObj = Title::newFromText( $wgRequest->getText( 'target' ) );
		}

		# For reviewing deleted files...
		$this->archiveName = $wgRequest->getVal( 'file' );
		$this->token = $wgRequest->getVal( 'token' );
		if ( $this->archiveName && $this->targetObj ) {
			$this->tryShowFile( $this->archiveName );
			return;
		}

		if ( isset( self::$deprecatedTypeMap[$this->typeName] ) ) {
			$this->typeName = self::$deprecatedTypeMap[$this->typeName];
		}

		# No targets?
		if( !isset( self::$allowedTypes[$this->typeName] ) || count( $this->ids ) == 0 ) {
			$wgOut->showErrorPage( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
			return;
		}
		$this->typeInfo = self::$allowedTypes[$this->typeName];

		# If we have revisions, get the title from the first one
		# since they should all be from the same page. This allows 
		# for more flexibility with page moves...
		if( $this->typeName == 'revision' ) {
			$rev = Revision::newFromId( $this->ids[0] );
			$this->targetObj = $rev ? $rev->getTitle() : $this->targetObj;
		}
		
		$this->otherReason = $wgRequest->getVal( 'wpReason' );
		# We need a target page!
		if( is_null($this->targetObj) ) {
			$wgOut->addWikiMsg( 'undelete-header' );
			return;
		}
		# Give a link to the logs/hist for this page
		$this->showConvenienceLinks();

		# Initialise checkboxes
		$this->checks = array(
			array( $this->typeInfo['check-label'], 'wpHidePrimary', $this->typeInfo['deletion-bits'] ),
			array( 'revdelete-hide-comment', 'wpHideComment', Revision::DELETED_COMMENT ),
			array( 'revdelete-hide-user', 'wpHideUser', Revision::DELETED_USER )
		);
		if( $wgUser->isAllowed('suppressrevision') ) {
			$this->checks[] = array( 'revdelete-hide-restricted',
				'wpHideRestricted', Revision::DELETED_RESTRICTED );
		}

		# Either submit or create our form
		if( $this->mIsAllowed && $this->submitClicked ) {
			$this->submit( $wgRequest );
		} else {
			$this->showForm();
		}
		
		$qc = $this->getLogQueryCond();
		# Show relevant lines from the deletion log
		$wgOut->addHTML( "<h2>" . htmlspecialchars( LogPage::logName( 'delete' ) ) . "</h2>\n" );
		LogEventsList::showLogExtract( $wgOut, 'delete',
			$this->targetObj->getPrefixedText(), '', array( 'lim' => 25, 'conds' => $qc ) );
		# Show relevant lines from the suppression log
		if( $wgUser->isAllowed( 'suppressionlog' ) ) {
			$wgOut->addHTML( "<h2>" . htmlspecialchars( LogPage::logName( 'suppress' ) ) . "</h2>\n" );
			LogEventsList::showLogExtract( $wgOut, 'suppress',
				$this->targetObj->getPrefixedText(), '', array( 'lim' => 25, 'conds' => $qc ) );
		}
	}

	/**
	 * Show some useful links in the subtitle
	 */
	protected function showConvenienceLinks() {
		global $wgOut, $wgUser, $wgLang;
		# Give a link to the logs/hist for this page
		if( $this->targetObj ) {
			$links = array();
			$links[] = $this->skin->linkKnown(
				SpecialPage::getTitleFor( 'Log' ),
				wfMsgHtml( 'viewpagelogs' ),
				array(),
				array( 'page' => $this->targetObj->getPrefixedText() )
			);
			if ( $this->targetObj->getNamespace() != NS_SPECIAL ) {
				# Give a link to the page history
				$links[] = $this->skin->linkKnown(
					$this->targetObj,
					wfMsgHtml( 'pagehist' ),
					array(),
					array( 'action' => 'history' )
				);
				# Link to deleted edits
				if( $wgUser->isAllowed('undelete') ) {
					$undelete = SpecialPage::getTitleFor( 'Undelete' );
					$links[] = $this->skin->linkKnown(
						$undelete,
						wfMsgHtml( 'deletedhist' ),
						array(),
						array( 'target' => $this->targetObj->getPrefixedDBkey() )
					);
				}
			}
			# Logs themselves don't have histories or archived revisions
			$wgOut->setSubtitle( '<p>' . $wgLang->pipeList( $links ) . '</p>' );
		}
	}

	/**
	 * Get the condition used for fetching log snippets
	 */
	protected function getLogQueryCond() {
		$conds = array();
		// Revision delete logs for these item
		$conds['log_type'] = array('delete','suppress');
		$conds['log_action'] = $this->getList()->getLogAction();
		$conds['ls_field'] = RevisionDeleter::getRelationType( $this->typeName );
		$conds['ls_value'] = $this->ids;
		return $conds;
	}

	/**
	 * Show a deleted file version requested by the visitor.
	 * TODO Mostly copied from Special:Undelete. Refactor.
	 */
	protected function tryShowFile( $archiveName ) {
		global $wgOut, $wgRequest, $wgUser, $wgLang;

		$repo = RepoGroup::singleton()->getLocalRepo();
		$oimage = $repo->newFromArchiveName( $this->targetObj, $archiveName );
		$oimage->load();
		// Check if user is allowed to see this file
		if ( !$oimage->exists() ) {
			$wgOut->addWikiMsg( 'revdelete-no-file' );
			return;
		}
		if( !$oimage->userCan(File::DELETED_FILE) ) {
			if( $oimage->isDeleted( File::DELETED_RESTRICTED ) ) {
				$wgOut->permissionRequired( 'suppressrevision' );
			} else {
				$wgOut->permissionRequired( 'deletedtext' );
			}
			return;
		}
		if ( !$wgUser->matchEditToken( $this->token, $archiveName ) ) {
			$wgOut->addWikiMsg( 'revdelete-show-file-confirm',
				$this->targetObj->getText(),
				$wgLang->date( $oimage->getTimestamp() ),
				$wgLang->time( $oimage->getTimestamp() ) );
			$wgOut->addHTML(
				Xml::openElement( 'form', array(
					'method' => 'POST',
					'action' => $this->getTitle()->getLocalUrl(
						'target=' . urlencode( $oimage->getName() ) .
						'&file=' . urlencode( $archiveName ) .
						'&token=' . urlencode( $wgUser->editToken( $archiveName ) ) )
					)
				) .
				Xml::submitButton( wfMsg( 'revdelete-show-file-submit' ) ) .
				'</form>'
			);
			return;
		}
		$wgOut->disable();
		# We mustn't allow the output to be Squid cached, otherwise
		# if an admin previews a deleted image, and it's cached, then
		# a user without appropriate permissions can toddle off and
		# nab the image, and Squid will serve it
		$wgRequest->response()->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
		$wgRequest->response()->header( 'Cache-Control: no-cache, no-store, max-age=0, must-revalidate' );
		$wgRequest->response()->header( 'Pragma: no-cache' );

		# Stream the file to the client
		global $IP;
		require_once( "$IP/includes/StreamFile.php" );
		$key = $oimage->getStorageKey();
		$path = $repo->getZonePath( 'deleted' ) . '/' . $repo->getDeletedHashPath( $key ) . $key;
		wfStreamFile( $path );
	}

	/**
	 * Get the list object for this request
	 */
	protected function getList() {
		if ( is_null( $this->list ) ) {
			$class = $this->typeInfo['list-class'];
			$this->list = new $class( $this, $this->targetObj, $this->ids );
		}
		return $this->list;
	}

	/**
	 * Show a list of items that we will operate on, and show a form with checkboxes 
	 * which will allow the user to choose new visibility settings.
	 */
	protected function showForm() {
		global $wgOut, $wgUser, $wgLang;
		$UserAllowed = true;

		if ( $this->typeName == 'logging' ) {
			$wgOut->addWikiMsg( 'logdelete-selected', $wgLang->formatNum( count($this->ids) ) );
		} else {
			$wgOut->addWikiMsg( 'revdelete-selected',
				$this->targetObj->getPrefixedText(), count( $this->ids ) );
		}

		$wgOut->addHTML( "<ul>" );

		$where = $revObjs = array();
		
		$numRevisions = 0;
		// Live revisions...
		$list = $this->getList();
		for ( $list->reset(); $list->current(); $list->next() ) {
			$item = $list->current();
			if ( !$item->canView() ) {
				if( !$this->submitClicked ) {
					$wgOut->permissionRequired( 'suppressrevision' );
					return;
				}
				$UserAllowed = false;
			}
			$numRevisions++;
			$wgOut->addHTML( $item->getHTML() );
		}

		if( !$numRevisions ) {
			$wgOut->showErrorPage( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
			return;
		}
		
		$wgOut->addHTML( "</ul>" );
		// Explanation text
		$this->addUsageText();

		// Normal sysops can always see what they did, but can't always change it
		if( !$UserAllowed ) return;

		// Show form if the user can submit
		if( $this->mIsAllowed ) {
			$out = Xml::openElement( 'form', array( 'method' => 'post',
					'action' => $this->getTitle()->getLocalUrl( array( 'action' => 'submit' ) ), 
					'id' => 'mw-revdel-form-revisions' ) ) .
				Xml::fieldset( wfMsg( 'revdelete-legend' ) ) .
				$this->buildCheckBoxes() .
				Xml::openElement( 'table' ) .
				"<tr>\n" .
					'<td class="mw-label">' .
						Xml::label( wfMsg( 'revdelete-log' ), 'wpRevDeleteReasonList' ) .
					'</td>' .
					'<td class="mw-input">' .
						Xml::listDropDown( 'wpRevDeleteReasonList',
							wfMsgForContent( 'revdelete-reason-dropdown' ),
							wfMsgForContent( 'revdelete-reasonotherlist' ), '', 'wpReasonDropDown', 1
						) .
					'</td>' .
				"</tr><tr>\n" .
					'<td class="mw-label">' .
						Xml::label( wfMsg( 'revdelete-otherreason' ), 'wpReason' ) .
					'</td>' .
					'<td class="mw-input">' .
						Xml::input( 'wpReason', 60, $this->otherReason, array( 'id' => 'wpReason' ) ) .
					'</td>' .
				"</tr><tr>\n" .
					'<td></td>' .
					'<td class="mw-submit">' .
						Xml::submitButton( wfMsgExt('revdelete-submit','parsemag',$numRevisions),
							array( 'name' => 'wpSubmit' ) ) .
					'</td>' .
				"</tr>\n" .
				Xml::closeElement( 'table' ) .
				Xml::hidden( 'wpEditToken', $wgUser->editToken() ) .
				Xml::hidden( 'target', $this->targetObj->getPrefixedText() ) .
				Xml::hidden( 'type', $this->typeName ) .
				Xml::hidden( 'ids', implode( ',', $this->ids ) ) .
				Xml::closeElement( 'fieldset' ) . "\n";
		} else {
			$out = '';
		}
		if( $this->mIsAllowed ) {
			$out .= Xml::closeElement( 'form' ) . "\n";
			// Show link to edit the dropdown reasons
			if( $wgUser->isAllowed( 'editinterface' ) ) {
				$title = Title::makeTitle( NS_MEDIAWIKI, 'revdelete-reason-dropdown' );
				$link = $wgUser->getSkin()->link(
					$title,
					wfMsgHtml( 'revdelete-edit-reasonlist' ),
					array(),
					array( 'action' => 'edit' )
				);
				$out .= Xml::tags( 'p', array( 'class' => 'mw-revdel-editreasons' ), $link ) . "\n";
			}
		}
		$wgOut->addHTML( $out );
	}

	/**
	 * Show some introductory text
	 * FIXME Wikimedia-specific policy text
	 */
	protected function addUsageText() {
		global $wgOut, $wgUser;
		$wgOut->addWikiMsg( 'revdelete-text' );
		if( $wgUser->isAllowed( 'suppressrevision' ) ) {
			$wgOut->addWikiMsg( 'revdelete-suppress-text' );
		}
		if( $this->mIsAllowed ) {
			$wgOut->addWikiMsg( 'revdelete-confirm' );
		}
	}
	
	/**
	* @return String: HTML
	*/
	protected function buildCheckBoxes() {
		global $wgRequest;

		$html = '<table>';
		// If there is just one item, use checkboxes
		$list = $this->getList();
		if( $list->length() == 1 ) {
			$list->reset();
			$bitfield = $list->current()->getBits(); // existing field
			if( $this->submitClicked ) {
				$bitfield = $this->extractBitfield( $this->extractBitParams($wgRequest), $bitfield );
			}
			foreach( $this->checks as $item ) {
				list( $message, $name, $field ) = $item;
				$innerHTML = Xml::checkLabel( wfMsg($message), $name, $name, $bitfield & $field );
				if( $field == Revision::DELETED_RESTRICTED )
					$innerHTML = "<b>$innerHTML</b>";
				$line = Xml::tags( 'td', array( 'class' => 'mw-input' ), $innerHTML );
				$html .= "<tr>$line</tr>\n";
			}
		// Otherwise, use tri-state radios
		} else {
			$html .= '<tr>';
			$html .= '<th class="mw-revdel-checkbox">'.wfMsgHtml('revdelete-radio-same').'</th>';
			$html .= '<th class="mw-revdel-checkbox">'.wfMsgHtml('revdelete-radio-unset').'</th>';
			$html .= '<th class="mw-revdel-checkbox">'.wfMsgHtml('revdelete-radio-set').'</th>';
			$html .= "<th></th></tr>\n";
			foreach( $this->checks as $item ) {
				list( $message, $name, $field ) = $item;
				// If there are several items, use third state by default...
				if( $this->submitClicked ) {
					$selected = $wgRequest->getInt( $name, 0 /* unchecked */ );
				} else {
					$selected = -1; // use existing field
				}
				$line = '<td class="mw-revdel-checkbox">' . Xml::radio( $name, -1, $selected == -1 ) . '</td>';
				$line .= '<td class="mw-revdel-checkbox">' . Xml::radio( $name, 0, $selected == 0 ) . '</td>';
				$line .= '<td class="mw-revdel-checkbox">' . Xml::radio( $name, 1, $selected == 1 ) . '</td>';
				$label = wfMsgHtml($message);
				if( $field == Revision::DELETED_RESTRICTED ) {
					$label = "<b>$label</b>";
				}
				$line .= "<td>$label</td>";
				$html .= "<tr>$line</tr>\n";
			}
		}
		
		$html .= '</table>';
		return $html;
	}

	/**
	 * UI entry point for form submission.
	 * @param $request WebRequest
	 */
	protected function submit( $request ) {
		global $wgUser, $wgOut;
		# Check edit token on submission
		if( $this->submitClicked && !$wgUser->matchEditToken( $request->getVal('wpEditToken') ) ) {
			$wgOut->addWikiMsg( 'sessionfailure' );
			return false;
		}
		$bitParams = $this->extractBitParams( $request );
		$listReason = $request->getText( 'wpRevDeleteReasonList', 'other' ); // from dropdown
		$comment = $listReason;
		if( $comment != 'other' && $this->otherReason != '' ) {
			// Entry from drop down menu + additional comment
			$comment .= wfMsgForContent( 'colon-separator' ) . $this->otherReason;
		} elseif( $comment == 'other' ) {
			$comment = $this->otherReason;
		}
		# Can the user set this field?
		if( $bitParams[Revision::DELETED_RESTRICTED]==1 && !$wgUser->isAllowed('suppressrevision') ) {
			$wgOut->permissionRequired( 'suppressrevision' );
			return false;
		}
		# If the save went through, go to success message...
		$status = $this->save( $bitParams, $comment, $this->targetObj );
		if ( $status->isGood() ) {
			$this->success();
			return true;
		# ...otherwise, bounce back to form...
		} else {
			$this->failure( $status );
		}
		return false;
	}

	/**
	 * Report that the submit operation succeeded
	 */
	protected function success() {
		global $wgOut;
		$wgOut->setPagetitle( wfMsg( 'actioncomplete' ) );
		$wgOut->wrapWikiMsg( '<span class="success">$1</span>', $this->typeInfo['success'] );
		$this->list->reloadFromMaster();
		$this->showForm();
	}

	/**
	 * Report that the submit operation failed
	 */
	protected function failure( $status ) {
		global $wgOut;
		$wgOut->setPagetitle( wfMsg( 'actionfailed' ) );
		$wgOut->addWikiText( $status->getWikiText( $this->typeInfo['failure'] ) );
		$this->showForm();
	}

	/**
	 * Put together an array that contains -1, 0, or the *_deleted const for each bit
	 * @param $request WebRequest
	 * @return array
	 */
	protected function extractBitParams( $request ) {
		$bitfield = array();
		foreach( $this->checks as $item ) {
			list( /* message */ , $name, $field ) = $item;
			$val = $request->getInt( $name, 0 /* unchecked */ );
			if( $val < -1 || $val > 1) {
				$val = -1; // -1 for existing value
			}
			$bitfield[$field] = $val;
		}
		if( !isset($bitfield[Revision::DELETED_RESTRICTED]) ) {
			$bitfield[Revision::DELETED_RESTRICTED] = 0;
		}
		return $bitfield;
	}
	
	/**
	 * Put together a rev_deleted bitfield
	 * @param $bitPars array extractBitParams() params
	 * @param $oldfield int current bitfield
	 * @return array
	 */
	public static function extractBitfield( $bitPars, $oldfield ) {
		// Build the actual new rev_deleted bitfield
		$newBits = 0;
		foreach( $bitPars as $const => $val ) {
			if( $val == 1 ) {
				$newBits |= $const; // $const is the *_deleted const
			} else if( $val == -1 ) {
				$newBits |= ($oldfield & $const); // use existing
			}
		}
		return $newBits;
	}

	/**
	 * Do the write operations. Simple wrapper for RevDel_*List::setVisibility().
	 */
	protected function save( $bitfield, $reason, $title ) {
		return $this->getList()->setVisibility(
			array( 'value' => $bitfield, 'comment' => $reason )
		);
	}
}

/**
 * Temporary b/c interface, collection of static functions.
 * @ingroup SpecialPage
 */
class RevisionDeleter {
	/**
	 * Checks for a change in the bitfield for a certain option and updates the
	 * provided array accordingly.
	 *
	 * @param $desc String: description to add to the array if the option was
	 * enabled / disabled.
	 * @param $field Integer: the bitmask describing the single option.
	 * @param $diff Integer: the xor of the old and new bitfields.
	 * @param $new Integer: the new bitfield 
	 * @param $arr Array: the array to update.
	 */
	protected static function checkItem( $desc, $field, $diff, $new, &$arr ) {
		if( $diff & $field ) {
			$arr[ ( $new & $field ) ? 0 : 1 ][] = $desc;
		}
	}

	/**
	 * Gets an array describing the changes made to the visibilit of the revision.
	 * If the resulting array is $arr, then $arr[0] will contain an array of strings
	 * describing the items that were hidden, $arr[2] will contain an array of strings
	 * describing the items that were unhidden, and $arr[3] will contain an array with
	 * a single string, which can be one of "applied restrictions to sysops",
	 * "removed restrictions from sysops", or null.
	 *
	 * @param $n Integer: the new bitfield.
	 * @param $o Integer: the old bitfield.
	 * @return An array as described above.
	 */
	protected static function getChanges( $n, $o ) {
		$diff = $n ^ $o;
		$ret = array( 0 => array(), 1 => array(), 2 => array() );
		// Build bitfield changes in language
		self::checkItem( wfMsgForContent( 'revdelete-content' ),
			Revision::DELETED_TEXT, $diff, $n, $ret );
		self::checkItem( wfMsgForContent( 'revdelete-summary' ),
			Revision::DELETED_COMMENT, $diff, $n, $ret );
		self::checkItem( wfMsgForContent( 'revdelete-uname' ),
			Revision::DELETED_USER, $diff, $n, $ret );
		// Restriction application to sysops
		if( $diff & Revision::DELETED_RESTRICTED ) {
			if( $n & Revision::DELETED_RESTRICTED )
				$ret[2][] = wfMsgForContent( 'revdelete-restricted' );
			else
				$ret[2][] = wfMsgForContent( 'revdelete-unrestricted' );
		}
		return $ret;
	}

	/**
	 * Gets a log message to describe the given revision visibility change. This
	 * message will be of the form "[hid {content, edit summary, username}];
	 * [unhid {...}][applied restrictions to sysops] for $count revisions: $comment".
	 *
	 * @param $count Integer: The number of effected revisions.
	 * @param $nbitfield Integer: The new bitfield for the revision.
	 * @param $obitfield Integer: The old bitfield for the revision.
	 * @param $isForLog Boolean
	 */
	public static function getLogMessage( $count, $nbitfield, $obitfield, $isForLog = false ) {
		global $wgLang;
		$s = '';
		$changes = self::getChanges( $nbitfield, $obitfield );
		if( count( $changes[0] ) ) {
			$s .= wfMsgForContent( 'revdelete-hid', implode( ', ', $changes[0] ) );
		}
		if( count( $changes[1] ) ) {
			if ($s) $s .= '; ';
			$s .= wfMsgForContent( 'revdelete-unhid', implode( ', ', $changes[1] ) );
		}
		if( count( $changes[2] ) ) {
			$s .= $s ? ' (' . $changes[2][0] . ')' : $changes[2][0];
		}
		$msg = $isForLog ? 'logdelete-log-message' : 'revdelete-log-message';
		return wfMsgExt( $msg, array( 'parsemag', 'content' ), $s, $wgLang->formatNum($count) );

	}
	
	// Get DB field name for URL param...
	// Future code for other things may also track
	// other types of revision-specific changes.
	// @returns string One of log_id/rev_id/fa_id/ar_timestamp/oi_archive_name
	public static function getRelationType( $typeName ) {
		if ( isset( SpecialRevisionDelete::$deprecatedTypeMap[$typeName] ) ) {
			$typeName = SpecialRevisionDelete::$deprecatedTypeMap[$typeName];
		}
		if ( isset( SpecialRevisionDelete::$allowedTypes[$typeName] ) ) {
			$class = SpecialRevisionDelete::$allowedTypes[$typeName]['list-class'];
			$list = new $class( null, null, null );
			return $list->getIdField();
		} else {
			return null;
		}
	}
}

/**
 * Abstract base class for a list of deletable items
 */
abstract class RevDel_List {
	var $special, $title, $ids, $res, $current;
	var $type = null; // override this
	var $idField = null; // override this
	var $dateField = false; // override this
	var $authorIdField = false; // override this
	var $authorNameField = false; // override this

	/**
	 * @param $special The parent SpecialPage
	 * @param $title The target title
	 * @param $ids Array of IDs
	 */
	public function __construct( $special, $title, $ids ) {
		$this->special = $special;
		$this->title = $title;
		$this->ids = $ids;
	}

	/**
	 * Get the internal type name of this list. Equal to the table name.
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Get the DB field name associated with the ID list
	 */
	public function getIdField() {
		return $this->idField;
	}

	/**
	 * Get the DB field name storing timestamps
	 */
	public function getTimestampField() {
		return $this->dateField;
	}

	/**
	 * Get the DB field name storing user ids
	 */
	public function getAuthorIdField() {
		return $this->authorIdField;
	}

	/**
	 * Get the DB field name storing user names
	 */
	public function getAuthorNameField() {
		return $this->authorNameField;
	}
	/**
	 * Set the visibility for the revisions in this list. Logging and 
	 * transactions are done here.
	 *
	 * @param $params Associative array of parameters. Members are:
	 *     value:       The integer value to set the visibility to
	 *     comment:     The log comment.
	 * @return Status
	 */
	public function setVisibility( $params ) {
		$bitPars = $params['value'];
		$comment = $params['comment'];

		$this->res = false;
		$dbw = wfGetDB( DB_MASTER );
		$this->doQuery( $dbw );
		$dbw->begin();
		$status = Status::newGood();
		$missing = array_flip( $this->ids );
		$this->clearFileOps();
		$idsForLog = array();
		$authorIds = $authorIPs = array();

		for ( $this->reset(); $this->current(); $this->next() ) {
			$item = $this->current();
			unset( $missing[ $item->getId() ] );

			$oldBits = $item->getBits();
			// Build the actual new rev_deleted bitfield
			$newBits = SpecialRevisionDelete::extractBitfield( $bitPars, $oldBits );

			if ( $oldBits == $newBits ) {
				$status->warning( 'revdelete-no-change', $item->formatDate(), $item->formatTime() );
				$status->failCount++;
				continue;
			} elseif ( $oldBits == 0 && $newBits != 0 ) {
				$opType = 'hide';
			} elseif ( $oldBits != 0 && $newBits == 0 ) {
				$opType = 'show';
			} else {
				$opType = 'modify';
			}

			if ( $item->isHideCurrentOp( $newBits ) ) {
				// Cannot hide current version text
				$status->error( 'revdelete-hide-current', $item->formatDate(), $item->formatTime() );
				$status->failCount++;
				continue;
			}
			if ( !$item->canView() ) {
				// Cannot access this revision
				$msg = ($opType == 'show') ?
					'revdelete-show-no-access' : 'revdelete-modify-no-access';
				$status->error( $msg, $item->formatDate(), $item->formatTime() );
				$status->failCount++;
				continue;
			}
			// Cannot just "hide from Sysops" without hiding any fields
			if( $newBits == Revision::DELETED_RESTRICTED ) {
				$status->warning( 'revdelete-only-restricted', $item->formatDate(), $item->formatTime() );
				$status->failCount++;
				continue;
			} 

			// Update the revision
			$ok = $item->setBits( $newBits );

			if ( $ok ) {
				$idsForLog[] = $item->getId();
				$status->successCount++;
				if( $item->getAuthorId() > 0 ) {
					$authorIds[] = $item->getAuthorId();
				} else if( IP::isIPAddress( $item->getAuthorName() ) ) {
					$authorIPs[] = $item->getAuthorName();
				}
			} else {
				$status->error( 'revdelete-concurrent-change', $item->formatDate(), $item->formatTime() );
				$status->failCount++;
			}
		}

		// Handle missing revisions
		foreach ( $missing as $id => $unused ) {
			$status->error( 'revdelete-modify-missing', $id );
			$status->failCount++;
		}

		if ( $status->successCount == 0 ) {
			$status->ok = false;
			$dbw->rollback();
			return $status;
		}

		// Save success count 
		$successCount = $status->successCount;

		// Move files, if there are any
		$status->merge( $this->doPreCommitUpdates() );
		if ( !$status->isOK() ) {
			// Fatal error, such as no configured archive directory
			$dbw->rollback();
			return $status;
		}

		// Log it
		$this->updateLog( array(
			'title' => $this->title, 
			'count' => $successCount, 
			'newBits' => $newBits, 
			'oldBits' => $oldBits,
			'comment' => $comment,
			'ids' => $idsForLog,
			'authorIds' => $authorIds,
			'authorIPs' => $authorIPs
		) );
		$dbw->commit();

		// Clear caches
		$status->merge( $this->doPostCommitUpdates() );
		return $status;
	}

	/**
	 * Reload the list data from the master DB. This can be done after setVisibility()
	 * to allow $item->getHTML() to show the new data.
	 */
	function reloadFromMaster() {
		$dbw = wfGetDB( DB_MASTER );
		$this->res = $this->doQuery( $dbw );
	}

	/**
	 * Record a log entry on the action
	 * @param $params Associative array of parameters:
	 *     newBits:         The new value of the *_deleted bitfield
	 *     oldBits:         The old value of the *_deleted bitfield. 
	 *     title:           The target title
	 *     ids:             The ID list
	 *     comment:         The log comment
	 *     authorsIds:      The array of the user IDs of the offenders
	 *     authorsIPs:      The array of the IP/anon user offenders
	 */
	protected function updateLog( $params ) {
		// Get the URL param's corresponding DB field
		$field = RevisionDeleter::getRelationType( $this->getType() );
		if( !$field ) {
			throw new MWException( "Bad log URL param type!" );
		}
		// Put things hidden from sysops in the oversight log
		if ( ( $params['newBits'] | $params['oldBits'] ) & $this->getSuppressBit() ) {
			$logType = 'suppress';
		} else {
			$logType = 'delete';
		}
		// Add params for effected page and ids
		$logParams = $this->getLogParams( $params );
		// Actually add the deletion log entry
		$log = new LogPage( $logType );
		$logid = $log->addEntry( $this->getLogAction(), $params['title'],
			$params['comment'], $logParams );
		// Allow for easy searching of deletion log items for revision/log items
		$log->addRelations( $field, $params['ids'], $logid );
		$log->addRelations( 'target_author_id', $params['authorIds'], $logid );
		$log->addRelations( 'target_author_ip', $params['authorIPs'], $logid );
	}

	/**
	 * Get the log action for this list type
	 */
	public function getLogAction() {
		return 'revision';
	}

	/**
	 * Get log parameter array.
	 * @param $params Associative array of log parameters, same as updateLog()
	 * @return array
	 */
	public function getLogParams( $params ) {
		return array(
			$this->getType(),
			implode( ',', $params['ids'] ),
			"ofield={$params['oldBits']}",
			"nfield={$params['newBits']}"
		);
	}

	/**
	 * Initialise the current iteration pointer
	 */
	protected function initCurrent() {
		$row = $this->res->current();
		if ( $row ) {
			$this->current = $this->newItem( $row );
		} else {
			$this->current = false;
		}
	}

	/**
	 * Start iteration. This must be called before current() or next(). 
	 * @return First list item
	 */
	public function reset() {
		if ( !$this->res ) {
			$this->res = $this->doQuery( wfGetDB( DB_SLAVE ) );
		} else {
			$this->res->rewind();
		}
		$this->initCurrent();
		return $this->current;
	}

	/**
	 * Get the current list item, or false if we are at the end
	 */
	public function current() {
		return $this->current;
	}

	/**
	 * Move the iteration pointer to the next list item, and return it.
	 */
	public function next() {
		$this->res->next();
		$this->initCurrent();
		return $this->current;
	}
	
	/**
	 * Get the number of items in the list.
	 */
	public function length() {
		if( !$this->res ) {
			return 0;
		} else {
			return $this->res->numRows();
		}
	}

	/**
	 * Clear any data structures needed for doPreCommitUpdates() and doPostCommitUpdates()
	 * STUB
	 */
	public function clearFileOps() {
	}

	/** 
	 * A hook for setVisibility(): do batch updates pre-commit.
	 * STUB
	 * @return Status
	 */
	public function doPreCommitUpdates() {
		return Status::newGood();
	}

	/**
	 * A hook for setVisibility(): do any necessary updates post-commit. 
	 * STUB
	 * @return Status 
	 */
	public function doPostCommitUpdates() {
		return Status::newGood();
	}

	/**
	 * Create an item object from a DB result row
	 * @param $row stdclass
	 */
	abstract public function newItem( $row );

	/**
	 * Do the DB query to iterate through the objects.
	 * @param $db Database object to use for the query
	 */
	abstract public function doQuery( $db );

	/**
	 * Get the integer value of the flag used for suppression
	 */
	abstract public function getSuppressBit();
}

/**
 * Abstract base class for deletable items
 */
abstract class RevDel_Item {
	/** The parent SpecialPage */
	var $special;

	/** The parent RevDel_List */
	var $list;

	/** The DB result row */
	var $row;

	/** 
	 * @param $list RevDel_List
	 * @param $row DB result row
	 */
	public function __construct( $list, $row ) {
		$this->special = $list->special;
		$this->list = $list;
		$this->row = $row;
	}

	/**
	 * Get the ID, as it would appear in the ids URL parameter
	 */
	public function getId() {
		$field = $this->list->getIdField();
		return $this->row->$field;
	}

	/**
	 * Get the date, formatted with $wgLang
	 */
	public function formatDate() {
		global $wgLang;
		return $wgLang->date( $this->getTimestamp() );
	}

	/**
	 * Get the time, formatted with $wgLang
	 */
	public function formatTime() {
		global $wgLang;
		return $wgLang->time( $this->getTimestamp() );
	}

	/**
	 * Get the timestamp in MW 14-char form
	 */
	public function getTimestamp() {
		$field = $this->list->getTimestampField();
		return wfTimestamp( TS_MW, $this->row->$field );
	}
	
	/**
	 * Get the author user ID
	 */	
	public function getAuthorId() {
		$field = $this->list->getAuthorIdField();
		return intval( $this->row->$field );
	}

	/**
	 * Get the author user name
	 */	
	public function getAuthorName() {
		$field = $this->list->getAuthorNameField();
		return strval( $this->row->$field );
	}

	/** 
	 * Returns true if the item is "current", and the operation to set the given
	 * bits can't be executed for that reason
	 * STUB
	 */
	public function isHideCurrentOp( $newBits ) {
		return false;
	}

	/**
	 * Returns true if the current user can view the item
	 */
	abstract public function canView();
	
	/**
	 * Returns true if the current user can view the item text/file
	 */
	abstract public function canViewContent();

	/**
	 * Get the current deletion bitfield value
	 */
	abstract public function getBits();

	/**
	 * Get the HTML of the list item. Should be include <li></li> tags.
	 * This is used to show the list in HTML form, by the special page.
	 */
	abstract public function getHTML();

	/**
	 * Set the visibility of the item. This should do any necessary DB queries.
	 *
	 * The DB update query should have a condition which forces it to only update
	 * if the value in the DB matches the value fetched earlier with the SELECT. 
	 * If the update fails because it did not match, the function should return 
	 * false. This prevents concurrency problems.
	 *
	 * @return boolean success
	 */
	abstract public function setBits( $newBits );
}

/**
 * List for revision table items
 */
class RevDel_RevisionList extends RevDel_List {
	var $currentRevId;
	var $type = 'revision';
	var $idField = 'rev_id';
	var $dateField = 'rev_timestamp';
	var $authorIdField = 'rev_user';
	var $authorNameField = 'rev_user_text';

	public function doQuery( $db ) {
		$ids = array_map( 'intval', $this->ids );
		return $db->select( array('revision','page'), '*',
			array(
				'rev_page' => $this->title->getArticleID(),
				'rev_id'   => $ids,
				'rev_page = page_id'
			),
			__METHOD__,
			array( 'ORDER BY' => 'rev_id DESC' )
		);
	}

	public function newItem( $row ) {
		return new RevDel_RevisionItem( $this, $row );
	}

	public function getCurrent() {
		if ( is_null( $this->currentRevId ) ) {
			$dbw = wfGetDB( DB_MASTER );
			$this->currentRevId = $dbw->selectField( 
				'page', 'page_latest', $this->title->pageCond(), __METHOD__ );
		}
		return $this->currentRevId;
	}

	public function getSuppressBit() {
		return Revision::DELETED_RESTRICTED;
	}

	public function doPreCommitUpdates() {
		$this->title->invalidateCache();
		return Status::newGood();
	}

	public function doPostCommitUpdates() {
		$this->title->purgeSquid();
		// Extensions that require referencing previous revisions may need this
		wfRunHooks( 'ArticleRevisionVisiblitySet', array( &$this->title ) );
		return Status::newGood();
	}
}

/**
 * Item class for a revision table row
 */
class RevDel_RevisionItem extends RevDel_Item {
	var $revision;

	public function __construct( $list, $row ) {
		parent::__construct( $list, $row );
		$this->revision = new Revision( $row );
	}

	public function canView() {
		return $this->revision->userCan( Revision::DELETED_RESTRICTED );
	}
	
	public function canViewContent() {
		return $this->revision->userCan( Revision::DELETED_TEXT );
	}

	public function getBits() {
		return $this->revision->mDeleted;
	}

	public function setBits( $bits ) {
		$dbw = wfGetDB( DB_MASTER );
		// Update revision table
		$dbw->update( 'revision',
			array( 'rev_deleted' => $bits ),
			array( 
				'rev_id' => $this->revision->getId(), 
				'rev_page' => $this->revision->getPage(),
				'rev_deleted' => $this->getBits()
			),
			__METHOD__
		);
		if ( !$dbw->affectedRows() ) {
			// Concurrent fail!
			return false;
		}
		// Update recentchanges table
		$dbw->update( 'recentchanges',
			array( 
				'rc_deleted' => $bits,
				'rc_patrolled' => 1
			),
			array(
				'rc_this_oldid' => $this->revision->getId(), // condition
				// non-unique timestamp index
				'rc_timestamp' => $dbw->timestamp( $this->revision->getTimestamp() ),
			),
			__METHOD__
		);
		return true;
	}

	public function isDeleted() {
		return $this->revision->isDeleted( Revision::DELETED_TEXT );
	}

	public function isHideCurrentOp( $newBits ) {
		return ( $newBits & Revision::DELETED_TEXT ) 
			&& $this->list->getCurrent() == $this->getId();
	}

	/**
	 * Get the HTML link to the revision text.
	 * Overridden by RevDel_ArchiveItem.
	 */
	protected function getRevisionLink() {
		global $wgLang;
		$date = $wgLang->timeanddate( $this->revision->getTimestamp(), true );
		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return $date;
		}
		return $this->special->skin->link(
			$this->list->title,
			$date, 
			array(),
			array(
				'oldid' => $this->revision->getId(),
				'unhide' => 1
			)
		);
	}

	/**
	 * Get the HTML link to the diff.
	 * Overridden by RevDel_ArchiveItem
	 */
	protected function getDiffLink() {
		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return wfMsgHtml('diff');
		} else {
			return 
				$this->special->skin->link( 
					$this->list->title, 
					wfMsgHtml('diff'),
					array(),
					array(
						'diff' => $this->revision->getId(),
						'oldid' => 'prev',
						'unhide' => 1
					),
					array(
						'known',
						'noclasses'
					)
				);
		}
	}

	public function getHTML() {
		$difflink = $this->getDiffLink();
		$revlink = $this->getRevisionLink();
		$userlink = $this->special->skin->revUserLink( $this->revision );
		$comment = $this->special->skin->revComment( $this->revision );
		if ( $this->isDeleted() ) {
			$revlink = "<span class=\"history-deleted\">$revlink</span>";
		}
		return "<li>($difflink) $revlink $userlink $comment</li>";
	}
}

/**
 * List for archive table items, i.e. revisions deleted via action=delete
 */
class RevDel_ArchiveList extends RevDel_RevisionList {
	var $type = 'archive';
	var $idField = 'ar_timestamp';
	var $dateField = 'ar_timestamp';
	var $authorIdField = 'ar_user';
	var $authorNameField = 'ar_user_text';

	public function doQuery( $db ) {
		$timestamps = array();
		foreach ( $this->ids as $id ) {
			$timestamps[] = $db->timestamp( $id );
		}
		return $db->select( 'archive', '*',
				array(
					'ar_namespace' => $this->title->getNamespace(),
					'ar_title'     => $this->title->getDBkey(),
					'ar_timestamp' => $timestamps
				),
				__METHOD__,
				array( 'ORDER BY' => 'ar_timestamp DESC' )
			);
	}

	public function newItem( $row ) {
		return new RevDel_ArchiveItem( $this, $row );
	}

	public function doPreCommitUpdates() {
		return Status::newGood();
	}

	public function doPostCommitUpdates() {
		return Status::newGood();
	}
}

/**
 * Item class for a archive table row
 */
class RevDel_ArchiveItem extends RevDel_RevisionItem {
	public function __construct( $list, $row ) {
		RevDel_Item::__construct( $list, $row );
		$this->revision = Revision::newFromArchiveRow( $row,
			array( 'page' => $this->list->title->getArticleId() ) );
	}

	public function getId() {
		# Convert DB timestamp to MW timestamp
		return $this->revision->getTimestamp();
	}
	
	public function setBits( $bits ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'archive',
			array( 'ar_deleted' => $bits ),
			array( 'ar_namespace' => $this->list->title->getNamespace(),
				'ar_title'     => $this->list->title->getDBkey(),
				// use timestamp for index
				'ar_timestamp' => $this->row->ar_timestamp,
				'ar_rev_id'    => $this->row->ar_rev_id,
				'ar_deleted' => $this->getBits()
			),
			__METHOD__ );
		return (bool)$dbw->affectedRows();
	}

	protected function getRevisionLink() {
		global $wgLang;
		$undelete = SpecialPage::getTitleFor( 'Undelete' );
		$date = $wgLang->timeanddate( $this->revision->getTimestamp(), true );
		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return $date;
		}
		return $this->special->skin->link( $undelete, $date, array(),
			array(
				'target' => $this->list->title->getPrefixedText(),
				'timestamp' => $this->revision->getTimestamp()
			) );
	}

	protected function getDiffLink() {
		if ( $this->isDeleted() && !$this->canViewContent() ) {
			return wfMsgHtml( 'diff' );
		}
		$undelete = SpecialPage::getTitleFor( 'Undelete' );		
		return $this->special->skin->link( $undelete, wfMsgHtml('diff'), array(), 
			array(
				'target' => $this->list->title->getPrefixedText(),
				'diff' => 'prev',
				'timestamp' => $this->revision->getTimestamp()
			) );
	}
}

/**
 * List for oldimage table items
 */
class RevDel_FileList extends RevDel_List {
	var $type = 'oldimage';
	var $idField = 'oi_archive_name';
	var $dateField = 'oi_timestamp';
	var $authorIdField = 'oi_user';
	var $authorNameField = 'oi_user_text';
	var $storeBatch, $deleteBatch, $cleanupBatch;

	public function doQuery( $db ) {
		$archiveName = array();
		foreach( $this->ids as $timestamp ) {
			$archiveNames[] = $timestamp . '!' . $this->title->getDBkey();
		}
		return $db->select( 'oldimage', '*',
			array(
				'oi_name'         => $this->title->getDBkey(),
				'oi_archive_name' => $archiveNames
			),
			__METHOD__,
			array( 'ORDER BY' => 'oi_timestamp DESC' )
		);
	}

	public function newItem( $row ) {
		return new RevDel_FileItem( $this, $row );
	}

	public function clearFileOps() {
		$this->deleteBatch = array();
		$this->storeBatch = array();
		$this->cleanupBatch = array();
	}

	public function doPreCommitUpdates() {
		$status = Status::newGood();
		$repo = RepoGroup::singleton()->getLocalRepo();
		if ( $this->storeBatch ) {
			$status->merge( $repo->storeBatch( $this->storeBatch, FileRepo::OVERWRITE_SAME ) );
		}
		if ( !$status->isOK() ) {
			return $status;
		}
		if ( $this->deleteBatch ) {
			$status->merge( $repo->deleteBatch( $this->deleteBatch ) );
		}
		if ( !$status->isOK() ) {
			// Running cleanupDeletedBatch() after a failed storeBatch() with the DB already
			// modified (but destined for rollback) causes data loss
			return $status;
		}
		if ( $this->cleanupBatch ) {
			$status->merge( $repo->cleanupDeletedBatch( $this->cleanupBatch ) );
		}
		return $status;
	}

	public function doPostCommitUpdates() {
		$file = wfLocalFile( $this->title );
		$file->purgeCache();
		$file->purgeDescription();
		return Status::newGood();
	}

	public function getSuppressBit() {
		return File::DELETED_RESTRICTED;
	}
}

/**
 * Item class for an oldimage table row
 */
class RevDel_FileItem extends RevDel_Item {
	var $file;

	public function __construct( $list, $row ) {
		parent::__construct( $list, $row );
		$this->file = RepoGroup::singleton()->getLocalRepo()->newFileFromRow( $row );
	}

	public function getId() {
		$parts = explode( '!', $this->row->oi_archive_name );
		return $parts[0];
	}

	public function canView() {
		return $this->file->userCan( File::DELETED_RESTRICTED );
	}
	
	public function canViewContent() {
		return $this->file->userCan( File::DELETED_FILE );
	}

	public function getBits() {
		return $this->file->getVisibility();
	}

	public function setBits( $bits ) {
		# Queue the file op
		# FIXME: move to LocalFile.php
		if ( $this->isDeleted() ) {
			if ( $bits & File::DELETED_FILE ) {
				# Still deleted
			} else {
				# Newly undeleted
				$key = $this->file->getStorageKey();
				$srcRel = $this->file->repo->getDeletedHashPath( $key ) . $key;
				$this->list->storeBatch[] = array(
					$this->file->repo->getVirtualUrl( 'deleted' ) . '/' . $srcRel,
					'public',
					$this->file->getRel()
				);
				$this->list->cleanupBatch[] = $key;
			}
		} elseif ( $bits & File::DELETED_FILE ) {
			# Newly deleted
			$key = $this->file->getStorageKey();
			$dstRel = $this->file->repo->getDeletedHashPath( $key ) . $key;
			$this->list->deleteBatch[] = array( $this->file->getRel(), $dstRel );
		}
		
		# Do the database operations
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'oldimage',
			array( 'oi_deleted' => $bits ),
			array( 
				'oi_name' => $this->row->oi_name,
				'oi_timestamp' => $this->row->oi_timestamp,
				'oi_deleted' => $this->getBits()
			),
			__METHOD__
		);
		return (bool)$dbw->affectedRows();
	}

	public function isDeleted() {
		return $this->file->isDeleted( File::DELETED_FILE );
	}

	/**
	 * Get the link to the file. 
	 * Overridden by RevDel_ArchivedFileItem.
	 */
	protected function getLink() {
		global $wgLang, $wgUser;
		$date = $wgLang->timeanddate( $this->file->getTimestamp(), true  );		
		if ( $this->isDeleted() ) {
			# Hidden files...
			if ( !$this->canViewContent() ) {
				$link = $date;
			} else {
				$link = $this->special->skin->link( 
					$this->special->getTitle(), 
					$date, array(), 
					array(
						'target' => $this->list->title->getPrefixedText(),
						'file'   => $this->file->getArchiveName(),
						'token'  => $wgUser->editToken( $this->file->getArchiveName() )
					)
				);
			}
			return '<span class="history-deleted">' . $link . '</span>';
		} else {
			# Regular files...
			$url = $this->file->getUrl();
			return Xml::element( 'a', array( 'href' => $this->file->getUrl() ), $date );
		}
	}
	/**
	 * Generate a user tool link cluster if the current user is allowed to view it
	 * @return string HTML
	 */
	protected function getUserTools() {
		if( $this->file->userCan( Revision::DELETED_USER ) ) {
			$link = $this->special->skin->userLink( $this->file->user, $this->file->user_text ) .
				$this->special->skin->userToolLinks( $this->file->user, $this->file->user_text );
		} else {
			$link = wfMsgHtml( 'rev-deleted-user' );
		}
		if( $this->file->isDeleted( Revision::DELETED_USER ) ) {
			return '<span class="history-deleted">' . $link . '</span>';
		}
		return $link;
	}

	/**
	 * Wrap and format the file's comment block, if the current
	 * user is allowed to view it.
	 *
	 * @return string HTML
	 */
	protected function getComment() {
		if( $this->file->userCan( File::DELETED_COMMENT ) ) {
			$block = $this->special->skin->commentBlock( $this->file->description );
		} else {
			$block = ' ' . wfMsgHtml( 'rev-deleted-comment' );
		}
		if( $this->file->isDeleted( File::DELETED_COMMENT ) ) {
			return "<span class=\"history-deleted\">$block</span>";
		}
		return $block;
	}

	public function getHTML() {
		global $wgLang;
		$data = 
			wfMsg(
				'widthheight', 
				$wgLang->formatNum( $this->file->getWidth() ),
				$wgLang->formatNum( $this->file->getHeight() ) 
			) .
			' (' . 
			wfMsgExt( 'nbytes', 'parsemag', $wgLang->formatNum( $this->file->getSize() ) ) . 
			')';
		$pageLink = $this->getLink();

		return '<li>' . $this->getLink() . ' ' . $this->getUserTools() . ' ' .
			$data . ' ' . $this->getComment(). '</li>';
	}
}

/**
 * List for filearchive table items
 */
class RevDel_ArchivedFileList extends RevDel_FileList {
	var $type = 'filearchive';
	var $idField = 'fa_id';
	var $dateField = 'fa_timestamp';
	var $authorIdField = 'fa_user';
	var $authorNameField = 'fa_user_text';
	
	public function doQuery( $db ) {
		$ids = array_map( 'intval', $this->ids );
		return $db->select( 'filearchive', '*',
			array(
				'fa_name' => $this->title->getDBkey(),
				'fa_id'   => $ids
			),
			__METHOD__,
			array( 'ORDER BY' => 'fa_id DESC' )
		);
	}

	public function newItem( $row ) {
		return new RevDel_ArchivedFileItem( $this, $row );
	}
}

/**
 * Item class for a filearchive table row
 */
class RevDel_ArchivedFileItem extends RevDel_FileItem {
	public function __construct( $list, $row ) {
		RevDel_Item::__construct( $list, $row );
		$this->file = ArchivedFile::newFromRow( $row );
	}

	public function getId() {
		return $this->row->fa_id;
	}

	public function setBits( $bits ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'filearchive',
			array( 'fa_deleted' => $bits ),
			array(
				'fa_id' => $this->row->fa_id,
				'fa_deleted' => $this->getBits(),
			),
			__METHOD__
		);
		return (bool)$dbw->affectedRows();
	}

	protected function getLink() {
		global $wgLang, $wgUser;
		$date = $wgLang->timeanddate( $this->file->getTimestamp(), true  );
		$undelete = SpecialPage::getTitleFor( 'Undelete' );
		$key = $this->file->getKey();
		# Hidden files...
		if( !$this->canViewContent() ) {
			$link = $date;
		} else {
			$link = $this->special->skin->link( $undelete, $date, array(),
				array(
					'target' => $this->list->title->getPrefixedText(),
					'file' => $key,
					'token' => $wgUser->editToken( $key )
				)
			);
		}
		if( $this->isDeleted() ) {
			$link = '<span class="history-deleted">' . $link . '</span>';
		}
		return $link;
	}
}

/**
 * List for logging table items
 */
class RevDel_LogList extends RevDel_List {
	var $type = 'logging';
	var $idField = 'log_id';
	var $dateField = 'log_timestamp';
	var $authorIdField = 'log_user';
	var $authorNameField = 'log_user_text';

	public function doQuery( $db ) {
		global $wgMessageCache;
		$wgMessageCache->loadAllMessages();
		$ids = array_map( 'intval', $this->ids );
		return $db->select( 'logging', '*',
			array( 'log_id' => $ids ),
			__METHOD__,
			array( 'ORDER BY' => 'log_id DESC' )
		);
	}

	public function newItem( $row ) {
		return new RevDel_LogItem( $this, $row );
	}

	public function getSuppressBit() {
		return Revision::DELETED_RESTRICTED;
	}

	public function getLogAction() {
		return 'event';
	}

	public function getLogParams( $params ) {
		return array(
			implode( ',', $params['ids'] ),
			"ofield={$params['oldBits']}",
			"nfield={$params['newBits']}"
		);
	}
}

/**
 * Item class for a logging table row
 */
class RevDel_LogItem extends RevDel_Item {
	public function canView() {
		return LogEventsList::userCan( $this->row, Revision::DELETED_RESTRICTED );
	}
	
	public function canViewContent() {
		return true; // none
	}

	public function getBits() {
		return $this->row->log_deleted;
	}

	public function setBits( $bits ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->update( 'recentchanges',
			array( 
				'rc_deleted' => $bits, 
				'rc_patrolled' => 1 
			),
			array(
				'rc_logid' => $this->row->log_id,
				'rc_timestamp' => $this->row->log_timestamp // index
			),
			__METHOD__
		);
		$dbw->update( 'logging',
			array( 'log_deleted' => $bits ),
			array(
				'log_id' => $this->row->log_id,
				'log_deleted' => $this->getBits()
			),
			__METHOD__
		);
		return (bool)$dbw->affectedRows();
	}

	public function getHTML() {
		global $wgLang;

		$date = htmlspecialchars( $wgLang->timeanddate( $this->row->log_timestamp ) );
		$paramArray = LogPage::extractParams( $this->row->log_params );
		$title = Title::makeTitle( $this->row->log_namespace, $this->row->log_title );

		// Log link for this page
		$loglink = $this->special->skin->link(
			SpecialPage::getTitleFor( 'Log' ),
			wfMsgHtml( 'log' ),
			array(),
			array( 'page' => $title->getPrefixedText() )
		);
		// Action text
		if( !$this->canView() ) {
			$action = '<span class="history-deleted">' . wfMsgHtml('rev-deleted-event') . '</span>';
		} else {
			$action = LogPage::actionText( $this->row->log_type, $this->row->log_action, $title,
				$this->special->skin, $paramArray, true, true );
			if( $this->row->log_deleted & LogPage::DELETED_ACTION )
				$action = '<span class="history-deleted">' . $action . '</span>';
		}
		// User links
		$userLink = $this->special->skin->userLink( $this->row->log_user,
			User::WhoIs( $this->row->log_user ) );
		if( LogEventsList::isDeleted($this->row,LogPage::DELETED_USER) ) {
			$userLink = '<span class="history-deleted">' . $userLink . '</span>';
		}
		// Comment
		$comment = $wgLang->getDirMark() . $this->special->skin->commentBlock( $this->row->log_comment );
		if( LogEventsList::isDeleted($this->row,LogPage::DELETED_COMMENT) ) {
			$comment = '<span class="history-deleted">' . $comment . '</span>';
		}
		return "<li>($loglink) $date $userLink $action $comment</li>";
	}
}
