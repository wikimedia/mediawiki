<?php
/**
 * Implements Special:Upload
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
 * @ingroup Upload
 */

use MediaWiki\MediaWikiServices;

/**
 * Form for handling uploads and special page.
 *
 * @ingroup SpecialPage
 * @ingroup Upload
 */
class SpecialUpload extends SpecialPage {
	/**
	 * Get data POSTed through the form and assign them to the object
	 * @param WebRequest $request Data posted.
	 */
	public function __construct( $request = null ) {
		parent::__construct( 'Upload', 'upload' );
	}

	public function doesWrites() {
		return true;
	}

	/** Misc variables **/

	/** @var WebRequest|FauxRequest The request this form is supposed to handle */
	public $mRequest;
	public $mSourceType;

	/** @var UploadBase */
	public $mUpload;

	/** @var LocalFile */
	public $mLocalFile;
	public $mUploadClicked;

	/** User input variables from the "description" section **/

	/** @var string The requested target file name */
	public $mDesiredDestName;
	public $mComment;
	public $mLicense;

	/** User input variables from the root section **/

	public $mIgnoreWarning;
	public $mWatchthis;
	public $mCopyrightStatus;
	public $mCopyrightSource;

	/** Hidden variables **/

	public $mDestWarningAck;

	/** @var bool The user followed an "overwrite this file" link */
	public $mForReUpload;

	/** @var bool The user clicked "Cancel and return to upload form" button */
	public $mCancelUpload;
	public $mTokenOk;

	/** @var bool Subclasses can use this to determine whether a file was uploaded */
	public $mUploadSuccessful = false;

	/** Text injection points for hooks not using HTMLForm **/
	public $uploadFormTextTop;
	public $uploadFormTextAfterSummary;

	/**
	 * Initialize instance variables from request and create an Upload handler
	 */
	protected function loadRequest() {
		$this->mRequest = $request = $this->getRequest();
		$this->mSourceType = $request->getVal( 'wpSourceType', 'file' );
		$this->mUpload = UploadBase::createFromRequest( $request );
		$this->mUploadClicked = $request->wasPosted()
			&& ( $request->getCheck( 'wpUpload' )
				|| $request->getCheck( 'wpUploadIgnoreWarning' ) );

		// Guess the desired name from the filename if not provided
		$this->mDesiredDestName = $request->getText( 'wpDestFile' );
		if ( !$this->mDesiredDestName && $request->getFileName( 'wpUploadFile' ) !== null ) {
			$this->mDesiredDestName = $request->getFileName( 'wpUploadFile' );
		}
		$this->mLicense = $request->getText( 'wpLicense' );

		$this->mDestWarningAck = $request->getText( 'wpDestFileWarningAck' );
		$this->mIgnoreWarning = $request->getCheck( 'wpIgnoreWarning' )
			|| $request->getCheck( 'wpUploadIgnoreWarning' );
		$this->mWatchthis = $request->getBool( 'wpWatchthis' ) && $this->getUser()->isLoggedIn();
		$this->mCopyrightStatus = $request->getText( 'wpUploadCopyStatus' );
		$this->mCopyrightSource = $request->getText( 'wpUploadSource' );

		$this->mForReUpload = $request->getBool( 'wpForReUpload' ); // updating a file

		$commentDefault = '';
		$commentMsg = wfMessage( 'upload-default-description' )->inContentLanguage();
		if ( !$this->mForReUpload && !$commentMsg->isDisabled() ) {
			$commentDefault = $commentMsg->plain();
		}
		$this->mComment = $request->getText( 'wpUploadDescription', $commentDefault );

		$this->mCancelUpload = $request->getCheck( 'wpCancelUpload' )
			|| $request->getCheck( 'wpReUpload' ); // b/w compat

		// If it was posted check for the token (no remote POST'ing with user credentials)
		$token = $request->getVal( 'wpEditToken' );
		$this->mTokenOk = $this->getUser()->matchEditToken( $token );

		$this->uploadFormTextTop = '';
		$this->uploadFormTextAfterSummary = '';
	}

	/**
	 * This page can be shown if uploading is enabled.
	 * Handle permission checking elsewhere in order to be able to show
	 * custom error messages.
	 *
	 * @param User $user
	 * @return bool
	 */
	public function userCanExecute( User $user ) {
		return UploadBase::isEnabled() && parent::userCanExecute( $user );
	}

