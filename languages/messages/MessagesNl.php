<?php
/** Dutch (Nederlands)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Annabel
 * @author Arent
 * @author AvatarTeam
 * @author B4bol4t
 * @author Basvb
 * @author Breghtje
 * @author DasRakel
 * @author Effeietsanders
 * @author Erwin
 * @author Erwin85
 * @author Extended by Hendrik Maryns <hendrik.maryns@uni-tuebingen.de>, March 2007.
 * @author Flightmare
 * @author Fryed-peach
 * @author Galwaygirl
 * @author Geitost
 * @author GerardM
 * @author Hamaryns
 * @author HanV
 * @author Hansmuller
 * @author Jens Liebenau
 * @author JurgenNL
 * @author Kaganer
 * @author Kippenvlees1
 * @author Krinkle
 * @author MarkvA
 * @author McDutchie
 * @author Mihxil
 * @author Multichill
 * @author Mwpnl
 * @author Naudefj
 * @author Niels
 * @author Niknetniko
 * @author Paul B
 * @author Romaine
 * @author SPQRobin
 * @author Saruman
 * @author Servien
 * @author Siebrand
 * @author Sjoerddebruin
 * @author Slomox
 * @author Southparkfan
 * @author TBloemink
 * @author Tedjuh10
 * @author Tjcool007
 * @author Trijnstel
 * @author Troefkaart
 * @author Tvdm
 * @author User555
 * @author Vogone
 * @author WTM
 * @author Wiki13
 * @author Wikiklaas
 * @author Wolf Lambert
 * @author לערי ריינהארט
 */

$separatorTransformTable = array( ',' => '.', '.' => ',' );

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciaal',
	NS_TALK             => 'Overleg',
	NS_USER             => 'Gebruiker',
	NS_USER_TALK        => 'Overleg_gebruiker',
	NS_PROJECT_TALK     => 'Overleg_$1',
	NS_FILE             => 'Bestand',
	NS_FILE_TALK        => 'Overleg_bestand',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Overleg_MediaWiki',
	NS_TEMPLATE         => 'Sjabloon',
	NS_TEMPLATE_TALK    => 'Overleg_sjabloon',
	NS_HELP             => 'Help',
	NS_HELP_TALK        => 'Overleg_help',
	NS_CATEGORY         => 'Categorie',
	NS_CATEGORY_TALK    => 'Overleg_categorie',
);

$namespaceAliases = array(
	'Afbeelding' => NS_FILE,
	'Overleg_afbeelding' => NS_FILE_TALK,
);

$datePreferences = array(
	'default',
	'dmy',
	'ymd',
	'ISO 8601',
);

$defaultDateFormat = 'dmy';

$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'j M Y H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'Y M j H:i',
);

$bookstoreList = array(
	'Koninklijke Bibliotheek' => 'http://opc4.kb.nl/DB=1/SET=5/TTL=1/CMD?ACT=SRCH&IKT=1007&SRT=RLV&TRM=$1'
);

