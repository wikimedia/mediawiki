<?php
/** Zazaki (Zazaki)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Asmen
 * @author Aspar
 * @author Belekvor
 * @author Erdemaslancan
 * @author George Animal
 * @author Kaganer
 * @author Mirzali
 * @author Olvörg
 * @author Reedy
 * @author Sahim
 * @author Xoser
 */

$namespaceNames = array(
	NS_MEDIA            => 'Medya',
	NS_SPECIAL          => 'Xısusi',
	NS_TALK             => 'Werênayış',
	NS_USER             => 'Karber',
	NS_USER_TALK        => 'Karber_werênayış',
	NS_PROJECT_TALK     => '$1_werênayış',
	NS_FILE             => 'Dosya',
	NS_FILE_TALK        => 'Dosya_werênayış',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_werênayış',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_werênayış',
	NS_HELP             => 'Peşti',
	NS_HELP_TALK        => 'Peşti_werênayış',
	NS_CATEGORY         => 'Kategoriye',
	NS_CATEGORY_TALK    => 'Kategoriye_werênayış',
);

$namespaceAliases = array(
	'Karber_mesac'       => NS_USER_TALK,
	'Desteg'             => NS_HELP,
	'Desteg_werênayış'   => NS_HELP_TALK,
	'Kategori'           => NS_CATEGORY,
	'Kategori_werênayış' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'KarberêAktivi', 'AktivKarberi' ),
	'Allmessages'               => array( 'MesaciPêro' ),
	'Allpages'                  => array( 'PeleyPêro' ),
	'Ancientpages'              => array( 'PeleyVerêni' ),
	'Badtitle'                  => array( 'SernameyoXırab' ),
	'Blankpage'                 => array( 'PelaVeng', 'VengPela' ),
	'Block'                     => array( 'Bloke', 'BlokeIP', 'BlokeKarber' ),
	'Blockme'                   => array( 'BlokêMe' ),
	'Booksources'               => array( 'KıtabeÇıme' ),
	'BrokenRedirects'           => array( 'HetênayışoXırab' ),
	'Categories'                => array( 'Kategoriye' ),
	'ChangeEmail'               => array( 'EpostaBıvurnê' ),
	'ChangePassword'            => array( 'ParolaBıvurnê', 'ParolaResetke' ),
	'ComparePages'              => array( 'PelaPêverke' ),
	'Confirmemail'              => array( 'EpostayAraştke' ),
	'Contributions'             => array( 'İştiraxi' ),
	'CreateAccount'             => array( 'HesabVırazê' ),
	'Deadendpages'              => array( 'PelaBêgıre' ),
	'DeletedContributions'      => array( 'İştıraxêkeBesterneyayê' ),
	'Disambiguations'           => array( 'ManeoBin' ),
	'DoubleRedirects'           => array( 'DıletHeteneayış' ),
	'EditWatchlist'             => array( 'ListeyaSeyriVurnayış' ),
	'Emailuser'                 => array( 'EpostayaKarberi' ),
	'Export'                    => array( 'Ateberde' ),
	'Fewestrevisions'           => array( 'TewrtaynRevizyon' ),
	'FileDuplicateSearch'       => array( 'KopyaydosyaCıgeyrayış', 'DıletdosyaCıgeyrayış' ),
	'Filepath'                  => array( 'RayaDosya', 'HerunaDosya', 'CayêDosya' ),
	'Import'                    => array( 'Azeredê', 'Atewrke' ),
	'Invalidateemail'           => array( 'TesdiqêepostaBıterknê' ),
	'BlockList'                 => array( 'ListeyêBLoki', 'IPBloki', 'Blokeyê_IP' ),
	'LinkSearch'                => array( 'GreCıgeyrayış' ),
	'Listadmins'                => array( 'ListeyêXizmetkaran' ),
	'Listbots'                  => array( 'ListeyêBotan' ),
	'Listfiles'                 => array( 'ListeyêDosyayan', 'DosyayaListeke', 'ListeyêResiman' ),
	'Listgrouprights'           => array( 'ListeyêHeqêGruban', 'HeqêGrubdeKarberan' ),
	'Listredirects'             => array( 'ListeyêHetanayışi' ),
	'Listusers'                 => array( 'ListeyêKarberan', 'KarberaListeke' ),
	'Lockdb'                    => array( 'DBKilitke' ),
	'Log'                       => array( 'Qeyd', 'Qeydi' ),
	'Lonelypages'               => array( 'PeleyêBêkesi' ),
	'Longpages'                 => array( 'PeleyeDergi' ),
	'MergeHistory'              => array( 'RavêrdaPêtewrke' ),
	'MIMEsearch'                => array( 'NIMECıgeyrayış' ),
	'Mostcategories'            => array( 'TewrvêşiKategoriyıni' ),
	'Mostimages'                => array( 'DosyeyêkeCırêvêşiGreDeyayo' ),
	'Mostlinked'                => array( 'PeleyêkeCırêvêşiGreDeyayo' ),
	'Mostlinkedcategories'      => array( 'KategoriyêkeCırêvêşiGreDeyayo' ),
	'Mostlinkedtemplates'       => array( 'ŞablonêkeCırêvêşiGreDeyayo' ),
	'Mostrevisions'             => array( 'TewrvêşiRevizyon' ),
	'Movepage'                  => array( 'PelaAhuln' ),
	'Mycontributions'           => array( 'İştırakeMe' ),
	'Mypage'                    => array( 'PelaMe' ),
	'Mytalk'                    => array( 'PersiyeME' ),
	'Myuploads'                 => array( 'BarkerdışeMe' ),
	'Newimages'                 => array( 'DosyeyêNewey', 'ResimêNewey' ),
	'Newpages'                  => array( 'PeleyeNewey' ),
	'PasswordReset'             => array( 'ParolaReset' ),
	'PermanentLink'             => array( 'DaimiGre' ),
	'Popularpages'              => array( 'PeleyêPopuleri' ),
	'Preferences'               => array( 'Tercihi' ),
	'Prefixindex'               => array( 'SerVerole' ),
	'Protectedpages'            => array( 'PeleyêkeStaryayê' ),
	'Protectedtitles'           => array( 'SernameyêkeStaryayê' ),
	'Randompage'                => array( 'Raştamê', 'PelayakeRaştamê' ),
	'Randomredirect'            => array( 'HetenayışoRaştame' ),
	'Recentchanges'             => array( 'VurnayışêPeyêni' ),
	'Recentchangeslinked'       => array( 'GreyêVurnayışêPeyêni' ),
	'Revisiondelete'            => array( 'RevizyoniBesterne' ),
	'RevisionMove'              => array( 'RewizyoniAhulne' ),
	'Search'                    => array( 'Cıgeyre' ),
	'Shortpages'                => array( 'PeleyêKılmi' ),
	'Specialpages'              => array( 'PeleyXısusi' ),
	'Statistics'                => array( 'İstatistiki' ),
	'Tags'                      => array( 'Etiketi' ),
	'Unblock'                   => array( 'Bloqiwedarne' ),
	'Uncategorizedcategories'   => array( 'KategoriyêkeKategorinêbiyê' ),
	'Uncategorizedimages'       => array( 'DosyeyêkeKategorinêbiyê' ),
	'Uncategorizedpages'        => array( 'PeleyêkeKategorinêbiyê' ),
	'Uncategorizedtemplates'    => array( 'ŞablonêkeKategorinêbiyê' ),
	'Undelete'                  => array( 'Peyserbiya' ),
	'Unlockdb'                  => array( 'DBSırmiake' ),
	'Unusedcategories'          => array( 'KategoriyêkeNêkaryayê' ),
	'Unusedimages'              => array( 'DosyeyêkeNêkaryayê' ),
	'Unusedtemplates'           => array( 'ŞablonêkeNêkaryayê' ),
	'Unwatchedpages'            => array( 'PeleyêkeNêweyneyênê' ),
	'Upload'                    => array( 'Barke' ),
	'UploadStash'               => array( 'BarkerdışêNımtey' ),
	'Userlogin'                 => array( 'KarberDekewtış' ),
	'Userlogout'                => array( 'KarberVıcyayış' ),
	'Userrights'                => array( 'HeqêKarberan', 'SysopKerdış', 'BotKerdış' ),
	'Version'                   => array( 'Versiyon' ),
	'Wantedcategories'          => array( 'KategoriyêkeWazênê' ),
	'Wantedfiles'               => array( 'DosyeyêkeWazênê' ),
	'Wantedpages'               => array( 'PeleyêkeWazênê' ),
	'Wantedtemplates'           => array( 'ŞablonêkeWazênê' ),
	'Watchlist'                 => array( 'Listeyseyri' ),
	'Whatlinkshere'             => array( 'PelarêGre' ),
	'Withoutinterwiki'          => array( 'Bêİnterwiki' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#HETENAYIŞ', '#REDIRECT' ),
	'notoc'                     => array( '0', '__ESTENÇINO__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__GALERİÇINO__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__ESTENZARURET__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__ESTEN__', '__TOC__' ),
	'noeditsection'             => array( '0', '__TİMARKERDIŞÇINO__', '__NOEDITSECTION__' ),
	'noheader'                  => array( '0', '__SERNAMEÇINO__', '__NOHEADER__' ),
	'currentmonth'              => array( '1', 'AŞMİYANEWKİ', 'MEWCUDAŞMİ2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'AŞMİYANEWKİ1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'NAMEYAŞMDANEWKİ', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'AŞMACIYANEWKİ', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'AŞMİYANEWKİKILMKERDIŞ', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'ROCENEWKİ', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'ROCENEWKİ2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'NAMEYÊROCENEWKİ', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'SERRENEWKİ', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'DEMENEWKİ', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'SEHATNEWKİ', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'WAREYAŞMİ', 'WAREYAŞMİ2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'WAREYAŞMİ1', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'NAMEYÊWAREYAŞMİ', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'NAMEYWAREDÊAŞMİDACI', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'WAREYAŞMİKILMKERDIŞ', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'WAREYROCE', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'WAREYROCE2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'NAMEYÊWAREYROCE', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'WAREYSERRE', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'WAREYDEME', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'WAREYSEHAT', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'AMARİYAPELAN', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'AMARİYAWESİQAN', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'AMARİYADOSYAYAN', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'AMARİYAKARBERAN', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'AMARİYAAKTİVKARBERAN', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'AMARİYAVURNAYIŞAN', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'AMARİYAMOCNAYIŞAN', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'NAMEYPELA', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'NAMEYPELAA', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'CANAME', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'CANAMEE', 'NAMESPACEE' ),
	'namespacenumber'           => array( '1', 'AMARİYACANAME', 'NAMESPACENUMBER' ),
	'talkspace'                 => array( '1', 'CAYÊWERÊNAYIŞİ', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'CAYÊWERÊNAYIŞAN', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'CAYÊMESEL', 'CAYÊWESİQE', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'CAYÊMESELAN', 'CAYÊWESİQAN', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'NAMEYPELAPÊRO', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'NAMEYPELAPÊRON', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'NAMEYBINPELA', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'NAMEYBINPELAA', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'NAMEYSERPELA', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'NAMEYSERPELAA', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'NAMEYPELAWERÊNAYIŞ', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'NAMEYPELAWERÊNAYIŞAN', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'NAMEYPELAMESEL', 'NAMEYPELAWESİQE', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'NAMEYPELAMESELER', 'NAMEYPELAQESİQER', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'MSC', 'MSG:' ),
	'subst'                     => array( '0', 'KOPYAKE', 'ATEBERDE', 'SUBST:' ),
	'safesubst'                 => array( '0', 'EMELEYATEBERDE', 'SAFESUBST:' ),
	'msgnw'                     => array( '0', 'MSJNW:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'resmoqıckek', 'qıckek', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'resmoqıckek=$1', 'qıckek=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'raşt', 'right' ),
	'img_left'                  => array( '1', 'çep', 'left' ),
	'img_none'                  => array( '1', 'çıniyo', 'none' ),
	'img_width'                 => array( '1', '$1pik', '$1piksel', '$1px' ),
	'img_center'                => array( '1', 'werte', 'miyan', 'center', 'centre' ),
	'img_framed'                => array( '1', 'çerçeweya', 'çerçeweniyo', 'çerçewe', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'bêçerçewe', 'frameless' ),
	'img_page'                  => array( '1', 'pela=$1', 'pela_$1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'disleg', 'disleg=$1', 'disleg_$1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'sinor', 'border' ),
	'img_baseline'              => array( '1', 'Sinorêerdi', 'baseline' ),
	'img_sub'                   => array( '1', 'bın', 'sub' ),
	'img_super'                 => array( '1', 'corên', 'cor', 'super', 'sup' ),
	'img_top'                   => array( '1', 'gedug', 'top' ),
	'img_text_top'              => array( '1', 'gedug-metin', 'text-top' ),
	'img_middle'                => array( '1', 'merkez', 'middle' ),
	'img_bottom'                => array( '1', 'erd', 'bottom' ),
	'img_text_bottom'           => array( '1', 'erd-metin', 'text-bottom' ),
	'img_link'                  => array( '1', 'gre=$1', 'link=$1' ),
	'int'                       => array( '0', 'İNT:', 'INT:' ),
	'sitename'                  => array( '1', 'NAMEYSİTA', 'SITENAME' ),
	'ns'                        => array( '0', 'CN', 'NS:' ),
	'nse'                       => array( '0', 'CNV', 'NSE:' ),
	'localurl'                  => array( '0', 'LOKALGRE', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'LOKALGREV', 'LOCALURLE:' ),
	'articlepath'               => array( '0', 'SOPAWESİQAN', 'ARTICLEPATH' ),
	'pageid'                    => array( '0', 'NIMREYPELA', 'PAGEID' ),
	'server'                    => array( '0', 'ARDEN', 'SERVER' ),
	'servername'                => array( '0', 'NAMEYARDEN', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'RAYASCRIPTİ', 'SCRIPTPATH' ),
	'stylepath'                 => array( '0', 'TERZÊTEWRİ', 'STYLEPATH' ),
	'grammar'                   => array( '0', 'GRAMER:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'CİNSİYET:', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__SERNAMEVURNAYIŞÇINO__', '__SVÇ__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__ZERREVURNAYIŞÇINO__', '__ZVÇ__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'MEVCUDHEFTE', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'MEVCUDWAREYHEFTİ', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'WAREYHEFTİ', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'WAREYROCAHEFTİ', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'NIMREYREVİZYONİ', 'REVISIONID' ),
	'revisionday'               => array( '1', 'ROCAREVİZYONİ', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'ROCAREVİZYON1', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'AŞMAREVİZYONİ', 'REVISIONMONTH' ),
	'revisionmonth1'            => array( '1', 'AŞMAREVİZYONİ1', 'REVISIONMONTH1' ),
	'revisionyear'              => array( '1', 'SERRAREVİZYONİ', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'MELUMATÊREVİZYONÊDEMİ', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', 'REVİZYONKARBER', 'REVISIONUSER' ),
	'plural'                    => array( '0', 'ZAFEN:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'GREPÊRO:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'GREYOPÊRON:', 'FULLURLE:' ),
	'canonicalurl'              => array( '0', 'GREYÊKANONİK:', 'CANONICALURL:' ),
	'canonicalurle'             => array( '0', 'GREYOKANONİK:', 'CANONICALURLE:' ),
	'lcfirst'                   => array( '0', 'KHİLK:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'BHİLK:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'KH:', 'LC:' ),
	'uc'                        => array( '0', 'BH:', 'UC:' ),
	'raw'                       => array( '0', 'XAM:', 'RAW:' ),
	'displaytitle'              => array( '1', 'SERNAMİBIMOCNE', 'DISPLAYTITLE' ),
	'newsectionlink'            => array( '1', '__GREYÊSERNAMEDÊNEWİ__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__GREYÊSERNAMEDÊNEWİÇINO__', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'VERSİYONÊNEWKİ', 'CURRENTVERSION' ),
	'currenttimestamp'          => array( '1', 'WAREYSEHATÊNEWKİ', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'MALUMATÊWAREYSEHAT', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'HETANIŞANKERDIŞ', 'HETNIŞAN', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#ZIWAN', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'ZIWANÊESTİN', 'ZIWESTEN', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'PELEYÊKECADÊNAMİDEYÊ', 'PELECN', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'AMARİYAXİZMETKARAN', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'BABETNAYIŞ', 'FORMATNUM' ),
	'padleft'                   => array( '0', 'ÇEPİPIRKE', 'PADLEFT' ),
	'padright'                  => array( '0', 'RAŞTİPIRKE', 'PADRIGHT' ),
	'special'                   => array( '0', 'xısusi', 'special' ),
	'speciale'                  => array( '0', 'xısusiye', 'speciale' ),
	'defaultsort'               => array( '1', 'RATNAYIŞOHESBNAYIŞ', 'SIRMEYRATNAYIŞOHESBNAYIŞ', 'KATEGORİYARATNAYIŞOHESBNAYIŞ', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'RAYADOSYA:', 'FILEPATH:' ),
	'tag'                       => array( '0', 'etiket', 'tag' ),
	'hiddencat'                 => array( '1', '__KATEGORİYANIMITİ__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'PELEYÊKEKATEGORİDEYÊ', 'KATDÊPELEY', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'EBATÊPELA', 'PAGESIZE' ),
	'index'                     => array( '1', '__SERSIQ__', '__INDEX__' ),
	'noindex'                   => array( '1', '__SERSIQÇINYO__', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'GRUBDEAMARE', 'AMARİYAGRUBER', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__STATİKHETENAYIŞ__', '__STATICHETENAYIŞ__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'SEWİYEYÊSTARE', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'demêformati', 'formatdate', 'dateformat' ),
	'url_path'                  => array( '0', 'RAY', 'PATH' ),
	'url_wiki'                  => array( '0', 'WİKİ', 'WIKI' ),
	'url_query'                 => array( '0', 'PERSİYE', 'QUERY' ),
	'defaultsort_noerror'       => array( '0', 'xırabinçıniya', 'noerror' ),
	'defaultsort_noreplace'     => array( '0', 'cewabçıniyo', 'noreplace' ),
	'pagesincategory_all'       => array( '0', 'pêro', 'all' ),
	'pagesincategory_pages'     => array( '0', 'peley', 'pages' ),
	'pagesincategory_files'     => array( '0', 'dosyey', 'files' ),
);

$messages = array(
# User preference toggles
'tog-underline' => 'Bınê gırey de xete bıance:',
'tog-justify' => 'Paragrafan eyar ke',
'tog-hideminor' => 'Vurnayışanê şenıkan pela vurnayışanê peyênan de bınımne',
'tog-hidepatrolled' => 'Vurnayışanê qontrolkerdeyan pela vurnayışê peyêni de bınımne',
'tog-newpageshidepatrolled' => 'Pelanê qontrolkerdeyan lista pelanê neweyan de bınımne',
'tog-extendwatchlist' => 'Lista seyrkerdışi hera bıke ke vurnayışi pêro bıasê, tenya tewr peyêni nê',
'tog-usenewrc' => 'Pele be vurnayışanê grube de vurnayışê peyêni u lista seyrkerdışi (JavaScript lazımo)',
'tog-numberheadings' => 'Sernuşteyan be xo numre cı şane',
'tog-showtoolbar' => 'Goceganê hacetanê vurnayışi bımocne (JavaScript lazımo)',
'tog-editondblclick' => 'Pê dı rey tıknayış pele sero bıxebetiye (JavaScript lazımo)',
'tog-editsection' => 'Vurnayışê qısımi be gıreyanê [bıvurne] ra feal ke',
'tog-editsectiononrightclick' => 'Qısıman be tıknayışê serrêze ra ebe gocega raşte bıvurne (JavaScript lazımo)',
'tog-showtoc' => 'Tabloyê tedeesteyan bımocne (de pelanê be hirê sernuşteyan ra vêşêri de)',
'tog-rememberpassword' => 'Parola mı nê cıgeyrayoği de bia xo viri (seba tewr zêde $1 {{PLURAL:$1|roce|rocan}}).',
'tog-watchcreations' => 'Pelê ke mı afernayê u dosyeyê ke mı bar kerdê lista mına seyrkerdışi ke',
'tog-watchdefault' => 'Pel u dosyeyê ke mı vurnayê lista mına seyrkerdışi ke',
'tog-watchmoves' => 'Pel u dosyeyê ke mı kırıştê lista mına seyrkerdışi ke',
'tog-watchdeletion' => 'Pel u dosyeyê ke mı esterıtê lista mına seyrkerdışi ke',
'tog-minordefault' => "Vurnayışanê xo pêrune ''vurnayışo qıckek'' nışan bıde",
'tog-previewontop' => 'Verqayti pela nuştışi ser de bımocne',
'tog-previewonfirst' => 'Vurnayışo verên de verqayti tım bımocne',
'tog-nocache' => 'Pelanê cıgeyrayoği meya xo viri',
'tog-enotifwatchlistpages' => 'Jû pele ya zi dosyaya ke lista mına seyrkerdışi de vurnayê mı rê e-poste bırışe',
'tog-enotifusertalkpages' => 'Pela mına werênayışi ke vurnayê mı rê e-poste bırışe',
'tog-enotifminoredits' => 'Vurnayışanê qıckekanê pelan u dosyeyan de zi mı rê e-poste bırışe',
'tog-enotifrevealaddr' => 'Adresa e-posteyê mı posteyê xeberan de bımocne',
'tog-shownumberswatching' => 'Amarê karberanê seyrkerdoğan bımocne',
'tog-oldsig' => 'İmzaya mewcude:',
'tog-fancysig' => 'İmza rê mameleyê wikimeqaley bıke (bê gıreyo otomatik)',
'tog-externaleditor' => 'Editorê teberi standard bıxebetne (tenya seba ekspertano, komputerê şıma de eyarê xısusiy lazımê. [//www.mediawiki.org/wiki/Manual:External_editors Melumato vêşêr.])',
'tog-externaldiff' => 'Têverşanayışan pê programê teberi vıraze (tenya seba ekspertano, komputerê şıma de eyarê xısusiy lazımê. [//www.mediawiki.org/wiki/Manual:External_editors Melumato vêşêr.])',
'tog-showjumplinks' => 'Gıreyê "şo"y aktif ke',
'tog-uselivepreview' => 'Verqayto cınde bıxebetne (JavaScript lazımo) (hewna cerrebnayış dero)',
'tog-forceeditsummary' => 'Mı ke xulasa kerde cı vira, hay be mı ser de',
'tog-watchlisthideown' => 'Vurnayışanê mı lista mına seyrkerdışi de bınımne',
'tog-watchlisthidebots' => 'Lista seyrkerdışi ra vurnayışanê boti bınımne',
'tog-watchlisthideminor' => 'Vurnayışanê qıckekan lista mına seyrkerdışi de bınımne',
'tog-watchlisthideliu' => 'Lista seyrkerdışi ra vurnayışanê karberanê cıkewteyan bınımne',
'tog-watchlisthideanons' => 'Lista seyrkerdışi ra vurnayışanê karberanê anoniman bınımne',
'tog-watchlisthidepatrolled' => 'Lista seyrkerdışi ra vurnayışanê qontrol kerdeyan bınımne',
'tog-ccmeonemails' => 'E-posteyanê ke ez karberanê binan rê rışenan, mı rê kopya inan bırışe',
'tog-diffonly' => 'Qıyasê versiyonan de tek ferqan bımocne, pela butıne nê',
'tog-showhiddencats' => 'Kategoriyanê dızdine bımocne',
'tog-noconvertlink' => 'Greyê sernami çerx kerdışi bıqefılne',
'tog-norollbackdiff' => 'Peyserardışi ra dıme ferqi caverde',

'underline-always' => 'Tım',
'underline-never' => 'Qet',
'underline-default' => 'Cild ya zi cıgeyrayoğo hesıbyaye',

# Font style option in Special:Preferences
'editfont-style' => 'Cayê vurnayışi de terzê nuştışi:',
'editfont-default' => 'Cıgeyrayoğo hesabiyaye',
'editfont-monospace' => 'Terzê nusteyê sabıtcagırewtoği',
'editfont-sansserif' => 'Babetê Sans-serifi',
'editfont-serif' => 'Babetê serifi',

# Dates
'sunday' => 'Kırê',
'monday' => 'Dışeme',
'tuesday' => 'Sêşeme',
'wednesday' => 'Çeharşeme',
'thursday' => 'Pancşeme',
'friday' => 'Êne',
'saturday' => 'Şeme',
'sun' => 'Krê',
'mon' => 'Dış',
'tue' => 'Sêş',
'wed' => 'Çrş',
'thu' => 'Pşm',
'fri' => 'Êne',
'sat' => 'Şem',
'january' => 'Çele',
'february' => 'Şıbate',
'march' => 'Adar',
'april' => 'Nisane',
'may_long' => 'Gulane',
'june' => 'Heziran',
'july' => 'Temuze',
'august' => 'Tebaxe',
'september' => 'Keşkelun',
'october' => 'Tışrino Verên',
'november' => 'Tışrino Peyên',
'december' => 'Kanun',
'january-gen' => 'Çele',
'february-gen' => 'Şıbate',
'march-gen' => 'Adar',
'april-gen' => 'Nisane',
'may-gen' => 'Gulane',
'june-gen' => 'Heziran',
'july-gen' => 'Temuze',
'august-gen' => 'Tebaxe',
'september-gen' => 'Keşkelun',
'october-gen' => 'Tışrino Verên',
'november-gen' => 'Tışrino Peyên',
'december-gen' => 'Kanun',
'jan' => 'Çel',
'feb' => 'Şbt',
'mar' => 'Adr',
'apr' => 'Nsn',
'may' => 'Gln',
'jun' => 'Hzr',
'jul' => 'Tmz',
'aug' => 'Tbx',
'sep' => 'Kşk',
'oct' => 'Tşv',
'nov' => 'Tşp',
'dec' => 'Kan',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Kategoriye|Kategoriy}}',
'category_header' => 'Pelê ke kategoriya "$1" derê',
'subcategories' => 'Kategoriyê bınêni',
'category-media-header' => 'Dosyeyê ke kategoriya da "$1" deyê',
'category-empty' => "''Ena kategoriye de hewna qet nuştey ya zi medya çıniyê.''",
'hidden-categories' => '{{PLURAL:$1|Kategoriya nımıtiye|Kategoriyê nımıtey}}',
'hidden-category-category' => 'Kategoriyê nımıtey',
'category-subcat-count' => '{{PLURAL:$2|Na kategoriye de ena kategoriya bınêne esta.|Na kategoriye de $2 ra pêro pia, {{PLURAL:$1|ena kategoriya bınêne esta|enê $1 kategoriyê bınêni estê.}}, be $2 ra pêro pia.}}',
'category-subcat-count-limited' => 'Na kategoriye de {{PLURAL:$1|ena kategoriya bınêne esta|enê $1 kategoriyê bınêni estê}}.',
'category-article-count' => '{{PLURAL:$2|Na kategoriye de teyna ena pele esta.|Na kategoriye de $2 ra pêro pia, {{PLURAL:$1|ena pele esta|enê $1 peli estê.}}, be $2 ra pêro pia}}',
'category-article-count-limited' => '{{PLURAL:$1|Pela cêrêne|$1 Pelê cêrêni}} na kategoriye derê.',
'category-file-count' => '<noinclude>{{PLURAL:$2|Na kategoriye tenya dosyayanê cêrênan muhtewa kena.}}</noinclude>
*Na kategoriye de $2 dosyayan ra {{PLURAL:$1|yew dosya tenêka esta| $1 dosyey asenê}}.',
'category-file-count-limited' => '{{PLURAL:$1|Dosya cêrêne|$1 Dosyê cêrêni}} na kategoriye derê.',
'listingcontinuesabbrev' => '(dewam)',
'index-category' => 'Pelê endeksıni',
'noindex-category' => 'Perê ke ratnena cı çinıya',
'broken-file-category' => 'Peleye ke linkê Dosyayandê xelata muhtewa kenê',
'categoryviewer-pagedlinks' => '($1) ($2)',

'linkprefix' => "'''MediaWiki niya ro.'''",

'about' => 'Heqa cı de',
'article' => 'Wesiqe',
'newwindow' => '(pençereyê newey de beno a)',
'cancel' => 'Bıtexelne',
'moredotdotdot' => 'Vêşi...',
'mypage' => 'Per',
'mytalk' => 'Werênayış',
'anontalk' => 'Pela werênayışê nê IPy',
'navigation' => 'Geyrayış',
'and' => '&#32;u',

# Cologne Blue skin
'qbfind' => 'Bıvêne',
'qbbrowse' => 'Rovete',
'qbedit' => 'Bıvurne',
'qbpageoptions' => 'Ena pele',
'qbpageinfo' => 'Gıre',
'qbmyoptions' => 'Pelê mı',
'qbspecialpages' => 'Pelê xısusiy',
'faq' => 'PZP (Persê ke zehf persiyenê)',
'faqpage' => 'Project: PZP',

# Vector skin
'vector-action-addsection' => 'Mesel Vırazê',
'vector-action-delete' => 'Bestere',
'vector-action-move' => 'Berê',
'vector-action-protect' => 'Bıpawe',
'vector-action-undelete' => 'Esterıtışi peyser bıgê',
'vector-action-unprotect' => 'Starkerdışi bıvurne',
'vector-simplesearch-preference' => 'Çuweya cı geyreyış de rehater aktiv ke (Tenya vector skin de)',
'vector-view-create' => 'Vıraze',
'vector-view-edit' => 'Bıvurne',
'vector-view-history' => 'Vurnayışê verêni',
'vector-view-view' => 'Bıwane',
'vector-view-viewsource' => 'Çımey bıvêne',
'actions' => 'Kerdışi',
'namespaces' => 'Cayê namam',
'variants' => 'Varyanti',

'errorpagetitle' => 'Xırab',
'returnto' => 'Peyser şo $1.',
'tagline' => '{{SITENAME}} ra',
'help' => 'Peşti',
'search' => 'Cı geyre',
'searchbutton' => 'Cı geyre',
'go' => 'Şo',
'searcharticle' => 'Şo',
'history' => 'Verora perer',
'history_short' => 'Vurnayışê verêni',
'updatedmarker' => 'cıkewtena mına peyêne ra dıme biyo rocane',
'printableversion' => 'Asayışê çapkerdışi',
'permalink' => 'Gıreyo jûqere',
'print' => 'Nusten ke',
'view' => 'Bıvin',
'edit' => 'Bıvurnên',
'create' => 'Vıraze',
'editthispage' => 'Ena pele bıvurne',
'create-this-page' => 'Na pele bınuse',
'delete' => 'Bestere',
'deletethispage' => 'Ena perer besternê',
'undelete_short' => '{{PLURAL:$1|Yew vurnayışi|$1 Vurnayışan}} mestere',
'viewdeleted_short' => '{{PLURAL:$1|Yew vurnayışo esterıte|$1 Vurnayışanê esterıtan}} bımocne',
'protect' => 'Bıpawe',
'protect_change' => 'bıvurne',
'protectthispage' => 'Ena pele bıpawe',
'unprotect' => 'Starkerdışi bıvurne',
'unprotectthispage' => 'Starkerdışe ena peler bıvurne',
'newpage' => 'Pera newiye',
'talkpage' => 'Ena pele sero werêne',
'talkpagelinktext' => 'Mesac',
'specialpage' => 'Pela xısusiye',
'personaltools' => 'Hacetê şexsiy',
'postcomment' => 'Qısımo newe',
'articlepage' => 'Pela zerreki bıvêne',
'talk' => 'Werênayış',
'views' => 'Asayışi',
'toolbox' => 'Haceti',
'userpage' => 'Pela karberi bıvêne',
'projectpage' => 'Pela procey bıvêne',
'imagepage' => 'Pela dosya bımocne',
'mediawikipage' => 'Pela mesaci bımocne',
'templatepage' => 'Pela şabloni bımocne',
'viewhelppage' => 'Pela peşti bıvêne',
'categorypage' => 'Pela kategoriye bıvêne',
'viewtalkpage' => 'Werênayışi bıvêne',
'otherlanguages' => 'Zıwananê binan de',
'redirectedfrom' => '(Pele da $1 ra heteneyê)',
'redirectpagesub' => 'Pela berdışi',
'lastmodifiedat' => 'Ena pele tewr peyên roca $2, $1 de biya rocaniye.',
'viewcount' => 'Ena pele {{PLURAL:$1|rae|$1 rey}} vêniya.',
'protectedpage' => 'Pela pawıtiye',
'jumpto' => 'Şo:',
'jumptonavigation' => 'karfiyê',
'jumptosearch' => 'cı geyre',
'view-pool-error' => 'Qaytê qısuri mekerên, serverê ma enıka zêde bar gırewto xo ser.
Hedê xo ra zêde karberi kenê ke seyrê na pele bıkerê.
Şıma rê zehmet, tenê vınderên, heta ke reyna kenê ke ena pele kewê.

$1',
'pool-timeout' => 'Kılitbiyayışi sero wextê vınetışi',
'pool-queuefull' => 'Rêza hewze pırra',
'pool-errorunknown' => 'Xeta nêzanıtiye',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Heqa {{SITENAME}}i de',
'aboutpage' => 'Project:Heqdê cı',
'copyright' => 'Zerrek bınê $1 dero.',
'copyrightpage' => '{{ns:project}}:Heqa telifi',
'currentevents' => 'Veng u vac',
'currentevents-url' => 'Project:Veng u vac',
'disclaimers' => 'Redê mesuliyeti',
'disclaimerpage' => 'Project:Reddê mesuliyetê bıngey',
'edithelp' => 'Peştdariya vurnayışi',
'edithelppage' => 'Help:Vurnayış',
'helppage' => 'Help:Estêni',
'mainpage' => 'Pera Seri',
'mainpage-description' => 'Pela Seri',
'policy-url' => 'Project:Terzê hereketi',
'portal' => 'Portalê cemaeti',
'portal-url' => 'Project:Portalê cemaeti',
'privacy' => 'Madeyê dızdiye',
'privacypage' => 'Project:Xısusiyetê nımtışi',

'badaccess' => 'Xeta mısadey',
'badaccess-group0' => 'Heqa şıma çıniya, karo ke şıma waşt, bıkerê.',
'badaccess-groups' => 'No fealiyeto ke şıma waşt, tenya karberanê {{PLURAL:$2|grubi|gruban ra yewi}} rê akerdeyo: $1.',

'versionrequired' => 'No $1 MediaWiki lazımo',
'versionrequiredtext' => 'Seba gurenayışê na pele versiyonê MediaWiki $1 lazımo. 
[[Special:Version|Versiyonê pele]] bıvêne.',

'ok' => 'Temam',
'pagetitle' => '"$1" adres ra gerya.',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'backlinksubtitle' => '← $1',
'retrievedfrom' => '"$1" ra ard',
'youhavenewmessages' => 'To rê $1 esto ($2).',
'newmessageslink' => 'mesacê şıma',
'newmessagesdifflink' => 'vurnayışo peyên',
'youhavenewmessagesfromusers' => 'Zey $1 ra {{PLURAL:$3|zewbi karber|$3 karberi}} ($2) esto.',
'youhavenewmessagesmanyusers' => '$1 ra tay karberi ($2) dı estê.',
'newmessageslinkplural' => '{{PLURAL:$1|yew mesac|mesacê newey}}',
'newmessagesdifflinkplural' => 'peyni {{PLURAL:$1|vurnayış|vurnayışi}}',
'youhavenewmessagesmulti' => '$1 mesaco newe esto',
'editsection' => 'bıvurne',
'editsection-brackets' => '[$1]',
'editold' => 'bıvurne',
'viewsourceold' => 'çımey cı bıvinê',
'editlink' => 'bıvurne',
'viewsourcelink' => 'çımey bıvêne',
'editsectionhint' => 'Leteyo ke bıvuriyo: $1',
'toc' => 'Sernameyê meselan',
'showtoc' => 'bımocne',
'hidetoc' => 'bınımne',
'collapsible-collapse' => 'Kılm ke',
'collapsible-expand' => 'Hera ke',
'thisisdeleted' => 'Bıvêne ya zi $1 peyser bia?',
'viewdeleted' => '$1 bıvêne?',
'restorelink' => '{{PLURAL:$1|yew vurnayışo esterıte|$1 vurnayışê esterıtey}}',
'feedlinks' => 'Warikerdış:',
'feed-invalid' => 'Qeydey cıresnayışê  beğşi nêvêreno.',
'feed-unavailable' => 'Cıresnayışê şebekey çıniyê',
'site-rss-feed' => '$1 Cıresnayışê RSSi',
'site-atom-feed' => '$1 Cıresnayışê atomi',
'page-rss-feed' => '"$1" Cıresnayışê RSSi',
'page-atom-feed' => '"$1" Cıresnayışê atomi',
'feed-atom' => 'Atom',
'feed-rss' => 'RSS',
'red-link-title' => '$1 (çınîya)',
'sort-descending' => 'Ratnayışê qemeyayışi',
'sort-ascending' => 'Ratnayışê Zeydnayışi',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Pele',
'nstab-user' => 'Pera Karberi',
'nstab-media' => 'Pela Medya',
'nstab-special' => 'Pela xısusiye',
'nstab-project' => 'Pera proci',
'nstab-image' => 'Dosya',
'nstab-mediawiki' => 'Mesac',
'nstab-template' => 'Şablon',
'nstab-help' => 'Pela peşti',
'nstab-category' => 'Kategoriye',

# Main script and global functions
'nosuchaction' => 'Fealiyeto wınasi çıniyo',
'nosuchactiontext' => 'URL ra kar qebul nêbı.
Şıma belka URL şaş nuşt, ya zi gıreyi şaş ra ameyi.
Keyepelê {{SITENAME}} eşkeno xeta eşkera bıkero.',
'nosuchspecialpage' => 'Pela xasa wınasiye çıniya',
'nospecialpagetext' => '<strong>To yew pela xasa nêvêrdiye waşte.</strong>

Seba lista pelanê xasanê vêrdeyan reca kena: [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error' => 'Xırab',
'databaseerror' => 'Xeta serveri',
'dberrortext' => 'Rêzê vateyê malumati de xeta bı.
No xeta belka software ra yo.
"<blockquote><tt>$1</tt></blockquote>.
<tt>$2</tt>" ra pers kerdışê peyin:
Malumatê yo ke xeta dayo "<tt>$3: $4</tt>".',
'dberrortextcl' => 'Persê rêzê vateyê database de xeta bı.
Persê databaseyê peyin:
"$1"
Fonksiyono ke şuxulyo "$2".
Mesacê ke database dayo "$3: $4"',
'laggedslavemode' => 'Diqet: Pel de newe vıraşteyi belka çini .',
'readonly' => 'database kılit biyo',
'enterlockreason' => 'Database kılit biyo',
'readonlytext' => 'Qey pawıtış ri yew sebeb vace. Texmini yew tarix vace şıma key pawıtış wedarneni:  $1',
'missing-article' => "Banqa, pela be namê \"\$1\" \$2 ke gunê bıbo, nêdiye.

Ena belki seba yew vurnayışo kıhan ya zi tarixê gırê yew pele esteriya.

Eke wına niyo, belki ''software''i de yew xeta esta.
Kerem kerê, naye be namey ''URL''i yew [[Special:ListUsers/sysop|karber]]i ra vacê.",
'missingarticle-rev' => '(rewizyon#: $1)',
'missingarticle-diff' => '(Ferq: $1, $2)',
'readonly_lag' => 'Daegeh (database) otomatikmen kılit bi, sureo ke  daegehê bınêni resay daegehê serêni.',
'internalerror' => 'Xeta zerreki',
'internalerror_info' => 'Xeta zerreki: $1',
'fileappenderrorread' => 'Surey pırakerdene de "$1" nêşa bıwaniyo.',
'fileappenderror' => 'Dosyayê "$1" têyor nêbeno dosyayê "$2" ri.',
'filecopyerror' => '"$1" qaydê na "$2" dosya nêbeno.',
'filerenameerror' => 'nameyê "$1" dosya nêvuriya no name "$2" ri.',
'filedeleteerror' => 'Na "$1" dosya hewn a nêşi .',
'directorycreateerror' => '"$1" rêzkiyê ey nêvırazya',
'filenotfound' => 'Na "$1" dosya nêasena.',
'fileexistserror' => '"$1" nênusiya dosya re çunke : na dosya ca ra esta',
'unexpected' => 'Endek texmin nêbeni: "$1"="$2".',
'formerror' => 'Xeta: Form nêerşawiyeno',
'badarticleerror' => 'Kar  ke şıma kenê, qebul nêbi.',
'cannotdelete' => 'Pel  "$1" o ke şıma nişane kerd hewn a neşı.
Belka yewna ten kerdo hewn a.',
'cannotdelete-title' => 'şıma  "$1" nê şenê besternê.',
'delete-hook-aborted' => 'Esterıtışi terefê çengeli ra ibtal bi.
Qet tesrih beyan nêbi.',
'badtitle' => 'Sernameo xırabın',
'badtitletext' => 'Sernamey pela ke şıma waşt, nêvêrd, thalo/vengo ya ki zıwano miyanêno ğelet gırêdae ya ki sernamey wiki.
Beno ke, tede yew ya zi zêdê işareti estê ke sernaman de nêxebetiyenê.',
'perfcached' => 'Datay cı ver hazır biye. No semedê ra nıkayin niyo! tewr zaf {{PLURAL:$1|netice|$1 netice}} debêno de',
'perfcachedts' => 'Cêr de malumatê nımıteyi esti, demdê newe kerdışo peyın: $1. Tewr zaf {{PLURAL:$4|netice|$4 neticey cı}} debyayo de',
'querypage-no-updates' => 'Nıka newe kerdış nêbeno. no datayi ca de newe nêbeni .',
'wrong_wfQuery_params' => 'wfQuery() parametreyo şaş<br />
Fonksiyon: $1<br />
Perse: $2',
'viewsource' => 'Çımey bıvêne',
'viewsource-title' => "Cı geyrayışê $1'i bıvin",
'actionthrottled' => 'Kerden peysnaya',
'actionthrottledtext' => 'Riyê tedbirê anti-spami ra,  wextê do kılmek de şıma nê fealiyeti nêşkenê zaf zêde bıkerê, şıma ki no hedi viyarna ra.
Çend deqey ra tepeya reyna bıcerrebnên.',
'protectedpagetext' => 'Na per qey nêvuriyayiş ho pawyeno ya zi kerdışe bini.',
'viewsourcetext' => 'To şikinay çımey na pele bıvêne u kopya kerê:',
'viewyourtext' => "Na pela '''Vurnayışê ke kerdê''' re şıma şenê kopya kerê:",
'protectedinterface' => 'Na pela qandê nusnerin destegê verri dana u kes xırabin nêqero deye kerda kılit.',
'editinginterface' => "'''İqaz:''' Şıma hao jû pela ke seba nuşteyê meqalanê cayanê bırnayeyan dana, vurnenê.
Vurnayışê na pele karberanê binan rê serpela karberi kena ke bımocno.
Seba çarnayışi, yardımê [//translatewiki.net/wiki/Main_Page?setlang=diq translatewiki.net]i ra procêdoşkerdışi rê diqet kerên.",
'sqlhidden' => '(SQL pers kerdışê nımıte)',
'cascadeprotected' => 'No pel de vurnayiş qedexe biyo, çunke şıma tuşa "kademeyın" aqtif kerdo u no {{PLURAL:$1|pelo|pelo}} pawıteyo de xebıtyeno:
$2',
'namespaceprotected' => "No '''$1''' ca de icazetê şıma çino şıma pel rêz keri.",
'customcssprotected' => 'Mısadeyê şıma çıniyo ke na pela CSSi bıvurnên, çıke na pela xısusiye eyaranê karberan muhtewa kena.',
'customjsprotected' => 'Mısadeyê şıma çıniyo ke na pela Java Scripti bıvurnên, çıke na pela xısusiye eyaranê karberan muhtewa kena.',
'ns-specialprotected' => 'Pelê xısusiy nênê vurnayış.',
'titleprotected' => 'Eno [[User:$1|$1]] zerreyê ena peli nişeno vuriye.
Sebeb: "\'\'$2\'\'".',
'filereadonlyerror' => 'Dosyay vurnayışê "$1" nê abê no lakin depoy dosya da "$2" mod dê  salt wendi deyo.

Xızmetkarê  kılitkerdışi wa bewni ro enay wa çımra ravyarno: "$3".',
'invalidtitle-knownnamespace' => 'Canemey "$2" u metnê "$3" xırabo',
'invalidtitle-unknownnamespace' => 'Sernameye nêşınasiya yana amraiya canameyo  $1 u metno "$2" xırab',
'exception-nologin' => 'Tı cı nêkewtê',
'exception-nologin-text' => 'Na pele ya zi nê karkerdışi rê nê wiki de cıkewtış icab keno.',

# Virus scanner
'virus-badscanner' => "Eyaro şaş: no virus-cıgerayox nêzanyeno: ''$1''",
'virus-scanfailed' => 'cıgerayiş tamam nêbı (kod $1)',
'virus-unknownscanner' => 'antiviruso ke nêzanyeno:',

# Login and logout pages
'logouttext' => "'''Şıma hesab qefelna.'''

Nıka kamiyê xo eşkera mekere u siteyê {{SITENAME}} ra eşkeni devam bıkeri, ya zi [[Special:UserLogin|newe ra hesabê xo akere]] (wazeni pey nameyê xo, wazeni pey yewna name).
Wexta ke verhafızayê cıgerayoxê şıma pak beno no benate de taye peli de hesabe şıma akerde aseno.",
'welcomecreation' => '== Şıma xeyr amey, $1! ==

Hesabê şıma biyo a.
[[Special:Preferences|{{SITENAME}} vurnayişê tercihanê xo]], xo vir ra mekere.',
'yourname' => 'Namey karberi',
'yourpassword' => 'Parola',
'yourpasswordagain' => 'Parola reyna bınusne:',
'remembermypassword' => 'Parola mı nê cıgeyrayoği de bia xo viri (seba tewr zêde $1 {{PLURAL:$1|roce|rocan}})',
'securelogin-stick-https' => "Dekewtış kerdışi dıma HTTPS'i dı grêdaye bıman",
'yourdomainname' => 'Nameyê şıma yo meydani',
'password-change-forbidden' => 'Şıma na wiki de nêşenê parola bıvurnê.',
'externaldberror' => 'Ya database de xeta esta ya zi heqê şıma çino şıma no hesab bıvurni.',
'login' => 'Cı kewe',
'nav-login-createaccount' => 'Dekew de / hesab vıraze',
'loginprompt' => "{{SITENAME}} dı ronıştış akerdışi rê ''çerezan'' aktiv kerdış icab keno.",
'userlogin' => 'Cı kewe / hesab vıraze',
'userloginnocreate' => 'Cı kewe',
'logout' => 'Bıveciye',
'userlogout' => 'Bıveciye',
'notloggedin' => 'Hesab akerde niyo',
'nologin' => "Hesabê şıma çıniyo? '''$1'''.",
'nologinlink' => 'Yew hesab ake',
'createaccount' => 'Hesab vıraze',
'gotaccount' => "Hesabê şıma esto? '''$1'''.",
'gotaccountlink' => 'Cı kewe',
'userlogin-resetlink' => 'Melumatê cıkewtışi xo vira kerdê?',
'createaccountmail' => 'mı rê e-mail sera parola bırışe',
'createaccountreason' => 'Sebeb:',
'badretype' => 'Parolayê ke şıma nuşti yewbini nêtepışneni.',
'userexists' => 'Jewna karber enê nami karneno.
Mara reca xorê jewna name bınusnê.',
'loginerror' => 'Xetayê hesab ekerdışi',
'createaccounterror' => 'Hesab nêvırazyeno: $1',
'nocookiesnew' => 'Hesabê karberi vıraziya, labelê şıma nêşay cı kewê.
Semedê akerdışê hesabi çerezê {{SITENAME}}i gurêniyenê.
Şıma çerezi qapan kerdi.
Ravêri inan akerê, dıma be name u parola şımawa newiye cı kewê.',
'nocookieslogin' => 'Semedê akerdışê hesabi çerezê {{SITENAME}}i gurêniyenê.
Şıma çerezi qapan kerdi.
Ravêri inan akerê u reyna bıcerrebnê.',
'nocookiesfornew' => 'Hesabê karberi nêvıraziya, MA nêzana sebebê cı kotirawo.
Akerdış dê çerezarê xo emel bê uena pela fına barkerê.',
'nocookiesforlogin' => '{{int:nocookieslogin}}',
'noname' => 'Yew nameyo maqbul bınuse.',
'loginsuccesstitle' => 'Hesab abıya',
'loginsuccess' => "'''{{SITENAME}} dı name dê \"\$1\" şıma hesab akerdo.'''",
'nosuchuser' => 'Ebe namey "$1"i yew karber çıniyo.
Nuştışê namanê karberan de herfa pil u qıce rê diqet kerên.
Nuştışê xo qonrol kerên, ya zi [[Special:UserLogin/signup|yew hesabo newe akerên]].',
'nosuchusershort' => 'No "$1" name de yew ten çino. Kontrolê nuştışi bıkere.',
'nouserspecified' => 'Şıma gani yew name bıde.',
'login-userblocked' => 'No karber/na karbere blokekerdeyo/blokekerdiya. Cıkewtışi rê musade çıniyo.',
'wrongpassword' => 'Parola ğeleta. Rêna / fına bıcerrebne .',
'wrongpasswordempty' => 'Parola tola, venga. tekrar bınuse.',
'passwordtooshort' => 'Derganiya parola wa tewr tayn {{PLURAL:$1|1 karakter|$1 karakteran}} dı bo.',
'password-name-match' => 'Parola u nameyê şıma gani zeypê (seypê) nêbo.',
'password-login-forbidden' => 'No namey karberi u parola karkerdışê cı  kerdo xırab.',
'mailmypassword' => 'E-mail sera parola newiye bırışe',
'passwordremindertitle' => "Qandê {{SITENAME}}'i idareten parolaya newiye",
'passwordremindertext' => 'Yew ten (muhtemelen, şıma na aderesê IP ra $1 ) {{SITENAME}} ($4) newe yew parola waşt. "$2" no name ri emanet yew parola vıraziya "$3". Eke na şıma waşta, hesabê xo akere u newe yew parola bıvıraze. Muddetê parolayê şıma yo emanet {{PLURAL:$5|1 roc|$5 roci}}.

Eke vurnayişê parolayi, şıma nêwaşt ya zi parolayê şıma ameyo şıma vir u şıma hini qayil nşye parolayê xo bıvurni; no mesaj peygoş kere u bıewne gureyê xo.',
'noemail' => '"$1" No name de yew e-posta çiniyo.',
'noemailcreate' => 'Şıma gani yew parolayo meqbul peda bıkeri',
'passwordsent' => '"$1" No name de yew e-posta erşawiya (ruşya). hesabê xo, şıma wext mesaj gırewt u çax akere.',
'blocked-mailpassword' => 'Cıkewetışê na keyepel de şıma qedexe biye, ey ra newe yew şifre nêerşawyeno.',
'eauthentsent' => 'Adreso ke şıma dayo ma, ma yew e-posta rışt uca, o e-posta de kodê araşt kerdış esto.
Heta ke şıma o e-postaaraşt nêkeri ma yewna e-posta şıma ri nêrışêno.',
'throttled-mailpassword' => 'Parola vir ardış, zerreyê {{PLURAL:$1|yew seet|$1 seet}} de erşawiya.
Parola her {{PLURAL:$1|yew seete|$1 seete}} de yew rey erşawiyena.',
'mailerror' => 'Erşawıtışe xetayê e-posta: $1',
'acct_creation_throttle_hit' => 'Yew ten IP adresê şıma xebıtnayo u kewto no wiki, roco peyin de {{PLURAL:$1|1 hesab|$1 hesab}} vıraşto.
xulasa ney kesê ke IP adresê şıma xebıtneni hini nêeşkeni ney ra zêdêr hesab akeri.',
'emailauthenticated' => "Adresê E-posta da şıma '''$2''' seate $3 dı kerdo araşt.",
'emailnotauthenticated' => 'No format de nuştışê e-postayi qebul nêbeno.
Yew formato meqbul de adresê e-posta bınuse ya zi veng bıverde.',
'noemailprefs' => 'Hesab biyo a.',
'emailconfirmlink' => 'E-postayê xo araşt kerê',
'invalidemailaddress' => 'No format de nuştışê e-postayi qebul nêbeno. Yew formato meqbul de adresê e-posta bınuse ya zi veng bıverde.',
'cannotchangeemail' => 'E-postay hesabi ena wiki sera nêvurneyêno.',
'emaildisabled' => 'Na site ra e-posta nêrışêno.',
'accountcreated' => 'Hesab vıraciya',
'accountcreatedtext' => 'Qandê $1 hesabê karberi vıraziyayo.',
'createaccount-title' => 'Qey {{SITENAME}} newe yew heab vıraştış',
'createaccount-text' => 'Kesê, be e-posteyê şıma ra {{SITENAME}} ($4) de, ebe nameyê "$2" u parola "$3" ra yew hesab vıraşto.
Şıma gani cı kewê u parola xo nıka bıvurnê.',
'usernamehasherror' => 'Namey karberi de karakteri gani têmiyan ra mebê',
'login-throttled' => 'Demekê cıwa ver de şıma zah teşebbusê hesab akerdış kerd.
Bıne vındere u newe ra dest pê bıkere.',
'login-abort-generic' => 'Dekewtışê şıma xırabo-terkneyayo',
'loginlanguagelabel' => 'Zıwan: $1',
'suspicious-userlogout' => 'Waştişê tu ya veciyayişi kebul nibiya cunki ihtimal o ke waştiş yew browser ya zi proksiyê heripiyaye ra ameya.',

# E-mail sending
'php-mail-error-unknown' => "PHP's mail() fonksiyoni de xırabin vıcyê.",
'user-mail-no-addy' => 'Bê E-posta kerd ju e-posta bırşo cırê.',

# Change password dialog
'resetpass' => 'Parola bıvurne',
'resetpass_announce' => 'Şıma pê yew parolayê muweqqet hesab kerd a, qey qedyayişe dekewtış newe yew parola bınuse:',
'resetpass_text' => 'Parolayê hesab bıvurn',
'resetpass_header' => 'Parola hesabi bıvurne',
'oldpassword' => 'Parola kıhane:',
'newpassword' => 'Parola newiye:',
'retypenew' => 'Parola newiye tekrar ke:',
'resetpass_submit' => 'Parola eyar kere u newe ra dekewe',
'resetpass_success' => 'Parola şıma be serkewtış vurriye! Nıka hesabê şıma beno a...',
'resetpass_forbidden' => 'parolayi nêvuryayi',
'resetpass-no-info' => 'şıma gani hesab akere u hona bıeşke bırese cı',
'resetpass-submit-loggedin' => 'Parola bıvurne',
'resetpass-submit-cancel' => 'Bıtexelne',
'resetpass-wrong-oldpass' => 'parolayo parola maqbul niyo.
şıma ya parolaye xo vurnayo ya zi parolayo muwaqqat waşto.',
'resetpass-temp-password' => 'parolayo muweqet:',

# Special:PasswordReset
'passwordreset' => 'Parola reset ke',
'passwordreset-text' => 'Nê formi melumatê hesab dê şıma birê şıma viri deye pırkerê.',
'passwordreset-legend' => 'Parola reset ke',
'passwordreset-disabled' => 'Parola reset kerdış ena viki sera qefılneyayo.',
'passwordreset-pretext' => '{{PLURAL:$1||Enê cerenan ra jeweri defiye de}}',
'passwordreset-username' => 'Namey karberi:',
'passwordreset-domain' => 'Domain:',
'passwordreset-capture' => 'neticey e-postay bımocne?',
'passwordreset-capture-help' => 'Şıma na dorek morkerê se, e-posta (idareten eposta ya) şıma rê yana karbera rê rışêno.',
'passwordreset-email' => 'Adresa e-postey:',
'passwordreset-emailtitle' => 'Hesab timarê {{SITENAME}}',
'passwordreset-emailtext-ip' => 'Jeweri, {{SITENAME}} ra (ma heta şımayê, $1 IP adresi ra) ($4) teferuatê hesabdê şıma  va wa biyaro xo viri. Karbero ke cêrdeyo {{PLURAL:$3|hesaba|eno hesaba}} ena e-posta adresiya aleqey cı esto:

$2

{{PLURAL:$3|ena parola idaretena|ena parola idareten}} {{PLURAL:$5|jew roc|$5  roca}}rêya.
Ena parolaya deqewe de u xorê ju parolaya newi bıweçine. Parolaya şıma emaya şıma viri se  yana  ena e-posta şıma nê weştase u şıma qayıl niye parolaya xo bıvurnese, ena mesacer peygoş bıkerê.',
'passwordreset-emailtext-user' => '$1 enê karberi, {{SITENAME}}  ra ($4) teferuatê hesab dê şıma  va wa biyaro xo viri. Karbero ke cêrdeyo {{PLURAL:$3|hesaba|eno hesaba}} ena e-posta adresiya aleqey cı esto:

$2

{{PLURAL:$3|ena parola idaretena|ena parola idareten}} {{PLURAL:$5|jew roc|$5  roca}}rêya.
Ena parolaya deqewe de u xorê ju parolaya newi bıweçine. Parolaya şıma emaya şıma viri se  yana  ena e-posta şıma nê weştase u şıma qayıl niye parolaya xo bıvurnese, ena mesacer peygoş bıkerê.',
'passwordreset-emailelement' => 'Namey karberi: $1
Parola vêrdiye: $2',
'passwordreset-emailsent' => 'E-postay xo vira kerdışi rışiyê.',
'passwordreset-emailsent-capture' => 'Zey cêri e-postay xo vira kerdışi rışiyê.',
'passwordreset-emailerror-capture' => 'ey cêri e-postay xo vira kerdışi vıraziyê lakin merdum dê $1 rê nêrışiyê.',

# Special:ChangeEmail
'changeemail' => 'E-posta adresa xo bıvurnê',
'changeemail-header' => 'E-posya adresta hesabdê xo bıvurnê',
'changeemail-text' => 'Şıma qayılê ke e-postay xo bıvurnê se enê formi pırkerê. Qandê araşt kerdışi zi parolay xo şıma de bınusnê',
'changeemail-no-info' => 'Resayışê ena pela rê Dekewtış icab keno.',
'changeemail-oldemail' => 'E-postay şımaya newki:',
'changeemail-newemail' => 'E-posta adresiyo newe:',
'changeemail-none' => '(Çıno)',
'changeemail-submit' => 'E-postay xo bıvurne',
'changeemail-cancel' => 'Bıtexelne',

# Edit page toolbar
'bold_sample' => 'Metno qalın',
'bold_tip' => 'Metno qalın',
'italic_sample' => 'Metno vırandere',
'italic_tip' => 'Metno vırandere',
'link_sample' => 'Namey gırê',
'link_tip' => 'Gıreyê miyani',
'extlink_sample' => 'http://www.example.com şınasiya adresi',
'extlink_tip' => 'Greyê teberi (adresi vero http:// dekerê de)',
'headline_sample' => 'nuştey xeta seri',
'headline_tip' => '2.ki sewiye sername',
'nowiki_sample' => 'Non-format nuşte itiya ra bıerz',
'nowiki_tip' => 'Format kerdışê wiki bıterknê',
'image_sample' => 'Misal resim.jpg',
'image_tip' => 'Dosyaya gumın',
'media_sample' => 'misal.jpg',
'media_tip' => 'Gıreyê dosya',
'sig_tip' => 'İmza u wext',
'hr_tip' => 'Çıxiza dimdayi (hend akar mefiye)',

# Edit pages
'summary' => "<font style=\"color:Blue\">'''Xulasa:'''</font>",
'subject' => 'Mewzu/sernuşte:',
'minoredit' => "<font style=\"color:Green\">'''Eno vurnayışo de qıckeko'''</font>",
'watchthis' => "<font style=\"color:Green\">'''Ena pele seyr ke'''</font>",
'savearticle' => 'Pele qeyd ke',
'preview' => 'Verqayt',
'showpreview' => 'Verqayti bımocne',
'showlivepreview' => 'Verqayto cıwın',
'showdiff' => 'Vurnayışan bımocne',
'anoneditwarning' => 'Teme!: Şıma bı hesabê xo nıkewtê cı. Hurêndiya namey şıma dı IP-adresa şıma qeyd bena u asena.',
'anonpreviewwarning' => "''Ti hama nicikewte. Qeyd kerdiş zerre tarixê pele de adresê IP yê tu keyd keno.''",
'missingsummary' => "'''DİQET:''' Şıma kılmnuşte nıkerd.
Eke şıma reyna butonê qaydker ser a ne pel bê kılmnuşte qayd beno.",
'missingcommenttext' => 'Cêr de yew xulasa binuse.',
'missingcommentheader' => "Vir ardoğ:''' Şıma qey na mesela sername nuşte nênuşt.
Eke şıma reyna \"{{int:savearticle}}\" qayd ker bıtıkni pel bê sername qayd beno.",
'summary-preview' => 'Verqeydê qıssa:',
'subject-preview' => 'Mesela/Sername  verqayd seyr kerdış:',
'blockedtitle' => 'Karber (eza) blok biyo',
'blockedtext' => '\'\'\'No name ya zi na IP adresê şıma ri musade çino.\'\'\'

Oyo ke musade nêkeno: $1.<br />
Sebebê musade nêdayiş: \'\'$2\'\'.

* Dest pê kerdışê musade nêdayiş: $8
* Qedyayişê musade nêdayiş: $6
* Oyo ke cı rê musade nêdeyêno: $7

Eke şıma sebebê musade nêdayiş ri itiraz keni, $1 de ya zi yewna [[{{MediaWiki:Grouppage-sysop}}|xızmetkar]] de şıma eşkeni na mesela de qıse bıkeri. [[Special:Preferences|Tercihê]] eke şıma na qısme de pey yew e-postayo raşt nêkewte cı, şıma xususiyetê "Karber ri e-posta bırışê" ra nêeşkeni istifade bıkeri, eke şıma tercihanê xo bıerz zerreyê e-postayê xo şıma hıni şenê ep-posta bırışê.
<br />IP adresê şıma yo nıkayın $3, numreya musade nêdayiş #$5.
<br />Eke şıma qayile yew xızmkar çiko bıpers, no malumatan not bıkere ney şıma rê lazım beni.',
'autoblockedtext' => 'IP adresê şıma otomotikmen kerda kılit, çıkı $1 verniya nê hesabi grota.
Sebebê cı zi:

:\'\'$2\'\'

* Dest pê kerdışê verni grotışi: $8
* Qedyayişê verni grotışi: $6
* Qayile ke bloqe bıbo: $7

Şıma qayile qey weri kewtışê na mesela,  $1 ya na [[{{MediaWiki:Grouppage-sysop}}|serkaran ra]] yewi ra şenê irtibat kewê.

Not, [[Special:Preferences|Tercihê karberi]] eke şıma yew e-postayo raşt nênuşt se şıma nêşenê na xususiyet ra "karber rê e-posta bırışê" istifade bıkeri.

IP adresiya şıma yo nıkayên $3 u ID şıma yo ke musade nêdaye #$5. Eke şıma yew tehqiqat vırazeni malumatê corênan xo vira mekerê.',
'blockednoreason' => 'sebeb nidaniyo',
'whitelistedittext' => 'Qandê vurnayış kerdışi rê $1.',
'confirmedittext' => 'Eka ti wazene binusi, adresê xo e-maili confirme bike.
Adresê xo e-maili [[Special:Preferences|user preferences]] de confirme bike.',
'nosuchsectiontitle' => 'Eno qısım çıniyo',
'nosuchsectiontext' => 'To waşt ke yew qısım kewê, oyo ke çıniyo.
Heta ke werte de qısım çıniyo, ca çıniyo ke tı raştkerdışê xo qeyd bıkerê.',
'loginreqtitle' => 'Cıkewtış lazımo',
'loginreqlink' => 'cı kewe',
'loginreqpagetext' => 'Eka ti wazeno peleyanê bini bivini, ti gani $1.',
'accmailtitle' => 'Paralo şirawiyayo.',
'accmailtext' => "[[User talk:$1|$1]] parolayo ke raşt ameyo şırawiyo na adres $2.

Qey na hesabê newe parola, cıkewtış dıma şıma eşkeni na qısım de ''[[Special:ChangePassword|parola bıvurn]]'' bıvurni.",
'newarticle' => '(Newe)',
'newarticletext' => "Ena pele, database ma de hona çiniyo.
Eka tı wazene yew bıvırazi, bınê eno nuşte de yew quti esto u uca de bınuse (bıvinin [[{{MediaWiki:Helppage}}|help page]] qe informasyonê zafyeri).
Eka tı ita semed yew heta ra amey, ser gocekê '''back'''i klik bıkin.",
'anontalkpagetext' => "----''No pel, pel o karbero hesab a nêkerdeyan o, ya zi karbero hesab akerdeyan o labele pê hesabê xo nêkewto de. No sebeb ra ma IP adres şuxulneni û ney IP adresan herkes eşkeno bıvino. Eke şıma qayil niye ina bo xo ri [[Special:UserLogin/signup|yew hesab bıvıraze]] veyaxut [[Special:UserLogin|hesab akere]].''",
'noarticletext' => 'Ena pele de hewna theba çıniyo.
Tı şenay zerreyê pelanê binan de [[Special:Search/{{PAGENAME}}|seba sernamey ena pele cı geyre]],
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} cıkewtışê aidi rê cı geyre],
ya zi [{{fullurl:{{FULLPAGENAME}}|action=edit}} ena pele bıvurne]</span>.',
'noarticletext-nopermission' => 'Na pela dı eno metin enewke vengo
Na sernuşteya şıma [[Special:Search/{{PAGENAME}}|pelanê binan de şeni bıgeyri]]
ya zi <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} itara şeni bıgeyri cı].</span> feqet şıma nişeni biizın teba bıkeri.',
'missing-revision' => 'Rewizyonê name dê pela da #$1 "{{PAGENAME}}" dı çıniyo.

No normal de tarix dê pelanê besterneyan dı ena xırabin asena.
Detayê besternayışi [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} tiya dı] aseno.',
'userpage-userdoesnotexist' => 'Hesabê karberi "<nowiki>$1</nowiki>" qeyd nêbiyo.
Kerem ke, tı ke wazenay na pele bafernê/bıvurnê, qontrol ke.',
'userpage-userdoesnotexist-view' => 'Hesabê karberi "$1" qeyd nêbiyo.',
'blocked-notice-logextract' => 'No karber/na karbere emanet blokekerdeyo/blokekediya.
Cıkewtışo tewr peyêno ke bloke biyo, cêr seba referansi belikerdeyo:',
'clearyourcache' => "'''Not:''' Bahde sazkerdışi, gani hafızayê cı gerayoğ pak bıbo.
*'''Mozilla / Firefox / Safari:''' ''Shift'' ri gıştê şıma ser nayi pel newe ra bar kere yana zi''Ctrl-Shift-R'' bıkere u (qey Apple Mac ''Cmd-Shift-R'');,
*'''IE:''' ''Ctrl-F5' piya pıploxnê ke wa newe bo', 
* '''Operar:'''hacetan ra şı rê →tercihan ra bıvurnen",
'usercssyoucanpreview' => "'''Yardim:''' Ser \"{{int:showpreview}}\" sima eskeni CSSe newe test bikeri.",
'userjsyoucanpreview' => "'''Yardim:''' Ser \"{{int:showpreview}}\" sima eskeni CSSe newe test bikeri.",
'usercsspreview' => "'''şıma tena verqaydê dosyayê CSS vineni.''' '''Dosyayê Karberi CSS hema qayd nebiyo!'''",
'userjspreview' => "'''şıma tena test keni ya ziverqayn seyr keni - karberê JavaScript'i hema qayd nebiyo.'''",
'sitecsspreview' => "'''Şımayê enewke tenya verqaytê dosya da CSS vınenê.''' 
'''Hewna qayd nêbı!'''",
'sitejspreview' => "'''Şımayê enewke tenya verqaytê kodê dosya da JavaScriptê karberi vınenê.''' 
'''hewna qayd nebı!'''",
'userinvalidcssjstitle' => "'''Teme:''' Mewzuyê \"\$1\" çıniyo.
Dosyanê be namey .css u .js'i de herfa werdiye bıgurêne, mesela herında {{ns:user}}:Foo/Vector.css'i de {{ns:user}}:Foo/vector.css bınuse.",
'updated' => '(Newenyaya)',
'note' => "'''Not:'''",
'previewnote' => "'''Xo vira mekerê ke ena yew verqayta.'''
Vurnayışê şıma hona qeyd nêbiyo!",
'continue-editing' => 'Şo herunda vurnayışi',
'previewconflict' => 'No seyrkerdışê verqaydi serê qutiyê nuşte tezim kerdış de yo, eke şıma qayile vurnayişê maddeyi seyino bıvini, no mocneno şıma.',
'session_fail_preview' => 'Ma ef kere. Vindibiyayişê tayê datay ra a kerdışê hesabê şıma de ma vurnayişê şıma qayd nêkerd. Newe ra tesel (cereb) bıkere. Eke no qayde zi nêbo, [[Special:UserLogout|hesabê xo bıqefelne]] u newera a kere.',
'session_fail_preview_html' => "'''Ma meluli! Sebayê vindbiyayişê datasistemi ma vurnayişê şıma nêeşkeni qaydker.'''

''Çunke keyepelê {{SITENAME}} de raw HTML aqtifo, seyrkerdışê verqayd semedê galayê (alızyayiş) JavaScript ri nımıyayo.''

'''Eke no vurnayiş heqê şımayo, newe ra tesel bıker (bıcerebi). eke hona zi nêxebıtya, [[Special:UserLogout|vec]] newe ra hesabê xo aker.'''",
'token_suffix_mismatch' => "'''Vurnayişê şıma tepeya ameyo çunke qutiyê imla xerıbya.
Vurnayişê şıma qey nêxerepyayişê peli tepeya geyra a.
Eke şıma servisê proksi yo anonim şuxulneni sebebê ey noyo.'''",
'edit_form_incomplete' => "'''Qandê form dê vurnayışa tay wastera ma nêreşti; Vurnayışê ke şıma kerdê nêalızyayê, çım ra ravyarnê u fına bıcerbnê.'''",
'editing' => 'Şımayê <font style="color:red">$1</font> vurnenê',
'creating' => 'Pela <font style="color:blue">$1</font> vırazê',
'editingsection' => 'Per da $1 de şımaye kenê ke leti bıvurnê',
'editingcomment' => '$1 vuryeno (qısmo newe)',
'editconflict' => 'Vurnayişê ke yewbini nêtepışeni: $1',
'explainconflict' => "Wexta ke şıma pel vurneyene yewna ten zi pel vurna.
Nuşteyo corin; halê pelo nıkayin mocneno.
Vurnayişê şıma cêr de mocya ( musya).
Vurnayişanê peyinan şıma gani qayd bıkeri.
Wexta ke şıma butonê \"{{int:savearticle}}\" tıkna '''teyna''' nuşteyo corin qayd beno.",
'yourtext' => 'nuşteyê şıma',
'storedversion' => 'Nuşteyo qaydbiyaye',
'nonunicodebrowser' => "'''DİQET: Browserê şıma u unicode yewbini nêgeni. Qey izin dayişê vurnayişê pelan: Karakteri ke ASCII niyê; zerreyê qutiyê vurnayişi de kodi (cod) şiyes-şiyes aseni.'''",
'editingold' => "'''DİQET: Şıma pelo revizebiyaye de vurnayiş keni. Eke şıma qayd bıkeri vurnayişi ke pelo revizebiyayiş ra heta ewro biyê, pêroyê ey beni vini.'''",
'yourdiff' => 'pêverronayiş',
'copyrightwarning' => "'''Recayê ikazi:''' Sita da {{SITENAME}} ra iştıraqi pêro umışin da $2 zerredeyo (teferruata rê $1'i bıvinê).
İştıraqê şıma, şıma kayıl niyê ke yewna merdumi kerpeyina bıvurnê yana yewna caya ra vılakerê se, iştıraq mekewê.<br />
Fına zi qayılê ke  iştıraq kewê, Şıma qayılê kê şar vaco eno nuşte felani nuşnayo yana resmi meqeman ra zanayışê cı  u malumatê cı esto/ Xoseri cayan ra groti rê şıma qerenti danê. '''Tiya dı, şıma wêrê telifira mısade nêgroto se eserê cı tiya vıla mekerê! '''",
'copyrightwarning2' => 'Ney bızane ke nuşteyê ke şıma ruşneni (şaweni) keyepelê {{SITENAME}} herkes eşkeno nê nuşteyanê şıma ser kay bıkero. Eke şıma qayil niye kes bıvurno, nuşetyanê xo meerze ita. <br />
Wexta ke şıma nuşte zi erzeni ita; şıma gani taahhud bıde koti ra ardo (qey teferruati referans: $1).',
'longpageerror' => "'''Xırab: Dergeya nuşte dê şıma nezdi {{PLURAL:$1|kilobayto|$1 kilobayto}}, feqet {{PLURAL:$2|kilobayt|$2 kilobayt}} ra vêşiyo. Qeyd biyayişê cı nêbeno'''",
'readonlywarning' => "'''DİQET: Semedê mıqayti, database kılit biyo. No sebeb ra vurnayişê şıma qayd nêbeno. Nuşteyanê şıma yewna serkar eşkeno wedaro u pey ra şıma eşkeni reyna ita de qayd bıker'''

Serkar o ke kılit kerdo; no beyanat dayo: $1",
'protectedpagewarning' => "'''Diqet: No pel pawyeno, teyna serkari eşkeni bıvurni.'''
Wexta ke şıma no pel vurneni diqet bıkeri:",
'semiprotectedpagewarning' => "'''Diqet: No pel pawyeno, teyna serkari eşkeni bıvurni.'''
Wexta ke şıma no pel vurneni diqet bıkeri, log bivini:",
'cascadeprotectedwarning' => "'''Diqet:''' Na pele kılit biya, tenya karberê idarekeri şenê ke naye bıvurnê, çıke na zerrey {{PLURAL:$1|na pela şipa-kılitkerdiye|nê pelanê şipanê-kılitkerdiyan}} dera:",
'titleprotectedwarning' => "'''Diqet: Na pele kılit biya, [[Special:ListGroupRights|heqê xususiy]] lazımê ke naye vırazê.'''
Loge peniye cor de este:",
'templatesused' => '{{PLURAL:$1|Şablon|Şabloni}} ke na pela de xebtênê:',
'templatesusedpreview' => '{{PLURAL:$1|Sablon|Sabloni}}  ke na verqayt de xebetnayê:',
'templatesusedsection' => '{{PLURAL:$1|Template|Templateyan}}  ke na qısım de xebetniyenê:',
'template-protected' => '(kılit biyo)',
'template-semiprotected' => '(nimey ena pele kılit biya)',
'hiddencategories' => 'Ena per de {{PLURAL:$1|1 kategoriyo nımıte|$1 kategoriyê nımıtey}} muhtewa benê:',
'edittools' => '<!-- Text here will be shown below edit and upload forms. -->',
'edittools-upload' => '-',
'nocreatetitle' => 'Vıraştışê pele mehcuro',
'nocreatetext' => '{{SITENAME}}, Pelê neweyi vıraştış re destur çino.
şıma eşkeni tepiya şêri u eke şıma qayd biyaye yê [[Special:UserLogin|şıma eşkeni hesab akeri]], eke niye [[Special:UserLogin|şıma eşkeni qayd bıbiy]].',
'nocreate-loggedin' => 'İcaze şıma çino şıma pelo newe akeri.',
'sectioneditnotsupported-title' => 'Destekê vurnayışiê qısımi çıniyo',
'sectioneditnotsupported-text' => 'Destekê vurnayışiê qısımi ena pela vurnayışi de çıniyo.',
'permissionserrors' => 'Xetayê icazeyi',
'permissionserrorstext' => 'Qey {{PLURAL:$1|sebebê|sebebê}} cêrini ra icazeyê şıma çin o:',
'permissionserrorstext-withaction' => '{{PLURAL:$1|Sebeba|Sebeb da}} cêri ra icazetê $2 çıniyo:',
'recreate-moveddeleted-warn' => "'''Hişyari: no pel o ke şıma vırazeni vere cû vırazyayo.'''

Diqet bıkeri no vurnayişê şıma re gerek esto:",
'moveddeleted-notice' => 'Ma ena pele wederna.
Qe referansi logê wedernayışi bın de mocnayiya.',
'log-fulllog' => 'Temamê rocaneyi bıvine',
'edit-hook-aborted' => 'Vurnayiş vınderiya.
Yew sebeb beyan nibı.',
'edit-gone-missing' => 'Pel rocanebiyaye niyo.
Hewna kerde aseno.',
'edit-conflict' => 'Vurnayişê pêverdiyaye .',
'edit-no-change' => 'Vurnayişê şıma qebul nêbı, çunke nuşte de yew vurnayiş n3evıraziya.',
'edit-already-exists' => 'Pelo newe nêvıraziyeno.
Pel ca ra esto.',
'defaultmessagetext' => 'Hesıbyaye metne mesaci',

# Parser/template warnings
'expensive-parserfunction-warning' => 'Hişyari: No pel de fonksiyoni zaf esti.

No $2 daweti ra gani tay bıbo, na hel {{PLURAL:$1|1 dawet esto|$1 dawet esto}}.',
'expensive-parserfunction-category' => 'Pelê ke tede zaf fonksiyoni esti',
'post-expand-template-inclusion-warning' => 'Tembe: zerreyê şabloni zaf gırdo.
Taye şabloni zerre pel de nêmociyayeni.',
'post-expand-template-inclusion-category' => 'Pelê şabloni ke hed ra veceyi',
'post-expand-template-argument-warning' => 'Tembe: No per de tewr tay yew şablono herayi esto.Nê vurnayeni ser çebyay',
'post-expand-template-argument-category' => 'Pelê ke şablonê eyi qebul niye',
'parser-template-loop-warning' => 'Gıreyê şabloni ca biyo: [[$1]]',
'parser-template-recursion-depth-warning' => 'limitê şablonê newekerdışi biyo de ($1)',
'language-converter-depth-warning' => 'xoritiya çarnekarê zıwanan viyarnê ra ($1)',
'node-count-exceeded-category' => 'Pela ra hetê kotya amardışê cı ravêrya',
'node-count-exceeded-warning' => 'Amariya pela ravêrya.',
'expansion-depth-exceeded-category' => 'Pela dı hetê canaya zoriya herayin ravêrya',
'expansion-depth-exceeded-warning' => 'Ravêriya pela xori herayêna',
'parser-unstrip-loop-warning' => 'Unstrip lete vineya',
'parser-unstrip-recursion-limit' => 'Sinorê limit dê qayış dê ($1) ravêrya',
'converter-manual-rule-error' => 'Rehberê zıwan açarnayışi dı xırabin tesbit biya',

# "Undo" feature
'undo-success' => 'No vurnayiş tepeye geryeno. pêverronayişêyê cêrıni kontrol bıkeri.',
'undo-failure' => 'Sebayê pêverameyişê vurnayişan karo tepêya gırewtış nêbı.',
'undo-norev' => 'Vurnayiş tepêya nêgeryeno çunke ya vere cû hewna biyo ya zi ca ra çino.',
'undo-summary' => 'Peysergırewtışê teshisê $1i be terefê [[Special:Contributions/$2|$2i]] ([[User talk:$2|Werênayış]])',

# Account creation failure
'cantcreateaccounttitle' => 'Nêşenay hesab rakerê',
'cantcreateaccount-text' => "Hesabvıraştışê na IP adrese ('''$1''') terefê [[User:$3|$3]] kılit biyo.

Sebebo ke terefê $3 ra diyao ''$2''",

# History pages
'viewpagelogs' => 'Heq dê ena perer qeydan bıvinên',
'nohistory' => 'Verê vurnayışanê na pele çıniyo.',
'currentrev' => 'Halo nıkayên',
'currentrev-asof' => 'Revizyonanê peniyan, tarixê $1',
'revisionasof' => 'Verziyonê roca $1ine',
'revision-info' => 'Vıraştena cı karber $2 ra rewizyona $1',
'previousrevision' => '← Çımraviyarnayışo kıhanêr',
'nextrevision' => 'Rewizyono newên →',
'currentrevisionlink' => 'Tewr halê rocaniye bımocne',
'cur' => 'ferq',
'next' => 'badên',
'last' => 'peyên',
'page_first' => 'verên',
'page_last' => 'peyên',
'histlegend' => "'''Ferqê weçinayışi:''' Qutiya versiyonan mor ke u  ''enter''i bıpıloxne ya zi makera cêrêne bıpıloxne.<br /> 
Lecant: '''({{int:cur}})''' = ferqê versiyonê peyêni,
'''({{int:last}})''' = ferqê versiyonê verêni, '''{{int:minoreditletter}}''' = vurnayışo werdi.",
'history-fieldset-title' => 'Bewni tarixer',
'history-show-deleted' => 'Tenya esterıt',
'histfirst' => 'Verênêr',
'histlast' => 'Peyênêr',
'historysize' => '({{PLURAL:$1|1 bayt|$1 bayti}})',
'historyempty' => '(thal)',

# Revision feed
'history-feed-title' => 'Tarixê çımraviyarnayışi',
'history-feed-description' => 'Wiki de tarixê çımraviyarnayışê na pele',
'history-feed-item-nocomment' => '$1 miyanê $2i de',
'history-feed-empty' => 'Pela cıgeyrayiye çıniya.
Beno ke ena esteriya, ya zi namê cı vuriyo.
Seba pelanê muhimanê newan [[Special:Search|cıgeyrayışê wiki de]] bıcerebne.',

# Revision deletion
'rev-deleted-comment' => '(Timarkerdışe enay hewadeyayo)',
'rev-deleted-user' => '(namey karberi esteriyo)',
'rev-deleted-event' => '(fealiyetê cıkewtışi esteriyo)',
'rev-deleted-user-contribs' => '[namey karberi ya zi adresa IPy esteriya - vurnayış iştırakan ra nımniyo]',
'rev-deleted-text-permission' => "Çımraviyarnayışê ena pele '''esteriyo'''.
Beno ke [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log] de teferruat esto.",
'rev-deleted-text-unhide' => "Çımra viyarnayışê ena pele '''besterêno'''.
Beno ke [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} besternayış] de teferruat esto.
Şıma be idarekerina xo ra şenê hewna [$1 nê çımra viyarnayışi bıvinê], eke wazenê dewam kerê.",
'rev-suppressed-text-unhide' => "Çımra viyarnayışê ena pele '''Degusneyayo'''.
Beno ke [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} degustış] de teferruat esto.
Şıma be idarekerina xo ra şenê hewna [$1 nê çımraviyarnayışi bıvênê], eke wazenê dewam kerê.",
'rev-deleted-text-view' => "Çımra viyarnayışê ena pele '''besternêno'''.
Şıma be idarekerina xo ra şenê ey bıvênê; beno ke [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} besternayış] de teferruat esto.",
'rev-suppressed-text-view' => "Çımraviyarnayışê ena pele '''degusneyayo'''.
Şıma be idarekerina xo ra şenê ey bıvênê; beno ke [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} degusnayış] de teferruat esto.",
'rev-deleted-no-diff' => "Şıma nêşenê nê ferqi bıvênê, çıke çımraviyarnayışan ra  yew '''esteriyo'''.
Beno ke [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log] de teferruat esto.",
'rev-suppressed-no-diff' => "Revizyon '''esteriyayo\"' aye ra ti nieşkeno ena diff bivine.",
'rev-deleted-unhide-diff' => "Çımra viyarnayışanê na ferqi ra  yew '''besterneyayo'''.
Beno ke [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} besternayış] dı teferruat esto.
Şıma be idarekerina xo ra şenê hewna [$1 nê ferqi bıvênê], eke wazenê dewam kerê.",
'rev-suppressed-unhide-diff' => "Nê Timarkerdışi ra yewi '''çap biyo'''.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rocaneyê vındertışi] de teferru'ati esti.
Eke şıma serkari u devam bıkeri [$1 no vurnayiş şıma eşkeni bıvini].",
'rev-deleted-diff-view' => "Jew timarkerdışê ena versiyon '''wedariyayo''.
Îdarekarî şenê ena versiyon bivîne; belki tiya de [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} wedarnayişî] de teferruat esto.",
'rev-suppressed-diff-view' => "Jew timarkerdışê ena versiyon '''Ploxneyış'' biyo.
Îdarekarî eşkeno ena dif bivîne; belki tiya de [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} ploxnayış] de teferruat esto.",
'rev-delundel' => 'bımocne/bınımne',
'rev-showdeleted' => 'bımocne',
'revisiondelete' => 'Bıestere/çımraviyarnayışan peyser bia',
'revdelete-nooldid-title' => 'Çımraviyarnayışo waşte nêvêreno',
'revdelete-nooldid-text' => 'Şıma vıraştışê nê fonksiyoni rê ya yew çımraviyarnayışo waşte diyar nêkerdo, çımraviyarnayışo diyarkerde çıniyo, ya ki şıma wazenê ke çımraviyarnayışê nıkayêni bınımnê.',
'revdelete-nologtype-title' => 'Qet qeydê cınêdiya',
'revdelete-nologtype-text' => 'Qeydê şımawo diyar çıniyo ke nê fealiyet kewê.',
'revdelete-nologid-title' => 'Cıkewtış qebul nêbi',
'revdelete-nologid-text' => 'Şıma vıraştışê nê fonksiyoni rê ya yew cıkewtışo waşte diyar nêkerdo, ya ki çıkewtışo diyarkerde çıniyo.',
'revdelete-no-file' => 'Dosya diyarkerdiye çıniya.',
'revdelete-show-file-confirm' => 'Şıma eminê ke wazenê çımraviyarnayışê esterıtey na dosya "<nowiki>$1</nowiki>" $2 ra $3 de bıvênê?',
'revdelete-show-file-submit' => 'E',
'revdelete-selected' => "'''[[:$1]]: ra {{PLURAL:$2|çımraviyarnayışo weçinıte|çımraviyarnayışê weçinıtey}}'''",
'logdelete-selected' => "'''{{PLURAL:$1|Qeydbiyayışo weçinıte|Qeydbiyayışê weçinıtey}}:'''",
'revdelete-text' => "'''Çımraviyarnayışê esterıtey u kerdışi hewna tarixê pele u qeydan de asenê, hema parçeyê zerrekê dinan areze nêbenê.'''
Eke şertê ilawekerdey ke niyê ro, idarekerê bini {{SITENAME}} de nêşenê hewna bıresê zerrekê nımıtey u şenê ey anciya na eyni miyanpele ra peyser biarê.",
'revdelete-confirm' => 'Ma rica keno testiq bike ti ena hereket keno u ti zano neticeyanê herketanê xo u ti ena hereket pê ena [[{{MediaWiki:Policy-url}}|polici]] ra keno.',
'revdelete-suppress-text' => "Wedardış gani '''tenya''' nê halanê cêrênan de bıxebıtiyo:
* Melumatê kıfırio mıhtemel
* Melumatê şexio bêmınasıb
*: ''adresa keyey u numreyê têlefoni, numreyê siğorta sosyale, uêb.''",
'revdelete-legend' => 'Şertanê vênayışi rone',
'revdelete-hide-text' => 'Nuştey çımraviyarnayışi bınımne',
'revdelete-hide-image' => 'zerreyê dosyay bınımnê',
'revdelete-hide-name' => "hedef u vaqa' bınımne",
'revdelete-hide-comment' => 'kılmvatış memocne',
'revdelete-hide-user' => 'Karber u IP ê ke vurnayiş kerdo bınım.',
'revdelete-hide-restricted' => 'Malumatan pa serkaran u karberan ra bınım.',
'revdelete-radio-same' => '(mevurne)',
'revdelete-radio-set' => 'E',
'revdelete-radio-unset' => 'Nê',
'revdelete-suppress' => 'Hem ê binan ra hem zi serkaran ra malumatan bınım',
'revdelete-unsuppress' => 'reizyonê ke tepiya anciye serbest ker',
'revdelete-log' => 'Sebeb:',
'revdelete-submit' => 'Cewab be {{PLURAL:$1|çımraviyarnayışi|çımraviyarnayışan}} de',
'revdelete-success' => "''''Esayişê revizyoni bi muvaffaqi eyar bi.'''",
'revdelete-failure' => "'''Esayişê revizyoni eyar nibeno:'''
$1",
'logdelete-success' => "'''Esayişê rocaneyi bı muvaffaqi eyar bı.'''",
'logdelete-failure' => "'''Esayişê rocaneyi eyar nêbı:'''
$1",
'revdel-restore' => 'asayışi bıvurne',
'revdel-restore-deleted' => 'revizyonê wedariyaye',
'revdel-restore-visible' => 'revizyonê ke asenê',
'pagehist' => 'Verora perer',
'deletedhist' => 'tarixê hewna şiyaye',
'revdelete-hide-current' => '$2 $1 ney çiye ke wexta diyayene wera (wedar dayiş) xeta da: no reviyon nınımiyeno.',
'revdelete-show-no-access' => '$2 $1 wexta ke ney tarix de mociyayene xeta da: ne çi "vergırewtı" nişane biyo.
resayişê şıma çino.',
'revdelete-modify-no-access' => '$2 $1 no çi yê ke wexta vuriyayene xeta da: no çi "vergırewtı" nişane biyo.
resayişê şıma çino.',
'revdelete-modify-missing' => "$1 ID' de wexta ke çiyek vuriyayene xeta vıraziya: database vindbiyaye yo!",
'revdelete-no-change' => "'''Hişyari:'''  $2 $1 no çi re ca ra eyarê esayişi waziyayo.",
'revdelete-concurrent-change' => '$2 $1 no çi wexta ke vuriya xeta da: wina aseno ke wexta şıma vurnayiş kerdene o enate de yewna te vurnayiş kerdo.
rocaneyan kontrol bıkere.',
'revdelete-only-restricted' => 'Xetawa ke maddeyanê rocanê $2, $1ine nımnena: şıma nêşenê maddeyanê ke terefê idarekeran ra nêdiyaeyan, bê weçinıtışê tercihanê vêniyaoğanê binan ra zi yewi, çap kerê.',
'revdelete-reason-dropdown' => '*Sebebê besternayış de umumi
** İhlalê telifi
** Malumatê şexsiyo ke munasib niye
** Nameyo xırab
** Malumatê iftira çekerdışi',
'revdelete-otherreason' => 'ê bini/sebebê bini',
'revdelete-reasonotherlist' => 'sebebê bini',
'revdelete-edit-reasonlist' => 'sebebê hewna kerdışani bıvurn',
'revdelete-offender' => 'nuştoxê revizyoni:',

