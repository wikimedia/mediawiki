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
'cancel'        => 'Sussa',
'moredotdotdot' => 'Suli...',
'mytalk'        => 'Oqalliffikka',
'navigation'    => 'Sumiissusersiuut',

# Cologne Blue skin
'qbfind' => 'Naniuk',
'qbedit' => 'Aaqqissuutiguk',

# Vector skin
'vector-view-edit' => 'Aaqqissuuguk',

'errorpagetitle'   => 'Kukkuneq',
'tagline'          => 'Matumannga {{SITENAME}}',
'help'             => 'Ikiortissamik',
'search'           => 'Ujarlerit',
'searchbutton'     => 'Ujarlerit',
'go'               => 'Ikunnarit',
'searcharticle'    => 'Tassunngarit',
'history'          => 'Oqaluttuassartaa',
'history_short'    => 'Oqaluttuassartaa',
'info_short'       => 'Paasissutissat',
'printableversion' => 'Naqikkuminartoq',
'permalink'        => 'Innersuut',
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
'portal'               => 'Allaatiginnittartup saqqai',
'privacy'              => 'Namminermut paasissutissat',

'retrievedfrom'       => 'Uannga aaneqartoq "$1"',
'youhavenewmessages'  => 'Peqarputit $1 ($2)',
'newmessageslink'     => 'oqariartuutinik nutaanik',
'newmessagesdifflink' => 'allannguutini kingullernili',
'editsection'         => 'aaqqissuuguk',
'editold'             => 'aaqqissuuguk',
'editlink'            => 'aaqqissuuguk',
'editsectionhint'     => 'Aaqqissuuguk immikkoortoq: $1',
'toc'                 => 'Imarisai',
'site-rss-feed'       => '$1 RSS Feed',
'site-atom-feed'      => '$1 Atom Feed',
'page-rss-feed'       => '"$1" RSS Feed',
'red-link-title'      => '$1 (Qupperneq suli allaffigineqanngilaq)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Qupperneq',
'nstab-user'     => 'Atuisup quppernera',
'nstab-special'  => 'Immikkut',
'nstab-image'    => 'Assiliaq',
'nstab-template' => 'Ilisserut',
'nstab-help'     => 'Ikiuutit',
'nstab-category' => 'Sumut atassuseq',

# Login and logout pages
'yourname'                   => 'Atuisutut atit',
'yourpassword'               => 'Isissutissaq:',
'yourpasswordagain'          => 'Isissutissaq allaqqiguk',
'remembermypassword'         => 'Iserfiga tullissaanut eqqaamalara',
'login'                      => 'Iserit',
'nav-login-createaccount'    => 'Konto-mik pilersitsigit imalt. iserit',
'userlogin'                  => 'Kontomik pilersitsigit / iserit',
'logout'                     => 'Anigit',
'userlogout'                 => 'Anigit',
'nologinlink'                => 'Kontomik pilersitsigit',
'gotaccountlink'             => 'Iserit',
'acct_creation_throttle_hit' => 'Konto-mik pilersitsereersimagavit pilersitseqqissinnaanngilatit, IP-adressit malillugu.
Taamaattumik maannakkorpiaq kontomik pilersitsisinnaanngilatit.',

# Password reset dialog
'oldpassword' => 'Isissutissatoqaq:',
'newpassword' => 'Isissutissaq nutaaq:',
'retypenew'   => 'Isissutissaq nutaaq allaqqiuk',

# Edit pages
'summary'      => 'Qanoq issusersiuineq:',
'watchthis'    => 'Allaaserisaq ersersimatiguk',
'savearticle'  => 'Toqqoruk',
'preview'      => 'Isikkua',
'showpreview'  => 'Isikkua takuuk',
'showdiff'     => 'Allannguutit',
'accmailtitle' => 'Password-i nassiunneqarsimavoq.',
'accmailtext'  => 'Password-i "$1" $2-mut nassiunneqarsimavoq.',
'newarticle'   => '(Nuutaq)',
'previewnote'  => 'Eqqaamallugu isikkua takutinneqaannarpoq, toqqorneqanngilaq suli!',

# History pages
'histfirst' => 'Pisoqaaneq',
'histlast'  => 'Nutaaneq',

# Diffs
'editundo' => 'peeruk',

# Search results
'searchresults'             => 'Ujaasinermi inernerit',
'searchresults-title'       => 'Uuma ujarnera "$1"',
'search-result-size'        => '$1 ({{PLURAL:$2|oqaaseq|$2 oqaatsit}})',
'search-redirect'           => '(nuunneq $1)',
'search-mwsuggest-enabled'  => 'siunnersuuserlugu',
'search-mwsuggest-disabled' => 'siunnersuusernagu',
'powersearch'               => 'Ujarlerit',

# Preferences page
'mypreferences' => 'Inissiffissat',

# Groups
'group-sysop' => 'Administratorit',

# Recent changes
'recentchanges'   => 'Allannguutit kingulliit',
'hist'            => 'hist',
'minoreditletter' => 'm',
'newpageletter'   => 'N',
'boteditletter'   => 'b',

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
'randompage' => 'Nalaatsorluni atuagassianut ilanngutassiaq',

# Statistics
'statistics' => 'Kisitsisinngorlugit paasissutissat',

# Miscellaneous special pages
'newpages' => 'Quppernerit nutaat',
'move'     => 'Nuuguk',

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
'actioncomplete' => 'Naammassineqareersimavoq',

# Protect
'prot_1movedto2' => '[[$1]]-i nuunneqarsimavoq [[$2]]-mut',

# Namespace form on various pages
'namespace'      => 'Quppernerup ilusia:',
'blanknamespace' => '(Pingaarneq)',

# Contributions
'contributions' => 'Atuisup tapii',
'mycontris'     => 'Tapikka',
'uctop'         => '(kingulleq)',

'sp-contributions-talk'     => 'Oqallinneq',
'sp-contributions-username' => 'IP adresse imalt. atuisoq:',

# What links here
'whatlinkshere' => 'Suna maangamut innersuussisoq',

# Block/unblock
'blocklink'    => 'aporfeqarneq',
'contribslink' => 'tapikkat',

# Move page
'movearticle'     => 'Qupperneq nuuguk',
'move-watch'      => 'Qupperneq ersersimatiguk',
'movepagebtn'     => 'Qupperneq nuuguk',
'pagemovedsub'    => 'Nuunnera iluatsippoq',
'movepage-moved'  => '<big>Qupperneq \'\'\'"$1" uunga nuuppoq "$2"\'\'\'</big>',
'1movedto2'       => '[[$1]]-i nuunneqarsimavoq [[$2]]-mut',
'1movedto2_redir' => '[[$1]] nuunneqarsimavoq [[$2]]-mut adresse-ia aqqutigalugu allanngortillugu',

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

# Special:SpecialPages
'specialpages' => 'Quppernerit immikkut ittut',

# Add categories per AJAX
'ajax-confirm-save' => 'Toqqoruk',

);
