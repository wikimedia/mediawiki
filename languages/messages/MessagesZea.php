<?php
/** Zeeuws (Zeêuws)
 *
 * @addtogroup Language
 *
 * @author Rob Church <robchur@gmail.com>
 * @author SPQRobin
 * @author Steinbach
 * @author Troefkaart
 * @author Adnergje
 * @author SPQRobin
 * @author Siebrand
 */

$fallback = 'nl';

/**
 * Namespace names
 * (bug 8708)
 */
$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciaol',
	NS_MAIN             => '',
	NS_TALK             => 'Overleg',
	NS_USER             => 'Gebruker',
	NS_USER_TALK        => 'Overleg_gebruker',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => 'Overleg_$1',
	NS_IMAGE            => 'Plaetje',
	NS_IMAGE_TALK       => 'Overleg_plaetje',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Overleg_MediaWiki',
	NS_TEMPLATE         => 'Sjabloon',
	NS_TEMPLATE_TALK    => 'Overleg_sjabloon',
	NS_HELP             => 'Ulpe',
	NS_HELP_TALK        => 'Overleg_ulpe',
	NS_CATEGORY         => 'Categorie',
	NS_CATEGORY_TALK    => 'Overleg_categorie',
);

$messages = array(
# Dates
'sunday'       => 'zundag',
'monday'       => 'maendag',
'friday'       => 'vriedag',
'saturday'     => 'zaeterdag',
'sun'          => 'zun',
'mon'          => 'mae',
'tue'          => 'din',
'wed'          => 'woe',
'thu'          => 'don',
'fri'          => 'vri',
'sat'          => 'zae',
'january'      => 'januaori',
'february'     => 'februaori',
'march'        => 'maert',
'may_long'     => 'meie',
'august'       => 'oest',
'january-gen'  => 'januaori',
'february-gen' => 'februaori',
'march-gen'    => 'maert',
'may-gen'      => 'meie',
'august-gen'   => 'oest',
'aug'          => 'oes',

# Bits of text used by many pages
'category_header'       => 'Artikels in categorie "$1"',
'category-media-header' => 'Media in categorie "$1".',

'article'    => 'Artikel',
'newwindow'  => '(opent een nieuw scherm)',
'cancel'     => 'Afbreke',
'qbedit'     => 'Bewerk',
'mytalk'     => 'Mien overleg',
'anontalk'   => 'Discussie vò dit IP-adres',
'navigation' => 'Navigaotie',

'help'             => 'Ulpe',
'search'           => 'Zoek',
'searchbutton'     => 'Zoek',
'searcharticle'    => 'Bladzie',
'history_short'    => 'Geschiedenisse',
'printableversion' => 'Printbaere versie',
'edit'             => 'Bewerken',
'editthispage'     => 'Deêze bladzie bewerken',
'delete'           => 'Wissen',
'deletethispage'   => 'Wis deêze bladzie',
'protect'          => 'Bescherm',
'protectthispage'  => 'Bescherm deêze bladzie',
'talkpagelinktext' => 'Overleg',
'specialpage'      => 'Speciaole bladzie',
'articlepage'      => "Bekiek 't artikel",
'talk'             => 'Overleg',
'toolbox'          => 'Ulpmiddels',
'userpage'         => 'Bekiek gebrukersbladzie',
'projectpage'      => 'Bekiek projectbladzie',
'categorypage'     => 'Beziet de categoriebladzie.',
'otherlanguages'   => 'In aore taelen',
'lastmodifiedat'   => "Deêze bladzie is vò 't lèst bewerkt op $1 om $2.", # $1 date, $2 time
'protectedpage'    => 'Beschermde bladzie',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'edithelp'    => "Ulpe bie't bewerken",
'mainpage'    => 'Vòblad',
'portal'      => "'t Durpsuus",
'portal-url'  => 'Project:Durpsuus',
'sitesupport' => 'Donaoties',

'badaccess'        => 'Fout in toegangsrechten',
'badaccess-group0' => 'Jie mag de opgevraegde actie nie zelf uutvoere.',
'badaccess-group1' => 'De actie die-a je opgevrogen ei is gerizzerveerd vo gebrukers uut de groep van $1.',
'badaccess-group2' => 'De actie die-a je opgevroge ei is gerizzerveerd vò gebrukers uut de groepen $1.',
'badaccess-groups' => 'De actie die-a je opgevroge ei is gerizzerveerd vò gebrukers uut de groepen $1.',

'newmessageslink' => 'nieuw bericht',
'editsection'     => 'bewerken',
'editold'         => 'bewerk',
'toc'             => "In'oud",
'showtoc'         => 'uutklappe',
'hidetoc'         => 'inklappe',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Bladzie',
'nstab-user'      => 'Gebruker',
'nstab-special'   => 'Speciaol',
'nstab-project'   => 'Projectbladzie',
'nstab-mediawiki' => 'Systeemtekst',
'nstab-help'      => 'Ulp bladzie',

# General errors
'badarticleerror' => 'Dit kan mee deêze bladzie nie gedaen ore.',
'cannotdelete'    => "Kan de bladzie of 't bestand nie wisse. Misschien is 't a deu iemand aors gewist.",
'badtitle'        => 'Verkeerde titel',
'badtitletext'    => "De bladzie die-a je angevrogen ei was ongeldig, leeg, of fout gelinkt vanuut 'n aore wiki. Mischien stae d'r eên of meer teêkens in die-an nie in titels gebruukt kunne ore.",
'viewsource'      => 'brontekst bekieken',
'viewsourcefor'   => 'vò $1',

# Login and logout pages
'userlogin'                  => 'Anmelden / Inschrieven',
'badretype'                  => 'De wachtwoôrden die-a je ingegeven typ bin nie eênder.',
'username'                   => 'Gebrukersnaem:',
'badsig'                     => 'Ongeldege andteêkenienge; kiek de [[HTML]]-expressies nae.',
'badsiglength'               => 'Te lange naem; ie mag uut maximaol $1 letters bestae.',
'blocked-mailpassword'       => 'Jen IP-adres is geblokkeerd, en vò zoôlank as dat
diert kan je, om misbruuk te vorkommen, geên nieuw wachtwoord laete opstiere.',
'acct_creation_throttle_hit' => "J'ei al $1 gebrukers angemaekt. Meêr mag je d'r nie ebbe.",
'accountcreated'             => 'Gebruker angemaekt.',
'accountcreatedtext'         => 'De gebrukersnaem vò $1 is angemaekt.',
'loginlanguagelabel'         => 'Taele: $1',

# Edit page toolbar
'bold_sample' => 'Vette tekst',

# Edit pages
'summary'               => 'Saemenvatting',
'minoredit'             => 'Dit is een kleine wieziging',
'watchthis'             => 'Volg deêze bladzie',
'savearticle'           => 'Bewaer bladzie',
'showpreview'           => 'Naekieke',
'showdiff'              => 'Bekiek veranderiengen',
'anoneditwarning'       => "'''Waerschuwienge:''' Je bin nie angemolde. Je IP-adres komt in de bewerkiengsgeschiedenisse van deêze bladzie te staen.",
'blockedtitle'          => 'Gebruker is geblokkeerd',
'blockedoriginalsource' => "De brontekst van '''$1''' staet ieronder:",
'blockededitsource'     => "D'n tekst van '''joen biedragen''' an '''$1''' staet ieronder:",
'accmailtitle'          => 'Wachtwoord verstierd.',
'accmailtext'           => "'t Wachtwoord vò $1 is nae $2 opgestierd.",
'anontalkpagetext'      => "----''Dit is de overlegbladzie vò 'n anonieme gebruker die-a gin inlognaem eit of 'm nie gebruukt. Zien/eur IP-adres kan deu meêr as eên gebruker gebruukt ore. A je 'n bericht gekrege è dat-a dudelik nie an joe gericht is, ka je 't beste [[Speciaol:Userlogin|jen eige anmelde]] om zukke verwarrienge in 't vervolg te vòkommen.''",

# History pages
'viewpagelogs' => 'Bekiek de logboeken vò deêze bladzie',
'page_first'   => 'eêrste',
'page_last'    => 'lèste',

# Revision feed
'history-feed-title' => 'Bewerkiengsgeschiedenisse',

# Search results
'prevn'        => 'vorrege $1',
'viewprevnext' => 'Bekiek ($1) ($2) ($3).',

# Preferences page
'preferences'       => 'Vòkeuren',
'mypreferences'     => 'Mien vòkeuren',
'datetime'          => 'Daotum en tied',
'prefs-personal'    => 'Gebrukersprofiel',
'prefs-rc'          => 'Juust angepast',
'prefs-watchlist'   => 'Volglieste',
'prefs-misc'        => 'Rest',
'searchresultshead' => 'Zoek',
'allowemail'        => 'Laet e-mail van aore gebrukers toe.',

# Groups
'group-sysop'      => 'Opzichters',
'group-bureaucrat' => 'Amtenaers',

'group-sysop-member'      => 'Opzichter',
'group-bureaucrat-member' => 'Amtenaer',

# User rights log
'rightslog' => 'Gebrukersrechtenlogboek',

# Recent changes
'recentchanges'                  => 'Juust angepast',
'recentchangestext'              => 'Bekiek wat-a juust veranderd is op deêze wiki.',
'recentchanges-feed-description' => 'Bekiek wat-a juust veranderd is op deêze wiki.',
'rcnote'                         => "Ieronder stae de lèste '''$1''' wiezigingen die a in de lèste '''$2''' daegen gemaekt binne",
'rclistfrom'                     => 'Bekiek de wiezigingen sins $1',
'rcshowhideminor'                => '$1 kleine bewerkiengen',
'rcshowhideliu'                  => '$1 angemelde gebrukers',
'rcshowhideanons'                => '$1 anonieme gebrukers',
'rcshowhidemine'                 => '$1 mien bewerkiengen',
'rclinks'                        => 'Bekiek de lèste $1 wiezigingen in de lèste $2 daegen<br />$3',
'diff'                           => 'wiez',
'hide'                           => 'Verberge',

# Recent changes linked
'recentchangeslinked' => 'Gerelateerde bewerkiengen',

# Upload
'upload'            => 'Upload bestand',
'fileuploadsummary' => 'Zaemenvattienge:',
'badfilename'       => 'Bestandsnaem is veranderd nae "$1".',
'watchthisupload'   => 'Volg deêze bladzie',

# Random page
'randompage' => 'Bladzie op goed geluk',

'brokenredirectstext' => 'De volgende deuverwieziengen stiere deu nae bladzie die nie bestae:',

# Miscellaneous special pages
'nmembers'          => '$1 {{PLURAL:$1|bladzie|bladzies}}',
'allpages'          => 'Aolle bladzies',
'longpages'         => 'Langste bladzies',
'listusers'         => 'Gebrukerslieste',
'specialpages'      => 'Speciaole bladzies',
'newpages-username' => 'Gebrukersnaem:',
'ancientpages'      => 'Bladzies die-an lang nie bin angepast',
'move'              => 'Verschuuf',
'movethispage'      => 'Verschuuf deêze bladzie',

# Book sources
'booksources' => 'Bronnen vò boeken',

'categoriespagetext' => 'De wiki eit de volgende categorieën.',
'alphaindexline'     => '$1 toet $2',

# Special:Log
'specialloguserlabel' => 'Gebruker:',
'alllogstext'         => "Saemengesteld overzicht van de wis-, bescherm-, blokkeer- en gebrukerslechtenlogboeken.
Je kan 't overzicht bepaelen deu 'n soôrte logboek, 'n gebrukersnaem of eên bladzie uut te kiezen.",

# Special:Allpages
'nextpage'          => 'Volgende bladzie ($1)',
'allpagesfrom'      => 'Laet bladzies zieë vanaf:',
'allarticles'       => 'Aolle artikels',
'allinnamespace'    => 'Aolle bladzies uut de $1-naemruumte',
'allnotinnamespace' => 'Aolle bladzies (nie in de $1-naemruumte)',
'allpagesprev'      => 'Vorrege',
'allpagesprefix'    => "Laet bladzies zieë mee 't vovoegsel:",
'allpagesbadtitle'  => "D'n ingegeven bladzie-titel was ongeldeg of ao 'n interwiki-vòvoegsel. Meschien stae d'r eên of meer teêkens in die-an nie in titels gebruukt ore kunne.",

# E-mail user
'emailuser' => 'E-mail deêze gebruker',
'emailpage' => 'E-mail gebruker',

# Watchlist
'watchlist'            => 'Volglieste',
'mywatchlist'          => 'Mien volglieste',
'watchlistfor'         => "(vò '''$1''')",
'watchnologin'         => 'Je bin nie angemolde.',
'watchnologintext'     => 'Je moe [[Speciaol:Userlogin|angemolde]] weze om je volglieste an te passen.',
'addedwatch'           => 'An de volglieste toegevoegd',
'addedwatchtext'       => "De bladzie \"[[:\$1]]\" is an je [[Special:Watchlist|Volglieste]] toegevoegd.
Veranderiengen an deêze bladzie en de overlegbladzie die-a d'rbie oort zulle ierop zichtbaer ore
en de bladzie komt '''vet''' te staen in de [[Special:Recentchanges|lieste van wat-a juust veranderd is]], daermee 't makkeliker te vinden is.
A je de bladzie laeter weêr van je volglieste afaele wil, klik dan op \"nie meêr volge\" bovenan de bladzie.",
'watch'                => 'Volg',
'watchthispage'        => 'Bekiek deêze bladzie',
'unwatch'              => 'Nie meêr volge',
'watchnochange'        => "D'r is in d'n opgevrogen tied niks op je volglieste veranderd.",
'watchlistcontains'    => 'Uw volglieste bevat $1 {{PLURAL:$1|bladzie|bladzies}}.',
'watchlist-hide-bots'  => 'Verberge bot wiezigingen',
'watchlist-hide-own'   => 'Verberge mien wiezigingen',
'watchlist-hide-minor' => 'Verberge kleine wiezigingen',

# Delete/protect/revert
'actioncomplete' => 'Actie uutgevoerd',
'deletedarticle' => 'wiste "[[$1]]"',
'dellogpage'     => 'Wislogboek',
'alreadyrolled'  => 'De lèste bewerkienge op [[$1]] deu [[User:$2|$2]] ([[User talk:$2|Overleggienge]]) kan nie vrommegedraoid ore; iemand aors eit de bladzie al bewerkt of ersteld.
De lèste bewerkienge wier gedaen deu [[User:$3|$3]] ([[User talk:$3|Overleggienge]]).',
'revertpage'     => 'Wiezigingen deur [[Special:Contributions/$2|$2]] ([[User talk:$2|Overleg]]) teruggedraoid nae de lèste versie van [[User:$1|$1]]', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'protectlogpage' => 'Beschermlogboek',

# Undelete
'cannotundelete' => 'Can de bladzie nie erstelle; mischien eit iemand aors de bladzie a vrommegezet.',

# Namespace form on various pages
'namespace'      => 'Naemruumte:',
'blanknamespace' => '(Artikels)',

# Contributions
'contributions' => 'Biedraegen gebruker',
'mycontris'     => 'Mien biedraegen',

# What links here
'whatlinkshere' => 'Links nae deze bladzie',

# Block/unblock
'blockip'                     => 'Blokkeer gebruker',
'badipaddress'                => 'Ongeldig IP-adres',
'blockipsuccesssub'           => 'Blokkaode is gelukt.',
'blockipsuccesstext'          => "[[{{ns:Special}}:Contributions/$1|$1]] is geblokkeerd.
<br />Ziet de [[{{ns:Special}}:Ipblocklist|IP-blokliest]] vo 'n overzicht van blokkaodes.",
'anononlyblock'               => 'alleêne anon.',
'blocklink'                   => 'blokkeer',
'contribslink'                => 'biedraegen',
'autoblocker'                 => 'Je bin automaotisch geblokkeerd om-at je IP-adres pas gebruukt is deu "[[Gebruker:$1|$1]]". De reje daevò was: "\'\'\'$2\'\'\'"',
'blocklogentry'               => 'ei "[[$1]]" geblokkeerd mee \'n afloôptied van $2 $3',
'blocklogtext'                => "Dit is 'n logboek van gebrukersblokkaodes en -deblokkeriengen. Automaotisch geblokte ip-adressen stae d'r nie bie. Ziet de [[Speciaol:Ipblocklist|Lieste van ip-blokkeriengen]] vò blokkaodes die op dit moment in werkienge bin.",
'block-log-flags-anononly'    => 'allene anonieme gebrukers',
'block-log-flags-nocreate'    => 'uutgeslote van anmaeken gebrukersnaemen',
'block-log-flags-noautoblock' => 'gin autoblokkaode',

# Move page
'move-page-legend'        => 'Verschuuf bladzie',
'movearticle'     => 'Verschuuf bladzie',
'move-watch'      => 'Volg deêze bladzie',
'movepagebtn'     => 'Verschuuf bladzie',
'articleexists'   => "D'r bestaet al 'n bladzie mee dieën naem, of de naem
die-a je gekozen is is ongeldeg.
Kiest 'n aore naem.",
'1movedto2'       => '[[$1]] is verschove nae [[$2]]',
'1movedto2_redir' => '[[$1]] is verschove nae [[$2]] over de deurverwiezienge',

# Namespace 8 related
'allmessagesname'           => 'Naem',
'allmessagesdefault'        => 'Standerttekst',
'allmessagescurrent'        => 'Tekst van noe',
'allmessagestext'           => "Dit is 'n liest van aolle systeemteksten die-an in de MediaWiki-naemruumte stae.",
'allmessagesnotsupportedDB' => "'''Special:Allmessages''' kan nie gebruukt ore om'at '''\$wgUseDatabaseMessages''' uutgeschaekeld staet.",
'allmessagesfilter'         => 'Zoek nae systeemteksten mee...',
'allmessagesmodified'       => 'Laet alleêne de veranderde teksten zieë',

# Tooltip help for the actions
'tooltip-pt-userpage'     => 'Mien gebrukersbladzie',
'tooltip-pt-mytalk'       => 'Mien overlegbladzie',
'tooltip-pt-preferences'  => 'Mien vòkeuren',
'tooltip-pt-watchlist'    => 'Lieste meê bladzies die op mien volglieste stae',
'tooltip-pt-mycontris'    => 'Mien biedraegen',
'tooltip-ca-delete'       => 'Wis deêze bladzie',
'tooltip-ca-move'         => 'Verschuuf deêze bladzie',
'tooltip-ca-watch'        => 'Voeg deêze bladzie an de volglieste toe',
'tooltip-p-logo'          => 'Vòblad',
'tooltip-n-mainpage'      => "Bekiek 't vòblad",
'tooltip-n-portal'        => "Praet en overleg in't Durpsuus",
'tooltip-n-recentchanges' => 'Bekiek wat-a juust veranderd is op deêze wiki',
'tooltip-watch'           => 'Voeg deêze bladzie toe an de volglieste',

# Spam protection
'categoryarticlecount'   => "D'r {{PLURAL:$1|is eên artikel|bin $1 artikels}} in deze catgeorie.",
'category-media-count'   => "D'r {{PLURAL:$1|is eên bestand|bin $1 bestanden}} in deze categorie.",
'listingcontinuesabbrev' => 'vedder',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'aol',
'namespacesall' => 'aol',

# AJAX search
'articletitles' => "Artikels die-an beginne mee ''$1''",

# Multipage image navigation
'imgmultipageprev' => '← vorrege bladzie',
'imgmultipagenext' => 'volgende bladzie →',

# Table pager
'ascending_abbrev' => 'opl',
'table_pager_next' => 'Volgende bladzie',
'table_pager_prev' => 'Vorrege bladzie',

# Auto-summaries
'autosumm-blank'   => 'Bladzie leeggemaekt',
'autosumm-replace' => "Bladzie vervange mee '$1'",
'autoredircomment' => 'Oor deugestierd nae [[$1]]',
'autosumm-new'     => 'Nieuwe bladzie mee as inoud: $1',

);
