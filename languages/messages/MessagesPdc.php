<?php
/** Deitsch (Deitsch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Kaganer
 * @author Krinkle
 * @author Xqt
 * @author לערי ריינהארט
 */

$fallback = 'de';

$namespaceNames = array(
	NS_TALK             => 'Dischbedutt',
	NS_USER             => 'Yuuser',
	NS_USER_TALK        => 'Yuuser_Dischbedutt',
	NS_PROJECT_TALK     => '$1_Dischbedutt',
	NS_FILE             => 'Feil',
	NS_FILE_TALK        => 'Feil_Dischbedutt',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Dischbedutt',
	NS_TEMPLATE         => 'Moddel',
	NS_TEMPLATE_TALK    => 'Moddel_Dischbedutt',
	NS_HELP             => 'Hilf',
	NS_HELP_TALK        => 'Hilf_Dischbedutt',
	NS_CATEGORY         => 'Abdeeling',
	NS_CATEGORY_TALK    => 'Abdeeling_Dischbedutt',
);

$namespaceAliases = array(
	# German namespaces
	'Medium'               => NS_MEDIA,
	'Spezial'              => NS_SPECIAL,
	'Diskussion'           => NS_TALK,
	'Benutzer'             => NS_USER,
	'Benutzer_Diskussion'  => NS_USER_TALK,
	'$1_Diskussion'        => NS_PROJECT_TALK,
	'Datei'                => NS_FILE,
	'Datei_Diskussion'     => NS_FILE_TALK,
	'MediaWiki_Diskussion' => NS_MEDIAWIKI_TALK,
	'Vorlage'              => NS_TEMPLATE,
	'Vorlage_Diskussion'   => NS_TEMPLATE_TALK,
	'Hilfe'                => NS_HELP,
	'Hilfe_Diskussion'     => NS_HELP_TALK,
	'Kategorie'            => NS_CATEGORY,
	'Kategorie_Diskussion' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Listadmins'                => array( 'Verwalter', 'Administratoren' ),
	'Listbots'                  => array( 'Waddefresser', 'Bots' ),
	'Search'                    => array( 'Uffgucke', 'Suche' ),
);

