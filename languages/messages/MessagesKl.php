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
 * @author Qaqqalik
 * @author לערי ריינהארט
 */

$fallback = 'da';

$namespaceNames = array(
	NS_SPECIAL          => 'Immikkut',
	NS_TALK             => 'Oqallinneq',
	NS_USER             => 'Atuisoq',
	NS_USER_TALK        => 'Atuisup oqalliffia',
	NS_PROJECT_TALK     => '$1-p oqalliffia',
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
	'$1ip oqalliffia' => NS_PROJECT_TALK,
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
'may'           => 'Maaji',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Aug',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nov',
'dec'           => 'Dec',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Sumut atassuseq|Sunut atassusit}}',
'category_header'        => 'Quppernerit sumut atassusermi "$1"-miittut',
'subcategories'          => 'Sunut atassuserni ataaniittut',
'category-subcat-count'  => '{{PLURAL:$2|Una sumut atassuseq ataatsimik ataani ilaqarpoq.|Una sumut atassuseq imarivai {{PLURAL:$1|sumut atassuseq ataaniittoq|$1 sunut atassusit ataaniittut}}, $2-suni.}}',
'category-article-count' => 'Una sumut atassuseq imarivaa {{PLURAL:$2|qupperneq ataaseq ataaniittoq|{{PLURAL:$1|qupperneq ataaseq ataaniittoq|quppernerit ataaniittut $1-it}} $2-suni.}}',

'about'         => 'Pillugu',
'newwindow'     => '(nutaamut ammassaaq)',
'cancel'        => 'Unitsiguk',
'moredotdotdot' => 'Suli...',
'mytalk'        => 'Oqalliffikka',
'navigation'    => 'Sumiissusersiuut',

# Cologne Blue skin
'qbfind' => 'Naniuk',
'qbedit' => 'Aaqqissoruk',

# Vector skin
'vector-view-edit' => 'Aaqqissoruk',

'errorpagetitle'   => 'Kukkuneq',
'returnto'         => '$1 -mut uterit',
'tagline'          => '{{SITENAME}}-meersoq',
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
'edit'             => 'Aaqqissoruk',
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
'lastmodifiedat'   => 'Una qupperneq kingullermik allanngortinneqarsimavoq $1 $2',
'jumpto'           => 'Uunngarit:',
'jumptonavigation' => 'sumiissusersiuut',
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
'editsection'         => 'aaqqissoruk',
'editold'             => 'aaqqissoruk',
'viewsourceold'       => 'toqqavia takuuk',
'editlink'            => 'aaqqissoruk',
'editsectionhint'     => 'Aaqqissuuguk immikkoortoq: $1',
'toc'                 => 'Imarisai',
'showtoc'             => 'saqqummeruk',
'hidetoc'             => 'toqqoruk',
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
'viewsource'        => 'Toqqavia takuuk',
'protectedpagetext' => 'Una qupperneq allaffigineqarnissamut illersugaavoq.',
'viewsourcetext'    => 'Qupperneq takusinnaavat aamma sanarfia kopeersinnaavat:',

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
'loginerror'                 => 'Iserniarnerlunneq',
'loginsuccesstitle'          => 'Maanna isersimalerputit',
'loginsuccess'               => 'Maanna {{SITENAME}} -mut isersimalerputit "$1" -itut taaguuserlutit.',
'wrongpassword'              => 'Isissutissaq kukkusumik allanneqarsimavoq. Misileqqiuk.',
'mailmypassword'             => 'E-mail-ikkut isissutissaq nutaaq nassiuguk',
'acct_creation_throttle_hit' => 'Konto-mik pilersitsereersimagavit pilersitseqqissinnaanngilatit, IP-adressit malillugu.
Taamaattumik maannakkorpiaq kontomik pilersitsisinnaanngilatit.',

