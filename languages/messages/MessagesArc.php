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
'and'            => '&#32;ܘ',

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
'create'            => 'ܒܪܝ',
'editthispage'      => 'ܫܚܠܦ ܦܐܬܐ ܗܕܐ',
'create-this-page'  => 'ܒܪܝ ܗܕܐ ܦܐܬܐ',
'delete'            => 'ܫܘܦ',
'deletethispage'    => 'ܫܘܦ ܗܕܐ ܦܐܬܐ',
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
'imagepage'         => 'ܚܙܝ ܦܐܬܐ ܕܠܦܦܐ',
'mediawikipage'     => 'ܚܙܝ ܦܐܬܐ ܕܐܓܪܬܐ',
'templatepage'      => 'ܚܙܝ ܦܐܬܐ ܕܩܠܒܐ',
'viewhelppage'      => 'ܚܙܝ ܦܐܬܐ ܕܥܘܕܪܢܐ',
'viewtalkpage'      => 'ܚܙܝ ܕܘܪܫܐ',
'otherlanguages'    => 'ܠܫܢ̈ܐ ܐܚܪ̈ܢܐ',
'protectedpage'     => 'ܦܐܬܐ ܚܡܝܬܐ',
'jumptonavigation'  => 'ܐܠܦܪܘܬܐ',
'jumptosearch'      => 'ܒܨܐ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'currentevents'        => 'ܓܕ̈ܫܐ ܗ̈ܫܝܐ',
'currentevents-url'    => 'ܬܪܡܝܬܐ:ܓܕ̈ܫܐ ܗܫܝܐ',
'disclaimers'          => 'ܠܐ ܡܫܬܐܠܢܘܬܐ',
'edithelp'             => 'ܥܘܕܪܢܐ ܠܫܘܚܠܦܐ',
'mainpage'             => 'ܦܐܬܐ ܪܫܝܬܐ',
'mainpage-description' => 'ܦܐܬܐ ܪܫܝܬܐ',
'portal'               => 'ܬܪܥܐ ܕܟܢܫܐ',
'portal-url'           => 'ܬܪܡܝܬܐ:ܬܪܥܐ ܕܟܢܫܐ',

'badaccess' => 'ܦܘܕܐ ܠܦܣܣܐ',

'ok'                  => 'ܛܒ',
'youhavenewmessages'  => 'ܐܝܬ ܠܟ/ܠܟܝ $1 ($2).',
'newmessageslink'     => 'ܐܓܪ̈ܬܐ ܚܕ̈ܬܬܐ',
'newmessagesdifflink' => 'ܫܘܚܠܦܐ ܐܚܪܝܐ',
'editsection'         => 'ܫܚܠܦ',
'editold'             => 'ܫܚܠܦ',
'viewsourceold'       => 'ܚܙܝ ܥܩܪܐ',
'editlink'            => 'ܫܚܠܦ',
'viewsourcelink'      => 'ܚܙܝ ܥܩܪܐ',
'toc'                 => 'ܚܒܝܫܬ̈ܐ',
'showtoc'             => 'ܚܘܝ',
'hidetoc'             => 'ܛܫܝ',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ܦܐܬܐ',
'nstab-user'      => 'ܦܐܬܐ ܕܡܦܠܚܢܐ',
'nstab-special'   => 'ܦܐܬܐ ܕܝܠܢܝܬܐ',
'nstab-project'   => 'ܦܐܬܐ ܕܬܪܡܝܬܐ',
'nstab-mediawiki' => 'ܐܓܪܬܐ',
'nstab-template'  => 'ܩܠܒܐ',
'nstab-help'      => 'ܦܐܬܐ ܕܥܘܕܪܢܐ',
'nstab-category'  => 'ܣܕܪܐ',

# General errors
'error'      => 'ܦܘܕܐ',
'viewsource' => 'ܚܙܝ ܥܩܪܐ',

