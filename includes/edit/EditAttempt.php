<?php
/**
 * Model for an edit.
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
 */

use MediaWiki\MediaWikiServices;

/**
 * Internal, context-independent processing of an edit attempt
 *
 * Note that there are a few calls to wfMessage but those are all in content language.
 */
class EditAttempt {
	/**
	 * Status: Article successfully updated
	 */
	const AS_SUCCESS_UPDATE = 200;

	/**
	 * Status: Article successfully created
	 */
	const AS_SUCCESS_NEW_ARTICLE = 201;

	/**
	 * Status: Article update aborted by a hook function
	 */
	const AS_HOOK_ERROR = 210;

	/**
	 * Status: A hook function returned an error
	 */
	const AS_HOOK_ERROR_EXPECTED = 212;

	/**
	 * Status: User is blocked from editing this page
	 */
	const AS_BLOCKED_PAGE_FOR_USER = 215;

	/**
	 * Status: Content too big (> $wgMaxArticleSize)
	 */
	const AS_CONTENT_TOO_BIG = 216;

	/**
	 * Status: this anonymous user is not allowed to edit this page
	 */
	const AS_READ_ONLY_PAGE_ANON = 218;

	/**
	 * Status: this logged in user is not allowed to edit this page
	 */
	const AS_READ_ONLY_PAGE_LOGGED = 219;

	/**
	 * Status: wiki is in readonly mode (wfReadOnly() == true)
	 */
	const AS_READ_ONLY_PAGE = 220;

	/**
	 * Status: rate limiter for action 'edit' was tripped
	 */
	const AS_RATE_LIMITED = 221;

	/**
	 * Status: article was deleted while editing and param wpRecreate == false or form
	 * was not posted
	 */
	const AS_ARTICLE_WAS_DELETED = 222;

	/**
	 * Status: user tried to create this page, but is not allowed to do that
	 * ( Title->userCan('create') == false )
	 */
	const AS_NO_CREATE_PERMISSION = 223;

	/**
	 * Status: user tried to create a blank page and wpIgnoreBlankArticle == false
	 */
	const AS_BLANK_ARTICLE = 224;

	/**
	 * Status: (non-resolvable) edit conflict
	 */
	const AS_CONFLICT_DETECTED = 225;

	/**
	 * Status: no edit summary given and the user has forceeditsummary set and the user is not
	 * editing in his own userspace or talkspace and wpIgnoreBlankSummary == false
	 */
	const AS_SUMMARY_NEEDED = 226;

	/**
	 * Status: user tried to create a new section without content
	 */
	const AS_TEXTBOX_EMPTY = 228;

	/**
	 * Status: article is too big (> $wgMaxArticleSize), after merging in the new section
	 */
	const AS_MAX_ARTICLE_SIZE_EXCEEDED = 229;

	/**
	 * Status: WikiPage::doEdit() was unsuccessful
	 */
	const AS_END = 231;

	/**
	 * Status: summary contained spam according to one of the regexes in $wgSummarySpamRegex
	 */
	const AS_SPAM_ERROR = 232;

	/**
	 * Status: anonymous user is not allowed to upload (User::isAllowed('upload') == false)
	 */
	const AS_IMAGE_REDIRECT_ANON = 233;

	/**
	 * Status: logged in user is not allowed to upload (User::isAllowed('upload') == false)
	 */
	const AS_IMAGE_REDIRECT_LOGGED = 234;

	/**
	 * Status: user tried to modify the content model, but is not allowed to do that
	 * ( User::isAllowed('editcontentmodel') == false )
	 */
	const AS_NO_CHANGE_CONTENT_MODEL = 235;

	/**
	 * Status: user tried to create self-redirect (redirect to the same article) and
	 * wpIgnoreSelfRedirect == false
	 */
	const AS_SELF_REDIRECT = 236;

	/**
	 * Status: an error relating to change tagging. Look at the message key for
	 * more details
	 */
	const AS_CHANGE_TAG_ERROR = 237;

