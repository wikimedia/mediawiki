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

$namespaceNames = [
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
];

$namespaceAliases = [
	'Doaimmat' => NS_SPECIAL,
];

$specialPageAliases = [
	'Activeusers'               => [ 'Aktiivvalaš_geavaheaddjit' ],
	'Allmessages'               => [ 'Buot_systemadieđáhusat', 'Buot_vuogádatdieđáhusat' ],
	'Allpages'                  => [ 'Buot_siiddut' ],
	'Ancientpages'              => [ 'Dološ_siiddut' ],
	'Badtitle'                  => [ 'Veadjemeahttun_bajilčála' ],
	'Blankpage'                 => [ 'Guoros_siidu' ],
	'Block'                     => [ 'Hehtte', 'Hehtte_geavaheaddji', 'Hehtte_IP' ],
	'Booksources'               => [ 'Girjegáldut' ],
	'BrokenRedirects'           => [ 'Boatkanan_stivremat', 'Boatkanan_ođđasitstivremat' ],
	'Categories'                => [ 'Kategoriijat' ],
	'ChangeEmail'               => [ 'Rievdat_E-poastta' ],
	'ComparePages'              => [ 'Veardit_siidduid' ],
	'Confirmemail'              => [ 'Sihkaraste_e-poastta' ],
	'Contributions'             => [ 'Rievdadusat' ],
	'CreateAccount'             => [ 'Ráhkat_dovddaldaga', 'Ráhkat_konttu' ],
	'DeletedContributions'      => [ 'Sihkkojuvvon_rievdadusat' ],
	'DoubleRedirects'           => [ 'Guoktegeardásaš_ođđasitstivremat' ],
	'EditWatchlist'             => [ 'Rievdat_čuovvunlisttu' ],
	'Emailuser'                 => [ 'Sádde_e-poastta' ],
	'Export'                    => [ 'Olggosfievrrit_siidduid' ],
	'Import'                    => [ 'Sisafievrrit' ],
	'BlockList'                 => [ 'Hehttenlistu', 'Listu_hehttemiin' ],
	'Listadmins'                => [ 'Administráhtorlistu', 'Listu_administráhtoriin' ],
	'Listbots'                  => [ 'Bohttalistu', 'Listu_bohtain' ],
	'Listfiles'                 => [ 'Fiilalogahallan' ],
	'Listgrouprights'           => [ 'Listu_joavkkuid_vuoigatvuođain' ],
	'Listredirects'             => [ 'Stivrenlistu', 'Listu_stivremiin', 'Listu_ođđasitstivremiin' ],
	'Listusers'                 => [ 'Geavaheaddjelistu', 'Listu_geavaheddjiin' ],
	'Log'                       => [ 'Loggat', 'Logga' ],
	'Lonelypages'               => [ 'Oarbbes_siiddut' ],
	'Longpages'                 => [ 'Guhkes_siiddut' ],
	'MIMEsearch'                => [ 'MIME-ohcan' ],
	'Movepage'                  => [ 'Sirdde_siiddu' ],
	'Mycontributions'           => [ 'Mu_rievdadusat' ],
	'Mypage'                    => [ 'Mu_siidu' ],
	'Mytalk'                    => [ 'Mu_ságastallan' ],
	'Newimages'                 => [ 'Ođđa_govat', 'Ođđa_fiillat' ],
	'Newpages'                  => [ 'Ođđa_siiddut' ],
	'Preferences'               => [ 'Válljemat', 'Ásahusat' ],
	'Protectedpages'            => [ 'Suodjaluvvon_siiddut' ],
	'Protectedtitles'           => [ 'Suodjaluvvon_bajilčállagat', 'Suodjaluvvon_siidonamat' ],
	'Randompage'                => [ 'Summal', 'Summal_siidu' ],
	'Randomredirect'            => [ 'Summal_ođđasitstivren' ],
	'Recentchanges'             => [ 'Varas_rievdadusat' ],
	'Revisiondelete'            => [ 'Sihko_veršuvnna' ],
	'Search'                    => [ 'Oza' ],
	'Shortpages'                => [ 'Oanehis_siiddut' ],
	'Specialpages'              => [ 'Erenoamáš_siiddut', 'Doaibmasiiddut' ],
	'Statistics'                => [ 'Statistihkat' ],
	'Unblock'                   => [ 'Sihko_hehttema' ],
	'Uncategorizedcategories'   => [ 'Klassifiserekeahtes_kategoriijat' ],
	'Uncategorizedimages'       => [ 'Klassifiserekeahtes_fiillat', 'Klassifiserekeahtes_govat' ],
	'Uncategorizedpages'        => [ 'Klassifiserekeahtes_siiddut' ],
	'Uncategorizedtemplates'    => [ 'Klassifiserekeahtes_mállet' ],
	'Undelete'                  => [ 'Máhccat' ],
	'Unusedcategories'          => [ 'Geavatkeahtes_kategoriijat' ],
	'Unusedimages'              => [ 'Geavatkeahtes_govat', 'Geavatkeahtes_fiillat' ],
	'Unusedtemplates'           => [ 'Geavatkeahtes_mállet' ],
	'Unwatchedpages'            => [ 'Čuovotkeahtes_siiddut' ],
	'Upload'                    => [ 'Sádde_fiilla' ],
	'Userlogin'                 => [ 'Logge_sisa' ],
	'Userlogout'                => [ 'Logge_olggos' ],
	'Userrights'                => [ 'Geavaheaddjevuoigatvuođat' ],
	'Version'                   => [ 'Veršuvdna' ],
	'Wantedcategories'          => [ 'Kategoriijasávaldagat' ],
	'Wantedfiles'               => [ 'Fiilasávaldagat', 'Govvasávaldagat' ],
	'Wantedpages'               => [ 'Siidosávaldagat' ],
	'Wantedtemplates'           => [ 'Mállesávaldagat' ],
	'Watchlist'                 => [ 'Čuovvunlistu' ],
	'Withoutinterwiki'          => [ 'Interwikihis_siiddut', 'Giellaliŋkkahis_siiddut', 'Giellaleaŋkkahis_siiddut' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#STIVREN', '#OĐĐASITSTIVREN', '#REDIRECT' ],
	'notoc'                     => [ '0', '__IISISDOALLU__', '__IISIS__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__IIGALLERIIJA__', '__NOGALLERY__' ],
	'toc'                       => [ '0', '__SISDOALLU__', ' __SIS__', '__TOC__' ],
	'noeditsection'             => [ '0', '__IIRIEVDADITOASI__', '__NOEDITSECTION__' ],
	'numberofarticles'          => [ '1', 'ARTIHKKALIIDMEARRI', ' ARTIHKALMEARRI', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'FIILLAIDMEARRI', 'FIILAMEARRI', ' GOVAIDMEARRI', ' GOVVAMEARRI', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'GEAVAHEDDJIIDMEARRI', ' GEAVAHEADDJIMEARRI', ' GEAVAHEADDJEMEARRI', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'AKTIIVAGEAVAHEDDJIIDMEARRI', ' AKTIIVAGEAVAHEADDJIMEARRI', ' AKTIIVAGEAVAHEADDJEMEARRI', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', 'RIEVDADUSAIDMEARRI', ' RIEVDADUSMEARRI', 'NUMBEROFEDITS' ],
	'subst'                     => [ '0', 'LIIBME:', 'SUBST:' ],
	'img_thumbnail'             => [ '1', 'mini', 'thumb', 'thumbnail' ],
	'img_manualthumb'           => [ '1', 'mini=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'olgeš', 'right' ],
	'img_left'                  => [ '1', 'gurut', 'left' ],
	'img_center'                => [ '1', 'gasku', 'center', 'centre' ],
	'img_link'                  => [ '1', 'liŋka=$1', 'link=$1' ],
];

$separatorTransformTable = [ ',' => "\xc2\xa0", '.' => ',' ];

$linkTrail = '/^(:?[a-zàáâçčʒǯđðéèêëǧǥȟíìîïıǩŋñóòôõßšŧúùûýÿüžþæøåäö]+)(.*)$/sDu';
