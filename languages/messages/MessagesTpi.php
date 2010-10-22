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
'tog-hidepatrolled'           => 'Noken soim ol lukluk senis insait long ol nupela senis',
'tog-newpageshidepatrolled'   => 'Noken soim ol lukluk senis insait long ol nupela pes',
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
'tog-showhiddencats'          => 'Soim grup long noken soim',

'underline-always' => 'Oltaim',
'underline-never'  => 'No gat',

# Font style option in Special:Preferences
'editfont-monospace' => 'Monospaced rait',
'editfont-sansserif' => 'Sans-serif rait',
'editfont-serif'     => 'Serif rait',

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
'pagecategories'           => '{{PLURAL:$1|Grup|Ol grup}}',
'category_header'          => 'Ol pes insait long grup "$1"',
'subcategories'            => 'Ol grup insait long grup',
'category-media-header'    => 'Ol media (olsem piksa) insait long grup "$1"',
'category-empty'           => "''Dispela grup i no gat wanpela pes o media (olsem piksa) insait long en nau.''",
'hidden-categories'        => '{{PLURAL:$1|Hait pinis grup|Ol hait pinis grup}}',
'hidden-category-category' => 'Ol grup i hait',
'listingcontinuesabbrev'   => 'moa',

'about'         => 'Long',
'article'       => 'Stori',
'newwindow'     => '(bai kamap long nupela windo)',
'cancel'        => 'Toromwe senis',
'moredotdotdot' => 'Moa...',
'mypage'        => 'Pes bilong mi',
'mytalk'        => 'Toktok bilong mi',
'anontalk'      => 'Tokim long dispela IP',
'navigation'    => 'Ol bikpela pes',
'and'           => '&#32;na',

# Cologne Blue skin
'qbfind'         => 'Painim',
'qbbrowse'       => 'Lukim',
'qbedit'         => 'Senisim',
'qbpageoptions'  => 'Dispela pes',
'qbmyoptions'    => 'Ol pes bilong mi',
'qbspecialpages' => 'Ol sipesol pes',

# Vector skin
'vector-action-delete'   => 'Rausim',
'vector-action-move'     => 'Surikim',
'vector-action-protect'  => 'Haitim',
'vector-view-create'     => 'Kirapim',
'vector-view-edit'       => 'Senisim',
'vector-view-history'    => 'Lukim histori',
'vector-view-view'       => 'Rit',
'vector-view-viewsource' => 'Lukim as',

'errorpagetitle'   => 'Samting i kranki',
'returnto'         => 'Go bek long $1',
'tagline'          => 'Long {{SITENAME}}',
'help'             => 'Halivim mi',
'search'           => 'Painim',
'searchbutton'     => 'Painim',
'go'               => 'Go',
'searcharticle'    => 'Go',
'history'          => 'Ol senis long dispela pes',
'history_short'    => 'Ol senis',
'info_short'       => 'Infomesen',
'print'            => 'Prinim',
'edit'             => 'Senisim',
'create'           => 'Kirapim',
'editthispage'     => 'Senisim dispela pes',
'create-this-page' => 'Kirapim dispela pes',
'delete'           => 'Rausim',
'deletethispage'   => 'Rausim dispela pes',
'protect'          => 'Haitim',
'protect_change'   => 'senisim',
'newpage'          => 'Nupela pes',
'talkpage'         => 'Toktok dispela pes',
'talkpagelinktext' => 'Toktok',
'specialpage'      => 'Sipesol pes',
'personaltools'    => 'Ol praivet tul',
'postcomment'      => 'Nupela seksen',
'talk'             => 'Toktok',
'views'            => 'Ol lukluk',
'toolbox'          => 'Sipesol bokis',
'userpage'         => 'Lukim pes bilong yusa',
'projectpage'      => 'Lukim pes bilong projek',
'imagepage'        => 'Lukim pes bilong fail',
'mediawikipage'    => 'Lukim pes bilong mesej',
'templatepage'     => 'Lukim long templet pes',
'viewhelppage'     => 'Lukim pes long halivim',
'categorypage'     => 'Lukim pes bilong grup',
'viewtalkpage'     => 'Lukim toktok',
'otherlanguages'   => 'Long ol narapela tokples',
'redirectedfrom'   => '(Nupela rot i pinis long $1)',
'redirectpagesub'  => 'Nupela rot',
'protectedpage'    => 'Pes i haitim',
'jumpto'           => 'Kalap long:',
'jumptonavigation' => 'ol bikpela pes',
'jumptosearch'     => 'painim',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Long {{SITENAME}}',
'aboutpage'            => 'Project:Long',
'copyrightpage'        => '{{ns:project}}:Ol laisens',
'currentevents'        => 'Karen afeas',
'currentevents-url'    => 'Project:Karen afeas',
'disclaimers'          => 'Ol tok warn long lo',
'edithelp'             => 'Halivim mi long pasin bilong wokim senis',
'edithelppage'         => 'Help:Senisim',
'mainpage'             => 'Fran Pes',
'mainpage-description' => 'Fran Pes',
'portal'               => 'Bung ples',
'portal-url'           => 'Project:Bung ples',
'privacy'              => 'Polisi long praivet',

