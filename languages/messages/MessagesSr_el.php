<?php
/** Serbian (Latin script) (srpski (latinica)‎)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author FriedrickMILBarbarossa
 * @author Kaganer
 * @author Liangent
 * @author Meno25
 * @author Michaello
 * @author Rancher
 * @author Red Baron
 * @author Reedy
 * @author Slaven Kosanovic
 * @author Жељко Тодоровић
 * @author Михајло Анђелковић
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Medija',
	NS_SPECIAL          => 'Posebno',
	NS_TALK             => 'Razgovor',
	NS_USER             => 'Korisnik',
	NS_USER_TALK        => 'Razgovor_sa_korisnikom',
	NS_PROJECT_TALK     => 'Razgovor_o_$1',
	NS_FILE             => 'Slika',
	NS_FILE_TALK        => 'Razgovor_o_slici',
	NS_MEDIAWIKI        => 'MedijaViki',
	NS_MEDIAWIKI_TALK   => 'Razgovor_o_MedijaVikiju',
	NS_TEMPLATE         => 'Šablon',
	NS_TEMPLATE_TALK    => 'Razgovor_o_šablonu',
	NS_HELP             => 'Pomoć',
	NS_HELP_TALK        => 'Razgovor_o_pomoći',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Razgovor_o_kategoriji',
);

# Aliases to cyrillic namespaces
$namespaceAliases = array(
	"Медија"                  => NS_MEDIA,
	"Посебно"                 => NS_SPECIAL,
	"Разговор"                => NS_TALK,
	"Корисник"                => NS_USER,
	"Разговор_са_корисником"  => NS_USER_TALK,
	"Разговор_о_$1"           => NS_PROJECT_TALK,
	"Слика"                   => NS_FILE,
	"Разговор_о_слици"        => NS_FILE_TALK,
	"МедијаВики"              => NS_MEDIAWIKI,
	"Разговор_о_МедијаВикију" => NS_MEDIAWIKI_TALK,
	'Шаблон'                  => NS_TEMPLATE,
	'Разговор_о_шаблону'      => NS_TEMPLATE_TALK,
	'Помоћ'                   => NS_HELP,
	'Разговор_о_помоћи'       => NS_HELP_TALK,
	'Категорија'              => NS_CATEGORY,
	'Разговор_о_категорији'   => NS_CATEGORY_TALK,
);

$extraUserToggles = array(
	'nolangconversion',
);

$datePreferenceMigrationMap = array(
	'default',
	'hh:mm d. month y.',
	'hh:mm d month y',
	'hh:mm dd.mm.yyyy',
	'hh:mm d.m.yyyy',
	'hh:mm d. mon y.',
	'hh:mm d mon y',
	'h:mm d. month y.',
	'h:mm d month y',
	'h:mm dd.mm.yyyy',
	'h:mm d.m.yyyy',
	'h:mm d. mon y.',
	'h:mm d mon y',
);

$datePreferences = array(
	'default',
	'hh:mm d. month y.',
	'hh:mm d month y',
	'hh:mm dd.mm.yyyy',
	'hh:mm d.m.yyyy',
	'hh:mm d. mon y.',
	'hh:mm d mon y',
	'h:mm d. month y.',
	'h:mm d month y',
	'h:mm dd.mm.yyyy',
	'h:mm d.m.yyyy',
	'h:mm d. mon y.',
	'h:mm d mon y',
);

$defaultDateFormat = 'hh:mm d. month y.';

$dateFormats = array(
	/*
	'Није битно',
	'06:12, 5. јануар 2001.',
	'06:12, 5 јануар 2001',
	'06:12, 05.01.2001.',
	'06:12, 5.1.2001.',
	'06:12, 5. јан 2001.',
	'06:12, 5 јан 2001',
	'6:12, 5. јануар 2001.',
	'6:12, 5 јануар 2001',
	'6:12, 05.01.2001.',
	'6:12, 5.1.2001.',
	'6:12, 5. јан 2001.',
	'6:12, 5 јан 2001',
	 */

	'hh:mm d. month y. time'    => 'H:i',
	'hh:mm d month y time'      => 'H:i',
	'hh:mm dd.mm.yyyy time'     => 'H:i',
	'hh:mm d.m.yyyy time'       => 'H:i',
	'hh:mm d. mon y. time'      => 'H:i',
	'hh:mm d mon y time'        => 'H:i',
	'h:mm d. month y. time'     => 'G:i',
	'h:mm d month y time'       => 'G:i',
	'h:mm dd.mm.yyyy time'      => 'G:i',
	'h:mm d.m.yyyy time'        => 'G:i',
	'h:mm d. mon y. time'       => 'G:i',
	'h:mm d mon y time'         => 'G:i',

	'hh:mm d. month y. date'    => 'j. F Y.',
	'hh:mm d month y date'      => 'j F Y',
	'hh:mm dd.mm.yyyy date'     => 'd.m.Y',
	'hh:mm d.m.yyyy date'       => 'j.n.Y',
	'hh:mm d. mon y. date'      => 'j. M Y.',
	'hh:mm d mon y date'        => 'j M Y',
	'h:mm d. month y. date'     => 'j. F Y.',
	'h:mm d month y date'       => 'j F Y',
	'h:mm dd.mm.yyyy date'      => 'd.m.Y',
	'h:mm d.m.yyyy date'        => 'j.n.Y',
	'h:mm d. mon y. date'       => 'j. M Y.',
	'h:mm d mon y date'         => 'j M Y',

	'hh:mm d. month y. both'    => 'H:i, j. F Y.',
	'hh:mm d month y both'      => 'H:i, j F Y',
	'hh:mm dd.mm.yyyy both'     => 'H:i, d.m.Y',
	'hh:mm d.m.yyyy both'       => 'H:i, j.n.Y',
	'hh:mm d. mon y. both'      => 'H:i, j. M Y.',
	'hh:mm d mon y both'        => 'H:i, j M Y',
	'h:mm d. month y. both'     => 'G:i, j. F Y.',
	'h:mm d month y both'       => 'G:i, j F Y',
	'h:mm dd.mm.yyyy both'      => 'G:i, d.m.Y',
	'h:mm d.m.yyyy both'        => 'G:i, j.n.Y',
	'h:mm d. mon y. both'       => 'G:i, j. M Y.',
	'h:mm d mon y both'         => 'G:i, j M Y',
);