	/**
	 * Status: can't parse content
	 */
	const AS_PARSE_ERROR = 240;

	/**
	 * Status: when changing the content model is disallowed due to
	 * $wgContentHandlerUseDB being false
	 */
	const AS_CANNOT_USE_CUSTOM_MODEL = 241;

	/**
	 * Status: when a hook aborts saving in post-merge but doesn't return any error
	 */
	const AS_HOOK_ERROR_RESUME = 242;

	/**
	 * Constructor-set
	 */
	protected $page;
	protected $title;
	protected $user;
	protected $dataWrapper;
	protected $data;

	final public function __construct( WikiPage $page, User $user, EditFormDataWrapper $dataWrapper ) {
		$this->page = $page;
		$this->title = $page->getTitle(); // shortcut
		$this->user = $user;
		$this->dataWrapper = $dataWrapper;
		$this->data = $this->dataWrapper->getData(); // shortcut
	}

	/**
	 * Attempt submission of an edit
	 *
	 * @param array $info Required information for the edit, you must provide:
	 *  - changeTags (array|null): change tags to apply to the edit
	 *  - autoSumm (string): the md5 hash of the autosummary used
	 *  - undidRevId (int): id of the undid revision, if any
	 *
	 * @param array $options The options for the edit (all are required to be set):
	 *  - allowBlankArticle (bool): whether a "blank" article is allowed to be created,
	 *    this is checked using the default content of the article
	 *  - allowBlankSummary (bool): whether a "blank" summary is allowed to be used,
	 *    this is checked with the autoSumm info above
	 *  - allowSelfRedirect (bool): whether self-redirects are allowed
	 *  - bot (bool): whether the edit should be marked as a bot edit
	 *  - recreate (bool): whether the article should be recreated if deleted during editing
	 *  - minorEdit (bool): whether the edit should be marked as minor
	 *  - watchThis (bool|null): whether the article should be watched
	 *
	 * @param array $result Array to add statuses to, currently with the
	 *   possible keys:
	 *   - spam (string): Spam string from content if any spam is detected by
	 *     matchSpamRegex.
	 *   - sectionanchor (string): Section anchor for a section save.
	 *   - nullEdit (boolean): Set if doEditContent is OK.  True if null edit,
	 *     false otherwise.
	 *   - redirect (bool): Set if doEditContent is OK. True if resulting
	 *     revision is a redirect.
	 *   - newParent (int): In case of successful conflict resolution, the
	 *     new parent revision id
	 *
	 * @return Status Status object, possibly with a message, but always with
	 *   one of the AS_* constants in $status->value,
	 */
	final public function internalAttemptSave( array $info, array $options, &$result ) {
		global $wgMaxArticleSize, $wgContentHandlerUseDB;

		$status = Status::newGood();

		if ( !$this->runPreAttemptHooks( $status ) ) {
			return $status;
		}

		$spam = $this->user->getRequest()->getText( 'wpAntispam' );
		if ( $spam !== '' ) {
			wfDebugLog(
				'SimpleAntiSpam',
				$this->user->getName() .
				' editing "' .
				$this->title->getPrefixedText() .
				'" submitted bogus field "' .
				$spam .
				'"'
			);
			$status->fatal( 'spamprotectionmatch', false );
			$status->value = self::AS_SPAM_ERROR;
			return $status;
		}

		try {
			# Construct Content object
			$textbox_content = ContentHandler::makeContent( $this->data->textbox1, $this->title,
				$this->data->contentModel, $this->data->contentFormat );
		} catch ( MWContentSerializationException $ex ) {
			$status->fatal(
				'content-failed-to-parse',
				$this->data->contentModel,
				$this->data->contentFormat,
				$ex->getMessage()
			);
			$status->value = self::AS_PARSE_ERROR;
			return $status;
		}

		# Check image redirect
		if ( $this->title->getNamespace() === NS_FILE &&
			$textbox_content->isRedirect() &&
			!$this->user->isAllowed( 'upload' )
		) {
				$code = $this->user->isAnon() ?
					self::AS_IMAGE_REDIRECT_ANON :
					self::AS_IMAGE_REDIRECT_LOGGED;
				$status->setResult( false, $code );

				return $status;
		}

		# Check for spam
		$match = EditUtilities::matchSummarySpamRegex( $this->data->summary );
		if ( $match === false && $this->data->section === 'new' ) {
			# $wgSpamRegex is enforced on this new heading/summary because, unlike
			# regular summaries, it is added to the actual wikitext.
			if ( $this->data->sectionTitle !== '' ) {
				# This branch is taken when the API is used with the 'sectiontitle' parameter.
				$match = EditUtilities::matchSpamRegex( $this->data->sectionTitle );
			} else {
				# This branch is taken when the "Add Topic" user interface is used, or the API
				# is used with the 'summary' parameter.
				$match = EditUtilities::matchSpamRegex( $this->data->summary );
			}
		}
		if ( $match === false ) {
			$match = EditUtilities::matchSpamRegex( $this->data->textbox1 );
		}
		if ( $match !== false ) {
			$result['spam'] = $match;
			$ip = $this->user->getRequest()->getIP();
			$pdbk = $this->title->getPrefixedDBkey();
			$match = str_replace( "\n", '', $match );
			wfDebugLog( 'SpamRegex', "$ip spam regex hit [[$pdbk]]: \"$match\"" );
			$status->fatal( 'spamprotectionmatch', $match );
			$status->value = self::AS_SPAM_ERROR;
			return $status;
		}

		if ( !$this->runPreMergeHooks( $status ) ) {
			return $status;
		}

		if ( $this->user->isBlockedFrom( $this->title, false ) ) {
			// Auto-block user's IP if the account was "hard" blocked
			if ( !wfReadOnly() ) {
				$this->user->spreadAnyEditBlock();
			}
			# Check block state against master, thus 'false'.
			$status->setResult( false, self::AS_BLOCKED_PAGE_FOR_USER );
			return $status;
		}

		$contentLength = strlen( $this->data->textbox1 );
		if ( $contentLength > $wgMaxArticleSize * 1024 ) {
			$status->setResult( false, self::AS_CONTENT_TOO_BIG );
			return $status;
		}

		if ( !$this->user->isAllowed( 'edit' ) ) {
			if ( $this->user->isAnon() ) {
				$status->setResult( false, self::AS_READ_ONLY_PAGE_ANON );
				return $status;
			} else {
				$status->fatal( 'readonlytext' );
				$status->value = self::AS_READ_ONLY_PAGE_LOGGED;
				return $status;
			}
		}

		$changingContentModel = false;
		if ( $this->data->contentModel !== $this->title->getContentModel() ) {
			if ( !$wgContentHandlerUseDB ) {
				$status->fatal( 'editpage-cannot-use-custom-model' );
				$status->value = self::AS_CANNOT_USE_CUSTOM_MODEL;
				return $status;
			} elseif ( !$this->user->isAllowed( 'editcontentmodel' ) ) {
				$status->setResult( false, self::AS_NO_CHANGE_CONTENT_MODEL );
				return $status;
			}
			// Make sure the user can edit the page under the new content model too
			$titleWithNewContentModel = clone $this->title;
			$titleWithNewContentModel->setContentModel( $this->data->contentModel );
			if ( !$titleWithNewContentModel->userCan( 'editcontentmodel', $this->user )
				|| !$titleWithNewContentModel->userCan( 'edit', $this->user )
			) {
				$status->setResult( false, self::AS_NO_CHANGE_CONTENT_MODEL );
				return $status;
			}

			$changingContentModel = true;
			$oldContentModel = $this->title->getContentModel();
		}

		if ( $info['changeTags'] !== null && $info['changeTags'] !== [] ) {
			$changeTagsStatus = ChangeTags::canAddTagsAccompanyingChange(
				$info['changeTags'], $this->user );
			if ( !$changeTagsStatus->isOK() ) {
				$changeTagsStatus->value = self::AS_CHANGE_TAG_ERROR;
				return $changeTagsStatus;
			}
		}

		if ( wfReadOnly() ) {
			$status->fatal( 'readonlytext' );
			$status->value = self::AS_READ_ONLY_PAGE;
			return $status;
		}
		if ( $this->user->pingLimiter() || $this->user->pingLimiter( 'linkpurge', 0 )
			|| ( $changingContentModel && $this->user->pingLimiter( 'editcontentmodel' ) )
		) {
			$status->fatal( 'actionthrottledtext' );
			$status->value = self::AS_RATE_LIMITED;
			return $status;
		}

		# If the article has been deleted while editing, don't save it without
		# confirmation
		if ( $this->dataWrapper->wasDeletedWhileEditing() && !$options['recreate'] ) {
			$status->setResult( false, self::AS_ARTICLE_WAS_DELETED );
			return $status;
		}

		# Load the page data from the master. If anything changes in the meantime,
		# we detect it by using page_latest like a token in a 1 try compare-and-swap.
		$this->page->loadPageData( 'fromdbmaster' );
		$new = !$this->page->exists();

		if ( $new ) {
			// Late check for create permission, just in case *PARANOIA*
			if ( !$this->title->userCan( 'create', $this->user ) ) {
				$status->fatal( 'nocreatetext' );
				$status->value = self::AS_NO_CREATE_PERMISSION;
				wfDebug( __METHOD__ . ": no create permission\n" );
				return $status;
			}

			// Don't save a new page if it's blank or if it's a MediaWiki:
			// message with content equivalent to default (allow empty pages
			// in this case to disable messages, see bug 50124)
			$defaultMessageText = $this->title->getDefaultMessageText();
			if ( $this->title->getNamespace() === NS_MEDIAWIKI && $defaultMessageText !== false ) {
				$defaultText = $defaultMessageText;
			} else {
				$defaultText = '';
			}

			if ( !$options['allowBlankArticle'] && $this->data->textbox1 === $defaultText ) {
				$status->fatal( 'blankarticle' );
				$status->setResult( false, self::AS_BLANK_ARTICLE );
				return $status;
			}

			if ( !$this->runPostMergeFilters( $textbox_content, $status ) ) {
				return $status;
			}

			$content = $textbox_content;

			$result['sectionanchor'] = '';
			if ( $this->data->section === 'new' ) {
				if ( $this->data->sectionTitle !== '' ) {
					// Insert the section title above the content.
					$content = $content->addSectionHeader( $this->data->sectionTitle );
				} elseif ( $this->data->summary !== '' ) {
					// Insert the section title above the content.
					$content = $content->addSectionHeader( $this->data->summary );
				}
				$this->data->summary = $this->newSectionSummary( $result['sectionanchor'] );
			}

			$status->value = self::AS_SUCCESS_NEW_ARTICLE;

		} else { # not $new

			# Article exists. Check for edit conflict.
			$isConflict = false;

			$this->page->clear(); # Force reload of dates, etc.
			$timestamp = $this->page->getTimestamp();
			$latest = $this->page->getLatest();

			wfDebug( "timestamp: {$timestamp}, edittime: {$this->data->editTime}\n" );

			// Check editRevId if set, which handles same-second timestamp collisions
			if ( $timestamp !== $this->data->editTime
				|| ( $this->data->editRevId !== null && $this->data->editRevId !== $latest )
			) {
				$isConflict = true;
				if ( $this->data->section === 'new' ) {
					if ( $this->page->getUserText() === $this->user->getName() &&
						$this->page->getComment() === $this->newSectionSummary()
					) {
						// Probably a duplicate submission of a new comment.
						// This can happen when CDN resends a request after
						// a timeout but the first one actually went through.
						wfDebug( __METHOD__
							. ": duplicate new section submission; trigger edit conflict!\n" );
					} else {
						// New comment; suppress conflict.
						$isConflict = false;
						wfDebug( __METHOD__ . ": conflict suppressed; new section\n" );
					}
				} elseif ( $this->data->section === ''
					&& Revision::userWasLastToEdit(
						DB_MASTER, $this->title->getArticleID(),
						$this->user->getId(), $this->data->editTime
					)
				) {
					# Suppress edit conflict with self, except for section edits
					# where merging is required.
					wfDebug( __METHOD__ . ": Suppressing edit conflict, same user.\n" );
					$isConflict = false;
				}
			}

			// If sectiontitle is set, use it, otherwise use the summary as the section title.
			if ( $this->data->sectionTitle !== '' ) {
				$sectionTitle = $this->data->sectionTitle;
			} else {
				$sectionTitle = $this->data->summary;
			}

			$content = null;

			if ( $isConflict ) {
				wfDebug( __METHOD__
					. ": conflict! getting section '{$this->data->section}' ".
					" for time '{$this->data->editTime}'"
					. " (id '{$this->data->editRevId}') (article time '{$timestamp}')\n" );
				// @TODO: replaceSectionAtRev() with base ID (not prior current) for ?oldid=X case
				// ...or disable section editing for non-current revisions (not exposed anyway).
				if ( $this->data->editRevId !== null ) {
					$content = $this->page->replaceSectionAtRev(
						$this->data->section,
						$textbox_content,
						$sectionTitle,
						$this->data->editRevId
					);
				} else {
					$content = $this->page->replaceSectionContent(
						$this->data->section,
						$textbox_content,
						$sectionTitle,
						$this->data->editTime
					);
				}
			} else {
				wfDebug( __METHOD__ . ": getting section '{$this->data->section}'\n" );
				$content = $this->page->replaceSectionContent(
					$this->data->section,
					$textbox_content,
					$sectionTitle
				);
			}

			if ( is_null( $content ) ) {
				wfDebug( __METHOD__ . ": activating conflict; section replace failed.\n" );
				$isConflict = true;
				$content = $textbox_content; // do not try to merge here!
			} elseif ( $isConflict ) {
				# Attempt merge
				$newParent = null;
				if ( $this->mergeChangesIntoContent( $content, $newParent ) ) {
					// Successful merge! Maybe we should tell the user the good news?
					$isConflict = false;
					$result['newParent'] = $newParent;
					wfDebug( __METHOD__ . ": Suppressing edit conflict, successful merge.\n" );
				} else {
					$this->data->section = '';
					$this->data->textbox1 = ContentHandler::getContentText( $content );
					wfDebug( __METHOD__ . ": Keeping edit conflict, failed merge.\n" );
				}
			}

			if ( $isConflict ) {
				$status->setResult( false, self::AS_CONFLICT_DETECTED );
				return $status;
			}

			if ( !$this->runPostMergeFilters( $content, $status ) ) {
				return $status;
			}

			if ( $this->data->section === 'new' ) {
				// Handle the user preference to force summaries here
				if ( !$options['allowBlankSummary'] && trim( $this->data->summary ) === '' ) {
					// or 'missingcommentheader' if $this->data->section === 'new'. Blegh
					$status->fatal( 'missingsummary' );
					$status->value = self::AS_SUMMARY_NEEDED;
					return $status;
				}

				// Do not allow the user to post an empty comment
				if ( $this->data->textbox1 === '' ) {
					$status->fatal( 'missingcommenttext' );
					$status->value = self::AS_TEXTBOX_EMPTY;
					return $status;
				}
			} elseif ( !$options['allowBlankSummary']
				&& !$content->equals( $this->page->getRevision()->getContent( Revision::RAW ) )
				&& !$content->isRedirect()
				&& md5( $this->data->summary ) === $info['autoSumm']
			) {
				$status->fatal( 'missingsummary' );
				$status->value = self::AS_SUMMARY_NEEDED;
				return $status;
			}

			# All's well
			$sectionanchor = '';
			if ( $this->data->section === 'new' ) {
				$this->data->summary = $this->newSectionSummary( $sectionanchor );
			} elseif ( $this->data->section !== '' ) {
				# Try to get a section anchor from the section source, redirect
				# to edited section if header found.
				# XXX: Might be better to integrate this into Article::replaceSectionAtRev
				# for duplicate heading checking and maybe parsing.
				$hasmatch = preg_match( "/^ *([=]{1,6})(.*?)(\\1) *\\n/i", $this->data->textbox1,
					$matches );
				# We can't deal with anchors, includes, html etc in the header for now,
				# headline would need to be parsed to improve this.
				if ( $hasmatch && strlen( $matches[2] ) > 0 ) {
					$parser = MediaWikiServices::getInstance()->getParser();
					$sectionanchor = $parser->guessLegacySectionNameFromWikiText( $matches[2] );
				}
			}
			$result['sectionanchor'] = $sectionanchor;

			// Save errors may fall down to the edit form, but we've now
			// merged the section into full text. Clear the section field
			// so that later submission of conflict forms won't try to
			// replace that into a duplicated mess.
			$this->data->textbox1 = $content->serialize( $this->data->contentFormat );
			$this->data->section = '';

			$status->value = self::AS_SUCCESS_UPDATE;
		}

		if ( !$options['allowSelfRedirect']
			&& $content->isRedirect()
			&& $content->getRedirectTarget()->equals( $this->getTitle() )
		) {
			// If the page already redirects to itself, don't warn.
			$currentTarget = $this->page->getRevision->getContent( Revision::RAW )
				->getRedirectTarget();
			if ( !$currentTarget || !$currentTarget->equals( $this->getTitle() ) ) {
				$status->fatal( 'selfredirect' );
				$status->value = self::AS_SELF_REDIRECT;
				return $status;
			}
		}

		// Check for length errors again now that the section is merged in
		$contentLength = strlen( $content->serialize( $this->data->contentFormat ) );
		if ( $contentLength > $wgMaxArticleSize * 1024 ) {
			$status->setResult( false, self::AS_MAX_ARTICLE_SIZE_EXCEEDED );
			return $status;
		}

		# Allow bots to exempt some edits from bot flagging
		$bot = $this->user->isAllowed( 'bot' ) && $options['bot'];
		$minor = $options['minorEdit'] && !$new && $this->data->section !== 'new';
		$flags = EDIT_AUTOSUMMARY |
			( $new ? EDIT_NEW : EDIT_UPDATE ) |
			( $minor ? EDIT_MINOR : 0 ) |
			( $bot ? EDIT_FORCE_BOT : 0 );

		$doEditStatus = $this->page->doEditContent(
			$content,
			$this->data->summary,
			$flags,
			false,
			$this->user,
			$content->getDefaultFormat(),
			$info['changeTags'],
			$info['undidRevId']
		);

		if ( !$doEditStatus->isOK() ) {
			// Failure from doEdit()
			// Show the edit conflict page for certain recognized errors from doEdit(),
			// but don't show it for errors from extension hooks
			$errors = $doEditStatus->getErrorsArray();
			if ( in_array( $errors[0][0],
					[ 'edit-gone-missing', 'edit-conflict', 'edit-already-exists' ] )
			) {
				// Destroys data doEdit() put in $status->value but who cares
				$doEditStatus->value = self::AS_END;
			}
			return $doEditStatus;
		}

		$result['nullEdit'] = $doEditStatus->hasMessage( 'edit-no-change' );
		if ( $result['nullEdit'] ) {
			// We don't know if it was a null edit until now, so increment here
			$this->user->pingLimiter( 'linkpurge' );
		}
		$result['redirect'] = $content->isRedirect();

		$this->updateWatchlist( $options['watchThis'] );

		// If the content model changed, add a log entry
		if ( $changingContentModel ) {
			$this->addContentModelChangeLogEntry(
				$new ? false : $oldContentModel,
				$this->data->contentModel,
				$this->data->summary
			);
		}

		return $status;
	}

