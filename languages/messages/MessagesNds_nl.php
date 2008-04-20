<?php
/** Nedersaksisch (Nedersaksisch)
 *
 * @addtogroup Language
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>, Jens Frank
 * @author SPQRobin
 * @author Erwin85
 * @author לערי ריינהארט
 * @author Servien
 * @author Siebrand
 * @author Slomox
 * @author Nike
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason, Jens Frank
 * @copyright Copyright © 2007, Betawiki users
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

$fallback = 'nl';

$skinNames = array(
	'standard'      => 'Klassiek',
	'nostalgia'     => 'Nostalgie',
	'cologneblue'   => 'Keuls blauw',
	'chick'         => 'Sjiek',
	'myskin'        => 'MienSkin',
);

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciaal',
	NS_MAIN             => '',
	NS_TALK             => 'Overleg',
	NS_USER             => 'Gebruker',
	NS_USER_TALK        => 'Overleg_gebruker',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => 'Overleg_$1',
	NS_IMAGE            => 'Ofbeelding',
	NS_IMAGE_TALK       => 'Overleg_ofbeelding',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Overleg_MediaWiki',
	NS_TEMPLATE         => 'Sjabloon',
	NS_TEMPLATE_TALK    => 'Overleg_sjabloon',
	NS_HELP             => 'Hulpe',
	NS_HELP_TALK        => 'Overleg_hulpe',
	NS_CATEGORY         => 'Kattegerie',
	NS_CATEGORY_TALK    => 'Overleg_kattegerie'
);

$namespaceAliases = array(
	'Speciaol'          => NS_SPECIAL,
	'Categorie'         => NS_CATEGORY,
	'Overleg_categorie' => NS_CATEGORY_TALK,
	'Overleg_help'      => NS_HELP_TALK,
);

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j, Y',
	'mdy both' => 'H:i, M j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'H:i, j M Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'H:i, Y M j',
);

/**
 * Default list of book sources
 */
$bookstoreList = array(
        'Koninklijke Bibliotheek' => 'http://opc4.kb.nl/DB=1/SET=5/TTL=1/CMD?ACT=SRCH&IKT=1007&SRT=RLV&TRM=$1'
);

/**
 * Magic words
 * Customisable syntax for wikitext and elsewhere
 *
 * Note to translators:
 *   Please include the English words as synonyms.  This allows people
 *   from other wikis to contribute more easily.
 *
 * This array can be modified at runtime with the LanguageGetMagic hook
 */