$messages = array(
# User preference toggles
'tog-underline'          => 'Gleecher unnerleine:',
'tog-hideminor'          => 'Gleene Enneringe verschwinne losse',
'tog-watchdefault'       => 'Vun mir gennerte Ardickele watsche',
'tog-nocache'            => 'Bledder-Scheier ausmache',
'tog-watchlisthideown'   => 'Mei Ardickele vun mei Watsch-Lischt verschwinne losse',
'tog-watchlisthidebots'  => 'Enneringe vun Bots vun mei Watsch-Lischt verschwinne losse',
'tog-watchlisthideminor' => 'Gleene Enneringe vun mei Watsch-Lischt verschwinne losse',

'underline-always' => 'allfart',
'underline-never'  => 'nie naett',

# Dates
'sunday'        => 'Sunndaag',
'monday'        => 'Mundaag',
'tuesday'       => 'Dinschdaag',
'wednesday'     => 'Mittwoch',
'thursday'      => 'Dunnerschdaag',
'friday'        => 'Freidaag',
'saturday'      => 'Samschdaag',
'sun'           => 'Su',
'mon'           => 'Mo',
'tue'           => 'Di',
'wed'           => 'Mi',
'thu'           => 'Du',
'fri'           => 'Fr',
'sat'           => 'Sa',
'january'       => 'Yenner',
'february'      => 'Hanning',
'march'         => 'Matz',
'april'         => 'Abril',
'may_long'      => 'Moi',
'june'          => 'Yuni',
'july'          => 'Yuli',
'august'        => 'Aagscht',
'september'     => 'September',
'october'       => 'Oktower',
'november'      => 'Nowember',
'december'      => 'Disember',
'january-gen'   => 'Yenner',
'february-gen'  => 'Hanning',
'march-gen'     => 'Matz',
'april-gen'     => 'Abril',
'may-gen'       => 'Moi',
'june-gen'      => 'Tschuun',
'july-gen'      => 'Tschulei',
'august-gen'    => 'Aagscht',
'september-gen' => 'September',
'october-gen'   => 'Oktower',
'november-gen'  => 'Nowember',
'december-gen'  => 'Disember',
'jan'           => 'Yen.',
'feb'           => 'Han.',
'mar'           => 'Matz',
'apr'           => 'Abr.',
'may'           => 'Moi',
'jun'           => 'Yuni',
'jul'           => 'Yuli',
'aug'           => 'Aug.',
'sep'           => 'Sep.',
'oct'           => 'Okt.',
'nov'           => 'Nov.',
'dec'           => 'Dis.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Abdeeling|Abdeelinge}}',
'category_header'                => 'Bledder in Abdeeling „$1“',
'subcategories'                  => 'Unnerabdeeling',
'category-media-header'          => 'Media in Abdeeling „$1“',
'category-empty'                 => "''Die Abdeeling hot zu daere Zeit kene Bledder oder Feils.''",
'hidden-categories'              => '{{PLURAL:$1|Versteckelte Abdeeling|Verstecktelte Abdeelinge}}',
'category-article-count-limited' => '{{PLURAL:$1|Sell Blatt iss|Selle $1 Bledder sin}} in daer Abdeeling drin:',
'category-file-count-limited'    => '{{PLURAL:$1|Sell Feil iss|Selle $1 Feils sin}} in daer Abdeeling drin:',
'listingcontinuesabbrev'         => '(weider)',

'about'         => 'Iwwer',
'article'       => 'Blatt',
'newwindow'     => '(in em nei Fenschder)',
'cancel'        => 'Zerick',
'moredotdotdot' => 'Mehner…',
'mypage'        => 'Mei Blatt',
'mytalk'        => 'Mei Dischbedutt',
'anontalk'      => 'Gschwetz-Blatt fer die IP',
'navigation'    => 'Faahre-Gnepp',
'and'           => '&#32;unn',

# Cologne Blue skin
'qbfind'         => 'Finne',
'qbedit'         => 'Ennere',
'qbpageoptions'  => 'Des Blatt',
'qbpageinfo'     => 'Daade vun dem Blatt',
'qbmyoptions'    => 'Mei Bledder',
'qbspecialpages' => 'Besunnere Bledder',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-delete'  => 'Verwische',
'vector-action-move'    => 'Ziehe',
'vector-action-protect' => 'Schitze',
'vector-view-create'    => 'Schtaerte',
'vector-view-edit'      => 'Ennere',
'vector-view-history'   => 'Gschicht zeige',
'vector-view-view'      => 'Lese',
'namespaces'            => 'Blatznaame',

'errorpagetitle'   => 'Mischteek',
'returnto'         => 'Zerick zum Blatt $1.',
'tagline'          => 'Vun {{SITENAME}}',
'help'             => 'Hilf',
'search'           => 'Guck uff',
'searchbutton'     => 'Guck uff',
'go'               => 'Geh',
'searcharticle'    => 'Blatt',
'history'          => 'Gschicht',
'history_short'    => 'Gschicht',
'printableversion' => 'Version zum Drucke',
'permalink'        => 'Permanent Gleecher',
'print'            => 'Drucke',
'view'             => 'Aagucke',
'edit'             => 'Ennere/Tscheensche',
'create'           => 'Schtaerte',
'editthispage'     => 'Des Blatt ennere',
'create-this-page' => 'Blatt schtaerte',
'delete'           => 'Verwische',
'deletethispage'   => 'Des Blatt verwische',
'protect'          => 'Schitze',
'protect_change'   => 'tscheensche',
'protectthispage'  => 'Des Blatt schitze',
'newpage'          => 'Neies Blatt',
'talkpage'         => 'Sell Blatt dischbediere',
'talkpagelinktext' => 'Dischbedutt',
'specialpage'      => 'Besunneres Blatt',
'personaltools'    => 'Paerseenlich Gscharr',
'articlepage'      => 'Inhalt vun dem Blatt aagucke',
'talk'             => 'Dischbedutt',
'views'            => 'Aasichte',
'toolbox'          => 'Gscharr',
'userpage'         => 'Yuuserblatt zeige',
'projectpage'      => 'Projekt-Blatt aagucke',
'imagepage'        => 'Feils zeige',
'templatepage'     => 'Moddle zeige',
'categorypage'     => 'Abeelingsblatt zeige',
'viewtalkpage'     => 'Dischbedutt zeige',
'otherlanguages'   => 'Annere Schprooche',
'redirectedfrom'   => '(Weiterleitung vun $1)',
'redirectpagesub'  => 'Weiderleiding',
'lastmodifiedat'   => 'Des Blatt iss letscht gennert am $1 um $2 Uhr.',
'protectedpage'    => 'Blatt mit Schutz',
'jumpto'           => 'Gang nooch:',
'jumptonavigation' => 'Faahre-Gnepp',
'jumptosearch'     => 'guck uff',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Iwwer {{SITENAME}}',
'aboutpage'            => 'Project:Iwwer_{{SITENAME}}',
'copyright'            => 'Was do drin schdeht iss unner $1 verfiechbar',
'copyrightpage'        => '{{ns:project}}:Urhewerrechte',
'disclaimers'          => 'Impressum',
'disclaimerpage'       => 'Project:Impressum',
'edithelp'             => 'Hilf zum Ennere',
'edithelppage'         => 'Help:Tscheensche',
'helppage'             => 'Help:Hilf',
'mainpage'             => 'Haaptblatt',
'mainpage-description' => 'Haaptblatt',
'portal'               => 'Gmeeschafts-Portal',
'portal-url'           => 'Project:Gmeeschafts-Portal',
'privacy'              => 'Daadeschutz',
'privacypage'          => 'Project:Daadeschutz',

'versionrequired'     => 'Muss Version $1 vun MediaWiki sei',
'versionrequiredtext' => 'Muss Version $1 vun MediaWiki sei, fer es Blatt zu yuuse.
Guck aa [[Special:Version|Versionsblatt]]',

'ok'                      => 'OK',
'retrievedfrom'           => 'Vun „$1“',
'youhavenewmessages'      => 'Du hast $1 uff deinem $2.',
'newmessageslink'         => 'Neiichkeede',
'newmessagesdifflink'     => 'Gschwetz-Blatt',
'youhavenewmessagesmulti' => 'Du hascht neie Comments: $1',
'editsection'             => 'Ennere',
'editold'                 => 'Ennere',
'editlink'                => 'ennere',
'editsectionhint'         => 'Abschnitt ennere: $1',
'toc'                     => 'Lischt vum Inhalt',
'showtoc'                 => 'Zeige',
'hidetoc'                 => 'Verschwinne losse',
'collapsible-collapse'    => 'Zuklappe',
'collapsible-expand'      => 'Uffklappe',
'viewdeleted'             => '$1 zeige?',
'feedlinks'               => 'Feed:',
'site-rss-feed'           => 'RSS-Feed fer $1',
'site-atom-feed'          => 'Atom-Feed fer $1',
'page-rss-feed'           => 'RSS-Feed fer „$1“',
'page-atom-feed'          => 'Atom-Feed fer „$1“',
'red-link-title'          => '$1 (Blatt gebt es net)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Blatt',
'nstab-user'      => 'Yuuser-Blatt',
'nstab-media'     => 'Media-Blatt',
'nstab-special'   => 'Besunneres Blatt',
'nstab-project'   => 'Project-Blatt',
'nstab-image'     => 'Feil',
'nstab-mediawiki' => 'Melding vum System',
'nstab-template'  => 'Moddel',
'nstab-help'      => 'Hilf',
'nstab-category'  => 'Abdeeling',

# General errors
'error'               => 'Mischteek',
'databaseerror'       => 'Fehler in de Daadescheier',
'missing-article'     => 'Sell Text fer „$1“ $2 iss in de Daadebank naett gfunne warre.

Des Blatt iss verleicht glescht adder gezoge warre.

Wenns des net iss, hoscht verleicht en Fehler in de Daadebank gfunne. Bitte meld des an en [[Special:ListUsers/sysop|Verwalter]] unn gebb die URL dezu aa.',
'missingarticle-rev'  => '(Version: $1)',
'missingarticle-diff' => '(Unnerschidd zwische Versione: $1, $2)',
'internalerror'       => 'Interner Fehler',
'internalerror_info'  => 'Interner Fehler: $1',
'badtitle'            => 'Tidl net gildich',
'ns-specialprotected' => 'Besunnere Bledder sinn net zum Ennere.',

# Virus scanner
'virus-unknownscanner' => 'Unbekannter Virus-Uffgucker:',

# Login and logout pages
'yourname'                => 'Yuuser-Naame:',
'yourpassword'            => 'Paesswatt:',
'yourpasswordagain'       => 'Paesswatt noch eemol:',
'yourdomainname'          => 'Dei Domain:',
'login'                   => 'Kumm nei',
'nav-login-createaccount' => 'Kumm nei',
'userlogin'               => 'Kumm nei',
'userloginnocreate'       => 'Kumm nei',
'logout'                  => 'Geh naus',
'userlogout'              => 'Geh naus',
'gotaccountlink'          => 'Kumm nei',
'createaccountmail'       => 'iwwer E-Mail',
'createaccountreason'     => 'Grund:',
'mailmypassword'          => 'Neies Paesswadd eposchde',
'noemail'                 => 'Yuuser „$1“ hot ken E-Mail aagewwe.',
'loginlanguagelabel'      => 'Schprooch: $1',

# Change password dialog
'resetpass'                 => 'Paesswatt ennere',
'oldpassword'               => 'Aldes Paesswatt:',
'newpassword'               => 'Neies Paesswatt:',
'resetpass_forbidden'       => 'Paesswatt iss net zu ennere',
'resetpass-submit-loggedin' => 'Paesswatt ennere',

# Special:PasswordReset
'passwordreset'              => 'Paesswatt zerricksetze',
'passwordreset-legend'       => 'Paesswatt zerricksetze',
'passwordreset-username'     => 'Yuuser-Naame:',
'passwordreset-emailelement' => 'Yuusernaame: $1
Paesswatt fer nau: $2',

# Special:ChangeEmail
'changeemail-none' => '(ken)',

# Edit page toolbar
'bold_sample'     => 'Wadde fett gmarrickt',
'bold_tip'        => 'Wadde fett gmarrickt',
'link_sample'     => 'Gleecher-Titel',
'link_tip'        => 'Gleecher',
'extlink_sample'  => 'http://www.example.com Gleecher-Text',
'extlink_tip'     => 'Gewebbgleecher (acht uff http://)',
'headline_sample' => 'Iwwerschrift',
'headline_tip'    => 'Iwwerschrift Level 2',
'image_sample'    => 'Beeschpiel.jpg',
'media_sample'    => 'Beeschpiel.ogg',
'media_tip'       => 'Gleecher fer Feil',

# Edit pages
'minoredit'              => 'Nur gleene Enneringe gemacht',
'watchthis'              => 'Watsch des Blatt',
'savearticle'            => 'Blatt beilege',
'preview'                => 'Aagucke',
'showdiff'               => 'Enneringe zeige',
'blockednoreason'        => 'ken Grund gewwe',
'loginreqlink'           => 'kumm nei',
'newarticle'             => '(Nei)',
'note'                   => "'''Hieweis:'''",
'editing'                => '$1 ennere',
'editingsection'         => 'Ennere vun $1 (Abschnitt)',
'editingcomment'         => 'Ennere vun $1 (Neier Abschnitt)',
'editconflict'           => 'Druwwel beim Ennere: $1',
'yourdiff'               => 'Unnerschidde',
'templatesused'          => '{{PLURAL:$1|Sell Moddel iss|Selle Moddle sinn}} gyuust vun dem Blatt:',
'template-protected'     => '(geschitzt)',
'template-semiprotected' => '(geschitzt fer neie Yuuser)',

# "Undo" feature
'undo-summary' => 'Enneringe $1 vun [[Special:Contributions/$2|$2]] ([[User talk:$2|Dischbedutt]]) losgmacht.',

# History pages
'revisionasof'     => 'Version vum $2, $3 Uhr',
'previousrevision' => '← letscht Version',
'nextrevision'     => 'Neiere Version →',
'next'             => 'Neegschte',
'last'             => 'Letscht',
'page_first'       => 'Aafang',
'page_last'        => 'End',
'histfirst'        => 'Eldescht',
'histlast'         => 'Letscht',
'historysize'      => '({{PLURAL:$1|1 Beit|$1 Beit}})',
'historyempty'     => '(leer)',

# Revision deletion
'rev-deleted-comment'        => '(Aamaericking iss weg geduh warre)',
'rev-deleted-user'           => '(Yuuser-Naame gelöscht)',
'rev-delundel'               => 'zeig/verschwinne losse',
'rev-showdeleted'            => 'zeig',
'revdelete-no-file'          => 'Sell Feil gebt es net.',
'revdelete-show-file-submit' => 'Ya',
'revdelete-hide-text'        => 'Text vun de Version verschwinne losse',
'revdelete-radio-same'       => '(net ennere)',
'revdelete-radio-set'        => 'Ya',
'revdelete-radio-unset'      => 'Nee',
'revdelete-log'              => 'Grund:',
'pagehist'                   => 'Gschicht',
'revdelete-otherreason'      => 'Annere Grind dezu:',
'revdelete-reasonotherlist'  => 'Annere Grind',
'revdelete-edit-reasonlist'  => "Grind fer's Lesche ennere",
'revdelete-offender'         => 'Schreiwer fun daer Version:',

# History merging
'mergehistory-reason' => 'Grund:',

# Diffs
'difference' => '(Unnerschidd zwische Versione)',
'lineno'     => 'Lein $1:',
'editundo'   => 'losmache',

# Search results
'searchresults'                  => 'Results vum Uffgucke',
'searchresults-title'            => 'Results vum Uffgucke fer „$1“',
'searchsubtitle'                 => 'Du hoscht nooch \'\'\'[[:$1]]\'\'\' gsucht ([[Special:Prefixindex/$1|alle Bledder wu mit "$1" aafange]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|alle Bledder wu uff "$1" zeige]])',
'prevn'                          => '{{PLURAL:$1|letscht|letscht $1}}',
'nextn'                          => 'neegschte {{PLURAL:$1|$1}}',
'viewprevnext'                   => 'Zeige ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-new'                 => "'''Schtaert des Blatt „[[:$1|$1]]“ uf dem Wiki.'''",
'searchhelp-url'                 => 'Help:Hilf',
'searchprofile-everything'       => 'Abaddiche',
'searchprofile-articles-tooltip' => 'Uffgucke in $1',
'searchprofile-project-tooltip'  => 'Uffgucke in $1',
'searchprofile-images-tooltip'   => 'Nooch Feils uffgucke',
'search-result-size'             => '$1 ({{PLURAL:$2|1 Wadd|$2 Wadde}})',
'search-redirect'                => '(Weiderleiding vun „$1“)',
'search-section'                 => '(Abschnitt $1)',
'search-suggest'                 => 'Iss „$1“ gemeent?',
'search-interwiki-caption'       => 'Schweschder Projects',
'search-interwiki-default'       => '$1 Results:',
'search-interwiki-more'          => '(weidere)',
'search-mwsuggest-enabled'       => 'mit Vorschläch',
'search-mwsuggest-disabled'      => 'kee Vorschläch',
'searchall'                      => 'all',
'powersearch'                    => 'Guck uff',
'powersearch-ns'                 => 'Guck uff in Blatznaame:',
'powersearch-redir'              => 'Lischt vun Weiterleidinge',
'powersearch-field'              => 'Such fer',
'powersearch-toggleall'          => 'All',
'powersearch-togglenone'         => 'Ken',
'search-external'                => 'Guck im Gewebb',

# Quickbar
'qbsettings-none' => 'Ken',

# Preferences page
'preferences'              => 'Paerseenlich Profil',
'mypreferences'            => 'Uffschtellinge',
'changepassword'           => 'Paesswatt ennere',
'skin-preview'             => 'Aagucke',
'prefs-personal'           => 'Yuuser Profile',
'prefs-watchlist'          => 'Watsch-Lischt',
'prefs-watchlist-days'     => 'Daage in de Watsch-Lischt:',
'prefs-resetpass'          => 'Paesswatt ennere',
'saveprefs'                => 'Uffstellinge beilege',
'resetprefs'               => 'Ausduh',
'prefs-editing'            => 'Ennere',
'columns'                  => 'Kallems:',
'searchresultshead'        => 'Guck uff',
'recentchangesdays-max'    => 'Max. $1 {{PLURAL:$1|Daag|Daag}}',
'timezoneregion-africa'    => 'Afrikaa',
'timezoneregion-america'   => 'Amerikaa',
'timezoneregion-asia'      => 'Asie',
'timezoneregion-australia' => 'Australie',
'timezoneregion-europe'    => 'Eiropaa',
'prefs-namespaces'         => 'Blatznaame',
'prefs-files'              => 'Feils',
'prefs-custom-css'         => 'CSS vum Yuuser',
'prefs-custom-js'          => 'JavaScript vum Yuuser',
'youremail'                => 'E-Poschde:',
'username'                 => 'Yuuser-Naame:',
'uid'                      => 'Yuuser-ID:',
'prefs-memberingroups'     => 'Mitglied vun de {{PLURAL:$1|Yuuser-Druppe|Yuuser-Druppe}}:',
'yourlanguage'             => 'Schprooch:',
'yourgender'               => 'Geschlecht:',
'gender-female'            => 'Weiblich',
'email'                    => 'E-Poschde',
'prefs-signature'          => 'Unnerschrift',
'prefs-diffs'              => 'Unnerschidd vun Versione',

# User rights
'userrights-editusergroup' => 'Mitgliedschaft vun Yuuser ennere',
'userrights-groupsmember'  => 'Mitglied vun:',
'userrights-reason'        => 'Grund:',

# Groups
'group'       => 'Druppe:',
'group-user'  => 'Yuuser',
'group-bot'   => 'Waddefresser',
'group-sysop' => 'Verwalter',
'group-all'   => '(all)',

'group-user-member'  => '{{GENDER:$1|Yuuser}}',
'group-bot-member'   => '{{GENDER:$1|Waddefresser}}',
'group-sysop-member' => '{{GENDER:$1|Verwalter}}',

'grouppage-user'  => '{{ns:project}}:Yuuser',
'grouppage-bot'   => '{{ns:project}}:Waddefresser',
'grouppage-sysop' => '{{ns:project}}:Verwalter',

# Rights
'right-read'     => 'Bledder lese',
'right-edit'     => 'Bledder ennere',
'right-move'     => 'Bledder ziehe',
'right-movefile' => 'Feils ziehe',
'right-upload'   => 'Feils nuffdraage',
'right-writeapi' => 'Yuus vun write API',
'right-delete'   => 'Bledder lesche',

# User rights log
'rightsnone' => '(ken)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'     => 'es Blatt zu lese',
'action-edit'     => 'des Blatt zu ennere',
'action-move'     => 'des Blatt zu ziehe',
'action-movefile' => 'Des Feil ziehe',
'action-upload'   => 'Des Feil ufflaade',
'action-delete'   => 'des Blatt zu verwische',

# Recent changes
'nchanges'                  => '$1 {{PLURAL:$1|Ennering|Enneringe}}',
'recentchanges'             => 'Was mer letscht geduh henn',
'recentchanges-label-minor' => 'Gleene Ennering',
'recentchanges-label-bot'   => 'Ennering vun em Waddefresser',
'rcshowhideminor'           => 'Gleene Enneringe $1',
'rcshowhidebots'            => 'Bots $1',
'rcshowhideanons'           => 'IP-Yuuser $1',
'rcshowhidemine'            => 'Mei Ardickele $1',
'rclinks'                   => 'Zeig die letscht $1 Enneringe vun de letscht $2 Daag.<br />$3',
'diff'                      => 'Unnerschidd',
'hist'                      => 'Gschicht',
'hide'                      => 'verschwinne losse',
'show'                      => 'zeige',
'minoreditletter'           => 'g',
'newpageletter'             => 'N',
'boteditletter'             => 'W',
'rc_categories_any'         => 'All',
'rc-change-size-new'        => '$1 {{PLURAL:$1|Beit|Beit}} nooch daer Ennering',
'newsectionsummary'         => 'Neier Abschnitt /* $1 */',

# Recent changes linked
'recentchangeslinked'      => 'Was on verlinkde Bledder geduh warre iss',
'recentchangeslinked-page' => 'Blatt:',

# Upload
'upload'             => 'Nuffdraage',
'uploadbtn'          => 'Feil nuffdraage',
'uploadlogpage'      => 'Feil-Lochbuch',
'filename'           => 'Feilnaame',
'badfilename'        => 'Daer Feilnaame iss gennert warre nooch „$1“.',
'savefile'           => 'Feil beilege',
'uploadedimage'      => 'hot „[[$1]]“ uffglaade',
'overwroteimage'     => 'hot e neie Version vun „[[$1]]“ uffglaade',
'uploaddisabled'     => 'Ufflaade verbodde',
'uploaddisabledtext' => 'Es Ufflaade vun Feils iss verbodde.',
'watchthisupload'    => 'Watsch des Blatt',

'upload-file-error'   => 'Interner Fehler',
'upload-unknown-size' => 'Unbekannte Grees',
'upload-http-error'   => 'En HTTP-Fehler iss kumme: $1',

# File backend
'backend-fail-backup' => 'Des Feil $1 iss net zwettgmacht warre.',

# img_auth script messages
'img-auth-nofile' => 'Feil „$1“ gebt es net.',

'upload_source_file' => ' (e Feil uff deim Waddefresser)',

# Special:ListFiles
'imgfile'         => 'Feil',
'listfiles'       => 'Lischt vun Feils',
'listfiles_date'  => 'Datum',
'listfiles_name'  => 'Naame',
'listfiles_user'  => 'Yuuser',
'listfiles_size'  => 'Grees',
'listfiles_count' => 'Versione',

# File description page
'file-anchor-link'    => 'Feil',
'filehist'            => 'Versione vun Feils',
'filehist-deleteall'  => 'All Versione lösche',
'filehist-deleteone'  => 'Sell Version verwische',
'filehist-revert'     => 'zerick',
'filehist-datetime'   => 'Version vum',
'filehist-thumb'      => 'Glee Pikder',
'filehist-user'       => 'Yuuser',
'filehist-dimensions' => 'Grees',
'filehist-filesize'   => 'Grees vum Feil',
'filehist-comment'    => 'Aamaericking',
'imagelinks'          => 'Yuus vun dem Feil',
'shared-repo-from'    => 'vun $1',

# File reversion
'filerevert-comment' => 'Grund:',
'filerevert-submit'  => 'Zerick',

# File deletion
'filedelete'                  => 'Lösche „$1“',
'filedelete-comment'          => 'Grund:',
'filedelete-submit'           => 'Verwische',
'filedelete-nofile'           => "'''„$1“''' gebt es net.",
'filedelete-otherreason'      => 'Annere Grind dezu:',
'filedelete-reason-otherlist' => 'Annerer Gund',
'filedelete-edit-reasonlist'  => "Grind fer's Lesche ennere",

# MIME search
'download' => 'Runnerlaade',

# List redirects
'listredirects' => 'Lischt vun Weiderleidinge',

# Random page
'randompage' => 'Ennich Ardickel',

# Random redirect
'randomredirect' => 'Random Weiderleiding',

# Statistics
'statistics'              => 'Nummere',
'statistics-header-pages' => 'Nummere vun Bledder',
'statistics-header-edits' => 'Nummere vun Enneringe',
'statistics-header-users' => 'Nummere vun Yuuser',
'statistics-pages'        => 'Bledder',

'doubleredirects'       => 'Zweefache Weiderleidinge',
'double-redirect-fixer' => 'Xqbot',

'brokenredirects'        => 'Kaputte Weiderleidinge',
'brokenredirects-edit'   => 'ennere',
'brokenredirects-delete' => 'verwische',

'withoutinterwiki-submit' => 'Zeig',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|Beit|Beit}}',
'ncategories'       => '$1 {{PLURAL:$1|Abdeeling|Abdeelinge}}',
'nlinks'            => '{{PLURAL:$1|1 Gleecher|$1 Gleecher}}',
'nimagelinks'       => 'Gyuust uff $1 {{PLURAL:$1|Blatt|Bledder}}',
'ntransclusions'    => 'gyuust uff $1 {{PLURAL:$1|Blatt|Bledder}}',
'shortpages'        => 'Glee Bledder',
'longpages'         => 'Grosse Bledder',
'listusers'         => 'Lischt vun Yuuser',
'usereditcount'     => '$1 {{PLURAL:$1|Ennering|Enneringe}}',
'newpages'          => 'Neie Bledder',
'newpages-username' => 'Yuuser-Naame:',
'ancientpages'      => 'Eldere Bledder',
'move'              => 'Ziehe',
'movethispage'      => 'Blatt ziehe',
'pager-newer-n'     => '{{PLURAL:$1|neegscht|neegscht $1}}',
'pager-older-n'     => '{{PLURAL:$1|letscht|letscht $1}}',

