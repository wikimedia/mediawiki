<?php
/** Low Saxon (Netherlands) (Nedersaksies)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Erwin
 * @author Erwin85
 * @author Geitost
 * @author HanV
 * @author Jens Frank
 * @author Kaganer
 * @author Purodha
 * @author Servien
 * @author Slomox
 * @author Urhixidur
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author לערי ריינהארט
 */

$fallback = 'nl';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Spesiaal',
	NS_TALK             => 'Overleg',
	NS_USER             => 'Gebruker',
	NS_USER_TALK        => 'Overleg_gebruker',
	NS_PROJECT_TALK     => 'Overleg_$1',
	NS_FILE             => 'Bestaand',
	NS_FILE_TALK        => 'Overleg_bestaand',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Overleg_MediaWiki',
	NS_TEMPLATE         => 'Mal',
	NS_TEMPLATE_TALK    => 'Overleg_mal',
	NS_HELP             => 'Hulpe',
	NS_HELP_TALK        => 'Overleg_hulpe',
	NS_CATEGORY         => 'Kategorie',
	NS_CATEGORY_TALK    => 'Overleg_kategorie',
);

$namespaceAliases = array(
	'Speciaol'           => NS_SPECIAL,
	'Speciaal'           => NS_SPECIAL,
	'Sjabloon'           => NS_TEMPLATE,
	'Overleg_sjabloon'   => NS_TEMPLATE_TALK,
	'Ofbeelding'         => NS_FILE,
	'Overleg_ofbeelding' => NS_FILE_TALK,
	'Categorie'          => NS_CATEGORY,
	'Overleg_categorie'  => NS_CATEGORY_TALK,
	'Kattegerie'         => NS_CATEGORY,
	'Overleg_kattegerie' => NS_HELP_TALK,
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

#!!# Translation <b>HLEERSTE:</b> is used more than once for <a href="#mw-sp-magic-lcfirst">lcfirst</a> and <a href="#mw-sp-magic-ucfirst">ucfirst</a>.
$magicWords = array(
	'redirect'                  => array( '0', '#DEURVERWIEZING', '#DOORVERWIJZING', '#REDIRECT' ),
	'notoc'                     => array( '0', '__GIENONDERWARPEN__', '__GEENINHOUD__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__GIENGALLERIEJE__', '__GEEN_GALERIJ__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__FORSEERONDERWARPEN__', '__INHOUD_DWINGEN__', '__FORCEERINHOUD__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__ONDERWARPEN__', '__INHOUD__', '__TOC__' ),
	'noeditsection'             => array( '0', '__GIENBEWARKSEKSIE__', '__NIETBEWERKBARESECTIE__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'DISSEMAOND', 'HUIDIGEMAAND', 'HUIDIGEMAAND2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'DISSEMAONDNAAM', 'HUIDIGEMAANDNAAM', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'DISSEMAONDGEN', 'HUIDIGEMAANDGEN', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'DISSEMAONDAOFK', 'HUIDIGEMAANDAFK', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'DISSEDAG', 'HUIDIGEDAG', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'DISSEDAG2', 'HUIDIGEDAG2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'DISSEDAGNAAM', 'HUIDIGEDAGNAAM', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'DITJAOR', 'HUIDIGJAAR', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'DISSETIED', 'HUIDIGETIJD', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'DITURE', 'HUIDIGUUR', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'LOKALEMAOND', 'PLAATSELIJKEMAAND', 'LOKALEMAAND', 'LOKALEMAAND2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'LOKALEMAOND1', 'LOKALEMAAND1', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'LOKALEMAONDNAAM', 'PLAATSELIJKEMAANDNAAM', 'LOKALEMAANDNAAM', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'LOKALEMAONDNAAMGEN', 'PLAATSELIJKEMAANDNAAMGEN', 'LOKALEMAANDNAAMGEN', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'LOKALEMAONDAOFK', 'PLAATSELIJKEMAANDAFK', 'LOKALEMAANDAFK', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'LOKALEDAG', 'PLAATSELIJKEDAG', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'LOKALEDAG2', 'PLAATSELIJKEDAG2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'LOKALEDAGNAAM', 'PLAATSELIJKEDAGNAAM', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'LOKAALJAOR', 'PLAATSELIJKJAAR', 'LOKAALJAAR', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'LOKALETIED', 'PLAATSELIJKETIJD', 'LOKALETIJD', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'LOKAALURE', 'PLAATSELIJKUUR', 'LOKAALUUR', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'ANTALPAGINAS', 'ANTALPAGINA\'S', 'ANTALPAGINA’S', 'AANTALPAGINAS', 'AANTALPAGINA\'S', 'AANTALPAGINA’S', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'ANTALARTIKELS', 'AANTALARTIKELEN', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'ANTALBESTANDEN', 'AANTALBESTANDEN', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'ANTALGEBRUKERS', 'AANTALGEBRUIKERS', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'ANTALAKTIEVEGEBRUKERS', 'AANTALACTIEVEGEBRUIKERS', 'ACTIEVEGEBRUIKERS', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'ANTALBEWARKINGEN', 'AANTALBEWERKINGEN', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'ANTALKERENBEKEKEN', 'AANTALKERENBEKEKEN', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'PAGINANAAM', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'PAGINANAAME', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'NAAMRUUMTE', 'NAAMRUIMTE', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'NAAMRUUMTEE', 'NAAMRUIMTEE', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'OVERLEGRUUMTE', 'OVERLEGRUIMTE', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'OVERLEGRUUMTEE', 'OVERLEGRUIMTEE', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'ONDERWARPRUUMTE', 'ARTIKELRUUMTE', 'ONDERWERPRUIMTE', 'ARTIKELRUIMTE', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'ONDERWARPRUUMTEE', 'ARTIKELRUUMTEE', 'ONDERWERPRUIMTEE', 'ARTIKELRUIMTEE', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'HELEPAGINANAAM', 'VOLLEDIGEPAGINANAAM', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'HELEPAGINANAAME', 'VOLLEDIGEPAGINANAAME', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'DEELPAGINANAAM', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'DEELPAGINANAAME', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'BAOSISPAGINANAAM', 'BASISPAGINANAAM', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'BAOSISPAGINANAAME', 'BASISPAGINANAAME', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'OVERLEGPAGINANAAM', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'OVERLEGPAGINANAAME', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'ONDERWARPPAGINANAAM', 'ARTIKELPAGINANAAM', 'ONDERWERPPAGINANAAM', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'ONDERWARPPAGINANAAME', 'ARTIKELPAGINANAAME', 'ONDERWERPPAGINANAAME', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'BERICHT:', 'MSG:' ),
	'subst'                     => array( '0', 'VERVANG:', 'VERV:', 'SUBST:' ),
	'msgnw'                     => array( '0', 'BERICHTNW', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'miniatuur', 'duumnegel', 'doemnaegel', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'miniatuur=$1', 'duumnegel=$1', 'doemnaegel=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'rechts', 'right' ),
	'img_left'                  => array( '1', 'links', 'left' ),
	'img_none'                  => array( '1', 'gien', 'geen', 'none' ),
	'img_center'                => array( '1', 'esentreerd', 'gecentreerd', 'center', 'centre' ),
	'img_framed'                => array( '1', 'umraand', 'omkaderd', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'kaoderloos', 'kaderloos', 'frameless' ),
	'img_page'                  => array( '1', 'pagina=$1', 'pagina_$1', 'pagina $1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'rechtop', 'rechtop=$1', 'rechtop$1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'raand', 'rand', 'border' ),
	'img_baseline'              => array( '1', 'grondliende', 'grondlijn', 'baseline' ),
	'img_top'                   => array( '1', 'boven', 'top' ),
	'img_text_top'              => array( '1', 'tekste-boven', 'tekst-boven', 'text-top' ),
	'img_middle'                => array( '1', 'midden', 'middle' ),
	'img_bottom'                => array( '1', 'benejen', 'beneden', 'bottom' ),
	'img_text_bottom'           => array( '1', 'tekste-benejen', 'tekst-beneden', 'text-bottom' ),
	'img_link'                  => array( '1', 'verwiezing=$1', 'verwijzing=$1', 'koppeling=$1', 'link=$1' ),
	'sitename'                  => array( '1', 'WEBSTEENAAM', 'SITENAAM', 'SITENAME' ),
	'ns'                        => array( '0', 'NR:', 'NS:' ),
	'localurl'                  => array( '0', 'LOKALEURL', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'LOKALEURLE', 'LOCALURLE:' ),
	'servername'                => array( '0', 'SERVERNAAM', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'SKRIPTPAD', 'SCRIPTPAD', 'SCRIPTPATH' ),
	'stylepath'                 => array( '0', 'STIELPAD', 'STIJLPAD', 'STYLEPATH' ),
	'grammar'                   => array( '0', 'GRAMMATIKA:', 'GRAMMATICA:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'GESLACHTE:', 'GESLACHT:', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__GIENTITELKONVERSIE__', '__GIENTC__', '__GEENTITELCONVERSIE__', '__GEENTC__', '__GEENPAGINANAAMCONVERSIE__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__GIENINHOUDKONVERSIE__', '__GIENIC__', '__GEENINHOUDCONVERSIE__', '__GEENIC__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'DISSEWEKE', 'HUIDIGEWEEK', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'DISSEDVDW', 'HUIDIGEDVDW', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'LOKALEWEKE', 'PLAATSELIJKEWEEK', 'LOKALEWEEK', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'LOKALEDVDW', 'PLAATSELIJKEDVDW', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'REVISIEID', 'REVISIE-ID', 'VERSIEID', 'REVISIONID' ),
	'revisionday'               => array( '1', 'REVISIEDAG', 'VERSIEDAG', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'REVISIEDAG2', 'VERSIEDAG2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'REVISIEMAOND', 'VERSIEMAAND', 'REVISIONMONTH' ),
	'revisionyear'              => array( '1', 'REVISIEJAOR', 'VERSIEJAAR', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'REVISIETIEDSTEMPEL', 'VERSIETIJD', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', 'VERSIEGEBRUKER', 'VERSIEGEBRUIKER', 'REVISIONUSER' ),
	'plural'                    => array( '0', 'MEERVOUD:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'HELEURL', 'VOLLEDIGEURL', 'VOLLEDIGEURL:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'HELEURLE', 'VOLLEDIGEURLE', 'VOLLEDIGEURLE:', 'FULLURLE:' ),
	'lcfirst'                   => array( '0', 'KLEERSTE:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'GLEERSTE:', 'HLEERSTE:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'KL:', 'LC:' ),
	'uc'                        => array( '0', 'HL:', 'UC:' ),
	'raw'                       => array( '0', 'RAUW:', 'RUW:', 'RAW:' ),
	'displaytitle'              => array( '1', 'TEUNTITEL', 'TOONTITEL', 'TITELTONEN', 'WEERGEGEVENTITEL', 'DISPLAYTITLE' ),
	'newsectionlink'            => array( '1', '__NIEJESECTIEVERWIEZING__', '__NIEUWESECTIELINK__', '__NIEUWESECTIEKOPPELING__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__GIENNIEJKOPJENVERWIEZING__', '__GEENNIEUWKOPJEVERWIJZING__', '__GEENNIEUWESECTIELINK__', '__GEENNIEUWKOPJEKOPPELING__', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'DISSEVERSIE', 'HUIDIGEVERSIE', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'URLKODEREN', 'URLCODEREN', 'CODEERURL', 'URLENCODE:' ),
	'anchorencode'              => array( '0', 'ANKERKODEREN', 'ANKERCODEREN', 'CODEERANKER', 'ANCHORENCODE' ),
	'currenttimestamp'          => array( '1', 'DISSETIEDSTEMPEL', 'HUIDIGETIJDSTEMPEL', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'LOKALETIEDSTEMPEL', 'PLAATSELIJKETIJDSTEMPEL', 'LOKALETIJDSTEMPEL', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'RICHTINGMARKERING', 'RICHTINGSMARKERING', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#TAAL:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'INHOUDSTAAL', 'INHOUDTAAL', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'PAGINASINNAAMRUUMTE', 'PAGINA’SINNAAMRUUMTE', 'PAGINA\'SINNAAMRUUMTE', 'PAGINASINNAAMRUIMTE', 'PAGINA’SINNAAMRUIMTE', 'PAGINA\'SINNAAMRUIMTE', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'ANTALBEHEERDERS', 'AANTALBEHEERDERS', 'AANTALADMINS', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'FORMATTEERNUM', 'NUMFORMATTEREN', 'FORMATNUM' ),
	'padleft'                   => array( '0', 'LINKSOPVULLEN', 'PADLEFT' ),
	'padright'                  => array( '0', 'RECHTSOPVULLEN', 'PADRIGHT' ),
	'special'                   => array( '0', 'spesiaal', 'speciaal', 'special' ),
	'defaultsort'               => array( '1', 'STANDARDSORTERING:', 'STANDAARDSORTERING:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'BESTAANDSPAD:', 'BESTANDSPAD:', 'FILEPATH:' ),
	'tag'                       => array( '0', 'etiket', 'label', 'tag' ),
	'hiddencat'                 => array( '1', '__VERBÖRGENKAT__', '__VERBORGENCAT__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'PAGINASINKATEGORIE', 'PAGINASINKAT', 'PAGINASINCATEGORIE', 'PAGINASINCAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'PAGINAGROOTTE', 'PAGESIZE' ),
	'noindex'                   => array( '1', '__GIENINDEX__', '__GEENINDEX__', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'ANTALINGROEP', 'AANTALINGROEP', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__STAOTIESEDEURVERWIEZING__', '__STATISCHEDOORVERWIJZING__', '__STATISCHEREDIRECT__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'BEVEILIGINGSNIVO', 'BEVEILIGINGSNIVEAU', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'daotumopmaak', 'datumopmaak', 'formatdate', 'dateformat' ),
	'url_query'                 => array( '0', 'ZEUKOPDRACHTE', 'ZOEKOPDRACHT', 'QUERY' ),
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Aktieve_gebrukers' ),
	'Allmessages'               => array( 'Alle_systeemteksten' ),
	'Allpages'                  => array( 'Alle_pagina\'s' ),
	'Ancientpages'              => array( 'Oudste_pagina\'s' ),
	'Blankpage'                 => array( 'Lege_pagina' ),
	'Block'                     => array( 'Blokkeer_IP' ),
	'Blockme'                   => array( 'Blokkeer_mien' ),
	'Booksources'               => array( 'Boekinformasie' ),
	'BrokenRedirects'           => array( 'Ebreuken_deurverwiezingen' ),
	'Categories'                => array( 'Kategorieën' ),
	'ChangePassword'            => array( 'Wachtwoord_wiezigen' ),
	'Confirmemail'              => array( 'Netpost_bevestigen' ),
	'Contributions'             => array( 'Biedragen' ),
	'CreateAccount'             => array( 'Gebruker_anmaken' ),
	'Deadendpages'              => array( 'Gien_verwiezingen' ),
	'DeletedContributions'      => array( 'Vort-edaone_gebrukersbiedragen' ),
	'Disambiguations'           => array( 'Deurverwiespagina\'s' ),
	'DoubleRedirects'           => array( 'Dubbele_deurverwiezingen' ),
	'Emailuser'                 => array( 'Bericht_sturen' ),
	'Export'                    => array( 'Uutvoeren' ),
	'Fewestrevisions'           => array( 'Minste_bewarkingen' ),
	'FileDuplicateSearch'       => array( 'Dubbele_bestaanden_zeuken' ),
	'Filepath'                  => array( 'Bestaandslokasie' ),
	'Import'                    => array( 'Invoeren' ),
	'Invalidateemail'           => array( 'Netpost_ongeldig' ),
	'BlockList'                 => array( 'IP-blokkeerlieste' ),
	'LinkSearch'                => array( 'Verwiezingen_zeuken' ),
	'Listadmins'                => array( 'Beheerderslieste' ),
	'Listbots'                  => array( 'Botlieste' ),
	'Listfiles'                 => array( 'Bestaandslieste' ),
	'Listgrouprights'           => array( 'Groepsrechten_bekieken' ),
	'Listredirects'             => array( 'Deurverwiezingslieste' ),
	'Listusers'                 => array( 'Gebrukerslieste' ),
	'Lockdb'                    => array( 'Databanke_blokkeren' ),
	'Log'                       => array( 'Logboeken' ),
	'Lonelypages'               => array( 'Weespagina\'s' ),
	'Longpages'                 => array( 'Lange_artikels' ),
	'MergeHistory'              => array( 'Geschiedenisse_bie_mekaar_doon' ),
	'MIMEsearch'                => array( 'MIME-zeuken' ),
	'Mostcategories'            => array( 'Meeste_kategorieën' ),
	'Mostimages'                => array( 'Meestgebruukten_bestaanden' ),
	'Mostlinked'                => array( 'Meest_naor_verwezen_pagina\'s' ),
	'Mostlinkedcategories'      => array( 'Meestgebruukten_kategorieën' ),
	'Mostlinkedtemplates'       => array( 'Meestgebruken_mallen' ),
	'Mostrevisions'             => array( 'Meeste_bewarkingen' ),
	'Movepage'                  => array( 'Herneum_pagina' ),
	'Mycontributions'           => array( 'Mien_biedragen' ),
	'Mypage'                    => array( 'Mien_gebrukerspagina' ),
	'Mytalk'                    => array( 'Mien_overleg' ),
	'Myuploads'                 => array( 'Mien_in-elaojen_bestanen' ),
	'Newimages'                 => array( 'Nieje_bestaanden' ),
	'Newpages'                  => array( 'Nieje_pagina\'s' ),
	'PasswordReset'             => array( 'Wachtwoord_opniej_instellen' ),
	'PermanentLink'             => array( 'Vaste_verwiezing' ),
	'Popularpages'              => array( 'Populaire_artikels' ),
	'Preferences'               => array( 'Veurkeuren' ),
	'Prefixindex'               => array( 'Veurvoegselindex' ),
	'Protectedpages'            => array( 'Beveiligden_pagina\'s' ),
	'Protectedtitles'           => array( 'Beveiligden_titels' ),
	'Randompage'                => array( 'Zo_mer_n_artikel' ),
	'Randomredirect'            => array( 'Zo_mer_n_deurverwiezing' ),
	'Recentchanges'             => array( 'Leste_wiezigingen' ),
	'Recentchangeslinked'       => array( 'Volg_verwiezingen' ),
	'Revisiondelete'            => array( 'Versie_vortdoon' ),
	'Search'                    => array( 'Zeuken' ),
	'Shortpages'                => array( 'Korte_artikels' ),
	'Specialpages'              => array( 'Spesiale_pagina\'s' ),
	'Statistics'                => array( 'Staotistieken' ),
	'Tags'                      => array( 'Etiketten' ),
	'Uncategorizedcategories'   => array( 'Kategorieën_zonder_kategorie' ),
	'Uncategorizedimages'       => array( 'Bestaanden_zonder_kategorie' ),
	'Uncategorizedpages'        => array( 'Pagina\'s_zonder_kategorie' ),
	'Uncategorizedtemplates'    => array( 'Mallen_zonder_kategorie' ),
	'Undelete'                  => array( 'Weerummeplaotsen' ),
	'Unlockdb'                  => array( 'Databanke_vriegeven' ),
	'Unusedcategories'          => array( 'Ongebruukten_kategorieën' ),
	'Unusedimages'              => array( 'Ongebruukten_bestaanden' ),
	'Unusedtemplates'           => array( 'Ongebruukten_mallen' ),
	'Unwatchedpages'            => array( 'Niet-evolgden_pagina\'s' ),
	'Upload'                    => array( 'Bestaanden_opsturen' ),
	'UploadStash'               => array( 'Bestaandenstallige' ),
	'Userlogin'                 => array( 'Anmelden' ),
	'Userlogout'                => array( 'Aofmelden' ),
	'Userrights'                => array( 'Gebrukersrechten' ),
	'Version'                   => array( 'Versie' ),
	'Wantedcategories'          => array( 'Gewunste_kategorieën' ),
	'Wantedfiles'               => array( 'Gewunste_bestaanden' ),
	'Wantedpages'               => array( 'Gewunste_pagina\'s' ),
	'Wantedtemplates'           => array( 'Gewunste_mallen' ),
	'Watchlist'                 => array( 'Volglieste' ),
	'Whatlinkshere'             => array( 'Verwiezingen_naor_disse_pagina' ),
	'Withoutinterwiki'          => array( 'Gien_interwiki' ),
);

$linkTrail = '/^([a-zäöüïëéèà]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline' => 'Verwiezingen onderstrepen',
'tog-justify' => "Alinea's uutvullen",
'tog-hideminor' => 'Kleine wiezigingen verbargen in "Leste wiezigingen"',
'tog-hidepatrolled' => 'Wiezigingen die emarkeerd bin verbargen in "Leste wiezigingen"',
'tog-newpageshidepatrolled' => 'Ziejen die emarkeerd bin, verbargen in de lieste mit nieje artikels',
'tog-extendwatchlist' => 'Volglieste uutbreien, zodat alle wiezigingen zichtbaor bin, en niet allinnig de leste wieziging',
'tog-usenewrc' => 'Groepeer wiezigingen per zied in "Leste wiezigingen" en "Mien volglieste"',
'tog-numberheadings' => 'Koppen vanzelf nummeren',
'tog-showtoolbar' => 'Laot de warkbalke zien',
'tog-editondblclick' => 'Mit dubbelklik bewarken',
'tog-editsection' => 'Mit bewarkgedeelten',
'tog-editsectiononrightclick' => 'Bewarken van deelziejen meugelik maken mit n rechtermuusklik op n tussenkop',
'tog-showtoc' => 'Samenvatting laoten zien van de zaken die an bod koemen (mit meer as dree onderwarpen)',
'tog-rememberpassword' => 'Vanzelf anmelden (hooguut $1 {{PLURAL:$1|dag|dagen}})',
'tog-watchcreations' => "Spul wa'k anmake op mien volglieste zetten",
'tog-watchdefault' => "Spul wa'k bewarke op mien volglieste zetten",
'tog-watchmoves' => "Spul wa'k herneume op mien volglieste zetten",
'tog-watchdeletion' => "Spul wa'k vortdo op mien volglieste zetten",
'tog-minordefault' => "Markeer alle veraanderingen as 'kleine wieziging'",
'tog-previewontop' => 'De naokiekzied boven t bewarkingsveld zetten',
'tog-previewonfirst' => 'Naokieken bie eerste wieziging',
'tog-nocache' => 'De tussenopslag van de webkieker uutzetten',
'tog-enotifwatchlistpages' => 'Stuur mien n berichjen over zied- of bestaandswiezigingen uut mien volglieste.',
'tog-enotifusertalkpages' => 'Stuur mien n berichjen as mien overlegzied ewiezigd is.',
'tog-enotifminoredits' => 'Stuur mien oek n berichjen bie kleine bewarkingen van ziejen en bestaanden',
'tog-enotifrevealaddr' => 'Mien netpostadres laoten zien in netposttiejigen',
'tog-shownumberswatching' => 't Antal gebrukers bekieken die disse zied volgt',
'tog-oldsig' => 'Bestaonde haandtekening:',
'tog-fancysig' => 'Ondertekening zien as wikitekste (zonder automatiese verwiezing)',
'tog-uselivepreview' => 'Gebruuk "rechtstreeks naokieken" (experimenteel)',
'tog-forceeditsummary' => 'Geef n melding bie n lege samenvatting',
'tog-watchlisthideown' => 'Verbarg mien eigen bewarkingen',
'tog-watchlisthidebots' => 'Verbarg botgebrukers',
'tog-watchlisthideminor' => 'Verbarg kleine wiezigingen in mien volglieste',
'tog-watchlisthideliu' => 'Bewarkingen van an-emelde gebrukers op mien volglieste verbargen',
'tog-watchlisthideanons' => 'Bewarkingen van anonieme gebrukers op mien volglieste verbargen',
'tog-watchlisthidepatrolled' => 'Wiezigingen die emarkeerd bin op volglieste verbargen',
'tog-ccmeonemails' => 'Stuur mien kopieën van berichten an aandere gebrukers',
'tog-diffonly' => 'Laot de inhoud van ziejen niet onder de an-egeven wiezigingen zien.',
'tog-showhiddencats' => 'Laot verbörgen kategorieën zien',
'tog-noconvertlink' => 'Ziednaamkonversie uutschakelen',
'tog-norollbackdiff' => 'Wiezigingen vortlaoten nao t weerummedreien',
'tog-useeditwarning' => "Waorschuw mien a'k n bewörken zied aof wil sluten die nog niet op-esleugen is",
'tog-prefershttps' => "Altied n beveiligde verbiending gebruken a'j an-emeld bin",

'underline-always' => 'Altied',
'underline-never' => 'Nooit',
'underline-default' => 'Standard in joew vormgeving of webkieker',

# Font style option in Special:Preferences
'editfont-style' => 'Lettertype veur de tekste t bewarkingsveld:',
'editfont-default' => 'Standardwebkieker',
'editfont-monospace' => 'Lettertype waorvan t tekenbreedte vaste steet',
'editfont-sansserif' => 'Sans-seriflettertype',
'editfont-serif' => 'Seriflettertype',

# Dates
'sunday' => 'zundag',
'monday' => 'maondag',
'tuesday' => 'diensdag',
'wednesday' => 'woonsdag',
'thursday' => 'donderdag',
'friday' => 'vriedag',
'saturday' => 'zaoterdag',
'sun' => 'zun',
'mon' => 'mao',
'tue' => 'die',
'wed' => 'woo',
'thu' => 'don',
'fri' => 'vrie',
'sat' => 'zao',
'january' => 'jannewaori',
'february' => 'febrewaori',
'march' => 'meert',
'april' => 'april',
'may_long' => 'mei',
'june' => 'juni',
'july' => 'juli',
'august' => 'augustus',
'september' => 'september',
'october' => 'oktober',
'november' => 'november',
'december' => 'desember',
'january-gen' => 'jannewaori',
'february-gen' => 'febrewaori',
'march-gen' => 'meert',
'april-gen' => 'april',
'may-gen' => 'mei',
'june-gen' => 'juni',
'july-gen' => 'juli',
'august-gen' => 'augustus',
'september-gen' => 'september',
'october-gen' => 'oktober',
'november-gen' => 'november',
'december-gen' => 'desember',
'jan' => 'jan',
'feb' => 'feb',
'mar' => 'mrt',
'apr' => 'apr',
'may' => 'mei',
'jun' => 'jun',
'jul' => 'jul',
'aug' => 'aug',
'sep' => 'sep',
'oct' => 'okt',
'nov' => 'nov',
'dec' => 'des',
'january-date' => '$1 jannewaori',
'february-date' => '$1 febrewaori',
'march-date' => '$1 meert',
'april-date' => '$1 april',
'may-date' => '$1 mei',
'june-date' => '$1 juni',
'july-date' => '$1 juli',
'august-date' => '$1 augustus',
'september-date' => '$1 september',
'october-date' => '$1 oktober',
'november-date' => '$1 november',
'december-date' => '$1 desember',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Kategorie|Kategorieën}}',
'category_header' => 'Artikels in kategorie $1',
'subcategories' => 'Subkategorieën',
'category-media-header' => 'Media in kategorie "$1"',
'category-empty' => "''In disse kategoria staon op t moment nog gien artikels of media.''",
'hidden-categories' => 'Verbörgen {{PLURAL:$1|kategorie|kategorieën}}',
'hidden-category-category' => 'Verbörgen kategorieën',
'category-subcat-count' => '{{PLURAL:$2|Disse kategorie hef de volgende subkategorie.|Disse kategorie hef de volgende {{PLURAL:$1|subkategorie|$1 subkategorieën}}, van in totaal $2.}}',
'category-subcat-count-limited' => 'Disse kategorie hef de volgende {{PLURAL:$1|subkategorie|$1 subkategorieën}}.',
'category-article-count' => '{{PLURAL:$2|In disse kategorie steet allinnig de volgende zied.|De volgende {{PLURAL:$1|zied steet|$1 ziejen staon}} in disse kategorie, van in totaal $2.}}',
'category-article-count-limited' => 'In disse kategorie {{PLURAL:$1|steet de volgende zied|staon de volgende $1 ziejen}}.',
'category-file-count' => 'In disse kategorie {{PLURAL:$2|steet t volgende bestaand|staon de volgende $1 bestaanden, van in totaal $2}}.',
'category-file-count-limited' => 'In disse kategorie {{PLURAL:$1|steet t volgende bestaand|staon de volgende $1 bestaanden}}.',
'listingcontinuesabbrev' => '(vervolg)',
'index-category' => 'Te indexeren ziejen',
'noindex-category' => 'Niet te indexeren ziejen',
'broken-file-category' => 'Ziejen mit verkeerde bestaandsverwiezingen',

'about' => 'Informasie',
'article' => 'Artikel',
'newwindow' => '(niej vienster)',
'cancel' => 'Aofbreken',
'moredotdotdot' => 'Meer...',
'morenotlisted' => 'Disse lieste is niet kompleet...',
'mypage' => 'Gebrukerszied',
'mytalk' => 'Mien overleg',
'anontalk' => 'Overlegzied veur dit IP-adres',
'navigation' => 'Navigasie',
'and' => '&#32;en',

# Cologne Blue skin
'qbfind' => 'Zeuken',
'qbbrowse' => 'Blaojen',
'qbedit' => 'Bewark',
'qbpageoptions' => 'Disse zied',
'qbmyoptions' => 'Veurkeuren',
'qbspecialpages' => 'Spesiale ziejen',
'faq' => 'Vragen die vake esteld wörden',
'faqpage' => 'Project:Vragen die vake esteld wörden',

# Vector skin
'vector-action-addsection' => 'Niej onderwarp',
'vector-action-delete' => 'Vortdoon',
'vector-action-move' => 'Herneumen',
'vector-action-protect' => 'Beveiligen',
'vector-action-undelete' => 'Weerummeplaotsen',
'vector-action-unprotect' => 'Beveiliging wiezigen',
'vector-simplesearch-preference' => 'Vereenvoudigd zeuken anzetten (allinnig mit Vector-vormgeving)',
'vector-view-create' => 'Anmaken',
'vector-view-edit' => 'Bewarken',
'vector-view-history' => 'Geschiedenisse bekieken',
'vector-view-view' => 'Lezen',
'vector-view-viewsource' => 'Brontekste bekieken',
'actions' => 'Haandeling',
'namespaces' => 'Naamruumtes',
'variants' => 'Variaanten',

'navigation-heading' => 'Navigasiemenu',
'errorpagetitle' => 'Foutmelding',
'returnto' => 'Weerumme naor $1.',
'tagline' => 'Van {{SITENAME}}',
'help' => 'Hulpe en kontakt',
'search' => 'Zeuken',
'searchbutton' => 'Zeuken',
'go' => 'Artikel',
'searcharticle' => 'Artikel',
'history' => 'Geschiedenisse',
'history_short' => 'Geschiedenisse',
'updatedmarker' => 'bie-ewörken sinds mien leste bezeuk',
'printableversion' => 'Aofdrokbaore versie',
'permalink' => 'Vaste verwiezing',
'print' => 'Aofdrokken',
'view' => 'Lezen',
'edit' => 'Bewarken',
'create' => 'Anmaken',
'editthispage' => 'Disse zied bewarken',
'create-this-page' => 'Disse zied anmaken',
'delete' => 'Vortdoon',
'deletethispage' => 'Disse zied vortdoon',
'undeletethispage' => 'Zied weerummeplaotsen',
'undelete_short' => '$1 {{PLURAL:$1|versie|versies}} weerummeplaotsen',
'viewdeleted_short' => '{{PLURAL:$1|Eén versie die vortedaon is|$1 versies die vortedaon bin}} bekieken',
'protect' => 'Beveiligen',
'protect_change' => 'wiezigen',
'protectthispage' => 'Beveiligen',
'unprotect' => 'Beveiliging wiezigen',
'unprotectthispage' => 'Beveiliging van disse zied wiezigen',
'newpage' => 'Nieje zied',
'talkpage' => 'Overlegzied',
'talkpagelinktext' => 'Overleg',
'specialpage' => 'Spesiale zied',
'personaltools' => 'Persoonlike instellingen',
'postcomment' => 'Niej onderwarp',
'articlepage' => 'Artikel',
'talk' => 'Overleg',
'views' => 'Weergaven',
'toolbox' => 'Hulpmiddels',
'userpage' => 'gebrukerszied',
'projectpage' => 'Bekiek projektzied',
'imagepage' => 'Bestaandszied bekieken',
'mediawikipage' => 'Tiejige bekieken',
'templatepage' => 'Mal bekieken',
'viewhelppage' => 'Hulpzied bekieken',
'categorypage' => 'Kategoriezied bekieken',
'viewtalkpage' => 'Bekiek overlegzied',
'otherlanguages' => 'Aandere talen',
'redirectedfrom' => '(deurestuurd vanaof "$1")',
'redirectpagesub' => 'Deurverwieszied',
'lastmodifiedat' => 'Disse zied is t lest ewiezigd op $1 um $2.',
'viewcount' => 'Disse zied is $1 {{PLURAL:$1|keer|keer}} bekeken.',
'protectedpage' => 'Beveiligden zied',
'jumpto' => 'Gao naor:',
'jumptonavigation' => 'navigasie',
'jumptosearch' => 'zeuk',
'view-pool-error' => "De servers bin op heden overbelast.
Te veule gebrukers proberen disse zied te bekieken.
Wacht effen veurda'j opniej toegang proberen te kriegen tot disse zied.

$1",
'pool-timeout' => 'De maximumwachttied veur databankvergrendeling is verleupen.',
'pool-queuefull' => 'De wachtrie van de poel is vol',
'pool-errorunknown' => 'Onbekende fout',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'Over {{SITENAME}}',
'aboutpage' => 'Project:Info',
'copyright' => 'De inhoud is beschikbaor onder de $1 as der niks aanders an-egeven is.',
'copyrightpage' => '{{ns:project}}:Auteursrechten',
'currentevents' => 'In t niejs',
'currentevents-url' => 'Project:In t niejs',
'disclaimers' => 'Veurbehold',
'disclaimerpage' => 'Project:Veurbehoud',
'edithelp' => 'Hulpe mit bewarken',
'helppage' => 'Help:Inhoud',
'mainpage' => 'Veurblad',
'mainpage-description' => 'Veurblad',
'policy-url' => 'Project:Beleid',
'portal' => 'Gebrukersportaol',
'portal-url' => 'Project:Gebrukersportaol',
'privacy' => 'Gegevensbeleid',
'privacypage' => 'Project:Gegevensbeleid',

'badaccess' => 'Gien toestemming',
'badaccess-group0' => 'Je hebben gien toestemming um disse aksie uut te voeren.',
'badaccess-groups' => 'Disse aksie kan allinnig uutevoerd wörden deur gebrukers uut {{PLURAL:$2|de groep|één van de groepen}}: $1.',

'versionrequired' => 'Versie $1 van MediaWiki is neudig',
'versionrequiredtext' => 'Versie $1 van MediaWiki is neudig um disse zied te gebruken. Zie [[Special:Version|Versie]].',

'ok' => 'Best',
'retrievedfrom' => 'Van "$1"',
'youhavenewmessages' => 'Je hebben $1 ($2).',
'newmessageslink' => 'nieje berichten',
'newmessagesdifflink' => 'verschil mit de veurige versie',
'youhavenewmessagesfromusers' => 'Je hebben $1 van {{PLURAL:$3|n aandere gebruker|$3 gebrukers}} ($2).',
'youhavenewmessagesmanyusers' => 'Je hebben $1 van n bulte gebrukers ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|n niej bericht|nieje berichten}}',
'newmessagesdifflinkplural' => 'leste {{PLURAL:$1|wieziging|wiezigingen}}',
'youhavenewmessagesmulti' => 'Je hebben nieje berichten op $1',
'editsection' => 'bewark',
'editold' => 'bewark',
'viewsourceold' => 'brontekste bekieken',
'editlink' => 'bewark',
'viewsourcelink' => 'brontekste bekieken',
'editsectionhint' => 'Bewarkingsveld: $1',
'toc' => 'Kömp an bod',
'showtoc' => 'Bekieken',
'hidetoc' => 'Verbarg',
'collapsible-collapse' => 'Inklappen',
'collapsible-expand' => 'Uutklappen',
'thisisdeleted' => 'Bekieken of herstellen van $1?',
'viewdeleted' => 'Bekiek $1?',
'restorelink' => '{{PLURAL:$1|versie die vortedaon is|versies die vortedaon bin}}',
'feedlinks' => 'Voer:',
'feed-invalid' => 'Voertype wörden niet ondersteunt.',
'feed-unavailable' => 'Syndicakievoer is niet beschikbaor',
'site-rss-feed' => '$1 RSS-voer',
'site-atom-feed' => '$1 Atom-voer',
'page-rss-feed' => '"$1" RSS-voer',
'page-atom-feed' => '"$1" Atom-voer',
'red-link-title' => '$1 (zied besteet nog niet)',
'sort-descending' => 'Aoflopend sorteren',
'sort-ascending' => 'Oplopend sorteren',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Artikel',
'nstab-user' => 'Gebruker',
'nstab-media' => 'Media',
'nstab-special' => 'Spesiale zied',
'nstab-project' => 'Projektzied',
'nstab-image' => 'Bestaand',
'nstab-mediawiki' => 'Tiejige',
'nstab-template' => 'Mal',
'nstab-help' => 'Hulpe',
'nstab-category' => 'Kategorie',

# Main script and global functions
'nosuchaction' => 'De op-egeven haandeling besteet niet',
'nosuchactiontext' => 'De opdrachte in t webadres in ongeldig.
Je hebben t webadres misschien verkeerd in-etikt of de verkeerde verwiezing evolgd.
Dit kan oek dujen op n fout in de programmatuur van {{SITENAME}}.',
'nosuchspecialpage' => 'Der besteet gien spesiale zied mit disse naam',
'nospecialpagetext' => "<strong>Disse spesiale zied wörden niet herkend deur de programmatuur.</strong>

n Lieste mit bestaonde spesiale ziejen ku'j vienen op [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error' => 'Foutmelding',
'databaseerror' => 'Fout in de databanke',
'databaseerror-text' => 'Der is wat mis egaon bie n databankzeukopdrachte.
Dit kan betekenen dat der n fout in de programmtuur zit.',
'databaseerror-textcl' => 'Der is wat mis egaon bie n databankzeukopdrachte.',
'databaseerror-query' => 'Zeukopdrachte: $1',
'databaseerror-function' => 'Funksie: $1',
'databaseerror-error' => 'Fout: $1',
'laggedslavemode' => '<strong>Waorschuwing:</strong> t is meugelik dat leste wiezigingen in de tekste van dit artikel nog niet verwarkt bin.',
'readonly' => 'De databanke is beveiligd',
'enterlockreason' => 'Waorumme en veur hoe lange is t eblokkeerd?',
'readonlytext' => "De databanke van {{SITENAME}} is noen esleuten veur nieje bewarkingen en wiezigingen, warschienlik veur bestaandsonderhoud. De verantwoordelike systeembeheerder gaf hierveur de volgende reden op: '''$1'''",
'missing-article' => 'In de databanke steet gien tekste veur de zied "$1" die der wel in zol mutten staon ($2).

Dit kan koemen deurda\'j n ouwe verwiezing naor t verschil tussen twee versies van n zied volgen of n versie opvragen die vortedaon is.

As dat niet zo is, dan he\'j misschien n fout in de programmatuur evunnen.
Meld t dan effen bie n [[Special:ListUsers/sysop|systeembeheerder]] van {{SITENAME}} en vermeld derbie de internetverwiezing van disse zied.',
'missingarticle-rev' => '(versienummer: $1)',
'missingarticle-diff' => '(Wieziging: $1, $2)',
'readonly_lag' => 'De databanke is automaties beveilig, zodat de ondergeschikten servers zich kunnen synchroniseren mit de sentrale server.',
'internalerror' => 'Interne fout',
'internalerror_info' => 'Interne fout: $1',
'fileappenderrorread' => '"$1" kon niet elezen wörden tiejens t inlaojen.',
'fileappenderror' => 'Kon "$1" niet bie "$2" doon.',
'filecopyerror' => 'Kon bestaand "$1" niet naor "$2" kopiëren.',
'filerenameerror' => 'Bestaandsnaamwieziging "$1" naor "$2" niet meugelik.',
'filedeleteerror' => 'Kon bestaand "$1" niet vortdoon.',
'directorycreateerror' => 'Map "$1" kon niet an-emaakt wörden.',
'filenotfound' => 'Kon bestaand "$1" niet vienen.',
'fileexistserror' => 'Kon niet schrieven naor t bestaand "$1": t bestaand besteet al',
'unexpected' => 'Onverwachten weerde: "$1"="$2".',
'formerror' => 'Fout: kon formulier niet versturen',
'badarticleerror' => 'Disse haandeling kan op disse zied niet uutevoerd wörden.',
'cannotdelete' => 'De zied of t bestaand "$1" kon niet vortedaon wörden.
t Kan ween dat n aander t al vortedaon hef.',
'cannotdelete-title' => 'Zied "$1" kan niet vortedaon wörden',
'delete-hook-aborted' => 't Vortdoon wörden in t wiere eschopt deur n MediaWiki-programmatuuruutbreiding.
Der is gien veerdere informasie beschikbaor.',
'no-null-revision' => 'Kon gien lege nieje versie maken veur de zied "$1"',
'badtitle' => 'Ongeldige naam',
'badtitletext' => 'De naam van de op-evreugen zied is niet geldig, leeg, of n interwiki-verwiezing naor n onbekende of ongeldige wiki.',
'perfcached' => 'Disse gegevens koemen uut t tussengeheugen en bin misschien niet aktueel. Der {{PLURAL:$1|is hooguut een resultaot|bin hooguut $1 resultaoten}} beschikbaor in t tussengeheugen.',
'perfcachedts' => 'Disse gegevens koemen uut t tussengeheugen die veur t lest bie-ewörken is op $2 um $3. Der {{PLURAL:$4|is hooguut een resultaot|bin hooguut $4 resultaoten}} beschikbaor in t tussengeheugen.',
'querypage-no-updates' => "'''Disse zied wörden niet meer bie-ewörken.'''",
'wrong_wfQuery_params' => 'Parameters veur wfQuery() waren verkeerd<br />
Funksie: $1<br />
Zeukopdrachte: $2',
'viewsource' => 'Brontekste bekieken',
'viewsource-title' => 'Bron bekieken van $1',
'actionthrottled' => 'Haandeling tegenehöllen',
'actionthrottledtext' => "As maotregel tegen t plaotsen van ongewunste verwiezingen, is t antal keren da'j disse haandeling in n korte tied uutvoeren kunnen beteund. Je hebben de limiet overschrejen. Probeer t over n antal minuten weer.",
'protectedpagetext' => 'Disse zied is beveiligd. Bewarken of aandere haandelingen bin niet meugelik.',
'viewsourcetext' => 'Je kunnen de brontekste van disse zied bewarken en bekieken:',
'viewyourtext' => "Je kunnen '''joew bewarkingen''' an de brontekste van disse zied bekieken en kopiëren:",
'protectedinterface' => "Op disse zied steet tekste die gebruukt wörden veur systeemteksten van disse wiki. Allinnig beheerders kunnen disse zied bewarken.
Um vertalingen veur alle wiki's derbie te zetten of te wiezigen, gebruuk [//translatewiki.net/ translatewiki.net], t vertaalprojekt veur MediaWiki.",
'editinginterface' => "'''Waorschuwing:''' je bewarken n zied die gebruukt wörden deur de programmatuur. Wa'j hier wiezigen, is van invleud op de hele wiki. Um vertalingen derbie te zetten of te wiezigen veur alle wiki's, gebruuk [//translatewiki.net/wiki/Main_Page?setlang=nds-nl translatewiki.net], t vertalingsprojekt veur MediaWiki.",
'cascadeprotected' => 'Disse zied is beveiligd umdat t veurkömp in de volgende {{PLURAL:$1|zied|ziejen}}, die beveiligd {{PLURAL:$1|is|bin}} mit de "kaskade"-opsie:
$2',
'namespaceprotected' => "Je maggen gien ziejen in de '''$1'''-naamruumte bewarken.",
'customcssprotected' => 'Je kunnen disse CSS-zied niet bewarken, umdat der persoonlike instellingen van n aandere gebruker in staon.',
'customjsprotected' => 'Je kunnen disse JavaScript-zied niet bewarken, umdat der persoonlike instellingen van n aandere gebruker in staon.',
'mycustomcssprotected' => 'Je hebben gien toestemming um disse CSS-zied te bewarken.',
'mycustomjsprotected' => 'Je hebben gien rechten um disse JavaScript-zied te bewarken.',
'myprivateinfoprotected' => 'Je hebben gien rechten um joew priveegegevens an te passen.',
'mypreferencesprotected' => 'Je hebben gien rechten um joew veurkeuren an te passen.',
'ns-specialprotected' => 'Spesiale ziejen kunnen niet bewarkt wörden.',
'titleprotected' => "t Anmaken van disse zied is beveiligd deur [[User:$1|$1]].
De op-egeven reden is ''$2''.",
'filereadonlyerror' => 'Kon t bestaand "$1" niet anpassen umdat de bestaandsmap "$2" op dit moment op allinnig-lezen steet.

De beheerder gaf hierveur de volgende reden: "$3".',
'invalidtitle-knownnamespace' => 'Ongeldige titel mit naamruumte "$2" en tekste "$3"',
'invalidtitle-unknownnamespace' => 'Ongeldige titel mit onbekend naamruumtenummer $1 en tekste "$2"',
'exception-nologin' => 'Niet an-emeld',
'exception-nologin-text' => "Um disse zied te bekieken of disse haandeling uut te kunnen voeren mu'j an-emeld ween bie disse wiki.",

# Virus scanner
'virus-badscanner' => "Slichte konfigurasie: onbekend antivirusprogramma: ''$1''",
'virus-scanfailed' => 'inlezen is mislokt (kode $1)',
'virus-unknownscanner' => 'onbekend antivirusprogramma:',

# Login and logout pages
'logouttext' => "'''Je bin noen aofemeld.'''

t Kan ween dat der wat ziejen bin die weeregeven wörden as of je an-emeld bin totda'j t tussengeheugen van joew webkieker leegmaken.",
'welcomeuser' => 'Welkom, $1!',
'welcomecreation-msg' => 'Joew gebruker is an-emaakt.
Vergeet niet joew [[Special:Preferences|veurkeuren veur {{SITENAME}}]] an te passen.',
'yourname' => 'Gebrukersnaam',
'userlogin-yourname' => 'Gebrukersnaam',
'userlogin-yourname-ph' => 'Geef joew gebrukersnaam op',
'createacct-another-username-ph' => 'Vul de gebrukersnaam in',
'yourpassword' => 'Wachtwoord',
'userlogin-yourpassword' => 'Wachtwoord',
'userlogin-yourpassword-ph' => 'Geef joew wachtwoord op',
'createacct-yourpassword-ph' => 'Geef n wachtwoord op',
'yourpasswordagain' => 'Opniej invoeren',
'createacct-yourpasswordagain' => 'Wachtwoord bevestigen',
'createacct-yourpasswordagain-ph' => 'Geef t wachtwoord opniej op',
'remembermypassword' => 'Vanzelf anmelden (hooguut $1 {{PLURAL:$1|dag|dagen}})',
'userlogin-remembermypassword' => 'Vanzelf anmelden',
'userlogin-signwithsecure' => 'Beveiligde verbiending gebruken',
'yourdomainname' => 'Joew domein',
'password-change-forbidden' => 'Je kunnen joew wachtwoord niet wiezigen op disse wiki.',
'externaldberror' => 'Der gung iets fout bie de externe authentisering, of je maggen je gebrukersprofiel niet bewarken.',
'login' => 'Anmelden',
'nav-login-createaccount' => 'Anmelden',
'loginprompt' => 'Je mutten scheumbestaanden (cookies) an hebben staon um an te kunnen melden bie {{SITENAME}}.',
'userlogin' => 'Anmelden / inschrieven',
'userloginnocreate' => 'Anmelden',
'logout' => 'Aofmelden',
'userlogout' => 'Aofmelden',
'notloggedin' => 'Niet an-emeld',
'userlogin-noaccount' => "He'j nog gien gebrukersnaam?",
'userlogin-joinproject' => 'Wörd lid van {{SITENAME}}',
'nologin' => "He'j nog gien gebrukersnaam? $1.",
'nologinlink' => 'Maak n gebrukersprofiel an',
'createaccount' => 'Inschrieven',
'gotaccount' => "Stao'j al in-eschreven? '''$1'''.",
'gotaccountlink' => 'Anmelden',
'userlogin-resetlink' => "Bi'j de anmeldgegevens kwiet?",
'userlogin-resetpassword-link' => 'Joew wachtwoord opniej instellen',
'helplogin-url' => 'Help:Anmelden',
'userlogin-helplink' => '[[{{MediaWiki:helplogin-url}}|Hulpe bie t anmelden]]',
'userlogin-loggedin' => 'Je bin al an-emeld as {{GENDER:$1|$1}}.
Gebruuk t onderstaonde formulier um an te melden as n aandere gebruker.',
'userlogin-createanother' => 'n Aandere gebrukerskonto anmaken',
'createacct-join' => 'Geef joew gegevens hieronder op.',
'createacct-another-join' => 'Vul hieronder de informasie van de nieje gebruker in.',
'createacct-emailrequired' => 'Netpostadres',
'createacct-emailoptional' => 'Netpostadres (niet verplicht)',
'createacct-email-ph' => 'Geef joew netpostadres op',
'createacct-another-email-ph' => 'Vul joew netpostadres in',
'createaccountmail' => 'Gebruuk n tiejelik wachtwoord dat joe netzelde is en stuur t naor t op-egeven netpostadres',
'createacct-realname' => 'Echte naam (niet verplicht)',
'createaccountreason' => 'Reden:',
'createacct-reason' => 'Reden',
'createacct-reason-ph' => 'Waorumme je n aandere gebrukerskonto anmaken',
'createacct-captcha' => 'Veiligheidskontraole',
'createacct-imgcaptcha-ph' => "Voer de tekste in die'j hierboven zien",
'createacct-submit' => 'Gebrukerskonto anmaken',
'createacct-another-submit' => 'n Aandere gebrukerskonto anmaken',
'createacct-benefit-heading' => '{{SITENAME}} wörden emaakt deur meensen zo as jie.',
'createacct-benefit-body1' => 'bewarking{{PLURAL:$1||en}}',
'createacct-benefit-body2' => '{{PLURAL:$1|zied|ziejen}}',
'createacct-benefit-body3' => 'aktieve {{PLURAL:$1|biedrager|biedragers}}',
'badretype' => "De wachtwoorden die'j in-etikt hebben bin niet liek alleens.",
'userexists' => 'Disse gebrukersnaam is al gebruuk.
Kies n aandere naam.',
'loginerror' => 'Anmeldingsfout',
'createacct-error' => 'Fout bie t anmaken van n gebruker',
'createaccounterror' => 'Kon de gebrukersnaam niet anmaken: $1',
'nocookiesnew' => 'De gebrukersnaam is an-emaakt, mer je bin niet an-emeld.
{{SITENAME}} gebruuk scheumbestaanden (cookies) um gebrukers an te melden.
Je hebben disse scheumbestaanden uutezet.
Zet ze an, en meld daornao an mit de nieje gegevens.',
'nocookieslogin' => 't Anmelden is mislokt umdat de webkieker gien scheumbestaanden (cookies) an hef staon. Probeer t aksepteren van scheumbestaanden an te zetten en daornao opniej an te melden.',
'nocookiesfornew' => "De gebruker is niet an-emaakt, umdat de bron niet bevestigd kon wörden.
Zörg derveur da'j scheumbestaanden (cookies) an hebben staon, herlaoj disse zied en probeer t opniej.",
'noname' => 'Je mutten n gebrukersnaam opgeven.',
'loginsuccesstitle' => 'Suksesvol an-emeld',
'loginsuccess' => 'Je bin noen an-emeld bie {{SITENAME}} as "$1".',
'nosuchuser' => 'Der is gien gebruker mit de naam "$1".
Gebrukersnamen bin heufdlettergeveulig.
Kiek de schriefwieze effen nao of [[Special:UserLogin/signup|maak n nieje gebruker an]].',
'nosuchusershort' => 'Der is gien gebruker mit de naam "$1". Kiek de spelling nao.',
'nouserspecified' => 'Vul n naam in',
'login-userblocked' => 'Disse gebruker is eblokkeerd.
Je kunnen niet anmelden.',
'wrongpassword' => 'verkeerd wachtwoord, probeer t opniej.',
'wrongpasswordempty' => 'Gien wachtwoord in-evoerd. Probeer t opniej.',
'passwordtooshort' => 'Wachtwoorden mutten uut tenminsten {{PLURAL:$1|$1 teken|$1 tekens}} bestaon.',
'password-name-match' => 'Joew wachtwoord en gebrukersnaam maggen niet liek alleens ween.',
'password-login-forbidden' => 't Gebruuk van disse gebrukersnaam mit dit wachtwoord is niet toe-estaon.',
'mailmypassword' => 'Niej wachtwoord opsturen',
'passwordremindertitle' => 'Niej tiedelik wachtwoord veur {{SITENAME}}',
'passwordremindertext' => 'Der hef der ene evreugen, vanaof t IP-adres $1 (warschienlik jie zelf),
um n niej wachtwoord veur {{SITENAME}} ($4) op te sturen.
Der is n tiedelik wachtwoord an-emaakt veur gebruker "$2":
"$3". As dit niet de bedoeling was, meld dan effen an en kies n niej wachtwoord.
Joew tiedelike wachtwoord zal verlopen over {{PLURAL:$5|één dag|$5 dagen}}.

A\'j dit verzeuk niet zelf edaon hebben of a\'j t wachtwoord weer weten
en t niet meer wiezigen willen, negeer dit bericht dan
en blief joew bestaonde wachtwoord gebruken.',
'noemail' => 'Gien netpostadres eregistreerd veur "$1".',
'noemailcreate' => 'Je mutten n geldig netpostadres opgeven',
'passwordsent' => 'Der is n niej wachtwoord verstuurd naor t netpostadres van gebruker "$1". Meld an, a\'j t wachtwoord ontvangen.',
'blocked-mailpassword' => "Dit IP-adres is eblokkeerd. Dit betekent da'j niet bewarken kunnen en dat {{SITENAME}} joew wachtwoord niet weerummehaolen kan, dit wörden edaon um misbruuk tegen te gaon.",
'eauthentsent' => "Der is n bevestigingsberich naor t op-egeven netpostadres verstuurd. Veurdat der veerdere berichten naor dit netpostadres verstuurd kunnen wörden, mu'j de instruksies volgen in t toe-esturen berich, um te bevestigen da'j joe eigen daodwarkelik an-emeld hebben.",
'throttled-mailpassword' => 'In {{PLURAL:$1|t veurbieje ure|de veurbieje $1 uren}} is der al n wachtwoordherinnering estuurd.
Um misbruuk te veurkoemen wörden der mer één wachtwoordherinnering per {{PLURAL:$1|ure|$1 uren}} verstuurd.',
'mailerror' => 'Fout bie t versturen van bericht: $1',
'acct_creation_throttle_hit' => 'Onder dit IP-adres hebben luui de veurbieje dag al {{PLURAL:$1|1 gebruker|$1 gebrukers}} an-emaakt. Meer is niet toe-estaon in disse periode. Daorumme kunnen gebrukers mit dit IP-adres noen effen gien gebrukers meer anmaken.',
'emailauthenticated' => 'Joew netpostadres is bevestigd op $2 um $3.',
'emailnotauthenticated' => 'Netpostadres is <strong>nog niet bevestigd</strong>. Je kriegen gien berichten veur de onstaonde opsies.',
'noemailprefs' => 'Gien netpostadres in-evoerd, waordeur de onderstaonde funksies niet warken.',
'emailconfirmlink' => 'Bevestig netpostadres',
'invalidemailaddress' => 't Netpostadres kon niet aksepteerd wörden umdat de opmaak ongeldig is.
Voer de juuste opmaak van t adres in of laot t veld leeg.',
'cannotchangeemail' => 't Netpostadres veur n gebruker kan op disse wiki niet ewiezigd wörden.',
'emaildisabled' => 'Disse webstee kan gien netpost versturen.',
'accountcreated' => 'Gebrukersprofiel is an-emaakt',
'accountcreatedtext' => 'De gebrukersnaam veur [[{{ns:User}}:$1|$1]] ([[{{ns:User talk}}:$1|talk]]) is an-emaakt.',
'createaccount-title' => 'Gebrukers anmaken veur {{SITENAME}}',
'createaccount-text' => 'Der hef der ene n gebruker an-emaakt op {{SITENAME}} ($4), mit de naam $2 en t wachtwoord "$3". 
Meld je eigen noen an en wiezig t wachtwoord.

Negeer dit bericht as disse gebruker zonder joew toestemming an-emaakt is.',
'usernamehasherror' => "In n gebrukersnaam ma'j gien hekjen gebruken.",
'login-throttled' => "Je hebben lestens te vake eprobeerd um an te melden mit n verkeerd wachtwoord.
Je mutten effen $1 wachten veurda'j t opniej proberen.",
'login-abort-generic' => 'Je bin niet an-emeld. De procedure is aofebreuken.',
'loginlanguagelabel' => 'Taal: $1',
'suspicious-userlogout' => 'Joew verzeuk um of te melden is aofewezen umdat t dernaor uutziet dat t verstuurd is deur n kepotte webkieker of tussenopslagbuffer',
'createacct-another-realname-tip' => "Joew echte naam opgeven is niet verplicht.
A'j t invullen, dan zu'w t gebruken um erkenning te geven veur joew warkzaamhejen.",

# Email sending
'php-mail-error-unknown' => 'Der was n onbekende fout mit de mail()-funksie van PHP',
'user-mail-no-addy' => 'Eprobeerd n berichjen te versturen zonder n netpostadres',
'user-mail-no-body' => 'Der is eprobeerd n netbreef zonder tekste of mit n biester korte tekste te versturen.',

# Change password dialog
'resetpass' => 'Wachtwoord wiezigen',
'resetpass_announce' => "Je bin an-emeld mit n veurlopige kode die mit de netpost toe-estuurd wörden. Um t anmelden te voltooien, mu'j n niej wachtwoord invoeren:",
'resetpass_text' => '<!-- Tekste hier invoegen -->',
'resetpass_header' => 'Wachtwoord wiezigen',
'oldpassword' => "Wachtwoord da'j noen hebben",
'newpassword' => 'Niej wachtwoord',
'retypenew' => 'Niej wachtwoord (opniej)',
'resetpass_submit' => 'Voer t wachtwoord in en meld je an',
'changepassword-success' => 'Joew wachtwoord is ewiezigd!',
'resetpass_forbidden' => 'Wachtwoorden kunnen niet ewiezigd wörden',
'resetpass-no-info' => "Je mutten an-emeld ween veurda'j disse zied gebruken kunnen.",
'resetpass-submit-loggedin' => 'Wachtwoord wiezigen',
'resetpass-submit-cancel' => 'Aofbreken',
'resetpass-wrong-oldpass' => "t Veurlopige wachtwoord of t wachtwoord da'j noen hebben is ongeldig.
Misschien he'j t wachtwoord al ewiezigd of n niej veurlopig wachtwoord an-evreugen.",
'resetpass-temp-password' => 'Veurlopig wachtwoord:',
'resetpass-abort-generic' => 'De wachtwoordwieziging is aofebreuken deur n uutbreiding.',

# Special:PasswordReset
'passwordreset' => 'Wachtwoord opniej instellen',
'passwordreset-text-one' => 'Vul dit formulier in um joew wachtwoord opniej in te stellen.',
'passwordreset-text-many' => '{{PLURAL:$1|Vul een van de gegevensvelden in um joew wachtwoord opniej in te stellen.}}',
'passwordreset-legend' => 'Wachtwoord opniej instellen',
'passwordreset-disabled' => 'Je kunnen op disse wiki joew wachtwoord niet opniej instellen.',
'passwordreset-emaildisabled' => 'Netpostmeugelikhejen bin uutezet op disse wiki.',
'passwordreset-username' => 'Gebruker:',
'passwordreset-domain' => 'Domein:',
'passwordreset-capture' => 'De resulterende netpost bekieken?',
'passwordreset-capture-help' => "A'j dit vakjen anvinken, dan krie'j t netpostbericht (mit t tiedelike wachtwoord) te zien en t wörden naor de gebruker estuurd.",
'passwordreset-email' => 'Netpostadres:',
'passwordreset-emailtitle' => 'Gebrukersgegevens op {{SITENAME}}',
'passwordreset-emailtext-ip' => "Der hef der ene, waorschienlik jie zelf vanaof t IP-adres $1, n anvraag edaon um joew wachtwoord veur {{SITENAME}} ($4) opniej in te stellen.
De volgende {{PLURAL:$3|gebruker is|gebrukers bin}} ekoppeld an dit netpostadres:

$2

{{PLURAL:$3|Dit tiejelike wachtwoord vervölt|Disse tiejelike wachtwoorden vervallen}} over {{PLURAL:$5|één dag|$5 dagen}}.
Meld je eigen noen an en wiezig t wachtwoord. A'j dit verzeuk niet zelf edaon hebben, of a'j t oorspronkelike wachtwoord nog kennen en t niet wiezigen willen, negeer dit bericht dan en blief joew ouwe wachtwoord gebruken.",
'passwordreset-emailtext-user' => "De gebruker $1 van {{SITENAME}} hef n anvraag edaon um joew wachtwoord veur {{SITENAME}} ($4) opniej in te stellen. 
De volgende {{PLURAL:$3|gebruker is|gebrukers bin}} ekoppeld an dit netpostadres:

$2

{{PLURAL:$3|Dit tiejelike wachtwoord vervölt|Disse tiejelike wachtwoorden vervallen}} over {{PLURAL:$5|één dag|$5 dagen}}.
Meld je eigen noen an en wiezig t wachtwoord. A'j dit verzeuk niet zelf edaon hebben, of a'j t oorspronkelike wachtwoord nog kennen en t niet wiezigen willen, negeer dit bericht dan en blief joew ouwe wachtwoord gebruken.",
'passwordreset-emailelement' => 'Gebrukersnaam: $1
Tiedelik wachtwoord: $2',
'passwordreset-emailsent' => 'Der is n bericht verstuurd um t wachtwoord opniej in te stellen.',
'passwordreset-emailsent-capture' => "Der is n bericht verstuurd um joew wachtwoord opniej in te stellen. Dit ku'j hieronder lezen.",
'passwordreset-emailerror-capture' => "Der is n bericht veur t opniej opstellen van joew wachwoord an-emaakt, dit ku'j hieronder lezen. t Versturen naor de {{GENDER:$2|gebruker}} is mislokt um de volgende reden: $1",

# Special:ChangeEmail
'changeemail' => 'Wiezig netpostadres',
'changeemail-header' => 'Netpostadres wiezigen',
'changeemail-text' => "Vul dit formulier in um joew netpostadres te wiezigen. Um disse wieziging te bevestigen mu'j je wachtwoord invoeren.",
'changeemail-no-info' => 'Je mutten an-emeld ween um drekt toegang te hebben tot disse zied.',
'changeemail-oldemail' => 't Ouwe netpostadres:',
'changeemail-newemail' => 't Nieje netpostadres:',
'changeemail-none' => '(gien)',
'changeemail-password' => 'Joew wachtwoord veur {{SITENAME}}:',
'changeemail-submit' => 'Netpostadres wiezigen',
'changeemail-cancel' => 'Aofbreken',

# Special:ResetTokens
'resettokens' => 'Tokens ongedaonmaken',
'resettokens-text' => "Je kunnen hier tokens opniej instellen die toegang geven tot bepaolde persoonlike gegevens die ekoppeld bin an joew gebruker.

Do dit a'j ze per ongelok mit ene edeeld hebben of as onbevoegden toegang ekregen hebben tot joew gebruker.",
'resettokens-no-tokens' => 'Der bin gien tokens um ongedaon te maken.',
'resettokens-legend' => 'Tokens ongedaonmaken',
'resettokens-tokens' => 'Tokens:',
'resettokens-token-label' => '$1 (aktuele weerde: $2)',
'resettokens-watchlist-token' => 'Token veur webvoer (Atom/RSS) van [[Special:Watchlist|wiezigingen van ziejen die joew volglieste staon]]',
'resettokens-done' => 'Tokens ongedaonmaken.',
'resettokens-resetbutton' => 'Ekeuzen tokens ongedaonmaken',

# Edit page toolbar
'bold_sample' => 'Vet-edrokten tekste',
'bold_tip' => 'Vet-edrokten tekste',
'italic_sample' => 'Schunedrokken tekste',
'italic_tip' => 'Schunedrok',
'link_sample' => 'Onderwarp',
'link_tip' => 'Interne verwiezing',
'extlink_sample' => 'http://www.example.com verwiezingstekste',
'extlink_tip' => 'Uutgaonde verwiezing',
'headline_sample' => 'Deelonderwarp',
'headline_tip' => 'Deelonderwarp',
'nowiki_sample' => 'Tekste zonder wiki-opmaak.',
'nowiki_tip' => 'Gien wiki-opmaak toepassen',
'image_sample' => 'Veurbeeld.jpg',
'image_tip' => 'Mediabestaand',
'media_sample' => 'Veurbeeld.ogg',
'media_tip' => 'Verwiezing naor bestaand',
'sig_tip' => 'Joew ondertekening (mit daotum en tied)',
'hr_tip' => 'Horizontale liende',

# Edit pages
'summary' => 'Samenvatting:',
'subject' => 'Onderwarp:',
'minoredit' => 'kleine wieziging',
'watchthis' => 'Volg disse zied',
'savearticle' => 'Zied opslaon',
'preview' => 'Naokieken',
'showpreview' => 'Bewarking naokieken',
'showlivepreview' => 'Drekte weergave',
'showdiff' => 'Verschil bekieken',
'anoneditwarning' => "'''Waorschuwing:''' je bin niet an-emeld.
Joew IP-adres zal op-esleugen wörden a'j wiezigingen op disse zied anbrengen.",
'anonpreviewwarning' => "''Je bin niet an-emeld.''
''Deur de bewarking op te slaon wörden joew IP-adres op-esleugen in de ziedgeschiedenisse.''",
'missingsummary' => "'''Herinnering:''' je hebben gien samenvatting op-egeven veur de bewarking. A'j noen weer op ''Opslaon'' klikken wörden de bewarking zonder samenvatting op-esleugen.",
'missingcommenttext' => 'Plaots joew opmarking hieronder.',
'missingcommentheader' => "'''Waorschuwing:''' je hebben der gien onderwarptitel bie ezet. A'j noen weer op \"{{int:savearticle}}\" klikken, dan wörden de bewarking op-esleugen zonder onderwarptitel.",
'summary-preview' => 'Samenvatting naokieken:',
'subject-preview' => 'Onderwarp/kop naokieken:',
'blockedtitle' => 'Gebruker is eblokkeerd',
'blockedtext' => "'''Joew gebrukersnaam of IP-adres is eblokkeerd.'''

Je bin eblokkeerd deur: $1.
De op-egeven reden is: ''$2''.

* Eblokkeerd vanaof: $8
* Eblokkeerd tot: $6
* Bedoeld um te blokkeren: $7

Je kunnen kontakt opnemen mit $1 of n aandere [[{{MediaWiki:Grouppage-sysop}}|beheerder]] um de blokkering te bepraoten.
Je kunnen gien gebruukmaken van de funksie 'een bericht sturen', behalven a'j n geldig netpostadres op-egeven hebben in joew [[Special:Preferences|veurkeuren]] en t gebruuk van disse funksie niet eblokkeerd is.
t IP-adres da'j noen gebruken is $3 en t blokkeringsnummer is #$5.
Vermeld t allebeie a'j argens op disse blokkering reageren.",
'autoblockedtext' => 'Joew IP-adres is automaties eblokkeerd umdat t gebruukt wörden deur n aandere gebruker, die eblokkeerd wörden deur $1.
De reden hierveur was:

:\'\'$2\'\'

* Begint: $8
* Löp of nao: $6
* Wee eblokkeerd wörden: $7

Je kunnen kontakt opnemen mit $1 of n van de aandere
[[{{MediaWiki:Grouppage-sysop}}|beheerders]] um de blokkering te bepraoten.

NB: je kunnen de opsie "n bericht sturen" niet gebruken, behalven a\'j n geldig netpostadres op-egeven hebben in de [[Special:Preferences|gebrukersveurkeuren]] en je niet eblokkeerd bin.

Joew IP-adres is $3 en joew blokkeernummer is $5.
Geef disse nummers deur a\'j kontakt mit ene opnemen over de blokkering.',
'blockednoreason' => 'gien reden op-egeven',
'whitelistedittext' => "Um ziejen te kunnen wiezigen, mu'j $1 ween",
'confirmedittext' => "Je mutten je netpostadres bevestigen veurda'j bewarken kunnen. Vul je adres in en bevestig t via [[Special:Preferences|mien veurkeuren]].",
'nosuchsectiontitle' => 'Disse seksie besteet niet',
'nosuchsectiontext' => 'Je proberen n seksie te bewarken dat niet besteet.
t Kan ween dat t herneumd is of dat t vortedaon is to jie t an t bekieken waren.',
'loginreqtitle' => 'Anmelden verplicht',
'loginreqlink' => 'Anmelden',
'loginreqpagetext' => 'Je mutten $1 um disse zied te bekieken.',
'accmailtitle' => 'Wachtwoord is verstuurd.',
'accmailtext' => "Der is n willekeurig wachtwoord veur [[User talk:$1|$1]] verstuurd naor $2. t Kan ewiezigd wörden op de zied ''[[Special:ChangePassword|wachtwoord wiezigen]]'' naoda'j an-emeld bin.",
'newarticle' => '(Niej)',
'newarticletext' => "Disse zied besteet nog niet.
In t veld hieronder ku'j wat schrieven um disse zied an te maken (meer informasie vie'j op de [[{{MediaWiki:Helppage}}|hulpzied]]).
A'j hier per ongelok terechtekeumen bin gebruuk dan de knoppe '''veurige''' um weerumme te gaon.",
'anontalkpagetext' => "---- ''Disse overlegzied heurt bie n anonieme gebruker die nog gien gebrukersnaam hef, of t niet gebruukt. We gebruken daorumme t IP-adres um hum of heur te herkennen, mer t kan oek ween dat meerdere personen t zelfde IP-adres gebruken, en da'j hiermee berichten ontvangen die niet veur joe bedoeld bin. A'j dit veurkoemen willen, dan ku'j t best [[Special:UserLogin/signup|n gebrukersnaam anmaken]] of [[Special:UserLogin|anmelden]].''",
'noarticletext' => 'Der steet noen gien tekste op disse zied.
Je kunnen [[Special:Search/{{PAGENAME}}|de titel opzeuken]] in aandere ziejen,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} zeuken in de logboeken],
of [{{fullurl:{{FULLPAGENAME}}|action=edit}} disse zied bewarken]</span>.',
'noarticletext-nopermission' => 'Op disse zied steet gien tekste.
Je kunnen [[Special:Search/{{PAGENAME}}|zeuken naor disse term]] in aandere ziejen of
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} de logboeken deurzeuken]</span>, mer je hebben gien rechten um disse zied an te maken.',
'missing-revision' => 'De versie #$1 van de zied "{{PAGENAME}} besteet niet.

Dit kömp meestentieds deur t volgen van n verouwerde verwiezing naor n zied die vortedaon is.
Waorschienlik ku\'j der meer gegevens over vienen in t [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} vortdologboek].',
'userpage-userdoesnotexist' => 'Je bewarken n gebrukerszied van n gebruker die niet besteet (gebruker "<nowiki>$1</nowiki>"). Kiek effen nao o\'j disse zied wel anmaken/bewarken willen.',
'userpage-userdoesnotexist-view' => 'Gebruker "$1" steet hier niet in-eschreven',
'blocked-notice-logextract' => 'Disse gebruker is op t moment eblokkeerd.
De leste regel uut t blokkeerlogboek steet hieronder as referensie:',
'clearyourcache' => "'''Waort je:''' naodat de wiezigingen op-esleugen bin, mut t tussengeheugen van de webkieker nog eleegd wörden um t te kunnen zien. 
*'''Firefox / Safari:''' drok op ''Shift'' terwiel je op ''verniejen'' klikken, of gebruuk ''Ctrl-F5'' of ''Ctrl-R'' (''⌘-R'' op n knipperkiste van Mac)
* '''Google Chrome:''' drok op ''Ctrl-Shift-R'' (''⌘-Shift-R'' op n knipperkiste van Mac)
*'''Internet Explorer:''' drok op ''Ctrl'' terwiel je op ''verniejen'' klikken of drok op ''Ctrl-F5''
*'''Opera:''' leeg t tussengeheugen in ''Extra → Voorkeuren\"",
'usercssyoucanpreview' => "'''Tip:''' gebruuk de knoppe \"{{int:showpreview}}\" um joew nieje css/js nao te kieken veurda'j t opslaon.",
'userjsyoucanpreview' => "'''Tip:''' gebruuk de knoppe \"{{int:showpreview}}\" um joew nieje css/js nao te kieken veurda'j t opslaon.",
'usercsspreview' => "'''Dit is allinnig n naokieksel van joew persoonlike CSS.'''
'''t Is nog niet op-esleugen!'''",
'userjspreview' => "'''Denk deran da'j joew persoonlike JavaScript allinnig nog mer an t bekieken bin, t is nog niet op-esleugen!'''",
'sitecsspreview' => "'''Je bin allinnig mer de CSS an t naokieken.'''
'''t Is nog niet op-esleugen!'''",
'sitejspreview' => "'''Je bin allinnig mer de JavaScript-kode an t naokieken.'''
'''t Is nog niet op-esleugen!'''",
'userinvalidcssjstitle' => "'''Waorschuwing:''' der is gien uutvoering mit de naam \"\$1\". Vergeet niet dat joew eigen .css- en .js-ziejen beginnen mit n kleine letter, bv. \"{{ns:user}}:Naam/'''v'''ector\" in plaotse van \"{{ns:user}}:Naam/'''V'''ector.css\".",
'updated' => '(Bewark)',
'note' => "'''Opmarking:'''",
'previewnote' => "'''Waort je: dit is n naokiekzied.'''
Joew tekste is niet op-esleugen!",
'continue-editing' => 'Gao naor t bewarkingsvienster',
'previewconflict' => "Disse versie löt zien hoe de tekste in t bovenste veld deruut kömp te zien a'j de tekste opslaon.",
'session_fail_preview' => "'''De bewarking kan niet verwarkt wörden wegens n verlies an data.'''
Probeer t laoter weer.
As t probleem dan nog steeds veurkömp, probeer dan [[Special:UserLogout|opniej an te melden]].",
'session_fail_preview_html' => "'''De bewarking kan niet verwarkt wörden wegens n verlies an data.'''

''Umdat in {{SITENAME}} roewe HTML in-eschakeld is, is de weergave dervan verbörgen um te veurkoemen dat t JavaScript an-evöllen wörden.''

'''As dit n legitieme wieziging is, probeer t dan opniej.'''
As t dan nog problemen gif, probeer dan um [[Special:UserLogout|opniej an te melden]].",
'token_suffix_mismatch' => "'''De bewarking is eweigerd umdat de webkieker de leestekens in t bewarkingstoken verkeerd behaandeld hef. De bewarking is eweigerd um verminking van de ziedtekste te veurkoemen. Dit gebeurt soms as der n web-ebaseerden proxydienst gebruukt wörden waor fouten in zitten.'''",
'edit_form_incomplete' => "'''Partie delen van t bewarkingsformulier hebben de server niet bereikt. Kiek eers nao of de bewarkingen kloppen en probeer t opniej.'''",
'editing' => 'Bewarken: $1',
'creating' => 'Anmaken: $1',
'editingsection' => 'Bewarken: $1 (deelzied)',
'editingcomment' => 'Bewarken: $1 (niej onderwarp)',
'editconflict' => 'Tegelieke bewörken: $1',
'explainconflict' => "'''NB:''' n aander hef disse zied ewiezigd naoda'j an disse bewarking begunnen bin.
t Bovenste bewarkingsveld löt de zied zien zo as t noen is.
Daoronder (bie \"Wiezigingen\") staon de verschillen tussen joew versie en de op-esleugen zied.
Helemaole onderan (bie \"Joew tekste\") steet nog n bewarkingsveld mit joew versie.
Je zullen je eigen wiezigingen in de nieje tekste in mutten passen.
'''Allinnig''' de tekste in t bovenste veld wörden beweerd a'j noen kiezen veur \"{{int:savearticle}}\".",
'yourtext' => 'Joew tekste',
'storedversion' => 'Op-esleugen versie',
'nonunicodebrowser' => "'''Waorschuwing: joew webkieker kan niet goed mit unikode uut de voten, schakel over op n aandere webkieker um de wiezigingen an te brengen!'''",
'editingold' => "'''Waorschuwing: je bewarken noen n ouwe versie van disse zied. A'j de wiezigingen opslaon, bi'j alle niejere versies kwiet.'''",
'yourdiff' => 'Wiezigingen',
'copyrightwarning' => "Waort je dat alle biedragen an {{SITENAME}} vrie-egeven mutten wörden onder de \$2 (zie \$1 veur meer informasie).
A'j niet willen dat joew tekste deur aander volk bewarkt en verspreid kan wörden, slao de tekste dan niet op.<br />
Deur op \"Zied opslaon\" te klikken beleuf je ons da'j disse tekste zelf eschreven hebben, of over-eneumen hebben uut n vrieje, openbaore bron.<br />
'''Gebruuk gien spul mit auteursrechten, a'j daor gien toestemming veur hebben!'''",
'copyrightwarning2' => "Waort je dat alle biedragen an {{SITENAME}} deur aander volk bewarkt of vortedaon kan wörden. A'j niet willen dat joew tekste deur aander volk bewarkt wörden, slao de tekste dan niet op.<br />
Deur op \"Zied opslaon\" te klikken beleuf je ons da'j disse tekste zelf eschreven hebben, of over-eneumen hebben uut n vrieje, openbaore bron (zie \$1 veur meer informasie).
'''Gebruuk gien spul mit auteursrechten, a'j daor gien toestemming veur hebben!'''",
'longpageerror' => "'''Foutmelding: de tekste die'j opslaon willen is {{PLURAL:$1|een kilobyte|$1 kilobytes}}. Dit is groter as t toe-estaone maximum van $2 kilobytes. Joew tekste kan niet op-esleugen wörden.'''",
'readonlywarning' => "'''Waorschuwing: De databanke is op dit moment in onderhoud; t is daorumme niet meugelik um ziejen te wiezigen.
Je kunnen de tekste t beste bie joew eigen systeem opslaon en laoter opniej proberen de zied te bewarken.'''

As reden is an-egeven: $1",
'protectedpagewarning' => "'''Waorschuwing: disse zied is beveiligd, zodat allinnig beheerders t kunnen wiezigen.'''
De leste logboekregel steet hieronder:",
'semiprotectedpagewarning' => "'''Let op:''' disse zied is beveiligd en ku'j allinnig bewarken a'j n eregistreerden gebruker bin.
De leste logboekregel steet hieronder:",
'cascadeprotectedwarning' => "'''Waorschuwing:''' disse zied is beveiligd, zodat allinnig beheerders disse zied bewarken kunnen, dit wörden edaon umdat disse zied veurkömp in de volgende {{PLURAL:$1|kaskade-beveiligden zied|kaskade-beveiligden ziejen}}:",
'titleprotectedwarning' => "'''Waorschuwing: disse zied is beveiligd. Je hebben [[Special:ListGroupRights|bepaolde rechten]] neudig um t an te kunnen maken.'''
De leste logboekregel steet hieronder:",
'templatesused' => '{{PLURAL:$1|Mal|Mallen}} die op disse zied gebruukt wörden:',
'templatesusedpreview' => '{{PLURAL:$1|Mal|Mallen}} die in disse bewarking gebruukt wörden:',
'templatesusedsection' => '{{PLURAL:$1|Mal|Mallen}} die in dit subkopjen gebruukt wörden:',
'template-protected' => '(beveiligd)',
'template-semiprotected' => '(half-beveiligd)',
'hiddencategories' => 'Disse zied völt in de volgende verbörgen {{PLURAL:$1|kategorie|kategorieën}}:',
'edittools' => '<!-- Disse tekste steet onder de bewarkings- en bestaandinlaodformulieren. -->',
'nocreatetext' => 'Disse webstee hef de meugelikheid um nieje ziejen an te maken beteund. Je kunnen ziejen die al bestaon wiezigen of je kunnen je [[Special:UserLogin|anmelden of n gebrukerszied anmaken]].',
'nocreate-loggedin' => 'Je hebben gien toestemming um nieje ziejen an te maken.',
'sectioneditnotsupported-title' => 't Bewarken van seksies wörden niet ondersteund',
'sectioneditnotsupported-text' => 'Je kunnen op disse zied gien seksies bewarken.',
'permissionserrors' => 'Gien toestemming',
'permissionserrorstext' => 'Je maggen of kunnen dit niet doon. De {{PLURAL:$1|reden|redens}} daorveur {{PLURAL:$1|is|bin}}:',
'permissionserrorstext-withaction' => 'Je hebben gien rech um $2, mit de volgende {{PLURAL:$1|reden|redens}}:',
'recreate-moveddeleted-warn' => "'''Waorschuwing: je maken n zied an die eerder al vortedaon is.'''

Bedenk eerst of t neudig is um disse zied veerder te bewarken.
Veur de dudelikheid steet hieronder  t vortdologboek en t herneumlogboek veur disse zied:",
'moveddeleted-notice' => 'Disse zied is vortedaon.
Hieronder steet de informasie uut t vortdologboek en t herneumlogboek.',
'log-fulllog' => 't Hele logboek bekieken',
'edit-hook-aborted' => 'De bewarking is aofebreuken deur n hook.
Der is gien reden op-egeven.',
'edit-gone-missing' => 'De zied kon niet bie-ewörken wörden.
t Schient dat t vortedaon is.',
'edit-conflict' => 'Tegelieke bewörken.',
'edit-no-change' => 'Joew bewarking is enegeerd, umdat der gien wieziging an de tekste edaon is.',
'postedit-confirmation' => 'Joew bewarking is op-esleugen',
'edit-already-exists' => 'De zied kon niet an-emaakt wörden.
t Besteet al.',
'defaultmessagetext' => 'Standardtekste',
'content-failed-to-parse' => 'Kon de inhoud van t MIME-type $2 veur t model $1 niet verwarken: $3.',
'invalid-content-data' => 'Ongeldige inhoudsgegevens',
'content-not-allowed-here' => 'De inhoud "$1" is niet toe-estaan op de zied [[$2]].',
'editwarning-warning' => "A'j disse zied verlaoten dan bi'j de wieziging die'j emaakt hebben waorschienlik kwiet.
A'j an-emeld bin, dan ku'j disse waorschuwing uutzetten in t tabblad \"Bewarkingsveld\" in joew veurkeuren.",

# Content models
'content-model-wikitext' => 'wikitekste',
'content-model-text' => 'tekste zonder opmaak',
'content-model-javascript' => 'JavaScript',
'content-model-css' => 'CSS',

# Parser/template warnings
'expensive-parserfunction-warning' => 'Waorschuwing: disse zied gebruukt te veule kostbaore parserfunksies.

Noen {{PLURAL:$1|is|bin}} t der $1, terwiel t der minder as $2 {{PLURAL:$2|mut|mutten}} ween.',
'expensive-parserfunction-category' => 'Ziejen die te veule kostbaore parserfunksies gebruken',
'post-expand-template-inclusion-warning' => 'Waorschuwing: de grootte van de in-evoegden mal is te groot.
Sommigen mallen wörden niet in-evoegd.',
'post-expand-template-inclusion-category' => 'Ziejen die over de maximumgrootte veur in-evoegden mallen hinne gaon',
'post-expand-template-argument-warning' => 'Waorschuwing: disse zied gebruuk tenminsten één parameter in n mal, die te groot is as t uuteklap wörden. Disse parameters wörden vorteleuten.',
'post-expand-template-argument-category' => 'Ziejen mit ontbrekende malelementen',
'parser-template-loop-warning' => 'Der is n kringloop in mallen waoreneumen: [[$1]]',
'parser-template-recursion-depth-warning' => 'Der is over de rekursiediepte veur mallen is hinne gaon ($1)',
'language-converter-depth-warning' => 'Je hebben t dieptelimiet veur de taalumzetter bereikt ($1)',
'node-count-exceeded-category' => 'Ziejen die t knuppenantal overschrejen hebben.',
'node-count-exceeded-warning' => 'Op de zied is t maximale antal nodes overschrejen',
'expansion-depth-exceeded-category' => 'Ziejen waor de expansiediepte overschrejen is',
'expansion-depth-exceeded-warning' => 'Op disse zied staon te veule mallen',
'parser-unstrip-loop-warning' => 'Der is n "unstrip"-lusse evunnen',
'parser-unstrip-recursion-limit' => 'De rekursielimiet ($1) veur "unstrip" is overschrejen',
'converter-manual-rule-error' => 'Der is n fout evunnen in n haandmaotig in-evoegden taalkonversieregel.',

# "Undo" feature
'undo-success' => 'De bewarking kan weerummedreid wörden. Kiek de vergelieking hieronder nao um der wisse van de ween dat alles goed is, en slao de de zied op um de bewarking weerumme te dreien.',
'undo-failure' => 'De wieziging kon niet weerummedreid wörden umdat t ondertussen awweer ewiezigd is.',
'undo-norev' => 'De bewarking kon niet weerummedreid wörden, umdat t niet besteet of vortedaon is.',
'undo-summary' => 'Versie $1 van [[Special:Contributions/$2|$2]] ([[User talk:$2|overleg]]) weerummedreid.',
'undo-summary-username-hidden' => 'Versie $1 deur n verbörgen gebruker weerummedreid',

# Account creation failure
'cantcreateaccounttitle' => 'Anmaken van n gebrukersprofiel is niet meugelik',
'cantcreateaccount-text' => "t Anmaken van gebrukers van dit IP-adres (<b>$1</b>) is eblokkeerd deur [[User:$3|$3]].

De deur $3 op-egeven reden is ''$2''",

# History pages
'viewpagelogs' => 'Bekiek logboeken veur disse zied',
'nohistory' => 'Der bin gien eerdere versies van disse zied.',
'currentrev' => 'Leste versie',
'currentrev-asof' => 'Leste versie van $1',
'revisionasof' => 'Versie op $1',
'revision-info' => 'Versie op $1 van $2',
'previousrevision' => '&larr; eerdere versie',
'nextrevision' => 'niejere versie &rarr;',
'currentrevisionlink' => 'versie zo as t noen is',
'cur' => 'noen',
'next' => 'Volgende',
'last' => 'leste',
'page_first' => 'eerste',
'page_last' => 'leste',
'histlegend' => 'Verklaoring aofkortingen: (noen) = verschil mit de op-esleugen versie, (veurige) = verschil mit de veurige versie, K = kleine wieziging',
'history-fieldset-title' => 'Deur de geschiedenisse blaojen',
'history-show-deleted' => 'Allinnig vortedaon',
'histfirst' => 'Eerste',
'histlast' => 'Leste',
'historysize' => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty' => '(leeg)',

# Revision feed
'history-feed-title' => 'Wiezigingsoverzichte',
'history-feed-description' => 'Wiezigingsoverzichte veur disse zied op de wiki',
'history-feed-item-nocomment' => '$1 op $2',
'history-feed-empty' => 'De op-evreugen zied besteet niet. t Kan ween dat disse zied vortedaon is of dat t herneumd is. Probeer te [[Special:Search|zeuken]] naor soortgelieke nieje ziejen.',

# Revision deletion
'rev-deleted-comment' => '(bewarkingsopmarking vortedaon)',
'rev-deleted-user' => '(gebrukersnaam vortedaon)',
'rev-deleted-event' => '(antekening vortedaon)',
'rev-deleted-user-contribs' => '[gebrukersnaam of IP-adres vortedaon - bewarking verbörgen in biedragen]',
'rev-deleted-text-permission' => "Disse bewarking is '''vortedaon'''.
As der meer informasie is, ku'j t vienen in t [{{fullurl:{{#Special:Log}}/delete|page={{PAGENAMEE}}}} vortdologboek].",
'rev-deleted-text-unhide' => "Disse bewarking is '''vortedaon'''.
As der meer informasie is, ku'j t vienen in t [{{fullurl:{{#Special:Log}}/delete|page={{PAGENAMEE}}}} vortdologboek].
Je kunnen [$1 disse versie bekieken] a'j willen.",
'rev-suppressed-text-unhide' => "Disse bewarking is '''onderdrokt'''.
As der meer informasie is, ku'j t vienen in t [{{fullurl:{{#Special:Log}}/suppress|page={{PAGENAMEE}}}} logboek mit onderdrokten informasie].
Je kunnen [$1 disse versie bekieken] a'j willen.",
'rev-deleted-text-view' => "Disse bewarking is '''vortedaon'''.
Je kunnnen t bekieken; as der meer informasie is, ku'j dat vienen in t [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} vortdologboek].",
'rev-suppressed-text-view' => "Disse bewarking is '''onderdrok'''.
Je kunnen t bekieken; as der meer informasie is, ku'j dat vienen in t [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} logboek mit onderdrokten versies].",
'rev-deleted-no-diff' => "Je kunnen de verschillen niet bekieken umdat één van de versies '''vortedaon''' is.
As der meer informasie is, ku'j t vienen in t [{{fullurl:{{#Special:Log}}/delete|page={{PAGENAMEE}}}} vortdologboek].",
'rev-suppressed-no-diff' => "Je kunnen de verschillen niet bekieken umdat één van de versies '''vortedaon''' is.",
'rev-deleted-unhide-diff' => "Eén van de bewarkingen in disse vergeliekingen is '''vortedaon'''.
As der meer informasie is, ku'j t vienen in t [{{fullurl:{{#Special:Log}}/delete|page={{PAGENAMEE}}}} vortdologboek].
Je kunnen [$1 de verschillen bekieken] a'j willen.",
'rev-suppressed-unhide-diff' => "Eén van de bewarkingen in disse vergeliekingen is '''vortedaon'''.
As der meer informasie is, ku'j t vienen in t [{{fullurl:{{#Special:Log}}/suppress|page={{PAGENAMEE}}}} logboek mit onderdrokten informasie].
Je kunnen [$1 de verschillen bekieken] a'j willen.",
'rev-deleted-diff-view' => "Eén van de bewarkingen veur de verschillen die'j op-evreugen hebben, is '''vortedaon'''.
Je kunnen disse verschillen bekieken. Misschien steet der meer over in t [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} vortdologboek].",
'rev-suppressed-diff-view' => "Eén van de bewarkingen veur de verschillen die'j op-evreugen hebben, is '''onderdrokt'''.
Je kunnen disse verschillen bekieken. Misschien steet der over in t [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} logboek mit onderdrokten versies].",
'rev-delundel' => 'bekiek/verbarg',
'rev-showdeleted' => 'bekiek',
'revisiondelete' => 'Wiezigingen vortdoon/herstellen',
'revdelete-nooldid-title' => 'Gien doelversie',
'revdelete-nooldid-text' => 'Je hebben gien versie an-egeven waor disse aksie op uutevoerd mut wörden.',
'revdelete-nologtype-title' => 'Der is gien logboektype op-egeven',
'revdelete-nologtype-text' => 'Je hebben gien logboektype op-egeven um disse haandeling op uut te voeren.',
'revdelete-nologid-title' => 'Ongeldige logboekregel',
'revdelete-nologid-text' => 'Of je hebben gien doellogboekregel op-egeven of de an-egeven logboekregel besteet niet.',
'revdelete-no-file' => 't Op-egeven bestaand besteet niet.',
'revdelete-show-file-confirm' => 'Bi\'j der wisse van da\'j de vortedaone versie van t bestaand "<nowiki>$1</nowiki>" van $2 um $3 bekieken willen?',
'revdelete-show-file-submit' => 'Ja',
'revdelete-selected' => "'''{{PLURAL:$2|Ekeuzen bewarking|Ekeuzen bewarkingen}} van '''[[:$1]]''':'''",
'logdelete-selected' => "'''{{PLURAL:$1|Ekeuzen logboekboekaksie|Ekeuzen logboekaksies}}:'''",
'revdelete-text' => "'''Vortedaone bewarkingen staon nog altied in de geschiedenisse en in logboeken, mer niet iederene kan de inhoud zo mer bekieken.'''
Beheerders van {{SITENAME}} kunnen de verbörgen inhoud bekieken en t weerummeplaotsen deur dit scharm te gebruken, behalven as der aandere beparkingen in-esteld bin.",
'revdelete-confirm' => "Bevestig da'j dit doon wollen, da'j de gevolgen dervan begriepen en da'j t doon in overeenstemming mit t geldende [[{{MediaWiki:Policy-url}}|beleid]].",
'revdelete-suppress-text' => "Onderdrokken ma'j '''allinnig''' gebruken in de volgende gevallen:
* Ongepassen persoonlike informasie
*: ''adressen en tillefoonnummers, burgerservicenummers, en gao zo mer deur.''",
'revdelete-legend' => 'Stel versiebeparkingen in:',
'revdelete-hide-text' => 'Verbarg de bewarken tekste',
'revdelete-hide-image' => 'Verbarg bestaandsinhoud',
'revdelete-hide-name' => 'Verbarg logboekaksie',
'revdelete-hide-comment' => 'Verbarg bewarkingssamenvatting',
'revdelete-hide-user' => 'Verbarg gebrukersnamen en IP-adressen van aandere luui.',
'revdelete-hide-restricted' => 'Gegevens veur beheerders en aander volk onderdrokken',
'revdelete-radio-same' => '(niet wiezigen)',
'revdelete-radio-set' => 'Ja',
'revdelete-radio-unset' => 'Nee',
'revdelete-suppress' => 'Gegevens veur beheerders en aander volk onderdrokken',
'revdelete-unsuppress' => 'Beparkingen veur weerummezetten versies vortdoon',
'revdelete-log' => 'Reden:',
'revdelete-submit' => 'Toepassen op de ekeuzen {{PLURAL:$1|bewarking|bewarkingen}}',
'revdelete-success' => "'''De zichtbaorheid van de wieziging is bie-ewörken.'''",
'revdelete-failure' => "'''De zichtbaorheid veur de wieziging kon niet bie-ewörken wörden:'''
$1",
'logdelete-success' => "'''Zichtbaorheid van de gebeurtenisse is suksesvol in-esteld.'''",
'logdelete-failure' => "'''De zichtbaorheid van de logboekregel kon niet in-esteld wörden:'''
$1",
'revdel-restore' => 'Zichtbaorheid wiezigen',
'revdel-restore-deleted' => 'vortedaone versies',
'revdel-restore-visible' => 'zichtbaore versies',
'pagehist' => 'Ziedgeschiedenisse',
'deletedhist' => 'Geschiedenisse die vortehaold is',
'revdelete-hide-current' => 'Fout bie t verbargen van t objekt van $1 um $2 uur: dit is de versie van noen.
Disse versie kan niet verbörgen wörden.',
'revdelete-show-no-access' => 'Fout bie t weergeven van t objekt van $1 um $2 uur: dit objekt is emarkeerd as "beveilig".
Je hebben gien toegang tot dit objekt.',
'revdelete-modify-no-access' => 'Fout bie t wiezigen van t objekt van $1 um $2 uur: dit objekt is emarkeerd as "beveilig".
Je hebben gien toegang tot dit objekt.',
'revdelete-modify-missing' => 'Fout bie t wiezigen van versienummer $1: t kömp niet veur in de databanke!',
'revdelete-no-change' => "'''Waorschuwing:''' t objekt van $1 um $2 uur had al de an-egeven zichtbaorheidsinstellingen.",
'revdelete-concurrent-change' => 'Fout bie t wiezigen van t objekt van $1 um $2 uur: de staotus is inmiddels ewiezigd deur n aander.
Kiek de logboeken nao.',
'revdelete-only-restricted' => 'Der is n fout op-etrejen bie t verbargen van t objekt van $1, $2: je kunnen gien objekten onderdrokken uut t zich van beheerders zonder oek n van de aandere zichtbaorheidsopsies te selekteren.',
'revdelete-reason-dropdown' => '*Veulveurkoemde redens veur t vortdoon
** Schenden van de auteursrechten
** Ongepast kommentaar of ongepaste persoonlike informasie
** Ongepaste gebrukersnaam
** Meugelik lasterlike informasie',
'revdelete-otherreason' => 'Aandere reden:',
'revdelete-reasonotherlist' => 'Aandere reden',
'revdelete-edit-reasonlist' => 'Redens veur t vortdoon bewarken',
'revdelete-offender' => 'Auteur versie:',

# Suppression log
'suppressionlog' => 'Verbargingslogboek',
'suppressionlogtext' => 'In de onderstaande lieste staon de vortedaone ziejen en blokkeringen die veur beheerders verbörgen bin. 
In de [[Special:BlockList|blokkeerlieste]] bin de blokkeringen, die noen van toepassige bin, te bekieken.',

# History merging
'mergehistory' => 'Geschiedenisse van ziejen bie mekaar doon',
'mergehistory-header' => "Via disse zied ku'j versies uut de geschiedenisse van n bronzied mit n niejere zied samenvoegen. Zörg derveur dat disse versies uut de geschiedenisse histories juus bin.",
'mergehistory-box' => 'Geschiedenisse van twee ziejen bie mekaar doon:',
'mergehistory-from' => 'Bronzied:',
'mergehistory-into' => 'Bestemmingszied:',
'mergehistory-list' => 'Bewarkingsgeschiedenisse die bie mekaar edaon kan wörden',
'mergehistory-merge' => 'De volgende versies van [[:$1]] kunnen samenevoegd wörden naor [[:$2]]. Gebruuk de kolom mit keuzerondjes um allinnig de versies die emaak bin op en veur de an-egeven tied samen te voegen. Let op dat t gebruken van de navigasieverwiezingen disse kolom zal herinstellen.',
'mergehistory-go' => 'Bekiek bewarkingen die bie mekaar edaon kunnen wörden',
'mergehistory-submit' => 'Versies bie mekaar doon',
'mergehistory-empty' => 'Der bin gien versies die samenevoegd kunnen wörden.',
'mergehistory-success' => '$3 {{PLURAL:$3|versie|versies}} van [[:$1]] bin suksesvol samenevoegd naor [[:$2]].',
'mergehistory-fail' => 'Kan gien geschiedenisse samenvoegen, kiek opniej de zied- en tiedparameters nao.',
'mergehistory-no-source' => 'Bronzied $1 besteet niet.',
'mergehistory-no-destination' => 'Bestemmingszied $1 besteet niet.',
'mergehistory-invalid-source' => 'De bronzied mut n geldige titel ween.',
'mergehistory-invalid-destination' => 'De bestemmingszied mut n geldige titel ween.',
'mergehistory-autocomment' => '[[:$1]] samenevoegd naor [[:$2]]',
'mergehistory-comment' => '[[:$1]] samenevoegd naor [[:$2]]: $3',
'mergehistory-same-destination' => 'De bronzied en doelzied kunnen niet t zelfde ween',
'mergehistory-reason' => 'Reden:',

# Merge log
'mergelog' => 'Samenvoegingslogboek',
'pagemerge-logentry' => 'voegen [[$1]] naor [[$2]] samen (versies tot en mit $3)',
'revertmerge' => 'Samenvoeging weerummedreien',
'mergelogpagetext' => "Hieronder zie'j n lieste van de leste samenvoegingen van n ziedgeschiedenisse naor n aandere.",

# Diffs
'history-title' => 'Versiegeschiedenisse van "$1"',
'difference-title' => 'Verschil tussen versies van "$1"',
'difference-title-multipage' => 'Verschil tussen ziejen "$1" en "$2"',
'difference-multipage' => '(Verschil tussen ziejen)',
'lineno' => 'Regel $1:',
'compareselectedversions' => 'Vergeliek de ekeuzen versies',
'showhideselectedversions' => 'Ekeuzen versies bekieken/verbargen',
'editundo' => 'weerummedreien',
'diff-empty' => '(Gien verschil)',
'diff-multi' => '(Hier {{PLURAL:$1|zit nog 1 versie|zitten nog $1 versies}} van {{PLURAL:$2|1 gebruker|$2 gebrukers}} tussen die der niet bie staon.)',
'diff-multi-manyusers' => '($1 tussenliggende {{PLURAL:$1|versie|versies}} deur meer as $2 {{PLURAL:$2|gebruker|gebrukers}} niet weeregeven)',
'difference-missing-revision' => "{{PLURAL:$2|Eén versie|$2 versies}} van disse verschillen ($1) {{PLURAL:$2|is|bin}} niet evunnen.

Dit kömp meestentieds deur t volgen van n verouwerde verwiezing naor n zied die vortedaon is.
Waorschienlik ku'j der meer gegevens over vienen in t [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} vortdologboek].",

# Search results
'searchresults' => 'Zeukresultaoten',
'searchresults-title' => 'Zeukresultaoten veur "$1"',
'searchresulttext' => 'Veur meer informasie over zeuken op {{SITENAME}}, zie [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => 'Je zöchten naor \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|alle ziejen die beginnen mit "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|alle ziejen die verwiezen naor "$1"]])',
'searchsubtitleinvalid' => 'Veur zeukopdrachte "$1"',
'toomanymatches' => 'Der waren te veule resultaoten. Probeer n aandere zeukopdrachte.',
'titlematches' => 'Overeenkomst mit t onderwarp',
'notitlematches' => 'Gien overeenstemming',
'textmatches' => 'Overeenkomst mit teksten',
'notextmatches' => 'Gien overeenstemming',
'prevn' => 'veurige {{PLURAL:$1|$1}}',
'nextn' => 'volgende {{PLURAL:$1|$1}}',
'prevn-title' => '{{PLURAL:$1|Veurig resultaot|Veurige $1 resultaoten}}',
'nextn-title' => '{{PLURAL:$1|Volgend resultaot|Volgende $1 resultaoten}}',
'shown-title' => 'Laot $1 {{PLURAL:$1|resultaot|resultaoten}} per zied zien',
'viewprevnext' => '($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend' => 'Zeukopsies',
'searchmenu-exists' => "'''Der is n zied mit de naam \"[[:\$1]]\" op disse wiki.'''",
'searchmenu-new' => "'''De zied \"[[:\$1]]\" op disse wiki anmaken!'''",
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Ziednamen mit dit veurvoegsel laoten zien]]',
'searchprofile-articles' => 'Artikels',
'searchprofile-project' => 'Hulp- en projektziejen',
'searchprofile-images' => 'Multimedia',
'searchprofile-everything' => 'Alles',
'searchprofile-advanced' => 'Uutebreid',
'searchprofile-articles-tooltip' => 'Zeuken in $1',
'searchprofile-project-tooltip' => 'Zeuken in $1',
'searchprofile-images-tooltip' => 'Zeuken naor bestaanden',
'searchprofile-everything-tooltip' => 'Alle inhoud deurzeuken (oek overlegziejen)',
'searchprofile-advanced-tooltip' => 'Zeuken in de an-egeven naamruumtes',
'search-result-size' => '$1 ({{PLURAL:$2|1 woord|$2 woorden}})',
'search-result-category-size' => '{{PLURAL:$1|1 kategorielid|$1 kategorielejen}} ({{PLURAL:$2|1 onderkategorie|$2 onderkategorieën}}, {{PLURAL:$3|1 bestaand|$3 bestaanden}})',
'search-result-score' => 'Relevansie: $1%',
'search-redirect' => '(deurverwiezing $1)',
'search-section' => '(onderwarp $1)',
'search-suggest' => 'Bedoelden je: $1',
'search-interwiki-caption' => 'Zusterprojekten',
'search-interwiki-default' => '$1 resultaoten:',
'search-interwiki-more' => '(meer)',
'search-relatedarticle' => 'Verwaant',
'mwsuggest-disable' => 'Zeuksuggesties uutzetten',
'searcheverything-enable' => 'In alle naamruumten zeuken',
'searchrelated' => 'verwaant',
'searchall' => 'alles',
'showingresults' => "Hieronder {{PLURAL:$1|steet '''1''' resultaot|staon '''$1''' resultaoten}}  <b>$1</b> vanaof nummer <b>$2</b>.",
'showingresultsnum' => "Hieronder {{PLURAL:$3|steet '''1''' resultaot|staon '''$3''' resultaoten}} vanaof nummer '''$2'''.",
'showingresultsheader' => "{{PLURAL:$5|Resultaot '''$1''' van '''$3'''|Resultaoten '''$1 - $2''' van '''$3'''}} veur '''$4'''",
'nonefound' => "<strong>Let wel:</strong> standard wörden niet alle naamruumtes deurzöcht. A'j in zeukopdrachte as veurvoegsel \"''all:'' gebruken wörden alle ziejen deurzöcht (oek overlegziejen, mallen en gao zo mer deur). Je kunnen oek n naamruumte as veurvoegsel gebruken.",
'search-nonefound' => 'Der bin gien resultaoten veur de zeukopdrachte.',
'powersearch' => 'Zeuk',
'powersearch-legend' => 'Uutebreid zeuken',
'powersearch-ns' => 'Zeuken in naamruumten:',
'powersearch-redir' => 'Deurverwiezingen bekieken',
'powersearch-field' => 'Zeuken naor',
'powersearch-togglelabel' => 'Selekteren:',
'powersearch-toggleall' => 'Alle',
'powersearch-togglenone' => 'Gien',
'search-external' => 'Extern zeuken',
'searchdisabled' => 'Zeuken in {{SITENAME}} is niet meugelik. Je kunnen gebruukmaken van Google. De gegevens over {{SITENAME}} bin misschien niet bie-ewörken.',
'search-error' => 'Der is wat mis-egaon bie t zeuken: $1',

# Preferences page
'preferences' => 'Veurkeuren',
'mypreferences' => 'Mien veurkeuren',
'prefs-edits' => 'Antal bewarkingen:',
'prefsnologin' => 'Niet an-meld',
'prefsnologintext' => 'Je mutten <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} an-emeld]</span> ween um joew veurkeuren in te kunnen stellen.',
'changepassword' => 'Wachtwoord wiezigen',
'prefs-skin' => '{{SITENAME}}-uterlik',
'skin-preview' => 'bekieken',
'datedefault' => 'Gien veurkeur',
'prefs-beta' => 'Bètafunksies',
'prefs-datetime' => 'Daotum en tied',
'prefs-labs' => 'Alphafunksies',
'prefs-user-pages' => 'Gebrukersziejen',
'prefs-personal' => 'Gebrukersgegevens',
'prefs-rc' => 'Leste wiezigingen',
'prefs-watchlist' => 'Volglieste',
'prefs-watchlist-days' => 'Antal dagen in de volglieste bekieken:',
'prefs-watchlist-days-max' => 'Hooguut $1 {{PLURAL:$1|dag|dagen}}',
'prefs-watchlist-edits' => 'Antal wiezigingen in de uutebreiden volglieste:',
'prefs-watchlist-edits-max' => 'Maximale antal: 1.000',
'prefs-watchlist-token' => 'Volgliestesleutel',
'prefs-misc' => 'Overig',
'prefs-resetpass' => 'Wachtwoord wiezigen',
'prefs-changeemail' => 'Netpostadres wiezigen',
'prefs-setemail' => 'Stel n netpostadres in',
'prefs-email' => 'Instellingen veur netpost',
'prefs-rendering' => 'Ziedweergave',
'saveprefs' => 'Veurkeuren opslaon',
'resetprefs' => 'Standardveurkeuren herstellen',
'restoreprefs' => 'Alle standardinstellingen weerummezetten (veur alle seksies)',
'prefs-editing' => 'Bewarkingsveld',
'rows' => 'Regels',
'columns' => 'Kolommen',
'searchresultshead' => 'Zeukresultaoten',
'resultsperpage' => 'Antal zeukresultaoten per zied',
'stub-threshold' => 'Verwiezingsformattering van <a href="#" class="stub">beginnetjes</a>:',
'stub-threshold-disabled' => 'uutezet',
'recentchangesdays' => 'Antal dagen die "Leste wiezigingen" löt zien:',
'recentchangesdays-max' => '(hooguut $1 {{PLURAL:$1|dag|dagen}})',
'recentchangescount' => 'Standard antal bewarkingen um te laoten zien:',
'prefs-help-recentchangescount' => 'Dit geldt veur leste wiezigingen, ziedgeschiedenisse en logboekziejen',
'prefs-help-watchlist-token2' => "Dit is de geheime sleutel veur de webvoer van joew volglieste.
Iederene die t token kent, kan joew volglieste bekieken, dus deel dit token niet.
Je kunnen de [[Special:ResetTokens|tokens opniej instellen]] a'j dat willen.",
'savedprefs' => 'Veurkeuren bin op-esleugen.',
'timezonelegend' => 'Tiedzone:',
'localtime' => 'Plaotselike tied:',
'timezoneuseserverdefault' => 'Wikistandard gebruken ($1)',
'timezoneuseoffset' => 'Aanders (tiedverschil angeven)',
'timezoneoffset' => 'Tiedverschil¹:',
'servertime' => 'Tied op de server:',
'guesstimezone' => 'Vanuut webkieker overnemen',
'timezoneregion-africa' => 'Afrika',
'timezoneregion-america' => 'Amerika',
'timezoneregion-antarctica' => 'Antarktika',
'timezoneregion-arctic' => 'Arktis',
'timezoneregion-asia' => 'Azië',
'timezoneregion-atlantic' => 'Atlantiese Oseaan',
'timezoneregion-australia' => 'Australië',
'timezoneregion-europe' => 'Europa',
'timezoneregion-indian' => 'Indiese Oseaan',
'timezoneregion-pacific' => 'Stille Oseaan',
'allowemail' => 'Berichten van aandere gebrukers toestaon',
'prefs-searchoptions' => 'Zeukinstellingen',
'prefs-namespaces' => 'Naamruumtes',
'defaultns' => 'Aanders in de volgende naamruumten zeuken:',
'default' => 'standard',
'prefs-files' => 'Bestaanden',
'prefs-custom-css' => 'Persoonlike CSS',
'prefs-custom-js' => 'Persoonlike JS',
'prefs-common-css-js' => 'Edeelden CSS/JS veur elke vormgeving:',
'prefs-reset-intro' => 'Je kunnen disse zied gebruken um joew veurkeuren naor de standardinstellingen weerumme te zetten.
Disse haandeling kan niet ongedaonemaakt wörden.',
'prefs-emailconfirm-label' => 'Netpostbevestiging:',
'youremail' => 'Netpostadres (niet verplicht) *',
'username' => '{{GENDER:$1|Gebrukersnaam}}:',
'uid' => '{{GENDER:$1|Gebrukersnummer}}:',
'prefs-memberingroups' => '{{GENDER:$2|Lid}} van {{PLURAL:$1|groep|groepen}}:',
'prefs-registration' => 'Registrasiedaotum:',
'yourrealname' => 'Echte naam (niet verplicht)',
'yourlanguage' => 'Taal veur systeemteksten',
'yourvariant' => 'Taalvariaant veur inhoud:',
'prefs-help-variant' => 'Joew veurkeursvariaant of -spelling um de inhoudsziejen van disse wiki in weer te geven.',
'yournick' => 'Alias veur ondertekeningen',
'prefs-help-signature' => 'Reaksies op de overlegziejen mutten ondertekend wörden mit "<nowiki>~~~~</nowiki>", dit wörden dan ummezet in joew ondertekening mit daorbie de daotum en tied van de bewarking.',
'badsig' => 'Ongeldige haandtekening; HTML naokieken.',
'badsiglength' => 'Joew haandtekening is te lang.
t Mut minder as {{PLURAL:$1|letter|letters}} hebben.',
'yourgender' => 'Geslacht:',
'gender-unknown' => 'Geet joe niks an',
'gender-male' => 'Keerl',
'gender-female' => 'Deerne',
'prefs-help-gender' => 'Disse instelling is opsioneel.

De programmatuur gebruukt disse weerde um joe op de juuste maniere an te spreken en veur aandere gebrukers um joew geslacht an te geven.
Disse informasie is zichtbaor veur aandere gebrukers.',
'email' => 'Privéberichten',
'prefs-help-realname' => "* Echte naam (niet verplicht): a'j disse opsie invullen zu'w joew echte naam gebruken um erkenning te geven veur joew warkzaamheen.",
'prefs-help-email' => "n Netpostadres is niet verplicht, mer zo ku'w wel joew wachtwoord toesturen veur a'j t vergeten bin.",
'prefs-help-email-others' => "Je kunnen oek aandere meensen de meugelikheid geven um kontakt mit joe op te nemen mit n verwiezing op joew gebrukers- en overlegzied zonder da'j de identiteit pries hoeven te geven.",
'prefs-help-email-required' => "Hier he'w n netpostadres veur neudig.",
'prefs-info' => 'Baosisinformasie',
'prefs-i18n' => 'Taalinstellingen',
'prefs-signature' => 'Ondertekening',
'prefs-dateformat' => 'Daotumopmaak:',
'prefs-timeoffset' => 'Tiedsverschil',
'prefs-advancedediting' => 'Algemene opsies',
'prefs-editor' => 'Bewarkingsprogramma',
'prefs-preview' => 'Naokieken',
'prefs-advancedrc' => 'Aandere instellingen',
'prefs-advancedrendering' => 'Aandere instellingen',
'prefs-advancedsearchoptions' => 'Aandere instellingen',
'prefs-advancedwatchlist' => 'Aandere instellingen',
'prefs-displayrc' => 'Weergave-instellingen',
'prefs-displaysearchoptions' => 'Weergave-instellingen',
'prefs-displaywatchlist' => 'Weergave-instellingen',
'prefs-tokenwatchlist' => 'Token',
'prefs-diffs' => 'Verschillen',
'prefs-help-prefershttps' => "Disse veurkeur wörden toe-epast a'j je eigen de volgende keer anmelden.",

# User preference: email validation using jQuery
'email-address-validity-valid' => 'Geldig netpostadres',
'email-address-validity-invalid' => 'Geef n geldig netpostadres op',

# User rights
'userrights' => 'Gebrukersrechtenbeheer',
'userrights-lookup-user' => 'Beheer gebrukersgroepen',
'userrights-user-editname' => 'Vul n gebrukersnaam in:',
'editusergroup' => 'Bewark gebrukersgroepen',
'editinguser' => "Doonde mit t wiezigen van de gebrukersrechten van '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => 'Bewark gebrukersgroep',
'saveusergroups' => 'Gebrukergroepen opslaon',
'userrights-groupsmember' => 'Lid van:',
'userrights-groupsmember-auto' => 'Lid van:',
'userrights-groups-help' => 'Je kunnen de groepen wiezigen waor as de gebruker lid van is.
* n An-evinkt vakjen betekent dat de gebruker lid is van de groep.
* n Niet an-evinkt vakjen betekent dat de gebruker gien lid is van de groep.
* n "*" betekent da\'j n gebruker niet uut n groep vort kunnen haolen naodat e derbie ezet is, of aandersumme.',
'userrights-reason' => 'Reden:',
'userrights-no-interwiki' => "Je hebben gien rechten um gebrukersrechten op aandere wiki's te wiezigen.",
'userrights-nodatabase' => 'Databanke $1 besteet niet of is gien plaotselike databanke.',
'userrights-nologin' => 'Je mutten [[Special:UserLogin|an-emeld]] ween en as gebruker de juuste rechten hebben um gebrukersrechten toe te kunnen wiezen.',
'userrights-notallowed' => 'Je hebben gien rechten um gebrukersrechten toe te kunnen wiezen of in te trekken.',
'userrights-changeable-col' => "Groepen die'j beheren kunnen",
'userrights-unchangeable-col' => "Groepen die'j niet beheren kunnen",
'userrights-conflict' => 'Konflikt bie t wiezigen van gebrukersrechten! Kiek joew wiezigingen nao en bevestig t.',
'userrights-removed-self' => 'Je hebben joew eigen bevoegdhejen in-etrökken. Je kunnen disse zied niet meer gebruken.',

# Groups
'group' => 'Groep:',
'group-user' => 'gebrukers',
'group-autoconfirmed' => 'an-emelde gebrukers',
'group-bot' => 'bots',
'group-sysop' => 'beheerders',
'group-bureaucrat' => 'burokraoten',
'group-suppress' => 'toezichthouwers',
'group-all' => '(alles)',

'group-user-member' => '{{GENDER:$1|gebruker}}',
'group-autoconfirmed-member' => '{{GENDER:$1|autobevestigden gebruker}}',
'group-bot-member' => '{{GENDER:$1|bot}}',
'group-sysop-member' => '{{GENDER:$1|beheerder}}',
'group-bureaucrat-member' => '{{GENDER:$1|burokraot}}',
'group-suppress-member' => '{{GENDER:$1|toezichthouwer}}',

'grouppage-user' => '{{ns:project}}:Gebrukers',
'grouppage-autoconfirmed' => '{{ns:project}}:An-emelde gebrukers',
'grouppage-bot' => '{{ns:project}}:Bots',
'grouppage-sysop' => '{{ns:project}}:Beheerder',
'grouppage-bureaucrat' => '{{ns:project}}:Beheerder',
'grouppage-suppress' => '{{ns:project}}:Toezichte',

# Rights
'right-read' => 'Ziejen bekieken',
'right-edit' => 'Ziejen bewarken',
'right-createpage' => 'Ziejen anmaken',
'right-createtalk' => 'Overlegziejen anmaken',
'right-createaccount' => 'Nieje gebrukers anmaken',
'right-minoredit' => 'Bewarkingen markeren as klein',
'right-move' => 'Ziejen herneumen',
'right-move-subpages' => 'Ziejen samen mit de ziejen die deronder hangen verplaotsen',
'right-move-rootuserpages' => 'Gebrukersziejen van t hoogste nivo herneumen',
'right-movefile' => 'Bestaanden herneumen',
'right-suppressredirect' => 'Gien deurverwiezing anmaken op de ouwe naam as n zied herneumd wörden',
'right-upload' => 'Bestaanden opsturen',
'right-reupload' => 'n Bestaond bestaand overschrieven',
'right-reupload-own' => "Bestaanden overschrieven die'j der zelf bie ezet hebben",
'right-reupload-shared' => 'Media uut de edeelden mediadatabanke plaotselik overschrieven',
'right-upload_by_url' => 'Bestaanden inlaojen via n webadres',
'right-purge' => 't Tussengeheugen van n zied legen',
'right-autoconfirmed' => 'Uutezonderd van IP-adres-ebaseerden tiedsaofhankelike beparkingen',
'right-bot' => 'Behaandeld wörden as n eautomatiseerd preces',
'right-nominornewtalk' => "Kleine bewarkingen an n overlegzied leien niet tot n melding 'nieje berichten'",
'right-apihighlimits' => 'Hoge API-limieten gebruken',
'right-writeapi' => 'Bewarken via de API',
'right-delete' => 'Ziejen vortdoon',
'right-bigdelete' => 'Ziejen mit n grote geschiedenisse vortdoon',
'right-deletelogentry' => 'Bepaolde logboekregels vortdoon en weerummeplaotsen',
'right-deleterevision' => 'Versies van ziejen verbargen',
'right-deletedhistory' => 'Vortedaone versies bekieken, zonder te kunnen zien wat der vortedaon is',
'right-deletedtext' => 'Bekiek vortedaone tekste en wiezigingen tussen vortedaone versies',
'right-browsearchive' => 'Vortedaone ziejen bekieken',
'right-undelete' => 'Vortedaone ziejen weerummeplaotsen',
'right-suppressrevision' => 'Verbörgen versies bekieken en weerummeplaotsen',
'right-suppressionlog' => 'Niet-publieke logboeken bekieken',
'right-block' => 'Aandere gebrukers de meugelikheid ontnemen um te bewarken',
'right-blockemail' => 'n Gebruker t recht ontnemen um berichjes te versturen',
'right-hideuser' => 'n Gebruker veur de aandere gebrukers verbargen',
'right-ipblock-exempt' => 'IP-blokkeringen ummezeilen',
'right-proxyunbannable' => "Blokkeringen veur proxy's gelden niet",
'right-unblockself' => 'Eigen gebruker deblokkeren',
'right-protect' => "Beveiligingsnivo's wiezigen",
'right-editprotected' => 'Ziejen bewarken die beveiligd bin as "{{int:protect-level-sysop}}"',
'right-editsemiprotected' => 'Ziejen bewarken die beveiligd bin as "{{int:protect-level-autoconfirmed}}"',
'right-editinterface' => 'Systeemteksten bewarken',
'right-editusercssjs' => 'De CSS- en JS-bestaanden van aandere gebrukers bewarken',
'right-editusercss' => 'De CSS-bestaanden van aandere gebrukers bewarken',
'right-edituserjs' => 'De JS-bestaanden van aandere gebrukers bewarken',
'right-editmyusercss' => 'Joew eigen CSS-ziejen bewarken',
'right-editmyuserjs' => 'Joew eigen JavaScript-ziejen bewarken',
'right-viewmywatchlist' => 'Joew eigen volglieste bekieken',
'right-editmywatchlist' => 'Joew eigen volglieste bewarken. Via sommige haandelingen kunnen nog altied ziejen derbie ezet wörden, zelfs zonder disse bevoegdheid',
'right-viewmyprivateinfo' => 'Joew eigen priveegegevens bekieken (bieveurbeeld netpostadres, echte naam)',
'right-editmyprivateinfo' => 'Joew eigen priveegegevens bewarken (bieveurbeeld netpostadres, echte naam)',
'right-editmyoptions' => 'Joew eigen veurkeuren bewarken',
'right-rollback' => 'Gauw de leste bewarking(en) van n gebruker an n zied weerummedreien',
'right-markbotedits' => 'Weerummedreien bewarkingen markeren as botbewarkingen',
'right-noratelimit' => 'Hef gien tiedsaofhankelike beparkingen',
'right-import' => "Ziejen uut aandere wiki's invoeren",
'right-importupload' => 'Ziejen vanuut n bestaand invoeren',
'right-patrol' => 'Bewarkingen as nao-ekeken markeren',
'right-autopatrol' => 'Bewarkingen wörden automaties op nao-ekeken ezet',
'right-patrolmarks' => 'Naokiektekens in "Leste wiezigingen" bekieken',
'right-unwatchedpages' => 'Bekiek n lieste mit ziejen die niet op n volglieste staon',
'right-mergehistory' => 'De geschiedenisse van ziejen bie mekaar doon',
'right-userrights' => 'Alle gebrukersrechten bewarken',
'right-userrights-interwiki' => "Gebrukersrechten van gebrukers in aandere wiki's wiezigen",
'right-siteadmin' => 'De databanke blokkeren en weer vriegeven',
'right-override-export-depth' => 'Ziejen uutvoeren, oek de ziejen waor naor verwezen wörden, tot n diepte van 5',
'right-sendemail' => 'Bericht versturen naor aandere gebrukers',
'right-passwordreset' => 'Bekiek netpostberichten veur t opniej instellen van joew wachtwoord',

# Special:Log/newusers
'newuserlogpage' => 'Logboek mit anwas',
'newuserlogpagetext' => 'Hieronder staon de niej in-eschreven gebrukers',

# User rights log
'rightslog' => 'Gebrukersrechtenlogboek',
'rightslogtext' => 'Dit is n logboek mit veraanderingen van gebrukersrechten',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'disse zied lezen',
'action-edit' => 'disse zied bewarken',
'action-createpage' => 'ziejen schrieven',
'action-createtalk' => 'overlegziejen anmaken',
'action-createaccount' => 'disse gebrukerskonto anmaken',
'action-minoredit' => 'disse bewarking as klein markeren',
'action-move' => 'disse zied herneumen',
'action-move-subpages' => 'disse zied en de biebeheurende ziejen die deronder hangen herneumen',
'action-move-rootuserpages' => 'gebrukersziejen van t hoogste nivo herneumen',
'action-movefile' => 'dit bestaand herneumen',
'action-upload' => 'dit bestaand opsturen',
'action-reupload' => 'dit bestaonde bestaand overschrieven',
'action-reupload-shared' => 'n aander bestaand over dit bestaand uut de edeelden mediadatabanke hinne zetten.',
'action-upload_by_url' => 'dit bestaand vanaof n webadres inlaojen',
'action-writeapi' => 'de schrief-API bewarken',
'action-delete' => 'disse zied vortdoon',
'action-deleterevision' => 'disse versie vortdoon',
'action-deletedhistory' => 'de vortedaone versies van disse zied bekieken',
'action-browsearchive' => 'vortedaone ziejen zeuken',
'action-undelete' => 'disse zied weerummeplaotsen',
'action-suppressrevision' => 'disse verbörgen versie bekieken en weerummeplaotsen',
'action-suppressionlog' => 'dit bescharmde logboek bekieken',
'action-block' => 'disse gebruker blokkeren',
'action-protect' => 't beveiligingsnivo van disse zied anpassen',
'action-rollback' => 'bewarkingen van de leste gebruker die n zied hef ewiezigd rap weerummedreien',
'action-import' => 'ziejen van n aandere wiki invoeren',
'action-importupload' => 'ziejen invoeren vanaof n op-estuurd bestaand',
'action-patrol' => 'bewarkingen van aandere luui op nao-ekeken zetten',
'action-autopatrol' => 'eigen bewarkingen op nao-ekeken zetten',
'action-unwatchedpages' => 'bekiek de lieste mit ziejen die niet evolgd wörden',
'action-mergehistory' => 'de geschiedenisse van disse zied samenvoegen',
'action-userrights' => 'alle gebrukersrechten bewarken',
'action-userrights-interwiki' => "de rechten van gebrukers op aandere wiki's bewarken",
'action-siteadmin' => 'de databanke blokkeren of vriegeven',
'action-sendemail' => 'netpostberichten versturen',
'action-editmywatchlist' => 'joew eigen volglieste bewarken',
'action-viewmywatchlist' => 'joew eigen volglieste bekieken',
'action-viewmyprivateinfo' => 'joew eigen priveegegevens bekieken',
'action-editmyprivateinfo' => 'joew eigen priveegegevens bewarken',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|wieziging|wiezigingen}}',
'enhancedrc-since-last-visit' => '$1 {{PLURAL:$1|sinds joew leste bezeuk}}',
'enhancedrc-history' => 'geschiedenisse',
'recentchanges' => 'Leste wiezigingen',
'recentchanges-legend' => 'Opsies veur leste wiezigingen',
'recentchanges-summary' => "Op disse zied ku'j de leste wiezigingen van disse wiki bekieken.",
'recentchanges-noresult' => 'Der waren in disse periode gien wiezigingen die an de kriteria voldoon.',
'recentchanges-feed-description' => 'Zeuk naor de alderleste wiezingen op disse wiki in disse voer.',
'recentchanges-label-newpage' => 'Mit disse bewarking is n nieje zied an-emaakt',
'recentchanges-label-minor' => 'Dit is n kleine wieziging',
'recentchanges-label-bot' => 'Disse bewarking is uutevoerd deur n bot',
'recentchanges-label-unpatrolled' => 'Disse bewarking is nog niet nao-ekeken',
'rcnote' => "Hieronder {{PLURAL:$1|steet de leste bewarking|staon de leste '''$1''' bewarkingen}} van de aofgeleupen {{PLURAL:$2|dag|'''$2''' dagen}} (per: $5, $4).",
'rcnotefrom' => 'Dit bin de wiezigingen sinds <b>$2</b> (maximum van <b>$1</b> wiezigingen).',
'rclistfrom' => 'Bekiek wiezigingen vanaof $1',
'rcshowhideminor' => '$1 kleine wiezigingen',
'rcshowhidebots' => '$1 botgebrukers',
'rcshowhideliu' => '$1 an-emelde gebrukers',
'rcshowhideanons' => '$1 anonieme gebrukers',
'rcshowhidepatr' => '$1 nao-ekeken bewarkingen',
'rcshowhidemine' => '$1 mien bewarkingen',
'rclinks' => 'Bekiek de leste $1 wiezigingen van de aofgeleupen $2 dagen<br />$3',
'diff' => 'wiezig',
'hist' => 'gesch',
'hide' => 'verbarg',
'show' => 'bekiek',
'minoreditletter' => 'K',
'newpageletter' => 'N',
'boteditletter' => 'B',
'unpatrolledletter' => '!',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|keer|keer}} op n volglieste]',
'rc_categories' => 'Beparking tot kategorieën (scheien mit "|")',
'rc_categories_any' => 'alles',
'rc-change-size-new' => '$1 {{PLURAL:$1|byte|bytes}} nao de wieziging',
'newsectionsummary' => 'Niej onderwarp: /* $1 */',
'rc-enhanced-expand' => 'Details bekieken',
'rc-enhanced-hide' => 'Details verbargen',
'rc-old-title' => 'oorspronkelik an-emaakt as "$1"',

