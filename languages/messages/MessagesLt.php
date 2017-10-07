<?php
/** Lithuanian (lietuvių)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Audriusa
 * @author Auwris
 * @author Break Through Pain
 * @author Dark Eagle
 * @author Eitvys200
 * @author Garas
 * @author Geitost
 * @author Homo
 * @author Hugo.arg
 * @author Ignas693
 * @author Kaganer
 * @author Mantak111
 * @author Matasg
 * @author Meno25
 * @author Ola
 * @author Pdxx
 * @author Perkunas
 * @author Pėstininkas
 * @author Reedy
 * @author Siggis
 * @author Tomasdd
 * @author Urhixidur
 * @author Vilius2001
 * @author Vpovilaitis
 * @author Xabier Armendaritz
 * @author לערי ריינהארט
 */

$namespaceNames = [
	NS_MEDIA            => 'Medija',
	NS_SPECIAL          => 'Specialus',
	NS_TALK             => 'Aptarimas',
	NS_USER             => 'Naudotojas',
	NS_USER_TALK        => 'Naudotojo_aptarimas',
	NS_PROJECT_TALK     => '$1_aptarimas',
	NS_FILE             => 'Vaizdas',
	NS_FILE_TALK        => 'Vaizdo_aptarimas',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_aptarimas',
	NS_TEMPLATE         => 'Šablonas',
	NS_TEMPLATE_TALK    => 'Šablono_aptarimas',
	NS_HELP             => 'Pagalba',
	NS_HELP_TALK        => 'Pagalbos_aptarimas',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Kategorijos_aptarimas',
];

$namespaceGenderAliases = [
	NS_USER      => [ 'male' => 'Naudotojas', 'female' => 'Naudotoja' ],
	NS_USER_TALK => [ 'male' => 'Naudotojo_aptarimas', 'female' => 'Naudotojos_aptarimas' ],
];

