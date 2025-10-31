<?php
/**
 * This is the default English localisation file containing language specific
 * information excluding interface strings, which are stored in JSON files.
 *
 * Please see https://www.mediawiki.org/wiki/Localisation for more information.
 */

/**
 * Fallback language, used for all unspecified messages and behavior. This
 * is English by default, for all files other than this one.
 *
 * Do NOT set this to false in any other message file! Leave the line out to
 * accept the default fallback to "en".
 */
$fallback = false;

/**
 * Is the language written right-to-left?
 */
$rtl = false;

/**
 * Optional array mapping ASCII digits 0-9 to local digits.
 */
$digitTransformTable = null;

/**
 * Transform table for decimal point '.' and thousands separator ','
 */
$separatorTransformTable = null;

/**
 * The minimum number of digits a number must have, in addition to the grouping
 * size, before grouping separators are added.
 *
 * For example, Polish has minimumGroupingDigits = 2, which with a grouping
 * size of 3 causes 4-digit numbers to be written like 9999, but 5-digit
 * numbers are written like "10 000".
 */
$minimumGroupingDigits = 1;

/**
 * The CLDR numbering system name.
 * Refer to https://github.com/unicode-org/cldr/blob/main/common/supplemental/numberingSystems.xml
 */
$numberingSystem = 'latn';

/**
 * URLs do not specify their encoding. UTF-8 is used by default, but if the
 * URL is not a valid UTF-8 sequence, we have to try to guess what the real
 * encoding is. The encoding used in this case is defined below, and must be
 * supported by iconv().
 */
$fallback8bitEncoding = 'windows-1252';

/**
 * To allow "foo[[bar]]" to extend the link over the whole word "foobar"
 */
$linkPrefixExtension = false;

/**
 * Namespace names. NS_PROJECT is always set to $wgMetaNamespace after the
 * settings are loaded, it will be ignored even if you specify it here.
 *
 * NS_PROJECT_TALK will be set to $wgMetaNamespaceTalk if that variable is
 * set, otherwise the string specified here will be used. The string may
 * contain "$1", which will be replaced by the name of NS_PROJECT. It may
 * also contain a grammatical transformation, e.g.
 *
 *     NS_PROJECT_TALK => 'Keskustelu_{{grammar:elative|$1}}'
 *
 * Only one grammatical transform may be specified in the string. For
 * performance reasons, this transformation is done locally by the language
 * module rather than by the full wikitext parser. As a result, no other
 * parser features are available.
 */
$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_MAIN             => '',
	NS_TALK             => 'Talk',
	NS_USER             => 'User',
	NS_USER_TALK        => 'User_talk',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_talk',
	NS_FILE             => 'File',
	NS_FILE_TALK        => 'File_talk',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talk',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Help_talk',
	NS_CATEGORY         => 'Category',
	NS_CATEGORY_TALK    => 'Category_talk',
];

/**
 * Array of namespace aliases, mapping from name to NS_xxx index.
 *
 * Note that 'namespaceAliases' is a mergable language attribute,
 * which means it is combined with other languages in the fallback chain.
 */
$namespaceAliases = [
	// The canonical names of namespaces 6 and 7 are, as of MediaWik 1.14,
	// "File" and "File_talk".  The old names "Image" and "Image_talk" are
	// retained as aliases for backwards compatibility.
	// This must apply regardless of site language (and does, given 'en' is at
	// the end of all fallback chains.)
	'Image' => NS_FILE,
	'Image_talk' => NS_FILE_TALK,
];

/**
 * Array of gender specific. namespace aliases.
 * Mapping NS_xxx to array of GENDERKEY to alias.
 * Example:
 * @code
 * $namespaceGenderAliases = [
 *   NS_USER => [ 'male' => 'Male_user', 'female' => 'Female_user' ],
 * ];
 * @endcode
 */
$namespaceGenderAliases = [];

/**
 * A list of date format preference keys, which can be selected in user
 * preferences. New preference keys can be added, provided they are supported
 * by the language class's timeanddate(). Only the 5 keys listed below are
 * supported by the wikitext converter (parser/DateFormatter.php).
 *
 * The special key "default" is an alias for either dmy or mdy depending on
 * $wgAmericanDates
 */
