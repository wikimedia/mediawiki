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

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;

/**
 * Form for handling uploads and special page.
 *
 * @ingroup SpecialPage
 * @ingroup Upload
 */
class SpecialUpload extends SpecialPage {
	/**
	 * Constructor : initialise object
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
			if ( !Hooks::run( 'UploadForm:initial', [ &$this ] ) ) {
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
			LogEventsList::showLogExtract( $delNotice, [ 'delete', 'move' ],
				$desiredTitleObj,
				'', [ 'lim' => 10,
					'conds' => [ "log_action != 'revision'" ],
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
		} else {
			$sessionKey = null;
			// TODO Add a warning message about the failure to stash here?
		}
		$message = '<h2>' . $this->msg( 'uploaderror' )->escaped() . "</h2>\n" .
			'<div class="error">' . $message . "</div>\n";

		$form = $this->getUploadForm( $message, $sessionKey );
		$form->setSubmitText( $this->msg( 'upload-tryagain' )->escaped() );
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
		} else {
			$sessionKey = null;
			// TODO Add a warning message about the failure to stash here?
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
		$warningHtml .= $this->msg( 'uploadwarning-text' )->parseAsBlock();

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

		if ( !Hooks::run( 'UploadForm:BeforeProcessing', [ &$this ] ) ) {
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
		Hooks::run( 'SpecialUploadComplete', [ &$this ] );
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
			$config = ConfigFactory::getDefaultInstance()->makeConfig( 'main' );
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

		if ( $config->get( 'UseCopyrightUpload' ) ) {
			$licensetxt = '';
			if ( $license != '' ) {
				$licensetxt = '== ' . $msg['license-header'] . " ==\n" . '{{' . $license . '}}' . "\n";
			}
			$pageText = '== ' . $msg['filedesc'] . " ==\n" . $comment . "\n" .
				'== ' . $msg['filestatus'] . " ==\n" . $copyStatus . "\n" .
				"$licensetxt" .
				'== ' . $msg['filesource'] . " ==\n" . $source;
		} else {
			if ( $license != '' ) {
				$filedesc = $comment == '' ? '' : '== ' . $msg['filedesc'] . " ==\n" . $comment . "\n";
					$pageText = $filedesc .
					'== ' . $msg['license-header'] . " ==\n" . '{{' . $license . '}}' . "\n";
			} else {
				$pageText = $comment;
			}
		}

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
	 */
	public static function rotationEnabled() {
		$bitmapHandler = new BitmapHandler();
		return $bitmapHandler->autoRotateEnabled();
	}
}

/**
 * Sub class of HTMLForm that provides the form section of SpecialUpload
 */
class UploadForm extends HTMLForm {
	protected $mWatch;
	protected $mForReUpload;
	protected $mSessionKey;
	protected $mHideIgnoreWarning;
	protected $mDestWarningAck;
	protected $mDestFile;

	protected $mComment;
	protected $mTextTop;
	protected $mTextAfterSummary;

	protected $mSourceIds;

	protected $mMaxFileSize = [];

	protected $mMaxUploadSize = [];

	public function __construct( array $options = [], IContextSource $context = null,
		LinkRenderer $linkRenderer = null
	) {
		if ( $context instanceof IContextSource ) {
			$this->setContext( $context );
		}

		if ( !$linkRenderer ) {
			$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		}

		$this->mWatch = !empty( $options['watch'] );
		$this->mForReUpload = !empty( $options['forreupload'] );
		$this->mSessionKey = isset( $options['sessionkey'] ) ? $options['sessionkey'] : '';
		$this->mHideIgnoreWarning = !empty( $options['hideignorewarning'] );
		$this->mDestWarningAck = !empty( $options['destwarningack'] );
		$this->mDestFile = isset( $options['destfile'] ) ? $options['destfile'] : '';

		$this->mComment = isset( $options['description'] ) ?
			$options['description'] : '';

		$this->mTextTop = isset( $options['texttop'] )
			? $options['texttop'] : '';

		$this->mTextAfterSummary = isset( $options['textaftersummary'] )
			? $options['textaftersummary'] : '';

		$sourceDescriptor = $this->getSourceSection();
		$descriptor = $sourceDescriptor
			+ $this->getDescriptionSection()
			+ $this->getOptionsSection();

		Hooks::run( 'UploadFormInitDescriptor', [ &$descriptor ] );
		parent::__construct( $descriptor, $context, 'upload' );

		# Add a link to edit MediaWiki:Licenses
		if ( $this->getUser()->isAllowed( 'editinterface' ) ) {
			$this->getOutput()->addModuleStyles( 'mediawiki.special.upload.styles' );
			$licensesLink = $linkRenderer->makeKnownLink(
				$this->msg( 'licenses' )->inContentLanguage()->getTitle(),
				$this->msg( 'licenses-edit' )->text(),
				[],
				[ 'action' => 'edit' ]
			);
			$editLicenses = '<p class="mw-upload-editlicenses">' . $licensesLink . '</p>';
			$this->addFooterText( $editLicenses, 'description' );
		}

		# Set some form properties
		$this->setSubmitText( $this->msg( 'uploadbtn' )->text() );
		$this->setSubmitName( 'wpUpload' );
		# Used message keys: 'accesskey-upload', 'tooltip-upload'
		$this->setSubmitTooltip( 'upload' );
		$this->setId( 'mw-upload-form' );

		# Build a list of IDs for javascript insertion
		$this->mSourceIds = [];
		foreach ( $sourceDescriptor as $field ) {
			if ( !empty( $field['id'] ) ) {
				$this->mSourceIds[] = $field['id'];
			}
		}
	}

