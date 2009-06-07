<?php
/** Nedersaksisch (Nedersaksisch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Erwin85
 * @author Jens Frank
 * @author Servien
 * @author Slomox
 * @author Urhixidur
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author לערי ריינהארט
 */

$fallback = 'nl';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciaal',
	NS_TALK             => 'Overleg',
	NS_USER             => 'Gebruker',
	NS_USER_TALK        => 'Overleg_gebruker',
	NS_PROJECT_TALK     => 'Overleg_$1',
	NS_FILE             => 'Ofbeelding',
	NS_FILE_TALK        => 'Overleg_ofbeelding',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Overleg_MediaWiki',
	NS_TEMPLATE         => 'Mal',
	NS_TEMPLATE_TALK    => 'Overleg_mal',
	NS_HELP             => 'Hulpe',
	NS_HELP_TALK        => 'Overleg_hulpe',
	NS_CATEGORY         => 'Kattegerie',
	NS_CATEGORY_TALK    => 'Overleg_kattegerie',
);

$namespaceAliases = array(
	'Speciaol'          => NS_SPECIAL,
	'Sjabloon'          => NS_TEMPLATE,
	'Overleg_sjabloon'  => NS_TEMPLATE_TALK,
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

$bookstoreList = array(
        'Koninklijke Bibliotheek' => 'http://opc4.kb.nl/DB=1/SET=5/TTL=1/CMD?ACT=SRCH&IKT=1007&SRT=RLV&TRM=$1'
);

$magicWords = array(
	'redirect'              => array( '0', '#DEURVERWIEZING', '#DOORVERWIJZING', '#REDIRECT' ),
	'notoc'                 => array( '0', '__GIENONDERWARPEN__', '__GEENINHOUD__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__GIENGALLERIEJE__', '__GEEN_GALERIJ__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__FORCEERONDERWARPEN__', '__INHOUD_DWINGEN__', '__FORCEERINHOUD__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__ONDERWARPEN__', '__INHOUD__', '__TOC__' ),
	'noeditsection'         => array( '0', '__GIENBEWARKSECTIE__', '__NIETBEWERKBARESECTIE__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'DISSEMAOND', 'HUIDIGEMAAND', 'CURRENTMONTH' ),
	'currentmonthname'      => array( '1', 'DISSEMAONDNAAM', 'HUIDIGEMAANDNAAM', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'DISSEMAONDGEN', 'HUIDIGEMAANDGEN', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'DISSEMAONDOFK', 'HUIDIGEMAANDAFK', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'DISSEDAG', 'HUIDIGEDAG', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'DISSEDAG2', 'HUIDIGEDAG2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'DISSEDAGNAAM', 'HUIDIGEDAGNAAM', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'DITJAOR', 'HUIDIGJAAR', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'DISSETIED', 'HUIDIGETIJD', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'DITURE', 'HUIDIGUUR', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'LOKALEMAOND', 'PLAATSELIJKEMAAND', 'LOKALEMAAND', 'LOCALMONTH' ),
	'localmonthname'        => array( '1', 'LOKALEMAONDNAAM', 'PLAATSELIJKEMAANDNAAM', 'LOKALEMAANDNAAM', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'LOKALEMAONDNAAMGEN', 'PLAATSELIJKEMAANDNAAMGEN', 'LOKALEMAANDNAAMGEN', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'LOKALEMAONDOFK', 'PLAATSELIJKEMAANDAFK', 'LOKALEMAANDAFK', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'LOKALEDAG', 'PLAATSELIJKEDAG', 'LOKALEDAG', 'LOCALDAY' ),
	'localday2'             => array( '1', 'LOKALEDAG2', 'PLAATSELIJKEDAG2', 'LOKALEDAG2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'LOKALEDAGNAAM', 'PLAATSELIJKEDAGNAAM', 'LOKALEDAGNAAM', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'LOKAALJAOR', 'PLAATSELIJKJAAR', 'LOKAALJAAR', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'LOKALETIED', 'PLAATSELIJKETIJD', 'LOKALETIJD', 'LOCALTIME' ),
	'localhour'             => array( '1', 'LOKAALURE', 'PLAATSELIJKUUR', 'LOKAALUUR', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'ANTALPAGINAS', 'ANTALPAGINA\'S', 'ANTALPAGINA’S', 'AANTALPAGINAS', 'AANTALPAGINA\'S', 'AANTALPAGINA’S', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'ANTALARTIKELS', 'AANTALARTIKELEN', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'ANTALBESTANDEN', 'AANTALBESTANDEN', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'ANTALGEBRUKERS', 'AANTALGEBRUIKERS', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'ANTALBEWARKINGEN', 'AANTALBEWERKINGEN', 'NUMBEROFEDITS' ),
	'pagename'              => array( '1', 'PAGINANAAM', 'PAGINANAAM', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'PAGINANAAME', 'PAGINANAAME', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'NAAMRUUMTE', 'NAAMRUIMTE', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'NAAMRUUMTEE', 'NAAMRUIMTEE', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'OVERLEGRUUMTE', 'OVERLEGRUIMTE', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'OVERLEGRUUMTEE', 'OVERLEGRUIMTEE', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'ONDERWARPRUUMTE', 'ARTIKELRUUMTE', 'ONDERWERPRUIMTE', 'ARTIKELRUIMTE', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'ONDERWARPRUUMTEE', 'ARTIKELRUUMTEE', 'ONDERWERPRUIMTEE', 'ARTIKELRUIMTEE', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'HELEPAGINANAAM', 'VOLLEDIGEPAGINANAAM', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'HELEPAGINANAAME', 'VOLLEDIGEPAGINANAAME', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'DEELPAGINANAAM', 'DEELPAGINANAAM', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'DEELPAGINANAAME', 'DEELPAGINANAAME', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'BAOSISPAGINANAAM', 'BASISPAGINANAAM', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'BAOSISPAGINANAAME', 'BASISPAGINANAAME', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'OVERLEGPAGINANAAM', 'OVERLEGPAGINANAAM', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'OVERLEGPAGINANAAME', 'OVERLEGPAGINANAAME', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'ONDERWARPPAGINANAAM', 'ARTIKELPAGINANAAM', 'ONDERWERPPAGINANAAM', 'ARTIKELPAGINANAAM', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'ONDERWARPPAGINANAAME', 'ARTIKELPAGINANAAME', 'ONDERWERPPAGINANAAME', 'ARTIKELPAGINANAAME', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                   => array( '0', 'BERICH:', 'BERICHT:', 'MSG:' ),
	'subst'                 => array( '0', 'VERVANG:', 'VERV:', 'SUBST:' ),
	'msgnw'                 => array( '0', 'BERICHNW', 'BERICHTNW', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'duumnegel', 'doemnaegel', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'duumnegel=$1', 'doemnegel=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'rechs', 'rechts', 'right' ),
	'img_left'              => array( '1', 'links', 'links', 'left' ),
	'img_none'              => array( '1', 'gien', 'geen', 'none' ),
	'img_center'            => array( '1', 'ecentreerd', 'gecentreerd', 'center', 'centre' ),
	'img_framed'            => array( '1', 'umraand', 'omkaderd', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'kaoderloos', 'kaderloos', 'frameless' ),
	'img_page'              => array( '1', 'pagina=$1', 'pagina $1', 'pagina=$1', 'pagina $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'rechop', 'rechop=$1', 'rechop $1', 'rechtop', 'rechtop=$1', 'rechtop$1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'raand', 'rand', 'border' ),
	'img_baseline'          => array( '1', 'grondliende', 'grondlijn', 'baseline' ),
	'img_top'               => array( '1', 'boven', 'boven', 'top' ),
	'img_text_top'          => array( '1', 'tekse-boven', 'tekst-boven', 'text-top' ),
	'img_middle'            => array( '1', 'midden', 'midden', 'middle' ),
	'img_bottom'            => array( '1', 'benejen', 'beneden', 'bottom' ),
	'img_text_bottom'       => array( '1', 'tekse-benejen', 'tekst-beneden', 'text-bottom' ),
	'sitename'              => array( '1', 'WEBSTEENAAM', 'SITENAAM', 'SITENAME' ),
	'ns'                    => array( '0', 'NR:', 'NR:', 'NS:' ),
	'localurl'              => array( '0', 'LOKALEURL', 'LOKALEURL', 'LOCALURL:' ),
	'localurle'             => array( '0', 'LOKALEURLE', 'LOKALEURLE', 'LOCALURLE:' ),
	'servername'            => array( '0', 'SERVERNAAM', 'SERVERNAAM', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'SCRIPTPAD', 'SCRIPTPAD', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'GRAMMATICA:', 'GRAMMATICA:', 'GRAMMAR:' ),
	'notitleconvert'        => array( '0', '__GIENTITELCONVERSIE__', '__GIENTC__', '__GEENTITELCONVERSIE__', '__GEENTC__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__GIENINHOUDCONVERSIE__', '__GIENIC__', '__GEENINHOUDCONVERSIE__', '__GEENIC__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'DISSEWEKE', 'HUIDIGEWEEK', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'DISSEDVDW', 'HUIDIGEDVDW', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'LOKALEWEKE', 'PLAATSELIJKEWEEK', 'LOKALEWEEK', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'LOKALEDVDW', 'PLAATSELIJKEDVDW', 'LOKALEDVDW', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'REVISIEID', 'REVISIE-ID', 'VERSIEID', 'REVISIONID' ),
	'revisionday'           => array( '1', 'REVISIEDAG', 'VERSIEDAG', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'REVISIEDAG2', 'VERSIEDAG2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'REVISIEMAOND', 'VERSIEMAAND', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'REVISIEJAOR', 'VERSIEJAAR', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'REVISIETIEDSTEMPEL', 'VERSIETIJD', 'REVISIONTIMESTAMP' ),
	'plural'                => array( '0', 'MEERVOUD:', 'MEERVOUD:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'HELEURL', 'VOLLEDIGEURL', 'FULLURL:' ),
	'fullurle'              => array( '0', 'HELEURLE', 'VOLLEDIGEURLE', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'HLEERSTE:', 'KLEERSTE:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'KLEERSTE:', 'GLEERSTE:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'KL:', 'KL:', 'LC:' ),
	'uc'                    => array( '0', 'HL:', 'HL:', 'UC:' ),
	'raw'                   => array( '0', 'RAUW:', 'RAUW:', 'RUW:', 'RAW:' ),
	'displaytitle'          => array( '1', 'TEUNTITEL', 'TOONTITEL', 'TITELTONEN', 'DISPLAYTITLE' ),
	'newsectionlink'        => array( '1', '__NIEJESECTIEVERWIEZING__', '__NIEUWESECTIELINK__', '__NIEUWESECTIEKOPPELING__', '__NEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'DISSEVERSIE', 'HUIDIGEVERSIE', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'CODEERURL', 'URLCODEREN', 'CODEERURL', 'URLENCODE:' ),
	'anchorencode'          => array( '0', 'CODEERANKER', 'ANKERCODEREN', 'CODEERANKER', 'ANCHORENCODE' ),
	'currenttimestamp'      => array( '1', 'DISSETIEDSTEMPEL', 'HUIDIGETIJDSTEMPEL', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'LOKALETIEDSTEMPEL', 'PLAATSELIJKETIJDSTEMPEL', 'LOKALETIJDSTEMPEL', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'RICHTINGMARKERING', 'RICHTINGSMARKERING', 'RICHTINGMARKERING', 'RICHTINGSMARKERING', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#TAAL:', '#TAAL:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'INHOUDSTAAL', 'INHOUDSTAAL', 'INHOUDTAAL', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'PAGINASINNAAMRUUMTE', 'PAGINA’SINNAAMRUUMTE', 'PAGINA\'SINNAAMRUUMTE', 'PAGINASINNAAMRUIMTE', 'PAGINA’SINNAAMRUIMTE', 'PAGINA\'SINNAAMRUIMTE', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'ANTALBEHEERDERS', 'AANTALBEHEERDERS', 'AANTALADMINS', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'FORMATTEERNUM', 'FORMATTEERNUM', 'NUMFORMATTEREN', 'FORMATNUM' ),
	'padleft'               => array( '0', 'LINKSOPVULLEN', 'LINKSOPVULLEN', 'PADLEFT' ),
	'padright'              => array( '0', 'RECHSOPVULLEN', 'RECHTSOPVULLEN', 'PADRIGHT' ),
	'special'               => array( '0', 'speciaal', 'speciaal', 'special' ),
	'defaultsort'           => array( '1', 'STANDARDSORTERING:', 'STANDAARDSORTERING:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'BESTANSPAD:', 'BESTANDSPAD:', 'FILEPATH:' ),
	'hiddencat'             => array( '1', '__VERBÖRGENKAT__', '__VERBORGENCAT__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'PAGINASINKATTEGERIE', 'PAGINASINKAT', 'PAGINASINCATEGORIE', 'PAGINASINCAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'PAGINAGROOTTE', 'PAGINAGROOTTE', 'PAGESIZE' ),
	'noindex'               => array( '1', '__GIENINDEX__', '__GEENINDEX__', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'ANTALINGROEP', 'AANTALINGROEP', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__STAOTISCHEDEURVERWIEZING__', '__STATISCHEDOORVERWIJZING__', '__STATISCHEREDIRECT__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'BEVEILIGINGSNIVO', 'BEVEILIGINGSNIVEAU', 'PROTECTIONLEVEL' ),
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Dubbele_deurverwiezingen' ),
	'BrokenRedirects'           => array( 'Ebreuken_deurverwiezingen' ),
	'Disambiguations'           => array( 'Deurverwiespagina\'s' ),
	'Userlogin'                 => array( 'Anmelden' ),
	'Userlogout'                => array( 'Ofmelden' ),
	'CreateAccount'             => array( 'Gebruker_anmaken' ),
	'Preferences'               => array( 'Veurkeuren' ),
	'Watchlist'                 => array( 'Volglieste' ),
	'Recentchanges'             => array( 'Leste_wiezigingen' ),
	'Upload'                    => array( 'Bestanden_toevoegen' ),
	'Listfiles'                 => array( 'Ofbeeldingenlieste' ),
	'Newimages'                 => array( 'Nieje_ofbeeldingen' ),
	'Listusers'                 => array( 'Gebrukerslieste' ),
	'Listgrouprights'           => array( 'Groepsrechen bekieken' ),
	'Statistics'                => array( 'Staotestieken' ),
	'Randompage'                => array( 'Willekeurig_artikel' ),
	'Lonelypages'               => array( 'Weespagina\'s' ),
	'Uncategorizedpages'        => array( 'Pagina\'s_zonder_kattegerie' ),
	'Uncategorizedcategories'   => array( 'Kattergieën_zonder_kattegerie' ),
	'Uncategorizedimages'       => array( 'Ofbeeldingen_zonder_kattegerie' ),
	'Uncategorizedtemplates'    => array( 'Sjablonen_zonder_kattegerie' ),
	'Unusedcategories'          => array( 'Ongebruken_kattegerieën' ),
	'Unusedimages'              => array( 'Ongebruken_ofbeeldingen' ),
	'Wantedpages'               => array( 'Gewunste_pagina\'s' ),
	'Wantedcategories'          => array( 'Gewunste_kattegerieën' ),
	'Wantedfiles'               => array( 'Gewunste_bestanden' ),
	'Wantedtemplates'           => array( 'Gewunste_sjablonen' ),
	'Mostlinked'                => array( 'Meest_naor_verwezen_pagina\'s' ),
	'Mostlinkedcategories'      => array( 'Meestgebruken_kattegerieën' ),
	'Mostlinkedtemplates'       => array( 'Meest_naor_verwezen_sjablonen' ),
	'Mostimages'                => array( 'Meeste_ofbeeldingen' ),
	'Mostcategories'            => array( 'Meeste_kattegerieën' ),
	'Mostrevisions'             => array( 'Meeste_bewarkingen' ),
	'Fewestrevisions'           => array( 'Minste_bewarkingen' ),
	'Shortpages'                => array( 'Korte_artikels' ),
	'Longpages'                 => array( 'Lange_artikels' ),
	'Newpages'                  => array( 'Nieje_pagina\'s' ),
	'Ancientpages'              => array( 'Oudste_pagina\'s' ),
	'Deadendpages'              => array( 'Gien_verwiezingen' ),
	'Protectedpages'            => array( 'Beveiligen_pagina\'s' ),
	'Protectedtitles'           => array( 'Beveiligen_titels' ),
	'Allpages'                  => array( 'Alle_pagina\'s' ),
	'Prefixindex'               => array( 'Veurvoegselindex' ),
	'Ipblocklist'               => array( 'IP-blokkeerlieste' ),
	'Specialpages'              => array( 'Speciale_pagina\'s' ),
	'Contributions'             => array( 'Biedragen' ),
	'Emailuser'                 => array( 'Berich_sturen' ),
	'Confirmemail'              => array( 'E-mailbevestigen' ),
	'Whatlinkshere'             => array( 'Verwiezingen_naor_disse_pagina' ),
	'Recentchangeslinked'       => array( 'Volg_verwiezingen' ),
	'Movepage'                  => array( 'Herneum_pagina' ),
	'Blockme'                   => array( 'Blokkeer_mien' ),
	'Booksources'               => array( 'Boekinfermasie' ),
	'Categories'                => array( 'Kattegerieën' ),
	'Export'                    => array( 'Uutvoeren' ),
	'Version'                   => array( 'Versie' ),
	'Allmessages'               => array( 'Alle_systeemteksen' ),
	'Log'                       => array( 'Logboeken' ),
	'Blockip'                   => array( 'Blokkeer_IP' ),
	'Undelete'                  => array( 'Weerummeplaosen' ),
	'Import'                    => array( 'Invoeren' ),
	'Lockdb'                    => array( 'Databanke_blokkeren' ),
	'Unlockdb'                  => array( 'Databanke_vriegeven' ),
	'Userrights'                => array( 'Gebrukersrechen' ),
	'MIMEsearch'                => array( 'MIME-zeuken' ),
	'FileDuplicateSearch'       => array( 'Dubbele_bestanden_zeuken' ),
	'Unwatchedpages'            => array( 'Neet-evolgen_pagina\'s' ),
	'Listredirects'             => array( 'Deurverwiezingslieste' ),
	'Revisiondelete'            => array( 'Versie_vortdoon' ),
	'Unusedtemplates'           => array( 'Ongebruken_sjablonen' ),
	'Randomredirect'            => array( 'Willekeurige_deurverwiezing' ),
	'Mypage'                    => array( 'Mien_gebrukerspagina' ),
	'Mytalk'                    => array( 'Mien_overleg' ),
	'Mycontributions'           => array( 'Mien_biedragen' ),
	'Listadmins'                => array( 'Beheerderslieste' ),
	'Listbots'                  => array( 'Botlieste' ),
	'Popularpages'              => array( 'Populaire_artikels' ),
	'Search'                    => array( 'Zeuken' ),
	'Resetpass'                 => array( 'Wachwoord_wiezigen' ),
	'Withoutinterwiki'          => array( 'Gien_interwiki' ),
	'MergeHistory'              => array( 'Geschiedenisse_bie_mekaar_doon' ),
	'Filepath'                  => array( 'Bestanslokasie' ),
	'Invalidateemail'           => array( 'E-mail_annuleren' ),
	'Blankpage'                 => array( 'Lege_pagina' ),
	'LinkSearch'                => array( 'Verwiezingen_zeuken' ),
	'DeletedContributions'      => array( 'Vort-ehaolen gebrukersbiedragen' ),
);

$linkTrail = '/^([a-zäöüïëéèà]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Verwiezingen onderstrepen',
'tog-highlightbroken'         => "Verwiezingen naor lege pagina's op laoten lochen",
'tog-justify'                 => "Alinea's uutvullen",
'tog-hideminor'               => 'Kleine wiezigingen verbargen in leste wiezigingen',
'tog-hidepatrolled'           => 'Wiezigingen dee emarkeerd bin verbargen in leste wiezigingen',
'tog-newpageshidepatrolled'   => "Pagina's dee emarkeerd bin verbargen in de lieste mit nieje artikels",
'tog-extendwatchlist'         => 'Volglieste uutbreien zodat alle wiezigingen zichbaor bin, en neet allinnig de leste wieziging',
'tog-usenewrc'                => 'Gebruuk de uut-ebreien lestewiezigingenpagina (hierveur he-j JavaScript neudig)',
'tog-numberheadings'          => 'Koppen vanzelf nummeren',
'tog-showtoolbar'             => 'Warkbalke weergeven',
'tog-editondblclick'          => 'Mit dubbelklik bewarken (JavaScript)',
'tog-editsection'             => 'Mit bewarkgedeeltes',
'tog-editsectiononrightclick' => 'Bewarkgedeelte mit rechtermuusknoppe bewarken (JavaScript)',
'tog-showtoc'                 => 'Samenvatting van de onderwarpen laoten zien (mit meer as dree onderwarpen)',
'tog-rememberpassword'        => 'Vanzelf anmelden',
'tog-editwidth'               => 'Bewarkingsveld over volle breedte',
'tog-watchcreations'          => 'Artikels dee-j anmaken an volglieste toevoegen',
'tog-watchdefault'            => 'Artikels dee-j wiezigen an volglieste toevoegen',
'tog-watchmoves'              => "Pagina's dee-k herneume op mien volglieste zetten",
'tog-watchdeletion'           => 'Voeg pagina dee-k vortdo an mien volglieste toe',
'tog-minordefault'            => "Markeer alle veraanderingen as 'kleine wieziging'",
'tog-previewontop'            => 'Naokiekpagina boven bewarkingsveld weergeven',
'tog-previewonfirst'          => 'Naokieken bie eerste wieziging',
'tog-nocache'                 => 'De kas uutschakelen',
'tog-enotifwatchlistpages'    => 'Stuur mien een berichjen over paginawiezigingen.',
'tog-enotifusertalkpages'     => 'Stuur mien een berichjen as mien overlegpagina ewiezig is.',
'tog-enotifminoredits'        => 'Stuur mien oek een berichjen bie kleine bewarkingen',
'tog-enotifrevealaddr'        => 'Mien netposadres weergeven in netpostiejigen',
'tog-shownumberswatching'     => 'Antal volgende gebrukers weergeven',
'tog-fancysig'                => 'Ondertekening zien as wikitekse (zonder autematische verwiezing)',
'tog-externaleditor'          => 'Gebruuk standard een externe teksbewarker',
'tog-externaldiff'            => 'Gebruuk standard een extern vergeliekingspregramma',
'tog-showjumplinks'           => 'Verwiezingen naor "navigasie" en "zeuken" weergeven bovenan pagina\'s in partie uterlijken (zoas Myskin)',
'tog-uselivepreview'          => 'Gebruuk "rechstreekse veurbeschouwing" (mu-j JavaScript veur hemmen - experimenteel)',
'tog-forceeditsummary'        => 'Geef een melding bie een lege samenvatting',
'tog-watchlisthideown'        => 'Verbarg mien eigen bewarkingen',
'tog-watchlisthidebots'       => 'Verbarg botgebrukers',
'tog-watchlisthideminor'      => 'Verbarg kleine wiezigingen in mien volglieste',
'tog-watchlisthideliu'        => 'Bewarkingen van an-emelde gebrukers op mien volglieste verbargen',
'tog-watchlisthideanons'      => 'Bewarkingen van annenieme gebrukers op mien volglieste verbargen',
'tog-watchlisthidepatrolled'  => 'Wiezigingen dee emarkeerd bin op volglieste verbargen',
'tog-nolangconversion'        => 'Ummezetten van varianten uutschakelen',
'tog-ccmeonemails'            => 'Stuur mien kopieën van berichen an aandere gebrukers',
'tog-diffonly'                => 'Pagina-inhoud neet onder de an-egeven wiezigingen weergeven.',
'tog-showhiddencats'          => 'Verbörgen kattegerieën weergeven',
'tog-noconvertlink'           => 'Paginanaamconversie uutschakelen',
'tog-norollbackdiff'          => "Wiezigingen vortlaoten nao 't weerummedreien",

'underline-always'  => 'Altied',
'underline-never'   => 'Nooit',
'underline-default' => 'Standardinstelling',

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
'thu'           => 'don',
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
'jul'           => 'juli',
'aug'           => 'aug',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'dec',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kattegerie|Kattegerieën}}',
'category_header'                => 'Artikels in kattegerie $1',
'subcategories'                  => 'Subkattegerieën',
'category-media-header'          => 'Media in kattegerie "$1"',
'category-empty'                 => "''Disse kattegerie bevat op 't mement nog gien artikels of media.''",
'hidden-categories'              => 'Verbörgen {{PLURAL:$1|kattegerie|kattegerieën}}',
'hidden-category-category'       => 'Verbörgen kattegerieën', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Disse kattegerie hef de volgende subkattegerie.|Disse kattegerie hef de volgende {{PLURAL:$1|subkattegerie|$1 subkattegerieën}}, van een totaal van $2.}}',
'category-subcat-count-limited'  => 'Disse kattegerie hef de volgende {{PLURAL:$1|subkattegerie|$1 subkattegerieën}}.',
'category-article-count'         => "{{PLURAL:$2|Disse kattegerie bevat de volgende pagina.|Disse kattegerie bevat de volgende {{PLURAL:$1|pagina|$1 pagina's}}, van in totaal $2.}}",
'category-article-count-limited' => "Disse kattegerie bevat de volgende {{PLURAL:$1|pagina|$1 pagina's}}.",
'category-file-count'            => "{{PLURAL:$2|Disse kattegerie bevat 't volgende bestaand.|Disse kattegerie bevat {{PLURAL:$1|'t volgende bestaand|de volgende $1 bestanen}}, van in totaal $2.}}",
'category-file-count-limited'    => "Disse kattegerie bevat {{PLURAL:$1|'t volgende bestaand|de volgende $1 bestanen}}.",
'listingcontinuesabbrev'         => '(vervolg)',

'mainpagetext'      => "<big>'''’t Installeren van de MediaWiki pregrammetuur is succesvol.'''</big>",
'mainpagedocfooter' => "Raodpleeg de [http://meta.wikimedia.org/wiki/Help:Contents haandleiding] veur infermasie over 't gebruuk van de wikipregrammetuur.

== Meer hulpe ==
* [http://www.mediawiki.org/wiki/Help:Configuration_settings Lieste mit instellingen]
* [http://www.mediawiki.org/wiki/Help:FAQ MediaWiki-vragen dee vake esteld wonnen]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki-poslieste veur nieje versies]",

'about'          => 'Infermasie',
'article'        => 'artikel',
'newwindow'      => '(niej vienster)',
'cancel'         => 'Annuleren',
'qbfind'         => 'Zeuken',
'qbbrowse'       => 'Blaojen',
'qbedit'         => 'Bewark',
'qbpageoptions'  => 'Pagina-opties',
'qbpageinfo'     => 'Pagina-infermasie',
'qbmyoptions'    => 'Veurkeuren',
'qbspecialpages' => "Speciale pagina's",
'moredotdotdot'  => 'Meer...',
'mypage'         => 'Mien gebrukerspagina',
'mytalk'         => 'Mien overleg',
'anontalk'       => 'Overlegpagina veur dit IP-adres',
'navigation'     => 'Navigasie',
'and'            => '&#32;en',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Foutmelding',
'returnto'          => 'Weerumme naor $1.',
'tagline'           => 'Van {{SITENAME}}',
'help'              => 'Hulp en kontak',
'search'            => 'Zeuken',
'searchbutton'      => 'Zeuken',
'go'                => 'artikel',
'searcharticle'     => 'artikel',
'history'           => 'Geschiedenisse',
'history_short'     => 'Geschiedenisse',
'updatedmarker'     => 'bie-ewark sins mien leste bezeuk',
'info_short'        => 'Infermasie',
'printableversion'  => 'Ofdrokbaore versie',
'permalink'         => 'Vaste verwiezing',
'print'             => 'Ofdrokken',
'edit'              => 'Bewarken',
'create'            => 'Anmaken',
'editthispage'      => 'Pagina bewarken',
'create-this-page'  => 'Disse pagina anmaken',
'delete'            => 'Vortdoon',
'deletethispage'    => 'Disse pagina vortdoon',
'undelete_short'    => '$1 {{PLURAL:$1|versie|versies}} weerummeplaosen',
'protect'           => 'Beveiligen',
'protect_change'    => 'wiezigen',
'protectthispage'   => 'Beveiligen',
'unprotect'         => 'Vriegeven',
'unprotectthispage' => 'Beveiliging opheffen',
'newpage'           => 'Nieje pagina',
'talkpage'          => 'Overlegpagina',
'talkpagelinktext'  => 'Overleg',
'specialpage'       => 'speciale pagina',
'personaltools'     => 'Persoonlijke instellingen',
'postcomment'       => 'Niej onderwarp',
'articlepage'       => 'Artikel',
'talk'              => 'Overleg',
'views'             => 'Aspekken/acties',
'toolbox'           => 'Hulpmiddels',
'userpage'          => 'gebrukerspagina',
'projectpage'       => 'Bekiek prejekpagina',
'imagepage'         => 'Bestaanspagina bekieken',
'mediawikipage'     => 'Tiejige bekieken',
'templatepage'      => 'Mal bekieken',
'viewhelppage'      => 'Hulppagina bekieken',
'categorypage'      => 'Kattegeriepagina bekieken',
'viewtalkpage'      => 'Teun overlegpagina',
'otherlanguages'    => "Interwiki's",
'redirectedfrom'    => '(deur-estuurd vanof "$1")',
'redirectpagesub'   => 'Deurstuurpagina',
'lastmodifiedat'    => "Disse pagina is 't les ewiezig op $1 um $2.", # $1 date, $2 time
'viewcount'         => 'Disse pagina is $1 {{PLURAL:$1|keer|keer}} bekeken.',
'protectedpage'     => 'Beveiligen pagina',
'jumpto'            => 'Gao naor:',
'jumptonavigation'  => 'navigasie',
'jumptosearch'      => 'zeuk',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Over {{SITENAME}}',
'aboutpage'            => 'Project:Info',
'copyright'            => 'De inhold is beschikbaor onder de $1.',
'copyrightpagename'    => '{{SITENAME}}-auteursrechen',
'copyrightpage'        => '{{ns:project}}:Auteursrechen',
'currentevents'        => "In 't niejs",
'currentevents-url'    => "Project:In 't niejs",
'disclaimers'          => 'Veurbehold',
'disclaimerpage'       => 'Project:Veurbehold',
'edithelp'             => "Hulpe bie 't bewarken",
'edithelppage'         => 'Help:Uutleg',
'faq'                  => 'Vragen dee vake esteld wonnen',
'faqpage'              => 'Project:Vragen dee vake esteld wonnen',
'helppage'             => 'Help:Inhold',
'mainpage'             => 'Veurblad',
'mainpage-description' => 'Veurblad',
'policy-url'           => 'Project:Beleid',
'portal'               => 'Gebrukerspertaol',
'portal-url'           => 'Project:Gebrukerspertaol',
'privacy'              => 'Gegevensbeleid',
'privacypage'          => 'Project:Gegevensbeleid',

'badaccess'        => 'Gien toestemming',
'badaccess-group0' => 'Je hemmen gien toestemming um disse actie uut te voeren.',
'badaccess-groups' => 'Disse actie kan allinnig uut-evoerd wonnen deur gebrukers uut {{PLURAL:$2|de groep|een van de groepen}}: $1.',

'versionrequired'     => 'Versie $1 van MediaWiki is neudig',
'versionrequiredtext' => 'Versie $1 van MediaWiki is neudig um disse pagina te gebruken. Zie [[Special:Version|Versie]].',

'ok'                      => 'Oké',
'retrievedfrom'           => 'Van "$1"',
'youhavenewmessages'      => 'Je hemmen $1 ($2).',
'newmessageslink'         => 'een niej berich',
'newmessagesdifflink'     => 'wieziging weergeven',
'youhavenewmessagesmulti' => 'Je hemmen een niej berich op $1',
'editsection'             => 'bewark',
'editold'                 => 'bewark',
'viewsourceold'           => 'brontekse bekieken',
'editlink'                => 'bewark',
'viewsourcelink'          => 'brontekse bekieken',
'editsectionhint'         => 'Bewarkingsveld: $1',
'toc'                     => 'Onderwarpen',
'showtoc'                 => 'Teun',
'hidetoc'                 => 'Verbarg',
'thisisdeleted'           => 'Bekieken of herstellen van $1?',
'viewdeleted'             => 'Bekiek $1?',
'restorelink'             => '{{PLURAL:$1|versie dee vort-edaon is|versies dee vort-edaon bin}}',
'feedlinks'               => 'Kenaal:',
'feed-invalid'            => 'Ongeldig abonnementstype.',
'feed-unavailable'        => 'Syndicasiefeeds bin neet beschikbaor',
'site-rss-feed'           => '$1 RSS-feed',
'site-atom-feed'          => '$1 Atom-feed',
'page-rss-feed'           => '"$1" RSS-feed',
'page-atom-feed'          => '"$1" Atom-feed',
'red-link-title'          => '$1 (pagina besteet nog neet)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikel',
'nstab-user'      => 'Gebruker',
'nstab-media'     => 'Media',
'nstab-special'   => 'Speciale pagina',
'nstab-project'   => 'Prejekpagina',
'nstab-image'     => 'Ofbeelding',
'nstab-mediawiki' => 'Tiejige',
'nstab-template'  => 'Mal',
'nstab-help'      => 'Hulpe',
'nstab-category'  => 'Kattegerie',

# Main script and global functions
'nosuchaction'      => 'De op-egeven haandeling besteet neet',
'nosuchactiontext'  => "De opdrach in 't webadres in ongeldig.
Je hemmen 't webadres meschien verkeerd in-etik of de verkeerde verwiezing evolg.
Dit kan oek dujen op een fout in de pregrammetuur van {{SITENAME}}.",
'nosuchspecialpage' => 'Der besteet gien speciale pagina mit disse naam',
'nospecialpagetext' => "<big>'''Disse speciale pagina wonnen neet herkend deur de pregrammetuur.'''</big>

Een lieste mit bestaonde speciale pagina ku-j vienen op [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'                => 'Foutmelding',
'databaseerror'        => 'Fout in de databanke',
'dberrortext'          => 'Bie \'t zeuken is een syntaxfout in de databanke op-etrejen.
De oorzake hiervan kan dujen op een fout in de pregrammetuur.

De leste zeukpoging in de databanke was:
<blockquote><tt>$1</tt></blockquote>
vanuut de functie "<tt>$2</tt>".
MySQL gaf de foutmelding "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Bie \'t opvragen van de databanke is een syntaxfout op-etrejen. De leste opdrach was:
"$1"
Vanuut de functie "$2"
MySQL gaf de volgende foutmelding: "$3: $4".',
'noconnect'            => 'De wiki hef technische preblemen en kan de databanke neet bereiken.<br />
$1',
'nodb'                 => 'Selectie van databanke $1 is neet meugelijk.',
'cachederror'          => 'Hieronder wonnen een versie uut de kas weer-egeven. Dit is meschien neet de leste versie.',
'laggedslavemode'      => "<strong>Waorschuwing:</strong> 't is meugelijk dat leste wiezigingen in de tekse van dit artikel nog neet verwark bin.",
'readonly'             => 'De databanke is beveilig',
'enterlockreason'      => "Geef een rejen veur de blokkering op en hoelange 't geet duren. De op-egeven rejen zal an de gebrukers eteund wonnen.",
'readonlytext'         => "De databanke van {{SITENAME}} is noen esleuten veur nieje bewarkingen en wiezigingen, werschienlijk veur bestaansonderhoud. De verantwoordelijke systeembeheerder gaf hierveur de volgende rejen op: '''$1'''",
'missing-article'      => 'In de databanke steet gien tekse veur de pagina "$1" dee der wel in zol mutten staon ($2).

Dit kan koemen deurda-j een ouwe verwiezing naor \'t verschil tussen twee versies van een pagina volgen of een versie opvragen dee vort-edaon is.

As dat neet zo is, dan he-j meschien een fout in de pregremmetuur evunnen.
Meld \'t dan effen bie een [[Special:ListUsers/sysop|systeembeheerder]] van {{SITENAME}} en vermeld derbie de internetverwiezing van disse pagina.',
'missingarticle-rev'   => '(versienummer: $1)',
'missingarticle-diff'  => '(Wieziging: $1, $2)',
'readonly_lag'         => 'De databanke is autematisch beveilig, zodat de onder-eschikken servers zich kunnen synchroniseren mit de centrale server.',
'internalerror'        => 'Interne fout',
'internalerror_info'   => 'Interne fout: $1',
'filecopyerror'        => 'Kon bestaand "$1" neet naor "$2" kopiëren.',
'filerenameerror'      => 'Bestaansnaamwieziging "$1" naor "$2" neet meugelijk.',
'filedeleteerror'      => 'Kon bestaand "$1" neet vortdoon.',
'directorycreateerror' => 'Map "$1" kon neet an-emaak wonnen.',
'filenotfound'         => 'Kon bestaand "$1" neet vienen.',
'fileexistserror'      => 'Schrieven naor bestaand "$1" was neet meugelijk: \'t bestaand besteet al',
'unexpected'           => 'Onverwachen weerde: "$1"="$2".',
'formerror'            => 'Fout: kon formelier neet versturen',
'badarticleerror'      => 'Disse haandeling kan op disse pagina neet uut-evoerd wonnen.',
'cannotdelete'         => 'Kon de pagina of ofbeelding neet vort-edaon wonnen.',
'badtitle'             => 'Ongeldige naam',
'badtitletext'         => 'De naam van de op-evreugen pagina is neet geldig, leeg, of een interwiki-verwiezing naor een onbekende of ongeldige wiki.',
'perfcached'           => 'Disse gegevens kwammen uut de kas en bin werschienlijk neet actueel:',
'perfcachedts'         => 'De infermasie dee hieronder steet, is op-esleugen, en is van $1.',
'querypage-no-updates' => "'''Disse pagina wonnen neet meer bie-ewark.'''",
'wrong_wfQuery_params' => 'Parremeters veur wfQuery() wanen verkeerd<br />
Functie: $1<br />
Zeukopdrachte: $2',
'viewsource'           => 'Brontekse bekieken',
'viewsourcefor'        => 'veur "$1"',
'actionthrottled'      => 'Haandeling tegen-ehuilen',
'actionthrottledtext'  => "As maotregel tegen 't ongewunst plaosen van verwiezingen naor aandere websteeën is 't antal keren da-j disse haandeling in een korte tied uutvoeren kunnen beteund. Je hemmen de limiet overschrejen. Prebeer 't over een antal menuten weer.",
'protectedpagetext'    => 'Disse pagina is beveilig um bewarkingen te veurkoemen.',
'viewsourcetext'       => 'Je kunnen de brontekse van disse pagina bewarken en bekieken:',
'protectedinterface'   => 'Disse pagina bevat een tekse dee gebruuk wonnen veur systeemteksen van de wiki. Allinnig beheerders kunnen disse pagina bewarken.',
'editinginterface'     => "'''Waorschuwing:''' je bewarken een pagina dee gebruuk wonnen deur de pregrammetuur. Wiezigingen dee an-ebröch wonnen op disse pagina zullen 't uterlijk veur iederene beïnvleujen. Overweeg veur vertalingen um [http://translatewiki.net/wiki/Main_Page?setlang=nds-nl translatewiki.net] te gebruken, 't vertalingsprejek veur MediaWiki.",
'sqlhidden'            => '(SQL-zeukopdrachte verbörgen)',
'cascadeprotected'     => 'Disse pagina is beveilig umdat \'t veurkump in de volgende {{PLURAL:$1|pagina|pagina\'s}}, dee beveilig {{PLURAL:$1|is|bin}} mit de "cascade"-optie:
$2',
'namespaceprotected'   => "Je bin neet bevoeg um pagina is de '''$1'''-naamruumte te bewarken.",
'customcssjsprotected' => 'Je kunnen disse pagina neet bewarken umdat der persoonlijke instellingen van een aandere gebruker in staon.',
'ns-specialprotected'  => "Speciale pagina's kunnen neet bewörk wonnen.",
'titleprotected'       => "'t Anmaken van disse pagina is beveilig deur [[User:$1|$1]].
De op-egeven rejen is ''$2''.",

# Virus scanner
'virus-badscanner'     => "Slichte configurasie: onbekende virusscanner: ''$1''",
'virus-scanfailed'     => 'scannen is mislok (code $1)',
'virus-unknownscanner' => 'onbekende virusscanner:',

# Login and logout pages
'logouttitle'                => 'Ofmelden gebruker',
'logouttext'                 => "'''Je bin noen of-emeld.'''

Je kunnen {{SITENAME}} noen anneniem gebruken of onder disse of een aandere gebrukersnaam je eigen weer anmelden.
't Kan ween dat der een antal pagina's weer-egeven wonnen asof je an-emeld bin totda-j de kas van joew webkieker leegmaken.",
'welcomecreation'            => '<h2>Welkom, $1!</h2><p>Joew gebrukersprefiel is an-emaak. Je kunnen noen joew persoonlijke veurkeuren instellen.</p>',
'loginpagetitle'             => 'Gebrukersnaam',
'yourname'                   => 'Gebrukersnaam',
'yourpassword'               => 'Wachwoord',
'yourpasswordagain'          => 'Opniej invoeren',
'remembermypassword'         => 'vanzelf anmelden',
'yourdomainname'             => 'Joew domein',
'externaldberror'            => 'Der gung iets fout bie de externe authenticering, of je maggen je gebrukersprefiel neet bewarken.',
'login'                      => 'Anmelden',
'nav-login-createaccount'    => 'Anmelden',
'loginprompt'                => 'Je mutten cookies an hemmen staon um an te kunnen melden bie {{SITENAME}}.',
'userlogin'                  => 'Anmelden',
'logout'                     => 'Ofmelden',
'userlogout'                 => 'Ofmelden',
'notloggedin'                => 'Neet an-emeld',
'nologin'                    => 'He-j nog gien gebrukersnaam? $1.',
'nologinlink'                => 'Maak een gebrukersprefiel an',
'createaccount'              => 'Niej gebrukersprefiel anmaken',
'gotaccount'                 => 'Stao-j al in-eschreven? $1.',
'gotaccountlink'             => 'Anmelden',
'createaccountmail'          => 'per netpos',
'badretype'                  => 'De wachwoorden dee-j in-etik hemmen bin neet liekeleens.',
'userexists'                 => 'Disse gebrukersnaam is al gebruuk. 
Kies een aandere naam.',
'youremail'                  => 'Netposadres (neet verplich) *',
'username'                   => 'Gebrukersnaam:',
'uid'                        => 'Gebrukersnummer:',
'prefs-memberingroups'       => 'Lid van {{PLURAL:$1|groep|groepen}}:',
'yourrealname'               => 'Echte naam (neet verplich)',
'yourlanguage'               => 'Taal veur systeemteksen',
'yourvariant'                => 'Gewunste taal:',
'yournick'                   => 'Alias veur ondertekeningen',
'badsig'                     => 'Ongeldige haandtekening; HTML naokieken.',
'badsiglength'               => "Joew haandtekening is te lang.
't Mut minder as {{PLURAL:$1|letter|letters}} hemmen.",
'yourgender'                 => 'Geslachte:',
'gender-unknown'             => 'Neet an-egeven',
'gender-male'                => 'Keel',
'gender-female'              => 'Deerne',
'prefs-help-gender'          => 'Optioneel: dit wonnen gebruuk um gebrukers op een juuste meniere an te spreken in de pregrammetuur.
Disse infermasie is zichbaor veur aandere gebrukers.',
'email'                      => 'Privéberichen',
'prefs-help-realname'        => '* Echte naam (optioneel): a-j disse optie invullen zal joew echte naam gebruuk wonnen veur toekenningen veur joew warkzaamheen.',
'loginerror'                 => 'Anmeldingsfout',
'prefs-help-email'           => "Een netposadres is neet verplich, mar zo ku-w wè joew wachwoord toesturen veur a-j 't vergeten bin.
Je kunnen oek aandere luui in staot stellen um per netpos kontak mit joe op te nemen via de verwiezing op joew gebrukers- en overlegpagina, zonder da-j joew identiteit priesgeven.",
'prefs-help-email-required'  => 'Hier he-w een netposadres veur neudig.',
'nocookiesnew'               => 'De gebrukersnaam is an-emaak, mar je bin neet an-emeld.
{{SITENAME}} gebruuk cookies um gebrukers an te melden.
Je hemmen cookies uut-eschakeld.
Schakel cookies weer in, en meld daornao an mit de nieje gegevens.',
'nocookieslogin'             => "'t Anmelden is mislok umdat de webkieker gien cookies an hef staon. Prebeer 't accepteren van cookies an te zetten en daornao opniej an te melden.",
'noname'                     => 'Je mutten een gebrukersnaam opgeven.',
'loginsuccesstitle'          => 'Succesvol an-emeld',
'loginsuccess'               => 'Je bin noen an-emeld bie {{SITENAME}} as "$1".',
'nosuchuser'                 => 'Der is gien gebruker mit de naam "$1".
Gebrukersnamen bin heuflettergeveulig.
Kiek de schriefwieze effen nao of [[Special:UserLogin/signup|maak een nieje gebruker an]].',
'nosuchusershort'            => 'Der is gien gebruker mit de naam "$1". Kiek de spelling nao.',
'nouserspecified'            => 'Vul asjeblief een naam in',
'wrongpassword'              => "verkeerd wachwoord, prebeer 't opniej.",
'wrongpasswordempty'         => "Gien wachwoord in-evoerd. Prebeer 't opniej.",
'passwordtooshort'           => "Wachwoord is te kort.
't Mut uut minstens $1 {{PLURAL:$1|teken|tekens}} bestaon.",
'mailmypassword'             => 'Niej wachwoord opsturen',
'passwordremindertitle'      => 'niej tiedelik wachwoord veur {{SITENAME}}',
'passwordremindertext'       => 'Iemand vanof \'t IP-adres $1 (werschienlijk jiezelf) hef evreugen 
um een niej wachwoord veur {{SITENAME}} ($4) toe te sturen. 
Der is een tiedelijk wachwoord an-emaak veur gebruker "$2":
"$3". As \'t neet de bedoeling was, meld dan an en kies een niej wachwoord.
Joew tiedelijke wachwoord zal verlopen over {{PLURAL:$5|één dag|$5 dagen}}.

A-j dit verzeuk neet zelf edaon hemmen of a-j \'t wachwoord weer weten 
en \'t neet meer wiezigen willen, negeer dit berich dan 
en blief joew bestaonde wachwoord gebruken.',
'noemail'                    => 'Gien netposadres eregistreerd veur "$1".',
'passwordsent'               => 'Der is een niej wachwoord verstuurd naor \'t netposadres van gebruker "$1". Meld an, a-j \'t wachwoord ontvangen.',
'blocked-mailpassword'       => 'Dit IP-adres is eblokkeerd. Dit betekent da-j neet bewarken kunnen en dat {{SITENAME}} joew wachwoord neet weerummehaolen kan, dit wonnen edaon um misbruuk tegen te gaon.',
'eauthentsent'               => "Der is een bevestigingsberich naor 't op-egeven netposadres verstuurd. Veurdat der veerdere berichen naor dit netposadres verstuurd kunnen wonnen, mu-j de instructies volgen in 't toe-esturen berich, um te bevestigen da-j joe eigen daodwarkelijk an-emeld hemmen.",
'throttled-mailpassword'     => 'In de leste {{PLURAL:$1|uur|$1 ure}} is der al een wachwoordherinnering estuurd.
Um misbruuk te veurkoemen wonnen der mar één wachwoordherinnering per {{PLURAL:$1|uur|$1 ure}} verzunnen.',
'mailerror'                  => "Fout bie 't versturen van berich: $1",
'acct_creation_throttle_hit' => 'Onder dit IP-adres hemmen luui de veurbieje dag al {{PLURAL:$1|1 gebruker|$1 gebrukers}} an-emaak. Meer is neet toe-estaon in disse periode. Daorumme kunnen gebrukers mit dit IP-adres noen effen gien gebrukers meer anmaken.',
'emailauthenticated'         => 'Joew netposadres is bevestig op $2 um $3.',
'emailnotauthenticated'      => 'Netposadres is <strong>nog neet bevestig</strong>. Je kriegen gien berichen veur de onstaonde opties.',
'noemailprefs'               => 'Gien netposadres in-evoerd, waodeur de onderstaonde functies neet warken.',
'emailconfirmlink'           => 'Bevestig netposadres',
'invalidemailaddress'        => "'t Netposadres kon neet eaccepteerd wonnen umdat de opmaak ongeldig is. 
Voer de juuste opmaak van 't adres in of laot 't veld leeg.",
'accountcreated'             => 'Gebrukersprefiel is an-emaak',
'accountcreatedtext'         => 'De gebrukersnaam veur $1 is an-emaak.',
'createaccount-title'        => 'Gebrukers anmaken veur {{SITENAME}}',
'createaccount-text'         => 'Der hef der ene een gebruker veur $2 an-emaak op {{SITENAME}} ($4). \'t Wachwoord veur "$2" is "$3".
Meld je noen an en wiezig \'t wachwoord.

Negeer dit berich as disse gebruker zonder joew toestemming an-emaak is.',
'login-throttled'            => "Je hemmen leste paor keren te vake eprebeerd um an te melden mit een verkeerd wachwoord.
Je mutten effen wachen veurda-j 't opniej preberen kunnen.",
'loginlanguagelabel'         => 'Taal: $1',

# Password reset dialog
'resetpass'                 => 'Wachwoord wiezigen',
'resetpass_announce'        => "Je bin an-emeld mit een veurlopige code dee mit de netpos toe-estuurd wonnen. Um 't anmelden te voltooien, mu-j een niej wachwoord invoeren:",
'resetpass_text'            => '<!-- Tekse hier invoegen -->',
'resetpass_header'          => 'Wachwoord wiezigen',
'oldpassword'               => 'Wachwoord da-j noen hemmen',
'newpassword'               => 'Niej wachwoord',
'retypenew'                 => 'Niej wachwoord (opniej)',
'resetpass_submit'          => "Voer 't wachwoord in en meld je an",
'resetpass_success'         => 'Joew wachwoord is succesvol ewiezig. Je wonnen noen an-emeld...',
'resetpass_bad_temporary'   => 'Ongeldig tiedelijk wachwoord. Je hemmen joew wachwoord al ewiezig of een niej tiedelijk wachwoord an-evreugen.',
'resetpass_forbidden'       => 'Wachwoorden kunnen neet ewiezig wonnen',
'resetpass-no-info'         => 'Je mutten an-emeld ween veurda-j disse pagina gebruken kunnen.',
'resetpass-submit-loggedin' => 'Wachwoord wiezigen',
'resetpass-wrong-oldpass'   => "'t Veurlopige wachwoord of 't wachwoord da-j noen hemmen is ongeldig.
Meschien he-j 't wachwoord al ewiezig of een niej veurlopig wachwoord an-evreugen.",
'resetpass-temp-password'   => 'Veurlopig wachwoord:',
'resetpass-log'             => 'Wachwoordherstellogboek',
'resetpass-logtext'         => "Disse pagina bevat een logboek mit gebrukers waovan 't wachwoord opniej in-esteld is deur een beheerder.",
'resetpass-logentry'        => "hef 't wachwoord van $1 ewiezig",
'resetpass-comment'         => "Rejen veur 't opniej instellen van 't wachwoord",

# Edit page toolbar
'bold_sample'     => 'Vet-edrokken tekse',
'bold_tip'        => 'Vet-edrokken tekse',
'italic_sample'   => 'Schunedrokken tekse',
'italic_tip'      => 'Schunedrok',
'link_sample'     => 'Onderwarp',
'link_tip'        => 'Interne verwiezing',
'extlink_sample'  => 'http://www.example.com linktekst',
'extlink_tip'     => 'Uutgaonde verwiezing',
'headline_sample' => 'Deelonderwarp',
'headline_tip'    => 'Deelonderwarp',
'math_sample'     => 'a^2 + b^2 = c^2',
'math_tip'        => 'Wiskundige formule (in LaTeX)',
'nowiki_sample'   => 'Tekse zonder wiki-opmaak.',
'nowiki_tip'      => 'Gien wiki-opmaak toepassen',
'image_sample'    => 'Veurbeeld.jpg',
'image_tip'       => 'Ofbeelding',
'media_sample'    => 'Veurbeeld.ogg',
'media_tip'       => 'Verwiezing naor bestaand',
'sig_tip'         => 'Joew ondertekening (mit daotum en tied)',
'hr_tip'          => 'Horizontale liende',

# Edit pages
'summary'                          => 'Samenvatting:',
'subject'                          => 'Onderwarp:',
'minoredit'                        => 'kleine wieziging / spelling',
'watchthis'                        => 'volg disse pagina',
'savearticle'                      => 'Pagina opslaon',
'preview'                          => 'Naokieken',
'showpreview'                      => 'Pagina naokieken',
'showlivepreview'                  => 'Drekte weergave',
'showdiff'                         => 'Teun wiezigingen',
'anoneditwarning'                  => "'''Waorschuwing:''' Je bin neet an-emeld.
As annenieme gebruker zal joew IP-adres bie elke bewarking veur iederene zichbaor ween.",
'missingsummary'                   => "'''Herinnering:''' je hemmen gien samenvatting op-egeven veur de bewarking. A-j noen weer op ''Pagina opslaon'' klikken wonnen de bewarking zonder samenvatting op-esleugen.",
'missingcommenttext'               => 'Plaos joew opmarking hieronder.',
'missingcommentheader'             => "'''Let wel:''' je hemmen gien onderwarptitel toe-evoeg. A-j opniej op Pagina opslaon klikken wonnen de bewarking op-esleugen zonder onderwarptitel.",
'summary-preview'                  => 'Samenvatting naokieken:',
'subject-preview'                  => 'Onderwarp/kop naokieken:',
'blockedtitle'                     => 'Gebruker is eblokkeerd',
'blockedtext'                      => "<big>'''Joew gebrukersnaam of IP-adres is eblokkeerd.'''</big>

Je bin eblokkeerd deur: $1.
De op-egeven rejen is: ''$2''.

* Eblokkeerd vanof: $8
* Eblokkeerd tot: $6
* Bedoeld um te blokkeren: $7

Je kunnen kontak opnemen mit $1 of een aandere [[{{MediaWiki:Grouppage-sysop}}|beheerder]] um de blokkering te bepraoten.
Je kunnen gien gebruukmaken van de functie 'een berich sturen', behalven a-j een geldig netposadres op-egeven hemmen in joew [[Special:Preferences|veurkeuren]] en 't gebruuk van disse functie neet eblokkeerd is.
't IP-adres da-j noen gebruken is $3 en 't blokkeringsnummer is #$5. 
Vermeld 't allebeie a-j argens op disse blokkering reageren.",
'autoblockedtext'                  => 'Joew IP-adres is autematisch eblokkeerd umdat \'t gebruuk wönnen deur een aandere gebruker, dee eblokkeerd wönnen deur $1.
De rejen hierveur was:

:\'\'$2\'\'

* Begint: $8
* Verloop nao: $6
* Wee eblokkeerd wonnen: $7

Je kunnen kontak opnemen mit $1 of een van de aandere
[[{{MediaWiki:Grouppage-sysop}}|beheerders]] um de blokkering te bepraoten.

NB: je kunnen de optie "een berich sturen" neet gebruken, behalven a-j een geldig netposadres op-egeven hemmen in de [[Special:Preferences|gebrukersveurkeuren]] en je neet eblokkeerd bin.

Joew IP-adres is $3 en joew blokkeernummer is $5. 
Geef disse nummers deur a-j kontak mit ene opnemen over de blokkering.',
'blockednoreason'                  => 'gien rejen op-egeven',
'blockedoriginalsource'            => "De brontekse van '''$1''' wonnen hieronder weer-egeven:",
'blockededitsource'                => "De tekse van '''joew eigen bewarkingen''' an '''$1''' wonnen hieronder weer-egeven:",
'whitelistedittitle'               => 'Um disse pagina te bewarken, mu-j je anmelden',
'whitelistedittext'                => "Um pagina's te kunnen wiezigen, mu-j $1 ween",
'confirmedittitle'                 => 'Berichbevestiging is neudig um te bewarken.',
'confirmedittext'                  => "Je mutten je posadres bevestigen veurda-j bewarken kunnen. Vul je adres in en bevestig 't via [[Special:Preferences|mien veurkeuren]].",
'nosuchsectiontitle'               => 'Disse sectie besteet neet',
'nosuchsectiontext'                => 'Je preberen een sectie te bewarken dat neet besteet. Umdat der gien sectie $1 is, is der gien plaos um joew bewarking op te slaon.',
'loginreqtitle'                    => 'Anmelden verplich',
'loginreqlink'                     => 'Anmelden',
'loginreqpagetext'                 => 'Je mutten $1 um disse pagina te bekieken.',
'accmailtitle'                     => 'Wachwoord is verzunnen.',
'accmailtext'                      => "Der is een willekeurig wachwoord veur [[User talk:$1|$1]] verstuurd naor $2.

't Wachwoord veur disse gebruker kan ewiezig wonnen deur de pagina ''[[Special:ChangePassword|wachwoord wiezigen]]'' te gebruken.",
'newarticle'                       => '(Niej)',
'newarticletext'                   => "Disse pagina besteet nog neet. Hieronder ku-j wat schrieven en naokieken of opslaon. A-j hier per ongelok terechtekeumen bin gebruuk dan de knoppe ''veurige'' um weerumme te gaon.",
'anontalkpagetext'                 => "---- ''Disse overlegpagina heurt bie een annenieme gebruker dee: óf gien gebrukersnaam hef, óf 't neet gebruuk. We gebruken daorumme 't IP-adres ter herkenning, mar 't kan oek ween dat meerdere personen 'tzelfde IP-adres gebruken, en da-j hiermee berichen ontvangen dee neet veur joe bedoeld bin. A-j dit veurkoemen willen, dan ku-j 't bes [[Special:UserLogin|een gebrukersnaam anmaken of anmelden]].''",
'noarticletext'                    => 'Der steet op hejen gien tekse op disse pagina.
Je kunnen [[Special:Search/{{PAGENAME}}|de titel opzeuken]] in aandere pagina\'s,
<span class="plainlinks">[{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}} zeuken in de logboeken],
of [{{fullurl:{{FULLPAGENAME}}|action=edit}} disse pagina bewarken]</span>.',
'userpage-userdoesnotexist'        => 'Je bewarken een gebrukerspagina van een gebruker dee neet besteet (gebruker "$1"). Kiek effen nao o-j disse pagina wel anmaken/bewarken willen.',
'clearyourcache'                   => "'''NB:''' naodat de wiezigingen op-esleugen bin, mut de kas van de webkieker nog leeg-emaak wonnen um 't te kunnen zien. '''Mozilla / Firefox / Safari:''' drok op ''Shift'' + ''Pagina verniejen,'' of ''Ctrl-F5'' of ''Ctrl-R'' (''Command-R'' op een Macintosh-computer); '''Konqueror: '''klik op ''verniejen'' of drok op ''F5;'' '''Opera:''' leeg de kas in ''Extra → Voorkeuren;'' '''Internet Explorer:''' huil ''Ctrl'' in-edrok terwiel je op ''Pagina verniejen'' klikken of ''Ctrl-F5'' gebruken.",
'usercssjsyoucanpreview'           => "'''Tip:''' gebruuk de knoppe 'Pagina naokieken' um joew nieje css/js nao te kieken veurda-j 't opslaon.",
'usercsspreview'                   => "'''Dit is allinnig een controle van joew persoonlijke CSS.'''
''''t Is nog neet op-esleugen!'''",
'userjspreview'                    => "'''Denk deran da-j joew persoonlijke JavaScript allinnig nog mar an 't bekieken bin, 't is nog neet op-esleugen!'''",
'userinvalidcssjstitle'            => "'''Waorschuwing:''' der is gien uutvoering mit de naam \"\$1\". Vergeet neet dat joew eigen .css- en .js-pagina's beginnen mit een kleine letter, bv. \"{{ns:user}}:Naam/'''m'''onobook.css\" in plaose van \"{{ns:user}}:Naam/'''M'''onobook.css\".",
'updated'                          => '(Bewark)',
'note'                             => "'''Opmarking:'''",
'previewnote'                      => "'''NB: je bin de pagina allinnig nog mar an 't naokieken; de tekse is nog neet op-esleugen!'''",
'previewconflict'                  => "Disse versie laot zien ho de tekse in 't bovenste veld deruut kump te zien a-j de tekse opslaon.",
'session_fail_preview'             => "'''De bewarking kan neet verwark wonnen wegens een verlies an data. Prebeer 't laoter weer, as 't prebleem dan nog steeds veurkump, prebeer dan opniej an te melden.'''",
'session_fail_preview_html'        => "'''Joew wieziging kon neet verwark wonnen umdat sessiegegevens verleuren egaon bin.'''

''Umdat in {{SITENAME}} roewe HTML in-eschakeld is, is de weergave dervan verbörgen um te veurkoemen dat 't JavaScript an-evuilen wonnen.''

'''As dit een legetieme wieziging is, prebeer 't dan opniej. 
As 't dan nog preblemen geef, prebeer dan um [[Special:UserLogout|opniej an te melden]].'''",
'token_suffix_mismatch'            => "'''De bewarking is eweigerd umdat de webkieker de leestekens in 't bewarkingstoken verkeerd behaandeld hef. De bewarking is eweigerd um verminking van de paginatekse te veurkoemen. Dit gebeurt soms as der een web-ebaseren proxydiens gebruuk wonnen dee fouten bevat.'''",
'editing'                          => 'Bewark: $1',
'editingsection'                   => 'Bewark: $1 (deelpagina)',
'editingcomment'                   => 'Bewark: $1 (niej onderwarp)',
'editconflict'                     => 'Bewarkingskonflik: $1',
'explainconflict'                  => "'''NB:''' een aander hef disse pagina ewiezig naoda-j an disse bewarking begunnen bin.

't Bovenste bewarkingsveld laot de pagina zien zoas 't noen is. Daoronder (bie \"Wiezigingen\") staon de verschillen tussen joew versie en de op-esleugen pagina. Helemaole onderan (bie \"Joew tekse\") steet nog een bewarkingsveld mit joew versie.

Je zullen je eigen wiezigingen in de nieje tekse in mutten passen. Allinnig de tekse in 't bovenste veld wonnen beweerd a-j noen kiezen veur \"Pagina opslaon\".",
'yourtext'                         => 'Joew tekse',
'storedversion'                    => 'Op-esleugen versie',
'nonunicodebrowser'                => "'''Waorschuwing: de webkieker kan neet goed overweg mit unicode, schakel over op een aandere webkieker um de wiezigingen an te brengen!'''",
'editingold'                       => "'''Waorschuwing: je bin een ouwere versie van disse pagina an 't bewarken. A-j de veraandering opslaon, wonnen alle niejere versies over-eschreven.'''",
'yourdiff'                         => 'Wiezigingen',
'copyrightwarning'                 => "NB: Alle biedragen an {{SITENAME}} mutten vrie-egeven wonnen onder de $2 (zie $1 veur infermasie).
A-j neet willen dat joew tekse deur aandere gebrukers an-epas en verspreid kan wonnen, kies dan neet veur 'Pagina opslaon'.<br />
Deur op 'Pagina opslaon' te klikken beleuf je da-j disse tekse zelf eschreven hemmen, of over-eneumen hemmen uut een vrieje, openbaore bron.<br />
'''GEBRUUK GIEN MATERIAAL DAT BESCHARMP WONNEN DEUR AUTEURSRECHEN, BEHALVEN A-J DAOR TOESTEMMING VEUR HEMMEN!'''",
'copyrightwarning2'                => "Let wel dat alle biedragen an {{SITENAME}} deur aandere gebrukers ewiezig of vort-edaon kunnen wonnen. A-j neet willen dat joew tekse veraanderd wonnen, plaos 't hier dan neet.<br />
De tekse mut auteursrechvrie ween (zie $1 veur details).
'''GIEN WARK VAN AANDERE LUUI TOEVOEGEN ZONDER TOESTEMMING VAN DE AUTEUR!'''",
'longpagewarning'                  => "Disse pagina is $1 kB groot. 't Bewarken van grote pagina's kan veur preblemen zörgen bie ouwere webkiekers.",
'longpageerror'                    => "'''Foutmelding: de tekse dee-j opslaon willen is $1 kilobytes. Dit is groter as 't toe-estaone maximum van $2 kilobytes. Joew tekse kan neet op-esleugen wonnen.'''",
'readonlywarning'                  => "'''Waorschuwing: De databanke is op dit mement in onderhoud; 't is daorumme neet meugelijk um pagina's te wiezigen.
Je kunnen de tekse 't beste op de computer opslaon en laoter opniej preberen de pagina te bewarken.'''

As grund is angeven: $1",
'protectedpagewarning'             => "'''Waorschuwing! Disse pagina is beveilig zodat allinnig beheerders 't kunnen wiezigen.'''",
'semiprotectedpagewarning'         => "'''Let op:''' disse pagina ku-j allinnig bewarken a-j tenminsen vier dagen in-eschreven staon.",
'cascadeprotectedwarning'          => "'''Waorschuwing:''' disse pagina is beveilig zodat allinnig beheerders disse pagina kunnen bewarken, dit wonnen edaon umdat disse pagina veurkump in de volgende {{PLURAL:$1|cascade-beveilige pagina|cascade-beveiligen pagina's}}:",
'titleprotectedwarning'            => "'''Waorschuwing: disse pagina is beveilig. Je hemmen [[Special:ListGroupRights|bepaolde rechen]] neudig um 't an te kunnen maken.'''",
'templatesused'                    => 'Mallen dee op disse pagina gebruuk bin:',
'templatesusedpreview'             => 'Mallen dee in disse bewarking gebruuk wonnen:',
'templatesusedsection'             => 'Mallen dee in disse sectie gebruuk wonnen:',
'template-protected'               => '(beveilig)',
'template-semiprotected'           => '(semibeveilig)',
'hiddencategories'                 => 'Disse pagina vuilt in de volgende verbörgen {{PLURAL:$1|kattegerie|kattegerieën}}:',
'edittools'                        => '<!-- Disse tekse wonnen weer-egeven onder bewarkings- en bestaanstoevoegingsformelieren. -->',
'nocreatetitle'                    => "'t Anmaken van pagina's is beteund",
'nocreatetext'                     => "Disse webstee hef de meugelijkheid um nieje pagina's an te maken beteund. Je kunnen pagina's dee al bestaon wiezigen of je kunnen je [[Special:UserLogin|anmelden of een gebrukerspagina anmaken]].",
'nocreate-loggedin'                => "Je hemmen gien toestemming um nieje pagina's an te maken.",
'permissionserrors'                => 'Fouten mit de rechen',
'permissionserrorstext'            => 'Je maggen of kunnen dit neet doon. De {{PLURAL:$1|rejen|rejens}} daoveur {{PLURAL:$1|is|bin}}:',
'permissionserrorstext-withaction' => 'Je hemmen gien rech um $2, mit de volgende {{PLURAL:$1|rejen|rejens}}:',
'recreate-deleted-warn'            => "'''Waorschuwing: je maken een pagina an dee eerder al vort-edaon is.'''

Bedenk eers of 't neudig is um disse pagina veerder te bewarken.
't Logboek mit de rejen(s) waorumme as disse pagina vort-edaon is, wonnen veur de dudelijkheid eteund:",
'deleted-notice'                   => "Disse pagina is vort-edaon.
Hieronder steet de infermasie uut 't logboek vort-edaone pagina's.",
'deletelog-fulllog'                => "'t Hele logboek bekieken",
'edit-hook-aborted'                => 'De bewarking is of-ebreuken deur een hook.
Der is gien rejen op-egeven.',
'edit-gone-missing'                => "De pagina kon neet bie-ewark wonnen.
't Schient dat 't vort-edaon is.",
'edit-conflict'                    => 'Bewarkingskonflik.',
'edit-no-change'                   => 'Joew bewarking is enegeerd, umdat der gien wieziging an de tekse edaon is.',
'edit-already-exists'              => "De pagina kon neet an-emaak wonnen.
't Besteet à.",

# Parser/template warnings
'expensive-parserfunction-warning'        => "Waorschuwing: disse pagina gebruuk te veul kosbaore parserfuncties.

Noen {{PLURAL:$1|is|bin}} 't der $1, terwiel 't der minder as $2 {{PLURAL:$2|mut|mutten}} ween.",
'expensive-parserfunction-category'       => "Pagina's dee te veule kosbaore parserfuncties gebruken",
'post-expand-template-inclusion-warning'  => 'Waorschuwing: de grootte van de in-evoegen mal is te groot.
Sommigen mallen wonnen neet in-evoeg.',
'post-expand-template-inclusion-category' => "Pagina's dee over de maximumgrootte veur in-evoegen mallen hinne gaon",
'post-expand-template-argument-warning'   => "Waorschuwing: disse pagina gebruuk tenminsen één parremeter in een mal, dee te groot is, as 't uut-eklap wonnen. Disse parremeters bin vort-eleuten.",
'post-expand-template-argument-category'  => "Pagina's mit ontbrekende malelementen",
'parser-template-loop-warning'            => 'Der is een kringloop in mallen waor-eneumen: [[$1]]',
'parser-template-recursion-depth-warning' => 'Der is over de recursiediepte veur mallen is hinne gaon ($1)',

# "Undo" feature
'undo-success' => 'De bewarking kan weerummedreid wonnen. Kiek de vergelieking hieronder nao um der wisse van de ween dat alles goed is, en slao de de pagina op um de bewarking weerumme te dreien.',
'undo-failure' => "De wieziging kon neet weerummedreid wonnen umdat 't ondertussen awweer ewiezig is.",
'undo-norev'   => "De bewarking kon neet weerummedreid wonnen, umdat 't neet besteet of vort-edaon is.",
'undo-summary' => 'Versie $1 van [[Special:Contributions/$2|$2]] ([[User talk:$2|overleg]]) weerummedreid.',

# Account creation failure
'cantcreateaccounttitle' => 'Anmaken van een gebrukersprefiel is neet meugelijk',
'cantcreateaccount-text' => "'t Anmaken van gebrukers van dit IP-adres (<b>$1</b>) is eblokkeerd deur [[User:$3|$3]].

De deur $3 op-egeven rejen is ''$2''",

# History pages
'viewpagelogs'           => 'Bekiek logboeken veur disse pagina',
'nohistory'              => 'Der bin gien eerdere versies van disse pagina.',
'currentrev'             => 'Leste versie',
'currentrev-asof'        => 'Leste versie van $1',
'revisionasof'           => 'Versie op $1',
'revision-info'          => 'Versie op $1 van $2', # Additionally available: $3: revision id
'previousrevision'       => '&larr; eerdere versie',
'nextrevision'           => 'niejere versie &rarr;',
'currentrevisionlink'    => "versie zoas 't noen is",
'cur'                    => 'noen',
'next'                   => 'Volgende',
'last'                   => 'leste',
'page_first'             => 'eerste',
'page_last'              => 'leste',
'histlegend'             => 'Verklaoring ofkortingen: (noen) = verschil mit de op-esleugen versie, (veurige) = verschil mit de veurige versie, K = kleine wieziging',
'history-fieldset-title' => 'Deur de geschiedenisse blaojen',
'deletedrev'             => '[vort-edaon]',
'histfirst'              => 'Eerste',
'histlast'               => 'Leste',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(leeg)',

# Revision feed
'history-feed-title'          => 'Wiezigingsoverzichte',
'history-feed-description'    => 'Wiezigingsoverzichte veur disse pagina op de wiki',
'history-feed-item-nocomment' => '$1 op $2', # user at time
'history-feed-empty'          => "De op-evreugen pagina besteet neet. 't Is meugelijk dat disse pagina vort-edaon is of dat 't herneumd is. Prebeer te [[Special:Search|zeuken]] veur relevante nieje pagina's.",

# Revision deletion
'rev-deleted-comment'            => '(commentaar vort-ehaold)',
'rev-deleted-user'               => '(gebrukersnaam vort-edaon)',
'rev-deleted-event'              => '(antekening vort-edaon)',
'rev-deleted-text-permission'    => "Disse bewarking is uut de peblieke archieven ewis.
As der meer infermasie is, ku-j dat vienen in 't [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} logboek vort-edaone pagina's].",
'rev-deleted-text-view'          => "Disse bewarking is uut de peblieke archieven ewis.
As beheerder van disse wiki ku-j 't wel zien;
as der meer infermasie is, ku-j dat vienen in 't [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} logboek vort-edaone pagina's].",
'rev-deleted-no-diff'            => "Je kunnen de verschillen neet bekieken umdat één van de versies uut 't peblieke archief vort-edaon is.
De achtergronden ku-j meugelijk vienen in 't [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} logboek mit vort-edaone pagina's].",
'rev-deleted-unhide-diff'        => "Eén van de bewarkingen van disse vergeliekingen is uut de peblieke archieven ewis.
Achtergronden ku-j meugelijk vienen in 't [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} logboek vort-edaone pagina's].
As beheerder ku-j [$1 de verschillen bekieken] a-j willen.",
'rev-delundel'                   => 'teun/verbarg',
'revisiondelete'                 => 'Wiezigingen vortdoon/herstellen',
'revdelete-nooldid-title'        => 'Gien doelversie',
'revdelete-nooldid-text'         => 'Je hemmen gien versie an-egeven waor disse actie op uut-evoerd mut wonnen.',
'revdelete-nologtype-title'      => 'Der is gien logboektype op-egeven',
'revdelete-nologtype-text'       => 'Je hemmen gien logboektype op-egeven um disse haandeling op uut te voeren.',
'revdelete-toomanytargets-title' => 'Te veul doelen',
'revdelete-toomanytargets-text'  => 'Je hemmen te veul doelen egeven um disse haandeling op uut te voeren.',
'revdelete-nologid-title'        => 'Ongeldige logboekregel',
'revdelete-nologid-text'         => 'Of je hemmen gien doellogboekregel op-egeven of de an-egeven logboekregel besteet neet.',
'revdelete-selected'             => "'''{{PLURAL:$2|Esillekteren bewarking|Esillekteren bewarkingen}} van '''[[:$1]]''':'''",
'logdelete-selected'             => "'''{{PLURAL:$1|Esillecteren logboekboekactie|Esillecteren logboekacties}}:'''",
'revdelete-text'                 => "'''Vort-edaone bewarkingen staon nog altied in de geschiedenisse en in logboeken, mar neet iederene kan de inhoud zomar bekieken.'''

Beheerders van {{SITENAME}} kunnen de verbörgen inhoud bekieken en 't weerummeplaosen deur dit scharm te gebruken, behalven as der aandere beparkingen in-esteld bin.
Bevestig dat dit de bedoeling is, da-j de gevolgen dervan begriepen en da-j dit doon in overeenstemming mit [[{{MediaWiki:Policy-url}}|'t beleid]] van disse wiki.",
'revdelete-suppress-text'        => "Onderdrokken ma-j '''allinnig''' gebruken in de volgende gevallen:
* Ongepassen persoonlijke infermasie
*: ''adressen en tillefoonnummers, burgerservicenummers, en gao zo mar deur.''",
'revdelete-legend'               => 'Stel versiebeparkingen in:',
'revdelete-hide-text'            => 'Verbarg de bewarken tekse',
'revdelete-hide-name'            => 'Verbarg logboekactie',
'revdelete-hide-comment'         => 'Verbarg bewarkingssamenvatting',
'revdelete-hide-user'            => 'Verbarg gebrukersnamen en IP-adressen van aandere luui.',
'revdelete-hide-restricted'      => 'Gegevens veur beheerders en aander volk onderdrokken',
'revdelete-suppress'             => 'Gegevens veur beheerders en aander volk onderdrokken',
'revdelete-hide-image'           => 'Verbarg bestaansinhoud',
'revdelete-unsuppress'           => 'Beparkingen veur weerummezetten versies vortdoon',
'revdelete-log'                  => 'Logopmarkingen:',
'revdelete-submit'               => 'De esillecteren versie toepassen',
'revdelete-logentry'             => 'zichbaorheid van bewarkingen is ewiezig veur [[$1]]',
'logdelete-logentry'             => 'wiezigen zichbaorheid van gebeurtenisse [[$1]]',
'revdelete-success'              => 'Zichbaorheid van de wieziging succesvol in-esteld.',
'logdelete-success'              => "'''Zichbaorheid van de gebeurtenisse is succesvol in-esteld.'''",
'revdel-restore'                 => 'Zichbaorheid wiezigen',
'pagehist'                       => 'Paginageschiedenisse',
'deletedhist'                    => 'Geschiedenisse dee vort-ehaold is',
'revdelete-content'              => 'inhoud',
'revdelete-summary'              => 'samenvatting bewarken',
'revdelete-uname'                => 'gebrukersnaam',
'revdelete-restricted'           => 'hef beparkingen an beheerders op-eleg',
'revdelete-unrestricted'         => 'hef beparkingen veur beheerders derof ehaold',
'revdelete-hid'                  => 'hef $1 verbörgen',
'revdelete-unhid'                => 'hef $1 zichbaor emaak',
'revdelete-log-message'          => '$1 veur $2 {{PLURAL:$2|versie|versies}}',
'logdelete-log-message'          => '$1 veur $2 {{PLURAL:$2|logboekregel|logboekregels}}',

# Suppression log
'suppressionlog'     => 'Verbargingslogboek',
'suppressionlogtext' => 'De onderstaande lieste bevat de pagina dee vort-edaon bin en blokkeringen dee veur beheerders verbörgen bin. In de [[Special:IPBlockList|IP-blokkeerlieste]] bin de blokkeringen dee noen van toepassing bin te bekieken.',

# History merging
'mergehistory'                     => "Geschiedenisse van pagina's bie mekaar doon",
'mergehistory-header'              => 'Via disse pagina ku-j versies uut de geschiedenisse van een bronpagina mit een niejere pagina samenvoegen. Zörg derveur dat disse versies uut de geschiedenisse historisch juustement bin.',
'mergehistory-box'                 => "Versies van twee pagina's samenvoegen:",
'mergehistory-from'                => 'Bronpagina:',
'mergehistory-into'                => 'Bestemmingspagina:',
'mergehistory-list'                => 'Samenvoegbaore bewarkingsgeschiedenisse',
'mergehistory-merge'               => "De volgende versies van [[:$1]] kunnen samen-evoeg wonnen naor [[:$2]]. Gebruuk de kelom mit keuzerondjes um allinnig de versies emaak op en veur de an-egeven tied samen te voegen. Let op dat 't gebruken van de navigasieverwiezingen disse kelom zal herinstellen.",
'mergehistory-go'                  => 'Samenvoegbaore bewarkingen bekieken',
'mergehistory-submit'              => 'Versies bie mekaar doon',
'mergehistory-empty'               => 'Der bin gien versies dee samen-evoeg kunnen wonnen.',
'mergehistory-success'             => '$3 {{PLURAL:$3|versie|versies}} van [[:$1]] bin succesvol samen-evoeg naor [[:$2]].',
'mergehistory-fail'                => 'Kan gien geschiedenisse samenvoegen, kiek opniej de pagina- en tiedparremeters nao.',
'mergehistory-no-source'           => 'Bronpagina $1 besteet neet.',
'mergehistory-no-destination'      => 'Bestemmingspagina $1 besteet neet.',
'mergehistory-invalid-source'      => 'De bronpagina mut een geldige titel ween.',
'mergehistory-invalid-destination' => 'De bestemmingspagina mut een geldige titel ween.',
'mergehistory-autocomment'         => '[[:$1]] samen-evoeg naor [[:$2]]',
'mergehistory-comment'             => '[[:$1]] samen-evoeg naor [[:$2]]: $3',
'mergehistory-same-destination'    => "De bronpagina en doelpagina kunnen neet 'tzelfde ween",
'mergehistory-reason'              => 'Rejen:',

# Merge log
'mergelog'           => 'Samenvoegingslogboek',
'pagemerge-logentry' => 'voegen [[$1]] naor [[$2]] samen (versies tot en mit $3)',
'revertmerge'        => 'Samenvoeging weerummedreien',
'mergelogpagetext'   => 'Hieronder zie-j een lieste van de leste samenvoegingen van een paginageschiedenisse naor een aandere.',

# Diffs
'history-title'           => 'Geschiedenisse van "$1"',
'difference'              => '(Verschil tussen bewarkingen)',
'lineno'                  => 'Regel $1:',
'compareselectedversions' => 'Vergeliek de ekeuzen versies',
'visualcomparison'        => 'Visuele vergelieking',
'wikicodecomparison'      => 'Vergelieking wikitekse',
'editundo'                => 'weerummedreien',
'diff-multi'              => '({{PLURAL:$1|1 tussenliggende versie|$1 tussenliggende versies}} wonnen neet weer-egeven.)',
'diff-movedto'            => 'herneumd naor $1',
'diff-styleadded'         => 'stiel $1 derbie edaon',
'diff-added'              => '$1 der edaon',
'diff-changedto'          => 'ewiezig in $1',
'diff-movedoutof'         => 'herneumd buten $1',
'diff-styleremoved'       => 'stiel $1 vort-edaon',
'diff-removed'            => '$1 vort-edaon',
'diff-changedfrom'        => 'ewiezig van $1',
'diff-src'                => 'bron',
'diff-withdestination'    => 'mit bestemming $1',
'diff-with'               => '&#32;mit $1 $2',
'diff-with-final'         => '&#32;en $1 $2',
'diff-width'              => 'breedte',
'diff-height'             => 'heugte',
'diff-p'                  => "een '''parregraaf'''",
'diff-blockquote'         => "een '''haakjen'''",
'diff-h1'                 => "een '''kopjen (nivo 1)'''",
'diff-h2'                 => "een '''kopjen (nivo 2)'''",
'diff-h3'                 => "een '''kopjen (nivo 3)'''",
'diff-h4'                 => "een '''kopjen (nivo 4)'''",
'diff-h5'                 => "een '''kopjen (nivo 5)'''",
'diff-pre'                => "een '''veur-eformeteren teksblokke'''",
'diff-div'                => "een '''deling'''",
'diff-ul'                 => "een '''lieste zonder nummers'''",
'diff-ol'                 => "een '''lieste mit nummers'''",
'diff-li'                 => "een '''liestenonderwarp'''",
'diff-table'              => "een '''tebel'''",
'diff-tbody'              => "'''tebelinhoud'''",
'diff-tr'                 => "een '''rie'''",
'diff-td'                 => "een '''cel'''",
'diff-th'                 => "een '''kelomkop'''",
'diff-br'                 => "een '''nieje regel'''",
'diff-hr'                 => "een '''horizontale liende'''",
'diff-code'               => "een '''teksblokke mit pregrammacode'''",
'diff-dl'                 => "een '''lieste van defenisies'''",
'diff-dt'                 => "een '''uutdrokking, dee edefenieerd wonnen'''",
'diff-dd'                 => "een '''defenisie'''",
'diff-input'              => "een '''formelierveld'''",
'diff-form'               => "een '''formelier'''",
'diff-img'                => "een '''ofbeelding'''",
'diff-span'               => "een '''span'''",
'diff-a'                  => "een '''verwiezing'''",
'diff-i'                  => "'''schunedrok'''",
'diff-b'                  => "'''vet-edrok'''",
'diff-strong'             => "'''stark'''",
'diff-em'                 => "'''naodrok'''",
'diff-font'               => "'''lettertype'''",
'diff-big'                => "'''groot'''",
'diff-del'                => "'''vort-edaon'''",
'diff-tt'                 => "'''vaste breedte'''",
'diff-sub'                => "'''lege tekse'''",
'diff-sup'                => "'''hoge tekse'''",
'diff-strike'             => "'''deurstrepen'''",

# Search results
'searchresults'                    => 'Zeukrisseltaoten',
'searchresults-title'              => 'Zeukrisseltaoten veur "$1"',
'searchresulttext'                 => "'''Opmarking:''' een pagina dee kortens an-emaak is ku-j meschien neet vienen via de zeukfunctie. 't Zeuken geet via een speciale zeukdatabanke dee ongeveer um de 30 tot 48 uur bie-ewörk wonnen.",
'searchsubtitle'                   => 'Je zochen naor \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|alle pagina\'s dee beginnen mit "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|alle pagina\'s dee verwiezen naor "$1"]])',
'searchsubtitleinvalid'            => 'Veur zeukopdrachte "$1"',
'noexactmatch'                     => "'''Der besteet gien artikel mit de naam $1.''' Je kunnen disse pagina [[:$1|anmaken]].",
'noexactmatch-nocreate'            => "'''Der besteet gien pagina mit de naam \"\$1\".'''",
'toomanymatches'                   => 'Der wanen te veule risseltaoten. Prebeer asjeblief een aandere zeukopdrachte.',
'titlematches'                     => 'Overeenkoms mit volgende namen',
'notitlematches'                   => 'Gien overeenstemming',
'textmatches'                      => 'Overeenkoms mit teksen',
'notextmatches'                    => 'Gien overeenstemming',
'prevn'                            => 'veurige $1',
'nextn'                            => 'volgende $1',
'prevn-title'                      => '{{PLURAL:$1|Veurig risseltaot|Veurige $1 risseltaoten}}',
'nextn-title'                      => '{{PLURAL:$1|Volgend risseltaot|Volgende $1 risseltaoten}}',
'shown-title'                      => '$1 {{PLURAL:$1|risseltaot|risseltaoten}} per pagina weergeven',
'viewprevnext'                     => '($1) ($2) ($3)',
'searchmenu-legend'                => 'Zeukopties',
'searchmenu-exists'                => "* Pagina '''[[$1]]'''",
'searchmenu-new'                   => "'''De pagina \"[[:\$1]]\" op disse wiki anmaken!'''",
'searchhelp-url'                   => 'Help:Inhold',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Paginanamen mit dit veurvoegsel laoten zien]]',
'searchprofile-articles'           => "Inhouwelijke pagina's",
'searchprofile-articles-and-proj'  => "Inhouwelijke en prejekpagina's",
'searchprofile-project'            => "Prejekpagina's",
'searchprofile-images'             => 'Bestanden',
'searchprofile-everything'         => 'Alles',
'searchprofile-advanced'           => 'Uut-ebreid',
'searchprofile-articles-tooltip'   => 'Zeuken in $1',
'searchprofile-project-tooltip'    => 'Zeuken in $1',
'searchprofile-images-tooltip'     => 'Zeuken naor bestanen',
'searchprofile-everything-tooltip' => "Alle inhoud deurzeuken (oek overlegpagina's)",
'searchprofile-advanced-tooltip'   => 'Zeuken in de an-egeven naamruumtes',
'prefs-search-nsdefault'           => 'Zeuken mit standardinstellingen:',
'prefs-search-nscustom'            => 'Zeuken in an-egeven naamruumtes:',
'search-result-size'               => '$1 ({{PLURAL:$2|1 woord|$2 woorden}})',
'search-result-score'              => 'Relevantie: $1%',
'search-redirect'                  => '(deurverwiezing $1)',
'search-section'                   => '(onderwarp $1)',
'search-suggest'                   => 'Bedoelen je: $1',
'search-interwiki-caption'         => 'Zusterprejekken',
'search-interwiki-default'         => '$1 risseltaoten:',
'search-interwiki-more'            => '(meer)',
'search-mwsuggest-enabled'         => 'mit anbevelingen',
'search-mwsuggest-disabled'        => 'gien anbevelingen',
'search-relatedarticle'            => 'Verwant',
'mwsuggest-disable'                => 'Anbevelingen via AJAX uutschakelen',
'searchrelated'                    => 'verwant',
'searchall'                        => 'alles',
'showingresults'                   => "Hieronder {{PLURAL:$1|steet '''1''' risseltaot|staon '''$1''' risseltaoten}}  <b>$1</b> vanof nummer <b>$2</b>.",
'showingresultsnum'                => "Hieronder {{PLURAL:$3|steet '''1''' risseltaot|staon '''$3''' risseltaoten}} vanof nummer '''$2'''.",
'showingresultstotal'              => "Hieronder {{PLURAL:$4|wordt et risseltaot '''$1''' van '''$3''' weer-egeven|wonnen de risseltaoten '''$1 tot $2''' van '''$3''' weer-egeven}}",
'nonefound'                        => "<strong>Let wel:</strong> standard wonnen neet alle naamruumtes deurzoch. A-j in zeukopdrach as veurvoegsel \"''all:'' gebruken wonnen alle pagina's deurzoch (oek overlegpagina's, mallen en gao zo mar deur). Je kunnen oek een naamruumte as veurvoegsel gebruken.",
'search-nonefound'                 => 'Der bin gien risseltaoten veur de zeukopdrach.',
'powersearch'                      => 'Zeuk',
'powersearch-legend'               => 'Uut-ebreid zeuken',
'powersearch-ns'                   => 'Zeuken in naamruumten:',
'powersearch-redir'                => 'Deurverwiezingen weergeven',
'powersearch-field'                => 'Zeuken naor',
'search-external'                  => 'Extern zeuken',
'searchdisabled'                   => 'Zeuken in {{SITENAME}} is neet meugelijk. Je kunnen gebruukmaken van Google. De gegevens over {{SITENAME}} bin meugelijk neet bie-ewörk.',

# Preferences page
'preferences'               => 'Veurkeuren',
'mypreferences'             => 'Mien veurkeuren',
'prefs-edits'               => 'Antal bewarkingen:',
'prefsnologin'              => 'Neet an-meld',
'prefsnologintext'          => 'Je mutten <span class="plainlinks">[{{fullurl:Special:UserLogin|returnto=$1}} an-emeld]</span> ween um joew veurkeuren in te kunnen stellen.',
'prefsreset'                => 'Standardveurkeuren hersteld.',
'qbsettings'                => 'Paginalieste',
'qbsettings-none'           => 'Gien',
'qbsettings-fixedleft'      => 'Links, vaste',
'qbsettings-fixedright'     => 'Rechs, vaste',
'qbsettings-floatingleft'   => 'Links, zweven',
'qbsettings-floatingright'  => 'Rechs, zweven',
'changepassword'            => 'Wachwoord wiezigen',
'skin'                      => '{{SITENAME}}-uterlijk',
'skin-preview'              => 'bekieken',
'math'                      => 'Wiskundige formules',
'dateformat'                => 'Daotumweergave',
'datedefault'               => 'Gien veurkeur',
'datetime'                  => 'Daotum en tied',
'math_failure'              => 'Wiskundige formule neet begriepelijk',
'math_unknown_error'        => 'Onbekende fout in formule',
'math_unknown_function'     => 'Onbekende functie in formule',
'math_lexing_error'         => 'Lexicografische fout in formule',
'math_syntax_error'         => 'Syntactische fout in formule',
'math_image_error'          => "'t Overzetten naor PNG is mislok.",
'math_bad_tmpdir'           => 'De map veur tiedelijke bestanen veur wiskundige formules besteet neet of is kan neet an-emaak wonnen.',
'math_bad_output'           => 'De map veur wiskundebestanen besteet neet of is neet an te maken.',
'math_notexvc'              => "Kan 't pregramma texvc neet vienen; configureer volgens de beschrieving in math/README.",
'prefs-personal'            => 'Gebrukersgegevens',
'prefs-rc'                  => 'Leste wiezigingen',
'prefs-watchlist'           => 'Volglieste',
'prefs-watchlist-days'      => 'Antal dagen weergeven:',
'prefs-watchlist-days-max'  => '(maximaal 7 dagen)',
'prefs-watchlist-edits'     => 'Antal wiezigingen in de uut-ebreien volglieste:',
'prefs-watchlist-edits-max' => '(maximale antal: 1.000)',
'prefs-misc'                => 'Overig',
'prefs-resetpass'           => 'Wachwoord wiezigen',
'saveprefs'                 => 'Veurkeuren opslaon',
'resetprefs'                => 'Standardveurkeuren herstellen',
'restoreprefs'              => 'Alle standardinstellingen weerummezetten',
'textboxsize'               => 'Bewarkingsveld',
'prefs-edit-boxsize'        => "Ofmetingen van 't bewarkingsvienster.",
'rows'                      => 'Regels',
'columns'                   => 'Kolommen',
'searchresultshead'         => 'Zeukrisseltaoten',
'resultsperpage'            => 'Antal zeukrisseltaoten per pagina',
'contextlines'              => 'Antal regels per evunnen pagina',
'contextchars'              => 'Antal tekens per pagina',
'stub-threshold'            => 'Verwiezingsformettering van <a href="#" class="stub">beginnetjes</a>:',
'recentchangesdays'         => 'Antal dagen dee eteund mutten wonnen in "leste wiezigingen":',
'recentchangesdays-max'     => '(maximaal $1 {{PLURAL:$1|dag|dagen}})',
'recentchangescount'        => "Antal wiezigingen in leste wiezigingen, geschiedenisse en logboekpagina's:",
'savedprefs'                => 'Veurkeuren bin op-esleugen.',
'timezonelegend'            => 'Tiedzone:',
'timezonetext'              => "Geef 't antal uren an, dee tussen joew tiedgebied en UTC liggen.",
'localtime'                 => 'Plaoselijke tied:',
'timezoneselect'            => 'Tiedzone:',
'timezoneuseserverdefault'  => 'Tied van de server gebruken',
'timezoneuseoffset'         => 'Aanders (tiedverschil angeven)',
'timezoneoffset'            => 'Tiedverschil¹:',
'servertime'                => 'Tied op de server:',
'guesstimezone'             => 'Vanuut webkieker toevoegen',
'timezoneregion-africa'     => 'Afrika',
'timezoneregion-america'    => 'Amerika',
'timezoneregion-antarctica' => 'Antarctica',
'timezoneregion-arctic'     => 'Arctis',
'timezoneregion-asia'       => 'Azië',
'timezoneregion-atlantic'   => 'Atlantische Oceaan',
'timezoneregion-australia'  => 'Australië',
'timezoneregion-europe'     => 'Europa',
'timezoneregion-indian'     => 'Indische Oceaan',
'timezoneregion-pacific'    => 'Stille Oceaan',
'allowemail'                => 'Berichen van aandere gebrukers toelaoten',
'prefs-searchoptions'       => 'Zeukinstellingen',
'prefs-namespaces'          => 'Naamruumtes',
'defaultns'                 => 'Naamruumtes um in te zeuken:',
'default'                   => 'standard',
'files'                     => 'Bestanden',
'prefs-custom-css'          => 'Persoonlijke CSS',
'prefs-custom-js'           => 'Persoonlijke JS',

# User rights
'userrights'                  => 'Gebrukersrechenbeheer', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'      => 'Beheer gebrukersgroepen',
'userrights-user-editname'    => 'Vul een gebrukersnaam in:',
'editusergroup'               => 'Bewark gebrukersgroepen',
'editinguser'                 => "Doonde mit 't wiezigen van de gebrukersrechen van '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Bewark gebrukersgroep',
'saveusergroups'              => 'Gebrukergroepen opslaon',
'userrights-groupsmember'     => 'Lid van:',
'userrights-groups-help'      => 'Je kunnen de groepen wiezigen waor as de gebruker lid van is.
* Een an-evink vakjen betekent dat de gebruker lid is van de groep.
* Een neet an-evink vakjen betekent dat de gebruker gien lid is van de groep.
* Een "*" betekent da-j een gebruker neet uut een groep vort kunnen haolen naoda-j-m deran toe-evoeg hemmen, of aandersumme.',
'userrights-reason'           => 'Rejen:',
'userrights-no-interwiki'     => "Je hemmen gien rechen um gebrukersrechen op aandere wiki's te wiezigen.",
'userrights-nodatabase'       => 'Databanke $1 besteet neet of is gien plaoselijke databanke.',
'userrights-nologin'          => 'Je mutten [[Special:UserLogin|an-emeld]] ween en as gebruker de juuste rechen hemmen um gebrukersrechen toe te kunnen wiezen.',
'userrights-notallowed'       => 'Je hemmen gien rechen um gebrukersrechen toe te kunnen wiezen.',
'userrights-changeable-col'   => 'Groepen dee-j beheren kunnen',
'userrights-unchangeable-col' => 'Groepen dee-j neet beheren kunnen',

# Groups
'group'               => 'Groep:',
'group-user'          => 'gebrukers',
'group-autoconfirmed' => 'an-emelde gebrukers',
'group-bot'           => 'bots',
'group-sysop'         => 'beheerders',
'group-bureaucrat'    => 'burocraoten',
'group-suppress'      => 'toezichhouwers',
'group-all'           => '(alles)',

'group-user-member'          => 'Gebruker',
'group-autoconfirmed-member' => 'An-emelde gebruker',
'group-bot-member'           => 'bot',
'group-sysop-member'         => 'beheerder',
'group-bureaucrat-member'    => 'burocraot',
'group-suppress-member'      => 'Toezichhouwer',

'grouppage-user'          => '{{ns:project}}:Gebrukers',
'grouppage-autoconfirmed' => '{{ns:project}}:An-emelde gebrukers',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Beheerder',
'grouppage-bureaucrat'    => '{{ns:project}}:Beheerder',
'grouppage-suppress'      => '{{ns:project}}:Toezichte',

# Rights
'right-read'                  => "Pagina's bekieken",
'right-edit'                  => "Pagina's bewarken",
'right-createpage'            => "Pagina's anmaken",
'right-createtalk'            => "Overlegpagina's anmaken",
'right-createaccount'         => 'Nieje gebrukers anmaken',
'right-minoredit'             => 'Bewarkingen markeren as klein',
'right-move'                  => "Pagina's herneumen",
'right-move-subpages'         => "Pagina's samen mit subpagina's verplaosen",
'right-move-rootuserpages'    => "Gebrukerspagina's van 't hoogste nivo herneumen",
'right-movefile'              => 'Bestanen herneumen',
'right-suppressredirect'      => 'Gien deurverwiezing anmaken op de ouwe naam as een pagina herneumd wonnen',
'right-upload'                => 'Bestanen toevoegen',
'right-reupload'              => 'Een bestaond bestaand overschrieven',
'right-reupload-own'          => 'Eigen toe-evoegen bestanen overschrieven',
'right-reupload-shared'       => 'Media uut de edelen mediadatabanke plaoselijk overschrieven',
'right-upload_by_url'         => 'Bestanen toevoegen via een verwiezing',
'right-purge'                 => 'De kas van een pagina legen',
'right-autoconfirmed'         => 'Behaandeld wonnen as een an-emelde gebruker',
'right-bot'                   => 'Behaandeld wonnen as een eautomatiseerd preces',
'right-nominornewtalk'        => "Kleine bewarkingen an een overlegpagina leien neet tot een melding 'nieje berichen'",
'right-apihighlimits'         => 'Hoge API-limieten gebruken',
'right-writeapi'              => 'Bewarken via de API',
'right-delete'                => "Pagina's vortdoon",
'right-bigdelete'             => "Pagina's mit een grote geschiedenisse vortdoon",
'right-deleterevision'        => "Versies van pagina's verbargen",
'right-deletedhistory'        => 'Vort-edaone versies bekieken, zonder te kunnen zien wat der vort-edaon is',
'right-browsearchive'         => "Vort-edaone pagina's bekieken",
'right-undelete'              => "Vort-edaone pagina's weerummeplaosen",
'right-suppressrevision'      => 'Verbörgen versies bekieken en weerummeplaosen',
'right-suppressionlog'        => 'Neet-peblieke logboeken bekieken',
'right-block'                 => 'Aandere gebrukers de meugelijkheid ontnemen um te bewarken',
'right-blockemail'            => "Een gebruker 't rech ontnemen um liendepos te versturen",
'right-hideuser'              => 'Een gebruker veur de overige gebrukers verbargen',
'right-ipblock-exempt'        => 'IP-blokkeringen ummezeilen',
'right-proxyunbannable'       => "Blokkeringen veur proxy's gellen neet",
'right-protect'               => "Beveiligingsnivo's wiezigen",
'right-editprotected'         => "Beveiligen pagina's bewarken",
'right-editinterface'         => "'t {{SITENAME}}-uterlijk bewarken",
'right-editusercssjs'         => 'De CSS- en JS-bestanen van aandere gebrukers bewarken',
'right-rollback'              => 'Gauw de leste bewarking(en) van een gebruker an een pagina weerummedreien',
'right-markbotedits'          => 'Weerummedreien bewarkingen markeren as botbewarkingen',
'right-noratelimit'           => 'Hef gien tiedsofhankelijke beparkingen',
'right-import'                => "Pagina's uut aandere wiki's invoeren",
'right-importupload'          => "Pagina's vanuut een bestaand invoeren",
'right-patrol'                => 'Bewarkingen as econtreleerd markeren',
'right-autopatrol'            => 'Bewarkingen wonnen autematisch as econtreleerd emarkeerd',
'right-patrolmarks'           => 'Controletekens in leste wiezigingen bekieken',
'right-unwatchedpages'        => "Bekiek een lieste mit pagina's dee neet op een volglieste staon",
'right-trackback'             => 'Een trackback opgeven',
'right-mergehistory'          => "De geschiedenisse van pagina's bie mekaar doon",
'right-userrights'            => 'Alle gebrukersrechen bewarken',
'right-userrights-interwiki'  => "Gebrukersrechen van gebrukers in aandere wiki's wiezigen",
'right-siteadmin'             => 'De databanke blokkeren en weer vriegeven',
'right-reset-passwords'       => 'Wachwoorden van aandere gebrukers opniej instellen',
'right-override-export-depth' => "Pagina's uutvoeren, oek de pagina's waor naor verwezen wonnen, tot een diepte van 5",

# User rights log
'rightslog'      => 'Gebrukersrechenlogboek',
'rightslogtext'  => 'Dit is een logboek mit veraanderingen van gebrukersrechen',
'rightslogentry' => 'Gebrukersrechen veur $1 ewiezig van $2 naor $3',
'rightsnone'     => '(gien)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'disse pagina lezen',
'action-edit'                 => 'disse pagina bewarken',
'action-createpage'           => "pagina's schrieven",
'action-createtalk'           => "overlegpagina's anmaken",
'action-createaccount'        => 'disse gebruker anmaken',
'action-minoredit'            => 'disse bewarking as klein markeren',
'action-move'                 => 'disse pagina herneumen',
'action-move-subpages'        => "disse pagina en de biebeheurende subpagina's herneumen",
'action-move-rootuserpages'   => "gebrukerspagina's van 't hoogste nivo herneumen",
'action-movefile'             => 'dit bestaand herneumen',
'action-upload'               => 'dit bestaand toevoegen',
'action-reupload'             => 'dit bestaonde bestaand overschrieven',
'action-reupload-shared'      => 'een aander bestaand over dit bestaand uut de edeelde mediadatabanke hinne zetten.',
'action-upload_by_url'        => 'dit bestaand vanof een webadres toevoegen',
'action-writeapi'             => 'de schrief-API bewarken',
'action-delete'               => 'disse pagina vortdoon',
'action-deleterevision'       => 'disse versie vortdoon',
'action-deletedhistory'       => 'de vort-edaone versies van disse pagina bekieken',
'action-browsearchive'        => "vort-edaone pagina's zeuken",
'action-undelete'             => 'disse pagina weerummeplaosen',
'action-suppressrevision'     => 'disse verbörgen versie bekieken en weerummeplaosen',
'action-suppressionlog'       => 'dit bescharmde logboek bekieken',
'action-block'                => 'disse gebruker blokkeren',
'action-protect'              => "'t beveiligingsnivo van disse pagina anpassen",
'action-import'               => 'disse pagina van een aandere wiki invoeren',
'action-importupload'         => 'disse pagina invoeren vanof een toe-evoeg bestaand',
'action-patrol'               => 'bewarkingen van aander volk as econtreleerd markeren',
'action-autopatrol'           => 'eigen bewarkingen as econtreleerd markeren',
'action-unwatchedpages'       => "bekiek de liest mit pagina's dee neet evolg wonnen",
'action-trackback'            => 'een trackback opgeven',
'action-mergehistory'         => 'de geschiedenisse van disse pagina samenvoegen',
'action-userrights'           => 'alle gebrukersrechen bewarken',
'action-userrights-interwiki' => "de rechen van gebrukers op aandere wiki's bewarken",
'action-siteadmin'            => 'de databanke blokkeren of vriegeven',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|wieziging|wiezigingen}}',
'recentchanges'                     => 'Leste wiezigingen',
'recentchanges-legend'              => 'Opties veur leste wiezigingen',
'recentchangestext'                 => 'Op disse pagina ku-j de leste wiezigingen van disse wiki bekieken.',
'recentchanges-feed-description'    => 'Zeuk naor de alderleste wiezingen op disse wiki in disse feed.',
'rcnote'                            => "Hieronder {{PLURAL:$1|steet de leste bewarking|staon de leste '''$1''' bewarkingen}} van de of-eleupen {{PLURAL:$2|dag|'''$2''' dagen}} (stand: $5, $4).",
'rcnotefrom'                        => 'Dit bin de wiezigingen sins <b>$2</b> (maximum van <b>$1</b> wiezigingen).',
'rclistfrom'                        => 'Teun wiezigingen vanof $1',
'rcshowhideminor'                   => '$1 kleine wiezigingen',
'rcshowhidebots'                    => '$1 botgebrukers',
'rcshowhideliu'                     => '$1 an-emelde gebrukers',
'rcshowhideanons'                   => '$1 annenieme gebrukers',
'rcshowhidepatr'                    => '$1 nao-ekeken bewarkingen',
'rcshowhidemine'                    => '$1 mien bewarkingen',
'rclinks'                           => 'Bekiek de leste $1 wiezigingen van de of-eleupen $2 dagen<br />$3',
'diff'                              => 'wiezig',
'hist'                              => 'gesch',
'hide'                              => 'verbarg',
'show'                              => 'teun',
'minoreditletter'                   => 'K',
'newpageletter'                     => 'N',
'boteditletter'                     => ' (bot)',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|keer|keer}} op een volglieste]',
'rc_categories'                     => 'Kattegeriebeparking (scheiden mit "|")',
'rc_categories_any'                 => 'alles',
'newsectionsummary'                 => 'Niej onderwarp: /* $1 */',
'rc-enhanced-expand'                => 'Details weergeven (hier he-j JavaScript veur neudig)',
'rc-enhanced-hide'                  => 'Details verbargen',

# Recent changes linked
'recentchangeslinked'          => 'Volg verwiezigingen',
'recentchangeslinked-title'    => 'Wiezigingen verwant an $1',
'recentchangeslinked-noresult' => 'Gien wiezigingen of pagina waornaor verwezen wonnen in disse periode.',
'recentchangeslinked-summary'  => "Op disse speciale pagina steet een lieste mit de leste wieziginen op pagina's waornaor verwezen wonnen. Pagina's op joew volglieste staon '''vet-edrok'''.",
'recentchangeslinked-page'     => 'Paginanaam:',
'recentchangeslinked-to'       => "Bekiek wiezigingen op pagina's mit verwiezingen naor disse pagina",

# Upload
'upload'                      => 'Bestaand toevoegen',
'uploadbtn'                   => 'Bestaand toevoegen',
'reupload'                    => 'Opniej toevoegen',
'reuploaddesc'                => 'Weerumme naor bestaandtoevoegingsformelier.',
'uploadnologin'               => 'Neet an-emeld',
'uploadnologintext'           => 'Je mutten [[Special:UserLogin|an-emeld]] ween um bestanen toe te kunnen voegen.',
'upload_directory_missing'    => 'De bestaandtoevoegingsmap ($1) ontbreek en kon neet an-emaak wonnen deur de webserver.',
'upload_directory_read_only'  => "Op 't mement ku-j gien bestanen toevoegen wegens technische rejens ($1).",
'uploaderror'                 => "Fout bie 't toevoegen van 't bestaand",
'uploadtext'                  => "Gebruuk 't onderstaonde formelier um bestanen toe te voegen.
Um eerder toe-evoegen bestanen te bekieken of te zeuken ku-j naor de [[Special:FileList|bestaanslieste]] gaon.
Toe-evoegen bestanen en media dee vort-edaon bin wonnen bie-ehuilen in 't [[Special:Log/upload|logboek mit toe-evoegen bestanen]] en 't [[Special:Log/delete|logboek mit vort-edaon bestanen]].

Um 't bestaand in te voegen in een pagina ku-j een van de volgende codes gebruken:
* '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Bestaand.jpg]]</nowiki>'''
* '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Bestaand.png|alternetieve tekse]]</nowiki>'''
* '''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Bestaand.ogg]]</nowiki>''' drekte verwiezing naor een bestaand.",
'upload-permitted'            => 'Toe-estaone bestaanstypes: $1.',
'upload-preferred'            => 'An-ewezen bestaanstypes: $1.',
'upload-prohibited'           => 'Verbeujen bestaanstypes: $1.',
'uploadlog'                   => 'Toe-evoegen bestanen',
'uploadlogpage'               => 'Toe-evoegen bestanen',
'uploadlogpagetext'           => 'Hieronder steet een lieste mit bestanen dee net niej bin.
Zie de [[Special:NewFiles|uutstalling mit media]] veur een overzichte.',
'filename'                    => 'Bestaansnaam',
'filedesc'                    => 'Beschrieving',
'fileuploadsummary'           => 'Beschrieving:',
'filereuploadsummary'         => 'Bestaanswiezigingen:',
'filestatus'                  => 'Auteursrechstaotus',
'filesource'                  => 'Bron',
'uploadedfiles'               => 'Toe-evoegen bestanen',
'ignorewarning'               => 'Negeer alle waorschuwingen',
'ignorewarnings'              => 'negeer waorschuwingen',
'minlength1'                  => 'Bestaansnamen mutten tenminsen één letter lang ween.',
'illegalfilename'             => 'De bestaansnaam "$1" bevat kerakters dee neet in namen van artikels veur maggen koemen. Geef \'t bestaand een aandere naam, en prebeer \'t dan opniej toe te voegen.',
'badfilename'                 => 'De naam van \'t bestaand is ewiezig naor "$1".',
'filetype-badmime'            => 'Bestanen mit \'t MIME-type "$1" maggen hier neet toe-evoeg wonnen.',
'filetype-bad-ie-mime'        => 'Dit bestaand kan neet toe-evoeg wonnen umdat Internet Explorer \'t zol herkennen as "$1", een neet toe-estaone bestaanstype dee schao an kan richen.',
'filetype-unwanted-type'      => "'''\".\$1\"''' is een ongewunst bestaanstype. An-ewezen {{PLURAL:\$3|bestaanstype is|bestaanstypes bin}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' is gien toe-estaone bestaanstype.
Toe-estaone {{PLURAL:\$3|bestaanstype is|bestaanstypes bin}} \$2.",
'filetype-missing'            => 'Dit bestaand hef gien extensie (bv. ".jpg").',
'large-file'                  => "'t Wonnen an-eraojen dat bestanen neet groter bin as $1, dit bestaand is $2.",
'largefileserver'             => "'t Bestaand is groter as dat de server toesteet.",
'emptyfile'                   => "'t Bestaand da-j toe-evoeg hemmen is leeg. Dit kan koemen deur een tikfout in de bestaansnaam. Kiek effen nao o-j dit bestaand wè bedoelen.",
'fileexists'                  => "Een ofbeelding mit disse naam besteet al; voeg 't bestaand onder een aandere naam toe. '''<tt>$1</tt>'''",
'filepageexists'              => "De beschrievingspagina veur dit bestaand bestung al op '''<tt>$1</tt>''', mar der besteet nog gien bestaand mit disse naam.
De samenvatting dee-j op-egeven hemmen zal neet op de beschrievingspagina koemen.
Bewark de pagina haandmaotig um joew beschrieving daor weer te geven.",
'fileexists-extension'        => "Een bestaand mit een soortgelieke naam besteet al:<br />
Naam van 't bestaand da-j toevoegen wollen: '''<tt>$1</tt>'''<br />
Naam van 't bestaonde bestaand: '''<tt>$2</tt>'''<br />
't Enigste verschil is de heufletters/kleine letters van de extensie. Kiek effen nao of de bestanen neet liekeleens bin.",
'fileexists-thumb'            => "'''<center>Bestaonde ofbeelding</center>'''",
'fileexists-thumbnail-yes'    => "Dit bestaand is een ofbeelding waovan de grootte verkleind is <i>(ofbeeldingsoverzichte)</i>. Kiek 't bestaand nao <strong><tt>$1</tt></strong>.<br />
As de ofbeelding dee-j krek nao-ekeken hemmen dezelfde grootte hef, dan is 't neet neudig um 't opniej toe te voegen.",
'file-thumbnail-no'           => "De bestaansnaam begint mit '''<tt>$1</tt>'''. 
Dit is werschienlijk een verkleinde ofbeelding ''(overzichsofbeelding)''.
A-j disse ofbeelding in volle grootte hemmen voeg 't dan toe, wiezig aanders de bestaansnaam.",
'fileexists-forbidden'        => "Een bestaand mit disse naam besteet al, en kan neet overschreven wonnen.
Voeg 't bestaand toe onder een aandere naam. 
[[File:$1|thumb|center|$1]]",
'fileexists-shared-forbidden' => "Der besteet al een bestaand mit disse naam in de gezamelijke bestaanslokasie.
A-j 't bestaand asnog toevoegen willen, gao dan weerumme en kies een aandere naam.
[[File:$1|thumb|center|$1]]",
'file-exists-duplicate'       => "Dit bestaand is liekeleens as {{PLURAL:$1|'t volgende bestaand|de volgende bestanen}}:",
'file-deleted-duplicate'      => "Een bestaand dat liekeleens is an dit bestaand ([[$1]]) is eerder al vort-edaon.
Raodpleeg 't logboek mit vort-edaone pagina's veurda-j veurdan gaon.",
'successfulupload'            => 'Bestaanstoevoeging was succesvol',
'uploadwarning'               => 'Waorschuwing',
'savefile'                    => 'Bestaand opslaon',
'uploadedimage'               => 'Toe-evoeg: [[$1]]',
'overwroteimage'              => 'Nieje versie van "[[$1]]" toe-evoeg',
'uploaddisabled'              => 'Bestanen toevoegen is neet meugelijk.',
'uploaddisabledtext'          => 'Bestaanstoevoegingen bin uut-eschakeld.',
'php-uploaddisabledtext'      => "'t Toevoegen van PHP-bestanen is uut-eschakeld. Kiek de instellingen veur 't toevoegen van bestanen effen nao.",
'uploadscripted'              => 'Dit bestaand bevat een HTML- of scriptcode dat verkeerd deur je webkieker weer-egeven kan wonnen.',
'uploadcorrupt'               => "'t Bestaand is korrup of hef een verkeerde extensie. 
Kiek 't bestaand nao en voeg 't bestaand opniej toe.",
'uploadvirus'                 => "'t Bestaand bevat een virus! Gegevens: $1",
'sourcefilename'              => 'Bestaansnaam op de hardeschieve:',
'destfilename'                => 'Opslaon as (optioneel)',
'upload-maxfilesize'          => 'Maximale bestaansgrootte: $1',
'watchthisupload'             => 'Volg disse pagina',
'filewasdeleted'              => "Een bestaand mit disse naam is al eerder vort-edaon. Kiek 't $1 nao veurda-j 't opniej toevoegen.",
'upload-wasdeleted'           => "'''Waorschuwing: je bin een bestaand an 't toevoegen dee eerder al vort-edaon is.'''

Bedenk eers of 't inderdaod de bedoeling is dat dit bestaand toe-evoeg wonnen.
't Logboek mit vort-edaone pagina's ku-j hier vienen:",
'filename-bad-prefix'         => "De naam van 't bestaand da-j toevoegen, begint mit '''\"\$1\"''', dit is een neet-beschrievende naam dee meestentieds autematisch deur een digitale camera egeven wonnen. Kies een dudelijke naam veur 't bestaand.",

'upload-proto-error'      => 'Verkeerde protocol',
'upload-proto-error-text' => 'Um op disse meniere bestanen toe te voegen mutten webadressen beginnen mit <code>http://</code> of <code>ftp://</code>.',
'upload-file-error'       => 'Interne fout',
'upload-file-error-text'  => 'Bie ons gung der effen wat fout to een tiedelijk bestaand op de server an-emaak wönnen. Neem kontak op mit een [[Special:ListUsers/sysop|systeembeheerder]].',
'upload-misc-error'       => "Onbekende fout bie 't toevoegen van joew bestaand",
'upload-misc-error-text'  => "Der is bie 't toevoegen van 't bestaand een onbekende fout op-etrejen. Kiek effen nao of de verwiezing 't wel dut en prebeer 't opniej. As 't prebleem anhuilt, neem dan kontak op mit één van de systeembeheerders.",

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Kon webadres neet bereiken',
'upload-curl-error6-text'  => "'t Webadres kon neet bereik wonnen. Kiek effen nao of je 't goeie adres in-evoerd hemmen en of de webstee bereikbaor is.",
'upload-curl-error28'      => "Tiedsoverschriejing bie 't toevoegen van 't bestaand",
'upload-curl-error28-text' => "'t Duren te lange veurdat de webstee reageren. Kiek effen nao of de webstee bereikbaor is, wach effen en prebeer 't daornao weer. Prebeer 't aanders as 't wat rustiger is.",

'license'            => 'Licentie',
'nolicense'          => 'Gien licentie ekeuzen',
'license-nopreview'  => '(Naokieken is neet meugelijk)',
'upload_source_url'  => ' (een geldig, pebliek toegankelijk webadres)',
'upload_source_file' => ' (een bestaand op de hardeschieve)',

# Special:ListFiles
'listfiles-summary'     => 'Op disse speciale pagina ku-j alle toe-evoegen bestanen bekieken.
Standard wonnen de les toe-evoegen bestanen bovenan de lieste ezet.
Klikken op een kelomkop veraandert de sortering.',
'listfiles_search_for'  => 'Zeuk op ofbeeldingnaam:',
'imgfile'               => 'bestaand',
'listfiles'             => 'Ofbeeldingenlieste',
'listfiles_date'        => 'Daotum',
'listfiles_name'        => 'Naam',
'listfiles_user'        => 'Gebruker',
'listfiles_size'        => 'Grootte (bytes)',
'listfiles_description' => 'Beschrieving',
'listfiles_count'       => 'Versies',

# File description page
'filehist'                  => 'Bestaansgeschiedenisse',
'filehist-help'             => "Klik op een daotum/tied um 't bestaand te zien zoas 't to was.",
'filehist-deleteall'        => 'alles vortdoon',
'filehist-deleteone'        => 'disse vortdoon',
'filehist-revert'           => 'weerummedreien',
'filehist-current'          => "zoas 't noen is",
'filehist-datetime'         => 'Daotum/tied',
'filehist-thumb'            => 'Ofbeeldingsoverzichte',
'filehist-thumbtext'        => 'Ofbeeldingsoverzichte veur versie van $1',
'filehist-nothumb'          => 'Gien ofbeeldingsoverzichte',
'filehist-user'             => 'Gebruker',
'filehist-dimensions'       => 'Ofmetingen',
'filehist-filesize'         => 'Bestaansgrootte',
'filehist-comment'          => 'Opmarkingen',
'imagelinks'                => 'Verwiezingen naor dit bestaand',
'linkstoimage'              => "Disse ofbeelding wonnen gebruuk op de volgende {{PLURAL:$1|pagina|$1 pagina's}}:",
'linkstoimage-more'         => 'Der {{PLURAL:$2|is|bin}} meer as $1 {{PLURAL:$1|verwiezing|verwiezingen}} naor dit bestaand.
De volgende lieste geef allinnig de eerste {{PLURAL:$1|verwiezing|$1 verwiezingen}} naor dit bestaand weer.
De [[Special:WhatLinksHere/$2|hele lieste]] is oek beschikbaor.',
'nolinkstoimage'            => 'Ofbeelding is neet in gebruuk.',
'morelinkstoimage'          => '[[Special:WhatLinksHere/$1|Meer verwiezingen]] naor dit bestaand bekieken.',
'redirectstofile'           => "{{PLURAL:$1|'t Volgende bestaand verwies|De volgende $1 bestanen verwiezen}} deur naor dit bestaand:",
'duplicatesoffile'          => "{{PLURAL:$1|'t Volgende bestaand is|De volgende $1 bestanen bin}} liekeleens as dit bestaand ([[Special:FileDuplicateSearch/$2|meer infermasie]]):",
'sharedupload'              => 'Dit is een edeeld bestaand op $1 en ku-j oek gebruken veur aandere prejekken.', # $1 is the repo name, $2 is shareduploadwiki(-desc)
'shareduploadwiki'          => 'Zie $1 veur veerdere infermasie.',
'shareduploadwiki-desc'     => 'De beschrieving van de $1 vie-j hieronder.',
'shareduploadwiki-linktext' => 'bestandbeschrievingspagina',
'noimage'                   => "Der besteet gien bestand mit disse naam, je kunnen 't $1.",
'noimage-linktext'          => 'toevoegen',
'uploadnewversion-linktext' => 'Een niejere versie van dit bestaand toevoegen.',
'shared-repo-from'          => 'uut $1', # $1 is the repository name
'shared-repo'               => 'een edeelde mediadatabanke', # used when shared-repo-NAME does not exist

# File reversion
'filerevert'                => '$1 weerummedreien',
'filerevert-legend'         => 'Bestaand weerummezetten',
'filerevert-intro'          => "Je bin '''[[Media:$1|$1]]''' an 't weerummedreien tot de [$4 versie van $2, $3]",
'filerevert-comment'        => 'Opmarkingen:',
'filerevert-defaultcomment' => 'Weerummedreid tot de versie van $1, $2',
'filerevert-submit'         => 'Weerummedreien',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' is weerummedreid naor de [$4 versie op $2, $3]</span>.',
'filerevert-badversion'     => 'Der is gien veurige lokale versie van dit bestaand mit de op-egeven tied.',

# File deletion
'filedelete'                  => '$1 vortdoon',
'filedelete-legend'           => 'Bestaand vortdoon',
'filedelete-intro'            => "Je doon 't bestaand '''[[Media:$1|$1]]''' noen vort samen mit de geschiedenisse dervan.",
'filedelete-intro-old'        => "Je bin de versie van '''[[Media:$1|$1]]''' van [$4 $3, $2] vort an 't doon.",
'filedelete-comment'          => 'Opmarking:',
'filedelete-submit'           => 'Vortdoon',
'filedelete-success'          => "'''$1''' is vort-edaon.",
'filedelete-success-old'      => "De versie van '''[[Media:$1|$1]]''' van $3, $2 is vort-edaon.",
'filedelete-nofile'           => "'''$1''' besteet neet.",
'filedelete-nofile-old'       => "Der is gien versie van '''$1''' in 't archief mit de an-egeven eigenschappen.",
'filedelete-otherreason'      => 'Aandere rejen:',
'filedelete-reason-otherlist' => 'Aandere rejen',
'filedelete-reason-dropdown'  => '*Veulveurkoemende rejens
** Auteursrechenschending
** Dit bestaand he-w dubbel',
'filedelete-edit-reasonlist'  => "Rejen veur 't vortdoon van de bewarken",

# MIME search
'mimesearch'         => 'Zeuken op MIME-type',
'mimesearch-summary' => "Op disse speciale pagina kunnen de bestanen naor 't MIME-type efiltreerd wonnen. De invoer mut altied 't media- en subtype bevatten, bieveurbeeld: <tt>ofbeelding/jpeg</tt>.",
'mimetype'           => 'MIME-type:',
'download'           => 'oflaojen',

# Unwatched pages
'unwatchedpages' => "Pagina's dee neet evolg wonnen",

# List redirects
'listredirects' => 'Lieste van deurverwiezingen',

# Unused templates
'unusedtemplates'     => 'Ongebruken mallen',
'unusedtemplatestext' => "Hieronder staon alle pagina's in de naamruumte {{ns:template}} dee op gien enkele pagina gebruuk wonnen.
Vergeet neet de verwiezingen nao te kieken veurda-j de mal vortdoon.",
'unusedtemplateswlh'  => 'aandere verwiezingen',

# Random page
'randompage'         => 'Willekeurig artikel',
'randompage-nopages' => 'Der staon gien pagina\'s in de naamruumte "$1".',

# Random redirect
'randomredirect'         => 'Willekeurige deurverwiezing',
'randomredirect-nopages' => 'Der staon gien deurverwiezingen in de naamruumte "$1".',

# Statistics
'statistics'                   => 'Staotestieken',
'statistics-header-pages'      => 'Paginastaotestieken',
'statistics-header-edits'      => 'Bewarkingsstaotestieken',
'statistics-header-views'      => 'Staotestieken bekieken',
'statistics-header-users'      => 'Gebrukerstaotestieken',
'statistics-articles'          => "Inhouwelijke pagina's",
'statistics-pages'             => "Pagina's",
'statistics-pages-desc'        => "Alle pagina's in de wiki, oek overlegpagina's, deurverwiezingen, en gao zo mar deur.",
'statistics-files'             => 'Bestanen',
'statistics-edits'             => "Paginabewarkingen vanof 't begin van {{SITENAME}}",
'statistics-edits-average'     => 'Gemiddeld antal bewarkingen per pagina',
'statistics-views-total'       => "Totaal antal weer-egeven pagina's",
'statistics-views-peredit'     => "Weer-egeven pagina's per bewarking",
'statistics-jobqueue'          => '[http://www.mediawiki.org/wiki/Manual:Job_queue Jobqueuelengte]',
'statistics-users'             => 'In-eschreven [[Special:ListUsers|gebrukers]]',
'statistics-users-active'      => 'Actieve gebrukers',
'statistics-users-active-desc' => 'Gebrukers dee de veurbieje {{PLURAL:$1|dag|$1 dagen}} een haandeling uut-evoerd hemmen',
'statistics-mostpopular'       => "Meestbekeken pagina's",

'disambiguations'      => "Deurverwiespagina's",
'disambiguationspage'  => 'Template:Dv',
'disambiguations-text' => "De onderstaonde pagina's verwiezen naor een '''deurverwiespagina'''. Disse verwiezingen mutten eigenlijks rechstreeks verwiezen naor 't juuste onderwarp.

Pagina's wonnen ezien as een deurverwiespagina, as de mal gebruuk wonnen dee vermeld steet op [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Dubbele deurverwiezingen',
'doubleredirectstext'        => "Op elke regel steet de eerste deurstuurpagina, de tweede deurstuurpagina en de eerste regel van de tweede deurverwiezing. Meestentieds is leste pagina 't eigenlijke doel.",
'double-redirect-fixed-move' => '[[$1]] is herneumd en is noen een deurverwiezing naor [[$2]]',
'double-redirect-fixer'      => 'Deurverwiezingsverbeteraar',

'brokenredirects'        => 'Doodlopende deurverwiezingen',
'brokenredirectstext'    => 'Disse deurverwiezingen verwiezen naor een neet-bestaonde pagina.',
'brokenredirects-edit'   => '(bewark)',
'brokenredirects-delete' => '(vortdoon)',

'withoutinterwiki'         => "Pagina's zonder verwiezingen naor aandere talen",
'withoutinterwiki-summary' => "De volgende pagina's verwiezen neet naor versies in een aandere taal.",
'withoutinterwiki-legend'  => 'Veurvoegsel',
'withoutinterwiki-submit'  => 'Bekieken',

'fewestrevisions' => 'Artikels mit de minste bewarkingen',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|kattegerie|kattegerieën}}',
'nlinks'                  => '$1 {{PLURAL:$1|verwiezing|verwiezingen}}',
'nmembers'                => '$1 {{PLURAL:$1|onderwarp|onderwarpen}}',
'nrevisions'              => '$1 {{PLURAL:$1|versie|versies}}',
'nviews'                  => '{{PLURAL:$1|1 keer|$1 keer}} bekeken',
'specialpage-empty'       => 'Disse pagina is leeg.',
'lonelypages'             => "Weespagina's",
'lonelypagestext'         => "Naor disse pagina's wonnen neet verwezen vanuut {{SITENAME}} en ze bin oek nargens in-evoeg.",
'uncategorizedpages'      => "Pagina's zonder kattegerie",
'uncategorizedcategories' => 'Kattegerieën zonder kattegerie',
'uncategorizedimages'     => 'Ofbeeldingen zonder kattegerie',
'uncategorizedtemplates'  => 'Mallen zonder kattegerie',
'unusedcategories'        => 'Ongebruken kattegerieën',
'unusedimages'            => 'Ongebruken ofbeeldingen',
'popularpages'            => 'Populaire artikels',
'wantedcategories'        => 'Gewunste kattegerieën',
'wantedpages'             => "Gewunste pagina's",
'wantedpages-badtitle'    => 'Ongeldige paginanaam in risseltaot: $1',
'wantedfiles'             => 'Gewunste bestanen',
'wantedtemplates'         => 'Gewunste mallen',
'mostlinked'              => "Pagina's waor 't meest naor verwezen wonnen",
'mostlinkedcategories'    => 'Meestgebruken kattegerieën',
'mostlinkedtemplates'     => "Mallen dee 't meest gebruuk wonnen",
'mostcategories'          => 'Artikels mit de meeste kattegerieën',
'mostimages'              => 'Meestgebruken ofbeeldingen',
'mostrevisions'           => 'Artikels mit de meeste bewarkingen',
'prefixindex'             => "Alle pagina's op veurvoegsel",
'shortpages'              => 'Korte artikels',
'longpages'               => 'Lange artikels',
'deadendpages'            => "Pagina's zonder verwiezingen",
'deadendpagestext'        => "De onderstaonde pagina's verwiezen neet naor aandere pagina's in disse wiki.",
'protectedpages'          => "Pagina's dee beveilig bin",
'protectedpages-indef'    => 'Allinnig blokkeringen zonder verloopdaotum',
'protectedpages-cascade'  => 'Allinnig beveiligingen mit de cascade-optie',
'protectedpagestext'      => "De volgende pagina's bin beveilig en kunnen neet herneumd of bewark wonnen.",
'protectedpagesempty'     => "Der bin op 't mement gien beveiligen pagina's",
'protectedtitles'         => 'Beveiligen titels',
'protectedtitlestext'     => "De volgende pagina's bin beveilig zoda-ze neet opniej an-emaak kunnen wonnen",
'protectedtitlesempty'    => 'Der bin noen gien titels beveilig dee an disse parremeters voldoon.',
'listusers'               => 'Gebrukerslieste',
'listusers-editsonly'     => 'Allinnig gebrukers mit bewarkingen weergeven',
'listusers-creationsort'  => 'Sorteren op inschriefdaotum',
'usereditcount'           => '$1 {{PLURAL:$1|bewarking|bewarkingen}}',
'usercreated'             => 'An-emaak op $1 um $2',
'newpages'                => 'Nieje artikels',
'newpages-username'       => 'Gebrukersnaam:',
'ancientpages'            => 'Artikels dee lange neet bewörk bin',
'move'                    => 'Herneumen',
'movethispage'            => 'Herneum',
'unusedimagestext'        => "Vergeet neet dat aandere wiki's meschien oek enkele van disse ofbeeldingen gebruken.",
'unusedcategoriestext'    => 'De onderstaonde kattegerieën bin an-emaak mar wonnen neet gebruuk.',
'notargettitle'           => 'Gien pagina op-egeven',
'notargettext'            => 'Je hemmen neet op-egeven veur welke pagina je disse functie bekieken willen.',
'nopagetitle'             => 'Doelpagina besteet neet',
'nopagetext'              => 'De pagina dee-j herneumen willen besteet neet.',
'pager-newer-n'           => '{{PLURAL:$1|1 niejere|$1 niejere}}',
'pager-older-n'           => '{{PLURAL:$1|1 ouwere|$1 ouwere}}',
'suppress'                => 'Toezichte',