$magicWords = array(
#   ID                                 CASE  SYNONYMS
        'redirect'               => array( 0,    '#REDIRECT', '#DEURVERWIEZING' ),
        'notoc'                  => array( 0,    '__NOTOC__', '__GIENONDERWARPEN__' ),
        'nogallery'              => array( 0,    '__NOGALLERY__', '__GIENGALLERIEJE__' ),
        'forcetoc'               => array( 0,    '__FORCETOC__', '__FORCEERONDERWARPEN__' ),
        'toc'                    => array( 0,    '__TOC__', '__ONDERWARPEN__' ),
        'noeditsection'          => array( 0,    '__NOEDITSECTION__', '__GIENBEWARKSECTIE__' ),
        'currentmonth'           => array( 1,    'CURRENTMONTH', 'DISSEMAOND' ),
        'currentmonthname'       => array( 1,    'CURRENTMONTHNAME', 'DISSEMAONDNAAM' ),
        'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN', 'DISSEMAONDGEN' ),
        'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV', 'DISSEMAONDOFK' ),
        'currentday'             => array( 1,    'CURRENTDAY', 'DISSEDAG' ),
        'currentday2'            => array( 1,    'CURRENTDAY2', 'DISSEDAG2' ),
        'currentdayname'         => array( 1,    'CURRENTDAYNAME', 'DISSEDAGNAAM' ),
        'currentyear'            => array( 1,    'CURRENTYEAR', 'DITJAOR' ),
        'currenttime'            => array( 1,    'CURRENTTIME', 'DISSETIED' ),
        'currenthour'            => array( 1,    'CURRENTHOUR', 'DITURE' ),
        'localmonth'             => array( 1,    'LOCALMONTH', 'LOKALEMAOND' ),
        'localmonthname'         => array( 1,    'LOCALMONTHNAME', 'LOKALEMAONDNAAM' ),
        'localmonthnamegen'      => array( 1,    'LOCALMONTHNAMEGEN', 'LOKALEMAONDNAAMGEN' ),
        'localmonthabbrev'       => array( 1,    'LOCALMONTHABBREV', 'LOKALEMAONDOFK' ),
        'localday'               => array( 1,    'LOCALDAY', 'LOKALEDAG' ),
        'localday2'              => array( 1,    'LOCALDAY2', 'LOKALEDAG2' ),
        'localdayname'           => array( 1,    'LOCALDAYNAME', 'LOKALEDAGNAAM' ),
        'localyear'              => array( 1,    'LOCALYEAR', 'LOKAALJAOR' ),
        'localtime'              => array( 1,    'LOCALTIME', 'LOKALETIED' ),
        'localhour'              => array( 1,    'LOCALHOUR', 'LOKAALURE' ),
        'numberofpages'          => array( 1,    'NUMBEROFPAGES', 'ANTALPAGINAS', 'ANTALPAGINA\'S', 'ANTALPAGINA’S' ),
        'numberofarticles'       => array( 1,    'NUMBEROFARTICLES', 'ANTALARTIKELS' ),
        'numberoffiles'          => array( 1,    'NUMBEROFFILES', 'ANTALBESTANDEN' ),
        'numberofusers'          => array( 1,    'NUMBEROFUSERS', 'ANTALGEBRUKERS' ),
        'pagename'               => array( 1,    'PAGENAME', 'PAGINANAAM' ),
        'pagenamee'              => array( 1,    'PAGENAMEE', 'PAGINANAAME' ),
        'namespace'              => array( 1,    'NAMESPACE', 'NAAMRUUMTE' ),
        'namespacee'             => array( 1,    'NAMESPACEE', 'NAAMRUUMTEE' ),
        'talkspace'              => array( 1,    'TALKSPACE', 'OVERLEGRUUMTE' ),
        'talkspacee'             => array( 1,    'TALKSPACEE', 'OVERLEGRUUMTEE' ),
        'subjectspace'           => array( 1,    'SUBJECTSPACE', 'ARTICLESPACE', 'ONDERWARPRUUMTE', 'ARTIKELRUUMTE' ),
        'subjectspacee'          => array( 1,    'SUBJECTSPACEE', 'ARTICLESPACEE', 'ONDERWARPRUUMTEE', 'ARTIKELRUUMTEE' ),
        'fullpagename'           => array( 1,    'FULLPAGENAME', 'HELEPAGINANAAM' ),
        'fullpagenamee'          => array( 1,    'FULLPAGENAMEE', 'HELEPAGINANAAME' ),
        'subpagename'            => array( 1,    'SUBPAGENAME', 'DEELPAGINANAAM' ),
        'subpagenamee'           => array( 1,    'SUBPAGENAMEE', 'DEELPAGINANAAME' ),
        'basepagename'           => array( 1,    'BASEPAGENAME', 'BAOSISPAGINANAAM' ),
        'basepagenamee'          => array( 1,    'BASEPAGENAMEE', 'BAOSISPAGINANAAME' ),
        'talkpagename'           => array( 1,    'TALKPAGENAME', 'OVERLEGPAGINANAAM' ),
        'talkpagenamee'          => array( 1,    'TALKPAGENAMEE', 'OVERLEGPAGINANAAME' ),
        'subjectpagename'        => array( 1,    'SUBJECTPAGENAME', 'ARTICLEPAGENAME', 'ONDERWARPPAGINANAAM', 'ARTIKELPAGINANAAM' ),
        'subjectpagenamee'       => array( 1,    'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE', 'ONDERWARPPAGINANAAME', 'ARTIKELPAGINANAAME' ),
        'msg'                    => array( 0,    'MSG:', 'BERICH:' ),
        'msgnw'                  => array( 0,    'MSGNW:', 'BERICHNW' ),
        'img_right'              => array( 1,    'right', 'rechs' ),
        'img_left'               => array( 1,    'left', 'links' ),
        'img_none'               => array( 1,    'none', 'gien' ),
        'img_center'             => array( 1,    'center', 'centre', 'ecentreerd' ),
        'img_framed'             => array( 1,    'framed', 'enframed', 'frame', 'umraand' ),
        'img_page'               => array( 1,    'page=$1', 'page $1', 'pagina=$1', 'pagina $1' ),
        'img_baseline'           => array( 1,    'baseline', 'grondliende' ),
        'img_top'                => array( 1,    'top', 'boven' ),
        'img_text_top'           => array( 1,    'text-top', 'tekse-boven' ),
        'img_middle'             => array( 1,    'middle', 'midden' ),
        'img_bottom'             => array( 1,    'bottom', 'ummeneer' ),
        'img_text_bottom'        => array( 1,    'text-bottom', 'tekse-ummeneer' ),
        'sitename'               => array( 1,    'SITENAME', 'WEBSTEENAAM' ),
        'ns'                     => array( 0,    'NS:', 'NR:' ),
        'localurl'               => array( 0,    'LOCALURL:', 'LOKALEURL' ),
        'localurle'              => array( 0,    'LOCALURLE:', 'LOKALEURLE' ),
        'servername'             => array( 0,    'SERVERNAME', 'SERVERNAAM' ),
        'scriptpath'             => array( 0,    'SCRIPTPATH', 'SCRIPTPAD' ),
        'grammar'                => array( 0,    'GRAMMAR:', 'GRAMMATICA:' ),
        'notitleconvert'         => array( 0,    '__NOTITLECONVERT__', '__NOTC__', '__GIENTITELCONVERSIE__', '__GIENTC__' ),
        'nocontentconvert'       => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__', '__GIENINHOUDCONVERSIE__', '__GIENIC__' ),
        'currentweek'            => array( 1,    'CURRENTWEEK', 'DISSEWEKE' ),
        'currentdow'             => array( 1,    'CURRENTDOW', 'DISSEDVDW' ),
        'localweek'              => array( 1,    'LOCALWEEK', 'LOKALEWEKE' ),
        'localdow'               => array( 1,    'LOCALDOW', 'LOKALEDVDW' ),
        'revisionid'             => array( 1,    'REVISIONID', 'REVISIEID', 'REVISIE-ID' ),
        'revisionday'            => array( 1,    'REVISIONDAY', 'REVISIEDAG' ),
        'revisionday2'           => array( 1,    'REVISIONDAY2', 'REVISIEDAG2' ),
        'revisionmonth'          => array( 1,    'REVISIONMONTH', 'REVISIEMAOND' ),
        'revisionyear'           => array( 1,    'REVISIONYEAR', 'REVISIEJAOR' ),
        'revisiontimestamp'      => array( 1,    'REVISIONTIMESTAMP', 'REVISIETIEDSTEMPEL' ),
        'plural'                 => array( 0,    'PLURAL:', 'MEERVOUD:' ),
        'fullurl'                => array( 0,    'FULLURL:', 'HELEURL' ),
        'fullurle'               => array( 0,    'FULLURLE:', 'HELEURLE' ),
        'lcfirst'                => array( 0,    'LCFIRST:', 'HLEERSTE:' ),
        'ucfirst'                => array( 0,    'UCFIRST:', 'KLEERSTE:' ),
        'lc'                     => array( 0,    'LC:', 'KL:' ),
        'uc'                     => array( 0,    'UC:', 'HL:' ),
        'raw'                    => array( 0,    'RAW:', 'RAUW:' ),
        'displaytitle'           => array( 1,    'DISPLAYTITLE', 'TEUNTITEL' ),
        'newsectionlink'         => array( 1,    '__NEWSECTIONLINK__', '__NIEJESECTIEVERWIEZING__' ),
        'currentversion'         => array( 1,    'CURRENTVERSION', 'DISSEVERSIE' ),
        'urlencode'              => array( 0,    'URLENCODE:', 'CODEERURL' ),
        'anchorencode'           => array( 0,    'ANCHORENCODE', 'CODEERANKER' ),
        'currenttimestamp'       => array( 1,    'CURRENTTIMESTAMP', 'DISSETIEDSTEMPEL' ),
        'localtimestamp'         => array( 1,    'LOCALTIMESTAMP', 'LOKALETIEDSTEMPEL' ),
        'directionmark'          => array( 1,    'DIRECTIONMARK', 'DIRMARK', 'RICHTINGMARKERING', 'RICHTINGSMARKERING' ),
        'language'               => array( 0,    '#LANGUAGE:', '#TAAL:' ),
        'contentlanguage'        => array( 1,    'CONTENTLANGUAGE', 'CONTENTLANG', 'INHOUDSTAAL' ),
        'pagesinnamespace'       => array( 1,    'PAGESINNAMESPACE:', 'PAGESINNS:', 'PAGINASINNAAMRUUMTE', 'PAGINA’SINNAAMRUUMTE', 'PAGINA\'SINNAAMRUUMTE' ),
        'numberofadmins'         => array( 1,    'NUMBEROFADMINS', 'ANTALBEHEERDERS' ),
        'formatnum'              => array( 0,    'FORMATNUM', 'FORMATTEERNUM' ),
        'padleft'                => array( 0,    'PADLEFT', 'LINKSOPVULLEN' ),
        'padright'               => array( 0,    'PADRIGHT', 'RECHSOPVULLEN' ),
        'special'                => array( 0,    'special', 'speciaal' ),
        'defaultsort'            => array( 1,    'DEFAULTSORT:', 'STANDARDSORTERING:' )
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Dubbele_deurverwiezingen' ),
	'BrokenRedirects'           => array( 'Ebreuken_deurverwiezingen' ),
	'Disambiguations'           => array( 'Deurverwiespagina\'s' ),
	'Userlogin'                 => array( 'Anmelden' ),
	'Userlogout'                => array( 'Ofmelden' ),
	'Preferences'               => array( 'Veurkeuren' ),
	'Watchlist'                 => array( 'Volglieste' ),
	'Recentchanges'             => array( 'Leste_wiezigingen' ),
	'Upload'                    => array( 'Bestanden_toevoegen' ),
	'Imagelist'                 => array( 'Ofbeeldingenlieste' ),
	'Newimages'                 => array( 'Nieje_ofbeeldingen' ),
	'Listusers'                 => array( 'Gebrukerslieste' ),
	'Statistics'                => array( 'Staotestieken' ),
	'Randompage'                => array( 'Willekeurige_pagina' ),
	'Lonelypages'               => array( 'Weespagina\'s' ),
	'Uncategorizedpages'        => array( 'Pagina\'s_zonder_kattegerie' ),
	'Uncategorizedcategories'   => array( 'Kattergieën_zonder_kattegerie' ),
	'Uncategorizedimages'       => array( 'Ofbeeldingen_zonder_kattegerie' ),
	'Unusedcategories'          => array( 'Ongebruken_kattegerieën' ),
	'Unusedimages'              => array( 'Ongebruken_ofbeeldingen' ),
	'Wantedpages'               => array( 'Gewunste_pagina\'s' ),
	'Wantedcategories'          => array( 'Gewunste_kattegerieën' ),
	'Mostlinked'                => array( 'Meest_naor_verwezen_pagina\'s' ),
	'Mostlinkedcategories'      => array( 'Meestgebruken_kattegerieën' ),
	'Mostcategories'            => array( 'Meeste_kattegerieën' ),
	'Mostimages'                => array( 'Meeste_ofbeeldingen' ),
	'Mostrevisions'             => array( 'Meeste_bewarkingen' ),
	'Fewestrevisions'           => array( 'Minste_bewarkingen' ),
	'Shortpages'                => array( 'Korte_artikels' ),
	'Longpages'                 => array( 'Lange_artikels' ),
	'Newpages'                  => array( 'Nieje_pagina\'s' ),
	'Ancientpages'              => array( 'Oudste_pagina\'s' ),
	'Deadendpages'              => array( 'Doodlopende_deurverwiezingen' ),
	'Protectedpages'            => array( 'Beveiligen_pagina\'s' ),
	'Allpages'                  => array( 'Alle_pagina\'s' ),
	'Prefixindex'               => array( 'Prefixindex' ),
	'Ipblocklist'               => array( 'IP-blokkeerlieste' ),
	'Specialpages'              => array( 'Speciale_pagina\'s' ),
	'Contributions'             => array( 'Biedragen' ),
	'Emailuser'                 => array( 'Berich_sturen' ),
	'Whatlinkshere'             => array( 'Verwiezingen_naor_disse_pagina' ),
	'Recentchangeslinked'       => array( 'Volg_verwiezingen' ),
	'Movepage'                  => array( 'Herneum_pagina' ),
	'Blockme'                   => array( 'Blokkeer_mien' ),
	'Booksources'               => array( 'Boekinfermasie' ),
	'Categories'                => array( 'Kattegerieën' ),
	'Export'                    => array( 'Uutvoeren' ),
	'Version'                   => array( 'Versie' ),
	'Allmessages'               => array( 'Alle_systeemteksen' ),
	'Log'                       => array( 'Log', 'Logs' ),
	'Blockip'                   => array( 'Blokkeer_IP' ),
	'Undelete'                  => array( 'Weerummeplaosen' ),
	'Import'                    => array( 'Invoeren' ),
	'Lockdb'                    => array( 'Databanke_blokkeren' ),
	'Unlockdb'                  => array( 'Databanke_vriegeven' ),
	'Userrights'                => array( 'Gebrukersrechen' ),
	'MIMEsearch'                => array( 'MIME-zeuken' ),
	'Unwatchedpages'            => array( 'Neet-evolgen_pagina\'s' ),
	'Listredirects'             => array( 'Deurverwiezingslieste' ),
	'Revisiondelete'            => array( 'Versie_vortdoon' ),
	'Unusedtemplates'           => array( 'Ongebruken_sjablonen' ),
	'Randomredirect'            => array( 'Willekeurige_deurverwiezing' ),
	'Mypage'                    => array( 'Mien_gebrukerspagina' ),
	'Mytalk'                    => array( 'Mien_overleg' ),
	'Mycontributions'           => array( 'Mien_biedragen' ),
	'Listadmins'                => array( 'Beheerderslieste' ),
	'Popularpages'              => array( 'Populaire_artikels' ),
	'Search'                    => array( 'Zeuken' ),
	'Resetpass'                 => array( 'Wachwoord_opniej_instellen' ),
	'Withoutinterwiki'          => array( 'Gien_interwiki' ),
	);

