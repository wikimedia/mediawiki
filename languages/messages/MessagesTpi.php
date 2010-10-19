<?php
/** Tok Pisin (Tok Pisin)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Iketsi
 * @author Wantok
 * @author Wytukaze
 * @author לערי ריינהארט
 */

$specialPageAliases = array(
	'Userlogin'                 => array( 'Yusa login' ),
	'Userlogout'                => array( 'Yusa logaut' ),
	'CreateAccount'             => array( 'Mekim nupela login' ),
	'Preferences'               => array( 'Ol laik bilong mi' ),
	'Watchlist'                 => array( 'Lukautbuk' ),
	'Recentchanges'             => array( 'Nupela senis' ),
	'Upload'                    => array( 'Salim media fail' ),
	'Randompage'                => array( 'Soim wanpela pes' ),
	'Specialpages'              => array( 'Sipesol pes' ),
	'Contributions'             => array( 'Ol senis bilong yusa' ),
	'Emailuser'                 => array( 'Imel yusa' ),
	'Confirmemail'              => array( 'Orait long imel' ),
	'Whatlinkshere'             => array( 'Ol link ikam long hia' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Putim lain ananit long ol link:',
'tog-highlightbroken'         => 'Soim ol link long ol pes i no stap yet <a href="" class="new">olsem</a> (o: olsem<a href="" class="internal">?</a>).',
'tog-justify'                 => "Soim ol paragraf i pulmapim sipes long lephan i go long raithan (''justify'')",
'tog-hideminor'               => 'Noken soim ol liklik senis insait long ol nupela senis',
'tog-extendwatchlist'         => 'Larim lukautbuk i go longpela long soim olgeta senis',
'tog-usenewrc'                => 'Moa beta stail bilong nupela senis (i nidim JavaScript)',
'tog-numberheadings'          => 'Putim ol namba i go long wanwan hap bilong pes',
'tog-showtoolbar'             => 'Soim ol liklik link long wokim senis kwiktaim (i nidim JavaScript)',
'tog-editondblclick'          => 'Senisim pes taim yu paitim tupela taim kwiktaim (i nidim JavaScript)',
'tog-editsection'             => 'Soim ol [senisim] link long wanwan hap bilong ol pes',
'tog-editsectiononrightclick' => 'Senisim ol hap bilong pes taim yu paitim nem bilong hap<br />wantaim raithan-klik (i nidim Javascript)',
'tog-showtoc'                 => 'Soim ol nem bilong hap insait long liklik bokis, taim igat antap long 3 hap long pes',
'tog-rememberpassword'        => 'Holim nem bilong yusa bilong mi long dispela kompiuta (for a maximum of $1 {{PLURAL:$1|day|days}})',
'tog-watchcreations'          => 'Putim ol nupela pes mi wokim long lukautbuk',
'tog-watchdefault'            => 'Putim ol pes mi senisim long lukautbuk bilong mi',
'tog-watchmoves'              => 'Putim ol pes mi surikim long lukautbuk bilong mi',
'tog-watchdeletion'           => 'Putim ol pes mi rausim long lukautbuk bilong mi',
'tog-previewontop'            => 'Soim pes mi senisim (pastaim long raitim) antap long bokis bilong wokim senis',
'tog-previewonfirst'          => 'Soim pes mi senisim pastaim long raitim',
'tog-enotifwatchlistpages'    => 'Salim imel (e-mail) long mi taim wanpela pes mi lukautim i senis',
'tog-shownumberswatching'     => 'Soim hamas yusa i lukautim pes',
'tog-uselivepreview'          => 'Soim ol senis kwiktaim taim mi wokim (i nidim Javascript)',
'tog-watchlisthideown'        => 'Haitim ol senis mi wokim long lukautbuk bilong mi',
'tog-watchlisthidebots'       => 'Haitim ol senis ol bot i wokim long lukautbuk bilong mi',
'tog-watchlisthideminor'      => 'Haitim ol liklik senis long lukautbuk bilong mi',

# Dates
'sunday'        => 'Sande',
'monday'        => 'Mande',
'tuesday'       => 'Tunde',
'wednesday'     => 'Trinde',
'thursday'      => 'Fonde',
'friday'        => 'Fraide',
'saturday'      => 'Sarere',
'sun'           => 'San',
'mon'           => 'Man',
'tue'           => 'Tun',
'wed'           => 'Tri',
'thu'           => 'Fon',
'fri'           => 'Frai',
'sat'           => 'Sar',
'january'       => 'Janueri',
'february'      => 'Februeri',
'march'         => 'Mas',
'april'         => 'Epril',
'may_long'      => 'Me',
'june'          => 'Jun',
'july'          => 'Julai',
'august'        => 'Ogas',
'september'     => 'Septemba',
'october'       => 'Oktoba',
'november'      => 'Novemba',
'december'      => 'Disemba',
'january-gen'   => 'Janueri',
'february-gen'  => 'Februeri',
'march-gen'     => 'Mas',
'april-gen'     => 'Epril',
'may-gen'       => 'Me',
'june-gen'      => 'Jun',
'july-gen'      => 'Julai',
'august-gen'    => 'Ogas',
'september-gen' => 'Septemba',
'october-gen'   => 'Oktoba',
'november-gen'  => 'Novemba',
'december-gen'  => 'Disemba',
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Mas',
'apr'           => 'Epr',
'may'           => 'Me',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Oga',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nov',
'dec'           => 'Dis',

# Categories related messages
'pagecategories'        => '{{PLURAL:$1|Grup|Ol grup}}',
'category_header'       => 'Ol pes insait long grup "$1"',
'subcategories'         => 'Ol grup insait long grup',
'category-media-header' => 'Ol media (olsem piksa) insait long grup "$1"',
'category-empty'        => "''Dispela grup i no gat wanpela pes o media (olsem piksa) insait long en nau.''",

'newwindow'     => '(bai kamap long nupela windo)',
'cancel'        => 'Toromwe senis',
'moredotdotdot' => 'Moa...',
'mypage'        => 'Pes bilong mi',
'mytalk'        => 'Toktok bilong mi',
'navigation'    => 'Ol bikpela pes',
'and'           => '&#32;na',

# Cologne Blue skin
'qbfind'         => 'Painim',
'qbedit'         => 'Senisim',
'qbpageoptions'  => 'Dispela pes',
'qbmyoptions'    => 'Ol pes bilong mi',
'qbspecialpages' => 'Ol sipesol pes',

# Vector skin
'vector-action-delete' => 'Rausim',
'vector-action-move'   => 'Surikim',
'vector-view-edit'     => 'Senisim',
'vector-view-history'  => 'Lukim histori',

'errorpagetitle'   => 'Samting i kranki',
'tagline'          => 'Long {{SITENAME}}',
'help'             => 'Halivim mi',
'search'           => 'Painim',
'searchbutton'     => 'Painim',
'go'               => 'Go',
'searcharticle'    => 'Go',
'history'          => 'Ol senis long dispela pes',
'history_short'    => 'Ol senis',
'edit'             => 'Senisim',
'delete'           => 'Rausim',
'deletethispage'   => 'Rausim dispela pes',
'newpage'          => 'Nupela pes',
'talkpagelinktext' => 'Toktok',
'specialpage'      => 'Sipesol pes',
'talk'             => 'Toktok',
'toolbox'          => 'Sipesol bokis',
'categorypage'     => 'Lukim pes bilong grup',
'otherlanguages'   => 'Long ol narapela tokples',
'jumptonavigation' => 'ol bikpela pes',
'jumptosearch'     => 'painim',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'currentevents'        => 'Karen afeas',
'currentevents-url'    => 'Project:Karen afeas',
'edithelp'             => 'Halivim mi long pasin bilong wokim senis',
'edithelppage'         => 'Help:Senisim',
'mainpage'             => 'Fran Pes',
'mainpage-description' => 'Fran Pes',
'portal'               => 'Bung ples',
'portal-url'           => 'Project:Bung ples',

'badaccess' => 'Kranki long tok orait.',

'ok'             => 'OK',
'editsection'    => 'senisim',
'editold'        => 'senisim',
'editlink'       => 'senisim',
'feedlinks'      => 'Fid:',
'site-rss-feed'  => '$1 RSS fid',
'site-atom-feed' => '$1 Atom fid',
'page-rss-feed'  => '"$1" RSS fid',
'page-atom-feed' => '"$1" Atom fid',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Stori',
'nstab-user'      => 'Pes bilong yusa',
'nstab-media'     => 'Media pes',
'nstab-special'   => 'Sipesol pes',
'nstab-image'     => 'Fail',
'nstab-mediawiki' => 'Toksave',
'nstab-category'  => 'Grup',

# General errors
'error'         => 'Samting i kranki',
'badtitle'      => 'Nogutpela titel',
'viewsourcefor' => 'long $1',

# Login and logout pages
'logout'             => 'Logaut',
'userlogout'         => 'Logaut',
'loginlanguagelabel' => 'Toktok: $1',

# JavaScript password checks
'password-strength-bad'  => 'NOGUT',
'password-strength-good' => 'gut',

# Edit page toolbar
'link_sample'    => 'Link taitel',
'extlink_sample' => 'http://www.example.com link taitel',
'media_tip'      => 'Link bilong fail',

# Edit pages
'summary'           => 'Liklik toksave bilong senis:',
'subject'           => 'Nem bilong pes (o hap bilong pes):',
'minoredit'         => 'Dispela emi liklik senis',
'watchthis'         => 'Putim dispela pes long lukautbuk bilong mi',
'savearticle'       => 'Raitim pes',
'preview'           => 'Pes wantaim senis (pastaim long raitim)',
'showpreview'       => 'Soim pes wantaim senis (pastaim long raitim)',
'showlivepreview'   => 'Soim senis kwiktaim taim mi wokim (pastaim long raitim)',
'showdiff'          => 'Soim ol senis',
'summary-preview'   => 'Toksave bilong senis bai luk olsem:',
'subject-preview'   => 'Nem bilong pes (o hap bilong pes) bai olsem:',
'newarticle'        => '(Nupela)',
'copyrightwarning'  => "Toksave: olgeta senis yu wokim long long {{SITENAME}} bai stap ananit long tokorait $2 (lukim $1 long painimaut moa long dispela). Sapos yu no laikim narapela manmeri long senisim olgeta, o salim dispela i go long ol kainkain hap, noken raitim long hia.<br />
Na tu yu tok tru nau olsem yu raitim dispela yu yet, o yu kisim long wanpela hap we lo i tok olsem i orait long kisim (Tok Inglis: <i>public domain</i>).
'''YU NOKEN RAITIM WANPELA SAMTING SAPOS YU NO WOKIM YU YET, O YU KISIM TOKORAIT LONG PUTIM LONG HIA!'''",
'copyrightwarning2' => "Toksave: olgeta senis yu wokim long long {{SITENAME}} bai inap senis o raus long han bilong ol narapela manmeri. Sapos yu no laikim narapela manmeri long senisim olgeta samting yu raitim, o salim dispela i go long ol kainkain hap, noken raitim long hia.<br />
Na tu yu tok tru nau olsem yu raitim dispela yu yet, o yu kisim long wanpela hap we lo i tok olsem i orait long kisim (Tok Inglis: <i>public domain</i>). Lukim $1 long painimaut moa long dispela.<br />
'''YU NOKEN RAITIM WANPELA SAMTING IGAT COPYRIGHT LONG EN (NARAPELA MANMERI I RAITIM)!'''",
'templatesused'     => 'Dispela pes i yusim ol templet:',

# Revision deletion
'revdelete-uname' => 'yusanem',

# Revision move
'revmove-nullmove-title' => 'Nogutpela titel',

# Search results
'searchprofile-everything'       => 'Ol',
'searchprofile-articles-tooltip' => 'Painim long $1',
'searchprofile-project-tooltip'  => 'Painim long $1',
'search-interwiki-more'          => '(moa)',
'searchall'                      => 'ol',
'powersearch'                    => 'Mobeta Painim',
'powersearch-legend'             => 'Mobeta Painim',
'powersearch-ns'                 => 'Painim long ol nem',
'powersearch-field'              => 'Painim long',

# Preferences page
'preferences'              => 'Ol laik',
'mypreferences'            => 'Ol laik bilong mi',
'prefs-edits'              => 'Hamas senis:',
'skin-preview'             => 'pes mi senisim, pastaim long raitim',
'prefs-math'               => 'Matematiks',
'prefs-rc'                 => 'Nupela senis',
'prefs-watchlist'          => 'Lukautbuk',
'searchresultshead'        => 'Painim',
'timezoneregion-africa'    => 'Aprika',
'timezoneregion-america'   => 'Amerika',
'timezoneregion-asia'      => 'Esia',
'timezoneregion-atlantic'  => 'Atlantik solwara',
'timezoneregion-australia' => 'Ostrelia',
'timezoneregion-europe'    => 'Yurop',
'timezoneregion-pacific'   => 'Pasifik solwara',
'prefs-files'              => 'Ol fail',
'username'                 => 'Yusanem:',
'yourlanguage'             => 'Toktok:',
'gender-male'              => 'Man',
'gender-female'            => 'Meri',

# User rights
'userrights-groupsmember' => 'Memba bilong:',

# Groups
'group-user' => 'Ol yusa',
'group-bot'  => 'Ol bot',
'group-all'  => '(ol)',

'group-user-member' => 'yusa',
'group-bot-member'  => 'bot',

'grouppage-user' => '{{ns:project}}:Ol yusa',

# Rights
'right-move'     => 'Surikim ol pes',
'right-movefile' => 'Surikim ol fail',

# Recent changes
'recentchanges'     => 'Nupela senis',
'rcnote'            => "Ananit yu lukim '''$1 senis''' long '''$2 de''' igo pinis, na i olsem long $3.",
'rcshowhidebots'    => '$1 ol bot',
'minoreditletter'   => 'm',
'newpageletter'     => 'N',
'boteditletter'     => 'b',
'rc_categories'     => 'Soim ol senis insait long ol dispela grup tasol (raitim wantaim "|" namel long wanwan)',
'rc_categories_any' => 'Olgeta',

# Recent changes linked
'recentchangeslinked'         => 'Ol senis klostu',
'recentchangeslinked-feed'    => 'Ol senis klostu',
'recentchangeslinked-toolbox' => 'Ol senis klostu',

# Upload
'upload' => 'Salim media fail',

# Special:ListFiles
'imgfile'        => 'fail',
'listfiles_user' => 'Yusa',

# File description page
'file-anchor-link' => 'Fail',
'filehist-user'    => 'Yusa',
'imagelinks'       => 'Ol fail link',
'shared-repo-from' => 'long $1',

# File deletion
'filedelete'        => 'Rausim $1',
'filedelete-submit' => 'Rausim',

# Random page
'randompage' => 'Soim wanpela pes',

# Statistics
'statistics-pages' => 'Ol pes',

# Miscellaneous special pages
'ncategories'             => '$1 {{PLURAL:$1|grup|grup}}',
'nmembers'                => '$1 {{PLURAL:$1|memba|memba}}',
'uncategorizedpages'      => 'Ol pes i no stap insait long grup',
'uncategorizedcategories' => 'Ol grup i no stap insait long grup',
'uncategorizedimages'     => 'Ol piksa i no stap insait long grup',
'uncategorizedtemplates'  => 'Ol templet i no stap insait long grup',
'unusedcategories'        => 'Ol grup i no gat samting insait long ol',
'unusedimages'            => 'Ol media (olsem piksa) i no gat wanpela pes i soim ol',
'wantedcategories'        => 'Ol grup i no stap yet tasol igat link i kam long ol',
'wantedpages'             => 'Ol pes i no stap yet tasol igat link i kam long ol',
'mostlinked'              => 'Ol pes i gat planti link i kam long ol',
'mostlinkedcategories'    => 'Ol grup igat planti link i kam long ol',
'mostlinkedtemplates'     => 'Ol templet igat planti link i kam long ol',
'mostcategories'          => 'Ol pes bilong buk istap insait long planti grup',
'newpages'                => 'Ol nupela pes',
'move'                    => 'Surikim',
'movethispage'            => 'Surikim dispela pes',
'unusedcategoriestext'    => 'Ol dispela grup istap yet, tasol i no gat wanpela pes o grup i stap insait long ol.',

# Book sources
'booksources-go' => 'Go',

# Special:Log
'specialloguserlabel' => 'Yusa:',

# Special:AllPages
'allpages'       => 'Olgeta pes',
'alphaindexline' => '$1 long $2',
'allarticles'    => 'Ol pes',
'allpagessubmit' => 'Go',

# Special:Categories
'categories'         => 'Ol grup',
'categoriespagetext' => 'Ol dispela grup istap.
[[Special:UnusedCategories|Unused categories]] are not shown here.
Also see [[Special:WantedCategories|wanted categories]].',

# Special:LinkSearch
'linksearch-ok' => 'Painim',

# Watchlist
'watchlist'            => 'Lukautbuk bilong mi',
'mywatchlist'          => 'Lukautbuk bilong mi',
'nowatchlist'          => 'Nogat wanpela samting istap long lukautbuk bilong yu.',
'watchlistanontext'    => 'Yu mas $1 long lukim o senisim ol samting long lukautbuk bilong yu.',
'watchnologintext'     => 'Yu mas [[Special:UserLogin|login]] long senisim lukautbuk bilong yu.',
'addedwatch'           => 'Igo insait long lukautbuk',
'addedwatchtext'       => "Pes \"[[:\$1]]\" igo insait long [[Special:Watchlist|lukautbuk]] bilong yu nau.
Bai yu lukim ol nupela senis long dispela pes, na pes toktok bilong en, long lukautbuk,
na dispela pes bai kamap '''strongpela''' long [[Special:RecentChanges|pes bilong ol nupela senis]]
na olsem bai isi long lukim em.

Sapos yu laik rausim dispela pes long lukautbuk bilong yu bihain, paitim \"Pinis long lukautim\" taim yu lukim pes.",
'removedwatch'         => 'Raus pinis long lukautbuk',
'removedwatchtext'     => 'Pes "[[:$1]]" i raus pinis long lukautbuk bilong yu.',
'watch'                => 'Lukautim',
'watchthispage'        => 'Lukautim dispela pes',
'unwatch'              => 'Pinis long lukautim',
'unwatchthispage'      => 'Pinis long lukautim',
'watchlist-details'    => '$1 pes istap long lukautbuk (dispela namba i no kaunim ol pes bilong toktok).',
'wlheader-showupdated' => "* Ol pes i senis pinis bihain long taim yu lukim ol igat nem i '''strongpela'''",

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Luklukim...',

'enotif_reset'                 => 'Makim olgeta pes olsem mi lukim pinis',
'enotif_impersonal_salutation' => 'yusa long {{SITENAME}}',

# Delete
'delete-confirm' => 'Rausim $1',
'delete-legend'  => 'Rausim',

# Protect
'prot_1movedto2' => '[[$1]] i surik i go long [[$2]] pinis',

# Restrictions (nouns)
'restriction-edit' => 'Senisim',

# Contributions
'contributions' => 'Ol senis yusa i wokim',
'mycontris'     => 'Ol senis mi wokim',

'sp-contributions-talk'   => 'Toktok',
'sp-contributions-submit' => 'Painim',

# What links here
'whatlinkshere'           => 'Ol link ikam long hia',
'whatlinkshere-page'      => 'Pes:',
'whatlinkshere-links'     => '← ol link',
'whatlinkshere-hidelinks' => '$1 ol link',

# Block/unblock
'ipbotheroption'     => 'narapela',
'ipblocklist-submit' => 'Painim',

# Move page
'movearticle'     => 'Surikim pes:',
'newtitle'        => 'Long nupela titel:',
'movepagebtn'     => 'Surikim',
'pagemovedsub'    => 'Pes i surik pinis',
'articleexists'   => 'Wanpela pes wantaim dispela nem i stap pinis, o dispela nem i no stret.
Yu mas painim narapela nem.',
'talkexists'      => "'''Pes bilong buk i surik pinis, tasol pes bilong toktok i no inap surik, bilong wanem wanpela pes bilong toktok istap pinis wantaim dispela nam.  Yu mas pasim wantaim tupela pes bilong toktok yu yet.'''",
'movedto'         => 'i surik i go long',
'movetalk'        => 'Surikim pes bilong toktok wantaim',
'1movedto2'       => '[[$1]] i surik i go long [[$2]] pinis',
'movelogpage'     => 'Buk bilong ol surik',
'movelogpagetext' => 'Hia yumi lukim ol pes i surik pinis.',

# Namespace 8 related
'allmessages' => 'Ol toksave bilong sistem',

# Tooltip help for the actions
'tooltip-pt-logout'         => 'Logaut',
'tooltip-search'            => 'Painim {{SITENAME}}',
'tooltip-feed-rss'          => 'RSS fid bilong dispela pes',
'tooltip-feed-atom'         => 'Atom fid bilong dispela pes',
'tooltip-t-upload'          => 'Salim media fail',
'tooltip-ca-nstab-category' => 'Lukim grup',

# Attribution
'siteuser' => '{{SITENAME}} yusa $1',
'others'   => 'ol narapela',

# Special:NewFiles
'ilsubmit' => 'Painim',

# Metadata
'metadata' => 'Metadata',

# EXIF tags
'exif-imagedescription' => 'Piksa titel',

'exif-meteringmode-255' => 'Narapela',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ol',
'imagelistall'     => 'ol',
'watchlistall2'    => 'ol',
'namespacesall'    => 'ol',
'monthsall'        => 'ol',
'limitall'         => 'ol',

# Trackbacks
'trackbackremove' => '([$1 Rausim])',

# Table pager
'table_pager_limit_submit' => 'Go',

# Watchlist editor
'watchlistedit-numitems'       => 'Igat {{PLURAL:$1|1 samting|$1 samting}} insait long lukautbuk bilong yu (ol pes bilong toktok i no stap long dispela namba).',
'watchlistedit-noitems'        => 'Nogat wanpela samting long lukautbuk bilong yu.',
'watchlistedit-normal-title'   => 'Senisim lukautbuk',
'watchlistedit-normal-legend'  => 'Rausim ol samting long lukautbuk',
'watchlistedit-normal-explain' => 'Ananit yu lukim ol samting long lukautbuk. Long rausim wanpela samting, makim liklik boxis long sait bilong en, na paitim "Rausim ol samting".  Na tu yu inap [[Special:Watchlist/raw|senisim lukautbuk long wanpela bokis]].',
'watchlistedit-normal-submit'  => 'Rausim ol samting',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Wanpela|$1}} samting i raus pinis long lukautbuk bilong yu:',
'watchlistedit-raw-title'      => 'Senisim lukautbuk long wanpela bokis',
'watchlistedit-raw-legend'     => 'Senisim lukautbuk insait long wanpela bokis',
'watchlistedit-raw-explain'    => 'Ananit yu lukim ol samting long lukautbuk bilong yu insait long wanpela bokis.
	Yu inap putim sampela moa samting igo insait, o rausim sampela ol samting. Putim
	wanpela samting i go long wanwan lain.  Taim yu pinisim ol senis, paitim "Senisim lukautbuk".
	Na tu yu inap [[Special:Watchlist/edit|senisim lukautbuk long planti liklik bokis]].',
'watchlistedit-raw-titles'     => 'Ol samting:',
'watchlistedit-raw-submit'     => 'Senisim lukautbuk',
'watchlistedit-raw-done'       => 'Lukautbuk bilong yu i senis pinis.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|wanpela|$1}} samting igo insait:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Wanpela|$1}} samting i raus pinis:',

# Watchlist editing tools
'watchlisttools-view' => 'Lukim ol senis',
'watchlisttools-edit' => 'Lukim na senisim lukautbuk',
'watchlisttools-raw'  => 'Senisim lukautbuk insait long wanpela bokis',

# Special:Version
'version-other' => 'Narapela',

# Special:FilePath
'filepath-page'   => 'Fail:',
'filepath-submit' => 'Go',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Painim',

# Special:SpecialPages
'specialpages' => 'Ol sipesol pes',

# Special:Tags
'tags-edit' => 'senisim',

# Special:ComparePages
'compare-page1' => 'Pes 1',
'compare-page2' => 'Pes 2',

# HTML forms
'htmlform-selectorother-other' => 'Narapela',

);
