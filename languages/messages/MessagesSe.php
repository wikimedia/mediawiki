<?php
/** Northern Sami (sámegiella)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Gálaniitoluodda
 * @author Jeblad
 * @author Kaganer
 * @author Laila
 * @author Linnea
 * @author Skuolfi
 * @author Teak
 * @author The Evil IP address
 * @author Trondtr
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Erenoamáš',
	NS_TALK             => 'Ságastallan',
	NS_USER             => 'Geavaheaddji',
	NS_USER_TALK        => 'Geavaheaddjeságastallan',
	NS_PROJECT_TALK     => '$1-ságastallan',
	NS_FILE             => 'Fiila',
	NS_FILE_TALK        => 'Fiilaságastallan',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki-ságastallan',
	NS_TEMPLATE         => 'Málle',
	NS_TEMPLATE_TALK    => 'Málleságastallan',
	NS_HELP             => 'Veahkki',
	NS_HELP_TALK        => 'Veahkkeságastallan',
	NS_CATEGORY         => 'Kategoriija',
	NS_CATEGORY_TALK    => 'Kategoriijaságastallan',
);

$namespaceAliases = array(
	'Doaimmat' => NS_SPECIAL,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Aktiivvalaš_geavaheaddjit' ),
	'Allmessages'               => array( 'Buot_systemadieđáhusat', 'Buot_vuogádatdieđáhusat' ),
	'Allpages'                  => array( 'Buot_siiddut' ),
	'Ancientpages'              => array( 'Dološ_siiddut' ),
	'Badtitle'                  => array( 'Veadjemeahttun_bajilčála' ),
	'Blankpage'                 => array( 'Guoros_siidu' ),
	'Block'                     => array( 'Hehtte', 'Hehtte_geavaheaddji', 'Hehtte_IP' ),
	'Booksources'               => array( 'Girjegáldut' ),
	'BrokenRedirects'           => array( 'Boatkanan_stivremat', 'Boatkanan_ođđasitstivremat' ),
	'Categories'                => array( 'Kategoriijat' ),
	'ChangeEmail'               => array( 'Rievdat_E-poastta' ),
	'ComparePages'              => array( 'Veardit_siidduid' ),
	'Confirmemail'              => array( 'Sihkaraste_e-poastta' ),
	'Contributions'             => array( 'Rievdadusat' ),
	'CreateAccount'             => array( 'Ráhkat_dovddaldaga', 'Ráhkat_konttu' ),
	'DeletedContributions'      => array( 'Sihkkojuvvon_rievdadusat' ),
	'DoubleRedirects'           => array( 'Guoktegeardásaš_ođđasitstivremat' ),
	'EditWatchlist'             => array( 'Rievdat_čuovvunlisttu' ),
	'Emailuser'                 => array( 'Sádde_e-poastta' ),
	'Export'                    => array( 'Olggosfievrrit_siidduid' ),
	'Import'                    => array( 'Sisafievrrit' ),
	'BlockList'                 => array( 'Hehttenlistu', 'Listu_hehttemiin' ),
	'Listadmins'                => array( 'Administráhtorlistu', 'Listu_administráhtoriin' ),
	'Listbots'                  => array( 'Bohttalistu', 'Listu_bohtain' ),
	'Listfiles'                 => array( 'Fiilalogahallan' ),
	'Listgrouprights'           => array( 'Listu_joavkkuid_vuoigatvuođain' ),
	'Listredirects'             => array( 'Stivrenlistu', 'Listu_stivremiin', 'Listu_ođđasitstivremiin' ),
	'Listusers'                 => array( 'Geavaheaddjelistu', 'Listu_geavaheddjiin' ),
	'Log'                       => array( 'Loggat', 'Logga' ),
	'Lonelypages'               => array( 'Oarbbes_siiddut' ),
	'Longpages'                 => array( 'Guhkes_siiddut' ),
	'MIMEsearch'                => array( 'MIME-ohcan' ),
	'Movepage'                  => array( 'Sirdde_siiddu' ),
	'Mycontributions'           => array( 'Mu_rievdadusat' ),
	'Mypage'                    => array( 'Mu_siidu' ),
	'Mytalk'                    => array( 'Mu_ságastallan' ),
	'Newimages'                 => array( 'Ođđa_govat', 'Ođđa_fiillat' ),
	'Newpages'                  => array( 'Ođđa_siiddut' ),
	'Preferences'               => array( 'Válljemat', 'Ásahusat' ),
	'Protectedpages'            => array( 'Suodjaluvvon_siiddut' ),
	'Protectedtitles'           => array( 'Suodjaluvvon_bajilčállagat', 'Suodjaluvvon_siidonamat' ),
	'Randompage'                => array( 'Summal', 'Summal_siidu' ),
	'Randomredirect'            => array( 'Summal_ođđasitstivren' ),
	'Recentchanges'             => array( 'Varas_rievdadusat' ),
	'Revisiondelete'            => array( 'Sihko_veršuvnna' ),
	'Search'                    => array( 'Oza' ),
	'Shortpages'                => array( 'Oanehis_siiddut' ),
	'Specialpages'              => array( 'Erenoamáš_siiddut', 'Doaibmasiiddut' ),
	'Statistics'                => array( 'Statistihkat' ),
	'Unblock'                   => array( 'Sihko_hehttema' ),
	'Uncategorizedcategories'   => array( 'Klassifiserekeahtes_kategoriijat' ),
	'Uncategorizedimages'       => array( 'Klassifiserekeahtes_fiillat', 'Klassifiserekeahtes_govat' ),
	'Uncategorizedpages'        => array( 'Klassifiserekeahtes_siiddut' ),
	'Uncategorizedtemplates'    => array( 'Klassifiserekeahtes_mállet' ),
	'Undelete'                  => array( 'Máhccat' ),
	'Unusedcategories'          => array( 'Geavatkeahtes_kategoriijat' ),
	'Unusedimages'              => array( 'Geavatkeahtes_govat', 'Geavatkeahtes_fiillat' ),
	'Unusedtemplates'           => array( 'Geavatkeahtes_mállet' ),
	'Unwatchedpages'            => array( 'Čuovotkeahtes_siiddut' ),
	'Upload'                    => array( 'Sádde_fiilla' ),
	'Userlogin'                 => array( 'Logge_sisa' ),
	'Userlogout'                => array( 'Logge_olggos' ),
	'Userrights'                => array( 'Geavaheaddjevuoigatvuođat' ),
	'Version'                   => array( 'Veršuvdna' ),
	'Wantedcategories'          => array( 'Kategoriijasávaldagat' ),
	'Wantedfiles'               => array( 'Fiilasávaldagat', 'Govvasávaldagat' ),
	'Wantedpages'               => array( 'Siidosávaldagat' ),
	'Wantedtemplates'           => array( 'Mállesávaldagat' ),
	'Watchlist'                 => array( 'Čuovvunlistu' ),
	'Withoutinterwiki'          => array( 'Interwikihis_siiddut', 'Giellaliŋkkahis_siiddut', 'Giellaleaŋkkahis_siiddut' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#STIVREN', '#OĐĐASITSTIVREN', '#REDIRECT' ),
	'notoc'                     => array( '0', '__IISISDOALLU__', '__IISIS__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__IIGALLERIIJA__', '__NOGALLERY__' ),
	'toc'                       => array( '0', '__SISDOALLU__', ' __SIS__', '__TOC__' ),
	'noeditsection'             => array( '0', '__IIRIEVDADITOASI__', '__NOEDITSECTION__' ),
	'numberofarticles'          => array( '1', 'ARTIHKKALIIDMEARRI', ' ARTIHKALMEARRI', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'FIILLAIDMEARRI', 'FIILAMEARRI', ' GOVAIDMEARRI', ' GOVVAMEARRI', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'GEAVAHEDDJIIDMEARRI', ' GEAVAHEADDJIMEARRI', ' GEAVAHEADDJEMEARRI', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'AKTIIVAGEAVAHEDDJIIDMEARRI', ' AKTIIVAGEAVAHEADDJIMEARRI', ' AKTIIVAGEAVAHEADDJEMEARRI', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'RIEVDADUSAIDMEARRI', ' RIEVDADUSMEARRI', 'NUMBEROFEDITS' ),
	'subst'                     => array( '0', 'LIIBME:', 'SUBST:' ),
	'img_thumbnail'             => array( '1', 'mini', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'mini=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'olgeš', 'right' ),
	'img_left'                  => array( '1', 'gurut', 'left' ),
	'img_center'                => array( '1', 'gasku', 'center', 'centre' ),
	'img_link'                  => array( '1', 'liŋka=$1', 'link=$1' ),
);

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$linkTrail = '/^(:?[a-zàáâçčʒǯđðéèêëǧǥȟíìîïıǩŋñóòôõßšŧúùûýÿüžþæøåäö]+)(.*)$/sDu';