$linkTrail = '/^([a-zäöüïëéèà]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-hideminor'        => 'Kleine wiezigingen verbargen in leste wiezigingen',
'tog-watchcreations'   => 'Artikels dee-j anmaken an volglieste toevoegen',
'tog-watchdefault'     => 'Artikels dee-j wiezigen an volglieste toevoegen',
'tog-watchmoves'       => "Pagina's dee-k herneume op mien volglieste zetten",
'tog-watchdeletion'    => 'Voeg pagina dee-k vortdo an mien volglieste toe',
'tog-uselivepreview'   => 'Gebruuk "rechstreekse veurbeschouwing" (mu-j JavaScript veur hemmen - experimenteel)',
'tog-nolangconversion' => 'Ummezetten van varianten uutschakelen',

# Dates
'sunday'        => 'zundag',
'monday'        => 'maondag',
'tuesday'       => 'diensdag',
'wednesday'     => 'woonsdag',
'thursday'      => 'donderdag',
'friday'        => 'vriedag',
'saturday'      => 'zaoterdag',
'sun'           => 'zun',
'mon'           => 'mao',
'tue'           => 'die',
'wed'           => 'woo',
'fri'           => 'vrie',
'sat'           => 'zao',
'january'       => 'jannewaori',
'february'      => 'febrewaori',
'march'         => 'meert',
'april'         => 'april',
'may_long'      => 'mei',
'june'          => 'juni',
'july'          => 'juli',
'august'        => 'augustus',
'september'     => 'september',
'october'       => 'oktober',
'november'      => 'november',
'december'      => 'december',
'january-gen'   => 'jannewaori',
'february-gen'  => 'febrewaori',
'march-gen'     => 'meert',
'april-gen'     => 'april',
'may-gen'       => 'mei',
'june-gen'      => 'juni',
'july-gen'      => 'juli',
'august-gen'    => 'augustus',
'september-gen' => 'september',
'october-gen'   => 'oktober',
'november-gen'  => 'november',
'december-gen'  => 'december',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'mrt',
'apr'           => 'apr',
'may'           => 'mei',
'jun'           => 'jun',
'aug'           => 'aug',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'dec',