/* NOT USED IN STABLE VERSION */
$magicWords = array(
	'redirect'                => array( '0', '#Preusmeri', '#preusmeri', '#PREUSMERI', '#Preusmjeri', '#preusmjeri', '#PREUSMJERI', '#redirect', '#REDIRECT' ),
	'notoc'                   => array( '0', '__BEZSADRŽAJA__', '__BEZ_SADRŽAJA__', '__NOTOC__' ),
	'nogallery'               => array( '0', '__BEZGALERIJE__', '__BEZ_GALERIJE__', '__NOGALLERY__' ),
	'forcetoc'                => array( '0', '__FORSIRANISADRŽAJ__', '__FORSIRANI_SADRŽAJ__', '__PRIMORANISADRŽAJ__', '__PRIMORANI_SADRŽAJ__', '__FORCETOC__' ),
	'toc'                     => array( '0', '__SADRŽAJ__', '__TOC__' ),
	'noeditsection'           => array( '0', '__BEZIZMENA__', '__BEZ_IZMENA__', '__BEZIZMJENA__', '__BEZ_IZMJENA__', '__NOEDITSECTION__' ),
	'noheader'                => array( '0', '__BEZZAGLAVLJA__', '__BEZ_ZAGLAVLJA__', '__NOHEADER__' ),
	'currentmonth'            => array( '1', 'TRENUTNIMESEC', 'TRENUTNI_MESEC', 'TEKUĆIMESEC', 'TEKUĆI_MESEC', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'           => array( '1', 'TRENUTNIMESEC1', 'TRENUTNI_MESEC1', 'TEKUĆIMESEC1', 'TEKUĆI_MESEC1', 'CURRENTMONTH1' ),
	'currentmonthname'        => array( '1', 'TRENUTNIMESECIME', 'IMETEKUĆEGMESECA', 'IME_TEKUĆEG_MESECA', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'     => array( '1', 'TRENUTNIMESECGEN', 'TEKUĆIMESECGEN', 'TEKUĆI_MESEC_GEN', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'      => array( '1', 'TRENUTNIMESECSKR', 'TEKUĆIMESECSKR', 'TEKUĆI_MESEC_SKR', 'CURRENTMONTHABBREV' ),
	'currentday'              => array( '1', 'TRENUTNIDAN', 'TEKUĆIDAN', 'TEKUĆI_DAN', 'CURRENTDAY' ),
	'currentday2'             => array( '1', 'TEKUĆIDAN2', 'TEKUĆI_DAN_2', 'CURRENTDAY2' ),
	'currentdayname'          => array( '1', 'TRENUTNIDANIME', 'IMETEKUĆEGDANA', 'IME_TEKUĆEG_DANA', 'CURRENTDAYNAME' ),
	'currentyear'             => array( '1', 'TRENUTNAGODINA', 'TEKUĆAGODINA', 'TEKUĆA_GODINA', 'CURRENTYEAR' ),
	'currenttime'             => array( '1', 'TRENUTNOVREME', 'TEKUĆEVREME', 'TEKUĆE_VREME', 'CURRENTTIME' ),
	'currenthour'             => array( '1', 'TEKUĆISAT', 'TEKUĆI_SAT', 'CURRENTHOUR' ),
	'localmonth'              => array( '1', 'LOKALNIMESEC', 'LOKALNI_MESEC', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'             => array( '1', 'LOKALNIMESEC2', 'LOKALNI_MESEC2', 'LOCALMONTH1' ),
	'localmonthname'          => array( '1', 'IMELOKALNOGMESECA', 'IME_LOKALNOG_MESECA', 'LOCALMONTHNAME' ),
	'localmonthnamegen'       => array( '1', 'LOKALNIMESECGEN', 'LOKALNI_MESEC_GEN', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'        => array( '1', 'LOKALNIMESECSKR', 'LOKALNI_MESEC_SKR', 'LOCALMONTHABBREV' ),
	'localday'                => array( '1', 'LOKALNIDAN', 'LOKALNI_DAN', 'LOCALDAY' ),
	'localday2'               => array( '1', 'LOKALNIDAN2', 'LOKALNI_DAN2', 'LOCALDAY2' ),
	'localdayname'            => array( '1', 'IMELOKALNOGDANA', 'IME_LOKALNOG_DANA', 'LOCALDAYNAME' ),
	'localyear'               => array( '1', 'LOKALNAGODINA', 'LOKALNA_GODINA', 'LOCALYEAR' ),
	'localtime'               => array( '1', 'LOKALNOVREME', 'LOKALNO_VREME', 'LOCALTIME' ),
	'localhour'               => array( '1', 'LOKALNISAT', 'LOKALNI_SAT', 'LOCALHOUR' ),
	'numberofpages'           => array( '1', 'BROJSTRANICA', 'BROJ_STRANICA', 'NUMBEROFPAGES' ),
	'numberofarticles'        => array( '1', 'BROJČLANAKA', 'BROJ_ČLANAKA', 'NUMBEROFARTICLES' ),
	'numberoffiles'           => array( '1', 'BROJDATOTEKA', 'BROJ_DATOTEKA', 'BROJFAJLOVA', 'BROJ_FAJLOVA', 'NUMBEROFFILES' ),
	'numberofusers'           => array( '1', 'BROJKORISNIKA', 'BROJ_KORISNIKA', 'NUMBEROFUSERS' ),
	'numberofactiveusers'     => array( '1', 'BROJAKTIVNIHKORISNIKA', 'BROJ_AKTIVNIH_KORISNIKA', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'           => array( '1', 'BROJIZMENA', 'BROJ_IZMENA', 'NUMBEROFEDITS' ),
	'numberofviews'           => array( '1', 'BROJPREGLEDA', 'BROJ_PREGLEDA', 'NUMBEROFVIEWS' ),
	'pagename'                => array( '1', 'IMESTRANICE', 'IME_STRANICE', 'STRANICA', 'PAGENAME' ),
	'pagenamee'               => array( '1', 'IMENASTRANICA', 'IMENA_STRANICA', 'STRANICE', 'PAGENAMEE' ),
	'namespace'               => array( '1', 'IMENSKIPROSTOR', 'IMENSKI_PROSTOR', 'NAMESPACE' ),
	'namespacee'              => array( '1', 'IMENSKIPROSTORI', 'IMENSKI_PROSTORI', 'NAMESPACEE' ),
	'talkspace'               => array( '1', 'RAZGOVOR', 'TALKSPACE' ),
	'talkspacee'              => array( '1', 'RAZGOVORI', 'TALKSPACEE' ),
	'subjectspace'            => array( '1', 'IMENSKIPROSTORČLANKA', 'IMENSKI_PROSTOR_ČLANKA', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'           => array( '1', 'IMENSKIPROSTORČLANAKA', 'IMENSKI_PROSTOR_ČLANAKA', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'            => array( '1', 'PUNOIMESTRANICE', 'PUNOIMESTRANE', 'PUNO_IME_STRANICE', 'PUNO_IME_STRANE', 'FULLPAGENAME' ),
	'fullpagenamee'           => array( '1', 'PUNAIMENASTRANICA', 'PUNAIMENASTRANA', 'PUNA_IMENA_STRANICA', 'PUNA_IMENA_STRANA', 'FULLPAGENAMEE' ),
	'subpagename'             => array( '1', 'IMEPODSTRANICE', 'IMEPODSTRANE', 'IME_PODSTRANICE', 'IME_PODSTRANE', 'SUBPAGENAME' ),
	'subpagenamee'            => array( '1', 'IMENAPODSTRANICA', 'IMENAPODSTRANA', 'IMENA_PODSTRANICA', 'IMENA_PODSTRANA', 'SUBPAGENAMEE' ),
	'basepagename'            => array( '1', 'IMEOSNOVE', 'IME_OSNOVE', 'BASEPAGENAME' ),
	'basepagenamee'           => array( '1', 'IMENAOSNOVA', 'IMENA_OSNOVA', 'BASEPAGENAMEE' ),
	'talkpagename'            => array( '1', 'IMERAZGOVORA', 'IME_RAZGOVORA', 'TALKPAGENAME' ),
	'talkpagenamee'           => array( '1', 'IMENARAZGOVORA', 'IMENA_RAZGOVORA', 'TALKPAGENAMEE' ),
	'subjectpagename'         => array( '1', 'IMEČLANKA', 'IME_ČLANKA', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'        => array( '1', 'IMENAČLANAKA', 'IMENA_ČLANAKA', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                     => array( '0', 'POR:', 'MSG:' ),
	'subst'                   => array( '0', 'ZAMENI:', 'ZAMENA:', 'SUBST:' ),
	'safesubst'               => array( '0', 'BEZBEDNAZAMENA', 'BEZBEDNA_ZAMENA', 'SAFESUBST:' ),
	'msgnw'                   => array( '0', 'NVPOR:', 'MSGNW:' ),
	'img_thumbnail'           => array( '1', 'mini', 'umanjeno', 'thumbnail', 'thumb' ),
	'img_manualthumb'         => array( '1', 'mini=$1', 'umanjeno=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'               => array( '1', 'desno', 'd', 'right' ),
	'img_left'                => array( '1', 'levo', 'l', 'left' ),
	'img_none'                => array( '1', 'bez', 'n', 'none' ),
	'img_width'               => array( '1', '$1piskel', '$1p', '$1px' ),
	'img_center'              => array( '1', 'centar', 'c', 'center', 'centre' ),
	'img_framed'              => array( '1', 'okvir', 'ram', 'framed', 'enframed', 'frame' ),
	'img_frameless'           => array( '1', 'bezokvira', 'bez_okvira', 'bezrama', 'bez_rama', 'frameless' ),
	'img_page'                => array( '1', 'stranica=$1', 'strana=$1', 'stranica_$1', 'strana_$1', 'page=$1', 'page $1' ),
	'img_upright'             => array( '1', 'uspravno', 'uspravno=$1', 'uspravno_$1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'              => array( '1', 'ivica', 'border' ),
	'img_baseline'            => array( '1', 'osnova', 'baseline' ),
	'img_sub'                 => array( '1', 'pod', 'sub' ),
	'img_top'                 => array( '1', 'vrh', 'top' ),
	'img_text_top'            => array( '1', 'vrhteksta', 'vrh_teksta', 'text-top' ),
	'img_middle'              => array( '1', 'sredina', 'middle' ),
	'img_bottom'              => array( '1', 'dno', 'bottom' ),
	'img_text_bottom'         => array( '1', 'sredinateksta', 'sredina_teksta', 'text-bottom' ),
	'img_link'                => array( '1', 'veza=$1', 'link=$1' ),
	'sitename'                => array( '1', 'IMESAJTA', 'SITENAME' ),
	'ns'                      => array( '0', 'IP:', 'NS:' ),
	'localurl'                => array( '0', 'LOKALNAADRESA:', 'LOKALNA_ADRESA:', 'LOCALURL:' ),
	'localurle'               => array( '0', 'LOKALNEADRESE:', 'LOKALNE_ADRESE:', 'LOCALURLE:' ),
	'articlepath'             => array( '0', 'PUTANJAČLANKA', 'PUTANJA_ČLANKA', 'ARTICLEPATH' ),
	'servername'              => array( '0', 'IMESERVERA', 'IME_SERVERA', 'SERVERNAME' ),
	'scriptpath'              => array( '0', 'SKRIPTA', 'SKRIPT', 'SCRIPTPATH' ),
	'stylepath'               => array( '0', 'PUTANJASTILA', 'PUTANJA_STILA', 'STYLEPATH' ),
	'grammar'                 => array( '0', 'GRAMATIKA:', 'GRAMMAR:' ),
	'gender'                  => array( '0', 'ROD:', 'LICE:', 'GENDER:' ),
	'notitleconvert'          => array( '0', '__BEZKN__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'        => array( '0', '__BEZCC__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'             => array( '1', 'TRENUTNANEDELJA', 'TRENUTNA_NEDELJA', 'TEKUĆANEDELJA', 'TEKUĆA_NEDELJA', 'CURRENTWEEK' ),
	'currentdow'              => array( '1', 'TRENUTNIDOV', 'TEKUĆIDUN', 'CURRENTDOW' ),
	'localweek'               => array( '1', 'LOKALNANEDELJA', 'LOKALNA_NEDELJA', 'LOCALWEEK' ),
	'localdow'                => array( '1', 'LOKALNIDUN', 'LOCALDOW' ),
	'revisionid'              => array( '1', 'IDREVIZIJE', 'ID_REVIZIJE', 'IB_IZMENE', 'REVISIONID' ),
	'revisionday'             => array( '1', 'DANIZMENE', 'DAN_IZMENE', 'REVISIONDAY' ),
	'revisionday2'            => array( '1', 'DANIZMENE2', 'DAN_IZMENE2', 'REVISIONDAY2' ),
	'revisionmonth'           => array( '1', 'MESECIZMENE', 'MESEC_IZMENE', 'REVISIONMONTH' ),
	'revisionmonth1'          => array( '1', 'MESECIZMENE1', 'MESEC_IZMENE1', 'REVISIONMONTH1' ),
	'revisionyear'            => array( '1', 'GODINAIZMENE', 'GODINA_IZMENE', 'REVISIONYEAR' ),
	'revisiontimestamp'       => array( '1', 'VREMEIZMENE', 'VREME_IZMENE', 'REVISIONTIMESTAMP' ),
	'revisionuser'            => array( '1', 'KORISNIKIZMENE', 'KORISNIK_IZMENE', 'REVISIONUSER' ),
	'plural'                  => array( '0', 'MNOŽINA:', 'PLURAL:' ),
	'fullurl'                 => array( '0', 'PUNURL:', 'CELAADRESA', 'CELA_ADRESA', 'FULLURL:' ),
	'fullurle'                => array( '0', 'PUNURLE:', 'CELEADRESE', 'CELE_ADRESE', 'FULLURLE:' ),
	'lcfirst'                 => array( '0', 'LCPRVI:', 'LCFIRST:' ),
	'ucfirst'                 => array( '0', 'UCPRVI:', 'UCFIRST:' ),
	'raw'                     => array( '0', 'ČIST:', 'RAW:' ),
	'displaytitle'            => array( '1', 'NAZIVPRIKAZA', 'NAZIV_PRIKAZA', 'DISPLAYTITLE' ),
	'newsectionlink'          => array( '1', '__NOVAVEZAODELJKA__', '__NOVA_VEZA_ODELJKA__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'        => array( '1', '__BEZNOVEVEZEODELJKA__', '__BEZ_NOVE_VEZE_ODELJKA__', '__NONEWSECTIONLINK__' ),
	'currentversion'          => array( '1', 'TEKUĆEIZDANJE', 'TEKUĆE_IZDANJE', 'CURRENTVERSION' ),
	'urlencode'               => array( '0', 'KODIRANJEADRESE', 'KODIRANJE_ADRESE', 'URLENCODE:' ),
	'anchorencode'            => array( '0', 'KODIRANJEVEZE', 'KODIRANJE_VEZE', 'ANCHORENCODE' ),
	'currenttimestamp'        => array( '1', 'TEKUĆIOTISAKVREMENA', 'TEKUĆI_OTISAK_VREMENA', 'CURRENTTIMESTAMP' ),
	'localtimestamp'          => array( '1', 'OTISAKVREMENA', 'OTISAK_VREMENA', 'LOCALTIMESTAMP' ),
	'directionmark'           => array( '1', 'SMEROZNAKE', 'SMER	_OZNAKE', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                => array( '0', '#JEZIK:', '#LANGUAGE:' ),
	'contentlanguage'         => array( '1', 'JEZIKSADRŽAJA', 'JEZIK_SADRŽAJA', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'        => array( '1', 'STRANICAUIMENSKOMPROSTORU', 'STRANAUIMENSKOMPROSTORU', 'STRANICA_U_IMENSKOM_PROSTORU', 'STRANA_U_IMENSKOM_PROSTORU', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'          => array( '1', 'BROJADMINA', 'BROJ_ADMINA', 'NUMBEROFADMINS' ),
	'padleft'                 => array( '0', 'PORAVNAJULEVO', 'PORAVNAJ_ULEVO', 'PADLEFT' ),
	'padright'                => array( '0', 'PORAVNAJUDESNO', 'PORAVNAJ_UDESNO', 'PADRIGHT' ),
	'special'                 => array( '0', 'posebno', 'special' ),
	'filepath'                => array( '0', 'PUTANJADATOTEKE', 'PUTANJA_DATOTEKE', 'FILEPATH:' ),
	'tag'                     => array( '0', 'oznaka', 'tag' ),
	'hiddencat'               => array( '1', '__SAKRIVENAKAT__', '__HIDDENCAT__' ),
	'pagesincategory'         => array( '1', 'STRANICAUKATEGORIJI', 'STRANAUKATEGORIJI', 'STRANICA_U_KATEGORIJI', 'STRANA_U_KATEGORIJI', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                => array( '1', 'VELIČINASTRANICE', 'VELIČINASTRANE', 'VELIČINA_STRANICE', 'VELIČINA_STRANE', 'PAGESIZE' ),
	'index'                   => array( '1', '__INDEKS__', '__INDEX__' ),
	'noindex'                 => array( '1', '__BEZINDEKSA__', '__BEZ_INDEKSA__', '__NOINDEX__' ),
	'numberingroup'           => array( '1', 'BROJUGRUPI', 'BROJ_U_GRUPI', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'          => array( '1', '__STATIČKOPREUSMERENJE__', 'STATIČKO_PREUSMERENJE', '__STATICREDIRECT__' ),
	'protectionlevel'         => array( '1', 'NIVOZAŠTITE', 'NIVO_ZAŠTITE', 'PROTECTIONLEVEL' ),
	'formatdate'              => array( '0', 'formatdatuma', 'format_datuma', 'formatdate', 'dateformat' ),
	'url_path'                => array( '0', 'PUTANJA', 'PATH' ),
	'url_wiki'                => array( '0', 'VIKI', 'WIKI' ),
	'url_query'               => array( '0', 'REDOSLED', 'QUERY' ),
	'defaultsort_noerror'     => array( '0', 'bez_greške', 'noerror' ),
	'defaultsort_noreplace'   => array( '0', 'bez_zamene', 'noreplace' ),
);

$separatorTransformTable = array( ',' => '.', '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Podvlačenje veza:',
'tog-highlightbroken'         => 'Istakni neispravne veze <a href="" class="new">ovako</a> (alternativno: <a href="" class="internal">ovako</a>)',
'tog-justify'                 => 'Poravnaj pasuse',
'tog-hideminor'               => 'Sakrij manje izmene u spisku skorašnjih izmena',
'tog-hidepatrolled'           => 'Sakrij pregledane izmene u spisku skorašnjih izmena',
'tog-newpageshidepatrolled'   => 'Sakrij pregledane stranice sa spiska novih stranica',
'tog-extendwatchlist'         => 'Proširi spisak nadgledanja za prikaz svih izmena, ne samo skorašnjih',
'tog-usenewrc'                => 'Promene u grupi po stranici u spisku skorašnjih izmena i nadgledanih stranica (zahteva javaskript)',
'tog-numberheadings'          => 'Samostalno numeriši podnaslove',
'tog-showtoolbar'             => 'Traka s alatkama za uređivanje (javaskript)',
'tog-editondblclick'          => 'Uređivanje stranica dvostrukim klikom (javaskript)',
'tog-editsection'             => 'Veze za uređivanje pojedinačnih odeljaka',
'tog-editsectiononrightclick' => 'Uređivanje odeljaka desnim klikom na njihove naslove (javaskript)',
'tog-showtoc'                 => 'Prikaži sadržaj stranica koje imaju više od tri podnaslova',
'tog-rememberpassword'        => 'Zapamti me na ovom pregledaču (najduže $1 {{PLURAL:$1|dan|dana|dana}})',
'tog-watchcreations'          => 'Dodaj stranice koje napravim i datoteke koje pošaljem u spisak nadgledanja',
'tog-watchdefault'            => 'Dodaj stranice i datoteke koje izmenim u spisak nadgledanja',
'tog-watchmoves'              => 'Dodaj stranice i datoteke koje premestim u spisak nadgledanja',
'tog-watchdeletion'           => 'Dodaj stranice i datoteke koje obrišem u spisak nadgledanja',
'tog-minordefault'            => 'Označavaj sve izmene kao manje',
'tog-previewontop'            => 'Prikaži pregled pre okvira za uređivanje',
'tog-previewonfirst'          => 'Prikaži pregled na prvoj izmeni',
'tog-nocache'                 => 'Onemogući privremeno memorisanje stranica',
'tog-enotifwatchlistpages'    => 'Pošalji mi e-poruku kada se promeni stranica ili datoteka koju nadgledam',
'tog-enotifusertalkpages'     => 'Pošalji mi e-poruku kada se promeni moja stranica za razgovor',
'tog-enotifminoredits'        => 'Pošalji mi e-poruku i za manje izmene u stranicama i datotekama',
'tog-enotifrevealaddr'        => 'Otkrij moju e-adresu u porukama obaveštenja',
'tog-shownumberswatching'     => 'Prikaži broj korisnika koji nadgledaju',
'tog-oldsig'                  => 'Tekući potpis:',
'tog-fancysig'                => 'Smatraj potpis kao vikitekst (bez samopovezivanja)',
'tog-externaleditor'          => 'Uvek koristi spoljni uređivač (samo za napredne — potrebne su posebne postavke na računaru)',
'tog-externaldiff'            => 'Uvek koristi spoljni program za upoređivanje (samo za napredne — potrebne su posebne postavke na računaru)',
'tog-showjumplinks'           => 'Omogući pomoćne veze „Idi na“',
'tog-uselivepreview'          => 'Koristi trenutan pregled (javaskript, probna mogućnost)',
'tog-forceeditsummary'        => 'Opomeni me pri unosu praznog opisa',
'tog-watchlisthideown'        => 'Sakrij moje izmene sa spiska nadgledanja',
'tog-watchlisthidebots'       => 'Sakrij izmene botova sa spiska nadgledanja',
'tog-watchlisthideminor'      => 'Sakrij manje izmene sa spiska nadgledanja',
'tog-watchlisthideliu'        => 'Sakrij izmene prijavljenih korisnika sa spiska nadgledanja',
'tog-watchlisthideanons'      => 'Sakrij izmene anonimnih korisnika sa spiska nadgledanja',
'tog-watchlisthidepatrolled'  => 'Sakrij pregledane izmene sa spiska nadgledanja',
'tog-nolangconversion'        => 'Onemogući pretvaranje pisama',
'tog-ccmeonemails'            => 'Pošalji mi primerke e-poruka koje pošaljem drugim korisnicima',
'tog-diffonly'                => 'Ne prikazuj sadržaj stranice ispod razlika',
'tog-showhiddencats'          => 'Prikaži skrivene kategorije',
'tog-noconvertlink'           => 'Onemogući pretvaranje naslova veza',
'tog-norollbackdiff'          => 'Izostavi razliku nakon izvršenog vraćanja',

'underline-always'  => 'uvek podvlači',
'underline-never'   => 'nikad ne podvlači',
'underline-default' => 'prema temi ili pregledaču',

# Font style option in Special:Preferences
'editfont-style'     => 'Izgled fonta u uređivačkom okviru:',
'editfont-default'   => 'po postavkama pregledača',
'editfont-monospace' => 'srazmerno širok font',
'editfont-sansserif' => 'beserifni font',
'editfont-serif'     => 'serifni font',

# Dates
'sunday'        => 'nedelja',
'monday'        => 'ponedeljak',
'tuesday'       => 'utorak',
'wednesday'     => 'sreda',
'thursday'      => 'četvrtak',
'friday'        => 'petak',
'saturday'      => 'subota',
'sun'           => 'ned',
'mon'           => 'pon',
'tue'           => 'uto',
'wed'           => 'sre',
'thu'           => 'čet',
'fri'           => 'pet',
'sat'           => 'sub',
'january'       => 'januar',
'february'      => 'februar',
'march'         => 'mart',
'april'         => 'april',
'may_long'      => 'maj',
'june'          => 'jun',
'july'          => 'jul',
'august'        => 'avgust',
'september'     => 'septembar',
'october'       => 'oktobar',
'november'      => 'novembar',
'december'      => 'decembar',
'january-gen'   => 'januara',
'february-gen'  => 'februara',
'march-gen'     => 'marta',
'april-gen'     => 'aprila',
'may-gen'       => 'maja',
'june-gen'      => 'juna',
'july-gen'      => 'jula',
'august-gen'    => 'avgusta',
'september-gen' => 'septembra',
'october-gen'   => 'oktobra',
'november-gen'  => 'novembra',
'december-gen'  => 'decembra',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'maj',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'avg',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'dec',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorija|Kategorije}}',
'category_header'                => 'Stranice u kategoriji „$1“',
'subcategories'                  => 'Potkategorije',
'category-media-header'          => 'Datoteke u kategoriji „$1“',
'category-empty'                 => "''Ova kategorija trenutno ne sadrži stranice ili datoteke.''",
'hidden-categories'              => '{{PLURAL:$1|Sakrivena kategorija|Sakrivene kategorije}}',
'hidden-category-category'       => 'Sakrivene kategorije',
'category-subcat-count'          => '{{PLURAL:$2|Ova kategorija sadrži samo sledeću potkategoriju.|Ova kategorija ima {{PLURAL:$1|sledeću potkategoriju|sledeće $1 potkategorije|sledećih $1 potkategorija}}, od ukupno $2.}}',
'category-subcat-count-limited'  => 'Ova kategorija sadrži {{PLURAL:$1|sledeću potkategoriju|sledeće $1 potkategorije|sledećih $1 potkategorija}}.',
'category-article-count'         => '{{PLURAL:$2|Ova kategorija sadrži samo sledeću stranicu.|{{PLURAL:$1|Sledeća stranica je|Sledeće $1 stranice su|Sledećih $1 stranica je}} u ovoj kategoriji, od ukupno $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Sledeća stranica je|Sledeće $1 stranice su|Sledećih $1 stranica je}} u ovoj kategoriji.',
'category-file-count'            => '{{PLURAL:$2|Ova kategorija sadrži samo sledeću datoteku.|{{PLURAL:$1|Sledeća datoteka je|Sledeće $1 datoteke su|Sledećih $1 datoteka je}} u ovoj kategoriji, od ukupno $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Sledeća datoteka je|Sledeće $1 datoteke su|Sledećih $1 datoteka je}} u ovoj kategoriji.',
'listingcontinuesabbrev'         => 'nast.',
'index-category'                 => 'Popisane stranice',
'noindex-category'               => 'Nepopisane stranice',
'broken-file-category'           => 'Stranice s neispravnim vezama do datoteka',

'linkprefix' => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',

'about'         => 'O nama',
'article'       => 'Stranica sa sadržajem',
'newwindow'     => '(otvara u novom prozoru)',
'cancel'        => 'Otkaži',
'moredotdotdot' => 'Više…',
'mypage'        => 'Moja stranica',
'mytalk'        => 'Razgovor',
'anontalk'      => 'Razgovor za ovu IP adresu',
'navigation'    => 'Navigacija',
'and'           => '&#32;i',

# Cologne Blue skin
'qbfind'         => 'Pronađi',
'qbbrowse'       => 'Potraži',
'qbedit'         => 'Uredi',
'qbpageoptions'  => 'Postavke stranice',
'qbpageinfo'     => 'Sadržaj stranice',
'qbmyoptions'    => 'Moje stranice',
'qbspecialpages' => 'Posebne stranice',
'faq'            => 'NPP',
'faqpage'        => 'Project:NPP',

# Vector skin
'vector-action-addsection'       => 'Dodaj temu',
'vector-action-delete'           => 'Obriši',
'vector-action-move'             => 'Premesti',
'vector-action-protect'          => 'Zaštiti',
'vector-action-undelete'         => 'Vrati',
'vector-action-unprotect'        => 'Promeni zaštitu',
'vector-simplesearch-preference' => 'Uprošćeno polje za pretragu (samo za temu „Vektorsko“)',
'vector-view-create'             => 'Napravi',
'vector-view-edit'               => 'Uredi',
'vector-view-history'            => 'Istorija',
'vector-view-view'               => 'Čitaj',
'vector-view-viewsource'         => 'Izvornik',
'actions'                        => 'Radnje',
'namespaces'                     => 'Imenski prostori',
'variants'                       => 'Varijante',

'errorpagetitle'    => 'Greška',
'returnto'          => 'Nazad na $1.',
'tagline'           => 'Izvor: {{SITENAME}}',
'help'              => 'Pomoć',
'search'            => 'Pretraga',
'searchbutton'      => 'Pretraži',
'go'                => 'Idi',
'searcharticle'     => 'Idi',
'history'           => 'Istorija stranice',
'history_short'     => 'Istorija',
'updatedmarker'     => 'ažurirano od moje poslednje posete',
'printableversion'  => 'Verzija za štampu',
'permalink'         => 'Trajna veza',
'print'             => 'Štampaj',
'view'              => 'Pogledaj',
'edit'              => 'Uredi',
'create'            => 'Napravi',
'editthispage'      => 'Uredi ovu stranicu',
'create-this-page'  => 'Napravi ovu stranicu',
'delete'            => 'Obriši',
'deletethispage'    => 'Obriši ovu stranicu',
'undelete_short'    => 'Vrati {{PLURAL:$1|jednu obrisanu izmenu|$1 obrisane izmene|$1 obrisanih izmena}}',
'viewdeleted_short' => 'Pogledaj {{PLURAL:$1|obrisanu izmenu|$1 obrisane izmene|$1 obrisanih izmena}}',
'protect'           => 'Zaštiti',
'protect_change'    => 'promeni',
'protectthispage'   => 'Zaštiti ovu stranicu',
'unprotect'         => 'Promeni zaštitu',
'unprotectthispage' => 'Promeni zaštitu ove stranice',
'newpage'           => 'Nova stranica',
'talkpage'          => 'Razgovor ove stranice',
'talkpagelinktext'  => 'razgovor',
'specialpage'       => 'Posebna stranica',
'personaltools'     => 'Lične alatke',
'postcomment'       => 'Novi odeljak',
'articlepage'       => 'Pogledaj stranicu sa sadržajem',
'talk'              => 'Razgovor',
'views'             => 'Pregledi',
'toolbox'           => 'Alatke',
'userpage'          => 'Pogledaj korisničku stranicu',
'projectpage'       => 'Pogledaj stranicu projekta',
'imagepage'         => 'Pogledaj stranicu datoteke',
'mediawikipage'     => 'Pogledaj stranicu poruke',
'templatepage'      => 'Pogledaj stranicu šablona',
'viewhelppage'      => 'Pogledaj stranicu pomoći',
'categorypage'      => 'Pogledaj stranicu kategorija',
'viewtalkpage'      => 'Pogledaj razgovor',
'otherlanguages'    => 'Drugi jezici',
'redirectedfrom'    => '(preusmereno sa $1)',
'redirectpagesub'   => 'Preusmerenje',
'lastmodifiedat'    => 'Ova stranica je poslednji put izmenjena $1 u $2.',
'viewcount'         => 'Ova stranica je pregledana {{PLURAL:$1|jedanput|$1 puta|$1 puta}}.',
'protectedpage'     => 'Zaštićena stranica',
'jumpto'            => 'Idi na:',
'jumptonavigation'  => 'navigaciju',
'jumptosearch'      => 'pretragu',
'view-pool-error'   => 'Nažalost, serveri su trenutno preopterećeni.
Previše korisnika pokušava da pregleda ovu stranicu.
Sačekajte neko vreme pre nego što ponovo pokušate da joj pristupite.

$1',
'pool-timeout'      => 'Istek vremena čeka na zaključavanje',
'pool-queuefull'    => 'Red je pun zahteva',
'pool-errorunknown' => 'Nepoznata greška',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'O projektu {{SITENAME}}',
'aboutpage'            => 'Project:O nama',
'copyright'            => 'Sadržaj je dostupan pod licencom $1.',
'copyrightpage'        => '{{ns:project}}:Autorska prava',
'currentevents'        => 'Aktuelnosti',
'currentevents-url'    => 'Project:Novosti',
'disclaimers'          => 'Odricanje odgovornosti',
'disclaimerpage'       => 'Project:Odricanje odgovornosti',
'edithelp'             => 'Pomoć pri uređivanju',
'edithelppage'         => 'Help:Uređivanje',
'helppage'             => 'Help:Sadržaj',
'mainpage'             => 'Glavna strana',
'mainpage-description' => 'Glavna strana',
'policy-url'           => 'Project:Pravila',
'portal'               => 'Radionica',
'portal-url'           => 'Project:Radionica',
'privacy'              => 'Politika privatnosti',
'privacypage'          => 'Project:Politika privatnosti',

'badaccess'        => 'Greške u ovlašćenjima',
'badaccess-group0' => 'Nije vam dozvoljeno da izvršite zahtevanu radnju.',
'badaccess-groups' => 'Radnja je dostupna samo korisnicima u {{PLURAL:$2|sledećoj grupi|sledećim grupama}}:  $1.',

'versionrequired'     => 'Potrebno je izdanje $1 Medijavikija',
'versionrequiredtext' => 'Potrebno je izdanje $1 Medijavikija da biste koristili ovu stranicu.
Pogledajte stranicu za [[Special:Version|izdanje]].',

'ok'                      => 'U redu',
'pagetitle'               => '$1 – {{SITENAME}}',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'backlinksubtitle'        => '← $1',
'retrievedfrom'           => 'Preuzeto iz „$1“',
'youhavenewmessages'      => 'Imate $1 ($2).',
'newmessageslink'         => 'novih poruka',
'newmessagesdifflink'     => 'poslednju izmenu',
'youhavenewmessagesmulti' => 'Imate novih poruka na $1',
'editsection'             => 'uredi',
'editsection-brackets'    => '[$1]',
'editold'                 => 'uredi',
'viewsourceold'           => 'izvornik',
'editlink'                => 'uredi',
'viewsourcelink'          => 'Izvor',
'editsectionhint'         => 'Uredite odeljak „$1“',
'toc'                     => 'Sadržaj',
'showtoc'                 => 'prikaži',
'hidetoc'                 => 'sakrij',
'collapsible-collapse'    => 'skupi',
'collapsible-expand'      => 'proširi',
'thisisdeleted'           => 'Pogledati ili vratiti $1?',
'viewdeleted'             => 'Pogledati $1?',
'restorelink'             => '{{PLURAL:$1|obrisanu izmenu|$1 obrisane izmene|$1 obrisanih izmena}}',
'feedlinks'               => 'Dovod:',
'feed-invalid'            => 'Neispravna vrsta dovoda.',
'feed-unavailable'        => 'Dovodi nisu dostupni',
'site-rss-feed'           => '$1 RSS dovod',
'site-atom-feed'          => '$1 Atom dovod',
'page-rss-feed'           => '„$1“ RSS dovod',
'page-atom-feed'          => '„$1“ Atom dovod',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => '$1 (stranica ne postoji)',
'sort-descending'         => 'Poređaj opadajuće',
'sort-ascending'          => 'Poređaj rastuće',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Stranica',
'nstab-user'      => '{{GENDER:{{BASEPAGENAME}}|Korisnik|Korisnica}}',
'nstab-media'     => 'Mediji',
'nstab-special'   => 'Posebno',
'nstab-project'   => 'Projekat',
'nstab-image'     => 'Datoteka',
'nstab-mediawiki' => 'Poruka',
'nstab-template'  => 'Šablon',
'nstab-help'      => 'Pomoć',
'nstab-category'  => 'Kategorija',

# Main script and global functions
'nosuchaction'      => 'Nema takve radnje',
'nosuchactiontext'  => 'Radnja navedena u adresi nije ispravna.
Možda ste pogrešno napisali adresu ili ste pratili zastarelu vezu.
Moguće je i da se radi o grešci u softveru vikija.',
'nosuchspecialpage' => 'Nema takve posebne stranice',
'nospecialpagetext' => '<strong>Posebna stranica ne postoji.</strong>

Spisak svih posebnih stranica nalazi se [[Special:SpecialPages|ovde]].',

# General errors
'error'                => 'Greška',
'databaseerror'        => 'Greška u bazi podataka',
'dberrortext'          => 'Došlo je do sintaktičke greške u bazi.
Možda se radi o grešci u softveru.
Poslednji pokušaj upita je glasio:
<blockquote><code>$1</code></blockquote>
unutar funkcije „<code>$2</code>“.
Baza podataka je prijavila grešku „<samp>$3: $4</samp>“.',
'dberrortextcl'        => 'Došlo je do sintaktičke greške u bazi.
Poslednji pokušaj upita je glasio:
„$1“
unutar funkcije „$2“.
Baza podataka je prijavila grešku „$3: $4“',
'laggedslavemode'      => "'''Upozorenje:''' stranica je možda zastarela.",
'readonly'             => 'Baza podataka je zaključana',
'enterlockreason'      => 'Unesite razlog za zaključavanje, uključujući i vreme otključavanja',
'readonlytext'         => 'Baza podataka je trenutno zaključana, što znači da je nije moguće menjati.

Razlog: $1',
'missing-article'      => 'Tekst stranice pod nazivom „$1“ ($2) nije pronađen.

Uzrok ove greške je obično zastarela izmena ili veza do obrisane stranice.

Ako se ne radi o tome, onda ste verovatno pronašli grešku u softveru.
Prijavite je [[Special:ListUsers/sysop|administratoru]] uz odgovarajuću vezu.',
'missingarticle-rev'   => '(izmena#: $1)',
'missingarticle-diff'  => '(razlika: $1, $2)',
'readonly_lag'         => 'Baza podataka je zaključana dok se sporedni bazni serveri ne usklade s glavnim.',
'internalerror'        => 'Unutrašnja greška',
'internalerror_info'   => 'Unutrašnja greška: $1',
'fileappenderrorread'  => 'Ne mogu da pročitam „$1“ tokom kačenja.',
'fileappenderror'      => 'Ne mogu da zakačim „$1“ na „$2“.',
'filecopyerror'        => 'Ne mogu da umnožim datoteku „$1“ u „$2“.',
'filerenameerror'      => 'Ne mogu da preimenujem datoteku „$1“ u „$2“.',
'filedeleteerror'      => 'Ne mogu da obrišem datoteku „$1“.',
'directorycreateerror' => 'Ne mogu da napravim fasciklu „$1“.',
'filenotfound'         => 'Ne mogu da pronađem datoteku „$1“.',
'fileexistserror'      => 'Ne mogu da pišem po datoteci „$1“: datoteka već postoji',
'unexpected'           => 'Neočekivana vrednost: „$1“=„$2“.',
'formerror'            => 'Greška: ne mogu da pošaljem obrazac',
'badarticleerror'      => 'Ova radnja se ne može izvršiti na ovoj stranici.',
'cannotdelete'         => 'Ne mogu da obrišem stranicu ili datoteku „$1“.
Verovatno ju je neko drugi obrisao.',
'cannotdelete-title'   => 'Ne mogu da obrišem stranicu „$1“',
'badtitle'             => 'Neispravan naslov',
'badtitletext'         => 'Naslov stranice je neispravan, prazan ili je međujezički ili međuviki naslov pogrešno povezan.
Možda sadrži znakove koji se ne mogu koristiti u naslovima.',
'perfcached'           => 'Sledeći podaci su keširani i mogu biti zastareli. Keš sadrži najviše {{PLURAL:$1|jedan rezultat|$1 rezultata|$1 rezultata}}.',
'perfcachedts'         => 'Sledeći podaci su keširani, a poslednji put su ažurirani $2 u $3. Keš sadrži najviše {{PLURAL:$4|jedan rezultat|$4 rezultata|$4 rezultata}}.',
'querypage-no-updates' => 'Ažuriranje ove stranice je trenutno onemogućeno.
Podaci koji se ovde nalaze mogu biti zastareli.',
'wrong_wfQuery_params' => 'Neispravni parametri za wfQuery()<br />
Funkcija: $1<br />
Upit: $2',
'viewsource'           => 'Izvornik',
'viewsource-title'     => 'Prikaz izvora stranice $1',
'actionthrottled'      => 'Radnja je usporena',
'actionthrottledtext'  => 'U cilju borbe protiv nepoželjnih poruka, ograničene su vam izmene u određenom vremenu, a upravo ste prešli to ograničenje. Pokušajte ponovo za nekoliko minuta.',
'protectedpagetext'    => 'Ova stranica je zaključana za uređivanja.',
'viewsourcetext'       => 'Možete da pogledate i umnožite izvorni tekst ove stranice:',
'viewyourtext'         => "Možete da pogledate i umnožite izvor '''vaših izmena''' na ovoj stranici:",
'protectedinterface'   => 'Ova stranica sadrži tekst korisničkog okruženja za softver na ovom vikiju i zaštićena je radi sprečavanja zloupotrebe.
Da biste dodali ili izmenili prevode svih vikija, posetite [//translatewiki.net/ Translejtviki], projekat za lokalizaciju Medijavikija.',
'editinginterface'     => "'''Upozorenje:''' uređujete stranicu koja se koristi za prikazivanje teksta korisničkog okruženja.
Izmene na ovoj stranici će uticati na sve korisnike ovog vikija.
Da biste dodali ili izmenili prevode svih vikija, posetite [//translatewiki.net/wiki/Main_Page?setlang=sr_ec Translejtviki], projekat za lokalizaciju Medijavikija.",
'sqlhidden'            => '(SQL upit je sakriven)',
'cascadeprotected'     => 'Ova stranica je zaključana jer sadrži {{PLURAL:$1|sledeću stranicu koja je zaštićena|sledeće stranice koje su zaštićene}} „prenosivom“ zaštitom:
$2',
'namespaceprotected'   => "Nemate dozvolu da uređujete stranice u imenskom prostoru '''$1'''.",
'customcssprotected'   => 'Nemate dozvolu da menjate ovu CSS stranicu jer sadrži lične postavke drugog korisnika.',
'customjsprotected'    => 'Nemate dozvolu da menjate ovu stranicu javaskripta jer sadrži lične postavke drugog korisnika.',
'ns-specialprotected'  => 'Posebne stranice se ne mogu uređivati.',
'titleprotected'       => "Ovaj naslov je {{GENDER:$1|zaštitio korisnik|zaštitila korisnica|zaštitio korisnik}} [[User:$1|$1]].
Navedeni razlog: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Neispravna postavka: nepoznati skener za viruse: ''$1''",
'virus-scanfailed'     => 'neuspešno skeniranje (kod $1)',
'virus-unknownscanner' => 'nepoznati antivirus:',

# Login and logout pages
'logouttext'                 => "'''Odjavljeni ste.'''

Možete da nastavite s korišćenjem ovog vikija kao gost, ili se [[Special:UserLogin|ponovo prijavite]] kao drugi korisnik.
Imajte na umu da neke stranice mogu nastaviti da se prikazuju kao da ste još prijavljeni, sve dok ne očistite privremenu memoriju svog pregledača.",
'welcomecreation'            => '== Dobro došli, $1! ==

Vaš nalog je otvoren.
Ne zaboravite da prilagodite svoja [[Special:Preferences|podešavanja]].',
'yourname'                   => 'Korisničko ime:',
'yourpassword'               => 'Lozinka:',
'yourpasswordagain'          => 'Potvrda lozinke:',
'remembermypassword'         => 'Zapamti me na ovom pregledaču (najduže $1 {{PLURAL:$1|dan|dana|dana}})',
'securelogin-stick-https'    => 'Ostanite povezani sa HTTPS nakon prijave',
'yourdomainname'             => 'Domen:',
'externaldberror'            => 'Došlo je do greške pri prepoznavanju baze podataka ili nemate ovlašćenja da ažurirate svoj spoljni nalog.',
'login'                      => 'Prijavi me',
'nav-login-createaccount'    => 'Prijava/registracija',
'loginprompt'                => 'Omogućite kolačiće da biste se prijavili na ovaj viki.',
'userlogin'                  => 'Prijava/registracija',
'userloginnocreate'          => 'Prijava',
'logout'                     => 'Odjava',
'userlogout'                 => 'Odjavi me',
'notloggedin'                => 'Niste prijavljeni',
'nologin'                    => 'Nemate nalog? Idite na stranicu „$1“.',
'nologinlink'                => 'Otvaranje naloga',
'createaccount'              => 'Otvori nalog',
'gotaccount'                 => 'Već imate nalog? Idite na stranicu „$1“.',
'gotaccountlink'             => 'Prijava',
'userlogin-resetlink'        => 'Zaboravili ste podatke za prijavu?',
'createaccountmail'          => 'E-poštom',
'createaccountreason'        => 'Razlog:',
'badretype'                  => 'Unete lozinke se ne poklapaju.',
'userexists'                 => 'Korisničko ime je zauzeto. Izaberite drugo.',
'loginerror'                 => 'Greška pri prijavljivanju',
'createaccounterror'         => 'Ne mogu da otvorim nalog: $1',
'nocookiesnew'               => 'Korisnički nalog je otvoren, ali niste prijavljeni.
Ovaj viki koristi kolačiće za prijavu. Vama su kolačići onemogućeni.
Omogućite ih, pa se onda prijavite sa svojim korisničkim imenom i lozinkom.',
'nocookieslogin'             => 'Ovaj viki koristi kolačiće za prijavljivanje korisnika.
Vama su kolačići onemogućeni. Omogućite ih i pokušajte ponovo.',
'nocookiesfornew'            => 'Korisnički nalog nije otvoren jer njegov izvor nije potvrđen.
Omogućite kolačiće na pregledaču i ponovo učitajte stranicu.',
'nocookiesforlogin'          => '{{int:nocookieslogin}}',
'noname'                     => 'Uneli ste neispravno korisničko ime.',
'loginsuccesstitle'          => 'Uspešno prijavljivanje',
'loginsuccess'               => "'''Prijavljeni ste kao „$1“.'''",
'nosuchuser'                 => 'Ne postoji korisnik s imenom „$1“.
Korisnička imena su osetljiva na mala i velika slova.
Proverite da li ste ga dobro uneli ili [[Special:UserLogin/signup|otvorite novi nalog]].',
'nosuchusershort'            => 'Korisnik s imenom „$1“ ne postoji.
Proverite da li ste pravilno napisali.',
'nouserspecified'            => 'Morate navesti korisničko ime.',
'login-userblocked'          => '{{GENDER:$1|Ovaj korisnik je blokiran|Ova korisnica je blokirana|Ovaj korisnik je blokiran}}. Prijava nije dozvoljena.',
'wrongpassword'              => 'Uneli ste neispravnu lozinku. Pokušajte ponovo.',
'wrongpasswordempty'         => 'Niste uneli lozinku. Pokušajte ponovo.',
'passwordtooshort'           => 'Lozinka mora imati najmanje {{PLURAL:$1|jedan znak|$1 znaka|$1 znakova}}.',
'password-name-match'        => 'Lozinka se mora razlikovati od korisničkog imena.',
'password-login-forbidden'   => 'Korišćenje ovog korisničkog imena i lozinke je zabranjeno.',
'mailmypassword'             => 'Pošalji novu lozinku',
'passwordremindertitle'      => '{{SITENAME}} – podsetnik za lozinku',
'passwordremindertext'       => 'Neko, verovatno vi, sa IP adrese $1 je zatražio novu lozinku na vikiju {{SITENAME}} ($4).
Stvorena je privremena lozinka za {{GENDER:$2|korisnika|korisnicu|korisnika}} $2 koja glasi $3.
Ukoliko je ovo vaš zahtev, sada se prijavite i postavite novu lozinku.
Privremena lozinka ističe za {{PLURAL:$5|jedan dan|$5 dana|$5 dana}}.

Ako je neko drugi zatražio promenu lozinke, ili ste se setili vaše lozinke i ne želite da je menjate, zanemarite ovu poruku.',
'noemail'                    => 'Ne postoji e-adresa za {{GENDER:$1|korisnika|korisnicu|korisnika}} $1.',
'noemailcreate'              => 'Morate navesti ispravnu e-adresu',
'passwordsent'               => 'Nova lozinka je poslata na e-adresu {{GENDER:$1|korisnika|korisnice|korisnika}} $1.
Prijavite se pošto je primite.',
'blocked-mailpassword'       => 'Vašoj IP adresi je onemogućeno uređivanje stranica, kao i mogućnost zahtevanja nove lozinke.',
'eauthentsent'               => 'Na navedenu e-adresu je poslat potvrdni kod.
Pre nego što pošaljemo daljnje poruke, pratite uputstva s e-pošte da biste potvrdili da ste vi otvorili nalog.',
'throttled-mailpassword'     => 'Podsetnik za lozinku je poslat {{PLURAL:$1|pre sat vremena|u poslednja $1 sata|u poslednjih $1 sati}}.
Da bismo sprečili zloupotrebu, posednik šaljemo samo jednom u roku od {{PLURAL:$1|jednog sata|$1 sata|$1 sati}}.',
'mailerror'                  => 'Greška pri slanju poruke: $1',
'acct_creation_throttle_hit' => 'Posetioci ovog vikija koji koriste vašu IP adresu su već otvorili {{PLURAL:$1|jedan nalog|$1 naloga|$1 naloga}} prethodni dan, što je najveći dozvoljeni broj u tom vremenskom periodu.
Zbog toga posetioci s ove IP adrese trenutno ne mogu otvoriti više naloga.',
'emailauthenticated'         => 'Vaša e-adresa je potvrđena $2 u $3.',
'emailnotauthenticated'      => 'Vaša e-adresa još nije potvrđena.
Poruke neće biti poslate ni za jednu od sledećih mogućnosti.',
'noemailprefs'               => 'Unesite e-adresu kako bi ove mogućnosti radile.',
'emailconfirmlink'           => 'Potvrdite svoju e-adresu',
'invalidemailaddress'        => 'E-adresa ne može biti prihvaćena jer je neispravnog oblika.
Unesite ispravnu adresu ili ostavite prazno polje.',
'cannotchangeemail'          => 'Na ovom vikiju ne možete promeniti e-adresu naloga.',
'accountcreated'             => 'Nalog je otvoren',
'accountcreatedtext'         => 'Nalog {{GENDER:$1|korisnika|korisnice|korisnika}} $1 je otvoren.',
'createaccount-title'        => 'Otvaranje korisničkog naloga za {{SITENAME}}',
'createaccount-text'         => 'Neko je otvorio nalog s vašom e-adresom na {{SITENAME}} ($4) pod imenom $2 i lozinkom $3.
Prijavite se i promenite svoju lozinku.

Ako je ovo greška, zanemarite ovu poruku.',
'usernamehasherror'          => 'Korisničko ime ne može sadržati tarabe',
'login-throttled'            => 'Previše puta ste pokušali da se prijavite.
Sačekajte nekoliko minuta i pokušajte ponovo.',
'login-abort-generic'        => 'Neuspešna prijava – prekinuto',
'loginlanguagelabel'         => 'Jezik: $1',
'suspicious-userlogout'      => 'Vaš zahtev za odjavu je odbijen jer je poslat od strane neispravnog pregledača ili posrednika.',

# E-mail sending
'php-mail-error-unknown' => 'Nepoznata greška u funkciji PHP mail().',
'user-mail-no-addy'      => 'Pokušali ste da pošaljete poruku bez e-adrese.',

# Change password dialog
'resetpass'                 => 'Promena lozinke',
'resetpass_announce'        => 'Prijavljeni ste s privremenom lozinkom.
Da biste završili prijavu, podesite novu lozinku ovde:',
'resetpass_text'            => '<!-- Ovde unesite tekst -->',
'resetpass_header'          => 'Promena lozinke naloga',
'oldpassword'               => 'Stara lozinka:',
'newpassword'               => 'Nova lozinka:',
'retypenew'                 => 'Potvrda lozinke:',
'resetpass_submit'          => 'Postavi lozinku i prijavi me',
'resetpass_success'         => 'Vaša lozinka je promenjena.
Prijavljivanje je u toku…',
'resetpass_forbidden'       => 'Lozinka ne može biti promenjena',
'resetpass-no-info'         => 'Morate biti prijavljeni da biste pristupili ovoj stranici.',
'resetpass-submit-loggedin' => 'Promeni lozinku',
'resetpass-submit-cancel'   => 'Otkaži',
'resetpass-wrong-oldpass'   => 'Neispravna privremena ili tekuća lozinka.
Možda ste već promenili lozinku ili ste zatražili novu privremenu lozinku.',
'resetpass-temp-password'   => 'Privremena lozinka:',

# Special:PasswordReset
'passwordreset'                    => 'Obnavljanje lozinke',
'passwordreset-text'               => 'Popunite ovaj obrazac da biste primili e-poruku sa svojim podacima za prijavu.',
'passwordreset-legend'             => 'Poništi lozinku',
'passwordreset-disabled'           => 'Obnavljanje lozinke je onemogućeno na ovom vikiju.',
'passwordreset-pretext'            => '{{PLURAL:$1||Unesite jedan od delova podataka ispod}}',
'passwordreset-username'           => 'Korisničko ime:',
'passwordreset-domain'             => 'Domen:',
'passwordreset-capture'            => 'Pogledati krajnju poruku?',
'passwordreset-capture-help'       => 'Ako označite ovu kućicu, e-poruka s privremenom lozinkom će biti prikazana i poslata korisniku.',
'passwordreset-email'              => 'E-adresa:',
'passwordreset-emailtitle'         => 'Detalji naloga na vikiju {{SITENAME}}',
'passwordreset-emailtext-ip'       => 'Neko, verovatno vi, sa IP adrese $1 je zatražio novu lozinku na vikiju {{SITENAME}} ($4).
Sledeći {{PLURAL:$3|korisnički nalog je povezan|korisnički nalozi su povezani}} s ovom e-adresom:

$2

{{PLURAL:$3|Privremena lozinka ističe|Privremene lozinke ističu}} za {{PLURAL:$5|jedan dan|$5 dana|$5 dana}}.
Prijavite se i izaberite novu lozinku. Ako je neko drugi zahtevao ovu radnju ili ste se setili lozinke i ne želite da je menjate, zanemarite ovu poruku.',
'passwordreset-emailtext-user'     => '{{GENDER:$1|Korisnik|Korisnica|Korisnik}} $1 je zatražio podsetnik o podacima za prijavu na vikiju {{SITENAME}} ($4).
Sledeći {{PLURAL:$3|korisnički nalog je povezan|korisnički nalozi su povezani}} s ovom e-adresom:

$2

{{PLURAL:$3|Privremena lozinka ističe|Privremene lozinke ističu}} za {{PLURAL:$5|jedan dan|$5 dana|$5 dana}}.
Prijavite se i izaberite novu lozinku. Ako je neko drugi zahtevao ovu radnju ili ste se setili lozinke i ne želite da je menjate, zanemarite ovu poruku.',
'passwordreset-emailelement'       => 'Korisničko ime: $1
Privremena lozinka: $2',
'passwordreset-emailsent'          => 'Podsetnik o lozinci je poslat na vašu adresu.',
'passwordreset-emailsent-capture'  => 'Poslat je podsetnik preko e-pošte (prikazan dole).',
'passwordreset-emailerror-capture' => 'Napravljen je podsetnik za slanje preko e-pošte (prikazan dole), ali ga ne mogu poslati korisniku: $1',

# Special:ChangeEmail
'changeemail'          => 'Promena e-adrese',
'changeemail-header'   => 'Promenite e-adresu naloga',
'changeemail-text'     => 'Popunite ovaj obrazac da biste promenili svoju e-adresu. Moraćete da unesete lozinku da potvrdite izmenu.',
'changeemail-no-info'  => 'Morate biti prijavljeni da biste pristupili ovoj stranici.',
'changeemail-oldemail' => 'Trenutna e-adresa:',
'changeemail-newemail' => 'Nova e-adresa:',
'changeemail-none'     => '(ništa)',
'changeemail-submit'   => 'Promeni',
'changeemail-cancel'   => 'Otkaži',

# Edit page toolbar
'bold_sample'     => 'Podebljan tekst',
'bold_tip'        => 'Podebljan tekst',
'italic_sample'   => 'Iskošeni tekst',
'italic_tip'      => 'Iskošeni tekst',
'link_sample'     => 'Naslov veze',
'link_tip'        => 'Unutrašnja veza',
'extlink_sample'  => 'http://www.primer.com naslov veze',
'extlink_tip'     => "Spoljna veza (s predmetkom ''http://'')",
'headline_sample' => 'Naslov',
'headline_tip'    => 'Podnaslov',
'nowiki_sample'   => 'Ubacite neoblikovan tekst ovde',
'nowiki_tip'      => 'Zanemari viki oblikovanje',
'image_sample'    => 'Primer.jpg',
'image_tip'       => 'Ugrađena datoteka',
'media_sample'    => 'Primer.ogg',
'media_tip'       => 'Veza',
'sig_tip'         => 'Potpis s trenutnim vremenom',
'hr_tip'          => 'Vodoravna linija (koristiti retko)',

# Edit pages
'summary'                          => 'Opis izmene:',
'subject'                          => 'Tema/naslov:',
'minoredit'                        => 'manja izmena',
'watchthis'                        => 'nadgledaj ovu stranicu',
'savearticle'                      => 'Sačuvaj stranicu',
'preview'                          => 'Pregled',
'showpreview'                      => 'Pregledaj',
'showlivepreview'                  => 'Trenutni pregled',
'showdiff'                         => 'Prikaži izmene',
'anoneditwarning'                  => "'''Upozorenje:''' niste prijavljeni.
Vaša IP adresa će biti zabeležena u istoriji ove stranice.",
'anonpreviewwarning'               => "''Niste prijavljeni. Vaša IP adresa će biti zabeležena u istoriji ove stranice.''",
'missingsummary'                   => "'''Napomena:''' niste uneli opis izmene.
Ako ponovo kliknete na „{{int:savearticle}}“, vaša izmena će biti sačuvana bez opisa.",
'missingcommenttext'               => 'Unesite komentar ispod.',
'missingcommentheader'             => "'''Napomena:''' niste uneli naslov ovog komentara.
Ako ponovo kliknete na „{{int:savearticle}}“, vaša izmena će biti sačuvana bez naslova.",
'summary-preview'                  => 'Pregled opisa:',
'subject-preview'                  => 'Pregled teme/naslova:',
'blockedtitle'                     => 'Korisnik je blokiran',
'blockedtext'                      => "'''Vaše korisničko ime ili IP adresa je blokirana.'''

Blokiranje je {{GENDER:$4|izvršio|izvršila|izvršio}} $1.
Razlog: ''$2''.

* Datum blokiranja: $8
* Blokiranje ističe: $6
* Ime korisnika: $7

Obratite se {{GENDER:$4|korisniku|korisnici|korisniku}} $1 ili [[{{MediaWiki:Grouppage-sysop}}|administratoru]] da razjasnite stvar.
Ne možete koristiti mogućnost „Pošalji poruku ovom korisniku“ ako niste uneli ispravnu e-adresu u [[Special:Preferences|podešavanjima]].
Vaša blokirana IP adresa je $3, a IB $5.
Navedite sve podatke iznad pri stvaranja bilo kakvih upita.",
'autoblockedtext'                  => "Vaša IP adresa je blokirana jer ju je upotrebljavao drugi korisnik, koga je {{GENDER:$1|blokirao|blokirala|blokirao}} $1.
Razlog:

:''$2''

* Datum blokiranja: $8
* Blokiranje ističe: $6
* Ime korisnika: $7

Obratite se {{GENDER:$1|korisniku|korisnici|korisniku}} $1 ili [[{{MediaWiki:Grouppage-sysop}}|administratoru]] da razjasnite stvar.

Ne možete koristiti mogućnost „Pošalji poruku ovom korisniku“ ako niste uneli ispravnu e-adresu u [[Special:Preferences|podešavanjima]].

Vaša blokirana IP adresa je $3, a IB $5.
Navedite sve podatke iznad pri stvaranju bilo kakvih upita.",
'blockednoreason'                  => 'razlog nije naveden',
'whitelistedittext'                => 'Za uređivanje stranice je potrebno da budete $1.',
'confirmedittext'                  => 'Morate da potvrdite svoju e-adresu pre uređivanja stranica.
Postavite i potvrdite adresu preko [[Special:Preferences|podešavanja]].',
'nosuchsectiontitle'               => 'Ne mogu da pronađem odeljak',
'nosuchsectiontext'                => 'Pokušali ste da uredite odeljak koji ne postoji.
Možda je premešten ili obrisan dok ste pregledali stranicu.',
'loginreqtitle'                    => 'Potrebna je prijava',
'loginreqlink'                     => 'prijavljeni',
'loginreqpagetext'                 => 'Morate biti $1 da biste videli druge stranice.',
'accmailtitle'                     => 'Lozinka je poslata.',
'accmailtext'                      => 'Lozinka za {{GENDER:$1|korisnika|korisnicu|korisnika}} [[User talk:$1|$1]] je poslata na $2.

Nakon prijave, lozinka se može promeniti [[Special:ChangePassword|ovde]].',
'newarticle'                       => '(novi)',
'newarticletext'                   => 'Došli ste na stranicu koja još ne postoji.
Da biste je napravili, počnite kucati u prozor ispod ovog teksta (pogledajte [[{{MediaWiki:Helppage}}|stranicu za pomoć]]).
Ako ste ovde došli greškom, vratite se na prethodnu stranicu.',
'anontalkpagetext'                 => '---- Ovo je stranica za razgovor s anonimnim korisnikom koji još nema nalog ili ga ne koristi.
Zbog toga moramo da koristimo brojčanu IP adresu kako bismo ga prepoznali.
Takvu adresu može deliti više korisnika.
Ako ste anonimni korisnik i mislite da su vam upućene primedbe, [[Special:UserLogin/signup|otvorite nalog]] ili se [[Special:UserLogin|prijavite]] da biste izbegli buduću zabunu s ostalim anonimnim korisnicima.',
'noarticletext'                    => 'Na ovoj stranici trenutno nema sadržaja.
Možete [[Special:Search/{{PAGENAME}}|potražiti ovaj naslov]] na drugim stranicama,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} pretražiti srodne izveštaje] ili [{{fullurl:{{FULLPAGENAME}}|action=edit}} urediti stranicu]</span>.',
'noarticletext-nopermission'       => 'Na ovoj stranici trenutno nema sadržaja.
Možete [[Special:Search/{{PAGENAME}}|potražiti ovaj naslov]] na drugim stranicama ili <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} pretražiti srodne izveštaje]</span>.',
'userpage-userdoesnotexist'        => 'Korisnički nalog „<nowiki>$1</nowiki>“ nije otvoren.
Razmislite da li zaista želite da napravite ili uredite ovu stranicu.',
'userpage-userdoesnotexist-view'   => 'Korisnički nalog „$1“ nije otvoren.',
'blocked-notice-logextract'        => 'Ovaj korisnik je trenutno blokiran.
Izveštaj o poslednjem blokiranju možete pogledati ispod:',
'clearyourcache'                   => "'''Napomena:''' nakon čuvanja, možda ćete morati da očistite keš pregledača.
*'''Fajerfoks i Safari:''' držite ''Shift'' i kliknite na ''Osveži'', ili pritisnite ''Ctrl-F5'' ili Ctrl-R (''⌘-R'' na Makintošu)
*'''Gugl kroum:''' pritisnite ''Ctrl-Shift-R'' (''⌘-Shift-R'' na Makintošu)
*'''Internet eksplorer: '''držite ''Ctrl'' i kliknite na ''Osveži'', ili pritisnite ''Ctrl-F5''
*'''Opera:''' očistite privremenu memoriju preko menija ''Alatke → Postavke''.",
'usercssyoucanpreview'             => "'''Savet:''' korisitite dugme „{{int:showpreview}}“ da isprobate svoj novi CSS pre nego što ga sačuvate.",
'userjsyoucanpreview'              => "'''Savet:''' korisitite dugme „{{int:showpreview}}“ da isprobate svoj novi javaskript pre nego što ga sačuvate.",
'usercsspreview'                   => "'''Ovo je samo pregled CSS-a.'''
'''Stranica još nije sačuvana!'''",
'userjspreview'                    => "'''Ovo je samo pregled javaskripta.'''
'''Stranica još nije sačuvana!'''",
'sitecsspreview'                   => "'''Ovo je samo pregled CSS-a.'''
'''Stranica još nije sačuvana!'''",
'sitejspreview'                    => "'''Ovo je samo pregled javaskripta.'''
'''Stranica još nije sačuvana!'''",
'userinvalidcssjstitle'            => "'''Upozorenje:''' ne postoji tema „$1“.
Prilagođene stranice CSS i javaskript počinju malim slovom, npr. {{ns:user}}:Foo/vector.css, a ne {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(Ažurirano)',
'note'                             => "'''Napomena:'''",
'previewnote'                      => "'''Imajte u vidu da je ovo samo pregled.'''
Vaše izmene još nisu sačuvane!",
'previewconflict'                  => 'Ovaj pregled oslikava kako će tekst u tekstualnom okviru izgledati.',
'session_fail_preview'             => "'''Nismo mogli da obradimo vašu izmenu zbog gubitka podataka sesije.'''
Pokušajte ponovo.
Ako i dalje ne radi, pokušajte da se [[Special:UserLogout|odjavite]] i ponovo prijavite.",
'session_fail_preview_html'        => "'''Nismo mogli da obradimo vašu izmenu zbog gubitka podataka sesije.'''

''Budući da je na ovom vikiju omogućen unos HTML oznaka, pregled je sakriven kao mera predostrožnosti protiv napada preko javaskripta.''

'''Ako ste pokušali da napravite pravu izmenu, pokušajte ponovo.
Ako i dalje ne radi, pokušajte da se [[Special:UserLogout|odjavite]] i ponovo prijavite.'''",
'token_suffix_mismatch'            => "'''Vaša izmena je odbačena jer je vaš pregledač ubacio znakove interpunkcije u novčić uređivanja.
To se ponekad događa kada se koristi neispravan posrednik.'''",
'edit_form_incomplete'             => "'''Neki delovi obrasca za uređivanje nisu dostigli do servera. Proverite da li su izmene promenjene i pokušajte ponovo.'''",
'editing'                          => 'Uređujete $1',
'editingsection'                   => 'Uređujete $1 (odeljak)',
'editingcomment'                   => 'Uređujete $1 (novi odeljak)',
'editconflict'                     => 'Sukobljene izmene: $1',
'explainconflict'                  => "Neko drugi je u međuvremenu promenio ovu stranicu.
Gornji okvir sadrži tekst stranice.
Vaše izmene su prikazane u donjem polju.
Moraćete da unesete svoje promene u postojeći tekst.
'''Samo''' će tekst u gornjem tekstualnom okviru biti sačuvan kada kliknete na „{{int:savearticle}}“.",
'yourtext'                         => 'Vaš tekst',
'storedversion'                    => 'Uskladištena izmena',
'nonunicodebrowser'                => "'''Upozorenje: vaš pregledač ne podržava unikod.'''
Promenite ga pre nego što počnete s uređivanjem.",
'editingold'                       => "'''Upozorenje: uređujete zastarelu izmenu ove stranice.
Ako je sačuvate, sve novije izmene će biti izgubljene.'''",
'yourdiff'                         => 'Razlike',
'copyrightwarning'                 => "Imajte na umu da se svi doprinosi na ovom vikiju smatraju kao objavljeni pod licencom $2 (više na $1).
Ako ne želite da se vaši tekstovi menjaju i razmenjuju bez ograničenja, onda ih ne šaljite ovde.<br />
Isto tako obećavate da ste vi autor teksta, ili da ste ga umnožili s izvora koji je u javnom vlasništvu.
'''Ne šaljite radove zaštićene autorskim pravima bez dozvole!'''",
'copyrightwarning2'                => "Imajte na umu da se svi doprinosi na ovom vikiju mogu menjati, vraćati ili brisati od drugih korisnika.
Ako ne želite da se vaši tekstovi slobodno menjaju i raspodeljuju, ne šaljite ih ovde.<br />
Isto tako obećavate da ste vi autor teksta, ili da ste ga umnožili s izvora koji je u javnom vlasništvu (više na $1).
'''Ne šaljite radove zaštićene autorskim pravima bez dozvole!'''",
'longpageerror'                    => "'''Greška: tekst koji ste uneli je veličine {{PLURAL:$1|jedan kilobajt|$1 kilobajta|$1 kilobajta}}, što je veće od {{PLURAL:$2|dozvoljenog jednog kilobajta|dozvoljena $2 kilobajta|dozvoljenih $2 kilobajta}}.'''
Stranica ne može biti sačuvana.",
'readonlywarning'                  => "'''Upozorenje: baza podataka je zaključana radi održavanja, tako da nećete moći da sačuvate izmene.
Najbolje bi bilo da umnožite tekst u uređivač teksta i sačuvate ga za kasnije.'''

Administrator koji je zaključao bazu podataka je naveo sledeće objašnjenje: $1",
'protectedpagewarning'             => "'''Upozorenje: ova stranica je zaštićena, tako da samo administratori mogu da je menjaju.'''
Poslednja stavka u istoriji je prikazana ispod:",
'semiprotectedpagewarning'         => "'''Napomena:''' ova stranica je zaštićena, tako da samo registrovani korisnici mogu da je uređuju.
Poslednji zapis u dnevniku je prikazan ispod:",
'cascadeprotectedwarning'          => "'''Upozorenje:''' ova stranica je zaštićena tako da je mogu uređivati samo administratori, jer je ona uključena u {{PLURAL:$1|sledeću stranicu koja je|sledeće stranice koje su}} zaštićene „prenosivom“ zaštitom:",
'titleprotectedwarning'            => "'''Upozorenje: ova stranica je zaštićena tako da je mogu napraviti samo korisnici [[Special:ListGroupRights|s određenim pravima]].'''",
'templatesused'                    => '{{PLURAL:$1|Šablon|Šabloni}} na ovoj stranici:',
'templatesusedpreview'             => '{{PLURAL:$1|Šablon|Šabloni}} u ovom pregledu:',
'templatesusedsection'             => '{{PLURAL:$1|Šablon|Šabloni}} u ovom odeljku:',
'template-protected'               => '(zaštićen)',
'template-semiprotected'           => '(poluzaštićen)',
'hiddencategories'                 => 'Ova stranica je član {{PLURAL:$1|jedne skrivene kategorije|$1 skrivene kategorije|$1 skrivenih kategorija}}:',
'edittools'                        => '<!-- Ovaj tekst će biti prikazan ispod obrasca za uređivanje i otpremanje. -->',
'edittools-upload'                 => '-',
'nocreatetitle'                    => 'Pravljenje stranice je ograničeno',
'nocreatetext'                     => 'Na ovom vikiju je ograničeno pravljenje novih stranica.
Možete se vratiti i urediti postojeću stranicu, ili se [[Special:UserLogin|prijavite ili otvorite nalog]].',
'nocreate-loggedin'                => 'Nemate dozvolu da pravite nove stranice.',
'sectioneditnotsupported-title'    => 'Uređivanje odeljka nije podržano',
'sectioneditnotsupported-text'     => 'Uređivanje odeljka nije podržano na ovoj stranici.',
'permissionserrors'                => 'Greške u dozvolama',
'permissionserrorstext'            => 'Nemate ovlašćenje za tu radnju iz {{PLURAL:$1|sledećeg|sledećih}} razloga:',
'permissionserrorstext-withaction' => 'Nemate dozvolu da $2 iz {{PLURAL:$1|sledećeg|sledećih}} razloga:',
'recreate-moveddeleted-warn'       => "'''Upozorenje: ponovo pravite stranicu koja je prethodno obrisana.'''

Razmotrite da li je prikladno da nastavite s uređivanjem ove stranice.
Ovde je navedena istorija brisanja i premeštanja s obrazloženjem:",
'moveddeleted-notice'              => 'Ova stranica je obrisana.
Istorija njenog brisanja i premeštanja nalazi se ispod:',
'log-fulllog'                      => 'Pogledaj celu istoriju',
'edit-hook-aborted'                => 'Izmenu je prekinula kuka.
Nije dato nikakvo obrazloženje.',
'edit-gone-missing'                => 'Ne mogu da ažuriram stranicu.
Izgleda da je obrisana.',
'edit-conflict'                    => 'Sukob izmena.',
'edit-no-change'                   => 'Vaša izmena je zanemarena jer nije bilo nikakvih izmena u tekstu.',
'edit-already-exists'              => 'Ne mogu da napravim stranicu.
Izgleda da ona već postoji.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''Upozorenje:''' ova stranica sadrži previše poziva za raščlanjivanje.

Trebalo bi da ima manje od $2 {{PLURAL:$2|poziv|poziva|poziva}}, a sada ima $1.",
'expensive-parserfunction-category'       => 'Stranice s previše poziva za raščlanjivanje',
'post-expand-template-inclusion-warning'  => "'''Upozorenje:''' veličina uključenog šablona je prevelika.
Neki šabloni neće biti uključeni.",
'post-expand-template-inclusion-category' => 'Stranice gde su uključeni šabloni preveliki',
'post-expand-template-argument-warning'   => "'''Upozorenje:''' ova stranica sadrži najmanje jedan argument u šablonu koji ima preveliku veličinu.
Ovakve argumente bi trebalo izbegavati.",
'post-expand-template-argument-category'  => 'Stranice koje sadrže izostavljene argumente u šablonu',
'parser-template-loop-warning'            => 'Otkrivena je petlja šablona: [[$1]]',
'parser-template-recursion-depth-warning' => 'Dubina uključivanja šablona je prekoračena ($1)',
'language-converter-depth-warning'        => 'Prekoračena je granica dubine jezičkog pretvarača ($1)',

# "Undo" feature
'undo-success' => 'Izmena se može vratiti.
Proverite razlike ispod, pa sačuvajte izmene.',
'undo-failure' => 'Ne mogu da vratim izmenu zbog postojanja sukobljenih međuizmena.',
'undo-norev'   => 'Ne mogu da vratim izmenu jer ne postoji ili je obrisana.',
'undo-summary' => 'Vraćena izmena $1 od {{GENDER:$2|korisnika|korisnice|korisnika}} [[Special:Contributions/$2|$2]] ([[User talk:$2|razgovor]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ne mogu da otvorim nalog',
'cantcreateaccount-text' => "Otvaranje naloga s ove IP adrese ('''$1''') je {{GENDER:$3|blokirao|blokirala|blokirao}} [[User:$3|$3]].

Razlog koji je naveo {{GENDER:$3|korisnik|korisnica|korisnik}} $3 je ''$2''",

# History pages
'viewpagelogs'           => 'Pogledaj dnevnike ove stranice',
'nohistory'              => 'Ne postoji istorija izmena ove stranice.',
'currentrev'             => 'Tekuća izmena',
'currentrev-asof'        => 'Tekuća izmena od $2 u $3',
'revisionasof'           => 'Izmena od $2 u $3',
'revision-info'          => 'Izmena od $1; $2',
'previousrevision'       => '← Starija izmena',
'nextrevision'           => 'Novija izmena →',
'currentrevisionlink'    => 'Tekuća izmena',
'cur'                    => 'tren',
'next'                   => 'sled',
'last'                   => 'preth',
'page_first'             => 'prva',
'page_last'              => 'poslednja',
'histlegend'             => "Izbor razlika: izaberite kutijice izmena za upoređivanje i pritisnite enter ili dugme na dnu.<br />
Objašnjenje: '''({{int:cur}})''' – razlika s trenutnom izmenom,
'''({{int:last}})''' – razlika s prethodnom izmenom, '''{{int:minoreditletter}}''' – mala izmena",
'history-fieldset-title' => 'Pregled istorije',
'history-show-deleted'   => 'samo obrisano',
'histfirst'              => 'najstarije',
'histlast'               => 'najnovije',
'historysize'            => '({{PLURAL:$1|1 bajt|$1 bajta|$1 bajtova}})',
'historyempty'           => '(prazno)',

# Revision feed
'history-feed-title'          => 'Istorija izmena',
'history-feed-description'    => 'Istorija izmena ove stranice na vikiju',
'history-feed-item-nocomment' => '$1 u $2',
'history-feed-empty'          => 'Tražena stranica ne postoji.
Moguće da je obrisana s vikija ili je preimenovana.
Pokušajte da [[Special:Search|pretražite viki]] za slične stranice.',

# Revision deletion
'rev-deleted-comment'         => '(opis izmene je uklonjen)',
'rev-deleted-user'            => '(korisničko ime je uklonjeno)',
'rev-deleted-event'           => '(istorija je uklonjena)',
'rev-deleted-user-contribs'   => '[korisničko ime ili IP adresa je uklonjena – izmena je sakrivena sa spiska doprinosa]',
'rev-deleted-text-permission' => "Izmena ove stranice je '''obrisana'''.
Detalje možete videti u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} istoriji brisanja].",
'rev-deleted-text-unhide'     => "Izmena ove stranice je '''obrisana'''.
Detalje možete videti u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} istoriji brisanja].
Ipak možete da [$1 vidite ovu izmenu] ako želite da nastavite.",
'rev-suppressed-text-unhide'  => "Izmena ove stranice je '''sakrivena'''.
Detalje možete videti u [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} istoriji sakrivanja].
Ipak možete da [$1 vidite ovu izmenu] ako želite da nastavite.",
'rev-deleted-text-view'       => "Izmena ove stranice je '''obrisana'''.
Možete je pogledati; više detalja možete naći u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} istoriji brisanja].",
'rev-suppressed-text-view'    => "Izmena ove stranice je '''sakrivena'''.
Možete je pogledati; više detalja možete naći u [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} istoriji sakrivanja].",
'rev-deleted-no-diff'         => "Ne možete videti ovu razliku jer je jedna od izmena '''obrisana'''.
Detalji se nalaze u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} istoriji brisanja].",
'rev-suppressed-no-diff'      => "Ne možete videti ovu razliku jer je jedna od izmena '''obrisana'''.",
'rev-deleted-unhide-diff'     => "Jedna od izmena u ovom pregledu razlika je '''obrisana'''.
Detalji se nalaze u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} istoriji brisanja].
Ipak možete da [$1 vidite ovu razliku] ako želite da nastavite.",
'rev-suppressed-unhide-diff'  => "Jedna od izmena ove razlike je '''sakrivena'''.
Detalji se nalaze u [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} istoriji sakrivanja].
Ipak možete da [$1 vidite ovu razliku] ako želite da nastavite.",
'rev-deleted-diff-view'       => "Jedna od izmena ove razlike je '''obrisana'''.
Ipak možete da vidite ovu razliku; više detalja možete naći u [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} istoriji brisanja].",
'rev-suppressed-diff-view'    => "Jedna od izmena ove razlike je '''sakrivena'''.
Ipak možete da vidite ovu razliku; više detalja možete naći u [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} istoriji sakrivanja].",
'rev-delundel'                => 'prikaži/sakrij',
'rev-showdeleted'             => 'prikaži',
'revisiondelete'              => 'Obriši/vrati izmene',
'revdelete-nooldid-title'     => 'Nema tražene izmene',
'revdelete-nooldid-text'      => 'Niste naveli željenu izmenu, ona ne postoji ili pokušavate da je sakrijete.',
'revdelete-nologtype-title'   => 'Nije navedena vrsta istorije',
'revdelete-nologtype-text'    => 'Niste naveli vrstu istorije nad kojom želite da izvršite ovu radnju.',
'revdelete-nologid-title'     => 'Neispravan zapis u dnevniku',
'revdelete-nologid-text'      => 'Niste odredili odredišnu istoriju ili navedeni unos ne postoji.',
'revdelete-no-file'           => 'Tražena datoteka ne postoji.',
'revdelete-show-file-confirm' => 'Želite li da vidite obrisanu izmenu datoteke „<nowiki>$1</nowiki>“ od $2; $3?',
'revdelete-show-file-submit'  => 'Da',
'revdelete-selected'          => "'''{{PLURAL:$2|Izabrana izmena|Izabrane izmene}} stranice '''[[:$1]]''''''",
'logdelete-selected'          => "'''{{PLURAL:$1|Izabrana stavka u istoriji|Izabrane stavke u istoriji}}:'''",
'revdelete-text'              => "'''Obrisane izmene će i dalje biti prikazane u istoriji stranica i zapisima, ali delovi njihovog sadržaja neće biti dostupni javnosti.'''
Drugi administratori na ovom vikiju će i dalje imati pristup sakrivenom sadržaju, a oni će taj sadržaj moći da vrate putem ovog sučelja, osim ako nisu postavljena dodatna ograničenja.",
'revdelete-confirm'           => 'Potvrdite da nameravate ovo uraditi, da razumete posledice i da to činite u skladu s [[{{MediaWiki:Policy-url}}|pravilima]].',
'revdelete-suppress-text'     => "Sakrivanje izmena bi trebalo koristiti '''samo''' u sledećim slučajevima:
* Zlonamerni ili pogrdni podaci
* Neprikladni lični podaci
*: ''kućna adresa i broj telefona, broj bankovne kartice itd.''",
'revdelete-legend'            => 'Ograničenja vidljivosti',
'revdelete-hide-text'         => 'sakrij tekst izmene',
'revdelete-hide-image'        => 'Sakrij sadržaj datoteke',
'revdelete-hide-name'         => 'Sakrij radnju i odredište',
'revdelete-hide-comment'      => 'sakrij opis izmene',
'revdelete-hide-user'         => 'sakrij ime uređivača',
'revdelete-hide-restricted'   => 'Sakrij podatke od administratora i drugih korisnika',
'revdelete-radio-same'        => '(ne menjaj)',
'revdelete-radio-set'         => 'da',
'revdelete-radio-unset'       => 'ne',
'revdelete-suppress'          => 'Sakrij podatke od administratora i drugih korisnika',
'revdelete-unsuppress'        => 'Ukloni ograničenja na vraćenim izmenama',
'revdelete-log'               => 'Razlog:',
'revdelete-submit'            => 'Primeni na {{PLURAL:$1|izabranu izmenu|izabrane izmene}}',
'revdelete-success'           => "'''Vidljivost izmene je ažurirana.'''",
'revdelete-failure'           => "'''Ne mogu da ažuriram vidljivost izmene:'''
$1",
'logdelete-success'           => "'''Vidljivost istorije je postavljena.'''",
'logdelete-failure'           => "'''Ne mogu da postavim vidljivost istorije:'''
$1",
'revdel-restore'              => 'promeni vidljivost',
'revdel-restore-deleted'      => 'obrisane izmene',
'revdel-restore-visible'      => 'vidljive izmene',
'pagehist'                    => 'Istorija stranice',
'deletedhist'                 => 'Obrisana istorija',
'revdelete-hide-current'      => 'Greška pri sakrivanju stavke od $1, $2: ovo je trenutna izmena.
Ne može biti sakrivena.',
'revdelete-show-no-access'    => 'Greška pri prikazivanju stavke od $1, $2: označena je kao „ograničena“.
Nemate pristup do nje.',
'revdelete-modify-no-access'  => 'Greška pri menjanju stavke od $1, $2: označena je kao „ograničena“.
Nemate pristup do nje.',
'revdelete-modify-missing'    => 'Greška pri menjanju IB stavke $1: ona ne postoji u bazi podataka.',
'revdelete-no-change'         => "'''Upozorenje:''' stavka od $1, $2 već poseduje zatražene postavke vidljivosti.",
'revdelete-concurrent-change' => 'Greška pri menjanju stavke od $1, $2: njeno stanje je u međuvremenu promenjeno od strane drugog korisnika.
Pogledajte istoriju.',
'revdelete-only-restricted'   => 'Greška pri sakrivanju stavke od $1, $2: ne možete sakriti stavke od administratora bez izbora drugih mogućnosti vidljivosti.',
'revdelete-reason-dropdown'   => '*Uobičajeni razlozi za brisanje
** Kršenje autorskog prava
** Neprikladan komentar ili lični podaci
** Neprikladno korisničko ime
** Uvredljivi podaci',
'revdelete-otherreason'       => 'Drugi/dodatni razlog:',
'revdelete-reasonotherlist'   => 'Drugi razlog',
'revdelete-edit-reasonlist'   => 'Uredi razloge za brisanje',
'revdelete-offender'          => 'Autor izmene:',

