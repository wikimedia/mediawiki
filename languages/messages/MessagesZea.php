<?php
/** Zeeuws (Zeêuws)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Adnergje
 * @author NJ
 * @author Ooswesthoesbes
 * @author Purodha
 * @author Rob Church <robchur@gmail.com>
 * @author Steinbach
 * @author Troefkaart
 * @author Urhixidur
 */

$fallback = 'nl';

/**
 * Namespace names
 * (bug 8708)
 */
$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciaol',
	NS_TALK             => 'Overleg',
	NS_USER             => 'Gebruker',
	NS_USER_TALK        => 'Overleg_gebruker',
	NS_PROJECT_TALK     => 'Overleg_$1',
	NS_FILE             => 'Plaetje',
	NS_FILE_TALK        => 'Overleg_plaetje',
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
'tog-hidepatrolled'           => "Gemarkeerde wiezigiengen verbarg'n in juust angepast",
'tog-newpageshidepatrolled'   => "Gemarkeerde pagina's verbarg'n in de lieste mei nuwe pagina's",
'tog-extendwatchlist'         => "Uutebreide volglieste hebruuk'n om aolle wiezigieng'n te bekiek'n, en nie alleêne de laeste",
'tog-usenewrc'                => 'Uutebreide lèste wiezigiengen-pagina gebruken (JavaScript vereist)',
'tog-numberheadings'          => 'Koppn automaotisch nummern',
'tog-showtoolbar'             => 'Bewerkiengswerkbalke weerheven (JavaScript vereist)',
'tog-editondblclick'          => 'Dubbelklikkn voe bewerkn (JavaScript vereist)',
'tog-editsection'             => "Bewerken van deêlpahina's meuhlijk maeken via [bewerken]-koppeliengen",
'tog-editsectiononrightclick' => "Bewerken van deêlpahina's meulijk maeken mie een rechtermuusklik op een tussenkopje (JavaScript vereist)",
'tog-showtoc'                 => "Inoudsopgaeve weerheven (voe pahina's mie minstes 3 tussenkopjes)",
'tog-rememberpassword'        => 'Anmeldhehevens ontouwen (maximaal $1 {{PLURAL:$1|dag|daege}})',
'tog-watchcreations'          => "Pahina's die ak anmik automaotisch volhen",
'tog-watchdefault'            => "Pahina's die ak bewerk automaotisch volhen",
'tog-watchmoves'              => "Pahina's die ak verplekke automaotisch volhen",
'tog-watchdeletion'           => "Pahina's die ak verwieder automaotisch volhen",
'tog-minordefault'            => "Al mien bewerkiengen as 'kleine' markeern",
'tog-previewontop'            => 'Voevertoônienge boven bewerkiengsveld weerheven',
'tog-previewonfirst'          => 'Voevertoônienge bie eêste bewerkieng weerheven',
'tog-nocache'                 => "Cach'n van pagina's deur de browser uutzett'n",
'tog-enotifwatchlistpages'    => "E-mail me bie bewerkiengen van pagina's op men volglieste",
'tog-enotifusertalkpages'     => 'E-mail me wunnir a iemand men overlegpagina wiezig',
'tog-enotifminoredits'        => "E-mail me bie kleine bewerkiengen van pahina's op men volglieste",
'tog-enotifrevealaddr'        => 'Men e-mailadres weerheven in e-mailberichen',
'tog-shownumberswatching'     => "'t Antal gebrukers weerheven 't a deêze pahina volg",
'tog-oldsig'                  => 'Bestaende onderteêkenienge',
'tog-fancysig'                => "As wikitekst behandel'n (zonder automaotische verwiezienge ni de gebrukersbladzie)",
'tog-externaleditor'          => 'Standard een externe tekstbewerker gebruken (alleêne vò experts - vò deêze functie ben speciaole ienstellienge nudig. [//www.mediawiki.org/wiki/Manual:External_editors Meê informaosie]).',
'tog-externaldiff'            => 'Standard een extern verheliekiengsprohramma gebruken (alleên vò experts - vò deêze functie ben speciaole ienstelliengen nudig. [//www.mediawiki.org/wiki/Manual:External_editors Meê informaosie]).',
'tog-showjumplinks'           => '“hi nae”-toehankelijkeidslienks inschaokelen',
'tog-uselivepreview'          => '“live voevertoônienge” gebruken (JavaScript vereist – experimenteêl)',
'tog-forceeditsummary'        => 'Heef me een meldieng bie een lehe saemenvattieng',
'tog-watchlisthideown'        => 'Eihen bewerkiengen op men volglieste verberhen',
'tog-watchlisthidebots'       => 'Botbewerkiengen op men volglieste verberhen',
'tog-watchlisthideminor'      => 'Kleine wiezigiengen op men volglieste verberhen',
'tog-watchlisthideliu'        => "Verbarg bewarkieng'n von anhemelde hebrukers op mien volglieste",
'tog-watchlisthideanons'      => "Verbarg bewarkieng'n von anonieme hebrukers op mien volglieste",
'tog-watchlisthidepatrolled'  => "Verbarg hemarkeerde bewarkieng'n op mien volglieste",
'tog-ccmeonemails'            => 'Zen me een kopie van e-mails die ak nae aore gebrukers stuur',
'tog-diffonly'                => 'Pagina-inoud onder wiezigiengen nie weerheven',
'tog-showhiddencats'          => 'Verborhen categorieën weerheven',
'tog-norollbackdiff'          => "Wiezigieng'n weglaet'n ni terugdraej'n",

'underline-always'  => 'Aoltied',
'underline-never'   => 'Nooit',
'underline-default' => 'Webbrowser-standard',

# Font style option in Special:Preferences
'editfont-style'     => 'Lettertypestiel bewarkiengsvenster:',
'editfont-default'   => 'Webbrowser-standard',
'editfont-monospace' => 'Monospaced lettertype',
'editfont-sansserif' => 'Sans-serif lettertype',
'editfont-serif'     => 'Serif lettertype',

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
'pagecategories'                 => '{{PLURAL:$1|Categorie|Categorieën}}',
'category_header'                => 'Artikels in categorie "$1"',
'subcategories'                  => 'Ondercategorieën',
'category-media-header'          => 'Media in categorie "$1".',
'category-empty'                 => "''Deêze categorie bevat hin pahina’s of media.''",
'hidden-categories'              => 'Verborhen {{PLURAL:$1|categorie|categorieën}}',
'hidden-category-category'       => 'Verborhen categorieën',
'category-subcat-count'          => '{{PLURAL:$2|Deêze categorie ei de volhende ondercategorie.|Deêze categorie ei de volhende {{PLURAL:$1|ondercategorie|$1 ondercategorieën}}, van een totaol van $2.}}',
'category-subcat-count-limited'  => 'Deêze categorie ei de volhende {{PLURAL:$1|ondercategorie|$1 ondercategorieën}}.',
'category-article-count'         => "{{PLURAL:$2|Deêze categorie bevat de volhende pahina.|Deêze categorie bevat de volhende {{PLURAL:$1|pahina|$1 pahina's}}, van in totaol $2.}}",
'category-article-count-limited' => "Deêze categorie bevat de volhende {{PLURAL:$1|pahina|$1 pahina's}}.",
'category-file-count'            => "{{PLURAL:$2|Deêze categorie bevat 't volhende bestand.|Deêze categorie bevat {{PLURAL:$1|'t volhende bestand|de volgende $1 bestan'n}}, van in totaol $2.}}",
'category-file-count-limited'    => "Deêze categorie bevat {{PLURAL:$1|'t volhende bestand|de volhende $1 bestan'n}}.",
'listingcontinuesabbrev'         => 'vedder',
'index-category'                 => "Te indexeren pagina's",
'noindex-category'               => "Niet te indexeren pagina's",
'broken-file-category'           => "Pagina's mei 'n onjuuste bestandsverwiezienge",

'about'         => 'Info',
'article'       => 'Artikel',
'newwindow'     => '(opent een nieuw scherm)',
'cancel'        => 'Afbreke',
'moredotdotdot' => 'Meêr …',
'mypage'        => 'Mien gebrukerspagina',
'mytalk'        => 'Mien overleg',
'anontalk'      => 'Discussie vò dit IP-adres',
'navigation'    => 'Navigaotie',
'and'           => '&#32;en',

# Cologne Blue skin
'qbfind'         => 'Zoeken',
'qbbrowse'       => 'Blaeren',
'qbedit'         => 'Bewerk',
'qbpageoptions'  => 'Paginaopties',
'qbpageinfo'     => 'Pagina-informaotie',
'qbmyoptions'    => 'Mien opties',
'qbspecialpages' => 'Speciaole pahina’s',
'faq'            => 'FAQ (veehestelde vraehen)',
'faqpage'        => 'Project:Veehestelde vraehen',

# Vector skin
'vector-action-addsection'       => 'Voeg kopje toe',
'vector-action-delete'           => 'Wissen',
'vector-action-move'             => 'Verschuuf',
'vector-action-protect'          => 'Bescherm',
'vector-action-undelete'         => 'Plaets truhhe',
'vector-action-unprotect'        => "Beveiligienge anpass'n",
'vector-simplesearch-preference' => "Verbetterde zoeksuggesties anzett'n (alleêne vò 't Vector uterlik)",
'vector-view-create'             => 'Anmaeken',
'vector-view-edit'               => 'Bewerk',
'vector-view-history'            => "Geschiedenisse bekiek'n",
'vector-view-view'               => 'Lezen',
'vector-view-viewsource'         => 'Brontekst bekieken',
'actions'                        => 'Handeliengen',
'namespaces'                     => 'Naemruumtes',
'variants'                       => 'Varianten',

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
'printableversion'  => 'Printbaere versie',
'permalink'         => 'Permanente lienk',
'print'             => "Print'n",
'view'              => 'Lezen',
'edit'              => 'Bewerken',
'create'            => 'Anmaeken',
'editthispage'      => 'Deêze bladzie bewerken',
'create-this-page'  => 'Deêze pahina anmaeken',
'delete'            => 'Wissen',
'deletethispage'    => 'Wis deêze bladzie',
'undelete_short'    => '$1 {{PLURAL:$1|bewerkieng|bewerkiengen}} terugzetten',
'viewdeleted_short' => '{{PLURAL: $1|Eên geschrapte bewarkienge|$1 geschrapte bewarkiengen}} bekieken',
'protect'           => 'Bescherm',
'protect_change'    => 'wiezigen',
'protectthispage'   => 'Bescherm deêze bladzie',
'unprotect'         => "Beveiligienge anpass'n",
'unprotectthispage' => "Beveiligieng van deêze pagina anpass'n",
'newpage'           => 'Nieuwe pahina',
'talkpage'          => 'Overlegpagina',
'talkpagelinktext'  => 'Overleg',
'specialpage'       => 'Speciaole bladzie',
'personaltools'     => 'Persoônlijke instelliengen',
'postcomment'       => 'Nuuw kopje toevoehen',
'articlepage'       => "Bekiek 't artikel",
'talk'              => 'Overleg',
'views'             => 'Acties',
'toolbox'           => 'Ulpmiddels',
'userpage'          => 'Bekiek gebrukersbladzie',
'projectpage'       => 'Bekiek projectbladzie',
'imagepage'         => "Bestandsbladzie bekiek'n",
'mediawikipage'     => 'Berichenpagina bekieken',
'templatepage'      => 'Sjabloonpagina bekieken',
'viewhelppage'      => 'Ulppagina bekieken',
'categorypage'      => 'Beziet de categoriebladzie.',
'viewtalkpage'      => 'Overlegpagina bekieken',
'otherlanguages'    => 'In aore taelen',
'redirectedfrom'    => '(Deurverwezen vanaf $1)',
'redirectpagesub'   => 'Deurverwiespagina',
'lastmodifiedat'    => "Deêze bladzie is vò 't lèst bewerkt op $1 om $2.",
'viewcount'         => 'Deêze pagina is {{PLURAL:$1|1 keêr|$1 keêr}} bekeken.',
'protectedpage'     => 'Beschermde bladzie',
'jumpto'            => 'Hi nae:',
'jumptonavigation'  => 'navigaotie',
'jumptosearch'      => 'zoeken',
'view-pool-error'   => "Sorry, de servers zij op 't moment overbelast.
Te vee gebrukers proberen deêze pagina te bekieken.
Wacht asseblief even vòdatjieu opnuuw toegang probeert te kriegen tot deêze pagina.