# Recent changes linked
'recentchangeslinked' => 'Volg verwiezigingen',
'recentchangeslinked-feed' => 'Volg verwiezigingen',
'recentchangeslinked-toolbox' => 'Volg verwiezigingen',
'recentchangeslinked-title' => 'Wiezigingen verwaant an $1',
'recentchangeslinked-summary' => "Op disse spesiale zied steet n lieste mit de leste wieziginen op ziejen waornaor verwezen wörden. Ziejen op [[Special:Watchlist|joew volglieste]] staon '''vet-edrokt'''.",
'recentchangeslinked-page' => 'Ziednaam:',
'recentchangeslinked-to' => 'Bekiek wiezigingen op ziejen mit verwiezingen naor disse zied',

# Upload
'upload' => 'Bestaand opsturen',
'uploadbtn' => 'Bestaand opsturen',
'reuploaddesc' => 'Weerumme naor de opstuurzied',
'upload-tryagain' => 'Bestaandsbeschrieving biewarken',
'uploadnologin' => 'Niet an-emeld',
'uploadnologintext' => 'Je mutten $1 ween um bestaanden op te kunnen sturen.',
'upload_directory_missing' => 'De inlaojmap veur bestaanden ($1) is vort en kon niet an-emaakt wörden deur de webserver.',
'upload_directory_read_only' => "Op t moment ku'j gien bestaanden opsturen vanwegen techniese problemen ($1).",
'uploaderror' => 'Fout bie t inlaojen van t bestaand',
'upload-recreate-warning' => "'''Waorschuwing: der is n bestaand mit disse naam vortedaon of herneumd.'''