# Book sources
'booksources'               => 'Boekinfermasie',
'booksources-search-legend' => 'Zeuk infermasie over een boek',
'booksources-go'            => 'Zeuk',
'booksources-text'          => "Hieronder steet een lieste mit verwiezingen naor aandere websteeën dee nieje of gebruken boeken verkopen, en hemmen meschien meer infermasie over 't boek da-j zeuken:",
'booksources-invalid-isbn'  => 'De op-egeven ISBN klop neet; kiek effen nao o-j gien fout emaak hemmen bie de invoer.',

# Special:Log
'specialloguserlabel'  => 'Gebruker:',
'speciallogtitlelabel' => 'Naam:',
'log'                  => 'Logboeken',
'all-logs-page'        => 'Alle logboeken',
'alllogstext'          => "Dit is 't combinasielogboek van {{SITENAME}}. 
Je kunnen oek kiezen veur bepaolde logboeken en filteren op gebruker (heuflettergeveulig) en titel (heuflettergeveulig).",
'logempty'             => "Der steet gien infermasie in 't logboek dee voldut an disse criteria.",
'log-title-wildcard'   => 'Zeuk naor titels dee beginnen mit disse tekse:',

# Special:AllPages
'allpages'          => "Alle pagina's",
'alphaindexline'    => '$1 tot $2',
'nextpage'          => 'Volgende pagina ($1)',
'prevpage'          => 'Veurige pagina ($1)',
'allpagesfrom'      => "Teun pagina's vanof:",
'allpagesto'        => "Pagina's bekieken tot:",
'allarticles'       => 'Alle artikels',
'allinnamespace'    => "Alle pagina's (naamruumte $1)",
'allnotinnamespace' => "Alle pagina's (neet in naamruumte $1)",
'allpagesprev'      => 'veurige',
'allpagesnext'      => 'volgende',
'allpagessubmit'    => 'Zeuk',
'allpagesprefix'    => "Teun pagina's dee beginnen mit:",
'allpagesbadtitle'  => 'De op-egeven paginanaam is ongeldig of bevatten een interwikiveurvoegsel. Meugelijkerwieze bevatten de naam kerakters dee neet gebruuk maggen wonnen in paginanamen.',
'allpages-bad-ns'   => '{{SITENAME}} hef gien "$1"-naamruumte.',