# Bits of text used by many pages
'pagecategories' => '{{PLURAL:$1|Kattegerie|Kattegerieën}}',

'mainpagedocfooter' => "Raodpleeg de [http://meta.wikimedia.org/wiki/Help:Contents haandleiding] veur infermasie over 't gebruuk van de wikipregrammetuur.

== Meer hulpe ==
* [http://www.mediawiki.org/wiki/Help:Configuration_settings Lieste mit instellingen]
* [http://www.mediawiki.org/wiki/Help:FAQ MediaWiki-vragen dee vake esteld wonnen]
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki-poslieste veur nieje versies]",

'cancel'     => 'Annuleren',
'mytalk'     => 'Mien overleg',
'navigation' => 'Navigasie',
'and'        => 'en',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'tagline'        => 'Van {{SITENAME}}',
'help'           => 'Hulp en kontak',
'search'         => 'Zeuken',
'searchbutton'   => 'Zeuken',
'history_short'  => 'Geschiedenisse',
'print'          => 'Ofdrokken',
'edit'           => 'bewark',
'delete'         => 'vortdoon',
'undelete_short' => '$1 {{PLURAL:$1|versie|versies}} weerummeplaosen',
'protect'        => 'Beveiligen',
'unprotect'      => 'ontgrendelen',
'talkpage'       => 'Overlegpagina',
'personaltools'  => 'Persoonlijke instellingen',
'postcomment'    => 'Opmarking plaosen',
'talk'           => 'Overleeg',
'toolbox'        => 'Hulpmiddels',
'viewcount'      => 'Disse pagina is $1 {{PLURAL:$1|keer|keer}} bekeken.',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Over {{SITENAME}}',
'aboutpage'         => 'Project:Info',
'bugreportspage'    => 'Project:Foutrepporten',
'copyright'         => 'De inhold is beschikbaor onder de $1.',
'copyrightpagename' => '{{SITENAME}}-auteursrechen',
'copyrightpage'     => '{{ns:project}}:Auteursrechen',
'currentevents'     => "In 't niejs",
'currentevents-url' => "Project:In 't niejs",
'disclaimers'       => 'Veurbehold',
'disclaimerpage'    => 'Project:Veurbehold',
'faqpage'           => 'Project:Vragen dee vake esteld wonnen',
'helppage'          => 'Help:Inhold',
'mainpage'          => 'Veurpagina',
'policy-url'        => 'Project:Beleid',
'portal'            => 'Gebrukerspertaol',
'portal-url'        => 'Project:Gebrukerspertaol',
'privacypage'       => 'Project:Gegevensbeleid',
'sitesupport'       => 'Financiële steun',
'sitesupport-url'   => 'Project:Financiële steun',

'badaccess-group1' => 'Disse actie kan allinnig uut-evoerd wonnen deur gebrukers dee tot de groep $1 beheuren.',
'badaccess-group2' => 'Disse actie kan allinnig uut-evoerd wonnen deur gebrukers dee tot een van groepen $1 beheuren.',
'badaccess-groups' => 'Disse actie kan allinnig uut-evoerd wonnen deur gebrukers dee tot een van de groepen $1 beheuren.',

'versionrequiredtext' => 'Versie $1 van MediaWiki is neudig um disse pagina te gebruken. Zie [[Special:Version|Versie]].',

'restorelink'      => '{{PLURAL:$1|versie dee vort-edaon is|versies dee vort-edaon bin}}',
'feed-unavailable' => 'Syndicasiefeeds bin neet beschikbaor op {{SITENAME}}',
'site-rss-feed'    => '$1 RSS-feed',
'site-atom-feed'   => '$1 Atom-feed',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'      => 'Gebruker',
'nstab-special'   => 'Speciaal',
'nstab-mediawiki' => 'Berich',
'nstab-template'  => 'Sjabloon',

# Main script and global functions
'nosuchaction'      => 'De op-egeven haandeling besteet neet',
'nospecialpagetext' => "<big>'''Disse speciale pagina wonnen neet herkend deur de pregrammetuur.'''</big>

Een lieste mit bestaonde speciale pagina ku-j vienen op [[Special:Specialpages|Speciaal:Speciale pagina’s]].",

# General errors
'noconnect'           => 'De databanke is noen neet bereikbaor. <br />
$1',
'nodb'                => 'Selectie van databanke $1 is neet meugelijk.',
'enterlockreason'     => "Geef een rejen veur de blokkering op en hoelange 't geet duren. De op-egeven rejen zal an de gebrukers eteund wonnen.",
'readonlytext'        => "De databanke van {{SITENAME}} is noen esleuten veur nieje bewarkingen en wiezigingen, werschienlijk veur bestansonderhoud. De verantwoordelijke systeembeheerder gaf hierveur de volgende rejen op: '''$1'''",
'internalerror'       => 'Interne fout',
'internalerror_info'  => 'Interne fout: $1',
'filecopyerror'       => 'Kon bestand "$1" neet naor "$2" kopiëren.',
'viewsource'          => 'brontekse bekieken',
'protectedinterface'  => 'Disse pagina bevat een tekse dee gebruuk wonnen veur systeemteksen van de wiki. Allinnig beheerders kunnen disse pagina bewarken.',
'editinginterface'    => "'''Waorschuwing:''' je bewarken een pagina dee gebruuk wonnen deur de pregrammetuur. Wiezigingen dee an-ebröch wonnen op disse pagina zullen 't uterlijk veur iederene beïnvleujen. Overweeg veur vertalingen um [http://translatewiki.net/wiki/Main_Page?setlang=nds-nl Betawiki] te gebruken, 't vertalingsprejek veur MediaWiki.",
'cascadeprotected'    => 'Disse pagina is beveilig umdat \'t veurkump in de volgende {{PLURAL:$1|pagina|pagina\'s}}, dee beveilig {{PLURAL:$1|is|bin}} mit de "cascade"-optie:
$2',
'ns-specialprotected' => "Speciale pagina's kunnen neet bewörk wonnen.",
'titleprotected'      => "'t Anmaken van disse pagina is beveilig deur [[User:$1|$1]].
De op-egeven rejen is ''$2''.",

# Login and logout pages
'logouttext'                => 'Je bin noen of-emeld. Je kunnen {{SITENAME}} noen anneniem gebruken of onder een aandere gebrukersnaam weer anmelden.',
'externaldberror'           => 'Der gung iets fout bie de externe authenticering, of je maggen je gebrukersprefiel neet bewarken.',
'loginprompt'               => 'Je mutten [[cookie]]s an hemmen staon um an te kunnen melden bie {{SITENAME}}.',
'userlogout'                => 'Ofmelden',
'createaccountmail'         => 'per e-mail',
'prefs-help-email-required' => 'Hier he-w een e-mailadres veur neudig.',
'loginsuccess'              => 'Je bin noen an-emeld bie {{SITENAME}} as "$1".',
'passwordremindertitle'     => 'niej tiedelik wachwoord veur {{SITENAME}}',
'passwordremindertext'      => 'Iemand vanof \'t IP-adres $1 (werschienlijk jiezelf) hef evreugen um een niej wachwoord veur {{SITENAME}} ($4) toe te sturen. \'t Nieje wachwoord veur gebruker "$2" is "$3". Advies: noen anmelden en \'t wachwoord wiezigigen.',
'blocked-mailpassword'      => 'Dit IP-adres is eblokkeerd. Dit betekent da-j neet bewarken kunnen en dat {{SITENAME}} joew wachwoord neet weerummehaolen kan, dit wonnen edaon um misbruuk tegen te gaon.',
'createaccount-title'       => 'Gebrukers anmaken veur {{SITENAME}}',
'createaccount-text'        => 'Der hef der ene ($1) een gebruker veur $2 an-emaak op {{SITENAME}}
($4). \'t Wachwoord veur "$2" is "$3". Meld je noen an en wiezig \'t wachwoord.

Negeer dit berich as disse gebruker zonder joew toestemming an-emaak is.',
'loginlanguagelabel'        => 'Taal: $1',

# Edit page toolbar
'extlink_sample' => 'http://www.veurbeeld.com/ linktekst',

# Edit pages
'summary'                  => 'Samenvatting',
'missingcommentheader'     => "'''Let wel:''' je hemmen gien onderwarptitel toe-evoeg. A-j opniej op Pagina opslaon klikken wonnen de bewarking op-esleugen zonder onderwarptitel.",
'blockedtext'              => "<big>'''Joew gebrukersnaam of IP-adres is eblokkeerd.'''</big>

Je bin eblokkeerd deur: $1.
De op-egeven rejen is: ''$2''.

* Eblokkeerd vanof: $8
* Eblokkeerd tot: $6
* Bedoeld um te blokkeren: $7

Je kunnen kontak opnemen mit $1 of een aandere [[{{MediaWiki:Grouppage-sysop}}|beheerder]] um de blokkering te bepraoten.
Je kunnen gien gebruukmaken van de functie 'een berich sturen', behalven a-j een geldig e-mailadres op-egeven hemmen in joew [[Special:Preferences|veurkeuren]] en 't gebruuk van disse functie neet eblokkeerd is.
't IP-adres da-j noen gebruken is $3 en 't blokkeringsnummer is #$5. Vermeld ze allebeie a-j argens op disse blokkering reageren.",
'autoblockedtext'          => 'Joew IP-adres is autematisch eblokkeerd umdat \'t gebruuk wönnen deur een aandere gebruker, dee eblokkeerd wönnen deur $1.
De rejen hierveur was:

:\'\'$2\'\'

* Aanvang: $8
* Verloop nao: $6

Je kunnen kontak opnemen mit $1 of een van de aandere
[[{{MediaWiki:grouppage-sysop}}|beheerders]] um de blokkering te bespreken.

NB: je kunnen de optie "een berich sturen" neet gebruken, behalven a-j een geldig e-mailadres op-egeven hemmen in de [[Special:Preferences|gebrukersveurkeuren]].

Joew blokkeer-ID is $5. Geef dit nummer deur a-j kontak mit ene opnemen over de blokkering.',
'whitelistedittitle'       => 'Anmelden is verplich',
'whitelistedittext'        => "Um pagina's te kunnen wiezigen, mu-j $1 ween",
'whitelistreadtitle'       => 'Anmelden is verplich',
'whitelistacctext'         => 'Nieje gebrukersprefielen op {{SITENAME}} kunnen allinnig an-emaak wonnen deur [[Special:Userlogin|an-emelde]] gebrukers mit de juuste rechten.',
'newarticletext'           => '<div class=plainlinks style="border: 1px solid #ccc; padding: .2em 1em; margin-bottom: .8em; color: #000;"><div align=right style="font-size:110%; float:right; border:none; width:40%;">[[Special:Prefixindex/{{PAGENAME}}|Prefixindex]] | [[Special:Search/{{PAGENAME}}|Zeuken]]</div><div style="font-size:110%;">Je bin de pagina "{{FULLPAGENAME}}" an \'t schrieven...</div></div>',
'anontalkpagetext'         => "<hr style=\"margin-top:2em;\">
<em>Disse overlegpagina heurt bie een annenieme gebruker dee: óf gien gebrukersnaam hef, óf 't neet gebruuk. We gebruken daorumme 't IP-adres ter herkenning, mar 't kan oek ween dat meerdere personen 'tzelfde IP-adres gebruken, en da-j hiermee berichen ontvangen dee neet veur joe bedoeld bin. A-j dit veurkoemen willen, dan ku-j 't bes [[Special:Userlogin|een gebrukersnaam anmaken of anmelden]].</em>",
'noarticletext'            => 'Disse pagina besteet nog neet.
Je kunnen \'t woord [[Special:Search/{{PAGENAME}}|opzeuken]] in aandere pagina\'s of <span class="plainlinks">[{{fullurl:{{FULLPAGENAME}}|action=edit}} disse pagina bewarken]</span>.',
'clearyourcache'           => "'''NB:''' naodat de wiezigingen op-esleugen bin, mut de kas van de webblaojeraar nog leeg-emaak wonnen um 't daodwarkelijk te zien.

{| border=\"1\" cellpadding=\"3\" class=toccolours style=\"border: 1px #AAAAAA solid; border-collapse: collapse;\"
| Mozilla (oek Firefox) || ctrl-shift-r
|-
| IE || ctrl-f5
|-
| Opera || f5
|-
| Safari || cmd-r
|-
| Konqueror || f5
|}",
'userinvalidcssjstitle'    => "'''Waorschuwing:''' der is gien uutvoering mit de naam \"\$1\". Vergeet neet dat joew eigen .css- en .js-pagina's beginnen mit een kleine letter, bv. \"{{ns:user}}:Naam/'''m'''onobook.css\" in plaose van \"{{ns:user}}:Naam/'''M'''onobook.css\".",
'token_suffix_mismatch'    => "<strong>De bewarking is eweigerd umdat joew webblaojeraar de leestekens in 't bewarkingstoken verkeerd behaandeld hef. De bewarking is eweigerd um verminking van de paginatekse te veurkoemen. Dit gebeurt soms as der een web-ebaseren proxydiens gebruuk wonnen dee fouten bevat.</strong>",
'copyrightwarning'         => "NB: Alle biedragen an {{SITENAME}} mutten vrie-egeven wonnen onder de $2 (zie $1 veur infermasie).
A-j neet willen dat joew tekse deur aandere gebrukers an-epas en verspreid kan wonnen, kies dan neet veur 'Pagina opslaon'.<br />
Deur op 'Pagina opslaon' te klikken beleuf je da-j disse tekse zelf eschreven hemmen, of over-eneumen hemmen uut een vrieje, openbaore bron.<br />
<strong>GEBRUUK GIEN MATERIAAL DAT BESCHARMP WONNEN DEUR AUTEURSRECHEN, BEHALVEN A-J DAOR TOESTEMMING VEUR HEMMEN!</strong>",
'copyrightwarning2'        => "Let wel dat alle biedragen an {{SITENAME}} deur aandere gebrukers ewiezig of vort-edaon kunnen wonnen. A-j neet willen dat joew tekse veraanderd wonnen, plaos 't hier dan neet.<br />
De tekse mut auteursrechvrie ween (zie $1 veur details).
<strong>GIEN WARK VAN AANDERE LUUI TOEVOEGEN ZONDER TOESTEMMING VAN DE AUTEUR!</strong>",
'protectedpagewarning'     => "<strong>Waorschuwing! Disse pagina is beveilig zodat allinnig beheerders 't kunnen wiezigen.</strong>",
'semiprotectedpagewarning' => "'''Let op:''' disse pagina is allinnig te bewarken deur gebrukers dee tenminsen 4 dagen in-eschreven bin.",
'cascadeprotectedwarning'  => "'''Waorschuwing:''' disse pagina is beveilig zodat allinnig beheerders disse pagina kunnen bewarken, dit wonnen edaon umdat disse pagina veurkump in de volgende {{PLURAL:$1|cascade-beveilige pagina|cascade-beveiligen pagina's}}:",
'titleprotectedwarning'    => "<strong>Waorschuwing: disse pagina is beveilig zodat allinnig bepaolde gebrukers 't an kunnen maken.</strong>",
'nocreatetitle'            => "'t Anmaken van pagina's is beteund",
'permissionserrorstext'    => 'Je maggen of kunnen dit neet doon. De {{PLURAL:$1|rejen|rejens}} daoveur {{PLURAL:$1|is|bin}}:',

# History pages
'revisionasof'  => 'Versie op $1',
'revision-info' => 'Versie op $1 van $2',
'page_first'    => 'eerste',
'historysize'   => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'  => '(leeg)',

# Revision feed
'history-feed-item-nocomment' => '$1 op $2', # user at time

# Revision deletion
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
De geschiedenisse van disse pagina is uut de peblieke archieven ewis.
Der kan veerdere infermasie staon in \'t [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} logboek vort-edaone pagina\'s].
</div>',
'rev-deleted-text-view'       => "<div class=\"mw-warning plainlinks\">
De geschiedenisse van disse pagina is uut de peblieke archieven ewis.
As beheerder van disse wiki ku-j 't wel zien;
der kan veerdere infermasie staon in 't [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} logboek vort-edaone pagina's].
</div>",
'revdelete-selected'          => "{{PLURAL:$2|Esillekteren bewarking|Esillekteren bewarkingen}} van '''[[:$1]]''':",
'revdelete-text'              => "Bewarkingen dee vort-ehaold bin, ku-j wel zien in de geschiedenisse, mar de inhoud is neet langer pebliekelijk toegankelijk.