	/**
	 * Get the descriptor of the fieldset that contains the file source
	 * selection. The section is 'source'
	 *
	 * @return array Descriptor array
	 */
	protected function getSourceSection() {
		if ( $this->mSessionKey ) {
			return [
				'SessionKey' => [
					'type' => 'hidden',
					'default' => $this->mSessionKey,
				],
				'SourceType' => [
					'type' => 'hidden',
					'default' => 'Stash',
				],
			];
		}

		$canUploadByUrl = UploadFromUrl::isEnabled()
			&& ( UploadFromUrl::isAllowed( $this->getUser() ) === true )
			&& $this->getConfig()->get( 'CopyUploadsFromSpecialUpload' );
		$radio = $canUploadByUrl;
		$selectedSourceType = strtolower( $this->getRequest()->getText( 'wpSourceType', 'File' ) );

		$descriptor = [];
		if ( $this->mTextTop ) {
			$descriptor['UploadFormTextTop'] = [
				'type' => 'info',
				'section' => 'source',
				'default' => $this->mTextTop,
				'raw' => true,
			];
		}

		$this->mMaxUploadSize['file'] = min(
			UploadBase::getMaxUploadSize( 'file' ),
			UploadBase::getMaxPhpUploadSize()
		);

		$help = $this->msg( 'upload-maxfilesize',
				$this->getContext()->getLanguage()->formatSize( $this->mMaxUploadSize['file'] )
			)->parse();

		// If the user can also upload by URL, there are 2 different file size limits.
		// This extra message helps stress which limit corresponds to what.
		if ( $canUploadByUrl ) {
			$help .= $this->msg( 'word-separator' )->escaped();
			$help .= $this->msg( 'upload_source_file' )->parse();
		}

		$descriptor['UploadFile'] = [
			'class' => 'UploadSourceField',
			'section' => 'source',
			'type' => 'file',
			'id' => 'wpUploadFile',
			'radio-id' => 'wpSourceTypeFile',
			'label-message' => 'sourcefilename',
			'upload-type' => 'File',
			'radio' => &$radio,
			'help' => $help,
			'checked' => $selectedSourceType == 'file',
		];

		if ( $canUploadByUrl ) {
			$this->mMaxUploadSize['url'] = UploadBase::getMaxUploadSize( 'url' );
			$descriptor['UploadFileURL'] = [
				'class' => 'UploadSourceField',
				'section' => 'source',
				'id' => 'wpUploadFileURL',
				'radio-id' => 'wpSourceTypeurl',
				'label-message' => 'sourceurl',
				'upload-type' => 'url',
				'radio' => &$radio,
				'help' => $this->msg( 'upload-maxfilesize',
					$this->getContext()->getLanguage()->formatSize( $this->mMaxUploadSize['url'] )
				)->parse() .
					$this->msg( 'word-separator' )->escaped() .
					$this->msg( 'upload_source_url' )->parse(),
				'checked' => $selectedSourceType == 'url',
			];
		}
		Hooks::run( 'UploadFormSourceDescriptors', [ &$descriptor, &$radio, $selectedSourceType ] );

		$descriptor['Extensions'] = [
			'type' => 'info',
			'section' => 'source',
			'default' => $this->getExtensionsMessage(),
			'raw' => true,
		];

		return $descriptor;
	}

