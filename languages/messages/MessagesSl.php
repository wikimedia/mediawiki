<?php
/** Slovenian (slovenščina)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Dbc334
 * @author Eleassar
 * @author Freakolowsky
 * @author Irena Plahuta
 * @author Matej1234
 * @author McDutchie
 * @author Nemo bis
 * @author Smihael
 * @author Vadgt
 * @author XJamRastafire
 * @author Yerpo
 * @author romanm
 * @author sl.wikipedia.org administrators
 */

$namespaceNames = [
	NS_MEDIA            => 'Datoteka',
	NS_SPECIAL          => 'Posebno',
	NS_TALK             => 'Pogovor',
	NS_USER             => 'Uporabnik',
	NS_USER_TALK        => 'Uporabniški_pogovor',
	NS_PROJECT_TALK     => 'Pogovor_{{grammar:mestnik|$1}}',
	NS_FILE             => 'Slika',
	NS_FILE_TALK        => 'Pogovor_o_sliki',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Pogovor_o_MediaWiki',
	NS_TEMPLATE         => 'Predloga',
	NS_TEMPLATE_TALK    => 'Pogovor_o_predlogi',
	NS_HELP             => 'Pomoč',
	NS_HELP_TALK        => 'Pogovor_o_pomoči',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Pogovor_o_kategoriji',
];