Aandere beheerders van {{SITENAME}} kunnen de verbörgen inhoud bekieken en 't weerummeplaosen mit behulpe van dit scharm, behalven as der aandere beparkingen gelden dee in-esteld bin deur de systeembeheerder.",
'revdelete-submit'            => 'De esillecteren versie toepassen',
'logdelete-logentry'          => 'wiezigen zichbaorheid van gebeurtenisse [[$1]]',
'revdelete-logaction'         => '$1 {{PLURAL:$1|wieziging|wiezigingen}} in-esteld naor modus $2',
'logdelete-success'           => "'''Zichbaorheid van de gebeurtenisse is succesvol in-esteld.'''",

# History merging
'mergehistory'        => "Geschiedenisse van pagina's bie mekaar doon",
'mergehistory-from'   => 'Bronpagina:',
'mergehistory-into'   => 'Bestemmingspagina:',
'mergehistory-list'   => 'Samenvoegbaore bewarkingsgeschiedenisse',
'mergehistory-go'     => 'Samenvoegbaore bewarkingen bekieken',
'mergehistory-submit' => 'Versies bie mekaar doon',

# Diffs
'history-title' => 'Geschiedenisse van "$1"',
'lineno'        => 'Regel $1:',

# Preferences page
'preferences' => 'Veurkeuren',