Hieronder steet t vortdologboek en t herneumlogboek veur disse zied:",
'uploadtext' => "Gebruuk t formulier hieronder um bestaanden op te sturen.
Um bestaanden te bekieken of te zeuken die eerder al op-estuurd bin, ku'j naor de [[Special:FileList|bestaandslieste]] gaon.
Bestaanden en media die nao t vortdoon opniej op-estuurd wörden ku'j in de smiezen houwen in t [[Special:Log/upload|logboek mit nieje bestaanden]] en t [[Special:Log/delete|vortdologboek]].

Um t bestaand in te voegen in n zied ku'j één van de volgende kodes gebruken:
* '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Bestaand.jpg]]</nowiki>'''
* '''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:Bestaand.png|alternatieve tekste]]</nowiki>'''
* '''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Bestaand.ogg]]</nowiki>''' drekte verwiezing naor n bestaand.",
'upload-permitted' => 'Toe-estaone bestaandstypes: $1.',
'upload-preferred' => 'An-ewezen bestaandstypes: $1.',
'upload-prohibited' => 'Verbeujen bestaandstypes: $1.',
'uploadlog' => 'logboek mit nieje bestaanden',
'uploadlogpage' => 'Logboek mit nieje bestaanden',
'uploadlogpagetext' => 'Hieronder steet n lieste mit bestaanden die net niej bin.
Zie de [[Special:NewFiles|uutstalling mit media]] veur n overzichte.',
'filename' => 'Bestaandsnaam',
'filedesc' => 'Beschrieving',
'fileuploadsummary' => 'Beschrieving:',
'filereuploadsummary' => 'Bestaandswiezigingen:',
'filestatus' => 'Auteursrechtstaotus',
'filesource' => 'Bron',
'uploadedfiles' => 'Nieje bestaanden',
'ignorewarning' => 'Negeer alle waorschuwingen',
'ignorewarnings' => 'Negeer waorschuwingen',
'minlength1' => 'Bestaandsnamen mutten uut tenminsten één letter bestaon.',
'illegalfilename' => 'Der staon karakters in bestaandsnaam "$1" die niet in namen van artikels veur maggen koemen. Geef t bestaand n aandere naam, en probeer t dan opniej toe te voegen.',
'filename-toolong' => 'Bestaandsnamen maggen niet langer ween as 240 bytes.',
'badfilename' => 'De naam van t bestaand is ewiezigd naor "$1".',
'filetype-mime-mismatch' => 'De bestaandsextensie ".$1" heurt niet bie t MIME-type van t bestaand ($2).',
'filetype-badmime' => 'Bestaanden mit t MIME-type "$1" ma\'j hier niet opsturen.',
'filetype-bad-ie-mime' => 'Dit bestaand kan niet op-estuurd wörden umdat Internet Explorer t zol herkennen as "$1", n niet toe-estaone bestaandstype die schao an kan richten.',
'filetype-unwanted-type' => "'''\".\$1\"''' is n ongewunst bestaandstype. An-ewezen {{PLURAL:\$3|bestaandstype is|bestaandstypes bin}} \$2.",
'filetype-banned-type' => "{{PLURAL:\$4|t Bestaandstype '''\".\$1\"''' wordt|De bestandstypes '''\".\$1\"''' worden}} niet toegelaten.
{{PLURAL:\$3|t Toe-estaone bestaandstype is|De toe-estaone bestaandstypen bin}} \$2.",
'filetype-missing' => 'Dit bestaand hef gien extensie (bv. ".jpg").',
'empty-file' => "t Bestaand da'j op-egeven hebben was leeg.",
'file-too-large' => "t Bestaand da'j op-egeven hebben was te groot.",
'filename-tooshort' => "t Bestaand da'j op-egeven hebben was te klein.",
'filetype-banned' => 'Dit bestaandstype is niet toe-estaon.',
'verification-error' => 'Dit bestaand is t bestaandsonderzeuk niet deurekeumen.',
'hookaborted' => "De wieziging die'j proberen deur te voeren bin aofebreuken deur n extra uutbreiding.",
'illegal-filename' => 'Disse bestaandsnaam is niet toe-estaon.',
'overwrite' => 't Overschrieven van n bestaand is niet toe-estaon.',
'unknown-error' => 'Der is n onbekende fout op-etrejen.',
'tmp-create-error' => 'Kon gien tiedelik bestaand anmaken.',
'tmp-write-error' => 'Der is n fout op-etrejen bie t anmaken van n tiedelik bestaand.',
'large-file' => 'Bestaanden mutten niet groter ween as $1, dit bestaand is $2.',
'largefileserver' => 't Bestaand is groter as dat de server toesteet.',
'emptyfile' => "t Bestaand da'j op-estuurd hebben is leeg. Dit kan koemen deur n tikfout in de bestaandsnaam. Kiek effen nao o'j dit bestaand wel bedoelden.",
'windows-nonascii-filename' => 'Disse wiki ondersteunt gien bestaandsnamen mit spesiale tekens.',
'fileexists' => 'n Bestaand mit disse naam besteet al; voeg t bestaand onder n aandere naam toe.
<strong>[[:$1]]</strong> [[$1|thumb]]',
'filepageexists' => "De beschrievingszied veur dit bestaand bestung al op <strong>[[:$1]]</strong>, mer der besteet nog gien bestaand mit disse naam.
De samenvatting die'j op-egeven hebben zal niet op de beschrievingszied koemen.
Bewark de zied haandmaotig um joew beschrieving daor weer te geven.
[[$1|thumb]]",
'fileexists-extension' => "n Bestaand mit n soortgelieke naam besteet al: [[$2|thumb]]
* Naam van t bestaand da'j derbie zetten wollen: <strong>[[:$1]]</strong>
* Naam van t bestaonde bestaand: <strong>[[:$2]]</strong>
Kies n aandere naam.",
'fileexists-thumbnail-yes' => "Dit bestaand is n aofbeelding waorvan de grootte verkleind is ''(miniatuuraofbeelding)''. [[$1|thumb]]
Kiek t bestaand nao <strong><strong>[[:$1]]</strong></strong>.
As de aofbeelding die'j krek nao-ekeken hebben de zelfde grootte hef, dan is t niet neudig um t opniej toe te voegen.",
'file-thumbnail-no' => "De bestaandsnaam begint mit <strong>$1</strong>.
Dit is warschienlik n verkleinde aofbeelding ''(overzichsaofbeelding)''.
A'j disse aofbeelding in volle grootte hebben voeg t dan toe, wiezig aanders de bestaandsnaam.",
'fileexists-forbidden' => 'n Bestaand mit disse naam besteet al, en kan niet overschreven wörden.
Voeg t bestaand toe onder n aandere naam.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => "Der besteet al n bestaand mit disse naam in de gezamenlike bestaandslokasie.
A'j t bestaand evengoed op willen sturen, gao dan weerumme en kies n aandere naam.
[[File:$1|thumb|center|$1]]",
'file-exists-duplicate' => 'Dit bestaand is liek alleens as {{PLURAL:$1|t volgende bestaand|de volgende bestaanden}}:',
'file-deleted-duplicate' => "n Bestaand dat liek alleens is an dit bestaand ([[:$1]]) is eerder al vortedaon.
Bekiek t vortdologboek veurda'j veurdan gaon.",
'uploadwarning' => 'Waorschuwing',
'uploadwarning-text' => 'Pas de bestaandsbeschrieving hieronder an en probeer t opniej',
'savefile' => 'Bestaand opslaon',
'uploadedimage' => 'Op-estuurd: [[$1]]',
'overwroteimage' => 'Nieje versie van "[[$1]]" op-estuurd',
'uploaddisabled' => 't Opsturen van bestaanden is uutezet.',
'copyuploaddisabled' => 't Opsturen van bestaanden via n webadres is uutezet.',
'uploadfromurl-queued' => 'Joew bestaand is in de wachtrie ezet.',
'uploaddisabledtext' => 't Opsturen van bestaanden is uutezet.',
'php-uploaddisabledtext' => 't Opsturen van PHP-bestaanden is uutezet. Kiek de instellingen veur t opsturen van bestaanden effen nao.',
'uploadscripted' => 'In dit bestaand steet HTML- of skriptkode die verkeerd elezen kan wörden deur de webkieker.',
'uploadvirus' => 'In dit bestaand zit n virus! Gegevens: $1',
'uploadjava' => 't Bestaand is n ZIP-bestaand waor n Java .class-bestaand in zit.
t Inlaojen van Java-bestaanden is niet toe-estaon umdat hiermee beveiligingsinstellingen ummezeild kunnen wörden.',
'upload-source' => 'Bronbestaand',
'sourcefilename' => 'Bestaandsnaam op de hardeschieve:',
'sourceurl' => 'Bronwebadres:',
'destfilename' => 'Opslaon as:',
'upload-maxfilesize' => 'Maximale bestaandsgrootte: $1',
'upload-description' => 'Bestaandsbeschrieving',
'upload-options' => 'Instellingen veur t opsturen van bestaanden',
'watchthisupload' => 'Volg dit bestaand',
'filewasdeleted' => "n Bestaand mit disse naam is al eerder vortedaon. Kiek t $1 nao veurda'j t opniej opsturen.",
'filename-bad-prefix' => "De naam van t bestaand da'j opsturen, begint mit '''\"\$1\"''', dit is n niet-beschrievende naam die meestentieds automaties deur n digitale kamera egeven wörden. Kies n dudelike naam veur t bestaand.",
'upload-success-subj' => 't Bestaand is op-estuurd',
'upload-success-msg' => 't Bestaand [$2] steet derop. Je kunnen t hier vienen: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Probleem bie t inlaojen van t bestaand',
'upload-failure-msg' => 'Der was n probleem bie t inlaojen van [$2]:

$1',
'upload-warning-subj' => 'Waorschuwing veur t opsturen van bestaanden',
'upload-warning-msg' => 'Der was n probleem mit t inlaojen van t bestaand [$2].
Gao weerumme naor t [[Special:Upload/stash/$1|opstuurformulier]] um dit probleem te verhelpen.',

'upload-proto-error' => 'Verkeerd protokol',
'upload-proto-error-text' => 'Um op disse maniere bestaanden toe te voegen mutten webadressen beginnen mit <code>http://</code> of <code>ftp://</code>.',
'upload-file-error' => 'Interne fout',
'upload-file-error-text' => 'Bie ons gung der effen wat fout to n tiedelik bestaand op de server an-emaakt wörden. Neem kontakt op mit n [[Special:ListUsers/sysop|beheerder]].',
'upload-misc-error' => 'Onbekende fout bie t inlaojen van joew bestaand',
'upload-misc-error-text' => 'Der is bie t inlaojen van t bestaand n onbekende fout op-etrejen. 
Kiek effen nao of de verwiezing t wel döt en probeer t opniej. 
As t probleem zo blif, neem dan kontakt op mit één van de [[Special:ListUsers/sysop|beheerders]].',
'upload-too-many-redirects' => 'Der zatten te veule deurverwiezingen in de URL.',
'upload-unknown-size' => 'Onbekende grootte',
'upload-http-error' => 'Der is n HTTP-fout op-etrejen: $1',
'upload-copy-upload-invalid-domain' => 'Bestaanden per kopie opsturen is niet beschikbaor vanuut dit domein.',

# File backend
'backend-fail-stream' => 't Was niet meugelik t bestaand $1 te streumen.',
'backend-fail-backup' => 't Was niet meugelik n reservekopie van t bestaand $1 te maken.',
'backend-fail-notexists' => 't Bestaand $1 besteet niet.',
'backend-fail-hashes' => 'De klutsweerde veur t bestaand kon niet op-ehaold wörden um t te vergelieken.',
'backend-fail-notsame' => 'Der steet al n niet-geliek bestaand op de plaotse $1.',
'backend-fail-invalidpath' => '$1 is gien geldig opslagpad.',
'backend-fail-delete' => 't Bestaand $1 kon niet vortedaon wörden.',
'backend-fail-describe' => 'Kon de metadata niet anpassen veur t bestaand "$1".',
'backend-fail-alreadyexists' => 't Bestaand $1 besteet al.',
'backend-fail-store' => 'Kon t bestaand $1 niet opslaon op lokasie $2.',
'backend-fail-copy' => 'Kon t bestaand $1 niet kopiëren naor $2.',
'backend-fail-move' => 'Kon t bestaand $1 niet verplaotsen naor $2.',
'backend-fail-opentemp' => 'Kon gien tiedelik bestaand openen.',
'backend-fail-writetemp' => 'Kon niet naor n tiedelik bestaand schrieven.',
'backend-fail-closetemp' => 'Kon niet n tiedelik bestaand sluten.',
'backend-fail-read' => 'Kon t bestaand $1 niet lezen.',
'backend-fail-create' => 'Kon t bestaand $1 niet schrieven.',
'backend-fail-maxsize' => 'Kon t bestaand $1 niet schrieven umdat t groter is as {{PLURAL:$2|één byte|$2 bytes}}.',
'backend-fail-readonly' => 'Van de opslag "$1" kan op dit moment allinnig elezen wörden. De op-egeven reden was: "$2"',
'backend-fail-synced' => 't Bestaand "$1" bevient zich in n inkonsistente toestaand in de interne opslagbackends.',
'backend-fail-connect' => 'Kon gien verbiending maken mit t opslagbackend "$1".',
'backend-fail-internal' => 'Der is n onbekende fout op-etreden in t opslagbackend "$1".',
'backend-fail-contenttype' => 'Kon t inhoudstype van t bestaand um op "$1" op te slaon niet bepaolen.',
'backend-fail-batchsize' => 'Reeks van $1 bestaands{{PLURAL:$1|operasie|operasies}} in de opslagbackend; de limiet is $2 {{PLURAL:$2|operasie|operasies}}.',
'backend-fail-usable' => 'Kon t bestaand "$1" niet lezen of schrieven umda\'j niet genog rechten hebben of vanwegen niet-anwezige mappen of houwers.',

# File journal errors
'filejournal-fail-dbconnect' => 'Kon gien verbiending maken mit de journaaldatabanke veur t opslagbackend "$1".',
'filejournal-fail-dbquery' => 'Kon de journaaldatabanke niet biewarken veur t opslagbackend "$1".',

# Lock manager
'lockmanager-notlocked' => 'Kon "$1" niet vriegeven; dit objekt is niet vergrendeld.',
'lockmanager-fail-closelock' => 'Kon t vergrendelingsbestaand veur "$1" niet sluten.',
'lockmanager-fail-deletelock' => 'Kon t vergrendelingsbestaand veur "$1" niet vortdoon.',
'lockmanager-fail-acquirelock' => 'Kon "$1" niet vergrendelen.',
'lockmanager-fail-openlock' => 'Kon t vergrendelingsbestaand veur "$1" niet openen.',
'lockmanager-fail-releaselock' => 'Kon vergrendeling van "$1" der niet aof haolen.',
'lockmanager-fail-db-bucket' => 'Kon niet in kontakt koemen mit genog vergrendelingsdatabanken in de nemmer $1.',
'lockmanager-fail-db-release' => 'Kon de vergrendeling veur de databanke $1 niet deraof haolen.',
'lockmanager-fail-svr-acquire' => 'Kon gien vergrendeling op server $1 zetten.',
'lockmanager-fail-svr-release' => 'Kon de vergrendeling veur de server $1 der niet aof haolen.',

# ZipDirectoryReader
'zip-file-open-error' => 'Der is wat fout egaon bie t los doon van t bestaand veur de ZIP-kontraole.',
'zip-wrong-format' => 't Op-egeven bestaand was gien ZIP-bestaand.',
'zip-bad' => 't Bestaand is beschaodig of is n onleesbaor ZIP-bestaand.
De veiligheid kan niet nao-ekeken wörden.',
'zip-unsupported' => 't Bestaand is n ZIP-bestaand dat gebruukmaak van ZIP-meugelikheen die MediaWiki niet ondersteunt.
De veiligheid kan niet nao-ekeken wörden.',

# Special:UploadStash
'uploadstash' => 'Verbörgen bestaanden',
'uploadstash-summary' => 'Disse zied gif toegang tot bestaanden die op-estuurd bin of nog op-estuurd wörden mer nog niet beschikbaor emaakt bin op de wiki. Disse bestaanden bin allinnig zichtbaor veur de gebruker die ze opstuurt.',
'uploadstash-clear' => 'Verbörgen bestaanden vortdoon',
'uploadstash-nofiles' => 'Der bin gien verbörgen bestaanden.',
'uploadstash-badtoken' => 't Uutvoeren van de haandeling is mislokt. Dit kömp waorschienlik deurdat joew bewarkingsreferensies verleupen bin. Probeer t opniej.',
'uploadstash-errclear' => 't Vortdoon van de bestaanden is mislokt.',
'uploadstash-refresh' => 'Lieste mit bestaanden biewarken',
'invalid-chunk-offset' => 'Ongeldig startpunt',

# img_auth script messages
'img-auth-accessdenied' => 'Toegang eweigerd',
'img-auth-nopathinfo' => 'PATH_INFO ontbrik.
Joew server is niet in-esteld um disse informasie deur te geven.
Misschien gebruukt t CGI, en dan wörden img_auth niet ondersteund.
Zie https://www.mediawiki.org/wiki/Manual:Image_Authorization veur meer informasie.',
'img-auth-notindir' => 't Op-evreugen pad is niet bekend in de in-estelden inlaojmap',
'img-auth-badtitle' => 'Kon gien geldige ziednaam maken van "$1".',
'img-auth-nologinnWL' => 'Je bin niet an-emeld en "$1" steet niet op de witte lieste.',
'img-auth-nofile' => 'Bestaand "$1" besteet niet.',
'img-auth-isdir' => 'Je proberen de map "$1" binnen te koemen.
Allinnig toegang tot bestaanden is toe-estaon.',
'img-auth-streaming' => 'Bezig mit t streumen van "$1".',
'img-auth-public' => 't Doel van img_auth.php is de uutvoer van bestaanden van n besleuten wiki.
Disse wiki is in-esteld as publieke wiki.
Um beveiligingsredens is img_auth.php uutezet.',
'img-auth-noread' => 'De gebruker hef gien leestoegang tot "$1".',
'img-auth-bad-query-string' => 'In t webadres steet n ongeldige zeukopdrachte.',

# HTTP errors
'http-invalid-url' => 'Ongeldig webadres: $1',
'http-invalid-scheme' => 'Webadressen mit de opmaak "$1" wörden niet ondersteund.',
'http-request-error' => 'Fout bie t verzenden van t verzeuk.',
'http-read-error' => 'Fout bie t lezen van HTTP',
'http-timed-out' => 'Tiedsoverschriejing bie t HTTP-verzeuk',
'http-curl-error' => 'Fout bie t ophaolen van t webadres: $1',
'http-bad-status' => 'Der is n probleem mit t HTTP-verzeuk: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'Kon webadres niet bereiken',
'upload-curl-error6-text' => "t Webadres kon niet bereikt wörden. Kiek effen nao o'j t goeie adres in-evoerd hebben en of de webstee bereikbaor is.",
'upload-curl-error28' => 'Tiedsoverschriejing veur t versturen van t bestaand',
'upload-curl-error28-text' => 't Duren te lange veurdat de webstee reageren. Kiek effen nao of de webstee bereikbaor is, wacht effen en probeer t daornao weer. Probeer t aanders as t wat rustiger is.',

'license' => 'Lisensie',
'license-header' => 'Lisensie',
'nolicense' => 'Gien lisensie ekeuzen',
'license-nopreview' => '(Naokieken is niet meugelik)',
'upload_source_url' => ' (een geldig, publiek toegankelik webadres)',
'upload_source_file' => ' (n bestaand op de hardeschieve)',

# Special:ListFiles
'listfiles-summary' => "Op disse spesiale zied ku'j alle bestaanden bekieken die lestens op-estuurd bin.",
'listfiles_search_for' => 'Zeuk naor bestaand:',
'imgfile' => 'bestaand',
'listfiles' => 'Bestaandslieste',
'listfiles_thumb' => 'Miniatuuraofbeelding',
'listfiles_date' => 'Daotum',
'listfiles_name' => 'Naam',
'listfiles_user' => 'Gebruker',
'listfiles_size' => 'Grootte (bytes)',
'listfiles_description' => 'Beschrieving',
'listfiles_count' => 'Versies',
'listfiles-show-all' => 'Ouwe versies van aofbeeldingen opnemen',
'listfiles-latestversion' => 'Aktuele versie',
'listfiles-latestversion-yes' => 'Ja',
'listfiles-latestversion-no' => 'Nee',

