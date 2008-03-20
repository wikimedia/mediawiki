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
 * @author Siebrand
 * @author NJ
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
# User preference toggles
'tog-underline'               => 'Lienks onderstreepn:',
'tog-highlightbroken'         => 'Lienks nae lehe pagina\'s <a href="" class="new">zò weerheven</a> (alternatief: zò weerheven<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Paragraeven uutvullen',
'tog-hideminor'               => 'Kleine wijzigingen verbergen in recente wijzigingen',
'tog-extendwatchlist'         => 'Uutebreide volglieste',
'tog-usenewrc'                => 'Uutebreide lèste wiezigiengen-pagina gebruken (JavaScript vereist)',
'tog-numberheadings'          => 'Koppn automaotisch nummern',
'tog-showtoolbar'             => 'Bewerkiengswerkbalke weerheven (JavaScript vereist)',
'tog-editondblclick'          => 'Dubbelklikkn voe bewerkn (JavaScript vereist)',
'tog-editsection'             => "Bewerken van deêlpahina's meuhlijk maeken via [bewerken]-koppeliengen",
'tog-editsectiononrightclick' => "Bewerken van deêlpahina's meulijk maeken mie een rechtermuusklik op een tussenkopje (JavaScript vereist)",
'tog-showtoc'                 => "Inoudsopgaeve weerheven (voe pahina's mie minstes 3 tussenkopjes)",
'tog-rememberpassword'        => 'Wachtwoôrd ontouwe',
'tog-editwidth'               => 'Bewerkiengsveld over de volle breêdte',
'tog-watchcreations'          => "Pahina's die ak anmik automaotisch volhen",
'tog-watchdefault'            => "Pahina's die ak bewerk automaotisch volhen",
'tog-watchmoves'              => "Pahina's die ak verplekke automaotisch volhen",
'tog-watchdeletion'           => "Pahina's die ak verwieder automaotisch volhen",
'tog-minordefault'            => "Al mien bewerkiengen as 'kleine' markeern",
'tog-previewontop'            => 'Voevertoônienge boven bewerkiengsveld weerheven',
'tog-previewonfirst'          => 'Voevertoônienge bie eêste bewerkieng weerheven',
'tog-nocache'                 => 'Hin caching gebruken',
'tog-enotifwatchlistpages'    => "E-mail me bie bewerkiengen van pagina's op men volglieste",
'tog-enotifusertalkpages'     => 'E-mail me wunnir a iemand men overlegpagina wiezig',
'tog-enotifminoredits'        => "E-mail me bie kleine bewerkiengen van pahina's op men volglieste",
'tog-enotifrevealaddr'        => 'Men e-mailadres weerheven in e-mailberichen',
'tog-shownumberswatching'     => "'t Antal gebrukers weerheven 't a deêze pahina volg",
'tog-fancysig'                => 'Onderteêkenen zonder lienk nae gebrukerspagina',
'tog-externaleditor'          => 'Standard een externe tekstbewerker gebruken',
'tog-externaldiff'            => 'Standard een extern verheliekiengsprohramma gebruken',
'tog-showjumplinks'           => '“hi nae”-toehankelijkeidslienks inschaokelen',
'tog-uselivepreview'          => '“live voevertoônienge” gebruken (JavaScript vereist – experimenteêl)',
'tog-forceeditsummary'        => 'Heef me een meldieng bie een lehe saemenvattieng',
'tog-watchlisthideown'        => 'Eihen bewerkiengen op men volglieste verberhen',
'tog-watchlisthidebots'       => 'Botbewerkiengen op men volglieste verberhen',
'tog-watchlisthideminor'      => 'Kleine wiezigiengen op men volglieste verberhen',
'tog-ccmeonemails'            => 'Zen me een kopie van e-mails die ak nae aore gebrukers stuur',
'tog-diffonly'                => 'Pagina-inoud onder wiezigiengen nie weerheven',
'tog-showhiddencats'          => 'Verborhen categorieën weerheven',

