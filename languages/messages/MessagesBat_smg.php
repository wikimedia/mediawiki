<?php
/** Samogitian (Žemaitėška)
  *
  * @addtogroup Language
  */

$namespaceNames = array(
//	NS_MEDIA            => '',
	NS_SPECIAL          => 'Specēlos',
	NS_MAIN             => '',
	NS_TALK             => 'Aptarėms',
	NS_USER             => 'Nauduotuos',
	NS_USER_TALK        => 'Nauduotuojė_aptarėms',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_aptarėms',
	NS_IMAGE            => 'Abruozdielis',
	NS_IMAGE_TALK       => 'Abruozdielė_aptarėms',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_aptarėms',
	NS_TEMPLATE         => 'Šabluons',
	NS_TEMPLATE_TALK    => 'Šabluona_aptarėms',
	NS_HELP             => 'Pagelba',
	NS_HELP_TALK        => 'Pagelbas_aptarėms',
	NS_CATEGORY         => 'Kateguorėjė',
	NS_CATEGORY_TALK    => 'Kateguorėjės_aptarėms'
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
	'Vaizdas'               => NS_IMAGE,
	'Vaizdo_aptarimas'      => NS_IMAGE_TALK,
	'MediaWiki_aptarimas'   => NS_MEDIAWIKI_TALK,
	'Šablonas'              => NS_TEMPLATE,
	'Šablono_aptarimas'     => NS_TEMPLATE_TALK,
	'Pagalba'               => NS_HELP,
	'Pagalbos_aptarimas'    => NS_HELP_TALK,
	'Kategorija'            => NS_CATEGORY,
	'Kategorijos_aptarimas' => NS_CATEGORY_TALK,
);

$fallback = 'lt';

$messages = array(
# User preference toggles
'tog-hideminor' => 'Pakavuotė mažus pataisėmus vielībūju taisimu sārašė',

'underline-always' => 'Visumet',
'underline-never'  => 'Nikumet',

'skinpreview' => '(Parveiza)',

# Dates
'sunday'        => 'sekmadėinis',
'monday'        => 'pėrmadėinis',
'tuesday'       => 'ontradėinis',
'wednesday'     => 'trečiadėinis',
'thursday'      => 'ketvėrtadėinis',
'friday'        => 'pėnktadėinis',
'saturday'      => 'šeštadėinis',
'sun'           => 'Sekm',
'mon'           => 'Pėrm',
'tue'           => 'Ontr',
'wed'           => 'Treč',
'thu'           => 'Ketv',
'fri'           => 'Pėnk',
'sat'           => 'Šešt',
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

# Bits of text used by many pages
'categories' => 'Kateguorėjės',

'about'          => 'Aple',
'article'        => 'Straipsnis',
'newwindow'      => '(īr atverams naujam longė)',
'cancel'         => 'Nutrauktė',
'qbfind'         => 'Ėiškuotė',
'qbbrowse'       => 'Naršītė',
'qbedit'         => 'Taisītė',
'qbpageoptions'  => 'Šits poslapis',
'qbpageinfo'     => 'Konteksts',
'qbmyoptions'    => 'Mona poslapē',
'qbspecialpages' => 'Specēlė̅jė poslapē',
'moredotdotdot'  => 'Daugiau...',
'mypage'         => 'Mona poslapis',
'mytalk'         => 'Mona aptarėms',
'anontalk'       => 'Šėta IP aptarėms',
'navigation'     => 'Navigacėjė',

'errorpagetitle'   => 'Klaida',
'returnto'         => 'Grīžtė i $1.',
'tagline'          => 'Straipsnis ėš {{SITENAME}}.',
'help'             => 'Pagelba',
'search'           => 'Ėiškuotė',
'searchbutton'     => 'Ėiškuok',
'go'               => 'Ēk',
'searcharticle'    => 'Ēk',
'history'          => 'Poslapė istorėjė',
'history_short'    => 'Istorėjė',
'updatedmarker'    => 'atnaujinta nu paskotėnė mona apsėlonkīma',
'info_short'       => 'Informacėjė',
'printableversion' => 'Versėjė spausdintė',
'permalink'        => 'Nulatėnė nūruoda',
'print'            => 'Spausdintė',
'edit'             => 'Taisītė',
'editthispage'     => 'Taisītė ton poslapė',
'delete'           => 'Trintė',
'deletethispage'   => 'Trintė ton poslapė',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'currentevents' => '** Current events **',
'mainpage'      => 'Pėrms poslapis',
'sitesupport'   => 'Pagelba',

# Recent changes
'recentchanges' => 'Naujausi keitimai',

# Upload
'upload' => 'Ikeltė faila',

# Random page
'randompage' => 'Atsitiktinis straipsnis',

# Miscellaneous special pages
'specialpages' => 'Specēlė̅jė poslapē',

# Namespace 8 related
'allmessages' => 'Vėsė sėstemas tekstā ė pranešėmā',

);
