<?php
/**
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
 */

namespace MediaWiki\Specials;

use BitmapHandler;
use ImageGalleryBase;
use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Config\Config;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Exception\UserBlockedError;
use MediaWiki\FileRepo\File\LocalFile;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\JobQueue\Jobs\UploadFromUrlJob;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\WikiFilePage;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\User;
use MediaWiki\Watchlist\WatchlistManager;
use Psr\Log\LoggerInterface;
use UnexpectedValueException;
use UploadBase;
use UploadForm;
use UploadFromStash;

/**
 * Form for uploading media files.
 *
 * @ingroup SpecialPage
 * @ingroup Upload
 */
class SpecialUpload extends SpecialPage {

	private LocalRepo $localRepo;
	private UserOptionsLookup $userOptionsLookup;
	private NamespaceInfo $nsInfo;
	private WatchlistManager $watchlistManager;
	private JobQueueGroup $jobQueueGroup;
	private LoggerInterface $log;

	public function __construct(
		?RepoGroup $repoGroup = null,
		?UserOptionsLookup $userOptionsLookup = null,
		?NamespaceInfo $nsInfo = null,
		?WatchlistManager $watchlistManager = null
	) {
		parent::__construct( 'Upload', 'upload' );
		// This class is extended and therefor fallback to global state - T265300
		$services = MediaWikiServices::getInstance();
		$this->jobQueueGroup = $services->getJobQueueGroup();
		$repoGroup ??= $services->getRepoGroup();
		$this->localRepo = $repoGroup->getLocalRepo();
		$this->userOptionsLookup = $userOptionsLookup ?? $services->getUserOptionsLookup();
		$this->nsInfo = $nsInfo ?? $services->getNamespaceInfo();
		$this->watchlistManager = $watchlistManager ?? $services->getWatchlistManager();
		$this->log = LoggerFactory::getInstance( 'SpecialUpload' );
	}

	private function addMessageBoxStyling() {
		$this->getOutput()->addModuleStyles( 'mediawiki.codex.messagebox.styles' );
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	// Misc variables

	/** @var WebRequest|FauxRequest The request this form is supposed to handle */
	public $mRequest;
	/** @var string */
	public $mSourceType;

	/** @var string The cache key to use to retreive the status of your async upload */
	public $mCacheKey;

	/** @var UploadBase */
	public $mUpload;

	/** @var LocalFile */
	public $mLocalFile;
	/** @var bool */
	public $mUploadClicked;

	// User input variables from the "description" section

	/** @var string The requested target file name */
	public $mDesiredDestName;
	/** @var string */
	public $mComment;
	/** @var string */
	public $mLicense;

	// User input variables from the root section

	/** @var bool */
	public $mIgnoreWarning;
	/** @var bool */
	public $mWatchthis;
	/** @var string */
	public $mCopyrightStatus;
	/** @var string */
	public $mCopyrightSource;

	// Hidden variables

	/** @var string */
	public $mDestWarningAck;

	/** @var bool The user followed an "overwrite this file" link */
	public $mForReUpload;

	/** @var bool The user clicked "Cancel and return to upload form" button */
	public $mCancelUpload;
	/** @var bool */
	public $mTokenOk;

	/** @var bool Subclasses can use this to determine whether a file was uploaded */
	public $mUploadSuccessful = false;

	/** @var string Raw html injection point for hooks not using HTMLForm */
	public $uploadFormTextTop;
	/** @var string Raw html injection point for hooks not using HTMLForm */
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
		$this->mWatchthis = $request->getBool( 'wpWatchthis' ) && $this->getUser()->isRegistered();
		$this->mCopyrightStatus = $request->getText( 'wpUploadCopyStatus' );
		$this->mCopyrightSource = $request->getText( 'wpUploadSource' );

		$this->mForReUpload = $request->getBool( 'wpForReUpload' ); // updating a file

		$commentDefault = '';
		$commentMsg = $this->msg( 'upload-default-description' )->inContentLanguage();
		if ( !$this->mForReUpload && !$commentMsg->isDisabled() ) {
			$commentDefault = $commentMsg->plain();
		}
		$this->mComment = $request->getText( 'wpUploadDescription', $commentDefault );

		$this->mCancelUpload = $request->getCheck( 'wpCancelUpload' )
			|| $request->getCheck( 'wpReUpload' ); // b/w compat

		// If it was posted check for the token (no remote POST'ing with user credentials)
		$token = $request->getVal( 'wpEditToken' );
		$this->mTokenOk = $this->getUser()->matchEditToken( $token );

		// If this is an upload from Url and we're allowing async processing,
		// check for the presence of the cache key parameter, or compute it. Else, it should be empty.
		if ( $this->isAsyncUpload() ) {
			$this->mCacheKey = \UploadFromUrl::getCacheKeyFromRequest( $request );
		} else {
			$this->mCacheKey = '';
		}

		$this->uploadFormTextTop = '';
		$this->uploadFormTextAfterSummary = '';
	}