# Suppression log
'suppressionlog' => 'qeydê pinani kerdışi',
'suppressionlogtext' => "Cêr de, kahyayan ra zerreko nımıte esto,eno listey besterneya u merdumê bloke kerdışiyo. 
Listey xırabi u bloki re pelay [[Special:BlockList|IP'yê ke bloke biyê]] bivinê.",

# History merging
'mergehistory' => 'vere cûye pelan bıhewelın',
'mergehistory-header' => 'No pel, reviyonê yew peli eşkeno yewna pelo newe de piyawano.
no vurnayişo ke şıma keni kontrol bıkere yew pelo kehen nêbo.',
'mergehistory-box' => 'revizyonê pelanî yew bike:',
'mergehistory-from' => 'Pela çimeyî',
'mergehistory-into' => 'Pela destinasyonî',
'mergehistory-list' => 'tarixê vurnayîşî ke eşkeno yew bi.',
'mergehistory-merge' => '[[:$1]] qey ney revizyonê cêrini [[:$2]] şıma ekeni piyawani. Benatê wexto muwaqqet de piyayanayişê rezizyonan de tuşa radyo bıxebitne.',
'mergehistory-go' => 'Vernayîşê yewbiyayeni bimocne',
'mergehistory-submit' => 'revizyonî yew bike',
'mergehistory-empty' => 'Revizyonî yew nibenê.',
'mergehistory-success' => '$3 {{PLURAL:$3|revizyonê|revizyonê}} [[:$1]] u [[:$2]] yew biyê.',
'mergehistory-fail' => 'Tarixê pele yew nibeno, ma rica kenê ke pel u wext control bike.',
'mergehistory-no-source' => 'Pela çime $1 çini yo.',
'mergehistory-no-destination' => 'Pela destinasyoni $1 çini yo.',
'mergehistory-invalid-source' => 'Pela çime gani yew seroğê raşt biy.',
'mergehistory-invalid-destination' => 'Pela destinasyonî gani yew seroğê raşt biy.',
'mergehistory-autocomment' => '[[:$1]] u [[:$2]] yew biyê',
'mergehistory-comment' => '[[:$1]] u [[:$2]] yew biyê: $3',
'mergehistory-same-destination' => 'Pela çime u destinasyonî gani eyni nibiy.',
'mergehistory-reason' => 'Sebeb:',
'mergehistory-revisionrow' => '$1 ($2) $3 . . $4 $5 $6',

# Merge log
'mergelog' => 'Logê yew kerdişî',
'pagemerge-logentry' => '[[$1]] u [[$2]] yew kerd (revizyonî heta $3)',
'revertmerge' => 'Abırnê',
'mergelogpagetext' => 'Cêr de jû liste esta ke mocnena ra, raya tewr peyêne kamci pela tarixi be a bine ra şanawa pê.',

# Diffs
'history-title' => 'Rewizyonê $1:',
'difference-title' => 'Pela "$1" ferqê çım ra viyarnayışan',
'difference-title-multipage' => 'Ferkê pelan dê "$1" u "$2"',
'difference-multipage' => '(Ferqê pelan)',
'lineno' => 'Xeta $1i:',
'compareselectedversions' => 'Rewizyonanê weçineyan pêver ke',
'showhideselectedversions' => 'Revizyonanê weçinıtan bımocne/bınımne',
'editundo' => 'peyser bia',
'diff-multi' => '({{PLURAL:$1|Yew revizyono miyanên|$1 revizyonê miyanêni}} terefê {{PLURAL:$2|yew karberi|$2 karberan}} nêmocno)',
'diff-multi-manyusers' => '({{PLURAL:$1|jew timar kerdışo qıckeko|$1 timar kerdışo qıckeko}} timar kerdo, $2 {{PLURAL:$2|Karber|karberi}} memocne)',
'difference-missing-revision' => 'Ferqê {{PLURAL:$2|Yew rewizyonê|$2 rewizyonê}} {{PLURAL:$2|dı|dı}} ($1) sero çıniyo.

No normal de werênayış dê pelanê besterneyan dı ena xırabin asena.
Detayê besternayışi [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} tiya dı] aseno.',

# Search results
'searchresults' => 'Neticeyê geyrayışi',
'searchresults-title' => 'Qandê "$1" neticeyê geyrayışi',
'searchresulttext' => 'Zerrey {{SITENAME}} de heqa cıgeyrayışi de seba melumat gırewtışi, şenay qaytê [[{{MediaWiki:Helppage}}|{{int:help}}]] ke.',
'searchsubtitle' => 'Tı semedê \'\'\'[[:$1]]\'\'\' cıgeyra. ([[Special:Prefixindex/$1|pelê ke pêro be "$1" ra dest niyaê pıra]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|pelê ke pêro be "$1"\' ra gırê xo esto]])',
'searchsubtitleinvalid' => "Tı cıgeyra qe '''$1'''",
'toomanymatches' => 'Zêde teki (zewci) peyser çarnay, şıma rê zehmet, be persê do bin ra bıcerrebnên.',
'titlematches' => 'tekê (zewcê) sernamey pele',
'notitlematches' => 'Tekê (zewcê) sernamey pele çıniyê.',
'textmatches' => 'Tekê (zewcê) nuştey pele',
'notextmatches' => 'tekê (zewcê) nuştey pele çıniyê',
'prevn' => '{{PLURAL:$1|$1}} verên',
'nextn' => '{{PLURAL:$1|$1}} peyên',
'prevn-title' => '$1o verên  {{PLURAL:$1|netice|neticeyan}}',
'nextn-title' => '$1o ke yeno {{PLURAL:$1|netice|neticey}}',
'shown-title' => 'bimocne $1î  {{PLURAL:$1|netice|neticeyan}} ser her pel',
'viewprevnext' => '($1 {{int:pipe-separator}} $2) ($3) bıvênên',
'searchmenu-legend' => 'bıgeyre tercihan (sae bıke)',
'searchmenu-exists' => "''Ena 'Wikipediya de ser \"[[:\$1]]\" yew pel esto'''",
'searchmenu-new' => "''Na Wiki de pelay \"[[:\$1]]\" vıraze!'''",
'searchhelp-url' => 'Help:Tedeestey',
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|pê eno prefix ser pelan de bigêre]]',
'searchprofile-articles' => 'Pelê tedeestey',
'searchprofile-project' => 'Pelê yardım u procey',
'searchprofile-images' => 'Multimedya',
'searchprofile-everything' => 'Her çi',
'searchprofile-advanced' => 'Raverşiyaye',
'searchprofile-articles-tooltip' => '$1 de bigêre',
'searchprofile-project-tooltip' => '$1 de bigêre',
'searchprofile-images-tooltip' => 'Dosya cı geyr',
'searchprofile-everything-tooltip' => 'Tedeestey hemine cı geyre (pelanê mınaqeşey zi tey)',
'searchprofile-advanced-tooltip' => 'qe cayê nimeyî bigêre',
'search-result-size' => '$1 ({{PLURAL:$2|1 çekuyo|$2 çekuyê}})',
'search-result-category-size' => '{{PLURAL:$1|1 eza|$1 ezayan}} ({{PLURAL:$2|1 kategoriyê bini|$2 kategirayanê binan}}, {{PLURAL:$3|1 dosya|$3 dosyayan}})',
'search-result-score' => 'Eleqa: $1%',
'search-redirect' => '(ber $1)',
'search-section' => '(qısmê $1)',
'search-suggest' => 'To va: $1',
'search-interwiki-caption' => 'Projey Bıray',
'search-interwiki-default' => '$1 neticeyan:',
'search-interwiki-more' => '(hona)',
'search-relatedarticle' => 'Eqreba',
'mwsuggest-disable' => 'Tewsiyay AJAXi bıgê',
'searcheverything-enable' => 'cayê nameyê hemi de bigêre',
'searchrelated' => 'eleqeyın',
'searchall' => 'pêro',
'showingresults' => "#$2 netican ra {{PLURAL:$1|'''1''' netica|'''$1''' neticey}} cêr deyê.",
'showingresultsnum' => "'''$2''' netican ra nata  {{PLURAL:$3|'''1''' netice|'''$3''' neticeyê}} cêrde liste biyê.",
'showingresultsheader' => "{{PLURAL:$5|Neticeyê '''$1''' of '''$3'''|Neticeyanê '''$1 - $2''' hetê '''$3'''}} qe '''$4'''",
'nonefound' => "'''Teme''': Teyna tay namecayan cıgeyro beno.
Pe verbendi ''all:'', vaceyê xo bıvurni ki contenti hemi cıgeyro (pelanê mınaqeşe, templatenan, ucb.) ya zi cıgeyro ser namecay ki tı wazeni.",
'search-nonefound' => 'Zey perskerdışê şıma netice nêvêniya.',
'powersearch' => 'Cıgeyrayışo hera',
'powersearch-legend' => 'Cıgeyrayışo hera',
'powersearch-ns' => 'Cayanê nameyan de cıgeyrayış:',
'powersearch-redir' => 'Listeya hetenayışan',
'powersearch-field' => 'Seba cı seyr ke',
'powersearch-togglelabel' => 'Qontrol ke:',
'powersearch-toggleall' => 'Pêro',
'powersearch-togglenone' => 'Çıniyo',
'search-external' => 'Cıgeyrayışê teberi',
'searchdisabled' => '{{SITENAME}} no keyepel de cıgerayiş muweqqet bıryayo. no benatê de şıma pê Google eşkeni zerreyê {{SITENAME}} de cıgerayiş bıkeri.',