# Login and logout pages
'yourname'                => 'ܫܡܐ ܕܡܦܠܚܢܐ:',
'login'                   => 'ܥܘܠ',
'nav-login-createaccount' => 'ܥܘܠ \\ ܒܪܝ ܚܘܫܒܢܐ',
'userlogin'               => 'ܥܘܠ \\ ܒܪܝ ܚܘܫܒܢܐ',
'logout'                  => 'ܦܠܛ',
'userlogout'              => 'ܦܠܘܛ',
'nologin'                 => 'ܠܝܬ ܠܟ/ܠܟܝ ܚܘܫܒܢܐ؟ $1.',
'nologinlink'             => 'ܒܪܝ ܚܘܫܒܢܐ',
'createaccount'           => 'ܒܪܝ ܚܘܫܒܢܐ',
'gotaccount'              => 'ܐܝܬ ܠܟ/ܠܟܝ ܚܘܫܒܢܐ؟ $1.',
'gotaccountlink'          => 'ܥܘܠ',
'username'                => 'ܫܡܐ ܕܡܦܠܚܢܐ:',
'yourrealname'            => ':ܫܡܐ ܫܪܝܪܐ',
'yourlanguage'            => ':ܠܫܢܐ',
'mailmypassword'          => 'ܚܕܬܐ password ܫܕܪ ܠܝ',
'accountcreated'          => 'ܐܬܒܪܝ ܚܘܫܒܢܐ',
'accountcreatedtext'      => 'ܐܬܒܪܝ ܚܘܫܒܢܐ ܕܡܦܠܚܢܐ ܠ $1.',
'createaccount-title'     => 'ܒܪܝܐ ܕܚܘܫܒܢܐ ܒ {{SITENAME}}',

# Edit pages
'summary'                          => 'ܟܪܝܘܬܐ ܕܫܘܚܠܦܐ:',
'minoredit'                        => 'ܗܢܐ ܗܘ ܫܘܚܠܦܐ ܙܥܘܪܐ',
'watchthis'                        => 'ܕܘܩ ܦܐܬܐ ܗܕܐ',
'savearticle'                      => 'ܫܚܠܦ ܦܐܬܐ',
'preview'                          => 'ܚܝܪܬܐ ܩܕܡܝܬܐ',
'showpreview'                      => 'ܚܘܝ ܚܝܪܬܐ ܩܕܡܝܬܐ',
'showdiff'                         => 'ܚܘܝ ܫܘ̈ܚܠܦܐ',
'loginreqlink'                     => 'ܥܘܠ',
'newarticle'                       => '(ܚܕܬܐ)',
'previewnote'                      => "'''ܕܟܘܪ ܗܢܐ ܗܘ ܚܝܪܬܐ ܩܕܡܝܬܐ ܒܠܚܘܕ''' ܫܘܚܠܦ̈ܐ ܕܝܠܟ/ܕܝܠܟܝ ܠܐ ܐܬܬܚܡܝܬ ܥܕܡܐ ܠܗܫܐ",
'yourtext'                         => 'ܟܬ̈ܒܝܟ',
'templatesused'                    => 'ܩܠܒ̈ܐ ܒܦܐܬܐ ܗܕܐ',
'template-protected'               => '(ܚܡܝܐ)',
'permissionserrorstext-withaction' => 'ܠܝܬ ܠܟ/ܠܟܝ $2, ܒܥܠܬܐ ܕ {{PLURAL:$1|reason|reasons}}:',

# Account creation failure
'cantcreateaccounttitle' => 'ܒܪܝܐ ܕܚܘܫܒܢܐ ܠܐ ܡܬܡܨܝܢܐ',

# History pages
'cur'          => 'ܗܫܝܐ',
'histfirst'    => 'ܩܕܝܡ ܟܠ',
'histlast'     => 'ܐܚܪܝ ܟܠ',
'historyempty' => '(ܣܦܝܩܐ)',

# Revision deletion
'rev-delundel'    => 'ܚܘܝ/ܛܫܝ',
'revdelete-uname' => 'ܫܡܐ ܕܡܦܠܚܢܐ',