$datePreferences = [
	'default',
	'mdy',
	'dmy',
	'ymd',
	'ISO 8601',
];

/**
 * The date format to use for generated dates in the user interface.
 * This may be one of the above date preferences, or the special value
 * "dmy or mdy", which uses mdy if $wgAmericanDates is true, and dmy
 * if $wgAmericanDates is false.
 */
$defaultDateFormat = 'dmy or mdy';

/**
 * Associative array mapping old numeric date formats, which may still be
 * stored in user preferences, to the new string formats.
 */
$datePreferenceMigrationMap = [
	'default',
	'mdy',
	'dmy',
	'ymd'
];

/**
 * These are formats for dates generated by MediaWiki (as opposed to the wikitext
 * DateFormatter). Documentation for the format string can be found in
 * Language.php, search for sprintfDate.
 *
 * This array is automatically inherited by all subclasses. Individual keys can be
 * overridden.
 */
$dateFormats = [
	'mdy time' => 'H:i',
	'mdy date' => 'F j, Y',
	'mdy monthonly' => 'F Y',
	'mdy both' => 'H:i, F j, Y',
	'mdy pretty' => 'F j',

	'dmy time' => 'H:i',
	'dmy date' => 'j F Y',
	'dmy monthonly' => 'F Y',
	'dmy both' => 'H:i, j F Y',
	'dmy pretty' => 'j F',

	'ymd time' => 'H:i',
	'ymd date' => 'Y F j',
	'ymd monthonly' => 'Y F',
	'ymd both' => 'H:i, Y F j',
	'ymd pretty' => 'F j',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 monthonly' => 'xnY-xnm',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
	'ISO 8601 pretty' => 'xnm-xnd'
];

/**
 * Overrides for the JS date format option arrays generated from $dateFormats by
 * Language::convertDateFormatToJs()
 */
$jsDateFormats = [];

/**
 * Default list of book sources
 */
$bookstoreList = [
	'BWB' => 'https://www.betterworldbooks.com/product/detail/-$1',
	'OpenLibrary' => 'https://openlibrary.org/isbn/$1',
	'Worldcat' => 'https://www.worldcat.org/search?q=isbn:$1',
];

/**
 * Magic words
 * Customizable syntax for wikitext and elsewhere.
 *
 * IDs must be valid identifiers, they cannot contain hyphens.
 * CASE is 0 to match all case variants, 1 for case-sensitive
 *
 * Note to localisers:
 *   - Include the English magic words as synonyms. This allows people from
 *     other wikis that do not speak the language to contribute more easily.
 *   - The first alias listed MUST be the preferred alias in that language.
 *     Tools (like Visual Editor) are expected to use the first listed alias
 *     when editing or creating new content.
 *   - Order the other aliases so that common aliases occur before more rarely
 *     used aliases. The aliases SHOULD be sorted by the following convention:
 *     1. Local first, English last, then
 *     2. Most common first, least common last.
 * @phpcs-require-sorted-array
 */