# File description page
'file-anchor-link' => 'Bestaand',
'filehist' => 'Bestaandsgeschiedenisse',
'filehist-help' => 'Klik op n daotum/tied um t bestaand te zien zo as t to was.',
'filehist-deleteall' => 'alles vortdoon',
'filehist-deleteone' => 'disse vortdoon',
'filehist-revert' => 'weerummedreien',
'filehist-current' => 'zo as t noen is',
'filehist-datetime' => 'Daotum/tied',
'filehist-thumb' => 'Miniatuuraofbeelding',
'filehist-thumbtext' => 'Miniatuuraofbeelding veur versie van $1',
'filehist-nothumb' => 'Gien miniatuuraofbeelding',
'filehist-user' => 'Gebruker',
'filehist-dimensions' => 'Grootte',
'filehist-filesize' => 'Bestaandsgrootte',
'filehist-comment' => 'Opmarkingen',
'filehist-missing' => 'Bestaand ontbrik',
'imagelinks' => 'Bestaandsgebruuk',
'linkstoimage' => 'Dit bestaand wörden gebruukt op de volgende {{PLURAL:$1|zied|$1 ziejen}}:',
'linkstoimage-more' => 'Der {{PLURAL:$2|is|bin}} meer as $1 {{PLURAL:$1|verwiezing|verwiezingen}} naor dit bestaand.
De volgende lieste gif allinnig de eerste {{PLURAL:$1|verwiezing|$1 verwiezingen}} naor dit bestaand weer.
De [[Special:WhatLinksHere/$2|hele lieste]] is oek beschikbaor.',
'nolinkstoimage' => 'Bestaand is niet in gebruuk.',
'morelinkstoimage' => '[[Special:WhatLinksHere/$1|Meer verwiezingen]] naor dit bestaand bekieken.',
'linkstoimage-redirect' => '$1 (bestaandsdeurverwiezing) $2',
'duplicatesoffile' => '{{PLURAL:$1|t Volgende bestaand is|De volgende $1 bestaanden bin}} liek alleens as dit bestaand ([[Special:FileDuplicateSearch/$2|meer informasie]]):',
'sharedupload' => "Dit is n edeeld bestaand op $1 en ku'j oek gebruken veur aandere projekten.",
'sharedupload-desc-there' => "Dit is n edeeld bestaand op $1 en ku'j oek gebruken veur aandere projekten. Bekiek de [$2 beschrieving van t bestaand] veur meer informasie.",
'sharedupload-desc-here' => "Dit is n edeeld bestaand op $1 en ku'j oek gebruken veur aandere projekten. De [$2 beschrieving van t bestaand] dergindse, steet hieronder.",
'sharedupload-desc-edit' => 'Dit besatand kömp van $1 en kan oek in aandere projekten gebruukt wörden.
Je kunnen de [$2 zied mit de bestaandsbeschrieving] daor bewarken.',
'sharedupload-desc-create' => 'Dit besatand kömp van $1 en kan oek in aandere projekten gebruukt wörden.
Je kunnen de [$2 zied mit de bestaandsbeschrieving] daor bewarken.',
'filepage-nofile' => 'Der besteet gien bestaand mit disse naam.',
'filepage-nofile-link' => 'Der besteet gien bestaand mit disse naam, mer je kunnen t [$1 opsturen].',
'uploadnewversion-linktext' => 'n Niejere versie van dit bestaand opsturen.',
'shared-repo-from' => 'uut $1',
'shared-repo' => 'n edeelden mediadatabanke',
'upload-disallowed-here' => 'Je kunnen dit bestaand niet overschrieven.',

# File reversion
'filerevert' => '$1 weerummedreien',
'filerevert-legend' => 'Bestaand weerummezetten',
'filerevert-intro' => "Je bin '''[[Media:$1|$1]]''' an t weerummedreien tot de [$4 versie van $2, $3]",
'filerevert-comment' => 'Reden:',
'filerevert-defaultcomment' => 'Weerummedreid tot de versie van $1, $2',
'filerevert-submit' => 'Weerummedreien',
'filerevert-success' => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' is weerummedreid naor de [$4 versie op $2, $3]</span>.',
'filerevert-badversion' => 'Der is gien veurige lokale versie van dit bestaand mit de op-egeven tied.',

# File deletion
'filedelete' => '$1 vortdoon',
'filedelete-legend' => 'Bestaand vortdoon',
'filedelete-intro' => "Je doon t bestaand '''[[Media:$1|$1]]''' noen vort samen mit de geschiedenisse dervan.",
'filedelete-intro-old' => "Je bin de versie van '''[[Media:$1|$1]]''' van [$4 $3, $2] vort an t doon.",
'filedelete-comment' => 'Reden:',
'filedelete-submit' => 'Vortdoon',
'filedelete-success' => "'''$1''' is vortedaon.",
'filedelete-success-old' => "De versie van '''[[Media:$1|$1]]''' van $3, $2 is vortedaon.",
'filedelete-nofile' => "'''$1''' besteet niet.",
'filedelete-nofile-old' => "Der is gien versie van '''$1''' in t archief mit de an-egeven eigenschappen.",
'filedelete-otherreason' => 'Aandere reden:',
'filedelete-reason-otherlist' => 'Aandere reden',
'filedelete-reason-dropdown' => "*Veulveurkoemende redens veur t vortdoon van ziejen
** Auteursrechtenschending
** Dit bestaand he'w dubbel",
'filedelete-edit-reasonlist' => 'Reden veur t vortdoon bewarken',
'filedelete-maintenance' => 't Vortdoon en weerummeplaotsen kan noen effen niet umda-w bezig bin mit onderhoud.',
'filedelete-maintenance-title' => 'Kan bestaand niet vortdoon',

# MIME search
'mimesearch' => 'Zeuken op MIME-type',
'mimesearch-summary' => 'Op disse spesiale zied kunnen de bestaanden naor t MIME-type efiltreerd wörden. In de invoer mut altied t media- en subtype staon, bieveurbeeld: <code>aofbeelding/jpeg</code>.',
'mimetype' => 'MIME-type:',
'download' => 'binnenhaolen',

# Unwatched pages
'unwatchedpages' => 'Ziejen die niet evolgd wörden',

# List redirects
'listredirects' => 'Lieste van deurverwiezingen',

# Unused templates
'unusedtemplates' => 'Ongebruukten mallen',
'unusedtemplatestext' => 'Hieronder staon alle ziejen in de naamruumte "{{ns:template}}" die nargens gebruukt wörden.
Vergeet niet de verwiezingen nao te kieken veurda\'j de mal vortdoon.',
'unusedtemplateswlh' => 'aandere verwiezingen',

# Random page
'randompage' => 'Netzelde welk artikel',
'randompage-nopages' => 'Der staon gien ziejen in de {{PLURAL:$2|naamruumte|naamruumtes}}: $1.',

# Random page in category
'randomincategory' => 'Netzelde welke zied in n kategorie',
'randomincategory-invalidcategory' => '"$1" is gien geldige kategorienaam.',
'randomincategory-nopages' => 'Der bin gien ziejen in [[:Category:$1]].',
'randomincategory-selectcategory' => 'Netzelde welke zied uut de kategorie: $1 $2',
'randomincategory-selectcategory-submit' => 'Laot kulen',

# Random redirect
'randomredirect' => 'Netzelde welke deurverwiezing',
'randomredirect-nopages' => 'Der staon gien deurverwiezingen in de naamruumte "$1".',

# Statistics
'statistics' => 'Staotistieken',
'statistics-header-pages' => 'Ziedstaotistieken',
'statistics-header-edits' => 'Bewarkingsstaotistieken',
'statistics-header-views' => 'Staotistieken bekieken',
'statistics-header-users' => 'Gebrukerstaotistieken',
'statistics-header-hooks' => 'Overige staotistieken',
'statistics-articles' => 'Inhouwelike ziejen',
'statistics-pages' => 'Ziejen',
'statistics-pages-desc' => 'Alle ziejen in de wiki, oek overlegziejen, deurverwiezingen, en gao zo mer deur.',
'statistics-files' => 'Bestaanden',
'statistics-edits' => 'Ziedbewarkingen vanaof t begin van {{SITENAME}}',
'statistics-edits-average' => 'Gemiddeld antal bewarkingen per zied',
'statistics-views-total' => 'Totaal antal weeregeven ziejen',
'statistics-views-total-desc' => 't Bekieken van niet-bestaonde ziejen en spesiale ziejen zitten der niet bie in',
'statistics-views-peredit' => 'Weeregeven ziejen per bewarking',
'statistics-users' => 'In-eschreven [[Special:ListUsers|gebrukers]]',
'statistics-users-active' => 'Aktieve gebrukers',
'statistics-users-active-desc' => 'Gebrukers die de veurbieje {{PLURAL:$1|dag|$1 dagen}} n haandeling uutevoerd hebben',
'statistics-mostpopular' => 'Meestbekeken ziejen',

'pageswithprop' => 'Ziejen mit n ziedeigenschap',
'pageswithprop-legend' => 'Ziejen mit n zied-eigenschap',
'pageswithprop-text' => 'Op disse zied staon ziejen mit n bepaolde ziedeigenschap.',
'pageswithprop-prop' => 'Naam van de eigenschap:',
'pageswithprop-submit' => 'Zeuk',
'pageswithprop-prophidden-long' => 'lange teksteigenschapsweerde verbörgen ($1)',
'pageswithprop-prophidden-binary' => 'binaere eigenschapsweerde verbörgen ($1)',

'doubleredirects' => 'Dubbele deurverwiezingen',
'doubleredirectstext' => 'Op disse lieste staon alle ziejen die deurverwiezen naor aandere deurverwiezingen.
Op elke regel steet de eerste en de tweede deurverwiezing, daorachter steet de doelzied van de tweede deurverwiezing.
Meestentieds is leste zied de gewunste doelzied, waor oek de eerste zied heer zol mutten liejen.',
'double-redirect-fixed-move' => '[[$1]] is herneumd en is noen n deurverwiezing naor [[$2]]',
'double-redirect-fixed-maintenance' => 'Verbeteren van dubbele deurverwiezing van [[$1]] naor [[$2]].',
'double-redirect-fixer' => 'Deurverwiezingsverbeteraar',

'brokenredirects' => 'Ebreuken deurverwiezingen',
'brokenredirectstext' => 'Disse deurverwiezingen verwiezen naor n niet-bestaonde zied.',
'brokenredirects-edit' => 'bewark',
'brokenredirects-delete' => 'vortdoon',

'withoutinterwiki' => 'Ziejen zonder verwiezingen naor aandere talen',
'withoutinterwiki-summary' => 'De volgende ziejen verwiezen niet naor versies in n aandere taal.',
'withoutinterwiki-legend' => 'Veurvoegsel',
'withoutinterwiki-submit' => 'Bekieken',

'fewestrevisions' => 'Artikels mit de minste bewarkingen',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories' => '$1 {{PLURAL:$1|kategorie|kategorieën}}',
'ninterwikis' => '$1 {{PLURAL:$1|interwikiverwiezing|interwikiverwiezingen}}',
'nlinks' => '$1 {{PLURAL:$1|verwiezing|verwiezingen}}',
'nmembers' => '$1 {{PLURAL:$1|onderwarp|onderwarpen}}',
'nrevisions' => '$1 {{PLURAL:$1|versie|versies}}',
'nviews' => '{{PLURAL:$1|1 keer|$1 keer}} bekeken',
'nimagelinks' => 'Wörden op {{PLURAL:$1|één zied|$1 ziejen}} gebruukt',
'ntransclusions' => 'wörden op {{PLURAL:$1|één zied|$1 ziejen}} gebruukt',
'specialpage-empty' => 'Disse zied is leeg.',
'lonelypages' => 'Weesziejen',
'lonelypagestext' => 'Naor disse ziejen wörden niet verwezen vanuut {{SITENAME}} en ze bin oek nargens in-evoegd.',
'uncategorizedpages' => 'Ziejen zonder kategorie',
'uncategorizedcategories' => 'Kategorieën zonder kategorie',
'uncategorizedimages' => 'Bestaanden zonder kategorie',
'uncategorizedtemplates' => 'Mallen zonder kategorie',
'unusedcategories' => 'Ongebruukten kategorieën',
'unusedimages' => 'Ongebruukten bestaanden',
'popularpages' => 'Populaere artikels',
'wantedcategories' => 'Gewunste kategorieën',
'wantedpages' => 'Gewunste ziejen',
'wantedpages-badtitle' => 'Ongeldige ziednaam in resultaot: $1',
'wantedfiles' => 'Gewunste bestaanden',
'wantedfiletext-cat' => 'De volgende bestaanden wörden gebruukt mer bestaon niet. Bestaanden van externe databanken kunnen op-eneumen ween in de lieste, ondanks dat ze bestaon. Soortgelieke vals positieven wörden <del>deurehaold weeregeven</del>. Ziejen die niet-bestaonde bestaanden insluten staon op de zied [[:$1]].',
'wantedfiletext-nocat' => 'De volgende bestaanden wörden gebruukt mer bestaon niet. Bestaanden van externe databanken kunnen op-eneumen ween in de lieste, ondanks dat ze bestaon. Soortgelieke vals positieven wörden <del>deurehaold weeregeven</del>.',
'wantedtemplates' => 'Gewunste mallen',
'mostlinked' => 'Ziejen waor t meest naor verwezen wörden',
'mostlinkedcategories' => 'Meestgebruukten kategorieën',
'mostlinkedtemplates' => 'Meestgebruukten mallen',
'mostcategories' => 'Artikels mit de meeste kategorieën',
'mostimages' => 'Meestgebruukten bestaanden',
'mostinterwikis' => 'Ziejen mit de meeste interwikiverwiezingen',
'mostrevisions' => 'Artikels mit de meeste bewarkingen',
'prefixindex' => 'Alle ziejen op veurvoegsel',
'prefixindex-namespace' => 'Alle ziejen mit t veurvoegsel (naamruumte $1)',
'prefixindex-strip' => 'Veurvoegsel in lieste vortdoon',
'shortpages' => 'Korte artikels',
'longpages' => 'Lange artikels',
'deadendpages' => 'Ziejen zonder verwiezingen',
'deadendpagestext' => 'De onderstaonde ziejen verwiezen niet naor aandere ziejen in disse wiki.',
'protectedpages' => 'Ziejen die beveiligd bin',
'protectedpages-indef' => 'Allinnig blokkeringen zonder verloopdaotum',
'protectedpages-cascade' => 'Allinnig beveiligingen mit de kaskadeopsie',
'protectedpagestext' => 'De volgende ziejen bin beveiligd en kunnen niet herneumd of bewarkt wörden.',
'protectedpagesempty' => 'Der bin op t moment gien beveiligden ziejen',
'protectedtitles' => 'Ziednamen die beveiligd bin',
'protectedtitlestext' => 'De volgende ziejen bin beveiligd, zodat ze niet opniej an-emaakt kunnen wörden',
'protectedtitlesempty' => 'Der bin noen gien titels beveiligd die an disse veurweerden voldoon.',
'listusers' => 'Gebrukerslieste',
'listusers-editsonly' => 'Allinnig gebrukers mit bewarkingen laoten zien',
'listusers-creationsort' => 'Sorteren op inschriefdaotum',
'listusers-desc' => 'Sorteren in aoflopende volgorde',
'usereditcount' => '$1 {{PLURAL:$1|bewarking|bewarkingen}}',
'usercreated' => '{{GENDER:$3|Eregistreerd}} op $1 um $2',
'newpages' => 'Nieje artikels',
'newpages-username' => 'Gebrukersnaam:',
'ancientpages' => 'Oudste artikels',
'move' => 'Herneumen',
'movethispage' => 'Herneum',
'unusedimagestext' => "Vergeet niet dat aandere wiki's misschien oek n antal van disse bestaanden gebruken.

De volgende bestaanden bin op-estuurd mer niet in gebruuk.
t Kan ween dat der drekt verwezen wörden naor n bestaand.
n Bestaand kan hier dus verkeerd op-eneumen ween.",
'unusedcategoriestext' => 'De onderstaonde kategorieën bin an-emaakt mer bin niet in gebruuk.',
'notargettitle' => 'Gien zied op-egeven',
'notargettext' => 'Je hebben niet op-egeven veur welke zied je disse funksie bekieken willen.',
'nopagetitle' => 'Doelzied besteet niet',
'nopagetext' => "De zied die'j herneumen willen besteet niet.",
'pager-newer-n' => '{{PLURAL:$1|1 niejere|$1 niejere}}',
'pager-older-n' => '{{PLURAL:$1|1 ouwere|$1 ouwere}}',
'suppress' => 'Toezichte',
'querypage-disabled' => 'Disse spesiale zied is uutezet um prestasieredens.',

# Book sources
'booksources' => 'Boekinformasie',
'booksources-search-legend' => 'Zeuk informasie over n boek',
'booksources-go' => 'Zeuk',
'booksources-text' => "Hieronder steet n lieste mit verwiezingen naor aandere websteeën die nieje of wat ouwere boeken verkopen, en daor hebben ze warschienlik meer informasie over t boek da'j zeuken:",
'booksources-invalid-isbn' => "De op-egeven ISBN klop niet; kiek effen nao o'j gien fout emaakt hebben bie de invoer.",

# Special:Log
'specialloguserlabel' => 'Uutvoerende gebruker:',
'speciallogtitlelabel' => 'Doel (ziednaam of gebruker):',
'log' => 'Logboeken',
'all-logs-page' => 'Alle publieke logboeken',
'alllogstext' => 'Dit is t kombinasielogboek van {{SITENAME}}.
Je kunnen oek kiezen veur bepaolde logboeken en filteren op gebruker (heufdlettergeveulig) en titel (heufdlettergeveulig).',
'logempty' => 'Der steet gien passende informasie in t logboek.',
'log-title-wildcard' => 'Zeuk naor titels die beginnen mit disse tekste:',
'showhideselectedlogentries' => 'Ekeuzen logboekregels laoten zien of verbargen',

# Special:AllPages
'allpages' => 'Alle ziejen',
'alphaindexline' => '$1 tot $2',
'nextpage' => 'Volgende zied ($1)',
'prevpage' => 'Veurige zied ($1)',
'allpagesfrom' => 'Laot ziejen zien vanaof:',
'allpagesto' => 'Laot ziejen zien tot:',
'allarticles' => 'Alle artikels',
'allinnamespace' => 'Alle ziejen (naamruumte $1)',
'allnotinnamespace' => 'Alle ziejen (niet in naamruumte $1)',
'allpagesprev' => 'veurige',
'allpagesnext' => 'volgende',
'allpagessubmit' => 'Zeuk',
'allpagesprefix' => 'Ziejen bekieken die beginnen mit:',
'allpagesbadtitle' => 'De op-egeven ziednaam is ongeldig of der steet n interwikiveurvoegsel in. Meugelikerwieze staon der karakters in de naam die niet gebruukt maggen wörden in ziednamen.',
'allpages-bad-ns' => '{{SITENAME}} hef gien "$1"-naamruumte.',
'allpages-hide-redirects' => 'Deurverwiezingen verbargen',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'Je bekieken noen n versie uut t tussengeheugen van disse zied, die hooguut $1 oud is.',
'cachedspecial-viewing-cached-ts' => 'Je bekieken noen n versie uut t tussengeheugen van disse zied, t kan ween dat t niet helemaole bie de tied is.',
'cachedspecial-refresh-now' => 'Leste bekieken.',

# Special:Categories
'categories' => 'Kategorieën',
'categoriespagetext' => "De de volgende {{PLURAL:$1|kategorie steet|kategorieën staon}} ziejen of mediabestaanden.
[[Special:UnusedCategories|ongebruukten kategorieën]] zie'j hier niet.
Zie oek [[Special:WantedCategories|gewunste kategorieën]].",
'categoriesfrom' => 'Laot kategorieën zien vanaof:',
'special-categories-sort-count' => 'op antal sorteren',
'special-categories-sort-abc' => 'alfebeties sorteren',

# Special:DeletedContributions
'deletedcontributions' => 'Vortedaone gebrukersbiedragen',
'deletedcontributions-title' => 'Vortedaone gebrukersbiedragen',
'sp-deletedcontributions-contribs' => 'biedragen',

# Special:LinkSearch
'linksearch' => 'Uutgaonde verwiezingen zeuken',
'linksearch-pat' => 'Zeukpetroon:',
'linksearch-ns' => 'Naamruumte:',
'linksearch-ok' => 'Zeuken',
'linksearch-text' => 'Jokers zo as "*.wikipedia.org" of "*.org" bin toe-estaon.
Hef tenminsten n topdomein, zo as "*.org".<br />
{{PLURAL:$2|Ondersteund protokol|Ondersteunde protokollen}}: <code>$1</code> (wörden "http://" as der gien protokol op-egeven wörden).',
'linksearch-line' => '$1 hef n verwiezing in $2',
'linksearch-error' => 'Jokers bin allinnig toe-estaon an t begin van n webadres.',

# Special:ListUsers
'listusersfrom' => 'Laot gebrukers zien vanaof:',
'listusers-submit' => 'Bekiek',
'listusers-noresult' => 'Gien gebrukers evunnen. Zeuk oek naor variaanten mit kleine letters of heufdletters.',
'listusers-blocked' => '(eblokkeerd)',

# Special:ActiveUsers
'activeusers' => 'Aktieve gebrukers',
'activeusers-intro' => 'Dit is n lieste van gebrukers die de aofgeleupen $1 {{PLURAL:$1|dag|dagen}} enigszins aktief ewest hebben.',
'activeusers-count' => '$1 leste {{PLURAL:$1|haandeling|haandelingen}} in de aofeleupen {{PLURAL:$3|dag|$3 dagen}}',
'activeusers-from' => 'Laot gebrukers zien vanaof:',
'activeusers-hidebots' => 'Bots verbargen',
'activeusers-hidesysops' => 'Beheerders verbargen',
'activeusers-noresult' => 'Gien aktieve gebrukers evunnen.',

# Special:ListGroupRights
'listgrouprights' => 'Rechten van gebrukersgroepen',
'listgrouprights-summary' => "Op disse zied staon de gebrukersgroepen van disse wiki beschreven, mit de biebeheurende rechten.
Meer informasie over de rechten ku'j [[{{MediaWiki:Listgrouprights-helppage}}|hier vienen]].",
'listgrouprights-key' => 'Leganda:
* <span class="listgrouprights-granted">Toe-ewezen recht</span>
* <span class="listgrouprights-revoked">In-etrökken recht</span>',
'listgrouprights-group' => 'Groep',
'listgrouprights-rights' => 'Rechten',
'listgrouprights-helppage' => 'Help:Gebrukersrechten',
'listgrouprights-members' => '(lejenlieste)',
'listgrouprights-addgroup' => 'Kan gebrukers bie disse {{PLURAL:$2|groep|groepen}} zetten: $1',
'listgrouprights-removegroup' => 'Kan gebrukers uut disse {{PLURAL:$2|groep|groepen}} haolen: $1',
'listgrouprights-addgroup-all' => 'Kan gebrukers bie alle groepen zetten',
'listgrouprights-removegroup-all' => 'Kan gebrukers uut alle groepen haolen',
'listgrouprights-addgroup-self' => 'Kan {{PLURAL:$2|groep|groepen}} bie de eigen gebruker doon: $1',
'listgrouprights-removegroup-self' => 'Kan {{PLURAL:$2|groep|groepen}} vortdoon van eigen gebruker: $1',
'listgrouprights-addgroup-self-all' => 'Kan alle groepen bie de eigen gebruker doon',
'listgrouprights-removegroup-self-all' => 'Kan alle groepen vortdoon van eigen gebruker',

# Email user
'mailnologin' => 'Niet an-emeld.',
'mailnologintext' => 'Je mutten [[Special:UserLogin|an-emeld]] ween en n geldig e-mailadres in "[[Special:Preferences|mien veurkeuren]]" invoeren um disse funksie te kunnen gebruken.',
'emailuser' => 'n Bericht sturen',
'emailuser-title-target' => 'Disse {{GENDER:$1|gebruker}} n bericht sturen',
'emailuser-title-notarget' => 'Gebruker n bericht sturen',
'emailpage' => 'Gebruker n bericht sturen',
'emailpagetext' => "Deur middel van dit formulier ku'j n bericht sturen naor disse {{GENDER:$1|gebruker}}.
t Adres da'j op-egeven hebben bie [[Special:Preferences|joew veurkeuren]] zal as aofzender gebruukt wörden.
De ontvanger kan dus drek beantwoorden.",
'usermailererror' => 'Foutmelding bie t versturen:',
'defemailsubject' => 'Bericht van {{SITENAME}}-gebruker "$1"',
'usermaildisabled' => 'n Persoonlik berichjen sturen geet niet.',
'usermaildisabledtext' => 'Je kunnen gien berichjes sturen naor aandere gebrukers van disse wiki',
'noemailtitle' => 'Gebruker hef gien netpostadres op-egeven',
'noemailtext' => 'Disse gebruker hef gien geldig e-mailadres in-evoerd.',
'nowikiemailtitle' => 'Netpost is niet toe-estaon',
'nowikiemailtext' => 'Disse gebruker wil gien netpost toe-estuurd kriegen van aandere gebrukers.',
'emailnotarget' => 'Niet-bestaonde of ongeldige ontvanger.',
'emailtarget' => 'Voer de gebrukersnaam of ontvanger in',
'emailusername' => 'Gebrukersnaam:',
'emailusernamesubmit' => 'Opslaon',
'email-legend' => 'n Bericht sturen naor n aandere gebruker van {{SITENAME}}',
'emailfrom' => 'Van:',
'emailto' => 'An:',
'emailsubject' => 'Onderwarp:',
'emailmessage' => 'Bericht:',
'emailsend' => 'Versturen',
'emailccme' => 'Stuur mien n kopie van dit bericht.',
'emailccsubject' => 'Kopie van joew bericht an $1: $2',
'emailsent' => 'Bericht verstuurd',
'emailsenttext' => 'Bericht is verstuurd.',
'emailuserfooter' => 'Dit bericht is verstuurd deur $1 an $2 deur de funksie "n Bericht sturen" van {{SITENAME}} te gebruken.',

# User Messenger
'usermessage-summary' => 'Systeemteksten achter-eleuten',
'usermessage-editor' => 'Systeemtekste',

# Watchlist
'watchlist' => 'Volglieste',
'mywatchlist' => 'Mien volglieste',
'watchlistfor2' => 'Veur $1 ($2)',
'nowatchlist' => 'Gien artikels in volglieste.',
'watchlistanontext' => '$1 is verplicht um joew volglieste te bekieken of te wiezigen.',
'watchnologin' => 'Niet an-emeld',
'watchnologintext' => "Um je volglieste an te passen mu'j eerst [[Special:UserLogin|an-emeld]] ween.",
'addwatch' => 'Op mien volglieste zetten',
'addedwatchtext' => 'De zied "[[:$1]]" steet noen op joew [[Special:Watchlist|volglieste]].
Toekomstige wiezigingen op disse zied en de overlegzied zullen hier vermeld wörden.',
'removewatch' => 'Van mien volglieste aofhaolen',
'removedwatchtext' => 'De zied "[[:$1]]" is van [[Special:Watchlist|joew volglieste]] aofehaold.',
'watch' => 'Volgen',
'watchthispage' => 'Volg disse zied',
'unwatch' => 'Niet volgen',
'unwatchthispage' => 'Niet volgen',
'notanarticle' => 'Gien artikel',
'notvisiblerev' => 'Bewarking is vortedaon',
'watchlist-details' => 'Der {{PLURAL:$1|steet één zied|staon $1 ziejen}} op joew volglieste, zonder de overlegziejen mee-erekend.',
'wlheader-enotif' => 'Je kriegen bericht per netpost',
'wlheader-showupdated' => "Ziejen die sinds joew leste bezeuk bie-ewörken bin, staon '''vet-edrokt'''.",
'watchmethod-recent' => "leste wiezigingen an t naokieken op ziejen die'j volgen",
'watchmethod-list' => 'Kik joew nao volglieste veur de leste wiezigingen',
'watchlistcontains' => 'Der {{PLURAL:$1|steet 1 zied|staon $1 ziejen}} op joew volglieste.',
'iteminvalidname' => "Verkeerde naam '$1'",
'wlnote' => 'Hieronder {{PLURAL:$1|steet de leste wieziging|staon de leste $1 wiezigingen}} in {{PLURAL:$2|t aofgeleupen ure|de leste $2 uren}} vanaof $3 um $4.',
'wlshowlast' => 'Laot de veurbieje $1 uur $2 dagen $3 zien',
'watchlist-options' => 'Opsies veur de volglieste',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Volg...',
'unwatching' => 'Niet volgen...',
'watcherrortext' => 'Der is n fout op-etrejen tiejens t wiezigen van joew volgliesinstellingen veur "$1".',

'enotif_mailer' => '{{SITENAME}}-berichgevingssysteem',
'enotif_reset' => 'Markeer alle ziejen as bezöcht.',
'enotif_impersonal_salutation' => '{{SITENAME}}-gebruker',
'enotif_subject_deleted' => '{{SITENAME}}: zied $1 is vortedaon deur {{GENDER:$2|$2}}',
'enotif_subject_created' => '{{SITENAME}}: zied $1 is an-emaakt deur {{GENDER:$2|$2}}',
'enotif_subject_moved' => '{{SITENAME}}: zied $1 is herneumd deur {{GENDER:$2|$2}}',
'enotif_subject_restored' => '{{SITENAME}}: zied $1 is weerummeplaotst deur {{GENDER:$2|$2}}',
'enotif_subject_changed' => '{{SITENAME}}: zied $1 is ewiezigd deur {{GENDER:$2|$2}}',
'enotif_body_intro_deleted' => 'De zied $1 op {{SITENAME}} is vortedaon deur {{gender:$2|$2}} op $PAGEEDITDATE. Zie $3 veur de aktuele versie.',
'enotif_body_intro_created' => 'De zied $1 op {{SITENAME}} is an-emaakt deur {{GENDER:$2|$2}} op $PAGEEDITDATE. Zie $3 veur de aktuele versie.',
'enotif_body_intro_moved' => 'De zied $1 op {{SITENAME}} is herneumd deur {{GENDER:$2|$2}} op $PAGEEDITDATE. Zie $3 veur de aktuele versie.',
'enotif_body_intro_restored' => 'De zied $1 op {{SITENAME}} is weerummeplaotst deur {{GENDER:$2|$2}} op $PAGEEDITDATE. Zie $3 veur de aktuele versie.',
'enotif_body_intro_changed' => 'De zied $1 op {{SITENAME}} is bewörken deur {{GENDER:$2|$2}} op $PAGEEDITDATE. Zie $3 veur de aktuele versie.',
'enotif_lastvisited' => 'Zie $1 veur alle wiezigingen sinds joew leste bezeuk.',
'enotif_lastdiff' => 'Zie $1 um disse wieziging te bekieken.',
'enotif_anon_editor' => 'anonieme gebruker $1',
'enotif_body' => 'Huj $WATCHINGUSERNAME,

$PAGEINTRO $NEWPAGE

Samenvatting van de wieziging: $PAGESUMMARY $PAGEMINOREDIT

Kontaktgevevens van de auteur:
Netpost: $PAGEEDITOR_EMAIL
Wiki: $PAGEEDITOR_WIKI

Je kriegen veerder gien berichten, behalven a\'j disse zied bezeuken.
Op joew volglieste ku\'j veur alle ziejen die\'j volgen de waorschuwingsinstellingen deraof haolen.

Groeten van t {{SITENAME}}-waorschuwingssysteem.