# Book sources
'booksources-go' => 'Uffgucke',

# Special:Log
'specialloguserlabel'  => 'Yuuser:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Logbicher',

# Special:AllPages
'allpages'          => 'Alle Bledder',
'alphaindexline'    => '$1 bis $2',
'nextpage'          => 'Neegschtes Blatt ($1)',
'prevpage'          => 'Letscht Blatt ($1)',
'allarticles'       => 'Alle Bledder',
'allinnamespace'    => 'Alle Bledder (Blatznaame: $1)',
'allnotinnamespace' => 'Alle Bledder (net vun $1 Blatznaame)',
'allpagesprev'      => 'Letscht',
'allpagesnext'      => 'Neegschte',
'allpagessubmit'    => 'Zeige',
'allpages-bad-ns'   => '{{SITENAME}} hot ken Blatznaame „$1“.',

# Special:Categories
'categories' => 'Abdeelinge',

# Special:LinkSearch
'linksearch'    => 'Gewebbgleecher uffgucke',
'linksearch-ns' => 'Blatznaame:',
'linksearch-ok' => 'Uffgucke',

# Special:ListUsers
'listusers-submit'   => 'Zeig',
'listusers-noresult' => 'Ken Yuuser gfunne.',

# Special:ActiveUsers
'activeusers-hidebots'   => 'Waddefresser verschwinne losse',
'activeusers-hidesysops' => 'Verwalter verschwinne losse',