	/**
	 * Check if the current request is an async upload by url request
	 *
	 * @return bool
	 */
	protected function isAsyncUpload() {
		return $this->mSourceType === 'url'
			&& $this->getConfig()->get( MainConfigNames::EnableAsyncUploads )
			&& $this->getConfig()->get( MainConfigNames::EnableAsyncUploadsByURL );
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
	 * @param string|null $par
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
		if ( $user->isBlockedFromUpload() ) {
			throw new UserBlockedError(
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable Block is checked and not null
				$user->getBlock(),
				$user,
				$this->getLanguage(),
				$this->getRequest()->getIP()
			);
		}

		# Check whether we actually want to allow changing stuff
		$this->checkReadOnly();

		$this->loadRequest();

		# Unsave the temporary file in case this was a cancelled upload
		if ( $this->mCancelUpload && !$this->unsaveUploadedFile() ) {
			# Something went wrong, so unsaveUploadedFile showed a warning
			return;
		}

		# If we have a cache key, show the upload status.
		if ( $this->mTokenOk && $this->mCacheKey !== '' ) {
			if ( $this->mUpload && $this->mUploadClicked && !$this->mCancelUpload ) {
				# If the user clicked the upload button, we need to process the upload
				$this->processAsyncUpload();
			} else {
				# Show the upload status
				$this->showUploadStatus( $user );
			}
		} elseif (
			# Process upload or show a form
			$this->mTokenOk && !$this->mCancelUpload &&
			( $this->mUpload && $this->mUploadClicked )
		) {
			$this->processUpload();
		} else {
			# Backwards compatibility hook
			if ( !$this->getHookRunner()->onUploadForm_initial( $this ) ) {
				wfDebug( "Hook 'UploadForm:initial' broke output of the upload form" );

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
	 * Show the upload status
	 *
	 * @param User $user The owner of the upload
	 */
	protected function showUploadStatus( $user ) {
		// first, let's fetch the status from the main stash
		$progress = UploadBase::getSessionStatus( $user, $this->mCacheKey );
		if ( !$progress ) {
			$progress = [ 'status' => Status::newFatal( 'invalid-cache-key' ) ];
		}
		$this->log->debug( 'Upload status: stage {stage}, result {result}', $progress );

		$status = $progress['status'] ?? Status::newFatal( 'invalid-cache-key' );
		$stage = $progress['stage'] ?? 'unknown';
		$result = $progress['result'] ?? 'unknown';
		switch ( $stage ) {
			case 'publish':
				switch ( $result ) {
					case 'Success':
						// The upload is done. Check the result and either show the form with the error
						// occurred, or redirect to the file itself
						// Success, redirect to description page
						$this->mUploadSuccessful = true;
						$this->getHookRunner()->onSpecialUploadComplete( $this );
						// Redirect to the destination URL, but purge the cache of the file description page first
						// TODO: understand why this is needed
						$title = Title::makeTitleSafe( NS_FILE, $this->mRequest->getText( 'wpDestFile' ) );
						if ( $title ) {
							$this->log->debug( 'Purging page', [ 'title' => $title->getText() ] );
							$page = new WikiFilePage( $title );
							$page->doPurge();
						}
						$this->getOutput()->redirect( $this->mRequest->getText( 'wpDestUrl' ) );
						break;
					case 'Warning':
						$this->showUploadWarning( UploadBase::unserializeWarnings( $progress['warnings'] ) );
						break;
					case 'Failure':
						$details = $status->getValue();
						// Verification failed.
						if ( is_array( $details ) && isset( $details['verification'] ) ) {
							$this->processVerificationError( $details['verification'] );
						} else {
							$this->showUploadError( $this->getOutput()->parseAsInterface(
								$status->getWikiText( false, false, $this->getLanguage() ) )
							);
						}
						break;
					case 'Poll':
						$this->showUploadProgress(
							[ 'active' => true, 'msg' => 'upload-progress-processing' ]
						);
						break;
					default:
						// unknown result, just show a generic error
						$this->showUploadError( $this->getOutput()->parseAsInterface(
							$status->getWikiText( false, false, $this->getLanguage() ) )
						);
						break;
				}
				break;
			case 'queued':
				// show stalled progress bar
				$this->showUploadProgress( [ 'active' => false, 'msg' => 'upload-progress-queued' ] );
				break;
			case 'fetching':
				switch ( $result ) {
					case 'Success':
						// The file is being downloaded from a URL
						// TODO: show active progress bar saying we're downloading the file
						$this->showUploadProgress( [ 'active' => true, 'msg' => 'upload-progress-downloading' ] );
						break;
					case 'Failure':
						// downloading failed
						$this->showUploadError( $this->getOutput()->parseAsInterface(
							$status->getWikiText( false, false, $this->getLanguage() ) )
						);
						break;
					default:
						// unknown result, just show a generic error
						$this->showUploadError( $this->getOutput()->parseAsInterface(
							$status->getWikiText( false, false, $this->getLanguage() ) )
						);
						break;
				}
				break;
			default:
				// unknown status, just show a generic error
				if ( $status->isOK() ) {
					$status = Status::newFatal( 'upload-progress-unknown' );
				}
				$statusmsg = $this->getOutput()->parseAsInterface(
					$status->getWikiText( false, false, $this->getLanguage() )
				);
				$message = '<h2>' . $this->msg( 'uploaderror' )->escaped() . '</h2>' . HTML::errorBox( $statusmsg );
				$this->addMessageBoxStyling();
				$this->showUploadForm( $this->getUploadForm( $message ) );
				break;
		}
	}

	/**
	 * Show the upload progress in a form, with a refresh button
	 *
	 * This is used when the upload is being processed asynchronously. We're
	 * forced to use a refresh button because we need to poll the primary mainstash.
	 * See UploadBase::getSessionStatus for more information.
	 *
	 * @param array $options
	 * @return void
	 */
	private function showUploadProgress( $options ) {
		// $isActive = $options['active'] ?? false;
		//$progressBarProperty = $isActive ? '' : 'disabled';
		$message = $this->msg( $options['msg'] )->escaped();
		$destUrl = $this->mRequest->getText( 'wpDestUrl', '' );
		if ( !$destUrl && $this->mUpload ) {
			if ( !$this->mLocalFile ) {
				$this->mLocalFile = $this->mUpload->getLocalFile();
			}
			// This probably means the title is bad, so we can't get the URL
			// but we need to wait for the job to execute.
			if ( $this->mLocalFile === null ) {
				$destUrl = '';
			} else {
				$destUrl = $this->mLocalFile->getTitle()->getFullURL();
			}
		}

		$destName = $this->mDesiredDestName;
		if ( !$destName ) {
			$destName = $this->mRequest->getText( 'wpDestFile' );
		}

		// Needed if we have warnings to show
		$sourceURL = $this->mRequest->getText( 'wpUploadFileURL' );

		$form = new HTMLForm( [
			'CacheKey' => [
				'type' => 'hidden',
				'default' => $this->mCacheKey,
			],
			'SourceType' => [
				'type' => 'hidden',
				'default' => $this->mSourceType,
			],
			'DestUrl' => [
				'type' => 'hidden',
				'default' => $destUrl,
			],
			'DestFile' => [
				'type' => 'hidden',
				'default' => $destName,
			],
			'UploadFileURL' => [
				'type' => 'hidden',
				'default' => $sourceURL,
			],
		], $this->getContext(), 'uploadProgress' );
		$form->setSubmitText( $this->msg( 'upload-refresh' )->escaped() );
		// TODO: use codex, add a progress bar
		//$preHtml = "<cdx-progress-bar aria--label='upload progressbar' $progressBarProperty />";
		$preHtml = "<div id='upload-progress-message'>$message</div>";
		$form->addPreHtml( $preHtml );
		$form->setSubmitCallback(
			static function ( $formData ) {
				return true;
			}
		);
		$form->prepareForm();
		$this->getOutput()->addHTML( $form->getHTML( false ) );
	}

	/**
	 * Show the main upload form
	 *
	 * @param HTMLForm|string $form An HTMLForm instance or HTML string to show
	 */
	protected function showUploadForm( $form ) {
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
	 * @param string|null $sessionKey Session key in case this is a stashed upload
	 * @param bool $hideIgnoreWarning Whether to hide "ignore warning" check box
	 * @return UploadForm
	 */
	protected function getUploadForm( $message = '', $sessionKey = '', $hideIgnoreWarning = false ) {
		# Initialize form
		$form = new UploadForm(
			[
				'watch' => $this->getWatchCheck(),
				'forreupload' => $this->mForReUpload,
				'sessionkey' => $sessionKey,
				'hideignorewarning' => $hideIgnoreWarning,
				'destwarningack' => (bool)$this->mDestWarningAck,

				'description' => $this->mComment,
				'texttop' => $this->uploadFormTextTop,
				'textaftersummary' => $this->uploadFormTextAfterSummary,
				'destfile' => $this->mDesiredDestName,
			],
			$this->getContext(),
			$this->getLinkRenderer(),
			$this->localRepo,
			$this->getContentLanguage(),
			$this->nsInfo,
			$this->getHookContainer()
		);
		$form->setTitle( $this->getPageTitle() ); // Remove subpage

		# Check the token, but only if necessary
		if (
			!$this->mTokenOk && !$this->mCancelUpload &&
			( $this->mUpload && $this->mUploadClicked )
		) {
			$form->addPreHtml( $this->msg( 'session_fail_preview' )->parse() );
		}

		# Give a notice if the user is uploading a file that has been deleted or moved
		# Note that this is independent from the message 'filewasdeleted'
		$desiredTitleObj = Title::makeTitleSafe( NS_FILE, $this->mDesiredDestName );
		$delNotice = ''; // empty by default
		if ( $desiredTitleObj instanceof Title && !$desiredTitleObj->exists() ) {
			LogEventsList::showLogExtract( $delNotice, [ 'delete', 'move' ],
				$desiredTitleObj,
				'', [ 'lim' => 10,
					'conds' => [ $this->localRepo->getReplicaDB()->expr( 'log_action', '!=', 'revision' ) ],
					'showIfEmpty' => false,
					'msgKey' => [ 'upload-recreate-warning' ] ]
			);
		}
		$form->addPreHtml( $delNotice );

		# Add text to form
		$form->addPreHtml( '<div id="uploadtext">' .
			$this->msg( 'uploadtext', [ $this->mDesiredDestName ] )->parseAsBlock() .
			'</div>' );
		# Add upload error message
		$form->addPreHtml( $message );

		# Add footer to form
		$uploadFooter = $this->msg( 'uploadfooter' );
		if ( !$uploadFooter->isDisabled() ) {
			$form->addPostHtml( '<div id="mw-upload-footer-message">'
				. $uploadFooter->parseAsBlock() . "</div>\n" );
		}

		return $form;
	}

	/**
	 * Stashes the upload and shows the main upload form.
	 *
	 * Note: only errors that can be handled by changing the name or
	 * description should be redirected here. It should be assumed that the
	 * file itself is sensible and has passed UploadBase::verifyFile. This
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
		$message = '<h2>' . $this->msg( 'uploaderror' )->escaped() . '</h2>' .
			Html::errorBox( $message );

		$this->addMessageBoxStyling();
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

		if ( $this->mUpload ) {
			$stashStatus = $this->mUpload->tryStashFile( $this->getUser() );
			if ( $stashStatus->isGood() ) {
				$sessionKey = $stashStatus->getValue()->getFileKey();
				$uploadWarning = 'uploadwarning-text';
			} else {
				$sessionKey = null;
				$uploadWarning = 'uploadwarning-text-nostash';
			}
		} else {
			$sessionKey = null;
			$uploadWarning = 'uploadwarning-text-nostash';
		}

		// Add styles for the warning, reused from the live preview
		$this->getOutput()->addModuleStyles( 'mediawiki.special' );

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
				$msg = "\t<li>" . $this->msg( 'fileexists-no-change', $filename )->parse() . "</li>\n";
			} elseif ( $warning == 'duplicate-version' ) {
				$file = $args[0];
				$count = count( $args );
				$filename = $file->getTitle()->getPrefixedText();
				$message = $this->msg( 'fileexists-duplicate-version' )
					->params( $filename )
					->numParams( $count );
				$msg = "\t<li>" . $message->parse() . "</li>\n";
			} elseif ( $warning == 'was-deleted' ) {
				# If the file existed before and was deleted, warn the user of this
				$ltitle = SpecialPage::getTitleFor( 'Log' );
				$llink = $linkRenderer->makeKnownLink(
					$ltitle,
					$this->msg( 'deletionlog' )->text(),
					[],
					[
						'type' => 'delete',
						'page' => Title::makeTitle( NS_FILE, $args )->getPrefixedText(),
					]
				);
				$msg = "\t<li>" . $this->msg( 'filewasdeleted' )->rawParams( $llink )->parse() . "</li>\n";
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
		$form->setSubmitTextMsg( 'upload-tryagain' );
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
		$message = '<h2>' . $this->msg( 'uploadwarning' )->escaped() . '</h2>' .
			Html::errorBox( $message );
		$this->showUploadForm( $this->getUploadForm( $message ) );
		$this->addMessageBoxStyling();
	}

	/**
	 * Common steps for processing uploads
	 *
	 * @param Status $fetchFileStatus
	 * @return bool
	 */
	protected function performUploadChecks( $fetchFileStatus ): bool {
		if ( !$fetchFileStatus->isOK() ) {
			$this->showUploadError( $this->getOutput()->parseAsInterface(
				$fetchFileStatus->getWikiText( false, false, $this->getLanguage() )
			) );

			return false;
		}
		if ( !$this->getHookRunner()->onUploadForm_BeforeProcessing( $this ) ) {
			wfDebug( "Hook 'UploadForm:BeforeProcessing' broke processing the file." );
			// This code path is deprecated. If you want to break upload processing
			// do so by hooking into the appropriate hooks in UploadBase::verifyUpload
			// and UploadBase::verifyFile.
			// If you use this hook to break uploading, the user will be returned
			// an empty form with no error message whatsoever.
			return false;
		}

		// Upload verification
		// If this is an asynchronous upload-by-url, skip the verification
		if ( $this->isAsyncUpload() ) {
			return true;
		}
		$details = $this->mUpload->verifyUpload();
		if ( $details['status'] != UploadBase::OK ) {
			$this->processVerificationError( $details );

			return false;
		}

		// Verify permissions for this title
		$user = $this->getUser();
		$status = $this->mUpload->authorizeUpload( $user );
		if ( !$status->isGood() ) {
			$this->showRecoverableUploadError(
				$this->getOutput()->parseAsInterface(
					Status::wrap( $status )->getWikiText( false, false, $this->getLanguage() )
				)
			);

			return false;
		}

		$this->mLocalFile = $this->mUpload->getLocalFile();

		// Check warnings if necessary
		if ( !$this->mIgnoreWarning ) {
			$warnings = $this->mUpload->checkWarnings( $user );
			if ( $this->showUploadWarning( $warnings ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Get the page text and tags for the upload
	 *
	 * @return array|null
	 */
	protected function getPageTextAndTags() {
		// Get the page text if this is not a reupload
		if ( !$this->mForReUpload ) {
			$pageText = self::getInitialPageText( $this->mComment, $this->mLicense,
				$this->mCopyrightStatus, $this->mCopyrightSource,
				$this->getConfig() );
		} else {
			$pageText = false;
		}
		$changeTags = $this->getRequest()->getVal( 'wpChangeTags' );
		if ( $changeTags === null || $changeTags === '' ) {
			$changeTags = [];
		} else {
			$changeTags = array_filter( array_map( 'trim', explode( ',', $changeTags ) ) );
		}
		if ( $changeTags ) {
			$changeTagsStatus = ChangeTags::canAddTagsAccompanyingChange(
				$changeTags, $this->getUser() );
			if ( !$changeTagsStatus->isOK() ) {
				$this->showUploadError( $this->getOutput()->parseAsInterface(
					$changeTagsStatus->getWikiText( false, false, $this->getLanguage() )
				) );

				return null;
			}
		}
		return [ $pageText, $changeTags ];
	}

	/**
	 * Do the upload.
	 * Checks are made in SpecialUpload::execute()
	 */
	protected function processUpload() {
		// Fetch the file if required
		$status = $this->mUpload->fetchFile();
		if ( !$this->performUploadChecks( $status ) ) {
			return;
		}
		$user = $this->getUser();
		$pageAndTags = $this->getPageTextAndTags();
		if ( $pageAndTags === null ) {
			return;
		}
		[ $pageText, $changeTags ] = $pageAndTags;

		$status = $this->mUpload->performUpload(
			$this->mComment,
			$pageText,
			$this->mWatchthis,
			$user,
			$changeTags
		);

		if ( !$status->isGood() ) {
			$this->showRecoverableUploadError(
				$this->getOutput()->parseAsInterface(
					$status->getWikiText( false, false, $this->getLanguage() )
				)
			);

			return;
		}

		// Success, redirect to description page
		$this->mUploadSuccessful = true;
		$this->getHookRunner()->onSpecialUploadComplete( $this );
		$this->getOutput()->redirect( $this->mLocalFile->getTitle()->getFullURL() );
	}

	/**
	 * Process an asynchronous upload
	 */
	protected function processAsyncUpload() {
		// Ensure the upload we're dealing with is an UploadFromUrl
		if ( !$this->mUpload instanceof \UploadFromUrl ) {
			$this->showUploadError( $this->msg( 'uploaderror' )->escaped() );

			return;
		}
		// check we can fetch the file
		$status = $this->mUpload->canFetchFile();
		if ( !$this->performUploadChecks( $status ) ) {
			$this->log->debug( 'Upload failed verification: {error}', [ 'error' => $status ] );
			return;
		}

		$pageAndTags = $this->getPageTextAndTags();
		if ( $pageAndTags === null ) {
			return;
		}
		[ $pageText, $changeTags ] = $pageAndTags;

		// Create a new job to process the upload from url
		$job = new UploadFromUrlJob(
			[
				'filename' => $this->mUpload->getDesiredDestName(),
				'url' => $this->mUpload->getUrl(),
				'comment' => $this->mComment,
				'tags' => $changeTags,
				'text' => $pageText,
				'watch' => $this->mWatchthis,
				'watchlistexpiry' => null,
				'session' => $this->getContext()->exportSession(),
				'reupload' => $this->mForReUpload,
				'ignorewarnings' => $this->mIgnoreWarning,
			]
		);
		// Save the session status
		$cacheKey = $job->getCacheKey();
		UploadBase::setSessionStatus( $this->getUser(), $cacheKey, [
			'status' => Status::newGood(),
			'stage' => 'queued',
			'result' => 'Poll'
		] );
		$this->log->info( "Submitting UploadFromUrlJob for {filename}",
			[ 'filename' => $this->mUpload->getDesiredDestName() ]
		);
		// Submit the job
		$this->jobQueueGroup->push( $job );
		// Show the upload status
		$this->showUploadStatus( $this->getUser() );
	}

	/**
	 * Get the initial image page text based on a comment and optional file status information
	 * @param string $comment
	 * @param string $license
	 * @param string $copyStatus
	 * @param string $source
	 * @param Config|null $config Configuration object to load data from
	 * @return string
	 */
	public static function getInitialPageText( $comment = '', $license = '',
		$copyStatus = '', $source = '', ?Config $config = null
	) {
		if ( $config === null ) {
			wfDebug( __METHOD__ . ' called without a Config instance passed to it' );
			$config = MediaWikiServices::getInstance()->getMainConfig();
		}

		$msg = [];
		$forceUIMsgAsContentMsg = (array)$config->get( MainConfigNames::ForceUIMsgAsContentMsg );
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
		if ( $comment !== '' && !str_contains( $comment, $headerText ) ) {
			// prepend header to page text unless it's already there (or there is no content)
			$pageText = $headerText . "\n" . $pageText;
		}

		if ( $config->get( MainConfigNames::UseCopyrightUpload ) ) {
			$pageText .= '== ' . $msg['filestatus'] . " ==\n" . $copyStatus . "\n";
			$pageText .= $licenseText;
			$pageText .= '== ' . $msg['filesource'] . " ==\n" . $source;
		} else {
			$pageText .= $licenseText;
		}

		// allow extensions to modify the content
		( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
			->onUploadForm_getInitialPageText( $pageText, $msg, $config );

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
	 * @return bool
	 */
	protected function getWatchCheck() {
		$user = $this->getUser();
		if ( $this->userOptionsLookup->getBoolOption( $user, 'watchdefault' ) ) {
			// Watch all edits!
			return true;
		}

		$desiredTitleObj = Title::makeTitleSafe( NS_FILE, $this->mDesiredDestName );
		if ( $desiredTitleObj instanceof Title &&
			$this->watchlistManager->isWatched( $user, $desiredTitleObj ) ) {
			// Already watched, don't change that
			return true;
		}

		$local = $this->localRepo->newFile( $this->mDesiredDestName );
		if ( $local && $local->exists() ) {
			// We're uploading a new version of an existing file.
			// No creation, so don't watch it if we're not already.
			return false;
		} else {
			// New page should get watched if that's our option.
			return $this->userOptionsLookup->getBoolOption( $user, 'watchcreations' ) ||
				$this->userOptionsLookup->getBoolOption( $user, 'watchuploads' );
		}
	}

	/**
	 * Provides output to the user for a result of UploadBase::verifyUpload
	 *
	 * @param array $details Result of UploadBase::verifyUpload
	 */
	protected function processVerificationError( $details ) {
		switch ( $details['status'] ) {
			/** Statuses that only require name changing */
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

			/** Statuses that require reuploading */
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
				$extensions =
					array_unique( $this->getConfig()->get( MainConfigNames::FileExtensions ) );
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
			default:
				throw new UnexpectedValueException( __METHOD__ . ": Unknown value `{$details['status']}`" );
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
			$this->getOutput()->showErrorPage(
				'internalerror',
				'filedeleteerror',
				[ $this->mUpload->getTempPath() ]
			);

			return false;
		} else {
			return true;
		}
	}

	/** Functions for formatting warnings */

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

		return $warnMsg ? $warnMsg->page( $file->getTitle() )->parse() : '';
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

	/** @inheritDoc */
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

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUpload::class, 'SpecialUpload' );
