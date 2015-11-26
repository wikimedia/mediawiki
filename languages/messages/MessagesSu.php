<?php
/** Sundanese (Basa Sunda)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Aditia.Gunawan
 * @author Irwangatot
 * @author Kaganer
 * @author Kandar
 * @author Meursault2004
 * @author Mssetiadi
 * @author Reedy
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$fallback = 'id';

$namespaceNames = array(
	NS_MEDIA            => 'Média',
	NS_SPECIAL          => 'Husus',
	NS_TALK             => 'Obrolan',
	NS_USER             => 'Pamaké',
	NS_USER_TALK        => 'Obrolan_pamaké',
	NS_PROJECT_TALK     => 'Obrolan_$1',
	NS_FILE             => 'Gambar',
	NS_FILE_TALK        => 'Obrolan_gambar',
	NS_MEDIAWIKI        => 'MédiaWiki',
	NS_MEDIAWIKI_TALK   => 'Obrolan_MédiaWiki',
	NS_TEMPLATE         => 'Citakan',
	NS_TEMPLATE_TALK    => 'Obrolan_citakan',
	NS_HELP             => 'Pitulung',
	NS_HELP_TALK        => 'Obrolan_pitulung',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Obrolan_kategori',
);

$namespaceAliases = array(
	'Obrolan_MediaWiki' => NS_MEDIAWIKI_TALK,
);

$specialPageAliases = array(
	'Allmessages'               => array( 'SadayaTalatah' ),
	'Allpages'                  => array( 'SadayaKaca' ),
	'Ancientpages'              => array( 'KacaKolot' ),
	'Blankpage'                 => array( 'KacaKosong' ),
	'Block'                     => array( 'PeungpeukIP' ),
	'Booksources'               => array( 'SumberPustaka' ),
	'BrokenRedirects'           => array( 'AlihanPegat' ),
	'Categories'                => array( 'Kategori' ),
	'ChangePassword'            => array( 'GantiSandi' ),
	'Confirmemail'              => array( 'KonfirmasiSurelek' ),
	'Contributions'             => array( 'Kontribusi' ),
	'CreateAccount'             => array( 'NyieunRekening' ),
	'Deadendpages'              => array( 'KacaBuntu' ),
	'DoubleRedirects'           => array( 'AlihanGanda' ),
	'Emailuser'                 => array( 'SurelekPamake' ),
	'Export'                    => array( 'Ekspor' ),
	'Fewestrevisions'           => array( 'PangjarangnaRevisi' ),
	'FileDuplicateSearch'       => array( 'SungsiGambarDuplikat' ),
	'Filepath'                  => array( 'JalurKoropak' ),
	'Import'                    => array( 'Impor' ),
	'Invalidateemail'           => array( 'SurelekTeuKaci' ),
	'BlockList'                 => array( 'IPDipeungpeuk' ),
	'Listadmins'                => array( 'DaptarKuncen' ),
	'Listbots'                  => array( 'DaptarBot' ),
	'Listfiles'                 => array( 'DaptarGambar' ),
	'Listgrouprights'           => array( 'DaptarHakPamake' ),
	'Listredirects'             => array( 'DaptarAlihan' ),
	'Listusers'                 => array( 'DaptarPamake' ),
	'Lockdb'                    => array( 'KonciDB' ),
	'Lonelypages'               => array( 'KacaNyorangan' ),
	'Longpages'                 => array( 'KacaPanjang' ),
	'MergeHistory'              => array( 'GabungJujutan' ),
	'MIMEsearch'                => array( 'SungsiMIME' ),
	'Mostcategories'            => array( 'KategoriPalingLoba' ),
	'Mostimages'                => array( 'GambarPalingKapake' ),
	'Mostlinked'                => array( 'PalingDitumbu' ),
	'Mostlinkedcategories'      => array( 'KategoriPalingDitumbu', 'KategoriPalingKapake' ),
	'Mostlinkedtemplates'       => array( 'CitakanPalingDitumbu', 'CitakanPalingKapake' ),
	'Mostrevisions'             => array( 'PalingRevisi' ),
	'Movepage'                  => array( 'PindahkeunKaca' ),
	'Mycontributions'           => array( 'KontribusiKuring' ),
	'Mypage'                    => array( 'KacaKuring' ),
	'Mytalk'                    => array( 'ObrolanKuring' ),
	'Newimages'                 => array( 'GambarAnyar' ),
	'Newpages'                  => array( 'KacaAnyar' ),
	'Preferences'               => array( 'Preferensi' ),
	'Prefixindex'               => array( 'IndeksAwalan' ),
	'Protectedpages'            => array( 'KacaDikonci' ),
	'Protectedtitles'           => array( 'JudulDikonci' ),
	'Randompage'                => array( 'Acak', 'KacaAcak' ),
	'Randomredirect'            => array( 'AlihanAcak' ),
	'Recentchanges'             => array( 'AnyarRobah' ),
	'Recentchangeslinked'       => array( 'ParobahanPatali' ),
	'Revisiondelete'            => array( 'HapusRevisi' ),
	'Search'                    => array( 'Sungsi' ),
	'Shortpages'                => array( 'KacaPondok' ),
	'Specialpages'              => array( 'KacaHusus' ),
	'Statistics'                => array( 'Statistika' ),
	'Tags'                      => array( 'Tag' ),
	'Uncategorizedcategories'   => array( 'KategoriTanpaKategori' ),
	'Uncategorizedimages'       => array( 'GambarTanpaKategori' ),
	'Uncategorizedpages'        => array( 'KacaTanpaKategori' ),
	'Uncategorizedtemplates'    => array( 'CitakanTanpaKategori' ),
	'Undelete'                  => array( 'BolayHapus' ),
	'Unlockdb'                  => array( 'BukaKonciDB' ),
	'Unusedcategories'          => array( 'KategoriNganggur' ),
	'Unusedimages'              => array( 'GambarNganggur' ),
	'Unusedtemplates'           => array( 'CitakanNganggur' ),
	'Unwatchedpages'            => array( 'KacaTeuDiawaskeun' ),
	'Upload'                    => array( 'Kunjal' ),
	'Userlogin'                 => array( 'AsupLog' ),
	'Userlogout'                => array( 'KaluarLog' ),
	'Userrights'                => array( 'HakPamake' ),
	'Version'                   => array( 'Versi' ),
	'Wantedcategories'          => array( 'KategoriDiteang' ),
	'Wantedpages'               => array( 'KacaDiteang', 'TumbuPegat' ),
	'Watchlist'                 => array( 'Awaskeuneun' ),
	'Whatlinkshere'             => array( 'NumbuKaDieu' ),
	'Withoutinterwiki'          => array( 'TanpaInterwiki' ),
);

