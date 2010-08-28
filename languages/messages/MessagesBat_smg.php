<?php
/** Samogitian (Žemaitėška)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Hugo.arg
 * @author Urhixidur
 * @author Zordsdavini
 * @author לערי ריינהארט
 */

$fallback = 'lt';

$namespaceNames = array(
	NS_MEDIA            => 'Medėjė',
	NS_SPECIAL          => 'Specēlos',
	NS_TALK             => 'Aptarėms',
	NS_USER             => 'Nauduotuos',
	NS_USER_TALK        => 'Nauduotuojė_aptarėms',
	NS_PROJECT_TALK     => '$1_aptarėms',
	NS_FILE             => 'Abruozdielis',
	NS_FILE_TALK        => 'Abruozdielė_aptarėms',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_aptarėms',
	NS_TEMPLATE         => 'Šabluons',
	NS_TEMPLATE_TALK    => 'Šabluona_aptarėms',
	NS_HELP             => 'Pagelba',
	NS_HELP_TALK        => 'Pagelbas_aptarėms',
	NS_CATEGORY         => 'Kateguorėjė',
	NS_CATEGORY_TALK    => 'Kateguorėjės_aptarėms',
);

/**
  * Aliases from the fallback language 'lt' to avoid breakage of links
  */

$namespaceAliases = array(
	'Specialus'             => NS_SPECIAL,
	'Aptarimas'             => NS_TALK,
	'Naudotojas'            => NS_USER,
	'Naudotojo_aptarimas'   => NS_USER_TALK,
	'$1_aptarimas'          => NS_PROJECT_TALK,
	'Vaizdas'               => NS_FILE,
	'Vaizdo_aptarimas'      => NS_FILE_TALK,
	'MediaWiki_aptarimas'   => NS_MEDIAWIKI_TALK,
	'Šablonas'              => NS_TEMPLATE,
	'Šablono_aptarimas'     => NS_TEMPLATE_TALK,
	'Pagalba'               => NS_HELP,
	'Pagalbos_aptarimas'    => NS_HELP_TALK,
	'Kategorija'            => NS_CATEGORY,
	'Kategorijos_aptarimas' => NS_CATEGORY_TALK,
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Pabrauktė nūruodas:',
'tog-highlightbroken'         => 'Fuormoutė nasontiu poslapiu nūruodas <a href="#" class="new">šėtēp</a> (prīšėngā - šėtēp <a href="#" class="internal">?</a>).',
'tog-justify'                 => 'Līgintė pastraipas palē abi poses',
'tog-hideminor'               => 'Pakavuotė mažus pataisėmus vielībūju taisīmu sārašė',
'tog-extendwatchlist'         => 'Ėšpliestė keravuojamu sāraša, kū ruodītu vėsus tėnkamus pakeitėmus',
'tog-usenewrc'                => 'Pažongē ruodomė vielibė̅jė pakeitėmā (JavaScript)',
'tog-numberheadings'          => 'Autuomatėškā numeroutė skėrsnelios',
'tog-showtoolbar'             => 'Ruodītė redagavėma rakondinė (JavaScript)',
'tog-editondblclick'          => 'Poslapiu redagavėms dvėgobu paspaudėmu (JavaScript)',
'tog-editsection'             => 'Ijongtė skėrsneliu redagavėma nauduojant nūruodas [taisītė]',
'tog-editsectiononrightclick' => 'Ijongtė skėrsneliu redagavėma paspaudos skėrsnelė pavadėnėma<br />dešėniouju pelies klavėšu (JavaScript)',
'tog-showtoc'                 => 'Ruodītė torėni, jē poslapī daugiau kāp 3 skėrsnelē',
'tog-rememberpassword'        => 'Atmintė prėsėjongėma infuormacėjė šėtom kuompioterī (for a maximum of $1 {{PLURAL:$1|day|days}})',
'tog-watchcreations'          => 'Pridietė poslapius, katrūs sokorio, i keravuojamu sāraša',
'tog-watchdefault'            => 'Pridietė poslapius, katrūs taisau, i keravuojamu sāraša',
'tog-watchmoves'              => 'Pridietė poslapius, katrūs parkelio, i keravuojamu sāraša',
'tog-watchdeletion'           => 'Pridietė poslapius, katrūs ėštrino, i keravuojamu sāraša',
'tog-previewontop'            => 'Ruodītė parvaiza vėrš redagavėma lauka',
'tog-previewonfirst'          => 'Ruodītė straipsnė parvėiza pėrmu redagavėmu',
'tog-nocache'                 => "Nenauduotė poslapiu kaupėma (''caching'')",
'tog-enotifwatchlistpages'    => 'Siōstė mon gromata, kūmet pakeitams poslapis, katra stebiu',
'tog-enotifusertalkpages'     => 'Siōstė mon gromata, kūmet pakaitams mona nauduotuojė aptarėma poslapis',
'tog-enotifminoredits'        => 'Siōstė mon gromata, kūmet poslapė keitėms īr mažos',
'tog-enotifrevealaddr'        => 'Ruodītė mona el. pašta adresa primėnėma gromatuos',
'tog-shownumberswatching'     => 'Ruodītė keravuojantiu nauduotuoju skatliu',
'tog-fancysig'                => 'Parašos ba autuomatėniu nūruodu',
'tog-externaleditor'          => 'Palē nutīliejėma nauduotė ėšuorini radaktuoriu',
'tog-externaldiff'            => 'Palē nutīliejėma nauduotė ėšuorinė skėrtomu ruodīma pruograma',
'tog-showjumplinks'           => 'Ijongtė „paršuoktė i“ pasėikiamoma nūruodas',
'tog-uselivepreview'          => 'Nauduotė tėisiogėne parvėiza (JavaScript) (Eksperimentėnis)',
'tog-forceeditsummary'        => 'Klaustė, kumet palėiku toščē pakeitėma kuomentara',
'tog-watchlisthideown'        => 'Kavuotė mona pakeitėmos keravuojamu sarašė',
'tog-watchlisthidebots'       => 'Kavuotė robotu pakeitėmos keravuojamu sārašė',
'tog-watchlisthideminor'      => 'Kavuotė mažos pakeitėmos keravuojamu sarašė',
'tog-watchlisthideliu'        => 'Kavuotė prisėjongosium nauduotuojum keitėmus keravuojamu sārošė',
'tog-watchlisthideanons'      => 'Kavuotė anonimėniu nauduotuoju keitėmus keravuojamu sarašė',
'tog-nolangconversion'        => 'Ėšjongtė variantu keitėma',
'tog-ccmeonemails'            => 'Siōstė mon gromatu kopėjės, katros siontiu kėtėims nauduotojams',
'tog-diffonly'                => 'Neruodītė poslapė torėnė puo skėrtomās',
'tog-showhiddencats'          => 'Ruodītė pakavuotas kateguorėjės',
'tog-norollbackdiff'          => 'Nekrēptė diemesė i skėrtoma atlėkus atmetėma',

'underline-always'  => 'Visumet',
'underline-never'   => 'Nikumet',
'underline-default' => 'Palē naršīklės nostatīmos',

# Dates
'sunday'        => 'sekma dėina',
'monday'        => 'pėrmadėinis',
'tuesday'       => 'ontradėinis',
'wednesday'     => 'trečiadėinis',
'thursday'      => 'ketvėrtadėinis',
'friday'        => 'pėnktadėinis',
'saturday'      => 'subata',
'sun'           => 'Sekm',
'mon'           => 'Pėrm',
'tue'           => 'Ontr',
'wed'           => 'Treč',
'thu'           => 'Ketv',
'fri'           => 'Pėnk',
'sat'           => 'Sub',
'january'       => 'sausė',
'february'      => 'vasarė',
'march'         => 'kuova',
'april'         => 'balondė',
'may_long'      => 'gegožės',
'june'          => 'bėrželė',
'july'          => 'lėipas',
'august'        => 'rogpjūtė',
'september'     => 'siejės',
'october'       => 'spalė',
'november'      => 'lapkrėstė',
'december'      => 'groudė',
'january-gen'   => 'Sausis',
'february-gen'  => 'Vasaris',
'march-gen'     => 'Kuovs',
'april-gen'     => 'Balondis',
'may-gen'       => 'Gegožė',
'june-gen'      => 'Bėrželis',
'july-gen'      => 'Lėipa',
'august-gen'    => 'Rogpjūtis',
'september-gen' => 'Siejė',
'october-gen'   => 'Spalis',
'november-gen'  => 'Lapkrėstis',
'december-gen'  => 'Groudis',
'jan'           => 'sau',
'feb'           => 'vas',
'mar'           => 'kuo',
'apr'           => 'bal',
'may'           => 'geg',
'jun'           => 'bėr',
'jul'           => 'lėi',
'aug'           => 'rgp',
'sep'           => 'sie',
'oct'           => 'spa',
'nov'           => 'lap',
'dec'           => 'grd',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|Kateguorėjė|Kateguorėjės|Kateguorėju}}',
'category_header'          => 'Kateguorėjės „$1“ straipsnē',
'subcategories'            => 'Subkateguorėjės',
'category-media-header'    => 'Abruozdielis kateguorėjuo „$1“',
'category-empty'           => "''Šėta kateguorėjė nūnā netor nė vėina straipsnė a faila.''",
'hidden-categories'        => '{{PLURAL:$1|Pakavuota kateguorėjė|Pakavuotas kateguorėjės}}',
'hidden-category-category' => 'Pakavuotas kateguorėjės',
'category-subcat-count'    => '{{PLURAL:$2|Tuo kateguorėjuo īr vėina subkateguorėjė.|{{PLURAL:$1|Ruodoma|Ruodomas|Ruodoma}} $1 {{PLURAL:$1|subkateguorėjė|subkateguorėjės|subkateguorėju}} (ėš vėsa īr $2 {{PLURAL:$2|subkateguorėjė|subkateguorėjės|subkateguorėju}}).}}',
'category-article-count'   => '{{PLURAL:$2|Tuo kateguorėjuo īr vėins poslapis.|{{PLURAL:$1|Ruodoms|Ruodomė|Ruodoma}} $1 tuos kateguorėjės {{PLURAL:$1|poslapis|poslapē|poslapiu}} (ėš vėsa kateguorėjuo īr $2 {{PLURAL:$2|poslapis|poslapē|poslapiu}}).}}',
'listingcontinuesabbrev'   => 'tes.',

'about'         => 'Aple',
'article'       => 'Straipsnis',
'newwindow'     => '(īr atverams naujam longė)',
'cancel'        => 'Nutrauktė',
'moredotdotdot' => 'Daugiau...',
'mypage'        => 'Mona poslapis',
'mytalk'        => 'Mona aptarėms',
'anontalk'      => 'Šėta IP aptarėms',
'navigation'    => 'Naršīms',
'and'           => '&#32;ėr',

# Cologne Blue skin
'qbfind'         => 'Ėiškuotė',
'qbbrowse'       => 'Naršītė',
'qbedit'         => 'Taisītė',
'qbpageoptions'  => 'Tas poslapis',
'qbpageinfo'     => 'Konteksts',
'qbmyoptions'    => 'Mona poslapē',
'qbspecialpages' => 'Specēlė̅jė poslapē',
'faq'            => 'DOK',
'faqpage'        => 'Project:DOK',

# Vector skin
'vector-action-addsection'   => 'Pridietė tema',
'vector-action-delete'       => 'Trintė',
'vector-action-move'         => 'Parvadintė',
'vector-action-protect'      => 'Ožrakintė',
'vector-action-undelete'     => 'Atkortė',
'vector-action-unprotect'    => 'Atrakintė',
'vector-namespace-category'  => 'Kateguorėjė',
'vector-namespace-help'      => 'Pagelbas poslapis',
'vector-namespace-image'     => 'Fails',
'vector-namespace-main'      => 'Poslapis',
'vector-namespace-media'     => 'Abruozdielė poslapis',
'vector-namespace-mediawiki' => 'Pranešims',
'vector-namespace-project'   => 'Pruojekta poslapis',
'vector-namespace-special'   => 'Specēlos poslapis',
'vector-namespace-talk'      => 'Aptarėms',
'vector-namespace-template'  => 'Šabluons',
'vector-namespace-user'      => 'Nauduotuojė poslapis',
'vector-view-create'         => 'Sokortė',
'vector-view-edit'           => 'Taisītė',
'vector-view-history'        => 'Veizietė istuorėjė',
'vector-view-view'           => 'Skaitītė',
'vector-view-viewsource'     => 'Veizietė kuoda',
'actions'                    => 'Vēksmā',
'namespaces'                 => 'Vardū srėtīs',
'variants'                   => 'Variantā',

'errorpagetitle'    => 'Klaida',
'returnto'          => 'Grīžtė i $1.',
'tagline'           => 'Straipsnis ėš {{SITENAME}}.',
'help'              => 'Pagelba',
'search'            => 'Ėiškuotė',
'searchbutton'      => 'Ėiškuok',
'go'                => 'Ēk',
'searcharticle'     => 'Ēk',
'history'           => 'Poslapė istuorėjė',
'history_short'     => 'Istuorėjė',
'updatedmarker'     => 'atnaujėnta nu paskotėnė mona apsėlonkīma',
'info_short'        => 'Infuormacėjė',
'printableversion'  => 'Versėjė spausdintė',
'permalink'         => 'Nulatėnė nūruoda',
'print'             => 'Spausdėntė',
'edit'              => 'Taisītė',
'create'            => 'Sokortė',
'editthispage'      => 'Taisītė ton poslapė',
'create-this-page'  => 'Sokortė ta poslapi',
'delete'            => 'Trintė',
'deletethispage'    => 'Trintė ton poslapė',
'protect'           => 'Ožrakintė',
'protect_change'    => 'pakeistė',
'protectthispage'   => 'Ožrakintė šėta poslapi',
'unprotect'         => 'Atrakintė',
'unprotectthispage' => 'Atrakintė šėta poslapi',
'newpage'           => 'Naus poslapis',
'talkpage'          => 'Aptartė šėta poslapi',
'talkpagelinktext'  => 'Aptarėms',
'specialpage'       => 'Specēlosis poslapis',
'personaltools'     => 'Persuonalėnē rakondā',
'postcomment'       => 'Rašītė kuomentara',
'articlepage'       => 'Veizietė straipsnė',
'talk'              => 'Aptarėms',
'views'             => 'Parveizėtė',
'toolbox'           => 'Rakondā',
'userpage'          => 'Ruodītė nauduotoja poslapi',
'projectpage'       => 'Ruodītė pruojekta poslapi',
'imagepage'         => 'Veizietė abruozdielė poslapi',
'mediawikipage'     => 'Ruodītė pranešėma poslapi',
'templatepage'      => 'Ruodītė šabluona poslapi',
'viewhelppage'      => 'Ruodītė pagelbuos poslapi',
'categorypage'      => 'Ruodītė kateguorėjės poslapi',
'viewtalkpage'      => 'Ruodītė aptarėma poslapi',
'otherlanguages'    => 'Kėtuom kalbuom',
'redirectedfrom'    => '(Nokreipta ėš $1)',
'redirectpagesub'   => 'Nokreipėma poslapis',
'lastmodifiedat'    => 'Šėts poslapis paskotini karta pakeists $1 $2.',
'viewcount'         => 'Tas poslapis bova atverts $1 {{PLURAL:$1|čiesa|čiesus|čiesu}}.',
'protectedpage'     => 'Ožrakints poslapis',
'jumpto'            => 'Paršuoktė i:',
'jumptonavigation'  => 'navėgacėjė',
'jumptosearch'      => 'paėiška',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Aple {{SITENAME}}',
'aboutpage'            => 'Project:Aple',
'copyright'            => 'Turinīs pateikts so $1 licencėjė.',
'copyrightpage'        => '{{ns:project}}:Autuoriu teisės',
'currentevents'        => '** Vielībė̅jė ivīkē **',
'currentevents-url'    => 'Project:Vielībė̅jė ivīkē',
'disclaimers'          => 'Atsakuomībės aprėbuojims',
'disclaimerpage'       => 'Project:Atsakuomībės aprėbuojims',
'edithelp'             => 'Kāp redagoutė',
'edithelppage'         => 'Help:Redagavėms',
'helppage'             => 'Help:Torėnīs',
'mainpage'             => 'Pėrms poslapis',
'mainpage-description' => 'Pėrms poslapis',
'policy-url'           => 'Project:Puolitėka',
'portal'               => 'Kuolektīvs',
'portal-url'           => 'Project:Kuolektīvs',
'privacy'              => 'Privatoma puolitėka',
'privacypage'          => 'Project:Privatoma puolitėka',

'badaccess'        => 'Privėlėju klaida',
'badaccess-group0' => 'Tomstā nelēdama ivīkdītė veiksma, katruo prašiet.',

'ok'                      => 'Gerā',
'retrievedfrom'           => 'Gautė ėš „$1“',
'youhavenewmessages'      => 'Tamsta toret $1 ($2).',
'newmessageslink'         => 'naujū žėnotiu',
'newmessagesdifflink'     => 'paskotinis pakeitėms',
'youhavenewmessagesmulti' => 'Toret naujū žėnotiu $1',
'editsection'             => 'taisītė',
'editold'                 => 'taisītė',
'viewsourceold'           => 'veizietė šaltėni',
'editlink'                => 'keistė',
'viewsourcelink'          => 'veizietė kuoda',
'editsectionhint'         => 'Redagoutė skirsneli: $1',
'toc'                     => 'Torėnīs',
'showtoc'                 => 'ruodītė',
'hidetoc'                 => 'kavuotė',
'thisisdeleted'           => 'Veizėtė a atkortė $1?',
'viewdeleted'             => 'Ruodītė $1?',
'restorelink'             => '$1 {{PLURAL:$1|ėštrinta keitėma|ėštrintos keitėmos|ėštrintū keitėmu}}',
'feedlinks'               => 'Šaltėnis:',
'site-rss-feed'           => '$1 RSS šaltėnis',
'site-atom-feed'          => '$1 Atom šaltėnis',
'page-rss-feed'           => '„$1“ RSS šaltėnis',
'page-atom-feed'          => '„$1“ Atom šaltėnis',
'red-link-title'          => '$1 (poslapis da neparašīts)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Poslapis',
'nstab-user'      => 'Nauduotuojė poslapis',
'nstab-media'     => 'Abruozdielė poslapis',
'nstab-special'   => 'Specēlos poslapis',
'nstab-project'   => 'Proujekta poslapis',
'nstab-image'     => 'Fails',
'nstab-mediawiki' => 'Teksts',
'nstab-template'  => 'Šabluons',
'nstab-help'      => 'Pagelbuos poslapis',
'nstab-category'  => 'Kateguorėjė',