	/**
	 * Get the messages indicating which extensions are preferred and prohibitted.
	 *
	 * @return string HTML string containing the message
	 */
	protected function getExtensionsMessage() {
		# Print a list of allowed file extensions, if so configured.  We ignore
		# MIME type here, it's incomprehensible to most people and too long.
		$config = $this->getConfig();

		if ( $config->get( 'CheckFileExtensions' ) ) {
			$fileExtensions = array_unique( $config->get( 'FileExtensions' ) );
			if ( $config->get( 'StrictFileExtensions' ) ) {
				# Everything not permitted is banned
				$extensionsList =
					'<div id="mw-upload-permitted">' .
					$this->msg( 'upload-permitted' )
						->params( $this->getLanguage()->commaList( $fileExtensions ) )
						->numParams( count( $fileExtensions ) )
						->parseAsBlock() .
					"</div>\n";
			} else {
				# We have to list both preferred and prohibited
				$fileBlacklist = array_unique( $config->get( 'FileBlacklist' ) );
				$extensionsList =
					'<div id="mw-upload-preferred">' .
						$this->msg( 'upload-preferred' )
							->params( $this->getLanguage()->commaList( $fileExtensions ) )
							->numParams( count( $fileExtensions ) )
							->parseAsBlock() .
					"</div>\n" .
					'<div id="mw-upload-prohibited">' .
						$this->msg( 'upload-prohibited' )
							->params( $this->getLanguage()->commaList( $fileBlacklist ) )
							->numParams( count( $fileBlacklist ) )
							->parseAsBlock() .
					"</div>\n";
			}
		} else {
			# Everything is permitted.
			$extensionsList = '';
		}

		return $extensionsList;
	}

	/**
	 * Get the descriptor of the fieldset that contains the file description
	 * input. The section is 'description'
	 *
	 * @return array Descriptor array
	 */
	protected function getDescriptionSection() {
		$config = $this->getConfig();
		if ( $this->mSessionKey ) {
			$stash = RepoGroup::singleton()->getLocalRepo()->getUploadStash( $this->getUser() );
			try {
				$file = $stash->getFile( $this->mSessionKey );
			} catch ( Exception $e ) {
				$file = null;
			}
			if ( $file ) {
				global $wgContLang;

				$mto = $file->transform( [ 'width' => 120 ] );
				if ( $mto ) {
					$this->addHeaderText(
						'<div class="thumb t' . $wgContLang->alignEnd() . '">' .
						Html::element( 'img', [
							'src' => $mto->getUrl(),
							'class' => 'thumbimage',
						] ) . '</div>', 'description' );
				}
			}
		}

		$descriptor = [
			'DestFile' => [
				'type' => 'text',
				'section' => 'description',
				'id' => 'wpDestFile',
				'label-message' => 'destfilename',
				'size' => 60,
				'default' => $this->mDestFile,
				# @todo FIXME: Hack to work around poor handling of the 'default' option in HTMLForm
				'nodata' => strval( $this->mDestFile ) !== '',
			],
			'UploadDescription' => [
				'type' => 'textarea',
				'section' => 'description',
				'id' => 'wpUploadDescription',
				'label-message' => $this->mForReUpload
					? 'filereuploadsummary'
					: 'fileuploadsummary',
				'default' => $this->mComment,
				'cols' => $this->getUser()->getIntOption( 'cols' ),
				'rows' => 8,
			]
		];
		if ( $this->mTextAfterSummary ) {
			$descriptor['UploadFormTextAfterSummary'] = [
				'type' => 'info',
				'section' => 'description',
				'default' => $this->mTextAfterSummary,
				'raw' => true,
			];
		}

		$descriptor += [
			'EditTools' => [
				'type' => 'edittools',
				'section' => 'description',
				'message' => 'edittools-upload',
			]
		];

		if ( $this->mForReUpload ) {
			$descriptor['DestFile']['readonly'] = true;
		} else {
			$descriptor['License'] = [
				'type' => 'select',
				'class' => 'Licenses',
				'section' => 'description',
				'id' => 'wpLicense',
				'label-message' => 'license',
			];
		}

		if ( $config->get( 'UseCopyrightUpload' ) ) {
			$descriptor['UploadCopyStatus'] = [
				'type' => 'text',
				'section' => 'description',
				'id' => 'wpUploadCopyStatus',
				'label-message' => 'filestatus',
			];
			$descriptor['UploadSource'] = [
				'type' => 'text',
				'section' => 'description',
				'id' => 'wpUploadSource',
				'label-message' => 'filesource',
			];
		}

		return $descriptor;
	}