$magicWords = [
#   ID                               CASE  SYNONYMS
	'!'                       => [ 1, '!' ],
	'='                       => [ 1, '=' ],
	'anchorencode'            => [ 0, 'ANCHORENCODE' ],
	'articlepath'             => [ 0, 'ARTICLEPATH' ],
	'basepagename'            => [ 1, 'BASEPAGENAME' ],
	'basepagenamee'           => [ 1, 'BASEPAGENAMEE' ],
	'bcp47'                   => [ 1, '#bcp47' ],
	'bidi'                    => [ 0, 'BIDI:' ],
	'canonicalurl'            => [ 0, 'CANONICALURL:' ],
	'canonicalurle'           => [ 0, 'CANONICALURLE:' ],
	'cascadingsources'        => [ 1, 'CASCADINGSOURCES' ],
	'contentlanguage'         => [ 1, 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'contentmodel'            => [ 1, '#contentmodel' ],
	'contentmodel_canonical'  => [ 1, 'canonical' ],
	'contentmodel_local'      => [ 1, 'local' ],
	'currentday'              => [ 1, 'CURRENTDAY' ],
	'currentday2'             => [ 1, 'CURRENTDAY2' ],
	'currentdayname'          => [ 1, 'CURRENTDAYNAME' ],
	'currentdow'              => [ 1, 'CURRENTDOW' ],
	'currenthour'             => [ 1, 'CURRENTHOUR' ],
	'currentmonth'            => [ 1, 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'           => [ 1, 'CURRENTMONTH1' ],
	'currentmonthabbrev'      => [ 1, 'CURRENTMONTHABBREV' ],
	'currentmonthname'        => [ 1, 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'     => [ 1, 'CURRENTMONTHNAMEGEN' ],
	'currenttime'             => [ 1, 'CURRENTTIME' ],
	'currenttimestamp'        => [ 1, 'CURRENTTIMESTAMP' ],
	'currentversion'          => [ 1, 'CURRENTVERSION' ],
	'currentweek'             => [ 1, 'CURRENTWEEK' ],
	'currentyear'             => [ 1, 'CURRENTYEAR' ],
	'defaultsort'             => [ 1, 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'defaultsort_noerror'     => [ 0, 'noerror' ],
	'defaultsort_noreplace'   => [ 0, 'noreplace' ],
	'dir'                     => [ 1, '#dir' ],
	'directionmark'           => [ 1, 'DIRECTIONMARK', 'DIRMARK' ],
	'displaytitle'            => [ 1, 'DISPLAYTITLE' ],
	'displaytitle_noerror'    => [ 0, 'noerror' ],
	'displaytitle_noreplace'  => [ 0, 'noreplace' ],
	'expectunusedcategory'    => [ 1, '__EXPECTUNUSEDCATEGORY__', ],
	'expectunusedtemplate'    => [ 1, '__EXPECTUNUSEDTEMPLATE__', ],
	'filepath'                => [ 0, 'FILEPATH:' ],
	'forcetoc'                => [ 0, '__FORCETOC__' ],
	'formal'                  => [ 1, '#FORMAL:' ],
	'formatdate'              => [ 0, 'formatdate', 'dateformat' ],
	'formatnum'               => [ 0, 'FORMATNUM' ],
	'fullpagename'            => [ 1, 'FULLPAGENAME' ],
	'fullpagenamee'           => [ 1, 'FULLPAGENAMEE' ],
	'fullurl'                 => [ 0, 'FULLURL:' ],
	'fullurle'                => [ 0, 'FULLURLE:' ],
	'gender'                  => [ 0, 'GENDER:' ],
	'grammar'                 => [ 0, 'GRAMMAR:' ],
	'hiddencat'               => [ 1, '__HIDDENCAT__' ],
	'img_alt'                 => [ 1, 'alt=$1' ],
	'img_baseline'            => [ 1, 'baseline' ],
	'img_border'              => [ 1, 'border' ],
	'img_bottom'              => [ 1, 'bottom' ],
	'img_center'              => [ 1, 'center', 'centre' ],
	'img_class'               => [ 1, 'class=$1' ],
	'img_framed'              => [ 1, 'frame', 'framed', 'enframed' ],
	'img_frameless'           => [ 1, 'frameless' ],
	'img_lang'                => [ 1, 'lang=$1' ],
	'img_left'                => [ 1, 'left' ],
	'img_link'                => [ 1, 'link=$1' ],
	'img_manualthumb'         => [ 1, 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'              => [ 1, 'middle' ],
	'img_none'                => [ 1, 'none' ],
	'img_page'                => [ 1, 'page=$1', 'page $1' ],
	'img_right'               => [ 1, 'right' ],
	'img_sub'                 => [ 1, 'sub' ],
	'img_super'               => [ 1, 'super', 'sup' ],
	'img_text_bottom'         => [ 1, 'text-bottom' ],
	'img_text_top'            => [ 1, 'text-top' ],
	'img_thumbnail'           => [ 1, 'thumb', 'thumbnail' ],
	'img_top'                 => [ 1, 'top' ],
	'img_upright'             => [ 1, 'upright', 'upright=$1', 'upright $1' ],
	'img_width'               => [ 1, '$1px' ],
	'index'                   => [ 1, '__INDEX__' ],
	'int'                     => [ 0, 'INT:' ],
	'interlanguagelink'       => [ 1, '#interlanguagelink' ],
	'interwikilink'           => [ 1, '#interwikilink' ],
	'language'                => [ 0, '#LANGUAGE' ],
	'language_option_bcp47'   => [ 1, 'bcp47' ],
	'lc'                      => [ 0, 'LC:' ],
	'lcfirst'                 => [ 0, 'LCFIRST:' ],
	'localday'                => [ 1, 'LOCALDAY' ],
	'localday2'               => [ 1, 'LOCALDAY2' ],
	'localdayname'            => [ 1, 'LOCALDAYNAME' ],
	'localdow'                => [ 1, 'LOCALDOW' ],
	'localhour'               => [ 1, 'LOCALHOUR' ],
	'localmonth'              => [ 1, 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'             => [ 1, 'LOCALMONTH1' ],
	'localmonthabbrev'        => [ 1, 'LOCALMONTHABBREV' ],
	'localmonthname'          => [ 1, 'LOCALMONTHNAME' ],
	'localmonthnamegen'       => [ 1, 'LOCALMONTHNAMEGEN' ],
	'localtime'               => [ 1, 'LOCALTIME' ],
	'localtimestamp'          => [ 1, 'LOCALTIMESTAMP' ],
	'localurl'                => [ 0, 'LOCALURL:' ],
	'localurle'               => [ 0, 'LOCALURLE:' ],
	'localweek'               => [ 1, 'LOCALWEEK' ],
	'localyear'               => [ 1, 'LOCALYEAR' ],
	'lossless'                => [ 0, 'LOSSLESS' ],
	'msg'                     => [ 0, 'MSG:' ],
	'msgnw'                   => [ 0, 'MSGNW:' ],
	'namespace'               => [ 1, 'NAMESPACE' ],
	'namespacee'              => [ 1, 'NAMESPACEE' ],
	'namespacenumber'         => [ 1, 'NAMESPACENUMBER' ],
	'newsectionlink'          => [ 1, '__NEWSECTIONLINK__' ],
	'nocommafysuffix'         => [ 0, 'NOSEP' ],
	'nocontentconvert'        => [ 0, '__NOCONTENTCONVERT__', '__NOCC__' ],
	'noeditsection'           => [ 0, '__NOEDITSECTION__' ],
	'nogallery'               => [ 0, '__NOGALLERY__' ],
	'noindex'                 => [ 1, '__NOINDEX__' ],
	'nonewsectionlink'        => [ 1, '__NONEWSECTIONLINK__' ],
	'notitleconvert'          => [ 0, '__NOTITLECONVERT__', '__NOTC__' ],
	'notoc'                   => [ 0, '__NOTOC__' ],
	'ns'                      => [ 0, 'NS:' ],
	'nse'                     => [ 0, 'NSE:' ],
	'numberingroup'           => [ 1, 'NUMBERINGROUP', 'NUMINGROUP' ],
	'numberofactiveusers'     => [ 1, 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'          => [ 1, 'NUMBEROFADMINS' ],
	'numberofarticles'        => [ 1, 'NUMBEROFARTICLES' ],
	'numberofedits'           => [ 1, 'NUMBEROFEDITS' ],
	'numberoffiles'           => [ 1, 'NUMBEROFFILES' ],
	'numberofpages'           => [ 1, 'NUMBEROFPAGES' ],
	'numberofusers'           => [ 1, 'NUMBEROFUSERS' ],
	'padleft'                 => [ 0, 'PADLEFT' ],
	'padright'                => [ 0, 'PADRIGHT' ],
	'pageid'                  => [ 0, 'PAGEID' ],
	'pagelanguage'            => [ 1, 'PAGELANGUAGE' ],
	'pagename'                => [ 1, 'PAGENAME' ],
	'pagenamee'               => [ 1, 'PAGENAMEE' ],
	'pagesincategory'         => [ 1, 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesincategory_all'     => [ 0, 'all' ],
	'pagesincategory_files'   => [ 0, 'files' ],
	'pagesincategory_pages'   => [ 0, 'pages' ],
	'pagesincategory_subcats' => [ 0, 'subcats' ],
	'pagesinnamespace'        => [ 1, 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                => [ 1, 'PAGESIZE' ],
	'plural'                  => [ 0, 'PLURAL:' ],
	'protectionexpiry'        => [ 1, 'PROTECTIONEXPIRY' ],
	'protectionlevel'         => [ 1, 'PROTECTIONLEVEL' ],
	'raw'                     => [ 0, 'RAW:' ],
	'rawsuffix'               => [ 1, 'R' ],
	'redirect'                => [ 0, '#REDIRECT' ],
	'revisionday'             => [ 1, 'REVISIONDAY' ],
	'revisionday2'            => [ 1, 'REVISIONDAY2' ],
	'revisionid'              => [ 1, 'REVISIONID' ],
	'revisionmonth'           => [ 1, 'REVISIONMONTH' ],
	'revisionmonth1'          => [ 1, 'REVISIONMONTH1' ],
	'revisionsize'            => [ 1, 'REVISIONSIZE' ],
	'revisiontimestamp'       => [ 1, 'REVISIONTIMESTAMP' ],
	'revisionuser'            => [ 1, 'REVISIONUSER' ],
	'revisionyear'            => [ 1, 'REVISIONYEAR' ],
	'rootpagename'            => [ 1, 'ROOTPAGENAME' ],
	'rootpagenamee'           => [ 1, 'ROOTPAGENAMEE' ],
	'safesubst'               => [ 0, 'SAFESUBST:' ],
	'scriptpath'              => [ 0, 'SCRIPTPATH' ],
	'server'                  => [ 0, 'SERVER' ],
	'servername'              => [ 0, 'SERVERNAME' ],
	'sitename'                => [ 1, 'SITENAME' ],
	'special'                 => [ 0, 'special' ],
	'speciale'                => [ 0, 'speciale' ],
	'staticredirect'          => [ 1, '__STATICREDIRECT__' ],
	'stylepath'               => [ 0, 'STYLEPATH' ],
	'subjectpagename'         => [ 1, 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'        => [ 1, 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'subjectspace'            => [ 1, 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'           => [ 1, 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'subpagename'             => [ 1, 'SUBPAGENAME' ],
	'subpagenamee'            => [ 1, 'SUBPAGENAMEE' ],
	'subst'                   => [ 0, 'SUBST:' ],
	'tag'                     => [ 0, 'tag' ],
	'talkpagename'            => [ 1, 'TALKPAGENAME' ],
	'talkpagenamee'           => [ 1, 'TALKPAGENAMEE' ],
	'talkspace'               => [ 1, 'TALKSPACE' ],
	'talkspacee'              => [ 1, 'TALKSPACEE' ],
	'toc'                     => [ 0, '__TOC__' ],
	'uc'                      => [ 0, 'UC:' ],
	'ucfirst'                 => [ 0, 'UCFIRST:' ],
	'urlencode'               => [ 0, 'URLENCODE:' ],
	'url_path'                => [ 0, 'PATH' ],
	'url_query'               => [ 0, 'QUERY' ],
	'url_wiki'                => [ 0, 'WIKI' ],
	'userlanguage'            => [ 1, 'USERLANGUAGE' ],
];

/**
 * Alternate names of special pages. All names are case-insensitive. The first
 * listed alias will be used as the default. Aliases from the fallback
 * localisation (usually English) will be included by default.
 * @phpcs-require-sorted-array
 */
$specialPageAliases = [
	'Activeusers'                => [ 'ActiveUsers' ],
	'Allmessages'                => [ 'AllMessages' ],
	'AllMyUploads'               => [ 'AllMyUploads', 'AllMyFiles' ],
	'Allpages'                   => [ 'AllPages' ],
	'Ancientpages'               => [ 'AncientPages' ],
	'ApiHelp'                    => [ 'ApiHelp' ],
	'ApiSandbox'                 => [ 'ApiSandbox' ],
	'AuthenticationPopupSuccess' => [ 'AuthenticationPopupSuccess' ],
	'AutoblockList'              => [ 'AutoblockList', 'ListAutoblocks' ],
	'Badtitle'                   => [ 'Badtitle' ],
	'Blankpage'                  => [ 'BlankPage' ],
	'Block'                      => [ 'Block', 'BlockIP', 'BlockUser' ],
	'BlockList'                  => [ 'BlockList', 'ListBlocks', 'IPBlockList' ],
	'Booksources'                => [ 'BookSources' ],
	'BotPasswords'               => [ 'BotPasswords' ],
	'BrokenRedirects'            => [ 'BrokenRedirects' ],
	'Categories'                 => [ 'Categories' ],
	'ChangeContentModel'         => [ 'ChangeContentModel' ],
	'ChangeCredentials'          => [ 'ChangeCredentials' ],
	'ChangeEmail'                => [ 'ChangeEmail' ],
	'ChangePassword'             => [ 'ChangePassword', 'ResetPass', 'ResetPassword' ],
	'ComparePages'               => [ 'ComparePages' ],
	'Confirmemail'               => [ 'ConfirmEmail' ],
	'Contribute'                 => [ 'Contribute' ],
	'Contributions'              => [ 'Contributions', 'Contribs' ],
	'CreateAccount'              => [ 'CreateAccount' ],
	'Deadendpages'               => [ 'DeadendPages' ],
	'DeletedContributions'       => [ 'DeletedContributions', 'DeletedContribs' ],
	'DeletePage'                 => [ 'DeletePage', 'Delete' ],
	'Diff'                       => [ 'Diff' ],
	'DoubleRedirects'            => [ 'DoubleRedirects' ],
	'EditPage'                   => [ 'EditPage', 'Edit' ],
	'EditRecovery'               => [ 'EditRecovery' ],
	'EditTags'                   => [ 'EditTags' ],
	'EditWatchlist'              => [ 'EditWatchlist' ],
	'Emailuser'                  => [ 'EmailUser', 'Email' ],
	'ExpandTemplates'            => [ 'ExpandTemplates' ],
	'Export'                     => [ 'Export' ],
	'Fewestrevisions'            => [ 'FewestRevisions' ],
	'FileDuplicateSearch'        => [ 'FileDuplicateSearch' ],
	'Filepath'                   => [ 'FilePath' ],
	'GoToInterwiki'              => [ 'GoToInterwiki' ],
	'Import'                     => [ 'Import' ],
	'Interwiki'                  => [ 'Interwiki' ],
	'Invalidateemail'            => [ 'InvalidateEmail' ],
	'JavaScriptTest'             => [ 'JavaScriptTest' ],
	'LinkAccounts'               => [ 'LinkAccounts' ],
	'LinkSearch'                 => [ 'LinkSearch' ],
	'Listadmins'                 => [ 'ListAdmins' ],
	'Listbots'                   => [ 'ListBots' ],
	'ListDuplicatedFiles'        => [ 'ListDuplicatedFiles', 'ListFileDuplicates' ],
	'Listfiles'                  => [ 'ListFiles', 'FileList', 'ImageList' ],
	'Listgrants'                 => [ 'ListGrants' ],
	'Listgrouprights'            => [ 'ListGroupRights', 'UserGroupRights' ],
	'Listredirects'              => [ 'ListRedirects' ],
	'Listusers'                  => [ 'ListUsers', 'UserList', 'Users' ],
	'Lockdb'                     => [ 'LockDB' ],
	'Log'                        => [ 'Log', 'Logs' ],
	'Lonelypages'                => [ 'LonelyPages', 'OrphanedPages' ],
	'Longpages'                  => [ 'LongPages' ],
	'MediaStatistics'            => [ 'MediaStatistics' ],
	'MergeHistory'               => [ 'MergeHistory' ],
	'MIMEsearch'                 => [ 'MIMESearch' ],
	'Mostcategories'             => [ 'MostCategories' ],
	'Mostimages'                 => [ 'MostLinkedFiles', 'MostFiles', 'MostImages' ],
	'Mostinterwikis'             => [ 'MostInterwikis' ],
	'Mostlinked'                 => [ 'MostLinkedPages', 'MostLinked' ],
	'Mostlinkedcategories'       => [ 'MostLinkedCategories', 'MostUsedCategories' ],
	'Mostlinkedtemplates'        => [ 'MostTranscludedPages', 'MostLinkedTemplates', 'MostUsedTemplates' ],
	'Mostrevisions'              => [ 'MostRevisions' ],
	'Movepage'                   => [ 'MovePage' ],
	'Mute'                       => [ 'Mute' ],
	'Mycontributions'            => [ 'MyContributions', 'MyContribs' ],
	'MyLanguage'                 => [ 'MyLanguage' ],
	'Mylog'                      => [ 'MyLog' ],
	'Mypage'                     => [ 'MyPage' ],
	'Mytalk'                     => [ 'MyTalk' ],
	'Myuploads'                  => [ 'MyUploads', 'MyFiles' ],
	'NamespaceInfo'              => [ 'NamespaceInfo' ],
	'Newimages'                  => [ 'NewFiles', 'NewImages' ],
	'Newpages'                   => [ 'NewPages' ],
	'NewSection'                 => [ 'NewSection', 'Newsection' ],
	'PageData'                   => [ 'PageData' ],
	'PageHistory'                => [ 'PageHistory', 'History' ],
	'PageInfo'                   => [ 'PageInfo', 'Info' ],
	'PageLanguage'               => [ 'PageLanguage' ],
	'PagesWithProp'              => [ 'PagesWithProp', 'Pageswithprop', 'PagesByProp', 'Pagesbyprop' ],
	'PasswordPolicies'           => [ 'PasswordPolicies' ],
	'PasswordReset'              => [ 'PasswordReset' ],
	'PermanentLink'              => [ 'PermanentLink', 'PermaLink' ],
	'Preferences'                => [ 'Preferences' ],
	'Prefixindex'                => [ 'PrefixIndex' ],
	'Protectedpages'             => [ 'ProtectedPages' ],
	'Protectedtitles'            => [ 'ProtectedTitles' ],
	'ProtectPage'                => [ 'ProtectPage', 'Protect' ],
	'Purge'                      => [ 'Purge' ],
	'RandomInCategory'           => [ 'RandomInCategory' ],
	'Randompage'                 => [ 'Random', 'RandomPage' ],
	'Randomredirect'             => [ 'RandomRedirect' ],
	'Randomrootpage'             => [ 'RandomRootpage' ],
	'Recentchanges'              => [ 'RecentChanges' ],
	'Recentchangeslinked'        => [ 'RecentChangesLinked', 'RelatedChanges' ],
	'Redirect'                   => [ 'Redirect' ],
	'RemoveCredentials'          => [ 'RemoveCredentials' ],
	'Renameuser'                 => [ 'RenameUser' ],
	'ResetTokens'                => [ 'ResetTokens' ],
	'RestSandbox'                => [ 'RestSandbox' ],
	'Revisiondelete'             => [ 'RevisionDelete' ],
	'RunJobs'                    => [ 'RunJobs' ],
	'Search'                     => [ 'Search' ],
	'Shortpages'                 => [ 'ShortPages' ],
	'Specialpages'               => [ 'SpecialPages' ],
	'Statistics'                 => [ 'Statistics', 'Stats' ],
	'Tags'                       => [ 'Tags' ],
	'TalkPage'                   => [ 'TalkPage' ],
	'TrackingCategories'         => [ 'TrackingCategories' ],
	'Unblock'                    => [ 'Unblock' ],
	'Uncategorizedcategories'    => [ 'UncategorizedCategories' ],
	'Uncategorizedimages'        => [ 'UncategorizedFiles', 'UncategorizedImages' ],
	'Uncategorizedpages'         => [ 'UncategorizedPages' ],
	'Uncategorizedtemplates'     => [ 'UncategorizedTemplates' ],
	'Undelete'                   => [ 'Undelete' ],
	'UnlinkAccounts'             => [ 'UnlinkAccounts' ],
	'Unlockdb'                   => [ 'UnlockDB' ],
	'Unusedcategories'           => [ 'UnusedCategories' ],
	'Unusedimages'               => [ 'UnusedFiles', 'UnusedImages' ],
	'Unusedtemplates'            => [ 'UnusedTemplates' ],
	'Unwatchedpages'             => [ 'UnwatchedPages' ],
	'Upload'                     => [ 'Upload' ],
	'UploadStash'                => [ 'UploadStash' ],
	'Userlogin'                  => [ 'UserLogin', 'Login' ],
	'Userlogout'                 => [ 'UserLogout', 'Logout' ],
	'Userrights'                 => [ 'UserRights', 'MakeSysop', 'MakeBot' ],
	'Version'                    => [ 'Version', 'Versions' ],
	'Wantedcategories'           => [ 'WantedCategories' ],
	'Wantedfiles'                => [ 'WantedFiles' ],
	'Wantedpages'                => [ 'WantedPages', 'BrokenLinks' ],
	'Wantedtemplates'            => [ 'WantedTemplates' ],
	'Watchlist'                  => [ 'Watchlist' ],
	'WatchlistLabels'            => [ 'WatchlistLabels' ],
	'Whatlinkshere'              => [ 'WhatLinksHere' ],
	'Withoutinterwiki'           => [ 'WithoutInterwiki' ],
];

/**
 * Regular expression matching the "link trail", e.g. "ed" in [[Toast]]ed, as
 * the first group, and the remainder of the string as the second group.
 */
$linkTrail = '/^([a-z]+)(.*)$/sD';

/**
 * Regular expression charset matching the "link prefix", e.g. "foo" in
 * foo[[bar]]. UTF-8 characters may be used.
 */
$linkPrefixCharset = 'a-zA-Z\\x{80}-\\x{10ffff}';

/**
 * A list of messages to preload for each request.
 * Here we add messages that are needed for a typical anonymous parser cache hit.
 */
$preloadedMessages = [
	'aboutpage',
	'aboutsite',
	'accesskey-ca-edit',
	'accesskey-ca-history',
	'accesskey-ca-nstab-main',
	'accesskey-ca-talk',
	'accesskey-ca-viewsource',
	'accesskey-n-currentevents',
	'accesskey-n-help',
	'accesskey-n-mainpage-description',
	'accesskey-n-portal',
	'accesskey-n-randompage',
	'accesskey-n-recentchanges',
	'accesskey-p-logo',
	'accesskey-pt-login',
	'accesskey-pt-createaccount',
	'accesskey-search',
	'accesskey-search-fulltext',
	'accesskey-search-go',
	'accesskey-t-info',
	'accesskey-t-permalink',
	'accesskey-t-print',
	'accesskey-t-recentchangeslinked',
	'accesskey-t-specialpages',
	'accesskey-t-whatlinkshere',
	'actions',
	'anonnotice',
	'brackets',
	'comma-separator',
	'currentevents',
	'currentevents-url',
	'disclaimerpage',
	'disclaimers',
	'edit',
	'editsection',
	'editsectionhint',
	'help',
	'helppage',
	'interlanguage-link-title',
	'jumpto',
	'jumptonavigation',
	'jumptosearch',
	'lastmodifiedat',
	'mainpage',
	'mainpage-description',
	'mainpage-nstab',
	'namespaces',
	'navigation',
	'nav-login-createaccount',
	'nstab-main',
	'opensearch-desc',
	'pagecategories',
	'pagecategorieslink',
	'pagetitle',
	'pagetitle-view-mainpage',
	'permalink',
	'personaltools',
	'portal',
	'portal-url',
	'printableversion',
	'privacy',
	'privacypage',
	'randompage',
	'randompage-url',
	'recentchanges',
	'recentchangeslinked-toolbox',
	'recentchanges-url',
	'retrievedfrom',
	'search',
	'searcharticle',
	'searchbutton',
	'searchsuggest-search',
	'sidebar',
	'navigation-heading',
	'site-atom-feed',
	'sitenotice',
	'specialpages',
	'tagline',
	'talk',
	'toolbox',
	'tooltip-ca-edit',
	'tooltip-ca-history',
	'tooltip-ca-nstab-main',
	'tooltip-ca-talk',
	'tooltip-ca-viewsource',
	'tooltip-n-currentevents',
	'tooltip-n-help',
	'tooltip-n-mainpage-description',
	'tooltip-n-portal',
	'tooltip-n-randompage',
	'tooltip-n-recentchanges',
	'tooltip-p-logo',
	'tooltip-pt-login',
	'tooltip-pt-createaccount',
	'tooltip-search',
	'tooltip-search-fulltext',
	'tooltip-search-go',
	'tooltip-t-info',
	'tooltip-t-permalink',
	'tooltip-t-print',
	'tooltip-t-recentchangeslinked',
	'tooltip-t-specialpages',
	'tooltip-t-whatlinkshere',
	'variants',
	'vector-view-edit',
	'vector-view-history',
	'vector-view-view',
	'viewcount',
	'views',
	'whatlinkshere',
	'word-separator',
];

$digitGroupingPattern = "#,##0.###";

$formalityIndex = 0;