# Special:Log/newusers
'newuserlogpage' => 'Logbuch vun neie Yuuser',

# Special:ListGroupRights
'listgrouprights'              => 'Rechte vun Yuuser-Druppe',
'listgrouprights-group'        => 'Druppe',
'listgrouprights-rights'       => 'Rechte',
'listgrouprights-helppage'     => 'Help:Rechte vun Druppe',
'listgrouprights-members'      => '(Lischt vun Mitglieder)',
'listgrouprights-addgroup'     => 'Yuuser zu {{PLURAL:$2|daer Druppe|denne Druppe}} dezu duh: $1',
'listgrouprights-addgroup-all' => 'Yuuser zu alle Druppe dezu duh',

# E-mail user
'emailuser'       => 'E-Poschd fer den Yuuser',
'defemailsubject' => '{{SITENAME}} - E-Poschde vun Yuuser „$1“',
'emailusername'   => 'Yuuser-Naame:',
'emailfrom'       => 'Vun:',
'emailto'         => 'Fer:',
'emailsend'       => 'Schicke',
'emailsent'       => 'E-Poscht naus gschickt',

# Watchlist
'watchlist'         => 'Mei Watsch-Lischt',
'mywatchlist'       => 'Watsch-Lischt',
'watchlistfor2'     => 'Vun $1 $2',
'watch'             => 'watsche',
'watchthispage'     => 'watsch des Blatt',
'unwatch'           => 'Nimmi watsche',
'unwatchthispage'   => 'Nimmi watsche',
'notanarticle'      => 'Ken Blatt',
'watchlist-details' => '{{PLURAL:$1|$1 Blatt|$1 Bledder}} uff dei Watch-Lischt, ohne Gschwetz-Bledder',
'watchlistcontains' => 'Dei Watsch-Lischt hot $1 {{PLURAL:$1|Blatt|Bledder}}.',
'wlshowlast'        => 'Zeig die Enneringe vun de letscht $1 Schtund, $2 Daag odder $3.',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Watsche…',
'unwatching' => 'Nimmi watsche...',