'badaccess' => 'Kranki long tok orait.',

'ok'                 => 'OK',
'retrievedfrom'      => 'igat long "$1"',
'youhavenewmessages' => 'Yu havim $1 ($2).',
'newmessageslink'    => 'nupela tok',
'editsection'        => 'senisim',
'editold'            => 'senisim',
'viewsourceold'      => 'lukim as',
'editlink'           => 'senisim',
'viewsourcelink'     => 'lukim as',
'editsectionhint'    => 'Senisim seksen: $1',
'showtoc'            => 'soim',
'hidetoc'            => 'hait',
'viewdeleted'        => 'Lukim $1?',
'restorelink'        => '{{PLURAL:$1|wan rausim senis|$1 rausim senis}}',
'feedlinks'          => 'Fid:',
'site-rss-feed'      => '$1 RSS fid',
'site-atom-feed'     => '$1 Atom fid',
'page-rss-feed'      => '"$1" RSS fid',
'page-atom-feed'     => '"$1" Atom fid',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Stori',
'nstab-user'      => 'Pes bilong yusa',
'nstab-media'     => 'Media pes',
'nstab-special'   => 'Sipesol pes',
'nstab-project'   => 'Pes bilong projek',
'nstab-image'     => 'Fail',
'nstab-mediawiki' => 'Toksave',
'nstab-template'  => 'Templet',
'nstab-help'      => 'Pes bilong halivim',
'nstab-category'  => 'Grup',

# General errors
'error'         => 'Samting i kranki',
'badtitle'      => 'Nogutpela titel',
'viewsource'    => 'Lukim as',
'viewsourcefor' => 'long $1',

# Login and logout pages
'yourname'            => 'Yusanem:',
'yourpassword'        => 'Paswot:',
'logout'              => 'Logaut',
'userlogout'          => 'Logaut',
'gotaccountlink'      => 'Log in',
'createaccountmail'   => 'Long e-mel',
'createaccountreason' => 'As bilong en:',
'mailmypassword'      => 'E-mel nupela paswot',
'loginlanguagelabel'  => 'Toktok: $1',

# JavaScript password checks
'password-strength-bad'  => 'NOGUT',
'password-strength-good' => 'gut',

# Edit page toolbar
'link_sample'    => 'Link taitel',
'extlink_sample' => 'http://www.example.com link taitel',
'math_tip'       => 'Matematik formula (LaTeX)',
'media_tip'      => 'Link bilong fail',