	/**
	 * Special page entry point
	 * @param string $par
	 * @throws ErrorPageError
	 * @throws Exception
	 * @throws FatalError
	 * @throws MWException
	 * @throws PermissionsError
	 * @throws ReadOnlyError
	 * @throws UserBlockedError
	 */
	public function execute( $par ) {
		$this->useTransactionalTimeLimit();

		$this->setHeaders();
		$this->outputHeader();

		# Check uploading enabled
		if ( !UploadBase::isEnabled() ) {
			throw new ErrorPageError( 'uploaddisabled', 'uploaddisabledtext' );
		}

		$this->addHelpLink( 'Help:Managing files' );

		# Check permissions
		$user = $this->getUser();
		$permissionRequired = UploadBase::isAllowed( $user );
		if ( $permissionRequired !== true ) {
			throw new PermissionsError( $permissionRequired );
		}

		# Check blocks
		if ( $user->isBlocked() ) {
			throw new UserBlockedError( $user->getBlock() );
		}

		// Global blocks
		if ( $user->isBlockedGlobally() ) {
			throw new UserBlockedError( $user->getGlobalBlock() );
		}

		# Check whether we actually want to allow changing stuff
		$this->checkReadOnly();

		$this->loadRequest();

		# Unsave the temporary file in case this was a cancelled upload
		if ( $this->mCancelUpload ) {
			if ( !$this->unsaveUploadedFile() ) {
				# Something went wrong, so unsaveUploadedFile showed a warning
				return;
			}
		}

		# Process upload or show a form
		if (
			$this->mTokenOk && !$this->mCancelUpload &&
			( $this->mUpload && $this->mUploadClicked )
		) {
			$this->processUpload();
		} else {
			# Backwards compatibility hook
			// Avoid PHP 7.1 warning of passing $this by reference
			$upload = $this;
			if ( !Hooks::run( 'UploadForm:initial', [ &$upload ] ) ) {
				wfDebug( "Hook 'UploadForm:initial' broke output of the upload form\n" );

				return;
			}
			$this->showUploadForm( $this->getUploadForm() );
		}

		# Cleanup
		if ( $this->mUpload ) {
			$this->mUpload->cleanupTempFile();
		}
	}

	/**
	 * Show the main upload form
	 *
	 * @param HTMLForm|string $form An HTMLForm instance or HTML string to show
	 */
	protected function showUploadForm( $form ) {
		# Add links if file was previously deleted
		if ( $this->mDesiredDestName ) {
			$this->showViewDeletedLinks();
		}

		if ( $form instanceof HTMLForm ) {
			$form->show();
		} else {
			$this->getOutput()->addHTML( $form );
		}
	}

	/**
	 * Get an UploadForm instance with title and text properly set.
	 *
	 * @param string $message HTML string to add to the form
	 * @param string $sessionKey Session key in case this is a stashed upload
	 * @param bool $hideIgnoreWarning Whether to hide "ignore warning" check box
	 * @return UploadForm
	 */
	protected function getUploadForm( $message = '', $sessionKey = '', $hideIgnoreWarning = false ) {
		# Initialize form
		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle() ); // Remove subpage
		$form = new UploadForm( [
			'watch' => $this->getWatchCheck(),
			'forreupload' => $this->mForReUpload,
			'sessionkey' => $sessionKey,
			'hideignorewarning' => $hideIgnoreWarning,
			'destwarningack' => (bool)$this->mDestWarningAck,

			'description' => $this->mComment,
			'texttop' => $this->uploadFormTextTop,
			'textaftersummary' => $this->uploadFormTextAfterSummary,
			'destfile' => $this->mDesiredDestName,
		], $context, $this->getLinkRenderer() );

		# Check the token, but only if necessary
		if (
			!$this->mTokenOk && !$this->mCancelUpload &&
			( $this->mUpload && $this->mUploadClicked )
		) {
			$form->addPreText( $this->msg( 'session_fail_preview' )->parse() );
		}

		# Give a notice if the user is uploading a file that has been deleted or moved
		# Note that this is independent from the message 'filewasdeleted'
		$desiredTitleObj = Title::makeTitleSafe( NS_FILE, $this->mDesiredDestName );
		$delNotice = ''; // empty by default
		if ( $desiredTitleObj instanceof Title && !$desiredTitleObj->exists() ) {
			$dbr = wfGetDB( DB_REPLICA );

			LogEventsList::showLogExtract( $delNotice, [ 'delete', 'move' ],
				$desiredTitleObj,
				'', [ 'lim' => 10,
					'conds' => [ 'log_action != ' . $dbr->addQuotes( 'revision' ) ],
					'showIfEmpty' => false,
					'msgKey' => [ 'upload-recreate-warning' ] ]
			);
		}
		$form->addPreText( $delNotice );