	/**
	 * Return the summary to be used for a new section.
	 *
	 * @param string $sectionanchor Set to the section anchor text
	 * @return string
	 */
	private function newSectionSummary( &$sectionanchor = null ) {
		$parser = MediaWikiServices::getInstance()->getParser();

		if ( $this->data->sectionTitle !== '' ) {
			$sectionanchor = $parser->guessLegacySectionNameFromWikiText( $this->data->sectionTitle );
			// If no edit summary was specified, create one automatically from the section
			// title and have it link to the new section. Otherwise, respect the summary as
			// passed.
			if ( $this->data->summary === '' ) {
				$cleanSectionTitle = $parser->stripSectionName( $this->data->sectionTitle );
				return wfMessage( 'newsectionsummary' )->title( $this->title )
					->rawParams( $cleanSectionTitle )->inContentLanguage()->text();
			}
		} elseif ( $this->data->summary !== '' ) {
			$sectionanchor = $parser->guessLegacySectionNameFromWikiText( $this->data->summary );
			# This is a new section, so create a link to the new section
			# in the revision summary.
			$cleanSummary = $parser->stripSectionName( $this->data->summary );
			return wfMessage( 'newsectionsummary' )->title( $this->title )
				->rawParams( $cleanSummary )->inContentLanguage()->text();
		}
		return $this->data->summary;
	}