--
Je kunnen joew netpostinstellingen wiezigen op:
{{canonicalurl:{{#special:Preferences}}}}

Je kunnen de volgliestinstellingen wiezigen op:
{{canonicalurl:{{#special:EditWatchlist}}}}

Je kunnen de zied van joew volglieste aofhaolen deur op de volgende verwiezing te klikken:
$UNWATCHURL

Opmarkingen en veerdere hulpe:
{{canonicalurl:{{MediaWiki:Helppage}}}}',
'created' => 'an-emaakt',
'changed' => 'ewiezigd',

# Delete
'deletepage' => 'Vortdoon',
'confirm' => 'Bevestigen',
'excontent' => "De tekste was: '$1'",
'excontentauthor' => "De tekste was: '$1' (zied an-emaakt deur: [[Special:Contributions/$2|$2]])",
'exbeforeblank' => "veurdat disse zied leegemaakt wörden stung hier: '$1'",
'exblank' => 'Zied was leeg',
'delete-confirm' => '"$1" vortdoon',
'delete-legend' => 'Vortdoon',
'historywarning' => "'''Waorschuwing''': de zied die'j vortdoon, hef $1 {{PLURAL:$1|versie|versies}}:",
'confirmdeletetext' => "Je staon op t punt n zied en de geschiedenisse dervan vort te doon.
Bevestig hieronder dat dit inderdaod de bedoeling is, da'j de gevolgen begriepen en dat t akkedeert mit t [[{{MediaWiki:Policy-url}}|beleid]].",
'actioncomplete' => 'Uutevoerd',
'actionfailed' => 'De haandeling is mislokt.',
'deletedtext' => 't Artikel "$1" is vortedaon. Zie de "$2" veur n lieste van ziejen die as lest vortedaon bin.',
'dellogpage' => 'Vortdologboek',
'dellogpagetext' => 'Hieronder steet n lieste van ziejen en bestaanden die as lest vortedaon bin.',
'deletionlog' => 'Vortdologboek',
'reverted' => 'Eerdere versie hersteld',
'deletecomment' => 'Reden:',
'deleteotherreason' => 'Aandere/extra reden:',
'deletereasonotherlist' => 'Aandere reden',
'deletereason-dropdown' => '*Redens veur t vortdoon van ziejen
** Op verzeuk van de auteur
** Schending van auteursrecht
** Vandalisme',
'delete-edit-reasonlist' => 'Redens veur t vortdoon bewarken',
'delete-toobig' => 'Disse zied hef n lange bewarkingsgeschiedenisse, meer as $1 {{PLURAL:$1|versie|versies}}.
t Vortdoon van dit soort ziejen is mit rechten bepark um t per ongelok versteuren van de warking van {{SITENAME}} te veurkoemen.',
'delete-warning-toobig' => 'Disse zied hef n lange bewarkingsgeschiedenisse, meer as $1 {{PLURAL:$1|versie|versies}}.
Woart je: t vortdoon van disse zied kan de warking van de databanke van {{SITENAME}} versteuren.
Wees veurzichtig',

# Rollback
'rollback' => 'Wiezigingen herstellen',
'rollback_short' => 'Weerummedreien',
'rollbacklink' => 'Weerummedreien',
'rollbacklinkcount' => '{{PLURAL:$1|één bewarking|$1 bewarkingen}} weerummedreien',
'rollbacklinkcount-morethan' => 'Meer as {{PLURAL:$1|één bewarking|$1 bewarkingen}} weerummedreien',
'rollbackfailed' => 'Wieziging herstellen is mislokt',
'cantrollback' => 'De wiezigingen konnen niet hersteld wörden; der is mer 1 auteur.',
'alreadyrolled' => 'Kan de leste wieziging van de zied [[:$1]] deur [[User:$2|$2]] ([[User talk:$2|Overleg]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); niet weerummedreien.
n Aander hef disse zied al bewarkt of hersteld naor n eerdere versie.

De leste bewarking op disse zied is edaon deur [[User:$3|$3]] ([[User talk:$3|Overleg]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "De bewarkingssamenvatting was: ''$1''.",
'revertpage' => 'Wiezigingen deur [[Special:Contributions/$2|$2]] hersteld tot de versie nao de leste wieziging deur $1',
'revertpage-nouser' => 'Wiezigingen deur n verbörgen gebruker weerummedreid naor de leste versie deur {{GENDER:$1|[[User:$1|$1]]}}',
'rollback-success' => 'Wiezigingen van $1; weerummedreid naor de leste versie van $2.',

# Edit tokens
'sessionfailure-title' => 'Sessiefout',
'sessionfailure' => 'Der is n probleem mit joew anmeldsessie. De aksie is stop-ezet uut veurzörg tegen n beveiligingsrisico (dat besteet uut t meugelike "kraken" van disse sessie). Gao weerumme naor de veurige zied, laoj disse zied opniej en probeer t nog es.',

# Protect
'protectlogpage' => 'Beveiligingslogboek',
'protectlogtext' => 'Hieronder staon de leste wiezigingen veur t blokkeren en vriegeven van artikels en ziejen.
Zie de [[Special:ProtectedPages|lieste mit ziejen die beveiligd bin]] veur t hele overzichte.',
'protectedarticle' => '[[$1]] is beveiligd',
'modifiedarticleprotection' => 'beveiligingsnivo van "[[$1]]"  ewiezigd',
'unprotectedarticle' => 'hef de beveiliging van "[[$1]]" deraof ehaold',
'movedarticleprotection' => 'hef de beveiligingsinstellingen over-ezet van "[[$2]]" naor "[[$1]]"',
'protect-title' => 'Instellen van beveiligingsnivo veur "$1"',
'protect-title-notallowed' => 'Beveiligingsnivo veur "$1" bekieken',
'prot_1movedto2' => '[[$1]] is ewiezigd naor [[$2]]',
'protect-badnamespace-title' => 'Niet te beveiligen naamruumte',
'protect-badnamespace-text' => 'Ziejen in disse naamruumte kunnen niet beveiligd wörden.',
'protect-norestrictiontypes-text' => 'Disse zied kan niet beveiligd wörden umdat der gien beparkingstypen beschikbaor bin.',
'protect-norestrictiontypes-title' => 'Zied kan niet beveiligd wörden',
'protect-legend' => 'Beveiliging bevestigen',
'protectcomment' => 'Reden:',
'protectexpiry' => 'Duur',
'protect_expiry_invalid' => 'Verlooptied is ongeldig.',
'protect_expiry_old' => 'De verlooptied is al veurbie.',
'protect-unchain-permissions' => 'Overige beveiligingsinstellingen beschikbaor maken',
'protect-text' => "Hier ku'j t beveiligingsnivo veur de zied '''$1''' instellen.",
'protect-locked-blocked' => "Je kunnen beveiligingsnivo's niet wiezigen terwiel je eblokkeerd bin. Hier bin de instellingen zo as ze noen bin veur de zied '''$1''':",
'protect-locked-dblock' => "Beveiligingsnivo's kunnen effen niet ewiezigd wörden umdat de databanke noen beveiligd is.
Hier staon de instellingen zo as ze noen bin veur de zied '''$1''':",
'protect-locked-access' => "Je hebben gien rechten um t beveilingsnivo van ziejen te wiezigen.
Hier staon de instellingen zo as ze noen bin veur de zied '''$1''':",
'protect-cascadeon' => 'Disse zied wörden beveiligd, umdat t op-eneumen is in de volgende {{PLURAL:$1|zied|ziejen}} die beveiligd {{PLURAL:$1|is|bin}} mit de kaskadeopsie. Je kunnen t beveiligingsnivo van disse zied anpassen, mer dat hef gien invleud op de kaskadebeveiliging.',
'protect-default' => 'Veur alle gebrukers',
'protect-fallback' => 'Allinnig gebrukers mit t recht "$1" toestaon',
'protect-level-autoconfirmed' => 'Allinnig automaties bevestigden gebrukers toestaon',
'protect-level-sysop' => 'Allinnig beheerders toestaon',
'protect-summary-cascade' => 'kaskade',
'protect-expiring' => 'löp aof op $1 (UTC)',
'protect-expiring-local' => 'vervölt op $1',
'protect-expiry-indefinite' => 'onbepark',
'protect-cascade' => 'Kaskadebeveiliging (beveilig alle ziejen en mallen die in disse zied op-eneumen bin)',
'protect-cantedit' => "Je kunnen t beveiligingsnivo van disse zied niet wiezigen, umda'j gien rechten hebben um t te bewarken.",
'protect-othertime' => 'Aandere tiedsduur:',
'protect-othertime-op' => 'aandere tiedsduur',
'protect-existing-expiry' => 'Bestaonde verloopdaotum: $2 $3',
'protect-otherreason' => 'Aandere reden:',
'protect-otherreason-op' => 'aandere reden',
'protect-dropdown' => '*Veulveurkomende redens veur beveiliging
** Te veul vandalisme
** Te veul moekreklame
** Bewarkingsoorlog
** Zied mit veule bezeukers',
'protect-edit-reasonlist' => 'Redens veur beveiliging bewarken',
'protect-expiry-options' => '1 uur:1 hour,1 dag:1 day,1 weke:1 week,2 weken:2 weeks,1 maond:1 month,3 maonden:3 months,6 maonden:6 months,1 jaor:1 year,onbeparkt:infinite',
'restriction-type' => 'Toegang',
'restriction-level' => 'Beveiligingsnivo',
'minimum-size' => 'Minimumgrootte (bytes)',
'maximum-size' => 'Maximumgrootte',
'pagesize' => '(byte)',

# Restrictions (nouns)
'restriction-edit' => 'Bewark',
'restriction-move' => 'Herneum',
'restriction-create' => 'Anmaken',
'restriction-upload' => 'Bestaand opsturen',

# Restriction levels
'restriction-level-sysop' => 'helemaole beveiligd',
'restriction-level-autoconfirmed' => 'semibeveiligd',
'restriction-level-all' => 'alles',

# Undelete
'undelete' => 'Vortedaone ziejen bekieken',
'undeletepage' => 'Vortedaone ziejen bekieken en weerummeplaotsen',
'undeletepagetitle' => "'''Hieronder staon de vortedaone bewarkingen van [[:$1]]'''.",
'viewdeletedpage' => 'Bekiek vortedaone ziejen',
'undeletepagetext' => 'Hieronder {{PLURAL:$1|steet de zied die vortedaon is|staon de ziejen die vortedaon bin}} en vanuut t archief  weerummeplaotst {{PLURAL:$1|kan|kunnen}} wörden.',
'undelete-fieldset-title' => 'Versies weerummeplaotsen',
'undeleteextrahelp' => "Laot alle vakjes leeg en klik op '''''{{int:undeletebtn}}''''' um de hele zied mit alle veurgeschiedenisse weerumme te plaotsen.
Vink de versies die weerummeplaotsen willen an en klik op '''''{{int:undeletebtn}}''''' um bepaolde versies weerumme te zetten.",
'undeleterevisions' => '$1 {{PLURAL:$1|versie|versies}} earchiveerd',
'undeletehistory' => "A'j n zied weerummeplaotsen, wörden alle versies as ouwe versies weerummeplaots.
As der al n nieje zied mit de zelfde naam an-emaakt is, zullen disse versies as ouwe versies weerummeplaotst wörden, mer de op-esleugen versie zal niet ewiezigd wörden.",
'undeleterevdel' => "Herstellen kan niet as daor de leste versie van de zied of t bestaand gedeeltelik mee vortedaon wörden.
In dat geval mu'j de leste versie as zichtbaor instellen.",
'undeletehistorynoadmin' => 'Disse zied is vortedaon. De reden hierveur steet hieronder, samen mit de informasie van de gebrukers die dit artikel ewiezigd hebben veurdat t vortedaon is. De tekste van t artikel is allinnig zichtbaor veur beheerders.',
'undelete-revision' => 'Vortedaone versies van $1 (per $4 um $5) deur $3:',
'undeleterevision-missing' => "Ongeldige of ontbrekende versie. t Is meugelik da'j n verkeerde verwiezing gebruken of dat disse zied weerummeplaotst is of dat t uut t archief ewist is.",
'undelete-nodiff' => 'Gien eerdere versie evunnen.',
'undeletebtn' => 'Weerummeplaotsen',
'undeletelink' => 'bekiek/weerummeplaotsen',
'undeleteviewlink' => 'bekieken',
'undeletereset' => 'Herstel',
'undeleteinvert' => 'Seleksie ummekeren',
'undeletecomment' => 'Reden:',
'undeletedrevisions' => '$1 {{PLURAL:$1|versie|versies}} weerummeplaotst',
'undeletedrevisions-files' => '{{PLURAL:$1|1 versie|$1 versies}} en {{PLURAL:$2|1 bestaand|$2 bestaanden}} bin weerummeplaotst',
'undeletedfiles' => '{{PLURAL:$1|1 bestaand|$1 bestaanden}} weerummeplaotst',
'cannotundelete' => 't Weerummeplaotsen is mislokt:
$1',
'undeletedpage' => "'''$1 is weerummeplaotst'''

Bekiek t [[Special:Log/delete|vortdologboek]] veur n overzichte van ziejen die kortens vortedaon en weerummeplaotst bin.",
'undelete-header' => 'Zie t [[Special:Log/delete|vortdologboek ]] veur spul dat krek vortedaon is.',
'undelete-search-title' => 'Vortedaone ziejen zeuken',
'undelete-search-box' => 'Deurzeuk vortedaone ziejen',
'undelete-search-prefix' => 'Bekiek ziejen vanaof:',
'undelete-search-submit' => 'Zeuk',
'undelete-no-results' => 'Gien ziejen evunnen in t archief mit vortedaone ziejen.',
'undelete-filename-mismatch' => 'Bestaandsversie van t tiedstip $1 kon niet hersteld wörden: bestaandsnaam kloppen niet',
'undelete-bad-store-key' => 'Bestaandsversie van t tiedstip $1 kon niet hersteld wörden: t bestaand was der al niet meer veurdat t vortedaon wörden.',
'undelete-cleanup-error' => 'Fout bie t herstellen van t ongebruukten archiefbestaand "$1".',
'undelete-missing-filearchive' => 't Lokten niet um ID $1 weerumme te plaotsen umdat t niet in de databanke is.
Misschien is t al weerummeplaotst.',
'undelete-error' => 'Der is wat fout egaon bie t vortdoon van de zied',
'undelete-error-short' => 'Fout bie t herstellen van t bestaand: $1',
'undelete-error-long' => 'Fouten bie t herstellen van t bestaand:

$1',
'undelete-show-file-confirm' => 'Bi\'j der wisse van da\'j n vortedaone versie van t bestaand "<nowiki>$1</nowiki>" van $2 um $3 bekieken willen?',
'undelete-show-file-submit' => 'Ja',

# Namespace form on various pages
'namespace' => 'Naamruumte:',
'invert' => 'Seleksie ummekeren',
'tooltip-invert' => 'Vink dit vakjen an um wiezigingen an ziejen binnen de ekeuzen naamruumte te verbargen (en de biebeheurende naamruumte as dat an-evinkt is)',
'namespace_association' => 'Naamruumte die hieran ekoppeld is',
'tooltip-namespace_association' => 'Vink dit vakjen an um oek de overlegnaamruumte, of in t ummekeren geval de naamruumte zelf, derbie te doon die bie disse naamruumte heurt.',
'blanknamespace' => '(Heufdnaamruumte)',

# Contributions
'contributions' => '{{GENDER:$1|Biedragen van disse gebruker}}',
'contributions-title' => 'Biedragen van $1',
'mycontris' => 'Mien biedragen',
'contribsub2' => 'Veur {{GENDER:$3|$1}} ($2)',
'nocontribs' => 'Gien wiezigingen evunnen die an de estelde criteria voldoon.',
'uctop' => '(leste wieziging)',
'month' => 'Maond:',
'year' => 'Jaor:',

'sp-contributions-newbies' => 'Allinnig biedragen van anwas bekieken',
'sp-contributions-newbies-sub' => 'Veur anwas',
'sp-contributions-newbies-title' => 'Biedragen van anwas',
'sp-contributions-blocklog' => 'blokkeerlogboek',
'sp-contributions-deleted' => 'vortedaone gebrukersbiedragen',
'sp-contributions-uploads' => 'nieje bestaanden',
'sp-contributions-logs' => 'logboeken',
'sp-contributions-talk' => 'overleg',
'sp-contributions-userrights' => 'gebrukersrechtenbeheer',
'sp-contributions-blocked-notice' => 'Disse gebruker is op t moment eblokkeerd.
De leste regel uut t blokkeerlogboek steet hieronder as referensie:',
'sp-contributions-blocked-notice-anon' => 'Dit IP-adres is eblokkeerd.
De leste regel uut t blokkeerlogboek steet as referensie',
'sp-contributions-search' => 'Zeuken naor biedragen',
'sp-contributions-username' => 'IP-adres of gebrukersnaam:',
'sp-contributions-toponly' => 'Allinnig de niejste versie laoten zien',
'sp-contributions-submit' => 'Zeuk',

# What links here
'whatlinkshere' => 'Verwiezingen naor disse zied',
'whatlinkshere-title' => 'Ziejen die verwiezen naor "$1"',
'whatlinkshere-page' => 'Zied:',
'linkshere' => "Disse ziejen verwiezen naor '''[[:$1]]''':",
'nolinkshere' => "Gien enkele zied verwis naor '''[[:$1]]'''.",
'nolinkshere-ns' => "Gien enkele zied verwis naor '''[[:$1]]''' in de ekeuzen naamruumte.",
'isredirect' => 'deurverwiezing',
'istemplate' => 'in-evoegd as mal',
'isimage' => 'bestaandsverwiezing',
'whatlinkshere-prev' => '{{PLURAL:$1|veurige|veurige $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|volgende|volgende $1}}',
'whatlinkshere-links' => '← verwiezingen',
'whatlinkshere-hideredirs' => '$1 deurverwiezingen',
'whatlinkshere-hidetrans' => '$1 in-evoegden mallen',
'whatlinkshere-hidelinks' => '$1 verwiezingen',
'whatlinkshere-hideimages' => '$1 bestaandsverwiezingen',
'whatlinkshere-filters' => 'Filters',

# Block/unblock
'autoblockid' => 'Automatiese blokkering #$1',
'block' => 'Gebruker blokkeren',
'unblock' => 'Gebruker deblokkeren',
'blockip' => 'Gebruker blokkeren',
'blockip-title' => 'Gebruker blokkeren',
'blockip-legend' => 'n Gebruker of IP-adres blokkeren',
'blockiptext' => 'Gebruuk dit formulier um n IP-adres of gebrukersnaam te blokkeren. 
t Is bedoeld um vandalisme te veurkoemen en mut akkederen mit t [[{{MediaWiki:Policy-url}}|beleid]]. 
Geef hieronder n reden op (bieveurbeeld op welke ziejen de vandalisme epleegd is).',
'ipadressorusername' => 'IP-adres of gebrukersnaam',
'ipbexpiry' => 'Verlöp nao',
'ipbreason' => 'Reden:',
'ipbreasonotherlist' => 'aandere reden',
'ipbreason-dropdown' => '*Algemene redens veur t blokkeren
** verkeerde informasie invoeren
** ziejen leegmaken
** ongewunste verwiezingen plaotsen
** onzinteksten schrieven
** targerieje of naor gedrag
** misbruuk vanaof meerdere profielen
** ongewunste gebrukersnaam',
'ipb-hardblock' => 'Veurkoemen dat an-emelde gebrukers vanaof dit IP-adres kunnen bewarken',
'ipbcreateaccount' => 'Veurkom t anmaken van gebrukersprofielen',
'ipbemailban' => 'Veurkom dat bepaolde gebrukers berichten versturen',
'ipbenableautoblock' => 'De IP-adressen van disse gebruker vanzelf blokkeren',
'ipbsubmit' => 'adres blokkeren',
'ipbother' => 'Aandere tied',
'ipboptions' => '2 uren:2 hours,1 dag:1 day,3 dagen:3 days,1 weke:1 week,2 weken:2 weeks,1 maond:1 month,3 maonden:3 months,6 maonden:6 months,1 jaor:1 year,onbeparkt:infinite',
'ipbotheroption' => 'aanders',
'ipbotherreason' => 'Aandere/extra reden:',
'ipbhidename' => 'Verbarg de gebrukersnaam in bewarkingen en liesten',
'ipbwatchuser' => 'Gebrukerszied en overlegzied op volglieste zetten',
'ipb-disableusertalk' => 'Veurkoemen dat disse gebruker tiejens de blokkering de eigen overlegzied kan bewarken',
'ipb-change-block' => 'De gebruker opniej blokkeren mit disse instellingen',
'ipb-confirm' => 'Blokkering bevestigen',
'badipaddress' => 'Ongeldig IP-adres of onbestaonde gebrukersnaam',
'blockipsuccesssub' => 'Suksesvol eblokkeerd',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] is noen eblokkeerd.<br />
Op de [[Special:BlockList|blokkeerlieste]] steet n lieste mit alle blokkeringen.',
'ipb-blockingself' => "Hiermee blokkeer je je eigen. Wi'j dat?",
'ipb-confirmhideuser' => "Hiermee blokkeer je n verbörgen gebruker. Hierveur wörden gebrukersnamen in alle liesten en logboekregels verbörgen. Wi'j dat?",
'ipb-edit-dropdown' => 'Blokkeerredens bewarken',
'ipb-unblock-addr' => 'Deblokkeer $1',
'ipb-unblock' => 'Deblokkeer n gebruker of IP-adres',
'ipb-blocklist' => 'Bekiek bestaonde blokkeringen',
'ipb-blocklist-contribs' => 'Biedragen van $1',
'unblockip' => 'Deblokkeer gebruker',
'unblockiptext' => 'Gebruuk t onderstaonde formulier um weerumme schrieftoegang te geven an n eblokkeerden gebruker of IP-adres.',
'ipusubmit' => 'Blokkering deraof haolen',
'unblocked' => '[[User:$1|$1]] is edeblokeerd',
'unblocked-range' => '$1 is edeblokkeerd',
'unblocked-id' => 'Blokkering $1 is op-eheven',
'blocklist' => 'Gebrukers die eblokkeerd bin',
'ipblocklist' => 'Gebrukers die eblokkeerd bin',
'ipblocklist-legend' => 'n Eblokkeerden gebruker zeuken',
'blocklist-userblocks' => 'Verbarg gebrukers die eblokkeerd bin',
'blocklist-tempblocks' => 'Tiedelike blokkeringen verbargen',
'blocklist-addressblocks' => 'Blokkering van één IP-adres verbargen',
'blocklist-rangeblocks' => 'IP-adresblokkeringen verbargen',
'blocklist-timestamp' => 'Tiedstip',
'blocklist-target' => 'Doel',
'blocklist-expiry' => 'Vervölt',
'blocklist-by' => 'Eblokkeerd deur',
'blocklist-params' => 'Blokkeringsparameters',
'blocklist-reason' => 'Reden',
'ipblocklist-submit' => 'Zeuk',
'ipblocklist-localblock' => 'Lokale blokkering',
'ipblocklist-otherblocks' => 'Aandere {{PLURAL:$1|blokkering|blokkeringen}}',
'infiniteblock' => 'onbeparkt',
'expiringblock' => 'löp aof op $1 um $2',
'anononlyblock' => 'allinnig anoniemen',
'noautoblockblock' => 'autoblok niet aktief',
'createaccountblock' => 'anmaken van n gebrukersprofiel is eblokkeerd',
'emailblock' => 't versturen van berichten is eblokkeerd',
'blocklist-nousertalk' => 'kan zien eigen overlegzied niet bewarken',
'ipblocklist-empty' => 'De blokkeerlieste is leeg.',
'ipblocklist-no-results' => 't Op-evreugen IP-adres of de gebrukersnaam is niet eblokkeerd.',
'blocklink' => 'blokkeren',
'unblocklink' => 'deblokkeer',
'change-blocklink' => 'blokkering wiezigen',
'contribslink' => 'biedragen',
'emaillink' => 'netpostbericht sturen',
'autoblocker' => 'Vanzelf eblokkeerd umdat t IP-adres overenekömp mit t IP-adres van [[User:$1|$1]], die eblokkeerd is mit as reden: "$2"',
'blocklogpage' => 'Blokkeerlogboek',
'blocklog-showlog' => 'Disse gebruker is al eerder eblokkeerd.
t Blokkeerlogboek steet hieronder as referensie:',
'blocklog-showsuppresslog' => 'Disse gebruker is al eerder eblokkeerd en wele bewarkingen van disse gebruker bin verbörgen.
t Logboek mit onderdrokten versies steet hieronder as referensie:',
'blocklogentry' => 'hef "[[$1]]"  eblokkeerd veur $2 $3',
'reblock-logentry' => 'hef de instellingen veur de blokkering van [[$1]] ewiezigd t Löp noen of over $2 $3',
'blocklogtext' => "Hier zie'j n lieste van de leste blokkeringen en deblokkeringen. Automatiese blokkeringen en deblokkeringen koemen niet in t logboek te staon. Zie de [[Special:BlockList|blokkeerlieste]] veur de lieste van adressen die noen eblokkeerd bin.",
'unblocklogentry' => 'blokkering van $1 is op-eheven',
'block-log-flags-anononly' => 'allinnig anoniemen',
'block-log-flags-nocreate' => 'anmaken van gebrukersprofielen uutezet',
'block-log-flags-noautoblock' => 'autoblokkeren uutezet',
'block-log-flags-noemail' => 't versturen van berichten is eblokkeerd',
'block-log-flags-nousertalk' => 'kan zien eigen overlegzied niet bewarken',
'block-log-flags-angry-autoblock' => 'uutebreide automatiese blokkering in-eschakeld',
'block-log-flags-hiddenname' => 'gebrukersnaam verbörgen',
'range_block_disabled' => 'De meugelikheid veur beheerders um n groep adressen te blokkeren is uutezet.',
'ipb_expiry_invalid' => 'De op-egeven verlooptied is ongeldig.',
'ipb_expiry_temp' => 'Blokkeringen veur verbörgen gebrukers mutten permanent ween.',
'ipb_hide_invalid' => 'Kan disse gebruker niet verbargen; warschienlik hef e al te veule bewarkingen emaakt.',
'ipb_already_blocked' => '"$1" is al eblokkeerd',
'ipb-needreblock' => "$1 is al eblokkeerd.
Wi'j de instellingen wiezigen?",
'ipb-otherblocks-header' => 'Aandere {{PLURAL:$1|blokkering|blokkeringen}}',
'unblock-hideuser' => 'Je kunnen disse gebruker niet deblokkeeren, omdat de gebrukersnaam verbörgen is.',
'ipb_cant_unblock' => 'Foutmelding: blokkerings-ID $1 niet evunnen, t is misschien al edeblokkeerd.',
'ipb_blocked_as_range' => 'Fout: t IP-adres $1 is niet drek eblokkeerd en de blokkering kan niet op-eheven wörden.
De blokkering is onderdeel van de reeks $2, waorvan de blokkering wel op-eheven kan wörden.',
'ip_range_invalid' => 'Ongeldige IP-reeks',
'ip_range_toolarge' => 'Groeps-IP-adressen die groter bin as /$1, bin niet toe-estaon.',
'proxyblocker' => 'Proxyblokker',
'proxyblockreason' => "Dit is n automatiese preventieve blokkering umda'j gebruukmaken van n open proxyserver.",
'sorbsreason' => "Joew IP-adres is op-eneumen as open proxyserver in de zwarte lieste van DNS die'w veur {{SITENAME}} gebruken.",
'sorbs_create_account_reason' => "Joew IP-adres is op-eneumen as open proxyserver in de zwarte lieste van DNS, die'w veur {{SITENAME}} gebruken.
Je kunnen gien gebrukerszied anmaken.",
'xffblockreason' => "n IP-adres dat jie gebruken is eblokkeerd. Dit steet in de kop 'X-Forwarded-For'. De oorspronkelike reden veur de blokkerings is: $1",
'cant-block-while-blocked' => "Je kunnen aandere gebrukers niet blokkeren a'j zelf oek eblokkeerd bin.",
'cant-see-hidden-user' => "De gebruker die'j proberen te blokkeren is al eblokkeerd en verbörgen.
Umda'j gien rech hebben um gebrukers te verbargen, ku'j de blokkering van de gebruker niet bekieken of bewarken.",
'ipbblocked' => "Je kunnen gien aandere gebrukers (de)blokkeren, umda'j zelf eblokkeerd bin",
'ipbnounblockself' => 'Je maggen je eigen niet deblokkeren',

# Developer tools
'lockdb' => 'Databanke blokkeren',
'unlockdb' => 'Databanke vriegeven',
'lockdbtext' => "Waorschuwing: a'j de databanke blokkeren dan kan der gienene meer ziejen bewarken, zien veurkeuren wiezingen of wat aanders doon waorveur der wiezigingen in de databanke neudig bin.",
'unlockdbtext' => 'Vriegeven van de databanke maak alle bewarkingen weer meugelik.
Mut de databanke vrie-egeven wörden?',
'lockconfirm' => 'Ja, ik wille de databanke blokkeren.',
'unlockconfirm' => 'Ja, ik wille de databanke vriegeven.',
'lockbtn' => 'Databanke blokkeren',
'unlockbtn' => 'Databanke vriegeven',
'locknoconfirm' => 'Je hebben t vakjen niet ekeuzen um joew keuze te bevestigen.',
'lockdbsuccesssub' => 'Databanke suksesvol eblokkeerd',
'unlockdbsuccesssub' => 'Blokkering van de databanke is op-eheven.',
'lockdbsuccesstext' => "De databanke is eblokkeerd.<br />
Vergeet niet de [[Special:UnlockDB|databanke vrie te geven]] a'j klaor bin mit t onderhoud.",
'unlockdbsuccesstext' => 'De databanke is weer vrie-egeven.',
'lockfilenotwritable' => 'Gien schriefrechten op t beveiligingsbestaand van de databanke. Um de databanke te blokkeren of de blokkering op te heffen, mut der eschreven kunnen wörden deur de webserver.',
'databasenotlocked' => 'De databanke is niet eblokkeerd.',
'lockedbyandtime' => '(deur $1 um $3 op $2)',

# Move page
'move-page' => 'Herneum "$1"',
'move-page-legend' => 'Zied herneumen',
'movepagetext' => "Mit dit formulier ku'j de zied n nieje naam geven, de geschiedenisse geet dan vanzelf mee.
De ouwe naam zal automaties n deurverwiezing wörden naor de nieje zied.
Deurverwiezingen naor de ouwe naam kunnen automaties ewiezigd wörden.
A'j derveur kiezen um dat niet te doon, kiek t dan effen nao of der [[Special:DoubleRedirects|dubbele]] en [[Special:BrokenRedirects|ebreuken deurverwiezingen]] bin ontstaon.
t Is an joe um derveur te zörgen dat de deurverwiezingen naor de goeie naam gaon.

n Zied kan '''allinnig''' herneumd wörden as de nieje naam niet besteet of t n deurverwiezing is zonder veerdere geschiedenisse.
Dit betekent da'j n zied weer naor de ouwe naam kunnen herneumen, a'j bieveurbeeld n fout emaakt hebben, zonder da'j de bestaonde zied overschrieven.

'''WAORSCHUWING!'''
Veur populaere ziejen kan t herneumen drastiese en onveurziene gevolgen hebben.
Zörg derveur da'j de gevolgen overzien veurda'j veerder gaon.",
'movepagetext-noredirectfixer' => "Mit dit formulier ku'j de zied n nieje naam geven, de geschiedenisse geet dan vanzelf mee.
De ouwe naam zal automaties n deurverwiezing wörden naor de nieje zied.
Kiek oek effen nao of der gien [[Special:DoubleRedirects|dubbele]] of [[Special:BrokenRedirects|ebreuken deurverwiezingen]] bin ontstaon.
t Is an joe um derveur te zörgen dat de deurverwiezingen naor de goeie naam gaon.

n Zied kan '''allinnig''' herneumd wörden as de nieje naam niet besteet of t n deurverwiezing is zonder veerdere geschiedenisse.
Dit betekent da'j n zied weer naor de ouwe naam kunnen herneumen, a'j bieveurbeeld n fout emaakt hebben, zonder da'j de bestaonde zied overschrieven.

'''WAORSCHUWING!'''
Veur populaere ziejen kan t herneumen drastiese en onveurziene gevolgen hebben.
Zörg derveur da'j de gevolgen overzien veurda'j veerder gaon.",
'movepagetalktext' => "De overlegzied die derbie heurt krig oek n nieje titel, mer '''niet''' in de volgende gevallen:
* As de zied in n aandere naamruumte eplaotst wörden
* As der al n niet-lege overlegzied besteet onder de aandere naam
* a'j t onderstaonde vinkjen vorthaolen",
'movearticle' => 'Herneum',
'moveuserpage-warning' => "'''Waorschuwing:''' Je staon op t punt um n gebrukerszied te herneumen. Allinnig disse zied zal herneumd wörden, '''niet''' de gebruker.",
'movenologin' => 'Niet an-emeld.',
'movenologintext' => 'Je mutten [[Special:UserLogin|an-emeld]] ween um de naam van n zied te wiezigen.',
'movenotallowed' => 'Je hebben gien rechten um ziejen te herneumen.',
'movenotallowedfile' => 'Je hebben gien rechten um bestaanden te herneumen.',
'cant-move-user-page' => 'Je hebben gien rechten um gebrukersziejen te herneumen.',
'cant-move-to-user-page' => "Je hebben gien rechten um n zied naor n gebrukerszied te herneumen. Herneumen naor n zied die deronder völt ma'j wel doon.",
'newtitle' => 'Nieje naam',
'move-watch' => 'volg disse zied',
'movepagebtn' => 'Herneum',
'pagemovedsub' => 'Naamwieziging suksesvol',
'movepage-moved' => '\'\'\'"$1" is ewiezigd naor "$2"\'\'\'',
'movepage-moved-redirect' => 'Der is n deurverwiezing an-emaakt.',
'movepage-moved-noredirect' => 'Der is gien deurverwiezing an-emaakt.',
'articleexists' => 'Onder disse naam besteet al n zied. Kies n aandere naam.',
'cantmove-titleprotected' => 'Je kunnen gien zied naor disse titel herneumen, umdat de nieje titel beveiligd is tegen t anmaken dervan.',
'talkexists' => 'De zied zelf is herneumd, mer de overlegzied kon niet verherneumd wörden, umdat de doelnaam al n niet-lege overlegzied had. Kombineer de overlegziejen mit de haand.',
'movedto' => 'wiezigen naor',
'movetalk' => 'De overlegzied oek wiezigen, as t meuglik is.',
'move-subpages' => 'Herneum de ziejen die deronder hangen (tot en mit $1)',
'move-talk-subpages' => 'Herneum de ziejen die onder de overlegziejen hangen (tot en mit $1)',
'movepage-page-exists' => 'De zied $1 besteet al en kan niet automaties vortedaon wörden.',
'movepage-page-moved' => 'De zied $1 is herneumd naor $2.',
'movepage-page-unmoved' => 'De zied $1 kon niet herneumd wörden naor $2.',
'movepage-max-pages' => 't Maximale antal automaties te herneumen ziejen is bereikt ({{PLURAL:$1|$1|$1}}).
De aandere ziejen wörden niet automaties herneumd.',
'movelogpage' => 'Herneumlogboek',
'movelogpagetext' => 'Hieronder steet n lieste mit ziejen die herneumd bin.',
'movesubpage' => '{{PLURAL:$1|Zied die deronder hank|Ziejen die deronder hangen}}',
'movesubpagetext' => "De {{PLURAL:$1|zied die onder disse zied hank|$1 ziejen die onder disse zied hangen}} vie'j hieronder.",
'movenosubpage' => 'Onder disse zied hangen gien aandere ziejen.',
'movereason' => 'Reden:',
'revertmove' => 'Weerummedreien',
'delete_and_move' => 'Vortdoon en herneumen',
'delete_and_move_text' => '==Mut vortedaon wörden==
<div style="color: red"> Onder de nieje naam "[[:$1]]" besteet al n artikel. Wi\'j t vortdoon um plaotse te maken veur t herneumen?</div>',
'delete_and_move_confirm' => 'Ja, disse zied vortdoon',
'delete_and_move_reason' => 'Vortedaon vanwegen de herneuming van "[[$1]]"',
'selfmove' => 'De naam kan niet ewiezigd wörden naor de naam die t al hef.',
'immobile-source-namespace' => 'Ziejen in de naamruumte "$1" kunnen niet herneumd wörden',
'immobile-target-namespace' => 'Ziejen kunnen niet herneumd wörden naor de naamruumte "$1"',
'immobile-target-namespace-iw' => 'n Interwikiverwiezing is gien geldige bestemming veur t herneumen van n zied.',
'immobile-source-page' => 'Disse zied kan niet herneumd wörden.',
'immobile-target-page' => 'Kan niet herneumd wörden naor disse ziednaam.',
'bad-target-model' => 'De gewunste bestemming gebruukt n aander inhoudsmodel. Kan niet ummezetten van $1 naor $2.',
'imagenocrossnamespace' => 'n Mediabestaand kan niet naor n aandere naamruumte verplaotst wörden',
'nonfile-cannot-move-to-file' => 'Je kunnen niet herneumen van en naor de bestaandsnaamruumte',
'imagetypemismatch' => 'De nieje bestaandsextensie is niet gelieke an t bestaandstype',
'imageinvalidfilename' => 'De nieje bestaandsnaam is ongeldig',
'fix-double-redirects' => 'Alle deurverwiezingen die naor de ouwe titel verwiezen, herneumen naor de nieje titel',
'move-leave-redirect' => 'n Deurverwiezing achterlaoten',
'protectedpagemovewarning' => "'''Waorschuwing:''' disse zied kan allinnig deur beheerders herneumd wörden.",
'semiprotectedpagemovewarning' => "'''Waorschuwing:''' disse zied kan allinnig deur eregistreerden gebrukers herneumd wörden.
De leste logboekregel steet hieronder:",
'move-over-sharedrepo' => "== t Bestaand besteet al ==
[[:$1]] besteet al in de edeelden mediadatabanke. A'j n bestaand naor disse titel herneumen, dan ku'j  t edeelden bestaand niet gebruken.",
'file-exists-sharedrepo' => 'Disse bestaandsnaam besteet al in de edeelden mediadatabanke.
Kies n aandere bestaandsnaam.',

# Export
'export' => 'Ziejen uutvoeren',
'exporttext' => "De tekste en geschiedenisse van n zied of n koppel ziejen kunnen in XML-formaot uutevoerd wörden. Dit bestaand ku'j daornao uutvoeren naor n aandere MediaWiki deur de [[Special:Import|invoerzied]] te gebruken.

Zet in t onderstaonde veld de namen van de ziejen die'j uutvoeren willen, één zied per regel, en gif an o'j alle versies mit de bewarkingssamenvatting uutvoeren willen of allinnig de leste versies mit de bewarkingssamenvatting.

A'j dat leste doon willen dan ku'j oek n verwiezing gebruken, bieveurbeeld [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] veur de zied \"[[{{MediaWiki:Mainpage}}]]\".",
'exportall' => 'Alle ziejen uutvoeren',
'exportcuronly' => 'Allinnig de actuele versie, niet de veurgeschiedenisse',
'exportnohistory' => "----
'''NB:''' t uutvoeren van de hele geschiedenisse is uutezet vanwegen prestasieredens.",
'exportlistauthors' => 'De hele auteurslieste opnemen veur elke zied',
'export-submit' => 'Uutvoeren',
'export-addcattext' => 'Ziejen derbie doon uut de kategorie:',
'export-addcat' => 'Derbie doon',
'export-addnstext' => 'Ziejen uut de volgende naamruumte derbie doon:',
'export-addns' => 'Derbie doon',
'export-download' => 'As bestaand opslaon',
'export-templates' => 'Mit mallen derbie',
'export-pagelinks' => 'Ziejen waor naor verwezen wörden opnemen tot:',

# Namespace 8 related
'allmessages' => 'Alle systeemteksten',
'allmessagesname' => 'Naam',
'allmessagesdefault' => 'Standardtekste',
'allmessagescurrent' => 'De leste versie',
'allmessagestext' => "Hieronder steet n lieste mit alle systeemteksten in de MediaWiki-naamruumte.
Kiek oek effen bie [//www.mediawiki.org/wiki/Localisation MediaWiki-lokalisasie] en [//translatewiki.net translatewiki.net] a'j biedragen willen an de algemene vertaling veur MediaWiki.",
'allmessagesnotsupportedDB' => "Disse zied kan niet gebruukt wörden umdat '''\$wgUseDatabaseMessages''' uutezet is.",
'allmessages-filter-legend' => 'Filter',
'allmessages-filter' => 'Filtreer op wiezigingen:',
'allmessages-filter-unmodified' => 'niet ewiezigd',
'allmessages-filter-all' => 'alles',
'allmessages-filter-modified' => 'ewiezigd',
'allmessages-prefix' => 'Filtreer op veurvoegsel:',
'allmessages-language' => 'Taal:',
'allmessages-filter-submit' => 'zeuk',

# Thumbnails
'thumbnail-more' => 'vergroten',
'filemissing' => 'Bestaand ontbrik',
'thumbnail_error' => 'Fout bie t laojen van de miniatuuraofbeelding: $1',
'thumbnail_error_remote' => 'Foutmelding van $1:
$2',
'djvu_page_error' => 'DjVu-zied buten bereik',
'djvu_no_xml' => 'Kon de XML-gegevens veur t DjVu-bestaand niet oproepen',
'thumbnail-temp-create' => 'Kon gien tiedelik miniatuurbestaand anmaken.',
'thumbnail-dest-create' => 'Kon gien miniatuurbestaand op de doellokasie opslaon.',
'thumbnail_invalid_params' => 'Ongeldige parameters veur de miniatuuraofbeelding',
'thumbnail_dest_directory' => 'De bestemmingsmap kon niet an-emaakt wörden.',
'thumbnail_image-type' => 'Dit bestaandstype wörden niet ondersteund',
'thumbnail_gd-library' => 'De instellingen veur de GD-biebeltheek bin niet compleet. De funksie $1 ontbrik',
'thumbnail_image-missing' => 't Lik derop dat t bestaand vort is: $1',

# Special:Import
'import' => 'Ziejen invoeren',
'importinterwiki' => 'Transwiki-invoer',
'import-interwiki-text' => 'Kies n wiki en ziednaam um in te voeren.
Versie- en auteursgegevens blieven hierbie beweerd.
Alle transwiki-invoerhaandelingen wörden op-esleugen in t [[Special:Log/import|invoerlogboek]].',
'import-interwiki-source' => 'Bronwiki/zied:',
'import-interwiki-history' => 'Kopieer de hele geschiedenisse veur disse zied',
'import-interwiki-templates' => 'Alle mallen opnemen',
'import-interwiki-submit' => 'Invoeren',
'import-interwiki-namespace' => 'Doelnaamruumte:',
'import-interwiki-rootpage' => 'Baosiszied veur doel (opsioneel):',
'import-upload-filename' => 'Bestaandsnaam:',
'import-comment' => 'Opmarkingen:',
'importtext' => 'Gebruuk de [[Special:Export|uutvoerfunksie]] in de wiki waor de informasie vandaon kömp.
Slao t op joew eigen systeem op, en stuur t daornao hier op.',
'importstart' => 'Ziejen an t invoeren...',
'import-revision-count' => '$1 {{PLURAL:$1|versie|versies}}',
'importnopages' => 'Der bin gien ziejen um in te voeren.',
'imported-log-entries' => '$1 {{PLURAL:$1|logboekregel|logboekregels}} in-evoerd.',
'importfailed' => 'Invoeren is mislokt: $1',
'importunknownsource' => 'Onbekend invoerbrontype',
'importcantopen' => 'Kon t invoerbestaand niet los doon',
'importbadinterwiki' => 'Foute interwikiverwiezing',
'importnotext' => 'Leeg of gien tekste',
'importsuccess' => 'Invoeren suksesvol!',
'importhistoryconflict' => 'Der bin konflikten in de geschiedenisse van de zied (is misschien eerder al in-evoerd)',
'importnosources' => 'Gien transwiki-invoerbronnen vastesteld en t drek inlaojen van versies is eblokkeerd.',
'importnofile' => 'Der is gien invoerbestaand op-estuurd.',
'importuploaderrorsize' => 't Opsturen van t invoerbestaand is mislokt.
t Bestaand is groter as de in-estelde limiet.',
'importuploaderrorpartial' => 't Opsturen van t invoerbestaand is mislokt.
t Bestaand is mer gedeeltelik an-ekeumen.',
'importuploaderrortemp' => 't Opsturen van t invoerbestaand is mislokt.
De tiedelike map is niet anwezig.',
'import-parse-failure' => 'Fout bie t verwarken van de XML-invoer',
'import-noarticle' => 'Der bin gien ziejen um in te voeren!',
'import-nonewrevisions' => 'Alle versies bin al eerder in-evoerd.',
'xml-error-string' => '$1 op regel $2, kolom $3 (byte $4): $5',
'import-upload' => 'XML-gegevens derbie doon',
'import-token-mismatch' => 'De sessiegegevens bin verleuren egaon. Probeer t opniej.',
'import-invalid-interwiki' => 't Is niet meugelik um van de an-egeven wiki in te voeren.',
'import-error-edit' => 'De zied "$1" is niet in-evoerd umda\'j de rechten niet hebben um t te bewarken.',
'import-error-create' => 'De zied "$1" is niet in-evoerd umda\'j de rechten niet hebben um t an te maken.',
'import-error-interwiki' => 'De zied "$1" is niet in-evoerd umdat disse naam ereserveerd is veur externe verwiezingen (interwiki).',
'import-error-special' => 'Zied "$1" is niet in-evoerd umdat t eplaotst is in n spesiale naamruumte waor gien ziejen in eplaotst kunnen wörden.',
'import-error-invalid' => 'De zied" "$1" is niet in-evoerd umdat de naam ongeldig is.',
'import-error-unserialize' => 'Versie $2 van de zied "$1" kon niet verwarkt wörden. De versie heurt inhoudsmodel $3 te gebruken mit n serialisasie as $4.',
'import-options-wrong' => 'Verkeerde {{PLURAL:$2|opsie|opsies}}: <nowiki>$1</nowiki>',
'import-rootpage-invalid' => 'De op-egeven baosiszied is ongeldig.',
'import-rootpage-nosubpage' => 'In de naamruumte "$1" van de baosiszied is t anmaken van onderziejen niet meugelik.',

# Import log
'importlogpage' => 'Invoerlogboek',
'importlogpagetext' => "Administratieve invoer van ziejen mit geschiedenisse van aandere wiki's.",
'import-logentry-upload' => 'hef [[$1]] in-evoerd',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|versie|versies}}',
'import-logentry-interwiki' => 'transwiki $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|versie|versies}} van $2',

# JavaScriptTest
'javascripttest' => 'JavaScript testen',
'javascripttest-title' => 'Tests uutvoeren veur $1',
'javascripttest-pagetext-noframework' => 'Disse zied is ereserveerd veur t uutvoeren van JavaScript-testen.',
'javascripttest-pagetext-unknownframework' => 'Onbekend testraamwark "$1".',
'javascripttest-pagetext-frameworks' => 'Kies een van de volgende testraamwarken: $1',
'javascripttest-pagetext-skins' => 'Kies n vormgeving um de tests mee uut te voeren:',
'javascripttest-qunit-intro' => 'Zie de [$1 testdokumentasie] op mediawiki.org.',
'javascripttest-qunit-heading' => 'QUnit testsuite veur MediaWiki JavaScript',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Oew gebroekersbladziede',
'tooltip-pt-anonuserpage' => "Gebroekersbladziede vuur t IP-adres da'j broekt",
'tooltip-pt-mytalk' => 'Oew oaverlegbladziede',
'tooltip-pt-anontalk' => 'Oaverlegbladziede van n naamlozen gebroeker van dit IP-adres',
'tooltip-pt-preferences' => 'Miene vuurkeuren',
'tooltip-pt-watchlist' => 'Lieste van bladzieden die op miene volglieste stoan',
'tooltip-pt-mycontris' => 'Liest van oew biejdraegen',
'tooltip-pt-login' => 'Iej wördt van harte oetneugd um oe an te melden as gebroeker, mer t is nich verplicht',
'tooltip-pt-anonlogin' => 'Iej wördt van harte oetneugd um oe an te maelden as gebroeker, mer t is nich verplicht',
'tooltip-pt-logout' => 'Ofmaelden',
'tooltip-ca-talk' => 'Loat n oaverlegtekst oaver disse bladziede zeen',
'tooltip-ca-edit' => 'Bewaerk disse bladziede',
'tooltip-ca-addsection' => 'Niej oonderwaerp tovogen',
'tooltip-ca-viewsource' => 'Disse bladziede is beveiligd taegen veraanderen. Iej könt wal kieken noar de bladziede',
'tooltip-ca-history' => 'Oaldere versies van disse bladziede',
'tooltip-ca-protect' => 'Beveilig disse bladziede taegen veraanderen',
'tooltip-ca-unprotect' => 'De beveiliging vuur disse bladziede wiezigen',
'tooltip-ca-delete' => 'Smiet disse bladziede vort',
'tooltip-ca-undelete' => 'Haal n inhoald van disse bladziede oet n emmer',
'tooltip-ca-move' => 'Gef disse bladziede nen aanderen titel',
'tooltip-ca-watch' => 'Voog disse bladziede to an oewe volglieste',
'tooltip-ca-unwatch' => 'Smiet disse bladziede van oewe voalglieste',
'tooltip-search' => '{{SITENAME}} duurzeukn',
'tooltip-search-go' => 'Noar n bladziede mit disse naam goan as t besteet',
'tooltip-search-fulltext' => 'Zeuk noar ziedn woar disse tekst in steet',
'tooltip-p-logo' => 'Goa noar t vuurblad',
'tooltip-n-mainpage' => 'Goa noar t vuurblad',
'tooltip-n-mainpage-description' => 'Goa noar t vuurblad',
'tooltip-n-portal' => 'Informoasie oaver t projekt: wel, wat, ho en woarum',
'tooltip-n-currentevents' => 'Achtergroondinformoasie oaver dinge in t niejs',
'tooltip-n-recentchanges' => 'Lieste van pas verrichte veraanderingen',
'tooltip-n-randompage' => 'Loat ne willekeurige bladziede zeen',
'tooltip-n-help' => 'Hölpinformoasie oaver {{SITENAME}}',
'tooltip-t-whatlinkshere' => 'Lieste van alle bladzieden die hiernoar verwiezen',
'tooltip-t-recentchangeslinked' => 'Pas verrichte veraanderingen die noar disse bladziede verwiezen',
'tooltip-feed-rss' => 'RSS-voer vuur disse bladziede',
'tooltip-feed-atom' => 'Atom-voer vuur disse bladziede',
'tooltip-t-contributions' => 'Lieste met biejdraegen van disse gebroeker',
'tooltip-t-emailuser' => 'Stuur disse gebroeker n netpostbericht',
'tooltip-t-upload' => 'Laad ofbeeldingen en/of geluudsmateriaal',
'tooltip-t-specialpages' => 'Lieste van alle biejzeundere bladzieden',
'tooltip-t-print' => 'De ofdrukboare versie van disse bladziede',
'tooltip-t-permalink' => 'Verbeending vuur altied noar de versie van disse bladziede van vandaag-an-n-dag',
'tooltip-ca-nstab-main' => 'Loat n tekst van t artikel zeen',
'tooltip-ca-nstab-user' => 'Loat de gebroekersbladziede zeen',
'tooltip-ca-nstab-media' => 'Loat n mediatekst zeen',
'tooltip-ca-nstab-special' => "Dit is ne biejzeundere bladziede die'j nich könt veraanderen",
'tooltip-ca-nstab-project' => 'Loat de projektbladziede zeen',
'tooltip-ca-nstab-image' => 'Loat de bestaandsbladziede zeen',
'tooltip-ca-nstab-mediawiki' => 'Loat de systeemtekstbladziede zeen',
'tooltip-ca-nstab-template' => 'Loat de malbladziede zeen',
'tooltip-ca-nstab-help' => 'Loat de hölpbladziede zeen',
'tooltip-ca-nstab-category' => 'Loat de rubriekbladziede zeen',
'tooltip-minoredit' => 'Markeer as n klaene wieziging',
'tooltip-save' => 'Wiezigingen opsloan',
'tooltip-preview' => "Bekiek oew versie vuurda'j t opsloan (anbeveulen)!",
'tooltip-diff' => 'Bekiek oew aegen wiezigingen',
'tooltip-compareselectedversions' => 'Bekiek de verschillen tussen de ekeuzen versies.',
'tooltip-watch' => 'Voog disse bladziede to an oew volglieste',
'tooltip-watchlistedit-normal-submit' => 'Ziejen vortdoon',
'tooltip-watchlistedit-raw-submit' => 'Volglieste biewarken',
'tooltip-recreate' => 'Disse bladziede opniej anmaken, ondanks t feit dat t vortdoan is.',
'tooltip-upload' => 'Bestaanden tovogen',
'tooltip-rollback' => 'Mit "weerummedreien" kö\'j mit één klik de bewaerking(en) van n leste gebroeker dee disse bladziede bewaerkt hef terugdraeien.',
'tooltip-undo' => 'A\'j op "weerummedreien" klikken geet t bewaerkingsvaenster lös en kö\'j ne vurige versie terugzetten.
Iej könt in de bewearkingssamenvatting n reden opgeven.',
'tooltip-preferences-save' => 'Vuurkeuren opsloan',
'tooltip-summary' => 'Voer ne korte samenvatting in',
'tooltip-iwiki' => '$1 – $2',

# Metadata
'notacceptable' => 'De wikiserver kan de gegevens niet leveren in n vorm die joew kliënt kan lezen.',

# Attribution
'anonymous' => 'Anonieme {{PLURAL:$1|gebruker|gebrukers}} van {{SITENAME}}',
'siteuser' => '{{SITENAME}}-gebruker $1',
'anonuser' => 'Anonieme {{SITENAME}}-gebruker $1',
'lastmodifiedatby' => 'Disse zied is t lest ewiezigd op $2, $1 deur $3.',
'othercontribs' => 'Ebaseerd op wark van $1.',
'others' => 'aandere',
'siteusers' => '{{SITENAME}}-{{PLURAL:$2|gebruker|gebrukers}}  $1',
'anonusers' => 'Anonieme {{SITENAME}}-{{PLURAL:$2|gebruker|gebrukers}} $1',
'creditspage' => 'Auteursinformasie',
'nocredits' => 'Der is gien auteursinformasie beschikbaor veur disse zied.',

# Spam protection
'spamprotectiontitle' => 'Moekfilter',
'spamprotectiontext' => "De zied die'j opslaon wollen is eblokkeerd deur de moekfilter.
Meestentieds kömp dit deur n uutgaonde verwiezing die op de zwarte lieste steet.",
'spamprotectionmatch' => 'Disse tekste zörgen derveur dat onze moekfilter alarmsleug: $1',
'spambot_username' => 'MediaWiki ongewunste zooi oprumen',
'spam_reverting' => 'Bezig mit t weerummezetten naor de leste versie die gien verwiezing hef naor $1',
'spam_blanking' => 'Alle wiezigingen mit n verwiezing naor $1 wörden vortehaold',
'spam_deleting' => 'In alle versies staon verwiezingen naor $1. Zied vortedaon',

# Info page
'pageinfo-title' => 'Informasie over "$1"',
'pageinfo-not-current' => 'Disse gegevens bin allinnig beschikbaor veur disse versie.',
'pageinfo-header-basic' => 'Baosisinformasie',
'pageinfo-header-edits' => 'Bewarkingsgeschiedenisse',
'pageinfo-header-restrictions' => 'Ziedbeveiliging',
'pageinfo-header-properties' => 'Ziedeigenschappen',
'pageinfo-display-title' => 'Weeregeven ziednaam',
'pageinfo-default-sort' => 'Standard sorteerwieze',
'pageinfo-length' => 'Ziedlengte (in bytes)',
'pageinfo-article-id' => 'Zied-ID',
'pageinfo-language' => 'Taal veur de zied',
'pageinfo-robot-policy' => 'Indexering deur bots',
'pageinfo-robot-index' => 'Toe-estaon',
'pageinfo-robot-noindex' => 'Niet toe-estaon',
'pageinfo-views' => 'Antal keer bekeken',
'pageinfo-watchers' => 'Antal ziedvolgers',
'pageinfo-few-watchers' => 'Minder as {{PLURAL:$1|één volger|$1 volgers}}',
'pageinfo-redirects-name' => 't Antal deurverwiezingen naor disse zied',
'pageinfo-subpages-name' => 'Onderziejen van disse zied',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|deurverwiezing|deurverwiezingen}}; $3 {{PLURAL:$3|niet-deurverwiezing|niet-deurverwiezingen}})',
'pageinfo-firstuser' => 'Gebruker die de zied an-emaakt hef',
'pageinfo-firsttime' => 'Daotum waorop de zied an-emaakt is',
'pageinfo-lastuser' => 'Leste bewarker',
'pageinfo-lasttime' => 'Daotum van leste bewarking',
'pageinfo-edits' => 'Totaal antal bewarkingen',
'pageinfo-authors' => 'Totaal antal verschillende auteurs',
'pageinfo-recent-edits' => 'Antal nieje bewarkingen (in de veurbieje $1).',
'pageinfo-recent-authors' => 'Leste antal van verschillende auteurs',
'pageinfo-magic-words' => '{{PLURAL:$1|Magies woord|Magiese woorden}} ($1)',
'pageinfo-hidden-categories' => 'Verbörgen {{PLURAL:$1|kategorie|kategorieën}} ($1)',
'pageinfo-templates' => '{{PLURAL:$1|Gebruukten mal|Gebruukten mallen}} ($1)',
'pageinfo-transclusions' => '{{PLURAL:$1|Zied|Ziejen}} in-evoegd op ($1)',
'pageinfo-toolboxlink' => 'Ziedgegevens',
'pageinfo-redirectsto' => 'Verwis deur naor',
'pageinfo-redirectsto-info' => 'informasie',
'pageinfo-contentpage' => 'Eteld as zied mit inhoud',
'pageinfo-contentpage-yes' => 'Ja',
'pageinfo-protect-cascading' => 'Beveiligingen warken vanaof hier deur',
'pageinfo-protect-cascading-yes' => 'Ja',
'pageinfo-protect-cascading-from' => 'Zied is beveiligd vanuut n aandere zied',
'pageinfo-category-info' => 'Kategorie-informasie',
'pageinfo-category-pages' => 'Antal ziejen',
'pageinfo-category-subcats' => 'Antal onderkategorieën',
'pageinfo-category-files' => 'Antal bestaanden',

# Skin names
'skinname-cologneblue' => 'Keuls blauw',
'skinname-monobook' => 'Monobook',
'skinname-modern' => 'Niejmoeds',

# Patrolling
'markaspatrolleddiff' => 'Zet op nao-ekeken',
'markaspatrolledtext' => 'Disse zied is op nao-ekeken ezet',
'markedaspatrolled' => 'Op nao-ekeken ezet',
'markedaspatrolledtext' => 'De ekeuzen versie van [[:$1]] is op nao-ekeken ezet.',
'rcpatroldisabled' => 'De naokiekmeugelikheid op "Leste wiezigingen" is uutezet.',
'rcpatroldisabledtext' => 'De meugelikheid um de leste wiezigingen op nao-ekeken te zetten is noen uutezet.',
'markedaspatrollederror' => 'De bewarking kon niet aofevinkt wörden.',
'markedaspatrollederrortext' => 'Je mutten n wieziging selekteren um t as nao-ekeken te markeren.',
'markedaspatrollederror-noautopatrol' => 'Je maggen joew eigen bewarkingen niet op nao-ekeken zetten.',
'markedaspatrollednotify' => 'Disse bewarking op $1 is emarkeerd as nao-ekeken.',
'markedaspatrollederrornotify' => 'Markeren as nao-ekeken is mislokt.',

# Patrol log
'patrol-log-page' => 'Markeerlogboek',
'patrol-log-header' => 'In dit logboek staon de versies die op nao-ekeken ezet bin.',
'log-show-hide-patrol' => 'Markeerlogboek $1',

# Image deletion
'deletedrevision' => 'Vortedaone ouwe versie $1.',
'filedeleteerror-short' => 'Fout bie t vortdoon van bestaand: $1',
'filedeleteerror-long' => 'Der waren fouten bie t vortdoon van t bestaand:

$1',
'filedelete-missing' => 't Bestaand "$1" kan niet vortedaon wörden, umdat t niet besteet.',
'filedelete-old-unregistered' => 'De an-egeven bestaandsversie "$1" steet niet in de databanke.',
'filedelete-current-unregistered' => 't An-egeven bestaand "$1" steet niet in de databanke.',
'filedelete-archive-read-only' => 'De webserver kan niet in de archiefmap "$1" schrieven.',

# Browsing diffs
'previousdiff' => '← veurige wieziging',
'nextdiff' => 'volgende wieziging →',

# Media information
'mediawarning' => "'''Waorschuwing:''' in dit bestaand zit misschien kodering die slicht is veur t systeem.",
'imagemaxsize' => "Maximale aofmetingen van aofbeeldingen:<br />
''(veur op de beschrievingszied)''",
'thumbsize' => 'Grootte van de miniatuuraofbeelding:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|zied|ziejen}}',
'file-info' => 'Bestaandsgrootte: $1, MIME-type: $2',
'file-info-size' => '$1 × $2 beeldpunten, bestaandsgrootte: $3, MIME-type: $4',
'file-info-size-pages' => '$1 × $2 beeldpunten, bestaandsgrootte: $3, MIME-type: $4, $5 {{PLURAL:$5|zied|ziejen}}',
'file-nohires' => 'Gien hogere resolusie beschikbaor.',
'svg-long-desc' => 'SVG-bestaand, uutgangsgrootte $1 × $2 beeldpunten, bestaandsgrootte: $3',
'svg-long-desc-animated' => 'Bewegend SVG-bestaand, uutgangsgrootte $1 × $2 beeldpunten, bestaandsgrootte: $3',
'svg-long-error' => 'Ongeldig SVG-bestaand: $1',
'show-big-image' => 'Volle resolusie',
'show-big-image-preview' => 'Grootte van disse weergave: $1.',
'show-big-image-other' => 'Aandere {{PLURAL:$2|resolusie|resolusies}}: $1.',
'show-big-image-size' => '$1 × $2 beeldpunten',
'file-info-gif-looped' => 'herhaolend',
'file-info-gif-frames' => '$1 {{PLURAL:$1|beeld|beelden}}',
'file-info-png-looped' => 'herhaolend',
'file-info-png-repeat' => '$1 {{PLURAL:$1|keer|keer}} aofespeuld',
'file-info-png-frames' => '$1 {{PLURAL:$1|beeld|beelden}}',
'file-no-thumb-animation' => "'''Opmarking: vanwegen techniese beparkingen, zie'j bie miniaturen de boel niet bewegen.''",
'file-no-thumb-animation-gif' => "'''Opmarking: vanwegen techniese beparkingen, zie'j bie miniaturen van GIF-aofbeeldingen mit n hoge resolusie de boel niet bewegen.''",

# Special:NewFiles
'newimages' => 'Nieje bestaanden',
'imagelisttext' => "Hier volgt n lieste mit '''$1''' {{PLURAL:$1|bestaand|bestaanden}} esorteerd $2.",
'newimages-summary' => 'Op disse spesiale zied staon de bestaanden die der as lest bie-ekeumen bin.',
'newimages-legend' => 'Bestaandsnaam',
'newimages-label' => 'Bestaandsnaam (of deel dervan):',
'showhidebots' => '(Bots $1)',
'noimages' => 'Niks te zien.',
'ilsubmit' => 'Zeuk',
'bydate' => 'op daotum',
'sp-newimages-showfrom' => 'Bekiek nieje bestaanden vanaof $1, $2',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 sekonde|$1 sekonden}}',
'minutes' => '{{PLURAL:$1|$1 minuut|$1 minuten}}',
'hours' => '{{PLURAL:$1|$1 ure|$1 uren}}',
'days' => '{{PLURAL:$1|$1 dag|$1 dagen}}',
'weeks' => '{{PLURAL: $1|één weke|$1 weken}}',
'months' => '{{PLURAL:$1|een maond|$1 maonden}}',
'years' => '{{PLURAL:$1|één jaor|$1 jaor}}',
'ago' => '$1 eleen',
'just-now' => 'onderlest',

# Human-readable timestamps
'hours-ago' => '$1 {{PLURAL:$1|uur}} elejen',
'minutes-ago' => '$1 {{PLURAL:$1|minuut|minuten}} elejen',
'seconds-ago' => '$1 {{PLURAL:$1|sekonde|sekonden}} elejen',
'monday-at' => 'Maondag um $1',
'tuesday-at' => 'Diensdag um $1',
'wednesday-at' => 'Woonsdag um $1',
'thursday-at' => 'Donderdag um $1',
'friday-at' => 'Vriedag um $1',
'saturday-at' => 'Zaoterdag um $1',
'sunday-at' => 'Zundag um $1',
'yesterday-at' => 'Gisteren um $1',

# Bad image list
'bad_image_list' => 'De opmaak is as volgt:

Allinnig regels in n lieste (regels die beginnen mit *) wörden verwarkt.
De eerste verwiezing op n regel mut n verwiezing ween naor n ongewunst bestaand.
Alle volgende verwiezingen die op de zelfde regel staon, wörden behaandeld as uutzundering, zo as ziejen waorop t bestaand in te tekste op-eneumen is.',

# Metadata
'metadata' => 'Metadata',
'metadata-help' => 'In dit bestaand zit metadata mit EXIF-informasie, die deur n fotokamera, inleesapparaot of fotobewarkingsprogramma op-estuurd kan ween.',
'metadata-expand' => 'Bekiek uutebreiden gegevens',
'metadata-collapse' => 'Verbarg uutebreiden gegevens',
'metadata-fields' => 'De aofbeeldingsmetadatavelden in dit bericht staon oek op n aofbeeldingszied as de metadatatabel in-eklapt is.
Aandere velden wörden verbörgen.
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
* gpsaltitude',

# Exif tags
'exif-imagewidth' => 'Wiedte',
'exif-imagelength' => 'Heugte',
'exif-bitspersample' => 'Bits per komponent',
'exif-compression' => 'Kompressiemethode',
'exif-photometricinterpretation' => 'Beeldpuntsamenstelling',
'exif-orientation' => 'Oriëntasie',
'exif-samplesperpixel' => 'Antal compenenten',
'exif-planarconfiguration' => 'Gegevensstructuur',
'exif-ycbcrsubsampling' => 'Subsamplingsverhouwige van Y tot C',
'exif-ycbcrpositioning' => 'Y- en C-posisionering',
'exif-xresolution' => 'Horizontale resolusie',
'exif-yresolution' => 'Verticale resolusie',
'exif-stripoffsets' => 'Lokasie aofbeeldingsgegevens',
'exif-rowsperstrip' => 'Riejen per strip',
'exif-stripbytecounts' => 'Bytes per ekomprimeerden strip',
'exif-jpeginterchangeformat' => 'Aofstaand tot JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bytes van JPEG-gegevens',
'exif-whitepoint' => 'Witpuntchromaticiteit',
'exif-primarychromaticities' => 'Chromasiteit van primaere kleuren',
'exif-ycbcrcoefficients' => 'Transformasiematrixkoëfficiënten veur de kleurruumte',
'exif-referenceblackwhite' => 'Referensieweerden veur zwart/wit',
'exif-datetime' => 'Tiedstip van digitalisasie',
'exif-imagedescription' => 'Aofbeeldingnaam',
'exif-make' => 'Kameramark',
'exif-model' => 'Kameramodel',
'exif-software' => 'Programmatuur die gebruukt wörden',
'exif-artist' => 'Eschreven deur',
'exif-copyright' => 'Auteursrechtenhouwer',
'exif-exifversion' => 'Exif-versie',
'exif-flashpixversion' => 'Ondersteunden Flashpix-versie',
'exif-colorspace' => 'Kleurruumte',
'exif-componentsconfiguration' => 'Betekenisse van elk compenent',
'exif-compressedbitsperpixel' => 'Beeldkompressiemethode',
'exif-pixelydimension' => 'Aofbeeldingsbreedte',
'exif-pixelxdimension' => 'Aofbeeldingsheugte',
'exif-usercomment' => 'Opmarkingen',
'exif-relatedsoundfile' => 'Biebeheurend geluudsbestaand',
'exif-datetimeoriginal' => 'Tiedstip van datagenerasie',
'exif-datetimedigitized' => 'Tiedstip van digitalisasie',
'exif-subsectime' => 'Subseconden tiedstip bestaandswieziging',
'exif-subsectimeoriginal' => 'Subseconden tiedstip datagenerasie',
'exif-subsectimedigitized' => 'Subseconden tiedstip digitalisasie',
'exif-exposuretime' => 'Belochtingstied',
'exif-exposuretime-format' => '$1 sek ($2)',
'exif-fnumber' => 'F-getal',
'exif-exposureprogram' => 'Belochtingsprogramma',
'exif-spectralsensitivity' => 'Spektrale geveuligheid',
'exif-isospeedratings' => 'ISO-weerde.',
'exif-shutterspeedvalue' => 'Slutersnelheid in APEX',
'exif-aperturevalue' => 'Diafragma in APEX',
'exif-brightnessvalue' => 'Helderheid in APEX',
'exif-exposurebiasvalue' => 'Belochtingscompensasie',
'exif-maxaperturevalue' => 'Maximale diafragmaweerde van de lenze',
'exif-subjectdistance' => 'Aofstaand tot onderwarp',
'exif-meteringmode' => 'Methode lochmeting',
'exif-lightsource' => 'Lochbron',
'exif-flash' => 'Flitser',
'exif-focallength' => 'Braandpuntofstand',
'exif-subjectarea' => 'Objektruumte',
'exif-flashenergy' => 'Flitserstarkte',
'exif-focalplanexresolution' => 'X-resolusie van CDD',
'exif-focalplaneyresolution' => 'Y-resolusie van CCD',
'exif-focalplaneresolutionunit' => 'Eenheid CCD-resolusie',
'exif-subjectlocation' => 'Objeklokasie',
'exif-exposureindex' => 'Belochtingsindex',
'exif-sensingmethod' => 'Meetmethode',
'exif-filesource' => 'Bestaandsnaam op de hardeschieve',
'exif-scenetype' => 'Scènetype',
'exif-customrendered' => 'An-epassen beeldbewarking',
'exif-exposuremode' => 'Belochtingsinstelling',
'exif-whitebalance' => 'Witbalans',
'exif-digitalzoomratio' => 'Digitale zoomfactor',
'exif-focallengthin35mmfilm' => 'Braandpuntaofstaand (35mm-equivalent)',
'exif-scenecapturetype' => 'Soort opname',
'exif-gaincontrol' => 'Piekbeheersing',
'exif-contrast' => 'Kontrast',
'exif-saturation' => 'Verzaojiging',
'exif-sharpness' => 'Scharpte',
'exif-devicesettingdescription' => 'Umschrieving apperaotinstellingen',
'exif-subjectdistancerange' => 'Aofstaandskategorie',
'exif-imageuniqueid' => 'Unieke ID-aofbeelding',
'exif-gpsversionid' => 'GPS-versienummer',
'exif-gpslatituderef' => 'Noorder- of zujerbreedte',
'exif-gpslatitude' => 'Breedte',
'exif-gpslongituderef' => 'Ooster- of westerlengte',
'exif-gpslongitude' => 'Lengtegraod',
'exif-gpsaltituderef' => 'Heugtereferensie',
'exif-gpsaltitude' => 'Heugte',
'exif-gpstimestamp' => 'GPS-tied (atoomklokke)',
'exif-gpssatellites' => 'Satellieten die gebruuk bin veur de meting',
'exif-gpsstatus' => 'Ontvangerstaotus',
'exif-gpsmeasuremode' => 'Meetmodus',
'exif-gpsdop' => 'Meetpresisie',
'exif-gpsspeedref' => 'Snelheidseenheid',
'exif-gpsspeed' => 'Snelheid van GPS-ontvanger',
'exif-gpstrackref' => 'Referensie veur bewegingsrichting',
'exif-gpstrack' => 'Bewegingsrichting',
'exif-gpsimgdirectionref' => 'Referensie veur aofbeeldingsrichting',
'exif-gpsimgdirection' => 'Aofbeeldingsrichtige',
'exif-gpsmapdatum' => 'Geodetiese onderzeuksgegevens die gebruukt bin',
'exif-gpsdestlatituderef' => 'Referensie veur breedtegraod tot bestemming',
'exif-gpsdestlatitude' => 'Breedtegraod bestemming',
'exif-gpsdestlongituderef' => 'Referensie veur lengtegraod bestemming',
'exif-gpsdestlongitude' => 'Lengtegraod bestemming',
'exif-gpsdestbearingref' => 'Referensie veur richting naor bestemming',
'exif-gpsdestbearing' => 'Richting naor bestemming',
'exif-gpsdestdistanceref' => 'Referensie veur aofstaand tot bestemming',
'exif-gpsdestdistance' => 'Aofstaand tot bestemming',
'exif-gpsprocessingmethod' => 'Naam van de GPS-verwarkingsmethode',
'exif-gpsareainformation' => 'Naam van t GPS-gebied',
'exif-gpsdatestamp' => 'GPS-daotum',
'exif-gpsdifferential' => 'Differensiële GPS-korreksie',
'exif-jpegfilecomment' => 'Opmarking bie JPEG-bestaand',
'exif-keywords' => 'Trefwoorden',
'exif-worldregioncreated' => 'Regio in de wereld waor de aofbeelding emaakt is',
'exif-countrycreated' => 'Laand waor de aofbeelding emaakt is',
'exif-countrycodecreated' => 'Kode veur t laand waor de aofbeelding emaakt is',
'exif-provinceorstatecreated' => 'Provinsie of staot waor de aofbeelding emaakt is',
'exif-citycreated' => 'Plaotse waor de aofbeelding emaakt is',
'exif-sublocationcreated' => 'Wiek van de plaotse waor de aofbeelding emaakt is',
'exif-worldregiondest' => 'Weeregeven wereldregio',
'exif-countrydest' => 'Weeregeven laand',
'exif-countrycodedest' => 'Kode veur t weeregeven laand',
'exif-provinceorstatedest' => 'Weeregeven provinsie of staot',
'exif-citydest' => 'Weeregeven plaotse',
'exif-sublocationdest' => 'Weeregeven wiek in plaotse',
'exif-objectname' => 'Korte naam',
'exif-specialinstructions' => 'Spesiale instruksies',
'exif-headline' => 'Kopjen',
'exif-credit' => 'Krediet/leverancier',
'exif-source' => 'Bron',
'exif-editstatus' => 'Bewarkingsstaotus van de aofbeelding',
'exif-urgency' => 'Urgensie',
'exif-fixtureidentifier' => 'Groepsnaam',
'exif-locationdest' => 'Weeregeven lokasie',
'exif-locationdestcode' => 'Kode veur de weeregeven lokasie',
'exif-objectcycle' => 'Tied van de dag waor de media veur bedoeld is',
'exif-contact' => 'Kontaktgegevens',
'exif-writer' => 'Schriever',
'exif-languagecode' => 'Taal',
'exif-iimversion' => 'IIM-versie',
'exif-iimcategory' => 'Kategorie',
'exif-iimsupplementalcategory' => 'Anvullende kategorieën',
'exif-datetimeexpires' => 'Niet te gebruken nao',
'exif-datetimereleased' => 'Uutebröcht op',
'exif-originaltransmissionref' => 'Oorspronkelike taaklokasiekode',
'exif-identifier' => 'ID',
'exif-lens' => 'Lenze die gebruukt wörden',
'exif-serialnumber' => 'Serienummer van de camera',
'exif-cameraownername' => 'Eigenaar van camera',
'exif-label' => 'Etiket',
'exif-datetimemetadata' => 'Daotum waorop de metadata veur t lest bie-ewörken bin',
'exif-nickname' => 'Informele naam van de aofbeelding',
'exif-rating' => 'Werdering (op n schaole van 5)',
'exif-rightscertificate' => 'Rechtenbeheercertificaot',
'exif-copyrighted' => 'Auteursrechtstaotus',
'exif-copyrightowner' => 'Auteursrechtenhouwer',
'exif-usageterms' => 'Gebruuksveurweerden',
'exif-webstatement' => 'Internetauteursrechverklaoring',
'exif-originaldocumentid' => 'Uniek ID van t originele dokument',
'exif-licenseurl' => 'Webadres veur auteursrechlisensie',
'exif-morepermissionsurl' => 'Alternatieve lisensiegegevens',
'exif-attributionurl' => 'Gebruuk de volgende verwiezing bie hergebruuk van dit wark',
'exif-preferredattributionname' => 'Gebruuk de volgende makersvermelding bie hergebruuk van dit wark',
'exif-pngfilecomment' => 'Opmarking bie PNG-bestaand',
'exif-disclaimer' => 'Veurbehoud',
'exif-contentwarning' => 'Waorschuwing over inhoud',
'exif-giffilecomment' => 'Opmarking bie GIF-bestaand',
'exif-intellectualgenre' => 'Soort onderwarp',
'exif-subjectnewscode' => 'Onderwarpkode',
'exif-scenecode' => 'IPTC-scènekode',
'exif-event' => 'Aofebeelden gebeurtenisse',
'exif-organisationinimage' => 'Aofebeelden organisasie',
'exif-personinimage' => 'Aofebeeld persoon',
'exif-originalimageheight' => 'Heugte van de aofbeelding veur biesniejen',
'exif-originalimagewidth' => 'Breedte van de aofbeelding veur biesniejen',

# Exif attributes
'exif-compression-1' => 'Niet ekomprimeerd',
'exif-compression-2' => 'CCITT-groep 3 1-dimensionale an-epasten "Huffman run length"-kodering',
'exif-compression-3' => 'CCITT-groep 3 faxcodering',
'exif-compression-4' => 'CCITT-groep 4 faxcodering',

'exif-copyrighted-true' => 'Auteursrechtelik bescharmp',
'exif-copyrighted-false' => 'Auteursrechtenstaotus niet vasteleegd',

'exif-unknowndate' => 'Onbekende daotum',

'exif-orientation-1' => 'Normaal',
'exif-orientation-2' => 'horizontaal espegeld',
'exif-orientation-3' => '180° edreid',
'exif-orientation-4' => 'verticaal edreid',
'exif-orientation-5' => 'espegeld um as linksboven-rechtsonder',
'exif-orientation-6' => '90° linksummedreid',
'exif-orientation-7' => '90° linksummedreid',
'exif-orientation-8' => '90° rechtsummedreid',

'exif-planarconfiguration-1' => 'Grof gegevensformaot',
'exif-planarconfiguration-2' => 'planar gegevensformaot',

'exif-colorspace-65535' => 'Niet-ekalibreerd',

'exif-componentsconfiguration-0' => 'besteet niet',

'exif-exposureprogram-0' => 'Niet umschreven',
'exif-exposureprogram-1' => 'Haandmaotig',
'exif-exposureprogram-2' => 'Normaal',
'exif-exposureprogram-3' => 'Diafragmaprioriteit',
'exif-exposureprogram-4' => 'Sluterprioriteit',
'exif-exposureprogram-5' => 'Kreatief (veurkeur veur grote scharptediepte)',
'exif-exposureprogram-6' => 'Aksie (veurkeur veur hoge slutersnelheid)',
'exif-exposureprogram-7' => 'Portret (detailopname mit onscharpe achtergrond)',
'exif-exposureprogram-8' => 'Laandschap (scharpe achtergrond)',

'exif-subjectdistance-value' => '$1 m',

'exif-meteringmode-0' => 'Onbekend',
'exif-meteringmode-1' => 'Gemiddeld',
'exif-meteringmode-2' => 'Gemiddeld, naodrok op midden',
'exif-meteringmode-3' => 'Spot',
'exif-meteringmode-4' => 'MultiSpot',
'exif-meteringmode-5' => 'Multi-segment (patrone)',
'exif-meteringmode-6' => 'Deelmeting',
'exif-meteringmode-255' => 'Aanders',

'exif-lightsource-0' => 'Onbekend',
'exif-lightsource-1' => 'Daglocht',
'exif-lightsource-2' => 'Tl-locht',
'exif-lightsource-3' => 'Tungsten (lamplocht)',
'exif-lightsource-4' => 'Flitser',
'exif-lightsource-9' => 'Mooi weer',
'exif-lightsource-10' => 'Bewolk',
'exif-lightsource-11' => 'Schaoduw',
'exif-lightsource-12' => 'Fluorescerend daglocht (D 5700 – 7100K)',
'exif-lightsource-13' => 'Witfluorescerend daglocht (N 4600 – 5400K)',
'exif-lightsource-14' => 'Koel witfluorescerend (W 3900 – 4500K)',
'exif-lightsource-15' => 'Witfluorescerend (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Standardlocht A',
'exif-lightsource-18' => 'Standardlocht B',
'exif-lightsource-19' => 'Standardlocht C',
'exif-lightsource-24' => 'ISO-studiokunstlocht',
'exif-lightsource-255' => 'Aanders',

# Flash modes
'exif-flash-fired-0' => 'Flits is niet aofegaon',
'exif-flash-fired-1' => 'Mit flitser',
'exif-flash-return-0' => 'flits stuurt gien gegevens',
'exif-flash-return-2' => 'gien weerkaotsing van de flits vastesteld',
'exif-flash-return-3' => 'weerkaotsing van de flits vastesteld',
'exif-flash-mode-1' => 'verplicht mit flitser',
'exif-flash-mode-2' => 'flitser verplicht onderdrokt',
'exif-flash-mode-3' => 'automatiese modus',
'exif-flash-function-1' => 'Gien flitserfunksie',
'exif-flash-redeye-1' => 'rooie ogen-filter',

'exif-focalplaneresolutionunit-2' => 'duum',

'exif-sensingmethod-1' => 'Niet vastesteld',
'exif-sensingmethod-2' => 'Eén-chip-kleursensor',
'exif-sensingmethod-3' => 'Twee-chips-kleursensor',
'exif-sensingmethod-4' => 'Dree-chips-kleurensensor',
'exif-sensingmethod-5' => 'Kleurvolgende gebiedssensor',
'exif-sensingmethod-7' => 'Dreeliendige sensor',
'exif-sensingmethod-8' => 'Kleurvolgende gebiedssensor',

'exif-filesource-3' => 'Digitale fotokamera',

'exif-scenetype-1' => 'n Drekt efotografeerden aofbeelding',

'exif-customrendered-0' => 'Normaal',
'exif-customrendered-1' => 'An-epas',

'exif-exposuremode-0' => 'Automaties',
'exif-exposuremode-1' => 'Haandmaotig',
'exif-exposuremode-2' => 'Belochtingsrie',

'exif-whitebalance-0' => 'Automaties',
'exif-whitebalance-1' => 'Haandmaotig',

'exif-scenecapturetype-0' => 'standard',
'exif-scenecapturetype-1' => 'laandschap',
'exif-scenecapturetype-2' => 'pertret',
'exif-scenecapturetype-3' => 'Nachtscène',

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
'exif-sharpness-1' => 'Zachte',
'exif-sharpness-2' => 'Hard',

'exif-subjectdistancerange-0' => 'Onbekend',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Kortbie',
'exif-subjectdistancerange-3' => 'Veeraof',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Noorderbreedte',
'exif-gpslatitude-s' => 'Zujerbreedte',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Oosterlengte',
'exif-gpslongitude-w' => 'Westerlengte',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|meter|meter}} boven de zeespegel',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|meter|meter}} onder de zeespegel',

'exif-gpsstatus-a' => 'Bezig mit meten',
'exif-gpsstatus-v' => 'Meetinteroperebiliteit',

'exif-gpsmeasuremode-2' => '2-dimensionale meting',
'exif-gpsmeasuremode-3' => '3-dimensionale meting',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometer per uur',
'exif-gpsspeed-m' => 'Miel per ure',
'exif-gpsspeed-n' => 'Kneupen',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilometer',
'exif-gpsdestdistance-m' => 'Miel',
'exif-gpsdestdistance-n' => 'Zeemielen',

'exif-gpsdop-excellent' => 'Uutstekend ($1)',
'exif-gpsdop-good' => 'Goed ($1)',
'exif-gpsdop-moderate' => 'Gemiddeld ($1)',
'exif-gpsdop-fair' => 'Redelik ($1)',
'exif-gpsdop-poor' => 'Slicht ($1)',

'exif-objectcycle-a' => 'Allinnig smarnens',
'exif-objectcycle-p' => 'Allinnig savends',
'exif-objectcycle-b' => "'s Mannen én 's avens",

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Waore richting',
'exif-gpsdirection-m' => 'Magnetiese richting',

'exif-ycbcrpositioning-1' => 'In t midden',
'exif-ycbcrpositioning-2' => 'E-kositueerd',

'exif-dc-contributor' => 'Luui die bie-edreugen hebben',
'exif-dc-coverage' => 'Ruumtelike of temporele reikwiedte van media',
'exif-dc-date' => 'Daotum(s)',
'exif-dc-publisher' => 'Uutgever',
'exif-dc-relation' => 'Verwaante media',
'exif-dc-rights' => 'Rechten',
'exif-dc-source' => 'Bronmedia',
'exif-dc-type' => 'Soort media',

'exif-rating-rejected' => 'Aofewezen',

'exif-isospeedratings-overflow' => 'Groter as 65535',

'exif-iimcategory-ace' => 'Kunst, kultuur en vermaak',
'exif-iimcategory-clj' => 'Misdaod en recht',
'exif-iimcategory-dis' => 'Rampen en ongevallen',
'exif-iimcategory-fin' => 'Ekonomie en bedriefsleven',
'exif-iimcategory-edu' => 'Onderwies',
'exif-iimcategory-evn' => 'Milieu',
'exif-iimcategory-hth' => 'Gezondheid',
'exif-iimcategory-hum' => 'Meenselike interesse',
'exif-iimcategory-lab' => 'Arbeid',
'exif-iimcategory-lif' => 'Levensstiel en vrieje tied',
'exif-iimcategory-pol' => 'Politiek',
'exif-iimcategory-rel' => 'Godsdienst en overtuging',
'exif-iimcategory-sci' => 'Wetenschap en technologie',
'exif-iimcategory-soi' => 'Sosiale kwesties',
'exif-iimcategory-spo' => 'Sport',
'exif-iimcategory-war' => 'Oorlog, armoe en onrust',
'exif-iimcategory-wea' => 'Weer',

'exif-urgency-normal' => 'Normaal ($1)',
'exif-urgency-low' => 'Leeg ($1)',
'exif-urgency-high' => 'Hoog ($1)',
'exif-urgency-other' => 'Deur gebruker in-estelde prioriteit ($1)',

# External editor support
'edit-externally' => 'Wiezig dit bestaand mit n extern programma',
'edit-externally-help' => '(Zie de [//www.mediawiki.org/wiki/Manual:External_editors installasie-instruksies] veur meer informasie)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'alles',
'namespacesall' => 'alles',
'monthsall' => 'alles',
'limitall' => 'alles',

# Email address confirmation
'confirmemail' => 'Bevestig netpostadres',
'confirmemail_noemail' => 'Je hebben gien geldig netpostadres in-evoerd in joew [[Special:Preferences|veurkeuren]].',
'confirmemail_text' => "Bie disse wiki mu'j je netpostadres bevestigen veurda'j de berichtopsies gebruken kunnen. Klik op de onderstaonde knoppe um n bevestigingsbericht te ontvangen. In dit bericht zit n kode mit n verwiezing; um je netpostadres te bevestigen mu'j disse verwiezing openen.",
'confirmemail_pending' => "Der is al n bevestigingskode op-estuurd; a'j net n gebrukersnaam an-emaakt hebben, wacht dan eerst n paor minuten tot da'j dit bericht ontvöngen hebben veurda'j n nieje kode anvragen.",
'confirmemail_send' => 'Stuur n bevestigingskode',
'confirmemail_sent' => 'Bevestigingsbericht verstuurd.',
'confirmemail_oncreate' => "n Bevestigingskode is naor joew netpostadres verstuurd. Disse kode is niet neudig um an te melden, mer je mutten t wel bevestigen veurda'j de netpostmeugelikheen van disse wiki gebruken kunnen.",
'confirmemail_sendfailed' => '{{SITENAME}} kon joe gien bevestigingskode toesturen.
Kiek nao of der gien ongeldige tekens in t netpostadres zitten.

Fout bie t versturen: $1',
'confirmemail_invalid' => 'Ongeldige bevestigingskode. De kode kan verlopen ween.',
'confirmemail_needlogin' => 'Je mutten $1 um joew netpostadres te bevestigen.',
'confirmemail_success' => 'Joew netpostadres is bevestigd. Je kunnen noen [[Special:UserLogin|anmelden]] en {{SITENAME}} gebruken.',
'confirmemail_loggedin' => 'Joew netpostadres is noen bevestig.',
'confirmemail_error' => 'Der is iets fout egaon bie t opslaon van joew bevestiging.',
'confirmemail_subject' => 'Bevestiging netpostadres veur {{SITENAME}}',
'confirmemail_body' => 'Ene mit IP-adres $1, warschienlik jie zelf, hef zien eigen mit dit netpostadres eregistreerd as de gebruker "$2" op {{SITENAME}}.

Klik op de volgende verwiezing um te bevestigen da\'jie disse gebruker bin en um de netpostmeugelikheen op {{SITENAME}} te aktiveren:

$3

A\'j joe eigen *niet* an-emeld hebben, klik dan niet op disse verwiezing um de bevestiging van joew netpostadres aof te breken:

$5

De bevestigingskode zal verlopen op $4.',
'confirmemail_body_changed' => 'Ene mit IP-adres $1, warschienlik jie zelf,
hef zien eigen mit dit netpostadres eregistreerd as de gebruker "$2" op {{SITENAME}}.

Klik op de volgende verwiezing um te bevestigen da\'jie disse gebruker bin en um de netpostmeugelikheen op {{SITENAME}} te aktiveren:

$3

A\'j joe eigen *niet* an-emeld hebben, klik dan niet op disse verwiezing
um de bevestiging van joew netpostadres aof te breken:

$5

De bevestigingskode zal verlopen op $4.',
'confirmemail_body_set' => 'Ene mit IP-adres $1, warschienlik jie zelf,
hef zien eigen mit dit netpostadres eregistreerd as de gebruker "$2" op {{SITENAME}}.

Klik op de volgende verwiezing um te bevestigen da\'jie disse gebruker bin en um de netpostmeugelikheen op {{SITENAME}} te aktiveren:

$3

A\'j joe eigen *niet* an-emeld hebben, klik dan niet op disse verwiezing
um de bevestiging van joew netpostadres of te zegen:

$5

De bevestigingskode zal verlopen op $4.',
'confirmemail_invalidated' => 'De netpostbevestiging is aofebreuken',
'invalidateemail' => 'Netpostbevestiging ofbreken',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki-intergrasie is uutezet]',
'scarytranscludefailed' => '[De mal $1 kon niet op-ehaold wörden]',
'scarytranscludefailed-httpstatus' => '[De mal $1 kon niet op-ehaold wörden: HTTP $2]',
'scarytranscludetoolong' => '[URL is te lang]',