# Main script and global functions
'nosuchaction'      => 'Nier tuokė veiksma',
'nosuchspecialpage' => 'Nier tuokė specēlėjė poslapė',
'nospecialpagetext' => 'Tamsta prašiet nelaistėna specēlė̅jė poslapė, laistėnū specēliūju poslapiu sōraša rasėt [[Special:SpecialPages|specēliūju poslapiu sārošė]].',

# General errors
'error'                => 'Klaida',
'databaseerror'        => 'Doumenū bazės klaida',
'laggedslavemode'      => 'Diemesė: Poslapī gal nesmatītė naujausiu pakeitėmu.',
'readonly'             => 'Doumenū bazė ožrakėnta',
'enterlockreason'      => 'Iveskėt ožrakėnėma prižasti, tēpuogi kumet daugmaž bus atrokėnta',
'readonlytext'         => 'Doumenū bazė daba īr ožrakėnta naujėm irašam a kėtėm keitėmam,
mažo doumenū bazės techninē pruofilaktėkā,
puo tuo vėsks griš i sava viežes.
Ožrakėnusiuojo admėnėstratuoriaus pateikts rakėnima paaiškėnims: $1',
'missing-article'      => 'Doumenū bazė nerada poslapė teksta, katra ana torietu rastė, pavadėnta „$1“ $2.

Paprastā tas būn dielē pasenosės skėrtoma vuo istuorėjės nūruodas i poslapi, katros bova ėštrėnts.

Jēgo tas nie šėts varėjants, Tamsta mažo raduot klaida pruogramėnė ironguo.
Prašuom aple šėtā paskelbtė [[Special:ListUsers/sysop|adminėstratoriō]], nepamėršdamė nuruodītė nūruoda.',
'missingarticle-rev'   => '(versėjė#: $1)',
'missingarticle-diff'  => '(Skėrt.: $1, $2)',
'readonly_lag'         => 'Doumenū bazė bova autuomatėškā ožrakėnta, kuol pagelbinės doumenū bazės pasvīs pagrėndine',
'internalerror'        => 'Vėdėnė klaida',
'internalerror_info'   => 'Vėdėnė klaida: $1',
'filecopyerror'        => 'Nepavīkst kopėjoutė faila ėš „$1“ i „$2“.',
'filerenameerror'      => 'Nepavīkst parvardėntė faila ėš „$1“ i „$2“.',
'filenotfound'         => 'Nepavīkst rastė faila „$1“.',
'fileexistserror'      => 'Nepavīkst irašītė i faila „$1“: tas fails jau īr',
'unexpected'           => 'Natėkieta raikšmie: „$1“=„$2“.',
'cannotdelete'         => 'Nepavīka ėštrintė nuruodīta poslapė a faila. (Mažo kažkas padarė pėrmesnis šėta)',
'badtitle'             => 'Bluogs pavadėnėms',
'badtitletext'         => 'Nuruodīts poslapė pavadėnėms bova neleistėns, toščės a neteisėngā sojongts terpkalbinis a terppruojektėnis pavadėnėms. Anamė gal būtė vėins a daugiau sėmbuoliu, neleistėnū pavadėnėmūs',
'perfcachedts'         => 'Ruodoma ėšsauguota doumenū kopėjė, katra bova atnaujėnta $1.',
'querypage-no-updates' => 'Atnaujėnėmā tam poslapiō nūnā ėšjongtė īr. Doumenīs nūnā čė nebus atnaujėntė.',
'wrong_wfQuery_params' => 'Netaisingė parametrā i funkcėjė wfQuery()<br />
Funkcėjė: $1<br />
Ožklausėms: $2',
'viewsource'           => 'Veizėtė kuoda',
'viewsourcefor'        => 'poslapiō $1',
'protectedpagetext'    => 'Šėts poslapis īr ožrakints, saugont anū nū redagavėma.',
'viewsourcetext'       => 'Tomsta galėt veizietė ėr kopėjoutė poslapė kuoda:',
'protectedinterface'   => 'Šėtom poslapi īr pruogramėnės ironguos sasajuos teksts katros īr apsauguots, kū neprietelē anū nasogadėntu.',
'editinginterface'     => "'''Diemesė:''' Tamsta keitat poslapi, katros īr nauduojams programėnės irongas sōsajės tekstė. Pakeitėmā tamė poslapū tēpuogi pakeis nauduotuojė sōsajės ėšruoda ė kėtėims nauduotujams. Jēgo nuorėt pargoldītė, siūluom pasėnauduotė [http://translatewiki.net/wiki/Main_Page?setlang=bat-smg „translatewiki.net“], „MediaWiki“ lokalėzacėjės pruojėktu.",
'sqlhidden'            => '(SQL ožklausa pakavuota)',
'namespaceprotected'   => "Tamsta netorėt teisiu keistė poslapiu '''$1''' srėtī.",
'ns-specialprotected'  => 'Specēlė̅ jė poslapē negal būtė keitamė.',

# Login and logout pages
'logouttext'                 => "'''Daba Tamsta esat atsėjongės.'''

Galėt ė tuoliau nauduotė {{SITENAME}} anuonimėškā aba prisėjonkėt ėš naujė šėtuo patiu a kėto nauduotuojė vardu.
Pastebiejims: katruos nekatruos poslapiuos ė tuoliau gal ruodītė būktā būtomiet prisėjongės lėgė tuol, kumet ėšvalīsėt sava naršīklės dietovė (''cache'').",
'welcomecreation'            => '== Svēkė, $1! ==

Tamstas paskīra bova sokorta. Neožmėrškėt pakeistė sava {{SITENAME}} nustatīmu.',
'yourname'                   => 'Nauduotuojė vards:',
'yourpassword'               => 'Slaptažuodis:',
'yourpasswordagain'          => 'Pakartuoket slaptažuodė:',
'remembermypassword'         => 'Atmintė šėta infuormacėjė šėtom kuompioteri(for a maximum of $1 {{PLURAL:$1|day|days}})',
'yourdomainname'             => 'Tamstas domens:',
'login'                      => 'Prisėjongtė',
'nav-login-createaccount'    => 'Prėsėjongtė / sokortė paskīra',
'loginprompt'                => 'Ijonkėt pakavukus, jēgo nuorėt prisėjongtė pri {{SITENAME}}.',
'userlogin'                  => 'Prėsėjongtė / sokortė paskīra',
'logout'                     => 'Atsėjongtė',
'userlogout'                 => 'Atsėjongtė',
'notloggedin'                => 'Neprisėjongis',
'nologin'                    => "Netorėt prisėjongėma varda? '''$1'''.",
'nologinlink'                => 'Sokorkėt paskīra',
'createaccount'              => 'Sokortė paskīra',
'gotaccount'                 => "Jau torėt paskīra? '''$1'''.",
'gotaccountlink'             => 'Prisėjonkėt',
'badretype'                  => 'Ivestė slaptažuodē nesotamp.',
'userexists'                 => 'Irašīts nauduotuojė vards jau īr nauduojams.
Prašuom pasėrėnktė kėtuoki varda.',
'loginerror'                 => 'Prisėjongėma klaida',
'nocookiesnew'               => 'Nauduotuojė paskīra bova sokurta, ale Tamsta nēsot prisėjongis. {{SITENAME}} nauduo pakavukus, kū prijongtu nauduotuojus. Tamsta esot ėšjongis anūs. Prašuom ijongtė pakavukus, tumet prisėjonkėt so sava nauju nauduotuojė vardu ė slaptažuodiu.',
'nocookieslogin'             => "Vikipedėjė nauduo pakavukus (''cookies''), kū prijongtu nauduotuojus. Tamsta esat ėšjongės anūs. Prašuom ijongtė pakavukus ė pamiegītė viel.",
'loginsuccesstitle'          => 'Siekmingā prisėjongiet.',
'loginsuccess'               => "'''Nūnā Tamsta esot prisėjongės pri {{SITENAME}} kāp „$1“.'''",
'nosuchuser'                 => 'Nier anėjuokė nauduotuojė pavadėnta „$1“.
Patikrėnkėt rašība, aba [[Special:UserLogin/signup|sokorkėt naujė paskīra]].',
'nosuchusershort'            => 'Nier juokė nauduotuojė, pavadėnta „$1“. Patėkrinkėt rašība.',
'nouserspecified'            => 'Tamstā rēk nuruodītė nauduotuojė varda.',
'wrongpassword'              => 'Ivests neteisings slaptažuodis. Pameginket dā karta.',
'wrongpasswordempty'         => 'Ivests slaptažuodis īr tošts. Pameginket vielėk.',
'passwordtooshort'           => 'Tamstas slaptažuodis nier laistėns aba par tromps īr. Ans tor būtė nuors {{PLURAL:$1|1 sėmbuolė|$1 sėmbuoliu}} ėlgoma ė skėrtės nū Tamstas nauduotuojė varda.',
'mailmypassword'             => 'Atsiōstė naujė slaptažuodi pašto',
'passwordremindertitle'      => 'Laikėns {{SITENAME}} slaptažuodis',
'passwordremindertext'       => 'Kažkastā (tėkriausē Tamsta, IP adreso $1)
paprašė, kū atsiōstomiet naujė slaptažuodi pruojektō {{SITENAME}} ($4).
Laikėns slaptažuodis nauduotuojō „$2“ bova sokorts ėr nustatīts kāp „$3“.
Jēgo Tamsta nuoriejot ana pakeistė tūmet torietomiet prisėjongtė ė daba pakeistė sava slaptažuodi.

Jēgo kažkas kėts atlėka ta prašīma aba Tamsta prisėmėniet sava slaptažuodi ė
nebnuorėt ana pakeistė, Tamsta galėt tėisiuog nekreiptė diemiesė ė šėta gruomata ė tuoliau
nauduotis sava senu slaptažuodžiu.',
'noemail'                    => 'Nier anėjuokė el. pašta adresa ivesta nauduotuojō „$1“.',
'passwordsent'               => 'Naus slaptažuodis bova nusiōsts i el. pašta adresa,
ožregėstrouta nauduotuojė „$1“.
Prašuom prisėjongtė vielē, kumet Tamsta gausėt anū.',
'blocked-mailpassword'       => 'Tamstas IP adresos īr ožblokouts nū redagavėma, tudie neleidama nauduotė slaptažuodė priminėma funkcėjės, kū apsėsauguotomė nū pėktnaudžēvėma.',
'eauthentsent'               => 'Patvėrtėnėma gruomata bova nusiōsta i paskėrta el. pašta adresa.
Prīš ėšsiontiant kėta gruomata i Tamstas diežote, Tamsta torėt vīkdītė nuruodīmus gruomatuo, kū patvėrtėntomiet, kū diežotė tėkrā īr Tamstas.',
'throttled-mailpassword'     => 'Slaptažuodžė priminims jau bova ėšsiōsts, par paskotėnes $1 adīnas. Nuorint apsėsauguotė nū pėktnaudžēvėma, slaptažuodė priminims gal būt ėšsiōsts tėk kas $1 adīnas.',
'mailerror'                  => 'Klaida siontiant pašta: $1',
'acct_creation_throttle_hit' => 'Tamsta jau sokūriet $1 prisėjongėma varda. Daugiau nebgalėma.',
'emailauthenticated'         => 'Tamstas el. pašta adresos bova ožtvirtėnts $1.',
'emailnotauthenticated'      => 'Tamstas el. pašta adresos da nier patvėrtėnts. Anėjuokės gruomatas
nebus siontamas ni vėinam žemiau ėšvardėntam puoslaugiō.',
'noemailprefs'               => 'Nuruodėkīt el. pašta adresa, kū vėiktu šėtos funkcėjės.',
'emailconfirmlink'           => 'Patvėrtinkėt sava el. pašta adresa',
'accountcreated'             => 'Nauduotuos sokorts',
'accountcreatedtext'         => 'Nauduotuos $1 sokorts.',
'createaccount-title'        => '{{SITENAME}} paskīruos kūrėms',
'loginlanguagelabel'         => 'Kalba: $1',

# Password reset dialog
'resetpass'               => 'Keistė slaptažuodi',
'resetpass_header'        => 'Keistė paskīruos slaptažuodi',
'oldpassword'             => 'Sens slaptažuodis:',
'newpassword'             => 'Naus slaptažuodis:',
'retypenew'               => 'Pakartuokėt nauja slaptažuodi:',
'resetpass_submit'        => 'Nostatītė slaptažuodi ė prėsėjongtė',
'resetpass_success'       => 'Tamstas slaptažuodis pakeists siekmėngā! Daba prėsėjongiama...',
'resetpass-temp-password' => 'Laikėns slaptažuodis:',

# Edit page toolbar
'bold_sample'     => 'Pastuorints teksts',
'bold_tip'        => 'Pastuorintė teksta',
'italic_sample'   => 'Teksts kursīvu',
'italic_tip'      => 'Teksts kursīvu',
'link_sample'     => 'Nūruodas pavadinėms',
'link_tip'        => 'Vėdinė nūruoda',
'extlink_sample'  => 'http://www.example.com nūruodas pavadėnėms',
'extlink_tip'     => 'Ėšuorėnė nūruoda (nepamėrškėt http:// priraša)',
'headline_sample' => 'Skīrė pavadėnėms',
'headline_tip'    => 'Ontra līgė skīrė pavadėnėms',
'math_sample'     => 'Iveskėt fuormolė',
'math_tip'        => 'Matematinė fuormolė (LaTeX fuormato)',
'nowiki_sample'   => 'Iterpkėt nefuormouta teksta čė',
'nowiki_tip'      => 'Ėgnoroutė wiki fuormata',
'image_sample'    => 'Pavīzdīs.jpg',
'image_tip'       => 'Idietė abruozdieli',
'media_sample'    => 'Pavīzdīs.ogg',
'media_tip'       => 'Nūruoda i media faila',
'sig_tip'         => 'Tomstas parašos ėr čiesos',
'hr_tip'          => 'Guorizuontali linėjė (nenauduokėt ba reikala)',

# Edit pages
'summary'                          => 'Kuomentars:',
'subject'                          => 'Tema/ontraštė:',
'minoredit'                        => 'Mažos pataisims',
'watchthis'                        => 'Keravuotė šėta poslapė',
'savearticle'                      => 'Ėšsauguotė poslapė',
'preview'                          => 'Parveiza',
'showpreview'                      => 'Ruodītė parveiza',
'showlivepreview'                  => 'Tėisiuogėnė parvaiza',
'showdiff'                         => 'Ruodītė skėrtomus',
'anoneditwarning'                  => "'''Diemesė:''' Tomsta nesat prisėjungės. Jūsa IP adresos būs irašīts i šiuo poslapė istuorėjė.",
'missingsummary'                   => "'''Priminėms:''' Tamsta nenuruodiet pakeitėma kuomentara. Jēgo viel paspausėt ''Ėšsauguotė'', Tamstas pakeitėms bus ėšsauguots ba anuo.",
'missingcommenttext'               => 'Prašuom ivestė kuomentara.',
'summary-preview'                  => 'Kuomentara parvaiza:',
'subject-preview'                  => 'Skėrsnelė/ontraštės parvaiza:',
'blockedtitle'                     => 'Nauduotuos īr ožblokouts',
'blockedtext'                      => "'''Tamstas nauduotuojė vards a IP adresos īr ožblokouts.'''

Ožbluokava $1.
Nuruodīta prižastis īr ''$2''.

* Bluokavėma pradžia: $8
* Bluokavėma pabenga: $6
* Numatīts bluokoujamasės: $7

Tamsta galėt sosėsėiktė so $1 a kėtu
[[{{MediaWiki:Grouppage-sysop}}|adminėstratuoriom]], kū aptartė ožbluokavėma.
Tamsta negalėt nauduotės funkcėjė „Rašītė laiška tam nauduotuojō“, jēgo nesot pateikis tėkra sava el. pašta adresa sava [[Special:Preferences|paskīruos nustatīmūs]] ė nesot ožblokouts nu anuos nauduojėma.
Tamstas dabartėnis IP adresos īr $3, a bluokavėma ID īr #$5. Prašuom nuruodītė šėtā, kumet kreipiatės diel atbluokavėma.",
'autoblockedtext'                  => "Tamstas IP adresos bova autuomatėškā ožblokouts, kadongi ana nauduojė kėts nauduotuos, katra ožbluokava $1.
Nuruodīta prīžastis īr tuokė:

:''$2''

* Bluokavėma pradžė: $8
* Bluokavėma pabenga: $6
* Numatuoms bluokavėma čiesos: $7

Tamsta galėt sosėsėiktė so $1 aba kėtu [[{{MediaWiki:Grouppage-sysop}}|adminėstratuoriom]], kū aprokoutomėt biedas diel bluokavėma.

Tamsta negalėt nauduotės fonkcėjė „Rašītė gruomata tam nauduotuojō“, jēgo nesot nuruode tėkra el. pašta adresa sava [[Special:Preferences|nauduotuojė nustatīmūs]]. Tēpuogi Tamsta negalat nauduotės ta fonkcėjė, jēgo Tamstā ožblokouts anuos nauduojėms.