# User rights
'userrights-reason'       => 'Rejen:',
'userrights-no-interwiki' => "Je hemmen gien rechen um gebrukersrechen op aandere wiki's te wiezigen.",
'userrights-nodatabase'   => 'Databanke $1 besteet neet of is gien plaoselijke databanke.',
'userrights-nologin'      => 'Je mutten [[Special:Userlogin|an-emeld]] ween en as gebruker de juuste rechen hemmen um gebrukersrechen toe te kunnen wiezen.',
'userrights-notallowed'   => 'Je hemmen gien rechen um gebrukersrechen toe te kunnen wiezen.',

'grouppage-autoconfirmed' => '{{ns:project}}:Eregistreren gebrukers',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Beheerder',
'grouppage-bureaucrat'    => '{{ns:project}}:Beheerder',

# Recent changes
'nchanges'      => '$1 {{PLURAL:$1|wieziging|wiezigingen}}',
'recentchanges' => 'Recente wiezigingen',
'hist'          => 'gesch',
'newpageletter' => 'N',

# Recent changes linked
'recentchangeslinked' => 'Volg verwiezigingen',

# Upload
'upload'                     => 'Bestand toevoegen',
'upload_directory_read_only' => "Op 't mement ku-j gien bestanden toevoegen wegens technische rejens ($1).",
'uploadlogpagetext'          => 'Hieronder steet een lieste mit bestanden dee net niej bin.',
'filetype-badmime'           => 'Bestanden mit \'t MIME-type "$1" maggen hier neet toe-evoeg wonnen.',
'fileexists-forbidden'       => "Een ofbeelding mit disse naam besteet al; je wonnen verzoch 't toe te voegen onder een aandere naam.
[[Ofbeelding:$1|thumb|center|$1]]",
'uploadedimage'              => 'Toe-evoeg: [[$1]]',