# Edit pages
'summary'                => 'Liklik toksave bilong senis:',
'subject'                => 'Nem bilong pes (o hap bilong pes):',
'minoredit'              => 'Dispela emi liklik senis',
'watchthis'              => 'Putim dispela pes long lukautbuk bilong mi',
'savearticle'            => 'Raitim pes',
'preview'                => 'Pes wantaim senis (pastaim long raitim)',
'showpreview'            => 'Soim pes wantaim senis (pastaim long raitim)',
'showlivepreview'        => 'Soim senis kwiktaim taim mi wokim (pastaim long raitim)',
'showdiff'               => 'Soim ol senis',
'missingcommenttext'     => 'Plis raitim tingting daunbilo.',
'summary-preview'        => 'Toksave bilong senis bai luk olsem:',
'subject-preview'        => 'Nem bilong pes (o hap bilong pes) bai olsem:',
'blockedtitle'           => 'Yusa i pasim.',
'blockednoreason'        => 'Nogat as bilong en',
'newarticle'             => '(Nupela)',
'updated'                => '(i nupela)',
'editing'                => 'Senisim $1',
'editingsection'         => 'Senisim $1 (seksen)',
'editconflict'           => 'Kranki long senisim: $1',
'yourtext'               => 'Yupela ol wod',
'yourdiff'               => 'Ol arapela',
'copyrightwarning'       => "Toksave: olgeta senis yu wokim long long {{SITENAME}} bai stap ananit long tokorait $2 (lukim $1 long painimaut moa long dispela). Sapos yu no laikim narapela manmeri long senisim olgeta, o salim dispela i go long ol kainkain hap, noken raitim long hia.<br />
Na tu yu tok tru nau olsem yu raitim dispela yu yet, o yu kisim long wanpela hap we lo i tok olsem i orait long kisim (Tok Inglis: <i>public domain</i>).
'''YU NOKEN RAITIM WANPELA SAMTING SAPOS YU NO WOKIM YU YET, O YU KISIM TOKORAIT LONG PUTIM LONG HIA!'''",
'copyrightwarning2'      => "Toksave: olgeta senis yu wokim long long {{SITENAME}} bai inap senis o raus long han bilong ol narapela manmeri. Sapos yu no laikim narapela manmeri long senisim olgeta samting yu raitim, o salim dispela i go long ol kainkain hap, noken raitim long hia.<br />
Na tu yu tok tru nau olsem yu raitim dispela yu yet, o yu kisim long wanpela hap we lo i tok olsem i orait long kisim (Tok Inglis: <i>public domain</i>). Lukim $1 long painimaut moa long dispela.<br />
'''YU NOKEN RAITIM WANPELA SAMTING IGAT COPYRIGHT LONG EN (NARAPELA MANMERI I RAITIM)!'''",
'templatesused'          => '{{PLURAL:$1|Templet|Ol templet}} dispela pes i yusim:',
'templatesusedpreview'   => '{{PLURAL:$1|Templet|Ol templet}} dispela pes wantaim senis:',
'template-protected'     => '(haitim)',
'template-semiprotected' => '(hap-haitim)',
'hiddencategories'       => 'Dispela pes i stap memba long {{PLURAL:$1|1 grup hait|$1 ol grup hait}}',
'log-fulllog'            => 'Lukim long olgeta ripot',

# History pages
'viewpagelogs'     => 'Lukluk ol ripot bilong dispela pes',
'previousrevision' => '← Moa olpela',
'nextrevision'     => 'Moa yangpela →',
'cur'              => 'nau',
'histfirst'        => 'Tru olpela',
'histlast'         => 'Tru nupela',

# Revision deletion
'rev-delundel'    => 'soim/hait',
'pagehist'        => 'Histori long pes',
'revdelete-uname' => 'yusanem',
'revdelete-hid'   => 'bin hait $1',
'revdelete-unhid' => 'bin soim $1',

# Revision move
'revmove-nullmove-title' => 'Nogutpela titel',

# History merging
'mergehistory-from' => 'As pes:',

# Diffs
'lineno'   => 'Lain $1:',
'editundo' => 'go bek',

# Search results
'searchresults'                  => 'Ol painim',
'searchresults-title'            => 'Ol painim long "$1"',
'searchresulttext'               => 'Long moa infomesen bilong painim {{SITENAME}}, lukim [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitleinvalid'          => "Yu i painim long '''$1'''",
'viewprevnext'                   => 'Lukluk ($1 {{int:pipe-separator}} $2) ($3)',
'searchprofile-images'           => 'Media',
'searchprofile-everything'       => 'Ol',
'searchprofile-articles-tooltip' => 'Painim long $1',
'searchprofile-project-tooltip'  => 'Painim long $1',
'search-redirect'                => '(nupela rot long $1)',
'search-section'                 => '(seksen $1)',
'search-suggest'                 => 'Yu i min: $1',
'search-interwiki-default'       => '$1 ol painim:',
'search-interwiki-more'          => '(moa)',
'search-mwsuggest-enabled'       => 'halivim mi',
'search-mwsuggest-disabled'      => 'nogat halivim mi',
'searchall'                      => 'ol',
'powersearch'                    => 'Mobeta Painim',
'powersearch-legend'             => 'Mobeta Painim',
'powersearch-ns'                 => 'Painim long ol nem',
'powersearch-redir'              => 'Soim ol nupela rot',
'powersearch-field'              => 'Painim long',
'powersearch-toggleall'          => 'Ol',
'powersearch-togglenone'         => 'I nogat wanpela',

# Quickbar
'qbsettings-none' => 'I nogat wanpela',

