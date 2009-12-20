<?php
/** Kalaallisut (Kalaallisut)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Aputtu
 * @author Kaare
 * @author Piivaat
 * @author לערי ריינהארט
 */

$fallback = 'da';

$namespaceNames = array(
	NS_SPECIAL          => 'Immikkut',
	NS_TALK             => 'Oqallinneq',
	NS_USER             => 'Atuisoq',
	NS_USER_TALK        => 'Atuisup oqalliffia',
	NS_PROJECT_TALK     => '$1ip oqalliffia',
	NS_FILE             => 'Fiileq',
	NS_FILE_TALK        => 'Fiilip oqalliffia',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Mediawikip oqalliffia',
	NS_TEMPLATE         => 'Ilisserut',
	NS_TEMPLATE_TALK    => 'Ilisserummi oqallinneq',
	NS_HELP             => 'Ikiuutit',
	NS_HELP_TALK        => 'Ikiuutini oqallinneq',
	NS_CATEGORY         => 'Sumut atassuseq',
	NS_CATEGORY_TALK    => 'Sumut atassusermi oqallinneq',
);

$namespaceAliases = array(
	'Speciel' => NS_SPECIAL,
	'Diskussion' => NS_TALK,
	'Bruger' => NS_USER,
	'Brugerdiskussion' => NS_USER_TALK,
	'$1-diskussion' => NS_PROJECT_TALK,
	'Fil' => NS_FILE,
	'Fildiskussion' => NS_FILE_TALK,
	'Billede' => NS_FILE,
	'Billeddiskussion' => NS_FILE_TALK,
	'MediaWiki' => NS_MEDIAWIKI,
	'MediaWiki-diskussion' => NS_MEDIAWIKI_TALK,
	'Skabelon' => NS_TEMPLATE,
	'Skabelondiskussion' => NS_TEMPLATE_TALK,
	'Hjælp' => NS_HELP,
	'Hjælp-diskussion' => NS_HELP_TALK,
	'Kategori' => NS_CATEGORY,
	'Kategoridiskussion' => NS_CATEGORY_TALK
);

$messages = array(
# Dates
'sunday'        => 'Sapaat',
'monday'        => 'Ataasinngorneq',
'tuesday'       => 'Marlunngorneq',
'wednesday'     => 'Pingasunngorneq',
'thursday'      => 'Sisamanngorneq',
'friday'        => 'Tallimanngorneq',
'saturday'      => 'Arfininngorneq',
'sun'           => 'Sap',
'mon'           => 'Ata',
'tue'           => 'Mar',
'wed'           => 'Pin',
'thu'           => 'Sis',
'fri'           => 'Tal',
'sat'           => 'Arf',
'january'       => 'Jannuaari',
'february'      => 'Februaari',
'march'         => 'Martsi',
'april'         => 'Apriili',
'may_long'      => 'Maaji',
'june'          => 'Juuni',
'july'          => 'Juuli',
'august'        => 'Aggusti',
'september'     => 'Septemberi',
'october'       => 'Oktoberi',
'november'      => 'Novemberi',
'december'      => 'Decemberi',
'january-gen'   => 'Januaari',
'february-gen'  => 'Februaari',
'march-gen'     => 'Marsi',
'april-gen'     => 'Apriili',
'may-gen'       => 'Maaji',
'june-gen'      => 'Juuni',
'july-gen'      => 'Juuli',
'august-gen'    => 'Aggusti',
'september-gen' => 'Septembari',
'october-gen'   => 'Oktobari',
'november-gen'  => 'Novembari',
'december-gen'  => 'Decembari',
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Mar',
'apr'           => 'Apr',
'may'           => 'Maa',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Aug',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nov',
'dec'           => 'Dec',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Sumut atassuseq|Sunut atasut}}',

'about'         => 'Pillugu',
'newwindow'     => '(nutaamut ammassaaq)',
'cancel'        => 'Unitsiguk',
'moredotdotdot' => 'Suli...',
'mytalk'        => 'Oqalliffikka',
'navigation'    => 'Sumiissusersiuut',

# Cologne Blue skin
'qbfind' => 'Naniuk',
'qbedit' => 'Aaqqissuutiguk',

