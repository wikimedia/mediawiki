<?php
/** Sranan Tongo (Sranan Tongo)
 *
 * @addtogroup Language
 *
 * @author Adfokati
 */

$fallback = 'nl';

$messages = array(
# User preference toggles
'tog-underline'       => 'Gi lin a den skaki:',
'tog-highlightbroken' => 'Gi skaki na no peprewoysi <a href="" class="new">leki dyaso</a> (alternati: leki disi<a href="" class="internal">?</a>).',
'tog-justify'         => 'Paragraf leti meki',
'tog-numberheadings'  => 'Autonumro edelen',
'tog-showtoolbar'     => 'Kenki-tubu-box libi si (JavaScript)',
'tog-editwidth'       => 'Kenki-box is o folbre',

'underline-always' => 'Alaten',
'underline-never'  => 'Noyti',

'skinpreview' => '(Fusi)',

# Dates
'sunday'        => 'sonde',
'monday'        => 'mundey',
'tuesday'       => 'tude-wroko',
'wednesday'     => 'dridey-wroko',
'thursday'      => 'fode-wroko',
'friday'        => 'freyda',
'saturday'      => 'satra',
'sun'           => 'son',
'mon'           => 'mun',
'tue'           => 'tud',
'wed'           => 'dri',
'thu'           => 'fod',
'fri'           => 'fre',
'sat'           => 'sat',
'january'       => 'foswan mun',
'february'      => 'fostu mun',
'march'         => 'fosdri mun',
'april'         => 'fosfo mun',
'may_long'      => 'fosfeyfi mun',
'june'          => 'fossiksi mun',
'july'          => 'fosseybi mun',
'august'        => 'fosayti mun',
'september'     => 'fosneygi mun',
'october'       => 'fostin mun',
'november'      => 'foserfu mun',
'december'      => 'fostwarfu mun',
'january-gen'   => 'foswan mun',
'february-gen'  => 'fostu mun',
'march-gen'     => 'fosdri mun',
'april-gen'     => 'fosfo mun',
'may-gen'       => 'fosfeyfi mun',
'june-gen'      => 'fossiksi mun',
'july-gen'      => 'fosseybi mun',
'august-gen'    => 'fosayti mun',
'september-gen' => 'fosneygi mun',
'october-gen'   => 'fostin mun',
'november-gen'  => 'foserfu mun',
'december-gen'  => 'fostwarfu mun',
'jan'           => 'wan',
'feb'           => 'tu',
'mar'           => 'dri',
'apr'           => 'fo',
'may'           => 'fosfeyfi mun',
'jun'           => 'sik',
'jul'           => 'sey',
'aug'           => 'ayt',
'sep'           => 'ney',
'oct'           => 'tin',
'nov'           => 'erf',
'dec'           => 'twa',

# Bits of text used by many pages
'categories'            => 'Den guru',
'pagecategories'        => '{{PLURAL:$1|Guru|Guru}}',
'category_header'       => 'Peprewoysi ini guru “$1”',
'subcategories'         => 'Subguru',
'category-media-header' => 'Media ini guru “$1”',

'about'          => 'Abra',
'article'        => 'Papira',
'newwindow'      => '(opo ini wan nyon fensre)',
'cancel'         => 'Broko',
'qbfind'         => 'Suku',
'qbbrowse'       => 'Wiwiri',
'qbedit'         => 'Kenki',
'qbpageoptions'  => 'Disi papira',
'qbpageinfo'     => 'Papira-infrumasi',
'qbmyoptions'    => 'Mi peprewoysi',
'qbspecialpages' => 'Spesyal peprewoysi',
'moredotdotdot'  => 'Pasa...',
'mypage'         => 'Mi papira',
'mytalk'         => 'Mi taki',
'anontalk'       => 'Taki fu disi IP',
'navigation'     => 'Lukubun',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'   => 'Fowtu',
'returnto'         => 'Gwe na $1.',
'tagline'          => 'Fu {{SITENAME}}',
'help'             => 'Yepi',
'search'           => 'Suku',
'searchbutton'     => 'Suku',
'go'               => 'Go',
'searcharticle'    => 'Go',
'history'          => 'Stori fu a papira',
'history_short'    => 'Stori',
'info_short'       => 'Infrumasie',
'printableversion' => 'Libiwroko e kwinsi',
'permalink'        => 'Permalink',
'print'            => 'Kwinsi',
'edit'             => 'Kenki',
'editthispage'     => 'Kenki disi papira',
'delete'           => 'Trowe',
'deletethispage'   => 'Disi papira trowe',
'undelete_short'   => 'Otrowe $1 {{PLURAL:$1|kenki|kenki}}',
'newpage'          => 'Nyun papira',
'talkpage'         => 'Taki',
'talkpagelinktext' => 'Taki',
'specialpage'      => 'Spesyal papira',
'personaltools'    => 'Pesoonli tubu',
'articlepage'      => 'Papira libi si',
'talk'             => 'Taki',
'views'            => 'Den kibri',
'toolbox'          => 'Tubu-box',
'userpage'         => 'Papira fu masyin libi si',
'mediawikipage'    => 'Papira fu boskopu libi si',
'templatepage'     => 'Ankra libi si',
'viewhelppage'     => 'Papira fu yibi libi si',
'categorypage'     => 'Gurupapira libi si',
'viewtalkpage'     => 'Taki libi si',
'otherlanguages'   => 'Ini tra tongo',
'redirectedfrom'   => '(Stir fu $1)',
'redirectpagesub'  => 'Stirpapira',
'jumpto'           => 'Go na:',
'jumptonavigation' => 'lukubun',
'jumptosearch'     => 'suku',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Abra {{SITENAME}}',
'copyrightpagename' => '{{SITENAME}} kopireg',
'copyrightpage'     => '{{ns:project}}:Kopireg',
'edithelp'          => 'Yibi a kenki',
'edithelppage'      => 'Help:Kenki',
'helppage'          => 'Help:Inot',
'mainpage'          => 'Fruwondruwiwiri',

'ok'                      => 'oki',
'retrievedfrom'           => 'Riti fu "$1"',
'youhavenewmessages'      => 'Yu abi $1 ($2).',
'newmessageslink'         => 'nyun boskopu',
'youhavenewmessagesmulti' => 'Yu abi nyun boskopu opo $1',
'editsection'             => 'kenki',
'editold'                 => 'kenki',
'editsectionhint'         => 'Kenki gron: $1',
'toc'                     => 'Inut',
'showtoc'                 => 'libi si',
'hidetoc'                 => 'kibri',
'thisisdeleted'           => '$1 libi si efu otrowe?',
'viewdeleted'             => '$1 libi si?',
'feedlinks'               => 'Nyan:',
'site-rss-feed'           => '$1 RSS-nyan',
'site-atom-feed'          => '$1 Atom-nyan',
'page-rss-feed'           => '“$1” RSS-nyan',
'page-atom-feed'          => '“$1” Atom-nyan',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Papira',
'nstab-user'      => 'Papira fu a masyin',
'nstab-media'     => 'Papira fu media',
'nstab-special'   => 'Spesyal',
'nstab-mediawiki' => 'Boskopu',
'nstab-template'  => 'Ankra',
'nstab-help'      => 'Papira fu yibi',
'nstab-category'  => 'Guru',

# General errors
'error'         => 'Fowtu',
'databaseerror' => 'Fowtu fu database',
'viewsource'    => 'Bigin libi si',
'viewsourcefor' => 'fu $1',

# Login and logout pages
'logouttitle'        => 'Gwe efu masyin',
'loginpagetitle'     => 'Nen fu masyin',
'yourname'           => 'Nen fu masyin:',
'yourdomainname'     => 'Yu domein:',
'login'              => 'Kon',
'userlogin'          => 'Kon / Meki fu masyin',
'logout'             => 'Gwe',
'userlogout'         => 'Gwe',
'notloggedin'        => 'No kon',
'nologin'            => 'Yu abi no masyin? $1.',
'nologinlink'        => 'Meki fu masyin',
'createaccount'      => 'Meki fu masyin',
'gotaccount'         => 'Yu abi wansi wan masyin? $1.',
'gotaccountlink'     => 'Kon',
'youremail'          => 'E-mail:',
'username'           => 'Nen fu masyin:',
'uid'                => 'Masyin ID:',
'yourrealname'       => 'Yu tru nen:',
'yourlanguage'       => 'Tongo:',
'yournick'           => 'Habnen:',
'email'              => 'E-mail',
'loginsuccesstitle'  => 'Gu e kon',
'loginlanguagelabel' => 'Tongo: $1',

# Edit page toolbar
'bold_sample'     => 'Deki litiwrok',
'bold_tip'        => 'Fatu',
'italic_sample'   => 'Skoynise litiwrok',
'italic_tip'      => 'Skoynsi',
'headline_sample' => 'Edelen litiwrok',
'headline_tip'    => 'Edelen (2)',
'math_sample'     => 'Gi fomula',
'math_tip'        => 'Fomula fu teri (LaTeX)',

# Edit pages
'summary'         => 'Infrumasi-box',
'savearticle'     => 'Oponaki',
'preview'         => 'Fusi',
'showpreview'     => 'Fusi libi si pre kenki',
'showlivepreview' => 'Fusi libi si pre kenki (LIVE)',
'showdiff'        => 'Kenki libi si',
'summary-preview' => 'Fusi libi si fu infrumasi',
'blockedtitle'    => 'Masyin ben spikri',
'loginreqlink'    => 'kon',
'newarticle'      => '(Nyon)',
'editing'         => 'Kenki fu $1',
'yourtext'        => 'Yu litiwrok',

# Account creation failure
'cantcreateaccounttitle' => 'Kan masyin ni skopu.',

# History pages
'last'       => 'bakaseywan',
'deletedrev' => '[ben e trowe]',

# Revision feed
'history-feed-item-nocomment' => '$1 o $2', # user at time

# Revision deletion
'rev-delundel'   => 'libi si/no libi si',
'revisiondelete' => 'Versie trowe/otrowe',

# Diffs
'lineno' => 'Lin $1:',

# Search results
'searchsubtitle'        => "Y ben o suku na '''[[:$1]]'''",
'searchsubtitleinvalid' => "Yu ben o suku na '''$1'''",
'viewprevnext'          => '($1) ($2) ($3) libi si.',
'powersearch'           => 'Suku',

# Preferences page
'prefs-edits'              => 'Nomru fu kenki:',
'prefsnologin'             => 'No kon',
'qbsettings'               => 'Kwikbak',
'qbsettings-none'          => 'Nowan',
'qbsettings-fixedleft'     => 'Set na ku',
'qbsettings-fixedright'    => 'Set na pe',
'qbsettings-floatingleft'  => 'Han na ku',
'qbsettings-floatingright' => 'Han na pe',
'skin'                     => 'Buba',
'math'                     => 'Fomula',
'math_lexing_error'        => 'leksikografi fowtu',
'math_syntax_error'        => 'sintaki fowtu',
'prefs-rc'                 => 'Bakaseywan kenki',
'saveprefs'                => 'Oponaki',
'textboxsize'              => 'Kenki',
'rows'                     => 'Rei:',
'columns'                  => 'Kolum:',
'searchresultshead'        => 'Suku',
'timezonelegend'           => 'Gron fu ten',
'localtime'                => 'Lokali ten',
'timezoneoffset'           => 'Ski ini ten¹',

# Groups
'group'               => 'Guru:',
'group-autoconfirmed' => 'Registiri masyin',
'group-bot'           => 'Bot',
'group-sysop'         => 'Sesopu',
'group-bureaucrat'    => 'Burokrati',
'group-all'           => '(ala)',

'group-autoconfirmed-member' => 'Registiri masyin',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Sesopu',
'group-bureaucrat-member'    => 'Burokrati',

'grouppage-autoconfirmed' => '{{ns:project}}:Registiri masyin',
'grouppage-bot'           => '{{ns:project}}:Bot',
'grouppage-sysop'         => '{{ns:project}}:Sesopu',
'grouppage-bureaucrat'    => '{{ns:project}}:Burokrati',

# User rights log
'rightsnone' => '(no)',

# Recent changes
'nchanges'          => '$1 {{PLURAL:$1|kenki|kenki}}',
'recentchanges'     => 'Bakaseywan kenki',
'rclistfrom'        => 'Libi a kenki si fu $1',
'rcshowhideminor'   => '$1 den pikin kenki',
'rcshowhidebots'    => '$1 den bot',
'rcshowhideliu'     => '$1 den masyin',
'rcshowhideanons'   => '$1 den IP',
'rcshowhidepatr'    => '$1 den kenki kontoli',
'rcshowhidemine'    => 'mi kenki $1',
'rclinks'           => 'A $1 bakaseywan kenki libi si ini a bakaseywan $2 dey<br />$3',
'diff'              => 'kenki',
'hist'              => 'stori',
'hide'              => 'kibri',
'show'              => 'libi si',
'minoreditletter'   => 'p',
'newpageletter'     => 'N',
'boteditletter'     => 'b',
'newsectionsummary' => '/* $1 */ nyon gron',

# Upload
'upload'        => 'Uploti',
'uploadbtn'     => 'Uploti',
'reupload'      => 'Ri-uploti',
'uploadnologin' => 'No kon',
'filedesc'      => 'Infrumasi-box',

# Image list
'ilsubmit'           => 'Suku',
'filehist-deleteall' => 'trowe ala',
'filehist-deleteone' => 'trowe disi',
'filehist-user'      => 'Masyin',
'imagelinks'         => 'Skaki',
'imagelist_name'     => 'Nen',

# MIME search
'mimesearch' => 'Suku opo MIME-type',
'mimetype'   => 'MIME-type:',

'brokenredirects-edit' => '(kenki)',

# Miscellaneous special pages
'nbytes'       => '$1 {{PLURAL:$1|a byte|den byte}}',
'allpages'     => 'Ala peprewoysi',
'specialpages' => 'Spesyal peprewoysi',
'newpages'     => 'Nyon peprewoysi',
'move'         => 'Dribi',

# Book sources
'booksources-go' => 'Suku',

'alphaindexline' => '$1 te $2',

# Special:Log
'log-search-submit' => 'Go',

# Special:Allpages
'allarticles'    => 'Ala peprewoysi',
'allpagessubmit' => 'Go',

# E-mail user
'emailfrom'    => 'Fu',
'emailto'      => 'A',
'emailmessage' => 'Boskopu',

# Watchlist
'watchlistfor'         => "(fru '''$1''')",
'wlshowlast'           => 'Libi den bakaseywan $1 yuru, $2 dey si ($3)',
'watchlist-hide-bots'  => 'Kibri kenki fu den bot',
'watchlist-hide-own'   => 'Kibri mi kenki',
'watchlist-hide-minor' => 'Kibri pikin kenki',

# Undelete
'undelete-search-submit' => 'Suku',

# Namespace form on various pages
'namespace'      => 'Nenpreki:',
'blanknamespace' => '(Edenenpreki)',

# Contributions
'contribsub2' => 'Fu $1 ($2)',

'sp-contributions-submit' => 'Suku',

# What links here
'linklistsub'         => '(Rei fu skaki)',
'whatlinkshere-links' => '← skaki na da',

# Block/unblock
'ipblocklist-submit' => 'Suku',

# Move page
'movepage'       => 'Dribi papira',
'movearticle'    => 'Dribi papira:',
'movenologin'    => 'No kon',
'movepagebtn'    => 'Dribi papira',
'movepage-moved' => '<big>\'\'\'"$1" ben dribi na "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'1movedto2'      => '[[$1]] ben dribi na [[$2]]',

# Namespace 8 related
'allmessagesname' => 'Nen',

# Tooltip help for the actions
'tooltip-pt-userpage'        => 'Mi papira',
'tooltip-pt-mytalk'          => 'Mi taki',
'tooltip-ca-talk'            => 'Taki abra disi papira',
'tooltip-ca-move'            => 'Dribi disi papira',
'tooltip-search'             => 'Suku ini {{SITENAME}}',
'tooltip-p-logo'             => 'Fruwondruwiwiri',
'tooltip-n-mainpage'         => 'Go na a Fruwondruwiwiri',
'tooltip-ca-nstab-user'      => 'Papira fu masyin libi si',
'tooltip-ca-nstab-media'     => 'Papira fu media libi si',
'tooltip-ca-nstab-mediawiki' => 'Boskopu libi si',
'tooltip-ca-nstab-template'  => 'Ankra libi si',
'tooltip-ca-nstab-help'      => 'Papira fu yibi libi si',
'tooltip-minoredit'          => 'Disi efu pikin kenki libi si',
'tooltip-save'               => 'Oponaki fu yu kenki',

# Attribution
'siteuser'  => '{{SITENAME}}-masyin $1',
'and'       => 'nanga',
'siteusers' => '{{SITENAME}}-masyin $1',

# Spam protection
'listingcontinuesabbrev' => 'pasa',

# Media information
'widthheightpage' => '$1×$2, $3 peprewoysi',

# Special:Newimages
'showhidebots' => '(Bot $1)',
'noimages'     => 'Noti a si.',

# Metadata
'metadata' => 'Metadata',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ala',
'imagelistall'     => 'ala',
'watchlistall2'    => 'ala',
'namespacesall'    => 'ala',
'monthsall'        => 'ala',

# action=purge
'confirm_purge_button' => 'oki',

# Multipage image navigation
'imgmultigo'      => 'Go!',
'imgmultigotopre' => 'Go na papira',

# Table pager
'ascending_abbrev'         => 'opo.',
'descending_abbrev'        => 'afo.',
'table_pager_limit_submit' => 'Go',

# Auto-summaries
'autosumm-new' => 'Nyon papira: $1',

);
