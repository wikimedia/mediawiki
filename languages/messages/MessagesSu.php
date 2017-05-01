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

$namespaceNames = [
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
];

$namespaceAliases = [
	'Obrolan_MediaWiki' => NS_MEDIAWIKI_TALK,
];

$specialPageAliases = [
	'Allmessages'               => [ 'SadayaTalatah' ],
	'Allpages'                  => [ 'SadayaKaca' ],
	'Ancientpages'              => [ 'KacaKolot' ],
	'Blankpage'                 => [ 'KacaKosong' ],
	'Block'                     => [ 'PeungpeukIP' ],
	'Booksources'               => [ 'SumberPustaka' ],
	'BrokenRedirects'           => [ 'AlihanPegat' ],
	'Categories'                => [ 'Kategori' ],
	'ChangePassword'            => [ 'GantiSandi' ],
	'Confirmemail'              => [ 'KonfirmasiSurelek' ],
	'Contributions'             => [ 'Kontribusi' ],
	'CreateAccount'             => [ 'NyieunRekening' ],
	'Deadendpages'              => [ 'KacaBuntu' ],
	'DoubleRedirects'           => [ 'AlihanGanda' ],
	'Emailuser'                 => [ 'SurelekPamake' ],
	'Export'                    => [ 'Ekspor' ],
	'Fewestrevisions'           => [ 'PangjarangnaRevisi' ],
	'FileDuplicateSearch'       => [ 'SungsiGambarDuplikat' ],
	'Filepath'                  => [ 'JalurKoropak' ],
	'Import'                    => [ 'Impor' ],
	'Invalidateemail'           => [ 'SurelekTeuKaci' ],
	'BlockList'                 => [ 'IPDipeungpeuk' ],
	'Listadmins'                => [ 'DaptarKuncen' ],
	'Listbots'                  => [ 'DaptarBot' ],
	'Listfiles'                 => [ 'DaptarGambar' ],
	'Listgrouprights'           => [ 'DaptarHakPamake' ],
	'Listredirects'             => [ 'DaptarAlihan' ],
	'Listusers'                 => [ 'DaptarPamake' ],
	'Lockdb'                    => [ 'KonciDB' ],
	'Lonelypages'               => [ 'KacaNyorangan' ],
	'Longpages'                 => [ 'KacaPanjang' ],
	'MergeHistory'              => [ 'GabungJujutan' ],
	'MIMEsearch'                => [ 'SungsiMIME' ],
	'Mostcategories'            => [ 'KategoriPalingLoba' ],
	'Mostimages'                => [ 'GambarPalingKapake' ],
	'Mostlinked'                => [ 'PalingDitumbu' ],
	'Mostlinkedcategories'      => [ 'KategoriPalingDitumbu', 'KategoriPalingKapake' ],
	'Mostlinkedtemplates'       => [ 'CitakanPalingDitumbu', 'CitakanPalingKapake' ],
	'Mostrevisions'             => [ 'PalingRevisi' ],
	'Movepage'                  => [ 'PindahkeunKaca' ],
	'Mycontributions'           => [ 'KontribusiKuring' ],
	'Mypage'                    => [ 'KacaKuring' ],
	'Mytalk'                    => [ 'ObrolanKuring' ],
	'Newimages'                 => [ 'GambarAnyar' ],
	'Newpages'                  => [ 'KacaAnyar' ],
	'Preferences'               => [ 'Preferensi' ],
	'Prefixindex'               => [ 'IndeksAwalan' ],
	'Protectedpages'            => [ 'KacaDikonci' ],
	'Protectedtitles'           => [ 'JudulDikonci' ],
	'Randompage'                => [ 'Acak', 'KacaAcak' ],
	'Randomredirect'            => [ 'AlihanAcak' ],
	'Recentchanges'             => [ 'AnyarRobah' ],
	'Recentchangeslinked'       => [ 'ParobahanPatali' ],
	'Revisiondelete'            => [ 'HapusRevisi' ],
	'Search'                    => [ 'Sungsi' ],
	'Shortpages'                => [ 'KacaPondok' ],
	'Specialpages'              => [ 'KacaHusus' ],
	'Statistics'                => [ 'Statistika' ],
	'Tags'                      => [ 'Tag' ],
	'Uncategorizedcategories'   => [ 'KategoriTanpaKategori' ],
	'Uncategorizedimages'       => [ 'GambarTanpaKategori' ],
	'Uncategorizedpages'        => [ 'KacaTanpaKategori' ],
	'Uncategorizedtemplates'    => [ 'CitakanTanpaKategori' ],
	'Undelete'                  => [ 'BolayHapus' ],
	'Unlockdb'                  => [ 'BukaKonciDB' ],
	'Unusedcategories'          => [ 'KategoriNganggur' ],
	'Unusedimages'              => [ 'GambarNganggur' ],
	'Unusedtemplates'           => [ 'CitakanNganggur' ],
	'Unwatchedpages'            => [ 'KacaTeuDiawaskeun' ],
	'Upload'                    => [ 'Kunjal' ],
	'Userlogin'                 => [ 'AsupLog' ],
	'Userlogout'                => [ 'KaluarLog' ],
	'Userrights'                => [ 'HakPamake' ],
	'Version'                   => [ 'Versi' ],
	'Wantedcategories'          => [ 'KategoriDiteang' ],
	'Wantedpages'               => [ 'KacaDiteang', 'TumbuPegat' ],
	'Watchlist'                 => [ 'Awaskeuneun' ],
	'Whatlinkshere'             => [ 'NumbuKaDieu' ],
	'Withoutinterwiki'          => [ 'TanpaInterwiki' ],
];