# Suppression log
'suppressionlog'     => 'Istorija sakrivanja',
'suppressionlogtext' => 'Ispod se nalazi spisak brisanja i blokiranja koji uključuje sadržaj sakriven od administratora. Tekuće zabrane i blokiranja možete naći [[Special:BlockList|ovde]].',

# History merging
'mergehistory'                     => 'Spoji istorije stranica',
'mergehistory-header'              => 'Ova stranica vam omogućava da spojite izmene neke izvorne stranice u novu stranicu.
Zapamtite da će ova izmena ostaviti nepromenjen sadržaj istorije stranice.',
'mergehistory-box'                 => 'Spoji izmene dve stranice:',
'mergehistory-from'                => 'Izvorna stranica:',
'mergehistory-into'                => 'Odredišna stranica:',
'mergehistory-list'                => 'Spojiva istorija izmena',
'mergehistory-merge'               => 'Sledeće izmene stranice [[:$1]] mogu se spojiti sa [[:$2]].
Koristite dugmiće u koloni da biste spojili izmene koje su napravljene pre navedenog vremena.
Korišćenje navigacionih veza će poništiti ovu kolonu.',
'mergehistory-go'                  => 'Prikaži izmene koje se mogu spojiti',
'mergehistory-submit'              => 'Spoji izmene',
'mergehistory-empty'               => 'Nema izmena za spajanje.',
'mergehistory-success'             => '$3 {{PLURAL:$3|izmena stranice [[:$1]] je spojena|izmene stranice [[:$1]] su spojene|izmena stranice [[:$1]] je spojeno}} u [[:$2]].',
'mergehistory-fail'                => 'Ne mogu da spojim istorije. Proverite stranicu i vremenske parametre.',
'mergehistory-no-source'           => 'Izvorna stranica $1 ne postoji.',
'mergehistory-no-destination'      => 'Odredišna stranica $1 ne postoji.',
'mergehistory-invalid-source'      => 'Izvorna stranica mora imati ispravan naslov.',
'mergehistory-invalid-destination' => 'Odredišna stranica mora imati ispravan naslov.',
'mergehistory-autocomment'         => 'Stranica [[:$1]] je spojena u [[:$2]]',
'mergehistory-comment'             => 'Stranica [[:$1]] je spojena u [[:$2]]: $3',
'mergehistory-same-destination'    => 'Izvorna i odredišna stranica ne mogu biti iste',
'mergehistory-reason'              => 'Razlog:',

# Merge log
'mergelog'           => 'Dnevnik spajanja',
'pagemerge-logentry' => 'stranica [[$1]] je spojena u [[$2]] (sve do izmene $3)',
'revertmerge'        => 'rastavi',
'mergelogpagetext'   => 'Ispod se nalazi spisak skorašnjih spajanja istorija stranica.',

# Diffs
'history-title'            => 'Istorija izmena stranice „$1“',
'difference'               => '(razlike između izmena)',
'difference-multipage'     => '(razlike između stranica)',
'lineno'                   => 'Red $1:',
'compareselectedversions'  => 'Uporedi izabrane izmene',
'showhideselectedversions' => 'Prikaži/sakrij izabrane izmene',
'editundo'                 => 'poništi',
'diff-multi'               => '({{PLURAL:$1|nije prikazana međuizmena|nisu prikazane $1 međuizmene|nije prikazano $1 međuizmena}} {{PLURAL:$2|jednog|$2|$2}} korisnika)',
'diff-multi-manyusers'     => '({{PLURAL:$1|Nije prikazana međuizmena|Nisu prikazane $1 međuizmene|Nije prikazano $1 međuizmena}} od više od $2 korisnika)',

