<?php
/** Aramaic (ܐܪܡܝܐ)
 *
 * @addtogroup Language
 *
 * @author A2raya07
 * @author The Thadman
 * @author Siebrand
 * @author SPQRobin
 * @author 334a
 */

$rtl = true;

$defaultUserOptionOverrides = array(
	# Swap sidebar to right side by default
	'quickbar' => 2,
);

$messages = array(
# Dates
'sunday'      => 'ܚܕܒܫܒܐ',
'monday'      => 'ܬܪܝܢܒܫܒܐ',
'tuesday'     => 'ܬܠܬܒܫܒܐ',
'wednesday'   => 'ܐܪܒܥܒܫܒܐ',
'thursday'    => 'ܚܡܫܒܫܒܐ',
'friday'      => 'ܥܪܘܒܬܐ',
'saturday'    => 'ܫܒܬܐ',
'sun'         => 'ܚܕܒܫܒܐ',
'mon'         => 'ܬܪܝܢܒܫܒܐ',
'tue'         => 'ܬܠܬܒܫܒܐ',
'wed'         => 'ܐܪܒܥܒܫܒܐ',
'thu'         => 'ܚܡܫܒܫܒܐ',
'fri'         => 'ܥܪܘܒܬܐ',
'sat'         => 'ܫܒܬܐ',
'january'     => 'ܟܢܘܢ ܬܪܝܢܐ',
'february'    => 'ܫܒܛ',
'march'       => 'ܐܕܪ',
'april'       => 'ܢܝܣܢ',
'may_long'    => 'ܐܝܪ',
'june'        => 'ܚܙܝܪܢ',
'july'        => 'ܬܡܘܙ',
'august'      => 'ܐܒ',
'september'   => 'ܐܝܠܘܠ',
'october'     => 'ܬܫܪܝܢ ܩܕܝܡ',
'november'    => 'ܬܫܪܝܢ ܬܪܝܢܐ',
'december'    => 'ܟܢܘܢ ܩܕܝܡ',
'january-gen' => 'ܟܢܘܢ ܬܪܝܢܐ',
'jan'         => 'ܟܢܘܢ ܒ',
'feb'         => 'ܫܒܛ',
'mar'         => 'ܐܕܪ',
'apr'         => 'ܢܝܣܢ',
'may'         => 'ܐܝܪ',
'jun'         => 'ܚܙܝܪܢ',
'jul'         => 'ܬܡܘܙ',
'aug'         => 'ܐܒ',
'sep'         => 'ܐܝܠܘܠ',
'oct'         => 'ܬܫܪܝܢ ܐ',
'nov'         => 'ܬܫܪܝܢ ܒ',
'dec'         => 'ܟܢܘܢ ܐ',

'article'        => 'ܡܐܡܪܐ',
'newwindow'      => '(ܦܬܚ ܒܟܘܬܐ ܚܕܬܬܐ)',
'cancel'         => 'ܒܛܘܠ',
'qbpageoptions'  => 'ܗܢܐ ܕܦܐ',
'qbmyoptions'    => 'ܓܒܝ̈ܘܬܝ',
'qbspecialpages' => 'ܦܐܬܘܬ̈ܐ ܪܫܝܬܐ',
'moredotdotdot'  => '...ܝܬܝܪ̈ܐ',
'mypage'         => 'ܕܦܝ',
'mytalk'         => 'ܕܘܪܫܝ',
'navigation'     => 'ܐܠܦܪܘܬܐ',

'errorpagetitle'    => 'ܛܥܝܘܬܐ',
'help'              => 'ܥܘܕܪܢܐ',
'search'            => 'ܒܨܐ',
'searchbutton'      => 'ܒܨܝ',
'go'                => 'ܙܠ',
'searcharticle'     => 'ܙܠ',
'history'           => 'ܬܫܥܝܬܐ ܕܕܦܐ',
'history_short'     => 'ܬܫܥܝܬܐ',
'print'             => 'ܒܨܡܐ',
'edit'              => 'ܫܚܠܦ',
'editthispage'      => 'ܫܚܠܦ ܗܢܐ ܕܦܐ',
'delete'            => 'ܫܘܦ',
'deletethispage'    => 'ܫܘܦ ܗܢܐ ܕܦܐ',
'protect'           => 'ܚܡܝ',
'protectthispage'   => 'ܚܡܝ ܗܢܐ ܕܦܐ',
'unprotect'         => 'ܠܐ ܚܡܝ',
'unprotectthispage' => 'ܠܐ ܚܡܝ ܗܢܐ ܕܦܐ',
'newpage'           => 'ܕܦܐ ܚܕܬܐ',
'talkpage'          => 'ܕܪܘܫ ܗܢܐ ܕܦܐ',
'specialpage'       => 'ܕܦܐ ܝܚܝܕܐ',
'talk'              => 'ܕܘܪܫܐ',
'toolbox'           => 'ܡܐܢ̈ܐ',
'imagepage'         => 'ܚܙܝ ܕܦܐ ܕܨܘܪܬܐ',
'viewhelppage'      => 'ܚܙܝ ܕܦܐ ܕܥܘܕܪܢܐ',
'viewtalkpage'      => 'ܚܙܝ ܕܘܪܫܐ',
'otherlanguages'    => 'ܠܫܢ̈ܐ ܐܚܪ̈ܢܐ',
'protectedpage'     => 'ܕܦܐ ܚܡܝܐ',
'jumptonavigation'  => 'ܐܠܦܪܘܬܐ',
'jumptosearch'      => 'ܒܨܐ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'currentevents'     => 'ܓܕ̈ܫܐ ܗܫ̈ܝܐ',
'currentevents-url' => 'Project:ܓܕ̈ܫܐ ܗܫܝܐ',
'mainpage'          => 'ܕܦܐ ܪܫܝܐ',
'portal'            => 'ܬܪܥܐ ܕܟܢܫܐ',
'portal-url'        => 'Project:ܬܪܥܐ ܕܟܢܫܐ',
'sitesupport'       => 'ܕܚܘܝܬܐ',

'ok'              => 'ܛܒ',
'newmessageslink' => 'ܣܒܪ̈ܬܐ ܚܕ̈ܬܬܐ',
'editsection'     => 'ܫܚܠܦ',
'editold'         => 'ܫܚܠܦ',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ܕܦܐ',
'nstab-mediawiki' => 'ܣܒܪܬܐ',
'nstab-template'  => 'ܩܠܒܐ',
'nstab-help'      => 'ܕܦܐ ܕܥܘܕܪܢܐ',
'nstab-category'  => 'ܣܕܪܐ',

# General errors
'error'      => 'ܛܥܝܘܬܐ',
'viewsource' => 'ܚܙܝ ܥܩܪܐ',

# Login and logout pages
'login'        => 'ܥܘܠ',
'userlogin'    => 'ܥܘܠ \ ܒܪܝ ܫܡܐ',
'logout'       => 'ܦܠܛ',
'userlogout'   => 'ܦܠܘܛ',
'yourrealname' => ':ܫܡܐ ܫܪܝܪܐ',
'yourlanguage' => ':ܠܫܢܐ',

# Edit pages
'savearticle'  => 'ܫܚܠܦ ܕܦܐ',
'showdiff'     => 'ܚܘܝ ܫܘ̈ܚܠܦܐ',
'loginreqlink' => 'ܥܘܠ',
'newarticle'   => '(ܚܕܬܐ)',
'yourtext'     => 'ܟܬܒܝ̈ܟ',

# History pages
'historyempty' => '(ܣܦܝܩܐ)',

# Preferences page
'mypreferences' => 'ܦܪ̈ܝܫܘܬܝ',
'math'          => 'ܡܬܡܐܛܝܩܘܬܐ',
'prefs-rc'      => 'ܫܘ̈ܚ̈ܠܦܐ ܚܕ̈ܬܐ',
'saveprefs'     => 'ܚܡܝ',

# Recent changes
'recentchanges' => 'ܫܚ̈ܠܦܬܐ ܚܕ̈ܬܬܐ',

# Recent changes linked
'recentchangeslinked' => 'ܫܚܠ̈ܦܐ ܕܡܝܐ',

# Upload
'upload'   => 'ܛܥܢܐ ܦ̮ܥܝܠ',
'filename' => 'ܫܡܐ ܕܫܘܦܝܢܐ',

# Image list
'ilsubmit'       => 'ܛܥܘܝܐ',
'imagelist_name' => 'ܫܡܐ',

# Random page
'randompage' => 'ܡܓܠܬܐ ܚܘܝܚܐ',

# Miscellaneous special pages
'allpages'     => 'ܟܠ ܦܐܬܐ',
'specialpages' => 'ܦܐܬܘܬ̈ܐ ܪܫܝܐ',
'move'         => 'ܡܓ̰ܘܓ̰',

# Book sources
'booksources'    => 'ܙܠ',
'booksources-go' => 'ܙܠ',

# Special:Allpages
'allarticles'    => 'ܟܠ ܡ̈ܓܠܐ',
'allpagessubmit' => 'ܙܠ',

# E-mail user
'emailfrom' => 'ܡܢ',
'emailto'   => 'ܥܠ',

# Watchlist
'watchlist'   => 'ܟܬܒܢܘܬܝ',
'mywatchlist' => 'ܟܬܒܢܘܬܝ',
'watch'       => 'ܢܛܪ',
'unwatch'     => 'ܠܐ ܢܛܪ',

# Restrictions (nouns)
'restriction-edit' => 'ܫܚܠܦ',
'restriction-move' => 'ܡܓ̰ܘܓ̰',

# Namespace form on various pages
'blanknamespace' => '(ܪܫܝܐ)',

# Contributions
'mycontris' => 'ܗܝܪܬܝ',

# What links here
'whatlinkshere' => 'ܡܐ ܐܣܝܪܐ ܠܟܐ؟',

# Namespace 8 related
'allmessagesname' => 'ܫܡܐ',

# Attribution
'and' => 'ܘ',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ܟܠ',
'namespacesall'    => 'ܟܠ',

# action=purge
'confirm_purge_button' => 'ܛܒ',

# Multipage image navigation
'imgmultigo' => 'ܙܠ!',

# Table pager
'table_pager_limit_submit' => 'ܙܠ',

);