# Diffs
'lineno'   => 'ܣܪܛܐ $1:',
'editundo' => 'ܠܐ ܥܒܘܕ',

# Search results
'viewprevnext'       => 'ܚܘܝ ($1) ($2) ($3)',
'powersearch'        => 'ܒܨܝܐ ܡܬܩܕܡܢܐ',
'powersearch-legend' => 'ܒܨܝܐ ܡܬܩܕܡܢܐ',

# Preferences page
'preferences'   => 'ܦܪ̈ܝܫܘܝܬܐ',
'mypreferences' => 'ܦܪ̈ܝܫܘܝܬܝ',
'math'          => 'ܡܬܡܐܛܝܩܘܬܐ',
'prefs-rc'      => 'ܫܚܠܦܬ̈ܐ ܚܕ̈ܬܬܐ',
'saveprefs'     => 'ܚܡܝ',

# Groups
'group-user' => 'ܡ̈ܦܠܚܢܐ',

'group-user-member' => 'ܡܦܠܚܢܐ',

# Rights
'right-edit'          => 'ܫܚܠܦ ܦܐܬܘܬ̈ܐ',
'right-createaccount' => 'ܒܪܝ ܚܘ̈ܫܒܢܐ ܕܡܦܠܚܢܐ ܚܕܬܐ',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'          => 'ܫܚܠܦ ܦܐܬܐ ܗܕܐ',
'action-createaccount' => 'ܒܪܝ ܚܘܫܒܢܐ ܕܗܢ ܡܦܠܚܢܐ',

# Recent changes
'recentchanges'   => 'ܫܚ̈ܠܦܬܐ ܚܕ̈ܬܬܐ',
'rcshowhideliu'   => '$1 ܡܦܠܚܢ̈ܐ ܥܠܝܠ̈ܐ',
'rcshowhideanons' => '$1 ܡܦܠܚܢܐ ܠܐ ܝܕܝܥܐ',
'rcshowhidemine'  => '$1 ܫܘ̈ܚܠܦܝ',
'diff'            => 'ܦܪܝܫܘܬܐ',
'hist'            => 'ܬܫܥܝܬܐ',
'hide'            => 'ܛܫܝ',
'show'            => 'ܚܘܝ',
'minoreditletter' => 'ܙ',
'newpageletter'   => 'ܚ',

# Recent changes linked
'recentchangeslinked'      => 'ܫܚܠܦܬ̈ܐ ܕܡܝܐ',
'recentchangeslinked-page' => 'ܫܡܐ ܕܦܐܬܐ:',

# Upload
'upload'   => 'ܐܛܥܢ ܠܦܦܐ',
'filename' => 'ܫܡܐ ܕܠܦܦܐ',

# Special:ListFiles
'listfiles_name' => 'ܫܡܐ',

# File description page
'filehist-user' => 'ܡܦܠܚܢܐ',

# Random page
'randompage' => 'ܡܓܠܬ̈ܐ ܚܘ̈ܝܚܐ',

# Miscellaneous special pages
'newpages'      => 'ܦܐܬܘ̈ܬܐ ܚܕ̈ܬܬܐ',
'move'          => 'ܫܢܝ',
'movethispage'  => 'ܫܢܝ ܦܐܬܐ ܗܕܐ',

# Book sources
'booksources'    => 'ܙܠ',
'booksources-go' => 'ܙܠ',

# Special:AllPages
'allpages'       => 'ܟܠ ܦܐܬܘ̄ܬܐ',
'allarticles'    => 'ܟܠ ܡ̈ܓܠܐ',
'allpagessubmit' => 'ܙܠ',

# Special:Log/newusers
'newuserlog-create-entry' => 'ܚܘܫܒܢܐ ܕܡܦܠܚܢܐ ܚܕܬܐ',

# E-mail user
'emailfrom' => 'ܡܢ',
'emailto'   => 'ܥܠ',