'enotif_newpagetext'           => 'Sell iss en neies Blatt.',
'enotif_impersonal_salutation' => '{{SITENAME}}-Yuuser',
'changed'                      => 'gennert',

# Delete
'deletepage'             => 'Blatt lesche',
'exblank'                => 'Blatt war leer',
'delete-confirm'         => 'Lesche vun „$1“',
'delete-legend'          => 'Verwische',
'deletedtext'            => '"$1" iss gelescht warre.
Guck $2 fer e Lischt vun de letscht Leschunge.',
'dellogpage'             => 'Lischt vun gelöschte Bledder',
'deletecomment'          => 'Grund:',
'deleteotherreason'      => 'Annre Grind:',
'deletereasonotherlist'  => 'Annerer Grund',
'delete-edit-reasonlist' => "Grind fer's Lesche ennere",

# Protect
'prot_1movedto2'            => 'hot „[[$1]]“ nooch „[[$2]]“ gezoge',
'protectcomment'            => 'Grund:',
'protect-default'           => 'All Yuuser',
'protect-level-sysop'       => 'Nur Verwalter',
'protect-expiring'          => 'bis $2, $3 Uhr (UTC)',
'protect-expiry-indefinite' => 'fer immer',
'protect-othertime'         => 'Annere Zeit:',
'protect-othertime-op'      => 'annere Zeit',
'protect-otherreason'       => 'Annerer Grund:',
'protect-otherreason-op'    => 'Annerer Grund',
'protect-expiry-options'    => '1 Schtund:1 hour,1 Daag:1 day,1 Woch:1 week,2 Woche:2 weeks,1 Munet:1 month,3 Munede:3 months,6 Munede:6 months,1 Yaar:1 year,Fer immer:infinite',
'minimum-size'              => 'Min. Grees',
'maximum-size'              => 'Max. Grees:',
'pagesize'                  => '(Beit)',