$1",
'pool-timeout'      => "De maximaol te wacht'n tied vò 't wacht'n op 'n lock is verstreek'n",
'pool-queuefull'    => 'De wachtrieë von de poel is vaol',
'pool-errorunknown' => "Er is 'n onbekande fout ophetreed'n",

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Over {{SITENAME}}',
'aboutpage'            => 'Project:Info',
'copyright'            => 'Den inoud is beschikbaer onder de $1.',
'copyrightpage'        => '{{ns:project}}:Auteursrechen',
'currentevents'        => "In 't nieuws",
'currentevents-url'    => "Project:In 't nieuws",
'disclaimers'          => 'Voebehoud',
'disclaimerpage'       => 'Project:Alhemeên voebehoud',
'edithelp'             => "Ulpe bie't bewerken",
'edithelppage'         => 'Help:Bewerken',
'helppage'             => 'Help:Inoud',
'mainpage'             => 'Vòblad',
'mainpage-description' => 'Vòblad',
'policy-url'           => 'Project:Beleid',
'portal'               => "'t Durpsuus",
'portal-url'           => 'Project:Durpsuus',
'privacy'              => 'Privacybeleid',
'privacypage'          => 'Project:Privacybeleid',

'badaccess'        => 'Fout in toegangsrechten',
'badaccess-group0' => 'Jie mag de opgevraegde actie nie zelf uutvoere.',
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
'viewsourceold'           => 'brontekst bekieken',
'editlink'                => 'bewerken',
'viewsourcelink'          => 'brontekst bekieken',
'editsectionhint'         => 'Deêlpahina bewerken: $1',
'toc'                     => "In'oud",
'showtoc'                 => 'uutklappe',
'hidetoc'                 => 'inklappe',
'thisisdeleted'           => '$1 bekieken of trugplekken?',
'viewdeleted'             => '$1 bekieken?',
'restorelink'             => '$1 verwiederde {{PLURAL:$1|versie|versies}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Feedtype wor nie ondersteund.',
'feed-unavailable'        => 'Syndicaotiefeeds zien nie beschikbaer',
'site-rss-feed'           => '$1 RSS-feed',
'site-atom-feed'          => '$1 Atom-feed',
'page-rss-feed'           => '“$1” RSS-feed',
'page-atom-feed'          => '“$1” Atom-feed',
'red-link-title'          => '$1 (de bladzie besti nie)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Bladzie',
'nstab-user'      => 'Gebruker',
'nstab-media'     => 'Mediapagina',
'nstab-special'   => 'Speciaole bladzie',
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
'nospecialpagetext' => '<strong>Je ei een onbestaende speciaole pagina opevrogen.</strong>

Een lieste mie speciaole pagina’s sti op [[Special:SpecialPages|speciaole pagina’s]].',

# General errors
'error'                => 'Fout',
'databaseerror'        => 'Databasefout',
'dberrortext'          => "Der is een syntaxisfout in 't databaseverzoek opetreeën.
Meuhlijk zit der een fout in de software.
't Lèste verzoek an de database was:
<blockquote><tt>$1</tt></blockquote>
vanuut de functie “<tt>$2</tt>”.
MySQL haf de foutmeldieng “<tt>$3: $4</tt>”.",
'dberrortextcl'        => "Der is een syntaxisfout in 't databaseverzoek opetreeën.
't Lèste verzoek an de database was:
“$1”
vanuut de functie “$2”.
MySQL haf de volhende foutmeldieng: “$3: $4”",
'laggedslavemode'      => "Waerschuwieng: de pahina zou verouwerd kunn'n zien.",
'readonly'             => 'Database heblokkeerd',
'enterlockreason'      => 'Heef een reeën op voe de blokkaode en heef op wunnir a die warschijnlijk wor opeheven',
'readonlytext'         => 'De database is heblokkeerd voe bewerkiengen, warschijnlijk voe rehulier databaseonderoud. Nae afrondieng wor de functionaliteit hersteld.

De beheêrder ei de volhende reeën opeheven: $1',
'missing-article'      => "In de database is gin inhoud angetroff'n vò de pagina \"\$1\" die er wè zow moeë weez'n (\$2).

Dit kan vòkomm'n as jie 'n veraoderde verwiezienge nir 't verschil tiss'n tweê versies von 'n pagina vogt of 'n versie opvraeg die is gewist.

As dit nie 't geval bin, hebbe jie wèlicht 'n fout in de software gevond'n.
Maek hiervon maldienge bie 'n [[Special:ListUsers/sysop|beheêrder]] von {{SITENAME}} en vermalt daerbie de URL von deze pagina.",
'missingarticle-rev'   => '(versienummer: $1)',
'readonly_lag'         => 'De database is automaotisch verhrendeld terwijl an de onderheschikte databaseservers synhroniseren mie den oôdserver.',
'internalerror'        => 'Interne fout',
'internalerror_info'   => 'Interne fout: $1',
'filecopyerror'        => 'Bestand “$1” kon nie ni “$2” ekopieerd worn.',
'filerenameerror'      => '“$1” kon nie tot “$2” hernoemd worn.',
'filedeleteerror'      => 'Bestand “$1” kon nie verwiederd worn.',
'directorycreateerror' => 'Map “$1” kon nie anemikt worn.',
'filenotfound'         => "Bestand “$1” wier nie evon'n.",
'fileexistserror'      => 'Schrieven ni bestand “$1” onmeuhlijk: bestand besti a',
'unexpected'           => 'Onverwachte waerde: "$1"="$2".',
'formerror'            => "Fout: formulier kon nie worn verzon'n",
'badarticleerror'      => 'Dit kan mee deêze bladzie nie gedaen ore.',
'cannotdelete'         => "Kan de bladzie of 't bestand nie wisse. Misschien is 't a deu iemand aors gewist.",
'badtitle'             => 'Verkeerde titel',
'badtitletext'         => "De bladzie die-a je angevrogen ei was ongeldig, leeg, of fout gelinkt vanuut 'n aore wiki. Mischien stae d'r eên of meer teêkens in die-an nie in titels gebruukt kunne ore.",
'perfcached'           => "De hehevens komm'n uut een cache en zien meuhlijk nie actueel.",
'perfcachedts'         => "De hehevens komm'n uut een cache en zien voe 't lèst biehewerkt op $1.",
'querypage-no-updates' => 'Deêze pagina kan nie biehewerkt worn. Deêze hehevens worn nie ververst.',
'wrong_wfQuery_params' => 'Foute parameters voe wfQuery()<br />
Functie: $1<br />
Zoekopdracht: $2',
'viewsource'           => 'brontekst bekieken',
'viewsourcefor'        => 'vò $1',
'actionthrottled'      => 'Handelienge tehenehouwen',
'actionthrottledtext'  => "As maetrehel tehen spam is 't antal keern per tiedseêneid da je deêze handelienge kan verrichen beperkt.
De limiet is overschreeën.
Probeer 't over een antal menuten wee.",
'protectedpagetext'    => 'Deêze pagina is beveiligd. Bewerken is nie meuhlijk.',
'viewsourcetext'       => 'Je kan de brontekst van deêze pagina bekieken en kopiëren:',
'protectedinterface'   => "Deêze pagina bevat tekst voe berichen van de software en is beveiligd om misbruuk te voorkomm'n.",
'editinginterface'     => "'''Waerschuwienge:''' Je bewerk een pagina die a gebruukt wor deur de software. Bewerkiengen op deêze pagina beïnvloeden de gebrukersinterface van iedereên. Overweeg voe vertaeliengen om [//translatewiki.net/wiki/Main_Page?setlang=zea translatewiki.net] te gebruken, 't vertaeliengsproject voe MediaWiki.",
'sqlhidden'            => '(SQL-zoekopdracht verborhen)',
'cascadeprotected'     => "Deêze pagina kan nie bewerkt worn, omda 't een is openomen in de volhende {{PLURAL:$1|pagina|pagina's}} die beveiligd {{PLURAL:$1|is|zien}} mie de cascaode-optie:
$2",
'namespaceprotected'   => "Je ei hin rechen om pagina's in de naemruumte '''$1''' te bewerken.",
'ns-specialprotected'  => 'Pagina\'s in de naemruumte "{{ns:special}}" kunn\'n nie bewerkt worn.',
'titleprotected'       => "'t Anmaeken van deêze pagina is beveiligd deur [[User:$1|$1]].
De heheven reeën is ''$2''.",

# Login and logout pages
'logouttext'                 => "'''Je bin noe ofemeld.'''

Je kan {{SITENAME}} noe anoniem gebruken of wee anmelden as dezelven of een aore gebruker.
Meuhlijk worn nog een antal pagina's weereheven asof a je anemeld bin totda je de cache van je browser leeg.",
'welcomecreation'            => '== Welkom, $1! ==
Jen account is anemikt.
Vergeet nie je [[Special:Preferences|vòkeuren voe {{SITENAME}}]] an te passen.',
'yourname'                   => 'Gebrukersnaem',
'yourpassword'               => 'Wachtwoôrd',
'yourpasswordagain'          => 'Heef je wachtwoôrd opnieuw in:',
'remembermypassword'         => 'Anmeldhehevens ontouwen (maximaal $1 {{PLURAL:$1|dag|daege}})',
'yourdomainname'             => 'Je domein:',
'externaldberror'            => "Der is een fout opetreeën bie 't anmelden bie de database of je ei hin toestemmieng jen externe gebruker bie te werken.",
'login'                      => 'Anmelden',
'nav-login-createaccount'    => 'Anmelden / Inschrieven',
'loginprompt'                => "Je mò cookies ineschaokeld ène om je te kunn'n anmelden bie {{SITENAME}}.",
'userlogin'                  => 'Anmelden / Inschrieven',
'logout'                     => 'Ofmelden',
'userlogout'                 => 'Ofmelden',
'notloggedin'                => 'Nie anemeld',
'nologin'                    => 'Nog hin gebrukersnaem? $1.',
'nologinlink'                => 'Mik een gebruker an',
'createaccount'              => 'Gebruker anmaeken',
'gotaccount'                 => "È je a een gebrukersnaem? '''$1'''.",
'gotaccountlink'             => 'Anmelden',
'userlogin-resetlink'        => "Ben jie je anmeldgegevens vergeet'n?",
'createaccountmail'          => 'per e-mail',
'badretype'                  => 'De wachtwoôrden die-a je ingegeven typ bin nie eênder.',
'userexists'                 => 'De hekozen gebrukersnaem is a in gebruuk.
Kies asjeblieft een aore naem.',
'loginerror'                 => 'Anmeldfout',
'nocookiesnew'               => "De gebruker is anemikt mè nie anemeld.
{{SITENAME}} gebruuk cookies voe 't anmelden van gebrukers.
Schaokel die asjeblieft in en meld dinae an mie je nieuwe gebrukersnaem en wachtwoôrd.",
'nocookieslogin'             => "{{SITENAME}} gebruuk cookies voe 't anmelden van gebrukers. Cookies zien uutgeschaokeld in je browser. Schaokel dezen optie asjeblieft an en probeer 't opnieuw.",
'noname'                     => 'Je ei hin heldihe gebrukersnaem opeheven.',
'loginsuccesstitle'          => 'Anmelden geslaegd',
'loginsuccess'               => "'''Je bin noe anemeld bie {{SITENAME}} as \"\$1\".'''",
'nosuchuser'                 => 'De gebruker "$1" besti nie.
Controleer de schriefwieze of [[Special:UserLogin/signup|mik een nieuwe gebruker an]].',
'nosuchusershort'            => 'De gebruker "$1" besti nie. Controleer de schriefwieze.',
'nouserspecified'            => 'Je dien een gebrukersnaem op te heven.',
'wrongpassword'              => "Wachtwoôrd onjuust. Probeer 't opnieuw.",
'wrongpasswordempty'         => "'t Opeheven wachtwoôrd was leeg. Probeer 't opnieuw.",
'passwordtooshort'           => "Je wachtwoôrd is te kort.
't Mò minstens uut $1 {{PLURAL:$1|teêken|teêkens}} bestaene.",
'mailmypassword'             => 'E-mail nuuw wachtwoôrd',
'passwordremindertitle'      => 'Nieuw tiedelijk wachtwoôrd voe {{SITENAME}}',
'passwordremindertext'       => 'Iemand, warschienlijk jie, ei vanof \'t IP-adres $1 een verzoek edaene toet \'t toezen\'n van \'t wachtwoôrd voe {{SITENAME}} ($4).
\'t Wachtwoôrd voe gebruker "$2" is "$3".
Mel je noe an en wiezig dan je wachtwoôrd.

