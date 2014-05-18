<?php
/** Basque (euskara)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Berezi',
	NS_TALK             => 'Eztabaida',
	NS_USER             => 'Lankide',
	NS_USER_TALK        => 'Lankide_eztabaida',
	NS_PROJECT_TALK     => '$1_eztabaida',
	NS_FILE             => 'Fitxategi',
	NS_FILE_TALK        => 'Fitxategi_eztabaida',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_eztabaida',
	NS_TEMPLATE         => 'Txantiloi',
	NS_TEMPLATE_TALK    => 'Txantiloi_eztabaida',
	NS_HELP             => 'Laguntza',
	NS_HELP_TALK        => 'Laguntza_eztabaida',
	NS_CATEGORY         => 'Kategoria',
	NS_CATEGORY_TALK    => 'Kategoria_eztabaida',
);

$namespaceAliases = array(
	'Aparteko' => NS_SPECIAL,
	'Irudi' => NS_FILE,
	'Irudi_eztabaida' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'Allmessages'               => array( 'MezuGuztiak' ),
	'Allpages'                  => array( 'OrrialdeGuztiak' ),
	'Ancientpages'              => array( 'OrrialdeZaharrak' ),
	'Blankpage'                 => array( 'OrrialdeZuria' ),
	'Block'                     => array( 'Blokeatu' ),
	'BrokenRedirects'           => array( 'HautsitakoBirzuzenketak' ),
	'Categories'                => array( 'Kategoriak' ),
	'ChangePassword'            => array( 'PasahitzaAldatu' ),
	'Confirmemail'              => array( 'EmailaBaieztatu' ),
	'Contributions'             => array( 'Ekarpenak' ),
	'CreateAccount'             => array( 'KontuaSortu' ),
	'DoubleRedirects'           => array( 'BirzuzenketaBikoitzak' ),
	'Emailuser'                 => array( 'LankideEmaila' ),
	'Export'                    => array( 'Esportatu' ),
	'Import'                    => array( 'Inportatu' ),
	'Listadmins'                => array( 'AdministratzaileZerrenda' ),
	'Listbots'                  => array( 'BotZerrenda' ),
	'Listfiles'                 => array( 'FitxategiZerrenda' ),
	'Listusers'                 => array( 'LankideZerrenda' ),
	'Longpages'                 => array( 'OrrialdeLuzeak' ),
	'Movepage'                  => array( 'OrrialdeaMugitu' ),
	'Mycontributions'           => array( 'NireEkarpenak' ),
	'Mypage'                    => array( 'NireOrrialdea' ),
	'Mytalk'                    => array( 'NireEztabaida' ),
	'Newimages'                 => array( 'FitxategiBerriak' ),
	'Newpages'                  => array( 'OrrialdeBerriak' ),
	'Preferences'               => array( 'Hobespenak' ),
	'Protectedpages'            => array( 'BabestutakoOrrialdeak' ),
	'Protectedtitles'           => array( 'BabestutakoIzenburuak' ),
	'Randompage'                => array( 'Ausazkoa' ),
	'Recentchanges'             => array( 'AzkenAldaketak' ),
	'Search'                    => array( 'Bilatu' ),
	'Shortpages'                => array( 'OrrialdeMotzak' ),
	'Specialpages'              => array( 'OrrialdeBereziak' ),
	'Statistics'                => array( 'Estatistikak' ),
	'Tags'                      => array( 'Etiketak' ),
	'Uncategorizedcategories'   => array( 'KategorizatuGabekoKategoriak' ),
	'Uncategorizedimages'       => array( 'KategorizatuGabekoFitxategiak' ),
	'Uncategorizedpages'        => array( 'KategorizatuGabekoOrrialdeak' ),
	'Uncategorizedtemplates'    => array( 'KategorizatuGabekoTxantiloiak' ),
	'Upload'                    => array( 'Igo' ),
	'Userlogin'                 => array( 'SaioaHasi' ),
	'Userlogout'                => array( 'SaioaItxi' ),
	'Version'                   => array( 'Bertsioa' ),
	'Wantedcategories'          => array( 'EskatutakoKategoriak' ),
	'Wantedfiles'               => array( 'EskatutakoFitxategiak' ),
	'Wantedpages'               => array( 'EskatutakoOrrialdeak' ),
	'Wantedtemplates'           => array( 'EskatutakoTxantiloiak' ),
	'Watchlist'                 => array( 'JarraipenZerrenda' ),
	'Whatlinkshere'             => array( 'ZerkLotzenDuHona' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#BIRZUZENDU', '#REDIRECT' ),
	'currentmonth'              => array( '1', 'ORAINGOHILABETE', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'ORAINGOHILABETEIZEN', 'CURRENTMONTHNAME' ),
	'currentday'                => array( '1', 'ORAINGOEGUN', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'ORAINGOEGUN2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'ORAINGOEGUNIZEN', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'ORAINGOURTE', 'CURRENTYEAR' ),
	'numberofpages'             => array( '1', 'ORRIALDEKOPURU', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'ARTIKULUKOPURU', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'FITXATEGIKOPURU', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'LANKIDEKOPURU', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'ALDAKETAKOPURU', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'ORRIALDEIZEN', 'PAGENAME' ),
	'img_right'                 => array( '1', 'eskuinera', 'right' ),
	'img_left'                  => array( '1', 'ezkerrera', 'left' ),
	'img_center'                => array( '1', 'erdian', 'center', 'centre' ),
);

$separatorTransformTable = array( ',' => '.', '.' => ',' ); /* Bug 15717 */

