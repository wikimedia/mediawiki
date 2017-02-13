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

use MediaWiki\Logger\LoggerFactory;

/**
 * Internal, context-independent processing of an edit request
 *
 * Note that there are a few calls to wfMessage but those are all in content language.
 */
class EditModel {
	/**
	 * Constructor-set
	 */
	protected $page;
	protected $title;
	protected $user;
	protected $data;

	private $contentModelOverride = false;

	/**
	 * Magic accessor, prevents invalid field access (mostly for debugging)
	 * @param string $field
	 */
	public function __get( $field ) {
		throw new InvalidArgumentException( "$field is not a valid EditModel property." );
	}

	/**
	 * Magic mutator, prevents invalid field mutation (mostly for debugging)
	 * @param string $field
	 * @param mixed $value
	 */
	public function __set( $field, $value ) {
		throw new InvalidArgumentException( "$field is not a valid EditModel property." );
	}

	public function __construct( WikiPage $page, User $user, EditFormData $data ) {
		$this->page = $page;
		$this->title = $page->getTitle(); // shortcut
		$this->user = $user;
		$this->data = $data;
	}

	/**
	 * Allow editing of content that supports API direct editing, but not general
	 * direct editing. Set to false by default.
	 */
	public function enableContentModelOverride() {
		$this->contentModelOverride = true;
	}

	/**
	 * Attempt submission (no UI)
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
	 *
	 * @return Status Status object, possibly with a message, but always with
	 *   one of the AS_* constants in $status->value,
	 *
	 * @todo FIXME: This interface is TERRIBLE, but hard to get rid of due to
	 *   various error display idiosyncrasies. There are also lots of cases
	 *   where error metadata is set in the object and retrieved later instead
	 *   of being returned, e.g. AS_CONTENT_TOO_BIG and
	 *   AS_BLOCKED_PAGE_FOR_USER. All that stuff needs to be cleaned up some
	 * time.
	 */
	final public function internalAttemptSave( array $info, array $options, &$result ) {
		global $wgParser, $wgMaxArticleSize;
		global $wgContentHandlerUseDB;

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
			$status->value = EditAttempt::AS_SPAM_ERROR;
			return $status;
		}

		try {
			# Construct Content object
			$textbox_content = $this->toEditContent( $this->data->textbox1 );
		} catch ( MWContentSerializationException $ex ) {
			$status->fatal(
				'content-failed-to-parse',
				$this->data->contentModel,
				$this->data->contentFormat,
				$ex->getMessage()
			);
			$status->value = EditAttempt::AS_PARSE_ERROR;
			return $status;
		}

		# Check image redirect
		if ( $this->title->getNamespace() == NS_FILE &&
			$textbox_content->isRedirect() &&
			!$this->user->isAllowed( 'upload' )
		) {
				$code = $this->user->isAnon() ? EditAttempt::AS_IMAGE_REDIRECT_ANON : EditAttempt::AS_IMAGE_REDIRECT_LOGGED;
				$status->setResult( false, $code );

				return $status;
		}

		# Check for spam
		$match = EditUtilities::matchSummarySpamRegex( $this->data->summary );
		if ( $match === false && $this->data->section == 'new' ) {
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
			$status->value = EditAttempt::AS_SPAM_ERROR;
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
			$status->setResult( false, EditAttempt::AS_BLOCKED_PAGE_FOR_USER );
			return $status;
		}

		$contentLength = strlen( $this->data->textbox1 );
		if ( $contentLength > $wgMaxArticleSize * 1024 ) {
			$status->setResult( false, EditAttempt::AS_CONTENT_TOO_BIG );
			return $status;
		}

		if ( !$this->user->isAllowed( 'edit' ) ) {
			if ( $this->user->isAnon() ) {
				$status->setResult( false, EditAttempt::AS_READ_ONLY_PAGE_ANON );
				return $status;
			} else {
				$status->fatal( 'readonlytext' );
				$status->value = EditAttempt::AS_READ_ONLY_PAGE_LOGGED;
				return $status;
			}
		}