A iemand aors dan jie dit verzoek ei edaene of a je ondertussen \'t wachtwoôrd wee weet en \'t nie langer wil wiezigen, neheer dan dit bericht en bluuf je bestaende wachtwoôrd gebruken.',
'noemail'                    => 'Der is hin e-mailadres bekend van gebruker "$1".',
'passwordsent'               => "'t Wachtwoôrd is verzon'n nae 't e-mailadres voe \"\$1\".
Mel je asjeblieft an naeda je 't ei ontvangen.",
'blocked-mailpassword'       => 'Jen IP-adres is geblokkeerd, en vò zoôlank as dat
diert kan je, om misbruuk te vorkommen, geên nieuw wachtwoord laete opstiere.',
'eauthentsent'               => "Der is een bevestigiengs-e-mail ni 't opeheven e-mailadres ezon'n. Volg de anwieziengen in de e-mail om an te heven dat 't joen e-mailadres is. Tot die tied kunn'n der hin e-mails ni 't e-mailadres ezon'n worn.",
'throttled-mailpassword'     => "Der is in de lèste $1 uur een wachtwoôrdherinnerienge verzon'n. Om misbruuk te vorkomm'n wor der slechs eên herinnerienge per $1 uur verzon'n.",
'mailerror'                  => "Fout bie 't verzen'n van e-mail: $1",
'acct_creation_throttle_hit' => "J'ei al $1 {{PLURAL:$1|gebruker|gebrukers}} angemaekt.
Meêr mag je d'r nie ebbe.",
'emailauthenticated'         => 'Jen e-mailadres is bevestigd op $1.',
'emailnotauthenticated'      => 'Jen e-mailadres is <strong>nie bevestigd</strong>. Je ontvang hin e-mail voe de onderstaende functies.',
'noemailprefs'               => 'Heef een e-mailadres op om deêze functies te gebruken.',
'emailconfirmlink'           => 'Bevestig jen e-mailadres',
'invalidemailaddress'        => "'t E-mailadres is nie anvaerd omda 't een onheldihe opmaek ei.
Heef asjeblieft een heldig e-mailadres op of lit 't veld leeg.",
'accountcreated'             => 'Gebruker angemaekt.',
'accountcreatedtext'         => 'De gebrukersnaem vò $1 is angemaekt.',
'createaccount-title'        => 'Gebrukers anmaeken voe {{SITENAME}}',
'createaccount-text'         => 'Iemand ei een gebruker op {{SITENAME}} ($4) anemikt mie de naem "$2" en joen e-mailadres. \'t Wachtwoôrd voe "$2" is "$3". Mel jen eihen an en wiezig je wachtwoôrd.

Neheer dit bericht as deêze gebruker zonder joe medeweten is anemikt.',
'loginlanguagelabel'         => 'Taele: $1',

# Change password dialog
'resetpass'           => "Wachtwoôrd herinstell'n",
'resetpass_announce'  => "Je bin anemeld mie een tiedelijke code die a je per e-mail is toe-ezon'n. Voer een nieuw wachtwoôrd in om 't anmelden te voltooien:",
'resetpass_header'    => "Wachtwoôrd herinstell'n",
'oldpassword'         => 'Udihe wachtwoôrd:',
'newpassword'         => 'Nieuw wachtwoôrd:',
'retypenew'           => 'Herhaolieng nieuwe wachtwoôrd:',
'resetpass_submit'    => "Wachtwoôrd instell'n en anmelden",
'resetpass_success'   => 'Je wachtwoord is ewiezigd. Bezig mie anmelden ...',
'resetpass_forbidden' => "Wachtwoôrden kunn'n op {{SITENAME}} nie ewiezigd worn",

# Edit page toolbar
'bold_sample'     => 'Vette tekst',
'bold_tip'        => 'Vet',
'italic_sample'   => 'Schuunhedrukte tekst',
'italic_tip'      => 'Schuun',
'link_sample'     => 'Onderwerp',
'link_tip'        => 'Interne lienk',
'extlink_sample'  => 'http://www.example.com lienktekst',
'extlink_tip'     => 'Externe lienk (verheet http:// nie)',
'headline_sample' => 'Deêlonderwerp',
'headline_tip'    => 'Tussenkopje (oôgste niveau)',
'nowiki_sample'   => 'Voer ier de nie op te maeken tekst in',
'nowiki_tip'      => 'Wiki-opmaek neheren',
'image_tip'       => 'Mediabestand',
'media_tip'       => 'Lienk ni bestand',
'sig_tip'         => "Jen 'andteêkenienge mie datum en tied",
'hr_tip'          => 'Horizontaele lien (gebruuk spaerzaem)',

# Edit pages
'summary'                          => 'Saemenvatting:',
'subject'                          => 'Onderwerp/kop:',
'minoredit'                        => 'Dit is een kleine wieziging',
'watchthis'                        => 'Volg deêze bladzie',
'savearticle'                      => 'Bewaer bladzie',
'preview'                          => 'Naekieken',
'showpreview'                      => 'Naekieke',
'showlivepreview'                  => 'Bewerkieng ter controle bekieken',
'showdiff'                         => 'Bekiek veranderiengen',
'anoneditwarning'                  => "'''Waerschuwienge:''' Je bin nie angemolde. Je IP-adres komt in de bewerkiengsgeschiedenisse van deêze bladzie te staen.",
'missingsummary'                   => "'''Herinnerieng:''' je ei hin saemenvattieng opeheven voe je bewerkieng. A je nog een keêr op ''Pagina opslaen'' klik wor de bewerkieng zonder saemenvattieng opeslogen.",
'missingcommenttext'               => 'Plek jen opmerkieng asjeblieft ieronder.',
'missingcommentheader'             => "'''Let op:''' Je ei hin onderwerp/kop voe deêze opmerkieng opeheven. A je opnieuw op \"opslaen\" klik, wor je wieziging zonder een onderwerp/kop opeslogen.",
'summary-preview'                  => 'Saemenvattieng naekieken:',
'subject-preview'                  => 'Naekieken onderwerp/kop:',
'blockedtitle'                     => 'Gebruker is geblokkeerd',
'blockedtext'                      => "'''Je gebruker of IP-adres is eblokkeerd.'''

De blokkaode is uutevoerd deur $1.
De opeheven reeën is ''$2''.

* Behun blokkaode: $8
* Ènde blokkaode: $6
* Bedoeld te blokkeren: $7

Je kan contact opnemen mie $1 of een aore [[{{MediaWiki:Grouppage-sysop}}|opzichter]] om de blokkaode te bespreken.
Je kan hin gebruuk maeken van de functie 'e-mail deêze gebruker', tenzie a je een heldig e-mailadres ei opeheven in je [[Special:Preferences|vòkeuren]] en 't gebruuk van deêze functie nie eblokkeerd is.
Je udihe IP-adres is $3 en 't blokkaodenummer is #$5. Vermeld beie hehevens a je erhens op deêze blokkaode wil reaheern.",
'autoblockedtext'                  => "Jen IP-adres is automaotisch eblokkeerd, omda 't is gebruukt deur een aore gebruker, die a is eblokkeerd deur $1.
De opeheven reeën is:

:''$2''

* Behun blokkaode: $8
* Ènde blokkaode: $6

Je kan deêze blokkaode bespreken mie $1 of een aore [[{{MediaWiki:Grouppage-sysop}}|opzichter]].
Je kan hin gebruuk maeken van de functie 'e-mail deêze gebruker', tenzie a je een heldig e-mailadres ei opeheven in je [[Special:Preferences|vòkeuren]] en 't gebruuk van deêze functie nie is eblokkeerd.
Je blokkaodenummer is #$5.
Vermeld dat a je erhens over deêze blokkaode wil reaheern.",
'blockednoreason'                  => 'hin reeën opeheven',
'blockedoriginalsource'            => "De brontekst van '''$1''' staet ieronder:",
'blockededitsource'                => "D'n tekst van '''joen biedragen''' an '''$1''' staet ieronder:",
'whitelistedittitle'               => 'Voe bewerken is anmelden verplicht',
'whitelistedittext'                => "Je mò $1 om pagina's te bewerken.",
'confirmedittext'                  => "Je mò jen e-mailadres bevestihen voe da je kan bewerken.
Voer jen e-mailadres in en bevestig 't via [[Special:Preferences|je vòkeuren]].",
'nosuchsectiontitle'               => 'Deêze subkop besti nie',
'nosuchsectiontext'                => 'Je probeern een subkop te bewerken die a nie besti.',
'loginreqtitle'                    => 'Anmelden verplicht',
'loginreqlink'                     => 'anmelden',
'loginreqpagetext'                 => "$1 is verplicht om aore pagina's te kunn'n ziene.",
'accmailtitle'                     => 'Wachtwoord verstierd.',
'accmailtext'                      => "'t Wachtwoord vò $1 is nae $2 opgestierd.",
'newarticle'                       => '(Nieuw)',
'newarticletext'                   => "Deêze pagina besti nie. Typ in 't onderstaende veld om de pagina an te maeken (meêr informatie sti op de [[{{MediaWiki:Helppage}}|ulppagina]]).
Gebruuk de knoppe '''vorige''' in je browser as je ier per ongeluk terecht bin ekomm'n.",
'anontalkpagetext'                 => "----''Dit is de overlegbladzie vò 'n anonieme gebruker die-a gin inlognaem eit of 'm nie gebruukt.
Zien/eur IP-adres kan deu meêr as eên gebruker gebruukt ore.
A je 'n bericht gekrege è dat-a dudelik nie an joe gericht is, ka je 't beste [[Special:UserLogin|jen eige anmelde]] om zukke verwarrienge in 't vervolg te vòkommen.''",
'noarticletext'                    => "Deêze pagina bevat hin tekst.
Je kan [[Special:Search/{{PAGENAME}}|ni deêze term zoeken]] in aore pagina's, <span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} de logboek'n deurzoek'n] of [{{fullurl:{{FULLPAGENAME}}|action=edit}} deêze pagina bewerken]</span>.",
'noarticletext-nopermission'       => 'Deêze pagina bevat hin tekst.
Jie kan [[Special:Search/{{PAGENAME}}|ni deêze term zoeken]] in aore pagina\'s of
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} de logboeken deurzoeken]</span>.',
'userpage-userdoesnotexist'        => 'Je bewerk een gebrukerspagina van een gebruker die a nie besti (gebruker "<nowiki>$1</nowiki>"). Controleer of a je deêze pagina wè wil anmaeken/bewerken.',
'clearyourcache'                   => '\'\'\'Let op!\'\'\' Leeg je cache naeda je de wiezigiengen ei opeslogen.