# Password reset dialog
'oldpassword' => 'Isissutissatoqaq:',
'newpassword' => 'Isissutissaq nutaaq:',
'retypenew'   => 'Isissutissaq nutaaq allaqqiuk',

# Edit pages
'summary'                          => 'Allaaserinera:',
'subject'                          => 'Pineqartoq/qulequtaq:',
'minoredit'                        => 'Annikitsumik allannguutaavoq',
'watchthis'                        => 'Allaaserisaq ersersimatiguk',
'savearticle'                      => 'Toqqoruk',
'preview'                          => 'Isikkua',
'showpreview'                      => 'Isikkua takuuk',
'showdiff'                         => 'Allannguutit',
'anoneditwarning'                  => "'''Mianersoqqussut:''' Isersimanak sulilerputit.
IP adressit nuisassaaq massuma quppernerup oqaluttuassartaani.",
'accmailtitle'                     => 'Password-i nassiunneqarsimavoq.',
'accmailtext'                      => 'Password-i "$1" $2-mut nassiunneqarsimavoq.',
'newarticle'                       => '(Nuutaq)',
'newarticletext'                   => "Maanga innersuunneqarsimavutit quppernermut suli pilersinneqarsimanngitsumut.
Qupperneq pilersissagukku, boks-ip iluani allagit (takuuk [[{{MediaWiki:Helppage}}|ikiuutit]] paasissutissaanerusut).
Maanngarsimaguit kukkusumik, toortaat '''utimut''' tooruk.",
'anontalkpagetext'                 => "---- ''Manna tassaavoq oqalliffik atuisumit anonym-iusumeersumit, konto-mik pilersitsisimanngitsumik imalt. atorneq ajugaanik.
Taamaattumik IP-adressia kinaanerattut atortariaqassavarput.
IP-adressi pigineqarsinnaavoq atuisunit arlalinnit.
Atuisuuguit anonym-iusoq, isumaqarlutillu soqutiginngisannik oqaaseqarfigineqarlutit, qinnuigivatsigit [[Special:UserLogin/signup|atuisutut pilersitsissallutit]] aamma [[Special:UserLogin|iserlutit]], taava siunissami paarlattoornernik atuisuni arlalinni pinaveersaartoqarniassammat.''",
'noarticletext'                    => 'Maannamut una qupperneq allaffigineqanngilaq.
Taamatut oqaasilimmik quppernerni allani [[Special:Search/{{PAGENAME}}|ujaasisinnaavutit]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} log-ini ujaasisinnavutillu] imaluunniit [{{fullurl:{{FULLPAGENAME}}|action=edit}} qupperneq pilersissinnaavat]</span>.',
'previewnote'                      => 'Eqqaamallugu isikkua takutinneqaannarpoq, toqqorneqanngilaq suli!',
'editing'                          => 'Aaqqissorpaa $1',
'editingsection'                   => 'Aaqqissorpaa $1 (immikkoortoq)',
'editingcomment'                   => 'Aaqqissorpaa $1 (immikkoortoq nutaaq)',
'yourtext'                         => 'Allatat',
'editingold'                       => "'''Mianersoqqussut: Qupperneq pisoqaanerusoq aaqqissuutilerpat.'''
Toqqorukku quppernerup taamaannera taarserneqassaaq.",
'protectedpagewarning'             => "'''Mianersoqqussut: Una qupperneq illersugaavoq, administratorit kisimik aaqqissorsinnaavaat.'''",
'semiprotectedpagewarning'         => "'''Malugiuk:''' Qupperneq parnaaqqavoq, atuisutut nalunaarsimasut kisimik allanngortitersinnaavaat.",
'templatesused'                    => '{{PLURAL:$1|Ilisserut|Ilisserutit}} quppernermi atorneqartoq/tut:',
'permissionserrorstext-withaction' => 'Pisinnaatitaaffeqanngilatit $2 atussallugu, {{PLURAL:$1|peqqutigalugu|peqqutigalugit}}:',
'moveddeleted-notice'              => 'Una qupperneq peerneqarsimavoq.
Peersinermut nuutsinermullu nalunaarsuutit ataani takuneqarsinnaapput.',