# Delete conflict
'deletedwhileediting' => "'''Waorschuwing''': disse zied is vortedaon terwiel jie t an t bewarken waren!",
'confirmrecreate' => "Gebruker [[User:$1|$1]] ([[User talk:$1|Overleg]]) hef disse zied vortedaon naoda'j  begunnen bin mit joew wieziging, mit opgave van de volgende reden: ''$2''. Bevestig da'j t artikel herschrieven willen.",
'confirmrecreate-noreason' => "Gebruker [[User:$1|$1]] ([[User talk:$1|overleg]]) hef disse zied vortedaon naoda'j  begunnen bin mit joew wieziging. Bevestig da'j t artikel herschrieven willen.",
'recreate' => 'Herschrieven',

# action=purge
'confirm_purge_button' => 'Bevestig',
'confirm-purge-top' => "Klik op 'bevestig' um t tussengeheugen van disse zied te legen.",
'confirm-purge-bottom' => "t Leegmaken van t tussengeheugen zörgt derveur da'j de leste versie van n zied zien.",

# action=watch/unwatch
'confirm-watch-button' => 'Oké',
'confirm-watch-top' => 'Disse zied op joew volglieste zetten?',
'confirm-unwatch-button' => 'Oké',
'confirm-unwatch-top' => 'Disse zied van joew volglieste aofhaolen?',