		$changingContentModel = false;
		if ( $this->data->contentModel !== $this->title->getContentModel() ) {
			if ( !$wgContentHandlerUseDB ) {
				$status->fatal( 'editpage-cannot-use-custom-model' );
				$status->value = EditAttempt::AS_CANNOT_USE_CUSTOM_MODEL;
				return $status;
			} elseif ( !$this->user->isAllowed( 'editcontentmodel' ) ) {
				$status->setResult( false, EditAttempt::AS_NO_CHANGE_CONTENT_MODEL );
				return $status;
			}
			// Make sure the user can edit the page under the new content model too
			$titleWithNewContentModel = clone $this->title;
			$titleWithNewContentModel->setContentModel( $this->data->contentModel );
			if ( !$titleWithNewContentModel->userCan( 'editcontentmodel', $this->user )
				|| !$titleWithNewContentModel->userCan( 'edit', $this->user )
			) {
				$status->setResult( false, EditAttempt::AS_NO_CHANGE_CONTENT_MODEL );
				return $status;
			}

			$changingContentModel = true;
			$oldContentModel = $this->title->getContentModel();
		}

		if ( $this->data->changeTags ) {
			$changeTagsStatus = ChangeTags::canAddTagsAccompanyingChange(
				$this->data->changeTags, $this->user );
			if ( !$changeTagsStatus->isOK() ) {
				$changeTagsStatus->value = EditAttempt::AS_CHANGE_TAG_ERROR;
				return $changeTagsStatus;
			}
		}

		if ( wfReadOnly() ) {
			$status->fatal( 'readonlytext' );
			$status->value = EditAttempt::AS_READ_ONLY_PAGE;
			return $status;
		}
		if ( $this->user->pingLimiter() || $this->user->pingLimiter( 'linkpurge', 0 )
			|| ( $changingContentModel && $this->user->pingLimiter( 'editcontentmodel' ) )
		) {
			$status->fatal( 'actionthrottledtext' );
			$status->value = EditAttempt::AS_RATE_LIMITED;
			return $status;
		}

		# If the article has been deleted while editing, don't save it without
		# confirmation
		if ( $info['wasDeleted'] && !$this->data->recreate ) {
			$status->setResult( false, EditAttempt::AS_ARTICLE_WAS_DELETED );
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
				$status->value = EditAttempt::AS_NO_CREATE_PERMISSION;
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
				$status->setResult( false, EditAttempt::AS_BLANK_ARTICLE );
				return $status;
			}

			if ( !$this->runPostMergeFilters( $textbox_content, $status ) ) {
				return $status;
			}

			$content = $textbox_content;

			$result['sectionanchor'] = '';
			if ( $this->data->section == 'new' ) {
				if ( $this->data->sectionTitle !== '' ) {
					// Insert the section title above the content.
					$content = $content->addSectionHeader( $this->data->sectionTitle );
				} elseif ( $this->data->summary !== '' ) {
					// Insert the section title above the content.
					$content = $content->addSectionHeader( $this->data->summary );
				}
				$this->data->summary = $this->newSectionSummary( $result['sectionanchor'] );
			}

