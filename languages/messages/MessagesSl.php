<?php
/** Slovenian (slovenščina)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
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

$namespaceNames = array(
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
);

$specialPageAliases = array(
	'Activeusers'               => array( 'AktivniUporabniki' ),
	'Allmessages'               => array( 'VsaSporočila' ),
	'Allpages'                  => array( 'VseStrani' ),
	'Ancientpages'              => array( 'StarodavneStrani' ),
	'Blankpage'                 => array( 'PraznaStran' ),
	'Block'                     => array( 'Blokiraj', 'BlokirajIP', 'BlokirajUporabnika' ),
	'Booksources'               => array( 'ViriKnjig' ),
	'BrokenRedirects'           => array( 'PretrganePreusmeritve' ),
	'Categories'                => array( 'Kategorije' ),
	'ChangePassword'            => array( 'SpremeniGeslo', 'PonastaviGeslo' ),
	'Contributions'             => array( 'Prispevki' ),
	'CreateAccount'             => array( 'Registracija' ),
	'DeletedContributions'      => array( 'IzbrisaniPrispevki' ),
	'DoubleRedirects'           => array( 'DvojnePreusmeritve' ),
	'Export'                    => array( 'Izvozi' ),
	'Fewestrevisions'           => array( 'NajmanjRedakcij' ),
	'Filepath'                  => array( 'PotDatoteke' ),
	'Import'                    => array( 'Uvoz' ),
	'Listadmins'                => array( 'SeznamAdministratorjev' ),
	'Listbots'                  => array( 'SeznamBotov' ),
	'Listfiles'                 => array( 'SeznamDatotek', 'SeznamSlik' ),
	'Listgrouprights'           => array( 'SeznamPravicSkupin' ),
	'Listusers'                 => array( 'SeznamUporabnikov' ),
	'Log'                       => array( 'Dnevnik', 'Dnevniki' ),
	'Lonelypages'               => array( 'OsiroteleStrani' ),
	'Longpages'                 => array( 'DolgeStrani' ),
	'MergeHistory'              => array( 'ZdružiZgodovino' ),
	'MIMEsearch'                => array( 'IskanjeMIME' ),
	'Mostcategories'            => array( 'NajvečKategorij' ),
	'Mostimages'                => array( 'NajboljPovezaneDatoteke' ),
	'Mostlinked'                => array( 'NajboljPovezaneStrani' ),
	'Mostlinkedcategories'      => array( 'NajboljPovezaneKategorije' ),
	'Mostlinkedtemplates'       => array( 'NajboljPovezanePredloge' ),
	'Mostrevisions'             => array( 'NajvečRedakcij' ),
	'Movepage'                  => array( 'PrestaviStran', 'PremakniStran' ),
	'Mycontributions'           => array( 'MojiPrispevki' ),
	'Mypage'                    => array( 'MojaStran' ),
	'Mytalk'                    => array( 'MojPogovor' ),
	'Newimages'                 => array( 'NoveDatoteke', 'NoveSlike' ),
	'Newpages'                  => array( 'NoveStrani' ),
	'Popularpages'              => array( 'PriljubljeneStrani' ),
	'Preferences'               => array( 'Nastavitve' ),
	'Protectedpages'            => array( 'ZaščiteneStrani' ),
	'Protectedtitles'           => array( 'ZaščiteniNaslovi' ),
	'Randompage'                => array( 'Naključno', 'NaključnaStran' ),
	'Recentchanges'             => array( 'ZadnjeSpremembe' ),
	'Search'                    => array( 'Iskanje' ),
	'Shortpages'                => array( 'KratkeStrani' ),
	'Specialpages'              => array( 'PosebneStrani' ),
	'Statistics'                => array( 'Statistika' ),
	'Unblock'                   => array( 'Odblokiraj' ),
	'Uncategorizedcategories'   => array( 'NekategoriziraneKategorije' ),
	'Uncategorizedimages'       => array( 'NekategoriziraneDatoteke', 'NekategoriziraneSlike' ),
	'Uncategorizedpages'        => array( 'NekategoriziraneStrani' ),
	'Uncategorizedtemplates'    => array( 'NekategoriziranePredloge' ),
	'Undelete'                  => array( 'Obnovi' ),
	'Unusedcategories'          => array( 'NeuporabljeneKategorije' ),
	'Unusedimages'              => array( 'NeuporabljeneDatoteke', 'NeuporabljeneSlike' ),
	'Unwatchedpages'            => array( 'NespremljaneStrani' ),
	'Upload'                    => array( 'Nalaganje' ),
	'Userlogin'                 => array( 'Prijava' ),
	'Userlogout'                => array( 'Odjava' ),
	'Version'                   => array( 'Različica', 'Verzija' ),
	'Wantedcategories'          => array( 'ŽeleneKategorije' ),
	'Wantedfiles'               => array( 'ŽeleneDatoteke' ),
	'Wantedpages'               => array( 'ŽeleneStrani' ),
	'Wantedtemplates'           => array( 'ŽelenePredloge' ),
	'Watchlist'                 => array( 'SpisekNadzorov' ),
	'Whatlinkshere'             => array( 'KajSePovezujeSem' ),
	'Withoutinterwiki'          => array( 'BrezInterwikijev' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#PREUSMERITEV', '#REDIRECT' ),
	'notoc'                     => array( '0', '__BREZKAZALAVSEBINE__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__BREZGALERIJE__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__VSILIKAZALOVSEBINE__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__POGLAVJE__', '__TOC__' ),
	'noeditsection'             => array( '0', '__BREZUREJANJARAZDELKOV__', '__NOEDITSECTION__' ),
	'img_thumbnail'             => array( '1', 'sličica', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'sličica=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'desno', 'right' ),
	'img_left'                  => array( '1', 'levo', 'left' ),
	'img_none'                  => array( '1', 'brez', 'none' ),
	'img_width'                 => array( '1', '$1_pik', '$1px' ),
	'img_center'                => array( '1', 'sredina', 'sredinsko', 'center', 'centre' ),
	'img_framed'                => array( '1', 'okvir', 'okvirjeno', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'brezokvirja', 'frameless' ),
	'img_page'                  => array( '1', 'stran=$1', 'm_stran $1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'zgorajdesno', 'zgorajdesno=$1', 'zgorajdesno $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'obroba', 'border' ),
	'img_sub'                   => array( '1', 'pod', 'podpisano', 'sub' ),
	'img_super'                 => array( '1', 'nad', 'nadpisano', 'super', 'sup' ),
	'img_top'                   => array( '1', 'vrh', 'top' ),
	'img_text_top'              => array( '1', 'vrh-besedila', 'text-top' ),
	'img_bottom'                => array( '1', 'dno', 'bottom' ),
	'img_text_bottom'           => array( '1', 'dno-besedila', 'text-bottom' ),
	'sitename'                  => array( '1', 'IMESTRANI', 'SITENAME' ),
	'server'                    => array( '0', 'STREZNIK', 'SERVER' ),
	'grammar'                   => array( '0', 'SKLON:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'SPOL:', 'GENDER:' ),
	'plural'                    => array( '0', 'MNOZINA:', 'PLURAL:' ),
	'language'                  => array( '0', '#JEZIK:', '#LANGUAGE:' ),
	'tag'                       => array( '0', 'oznaka', 'tag' ),
	'hiddencat'                 => array( '1', '__SKRITAKATEGORIJA__', '__HIDDENCAT__' ),
	'index'                     => array( '1', '__KAZALO__', '__INDEX__' ),
	'noindex'                   => array( '1', '__BREZKAZALA__', '__NOINDEX__' ),
	'staticredirect'            => array( '1', '__STATICNAPREUSMERITEV__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'STOPNJAZASCITE', 'PROTECTIONLEVEL' ),
	'url_path'                  => array( '0', 'POT', 'PATH' ),
	'url_query'                 => array( '0', 'POIZVEDBA', 'QUERY' ),
);

$linkTrail = '/^([a-zčćđžš]+)(.*)$/sDu';

$datePreferences = array(
	'default',
	'dmy short',
	'dmy full',
	'ISO 8601',
);

/**
 * The date format to use for generated dates in the user interface.
 * This may be one of the above date preferences, or the special value
 * "dmy or mdy", which uses mdy if $wgAmericanDates is true, and dmy
 * if $wgAmericanDates is false.
 */
$defaultDateFormat = 'dmy full';

$dateFormats = array(
	'dmy short time' => 'H:i',
	'dmy short date' => 'j. F Y',
	'dmy short both' => 'H:i, j. M Y',

	'dmy full time' => 'H:i',
	'dmy full date' => 'j. F Y',
	'dmy full both' => 'H:i, j. F Y',
);

$fallback8bitEncoding = "iso-8859-2";
$separatorTransformTable = array( ',' => '.', '.' => ',' );