# History pages
'currentrev'             => 'Maanna taamaannera',
'currentrev-asof'        => 'Maanna taamaannera $1-meersoq',
'revisionasof'           => 'Taamaannera $1-meersoq',
'previousrevision'       => '← Pisoqaaneq',
'nextrevision'           => 'Nutaaneq →',
'currentrevisionlink'    => 'Massakkuunera takuuk',
'cur'                    => 'maanna',
'last'                   => 'siulia',
'page_first'             => 'siulliit',
'page_last'              => 'kingulliit',
'histlegend'             => 'Nassuiaat: (maanna) = assigiinngissut maanna inneranut, (siulia) = assigiinngissut siulianut, M = annikitsumik allannguut',
'history-fieldset-title' => 'Oqaluttuassartaani qupperaagit',
'histfirst'              => 'Pisoqaaneq',
'histlast'               => 'Nutaaneq',

# Diffs
'history-title'           => '"$1"-p oqaluttuassartaa',
'compareselectedversions' => 'Qinikkat nalilersukkit',
'editundo'                => 'peeruk',

# Search results
'searchresults'             => 'Ujaasinermi inernerit',
'searchresults-title'       => 'Uuma ujarnera "$1"',
'prevn'                     => 'siulii {{PLURAL:$1|$1}}',
'nextn'                     => 'tullii {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Takuuk ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-exists'         => "'''Qupperneqarpoq \"[[:\$1]]\" -mik atilimmik maani wikimi'''",
'searchmenu-new'            => "'''Qupperneq [[:$1]] pilersiguk maani wikimi'''",
'searchhelp-url'            => 'Help:Ikiuutit',
'searchprofile-articles'    => 'Imarisai',
'searchprofile-project'     => 'Ikiuutit suliniutillu imaat',
'searchprofile-everything'  => 'Tamarmik',
'searchprofile-advanced'    => 'Ujaasiffik anneq',
'search-result-size'        => '$1 ({{PLURAL:$2|oqaaseq|$2 oqaatsit}})',
'search-redirect'           => '(nuunneq $1)',
'search-suggest'            => 'Una piviuk: $1',
'search-mwsuggest-enabled'  => 'siunnersuuserlugu',
'search-mwsuggest-disabled' => 'siunnersuusernagu',
'showingresultsheader'      => "{{PLURAL:$5|Inernera '''$1''' '''$3'''|Inerneri '''$1 - $2''' '''$3'''}}-suni '''$4'''-mut",
'search-nonefound'          => 'Ujaasineq inerneqanngilaq',
'powersearch'               => 'Ujarlerit',

# Preferences page
'mypreferences' => 'Inissiffissat',
'prefs-rc'      => 'Allannguutit kingulliit',
'saveprefs'     => 'Toqqukkit',
'yourlanguage'  => 'Oqaatsit:',

# Groups
'group-sysop' => 'Administratorit',

# Recent changes
'recentchanges'                => 'Allannguutit kingulliit',
'recentchanges-legend'         => 'Inissisimaffiit allannguutini kingullerni',
'recentchangestext'            => "Uani quppernermi '''{{SITENAME}}'''-mi allannguutit kingulliit malinnaavigisinnaavatit.",
'recentchanges-label-legend'   => 'Nassuiaatit: $1.',
'recentchanges-legend-newpage' => '$1 - qupperneq nutaaq',
'recentchanges-label-newpage'  => 'Tassaavoq qupperneq nutaaq',
'recentchanges-legend-minor'   => '$1 - allannguut annikitsoq',
'recentchanges-label-minor'    => 'Tassaavoq allannguut annikitsoq',
'recentchanges-legend-bot'     => '$1 - bot-ip allannguutaa',
'recentchanges-label-bot'      => 'Bot-ip allannguutaa',
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
'upload'        => 'Fiilimik ilisigit',
'uploadedimage' => 'ilivaa "[[$1]]"',