'underline-always'  => 'Aoltied',
'underline-never'   => 'Nooit',
'underline-default' => 'Webbrowser-standard',

'skinpreview' => '(Voevertoônienge)',

# Dates
'sunday'        => 'zundag',
'monday'        => 'maendag',
'tuesday'       => 'diesendag',
'wednesday'     => 'weusdag',
'thursday'      => 'dunderdag',
'friday'        => 'vriedag',
'saturday'      => 'zaeterdag',
'sun'           => 'zun',
'mon'           => 'mae',
'tue'           => 'die',
'wed'           => 'weu',
'thu'           => 'dun',
'fri'           => 'vri',
'sat'           => 'zae',
'january'       => 'januaori',
'february'      => 'februaori',
'march'         => 'maert',
'april'         => 'april',
'may_long'      => 'meie',
'june'          => 'juni',
'july'          => 'juli',
'august'        => 'auhustus',
'september'     => 'september',
'october'       => 'oktober',
'november'      => 'november',
'december'      => 'december',
'january-gen'   => 'januaori',
'february-gen'  => 'februaori',
'march-gen'     => 'maert',
'april-gen'     => 'april',
'may-gen'       => 'meie',
'june-gen'      => 'juni',
'july-gen'      => 'juli',
'august-gen'    => 'oest',
'september-gen' => 'september',
'october-gen'   => 'oktober',
'november-gen'  => 'november',
'december-gen'  => 'december',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'mae',
'apr'           => 'apr',
'may'           => 'mei',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'auh',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'dec',

# Categories related messages
'categories'                     => 'Categorieën',
'categoriespagetext'             => 'De wiki eit de volgende categorieën.',
'pagecategories'                 => '{{PLURAL:$1|Categorie|Categorieën}}',
'category_header'                => 'Artikels in categorie "$1"',
'subcategories'                  => 'Ondercategorieën',
'category-media-header'          => 'Media in categorie "$1".',
'category-empty'                 => "''Deêze categorie bevat hin pahina’s of media.''",
'hidden-categories'              => 'Verborhen {{PLURAL:$1|categorie|categorieën}}',
'hidden-category-category'       => 'Verborhen categorieën', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Deêze categorie ei de volhende ondercategorie.|Deêze categorie ei de volhende {{PLURAL:$1|ondercategorie|$1 ondercategorieën}}, van een totaol van $2.}}',
'category-subcat-count-limited'  => 'Deêze categorie ei de volhende {{PLURAL:$1|ondercategorie|$1 ondercategorieën}}.',
'category-article-count'         => "{{PLURAL:$2|Deêze categorie bevat de volhende pahina.|Deêze categorie bevat de volhende {{PLURAL:$1|pahina|$1 pahina's}}, van in totaol $2.}}",
'category-article-count-limited' => "Deêze categorie bevat de volhende {{PLURAL:$1|pahina|$1 pahina's}}.",
'category-file-count'            => "{{PLURAL:$2|Deêze categorie bevat 't volhende bestand.|Deêze categorie bevat {{PLURAL:$1|'t volhende bestand|de volgende $1 bestan'n}}, van in totaol $2.}}",
'category-file-count-limited'    => "Deêze categorie bevat {{PLURAL:$1|'t volhende bestand|de volhende $1 bestan'n}}.",
'listingcontinuesabbrev'         => 'vedder',

'mainpagetext'      => "<big>'''De installaotie van MediaWiki is geslaegd.'''</big>",
'mainpagedocfooter' => "Raedpleeg de [http://meta.wikimedia.org/wiki/ZEA_Ulpe:Inhoudsopgaeve andleidieng] voe informatie over 't gebruuk van de wikisoftware.