	/**
	 * @param string|false $oldModel false if the page is being newly created
	 * @param string $newModel
	 * @param string $reason
	 */
	private function addContentModelChangeLogEntry( $oldModel, $newModel, $reason ) {
		$new = $oldModel === false;
		$log = new ManualLogEntry( 'contentmodel', $new ? 'new' : 'change' );
		$log->setPerformer( $this->user );
		$log->setTarget( $this->title );
		$log->setComment( $reason );
		$log->setParameters( [
			'4::oldmodel' => $oldModel,
			'5::newmodel' => $newModel
		] );
		$logid = $log->insert();
		$log->publish( $logid );
	}

	/**
	 * Register the change of watch status
	 */
	private function updateWatchlist( $watch ) {
		if ( !$this->user->isLoggedIn() ) {
			return;
		}
		if ( $watch === null ) {
			return;
		}

		$user = $this->user;
		$title = $this->title;
		// Do this in its own transaction to reduce contention...
		DeferredUpdates::addCallableUpdate( function () use ( $user, $title, $watch ) {
			if ( $watch === $user->isWatched( $title, User::IGNORE_USER_RIGHTS ) ) {
				return; // nothing to change
			}
			WatchAction::doWatchOrUnwatch( $watch, $title, $user );
		} );
	}

	/**
	 * Attempts to do 3-way merge of edit content with a base revision
	 * and current content, in case of edit conflict, in whichever way appropriate
	 * for the content type.
	 *
	 * @since 1.21
	 *
	 * @param Content $editContent
	 *
	 * @return bool
	 */
	private function mergeChangesIntoContent( &$editContent, &$newParent ) {
		$db = wfGetDB( DB_MASTER );

		// This is the revision the editor started from
		$startingRevision = $this->getStartingRevision();
		$startingContent = $startingRevision ? $startingRevision->getContent() : null;

		if ( is_null( $startingContent ) ) {
			return false;
		}

		// The current state, we want to merge updates into it
		$currentRevision = Revision::loadFromTitle( $db, $this->title );
		$currentContent = $currentRevision ? $currentRevision->getContent() : null;

		if ( is_null( $currentContent ) ) {
			return false;
		}

		$handler = ContentHandler::getForModelID( $startingContent->getModel() );

		$result = $handler->merge3( $startingContent, $editContent, $currentContent );

		if ( $result ) {
			$editContent = $result;
			// Update parentRevId to what we just merged.
			$newParent = $currentRevision->getId();
			return true;
		}

		return false;
	}