# Special:Categories
'categories'                    => 'Kattegerieën',
'categoriespagetext'            => "De volgende kattegerieën bin anwezig in {{SITENAME}}.

De volgende kattegerieën bevatten pagina's of media.
[[Special:UnusedCategories|ongebruken kattegerieën]] zie-j hier neet.
Zie oek [[Special:WantedCategories|gewunste kattegerieën]].",
'categoriesfrom'                => 'Kattegerieën weergeven vanof:',
'special-categories-sort-count' => 'op antal sorteren',
'special-categories-sort-abc'   => 'alfebetisch sorteren',

# Special:DeletedContributions
'deletedcontributions'       => 'Vort-edaone gebrukersbiedragen',
'deletedcontributions-title' => 'Vort-edaone gebrukersbiedragen',

# Special:LinkSearch
'linksearch'       => 'Uutgaonde verwiezingen',
'linksearch-pat'   => 'Zeukpetroon:',
'linksearch-ns'    => 'Naamruumte:',
'linksearch-ok'    => 'Zeuken',
'linksearch-text'  => 'Wildcards zoas "*.wikipedia.org" of "*.org" bin toe-estaon.<br />
Ondersteunde protecollen: <tt>$1</tt>',
'linksearch-line'  => '$1 hef een verwiezing in $2',
'linksearch-error' => "Wildcards bin allinnig toe-estaon an 't begin van een webadres.",

