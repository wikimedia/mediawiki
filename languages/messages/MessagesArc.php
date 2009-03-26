<?php
/** Aramaic (ܐܪܡܝܐ)
 *
 * @ingroup Language
 * @file
 *
 * @author 334a
 * @author A2raya07
 * @author Basharh
 * @author The Thadman
 */

$rtl = true;

$defaultUserOptionOverrides = array(
	# Swap sidebar to right side by default
	'quickbar' => 2,
);

$messages = array(
# User preference toggles
'tog-hideminor' => 'ܛܫܝ ܫ̈ܚܠܦܬܐ ܙܥܘܪ̈ܬܐ ܒܫܚܠܦܬ̈ܐ ܚܕܬܬ̈ܐ',

# Dates
'sunday'        => 'ܚܕܒܫܒܐ',
'monday'        => 'ܬܪܝܢܒܫܒܐ',
'tuesday'       => 'ܬܠܬܒܫܒܐ',
'wednesday'     => 'ܐܪܒܥܒܫܒܐ',
'thursday'      => 'ܚܡܫܒܫܒܐ',
'friday'        => 'ܥܪܘܒܬܐ',
'saturday'      => 'ܫܒܬܐ',
'sun'           => 'ܚܕܒܫܒܐ',
'mon'           => 'ܬܪܝܢܒܫܒܐ',
'tue'           => 'ܬܠܬܒܫܒܐ',
'wed'           => 'ܐܪܒܥܒܫܒܐ',
'thu'           => 'ܚܡܫܒܫܒܐ',
'fri'           => 'ܥܪܘܒܬܐ',
'sat'           => 'ܫܒܬܐ',
'january'       => 'ܟܢܘܢ ܒ',
'february'      => 'ܫܒܛ',
'march'         => 'ܐܕܪ',
'april'         => 'ܢܝܣܢ',
'may_long'      => 'ܐܝܪ',
'june'          => 'ܚܙܝܪܢ',
'july'          => 'ܬܡܘܙ',
'august'        => 'ܐܒ',
'september'     => 'ܐܝܠܘܠ',
'october'       => 'ܬܫܪܝܢ ܐ',
'november'      => 'ܬܫܪܝܢ ܒ',
'december'      => 'ܟܢܘܢ ܐ',
'january-gen'   => 'ܟܢܘܢ ܒ',
'february-gen'  => 'ܫܒܛ',
'march-gen'     => 'ܐܕܪ',
'april-gen'     => 'ܢܝܣܢ',
'may-gen'       => 'ܐܝܪ',
'june-gen'      => 'ܚܙܝܪܢ',
'july-gen'      => 'ܬܡܘܙ',
'august-gen'    => 'ܐܒ',
'september-gen' => 'ܐܝܠܘܠ',
'october-gen'   => 'ܬܫܪܝܢ ܐ',
'november-gen'  => 'ܬܫܪܝܢ ܒ',
'december-gen'  => 'ܟܢܘܢ ܐ',
'jan'           => 'ܟܢܘܢ ܒ',
'feb'           => 'ܫܒܛ',
'mar'           => 'ܐܕܪ',
'apr'           => 'ܢܝܣܢ',
'may'           => 'ܐܝܪ',
'jun'           => 'ܚܙܝܪܢ',
'jul'           => 'ܬܡܘܙ',
'aug'           => 'ܐܒ',
'sep'           => 'ܐܝܠܘܠ',
'oct'           => 'ܬܫܪܝܢ ܐ',
'nov'           => 'ܬܫܪܝܢ ܒ',
'dec'           => 'ܟܢܘܢ ܐ',

'article'        => 'ܡܓܠܬܐ',
'newwindow'      => '(ܦܬܚ ܒܟܘܬܐ ܚܕܬܐ)',
'cancel'         => 'ܒܛܘܠ',
'qbedit'         => 'ܫܚܠܦ',
'qbpageoptions'  => 'ܗܕܐ ܦܐܬܐ',
'qbmyoptions'    => 'ܓܒܝܘ̈ܬܝ',
'qbspecialpages' => 'ܦܐܬܘܬܐ ܪܫܝܬܐ',
'moredotdotdot'  => '...ܝܬܝܪ̈ܐ',
'mypage'         => 'ܦܐܬܝ',
'mytalk'         => 'ܕܘܪܫܝ',
'anontalk'       => 'ܡܡܠܠܐ ܠܗܢܐ IP',
'navigation'     => 'ܐܠܦܪܘܬܐ',
'and'            => 'ܘ',

'errorpagetitle'    => 'ܦܘܕܐ',
'returnto'          => 'ܕܥܘܪ ܠ $1.',
'tagline'           => 'ܡܢ {{SITENAME}}',
'help'              => 'ܥܘܕܪܢܐ',
'search'            => 'ܒܨܐ',
'searchbutton'      => 'ܒܨܝ',
'go'                => 'ܙܠ',
'searcharticle'     => 'ܙܠ',
'history'           => 'ܬܫܥܝܬܐ ܕܦܐܬܐ',
'history_short'     => 'ܬܫܥܝܬܐ',
'printableversion'  => 'ܨܚܚܐ ܡܬܛܒܥܢܐ',
'permalink'         => 'ܐܣܘܪܐ ܦܝܘܫܐ',
'print'             => 'ܛܒܘܥ',
'edit'              => 'ܫܚܠܦ',
'editthispage'      => 'ܫܚܠܦ ܦܐܬܐ ܗܕܐ',
'delete'            => 'ܡܫܝ',
'deletethispage'    => 'ܡܫܝ ܗܕܐ ܦܐܬܐ',
'protect'           => 'ܚܡܝ',
'protectthispage'   => 'ܚܡܝ ܗܕܐ ܦܐܬܐ',
'unprotect'         => 'ܠܐ ܚܡܝ',
'unprotectthispage' => 'ܠܐ ܚܡܝ ܗܕܐ ܦܐܬܐ',
'newpage'           => 'ܦܐܬܐ ܚܕܬܐ',
'talkpage'          => 'ܕܪܘܫ ܗܕܐ ܦܐܬܐ',
'talkpagelinktext'  => 'ܡܡܠܠܐ',
'specialpage'       => 'ܦܐܬܐ ܕܝܠܢܝܬܐ',
'personaltools'     => 'ܡܐ̈ܢܐ ܦܪ̈ܨܘܦܝܐ',
'talk'              => 'ܕܘܪܫܐ',
'views'             => 'ܚܙ̈ܝܬܐ',
'toolbox'           => 'ܣܢܕܘܩܐ ܕܡܐ̈ܢܐ',
'userpage'          => 'ܚܙܝ ܦܐܬܐ ܕܡܦܠܚܢܐ',
'projectpage'       => 'ܚܙܝ ܦܐܬܐ ܕܬܪܡܝܬܐ',
'imagepage'         => 'ܚܙܝ ܦܐܬܐ ܕܠܦܦܐ',
'mediawikipage'     => 'ܚܙܝ ܦܐܬܐ ܕܐܓܪܬܐ',
'templatepage'      => 'ܚܙܝ ܦܐܬܐ ܕܩܠܒܐ',
'viewhelppage'      => 'ܚܙܝ ܦܐܬܐ ܕܥܘܕܪܢܐ',
'categorypage'      => 'ܚܙܝ ܦܐܬܐ ܕܣܕܪܐ',
'viewtalkpage'      => 'ܚܙܝ ܕܘܪܫܐ',
'otherlanguages'    => 'ܠܫܢ̈ܐ ܐܚܪ̈ܢܐ',
'protectedpage'     => 'ܦܐܬܐ ܚܡܝܬܐ',
'jumptonavigation'  => 'ܐܠܦܪܘܬܐ',
'jumptosearch'      => 'ܒܨܐ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'currentevents'     => 'ܓܕ̈ܫܐ ܗ̈ܫܝܐ',
'currentevents-url' => 'ܬܪܡܝܬܐ:ܓܕ̈ܫܐ ܗܫܝܐ',
'disclaimers'       => 'ܠܐ ܡܫܬܐܠܢܘܬܐ',
'edithelp'          => 'ܥܘܕܪܢܐ ܠܫܘܚܠܦܐ',
'mainpage'          => 'ܦܐܬܐ ܪܫܝܬܐ',
'portal'            => 'ܬܪܥܐ ܕܟܢܫܐ',
'portal-url'        => 'ܬܪܡܝܬܐ:ܬܪܥܐ ܕܟܢܫܐ',
'sitesupport'       => 'ܕܒܘܚ ܠܢ',

'badaccess' => 'ܦܘܕܐ ܠܦܣܣܐ',

'ok'                  => 'ܛܒ',
'youhavenewmessages'  => 'ܐܝܬ ܠܟ/ܠܟܝ $1 ($2).',
'newmessageslink'     => 'ܐܓܪ̈ܬܐ ܚܕ̈ܬܬܐ',
'newmessagesdifflink' => 'ܫܘܚܠܦܐ ܐܚܪܝܐ',
'editsection'         => 'ܫܚܠܦ',
'editold'             => 'ܫܚܠܦ',
'toc'                 => 'ܚܒܝܫܬ̈ܐ',
'showtoc'             => 'ܚܘܝ',
'hidetoc'             => 'ܛܫܝ',
'viewdeleted'         => 'ܚܙܝ $1?',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ܦܐܬܐ',
'nstab-user'      => 'ܦܐܬܐ ܕܡܦܠܚܢܐ',
'nstab-special'   => 'ܦܐܬܐ ܕܝܠܢܝܬܐ',
'nstab-project'   => 'ܦܐܬܐ ܕܬܪܡܝܬܐ',
'nstab-mediawiki' => 'ܐܓܪܬܐ',
'nstab-template'  => 'ܩܠܒܐ',
'nstab-help'      => 'ܦܐܬܐ ܕܥܘܕܪܢܐ',
'nstab-category'  => 'ܣܕܪܐ',

# Main script and global functions
'nosuchaction'      => 'ܠܝܬ ܗܟܘܬ ܥܒܕܐ',
'nosuchspecialpage' => 'ܠܝܬ ܗܟܘܬ ܦܐܬܐ ܕܝܠܢܝܬܐ',

# General errors
'error'              => 'ܦܘܕܐ',
'internalerror'      => 'ܦܘܕܐ ܓܘܝܐ',
'internalerror_info' => 'ܦܘܕܐ ܓܘܝܐ: $1',
'viewsource'         => 'ܚܙܝ ܥܩܪܐ',

# Login and logout pages
'logouttext'          => "'''ܗܫܐ ܦܠܛܠܟ ܡܢ ܚܘܫܒܢܟ.'''

You can continue to use {{SITENAME}} anonymously, or you can [[Special:UserLogin|log in again]] as the same or as a different user.
Note that some pages may continue to be displayed as if you were still logged in, until you clear your browser cache.",
'welcomecreation'     => '== ܒܫܝܢܐ, $1! ==
ܐܬܒܪܝ ܚܘܫܒܢܟ.
Do not forget to change your [[Special:Preferences|{{SITENAME}} preferences]].',
'yourname'            => 'ܫܡܐ ܕܡܦܠܚܢܐ:',
'login'               => 'ܥܘܠ',
'userlogin'           => 'ܥܘܠ \\ ܒܪܝ ܚܘܫܒܢܐ',
'logout'              => 'ܦܠܛ',
'userlogout'          => 'ܦܠܘܛ',
'nologin'             => 'ܠܝܬ ܠܟ/ܠܟܝ ܚܘܫܒܢܐ؟ $1.',
'nologinlink'         => 'ܒܪܝ ܚܘܫܒܢܐ',
'createaccount'       => 'ܒܪܝ ܚܘܫܒܢܐ',
'gotaccount'          => 'ܐܝܬ ܠܟ/ܠܟܝ ܚܘܫܒܢܐ؟ $1.',
'gotaccountlink'      => 'ܥܘܠ',
'username'            => 'ܫܡܐ ܕܡܦܠܚܢܐ:',
'yourrealname'        => ':ܫܡܐ ܫܪܝܪܐ',
'yourlanguage'        => ':ܠܫܢܐ',
'mailmypassword'      => 'ܚܕܬܐ password ܫܕܪ ܠܝ',
'accountcreated'      => 'ܐܬܒܪܝ ܚܘܫܒܢܐ',
'accountcreatedtext'  => 'ܐܬܒܪܝ ܚܘܫܒܢܐ ܕܡܦܠܚܢܐ ܠ $1.',
'createaccount-title' => 'ܒܪܝܐ ܕܚܘܫܒܢܐ ܒ {{SITENAME}}',
'loginlanguagelabel'  => 'ܠܫܢܐ: $1',

# Edit pages
'minoredit'          => 'ܗܢܐ ܗܘ ܫܘܚܠܦܐ ܙܥܘܪܐ',
'watchthis'          => 'ܕܘܩ ܦܐܬܐ ܗܕܐ',
'savearticle'        => 'ܫܚܠܦ ܦܐܬܐ',
'preview'            => 'ܚܝܪܬܐ ܩܕܡܝܬܐ',
'showpreview'        => 'ܚܘܝ ܚܝܪܬܐ ܩܕܡܝܬܐ',
'showdiff'           => 'ܚܘܝ ܫܘ̈ܚܠܦܐ',
'loginreqlink'       => 'ܥܘܠ',
'newarticle'         => '(ܚܕܬܐ)',
'updated'            => '(ܐܬܚܕܬ)',
'previewnote'        => "'''ܕܟܘܪ ܗܢܐ ܗܘ ܚܝܪܬܐ ܩܕܡܝܬܐ ܒܠܚܘܕ''' ܫܘܚܠܦ̈ܐ ܕܝܠܟ/ܕܝܠܟܝ ܠܐ ܐܬܬܚܡܝܬ ܥܕܡܐ ܠܗܫܐ",
'yourtext'           => 'ܟܬ̈ܒܝܟ',
'yourdiff'           => 'ܦܪ̈ܝܫܘܝܬܐ',
'templatesused'      => 'ܩܠܒ̈ܐ ܒܦܐܬܐ ܗܕܐ',
'template-protected' => '(ܚܡܝܐ)',

# Account creation failure
'cantcreateaccounttitle' => 'ܒܪܝܐ ܕܚܘܫܒܢܐ ܠܐ ܡܬܡܨܝܢܐ',

# History pages
'viewpagelogs' => 'View logs for this page
ܚܙܝ ܣܓܠ̈ܐ ܕܦܐܬܐ ܗܕܐ',
'cur'          => 'ܗܫܝܐ',
'page_first'   => 'ܩܕܡܝܐ',
'page_last'    => 'ܐܚܪܝܐ',
'histfirst'    => 'ܩܕܝܡ ܟܠ',
'histlast'     => 'ܐܚܪܝ ܟܠ',
'historyempty' => '(ܣܦܝܩܐ)',

# Revision deletion
'rev-delundel' => 'ܚܘܝ/ܛܫܝ',

# Diffs
'lineno'   => 'ܣܪܛܐ $1:',
'editundo' => 'ܠܐ ܥܒܘܕ',

# Search results
'viewprevnext' => 'ܚܘܝ ($1) ($2) ($3)',
'powersearch'  => 'ܒܨܝܐ ܡܬܩܕܡܢܐ',

# Preferences page
'preferences'   => 'ܦܪ̈ܝܫܘܝܬܐ',
'mypreferences' => 'ܦܪ̈ܝܫܘܝܬܝ',
'math'          => 'ܡܬܡܐܛܝܩܘܬܐ',
'prefs-rc'      => 'ܫܚܠܦܬ̈ܐ ܚܕ̈ܬܬܐ',
'saveprefs'     => 'ܚܡܝ',

# Recent changes
'recentchanges'   => 'ܫܚ̈ܠܦܬܐ ܚܕ̈ܬܬܐ',
'rclistfrom'      => 'ܚܘܝ ܫܘܚܠܦ̈ܐ ܚܕܬ̈ܐ ܡܢ $1',
'rcshowhideminor' => '$1 ܫܘܚܠܦ̈ܐ ܙܥܘܪ̈ܐ',
'rcshowhideliu'   => '$1 ܡܦܠܚܢ̈ܐ ܥܠܝܠ̈ܐ',
'rcshowhideanons' => '$1 ܡܦܠܚܢܐ ܠܐ ܝܕܝܥܐ',
'rcshowhidemine'  => '$1 ܫܘ̈ܚܠܦܝ',
'rclinks'         => 'ܚܘܝ $1 ܫܘܚܠܦ̈ܐ ܐܚܪ̈ܝܐ ܒ $2 ܝܘܡ̈ܐ ܐܚܪ̈ܝܐ<br />$3',
'diff'            => 'ܦܪܝܫܘܬܐ',
'hist'            => 'ܬܫܥܝܬܐ',
'hide'            => 'ܛܫܝ',
'show'            => 'ܚܘܝ',
'minoreditletter' => 'ܙ',
'newpageletter'   => 'ܚ',

# Recent changes linked
'recentchangeslinked' => 'ܫܚܠܦܬ̈ܐ ܕܡܝܐ',

# Upload
'upload'   => 'ܐܛܥܢ ܠܦܦܐ',
'filename' => 'ܫܡܐ ܕܠܦܦܐ',

# Image list
'ilsubmit'       => 'ܒܨܝ',
'filehist-user'  => 'ܡܦܠܚܢܐ',
'imagelist_name' => 'ܫܡܐ',

# Random page
'randompage' => 'ܡܓܠܬ̈ܐ ܚܘ̈ܝܚܐ',

# Miscellaneous special pages
'allpages'      => 'ܟܠ ܦܐܬܘ̄ܬܐ',
'specialpages'  => 'ܦܐܬܘ̈ܬܐ ܕܝܠܢܝܬ̈ܐ',
'newpages'      => 'ܦܐܬܘ̈ܬܐ ܚܕ̈ܬܬܐ',
'move'          => 'ܫܢܝ',
'movethispage'  => 'ܫܢܝ ܦܐܬܐ ܗܕܐ',
'pager-newer-n' => '{{PLURAL:$1|ܝܬܝܪ ܚܕܬܐ  1|ܝܬܝܪ ܚܕܬܐ  $1}}',
'pager-older-n' => '{{PLURAL:$1|ܝܬܝܪ ܥܬܝܩܐ  1|ܝܬܝܪ ܥܬܝܩܐ  $1}}',

# Book sources
'booksources'    => 'ܙܠ',
'booksources-go' => 'ܙܠ',

# Special:Log
'log' => 'ܣܓܠ̈ܐ',

# Special:Allpages
'allarticles'    => 'ܟܠ ܡ̈ܓܠܐ',
'allpagessubmit' => 'ܙܠ',

# E-mail user
'emailfrom' => 'ܡܢ:',
'emailto'   => 'ܠ:',

# Watchlist
'watchlist'     => 'ܕܘܩܘܬܝ',
'mywatchlist'   => 'ܕܘܩܘܬܝ',
'watchlistfor'  => "(ܠ '''$1''')",
'watch'         => 'ܕܘܩ',
'watchthispage' => 'ܕܘܩ ܗܕܐ ܦܐܬܐ',
'unwatch'       => 'ܠܐ ܕܘܩ',
'wlshowlast'    => 'ܚܘܝ $1 ܫܥܬ̈ܐ $2 ܝܘܡ̈ܐ ܐܚܪ̈ܝܐ $3',

# Displayed when you click the "watch" button and it's in the process of watching
'watching' => 'ܕܘܩܘܬܐ...',

# Delete/protect/revert
'deletepage'            => 'ܡܫܝ ܦܐܬܐ',
'dellogpage'            => 'ܣܓܠܐ ܕܡܫܝܐ',
'deletionlog'           => 'ܣܓܠܐ ܕܡܫܝܐ',
'deletecomment'         => 'ܥܠܬܐ ܕܡܫܝܐ:',
'deleteotherreason'     => 'ܥܠܬܐ ܐܚܪܬܐ/ܝܬܝܪܐ:',
'deletereasonotherlist' => 'ܥܠܬܐ ܐܚܪܬܐ',
'restriction-type'      => 'ܦܣܣܐ:',

# Restrictions (nouns)
'restriction-edit' => 'ܫܚܠܦ',
'restriction-move' => 'ܫܢܝ',

# Namespace form on various pages
'invert'         => 'ܐܗܦܟ ܓܘܒܝܐ',
'blanknamespace' => '(ܪܫܝܬܐ)',

# Contributions
'contributions' => 'ܫܘܬܦܘ̈ܝܬܐ ܕܡܦܠܚܢܐ',
'mycontris'     => 'ܫܘ̈ܬܦܘܝܬܝ',
'uctop'         => '(ܥܠܝܐ)',
'month'         => 'ܡܢ ܝܪܚܐ (ܘܡܢ ܩܕܡ ܗܝܕܝܢ):',
'year'          => 'ܡܢ ܫܢܬܐ (ܘܡܢ ܩܕܡ ܗܝܕܝܢ):',

'sp-contributions-blocklog' => 'ܣܓܠܐ ܕܚܪܡܐ',
'sp-contributions-search'   => 'ܒܨܝ ܫܘ̈ܬܦܘܝܬܐ',
'sp-contributions-submit'   => 'ܒܨܝ',

# What links here
'whatlinkshere'      => 'ܡܐ ܐܣܪ ܠܟܐ؟',
'whatlinkshere-page' => 'ܦܐܬܐ:',

# Block/unblock
'blocklink'    => 'ܐܚܪܡ',
'contribslink' => 'ܫܘ̈ܬܦܘܝܬܐ',
'blocklogpage' => 'ܣܓܠܐ ܕܚܪܡܐ',

# Move page
'movearticle'  => 'ܫܢܝ ܦܐܬܐ:',
'move-watch'   => 'ܕܘܩ ܦܐܬܐ ܗܕܐ',
'movepagebtn'  => 'ܫܢܝ ܦܐܬܐ',
'pagemovedsub' => 'ܫܘܢܝܐ ܐܬܓܡܪ',
'movelogpage'  => 'ܣܓܠܐ ܕܫܘܢܝܐ',
'movereason'   => 'ܥܠܬܐ:',

# Namespace 8 related
'allmessagesname' => 'ܫܡܐ',

# Thumbnails
'thumbnail-more' => 'ܐܘܪܒ',

# Tooltip help for the actions
'tooltip-search'  => 'ܒܨܝ {{SITENAME}}',
'tooltip-t-print' => 'ܨܚܚܐ ܡܬܛܒܥܢܐ ܕܗܕܐ ܦܐܬܐ',

# Browsing diffs
'previousdiff' => '← ܫܘܚܠܦܐ ܝܬܝܪ ܥܬܝܩܐ',
'nextdiff'     => 'ܫܘܚܠܦܐ ܝܬܝܪ ܚܕܬܐ →',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ܟܠ',
'watchlistall2'    => 'ܟܠ',
'namespacesall'    => 'ܟܠ',
'monthsall'        => 'ܟܠ',

# action=purge
'confirm_purge_button' => 'ܛܒ',

# Multipage image navigation
'imgmultigo' => 'ܙܠ!',

# Table pager
'table_pager_limit_submit' => 'ܙܠ',

# Watchlist editor
'watchlistedit-raw-submit' => 'ܚܕܬ ܕܘܩܘܬܐ',

# Watchlist editing tools
'watchlisttools-view' => 'ܚܘܝ ܫܚܠܦܬ̈ܐ ܕܡܝܐ',

# Special:Filepath
'filepath'        => 'ܫܒܝܠܐ ܕܠܦܦܐ',
'filepath-page'   => 'ܠܦܦܐ',
'filepath-submit' => 'ܫܒܝܠܐ',

);