{| border="1" cellpadding="3" class=toccolours style="border: 1px #AAAAAA solid; border-collapse: collapse;"
| Mozilla/Safari/Konqueror || CTRL-SHIFT-R
|-
| IE || CTRL-F5
|-
| Opera || F5
|-
| Safari || CMD-R
|-
| Konqueror || F5
|}',
'usercssyoucanpreview'             => "'''Tip:''' Gebruuk de knoppe 'Naekieken' om je nieuwe CSS te tessen voe da je opsli.",
'userjsyoucanpreview'              => "'''Tip:''' Gebruuk de knoppe 'Naekieken' om je nieuwe JS te tessen voe da je opsli.",
'usercsspreview'                   => "'''Dit is alleên een voeôvertonieng van je persoônlijke CSS, dezen is nog nie opeslogen!'''",
'userjspreview'                    => "'''Let op: je test noe je persoônlijke JavaScript. De pagina is nie opeslogen!'''",
'userinvalidcssjstitle'            => "'''Waerschuwieng:''' der is hin skin \"\$1\". Let op: jen eihen .css- en .js-pagina's behunnen mie een kleine letter, buvobbeld {{ns:user}}:Naem/vector.css in plekke van {{ns:user}}:Naem/Vector.css.",
'updated'                          => '(Biehewerkt)',
'note'                             => "'''Opmerkieng:'''",
'previewnote'                      => "'''Let op: dit is een controlepagina; je tekst is nie opeslogen!'''",
'previewconflict'                  => "Deêze voevertoônieng heef an oe a de tekst in 't bovenste veld deruut zie a je die opsli.",
'session_fail_preview'             => "'''Sorry! Je bewerkieng is nie verwerkt, omdan de sessiehehevens verloorn zien ehaen.
Probeer 't opnieuw. A 't dan nog nie luk, mel jen eihen dan of en wee an.'''",
'session_fail_preview_html'        => "'''Sorry! Je bewerkieng is nie verwerkt, omdan sessiehehevens verloren zien ehaene.'''

''Omda in {{SITENAME}} ruwe HTML is ineschaokeld, is een voevertoônieng nie meuhlijk as beschermieng tehen anvall'n mie JavaScript.''

'''A dit een lehitieme bewerkieng is, probeer 't dan opnieuw. A 't dan nog nie luk, mel jen eihen dan of en wee an.'''",
'token_suffix_mismatch'            => "'''Je bewerkieng is eweiherd omda je browser de leesteêkens in 't bewerkiengstoken onjuust ei behandeld.
De bewerkieng is eweiherd om vermienkieng van de paginatekst te voorkomm'n.
Dit gebeur soms a der een webhebaseerde proxydienst wor gebruukt die a fout'n bevat.'''",
'editing'                          => 'Bezig mie bewerken van $1',
'editingsection'                   => 'Bezig mie bewerken van $1 (deêlpagina)',
'editingcomment'                   => 'Bezig mie bewerken van $1 (opmerkieng)',
'editconflict'                     => 'Bewerkiengsconflict: $1',
'explainconflict'                  => "Een aore gebruker ei deêze pagina bewerkt sins a je mie je bewerkieng bin behonnen.
In 't bovenste deêl van 't venster sti de tekst van de udihe pagina.
Je bewerkieng sti in 't onderste hedeêlte.
Je dien je bewerkiengen in te voehen in de bestaende tekst.
'''Alleên''' de tekst in 't bovenste hedeêlte wor opeslogen a je op \"{{int:savearticle}}\" klik.",
'yourtext'                         => 'Joe tekst',
'storedversion'                    => 'Opeslogen versie',
'nonunicodebrowser'                => "'''WAERSCHUWIENG: Je browser kan nie hoed overwig mie unicode.
Iermie wor deur de MediaWiki-software rekenienge ehouwen zoda je toch zonder probleemn pagina's kan bewerken: nie-ASCII karakters worn in 't bewerkiengsveld weereheven as hexadecimale codes.'''",
'editingold'                       => "'''WAARSCHUWING!
Je bewerk een ouwe versie van deêze pagina.
A je je bewerkieng opsli, haen aolle wiezigiengen die an nae deêze versie emikt zien verloorn.'''",
'yourdiff'                         => 'Wiezigiengen',
'copyrightwarning'                 => "Opelet: Aolle biedraegen an {{SITENAME}} worn eacht te zien vrie'eheven onder de $2 (zie $1 voe details).
A je nie wil da je tekst deur aore ni believen bewerkt en verspreid kan worn, kies dan nie voe 'Pagina Opslaen'.<br />
Ierbie beloof je ons ok da je deêze tekst zelf eschreven ei, of overenomen uut een vrieë, openbaere bron.<br />
'''GEBRUUK HIN MATERIAOL DAT A BESCHERMD WOR DEUR AUTEURSRECHT, TENZIE A JE DIVOE TOESTEMMIENG                   EI!'''",
'copyrightwarning2'                => "Al je biedraehen an {{SITENAME}} kunn'n bewerkt, ewiezigd of verwiederd worn deur aore gebrukers.
A je nie wil dan je teksen rihoreus anepast worn deur aore, plek ze ier dan nie.<br />
Je beloof ok da je de oôrspronkelijke auteur bin van dit materiaol, of da je 't ei ekopieerd uut een bron in 't publieke domein, of een soôrthelieke vrieë bron (zie $1 voor details).
'''GEBRUUK HIN MATERIAOL DAT A BESCHERMD WOR DEUR AUTEURSRECHT, TENZIE A JE DIVOE TOESTEMMIENG EI!'''",
'longpageerror'                    => "'''FOUT: de tekst die a je ei toe'evoegd is $1 kilobyte hroôt, wat a hrotter is dan 't maximum van $2 kilobyte.
Opslaene is nie meuhlijk.'''",
'readonlywarning'                  => "'''WAERSCHUWIENG: de database is eblokkeerd voe onderoud, dus je kan dezen noe nie opslaen.
't Is misschien verstandig om je tekst tiedelijk in een tekstbestand op te slaene om dit te bewaeren                    ve wunnir a de blokkerieng van de database opeheven is.'''",
'protectedpagewarning'             => "'''WAERSCHUWIENG! Deêze beveiligde pagina kan allin deur gebrukers mie beheêrdersrechten bewerkt        worn.'''",
'semiprotectedpagewarning'         => "'''Let op:''' deêze pagina is beveiligd en kan allaen deur herehistreerde gebrukers bewerkt worn.",
'cascadeprotectedwarning'          => "'''Waerschuwieng:''' Deêze pagina is beveiligd en kan allin deur beheêrders bewerkt worn, omda dezen is openomen in de volhende {{PLURAL:$1|pagina|pagina's}} die a beveiligd {{PLURAL:$1|is|zien}} mie de cascade-optie:",
'titleprotectedwarning'            => "'''WAERSCHUWIENG: Deêze pagina is beveiligd zodan allaen ienkele gebrukers 't kunn'n anmaeken.'''",
'templatesused'                    => "Op deêze pagina {{PLURAL:$1|gebruukt sjabloon|gebruukte sjabloon'n}}:",
'templatesusedpreview'             => "Sjabloon'n gebruukt in deêze voevertoônieng:",
'templatesusedsection'             => "Sjabloon'n die an gebruukt worn in deêze subkop:",
'template-protected'               => '(beveiligd)',
'template-semiprotected'           => '(semi-beveiligd)',
'hiddencategories'                 => 'Deêze pagina val in de volhende verborhen {{PLURAL:$1|categorie|categorieën}}:',
'nocreatetitle'                    => "'t Anmaeken van pagina's is beperkt",
'nocreatetext'                     => "{{SITENAME}} ei de meuhlijkeid om nieuwe pagina's an te maeken beperkt.
Je kan a bestaende pagina's wiezigen, of je kan [[Special:UserLogin|jen eihen anmelden of een gebruker  anmaeken]].",
'nocreate-loggedin'                => "Je kan hin nieuwe pagina's anmaeken.",
'permissionserrors'                => "Fout'n in rechen",
'permissionserrorstext'            => "Je ei hin rechen om dit te doene wehens de volhende {{PLURAL:$1|reeën|reden'n}}:",
'permissionserrorstext-withaction' => "Je ei hin rechen om $2 wehens de volhende {{PLURAL:$1|reeën|reden'n}}:",
'recreate-moveddeleted-warn'       => "'''Waerschuwieng: je bin bezig mie 't anmaeken van een pagina die a in 't verleeën verwiederd is.'''

Overweeg of a 't terecht is dat je vadder werk an deêze pagina. Voe je hemak sti ieronder 't verwiederiengslogboek van deêze pagina:",
'moveddeleted-notice'              => "Deêze pagina is gewist.
Ter informaosie wor'n 't verwiederiengslogboek en 't hernoemiengslogboek van deêze pagina hieronder weerheheven.",

# Parser/template warnings
'expensive-parserfunction-warning'        => "Waerschuwieng: deêze pagina gebruuk te vee kosbaere parserfuncties.

Noe zien 't et der $1, terwijl an 't der minder as $2 motten zien.",
'expensive-parserfunction-category'       => "Pagina's die an te vee kosbaere parserfuncties gebruken",
'post-expand-template-inclusion-warning'  => "Waerschiewienge: de maximaole transclusiegroôtte vò sjabloon'n is overschreed'n.
Sommige sjabloon'n worr'n nie getranscludeerd.",
'post-expand-template-inclusion-category' => "Pagina's wirvòr de maximaole transclusiegroôtte is overschreed'n",
'post-expand-template-argument-warning'   => "Waerschiewienge: deêze pagina bevat tenminste eên sjabloonparameter mei 'n te hroôte transclusiehroôtte. Deêze parameters zin weggelaet'n.",
'post-expand-template-argument-category'  => "Pagina's die missende sjabloonelement'n bevatt'n",

# "Undo" feature
'undo-success' => "Ieronder sti de tekst wirin a de wiezigieng onedaene is emikt. Controleer voe 't opslaene of a 't resultaot ewenst is.",
'undo-failure' => 'De wiezigieng kan nie onhedaen emikt worn vanwehe aore striedihe wiezigiengen.',
'undo-summary' => 'Versie $1 van [[Special:Contributions/$2|$2]] ([[User talk:$2|overleg]]) onedaen emikt.',

# Account creation failure
'cantcreateaccounttitle' => 'Anmaeken gebruker mislukt.',
'cantcreateaccount-text' => "'t Anmaeken van gebrukers van dit IP-adres (<b>$1</b>) is eblokkeerd deur [[User:$3|$3]].