# Vector skin
'vector-view-edit' => 'Aaqqissuuguk',

'errorpagetitle'   => 'Kukkuneq',
'returnto'         => '$1 -mut uterit',
'tagline'          => 'Matumannga {{SITENAME}}',
'help'             => 'Ikiuutit',
'search'           => 'Ujarlerit',
'searchbutton'     => 'Ujarlerit',
'go'               => 'Ikunnarit',
'searcharticle'    => 'Tassunngarit',
'history'          => 'Oqaluttuassartaa',
'history_short'    => 'Oqaluttuassartaa',
'info_short'       => 'Paasissutissat',
'printableversion' => 'Naqikkuminartoq',
'permalink'        => 'Ataavartumik innersuut',
'edit'             => 'Aaqqissuuguk',
'create'           => 'Pilersiguk',
'editthispage'     => 'Qupperneq aaqqissuuguk',
'delete'           => 'Peeruk',
'deletethispage'   => 'Qupperneq piiaruk',
'protect'          => 'Illersoruk',
'protect_change'   => 'allannguutit',
'unprotect'        => 'Illersorunnaaruk',
'newpage'          => 'Qupperneq nutaaq',
'talkpagelinktext' => 'Oqallinneq',
'personaltools'    => 'Namminermut sannatit',
'talk'             => 'Oqallinneq',
'views'            => 'Takutitat',
'toolbox'          => 'Atortut',
'otherlanguages'   => 'Oqaatsit allat',
'redirectedfrom'   => '($1-mit nuunneq)',
'jumpto'           => 'Uunngarit:',
'jumptonavigation' => 'navigationi',
'jumptosearch'     => 'ujarlerit',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} pillugu',
'aboutpage'            => 'Project:Pillugu',
'currentevents'        => 'Maannakkut pisut',
'disclaimers'          => 'Aalajangersagaq',
'edithelp'             => 'Ikiuutit',
'edithelppage'         => 'Help:Aaqqissuussineq',
'helppage'             => 'Help:Ikiuutit',
'mainpage'             => 'Saqqaa',
'mainpage-description' => 'Saqqaa',
'portal'               => 'Allattartup saqqaa',
'privacy'              => 'Namminermut paasissutissat',

'retrievedfrom'       => 'Uannga aaneqartoq "$1"',
'youhavenewmessages'  => '<!-- This sentence shall be empty because of kl grammar. --> $1 ($2)',
'newmessageslink'     => 'Allagarsivutit',
'newmessagesdifflink' => 'allannguutini kingullerniit',
'editsection'         => 'aaqqissuuguk',
'editold'             => 'aaqqissuuguk',
'viewsourceold'       => 'toqqavia takuuk',
'editlink'            => 'aaqqissuuguk',
'editsectionhint'     => 'Aaqqissuuguk immikkoortoq: $1',
'toc'                 => 'Imarisai',
'showtoc'             => 'Ersiguk',
'site-rss-feed'       => '$1 RSS Feed',
'site-atom-feed'      => '$1 Atom Feed',
'page-rss-feed'       => '"$1" RSS Feed',
'red-link-title'      => '$1 (Qupperneq suli allaffigineqanngilaq)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Allaaserisaq',
'nstab-user'     => 'Atuisup quppernera',
'nstab-special'  => 'Immikkut',
'nstab-project'  => 'Pillugu',
'nstab-image'    => 'Assiliaq',
'nstab-template' => 'Ilisserut',
'nstab-help'     => 'Ikiuutit',
'nstab-category' => 'Sumut atassuseq',

# General errors
'viewsource' => 'Toqqavia takuuk',

# Login and logout pages
'logouttext'                 => "'''Maanna anivutit.'''