	/**
	 * Get the descriptor of the fieldset that contains the upload options,
	 * such as "watch this file". The section is 'options'
	 *
	 * @return array Descriptor array
	 */
	protected function getOptionsSection() {
		$user = $this->getUser();
		if ( $user->isLoggedIn() ) {
			$descriptor = [
				'Watchthis' => [
					'type' => 'check',
					'id' => 'wpWatchthis',
					'label-message' => 'watchthisupload',
					'section' => 'options',
					'default' => $this->mWatch,
				]
			];
		}
		if ( !$this->mHideIgnoreWarning ) {
			$descriptor['IgnoreWarning'] = [
				'type' => 'check',
				'id' => 'wpIgnoreWarning',
				'label-message' => 'ignorewarnings',
				'section' => 'options',
			];
		}

		$descriptor['DestFileWarningAck'] = [
			'type' => 'hidden',
			'id' => 'wpDestFileWarningAck',
			'default' => $this->mDestWarningAck ? '1' : '',
		];

		if ( $this->mForReUpload ) {
			$descriptor['ForReUpload'] = [
				'type' => 'hidden',
				'id' => 'wpForReUpload',
				'default' => '1',
			];
		}

		return $descriptor;
	}

	/**
	 * Add the upload JS and show the form.
	 */
	public function show() {
		$this->addUploadJS();
		parent::show();
	}

	/**
	 * Add upload JS to the OutputPage
	 */
	protected function addUploadJS() {
		$config = $this->getConfig();

		$useAjaxDestCheck = $config->get( 'UseAjax' ) && $config->get( 'AjaxUploadDestCheck' );
		$useAjaxLicensePreview = $config->get( 'UseAjax' ) &&
			$config->get( 'AjaxLicensePreview' ) && $config->get( 'EnableAPI' );
		$this->mMaxUploadSize['*'] = UploadBase::getMaxUploadSize();

		$scriptVars = [
			'wgAjaxUploadDestCheck' => $useAjaxDestCheck,
			'wgAjaxLicensePreview' => $useAjaxLicensePreview,
			'wgUploadAutoFill' => !$this->mForReUpload &&
				// If we received mDestFile from the request, don't autofill
				// the wpDestFile textbox
				$this->mDestFile === '',
			'wgUploadSourceIds' => $this->mSourceIds,
			'wgCheckFileExtensions' => $config->get( 'CheckFileExtensions' ),
			'wgStrictFileExtensions' => $config->get( 'StrictFileExtensions' ),
			'wgFileExtensions' => array_values( array_unique( $config->get( 'FileExtensions' ) ) ),
			'wgCapitalizeUploads' => MWNamespace::isCapitalized( NS_FILE ),
			'wgMaxUploadSize' => $this->mMaxUploadSize,
			'wgFileCanRotate' => SpecialUpload::rotationEnabled(),
		];

		$out = $this->getOutput();
		$out->addJsConfigVars( $scriptVars );

		$out->addModules( [
			'mediawiki.action.edit', // For <charinsert> support
			'mediawiki.special.upload', // Extras for thumbnail and license preview.
		] );
	}

	/**
	 * Empty function; submission is handled elsewhere.
	 *
	 * @return bool False
	 */
	function trySubmit() {
		return false;
	}
}

/**
 * A form field that contains a radio box in the label
 */
class UploadSourceField extends HTMLTextField {

	/**
	 * @param array $cellAttributes
	 * @return string
	 */
	function getLabelHtml( $cellAttributes = [] ) {
		$id = $this->mParams['id'];
		$label = Html::rawElement( 'label', [ 'for' => $id ], $this->mLabel );

		if ( !empty( $this->mParams['radio'] ) ) {
			if ( isset( $this->mParams['radio-id'] ) ) {
				$radioId = $this->mParams['radio-id'];
			} else {
				// Old way. For the benefit of extensions that do not define
				// the 'radio-id' key.
				$radioId = 'wpSourceType' . $this->mParams['upload-type'];
			}

			$attribs = [
				'name' => 'wpSourceType',
				'type' => 'radio',
				'id' => $radioId,
				'value' => $this->mParams['upload-type'],
			];

			if ( !empty( $this->mParams['checked'] ) ) {
				$attribs['checked'] = 'checked';
			}

			$label .= Html::element( 'input', $attribs );
		}

		return Html::rawElement( 'td', [ 'class' => 'mw-label' ] + $cellAttributes, $label );
	}

	/**
	 * @return int
	 */
	function getSize() {
		return isset( $this->mParams['size'] )
			? $this->mParams['size']
			: 60;
	}
}