$specialPageAliases = [
	'Allmessages'               => [ 'Visi_pranešimai' ],
	'Allpages'                  => [ 'Visi_puslapiai' ],
	'Ancientpages'              => [ 'Seniausi_puslapiai' ],
	'Blankpage'                 => [ 'Tuščias_puslapis' ],
	'Block'                     => [ 'Blokuoti_IP' ],
	'Booksources'               => [ 'Knygų_šaltiniai' ],
	'BrokenRedirects'           => [ 'Peradresavimai_į_niekur' ],
	'Categories'                => [ 'Kategorijos' ],
	'ChangePassword'            => [ 'Slaptažodžio_atstatymas' ],
	'Confirmemail'              => [ 'Elektroninio_pašto_patvirtinimas' ],
	'Contributions'             => [ 'Indėlis' ],
	'CreateAccount'             => [ 'Sukurti_paskyrą' ],
	'Deadendpages'              => [ 'Puslapiai-aklavietės' ],
	'DeletedContributions'      => [ 'Ištrintas_indėlis' ],
	'DoubleRedirects'           => [ 'Dvigubi_peradesavimai' ],
	'Emailuser'                 => [ 'Rašyti_laišką' ],
	'Export'                    => [ 'Eksportas' ],
	'Fewestrevisions'           => [ 'Mažiausiai_keičiami' ],
	'FileDuplicateSearch'       => [ 'Failo_dublikatų_paieška' ],
	'Filepath'                  => [ 'Kelias_iki_failo' ],
	'Import'                    => [ 'Importas' ],
	'Invalidateemail'           => [ 'Nutraukti_elektroninio_pašto_galiojimą' ],
	'BlockList'                 => [ 'IP_blokavimų_sąrašas' ],
	'LinkSearch'                => [ 'Nuorodų_paieška' ],
	'Listadmins'                => [ 'Administratorių_sąrašas' ],
	'Listbots'                  => [ 'Botų_sąrašas' ],
	'Listfiles'                 => [ 'Paveikslėlių_sąrašas' ],
	'Listgrouprights'           => [ 'Grupių_teisių_sąrašas' ],
	'Listredirects'             => [ 'Peradresavimų_sąrašas' ],
	'Listusers'                 => [ 'Naudotojų_sąrašas' ],
	'Lockdb'                    => [ 'Užrakinti_duomenų_bazę' ],
	'Log'                       => [ 'Sąrašas', 'Sąrašai' ],
	'Lonelypages'               => [ 'Vieniši_puslapiai' ],
	'Longpages'                 => [ 'Ilgiausi_puslapiai' ],
	'MergeHistory'              => [ 'Sujungti_istoriją' ],
	'MIMEsearch'                => [ 'MIME_paieška' ],
	'Mostcategories'            => [ 'Daugiausiai_naudojamos_kategorijos' ],
	'Mostimages'                => [ 'Daugiausiai_naudojami_paveikslėliai' ],
	'Mostlinked'                => [ 'Turintys_daugiausiai_nuorodų' ],
	'Mostlinkedcategories'      => [ 'Kategorijos_turinčios_daugiausiai_nuorodų' ],
	'Mostlinkedtemplates'       => [ 'Šablonai' ],
	'Mostrevisions'             => [ 'Daugiausiai_keičiami' ],
	'Movepage'                  => [ 'Puslapio_pervadinimas' ],
	'Mycontributions'           => [ 'Mano_indėlis' ],
	'Mypage'                    => [ 'Mano_puslapis' ],
	'Mytalk'                    => [ 'Mano_aptarimas' ],
	'Newimages'                 => [ 'Nauji_paveikslėliai' ],
	'Newpages'                  => [ 'Naujausi_puslapiai' ],
	'Preferences'               => [ 'Nustatymai' ],
	'Prefixindex'               => [ 'Prasidedantys' ],
	'Protectedpages'            => [ 'Užrakinti_puslapiai' ],
	'Protectedtitles'           => [ 'Apsaugoti_pavadinimai' ],
	'Randompage'                => [ 'Atsitiktinis_puslapis' ],
	'Randomredirect'            => [ 'Atsitiktinis_peradresavimas' ],
	'Recentchanges'             => [ 'Naujausi_keitimai' ],
	'Recentchangeslinked'       => [ 'Pakeitimai_susijusiuose_puslapiuose' ],
	'Revisiondelete'            => [ 'Redagavimo_ištrynimas' ],
	'Search'                    => [ 'Paieška' ],
	'Shortpages'                => [ 'Trumpiausi_puslapiai' ],
	'Specialpages'              => [ 'Specialieji_puslapiai' ],
	'Statistics'                => [ 'Statistika' ],
	'Tags'                      => [ 'Žymos' ],
	'Uncategorizedcategories'   => [ 'Kategorijos_be_subkategorijų' ],
	'Uncategorizedimages'       => [ 'Paveikslėliai_be_kategorijų' ],
	'Uncategorizedpages'        => [ 'Puslapiai_be_kategorijų' ],
	'Uncategorizedtemplates'    => [ 'Šablonai_be_kategorijų' ],
	'Undelete'                  => [ 'Netrinti' ],
	'Unlockdb'                  => [ 'Atrakinti_duomenų_bazę' ],
	'Unusedcategories'          => [ 'Nenaudojamos_kategorijos' ],
	'Unusedimages'              => [ 'Nenaudojami_paveikslėliai' ],
	'Unusedtemplates'           => [ 'Nenaudojami_šablonai' ],
	'Unwatchedpages'            => [ 'Nestebimi_puslapiai' ],
	'Upload'                    => [ 'Įkėlimas' ],
	'Userlogin'                 => [ 'Prisijungimas' ],
	'Userlogout'                => [ 'Atsijungimas' ],
	'Userrights'                => [ 'Naudotojo_teisės' ],
	'Version'                   => [ 'Versija' ],
	'Wantedcategories'          => [ 'Trokštamiausios_kategorijos' ],
	'Wantedfiles'               => [ 'Trokštami_failai' ],
	'Wantedpages'               => [ 'Trokštamiausi_puslapiai', 'Blogos_nuorodos' ],
	'Wantedtemplates'           => [ 'Trokštami_šablonai' ],
	'Watchlist'                 => [ 'Stebimieji' ],
	'Whatlinkshere'             => [ 'Kas_į_čia_rodo' ],
	'Withoutinterwiki'          => [ 'Be_interwiki' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#PERADRESAVIMAS', '#REDIRECT' ],
	'notoc'                     => [ '0', '__BETURIN__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__BEGALERIJOS__', '__NOGALLERY__' ],
	'toc'                       => [ '0', '__TURINYS__', '__TOC__' ],
	'noeditsection'             => [ '0', '__BEREDAGSEKC__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'DABARTINISMĖNESIS', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'DABARTINIOMĖNESIOPAVADINIMAS', 'CURRENTMONTHNAME' ],
	'currentday'                => [ '1', 'DABARTINĖDIENA', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'DABARTINĖDIENA2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'DABARTINĖSDIENOSPAVADINIMAS', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'DABARTINIAIMETAI', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'DABARTINISLAIKAS', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'DABARTINĖVALANDA', 'CURRENTHOUR' ],
	'numberofpages'             => [ '1', 'PUSLAPIŲSKAIČIUS', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'STRAIPSNIŲSKAIČIUS', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'FAILŲSKAIČIUS', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'NAUDOTOJŲSKAIČIUS', 'NUMBEROFUSERS' ],
	'numberofedits'             => [ '1', 'KEITIMŲSKAIČIUS', 'NUMBEROFEDITS' ],
	'img_thumbnail'             => [ '1', 'miniatiūra', 'mini', 'thumb', 'thumbnail' ],
	'img_manualthumb'           => [ '1', 'miniatiūra=$1', 'mini=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'dešinėje', 'right' ],
	'img_left'                  => [ '1', 'kairėje', 'left' ],
];

$fallback8bitEncoding = 'windows-1257';
$separatorTransformTable = [ ',' => "\u{00A0}", '.' => ',' ];

$dateFormats = [
	'ymd time' => 'H:i',
	'ymd date' => 'Y "m." F j "d."',
	'ymd both' => 'Y "m." F j "d.", H:i',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
];

$linkTrail = '/^([a-ząčęėįšųūž]+)(.*)$/sDu';