# Special:ListUsers
'listusersfrom'      => 'Teun vanof:',
'listusers-submit'   => 'Teun',
'listusers-noresult' => 'Gien gebrukers evunnen. Zeuk oek naor varianten mit kleine letters of heufletters.',

# Special:Log/newusers
'newuserlogpage'              => 'Logboek nieje gebrukers',
'newuserlogpagetext'          => 'Hieronder staon de niej in-eschreven gebrukers',
'newuserlog-byemail'          => 'wachwoord is verzunnen via de liendepos',
'newuserlog-create-entry'     => 'Nieje gebruker',
'newuserlog-create2-entry'    => 'hef nieje gebruker $1 eregistreerd',
'newuserlog-autocreate-entry' => 'Gebruker autematisch an-emaak',

# Special:ListGroupRights
'listgrouprights'                 => 'Rechen van gebrukersgroepen',
'listgrouprights-summary'         => 'Op disse pagina staon de gebrukersgroepen van disse wiki beschreven, mit de biebeheurende rechen.
Meer infermasie over de rechen ku-j [[{{MediaWiki:Listgrouprights-helppage}}|hier vienen]].',
'listgrouprights-group'           => 'Groep',
'listgrouprights-rights'          => 'Rechen',
'listgrouprights-helppage'        => 'Help:Gebrukersrechen',
'listgrouprights-members'         => '(lejenlieste)',
'listgrouprights-addgroup'        => 'Kan gebrukers bie disse {{PLURAL:$2|groep|groepen}} zetten: $1',
'listgrouprights-removegroup'     => 'Kan gebrukers uut disse {{PLURAL:$2|groep|groepen}} haolen: $1',
'listgrouprights-addgroup-all'    => 'Kan gebrukers bie alle groepen zetten',
'listgrouprights-removegroup-all' => 'Kan gebrukers uut alle groepen haolen',