# Preferences page
'preferences'               => 'Ol laik',
'mypreferences'             => 'Ol laik bilong mi',
'prefs-edits'               => 'Hamas senis:',
'changepassword'            => 'Senis paswot',
'prefs-skin'                => 'Skin',
'skin-preview'              => 'pes mi senisim, pastaim long raitim',
'prefs-math'                => 'Matematiks',
'datedefault'               => 'Nogat laik',
'prefs-rc'                  => 'Nupela senis',
'prefs-watchlist'           => 'Lukautbuk',
'prefs-watchlist-days-max'  => 'No moa long 7 de',
'prefs-watchlist-edits-max' => 'No moa long: 1000',
'prefs-resetpass'           => 'Senis paswot',
'saveprefs'                 => 'Holim long tingting',
'prefs-editing'             => 'Senisim',
'searchresultshead'         => 'Painim',
'recentchangesdays-max'     => 'No moa long $1 {{PLURAL:$1|de|ol de}}',
'timezoneregion-africa'     => 'Aprika',
'timezoneregion-america'    => 'Amerika',
'timezoneregion-antarctica' => 'Antatika',
'timezoneregion-asia'       => 'Esia',
'timezoneregion-atlantic'   => 'Atlantik solwara',
'timezoneregion-australia'  => 'Ostrelia',
'timezoneregion-europe'     => 'Yurop',
'timezoneregion-pacific'    => 'Pasifik solwara',
'prefs-files'               => 'Ol fail',
'prefs-custom-css'          => 'Praivet CSS',
'prefs-custom-js'           => 'Praivet JavaScript',
'youremail'                 => 'E-mel:',
'username'                  => 'Yusanem:',
'uid'                       => 'Yusa ID:',
'yourrealname'              => 'Tru nem:',
'yourlanguage'              => 'Toktok:',
'gender-male'               => 'Man',
'gender-female'             => 'Meri',
'email'                     => 'E-mel',
'prefs-info'                => 'Liklik infomesen',

# User rights
'editusergroup'           => 'Sanisim ol grup bilong yusa',
'userrights-groupsmember' => 'Memba bilong:',

# Groups
'group'      => 'Grup:',
'group-user' => 'Ol yusa',
'group-bot'  => 'Ol bot',
'group-all'  => '(ol)',

'group-user-member' => 'yusa',
'group-bot-member'  => 'bot',

'grouppage-user' => '{{ns:project}}:Ol yusa',
'grouppage-bot'  => '{{ns:project}}:Ol bot',

# Rights
'right-read'           => 'Rid ol pes',
'right-edit'           => 'Senisim ol pes',
'right-move'           => 'Surikim ol pes',
'right-movefile'       => 'Surikim ol fail',
'right-upload'         => 'Salim media fail',
'right-delete'         => 'Rausim ol pes',
'right-suppressionlog' => 'Lukim ol praivet ripot',

# User rights log
'rightslog'  => 'Ripot long ol pas bilong yusa',
'rightsnone' => 'i nogat wanpela',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'   => 'rit dispela pes',
'action-edit'   => 'senisim dispela pes',
'action-delete' => 'rausim dispela pes',

# Recent changes
'nchanges'          => '$1 {{PLURAL:$1||ol}} senis',
'recentchanges'     => 'Nupela senis',
'rcnote'            => "Ananit yu lukim '''$1 senis''' long '''$2 de''' igo pinis, na i olsem long $3.",
'rcshowhideminor'   => '$1 ol liklik senis',
'rcshowhidebots'    => '$1 ol bot',
'rcshowhideliu'     => '$1 ol yusa',
'rcshowhideanons'   => '$1 ol IP yusa',
'rcshowhidemine'    => '$1 ol senis bilong mi',
'diff'              => 'arakain',
'hist'              => 'hist',
'hide'              => 'Hait',
'show'              => 'Soim',
'minoreditletter'   => 'm',
'newpageletter'     => 'N',
'boteditletter'     => 'b',
'rc_categories'     => 'Soim ol senis insait long ol dispela grup tasol (raitim wantaim "|" namel long wanwan)',
'rc_categories_any' => 'Olgeta',

# Recent changes linked
'recentchangeslinked'         => 'Ol senis klostu',
'recentchangeslinked-feed'    => 'Ol senis klostu',
'recentchangeslinked-toolbox' => 'Ol senis klostu',
'recentchangeslinked-page'    => 'Nem bilong pes:',