# Image list
'sharedupload' => 'Dit bestand is een gedeelde upload en kan ook deur andere prejekken ebruukt worden.',

# File reversion
'filerevert-intro'          => '<span class="plainlinks">Je bin \'\'\'[[Media:$1|$1]]\'\'\' an \'t weerummedreien tot de [$4 versie van $2, $3]</span>.',
'filerevert-defaultcomment' => 'Weerummedreid tot de versie van $1, $2',

# Random page
'randompage' => 'Willekeurig artikel',

# Miscellaneous special pages
'nbytes'       => '$1 {{PLURAL:$1|byte|bytes}}',
'allpages'     => "Alle pagina's",
'prefixindex'  => 'Veurvoegselindex',
'specialpages' => "Speciale pagina's",
'move'         => 'herneum',

'categoriespagetext' => 'De volgende kattegerieën bin anwezig in {{SITENAME}}.',

# Special:Log
'log'           => 'Logboeken',
'all-logs-page' => 'Alle logboeken',

# Special:Allpages
'nextpage' => 'Volgende pagina ($1)',

# Watchlist
'watchlist' => 'Volglieste',
'watch'     => 'volg',
'unwatch'   => 'neet volgen',

# Delete/protect/revert
'deleteotherreason' => 'Aandere/extra rejen:',
'protectcomment'    => 'Rejen',