$magicWords = array(
	'redirect'                  => array( '0', '#DOORVERWIJZING', '#REDIRECT' ),
	'notoc'                     => array( '0', '__GEENINHOUD__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__GEEN_GALERIJ__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__INHOUD_DWINGEN__', '__FORCEERINHOUD__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__INHOUD__', '__TOC__' ),
	'noeditsection'             => array( '0', '__NIETBEWERKBARESECTIE__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'HUIDIGEMAAND', 'HUIDIGEMAAND2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'HUIDIGEMAAND1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'HUIDIGEMAANDNAAM', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'HUIDIGEMAANDGEN', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'HUIDIGEMAANDAFK', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'HUIDIGEDAG', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'HUIDIGEDAG2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'HUIDIGEDAGNAAM', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'HUIDIGJAAR', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'HUIDIGETIJD', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'HUIDIGUUR', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'PLAATSELIJKEMAAND', 'LOKALEMAAND', 'LOKALEMAAND2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'LOKALEMAAND1', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'PLAATSELIJKEMAANDNAAM', 'LOKALEMAANDNAAM', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'PLAATSELIJKEMAANDNAAMGEN', 'LOKALEMAANDNAAMGEN', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'PLAATSELIJKEMAANDAFK', 'LOKALEMAANDAFK', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'PLAATSELIJKEDAG', 'LOKALEDAG', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'PLAATSELIJKEDAG2', 'LOKALEDAG2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'PLAATSELIJKEDAGNAAM', 'LOKALEDAGNAAM', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'PLAATSELIJKJAAR', 'LOKAALJAAR', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'PLAATSELIJKETIJD', 'LOKALETIJD', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'PLAATSELIJKUUR', 'LOKAALUUR', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'AANTALPAGINAS', 'AANTALPAGINA\'S', 'AANTALPAGINA’S', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'AANTALARTIKELEN', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'AANTALBESTANDEN', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'AANTALGEBRUIKERS', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'AANTALACTIEVEGEBRUIKERS', 'ACTIEVEGEBRUIKERS', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'AANTALBEWERKINGEN', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'PAGINANAAM', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'PAGINANAAME', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'NAAMRUIMTE', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'NAAMRUIMTEE', 'NAMESPACEE' ),
	'namespacenumber'           => array( '1', 'NAAMRUIMTENUMMER', 'NAMESPACENUMBER' ),
	'talkspace'                 => array( '1', 'OVERLEGRUIMTE', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'OVERLEGRUIMTEE', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'ONDERWERPRUIMTE', 'ARTIKELRUIMTE', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'ONDERWERPRUIMTEE', 'ARTIKELRUIMTEE', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'VOLLEDIGEPAGINANAAM', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'VOLLEDIGEPAGINANAAME', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'DEELPAGINANAAM', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'DEELPAGINANAAME', 'SUBPAGENAMEE' ),
	'rootpagename'              => array( '1', 'ROOTPAGINANAAM', 'ROOTPAGENAME' ),
	'rootpagenamee'             => array( '1', 'ROOTPAGINANAAME', 'ROOTPAGENAMEE' ),
	'basepagename'              => array( '1', 'BASISPAGINANAAM', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'BASISPAGINANAAME', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'OVERLEGPAGINANAAM', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'OVERLEGPAGINANAAME', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'ONDERWERPPAGINANAAM', 'ARTIKELPAGINANAAM', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'ONDERWERPPAGINANAAME', 'ARTIKELPAGINANAAME', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'BERICHT:', 'MSG:' ),
	'subst'                     => array( '0', 'VERV:', 'SUBST:' ),
	'safesubst'                 => array( '0', 'VEILIGVERV:', 'SAFESUBST:' ),
	'msgnw'                     => array( '0', 'BERICHTNW', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'miniatuur', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'miniatuur=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'rechts', 'right' ),
	'img_left'                  => array( '1', 'links', 'left' ),
	'img_none'                  => array( '1', 'geen', 'none' ),
	'img_center'                => array( '1', 'gecentreerd', 'center', 'centre' ),
	'img_framed'                => array( '1', 'omkaderd', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'kaderloos', 'frameless' ),
	'img_lang'                  => array( '1', 'taal=$1', 'lang=$1' ),
	'img_page'                  => array( '1', 'pagina=$1', 'pagina_$1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'rechtop', 'rechtop=$1', 'rechtop$1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'rand', 'border' ),
	'img_baseline'              => array( '1', 'grondlijn', 'baseline' ),
	'img_top'                   => array( '1', 'boven', 'top' ),
	'img_text_top'              => array( '1', 'tekst-boven', 'text-top' ),
	'img_middle'                => array( '1', 'midden', 'middle' ),
	'img_bottom'                => array( '1', 'beneden', 'bottom' ),
	'img_text_bottom'           => array( '1', 'tekst-beneden', 'text-bottom' ),
	'img_link'                  => array( '1', 'koppeling=$1', 'verwijzing=$1', 'link=$1' ),
	'img_class'                 => array( '1', 'klasse=$1', 'class=$1' ),
	'sitename'                  => array( '1', 'SITENAAM', 'SITENAME' ),
	'ns'                        => array( '0', 'NR:', 'NS:' ),
	'nse'                       => array( '0', 'NRE:', 'NSE:' ),
	'localurl'                  => array( '0', 'LOKALEURL', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'LOKALEURLE', 'LOCALURLE:' ),
	'articlepath'               => array( '0', 'ARTIKELPAD', 'ARTICLEPATH' ),
	'pageid'                    => array( '0', 'PAGINAID', 'PAGEID' ),
	'servername'                => array( '0', 'SERVERNAAM', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'SCRIPTPAD', 'SCRIPTPATH' ),
	'stylepath'                 => array( '0', 'STIJLPAD', 'STYLEPATH' ),
	'grammar'                   => array( '0', 'GRAMMATICA:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'GESLACHT:', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__GEENPAGINANAAMCONVERSIE__', '__GEENTITELCONVERSIE__', '__GEENTC__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__GEENINHOUDCONVERSIE__', '__GEENIC__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'HUIDIGEWEEK', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'HUIDIGEDVDW', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'PLAATSELIJKEWEEK', 'LOKALEWEEK', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'PLAATSELIJKEDVDW', 'LOKALEDVDW', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'VERSIEID', 'REVISIONID' ),
	'revisionday'               => array( '1', 'VERSIEDAG', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'VERSIEDAG2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'VERSIEMAAND', 'REVISIONMONTH' ),
	'revisionmonth1'            => array( '1', 'VERSIEMAAND1', 'REVISIONMONTH1' ),
	'revisionyear'              => array( '1', 'VERSIEJAAR', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'VERSIETIJD', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', 'VERSIEGEBRUIKER', 'REVISIONUSER' ),
	'revisionsize'              => array( '1', 'VERSIEGROOTTE', 'REVISIONSIZE' ),
	'plural'                    => array( '0', 'MEERVOUD:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'VOLLEDIGEURL:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'VOLLEDIGEURLE:', 'FULLURLE:' ),
	'canonicalurl'              => array( '0', 'CANOIEKEURL:', 'CANONICALURL:' ),
	'canonicalurle'             => array( '0', 'CANONIEKEURLE:', 'CANONICALURLE:' ),
	'lcfirst'                   => array( '0', 'KLEERSTE:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'GLEERSTE:', 'HLEERSTE:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'KL:', 'LC:' ),
	'uc'                        => array( '0', 'HL:', 'UC:' ),
	'raw'                       => array( '0', 'RAUW:', 'RUW:', 'RAW:' ),
	'displaytitle'              => array( '1', 'WEERGEGEVENTITEL', 'TOONTITEL', 'DISPLAYTITLE' ),
	'rawsuffix'                 => array( '1', 'V', 'R' ),
	'nocommafysuffix'           => array( '0', 'GEENSCHEIDINGSTEKEN', 'NOSEP' ),
	'newsectionlink'            => array( '1', '__NIEUWESECTIELINK__', '__NIEUWESECTIEKOPPELING__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__GEENNIEUWKOPJEKOPPELING__', '__GEENNIEUWESECTIELINK__', '__GEENNIEUWKOPJEVERWIJZING__', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'HUIDIGEVERSIE', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'URLCODEREN', 'CODEERURL', 'URLENCODE:' ),
	'anchorencode'              => array( '0', 'ANKERCODEREN', 'CODEERANKER', 'ANCHORENCODE' ),
	'currenttimestamp'          => array( '1', 'HUIDIGETIJDSTEMPEL', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'PLAATSELIJKETIJDSTEMPEL', 'LOKALETIJDSTEMPEL', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'RICHTINGMARKERING', 'RICHTINGSMARKERING', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#TAAL:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'INHOUDSTAAL', 'INHOUDTAAL', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'PAGINASINNAAMRUIMTE', 'PAGINA’SINNAAMRUIMTE', 'PAGINA\'SINNAAMRUIMTE', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'AANTALBEHEERDERS', 'AANTALADMINS', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'FORMATTEERNUM', 'NUMFORMATTEREN', 'FORMATNUM' ),
	'padleft'                   => array( '0', 'LINKSOPVULLEN', 'PADLEFT' ),
	'padright'                  => array( '0', 'RECHTSOPVULLEN', 'PADRIGHT' ),
	'special'                   => array( '0', 'speciaal', 'special' ),
	'speciale'                  => array( '0', 'speciaale', 'speciale' ),
	'defaultsort'               => array( '1', 'STANDAARDSORTERING:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'BESTANDSPAD:', 'FILEPATH:' ),
	'tag'                       => array( '0', 'label', 'tag' ),
	'hiddencat'                 => array( '1', '__VERBORGENCAT__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'PAGINASINCATEGORIE', 'PAGINASINCAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'PAGINAGROOTTE', 'PAGESIZE' ),
	'noindex'                   => array( '1', '__GEENINDEX__', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'AANTALINGROEP', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__STATISCHEDOORVERWIJZING__', '__STATISCHEREDIRECT__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'BEVEILIGINGSNIVEAU', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'datumopmaak', 'formatdate', 'dateformat' ),
	'url_path'                  => array( '0', 'PAD', 'PATH' ),
	'url_query'                 => array( '0', 'ZOEKOPDRACHT', 'QUERY' ),
	'defaultsort_noerror'       => array( '0', 'geenfout', 'noerror' ),
	'defaultsort_noreplace'     => array( '0', 'nietvervangen', 'noreplace' ),
	'pagesincategory_all'       => array( '0', 'alle', 'all' ),
	'pagesincategory_pages'     => array( '0', 'paginas', 'pages' ),
	'pagesincategory_subcats'   => array( '0', 'ondercategorieen', 'subcats' ),
	'pagesincategory_files'     => array( '0', 'bestanden', 'files' ),
);

$specialPageAliases = array(
	'Activeusers'               => array( 'ActieveGebruikers' ),
	'Allmessages'               => array( 'AlleBerichten', 'Systeemberichten' ),
	'AllMyUploads'              => array( 'AlMijnUploads' ),
	'Allpages'                  => array( 'AllePaginas', 'AllePagina’s', 'AllePagina\'s' ),
	'ApiHelp'                   => array( 'ApiHulp' ),
	'Ancientpages'              => array( 'OudstePaginas', 'OudstePagina’s', 'OudstePagina\'s' ),
	'Badtitle'                  => array( 'OnjuistePaginanaam' ),
	'Blankpage'                 => array( 'LegePagina' ),
	'Block'                     => array( 'Blokkeren', 'IPblokkeren', 'BlokkeerIP', 'BlokkeerIp' ),
	'Booksources'               => array( 'Boekbronnen', 'Boekinformatie' ),
	'BrokenRedirects'           => array( 'GebrokenDoorverwijzingen' ),
	'Categories'                => array( 'Categorieën' ),
	'ChangeEmail'               => array( 'E-mailWijzigen' ),
	'ChangePassword'            => array( 'WachtwoordWijzigen', 'WachtwoordHerinitialiseren' ),
	'ComparePages'              => array( 'PaginasVergelijken', 'Pagina\'sVergelijken' ),
	'Confirmemail'              => array( 'Emailbevestigen', 'E-mailbevestigen' ),
	'Contributions'             => array( 'Bijdragen' ),
	'CreateAccount'             => array( 'GebruikerAanmaken' ),
	'Deadendpages'              => array( 'VerwijslozePaginas', 'VerwijslozePagina’s', 'VerwijslozePagina\'s' ),
	'DeletedContributions'      => array( 'VerwijderdeBijdragen' ),
	'DoubleRedirects'           => array( 'DubbeleDoorverwijzingen' ),
	'EditWatchlist'             => array( 'VolglijstBewerken' ),
	'Emailuser'                 => array( 'GebruikerE-mailen', 'E-mailGebruiker' ),
	'ExpandTemplates'           => array( 'SjablonenSubstitueren' ),
	'Export'                    => array( 'Exporteren' ),
	'Fewestrevisions'           => array( 'MinsteVersies', 'MinsteHerzieningen', 'MinsteRevisies' ),
	'FileDuplicateSearch'       => array( 'BestandsduplicatenZoeken' ),
	'Filepath'                  => array( 'Bestandspad' ),
	'Import'                    => array( 'Importeren' ),
	'Invalidateemail'           => array( 'EmailAnnuleren' ),
	'BlockList'                 => array( 'Blokkeerlijst', 'IP-blokkeerlijst', 'IPblokkeerlijst', 'IpBlokkeerlijst' ),
	'LinkSearch'                => array( 'VerwijzingenZoeken', 'LinksZoeken' ),
	'Listadmins'                => array( 'Beheerderlijst', 'Administratorlijst', 'Adminlijst', 'Beheerderslijst' ),
	'Listbots'                  => array( 'Botlijst', 'Lijstbots' ),
	'Listfiles'                 => array( 'Bestandenlijst', 'Afbeeldingenlijst' ),
	'Listgrouprights'           => array( 'GroepsrechtenWeergeven' ),
	'Listredirects'             => array( 'Doorverwijzinglijst', 'Redirectlijst' ),
	'ListDuplicatedFiles'       => array( 'DuplicaatbestandenWeergeven' ),
	'Listusers'                 => array( 'Gebruikerslijst', 'Gebruikerlijst' ),
	'Lockdb'                    => array( 'DBblokkeren', 'DbBlokkeren', 'BlokkeerDB' ),
	'Log'                       => array( 'Logboeken', 'Logboek' ),
	'Lonelypages'               => array( 'Weespaginas', 'Weespagina\'s' ),
	'Longpages'                 => array( 'LangePaginas', 'LangePagina’s', 'LangePagina\'s' ),
	'MediaStatistics'           => array( 'Mediastatistieken' ),
	'MergeHistory'              => array( 'GeschiedenisSamenvoegen' ),
	'MIMEsearch'                => array( 'MIMEzoeken', 'MIME-zoeken' ),
	'Mostcategories'            => array( 'MeesteCategorieën' ),
	'Mostimages'                => array( 'MeesteVerwezenBestanden', 'MeesteBestanden', 'MeesteAfbeeldingen' ),
	'Mostinterwikis'            => array( 'MeesteInterwikiverwijzingen' ),
	'Mostlinked'                => array( 'MeestVerwezenPaginas', 'MeestVerwezenPagina\'s', 'MeestVerwezen' ),
	'Mostlinkedcategories'      => array( 'MeestVerwezenCategorieën' ),
	'Mostlinkedtemplates'       => array( 'MeestVerwezenSjablonen' ),
	'Mostrevisions'             => array( 'MeesteVersies', 'MeesteHerzieningen', 'MeesteRevisies' ),
	'Movepage'                  => array( 'PaginaHernoemen', 'PaginaVerplaatsen', 'TitelWijzigen', 'VerplaatsPagina' ),
	'Mycontributions'           => array( 'MijnBijdragen' ),
	'MyLanguage'                => array( 'MijnTaal' ),
	'Mypage'                    => array( 'MijnPagina' ),
	'Mytalk'                    => array( 'MijnOverleg' ),
	'Myuploads'                 => array( 'MijnUploads' ),
	'Newimages'                 => array( 'NieuweBestanden', 'NieuweAfbeeldingen' ),
	'Newpages'                  => array( 'NieuwePaginas', 'NieuwePagina’s', 'NieuwePagina\'s' ),
	'PagesWithProp'             => array( 'PaginasMetEigenschap', 'Pagina\'sMetEigenschap' ),
	'PageLanguage'              => array( 'Paginataal' ),
	'PasswordReset'             => array( 'WachtwoordOpnieuwInstellen' ),
	'PermanentLink'             => array( 'PermanenteVerwijzing' ),

	'Preferences'               => array( 'Voorkeuren' ),
	'Prefixindex'               => array( 'Voorvoegselindex' ),
	'Protectedpages'            => array( 'BeveiligdePaginas', 'BeveiligdePagina\'s', 'BeschermdePaginas', 'BeschermdePagina’s', 'BeschermdePagina\'s' ),
	'Protectedtitles'           => array( 'BeveiligdeTitels', 'BeschermdeTitels' ),
	'Randompage'                => array( 'Willekeurig', 'WillekeurigePagina' ),
	'RandomInCategory'          => array( 'WillekeurigeUitCategorie' ),
	'Randomredirect'            => array( 'WillekeurigeDoorverwijzing' ),
	'Recentchanges'             => array( 'RecenteWijzigingen' ),
	'Recentchangeslinked'       => array( 'RecenteWijzigingenGelinkt', 'VerwanteWijzigingen' ),
	'Redirect'                  => array( 'Doorverwijzen' ),
	'ResetTokens'               => array( 'TokensOpnieuwInstellen' ),
	'Revisiondelete'            => array( 'VersieVerwijderen', 'HerzieningVerwijderen', 'RevisieVerwijderen' ),
	'RunJobs'                   => array( 'TakenUitvoeren' ),
	'Search'                    => array( 'Zoeken' ),
	'Shortpages'                => array( 'KortePaginas', 'KortePagina’s', 'KortePagina\'s' ),
	'Specialpages'              => array( 'SpecialePaginas', 'SpecialePagina’s', 'SpecialePagina\'s' ),
	'Statistics'                => array( 'Statistieken' ),
	'Tags'                      => array( 'Labels' ),
	'TrackingCategories'        => array( 'Trackingcategorieen' ),
	'Unblock'                   => array( 'Deblokkeren' ),
	'Uncategorizedcategories'   => array( 'NietGecategoriseerdeCategorieën', 'Niet-GecategoriseerdeCategorieën' ),
	'Uncategorizedimages'       => array( 'NietGecategoriseerdeBestanden', 'NietGecategoriseerdeAfbeeldingen', 'Niet-GecategoriseerdeAfbeeldingen' ),
	'Uncategorizedpages'        => array( 'NietGecategoriseerdePaginas', 'Niet-GecategoriseerdePagina’s', 'Niet-GecategoriseerdePagina\'s' ),
	'Uncategorizedtemplates'    => array( 'NietGecategoriseerdeSjablonen' ),
	'Undelete'                  => array( 'Terugplaatsen', 'Herstellen', 'VerwijderenOngedaanMaken' ),
	'Unlockdb'                  => array( 'DBvrijgeven', 'DbVrijgeven', 'GeefDbVrij' ),
	'Unusedcategories'          => array( 'OngebruikteCategorieën' ),
	'Unusedimages'              => array( 'OngebruikteBestanden', 'OngebruikteAfbeeldingen' ),
	'Unusedtemplates'           => array( 'OngebruikteSjablonen' ),
	'Unwatchedpages'            => array( 'NietGevolgdePaginas', 'Niet-GevolgdePagina’s', 'Niet-GevolgdePagina\'s' ),
	'Upload'                    => array( 'Uploaden' ),
	'UploadStash'               => array( 'TijdelijkeUpload' ),
	'Userlogin'                 => array( 'Aanmelden', 'Inloggen' ),
	'Userlogout'                => array( 'Afmelden', 'Uitloggen' ),
	'Userrights'                => array( 'Gebruikersrechten', 'Gebruikerrechten' ),
	'Version'                   => array( 'Softwareversie', 'Versie' ),
	'Wantedcategories'          => array( 'GevraagdeCategorieën' ),
	'Wantedfiles'               => array( 'GevraagdeBestanden' ),
	'Wantedpages'               => array( 'GevraagdePaginas', 'GevraagdePagina\'s', 'GevraagdePagina’s' ),
	'Wantedtemplates'           => array( 'GevraagdeSjablonen' ),
	'Watchlist'                 => array( 'Volglijst' ),
	'Whatlinkshere'             => array( 'VerwijzingenNaarHier', 'Verwijzingen', 'LinksNaarHier' ),
	'Withoutinterwiki'          => array( 'ZonderInterwiki' ),
);

$linkTrail = '/^([a-zäöüïëéèà]+)(.*)$/sDu';