# Watchlist
'watchlist'     => 'ܕܘܩܘܬܝ',
'mywatchlist'   => 'ܕܘܩܘܬܝ',
'watchlistfor'  => "(ܠ '''$1''')",
'watch'         => 'ܕܘܩ',
'watchthispage' => 'ܕܘܩ ܗܕܐ ܦܐܬܐ',
'unwatch'       => 'ܠܐ ܕܘܩ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'ܕܘܩܘܬܐ...',

# Delete
'deletepage'            => 'ܫܘܦ ܦܐܬܐ',
'deletecomment'         => 'ܥܠܬܐ ܠܫܝܦܐ:',
'deleteotherreason'     => 'ܥܠܬܐ ܐܚܪܬܐ/ܝܬܝܪܐ:',
'deletereasonotherlist' => 'ܥܠܬܐ ܐܚܪܬܐ',

# Protect
'restriction-type' => 'ܦܣܣܐ:',

# Restrictions (nouns)
'restriction-edit' => 'ܫܚܠܦ',
'restriction-move' => 'ܫܢܝ',

# Namespace form on various pages
'invert'         => 'ܗܦܟܐ ܕܓܘܒܝܐ',
'blanknamespace' => '(ܪܫܝܐ)',

# Contributions
'contributions'       => 'ܫܘܬܦܘ̈ܝܬܐ ܕܡܦܠܚܢܐ',
'contributions-title' => 'ܫܘ̈ܬܦܘܝܬܐ ܕܡܦܠܚܢܐ ܠ $1',
'mycontris'           => 'ܫܘ̈ܬܦܘܝܬܝ',
'uctop'               => '(ܥܠܝܐ)',
'month'               => 'ܡܢ ܝܪܚܐ (ܘܡܢ ܩܕܡ ܗܝܕܝܢ):',
'year'                => 'ܡܢ ܫܢܬܐ (ܘܡܢ ܩܕܡ ܗܝܕܝܢ):',

'sp-contributions-logs'   => 'ܣܓܠ̈ܐ',
'sp-contributions-search' => 'ܒܨܝ ܫܘ̈ܬܦܘܝܬܐ',
'sp-contributions-submit' => 'ܒܨܝ',

# What links here
'whatlinkshere'      => 'ܡܐ ܐܣܪ ܠܟܐ؟',
'whatlinkshere-page' => 'ܦܐܬܐ:',

# Block/unblock
'blocklink'    => 'ܐܚܪܡ',
'contribslink' => 'ܫܘ̈ܬܦܘܝܬܐ',

# Move page
'movearticle'  => 'ܫܢܝ ܦܐܬܐ:',
'move-watch'   => 'ܕܘܩ ܦܐܬܐ ܗܕܐ',
'movepagebtn'  => 'ܫܢܝ ܦܐܬܐ',
'pagemovedsub' => 'ܫܘܢܝܐ ܐܬܓܡܪ',
'movereason'   => 'ܥܠܬܐ:',

# Namespace 8 related
'allmessagesname' => 'ܫܡܐ',

# Thumbnails
'thumbnail-more' => 'ܐܘܪܒ',

# Tooltip help for the actions
'tooltip-search'  => 'ܒܨܝ {{SITENAME}}',
'tooltip-t-print' => 'ܨܚܚܐ ܡܬܛܒܥܢܐ ܕܗܕܐ ܦܐܬܐ',

# Browsing diffs
'previousdiff' => '← ܫܘܚܠܦܐ ܩܕܝܡܐ',
'nextdiff'     => 'ܫܘܚܠܦܐ ܚܕܬܐ →',

# Special:NewFiles
'ilsubmit' => 'ܒܨܝ',

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

# Special:FilePath
'filepath'        => 'ܫܒܝܠܐ ܕܠܦܦܐ',
'filepath-page'   => 'ܠܦܦܐ',
'filepath-submit' => 'ܫܒܝܠܐ',

# Special:SpecialPages
'specialpages' => 'ܦܐܬܘ̈ܬܐ ܕܝܠܢܝܬ̈ܐ',

);