Tamstas IP adresos īr $3, bluokavėma ID īr $5.
Prašuom nuruodītė šėtūs doumenis visūmet, kumet kreipiatės diel bluokavėma.",
'blockednoreason'                  => 'prīžastis nier nuruodīta',
'blockedoriginalsource'            => "Žemiau īr ruodoms '''$1''' torėnīs:",
'blockededitsource'                => "''Tamstas keitimu'' teksts poslapiui '''$1''' īr ruodoms žemiau:",
'whitelistedittitle'               => 'Nuorėnt redagoutė rēk prisėjongtė',
'whitelistedittext'                => 'Tamsta torėt $1, kū keistomėt poslapius.',
'nosuchsectiontitle'               => 'Nier tuokė skīrė',
'loginreqlink'                     => 'prisėjongtė',
'accmailtitle'                     => 'Slaptažuodis ėšsiūsts īr.',
'accmailtext'                      => "Nauduotuojė '$1' slaptažuodis nusiūsts i $2 īr.",
'newarticle'                       => '(Naus)',
'newarticletext'                   => "Tamsta pakliovuot i nūnā neesoti poslapi.
Nuoriedamė sokortė poslapi, pradiekėt rašītė žemiau esontiamė ivedima pluotė
(platiau [[{{MediaWiki:Helppage}}|pagelbas poslapī]]).
Jēgo pakliovuot čė netīčiuom, paprastiausē paspauskėt naršīklės mīgtoka '''atgal'''.",
'anontalkpagetext'                 => "----''Tas īr anonimėnė nauduotuojė, katros nier sosėkūrės aba nenauduo paskīruos, aptarėmu poslapis.
Dielē tuo nauduojams IP adresos anuo atpažėnėmō.
Tas IP adresos gal būtė dalinams keletō nauduotuoju.
Jēgo Tamsta esat anonimėnis nauduotuos ėr veizėt, kū kuomentarā nier skėrtė Tamstā, [[Special:UserLogin/signup|sokorkėt paskīra]] aba [[Special:UserLogin|prisėjonkėt]], ė nebūsėt maišuoms so kėtās anonimėnēs nauduotuojās.''",
'noarticletext'                    => 'Tuo čiesu tamė poslapī nier juokė teksta.
Tamsta galėt [[Special:Search/{{PAGENAME}}|ėiškuotė šėta poslapė pavadėnėma]] kėtūs poslapiūs,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ėiškuotė sosėjosiu regėstru],
aba [{{fullurl:{{FULLPAGENAME}}|action=edit}} keistė ta poslapi].',
'userpage-userdoesnotexist'        => 'Nauduotuojė paskīra „$1“ nier ožregėstrouta. Prašuom patikrėntė, a Tamsta nuorėt kortė/keistė ta poslapi.',
'clearyourcache'                   => "'''Diemesė:''' ėšsauguojus Tamstā gal prireiktė ėšvalītė Tamstas naršīklės rėnktovė, kū paveizėtomėt pakeitėmus. '''Mozilla / Safari / Konqueror:''' laikīdami ''Shift'' pasėrinkėt ''Atsiōstė ėš nauja'', a paspauskėt ''Ctrl-Shift-R'' (sėstemuo Apple Mac ''Cmd-Shift-R''); '''IE:''' laikīdamė ''Ctrl'' paspauskėt ''Atnaujėntė'', o paspauskėt ''Ctrl-F5''; '''Konqueror:''' paprastiausē paspauskėt ''Perkrautė'' mīgtoka, o paspauskėt ''F5''; '''Opera''' nauduotuojam gal prireiktė pėlnā ėšvalītė anū rėnktovė ''Rakondā→Nustatīmā''.",
'usercssyoucanpreview'             => "'''Patarėms:''' Nauduokit „Ruodītė parvaiza“ mīgtoka, kū ėšmiegintomiet sava naujaji CSS priš ėšsaugont.",
'userjsyoucanpreview'              => "'''Patarėms:''' Nauduokit „Ruodītė parvaiza“ mīgtoka, kū ėšmiegintomiet sava naujaji JS priš ėšsaugont.",
'usercsspreview'                   => "'''Napamirškėt, kū Tamsta tėk parveizėt sava nauduotoja CSS, ans da nabova ėšsauguots!'''",
'userjspreview'                    => "'''Nepamirškėt, kū Tamsta tėk testoujat/parvaizėt sava nauduotoja ''JavaScript'', ans da nabova ėšsauguots!'''",
'userinvalidcssjstitle'            => "'''Diemesė:''' Nė juokės ėšruodos „$1“. Napamirškėt, kū sava .css ėr .js poslapē nauduo pavadėnėma mažuosiomės raidiemis, pvz., Nauduotuos:Foo/monobook.css, o ne Nauduotuos:Foo/Monobook.css.",
'updated'                          => '(Atnaujėnta)',
'note'                             => "'''Pastebiejims:'''",
'previewnote'                      => "'''Nepamėrškėt, kū tas tėktās pervaiza, pakeitėmā da nier ėšsauguotė!'''",
'previewconflict'                  => 'Šėta parvaiza paruod teksta ėš vėršotinėjė teksta redagavėma lauka tēp, kāp ans bus ruodoms, jei pasirinksėt anū ėšsauguotė.',
'session_fail_preview'             => "'''Atsiprašuom! Mes nagalėm vīkdītė Tamstas keitėma diel sesėjės doumenū praradima.
Prašuom pamiegintė vielēk. Jei šėtā napaded, pamieginkėt atsėjongtė ėr prėsėjongtė atgal.'''",
'session_fail_preview_html'        => "'''Atsėprašuom! Mes nagalėm apdoroutė Tamstas keitėma diel sesėjės doumenū praradėma.'''
''Kadaogi šėtom pruojekte grīnasės HTML īr ijongts, parveiza īr pasliepta kāp atsargoma prėimonė priš JavaScript atakas.''
'''Jei tā teisiets keitėma bandīms, prašuom pamiegint viel. Jei šėtā napaded, pamieginkėt atsėjongtė ėr prėsėjongtė atgal.'''",
'editing'                          => 'Taisuoms straipsnis - $1',
'editingsection'                   => 'Taisuoms $1 (skėrsnelis)',
'editingcomment'                   => 'Taisuoms $1 (naus skīrius)',
'editconflict'                     => 'Ėšpreskėt kuonflėkta: $1',
'explainconflict'                  => "Kažėn kas kėts jau pakeitė poslapi nū tuo čiesa, kumet Tamsta pradiejuot ana redagoutė.
Vėršotėniamė tekstėniamė laukė pateikta šėtu čiesu esontė poslapė versėjė.
Tamstas pakeitėmā pateiktė žemiau esontiamė laukė.
Tamstā rēk sojongtė Tamstas pakeitėmus so esontė versėjė.
Kumet paspausėte „Irašītė“, bus irašīts '''tėktās''' teksts vėršotėniam tekstėniam laukė.",
'yourtext'                         => 'Tamstas teksts',
'storedversion'                    => 'Ėšsauguota versėjė',
'editingold'                       => "'''ISPIEJIMS: Tamsta keitat ne naujausė poslapė versėjė.
Jēgo ėšsauguosėt sava pakeitėmus, paskum darītė pakeitėmā prapols.'''",
'yourdiff'                         => 'Skėrtomā',
'copyrightwarning'                 => "Primenam, kū vėsks, kas patenk i {{SITENAME}}, īr laikuoma pavėišėnto palē $2 (platiau - $1). Jēgo nenuorit, kū Tamstas duovis būtou ba pasėgailiejėma keitams ė platėnams, nerašīkėt čė.<br />
Tamsta tēpuogi pasėžadat, kū tas īr Tamstas patėis rašīts torėnīs a kuopėjouts ėš vėišū a panašiū valnū šaltėniu.
'''NEKOPĖJOUKĖT AUTUORĖNIEM TEISIEM APSAUGUOTU DARBŪ BA LEIDĖMA!'''",
'copyrightwarning2'                => "Primenam, kū vėsks, kas patenk i {{SITENAME}} gal būtė keitama, perdaruoma, a pašalėnama kėtū nauduotuoju. Jēgo nenuorėt, kū Tamstas duovis būtu ba pasėgailiejėma keitams, čiuonās nerašīkėt.<br />
Tēpuogi Tamsta pasėžadat, kū tas īr Tamstas rašīts teksts aba kuopėjouts
ėš vėišū liousū šaltėniu (detaliau - $1).
'''NEKUOPĖJOUKAT AUTUORĖNIEM TEISIEM APSAUGUOTU DARBŪ BA LEIDĖMA!'''",
'longpagewarning'                  => "'''DIEMESĖ: Tas poslapis īr $1 kilobaitu ėlgoma; katruos nekatruos
naršīklės gal torietė biedū redagounant poslapius bavēk a vėrš 32 kB.
Prašuom pamiegītė poslapi padalėntė i keleta smolkesniū daliū.'''",
'readonlywarning'                  => "'''DIEMESĖ: Doumenū bazė bova ožrakėnta teknėnē pruofilaktėkā,
tudie negaliesėt ėšsauguotė sava pakeitėmu daba. Tamsta galėt nosėkopėjoutė teksta i tekstėni faila
ė paskum ikeltė ana čė.'''",
'protectedpagewarning'             => "'''DIEMESĖ: Šėts poslapis īr ožrakints ėr anū redagoutė gal tėk admėnėstratuorė teises torėntīs prietelē.'''",
'semiprotectedpagewarning'         => "'''Pastebiejėms:''' Šėts poslapis bova ožrakėnts ėr anuo gal redagoutė tėk regėstroutė nauduotojā.",
'titleprotectedwarning'            => "'''DIEMESĖ: Tas poslapis bova ožrakėnts tēp, ka tėktās kāpkatrė nauduotuojē galietu ana sokortė.'''",
'templatesused'                    => '{{PLURAL:$1|Šabluons|Šabluonā}}, katrėi īr nauduojamė poslapī:',
'templatesusedpreview'             => '{{PLURAL:$1|Šabluons|Šabluonā}}, nauduotė šėtuo parvaizuo:',
'templatesusedsection'             => 'Šabluonā, nauduotė šėtom skėrsnelī:',
'template-protected'               => '(apsauguots)',
'template-semiprotected'           => '(posiau apsauguots)',
'hiddencategories'                 => 'Tas poslapis prėklausa $1 {{PLURAL:$1|pakavuotā kateguorėjē|pakavuotoms kateguorėjėms|pakavuotu kateguorėju}}:',
'nocreatetitle'                    => 'Poslapiu kūrims aprėbuots',
'nocreatetext'                     => '{{SITENAME}} aprėbuojė galėmībe kortė naujus poslapius.
Tamsta galėt grīžtė ė redagoutė nūnā esonti poslapi, a [[Special:UserLogin|prėsėjongtė a sokortė paskīra]].',
'permissionserrors'                => 'Teisiu klaida',
'permissionserrorstext'            => 'Tamsta netorėt teisiu šėta darītė diel {{PLURAL:$1|tuos prīžastėis|tū prīžastiū}}:',
'permissionserrorstext-withaction' => 'Tamsta netorėt leidėma $2 dielē {{PLURAL:$1|tos prīžastėis|tū prīžastiu}}:',
'recreate-moveddeleted-warn'       => "'''Diemesė: Tomsta atkoriat poslapi, katros onkstiau bova ėštrints.'''

Tomsta torėt nosprēst, a pritėnk tuoliau redagoutė šėta poslapi.
Šėta poslapė šalėnėmu istuorėjė īr pateikta patuogoma vardan:",
'moveddeleted-notice'              => 'Tas poslapis bova ėštrėnts.
Ėštrėnta poslapė versėju sārašos īr pateikts paveiziejėmō žemiau.',
'edit-conflict'                    => 'Redagavėma kuonflėktos',

# "Undo" feature
'undo-success' => 'Keitėms gal būtė atšaukts. Prašuom patėkrėntė palīgėnėma, asonti žemiau, kū patvėrtėntomiet, kū Tamsta šėta ė nuorėt padarītė, ė tumet ėšsauguokit pakeitėmos, asontios žemiau, kū ožbėngtomiet keitėma atšaukėma.',
'undo-failure' => 'Keitėms nagal būt atšaukts diel konflėktounantiu tarpėniu pakeitėmu.',
'undo-summary' => 'Atšauktė [[Special:Contributions/$2|$2]] ([[User talk:$2|Aptarėms]]) versėje $1',

# Account creation failure
'cantcreateaccount-text' => "Paskīrū kūrėma ėš šėta IP adresa ('''$1''') ožbluokava [[User:$3|$3]].

$3 nuruodīta prīžastis īr ''$2''",

# History pages
'viewpagelogs'           => 'Ruodītė šėtuo poslapė specēliōsios vaiksmos',
'nohistory'              => 'Šėts poslapis netor keitėmu istuorėjės.',
'currentrev'             => 'Dabartėnė versėjė',
'currentrev-asof'        => 'Dabartėnė $1 versėjė',
'revisionasof'           => '$1 versėjė',
'revision-info'          => '$1 versėjė nauduotuojė $2',
'previousrevision'       => '←Onkstesnė versėjė',
'nextrevision'           => 'Paskesnė versėjė→',
'currentrevisionlink'    => 'Dabartėnė versėjė',
'cur'                    => 'dab',
'next'                   => 'kėts',
'last'                   => 'pask',
'page_first'             => 'pėrm',
'page_last'              => 'pask',
'histlegend'             => "Skėrtomā terp versėju: pažīmiekit līginamas versėjės ė spauskėt ''Enter'' klavėša a mīgtuka apatiuo.<br />
Žīmiejimā: (dab) = palīginims so vielibiausė versėjė,
(pask) = palīginims so priš ta bovosia versėjė, S = mažos pataisims.",
'history-fieldset-title' => 'Naršītė istuorėjuo',
'histfirst'              => 'Seniausė',
'histlast'               => 'Vielibė̅jė',
'historysize'            => '($1 {{PLURAL:$1|baits|baitā|baitu}})',
'historyempty'           => '(nieka nier)',

# Revision feed
'history-feed-title'          => 'Versėju istuorėjė',
'history-feed-item-nocomment' => '$1 $2',
'history-feed-empty'          => 'Prašuoms poslapis nēgzėstuo.
Ans galiejė būtė ėštrėnts ėš pruojekta, aba parvardėnts.
Pamiegīkėt [[Special:Search|ėiškoutė pruojektė]] sosėjosiu naujū poslapiu.',

# Revision deletion
'rev-delundel'              => 'ruodītė/kavuotė',
'revisiondelete'            => 'Trintė/atkortė versėjės',
'logdelete-selected'        => "{{PLURAL:$2|Pasėrinkts|Pasėrinktė|Pasėrinktė}} '''$1''' istuorėjės {{PLURAL:$2|atėtėkims|atsėtėkimā|atsėtėkimā}}:",
'revdelete-text'            => "'''Ėštrintuos versėjės ėr ivīkē vistėik da bus ruodomė poslapė istuorėjuo ėr specēliūju veiksmū istuorėjuo, no anū torėnė dalīs nabus vėišā pasėikiamos.'''
Kėtė admėnėstratuorē šėtom pruojekte vėsdar galės pasėiktė pasliepta torėni ėr galės ana atkortė viel par šėta pate sasaja, nabent īr nostatītė papėlduomė aprėbuojėmā.",
'revdelete-unsuppress'      => 'Šalėntė apribuojėmos atkortuos versėjės',
'logdelete-logentry'        => 'pakeists [[$1]] atsėtėkima veiziemoms',
'revdel-restore'            => 'Keistė veizėmuma',
'revdelete-edit-reasonlist' => 'Keistė trīnėma prīžastis',

# Suppression log
'suppressionlog' => 'Trīnėma istuorėjė',

# History merging
'mergehistory-success' => '$3 [[:$1]] versėju siekmėngā sojongta so [[:$2]].',

# Merge log
'revertmerge' => 'Atskėrtė',

# Diffs
'history-title'           => 'Poslapė „$1“ istuorėjė',
'difference'              => '(Skėrtomā terp versėju)',
'lineno'                  => 'Eilotė $1:',
'compareselectedversions' => 'Palīgintė pasėrinktas versėjės',
'editundo'                => 'atšauktė',
'diff-multi'              => '($1 {{PLURAL:$1|tarpėnis keitėms nier ruoduoms|tarpėnē keitėmā nier ruoduomė|tarpėniu keitėmu nier ruoduoma}}.)',

# Search results
'searchresults'                    => 'Paėiškuos rezoltatā',
'searchresults-title'              => 'Paėiškuos rezoltatā "$1"',
'searchresulttext'                 => 'Daugiau infuormacėjės aple paėiška pruojektė {{SITENAME}} rasėt [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Tamsta ėiškuojot \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|vėsė poslapē katrėi prasėded so "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|vėsė poslapē katrėi ruod i "$1"]])',
'searchsubtitleinvalid'            => 'Jėškuom „$1“',
'titlematches'                     => 'Straipsniu pavadėnėmu atitėkmenīs',
'notitlematches'                   => 'Juokiū pavadinėma atitikmenū',
'textmatches'                      => 'Poslapė torėnė atėtikmenīs',
'notextmatches'                    => 'Juokiū poslapė teksta atitikmenū',
'prevn'                            => 'onkstesnius {{PLURAL:$1|$1}}',
'nextn'                            => 'paskesnius {{PLURAL:$1|$1}}',
'viewprevnext'                     => 'Veizėtė ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Paėiškuos nustatīmā',
'searchmenu-exists'                => "'''Poslapis pavadėnts „[[$1]]“ šėtuo wiki'''",
'searchmenu-new'                   => "'''Sokortė poslapi „[[:$1]]“ šėtuo wiki!'''",
'searchhelp-url'                   => 'Help:Torėnīs',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Ėiškuotė poslapiu so šėtuom prīšdielio]]',
'searchprofile-articles'           => 'Torėnė poslapē',
'searchprofile-project'            => 'Pruojėkta poslapē',
'searchprofile-images'             => 'Failā',
'searchprofile-everything'         => 'Vėsks',
'searchprofile-advanced'           => 'Prapliesta',
'searchprofile-articles-tooltip'   => 'Ėiškuotė čiuonās: $1',
'searchprofile-project-tooltip'    => 'Ėiškuotė čiuonās: $1',
'searchprofile-images-tooltip'     => 'Ėiškuotė failu',
'searchprofile-everything-tooltip' => 'Ėiškuotė vėsuo torėnė (tuom patėm ėr aptarėma poslapiu)',
'search-result-size'               => '$1 ({{PLURAL:$2|1 žuodis|$2 žuodē|$2 žuodiu}})',
'search-result-score'              => 'Tėnkamoms: $1%',
'search-redirect'                  => '(paradresavėms $1)',
'search-section'                   => '(skīrios $1)',
'search-suggest'                   => 'Mažo nuoriejot $1',
'search-interwiki-caption'         => 'Dokterėnē pruojektā',
'search-interwiki-default'         => '$1 rezoltatā:',
'search-interwiki-more'            => '(daugiau)',
'search-mwsuggest-enabled'         => 'so pasiūlīmās',
'search-mwsuggest-disabled'        => 'nie pasiūlīmu',
'search-relatedarticle'            => 'Sosėjėn',
'mwsuggest-disable'                => 'Kavuotė AJAX pasiūlīmus',
'searchrelated'                    => 'sosėjėn',
'searchall'                        => 'vėsė',
'showingresults'                   => "Žemiau ruodoma lėgė '''$1''' {{PLURAL:$1|rezoltata|rezoltatu|rezoltatu}} pradedont #'''$2'''.",
'showingresultsnum'                => "Žemiau ruodoma '''$3''' {{PLURAL:$3|rezoltata|rezoltatu|rezoltatu}} pradedant #'''$2'''.",
'nonefound'                        => "'''Pastebiejėms''': Palē nutīliejėma ėiškuoma tėktās kāp katruosė vardū srėtīsė. Pamiegīkėt prirašītė prėišdieli ''all:'', jēgo nuorėt ėiškiuotė vėsa torėnė (tamė tarpė aptarėma poslapius, šabluonus ė tēp tuoliau), aba nauduokėt nuorėma vardū srėti kāp prėišdieli.",
'search-nonefound'                 => 'Nier rezoltatu, katrėi atitėktu ožklausėma.',
'powersearch'                      => 'Ėiškuotė',
'powersearch-legend'               => 'Prapliesta paėiška',
'powersearch-ns'                   => 'Ėiškoutė vardū srėtīsė:',
'powersearch-redir'                => 'Itrauktė paradresavėmus',
'powersearch-field'                => 'Ėiškoutė',
'search-external'                  => 'Ėšuorėnė paėiška',

# Quickbar
'qbsettings'      => 'Greitasā pasėrėnkėms',
'qbsettings-none' => 'Neruodītė',

# Preferences page
'preferences'               => 'Nustatīmā',
'mypreferences'             => 'Mona nustatīmā',
'prefs-edits'               => 'Keitėmu skaitlius:',
'prefsnologin'              => 'Naprisėjongis',
'prefsnologintext'          => 'Tamstā rēk būtė <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} prisėjongosam]</span>, kū galietomiet keistė sava nustatīmus.',
'changepassword'            => 'Pakeistė slaptažuodė',
'prefs-skin'                => 'Ėšruoda',
'skin-preview'              => 'Parveiza',
'prefs-math'                => 'Matematėka',
'datedefault'               => 'Juokė pasėrėnkėma',
'prefs-datetime'            => 'Data ė čiesos',
'prefs-personal'            => 'Nauduotuojė pruopilis',
'prefs-rc'                  => 'Vielībė̅jė pakeitėmā',
'prefs-watchlist'           => 'Keravuojamu sārašos',
'prefs-watchlist-days'      => 'Kėik dėinū ruodītė keravuojamu sārašė:',
'prefs-watchlist-days-max'  => '(daugiausē 7 dėinas)',
'prefs-watchlist-edits'     => 'Kėik pakeitėmu ruodītė ėšpliestiniam keravuojamu sārašė:',
'prefs-watchlist-edits-max' => '(dėdliausias skaitlius: 1000)',
'prefs-misc'                => 'Ivairė nustatīmā',
'prefs-resetpass'           => 'Keistė slaptažuodi',
'saveprefs'                 => 'Ėšsauguotė',
'resetprefs'                => 'Atstatītė nostatīmos',
'restoreprefs'              => 'Atstatītė vėsus numatītūsius nustatīmus',
'prefs-editing'             => 'Redagavėms',
'prefs-edit-boxsize'        => 'Redagavėma longa dėdoms.',
'rows'                      => 'Eilotės:',
'columns'                   => 'Štolpalē:',
'searchresultshead'         => 'Paėiškuos nostatīmā',
'resultsperpage'            => 'Rezoltatu poslapie:',
'contextlines'              => 'Eilotiu rezoltatė:',
'contextchars'              => 'Konteksta sėmbuoliu eilotie:',
'stub-threshold'            => 'Minimums <a href="#" class="stub">nabėngta poslapė</a> fuormatavėmō:',
'recentchangesdays'         => 'Ruodomas dėinas vielībūju pakeitėmu sārašė:',
'recentchangesdays-max'     => '(daugiausē $1 {{PLURAL:$1|dėina|dėinū|dėinas}})',
'recentchangescount'        => 'Numatītasā keitėmu skaitlius, ruodoms vielībūju keitėmu, poslapiu istuorėjėsė ė notėkėmu sarašūsė:',
'savedprefs'                => 'Nostatīmā siekmėngā ėšsauguotė.',
'timezonelegend'            => 'Čiesa zuona',
'localtime'                 => 'Vėitinis čiesos:',
'timezoneuseserverdefault'  => 'Nauduotė palē nutīliejėma ėš serverė',
'timezoneuseoffset'         => 'Kėta (patikslėntė skėrtoma)',
'timezoneoffset'            => 'Skėrtoms¹:',
'servertime'                => 'Serverė čiesos:',
'guesstimezone'             => 'Paimtė ėš naršīklės',
'timezoneregion-africa'     => 'Afrėka',
'timezoneregion-america'    => 'Amerėka',
'timezoneregion-antarctica' => 'Antarktėda',
'timezoneregion-asia'       => 'Azėjė',
'timezoneregion-atlantic'   => 'Atlanta ondenīns',
'timezoneregion-australia'  => 'Australėjė',
'timezoneregion-europe'     => 'Euruopa',
'timezoneregion-indian'     => 'Indėjės ondenīns',
'timezoneregion-pacific'    => 'Ramosis ondenīns',
'allowemail'                => 'Lēstė siūstė el. gramuotelės ėš kėtū nauduotuoju',
'prefs-searchoptions'       => 'Paėiškuos nustatīmā',
'prefs-namespaces'          => 'Vardū srėtīs',
'defaultns'                 => 'Palē nutīliejėma ėiškuotė šėtuosė vardū srėtīsė:',
'default'                   => 'palē nūtīliejėma',
'prefs-files'               => 'Failā',
'youremail'                 => 'El. pašts:',
'username'                  => 'Nauduotuojė vards:',
'uid'                       => 'Nauduotuojė ID:',
'prefs-memberingroups'      => '{{PLURAL:$1|Gropės|Gropiu}} narīs:',
'yourrealname'              => 'Tėkros vards:',
'yourlanguage'              => 'Aplėnkuos kalba:',
'yourvariant'               => 'Variants',
'yournick'                  => 'Pasėrinkts slapīvardis:',
'badsig'                    => 'Neteisings parašas; patėkrinkėt HTML žīmės.',
'badsiglength'              => 'Tamstas parašos īr par ėlgs.
Ana gal sodarītė ne daugiau kāp $1 {{PLURAL:$1|sėmbuolis|sėmbuolē|sėmbuoliu}}.',
'yourgender'                => 'Lītis:',
'gender-unknown'            => 'Nier nuruodīta',
'gender-male'               => 'Vīrs',
'gender-female'             => 'Muoterėška',
'email'                     => 'El. pašts',
'prefs-help-realname'       => 'Tėkrs vards nier privaluoms, vuo jēgo Tamsta ana ivesėt, ons bus nauduojams Tamstas darba pažīmiejėmō.',
'prefs-help-email'          => 'El. pašta adresos nier privaloms, ale uns leid Tamstā gautė nauja slaptažuodi, jēgo pamėršuot kuoks uns bova, ė tēpuogi Tamsta galėt leistė kėtėims pasėiktė Tamsta par Tamstas nauduotuojė a nauduotuojė aptarėma poslapi neatsklėidont Tamstas tapatoma.',

# User rights
'userrights'               => 'Nauduotuoju teisiu valdīms',
'userrights-lookup-user'   => 'Tvarkītė nauduotuojė gropės',
'userrights-user-editname' => 'Iveskėt nauduotuojė varda:',
'editusergroup'            => 'Redagoutė nauduotuojė gropes',
'editinguser'              => "Taisuoms nauduotuos '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup' => 'Keistė nauduotuoju gropes',
'saveusergroups'           => 'Sauguotė nauduotuoju gropes',
'userrights-groupsmember'  => 'Narīs:',
'userrights-reason'        => 'Prīžastis:',

# Groups
'group'               => 'Gropė:',
'group-user'          => 'Nauduotuojē',
'group-autoconfirmed' => 'Autuomatėškā patvirtėntė nauduotuojē',
'group-bot'           => 'Buotā',
'group-sysop'         => 'Adminėstratuorē',
'group-bureaucrat'    => 'Biorokratā',
'group-all'           => '(vėsė)',

'group-user-member'       => 'Nauduotuos',
'group-bot-member'        => 'Buots',
'group-sysop-member'      => 'Adminėstratuorius',
'group-bureaucrat-member' => 'Biorokrats',

'grouppage-user'          => '{{ns:project}}:Nauduotuojē',
'grouppage-autoconfirmed' => '{{ns:project}}:Automatėškā patvėrtintė nauduotuojē',
'grouppage-bot'           => '{{ns:project}}:Robuotā',
'grouppage-sysop'         => '{{ns:project}}:Adminėstratuorē',
'grouppage-bureaucrat'    => '{{ns:project}}:Biorokratā',

# Rights
'right-read' => 'Skaitītė poslapius',
'right-edit' => 'Keistė poslapius',

# User rights log
'rightslog'      => 'Nauduotuoju teisiu istuorėjė',
'rightslogtext'  => 'Pateikiams nauduotuoju teisiu pakeitėmu sārašos.',
'rightslogentry' => 'pakeista $1 gropės narīstė ėš $2 i $3. Sveikėnam!',
'rightsnone'     => '(juokiū)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'       => 'redagoutė ta poslapi',
'action-undelete'   => 'atkortė ta poslapi',
'action-patrol'     => 'pažīmietė kėtū keitėmus kāp patikrėntus',
'action-userrights' => 'keistė vėsū nauduotuoju teises',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|pakeitėms|pakeitėmā|pakeitėmu}}',
'recentchanges'                     => 'Vielībė̅jė pakeitėmā',
'recentchanges-legend'              => 'Vielībuju pakeitėmu pasėrinkėmā',
'recentchangestext'                 => 'Tamė poslapī īr patīs vielībė̅ jė paketėmā tom pruojėktė.',
'recentchanges-feed-description'    => 'Keravuokėt patius vielībiausius pakeitėmus pruojektō tamė šaltėnī.',
'rcnote'                            => "Žemiau īr '''$1''' {{PLURAL:$1|paskotinis pakeitims|paskotinē pakeitimā|paskotiniu pakeitimu}} par $2 {{PLURAL:$2|paskotinė̅jė dėina|paskotėniasės '''$2''' dėinas|paskotėniuju '''$2''' dėinū}} skaitlioujant nū $4, $5.",
'rcnotefrom'                        => 'Žemiau īr pakeitėma pradedant nū <b>$2</b> (ruodom lėgė <b>$1</b> pakeitėmu).',
'rclistfrom'                        => 'Ruodītė naujus pakeitėmus pradedant nū $1',
'rcshowhideminor'                   => '$1 mažus pakeitėmus',
'rcshowhidebots'                    => '$1 robuotus',
'rcshowhideliu'                     => '$1 prėsėjongusiūm nauduotuojūm pakeitėmus',
'rcshowhideanons'                   => '$1 anuonimėnius nauduotuojus',
'rcshowhidepatr'                    => '$1 patikrėntus pakeitėmus',
'rcshowhidemine'                    => '$1 mona pakeitėmus',
'rclinks'                           => 'Ruodītė paskotėnius $1 pakeitėmu par paskotėnė̅sēs $2 dėinū<br />$3',
'diff'                              => 'skėrt',
'hist'                              => 'ist',
'hide'                              => 'Kavuotė',
'show'                              => 'Ruodītė',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'r',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|keravuojontis nauduotuos|keravuojontīs nauduotuojē|keravuojontiu nauduotuoju}}]',
'rc_categories'                     => 'Ruodītė tėk šėtas kateguorėjės (atskirkit nauduodamė „|“)',
'rc_categories_any'                 => 'Bikuokė',
'newsectionsummary'                 => '/* $1 */ naus skėrsnelis',
'rc-enhanced-expand'                => 'Ruodītė detales (rēk JavaScript)',
'rc-enhanced-hide'                  => 'Kavuotė detales',

# Recent changes linked
'recentchangeslinked'          => 'Sosėjėn pakeitėmā',
'recentchangeslinked-feed'     => 'Sosėjėn pakeitėmā',
'recentchangeslinked-toolbox'  => 'Sosėjėn pakeitėmā',
'recentchangeslinked-title'    => 'So $1 sosėje pakeitimā',
'recentchangeslinked-noresult' => 'Nier juokiū pakeitėmu sosėitous poslapious douto čieso.',
'recentchangeslinked-summary'  => "Šėtom specēliajam poslapi ruodomė vielībė̅jė pakeitėmā poslapiūs, i katrūs īr nuruodoma. Poslapē ėš Tamstas [[Special:Watchlist|keravuojamu sāraša]] īr '''pastuorėntė'''.",
'recentchangeslinked-page'     => 'Poslapė pavadinėms:',
'recentchangeslinked-to'       => 'Ruodītė so doutu poslapiu sosėjosiu puslapiu pakeitėmus',

# Upload
'upload'                     => 'Ikeltė faila',
'uploadbtn'                  => 'Ikeltė faila',
'reuploaddesc'               => 'Sogrīžtė i ikielima fuorma.',
'uploadnologin'              => 'Naprėsėjongis',
'uploadnologintext'          => 'Nuoriedamė ikeltė faila, torėt būt [[Special:UserLogin|prėsėjongis]].',
'upload_directory_read_only' => 'Tėnklapė serveris nagal rašītė i ikielima papke ($1).',
'uploaderror'                => 'Ikielima soklīdims',
'uploadtext'                 => "Nauduokėtės žemiau pateikta skvarma kū ikeltomėt failus.
Nuoriedamė parveizietė ar ėiškuotė unkstiau ikeltū abruozdieliu, ēkėt i [[Special:FileList|ikeltū failu saraša]], ikielėmā ėr ėštrīnėmā īr ožregėstroujamė [[Special:Log/upload|ikielėmu istuorėjuo]], trīnėmā - [[Special:Log/delete|trīnėmu istuorėjuo]].

Nuoriedamė panauduotė ikelta faila poslapī, nauduokėt tuokės nūoruodas:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>'''
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></tt>''' aba
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' tėisiuogėnē nūruodā i faila.",
'upload-permitted'           => 'Laistėnė failu tėpā: $1.',
'upload-preferred'           => 'Pageidajamė failu tėpā: $1.',
'upload-prohibited'          => 'Oždraustė failu tėpā: $1.',
'uploadlog'                  => 'ikielimu istuorėjė',
'uploadlogpage'              => 'Ikielimu istuorėjė',
'uploadlogpagetext'          => 'Žemiau pateikiam paskotėniu failu ikielima istuorėjė.',
'filename'                   => 'Faila vards',
'filedesc'                   => 'Kuomentars',
'fileuploadsummary'          => 'Kuomentars:',
'uploadedfiles'              => 'Ikeltė failā',
'ignorewarning'              => 'Nekrėiptė diemiesė i parspiejėma ėr ėšauguotė faila vėsvėin.',
'ignorewarnings'             => 'Nekrėiptė diemesė i vėsuokius perspiejimos',
'minlength1'                 => 'Faila pavadinėms tor būtė nuors vėina raidie.',
'illegalfilename'            => 'Faila vardė „$1“ īr sėmbuoliu, katrėi nier leidami poslapė pavadinėmūs. Prašuom parvadėntė faila ė miegītė ikeltė ana par naujė.',
'badfilename'                => 'Faila pavadinėms pakeists i „$1“.',
'filetype-missing'           => 'Fails netor galūnės (kāp pavīzdīs „.jpg“).',
'emptyfile'                  => 'Panašu, ka fails, katra ikieliet īr toščias. Tas gal būtė diel klaiduos faila pavadėnėmė. Pasėtėkrinkėt a tėkrā nuorėt ikeltė šėta faila.',
'fileexists'                 => "Fails so tuokiu vardu jau īr, prašuom paveizėtė '''<tt>[[:$1]]</tt>''', jēgo nesat ožtėkrėnts, a nuorit ana parrašītė.
[[$1|thumb]]",
'fileexists-extension'       => "Fails so pavėdiu pavadinėmu jau īr: [[$2|thumb]]
* Ikeliama faila pavadinėms: '''<tt>[[:$1]]</tt>'''
* Jau esontė faila pavadinėms: '''<tt>[[:$2]]</tt>'''
Prašuom ėšsėrėnktė kėta varda.",
'file-exists-duplicate'      => 'Tas fails īr {{PLURAL:$1|šėta faila|šėtū failu}} doblėkats:',
'uploadwarning'              => 'Diemesė',
'savefile'                   => 'Ėšsauguotė faila',
'uploadedimage'              => 'ikielė „[[$1]]“',
'overwroteimage'             => 'ikruovė nauja „[[$1]]“ versėjė',
'uploaddisabledtext'         => 'Failu ikielėmā oždraustė īr.',
'uploadscripted'             => 'Šėts failos tor HTML a programėni kuoda, katros gal būtė klaidėngā soprasts interneta naršīklės.',
'uploadvirus'                => 'Šėtom faile īr virosas! Ėšsamiau: $1',
'sourcefilename'             => 'Ikeliams fails',
'destfilename'               => 'Nuorims faila pavadinims',
'upload-maxfilesize'         => 'Dėdliausias faila dėdoms: $1',
'watchthisupload'            => 'Keravuotė šėta poslapė',
'upload-wasdeleted'          => "'''Parspiejėms: Tamsta ikeliat faila, katros unkstiau bova ėštrėnts.'''

Tamsta torietomiet nusprēstė, a īr naudėnga tuoliau ikeldinietė ta faila.
Tuo faila pašalinėma istuorėjė īr pateikta dielē patuogoma:",
'upload-success-subj'        => 'Ikelt siekmėngā',

'upload-proto-error'      => 'Nateisėngs protuokols',
'upload-proto-error-text' => 'Nutuolinē ikielims raikalaun, kū URL prasėdietu <code>http://</code> o <code>ftp://</code>.',
'upload-file-error'       => 'Vėdėnė klaida',
'upload-file-error-text'  => 'Ivīka vėdėnė klaida bandont sokortė laikinaji faila serverī. Prašuom sosėsėiktė so sistemuos admėnėstratuoriom.',
'upload-misc-error'       => 'Nažėnuoma ikielėma klaida',
'upload-misc-error-text'  => 'Ivīka nežėnuoma klaida vīkstont ikielėmō. Prašuom patėkrėnt, kū URL teisėngs teipuogi pasėikiams ėr pamiegīkit viel. Jē bieda ėšlėik, sosėsėikėt so sistemuos admėnėstratuoriom.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Napavīkst pasėiktė URL',
'upload-curl-error6-text'  => 'Pataikts URL nagal būt pasėikts. Prašuom patėkrėntė, kū URL īr teisings ėr svetainė veik.',
'upload-curl-error28'      => 'Par ėlgā ikeliama',
'upload-curl-error28-text' => 'Atsakontė svetainė ožtronk par ėlgā. Patėkrėnkėt, a svetainė veik, palaukėt tropoti ė vielē pamiegīkit. Mažo Tamstā rēktu pamiegītė ne tuokio apkrauto čieso.',

'license'            => 'Licensėjė',
'license-header'     => 'Licensėjė',
'nolicense'          => 'Nepasėrėnkt',
'license-nopreview'  => '(Parveiza negalėma)',
'upload_source_url'  => ' (tėkrs, vėišā priėinams URL)',
'upload_source_file' => ' (fails Tamstas kompioterī)',

# Special:ListFiles
'listfiles-summary'     => 'Tas specēlus poslapis ruod vėsus ikeltus failus.
Palē numatīma paskiausē ikeltė failā īr ruoduomė sāroša vėršou.
Paspaude ont štolpelė ontraštės pakeisėt ėšruokavėma.',
'imgfile'               => 'fails',
'listfiles'             => 'Failu sārašos',
'listfiles_name'        => 'Pavadinėms',
'listfiles_user'        => 'Nauduotuos',
'listfiles_size'        => 'Dėdoms',
'listfiles_description' => 'Aprašīms',
'listfiles_count'       => 'Versėjės',

# File description page
'file-anchor-link'          => 'Fails',
'filehist'                  => 'Abruozdielė istuorėjė',
'filehist-help'             => 'Paspauskėt ont datas/čiesa, ka paveizietomėt faila tuoki, kokis ons bova tū čiesu.',
'filehist-deleteall'        => 'trintė vėsus',
'filehist-deleteone'        => 'trintė šėta',
'filehist-revert'           => 'sogōžėntė',
'filehist-current'          => 'dabartėnis',
'filehist-datetime'         => 'Data/Čiesos',
'filehist-thumb'            => 'Miniatiūra',
'filehist-thumbtext'        => 'Versėjės $1 miniatiūra',
'filehist-user'             => 'Nauduotuos',
'filehist-dimensions'       => 'Mierā',
'filehist-filesize'         => 'Faila dėdoms',
'filehist-comment'          => 'Kuomentars',
'imagelinks'                => 'Faila nūruodas',
'linkstoimage'              => '{{PLURAL:$1|Šėts poslapis|Šėtė poslapē}} nuruod i šėta faila:',
'nolinkstoimage'            => 'I faila neruod anėjuoks poslapis.',
'sharedupload'              => 'Tas fails īr ėš $1 ė gal būtė nauduojams kėtūs pruojektūs.',
'uploadnewversion-linktext' => 'Ikeltė nauja faila versėje',

# File reversion
'filerevert'         => 'Sogrōžėntė $1',
'filerevert-legend'  => 'Faila sogrōžinėms',
'filerevert-intro'   => '<span class="plainlinks">Tamsta grōžėnat \'\'\'[[Media:$1|$1]]\'\'\' i versėje $4 ($2, $3).</span>',
'filerevert-comment' => 'Kuomentars:',
'filerevert-submit'  => 'Grōžėntė',

# File deletion
'filedelete'                  => 'Trintė $1',
'filedelete-legend'           => 'Trintė faila',
'filedelete-intro'            => "Tamsta roušeties ėštrėntė faila '''[[Media:$1|$1]]''' so vėsa anuo istuorėjė.",
'filedelete-comment'          => 'Prīžastis:',
'filedelete-submit'           => 'Trintė',
'filedelete-success'          => "'''$1''' bova ėštrints.",
'filedelete-nofile'           => "'''$1''' nēsa.",
'filedelete-otherreason'      => 'Kėta/papėlduoma prīžastis:',
'filedelete-reason-otherlist' => 'Kėta prīžastis',
'filedelete-reason-dropdown'  => '*Dažnas trīnėma prīžastīs
** Autorīstės teisiu pažeidėmā
** Pasėkartuojontis fails',
'filedelete-edit-reasonlist'  => 'Keistė trīnėma prīžastis',

# MIME search
'mimesearch'         => 'MIME paėiška',
'mimesearch-summary' => 'Šėts poslapis laid ruodīti failus vagol anū MIME tipa. Iveskėt: torėnėtips/potipis, pvz. <tt>image/jpeg</tt>.',
'mimetype'           => 'MIME tips:',
'download'           => 'parsėsiūstė',

# Unwatched pages
'unwatchedpages' => 'Nekeravuojėmė poslapē',

# List redirects
'listredirects' => 'Paradresavėmu sārašos',

# Unused templates
'unusedtemplates'     => 'Nenauduojamė šabluonā',
'unusedtemplatestext' => 'Šėts poslapis ruod sāraša poslapiu, esontiu šabluonu vardū srėtī, katrė nė iterptė i juoki kėta poslapi. Nepamėrškėt patėkrėntė kėtū nūruodu priš anūs ėštrėnont.',
'unusedtemplateswlh'  => 'kėtas nūruodas',

# Random page
'randompage'         => 'Bikuoks poslapis',
'randompage-nopages' => 'Šėtuo vardū srėti nier anėjuokiu poslapiu.',

# Random redirect
'randomredirect'         => 'Bikuoks paradresavėms',
'randomredirect-nopages' => 'Šėtuo vardū srėti nier anėjuokiū paradresavėmu.',

# Statistics
'statistics'               => 'Statėstėka',
'statistics-header-pages'  => 'Poslapiu statėstėka',
'statistics-header-edits'  => 'Redagavėmu statėstėka',
'statistics-header-views'  => 'Parveizu statistėka',
'statistics-header-users'  => 'Nauduotuoju statėstėka',
'statistics-articles'      => 'Torėnė poslapē',
'statistics-pages'         => 'Poslapē',
'statistics-files'         => 'Ikeltė failā',
'statistics-edits'         => 'Poslapiu redagavėmu skaitlius nū {{SITENAME}} sokūrėma',
'statistics-edits-average' => 'Vėdotėnis keitėmu skaitlius poslapiō',
'statistics-users'         => 'Ožsėregėstravosiu [[Special:ListUsers|nauduotuoju]]',
'statistics-users-active'  => 'Aktīviu nauduotuoju',
'statistics-mostpopular'   => 'Daugiausē ruodītė poslapē',

'disambiguations' => 'Daugiareikšmiu žuodiu poslapē',

'doubleredirects'            => 'Dvėgobė paradresavėmā',
'doubleredirectstext'        => 'Tėi paradresavėmā ruod i kėtus paradresavėma poslapius. Kuožnuo eilotē pamėnavuots pėrmasā ėr ontrasā paradresavėmā, tēpuogi ontrojė paradresavėma paskėrtis, katra paprastā ė paruod i tėkraji poslapi, i katra pėrmasā paradresavėms ė torietu ruodītė.',
'double-redirect-fixed-move' => '[[$1]] bova parkelts, daba tas īr paradresavėms i [[$2]]',

'brokenredirects'        => 'Neveikiantīs paradresavėmā',
'brokenredirectstext'    => 'Žemiau ėšvardintė paradresavėma poslapē ruod i nasontius poslapius:',
'brokenredirects-edit'   => 'redagoutė',
'brokenredirects-delete' => 'trintė',

'withoutinterwiki'         => 'Poslapē ba kalbū nūruodu',
'withoutinterwiki-summary' => 'Šėtė poslapē neruod i kėtū kalbū versėjės:',
'withoutinterwiki-submit'  => 'Ruodītė',

'fewestrevisions' => 'Straipsnē so mažiausė pakeitėmu',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|baits|baitā|baitu}}',
'ncategories'             => '$1 {{PLURAL:$1|kateguorėjė|kateguorėjės|kateguorėju}}',
'nlinks'                  => '$1 {{PLURAL:$1|nūruoda|nūruodas|nūruodu}}',
'nmembers'                => '$1 {{PLURAL:$1|narīs|narē|nariū}}',
'nrevisions'              => '$1 {{PLURAL:$1|pakeitėms|pakeitėmā|pakeitėmu}}',
'nviews'                  => '$1 {{PLURAL:$1|paruodīms|paruodīmā|paruodīmu}}',
'specialpage-empty'       => 'Šėtā ataskaitā nie rezoltatu.',
'lonelypages'             => 'Vėinišė straipsnē',
'lonelypagestext'         => 'I šėtuos poslapius nier nūruodu ėš kėtū šėta pruojekta poslapiu.',
'uncategorizedpages'      => 'Poslapē, napriskėrtė juokē kateguorėjē',
'uncategorizedcategories' => 'Kateguorėjės, naprėskėrtas juokē kateguorėjē',
'uncategorizedimages'     => 'Abruozdielē, nepriskėrtė juokē kateguorėjē',
'uncategorizedtemplates'  => 'Šabluonā, nepriskėrtė juokē kateguorėjē',
'unusedcategories'        => 'Nenauduojamas kateguorėjės',
'unusedimages'            => 'Nenauduojamė failā',
'wantedcategories'        => 'Nuorėmiausės kateguorėjės',
'wantedpages'             => 'Nuorėmiausē poslapē',
'wantedfiles'             => 'Nuorėmė failā',
'wantedtemplates'         => 'Nuorėmė šabluonā',
'mostlinked'              => 'Daugiausē ruodomė straipsnē',
'mostlinkedcategories'    => 'Daugiausē ruodomas kateguorėjės',
'mostlinkedtemplates'     => 'Daugiausē ruodomė šabluonā',
'mostcategories'          => 'Straipsnē so daugiausē kateguorėju',
'mostimages'              => 'Daugiausē ruodomė abruozdielē',
'mostrevisions'           => 'Straipsnē so daugiausē keitėmu',
'prefixindex'             => 'Vėsė poslapē so prīšdielio',
'shortpages'              => 'Trompiausė poslapē',
'longpages'               => 'Ėlgiausė poslapē',
'deadendpages'            => 'Straipsnē-aklavėitės',
'deadendpagestext'        => 'Tė poslapē netor nūruodu i kėtus poslapius šėtom pruojektė.',
'protectedpages'          => 'Apsauguotė poslapē',
'protectedpagestext'      => 'Šėtē poslapē īr apsauguotė nū parkielėma a redagavėma',
'protectedpagesempty'     => 'Šėtu čiesu nier apsauguots anėjuoks fails so šėtās parametrās.',
'protectedtitles'         => 'Apsauguotė pavadinėmā',
'protectedtitlesempty'    => 'Šėtou čieso nier anėjuokė pavadinėma, katros apsauguots tās parametrās.',
'listusers'               => 'Sārašos nauduotuoju',
'listusers-editsonly'     => 'Ruodītė tėktās nauduotuojus katrėi īr atlėkė pakeitėmus',
'newpages'                => 'Naujausė straipsnē',
'newpages-username'       => 'Nauduotuojė vards:',
'ancientpages'            => 'Seniausė poslapē',
'move'                    => 'Parvadintė',
'movethispage'            => 'Parvadintė šėta poslapi',
'unusedimagestext'        => 'Primenam, kū kėtas svetainės gal būtė nuruodiosės i abruozdieli tėisiogėniu URL, no vėstėik gal būtė šėtom sārašė, nuors ėr īr aktīvē naudounams.',
'unusedcategoriestext'    => 'Šėtū kateguorėju poslapē sokortė, nuors juoks kėts straipsnis a kateguorėjė ana nenauduo.',
'notargettitle'           => 'Nenuruodīts objekts',
'notargettext'            => 'Tamsta nenuruodiet nuorima poslapė a nauduotuojė,
katram ivīkdītė šėta funkcėjė.',
'pager-newer-n'           => '$1 {{PLURAL:$1|paskesnis|paskesni|paskesniū}}',
'pager-older-n'           => '{{PLURAL:$1|senesnis|senesni|senesniū}}',

# Book sources
'booksources'               => 'Knīngu šaltinē',
'booksources-search-legend' => 'Knīngu šaltiniu paėiška',
'booksources-go'            => 'Ēk!',

# Special:Log
'specialloguserlabel'  => 'Nauduotuos:',
'speciallogtitlelabel' => 'Pavadėnims:',
'log'                  => 'Specēliūju veiksmū istuorėjė',
'all-logs-page'        => 'Vėsos istuorėjės',
'alllogstext'          => 'Bėndra idietu failu, ėštrīnėmu, ožrakėnėmu, bluokavėmu ė prėvėlėju soteikėmu istuorėjė.
Īr galėmībė somažintė rezoltatu skaitliu patėkslėnont vēksma tėpa, nauduotuojė a sosėjosė poslapė.',
'logempty'             => 'Istuorėjuo nier anėjuokiū atitinkontiu atsėtėkimu.',
'log-title-wildcard'   => 'Ėiškuotė pavadinėmu, katrė prasėded šėtuo teksto',

# Special:AllPages
'allpages'          => 'Vėsė straipsnē',
'alphaindexline'    => 'Nu $1 lėg $2',
'nextpage'          => 'Kėts poslapis ($1)',
'prevpage'          => 'Unkstesnis poslapis ($1)',
'allpagesfrom'      => 'Ruodītė poslapius pradedont nu:',
'allpagesto'        => 'Ruodītė poslapius, basėbengėnčios so:',
'allarticles'       => 'Vėsė straipsnē',
'allinnamespace'    => 'Vėsė poslapē (srėtis - $1)',
'allnotinnamespace' => 'Vėsė poslapē (nesontīs šiuo srėtie - $1)',
'allpagesprev'      => 'Onkstesnis',
'allpagesnext'      => 'Sekontis',
'allpagessubmit'    => 'Tink',
'allpagesprefix'    => 'Ruodītė poslapios so prīdelēs:',
'allpagesbadtitle'  => 'Douts poslapė pavadėnėms īr neteisings a tor terpkalbėnė a terppruojektėnė prīdielė. Anamė īr vėns a kelė žėnklā, katrū negal nauduotė pavadėnėmūs.',
'allpages-bad-ns'   => '{{SITENAME}} netor „$1“ vardū srėtėis.',

# Special:Categories
'categories'         => 'Kateguorėjės',
'categoriespagetext' => 'Pruojekte īr šėtuos kateguorėjės.
[[Special:UnusedCategories|Unused categories]] are not shown here.
Also see [[Special:WantedCategories|wanted categories]].',
'categoriesfrom'     => 'Ruodītė kateguorėjės pradedont nu:',

# Special:DeletedContributions
'deletedcontributions'       => 'Panaikėnts nauduotuojė duovis',
'deletedcontributions-title' => 'Ėštrėnts nauduotuojė duovis',

# Special:LinkSearch
'linksearch'    => 'Ėšuorėnės nūruodas',
'linksearch-ns' => 'Vardū srėtis:',
'linksearch-ok' => 'Ėiškuotė',

# Special:ListUsers
'listusersfrom'      => 'Ruodītė nauduotuojus pradedont nū:',
'listusers-submit'   => 'Ruodītė',
'listusers-noresult' => 'Nerast anėjuokiū nauduotuoju.',

# Special:Log/newusers
'newuserlogpage'          => 'Nauduotuojė kūrėma regėstros',
'newuserlog-byemail'      => 'slaptažuodis ėšsiōsts par el. pašta',
'newuserlog-create-entry' => 'Naus nauduotuos',

# Special:ListGroupRights
'listgrouprights'         => 'Nauduotuoju gropiu teisės',
'listgrouprights-group'   => 'Gropė',
'listgrouprights-members' => '(nariū sārošos)',

# E-mail user
'mailnologin'     => 'Nier adresa',
'mailnologintext' => 'Tamstā reik būtė [[Special:UserLogin|prisėjongosiam]]
ė tor būtė ivests teisings el. pašta adresos Tamstas [[Special:Preferences|nustatīmuos]],
kū siōstomiet el. gruomatas kėtėm nauduotuojam.',
'emailuser'       => 'Rašītė gruomata šėtam nauduotuojō',
'emailpage'       => 'Siūstė el. gruomata nauduotuojui',
'usermailererror' => 'Pašta objekts grōžėna klaida:',
'noemailtitle'    => 'Nier el. pašta adreso',
'noemailtext'     => 'Šėts nauduotuos nier nuruodės teisėnga el.pašta adresa a īr pasėrinkės negautė el. pašta ėš kėtū nauduotuoju.',
'email-legend'    => 'Siūstė elektruonėne gruomata kėtam {{SITENAME}} nauduotuojō',
'emailfrom'       => 'Nū:',
'emailmessage'    => 'Pranešėms:',
'emailsend'       => 'Siōstė',
'emailccme'       => 'Siōstė monei mona gruomatas kuopėjė.',
'emailccsubject'  => 'Gruomatas kuopėjė nauduotuojō $1: $2',
'emailsent'       => 'El. gruomata ėšsiōsta',
'emailsenttext'   => 'Tamstas el. pašta žėnotė ėšsiōsta.',

# Watchlist
'watchlist'            => 'Keravuojamė straipsnē',
'mywatchlist'          => 'Keravuojamė poslapē',
'nowatchlist'          => 'Netorėt anėvėina keravuojama poslapė.',
'watchlistanontext'    => 'Prašuom $1, ka parveizietomėt a pakeistomiet elementus sava keravuojamu sārašė.',
'watchnologin'         => 'Neprisėjongės',
'watchnologintext'     => 'Tamstā rēk būtė [[Special:UserLogin|prisėjongosiam]], ka pakeistomiet sava keravuojamu sāraša.',
'addedwatch'           => 'Pridieta pri keravuojamu',
'addedwatchtext'       => "Poslapis \"[[\$1]]\" idiets i [[Special:Watchlist|keravuojamu sāraša]].
Būsantīs poslapė ėr atėtinkama aptarėma poslapė pakeitėmā bus paruoduomė keravuojamu poslapiu sārašė,
tēpuogi bus '''parīškintė''' [[Special:RecentChanges|vielībūju pakeitėmu sārašė]], kū ėšsėskėrtom ėš kėtū straipsniu.
Jēgo bikumet ožsėnuorietomiet liautėis keravuotė straipsnė, spauskat \"nebkeravuotė\" vėršotėniam meniū.",
'removedwatch'         => 'Pašalėntė ėš keravuojamu',
'removedwatchtext'     => 'Poslapis „[[:$1]]“ pašalėnts ėš [[Special:Watchlist|Tamstas keravuojamu sāraša]].',
'watch'                => 'Keravuotė',
'watchthispage'        => 'Keravuotė šėta poslapė',
'unwatch'              => 'Nebkeravuotė',
'unwatchthispage'      => 'Nustuotė keravuotė',
'notanarticle'         => 'Ne torėnė poslapis',
'watchnochange'        => 'Pasėrėnkto čieso nebova redagouts nė vėins keravuojams straipsnis.',
'watchlist-details'    => 'Keravuojama $1 {{PLURAL:$1|poslapis|$1 poslapē|$1 poslapiu}} neskaitlioujant aptarėmu poslapiu.',
'wlheader-enotif'      => '* El. pašta primėnėmā ijongtė īr.',
'wlheader-showupdated' => "* Poslapē, katrėi pakeistė nu Tamstas paskotėnė apsėlonkėma čiesa anūs, īr pažīmietė '''pastuorintā'''",
'watchmethod-recent'   => 'tėkrėnamė vielībė̅jė pakeitėmā keravuojamiems poslapiams',
'watchmethod-list'     => 'Ėiškuoma vielībūju pakeitėmu keravuojamūs poslapiūs',
'watchlistcontains'    => 'Tamstas kervuojamu sārašė īr $1 {{PLURAL:$1|poslapis|poslapē|poslapiu}}.',
'wlnote'               => "Ruoduoma '''$1''' paskotėniu pakeitėmu, atlėktū par '''$2''' paskotėniu adīnu.",
'wlshowlast'           => 'Ruodītė paskotėniu $1 adīnu, $2 dėinū a $3 pakeitėmus',
'watchlist-options'    => 'Keravuojamu sāroša pasėrinkėmā',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Itraukiama i keravuojamu sāraša...',
'unwatching' => 'Šalėnama ėš keravuojamu sāraša...',

'enotif_reset'       => 'Pažīmietė vėsus poslapius kāp aplonkītus',
'enotif_newpagetext' => 'Tas īr naus poslapis.',
'changed'            => 'pakeitė',
'created'            => 'sokūrė',
'enotif_anon_editor' => 'anuonėminis nauduotuos $1',

# Delete
'deletepage'             => 'Trintė poslapi',
'confirm'                => 'Ožtvėrtinu',
'excontent'              => 'boves torinīs: „$1“',
'excontentauthor'        => 'boves torinīs: „$1“ (redagava tėktās „[[Special:Contributions/$2|$2]]“)',
'exbeforeblank'          => 'priš ėštrinant torinīs bova: „$1“',
'exblank'                => 'poslapis bova tuščes',
'delete-confirm'         => 'Ėštrėnta "$1"',
'delete-legend'          => 'Trīnėms',
'historywarning'         => 'Diemesė: Trėnams poslapis tor istuorėjė:',
'confirmdeletetext'      => 'Tamsta pasėrėnkuot ėštrėntė poslapi a abruozdieli draugum so vėsa anuo istuorėjė.
Prašuom patvėrtėntė, kū Tamsta tėkrā nuorėt šėtu padarītė, žėnuot aple galėmus padarėnius, ė kū Tamsta šėtā daruot atsėžvelgdamė i [[{{MediaWiki:Policy-url}}|puolitėka]].',
'actioncomplete'         => 'Vēksmos atlėkts īr',
'deletedtext'            => '„$1“ ėštrints.
Paskotiniu pašalinėmu istuorėjė - $2.',
'deletedarticle'         => 'ėštrīnė „[[$1]]“',
'suppressedarticle'      => 'apžabuots „[[$1]]“',
'dellogpage'             => 'Pašalinėmu istuorėjė',
'dellogpagetext'         => 'Žemiau īr pateikiams paskotiniu ėštrīnimu sārašos.',
'deletionlog'            => 'pašalinėmu istuorėjė',
'reverted'               => 'Atkorta i onkstesne versėje',
'deletecomment'          => 'Prīžastis:',
'deleteotherreason'      => 'Kėta/papėlduoma prižastis:',
'deletereasonotherlist'  => 'Kėta prižastis',
'deletereason-dropdown'  => '*Dažnas trīnėma prižastīs
** Autorė prašīms
** Autorėniu teisiu pažeidėms
** Vandalėzmos',
'delete-edit-reasonlist' => 'Keistė trėnėma prīžastis',

# Rollback
'rollback'         => 'Atmestė pakeitėmos',
'rollback_short'   => 'Atmestė',
'rollbacklink'     => 'atmestė',
'rollbackfailed'   => 'Atmetims napavīka',
'cantrollback'     => 'Negalėma atmestė redagavėma; paskotinis keitės nauduotuos īr tuo poslapė autorius.',
'alreadyrolled'    => 'Nepavīka atmestė paskotėnė [[User:$2|$2]] ([[User talk:$2|Aptarėms]]) darīta straipsnė [[$1]] keitėma;
kažkas jau pakeitė straipsnė arba sospiejė pėrmiesnis atmestė keitėma.

Galėnis keitėms dėrbts nauduotuojė [[User:$3|$3]] ([[User talk:$3|Aptarėms]]).',
'editcomment'      => "Redagavėma kuomentars bova: „''$1''“.",
'revertpage'       => 'Atmests [[Special:Contributions/$2|$2]] ([[User talk:$2|Aptarėms]]) pakeitėms; sogrōžėnta nauduotuojė [[User:$1|$1]] versėjė',
'rollback-success' => 'Atmestė $1 keitėmā; grōžėnta i paskotėne $2 versėje.',

# Edit tokens
'sessionfailure' => 'Atruod kū īr biedū so Tamstas prėsėjongėma sesėjė; šėts veiksmos bova atšaukts kāp atsargoma prėimonė priš sesėjės vuogėma.
Prašoum paspaustė „atgal“ ėr parkrautė poslapi ėš katruo atiejot, ė pamieginkėt vielē.',

# Protect
'protectlogpage'              => 'Rakinėmu istuorėjė',
'protectlogtext'              => 'Žemiau īr poslapė ožrakinėmu tēpuogi atrakinėmu istuorėjė. Nūnā veikiantiu poslapiu apsaugū sāraša sorasėt [[Special:ProtectedPages|apsauguotu poslapiu sarašė]].',
'protectedarticle'            => 'ožrakina „[[$1]]“',
'modifiedarticleprotection'   => 'pakeists „[[$1]]“ apsauguos līgis',
'unprotectedarticle'          => 'atrakėna „[[$1]]“',
'protect-title'               => 'Nustatuoms apsauguojėma līgis poslapiō „$1“',
'prot_1movedto2'              => 'Straipsnis [[$1]] parvadints i [[$2]]',
'protect-legend'              => 'Ožrakinėma patvėrtinėms',
'protectcomment'              => 'Prīžastis:',
'protectexpiry'               => 'Beng galiuotė:',
'protect_expiry_invalid'      => 'Galiuojėma čiesos īr nateisėngs.',
'protect_expiry_old'          => 'Galiuojėma čiesos īr praėitī.',
'protect-text'                => "Čė Tamsta galėt paveizėtė ė pakeistė apsauguos līgi šėtuo poslapio '''$1'''.",
'protect-locked-access'       => "Tamstas paskīra netor teisiu keistė poslapiu apsauguos līgiu.
Čė īr dabartėnē nustatīmā poslapiō '''$1''':",
'protect-cascadeon'           => 'Tas poslapis nūnā īr apsauguots, kadongi ons īr itraukts i {{PLURAL:$1|ta poslapi, apsauguota|tūs poslapiūs, apsauguotus}} „pakuopėnės apsauguos“ pasėrėnkėmu. Tamsta galėt pakeistė šėta poslapė apsauguos līgi, no tas nepaveiks pakuopėnės apsauguos.',
'protect-default'             => 'Leistė vėsėms nauduotuojams',
'protect-fallback'            => 'Rēkalautė „$1“ teisės',
'protect-level-autoconfirmed' => 'Blokoutė naujē prisėregėstravosius ė neregėstroutus nauduotuojus',
'protect-level-sysop'         => 'Tėktās adminėstratuorē',
'protect-summary-cascade'     => 'pakuopėnė apsauga',
'protect-expiring'            => 'beng galiuotė $1 (UTC)',
'protect-expiry-indefinite'   => 'nerėbuotā',
'protect-cascade'             => 'Apsaugotė poslapius, itrauktus i šėta poslapi (pakuopėnė apsauga).',
'protect-cantedit'            => 'Tamsta negalėt keistė šėta poslapė apsauguojėma līgiu, kagongi netorėt teisiu anuo redagoutė.',
'protect-othertime'           => 'Kėts čiesos:',
'protect-othertime-op'        => 'kėts čiesos',
'protect-existing-expiry'     => 'Esams rakėnėma ožsėbengėma čiesos: $3, $2',
'protect-otherreason'         => 'Kėta/papėlduoma prīžastis:',
'protect-otherreason-op'      => 'kėta/papėlduoma prīžastis',
'protect-dropdown'            => '*Iprastas ožrakinėma prīžastīs
** Intensīvus vandalėzmos
** Intensīvus nūruodu reklamavėms
** Neproduktīvi redagavėma vaina
** Dėdlė svarboma poslapis',
'protect-edit-reasonlist'     => 'Keistė ožrakinėma prīžastis',
'protect-expiry-options'      => '1 adīna:1 hour,1 dėina:1 day,1 nedielė:1 week,2 nedielės:2 weeks,1 mienou:1 month,3 mieniesē:3 months,6 mieniesē:6 months,1 metā:1 year,par omžius:infinite',
'restriction-type'            => 'Laidėms:',
'restriction-level'           => 'Aprėbuojėma līgis:',
'minimum-size'                => 'Minėmalus dėdoms',
'maximum-size'                => 'Dėdliausis dėdoms',
'pagesize'                    => '(baitās)',

# Restrictions (nouns)
'restriction-edit'   => 'Redagavėms',
'restriction-move'   => 'Parvadėnėms',
'restriction-create' => 'Sokortė',
'restriction-upload' => 'Ikeltė',

# Restriction levels
'restriction-level-sysop'         => 'pėlnā apsauguota',
'restriction-level-autoconfirmed' => 'posiau apsauguota',
'restriction-level-all'           => 'bikuoks',

# Undelete
'undelete'                   => 'Atstatītė ėštrinta poslapi',
'undeletepage'               => 'Ruodītė ė atkortė ėštrintos poslapios',
'viewdeletedpage'            => 'Ruodītė ėštrintos poslapios',
'undeletepagetext'           => 'Žemiau ėšvardėntė poslapē īr ėštrėntė, no da laikuomi
arkīve, tudie anie gal būt atstatītė. Arkīvs gal būt perēodėškā valuoms.',
'undeleteextrahelp'          => "Nuoriedamė atkortė vėsa poslapi, palikit vėsas varnales napažīmietas ėr
spauskėt '''''Atkortė'''''. Nuoriedamiė atlėktė pasirėnktini atstatīma, pažīmiekit varnales šėtū versėju, katras nuorietomiet atstatītė, ėr spauskėt '''''Atkortė'''''. Paspaudus
'''''Ėš naujė''''' bos ėšvalītuos vėsos varnalės ėr kuomentara lauks.",
'undeleterevisions'          => '$1 {{PLURAL:$1|versėjė|versėjės|versėju}} soarkīvouta',
'undeletehistory'            => 'Jē atstatīsėt straipsni, istuorėjuo bos atstatītuos vėsos versėjės.
Jē puo ėštrīnima bova sokuots straipsnis tuokiuo patio pavadėnėmo,
atstatītuos versėjės atsiras onkstesnie istuorėjuo, o dabartėnė
versėjė lėks napakeista. Atkoriant īr prarondamė apribuojimā failu versėjuom.',
'undeleterevdel'             => 'Atkorėms nebus ivīkdīts, jē šėtā nulems paskotėnės poslapė versėjės dalini ėštrīnima.
Tuokēs atvejās, Tamstā rēk atžīmietė a atkavuotė naujausēs ėštrintas versėjės.
Failu versėjės, katrū netorėt teisiu veizėtė, nebus atkortas.',
'undeletehistorynoadmin'     => 'Šėts straipsnis bova ėštrints. Trīnima prižastis
ruodoma žemiau, teipuogi kas redagava poslapi
lėgė trīnima. Ėštrintū poslapiu tekstos īr galėmas tėk admėnėstratuoriam.',
'undelete-revision'          => 'Ėštrėnta $1 versėjė, katra $4 d. $5 padėrba $3:',
'undeleterevision-missing'   => 'Neteisėnga a dėngosė versėjė. Tamsta mažo torėt bluoga nūruoda, a versėjė bova atkorta a pašalėnta ėš arkīva.',
'undeletebtn'                => 'Atkortė',
'undeletelink'               => 'veizietė/atstatītė',
'undeletereset'              => 'Ėš naujė',
'undeleteinvert'             => 'Žīmietė prīšėngā',
'undeletecomment'            => 'Kuomentars:',
'undeletedarticle'           => 'atkorta „[[$1]]“',
'undeletedrevisions'         => 'atkorta $1 {{PLURAL:$1|versėjė|versėjės|versėju}}',
'undeletedrevisions-files'   => 'atkorta $1 {{PLURAL:$1|versėjė|versėjės|versėju}} ėr $2 {{PLURAL:$2|fails|failā|failu}}',
'undeletedfiles'             => 'atkorta $1 {{PLURAL:$1|fails|failā|failu}}',
'undeletedpage'              => "'''$1 bova atkurts'''
Parveizėkiet [[Special:Log/delete|trīnimu sāraša]], nuoriedamė rastė paskotėniu trīnimu ėr atkorėmu sāraša.",
'undelete-header'            => 'Veizėkit [[Special:Log/delete|trīnima istuorėjuo]] paskoteniausē ėštrintū poslapiu.',
'undelete-search-box'        => 'Ėiškuotė ėštrintū poslapiu',
'undelete-search-prefix'     => 'Ruodītė poslapios pradedant so:',
'undelete-search-submit'     => 'Ėiškuotė',
'undelete-no-results'        => 'Nabova rasta juokė atėtėnkontė poslapė ėštrīnima arkīve.',
'undelete-show-file-confirm' => 'A ėš tėkrā nuorėt parveizietė ėštrėnta faila „<nowiki>$1</nowiki>“ $2 $3 versėjė?',
'undelete-show-file-submit'  => 'Tēp',

# Namespace form on various pages
'namespace'      => 'Vardū srėtis:',
'invert'         => 'Žīmietė prīšėngā',
'blanknamespace' => '(Pagrėndinė)',

# Contributions
'contributions'       => 'Nauduotuojė duovis',
'contributions-title' => 'Nauduotuojė $1 duovis',
'mycontris'           => 'Mona duovis',
'contribsub2'         => 'Nauduotuojė $1 ($2)',
'uctop'               => ' (paskotinis)',
'month'               => 'Nu mienėsė (ėr onkstiau):',
'year'                => 'Nu metu (ėr onkstiau):',

'sp-contributions-newbies'       => 'Ruodītė tėk naujū prieteliu duovios',
'sp-contributions-newbies-sub'   => 'Naujuoms paskīruoms',
'sp-contributions-newbies-title' => 'Nauduotuoju keitėmā naujuoms paskīruoms',
'sp-contributions-blocklog'      => 'Bluokavėmu istuorėjė',
'sp-contributions-deleted'       => 'Panaikėnts nauduotuojė duovis',
'sp-contributions-talk'          => 'Aptarėms',
'sp-contributions-userrights'    => 'Nauduotuoju teisiu valdīms',
'sp-contributions-search'        => 'Ėiškuotė duovė',
'sp-contributions-username'      => 'IP adresos a nauduotuojė vards:',
'sp-contributions-submit'        => 'Ėiškuotė',

# What links here
'whatlinkshere'            => 'Sosėjėn straipsnē',
'whatlinkshere-title'      => 'Poslapē, katrėi ruod i "$1"',
'whatlinkshere-page'       => 'Poslapis:',
'linkshere'                => "Šėtė poslapē ruod i '''[[:$1]]''':",
'nolinkshere'              => "I '''[[:$1]]''' nūruodu nier.",
'nolinkshere-ns'           => "Nurodītuo vardū srėtī anė vėins poslapis neruod i '''[[:$1]]'''.",
'isredirect'               => 'nukreipēmasės poslapis',
'istemplate'               => 'iterpims',
'isimage'                  => 'abruozdielė nūruoda',
'whatlinkshere-prev'       => '$1 {{PLURAL:$1|onkstesnis|onkstesni|onkstesniū}}',
'whatlinkshere-next'       => '$1 {{PLURAL:$1|kėts|kėtė|kėtū}}',
'whatlinkshere-links'      => '← nūruodas',
'whatlinkshere-hideredirs' => '$1 nukreipėmus',
'whatlinkshere-hidetrans'  => '$1 itraukėmus',
'whatlinkshere-hidelinks'  => '$1 nūruodas',
'whatlinkshere-hideimages' => '$1 abruozdieliu nūruodas',
'whatlinkshere-filters'    => 'Fėltrā',

# Block/unblock
'blockip'                     => 'Ožblokoutė nauduotuoja',
'blockip-legend'              => 'Blokoutė nauduotuoja',
'blockiptext'                 => 'Nauduokėt šėta fuorma noriedamė oždraustė redagavėma teises nuruodīto IP adreso a nauduotuojo. Tas torietu būt atlėikama tam, kū sostabdītomiet vandalėzma, ė vagol [[{{ns:project}}:Puolitėka|puolitėka]].
Žemiau nuruodīkėt tėkslē prižastė.',
'ipaddress'                   => 'IP adresos',
'ipadressorusername'          => 'IP adresos a nauduotuojė vards',
'ipbexpiry'                   => 'Galiuojėma čiesos',
'ipbreason'                   => 'Prīžastis:',
'ipbreasonotherlist'          => 'Kėta prīžastis',
'ipbreason-dropdown'          => '*Dažniausės bluokavėma prižastīs
** Melagėngas infuormacėjės rašīms
** Torėnė trīnims ėš poslapiu
** Spaminims
** Zaunu/bikuo rašīms i poslapios
** Gondinėmā/Pėktžuodiavėmā
** Pėktnaudžiavėms paskėruomis
** Netėnkams nauduotuojė vards',
'ipbanononly'                 => 'Blokoutė tėktās anuonimėnius nauduotuojus',
'ipbcreateaccount'            => 'Nelaistė kortė paskīrū',
'ipbemailban'                 => 'Nelaistė nauduotuojō siōstė el. gruomatas',
'ipbenableautoblock'          => 'Autuomatėškā blokoutė tuo nauduotuojė paskiausē nauduota IP adresa, ė bikuokius paskesnius IP adresus, ėš katrū ons miegin redagoutė',
'ipbsubmit'                   => 'Blokoutė šėta nauduotuoja',
'ipbother'                    => 'Kėtuoks čiesos',
'ipboptions'                  => '2 adīnas:2 hours,1 dėina:1 day,3 dėinas:3 days,1 nedielė:1 week,2 nedielės:2 weeks,1 mienou:1 month,3 mienesē:3 months,6 mienesē:6 months,1 metā:1 year,omžėms:infinite',
'ipbotheroption'              => 'kėta',
'ipbotherreason'              => 'Kėta/papėlduoma prižastis',
'ipbwatchuser'                => 'Keravuotė tuo nauduotuojė poslapi ėr anuo aptarėma poslapi',
'ipb-change-block'            => 'Parblokoutė ta nauduotuoja so šėtās nustatīmās',
'badipaddress'                => 'Nelaistėns IP adresos',
'blockipsuccesssub'           => 'Ožblokavėms pavīka',
'blockipsuccesstext'          => '[[Special:Contributions/$1|$1]] bova ožblokouts.
<br />Aplonkīkėt [[Special:IPBlockList|IP blokavėmu istuorėjė]] noriedamė ana parveizėtė.',
'ipb-edit-dropdown'           => 'Redagoutė blokavėmu prīžastis',
'ipb-unblock-addr'            => 'Atblokoutė $1',
'ipb-unblock'                 => 'Atblokoutė nauduotuojė varda a IP adresa',
'ipb-blocklist-addr'          => 'Ruodītė esontius $1 bluokavėmus',
'ipb-blocklist'               => 'Ruodītė asontius bluokavėmus',
'ipb-blocklist-contribs'      => '$1 duovis',
'unblockip'                   => 'Atbluokoutė nauduotuoja',
'unblockiptext'               => 'Nauduokėt šėta fuorma, kū atkortomiet rašīma teises
onkstiau ožbluokoutam IP adresō a nauduotuojō.',
'ipusubmit'                   => 'Atblokoutė šėta adresa',
'unblocked'                   => '[[User:$1|$1]] bova atbluokouts',
'unblocked-id'                => 'Bluokavėms $1 bova pašalėnts',
'ipblocklist'                 => 'Blokoutė IP adresā ė nauduotuojē',
'ipblocklist-legend'          => 'Rastė ožblokouta nauduotuoja',
'ipblocklist-username'        => 'Nauduotuos a IP adresos:',
'ipblocklist-submit'          => 'Ėiškuotė',
'blocklistline'               => '$1, $2 ožblokava $3 ($4)',
'infiniteblock'               => 'neribuotā',
'expiringblock'               => 'beng galiuotė $1 $2',
'anononlyblock'               => 'vėn anuonėmā',
'noautoblockblock'            => 'autuomatinis blokavėms ėšjongts',
'createaccountblock'          => 'paskīrū korėms oždrausts īr',
'emailblock'                  => 'el. pašts ožblokouts',
'ipblocklist-empty'           => 'Blokavėmu sarašos toščias.',
'ipblocklist-no-results'      => 'Prašuoms IP adresos a nauduotuojė vards ožblokouts nier.',
'blocklink'                   => 'ožblokoutė',
'unblocklink'                 => 'atbluokoutė',
'change-blocklink'            => 'keistė bluokavėma nustatīmus',
'contribslink'                => 'duovis',
'autoblocker'                 => 'Autuomatėnis ožbluokavėms, nes dalėnaties IP adreso so nauduotuojo "$1". Prīžastės - "$2".',
'blocklogpage'                => 'Ožblokavėmu istuorėjė',
'blocklogentry'               => 'ožblokava „[[$1]]“, blokavėma čiesos - $2 $3',
'reblock-logentry'            => 'pakeistė [[$1]] bluokavėma nustatīmā, naus bluokavėma čiesos īr $2 $3',
'blocklogtext'                => 'Čė īr nauduotuoju blokavėma ėr atblokavėma sārašos. Autuomatėškā blokoutė IP adresā nier ėšvardėntė. Jeigu nuorėt paveizėtė nūnā blokoujamus adresus, veizėkėt [[Special:IPBlockList|IP ožbluokavėmu istuorėjė]].',
'unblocklogentry'             => 'atbluokava $1',
'block-log-flags-anononly'    => 'vėn anonėmėnē nauduotuojē',
'block-log-flags-nocreate'    => 'privėlėju kūrėms ėšjungts',
'block-log-flags-noautoblock' => 'automatėnis blokavėms ėšjungts',
'block-log-flags-noemail'     => 'e-pašts bluokouts īr',
'ipb_expiry_invalid'          => 'Galiuojėma čiesos nelaistėns.',
'ipb_already_blocked'         => '„$1“ jau ožblokouts',
'ipb-needreblock'             => '== Jau ožblokouts ==
$1 jau īr ožblokouts. A nuorėt pakeistė nustatīmus?',
'proxyblocksuccess'           => 'Padarīt.',

# Developer tools
'unlockdbtext'        => 'Atrakėnos doumenū baze grōžėns galimībe vėsėm
nauduotuojam redagoutė poslapios, keistė anū nostatīmos, keistė anū keravuojamu sāraša ė
kėtos dalīkos, rēkalaujontios pakeitėmu doumenū bazė.
Prašuom patvėrtėntė šėtā, kū ketinat padarītė.',
'locknoconfirm'       => 'Tamsta neoždiejot patvėrtinėma varnalės.',
'unlockdbsuccesstext' => 'Doumenū bazė bova atrakėnta.',

# Move page
'move-page'               => 'Pervadėntė $1',
'move-page-legend'        => 'Poslapė pervadėnims',
'movepagetext'            => "Nauduodamė žemiau pateikta fuorma, parvadinsėt poslapi neprarasdamė anuo istuorėjės.
Senasā pavadinėms pataps nukrēpiamouju - ruodīs i naujīji.
Tamsta esat atsakėngs ož šėta, kū nūruodas ruodītu i ten, kor ė nuorieta.

Primenam, kū poslapis '''nebus''' parvadints, jēgo jau īr poslapis naujo pavadinėmo, nebent tas poslapis īr tuščės a nukreipēmasis ė netor redagavėma istuorėjės.
Tumet, Tamsta galėt parvadintė poslapi seniau nauduota vardu, jēgo priš šėta ons bova par klaida parvadints, a egzėstounantiu poslapiu sogadintė negalėt.

'''DIEMESĖ!'''
Jēgo parvadinat puopoliaru poslapi, tas gal sokeltė nepagēdaunamu šalotiniu efektu, tudie šėta veiksma vīkdīkit tėk isitėkine,
kū soprantat vėsas pasiekmes.",
'movepagetalktext'        => "Sosėits aptarėma poslapis bus autuomatėškā parkelts draugom so ano, '''ėšskīrus:''':
*Poslapis nauju pavadinėmo tor netoštė aptarėma poslapi, a
*Paliksėt žemiau asontė varnale nepažīmieta.
Šėtās atviejās Tamsta sava nužiūra torėt parkeltė a apjongtė aptarėma poslapi.",
'movearticle'             => 'Parvadintė poslapi:',
'movenologin'             => 'Neprisėjongės',
'movenologintext'         => 'Nuoriedamė parvadintė poslapi, torėt būtė ožsėregėstravės nauduotuos ė tēpuogi būtė [[Special:UserLogin|prisėjongės]].',
'newtitle'                => 'Naus pavadėnėms:',
'move-watch'              => 'Keravuotė šėta poslapi',
'movepagebtn'             => 'Parvadintė poslapė',
'pagemovedsub'            => 'Parvadinta siekmingā',
'movepage-moved'          => '\'\'\'"$1" bova parvadints i "$2"\'\'\'',
'movepage-moved-redirect' => 'Nukreipims bova sokorts.',
'articleexists'           => 'Straipsnis so tuokiu vardo jau īr
a parinktāsis vards īr bluogs.
Parinkat kėta varda.',
'talkexists'              => "'''Patsā poslapis bova siekmėngā parvadints, no aptarėmu poslapis nabova parkelts, kadongi nauja
pavadėnėma straipsnis jau tor aptarėmu poslapi.
Prašuom sojongtė šėtuos poslapios.'''",
'movedto'                 => 'parvadints i',
'movetalk'                => 'Parkeltė sosėta aptarėma poslapi.',
'1movedto2'               => 'Straipsnis [[$1]] parvadints i [[$2]]',
'1movedto2_redir'         => '[[$1]] parvadints i [[$2]] (onkstiau bova nukrēpamāsis)',
'movelogpage'             => 'Parvardinėmu istuorėjė',
'movelogpagetext'         => 'Sārašos parvadintu poslapiu.',
'movereason'              => 'Prīžastis:',
'revertmove'              => 'atmestė',
'delete_and_move'         => 'Ėštrintė ė parkeltė',
'delete_and_move_text'    => '==Rēkalings ėštrīnims==
Paskėrties straipsnis „[[:$1]]“ jau īr. A nuorėt ana ėštrintė, kū galietomiet parvadintė?',
'delete_and_move_confirm' => 'Tēp, trintė poslapi',
'delete_and_move_reason'  => 'Ėštrinta diel parkielima',
'move-leave-redirect'     => 'Parvadėnant paliktė nukreipėma',

# Export
'export'            => 'Ekspuortoutė poslapius',
'exportcuronly'     => 'Eksportoutė tėktās dabartėne versėjė, neitraukiant istuorėjės',
'export-submit'     => 'Eksportoutė',
'export-addcattext' => 'Pridietė poslapius ėš kateguorėjės:',
'export-addcat'     => 'Pridietė',
'export-download'   => 'Sauguotė kāp faila',

# Namespace 8 related
'allmessages'               => 'Vėsė sėstemas tekstā ė pranešėmā',
'allmessagesname'           => 'Pavadėnėms',
'allmessagesdefault'        => 'Pradėnis teksts',
'allmessagescurrent'        => 'Dabartėnis teksts',
'allmessagestext'           => 'Čė pateikamė sėstemėniu pranešėmu sārašos, esontis MediaWiki srėtie.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' nepalaikuoms īr, nes nustatīms '''\$wgUseDatabaseMessages''' ėšjungts īr.",

# Thumbnails
'thumbnail-more'           => 'Padėdintė',
'thumbnail_error'          => 'Klaida koriant somažėnta pavēkslieli: $1',
'thumbnail_invalid_params' => 'Nalaistieni miniatiūras parametrā',
'thumbnail_dest_directory' => 'Nepavīkst sokortė paskėrtėis papkes',

# Special:Import
'import'                => 'Importoutė poslapius',
'import-revision-count' => '$1 {{PLURAL:$1|versėjė|versėjės|versėju}}',

# Import log
'importlogpage'                    => 'Impuorta istuorėjė',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|keitims|keitimā|keitimu}}',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|keitims|keitimā|keitimu}} ėš $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Tamstas nauduotuojė poslapis',
'tooltip-pt-anonuserpage'         => 'Nauduotuojė poslapis Tamstas IP adresō',
'tooltip-pt-mytalk'               => 'Tamstas aptarėma poslapis',
'tooltip-pt-preferences'          => 'Mona nostatīmā',
'tooltip-pt-watchlist'            => 'Poslapiu sārašos, katrūs Tamsta pasėrėnkuot keravuotė.',
'tooltip-pt-mycontris'            => 'Tamstas darītu keitimu sārašos',
'tooltip-pt-login'                => 'Rekuomendoujam prėsėjongtė, nuors tas nėr privaluoma.',
'tooltip-pt-logout'               => 'Atsėjongtė',
'tooltip-ca-talk'                 => 'Poslapė torėnė aptarėms',
'tooltip-ca-edit'                 => 'Tamsta galėt keistė ta poslapi. Nepamėrškėt paspaustė parvaizuos mīgtoka priš ėšsauguodamė.',
'tooltip-ca-addsection'           => 'Pradietė nauja skīriu',
'tooltip-ca-viewsource'           => 'Poslapis īr ožrakints. Galėt parveizėt torini.',
'tooltip-ca-history'              => 'Unkstesnės poslapė versėjės.',
'tooltip-ca-protect'              => 'Ožrakintė šėta poslapi',
'tooltip-ca-delete'               => 'Trėntė ta poslapi',
'tooltip-ca-move'                 => 'Parvadėntė poslapi',
'tooltip-ca-watch'                => 'Pridietė poslapi i keravuojamu sāraša',
'tooltip-ca-unwatch'              => 'Pašalėntė poslapi ėš keravuojamu sāraša',
'tooltip-search'                  => 'Ėiškuotė šėtom pruojektė',
'tooltip-search-go'               => 'Ētė i poslapi su tuokiu pavadėnėmu jēgo tuoks īr',
'tooltip-search-fulltext'         => 'Ėiškuotė poslapiu so tuokiu tekstu',
'tooltip-p-logo'                  => 'Pėrms poslapis',
'tooltip-n-mainpage'              => 'Aplonkītė pėrma poslapi',
'tooltip-n-mainpage-description'  => 'Ētė i pėrma poslapi',
'tooltip-n-portal'                => 'Aple pruojekta, ka galėma vēktė, kamė ka rastė',
'tooltip-n-currentevents'         => 'Raskėt naujausė infuormacėjė',
'tooltip-n-recentchanges'         => 'Vielībūju pakeitėmu sārašos tamė projektė.',
'tooltip-n-randompage'            => 'Atidarītė bikuoki straipsni',
'tooltip-n-help'                  => 'Vėita, katruo rasėt rūpėmus atsakīmus.',
'tooltip-t-whatlinkshere'         => 'Poslapiu sārašos, ruodantiu i čė',
'tooltip-t-recentchangeslinked'   => 'Paskotėnē pakeitėmā straipsnious, pasėikiamous ėš šėta straipsnė',
'tooltip-feed-rss'                => 'Šėta poslapė RSS šaltėnis',
'tooltip-feed-atom'               => 'Šėta poslapė Atom šaltėnis',
'tooltip-t-contributions'         => 'Ruodītė šėta nauduotuojė keitėmu sāraša',
'tooltip-t-emailuser'             => 'Siōstė gromata šėtom prietėliō',
'tooltip-t-upload'                => 'Idietė abruozdielios a medėjės failos',
'tooltip-t-specialpages'          => 'Specēliūju poslapiu sārašos',
'tooltip-t-print'                 => 'Šėta poslapė versėjė spausdėnėmō',
'tooltip-t-permalink'             => 'Vėslaikėnė nūruoda i šėta poslapė versėje',
'tooltip-ca-nstab-main'           => 'Ruodītė poslapė torėni',
'tooltip-ca-nstab-user'           => 'Ruodītė nauduotuojė poslapi',
'tooltip-ca-nstab-special'        => 'Šėts poslapis īr specēlosis - anuo nagalėm redagoutė.',
'tooltip-ca-nstab-project'        => 'Ruodītė pruojekta poslapi',
'tooltip-ca-nstab-image'          => 'Ruodītė abruozdielė poslapi',
'tooltip-ca-nstab-template'       => 'Ruodītė šabluona',
'tooltip-ca-nstab-help'           => 'Ruodītė pagelbas poslapi',
'tooltip-ca-nstab-category'       => 'Ruodītė kateguorėjės poslapi',
'tooltip-minoredit'               => 'Pažīmietė pakeitėma kāp maža',
'tooltip-save'                    => 'Ėšsauguotė pakeitėmos',
'tooltip-preview'                 => 'Pakeitėmu parveiza, prašuom parveizėt priš ėšsaugont!',
'tooltip-diff'                    => 'Ruod, kuokios pakeitėmos padariet tekste.',
'tooltip-compareselectedversions' => 'Veizėtė abodvėju pasėrėnktū poslapė versėju skėrtomos.',
'tooltip-watch'                   => 'Pridietė šėta poslapi i keravuojamu sāraša',
'tooltip-recreate'                => 'Atkortė poslapi napaisant šėto, kū ans bova ėštrints',
'tooltip-rollback'                => 'Atšauktė atmestus šėta poslapė keitėmus i vielībiause versėje par vėina paspaudėma',
'tooltip-undo'                    => '"Anolioutė" atmeta ta keitėma ėr atidara unkstesnies versėjės redagavėma skvarma. Leid pridietė atmetėma prīžasti kuomentarūsė.',

# Attribution
'anonymous'        => 'Neregėstrouts nauduotuos',
'siteuser'         => '{{SITENAME}} nauduotuos $1',
'lastmodifiedatby' => 'Šėta poslapi paskotini karta redagava $3 $2, $1.',
'others'           => 'kėtė',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|nauduotuos|nauduotuojē}} $1',
'creditspage'      => 'Poslapė kūriejē',

# Spam protection
'spamprotectiontitle' => 'Prišreklamėnis fėltros',
'spamprotectiontext'  => 'Poslapis, katra nuoriejot ėšsauguotė bova ožblokouts prišreklamėnė fėltra. Šėtā tėkriausē sokielė nūruoda i kėta svetaine. Ėšėmkit nūruoda ė pamieginkėt viel.',
'spamprotectionmatch' => 'Šėts tekstos bova atpažėnts prišreklamėnė fėltra: $1',
'spambot_username'    => "''MediaWiki'' reklamu šalėnėms",
'spam_reverting'      => 'Atkoriama i onkstesne versėje, katra nator nūruodu i $1',
'spam_blanking'       => 'Vėsos versėjės toriejė nūruodu i $1. Ėšvaluoma',

# Info page
'numedits'     => 'Pakeitimu skaitlius (straipsnis): $1',
'numtalkedits' => 'Pakeitėmu skaitlius (aptarėma poslapis): $1',
'numwatchers'  => 'Keravuojantiu skaitlius: $1',

# Math options
'mw_math_png'    => 'Vėsumet fuormuotė PNG',
'mw_math_simple' => 'HTML paprastās atvejās, kėtēp - PNG',
'mw_math_html'   => 'HTML kumet imanuoma, kėtēp - PNG',
'mw_math_source' => 'Paliktė TeX fuormata (tekstinems naršīklems)',
'mw_math_modern' => 'Rekomendounama muodernioms naršīklems',
'mw_math_mathml' => 'MathML jēgo imanuoma (ekspermentinis)',

# Math errors
'math_failure'          => 'Nepavīka apdoruotė',
'math_unknown_error'    => 'nežinuoma klaida',
'math_unknown_function' => 'nežinuoma funkcėjė',

# Patrolling
'markaspatrolleddiff'   => 'Žīmietė, kū patikrėnta',
'markaspatrolledtext'   => 'Pažīmietė, ka poslapis patėkrėnts īr',
'markedaspatrolled'     => 'Pažīmiets kāp patėkrints',
'markedaspatrolledtext' => 'Pasėrinkta versėjė siekmingā pažīmieta kāp patėkrinta',

# Patrol log
'patrol-log-page'      => 'Patikrinėma istuorėjė',
'patrol-log-line'      => 'Poslapė „$2“ $1 pažīmieta kāp patėkrinta $3',
'patrol-log-auto'      => '(autuomatėškā)',
'patrol-log-diff'      => 'versėjė $1',
'log-show-hide-patrol' => '$1 patvirtėnėmu saraša',

# Image deletion
'deletedrevision'       => 'Ėštrinta sena versėjė $1.',
'filedeleteerror-short' => 'Klaida trėnont faila: $1',

# Browsing diffs
'previousdiff' => '← Onkstesnis pakeitėms',
'nextdiff'     => 'Paskesinis pakeitėms →',

# Media information
'mediawarning'         => "'''Diemesė''': Šėts fails gal torietė kenksmėnga kuoda, anū palaidus Tamstas sėstėma gal būtė sogadinta.",
'imagemaxsize'         => 'Rėbuotė abruozdieliu dėdoma anū aprašīma poslapī lėgė:',
'thumbsize'            => 'Somažėntu pavēkslieliu didums:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|poslapis|poslapē|poslapiu}}',
'file-info'            => '(faila dėdoms: $1, MIME tips: $2)',
'file-info-size'       => '($1 × $2 taškū, faila dėdoms: $3, MIME tips: $4)',
'file-nohires'         => '<small>Geresnis ėšraiškėms negalėms.</small>',
'svg-long-desc'        => '(SVG fails, fuormalē $1 × $2 puškiu, faila dėdoms: $3)',
'show-big-image'       => 'Pėlns ėšraiškėms',
'show-big-image-thumb' => '<small>Šėtuos parvaizos dėdums: $1 × $2 puškiu</small>',

# Special:NewFiles
'newimages'             => 'Naujausiu abruozdieliu galerėjė',
'imagelisttext'         => "Žemiau īr '''$1''' failu sārašos, sorūšiouts $2.",
'newimages-label'       => 'Faila vards (ar anuo dalis):',
'showhidebots'          => '($1 robotos)',
'ilsubmit'              => 'Ėiškoutė',
'bydate'                => 'palē data',
'sp-newimages-showfrom' => 'Ruodītė naujus abruozdielius pradedant nū $2, $1',

# Bad image list
'bad_image_list' => 'Fuormats tuoks īr:

Tėk eilotės, prasėdedantės *, īr itraukiamas. Pėrmuojė nūruoda eilotie tor būtė nūruoda i bluoga abruozdieli.
Vėsas kėtas nūoruodas tuo patiuo eilotie īr laikomas ėšėmtim, tas rēšk ka poslapē, katrūs leidama iterptė abruozdieli.',

# Metadata
'metadata'          => 'Metadoumenīs',
'metadata-help'     => 'Šėtom failė īr papėlduomos infuormacėjės, tikriausē pridietos skaitmeninės kameruos a skanėrė, katros bova nauduots anam sokortė a parkeltė i skaitmenėni fuormata. Jēgo fails bova pakeists ėš pradėnės versėjės, katruos nekatruos datalės gal nepėlnā atspėndietė nauja faila.',
'metadata-expand'   => 'Ruodītė ėšpliestinė infuormacėjė',
'metadata-collapse' => 'Kavuotė ėšpliestinė infuormacėjė',
'metadata-fields'   => 'EXIF metadoumenū laukā, nuruodītė tamė pranešėmė, bus itrauktė i abruozdielė poslapi, kumet metadoumenū lentelė bus suskleista. Palē nutīliejėma kėtė laukā bus pakavuotė.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'             => 'Platoms',
'exif-imagelength'            => 'Aukštoms',
'exif-orientation'            => 'Pasokims',
'exif-xresolution'            => 'Gorizuontalus ėšraiškėms',
'exif-datetime'               => 'Faila keitėma data ė čiesos',
'exif-imagedescription'       => 'Abruozdielė pavadėnėms',
'exif-make'                   => 'Kameras gamėntuos',
'exif-model'                  => 'Kameras muodelis',
'exif-colorspace'             => 'Spalvū pristatīms',
'exif-compressedbitsperpixel' => 'Abruozdielė sospaudėma rėžėms',
'exif-datetimeoriginal'       => 'Doumenū generavėma data ė čiesos',
'exif-exposuretime'           => 'Ėšlaikīma čiesos',
'exif-fnumber'                => 'F skaitlius',
'exif-brightnessvalue'        => 'Švėisoms',
'exif-lightsource'            => 'Švėisuos šaltėnis',
'exif-flash'                  => 'Blėcos',
'exif-focallength'            => 'Žėdinė nutuolėms',
'exif-flashenergy'            => 'Blėca energėjė',
'exif-contrast'               => 'Kuontrasts',

'exif-orientation-1' => 'Standartėšks',

'exif-xyresolution-i' => '$1 puškē cuolī',
'exif-xyresolution-c' => '$1 puškē centėmetrė',

'exif-componentsconfiguration-0' => 'nēsa',

'exif-exposureprogram-0' => 'Nenūruodīta',

'exif-contrast-0' => 'Paprasts',
'exif-contrast-1' => 'Mažos',
'exif-contrast-2' => 'Dėdlis',

# External editor support
'edit-externally'      => 'Atdarītė ėšuoriniam redaktuorio',
'edit-externally-help' => 'Nuoriedamė gautė daugiau infuormacėjės, veiziekėt [http://www.mediawiki.org/wiki/Manual:External_editors kruovėma instrokcėjės].',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'vėsos',
'imagelistall'     => 'vėsė',
'watchlistall2'    => 'vėsos',
'namespacesall'    => 'vėsas',
'monthsall'        => 'vėsė',

# E-mail address confirmation
'confirmemail'           => 'Patvirtėnkėt el. pašta adresa',
'confirmemail_noemail'   => 'Tamsta netorėt nuruodės teisėnga el. pašta adresa [[Special:Preferences|sava nustatīmūs]].',
'confirmemail_text'      => 'Šėtom pruojektė īr rēkalėnga patvirtėntė el. pašta adresa prīš nauduojont el. pašta funkcėjės. Spauskėt žemiau esonti mīgtoka,
kū Tamstas el. pašta adresu būtom ėšsiōsts patvirtėnėma kods.
Gruomatuo bus atsiōsta nūruoda so kodu, katra nuējus, el. pašta adresos bus patvirtėnts.',
'confirmemail_send'      => 'Ėšsiōstė patvirtėnėma koda',
'confirmemail_sent'      => 'Patvirtėnėma gruomata ėšsiōsta.',
'confirmemail_needlogin' => 'Tamstā rēk $1, kū patvirtėntomiet sava el. pašta adresa.',
'confirmemail_loggedin'  => 'Tamstas el. pašta adresos ožtvėrtints īr.',
'confirmemail_body'      => 'Kažėnkas, mosiet Tamsta IP adreso $1, ožregėstrava
paskīra „$2“ sosėita so šėtuom el. pašta adresu pruojektė {{SITENAME}}.

Kū patvirtėntomiet, kū ta diežotė ėš tėkrā prėklausa Tamstā, ėr aktīvoutomiet
el. pašta puoslaugi pruojėktė {{SITENAME}}, atdarīkiet ta nūruoda sava naršīklie:

$3

Jēgo paskīra regėstravuot *ne* Tamsta, tumet ēkėt ta nūruoda,
kū atšauktomiet el. pašta adresa patvirtėnėma:

$5

Patvirtėnėma kods bengs galiuotė $4.',
'invalidateemail'        => 'El. pašta patvirtėnėma atšaukėms',

# Trackbacks
'trackbackremove' => '([$1 Trintė])',

# Delete conflict
'deletedwhileediting' => 'Diemesė: Šėts poslapis ėštrints po šėta, kumet pradiejot redagoutė!',
'recreate'            => 'Atkortė',

# action=purge
'confirm_purge_button' => 'Tink',

# Multipage image navigation
'imgmultipageprev' => '← unkstesnis poslapis',
'imgmultipagenext' => 'kėts poslapis →',
'imgmultigo'       => 'Ētė!',
'imgmultigoto'     => 'Ētė i poslapi $1',

# Table pager
'ascending_abbrev'         => 'dėdiejėma tvarka',
'descending_abbrev'        => 'mažiejontė tvarka',
'table_pager_next'         => 'Kėts poslapis',
'table_pager_prev'         => 'Onkstesnis poslapis',
'table_pager_first'        => 'Pėrms poslapis',
'table_pager_last'         => 'Paskotėnis poslapis',
'table_pager_limit'        => 'Ruodītė $1 elementu par poslapi',
'table_pager_limit_submit' => 'Ruodītė',
'table_pager_empty'        => 'Juokiū rezoltatu',

# Auto-summaries
'autosumm-blank'   => 'Šalėnams ciels torėnīs ėš poslapė',
'autosumm-replace' => "Poslapis keitams so '$1'",
'autoredircomment' => 'Nukreipama i [[$1]]',
'autosumm-new'     => 'Naus poslapis: $1',

# Live preview
'livepreview-loading' => 'Kraunama īr…',
'livepreview-ready'   => 'Ikeliama… Padarīta!',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Pakeitėmā, naujesnė kāp $1 {{PLURAL:$1|sekondė|sekondės|sekondiu}}, tamė sārašė gal būtė neruodomė.',
'lag-warn-high'   => 'Dielē dėdlė doumenū bazės pasėlikėma pakeitėmā, naujesnė nēgo $1 {{PLURAL:$1|sekondė|sekondės|sekondiu}}, tamė sarašė gal būtė neruodomė.',

# Watchlist editor
'watchlistedit-numitems'       => 'Tamstas keravuojamu sārašė īr $1 poslapiu neskaitliuojant aptarėmu poslapiu.',
'watchlistedit-noitems'        => 'Tamstas keravuojamu sārašė nė juokiū poslapiu.',
'watchlistedit-normal-title'   => 'Keistė keravuojamu sāroša',
'watchlistedit-normal-legend'  => 'Šalėntė poslapios ėš keravuojamu sāraša',
'watchlistedit-normal-explain' => 'Žemiau īr ruodomė poslapē Tamstas keravuojamu sārašė.
Nuoriedamė pašalėntė poslapi, pri anuo oždiekėt varnale ė paspauskėt „Šalėntė poslapios“.
Tamsta tēpuogi galėt [[Special:Watchlist/raw|redagoutė grīnaji keravuojamu sāraša]].',
'watchlistedit-normal-submit'  => 'Šalėntė poslapios',
'watchlistedit-normal-done'    => '$1 {{PLURAL:$1|poslapis bova pašalėnts|poslapē bova pašalėntė|poslapiu bova pašalėnta}} ėš Tamstas keravuojamu saraša:',
'watchlistedit-raw-title'      => 'Keistė grīnōjė keravuojamu sāraša',
'watchlistedit-raw-legend'     => 'Keistė grīnōjė keravuojamu sāraša',
'watchlistedit-raw-explain'    => 'Žemiau ruodomė poslapē Tamstas keravuojamu sārašė, ė gal būtė pridietė i a pašalėntė ėš sāraša; vėins poslapis eilotie. Bėngė paspauskėt „Atnaujėntė keravuojamu sāraša“. Tamsta tēpuogi galėt [[Special:Watchlist/edit|nauduotė standartėni radaktuoriu]].',
'watchlistedit-raw-titles'     => 'Poslapē:',
'watchlistedit-raw-submit'     => 'Atnaujėntė keravuojamu sāraša',
'watchlistedit-raw-done'       => 'Tamstas keravuojamu sārošos bova atnaujėnts.',
'watchlistedit-raw-added'      => '$1 {{PLURAL:$1|poslapis bova pridiets|poslapē bova pridietė|poslapiu bova pridieta}}:',
'watchlistedit-raw-removed'    => '$1 {{PLURAL:$1|poslapis bova pašalėnts|poslapē bova pašalėntė|poslapiu bova pašalėnta}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Veizietė sosėjosius pakeitėmus',
'watchlisttools-edit' => 'Veizietė ėr keistė keravuojamu straipsniu sāraša',
'watchlisttools-raw'  => 'Keistė nebėngta keravuojamu straipsniu sāraša',

# Special:Version
'version'         => 'Versėjė',
'version-license' => 'Licenzėjė',

# Special:FilePath
'filepath'      => 'Faila maršrots',
'filepath-page' => 'Fails:',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Ėiškuotė doblikoutu failu',
'fileduplicatesearch-legend'   => 'Ėiškuotė doblėkatu',
'fileduplicatesearch-filename' => 'Faila vards:',
'fileduplicatesearch-submit'   => 'Ėiškuotė',
'fileduplicatesearch-info'     => '$1 × $2 pėkseliu<br />Faila dėdoms: $3<br />MIME tėps: $4',

# Special:SpecialPages
'specialpages'                   => 'Specēlė̅jė poslapē',
'specialpages-note'              => '----
* Normalūs specēlė̅jė puslapē.
* <strong class="mw-specialpagerestricted">Apribuotė specēlė̅jė puslapē.</strong>',
'specialpages-group-maintenance' => 'Sėstemas palaikīma pranešėmā',
'specialpages-group-other'       => 'Kėtė specēlė̅jė poslapē',
'specialpages-group-login'       => 'Prisėjongėms / Registracėjė',
'specialpages-group-changes'     => 'Vielībiė̅ jė pakeitėmā ė regėstrā',
'specialpages-group-media'       => 'Infuormacėjė aple failus ėr anū pakruovėms',
'specialpages-group-users'       => 'Nauduotuojē ė teisės',
'specialpages-group-highuse'     => 'Platē nauduojamė poslapē',
'specialpages-group-pages'       => 'Poslapiu sarašas',
'specialpages-group-pagetools'   => 'Poslapiu rakondā',
'specialpages-group-wiki'        => 'Wiki doumenīs ė rakondā',
'specialpages-group-redirects'   => 'Specēlė̅jė nukreipėma poslapē',
'specialpages-group-spam'        => 'Šlamšta valdīma rakondā',

# Special:BlankPage
'blankpage' => 'Toščias poslapis',

# Special:Tags
'tags-edit' => 'taisītė',

);