# Undelete
'undelete'               => "Vort-edaone pagina's bekieken",
'undeletepage'           => "Vort-edaone pagina's bekieken en weerummeplaosen",
'undeletehistorynoadmin' => "Disse pagina is vort-edaon. De rejen hierveur steet hieronder, samen mit de infermasie van de gebrukers dee dit artikel ewiezig hemmen veurdat 't vort-edaon is. De tekse van 't artikel is allinnig zichbaor veur beheerders.",

# Contributions
'contributions' => 'Biedragen van disse gebruker',
'mycontris'     => 'mien biedragen',

'sp-newimages-showfrom' => 'Teun nieje ofbeeldingen vanof $1, $2',

# What links here
'whatlinkshere'      => 'Verwiezingen naor disse pagina',
'whatlinkshere-prev' => '{{PLURAL:$1|veurige|veurige $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|volgende|volgende $1}}',

# Block/unblock
'ipbreasonotherlist'          => 'aandere rejen',
'ipbreason-dropdown'          => "*Algemene rejens veur 't blokkeren
** pagina's leegmaken
** ongewunste verwiezingen toevoegen
** anmaken van onzinpagina's
** targerieje en/of intimiderend gedrag
** sokpopperieje
** onacceptabele gebrukersnaam
** vandelisme",
'ipbotherreason'              => 'Aandere/extra rejen:',
'ipb-edit-dropdown'           => 'Blokkeerrejens bewarken',
'blocklistline'               => 'Op $1 (vervuilt op $4) blokkeren $2: $3',
'autoblocker'                 => 'Vanzelf eblokkeerd umdat \'t IP-adres overenekump mit \'t IP-adres van [[User:$1|$1]], dee eblokkeerd is mit as rejen: "$2"',
'sorbsreason'                 => 'Joew IP-adres is op-eneumen as open proxyserver in de DNS-blacklist de {{SITENAME}} ebruukt.',
'sorbs_create_account_reason' => 'Joew IP-adres is op-eneumen as open proxyserver in de DNS-blacklist de {{SITENAME}} ebruukt.
Je kunnen gien gebrukerspagina anmaken.',

# Developer tools
'lockconfirm'       => 'Ja, ik wille de databanke blokkeren.',
'unlockconfirm'     => 'Ja, ik wille de databanke vriegeven.',
'databasenotlocked' => 'De databanke is neet eblokkeerd.',

# Move page
'movereason' => 'Rejen:',

# Export
'exportnohistory' => "----
'''NB:''' 't uutvoeren van de hele geschiedenisse is uut-eschakeld vanwegen prestasierejens.",

# Namespace 8 related
'allmessages'               => 'Alle systeemteksten',
'allmessagesnotsupportedDB' => "Der is gien ondersteuning veur '''{{ns:special}}:AllMessages''' umdat '''\$wgUseDatabaseMessages''' uut-eschakeld is.",

# Special:Import
'importnofile' => 'Der is gien invoerbestand toe-evoeg.',

# Tooltip help for the actions
'tooltip-search'                  => '{{SITENAME}} deurzeuken',
'tooltip-minoredit'               => 'Markeer as een kleine wieziging',
'tooltip-save'                    => 'Wiezigingen opslaon',
'tooltip-preview'                 => "Bekiek joew versie veurda-j 't opslaon (anbeveulen)!",
'tooltip-diff'                    => 'Teun de deur joe an-ebröchen wiezigingen.',
'tooltip-compareselectedversions' => 'Teun de verschillen tussen de ekeuzen versies.',
'tooltip-watch'                   => 'Voeg disse pagina toe an joew volglieste',

# Metadata
'metadata'        => 'Metadata',
'metadata-help'   => 'Dit bestand bevat metadata mit EXIF-infermasie, dee deur een fotocamera, scanner of fotobewarkingspregramma toe-evoeg kan ween.',
'metadata-fields' => 'EXIF-gegevens dee zichbaor bin as de tebel in-eklap is. De overige gegevens bin zichbaor as de tebel uut-eklap is, nao \'t klikken op "Teun uut-ebreien gegevens".
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-usercomment'       => 'Opmarkingen',
'exif-gpsaltituderef'    => 'Heugterifferentie',
'exif-gpsdestbearingref' => 'Rifferentie veur richting naor bestemming',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-m' => 'Miel per ure',

# E-mail address confirmation
'confirmemail_loggedin' => 'Joew e-mailadres is noen bevestig.',

# Scary transclusion
'scarytranscludetoolong' => '[URL is te lange]',

# Special:Filepath
'filepath-summary' => "Disse speciale pagina geef 't hele pad veur een bestand. Ofbeeldingen wonnen in resolusie helemaole weer-egeven. Aandere bestanstypen wonnen gelieke in 't mit 't MIME-type verbunnen pregramma los edaon.

Voer de bestansnaam in zonder 't veurvoegsel \"{{ns:image}}:\".",

);