# Quickbar
'qbsettings' => 'Çûwo pêt',
'qbsettings-none' => 'Çıniyo',
'qbsettings-fixedleft' => 'Rêcaene çhep',
'qbsettings-fixedright' => 'Rêcaene raşt',
'qbsettings-floatingleft' => 'rêcaene çhep',
'qbsettings-floatingright' => 'rêcaene raşt',
'qbsettings-directionality' => 'Sabito, hereket de dosya da zıwan de şımaya gıredayeyo',

# Preferences page
'preferences' => 'Tercihi',
'mypreferences' => 'Tercihi',
'prefs-edits' => 'Amarê vurnayışan:',
'prefsnologin' => 'Şıma cıkewtış nêvıraşto',
'prefsnologintext' => 'Şıma gani be <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} cikewte]</span> ke tercihanê karberi xo eyar bıkerê.',
'changepassword' => 'Parola bıvurne',
'prefs-skin' => 'Çerme',
'skin-preview' => 'Verasayış',
'datedefault' => 'Tercih çıniyo',
'prefs-beta' => 'Xacetê beta',
'prefs-datetime' => 'Tarix u wext',
'prefs-labs' => 'Xacetê labs',
'prefs-user-pages' => 'Pela Karberi',
'prefs-personal' => 'Pela karberi',
'prefs-rc' => 'Vurnayışê peyêni',
'prefs-watchlist' => 'Lista seyrkerdışi',
'prefs-watchlist-days' => 'Rocê ke lista seyrkerdışi de bêrê ramocnaene',
'prefs-watchlist-days-max' => 'tewr vêşi $1 {{PLURAL:$1|roci|roci}}',
'prefs-watchlist-edits' => 'tewr zêde amarê vurnayışi ke lista seyrkerdışia herakerdiye de bıasê:',
'prefs-watchlist-edits-max' => 'Amerê tewr zafî: 1000',
'prefs-watchlist-token' => 'Lista seyrkerdışia nışani:',
'prefs-misc' => 'ê bini',
'prefs-resetpass' => 'Parola bıvurne',
'prefs-changeemail' => 'E-postay bıvurne',
'prefs-setemail' => 'E-posta adresiyê xo saz kerê',
'prefs-email' => 'Tercihê e-maili',
'prefs-rendering' => 'Asayış',
'saveprefs' => 'Qeyd ke',
'resetprefs' => 'Vurnayışê ke qeyd nêbiy, pak ke',
'restoreprefs' => 'Sazanê hesıbyaya pêron newe dere barke',
'prefs-editing' => 'Cay pela nustısi',
'prefs-edit-boxsize' => 'Ebatê pencereyê vurnayîşî.',
'rows' => 'Xeti:',
'columns' => 'Estûni:',
'searchresultshead' => 'Cı geyre',
'resultsperpage' => 'Serê pele  amarê cıkewtoğan:',
'stub-threshold' => 'Baraj ke <a href="#" class="stub">stub link</a> ho şekil dano (bîtî):',
'stub-threshold-disabled' => 'Astengın',
'recentchangesdays' => 'Rocê ke vurnayışanê peyênan de bıasê:',
'recentchangesdays-max' => 'Tewr zaf $1 {{PLURAL:$1|roc|roci}}',
'recentchangescount' => 'Amarê vurnayışê ke hesıbyaye deye bımocneyê:',
'prefs-help-recentchangescount' => 'Ney de vurnayışê peyêni, tarixê pelan u cıkewteni asenê.',
'prefs-help-watchlist-token' => 'Eke no ca pê kılito dızdeni/miyanki pırr bo, lista şımawa seyrkerdışi rê yew cıresnayışê RSSi vıraziyeno.
Her kamo ke nê kılitê nê cay zaneno, şeno lista şımawa seyrkerdışi ki bıwano, coke ra yewo sağlem weçine.
Etıya şıma rê yew kılito raştameo ke şıma şenê bıgurenê/bıxebetnê: $1',
'savedprefs' => 'Tecihê şıma qeyd biy.',
'timezonelegend' => 'Warey saete:',
'localtime' => 'saeta mehelliye:',
'timezoneuseserverdefault' => 'Zey karkerdışê Wiki ($1)',
'timezoneuseoffset' => 'Zewbina (offseti beli bıke)',
'timezoneoffset' => 'Offset¹:',
'servertime' => 'Wextê serveri:',
'guesstimezone' => 'Browser ra pırr ke',
'timezoneregion-africa' => 'Afrika',
'timezoneregion-america' => 'Amerika',
'timezoneregion-antarctica' => 'Antarktika',
'timezoneregion-arctic' => 'Arktik',
'timezoneregion-asia' => 'Asya',
'timezoneregion-atlantic' => 'Okyanuso Atlantik',
'timezoneregion-australia' => 'Awıstralya',
'timezoneregion-europe' => 'Ewropa',
'timezoneregion-indian' => 'Okyanuso Hind',
'timezoneregion-pacific' => 'Okyanuso Pasifik',
'allowemail' => 'Karberê bini wa bışê mı rê e-posta bırışê.',
'prefs-searchoptions' => 'Cı geyre',
'prefs-namespaces' => 'Cayê namam',
'defaultns' => 'Eke heni, enê cayanê namey de cı geyre (sae ke):',
'default' => 'qısur',
'prefs-files' => 'Dosyey',
'prefs-custom-css' => 'CSSê xasi',
'prefs-custom-js' => 'JSê xasi',
'prefs-common-css-js' => 'CSS/JavaScript pê şablonanê peran de pay biya:',
'prefs-reset-intro' => 'ena pele de şıma tercihanê xo şenê bıçarnê be tercihanê keyepelê ke verê coy eyar biy.
Na game tepeya nêerziyena.',
'prefs-emailconfirm-label' => 'Tesdiqiya E-posta:',
'prefs-textboxsize' => 'Ebatê pencerey vurnayışi',
'youremail' => 'E-Mail (mecbur niyo) *:',
'username' => 'Namey karberi:',
'uid' => 'Namey karberi:',
'prefs-memberingroups' => 'Ezayê {{PLURAL:$1|grube|gruban}}:',
'prefs-memberingroups-type' => '$1',
'prefs-registration' => 'Wextê qeydbiyayışi',
'prefs-registration-date-time' => '$1',
'yourrealname' => 'Nameyo raştay',
'yourlanguage' => 'Zıwan:',
'yourvariant' => 'Varyante miyandê zuwani:',
'prefs-help-variant' => 'Zerrey ena viki mocnayışi rê varyant yana ortografi re şıre tercihan dê xo.',
'yournick' => 'imza:',
'prefs-help-signature' => 'mesajê ke pelê werenayişi de gani pê ney "<nowiki>~~~~</nowiki>" imza bıbi.',
'badsig' => 'Îmzayê tu raşt niyo.
Etiketê HTMLî kontrol bike.',
'badsiglength' => 'İmzayê şıma zaf dergo.
$1 gani bınê no {{PLURAL:$1|karakter|karakter}} de bıbo.',
'yourgender' => 'Cınsiyet:',
'gender-unknown' => 'Cınsiyet nêvato',
'gender-male' => 'Camêrd',
'gender-female' => 'Cıniye',
'prefs-help-gender' => 'keyfiyo: sofware qey adersê cinsiyet şuxulneno, no malumat umumiyo.',
'email' => 'E-posta',
'prefs-help-realname' => 'Nameyo raşt waştena şıma rê mendo.
Eka tu wazene ke nameyo raşt xo bide, ma nameyo raşt ti iştirakanê ti de mocnenê.',
'prefs-help-email' => 'Dayışê adresa e-postey keyfiyo, labelê seba eyarê parola lazıma, wexto ke şıma naye xo vira kerê.',
'prefs-help-email-others' => 'Şıma şenê weçinê ke ê bini be yew gırey pela şımaya karberi ya zi pela werênayışi sera şıma de ebe e-poste irtıbat kewê.
Kaberê bini ke şıma de kewti irtıbat, adresa e-postey şıma eşkera nêbena.',
'prefs-help-email-required' => 'E-mail adrese mecburiya.',
'prefs-info' => 'Melumato bıngeh',
'prefs-i18n' => 'Beynelmılelkerdış',
'prefs-signature' => 'İmza',
'prefs-dateformat' => 'Formatê tarixi',
'prefs-timeoffset' => 'Wext offset',
'prefs-advancedediting' => 'Tercihê raverberdey',
'prefs-advancedrc' => 'Tercihê raverberdey',
'prefs-advancedrendering' => 'Tercihê raverberdey',
'prefs-advancedsearchoptions' => 'Tercihê raverberdey',
'prefs-advancedwatchlist' => 'Tercihê raverberdey',
'prefs-displayrc' => 'Tercihan bımocne',
'prefs-displaysearchoptions' => 'Weçinayışê mocnayışi',
'prefs-displaywatchlist' => 'Weçinayışê mocnayışi',
'prefs-diffs' => 'Ferqi',

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => 'e-posta adresi raştayo',
'email-address-validity-invalid' => 'e-postayo raştay defiye de',

# User rights
'userrights' => 'İdarey heqanê karberan',
'userrights-lookup-user' => 'Grubanê karberi/karbere idare bıke',
'userrights-user-editname' => 'Yew nameyê karberi cı kewe:',
'editusergroup' => 'Grupanê karberi/karbere bıvurne (bıbedelne)',
'editinguser' => "'''[[User:$1|$1]]''' keno weziyetê $2'i bıvurno",
'userrights-editusergroup' => 'Grubanê karberi/karbere sero bıgureye (bıxebetiye)',
'saveusergroups' => 'Grubanê karberi qeyd bıke',
'userrights-groupsmember' => 'Ezayê:',
'userrights-groupsmember-auto' => 'Ezao daxıl/ezaa daxıle ê:',
'userrights-groups-help' => 'şıma şenê grubanê nê karberi/na karbere, oyo/aya ke tede, bıvurnê:
* qutiya ke nışankerdiya, mocnena ke karber/e na grube dero/dera.
* qutiya ke nışankerdiye niya, mocnena ke karber/ na grube de niyo/niya.
* Yew estare * mocneno ke, gruba ke şıma kerda ra ser (daxıl kerda), şıma nêşenê wedarê/hewa dê ya ki dêmlaşta/tersê cı.',
'userrights-reason' => 'Sebeb:',
'userrights-no-interwiki' => 'Heqa şıma çıniya ke heqanê karberanê Wikipediyanê binan sero bıgureyê.',
'userrights-nodatabase' => 'Database $1 çıniyo ya zi mehelli niyo.',
'userrights-nologin' => 'Eke şıma wazenê ke heqa karberi/karbere cı dê, şıma gani be [[Special:UserLogin|cikewtiye]] pê yew hesabê idarekeran cı kewê',
'userrights-notallowed' => 'Hesabdê şımadı heqanê xo hewadayış u xorê heq dekerdış çıno.',
'userrights-changeable-col' => 'Grubê ke şıma şenê bıvurnê',
'userrights-unchangeable-col' => 'Grubê ke şıma nêşenê bıvurnê',
'userrights-irreversible-marker' => '$1*',

# Groups
'group' => 'Grube:',
'group-user' => 'Karberi',
'group-autoconfirmed' => 'Karberê ke xob xo biyê araşt',
'group-bot' => 'Boti',
'group-sysop' => 'İdarekari',
'group-bureaucrat' => 'Burokrati',
'group-suppress' => 'Çımpawıteni',
'group-all' => '(pêro)',

'group-user-member' => '{{GENDER:$1|karber}}',
'group-autoconfirmed-member' => '{{GENDER:$1|Karberê ke xob xo biyê araşt}}',
'group-bot-member' => '{{GENDER:$1|bot}}',
'group-sysop-member' => '{{GENDER:$1|İdarekar}}',
'group-bureaucrat-member' => '{{GENDER:$1|buroqrat}}',
'group-suppress-member' => '{{GENDER:$1|Temaşekar}}',

'grouppage-user' => '{{ns:project}}:Karberi',
'grouppage-autoconfirmed' => '{{ns:project}}:Karberê ke xob xo biyê araşt',
'grouppage-bot' => '{{ns:project}}:Boti',
'grouppage-sysop' => '{{ns:project}}:İdarekeri',
'grouppage-bureaucrat' => '{{ns:project}}:Burokrati',
'grouppage-suppress' => '{{ns:project}}:Qontrol',

# Rights
'right-read' => 'Pelan bıwane',
'right-edit' => 'Pele bıvurne',
'right-createpage' => 'Pele vıraze (pelê ke ê werênayışi niyê)',
'right-createtalk' => 'Pela werênayışi vıraze',
'right-createaccount' => 'Hesabê karberi vıraze',
'right-minoredit' => 'Vurnayışan qıckek nışan bıde.',
'right-move' => 'Pele bere',
'right-move-subpages' => 'Pele be bınpelanê cı ra pia bere',
'right-move-rootuserpages' => 'Pelanê kaberiê rıstımi bere',
'right-movefile' => 'Dosyan bere',
'right-suppressredirect' => 'Wexto ke pelan benê, pelanê çımey ra neql mevıraze',
'right-upload' => 'Dosya bar bıke',
'right-reupload' => 'Dosyeyê ke estê, inan serde bınuse',
'right-reupload-own' => 'Dosyeyê ke to bar kerdi, inan sero bınuse',
'right-reupload-shared' => 'Dosyeyê ke ambarê medyao barekerde de, inan mehelli wedare',
'right-upload_by_url' => 'Yew URL ra dosyan bar bıke',
'right-purge' => 'Verde ju pela araşt kerdışi hafızay sita besterne',
'right-autoconfirmed' => 'Pelanê ke nême kılit biyê, inan bıvurne',
'right-bot' => 'Zey yew karê xoserkerdey be',
'right-nominornewtalk' => 'Pelanê werênayışan rê vurnayışê qıckeki çıniyê, qutiya mesacanê newiyan bıgurene',
'right-apihighlimits' => 'Persanê API de sinoranê berzêran bıgurene',
'right-writeapi' => 'İstıfadey APIyê nuştey',
'right-delete' => 'Pele bestere',
'right-bigdelete' => 'Pelanê be tarixanê dergan bestere',
'right-deletelogentry' => 'besternayış u mebesternayışa re qeyde definayışê xısusi',
'right-deleterevision' => 'Vurnayışê xısusiyê ke ê pelanê, inan bestere ya zi peyser bia',
'right-deletedhistory' => 'Qeydanê tarixanê esterıteyan de qayt ke, bê nuştey inan',
'right-deletedtext' => 'Mabênê newede vurnayışanê esterıtiyan de qaytê nuştey esterıtey u vurnayışan ke',
'right-browsearchive' => 'Pelanê esterıteyan bıgeyre',
'right-undelete' => 'Jû pela esterıtiye peyser bia',
'right-suppressrevision' => 'İdarekeran ra dızdeni/miyanki, newede vurnayışan de qayt ke u newede vıraze',
'right-suppressionlog' => 'Rocekanê xasan bıvêne',
'right-block' => 'Karberanê binan karê vurnayışi ra bloke bıke',
'right-blockemail' => 'Yew karberê erşawıtışê/rıştena e-maili ra bloke bıke',
'right-hideuser' => 'Yew namey karberi  şari ra dızdeni/miyanki bloke bıke',
'right-ipblock-exempt' => 'Blokanê IPi, oto-blokan u blokanê menzıli ra ravêre',
'right-proxyunbannable' => 'Blokanê otomatikiê proksiyan ra ravêre',
'right-unblockself' => 'İnan ake',
'right-protect' => 'Sewiyanê pawıtışi (mıhafezey) bıvurne u pelanê kılitbiyaiyan sero bıgureye.',
'right-editprotected' => 'Pelanê pawıtiyan sero bıgureye (bê pawıtena kaskadi (game be game))',
'right-editinterface' => 'Interfaceê karberi sero bıgureye',
'right-editusercssjs' => 'CSS u dosyanê JSiê karberanê binan sero bıgureye',
'right-editusercss' => 'Dosyanê CSSiê karberanê binan sero bıgureye',
'right-edituserjs' => 'Dosyanê JSiê karberanê binan sero bıgureye',
'right-rollback' => 'Lez/herbi vurnayışanê karberê peyêni tekrar bıke, oyo ke yew be yew pelê sero gureyao',
'right-markbotedits' => 'Vurnayışanê peyd ameyan, vurnayışê boti deye nışan kerê',
'right-noratelimit' => 'Sinoranê xızi (rate limit) ra tesir nêbi',
'right-import' => 'Pelan wikiyanê binan ra bia',
'right-importupload' => 'Pelî dosya bar kerdişî ra import bike',
'right-patrol' => 'Vurnayîşanê karberê binî nîşan bike ke patrol biyê',
'right-autopatrol' => 'Vurnayîşanê xo otomatik nîşan bike ke patrol biyê',
'right-patrolmarks' => 'Vurnayîşanê peniyî nîşan patrol biyê bivîne',
'right-unwatchedpages' => 'Yew listeyê pelanê seyrnibiye bivîne',
'right-mergehistory' => 'Tarixê pelan yew ke',
'right-userrights' => 'Heqanê karberi pêro bıvurne',
'right-userrights-interwiki' => 'Heqqa karberanê ke ho wîkîyo binî de ey bivurne',
'right-siteadmin' => 'Database kilit bike u a bike',
'right-override-export-depth' => 'Peleyanê ke tede linkanê 5 ra zafyer estê ay export bike',
'right-sendemail' => 'Karberanê binî ra e-mail bişirav',
'right-passwordreset' => 'E-postayanê parola reset kerdışa vineno',