# Multipage image navigation
'imgmultipageprev' => '&larr; veurige',
'imgmultipagenext' => 'volgende &rarr;',
'imgmultigo' => 'Oké',
'imgmultigoto' => 'Gao naor de zied $1',

# Table pager
'ascending_abbrev' => 'opl.',
'descending_abbrev' => 'aofl.',
'table_pager_next' => 'Volgende',
'table_pager_prev' => 'Veurige',
'table_pager_first' => 'Eerste zied',
'table_pager_last' => 'Leste zied',
'table_pager_limit' => 'Laot $1 resultaoten per zied zien',
'table_pager_limit_label' => 'Zaken per zied:',
'table_pager_limit_submit' => 'Zeuk',
'table_pager_empty' => 'Gien resultaoten',

# Auto-summaries
'autosumm-blank' => 'Zied leegemaakt',
'autosumm-replace' => "Tekste vervöngen deur '$1'",
'autoredircomment' => 'deurverwiezing naor [[$1]]',
'autosumm-new' => "Nieje zied: '$1'",

# Size units
'size-kilobytes' => '$1 kB',

# Live preview
'livepreview-loading' => 'An t laojen…',
'livepreview-ready' => 'An t laojen… ree!',
'livepreview-failed' => 'Rechtstreeks naokieken is niet meugelik!
Kiek de zied op de normale maniere nao.',
'livepreview-error' => 'Verbiending niet meugelik: $1 "$2"
Kiek de zied op de normale maniere nao.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Wiezigingen die niejer bin as $1 {{PLURAL:$1|seconde|seconden}} staon misschien nog niet in de lieste.',
'lag-warn-high' => 'De databanke is aorig zwaor belast. Wiezigingen die niejer bin as $1 {{PLURAL:$1|sekonde|sekonden}} staon daorumme misschien nog niet in de lieste.',

# Watchlist editor
'watchlistedit-numitems' => 'Der {{PLURAL:$1|steet 1 zied|staon $1 ziejen}} op joew volglieste, zonder overlegziejen.',
'watchlistedit-noitems' => 'Joew volglieste is leeg.',
'watchlistedit-normal-title' => 'Volglieste bewarken',
'watchlistedit-normal-legend' => 'Disse ziejen van mien volglieste aofhaolen.',
'watchlistedit-normal-explain' => 'Ziejen die op joew volglieste staon, zie\'j hieronder.
Um n zied van joew volglieste aof te haolen mu\'j t vakjen dernaost anklikken, en klik dan op "{{int:Watchlistedit-normal-submit}}".
Je kunnen oek [[Special:EditWatchlist/raw|de roewe lieste bewarken]].',
'watchlistedit-normal-submit' => 'Ziejen deraof haolen',
'watchlistedit-normal-done' => 'Der {{PLURAL:$1|is 1 zied|bin $1 ziejen}} vortedaon uut joew volglieste:',
'watchlistedit-raw-title' => 'Roewe volglieste bewarken',
'watchlistedit-raw-legend' => 'Roewe volglieste bewarken',
'watchlistedit-raw-explain' => 'Ziejen die op joew volglieste staon, zie\'j hieronder. Je kunnen de lieste bewarken deur ziejen deruut vort te haolen en derbie te te zetten.
Eén zied per regel.
A\'j klaor bin, klik dan op "{{int:Watchlistedit-raw-submit}}".
Je kunnen oek [[Special:EditWatchlist|t standardbewarkingsscharm gebruken]].',
'watchlistedit-raw-titles' => 'Titels:',
'watchlistedit-raw-submit' => 'Volglieste biewarken',
'watchlistedit-raw-done' => 'Joew volglieste is bie-ewörken.',
'watchlistedit-raw-added' => 'Der {{PLURAL:$1|is 1 zied|bin $1 ziejen}} bie edaon:',
'watchlistedit-raw-removed' => 'Der {{PLURAL:$1|is 1 zied|bin $1 ziejen}} vortedaon:',

# Watchlist editing tools
'watchlisttools-view' => 'Wiezigingen bekieken',
'watchlisttools-edit' => 'Volglieste bekieken en bewarken',
'watchlisttools-raw' => 'Roewe volglieste bewarken',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|overleg]])',

# Core parser functions
'unknown_extension_tag' => 'Onbekende tag "$1"',
'duplicate-defaultsort' => 'Waorschuwing: de standardsortering "$2" krig veurrang veur de sortering "$1".',

# Special:Version
'version' => 'Versie',
'version-extensions' => 'Uutbreidingen die installeerd bin',
'version-specialpages' => 'Spesiale ziejen',
'version-parserhooks' => 'Parserhoeken',
'version-variables' => 'Variabels',
'version-antispam' => 'Veurkoemen van ongewunste bewarkingen',
'version-skins' => 'Vormgevingen',
'version-api' => 'Api',
'version-other' => 'Overige',
'version-mediahandlers' => 'Mediaverwarkers',
'version-hooks' => 'Hoeken',
'version-parser-extensiontags' => 'Parseruutbreidingsplaotjes',
'version-parser-function-hooks' => 'Parserfunksiehoeken',
'version-hook-name' => 'Hooknaam',
'version-hook-subscribedby' => 'In-eschreven deur',
'version-version' => '(Versie $1)',
'version-license' => 'Lisensie',
'version-poweredby-credits' => "Disse wiki wörden an-estuurd deur '''[//www.mediawiki.org/ MediaWiki]''', auteursrecht © 2001-$1 $2.",
'version-poweredby-others' => 'aanderen',
'version-poweredby-translators' => 'vertalers van translatewiki.net',
'version-credits-summary' => 'Wulen erkennen grege de volgende personen veur der biedrage an [[Special:Version|MediaWiki]].',
'version-license-info' => 'MediaWiki is vrieje programmatuur; je kunnen MediaWiki verspreien en/of anpassen onder de veurweerden van de GNU General Public License zo as epubliceerd deur de Free Software Foundation; of versie 2 van de Lisensie, of - naor eigen wuns - n laotere versie.

MediaWiki wörden verspreid in de hoop dat t nuttig is, mer ZONDER ENIGE GARANSIE; zonder zelfs de daoronder begrepen garansie van VERKOOPBAORHEID of GESCHIKTHEID VEUR ENIG DOEL IN T BIEZUNDER. Zie de GNU General Public License veur meer informasie.

Samen mit dit programma heur je n [{{SERVER}}{{SCRIPTPATH}}/COPYING kopie van de GNU General Public License] te hebben ekregen; as dat niet zo is, schrief dan naor de Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA of [//www.gnu.org/licenses/old-licenses/gpl-2.0.html lees de lisensie op t internet].',
'version-software' => 'Programmatuur die installeerd is',
'version-software-product' => 'Produkt',
'version-software-version' => 'Versie',
'version-entrypoints' => 'Webadressen veur ingangen',
'version-entrypoints-header-entrypoint' => 'Ingang',
'version-entrypoints-header-url' => 'Webadres',

# Special:Redirect
'redirect' => 'Deurverwiezen op bestaandsnaam, gebrukersnummer of versienummer',
'redirect-legend' => 'Deurverwiezen naor n bestaand of zied',
'redirect-summary' => 'Disse spesiale zied verwis deur naor n bestaand (as n bestaandsnaam op-egeven wörden), n zied (as n versienummer op-egeven wörden) of n gebrukerszied (as t gebrukersnummer op-egeven wörden).',
'redirect-submit' => 'Zeuk',
'redirect-lookup' => 'Opzeuken:',
'redirect-value' => 'Weerde:',
'redirect-user' => 'Gebrukersnummer',
'redirect-revision' => 'Ziedversie',
'redirect-file' => 'Bestaandsnaam',
'redirect-not-exists' => 'Weerde niet evunnen',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Dubbele bestaanden zeuken',
'fileduplicatesearch-summary' => 'Dubbele bestaanden zeuken op baosis van de hashweerde.',
'fileduplicatesearch-legend' => 'Dubbele bestaanden zeuken',
'fileduplicatesearch-filename' => 'Bestaandsnaam:',
'fileduplicatesearch-submit' => 'Zeuken',
'fileduplicatesearch-info' => '$1 × $2 beeldpunten<br />Bestaandsgrootte: $3<br />MIME-type: $4',
'fileduplicatesearch-result-1' => 'Der bin gien bestaanden die liek alleens bin as "$1".',
'fileduplicatesearch-result-n' => 'Der {{PLURAL:$2|is één bestaand|bin $2 bestaanden}} die liek alleens bin as "$1".',
'fileduplicatesearch-noresults' => 'Der is gien bestaand mit de naam "$1" evunnen.',

# Special:SpecialPages
'specialpages' => 'Spesiale ziejen',
'specialpages-note' => '----
* Normale spesiale ziejen.
* <strong class="mw-specialpagerestricted">Beparkt toegankelike spesiale ziejen.</strong>
* <span class="mw-specialpagecached">Spesiale ziejen mit allinnig gegevens uut t tussengeheugen (kunnen verouwerd ween).</span>',
'specialpages-group-maintenance' => 'Onderhoudsliesten',
'specialpages-group-other' => 'Aandere spesiale ziejen',
'specialpages-group-login' => 'Anmelden / inschrieven',
'specialpages-group-changes' => 'Leste wiezigingen en logboeken',
'specialpages-group-media' => 'Media-overzichten en nieje bestaanden',
'specialpages-group-users' => 'Gebrukers en rechten',
'specialpages-group-highuse' => 'Veulgebruukten ziejen',
'specialpages-group-pages' => 'Liesten mit ziejen',
'specialpages-group-pagetools' => 'Ziedhulpmiddels',
'specialpages-group-wiki' => 'Gegevens en hulpmiddels',
'specialpages-group-redirects' => 'Deurverwiezende spesiale ziejen',
'specialpages-group-spam' => 'Hulpmiddels tegen ongewunste bewarkingen',

# Special:BlankPage
'blankpage' => 'Lege zied',
'intentionallyblankpage' => 'Disse zied is bewust leeg eleuten.',

# External image whitelist
'external_image_whitelist' => ' #Laot disse regel onveraanderd<pre>
#Hieronder kunnen delen van reguliere uutdrokkingen (t deel tussen //) an-egeven wörden.
#t Wörden mit de webadressen van aofbeeldingen uut bronnen van butenof vergeleken
#n Positief vergeliekingsresultaot zörgt derveur dat de aofbeelding weeregeven wörden, aanders wörden de aofbeelding allinnig as verwiezing weeregeven
#Regels die mit n # beginnen, wörden as kommentaar behaandeld
#De regels in de lieste bin niet heufdlettergeveulig

#Delen van reguliere uutdrokkingen boven disse regel plaotsen. Laot disse regel onveraanderd</pre>',

# Special:Tags
'tags' => 'Geldige wiezigingsetiketten',
'tag-filter' => '[[Special:Tags|Etiketfilter]]:',
'tag-filter-submit' => 'Filtreren',
'tag-list-wrapper' => '([[Special:Tags|Etiket{{PLURAL:$1||ten}}]]: $2)',
'tags-title' => 'Etiket',
'tags-intro' => 'Op disse zied staon de etiketten waormee de programmatuur elke bewarking kan markeren, en de betekenisse dervan.',
'tags-tag' => 'Etiketnaam',
'tags-display-header' => 'Weergave in wiezigingsliesten',
'tags-description-header' => 'Beschrieving van de betekenisse',
'tags-active-header' => 'Aktief?',
'tags-hitcount-header' => 'Bewarkingen mit etiket',
'tags-active-yes' => 'Ja',
'tags-active-no' => 'Nee',
'tags-edit' => 'bewarking',
'tags-hitcount' => '$1 {{PLURAL:$1|wieziging|wiezigingen}}',

# Special:ComparePages
'comparepages' => 'Ziejen vergelieken',
'compare-selector' => 'Ziedversies vergelieken',
'compare-page1' => 'Zied 1',
'compare-page2' => 'Zied 2',
'compare-rev1' => 'Versie 1',
'compare-rev2' => 'Versie 2',
'compare-submit' => 'Vergelieken',
'compare-invalid-title' => "De titel die'j op-egeven hebben, is ongeldig.",
'compare-title-not-exists' => "De titel die'j op-egeven hebben, besteet niet.",
'compare-revision-not-exists' => "De versie die'j op-egeven hebben, besteet niet.",

# Database error messages
'dberr-header' => 'Disse wiki hef wat kuren',
'dberr-problems' => 't Spiet ons, mer disse webstee hef op t moment wat techniese problemen.',
'dberr-again' => 'Wach n paor minuten en probeer t daornao opniej.',
'dberr-info' => '(Kan gien verbiending maken mit de databankeserver: $1)',
'dberr-info-hidden' => '(Kan gien verbiending maken mit de databankserver)',
'dberr-usegoogle' => "Misschien ku'j ondertussen zeuken via Google.",
'dberr-outofdate' => 'Let op: indexen die zee hebben van onze ziejen bin misschien niet aktueel.',
'dberr-cachederror' => 'Disse zied is n kopie uut t tussengeheugen en is misschien niet aktueel.',

# HTML forms
'htmlform-invalid-input' => 'Der bin problemen mit n paor in-egeven weerden',
'htmlform-select-badoption' => 'De in-egeven weerde is ongeldig.',
'htmlform-int-invalid' => 'De in-egeven weerde is gien geheel getal.',
'htmlform-float-invalid' => "De weerde die'j op-egeven hebben is gien getal.",
'htmlform-int-toolow' => 'De in-egeven weerde lig onder de minimumweerde van $1',
'htmlform-int-toohigh' => 'De in-egeven weerde lig boven de maximumweerde van $1',
'htmlform-required' => 'Disse weerde is verplicht',
'htmlform-submit' => 'Opslaon',
'htmlform-reset' => 'Wiezigingen ongedaonmaken',
'htmlform-selectorother-other' => 'Aanders',
'htmlform-no' => 'Nee',
'htmlform-yes' => 'Ja',
'htmlform-chosen-placeholder' => 'Kies n opsie',

# SQLite database support
'sqlite-has-fts' => 'Versie $1 mit ondersteuning veur "full-text" zeuken',
'sqlite-no-fts' => 'Versie $1 zonder ondersteuning veur "full-text" zeuken',

# New logging system
'logentry-delete-delete' => '$1 hef de zied $3 {{GENDER:$2|vortedaon}}',
'logentry-delete-restore' => '$1 hef de zied $3 {{GENDER:$2|weerummezet}}',
'logentry-delete-event' => '$1 hef de zichtbaorheid van {{PLURAL:$5|n logboekregel|$5 logboekregels}} van $3 {{GENDER:$2|ewiezigd}}: $4',
'logentry-delete-revision' => '$1 hef de zichtbaorheid van {{PLURAL:$5|een versie|$5 versies}} van de zied $3 {{GENDER:$2|ewiezigd}}: $4',
'logentry-delete-event-legacy' => '$1 hef de zichtbaorheid van logboekregels van $3 {{GENDER:$2|ewiezigd}}',
'logentry-delete-revision-legacy' => '$1 hef de zichtbaorheid van versies van de zied $3 {{GENDER:$2|ewiezigd}}.',
'logentry-suppress-delete' => '$1 hef de zied $3 {{GENDER:$2|onderdrokt}}',
'logentry-suppress-event' => '$1 hef de zichtbaorheid van {{PLURAL:$5|een logboekregel|$5 logboekregels}} van $3 sluuksem {{GENDER:$2|ewiezigd}}: $4',
'logentry-suppress-revision' => '$1 hef de zichtbaorheid van {{PLURAL:$5|een versie|$5 versies}} van de zied $3 sluuksem {{GENDER:$2|ewiezigd}}: $4',
'logentry-suppress-event-legacy' => '$1 hef de zichtbaorheid van logboekregels van $3 sluuksem {{GENDER:$2|ewiezigd}}',
'logentry-suppress-revision-legacy' => '$1 hef de zichtbaorheid van versies van de zied $3 sluuksem {{GENDER:$2|ewiezigd}}.',
'revdelete-content-hid' => 'inhoud verbörgen',
'revdelete-summary-hid' => 'bewarkingssamenvatting verbörgen',
'revdelete-uname-hid' => 'gebrukersnaam verbörgen',
'revdelete-content-unhid' => 'inhoud zichtbaor emaakt',
'revdelete-summary-unhid' => 'bewarkingssamenvatting zichtbaor emaakt',
'revdelete-uname-unhid' => 'gebrukersnaam zichtbaor emaakt',
'revdelete-restricted' => 'hef beparkingen an beheerders op-eleg',
'revdelete-unrestricted' => 'hef beparkingen veur beheerders deraof ehaold',
'logentry-move-move' => '$1 hef de zied $3 {{GENDER:$2|herneumd}} naor $4',
'logentry-move-move-noredirect' => '$1 hef de zied $3 {{GENDER:$2|herneumd}} naor $4 zonder n deurverwiezing achter te laoten',
'logentry-move-move_redir' => '$1 hef de zied $3 {{GENDER:$2|herneumd}} naor $4 over n deurverwiezing heer',
'logentry-move-move_redir-noredirect' => '$1 hef de zied $3 {{GENDER:$2|herneumd}} naor $4 over n deurverwiezing heer zonder n deurverwiezing achter te laoten',
'logentry-patrol-patrol' => '$1 hef versie $4 van de zied $3 op {{GENDER:$2|nao-ekeken}} ezet',
'logentry-patrol-patrol-auto' => '$1 hef versie $4 van de zied $3 automaties op {{GENDER:$2|nao-ekeken}} ezet',
'logentry-newusers-newusers' => 'Gebruker $1 is {{GENDER:$2|an-emaakt}}',
'logentry-newusers-create' => 'Gebruker $1 is {{GENDER:$2|an-emaakt}}',
'logentry-newusers-create2' => 'Gebruker $3 is {{GENDER:$2|an-emaakt}} an-emaakt deur $1',
'logentry-newusers-byemail' => 'Gebruker $3 {{GENDER:$2|is}} an-emaakt deur $1 en t wachtwoord is per netpost verstuurd',
'logentry-newusers-autocreate' => 'De gebruker $1 is automaties {{GENDER:$2|an-emaakt}}',
'logentry-rights-rights' => '$1 {{GENDER:$2|hef}} groepslidmaotschap veur $3 ewiezigd van $4 naor $5',
'logentry-rights-rights-legacy' => '$1 {{GENDER:$2|hef}} t groepslidmaotschap ewiezigd veur $3',
'logentry-rights-autopromote' => '$1 {{GENDER:$2|is}} automaties bevorderd van $4 tot $5',
'rightsnone' => '(gien)',

# Feedback
'feedback-bugornote' => 'A\'j zovere bin um n technies probleem nauwkeurig te beschrieven, [$1 meld dan n programmafout].
Aanders ku\'j oek t eenvoudige formulier hieronder gebruken. Joew kommentaar zal op de zied "[$3 $2]" ezet wörden, samen mit joew gebrukersnaam en de webkieker die\'j gebruken.',
'feedback-subject' => 'Onderwarp:',
'feedback-message' => 'Bericht:',
'feedback-cancel' => 'Aofbreken',
'feedback-submit' => 'Kommentaar geven',
'feedback-adding' => 'Joew kommentaar wörden op de zied ezet...',
'feedback-error1' => 'Fout: onbekend resultaot uut de API',
'feedback-error2' => 'Fout: de bewarking is mislokt',
'feedback-error3' => 'Fout: gien reaksie van de API',
'feedback-thanks' => 'Bedankt! Joew kommentaar is op de zied "[$2 $1]" ezet.',
'feedback-close' => 'Ree',
'feedback-bugcheck' => 'Mooi! Kiek nao of t niet al één van de [$1 bekende problemen] is.',
'feedback-bugnew' => 'Ik heb t nao-ekeken. Meld n nieje programmafout',

# Search suggestions
'searchsuggest-search' => 'Zeuken / zuken / zuiken',
'searchsuggest-containing' => 'bevat...',

# API errors
'api-error-badaccess-groups' => 'Je maggen gien bestaanden in disse wiki inlaojen.',
'api-error-badtoken' => 'Interne fout: t token klopt niet.',
'api-error-copyuploaddisabled' => 'Bestaanden opsturen via n webadres is uutezet op disse server.',
'api-error-duplicate' => 'Der {{PLURAL:$1|steet al [$2 n bestaand]|staon al [$2 bestaanden]}} mit de zelfde inhoud in de wiki.',
'api-error-duplicate-archive' => 'Der {{PLURAL:$1|was al [$2 n aander bestaand]|waren al [$2 $1 aandere bestaanden]}}  op de webstee mit de zelfde inhoud, mer {{PLURAL:$1|dat is|die bin}} vortedaon.',
'api-error-duplicate-archive-popup-title' => '{{PLURAL:$1|Duplikaotbestaand dat al vortedaon is|Duplikaotbestaanden die al vortedaon bin}}',
'api-error-duplicate-popup-title' => 'Zelfde {{PLURAL:$1|bestaand|bestaanden}}',
'api-error-empty-file' => "t Bestaand da'j op-estuurd hebben is leeg.",
'api-error-emptypage' => 'Je maggen gien lege nieje ziejen anmaken.',
'api-error-fetchfileerror' => 'Interne fout: der is iets verkeerd egaon mit t ophaolen van t bestaand.',
'api-error-fileexists-forbidden' => 'Der besteet al n bestaand mit de naam "$1" die niet overschreven kan wörden.',
'api-error-fileexists-shared-forbidden' => 'Der besteet al n bestaand mit de naam "$1" in de edeelden bestaandsarchief dat niet overschreven kan wörden.',
'api-error-file-too-large' => "t Bestaand da'j op-estuurd hebben is te groot.",
'api-error-filename-tooshort' => 'De bestaandsnaam is te kort.',
'api-error-filetype-banned' => 'Dit bestaandstype is niet toe-estaon.',
'api-error-filetype-banned-type' => '{{PLURAL:$4|t Bestaandstype $1|De bestaandstypes $1}} wörden niet toe-eleuten. {{PLURAL:$3|t Toe-estaone bestaandstype is|De toe-estaone bestaandstypen bin}} $2.',
'api-error-filetype-missing' => 't Bestaand hef gien extensie.',
'api-error-hookaborted' => "De wieziging die'j proberen deur te voeren is aofebreuken deur n extra uutbreiding.",
'api-error-http' => 'Interne fout: der kon gien verbiending emaakt wörden mit de server.',
'api-error-illegal-filename' => 'Disse bestaandsnaam mag niet.',
'api-error-internal-error' => 'Interne fout: der is iets verkeerd egaon tiejens t verwarken van joew op-estuurden bestaand deur de wiki.',
'api-error-invalid-file-key' => 'Interne fout: t bestaand stung niet in de tiedelike opslag.',
'api-error-missingparam' => 'Interne fout: niet alle parameters bin in t verzeuk mee-eleverd.',
'api-error-missingresult' => 'Interne fout: kon niet vaststellen of t kopiëren wel wol lokken.',
'api-error-mustbeloggedin' => 'Je mutten an-emeld ween um bestaanden te kunnen opsturen.',
'api-error-mustbeposted' => 'Der zit n fout in de programmatuur. Der wörden gien gebruukemaakt van de juuste HTTP-methode.',
'api-error-noimageinfo' => 't Opsturen van t bestaand is aoferond, mer de server hef gien gegevens over t bestaand egeven.',
'api-error-nomodule' => 'Interne fout: der is gien inlaojmodule in-esteld.',
'api-error-ok-but-empty' => 'Interne fout: de server hef gien gegevens weerestuurd.',
'api-error-overwrite' => 'Je maggen gien bestaond bestaand overschrieven.',
'api-error-stashfailed' => 'Interne fout: de server kon t tiedelike bestaand niet opslaon.',
'api-error-publishfailed' => 'Interne fout: de server kon t tiejelike bestaand niet publiseren.',
'api-error-timeout' => 'De server hef niet binnen de verwachte tied antwoord egeven.',
'api-error-unclassified' => 'Der is n onbekende fout op-etrejen',
'api-error-unknown-code' => 'Interne fout: "$1"',
'api-error-unknown-error' => 'Interne fout: der is iets verkeerd egaon tiejens t opsturen van joew bestaand.',
'api-error-unknown-warning' => 'Onbekende waorschuwing: $1',
'api-error-unknownerror' => 'Onbekende fout: "$1"',
'api-error-uploaddisabled' => 'Je kunnen gien bestaanden opsturen in deze wiki.',
'api-error-verification-error' => 'Dit bestaand is meugelik beschaodigd of hef n onjuuste extensie.',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|sekonde|sekonden}}',
'duration-minutes' => '$1 {{PLURAL:$1|minuut|minuten}}',
'duration-hours' => '$1 {{PLURAL:$1|uur|uren}}',
'duration-days' => '$1 {{PLURAL:$1|dag|dagen}}',
'duration-weeks' => '$1 {{PLURAL:$1|weke|weken}}',
'duration-years' => '$1 {{PLURAL:$1|jaor|jaoren}}',
'duration-decades' => '$1 {{PLURAL:$1|desennium|desennia}}',
'duration-centuries' => '$1 {{PLURAL:$1|eeuw|eeuwen}}',
'duration-millennia' => '$1 {{PLURAL:$1|millennium|millennia}}',

# Image rotation
'rotate-comment' => 'Aofbeelding is $1 {{PLURAL:$1|graod|graojen}} mit de klokke mee edreid',

# Limit report
'limitreport-title' => 'Parser-profieldata:',
'limitreport-cputime' => 'Tiedsgebruuk van de prosessor',
'limitreport-cputime-value' => '$1 {{PLURAL:$1|sekonde|sekonden}}',
'limitreport-walltime' => 'Reëel tiedsgebruuk',
'limitreport-walltime-value' => '$1 {{PLURAL:$1|sekonde|sekonden}}',
'limitreport-ppvisitednodes' => 'Antal verbiendingsknopen bezöcht tiejens de veurverwarking:',
'limitreport-ppgeneratednodes' => 'Antal verbiedingsknopen an-emaakt tiejens de veurverwarking:',
'limitreport-postexpandincludesize' => 'Inklusiegrootte nao uutbreien',
'limitreport-postexpandincludesize-value' => '$1/$2 {{PLURAL:$2|byte|bytes}}',
'limitreport-templateargumentsize' => 'Grootte van malparameters',
'limitreport-templateargumentsize-value' => '$1/$2 {{PLURAL:$2|byte|bytes}}',
'limitreport-expansiondepth' => 'Hoogste uutbreidingsdiepte',
'limitreport-expensivefunctioncount' => 'Antal kostbaore parserfunksies',

);