# Search results
'searchresults'                    => 'Rezultati pretrage',
'searchresults-title'              => 'Rezultati pretrage za „$1”',
'searchresulttext'                 => 'Za više informacija o pretraživanju projekta {{SITENAME}} pogledajte [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => "Tražili ste '''[[:$1]]''' ([[Special:Prefixindex/$1|sve stranice koje počinju sa „$1“]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|sve stranice koje vode do „$1“]])",
'searchsubtitleinvalid'            => "Tražili ste '''$1'''",
'toomanymatches'                   => 'Pronađeno je previše rezultata. Izmenite upit.',
'titlematches'                     => 'Naslov stranice odgovara',
'notitlematches'                   => 'Nijedan naslov stranice ne odgovara',
'textmatches'                      => 'Tekst stranice odgovara',
'notextmatches'                    => 'Nijedan tekst stranice ne odgovara',
'prevn'                            => 'prethodnih {{PLURAL:$1|$1}}',
'nextn'                            => 'sledećih {{PLURAL:$1|$1}}',
'prevn-title'                      => '$1 {{PLURAL:$1|prethodni  rezultat|prethodna rezultata|prethodnih rezultata}}',
'nextn-title'                      => '$1 {{PLURAL:$1|sledeći rezultat|sledeća rezultata|sledećih rezultata}}',
'shown-title'                      => 'Prikaži $1 {{PLURAL:$1|rezultat|rezultata|rezultata}} po stranici',
'viewprevnext'                     => 'Pogledaj ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Postavke pretrage',
'searchmenu-exists'                => "'''Postoji i članak pod nazivom „[[:$1]]“.'''",
'searchmenu-new'                   => "'''Napravite stranicu „[[:$1]]“.'''",
'searchhelp-url'                   => 'Help:Sadržaj',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Pregledaj stranice s ovim prefiksom]]',
'searchprofile-articles'           => 'Članci',
'searchprofile-project'            => 'Stranice pomoći i projekata',
'searchprofile-images'             => 'Datoteke',
'searchprofile-everything'         => 'Sve',
'searchprofile-advanced'           => 'Napredno',
'searchprofile-articles-tooltip'   => 'Pretražite: $1',
'searchprofile-project-tooltip'    => 'Pretražite: $1',
'searchprofile-images-tooltip'     => 'Pretražite datoteke',
'searchprofile-everything-tooltip' => 'Pretražite sav sadržaj (uključujući stranice za razgovor)',
'searchprofile-advanced-tooltip'   => 'Pretražite prilagođene imenske prostore',
'search-result-size'               => '$1 ({{PLURAL:$2|1 reč|$2 reči|$2 reči}})',
'search-result-category-size'      => '{{PLURAL:$1|1 član|$1 člana|$1 članova}}, ({{PLURAL:$2|1 potkategorija|$2 potkategorije|$2 potkategorija}}, {{PLURAL:$3|1 datoteka|$3 datoteke|$3 datoteka}})',
'search-result-score'              => 'Relevantnost: $1%',
'search-redirect'                  => '(preusmerenje $1)',
'search-section'                   => '(odeljak $1)',
'search-suggest'                   => 'Da li ste mislili na: $1',
'search-interwiki-caption'         => 'Bratski projekti',
'search-interwiki-default'         => '$1 rezultati:',
'search-interwiki-more'            => '(više)',
'search-mwsuggest-enabled'         => 'sa predlozima',
'search-mwsuggest-disabled'        => 'bez predloga',
'search-relatedarticle'            => 'Povezano',
'mwsuggest-disable'                => 'Onemogući predloge AJAX',
'searcheverything-enable'          => 'svi imenski prostori',
'searchrelated'                    => 'povezano',
'searchall'                        => 'sve',
'showingresults'                   => "Ispod {{PLURAL:$1|je prikazan '''1''' rezultat|su prikazana '''$1''' rezultata|je prikazano '''$1''' rezultata}} počev od broja '''$2'''.",
'showingresultsnum'                => "Ispod {{PLURAL:$3|je prikazan '''1''' rezultat|su prikazana '''$3''' rezultata|je prikazano '''$3''' rezultata}} počev od broja '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Rezultat '''$1''' od '''$3'''|Rezultata '''$1 – $2''' od '''$3'''}} za '''$4'''",
'nonefound'                        => "'''Napomena''': samo se neki imenski prostori pretražuju po podrazumevanim postavkama.
Ako želite sve da pretražite, dodajte prefiks '''all:''' ispred traženog sadržaja (ovo uključuje stranice za razgovor, šablone itd.) ili koristite prefiks željenog imenskog prostora.",
'search-nonefound'                 => 'Nema poklapanja.',
'powersearch'                      => 'Pretraži',
'powersearch-legend'               => 'Napredna pretraga',
'powersearch-ns'                   => 'Traži u sledećim imenskim prostorima:',
'powersearch-redir'                => 'Spisak preusmerenja',
'powersearch-field'                => 'Traži',
'powersearch-togglelabel'          => 'Izaberi:',
'powersearch-toggleall'            => 'sve',
'powersearch-togglenone'           => 'ništa',
'search-external'                  => 'Spoljna pretraga',
'searchdisabled'                   => 'Pretraga je onemogućena.
U međuvremenu možete tražiti preko Gugla.
Upamtite da njegovi popisi ovog vikija mogu biti zastareli.',

# Quickbar
'qbsettings'                => 'Bočna paleta',
'qbsettings-none'           => 'Ništa',
'qbsettings-fixedleft'      => 'Pričvršćena levo',
'qbsettings-fixedright'     => 'Pričvršćena desno',
'qbsettings-floatingleft'   => 'Plutajuća levo',
'qbsettings-floatingright'  => 'Plutajuća desno',
'qbsettings-directionality' => 'Fiksno, u zavisnosti od smera pisanja vašeg jezika',

# Preferences page
'preferences'                   => 'Podešavanja',
'mypreferences'                 => 'Podešavanja',
'prefs-edits'                   => 'Broj izmena:',
'prefsnologin'                  => 'Niste prijavljeni',
'prefsnologintext'              => 'Morate biti <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} prijavljeni]</span> da biste podešavali korisničke postavke.',
'changepassword'                => 'Promeni lozinku',
'prefs-skin'                    => 'Tema',
'skin-preview'                  => 'Pregledaj',
'datedefault'                   => 'Svejedno',
'prefs-beta'                    => 'Beta mogućnosti',
'prefs-datetime'                => 'Datum i vreme',
'prefs-labs'                    => 'Probne mogućnosti',
'prefs-personal'                => 'Profil',
'prefs-rc'                      => 'Skorašnje izmene',
'prefs-watchlist'               => 'Spisak nadgledanja',
'prefs-watchlist-days'          => 'Broj dana u spisku nadgledanja:',
'prefs-watchlist-days-max'      => 'Najviše $1 {{PLURAL:$1|dan|dana|dana}}',
'prefs-watchlist-edits'         => 'Najveći broj izmena u proširenom spisku nadgledanja:',
'prefs-watchlist-edits-max'     => 'Najveća vrednost je hiljadu',
'prefs-watchlist-token'         => 'Žeton spiska nadgledanja:',
'prefs-misc'                    => 'Druga podešavanja',
'prefs-resetpass'               => 'Promeni lozinku',
'prefs-changeemail'             => 'Promeni e-adresu',
'prefs-setemail'                => 'Postavi e-adresu',
'prefs-email'                   => 'Postavke e-pošte',
'prefs-rendering'               => 'Izgled',
'saveprefs'                     => 'Sačuvaj',
'resetprefs'                    => 'Očisti nesačuvane izmene',
'restoreprefs'                  => 'Vrati sve na podrazumevano',
'prefs-editing'                 => 'Uređivanje',
'prefs-edit-boxsize'            => 'Veličina okvira za uređivanje.',
'rows'                          => 'Redova:',
'columns'                       => 'Kolone:',
'searchresultshead'             => 'Pretraga',
'resultsperpage'                => 'Pogodaka po stranici:',
'stub-threshold'                => 'Prag za oblikovanje <a href="#" class="stub">veze kao klice</a> (u bajtovima):',
'stub-threshold-disabled'       => 'Onemogućeno',
'recentchangesdays'             => 'Broj dana u skorašnjim izmenama:',
'recentchangesdays-max'         => '(najviše $1 {{PLURAL:$1|dan|dana|dana}})',
'recentchangescount'            => 'Broj izmena za prikaz:',
'prefs-help-recentchangescount' => 'Podrazumeva skorašnje izmene, istorije stranica i dnevnike.',
'prefs-help-watchlist-token'    => 'Popunjavanjem ovog polja s tajnom šifrom napraviće RSS dovod vašeg spiska nadgledanja.
Svako ko zna tu šifru biće u mogućnosti da vidi vaša nadgledanja, zato izaberite bezbednu.
Na primer: $1',
'savedprefs'                    => 'Vaša podešavanja su sačuvana.',
'timezonelegend'                => 'Vremenska zona:',
'localtime'                     => 'Lokalno vreme:',
'timezoneuseserverdefault'      => 'podrazumevane vrednosti ($1)',
'timezoneuseoffset'             => 'drugo (unesite odstupanje)',
'timezoneoffset'                => 'Odstupanje¹:',
'servertime'                    => 'Vreme na serveru:',
'guesstimezone'                 => 'popuni iz pregledača',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktik',
'timezoneregion-arctic'         => 'Arktik',
'timezoneregion-asia'           => 'Azija',
'timezoneregion-atlantic'       => 'Atlantski okean',
'timezoneregion-australia'      => 'Australija',
'timezoneregion-europe'         => 'Evropa',
'timezoneregion-indian'         => 'Indijski okean',
'timezoneregion-pacific'        => 'Tihi okean',
'allowemail'                    => 'Omogući primanje e-poruka od drugih korisnika',
'prefs-searchoptions'           => 'Pretraga',
'prefs-namespaces'              => 'Imenski prostori',
'defaultns'                     => 'Ako nije navedeno drugačije, traži u ovim imenskim prostorima:',
'default'                       => 'podrazumevano',
'prefs-files'                   => 'Datoteke',
'prefs-custom-css'              => 'Prilagođeni CSS',
'prefs-custom-js'               => 'Prilagođeni javaskript',
'prefs-common-css-js'           => 'Deljeni CSS/javaskript za sve teme:',
'prefs-reset-intro'             => 'Možete koristiti ovu stranicu da poništite svoje postavke na podrazumevane vrednosti.
Ova radnja se ne može vratiti.',
'prefs-emailconfirm-label'      => 'Potvrda e-adrese:',
'prefs-textboxsize'             => 'Veličina okvira za uređivanje',
'youremail'                     => 'E-adresa:',
'username'                      => 'Korisničko ime:',
'uid'                           => 'Korisnički IB:',
'prefs-memberingroups'          => 'Član {{PLURAL:$1|grupe|grupâ}}:',
'prefs-memberingroups-type'     => '$1',
'prefs-registration'            => 'Vreme upisa:',
'prefs-registration-date-time'  => '$1',
'yourrealname'                  => 'Pravo ime:',
'yourlanguage'                  => 'Jezik:',
'yourvariant'                   => 'Varijanta jezika:',
'prefs-help-variant'            => 'Željena varijanta ili pravopis za prikaz stranica sa sadržajem ovog vikija.',
'yournick'                      => 'Novi potpis:',
'prefs-help-signature'          => "Komentare na stranicama za razgovor potpišite sa ''<nowiki>~~~~</nowiki>''. Ovi znakovi će biti pretvoreni u vaš potpis i trenutno vreme.",
'badsig'                        => 'Potpis je neispravan.
Proverite oznake HTML.',
'badsiglength'                  => 'Vaš potpis je predugačak.
Ne sme biti duži od $1 {{PLURAL:$1|znaka|znaka|znakova}}.',
'yourgender'                    => 'Pol:',
'gender-unknown'                => 'nenaznačen',
'gender-male'                   => 'muški',
'gender-female'                 => 'ženski',
'prefs-help-gender'             => 'Neobavezno: koristi se za ispravno obraćanje softvera korisnicima, zavisno od njihovog pola.
Ovaj podatak će biti javan.',
'email'                         => 'E-adresa',
'prefs-help-realname'           => 'Pravo ime nije obavezno.
Ako izaberete da ga unesete, ono će biti korišćeno za pripisivanje vašeg rada.',
'prefs-help-email'              => 'E-adresa nije obavezna, ali je potrebna u slučaju da zaboravite lozinku.',
'prefs-help-email-others'       => 'Možete je koristiti i da omogućite drugima da vas kontaktiraju preko korisničke stranice ili stranice za razgovor, bez otkrivanja svog identiteta.',
'prefs-help-email-required'     => 'Potrebna je e-adresa.',
'prefs-info'                    => 'Osnovni podaci',
'prefs-i18n'                    => 'Internacionalizacija',
'prefs-signature'               => 'Potpis',
'prefs-dateformat'              => 'Format datuma',
'prefs-timeoffset'              => 'Vremensko odstupanje',
'prefs-advancedediting'         => 'Napredne postavke',
'prefs-advancedrc'              => 'Napredne postavke',
'prefs-advancedrendering'       => 'Napredne postavke',
'prefs-advancedsearchoptions'   => 'Napredne postavke',
'prefs-advancedwatchlist'       => 'Napredne postavke',
'prefs-displayrc'               => 'Postavke prikaza',
'prefs-displaysearchoptions'    => 'Postavke prikaza',
'prefs-displaywatchlist'        => 'Postavke prikaza',
'prefs-diffs'                   => 'Razlike',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'E-adresa je ispravna',
'email-address-validity-invalid' => 'Unesite ispravnu e-adresu',

# User rights
'userrights'                     => 'Upravljanje korisničkim pravima',
'userrights-lookup-user'         => 'Upravljanje korisničkim grupama',
'userrights-user-editname'       => 'Unesite korisničko ime:',
'editusergroup'                  => 'Promeni korisničke grupe',
'editinguser'                    => "Menjate korisnička prava {{GENDER:$1|korisnika|korisnice|korisnika}} '''[[User:$1|$1]]''' $2",
'userrights-editusergroup'       => 'Promena korisničkih grupa',
'saveusergroups'                 => 'Sačuvaj korisničke grupe',
'userrights-groupsmember'        => 'Član:',
'userrights-groupsmember-auto'   => 'Uključeni član od:',
'userrights-groups-help'         => 'Možete promeniti grupe kojima ovaj korisnik pripada.
* Označena kućica označava da se korisnik nalazi u toj grupi.
* Neoznačena kućica označava da se korisnik ne nalazi u toj grupi.
* Zvezdica označava da ne možete ukloniti grupu ako je dodate i obratno.',
'userrights-reason'              => 'Razlog:',
'userrights-no-interwiki'        => 'Nemate ovlašćenja da menjate korisnička prava na drugim vikijima.',
'userrights-nodatabase'          => 'Baza podataka $1 ne postoji ili nije lokalna.',
'userrights-nologin'             => 'Morate se [[Special:UserLogin|prijaviti]] s administratorskim nalogom da biste dodali korisnička prava.',
'userrights-notallowed'          => 'Nemate ovlašćenja da dodajete ili uklanjate korisnička prava.',
'userrights-changeable-col'      => 'Grupe koje možete da promenite',
'userrights-unchangeable-col'    => 'Grupe koje ne možete da promenite',
'userrights-irreversible-marker' => '$1*',

# Groups
'group'               => 'Grupa:',
'group-user'          => 'Korisnici',
'group-autoconfirmed' => 'Automatski potvrđeni korisnici',
'group-bot'           => 'Botovi',
'group-sysop'         => 'Administratori',
'group-bureaucrat'    => 'Birokrate',
'group-suppress'      => 'Revizori',
'group-all'           => '(sve)',

'group-user-member'          => '{{GENDER:$1|korisnik|korisnica|korisnik}}',
'group-autoconfirmed-member' => '{{GENDER:$1|automatski potvrđen korisnik|automatski potvrđena korisnica|automatski potvrđen korisnik}}',
'group-bot-member'           => '{{GENDER:$1|bot}}',
'group-sysop-member'         => '{{GENDER:$1|administrator|administratorka|administrator}}',
'group-bureaucrat-member'    => '{{GENDER:$1|birokrata|birokratkinja|birokrata}}',
'group-suppress-member'      => '{{GENDER:$1|revizor|revizorka|revizor}}',

'grouppage-user'          => '{{ns:project}}:Korisnici',
'grouppage-autoconfirmed' => '{{ns:project}}:Automatski potvrđeni korisnici',
'grouppage-bot'           => '{{ns:project}}:Botovi',
'grouppage-sysop'         => '{{ns:project}}:Administratori',
'grouppage-bureaucrat'    => '{{ns:project}}:Birokrate',
'grouppage-suppress'      => '{{ns:project}}:Revizor',

# Rights
'right-read'                  => 'pregledanje stranica',
'right-edit'                  => 'uređivanje stranica',
'right-createpage'            => 'pravljenje stranica (izuzev stranica za razgovor)',
'right-createtalk'            => 'pravljenje stranica za razgovor',
'right-createaccount'         => 'otvaranje novih korisničkih naloga',
'right-minoredit'             => 'označavanje izmena kao manje',
'right-move'                  => 'premeštanje stranica',
'right-move-subpages'         => 'premeštanje stranica s njihovim podstranicama',
'right-move-rootuserpages'    => 'premeštanje osnovnih korisničkih stranica',
'right-movefile'              => 'premeštanje datoteka',
'right-suppressredirect'      => 'preskakanje stvaranja preusmerenja pri premeštanju stranica',
'right-upload'                => 'otpremanje datoteka',
'right-reupload'              => 'zamenjivanje postojećih datoteka',
'right-reupload-own'          => 'zamenjivanje sopstvenih datoteka',
'right-reupload-shared'       => 'menjanje datoteka na deljenom skladištu multimedije',
'right-upload_by_url'         => 'otpremanje datoteka sa veb adrese',
'right-purge'                 => 'čišćenje keš memorije stranice bez potvrde',
'right-autoconfirmed'         => 'uređivanje poluzaštićenih stranica',
'right-bot'                   => 'smatranje izmena kao automatski proces',
'right-nominornewtalk'        => 'neposedovanje malih izmena na stranicama za razgovor otvara prozor za nove poruke',
'right-apihighlimits'         => 'korišćenje viših granica za upite iz API-ja',
'right-writeapi'              => 'pisanje API-ja',
'right-delete'                => 'brisanje stranica',
'right-bigdelete'             => 'brisanje stranica s velikom istorijom',
'right-deleterevision'        => 'brisanje i vraćanje određenih izmena stranica',
'right-deletedhistory'        => 'pregledanje obrisanih stavki istorije bez povezanog teksta',
'right-deletedtext'           => 'pregledanje obrisanog teksta i izmena između obrisanih izmena',
'right-browsearchive'         => 'traženje obrisanih stranica',
'right-undelete'              => 'vraćanje obrisanih stranica',
'right-suppressrevision'      => 'pregledanje i vraćanje izmena koje su sakrivene od strane administratora',
'right-suppressionlog'        => 'gledanje privatnih dnevnika',
'right-block'                 => 'blokiranje daljih izmena drugih korisnika',
'right-blockemail'            => 'onemogućavanje korisnicima da šalju e-poruke',
'right-hideuser'              => 'blokiranje korisničkog imena i njegovo sakrivanje od javnosti',
'right-ipblock-exempt'        => 'zaobilaženje blokiranja IP adrese, samoblokiranja i blokiranja opsega',
'right-proxyunbannable'       => 'zaobilaženje samoblokiranja posrednika',
'right-unblockself'           => 'deblokiranje samog sebe',
'right-protect'               => 'menjanje zaštićenih stranica i stepena zaštite',
'right-editprotected'         => 'uređivanje zaštićenih stranica (s prenosivom zaštitom)',
'right-editinterface'         => 'uređivanje korisničkog sučelja',
'right-editusercssjs'         => 'uređivanje tuđih CSS i javaskript datoteka',
'right-editusercss'           => 'uređivanje tuđih CSS datoteka',
'right-edituserjs'            => 'uređivanje tuđih javaskript datoteka',
'right-rollback'              => 'brzo vraćanje izmena poslednjeg korisnika koji je menjao određenu stranicu',
'right-markbotedits'          => 'označavanje vraćenih izmena kao izmene bota',
'right-noratelimit'           => 'otpornost na ograničenja',
'right-import'                => 'uvoženje stranica iz drugih vikija',
'right-importupload'          => 'uvoženje stranica iz otpremljene datoteke',
'right-patrol'                => 'označavanje tuđih izmena kao pregledanih',
'right-autopatrol'            => 'samooznačavanje izmena kao pregledane',
'right-patrolmarks'           => 'pregledanje oznaka za patroliranje unutar skorašnjih izmena',
'right-unwatchedpages'        => 'pregledanje spiska nenadgledanih stranica',
'right-mergehistory'          => 'spajanje istorija stranica',
'right-userrights'            => 'uređivanje svih korisničkih prava',
'right-userrights-interwiki'  => 'uređivanje korisničkih prava na drugim vikijima',
'right-siteadmin'             => 'zaključavanje i otključavanje baze podataka',
'right-override-export-depth' => 'izvoz stranica uključujući i povazene stranice do dubine od pet veza',
'right-sendemail'             => 'slanje e-poruka drugim korisnicima',
'right-passwordreset'         => 'pregledanje poruka za obnavljanje lozinke',

# User rights log
'rightslog'                  => 'Dnevnik korisničkih prava',
'rightslogtext'              => 'Ovo je dnevnik izmena korisničkih prava.',
'rightslogentry'             => '{{GENDER:|je promenio|je promenila|je promenio}} prava za člana $1 iz $2 u $3',
'rightslogentry-autopromote' => 'je unapređen iz $2 u $3',
'rightsnone'                 => '(ništa)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'čitanje ove stranice',
'action-edit'                 => 'uređivanje ove stranice',
'action-createpage'           => 'pravljenje stranica',
'action-createtalk'           => 'pravljenje stranica za razgovor',
'action-createaccount'        => 'otvaranje ovog korisničkog naloga',
'action-minoredit'            => 'označavanje ove izmene kao manje',
'action-move'                 => 'premeštanje ove stranice',
'action-move-subpages'        => 'premeštanje ove stranice i njenih podstranica',
'action-move-rootuserpages'   => 'premeštanje osnovnih korisničkih stranica',
'action-movefile'             => 'premeštanje ove datoteke',
'action-upload'               => 'slanje ove datoteke',
'action-reupload'             => 'zamenjivanje postojeće datoteke',
'action-reupload-shared'      => 'postavljanje ove datoteke na zajedničko skladište',
'action-upload_by_url'        => 'slanje ove datoteke preko URL adrese',
'action-writeapi'             => 'pisanje API-ja',
'action-delete'               => 'brisanje ove stranice',
'action-deleterevision'       => 'brisanje ove izmene',
'action-deletedhistory'       => 'pregledanje obrisane istorije ove stranice',
'action-browsearchive'        => 'pretraživanje obrisanih stranica',
'action-undelete'             => 'vraćanje ove stranice',
'action-suppressrevision'     => 'pregledanje i vraćanje ove sakrivene izmene',
'action-suppressionlog'       => 'pregledanje ove privatne istorije',
'action-block'                => 'blokiranje daljih izmena ovog korisnika',
'action-protect'              => 'menjanje stepena zaštite ove stranice',
'action-rollback'             => 'brzo vraćanje izmena poslednjeg korisnika koji je menjao određenu stranicu',
'action-import'               => 'uvoz ove stranice s drugog vikija',
'action-importupload'         => 'uvoz ove stranice slanjem datoteke',
'action-patrol'               => 'označavanje tuđih izmena pregledanim',
'action-autopatrol'           => 'samooznačavanje izmena pregledanim',
'action-unwatchedpages'       => 'pregledanje spiska nenadgledanih stranica',
'action-mergehistory'         => 'spajanje istorije ove stranice',
'action-userrights'           => 'uređivanje svih korisničkih prava',
'action-userrights-interwiki' => 'uređivanje korisničkih prava na drugim vikijima',
'action-siteadmin'            => 'zaključavanje ili otključavanje baze podataka',
'action-sendemail'            => 'slanje e-poruka',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|izmena|izmene|izmena}}',
'recentchanges'                     => 'Skorašnje izmene',
'recentchanges-legend'              => 'Postavke skorašnjih izmena',
'recentchangestext'                 => 'Pratite skorašnje izmene na ovoj stranici.',
'recentchanges-feed-description'    => 'Pratite skorašnje izmene uz pomoć ovog dovoda.',
'recentchanges-label-newpage'       => 'Nova stranica',
'recentchanges-label-minor'         => 'Manja izmena',
'recentchanges-label-bot'           => 'Ovu izmenu je napravio bot',
'recentchanges-label-unpatrolled'   => 'Ova izmena još nije pregledana',
'rcnote'                            => "Ispod {{PLURAL:$1|je '''1''' izmena|su poslednje '''$1''' izmene|su poslednjih '''$1''' izmena}} {{PLURAL:$2|prethodni dan|u poslednja '''$2''' dana|u poslednjih '''$2''' dana}}, od $4; $5.",
'rcnotefrom'                        => 'Ispod su izmene od <b>$3; $4</b> (do <b>$1</b> izmena).',
'rclistfrom'                        => 'Prikaži nove izmene počev od $1',
'rcshowhideminor'                   => '$1 manje izmene',
'rcshowhidebots'                    => '$1 botove',
'rcshowhideliu'                     => '$1 prijavljene korisnike',
'rcshowhideanons'                   => '$1 anonimne korisnike',
'rcshowhidepatr'                    => '$1 patrolirane izmene',
'rcshowhidemine'                    => '$1 moje izmene',
'rclinks'                           => 'Prikaži poslednjih $1 izmena {{PLURAL:$2|prethodni dan|u poslednja $2 dana|u poslednjih $2 dana}}<br />$3',
'diff'                              => 'razl',
'hist'                              => 'ist',
'hide'                              => 'sakrij',
'show'                              => 'prikaži',
'minoreditletter'                   => ' m',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'unpatrolledletter'                 => '!',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|korisnik nadgleda|korisnika nadgledaju|korisnika nadgledaju}}]',
'rc_categories'                     => 'Ograniči na kategorije (razdvoji s uspravnom crtom)',
'rc_categories_any'                 => 'Sve',
'rc-change-size'                    => '$1',
'rc-change-size-new'                => '$1 {{PLURAL:$1|bajt|bajta|bajtova}} posle izmene',
'newsectionsummary'                 => '/* $1 */ novi odeljak',
'rc-enhanced-expand'                => 'Prikaži detalje (javaskript)',
'rc-enhanced-hide'                  => 'Sakrij detalje',
'rc-old-title'                      => 'prvobitno napravljeno kao „$1“',

# Recent changes linked
'recentchangeslinked'          => 'Srodne izmene',
'recentchangeslinked-feed'     => 'Srodne izmene',
'recentchangeslinked-toolbox'  => 'Srodne izmene',
'recentchangeslinked-title'    => 'Srodne izmene sa „$1“',
'recentchangeslinked-noresult' => 'Nema izmena na povezanim stranicama u zadanom periodu.',
'recentchangeslinked-summary'  => "Ova posebna stranica prikazuje spisak poslednjih izmena na stranicama koje su povezane (ili članovi određene kategorije).
Stranice s [[Special:Watchlist|vašeg spiska nadgledanja]] su '''podebljane'''.",
'recentchangeslinked-page'     => 'Naziv stranice:',
'recentchangeslinked-to'       => 'Prikaži izmene stranica koje su povezane s datom stranicom',

# Upload
'upload'                      => 'Pošalji datoteku',
'uploadbtn'                   => 'Pošalji datoteku',
'reuploaddesc'                => 'Nazad na obrazac za otpremanje',
'upload-tryagain'             => 'Pošalji izmenjeni opis datoteke',
'uploadnologin'               => 'Niste prijavljeni',
'uploadnologintext'           => 'Morate biti [[Special:UserLogin|prijavljeni]] da biste otpremali datoteke.',
'upload_directory_missing'    => 'Fascikla za slanje ($1) nedostaje i server je ne može napraviti.',
'upload_directory_read_only'  => 'Server ne može da piše po fascikli za slanje ($1).',
'uploaderror'                 => 'Greška pri otpremanju',
'upload-recreate-warning'     => "'''Upozorenje: datoteka s tim nazivom je obrisana ili premeštena.'''

Istorija brisanja i premeštanja se nalazi ispod:",
'uploadtext'                  => "Koristite obrazac ispod da biste otpremili datoteke.
Za pregled ili pretragu postojećih datoteka, pogledajte [[Special:FileList|spisak otpremljenih datoteka]], ponovna otpremanja su navedena u [[Special:Log/upload|dnevniku otpremanja]], a brisanja u [[Special:Log/delete|dnevniku brisanja]].

Datoteku dodajete na željenu stranicu koristeći sledeće obrasce:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Slika.jpg]]</nowiki></code>''' za verziju slike u punoj veličini
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Slika.png|200p|mini|levo|opis]]</nowiki></code>''' za verziju slike s veličinom od 200 piksela koja je prikazana u zasebnom okviru, zajedno s opisom.
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Datoteka.ogg]]</nowiki></code>''' za direktno povezivanje s datotekom bez njenog prikazivanja",
'upload-permitted'            => 'Dozvoljene vrste datoteka: $1.',
'upload-preferred'            => 'Poželjne vrste datoteka: $1.',
'upload-prohibited'           => 'Zabranjene vrste datoteka: $1.',
'uploadlog'                   => 'dnevnik otpremanja',
'uploadlogpage'               => 'Dnevnik otpremanja',
'uploadlogpagetext'           => 'Ispod je spisak skorašnjih slanja.
Pogledajte [[Special:NewFiles|galeriju novih datoteka]] za lepši pregled.',
'filename'                    => 'Naziv datoteke',
'filedesc'                    => 'Opis',
'fileuploadsummary'           => 'Opis:',
'filereuploadsummary'         => 'Izmene datoteke:',
'filestatus'                  => 'Status autorskog prava:',
'filesource'                  => 'Izvor:',
'uploadedfiles'               => 'Poslate datoteke',
'ignorewarning'               => 'Zanemari upozorenja i sačuvaj datoteku',
'ignorewarnings'              => 'Zanemari sva upozorenja',
'minlength1'                  => 'Naziv datoteke mora imati barem jedan znak.',
'illegalfilename'             => 'Datoteka „$1“ sadrži znakove koji nisu dozvoljeni u nazivima stranica.
Promenite naziv datoteke i ponovo je pošaljite.',
'filename-toolong'            => 'Nazivi datoteka mogu imati najviše 240 bajtova.',
'badfilename'                 => 'Naziv datoteke je promenjen u „$1“.',
'filetype-mime-mismatch'      => 'Ekstenzija „.$1“ ne odgovara prepoznatoj vrsti MIME datoteke ($2).',
'filetype-badmime'            => 'Datoteke MIME vrste „$1“ nije dozvoljeno slati.',
'filetype-bad-ie-mime'        => 'Ova datoteka se ne može poslati zato što bi je Internet eksplorer uočio kao „$1“, a to je zabranjena i opasna vrsta datoteke.',
'filetype-unwanted-type'      => '„.$1“ je nepoželjna vrsta datoteke.
{{PLURAL:$3|Poželjna vrsta datoteke je|Poželjne vrste datoteka su}} $2.',
'filetype-banned-type'        => '\'\'\'".$1"\'\'\' {{PLURAL:$4|je zabranjena vrsta datoteke|su zabranjene vrste datoteka}}.
{{PLURAL:$3|Dozvoljena vrsta datoteke je|Dozvoljene vrste datoteka su}} $2.',
'filetype-missing'            => 'Ova datoteka nema ekstenziju.',
'empty-file'                  => 'Poslata datoteka je prazna.',
'file-too-large'              => 'Poslata datoteka je prevelika.',
'filename-tooshort'           => 'Naziv datoteke je prekratak.',
'filetype-banned'             => 'Vrsta datoteke je zabranjena.',
'verification-error'          => 'Ova datoteka nije prošla proveru.',
'hookaborted'                 => 'Izmena je odbačena od kuke za proširenja.',
'illegal-filename'            => 'Naziv datoteke je zabranjen.',
'overwrite'                   => 'Zamenjivanje postojeće datoteke je zabranjeno.',
'unknown-error'               => 'Došlo je do nepoznate greške.',
'tmp-create-error'            => 'Ne mogu da napravim privremenu datoteku.',
'tmp-write-error'             => 'Greška pri pisanju privremene datoteke.',
'large-file'                  => 'Preporučljivo je da datoteke ne budu veće od $1; ova datoteka je $2.',
'largefileserver'             => 'Ova datoteka prelazi ograničenje veličine.',
'emptyfile'                   => 'Datoteka koju ste poslali je prazna.
Uzrok može biti greška u nazivu datoteke.
Proverite da li zaista želite da je pošaljete.',
'windows-nonascii-filename'   => 'Ovaj viki ne podržava nazive datoteka s posebnim znacima.',
'fileexists'                  => 'Datoteka s ovim nazivom već postoji. Pogledajte <strong>[[:$1]]</strong> ako niste sigurni da li želite da je promenite.
[[$1|thumb]]',
'filepageexists'              => 'Stranica s opisom ove datoteke je već napravljena ovde <strong>[[:$1]]</strong>, iako datoteka ne postoji.
Opis koji ste naveli se neće pojaviti na stranici s opisom.
Da bi se vaš opis ovde našao, potrebno je da ga ručno izmenite.
[[$1|thumb]]',
'fileexists-extension'        => 'Datoteka sa sličnim nazivom već postoji: [[$2|thumb]]
* Naziv datoteke koju šaljete: <strong>[[:$1]]</strong>
* Naziv postojeće datoteke: <strong>[[:$2]]</strong>
Izaberite drugačiji naziv.',
'fileexists-thumbnail-yes'    => "Izgleda da je datoteka umanjeno izdanje slike ''(thumbnail)''.
[[$1|thumb]]
Proverite datoteku <strong>[[:$1]]</strong>.
Ako je proverena datoteka ista slika originalne veličine, nije potrebno slati dodatnu sliku.",
'file-thumbnail-no'           => "Datoteka počinje sa <strong>$1</strong>.
Izgleda da se radi o umanjenoj slici ''(thumbnail)''.
Ukoliko imate ovu sliku u punoj veličini, pošaljite je, a ako nemate, promenite naziv datoteke.",
'fileexists-forbidden'        => 'Datoteka s ovim nazivom već postoji i ne može se zameniti.
Ako i dalje želite da pošaljete datoteku, vratite se i izaberite drugi naziv.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Datoteka s ovim nazivom već postoji u zajedničkom skladištu.
Vratite se i pošaljite datoteku s drugim nazivom.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Ovo je duplikat {{PLURAL:$1|sledeće datoteke|sledećih datoteka}}:',
'file-deleted-duplicate'      => 'Datoteka istovetna ovoj ([[:$1]]) je prethodno obrisana.
Pogledajte istoriju brisanja pre ponovnog slanja.',
'uploadwarning'               => 'Upozorenje pri otpremanju',
'uploadwarning-text'          => 'Izmenite opis datoteke i pokušajte ponovo.',
'savefile'                    => 'Sačuvaj datoteku',
'uploadedimage'               => '{{GENDER:|je poslao|je poslala|je poslao}} „[[$1]]“',
'overwroteimage'              => '{{GENDER:|je poslao|je poslala|je poslao}} novo izdanje „[[$1]]“',
'uploaddisabled'              => 'Otpremanje je onemogućeno.',
'copyuploaddisabled'          => 'Slanje putem URL adrese je onemogućeno.',
'uploadfromurl-queued'        => 'Slanje je stavljeno na spisak čekanja.',
'uploaddisabledtext'          => 'Otpremanje datoteka je onemogućeno.',
'php-uploaddisabledtext'      => 'Slanje datoteka je onemogućeno u PHP-u.
Proverite postavke file_uploads.',
'uploadscripted'              => 'Datoteka sadrži HTML ili skriptni kod koji može biti pogrešno protumačen od strane pregledača.',
'uploadvirus'                 => 'Datoteka sadrži virus!
Detalji: $1',
'uploadjava'                  => 'Datoteka je formata ZIP koji sadrži java .class element.
Slanje java datoteka nije dozvoljeno jer one mogu izazvati zaobilaženje sigurnosnih ograničenja.',
'upload-source'               => 'Izvorna datoteka',
'sourcefilename'              => 'Naziv izvorne datoteke:',
'sourceurl'                   => 'Adresa izvora:',
'destfilename'                => 'Naziv:',
'upload-maxfilesize'          => 'Najveća veličina datoteke: $1',
'upload-description'          => 'Opis datoteke',
'upload-options'              => 'Postavke slanja',
'watchthisupload'             => 'Nadgledaj ovu datoteku',
'filewasdeleted'              => 'Datoteka s ovim nazivom je ranije poslata, ali je obrisana.
Proverite $1 pre nego što nastavite s ponovnim slanjem.',
'filename-bad-prefix'         => "Naziv datoteke koju šaljete počinje sa '''\"\$1\"''', a njega obično dodeljuju digitalni fotoaparati.
Izaberite naziv datoteke koji opisuje njen sadržaj.",
'filename-prefix-blacklist'   => ' #<!-- ostavite ovaj red onakvim kakav jeste --> <pre>
# Sintaksa je sledeća:
#   * Sve od tarabe pa do kraja reda je komentar
#   * Svaki red označava prefiks tipičnih naziva datoteka koje dodeljivaju digitalni aparati
CIMG # Kasio
DSC_ # Nikon
DSCF # Fudži
DSCN # Nikon
DUW # neki mobilni telefoni
IMG # opšte
JD # Dženoptik
MGP # Pentaks
PICT # razno
 #</pre> <!-- ostavite ovaj red onakvim kakav jeste -->',
'upload-success-subj'         => 'Uspešno otpremanje',
'upload-success-msg'          => 'Datoteka iz [$2] je poslata. Dostupna je ovde: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Greška pri otpremanju',
'upload-failure-msg'          => 'Došlo je do problema pri slanju iz [$2]:

$1',
'upload-warning-subj'         => 'Upozorenje pri otpremanju',
'upload-warning-msg'          => 'Došlo je do greške pri slanju iz [$2]. Vratite se na [[Special:Upload/stash/$1|stranicu za slanje datoteka]] da biste rešili problem.',

'upload-proto-error'        => 'Neispravan protokol',
'upload-proto-error-text'   => 'Slanje sa spoljne lokacije zahteva adresu koja počinje sa <code>http://</code> ili <code>ftp://</code>.',
'upload-file-error'         => 'Unutrašnja greška',
'upload-file-error-text'    => 'Došlo je do unutrašnje greške pri otvaranju privremene datoteke na serveru.
Kontaktirajte [[Special:ListUsers/sysop|administratora]].',
'upload-misc-error'         => 'Nepoznata greška pri slanju datoteke',
'upload-misc-error-text'    => 'Nepoznata greška pri slanju datoteke.
Proverite da li je adresa ispravna i pokušajte ponovo.
Ako se problem ne reši, kontaktirajte [[Special:ListUsers/sysop|administratora]].',
'upload-too-many-redirects' => 'Adresa sadrži previše preusmerenja',
'upload-unknown-size'       => 'Nepoznata veličina',
'upload-http-error'         => 'Došlo je do HTTP greške: $1',

# File backend
'backend-fail-stream'        => 'Ne mogu da emitujem datoteku $1.',
'backend-fail-backup'        => 'Ne mogu da napravim rezervu datoteke $1.',
'backend-fail-notexists'     => 'Datoteka $1 ne postoji.',
'backend-fail-hashes'        => 'Ne mogu da dobijem disperzije datoteke za upoređivanje.',
'backend-fail-notsame'       => 'Već postoji neistovetna datoteka – $1.',
'backend-fail-invalidpath'   => '$1 nije ispravna putanja za skladištenje.',
'backend-fail-delete'        => 'Ne mogu da obrišem datoteku $1.',
'backend-fail-alreadyexists' => 'Datoteka $1 već postoji.',
'backend-fail-store'         => 'Ne mogu da smestim datoteku $1 u $2.',
'backend-fail-copy'          => 'Ne mogu da umnožim datoteku $1 u $2.',
'backend-fail-move'          => 'Ne mogu da premestim datoteku $1 u $2.',
'backend-fail-opentemp'      => 'Ne mogu da otvorim privremenu datoteku.',
'backend-fail-writetemp'     => 'Ne mogu da pišem u privremenoj datoteci.',
'backend-fail-closetemp'     => 'Ne mogu da zatvorim privremenu datoteku.',
'backend-fail-read'          => 'Ne mogu da pročitam datoteku $1.',
'backend-fail-create'        => 'Ne mogu da zapišem datoteku $1.',
'backend-fail-readonly'      => 'Skladišna osnova „$1“ trenutno ne može da se zapisuje. Navedeni razlog glasi: „$2“',
'backend-fail-synced'        => 'Datoteka „$1“ je nedosledna između unutrašnjih skladišnih osnova',
'backend-fail-connect'       => 'Ne mogu da se povežem sa skladišnom osnovom „$1“.',
'backend-fail-internal'      => 'Došlo je do nepoznate greške u skladišnoj osnovi „$1“.',
'backend-fail-contenttype'   => 'Ne mogu da utvrdim kakav sadržaj ima datoteka koju treba da smestim u „$1“.',
'backend-fail-batchsize'     => 'Skladišna osnova je dobila blokadu od $1 {{PLURAL:$1|operacije|operacije|operacija}}; ograničenje je $2 {{PLURAL:$2|operacija|operacije|operacija}}.',

# Lock manager
'lockmanager-notlocked'        => 'Ne mogu da otključam „$1“ jer nije zaključan.',
'lockmanager-fail-closelock'   => 'Ne mogu da zatvorim katanac za „$1“.',
'lockmanager-fail-deletelock'  => 'Ne mogu da obrišem katanac za „$1“.',
'lockmanager-fail-acquirelock' => 'Ne mogu da dobijem katanac za „$1“.',
'lockmanager-fail-openlock'    => 'Ne mogu da otvorim katanac za „$1“.',
'lockmanager-fail-releaselock' => 'Ne mogu da oslobodim katanac za „$1“.',
'lockmanager-fail-db-bucket'   => 'Ne mogu da kontaktiram s dovoljno katanaca u kanti $1.',
'lockmanager-fail-db-release'  => 'Ne mogu da oslobodim katance u bazi $1.',
'lockmanager-fail-svr-release' => 'Ne mogu da oslobodim katance na serveru $1.',

# ZipDirectoryReader
'zip-file-open-error' => 'Došlo je do greške pri otvaranju datoteke za proveru ZIP arhive.',
'zip-wrong-format'    => 'Navedena datoteka nije formata ZIP.',
'zip-bad'             => 'Datoteka je oštećena ili je nečitljiva ZIP datoteka.
Bezbednosna provera ne može da se izvrši kako treba.',
'zip-unsupported'     => 'Datoteka je formata ZIP koji koristi mogućnosti koje ne podržava Medijaviki.
Bezbednosna provera ne može da se izvrši kako treba.',

# Special:UploadStash
'uploadstash'          => 'Tajno skladište',
'uploadstash-summary'  => 'Ova stranica pruža pristup datotekama koje su poslate (ili se šalju), ali još nisu objavljene. Ove datoteke su vidljive samo korisniku koji ga je poslao.',
'uploadstash-clear'    => 'Očisti sakrivene datoteke',
'uploadstash-nofiles'  => 'Nemate sakrivene datoteke.',
'uploadstash-badtoken' => 'Izvršavanje date radnje nije uspelo. Razlog tome može biti istek vremena za uređivanje. Pokušajte ponovo.',
'uploadstash-errclear' => 'Čišćenje datoteka nije uspelo.',
'uploadstash-refresh'  => 'Osveži spisak datoteka',
'invalid-chunk-offset' => 'Neispravna polazna tačka',

# img_auth script messages
'img-auth-accessdenied'     => 'Pristup je odbijen',
'img-auth-nopathinfo'       => 'Nedostaje PATH_INFO.
Vaš server nije podešen da prosleđuje ovakve podatke.
Možda je zasnovan na CGI-ju koji ne podržava img_auth.
Pogledajte https://www.mediawiki.org/wiki/Manual:Image_Authorization?uselang=sr-ec.',
'img-auth-notindir'         => 'Zahtevana putanja nije u podešenoj fascikli za slanje.',
'img-auth-badtitle'         => 'Ne mogu da stvorim ispravan naslov za „$1“.',
'img-auth-nologinnWL'       => 'Niste prijavljeni i „$1“ nije na spisku dozvoljenih.',
'img-auth-nofile'           => 'Datoteka „$1“ ne postoji.',
'img-auth-isdir'            => 'Pokušavate da pristupite fascikli „$1“.
Dozvoljen je samo pristup datotekama.',
'img-auth-streaming'        => 'Učitavanje „$1“.',
'img-auth-public'           => 'Svrha img_auth.php je da prosleđuje datoteke iz privatnih vikija.
Ovaj viki je postavljen kao javni.
Radi sigurnosti, img_auth.php je onemogućen.',
'img-auth-noread'           => 'Korisnik nema pristup za čitanje „$1“.',
'img-auth-bad-query-string' => 'Adresa ima neispravnu nisku upita.',

# HTTP errors
'http-invalid-url'      => 'Neispravna adresa: $1',
'http-invalid-scheme'   => 'Adrese sa šemom „$1“ nisu podržane.',
'http-request-error'    => 'HTTP zahtev nije prošao zbog nepoznate greške.',
'http-read-error'       => 'HTTP greška pri čitanju.',
'http-timed-out'        => 'Zahtev HTTP je istekao.',
'http-curl-error'       => 'Greška pri otvaranju adrese: $1',
'http-host-unreachable' => 'Ne mogu da pristupim adresi.',
'http-bad-status'       => 'Došlo je do problema tokom zahteva HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Ne mogu da pristupim adresi',
'upload-curl-error6-text'  => 'Ne mogu da pristupim navedenoj adresi.
Proverite da li je adresa ispravna i dostupna.',
'upload-curl-error28'      => 'Slanje je isteklo',
'upload-curl-error28-text' => 'Server ne odgovara na upit.
Proverite da li sajt radi, malo osačekajte i pokušajte ponovo.
Probajte kasnije kada bude manje opterećenje.',

'license'            => 'Licenca:',
'license-header'     => 'Licenca:',
'nolicense'          => 'nije izabrano',
'license-nopreview'  => '(pregled nije dostupan)',
'upload_source_url'  => ' (ispravna i javno dostupna adresa)',
'upload_source_file' => ' (datoteka na vašem računaru)',

# Special:ListFiles
'listfiles-summary'     => 'Ova posebna stranica prikazuje sve poslate datoteke.
Kad je poređano po korisniku, popis prikazuje samo one datoteke čije je poslednje izdanje postavio taj korisnik.',
'listfiles_search_for'  => 'Naziv datoteke:',
'imgfile'               => 'datoteka',
'listfiles'             => 'Spisak datoteka',
'listfiles_thumb'       => 'Minijatura',
'listfiles_date'        => 'Datum',
'listfiles_name'        => 'Naziv',
'listfiles_user'        => 'Korisnik',
'listfiles_size'        => 'Veličina',
'listfiles_description' => 'Opis',
'listfiles_count'       => 'Verzije',

# File description page
'file-anchor-link'                  => 'Datoteka',
'filehist'                          => 'Istorija datoteke',
'filehist-help'                     => 'Kliknite na datum/vreme da vidite tadašnje izdanje datoteke.',
'filehist-deleteall'                => 'obriši sve',
'filehist-deleteone'                => 'obriši',
'filehist-revert'                   => 'vrati',
'filehist-current'                  => 'trenutno',
'filehist-datetime'                 => 'Datum/vreme',
'filehist-thumb'                    => 'Minijatura',
'filehist-thumbtext'                => 'Umanjeni prikaz za izdanje od $1',
'filehist-nothumb'                  => 'Nema minijature',
'filehist-user'                     => 'Korisnik',
'filehist-dimensions'               => 'Dimenzije',
'filehist-filesize'                 => 'Veličina datoteke',
'filehist-comment'                  => 'Komentar',
'filehist-missing'                  => 'Datoteka nedostaje',
'imagelinks'                        => 'Upotreba datoteke',
'linkstoimage'                      => '{{PLURAL:$1|Sledeća stranica koristi|$1 sledeće stranice koriste|$1 sledećih stranica koristi}} ovu datoteku:',
'linkstoimage-more'                 => 'Više od $1 {{PLURAL:$1|stranice|stranice|stranica}} je povezano s ovom datotekom.
Sledeći spisak prikazuje samo {{PLURAL:$1|prvu stranicu povezanu|prve $1 stranice povezane|prvih $1 stranica povezanih}} s ovom datotekom.
Dostupan je i [[Special:WhatLinksHere/$2|potpuni spisak]].',
'nolinkstoimage'                    => 'Nema stranica koje koriste ovu datoteku.',
'morelinkstoimage'                  => 'Pogledajte [[Special:WhatLinksHere/$1|više veza]] do ove datoteke.',
'linkstoimage-redirect'             => '$1 (preusmerenje datoteke) $2',
'duplicatesoffile'                  => '{{PLURAL:$1|Sledeća datoteka je duplikat|Sledeće $1 datoteke su duplikati|Sledećih $1 datoteka su duplikati}} ove datoteke ([[Special:FileDuplicateSearch/$2|detaljnije]]):',
'sharedupload'                      => 'Ova datoteka se nalazi na $1 i može se koristiti i na drugim projektima.',
'sharedupload-desc-there'           => 'Ova datoteka se nalazi na $1 i može se koristiti i na drugim projektima.
Pogledajte [$2 stranicu za opis datoteke] za više detalja o njoj.',
'sharedupload-desc-here'            => 'Ova datoteka se nalazi na $1 i može da se koristi na drugim projektima.
Opis njene [$2 stranice za opis] je prikazan ispod.',
'filepage-nofile'                   => 'Ne postoji datoteka s ovim nazivom.',
'filepage-nofile-link'              => 'Ne postoji datoteka s ovim nazivom, ali je možete [$1 poslati].',
'uploadnewversion-linktext'         => 'Pošalji novo izdanje ove datoteke',
'shared-repo-from'                  => 'iz $1',
'shared-repo'                       => 'zajedničko skladište',
'shared-repo-name-wikimediacommons' => 'Vikimedijina ostava',
'filepage.css'                      => '/* CSS koji je postavljen ovde se nalazi na stranicama za opis datoteka, kao i na stranim vikijima */',

# File reversion
'filerevert'                => 'Vrati $1',
'filerevert-legend'         => 'Vrati datoteku',
'filerevert-intro'          => "Vraćate datoteku '''[[Media:$1|$1]]''' na [$4 izdanje od $2; $3].",
'filerevert-comment'        => 'Razlog:',
'filerevert-defaultcomment' => 'Vraćeno na izdanje od $1; $2',
'filerevert-submit'         => 'Vrati',
'filerevert-success'        => "Datoteka '''[[Media:$1|$1]]''' je vraćena na [$4 izdanje od $2; $3].",
'filerevert-badversion'     => 'Ne postoji ranije lokalno izdanje datoteke s navedenim vremenskim podacima.',