# Upload
'upload'        => 'Salim media fail',
'uploadlogpage' => 'Ripot long salim',
'filesource'    => 'As:',

'license'            => 'Laisens:',
'license-header'     => 'Laisens',
'upload_source_file' => '(fail long kompyuta bilong yu)',

# Special:ListFiles
'imgfile'        => 'fail',
'listfiles'      => 'Lista bilong fail',
'listfiles_date' => 'De',
'listfiles_name' => 'Nem',
'listfiles_user' => 'Yusa',

# File description page
'file-anchor-link'    => 'Fail',
'filehist'            => 'Histori bilong fail',
'filehist-deleteall'  => 'rausim ol',
'filehist-deleteone'  => 'rausim',
'filehist-current'    => 'bilong nau',
'filehist-datetime'   => 'De/Taim',
'filehist-thumb'      => 'Liklik',
'filehist-user'       => 'Yusa',
'filehist-dimensions' => 'Ol meta',
'filehist-comment'    => 'Tingting',
'filehist-missing'    => 'Fail i no kamap',
'imagelinks'          => 'Ol fail link',
'linkstoimage'        => 'Dispela {{PLURAL:$1|pes i link|$1 ol pes i link}} long dispela fail:',
'shared-repo-from'    => 'long $1',

# File deletion
'filedelete'                  => 'Rausim $1',
'filedelete-comment'          => 'As bilong en:',
'filedelete-submit'           => 'Rausim',
'filedelete-reason-otherlist' => 'Arapela as bilong en',

# Random page
'randompage' => 'Soim wanpela pes',

# Statistics
'statistics-pages' => 'Ol pes',

'withoutinterwiki-submit' => 'Soim',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
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
'listusers'               => 'Lista long ol yusa',
'newpages'                => 'Ol nupela pes',
'newpages-username'       => 'Yusanem:',
'ancientpages'            => 'Moa olpela ol pes',
'move'                    => 'Surikim',
'movethispage'            => 'Surikim dispela pes',
'unusedcategoriestext'    => 'Ol dispela grup istap yet, tasol i no gat wanpela pes o grup i stap insait long ol.',

# Book sources
'booksources'               => 'Ol as bilong buk',
'booksources-search-legend' => 'Painim long ol buk as',
'booksources-go'            => 'Go',

# Special:Log
'specialloguserlabel'  => 'Yusa:',
'speciallogtitlelabel' => 'Taitel:',
'log'                  => 'Ol ripot',

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

# Special:ListUsers
'listusers-submit' => 'Soim',

# Special:Log/newusers
'newuserlogpage'          => 'Ripot long yusa mekim',
'newuserlog-create-entry' => 'Nupela yusa akaun',

# Special:ListGroupRights
'listgrouprights-group'   => 'Grup',
'listgrouprights-members' => '(lista long memba)',

# E-mail user
'emailuser'       => 'E-mel dispela yusa',
'emailpage'       => 'E-mel yusa',
'defemailsubject' => '{{SITENAME}} e-mel',
'noemailtitle'    => 'Nogat e-mel',
'emailfrom'       => 'Bilong:',
'emailto'         => 'Long:',
'emailmessage'    => 'Mesej:',
'emailsend'       => 'Salim',

# Watchlist
'watchlist'            => 'Lukautbuk bilong mi',
'mywatchlist'          => 'Lukautbuk bilong mi',
'watchlistfor2'        => 'Long $1 ($2)',
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
'removedwatchtext'     => 'Pes "[[:$1]]" i raus pinis long [[Special:Watchlist|lukautbuk bilong yu]].',
'watch'                => 'Lukautim',
'watchthispage'        => 'Lukautim dispela pes',
'unwatch'              => 'Pinis long lukautim',
'unwatchthispage'      => 'Pinis long lukautim',
'watchlist-details'    => '$1 pes istap long lukautbuk (dispela namba i no kaunim ol pes bilong toktok).',
'wlheader-showupdated' => "* Ol pes i senis pinis bihain long taim yu lukim ol igat nem i '''strongpela'''",
'wlshowlast'           => 'Lukim moa nupela $1 awa $2 de $3',
'watchlist-options'    => 'Ol laik bilong Lukautbuk',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Lukim...',
'unwatching' => 'Nogat lukim...',