# Special:ListFiles
'listfiles_user' => 'Atuisoq',

# File description page
'file-anchor-link'  => 'Assiliaq',
'filehist-datetime' => 'Ulloq/Piffissaq',
'filehist-user'     => 'Atuisoq',
'imagelinks'        => 'Innersuutit',

# MIME search
'mimesearch' => 'MIME ujaarlerit',

# Random page
'randompage' => 'Nalaatsornermi qupperneq',

# Statistics
'statistics' => 'Kisitsisinngorlugit paasissutissat',

# Miscellaneous special pages
'newpages'      => 'Quppernerit nutaat',
'move'          => 'Nuuguk',
'pager-newer-n' => '{{PLURAL:$1|nutaaneq 1|nutaanerit $1}}',
'pager-older-n' => '{{PLURAL:$1|pisoqaaneq 1|pisoqaanerit $1}}',

# Book sources
'booksources-go' => 'Ujaruk',

# Special:Log
'specialloguserlabel' => 'Atuisoq:',

# Special:AllPages
'allarticles'    => 'Quppernerit tamarmik',
'allpagesprev'   => 'Siulii',
'allpagesnext'   => 'Tullii',
'allpagessubmit' => 'Tassunngarit',

# Special:Categories
'categories'         => 'Sunut atassusit',
'categoriespagetext' => 'Uku {{PLURAL:$1|sumut atassuseq|sunut atassusit}} imarivai quppernerit media-lluunniit.
[[Special:UnusedCategories|Sunut atassusit]] atorneqanngitsut maani ilaanngillat.
Aamma takuuk [[Special:WantedCategories|sunut atassusinut kissaatigineqartut]].',
'categoriesfrom'     => 'Takuuk qanoq aallartiffianeersumiit:',

# Special:LinkSearch
'linksearch-ok' => 'Ujaruk',

# Special:Log/newusers
'newuserlog-create-entry' => 'Atuisoq nutaaq',

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
'excontent'       => "imarivaa: '$1'",
'excontentauthor' => "imarivaa: '$1' (allattutuaavorlu '[[Special:Contributions/$2|$2]]')",
'delete-confirm'  => 'Peeruk "$1"',
'actioncomplete'  => 'Naammassivoq',
'deletedtext'     => '"$1" peerpoq. Takuuk $2 peerneqarsimasut kingulliit.',
'deletedarticle'  => 'peerpaa "[[$1]]"',

# Rollback
'revertpage' => 'Inisseqqiineq [[User:$1|$1]]-meersoq, peerneqarpoq [[Special:Contributions/$2|$2]] ([[User talk:$2|diskussion]])-meersoq',

# Protect
'prot_1movedto2'         => '[[$1]]-i nuunneqarsimavoq [[$2]]-mut',
'protect-expiry-options' => '1 tiimi:1 hour,ulloq 1:1 day,sap akunn 1:1 week,sap akunn 2:2 weeks,qaammat 1:1 month,qaammatit 3:3 months,qaammatit 6:6 months,ukioq 1:1 year,killeqanngitsoq:infinite',

# Undelete
'undeletebtn'      => 'Inisseqqiguk',
'undeletedarticle' => 'inisseqqippaa "[[$1]]"',

# Namespace form on various pages
'namespace'      => 'Quppernerup ilusia:',
'invert'         => 'Quppernerup ilusia qinernagu',
'blanknamespace' => '(Pingaarneq)',

# Contributions
'contributions' => 'Atuisup tapii',
'mycontris'     => 'Tapikka',
'contribsub2'   => '$1-meersoq ($2)',
'uctop'         => '(kingulleq)',
'month'         => 'Qaammat:',
'year'          => 'Ukioq:',