# File deletion
'filedelete'                   => 'Obriši $1',
'filedelete-legend'            => 'Obriši datoteku',
'filedelete-intro'             => "Brišete datoteku '''[[Media:$1|$1]]''' zajedno s njenom istorijom.",
'filedelete-intro-old'         => "Brišete verziju datoteke '''[[Media:$1|$1]]''' od [$4 $2; $3].",
'filedelete-comment'           => 'Razlog:',
'filedelete-submit'            => 'Obriši',
'filedelete-success'           => "Datoteka '''$1''' je obrisana.",
'filedelete-success-old'       => "Izdanje '''[[Media:$1|$1]]''' od $2, $3 je obrisano.",
'filedelete-nofile'            => "Datoteka '''$1''' ne postoji.",
'filedelete-nofile-old'        => "Ne postoji arhivirano izdanje datoteke '''$1''' s navedenim osobinama.",
'filedelete-otherreason'       => 'Drugi/dodatni razlog:',
'filedelete-reason-otherlist'  => 'Drugi razlog',
'filedelete-reason-dropdown'   => '*Najčešći razlozi brisanja
** Kršenje autorskih prava
** Duplikati datoteka',
'filedelete-edit-reasonlist'   => 'Uredi razloge brisanja',
'filedelete-maintenance'       => 'Brisanje i vraćanje datoteka je privremeno onemogućeno tokom održavanja.',
'filedelete-maintenance-title' => 'Ne mogu da obrišem datoteku',

# MIME search
'mimesearch'         => 'MIME pretraga',
'mimesearch-summary' => 'Ova stranica omogućava filtriranje datoteka prema njihovim vrstama MIME.
Ulazni podaci: contenttype/subtype, npr. <code>image/jpeg</code>.',
'mimetype'           => 'MIME vrsta:',
'download'           => 'preuzmi',

# Unwatched pages
'unwatchedpages' => 'Nenadgledane stranice',

# List redirects
'listredirects' => 'Spisak preusmerenja',

# Unused templates
'unusedtemplates'     => 'Nekorišćeni šabloni',
'unusedtemplatestext' => 'Ova stranica navodi sve stranice u imenskom prostoru {{ns:template}} koje nisu uključene ni na jednoj drugoj stranici.
Pre brisanja proverite da li druge stranice vode do tih šablona.',
'unusedtemplateswlh'  => 'ostale veze',

# Random page
'randompage'         => 'Slučajna stranica',
'randompage-nopages' => 'Nema stranica u {{PLURAL:$2|sledećem imenskom prostoru|sledećim imenskim prostorima}}: $1.',

# Random redirect
'randomredirect'         => 'Slučajno preusmerenje',
'randomredirect-nopages' => 'Nema preusmerenja u imenskom prostoru „$1”.',

# Statistics
'statistics'                   => 'Statistike',
'statistics-header-pages'      => 'Stranice',
'statistics-header-edits'      => 'Izmene',
'statistics-header-views'      => 'Pregledi',
'statistics-header-users'      => 'Korisnici',
'statistics-header-hooks'      => 'Ostalo',
'statistics-articles'          => 'Stranice sa sadržajem',
'statistics-pages'             => 'Stranica',
'statistics-pages-desc'        => 'Sve stranice na vikiju, uključujući stranice za razgovor, preusmerenja itd.',
'statistics-files'             => 'Otpremljeno datoteka',
'statistics-edits'             => 'Broj izmena stranica otkad postoji {{SITENAME}}',
'statistics-edits-average'     => 'Prosečan broj izmena po stranici',
'statistics-views-total'       => 'Ukupno pregleda',
'statistics-views-total-desc'  => 'Pregledi nepostojećih i posebnih stranica nisu uključeni',
'statistics-views-peredit'     => 'Pregleda po izmeni',
'statistics-users'             => 'Upisani korisnici ([[Special:ListUsers|spisak članova]])',
'statistics-users-active'      => 'Aktivni korisnici',
'statistics-users-active-desc' => 'Korisnici koji su izvršili bar jednu radnju {{PLURAL:$1|prethodni dan|u poslednja $1 dana|u poslednjih $1 dana}}',
'statistics-mostpopular'       => 'Najposećenije stranice',

'disambiguations'      => 'Stranice do višeznačnih odrednica',
'disambiguationspage'  => 'Template:Višeznačna odrednica',
'disambiguations-text' => "Sledeće stranice sadrže bar jednu vezu do '''višeznačne odrednice'''.
Umesto toga, valjalo bi da vode do odgovarajuće teme.
Stranica se smatra višeznačnom odrednicom ako koristi šablon koji vodi od [[MediaWiki:Disambiguationspage]].",

'doubleredirects'                   => 'Dvostruka preusmerenja',
'doubleredirectstext'               => 'Ova stranica prikazuje stranice koje preusmeravaju na druga preusmerenja.
Svaki red sadrži veze prema prvom i drugom preusmerenju, kao i odredišnu stranicu drugog preusmerenja koja je obično „pravi“ članak na koga prvo preusmerenje treba da upućuje.
<del>Precrtani</del> unosi su već rešeni.',
'double-redirect-fixed-move'        => '[[$1]] je premešten.
Sada je preusmerenje na [[$2]].',
'double-redirect-fixed-maintenance' => 'Ispravljanje dvostrukih preusmerenja iz [[$1]] u [[$2]].',
'double-redirect-fixer'             => 'Ispravljač preusmerenja',

'brokenredirects'        => 'Pokvarena preusmerenja',
'brokenredirectstext'    => 'Sledeća preusmerenja upućuju na nepostojeće stranice:',
'brokenredirects-edit'   => 'uredi',
'brokenredirects-delete' => 'obriši',

'withoutinterwiki'         => 'Stranice bez jezičkih veza',
'withoutinterwiki-summary' => 'Sledeće stranice nisu povezane s drugim jezicima.',
'withoutinterwiki-legend'  => 'Prefiks',
'withoutinterwiki-submit'  => 'Prikaži',

'fewestrevisions' => 'Stranice s najmanje izmena',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bajt|bajta|bajtova}}',
'ncategories'             => '$1 {{PLURAL:$1|kategorija|kategorije|kategorija}}',
'nlinks'                  => '$1 {{PLURAL:$1|veza|veze|veza}}',
'nmembers'                => '$1 {{PLURAL:$1|član|člana|članova}}',
'nrevisions'              => '$1 {{PLURAL:$1|izmena|izmene|izmena}}',
'nviews'                  => '$1 {{PLURAL:$1|pregled|pregleda|pregleda}}',
'nimagelinks'             => 'Koristi se na $1 {{PLURAL:$1|stranici|stranice|stranica}}',
'ntransclusions'          => 'koristi se na $1 {{PLURAL:$1|stranici|stranice|stranica}}',
'specialpage-empty'       => 'Nema rezultata za ovaj izveštaj.',
'lonelypages'             => 'Siročići',
'lonelypagestext'         => 'Sledeće stranice nisu povezane s drugim stranicama, niti su uključene transkluzijom u druge stranice.',
'uncategorizedpages'      => 'Nekategorisane stranice',
'uncategorizedcategories' => 'Nekategorisane stranice',
'uncategorizedimages'     => 'Datoteke bez kategorija',
'uncategorizedtemplates'  => 'Nekategorisani šabloni',
'unusedcategories'        => 'Nekorišćene kategorije',
'unusedimages'            => 'Nekorišćene datoteke',
'popularpages'            => 'Popularne stranice',
'wantedcategories'        => 'Tražene kategorije',
'wantedpages'             => 'Tražene stranice',
'wantedpages-badtitle'    => 'Neispravan naslov u nizu rezultata: $1',
'wantedfiles'             => 'Tražene datoteke',
'wantedfiletext-cat'      => 'Sledeće datoteke se koriste, ali ne postoje. Datoteke iz drugih riznica mogu biti navedene iako ne postoje. Takve datoteke će biti <del>poništene</del> sa spiska. Pored toga, stranice koje sadrže nepostojeće datoteke se nalaze [[:$1|ovde]].',
'wantedfiletext-nocat'    => 'Sledeće datoteke se koriste, ali ne postoje. Datoteke iz drugih riznica mogu biti navedene iako ne postoje. Takve datoteke će biti <del>poništene</del> sa spiska.',
'wantedtemplates'         => 'Traženi šabloni',
'mostlinked'              => 'Stranice s najviše veza',
'mostlinkedcategories'    => 'Članci s najviše kategorija',
'mostlinkedtemplates'     => 'Šabloni s najviše veza',
'mostcategories'          => 'Članci s najviše kategorija',
'mostimages'              => 'Datoteke s najviše veza',
'mostrevisions'           => 'Stranice s najviše izmena',
'prefixindex'             => 'Sve stranice s prefiksom',
'prefixindex-namespace'   => 'Sve stranice s predmetkom (imenski prostor $1)',
'shortpages'              => 'Kratke stranice',
'longpages'               => 'Dugačke stranice',
'deadendpages'            => 'Stranice bez unutrašnjih veza',
'deadendpagestext'        => 'Sledeće stranice nemaju veze do drugih stranica na ovom vikiju.',
'protectedpages'          => 'Zaštićene stranice',
'protectedpages-indef'    => 'samo neograničene zaštite',
'protectedpages-cascade'  => 'samo prenosive zaštite',
'protectedpagestext'      => 'Sledeće stranice su zaštićene od premeštanja ili uređivanja',
'protectedpagesempty'     => 'Nema zaštićenih stranica s ovim parametrima.',
'protectedtitles'         => 'Zaštićeni naslovi',
'protectedtitlestext'     => 'Sledeći naslovi su zaštićeni od stvaranja',
'protectedtitlesempty'    => 'Nema zaštićenih naslova s ovim parametrima.',
'listusers'               => 'Spisak korisnika',
'listusers-editsonly'     => 'prikaži samo korisnike koji su uređivali',
'listusers-creationsort'  => 'poređaj po datumu stvaranja',
'usereditcount'           => '$1 {{PLURAL:$1|izmena|izmene|izmena}}',
'usercreated'             => '{{GENDER:$3|je napravio|je napravila|je napravio}} dana $1 u $2',
'newpages'                => 'Nove stranice',
'newpages-username'       => 'Korisničko ime:',
'ancientpages'            => 'Najstarije stranice',
'move'                    => 'premesti',
'movethispage'            => 'Premesti ovu stranicu',
'unusedimagestext'        => 'Sledeće datoteke postoje, ali nisu ugrađene ni u jednu stranicu.
Druge veb stranice mogu koristiti sliku preko direktne adrese, tako da i pored toga mogu biti prikazane ovde pored aktivne upotrebe.',
'unusedcategoriestext'    => 'Sledeće stranice kategorija postoje iako ih nijedan drugi članak ili kategorija ne koriste.',
'notargettitle'           => 'Nema odredišta',
'notargettext'            => 'Niste naveli odredišnu stranicu ili korisnika na kome bi se izvela ova radnja.',
'nopagetitle'             => 'Ne postoji takva stranica',
'nopagetext'              => 'Tražena stranica ne postoji.',
'pager-newer-n'           => '{{PLURAL:$1|noviji 1|novija $1|novijih $1}}',
'pager-older-n'           => '{{PLURAL:$1|stariji 1|starija $1|starijih $1}}',
'suppress'                => 'Nadzor',
'querypage-disabled'      => 'Ova posebna stranica je onemogućena radi poboljšanja performansi.',

# Book sources
'booksources'               => 'Štampani izvori',
'booksources-search-legend' => 'Traženje izvora knjige',
'booksources-isbn'          => 'ISBN:',
'booksources-go'            => 'Idi',
'booksources-text'          => 'Ispod se nalazi spisak veza ka sajtovima koji se bave prodajom novih i polovnih knjiga, a koji bi mogli imati dodatne podatke o knjigama koje tražite:',
'booksources-invalid-isbn'  => 'Navedeni ISBN broj nije ispravan. Proverite da nije došlo do greške pri umnožavanju iz prvobitnog izvora.',

# Special:Log
'specialloguserlabel'  => 'Izvršilac:',
'speciallogtitlelabel' => 'Cilj (naslov ili korisnik):',
'log'                  => 'Dnevnici',
'all-logs-page'        => 'Svi javni dnevnici',
'alllogstext'          => 'Skupni prikaz svih dostupnih istorija ovog vikija.
Možete suziti prikaz odabirući vrstu istorije, korisničkog imena ili tražene stranice.',
'logempty'             => 'Nema pronađenih stavki u istoriji.',
'log-title-wildcard'   => 'traži naslove koji počinju s ovim tekstom',

# Special:AllPages
'allpages'          => 'Sve stranice',
'alphaindexline'    => '$1 do $2',
'nextpage'          => 'Sledeća stranica ($1)',
'prevpage'          => 'Prethodna stranica ($1)',
'allpagesfrom'      => 'Prikaži stranice počev od:',
'allpagesto'        => 'Prikaži stranice završno sa:',
'allarticles'       => 'Sve stranice',
'allinnamespace'    => 'Sve stranice (imenski prostor $1)',
'allnotinnamespace' => 'Sve stranice van imenskog prostora $1',
'allpagesprev'      => 'Prethodna',
'allpagesnext'      => 'Sledeća',
'allpagessubmit'    => 'Idi',
'allpagesprefix'    => 'Prikaži stranice s prefiksom:',
'allpagesbadtitle'  => 'Navedeni naziv stranice nije ispravan ili sadrži međujezički ili međuviki prefiks.
Možda sadrži znakove koji se ne mogu koristiti u naslovima.',
'allpages-bad-ns'   => '{{SITENAME}} nema imenski prostor „$1“.',

# Special:Categories
'categories'                    => 'Kategorije',
'categoriespagetext'            => '{{PLURAL:$1|Sledeća kategorija sadrži|Sledeće kategorije sadrže}} stranice ili datoteke.
[[Special:UnusedCategories|Nekorišćene kategorije]] nisu prikazane ovde.
Pogledajte i [[Special:WantedCategories|tražene kategorije]].',
'categoriesfrom'                => 'Prikaži kategorije počev od:',
'special-categories-sort-count' => 'poređaj po broju',
'special-categories-sort-abc'   => 'poređaj po azbučnom redu',

# Special:DeletedContributions
'deletedcontributions'             => 'Obrisani korisnički doprinosi',
'deletedcontributions-title'       => 'Obrisani korisnički doprinosi',
'sp-deletedcontributions-contribs' => 'doprinosi',