'enotif_reset'                 => 'Makim olgeta pes olsem mi lukim pinis',
'enotif_newpagetext'           => 'Dispela i nupela pes',
'enotif_impersonal_salutation' => 'yusa long {{SITENAME}}',
'changed'                      => 'i bin senisim',
'enotif_subject'               => '{{SITENAME}} pes $PAGETITLE i bin senisim $CHANGEDORCREATED bilong $PAGEEDITOR',
'enotif_anon_editor'           => 'IP yusa $1',

# Delete
'deletepage'            => 'Rausim dispela pes',
'delete-confirm'        => 'Rausim $1',
'delete-legend'         => 'Rausim',
'deletedarticle'        => 'i rausim "[[$1]]"',
'dellogpage'            => 'Ripot long ol rausim',
'deletecomment'         => 'As bilong en:',
'deletereasonotherlist' => 'Arapela as bilong en',

# Protect
'protectlogpage'            => 'Ripot long ol haitim',
'protectedarticle'          => 'haitim "[[$1]]"',
'modifiedarticleprotection' => 'senisim haitim long "[[$1]]"',
'prot_1movedto2'            => '[[$1]] i surik i go long [[$2]] pinis',
'protectcomment'            => 'As bilong en:',
'protect-othertime'         => 'Arapela taim:',
'protect-othertime-op'      => 'arapela taim',
'protect-otherreason'       => 'Arapela/moa as bilong en',
'protect-otherreason-op'    => 'Arapela as bilong en',

# Restrictions (nouns)
'restriction-edit'   => 'Senisim',
'restriction-move'   => 'Surikim',
'restriction-create' => 'Kirapim',

# Undelete
'undeletelink'              => 'soim/restore',
'undeletecomment'           => 'As bilong en:',
'undelete-show-file-submit' => 'Yes',

# Namespace form on various pages
'invert'         => 'Tanbek sois',
'blanknamespace' => '(Nambawan)',

# Contributions
'contributions'       => 'Ol senis yusa i wokim',
'contributions-title' => 'Ol yusa senis long $1',
'mycontris'           => 'Ol senis mi wokim',
'contribsub2'         => 'Long $1 ($2)',
'uctop'               => '(antap)',
'month'               => 'Long mun (na moa nupela)',
'year'                => 'Long yia (na moa nupela)',

'sp-contributions-blocklog' => 'ripot long ol haitim',
'sp-contributions-talk'     => 'toktok',
'sp-contributions-submit'   => 'Painim',

# What links here
'whatlinkshere'            => 'Ol link ikam long hia',
'whatlinkshere-page'       => 'Pes:',
'isredirect'               => 'nupela rot',
'isimage'                  => 'link bilong piksa',
'whatlinkshere-links'      => '← ol link',
'whatlinkshere-hideredirs' => '$1 ol nupela rot',
'whatlinkshere-hidelinks'  => '$1 ol link',

# Block/unblock
'blockip'            => 'Pasim yusa (Block user)',
'ipaddress'          => 'IP adres:',
'ipboptions'         => '2 awa:2 hours,1 de:1 day,3 de:3 days,1 wik:1 week,2 wik:2 weeks,1 mun:1 month,3 mun:3 months,6 mun:6 months,1 yia:1 year,oltaim:infinite',
'ipbotheroption'     => 'narapela',
'ipblocklist'        => 'Ol haitim pinis IP adres na yusanem',
'ipblocklist-submit' => 'Painim',
'blocklink'          => 'pasim',
'unblocklink'        => 'larim',
'change-blocklink'   => 'senis pasim',
'contribslink'       => 'ol senisim',
'blocklogpage'       => 'Ripot long ol haitim',

# Move page
'move-page'        => 'Surikim $1',
'move-page-legend' => 'Surikim pes',
'movearticle'      => 'Surikim pes:',
'newtitle'         => 'Long nupela titel:',
'movepagebtn'      => 'Surikim',
'pagemovedsub'     => 'Pes i surik pinis',
'movepage-moved'   => '\'\'\'"$1" i surikim pinis long "$2"\'\'\'',
'articleexists'    => 'Wanpela pes wantaim dispela nem i stap pinis, o dispela nem i no stret.
Yu mas painim narapela nem.',
'talkexists'       => "'''Pes bilong buk i surik pinis, tasol pes bilong toktok i no inap surik, bilong wanem wanpela pes bilong toktok istap pinis wantaim dispela nam.  Yu mas pasim wantaim tupela pes bilong toktok yu yet.'''",
'movedto'          => 'i surik i go long',
'movetalk'         => 'Surikim pes bilong toktok wantaim',
'1movedto2'        => '[[$1]] i surik i go long [[$2]] pinis',
'1movedto2_redir'  => 'surikim [[$1]] long [[$2]] bilong nupela rot',
'movelogpage'      => 'Buk bilong ol surik',
'movelogpagetext'  => 'Hia yumi lukim ol pes i surik pinis.',
'movereason'       => 'As bilong en:',
'revertmove'       => 'go bek',