# User rights log
'rightslog' => 'Qeydê heqanê karberi',
'rightslogtext' => 'Ena listeyê loganê ke heqqa karbaranî mucneno.',
'rightslogentry' => 'eza biyayişê grupî $1 ra $2 rê $3î bivurne',
'rightslogentry-autopromote' => '$2 otomatikmen gırdkerdışi ra kerd $3.',
'rightsnone' => '(çino)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'ena pela wanayış',
'action-edit' => 'ena pela bıvurnê',
'action-createpage' => 'pelan bıvıraze',
'action-createtalk' => 'pelanê werênayışi bıvıraze',
'action-createaccount' => 'hesabê nê karberi bıvıraze',
'action-minoredit' => 'nê vurnayışi be qıckek işaret ke',
'action-move' => 'ena pele bere',
'action-move-subpages' => 'ena pele, u pelanê daê bınênan bere',
'action-move-rootuserpages' => 'pelanê karberiyê bıngeyan bere',
'action-movefile' => 'ena dosya bere',
'action-upload' => 'ena dosya bar ke',
'action-reupload' => 'dosyayê ke database de esto ser ey binuse',
'action-reupload-shared' => 'dosyayê ki ho embarê medyayî de esto ser ay binusne',
'action-upload_by_url' => 'Ena dosya yew URL ra bar bike',
'action-writeapi' => 'ser nuşte API gure bike',
'action-delete' => 'ena perer besternê',
'action-deleterevision' => 'nê çımraviyarnayışi bıestere',
'action-deletedhistory' => 'tarixê ena pel ki estereyî biya, ey bivine',
'action-browsearchive' => 'pelanê esterıteyan bıgeyre',
'action-undelete' => 'ena pele reyna biyere',
'action-suppressrevision' => 'revizyone ki nimnaye biye reyna bivîne u restore bike',
'action-suppressionlog' => 'ena logê xasî bivîne',
'action-block' => 'enê karberi vurnayışi ra bıreyne',
'action-protect' => 'seviyeyê pawitişî se ena pele bivurne',
'action-rollback' => 'Lez/herbi vurnayışanê karberê peyêni tekrar bıke, oyo ke yew be yew pelê sero gureyao',
'action-import' => 'ena pele yewna wiki ra azere de',
'action-importupload' => 'ena pele yew dosyayê bar kerdişî ra import bike',
'action-patrol' => 'vurnayîşê karberanê binî nişan bike patrol biye',
'action-autopatrol' => 'vurnayîşê xoye nişan bike ke belli biyo patrol biye',
'action-unwatchedpages' => 'listeyê pelanê seyirnibiya bivîne',
'action-mergehistory' => 'tarixê ena pele yew ke',
'action-userrights' => 'heqqa karberanê hemî bivurne',
'action-userrights-interwiki' => 'heqqa karberanê ke wikiyê binî de hemî bivurne',
'action-siteadmin' => 'database kilit bike ya zi a bike',
'action-sendemail' => 'e-posta bırşe',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|fın vurna|fıni vurna}}',
'recentchanges' => 'Vurnayışê peyêni',
'recentchanges-legend' => 'Tercihê vurnayışanê peyênan',
'recentchanges-summary' => 'Ena pele de wiki sero vurnayışanê peyênan teqib ke.',
'recentchanges-feed-description' => 'Ena feed dı vurnayişanê tewr peniyan teqip bık.',
'recentchanges-label-newpage' => 'Enê vurnayışi pelaya newi vıraşt',
'recentchanges-label-minor' => 'Eno yew vurnayışo qıckeko',
'recentchanges-label-bot' => 'Yew boti xo het ra no vurnayış vıraşto',
'recentchanges-label-unpatrolled' => 'Eno vurnayış hewna dewriya nêbiyo',
'rcnote' => "Bıni dı {{PLURAL:$1|'''1''' vurnayış|peyni de '''$1''' vurnayışi estê}} {{PLURAL:$2|roc|'''$2''' roci}}, hetana $5, $4.",
'rcnotefrom' => "Cêr de '''$2''' ra nata vurnayışiyê asenê (tewr vêşi <b> '''$1'''</b> asenê).",
'rclistfrom' => '$1 ra vurnayışanê neweyan bımocne',
'rcshowhideminor' => 'Vurnayışanê werdiyan $1',
'rcshowhidebots' => 'Botan $1',
'rcshowhideliu' => 'Karberanê qeydınan $1',
'rcshowhideanons' => 'Karberanê anoniman $1',
'rcshowhidepatr' => '$1 vurnayışê ke dewriya geyrayê',
'rcshowhidemine' => 'Vurnayışanê mı $1',
'rclinks' => 'Peyniya $2 rocan de $1 vurnayışan bımocne <br />$3',
'diff' => 'ferq',
'hist' => 'verên',
'hide' => 'Bınımne',
'show' => 'Bımocne',
'minoreditletter' => 'q',
'newpageletter' => 'N',
'boteditletter' => 'b',
'unpatrolledletter' => '!',
'number_of_watching_users_pageview' => '[$1 ho seyr keno {{PLURAL:$1|karber|karberî}}]',
'rc_categories' => 'Kategoriyanî rê limît bike (pê "|" ciya bike)',
'rc_categories_any' => 'Her yew',
'rc-change-size' => '$1',
'rc-change-size-new' => 'Vurnayışa dıma $1 {{PLURAL:$1|bayt|bayt}}',
'newsectionsummary' => '/* $1 */ qısımo newe',
'rc-enhanced-expand' => 'detayan bımoc (requires JavaScript)',
'rc-enhanced-hide' => 'Detaya bınımnê',
'rc-old-title' => '"$1"i orcinalê cı vıraşt',

# Recent changes linked
'recentchangeslinked' => 'Vurnayışê eleqeyıni',
'recentchangeslinked-feed' => 'Vurnayışê eleqeyıni',
'recentchangeslinked-toolbox' => 'Vurnayışê eleqeyıni',
'recentchangeslinked-title' => 'vurnayışan ser "$1"',
'recentchangeslinked-noresult' => 'Pelanê ke link biye ey vurnayîşî çino.',
'recentchangeslinked-summary' => "Lista cêrêne, pela bêlikerdiye rê (ya zi karberanê kategoriya bêlikerdiye rê) pelanê gırêdaoğan de lista de vurnayışê peyênana.
[[Special:Watchlist|Lista şımawa seyrkedışi de]] peli be nuşteyo '''qolınd''' bêli kerdê.",
'recentchangeslinked-page' => 'Nameyê pele:',
'recentchangeslinked-to' => 'Pelayan ke ena pela ri gire bi, ser ayi vurnayışi bımoc',

# Upload
'upload' => 'Dosya bar ke',
'uploadbtn' => 'Dosya bar ke',
'reuploaddesc' => 'Barkerdışi iptal ke u peyser şo formê barkerdışi',
'upload-tryagain' => 'Deskripyonê dosyayî ke vurîya ey qeyd bike',
'uploadnologin' => 'Nicikewte',
'uploadnologintext' => 'Ti gani [[Special:UserLogin|cikewte]] biyo ke dosya bar bike.',
'upload_directory_missing' => 'Direktorê dosyayê ($1)î biyo vînî u webserver de nieşkeno viraziye.',
'upload_directory_read_only' => 'Direktorê dosyayê ($1)î webserver de nieşkeno binuse.',
'uploaderror' => 'Ğeletê bar kerdişî',
'upload-recreate-warning' => "'''Diqet: Yew dosya pê ena name wedariya ya zi vurniya.'''

Logê wedariyayiş u berdişi seba ena pele a ti ra xezir kerda:",
'uploadtext' => "Qey barkerdişê dosyayî, formê cêrinî bişuxulne.
Dosyayê ke vera cû bar biyê eke şima qayîl e ney dosyayan bivînê ya zî bigerî biewnê[[Special:FileList|listeyê dosyayê bar bîyaye]] (tekrar) bar bîyaye [[Special:Log/upload|rocaneyê barkerdişî]] de, hewn a şîyaye zî tîya de [[Special:Log/delete|rocaneyê hewn a kerdişî]] pawiyene.

wexta şima qayîl e yew peli re dosya bierzî, formanê cêrinan ra yewi bişuxulne;
* Qey xebitnayişê dosyayî: '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Dosya.jpg]]</nowiki></code>'''
*Heto çep de zerreyê yew qutî de, qey xebitnayişi 'nuşteyê binîn' û 200 pikseli: '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Dosya.png|200px|thumb|left|alt metin]]</nowiki></code>'''
* Dosya memocın, dosya te direk gırey bıerz: '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Dosya.ogg]]</nowiki></code>'''",
'upload-permitted' => 'Tipanê dosyayi ke izin ey estê: $1.',
'upload-preferred' => 'Tipanê dosyayi ke tercihe ey estê: $1',
'upload-prohibited' => 'Babetê dosyayanê tometebiyayeyan: $1.',
'uploadlog' => 'cıkewtışê barkerdışi',
'uploadlogpage' => 'Cıkewtışê bar-kerdışi',
'uploadlogpagetext' => 'cêr de [[Special:NewFiles|listeyê dosyayan]] estî.',
'filename' => 'Namey Dosya',
'filedesc' => 'Xulasa',
'fileuploadsummary' => 'Xulasa:',
'filereuploadsummary' => 'Vurnayîşê dosyayî:',
'filestatus' => 'Weziyetê heqa telifi:',
'filesource' => 'Çıme:',
'uploadedfiles' => 'Dosyayê ke bar biye',
'ignorewarning' => 'Îkazi kebul meke u dosya reyna bar bike',
'ignorewarnings' => 'Îkazi kebul meke',
'minlength1' => 'Nameyanê dosyayî de gani bî ezamî yew herf est biyê.',
'illegalfilename' => '"$1" no nameyê dosya de tayê karakteri nêşuxulyenî. newe ra tesel bıkerê',
'filename-toolong' => 'Nameyê dosyayan 240 bayt ra derg do nêbo.',
'badfilename' => "Nameyanê dosyayî ''$1'' rê vurneyî biye.",
'filetype-mime-mismatch' => 'Derg kerdıştê Dosyada ".$1" u ($2) MIME tipiya cıya pêro nina.',
'filetype-badmime' => 'Dosyaye ke tipê MIME "$1"î de bar nibeno.',
'filetype-bad-ie-mime' => 'na dosya bar nebena çunke Internet Explorer na dosya "$1" zerarın vinena.',
'filetype-unwanted-type' => "'''\".\$1\"''' na tewırê dosyayi nêwazyena. pêşniyaz biyaye {{PLURAL:\$3|tewırê dosyayi|tewırê dosyayi}} \$2.",
'filetype-banned-type' => '\'\'\'".$1"\'\'\' {{PLURAL:$4|Ena babeta dosya qebul ne vinena|Ena babeta dosya qebul ne vinena|Ena babeta dosya qebul ne vinena}}. Eke cırê izin deyayo $2. {{PLURAL:$3|Babatan dosyayan|babeta dosyayan}}',
'filetype-missing' => 'Ena dosya de extention (ze ".jpg") çini yo.',
'empty-file' => 'Dosyaya ke şıma rışta venga.',
'file-too-large' => 'Dosyaye ke şıma rışta zaf gırda.',
'filename-tooshort' => 'Namayê dosyayi zaf kilm a.',
'filetype-banned' => 'Tipê ena dosya qedexe biya.',
'verification-error' => 'Ena dosya taramayê dosyayi temam nikena.',
'hookaborted' => 'Vurnayişê tu ke to cerbna pê yew çengal ra terkneya.',
'illegal-filename' => 'Ena nameyê dosyayi kebul nibena.',
'overwrite' => 'Ser yew dosyayê ke hama esta, ser ey qeyd nibena.',
'unknown-error' => 'Yew xeteyê nizanyeni biya.',
'tmp-create-error' => 'Yew dosyayê gecici niviraziyeya.',
'tmp-write-error' => 'Dosyayê gecici de xeta biya.',
'large-file' => 'gırdîyê dosyayan re, na gırdî $1 ra wet pêşniyazi çino;
gırdîyê na dosyayi $2.',
'largefileserver' => 'Ena dosya zaf girde ke server kebul nikeno.',
'emptyfile' => 'dosya ya ke şıma bar kerda veng asena, nameyê dosyayi şaş nusyaya belka.',
'windows-nonascii-filename' => 'Na wiki namen de dosyayan de xısusi karaxtera karkerdışa peşti nêdana.',
'fileexists' => 'no name de yew dosya ca ra esta.
Eke şıma emin niyê bıvurni bıewne na dosya<strong>[[:$1]]</strong>
[[$1|thumb]]',
'filepageexists' => 'qey na dosya pelê eşkera kerdışi <strong>[[:$1]]</strong> na adresi de ca ra vıraziyayo labele no name de yew dosya nêasena.
kılmnuşteyê şıma nêasena eke şıma qayili bıvini gani şıma pê dest bıvurni
[[$1|resimo qıc]]',
'fileexists-extension' => 'zey no nameyê dosyayi yewna nameyê dosyayi esta: [[$2|thumb]]
* dosyaya ke bar biya: <strong>[[:$1]]</strong>
* dosyaya ke ca ra esta: <strong>[[:$2]]</strong>
kerem kere yewna name bıvıcinê',
'fileexists-thumbnail-yes' => "na dosya wina asena ke versiyona yew resmê qıc biyayeya ''(thumbnail)''. [[$1|thumb]]
kerem kerê <strong>[[:$1]]</strong> na dosya konrol bıkerê .",
'file-thumbnail-no' => "nameyê na dosyayi pê ney <strong>$1</strong> dest keno pê.
na manena ke versiyona yew resmê qıc biyaye ya ''(thumbnail)''",
'fileexists-forbidden' => 'no name de yew dosya ca ra esta u ser nuştış nêbeno.
eke şıma qayile dosyaya xo bar keri tepiya agerê u yew nameyo newe bınusi. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'no name de yew dosya hewza ortaxi de ca ra esta.
eke şıma hhene zi qayili dosyaya xo bar keri ager3e u newe yew name bışuxulnê. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'Ena pel yew kopyayê ena {{PLURAL:$1|pel|pelan}} o:',
'file-deleted-duplicate' => 'Jû dosya be zey na dosya ([[:$1]]) verê coy esteriyawa.
Semedê ancia barkerdışi dewamkerdış ra ver tarixê esterışê dosya gani qontrol kerê.',
'uploadwarning' => 'Îkazê bar kerdişî',
'uploadwarning-text' => 'Bînê de deskripyonê dosyayî bivurne u reyna qeyd bike.',
'savefile' => 'Dosya qeyd ke',
'uploadedimage' => '"[[$1]]" bar bi',
'overwroteimage' => 'yew versiyonê newvî ye "[[$1]]"î bar bike',
'uploaddisabled' => 'bar kerdişî iptal biyo',
'copyuploaddisabled' => 'URL bar kerdiş kefiliyeyo.',
'uploadfromurl-queued' => 'Bar kerdişê tu ha sira de vindeno.',
'uploaddisabledtext' => 'Bar kerdişê dosyayî iptal biyo',
'php-uploaddisabledtext' => 'barkerdışê dosyayê PHP nıka çino. kerem kere eyarê file_uploads korol bıkerê.',
'uploadscripted' => 'Ena dosya de yew HTML ya zi kodê scriptî este ke belki browserê webî fam nikeno.',
'uploadvirus' => 'Ena dosya de yew virus estê: Qe detayan: $1',
'uploadjava' => 'Dosya, zerre de cıdı jew Java .class dosyaya ZIP esta.
Dosyayn de Java barkerdışi rê icazet nêdeyê, çıkı emeleya merduman nêbena.',
'upload-source' => 'Dosyayê henî',
'sourcefilename' => 'Nameyê dosyaye çimeyî',
'sourceurl' => "URL'yê Çımi",
'destfilename' => 'Destînasyonê nameyêdosya',
'upload-maxfilesize' => 'Ebatêî dosya tewr girdî: $1',
'upload-description' => 'Deskripsiyonê dosyayî',
'upload-options' => 'Tercihanê bar kerdişî',
'watchthisupload' => 'Ena dosya seyr bike',
'filewasdeleted' => 'no name de yew dosya yew wexto nızdi de bar biya u dıma zi serkaran hewn a kerdo. wexya ke şıma dosya bar keni bıewnê no pel $1.',
'filename-bad-prefix' => "name yo ke şıma bar keni zey nameyê kamerayê dijital î, pê ney '''\"\$1\"''' destpêkeno .
kerem kere yewna nameyo eşkera bıvicinê.",
'filename-prefix-blacklist' => ' #<!-- leave this line exactly as it is --> <pre>
# Syntax is as follows:
#   * Everything from a "#" character to the end of the line is a comment
#   * Every non-blank line is a prefix for typical file names assigned automatically by digital cameras
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # some mobile phones
IMG # generic
JD # Jenoptik
MGP # Pentax
PICT # misc.
 #</pre> <!-- leave this line exactly as it is -->',
'upload-success-subj' => 'bar biyo',
'upload-success-msg' => '[$2] barkerdışê şıma qebul bı. Barkerdışê şımayo itado: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Problem bar bike',
'upload-failure-msg' => '[$1] delal: $2 ra barkerdıştê şıman ra jew xelat vıcyayo.',
'upload-warning-subj' => 'İqazê barkerdışi',
'upload-warning-msg' => 'Barkerdış dê [$2] de xırabey vıcyê. Xırabi timar kerdışi re  peyser şırê  [[Special:Upload/stash/$1|heruna barkerdışi]].',

'upload-proto-error' => 'Porotokol raşt ni yo.',
'upload-proto-error-text' => 'Bar kerdişê durî gani  URLî estbiye ke pe <code>http://</code> ya zi <code>ftp://</code> başli beno.',
'upload-file-error' => 'Xeta daxılkiye',
'upload-file-error-text' => 'Peşkeşwan de wexta yew dosya vıraziyayene xeta bı.
kerem kerê [[Special:ListUsers/sysop|serkari]]de irtibat kewe.',
'upload-misc-error' => 'Ğeletê bar kerdişî nizanyeno',
'upload-misc-error-text' => 'wextê barkerdişî de yew xetayo mechul vırazîya.
konrol bıkeri şıma besteyi? Ya zi şıma karo raşt keni?
Eke problem dewam kerd [[Special:ListUsers/sysop|serkari]] de irtibat kewe.',
'upload-too-many-redirects' => 'Eno URL de zaf redireksiyonî esto.',
'upload-unknown-size' => 'Ebat nizanyeno',
'upload-http-error' => 'Yew ğeletê HTTPî biyo: $1',
'upload-copy-upload-invalid-domain' => 'Na domain ra kopyayê barkerdışanê nêbenê.',

# File backend
'backend-fail-stream' => '$1 nê vırazeyê',
'backend-fail-backup' => '$1 nê wendeyê',
'backend-fail-notexists' => '$1 name dı dosya çına.',
'backend-fail-hashes' => 'Şınasiya dosyaya gırotışê cı nêgêriya.',
'backend-fail-notsame' => 'Zey $1 ju dosya xora  esta.',
'backend-fail-invalidpath' => '$1 rayê da depo kerdışa raştay niya.',
'backend-fail-delete' => '$1 nê besterneyê',
'backend-fail-alreadyexists' => "Dosyay $1'ya nêwanêna",
'backend-fail-store' => '$1 ra $2 berdışo nê wanêno',
'backend-fail-copy' => '$1 ra $2 kopya kerdışena dosyayo nêbeno',
'backend-fail-move' => '$1 ra $2 berdışo nê wanêno',
'backend-fail-opentemp' => 'Teferruatê dosyayo nêwanêno',
'backend-fail-writetemp' => 'Dosyaya idari nênusneyê.',
'backend-fail-closetemp' => 'Dosyaya idari nêracneyê',
'backend-fail-read' => 'Na "$1" dosya nê wanêna',
'backend-fail-create' => 'Dosyay $1 nê vırazıyê',
'backend-fail-maxsize' => 'Dosyay $1 aya nênusneyêna feqet gırdeya cı {{PLURAL:$2|bayta|$2 bayto}}',
'backend-fail-readonly' => 'Depo kerdışê "$1" enewke salt wanêno.Sebebê cı zi:"\'\'$2\'\'"',
'backend-fail-synced' => 'Dosyay " $1 " miyan de depo kerdışeyda cıdı pê nêtepıştey esta',
'backend-fail-connect' => 'Depo kerdışê "$1" peyni de nêgrêdeya.',
'backend-fail-internal' => 'Depo kerdışê "$1" peyni de ju xırabin vıcyê.',
'backend-fail-contenttype' => 'Qandê depo kerdışi zerrey babeta dosya da "$1" nêvineya.',
'backend-fail-batchsize' => 'Depo kerdışê  dosya da $1 {{PLURAL:$1|operasyon de|operasyonê}} cı groto; sinorê  {{PLURAL:$2|operasyoni|operasyona}} $2.',
'backend-fail-usable' => 'Dosyay $1 nênusneyê çıkı ratnayışê cı racnayeyo yana karkerdışe cı kemiyo.',

# File journal errors
'filejournal-fail-dbconnect' => 'Depo kerdış de "$1" qande malumatê gurweynayışi cıya irtibat nêkewiya.',
'filejournal-fail-dbquery' => 'Depo kerdış de "$1" qande malumatê gurweynayışi cıyo nêbeno.',

# Lock manager
'lockmanager-notlocked' => 'Dosyay "$1" kılit nêbiya; kesi kılit nêkerda.',
'lockmanager-fail-closelock' => 'Dosyay kıliti nêracneyê "$1".',
'lockmanager-fail-deletelock' => 'Dosyay kıliti nêbesterneyê "$1".',
'lockmanager-fail-acquirelock' => 'Kılitê cı nêgêriya "$1".',
'lockmanager-fail-openlock' => 'Dosyay kıliti nêracneyê qandê "$1".',
'lockmanager-fail-releaselock' => 'Dosyay kıliti nêvıradeyê "$1".',
'lockmanager-fail-db-bucket' => 'Kılite malumat da sitıl de $1 irtibat kewtışi re bes nêkeno.',
'lockmanager-fail-db-release' => 'Malumatê kıliti nêvıradeyê $1.',
'lockmanager-fail-svr-acquire' => 'Kılitê teqdimkarê $1i nêvêniyenê.',
'lockmanager-fail-svr-release' => 'Wasterê kıliti nêvıradeyê $1.',

# ZipDirectoryReader
'zip-file-open-error' => 'Dosya ZIP kontrol kerdışi re akerdin de jew xırabin amê.',
'zip-wrong-format' => "Dosyaya ke nışan biya dosyay ZIP'i niya.",
'zip-bad' => 'Dosya xırabiya yana zewbi sebeb ra ZIP dosyaya nêwanêna.
Kontrolê emeleyey oyo veş nêbeno.',
'zip-unsupported' => 'Dosya MediaWiki ra ZIP dosyaya nêwanêna yana derganiya ZIP de cı aya pıro nina. Kontrolê emeleyey oyo veş nêbeno.',

# Special:UploadStash
'uploadstash' => 'Nımıtışê barkerdışi',
'uploadstash-summary' => "Na pela barkerdış (yana hewna barbenayış dı) hema hewna wiki'dedosyeyê ke nêpêseryayê enarê rasayış gre danop. Enê dosyay o ke a dosya keno bar tek o şena a dosya bıvino.",
'uploadstash-clear' => 'Dosyeyê ke idareten bıvıryê ena besternê',
'uploadstash-nofiles' => 'Dosyeyê ke idareten bıvıryê çınyê.',
'uploadstash-badtoken' => 'Karkerdışê cı nêbı, muhtemelen desture şımayê timarkerdışi zeman do şıma ravêrdo. Fına bıcerbnê.',
'uploadstash-errclear' => 'Besternayışê dosyayan nêbı',
'uploadstash-refresh' => 'Listanê dosyayan aneweke',
'invalid-chunk-offset' => 'Ofseto nêravyarde',

# img_auth script messages
'img-auth-accessdenied' => 'Cıresnayış vındarnayo.',
'img-auth-nopathinfo' => 'PATH_INFO kemiyo.
Teqdimkerê şıma seba ravurnayışê nê melumati eyar nêkerdo.
Beno ke be CGI-bıngeyın bo u img_auth rê destek nêbeno.
https://www.mediawiki.org/wiki/Manual:Image_Authorization Selahiyetê resımi bıvêne.',
'img-auth-notindir' => 'Patikayê ke ti wazeno direktorê bar biyayişî de çin o.',
'img-auth-badtitle' => '"$1" ra nieşkeno yew seroğê raştî virazî.',
'img-auth-nologinnWL' => 'Ti cikewte ni yo u "$1" listeyo sipê de çin o.',
'img-auth-nofile' => "Dosyayê ''$1''î çin o.",
'img-auth-isdir' => '"$1" şıma gêrenî bıresî tiya.
şıma têna eşkenî bıresi dosya.',
'img-auth-streaming' => '"$1" stream keno.',
'img-auth-public' => "img_auth.php'nin fonksiyonê ney; wiki ra dosyaya xususiyan vetışo.
no wiki bı umumi eyar biyo.
qey pawıtışi, img_auth.php battal verdiyayo.",
'img-auth-noread' => 'Heqqa karberanî çino ke "$1" biwendi',
'img-auth-bad-query-string' => "URL'dı ratnayışo nêravêrde esto.",

# HTTP errors
'http-invalid-url' => 'URL raşt niya: $1',
'http-invalid-scheme' => 'URLan ke pê şablonê "$1"i rê destek cini ya.',
'http-request-error' => 'Waştişê tu HTTP de xeta biya seba yew xetayê ke nizanyeno.',
'http-read-error' => 'Wendişê HTTP de xeta esta.',
'http-timed-out' => 'Waştişê HTTP qediya.',
'http-curl-error' => 'Xetayê URLi: $1',
'http-host-unreachable' => 'URL rê niresa.',
'http-bad-status' => 'Waştişê tu HTTP yew problem biya: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL rê nieşkeno biraso',
'upload-curl-error6-text' => 'URL yo ke nişane biyo nêresiyeno
kerem kerê bıewnê URLyê şıma raşta ya zi bıewnê keyepel akerdeyo.',
'upload-curl-error28' => 'Wextê bar kerdişî qediya',
'upload-curl-error28-text' => 'cewab dayişê no keyepel zaf hereyo.
bıewnê keyepel akerdeyo ya zi bıne vınderê u newe ra tesel bıkerê.
keyepel nıka zaf meşğulo yew dema herayi de newe ra tesel bıkerê.',

'license' => 'Lisans:',
'license-header' => 'Lisansdayış',
'nolicense' => 'Theba nêweçineya',
'license-nopreview' => '(verqeydî çin o)',
'upload_source_url' => '(yew URLê raştî, şar rê akerde yo)',
'upload_source_file' => '(komputerê ti de yew dosya)',

# Special:ListFiles
'listfiles-summary' => 'Na pelaya xısusiya; heme resimê bar biyayeyan mocnena.',
'listfiles_search_for' => 'Qe nameyê medyayî bigêre:',
'imgfile' => 'dosya',
'listfiles' => 'Lista Dosya',
'listfiles_thumb' => 'Resmo qıckek',
'listfiles_date' => 'Deme',
'listfiles_name' => 'Name',
'listfiles_user' => 'Karber',
'listfiles_size' => 'Gırdiye',
'listfiles_description' => 'Sılasnayış',
'listfiles_count' => 'Versiyoni',

# File description page
'file-anchor-link' => 'Dosya',
'filehist' => 'Ravêrdê dosya',
'filehist-help' => 'bıploxne ser yew tarih u aye tarih dı versionê dosya bıvin.',
'filehist-deleteall' => 'hemî biestere',
'filehist-deleteone' => 'bestere',
'filehist-revert' => 'reyna biyere',
'filehist-current' => 'nıkayên',
'filehist-datetime' => 'Tarix/Zeman',
'filehist-thumb' => 'Resmo qıckek',
'filehist-thumbtext' => 'Thumbnail qe versiyonê $1',
'filehist-nothumb' => 'Thumbnail çin o.',
'filehist-user' => 'Karber',
'filehist-dimensions' => 'Ebati',
'filehist-filesize' => 'Ebatê dosyayî',
'filehist-comment' => 'Vacayış',
'filehist-missing' => 'Dosya nieseno',
'imagelinks' => 'Gurenayışê dosya',
'linkstoimage' => 'Ena {{PLURAL:$1|pela|$1 pela}} gıreye ena dosya:',
'linkstoimage-more' => '$1 ra ziyed {{PLURAL:$1|pel|pel}} re gırey dano.
listeya ke ha ver a têna na {{PLURAL:$1|dosyaya ewwili|dosyaya $1 ewwili}} mocnena.
[[Special:WhatLinksHere/$2|pêroyê liste]] mevcud o.',
'nolinkstoimage' => 'Pelanê ser ena dosyayê link biyê çin o.',
'morelinkstoimage' => '[[Special:WhatLinksHere/$1|Linkanê zafyerî]] ena pele ra link biyo bivîne.',
'linkstoimage-redirect' => '$1 (Dosya raçarnayış) $2',
'duplicatesoffile' => 'a {{PLURAL:$1|dosya|$1 dosya}}, kopyayê na dosyayi ([[Special:FileDuplicateSearch/$2|teferruati]]):',
'sharedupload' => 'Ena dosya $1 ra u belki projeyê binan dı hewitiyeno.',
'sharedupload-desc-there' => 'depoyê $1 u projeyê bini na dosyayi xebıtneni. qey teferruati bıewnê [$2 teferruati dosyayi].',
'sharedupload-desc-here' => 'depoyê $1 u projeyê bini na dosyayi xebıtneni. qey teferruati bıewnê [$2 teferruati dosyayi].',
'sharedupload-desc-edit' => 'Na dosya $1 proceyan dê binandı ke şeno bıgurweyno.
Şıma qayılê ke malumatê cı bıvurnê se şıre [pela da $2 ].',
'sharedupload-desc-create' => 'Na dosya $1 proceyan dê binandı ke şeno bıgurweyno.
Şıma qayılê ke malumatê cı bıvurnê se şıre [pela da $2 ].',
'filepage-nofile' => 'Ena name de dosya çin o.',
'filepage-nofile-link' => 'Ena name de dosya çin o. Feqet ti eşkeno [$1 bar bike].',
'uploadnewversion-linktext' => 'Versiyonê newiyerê ena dosya bar ke',
'shared-repo-from' => '$1 ra',
'shared-repo' => 'yew embarê repositoryî',
'shared-repo-name-wikimediacommons' => 'Wikimedia Commons',
'filepage.css' => '/* CSS placed here is included on the file description page, also included on foreign client wikis */',
'upload-disallowed-here' => 'Şıma nêşenê serê na dosya ra bınusên.',

# File reversion
'filerevert' => '$1 reyna biyere',
'filerevert-legend' => 'Dosya ber weziyet do verên',
'filerevert-intro' => "Ti ho ena dosyayê '''[[Media:$1|$1]]'''î  [$4 versiyonê $3, $2] rê reyna anî.",
'filerevert-comment' => 'Sebeb:',
'filerevert-defaultcomment' => 'Versiyonê $2, $1 rê reyna ard',
'filerevert-submit' => 'Reyna biyere',
'filerevert-success' => "'''[[Media:$1|$1]]''', [$4 versiyonê $3, $2]î reyna berd.",
'filerevert-badversion' => 'Vesiyonê lokalê verniyê eno dosya pê ena pulêwext de çin o.',

# File deletion
'filedelete' => '$1 bıestere',
'filedelete-legend' => 'Dosya bıestere',
'filedelete-intro' => "Ti ho dosyayê '''[[Media:$1|$1]]'''i u tarixê ey dosyayê hemî estereno.",
'filedelete-intro-old' => "Ti ho versiyonê '''[[Media:$1|$1]]'''i [$4 $3, $2] estereno.",
'filedelete-comment' => 'Sebeb:',
'filedelete-submit' => 'Bestere',
'filedelete-success' => "'''$1'''  esteriyayo.",
'filedelete-success-old' => "Versiyonê'''[[Media:$1|$1]]'''î $3, $2 esteriyayo.",
'filedelete-nofile' => "'''$1''' çin o.",
'filedelete-nofile-old' => "Versiyonê arşivi ye '''$1'''î pê enê detayanê xasî çin o.",
'filedelete-otherreason' => 'Sebebê binî',
'filedelete-reason-otherlist' => 'Sebebê binî',
'filedelete-reason-dropdown' => '*sebebê hewna kerdışi
** ihlalê heqê telifi
** Çift/dosyaya kopyayın',
'filedelete-edit-reasonlist' => 'Sebebê esterayîşî bivurne',
'filedelete-maintenance' => 'Esterayîş u resterasyonê dosyayî wextê texmirî de nibenê.',
'filedelete-maintenance-title' => 'Dosyaya nêbesterneyêna',

# MIME search
'mimesearch' => 'MIME bigêre',
'mimesearch-summary' => 'no pel, no tewır dosyayan MIME kontrol kena. kewteye: tipa zerreyi/tipa bıni, e.g. <code>resim/jpeg</code>.',
'mimetype' => 'Babetê NIME',
'download' => 'bar ke',

# Unwatched pages
'unwatchedpages' => 'Pelanê seyrnibiyeyî',

# List redirects
'listredirects' => 'Listeya Hetenayışan',

# Unused templates
'unusedtemplates' => 'Şablonê ke nê xebtênê',
'unusedtemplatestext' => 'no pel, {{ns:template}} pelê ke pelê binan de nêaseni, ninan keno.',
'unusedtemplateswlh' => 'linkanê binî',

# Random page
'randompage' => 'Pela raştameyiye',
'randompage-nopages' => 'Ena {{PLURAL:$2|cayêname|cayênameyî}} de enê pelan çin o: $1.',

# Random redirect
'randomredirect' => 'Xoseri hetenayış',
'randomredirect-nopages' => 'Ena cayênameyê "$1"î de redereksiyonî çin o.',

# Statistics
'statistics' => 'İstatistiki',
'statistics-header-pages' => 'İstatistikê pele',
'statistics-header-edits' => 'Îstatistikê vurnayîşî',
'statistics-header-views' => 'Îstatistiksê vînayîşî',
'statistics-header-users' => 'Îstatistiksê karberî',
'statistics-header-hooks' => 'Îstatistiksê binî',
'statistics-articles' => 'Pelanê tedesteyî',
'statistics-pages' => 'Peli',
'statistics-pages-desc' => 'Pelanê hemî ke wîkî de estê, pelanê mineqeşeyî, redireksiyon ucb... dehil o.',
'statistics-files' => 'Dosyayê bar biye',
'statistics-edits' => 'Amarê vurnayîşî ke wextê {{SITENAME}} ronayîşî ra',
'statistics-edits-average' => 'Ser her pele de amarê vurnayîşîyê averageyî',
'statistics-views-total' => 'Yekunî bivîne',
'statistics-views-total-desc' => 'Peleyê ke çınyê yana xısusiyê e nina zerre nêkerdê',
'statistics-views-peredit' => 'Ser her vurnayîşî de vînayîşî',
'statistics-users' => 'Qeyd biye [[Special:ListUsers|karberî]]',
'statistics-users-active' => 'Karberê aktifi',
'statistics-users-active-desc' => '{{PLURAL:$1|roco peyin de|$1 roco peyin de}} karber ê ke kar kerdê.',
'statistics-mostpopular' => 'Pelayanê ke tewr zafî vînî biye',

'disambiguations' => 'Pelayê ke maneyo bini rê gırey cı esto',
'disambiguationspage' => 'Template:Maneo bin',
'disambiguations-text' => "Peleyê ke satır da sıteyên dı pelanê '''maneo bin'''i rê esteyina zeregri mocnenê. Nara satırda dıdın dı zi <br />tiya de [[MediaWiki:Disambiguationspage|Pelaya Maneo do bini ]] gani heme gıreyê şablonê ciya-manayan re gıre dayış icab keno.",

'doubleredirects' => 'Hetenayışê dıletıni',
'doubleredirectstext' => 'no pel pelê ray motışani liste keno.
gıreyê her satıri de gıreyi; raş motışê yewın u dıyıni esto.
<del>serê ey nuşteyi</del> safi biye.',
'double-redirect-fixed-move' => '[[$1]] kırışiya, hıni ray dana [[$2]] no pel',
'double-redirect-fixed-maintenance' => 'raçarnayışo dıletê [[$1]] ra  pela da [[$2]] timarêno',
'double-redirect-fixer' => 'Fixerî redirek bike',

'brokenredirects' => 'Hetenayışê vengi',
'brokenredirectstext' => 'Redireksiyonê ey ki pelanê hama çiniyeno ra link dano:',
'brokenredirects-edit' => 'bıvurne',
'brokenredirects-delete' => 'bestere',

'withoutinterwiki' => 'Peleyê ke zıwanan de bina re gırey cı çınyo',
'withoutinterwiki-summary' => 'Enê pelî ke versiyonê ziwanî binî ra link nidano.',
'withoutinterwiki-legend' => 'Verole',
'withoutinterwiki-submit' => 'Bımocne',

'fewestrevisions' => 'Peleyê ke cı sero tewr tayn timaryayış vıraziyayo',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|bayt|bayti}}',
'ncategories' => '$1 {{PLURAL:$1|Kategoriye|Kategoriy}}',
'ninterwikis' => '$1 {{PLURAL:$1|interwiki|interwikiy}}',
'nlinks' => '$1 {{PLURAL:$1|link|linkî}}',
'nmembers' => '$1 {{PLURAL:$1|eza|ezayan}}',
'nrevisions' => '$1 {{PLURAL:$1|vurnayış|vurnayışi}}',
'nviews' => '$1 {{PLURAL:$1|vênayış|vênayışi}}',
'nimagelinks' => '$1 {{PLURAL:$1|pele de|pelan de}} gureyeno',
'ntransclusions' => '$1 {{PLURAL:$1|pele de|pelan de}} gureyeno',
'specialpage-empty' => 'Seba na rapore netice çıniyo.',
'lonelypages' => 'Pelê seyi',
'lonelypagestext' => 'Ena pelî link nibiyê ya zi pelanê binî {{SITENAME}} de transclude biy.',
'uncategorizedpages' => 'Pelayanê ke kategorî nibiye',
'uncategorizedcategories' => 'Kategoriyê ke bê kategorîyê',
'uncategorizedimages' => 'Dosyayê ke bê kategoriyê',
'uncategorizedtemplates' => 'Şablonê ke bê kategoriyê',
'unusedcategories' => 'Kategoriyê ke nê xebtênê',
'unusedimages' => 'Dosyeyê ke nê xebtênê',
'popularpages' => 'Pelî ke populer o.',
'wantedcategories' => 'Kategoriye ke waştênê',
'wantedpages' => 'Peleye ke waştênê',
'wantedpages-badtitle' => 'sernuşte meqbul niyo: $1',
'wantedfiles' => 'Dosyeye ke waştênê',
'wantedfiletext-cat' => 'Dosyaya cêrên karvıstedeya lakin çınya. Mewcud dosyayan de xeriba miyan de liste bena. Xırabiya wınisin dana <del>ateber</del>. Zewbi zi, şırê pela da dosyeyê ke çınyaya [[:$1]].',
'wantedfiletext-nocat' => 'Dosyeyê cêrêni estê lekin karnêvıstê. Dosyeyê xeribi liste benê. bo babeta dano <del>ateber</del>',
'wantedtemplates' => 'Şablonê ke waştênê',
'mostlinked' => 'Pelî ke tewr zafî lînk bîy.',
'mostlinkedcategories' => 'Kategorî ke tewr zafî lînk bîy.',
'mostlinkedtemplates' => 'Şablonê ke tewr zafî pela re gıre bîye.',
'mostcategories' => 'Pelan ke tewr zaf kategorî estê.',
'mostimages' => 'Dosyayan ke tewr zaf link estê.',
'mostinterwikis' => 'Pelan ke tewr zaf interwiki biyê.',
'mostrevisions' => 'Pelan ke tewr zaf revizyonî biyê.',
'prefixindex' => 'Veroleya peley pêro',
'prefixindex-namespace' => 'Peleyê Veroleyıni ($1 cay nami)',
'shortpages' => 'Pelê kılmeki',
'longpages' => 'Peleyê dergeki',
'deadendpages' => 'pelê ke pelê binan re gırey nêeşto',
'deadendpagestext' => 'Ena pelan ke {{SITENAME}} de zerrî ey de link çini yo.',
'protectedpages' => 'Pelayê ke biyê star',
'protectedpages-indef' => 'têna pawıteyê bêmuddeti',
'protectedpages-cascade' => 'Kilit biyaye ke teyna cascadiye',
'protectedpagestext' => 'pelê cêrınî pawiyenê',
'protectedpagesempty' => 'pê ney parametreyan pelê pawiteyi çinî',
'protectedtitles' => 'Sernameyê ke starênê',
'protectedtitlestext' => 'sernameyê cêrıni pawıte yî',
'protectedtitlesempty' => 'pê ney parametreyan sernuşteyê pawite çinê',
'listusers' => 'Listeyê Karberan',
'listusers-editsonly' => 'Teyna karberan bimucne ke ey nuştê',
'listusers-creationsort' => 'goreyê wextê vıraştışi rêz ker',
'usereditcount' => '$1 {{PLURAL:$1|vurnayîş|vurnayîşî}}',
'usercreated' => '$2 de $1 {{GENDER:$3|viraziya}}',
'newpages' => 'Pelê newey',
'newpages-username' => 'Nameyê karberi:',
'ancientpages' => 'Wesiqeyê ke vurnayışê ciyê peyeni tewr kehani',
'move' => 'Berdış',
'movethispage' => 'Ena pele bere',
'unusedimagestext' => 'Enê dosyey estê, feqet zerrey yew pele de wedardey niyê.
Xo vira mekerê ke, sıteyê webiê bini şenê direkt ebe URLi yew dosya ra gırê bê, u wına şenê verba gurênayışo feal de tiya hewna lista bê.',
'unusedcategoriestext' => 'kategoriyê cêrıni bıbo zi çı nêşuxulyena.',
'notargettitle' => 'Hedef çini yo',
'notargettext' => 'qey xebıtnayişê ney fonksiyoni şıma yew hedef nişane nêkerd.',
'nopagetitle' => 'wina yew pelê hedefi çin o.',
'nopagetext' => 'pelê hedefi ke şıma nişane kerdo çin o.',
'pager-newer-n' => '{{PLURAL:$1|newiyer 1|newiyer $1}}',
'pager-older-n' => '{{PLURAL:$1|deha kehan 1|deha kehan $1}}',
'suppress' => 'Çımpawıten',
'querypage-disabled' => 'Na pelaya xısusi,sebeb de performansi ra qefılneyê.',