# E-mail user
'mailnologin'      => 'Neet an-emeld.',
'mailnologintext'  => 'Je mutten [[Special:UserLogin|an-emeld]] ween en een geldig e-mailadres in "[[Special:Preferences|mien veurkeuren]]" invoeren um disse functie te kunnen gebruken.',
'emailuser'        => 'Een berich sturen',
'emailpage'        => 'Gebruker een berich sturen',
'emailpagetext'    => "Deur middel van dit formelier ku-j een berich sturen naor disse gebruker. 
't Adres da-j op-egeven hemmen bie [[Special:Preferences|joew veurkeuren]] zal as ofzender gebruuk wonnen.
De ontvanger kan dus drek beantwoorden.",
'usermailererror'  => "Foutmelding bie 't versturen:",
'defemailsubject'  => 'Berich van {{SITENAME}}',
'noemailtitle'     => 'Gebruker hef gien netposadres op-egeven',
'noemailtext'      => 'Disse gebruker hef gien geldig e-mailadres in-evoerd.',
'nowikiemailtitle' => 'Netpos is neet toe-estaon',
'nowikiemailtext'  => 'Disse gebruker wil gien netpos toe-estuurd kriegen van aandere gebrukers.',
'email-legend'     => 'Een berich sturen naor een aandere gebruker van {{SITENAME}}',
'emailfrom'        => 'Van:',
'emailto'          => 'An:',
'emailsubject'     => 'Onderwarp:',
'emailmessage'     => 'Berich:',
'emailsend'        => 'Versturen',
'emailccme'        => 'Stuur mien een kopie van dit berich.',
'emailccsubject'   => 'Kopie van joew berich an $1: $2',
'emailsent'        => 'Berich verstuurd',
'emailsenttext'    => 'Berich is verzunnen.',
'emailuserfooter'  => 'Dit berich is verstuurd deur $1 an $2 deur de functie "Een berich sturen" van {{SITENAME}} te gebruken.',