	/**
	 * @return Revision Current version when the edit was started
	 */
	private function getStartingRevision() {
		$db = wfGetDB( DB_MASTER );
		return $this->data->editRevId
			? Revision::newFromId( $this->data->editRevId, Revision::READ_LATEST )
			: Revision::loadFromTimestamp( $db, $this->title, $this->data->editTime );
	}

	private function runPreAttemptHooks( Status $status ) {
		$hookArgs = [ $this->page, $this->user, $this->data, $status ];
		if ( !Hooks::run( 'EditAttemptPreAttemptSave', $hookArgs ) ) {
			wfDebug( "Hook 'EditAttemptPreAttemptSave' aborted article saving\n" );
			if ( $status->isOK() ) {
				throw new MWException( 'A hook aborted the edit before attempting save but ' .
					'didn\'t return a fatal status.' );
			}
			if ( !$status->getErrors() ) {
				throw new MWException( 'A hook aborted the edit before attempting save but ' .
					'didn\'t return any error.' );
			}
			$status->value = self::AS_HOOK_ERROR;
			return false;
		} elseif ( !$status->isOK() ) {
			if ( !$status->getErrors() ) {
				throw new MWException( 'A hook returned a fatal status before attempting save but ' .
					'didn\'t return any error.' );
			}
			$status->value = self::AS_HOOK_ERROR_EXPECTED;
			return false;
		}
		return true;
	}

