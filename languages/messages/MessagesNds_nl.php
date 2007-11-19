<?php
/** Nedersaksisch (Nedersaksisch)
 *
 * @addtogroup Language
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>, Jens Frank
 * @author Gangleri
 * @author SPQRobin
 * @author G - ג
 * @author Erwin85
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

$linkTrail = '/^([a-zäöüïëéèà]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-hideminor'      => 'Kleine wiezigingen verbargen in leste wiezigingen',
'tog-watchcreations' => 'Artikels dee-j anmaken an volglieste toevoegen',
'tog-watchdefault'   => 'Artikels dee-j wiezigen an volglieste toevoegen',
'tog-watchmoves'     => "Pagina's dee-k herneum an mien volglieste toevoegen",
'tog-watchdeletion'  => 'Voeg pagina dee-k vortdo an mien volglieste toe',

# Dates
'sunday'       => 'zundag',
'monday'       => 'maondag',
'tuesday'      => 'diensdag',
'wednesday'    => 'woonsdag',
'friday'       => 'vriedag',
'saturday'     => 'zaoterdag',
'sun'          => 'zun',
'mon'          => 'mao',
'tue'          => 'die',
'wed'          => 'woo',
'fri'          => 'vrie',
'sat'          => 'zao',
'january'      => 'jannewaori',
'february'     => 'febrewaori',
'march'        => 'meert',
'january-gen'  => 'jannewaori',
'february-gen' => 'febrewaori',
'march-gen'    => 'meert',

'mytalk'     => 'Mien overleg',
'navigation' => 'Navigasie',

'help'          => 'Hulp en kontak',
'search'        => 'Zeuken',
'searchbutton'  => 'Zeuken',
'history_short' => 'Geschiedenisse',
'print'         => 'Ofdrokken',
'edit'          => 'bewarken',
'delete'        => 'vortdoon',
'protect'       => 'Beveiligen',
'unprotect'     => 'ontgrendelen',
'talk'          => 'Overleg',
'toolbox'       => 'Hulpmiddels',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Over {{SITENAME}}',
'copyright'         => 'De inhold is beschikbaor onder de $1.',
'currentevents'     => "In 't niejs",
'currentevents-url' => "Project:In 't niejs",
'disclaimers'       => 'Veurbehold',
'disclaimerpage'    => 'Project:Veurbehold',
'mainpage'          => 'Heufdpagina',
'portal'            => 'Gebrukerspertaol',
'portal-url'        => 'Project:Gebrukerspertaol',
'sitesupport'       => 'Financiële steun',
'sitesupport-url'   => 'Project:Financiële steun',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'      => 'Gebruker',
'nstab-special'   => 'Speciaol',
'nstab-mediawiki' => 'Berich',

# General errors
'viewsource' => 'brontekse bekieken',

# Login and logout pages
'userlogout' => 'Ofmelden',

# Diffs
'history-title' => 'Geschiedenisse van "$1"',

# Preferences page
'preferences' => 'Veurkeuren',

# Recent changes
'recentchanges' => 'Recente wiezigingen',

# Recent changes linked
'recentchangeslinked' => 'Volg verwiezigingen',

# Upload
'upload' => 'Bestand toevoegen',

# Random page
'randompage' => 'Willekeurig artikel',

# Miscellaneous special pages
'allpages'     => "Alle pagina's",
'specialpages' => "Speciaole pagina's",
'move'         => 'herneumen',

# Watchlist
'watchlist' => 'Volglieste',
'watch'     => 'volgen',
'unwatch'   => 'neet volgen',

# Undelete
'undelete' => 'Verwiederde pagina herstellen',

# Contributions
'contributions' => 'Biedraogen van disse gebruker',
'mycontris'     => 'mien biedraogen',

# What links here
'whatlinkshere' => 'Verwiezingen naor disse pagina',

# Namespace 8 related
'allmessages' => 'Alle systeemteksten',

);