# Watchlist
'watchlist'            => 'Volglieste',
'mywatchlist'          => 'Mien volglieste',
'watchlistfor'         => "(veur '''$1''')",
'nowatchlist'          => 'Gien artikels in volglieste.',
'watchlistanontext'    => '$1 is verplich um joew volglieste te bekieken of te wiezigen.',
'watchnologin'         => 'Neet an-emeld',
'watchnologintext'     => 'Um je volglieste an te passen mu-j eers [[Special:UserLogin|an-emeld]] ween.',
'addedwatch'           => 'Disse pagina steet noen op joew volglieste',
'addedwatchtext'       => "De pagina \"[[:\$1]]\" steet noen op joew [[Special:Watchlist|volglieste]].
Toekomstige wiezigingen op disse pagina en de overlegpagina zullen hier vermeld wonnen, oek zullen disse pagina's '''vet-edrok''' ween in de lieste mit de [[Special:RecentChanges|leste wiezigingen]] zoda-j 't makkelijker zien kunnen.",
'removedwatch'         => 'Van volglieste ofhaolen',
'removedwatchtext'     => 'De pagina "$1" is van joew volglieste op-ehaold.',
'watch'                => 'Volgen',
'watchthispage'        => 'Volg disse pagina',
'unwatch'              => 'Neet volgen',
'unwatchthispage'      => 'Neet volgen',
'notanarticle'         => 'Gien artikel',
'notvisiblerev'        => 'Bewarking is vort-edaon',
'watchnochange'        => "Gien van de pagina's op joew volglieste is in disse periode ewiezig.",
'watchlist-details'    => "Der {{PLURAL:$1|steet één pagina|staon $1 pagina's}} op joew volglieste, zonder de overlegpagina's mee-erekend.",
'wlheader-enotif'      => 'Je kriegen berich per netpos',
'wlheader-showupdated' => "* Pagina's dee ewiezig sinds je ze 't veur 't les bie-ewark hemmen, wonnen '''vet''' weer-egeven.",
'watchmethod-recent'   => "Bie de pagina's dee kortens ewiezig bin, ezoch naor pagina's dee evolg wonnen",
'watchmethod-list'     => 'Kik joew nao volglieste veur de leste wiezigingen',
'watchlistcontains'    => "Der {{PLURAL:$1|steet 1 pagina|staon $1 pagina's}} op joew volglieste.",
'iteminvalidname'      => "Verkeerde naam '$1'",
'wlnote'               => "Hieronder {{PLURAL:$1|steet de leste wieziging|staon de leste $1 wiezigingen}} in {{PLURAL:$2|'t of-eleupen ure|de leste $2 uren}}.",
'wlshowlast'           => 'Teun de leste $1 ure $2 dagen $3',
'watchlist-options'    => 'Opties veur de volglieste',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Volg...',
'unwatching' => 'Neet volgen...',

'enotif_mailer'                => '{{SITENAME}}-berichgevingssysteem',
'enotif_reset'                 => "Markeer alle pagina's as bezoch.",
'enotif_newpagetext'           => 'Dit is een nieje pagina.',
'enotif_impersonal_salutation' => '{{SITENAME}}-gebruker',
'changed'                      => 'ewiezig',
'created'                      => 'an-emaak',
'enotif_subject'               => '{{SITENAME}}-pagina $PAGETITLE is $CHANGEDORCREATED deur $PAGEEDITOR',
'enotif_lastvisited'           => 'Zie $1 veur alle wiezigingen sinds joew leste bezeuk.',
'enotif_lastdiff'              => 'Zie $1 um disse wieziging te bekieken.',
'enotif_anon_editor'           => 'annenieme gebruker $1',
'enotif_body'                  => 'Beste $WATCHINGUSERNAME,

De pagina $PAGETITLE op {{SITENAME}} is $CHANGEDORCREATED op $PAGEEDITDATE deur $PAGEEDITOR, zie $PAGETITLE_URL veur de leste versie.

$NEWPAGE

Samenvatting van de wieziging: $PAGESUMMARY $PAGEMINOREDIT

Kontakgevevens van de auteur:
Netpos: $PAGEEDITOR_EMAIL
Wiki: $PAGEEDITOR_WIKI

Je kriegen veerder gien berichen, behalve a-j disse pagina bezeuken. Op joew volglieste ku-j veur alle pagina\'s dee-j volgen de waorschuwingsinstellingen derof haolen.

             Groeten van \'t {{SITENAME}}-waorschuwingssysteem.

--
Je kunnen de instellingen van joew volglieste wiezigen op:
{{fullurl:Special:Watchlist/edit}}

Opmarkingen en veerdere hulpe:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Vortdoon',
'confirm'                => 'Bevestigen',
'excontent'              => "De tekse was: '$1'",
'excontentauthor'        => "De tekse was: '$1' (pagina an-emaak deur: [[Special:Contributions/$2|$2]])",
'exbeforeblank'          => "veurdat disse pagina leeg-emaak wönnen stung hier: '$1'",
'exblank'                => 'Pagina was leeg',
'delete-confirm'         => '"$1" vortdoon',
'delete-legend'          => 'Vortdoon',
'historywarning'         => 'Waorschuwing: disse pagina hef een veurgeschiedenisse. Kiek effen nao of je neet een ouwere versie van disse pagina herstellen kunnen.',
'confirmdeletetext'      => 'Disse actie wis alle inhoud en geschiedenisse uut de databanke. Bevestig hieronder dat dit de bedoeling is en da-j de gevolgen dervan begriepen.',
'actioncomplete'         => 'Uut-evoerd',
'deletedtext'            => '\'t Artikel "$1" is vort-edaon. Zie de "$2" veur een lieste van pagina\'s dee as les vort-edaon bin.',
'deletedarticle'         => '"$1" vort-edaon',
'suppressedarticle'      => 'hef "[[$1]]" verbörgen',
'dellogpage'             => "Vort-edaone pagina's",
'dellogpagetext'         => "Hieronder een lieste van pagina's en ofbeeldingen dee 't les vort-edaon bin.",
'deletionlog'            => "Vort-edaone pagina's",
'reverted'               => 'Eerdere versie hersteld',
'deletecomment'          => 'Rejen',
'deleteotherreason'      => 'Aandere/extra rejen:',
'deletereasonotherlist'  => 'Aandere rejen',
'deletereason-dropdown'  => "*Rejens veur 't vortdoon van pagina's
** op anvrage van de auteur
** schending van de auteursrechen
** vandelisme",
'delete-edit-reasonlist' => "Rejens veur 't vortdoon bewarken",
'delete-toobig'          => "Disse pagina hef een lange bewarkingsgeschiedenisse, meer as $1 {{PLURAL:$1|versie|versies}}.
't Vortdoon van dit soort pagina's is mit rechen bepark um 't per ongelok versteuren van de warking van {{SITENAME}} te veurkoemen.",
'delete-warning-toobig'  => "Disse pagina hef een lange bewarkingsgeschiedenisse, meer as $1 {{PLURAL:$1|versie|versies}}.
Woart je: 't vortdoon van disse pagina kan de warking van de databanke van {{SITENAME}} versteuren.
Wees veurzichtig",

# Rollback
'rollback'         => 'Wiezigingen herstellen',
'rollback_short'   => 'Weerummedreien',
'rollbacklink'     => 'Weerummedreien',
'rollbackfailed'   => 'Wieziging herstellen is mislok',
'cantrollback'     => 'De wiezigingen konnen neet hersteld wonnen; der is mar 1 auteur.',
'alreadyrolled'    => 'Kan de leste wieziging van de pagina [[$1]] deur [[User:$2|$2]] ([[User talk:$2|Overleg]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); neet weerummedreien.
Een aander hef disse pagina al bewark of hersteld naor een eerdere versie.

De leste bewarking op disse pagina is edaon deur [[User:$3|$3]] ([[User talk:$3|Overleg]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'      => "De bewarkingssamenvatting was: ''$1''.", # only shown if there is an edit comment
'revertpage'       => 'Wiezigingen deur [[Special:Contributions/$2|$2]] hersteld tot de versie nao de leste wieziging deur $1', # Additionally available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success' => 'Wiezigingen van $1; weerummedreid naor de leste versie van $2.',
'sessionfailure'   => 'Der is een prebleem mit joew anmeldsessie. De actie is stop-ezet uut veurzörg tegen een beveiligingsrisico (dat besteet uut \'t meugelijke "kraken" van disse sessie). Gao een pagina weerumme, laot disse pagina opniej en prebeer \'t nog es.',

# Protect
'protectlogpage'              => 'Beveiligingslogboek',
'protectlogtext'              => "Hieronder steet een lieste mit pagina's dee beveilig bin.",
'protectedarticle'            => '[[$1]] is beveilig',
'modifiedarticleprotection'   => 'beveiligingsnivo van "[[$1]]"  ewiezig',
'unprotectedarticle'          => '[[$1]] vrie-egeven',
'movedarticleprotection'      => 'hef de beveiligingsinstellingen over-ezet van "[[$2]]" naor "[[$1]]"',
'protect-title'               => 'Instellen van beveiligingsnivo veur "$1"',
'prot_1movedto2'              => '[[$1]] is ewiezig naor [[$2]]',
'protect-legend'              => 'Beveiliging bevestigen',
'protectcomment'              => 'Rejen',
'protectexpiry'               => 'Duur',
'protect_expiry_invalid'      => 'Verlooptied is ongeldig.',
'protect_expiry_old'          => 'De verlooptied is al veurbie.',
'protect-unchain'             => 'Ontkoppel de naamwiezigingsrechen',
'protect-text'                => "Hier ku-j 't beveiligingsnivo veur de pagina '''$1''' instellen.",
'protect-locked-blocked'      => "Je kunnen beveiligingsnivo's neet wiezigen terwiel je eblokkeerd bin. Hier bin de instellingen zoas ze noen bin veur de pagina '''$1''':",
'protect-locked-dblock'       => "Beveiligingsnivo's kunnen noen effen neet ewiezig wonnen umdat de databanke noen beveilig is.
Hier staon de instellingen zoas ze noen bin veur de pagina '''$1''':",
'protect-locked-access'       => "Je hemmen gien rechen um 't beveilingsnivo van pagina's te wiezigen.
Hier staon de instellingen zoas ze noen bin veur de pagina '''$1''':",
'protect-cascadeon'           => "Disse pagina wonnen beveilig umdat 't op-eneumen is in de volgende {{PLURAL:$1|pagina|pagina's}} dee beveilig {{PLURAL:$1|is|bin}} mit de cascade-optie. Je kunnen 't beveiligingsnivo van disse pagina anpassen, mar dat hef gien invleud op de cascadebeveiliging.",
'protect-default'             => 'Veur alle gebrukers',
'protect-fallback'            => 'Hierveur is \'t rech "$1" neudig',
'protect-level-autoconfirmed' => 'Blokkeer nieje en annenieme gebrukers',
'protect-level-sysop'         => 'Allinnig beheerders',
'protect-summary-cascade'     => 'cascade',
'protect-expiring'            => 'verloop op $1 (UTC)',
'protect-expiry-indefinite'   => 'onbepark',
'protect-cascade'             => "Cascadebeveiliging (beveilig alle pagina's en mallen dee in disse pagina op-eneumen bin)",
'protect-cantedit'            => "Je kunnen 't beveiligingsnivo van disse pagina neet wiezigen, umda-j gien rechen hemmen um 't te bewarken.",
'protect-othertime'           => 'Aandere tiedsduur:',
'protect-othertime-op'        => 'aandere tiedsduur',
'protect-existing-expiry'     => 'Bestaonde verloopdaotum: $2 $3',
'protect-otherreason'         => 'Aandere rejen:',
'protect-otherreason-op'      => 'aandere rejen',
'protect-dropdown'            => '*Veulveurkomende rejens veur beveiliging
** Vandelisme
** Spam
** Bewarkingsoorlog
** Preventieve beveiliging veulbezochen pagina',
'protect-edit-reasonlist'     => 'Rejens veur beveiliging bewarken',
'protect-expiry-options'      => '1 uur:1 hour,1 dag:1 day,1 weke:1 week,2 weken:2 weeks,1 maond:1 month,3 maonden:3 months,6 maonden:6 months,1 jaor:1 year,onbepark:infinite', # display1:time1,display2:time2,...
'restriction-type'            => 'Toegang',
'restriction-level'           => 'Beveiligingsnivo',
'minimum-size'                => 'Minimumgrootte (bytes)',
'maximum-size'                => 'Maximumgrootte',
'pagesize'                    => '(byte)',

# Restrictions (nouns)
'restriction-edit'   => 'Bewark',
'restriction-move'   => 'Herneum',
'restriction-create' => 'Anmaken',
'restriction-upload' => 'Bestaand toevoegen',

# Restriction levels
'restriction-level-sysop'         => 'volledige beveiliging',
'restriction-level-autoconfirmed' => 'semibeveilig',
'restriction-level-all'           => 'alles',

# Undelete
'undelete'                     => "Vort-edaone pagina's bekieken",
'undeletepage'                 => "Vort-edaone pagina's bekieken en weerummeplaosen",
'undeletepagetitle'            => "'''Hieronder staon de vort-edaone bewarkingen van [[:$1]]'''.",
'viewdeletedpage'              => "Bekiek vort-edaone pagina's",
'undeletepagetext'             => "Hieronder {{PLURAL:$1|steet de pagina dee vort-edaon is|staon pagina's dee vort-edaon bin}} en vanuut 't archief  weerummeplaos {{PLURAL:$1|kan|kunnen}} wonnen.",
'undelete-fieldset-title'      => 'Versies weerummeplaosen',
'undeleteextrahelp'            => "Um de pagina mit alle eerdere versies weerumme te plaosen lao-j alle hokjes leeg en klik op '''''Weerummeplaosen!'''''.
Um een bepaolde versies weerumme te plaosen mu-j de versies dee-j weerummeplaosen willen anvinken en klik op '''''Weerummeplaosen!'''''.
Um een bulte achter mekaarstaonde versies te kiezen mu-j de eerste in de reeks anvinken en vervolgens mit de schuufknoppe in-edrok de leste anvinken. Hierdeur wonnen oek alle tussenliggende versies mee-eneumen.
A-j op '''''Herstel''''' klikken wonnen 't infermasieveld en alle hokjes leeg-emaak.",
'undeleterevisions'            => '$1 {{PLURAL:$1|versie|versies}} earchiveerd',
'undeletehistory'              => 'A-j een pagina weerummeplaosen, wonnen alle versies as ouwe versies weerummeplaos. 
As der al een nieje pagina mit dezelfde naam an-emaak is, zullen disse versies as ouwe versies weerummeplaos wonnen, mar de op-esleugen versie zal neet ewiezig wonnen.',
'undeleterevdel'               => "Herstellen kan neet as daor de leste versie van de pagina of 't bestaand gedeeltelijk mee vort-edaon wonnen.
In dat geval mu-j de leste versie as zichbaor instellen.",
'undeletehistorynoadmin'       => "Disse pagina is vort-edaon. De rejen hierveur steet hieronder, samen mit de infermasie van de gebrukers dee dit artikel ewiezig hemmen veurdat 't vort-edaon is. De tekse van 't artikel is allinnig zichbaor veur beheerders.",
'undelete-revision'            => 'Vort-edaone versies van $1 (per $4 um $5) deur $3:',
'undeleterevision-missing'     => "Ongeldige of ontbrekende versie. 't Is meugelijk da-j een verkeerde verwiezing gebruken of dat disse pagina weerummeplaos is of dat 't uut archief ewis is.",
'undelete-nodiff'              => 'Gien eerdere versie evunnen.',
'undeletebtn'                  => 'Weerummeplaosen',
'undeletelink'                 => 'bekiek/weerummeplaosen',
'undeletereset'                => 'Herstel',
'undeleteinvert'               => 'Selectie ummekeren',
'undeletecomment'              => 'Opmarking:',
'undeletedarticle'             => '"$1" is weerummeplaos',
'undeletedrevisions'           => '$1 {{PLURAL:$1|versie|versies}} weerummeplaos',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 versie|$1 versies}} en {{PLURAL:$2|1 bestaand|$2 bestanen}} bin weerummeplaos',
'undeletedfiles'               => '{{PLURAL:$1|1 bestaand|$1 bestanen}} weerummeplaos',
'cannotundelete'               => "Weerummeplaosen van 't bestaand is mislok; een aander hef disse pagina meschien al weerummeplaos.",
'undeletedpage'                => "<big>'''$1 is weerummeplaos'''</big>

Bekiek 't [[Special:Log/delete|logboek vort-edaone pagina's]] veur een overzichte van pagina's dee kortens vort-edaon en weerummeplaos bin.",
'undelete-header'              => "Zie [[Special:Log/delete|'t logboek vort-edaone pagina's]] veur pagina's dee 't les vort-edaon bin.",
'undelete-search-box'          => "Deurzeuk vort-edaone pagina's",
'undelete-search-prefix'       => "Teun pagina's vanof:",
'undelete-search-submit'       => 'Zeuk',
'undelete-no-results'          => "Gien pagina's evunnen in 't archief mit vort-edaone pagina's.",
'undelete-filename-mismatch'   => "Bestaansversie van 't tiedstip $1 kon neet hersteld wonnen: bestaansnaam kloppen neet",
'undelete-bad-store-key'       => "Bestaansversie van 't tiedstip $1 kon neet hersteld wonnen: 't bestaand was der al neet meer veurdat 't vort-edaon wönnen.",
'undelete-cleanup-error'       => 'Fout bie \'t herstellen van \'t ongebruken archiefbestaand "$1".',
'undelete-missing-filearchive' => "'t Lokken neet um ID $1 weerumme te plaosen umdat 't neet in de databanke is.
Meschien is 't al weerummeplaos.",
'undelete-error-short'         => "Fout bie 't herstellen van 't bestaand: $1",
'undelete-error-long'          => "Fouten bie 't herstellen van 't bestaand:

$1",
'undelete-show-file-confirm'   => 'Bi-j der wisse van da-j een vort-edaone versie van \'t bestaand "<nowiki>$1</nowiki>" van $2 um $3 bekieken willen?',
'undelete-show-file-submit'    => 'Ja',

# Namespace form on various pages
'namespace'      => 'Naamruumte:',
'invert'         => 'selectie ummekeren',
'blanknamespace' => '(encyclopedie)',

# Contributions
'contributions'       => 'Biedragen van disse gebruker',
'contributions-title' => 'Biedragen van $1',
'mycontris'           => 'Mien biedragen',
'contribsub2'         => 'Veur $1 ($2)',
'nocontribs'          => 'Gien wiezigingen evunnen dee an de estelde criteria voldoon.', # Optional parameter: $1 is the user name
'uctop'               => ' (leste wieziging)',
'month'               => 'Maond:',
'year'                => 'Jaor:',

'sp-contributions-newbies'       => 'Teun allinnig de biedragen van nieje gebrukers',
'sp-contributions-newbies-sub'   => 'Veur niejelingen',
'sp-contributions-newbies-title' => 'Biedragen van nieje gebrukers',
'sp-contributions-blocklog'      => 'Blokkeerlogboek',
'sp-contributions-logs'          => 'logboeken',
'sp-contributions-search'        => 'Zeuken naor biedragen',
'sp-contributions-username'      => 'IP-adres of gebrukersnaam:',
'sp-contributions-submit'        => 'Zeuk',

# What links here
'whatlinkshere'            => 'Verwiezingen naor disse pagina',
'whatlinkshere-title'      => 'Pagina\'s dee verwiezen naor "$1"',
'whatlinkshere-page'       => 'Pagina:',
'linkshere'                => "Disse pagina's verwiezen naor '''[[:$1]]''':",
'nolinkshere'              => "Gien enkele pagina verwies naor '''[[:$1]]'''.",
'nolinkshere-ns'           => "Gien enkele pagina verwiest naor '''[[:$1]]''' in de ekeuzen naamruumte.",
'isredirect'               => 'deurverwiezing',
'istemplate'               => 'in-evoeg as mal',
'isimage'                  => 'bestaansverwiezing',
'whatlinkshere-prev'       => '{{PLURAL:$1|veurige|veurige $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|volgende|volgende $1}}',
'whatlinkshere-links'      => '← verwiezingen',
'whatlinkshere-hideredirs' => '$1 deurverwiezingen',
'whatlinkshere-hidetrans'  => '$1 in-evoegen mallen',
'whatlinkshere-hidelinks'  => '$1 verwiezingen',
'whatlinkshere-hideimages' => '$1 bestaansverwiezingen',
'whatlinkshere-filters'    => 'Filters',

# Block/unblock
'blockip'                         => 'Gebruker blokkeren',
'blockip-legend'                  => 'Een gebruker of IP-adres blokkeren',
'blockiptext'                     => "Gebruuk dit formelier um een IP-adres te blokkeren. 't Is bedoeld um vandelisme te veurkoemen. Misbruuk van disse functie zal tot gevolg hemmen dat de staotus van beheerder of-eneumen zal wonnen.",
'ipaddress'                       => 'IP-adres:',
'ipadressorusername'              => 'IP-adres of gebrukersnaam',
'ipbexpiry'                       => 'Verloop nao',
'ipbreason'                       => 'Rejen',
'ipbreasonotherlist'              => 'aandere rejen',
'ipbreason-dropdown'              => "*Algemene rejens veur 't blokkeren
** valse infermasie invoeren
** pagina's leegmaken
** ongewunste verwiezingen plaosen
** onzinteksen schrieven
** targerieje of naor gedrag
** misbruuk vanof meerdere prefielen
** ongewunste gebrukersnaam",
'ipbanononly'                     => 'Blokkeer allinnig annenieme gebrukers',
'ipbcreateaccount'                => "Veurkom 't anmaken van gebrukersprefielen",
'ipbemailban'                     => 'Veurkom dat bepaolde gebrukers berichen versturen',
'ipbenableautoblock'              => 'De IP-adressen van disse gebruker vanzelf blokkeren',
'ipbsubmit'                       => 'adres blokkeren',
'ipbother'                        => 'Aandere tied',
'ipboptions'                      => '2 uren:2 hours,1 dag:1 day,3 dagen:3 days,1 weke:1 week,2 weken:2 weeks,1 maond:1 month,3 maonden:3 months,6 maonden:6 months,1 jaor:1 year,onbepark:infinite', # display1:time1,display2:time2,...
'ipbotheroption'                  => 'aanders',
'ipbotherreason'                  => 'Aandere/extra rejen:',
'ipbhidename'                     => 'Verbarg de gebrukersnaam in bewarkingen en liesten',
'ipbwatchuser'                    => 'Gebrukerspagina en overlegpagina op volglieste zetten',
'ipballowusertalk'                => 'Disse gebruker toestaon tiejens de blokkering zien eigen overlegpagina te bewarken',
'ipb-change-block'                => 'De gebruker opniej blokkeren mit disse instellingen',
'badipaddress'                    => 'ongeldig IP-adres of onbestaonde gebrukersnaam',
'blockipsuccesssub'               => 'Succesvol eblokkeerd',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] is noen eblokkeerd.<br />
Op de [[Special:IPBlockList|IP-blokkeerlieste]] steet een lieste mit alle blokkeringen.',
'ipb-edit-dropdown'               => 'Blokkeerrejens bewarken',
'ipb-unblock-addr'                => 'Deblokkeer $1',
'ipb-unblock'                     => 'Deblokkeer een gebruker of IP-adres',
'ipb-blocklist-addr'              => 'Bestaonde blokkeringen veur $1',
'ipb-blocklist'                   => 'Bekiek bestaonde blokkeringen',
'ipb-blocklist-contribs'          => 'Biedragen van $1',
'unblockip'                       => 'Deblokkeer gebruker',
'unblockiptext'                   => "Gebruuk 't onderstaonde formelier um weerumme schrieftoegang te geven an een eblokkeren gebruker of IP-adres.",
'ipusubmit'                       => 'Blokkering derof haolen',
'unblocked'                       => '[[User:$1|$1]] is edeblokeerd',
'unblocked-id'                    => 'Blokkade $1 is derof ehaold',
'ipblocklist'                     => 'Lieste van IP-adressen en gebrukers dee eblokkeerd bin',
'ipblocklist-legend'              => 'Een eblokkeren gebruker zeuken',
'ipblocklist-username'            => 'Gebrukersnaam of IP-adres:',
'ipblocklist-sh-userblocks'       => 'gebrukersblokkeringen $1',
'ipblocklist-sh-tempblocks'       => 'tiedelijke blokkeringen $1',
'ipblocklist-sh-addressblocks'    => 'enkele IP-blokkeringen $1',
'ipblocklist-submit'              => 'Zeuk',
'blocklistline'                   => 'Op $1 (vervuilt op $4) blokkeren $2: $3',
'infiniteblock'                   => 'onbepark',
'expiringblock'                   => '$1',
'anononlyblock'                   => 'allinnig anneniemen',
'noautoblockblock'                => 'autoblok neet actief',
'createaccountblock'              => 'anmaken van een gebrukersprefiel is eblokkeerd',
'emailblock'                      => "'t versturen van berichen is eblokkeerd",
'blocklist-nousertalk'            => 'kan zien eigen overlegpagina neet bewarken',
'ipblocklist-empty'               => 'De blokkeerlieste is leeg.',
'ipblocklist-no-results'          => "'t Op-evreugen IP-adres of de gebrukersnaam is neet eblokkeerd.",
'blocklink'                       => 'Blokkeer',
'unblocklink'                     => 'deblokkeer',
'change-blocklink'                => 'blokkering wiezigen',
'contribslink'                    => 'Biedragen',
'autoblocker'                     => 'Vanzelf eblokkeerd umdat \'t IP-adres overenekump mit \'t IP-adres van [[User:$1|$1]], dee eblokkeerd is mit as rejen: "$2"',
'blocklogpage'                    => 'Blokkeerlogboek',
'blocklog-fulllog'                => "'t Complete blokkeerlogboek",
'blocklogentry'                   => 'blokkeren "[[$1]]" veur $2 $3',
'reblock-logentry'                => "hef de instellingen veur de blokkering van [[$1]] ewiezig. 't Loop noen of over $2 $3",
'blocklogtext'                    => "Hier zie-j een lieste van de leste blokkeringen en deblokkeringen. Autematische blokkeringen en deblokkeringen koemen neet in 't logboek te staon. Zie de [[Special:IPBlockList|IP-blokkeerlieste]] veur de lieste van adressen dee noen eblokkeerd bin.",
'unblocklogentry'                 => 'Blokkering van [[$1]] op-eheven',
'block-log-flags-anononly'        => 'allinnig anneniemen',
'block-log-flags-nocreate'        => 'anmaken van gebrukersprefielen uut-eschakeld',
'block-log-flags-noautoblock'     => 'autoblokkeren uut-eschakeld',
'block-log-flags-noemail'         => "'t versturen van berichen is eblokkeerd",
'block-log-flags-nousertalk'      => 'kan zien eigen overlegpagina neet bewarken',
'block-log-flags-angry-autoblock' => 'uut-ebreide autematische blokkering in-eschakeld',
'block-log-flags-hiddenname'      => 'gebrukersnaam verbörgen',
'range_block_disabled'            => 'De meugelijkheid veur beheerders um een groep adressen te blokkeren is uut-eschakeld.',
'ipb_expiry_invalid'              => 'De op-egeven verlooptied is ongeldig.',
'ipb_expiry_temp'                 => 'Blokkeringen veur verbörgen gebrukers mutten permenent ween.',
'ipb_hide_invalid'                => 'Kan disse gebruker neet verbargen; werschienlijk hef e al te veule bewarkingen emaak.',
'ipb_already_blocked'             => '"$1" is al eblokkeerd',
'ipb-needreblock'                 => '== Disse gebruker is al eblokkeerd ==
$1 is al eblokkeerd.
Wi-j de instellingen wiezigen?',
'ipb_cant_unblock'                => "Foutmelding: blokkade ID $1 neet evunnen, 't is meschien al edeblokkeerd.",
'ipb_blocked_as_range'            => "Fout: 't IP-adres $1 is neet drek eblokkeerd en de blokkering kan neet op-eheven wonnen.
De blokkering is onderdeel van de reeks $2, waovan de blokkering wè op-eheven kan wonnen.",
'ip_range_invalid'                => 'Ongeldige IP-reeks',
'blockme'                         => 'Mien blokkeren',
'proxyblocker'                    => 'Proxyblokker',
'proxyblocker-disabled'           => 'Disse functie is uut-eschakeld.',
'proxyblockreason'                => 'Dit is een autematische preventieve blokkering umda-j gebruuk maken van een open proxyserver.',
'proxyblocksuccess'               => 'Succesvol.',
'sorbsreason'                     => 'Joew IP-adres is op-eneumen as open proxyserver in de DNS-blacklist de {{SITENAME}} ebruukt.',
'sorbs_create_account_reason'     => 'Joew IP-adres is op-eneumen as open proxyserver in de DNS-blacklist de {{SITENAME}} ebruukt.
Je kunnen gien gebrukerspagina anmaken.',
'cant-block-while-blocked'        => 'Je kunnen aandere gebrukers neet blokkeren a-j zelf oek eblokkeerd bin.',

# Developer tools
'lockdb'              => 'Databanke blokkeren',
'unlockdb'            => 'Databanke vriegeven',
'lockdbtext'          => "Waorschuwing: de databanke blokkeren hef tot gevolg dat gien enkele gebruker meer in staot is um pagina's te bewarking, d'r veurkeuren te wiezing of iets aanders te doon waorveur der wiezigingen in de databanke neudig bin.",
'unlockdbtext'        => 'Vriegeven van de databanke maak alle bewarkingen weer meugelijk.
Mut de databanke vrie-egeven wonnen?',
'lockconfirm'         => 'Ja, ik wille de databanke blokkeren.',
'unlockconfirm'       => 'Ja, ik wille de databanke vriegeven.',
'lockbtn'             => 'Databanke blokkeren',
'unlockbtn'           => 'Databanke vriegeven',
'locknoconfirm'       => "Je hemmen 't vakjen neet esillekteerd um joew keuze te bevestigen.",
'lockdbsuccesssub'    => 'Databanke succesvol eblokkeerd',
'unlockdbsuccesssub'  => 'Blokkering van de databanke is op-eheven.',
'lockdbsuccesstext'   => "De databanke is eblokkeerd.<br />
Vergeet neet de [[Special:UnlockDB|databanke vrie te geven]] a-j klaor bin mit 't onderhoud.",
'unlockdbsuccesstext' => 'De databanke is weer vrie-egeven.',
'lockfilenotwritable' => "Gien schriefrechen op 't beveiligingsbestaand van de databanke. Um de databanke te blokkeren of de blokkering op te heffen, mut der eschreven kunnen wonnen deur de webserver.",
'databasenotlocked'   => 'De databanke is neet eblokkeerd.',

# Move page
'move-page'                    => 'Herneum "$1"',
'move-page-legend'             => 'Pagina herneumen',
'movepagetext'                 => "Deur 't formelier da-j hieronder zien in te vullen ku-j de naam wiezigen, zo geet de veurgeschiedenisse neet verleuren. De ouwe paginanaam zal autematisch een deurverwiezing wonnen naor de nieje pagina (disse pagina kan, zoas op alle artikels mit een deurverwiezing, an-epas wonnen). Deurverwiezingen wonnen neet meeveraanderd en mutten mit de haand ewiezig wonnen.",
'movepagetalktext'             => "De biebeheurende overlegpagina krieg oek een nieje titel, mar '''neet''' in de volgende gevallen:
* As de pagina in een aandere naamruumte eplaos wonnen
* As der al een neet-lege overlegpagina besteet onder de aandere naam
* A-j 't onderstaonde vinkjen vorthaolen",
'movearticle'                  => 'Herneum',
'movenologin'                  => 'Neet an-emeld.',
'movenologintext'              => 'Je mutten [[Special:UserLogin|an-emeld]] ween um de naam van een pagina te wiezigen.',
'movenotallowed'               => "Je hemmen gien rechen um pagina's te herneumen.",
'movenotallowedfile'           => 'Je hemmen gien rechen um bestanen te herneumen.',
'cant-move-user-page'          => "Je hemmen gien rechen um gebrukerspagina's te herneumen.",
'cant-move-to-user-page'       => 'Je hemmen gien rechen um een pagina naor een gebrukerspagina te herneumen. Herneumen naor een subpagina ma-j wè doon.',
'newtitle'                     => 'Nieje naam',
'move-watch'                   => 'volg disse pagina',
'movepagebtn'                  => 'Herneum',
'pagemovedsub'                 => 'Naamwieziging succesvol',
'movepage-moved'               => '<big>\'\'\'"$1" is ewiezig naor "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movepage-moved-redirect'      => 'Der is een deurverwiezing an-emaak.',
'movepage-moved-noredirect'    => 'Der is gien deurverwiezing an-emaak.',
'articleexists'                => 'Onder disse naam besteet al een pagina. Kies een aandere naam.',
'cantmove-titleprotected'      => "Je kunnen gien pagina naor disse titel herneumen, umdat de nieje titel beveilig is tegen 't anmaken dervan.",
'talkexists'                   => "De pagina zelf is verplaos, mar de overlegpagina kon neet verplaos wonnen, umdat de doelnaam al een neet-lege overlegpagina had. Combineer de overlegpagina's haandmaotig.",
'movedto'                      => 'wiezigen naor',
'movetalk'                     => "De overlegpagina oek wiezigen, as 't meuglijk is.",
'move-subpages'                => "Herneum subpagina's (tot en mit $1)",
'move-talk-subpages'           => "Herneum subpagina's van overlegpagina's (tot en mit $1)",
'movepage-page-exists'         => 'De pagina $1 besteet al en kan neet autematisch vort-edaon wonnen.',
'movepage-page-moved'          => 'De pagina $1 is herneumd naor $2.',
'movepage-page-unmoved'        => 'De pagina $1 kon neet herneumd wonnen naor $2.',
'movepage-max-pages'           => "'t Maximale antal autematisch te herneumen pagina's is bereik ({{PLURAL:$1|$1|$1}}).
De overige pagina's wonnen neet autematisch herneumd.",
'1movedto2'                    => '[[$1]] is ewiezig naor [[$2]]',
'1movedto2_redir'              => '[[$1]] is ewiezig over de deurverwiezing [[$2]] hinne',
'move-redirect-suppressed'     => 'deurverwiezing onderdrokken',
'movelogpage'                  => 'Titelwiezigingen',
'movelogpagetext'              => "Hieronder steet een lieste mit pagina's dee herneumd bin.",
'movesubpage'                  => "{{PLURAL:$1|Subpagina|Subpagina's}}",
'movesubpagetext'              => "De {{PLURAL:$1|subpagina|$1 subpagina's}} van disse pagina vie-j hieronder.",
'movenosubpage'                => "Disse pagina hef gien subpagina's.",
'movereason'                   => 'Rejen:',
'revertmove'                   => 'Weerummedreien',
'delete_and_move'              => 'Vortdoon en herneumen',
'delete_and_move_text'         => '==Mut vort-edaon wonnen==
<div style="color: red"> Onder de nieje naam "[[:$1]]" besteet al een artikel. Wi-j \'t vortdoon um plaose te maken veur \'t herneumen?</div>',
'delete_and_move_confirm'      => 'Ja, disse pagina vortdoon',
'delete_and_move_reason'       => 'Vort-edaon vanwegen naamwieziging',
'selfmove'                     => "De naam kan neet ewiezig wonnen naor de naam dee 't al hef.",
'immobile-source-namespace'    => 'Pagina\'s in de naamruumte "$1" kunnen neet herneumd wonnen',
'immobile-target-namespace'    => 'Pagina\'s kunnen neet herneumd wonnen naor de naamruumte "$1"',
'immobile-target-namespace-iw' => "Een interwikiverwiezing is gien geldige bestemming veur 't herneumen van een pagina.",
'immobile-source-page'         => 'Disse pagina kan neet herneumd wonnen.',
'immobile-target-page'         => 'Kan neet herneumd wonnen naor disse paginanaam.',
'imagenocrossnamespace'        => 'Een mediabestaand kan neet naor een aandere naamruumte verplaos wonnen',
'imagetypemismatch'            => "De nieje bestaansextensie is neet gelieke an 't bestaanstype",
'imageinvalidfilename'         => 'De nieje bestaansnaam is ongeldig',
'fix-double-redirects'         => 'Alle deurverwiezingen dee naor de ouwe titel verwiezen, herneumen naor de nieje titel',
'move-leave-redirect'          => 'Een deurverwiezing achterlaoten',

# Export
'export'            => "Pagina's uutvoeren",
'exporttext'        => "De tekse en geschiedenisse van een pagina of een antal pagina's kunnen in XML-formaot uut-evoerd wonnen. Dit bestaand ku-j daornao uutvoeren naor een aandere MediaWiki deur de [[Special:Import|invoerpagina]] te gebruken.

Zet in 't onderstaonde veld de namen van de pagina's dee-j uutvoeren willen, één pagina per regel, en geef an o-j alle versies mit de bewarkingssamenvatting uutvoeren willen of allinnig de leste versies mit de bewarkingssamenvatting.

A-j dat leste doon willen dan ku-j oek een verwiezing gebruken, bieveurbeeld [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] veur de pagina \"{{MediaWiki:Mainpage}}\".",
'exportcuronly'     => 'Allinnig de actuele versie, neet de veurgeschiedenisse',
'exportnohistory'   => "----
'''NB:''' 't uutvoeren van de hele geschiedenisse is uut-eschakeld vanwegen prestasierejens.",
'export-submit'     => 'Uutvoeren',
'export-addcattext' => "Pagina's toevoegen uut kattegerie:",
'export-addcat'     => 'Toevoegen',
'export-addnstext'  => "Pagina's uut de volgende naamruumte toevoegen:",
'export-addns'      => 'Toevoegen',
'export-download'   => 'As bestaand opslaon',
'export-templates'  => 'Mit mallen derbie',
'export-pagelinks'  => "Pagina's waor naor verwezen wonnen opnemen tot:",

# Namespace 8 related
'allmessages'               => 'Alle systeemteksten',
'allmessagesname'           => 'Naam',
'allmessagesdefault'        => 'Standardtekse',
'allmessagescurrent'        => 'De leste versie',
'allmessagestext'           => 'Hieronder steet een lieste mit alle systeemteksen in de MediaWiki-naamruumte.
Kiek oek effen bie [http://www.mediawiki.org/wiki/Localisation MediaWiki-lokalisasie] en [http://translatewiki.net translatewiki.net] a-j biedragen willen an de algemene vertaling veur MediaWiki.',
'allmessagesnotsupportedDB' => "Disse pagina kan neet gebruuk wonnen umdat '''\$wgUseDatabaseMessages''' uut-eschakeld is.",
'allmessagesfilter'         => 'Berichnaamfilter:',
'allmessagesmodified'       => 'Allinnig teksen dee ewiezig bin',

# Thumbnails
'thumbnail-more'           => 'vergroten',
'filemissing'              => 'Bestaand ontbreek',
'thumbnail_error'          => "Fout bie 't laojen van 't ofbeeldingsoverzichte: $1",
'djvu_page_error'          => 'DjVu-pagina buten bereik',
'djvu_no_xml'              => "Kon de XML-gegevens veur 't DjVu-bestaand neet oproepen",
'thumbnail_invalid_params' => 'Ongeldige ofbeeldingsoverzichparremeters',
'thumbnail_dest_directory' => 'De bestemmingsmap kon neet an-emaak wonnen.',

# Special:Import
'import'                     => "Pagina's invoeren",
'importinterwiki'            => 'Transwiki-invoer',
'import-interwiki-text'      => "Kies een wiki en paginanaam um in te voeren.
Versie- en auteursgegevens blieven hierbie beweerd.
Alle transwiki-invoerhaandelingen wonnen op-esleugen in 't [[Special:Log/import|invoerlogboek]].",
'import-interwiki-source'    => 'Bronwiki/pagina:',
'import-interwiki-history'   => 'Kopieer de hele geschiedenisse veur disse pagina',
'import-interwiki-templates' => 'Alle mallen opnemen',
'import-interwiki-submit'    => 'Invoeren',
'import-interwiki-namespace' => 'Doelnaamruumte:',
'import-upload-filename'     => 'Bestaansnaam:',
'import-comment'             => 'Opmarkingen:',
'importtext'                 => "Gebruuk de Special:Export-optie in de wiki waor de infermasie vandaonkump, slao 't op joew eigen systeem op, en stuur 't daornao hier op.",
'importstart'                => "Pagina's an 't invoeren...",
'import-revision-count'      => '$1 {{PLURAL:$1|versie|versies}}',
'importnopages'              => "Der bin gien pagina's um in te voeren.",
'importfailed'               => 'Invoeren is mislok: $1',
'importunknownsource'        => 'Onbekend invoerbrontype',
'importcantopen'             => "Kon 't invoerbestaand neet los doon",
'importbadinterwiki'         => 'Foute interwikiverwiezing',
'importnotext'               => 'Leeg of gien tekse',
'importsuccess'              => 'Invoeren succesvol!',
'importhistoryconflict'      => 'Der bin konflikken in de geschiedenisse van de pagina (is meschien eerder al in-evoerd)',
'importnosources'            => 'Gien transwiki-invoerbronnen edefinieerd en drekte geschiedenistoevoegingen bin eblokkeerd.',
'importnofile'               => 'Der is gien invoerbestaand toe-evoeg.',
'importuploaderrorsize'      => "'t Oplaojen van 't invoerbestaand is mislok.
't Bestaand is groter as de in-estelde limiet.",
'importuploaderrorpartial'   => "'t Oplaojen van 't invoerbestaand is mislok.
't Bestaand is mar gedeeltelijk an-ekeumen.",
'importuploaderrortemp'      => "'t Oplaojen van 't invoerbestaand is mislok.
De tiedelijke map is neet anwezig.",
'import-parse-failure'       => "Fout bie 't verwarken van de XML-invoer",
'import-noarticle'           => "Der bin gien pagina's um in te voeren!",
'import-nonewrevisions'      => 'Alle versies bin al eerder in-evoerd.',
'xml-error-string'           => '$1 op regel $2, kelom $3 (byte $4): $5',
'import-upload'              => 'XML-gegevens derbie doon',
'import-token-mismatch'      => "De sessiegegevens bin verleuren egaon. Prebeer 't opniej.",
'import-invalid-interwiki'   => "'t Is neet meugelijk um van de an-egeven wiki in te voeren.",

# Import log
'importlogpage'                    => 'Invoerlogboek',
'importlogpagetext'                => "Administratieve invoer van pagina's mit geschiedenisse van aandere wiki's.",
'import-logentry-upload'           => '[[$1]] in-evoerd via een bestaanstoevoeging',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|versie|versies}}',
'import-logentry-interwiki'        => 'transwiki $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|versie|versies}} van $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Oew gebroekersbladziede',
'tooltip-pt-anonuserpage'         => "Gebroekersbladziede vuur t IP-adres da'j broekt",
'tooltip-pt-mytalk'               => 'Oew oaverlegbladziede',
'tooltip-pt-anontalk'             => 'Oaverlegbladziede van n naamloosn gebroeker van dit IP-adres',
'tooltip-pt-preferences'          => 'Miene vuurkeurn',
'tooltip-pt-watchlist'            => 'Lieste van bladziedn dee op miene voalglieste stoan',
'tooltip-pt-mycontris'            => 'Liest van oew biejdreagn',
'tooltip-pt-login'                => 'Iej wördt van harte oetneugd um oe an te mealdn as gebroeker, mer t is nich verplicht',
'tooltip-pt-anonlogin'            => 'Iej wördt van harte oetneugd um oe an te mealdn as gebroeker, mer t is nich verplicht',
'tooltip-pt-logout'               => 'Ofmealdn',
'tooltip-ca-talk'                 => 'Loat n oaverlegtekst oaver disse bladziede zeen',
'tooltip-ca-edit'                 => 'Beweark disse bladziede',
'tooltip-ca-addsection'           => 'Niej oonderwearp toovoogen',
'tooltip-ca-viewsource'           => 'Disse bladziede is beveiligd teagn veraandern. Iej könt wal kiekn noar de bladziede',
'tooltip-ca-history'              => 'Oaldere versies van disse bladziede',
'tooltip-ca-protect'              => 'Beveilig disse bladziede teagn veraandern',
'tooltip-ca-delete'               => 'Smiet disse bladziede vort',
'tooltip-ca-undelete'             => 'Haal n inhoald van disse bladziede oet n emmer',
'tooltip-ca-move'                 => 'Gef disse bladziede nen aandern titel',
'tooltip-ca-watch'                => 'Voog disse bladziede too an oewe voalglieste',
'tooltip-ca-unwatch'              => 'Smiet disse bladziede van oewe voalglieste',
'tooltip-search'                  => '{{SITENAME}} deurzeuken',
'tooltip-search-go'               => "Naor een pagina mit disse naam gaon as 't besteet",
'tooltip-search-fulltext'         => "De pagina's vuur disse tekst zeukn",
'tooltip-p-logo'                  => 'Vuurziede',
'tooltip-n-mainpage'              => 'Goa noar de vuurziede',
'tooltip-n-portal'                => 'Informoasie oaver t projekt: wel, wat, hoo en woarum',
'tooltip-n-currentevents'         => 'Achtergroondinformoasie oaver dinge in t niejs',
'tooltip-n-recentchanges'         => 'Lieste van pas verrichte veraanderingn',
'tooltip-n-randompage'            => 'Loat ne willekeurige bladziede zeen',
'tooltip-n-help'                  => 'Hölpinformoasie oaver {{SITENAME}}',
'tooltip-t-whatlinkshere'         => 'Lieste van alle bladziedn dee hiernoar verwiezn',
'tooltip-t-recentchangeslinked'   => 'Pas verrichte veraanderingn dee noar disse bladziede verwiezn',
'tooltip-feed-rss'                => 'Rss-feed vuur disse bladziede',
'tooltip-feed-atom'               => 'Atom-feed vuur disse bladziede',
'tooltip-t-contributions'         => 'Lieste met biejdreagn van disse gebroeker',
'tooltip-t-emailuser'             => 'Stuur disse gebroeker n iejmeel',
'tooltip-t-upload'                => 'Laad ofbeeldingn en/of geluudsmateriaal',
'tooltip-t-specialpages'          => 'Lieste van alle biejzeundere bladziedn',
'tooltip-t-print'                 => 'De ofdrukboare versie van disse bladziede',
'tooltip-t-permalink'             => 'Verbeending vuur altied noar de versie van disse bladziede van vandaag-an-n-dag',
'tooltip-ca-nstab-main'           => 'Loat n tekst van t artikel zeen',
'tooltip-ca-nstab-user'           => 'Loat de gebroekersbladziede zeen',
'tooltip-ca-nstab-media'          => 'Loat n mediatekst zeen',
'tooltip-ca-nstab-special'        => "Dit is ne biejzeundere bladziede dee'j nich könt veraandern",
'tooltip-ca-nstab-project'        => 'Loat de projektbladziede zeen',
'tooltip-ca-nstab-image'          => 'Loat de ofbeeldingnbladziede zeen',
'tooltip-ca-nstab-mediawiki'      => 'Loat de systeemtekstbladziede zeen',
'tooltip-ca-nstab-template'       => 'Loat de malbladziede zeen',
'tooltip-ca-nstab-help'           => 'Loat de hölpbladziede zeen',
'tooltip-ca-nstab-category'       => 'Loat de rubriekbladziede zeen',
'tooltip-minoredit'               => 'Markeer as een kleine wieziging',
'tooltip-save'                    => 'Wiezigingen opslaon',
'tooltip-preview'                 => "Bekiek joew versie veurda-j 't opslaon (anbeveulen)!",
'tooltip-diff'                    => 'Teun de deur joe an-ebröchen wiezigingen.',
'tooltip-compareselectedversions' => 'Teun de verschillen tussen de ekeuzen versies.',
'tooltip-watch'                   => 'Voeg disse pagina toe an joew volglieste',
'tooltip-recreate'                => "Disse pagina opniej anmaken, ondanks 't feit dat 't vort-edaon is.",
'tooltip-upload'                  => 'Bestaandn toovoogn',
'tooltip-rollback'                => 'Mit "weerummedreien" ku-j mit één klik de bewarking(en) van de leste gebruker dee disse pagina bewark hef weerummezetten.',
'tooltip-undo'                    => 'A-j op "weerummedreien" klikken geet \'t bewarkingsvienster los en ku-j de veurige versie weerummezetten.
Je kunnen in de bewarkingssamenvatting een rejen opgeven.',

# Metadata
'nodublincore'      => 'Dublin Core RDF-metadata is uut-eschakeld op disse server.',
'nocreativecommons' => 'Creative Commons RDF-metadata is uut-eschakeld op disse server.',
'notacceptable'     => 'De wikiserver kan de gegevens neet leveren in een vorm dee joew cliënt kan lezen.',

# Attribution
'anonymous'        => 'Annenieme {{PLURAL:$1|gebruker|gebrukers}} van {{SITENAME}}',
'siteuser'         => '{{SITENAME}}-gebruker $1',
'lastmodifiedatby' => "Disse pagina is 't les ewiezig op $2, $1 deur $3.", # $1 date, $2 time, $3 user
'othercontribs'    => 'Ebaseerd op wark van $1.',
'others'           => 'aandere',
'siteusers'        => '{{SITENAME}}-{{PLURAL:$2|gebruker|gebrukers}}  $1',
'creditspage'      => 'Pagina-auteurs',
'nocredits'        => 'Der is gien auteursinfermasie beschikbaor veur disse pagina.',

# Spam protection
'spamprotectiontitle' => 'Spamfilter',
'spamprotectiontext'  => 'De pagina dee-j opslaon wollen is eblokkeerd deur de ongewunsteverwiezingsfilter. 
Meestentieds wonnen dit veroorzaak deur een uutgaonde verwiezing dee op de zwarte lieste steet.',
'spamprotectionmatch' => 'Disse tekse zörgen derveur dat onze spamfilter alarmsleug: $1',
'spambot_username'    => 'MediaWiki keren van ongewunste toevoegingen',
'spam_reverting'      => "Bezig mit 't weerummezetten naor de leste versie dee gien verwiezing hef naor $1",
'spam_blanking'       => 'Alle wiezigingen mit een verwiezing naor $1 wonnen vort-ehaold',

# Info page
'infosubtitle'   => 'Infermasie veur disse pagina',
'numedits'       => 'Antal bewarkingen (artikel): $1',
'numtalkedits'   => 'Antal bewarkingen (overlegpagina): $1',
'numwatchers'    => 'Antal volgers: $1',
'numauthors'     => 'Antal verschillende auteurs (artikel): $1',
'numtalkauthors' => 'Antal verschillende auteurs (overlegpagina): $1',

# Skin names
'skinname-standard'    => 'Klassiek',
'skinname-nostalgia'   => 'Nostalgie',
'skinname-cologneblue' => 'Keuls blauw',
'skinname-monobook'    => 'Monobook',
'skinname-myskin'      => 'MienSkin',
'skinname-chick'       => 'Deftig',
'skinname-simple'      => 'Eenvoudig',
'skinname-modern'      => 'Medern',

# Math options
'mw_math_png'    => 'Altied as PNG weergeven',
'mw_math_simple' => 'HTML veur eenvoudige formules, aanders PNG',
'mw_math_html'   => "HTML as 't meugelijk is, aanders PNG",
'mw_math_source' => 'Laot TeX-broncode staon (veur teksblaojeraars)',
'mw_math_modern' => 'Anbeveulen methode veur niejere webkiekers',
'mw_math_mathml' => 'MathML',

# Patrolling
'markaspatrolleddiff'                 => 'Markeer as econtreleerd',
'markaspatrolledtext'                 => 'Disse pagina is emarkeerd as econtreleerd',
'markedaspatrolled'                   => 'Emarkeerd as econtreleerd',
'markedaspatrolledtext'               => 'De ekeuzen versie is emarkeerd as econtreleerd.',
'rcpatroldisabled'                    => 'De controlemeugelijkheid op leste wiezigingen is uut-eschakeld.',
'rcpatroldisabledtext'                => 'De meugelijkheid um de leste wiezigingen as econtreleerd te markeren is op hejen uut-eschakeld.',
'markedaspatrollederror'              => 'De bewarking kon neet of-evink wonnen.',
'markedaspatrollederrortext'          => "Je mutten een wieziging sillecteren um 't as nao-ekeken te markeren.",
'markedaspatrollederror-noautopatrol' => 'Je maggen joew eigen bewarkingen neet as econtreleerd markeren.',

# Patrol log
'patrol-log-page'      => 'Markeerlogboek',
'patrol-log-header'    => 'In dit logboek staon de versies dee emarkeerd bin as econtreleerd.',
'patrol-log-line'      => '$1 van $2 emarkeerd as econtreleerd $3',
'patrol-log-auto'      => '(autematisch)',
'patrol-log-diff'      => 'versie $1',
'log-show-hide-patrol' => 'Markeerlogboek $1',

# Image deletion
'deletedrevision'                 => 'Vort-edaone ouwe versie $1.',
'filedeleteerror-short'           => "Fout bie 't vortdoon van bestaand: $1",
'filedeleteerror-long'            => "Der wanen fouten bie 't vortdoon van 't bestaand:

$1",
'filedelete-missing'              => '\'t Bestaand "$1" kan neet vort-edaon wonnen, umdat \'t neet besteet.',
'filedelete-old-unregistered'     => 'De an-egeven bestaansversie "$1" steet neet in de databanke.',
'filedelete-current-unregistered' => '\'t An-egeven bestaand "$1" steet neet in de databanke.',
'filedelete-archive-read-only'    => 'De webserver kan neet in de archiefmap "$1" schrieven.',

# Browsing diffs
'previousdiff' => '← veurige wieziging',
'nextdiff'     => 'volgende wieziging →',

# Visual comparison
'visual-comparison' => 'Visuele vergelieking',

# Media information
'mediawarning'         => "'''Waorschuwing:''' dit bestaand bevat meschien codering dee slich is veur 't systeem. <hr />",
'imagemaxsize'         => 'Grootte van ofbeeldingen beteunen:',
'thumbsize'            => "Grootte van 't ofbeeldingsoverzichte (thumbnail):",
'widthheightpage'      => "$1×$2, $3 {{PLURAL:$3|pagina|pagina's}}",
'file-info'            => 'Bestaansgrootte: $1, MIME-type: $2',
'file-info-size'       => '($1 × $2 beeldpunten, bestaansgrootte: $3, MIME-type: $4)',
'file-nohires'         => '<small>Gien hogere resolusie beschikbaor.</small>',
'svg-long-desc'        => '(SVG-bestaand, uutgangsgrootte $1 × $2 beeldpunten, bestaansgrootte: $3)',
'show-big-image'       => 'Ofbeelding in hogere resolusie',
'show-big-image-thumb' => '<small>Grootte van disse weergave: $1 × $2 beeldpunten</small>',

# Special:NewFiles
'newimages'             => 'Nieje ofbeeldingen',
'imagelisttext'         => "Hier volg een lieste mit '''$1''' {{PLURAL:$1|bestaand|bestanen}} esorteerd $2.",
'newimages-summary'     => 'Op disse speciale pagina staon de bestanen dee der as les bie-ekeumen bin.',
'newimages-legend'      => 'Bestaansnaam',
'newimages-label'       => 'Bestaansnaam (of deel dervan):',
'showhidebots'          => '(Bots $1)',
'noimages'              => 'Niks te zien.',
'ilsubmit'              => 'Zeuk',
'bydate'                => 'op daotum',
'sp-newimages-showfrom' => 'Teun nieje ofbeeldingen vanof $1, $2',

# Bad image list
'bad_image_list' => "De opmaak is as volg:

Allinnig regels in een lieste (regels dee beginnen mit *) wonnen verwark. 
De eerste verwiezing op een regel mut een verwiezing ween naor een ongewunste ofbeelding.
Alle volgende verwiezingen dee op dezelfde regel staon, wonnen behaandeld as uutzundering, zoas pagina's waorop de ofbeelding in te tekse op-eneumen is.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Dit bestaand bevat metadata mit EXIF-infermasie, dee deur een fotocamera, scanner of fotobewarkingspregramma toe-evoeg kan ween.',
'metadata-expand'   => 'Teun uut-ebreien gegevens',
'metadata-collapse' => 'Verbarg uut-ebreien gegevens',
'metadata-fields'   => 'EXIF-gegevens dee zichbaor bin as de tebel in-eklap is. De overige gegevens bin zichbaor as de tebel uut-eklap is, nao \'t klikken op "Teun uut-ebreien gegevens".
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'                  => 'Wiedte',
'exif-imagelength'                 => 'Heugte',
'exif-bitspersample'               => 'Bits per compenent',
'exif-compression'                 => 'Compressiemethode',
'exif-photometricinterpretation'   => 'Beeldpuntsamenstelling',
'exif-orientation'                 => 'Oriëntasie',
'exif-samplesperpixel'             => 'Antal compenenten',
'exif-planarconfiguration'         => 'Gegevensstructuur',
'exif-ycbcrsubsampling'            => 'Subsamplingsverhouwige van Y tot C',
'exif-ycbcrpositioning'            => 'Y- en C-posisionering',
'exif-xresolution'                 => 'Horizontale reselusie',
'exif-yresolution'                 => 'Verticale reselusie',
'exif-resolutionunit'              => 'Eenheid van de oplossing X en Y',
'exif-stripoffsets'                => 'Lokasie ofbeeldingsgegevens',
'exif-rowsperstrip'                => 'Riejen per strip',
'exif-stripbytecounts'             => 'Bytes per ecomprimeren strip',
'exif-jpeginterchangeformat'       => 'Ofstand tot JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bytes van JPEG-gegevens',
'exif-transferfunction'            => 'Overdrachsfunctie',
'exif-whitepoint'                  => 'Witpuntchromaticiteit',
'exif-primarychromaticities'       => 'Chromaciteit van primaire kleuren',
'exif-ycbcrcoefficients'           => 'Transfermasiematrixcoëfficiënten veur de kleurruumte',
'exif-referenceblackwhite'         => 'Rifferentieweerden veur zwart/wit',
'exif-datetime'                    => 'Tiedstip van digitalisasie',
'exif-imagedescription'            => 'Ofbeeldingnaam',
'exif-make'                        => 'Camera-mark',
'exif-model'                       => 'Camera-medel',
'exif-software'                    => 'Pregrammetuur dee gebruuk wönnen',
'exif-artist'                      => 'Eschreven deur',
'exif-copyright'                   => 'Auteursrechenhouwer',
'exif-exifversion'                 => 'Exif-versie',
'exif-flashpixversion'             => 'Ondersteunen Flashpix-versie',
'exif-colorspace'                  => 'Kleurruumte',
'exif-componentsconfiguration'     => 'Betekenisse van elk compenent',
'exif-compressedbitsperpixel'      => 'Beeldcompressiemethode',
'exif-pixelydimension'             => 'Bruukbaore ofbeeldingsbreedte',
'exif-pixelxdimension'             => 'Bruukbaore ofbeeldingsheugte',
'exif-makernote'                   => 'Notities van de fabrikant',
'exif-usercomment'                 => 'Opmarkingen',
'exif-relatedsoundfile'            => 'Biebeheurend geluudsbestaand',
'exif-datetimeoriginal'            => 'Tiedstip van datagenerasie',
'exif-datetimedigitized'           => 'Tiedstip van digitalisasie',
'exif-subsectime'                  => 'Subseconden tiedstip bestaanswieziging',
'exif-subsectimeoriginal'          => 'Subseconden tiedstip dataginnerasie',
'exif-subsectimedigitized'         => 'Subseconden tiedstip digitalisasie',
'exif-exposuretime'                => 'Belochtingstied',
'exif-exposuretime-format'         => '$1 sec ($2)',
'exif-fnumber'                     => 'F-getal',
'exif-exposureprogram'             => 'Belochtingspregramma',
'exif-spectralsensitivity'         => 'Spectrale geveuligheid',
'exif-isospeedratings'             => 'ISO-weerde.',
'exif-oecf'                        => 'Opto-elektronische conversiefactor',
'exif-shutterspeedvalue'           => 'Slutersnelheid',
'exif-aperturevalue'               => 'Diafragma',
'exif-brightnessvalue'             => 'Helderheid',
'exif-exposurebiasvalue'           => 'Belochtingscompensasie',
'exif-maxaperturevalue'            => 'Maximale diafragmaweerde van de lenze',
'exif-subjectdistance'             => 'Ofstand tot onderwarp',
'exif-meteringmode'                => 'Methode lochmeting',
'exif-lightsource'                 => 'Lochbron',
'exif-flash'                       => 'Flitser',
'exif-focallength'                 => 'Braandpuntofstand',
'exif-subjectarea'                 => 'Objekruumte',
'exif-flashenergy'                 => 'Flitserstarkte',
'exif-spatialfrequencyresponse'    => 'Ruumtelijke frequentiereactie',
'exif-focalplanexresolution'       => 'X-resolutie van CDD',
'exif-focalplaneyresolution'       => 'Y-resolutie van CCD',
'exif-focalplaneresolutionunit'    => 'Eenheid CCD-resolusie',
'exif-subjectlocation'             => 'Objeklokasie',
'exif-exposureindex'               => 'Belochtingindex',
'exif-sensingmethod'               => 'Meetmethode',
'exif-filesource'                  => 'Bestaansnaam op de hardeschieve',
'exif-scenetype'                   => 'Scènetype',
'exif-cfapattern'                  => 'CFA-petroon',
'exif-customrendered'              => 'An-epaste beeldbewarking',
'exif-exposuremode'                => 'Belochtingsinstelling',
'exif-whitebalance'                => 'Witbelans',
'exif-digitalzoomratio'            => 'Digitale zoomfactor',
'exif-focallengthin35mmfilm'       => 'Braandpuntofstand (35mm-equivalent)',
'exif-scenecapturetype'            => 'Soort opname',
'exif-gaincontrol'                 => 'Piekbeheersing',
'exif-contrast'                    => 'Kontras',
'exif-saturation'                  => 'Verzaojiging',
'exif-sharpness'                   => 'Scharpte',
'exif-devicesettingdescription'    => 'Umschrieving apperaotinstellingen',
'exif-subjectdistancerange'        => 'Ofstanskattegerie',
'exif-imageuniqueid'               => 'Unieke ID-ofbeelding',
'exif-gpsversionid'                => 'GPS-versienummer',
'exif-gpslatituderef'              => 'Noorder- of zujerbreedte',
'exif-gpslatitude'                 => 'Breedte',
'exif-gpslongituderef'             => 'Ooster- of westerlengte',
'exif-gpslongitude'                => 'Lengtegraod',
'exif-gpsaltituderef'              => 'Heugterifferentie',
'exif-gpsaltitude'                 => 'Heugte',
'exif-gpstimestamp'                => 'GPS-tied (atoomklokke)',
'exif-gpssatellites'               => 'Satellieten dee gebruuk bin veur de meting',
'exif-gpsstatus'                   => 'Ontvangerstaotus',
'exif-gpsmeasuremode'              => 'Meetmodus',
'exif-gpsdop'                      => 'Meetprecisie',
'exif-gpsspeedref'                 => 'Snelheidseenheid',
'exif-gpsspeed'                    => 'Snelheid van GPS-ontvanger',
'exif-gpstrackref'                 => 'Referentie veur bewegingsrichting',
'exif-gpstrack'                    => 'Bewegingsrichting',
'exif-gpsimgdirectionref'          => 'Referentie veur ofbeeldingsrichting',
'exif-gpsimgdirection'             => 'Ofbeeldingsrichting',
'exif-gpsmapdatum'                 => 'Geodetische onderzeuksgegevens dee gebruuk bin',
'exif-gpsdestlatituderef'          => 'Rifferentie veur breedtegraod tot bestemming',
'exif-gpsdestlatitude'             => 'Breedtegraod bestemming',
'exif-gpsdestlongituderef'         => 'Rifferentie veur lengtegraod bestemming',
'exif-gpsdestlongitude'            => 'Lengtegraod bestemming',
'exif-gpsdestbearingref'           => 'Rifferentie veur richting naor bestemming',
'exif-gpsdestbearing'              => 'Richting naor bestemming',
'exif-gpsdestdistanceref'          => 'Rifferentie veur ofstand tot bestemming',
'exif-gpsdestdistance'             => 'Ofstand tot bestemming',
'exif-gpsprocessingmethod'         => 'Naam van de GPS-verwarkingsmethode',
'exif-gpsareainformation'          => "Naam van 't GPS-gebied",
'exif-gpsdatestamp'                => 'GPS-daotum',
'exif-gpsdifferential'             => 'Differentiële GPS-correctie',

# EXIF attributes
'exif-compression-1' => 'Neet ecomprimeerd',

'exif-unknowndate' => 'Onbekende daotum',

'exif-orientation-1' => 'Normaal', # 0th row: top; 0th column: left
'exif-orientation-2' => 'horizontaal espegeld', # 0th row: top; 0th column: right
'exif-orientation-3' => '180° edreid', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'verticaal edreid', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'espegeld um as linksboven-rechsonder', # 0th row: left; 0th column: top
'exif-orientation-6' => '90° rechsummedreid', # 0th row: right; 0th column: top
'exif-orientation-7' => '90° linksummedreid', # 0th row: right; 0th column: bottom
'exif-orientation-8' => '90° linksummedreid', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'Grof gegevensformaot',
'exif-planarconfiguration-2' => 'planar gegevensformaot',

'exif-componentsconfiguration-0' => 'besteet neet',

'exif-exposureprogram-0' => 'Neet umschreven',
'exif-exposureprogram-1' => 'Haandmaotig',
'exif-exposureprogram-2' => 'Normaal',
'exif-exposureprogram-3' => 'Diafragmaprioriteit',
'exif-exposureprogram-4' => 'Sluterprioriteit',
'exif-exposureprogram-5' => 'Creatief (veurkeur veur grote scharptediepte)',
'exif-exposureprogram-6' => 'Actie (veurkeur veur hoge slutersnelheid)',
'exif-exposureprogram-7' => 'pertret (detailopname mit onscharpe achtergrond)',
'exif-exposureprogram-8' => 'laandschap (scharpe achtergrond)',

'exif-subjectdistance-value' => '$1 m',

'exif-meteringmode-0'   => 'Onbekend',
'exif-meteringmode-1'   => 'Gemiddeld',
'exif-meteringmode-2'   => 'Gemiddeld, naodrok op midden',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'MultiSpot',
'exif-meteringmode-5'   => 'Multi-segment (petroon)',
'exif-meteringmode-6'   => 'Deelmeting',
'exif-meteringmode-255' => 'Aanders',

'exif-lightsource-0'   => 'Onbekend',
'exif-lightsource-1'   => 'Dagloch',
'exif-lightsource-2'   => 'Tl-loch',
'exif-lightsource-3'   => 'Tungsten (lamploch)',
'exif-lightsource-4'   => 'Flitser',
'exif-lightsource-9'   => 'Mooi weer',
'exif-lightsource-10'  => 'Bewolk',
'exif-lightsource-11'  => 'Schaoduw',
'exif-lightsource-12'  => 'Fluorescerend dagloch (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Witfluorescerend dagloch (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Koel witfluorescerend (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Witfluorescerend (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Standardloch A',
'exif-lightsource-18'  => 'Standardloch B',
'exif-lightsource-19'  => 'Standardloch C',
'exif-lightsource-24'  => 'ISO-studiokunsloch',
'exif-lightsource-255' => 'Aanders',

# Flash modes
'exif-flash-fired-0'    => 'Flits is neet of-egaon',
'exif-flash-fired-1'    => 'Mit flitser',
'exif-flash-return-0'   => 'flits zend gien gegevens',
'exif-flash-return-2'   => 'gien weerkaotsing van de flits vastesteld',
'exif-flash-return-3'   => 'weerkaotsing van de flits vastesteld',
'exif-flash-mode-1'     => 'verplich mit flitser',
'exif-flash-mode-2'     => 'flitser verplich onderdrok',
'exif-flash-mode-3'     => 'autematische modus',
'exif-flash-function-1' => 'Gien flitserfunctie',
'exif-flash-redeye-1'   => 'Rooie ogen-filter',

'exif-focalplaneresolutionunit-2' => 'duum',

'exif-sensingmethod-1' => 'Neet vastesteld',
'exif-sensingmethod-2' => 'Eén-chip-kleursensor',
'exif-sensingmethod-3' => 'Twee-chips-kleursensor',
'exif-sensingmethod-4' => 'Dree-chips-kleurensensor',
'exif-sensingmethod-5' => 'Kleurvolgende gebiedssensor',
'exif-sensingmethod-7' => 'Dreeliendige sensor',
'exif-sensingmethod-8' => 'Kleurvolgende liendesensor',

'exif-scenetype-1' => 'Een drek efotograferen ofbeelding',

'exif-customrendered-0' => 'Normaal',
'exif-customrendered-1' => 'An-epas',

'exif-exposuremode-0' => 'Autematisch',
'exif-exposuremode-1' => 'Haandmaotig',
'exif-exposuremode-2' => 'Autobracket',

'exif-whitebalance-0' => 'Autematisch',
'exif-whitebalance-1' => 'Haandmaotig',

'exif-scenecapturetype-0' => 'standard',
'exif-scenecapturetype-1' => 'laandschap',
'exif-scenecapturetype-2' => 'pertret',
'exif-scenecapturetype-3' => 'nachscène',

'exif-gaincontrol-0' => 'Gien',
'exif-gaincontrol-1' => 'Lege pieken umhoge',
'exif-gaincontrol-2' => 'Hoge pieken umhoge',
'exif-gaincontrol-3' => 'Lege pieken ummeneer',
'exif-gaincontrol-4' => 'Hoge pieken ummeneer',

'exif-contrast-0' => 'Normaal',
'exif-contrast-1' => 'Zachte',
'exif-contrast-2' => 'Hard',

'exif-saturation-0' => 'Normaal',
'exif-saturation-1' => 'Leeg',
'exif-saturation-2' => 'Hoge',

'exif-sharpness-0' => 'Normaal',
'exif-sharpness-1' => 'Zach',
'exif-sharpness-2' => 'Hard',

'exif-subjectdistancerange-0' => 'Onbekend',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Kortbie',
'exif-subjectdistancerange-3' => 'Veerof',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Noorderbreedte',
'exif-gpslatitude-s' => 'Zujerbreedte',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Oosterlengte',
'exif-gpslongitude-w' => 'Westerlengte',

'exif-gpsstatus-a' => 'Bezig mit meten',
'exif-gpsstatus-v' => 'Meetinteroperebiliteit',

'exif-gpsmeasuremode-2' => '2-dimensionale meting',
'exif-gpsmeasuremode-3' => '3-dimensionale meting',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilemeter per uur',
'exif-gpsspeed-m' => 'Miel per ure',
'exif-gpsspeed-n' => 'Knopen',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Waore richting',
'exif-gpsdirection-m' => 'Magnetische richting',

# External editor support
'edit-externally'      => 'Wiezig dit bestaand mit een extern pregramma',
'edit-externally-help' => '(zie de [http://www.mediawiki.org/wiki/Manual:External_editors instellasie-instructies] veur meer infermasie)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'alles',
'imagelistall'     => 'alles',
'watchlistall2'    => 'alles',
'namespacesall'    => 'alles',
'monthsall'        => 'alles',

# E-mail address confirmation
'confirmemail'             => 'Bevestig netposadres',
'confirmemail_noemail'     => 'Je hemmen gien geldig netposadres in-evoerd in joew [[Special:Preferences|veurkeuren]].',
'confirmemail_text'        => 'Bie disse wiki mu-j je netposadres bevestigen veurda-j de berichopties gebruken kunnen. Klik op de onderstaonde knoppe um een bevestigingsberich te ontvangen. Dit berich bevat een code mit een verwiezing; um je netposadres te bevestigen mu-j disse verwiezing los doon.',
'confirmemail_pending'     => 'Der is al een bevestigingscode op-estuurd; a-j net een gebrukersnaam an-emaak hemmen, wach dan eers een paor menuten tot da-j dit berich ontvungen hemmen veurda-j een nieje code anvragen.',
'confirmemail_send'        => 'Stuur een bevestigingscode',
'confirmemail_sent'        => 'Bevestigingsberich verstuurd.',
'confirmemail_oncreate'    => "Een bevestigingscode is naor joew netposadres verstuurd. Disse code is neet neudig um an te melden, mar je mutten 't wel bevestigen veurda-j de netposmeugelijkheen van disse wiki gebruken kunnen.",
'confirmemail_sendfailed'  => "{{SITENAME}} kon joe gien bevestigingscode toesturen.
Contreleer joew netposadres op ongeldige tekens.

Fout bie 't versturen: $1",
'confirmemail_invalid'     => 'Ongeldige bevestigingscode. De code kan verlopen ween.',
'confirmemail_needlogin'   => 'Je mutten $1 um joew netposadres te bevestigen.',
'confirmemail_success'     => 'Joew netposadres is bevestig. Je kunnen noen anmelden en {{SITENAME}} gebruken.',
'confirmemail_loggedin'    => 'Joew netposadres is noen bevestig.',
'confirmemail_error'       => "Der is iets fout egaon bie 't opslaon van joew bevestiging.",
'confirmemail_subject'     => 'Bevestiging netposadres veur {{SITENAME}}',
'confirmemail_body'        => 'Ene mit IP-adres $1, werschienlijk jie zelf, hef zien eigen mit dit netposadres eregistreerd as de gebruker "$2" op {{SITENAME}}.

Klik op de volgende verwiezing um te bevestigen da-jie disse gebruker bin en um de netposmeugelijkheen op {{SITENAME}} te activeren:

$3

A-j joe eigen *neet* an-emeld hemmen, klik dan neet op disse verwiezing um de bevestiging van joew netposadres of te breken:

$5

De bevestigingscode zal verlopen op $4.',
'confirmemail_invalidated' => 'De netposbevestiging is of-ebreuken',
'invalidateemail'          => 'Netposbevestiging ofbreken',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki-intergrasie is edeactiveerd]',
'scarytranscludefailed'   => '[De mal $1 kon neet op-ehaold wonnen]',
'scarytranscludetoolong'  => '[URL is te lang]',

# Trackbacks
'trackbackbox'      => 'Trackbacks veur disse pagina:<br />
$1',
'trackbackremove'   => '([$1 vortdoon])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'De trackback is vort-edaon.',

# Delete conflict
'deletedwhileediting' => "'''Waorschuwing''': disse pagina is vort-edaon terwiel jie 't an 't bewarken wanen!",
'confirmrecreate'     => "Gebruker [[User:$1|$1]] ([[User talk:$1|Overleg]]) hef disse pagina vort-edaon naoda-j  begunnen bin mit joew wieziging, mit opgave van de volgende rejen: ''$2''. Bevestig da-j 't artikel herschrieven willen.",
'recreate'            => 'Herschrieven',

# action=purge
'confirm_purge_button' => 'Bevestig',
'confirm-purge-top'    => "Klik op 'bevestig' um de kas van disse pagina te legen.",
'confirm-purge-bottom' => "'t Leegmaken van de kas zörg derveur da-j de leste versie van een pagina zien.",

# Multipage image navigation
'imgmultipageprev' => '&larr; veurige',
'imgmultipagenext' => 'volgende &rarr;',
'imgmultigo'       => 'Oké',
'imgmultigoto'     => 'Gao naor de pagina $1',

# Table pager
'ascending_abbrev'         => 'daol',
'descending_abbrev'        => 'stieg',
'table_pager_next'         => 'Volgende',
'table_pager_prev'         => 'Veurige',
'table_pager_first'        => 'Eerste pagina',
'table_pager_last'         => 'Leste pagina',
'table_pager_limit'        => 'Teun $1 onderwarpen per pagina',
'table_pager_limit_submit' => 'Zeuk',
'table_pager_empty'        => 'Gien risseltaoten',

# Auto-summaries
'autosumm-blank'   => 'Pagina leeg-emaak',
'autosumm-replace' => "Tekse vervungen deur '$1'",
'autoredircomment' => 'deurverwiezing naor [[$1]]',
'autosumm-new'     => "Nieje pagina: '$1'",

# Live preview
'livepreview-loading' => "An 't laojen…",
'livepreview-ready'   => "An 't laojen… ree!",
'livepreview-failed'  => 'Rechstreeks naokieken is neet meugelijk!
Kiek de pagina op de normale meniere nao.',
'livepreview-error'   => 'Verbiending neet meugelijk: $1 "$2"
Kiek de pagina op de normale meniere nao.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Wiezigingen dee niejer bin as $1 {{PLURAL:$1|seconde|seconden}} staon meschien nog neet in de lieste.',
'lag-warn-high'   => 'De databanke is aorig zwaor belas. Wiezigingen dee niejer bin as $1 {{PLURAL:$1|seconde|seconden}} staon daorumme meschien nog neet in de lieste.',

# Watchlist editor
'watchlistedit-numitems'       => "Der {{PLURAL:$1|steet 1 pagina|staon $1 pagina's}} op joew volglieste, zonder overlegpagina's.",
'watchlistedit-noitems'        => 'Joew volglieste is leeg.',
'watchlistedit-normal-title'   => 'Volglieste bewarken',
'watchlistedit-normal-legend'  => "Disse pagina's van mien volglieste ofhaolen.",
'watchlistedit-normal-explain' => "Pagina's op joew volglieste wonnen hieronder weer-egeven.
Um een pagina van joew volglieste of te haolen mu-j 't vakjen dernaos sillekteren, en klik dan op 'Pagina's derof haolen'.
Je kunnen oek [[Special:Watchlist/raw|de roege lieste bewarken]].",
'watchlistedit-normal-submit'  => "Pagina's derof haolen",
'watchlistedit-normal-done'    => "Der {{PLURAL:$1|is 1 pagina|bin $1 pagina's}} vort-edaon uut joew volglieste:",
'watchlistedit-raw-title'      => 'Roewe volglieste bewarken',
'watchlistedit-raw-legend'     => 'Roewe volglieste bewarken',
'watchlistedit-raw-explain'    => "Hieronder staon pagina’s op joew volglieste. Je kunnen de lieste bewarken deur pagina’s deruut vort te haolen en derbie te te doon. 
Eén pagina per regel.
A-j ree bin, klik dan op ‘Volglieste biewarken’.
Je kunnen oek [[Special:Watchlist/edit|'t standard bewarkingsscharm gebruken]].",
'watchlistedit-raw-titles'     => 'Titels:',
'watchlistedit-raw-submit'     => 'Volglieste biewarken',
'watchlistedit-raw-done'       => 'Joew volglieste is bie-ewörk.',
'watchlistedit-raw-added'      => "Der {{PLURAL:$1|is 1 pagina|bin $1 pagina's}} bie edaon:",
'watchlistedit-raw-removed'    => "Der {{PLURAL:$1|is 1 pagina|bin $1 pagina's}} vort-edaon:",

# Watchlist editing tools
'watchlisttools-view' => 'Wiezigingen bekieken',
'watchlisttools-edit' => 'Volglieste bekieken en bewarken',
'watchlisttools-raw'  => 'Roewe volglieste bewarken',

# Core parser functions
'unknown_extension_tag' => 'Onbekende tag "$1"',
'duplicate-defaultsort' => 'Waorschuwing: De standardsortering "$2" krieg veurrang veur de sortering "$1".',

# Special:Version
'version'                          => 'Versie', # Not used as normal message but as header for the special page itself
'version-extensions'               => 'Eïnstalleren uutbreidingen',
'version-specialpages'             => "Speciale pagina's",
'version-parserhooks'              => 'Parserhooks',
'version-variables'                => 'Variabels',
'version-other'                    => 'Overige',
'version-mediahandlers'            => 'Mediaverwarkers',
'version-hooks'                    => 'Hooks',
'version-extension-functions'      => 'Uutbreidingsfuncties',
'version-parser-extensiontags'     => 'Parseruutbreidingsplaotjes',
'version-parser-function-hooks'    => 'Parserfunctiehooks',
'version-skin-extension-functions' => 'Vormgevingsuutbreidingsfuncties',
'version-hook-name'                => 'Hooknaam',
'version-hook-subscribedby'        => 'Eabonneerd deur',
'version-version'                  => 'Versie',
'version-license'                  => 'Licentie',
'version-software'                 => 'Eïnstalleren pregrammetuur',
'version-software-product'         => 'Preduk',
'version-software-version'         => 'Versie',

# Special:FilePath
'filepath'         => 'Bestaanslokasie',
'filepath-page'    => 'Bestaand:',
'filepath-submit'  => 'Zeuken',
'filepath-summary' => "Disse speciale pagina geef 't hele pad veur een bestaand. Ofbeeldingen wonnen in resolusie helemaole weer-egeven. Aandere bestaanstypen wonnen gelieke in 't mit 't MIME-type verbunnen pregramma los edaon.

Voer de bestaansnaam in zonder 't veurvoegsel \"{{ns:file}}:\".",

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Dubbele bestanen zeuken',
'fileduplicatesearch-summary'  => 'Dubbele bestanen zeuken op baosis van de hashweerde.

Voer de bestaansnaam in zonder \'t veurvoegsel "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'Dubbele bestanen zeuken',
'fileduplicatesearch-filename' => 'Bestaansnaam:',
'fileduplicatesearch-submit'   => 'Zeuken',
'fileduplicatesearch-info'     => '$1 × $2 beeldpunten<br />Bestaansgrootte: $3<br />MIME-type: $4',
'fileduplicatesearch-result-1' => 'Der bin gien bestanen dee liekeleens bin as "$1".',
'fileduplicatesearch-result-n' => 'Der {{PLURAL:$2|is één bestaand|bin $2 bestanen}} dee liekeleens bin as "$1".',

# Special:SpecialPages
'specialpages'                   => "Speciale pagina's",
'specialpages-note'              => '----
* Normale speciale pagina\'s
* <strong class="mw-specialpagerestricted">Beteund toegankelijke speciale pagina\'s</strong>',
'specialpages-group-maintenance' => 'Onderhoudsliesten',
'specialpages-group-other'       => "Overige speciale pagina's",
'specialpages-group-login'       => 'Anmelden / inschrieven',
'specialpages-group-changes'     => 'Leste wiezigingen en logboeken',
'specialpages-group-media'       => 'Media-overzichen en toe-evoegen bestanen',
'specialpages-group-users'       => 'Gebrukers en rechen',
'specialpages-group-highuse'     => "Veulgebruken pagina's",
'specialpages-group-pages'       => 'Paginaliesten',
'specialpages-group-pagetools'   => 'Paginahulpmiddels',
'specialpages-group-wiki'        => 'Wikigegevens en -hulpmiddels',
'specialpages-group-redirects'   => "Deurverwiezende speciale pagina's",
'specialpages-group-spam'        => "Hulpmiddels tegen 't plaosen van moek",

# Special:BlankPage
'blankpage'              => 'Lege pagina',
'intentionallyblankpage' => 'Disse pagina is bewus leeg eleuten.',

# External image whitelist
'external_image_whitelist' => " #Laot disse regel onveraanderd<pre>
#Hieronder kunnen delen van regeliere uutdrokkingen ('t deel tussen //) an-egeven wonnen.
#'t Wonnen mit de webadressen van ofbeeldingen uut bronnen van butenof vergeleken
#Een positief vergeliekingsrisseltaot zörg derveur dat de ofbeelding weer-egeven wonnen, aanders wonnen de ofbeelding allinnig as verwiezing weer-egeven
#Regels dee mit een # beginnen, wonnen as commetaar behaandeld
#De regels in de lieste bin neet heuflettergeleuvig

#Delen van regeliere uutdrokkingen boven disse regel plaosen. Laot disse regel onveraanderd</pre>",

# Special:Tags
'tags'                    => 'Geldige wiezigingsetiketten',
'tag-filter'              => '[[Special:Tags|Etiketfilter]]:',
'tag-filter-submit'       => 'Filtreren',
'tags-title'              => 'Etiket',
'tags-intro'              => 'Op disse pagina staon de etiketten waormee de pregrammetuur elke bewarking kan markeren, en de betekenisse dervan.',
'tags-tag'                => 'Interne etiketnaam',
'tags-display-header'     => 'Weergave in wiezigingsliesten',
'tags-description-header' => 'Beschrieving van de betekenisse',
'tags-hitcount-header'    => 'Bewarkingen mit etiket',
'tags-edit'               => 'bewarking',
'tags-hitcount'           => '$1 {{PLURAL:$1|wieziging|wiezigingen}}',

# Database error messages
'dberr-header'      => 'Disse wiki hef een prebleem',
'dberr-problems'    => 'Onze verontschuldigingen. Disse webstee hef op hejen wat technische preblemen.',
'dberr-again'       => "Wach een paor menuten en prebeer 't daornao opniej.",
'dberr-info'        => '(Kan gien verbiending maken mit de databankeserver: $1)',
'dberr-usegoogle'   => 'Meschien ku-j ondertussen zeuken via Google.',
'dberr-outofdate'   => "Let op: indexen de zee hemmen van onze pagina's bin meschien neet actueel.",
'dberr-cachederror' => 'Disse pagina is een kepie uut de kas en is meschien neet de leste versie.',

);