De deur $3 opeheven reeën is ''$2''",

# History pages
'viewpagelogs'           => 'Bekiek de logboeken vò deêze bladzie',
'nohistory'              => 'Deêze pagina is nie bewerkt.',
'currentrev'             => 'Udihe versie',
'currentrev-asof'        => 'Hudige versie von $2 om $3',
'revisionasof'           => 'Versie op $1',
'revision-info'          => 'Versie op $1 van $2',
'previousrevision'       => '←Ouwere versie',
'nextrevision'           => 'Nieuwere versie→',
'currentrevisionlink'    => 'Udihe versie',
'cur'                    => 'udig',
'next'                   => 'volhende',
'last'                   => 'vorihe',
'page_first'             => 'eêrste',
'page_last'              => 'lèste',
'histlegend'             => "Selectie voe diff: sillecteer de te verheliek'n versies en toets ENTER of de knoppe onderan.<br />
Verklaerieng afkortiengen: (udig) = verschil mie udihe versie, (vorihe) = verschil mie voorhaende versie, k = kleine wiezigieng",
'history-fieldset-title' => "Dò geschiedenisse blaer'n",
'history-show-deleted'   => 'Alleên gewist',
'histfirst'              => 'Oussen',
'histlast'               => 'Nieuwsen',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(leeg)',

# Revision feed
'history-feed-title'          => 'Bewerkiengsgeschiedenisse',
'history-feed-description'    => 'Bewerkiengsoverzicht voe deêze pagina op de wiki',
'history-feed-item-nocomment' => '$1 op $2',
'history-feed-empty'          => "De evrogen pagina besti nie.
Misschien is een verwiederd of hernoemd.
[[Special:Search|Deurzoek de wiki]] voe rillevante pagina's.",

# Revision deletion
'rev-deleted-comment'         => '(opmerkieng verwiederd)',
'rev-deleted-user'            => '(gebruker verwiederd)',
'rev-deleted-event'           => '(logboekrehel verwiederd)',
'rev-deleted-text-permission' => "Deêze bewerkieng van de pagina is verwiederd uut de publieke archieven.
Der kunn'n details anwezig zien in 't [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}}verwiederiengslogboek].",
'rev-deleted-text-view'       => "Deêze bewerkieng van de pagina is verwiederd uut de publieke archieven.
As opzichter van {{SITENAME}} kan je dezen ziene;
der kunn'n details anwezig zien in 't [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} verwiederiengslogboek].",
'rev-delundel'                => 'weerheven/verberhen',
'revisiondelete'              => 'Versies verwiederen/trugplekken',
'revdelete-nooldid-title'     => 'Hin doelversie',
'revdelete-nooldid-text'      => 'Je ei hin doelversie(s) voe deêze handelienge opeheven, de aneheven versie besti nie, of je probeer de lèste versie te verberhen.',
'revdelete-selected'          => "'''Hesillecteerde {{PLURAL:$2|bewerkieng|bewerkiengen}} van [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Hesillecteerde logboekactie|Hesillecteerde logboekacties}}:'''",
'revdelete-text'              => "'''Verwiederde bewerkiengen zien zichbaer in de heschiedenisse, mè den inoud is nie langer publiek toehankelijk.'''

Aore opzichters van {{SITENAME}} kunn'n de verborhen inoud benadern en de verwiederieng onedaene maeken mie de ulpe van dit scherm, tenzie an der anvull'nde beperkiengen heln die an zien inesteld deur de systeembeheêrder.",
'revdelete-legend'            => "Zichbaereidsbeperkiengen instell'n",
'revdelete-hide-text'         => 'De bewerkte tekst verberhen',
'revdelete-hide-image'        => 'Bestandsinoud verberhen',
'revdelete-hide-name'         => 'Actie en doel verberhen',
'revdelete-hide-comment'      => 'De bewerkiengssaemenvattieng verberhen',
'revdelete-hide-user'         => 'Gebrukersnaem/IP van de gebruker verberhen',
'revdelete-hide-restricted'   => 'Deêze beperkiengen toepassen op opzichters en dezen interface ofsluten',
'revdelete-suppress'          => "Hehevens voe zòwè opzichters as aore onderdrukk'n",
'revdelete-unsuppress'        => 'Beperkiengen op terugezette wiezigiengen verwiederen',
'revdelete-log'               => 'Opmerkieng in logboek:',
'revdelete-submit'            => 'Toepassen op de hesillecteerde bewerkieng',
'revdelete-logentry'          => 'zichbaereid van bewerkiengen is ewiezigd voe [[$1]]',
'logdelete-logentry'          => 'wiezigen zichbaereid van hebeurtenis [[$1]]',
'revdelete-success'           => "'''Zichbaereid van de wiezigieng succesvol inesteld.'''",
'logdelete-success'           => "'''Zichbaereid van de hebeurtenisse succesvol inesteld.'''",
'revdel-restore'              => 'Zichbaereid wiezigen',
'revdel-restore-deleted'      => 'gewiste versies',
'revdel-restore-visible'      => 'zichtbaere versies',
'pagehist'                    => 'Paginaheschiedenisse',
'deletedhist'                 => 'Verwiederde heschiedenisse',
'revdelete-content'           => 'inoud',
'revdelete-summary'           => 'saemenvattieng bewerken',
'revdelete-uname'             => 'gebrukersnaem',
'revdelete-restricted'        => 'ei beperkiengen an beheêrders opeleid',
'revdelete-unrestricted'      => 'ei beperkiengen voe beheêrders opeheven',
'revdelete-hid'               => 'ei $1 verborhen',
'revdelete-unhid'             => 'ei $1 zichbaer emikt',
'revdelete-log-message'       => '$1 voe $2 {{PLURAL:$2|versie|versies}}',
'logdelete-log-message'       => '$1 voe $2 {{PLURAL:$2|logboekrehel|logboekrehels}}',

# Suppression log
'suppressionlog'     => 'Verberhiengslogboek',
'suppressionlogtext' => 'De onderstaende lieste bevat de miste recente verwiederiengen en blokkaodes die an voe opzichters verborhen zien. In de [[Special:IPBlockList|IP-blokkeerlieste]] zien de udihe blokkaodes te bekieken.',

# History merging
'mergehistory'                     => "Heschiedenisse van pagina's saemenvoehen",
'mergehistory-header'              => "Via deêze pagina kan je versies van de heschiedenisse van een bronpagina ni een nieuwere pagina saemenvoehen.
Zurg da je bie deêze wiezigieng de heschiedenisdeurloôpendeid van de pagina be'ouw.",
'mergehistory-box'                 => "Versies van twi pagina's saemenvoehen:",
'mergehistory-from'                => 'Bronpagina:',
'mergehistory-into'                => 'Bestemmiengspagina:',
'mergehistory-list'                => 'Saemenvoegbaere bewerkiengsheschiedenisse',
'mergehistory-merge'               => "De volhende versies van [[:$1]] kunn'n saemenevoegd worn ni [[:$2]]. Gebruuk de kolomme mie keuzerondjes om allaen de versies emikt op en voe de aneheven tied saemen te voehen. Let op da 't gebruken van de navigaotielienks deêze kolomme za herinstelln.",
'mergehistory-go'                  => 'Saemenvoegbaere bewerkiengen bekieken',
'mergehistory-submit'              => 'Versies saemenvoehen',
'mergehistory-empty'               => "Der zien hin versies die an saemenevoegd kunn'n worn.",
'mergehistory-success'             => '$3 {{PLURAL:$3|versie|versies}} van [[:$1]] zien succesvol saemenevoegd ni [[:$2]].',
'mergehistory-fail'                => 'Kan hin heschiedenisse saemenvoehen, controleer opnieuw de pagina- en tiedparameters.',
'mergehistory-no-source'           => 'Bronpagina $1 besti nie.',
'mergehistory-no-destination'      => 'Bestemmiengspagina $1 besti nie.',
'mergehistory-invalid-source'      => 'De bronpagina mò een heldihe titel zien.',
'mergehistory-invalid-destination' => 'De bestemmiengspagina mò een heldihe titel zien.',
'mergehistory-autocomment'         => '[[:$1]] saemenevoegd ni [[:$2]]',
'mergehistory-comment'             => '[[:$1]] saemenevoegd ni [[:$2]]: $3',

# Merge log
'mergelog'           => 'Saemenvoehiengslogboek',
'pagemerge-logentry' => 'voehen [[$1]] ni [[$2]] saemen (versies tot en met $3)',
'revertmerge'        => 'Saemenvoehieng onhedaen maeken',
'mergelogpagetext'   => 'Ieronder zie je een lieste van recente saemenvoehiengen van een paginaheschiedenisse ni een aorn.',

# Diffs
'history-title'           => 'Heschiedenisse van "$1"',
'difference'              => '(Verschil tussen bewerkiengen)',
'lineno'                  => 'Rehel $1:',
'compareselectedversions' => 'Anevienkte versies verhelieken',
'editundo'                => 'onedaene maeken',
'diff-multi'              => 'Von {{PLURAL:$2|eên gebruker|$2 gebrukers}} ({{PLURAL:$1|wor eên tussenlihhende versie|worn $1 tussenlihhende versies}} nie weereheven)',

# Search results
'searchresults'                    => 'Zoekresultaoten',
'searchresults-title'              => 'Zuikresultaot\'n vò "$1"',
'searchresulttext'                 => 'Voe meêr informaotie over zoeken op {{SITENAME}}, zie [[{{MediaWiki:Helppage}}|{{int:ulpe}}]].',
'searchsubtitle'                   => "Je zoch ni '''[[:$1]]'''",
'searchsubtitleinvalid'            => 'Voe zoekopdracht "$1"',
'toomanymatches'                   => 'Der waeren te vee resultaoten. Probeer asjeblieft een aore zoekopdracht.',
'titlematches'                     => 'Overeênkomst mie onderwerp',
'notitlematches'                   => "Hin resultaoten evon'n",
'textmatches'                      => 'Overeênkomst mie inoud',
'notextmatches'                    => "Hin pagina's evon'n",
'prevn'                            => 'vorrege {{PLURAL:$1|$1}}',
'nextn'                            => 'volhende {{PLURAL:$1|$1}}',
'prevn-title'                      => "Veurige {{PLURAL:$1|resultaot|$1 resultaot'n}}",
'nextn-title'                      => "Ouwere {{PLURAL:$1|resultaot|$1 resultaot'n}}",
'shown-title'                      => "$1 {{PLURAL:$1|resultaot|resultaot'n}} per pagina weêrgeven",
'viewprevnext'                     => 'Bekiek ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-exists'                => "'''Er is 'n pagina genaemd \"[[:\$1]]\" op deêze wiki.'''",
'searchmenu-new'                   => "'''De pagina \"[[:\$1]]\" anmaek'n op deêze wiki.'''",
'searchhelp-url'                   => 'Help:Inoud',
'searchprofile-articles'           => "Inhoudelike pagina's",
'searchprofile-project'            => "Hilp- en projectpagina's",
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Alles',
'searchprofile-advanced'           => 'Uutebreid',
'searchprofile-articles-tooltip'   => "Zoek'n in $1",
'searchprofile-project-tooltip'    => "Zoek'n in $1",
'searchprofile-images-tooltip'     => "Zoek nae bestand'n",
'searchprofile-everything-tooltip' => "Aolle inhoud dòzoek'n (inclusief overlegbladzies)",
'searchprofile-advanced-tooltip'   => "Zoek'n in angegeev'n naemruumtes",
'search-result-size'               => '$1 ({{PLURAL:$2|1 woôrd|$2 woôrn}})',
'search-result-category-size'      => '{{PLURAL:$1|1 categorielid|$1 categorieleden}} ({{PLURAL:$2|1 ondercategorie|$2 ondercategorieën}}, {{PLURAL:$3|1 bestand|$3 bestanden}})',
'search-result-score'              => 'Rillevantie: $1%',
'search-redirect'                  => '(deurverwiezieng $1)',
'search-section'                   => '(subkop $1)',
'search-suggest'                   => 'Bedoeln je: $1',
'searchrelated'                    => 'gerelateerd',
'searchall'                        => 'aolle',
'showingresults'                   => "Ieronder {{PLURAL:$1|sti '''1''' resultaot|staen '''$1''' resultaoten}} vanof #'''$2'''.",
'showingresultsnum'                => "Ieronder {{PLURAL:$3|sti '''1''' resultaot|staen '''$3''' resultaoten}} vanof #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Resultaot '''$1''' von '''$3'''|Resultaot'n '''$1 - $2''' von '''$3'''}} vò '''$4'''",
'nonefound'                        => "'''Opmerkieng''': mislukte zoekopdrachten worn vaok veroôrzaekt deur zoekn ni vee voekomm'nde woôrn as \"van\" en \"de\", die an nie in de indexen worn openoom'n, of deur meêr dan eên zoekterm op te heven. Allin pagina's die an aolle zoektermen bevatt'n worn openoom'n in de resultaoten.",
'search-nonefound'                 => "Er zin geen resultaot'n vò je zoekopdracht.",
'powersearch'                      => 'Uutebreid zoeken',
'powersearch-legend'               => 'Uutebreid zoeken',
'search-external'                  => 'Extern zoeken',
'searchdisabled'                   => "Zoeken in {{SITENAME}} is nie meuhlijk.
Je kan gebruuk maeken van Google.
De hehevens over {{SITENAME}} zien meuhlijk nie bie'ewerkt.",