== Meer ulpe over MediaWiki ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lieste mie instelliengen]
* [http://www.mediawiki.org/wiki/Manual:FAQ Veehestelde vraehen (FAQ)]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Mailienglieste voe ankondigiengen van nieuwe versies]",

'about'          => 'Info',
'article'        => 'Artikel',
'newwindow'      => '(opent een nieuw scherm)',
'cancel'         => 'Afbreke',
'qbfind'         => 'Zoeken',
'qbbrowse'       => 'Blaeren',
'qbedit'         => 'Bewerk',
'qbpageoptions'  => 'Paginaopties',
'qbpageinfo'     => 'Pagina-informaotie',
'qbmyoptions'    => 'Mien opties',
'qbspecialpages' => 'Speciaole pahina’s',
'moredotdotdot'  => 'Meêr …',
'mypage'         => 'Mien gebrukerspagina',
'mytalk'         => 'Mien overleg',
'anontalk'       => 'Discussie vò dit IP-adres',
'navigation'     => 'Navigaotie',
'and'            => 'en',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Fout',
'returnto'          => 'Trug nae $1.',
'tagline'           => 'Uut {{SITENAME}}',
'help'              => 'Ulpe',
'search'            => 'Zoek',
'searchbutton'      => 'Zoek',
'go'                => 'OK',
'searcharticle'     => 'Bladzie',
'history'           => 'Paginaheschiedenisse',
'history_short'     => 'Geschiedenisse',
'updatedmarker'     => 'bewerkt sins men lèste bezoek',
'info_short'        => 'Informaotie',
'printableversion'  => 'Printbaere versie',
'permalink'         => 'Permanente lienk',
'print'             => "Print'n",
'edit'              => 'Bewerken',
'create'            => 'Anmaeken',
'editthispage'      => 'Deêze bladzie bewerken',
'create-this-page'  => 'Deêze pahina anmaeken',
'delete'            => 'Wissen',
'deletethispage'    => 'Wis deêze bladzie',
'undelete_short'    => '$1 {{PLURAL:$1|bewerkieng|bewerkiengen}} terugzetten',
'protect'           => 'Bescherm',
'protect_change'    => 'beveiligiengsstaotus wiezigen',
'protectthispage'   => 'Bescherm deêze bladzie',
'unprotect'         => 'Beveiligieng opheffen',
'unprotectthispage' => 'Beveiligieng van deêze pagina opheffen',
'newpage'           => 'Nieuwe pahina',
'talkpage'          => 'Overlegpagina',
'talkpagelinktext'  => 'Overleg',
'specialpage'       => 'Speciaole bladzie',
'personaltools'     => 'Persoônlijke instelliengen',
'postcomment'       => 'Opmerkieng toevoehen',
'articlepage'       => "Bekiek 't artikel",
'talk'              => 'Overleg',
'views'             => 'Acties',
'toolbox'           => 'Ulpmiddels',
'userpage'          => 'Bekiek gebrukersbladzie',
'projectpage'       => 'Bekiek projectbladzie',
'imagepage'         => 'Mediabestandspahina bekieken',
'mediawikipage'     => 'Berichenpagina bekieken',
'templatepage'      => 'Sjabloonpagina bekieken',
'viewhelppage'      => 'Ulppagina bekieken',
'categorypage'      => 'Beziet de categoriebladzie.',
'viewtalkpage'      => 'Overlegpagina bekieken',
'otherlanguages'    => 'In aore taelen',
'redirectedfrom'    => '(Deurverwezen vanaf $1)',
'redirectpagesub'   => 'Deurverwiespagina',
'lastmodifiedat'    => "Deêze bladzie is vò 't lèst bewerkt op $1 om $2.", # $1 date, $2 time
'viewcount'         => 'Deêze pagina is {{PLURAL:$1|1 keêr|$1 keêr}} bekeken.',
'protectedpage'     => 'Beschermde bladzie',
'jumpto'            => 'Hi nae:',
'jumptonavigation'  => 'navigaotie',
'jumptosearch'      => 'zoeken',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Over {{SITENAME}}',
'aboutpage'         => 'Project:Info',
'bugreports'        => "Foutrapport'n",
'bugreportspage'    => "Project:Foutrapport'n",
'copyright'         => 'Den inoud is beschikbaer onder de $1.',
'copyrightpagename' => '{{SITENAME}} auteursrechen',
'copyrightpage'     => '{{ns:project}}:Auteursrechen',
'currentevents'     => "In 't nieuws",
'currentevents-url' => "Project:In 't nieuws",
'disclaimers'       => 'Voebehoud',
'disclaimerpage'    => 'Project:Alhemeên voebehoud',
'edithelp'          => "Ulpe bie't bewerken",
'edithelppage'      => 'Ulpe:Bewerken',
'faq'               => 'FAQ (veehestelde vraehen)',
'faqpage'           => 'Project:Veehestelde vraehen',
'helppage'          => 'Ulpe:Inoud',
'mainpage'          => 'Vòblad',
'policy-url'        => 'Project:Beleid',
'portal'            => "'t Durpsuus",
'portal-url'        => 'Project:Durpsuus',
'privacy'           => 'Privacybeleid',
'privacypage'       => 'Project:Privacybeleid',
'sitesupport'       => 'Donaoties',
'sitesupport-url'   => 'Project:Financieel biedraehen',

'badaccess'        => 'Fout in toegangsrechten',
'badaccess-group0' => 'Jie mag de opgevraegde actie nie zelf uutvoere.',
'badaccess-group1' => 'De actie die-a je opgevrogen ei is gerizzerveerd vo gebrukers uut de groep van $1.',
'badaccess-group2' => 'De actie die-a je opgevroge ei is gerizzerveerd vò gebrukers uut de groepen $1.',
'badaccess-groups' => 'De actie die-a je opgevroge ei is gerizzerveerd vò gebrukers uut de groepen $1.',

'versionrequired'     => 'Versie $1 van MediaWiki is vereist',
'versionrequiredtext' => 'Versie $1 van MediaWiki is vereist om deêze pahina te gebruken. Meêr info is beschikbaer op de pahina [[Special:Version|softwareversie]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Truggeplekt van "$1"',
'youhavenewmessages'      => 'Je ei $1 ($2).',
'newmessageslink'         => 'nieuw bericht',
'newmessagesdifflink'     => 'de bewerkieng bekieken',
'youhavenewmessagesmulti' => 'Je ei nieuwe berichen op $1',
'editsection'             => 'bewerken',
'editold'                 => 'bewerk',
'editsectionhint'         => 'Deêlpahina bewerken: $1',
'toc'                     => "In'oud",
'showtoc'                 => 'uutklappe',
'hidetoc'                 => 'inklappe',
'thisisdeleted'           => '$1 bekieken of trugplekken?',
'viewdeleted'             => '$1 bekieken?',
'restorelink'             => '$1 verwiederde {{PLURAL:$1|versie|versies}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Feedtype wor nie ondersteund.',
'feed-unavailable'        => 'Syndicaotiefeeds zien nie beschikbaer op {{SITENAME}}',
'site-rss-feed'           => '$1 RSS-feed',
'site-atom-feed'          => '$1 Atom-feed',
'page-rss-feed'           => '“$1” RSS-feed',
'page-atom-feed'          => '“$1” Atom-feed',
'red-link-title'          => '$1 (besti nog nie)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Bladzie',
'nstab-user'      => 'Gebruker',
'nstab-media'     => 'Mediapagina',
'nstab-special'   => 'Speciaol',
'nstab-project'   => 'Projectbladzie',
'nstab-image'     => 'Bestand',
'nstab-mediawiki' => 'Systeemtekst',
'nstab-template'  => 'Sjabloon',
'nstab-help'      => 'Ulp bladzie',
'nstab-category'  => 'Categorie',

# Main script and global functions
'nosuchaction'      => 'Opeheven handelieng besti nie',
'nosuchactiontext'  => 'Den opdracht in de URL wier nie herkend deur de wiki',
'nosuchspecialpage' => 'Deêze speciaole pagina besti nie',
'nospecialpagetext' => "<big>'''Je ei een onbestaende speciaole pagina opevrogen.'''</big>

Een lieste mie speciaole pagina’s sti op [[Special:Specialpages|speciaole pagina’s]].",

# General errors
'error'           => 'Fout',
'databaseerror'   => 'Databasefout',
'dberrortext'     => "Der is een syntaxisfout in 't databaseverzoek opetreeën.
Meuhlijk zit der een fout in de software.
't Lèste verzoek an de database was:
<blockquote><tt>$1</tt></blockquote>
vanuut de functie “<tt>$2</tt>”.
MySQL haf de foutmeldieng “<tt>$3: $4</tt>”.",
'dberrortextcl'   => "Der is een syntaxisfout in 't databaseverzoek opetreeën.
't Lèste verzoek an de database was:
“$1”
vanuut de functie “$2”.
MySQL haf de volhende foutmeldieng: “$3: $4”",
'noconnect'       => 'Sorry! De wiki ondervin technische moeilijkheden en kan de database nie bereiken. <br />
$1',
'nodb'            => 'Kon database $1 nie selecteren',
'cachederror'     => 'Deêze pahina is een kopie uut de cache en kan verouwerd zien.',
'laggedslavemode' => "Waerschuwieng: de pahina zou verouwerd kunn'n zien.",
'readonly'        => 'Database heblokkeerd',
'enterlockreason' => 'Heef een reeën op voe de blokkaode en heef op wunnir a die warschijnlijk wor opeheven',
'readonlytext'    => 'De database is heblokkeerd voe bewerkiengen, warschijnlijk voe rehulier databaseonderoud. Nae afrondieng wor de functionaliteit hersteld.

De beheêrder ei de volhende reeën opeheven: $1',
'missingarticle'  => "In de database is hin tekst anetroffen voe een pagina mie de naem “$1”.

Dit wor mistal veroôrzaekt deur 't volhen van een lienk in een verheliekiengs- of geschiedenispagina nae een pagina die a verwiederd is.

A dit nie 't heval is, dan è je een fout in de software evon'n.
Rapporteer dit asjeblieft an een beheêrder mie vermeldieng van de URL.",
'readonly_lag'    => 'De database is automaotisch verhrendeld terwijl an de onderheschikte databaseservers synhroniseren mie den oôdserver.',
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
'longpages'         => 'Langste bladzies',
'listusers'         => 'Gebrukerslieste',
'specialpages'      => 'Speciaole bladzies',
'newpages-username' => 'Gebrukersnaem:',
'ancientpages'      => 'Bladzies die-an lang nie bin angepast',
'move'              => 'Verschuuf',
'movethispage'      => 'Verschuuf deêze bladzie',

# Book sources
'booksources' => 'Bronnen vò boeken',

# Special:Log
'specialloguserlabel' => 'Gebruker:',
'alllogstext'         => "Saemengesteld overzicht van de wis-, bescherm-, blokkeer- en gebrukerslechtenlogboeken.
Je kan 't overzicht bepaelen deu 'n soôrte logboek, 'n gebrukersnaem of eên bladzie uut te kiezen.",

# Special:Allpages
'allpages'          => 'Aolle bladzies',
'alphaindexline'    => '$1 toet $2',
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
'move-page-legend' => 'Verschuuf bladzie',
'movearticle'      => 'Verschuuf bladzie',
'move-watch'       => 'Volg deêze bladzie',
'movepagebtn'      => 'Verschuuf bladzie',
'articleexists'    => "D'r bestaet al 'n bladzie mee dieën naem, of de naem
die-a je gekozen is is ongeldeg.
Kiest 'n aore naem.",
'1movedto2'        => '[[$1]] is verschove nae [[$2]]',
'1movedto2_redir'  => '[[$1]] is verschove nae [[$2]] over de deurverwiezienge',

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