			$status->value = EditAttempt::AS_SUCCESS_NEW_ARTICLE;

		} else { # not $new

			# Article exists. Check for edit conflict.
			$isConflict = false;

			$this->page->clear(); # Force reload of dates, etc.
			$timestamp = $this->page->getTimestamp();
			$latest = $this->page->getLatest();

			wfDebug( "timestamp: {$timestamp}, edittime: {$this->data->editTime}\n" );

			// Check editRevId if set, which handles same-second timestamp collisions
			if ( $timestamp != $this->data->editTime
				|| ( $this->data->editRevId !== null && $this->data->editRevId != $latest )
			) {
				$isConflict = true;
				if ( $this->data->section == 'new' ) {
					if ( $this->page->getUserText() == $this->user->getName() &&
						$this->page->getComment() == $this->newSectionSummary()
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
				} elseif ( $this->data->section == ''
					&& Revision::userWasLastToEdit(
						DB_MASTER, $this->title->getArticleID(),
						$this->user->getId(), $this->data->editTime
					)
				) {
					# Suppress edit conflict with self, except for section edits where merging is required.
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
					. ": conflict! getting section '{$this->data->section}' for time '{$this->data->editTime}'"
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
				if ( $this->mergeChangesIntoContent( $content ) ) {
					// Successful merge! Maybe we should tell the user the good news?
					$isConflict = false;
					wfDebug( __METHOD__ . ": Suppressing edit conflict, successful merge.\n" );
				} else {
					$this->data->section = '';
					$this->data->textbox1 = ContentHandler::getContentText( $content );
					wfDebug( __METHOD__ . ": Keeping edit conflict, failed merge.\n" );
				}
			}

			if ( $isConflict ) {
				$status->setResult( false, EditAttempt::AS_CONFLICT_DETECTED );
				return $status;
			}

			if ( !$this->runPostMergeFilters( $content, $status ) ) {
				return $status;
			}

			if ( $this->data->section == 'new' ) {
				// Handle the user preference to force summaries here
				if ( !$options['allowBlankSummary'] && trim( $this->data->summary ) == '' ) {
					$status->fatal( 'missingsummary' ); // or 'missingcommentheader' if $this->data->section == 'new'. Blegh
					$status->value = EditAttempt::AS_SUMMARY_NEEDED;
					return $status;
				}

				// Do not allow the user to post an empty comment
				if ( $this->data->textbox1 == '' ) {
					$status->fatal( 'missingcommenttext' );
					$status->value = EditAttempt::AS_TEXTBOX_EMPTY;
					return $status;
				}
			} elseif ( !$options['allowBlankSummary']
				&& !$content->equals( $this->getCurrentContent() )
				&& !$content->isRedirect()
				&& md5( $this->data->summary ) == $info['autoSumm']
			) {
				$status->fatal( 'missingsummary' );
				$status->value = EditAttempt::AS_SUMMARY_NEEDED;
				return $status;
			}

			# All's well
			$sectionanchor = '';
			if ( $this->data->section == 'new' ) {
				$this->data->summary = $this->newSectionSummary( $sectionanchor );
			} elseif ( $this->data->section != '' ) {
				# Try to get a section anchor from the section source, redirect
				# to edited section if header found.
				# XXX: Might be better to integrate this into Article::replaceSectionAtRev
				# for duplicate heading checking and maybe parsing.
				$hasmatch = preg_match( "/^ *([=]{1,6})(.*?)(\\1) *\\n/i", $this->data->textbox1, $matches );
				# We can't deal with anchors, includes, html etc in the header for now,
				# headline would need to be parsed to improve this.
				if ( $hasmatch && strlen( $matches[2] ) > 0 ) {
					$sectionanchor = $wgParser->guessLegacySectionNameFromWikiText( $matches[2] );
				}
			}
			$result['sectionanchor'] = $sectionanchor;

			// Save errors may fall down to the edit form, but we've now
			// merged the section into full text. Clear the section field
			// so that later submission of conflict forms won't try to
			// replace that into a duplicated mess.
			$this->data->textbox1 = $this->toEditText( $content );
			$this->data->section = '';

			$status->value = EditAttempt::AS_SUCCESS_UPDATE;
		}

		if ( !$options['allowSelfRedirect']
			&& $content->isRedirect()
			&& $content->getRedirectTarget()->equals( $this->getTitle() )
		) {
			// If the page already redirects to itself, don't warn.
			$currentTarget = $this->getCurrentContent()->getRedirectTarget();
			if ( !$currentTarget || !$currentTarget->equals( $this->getTitle() ) ) {
				$status->fatal( 'selfredirect' );
				$status->value = EditAttempt::AS_SELF_REDIRECT;
				return $status;
			}
		}

		// Check for length errors again now that the section is merged in
		$contentLength = strlen( $this->toEditText( $content ) );
		if ( $contentLength > $wgMaxArticleSize * 1024 ) {
			$status->setResult( false, EditAttempt::AS_MAX_ARTICLE_SIZE_EXCEEDED );
			return $status;
		}

		# Allow bots to exempt some edits from bot flagging
		$bot = $this->user->isAllowed( 'bot' ) && $this->data->bot;
		$minor = $this->data->minorEdit && !$new && $this->data->section !== 'new';
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
			$this->data->changeTags,
			$this->data->undidRevId
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
				$doEditStatus->value = EditAttempt::AS_END;
			}
			return $doEditStatus;
		}

		$result['nullEdit'] = $doEditStatus->hasMessage( 'edit-no-change' );
		if ( $result['nullEdit'] ) {
			// We don't know if it was a null edit until now, so increment here
			$this->user->pingLimiter( 'linkpurge' );
		}
		$result['redirect'] = $content->isRedirect();

		$this->updateWatchlist();

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
	 * Returns if the given content model is editable.
	 *
	 * @param string $modelId The ID of the content model to test. Use CONTENT_MODEL_XXX constants.
	 * @return bool
	 * @throws MWException If $modelId has no known handler
	 */
	protected function isSupportedContentModel( $modelId ) {
		return $this->contentModelOverride ||
			ContentHandler::getForModelID( $modelId )->supportsDirectEditing();
	}

	/**
	 * Gets an editable textual representation of $content.
	 * The textual representation can be turned by into a Content object by the
	 * toEditContent() method.
	 *
	 * If $content is null or false or a string, $content is returned unchanged.
	 *
	 * If the given Content object is not of a type that can be edited using
	 * the text base EditPage, an exception will be raised. Set
	 * $this->allowNonTextContent to true to allow editing of non-textual
	 * content.
	 *
	 * @param Content|null|bool|string $content
	 * @return string The editable text form of the content.
	 *
	 * @throws MWException If $content is not an instance of TextContent and
	 *   $this->allowNonTextContent is not true.
	 */
	final public function toEditText( $content ) {
		if ( $content === null || $content === false || is_string( $content ) ) {
			return $content;
		}

		if ( !$this->isSupportedContentModel( $content->getModel() ) ) {
			throw new MWException( 'This content model is not supported: ' . $content->getModel() );
		}

		return $content->serialize( $this->data->contentFormat );
	}

	/**
	 * Turns the given text into a Content object by unserializing it.
	 *
	 * If the resulting Content object is not of a type that can be edited using
	 * the text base EditPage, an exception will be raised. Set
	 * $this->allowNonTextContent to true to allow editing of non-textual
	 * content.
	 *
	 * @param string|null|bool $text Text to unserialize
	 * @return Content|bool|null The content object created from $text. If $text was false
	 *   or null, false resp. null will be  returned instead.
	 *
	 * @throws MWException If unserializing the text results in a Content
	 *   object that is not an instance of TextContent and
	 *   $this->allowNonTextContent is not true.
	 */
	final public function toEditContent( $text ) {
		if ( $text === false || $text === null ) {
			return $text;
		}

		$content = ContentHandler::makeContent( $text, $this->title,
			$this->data->contentModel, $this->data->contentFormat );

		if ( !$this->isSupportedContentModel( $content->getModel() ) ) {
			throw new MWException( 'This content model is not supported: ' . $content->getModel() );
		}

		return $content;
	}

	/**
	 * Get the current content of the page. This is basically similar to
	 * WikiPage::getContent( Revision::RAW ) except that when the page doesn't exist an empty
	 * content object is returned instead of null.
	 *
	 * @since 1.21
	 * @return Content
	 */
	final public function getCurrentContent() {
		$rev = $this->page->getRevision();
		$content = $rev ? $rev->getContent( Revision::RAW ) : null;

		if ( $content === false || $content === null ) {
			if ( !$this->data->contentModel ) {
				$this->data->contentModel = $this->title->getContentModel();
			}
			$handler = ContentHandler::getForModelID( $this->data->contentModel );

			return $handler->makeEmptyContent();
		} elseif ( !$this->data->undidRevId ) {
			// Content models should always be the same since we error
			// out if they are different before this point (in ->edit()).
			// The exception being, during an undo, the current revision might
			// differ from the prior revision.
			$logger = LoggerFactory::getInstance( 'editpage' );
			if ( $this->data->contentModel !== $rev->getContentModel() ) {
				$logger->warning( "Overriding content model from current edit {prev} to {new}", [
					'prev' => $this->data->contentModel,
					'new' => $rev->getContentModel(),
					'title' => $this->title->getPrefixedDBkey(),
					'method' => __METHOD__
				] );
				$this->data->contentModel = $rev->getContentModel();
			}

			// Given that the content models should match, the current selected
			// format should be supported.
			if ( !$content->isSupportedFormat( $this->data->contentFormat ) ) {
				$logger->warning( "Current revision content format unsupported. Overriding {prev} to {new}", [

					'prev' => $this->data->contentFormat,
					'new' => $rev->getContentFormat(),
					'title' => $this->title->getPrefixedDBkey(),
					'method' => __METHOD__
				] );
				$this->data->contentFormat = $rev->getContentFormat();
			}
		}
		return $content;
	}

	/**
	 * Return the summary to be used for a new section.
	 *
	 * @param string $sectionanchor Set to the section anchor text
	 * @return string
	 */
	private function newSectionSummary( &$sectionanchor = null ) {
		global $wgParser;

		if ( $this->data->sectionTitle !== '' ) {
			$sectionanchor = $wgParser->guessLegacySectionNameFromWikiText( $this->data->sectionTitle );
			// If no edit summary was specified, create one automatically from the section
			// title and have it link to the new section. Otherwise, respect the summary as
			// passed.
			if ( $this->data->summary === '' ) {
				$cleanSectionTitle = $wgParser->stripSectionName( $this->data->sectionTitle );
				return wfMessage( 'newsectionsummary' )->title( $this->title )
					->rawParams( $cleanSectionTitle )->inContentLanguage()->text();
			}
		} elseif ( $this->data->summary !== '' ) {
			$sectionanchor = $wgParser->guessLegacySectionNameFromWikiText( $this->data->summary );
			# This is a new section, so create a link to the new section
			# in the revision summary.
			$cleanSummary = $wgParser->stripSectionName( $this->data->summary );
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
	protected function addContentModelChangeLogEntry( $oldModel, $newModel, $reason ) {
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
	private function updateWatchlist() {
		if ( !$this->user->isLoggedIn() ) {
			return;
		}

		$user = $this->user;
		$title = $this->title;
		$watch = $this->data->watchThis;
		// Do this in its own transaction to reduce contention...
		DeferredUpdates::addCallableUpdate( function () use ( $user, $title, $watch ) {
			if ( $watch == $user->isWatched( $title, User::IGNORE_USER_RIGHTS ) ) {
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
	private function mergeChangesIntoContent( &$editContent ) {
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
			$this->data->parentRevId = $currentRevision->getId();
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
		if ( !Hooks::run( 'EditModel::attemptSave', [ $this->page, $this->user, $this->data, $status ] ) ) {
			wfDebug( "Hook 'EditModel::attemptSave' aborted article saving\n" );
			if ( $status->isOK() ) {
				throw new MWException( 'A hook aborted the edit before attempting save but ' .
					'didn\'t return a fatal status.' );
			}
			if ( !$status->getErrors() ) {
				throw new MWException( 'A hook aborted the edit before attempting save but ' .
					'didn\'t return any error.' );
			}
			$status->value = EditAttempt::AS_HOOK_ERROR;
			return false;
		} elseif ( !$status->isOK() ) {
			if ( !$status->getErrors() ) {
				throw new MWException( 'A hook returned a fatal status before attempting save but ' .
					'didn\'t return any error.' );
			}
			$status->value = EditAttempt::AS_HOOK_ERROR_EXPECTED;
			return false;
		}
		return true;
	}

	private function runPreMergeHooks( Status $status ) {
		if ( !Hooks::run(
			'EditModelEditFilter',
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
			$status->value = EditAttempt::AS_HOOK_ERROR;
			return false;
		} elseif ( !$status->isOK() ) {
			if ( !$status->getErrors() ) {
				throw new MWException( 'A hook returned a fatal status before attempting save but ' .
					'didn\'t return any error.' );
			}
			// ...or the hook could be expecting us to produce an error
			$status->value = EditAttempt::AS_HOOK_ERROR_EXPECTED;
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
		if ( !Hooks::run( 'EditModelFilterMergedContent',
			[ $this->page, $this->user, $this->data, $content, $status ] )
		) {
			// Error messages
			if ( $status->isGood() ) {
				// Allowing a good status here makes it possible for the hook
				// to cause a return to the edit page without Editor::$hookStatus
				// being set. This is used by ConfirmEdit to display a captcha
				// without any error message cruft.
				$status->value = EditAttempt::AS_HOOK_ERROR_RESUME;
			} elseif ( $status->isOK() ) {
				// The hook aborted but didn't fatal, this means we should display
				// a nice edit form but include the errors set in the hook.
				$status->setResult( false, EditAttempt::AS_HOOK_ERROR_EXPECTED );
			} elseif ( !$status->getErrors() ) {
				throw new MWException( 'A hook filtered the edit when attempting ' .
					'save but didn\'t return any error.' );
			} else {
				// The hook aborted and set a fatal, don't display the edit form,
				// the extension should handle everything.
				$status->value = EditAttempt::AS_HOOK_ERROR;
			}
			return false;
		} elseif ( !$status->isOK() ) {
			if ( !$status->getErrors() ) {
				throw new MWException( 'A hook returned a fatal status when attempting save but ' .
					'didn\'t return any error.' );
			}
			// The hook didn't abort but the status is not ok, display form
			// and include errors.
			$status->value = EditAttempt::AS_HOOK_ERROR_EXPECTED;
			return false;
		}

		return true;
	}
}