# Restrictions (nouns)
'restriction-edit'   => 'Ennere/Tscheensche',
'restriction-move'   => 'Ziehe',
'restriction-create' => 'Schtaerte',
'restriction-upload' => 'Ufflaade',

# Undelete
'undeleteviewlink'          => 'aagucke',
'undeletecomment'           => 'Grund:',
'undelete-search-submit'    => 'Guck uff',
'undelete-show-file-submit' => 'Ya',

# Namespace form on various pages
'namespace'      => 'Blatznaame:',
'blanknamespace' => '(Bledder)',

# Contributions
'contributions'       => 'Ardickele vum Yuuser',
'contributions-title' => 'Ardickele vun „$1“',
'mycontris'           => 'Mei Ardickele',
'contribsub2'         => 'Fer $1 ($2)',
'uctop'               => '(ewwerscht)',
'month'               => 'unn Munet:',
'year'                => 'bis Yaahr:',

'sp-contributions-talk'     => 'Dischbedutt',
'sp-contributions-search'   => 'Guck fer Ardickel',
'sp-contributions-username' => 'IP-Adress odder Yuusernaame:',
'sp-contributions-submit'   => 'Guck uff',

# What links here
'whatlinkshere'            => 'Was doher zeigt',
'whatlinkshere-page'       => 'Blatt:',
'isredirect'               => 'Weiderleidingsblatt',
'isimage'                  => 'Gleecher fer Feil',
'whatlinkshere-prev'       => '{{PLURAL:$1|letscht|letscht $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|neegschter|neegschte $1}}',
'whatlinkshere-links'      => '← Gleecher',
'whatlinkshere-hideredirs' => 'Weiderleidinge $1',
'whatlinkshere-hidelinks'  => 'Gleecher $1',
'whatlinkshere-hideimages' => 'Feil Gleecher $1',