# Quickbar
'qbsettings'               => 'Menubalke',
'qbsettings-none'          => 'Uuteschaokeld',
'qbsettings-fixedleft'     => 'Lienks vast',
'qbsettings-fixedright'    => 'Rechs vast',
'qbsettings-floatingleft'  => 'Lienks zwevend',
'qbsettings-floatingright' => 'Rechs zwevend',

# Preferences page
'preferences'               => 'Vòkeuren',
'mypreferences'             => 'Mien vòkeuren',
'prefs-edits'               => 'Antal bewerkiengen:',
'prefsnologin'              => 'Nie anemeld',
'prefsnologintext'          => 'Je mò <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} anemeld]</span> zien om je vòkeuren te kunn\'n instell\'n.',
'changepassword'            => 'Wachtwoôrd wiezigen',
'prefs-skin'                => 'Vurmhevieng',
'skin-preview'              => 'Voevertoônienge',
'datedefault'               => 'Hin vòkeur',
'prefs-datetime'            => 'Daotum en tied',
'prefs-personal'            => 'Gebrukersprofiel',
'prefs-rc'                  => 'Juust angepast',
'prefs-watchlist'           => 'Volglieste',
'prefs-watchlist-days'      => 'Daehen weer te heven in de volglieste:',
'prefs-watchlist-edits'     => 'Maximaol antal bewerkiengen in de uutebreide volglieste:',
'prefs-misc'                => 'Rest',
'saveprefs'                 => 'Opslaene',
'resetprefs'                => 'Nie opeslogen wiezigiengen herstellen',
'prefs-editing'             => 'Bewerken',
'rows'                      => 'Rehels:',
'columns'                   => "Kolomm'n:",
'searchresultshead'         => 'Zoek',
'resultsperpage'            => "Resultaot'n per pagina:",
'stub-threshold'            => 'Drempel voe markerieng <a href="#" class="stub">stompje</a>:',
'recentchangesdays'         => 'Antal daehen weer te heven in Juust angepast:',
'recentchangescount'        => "Antal pagina's in Juust angepast:",
'savedprefs'                => 'Je vòkeuren zien opeslogen.',
'timezonelegend'            => 'Tiedzône',
'localtime'                 => 'Lokaole tied',
'timezoneoffset'            => 'Tiedsverschil¹',
'servertime'                => 'Servertied',
'guesstimezone'             => 'Vanuut de browser toevoehen',
'allowemail'                => 'Laet e-mail van aore gebrukers toe.',
'defaultns'                 => "Standard in deêze naemruum'n zoeken:",
'default'                   => 'standard',
'prefs-files'               => "Bestan'n",
'youremail'                 => 'Jen e-mailadres:',
'username'                  => 'Gebrukersnaem:',
'uid'                       => 'Gebrukersnummer:',
'yourrealname'              => 'Jen echen naam:',
'yourlanguage'              => 'Taele:',
'yournick'                  => 'Tekst voe onderteêkenienge:',
'badsig'                    => 'Ongeldege andteêkenienge; kiek de [[HTML]]-expressies nae.',
'badsiglength'              => 'Te lange naem; ie mag uut maximaol $1 {{PLURAL:$1|letter|letters}} bestae.',
'email'                     => 'E-mail',
'prefs-help-realname'       => 'Echen naem is opsjoneel, a je dezen opgeef kan deêze naem gebruukt worn om je erkennieng te heven voe je werk.',
'prefs-help-email'          => "E-mailadres is opsjoneel, mè stel ie in staet je wachtwoôrd te ontvang'n via de mail as jen 't vergeet'n ben.",
'prefs-help-email-others'   => "Jie kunne ok aore in staet stell'n per e-mail contact mei jen op te neem'n via 'n verwiezienge op je gebrukers- en overlegpagina zonder da je jen identiteit priesguf.",
'prefs-help-email-required' => 'Iervoe is een e-mailadres noôdig.',

# User rights
'userrights'                  => 'Gebrukersrechenbeheer',
'userrights-lookup-user'      => "Gebrukershroep'n beheern",
'userrights-user-editname'    => 'Voer een gebrukersnaem in:',
'editusergroup'               => "Gebrukershroep'n wiezigen",
'editinguser'                 => "Bezig mie wiezigen van de gebrukersrechen van gebruker '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => "Gebrukershroep'n wiezigen",
'saveusergroups'              => "Gebrukershroep'n opslaene",
'userrights-groupsmember'     => 'Lid van:',
'userrights-groups-help'      => "Je kan de hroep'n wiezigen wir a deêze gebruker lid van is.
Een anekruusd vienkvakje beteêken da de gebruker lid is van de hroep.
Een nie anekruusd vienkvakje beteêken da de gebruker hin lid is van de hroep.",
'userrights-reason'           => "Reeën voe 't verandern:",
'userrights-no-interwiki'     => "Je ei hin rechen om gebrukersrechen op aore wiki's te wiezigen.",
'userrights-nodatabase'       => 'Database $1 besti nie of is hin plaetselijke database.',
'userrights-nologin'          => 'Je mò jen eihen[[Special:UserLogin|anmelden]] mie een gebruker mie de juuste rechen om gebrukersrechen toe te wiezen.',
'userrights-notallowed'       => 'Je ei hin rechen om gebrukersrechen toe te wiezen.',
'userrights-changeable-col'   => "Hroep'n die a je kan beheern",
'userrights-unchangeable-col' => "Hroep'n die a je nie kan beheern",

# Groups
'group'               => 'Hroep:',
'group-autoconfirmed' => 'Herehistreerde gebrukers',
'group-bot'           => 'Bots',
'group-sysop'         => 'Opzichters',
'group-bureaucrat'    => 'Amtenaers',
'group-suppress'      => 'Toezichtouwers',
'group-all'           => '(aolles)',

'group-autoconfirmed-member' => 'Herehistreerde gebruker',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Opzichter',
'group-bureaucrat-member'    => 'Amtenaer',
'group-suppress-member'      => 'Toezichtouwer',

'grouppage-autoconfirmed' => '{{ns:project}}:Herehistreerde gebrukers',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Opzichters',
'grouppage-bureaucrat'    => "{{ns:project}}:Bureaucraot'n",
'grouppage-suppress'      => '{{ns:project}}:Toezicht',

# User rights log
'rightslog'      => 'Gebrukersrechtenlogboek',
'rightslogtext'  => 'Ieronder staen de wiezigiengen in gebrukersrechen.',
'rightslogentry' => 'wiezihen de gebrukersrechen voe $1 van $2 ni $3',
'rightsnone'     => '(hin)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'deêze bladzie te bewerken',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|bewerkieng|bewerkiengen}}',
'recentchanges'                     => 'Juust angepast',
'recentchanges-legend'              => 'Opties vò juust angepast',
'recentchangestext'                 => 'Bekiek wat-a juust veranderd is op deêze wiki.',
'recentchanges-feed-description'    => 'Bekiek wat-a juust veranderd is op deêze wiki.',
'recentchanges-label-newpage'       => "Mei deêze bewarkienge is 'n nuwe pagina angemaekt",
'recentchanges-label-minor'         => 'Dit is een kleine wieziging',
'recentchanges-label-bot'           => "Deêze bewarkienge is uutgevoerd deur 'n bot",
'recentchanges-label-unpatrolled'   => 'Deêze bewarkienge is nog nie gecontroleerd',
'rcnote'                            => "Ieronder {{PLURAL:$1|sti de lèste bewerkieng|staen de lèste '''$1''' bewerkiengen}} in de lèste {{PLURAL:$2|dag|'''$2''' daegen}}, op $4 om $5.",
'rcnotefrom'                        => "Wiezigiengen sins '''$2''' (mie een maximum van '''$1''' wiezigiengen).",
'rclistfrom'                        => 'Bekiek de wiezigingen sins $1',
'rcshowhideminor'                   => '$1 kleine bewerkiengen',
'rcshowhidebots'                    => 'bots $1',
'rcshowhideliu'                     => '$1 angemelde gebrukers',
'rcshowhideanons'                   => '$1 anonieme gebrukers',
'rcshowhidepatr'                    => 'hecontroleerde bewerkiengen $1',
'rcshowhidemine'                    => '$1 mien bewerkiengen',
'rclinks'                           => 'Bekiek de lèste $1 wiezigingen in de lèste $2 daegen<br />$3',
'diff'                              => 'wiez',
'hist'                              => 'hesch',
'hide'                              => 'Verberge',
'show'                              => 'weerheven',
'minoreditletter'                   => 'k',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|keêr|keêr}} op een volglieste]',
'rc_categories'                     => 'Beperk\'n tot categorieën (scheien mie een "|")',
'rc_categories_any'                 => "Elk'n",
'newsectionsummary'                 => '/* $1 */ nieuwe subkop',
'rc-enhanced-expand'                => 'Details weerheven (JavaScript vereist)',
'rc-enhanced-hide'                  => 'Verbarg details',