{{SITENAME}} atorlugu ingerlaqqissinnaavutit kinaanerit isertorlugu, iseqqissinnaavutilluunniit taamatut isissuteqarlutit imalt. allat iserfigisinnaanngorpaat.",
'yourname'                   => 'Atuisutut atit',
'yourpassword'               => 'Isissutissaq:',
'yourpasswordagain'          => 'Isissutissaq allaqqiguk',
'remembermypassword'         => 'Iserfiga tullissaanut eqqaamalara',
'login'                      => 'Iserit',
'nav-login-createaccount'    => 'Konto-mik pilersitsigit imalt. iserit',
'loginprompt'                => 'Pisariaqassaaq cookies-itit atussallugit {{SITENAME}} -mut isissaguit.',
'userlogin'                  => 'Kontomik pilersitsigit / iserit',
'logout'                     => 'Anigit',
'userlogout'                 => 'Anigit',
'nologin'                    => "Kontomik peqanngilatit? '''$1'''.",
'nologinlink'                => 'Kontomik pilersitsigit',
'createaccount'              => 'Kontomik nutaamik pilersitsigit',
'gotaccount'                 => "Kontomik peqareerpit? '''$1'''.",
'gotaccountlink'             => 'Iserit',
'createaccountmail'          => 'e-mail-ikkut',
'badretype'                  => 'Isissutissat allanneqartut assigiinngillat.',
'userexists'                 => 'Atuisup atia atorneqareerpoq. Allamik qinersigit.',
'loginsuccesstitle'          => 'Maanna isersimalerputit',
'loginsuccess'               => 'Maanna {{SITENAME}} -mut isersimalerputit "$1" -itut taaguuserlutit.',
'acct_creation_throttle_hit' => 'Konto-mik pilersitsereersimagavit pilersitseqqissinnaanngilatit, IP-adressit malillugu.
Taamaattumik maannakkorpiaq kontomik pilersitsisinnaanngilatit.',

# Password reset dialog
'oldpassword' => 'Isissutissatoqaq:',
'newpassword' => 'Isissutissaq nutaaq:',
'retypenew'   => 'Isissutissaq nutaaq allaqqiuk',

# Edit pages
'summary'       => 'Allaaserinera:',
'minoredit'     => 'Annikitsumik allannguutaavoq',
'watchthis'     => 'Allaaserisaq ersersimatiguk',
'savearticle'   => 'Toqqoruk',
'preview'       => 'Isikkua',
'showpreview'   => 'Isikkua takuuk',
'showdiff'      => 'Allannguutit',
'accmailtitle'  => 'Password-i nassiunneqarsimavoq.',
'accmailtext'   => 'Password-i "$1" $2-mut nassiunneqarsimavoq.',
'newarticle'    => '(Nuutaq)',
'noarticletext' => 'Maannamut una qupperneq allaffigineqanngilaq.
Taamatut oqaasilimmik quppernerni allani [[Special:Search/{{PAGENAME}}|ujaasisinnaavutit]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} log-ini ujaasisinnavutillu] imaluunniit [{{fullurl:{{FULLPAGENAME}}|action=edit}} qupperneq pilersissinnaavat]</span>.',
'previewnote'   => 'Eqqaamallugu isikkua takutinneqaannarpoq, toqqorneqanngilaq suli!',
'editing'       => 'Aaqqissorpaa $1',

# History pages
'histfirst' => 'Pisoqaaneq',
'histlast'  => 'Nutaaneq',

# Diffs
'editundo' => 'peeruk',

# Search results
'searchresults'             => 'Ujaasinermi inernerit',
'searchresults-title'       => 'Uuma ujarnera "$1"',
'searchmenu-exists'         => "'''Qupperneqarpoq \"[[:\$1]]\" -mik atilimmik maani wikimi'''",
'searchmenu-new'            => "'''Qupperneq [[:$1]] pilersiguk maani wikimi'''",
'searchhelp-url'            => 'Help:Ikiuutit',
'search-result-size'        => '$1 ({{PLURAL:$2|oqaaseq|$2 oqaatsit}})',
'search-redirect'           => '(nuunneq $1)',
'search-suggest'            => 'Una piviuk: $1',
'search-mwsuggest-enabled'  => 'siunnersuuserlugu',
'search-mwsuggest-disabled' => 'siunnersuusernagu',
'search-nonefound'          => 'Ujaasineq inerneqanngilaq',
'powersearch'               => 'Ujarlerit',

# Preferences page
'mypreferences' => 'Inissiffissat',
'prefs-rc'      => 'Allannguutit kingulliit',
'yourlanguage'  => 'Oqaatsit:',