		# Add text to form
		$form->addPreText( '<div id="uploadtext">' .
			$this->msg( 'uploadtext', [ $this->mDesiredDestName ] )->parseAsBlock() .
			'</div>' );
		# Add upload error message
		$form->addPreText( $message );

		# Add footer to form
		$uploadFooter = $this->msg( 'uploadfooter' );
		if ( !$uploadFooter->isDisabled() ) {
			$form->addPostText( '<div id="mw-upload-footer-message">'
				. $uploadFooter->parseAsBlock() . "</div>\n" );
		}

		return $form;
	}

	/**
	 * Shows the "view X deleted revivions link""
	 */
	protected function showViewDeletedLinks() {
		$title = Title::makeTitleSafe( NS_FILE, $this->mDesiredDestName );
		$user = $this->getUser();
		// Show a subtitle link to deleted revisions (to sysops et al only)
		if ( $title instanceof Title ) {
			$count = $title->isDeleted();
			if ( $count > 0 && $user->isAllowed( 'deletedhistory' ) ) {
				$restorelink = $this->getLinkRenderer()->makeKnownLink(
					SpecialPage::getTitleFor( 'Undelete', $title->getPrefixedText() ),
					$this->msg( 'restorelink' )->numParams( $count )->text()
				);
				$link = $this->msg( $user->isAllowed( 'delete' ) ? 'thisisdeleted' : 'viewdeleted' )
					->rawParams( $restorelink )->parseAsBlock();
				$this->getOutput()->addHTML( "<div id=\"contentSub2\">{$link}</div>" );
			}
		}
	}

	/**
	 * Stashes the upload and shows the main upload form.
	 *
	 * Note: only errors that can be handled by changing the name or
	 * description should be redirected here. It should be assumed that the
	 * file itself is sane and has passed UploadBase::verifyFile. This
	 * essentially means that UploadBase::VERIFICATION_ERROR and
	 * UploadBase::EMPTY_FILE should not be passed here.
	 *
	 * @param string $message HTML message to be passed to mainUploadForm
	 */
	protected function showRecoverableUploadError( $message ) {
		$stashStatus = $this->mUpload->tryStashFile( $this->getUser() );
		if ( $stashStatus->isGood() ) {
			$sessionKey = $stashStatus->getValue()->getFileKey();
			$uploadWarning = 'upload-tryagain';
		} else {
			$sessionKey = null;
			$uploadWarning = 'upload-tryagain-nostash';
		}
		$message = '<h2>' . $this->msg( 'uploaderror' )->escaped() . "</h2>\n" .
			'<div class="error">' . $message . "</div>\n";

		$form = $this->getUploadForm( $message, $sessionKey );
		$form->setSubmitText( $this->msg( $uploadWarning )->escaped() );
		$this->showUploadForm( $form );
	}

	/**
	 * Stashes the upload, shows the main form, but adds a "continue anyway button".
	 * Also checks whether there are actually warnings to display.
	 *
	 * @param array $warnings
	 * @return bool True if warnings were displayed, false if there are no
	 *   warnings and it should continue processing
	 */
	protected function showUploadWarning( $warnings ) {
		# If there are no warnings, or warnings we can ignore, return early.
		# mDestWarningAck is set when some javascript has shown the warning
		# to the user. mForReUpload is set when the user clicks the "upload a
		# new version" link.
		if ( !$warnings || ( count( $warnings ) == 1
			&& isset( $warnings['exists'] )
			&& ( $this->mDestWarningAck || $this->mForReUpload ) )
		) {
			return false;
		}

		$stashStatus = $this->mUpload->tryStashFile( $this->getUser() );
		if ( $stashStatus->isGood() ) {
			$sessionKey = $stashStatus->getValue()->getFileKey();
			$uploadWarning = 'uploadwarning-text';
		} else {
			$sessionKey = null;
			$uploadWarning = 'uploadwarning-text-nostash';
		}

		// Add styles for the warning, reused from the live preview
		$this->getOutput()->addModuleStyles( 'mediawiki.special.upload.styles' );

		$linkRenderer = $this->getLinkRenderer();
		$warningHtml = '<h2>' . $this->msg( 'uploadwarning' )->escaped() . "</h2>\n"
			. '<div class="mw-destfile-warning"><ul>';
		foreach ( $warnings as $warning => $args ) {
			if ( $warning == 'badfilename' ) {
				$this->mDesiredDestName = Title::makeTitle( NS_FILE, $args )->getText();
			}
			if ( $warning == 'exists' ) {
				$msg = "\t<li>" . self::getExistsWarning( $args ) . "</li>\n";
			} elseif ( $warning == 'no-change' ) {
				$file = $args;
				$filename = $file->getTitle()->getPrefixedText();
				$msg = "\t<li>" . wfMessage( 'fileexists-no-change', $filename )->parse() . "</li>\n";
			} elseif ( $warning == 'duplicate-version' ) {
				$file = $args[0];
				$count = count( $args );
				$filename = $file->getTitle()->getPrefixedText();
				$message = wfMessage( 'fileexists-duplicate-version' )
					->params( $filename )
					->numParams( $count );
				$msg = "\t<li>" . $message->parse() . "</li>\n";
			} elseif ( $warning == 'was-deleted' ) {
				# If the file existed before and was deleted, warn the user of this
				$ltitle = SpecialPage::getTitleFor( 'Log' );
				$llink = $linkRenderer->makeKnownLink(
					$ltitle,
					wfMessage( 'deletionlog' )->text(),
					[],
					[
						'type' => 'delete',
						'page' => Title::makeTitle( NS_FILE, $args )->getPrefixedText(),
					]
				);
				$msg = "\t<li>" . wfMessage( 'filewasdeleted' )->rawParams( $llink )->parse() . "</li>\n";
			} elseif ( $warning == 'duplicate' ) {
				$msg = $this->getDupeWarning( $args );
			} elseif ( $warning == 'duplicate-archive' ) {
				if ( $args === '' ) {
					$msg = "\t<li>" . $this->msg( 'file-deleted-duplicate-notitle' )->parse()
						. "</li>\n";
				} else {
					$msg = "\t<li>" . $this->msg( 'file-deleted-duplicate',
							Title::makeTitle( NS_FILE, $args )->getPrefixedText() )->parse()
						. "</li>\n";
				}
			} else {
				if ( $args === true ) {
					$args = [];
				} elseif ( !is_array( $args ) ) {
					$args = [ $args ];
				}
				$msg = "\t<li>" . $this->msg( $warning, $args )->parse() . "</li>\n";
			}
			$warningHtml .= $msg;
		}
		$warningHtml .= "</ul></div>\n";
		$warningHtml .= $this->msg( $uploadWarning )->parseAsBlock();

		$form = $this->getUploadForm( $warningHtml, $sessionKey, /* $hideIgnoreWarning */ true );
		$form->setSubmitText( $this->msg( 'upload-tryagain' )->text() );
		$form->addButton( [
			'name' => 'wpUploadIgnoreWarning',
			'value' => $this->msg( 'ignorewarning' )->text()
		] );
		$form->addButton( [
			'name' => 'wpCancelUpload',
			'value' => $this->msg( 'reuploaddesc' )->text()
		] );

		$this->showUploadForm( $form );

		# Indicate that we showed a form
		return true;
	}

	/**
	 * Show the upload form with error message, but do not stash the file.
	 *
	 * @param string $message HTML string
	 */
	protected function showUploadError( $message ) {
		$message = '<h2>' . $this->msg( 'uploadwarning' )->escaped() . "</h2>\n" .
			'<div class="error">' . $message . "</div>\n";
		$this->showUploadForm( $this->getUploadForm( $message ) );
	}

	/**
	 * Do the upload.
	 * Checks are made in SpecialUpload::execute()
	 */
	protected function processUpload() {
		// Fetch the file if required
		$status = $this->mUpload->fetchFile();
		if ( !$status->isOK() ) {
			$this->showUploadError( $this->getOutput()->parse( $status->getWikiText() ) );

			return;
		}
		// Avoid PHP 7.1 warning of passing $this by reference
		$upload = $this;
		if ( !Hooks::run( 'UploadForm:BeforeProcessing', [ &$upload ] ) ) {
			wfDebug( "Hook 'UploadForm:BeforeProcessing' broke processing the file.\n" );
			// This code path is deprecated. If you want to break upload processing
			// do so by hooking into the appropriate hooks in UploadBase::verifyUpload
			// and UploadBase::verifyFile.
			// If you use this hook to break uploading, the user will be returned
			// an empty form with no error message whatsoever.
			return;
		}

		// Upload verification
		$details = $this->mUpload->verifyUpload();
		if ( $details['status'] != UploadBase::OK ) {
			$this->processVerificationError( $details );

			return;
		}

		// Verify permissions for this title
		$permErrors = $this->mUpload->verifyTitlePermissions( $this->getUser() );
		if ( $permErrors !== true ) {
			$code = array_shift( $permErrors[0] );
			$this->showRecoverableUploadError( $this->msg( $code, $permErrors[0] )->parse() );

			return;
		}

		$this->mLocalFile = $this->mUpload->getLocalFile();

		// Check warnings if necessary
		if ( !$this->mIgnoreWarning ) {
			$warnings = $this->mUpload->checkWarnings();
			if ( $this->showUploadWarning( $warnings ) ) {
				return;
			}
		}

		// This is as late as we can throttle, after expected issues have been handled
		if ( UploadBase::isThrottled( $this->getUser() ) ) {
			$this->showRecoverableUploadError(
				$this->msg( 'actionthrottledtext' )->escaped()
			);
			return;
		}

		// Get the page text if this is not a reupload
		if ( !$this->mForReUpload ) {
			$pageText = self::getInitialPageText( $this->mComment, $this->mLicense,
				$this->mCopyrightStatus, $this->mCopyrightSource, $this->getConfig() );
		} else {
			$pageText = false;
		}

		$changeTags = $this->getRequest()->getVal( 'wpChangeTags' );
		if ( is_null( $changeTags ) || $changeTags === '' ) {
			$changeTags = [];
		} else {
			$changeTags = array_filter( array_map( 'trim', explode( ',', $changeTags ) ) );
		}

		if ( $changeTags ) {
			$changeTagsStatus = ChangeTags::canAddTagsAccompanyingChange(
				$changeTags, $this->getUser() );
			if ( !$changeTagsStatus->isOK() ) {
				$this->showUploadError( $this->getOutput()->parse( $changeTagsStatus->getWikiText() ) );

				return;
			}
		}

		$status = $this->mUpload->performUpload(
			$this->mComment,
			$pageText,
			$this->mWatchthis,
			$this->getUser(),
			$changeTags
		);

		if ( !$status->isGood() ) {
			$this->showRecoverableUploadError( $this->getOutput()->parse( $status->getWikiText() ) );

			return;
		}

		// Success, redirect to description page
		$this->mUploadSuccessful = true;
		// Avoid PHP 7.1 warning of passing $this by reference
		$upload = $this;
		Hooks::run( 'SpecialUploadComplete', [ &$upload ] );
		$this->getOutput()->redirect( $this->mLocalFile->getTitle()->getFullURL() );
	}

	/**
	 * Get the initial image page text based on a comment and optional file status information
	 * @param string $comment
	 * @param string $license
	 * @param string $copyStatus
	 * @param string $source
	 * @param Config $config Configuration object to load data from
	 * @return string
	 */
	public static function getInitialPageText( $comment = '', $license = '',
		$copyStatus = '', $source = '', Config $config = null
	) {
		if ( $config === null ) {
			wfDebug( __METHOD__ . ' called without a Config instance passed to it' );
			$config = MediaWikiServices::getInstance()->getMainConfig();
		}

		$msg = [];
		$forceUIMsgAsContentMsg = (array)$config->get( 'ForceUIMsgAsContentMsg' );
		/* These messages are transcluded into the actual text of the description page.
		 * Thus, forcing them as content messages makes the upload to produce an int: template
		 * instead of hardcoding it there in the uploader language.
		 */
		foreach ( [ 'license-header', 'filedesc', 'filestatus', 'filesource' ] as $msgName ) {
			if ( in_array( $msgName, $forceUIMsgAsContentMsg ) ) {
				$msg[$msgName] = "{{int:$msgName}}";
			} else {
				$msg[$msgName] = wfMessage( $msgName )->inContentLanguage()->text();
			}
		}

		$licenseText = '';
		if ( $license !== '' ) {
			$licenseText = '== ' . $msg['license-header'] . " ==\n{{" . $license . "}}\n";
		}

		$pageText = $comment . "\n";
		$headerText = '== ' . $msg['filedesc'] . ' ==';
		if ( $comment !== '' && strpos( $comment, $headerText ) === false ) {
			// prepend header to page text unless it's already there (or there is no content)
			$pageText = $headerText . "\n" . $pageText;
		}

		if ( $config->get( 'UseCopyrightUpload' ) ) {
			$pageText .= '== ' . $msg['filestatus'] . " ==\n" . $copyStatus . "\n";
			$pageText .= $licenseText;
			$pageText .= '== ' . $msg['filesource'] . " ==\n" . $source;
		} else {
			$pageText .= $licenseText;
		}

		// allow extensions to modify the content
		Hooks::run( 'UploadForm:getInitialPageText', [ &$pageText, $msg, $config ] );

		return $pageText;
	}

	/**
	 * See if we should check the 'watch this page' checkbox on the form
	 * based on the user's preferences and whether we're being asked
	 * to create a new file or update an existing one.
	 *
	 * In the case where 'watch edits' is off but 'watch creations' is on,
	 * we'll leave the box unchecked.
	 *
	 * Note that the page target can be changed *on the form*, so our check
	 * state can get out of sync.
	 * @return bool|string
	 */
	protected function getWatchCheck() {
		if ( $this->getUser()->getOption( 'watchdefault' ) ) {
			// Watch all edits!
			return true;
		}

		$desiredTitleObj = Title::makeTitleSafe( NS_FILE, $this->mDesiredDestName );
		if ( $desiredTitleObj instanceof Title && $this->getUser()->isWatched( $desiredTitleObj ) ) {
			// Already watched, don't change that
			return true;
		}

		$local = wfLocalFile( $this->mDesiredDestName );
		if ( $local && $local->exists() ) {
			// We're uploading a new version of an existing file.
			// No creation, so don't watch it if we're not already.
			return false;
		} else {
			// New page should get watched if that's our option.
			return $this->getUser()->getOption( 'watchcreations' ) ||
				$this->getUser()->getOption( 'watchuploads' );
		}
	}

	/**
	 * Provides output to the user for a result of UploadBase::verifyUpload
	 *
	 * @param array $details Result of UploadBase::verifyUpload
	 * @throws MWException
	 */
	protected function processVerificationError( $details ) {
		switch ( $details['status'] ) {
			/** Statuses that only require name changing **/
			case UploadBase::MIN_LENGTH_PARTNAME:
				$this->showRecoverableUploadError( $this->msg( 'minlength1' )->escaped() );
				break;
			case UploadBase::ILLEGAL_FILENAME:
				$this->showRecoverableUploadError( $this->msg( 'illegalfilename',
					$details['filtered'] )->parse() );
				break;
			case UploadBase::FILENAME_TOO_LONG:
				$this->showRecoverableUploadError( $this->msg( 'filename-toolong' )->escaped() );
				break;
			case UploadBase::FILETYPE_MISSING:
				$this->showRecoverableUploadError( $this->msg( 'filetype-missing' )->parse() );
				break;
			case UploadBase::WINDOWS_NONASCII_FILENAME:
				$this->showRecoverableUploadError( $this->msg( 'windows-nonascii-filename' )->parse() );
				break;

			/** Statuses that require reuploading **/
			case UploadBase::EMPTY_FILE:
				$this->showUploadError( $this->msg( 'emptyfile' )->escaped() );
				break;
			case UploadBase::FILE_TOO_LARGE:
				$this->showUploadError( $this->msg( 'largefileserver' )->escaped() );
				break;
			case UploadBase::FILETYPE_BADTYPE:
				$msg = $this->msg( 'filetype-banned-type' );
				if ( isset( $details['blacklistedExt'] ) ) {
					$msg->params( $this->getLanguage()->commaList( $details['blacklistedExt'] ) );
				} else {
					$msg->params( $details['finalExt'] );
				}
				$extensions = array_unique( $this->getConfig()->get( 'FileExtensions' ) );
				$msg->params( $this->getLanguage()->commaList( $extensions ),
					count( $extensions ) );

				// Add PLURAL support for the first parameter. This results
				// in a bit unlogical parameter sequence, but does not break
				// old translations
				if ( isset( $details['blacklistedExt'] ) ) {
					$msg->params( count( $details['blacklistedExt'] ) );
				} else {
					$msg->params( 1 );
				}

				$this->showUploadError( $msg->parse() );
				break;
			case UploadBase::VERIFICATION_ERROR:
				unset( $details['status'] );
				$code = array_shift( $details['details'] );
				$this->showUploadError( $this->msg( $code, $details['details'] )->parse() );
				break;
			case UploadBase::HOOK_ABORTED:
				if ( is_array( $details['error'] ) ) { # allow hooks to return error details in an array
					$args = $details['error'];
					$error = array_shift( $args );
				} else {
					$error = $details['error'];
					$args = null;
				}

				$this->showUploadError( $this->msg( $error, $args )->parse() );
				break;
			default:
				throw new MWException( __METHOD__ . ": Unknown value `{$details['status']}`" );
		}
	}

	/**
	 * Remove a temporarily kept file stashed by saveTempUploadedFile().
	 *
	 * @return bool Success
	 */
	protected function unsaveUploadedFile() {
		if ( !( $this->mUpload instanceof UploadFromStash ) ) {
			return true;
		}
		$success = $this->mUpload->unsaveUploadedFile();
		if ( !$success ) {
			$this->getOutput()->showFileDeleteError( $this->mUpload->getTempPath() );

			return false;
		} else {
			return true;
		}
	}

	/*** Functions for formatting warnings ***/

	/**
	 * Formats a result of UploadBase::getExistsWarning as HTML
	 * This check is static and can be done pre-upload via AJAX
	 *
	 * @param array $exists The result of UploadBase::getExistsWarning
	 * @return string Empty string if there is no warning or an HTML fragment
	 */
	public static function getExistsWarning( $exists ) {
		if ( !$exists ) {
			return '';
		}

		$file = $exists['file'];
		$filename = $file->getTitle()->getPrefixedText();
		$warnMsg = null;

		if ( $exists['warning'] == 'exists' ) {
			// Exact match
			$warnMsg = wfMessage( 'fileexists', $filename );
		} elseif ( $exists['warning'] == 'page-exists' ) {
			// Page exists but file does not
			$warnMsg = wfMessage( 'filepageexists', $filename );
		} elseif ( $exists['warning'] == 'exists-normalized' ) {
			$warnMsg = wfMessage( 'fileexists-extension', $filename,
				$exists['normalizedFile']->getTitle()->getPrefixedText() );
		} elseif ( $exists['warning'] == 'thumb' ) {
			// Swapped argument order compared with other messages for backwards compatibility
			$warnMsg = wfMessage( 'fileexists-thumbnail-yes',
				$exists['thumbFile']->getTitle()->getPrefixedText(), $filename );
		} elseif ( $exists['warning'] == 'thumb-name' ) {
			// Image w/o '180px-' does not exists, but we do not like these filenames
			$name = $file->getName();
			$badPart = substr( $name, 0, strpos( $name, '-' ) + 1 );
			$warnMsg = wfMessage( 'file-thumbnail-no', $badPart );
		} elseif ( $exists['warning'] == 'bad-prefix' ) {
			$warnMsg = wfMessage( 'filename-bad-prefix', $exists['prefix'] );
		}

		return $warnMsg ? $warnMsg->title( $file->getTitle() )->parse() : '';
	}

	/**
	 * Construct a warning and a gallery from an array of duplicate files.
	 * @param array $dupes
	 * @return string
	 */
	public function getDupeWarning( $dupes ) {
		if ( !$dupes ) {
			return '';
		}

		$gallery = ImageGalleryBase::factory( false, $this->getContext() );
		$gallery->setShowBytes( false );
		$gallery->setShowDimensions( false );
		foreach ( $dupes as $file ) {
			$gallery->add( $file->getTitle() );
		}

		return '<li>' .
			$this->msg( 'file-exists-duplicate' )->numParams( count( $dupes ) )->parse() .
			$gallery->toHTML() . "</li>\n";
	}

	protected function getGroupName() {
		return 'media';
	}

	/**
	 * Should we rotate images in the preview on Special:Upload.
	 *
	 * This controls js: mw.config.get( 'wgFileCanRotate' )
	 *
	 * @todo What about non-BitmapHandler handled files?
	 * @return bool
	 */
	public static function rotationEnabled() {
		$bitmapHandler = new BitmapHandler();
		return $bitmapHandler->autoRotateEnabled();
	}
}