# Recent changes linked
'recentchangeslinked'          => 'Gerelateerde bewerkiengen',
'recentchangeslinked-feed'     => 'Gerelateerde bewerkiengen',
'recentchangeslinked-toolbox'  => 'Gerelateerde bewerkiengen',
'recentchangeslinked-title'    => 'Wiezigiengen verwant an "$1"',
'recentchangeslinked-noresult' => "Der zien hin bewerkiengen in de heheven periode ewist op de pagina's die an vanaf ier elienkt worn.",
'recentchangeslinked-summary'  => "Deze speciaole pagina geeft de laetste bewerkiengen weer op pagina's waerheê verwezen òdt vanof 'n angegeven pagina of vanuut pagina's in 'n angegeven categorie.
Pagina's die op [[Special:Watchlist|je volglieste]] staen wòdde '''vet''' weergegeven.",
'recentchangeslinked-page'     => 'Paginanaem:',
'recentchangeslinked-to'       => "Wiezigiengen weerheven ni de helienkte pagina's",

# Upload
'upload'                     => 'Upload bestand',
'uploadbtn'                  => "Bestand upload'n",
'reuploaddesc'               => "Upload annuleern en teruggaene ni 't uploadformelier",
'uploadnologin'              => 'Nie anemeld',
'uploadnologintext'          => "Je mò [[Special:UserLogin|anemeld]] zien
om bestan'n te upload'n.",
'upload_directory_read_only' => 'De webserver kan nie schrieven in de uploadmap ($1).',
'uploaderror'                => 'Uploadfout',
'uploadtext'                 => "Gebruuk 't onderstaende formelier om bestan'n te uploaden.
Om eêder toehevoegde bestan'n te bekieken of te zoeken kan je ni de [[Special:FileList|bestandslieste]] haen.
Uploads en verwiederiengen worn bie'ehouwen in 't [[Special:Log/upload|uploadlogboek]].

Om 't bestand in te voehen in een pagina kan je eên van de volhende codes gebruken, a ni helang 't bestandsformaot dat van toepassieng is:
* '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Bestand.jpg]]</nowiki>'''
* '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Bestand.png|alternatieve tekst]]</nowiki>'''
* '''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Bestand.ogg]]</nowiki>'''

De lèste lienk is bedoeld voe mediabestan'n die an hin plaetje zien.",
'upload-permitted'           => 'Toehelaeten bestandstypes: $1.',
'upload-preferred'           => 'Anewezen bestandstypes: $1.',
'upload-prohibited'          => "Verbood'n bestandstypes: $1.",
'uploadlog'                  => 'uploadlogboek',
'uploadlogpage'              => 'Uploadlogboek',
'uploadlogpagetext'          => "Ieronder staen de nieuwste bestan'n.",
'filename'                   => 'Bestandsnaem',
'filedesc'                   => 'Saemenvattieng',
'fileuploadsummary'          => 'Zaemenvattienge:',
'filestatus'                 => 'Auteursrechensituaosie:',
'filesource'                 => 'Bron:',
'uploadedfiles'              => "Heüploade bestan'n",
'ignorewarning'              => "Deêze waerschuwienge neheern en 't bestand toch opslaene",
'ignorewarnings'             => 'Aolle waerschuwiengen neheern',
'minlength1'                 => "Bestandsnaem'n mott'n minstes eên letter bevatt'n.",
'badfilename'                => 'Bestandsnaem is veranderd nae "$1".',
'uploadedimage'              => 'ei "[[$1]]" geüpload',
'watchthisupload'            => 'Volg deêze bladzie',

'license'        => 'Licentie:',
'license-header' => 'Licentie',

# File description page
'file-anchor-link'       => 'Bestand',
'filehist'               => 'Bestandsgeschiedenisse',
'filehist-help'          => "Klik op 'n datum/tied om 't bestand te zien zoas 't van d'r tied woas.",
'filehist-revert'        => 'terugdraejen',
'filehist-current'       => 'hudige versie',
'filehist-datetime'      => 'Datum/tied',
'filehist-thumb'         => 'Miniatuur',
'filehist-thumbtext'     => 'Miniatuuraofbilding vò de versie von $2 om $3',
'filehist-user'          => 'Gebruker',
'filehist-dimensions'    => 'Ofmetiengen',
'filehist-comment'       => 'Opmerkienge',
'imagelinks'             => 'Bestandsgebruuk',
'linkstoimage'           => "Dit bestand òdt op de volgende {{PLURAL:$1|pagina|$1 pagina's}} gebruukt:",
'nolinkstoimage'         => 'Hin enkele pagina gebruukt dit bestand.',
'sharedupload-desc-here' => "Dit bestand kom von $1 en kan oôk in aorre project'n gebruukt worr'n.
De [$2 pagina mè de bestandsbeschrievienge] wòdt hieronder weergegeev'n.",

# Random page
'randompage' => 'Bladzie op goed geluk',

# Statistics
'statistics' => "Stattistiek'n",

'disambiguationspage' => 'Template:Deurverwiespagina',

'brokenredirectstext' => 'De volgende deuverwieziengen stiere deu nae bladzie die nie bestae:',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|byte|bytes}}',
'nmembers'          => '$1 {{PLURAL:$1|bladzie|bladzies}}',
'prefixindex'       => "Alle pagina's op vòvoegsel",
'longpages'         => 'Langste bladzies',
'listusers'         => 'Gebrukerslieste',
'newpages'          => "Nieuwe pagina's",
'newpages-username' => 'Gebrukersnaem:',
'ancientpages'      => 'Bladzies die-an lang nie bin angepast',
'move'              => 'Verschuuf',
'movethispage'      => 'Verschuuf deêze bladzie',
'pager-newer-n'     => '{{PLURAL:$1|1 nuwere|$1 nuwere}}',
'pager-older-n'     => '{{PLURAL:$1|1 ouwere|$1 ouwere}}',

# Book sources
'booksources'               => 'Bronnen vò boeken',
'booksources-search-legend' => "Bronn'n en informaosie over 'n boek zoek'n",
'booksources-go'            => 'OK',

# Special:Log
'specialloguserlabel' => 'Gebruker:',
'log'                 => "Logboek'n",
'alllogstext'         => "Saemengesteld overzicht van de wis-, bescherm-, blokkeer- en gebrukerslechtenlogboeken.
Je kan 't overzicht bepaelen deu 'n soôrte logboek, 'n gebrukersnaem of eên bladzie uut te kiezen.",

# Special:AllPages
'allpages'          => 'Aolle bladzies',
'alphaindexline'    => '$1 toet $2',
'nextpage'          => 'Volgende bladzie ($1)',
'allpagesfrom'      => 'Laet bladzies zieë vanaf:',
'allarticles'       => 'Aolle artikels',
'allinnamespace'    => 'Aolle bladzies uut de $1-naemruumte',
'allnotinnamespace' => 'Aolle bladzies (nie in de $1-naemruumte)',
'allpagesprev'      => 'Vorrege',
'allpagessubmit'    => 'OK',
'allpagesprefix'    => "Laet bladzies zieë mee 't vovoegsel:",
'allpagesbadtitle'  => "D'n ingegeven bladzie-titel was ongeldeg of ao 'n interwiki-vòvoegsel. Meschien stae d'r eên of meer teêkens in die-an nie in titels gebruukt ore kunne.",

# Special:Categories
'categories'                    => 'Categorieën',
'categoriespagetext'            => 'De wiki eit de volgende categorieën.
[[Special:UnusedCategories|Unused categories]] are not shown here.
Also see [[Special:WantedCategories|wanted categories]].',
'special-categories-sort-count' => 'op antal sorteern',
'special-categories-sort-abc'   => 'alfabetisch sorteern',

# Special:LinkSearch
'linksearch-line' => "$1 ei 'n verwiezienge in $2",

# Special:Log/newusers
'newuserlogpage'          => 'Logboek nuwe gebrukers',
'newuserlog-create-entry' => 'Nieuwe gebruker',

# Special:ListGroupRights
'listgrouprights-members' => '(ledenlieste)',

# E-mail user
'emailuser' => 'E-mail deêze gebruker',
'emailpage' => 'E-mail gebruker',

# Watchlist
'watchlist'         => 'Volglieste',
'mywatchlist'       => 'Mien volglieste',
'watchlistfor2'     => 'Vò $1 $2',
'watchnologin'      => 'Je bin nie angemolde.',
'watchnologintext'  => 'Je moe [[Special:UserLogin|angemolde]] weze om je volglieste an te passen.',
'addedwatchtext'    => "De bladzie \"[[:\$1]]\" is an je [[Special:Watchlist|Volglieste]] toegevoegd.
Veranderiengen an deêze bladzie en de overlegbladzie die-a d'rbie oort zulle ierop zichtbaer ore
en de bladzie komt '''vet''' te staen in de [[Special:RecentChanges|lieste van wat-a juust veranderd is]], daermee 't makkeliker te vinden is.
A je de bladzie laeter weêr van je volglieste afaele wil, klik dan op \"nie meêr volge\" bovenan de bladzie.",
'watch'             => 'Volg',
'watchthispage'     => 'Bekiek deêze bladzie',
'unwatch'           => 'Nie meêr volge',
'watchnochange'     => "D'r is in d'n opgevrogen tied niks op je volglieste veranderd.",
'watchlist-details' => "Er {{PLURAL:$1|sti eên pagina|staen $1 pagina's}} op je volglieste, exclusief overlegpagina's.",
'watchlistcontains' => 'Uw volglieste bevat $1 {{PLURAL:$1|bladzie|bladzies}}.',
'wlshowlast'        => 'Laetste $1 uur, $2 daegen bekieken ($3)',
'watchlist-options' => 'Opties vò volglieste',

# Delete
'actioncomplete' => 'Actie uutgevoerd',
'actionfailed'   => 'De handelienge is mislukt.',
'deletedarticle' => 'wiste "[[$1]]"',
'dellogpage'     => 'Wislogboek',

# Rollback
'rollbacklink'  => 'terugdraejen',
'alreadyrolled' => 'De lèste bewerkienge op [[$1]] deu [[User:$2|$2]] ([[User talk:$2|Overleggienge]]) kan nie vrommegedraoid ore; iemand aors eit de bladzie al bewerkt of ersteld.
De lèste bewerkienge wier gedaen deu [[User:$3|$3]] ([[User talk:$3|Overleggienge]]).',
'revertpage'    => 'Wiezigingen deur [[Special:Contributions/$2|$2]] ([[User talk:$2|Overleg]]) teruggedraoid nae de lèste versie van [[User:$1|$1]]',

# Protect
'protectlogpage'   => 'Beschermlogboek',
'protectedarticle' => 'beveiligde "[[$1]]"',
'prot_1movedto2'   => '[[$1]] is verschove nae [[$2]]',

# Undelete
'undeletelink'     => "bekiek'n/terugplàts'n",
'undeleteviewlink' => "bekiek'n",
'cannotundelete'   => 'Can de bladzie nie erstelle; mischien eit iemand aors de bladzie a vrommegezet.',

# Namespace form on various pages
'namespace'      => 'Naemruumte:',
'invert'         => 'Omgekeêrde selectie',
'blanknamespace' => '(Artikels)',

# Contributions
'contributions'       => 'Biedraegen gebruker',
'contributions-title' => 'Biedraen van $1',
'mycontris'           => 'Mien biedraegen',
'contribsub2'         => 'Vò $1 ($2)',
'uctop'               => '(laetste wiezigieng)',
'month'               => 'Von maend (en eêder):',
'year'                => 'Von jaer (en eêder):',

'sp-contributions-newbies'    => "Alleên de biedraen von nuwe hebrukers bekiek'n",
'sp-contributions-blocklog'   => 'blokkeerlogboek',
'sp-contributions-uploads'    => 'uploads',
'sp-contributions-logs'       => "logboek'n",
'sp-contributions-talk'       => 'overleg',
'sp-contributions-userrights' => 'Gebrukersrechenbeheer',
'sp-contributions-search'     => "Zoek'n ni biedraen",
'sp-contributions-username'   => 'IP-adres of gebrukersnaem:',
'sp-contributions-toponly'    => 'Alleên nuuwste versies weerheven',
'sp-contributions-submit'     => 'Zoek',