# Groups
'group-sysop' => 'Administratorit',

# Recent changes
'recentchanges'                => 'Allannguutit kingulliit',
'recentchanges-legend'         => 'Inissisimaffiit allannguutini kingullerni',
'recentchangestext'            => "Uani quppernermi '''{{SITENAME}}'''-mi allannguutit kingulliit malinnaavigisinnaavatit.",
'recentchanges-label-legend'   => 'Nassuiaatit: $1.',
'recentchanges-legend-newpage' => '$1 - qupperneq nutaaq',
'recentchanges-legend-minor'   => '$1 - allannguut annikitsoq',
'recentchanges-legend-bot'     => '$1 - bot-ip allannguutaa',
'rclistfrom'                   => 'Allannguutit kingulliit takukkit $1 -nngaanniit',
'rcshowhideminor'              => '$1 allannguutit annikitsut',
'rcshowhidebots'               => '$1 robottit',
'rcshowhideliu'                => '$1 atuisut nalunaarsimasut',
'rcshowhideanons'              => '$1 atuisut anonymejusut',
'rcshowhidepatr'               => '$1 allannguutit misissorneqarsimasut',
'rcshowhidemine'               => '$1 nammineq tapit',
'rclinks'                      => 'Takutikkit $1 -it allannguutit kingulliit ulluni kingullerni $2 -ni<br />$3',
'diff'                         => 'assigiinng',
'hist'                         => 'oqalutt',
'hide'                         => 'Assequt',
'show'                         => 'Saqqummiuk',
'minoreditletter'              => 'm',
'newpageletter'                => 'N',
'boteditletter'                => 'b',

# Recent changes linked
'recentchangeslinked'         => 'Allannguutit naleqqiussat',
'recentchangeslinked-feed'    => 'Allannguutit naleqqiussat',
'recentchangeslinked-toolbox' => 'Allannguutit naleqqiussat',

# Upload
'upload' => 'Fiilimik ilisigit',

# File description page
'file-anchor-link'  => 'Assiliaq',
'filehist-datetime' => 'Ulloq/Piffissaq',
'filehist-user'     => 'Pineqartoq',
'imagelinks'        => 'Innersuutit',

# MIME search
'mimesearch' => 'MIME ujaarlerit',

# Random page
'randompage' => 'Nalaatsornermi qupperneq',

# Statistics
'statistics' => 'Kisitsisinngorlugit paasissutissat',

# Miscellaneous special pages
'newpages' => 'Quppernerit nutaat',
'move'     => 'Nuuguk',

# Book sources
'booksources-go' => 'Ujaruk',

# Special:Log
'specialloguserlabel' => 'Pineqartoq:',

# Special:AllPages
'allarticles'    => 'Quppernerit tamarmik',
'allpagessubmit' => 'Tassunngarit',

# Watchlist
'watchlist'      => 'Ersersimasut',
'mywatchlist'    => 'Nuisatiffikka',
'addedwatch'     => 'Nakkutilliinermi allattorsimaffimmut ilanngunneqarsimavoq',
'addedwatchtext' => "Qupperneq \"[[:\$1]]\" ilanngunneqarsimavoq [[Special:Watchlist|nakkutilliinermut allattorsimaffimmut]] ilanngunneqarsimavoq. Matumani quppernermi siunissami allannguutit, aammalu oqallinnermi qupperneq, maani saqqummersinneqassapput, quppernerlu '''erseqqissagaasoq''' inisseqqassalluni [[Special:RecentChanges|allattorsimaffik kingullermi allannguutinik imalik]] ajornannginnerussammat nassaariniarnissaanut.

Qupperneq nakkutilliinermi allattorsimaffik kingusinnerusukkut piissagukku, taava quppernerup sinaatungaani \"Nakkutilliinermi allattorsimaffik peeruk\" tooruk.",
'watch'          => 'Ersilli',
'watchthispage'  => 'Qupperneq ersersimatiguk',
'unwatch'        => 'Ersitsinnagu',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Saqqumitiguk',
'unwatching' => 'Saqquminera peeruk',

