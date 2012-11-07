<?php
/**
 * Implements Special:Revisiondelete
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
 * Special page allowing users with the appropriate permissions to view
 * and hide revisions. Log items can also be hidden.
 *
 * @ingroup SpecialPage
 */
class SpecialRevisionDelete extends UnlistedSpecialPage {
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
			'check-label' 	=> 'revdelete-hide-text',
			'deletion-bits' => Revision::DELETED_TEXT,
			'success' 		=> 'revdelete-success',
			'failure' 		=> 'revdelete-failure',
			'list-class' 	=> 'RevDel_RevisionList',
			'permission'	=> 'deleterevision',
		),
		'archive' => array(
			'check-label' 	=> 'revdelete-hide-text',
			'deletion-bits' => Revision::DELETED_TEXT,
			'success' 		=> 'revdelete-success',
			'failure' 		=> 'revdelete-failure',
			'list-class' 	=> 'RevDel_ArchiveList',
			'permission'	=> 'deleterevision',
		),
		'oldimage'=> array(
			'check-label' 	=> 'revdelete-hide-image',
			'deletion-bits' => File::DELETED_FILE,
			'success' 		=> 'revdelete-success',
			'failure' 		=> 'revdelete-failure',
			'list-class' 	=> 'RevDel_FileList',
			'permission'	=> 'deleterevision',
		),
		'filearchive' => array(
			'check-label' 	=> 'revdelete-hide-image',
			'deletion-bits' => File::DELETED_FILE,
			'success' 		=> 'revdelete-success',
			'failure' 		=> 'revdelete-failure',
			'list-class' 	=> 'RevDel_ArchivedFileList',
			'permission'	=> 'deleterevision',
		),
		'logging' => array(
			'check-label'	=> 'revdelete-hide-name',
			'deletion-bits' => LogPage::DELETED_ACTION,
			'success' 		=> 'logdelete-success',
			'failure' 		=> 'logdelete-failure',
			'list-class'	=> 'RevDel_LogList',
			'permission'	=> 'deletelogentry',
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
		$this->checkPermissions();
		$this->checkReadOnly();

		$output = $this->getOutput();
		$user = $this->getUser();

		$this->setHeaders();
		$this->outputHeader();
		$request = $this->getRequest();
		$this->submitClicked = $request->wasPosted() && $request->getBool( 'wpSubmit' );
		# Handle our many different possible input types.
		$ids = $request->getVal( 'ids' );
		if ( !is_null( $ids ) ) {
			# Allow CSV, for backwards compatibility, or a single ID for show/hide links
			$this->ids = explode( ',', $ids );
		} else {
			# Array input
			$this->ids = array_keys( $request->getArray('ids',array()) );
		}
		// $this->ids = array_map( 'intval', $this->ids );
		$this->ids = array_unique( array_filter( $this->ids ) );

		if ( $request->getVal( 'action' ) == 'historysubmit' || $request->getVal( 'action' ) == 'revisiondelete' ) {
			// For show/hide form submission from history page
			// Since we are access through index.php?title=XXX&action=historysubmit
			// getFullTitle() will contain the target title and not our title
			$this->targetObj = $this->getFullTitle();
			$this->typeName = 'revision';
		} else {
			$this->typeName = $request->getVal( 'type' );
			$this->targetObj = Title::newFromText( $request->getText( 'target' ) );
			if ( $this->targetObj->isSpecial( 'Log' ) ) {
				$result = wfGetDB( DB_SLAVE )->select( 'logging',
					'log_type',
					array( 'log_id' => $this->ids ),
					__METHOD__,
					array( 'DISTINCT' )
				);

				$logTypes = array();
				foreach ( $result as $row ) {
					$logTypes[] = $row->log_type;
				}

				if ( count( $logTypes ) == 1 ) {
					// If there's only one type, the target can be set to include it.
					$this->targetObj = SpecialPage::getTitleFor( 'Log', $logTypes[0] );
				}
			}
		}

		# For reviewing deleted files...
		$this->archiveName = $request->getVal( 'file' );
		$this->token = $request->getVal( 'token' );
		if ( $this->archiveName && $this->targetObj ) {
			$this->tryShowFile( $this->archiveName );
			return;
		}

		if ( isset( self::$deprecatedTypeMap[$this->typeName] ) ) {
			$this->typeName = self::$deprecatedTypeMap[$this->typeName];
		}

		# No targets?
		if( !isset( self::$allowedTypes[$this->typeName] ) || count( $this->ids ) == 0 ) {
			throw new ErrorPageError( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
		}
		$this->typeInfo = self::$allowedTypes[$this->typeName];
		$this->mIsAllowed = $user->isAllowed( $this->typeInfo['permission'] );

		# If we have revisions, get the title from the first one
		# since they should all be from the same page. This allows
		# for more flexibility with page moves...
		if( $this->typeName == 'revision' ) {
			$rev = Revision::newFromId( $this->ids[0] );
			$this->targetObj = $rev ? $rev->getTitle() : $this->targetObj;
		}

		$this->otherReason = $request->getVal( 'wpReason' );
		# We need a target page!
		if( is_null($this->targetObj) ) {
			$output->addWikiMsg( 'undelete-header' );
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
		if( $user->isAllowed('suppressrevision') ) {
			$this->checks[] = array( 'revdelete-hide-restricted',
				'wpHideRestricted', Revision::DELETED_RESTRICTED );
		}

		# Either submit or create our form
		if( $this->mIsAllowed && $this->submitClicked ) {
			$this->submit( $request );
		} else {
			$this->showForm();
		}

		$qc = $this->getLogQueryCond();
		# Show relevant lines from the deletion log
		$deleteLogPage = new LogPage( 'delete' );
		$output->addHTML( "<h2>" . $deleteLogPage->getName()->escaped() . "</h2>\n" );
		LogEventsList::showLogExtract( $output, 'delete',
			$this->targetObj, '', array( 'lim' => 25, 'conds' => $qc ) );
		# Show relevant lines from the suppression log
		if( $user->isAllowed( 'suppressionlog' ) ) {
			$suppressLogPage = new LogPage( 'suppress' );
			$output->addHTML( "<h2>" . $suppressLogPage->getName()->escaped()  . "</h2>\n" );
			LogEventsList::showLogExtract( $output, 'suppress',
				$this->targetObj, '', array( 'lim' => 25, 'conds' => $qc ) );
		}
	}

	/**
	 * Show some useful links in the subtitle
	 */
	protected function showConvenienceLinks() {
		# Give a link to the logs/hist for this page
		if( $this->targetObj ) {
			$links = array();
			$links[] = Linker::linkKnown(
				SpecialPage::getTitleFor( 'Log' ),
				$this->msg( 'viewpagelogs' )->escaped(),
				array(),
				array( 'page' => $this->targetObj->getPrefixedText() )
			);
			if ( !$this->targetObj->isSpecialPage() ) {
				# Give a link to the page history
				$links[] = Linker::linkKnown(
					$this->targetObj,
					$this->msg( 'pagehist' )->escaped(),
					array(),
					array( 'action' => 'history' )
				);
				# Link to deleted edits
				if( $this->getUser()->isAllowed('undelete') ) {
					$undelete = SpecialPage::getTitleFor( 'Undelete' );
					$links[] = Linker::linkKnown(
						$undelete,
						$this->msg( 'deletedhist' )->escaped(),
						array(),
						array( 'target' => $this->targetObj->getPrefixedDBkey() )
					);
				}
			}
			# Logs themselves don't have histories or archived revisions
			$this->getOutput()->addSubtitle( $this->getLanguage()->pipeList( $links ) );
		}
	}

	/**
	 * Get the condition used for fetching log snippets
	 * @return array
	 */
	protected function getLogQueryCond() {
		$conds = array();
		// Revision delete logs for these item
		$conds['log_type'] = array( 'delete', 'suppress' );
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
		$repo = RepoGroup::singleton()->getLocalRepo();
		$oimage = $repo->newFromArchiveName( $this->targetObj, $archiveName );
		$oimage->load();
		// Check if user is allowed to see this file
		if ( !$oimage->exists() ) {
			$this->getOutput()->addWikiMsg( 'revdelete-no-file' );
			return;
		}
		$user = $this->getUser();
		if( !$oimage->userCan( File::DELETED_FILE, $user ) ) {
			if( $oimage->isDeleted( File::DELETED_RESTRICTED ) ) {
				throw new PermissionsError( 'suppressrevision' );
			} else {
				throw new PermissionsError( 'deletedtext' );
			}
		}
		if ( !$user->matchEditToken( $this->token, $archiveName ) ) {
			$lang = $this->getLanguage();
			$this->getOutput()->addWikiMsg( 'revdelete-show-file-confirm',
				$this->targetObj->getText(),
				$lang->userDate( $oimage->getTimestamp(), $user ),
				$lang->userTime( $oimage->getTimestamp(), $user ) );
			$this->getOutput()->addHTML(
				Xml::openElement( 'form', array(
					'method' => 'POST',
					'action' => $this->getTitle()->getLocalUrl(
						'target=' . urlencode( $this->targetObj->getPrefixedDBkey() ) .
						'&file=' . urlencode( $archiveName ) .
						'&token=' . urlencode( $user->getEditToken( $archiveName ) ) )
					)
				) .
				Xml::submitButton( $this->msg( 'revdelete-show-file-submit' )->text() ) .
				'</form>'
			);
			return;
		}
		$this->getOutput()->disable();
		# We mustn't allow the output to be Squid cached, otherwise
		# if an admin previews a deleted image, and it's cached, then
		# a user without appropriate permissions can toddle off and
		# nab the image, and Squid will serve it
		$this->getRequest()->response()->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
		$this->getRequest()->response()->header( 'Cache-Control: no-cache, no-store, max-age=0, must-revalidate' );
		$this->getRequest()->response()->header( 'Pragma: no-cache' );

		$key = $oimage->getStorageKey();
		$path = $repo->getZonePath( 'deleted' ) . '/' . $repo->getDeletedHashPath( $key ) . $key;
		$repo->streamFile( $path );
	}

	/**
	 * Get the list object for this request
	 */
	protected function getList() {
		if ( is_null( $this->list ) ) {
			$class = $this->typeInfo['list-class'];
			$this->list = new $class( $this->getContext(), $this->targetObj, $this->ids );
		}
		return $this->list;
	}

	/**
	 * Show a list of items that we will operate on, and show a form with checkboxes
	 * which will allow the user to choose new visibility settings.
	 */
	protected function showForm() {
		$UserAllowed = true;

		if ( $this->typeName == 'logging' ) {
			$this->getOutput()->addWikiMsg( 'logdelete-selected', $this->getLanguage()->formatNum( count($this->ids) ) );
		} else {
			$this->getOutput()->addWikiMsg( 'revdelete-selected',
				$this->targetObj->getPrefixedText(), count( $this->ids ) );
		}

		$this->getOutput()->addHTML( "<ul>" );

		$numRevisions = 0;
		// Live revisions...
		$list = $this->getList();
		for ( $list->reset(); $list->current(); $list->next() ) {
			$item = $list->current();
			if ( !$item->canView() ) {
				if( !$this->submitClicked ) {
					throw new PermissionsError( 'suppressrevision' );
				}
				$UserAllowed = false;
			}
			$numRevisions++;
			$this->getOutput()->addHTML( $item->getHTML() );
		}

		if( !$numRevisions ) {
			throw new ErrorPageError( 'revdelete-nooldid-title', 'revdelete-nooldid-text' );
		}

		$this->getOutput()->addHTML( "</ul>" );
		// Explanation text
		$this->addUsageText();

		// Normal sysops can always see what they did, but can't always change it
		if( !$UserAllowed ) return;

		// Show form if the user can submit
		if( $this->mIsAllowed ) {
			$out = Xml::openElement( 'form', array( 'method' => 'post',
					'action' => $this->getTitle()->getLocalUrl( array( 'action' => 'submit' ) ),
					'id' => 'mw-revdel-form-revisions' ) ) .
				Xml::fieldset( $this->msg( 'revdelete-legend' )->text() ) .
				$this->buildCheckBoxes() .
				Xml::openElement( 'table' ) .
				"<tr>\n" .
					'<td class="mw-label">' .
						Xml::label( $this->msg( 'revdelete-log' )->text(), 'wpRevDeleteReasonList' ) .
					'</td>' .
					'<td class="mw-input">' .
						Xml::listDropDown( 'wpRevDeleteReasonList',
							$this->msg( 'revdelete-reason-dropdown' )->inContentLanguage()->text(),
							$this->msg( 'revdelete-reasonotherlist' )->inContentLanguage()->text(),
							'', 'wpReasonDropDown', 1
						) .
					'</td>' .
				"</tr><tr>\n" .
					'<td class="mw-label">' .
						Xml::label( $this->msg( 'revdelete-otherreason' )->text(), 'wpReason' ) .
					'</td>' .
					'<td class="mw-input">' .
						Xml::input( 'wpReason', 60, $this->otherReason, array( 'id' => 'wpReason', 'maxlength' => 100 ) ) .
					'</td>' .
				"</tr><tr>\n" .
					'<td></td>' .
					'<td class="mw-submit">' .
						Xml::submitButton( $this->msg( 'revdelete-submit', $numRevisions )->text(),
							array( 'name' => 'wpSubmit' ) ) .
					'</td>' .
				"</tr>\n" .
				Xml::closeElement( 'table' ) .
				Html::hidden( 'wpEditToken', $this->getUser()->getEditToken() ) .
				Html::hidden( 'target', $this->targetObj->getPrefixedText() ) .
				Html::hidden( 'type', $this->typeName ) .
				Html::hidden( 'ids', implode( ',', $this->ids ) ) .
				Xml::closeElement( 'fieldset' ) . "\n";
		} else {
			$out = '';
		}
		if( $this->mIsAllowed ) {
			$out .= Xml::closeElement( 'form' ) . "\n";
			// Show link to edit the dropdown reasons
			if( $this->getUser()->isAllowed( 'editinterface' ) ) {
				$title = Title::makeTitle( NS_MEDIAWIKI, 'Revdelete-reason-dropdown' );
				$link = Linker::link(
					$title,
					$this->msg( 'revdelete-edit-reasonlist' )->escaped(),
					array(),
					array( 'action' => 'edit' )
				);
				$out .= Xml::tags( 'p', array( 'class' => 'mw-revdel-editreasons' ), $link ) . "\n";
			}
		}
		$this->getOutput()->addHTML( $out );
	}

	/**
	 * Show some introductory text
	 * @todo FIXME: Wikimedia-specific policy text
	 */
	protected function addUsageText() {
		$this->getOutput()->addWikiMsg( 'revdelete-text' );
		if( $this->getUser()->isAllowed( 'suppressrevision' ) ) {
			$this->getOutput()->addWikiMsg( 'revdelete-suppress-text' );
		}
		if( $this->mIsAllowed ) {
			$this->getOutput()->addWikiMsg( 'revdelete-confirm' );
		}
	}

	/**
	* @return String: HTML
	*/
	protected function buildCheckBoxes() {
		$html = '<table>';
		// If there is just one item, use checkboxes
		$list = $this->getList();
		if( $list->length() == 1 ) {
			$list->reset();
			$bitfield = $list->current()->getBits(); // existing field
			if( $this->submitClicked ) {
				$bitfield = $this->extractBitfield( $this->extractBitParams(), $bitfield );
			}
			foreach( $this->checks as $item ) {
				list( $message, $name, $field ) = $item;
				$innerHTML = Xml::checkLabel( $this->msg( $message )->text(), $name, $name, $bitfield & $field );
				if( $field == Revision::DELETED_RESTRICTED )
					$innerHTML = "<b>$innerHTML</b>";
				$line = Xml::tags( 'td', array( 'class' => 'mw-input' ), $innerHTML );
				$html .= "<tr>$line</tr>\n";
			}
		// Otherwise, use tri-state radios
		} else {
			$html .= '<tr>';
			$html .= '<th class="mw-revdel-checkbox">' . $this->msg( 'revdelete-radio-same' )->escaped() . '</th>';
			$html .= '<th class="mw-revdel-checkbox">' . $this->msg( 'revdelete-radio-unset' )->escaped() . '</th>';
			$html .= '<th class="mw-revdel-checkbox">' . $this->msg( 'revdelete-radio-set' )->escaped() . '</th>';
			$html .= "<th></th></tr>\n";
			foreach( $this->checks as $item ) {
				list( $message, $name, $field ) = $item;
				// If there are several items, use third state by default...
				if( $this->submitClicked ) {
					$selected = $this->getRequest()->getInt( $name, 0 /* unchecked */ );
				} else {
					$selected = -1; // use existing field
				}
				$line = '<td class="mw-revdel-checkbox">' . Xml::radio( $name, -1, $selected == -1 ) . '</td>';
				$line .= '<td class="mw-revdel-checkbox">' . Xml::radio( $name, 0, $selected == 0 ) . '</td>';
				$line .= '<td class="mw-revdel-checkbox">' . Xml::radio( $name, 1, $selected == 1 ) . '</td>';
				$label = $this->msg( $message )->escaped();
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
	 * @return bool
	 */
	protected function submit() {
		# Check edit token on submission
		$token = $this->getRequest()->getVal('wpEditToken');
		if( $this->submitClicked && !$this->getUser()->matchEditToken( $token ) ) {
			$this->getOutput()->addWikiMsg( 'sessionfailure' );
			return false;
		}
		$bitParams = $this->extractBitParams();
		$listReason = $this->getRequest()->getText( 'wpRevDeleteReasonList', 'other' ); // from dropdown
		$comment = $listReason;
		if( $comment != 'other' && $this->otherReason != '' ) {
			// Entry from drop down menu + additional comment
			$comment .= $this->msg( 'colon-separator' )->inContentLanguage()->text() . $this->otherReason;
		} elseif( $comment == 'other' ) {
			$comment = $this->otherReason;
		}
		# Can the user set this field?
		if( $bitParams[Revision::DELETED_RESTRICTED]==1 && !$this->getUser()->isAllowed('suppressrevision') ) {
			throw new PermissionsError( 'suppressrevision' );
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
		$this->getOutput()->setPageTitle( $this->msg( 'actioncomplete' ) );
		$this->getOutput()->wrapWikiMsg( "<span class=\"success\">\n$1\n</span>", $this->typeInfo['success'] );
		$this->list->reloadFromMaster();
		$this->showForm();
	}

	/**
	 * Report that the submit operation failed
	 */
	protected function failure( $status ) {
		$this->getOutput()->setPageTitle( $this->msg( 'actionfailed' ) );
		$this->getOutput()->addWikiText( $status->getWikiText( $this->typeInfo['failure'] ) );
		$this->showForm();
	}

	/**
	 * Put together an array that contains -1, 0, or the *_deleted const for each bit
	 *
	 * @return array
	 */
	protected function extractBitParams() {
		$bitfield = array();
		foreach( $this->checks as $item ) {
			list( /* message */ , $name, $field ) = $item;
			$val = $this->getRequest()->getInt( $name, 0 /* unchecked */ );
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
			} elseif( $val == -1 ) {
				$newBits |= ($oldfield & $const); // use existing
			}
		}
		return $newBits;
	}

	/**
	 * Do the write operations. Simple wrapper for RevDel_*List::setVisibility().
	 * @param $bitfield
	 * @param $reason
	 * @param $title
	 * @return
	 */
	protected function save( $bitfield, $reason, $title ) {
		return $this->getList()->setVisibility(
			array( 'value' => $bitfield, 'comment' => $reason )
		);
	}
}