# Block/unblock
'block'              => 'Yuuser aabinne',
'blockip'            => 'Yuuser aabinne',
'blockip-title'      => 'Yuuser aabinne',
'blockip-legend'     => 'Yuuser aabinne',
'ipadressorusername' => 'IP-Adress odder Yuusernaame:',
'ipbreason'          => 'Grund:',
'ipbreasonotherlist' => 'Annerer Grund',
'ipbsubmit'          => 'Daen Yuuser aabinne',
'ipbother'           => 'Annere Zeit (englisch):',
'ipboptions'         => '2 Schtund:2 hours,1 Daag:1 day,3 Daag:3 days,1 Woch:1 week,2 Woche:2 weeks,1 Munet:1 month,3 Munede:3 months,6 Monate:6 months,1 Yaar:1 year,Fer immer:infinite',
'ipbotheroption'     => 'Anneres',
'ipbotherreason'     => 'Annerer Grund:',
'blocklist-reason'   => 'Grund',
'ipblocklist-submit' => 'Guck uff',
'infiniteblock'      => 'fer immer',
'blocklink'          => 'Aabinne',
'contribslink'       => 'Ardickele',
'proxyblocksuccess'  => 'Geduh.',

# Move page
'move-page'               => '„$1“ ziehe',
'move-page-legend'        => 'Blatt ziehe',
'movearticle'             => 'Blatt ziehe:',
'move-watch'              => 'watsch des Blatt',
'movepagebtn'             => 'Blatt ziehe',
'pagemovedsub'            => 'Blatt iss gezoge warre',
'movepage-moved'          => "'''Es Blatt „$1“ iss gezoge warre uff „$2“'''",
'movedto'                 => 'gezoge uff',
'movereason'              => 'Grund:',
'revertmove'              => 'zerick ziehe',
'delete_and_move_confirm' => 'Ya, es Blatt lösche',

# Export
'export'          => 'Bledder exportiere',
'export-addcat'   => 'Dezu duh',
'export-addns'    => 'Dezu duh',
'export-download' => 'As XML-Feil annelege',

# Namespace 8 related
'allmessagesname'               => 'Naame',
'allmessages-filter-unmodified' => 'Net gennert',
'allmessages-filter-all'        => 'All',
'allmessages-filter-modified'   => 'Gennert',
'allmessages-language'          => 'Schprooch:',
'allmessages-filter-submit'     => 'Los',

# Thumbnails
'thumbnail-more' => 'greeser mache',

# Special:Import
'import-upload-filename' => 'Feilnaame:',
'import-comment'         => 'Aamaerricking:',
'import-revision-count'  => '– {{PLURAL:$1|1 Version|$1 Versione}}',

# Import log
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|Version|Versione}}',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|Version|Versione}} vun $2',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'Dei Yuuser-Blatt',
'tooltip-pt-mytalk'              => 'Dei Gschwetz-Blatt',
'tooltip-pt-preferences'         => 'Mei Uffschtelling',
'tooltip-pt-mycontris'           => 'Lischt vun deine Ardickel',
'tooltip-pt-login'               => 'Du kannscht Dich aamelde, awwer du muschts net',
'tooltip-pt-logout'              => 'Geh naus',
'tooltip-ca-talk'                => 'Iwwer sell Blatt dischbediere',
'tooltip-ca-edit'                => 'Du kannscht des Blatt ennere. Bitte brauch de Aaguck-Gnopp vor em Speichere.',
'tooltip-ca-history'             => 'Ledschde Versione vun dem Blattt',
'tooltip-ca-protect'             => 'Des Blatt schitze',
'tooltip-ca-delete'              => 'Des Blatt verwische',
'tooltip-ca-move'                => 'Des Blatt ziehe',
'tooltip-search'                 => 'Guck uff {{SITENAME}}',
'tooltip-search-go'              => 'Geh zu dem Blatt mit genaa dem Naame, wenns es gebbt.',
'tooltip-search-fulltext'        => 'Guck nooch Bledder mit denne Wadde',
'tooltip-p-logo'                 => 'Haaptblatt',
'tooltip-n-mainpage'             => 'Zum Haaptblatt geh',
'tooltip-n-mainpage-description' => 'Haaptblatt bsuche',
'tooltip-n-portal'               => 'Iwwers Projekt, was de duhn kannscht, wo de ebbes finnscht',
'tooltip-n-recentchanges'        => 'D Lischt vun de letschte Enneringe in dem Wiki',
'tooltip-n-randompage'           => 'Ennich Ardickel',
'tooltip-n-help'                 => 'Hilf-Blatt zeige',
'tooltip-t-whatlinkshere'        => 'Lischt vun all die Bledder, wu do her zeige',
'tooltip-t-recentchangeslinked'  => 'Letschte Enneringe in Bledder, wu vun do verlinkt sinn',
'tooltip-feed-rss'               => 'RSS-Feed fer des Blatt',
'tooltip-feed-atom'              => 'Atom-Feed fer des Blatt',
'tooltip-t-contributions'        => 'Lischt von Ardickele vun dem Yuuser zeige',
'tooltip-t-emailuser'            => 'Dem Yuuser e E-Poschd schicke',
'tooltip-t-upload'               => 'Feils nuffdraage',
'tooltip-t-specialpages'         => 'Lischt vun alle besunnere Bledder',
'tooltip-t-print'                => 'Des Blatt fer zum Drucke',
'tooltip-t-permalink'            => 'En permanent Gleecher zu derre Version vun dem Blatt',
'tooltip-ca-nstab-main'          => 'Inhalt vun dem Blatt aagucke',
'tooltip-ca-nstab-user'          => 'Yuuserblatt zeige',
'tooltip-ca-nstab-special'       => 'Sell iss en besunneres Blatt. Du kannscht es Blatt net ennere.',
'tooltip-ca-nstab-image'         => 'Feil zeige',
'tooltip-ca-nstab-template'      => 'Moddel aagucke',
'tooltip-save'                   => 'Enneringe beilege',