# Namespace 8 related
'allmessages'     => 'Ol toksave bilong sistem',
'allmessagesname' => 'Nem',

# Thumbnails
'thumbnail-more' => 'Moa bikpela',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'Pes bilong yu (Your user page)',
'tooltip-pt-preferences'         => 'Ol laik bilong yu (Your preferences)',
'tooltip-pt-mycontris'           => 'Lista long ol sanis bilong yu (List of your contributions)',
'tooltip-pt-logout'              => 'Logaut',
'tooltip-ca-talk'                => 'Toktok long dispela pes',
'tooltip-ca-addsection'          => 'Kirapim nupela seksen',
'tooltip-ca-viewsource'          => 'Dispela pes i stap haitim.
Yu inap lukluk as bilong em.',
'tooltip-ca-protect'             => 'Haitim dispela pes',
'tooltip-ca-delete'              => 'Rausim dispela pes',
'tooltip-ca-move'                => 'Surikim dispela pes (Move this page)',
'tooltip-ca-watch'               => 'Skruim dispela pes long lukautbuk bilong yu',
'tooltip-search'                 => 'Painim {{SITENAME}}',
'tooltip-search-fulltext'        => 'Painim ol pes long dispela text',
'tooltip-n-mainpage'             => 'Lukim long fran pes (Visit the main page)',
'tooltip-n-mainpage-description' => 'Lukim long fran pes (Visit the main page)',
'tooltip-n-help'                 => 'Ples long painim halivim',
'tooltip-feed-rss'               => 'RSS fid bilong dispela pes',
'tooltip-feed-atom'              => 'Atom fid bilong dispela pes',
'tooltip-t-emailuser'            => 'Salim e-mel long dispela yusa',
'tooltip-t-upload'               => 'Salim media fail',
'tooltip-t-specialpages'         => 'Lista long ol sipesol pes (List of all special pages)',
'tooltip-ca-nstab-main'          => 'Lukim stori (View the content page)',
'tooltip-ca-nstab-user'          => 'Lukim pes bilong yusa',
'tooltip-ca-nstab-media'         => 'Lukim media pes (View the media page)',
'tooltip-ca-nstab-image'         => 'Lukluk pes bilong fail',
'tooltip-ca-nstab-template'      => 'Lukim templet',
'tooltip-ca-nstab-category'      => 'Lukim grup',
'tooltip-save'                   => 'Raitim senis bilong yu (Save your changes)',

# Attribution
'siteuser' => '{{SITENAME}} yusa $1',
'others'   => 'ol narapela',

# Browsing diffs
'previousdiff' => '← Moa olpela senis',
'nextdiff'     => 'Moa nupela senis →',

# Media information
'show-big-image' => 'Bikpela piksa',

# Special:NewFiles
'showhidebots' => '($1 ol bot)',
'ilsubmit'     => 'Painim',

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

# Scary transclusion
'scarytranscludetoolong' => '[URL too longpela]',

# Trackbacks
'trackbackremove' => '([$1 Rausim])',

# action=purge
'confirm_purge_button' => 'OK',

# Multipage image navigation
'imgmultigo' => 'Go!',

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
'version-other'            => 'Narapela',
'version-poweredby-others' => 'ol narapela',

# Special:FilePath
'filepath-page'   => 'Fail:',
'filepath-submit' => 'Go',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Failnem:',
'fileduplicatesearch-submit'   => 'Painim',

# Special:SpecialPages
'specialpages'                 => 'Ol sipesol pes',
'specialpages-group-other'     => 'Ol narapela sipesol pes',
'specialpages-group-pages'     => 'Lista long ol pes',
'specialpages-group-pagetools' => 'Ol tul bilong pes',

# Special:Tags
'tags-edit' => 'senisim',

# Special:ComparePages
'compare-page1' => 'Pes 1',
'compare-page2' => 'Pes 2',

# HTML forms
'htmlform-selectorother-other' => 'Narapela',

);