$specialPageAliases = [
	'Activeusers'               => [ 'AktivniUporabniki' ],
	'Allmessages'               => [ 'VsaSporočila' ],
	'Allpages'                  => [ 'VseStrani' ],
	'Ancientpages'              => [ 'StarodavneStrani' ],
	'Blankpage'                 => [ 'PraznaStran' ],
	'Block'                     => [ 'Blokiraj', 'BlokirajIP', 'BlokirajUporabnika' ],
	'Booksources'               => [ 'ViriKnjig' ],
	'BrokenRedirects'           => [ 'PretrganePreusmeritve' ],
	'Categories'                => [ 'Kategorije' ],
	'ChangePassword'            => [ 'SpremeniGeslo', 'PonastaviGeslo' ],
	'Contributions'             => [ 'Prispevki' ],
	'CreateAccount'             => [ 'Registracija' ],
	'DeletedContributions'      => [ 'IzbrisaniPrispevki' ],
	'DoubleRedirects'           => [ 'DvojnePreusmeritve' ],
	'Export'                    => [ 'Izvozi' ],
	'Fewestrevisions'           => [ 'NajmanjRedakcij' ],
	'Filepath'                  => [ 'PotDatoteke' ],
	'Import'                    => [ 'Uvoz' ],
	'Listadmins'                => [ 'SeznamAdministratorjev' ],
	'Listbots'                  => [ 'SeznamBotov' ],
	'Listfiles'                 => [ 'SeznamDatotek', 'SeznamSlik' ],
	'Listgrouprights'           => [ 'SeznamPravicSkupin' ],
	'Listusers'                 => [ 'SeznamUporabnikov' ],
	'Log'                       => [ 'Dnevnik', 'Dnevniki' ],
	'Lonelypages'               => [ 'OsiroteleStrani' ],
	'Longpages'                 => [ 'DolgeStrani' ],
	'MergeHistory'              => [ 'ZdružiZgodovino' ],
	'MIMEsearch'                => [ 'IskanjeMIME' ],
	'Mostcategories'            => [ 'NajvečKategorij' ],
	'Mostimages'                => [ 'NajboljPovezaneDatoteke' ],
	'Mostlinked'                => [ 'NajboljPovezaneStrani' ],
	'Mostlinkedcategories'      => [ 'NajboljPovezaneKategorije' ],
	'Mostlinkedtemplates'       => [ 'NajboljPovezanePredloge' ],
	'Mostrevisions'             => [ 'NajvečRedakcij' ],
	'Movepage'                  => [ 'PrestaviStran', 'PremakniStran' ],
	'Mycontributions'           => [ 'MojiPrispevki' ],
	'Mypage'                    => [ 'MojaStran' ],
	'Mytalk'                    => [ 'MojPogovor' ],
	'Newimages'                 => [ 'NoveDatoteke', 'NoveSlike' ],
	'Newpages'                  => [ 'NoveStrani' ],
	'Preferences'               => [ 'Nastavitve' ],
	'Protectedpages'            => [ 'ZaščiteneStrani' ],
	'Protectedtitles'           => [ 'ZaščiteniNaslovi' ],
	'Randompage'                => [ 'Naključno', 'NaključnaStran' ],
	'Recentchanges'             => [ 'ZadnjeSpremembe' ],
	'Search'                    => [ 'Iskanje' ],
	'Shortpages'                => [ 'KratkeStrani' ],
	'Specialpages'              => [ 'PosebneStrani' ],
	'Statistics'                => [ 'Statistika' ],
	'Unblock'                   => [ 'Odblokiraj' ],
	'Uncategorizedcategories'   => [ 'NekategoriziraneKategorije' ],
	'Uncategorizedimages'       => [ 'NekategoriziraneDatoteke', 'NekategoriziraneSlike' ],
	'Uncategorizedpages'        => [ 'NekategoriziraneStrani' ],
	'Uncategorizedtemplates'    => [ 'NekategoriziranePredloge' ],
	'Undelete'                  => [ 'Obnovi' ],
	'Unusedcategories'          => [ 'NeuporabljeneKategorije' ],
	'Unusedimages'              => [ 'NeuporabljeneDatoteke', 'NeuporabljeneSlike' ],
	'Unwatchedpages'            => [ 'NespremljaneStrani' ],
	'Upload'                    => [ 'Nalaganje' ],
	'Userlogin'                 => [ 'Prijava' ],
	'Userlogout'                => [ 'Odjava' ],
	'Version'                   => [ 'Različica', 'Verzija' ],
	'Wantedcategories'          => [ 'ŽeleneKategorije' ],
	'Wantedfiles'               => [ 'ŽeleneDatoteke' ],
	'Wantedpages'               => [ 'ŽeleneStrani' ],
	'Wantedtemplates'           => [ 'ŽelenePredloge' ],
	'Watchlist'                 => [ 'SpisekNadzorov' ],
	'Whatlinkshere'             => [ 'KajSePovezujeSem' ],
	'Withoutinterwiki'          => [ 'BrezInterwikijev' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#PREUSMERITEV', '#REDIRECT' ],
	'notoc'                     => [ '0', '__BREZKAZALAVSEBINE__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__BREZGALERIJE__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__VSILIKAZALOVSEBINE__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__POGLAVJE__', '__TOC__' ],
	'noeditsection'             => [ '0', '__BREZUREJANJARAZDELKOV__', '__NOEDITSECTION__' ],
	'img_thumbnail'             => [ '1', 'sličica', 'thumb', 'thumbnail' ],
	'img_manualthumb'           => [ '1', 'sličica=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'desno', 'right' ],
	'img_left'                  => [ '1', 'levo', 'left' ],
	'img_none'                  => [ '1', 'brez', 'none' ],
	'img_width'                 => [ '1', '$1_pik', '$1px' ],
	'img_center'                => [ '1', 'sredina', 'sredinsko', 'center', 'centre' ],
	'img_framed'                => [ '1', 'okvir', 'okvirjeno', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'brezokvirja', 'frameless' ],
	'img_page'                  => [ '1', 'stran=$1', 'm_stran $1', 'page=$1', 'page $1' ],
	'img_upright'               => [ '1', 'zgorajdesno', 'zgorajdesno=$1', 'zgorajdesno $1', 'upright', 'upright=$1', 'upright $1' ],
	'img_border'                => [ '1', 'obroba', 'border' ],
	'img_sub'                   => [ '1', 'pod', 'podpisano', 'sub' ],
	'img_super'                 => [ '1', 'nad', 'nadpisano', 'super', 'sup' ],
	'img_top'                   => [ '1', 'vrh', 'top' ],
	'img_text_top'              => [ '1', 'vrh-besedila', 'text-top' ],
	'img_bottom'                => [ '1', 'dno', 'bottom' ],
	'img_text_bottom'           => [ '1', 'dno-besedila', 'text-bottom' ],
	'sitename'                  => [ '1', 'IMESTRANI', 'SITENAME' ],
	'server'                    => [ '0', 'STREZNIK', 'SERVER' ],
	'grammar'                   => [ '0', 'SKLON:', 'GRAMMAR:' ],
	'gender'                    => [ '0', 'SPOL:', 'GENDER:' ],
	'plural'                    => [ '0', 'MNOZINA:', 'PLURAL:' ],
	'language'                  => [ '0', '#JEZIK:', '#LANGUAGE:' ],
	'tag'                       => [ '0', 'oznaka', 'tag' ],
	'hiddencat'                 => [ '1', '__SKRITAKATEGORIJA__', '__HIDDENCAT__' ],
	'index'                     => [ '1', '__KAZALO__', '__INDEX__' ],
	'noindex'                   => [ '1', '__BREZKAZALA__', '__NOINDEX__' ],
	'staticredirect'            => [ '1', '__STATICNAPREUSMERITEV__', '__STATICREDIRECT__' ],
	'protectionlevel'           => [ '1', 'STOPNJAZASCITE', 'PROTECTIONLEVEL' ],
	'url_path'                  => [ '0', 'POT', 'PATH' ],
	'url_query'                 => [ '0', 'POIZVEDBA', 'QUERY' ],
];

$linkTrail = '/^([a-zčćđžš]+)(.*)$/sDu';

$datePreferences = [
	'default',
	'dmy short',
	'dmy full',
	'ISO 8601',
];

/**
 * The date format to use for generated dates in the user interface.
 * This may be one of the above date preferences, or the special value
 * "dmy or mdy", which uses mdy if $wgAmericanDates is true, and dmy
 * if $wgAmericanDates is false.
 */
$defaultDateFormat = 'dmy full';

$dateFormats = [
	'dmy short time' => 'H:i',
	'dmy short date' => 'j. F Y',
	'dmy short both' => 'H:i, j. M Y',

	'dmy full time' => 'H:i',
	'dmy full date' => 'j. F Y',
	'dmy full both' => 'H:i, j. F Y',
];

$fallback8bitEncoding = "iso-8859-2";
$separatorTransformTable = [ ',' => '.', '.' => ',' ];