# Delete
'delete-confirm' => 'Peeruk "$1"',
'actioncomplete' => 'Naammassineqareersimavoq',
'deletedarticle' => 'peerpaa "[[$1]]"',

# Protect
'prot_1movedto2' => '[[$1]]-i nuunneqarsimavoq [[$2]]-mut',

# Namespace form on various pages
'namespace'      => 'Quppernerup ilusia:',
'invert'         => 'Quppernerup ilusia qinernagu',
'blanknamespace' => '(Pingaarneq)',

# Contributions
'contributions' => 'Atuisup tapii',
'mycontris'     => 'Tapikka',
'uctop'         => '(kingulleq)',

'sp-contributions-talk'     => 'oqallinneq',
'sp-contributions-username' => 'IP adresse imalt. atuisoq:',

# What links here
'whatlinkshere' => 'Suna maangamut innersuussisoq',

# Block/unblock
'blockip'        => 'Atuisoq asseruk',
'blockip-legend' => 'Atuisoq asseruk',
'blocklistline'  => '$1, $2 asserpaa $3 ($4)',
'blocklink'      => 'assersoruk',
'contribslink'   => 'tapikkat',

# Move page
'movearticle'     => 'Qupperneq nuuguk',
'move-watch'      => 'Qupperneq ersersimatiguk',
'movepagebtn'     => 'Qupperneq nuuguk',
'pagemovedsub'    => 'Nuunnera iluatsippoq',
'movepage-moved'  => '<big>Qupperneq \'\'\'"$1" uunga nuuppoq "$2"\'\'\'</big>',
'1movedto2'       => '[[$1]] nuuppaa [[$2]]-mut',
'1movedto2_redir' => '[[$1]] nuunneqarsimavoq [[$2]]-mut adresse-ia aqqutigalugu allanngortillugu',

# Namespace 8 related
'allmessages-language'      => 'Oqaatsit:',
'allmessages-filter-submit' => 'Takuuk',

# Thumbnails
'thumbnail-more' => 'Allisiguk',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'Atuisutut quppernerit',
'tooltip-pt-mytalk'              => 'Oqalliffiit',
'tooltip-pt-preferences'         => 'Inissiinikka',
'tooltip-pt-mycontris'           => 'Tapikkatit',
'tooltip-pt-login'               => 'Iseqquneqaraluarputit, pitsaanerussagaluarpoq',
'tooltip-pt-logout'              => 'Aniffik',
'tooltip-ca-talk'                => 'Quppernerup imaanik oqallinneq',
'tooltip-ca-edit'                => 'Allanngortiterisinnaavutit. Isikkua takulaariuk',
'tooltip-ca-history'             => 'Quppernerup siulii',
'tooltip-ca-move'                => 'Qupperneq nuuguk',
'tooltip-ca-watch'               => 'Saqqumitiguk',
'tooltip-search'                 => 'Ujaarlerit {{SITENAME}}',
'tooltip-search-go'              => 'Tassunngarit nassaassappat',
'tooltip-search-fulltext'        => 'Taanna ujaruk',
'tooltip-n-mainpage'             => 'Saqqaa iseruk',
'tooltip-n-mainpage-description' => 'Saqqaa iseruk',
'tooltip-n-portal'               => 'Suliaq, ilitsersuut, nassaassaasinnaasullu',
'tooltip-n-recentchanges'        => 'Wikimi allannguutit kingulliit',
'tooltip-n-randompage'           => 'Allaaserisamukarit',
'tooltip-n-help'                 => 'Qanoq iliussaanga ...',
'tooltip-t-whatlinkshere'        => 'Innersuussami saqqummiussat',
'tooltip-t-specialpages'         => 'Quppernerit immikkut ittut nassaassaasinnaasut',
'tooltip-ca-nstab-main'          => 'Imarisaa takuuk',
'tooltip-save'                   => 'Allannguutitit toqqukkit',
'tooltip-preview'                => 'Isikkua takuuk, toqqortinnaguk atortaruk!',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tamarmik',
'namespacesall'    => 'tamarmik',

# Special:SpecialPages
'specialpages' => 'Quppernerit immikkut ittut',

# Add categories per AJAX
'ajax-confirm-save' => 'Toqqoruk',

);
