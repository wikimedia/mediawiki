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

	/** Status: revision x was deleted while editing (?action=edit&oldid=x) */
	public const AS_REVISION_WAS_DELETED = 217;

	/** Status: this anonymous user is not allowed to edit this page */
	public const AS_READ_ONLY_PAGE_ANON = 218;

	/** Status: this logged in user is not allowed to edit this page */
	public const AS_READ_ONLY_PAGE_LOGGED = 219;

	/** Status: wiki is in readonly mode (ReadOnlyMode::isReadOnly() == true) */
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
	 * editing in their own userspace or talkspace and wpIgnoreBlankSummary == false
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

	/** Status: edit rejected because browser doesn't support Unicode. */
	public const AS_UNICODE_NOT_SUPPORTED = 242;

	/** Status: edit rejected because server was unable to acquire a temporary account name for this user */
	public const AS_UNABLE_TO_ACQUIRE_TEMP_ACCOUNT = 243;

	/** Status: user tried to create a redirect to a nonexistent page */
	public const AS_BROKEN_REDIRECT = 244;

	/** Status: user tried to create a redirect to another redirect */
	public const AS_DOUBLE_REDIRECT = 245;

	/** Status: user tried to create a redirect to another redirect that is pointing to the current page */
	public const AS_DOUBLE_REDIRECT_LOOP = 246;

	/** Status: user tried to create a redirect to an invalid redirect target */
	public const AS_INVALID_REDIRECT_TARGET = 247;
}