# Book sources
'booksources' => 'Çımey kitaban',
'booksources-search-legend' => 'Ser çımey kitaban bıgeyr',
'booksources-isbn' => 'ISBN:',
'booksources-go' => 'Şo',
'booksources-text' => 'listeya cêrıni, keyepelê kitap rotoxan o.',
'booksources-invalid-isbn' => 'ISBN raşt nêasena bıewnê çımeyê orjinali, raşt kopya biya nê nêbiyaya?',

# Special:Log
'specialloguserlabel' => 'Kerdoğ:',
'speciallogtitlelabel' => 'Menzil (sernuşte yana karber):',
'log' => 'Qeydi',
'all-logs-page' => 'Umumi qeydi pêro',
'alllogstext' => 'qey {{SITENAME}}i mocnayişê heme rocaneyani.
tipa rocaneyi, nameyê karberi (herfa pil u qıci re hessas a), ya zi peli (reyna hessasiyê herfa pil u qıciyi) bıweçine u esayiş qıc kerê.',
'logempty' => 'qaydi de weina yew malumat çino',
'log-title-wildcard' => 'sername yê ke pê ney nuşteyi destkenêpê bıgêr.',
'showhideselectedlogentries' => 'Qeydê weçinayışê bımocne/bınımne dekerê',

# Special:AllPages
'allpages' => 'Peri pêro',
'alphaindexline' => '$1 ra $2ine',
'nextpage' => 'Pela badê cû ($1)',
'prevpage' => 'Pela verêne ($1)',
'allpagesfrom' => 'Pelanê ke be ena herfe dest pêkenê bımocne',
'allpagesto' => 'Pelanê ke be ena herfe qediyenê bımocne:',
'allarticles' => 'Wesiqey pêro',
'allinnamespace' => 'Peli pênro ( $1 cayênameyî)',
'allnotinnamespace' => 'Pelanê hemî ($1 cayênameyî de niyo)',
'allpagesprev' => 'Verên',
'allpagesnext' => 'Bahdo',
'allpagessubmit' => 'Şo',
'allpagesprefix' => 'herfê ke şıma tiya de nuşti, pê ney herfan pelê ke destpêkenê liste ker:',
'allpagesbadtitle' => 'pel o ke şıma kewenî cı, nameyê no peli de gıreyê zıwanan u wikiyi re elaqa esto, ê ra cıkewtış qebul niyo. ya zi sernameyan de karakterê qedexeyi tede esto.',
'allpages-bad-ns' => '{{SITENAME}} keyepel de wina "$1" yew nameyê cayi çino.',
'allpages-hide-redirects' => 'Raçarnaya bınımne',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'Şıma rê verhafıza versiyonê na pela aseno, hetana $1 cı kehani.',
'cachedspecial-viewing-cached-ts' => 'Na pela raşt niya, şımayê enewke versiyonê verhafızada na pela vinenê.',
'cachedspecial-refresh-now' => 'Peyêni bıvin.',

# Special:Categories
'categories' => 'Kategoriy',
'categoriespagetext' => '{{PLURAL:$1|kategoriyê|kategoriyê}} cêrıni de pel u media esto.
[[Special:UnusedCategories|kategoriyê ke nê xebtênê]] tiya de nêmociyeni.
hem zi bıewnê [[Special:WantedCategories|kategori yê ke waziyeni]].',
'categoriesfrom' => 'kategori yê ke pê ninan destpêkeno ramocın:',
'special-categories-sort-count' => 'goreyê çendi rêz ker.',
'special-categories-sort-abc' => 'alfabetik rêz ker',

# Special:DeletedContributions
'deletedcontributions' => 'İştiraqê karberan de besternayına',
'deletedcontributions-title' => 'Îştirakê karberî wederna',
'sp-deletedcontributions-contribs' => 'iştıraqi',