'sp-contributions-newbies'  => 'Atuisut nutaaginnaat takukkit',
'sp-contributions-talk'     => 'oqallinneq',
'sp-contributions-search'   => 'Tapiisunik ujaasineq',
'sp-contributions-username' => 'IP adresse imalt. atuisoq:',
'sp-contributions-submit'   => 'Ujaruk',

# What links here
'whatlinkshere'      => 'Suna maangamut innersuussisoq',
'whatlinkshere-prev' => '{{PLURAL:$1|siulia|siulii $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|tullia|tullii $1}}',

# Block/unblock
'blockip'            => 'Atuisoq asseruk',
'blockip-legend'     => 'Atuisoq asseruk',
'ipadressorusername' => 'IP adresse imalt. atuisoq:',
'ipboptions'         => '2 tiimit:2 hours,ulloq 1:1 day,ullut 3:3 days,sap akunn 1:1 week,sap akunn 2:2 weeks,qaammat 1:1 month,qaammatit 3:3 months,qaammatit 6:6 months,ukioq 1:1 year,killeqanngitsoq:infinite',
'blocklistline'      => '$1, $2 asserpaa $3 ($4)',
'infiniteblock'      => 'killeqanngitsoq',
'expiringblock'      => 'atorunnaassaaq $1 $2-nngoruni',
'blocklink'          => 'assersoruk',
'contribslink'       => 'tapikkat',
'blocklogentry'      => 'asserpaa [[$1]] $2-mik sivissusilimmik $3',

# Move page
'movearticle'     => 'Qupperneq nuuguk',
'move-watch'      => 'Qupperneq ersersimatiguk',
'movepagebtn'     => 'Qupperneq nuuguk',
'pagemovedsub'    => 'Nuunnera iluatsippoq',
'movepage-moved'  => 'Qupperneq \'\'\'"$1" uunga nuuppoq "$2"\'\'\'',
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
'tooltip-n-currentevents'        => 'Maannakkut pisut tunuliaqutai takukkit',
'tooltip-n-recentchanges'        => 'Wikimi allannguutit kingulliit',
'tooltip-n-randompage'           => 'Allaaserisamukarit',
'tooltip-n-help'                 => 'Qanoq iliussaanga ...',
'tooltip-t-whatlinkshere'        => 'Innersuussami saqqummiussat',
'tooltip-t-recentchangeslinked'  => 'Massuma quppernerani allannguutit kingulliit',
'tooltip-t-upload'               => 'Assinik mediafiilinilluunniit ilisigit',
'tooltip-t-specialpages'         => 'Quppernerit immikkut ittut nassaassaasinnaasut',
'tooltip-t-print'                => 'Quppernerup naqikkuminarnera',
'tooltip-t-permalink'            => 'Massuma quppernerup taamaaqqaarnera',
'tooltip-ca-nstab-main'          => 'Imarisaa takuuk',
'tooltip-save'                   => 'Allannguutitit toqqukkit',
'tooltip-preview'                => 'Isikkua takuuk, toqqortinnaguk atortaruk!',

# Attribution
'lastmodifiedatby' => 'Una qupperneq kingullermik allanngortinneqarsimavoq $2, $1 $3-mit.',

# Patrol log
'patrol-log-line' => 'nalunaaqqutserpaa $1 $2 misissorneqarsimasutut $3',

# Browsing diffs
'previousdiff' => '← Assigiinngissut siulia',
'nextdiff'     => 'Assigiinngissut tullia →',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tamarmik',
'namespacesall'    => 'tamarmik',
'monthsall'        => 'tamarmik',

# Auto-summaries
'autosumm-new' => "Qupperneq pilersippaa '$1'",

# Special:SpecialPages
'specialpages' => 'Quppernerit immikkut ittut',

# Add categories per AJAX
'ajax-confirm-save' => 'Toqqoruk',

);
