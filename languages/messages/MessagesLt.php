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

$namespaceNames = array(
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
);

$namespaceGenderAliases = array(
	NS_USER      => array( 'male' => 'Naudotojas', 'female' => 'Naudotoja' ),
	NS_USER_TALK => array( 'male' => 'Naudotojo_aptarimas', 'female' => 'Naudotojos_aptarimas' ),
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Visi_pranešimai' ),
	'Allpages'                  => array( 'Visi_puslapiai' ),
	'Ancientpages'              => array( 'Seniausi_puslapiai' ),
	'Blankpage'                 => array( 'Tuščias_puslapis' ),
	'Block'                     => array( 'Blokuoti_IP' ),
	'Booksources'               => array( 'Knygų_šaltiniai' ),
	'BrokenRedirects'           => array( 'Peradresavimai_į_niekur' ),
	'Categories'                => array( 'Kategorijos' ),
	'ChangePassword'            => array( 'Slaptažodžio_atstatymas' ),
	'Confirmemail'              => array( 'Elektroninio_pašto_patvirtinimas' ),
	'Contributions'             => array( 'Indėlis' ),
	'CreateAccount'             => array( 'Sukurti_paskyrą' ),
	'Deadendpages'              => array( 'Puslapiai-aklavietės' ),
	'DeletedContributions'      => array( 'Ištrintas_indėlis' ),
	'DoubleRedirects'           => array( 'Dvigubi_peradesavimai' ),
	'Emailuser'                 => array( 'Rašyti_laišką' ),
	'Export'                    => array( 'Eksportas' ),
	'Fewestrevisions'           => array( 'Mažiausiai_keičiami' ),
	'FileDuplicateSearch'       => array( 'Failo_dublikatų_paieška' ),
	'Filepath'                  => array( 'Kelias_iki_failo' ),
	'Import'                    => array( 'Importas' ),
	'Invalidateemail'           => array( 'Nutraukti_elektroninio_pašto_galiojimą' ),
	'BlockList'                 => array( 'IP_blokavimų_sąrašas' ),
	'LinkSearch'                => array( 'Nuorodų_paieška' ),
	'Listadmins'                => array( 'Administratorių_sąrašas' ),
	'Listbots'                  => array( 'Botų_sąrašas' ),
	'Listfiles'                 => array( 'Paveikslėlių_sąrašas' ),
	'Listgrouprights'           => array( 'Grupių_teisių_sąrašas' ),
	'Listredirects'             => array( 'Peradresavimų_sąrašas' ),
	'Listusers'                 => array( 'Naudotojų_sąrašas' ),
	'Lockdb'                    => array( 'Užrakinti_duomenų_bazę' ),
	'Log'                       => array( 'Sąrašas', 'Sąrašai' ),
	'Lonelypages'               => array( 'Vieniši_puslapiai' ),
	'Longpages'                 => array( 'Ilgiausi_puslapiai' ),
	'MergeHistory'              => array( 'Sujungti_istoriją' ),
	'MIMEsearch'                => array( 'MIME_paieška' ),
	'Mostcategories'            => array( 'Daugiausiai_naudojamos_kategorijos' ),
	'Mostimages'                => array( 'Daugiausiai_naudojami_paveikslėliai' ),
	'Mostlinked'                => array( 'Turintys_daugiausiai_nuorodų' ),
	'Mostlinkedcategories'      => array( 'Kategorijos_turinčios_daugiausiai_nuorodų' ),
	'Mostlinkedtemplates'       => array( 'Šablonai' ),
	'Mostrevisions'             => array( 'Daugiausiai_keičiami' ),
	'Movepage'                  => array( 'Puslapio_pervadinimas' ),
	'Mycontributions'           => array( 'Mano_indėlis' ),
	'Mypage'                    => array( 'Mano_puslapis' ),
	'Mytalk'                    => array( 'Mano_aptarimas' ),
	'Newimages'                 => array( 'Nauji_paveikslėliai' ),
	'Newpages'                  => array( 'Naujausi_puslapiai' ),

	'Preferences'               => array( 'Nustatymai' ),
	'Prefixindex'               => array( 'Prasidedantys' ),
	'Protectedpages'            => array( 'Užrakinti_puslapiai' ),
	'Protectedtitles'           => array( 'Apsaugoti_pavadinimai' ),
	'Randompage'                => array( 'Atsitiktinis_puslapis' ),
	'Randomredirect'            => array( 'Atsitiktinis_peradresavimas' ),
	'Recentchanges'             => array( 'Naujausi_keitimai' ),
	'Recentchangeslinked'       => array( 'Pakeitimai_susijusiuose_puslapiuose' ),
	'Revisiondelete'            => array( 'Redagavimo_ištrynimas' ),
	'Search'                    => array( 'Paieška' ),
	'Shortpages'                => array( 'Trumpiausi_puslapiai' ),
	'Specialpages'              => array( 'Specialieji_puslapiai' ),
	'Statistics'                => array( 'Statistika' ),
	'Tags'                      => array( 'Žymos' ),
	'Uncategorizedcategories'   => array( 'Kategorijos_be_subkategorijų' ),
	'Uncategorizedimages'       => array( 'Paveikslėliai_be_kategorijų' ),
	'Uncategorizedpages'        => array( 'Puslapiai_be_kategorijų' ),
	'Uncategorizedtemplates'    => array( 'Šablonai_be_kategorijų' ),
	'Undelete'                  => array( 'Netrinti' ),
	'Unlockdb'                  => array( 'Atrakinti_duomenų_bazę' ),
	'Unusedcategories'          => array( 'Nenaudojamos_kategorijos' ),
	'Unusedimages'              => array( 'Nenaudojami_paveikslėliai' ),
	'Unusedtemplates'           => array( 'Nenaudojami_šablonai' ),
	'Unwatchedpages'            => array( 'Nestebimi_puslapiai' ),
	'Upload'                    => array( 'Įkėlimas' ),
	'Userlogin'                 => array( 'Prisijungimas' ),
	'Userlogout'                => array( 'Atsijungimas' ),
	'Userrights'                => array( 'Naudotojo_teisės' ),
	'Version'                   => array( 'Versija' ),
	'Wantedcategories'          => array( 'Trokštamiausios_kategorijos' ),
	'Wantedfiles'               => array( 'Trokštami_failai' ),
	'Wantedpages'               => array( 'Trokštamiausi_puslapiai', 'Blogos_nuorodos' ),
	'Wantedtemplates'           => array( 'Trokštami_šablonai' ),
	'Watchlist'                 => array( 'Stebimieji' ),
	'Whatlinkshere'             => array( 'Kas_į_čia_rodo' ),
	'Withoutinterwiki'          => array( 'Be_interwiki' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#PERADRESAVIMAS', '#REDIRECT' ),
	'notoc'                     => array( '0', '__BETURIN__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__BEGALERIJOS__', '__NOGALLERY__' ),
	'toc'                       => array( '0', '__TURINYS__', '__TOC__' ),
	'noeditsection'             => array( '0', '__BEREDAGSEKC__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'DABARTINISMĖNESIS', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'DABARTINIOMĖNESIOPAVADINIMAS', 'CURRENTMONTHNAME' ),
	'currentday'                => array( '1', 'DABARTINĖDIENA', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'DABARTINĖDIENA2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'DABARTINĖSDIENOSPAVADINIMAS', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'DABARTINIAIMETAI', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'DABARTINISLAIKAS', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'DABARTINĖVALANDA', 'CURRENTHOUR' ),
	'numberofpages'             => array( '1', 'PUSLAPIŲSKAIČIUS', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'STRAIPSNIŲSKAIČIUS', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'FAILŲSKAIČIUS', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'NAUDOTOJŲSKAIČIUS', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'KEITIMŲSKAIČIUS', 'NUMBEROFEDITS' ),
	'img_thumbnail'             => array( '1', 'miniatiūra', 'mini', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'miniatiūra=$1', 'mini=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'dešinėje', 'right' ),
	'img_left'                  => array( '1', 'kairėje', 'left' ),
);

$fallback8bitEncoding = 'windows-1257';
$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$dateFormats = array(
	'ymd time' => 'H:i',
	'ymd date' => 'Y "m." F j "d."',
	'ymd both' => 'Y "m." F j "d.", H:i',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