# Special:LinkSearch
'linksearch' => 'Gıreyê teberi cı geyrê',
'linksearch-pat' => 'bıgêr motif:',
'linksearch-ns' => 'Cayênameyî:',
'linksearch-ok' => 'Cı geyre',
'linksearch-text' => 'Jokeri ê zey "*.wikipedia.org"i benê ke bıgureniyê.
Tewr senık yew sewiya serêna cayê tesiri lazıma, mesela "*.org".<br />
Qeydeyê destegbiyayey: <code>$1</code> (qet yew qeydeyo hesabiyaye http:// ke name nêbiyo).',
'linksearch-line' => '$1, $2 ra link biya',
'linksearch-error' => 'jokeri têna nameyê makina ya serekini de aseni/eseni.',

# Special:ListUsers
'listusersfrom' => 'karber ê ke pey ıney detpêkeni ramocın:',
'listusers-submit' => 'Bımocne',
'listusers-noresult' => 'karber nêdiyayo/a.',
'listusers-blocked' => '(blok biy)',

# Special:ActiveUsers
'activeusers' => 'Listey karberan de aktivan',
'activeusers-intro' => 'Ena yew listeya karberê ke $1 {{PLURAL:$1|roc|rocan}} ra tepya iştiraq kerdo ênan mocneno.',
'activeusers-count' => 'Karberi {{PLURAL:$3|roce peyni de|$3 roca peyni de}} $1 {{PLURAL:$1|vurnayış|vurnayışi}} kerdê',
'activeusers-from' => 'Enê karberi ra tepya bımocne:',
'activeusers-hidebots' => 'Botan bınımne',
'activeusers-hidesysops' => 'İdarekerdoğan bınımne',
'activeusers-noresult' => 'Karberi nêdiyayê.',

# Special:Log/newusers
'newuserlogpage' => 'Cıkewtışê hesabvıraştışi',
'newuserlogpagetext' => 'Ena log de viraştişê karberî esta.',

# Special:ListGroupRights
'listgrouprights' => 'heqê grubê karberi',
'listgrouprights-summary' => 'wikiya cêrın a ke tede grubê karberi nişane biyê, listeya heqê cıresayişê inan o.
qey heqê şexsi de [[{{MediaWiki:Listgrouprights-helppage}}|hema malumato ziyed]] belka esto.',
'listgrouprights-key' => '* <span class="listgrouprights-granted">Heqa daiye</span>
* <span class="listgrouprights-revoked">Heqa guretiye</span>',
'listgrouprights-group' => 'Grube',
'listgrouprights-rights' => 'Heqqî',
'listgrouprights-helppage' => 'Help:Heqqanê gruban',
'listgrouprights-members' => '(listey ezayan)',
'listgrouprights-right-display' => '<span class="listgrouprights-granted">$1 <code>($2)</code></span>',
'listgrouprights-right-revoked' => '<span class="listgrouprights-revoked">$1 <code>($2)</code></span>',
'listgrouprights-addgroup' => '{{PLURAL:$2|Grube|Gruban}} cı kerê: $1',
'listgrouprights-removegroup' => '{{PLURAL:$2|Grube|Gruban}} bıvecê: $1',
'listgrouprights-addgroup-all' => 'şıma hesabê xo re heme gruban eşkeni têare bıkeri',
'listgrouprights-removegroup-all' => 'şıma hesabê xo ra heme gruban eşkeni veci',
'listgrouprights-addgroup-self' => 'Hesabê xo rê {{PLURAL:$2|grube|gruban}} cı kerê: $1',
'listgrouprights-removegroup-self' => 'Hesabê xo ra {{PLURAL:$2|grube|gruban}} bıvecê: $1',
'listgrouprights-addgroup-self-all' => 'şıma eşkeni hesabê xo re heme gruban têare bıkerî',
'listgrouprights-removegroup-self-all' => 'şıma hesabê xo ra eşkeni heme gruban bıveci',

# E-mail user
'mailnologin' => 'adresa erşawıtışi/ruşnayişi çina.',
'mailnologintext' => 'qey karberanê binan re e-posta erşawıtış de gani şıma [[Special:UserLogin|hesab aker]]ê [[Special:Preferences|pelê tercihani]] de gani yew e-postayo meqbul bıbo.',
'emailuser' => 'Ena karberi rê mesac bırse',
'emailuser-title-target' => 'Na E-postaya {{GENDER:$1|karberi}}ya',
'emailuser-title-notarget' => 'E-postaya karberi',
'emailpage' => 'karberi re e-posta bırışê',
'emailpagetext' => 'Şıma şenê nê formê cêrêni nê {{GENDER:$1|karber}}i rê e-poste rıştış de bıgurenê.
[[Special:Preferences|Tercihanê şımayê karberi]] de adresa e-posteya ke şıma daya, na adrese qısmê adresa e-postey de "kami ra" asena, no sebeb ra gırewtoğ şeno direkt cewab bıdero şıma.',
'usermailererror' => 'xizmetê e-postayi xeta da:',
'defemailsubject' => '"$1" ra e-postay {{SITENAME}} amê',
'usermaildisabled' => 'E-mailê karberani kafiliyeya',
'usermaildisabledtext' => 'Ti nieşkena ena wiki de karberanê binan rê e-mail bişave',
'noemailtitle' => 'adresa e-postayi çina',
'noemailtext' => 'no/na karber yew e-postayo meqbul nêdawa/o',
'nowikiemailtitle' => 'E-postayan re destur çino',
'nowikiemailtext' => 'no/na karber/e, karberanê binani ra gırewtışê e-postayi tercih nêkerd.',
'emailnotarget' => 'Qandê Gêreninamey karberiyo wuna çınyo yana xırabo.',
'emailtarget' => 'Namey Karberi defiyê de.',
'emailusername' => 'Nameyê karberi:',
'emailusernamesubmit' => 'İtaet',
'email-legend' => 'karberê {{SITENAME}} binan re e-posta bıerşaw',
'emailfrom' => 'Kami ra:',
'emailto' => 'Kami rê:',
'emailsubject' => 'Mewzu:',
'emailmessage' => 'Mesac:',
'emailsend' => 'Bırışe',
'emailccme' => 'kopyayekê mesaji mı re bıerşaw',
'emailccsubject' => '$2 kopyaya mesaj a ke şıma erşawıto/a $1:',
'emailsent' => 'E-posta bırşê',
'emailsenttext' => 'e-mailê şıma erşawiya/ruşiya',
'emailuserfooter' => 'na e-posta hetê ıney ra $1 erşawiya $2 no/na karberi/e re. pê fonksiyonê "Karberi/e re e-posta bıerşaw" no {{SITENAME}} keyepeli erşawiya.',

# User Messenger
'usermessage-summary' => 'Mesacê sistemi caverde.',
'usermessage-editor' => 'Mesaj berdoxe sistemi',
'usermessage-template' => 'MediaWiki:UserMessage',

# Watchlist
'watchlist' => 'Lista mına seyrkerdışi',
'mywatchlist' => 'Lista seyrkerdışi',
'watchlistfor2' => 'Qandê $1 ($2)',
'nowatchlist' => 'listeya temaşa kerdıişê şıma de yew madde zi çina.',
'watchlistanontext' => 'qey vurnayişê maddeya listeya temaşakerdişi $1.',
'watchnologin' => 'Şıma de nêkewtê',
'watchnologintext' => 'qey vurnayişê listeya temaşakerdışi [[Special:UserLogin|gani şıma hesab akeri]].',
'addwatch' => 'Listeyê seyri deke',
'addedwatchtext' => 'Ma pele "[[:$1]]" zerri [[Special:Watchlist|watchlist]]ê tı kerd de.
Ena deme ra, ma qe vurnayışan ser ena pele tı haberdar keni. Hem zi çı dem ma tu ri heber dun, zerri [[Special:RecentChanges|list of recent changes]] name pele beno qalın. Tı ri beno qolay çıta vurnaye biyo.',
'removewatch' => 'Listedê mınê seyr kerdışi ra hewad',
'removedwatchtext' => 'Ena pela "[[:$1]]" biya wedariya [[Special:Watchlist|listeyê seyr-kerdışi şıma]].',
'watch' => 'Temaşe ke',
'watchthispage' => 'Na pele seyr ke',
'unwatch' => 'Teqib mekerê',
'unwatchthispage' => 'temaşa kerdışê peli vındarn.',
'notanarticle' => 'mebhesê peli niyo',
'notvisiblerev' => 'Revizyon esteriyayo',
'watchnochange' => 'pelê listeya temaşakerdışê şıma ye wextê nişane biyaye de rocane nêbiyo.',
'watchlist-details' => '{{PLURAL:$1|$1 pele|$1 peleyan}} listeyê seyr-kerdışi şıma dı, peleyanê vurnayışi dahil niyo.',
'wlheader-enotif' => 'pê * E-mail xeber dayiş biyo a.',
'wlheader-showupdated' => "* ziyaretê şıma ye peyini de vuryayişê peli pê '''nuşteyo qalıni''' mocyayo.",
'watchmethod-recent' => 'pel ê ke şıma temaşa kenî vuryayişê peyinê ey konrol beno',
'watchmethod-list' => 'pel ê ke şıma temaşa kenî vuryayişê peyinê ey konrol beno',
'watchlistcontains' => 'listeya seyrkerdışê şıma de $1 tene {{PLURAL:$1|peli|peli}} estî.',
'iteminvalidname' => "pê no '$1' unsuri problem bı, nameyo nemeqbul...",
'wlnote' => "$3 seate u bahde $4 deqa dıma {{PLURAL:$2|ju seate dı|'''$2''' ju seate dı}} {{PLURAL:$1|vurnayışe peyeni|vurnayışe '''$1''' peyeni}} cêrdeyê",
'wlshowlast' => 'Peyni de vurnayışan ra  $1 seata u $2 roca $3 bımocnê',
'watchlist-options' => 'Tercihê liste da seyri',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Seyr ke...',
'unwatching' => 'Seyr meke...',
'watcherrortext' => 'Sazanê listeda seyri vurnayış de pox ta "$1" xırabey vıcyê .',

'enotif_mailer' => 'postaya xeberdayişi {{SITENAME}}',
'enotif_reset' => 'Pela pêro ziyaret kerde deye mor ke',
'enotif_newpagetext' => 'Ena yew pela newî ya.',
'enotif_impersonal_salutation' => '{{SITENAME}} karber',
'changed' => 'vurneya',
'created' => 'viraziya',
'enotif_subject' => 'pelê {{SITENAME}}i $PAGETITLE, hetê/perrê $PAGEEDITOR $CHANGEDORCREATED',
'enotif_lastvisited' => 'ziyareta şıma ye peyini ra nata heme vuryayiş ê ke biyê bıewnê $1i re..',
'enotif_lastdiff' => 'qey vinayişê ney vurnayişi bıewnê pelê $1i',
'enotif_anon_editor' => 'karbero anonim $1',
'enotif_body' => 'Embazê $WATCHINGUSERNAME,

{{SITENAME}} keyepel de no $PAGETITLE pelo sernameyın re $PAGEEDITDATE no tarix de $PAGEEDITOR no karberi $CHANGEDORCREATED. şıma eşkeni bıresi halê no peli re $PAGETITLE_URL na adresi ra.

$NEWPAGE

beyanatê karber o ke vurnayiş kerdo: $PAGESUMMARY $PAGEMINOREDIT

cıresayişê karber o ke vurnayiş kerdo:
e-posta: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

no pel o ke behs beno heta ziyaret kerdışê yewna heli, mesajê vuriyayişi nêşawiyeno.

           {{SITENAME}} sistemê hişyariyê keyepeli.

--
qey vurnayişê eyari:
{{canonicalurl:{{#Special:Watchlist/edit}}}}

qey wedarayişê ena pele liste xo ra seyr kerdişi, şo
$UNWATCHURL

qey hemkari u pêşniyazi:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage' => 'Pele bıestere',
'confirm' => 'Testiq ke',
'excontent' => "behso kêm: '$1'",
'excontentauthor' => "behso kêm: '$1' no/na ('[[Special:Contributions/$2|$2]]'  teyna iştıraq kerdo)",
'exbeforeblank' => "behsê verê esteriyayişi: '$1'",
'exblank' => 'zerreyê peli vengo',
'delete-confirm' => '"$1" bıestere',
'delete-legend' => 'Bestere',
'historywarning' => "'''Teme:''' Pela ke şıma esterenê tede yew viyarte be teqriben $1 {{PLURAL:$1|versiyon esto|versiyoni estê}}:",
'confirmdeletetext' => 'Tı ho yew pele u tarixê pele wederneno.
Tı ra rica keno, tı zani tı ho sekeno, tı zani neticeyanê eno wedarnayışi u tı zani tı ser [[{{MediaWiki:Policy-url}}|poliçe]] kar keno.',
'actioncomplete' => 'Xebten temam biyo',
'actionfailed' => 'kar nêbı',
'deletedtext' => '"$1" biya wedariya.
Qe qeydê wedarnayışi, $2 bevinin.',
'dellogpage' => 'Qeydê esterniye',
'dellogpagetext' => 'listeya cêrıni heme qaydê hewn a kerdeyan o.',
'deletionlog' => 'qaydê hewnakerdışani',
'reverted' => 'revizyono verin tepiya anciyayo',
'deletecomment' => 'Sebeb:',
'deleteotherreason' => 'Sebebo bin:',
'deletereasonotherlist' => 'Sebebo bin',
'deletereason-dropdown' => '*sebebê hewnakerdışê pêroyî
** talebê nuştekari
** ihlalê heqê telifi
** Vandalizm',
'delete-edit-reasonlist' => 'Sebebê vurnayışan bıvurne',
'delete-toobig' => 'no pel, pê $1 {{PLURAL:$1|tene vuriyayiş|tene vuriyayiş}}i wayirê yew tarixo kehen o.
qey hewna nêşiyayişi wina pelani u {{SITENAME}}nêxerebnayişê keyepeli yew hed niyaya ro.',
'delete-warning-toobig' => 'no pel wayirê tarixê vurnayiş ê derg o, $1 {{PLURAL:$1|revizyonê|revizyonê}} seri de.
hewn a kerdışê ıney {{SITENAME}} şuxul bıne gırano;
bı diqqet dewam kerê.',

# Rollback
'rollback' => 'vurnayişan tepiya bıger',
'rollback_short' => 'Peyser bia',
'rollbacklink' => 'peyser bia',
'rollbacklinkcount' => '$1 {{PLURAL:$1|vurnayış|vurnayışi}} peyd gıroti',
'rollbacklinkcount-morethan' => '$1 {{PLURAL:$1|vurnayış|vuranyışi}} tewr peyd gırot',
'rollbackfailed' => 'Peyserardış nêbi',
'cantrollback' => 'karbero peyin têna paşt dayo, no semedi ra vuriyayiş tepiya nêgeriyeni.',
'alreadyrolled' => '[[User:$2|$2]] ([[User talk:$2|Talk]]{{int:pipe-separator}} hetê [[Special:Contributions/$2|{{int:contribslink}}]]) ra pelê ıney[[:$1]] de vurnayiş biyo u no vurnayiş tepiya nêgeriyeno;
yewna ten pel de vurnayiş kerdo u pel tepiya nêgeriyeno.

oyo ke vurnayişo peyin kerdo: [[User:$3|$3]] ([[User talk:$3|Talk]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "kılmnuşteyê vurnayişibi: \"''\$1''\".",
'revertpage' => 'Hetê [[Special:Contributions/$2|$2]] ([[User talk:$2|Mesac]]) ra vurnayiş biyo u ney vurnayişi tepiya geriyayo u no [[User:$1|$1]] kes o ke cuwa ver revizyon kerdo revizyonê no kesi tepiya anciyayo.',
'revertpage-nouser' => '(nameyê karberi veteyo) no keso ke vuriyayiş kerdo vuriyayişê no kesi hetê no [[User:$1|$1]] kesi ra tepiya anciyayo',
'rollback-success' => 'vurnayişê no kesi $1 tepiya geriyayo u hetê no
$2 kesi ra cıwa ver o ke revizyon biyo no revizyon tepiya anciyayo.',

# Edit tokens
'sessionfailure-title' => 'Seans xeripiya',
'sessionfailure' => 'cıkewtışê hesabê şıma de yew problem aseno;
no kar semedê dızdiyê hesabi ibtal biyo.
kerem kerê "tepiya" şiyerê u pel o ke şıma tera ameyî u o pel newe ra bar kerê , newe ra tesel/cereb kerê.',

# Protect
'protectlogpage' => 'Qeydê staryayan',
'protectlogtext' => 'Şıma vurnayişê gırewtışê/wedarnayışê pawıtişi vinenê.
Qey malumato ziyede [[Special:ProtectedPages|Peleyê ke star biye]] bewni rê êna .',
'protectedarticle' => '"[[$1]]" kılit biyo',
'modifiedarticleprotection' => 'Qe "[[$1]]", seviye kılit kerdişi vurnayi biyo',
'unprotectedarticle' => 'Starkerdışê "[[$1]]" hewadeya',
'movedarticleprotection' => 'eyarê pawıtışi no "[[$2]]" peli ra kırışiya no "[[$1]]" peli',
'protect-title' => 'qey "$1" yew seviyaya pawıtışi bıvıcinê',
'protect-title-notallowed' => 'Star kerdış sewiyeyê "$1" bıvinê',
'prot_1movedto2' => 'nameyê [[$1]] peli yo newe: [[$2]]',
'protect-badnamespace-title' => 'Heruna naman itad starêna',
'protect-badnamespace-text' => 'Na herunda namide peley nêstarênê.',
'protect-legend' => 'Pawıtışi araşt ke',
'protectcomment' => 'Sebeb:',
'protectexpiry' => 'Qediyeno:',
'protect_expiry_invalid' => 'Demo qediyayışi raşt niyo.',
'protect_expiry_old' => 'Demo qediyayışi tarix dı.',
'protect-unchain-permissions' => 'Zobina tercihanê mıhafezekerdışi kilıt meke',
'protect-text' => "Tı eşkeno bıvin u seviyê kılit-kerdışi bıvin '''$1'''.",
'protect-locked-blocked' => "seviyeya qedexe biyayeyan nevuriyeno.
'''$1''' eyarê peli:",
'protect-locked-dblock' => "semedê kılidê database ya aktifi şıma neeşkeni seviyeya pawıtışi buvurni.
'''$1''' eyarê no peli:",
'protect-locked-access' => "Karber hesabê şıma nêşeno  staryaye sewiyey ena peler bıvurno.
Hesıbyayê sazê pela da '''$1''' enêyê:",
'protect-cascadeon' => 'Ena pele nıka kılit biya. Çınki ena pele zerre listeyê {{PLURAL:$1|pele, ki|peleyan, which li}} bınê "cascading protection"iyo.
Tı eşkeno seviyeye kılit kerdışi bıvurno, feqat tı nıeşken "cascading protection"i bıvurno.',
'protect-default' => 'Destur bıde karberan pêrune',
'protect-fallback' => 'Desturê "$1" lazımo',
'protect-level-autoconfirmed' => 'Karberanê neweyan u qeyd-nêbiyaoğan kılit ke',
'protect-level-sysop' => 'Tenya idarekeri',
'protect-summary-cascade' => 'çırrayış',
'protect-expiring' => 'qediyeno $1 (UTC)',
'protect-expiring-local' => '$1 do bı qedyo',
'protect-expiry-indefinite' => 'bê hed u hesab',
'protect-cascade' => 'Ena pela dı pelayan kılit-biya ca geno (cascading protection)',
'protect-cantedit' => 'Tı nêşenay sinorê kılit-biyayışê ena pele bıvurnê, çıke desturê to be vurnayışi çıniyo.',
'protect-othertime' => 'Wextê binî:',
'protect-othertime-op' => 'wextê binî',
'protect-existing-expiry' => 'wextê qediyayişi yê mewcudi: $3, $2',
'protect-otherreason' => 'sebebo bin/sebebê ilaveyi',
'protect-otherreason-op' => 'Sebebo bin',
'protect-dropdown' => '*sebebê pawıtışi ye pêroyiye
** vandalizmo hed ra vecaye
** spamo hed ra vecaye
** şêrê/herbê vurnayişi
** pel o ke zaf wayirê trafiki yo',
'protect-edit-reasonlist' => 'sebebê pawıtışi bıvurn',
'protect-expiry-options' => '1 seet:1 hour,1 roc:1 day,1 hefte:1 week,2 hefteyi:2 weeks,1 aşme:1 month,3 aşmî:3 months,6 aşmî:6 months,1 serre:1 year,bê hedd u hesab:infinite',
'restriction-type' => 'Destur:',
'restriction-level' => 'Sinorê desturi:',
'minimum-size' => 'Ebatê minumî',
'maximum-size' => 'Ebatê maximumî',
'pagesize' => '(bitî)',

# Restrictions (nouns)
'restriction-edit' => 'Bıvurne',
'restriction-move' => 'Berê',
'restriction-create' => 'Vıraze',
'restriction-upload' => 'Barke',

# Restriction levels
'restriction-level-sysop' => 'pawıtışê tamamîye',
'restriction-level-autoconfirmed' => 'nêm-pawıtış',
'restriction-level-all' => 'seviye ya ke raşt ame',

# Undelete
'undelete' => 'Peleyê ke besterneyayê enê bımocnê',
'undeletepage' => 'bıewn revizyonê peli yê hewn a şiyayeyan u tepiya biyar',
'undeletepagetitle' => "'''pelo [[:$1|$1]] cêrın, wayirê revizyonê hewn a şiyayeyan o'''.",
'viewdeletedpage' => 'bıewn pelê hewn a şiyayeyani',
'undeletepagetext' => '{{PLURAL:$1|pelo|$1 pelo}} cerın hewn a şiyo labele hema zi arşiv de yo u tepiya geriyeno.
Arşiv daimi pak beno.',
'undelete-fieldset-title' => 'revizyonan tepiya bar ker',
'undeleteextrahelp' => "Qey ardışê pel u verê pelani tuşê '''tepiya biya!'''yi bıtıknê. qey ciya ciya ardışê verê pelani zi qutiye tesdiqi nişane kerê u tuşê '''tepiya biya!'''yi bıtıknê '''''{{int:undeletebtn}}'''''.. qey hewn a kerdışê qutiya tesdiqan u qey sıfır kerdışê cayê sebebani zi tuşê '''agêr caverd/aça ker'''i bıtıknê '''''{{int:undeletebtn}}'''''..",
'undeleterevisions' => '$1 {{PLURAL:$1|revizyon|revizyon}} arşiw bi',
'undeletehistory' => 'eke şıma pel tepiya biyari heme revizyonî zi tepiya yeni.
eke yew pel hewn a biyo u pê nameyê o peli newe ra yew pel bıvıraziyo, revizyonê o pelê verıni zerreyê no pel de aseno.',
'undeleterevdel' => 'eke pelo serın de netice bıdo ya zi revizyoni qısmen hewn a bıbiy hewn a kerdışi tepiya nêgeriyeno.',
'undeletehistorynoadmin' => 'na madde hewn a biya. sebebê hewna kerdışi u teferruatê karber ê ke maddeyi vıraştı cêr de diyayî. revizyonê hewn a biyayeyani têna serkari vineni',
'undelete-revision' => 'hetê ıney $3 ra revizyonê pelê ıney $1 hewn a biyo, nêy revizyoni ($4 tarixi ra nat, $5 seeti de):',
'undeleterevision-missing' => 'revizyonê nemeqbul u vindbiyayeyi.
Revizyoni ya hewn a biyê ya arşiw ra veciyayê ya zi cıresayişê şımayi şaş o.',
'undelete-nodiff' => 'revizyonê verıni nidiya',
'undeletebtn' => 'Timar bike',
'undeletelink' => 'bıvêne/peyser bia',
'undeleteviewlink' => 'bıvin',
'undeletereset' => 'Reset kerê',
'undeleteinvert' => 'vicnayeyi qeldaye açarn',
'undeletecomment' => 'Sebeb:',
'undeletedrevisions' => 'pêro piya{{PLURAL:$1|1 qeyd|$1 qeyd}} tepiya anciya.',
'undeletedrevisions-files' => '{{PLURAL:$1|1 revizyon|$1 revizyon}} u {{PLURAL:$2|1 dosya|$2 dosya}} ameyê halê xo yê verıni',
'undeletedfiles' => '{{PLURAL:$1|1 dosya|$1 dosya}} tepiya anciyayi.',
'cannotundelete' => 'şıma ya ver yewna ten pel u medya tepiya ard u ê ra tepiya ardışê şıma meqbul niyo.',
'undeletedpage' => "'''$1 pel tepiya anciya'''

qey karê tepiya ardışi u qey karê hewn a kerdışê verıni bıewnê [[Special:Log/delete|qeydê hewn a kerdışi]].",
'undelete-header' => 'Peleyê ke veror de besterneyayê êna bıvinê: [[Special:Log/delete|qeydê esterneya]].',
'undelete-search-title' => 'Bıgeyre pelanê eserıtiyan',
'undelete-search-box' => 'bıgêr pelê hewn a biyayeyani',
'undelete-search-prefix' => 'pel ê ke pê ney destpêkenî, ramocın',
'undelete-search-submit' => 'Cı geyre',
'undelete-no-results' => 'Zerre arşîvê esterayîşî de peleyan match nibiyê.',
'undelete-filename-mismatch' => 'Vurnayîşê ke pê wextê puli ye $1î nieşkenî biyare: nameyê dosyayî match nibeno',
'undelete-bad-store-key' => 'Vurnayîşê ke pê wextê puli ye $1î nieşkenî biyare: verniyê esterayîşî de dosyayî vînî biya.',
'undelete-cleanup-error' => 'Eka dosyayê arşîvî "$1"î ke ho wedariyeno feqet yew ğelet biya.',
'undelete-missing-filearchive' => 'arşiwê IDyê yi dosyayi $1 tepiya niyeno çunke database de niyo.
belka cıwa ver hewn a biyo..',
'undelete-error' => 'Besternayışê peyd bıgi pela de xırabin vıcyê',
'undelete-error-short' => 'Eka dosyayê biyereno feqet yew ğelet biya: $1',
'undelete-error-long' => 'hewn a kerdışê na dosyayi wexta tepiya geriyenê xeta vıraziya:

$1',
'undelete-show-file-confirm' => '"<nowiki>$1</nowiki>" şıma emin î dosyaya revizyonê no $2 $3 tarixi bıvini?',
'undelete-show-file-submit' => 'E',
'undelete-revisionrow' => '$1 $2 ($3) $4 . . $5 $6 $7',

# Namespace form on various pages
'namespace' => 'Cayê namey:',
'invert' => 'Weçinıtışo peyserki',
'tooltip-invert' => 'nameyo ke nışan biyo (u nameyo elekeyın zi nışanyyayo se) vurnayışan  zerrekan nımtışi re ena dore tesdiqi nışan kerê',
'namespace_association' => 'Cayê nameyanê eleqedaran',
'tooltip-namespace_association' => 'Herunda canemiya elekeyın nışan kerdışi sero qıse kerdışi yana zerre dekerdışi rê ena dora tesdiqi nışan kerê',
'blanknamespace' => '(Ser)',

# Contributions
'contributions' => 'İştiraqê karberi',
'contributions-title' => 'Dekerdenê karber de $1',
'mycontris' => 'İştıraqi',
'contribsub2' => 'Qandê $1 ($2)',
'nocontribs' => 'Ena kriteriya de vurnayîş çini yo.',
'uctop' => '(top)',
'month' => 'Aşm:',
'year' => 'Ser:',

'sp-contributions-newbies' => 'Tenya iştıraqanê karberanê neweyan bımocne',
'sp-contributions-newbies-sub' => 'Qe hesebê newe',
'sp-contributions-newbies-title' => 'Îştîrakê karberî ser hesabê neweyî',
'sp-contributions-blocklog' => 'Qeydê kılit-kerdışi',
'sp-contributions-deleted' => 'vurnayîşê karberî wedariyayê',
'sp-contributions-uploads' => 'barkerdey',
'sp-contributions-logs' => 'qeydi',
'sp-contributions-talk' => 'mesac',
'sp-contributions-userrights' => 'Îdarayê heqqanê karberan',
'sp-contributions-blocked-notice' => 'verniyê no/na karber/e geriyayo/a
qê referansi qeydê vernigrewtışi cêr de eşkera biyo:',
'sp-contributions-blocked-notice-anon' => 'Eno adresê IPi bloke biyo.
Cıkewtışo tewr peyêno ke bloke biyo, cêr seba referansi belikerdeyo:',
'sp-contributions-search' => 'Dekerdena cı geyrê',
'sp-contributions-username' => 'Adresa IP yana namey karberi:',
'sp-contributions-toponly' => 'Tenya rewizyonanê tewr peyniyan bimocne',
'sp-contributions-submit' => 'Cı geyre',

# What links here
'whatlinkshere' => 'Gıreyê pele',
'whatlinkshere-title' => 'Per da "$1" rê perê ke gre danê',
'whatlinkshere-page' => 'Pele:',
'linkshere' => "Ena peleyan grey biya '''[[:$1]]''':",
'nolinkshere' => "Per da '''[[:$1]]''' rê pera ke gıre dana çıniya.",
'nolinkshere-ns' => "Ena cayê nameyî de yew pel zi '''[[:$1]]''' rê link nibeno.",
'isredirect' => 'pera hetenayışi',
'istemplate' => 'Açarnayene',
'isimage' => 'gıreyê dosya',
'whatlinkshere-prev' => '{{PLURAL:$1|veror|veror $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|verni|verni $1}}',
'whatlinkshere-links' => '← gırey',
'whatlinkshere-hideredirs' => 'Hetenayışê $1',
'whatlinkshere-hidetrans' => 'Açarnayışê $1',
'whatlinkshere-hidelinks' => 'Greyê $1',
'whatlinkshere-hideimages' => 'Gıreyê dosya $1',
'whatlinkshere-filters' => 'Avrêci',

# Block/unblock
'autoblockid' => 'Otomatik vındarnayış #$1',
'block' => 'Karberi vındarne',
'unblock' => 'Hesabê karberi akerê',
'blockip' => 'Karberi kılit ke',
'blockip-title' => 'Karberi kılit ke',
'blockip-legend' => 'Karber blok bike',
'blockiptext' => 'pê şuxulnayişê formê cêrıni, şıma eşkeni verniyê vurnayişkerdışê yew karberi ya zi yew IPyi bıgêrî. No têna qey verni-gırewtışê vandalizmiyo u gani şıma [[{{MediaWiki:Policy-url}}|qaydeyan]] re diqqet bıkeri. cêr de muheqqeq sebebê verni-grewtışi bınusi. (mesela: -nê- pelani de vandalizm kerdo).',
'ipadressorusername' => 'Adresa IP yana namey karberi:',
'ipbexpiry' => 'Qedyayış:',
'ipbreason' => 'Sebeb:',
'ipbreasonotherlist' => 'Sebebê bini',
'ipbreason-dropdown' => '*sebebê verni-grewtışi yê pêroyi
** malumatê şaş têare kerdış
** Zerreyê pelan vetış
** keyepelê teberi re gırey eştış
** pelani re qıseyê tewşan(toşan) eştış
** Tehditwari hereket/Taciz
** yew ra ziyed hesaban xırab şuxulnayiş
** nameyê karberi yo ke meqbul niyo',
'ipb-hardblock' => 'KArberê ke ena IP ra dekewte de wa vurnayış nêkerê',
'ipbcreateaccount' => 'Hesab viraştişi blok bik',
'ipbemailban' => 'Ena karber rê destur medî  ke ay e-mail neşiravî',
'ipbenableautoblock' => 'verniyê IPadresa peyin ê no karberi u wexta ke vurnayişi kerd ê IPadresani otomotik bıger.',
'ipbsubmit' => 'Ena karber blok bike',
'ipbother' => 'Waxtê bini:',
'ipboptions' => '2 seat:2 hours,1 roc:1 day,3 roci:3 days,1 hefte:1 week,2 heftey:2 weeks,1 aşm:1 month,3 aşm:3 months,6 aşmi:6 months,1 ser:1 year,ebedi:infinite',
'ipbotheroption' => 'bini',
'ipbotherreason' => 'Sebebê bini:',
'ipbhidename' => 'Nameyê karberî listeyan u vurnayîşan ra binumne',
'ipbwatchuser' => 'Pela miniqaşe u pela ena karberî seyr bike',
'ipb-disableusertalk' => 'No karber wexto ke bloqedeyo wa pela da xodı vurnayış kerdışi rê izin medı',
'ipb-change-block' => 'Pê ena ayaran, karberî reyna bloke bike',
'ipb-confirm' => 'Bloke kerdışi tesdik ke',
'badipaddress' => 'Adresê IPî raşt niyo',
'blockipsuccesssub' => 'Blok biyo',
'blockipsuccesstext' => 'Verniya [[Special:Contributions/$1|$1]] gêriyaya.
<br />Qey çım ra viyarnayişê verni-grewtışi bewni [[Special:BlockList|Ê yê ke verniyê IP adresê cı gêriyaya]].',
'ipb-blockingself' => 'Şımayê kenê ke xo bloke kerê! Şıma qayılye xo bloke kerê?',
'ipb-confirmhideuser' => 'Wexto ke "karberi bınımnê" nışandeyo se şıma ye kenê karberi bloke kerê. No, Namey karberi lista pêron dı u dekewtışê rocekan dı aktiv bo.Şıma qayıli ney bıkerê?',
'ipb-edit-dropdown' => 'Sebebê blokî bivurne',
'ipb-unblock-addr' => '$1 a bik',
'ipb-unblock' => 'Yew adresê IPî ya zi nameyê karberî blok bike',
'ipb-blocklist' => 'Blokî ke hama estê ey bivîne',
'ipb-blocklist-contribs' => 'Ser $1 îştîrakî',
'unblockip' => 'Hesabê karberî a bike',
'unblockiptext' => 'eke şıma qayili ê yê ke verniyê IPadesê inan geriyayê akeri formê cêrıni dekerê.',
'ipusubmit' => 'Ena blok wedarne',
'unblocked' => '[[User:$1|$1]] blok biyo',
'unblocked-range' => "Blokey $1'i wederya",
'unblocked-id' => 'Blokê $1î wedariyayo',
'blocklist' => 'Karberê kılitbiyaey',
'ipblocklist' => 'Karberê kılitbiyaey',
'ipblocklist-legend' => 'Yew karberê blok biyaye bivîne',
'blocklist-userblocks' => 'Wederneyanê hesaba bınımne',
'blocklist-tempblocks' => 'Wederneyanê idaretan bınımne',
'blocklist-addressblocks' => 'Nêverdışanê IP bınımne',
'blocklist-rangeblocks' => 'Nêverdışanê gırda bınımne',
'blocklist-timestamp' => 'İmzay demi',
'blocklist-target' => 'Menzil',
'blocklist-expiry' => 'Wahdey qedyayışi',
'blocklist-by' => 'hizmetdarê blokê',
'blocklist-params' => 'Parametreyê wedernayışi',
'blocklist-reason' => 'Sebeb',
'ipblocklist-submit' => 'Cı geyre',
'ipblocklist-localblock' => 'blokê mehelli',
'ipblocklist-otherblocks' => '{{PLURAL:$1|blokê|blokê}} bini',
'infiniteblock' => 'ebedî',
'expiringblock' => 'roca $1i saeta $2i de qediyena',
'anononlyblock' => 'teyna karbero anonim',
'noautoblockblock' => 'otoblok nihebitîyeno',
'createaccountblock' => 'Hesab viraştîş blok biyo',
'emailblock' => 'e-mail blok biyo',
'blocklist-nousertalk' => 'ti nieşken pele minaqaşe xo bivurne',
'ipblocklist-empty' => 'Listeyê blokî veng o.',
'ipblocklist-no-results' => 'Adresa IPya waştiye ya zi namey karberi kılit nêbiyo.',
'blocklink' => 'kılit ke',
'unblocklink' => 'bloqi hewad',
'change-blocklink' => 'kılit-kerdışi bıvurne',
'contribslink' => 'iştıraqi',
'emaillink' => 'e-poste bırışe',
'autoblocker' => 'Şıma otomatikmen kılit biy, çıke adresa şımawa \'\'IP\'\'y terefê "[[User:$1|$1]]" gureniyena.
Sebebê kılit-biyayışê $1\'i: "$2"o',
'blocklogpage' => 'Qeydê bloqi',
'blocklog-showlog' => 'verniyê no/na karberi cıwa ver geriyayo/ya.',
'blocklog-showsuppresslog' => 'verniyê no/na karberi cıwa ver geriyayo/ya.',
'blocklogentry' => 'Karberê [[$1]] ke bloqe, bloqey cı hetana $2 $3 do bıramo.',
'reblock-logentry' => 'qey [[$1]]i tarixê qediyayişi $2 $3 pa ninan a eyarê ver-grewtışan vurna.',
'blocklogtext' => "No kuliyatê kılitkerdış u rakerdışê fealiyetê karberano.
Adresê IP'ya ke otomatikmen kılit biyê lista de çıniya.
Seba lista karberanê ke heta nıka kılit biyê [[Special:BlockList|lista kılitkerdışê IPy]] bıvinê.",
'unblocklogentry' => '$1 ake',
'block-log-flags-anononly' => 'karberê anomini tenya',
'block-log-flags-nocreate' => 'akerdışê hesabi racneyayo',
'block-log-flags-noautoblock' => 'Oto-wedariye terkneyayo',
'block-log-flags-noemail' => 'e-posta biya bloqe',
'block-log-flags-nousertalk' => 'Pela verênayişi ke xo nêşeno bıvurno',
'block-log-flags-angry-autoblock' => 'oto-wedariye amayen aktivo',
'block-log-flags-hiddenname' => 'nameyê karberi nımteyo',
'range_block_disabled' => 'Desturê administorî ke viraştişê blokê rangeyî kefiliyo.',
'ipb_expiry_invalid' => 'Wextê qediyayışi nêvêreno.',
'ipb_expiry_temp' => 'Kılitbiyayışê karberê nımıtey gani ebedi bo.',
'ipb_hide_invalid' => 'hesabê karberi pinani nêbeno; belka semedê zaf vurnayişi ra yo.',
'ipb_already_blocked' => '"$1" zaten blok biya',
'ipb-needreblock' => '$1 xora engel biyo. Tı wazenay eyaran bıvurnê?',
'ipb-otherblocks-header' => '{{PLURAL:$1|Kılitkerdışo bin|Kılitkerdışê bini}}',
'unblock-hideuser' => 'NAmeyê karberi nımneyayo qandê coy şıma nêşenê bloqey cı wedarnê.',
'ipb_cant_unblock' => 'xeta: IDyê ver-grewtışi $1 nêesa/asa.
belka ver-grewtış wedariyayo.',
'ipb_blocked_as_range' => 'xeta: $1 verniyê IPadresi direk nêgeriyayo u ver-gırewtışi nêwedariyeno .
labele parçeya benateyê na $2 adresibi u ey ra ver-geryayo u şıma eşkeni no wedari.',
'ip_range_invalid' => 'Rêza IPi nêvêrena.',
'ip_range_toolarge' => 'Menzilan ke /$1 ra girdêrê inan rê izin nidano.',
'blockme' => 'Mi blok bik',
'proxyblocker' => 'blokarê proxyi',
'proxyblocker-disabled' => 'Eno fonksiyon nêxebetiyeno.',
'proxyblockreason' => 'IPadresa şıma yew proxyo akerdeyo u ey ra verniyê ey geriya.',
'proxyblocksuccess' => 'Qeyd ke.',
'sorbs' => 'DNSBL',
'sorbsreason' => 'IP adresa şıma, hetê no {{SITENAME}} keyepeli ra  DNSBL de proxy hesibyayo u liste biyo.',
'sorbs_create_account_reason' => 'IP adresa şıma, hetê no translatewiki.net keyepeli ra DNSBL de proxy hesibyayo u liste biyo.

şıma neeşkeni hesab bıvırazi',
'cant-block-while-blocked' => 'Ê ye ke verniyê şıma gırewtî şıma nêeşkeni verniyê ninan bıgeri',
'cant-see-hidden-user' => 'karber o ke şıma gêreni verniyê ey bıgeri ca ra verniyê ey gırewteyo u pinani kerdeyo.',
'ipbblocked' => 'Ti nieşkena karberanê binan bloke bike ya zi a bike cunki ti bloke biya',
'ipbnounblockself' => 'Ti nieşkena xo a bike',

# Developer tools
'lockdb' => 'Database kilit bik',
'unlockdb' => 'Database a bik',
'lockdbtext' => 'qefelnayişê databaseyi: pelê pêro karberan, tercihê ninan uêb vındarneno.
eke şıma ıney gure keni u şıma xo ra emini, taahhud bıde wexta gure şıma qediya şıma database keni a.',
'unlockdbtext' => 'akerdışê qeflıkê databaseyi; pêro karberani re pelan keno a, imkanê vurnayişê tercih u listeya temaşakerdışi dan.
şıma raşta qayili no gure bıkeri u eke şıma qayili teyid bıkerê.',
'lockconfirm' => 'Ya, ez wazene database kilit bikeri.',
'unlockconfirm' => 'Ya, ez wazene database a bikeri.',
'lockbtn' => 'Database kilit bik',
'unlockbtn' => 'Database a bik',
'locknoconfirm' => 'Şıma qutiyê araşt kerdışi nêweçinê.',
'lockdbsuccesssub' => 'Database kilit biya',
'unlockdbsuccesssub' => 'Database a biya',
'lockdbsuccesstext' => 'database qefıliya.<br />
wexta mıqat/qayt kewtışi databaseyê şıma qediya u xo vir ra mekerê[[Special:UnlockDB|qeflıkê databaseyi akerê]].',
'unlockdbsuccesstext' => 'Database a biya.',
'lockfilenotwritable' => 'dosyaya qefılnayişê databaseyi ser ra çiyek nênusyena.',
'databasenotlocked' => 'Database a nibiya.',
'lockedbyandtime' => '({{GENDER:$1|$1}} ra $2 tepya $3 biyo)',

# Move page
'move-page' => '$1 Bere',
'move-page-legend' => 'Pele bere',
'movepagetext' => "Pe form ki ho bın de, tı eşkeno name yew pele bıvurni u tarixê pele hemi ya zi pyeran beri.
Ma nameyê kıhanyeri keno pele redireksiyoni ser nameyê newe.
Tı eşkeno pele redireksiyoni ki şıno nameyê originali bıvurni.
Eg tı nıwazeno, ma tı ra rica keni tı [[Special:DoubleRedirects|double]] ya zi [[Special:BrokenRedirects|broken redirects]] qontrol bıki.
Tı gani qontrol bıki eg gıreyan şıno peleyanê raşti.

Teme eka ser yew name de yew nuşte esti, sistemê ma '''nıeşkeno''' nuşte tı beri. Eka ser ena name de yew pele vengi esti, sistemê ma eşkeno nuşte tı beri.
Tı nıeşkeni name yew pele reyna bıvurni.

'''Teme!'''
Ena transfer ser peleyanê populari zaf muhumo;
Ma tu ra rica keni, tı en verni dı qontrol bıki u bışıravi.",
'movepagetext-noredirectfixer' => "Pe form ki ho bın de, tı eşkeno name yew pele bıvurni u tarixê pele hemi ya zi pyeran beri.
Ma nameyê kıhanyeri keno pele redireksiyoni ser nameyê newe.
Tı eşkeno pele redireksiyoni ki şıno nameyê originali bıvurni.
Eg tı nıwazeno, ma tı ra rica keni tı [[Special:DoubleRedirects|raçarnayışo dılet]] ya zi [[Special:BrokenRedirects|raçarnayışo xırab]]i qontrol bıke.
Tı gani qontrol bıki eg gıreyan şıno peleyanê raşti.

Teme eka ser yew name de yew nuşte esti, sistemê ma '''nıeşkeno''' nuşte tı beri. Eka ser ena name de yew pele vengi esti, sistemê ma eşkeno nuşte tı beri.
Tı nıeşkeni name yew pele reyna bıvurni.

'''İkaz!'''
Ena transfer ser peleyanê populari zaf muhumo;
Ma tu ra rica keni, tı en verni dı qontrol bıki u bışıravi.",
'movepagetalktext' => "Ma peleyê mınaqeşeyê ena pele otomatik beno, '''ma nıeşken ber, eg:'''
*Yew peleyê mınaqeşeyê ser ena name rona esto, ya zi
*Tı quti check nıkerd.

Oturse, tı gani peleyê mınaqeşeyê manually beri.",
'movearticle' => 'Pele bere:',
'moveuserpage-warning' => "'''Diqet:''' Ti eka yew pelê karberi beni. Diqet bike teyna pel beni feqat ena pele reyna nameyê newi \"nebeno''.",
'movenologin' => 'Şıma de nêkewtê',
'movenologintext' => 'qey vurnayişê nameyê peli şıma gani qeyd kerde u cıkewteyê [[Special:UserLogin|sistemi]] bıbiy.',
'movenotallowed' => 'desturê şıma çino, şıma pelan bıkırışi',
'movenotallowedfile' => 'desturê şıma çino, şıma pelan bıkırışi',
'cant-move-user-page' => 'desturê şıma çino, şıma pelanê karberani bıkırışi (bê pelê cerıni).',
'cant-move-to-user-page' => 'desturê şıma çino, şıma yew peli bıkırışi pelê yew karberi.',
'newtitle' => 'Nameyê newi:',
'move-watch' => 'Peler seyr ke',
'movepagebtn' => 'Pele bere',
'pagemovedsub' => 'Berdışi kerd temam',
'movepage-moved' => '\'\'\'"$1" berd "$2"\'\'\'',
'movepage-moved-redirect' => 'yew rayberdışi vıraziya',
'movepage-moved-noredirect' => 'yew rayberdışi çap bı',
'articleexists' => 'Ena nameyê pela database ma dı esta ya zi tı raşt nınuşt. .
Yewna name bınus.',
'cantmove-titleprotected' => 'şıma nêşkeni yew peli bıhewelnê tiya çunke pawıyeno',
'talkexists' => "'''Ma ena pele berd. Feqet pele mıneqeşe dı yew problem esto. Çınki ser name newe dı yew pele rona esto. Eq tı eşkeno, pele mıneqeşe manually beri.'''",
'movedto' => 'berd be',
'movetalk' => 'Pela werênayışiê elaqedare bere',
'move-subpages' => 'pelê bınini bıkırış($1 heta tiya)',
'move-talk-subpages' => 'pelê bınini yê pelê werê ameyeşi bıkırış ($1 heta tiya)',
'movepage-page-exists' => 'maddeya $1i ca ra esta u newe ra otomatikmen nênusyena.',
'movepage-page-moved' => 'pelê $1i kırışiya pelê $2i.',
'movepage-page-unmoved' => 'pelê $1i nêkırışiyeno sernameyê $2i.',
'movepage-max-pages' => 'tewr ziyed $1 {{PLURAL:$1|peli|peli}} kırışiya u hıni ziyedê ıney otomotikmen nêkırışiyeno.',
'movelogpage' => 'Qeydê berdışi',
'movelogpagetext' => 'nameyê liste ya ke cêr de yo, pelê vuriyayeyani mocneno',
'movesubpage' => '{{PLURAL:$1|Subpage|pelê bınıni}}',
'movesubpagetext' => '{{PLURAL:$1|pelê bınıni yê|pelê bınıni yê}} no $1 peli cer de yo.',
'movenosubpage' => 'pelê bınıni yê no peli çino.',
'movereason' => 'Sebeb:',
'revertmove' => 'peyser bia',
'delete_and_move' => 'Biestere u bere',
'delete_and_move_text' => '==gani hewn a bıbıo/bıesteriyo==

" no [[:$1]]" name de yew pel ca ra esto. şıma wazeni pê hewn a kerdışê ey peli vurnayişê nameyi bıkeri?',
'delete_and_move_confirm' => 'Ya, ena pele biestere',
'delete_and_move_reason' => '"[[$1]]" qey vurnayişê nameyi esteriya',
'selfmove' => 'name yo ke şıma wazeni bıbo, ın name û name yo ke ca ra esto eyni yê /zepê yê. vurnayiş mumkin niyo.',
'immobile-source-namespace' => '"$1" pelê cayi de nameyi nêkırışyenî',
'immobile-target-namespace' => 'peli nêkırışiyeni "$1" cayê nameyan',
'immobile-target-namespace-iw' => 'xetê benatê wikiyan, hedefê pelkırıştış niyo',
'immobile-source-page' => 'nameyê no peli nêvuriyeno',
'immobile-target-page' => 'sernameyê no hedefi re nêkırışiyeno',
'imagenocrossnamespace' => 'Dosya, ca yo ke qey nameyê dosyayan nêbıbo nêkırışiyeno',
'nonfile-cannot-move-to-file' => 'Ekê dosya niyê, cade namande dosyaya nêahulneyênê',
'imagetypemismatch' => 'tipa dosyaya neweyi re pênêgıneno/nêgıneno pê',
'imageinvalidfilename' => 'nameyê dosyayi ya hedefi meqbul niyo.',
'fix-double-redirects' => 'rayberdış ê ke sernameyê orjinali re işaret keni rocane bıker.',
'move-leave-redirect' => 'pey de yew rayberdış roni',
'protectedpagemovewarning' => "'''Diqet: No pel pawyeno, teyna serkari eşkeni bıvurni.'''
Wexta ke şıma no pel vurneni diqet bıkeri, loge bivini:",
'semiprotectedpagemovewarning' => "'''Diqet: No pel pawyeno, teyna serkari eşkeni bıvurni.'''
Wexta ke şıma no pel vurneni diqet bıkeri, loge bivini:",
'move-over-sharedrepo' => '== Dosya esto ==
[[:$1]] enbar ma de esto. Eka ti wazeno ena dosyo ser ena sername de bero, yewna dosya sero nusiyeno.',
'file-exists-sharedrepo' => 'Ena sername zaten embar ma de esto.
Ma rica keno yewna sername binuse.',

# Export
'export' => 'Pela ateber dı',
'exporttext' => 'şıma yew pelê nişanebiyayeyi, nuşteyê taqımê pelani, pê pêşteyê XMLi eşkeni bıdi teberi.
wiki yo ke wikimedya xebıtneno, pê [[Special:Import|pelê zerre dayişê]] no wikiyi beno.

şıma eşkeni yew gırey bıerzi,
ma vaci: qey pelê "[[{{MediaWiki:Mainpage}}]]i " [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]].',
'exportall' => 'Pela Pêron ateberdı',
'exportcuronly' => 'têna revizyonê peyin bıger',
'exportnohistory' => "----
'''Not:''' pê no form teberdayişê verê (tarix) pelan battal biyo",
'exportlistauthors' => 'zerre de qandê her pela listey iştiraxkara esto',
'export-submit' => 'Teber de',
'export-addcattext' => 'kategoriya cerıni ra maddeyan têare ker',
'export-addcat' => 'têare ker',
'export-addnstext' => 'pelan cayê nameyan ra têare ker',
'export-addns' => 'têare ker',
'export-download' => 'yewna qaydeyi de qeydker',
'export-templates' => 'şablonan daxil ker',
'export-pagelinks' => 'behsê xorıniya pelê pêrabesteyani:',

# Namespace 8 related
'allmessages' => 'Mesacê sistemi',
'allmessagesname' => 'Name',
'allmessagesdefault' => 'Metnê mesacê hesabiyayey',
'allmessagescurrent' => 'Nuşteyê mesacê rocaney',
'allmessagestext' => 'na liste, listeya mesajê cayê nameyê wikimedya yo.
eke şıma qayili paşt bıdi mahalli kerdışê wikimedyayi, kerem kerê pelê [//www.mediawiki.org/wiki/Localisation mahalli kerdışê wikimedyayi] u [//translatewiki.net translatewiki.net] ziyaret bıkerê.',
'allmessagesnotsupportedDB' => "'''\$wgUseDatabaseMessages''' qefelnaye yo u ey ra '''{{ns:special}}:Allmessages''' karkerdışi re akerde niyo.",
'allmessages-filter-legend' => 'Avrêc',
'allmessages-filter' => 'goreyê xususi kerdışi re filtre bıker',
'allmessages-filter-unmodified' => 'Nivurnaye',
'allmessages-filter-all' => 'Pêro',
'allmessages-filter-modified' => 'Vurnaye',
'allmessages-prefix' => 'pê prefiks filtre bıker',
'allmessages-language' => 'Zıwan:',
'allmessages-filter-submit' => 'Şo',

# Thumbnails
'thumbnail-more' => 'Gırd ke',
'filemissing' => 'Dosya biya vini',
'thumbnail_error' => 'Thumbnail niviraziya: $1',
'djvu_page_error' => 'pelê DjVuyi bêşumulo',
'djvu_no_xml' => 'Qe DjVu nieşkenî XML fetch bikî',
'thumbnail-temp-create' => 'İdare dosyay resimiya nêvırazêna',
'thumbnail-dest-create' => 'Resimo werdiyo keyd nêbeno',
'thumbnail_invalid_params' => 'Parametreya thumbnailî raşt niyşê',
'thumbnail_dest_directory' => 'Nieşkenî direktorê destinasyonî virazî',
'thumbnail_image-type' => 'Tipê resimî kebul nibeno',
'thumbnail_gd-library' => 'Configurasyonê katalog ê GDî tam niyo:funksiyonê $1î vînî biyo',
'thumbnail_image-missing' => 'Dosya vînî biyo: $1',

# Special:Import
'import' => 'Peleyi import bik',
'importinterwiki' => 'Împortê transwîkî',
'import-interwiki-text' => 'qey kırıştışê zerreyi yew wiki u pel bıvıcinê.
tarixê revizyon u nameyê nuştoxi pawyene.
karê zerredayişê benateyê wikiyani[[Special:Log/import|zerreyê rocaneyê kırıştî de]] qeyd beno.',
'import-interwiki-source' => 'Çime wîkî/pel:',
'import-interwiki-history' => 'Qe eno pel, revizyonê tarixê hemî kopya bike',
'import-interwiki-templates' => 'Şablonê hemî dehil bike',
'import-interwiki-submit' => 'Azare de',
'import-interwiki-namespace' => 'Destinasyonê canameyî:',
'import-interwiki-rootpage' => 'Hedef pelaya reçi (opsiyonel):',
'import-upload-filename' => 'Nameyê dosyayi:',
'import-comment' => 'Vatış:',
'importtext' => 'Kerem ke dosyay, çımeyê wiki ra pê [[Special:Export|kırıştışê teberdayişi]] bıdê teber, Komputerê xo de qeyd kerê u bar kerê tiya.',
'importstart' => 'Pelan împort kenî',
'import-revision-count' => '$1 {{PLURAL:$1|revizyon|revizyon}}',
'importnopages' => 'Pel çino ke import bike',
'imported-log-entries' => ' $1 {{PLURAL:$1|logê dekerdişi|loganê dekerdişan}} ard.',
'importfailed' => 'Împort nebiy: <nowiki>$1</nowiki>',
'importunknownsource' => 'Çimeyê tip ê împortî nizanyano',
'importcantopen' => 'Nieşkenî dosyayê împortî a bike',
'importbadinterwiki' => 'Linkê înterwîkîyî nihebitiyeno',
'importnotext' => 'Veng o ya zi tede nuşte çini yo',
'importsuccess' => 'Împort qediya!',
'importhistoryconflict' => 'verê revizyon ê ke pêverdiyaye yê tiya de mewcud o (no pel, belka cıwa ver kırışiyayo zerreyi)',
'importnosources' => 'çımeyê kırıştışê zerredayişi nidiyo şınasnayişi u barbiyayişê verıni battal verdiyo.',
'importnofile' => 'Yew zi dosyayê împortî bar nibiyo.',
'importuploaderrorsize' => "barbiyayişê kırıştışê zerredayişi nibı.
gırdiyê dosyayi, gırdî yo ke musa'ade biyo ıney gırdıyî ra gırd o.",
'importuploaderrorpartial' => 'barbiyayişê kırıştışê zerredayişi nibı.
têna yew qısımê dosyayi ey bar bı',
'importuploaderrortemp' => 'barbiyayişê kırıştışê zerredayişi nibı.
dosyaya emaneti vindbiyo',
'import-parse-failure' => 'Împortê XML-parse nebiyo',
'import-noarticle' => 'Pel çino ke împort bike!',
'import-nonewrevisions' => 'Revizyonê hemi vernî de împort biyê.',
'xml-error-string' => '$1 çizgi de $2 col $3 (bit $4): $5',
'import-upload' => 'Dosyayê XML bar bike',
'import-token-mismatch' => "vindibiyayişê ma'lumatê hesabi. kerem kerê newe ra tesel/cereb bıkerê.",
'import-invalid-interwiki' => 'Ena wiki ra azere kerdış nêbeno.',
'import-error-edit' => 'Pela " $1 " qandê vurnayışi aya nêgêrêna çıkı cı rê icazet nêdeyayo.',
'import-error-create' => 'Pela " $1 " qandê vıraştışi aya nêabêna çıkı cı rê icazet nêdeyayo.',
'import-error-interwiki' => 'Pela " $1 " qandê name dayışi aya nêgêrêna çıkı namey cı (interwiki) sero cırê ca abıryayo.',
'import-error-special' => 'Pela " $1 " qandê vıraştışi aya nêgêrêna çıkı namay cı nameyo do xısusiyo u na pela rê no name nêgêrêno.',
'import-error-invalid' => 'Pela "$1" nêdebyê de çıkı namey cı çınyo.',
'import-options-wrong' => '{{PLURAL:$2|Weçenego|Weçenego}} xerpiyaye: <nowiki>$1</nowiki>',
'import-rootpage-invalid' => 'Sernuştey ena pela reçey cı raverde niyo.',
'import-rootpage-nosubpage' => 'Qan de bınnaman reçe de "$1" re mısade nedano.',

# Import log
'importlogpage' => 'Defterê seyırio idxal',
'importlogpagetext' => 'wiki yo ke nişane biyo tera kırıştışê zerredayişi nêbeno.',
'import-logentry-upload' => 'dosyayê bar kerdişî ra [[$1]] împort biyo',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|çımraviyarnayış|çımraviyarnayışi}}',
'import-logentry-interwiki' => '$1 transwiki biyo',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|revizyon|revizyonî}} $2 ra',

# JavaScriptTest
'javascripttest' => 'Cerebnayışê JavaScripti',
'javascripttest-disabled' => 'Na kerdin, na wiki sero aktiv nêbiya.',
'javascripttest-title' => 'Testê $1 gurweyênê',
'javascripttest-pagetext-noframework' => 'Na pela testanê JavaScripta gurweynayışi re abıryaya.',
'javascripttest-pagetext-unknownframework' => 'Çerçeweyê "$1" cerbnayışi xırabo.',
'javascripttest-pagetext-frameworks' => 'Şıma ra reca xorê cêr ra test weçinê:$1',
'javascripttest-pagetext-skins' => 'Testa akarfinayışi rê verqayt:',
'javascripttest-qunit-intro' => 'Mediawiki.org dı [dokumanê $1] bıvinê.',
'javascripttest-qunit-heading' => 'MediaWiki JavaScript QUnit test suite',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Pelaya karberi',
'tooltip-pt-anonuserpage' => 'pelê karberê IPyi',
'tooltip-pt-mytalk' => 'Pela ya Qıse vatışi',
'tooltip-pt-anontalk' => 'vurnayiş ê ke no Ipadresi ra biyo muneqeşa bıker',
'tooltip-pt-preferences' => 'Tercihê to',
'tooltip-pt-watchlist' => 'Lista pelanê ke to gırewtê seyrkerdış',
'tooltip-pt-mycontris' => 'Yew lista iştıraqanê şıma',
'tooltip-pt-login' => 'Mayê şıma ronıştış akerdışi rê dawet keme; labelê ronıştış mecburi niyo',
'tooltip-pt-anonlogin' => 'Seba cıkewtışi şıma rê dewato; labelê, no zeruri niyo',
'tooltip-pt-logout' => 'Bıveciye',
'tooltip-ca-talk' => 'Zerrey pela sero werênayış',
'tooltip-ca-edit' => 'Tı şenay na pele bıvurnê.
Kerem ke, qeydkerdış ra ver gocega verqayti bıxebetne.',
'tooltip-ca-addsection' => 'Yew qısımo newe ake',
'tooltip-ca-viewsource' => 'Ena pele kılit biya.
Şıma şenê çımeyê aye bıvênê',
'tooltip-ca-history' => 'Versiyonê verênê ena pele',
'tooltip-ca-protect' => 'Ena pele kılit ke',
'tooltip-ca-unprotect' => 'Starkerdışe ena peler bıvurne',
'tooltip-ca-delete' => 'Ena perer besternê',
'tooltip-ca-undelete' => 'peli biyarê halê ver hewnakerdışi',
'tooltip-ca-move' => 'Ena pele bere',
'tooltip-ca-watch' => 'Ena pele lista xoya seyrkerdışi ke',
'tooltip-ca-unwatch' => 'Ena pele listeya seyir-kerdışi xo ra bıvec',
'tooltip-search' => 'Zerreyê {{SITENAME}} de cı geyre',
'tooltip-search-go' => 'Ebe nê namey tami şo yew pela ke esta',
'tooltip-search-fulltext' => 'Nê  metni peran dı cı geyre',
'tooltip-p-logo' => 'Pela seri bıvênên',
'tooltip-n-mainpage' => 'Şo pela seri',
'tooltip-n-mainpage-description' => 'Şo pela seri',
'tooltip-n-portal' => 'Heqa projey de, kes çı şeno bıkero, çıçiyo koti deyo',
'tooltip-n-currentevents' => 'Vurnayışanê peyênan de melumatê pey bıvêne',
'tooltip-n-recentchanges' => 'Wiki de lista vurnayışanê peyênan',
'tooltip-n-randompage' => 'Şırê pera ke raştameyê',
'tooltip-n-help' => 'Cayê doskerdışi',
'tooltip-t-whatlinkshere' => 'Lista pelanê wikiya pêroina ke tiya gırê bena',
'tooltip-t-recentchangeslinked' => 'Vurnayışê peyênê pelanê ke ena pela ra gırê biyê',
'tooltip-feed-rss' => 'RSS feed qe ena pele',
'tooltip-feed-atom' => 'Qe ena pele atom feed',
'tooltip-t-contributions' => 'İştirakanê ena karber bevin',
'tooltip-t-emailuser' => 'Ena karber ri yew email bışırav',
'tooltip-t-upload' => 'Dosya bar ke',
'tooltip-t-specialpages' => 'Yew lista pelanê xasanê pêroyinan',
'tooltip-t-print' => 'Nımuney çapkerdışiê ena pele',
'tooltip-t-permalink' => 'Gırêyo daimi be ena versiyonê pele',
'tooltip-ca-nstab-main' => 'Pela zerreki bımocne',
'tooltip-ca-nstab-user' => 'Pela karberi bıvin',
'tooltip-ca-nstab-media' => 'Pele Mediya bivinên',
'tooltip-ca-nstab-special' => 'Na yew pelê da xususiya, şıma nêşenê nae bıvurnê',
'tooltip-ca-nstab-project' => 'Pela procey bıvêne',
'tooltip-ca-nstab-image' => 'Pelay dosya bımocne',
'tooltip-ca-nstab-mediawiki' => 'Mesacê sistemi bivinên',
'tooltip-ca-nstab-template' => 'Şabloni bıvinê',
'tooltip-ca-nstab-help' => 'Peleyê yardimi bivinên',
'tooltip-ca-nstab-category' => 'Pele kategoriyan bevinin',
'tooltip-minoredit' => 'Eno vurnayışê qıçkeko',
'tooltip-save' => 'Vurnayışanê xo qeyd ke',
'tooltip-preview' => 'Vurnayışê xo bıvin. Verniyê qeyd kerdışi, vurnayışê xo ena pele dı control bık.',
'tooltip-diff' => 'Metni sero vurnayışan mocneno',
'tooltip-compareselectedversions' => 'Ena per de ferqê rewziyonan de dı weçinaya bıvinê',
'tooltip-watch' => 'Eno pele listey tıyo seyir-kerdişi ri dek',
'tooltip-watchlistedit-normal-submit' => 'Sernuşteya hewad',
'tooltip-watchlistedit-raw-submit' => 'Listeyê seyri newen ke',
'tooltip-recreate' => 'pel hewn a bışiyo zi tepiya biya',
'tooltip-upload' => 'Dest be barkerdışi ke',
'tooltip-rollback' => '"Peyser bia" be yew tık pela iştıraq(an)ê peyên|i(an) peyser ano.',
'tooltip-undo' => '"Undo" ena vurnayışê newi iptal kena u vurnayışê verni a kena.
Tı eşkeno yew sebeb bınus.',
'tooltip-preferences-save' => 'Terciha qeyd ke',
'tooltip-summary' => 'Yew xulasaya kilm binuse',

# Scripts
'common.js' => "/**
 * Keep code in MediaWiki:Common.js to a minimum as it is unconditionally
 * loaded for all users on every wiki page. If possible create a gadget that is
 * enabled by default instead of adding it here (since gadgets are fully
 * optimized ResourceLoader modules with possibility to add dependencies etc.)
 *
 * Since common.js isn't a gadget, there is no place to declare its
 * dependencies, so we have to lazy load them with mw.loader.using on demand and
 * then execute the rest in the callback. In most cases these dependencies will
 * be loaded (or loading) already and the callback will not be delayed. In case a
 * dependency hasn't arrived yet it'll make sure those are loaded before this.
 */
mw.loader.using( 'mediawiki.util', function() {
/* Begin of mw.loader.using callback */

/**
 * Redirect User:Name/skin.js and skin.css to the current skin's pages
 * (unless the 'skin' page really exists)
 * @source: http://www.mediawiki.org/wiki/Snippets/Redirect_skin.js
 * @rev: 2
 */
if ( mw.config.get( 'wgArticleId' ) === 0 && mw.config.get( 'wgNamespaceNumber' ) == 2 ) {
	var titleParts = mw.config.get( 'wgPageName' ).split( '/' );
	// Make sure there was a part before and after the slash
	// And that the latter is 'skin.js' or 'skin.css'
	if ( titleParts.length == 2 ) {
		var userSkinPage = titleParts.shift() + '/' + mw.config.get( 'skin' );
		if ( titleParts.slice(-1) == 'skin.js' ) {
			window.location.href = mw.util.wikiGetlink( userSkinPage + '.js' );
		} else if ( titleParts.slice(-1) == 'skin.css' ) {
			window.location.href = mw.util.wikiGetlink( userSkinPage + '.css' );
		}
	}
}

/** Map addPortletLink to mw.util 
 */
window.addPortletLink = function() {
    return mw.util.addPortletLink.apply( mw.util, arguments );
};

/** extract a URL parameter from the current URL **********
 *
 * @deprecated: Use mw.util.getParamValue with proper escaping
 */
window.getURLParamValue = function() {
    return mw.util.getParamValue.apply( mw.util, arguments );
};

/** &withCSS= and &withJS= URL parameters *******
 * Allow to try custom scripts from MediaWiki space 
 * without editing personal .css or .js files
 */
var extraCSS = mw.util.getParamValue(\"withCSS\");
if ( extraCSS && extraCSS.match(/^MediaWiki:[^&<>=%]*\\.css\$/) ) {
    importStylesheet(extraCSS);
}
var extraJS = mw.util.getParamValue(\"withJS\");
if ( extraJS && extraJS.match(/^MediaWiki:[^&<>=%]*\\.js\$/) ) {
    importScript(extraJS);
}


/* Import more specific scripts if necessary */
if (wgAction == 'edit' || wgAction == 'submit' || wgPageName == 'Special:Upload') { //scripts specific to editing pages
    importScript('MediaWiki:Common.js/edit.js');
}
else if (mw.config.get('wgPageName') == 'Special:Watchlist') { //watchlist scripts
    mw.loader.load(mw.config.get('wgServer') + mw.config.get('wgScript') + '?title=MediaWiki:Common.js/watchlist.js&action=raw&ctype=text/javascript&smaxage=21600&maxage=86400');
}

if ( wgNamespaceNumber == 6 ) {
    importScript('MediaWiki:Common.js/file.js');
}

/**
 * WikiMiniAtlas
 *
 *  Description: WikiMiniAtlas is a popup click and drag world map.
 *               This script causes all of our coordinate links to display the WikiMiniAtlas popup button.
 *               The script itself is located on meta because it is used by many projects.
 *               See [[Meta:WikiMiniAtlas]] for more information. 
 *  Maintainers: [[User:Dschwen]]
 */

mw.loader.load('//meta.wikimedia.org/w/index.php?title=MediaWiki:Wikiminiatlas.js&action=raw&ctype=text/javascript&smaxage=21600&maxage=86400');

/* Scripts specific to Internet Explorer */
if (\$.client.profile().name == 'msie') {
    /** Internet Explorer bug fix **************************************************
     *
     *  Description: Fixes IE horizontal scrollbar bug
     *  Maintainers: [[User:Tom-]]?
     */
    
    var oldWidth;
    var docEl = document.documentElement;
    
    var fixIEScroll = function() {
        if (!oldWidth || docEl.clientWidth > oldWidth) {
            doFixIEScroll();
        } else {
            setTimeout(doFixIEScroll, 1);
        }
        
        oldWidth = docEl.clientWidth;
    };
    
    var doFixIEScroll = function () {
        docEl.style.overflowX = (docEl.scrollWidth - docEl.clientWidth < 4) ? \"hidden\" : \"\";
    };
    
    document.attachEvent(\"onreadystatechange\", fixIEScroll);
    document.attachEvent(\"onresize\", fixIEScroll);
    
    // In print IE (7?) does not like line-height
    mw.util.addCSS('@media print { sup, sub, p, .documentDescription { line-height: normal; } }');

    // IE overflow bug
    mw.util.addCSS('div.overflowbugx { overflow-x: scroll !important; overflow-y: hidden !important; } '
      + 'div.overflowbugy { overflow-y: scroll !important; overflow-x: hidden !important; }');

    // IE zoomfix
    // Use to fix right floating div/table inside tables
    mw.util.addCSS('.iezoomfix div, .iezoomfix table { zoom: 1; }');

    // Import scripts specific to Internet Explorer 6
    if (\$.client.profile().versionBase == '6') {
        importScript('MediaWiki:Common.js/IE60Fixes.js');
    }
}

/* Fixes for Windows XP font rendering */
if (navigator.appVersion.search(/windows nt 5/i) != -1) {
    mw.util.addCSS('.IPA {font-family: \"Lucida Sans Unicode\", \"Arial Unicode MS\";} ' + 
                   '.Unicode {font-family: \"Arial Unicode MS\", \"Lucida Sans Unicode\";}');
}

/* Helper script for .hlist class in Common.css
 * Last updated: September 12, 2012
 * Maintainer: [[User:Edokter]]
 */
 
if ( \$.client.profile().name == 'msie' ) {
    /* Add pseudo-selector class to last-child list items in IE 8 */
    if ( \$.client.profile().versionBase == '8' ) {
        \$( '.hlist' ).find( 'dd:last-child, dt:last-child, li:last-child' )
            .addClass( 'hlist-last-child' );
    }
    /* Generate interpuncts and parens for IE < 8 */
    if ( \$.client.profile().versionBase < '8' ) {
        var hlists = \$( '.hlist' );
        hlists.find( 'dt:not(:last-child)' )
            .append( ': ' );
        hlists.find( 'dd:not(:last-child)' )
            .append( '<b>·</b> ' );
        hlists.find( 'li:not(:last-child)' )
            .append( '<b>·</b> ' );
        hlists.find( 'dl dl, ol ol, ul ul' )
            .prepend( '( ' ).append( ') ' );
    }
}

/* Test if an element has a certain class
 * Maintainers: [[User:Mike Dillon]], [[User:R. Koot]], [[User:SG]]
 *
 * @deprecated:  Use \$(element).hasClass() instead.
 */

window.hasClass = ( function() {
    var reCache = {};
    return function (element, className) {
        return (reCache[className] ? reCache[className] : (reCache[className] = new RegExp(\"(?:\\\\s|^)\" + className + \"(?:\\\\s|\$)\"))).test(element.className);
    };
})();


/** Interwiki links to featured articles ***************************************
 *
 *  Description: Highlights interwiki links to featured articles (or
 *               equivalents) by changing the bullet before the interwiki link
 *               into a star.
 *  Maintainers: [[User:R. Koot]]
 */

function LinkFA() {
    if ( document.getElementById( \"p-lang\" ) ) {
        var InterwikiLinks = document.getElementById( \"p-lang\" ).getElementsByTagName( \"li\" );

        for ( var i = 0; i < InterwikiLinks.length; i++ ) {
            if ( document.getElementById( InterwikiLinks[i].className + \"-fa\" ) ) {
                InterwikiLinks[i].className += \" FA\";
                InterwikiLinks[i].title = \"This is a featured article in another language.\";
            } else if ( document.getElementById( InterwikiLinks[i].className + \"-ga\" ) ) {
                InterwikiLinks[i].className += \" GA\";
                InterwikiLinks[i].title = \"This is a good article in another language.\";
            }
        }
    }
}

\$( LinkFA );


/** Collapsible tables *********************************************************
 *
 *  Description: Allows tables to be collapsed, showing only the header. See
 *               [[Wikipedia:NavFrame]].
 *  Maintainers: [[User:R. Koot]]
 */

var autoCollapse = 2;
var collapseCaption = \"bınımne\";
var expandCaption = \"bıvin\";

window.collapseTable = function( tableIndex ){
    var Button = document.getElementById( \"collapseButton\" + tableIndex );
    var Table = document.getElementById( \"collapsibleTable\" + tableIndex );

    if ( !Table || !Button ) {
        return false;
    }

    var Rows = Table.rows;

    if ( Button.firstChild.data == collapseCaption ) {
        for ( var i = 1; i < Rows.length; i++ ) {
            Rows[i].style.display = \"none\";
        }
        Button.firstChild.data = expandCaption;
    } else {
        for ( var i = 1; i < Rows.length; i++ ) {
            Rows[i].style.display = Rows[0].style.display;
        }
        Button.firstChild.data = collapseCaption;
    }
}

function createCollapseButtons(){
    var tableIndex = 0;
    var NavigationBoxes = new Object();
    var Tables = document.getElementsByTagName( \"table\" );

    for ( var i = 0; i < Tables.length; i++ ) {
        if ( hasClass( Tables[i], \"collapsible\" ) ) {

            /* only add button and increment count if there is a header row to work with */
            var HeaderRow = Tables[i].getElementsByTagName( \"tr\" )[0];
            if (!HeaderRow) continue;
            var Header = HeaderRow.getElementsByTagName( \"th\" )[0];
            if (!Header) continue;

            NavigationBoxes[ tableIndex ] = Tables[i];
            Tables[i].setAttribute( \"id\", \"collapsibleTable\" + tableIndex );

            var Button     = document.createElement( \"span\" );
            var ButtonLink = document.createElement( \"a\" );
            var ButtonText = document.createTextNode( collapseCaption );

            Button.className = \"collapseButton\";  //Styles are declared in Common.css

            ButtonLink.style.color = Header.style.color;
            ButtonLink.setAttribute( \"id\", \"collapseButton\" + tableIndex );
            ButtonLink.setAttribute( \"href\", \"#\" );
            addHandler( ButtonLink,  \"click\", new Function( \"evt\", \"collapseTable(\" + tableIndex + \" ); return killEvt( evt );\") );
            ButtonLink.appendChild( ButtonText );

            Button.appendChild( document.createTextNode( \"[\" ) );
            Button.appendChild( ButtonLink );
            Button.appendChild( document.createTextNode( \"]\" ) );

            Header.insertBefore( Button, Header.firstChild );
            tableIndex++;
        }
    }

    for ( var i = 0;  i < tableIndex; i++ ) {
        if ( hasClass( NavigationBoxes[i], \"collapsed\" ) || ( tableIndex >= autoCollapse && hasClass( NavigationBoxes[i], \"autocollapse\" ) ) ) {
            collapseTable( i );
        } 
        else if ( hasClass( NavigationBoxes[i], \"innercollapse\" ) ) {
            var element = NavigationBoxes[i];
            while (element = element.parentNode) {
                if ( hasClass( element, \"outercollapse\" ) ) {
                    collapseTable ( i );
                    break;
                }
            }
        }
    }
}

\$( createCollapseButtons );


/** Dynamic Navigation Bars (experimental) *************************************
 *
 *  Description: See [[Wikipedia:NavFrame]].
 *  Maintainers: UNMAINTAINED
 */

// set up the words in your language
var NavigationBarHide = '[' + collapseCaption + ']';
var NavigationBarShow = '[' + expandCaption + ']';

// shows and hides content and picture (if available) of navigation bars
// Parameters:
//     indexNavigationBar: the index of navigation bar to be toggled
window.toggleNavigationBar = function(indexNavigationBar){
    var NavToggle = document.getElementById(\"NavToggle\" + indexNavigationBar);
    var NavFrame = document.getElementById(\"NavFrame\" + indexNavigationBar);

    if (!NavFrame || !NavToggle) {
        return false;
    }

    // if shown now
    if (NavToggle.firstChild.data == NavigationBarHide) {
        for (var NavChild = NavFrame.firstChild; NavChild != null; NavChild = NavChild.nextSibling) {
            if (hasClass(NavChild, 'NavContent') || hasClass(NavChild, 'NavPic')) {
                NavChild.style.display = 'none';
            }
        }
    NavToggle.firstChild.data = NavigationBarShow;

    // if hidden now
    } else if (NavToggle.firstChild.data == NavigationBarShow) {
        for (var NavChild = NavFrame.firstChild; NavChild != null; NavChild = NavChild.nextSibling) {
            if (hasClass(NavChild, 'NavContent') || hasClass(NavChild, 'NavPic')) {
                NavChild.style.display = 'block';
            }
        }
        NavToggle.firstChild.data = NavigationBarHide;
    }
}

// adds show/hide-button to navigation bars
function createNavigationBarToggleButton(){
    var indexNavigationBar = 0;
    // iterate over all < div >-elements 
    var divs = document.getElementsByTagName(\"div\");
    for (var i = 0; NavFrame = divs[i]; i++) {
        // if found a navigation bar
        if (hasClass(NavFrame, \"NavFrame\")) {

            indexNavigationBar++;
            var NavToggle = document.createElement(\"a\");
            NavToggle.className = 'NavToggle';
            NavToggle.setAttribute('id', 'NavToggle' + indexNavigationBar);
            NavToggle.setAttribute('href', 'javascript:toggleNavigationBar(' + indexNavigationBar + ');');

            var isCollapsed = hasClass( NavFrame, \"collapsed\" );
            /*
             * Check if any children are already hidden.  This loop is here for backwards compatibility:
             * the old way of making NavFrames start out collapsed was to manually add style=\"display:none\"
             * to all the NavPic/NavContent elements.  Since this was bad for accessibility (no way to make
             * the content visible without JavaScript support), the new recommended way is to add the class
             * \"collapsed\" to the NavFrame itself, just like with collapsible tables.
             */
            for (var NavChild = NavFrame.firstChild; NavChild != null && !isCollapsed; NavChild = NavChild.nextSibling) {
                if ( hasClass( NavChild, 'NavPic' ) || hasClass( NavChild, 'NavContent' ) ) {
                    if ( NavChild.style.display == 'none' ) {
                        isCollapsed = true;
                    }
                }
            }
            if (isCollapsed) {
                for (var NavChild = NavFrame.firstChild; NavChild != null; NavChild = NavChild.nextSibling) {
                    if ( hasClass( NavChild, 'NavPic' ) || hasClass( NavChild, 'NavContent' ) ) {
                        NavChild.style.display = 'none';
                    }
                }
            }
            var NavToggleText = document.createTextNode(isCollapsed ? NavigationBarShow : NavigationBarHide);
            NavToggle.appendChild(NavToggleText);

            // Find the NavHead and attach the toggle link (Must be this complicated because Moz's firstChild handling is borked)
            for(var j=0; j < NavFrame.childNodes.length; j++) {
                if (hasClass(NavFrame.childNodes[j], \"NavHead\")) {
                    NavToggle.style.color = NavFrame.childNodes[j].style.color;
                    NavFrame.childNodes[j].appendChild(NavToggle);
                }
            }
            NavFrame.setAttribute('id', 'NavFrame' + indexNavigationBar);
        }
    }
}

\$( createNavigationBarToggleButton );


/** Main Page layout fixes *********************************************************
 *
 *  Description: Adds an additional link to the complete list of languages available.
 *  Maintainers: [[User:AzaToth]], [[User:R. Koot]], [[User:Alex Smotrov]]
 */

if (wgPageName == 'Main_Page' || wgPageName == 'Talk:Main_Page') {
    \$(function () {
        mw.util.addPortletLink('p-lang', '//meta.wikimedia.org/wiki/List_of_Wikipedias',
            'Complete list', 'interwiki-completelist', 'Complete list of Wikipedias');
    });
}


/** Table sorting fixes ************************************************
  *
  *  Description: Disables code in table sorting routine to set classes on even/odd rows
  *  Maintainers: [[User:Random832]]
  */
ts_alternate_row_colors = false;


/***** uploadwizard_newusers ********
 * Switches in a message for non-autoconfirmed users at [[Wikipedia:Upload]]
 *
 *  Maintainers: [[User:Krimpet]]
 */
function uploadwizard_newusers() {
  if (wgNamespaceNumber == 4 && wgTitle == \"Upload\" && wgAction == \"view\") {
    var oldDiv = document.getElementById(\"autoconfirmedusers\"),
        newDiv = document.getElementById(\"newusers\");
    if (oldDiv && newDiv) {
      if (typeof wgUserGroups == \"object\" && wgUserGroups) {
        for (i = 0; i < wgUserGroups.length; i++) {
          if (wgUserGroups[i] == \"autoconfirmed\") {
            oldDiv.style.display = \"block\";
            newDiv.style.display = \"none\";
            return;
          }
        }
      }
      oldDiv.style.display = \"none\";
      newDiv.style.display = \"block\";
      return;
    }
  }
}
\$(uploadwizard_newusers);


/** IPv6 AAAA connectivity testing 

var __ipv6wwwtest_factor = 100;
var __ipv6wwwtest_done = 0;
if ((wgServer != \"https://secure.wikimedia.org\") && (Math.floor(Math.random()*__ipv6wwwtest_factor)==42)) {
    importScript(\"MediaWiki:Common.js/IPv6.js\");
}
**/

/** Magic editintros ****************************************************
 *
 *  Description: Adds editintros on disambiguation pages and BLP pages.
 *  Maintainers: [[User:RockMFR]]
 */

function addEditIntro( name ) {
  \$( '.editsection, #ca-edit' ).find( 'a' ).each( function( i, el ) {
    el.href = \$(this).attr(\"href\") + '&editintro=' + name;
  });
}

if (wgNamespaceNumber === 0) {
  \$(function(){
    if (document.getElementById('disambigbox')) {
      addEditIntro('Template:Disambig_editintro');
    }
  });

  \$(function(){
    var cats = document.getElementById('mw-normal-catlinks');
    if (!cats) {
      return;
    }
    cats = cats.getElementsByTagName('a');
    for (var i = 0; i < cats.length; i++) {
      if (cats[i].title == 'Category:Living people' || cats[i].title == 'Category:Possibly living people') {
        addEditIntro('Template:BLP_editintro');
        break;
      }
    }
  });
}

/**
 * Description: Stay on the secure server as much as possible
 * Maintainers: [[User:TheDJ]]
 */
if ( mw.config.get('wgServer') == 'https://secure.wikimedia.org' ) {
    /* Old secure server */
    importScript( 'MediaWiki:Common.js/secure.js');
} else if( document.location && document.location.protocol  && document.location.protocol == \"https:\" ) {
  /* New secure servers */
  importScript('MediaWiki:Common.js/secure new.js');
}

/**
  * Description: Fix the toggle for Mobile view
  * https://bugzilla.wikimedia.org/show_bug.cgi?id=38009
  * Maintainer: [[User:TheDJ]]
  */
mw.loader.using( 'jquery.cookie', function() {
    \$('a[href\$=\"toggle_view_mobile\"]').click(function(){
        document.cookie = 'stopMobileRedirect=false; domain=.wikipedia.org;'
                    + 'path=/; expires=Thu, 01-Jan-1970 00:00:00 GMT;'
    });
});

/* End of mw.loader.using callback */
} );
/* DO NOT ADD CODE BELOW THIS LINE */",

# Metadata
'notacceptable' => "formatê ma'lumati no peşkeşwanê wikiyi nêweniyeno.",

# Attribution
'anonymous' => '{{PLURAL:$1|karberê|karberê}} anonimi yê keyepelê {{SITENAME}}i',
'siteuser' => 'karberê {{SITENAME}}i $1',
'anonuser' => 'karberê anonim o {{SITENAME}}i $1',
'lastmodifiedatby' => 'Ena pele tewr peyên roca $2, $1 by $3. de biya rocaniye',
'othercontribs' => 'xebatê $1 ıney geriyayo diqqeti/geriyayo nezer.',
'others' => 'bini',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|karberê ey|karberanê ey}} $1',
'anonusers' => '{{SITENAME}} {{PLURAL:$2|karberê eyê|karberanê eyê}} anonimi $1',
'creditspage' => 'şınasnameyê peli',
'nocredits' => 'qey no peli hema/hona yew şınasnameyi mewcud niyo',

# Spam protection
'spamprotectiontitle' => 'filtreya spami yo pawıtışê verba-vındertışi',
'spamprotectiontext' => 'pel o ke şıma waşt qeyd bıkeri hetê filtreya spami ra blok bı. ihtimalo gırdek o teber-gıreyê listeya sabıqayi ra yo.',
'spamprotectionmatch' => 'nuşte yo ke rıcnayoxê spami herikneno: $1',
'spambot_username' => 'wikimedya spam-pakkerdışi',
'spam_reverting' => 'agêriyeno revizyon o ke tawayê $1 ıney piya çiniyo',
'spam_blanking' => 'Revizyonê gredê $1 vineyay, wa weng kero',
'spam_deleting' => 'Revizyonê gredê $1 vineyay, wa besterneyê',

# Info page
'pageinfo-title' => 'Heq tê "$1"\'i',
'pageinfo-not-current' => 'Qısur de mevêne, rewizyonanê verênan rê nê melumatan dayış mumkın niyo',
'pageinfo-header-basic' => 'Seron zanayış',
'pageinfo-header-edits' => 'Vurnayışê verêni',
'pageinfo-header-restrictions' => 'Sıtarkerdışê pele',
'pageinfo-header-properties' => 'Xısusiyetê pele',
'pageinfo-display-title' => 'Sernuştey bımocne',
'pageinfo-default-sort' => 'Hesıbyaye mırfeyo kılm',
'pageinfo-length' => 'Derdeya pela (bayti heta)',
'pageinfo-article-id' => 'Kamiya pele',
'pageinfo-robot-policy' => 'Weziyetê motor de cıgeyrayışi',
'pageinfo-robot-index' => 'İIndeksbiyayen',
'pageinfo-robot-noindex' => 'İndeksnêbiyayen',
'pageinfo-views' => 'Amarina mocnayışan',
'pageinfo-watchers' => 'Amariya pela serykeran',
'pageinfo-redirects-name' => 'Hetenayışê na pela',
'pageinfo-redirects-value' => '$1',
'pageinfo-subpages-name' => 'Bınpelê na pela',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|hetenayış|hetenayışi}}; $3 {{PLURAL:$3|raykerdışt|raykerdışi}})',
'pageinfo-firstuser' => 'Pela vıraşter',
'pageinfo-firsttime' => 'Demê pela vıraştışi',
'pageinfo-lastuser' => 'Vurnayoğo peyên',
'pageinfo-lasttime' => 'Deme u vurnayışo peyên',
'pageinfo-edits' => 'Amarina vurnayışan pêro',
'pageinfo-authors' => 'Amarina nuştekaran pêro',
'pageinfo-recent-edits' => 'Amariya vurnayışan ($1 ra nata)',
'pageinfo-recent-authors' => 'Amarina nuştekaran pêro',
'pageinfo-magic-words' => '{{PLURAL:$1|Çekuya|Çekuyê}} ($1) sihırini',
'pageinfo-hidden-categories' => '{{PLURAL:$1|Kategoriye|Kategoriyan}} ($1) bınımne',
'pageinfo-templates' => '{{PLURAL:$1|Şablon|Şabloni}} ($1) açarneyayê',

# Skin names
'skinname-standard' => 'Klasik',
'skinname-nostalgia' => 'Nostalciya',
'skinname-cologneblue' => 'Cologne Blue',
'skinname-monobook' => 'MonoBook',
'skinname-myskin' => 'MySkin',
'skinname-chick' => 'Şıq',
'skinname-simple' => 'Rehat',
'skinname-modern' => 'Modern',
'skinname-vector' => 'Vektor',

# Patrolling
'markaspatrolleddiff' => 'Nişan bıke ke dewriya biyo',
'markaspatrolledtext' => 'Ena pele nişan bike ke devriye biyo',
'markedaspatrolled' => 'Nişan biyo ke verni de devriye biyo',
'markedaspatrolledtext' => 'Versiyone weçinaye [[:$1]] nişan biyo ke devriye biyo',
'rcpatroldisabled' => 'Devriyeyê vurnayışê peyêni nihebitiyeno',
'rcpatroldisabledtext' => 'Devriyeyê vurnayışê peyêni inke kefilnaye biyo u nihebitiyeno',
'markedaspatrollederror' => 'Nişan nibeno ke devriye biyo',
'markedaspatrollederrortext' => 'Ti gani revizyon işaret bike ke Nişanê devriye biyo',
'markedaspatrollederror-noautopatrol' => 'Ti nieşkeno ke vurnayişê xo nişan bike ke devriye biyê.',

# Patrol log
'patrol-log-page' => 'Logê devriye',
'patrol-log-header' => 'Ena listeyê logi revizyonê devriyeyi mocneno.',
'log-show-hide-patrol' => '$1 logê devriye',

# Image deletion
'deletedrevision' => 'Veriyono kihan $1 wederna',
'filedeleteerror-short' => 'Wedarnayişê dosya de ğelati esto: $1',
'filedeleteerror-long' => 'Eka dosya wedarnayişi de ğeleti biyê:

$1',
'filedelete-missing' => 'Ena dosya "$1" nieşkeno biyo wedariye, çunki ena dosya çini yo.',
'filedelete-old-unregistered' => 'Ena dosya revizyoni yê weçinayi "$1" database ma de çini yo.',
'filedelete-current-unregistered' => 'Ena dosyayê weçinayi "$1" database ma de çini yo.',
'filedelete-archive-read-only' => 'Ena direktorê arşivi "$1" webserver de nieşkeno binusi.',

# Browsing diffs
'previousdiff' => '← Vurnayışê kıhanyer',
'nextdiff' => 'Vurnayışo peyên →',

# Media information
'mediawarning' => "'''Teme''': Na dosya de belkia kodê xırabıni estê.
Gurênayışê nae de, beno ke sistemê şıma zerar bıvêno.",
'imagemaxsize' => "Limitê ebat ê resimi:<br />''(qe pela deskripsiyonê dosyayan)''",
'thumbsize' => 'Ebadê Thumbnaili',
'widthheight' => '$1 - $2',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|pele|peli}}',
'file-info' => 'ebatê dosyayi: $1, MIME tip: $2',
'file-info-size' => '$1 × $2 pixelan, ebatê dosya: $3, MIME type: $4',
'file-info-size-pages' => '$1 × $2 pikse, dergeya dosyay: $3, MIME tipiya cı: $4, $5 {{PLURAL:$5|pela|pela}}',
'file-nohires' => 'Deha berz agozney cı çıniyo',
'svg-long-desc' => 'Dosyay SVG, zek vanê $1 × $2 piksela, ebatê dosya: $3',
'svg-long-desc-animated' => 'SVG dosya, nominalin $1 × $2 piksela, ebatê dosya: $3',
'show-big-image' => 'Tam agoznayen',
'show-big-image-preview' => "Verqayd dergiya: $1'i.",
'show-big-image-other' => 'Zewmi{{PLURAL:$2|Vılêşnayış|Vılêşnayışê}}: $1.',
'show-big-image-size' => '$1 × $2 piksel',
'file-info-gif-looped' => 'viyariye biyo',
'file-info-gif-frames' => '$1 {{PLURAL:$1|çerçeve|çerçeveyi}}',
'file-info-png-looped' => 'atlama biyo',
'file-info-png-repeat' => '$1 {{PLURAL:$1|hew|hew}} kay biyê',
'file-info-png-frames' => '$1 {{PLURAL:$1|çerçeve|çerçeveyi}}',
'file-no-thumb-animation' => "'''Not: Dılet tekniko limit, gırd agozneya resm de qıckek de animasyoni miyan dı nêbo.'''",
'file-no-thumb-animation-gif' => "'''Not: Dılet tekniko limit, gırd agozneya resm de qıckek de  GIF imaci de animasyon do nêbo.'''",

# Special:NewFiles
'newimages' => 'Galeriya dosyayan dê newan',
'imagelisttext' => "Cêr de yew listeyê '''$1''' esto {{PLURAL:$1|dosya|dosyayi}} veçiniya $2.",
'newimages-summary' => 'Ena pela xasi dosyayi ke peni de bar biyayeyi mocnane.',
'newimages-legend' => 'Avrêc',
'newimages-label' => 'Nameyê dosya ( ya zi parçe ey)',
'showhidebots' => '(bota $1)',
'noimages' => 'Çik çini yo.',
'ilsubmit' => 'Cı geyre',
'bydate' => 'goreyê zemani',
'sp-newimages-showfrom' => 'Dosyayê newi ke $2, $1 ra dest pe keni bimocne',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims' => '$1, $2 × $3',
'seconds-abbrev' => '$1s',
'minutes-abbrev' => '$1m',
'hours-abbrev' => '$1h',
'days-abbrev' => '$1d',
'seconds' => 'verdê {{PLURAL:$1|$1 saniya|$1 saniya}}',
'minutes' => 'verdê {{PLURAL:$1|$1 daka|$1 daka}}',
'hours' => 'Verdê {{PLURAL:$1|$1 seata|$1 seata}}',
'days' => 'Verdê {{PLURAL:$1|$1 rocan|$1 rocan}}',
'ago' => 'Verdê $1',

# Bad image list
'bad_image_list' => 'Şeklo umumi wınayo:

Tenya çiyo ke beno lista (rezê ke be * dest kenê cı) çıman ver de vêniyeno.
Yew rêze de gırêyo sıfteyın gani gırêyo de dosya xırabıne bo.
Na rêze de her gırêyo bin zey istisna vêniyeno, yanê pelê ke dosya beno ke sero rêzbiyaye asena.',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => 'hans',
'variantname-zh-hant' => 'hant',
'variantname-zh-cn' => 'cn',
'variantname-zh-tw' => 'tw',
'variantname-zh-hk' => 'hk',
'variantname-zh-mo' => 'mo',
'variantname-zh-sg' => 'sg',
'variantname-zh-my' => 'my',
'variantname-zh' => 'zh',

# Variants for Gan language
'variantname-gan-hans' => 'hans',
'variantname-gan-hant' => 'hant',
'variantname-gan' => 'gan',

# Variants for Serbian language
'variantname-sr-ec' => 'sr-ec',
'variantname-sr-el' => 'sr-el',
'variantname-sr' => 'sr',

# Variants for Kazakh language
'variantname-kk-kz' => 'kk-kz',
'variantname-kk-tr' => 'kk-tr',
'variantname-kk-cn' => 'kk-cn',
'variantname-kk-cyrl' => 'kk-cyrl',
'variantname-kk-latn' => 'kk-latn',
'variantname-kk-arab' => 'kk-arab',
'variantname-kk' => 'kk',

# Variants for Kurdish language
'variantname-ku-arab' => 'ku-Arab',
'variantname-ku-latn' => 'ku-Latn',
'variantname-ku' => 'ku',

# Variants for Tajiki language
'variantname-tg-cyrl' => 'tg-Cyrl',
'variantname-tg-latn' => 'tg-Latn',
'variantname-tg' => 'tg',

# Variants for Inuktitut language
'variantname-ike-cans' => 'ike-Cans',
'variantname-ike-latn' => 'ike-Latn',
'variantname-iu' => 'iu',

# Variants for Tachelhit language
'variantname-shi-tfng' => 'shi-Tfng',
'variantname-shi-latn' => 'shi-Latn',
'variantname-shi' => 'shi',

# Metadata
'metadata' => 'Melumato serên',
'metadata-help' => 'Ena dosya dı zafyer informasyoni esto. Belki ena dosya yew kamareyo dijital ya zi skaner ra vıraziyo.
Eg ena dosya, kondisyonê orcinali ra bıvuriya, belki detayanê hemi nıeseno.',
'metadata-expand' => 'Detayan bımocne',
'metadata-collapse' => 'melumati bınımne',
'metadata-fields' => 'Resımê meydanê metadataê ke na pele de benê lista, pela resımmocnaene de ke tabloê metadata gına waro, gureniyenê.
Ê bini zey sayekerdoğan nımiyenê.
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
'metadata-langitem' => "'''$2:''' $1",
'metadata-langitem-default' => '$1',

# EXIF tags
'exif-imagewidth' => 'Herayey',
'exif-imagelength' => 'Dergi',
'exif-bitspersample' => 'yew parçe de biti',
'exif-compression' => 'Planê kompresyoni',
'exif-photometricinterpretation' => 'Compozisyonê pixeli',
'exif-orientation' => 'Oriyentasyon',
'exif-samplesperpixel' => 'teneyê parçeyi',
'exif-planarconfiguration' => 'Rezeyê datayi',
'exif-ycbcrsubsampling' => 'Subsampleyi ebatê Y heta C',
'exif-ycbcrpositioning' => 'pozisyonê Y u C',
'exif-xresolution' => 'Rezulasyanê veriniye',
'exif-yresolution' => 'Rezulasyanê derganiye',
'exif-stripoffsets' => 'cayê data yê resim',
'exif-rowsperstrip' => 'Yew reze de teneyê dizeyi',
'exif-stripbytecounts' => 'Yew compresyon de dizeyi',
'exif-jpeginterchangeformat' => 'JPEG SOI rê ayar bike',
'exif-jpeginterchangeformatlength' => 'Bitê data yê JPEG',
'exif-whitepoint' => 'noktayê sipe ye kromaticiti',
'exif-primarychromaticities' => 'Kromaticitiyê eveli',
'exif-ycbcrcoefficients' => 'Cayê rengi yê transformasyon metriksê koefişinti',
'exif-referenceblackwhite' => 'Çiftyê siya u sipe değerê referansi',
'exif-datetime' => 'Zeman u tarixê vurnayişê dosyayi',
'exif-imagedescription' => 'Serê resimi',
'exif-make' => 'Viraştoğê kamera',
'exif-model' => 'Modelê kamerayi',
'exif-software' => 'Software ke hebitiyeno',
'exif-artist' => 'Nuştoğ',
'exif-copyright' => 'Wahirê copyrighti',
'exif-exifversion' => 'Versiyonê Exif',
'exif-flashpixversion' => 'Versiyonê Flashpix destek bike',
'exif-colorspace' => 'Cayê rengi',
'exif-componentsconfiguration' => 'manayê qisimê hemi',
'exif-compressedbitsperpixel' => 'Modê komprasyonê resimi',
'exif-pixelydimension' => 'Herayeya resimi',
'exif-pixelxdimension' => 'Berzeya resimi',
'exif-usercomment' => 'Hulasayê karberi',
'exif-relatedsoundfile' => 'Derhekê dosya yê vengi',
'exif-datetimeoriginal' => 'Zeman u tarixê data varaziyayişi',
'exif-datetimedigitized' => 'Zeman u tarixê dicital kerdişi',
'exif-subsectime' => 'ZemanTarix saniyeyibini',
'exif-subsectimeoriginal' => 'ZemanTarixOricinal saniyeyibini',
'exif-subsectimedigitized' => 'ZemanTarixDicital saniyeyibini',
'exif-exposuretime' => 'Zemanê orta de vinderdişi',
'exif-exposuretime-format' => '$1 san ($2)',
'exif-fnumber' => 'F Amar',
'exif-fnumber-format' => 'f/$1',
'exif-exposureprogram' => 'Programê Orta de Vinderdişi',
'exif-spectralsensitivity' => 'Hesasiyetê spektrali',
'exif-isospeedratings' => 'ISO değerê piti',
'exif-shutterspeedvalue' => "Pêtiya Deklanşor dê APEX'i",
'exif-aperturevalue' => "Akerdina APEX'i",
'exif-brightnessvalue' => "Berqeya APEX'i",
'exif-exposurebiasvalue' => 'Orta de viderdişi',
'exif-maxaperturevalue' => 'Tewr zafeyê wareyê apertur',
'exif-subjectdistance' => 'Duriyê ey',
'exif-meteringmode' => 'Modê pemawitişi',
'exif-lightsource' => 'Çimeyê roşni',
'exif-flash' => 'Flaş',
'exif-focallength' => 'Deganiyê fokus ê lensi',
'exif-focallength-format' => '$1 mm',
'exif-subjectarea' => 'Wareyê ey',
'exif-flashenergy' => 'Kuvetê flaşi',
'exif-focalplanexresolution' => 'Focal plane X resolution',
'exif-focalplaneyresolution' => 'Focal plane Y resolution',
'exif-focalplaneresolutionunit' => 'Focal plane resolution unit',
'exif-subjectlocation' => 'cayê kerdoxi',
'exif-exposureindex' => 'rêzê (indexê) pozi',
'exif-sensingmethod' => 'metodê hiskerdışi',
'exif-filesource' => 'çimeyê dosyayi',
'exif-scenetype' => 'tipa sehneyi',
'exif-customrendered' => 'karê resmê xususiyi',
'exif-exposuremode' => 'poz kerdışi',
'exif-whitebalance' => 'Dengeyo Sipe',
'exif-digitalzoomratio' => 'dijital zoom',
'exif-focallengthin35mmfilm' => "filmê 35 mm'yın de dûriyê merkeziyi",
'exif-scenecapturetype' => 'tipa sehne gırewtışi',
'exif-gaincontrol' => 'kontrolê sehneyi',
'exif-contrast' => 'Kontrast',
'exif-saturation' => 'Saturasyon',
'exif-sharpness' => 'Tucî',
'exif-devicesettingdescription' => "daşınasnayişê 'eyarê cihazi",
'exif-subjectdistancerange' => 'menzilê mesafeya kerdoxi',
'exif-imageuniqueid' => 'şınasnameyê resmê xususiyi',
'exif-gpsversionid' => 'revizyonê GPSyi',
'exif-gpslatituderef' => 'paralelê zıme û veroci',
'exif-gpslatitude' => 'Heralem',
'exif-gpslongituderef' => 'meridyenê rocvetış û rocawavi',
'exif-gpslongitude' => 'Lemen',
'exif-gpsaltituderef' => 'çımeyê berziyi',
'exif-gpsaltitude' => 'berzî',
'exif-gpstimestamp' => "Wextê GPSyi (se'eta atomiki)",
'exif-gpssatellites' => 'Qandê peymıtışi antenê ke vıstê kar',
'exif-gpsstatus' => 'cayê gırewtoxi',
'exif-gpsmeasuremode' => 'moda peymawıtışi',
'exif-gpsdop' => 'karê peymawıtışi',
'exif-gpsspeedref' => 'Uniteyê pitî',
'exif-gpsspeed' => 'pêtîyê receiveri',
'exif-gpstrackref' => 'Referansê ke ser hetiyê hereketi',
'exif-gpstrack' => 'hetiyê hereketi',
'exif-gpsimgdirectionref' => 'Referansê ke ser hetiyê resimi',
'exif-gpsimgdirection' => 'Hetiyê resimi',
'exif-gpsmapdatum' => 'Geodetic survey data used',
'exif-gpsdestlatituderef' => 'Reference for latitude of destination',
'exif-gpsdestlatitude' => 'Latitude destination',
'exif-gpsdestlongituderef' => 'Reference for longitude of destination',
'exif-gpsdestlongitude' => 'Longitude of destination',
'exif-gpsdestbearingref' => 'Reference for bearing of destination',
'exif-gpsdestbearing' => 'Bearing of destination',
'exif-gpsdestdistanceref' => 'Referanse ke ser duriyeyê cayê şiyayişi',
'exif-gpsdestdistance' => 'Duriyeyê cayê şiyayişi',
'exif-gpsprocessingmethod' => 'Name of GPS processing method',
'exif-gpsareainformation' => 'Nameyê wareyê GPSi',
'exif-gpsdatestamp' => 'Tarixê GPSi',
'exif-gpsdifferential' => 'GPS differential correction',
'exif-coordinate-format' => '$1° $2′ $3″ $4',
'exif-jpegfilecomment' => "Vatışê dosyada JPEG'i",
'exif-keywords' => 'Qesa kelimey',
'exif-worldregioncreated' => 'Resim dınya dı qanci mıntıqara gêriyayo',
'exif-countrycreated' => 'Resim qanci dewlet ra gêriyayo',
'exif-countrycodecreated' => 'Cayo ke resim ancıyayo kodê dewlet da cı',
'exif-provinceorstatecreated' => 'Cayê resim antışi dewlet yana wılayet',
'exif-citycreated' => 'Suka ke resim gêriyayao',
'exif-sublocationcreated' => 'Bın lokasyonê resimê suker da cı grot',
'exif-worldregiondest' => 'Wareyo ke mocneyêno',
'exif-countrydest' => 'Dewleta ke mocneyêna',
'exif-countrycodedest' => 'Kodê dewleto ke mocneyoêno',
'exif-provinceorstatedest' => 'Eyalet yana wılayeto ke mocneyêno',
'exif-citydest' => 'Sûka ke mocneyêna',
'exif-sublocationdest' => 'Mıntıqeya sûker mocnayış',
'exif-objectname' => 'Sernuşteyo qıckek',
'exif-specialinstructions' => 'Talimatê xısusi',
'exif-headline' => 'Sername',
'exif-credit' => 'Kredi/Destegdaren',
'exif-source' => 'Çıme',
'exif-editstatus' => 'Resmi vurnayışê weziyeti',
'exif-urgency' => 'Aciliyet',
'exif-fixtureidentifier' => 'Namey fiksturi',
'exif-locationdest' => 'Tarifê cay',
'exif-locationdestcode' => 'Lokasyon kodi vaciya',
'exif-objectcycle' => 'Qandê medyay deme u roce cı',
'exif-contact' => 'Zanışiya irtibati',
'exif-writer' => 'Nuştekar',
'exif-languagecode' => 'Zıwan',
'exif-iimversion' => 'Verqaydê IIM',
'exif-iimcategory' => 'Kategoriye',
'exif-iimsupplementalcategory' => 'Oleyê Kategoriyan',
'exif-datetimeexpires' => 'No peyra mekarênê',
'exif-datetimereleased' => 'Bıroşe',
'exif-originaltransmissionref' => 'Oricinal pusula da kodê açarnayışi',
'exif-identifier' => 'Şınasnayer',
'exif-lens' => 'Lensê karkerdışi',
'exif-serialnumber' => 'Seri nımreyê kamera',
'exif-cameraownername' => 'Wayırê kamera',
'exif-label' => 'Etiket',
'exif-datetimemetadata' => 'Malumatê metamalumati peyd timarya',
'exif-nickname' => 'Bêresmi namey cı',
'exif-rating' => 'Rey dayış (5i sera)',
'exif-rightscertificate' => 'Sertifikayê idariya heqan',
'exif-copyrighted' => 'Weziyetê telifi',
'exif-copyrightowner' => 'Wayırê Telifi',
'exif-usageterms' => 'Şertê karkerdışi',
'exif-webstatement' => 'Heqê telifiya miyandene',
'exif-originaldocumentid' => 'Xasiya ID ya dokuman de orcinali',
'exif-licenseurl' => 'Qandê Lisans de heqê telifiye URL',
'exif-morepermissionsurl' => 'Alternatif malumatê lisansi',
'exif-attributionurl' => 'No nuşte çı wext karyayo, şıma ra reca gre dekerê de',
'exif-preferredattributionname' => 'No nuşte çı wext karyayo, Şıma ra reca morkerê',
'exif-pngfilecomment' => "Vatışê dosyada PNG'i",
'exif-disclaimer' => 'Redê mesuliyeti',
'exif-contentwarning' => 'İkazê zerreki',
'exif-giffilecomment' => "vatena dosya da GIF'i",
'exif-intellectualgenre' => 'Babeta çêki',
'exif-subjectnewscode' => 'Kodê muhtewa',
'exif-scenecode' => 'IPTC kodê sahni',
'exif-event' => 'Weqaya ke nameycıyo ravreno',
'exif-organisationinimage' => 'Organizasyono ke ravêreno',
'exif-personinimage' => 'Merdumo ke nameycıyo ravêreno',
'exif-originalimageheight' => 'Veror de resim nêkırpnayışi dergeya cı',
'exif-originalimagewidth' => 'Veror de resim nêkırpnayışi herayeya cı',

# Make & model, can be wikified in order to link to the camera and model name
'exif-contact-value' => '$1

$2
<div class="adr">
$3

$4, $5, $6 $7
</div>
$8',
'exif-subjectnewscode-value' => '$2 ($1)',

# EXIF attributes
'exif-compression-1' => 'Nêdegusneyayo',
'exif-compression-2' => 'CCITT Grube 3 1-ebadın kodkerdışê dergiya gurenayışê Huffmanio modifiyekerde',
'exif-compression-3' => 'CCITT Group 3 fax kodkerdış',
'exif-compression-4' => 'CCITT Group 4 fax kodkerdış',
'exif-compression-5' => 'LZW',
'exif-compression-6' => 'JPEG (verên)',
'exif-compression-7' => 'JPEG',
'exif-compression-8' => 'Deflate (Adobe)',
'exif-compression-32773' => 'PackBits (Macintosh RLE)',
'exif-compression-32946' => 'Deflate (PKZIP)',
'exif-compression-34712' => 'JPEG2000',

'exif-copyrighted-true' => 'Heqê telifiye',
'exif-copyrighted-false' => 'Malê Şari',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-unknowndate' => 'Tarix nizanyano',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'kıştki-ser çarnayiş',
'exif-orientation-3' => '180° çariyayo',
'exif-orientation-4' => 'dergî-ser çarnayiş',
'exif-orientation-5' => '90° çariyayo (çepser) u dergî-ser çarnayiş',
'exif-orientation-6' => '90° CCW çariyayo (hetê saetê ra)',
'exif-orientation-7' => "90° çariyayo (hetê se'eti ra) u dergî-ser çarnayiş",
'exif-orientation-8' => '90° CW çariyayo (çepser)',

'exif-planarconfiguration-1' => 'chunky format',
'exif-planarconfiguration-2' => 'planar format',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1' => 'sRGB',
'exif-colorspace-65535' => 'Kalibrasyon nêvıraziyayo',

'exif-componentsconfiguration-0' => 'çini yo',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'nêdiya daşınasnayişi',
'exif-exposureprogram-1' => 'Pê/bı dest',
'exif-exposureprogram-2' => 'Programo normal',
'exif-exposureprogram-3' => "'ewwıliyê kıfşi",
'exif-exposureprogram-4' => "'ewwıliyê denklanşori",
'exif-exposureprogram-5' => 'proğramo vıraştox',
'exif-exposureprogram-6' => 'proğramê hareketi (qey antışê sehneyê hereketıni)',
'exif-exposureprogram-7' => 'moda portreyi (zemin keno gerzawın, portre zi keno net u hema anceno)',
'exif-exposureprogram-8' => 'moda peyzaji (têna çi yo ke dûri re çım verdeno)',

'exif-subjectdistance-value' => '$1 metreyi',

'exif-meteringmode-0' => 'Nêzanayen',
'exif-meteringmode-1' => 'orta',
'exif-meteringmode-2' => 'gıraniyê merkeziyi ser',
'exif-meteringmode-3' => 'noqtayın',
'exif-meteringmode-4' => 'zaf noqtayın',
'exif-meteringmode-5' => 'Desenın/fesalın',
'exif-meteringmode-6' => 'qısmî',
'exif-meteringmode-255' => 'Bin',

'exif-lightsource-0' => 'Nêzanayen',
'exif-lightsource-1' => 'Roşnê Tici',
'exif-lightsource-2' => 'Florasant',
'exif-lightsource-3' => 'roşnê bêbızate',
'exif-lightsource-4' => 'Flaş',
'exif-lightsource-9' => 'saye/hewayo weşî',
'exif-lightsource-10' => 'hewra/hora',
'exif-lightsource-11' => 'Sersiyın',
'exif-lightsource-12' => 'Florasanê roşnê tici (D 5700 – 7100K)',
'exif-lightsource-13' => 'Florasanê sipe ye roci (N 4600 – 5400K)',
'exif-lightsource-14' => 'Florasanê sipe ye hewli (W 3900 – 4500K)',
'exif-lightsource-15' => 'Florasanê sipe (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Roşna standarde A',
'exif-lightsource-18' => 'Roşna standarde B',
'exif-lightsource-19' => 'Roşna standarde C',
'exif-lightsource-20' => 'D55',
'exif-lightsource-21' => 'D65',
'exif-lightsource-22' => 'D75',
'exif-lightsource-23' => 'D50',
'exif-lightsource-24' => 'ISO volframê studyoyi',
'exif-lightsource-255' => 'Çimeyê roşni yê bini',

# Flash modes
'exif-flash-fired-0' => 'flash nêteqa/ta nêkewt',
'exif-flash-fired-1' => 'flash teqa/ta kewt',
'exif-flash-return-0' => 'moda roştê gêrayoxi qefelnaye yo',
'exif-flash-return-2' => 'roşto gêrayox çino',
'exif-flash-return-3' => 'roşto gêrayox tesbit bı/ca bı',
'exif-flash-mode-1' => 'flaşo mecburi teqa',
'exif-flash-mode-2' => 'flasho mecburi qefelnaye yo',
'exif-flash-mode-3' => 'moda otomatike',
'exif-flash-function-1' => 'Fonksiyonê flaşi çini yo',
'exif-flash-redeye-1' => 'modê çim-sur tay kerdişi',

'exif-focalplaneresolutionunit-2' => 'inchî',

'exif-sensingmethod-1' => 'daşinasnayişê ey çino',
'exif-sensingmethod-2' => 'Sensorê wareyê rengê yew-çipi',
'exif-sensingmethod-3' => 'Sensorê wareyê rengê di-çipi',
'exif-sensingmethod-4' => 'Sensorê wareyê rengê hirê-çipi',
'exif-sensingmethod-5' => 'sensora têrêz a ke rengın his kena',
'exif-sensingmethod-7' => 'Sensorê hirê-çizgi',
'exif-sensingmethod-8' => 'sensora aritmetik a ke rengın his kena',

'exif-filesource-3' => 'Dicital makinay kamera',

'exif-scenetype-1' => 'ca de fotoğraf ker',

'exif-customrendered-0' => 'Prosesê normali',
'exif-customrendered-1' => 'proseso xususi',

'exif-exposuremode-0' => 'pozkerdışê otomatiki',
'exif-exposuremode-1' => 'pozkerdışê manueli',
'exif-exposuremode-2' => 'Auto bracket',

'exif-whitebalance-0' => 'balansê sıpi yo otomatiki',
'exif-whitebalance-1' => 'balansê sıpi yo manueli',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Manzara',
'exif-scenecapturetype-2' => 'Portre',
'exif-scenecapturetype-3' => 'şew-antış',

'exif-gaincontrol-0' => 'Çıniyo',
'exif-gaincontrol-1' => 'Low gain up',
'exif-gaincontrol-2' => 'High gain up',
'exif-gaincontrol-3' => 'Low gain down',
'exif-gaincontrol-4' => 'High gain down',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Nerm',
'exif-contrast-2' => 'Huşk',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'mırdiyo kêm',
'exif-saturation-2' => 'mırdiyo ziyed',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Nerm',
'exif-sharpness-2' => 'Huşk',

'exif-subjectdistancerange-0' => 'Nêzanayen',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Vinayişê nezdiyi',
'exif-subjectdistancerange-3' => 'Vinayişê duri',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Veriniya zımeyi',
'exif-gpslatitude-s' => 'Veriniya veroci',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'derganiya rocvetış',
'exif-gpslongitude-w' => 'Derganiya rocawan',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => 'Sewiye de roy ra $1 {{PLURAL:$1|metre|metre}} cordeyo',
'exif-gpsaltitude-below-sealevel' => 'Sewiye de roy ra $1 {{PLURAL:$1|metre|metre}} cêrdeyo',

'exif-gpsstatus-a' => 'peymawıtış dewam keno',
'exif-gpsstatus-v' => 'şuxuliyayişê peymawıtışi',

'exif-gpsmeasuremode-2' => '2-dimensional measurement',
'exif-gpsmeasuremode-3' => '3-dimensional measurement',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'km/s',
'exif-gpsspeed-m' => 'Mil/saat',
'exif-gpsspeed-n' => 'milê deryayi',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilometre',
'exif-gpsdestdistance-m' => 'Mil',
'exif-gpsdestdistance-n' => 'Milê roy',

'exif-gpsdop-excellent' => '($1) Weşo',
'exif-gpsdop-good' => '($1) rındo',
'exif-gpsdop-moderate' => '($1) ne rınd nezi aro',
'exif-gpsdop-fair' => '($1) idare keno',
'exif-gpsdop-poor' => '($1) neqim nê keno',

'exif-objectcycle-a' => 'Teq ê şıfaqi',
'exif-objectcycle-p' => 'Teq ê şani',
'exif-objectcycle-b' => 'Şew u roc',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'hetê raştê ey',
'exif-gpsdirection-m' => 'hetê manyetikê ey',

'exif-ycbcrpositioning-1' => 'Wertekerdış',
'exif-ycbcrpositioning-2' => 'Wayırê-site',

'exif-dc-contributor' => 'İştıraqkeri',
'exif-dc-coverage' => 'Heruna yana wextin grotışa medya',
'exif-dc-date' => 'Tarix(i)',
'exif-dc-publisher' => 'Hesrekar',
'exif-dc-relation' => 'Medyay cı',
'exif-dc-rights' => 'Heqi',
'exif-dc-source' => 'Medyay çımi',
'exif-dc-type' => 'Babeta medyay',

'exif-rating-rejected' => 'Red ke',

'exif-isospeedratings-overflow' => '65535 ra gırdo',

'exif-maxaperturevalue-value' => '$1 APEX (f/$2)',

'exif-iimcategory-ace' => 'Zagon, kultur u keyfiye',
'exif-iimcategory-clj' => 'Arey u huquq',
'exif-iimcategory-dis' => 'Weqey u Qezey',
'exif-iimcategory-fin' => 'Ekonomi u Kar',
'exif-iimcategory-edu' => 'Terbiyet',
'exif-iimcategory-evn' => 'Dorme',
'exif-iimcategory-hth' => 'Weşeyey',
'exif-iimcategory-hum' => 'Elekey merduman',
'exif-iimcategory-lab' => 'Gurweyayin',
'exif-iimcategory-lif' => 'Cıwiyayış u keyf kerdış',
'exif-iimcategory-pol' => 'Siyaset',
'exif-iimcategory-rel' => 'Din u iman kerdış',
'exif-iimcategory-sci' => 'Zanış u teknoloci',
'exif-iimcategory-soi' => 'Sosyal meseley',
'exif-iimcategory-spo' => 'Spor',
'exif-iimcategory-war' => 'Leci, pê şanayış u dışmeney',
'exif-iimcategory-wea' => 'Hewa',

'exif-urgency-normal' => 'Normal ($1)',
'exif-urgency-low' => '($1) Kemiyo',
'exif-urgency-high' => '( $1 ) Vêşiyo',
'exif-urgency-other' => 'Sıftê  şınasiya karberi ($1)',

# External editor support
'edit-externally' => 'Ena dosya bıvurne pe yew programê harici',
'edit-externally-help' => '(Qe informasyonê zafyer ena bevinin [//www.mediawiki.org/wiki/Manual:External_editors setup instructions])',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'pêro',
'namespacesall' => 'pêro',
'monthsall' => 'pêro',
'limitall' => 'pêro',

# E-mail address confirmation
'confirmemail' => 'Adresê e-posta tesdiq ker',
'confirmemail_noemail' => 'Yew emaîlê tu raştîyê çin o ke [[Special:Preferences|tercihê karberî]] ayar bike.',
'confirmemail_text' => 'Qey gurweyayışê e-postayê wikiyi gani veror e-postayê şıma araşt bıbo.
Adresa şıma re qey erşawıtışê e-postayê araştin, butonê cêrıni pıploxnê.
E-posta yo ke erşawiyeno tede gıreyê kodê araşti esto, gıreyi pıploxne akerê u e-postayê xo araşt kerê.',
'confirmemail_pending' => 'Yew codê konfirmasyonî ma ti ra şiravt;
Eka ti newe hesabê xo viraşt, ti gani yew di dekika vindero u email xo kontrol bike, yani reyna yew hesab meviraz.',
'confirmemail_send' => 'Yew kodê konfirmasyonî email mina bişirave',
'confirmemail_sent' => 'Emailê konfirmasyonî şiravt',
'confirmemail_oncreate' => 'Yew codê konfirmasyonî ma ti ra şiravt;
Ena kod semed ci kewtîşî lazim niyo, feqat ti gani sistem rê eno kod bimocne ke ti opsiyonê emailî wîkî a bike.',
'confirmemail_sendfailed' => '{{SITENAME}} nieşkenî ti ra yew emailê konfirmasyonî bişiravî.
Rica keno ke adresê emailî xo kontrol bike.

Email şawitoğ eno reyna ard: $1',
'confirmemail_invalid' => 'Kodê konfirmasyonî raşt niyo.
Wextê kod ê konfirmasyonî viyerto.',
'confirmemail_needlogin' => ' $1 lazimo ke ti adresê emaîl ê xo konfirme bike.',
'confirmemail_success' => 'Email adresê tu konfirme biy.
Ti eşkeno [[Special:UserLogin|ci kewt]].',
'confirmemail_loggedin' => 'Eka email adresê tu konfirme biy.',
'confirmemail_error' => 'Konfirmasyon ni biy, yew ğelet esto.',
'confirmemail_subject' => '{{SITENAME}} konfirmasyonê adres ê emalî',
'confirmemail_body' => 'Brayo delal, mara ke şıma no IP-adresi ra,
keyepelê {{SITENAME}}i de pêno $2 e-postayi hesab kerda.

eke raşta no e-posta eyê şımayo şıma gani araşt bıkerî, qey araşt kerdışi gani karê e-postayê keyepeli {{SITENAME}} aktif bıbo, qey aktif kerdışi gıreyê cêrêni bıtıkne.

$3

eke şıma hesab *nê akerdo*, qey terqnayışê araşt kerdışê adresa e-postayi gıreyê cêrıni pıploxnê:

$5

kodê araşti heta ıney tarixi $4 meqbulo.',
'confirmemail_body_changed' => 'Yew ten, muhtemelen şıma no IP-adresi $1 ra,
keyepelê {{SITENAME}}i de pê no $2 e-postayi hesab kerd a.

Eke raşta no e-posta eyê şıma yo şıma gani tesdiq bıkerî,
qey tesdiq kerdışi gani karê e-postayê keyepeli {{SITENAME}} aktif bıbo, qey aktif kerdışi gıreyê cêrıni bıtıkne:

$3

eke şıma hesab *a nêkerdo*, qey ibtalê tesdiqkerdışê adresa e-postayi gıreyê cêrıni bıtıknê:

$5

kodê tesdiqi heta ıney tarixi $4 meqbul o.',
'confirmemail_body_set' => 'Jew ten, muhtemelen şıma no IP-adresi $1 ra,
keye pelê {{SITENAME}}i de pê no $2 e-postayi hesab kerda.

Eke raşta no e-posta eyê şıma yo şıma gani tesdiq bıkerî,
qey tesdiq kerdışi gani karê e-postayê keyepeli {{SITENAME}} aktif bıbo, qey aktif kerdışi gıreyê cêrıni bıtıkne:

$3

eke şıma hesab *nêakerdo*, qey ibtalê tesdiq kerdışê adresa e-postayi gıreyê cêrêni bıtıknê:

$5

kodê tesdiqi heta ıney tarixi $4 meqbul o.',
'confirmemail_invalidated' => 'Konfermasyonê adres ê emaîlî iptal biy',
'invalidateemail' => 'confirmasyonê e-maili iptal bik',

# Scary transclusion
'scarytranscludedisabled' => '[Transcludê înterwîkîyî nihebityeno]',
'scarytranscludefailed' => '[Qe $1 fetch kerdişî nihebitiyeno]',
'scarytranscludetoolong' => '[Ena URL zaf dergo]',

# Delete conflict
'deletedwhileediting' => "'''Teme''': Ena pele  verniyê ti de eseteriyaya!",
'confirmrecreate' => "Karberê [[User:$1|$1]]î ([[User talk:$1|mesac]]), verniyê vurnayîşê ti ra ena pele wedarno, sebeb: ''$2''
Ma rica keno tesdiq bike ke ti raştî wazeno eno pel bivirazo.",
'confirmrecreate-noreason' => 'karbero [[User:$1|$1]] ([[User talk:$1|mesac]]) , dest pêkerdışiena pela sero vurnayışiya tepya ena pela besternê. Şıma qayıli ke ena pela fına vırazê se ena pela tesdiq kerê.',
'recreate' => 'Reyna viraz',

'unit-pixel' => 'px',

# action=purge
'confirm_purge_button' => 'Temam',
'confirm-purge-top' => 'Cacheyê eno pel biestere?',
'confirm-purge-bottom' => 'Purge kerdişê yew pel cacheyî estereno u revizyonê penîyî mucneno.',

# action=watch/unwatch
'confirm-watch-button' => 'TEMAM',
'confirm-watch-top' => 'Ena pele lista xoya seyrkerdışi ke',
'confirm-unwatch-button' => 'TEMAM',
'confirm-unwatch-top' => 'Ena pele lista xoya seyirkerdışi ra bıvece?',

# Separators for various lists, etc.
'semicolon-separator' => '&#32;',
'comma-separator' => ',&#32;',
'colon-separator' => ':&#32;',
'autocomment-prefix' => '-&#32;',
'pipe-separator' => '&#32;|&#32;',
'word-separator' => '&#32;',
'ellipsis' => '...',
'percent' => '$1%',
'parentheses' => '($1)',
'brackets' => '[$1]',

# Multipage image navigation
'imgmultipageprev' => '← peleyê verin',
'imgmultipagenext' => 'pela badê cû →',
'imgmultigo' => 'Şo!',
'imgmultigoto' => 'Şo pela da $1',

# Table pager
'ascending_abbrev' => 'berz',
'descending_abbrev' => 'nızm',
'table_pager_next' => 'Pela peyêne',
'table_pager_prev' => 'Pela verêne',
'table_pager_first' => 'Pela jûyıne',
'table_pager_last' => 'Pela peyêne',
'table_pager_limit' => 'Jû pele de $1 unsuran bımocne',
'table_pager_limit_label' => 'Her pele ra xacetan',
'table_pager_limit_submit' => 'Şo',
'table_pager_empty' => 'Netice çini yo',

# Auto-summaries
'autosumm-blank' => 'Pele de her çi wederna',
'autosumm-replace' => "Maqale pê '$1' vuriya",
'autoredircomment' => 'Pele [[$1]] rê redirek biyo',
'autosumm-new' => "Pela vıraziyê, '$1' bıvinê",

# Size units
'size-bytes' => '$1 B',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',
'size-terabytes' => '$1 TB',
'size-petabytes' => '$1 PB',
'size-exabytes' => '$1 EB',
'size-zetabytes' => '$1 ZB',
'size-yottabytes' => '$1 YB',

# Bitrate units
'bitrate-bits' => '$1bps',
'bitrate-kilobits' => '$1kbps',
'bitrate-megabits' => '$1Mbps',
'bitrate-gigabits' => '$1Gbps',
'bitrate-terabits' => '$1Tbps',
'bitrate-petabits' => '$1Pbps',
'bitrate-exabits' => '$1Ebps',
'bitrate-zetabits' => '$1Zbps',
'bitrate-yottabits' => '$1Ybps',

# Live preview
'livepreview-loading' => 'Ho bar keni...',
'livepreview-ready' => 'Ho bar keni... Hezir o!',
'livepreview-failed' => 'Verqeyd nibiyo! Verqeydo normal deneme bike.',
'livepreview-error' => 'Nieşken giredayi biy: $1 "$2".
Verqeydo normal deneme bike.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Vurnayîşî ke {{PLURAL:$1|seniye|seniyeyî}} ra newiyerî belki inan nimucneno.',
'lag-warn-high' => 'Eka serverê databaseyî zaf hebitiyeno, ayra vurnayîşî ke {{PLURAL:$1|seniye|seniyeyî}} ra newiyerî belki inan nimucneno.',

# Watchlist editor
'watchlistedit-numitems' => 'Listeyê seyirkerdişi ti de {{PLURAL:$1|1 title|$1 titles}} esta, feqet pelayanê minaqeşeyan dahil niyê.',
'watchlistedit-noitems' => 'Listeyê seyr kerdişê tu de seroğ çin o.',
'watchlistedit-normal-title' => 'Listeyê seyirkerdişi bivurne',
'watchlistedit-normal-legend' => 'Listeyê seyr kerdişê tu de seroğ biwedarna.',
'watchlistedit-normal-explain' => 'Listeyê seyr kerdîşî ti de serogî cor de mucnayiyo.
Eka ti wazeno seroğ biwedarne, kuti ke kistê de, ay işaret bike u "{{int:Watchlistedit-normal-submit}}" klik bike.
Ti hem zi eşkeno [[Special:EditWatchlist/raw|edit the raw list]].',
'watchlistedit-normal-submit' => 'Seroğî biwedarnê',
'watchlistedit-normal-done' => '{{PLURAL:$1|1 seroğ|$1 seroğî}} seyr kerdişê tu ra wedarno.',
'watchlistedit-raw-title' => 'Listeyê seyirkerdişi ye hami bivurne',
'watchlistedit-raw-legend' => 'Listeyê seyirkerdişi ye hami bivurne',
'watchlistedit-raw-explain' => 'Listeyê seyr kerdîşî ti de serogî cor de mucnayiyo u ti eşkeno pê dekerdiş u wedarnayîş liste bivurne.
Eka vurnayîşê ti qediyo, Listeyê Seyr Kerdişî Rocaniye Bike "{{int:Watchlistedit-raw-submit}}" klik bike.
Ti hem zi eşkeno [[Special:EditWatchlist|use the standard editor]].',
'watchlistedit-raw-titles' => 'Seroğî:',
'watchlistedit-raw-submit' => 'Listeyê seyri newen ke',
'watchlistedit-raw-done' => 'Listeyê tuyê seyrkerdişi rocaniye biyo',
'watchlistedit-raw-added' => '{{PLURAL:$1|1 seroğ|$1 seroğî}} de kerd:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1 seroğ|$1 seroği}} besteriyaye:',

# Watchlist editing tools
'watchlisttools-view' => 'vurnayışanê eleqadari bıvin',
'watchlisttools-edit' => 'Lista seyrkerdışi bıvênên u bıvurnên',
'watchlisttools-raw' => 'Listeyê seyr-kerdışi bıvin',

# Iranian month names
'iranian-calendar-m1' => 'Farvardin',
'iranian-calendar-m2' => 'Ordibeheşt',
'iranian-calendar-m3' => 'Xordad',
'iranian-calendar-m4' => 'Tir',
'iranian-calendar-m5' => 'Morded',
'iranian-calendar-m6' => 'Şahrivar',
'iranian-calendar-m7' => 'Mehr',
'iranian-calendar-m8' => 'Aban',
'iranian-calendar-m9' => 'Azar',
'iranian-calendar-m10' => 'Dey',
'iranian-calendar-m11' => 'Behman',
'iranian-calendar-m12' => 'Esfend',

# Hijri month names
'hijri-calendar-m1' => 'Muharram',
'hijri-calendar-m2' => 'Sefer',
'hijri-calendar-m3' => 'Rebiel ewwel',
'hijri-calendar-m4' => 'Rebiel sani',
'hijri-calendar-m5' => 'Cemaziel ewwel',
'hijri-calendar-m6' => 'Cemaziel tani',
'hijri-calendar-m7' => 'Receb',
'hijri-calendar-m8' => 'Şehban',
'hijri-calendar-m9' => 'Remezan',
'hijri-calendar-m10' => 'Şewwal',
'hijri-calendar-m11' => 'Zil Qade',
'hijri-calendar-m12' => 'Zil Hicce',

# Hebrew month names
'hebrew-calendar-m1' => 'Tişrei',
'hebrew-calendar-m2' => 'Çeşvan',
'hebrew-calendar-m3' => 'Kislev',
'hebrew-calendar-m4' => 'Tevet',
'hebrew-calendar-m5' => 'Şevat',
'hebrew-calendar-m6' => 'Adar',
'hebrew-calendar-m6a' => 'Adar I',
'hebrew-calendar-m6b' => 'Adar II',
'hebrew-calendar-m7' => 'Nisan',
'hebrew-calendar-m8' => 'Iyar',
'hebrew-calendar-m9' => 'Sivan',
'hebrew-calendar-m10' => 'Tamuz',
'hebrew-calendar-m11' => 'Av',
'hebrew-calendar-m12' => 'Elul',
'hebrew-calendar-m1-gen' => 'Tişrei',
'hebrew-calendar-m2-gen' => 'Çeşvan',
'hebrew-calendar-m3-gen' => 'Kislev',
'hebrew-calendar-m4-gen' => 'Tevet',
'hebrew-calendar-m5-gen' => 'Şevat',
'hebrew-calendar-m6-gen' => 'Adar',
'hebrew-calendar-m6a-gen' => 'Adar I',
'hebrew-calendar-m6b-gen' => 'Adar II',
'hebrew-calendar-m7-gen' => 'Nisan',
'hebrew-calendar-m8-gen' => 'Iyar',
'hebrew-calendar-m9-gen' => 'Sivan',
'hebrew-calendar-m10-gen' => 'Tamuz',
'hebrew-calendar-m11-gen' => 'Av',
'hebrew-calendar-m12-gen' => 'Elul',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|mesac]])',
'timezone-utc' => '[[UTC]]',

# Core parser functions
'unknown_extension_tag' => 'Etiketê ekstensiyon ê "$1"î nizanyeno',
'duplicate-defaultsort' => '\'\'\'Tembe:\'\'\' Hesıbyaye sırmey ratnayış de "$2" sırmey ratnayış de "$1"i nêhesıbneno.',

# Special:Version
'version' => 'Versiyon',
'version-extensions' => 'Ekstensiyonî ke ronaye',
'version-specialpages' => 'Pelanê xasiyan',
'version-parserhooks' => 'Çengelê Parserî',
'version-variables' => 'Vurnayeyî',
'version-antispam' => 'Spam vındarnayış',
'version-skins' => 'Cıldi',
'version-api' => 'API',
'version-other' => 'Bin',
'version-mediahandlers' => 'Kulbê medyayî',
'version-hooks' => 'Çengelî',
'version-extension-functions' => 'Funksiyonê ekstensiyonî',
'version-parser-extensiontags' => 'Etiketê ekstensiyon ê parserî',
'version-parser-function-hooks' => 'Çengelê ekstensiyon ê parserî',
'version-hook-name' => 'Nameyê çengelî',
'version-hook-subscribedby' => 'Eza biyayoğ',
'version-version' => '(Versiyon $1)',
'version-svn-revision' => '(r$2)',
'version-license' => 'Lisans',
'version-poweredby-credits' => "Ena wiki, dezginda '''[//www.mediawiki.org/ MediaWiki]''' ya piya vıraziyaya, heqê telifi © 2001-$1 $2.",
'version-poweredby-others' => 'Zewmi',
'version-license-info' => "MediaWiki xoseri jew nuştereno; MediaWiki'yer, weqfê xoseri nuşteren GNU lisansiya merdumi şene ke vıla kerê, bıvurnê u timar kerê.

Nuşterenê MediaWiki merdumi cı ra nahfat bivinê deye êyê mısade danê; feqet ke nêşeno BIROŞO yana XOSERİ VILA KERO qerantiya ney çına. bewni rê lisansta GNU'y.

enê programiya piya [{{SERVER}}{{SCRIPTPATH}}/COPYING jew kopyay lisans dê GNU] zi şımarê icab keno; narak lisansê şıma çıno se, Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA adresi ra yana [//www.gnu.org/licenses/old-licenses/gpl-2.0.html enê lisansi buwane].",
'version-software' => 'Softwareyê ronayi',
'version-software-product' => 'Mal',
'version-software-version' => 'Versiyon',
'version-entrypoints' => "heruna dekewtış de GRE'i",
'version-entrypoints-header-entrypoint' => 'Heruna dekewtışi',
'version-entrypoints-header-url' => 'GRE',
'version-entrypoints-articlepath' => '[https://www.mediawiki.org/wiki/Manual:$wgArticlePath Article path]',
'version-entrypoints-scriptpath' => '[https://www.mediawiki.org/wiki/Manual:$wgScriptPath Script path]',

# Special:FilePath
'filepath' => 'Heruna dosyayer',
'filepath-page' => 'Dosya:',
'filepath-submit' => 'Şo',
'filepath-summary' => 'Na pela xısusiye raya temame jû dosya rê ana.
Resımi be tam asayış mocniyayê, tipê dosyaê bini be programê cıyo elaqedar direkt dest keno pê.',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Dosyayanê zey pêyan cı geyrê',
'fileduplicatesearch-summary' => 'Dosyanê çıftan bınê têmiyankewteyan de bıgeyre.',
'fileduplicatesearch-legend' => 'kopyayê ena dosya bigêre',
'fileduplicatesearch-filename' => 'Nameyê dosyayi',
'fileduplicatesearch-submit' => 'Cı geyre',
'fileduplicatesearch-info' => '$1 × $2 piksel<br />Ebatê dosyayî: $3<br />Tipê MIMEî: $4',
'fileduplicatesearch-result-1' => "Dosyayê ''$1î'' de hem-kopya çini yo.",
'fileduplicatesearch-result-n' => "Dosyayê ''$1î'' de {{PLURAL:$2|1 hem-kopya|$2 hem-kopyayî'}} esto.",
'fileduplicatesearch-noresults' => 'Ebe namey "$1" ra dosya nêdiyayê.',

# Special:SpecialPages
'specialpages' => 'Pelê xısusiy',
'specialpages-note' => '----
* Xısusi pelaya normal
* <span class="mw-specialpagerestricted">Xısusi peleyê keı rê ray nê deyaya.</span>
* <strong class="mw-specialpagerestricted">Peleya xısusiya ke grota verhefıza.</strong>',
'specialpages-group-maintenance' => 'Raporê pak tepiştîşî',
'specialpages-group-other' => 'Pelê xasiyê bini',
'specialpages-group-login' => 'Cı kewe / hesab vıraze',
'specialpages-group-changes' => 'Vurnayişê peni u logan',
'specialpages-group-media' => 'Raporê medya u bar kerdîşî',
'specialpages-group-users' => 'Karber u heqqî',
'specialpages-group-highuse' => 'Peleyê ke vêşi karênê',
'specialpages-group-pages' => 'listeyanê pelan',
'specialpages-group-pagetools' => 'Haletê pelan',
'specialpages-group-wiki' => 'Malumatê wiki u haceti',
'specialpages-group-redirects' => 'Pela xasîyê ke heteneyayê',
'specialpages-group-spam' => 'haletê spami',

# Special:BlankPage
'blankpage' => 'Pela venge',
'intentionallyblankpage' => 'Ena pel bi zanayişî weng mendo.',

# External image whitelist
'external_image_whitelist' => '  #no satır zey xo verde/raverde<pre>
#parçeyê ifadeya rêzbiyayeyani (têna zerreyê ıney de // ) u çıtayo/çiyo zi mende cêr de têare kerê.
#ney URL ya (hotlink) resmê teberi de hemcıta benî.
#Ê yê ke hemcıt (eşleşmek-hemçift) biyê zey resımi asenî, eqsê hal de zi zey gıreyê resmi aseno.
satır ê ke pê ney # # destpêkenê zey mışore/mıjore muamele vineno.
#herfa gırd û qıci ferq nêkeno

#parçeyê ifadeya rêzbiyayeyani bıerzê serê ney satıri. no satır zey xo verde/raverde </pre>',

# Special:Tags
'tags' => 'Etiketê vurnayîşê raştî',
'tag-filter' => 'Avrêcê [[Special:Tags|Etiketi]]:',
'tag-filter-submit' => 'Avrêc',
'tags-title' => 'Etiketan',
'tags-intro' => 'Eno pel de listeyê eyiketî este ke belki software pê ey edit kenî.',
'tags-tag' => 'Nameyê etiketi',
'tags-display-header' => 'Listeyê vurnayîşî de esayîş',
'tags-description-header' => 'Deskripsyonê manay ê hemî',
'tags-hitcount-header' => 'Vurnayîşî ke etiket biyê',
'tags-edit' => 'bıvurne',
'tags-hitcount' => '$1 {{PLURAL:$1|vurnayış|vurnayışi}}',

# Special:ComparePages
'comparepages' => 'Pela miqeyese ke',
'compare-selector' => 'Revizyonê pele miqayese bike',
'compare-page1' => 'Pele 1',
'compare-page2' => 'Pele 2',
'compare-rev1' => 'Revizyonê 1i',
'compare-rev2' => 'Revizyonê 2i',
'compare-submit' => 'Miqayese',
'compare-invalid-title' => 'Sernameyo ke şımayê vanê ravêrde niyo.',
'compare-title-not-exists' => 'Sernameyo ke şımayê vanê mewcud niyo.',
'compare-revision-not-exists' => 'Revizyono ke şımaye vanê mewcud niyo.',

# Database error messages
'dberr-header' => 'Ena Wiki de yew ğelet esta',
'dberr-problems' => 'Mayê muxulêm!
Ena sita dı newke xırabiya teknik esta.',
'dberr-again' => 'Yew di dekika vinder u hin bar bike.',
'dberr-info' => '(Erzmelumati ra xızmetkari nêreseno: $1)',
'dberr-usegoogle' => 'Ti eşkeno hem zi ser Google de bigêre.',
'dberr-outofdate' => 'Ekê raten da ma deyê belki zi newen niyo qandê coy diqet kerê.',
'dberr-cachederror' => 'Pel ke ti wazeno yew kopyayê cacheyî ay esto, ay belki rocaniyeyo.',

# HTML forms
'htmlform-invalid-input' => 'Inputê ti de tayê ğeletî estê',
'htmlform-select-badoption' => 'Ena değer ke ti spesife kerd yew opsiyonê raştî ni yo.',
'htmlform-int-invalid' => 'Ena değer ke ti spesife kerd yew reqem ni yo.',
'htmlform-float-invalid' => 'Ena değer ke ti spesife kerd yew amar ni yo.',
'htmlform-int-toolow' => 'Ena değer ke ti spesife kerd maxsimumê $1î ra kilmyer o.',
'htmlform-int-toohigh' => 'Ena değer ke ti spesife kerd maxsimumê $1î ra zafyer o.',
'htmlform-required' => 'Ena deger lazim o',
'htmlform-submit' => 'Bişirav',
'htmlform-reset' => 'Vurnayişî reyna biyar',
'htmlform-selectorother-other' => 'Bin',

# SQLite database support
'sqlite-has-fts' => '$1 tam-metn destegê cı geyrayışiya piya',
'sqlite-no-fts' => '$1 tam-metn bê destegê cı geyrayışi',

# New logging system
'logentry-delete-delete' => "Karber $1' pelay $3' besternê",
'logentry-delete-restore' => "Karber $1' pelay $3' peyser grot",
'logentry-delete-event' => '$1 asaneyaışê {{PLURAL:$5|weqey roceke|$5 weqey rocekan}} kerdi het de $3: $4 vurna',
'logentry-delete-revision' => '$1 $3: pela da $4 dı  {{PLURAL:$5|jew revizyon|$5 revizyon}} asayışê cı vurna',
'logentry-delete-event-legacy' => '$1 Asayışê vurnayışê $3 dekerde de',
'logentry-delete-revision-legacy' => '$1 revizyonê pela da $3 asayışê cı vurna',
'logentry-suppress-delete' => '$1  $3 rê pıloxneyê',
'logentry-suppress-event' => '$1 asayışê  {{PLURAL:$5|weqey rocaka|$5 weqey rocekan}}  $3: $4 miyanıki vurna',
'logentry-suppress-revision' => '$1 $3: pela da $4 dı  {{PLURAL:$5|jew revizyon|$5 revizyon}} asayışê cı xısusiye vurna',
'logentry-suppress-event-legacy' => '$1 Asayışê vurnayışê ciyo xısusiyeta cı $3 dekerde de',
'logentry-suppress-revision-legacy' => '$1 revizyonê pela da $3 asayışê cıyo xısuiye vurna',
'revdelete-content-hid' => 'zerreko nımte',
'revdelete-summary-hid' => 'xulusaya vurnayışa nımneyê',
'revdelete-uname-hid' => 'namey karberi nımteyo',
'revdelete-content-unhid' => 'errek mocneya',
'revdelete-summary-unhid' => 'Xulusaya vurnayışa mucneyê',
'revdelete-uname-unhid' => 'namey karberi ne nımteyo',
'revdelete-restricted' => 'verger (vergırewtış) ê ke qey xızmkaran biye',
'revdelete-unrestricted' => 'verger (ver gırewtış) ê ke qey xızmkaran diyê wera (wedariyê)',
'logentry-move-move' => "Karber $1' pelay $3' berd $4",
'logentry-move-move-noredirect' => "$1'i pelay $3 raçarnayış neker dı u berd $4",
'logentry-move-move_redir' => '$1 pela $3 pela da $4 sera hetenayış ra ahulnê',
'logentry-move-move_redir-noredirect' => '$1 hetenayışê qeydê pela da  $3 ahulnê $4 sero hetenayış vıraşt',
'logentry-patrol-patrol' => '$1 revizyonê pela da $4 $3 ke kontrol',
'logentry-patrol-patrol-auto' => "$1 pelay $3'i rewizyon dê $4 ya kontrol ke",
'logentry-newusers-newusers' => 'Hesabê karberi $1 vıraziya',
'logentry-newusers-create' => 'Hesabê karberi $1 vıraziya',
'logentry-newusers-create2' => 'Hesabê karberi $1 terefê $3 ra vıraziya',
'logentry-newusers-autocreate' => 'Hesabê $1 Otomatikmen vıraziya',
'newuserlog-byemail' => 'pê e-mail ra paralo şiravt',

# Feedback
'feedback-bugornote' => 'Jew mersela teferruato teknik esta şıma reca malumatê şıma hazıro se [ $1  jew xırab rapor] bıvinê.Zewbi zi, formê cerê xo rê şenê karfiyê. Vatışê xo pela da "[ $3  $2 ]", namey karber dê xoya piya u wasteriya karfiye.',
'feedback-subject' => 'Mersel:',
'feedback-message' => 'Mesac:',
'feedback-cancel' => 'Bıterkne',
'feedback-submit' => 'Peyxeberdar Bırşe',
'feedback-adding' => 'Pela rê peyxeberdar defêno...',
'feedback-error1' => 'Xeta: API ra neticey ne vıcyay',
'feedback-error2' => 'Xeta: Timar kerdış nebı',
'feedback-error3' => 'Xeta: API ra cewab çıno',
'feedback-thanks' => 'Teşekkur kemê! Vatışê şıma pela da "[$2 $1]" esta.',
'feedback-close' => 'Biya star',
'feedback-bugcheck' => 'Harika! Sadece [xırabina ke $1 ] çınyayışê cı kontrol keno.',
'feedback-bugnew' => 'Mı qontrol ke. Xetaya newi xeber ke',

# Search suggestions
'searchsuggest-search' => 'Cı geyre',
'searchsuggest-containing' => 'Estên...',

# API errors
'api-error-badaccess-groups' => 'Ena wiki de dosya barkerdışi rê mısade nêdeyêno.',
'api-error-badtoken' => 'Xirabiya zerrek:Xırab resim.',
'api-error-copyuploaddisabled' => 'URL barkerdış ena waster dı qefılyayo.',
'api-error-duplicate' => 'Ena {{PLURAL:$1|ze ke [zey $2]|biya [zey dosya da $2]}} zeq wesiqa biya wendeyê.',
'api-error-duplicate-archive' => 'Ena {{PLURAL:$1|vurneyaya [$2 zey na dosya]| [zerrey cı zey $2 dosya]}} aseno,feqet {{PLURAL:$1|ena dosya|tewr veri}} besterneyaya.',
'api-error-duplicate-archive-popup-title' => 'Ena {{PLURAL:$1|Dosya besterneyaya|dosya}} xora  besterneyaya.',
'api-error-duplicate-popup-title' => '{{PLURAL:$1|dosyaya|dosyaya}} dılet',
'api-error-empty-file' => 'Dosyaya ke şıma rışta venga.',
'api-error-emptypage' => 'Newi, pelaya veng vıraştışi rê mısade nêdeyêno.',
'api-error-fetchfileerror' => 'Xırabiya zerrek:Dosya grotış dı tay çi raşt nêşı.',
'api-error-fileexists-forbidden' => 'Jû dosya be nê nameyê "$1" ra xora esta, u naye sero nêşeno ke bınuşiyo.',
'api-error-fileexists-shared-forbidden' => 'Jû dosya be nameyê "$1" ra depoyê doyeyanê barekerdeyan de xora esta, u naye sero nêşeno ke bınuşiyo.',
'api-error-file-too-large' => 'Dosyaye ke şıma rışta zaf gırda.',
'api-error-filename-tooshort' => 'Namayê dosyayi zaf kilm a.',
'api-error-filetype-banned' => 'Tipê ena dosya qedexe biya.',
'api-error-filetype-banned-type' => '$1 {{PLURAL:$4|Dosya qebul ne vinena|dosya qebul ne vinena|Ena babeta dosya qebul ne vinena}}. Eke cırê izin deyayo se {{PLURAL:$3|Babatan dosyayan|babeta dosyayan}} de $2 bıvin.',
'api-error-filetype-missing' => 'Derganiya dosya kemiya',
'api-error-hookaborted' => 'Vurnayişê tu ke to cerbna pê yew çengal ra terkneya.',
'api-error-http' => 'Xırabiya zerreki:Wasteriya irtabet bırya.',
'api-error-illegal-filename' => 'Ena nameyê dosyayi kebul nibena.',
'api-error-internal-error' => 'Xırabiye zerrek:Na wikide barkerdış de şıma dı çıyê raşt nêşı.',
'api-error-invalid-file-key' => 'Xırabiye zerrek:İdari  depokerdışê dosya nêvineya.',
'api-error-missingparam' => 'Xırabiye zerrek:Parametre waştış dı xırabin',
'api-error-missingresult' => 'Xırabiya zerrek:Kopya kerdışê cı nêbı.',
'api-error-mustbeloggedin' => 'Dosya barkerdışi re cıkewtış icab keno.',
'api-error-mustbeposted' => 'Zırabiya zerrek:HTTP POST waştış icab keno',
'api-error-noimageinfo' => 'Barkerdışê dosya temamya lakin wasterira marê malumat nêdeyayo.',
'api-error-nomodule' => 'Xırabiya zerrek:Sazkerdışê modul dê barkerdışi nêvıraziyayo.',
'api-error-ok-but-empty' => 'Xırabiya zerrek:Wastero cıwan nêdano.',
'api-error-overwrite' => 'Ser yew dosyayê ke hama esta, ser ey qeyd nibena.',
'api-error-stashfailed' => 'Xırabiya zerrek:Wasteri idari dosyey kerdi vıni.',
'api-error-timeout' => 'Cıwab dayışê wasteri peyra mend.',
'api-error-unclassified' => 'Yew xeteyê nizanyeni biya.',
'api-error-unknown-code' => "$1'dı jew xeta vıciye",
'api-error-unknown-error' => 'Zerre xırabin:Dasoya barkerdış de tay çi raşt nêşı.',
'api-error-unknown-warning' => "$1'dı ikazo xırab:",
'api-error-unknownerror' => "$1'dı jew xeta vıciye",
'api-error-uploaddisabled' => 'BArkerdış ena wikide qefılneyayo',
'api-error-verification-error' => 'Dosya xırabiya yana derganiya cı xıraba.',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|saniya|saniyey}}',
'duration-minutes' => '$1 {{PLURAL:$1|deqa|deqey}}',
'duration-hours' => '($1 {{PLURAL:$1|seate|seati}})',
'duration-days' => '($1 {{PLURAL:$1|roce|roci}})',
'duration-weeks' => '$1 {{PLURAL: $1|hefte|heftey}}',
'duration-years' => '$1 {{PLURAL:$1|serre|serri}}',
'duration-decades' => '$1 {{PLURAL:$1|dades|dadesi}}',
'duration-centuries' => '$1 {{PLURAL:$1|seserre|seserri}}',
'duration-millennia' => '$1 {{PLURAL:$1|milenyum|milenyumi}}',

);