# Attribution
'siteuser'  => '{{SITENAME}}-Yuuser $1',
'others'    => 'annere',
'siteusers' => '{{SITENAME}}-{{PLURAL:$2|Yuuser|Yuuser}} $1',

# Info page
'pageinfo-header-edits'     => 'Enneringe',
'pageinfo-header-watchlist' => 'Watsch-Lischt',
'pageinfo-subjectpage'      => 'Blatt',
'pageinfo-talkpage'         => 'Gschwetz-Blatt',

# Browsing diffs
'nextdiff' => 'Zum neegschte Versionsunnerschidd →',

# Media information
'widthheightpage' => '$1 × $2, {{PLURAL:$3|1 Blatt|$3 Bledder}}',
'file-info-size'  => '$1 × $2 Pixel, Daadegrees: $3, MIME-Typ: $4',

# Special:NewFiles
'showhidebots' => '(Bots $1)',
'noimages'     => 'Keene Feils gfunne.',
'ilsubmit'     => 'Guck uff',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 Sekund|$1 Sekunde}}',
'minutes' => '{{PLURAL:$1|$1 Minudd|$1 Minudde}}',
'hours'   => '{{PLURAL:$1|ee Schtund|$1 Schtunde}}',
'days'    => '{{PLURAL:$1|een Daag|$1 Daag}}',
'ago'     => 'vor $1',

# Metadata
'metadata' => 'Metadaade',

# EXIF tags
'exif-imagelength'  => 'Leng',
'exif-software'     => 'Geyuust Software',
'exif-usercomment'  => 'Anmaerrickinge vun Yuuser',
'exif-gpsaltitude'  => 'Heech',
'exif-writer'       => 'Schreiwer',
'exif-languagecode' => 'Schprooch',

'exif-subjectdistance-value' => '$1 Meter',

'exif-meteringmode-255' => 'Naett bekannt',

'exif-gaincontrol-0' => 'Ken',

'exif-iimcategory-sci' => 'Wisseschaft unn Waerkzeichheet‎',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'all',
'namespacesall' => 'all',
'monthsall'     => 'all',
'limitall'      => 'all',

# Scary transclusion
'scarytranscludetoolong' => '[URL iss zu lang]',

# action=purge
'confirm_purge_button' => 'OK',

# action=watch/unwatch
'confirm-watch-button'   => 'OK',
'confirm-unwatch-button' => 'OK',

# Separators for various lists, etc.
'ellipsis' => '…',
'percent'  => '$1&nbsp;%',

# Multipage image navigation
'imgmultipageprev' => '← letscht Blatt',
'imgmultipagenext' => 'neegschtes Blatt →',
'imgmultigo'       => 'OK',
'imgmultigoto'     => 'Geh zu Blatt $1',

# Table pager
'ascending_abbrev'         => 'uff',
'descending_abbrev'        => 'ab',
'table_pager_next'         => 'Neegschtes Blatt',
'table_pager_prev'         => 'Letscht Blatt',
'table_pager_first'        => 'Erschtes Blatt',
'table_pager_last'         => 'Letscht Blatt',
'table_pager_limit_submit' => 'Geh los',

# Auto-summaries
'autosumm-blank' => 'Des Blatt iss leer gmacht worre.',
'autosumm-new'   => 'Des Blatt is gschtaert warre: „$1“',

# Live preview
'livepreview-loading' => 'Laade…',

# Watchlist editor
'watchlistedit-normal-title' => 'Watsch-Lischt ennere',

# Special:Version
'version'                  => 'Version',
'version-specialpages'     => 'Besunnere Bledder',
'version-other'            => 'Anneres',
'version-mediahandlers'    => 'Media-Haendlers',
'version-version'          => '(Version $1)',
'version-poweredby-others' => 'annere',
'version-software-version' => 'Version',

# Special:FilePath
'filepath'        => 'Feilpaad',
'filepath-page'   => 'Feil:',
'filepath-submit' => 'Geh',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Feilnaame:',
'fileduplicatesearch-submit'   => 'Uffgucke',

# Special:SpecialPages
'specialpages'                 => 'Besunnere Bledder',
'specialpages-group-other'     => 'Annere besunnere Bledder',
'specialpages-group-login'     => 'Kumm nei',
'specialpages-group-users'     => 'Yuuser unn Rechte',
'specialpages-group-pages'     => 'Lischde vun Bledder',
'specialpages-group-pagetools' => 'Gscharr fer Bledder',
'specialpages-group-redirects' => 'Besunnere Bledder wu weiderleide',
'specialpages-group-spam'      => 'Spam-Gscharr',

# Special:BlankPage
'blankpage' => 'Leeres Blatt',

# Special:Tags
'tags-edit'     => 'ennere',
'tags-hitcount' => '$1 {{PLURAL:$1|Ennering|Enneringe}}',

# Special:ComparePages
'compare-page1' => 'Blatt 1',
'compare-page2' => 'Blatt 2',

# HTML forms
'htmlform-reset'               => 'Enneringe losmache',
'htmlform-selectorother-other' => 'Annere',

# Feedback
'feedback-message' => 'Melding:',

);