# Special:LinkSearch
'linksearch'       => 'Pretraga spoljnih veza',
'linksearch-pat'   => 'Obrazac pretrage:',
'linksearch-ns'    => 'Imenski prostor:',
'linksearch-ok'    => 'Pretraži',
'linksearch-text'  => 'Mogu se koristiti džokeri poput „*.wikipedia.org“.<br />
Potreban je najviši domen, kao „*.org“.<br />
Podržani protokoli: <code>$1</code> (zadaje http:// ako ne navedete protokol).',
'linksearch-line'  => '$1 veza u $2',
'linksearch-error' => 'Džokeri se mogu pojaviti samo na početku adrese.',

# Special:ListUsers
'listusersfrom'      => 'Prikaži korisnike počev od:',
'listusers-submit'   => 'Prikaži',
'listusers-noresult' => 'Korisnik nije pronađen.',
'listusers-blocked'  => '({{GENDER:$1|blokiran|blokirana|blokiran}})',

# Special:ActiveUsers
'activeusers'            => 'Spisak aktivnih korisnika',
'activeusers-intro'      => 'Ovo je spisak korisnika koji su bili aktivni {{PLURAL:$1|prethodni dan|u poslednja $1 dana|u poslednjih $1 dana}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|izmena|izmene|izmena}} {{PLURAL:$3|prethodni dan|u poslednja $3 dana|u poslednjih $3 dana}}',
'activeusers-from'       => 'Prikaži korisnike počev od:',
'activeusers-hidebots'   => 'Sakrij botove',
'activeusers-hidesysops' => 'Sakrij administratore',
'activeusers-noresult'   => 'Korisnik nije pronađen.',

# Special:Log/newusers
'newuserlogpage'     => 'Dnevnik novih korisnika',
'newuserlogpagetext' => 'Ovo je istorija novih korisnika.',

# Special:ListGroupRights
'listgrouprights'                      => 'Prava korisničkih grupa',
'listgrouprights-summary'              => 'Sledi spisak korisničkih grupa na ovom vikiju, zajedno s pravima pristupa.
Pogledajte [[{{MediaWiki:Listgrouprights-helppage}}|više detalja]] o pojedinačnim pravima.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Dodeljeno pravo</span>
* <span class="listgrouprights-revoked">Ukinuto pravo</span>',
'listgrouprights-group'                => 'Grupa',
'listgrouprights-rights'               => 'Prava',
'listgrouprights-helppage'             => 'Help:Prava grupe',
'listgrouprights-members'              => '(spisak članova)',
'listgrouprights-right-display'        => '<span class="listgrouprights-granted">$1 <code>($2)</code></span>',
'listgrouprights-right-revoked'        => '<span class="listgrouprights-revoked">$1 <code>($2)</code></span>',
'listgrouprights-addgroup'             => 'dodaje {{PLURAL:$2|sledeću grupu|sledeće grupe}}: $1',
'listgrouprights-removegroup'          => 'briše {{PLURAL:$2|sledeću grupu|sledeće grupe}}: $1',
'listgrouprights-addgroup-all'         => 'dodavanje svih grupa',
'listgrouprights-removegroup-all'      => 'brisanje svih grupa',
'listgrouprights-addgroup-self'        => 'dodavanje {{PLURAL:$2|grupe|grupa}} na svoj nalog: $1',
'listgrouprights-removegroup-self'     => 'uklanjanje {{PLURAL:$2|grupe|grupa}} sa svog naloga: $1',
'listgrouprights-addgroup-self-all'    => 'Dodaj sve grupe na sopstveni nalog',
'listgrouprights-removegroup-self-all' => 'Ukloni sve grupe sa sopstvenog naloga',

# E-mail user
'mailnologin'          => 'Nema adrese za slanje',
'mailnologintext'      => 'Morate biti [[Special:UserLogin|prijavljeni]] i imati ispravnu e-adresu u [[Special:Preferences|podešavanjima]] da biste slali e-poruke drugim korisnicima.',
'emailuser'            => 'Pošalji e-poruku',
'emailpage'            => 'Slanje e-poruka',
'emailpagetext'        => 'Možete da koristite donji obrazac da pošaljete e-poruku {{GENDER:$1|ovom korisniku|ovoj korisnici|ovom korisniku|}}.
E-adresa koju ste uneli u vašim [[Special:Preferences|podešavanjima]] će se prikazati u polju „Od“, tako da će primalac moći da vam odgovori direktno.',
'usermailererror'      => 'Došlo je do greške pri slanju poruke:',
'defemailsubject'      => '{{SITENAME}} e-adresa {{GENDER:$1|korisnika|korisnice|korisnika}} $1',
'usermaildisabled'     => 'Korisnička e-pošta je onemogućena',
'usermaildisabledtext' => 'Ne možete da šaljete e-poruke drugim korisnicima na ovom vikiju',
'noemailtitle'         => 'Nema e-adrese',
'noemailtext'          => 'Ovaj korisnik nije naveo ispravnu e-adresu.',
'nowikiemailtitle'     => 'E-pošta nije dozvoljena',
'nowikiemailtext'      => 'Ovaj korisnik je odlučio da ne prima e-poruke od drugih korisnika.',
'emailnotarget'        => 'Nepostojeće ili neispravno korisničko ime primaoca.',
'emailtarget'          => 'Unos korisničkog imena primaoca',
'emailusername'        => 'Korisničko ime:',
'emailusernamesubmit'  => 'Pošalji',
'email-legend'         => 'Slanje e-poruka drugom korisniku',
'emailfrom'            => 'Od:',
'emailto'              => 'Za:',
'emailsubject'         => 'Naslov:',
'emailmessage'         => 'Poruka:',
'emailsend'            => 'Pošalji',
'emailccme'            => 'Pošalji mi primerak poruke e-poštom',
'emailccsubject'       => 'Primerak vaše poruke za $1: $2',
'emailsent'            => 'Poruka je poslata',
'emailsenttext'        => 'Vaša poruka je poslata e-poštom.',
'emailuserfooter'      => 'Ovu e-poruku je {{GENDER:|poslao|poslala|poslao}} $1 korisniku $2 putem e-pošte s vikija {{SITENAME}}.',

# User Messenger
'usermessage-summary'  => 'Slanje sistemske poruke.',
'usermessage-editor'   => 'Uređivač sistemskih poruka',
'usermessage-template' => 'MediaWiki:UserMessage',

# Watchlist
'watchlist'            => 'Spisak nadgledanja',
'mywatchlist'          => 'Spisak nadgledanja',
'watchlistfor2'        => 'Za $1 $2',
'nowatchlist'          => 'Vaš spisak nadgledanja je prazan.',
'watchlistanontext'    => 'Morate biti $1 da biste gledali i uređivali stavke na vašem spisku nadgledanja.',
'watchnologin'         => 'Niste prijavljeni',
'watchnologintext'     => 'Morate biti [[Special:UserLogin|prijavljeni]] da biste menjali spisak nadgledanja.',
'addwatch'             => 'Dodaj na spisak nadgledanja',
'addedwatchtext'       => 'Stranica „[[:$1]]“ je dodata na vaš [[Special:Watchlist|spisak nadgledanja]].
Buduće izmene ove stranice i njene stranice za razgovor biće navedene ovde, a stranica će biti <b>podebljana</b> u [[Special:RecentChanges|spisku skorašnjih izmena]] da bi se lakše uočila.

Ukoliko budete želeli da uklonite stranicu sa spiska nadgledanja, kliknite opet na zvezdicu u gornjoj paleti.',
'removewatch'          => 'Ukloni sa spiska nadgledanja',
'removedwatchtext'     => 'Stranica „[[:$1]]“ je uklonjena s vašeg [[Special:Watchlist|spiska nadgledanja]].',
'watch'                => 'Nadgledaj',
'watchthispage'        => 'Nadgledaj ovu stranicu',
'unwatch'              => 'Prekini nadgledanje',
'unwatchthispage'      => 'Prekini nadgledanje',
'notanarticle'         => 'Nije stranica sa sadržajem',
'notvisiblerev'        => 'Izmena je obrisana',
'watchnochange'        => 'Ništa što nadgledate nije promenjeno u prikazanom vremenu.',
'watchlist-details'    => '{{PLURAL:$1|$1 stranica|$1 stranice|$1 stranica}} na vašem spisku nadgledanja, ne računajući stranice za razgovor.',
'wlheader-enotif'      => '* E-obaveštenje je omogućeno.',
'wlheader-showupdated' => "* Stranice koje su izmenjene otkad ste ih poslednji put posetili su '''podebljane'''",
'watchmethod-recent'   => 'proverava se da li ima nadgledanih stranica u skorašnjim izmenama',
'watchmethod-list'     => 'proverava se da li ima skorašnjih izmena u nadgledanim stranicama',
'watchlistcontains'    => 'Vaš spisak nadgledanja sadrži $1 {{PLURAL:$1|stranicu|stranice|stranica}}.',
'iteminvalidname'      => 'Problem sa stavkom „$1“. Neispravan naziv.',
'wlnote'               => "Ispod {{PLURAL:$1|je poslednja izmena|su poslednje '''$1''' izmene|je poslednjih '''$1''' izmena}} u {{PLURAL:$2|prethodnom satu|prethodna '''$2''' sata|prethodnih '''$2''' sati}}, zaključno sa $3, $4.",
'wlshowlast'           => 'Prikaži poslednjih $1 sati, $2 dana, $3',
'watchlist-options'    => 'Postavke spiska nadgledanja',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'       => 'Nadgledanje…',
'unwatching'     => 'Prekidanje nadgledanja…',
'watcherrortext' => 'Došlo je do greške pri promeni postavki vašeg spiska nadgledanja za „$1“.',

'enotif_mailer'                => '{{SITENAME}} e-obaveštenje',
'enotif_reset'                 => 'Označi sve stranice kao posećene',
'enotif_newpagetext'           => 'Ovo je nova stranica.',
'enotif_impersonal_salutation' => '{{SITENAME}} korisnik',
'changed'                      => 'izmenjena',
'created'                      => 'napravljena',
'enotif_subject'               => '{{SITENAME}} stranica $PAGETITLE je $CHANGEDORCREATED od strane $PAGEEDITOR',
'enotif_lastvisited'           => 'Pogledajte $1 za sve izmene od vaše poslednje posete.',
'enotif_lastdiff'              => 'Pogledajte $1 da vidite ovu izmenu.',
'enotif_anon_editor'           => 'anoniman korisnik $1',
'enotif_body'                  => 'Poštovani $WATCHINGUSERNAME,


Stranica $PAGETITLE na vikiju {{SITENAME}} je $CHANGEDORCREATED dana $PAGEEDITDATE od strane {{GENDER:$PAGEEDITOR|korisnika|korisnice|korisnika}} $PAGEEDITOR. Pogledajte $PAGETITLE_URL za tekuću izmenu.

$NEWPAGE

Opis: $PAGESUMMARY $PAGEMINOREDIT

Kontakt:
e-adresa: $PAGEEDITOR_EMAIL
viki: $PAGEEDITOR_WIKI

Neće biti drugih obaveštenja u slučaju daljih izmena ukoliko ne posetite ovu stranicu.
Možete i da poništite postavke obaveštenja za sve stranice u vašem spisku nadgledanja.

Srdačan pozdrav, {{SITENAME}}

--
Da biste promenili postavke u vezi sa e-obaveštenjima, posetite
{{canonicalurl:{{#special:Preferences}}}}

Da biste promenili postavke u vezi sa spiskom nadgledanja, posetite
{{canonicalurl:{{#special:EditWatchlist}}}}

Da biste uklonili ovu stranicu sa spiska nadgledanja, posetite
$UNWATCHURL

Podrška i dalja pomoć:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Obriši stranicu',
'confirm'                => 'Potvrdi',
'excontent'              => 'sadržaj je bio: „$1“',
'excontentauthor'        => 'sadržaj je bio: „$1“ (jedinu izmenu {{GENDER:|napravio je|napravila je|napravio je}} [[Special:Contributions/$2|$2]])',
'exbeforeblank'          => 'sadržaj pre brisanja je bio: „$1“',
'exblank'                => 'stranica je bila prazna',
'delete-confirm'         => 'Brisanje stranice „$1“',
'delete-legend'          => 'Obriši',
'historywarning'         => "'''Upozorenje:''' stranica koju želite da obrišete ima istoriju s približno $1 {{PLURAL:$1|izmenom|izmene|izmena}}:",
'confirmdeletetext'      => 'Upravo ćete obrisati stranicu, uključujući i njenu istoriju.
Potvrdite svoju nameru, da razumete posledice i da ovo radite u skladu s [[{{MediaWiki:Policy-url}}|pravilima]].',
'actioncomplete'         => 'Radnja je završena',
'actionfailed'           => 'Radnja nije uspela',
'deletedtext'            => "Stranica „$1“ je obrisana.
Pogledajte ''$2'' za više detalja.",
'dellogpage'             => 'Dnevnik brisanja',
'dellogpagetext'         => 'Ispod je spisak poslednjih brisanja.',
'deletionlog'            => 'dnevnik brisanja',
'reverted'               => 'Vraćeno na raniju izmenu',
'deletecomment'          => 'Razlog:',
'deleteotherreason'      => 'Drugi/dodatni razlog:',
'deletereasonotherlist'  => 'Drugi razlog',
'deletereason-dropdown'  => '*Najčešći razlozi za brisanje
** Zahtev autora
** Kršenje autorskih prava
** Vandalizam',
'delete-edit-reasonlist' => 'Uredi razloge brisanja',
'delete-toobig'          => 'Ova stranica ima veliku istoriju, preko $1 {{PLURAL:$1|izmene|izmene|izmena}}.
Brisanje takvih stranica je ograničeno da bi se sprečilo slučajno opterećenje servera.',
'delete-warning-toobig'  => 'Ova stranica ima veliku istoriju, preko $1 {{PLURAL:$1|izmene|izmene|izmena}}.
Njeno brisanje može poremetiti bazu podataka, stoga postupajte s oprezom.',

# Rollback
'rollback'          => 'Vrati izmene',
'rollback_short'    => 'Vrati',
'rollbacklink'      => 'vrati',
'rollbackfailed'    => 'Neuspešno vraćanje',
'cantrollback'      => 'Ne mogu da vratim izmenu.
Poslednji autor je ujedno i jedini.',
'alreadyrolled'     => 'Vraćanje poslednje izmene stranice [[:$1]] od strane {{GENDER:$2|korisnika|korisnice|korisnika}} [[User:$2|$2]] ([[User talk:$2|razgovor]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); neko drugi je već izmenio ili vratio stranicu.

Poslednju izmenu je {{GENDER:$3|napravio|napravila|napravio}} [[User:$3|$3]] ([[User talk:$3|razgovor]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Opis izmene: \"''\$1''\".",
'revertpage'        => 'Vraćene su izmene {{GENDER:$2|korisnika|korisnice|korisnika}} [[Special:Contributions/$2|$2]] ([[User talk:$2|razgovor]]) na poslednju izmenu člana [[User:$1|$1]]',
'revertpage-nouser' => 'Vraćene su izmene korisnika (korisničko ime je uklonjeno) na poslednju izmenu člana [[User:$1|$1]]',
'rollback-success'  => 'Vraćene su izmene {{GENDER:$1|korisnika|korisnice|korisnika}} $1
na poslednju izmenu {{GENDER:$2|korisnika|korisnice|korisnika}} $2.',

# Edit tokens
'sessionfailure-title' => 'Sesija je okončana',
'sessionfailure'       => 'Izgleda da postoji problem s vašom sesijom;
ova radnja je otkazana da bi se izbegla zloupotreba.
Vratite se na prethodnu stranicu, ponovo je učitajte i pokušajte ponovo.',

# Protect
'protectlogpage'              => 'Dnevnik zaštite',
'protectlogtext'              => 'Ispod je spisak izmena u vidu zaštite stranica.
Pogledajte [[Special:ProtectedPages|spisak zaštićenih stranica]] za više detalja.',
'protectedarticle'            => '{{GENDER:|je zaštitio|je zaštitila|je zaštitio}} „[[$1]]“',
'modifiedarticleprotection'   => '{{GENDER:|je promenio|je promenila|je promenio}} stepen zaštite za „[[$1]]“',
'unprotectedarticle'          => '{{GENDER:|je uklonio|je uklonila|je uklonio}} zaštitu sa stranice „[[$1]]“',
'movedarticleprotection'      => '{{GENDER:|je premestio|je premestila|je premestio}} postavke zaštite sa „[[$2]]“ na „[[$1]]“',
'protect-title'               => 'Stepen zaštite za „$1“',
'protect-title-notallowed'    => 'Pregled stepena zaštite za „$1“',
'prot_1movedto2'              => '{{GENDER:|je premestio|je premestila|je premestio}} [[$1]] u [[$2]]',
'protect-badnamespace-title'  => 'Nezaštitljiv imenski prostor',
'protect-badnamespace-text'   => 'Stranice u ovom imenskom prostoru se ne mogu zaštititi.',
'protect-legend'              => 'Potvrdite zaštitu',
'protectcomment'              => 'Razlog:',
'protectexpiry'               => 'Ističe:',
'protect_expiry_invalid'      => 'Vreme isteka je neispravno.',
'protect_expiry_old'          => 'Vreme isteka je starije od trenutnog vremena.',
'protect-unchain-permissions' => 'Otključaj daljnje postavke zaštite',
'protect-text'                => "Ovde možete da pogledate i promenite stepen zaštite za stranicu '''$1'''.",
'protect-locked-blocked'      => "Ne možete menjati stepene zaštite dok ste blokirani.
Ovo su trenutne postavke stranice '''$1''':",
'protect-locked-dblock'       => "Stepeni zaštite se ne mogu menjati jer je aktivna baza podataka zaključana.
Ovo su postavke stranice '''$1''':",
'protect-locked-access'       => "Nemate ovlašćenja za menjanje stepena zaštite stranice.
Ovo su trenutne postavke stranice '''$1''':",
'protect-cascadeon'           => 'Ova stranica je trenutno zaštićena jer se nalazi na {{PLURAL:$1|stranici koja ima|stranicama koje imaju}} prenosivu zaštitu.
Možete da promenite stepen zaštite, ali to neće uticati na prenosivu zaštitu.',
'protect-default'             => 'Dozvoli svim korisnicima',
'protect-fallback'            => 'Potrebno je imati ovlašćenja „$1“',
'protect-level-autoconfirmed' => 'Blokiraj nove i anonimne korisnike',
'protect-level-sysop'         => 'Samo administratori',
'protect-summary-cascade'     => 'prenosiva zaštita',
'protect-expiring'            => 'ističe $1 (UTC)',
'protect-expiring-local'      => 'ističe $1',
'protect-expiry-indefinite'   => 'nikada',
'protect-cascade'             => 'Zaštiti sve stranice koje su uključene u ovu (prenosiva zaštita)',
'protect-cantedit'            => 'Ne možete menjati stepene zaštite ove stranice jer nemate ovlašćenja za uređivanje.',
'protect-othertime'           => 'Drugo vreme:',
'protect-othertime-op'        => 'drugo vreme',
'protect-existing-expiry'     => 'Postojeće vreme isteka: $2 u $3',
'protect-otherreason'         => 'Drugi/dodatni razlog:',
'protect-otherreason-op'      => 'Drugi razlog',
'protect-dropdown'            => '* Najčešći razlozi zaštićivanja
** Prekomerni vandalizam
** Nepoželjne poruke
** Neproduktivni rat izmena
** Stranica velikog prometa',
'protect-edit-reasonlist'     => 'Uredi razloge zaštićivanja',
'protect-expiry-options'      => '1 sat:1 hour,1 dan:1 day,1 nedelja:1 week,2 nedelje:2 weeks,1 mesec:1 month,3 meseca:3 months,6 meseci:6 months,1 godina:1 year,beskonačno:infinite',
'restriction-type'            => 'Dozvola:',
'restriction-level'           => 'Stepen ograničenja:',
'minimum-size'                => 'Najmanja veličina',
'maximum-size'                => 'Najveća veličina:',
'pagesize'                    => '(bajtovi)',

# Restrictions (nouns)
'restriction-edit'   => 'uređivanje',
'restriction-move'   => 'premeštanje',
'restriction-create' => 'stvaranje',
'restriction-upload' => 'slanje',

# Restriction levels
'restriction-level-sysop'         => 'potpuno zaštićeno',
'restriction-level-autoconfirmed' => 'poluzaštićeno',
'restriction-level-all'           => 'bilo koji stepen',

# Undelete
'undelete'                     => 'Prikaz obrisanih stranica',
'undeletepage'                 => 'Prikaz i vraćanje obrisanih stranica',
'undeletepagetitle'            => "'''Sledeći sadržaj se sastoji od obrisanih izmena stranice [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Prikaz obrisanih stranica',
'undeletepagetext'             => '{{PLURAL:$1|Sledeća stranica je obrisana, ali je još u arhivi i može biti vraćena|Sledeće $1 stranice su obrisane, ali su još u arhivi i mogu biti vraćene|Sledećih $1 stranica je obrisano, ali su još u arhivi i mogu biti vraćene}}.
Arhiva se povremeno čisti od ovakvih stranica.',
'undelete-fieldset-title'      => 'Vraćanje izmena',
'undeleteextrahelp'            => "Da biste vratili celu istoriju stranice, ostavite sve kućice neoznačene i kliknite na dugme '''''{{int:undeletebtn}}'''''.
Ako želite da vratite određene izmene, označite ih i kliknite na '''''{{int:undeletebtn}}'''''.",
'undeleterevisions'            => '$1 {{PLURAL:$1|izmena je arhivirana|izmene su arhivirane|izmena je arhivirano}}',
'undeletehistory'              => 'Ako vratite stranicu, sve izmene će biti vraćene njenoj istoriji.
Ako je u međuvremenu napravljena nova stranica s istim nazivom, vraćene izmene će se pojaviti u ranijom istoriji.',
'undeleterevdel'               => 'Vraćanje neće biti izvršeno ako je rezultat toga delimično brisanje poslednje izmene.
U takvim slučajevima morate isključiti ili otkriti najnovije obrisane izmene.',
'undeletehistorynoadmin'       => 'Ova stranica je obrisana.
Razlog za brisanje se nalazi ispod, zajedno s detaljima o korisniku koji je izmenio ovu stranicu pre brisanja.
Tekst obrisanih izmena je dostupan samo administratorima.',
'undelete-revision'            => 'Obrisana izmena stranice $1 (dana $4; $5) od strane {{GENDER:$3|korisnika|korisnice|korisnika}} $3:',
'undeleterevision-missing'     => 'Neispravna ili nepostojeća izmena.
Možda ste uneli pogrešnu vezu, ili je izmena vraćena ili uklonjena iz arhive.',
'undelete-nodiff'              => 'Prethodne izmene nisu pronađene.',
'undeletebtn'                  => 'Vrati',
'undeletelink'                 => 'pogledaj/vrati',
'undeleteviewlink'             => 'pogledaj',
'undeletereset'                => 'Poništi',
'undeleteinvert'               => 'Obrni izbor',
'undeletecomment'              => 'Razlog:',
'undeletedrevisions'           => '{{PLURAL:$1|Izmena je vraćena|$1 izmene su vraćene|$1 izmena je vraćeno}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|izmena|izmene|izmena}} i $2 {{PLURAL:$2|datoteka|datoteke|datoteka}} je vraćeno',
'undeletedfiles'               => '{{PLURAL:$1|Datoteka je vraćena|$1 datoteke su vraćene|$1 datoteka je vraćeno}}',
'cannotundelete'               => 'Neuspešno vraćanje. Neko drugi je to uradio pre vas.',
'undeletedpage'                => "'''Stranica $1 je vraćena'''

Pogledajte [[Special:Log/delete|istoriju brisanja]] za zapise o skorašnjim brisanjima i vraćanjima.",
'undelete-header'              => 'Pogledajte [[Special:Log/delete|istorijat brisanja]] za nedavno obrisane stranice.',
'undelete-search-title'        => 'Pretraga obrisanih stranica',
'undelete-search-box'          => 'Pretraži obrisane stranice',
'undelete-search-prefix'       => 'Prikaži stranice koje počinju sa:',
'undelete-search-submit'       => 'Pretraži',
'undelete-no-results'          => 'Odgovarajuća stranica u dnevniku brisanja nije pronađena.',
'undelete-filename-mismatch'   => 'Ne mogu da vratim izmenu datoteke od $1: naziv datoteke se ne poklapa',
'undelete-bad-store-key'       => 'Ne mogu da vratim izmenu datoteke od $1: datoteka je nedostajala pre brisanja.',
'undelete-cleanup-error'       => 'Greška pri brisanju nekorišćene arhive „$1“.',
'undelete-missing-filearchive' => 'Ne mogu da vratim arhivu s IB $1 jer se ona ne nalazi u bazi podataka.
Možda je već bila vraćena.',
'undelete-error'               => 'Došlo je do greške pri vraćanju obrisane stranice',
'undelete-error-short'         => 'Greška pri vraćanju datoteke: $1',
'undelete-error-long'          => 'Došlo je do greške pri vraćanju datoteke:

$1',
'undelete-show-file-confirm'   => 'Želite li da vidite obrisanu izmenu datoteke „<nowiki>$1</nowiki>“ od $2; $3?',
'undelete-show-file-submit'    => 'Da',

# Namespace form on various pages
'namespace'                     => 'Imenski prostor:',
'invert'                        => 'Obrni izbor',
'tooltip-invert'                => 'Označite ovu kućicu da biste sakrili izmene na stranicama u odabranom imenskom prostoru (i povezanim imenskim prostorima, ako je označeno)',
'namespace_association'         => 'Povezani imenski prostor',
'tooltip-namespace_association' => 'Označite ovu kućicu da biste uključili i razgovor ili imenski prostor teme koja je povezana s odabranim imenskim prostorom',
'blanknamespace'                => '(Glavno)',

# Contributions
'contributions'       => 'Korisnički doprinosi',
'contributions-title' => 'Doprinosi {{GENDER:$1|korisnika|korisnice|korisnika}} $1',
'mycontris'           => 'Doprinosi',
'contribsub2'         => 'Za $1 ($2)',
'nocontribs'          => 'Izmene koje odgovaraju ovim uslovima nisu pronađene.',
'uctop'               => '(vrh)',
'month'               => 'od meseca (i ranije):',
'year'                => 'od godine (i ranije):',

'sp-contributions-newbies'             => 'Prikaži samo doprinose novih korisnika',
'sp-contributions-newbies-sub'         => 'Za nove korisnike',
'sp-contributions-newbies-title'       => 'Doprinosi novih korisnika',
'sp-contributions-blocklog'            => 'dnevnik blokiranja',
'sp-contributions-deleted'             => 'obrisani korisnički doprinosi',
'sp-contributions-uploads'             => 'otpremanja',
'sp-contributions-logs'                => 'dnevnici',
'sp-contributions-talk'                => 'razgovor',
'sp-contributions-userrights'          => 'upravljanje korisničkim pravima',
'sp-contributions-blocked-notice'      => 'Ovaj korisnik je blokiran. Ispod su navedeni poslednji zapisi u dnevniku blokiranja:',
'sp-contributions-blocked-notice-anon' => 'Ovoj IP adresi je trenutno zabranjen pristup.
Izveštaj o blokiranim korisnicima se nalazi ispod:',
'sp-contributions-search'              => 'Pretraga doprinosa',
'sp-contributions-username'            => 'IP adresa ili korisničko ime:',
'sp-contributions-toponly'             => 'Prikaži samo najnovije izmene',
'sp-contributions-submit'              => 'Pretraži',

# What links here
'whatlinkshere'            => 'Šta vodi ovde',
'whatlinkshere-title'      => 'Stranice koje su povezane sa „$1“',
'whatlinkshere-page'       => 'Stranica:',
'linkshere'                => "Sledeće stranice imaju vezu do '''[[:$1]]''':",
'nolinkshere'              => "Nijedna stranica nije povezana sa: '''[[:$1]]'''.",
'nolinkshere-ns'           => "Nijedna stranica ne vodi do '''[[:$1]]''' u izabranom imenskom prostoru.",
'isredirect'               => 'preusmerenje',
'istemplate'               => 'uključivanje',
'isimage'                  => 'veza ka datoteci',
'whatlinkshere-prev'       => '{{PLURAL:$1|prethodni|prethodnih $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|sledeći|sledećih $1}}',
'whatlinkshere-links'      => '← veze',
'whatlinkshere-hideredirs' => '$1 preusmerenja',
'whatlinkshere-hidetrans'  => '$1 uključivanja',
'whatlinkshere-hidelinks'  => '$1 veze',
'whatlinkshere-hideimages' => '$1 veze do datoteke',
'whatlinkshere-filters'    => 'Filteri',

# Block/unblock
'autoblockid'                     => 'Samoblokiranje #$1',
'block'                           => 'Blokiraj korisnika',
'unblock'                         => 'Deblokiraj korisnika',
'blockip'                         => 'Blokiraj korisnika',
'blockip-title'                   => 'Blokiranje korisnika',
'blockip-legend'                  => 'Blokiraj korisnika',
'blockiptext'                     => 'Koristite donji obrazac da biste zabranili pristup za pisanje s određene IP adrese ili korisničkog imena.
Ovo bi trebalo da vršite samo radi sprečavanja vandalizma, u skladu sa [[{{MediaWiki:Policy-url}}|smernicama]].
Izaberite konkretan razlog ispod (primer: navođenje konkretnih stranica koje su vandalizovane).',
'ipadressorusername'              => 'IP adresa ili korisničko ime:',
'ipbexpiry'                       => 'Ističe:',
'ipbreason'                       => 'Razlog:',
'ipbreasonotherlist'              => 'Drugi razlog',
'ipbreason-dropdown'              => '*Najčešći razlozi za blokiranje
** Unošenje lažnih informacija
** Uklanjanje sadržaja sa stranica
** Postavljanje veza do spoljašnjih sajtova
** Unošenje besmislica u stranice
** Nepristojno ponašanje
** Upotreba više naloga
** Neprihvatljivo korisničko ime',
'ipb-hardblock'                   => 'Zabrani prijavljenim korisnicima da uređuju s ove IP adrese',
'ipbcreateaccount'                => 'Onemogući otvaranje naloga',
'ipbemailban'                     => 'Onemogući korisniku da šalje e-poruke',
'ipbenableautoblock'              => 'Automatski blokiraj poslednju IP adresu ovog korisnika i sve daljnje adrese s kojih pokuša da uređuje',
'ipbsubmit'                       => 'Blokiraj ovog korisnika',
'ipbother'                        => 'Drugo vreme:',
'ipboptions'                      => '2 sata:2 hours,1 dan:1 day,3 dana:3 days,1 nedelja:1 week,2 nedelje:2 weeks,1 mesec:1 month,3 meseca:3 months,6 meseci:6 months,1 godina:1 year,beskonačno:infinite',
'ipbotheroption'                  => 'drugo',
'ipbotherreason'                  => 'Drugi/dodatni razlog:',
'ipbhidename'                     => 'Sakrij korisničko ime sa izmena i spiskova',
'ipbwatchuser'                    => 'Nadgledaj korisničke stranice i stranice za razgovor ovog korisnika',
'ipb-disableusertalk'             => 'Zabrani ovom korisniku da uređuje svoju stranicu za razgovor dok je blokiran',
'ipb-change-block'                => 'Ponovo blokiraj korisnika s ovim postavkama',
'ipb-confirm'                     => 'Potvrdi blokiranje',
'badipaddress'                    => 'Neispravna IP adresa',
'blockipsuccesssub'               => 'Blokiranje je uspelo',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] je {{GENDER:$1|blokiran|blokirana|blokiran}}.<br />
Blokiranja možete da pogledate [[Special:BlockList|ovde]].',
'ipb-blockingself'                => 'Ovom radnjom ćete blokirati sebe! Jeste li sigurni da to želite?',
'ipb-confirmhideuser'             => 'Upravo ćete blokirati korisnika s uključenom mogućnošću „sakrij korisnika“. Ovim će korisničko ime biti sakriveno u svim spiskovima i izveštajima. Želite li to da uradite?',
'ipb-edit-dropdown'               => 'Uredi razloge blokiranja',
'ipb-unblock-addr'                => 'Deblokiraj $1',
'ipb-unblock'                     => 'Deblokiraj korisničko ime ili IP adresu',
'ipb-blocklist'                   => 'Pogledaj postojeća blokiranja',
'ipb-blocklist-contribs'          => 'Doprinosi za $1',
'unblockip'                       => 'Deblokiraj korisnika',
'unblockiptext'                   => 'Koristite obrazac ispod da biste vratili pravo pisanja blokiranoj IP adresi ili korisničkom imenu.',
'ipusubmit'                       => 'Ukloni ovu blokadu',
'unblocked'                       => '[[User:$1|$1]] je deblokiran',
'unblocked-range'                 => '$1 je {{GENDER:$1|deblokiran|deblokirana|deblokiran}}',
'unblocked-id'                    => 'Blokiranje $1 je uklonjeno',
'blocklist'                       => 'Blokirani korisnici',
'ipblocklist'                     => 'Blokirani korisnici',
'ipblocklist-legend'              => 'Pronalaženje blokiranog korisnika',
'blocklist-userblocks'            => 'Sakrij blokiranja naloga',
'blocklist-tempblocks'            => 'Sakrij privremena blokiranja',
'blocklist-addressblocks'         => 'Sakrij pojedinačna blokiranja IP adrese',
'blocklist-rangeblocks'           => 'Sakrij blokiranja opsega',
'blocklist-timestamp'             => 'Vreme i datum',
'blocklist-target'                => 'Korisnik',
'blocklist-expiry'                => 'Ističe',
'blocklist-by'                    => 'Blokirao',
'blocklist-params'                => 'Zabranjene radnje',
'blocklist-reason'                => 'Razlog',
'ipblocklist-submit'              => 'Pretraži',
'ipblocklist-localblock'          => 'Lokalno blokiranje',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Drugo blokiranje|Druga blokiranja}}',
'infiniteblock'                   => 'nikada',
'expiringblock'                   => 'ističe dana $1 u $2',
'anononlyblock'                   => 'samo anonimni',
'noautoblockblock'                => 'automatsko blokiranje je onemogućeno',
'createaccountblock'              => 'otvaranje naloga je blokirano',
'emailblock'                      => 'e-pošta je blokirana',
'blocklist-nousertalk'            => 'zabranjeno uređivanje sopstvene stranice za razgovor',
'ipblocklist-empty'               => 'Spisak blokiranja je prazan.',
'ipblocklist-no-results'          => 'Tražena IP adresa ili korisničko ime nije blokirano.',
'blocklink'                       => 'blokiraj',
'unblocklink'                     => 'deblokiraj',
'change-blocklink'                => 'promeni blokiranje',
'contribslink'                    => 'doprinosi',
'emaillink'                       => 'pošalji e-poruku',
'autoblocker'                     => 'Samoblokirani ste jer delite IP adresu s {{GENDER:$1|korisnikom|korisnicom|korisnikom}} [[User:$1|$1]].
Razlog blokiranja: "\'\'\'$2\'\'\'"',
'blocklogpage'                    => 'Dnevnik blokiranja',
'blocklog-showlog'                => '{{GENDER:$1|Ovaj korisnik je ranije blokiran|Ova korisnica je ranije blokirana|Ovaj korisnik je ranije blokiran}}.
Istorija blokiranja se nalazi ispod:',
'blocklog-showsuppresslog'        => '{{GENDER:|Ovaj korisnik je ranije blokiran i sakriven|Ova korisnica je ranije blokirana i sakrivena|Ovaj korisnik je ranije blokiran i sakriven}}.
Istorija sakrivanja se nalazi ispod:',
'blocklogentry'                   => '{{GENDER:|je blokirao|je blokirala|je blokirao}} „[[$1]]” s rokom isticanja od $2 $3',
'reblock-logentry'                => '{{GENDER:|je promenio|je promenila|je promenio}} podešavanja za blokiranje {{GENDER:$1|korisnika|korisnice|korisnika}} [[$1]] s rokom isteka od $2 ($3)',
'blocklogtext'                    => 'Ovo je dnevnik blokiranja i deblokiranja korisnika.
Automatski blokirane IP adrese nisu navedene.
Tekuće zabrane i blokiranja možete naći [[Special:BlockList|ovde]].',
'unblocklogentry'                 => '{{GENDER:|je deblokirao|je deblokirala|je deblokirao}} „$1“',
'block-log-flags-anononly'        => 'samo anonimni korisnici',
'block-log-flags-nocreate'        => 'onemogućeno otvaranje naloga',
'block-log-flags-noautoblock'     => 'automatsko blokiranje je onemogućeno',
'block-log-flags-noemail'         => 'e-pošta je blokirana',
'block-log-flags-nousertalk'      => 'zabranjeno uređivanje sopstvene stranice za razgovor',
'block-log-flags-angry-autoblock' => 'prošireno automatsko blokiranje je omogućeno',
'block-log-flags-hiddenname'      => 'korisničko ime je sakriveno',
'range_block_disabled'            => 'Administratorska mogućnost za blokiranje raspona IP adresa je onemogućena.',
'ipb_expiry_invalid'              => 'Vreme isteka je neispravno.',
'ipb_expiry_temp'                 => 'Sakrivene blokade korisnika moraju biti trajne.',
'ipb_hide_invalid'                => 'Ne mogu da potisnem ovaj nalog. Možda ima previše izmena.',
'ipb_already_blocked'             => '„$1“ je već blokiran',
'ipb-needreblock'                 => '$1 je već blokiran. Želite li da promenite podešavanja?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Druga blokada|Druge blokade}}',
'unblock-hideuser'                => 'Ne možete deblokirati ovog korisnika jer je njegovo korisničko ime sakriveno.',
'ipb_cant_unblock'                => 'Greška: blokada $1 ne postoji. Možda je korisnik deblokiran.',
'ipb_blocked_as_range'            => 'Greška: IP adresa $1 nije direktno blokirana i ne može da se deblokira.
Ona je blokirana kao deo blokade $2, koja može biti deblokirana.',
'ip_range_invalid'                => 'Neispravan raspod IP adresa.',
'ip_range_toolarge'               => 'Opsežna blokiranja veća od /$1 nisu dozvoljena.',
'blockme'                         => 'Blokiraj me',
'proxyblocker'                    => 'Bloker posrednika',
'proxyblocker-disabled'           => 'Ova funkcija je onemogućena.',
'proxyblockreason'                => 'Vaša IP adresa je blokirana jer predstavlja otvoreni posrednik.
Obratite se vašem dobavljaču internet usluga ili tehničku podršku i obavestite ih o ovom ozbiljnom bezbednosnom problemu.',
'proxyblocksuccess'               => 'Urađeno.',
'sorbs'                           => 'DNSBL',
'sorbsreason'                     => 'Vaša IP adresa je navedena kao otvoreni posrednik u DNSBL-u koji koristi {{SITENAME}}.',
'sorbs_create_account_reason'     => 'Vaša IP adresa je navedena kao otvoreni posrednik u DNSBL-u koji koristi {{SITENAME}}.
Ne možete da otvorite nalog.',
'cant-block-while-blocked'        => 'Ne možete da blokirate druge korisnike dok ste i sami blokirani.',
'cant-see-hidden-user'            => 'Član kome želite da zabranite pristup je već blokiran i sakriven.
S obzirom na to da nemate prava za sakrivanje korisnika, ne možete da vidite niti izmenite zabranu.',
'ipbblocked'                      => 'Ne možete zabraniti ili vratiti pristup drugim korisnicima jer ste i sami blokirani',
'ipbnounblockself'                => 'Nije vam dozvoljeno da deblokirate sebe',

# Developer tools
'lockdb'              => 'Zaključavanje baze podataka',
'unlockdb'            => 'Otključavanje baze podataka',
'lockdbtext'          => 'Zaključavanje baze podataka će ukinuti mogućnost svim korisnicima da uređuju stranice, menjaju podešavanja, uređuju spiskove nadgledanja i sve ostalo što zahteva izmene u bazi.
Potvrdite da je ovo vaša namera i da ćete otključati bazu kada završite s radom oko njenog održavanja.',
'unlockdbtext'        => 'Otključavanje baze podataka će vratiti mogućnost svim korisnicima da uređuju stranice, menjaju podešavanja, uređuju spiskove nadgledanja i sve ostalo što zahteva izmene u bazi.
Potvrdite da je ovo vaša namera.',
'lockconfirm'         => 'Želim da zaključam bazu.',
'unlockconfirm'       => 'Želim da otključam bazu.',
'lockbtn'             => 'Zaključaj bazu',
'unlockbtn'           => 'Otključaj bazu',
'locknoconfirm'       => 'Niste proverili potvrdnu kutiju.',
'lockdbsuccesssub'    => 'Baza je zaključana',
'unlockdbsuccesssub'  => 'Baza je otključana',
'lockdbsuccesstext'   => 'Baza podataka je zaključana.<br />
Setite se da je [[Special:UnlockDB|otključate]] kada završite s održavanjem.',
'unlockdbsuccesstext' => 'Baza je otključana.',
'lockfilenotwritable' => 'Datoteka za zaključavanje baze nije otvorena za pisanje.
Da biste zaključali i otključali bazu, datoteka mora biti dostupna za pisanje od strane mrežnog servera.',
'databasenotlocked'   => 'Baza nije zaključana.',
'lockedbyandtime'     => '(od $1 dana $2 u $3)',

# Move page
'move-page'                    => 'Premeštanje „$1“',
'move-page-legend'             => 'Premeštanje stranice',
'movepagetext'                 => "Donji obrazac će preimenovati stranicu, premeštajući celu istoriju na novo ime.
Stari naslov postaće preusmerenje na novi naslov.
Možete automatski izmeniti preusmerenje do izvornog naslova.
Pogledajte [[Special:DoubleRedirects|dvostruka]] ili [[Special:BrokenRedirects|pokvarena]] preusmerenja.
Na vama je odgovornost da veze i dalje idu tamo gde bi trebalo da idu.

Stranica '''neće''' biti premeštena ako već postoji stranica s tim imenom, osim ako je ona prazna, sadrži preusmerenje ili nema istoriju izmena.
To znači da možete vratiti stranicu na prethodno mesto ako pogrešite, ali ne možete zameniti postojeću stranicu.

'''Pažnja!'''
Ovo može predstavljati drastičnu i neočekivanu izmenu za popularnu stranicu;
dobro razmislite o posledicama pre nego što nastavite.",
'movepagetext-noredirectfixer' => "Donji obrazac će preimenovati stranicu, premeštajući celu istoriju na novo ime.
Stari naslov postaće preusmerenje na novi naslov.
Pogledajte [[Special:DoubleRedirects|dvostruka]] ili [[Special:BrokenRedirects|pokvarena]] preusmerenja.
Na vama je odgovornost da veze i dalje idu tamo gde bi trebalo da idu.

Stranica '''neće''' biti premeštena ako već postoji stranica s tim imenom, osim ako je ona prazna, sadrži preusmerenje ili nema istoriju izmena.
To znači da možete vratiti stranicu na prethodno mesto ako pogrešite, ali ne možete zameniti postojeću stranicu.

'''Pažnja!'''
Ovo može predstavljati drastičnu i neočekivanu izmenu za popularnu stranicu;
dobro razmislite o posledicama pre nego što nastavite.",
'movepagetalktext'             => 'Odgovarajuća stranica za razgovor, ako postoji, biće automatski premeštena zajedno s njom, osim ako:
*Već postoji stranica za razgovor s novim naslovom, ili
*Ako je označeno dolenavedeno polje.

U ovom slučaju mora ručno da se premesti ili spoji, ako ima potrebe za tim.',
'movearticle'                  => 'Premesti stranicu:',
'moveuserpage-warning'         => "'''Upozorenje:''' na putu ste da premestite korisničku stranicu. Imajte u vidu da će samo stranica biti premeštena, a sam korisnik ''neće'' biti preimenovan.",
'movenologin'                  => 'Niste prijavljeni',
'movenologintext'              => 'Morate da budete registrovani i [[Special:UserLogin|prijavljeni]] da biste premeštali stranice.',
'movenotallowed'               => 'Nemate dozvolu da premeštate stranice.',
'movenotallowedfile'           => 'Nemate dozvolu da premeštate datoteke.',
'cant-move-user-page'          => 'Nemate dozvolu za premeštanje osnovnih korisničkih stranica (osim podstranica).',
'cant-move-to-user-page'       => 'Nemate dozvolu za premeštanje stranice na vašu korisničku stranicu (osim na korisničku podstranicu).',
'newtitle'                     => 'Novi naslov:',
'move-watch'                   => 'Nadgledaj ovu stranicu',
'movepagebtn'                  => 'Premesti stranicu',
'pagemovedsub'                 => 'Uspešno premeštanje',
'movepage-moved'               => "'''„$1“ je premeštena na „$2“'''",
'movepage-moved-redirect'      => 'Preusmerenje je napravljeno.',
'movepage-moved-noredirect'    => 'Stvaranje preusmerenja je onemogućeno.',
'articleexists'                => 'Stranica s tim imenom već postoji, ili je ime neispravno.
Izaberite drugo ime.',
'cantmove-titleprotected'      => 'Ne možete da premestite stranicu na to mesto jer je željeni naslov zaštićen od stvaranja',
'talkexists'                   => "'''Sama stranica je premeštena, ali stranica za razgovor nije jer takva već postoji na novom naslovu.
Ručno ih spojite.'''",
'movedto'                      => 'premeštena na',
'movetalk'                     => 'Premesti i stranicu za razgovor',
'move-subpages'                => 'Premesti i podstranice (do $1)',
'move-talk-subpages'           => 'Premesti podstranice stranice za razgovor (do $1)',
'movepage-page-exists'         => 'Stranica $1 već postoji i ne može se zameniti.',
'movepage-page-moved'          => 'Stranica $1 je premeštena u $2.',
'movepage-page-unmoved'        => 'Stranica $1 ne može da se premesti na $2.',
'movepage-max-pages'           => 'Najviše $1 {{PLURAL:$1|stranica je premeštena|stranice su premeštene|stranica je premešteno}}, i više ne može da bude automatski premešteno.',
'movelogpage'                  => 'Dnevnik premeštanja',
'movelogpagetext'              => 'Ispod se nalazi spisak premeštanja stranica.',
'movesubpage'                  => '{{PLURAL:$1|Podstranica|Podstranice|Podstranica}}',
'movesubpagetext'              => 'Ova stranica ima $1 {{PLURAL:$1|podstranicu prikazanu|podstranice prikazane|podstranica prikazanih}} ispod.',
'movenosubpage'                => 'Ova stranica nema podstrana.',
'movereason'                   => 'Razlog:',
'revertmove'                   => 'vrati',
'delete_and_move'              => 'Obriši i premesti',
'delete_and_move_text'         => '== Potrebno brisanje ==

Odredišna stranica „[[:$1]]“ već postoji. 
Želite li da je obrišete da biste oslobodili mesto za preusmerenje?',
'delete_and_move_confirm'      => 'Da, obriši stranicu',
'delete_and_move_reason'       => 'Obrisano da se oslobodi mesto za premeštanje iz „[[$1]]“',
'selfmove'                     => 'Izvorni i odredišni naslovi su istovetni;
ne mogu da premestim stranicu preko same sebe.',
'immobile-source-namespace'    => 'Ne mogu da premestim stranice u imenskom prostoru „$1“',
'immobile-target-namespace'    => 'Ne mogu da premestim stranice u imenskom prostoru „$1”',
'immobile-target-namespace-iw' => 'Međuviki veza nije ispravno odredište za premeštanje stranice.',
'immobile-source-page'         => 'Ova stranica se ne može premestiti.',
'immobile-target-page'         => 'Ne mogu da premestim na željeni naslov.',
'imagenocrossnamespace'        => 'Datoteka se ne može premestiti u imenski prostor koji ne pripada datotekama.',
'nonfile-cannot-move-to-file'  => 'Ne-datoteke ne možete premestiti u imenski prostor za datoteke',
'imagetypemismatch'            => 'Ekstenzija nove datoteke se ne poklapa s njenom vrstom',
'imageinvalidfilename'         => 'Ciljani naziv datoteke je neispravan',
'fix-double-redirects'         => 'Ažurirajte sva preusmerenja koja vode do prvobitnog naslova',
'move-leave-redirect'          => 'Napravi preusmerenje',
'protectedpagemovewarning'     => "'''Upozorenje:''' ova stranica je zaštićena, tako da samo korisnici s administratorskim ovlašćenjima mogu da je premeste.
Za više informacija, poslednji zapis u dnevniku izmena je prikazan ispod:",
'semiprotectedpagemovewarning' => "'''Napomena:''' ova stranica je zaštićena, tako da samo registrovani korisnici mogu da je premeste.
Za više informacija, poslednji zapis u dnevniku izmena je prikazan ispod:",
'move-over-sharedrepo'         => '== Datoteka postoji ==
[[:$1]] se nalazi na deljenom skladištu. Ako premestite datoteku na ovaj naslov, to će zameniti deljenu datoteku.',
'file-exists-sharedrepo'       => 'Navedeni naziv datoteke se već koristi u deljenom skladištu.
Izaberite drugi naziv.',

# Export
'export'            => 'Izvoz stranica',
'exporttext'        => 'Možete izvesti tekst i istoriju izmena određene stranice ili grupe stranica u formatu XML.
Ovo onda može biti uvezeno u drugi viki koji koristi Medijaviki softver preko [[Special:Import|stranice za uvoz]].

Da biste izvezli stranice, unesite nazive u okviru ispod, s jednim naslovom po redu, i izaberite da li želite tekuću izmenu i sve ostale, ili samo tekuću izmenu s podacima o poslednjoj izmeni.

U drugom slučaju, možete koristiti i vezu, na primer [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] za stranicu [[{{MediaWiki:Mainpage}}]].',
'exportall'         => 'Izvezi sve stranice',
'exportcuronly'     => 'Uključi samo tekuću izmenu, ne celu istoriju',
'exportnohistory'   => "----
'''Napomena:''' izvoz pune istorije stranica preko ovog obrasca je onemogućeno iz tehničkih razloga.",
'exportlistauthors' => 'Uključi celokupan spisak doprinosilaca za svaku stranicu',
'export-submit'     => 'Izvezi',
'export-addcattext' => 'Dodaj stranice iz kategorije:',
'export-addcat'     => 'Dodaj',
'export-addnstext'  => 'Dodaj stranice iz imenskog prostora:',
'export-addns'      => 'Dodaj',
'export-download'   => 'Sačuvaj kao datoteku',
'export-templates'  => 'Uključi šablone',
'export-pagelinks'  => 'Uključi povezane stranice do dubine od:',

# Namespace 8 related
'allmessages'                   => 'Sistemske poruke',
'allmessagesname'               => 'Naziv',
'allmessagesdefault'            => 'Podrazumevani tekst',
'allmessagescurrent'            => 'Tekst poruke',
'allmessagestext'               => 'Ovo je spisak svih sistemskih poruka koje su dostupne u imenskom prostoru „Medijaviki“.
Posetite [//www.mediawiki.org/wiki/Localisation Medijaviki lokalizaciju] i [//translatewiki.net Translejtviki] ako želite da pomognete u prevođenju.',
'allmessagesnotsupportedDB'     => "Ova stranica ne može da se koristi jer je '''\$wgUseDatabaseMessages''' onemogućen.",
'allmessages-filter-legend'     => 'Filter',
'allmessages-filter'            => 'Filtriraj po stanju:',
'allmessages-filter-unmodified' => 'neizmenjene',
'allmessages-filter-all'        => 'sve',
'allmessages-filter-modified'   => 'izmenjene',
'allmessages-prefix'            => 'Filtriraj po predmetku:',
'allmessages-language'          => 'Jezik:',
'allmessages-filter-submit'     => 'Idi',

# Thumbnails
'thumbnail-more'           => 'Povećaj',
'filemissing'              => 'Datoteka nedostaje',
'thumbnail_error'          => 'Greška pri stvaranju minijature: $1',
'djvu_page_error'          => 'DjVu stranica je nedostupna',
'djvu_no_xml'              => 'Ne mogu da preuzmem XML za datoteku DjVu.',
'thumbnail-temp-create'    => 'Ne mogu da napravim privremenu datoteku minijature',
'thumbnail-dest-create'    => 'Ne mogu da sačuvam minijaturu u odredištu',
'thumbnail_invalid_params' => 'Neispravni parametri za minijaturu',
'thumbnail_dest_directory' => 'Ne mogu da napravim odredišnu fasciklu',
'thumbnail_image-type'     => 'Vrsta slike nije podržana',
'thumbnail_gd-library'     => 'Nedovršene postavke grafičke biblioteke: nedostaje funkcija $1',
'thumbnail_image-missing'  => 'Datoteka nedostaje: $1',

# Special:Import
'import'                     => 'Uvoz stranica',
'importinterwiki'            => 'Međuviki uvoz',
'import-interwiki-text'      => 'Izaberite viki i naslov stranice za uvoz.
Datumi i imena urednika će biti sačuvani.
Sve radnje pri uvozu s drugih vikija su zabeležene u [[Special:Log/import|dnevniku uvoza]].',
'import-interwiki-source'    => 'Izvor vikija/stranice:',
'import-interwiki-history'   => 'Umnoži sve izmene ove stranice',
'import-interwiki-templates' => 'Uključi sve šablone',
'import-interwiki-submit'    => 'Uvezi',
'import-interwiki-namespace' => 'Odredišni imenski prostor:',
'import-upload-filename'     => 'Naziv datoteke:',
'import-comment'             => 'Komentar:',
'importtext'                 => 'Izvezite datoteku s izvornog vikija koristeći [[Special:Export|izvoz]].
Sačuvajte je na računar i pošaljite ovde.',
'importstart'                => 'Uvozim stranice…',
'import-revision-count'      => '$1 {{PLURAL:$1|izmena|izmene|izmena}}',
'importnopages'              => 'Nema stranica za uvoz.',
'imported-log-entries'       => '{{PLURAL:$1|Uvezena je $1 stavka izveštaja|Uvezene su $1 stavke izveštaja|Uvezeno je $1 stavki izveštaja}}.',
'importfailed'               => 'Neuspešan uvoz: <nowiki>$1</nowiki>',
'importunknownsource'        => 'Nepoznata vrsta za uvoz',
'importcantopen'             => 'Ne mogu da otvorim datoteku za uvoz',
'importbadinterwiki'         => 'Neispravna međuviki veza',
'importnotext'               => 'Prazno ili bez teksta',
'importsuccess'              => 'Uvoženje je završeno!',
'importhistoryconflict'      => 'Postoji sukobljena izmena u istoriji (možda je ova stranica već uvezena ranije)',
'importnosources'            => 'Nije određen nijedan međuviki izvor za uvoz, tako da je otpremanje istorije onemogućeno.',
'importnofile'               => 'Uvozna datoteka nije poslata.',
'importuploaderrorsize'      => 'Ne mogu da otpremim datoteku za uvoz.
Datoteka je veća od dozvoljene veličine.',
'importuploaderrorpartial'   => 'Ne mogu da otpremim datoteku za uvoz.
Datoteka je samo delimično poslata.',
'importuploaderrortemp'      => 'Ne mogu da pošaljem datoteku za uvoz.
Nedostaje privremena fascikla.',
'import-parse-failure'       => 'Pogrešno raščlanjivanje XML-a.',
'import-noarticle'           => 'Nema stranice za uvoz!',
'import-nonewrevisions'      => 'Sve izmene su prethodno uvezene.',
'xml-error-string'           => '$1 u redu $2, kolona $3 (bajt $4): $5',
'import-upload'              => 'Otpremanje XML podataka',
'import-token-mismatch'      => 'Gubitak podataka o sesiji.
Pokušajte ponovo.',
'import-invalid-interwiki'   => 'Ne mogu da uvozim s navedenog vikija.',
'import-error-edit'          => 'Stranica „$1“ nije uvezena jer vam nije dozvoljeno da je uređujete.',
'import-error-create'        => 'Stranica „$1“ nije uvezena jer vam nije dozvoljeno da je napravite.',
'import-error-interwiki'     => 'Ne mogu da uvezem stranicu „$1“ jer je njen naziv rezervisan za spoljno povezivanje (međuviki).',
'import-error-special'       => 'Ne mogu da uvezem stranicu „$1“ jer ona pripada posebnom imenskom prostoru koje ne prihvata stranice.',
'import-error-invalid'       => 'Ne mogu da uvezem stranicu „$1“ jer je njen naziv neispravan.',

# Import log
'importlogpage'                    => 'Dnevnik uvoza',
'importlogpagetext'                => 'Administrativni uvozi stranica s istorijama izmena s drugih vikija.',
'import-logentry-upload'           => '{{GENDER:|je uvezao|je uvezla|uvede}} „[[$1]]“ otpremanjem datoteke',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|izmena|izmene|izmena}}',
'import-logentry-interwiki'        => 'premešteno s drugog vikija: $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|izmena|izmene|izmena}} od $2',

# JavaScriptTest
'javascripttest'                           => 'Javaskript test',
'javascripttest-disabled'                  => 'Ova funkcija nije omogućena na ovom vikiju.',
'javascripttest-title'                     => 'Izvršavanje testova za $1',
'javascripttest-pagetext-noframework'      => 'Ova stranica je rezervisana za izvršavanje javaskript testova.',
'javascripttest-pagetext-unknownframework' => 'Nepoznati radni okvir „$1“.',
'javascripttest-pagetext-frameworks'       => 'Izaberite jedan od sledećih radnih okvira: $1',
'javascripttest-pagetext-skins'            => 'Izaberite s kojom temom želite da pokrenete probu:',
'javascripttest-qunit-intro'               => 'Pogledajte [$1 dokumentaciju za testiranje] na mediawiki.org.',
'javascripttest-qunit-heading'             => 'Medijavikijin paket za testiranje – QUnit',

# Tooltip help for the actions
'tooltip-pt-userpage'                 => 'Vaša korisnička stranica',
'tooltip-pt-anonuserpage'             => 'Korisnička stranica za IP adresu s koje uređujete',
'tooltip-pt-mytalk'                   => 'Vaša stranica za razgovor',
'tooltip-pt-anontalk'                 => 'Razgovor o izmenama s ove IP adrese',
'tooltip-pt-preferences'              => 'Vaša podešavanja',
'tooltip-pt-watchlist'                => 'Spisak stranica koje nadgledate',
'tooltip-pt-mycontris'                => 'Spisak vaših doprinosa',
'tooltip-pt-login'                    => 'Preporučujemo vam da se prijavite, iako to nije obavezno.',
'tooltip-pt-anonlogin'                => 'Preporučujemo vam da se prijavite, iako to nije obavezno.',
'tooltip-pt-logout'                   => 'Odjavite se',
'tooltip-ca-talk'                     => 'Razgovor o stranici sa sadržajem',
'tooltip-ca-edit'                     => 'Možete da uređujete ovu stranicu. Koristite pretpregled pre snimanja',
'tooltip-ca-addsection'               => 'Započnite novi odeljak',
'tooltip-ca-viewsource'               => 'Ova stranica je zaključana. Možete da vidite izvorni kod.',
'tooltip-ca-history'                  => 'Prethodne verzije ove stranice',
'tooltip-ca-protect'                  => 'Zaštitite ovu stranicu',
'tooltip-ca-unprotect'                => 'Promeni zaštitu ove stranice',
'tooltip-ca-delete'                   => 'Obrišite ovu stranicu',
'tooltip-ca-undelete'                 => 'Vrati izmene napravljene na ovoj stranici pre nego što bude obrisana',
'tooltip-ca-move'                     => 'Premesti ovu stranicu',
'tooltip-ca-watch'                    => 'Dodaj ovu stranicu na spisak nadgledanja',
'tooltip-ca-unwatch'                  => 'Ukloni ovu stranicu sa spiska nadgledanja',
'tooltip-search'                      => 'Pretraga',
'tooltip-search-go'                   => 'Idite na stranicu s ovim imenom, ako postoji',
'tooltip-search-fulltext'             => 'Pretražite stranice s ovim tekstom',
'tooltip-p-logo'                      => 'Posetite naslovnu stranu',
'tooltip-n-mainpage'                  => 'Posetite naslovnu stranu',
'tooltip-n-mainpage-description'      => 'Posetite naslovnu stranu',
'tooltip-n-portal'                    => 'O projektu, šta možete da radite i gde da pronađete stvari',
'tooltip-n-currentevents'             => 'Saznajte više o tekućim događajima',
'tooltip-n-recentchanges'             => 'Spisak skorašnjih izmena na vikiju',
'tooltip-n-randompage'                => 'Otvorite slučajnu stranicu',
'tooltip-n-help'                      => 'Mesto gde možete da se informišete',
'tooltip-t-whatlinkshere'             => 'Spisak svih stranica koje vode ovde',
'tooltip-t-recentchangeslinked'       => 'Skorašnje izmene na stranicama koje su povezana s ovom stranicom',
'tooltip-feed-rss'                    => 'RSS dovod ove stranice',
'tooltip-feed-atom'                   => 'Atom dovod ove stranice',
'tooltip-t-contributions'             => 'Pogledajte spisak doprinosa ovog korisnika',
'tooltip-t-emailuser'                 => 'Pošaljite e-poruku ovom korisniku',
'tooltip-t-upload'                    => 'Pošaljite datoteke',
'tooltip-t-specialpages'              => 'Spisak svih posebnih stranica',
'tooltip-t-print'                     => 'Izdanje za štampanje ove stranice',
'tooltip-t-permalink'                 => 'Stalna veza ka ovoj izmeni stranice',
'tooltip-ca-nstab-main'               => 'Pogledajte stranicu sa sadržajem',
'tooltip-ca-nstab-user'               => 'Pogledajte korisničku stranicu',
'tooltip-ca-nstab-media'              => 'Pogledajte multimedijalnu datoteku',
'tooltip-ca-nstab-special'            => 'Ovo je posebna stranica. Ne možete je menjati.',
'tooltip-ca-nstab-project'            => 'Pogledajte stranicu projekta',
'tooltip-ca-nstab-image'              => 'Prikaži stranu datoteke',
'tooltip-ca-nstab-mediawiki'          => 'Pogledajte sistemsku poruku',
'tooltip-ca-nstab-template'           => 'Pogledajte šablon',
'tooltip-ca-nstab-help'               => 'Pogledajte stranicu za pomoć',
'tooltip-ca-nstab-category'           => 'Pogledajte stranicu kategorija',
'tooltip-minoredit'                   => 'Označite izmenu kao manju',
'tooltip-save'                        => 'Sačuvajte izmene koje ste napravili',
'tooltip-preview'                     => 'Pregledajte svoje izmene. Poželjno je da koristite ovo dugme pre čuvanja',
'tooltip-diff'                        => 'Pogledajte sve izmene koje ste napravili na tekstu',
'tooltip-compareselectedversions'     => 'Pogledajte razlike između dve izabrane izmene ove stranice.',
'tooltip-watch'                       => 'Dodajte ovu stranicu na spisak nadgledanja',
'tooltip-watchlistedit-normal-submit' => 'Ukloni naslove',
'tooltip-watchlistedit-raw-submit'    => 'Ažuriraj spisak',
'tooltip-recreate'                    => 'Ponovo napravite stranicu iako je obrisana',
'tooltip-upload'                      => 'Započnite otpremanje',
'tooltip-rollback'                    => 'Opcija „Vrati“ vraća izmene poslednjeg korisnika',
'tooltip-undo'                        => 'Vraća ovu izmenu i otvara obrazac za uređivanje.',
'tooltip-preferences-save'            => 'Sačuvaj postavke',
'tooltip-summary'                     => 'Unesite kratak opis',

# Stylesheets
'common.css'              => '/** CSS postavljen ovde će se odraziti na sve teme */',
'standard.css'            => '/* CSS postavljen ovde će uticati na sve korisnike teme „Standardno“ */',
'nostalgia.css'           => '/* CSS postavljen ovde će uticati na sve korisnike teme „Nostalgija“ */',
'cologneblue.css'         => '/* CSS postavljen ovde će uticati na sve korisnike teme „Kelnsko plava“ */',
'monobook.css'            => '/* CSS postavljen ovde će uticati na sve korisnike teme „Monobuk“ */',
'myskin.css'              => '/* CSS postavljen ovde će uticati na sve korisnike teme „Moja tema“ */',
'chick.css'               => '/* CSS postavljen ovde će uticati na sve korisnike teme „Šik“ */',
'simple.css'              => '/* CSS postavljen ovde će uticati na sve korisnike teme „Prosto“ */',
'modern.css'              => '/* CSS postavljen ovde će uticati na sve korisnike teme „Savremeno“ */',
'vector.css'              => '/* CSS postavljen ovde će uticati na sve korisnike teme „Vektorsko“ */',
'print.css'               => '/* CSS postavljen ovde će uticati na izdanje za štampu */',
'handheld.css'            => '/* CSS postavljen ovde će uticati na ručne uređaje s temom prilagođenom u $wgHandheldStyle */',
'noscript.css'            => '/* CSS postavljen ovde će uticati na sve korisnike kojima je onemogućen javaskript */',
'group-autoconfirmed.css' => '/* CSS postavljen ovde će uticati na samopotvrđene korisnike */',
'group-bot.css'           => '/* CSS postavljen ovde će uticati samo na botove */',
'group-sysop.css'         => '/* CSS postavljen ovde će uticati samo na sistemske operatore */',
'group-bureaucrat.css'    => '/* CSS postavljen ovde će uticati samo na birokrate */',

# Scripts
'common.js'              => '/* Javaskript postavljen ovde će se koristiti za sve korisnike pri otvaranju svake stranice. */',
'standard.js'            => '/* Javaskript postavljen ovde će se učitati za sve one koji koriste temu „Standardno“ */',
'nostalgia.js'           => '/* Javaskript postavljen ovde će se učitati za sve one koji koriste temu „Nostalgija“ */',
'cologneblue.js'         => '/* Javaskript postavljen ovde će se učitati za sve one koji koriste temu „Kelnsko plava“ */',
'monobook.js'            => '/* Javaskript postavljen ovde će se učitati za sve one koji koriste temu „Monobuk“ */',
'myskin.js'              => '/* Javaskript postavljen ovde će se učitati za sve one koji koriste „Moju temu“ */',
'chick.js'               => '/* Javaskript postavljen ovde će se učitati za sve one koji koriste temu „Šik“ */',
'simple.js'              => '/* Javaskript postavljen ovde će se učitati za sve one koji koriste temu „Prosto“ */',
'modern.js'              => '/* Javaskript postavljen ovde će se učitati za sve one koji koriste temu „Savremeno“ */',
'vector.js'              => '/* Javaskript postavljen ovde će se učitati za sve one koji koriste temu „Vektorsko“ */',
'group-autoconfirmed.js' => '/* Javaskript postavljen ovde će se učitati za samopotvrđene korisnike */',
'group-bot.js'           => '/* Javaskript postavljen ovde će se učitati samo za botove */',
'group-sysop.js'         => '/* Javaskript postavljen ovde će se učitati samo za sistemske operatore */',
'group-bureaucrat.js'    => '/* Javaskript postavljen ovde će se učitati samo za birokrate */',

# Metadata
'notacceptable' => 'Server ne može da napravi podatke u formatu pogodnom za vaš klijent.',

# Attribution
'anonymous'        => 'Anonimni {{PLURAL:$1|korisnik|korisnici}} na projektu {{SITENAME}}',
'siteuser'         => '{{SITENAME}} korisnik $1',
'anonuser'         => '{{SITENAME}} anoniman korisnik $1',
'lastmodifiedatby' => 'Ovu stranicu je poslednji put {{GENDER:$4|izmenio|izmenila|izmenio}} $3, $1 u $2.',
'othercontribs'    => 'Zasnovano na radu korisnikâ $1.',
'others'           => 'drugi',
'siteusers'        => '{{PLURAL:$2|korisnik|korisnici}} na projektu {{SITENAME}} $1',
'anonusers'        => '{{PLURAL:$2|anoniman korisnik|anonimni korisnici}} na projektu {{SITENAME}} $1',
'creditspage'      => 'Autori stranice',
'nocredits'        => 'Ne postoje podaci o autoru ove stranice.',

# Spam protection
'spamprotectiontitle' => 'Filter za zaštitu od nepoželjnih poruka',
'spamprotectiontext'  => 'Stranica koju želite da sačuvate je blokirana od filtera protiv nepoželjnih poruka.
Ovo je verovatno izazvano vezom do spoljašnjeg sajta koji se nalazi na crnoj listi.',
'spamprotectionmatch' => 'Sledeći tekst je izazvao naš filter za nepoželjne poruke: $1',
'spambot_username'    => 'Čišćenje nepoželjnih poruka u Medijavikiji',
'spam_reverting'      => 'Vraćam na poslednju izmenu koja ne sadrži veze do $1',
'spam_blanking'       => 'Sve izmene sadrže veze do $1. Čistim',

# Info page
'pageinfo-title'            => 'Podaci o „$1“',
'pageinfo-header-edits'     => 'Istorija izmena',
'pageinfo-header-watchlist' => 'Spisak nadgledanja',
'pageinfo-header-views'     => 'Pregleda',
'pageinfo-subjectpage'      => 'Stranica',
'pageinfo-talkpage'         => 'Stranica za razgovor',
'pageinfo-watchers'         => 'Broj nadgledača stranica',
'pageinfo-edits'            => 'Broj izmena',
'pageinfo-authors'          => 'Broj zasebnih autora',
'pageinfo-views'            => 'Broj pregleda',
'pageinfo-viewsperedit'     => 'Pregleda po izmeni',

# Skin names
'skinname-standard'    => 'Klasično',
'skinname-nostalgia'   => 'Nostalgija',
'skinname-cologneblue' => 'Kelnsko plava',
'skinname-monobook'    => 'Monobuk',
'skinname-myskin'      => 'Moja tema',
'skinname-chick'       => 'Šik',
'skinname-simple'      => 'Prosto',
'skinname-modern'      => 'Savremeno',
'skinname-vector'      => 'Vektorsko',

# Patrolling
'markaspatrolleddiff'                 => 'Označi kao patrolirano',
'markaspatrolledtext'                 => 'Označi stranicu kao patroliranu',
'markedaspatrolled'                   => 'Označeno kao patrolirano',
'markedaspatrolledtext'               => 'Izabrana izmena na [[:$1]] je označena kao pregledana.',
'rcpatroldisabled'                    => 'Patroliranje skorašnjih izmena je onemogućeno',
'rcpatroldisabledtext'                => 'Patroliranje skorašnjih izmena je onemogućeno.',
'markedaspatrollederror'              => 'Ne mogu da označim kao patrolirano',
'markedaspatrollederrortext'          => 'Morate izabrati izmenu da biste je označili kao pregledanu.',
'markedaspatrollederror-noautopatrol' => 'Ne možete da označite svoje izmene kao patrolirane.',

# Patrol log
'patrol-log-page'      => 'Dnevnik patroliranja',
'patrol-log-header'    => 'Ovo je dnevnik patroliranih izmena.',
'log-show-hide-patrol' => '$1 dnevnik patroliranja',

# Image deletion
'deletedrevision'                 => 'Obrisana stara izmena $1.',
'filedeleteerror-short'           => 'Greška pri brisanju datoteke: $1',
'filedeleteerror-long'            => 'Došlo je do grešaka pri brisanju datoteke:

$1',
'filedelete-missing'              => 'Datoteka „$1“ se ne može obrisati jer ne postoji.',
'filedelete-old-unregistered'     => 'Navedena izmena datoteke „$1“ ne postoji u bazi podataka.',
'filedelete-current-unregistered' => 'Navedena datoteka „$1“ ne postoji u bazi podataka.',
'filedelete-archive-read-only'    => 'Server ne može da piše po skladišnoj fascikli ($1).',

# Browsing diffs
'previousdiff' => '← Starija izmena',
'nextdiff'     => 'Novija izmena →',

# Media information
'mediawarning'           => "'''Upozorenje''': ova vrsta datoteke može sadržati štetan kod.
Ako ga pokrenete, vaš računar može biti ugrožen.",
'imagemaxsize'           => "Ograničenje veličine slike:<br />''(na stranicama za opis datoteka)''",
'thumbsize'              => 'Veličina minijature:',
'widthheight'            => '$1 × $2',
'widthheightpage'        => '$1 × $2, $3 {{PLURAL:$3|stranica|stranice|stranica}}',
'file-info'              => 'veličina: $1, MIME vrsta: $2',
'file-info-size'         => '$1 × $2 piksela, veličina: $3, MIME vrsta: $4',
'file-info-size-pages'   => '$1 × $2 piksela, veličina: $3, MIME vrsta: $4, $5 {{PLURAL:$5|stranica|stranice|stranica}}',
'file-nohires'           => 'Veća rezolucija nije dostupna.',
'svg-long-desc'          => 'SVG datoteka, nominalno $1 × $2 piksela, veličina: $3',
'show-big-image'         => 'Puna veličina',
'show-big-image-preview' => 'Veličina ovog prikaza: $1.',
'show-big-image-other'   => '{{PLURAL:$2|Druga rezolucija|Druge rezolucije}}: $1.',
'show-big-image-size'    => '$1 × $2 piksela',
'file-info-gif-looped'   => 'petlja',
'file-info-gif-frames'   => '$1 {{PLURAL:$1|kadar|kadra|kadrova}}',
'file-info-png-looped'   => 'petlja',
'file-info-png-repeat'   => 'ponovljeno $1 {{PLURAL:$1|put|puta|puta}}',
'file-info-png-frames'   => '$1 {{PLURAL:$1|kadar|kadra|kadrova}}',

# Special:NewFiles
'newimages'             => 'Galerija novih datoteka',
'imagelisttext'         => "Ispod je spisak od '''$1''' {{PLURAL:$1|datoteke|datoteke|datoteka}} poređanih $2.",
'newimages-summary'     => 'Ova posebna stranica prikazuje poslednje poslate datoteke.',
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Naziv datoteke (ili njen deo):',
'showhidebots'          => '($1 botove)',
'noimages'              => 'Nema ništa.',
'ilsubmit'              => 'Pretraži',
'bydate'                => 'po datumu',
'sp-newimages-showfrom' => 'prikaži nove datoteke počevši od $1, $2',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1, $2×$3',
'seconds-abbrev' => '$1 s',
'minutes-abbrev' => '$1 m',
'hours-abbrev'   => '$1 č',
'days-abbrev'    => '$1 d',
'seconds'        => '{{PLURAL:$1|$1 sekunda|$1 sekunde|$1 sekundi}}',
'minutes'        => '{{PLURAL:$1|$1 minut|$1 minuta|$1 minuta}}',
'hours'          => '{{PLURAL:$1|$1 sat|$1 sata|$1 sati}}',
'days'           => '{{PLURAL:$1|$1 dan|$1 dana|$1 dana}}',
'ago'            => 'pre $1',

# Bad image list
'bad_image_list' => 'Format je sledeći:

Razmatraju se samo nabrajanja (redovi koji počinju sa zvezdicom).
Prva veza u redu mora da bude veza do neispravne datoteke.
Sve daljnje veze u istom redu smatraju se izuzecima.',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => 'hans',
'variantname-zh-hant' => 'hant',
'variantname-zh-cn'   => 'cn',
'variantname-zh-tw'   => 'tw',
'variantname-zh-hk'   => 'hk',
'variantname-zh-mo'   => 'mo',
'variantname-zh-sg'   => 'sg',
'variantname-zh-my'   => 'my',
'variantname-zh'      => 'zh',

# Variants for Gan language
'variantname-gan-hans' => 'hans',
'variantname-gan-hant' => 'hant',
'variantname-gan'      => 'gan',

# Variants for Serbian language
'variantname-sr-ec' => 'Ćirilica',
'variantname-sr-el' => 'Latinica',
'variantname-sr'    => 'sr',

# Variants for Kazakh language
'variantname-kk-kz'   => 'kk-kz',
'variantname-kk-tr'   => 'kk-tr',
'variantname-kk-cn'   => 'kk-cn',
'variantname-kk-cyrl' => 'kk-cyrl',
'variantname-kk-latn' => 'kk-latn',
'variantname-kk-arab' => 'kk-arab',
'variantname-kk'      => 'kk',

# Variants for Kurdish language
'variantname-ku-arab' => 'ku-Arab',
'variantname-ku-latn' => 'ku-Latn',
'variantname-ku'      => 'ku',

# Variants for Tajiki language
'variantname-tg-cyrl' => 'tg-Cyrl',
'variantname-tg-latn' => 'tg-Latn',
'variantname-tg'      => 'tg',

# Variants for Inuktitut language
'variantname-ike-cans' => 'ike-Cans',
'variantname-ike-latn' => 'ike-Latn',
'variantname-iu'       => 'iu',

# Variants for Tachelhit language
'variantname-shi-tfng' => 'shi-Tfng',
'variantname-shi-latn' => 'shi-Latn',
'variantname-shi'      => 'shi',

# Metadata
'metadata'                  => 'Metapodaci',
'metadata-help'             => 'Ova datoteka sadrži dodatne podatke koji verovatno dolaze od digigalnih fotoaparata ili skenera.
Ako je prvobitno stanje datoteke promenjeno, moguće je da neki detalji ne opisuju izmenjenu datoteku.',
'metadata-expand'           => 'Prikaži detalje',
'metadata-collapse'         => 'Sakrij detalje',
'metadata-fields'           => 'Polja za metapodatke slike navedena u ovoj poruci će biti uključena na stranici za slike kada se skupi tabela metapodataka. Ostala polja će biti sakrivena po podrazumevanim postavkama.
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
'metadata-langitem'         => "'''$2:''' $1",
'metadata-langitem-default' => '$1',

# EXIF tags
'exif-imagewidth'                  => 'Širina',
'exif-imagelength'                 => 'Visina',
'exif-bitspersample'               => 'Dubina boje',
'exif-compression'                 => 'Način sažimanja',
'exif-photometricinterpretation'   => 'Sastav piksela',
'exif-orientation'                 => 'Orijentacija',
'exif-samplesperpixel'             => 'Broj delova',
'exif-planarconfiguration'         => 'Raspored podataka',
'exif-ycbcrsubsampling'            => 'Odnos veličine Y prema C',
'exif-ycbcrpositioning'            => 'Položaj Y i C',
'exif-xresolution'                 => 'Vodoravna rezolucija',
'exif-yresolution'                 => 'Uspravna rezolucija',
'exif-stripoffsets'                => 'Mesto podataka',
'exif-rowsperstrip'                => 'Broj redova po liniji',
'exif-stripbytecounts'             => 'Bajtova po sažetom bloku',
'exif-jpeginterchangeformat'       => 'Početak JPEG pregleda',
'exif-jpeginterchangeformatlength' => 'Bajtovi JPEG podataka',
'exif-whitepoint'                  => 'Hromatičnost bele tačke',
'exif-primarychromaticities'       => 'Hromatičnost osnovnih boja',
'exif-ycbcrcoefficients'           => 'Matrični koeficijenti transformacije bojnog prostora',
'exif-referenceblackwhite'         => 'Uputne vrednosti para bele i crne tačke',
'exif-datetime'                    => 'Datum i vreme poslednje izmene datoteke',
'exif-imagedescription'            => 'Naziv slike',
'exif-make'                        => 'Proizvođač kamere',
'exif-model'                       => 'Model kamere',
'exif-software'                    => 'Korišćeni softver',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Nosilac autorskih prava',
'exif-exifversion'                 => 'Exif izdanje',
'exif-flashpixversion'             => 'Podržano izdanje FlashPix-a',
'exif-colorspace'                  => 'Prostor boje',
'exif-componentsconfiguration'     => 'Značenje svakog dela',
'exif-compressedbitsperpixel'      => 'Režim sažimanja slike',
'exif-pixelydimension'             => 'Širina slike',
'exif-pixelxdimension'             => 'Visina slike',
'exif-usercomment'                 => 'Korisnički komentari',
'exif-relatedsoundfile'            => 'Povezani zvučni zapis',
'exif-datetimeoriginal'            => 'Datum i vreme slikanja',
'exif-datetimedigitized'           => 'Datum i vreme digitalizacije',
'exif-subsectime'                  => 'Deo sekunde u kojem je slikano',
'exif-subsectimeoriginal'          => 'Deo sekunde u kojem je fotografisano',
'exif-subsectimedigitized'         => 'Deo sekunde u kojem je digitalizovano',
'exif-exposuretime'                => 'Ekspozicija',
'exif-exposuretime-format'         => '$1 sek. ($2)',
'exif-fnumber'                     => 'F broj',
'exif-fnumber-format'              => 'f/$1',
'exif-exposureprogram'             => 'Režim ekspozicije',
'exif-spectralsensitivity'         => 'Spektralna osetljivost',
'exif-isospeedratings'             => 'ISO osetljivost na svetlo',
'exif-shutterspeedvalue'           => 'Brzina zatvarača',
'exif-aperturevalue'               => 'Otvor blende',
'exif-brightnessvalue'             => 'Osvetljenost',
'exif-exposurebiasvalue'           => 'Kompenzacija ekspozicije',
'exif-maxaperturevalue'            => 'Najveći broj otvora blende',
'exif-subjectdistance'             => 'Udaljenost do objekta',
'exif-meteringmode'                => 'Režim merača',
'exif-lightsource'                 => 'Izvor svetlosti',
'exif-flash'                       => 'Blic',
'exif-focallength'                 => 'Žarišna daljina sočiva',
'exif-focallength-format'          => '$1 mm',
'exif-subjectarea'                 => 'Položaj i površina objekta',
'exif-flashenergy'                 => 'Energija blica',
'exif-focalplanexresolution'       => 'Rezolucija fokusne ravni X',
'exif-focalplaneyresolution'       => 'Rezolucija fokusne ravni Y',
'exif-focalplaneresolutionunit'    => 'Jedinica za rezoluciju fokusne ravni',
'exif-subjectlocation'             => 'Položaj objekta',
'exif-exposureindex'               => 'Popis ekspozicije',
'exif-sensingmethod'               => 'Vrsta senzora',
'exif-filesource'                  => 'Izvorna datoteka',
'exif-scenetype'                   => 'Vrsta scene',
'exif-customrendered'              => 'Prilagođena obrada slika',
'exif-exposuremode'                => 'Režim ekspozicije',
'exif-whitebalance'                => 'Bela ravnoteža',
'exif-digitalzoomratio'            => 'Odnos digitalnog uveličanja',
'exif-focallengthin35mmfilm'       => 'Žarišna daljina za film od 35 mm',
'exif-scenecapturetype'            => 'Vrsta snimanja scena',
'exif-gaincontrol'                 => 'Kontrola scene',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Zasićenje',
'exif-sharpness'                   => 'Oštrina',
'exif-devicesettingdescription'    => 'Opis postavki uređaja',
'exif-subjectdistancerange'        => 'Opseg udaljenosti objekta',
'exif-imageuniqueid'               => 'Naznaka slike',
'exif-gpsversionid'                => 'Izdanje GPS oznake',
'exif-gpslatituderef'              => 'Severna ili južna širina',
'exif-gpslatitude'                 => 'Širina',
'exif-gpslongituderef'             => 'Istočna ili zapadna dužina',
'exif-gpslongitude'                => 'Dužina',
'exif-gpsaltituderef'              => 'Uputna tačka za visinu',
'exif-gpsaltitude'                 => 'Visina',
'exif-gpstimestamp'                => 'GPS vreme (atomski sat)',
'exif-gpssatellites'               => 'Korišćeni sateliti',
'exif-gpsstatus'                   => 'Stanje prijemnika',
'exif-gpsmeasuremode'              => 'Režim merenja',
'exif-gpsdop'                      => 'Preciznost merenja',
'exif-gpsspeedref'                 => 'Jedinica za brzinu',
'exif-gpsspeed'                    => 'Brzina GPS prijemnika',
'exif-gpstrackref'                 => 'Uput za pravac kretanja',
'exif-gpstrack'                    => 'Pravac kretanja',
'exif-gpsimgdirectionref'          => 'Uput za pravac slike',
'exif-gpsimgdirection'             => 'Pravac slike',
'exif-gpsmapdatum'                 => 'Korišćeni geodetski koordinatni sistem',
'exif-gpsdestlatituderef'          => 'Indeks za geografsku širinu objekta',
'exif-gpsdestlatitude'             => 'Geografska širina objekta',
'exif-gpsdestlongituderef'         => 'Indeks za geografsku dužinu objekta',
'exif-gpsdestlongitude'            => 'Geografska dužina objekta',
'exif-gpsdestbearingref'           => 'Indeks azimuta objekta',
'exif-gpsdestbearing'              => 'Azimut objekta',
'exif-gpsdestdistanceref'          => 'Merna jedinica za udaljenost objekta',
'exif-gpsdestdistance'             => 'Udaljenost objekta',
'exif-gpsprocessingmethod'         => 'Ime načina obrade GPS podataka',
'exif-gpsareainformation'          => 'Ime GPS područja',
'exif-gpsdatestamp'                => 'GPS datum',
'exif-gpsdifferential'             => 'GPS diferencijalna ispravka',
'exif-coordinate-format'           => '$1° $2′ $3″ $4',
'exif-jpegfilecomment'             => 'Komentar na datoteku JPEG',
'exif-keywords'                    => 'Ključne reči',
'exif-worldregioncreated'          => 'Oblast sveta gde je slikana fotografija',
'exif-countrycreated'              => 'Zemlja gde je slikana fotografija',
'exif-countrycodecreated'          => 'Kod zemlje gde je slika napravljena',
'exif-provinceorstatecreated'      => 'Pokrajina ili država gde je slikana fotografija',
'exif-citycreated'                 => 'Grad gde je slikana fotografija',
'exif-sublocationcreated'          => 'Oblast grada gde je slikana fotografija',
'exif-worldregiondest'             => 'Prikazana oblast sveta',
'exif-countrydest'                 => 'Prikazana zemlja',
'exif-countrycodedest'             => 'Kod prikazane zemlje',
'exif-provinceorstatedest'         => 'Prikazana pokrajina ili država',
'exif-citydest'                    => 'Prikazani grad',
'exif-sublocationdest'             => 'Prikazana oblast grada',
'exif-objectname'                  => 'Kratak naslov',
'exif-specialinstructions'         => 'Posebna uputstva',
'exif-headline'                    => 'Naslov',
'exif-credit'                      => 'Zasluge/pružalac usluga',
'exif-source'                      => 'Izvor',
'exif-editstatus'                  => 'Urednički status slike',
'exif-urgency'                     => 'Hitnost',
'exif-fixtureidentifier'           => 'Naziv rubrike',
'exif-locationdest'                => 'Prikazana lokacija',
'exif-locationdestcode'            => 'Kod prikazanog mesta',
'exif-objectcycle'                 => 'Doba dana za koji je medij namenjen',
'exif-contact'                     => 'Podaci za kontakt',
'exif-writer'                      => 'Pisac',
'exif-languagecode'                => 'Jezik',
'exif-iimversion'                  => 'IIM izdanje',
'exif-iimcategory'                 => 'Kategorija',
'exif-iimsupplementalcategory'     => 'Dopunske kategorije',
'exif-datetimeexpires'             => 'Ne koristi nakon',
'exif-datetimereleased'            => 'Objavljeno',
'exif-originaltransmissionref'     => 'Izvorni prenos kôda lokacije',
'exif-identifier'                  => 'Naznaka',
'exif-lens'                        => 'Korišćeni objektiv',
'exif-serialnumber'                => 'Serijski broj kamere',
'exif-cameraownername'             => 'Vlasnik kamere',
'exif-label'                       => 'Naziv',
'exif-datetimemetadata'            => 'Datum poslednje izmene metapodataka',
'exif-nickname'                    => 'Neformalan naziv slike',
'exif-rating'                      => 'Ocena (od 1 do 5)',
'exif-rightscertificate'           => 'Potvrda za upravljanje pravima',
'exif-copyrighted'                 => 'Status autorskog prava',
'exif-copyrightowner'              => 'Nosilac autorskog prava',
'exif-usageterms'                  => 'Pravila korišćenja',
'exif-webstatement'                => 'Izjava o autorskom pravu',
'exif-originaldocumentid'          => 'Jedinstveni IB izvornog dokumenta',
'exif-licenseurl'                  => 'Adresa licence za autorska prava',
'exif-morepermissionsurl'          => 'Rezervni podaci o licenciranju',
'exif-attributionurl'              => 'Pri ponovnom korišćenju ovog rada, koristite vezu do',
'exif-preferredattributionname'    => 'Pri ponovnom korišćenju ovog rada, postavite zasluge',
'exif-pngfilecomment'              => 'Komentar na datoteku PNG',
'exif-disclaimer'                  => 'Odricanje odgovornosti',
'exif-contentwarning'              => 'Upozorenje o sadržaju',
'exif-giffilecomment'              => 'Komentar na datoteku GIF',
'exif-intellectualgenre'           => 'Vrsta stavke',
'exif-subjectnewscode'             => 'Kod predmeta',
'exif-scenecode'                   => 'IPTC kod scene',
'exif-event'                       => 'Prikazani događaj',
'exif-organisationinimage'         => 'Prikazana organizacija',
'exif-personinimage'               => 'Prikazana osoba',
'exif-originalimageheight'         => 'Visina slike pre isecanja',
'exif-originalimagewidth'          => 'Širina slike pre isecanja',

# Make & model, can be wikified in order to link to the camera and model name
'exif-contact-value'         => '$1

$2
<div class="adr">
$3

$4, $5, $6 $7
</div>
$8',
'exif-subjectnewscode-value' => '$2 ($1)',

# EXIF attributes
'exif-compression-1'     => 'Nesažeto',
'exif-compression-2'     => 'CCITT Group 3 1 – Dimenzionalno izmenjeno Hafmanovo kodiranje po dužini',
'exif-compression-3'     => 'CCITT Group 3 faks kodiranje',
'exif-compression-4'     => 'CCITT Group 4 faks kodiranje',
'exif-compression-5'     => 'LZW',
'exif-compression-6'     => 'JPEG (stari)',
'exif-compression-7'     => 'JPEG',
'exif-compression-8'     => 'Deflate (Adobi)',
'exif-compression-32773' => 'PackBits (Makintoš RLE)',
'exif-compression-32946' => 'Deflate (PKZIP)',
'exif-compression-34712' => 'JPEG2000',

'exif-copyrighted-true'  => 'Zaštićeno autorskim pravom',
'exif-copyrighted-false' => 'Javno vlasništvo',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-unknowndate' => 'Nepoznat datum',

'exif-orientation-1' => 'Normalno',
'exif-orientation-2' => 'Obrnuto po horizontali',
'exif-orientation-3' => 'Zaokrenuto 180°',
'exif-orientation-4' => 'Obrnuto po vertikali',
'exif-orientation-5' => 'Zaokrenuto 90° ulevo i obrnuto po vertikali',
'exif-orientation-6' => 'Zaokrenuto 90° suprotno od smera kazaljke',
'exif-orientation-7' => 'Zaokrenuto 90° udesno i obrnuto po vertikali',
'exif-orientation-8' => 'Zaokrenuto 90° u smeru kazaljke',

'exif-planarconfiguration-1' => 'grubi format',
'exif-planarconfiguration-2' => 'planarni format',

'exif-xyresolution-i' => '$1 tpi',
'exif-xyresolution-c' => '$1 tpc',

'exif-colorspace-1'     => 'sRGB',
'exif-colorspace-65535' => 'Deštelovano',

'exif-componentsconfiguration-0' => 'ne postoji',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'Neodređeno',
'exif-exposureprogram-1' => 'Ručno',
'exif-exposureprogram-2' => 'Normalan program',
'exif-exposureprogram-3' => 'Prioritet otvora blende',
'exif-exposureprogram-4' => 'Prioritet zatvarača',
'exif-exposureprogram-5' => 'Kreativni režim (na osnovi potrebne dubine polja)',
'exif-exposureprogram-6' => 'Sportski režim (na osnovi što bržeg zatvarača)',
'exif-exposureprogram-7' => 'Portretni režim (za krupne kadrove s nejasnom pozadinom)',
'exif-exposureprogram-8' => 'Pejzažni režim (za fotografije pejzaža s oštrom pozadinom)',

'exif-subjectdistance-value' => '$1 metara',

'exif-meteringmode-0'   => 'Nepoznato',
'exif-meteringmode-1'   => 'Prosečno',
'exif-meteringmode-2'   => 'Prosek s težištem na sredini',
'exif-meteringmode-3'   => 'Tačka',
'exif-meteringmode-4'   => 'Više tačaka',
'exif-meteringmode-5'   => 'Matrični',
'exif-meteringmode-6'   => 'Delimični',
'exif-meteringmode-255' => 'Drugo',

'exif-lightsource-0'   => 'Nepoznato',
'exif-lightsource-1'   => 'Dnevna svetlost',
'exif-lightsource-2'   => 'Fluorescentno',
'exif-lightsource-3'   => 'Volframsko',
'exif-lightsource-4'   => 'Blic',
'exif-lightsource-9'   => 'Vedro vreme',
'exif-lightsource-10'  => 'Oblačno vreme',
'exif-lightsource-11'  => 'Senka',
'exif-lightsource-12'  => 'Fluorescentna svetlost (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Fluorescentna svetlost (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Fluorescentna svetlost (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Bela fluorescencija (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Standardno svetlo tipa A',
'exif-lightsource-18'  => 'Standardno svetlo tipa B',
'exif-lightsource-19'  => 'Standardno svetlo tipa C',
'exif-lightsource-20'  => 'D55',
'exif-lightsource-21'  => 'D65',
'exif-lightsource-22'  => 'D75',
'exif-lightsource-23'  => 'D50',
'exif-lightsource-24'  => 'ISO studijska lampa',
'exif-lightsource-255' => 'Drugi svetlosni izvor',

# Flash modes
'exif-flash-fired-0'    => 'Blic nije aktiviran',
'exif-flash-fired-1'    => 'Blic je aktiviran',
'exif-flash-return-0'   => 'bez upotrebe funkcije stroboskopa',
'exif-flash-return-2'   => 'nije prepoznato pozadinsko osvetljenje sa stroboskopa',
'exif-flash-return-3'   => 'prepoznato je pozadinsko osvetljenje sa stroboskopa',
'exif-flash-mode-1'     => 'obavezno sa blicem',
'exif-flash-mode-2'     => 'obavezno bez blica',
'exif-flash-mode-3'     => 'automatski režim',
'exif-flash-function-1' => 'Nema funkcije za blic',
'exif-flash-redeye-1'   => 'režim ispravke crvenih očiju',

'exif-focalplaneresolutionunit-2' => 'inči',

'exif-sensingmethod-1' => 'Neodređeno',
'exif-sensingmethod-2' => 'Jednokristalni matrični senzor',
'exif-sensingmethod-3' => 'Dvokristalni matrični senzor',
'exif-sensingmethod-4' => 'Trokristalni matrični senzor',
'exif-sensingmethod-5' => 'Sekvencijalni matrični senzor',
'exif-sensingmethod-7' => 'Trobojni linearni senzor',
'exif-sensingmethod-8' => 'Sekvencijalni linearni senzor',

'exif-filesource-3' => 'Digitalni fotoaparat',

'exif-scenetype-1' => 'Direktno fotografisana slika',

'exif-customrendered-0' => 'Normalan proces',
'exif-customrendered-1' => 'Nestandardan proces',

'exif-exposuremode-0' => 'Automatska ekspozicija',
'exif-exposuremode-1' => 'Ručna ekspozicija',
'exif-exposuremode-2' => 'Automatski sa zadatim rasponom',

'exif-whitebalance-0' => 'Automatska bela ravnoteža',
'exif-whitebalance-1' => 'Ručna bela ravnoteža',

'exif-scenecapturetype-0' => 'Standardno',
'exif-scenecapturetype-1' => 'Pejzaž',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Noćna scena',

'exif-gaincontrol-0' => 'Nema',
'exif-gaincontrol-1' => 'Malo povećanje',
'exif-gaincontrol-2' => 'Veliko povećanje',
'exif-gaincontrol-3' => 'Malo smanjenje',
'exif-gaincontrol-4' => 'Veliko smanjenje',

'exif-contrast-0' => 'Normalno',
'exif-contrast-1' => 'Meko',
'exif-contrast-2' => 'Tvrdo',

'exif-saturation-0' => 'Normalno',
'exif-saturation-1' => 'Nisko zasićenje',
'exif-saturation-2' => 'Visoko zasićenje',

'exif-sharpness-0' => 'Normalno',
'exif-sharpness-1' => 'Meko',
'exif-sharpness-2' => 'Tvrdo',

'exif-subjectdistancerange-0' => 'Nepoznato',
'exif-subjectdistancerange-1' => 'Krupni kadar',
'exif-subjectdistancerange-2' => 'Bliski kadar',
'exif-subjectdistancerange-3' => 'Daleki kadar',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Severna širina',
'exif-gpslatitude-s' => 'Južna širina',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Istočna dužina',
'exif-gpslongitude-w' => 'Zapadna dužina',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|metar|metra|metara}} nadmorske visine',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|metar|metra|metara}} ispod nivoa mora',

'exif-gpsstatus-a' => 'Merenje u toku',
'exif-gpsstatus-v' => 'Spreman za prenos',

'exif-gpsmeasuremode-2' => 'Dvodimenzionalno merenje',
'exif-gpsmeasuremode-3' => 'Trodimenzionalno merenje',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometri na čas',
'exif-gpsspeed-m' => 'Milje na čas',
'exif-gpsspeed-n' => 'Čvorovi',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilometri',
'exif-gpsdestdistance-m' => 'Milje',
'exif-gpsdestdistance-n' => 'Nautičke milje',

'exif-gpsdop-excellent' => 'Odlično ($1)',
'exif-gpsdop-good'      => 'Dobro ($1)',
'exif-gpsdop-moderate'  => 'Umereno ($1)',
'exif-gpsdop-fair'      => 'Zadovoljavajuće ($1)',
'exif-gpsdop-poor'      => 'Loše ($1)',

'exif-objectcycle-a' => 'Samo ujutru',
'exif-objectcycle-p' => 'Samo uveče',
'exif-objectcycle-b' => 'I ujutru i uveče',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Pravi pravac',
'exif-gpsdirection-m' => 'Magnetni pravac',

'exif-ycbcrpositioning-1' => 'Centrirano',
'exif-ycbcrpositioning-2' => 'Uporedo',

'exif-dc-contributor' => 'Doprinosioci',
'exif-dc-coverage'    => 'Prostorni ili vremenski opseg medija',
'exif-dc-date'        => 'Datum',
'exif-dc-publisher'   => 'Izdavač',
'exif-dc-relation'    => 'Srodni mediji',
'exif-dc-rights'      => 'Prava',
'exif-dc-source'      => 'Izvor medija',
'exif-dc-type'        => 'Vrsta medija',

'exif-rating-rejected' => 'Odbijeno',

'exif-isospeedratings-overflow' => 'Veće od 65535',

'exif-maxaperturevalue-value' => '$1 APEX (f/$2)',

'exif-iimcategory-ace' => 'Umetnost, kultura i zabava',
'exif-iimcategory-clj' => 'Kriminal i zakon',
'exif-iimcategory-dis' => 'Katastrofe i nesreće',
'exif-iimcategory-fin' => 'Ekonomija i posao',
'exif-iimcategory-edu' => 'Obrazovanje',
'exif-iimcategory-evn' => 'Okolina',
'exif-iimcategory-hth' => 'Zdravlje',
'exif-iimcategory-hum' => 'Zanimanje',
'exif-iimcategory-lab' => 'Rad',
'exif-iimcategory-lif' => 'Način života i slobodno vreme',
'exif-iimcategory-pol' => 'Politika',
'exif-iimcategory-rel' => 'Religija i verovanja',
'exif-iimcategory-sci' => 'Nauka i tehnologija',
'exif-iimcategory-soi' => 'Društvena pitanja',
'exif-iimcategory-spo' => 'Sport',
'exif-iimcategory-war' => 'Rat, sukobi i nemiri',
'exif-iimcategory-wea' => 'Vreme',

'exif-urgency-normal' => 'Normalno ($1)',
'exif-urgency-low'    => 'Nisko ($1)',
'exif-urgency-high'   => 'Visoko ($1)',
'exif-urgency-other'  => 'Prilagođeni prioritet ($1)',

# External editor support
'edit-externally'      => 'Izmeni ovu datoteku koristeći spoljašnji program',
'edit-externally-help' => '(Pogledajte [//www.mediawiki.org/wiki/Manual:External_editors uputstvo za podešavanje] za više informacija)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'sve',
'namespacesall' => 'sve',
'monthsall'     => 'sve',
'limitall'      => 'sve',

# E-mail address confirmation
'confirmemail'              => 'Potvrda e-adrese',
'confirmemail_noemail'      => 'Niste uneli ispravnu e-adresu u [[Special:Preferences|podešavanjima]].',
'confirmemail_text'         => '{{SITENAME}} zahteva da potvrdite e-adresu pre nego što počnete da koristite mogućnosti e-pošte.
Kliknite na dugme ispod za slanje poruke na vašu e-adresu.
U poruci će se nalaziti veza s potvrdnim kodom;
unesite je u pregledač da biste potvrdili da je vaša e-adresa ispravna.',
'confirmemail_pending'      => 'Potvrdni kod vam je već poslat. Ako ste upravo otvorili nalog, onda verovatno treba da sačekate nekoliko minuta da pristigne, pre nego što ponovo zatražite novi kod.',
'confirmemail_send'         => 'Pošalji potvrdni kod',
'confirmemail_sent'         => 'Potvrdna poruka je poslata.',
'confirmemail_oncreate'     => 'Poslat je potvrdni kod na vašu e-adresu.
Ovaj kod nije potreban za prijavljivanje, ali vam treba da biste uključili mogućnosti e-pošte na vikiju.',
'confirmemail_sendfailed'   => '{{SITENAME}} ne može da pošalje poruku.
Proverite da li je e-adresa pravilno napisana.

Greška: $1',
'confirmemail_invalid'      => 'Potvrdni kod je neispravan. Verovatno je istekao.',
'confirmemail_needlogin'    => 'Morate biti $1 da biste potvrdili e-adresu.',
'confirmemail_success'      => 'Vaša e-adresa je potvrđena.
Sada možete da se [[Special:UserLogin|prijavite]]. Želimo vam prijatan boravak na vikiju.',
'confirmemail_loggedin'     => 'Vaša e-adresa je sada potvrđena.',
'confirmemail_error'        => 'Nešto je krenulo naopako pri čuvanju vaše potvrde.',
'confirmemail_subject'      => '{{SITENAME}} – potvrda e-adrese',
'confirmemail_body'         => 'Neko, verovatno vi, sa IP adrese $1 je otvorio nalog „$2“ na vikiju {{SITENAME}}, navodeći ovu e-adresu.

Da potvrdite da ovaj nalog stvarno pripada vama, kao i da
omogućite mogućnosti e-pošte, otvorite ovu vezu u pregledaču:

$3

Ukoliko niste otvorili nalog, pratite vezu
ispod kako biste prekinuli postupak upisa:

$5

Ovaj potvrdni kod ističe $6 u $5.',
'confirmemail_body_changed' => 'Neko, verovatno vi, sa IP adrese $1 je promenio e-adresu naloga „$2“ u ovu adresu na vikiju {{SITENAME}}.

Da biste potvrdili da ovaj nalog stvarno pripada vama i ponovo aktivirali mogućnosti e-pošte, otvorite sledeću vezu u pregledaču:

$3

Ako nalog *ne* pripada vama, pratite sledeću vezu da otkažete potvrdu e-adrese:

$5

Ovaj potvrdni kod ističe $6 u $7.',
'confirmemail_body_set'     => 'Neko, verovatno vi, sa IP adrese $1 je promenio e-adresu naloga „$2“ u ovu adresu na vikiju {{SITENAME}}.

Da biste potvrdili da ovaj nalog stvarno pripada vama i ponovo aktivirali mogućnosti e-pošte, otvorite sledeću vezu u pregledaču:

$3

Ako nalog *ne* pripada vama, pratite sledeću vezu da otkažete potvrdu e-adrese:

$5

Ovaj potvrdni kod ističe $6 u $7.',
'confirmemail_invalidated'  => 'Potvrda e-pošte je otkazana',
'invalidateemail'           => 'Otkazivanje potvrde e-pošte',

# Scary transclusion
'scarytranscludedisabled' => '[Međuviki uključivanje šablona je onemogućeno]',
'scarytranscludefailed'   => '[Dobavljanje šablona za $1 nije uspelo]',
'scarytranscludetoolong'  => '[URL adresa je predugačka]',

# Delete conflict
'deletedwhileediting'      => "'''Upozorenje''': ova stranica je obrisana nakon što ste počeli s uređivanjem!",
'confirmrecreate'          => "[[User:$1|$1]] ([[User talk:$1|razgovor]]) {{GENDER:$1|je obrisao|je obrisala|je obrisao}} ovu stranicu nakon što ste počeli da je uređujete iz sledećeg razloga:
: ''$2''
Potvrdite da stvarno želite da napravite stranicu.",
'confirmrecreate-noreason' => 'Korisnik [[User:$1|$1]] ([[User talk:$1|razgovor]]) je obrisao ovu stranicu nakon što ste počeli da ga uređujete. Potvrdite da stvarno želite da ponovo napravite ovu stranicu.',
'recreate'                 => 'Ponovo napravi',

'unit-pixel' => 'px',

# action=purge
'confirm_purge_button' => 'U redu',
'confirm-purge-top'    => 'Očistiti privremenu memoriju ove strane?',
'confirm-purge-bottom' => 'Ova radnja čisti privremenu memoriju i prikazuje najnoviju izmenu.',

# action=watch/unwatch
'confirm-watch-button'   => 'U redu',
'confirm-watch-top'      => 'Dodati ovu stranicu u spisak nadgledanja?',
'confirm-unwatch-button' => 'U redu',
'confirm-unwatch-top'    => 'Ukloniti ovu stranicu sa spiska nadgledanja?',

# Separators for various lists, etc.
'semicolon-separator' => ';&#32;',
'comma-separator'     => ',&#32;',
'colon-separator'     => ':&#32;',
'autocomment-prefix'  => '-&#32;',
'pipe-separator'      => '&#32;•&#32;',
'word-separator'      => '&#32;',
'ellipsis'            => '…',
'percent'             => '$1%',
'parentheses'         => '($1)',

# Multipage image navigation
'imgmultipageprev' => '← prethodna stranica',
'imgmultipagenext' => 'sledeća stranica →',
'imgmultigo'       => 'Idi!',
'imgmultigoto'     => 'Idi na stranicu $1',

# Table pager
'ascending_abbrev'         => 'rast.',
'descending_abbrev'        => 'opad.',
'table_pager_next'         => 'Sledeća stranica',
'table_pager_prev'         => 'Prethodna stranica',
'table_pager_first'        => 'Prva stranica',
'table_pager_last'         => 'Poslednja stranica',
'table_pager_limit'        => 'Prikaži $1 stavki po stranici',
'table_pager_limit_label'  => 'Stavki po stranici:',
'table_pager_limit_submit' => 'Idi',
'table_pager_empty'        => 'Nema rezultata',

# Auto-summaries
'autosumm-blank'   => 'Potpuno obrisana stranica',
'autosumm-replace' => 'Zamena sadržaja sa „$1“',
'autoredircomment' => 'Preusmerenje na [[$1]]',
'autosumm-new'     => 'Napravljena stranica sa: „$1“',

# Size units
'size-bytes'     => '$1 B',
'size-kilobytes' => '$1 kB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Live preview
'livepreview-loading' => 'Učitavam…',
'livepreview-ready'   => 'Učitavanje… spremno!',
'livepreview-failed'  => 'Neuspešno pregledanje.
Probajte običan pregled.',
'livepreview-error'   => 'Ne mogu da se povežem: $1 „$2“.
Probajte običan prikaz.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Izmene novije od $1 {{PLURAL:$1|sekunde|sekunde|sekundi}} neće biti prikazane.',
'lag-warn-high'   => 'Zbog preopterećenja baze podataka, izmene novije od $1 {{PLURAL:$1|sekunde|sekunde|sekundi}} neće biti prikazane.',

# Watchlist editor
'watchlistedit-numitems'       => 'Vaš spisak nadgledanja sadrži {{PLURAL:$1|jedan naslov|$1 naslova|$1 naslova}}, bez stranica za razgovor.',
'watchlistedit-noitems'        => 'Vaš spisak nadgledanja ne sadrži stranice.',
'watchlistedit-normal-title'   => 'Uređivanje spiska nadgledanja',
'watchlistedit-normal-legend'  => 'Uklanjanje naslova sa spiska nadgledanja',
'watchlistedit-normal-explain' => 'Naslovi na vašem spisku nadgledanja su prikazani ispod.
Da biste uklonili naslov, označite kućicu do njega i kliknite na „{{int:Watchlistedit-normal-submit}}“.
Možete i da [[Special:EditWatchlist/raw|uredite sirov spisak]].',
'watchlistedit-normal-submit'  => 'Ukloni',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Jedna stranica je uklonjena|$1 stranice su uklonjene|$1 stranica je uklonjeno}} s vašeg spiska nadgledanja:',
'watchlistedit-raw-title'      => 'Napredno uređivanje spiska nadgledanja',
'watchlistedit-raw-legend'     => 'Napredno uređivanje spiska nadgledanja',
'watchlistedit-raw-explain'    => 'Naslovi sa spiska nadgledanja su prikazani ispod i mogu se uređivati dodavanjem ili uklanjanjem stavki sa spiska;
jedan naslov po redu.
Kada završite, kliknite na „{{int:Watchlistedit-raw-submit}}“.
Možete da [[Special:EditWatchlist|koristite i običan uređivač]].',
'watchlistedit-raw-titles'     => 'Naslovi:',
'watchlistedit-raw-submit'     => 'Ažuriraj spisak',
'watchlistedit-raw-done'       => 'Vaš spisak nadgledanja je ažuriran.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Dodat je jedan naslov|Dodata su $1 naslova|Dodato je $1 naslova}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Uklonjen je jedan naslov|Uklonjena su $1 naslova|Uklonjeno je $1 naslova}}:',

# Watchlist editing tools
'watchlisttools-view' => 'prikaži srodne izmene',
'watchlisttools-edit' => 'prikaži i uredi spisak nadgledanja',
'watchlisttools-raw'  => 'izmeni sirov spisak nadgledanja',

# Iranian month names
'iranian-calendar-m1'  => 'Farvardin',
'iranian-calendar-m2'  => 'Ordibehešt',
'iranian-calendar-m3'  => 'Hordad',
'iranian-calendar-m4'  => 'Tir',
'iranian-calendar-m5'  => 'Mordad',
'iranian-calendar-m6'  => 'Šahrivar',
'iranian-calendar-m7'  => 'Mehr',
'iranian-calendar-m8'  => 'Aban',
'iranian-calendar-m9'  => 'Azar',
'iranian-calendar-m10' => 'Dej',
'iranian-calendar-m11' => 'Bahman',
'iranian-calendar-m12' => 'Esfand',

# Hijri month names
'hijri-calendar-m1'  => 'Muharam',
'hijri-calendar-m2'  => 'Safar',
'hijri-calendar-m3'  => 'Rabija I',
'hijri-calendar-m4'  => 'Rabija II',
'hijri-calendar-m5'  => 'Jumada I',
'hijri-calendar-m6'  => 'Jumada II',
'hijri-calendar-m7'  => 'Radžab',
'hijri-calendar-m8'  => 'Šaban',
'hijri-calendar-m9'  => 'Ramazan',
'hijri-calendar-m10' => 'Šaval',
'hijri-calendar-m11' => 'Dulkada',
'hijri-calendar-m12' => 'Dulhidža',

# Hebrew month names
'hebrew-calendar-m1'      => 'Tišri',
'hebrew-calendar-m2'      => 'Hešvan',
'hebrew-calendar-m3'      => 'Kislev',
'hebrew-calendar-m4'      => 'Tevet',
'hebrew-calendar-m5'      => 'Ševat',
'hebrew-calendar-m6'      => 'Adar',
'hebrew-calendar-m6a'     => 'Adar I',
'hebrew-calendar-m6b'     => 'Adar II',
'hebrew-calendar-m7'      => 'Nisan',
'hebrew-calendar-m8'      => 'Ijar',
'hebrew-calendar-m9'      => 'Sivan',
'hebrew-calendar-m10'     => 'Tamuz',
'hebrew-calendar-m11'     => 'Av',
'hebrew-calendar-m12'     => 'Elul',
'hebrew-calendar-m1-gen'  => 'Tišri',
'hebrew-calendar-m2-gen'  => 'Hešvan',
'hebrew-calendar-m3-gen'  => 'Kislev',
'hebrew-calendar-m4-gen'  => 'Tevet',
'hebrew-calendar-m5-gen'  => 'Ševat',
'hebrew-calendar-m6-gen'  => 'Adar',
'hebrew-calendar-m6a-gen' => 'Adar I',
'hebrew-calendar-m6b-gen' => 'Adar II',
'hebrew-calendar-m7-gen'  => 'Nisan',
'hebrew-calendar-m8-gen'  => 'Ijar',
'hebrew-calendar-m9-gen'  => 'Sivan',
'hebrew-calendar-m10-gen' => 'Tamuz',
'hebrew-calendar-m11-gen' => 'Av',
'hebrew-calendar-m12-gen' => 'Elul',

# Signatures
'signature'    => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|razgovor]])',
'timezone-utc' => 'UTC',

# Core parser functions
'unknown_extension_tag' => 'Nepoznata oznaka proširenja „$1“',
'duplicate-defaultsort' => "'''Upozorenje:''' podrazumevani ključ svrstavanja „$2“ menja nekadašnji ključ „$1“.",

# Special:Version
'version'                       => 'Verzija',
'version-extensions'            => 'Instalirana proširenja',
'version-specialpages'          => 'Posebne stranice',
'version-parserhooks'           => 'Kuke raščlanjivača',
'version-variables'             => 'Promenljive',
'version-antispam'              => 'Sprečavanje nepoželjnih poruka',
'version-skins'                 => 'Teme',
'version-api'                   => 'API',
'version-other'                 => 'Drugo',
'version-mediahandlers'         => 'Rukovodioci medijima',
'version-hooks'                 => 'Kuke',
'version-extension-functions'   => 'Funkcije',
'version-parser-extensiontags'  => 'Oznake',
'version-parser-function-hooks' => 'Kuke',
'version-hook-name'             => 'Naziv kuke',
'version-hook-subscribedby'     => 'Prijavljeno od',
'version-version'               => '(izdanje $1)',
'version-svn-revision'          => '(izm. $2)',
'version-license'               => 'Licenca',
'version-poweredby-credits'     => "Ovaj viki pokreće '''[//www.mediawiki.org/ Medijaviki]''', autorska prava © 2001-$1 $2.",
'version-poweredby-others'      => 'ostali',
'version-license-info'          => 'Medijaviki je slobodan softver; možete ga raspodeljivati i menjati pod uslovima GNU-ove opšte javne licence (OJL) koju je objavila Zadužbina za slobodan softver, bilo da je u pitanju drugo ili novije izdanje licence.

Medijaviki se nudi u nadi da će biti od koristi, ali BEZ IKAKVE GARANCIJE; čak i bez podrazumevane garancije o PRODAJNOJ VREDNOSTI ili POGODNOSTI ZA ODREĐENE NAMENE. Pogledajte GNU-ovu opštu javnu licencu za više informacija.

Trebalo bi da ste primili [{{SERVER}}{{SCRIPTPATH}}/COPYING primerak GNU-ove opšte javne licence] zajedno s ovim programom. Ako niste, pišite na Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA ili [//www.gnu.org/licenses/old-licenses/gpl-2.0.html pročitajte ovde].',
'version-software'              => 'Instalirani softver',
'version-software-product'      => 'Proizvod',
'version-software-version'      => 'Verzija',

# Special:FilePath
'filepath'         => 'Putanja datoteke',
'filepath-page'    => 'Datoteka:',
'filepath-submit'  => 'Idi',
'filepath-summary' => 'Ova posebna stranica prikazuje potpunu putanju datoteke.
Slike su prikazane u punoj veličini, a druge vrste datoteka se pokreću pomoću njima pridruženim programima.',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'Pretraga duplikata',
'fileduplicatesearch-summary'   => 'Pretraga dupliranih datoteka prema vrednostima disperzije.',
'fileduplicatesearch-legend'    => 'Pretraga duplikata',
'fileduplicatesearch-filename'  => 'Naziv datoteke:',
'fileduplicatesearch-submit'    => 'Pretraži',
'fileduplicatesearch-info'      => '$1 × $2 piksela<br />Veličina: $3<br />MIME vrsta: $4',
'fileduplicatesearch-result-1'  => 'Datoteka „$1“ nema istovetnih duplikata.',
'fileduplicatesearch-result-n'  => 'Datoteka „$1“ ima {{PLURAL:$2|identičan duplikat|$2 identična duplikata|$2 identičnih duplikata}}.',
'fileduplicatesearch-noresults' => 'Datoteka pod nazivom „$1“ nije pronađena.',

# Special:SpecialPages
'specialpages'                   => 'Posebne stranice',
'specialpages-note'              => '----
* obične posebne stranice
* <span class="mw-specialpagerestricted">ograničene posebne stranice</span>
* <span class="mw-specialpagecached">privremeno memorisane posebne stranice</span>',
'specialpages-group-maintenance' => 'Izveštaji održavanja',
'specialpages-group-other'       => 'Ostale posebne stranice',
'specialpages-group-login'       => 'Prijava/registracija',
'specialpages-group-changes'     => 'Skorašnje izmene i dnevnici',
'specialpages-group-media'       => 'Izveštaji o multimedijalnom sadržaju i otpremanja',
'specialpages-group-users'       => 'Korisnici i korisnička prava',
'specialpages-group-highuse'     => 'Najčešće korišćene stranice',
'specialpages-group-pages'       => 'Spiskovi stranica',
'specialpages-group-pagetools'   => 'Alatke',
'specialpages-group-wiki'        => 'Podaci i alati enciklopedije',
'specialpages-group-redirects'   => 'Preusmeravanje posebnih stranica',
'specialpages-group-spam'        => 'Alatke protiv nepoželjnih poruka',

# Special:BlankPage
'blankpage'              => 'Prazna stranica',
'intentionallyblankpage' => 'Ova stranica je namerno ostavljena praznom.',

# External image whitelist
'external_image_whitelist' => ' #Ostavite ovaj red onakvim kakav jeste<pre>
#Ispod dodajte odlomke regularnih izraza (samo deo koji se nalazi između //)
#Oni će biti upoređeni s adresama spoljašnjih slika
#One koje se poklapaju biće prikazane kao slike, a preostale kao veze do slika
#Redovi koji počinju s tarabom se smatraju komentarima
#Svi unosi su osetljivi na mala i velika slova

#Dodajte sve odlomke regularnih izraza iznad ovog reda. Ovaj red ne dirajte</pre>',

# Special:Tags
'tags'                    => 'Važeće oznake izmena',
'tag-filter'              => 'Filter za [[Special:Tags|oznake]]:',
'tag-filter-submit'       => 'Filtriraj',
'tags-title'              => 'Oznake',
'tags-intro'              => 'Na ovoj stranici je naveden spisak oznaka s kojima program može da označi izmene i njegovo značenje.',
'tags-tag'                => 'Naziv oznake',
'tags-display-header'     => 'Izgled na spiskovima izmena',
'tags-description-header' => 'Opis značenja',
'tags-hitcount-header'    => 'Označene izmene',
'tags-edit'               => 'uredi',
'tags-hitcount'           => '$1 {{PLURAL:$1|izmena|izmene|izmena}}',

# Special:ComparePages
'comparepages'                => 'Upoređivanje stranica',
'compare-selector'            => 'Upoređivanje izmena stranice',
'compare-page1'               => 'Stranica 1',
'compare-page2'               => 'Stranica 2',
'compare-rev1'                => 'Izmena 1',
'compare-rev2'                => 'Izmena 2',
'compare-submit'              => 'Uporedi',
'compare-invalid-title'       => 'Navedeni naslov je neispravan.',
'compare-title-not-exists'    => 'Navedeni naslov ne postoji.',
'compare-revision-not-exists' => 'Navedena izmena ne postoji.',

# Database error messages
'dberr-header'      => 'Ovaj viki ne radi kako treba',
'dberr-problems'    => 'Došlo je do tehničkih problema.',
'dberr-again'       => 'Sačekajte nekoliko minuta i ponovo učitajte stranicu.',
'dberr-info'        => '(ne mogu da se povežem sa serverom baze: $1)',
'dberr-usegoogle'   => 'U međuvremenu, pokušajte da pretražite pomoću Gugla.',
'dberr-outofdate'   => 'Imajte na umu da njihovi primerci našeg sadržaja mogu biti zastareli.',
'dberr-cachederror' => 'Ovo je privremeno memorisan primerak strane koji možda nije ažuran.',

# HTML forms
'htmlform-invalid-input'       => 'Pronađeni su problemi u vašem unosu',
'htmlform-select-badoption'    => 'Navedena vrednost nije ispravna opcija.',
'htmlform-int-invalid'         => 'Navedena vrednost nije celi broj.',
'htmlform-float-invalid'       => 'Navedena vrednost nije broj.',
'htmlform-int-toolow'          => 'Navedena vrednost je ispod minimuma od $1',
'htmlform-int-toohigh'         => 'Navedena vrednost je iznad maksimuma od $1',
'htmlform-required'            => 'Ova vrednost je obavezna',
'htmlform-submit'              => 'Pošalji',
'htmlform-reset'               => 'Vrati izmene',
'htmlform-selectorother-other' => 'Drugo',

# SQLite database support
'sqlite-has-fts' => '$1 s podrškom pretrage celog teksta',
'sqlite-no-fts'  => '$1 bez podrške pretrage celog teksta',

# New logging system
'logentry-delete-delete'              => '$1 {{GENDER:|je obrisao|je obrisala|je obrisao}} $3',
'logentry-delete-restore'             => '$1 {{GENDER:|je vratio|je vratila|je vratio}} stranicu $3',
'logentry-delete-event'               => '$1 {{GENDER:$2|je promenio|je promenila|je promenio}} vidljivost {{PLURAL:$5|događaja|$5 događaja|$5 događaja}} u dnevniku na $3: $4',
'logentry-delete-revision'            => '$1 {{GENDER:|je promenio|je promenila|je promenio}} vidljivost {{PLURAL:$5|izmene|$5 izmene|$5 izmena}} na stranici $3: $4',
'logentry-delete-event-legacy'        => '$1 {{GENDER:|je promenio|je promenila|je promenio}} vidljivost događajâ u dnevniku na $3',
'logentry-delete-revision-legacy'     => '$1 {{GENDER:|je promenio|je promenila|je promenio}} vidljivost izmenâ na stranici $3',
'logentry-suppress-delete'            => '$1 {{GENDER:|je potisnuo|je potisnula|je potisnuo}} stranicu $3',
'logentry-suppress-event'             => '$1 je potajno {{GENDER:|promenio|promenila|promenio}} vidljivost {{PLURAL:$5|događaja|$5 događaja|$5 događaja}} u dnevniku na $3: $4',
'logentry-suppress-revision'          => '$1 je potajno {{GENDER:|promenio|promenila|promenio}} vidljivost {{PLURAL:$5|izmene|$5 izmene|$5 izmena}} na stranici $3: $4',
'logentry-suppress-event-legacy'      => '$1 je potajno {{GENDER:|promenio|promenila|promenio}} vidljivost događajâ u dnevniku na $3',
'logentry-suppress-revision-legacy'   => '$1 je potajno {{GENDER:|promenio|promenila|promenio}} vidljivost izmenâ na stranici $3',
'revdelete-content-hid'               => 'sadržaj je sakriven',
'revdelete-summary-hid'               => 'opis izmene je sakriven',
'revdelete-uname-hid'                 => 'korisničko ime je sakriveno',
'revdelete-content-unhid'             => 'sadržaj je otkriven',
'revdelete-summary-unhid'             => 'opis izmene je otkriven',
'revdelete-uname-unhid'               => 'korisničko ime je otkriveno',
'revdelete-restricted'                => 'primenjena ograničenja za administratore',
'revdelete-unrestricted'              => 'uklonjena ograničenja za administratore',
'logentry-move-move'                  => '$1 {{GENDER:|je premestio|je premestila|je premestio}} stranicu $3 na $4',
'logentry-move-move-noredirect'       => '$1 {{GENDER:|je premestio|je premestila|je premestio}} stranicu $3 na $4 bez ostavljanja preusmerenja',
'logentry-move-move_redir'            => '$1 {{GENDER:|je premestio|je premestila|je premestio}} stranicu $3 na $4 preko preusmerenja',
'logentry-move-move_redir-noredirect' => '$1 {{GENDER:|je premestio|je premestila|je premestio}} stranicu $3 na $4 preko preusmerenja bez ostavljanja preusmerenja',
'logentry-patrol-patrol'              => '$1 {{GENDER:|je označio|je označila|je označio}} izmenu $4 stranice $3 kao patroliranu',
'logentry-patrol-patrol-auto'         => '$1 je samostalno {{GENDER:|označio|označila|označio}} izmenu $4 stranice $3 kao pregledanu',
'logentry-newusers-newusers'          => '$1 {{GENDER:|je otvorio|je otvorila|je otvorio}} korisnički nalog',
'logentry-newusers-create'            => '$1 {{GENDER:|je otvorio|je otvorila|je otvorio}} korisnički nalog',
'logentry-newusers-create2'           => '$1 {{GENDER:|je otvorio|je otvorila|je otvorio}} korisnički nalog $3',
'logentry-newusers-autocreate'        => 'Nalog $1 je samostalno otvoren',
'newuserlog-byemail'                  => 'lozinka je poslata e-poštom',

# Feedback
'feedback-bugornote' => 'Ako ste spremni da detaljno opišete tehnički problem, onda [$1 prijavite grešku].
U suprotnom, poslužite se jednostavnim obrascem ispod. Vaš komentar će stajati na stranici „[$3 $2]“, zajedno s korisničkim imenom i pregledačem koji koristite.',
'feedback-subject'   => 'Naslov:',
'feedback-message'   => 'Poruka:',
'feedback-cancel'    => 'Otkaži',
'feedback-submit'    => 'Pošalji povratnu informaciju',
'feedback-adding'    => 'Dodajem povratnu informaciju na stranicu…',
'feedback-error1'    => 'Greška: neprepoznat rezultat od API-ja',
'feedback-error2'    => 'Greška: uređivanje nije uspelo',
'feedback-error3'    => 'Greška: nema odgovora od API-ja',
'feedback-thanks'    => 'Hvala! Vaša povratna informacija je postavljena na stranicu „[$2 $1]“.',
'feedback-close'     => 'Urađeno',
'feedback-bugcheck'  => 'Odlično! Proverite da li je greška [$1 poznata od pre].',
'feedback-bugnew'    => 'Provereno. Prijavi novu grešku',

# API errors
'api-error-badaccess-groups'              => 'Nije vam dozvoljeno da otpremate datoteke na ovaj viki.',
'api-error-badtoken'                      => 'Unutrašnja greška: neispravan žeton.',
'api-error-copyuploaddisabled'            => 'Otpremanje putem adrese je onemogućeno na ovom serveru.',
'api-error-duplicate'                     => 'Već {{PLURAL:$1|postoji [$2 druga datoteka]|postoje [$2 druge datoteke]}} s istim sadržajem.',
'api-error-duplicate-archive'             => '{{PLURAL:$1|Postojala je [$2 druga datoteka]|Postojale su [$2 druge datoteke]}} s istim sadržajem, ali {{PLURAL:$1|je obrisana|su obrisane}}.',
'api-error-duplicate-archive-popup-title' => '{{PLURAL:$1|Duplirana datoteka koja je već obrisana|Duplirane datoteke koje su već obrisane}}',
'api-error-duplicate-popup-title'         => '{{PLURAL:$1|Duplirana datoteka|Duplirane datoteke}}',
'api-error-empty-file'                    => 'Poslata datoteka je prazna.',
'api-error-emptypage'                     => 'Stvaranje novih praznih stranica nije dozvoljeno.',
'api-error-fetchfileerror'                => 'Unutrašnja greška: došlo je do greške pri dobavljanju datoteke.',
'api-error-file-too-large'                => 'Poslata datoteka je prevelika.',
'api-error-filename-tooshort'             => 'Naziv datoteke je prekratak.',
'api-error-filetype-banned'               => 'Ova vrsta datoteke je zabranjena.',
'api-error-filetype-missing'              => 'Datoteci nedostaje nastavak.',
'api-error-hookaborted'                   => 'Izmena je odbačena od kuke za proširenja.',
'api-error-http'                          => 'Unutrašnja greška: ne mogu da se povežem sa serverom.',
'api-error-illegal-filename'              => 'Naziv datoteke je zabranjen.',
'api-error-internal-error'                => 'Unutrašnja greška: došlo je do greške pri obrađivanju datoteke na vikiju.',
'api-error-invalid-file-key'              => 'Unutrašnja greška: ne mogu da pronađem datoteku u privremenom skladištu.',
'api-error-missingparam'                  => 'Unutrašnja greška: nedostaju parametri u zahtevu.',
'api-error-missingresult'                 => 'Unutrašnja greška: ne mogu da utvrdim da li je umnožavanje uspelo.',
'api-error-mustbeloggedin'                => 'Morate biti prijavljeni da biste otpremali datoteke.',
'api-error-mustbeposted'                  => 'Unutrašnja greška: ne koristi se ispravan HTTP metod.',
'api-error-noimageinfo'                   => 'Otpremanje je uspelo, ali server nije dao nikakav podatak o datoteci.',
'api-error-nomodule'                      => 'Unutrašnja greška: nije postavljen modul za otpremanje.',
'api-error-ok-but-empty'                  => 'Unutrašnja greška: server ne odgovara.',
'api-error-overwrite'                     => 'Zamenjivanje postojeće datoteke je zabranjeno.',
'api-error-stashfailed'                   => 'Unutrašnja greška: server ne može da sačuva privremenu datoteku.',
'api-error-timeout'                       => 'Server nije odgovorio u očekivano vreme.',
'api-error-unclassified'                  => 'Došlo je do nepoznate greške',
'api-error-unknown-code'                  => 'Nepoznata greška: „$1“',
'api-error-unknown-error'                 => 'Unutrašnja greška: došlo je do greške pri otpremanju datoteke.',
'api-error-unknown-warning'               => 'Nepoznato upozorenje: $1',
'api-error-unknownerror'                  => 'Nepoznata greška: „$1“.',
'api-error-uploaddisabled'                => 'Otpremanje je onemogućeno na ovom vikiju.',
'api-error-verification-error'            => 'Datoteka je oštećena ili ima neispravan nastavak.',

);