# What links here
'whatlinkshere'            => 'Links nae deze bladzie',
'whatlinkshere-title'      => 'Pagina\'s die verwiez\'n nir "$1"',
'whatlinkshere-page'       => 'Pagina:',
'linkshere'                => "De volhende pagina's verwieze nir '''[[:$1]]''':",
'nolinkshere'              => "Hin enkele pagina verwies nir '''[[:$1]]'''.",
'isredirect'               => 'deurverwiespagina',
'istemplate'               => 'ingevoegd as sjabloon',
'isimage'                  => 'bestandsverwiezienge',
'whatlinkshere-prev'       => '{{PLURAL:$1|ouwere|ouwere $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|volgende|volgende $1}}',
'whatlinkshere-links'      => '← verwieziengen ni deêze pagina',
'whatlinkshere-hideredirs' => 'deurverwieziengen $1',
'whatlinkshere-hidetrans'  => 'transclusies $1',
'whatlinkshere-hidelinks'  => 'verwieziengen $1',
'whatlinkshere-hideimages' => 'bestandsverwiezingen $1',
'whatlinkshere-filters'    => 'Filters',

# Block/unblock
'blockip'                     => 'Blokkeer gebruker',
'ipboptions'                  => '2 uur:2 hours,1 dag:1 day,3 daegen:3 days,1 week:1 week,2 weken:2 weeks,1 maend:1 month,3 maenden:3 months,6 maenden:6 months,1 jaer:1 year,onbepaeld:infinite',
'badipaddress'                => 'Ongeldig IP-adres',
'blockipsuccesssub'           => 'Blokkaode is gelukt.',
'blockipsuccesstext'          => "[[Special:Contributions/$1|$1]] is geblokkeerd.<br />
Ziet de [[Special:IPBlockList|IP-blokliest]] vo 'n overzicht van blokkaodes.",
'ipblocklist'                 => 'Geblokkeerde gebrukers',
'anononlyblock'               => 'alleêne anon.',
'blocklink'                   => 'blokkeer',
'unblocklink'                 => "deblokkeer'n",
'change-blocklink'            => "blokkade anpass'n",
'contribslink'                => 'biedraegen',
'autoblocker'                 => 'Je bin automaotisch geblokkeerd om-at je IP-adres pas gebruukt is deu "[[User:$1|$1]]".
De reje daevò was: "$2"',
'blocklogpage'                => 'Blokkeerlogboek',
'blocklogentry'               => 'ei "[[$1]]" geblokkeerd mee \'n afloôptied van $2 $3',
'blocklogtext'                => "Dit is 'n logboek van gebrukersblokkaodes en -deblokkeriengen.
Automaotisch geblokte ip-adressen stae d'r nie bie.
Ziet de [[Special:BlockList|Lieste van ip-blokkeriengen]] vò blokkaodes die op dit moment in werkienge bin.",
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
'movelogpage'      => 'Hernoemingslogboek',
'revertmove'       => 'terugdraejen',

# Export
'export' => 'Exporteren',

# Namespace 8 related
'allmessagesname'           => 'Naem',
'allmessagesdefault'        => 'Standerttekst',
'allmessagescurrent'        => 'Tekst van noe',
'allmessagestext'           => "Dit is 'n liest van aolle systeemteksten die-an in de MediaWiki-naemruumte stae.",
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' kan nie gebruukt ore om'at '''\$wgUseDatabaseMessages''' uutgeschaekeld staet.",

# Thumbnails
'thumbnail-more'  => 'Verhroôt',
'thumbnail_error' => "Fout bie 't anmaek'n van de miniatuuraofbeêldienge: $1",

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Mien gebrukersbladzie',
'tooltip-pt-mytalk'               => 'Mien overlegbladzie',
'tooltip-pt-preferences'          => 'Mien vòkeuren',
'tooltip-pt-watchlist'            => 'Lieste meê bladzies die op mien volglieste stae',
'tooltip-pt-mycontris'            => 'Mien biedraegen',
'tooltip-pt-login'                => "Je wod van harte uutgenoôdigd om j'eigen aen te melden as gebruker, maer dit is nie verplicht.",
'tooltip-pt-logout'               => 'Ofmelden',
'tooltip-ca-talk'                 => 'Overleg over deze pagina',
'tooltip-ca-edit'                 => "Jie kunne deze pagina bewark'n. Hebruuk de vòbildweêrhaveknop vò te bewaer'n.",
'tooltip-ca-addsection'           => "Nuuw kopje toevoeg'n",
'tooltip-ca-viewsource'           => "Dizze pagina bin beveiligd. Je kunne wè de broncode bekiek'n.",
'tooltip-ca-history'              => 'Eêdere versies von deze pahina',
'tooltip-ca-protect'              => 'Bescherm deêze bladzie',
'tooltip-ca-delete'               => 'Wis deêze bladzie',
'tooltip-ca-move'                 => 'Verschuuf deêze bladzie',
'tooltip-ca-watch'                => 'Voeg deêze bladzie an de volglieste toe',
'tooltip-ca-unwatch'              => "Deêze pagina van mien volglieste aofhael'n",
'tooltip-search'                  => '{{SITENAME}} deurzoeke',
'tooltip-search-go'               => "Gae ni 'n pagina mei deêze naem as die bestit",
'tooltip-search-fulltext'         => "Aole pagina's ap deêze tekst deurzoeke",
'tooltip-p-logo'                  => 'Vòblad',
'tooltip-n-mainpage'              => "Bekiek 't vòblad",
'tooltip-n-mainpage-description'  => "Gae nae 't vòblad",
'tooltip-n-portal'                => "Praet en overleg in't Durpsuus",
'tooltip-n-currentevents'         => "Achtergrondinformaotie over actuele zaok'n",
'tooltip-n-recentchanges'         => 'Bekiek wat-a juust veranderd is op deêze wiki',
'tooltip-n-randompage'            => "'n Bladzie ap goed geluk bekieke",
'tooltip-n-help'                  => 'Hulpinformaosie uvver deêze wiki',
'tooltip-t-whatlinkshere'         => "Lieste von aolle pagina's die nir deze pagina verwiezen",
'tooltip-t-recentchangeslinked'   => "Recente anpassiengen in pagina's wir deze pagina nir verwies",
'tooltip-feed-atom'               => 'Atom-feed vò deze pagina',
'tooltip-t-contributions'         => "'n Lieste mei biedraen van deêze gebruker",
'tooltip-t-emailuser'             => "'n E-mail nir deêze hebruker verzen'n",
'tooltip-t-upload'                => "Bestand upload'n",
'tooltip-t-specialpages'          => "Liest van aole speciaole pagina's",
'tooltip-t-print'                 => 'Printvrindelike versie van dizze pagina',
'tooltip-t-permalink'             => 'Permanente verwiezienge nir deze versie von de pagina',
'tooltip-ca-nstab-main'           => 'Bekiek inholdsbladzie',
'tooltip-ca-nstab-user'           => 'Bekiek gebrukersbladzie',
'tooltip-ca-nstab-special'        => "Dit is 'n speciaole pagina, jie kunne de pagina zalf nie bewark'n",
'tooltip-ca-nstab-project'        => 'Bekiek projectbladzie',
'tooltip-ca-nstab-image'          => 'Bekiek bestandspagina',
'tooltip-ca-nstab-template'       => "Sjabloon bekiek'n",
'tooltip-ca-nstab-category'       => "Categoriebladzie bekiek'n",
'tooltip-minoredit'               => "Deêze wiezigienge as 'n kleine wiezigienge markeren",
'tooltip-save'                    => 'Je wiezigiengen opslaen',
'tooltip-preview'                 => "'n Vòvertoônienge maek'n. Gebruuk diet!",
'tooltip-diff'                    => "Gemaekte wiezigienge bekiek'n (zoas 't in de geschiedenisse te zien zal wezen)",
'tooltip-compareselectedversions' => "De verschill'n tiss'n de geselecteerde versies van deêze pagina bekiek'n.",
'tooltip-watch'                   => 'Voeg deêze bladzie toe an de volglieste',
'tooltip-rollback'                => 'Mè "terugdraejen" draej ie mè eên klik de bewerkienge(n) terugge van de laetste gebruker die dizze pagina bewerkt ei.',
'tooltip-undo'                    => "Mè \"onhedaen maek'n\" draej-ie deze bewerkienge terugge en kom 't in 't bewerkiengsvenster. Je kunne in de bewerkiengssaemevattienge 'n reêde opheven.",
'tooltip-summary'                 => "Voer 'n korte saem'nvattienge in",

# Browsing diffs
'previousdiff' => '← Ouwere bewarkienge',
'nextdiff'     => 'Nuwere bewarkienge →',

# Media information
'file-info-size' => '$1 × $2 pixels, bestandsgroôtte: $3, MIME-type: $4',
'svg-long-desc'  => 'SVG-bestand, nominaal $1 × $2 pixels, bestandshroôtte: $3',
'show-big-image' => 'Volledige resolutie',

# Bad image list
'bad_image_list' => "De opmaek is as vogt:

Alleên regels in 'n lieste (regels die beginn'n mè *) worr'n verwarkt.
De eêste verwiezienge op 'n regel moe 'n verwiezienge zin nir 'n ongewenst bestand.
Aolle voggende verwieziengen die op dezelfde regel staen, worr'n behandeld as uutzonderienge, zoas bievòbild pagina's wirop 't bestand in de tekst is opgenaem'n.",

# Metadata
'metadata'        => 'Metadata',
'metadata-help'   => "Dit bestand bevat anvullende informaotie, die deur 'n fotocaomera, scanner of fotobewarkiengsprogramma toegevoegd kan zien. As 't bestand angepast is, kommen details mogelijk nie overeên mei 't gewiezigde bestand.",
'metadata-fields' => "De aofbildiengsmetadataveld'n in dit bericht worr'n oôk weergegeev'n op 'n aofbildiengspagina as de metadatatabel ingeklapt is.
Aorre veld'n worr'n verborr'n.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude",

# External editor support
'edit-externally'      => "Dit bestand in 'n extern programma bewark'n",
'edit-externally-help' => '(zieë de [//www.mediawiki.org/wiki/Manual:External_editors handleidienge vò instelliengen] vò meê informaosie)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'aol',
'namespacesall' => 'aol',
'monthsall'     => 'aolle',

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

# Watchlist editing tools
'watchlisttools-view' => 'Relevante wiezigiengen bekieken',
'watchlisttools-edit' => 'Bekiek en bewark ju volglieste',
'watchlisttools-raw'  => "Rieuwe volglieste bewark'n",

# Core parser functions
'duplicate-defaultsort' => 'Waerschiewienge: De standaardsorterienge "$2" kriet vòrang vò de sorterienge "$1".',

# Special:SpecialPages
'specialpages' => 'Speciaole bladzies',

# External image whitelist
'external_image_whitelist' => " #Laet deêze regel onveraonerd<pre>
#Zet ieronder reguliere expressiefragment'n (alleên 't deêl da tiss'n de // sti)
#Deêze worn gehouen tegen de URL's van externe (gehotlinkte) ofbildieng'n
#As de reguliere expressie van toegang is, wor 'n ofbildienge weereheven, anners wor alleên 'n verwiezienge weereheven
#Regels die beginn'n mè \"#\" worn as opmarkienge behandeld
#Regels in de witte lieste zin nie hoofdlettergevoelig.

#Zet aolle reguliere expressiefragment'n boven deêze regel. Laet deêze regel onveraonerd</pre>",

# Special:Tags
'tag-filter' => '[[Special:Tags|Labelfilter]]:',

);