	private function runPreMergeHooks( Status $status ) {
		if ( !Hooks::run(
			'EditAttemptEditFilter',
			[ $this->page, $this->user, $this->data, $status ] )
		) {
			if ( $status->isOK() ) {
				throw new MWException( 'A hook aborted the edit when attempting save but ' .
					'didn\'t return a fatal status.' );
			}
			if ( !$status->getErrors() ) {
				throw new MWException( 'A hook aborted the edit when attempting save but ' .
					'didn\'t return any error.' );
			}
			// Error messages etc. could be handled within the hook...
			$status->value = self::AS_HOOK_ERROR;
			return false;
		} elseif ( !$status->isOK() ) {
			if ( !$status->getErrors() ) {
				throw new MWException( 'A hook returned a fatal status before attempting save but ' .
					'didn\'t return any error.' );
			}
			// ...or the hook could be expecting us to produce an error
			$status->value = self::AS_HOOK_ERROR_EXPECTED;
			return false;
		}
		return true;
	}

	/**
	 * Run hooks that can filter edits just before they get saved.
	 *
	 * @param Content $content The Content to filter.
	 * @param Status $status For reporting the outcome to the caller
	 *
	 * @return bool
	 */
	private function runPostMergeFilters( Content $content, Status $status ) {
		if ( !Hooks::run( 'EditAttemptFilterMergedContent',
			[ $this->page, $this->user, $this->data, $content, $status ] )
		) {
			// Error messages
			if ( $status->isGood() ) {
				// Allowing a good status here makes it possible for the hook
				// to cause a return to the edit page without Editor::$hookStatus
				// being set. This is used by ConfirmEdit to display a captcha
				// without any error message cruft.
				$status->value = self::AS_HOOK_ERROR_RESUME;
			} elseif ( $status->isOK() ) {
				// The hook aborted but didn't fatal, this means we should display
				// a nice edit form but include the errors set in the hook.
				$status->setResult( false, self::AS_HOOK_ERROR_EXPECTED );
			} elseif ( !$status->getErrors() ) {
				throw new MWException( 'A hook filtered the edit when attempting ' .
					'save but didn\'t return any error.' );
			} else {
				// The hook aborted and set a fatal, don't display the edit form,
				// the extension should handle everything.
				$status->value = self::AS_HOOK_ERROR;
			}
			return false;
		} elseif ( !$status->isOK() ) {
			if ( !$status->getErrors() ) {
				throw new MWException( 'A hook returned a fatal status when attempting save but ' .
					'didn\'t return any error.' );
			}
			// The hook didn't abort but the status is not ok, display form
			// and include errors.
			$status->value = self::AS_HOOK_ERROR_EXPECTED;
			return false;
		}

		return true;
	}
}
