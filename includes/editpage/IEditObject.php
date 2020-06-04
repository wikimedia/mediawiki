<?php

namespace MediaWiki\EditPage;

/**
 * Serves as a common repository of constants for EditPage edit status results
 *
 * Each of these is a possible status value
 *
 * @internal
 */
interface IEditObject {
	/** Status: Article successfully updated */
	public const AS_SUCCESS_UPDATE = 200;

	/** Status: Article successfully created */
	public const AS_SUCCESS_NEW_ARTICLE = 201;

	/** Status: Article update aborted by a hook function */
	public const AS_HOOK_ERROR = 210;

	/** Status: A hook function returned an error */
	public const AS_HOOK_ERROR_EXPECTED = 212;

	/** Status: User is blocked from editing this page */
	public const AS_BLOCKED_PAGE_FOR_USER = 215;

	/** Status: Content too big (> $wgMaxArticleSize) */
	public const AS_CONTENT_TOO_BIG = 216;

	/** Status: this anonymous user is not allowed to edit this page */
	public const AS_READ_ONLY_PAGE_ANON = 218;

	/** Status: this logged in user is not allowed to edit this page */
	public const AS_READ_ONLY_PAGE_LOGGED = 219;

	/** Status: wiki is in readonly mode (wfReadOnly() == true) */
	public const AS_READ_ONLY_PAGE = 220;

	/** Status: rate limiter for action 'edit' was tripped */
	public const AS_RATE_LIMITED = 221;

	/** Status: article was deleted while editing and wpRecreate == false or form was not posted */
	public const AS_ARTICLE_WAS_DELETED = 222;

	/** Status: user tried to create this page, but is not allowed to do that */
	public const AS_NO_CREATE_PERMISSION = 223;

	/** Status: user tried to create a blank page and wpIgnoreBlankArticle == false */
	public const AS_BLANK_ARTICLE = 224;

	/** Status: (non-resolvable) edit conflict */
	public const AS_CONFLICT_DETECTED = 225;

	/**
	 * Status: no edit summary given and the user has forceeditsummary set and the user is not
	 * editing in his own userspace or talkspace and wpIgnoreBlankSummary == false
	 */
	public const AS_SUMMARY_NEEDED = 226;

	/** Status: user tried to create a new section without content */
	public const AS_TEXTBOX_EMPTY = 228;

	/** Status: article is too big (> $wgMaxArticleSize), after merging in the new section */
	public const AS_MAX_ARTICLE_SIZE_EXCEEDED = 229;

	/** Status: WikiPage::doEdit() was unsuccessful */
	public const AS_END = 231;

	/** Status: summary contained spam according to one of the regexes in $wgSummarySpamRegex */
	public const AS_SPAM_ERROR = 232;

	/** Status: anonymous user is not allowed to upload (User::isAllowed('upload') == false) */
	public const AS_IMAGE_REDIRECT_ANON = 233;

	/** Status: logged in user is not allowed to upload (User::isAllowed('upload') == false) */
	public const AS_IMAGE_REDIRECT_LOGGED = 234;

	/**
	 * Status: user tried to modify the content model, but is not allowed to do that
	 * ( User::isAllowed('editcontentmodel') == false )
	 */
	public const AS_NO_CHANGE_CONTENT_MODEL = 235;

	/** Status: user tried to create self-redirect and wpIgnoreSelfRedirect is false */
	public const AS_SELF_REDIRECT = 236;

	/** Status: an error relating to change tagging. Look at the message key for more details */
	public const AS_CHANGE_TAG_ERROR = 237;

	/** Status: can't parse content */
	public const AS_PARSE_ERROR = 240;

	/**
	 * Status: when changing the content model is disallowed due to
	 * $wgContentHandlerUseDB being false
	 *
	 * @deprecated since 1.35, meaningless since $wgContentHandlerUseDB has been removed.
	 */
	public const AS_CANNOT_USE_CUSTOM_MODEL = 241;

	/** Status: edit rejected because browser doesn't support Unicode. */
	public const AS_UNICODE_NOT_SUPPORTED = 242;
}
