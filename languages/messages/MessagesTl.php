<?php
/** Tagalog (Tagalog)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author AnakngAraw
 * @author Felipe Aira
 * @author Sky Harbor
 * @author tl.wikipedia.org sysops
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Midya',
	NS_SPECIAL          => 'Natatangi',
	NS_TALK             => 'Usapan',
	NS_USER             => 'Tagagamit',
	NS_USER_TALK        => 'Usapang_tagagamit',
	NS_PROJECT_TALK     => 'Usapang_$1',
	NS_FILE             => 'Talaksan',
	NS_FILE_TALK        => 'Usapang_talaksan',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Usapang_MediaWiki',
	NS_TEMPLATE         => 'Suleras',
	NS_TEMPLATE_TALK    => 'Usapang_suleras',
	NS_HELP             => 'Tulong',
	NS_HELP_TALK        => 'Usapang_tulong',
	NS_CATEGORY         => 'Kategorya',
	NS_CATEGORY_TALK    => 'Usapang_kategorya',
);

$namespaceAliases = array(
	'Talaksan'          => NS_FILE,
	'Usapang talaksan'  => NS_FILE_TALK,
	'Kaurian'         => NS_CATEGORY,
	'Usapang_kaurian' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Nagkadalawang mga panturo papunta sa ibang pahina', 'DoblengPanturo' ),
	'BrokenRedirects'           => array( 'Naputol na mga panturo papunta sa ibang pahina', 'NaputulangPanturo' ),
	'Disambiguations'           => array( 'Mga paglilinaw', 'Paglilinaw' ),
	'Userlogin'                 => array( 'Paglagda ng tagagamit', 'Maglagda' ),
	'Userlogout'                => array( 'Pag-alis sa pagkalagda ng tagagamit', 'AlisLagda' ),
	'CreateAccount'             => array( 'Likhain ang kuwenta', 'LikhaKuwenta' ),
	'Preferences'               => array( 'Mga nais' ),
	'Watchlist'                 => array( 'Talaan ng binabantayan', 'Bantayan' ),
	'Recentchanges'             => array( 'Mga huling binago', 'HulingBinago' ),
	'Upload'                    => array( 'Magkarga' ),
	'Listfiles'                 => array( 'Itala ang mga talaksan', 'Talaan ng mga talaksan', 'Talaan ng mga larawan' ),
	'Newimages'                 => array( 'Bagong mga talaksan', 'Bagong mga larawan' ),
	'Listusers'                 => array( 'Talaan ng mga tagagamit', 'Talaan ng tagagamit' ),
	'Listgrouprights'           => array( 'Talaan ng mga karapatan ng pangkat' ),
	'Statistics'                => array( 'Mga estadistika', 'Estadistika' ),
	'Randompage'                => array( 'Alin man', 'Alin mang pahina' ),
	'Lonelypages'               => array( 'Nangungulilang mga pahina', 'Ulilang mga pahina' ),
	'Uncategorizedpages'        => array( 'Mga pahinang walang kaurian' ),
	'Uncategorizedcategories'   => array( 'Mga kauriang walang kaurian' ),
	'Uncategorizedimages'       => array( 'Mga talaksang walang kaurian', 'Mga larawang walang kaurian' ),
	'Uncategorizedtemplates'    => array( 'Mga suleras na walang kaurian' ),
	'Unusedcategories'          => array( 'Hindi ginagamit na mga kaurian' ),
	'Unusedimages'              => array( 'Hindi ginagamit na mga talaksan', 'Hindi ginagamit na mga larawan' ),
	'Wantedpages'               => array( 'Ninanais na mga pahina', 'Putol na mga kawing' ),
	'Wantedcategories'          => array( 'Ninanais na mga kaurian' ),
	'Wantedfiles'               => array( 'Ninanais na mga talaksan' ),
	'Wantedtemplates'           => array( 'Ninanais na mga suleras' ),
	'Mostlinked'                => array( 'Mga pahinang may pinakamaraming kawing', 'Pinakamaraming kawing' ),
	'Mostlinkedcategories'      => array( 'Mga kauriang may pinakamaraming kawing', 'Pinakagamiting mga kaurian' ),
	'Mostlinkedtemplates'       => array( 'Mga suleras na may pinakamaraming kawing', 'Pinakagamiting mga suleras' ),
	'Mostimages'                => array( 'Mga talaksang may pinakamaraming kawing', 'Pinakamaraming talaksan', 'Pinakamaraming larawan' ),
	'Mostcategories'            => array( 'Pinakamaraming mga kaurian' ),
	'Mostrevisions'             => array( 'Pinakamaraming mga pagbabago' ),
	'Fewestrevisions'           => array( 'Pinakakaunting mga pagbabago' ),
	'Shortpages'                => array( 'Maikling mga pahina' ),
	'Longpages'                 => array( 'Mahabang mga pahina' ),
	'Newpages'                  => array( 'Bagong mga pahina' ),
	'Ancientpages'              => array( 'Sinaunang mga pahina' ),
	'Deadendpages'              => array( 'Mga pahinang sukol', 'Mga pahinang walang lagusan' ),
	'Protectedpages'            => array( 'Mga pahinang nakasanggalang' ),
	'Protectedtitles'           => array( 'Mga pamagat na nakasanggalang' ),
	'Allpages'                  => array( 'Lahat ng mga pahina', 'LahatPahina' ),
	'Prefixindex'               => array( 'Talatuntunan ng unlapi' ),
	'Ipblocklist'               => array( 'Talaan ng hinahadlangan', 'Talaan ng mga hinahadlangan', 'Talaan ng hinahadlangang IP' ),
	'Specialpages'              => array( 'Natatanging mga pahina' ),
	'Contributions'             => array( 'Mga ambag' ),
	'Emailuser'                 => array( 'Tagagamit ng e-liham' ),
	'Confirmemail'              => array( 'Tiyakin ang e-liham' ),
	'Whatlinkshere'             => array( 'Ano ang nakakawing dito' ),
	'Recentchangeslinked'       => array( 'Nakakawing ng kamakailang pagbabago', 'Kaugnay na mga pagbabago' ),
	'Movepage'                  => array( 'Ilipat ang pahina' ),
	'Blockme'                   => array( 'Hadlangang ako' ),
	'Booksources'               => array( 'Mga pinagmulang aklat' ),
	'Categories'                => array( 'Mga kaurian' ),
	'Export'                    => array( 'Pagluluwas' ),
	'Version'                   => array( 'Bersyon' ),
	'Allmessages'               => array( 'Lahat ng mga mensahe' ),
	'Log'                       => array( 'Tala', 'Mga tala' ),
	'Blockip'                   => array( 'Hadlangan', 'Hadlangan ang IP', 'Hadlangan ang tagagamit' ),
	'Undelete'                  => array( 'Huwag burahin' ),
	'Import'                    => array( 'Pag-aangkat' ),
	'Lockdb'                    => array( 'Ikandado ang kalipunan ng dato' ),
	'Unlockdb'                  => array( 'Huwag ikandado ang kalipunan ng dato' ),
	'Userrights'                => array( 'Mga karapatan ng tagagamit' ),
	'MIMEsearch'                => array( 'Paghahanap ng MIME' ),
	'FileDuplicateSearch'       => array( 'Paghahanap ng kamukhang talaksan' ),
	'Unwatchedpages'            => array( 'Mga pahinang hindi binabantayanan' ),
	'Listredirects'             => array( 'Talaan ng mga pagturo sa ibang pahina' ),
	'Revisiondelete'            => array( 'Pagbura ng pagbabago' ),
	'Unusedtemplates'           => array( 'Mga suleras na hindi ginagamit' ),
	'Randomredirect'            => array( 'Pagtuturo papunta sa alin mang pahina' ),
	'Mypage'                    => array( 'Pahina ko' ),
	'Mytalk'                    => array( 'Usapan ko' ),
	'Mycontributions'           => array( 'Mga ambag ko' ),
	'Listadmins'                => array( 'Talaan ng mga tagapangasiwa' ),
	'Listbots'                  => array( 'Talaan ng mga bot' ),
	'Popularpages'              => array( 'Sikat na mga pahina' ),
	'Search'                    => array( 'Maghanap' ),
	'Resetpass'                 => array( 'Baguhin ang hudyat', 'Muling itakda ang hudyat', 'Muling magtakda ng hudyat' ),
	'Withoutinterwiki'          => array( 'Walang ugnayang-wiki' ),
	'MergeHistory'              => array( 'Pagsanibin ang kasaysayan' ),
	'Filepath'                  => array( 'Daanan ng talaksan' ),
	'Invalidateemail'           => array( 'Hindi tanggap na e-liham' ),
	'Blankpage'                 => array( 'Tanggalin ang nilalaman ng pahina' ),
	'LinkSearch'                => array( 'Paghahanap ng kawing' ),
	'DeletedContributions'      => array( 'Naburang mga ambag' ),
	'Tags'                      => array( 'Mga tatak' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Pagsasalungguhit ng kawing:',
'tog-highlightbroken'         => 'Ayusin ang mga sirang kawing <a href="" class="new">nang ganito</a> (alternatibo: nang ganito<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Pantayin ang mga talata',
'tog-hideminor'               => 'Itago ang mga maliliit na pagbabago sa mga huling binago',
'tog-hidepatrolled'           => 'Ikubli ang napatrolyang mga pagbabagong nasa kamakailang mga pagbabago',
'tog-newpageshidepatrolled'   => 'Itago ang napatrolyang mga pahina mula talaan ng bagong pahina',
'tog-extendwatchlist'         => 'Palawigin ang talaan ng mga binabantayan upang maipakita ang lahat ng mga pagbabago, hindi lamang ang pinakakamakailan lamang',
'tog-usenewrc'                => 'Gamitin ang pinadagdagang huling binago (kailangan ng JavaScript)',
'tog-numberheadings'          => 'Automatikong bilangin ang mga pamagat',
'tog-showtoolbar'             => "Ipakita ang ''toolbar'' ng pagbabago (JavaScript)",
'tog-editondblclick'          => 'Magbago ng mga pahina sa dalawahang pagpindot (JavaScript)',
'tog-editsection'             => 'Payagan ang mga pagbabagong panseksyon sa mga [baguhin] na kawing',
'tog-editsectiononrightclick' => 'Payagan ang mga pagbabagong panseksyon sa pakanang pagpindot ng mga panseksyong pamagat (JavaScript)',
'tog-showtoc'                 => 'Ipakita ang talaan ng mga nilalaman (sa mga pahinang may higit sa 3 punong pamagat)',
'tog-rememberpassword'        => 'Tandaan ang paglagda ko sa panghanaphanap na ito (pinakamarami na ang $1 {{PLURAL:$1|araw|mga araw}})',
'tog-watchcreations'          => 'Idagdag ang mga pahinang nilikha ko sa aking tala ng mga binabantayan',
'tog-watchdefault'            => 'Idagdag ang mga pahinang binago ko sa aking tala ng mga binabantayan',
'tog-watchmoves'              => 'Idagdag ang mga pahinang inilipat ko sa aking tala ng mga binabantayan',
'tog-watchdeletion'           => 'Idagdag mga pahinang ibinura ko sa aking tala ng mga binabantayan',
'tog-minordefault'            => 'Markahan ang lahat ng pagbabago bilang maliit nang nakatakda',
'tog-previewontop'            => 'Ipakita ang paunang tingin bago ang kahon ng pagbabago',
'tog-previewonfirst'          => 'Ipakita ang paunang tingin sa unang pagbabago',
'tog-nocache'                 => 'Huwag paganahin ang pagtatago ng pahinang pantingintingin',
'tog-enotifwatchlistpages'    => 'Padalhan ako ng e-liham kapag nabago ang isa sa mga pahinang binabantayan ko',
'tog-enotifusertalkpages'     => 'Padalhan ako ng e-liham kapag binago ang aking pahina ng usapan',
'tog-enotifminoredits'        => 'Padalhan din ako ng e-liham para sa mga maliliit na pagbabago ng mga pahina',
'tog-enotifrevealaddr'        => 'Ipakita ang adres ng e-liham ko sa loob ng mga e-liham ng pagpapahayag',
'tog-shownumberswatching'     => 'Ipakita ang bilang ng mga nagbabantay na tagagamit',
'tog-oldsig'                  => 'Paunang tingin ng kasalukuyang lagda:',
'tog-fancysig'                => 'Ituring ang lagda bilang teksto ng wiki (walang automatikong pagkawing)',
'tog-externaleditor'          => 'Gumamit ng nakatakdang panlabas na pambago (para sa mga dalubhasa lamang, kailangan ng natatanging mga pagtatakda sa iyong kompyuter)',
'tog-externaldiff'            => 'Gumamit ng nakatakdang ibang panlabas (para sa mga dalubhasa lamang, kailangan ng natatanging pagtatakda sa iyong kompyuter)',
'tog-showjumplinks'           => 'Payagan ang mga "tumalon sa" na kawing pampagamit',
'tog-uselivepreview'          => 'Gamitin ang buhay na paunang tingin (JavaScript) (Eksperimental)',
'tog-forceeditsummary'        => 'Pagsabihan ako kapag nagpapasok ng walang-lamang buod ng pagbabago',
'tog-watchlisthideown'        => 'Itago ang aking mga pagbabago mula sa tala ng mga binabantayan',
'tog-watchlisthidebots'       => 'Itago ang mga pagbabago ng mga bot mula sa tala ng mga binabantayan',
'tog-watchlisthideminor'      => 'Itago ang mga maliliit na pagbabago mula sa tala ng mga binabantayan',
'tog-watchlisthideliu'        => 'Itago ang mga pagbabago ng mga nakalagdang tagagamit mula sa tala ng mga binabantayan',
'tog-watchlisthideanons'      => 'Itago ang mga pagbabago ng hindi nakikilalang mga tagagamit mula sa tala ng mga binabantayan',
'tog-watchlisthidepatrolled'  => 'Itago ang napatrolyang mga pagbabago mula sa tala ng mga binabantayan',
'tog-nolangconversion'        => 'Huwag paganahin ang pagpapalit ng mga halagang nagkakaibaiba (baryante)',
'tog-ccmeonemails'            => 'Padalahan ako ng mga kopya ng mga ipinadala kong e-liham sa ibang mga tagagamit',
'tog-diffonly'                => 'Huwag ipakita ang nilalaman ng pahinang nasa ilalim ng mga pagkakaiba',
'tog-showhiddencats'          => 'Ipakita ang mga nakatagong kategorya',
'tog-noconvertlink'           => 'Huwag paganahin ang pagpapalit ng pamagat na pangkawing',
'tog-norollbackdiff'          => 'Alisin ang mga pagkakaiba pagkatapos isagawa ang pagpapagulong na pabalik sa dati',

'underline-always'  => 'Palagi',
'underline-never'   => 'Hindi magpakailanman',
'underline-default' => 'Tinakda ng pambasa-basa',

# Font style option in Special:Preferences
'editfont-style'     => 'Baguhin ang estilong pantitik ng lugar:',
'editfont-default'   => 'Tinakda ng pambasa-basa',
'editfont-monospace' => 'Estilo ng titik na isahan ang puwang',
'editfont-sansserif' => 'Estilo ng titik na walang gutli sa dulo',
'editfont-serif'     => 'Estilo ng titik na may gutli sa dulo',

# Dates
'sunday'        => 'Linggo',
'monday'        => 'Lunes',
'tuesday'       => 'Martes',
'wednesday'     => 'Miyerkules',
'thursday'      => 'Huwebes',
'friday'        => 'Biyernes',
'saturday'      => 'Sabado',
'sun'           => 'Lin',
'mon'           => 'Lun',
'tue'           => 'Mar',
'wed'           => 'Miy',
'thu'           => 'Huw',
'fri'           => 'Biy',
'sat'           => 'Sab',
'january'       => 'Enero',
'february'      => 'Pebrero',
'march'         => 'Marso',
'april'         => 'Abril',
'may_long'      => 'Mayo',
'june'          => 'Hunyo',
'july'          => 'Hulyo',
'august'        => 'Agosto',
'september'     => 'Setyembre',
'october'       => 'Oktubre',
'november'      => 'Nobyembre',
'december'      => 'Disyembre',
'january-gen'   => 'Enero',
'february-gen'  => 'Pebrero',
'march-gen'     => 'Marso',
'april-gen'     => 'Abril',
'may-gen'       => 'Mayo',
'june-gen'      => 'Hunyo',
'july-gen'      => 'Hulyo',
'august-gen'    => 'Agosto',
'september-gen' => 'Setyembre',
'october-gen'   => 'Oktubre',
'november-gen'  => 'Nobyembre',
'december-gen'  => 'Disyembre',
'jan'           => 'Ene',
'feb'           => 'Peb',
'mar'           => 'Mar',
'apr'           => 'Abr',
'may'           => 'May',
'jun'           => 'Hun',
'jul'           => 'Hul',
'aug'           => 'Ago',
'sep'           => 'Set',
'oct'           => 'Okt',
'nov'           => 'Nob',
'dec'           => 'Dis',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategorya|Mga kategorya}}',
'category_header'                => 'Mga pahina sa kategoryang "$1"',
'subcategories'                  => 'Mga subkategorya',
'category-media-header'          => 'Mga midya sa kategoryang "$1"',
'category-empty'                 => "''Kasalukuyang walang artikulo o midya ang kategoryang ito.''",
'hidden-categories'              => '{{PLURAL:$1|Nakatagong kategorya|Mga nakatagong kategorya}}',
'hidden-category-category'       => 'Mga nakatagong kategorya',
'category-subcat-count'          => '{{PLURAL:$2|Ang kategoryang ito ay mayroong sumusunod na subkategorya lamang.|Ang kategoryang ito ay mayroong sumusunod na {{PLURAL:$1|subkategorya|$1 subkategorya}}, mula sa kabuuan na $2.}}',
'category-subcat-count-limited'  => 'Ang kategoryang ito ay mayroong sumusunod na {{PLURAL:$1|subkategorya|$1 subkategorya}}.',
'category-article-count'         => '{{PLURAL:$2|Ang kategoryang ito ay naglalaman lamang ng sumusunod na pahina.|Ang sumusunod na {{PLURAL:$1|pahina ay|$1 pahina ay}} nasa kategoryang ito, mula sa kabuuan na $2.}}',
'category-article-count-limited' => 'Ang sumusunod na {{PLURAL:$1|pahina ay|$1 pahina ay}} nasa pangkasalukuyang kategorya.',
'category-file-count'            => '{{PLURAL:$2|Ang kategoryang ito ay naglalaman lamang ng sumusunod na talaksan.|Ang sumusunod na {{PLURAL:$1|talaksan ay|$1 talaksan ay}} nasa kategoryang ito, mula sa kabuuan na $2.}}',
'category-file-count-limited'    => 'Ang sumusunod na {{PLURAL:$1|talaksan ay|$1 talaksan}} ay nasa kasalukuyang kategorya.',
'listingcontinuesabbrev'         => 'karugtong',
'index-category'                 => 'Mga pahinang may talatuntunan',
'noindex-category'               => 'Mga pahinang walang talatuntunan',

'mainpagetext'      => "'''Matagumpay na ininstala ang MediaWiki.'''",
'mainpagedocfooter' => "Silipin ang [http://meta.wikimedia.org/wiki/Help:Contents Patnubay sa Tagagamit] (''\"User's Guide\"'') para sa kaalaman sa paggamit ng wiking ''software''.

== Pagsisimula ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Tala ng mga nakatakdang kumpigurasyon]
* [http://www.mediawiki.org/wiki/Manual:FAQ Mga malimit itanong sa MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Tala ng mga pinadadalhan ng liham ng MediaWiki]",

'about'         => 'Patungkol',
'article'       => 'Pahina ng nilalaman',
'newwindow'     => '(magbubukas sa bagong bintana)',
'cancel'        => 'Kanselahin',
'moredotdotdot' => 'Damihan pa...',
'mypage'        => 'Pahina ko',
'mytalk'        => 'Usapan ko',
'anontalk'      => 'Usapan para sa IP na ito',
'navigation'    => 'Paglilibot (nabigasyon)',
'and'           => ',&#32;at',

# Cologne Blue skin
'qbfind'         => 'Hanapin',
'qbbrowse'       => 'Basa-basahin',
'qbedit'         => 'Baguhin',
'qbpageoptions'  => 'Itong pahina',
'qbpageinfo'     => 'Konteksto',
'qbmyoptions'    => 'Mga pahina ko',
'qbspecialpages' => 'Mga natatanging pahina',
'faq'            => "Mga malimit itanong (''FAQ'')",
'faqpage'        => "Project:Mga malimit itanong (''FAQ'')",

# Vector skin
'vector-action-addsection'       => 'Magdagdag ng paksa',
'vector-action-delete'           => 'Burahin',
'vector-action-move'             => 'Ilipat',
'vector-action-protect'          => 'Ipagsanggalang',
'vector-action-undelete'         => 'Alisin ang pagbubura',
'vector-action-unprotect'        => 'Alisin ang pagsasanggalang',
'vector-simplesearch-preference' => 'Paganahin ang pinainam na mga mungkahi sa paghahanap (pabalat na Vector lang)',
'vector-view-create'             => 'Likhain',
'vector-view-edit'               => 'Baguhin',
'vector-view-history'            => 'Tingnan ang kasaysayan',
'vector-view-view'               => 'Basahin',
'vector-view-viewsource'         => 'Tingnan ang pinagmulan',
'actions'                        => 'Mga kilos',
'namespaces'                     => 'Mga ngalan-espasyo',
'variants'                       => 'Naiiba pa',

'errorpagetitle'    => 'Pagkakamali',
'returnto'          => 'Bumalik sa $1.',
'tagline'           => 'Mula sa {{SITENAME}}',
'help'              => 'Tulong',
'search'            => 'Paghahanap',
'searchbutton'      => 'Maghanap',
'go'                => 'Gawin',
'searcharticle'     => 'Gawin',
'history'           => 'Kasaysayan ng pahina',
'history_short'     => 'Kasaysayan',
'updatedmarker'     => 'isinapanahon mula noong huli kong pagdalaw',
'info_short'        => 'Kabatiran',
'printableversion'  => 'Bersyong maililimbag',
'permalink'         => 'Palagiang kawing',
'print'             => 'Ilimbag',
'edit'              => 'Baguhin',
'create'            => 'Likhain',
'editthispage'      => 'Baguhin ang pahinang ito',
'create-this-page'  => 'Likhain ang pahinang ito',
'delete'            => 'Burahin',
'deletethispage'    => 'Burahin itong pahina',
'undelete_short'    => 'Baligtarin ang pagbura ng {{PLURAL:$1|isang pagbabago|$1 pagbabago}}',
'protect'           => 'Ipagsanggalang',
'protect_change'    => 'baguhin',
'protectthispage'   => 'Ipagsanggalang itong pahina',
'unprotect'         => 'Alisin ang pagsasanggalang',
'unprotectthispage' => 'Alisin ang pagsasanggalang sa pahinang ito',
'newpage'           => 'Bagong pahina',
'talkpage'          => 'Pag-usapan ang pahinang ito',
'talkpagelinktext'  => 'Usapan',
'specialpage'       => 'Natatanging pahina',
'personaltools'     => 'Mga kagamitang pansarili',
'postcomment'       => 'Bagong seksyon',
'articlepage'       => 'Tingnan ang pahina ng nilalaman',
'talk'              => 'Usapan',
'views'             => 'Mga anyo',
'toolbox'           => 'Mga kagamitan',
'userpage'          => 'Tingnan ang pahina ng tagagamit',
'projectpage'       => 'Tingnan ang pahina ng proyekto',
'imagepage'         => 'Tingnan ang pahina ng talaksan',
'mediawikipage'     => 'Tingnan ang pahina ng mensahe',
'templatepage'      => 'Tingnan ang pahina ng suleras',
'viewhelppage'      => 'Tingnan ang pahina ng tulong',
'categorypage'      => 'Tingnan ang pahina ng kategorya',
'viewtalkpage'      => 'Tingnan ang usapan',
'otherlanguages'    => 'Sa ibang wika',
'redirectedfrom'    => '(Ikinarga mula sa $1)',
'redirectpagesub'   => 'Pahina ng pagkarga',
'lastmodifiedat'    => 'Huling binago ang pahinang ito noong $2, noong $1.',
'viewcount'         => 'Namataan na pahinang ito nang {{PLURAL:$1|isang|$1}} beses.',
'protectedpage'     => 'Pahinang nakasanggalang',
'jumpto'            => 'Tumalon sa:',
'jumptonavigation'  => 'paglilibot (nabigasyon)',
'jumptosearch'      => 'paghahanap',
'view-pool-error'   => 'Paumanhin, ngunit masyado pong abala ang mga serbidor sa sandaling ito.
Masyadong maraming tagagamit ay sinusubukang tingnan ang pahinang ito.
Mangyari lamang na maghintay po nang sandali bago niyo pong subukang mataanin muli ang pahinang ito.

$1',
'pool-timeout'      => 'Ang pagpapahinga ay naghihintay ng kandado',
'pool-queuefull'    => 'Puno na ang pisan ng mga pila',
'pool-errorunknown' => 'Hindi nalalamang kamalian',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Tungkol sa {{SITENAME}}',
'aboutpage'            => 'Project:Patungkol',
'copyright'            => 'Maaaring gamitin ang nilalaman sa ilalim ng $1.',
'copyrightpage'        => '{{ns:project}}:Mga karapatang-ari',
'currentevents'        => 'Mga kasalukuyang pangyayari',
'currentevents-url'    => 'Project:Mga kasalukuyang pangyayari',
'disclaimers'          => 'Mga pagtatanggi',
'disclaimerpage'       => 'Project:Pangkalahatang pagtatanggi',
'edithelp'             => 'Tulong sa pagbabago',
'edithelppage'         => 'Help:Pagbabago',
'helppage'             => 'Help:Mga nilalaman',
'mainpage'             => 'Unang Pahina',
'mainpage-description' => 'Unang Pahina',
'policy-url'           => 'Project:Patakaran',
'portal'               => 'Puntahan ng pamayanan',
'portal-url'           => 'Project:Puntahan ng pamayanan',
'privacy'              => 'Patakaran sa paglilihim',
'privacypage'          => 'Project:Patakaran sa paglilihim',

'badaccess'        => 'Kamalian sa pahintulot',
'badaccess-group0' => 'Hindi ka pinahintulutang isagawa ang hiniling mong kilos.',
'badaccess-groups' => 'Ang kilos na hiniling mo ay nakatakda lamang para sa mga tagagamit sa {{PLURAL:$2|pangkat na|isa sa mga pangkat na}}: $1.',

'versionrequired'     => 'Kinakailangan ang bersyong $1 ng MediaWiki',
'versionrequiredtext' => 'Kinakailangan ang bersyong $1 ng MediaWiki upang magamit ang pahinang ito.
Tingnan ang [[Special:Version|pahina ng bersyon]].',

'ok'                      => 'Sige',
'retrievedfrom'           => 'Ikinuha mula sa "$1"',
'youhavenewmessages'      => 'Mayroon kang $1 ($2).',
'newmessageslink'         => 'mga bagong mensahe',
'newmessagesdifflink'     => 'huling pagbabago',
'youhavenewmessagesmulti' => 'Mayroon kang mga bagong mensahe sa $1',
'editsection'             => 'baguhin',
'editold'                 => 'baguhin',
'viewsourceold'           => 'tingnan ang pinagmulan',
'editlink'                => 'baguhin',
'viewsourcelink'          => 'tingnan ang pinagmulan',
'editsectionhint'         => 'Baguhin ang seksyon: $1',
'toc'                     => 'Mga nilalaman',
'showtoc'                 => 'ipakita',
'hidetoc'                 => 'itago',
'thisisdeleted'           => 'Tingnan o ibalik ang $1?',
'viewdeleted'             => 'Tingnan ang $1?',
'restorelink'             => '{{PLURAL:$1|isang binurang pagbabagp|$1 binurang pagbabago}}',
'feedlinks'               => 'Subo/Karga:',
'feed-invalid'            => 'Hindi tanggap na uri ng serbisyo ng pagpaparating.',
'feed-unavailable'        => 'Walang serbisyo mula sa sindikasyong pangpaglalathala',
'site-rss-feed'           => '$1 kargang RSS',
'site-atom-feed'          => '$1 kargang Atom',
'page-rss-feed'           => '"$1" kargang RSS',
'page-atom-feed'          => '"$1" kargang Atom',
'red-link-title'          => '$1 (hindi umiiral ang pahina)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Pahina',
'nstab-user'      => 'Pahina ng tagagamit',
'nstab-media'     => 'Pahina ng midya',
'nstab-special'   => 'Natatanging pahina',
'nstab-project'   => 'Pahina ng proyekto',
'nstab-image'     => 'Talaksan',
'nstab-mediawiki' => 'Mensahe',
'nstab-template'  => 'Suleras',
'nstab-help'      => 'Pahina ng tulong',
'nstab-category'  => 'Kategorya',

# Main script and global functions
'nosuchaction'      => 'Walang ganitong kilos',
'nosuchactiontext'  => 'Hindi tanggap ang galaw na tinukoy ng URL.
Maaaring nagkamali ka sa pagmamakinilya ng URL, o sumunod sa isang maling kawing.
Maaari rin itong magpahiwatig ng isang depektong nasa loob ng {{SITENAME}}.',
'nosuchspecialpage' => 'Walang ganyang natatanging pahina',
'nospecialpagetext' => '<strong>Humiling ka ng isang maling natatanging pahina.</strong>

Matatagpuan ang isang tala ng mga tamang natatanging pahina sa [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Kamalian',
'databaseerror'        => 'Kamalian sa kalipunan ng datos',
'dberrortext'          => 'Nagkaroon po ng isang pagkakamali sa usisang pampalaugnayan sa kalipunan ng datos.
Maaaring dahil ito sa depekto sa sopwer (\'\'software\'\').
Ang huling sinubukang paguusisa sa kalipunan ng datos ay:
<blockquote><tt>$1</tt></blockquote>
mula sa gawaing "<tt>$2</tt>".
Ibinalik ng kalipunan ng datos ang kamaliang "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Nagkaroon po ng isang pagkakamali sa usisang pampalaugnayan sa kalipunan ng datos.
Ang huling sinubukang paguusisa sa kalipunan ng datos ay:
"$1"
mula sa gawaing "$2".
Ibinalik ng kalipunan ng datos ang kamaliang "$3: $4"',
'laggedslavemode'      => "'''Babala:''' Maaaring hindi naglalaman ang pahina ng mga huling dagdag.",
'readonly'             => 'Nakakandado ang kalipunan ng datos',
'enterlockreason'      => 'Maglagay ng dahilan sa pagkakandado, kasama ang taya kung kailan magtatapos ang pagkakandado',
'readonlytext'         => 'Kasalukuyang nakakandado ang kalipunan ng datos para sa mga bagong entrada at iba pang mga pagbabago, marahil para sa gawaing pampagpapanatili ng kalipunan ng datos, magbabalik ito sa normal pagkaraan nito.

Nagbigay ng ganitong dahilan ang tagapangasiwang nagkandado nito: $1',
'missing-article'      => 'Hindi natagpuan ng kalipunan ng datos ang teksto ng isang pahinang dapat nitong natuklasan, may pangalang "$1" $2.

Kalimitang dahil ito sa pagsunod sa isang wala sa panahong pagkakaiba o kawing na pangkasaysayan sa isang pahinang nabura.

Kung hindi ito ang kaso, maaaring nakatagpo ka ng isang depekto sa sopwer.
Pakiulat ito sa isang [[Special:ListUsers/sysop|tagapangasiwa]], na ibinibigay ang URL.',
'missingarticle-rev'   => '(pagbabago#: $1)',
'missingarticle-diff'  => '(Pagkakaiba: $1, $2)',
'readonly_lag'         => 'Automatikong kinandado ang kalipunan ng datos habang humahabol ang mga aliping serbidor sa pinunong kalipunan nito',
'internalerror'        => 'Kamaliang panloob',
'internalerror_info'   => 'Kamaliang panloob: $1',
'fileappenderrorread'  => 'Hindi mabasa ang "$1" habang inilalakip.',
'fileappenderror'      => 'Hindi mailakip ang "$1" sa "$2".',
'filecopyerror'        => 'Hindi makopya ang talaksang "$1" sa "$2".',
'filerenameerror'      => 'Hindi mapalitan ang pangalan ng talaksang "$1" sa "$2".',
'filedeleteerror'      => 'Hindi mabura ang talaksang "$1".',
'directorycreateerror' => 'Hindi malikha ang direktoryong "$1".',
'filenotfound'         => 'Hindi mahanap ang talaksang "$1".',
'fileexistserror'      => 'Hindi makapagsulat sa talaksang "$1": umiiral ang talaksan',
'unexpected'           => 'Hindi inaasahang halaga: "$1"="$2".',
'formerror'            => 'Kamalian: hindi maipadala ang pormularyo',
'badarticleerror'      => 'Hindi maisasagawa ang gawaing ito sa pahinang ito.',
'cannotdelete'         => 'Hindi mabura ang pahina o talaksang "$1".
Maaaring ibinura na ito ng iba.',
'badtitle'             => 'Hindi kanais-nais na pamagat',
'badtitletext'         => 'Ang hiniling na pamagat ng pahina ay hindi katanggap-tanggap, wala, o isang may-maling kawing na pamagat na pangugnayang-wika (interwika) o pangugnayang wiki (interwiki).
Maaaring naglalaman ito ng isa o higit pang mga panitik (karakter) na hindi maaaring gamitin para sa mga pamagat.',
'perfcached'           => 'Ang sumusunod na datos ay nakaligpit at maaaring wala na sa panahon.',
'perfcachedts'         => 'Ang sumusunod na datos ay nakaligpit, at dating isinapanahon noong $1.',
'querypage-no-updates' => 'Kasulukuyang hindi gumagana ang mga pagbabago para sa pahinang ito.
Ang mga dato dito ay hindi pa masasariwa sa kasalukuyan.',
'wrong_wfQuery_params' => 'Maling mga parametro sa wfQuery()<br />
Tungkulin: $1<br />
Tanong: $2',
'viewsource'           => 'Tingnan ang pinagmulan',
'viewsourcefor'        => 'para sa $1',
'actionthrottled'      => 'Hinadlangan ang gawain',
'actionthrottledtext'  => "Bilang paraang panglaban sa ''spam'', pinigalan kang magawa ang galaw na ito nang maraming ulit sa loob ng maikling panahon, at lumabis ka na sa limitasyong ito.
Pakisubok na lang ulit pagkaraan ng kaunting mga minuto.",
'protectedpagetext'    => 'Kinandado ang pahinang ito upang mahadlangang ang pagbago.',
'viewsourcetext'       => 'Maaari mong tingnan at kopyahin ang pinagmulan ng pahinang ito:',
'protectedinterface'   => "Nagbibigay ang pahinang ito ng tekstong panghangganan (''interface'') para sa sopwer, at ikinandado para maiwasan ang pangaabuso.",
'editinginterface'     => "'''Babala:''' Binabago mo ang isang pahinang ginagamit sa pagbibigay ng tekstong panghangganan para sa sopwer.
Makakaapekto ang mga pagbago sa pahinang ito sa anyo ng hangganang (''interface'') pangtagagamit na para sa ibang mga tagagamit.
Para sa mga salinwika, paki isang-alang-alang o konsiderahin ang paggamit ng [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], ang proyektong panglokalisasyon ng MediaWiki.",
'sqlhidden'            => '(nakatago ang tanong ng SQL)',
'cascadeprotected'     => 'Nakasanggalang ang pahinang ito mula sa mga pagbabago, dahil kabilang ito sa sumusunod na {{PLURAL:$1|pahinang|mga pahinang}} nakasanggalang sa pamamagitan ng binuhay na opsyong "nahuhulog" (kumakaskada):
$2',
'namespaceprotected'   => "Wala kang pahintulot na magbago ng mga pahinang nasa ngalan-espasyong '''$1'''.",
'customcssjsprotected' => 'Wala kang pahintulot na baguhin ang pahinang ito, dahil naglalaman ito ng mga kagustuhang pansarili ng ibang tagagamit.',
'ns-specialprotected'  => 'Hindi pwedeng baguhin ang mga natatanging pahina.',
'titleprotected'       => "Nakasanggalang ang pamagat na ito mula sa paglikha ni [[User:$1|$1]].
Ang ibinigay na dahilan ay ''$2''.",

# Virus scanner
'virus-badscanner'     => "Masamang kompigurasyon: hindi kilalang tagahagilap (iskaner) ng birus: ''$1''",
'virus-scanfailed'     => 'nabigo ang paghagilap (kodigong $1)',
'virus-unknownscanner' => 'hindi kilalang panlaban sa birus:',

# Login and logout pages
'logouttext'                 => "'''Nakaalis ka na sa pagkakalagda.'''

Maaari kang tumuloy sa paggamit ng {{SITENAME}} nang hindi nakikilala (anonimo), o maaaring kang [[Special:UserLogin|lumagda/tumala muli]] bilang kapareho o ibang tagagamit.
Tandaan na may ilang pahinang maaaring magpatuloy na nagpapakitang parang nakalagda ka pa rin, hanggang sa linisin mo ang iyong baunang pambasa-basa (''browser cache'').",
'welcomecreation'            => '== Maligayang pagdating, $1! ==
Nilikha na ang iyong kuwenta.
Huwag kalimutang baguhin ang iyong [[Special:Preferences|mga kagustuhan sa {{SITENAME}}]].',
'yourname'                   => 'Bansag:',
'yourpassword'               => 'Hudyat:',
'yourpasswordagain'          => 'Hudyat mo uli:',
'remembermypassword'         => 'Tandaan ang paglagda ko sa kompyuter na ito (pinakamarami na ang $1 {{PLURAL:$1|araw|mga araw}})',
'securelogin-stick-https'    => 'Manatiling konektado sa HTTPS matapos lumagda',
'yourdomainname'             => 'Dominyo mo:',
'externaldberror'            => 'Maaaring may kamalian sa pagpapatotoo ng kalipunan ng mga dato o kaya hindi ka pinahintulutang isapanahon ng iyong panlabas na kuwenta o patnugutan.',
'login'                      => 'Lumagda',
'nav-login-createaccount'    => 'Lumagda / lumikha ng kuwenta',
'loginprompt'                => "Dapat na pinapahintulutan mo ang mga kuki (''cookie'') upang makalagda sa {{SITENAME}}.",
'userlogin'                  => 'Lumagda / lumikha ng kuwenta',
'userloginnocreate'          => 'Lumagda',
'logout'                     => 'Umalis sa pagkakalagda',
'userlogout'                 => 'Umalis sa pagkakalagda',
'notloggedin'                => 'Hindi nakalagda',
'nologin'                    => 'Wala ka pang kuwenta? $1.',
'nologinlink'                => 'Lumikha ng kuwenta',
'createaccount'              => 'Lumikha ng kuwenta',
'gotaccount'                 => 'May kuwenta ka na ba? $1.',
'gotaccountlink'             => 'Lumagda',
'createaccountmail'          => 'sa pamamagitan ng e-liham',
'createaccountreason'        => 'Dahilan:',
'badretype'                  => 'Hindi magkatugma ang ipinasok mong mga hudyat.',
'userexists'                 => 'May gumagamit na ng ganyang pangalang pantagagamit.
Pumili lamang ng iba pang pangalan.',
'loginerror'                 => 'Kamalian sa paglagda',
'createaccounterror'         => 'Hindi mailikha ang kuwenta: $1',
'nocookiesnew'               => "Nilikha na ang kuwentang pantagagamit, ngunit hindi ka nakalagda.
Gumagamit ang {{SITENAME}} ng mga kuki (''cookies'') para mailagda ang mga tagagamit.
Hindi mo pinagagana ang mga kuki.
Paki-andar mo po ang mga ito, pagkatapos ay lumagda na gamit ang bago mong pangalan ng tagagamit at hudyat.",
'nocookieslogin'             => "Gumagamit ang {{SITENAME}} ng mga kuki (''cookies'') para mailagda ang mga tagagamit.
Hindi mo pinagagana ang mga kuki.
Paki-andar mo ang mga ito at sumubok uli.",
'noname'                     => 'Hindi mo tinukoy ang isang tanggap na pangalan ng tagagamit.',
'loginsuccesstitle'          => 'Matagumpay ang paglagda',
'loginsuccess'               => "'''Nakalagda ka na sa {{SITENAME}} bilang si \"\$1\".'''",
'nosuchuser'                 => 'Walang tagagamit na may pangalang "$1".
Maselan sa pagtitipa ang mga pangalan ng tagagamit.
Suriin ang iyong pagbabaybay, o [[Special:UserLogin/signup|lumikha ng bagong kuwenta]].',
'nosuchusershort'            => 'Walang tagagamit na may pangalang "<nowiki>$1</nowiki>".
Pakitingnan ang iyong pagbabaybay.',
'nouserspecified'            => 'Kailangang tukuyin mo ang isang pangalang pantagagamit.',
'login-userblocked'          => 'Hinarang ang tagagamit na ito.  Hindi pinahihintulutan ang paglalagda.',
'wrongpassword'              => 'Mali ang ipinasok na hudyat.
Pakisubok muli.',
'wrongpasswordempty'         => 'Walang laman ang ipinasok na hudyat.
Pakisubok muli.',
'passwordtooshort'           => 'Ang mga hudyat ay dapat mayroong {{PLURAL:$1|1 panitik|$1 panitik}} (karakter).',
'password-name-match'        => 'Dapat magkaiba ang hudyat mo sa bansag mo.',
'mailmypassword'             => 'Ipadala sa pamamagitan ng e-liham ang bagong hudyat',
'passwordremindertitle'      => 'Bagong pansamantalang hudyat para sa {{SITENAME}}',
'passwordremindertext'       => 'Mayroong (na maaaring ikaw, mula sa adres ng IP na $1) humiling ng isang bagong
hudyat para sa {{SITENAME}} ($4). Isang pansamantalang hudyat ang nilikha
para sa tagagamit na "$2" at itinakda sa "$3".  Kung ito ang iyong pakay,
kailangan mo na ngayong lumagda/tumala at pumili ng isang bagong hudyat.
Mawawala/magtatapos ang bisa ang pansamantala mong hudyat sa loob ng {{PLURAL:$5|isang araw|$5 araw}}.

Kung ibang tao ang humiling nito, o kung naalala mo na ang iyong hudyat,
at hindi mo na ibig pang baguhin ito, maaari mong huwag pansinin ang mensaheng ito at
magpatuloy sa paggamit ng iyong lumang hudyat.',
'noemail'                    => 'Walang nakatalang adres ng e-liham para sa tagagamit na "$1".',
'noemailcreate'              => 'Kailangan mong magbigay ng may-bisang adres ng e-liham',
'passwordsent'               => 'Isang bagong hudyat ang ipinadala sa adres ng e-liham na nakatala para kay "$1".
Lumagda/Tumala lang po muli pagkaraan mong matanggap ito.',
'blocked-mailpassword'       => 'Hinarangan sa paggawa ng mga pagbabago ang iyong adres ng IP, at kaya hindi rin pinapahintulutang gumamit ng tungkuling makabawi ng hudyat para maiwasan ang pangaabuso.',
'eauthentsent'               => 'Nagpadala ng isang e-liham na pangkompirmasyon doon sa iniharap na adres ng e-liham.
Bago magpadala ng iba pang e-liham sa kuwenta, kailangan mong sundin ang mga tagubiling nasa loob ng e-liham, para mapatunayang iyo talaga ang kuwenta.',
'throttled-mailpassword'     => 'Nagpadala na ng isang paalalang panghudyat, nitong huling {{PLURAL:$1|oras|$1 oras}}.
Para maiwasin ang pangaabuso, isang paalalang panghudyat lang ang ipapadala bawat {{PLURAL:$1|oras|$1 oras}}.',
'mailerror'                  => 'Kamalian sa pagpapadala ng liham: $1',
'acct_creation_throttle_hit' => 'Ang mga panauhin sa wiking ito na gumagamit ng adres ng IP mo ay nakalikha na ng {{PLURAL:$1|1 kuwenta|$1 kuwenta}} sa loob ng huling araw, na siyang pinakamataas na pinapahintulutan sa loob ng sakop ng panahong ito.
Bilang kinalabasan, ang mga panauhing gumagamit ng ganitong adres ng IP ay hindi na muna makakalikha ng anumang karagdagang kuwenta sa ngayon.',
'emailauthenticated'         => 'Napatunayan na ang iyong adres ng e-liham noong $2 noong $3.',
'emailnotauthenticated'      => 'Hindi pa napapatunayan ang iyong adres ng e-liham.
Walang e-liham na ipapadala para sa anumang sumusunod na tampok na kasangkapang-katangian.',
'noemailprefs'               => 'Tumukoy ng isang adres ng e-liham sa loob ng mga nais mo upang gumana ang mga kasangkapang-katangiang ito.',
'emailconfirmlink'           => 'Pakikompirma ang iyong adres ng e-liham.',
'invalidemailaddress'        => 'Hindi matatanggap ang adres ng e-liham na ito dahil tila mayroon itong maling anyo.
Pakipasok ang isang may mahusay na anyong adres o paki-iwang walang laman na lang ang lagayan.',
'accountcreated'             => 'Nilikha na ang kuwenta',
'accountcreatedtext'         => 'Nilikha na ang kuwentang tagagamit para kay $1.',
'createaccount-title'        => 'Paglikha ng kuwenta para sa {{SITENAME}}',
'createaccount-text'         => 'May lumikha ng kuwenta para sa iyong adres ng e-liham sa {{SITENAME}} ($4) na pinangalanang "$2", na may hudyat na "$3".
Dapat kang tumala at baguhin ang hudyat mo ngayon.

Maaari mong huwag pansinin ang mensaheng ito, kung mali ang paglikha ng kuwentang ito.',
'usernamehasherror'          => 'Hindi maaaring maglaman ng mga panitik na pantadtad ang pangalan ng tagagamit',
'login-throttled'            => 'Masyadong marami ang ginawa mong kamakailan lang na mga pagsubok sa paglagdang papasok.
Maghintay po muna bago subukan uli.',
'loginlanguagelabel'         => 'Wika: $1',
'suspicious-userlogout'      => "Tinanggihan ang inyong kahilingang umalis sa pagkalagda dahil tila ito ay ipinadala ng sirang pambasa-basa o apoderadong pambaon (''caching proxy'')",

# JavaScript password checks
'password-strength'            => 'Tinatayang lakas ng hudyat: $1',
'password-strength-bad'        => 'MASAMA',
'password-strength-mediocre'   => 'pangkaraniwan',
'password-strength-acceptable' => 'katanggap-tanggap',
'password-strength-good'       => 'mabuti',
'password-retype'              => 'Muling makinilyahin ang hudyat dito',
'password-retype-mismatch'     => 'Hindi nagtutugma ang mga hudyat',

# Password reset dialog
'resetpass'                 => 'Palitan ang hudyat',
'resetpass_announce'        => 'Lumagda ka sa pamamagitan ng isang pansamantalang ini-e-liham na kodigo.
Para tapusin ang paglagda, dapat kang magtakda ng isang bagong hudyat dito:',
'resetpass_text'            => '<!-- Magdagdag ng teksto rito -->',
'resetpass_header'          => 'Baguhin ang hudyat ng kuwenta',
'oldpassword'               => 'Lumang hudyat:',
'newpassword'               => 'Bagong hudyat:',
'retypenew'                 => 'Ipasok muli ang bagong hudyat:',
'resetpass_submit'          => 'Itakda ang hudyat at lumagda',
'resetpass_success'         => 'Matagumpay na nabago ang iyong hudyat!  Inilalagda ka na ngayon...',
'resetpass_forbidden'       => 'Hindi mababago ang mga hudyat',
'resetpass-no-info'         => 'Nakalagda ka dapat para tuwirang mapuntahan ang pahina ito.',
'resetpass-submit-loggedin' => 'Baguhin ang hudyat',
'resetpass-submit-cancel'   => 'Kanselahin',
'resetpass-wrong-oldpass'   => 'Hindi tanggap na pansamantala o pangkasalukuyang hudyat.
Maaaring matagumpay mo nang nabago ang iyong hudyat o nakahiling na ng isang bagong pansamantalang hudyat.',
'resetpass-temp-password'   => 'Pansamantalang hudyat:',

# Edit page toolbar
'bold_sample'     => 'Makapal na panitik',
'bold_tip'        => 'Makapal na panitik',
'italic_sample'   => 'Nakahilig na panitik',
'italic_tip'      => 'Nakahilig na panitik',
'link_sample'     => 'Pamagat ng kawing',
'link_tip'        => 'Panloob na kawing',
'extlink_sample'  => 'http://www.example.com na kawing ng pamagat',
'extlink_tip'     => 'Panlabas na kawing (tandaan ang unlaping http://)',
'headline_sample' => 'Paulong teksto',
'headline_tip'    => 'Paulong antas 2',
'math_sample'     => 'Isingit ang pormula dito',
'math_tip'        => 'Pormulang pangmatematika (LaTeX)',
'nowiki_sample'   => 'Isingit ang hindi nakapormat na teksto dito',
'nowiki_tip'      => 'Balewalain ang pormat na pangwiki',
'image_sample'    => 'Halimbawa.jpg',
'image_tip'       => 'Nakabaong talaksan',
'media_sample'    => 'Halimbawa.ogg',
'media_tip'       => 'Kawing sa talaksan',
'sig_tip'         => 'Lagda mo na may tatak ng oras',
'hr_tip'          => 'Pahalagang na guhit (gamitin nang madalang)',

# Edit pages
'summary'                          => 'Buod:',
'subject'                          => 'Paksa/paulo:',
'minoredit'                        => 'Ito ay isang munting pagbabago',
'watchthis'                        => 'Bantayan ang pahinang ito',
'savearticle'                      => 'Itala ang pahina',
'preview'                          => 'Paunang tingin',
'showpreview'                      => 'Ipakita ang paunang tingin',
'showlivepreview'                  => 'Buhay na paunang tingin',
'showdiff'                         => 'Ipakita ang mga pagbabago',
'anoneditwarning'                  => "'''Babala:''' Hindi ka nakalagda.
Matatala ang adres ng IP mo sa kasaysayan ng pagbabago ng pahinang ito.",
'anonpreviewwarning'               => "''Hindi ka nakalagda.  Itatala sa inyong pagtatala ang inyong adres ng IP sa kasaysayan ng pagbabago ng pahinang ito.''",
'missingsummary'                   => "'''Paalala:''' Hindi ka nagbigay ng buod ng pagbabago.
Kapag pinindot mo uli ang Sagip, masasagip ang pagbabago mo na wala nito.",
'missingcommenttext'               => 'Magbigay ng isang kumento/puna sa ibaba.',
'missingcommentheader'             => "'''Paalala:''' Hindi ka nagbigay ng isang paksa/paulo para sa punang ito.
Kapag pinindot mo uli ang \"{{int:savearticle}}\", masasagip ang pagbabago mo na wala nito.",
'summary-preview'                  => 'Paunang tingin sa buod:',
'subject-preview'                  => 'Paunang tingin sa paksa/paulo:',
'blockedtitle'                     => 'Hinarang ang tagagamit',
'blockedtext'                      => "'''Hinarang/hinadlangan ang iyong pangalan ng tagagamit o adres ng IP.'''

Ginawa ang pagharang/paghadlang ni $1. Ito ang ibinigay na dahilan: ''$2''.

* Simula ng pagharang/paghadlang: $8
* Katapusan ng pagharang/paghadlang: $6
* Ang hinarang/hinadlangan ay si: $7

Maaari kang makipag-ugnayan kay $1 o sa iba pang [[{{MediaWiki:Grouppage-sysop}}|tagapangasiwa]] upang pagusapan ang pagharang/paghadlang na ito.
Hindi mo magagamit ang kasangkapang-katangiang 'magpadala ng e-liham sa tagagamit' hangga't hindi tinutukoy ang isang tanggap na adres ng e-liham sa iyong [[Special:Preferences|mga kagustuhan]] at hindi ka pa hinaharangan/hinahadlangan sa paggamit nito.
Ang pangkasalukuyang adres ng IP mo ay $3, at ang ID ay #$5.
Pakisama ang lahat ng mga detalye sa anumang mga pagtatanong na ginagawa/gagawin mo.",
'autoblockedtext'                  => 'Kusang hinadlangan/hinarang ang adres ng IP mo dahil ginamit ito ng ibang tagagamit, na hinadlangan/hinarang ni $1.
Ang ibinigay na dahilan ay:

:\'\'$2\'\'

* Simula ng pagharang: $8
* Katapusan ng pagharang: $6
* Ang hinadlangang ay si: $7

Maaari kang makipagugnayan kay $1 o sa isa sa iba pang [[{{MediaWiki:Grouppage-sysop}}|mga tagapangasiwa]] para pagusapan ang paghadlang/pagharang.

Pakitandaang hindi mo maaaring gamitin ang kasangkapang-katangiang "padalhan ng e-liham ang tagagamit na ito" maliban na lamang kung mayroon kang nakatalang tanggap na adres ng e-liham sa iyong [[Special:Preferences|mga kagustuhan]] at hindi ka hinadlangan sa paggamit nito.

Ang pangkasalukuyang adres mo ng IP ay $3, at ang ID ng pagharang ay #$5.
Pakisama ang lahat ng mga detalyeng nasa itaas sa anumang pagtatanong na gagawin mo.',
'blockednoreason'                  => 'walang binigay na dahilan',
'blockedoriginalsource'            => "Ang pinagmulan ng '''$1''' ay pinapakita sa ibaba:",
'blockededitsource'                => "Ang teksto ng '''mga pagbabago mo''' sa '''$1''' ay ipinapakita sa ibaba:",
'whitelistedittitle'               => 'Paglagda kailangan para makapagbago',
'whitelistedittext'                => 'Kailangan mong $1 para makapagbago ng mga pahina.',
'confirmedittext'                  => 'Kailangang kumpirmahin mo muna ang adres ng iyong e-liham bago makapagbago ng mga pahina.
Pakihanda at patotohanan ang adres ng e-liham sa pamamagitan ng iyong [[Special:Preferences|kagustuhan ng tagagamit]].',
'nosuchsectiontitle'               => 'Hindi mahanap ang seksyon',
'nosuchsectiontext'                => 'Sinubukan mong baguhin ang isang seksyong hindi umiiral.
Maaaring inilipat o ibinura ito habang tinitingnan mo ang pahina.',
'loginreqtitle'                    => 'Paglagda/Pagtala Kailangan',
'loginreqlink'                     => 'lumagda/tumala',
'loginreqpagetext'                 => 'Kailangan mong $1 para matanaw ang ibang mga pahina.',
'accmailtitle'                     => 'Ipinadala na ang hudyat.',
'accmailtext'                      => "Ipinadala na sa $2 ang isang hudyat na nilikha ng pagkakataon para kay [[User talk:$1|$1]].

Ang hudyat para sa bagong akawnt na ito ay mababago sa pahina ng ''[[Special:ChangePassword|mga nais ko]]'' kapag lumagdang papasok.",
'newarticle'                       => '(Bago)',
'newarticletext'                   => "Sinundan mo ang isang kawing para sa isang pahinang hindi pa umiiral.
Para likhain ang pahina, magsimulang magmakinilya sa loob ng kahong nasa ibaba (tingnan ang [[{{MediaWiki:Helppage}}|pahina ng tulong]] para sa mas maraming kabatiran).
Kung napunta ka rito dahil sa pagkakamali, pakipindot ang pinduntang '''balik''' ('''''back''''') ng iyong pantingin-tingin (''browser'').",
'anontalkpagetext'                 => "Usapan ito para sa isang hindi nakikilalang tagagamit na hindi pa lumilikha ng kuwenta/akawnt, o kaya hindi ito ginagamit.
Kaya't kinailangan naming gamitin ang may bilang na adres ng IP para makilala siya.
Maaaring pagsaluhan ng ilang mga tagagamit ang ganyang adres ng IP.
Kung isa kang hindi nagpapakilalang tagagamit at nakadaramang may mga walang saysay na puna/kumentong patungkol sa iyo, [[Special:UserLogin/signup|pakilikha ng isang kuwenta]] o [[Special:UserLogin|lumagda]] para maiwasan ang kalituhan o mapagkamalan ka bilang ibang hindi nakikilalang mga tagagamit sa hinaharap.",
'noarticletext'                    => 'Kasalukuyang walang teksto sa loob ng pahinang ito.
Maaari mong [[Special:Search/{{PAGENAME}}|hanapin ang pamagat ng pahinang ito]] sa loob iba pang mga pahina,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} maghanap sa kaugnay na mga talaan],
o [{{fullurl:{{FULLPAGENAME}}|action=edit}} baguhin ang pahinang ito]</span>.',
'noarticletext-nopermission'       => 'Kasalukuyang walang teksto sa pahinang ito.
Maaari mong [[Special:Search/{{PAGENAME}}|hanapin ang pamagat ng pahinang ito]] sa ibang mga pahina,
o <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} maghanap sa kaugnay na mga talaan]</span>.',
'userpage-userdoesnotexist'        => 'Hindi nakatala ang kuwenta ng tagagamit na "$1".
Pakisuri kung ibig mong likhain/baguhin ang pahinang ito.',
'userpage-userdoesnotexist-view'   => 'Hindi nakatala ang kuwenta ng tagagamit na "$1".',
'blocked-notice-logextract'        => 'Kasalukuyang hinarang ang tagagamit na ito.
Ang pinakahuling entrada sa talaan  ng pagharang ay ibinigay sa baba para sa inyong pagsasangguni:',
'clearyourcache'                   => "'''Tandaan: Pagkatapos magtala, dapat linisin mo ang baunan ng iyong pambasa-basa upang makita ang mga pagbabago.'''
'''Mozilla / Firefox / Safari:''' panatilihing nakapindot ang ''Shift'' habang kiniklik ang ''Reload'', o pindutin ang ''Ctrl-F5'' o ''Ctrl-R'' (''Command-R'' sa isang Macintosh);
'''Konqueror:''' i-klik ang ''Reload'' o pindutin ang ''F5'';
'''Opera:''' linisin ang baunan sa ''Tools → Preferences'';
'''Internet Explorer:''' panatilihing nakapindot ang ''Ctrl'' habang kiniklik ang ''Refresh'', o pindutin ang ''Ctrl-F5''.",
'usercssyoucanpreview'             => "'''Balato:''' Gamitin ang pindutang \"{{int:showpreview}}\" upang masubok ang bago mong CSS bago sagipin.",
'userjsyoucanpreview'              => "'''Balato:''' Gamitin ang pindutang \"{{int:showpreview}}\" upang masubok ang bago mong JavaScript bago sagipin.",
'usercsspreview'                   => "'''Tandaan mong paunang tingin pa lamang ito ng iyong CSS na pantagagamit.'''
'''Hindi pa ito nasasagip!'''",
'userjspreview'                    => "'''Tandaang pagsubok/paunang tingin mo pa lang ito ng iyong JavaScript.'''
'''Hindi pa ito nasasagip!'''",
'userinvalidcssjstitle'            => "'''Babala:''' Walang pabalat na \"\$1\".
Tandaang gumagamit ang pinasadyang mga pahinang .css at .js ng mga pamagat na may maliliit na mga titik, halimbawa na ang {{ns:user}}:Foo/vector.css na taliwas sa {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(Naisapanahon na)',
'note'                             => "'''Paunawa:'''",
'previewnote'                      => "'''Isang lamang itong paunang tingin;
hindi pa nasasagip ang mga pagbabago!'''",
'previewconflict'                  => 'Ipinamamalas ng paunang tinging ito ang teksto sa loob ng pangitaas na pook-patnugutan ng teksto ayon sa lilitaw na anyo nito kapag pinili mo ang pagsagip.',
'session_fail_preview'             => "'''Paumanhin! Hindi namin maproseso ang iyong pagbabago hinggil sa pagkawala ng sesyon ng datos.
Paki ulit muli. Kung hindi ito gumana, subukang umalis sa pagkalagda at bumalik muli.'''",
'session_fail_preview_html'        => "'''Paumanhin! Hindi namin maproseso ang iyong pagbabago hinggil sa pagkawala ng datos ng sesyon.'''

''Dahil naka-andar ang hilaw na HTML sa {{SITENAME}}, nakatago ang paunang tingin bilang pag-iingat sa mga paglusob ng JavaScript.''

'''Kung lehitimong pagbabago ito, paki-ulit muli.'''
Kung hindi pa rin ito gumagana, subukang [[Special:UserLogout|umalis sa pagkakalagda]] at lumagda muli.",
'token_suffix_mismatch'            => "'''Hindi tinanggap ang iyong pagbabago dahil sinira ng kliyente ang mga karakter na bantas sa ''token'' ng mamatnugot.
Tinanggihan ang pagbabago upang maiwasan ang korapsyon ng teksto ng artikulo.
Kadalasang nangyayari ito kapag gumagamit ka ng masurot na serbisyo ng hindi-nakikilalang apoderadong (''anonymous proxy'') nasa web.",
'editing'                          => 'Binabago ang $1',
'editingsection'                   => 'Binabago ang $1 (bahagi)',
'editingcomment'                   => 'Binabago ang $1 (bagong seksyon)',
'editconflict'                     => 'Alitan sa pagbabago: $1',
'explainconflict'                  => "Mayroon nagbago ng pahinang ito simula nang baguhin mo ito.
Naglalaman ang mga nasa taas na teksto ng mga pahinang teksto at kasalukuyang mayroon ito.
Ipinapakita sa ibabang teksto ang mga binago mo.
Kailangan mong pagsamahin ang mga binago mo sa kasalukuyang teksto.
Maitatala '''lamang''' ang nasa taas na teksto kapag pinindot ang \"{{int:savearticle}}\".",
'yourtext'                         => 'Teksto mo',
'storedversion'                    => 'Nakatagong rebisyon',
'nonunicodebrowser'                => "'''Babala: Hindi sumusunod sa unicode ang browser mo.'''
May ginawang solusyon para pahintulutan kang magbago ng mga pahina nang ligtas: ang mga 'di-ASCII na karakter ay magpapakita sa kahon ng pagbabago bilang mga kodigong heksadesimal.",
'editingold'                       => "'''Babala: Binabago mo ang lumang bersyon ng pahinang ito.
Kapag itinala mo ito, mawawala ang anumang pagbabago mula sa bersyon na ito.'''",
'yourdiff'                         => 'Mga pagkakaiba',
'copyrightwarning'                 => "Pakitandaan na lahat ng mga ambag sa {{SITENAME}} ay itinuturing na inilibas sa ilalim ng $2 (tingnan ang $1 para sa mga detalye).
Kung hindi mo nais na labis-labis na baguhin ang iyong isinulat at sadyaing muling ipamahagi, huwag mo na lamang itong ipasa rito.<br />
Nangangako ka rin sa amin na ikaw mismo ang sumulat nito, sumipi/kumopya nito mula sa isang pinagmulang nasa dominyo na ng publIko o katulad.
'''HUWAG MAGPASA NG AKDANG NAKAKARAPATANG-ARI (NAKAKOPIRAYT) NA HINDI MUNA HUMIHINGI NG PAHINTULOT!'''",
'copyrightwarning2'                => "Pakitandaan lamang na lahat ng mga ambag sa {{SITENAME}} ay maaaring baguhin o tanggalin ng ibang mga tagapaglathala/tagapagambag.
Kung ayaw mong mabago nang labis-labis ang mga isinulat mo, mas mabuting huwag mo na lamang ipasa iyan dito.<br />
Nangangako ka rin sa amin na ikaw ang mismong sumulat nito, o sinipi/kinopya mo ito mula sa isang pinagmulang nasa dominyo na ng publiko o katulad (tingnan ang $1 para sa mga detalye).
'''HUWAG MAGTALA NG AKDANG NAKAKARAPATANG-ARI (NAKAKOPIRAYT) NA HINDI MUNA HUMIHINGI NG PAHINTULOT!'''",
'longpageerror'                    => "'''KAMALIAN: May habang $1 ''kilobyte'' ang ipinasa mong teksto, na mas mahaba kaysa $2 ''kilobyte'' na siyang pinakamataas na nakatakdang halaga.
Hindi ito masasagip.'''",
'readonlywarning'                  => "'''BABALA: Ikinandado ang kalipunan ng dato para sa gawaing pampagpapanatili, kaya't hindi mo pa masasagip ang mga pagbabagong ginawa mo ngayon.
Maaaring ibigin mong gupitin at idikit ang teksto patungo sa isang talaksang pangteksto at sagipin ito mamaya.'''

Nagbigay ng ganitong paliwanag ang tagapangasiwang nagkandado nito: $1",
'protectedpagewarning'             => "'''Babala: Ikinandado ang pahinang ito upang mga tagagamit lamang na may karapatan ng tagapangasiwa ang makakapagbago nito.'''
Ang pinakahuling entrada sa talaan ay ibinigay sa baba para sa iyong pagsasangguni:",
'semiprotectedpagewarning'         => "'''Paunawa: Ikinandado ang pahinang ito upang mga nakatalang tagagamit lamang ang makakapagbago nito.'''
Ang pinakahuling entrada sa talaan ay ibinigay sa baba para sa iyong pagsasangguni:",
'cascadeprotectedwarning'          => "'''Babala:''' Ikinandado ang pahinang ito upang tanging mga tagagamit na may mga karapatang pang-''sysop'' lamang ang makapagbago nito, dahil kabilang ito sa sumusunod na mga {{PLURAL:$1|pahinang|mga pahinang}} may baita-baitang na panananggalang:",
'titleprotectedwarning'            => "'''Babala:  Ikinandado ang pahinang ito upang [[Special:ListGroupRights|partikular na mga karapatan]] ang kakailanganin upang mailikha ito.'''
Ang pinakahuling entrada sa talaan ay ibinigay sa baba para sa inyong pagsasangguni:",
'templatesused'                    => '{{PLURAL:$1|Suleras|Mga suleras}} na ginagamit sa pahinang ito:',
'templatesusedpreview'             => '{{PLURAL:$1|Suleras|Mga suleras}} na ginagamit sa paunang-tinging ito:',
'templatesusedsection'             => '{{PLURAL:$1|Suleras|Mga suleras}} na ginamit sa seksyong ito:',
'template-protected'               => '(nakasanggalang)',
'template-semiprotected'           => '(bahagyang nakasanggalang)',
'hiddencategories'                 => 'Ang pahinang ito ay kasapi sa {{PLURAL:$1|1 nakatagong kategorya|$1 nakatagong kategorya}}:',
'edittools'                        => '<!-- Ang teksto rito ay ipapakita sa ilalim ng mga pormularyo ng pagbabago at pagkarga. -->',
'nocreatetitle'                    => 'May hangganan ang paglikha ng pahina',
'nocreatetext'                     => 'Naglagay ng hangganan (restriksyon/limitasyon) ang {{SITENAME}} sa kakayahang makalikha ng bagong mga pahina.
Maaari kang bumalik at magbago ng isang umiiral na pahina, o kaya [[Special:UserLogin|lumagda o lumikha ng kuwenta/akawnt]].',
'nocreate-loggedin'                => 'Walang kang pahintulot para lumikha ng bagong mga pahina.',
'sectioneditnotsupported-title'    => 'Hindi sinusuportahan ang pagpapatnugot ng seksyon',
'sectioneditnotsupported-text'     => 'Hindi sinusuportahan ang pagpapatnugot ng seksyon sa pahinang ito.',
'permissionserrors'                => 'Mga kamalian sa mga pahintulot',
'permissionserrorstext'            => 'Wala kang pahintulot na gawin iyan, dahil sa sumusunod na {{PLURAL:$1|dahilan|mga dahilan}}:',
'permissionserrorstext-withaction' => 'Wala kang pahintulot na $2, dahil sa sumusunod na {{PLURAL:$1|dahilan|mga dahilan}}:',
'recreate-moveddeleted-warn'       => "'''Babala: Muli mong inililikha ang isang pahinang binura na dati.'''

Dapat mong isaalang-alang kung nararapat bang ipagpatuloy ang pagbago sa pahinang ito.
Ang tala ng pagbubura at paglilipat para sa pahinang ito ay ibinigay dito para sa inyong kaginhawaan:",
'moveddeleted-notice'              => 'Ibinura na ang pahinang ito.
Ang tala ng pagbubura at paglilipat para sa pahinang ito ibinigay sa baba para sa inyong pagsasangguni.',
'log-fulllog'                      => 'Tingnan ang buong tala',
'edit-hook-aborted'                => 'Pinigil ng sungkit ang pagbabago.
Walang ibinigay na paliwanag.',
'edit-gone-missing'                => 'Hindi maisapanahon ang pahina.
Tila binura na ito.',
'edit-conflict'                    => 'Alitan sa pagbabago.',
'edit-no-change'                   => 'Binalewala ang pagbabago mo, dahil walang pagbabagong ginawa sa teksto.',
'edit-already-exists'              => 'Hindi makalikha ng isang bagong pahina.
Umiiral na ito.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Babala: Naglalaman ang pahinang ito ng napakaraming mamahaling mga tawag na pantungkulin.

Dapat na mayroon itong mas mababa sa $2 {{PLURAL:$2|tawag|mga tawag}}, mayroon {{PLURAL:$1|ngayong $1 isang tawag|ngayong $1 mga tawag}}.',
'expensive-parserfunction-category'       => "Mga pahinang may napakaraming mga mamahaling tawag na pantungkulin ng banghay (''parser'')",
'post-expand-template-inclusion-warning'  => 'Babala: Napakalaki ng sukat ng saklaw ng suleras.
Hindi isasama ang ilang mga suleras.',
'post-expand-template-inclusion-category' => 'Mga pahina kung saan lumabis ang sukat ng saklaw ng suleras',
'post-expand-template-argument-warning'   => 'Babala: Naglalamang ang pahinang ito ng kahit isang pagaalitan ng suleras na napakalaki ng sukat ng paglawak.  Tinanggal ang mga alitang ito.',
'post-expand-template-argument-category'  => 'Mga pahinang naglalaman ng mga tinanggal na mga alitan ng suleras',
'parser-template-loop-warning'            => 'Nadiskubreng silo ng suleras: [[$1]]',
'parser-template-recursion-depth-warning' => 'Lumabis na sa nakatakdang lalim ng rekursyon (pormula) ng suleras ($1)',
'language-converter-depth-warning'        => 'Lumampas sa ($1) ang hangganan ng lalim ng pampalit ng wika',

# "Undo" feature
'undo-success' => 'Matatanggal ang pagbabago.
Pakitingnan ang paghahambing sa ibaba para masiyasat kung ito ang ibig mong gawin, at pagkatapos sagipin ang mga pagbabago sa ibaba para matapos ang pagtatanggal ng pagbabago.',
'undo-failure' => 'Hindi matanggal ang pagbabago dahil sa magkakasalungat na panggitnang mga pagbabago.',
'undo-norev'   => 'Hindi matanggal ang pagbabago dahil hindi ito umiiral o nabura na.',
'undo-summary' => 'Tanggalin ang pagbabagong $1 ni [[Special:Contributions/$2|$2]] ([[User talk:$2|Usapan]])',

# Account creation failure
'cantcreateaccounttitle' => 'Hindi malikha ang kuwenta',
'cantcreateaccount-text' => "Hinarang ni [[User:$3|$3]] ang paglikha ng kuwenta mula sa adres ng IP ('''$1''') na ito.

Ang dahilang ibinigay ni $3 ay ''$2''",

# History pages
'viewpagelogs'           => 'Tingnan ang mga pagtatala para sa pahinang ito',
'nohistory'              => 'Walang kasaysayan ng pagbabago para sa pahinang ito.',
'currentrev'             => 'Pangkasalukuyang pagbabago',
'currentrev-asof'        => 'Pangkasalukuyang pagbabago mula noong $1',
'revisionasof'           => 'Pagbabago mula noong $1',
'revision-info'          => 'Pagbabago mula noong $1 ni $2',
'previousrevision'       => '← Lumang pagbabago',
'nextrevision'           => 'Bagong pagbabago →',
'currentrevisionlink'    => 'Pangkasalukuyang pagbabago',
'cur'                    => 'kasalukuyan',
'next'                   => 'susunod',
'last'                   => 'huli',
'page_first'             => 'una',
'page_last'              => 'huli',
'histlegend'             => "Ipaghambing ang mga napili: markahan ang mga radyong buton (''radio button'') ng mga bersyong ihahambing at pindutin ang ''enter'' o ang buton sa ilalim.<br />
Mga daglat: (kas) = pagkakaiba sa kasalukuyang bersyon,
(huli) = pagkakaiba sa naunang bersyon, m = maliit na pagbabago.",
'history-fieldset-title' => 'Tumingin-tingin sa kasaysayan',
'history-show-deleted'   => 'Ibinura lamang',
'histfirst'              => 'Pinakasinauna',
'histlast'               => 'Pinakakamakailan',
'historysize'            => "({{PLURAL:$1|1 ''byte''|$1 ''byte''}})",
'historyempty'           => '(walang laman)',

# Revision feed
'history-feed-title'          => 'Kasaysayan ng pagbabago',
'history-feed-description'    => 'Kasaysayan ng pagbabago para sa pahinang ito dito sa wiki',
'history-feed-item-nocomment' => '$1 sa $2',
'history-feed-empty'          => 'Wala ang hiniling na pahina.
Nabura ito mula sa wiki, o napalitan ng pangalan.
Subukang [[Special:Search|hanapin sa wiki]] para sa mga kaugnay na mga bagong pahina.',

# Revision deletion
'rev-deleted-comment'         => '(tinanggal ang kumento/puna)',
'rev-deleted-user'            => '(tinanggal ang pangalan ng tagagamit)',
'rev-deleted-event'           => '(tinanggal ang galaw sa talaan)',
'rev-deleted-user-contribs'   => '[itinanggal ang bansag o adres ng IP - itinago ang pagbabago mula sa mga ambag]',
'rev-deleted-text-permission' => "Itong pagbabago ng pahina ay '''ibinura'''.
Maaaring hanapin ang mga detalye sa [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} tala ng pagbubura].",
'rev-deleted-text-unhide'     => "Itong pagbabago ng pahina ay '''ibinura'''.
Maaaring hanapin ang mga detalye sa [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} tala ng pagbubura].
Bilang isang tagapangasiwa, maaari mo pang [$1 tingnan ang pagbabagong ito] kung nais mong tumuloy.",
'rev-suppressed-text-unhide'  => "Itong pagbabago ng pahina ay '''isinugpo'''.
Maaaring hanapin ang mga detalye sa [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} tala ng pagsusugpo].
Bilang isang tagapangasiwa, maaari mo pang [$1 tingnan ang pagbabagong ito] kung nais mong tumuloy.",
'rev-deleted-text-view'       => "Itong pagbabago ng pahina ay '''ibinura'''.
Bilang isang tagapangasiwa, maaari mo itong makita; maaaring hanapin ang mga detalye sa [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} tala ng pagbubura].",
'rev-suppressed-text-view'    => "Itong pagbabago ng pahina ay '''isinugpo'''.
Bilang isang tagapangasiwa, maaari mo itong makita; maaaring hanapin ang mga detalye sa [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} tala ng pagsusugpo].",
'rev-deleted-no-diff'         => "Hindi mo maaaring makita ang pagkakaibang ito dahil '''binura na''' ang isa sa mga pagbabago.  Matatagpuan ang mga detalye mula sa loob ng [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} talaan ng pagbura].",
'rev-suppressed-no-diff'      => "Hindi mo maaaring tingnan ang pagkakaibang ito dahil '''ibinura''' ang isa sa mga pagbabago.",
'rev-deleted-unhide-diff'     => "'''Binura''' ang isa sa mga pagbabago ng pagkakaibang ito.
Maaaring matagpuan ang mga detalye sa loob ng [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} talaan ng pagbura].
Bilang isang tagapangasiwa, maaari mo pa ring [$1 tingnan ang pagkakaibang ito] kung nais mong magpatuloy.",
'rev-suppressed-unhide-diff'  => "Isa sa mga pagbabago ng pagkakaibang ito ay '''isinugpo'''.
Maaaring hanapin ang mga detalye sa [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} tala ng pagsusugpo].
Bilang isang tagapangasiwa, maaari mo pang [$1 tingnan ang pagbabagong ito] kung nais mong tumuloy.",
'rev-deleted-diff-view'       => "Isa sa mga pagbabago ng pagkakaibang ito ay '''binura'''.
Bilang isang tagapangasiwa, maaari mo itong makita; maaaring hanapin ang mga detalye sa [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} tala ng pagbubura].",
'rev-suppressed-diff-view'    => "Isa sa mga pagbabago ng pagkakaibang ito ay '''isinugpo'''.
Bilang isang tagapangasiwa, maaari mo itong makita; maaaring hanapin ang mga detalye sa [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} tala ng pagsusugpo].",
'rev-delundel'                => 'ipakita/itago',
'rev-showdeleted'             => 'ipakita',
'revisiondelete'              => 'Burahin/ibalik ang mga pagbabago',
'revdelete-nooldid-title'     => 'Hindi tanggap na puntiryang pagbabago',
'revdelete-nooldid-text'      => 'Hindi ka nagbigay ng pupuntahang pagbabago o mga pagbabago para magampanan ang paraan na ito.',
'revdelete-nologtype-title'   => 'Walang uri ng tala/pagtatalang ibinigay',
'revdelete-nologtype-text'    => 'Hindi ka tumukoy ng isang uri ng talang pagsasagawaan ng kilos na ito.',
'revdelete-nologid-title'     => 'Hindi tanggap na entrada/ipinasok sa tala.',
'revdelete-nologid-text'      => 'Hindi ka tumukoy ng isang pinupuntiryang kaganapang pangtala upang maisagawa ang tungkuling ito o hindi umiiral ang tinukoy na entrada/ipinasok.',
'revdelete-no-file'           => 'Hindi umiiral ang tinutukoy na talaksan.',
'revdelete-show-file-confirm' => 'Nakatitiyak ka bang nais mong tanawin ang isang binurang pagbabago ng talaksang "<nowiki>$1</nowiki>" mula $2 noong $3?',
'revdelete-show-file-submit'  => 'Oo',
'revdelete-selected'          => "{{PLURAL:$2|Piniling|Mga piniling}} pagbabago ng '''$1:'''",
'logdelete-selected'          => '{{PLURAL:$1|Piniling tala ng pangyayari|Piniling tala ng mga pangyayari}}:',
'revdelete-text'              => "'''Makikita pa rin ang mga binurang pagbabago at mga kaganapan sa pahina ng kasaysayan at mga talaan, ngunit hindi mapupuntahan ng madla ang mga bahagi ng kanilang nilalaman.
Makikita pa rin ng iba pang mga tagapangasiwang nasa {{SITENAME}} ang mga tinagong nilalaman at maaaring ibalik ito mula sa pagkakabura sa pamamagitan ng kaparehong ugnayang-hangganan, maliban na lamang kung may itinakdang karagdagang mga restriksyon.",
'revdelete-confirm'           => 'Pakitiyak po na nais mo itong gawin, na nauunawaan mo ang mga kahihinatnan, at na ginagawa mo ito alinsunod sa [[{{MediaWiki:Policy-url}}|patakaran]].',
'revdelete-suppress-text'     => "Ang paglilingid ay dapat na gamitin '''lamang''' para sa sumusunod na mga pagkakataon:
* Hindi naaangkop na kabatirang pansarili
*: ''mga adres ng tahanan at bilang na pangtelepono, mga bilang na pangseguridad na sosyal, atbp.''",
'revdelete-legend'            => 'Itakda ang mga kaantasan ng pagpapakita',
'revdelete-hide-text'         => 'Itago ang teksto ng pagbabago',
'revdelete-hide-image'        => 'Itago ang nilalaman ng talaksan',
'revdelete-hide-name'         => 'Itago ang galaw at puntirya',
'revdelete-hide-comment'      => 'Itago ang komento sa pagbabago',
'revdelete-hide-user'         => 'Itago ang pangalang pantagagamit/IP ng patnugot',
'revdelete-hide-restricted'   => 'Ilingid ang dato mula sa mga tagapangasiwa at maging sa mga iba pa',
'revdelete-radio-same'        => '(huwag baguhin)',
'revdelete-radio-set'         => 'Oo',
'revdelete-radio-unset'       => 'Hindi',
'revdelete-suppress'          => 'Supilin ang datos mula sa mga tagapangasiwa gayon din sa iba',
'revdelete-unsuppress'        => 'Tanggalin ang mga pagbabawal sa naibalik na mga pagbabago',
'revdelete-log'               => 'Dahilan:',
'revdelete-submit'            => 'Pairalin para sa napiling {{PLURAL:$1|pagbabago|mga pagbabago}}',
'revdelete-logentry'          => 'binago ang antas ng pagpapakita ng pagbabago kay [[$1]]',
'logdelete-logentry'          => 'binago ang antas ng pagpapakita ng kaganapan kay [[$1]]',
'revdelete-success'           => "'''Matagumpay na naisapanahon''' ang kaantasan ng pagpapakita ng pagbabago.",
'revdelete-failure'           => "'''Hindi maisapanahon ang pagbabago sa kaantasan ng pagpapakita.'''
$1",
'logdelete-success'           => "'''Matagumpay na naitakda ang kaantasan ng pagpapakita ng tala.'''",
'logdelete-failure'           => "'''Hindi maitakda ang kaantasan ng pagpapakita ng kalap:'''
$1",
'revdel-restore'              => 'Baguhin ang kaantasan ng pagpapakita',
'revdel-restore-deleted'      => 'naburang mga binago',
'revdel-restore-visible'      => 'makikitang mga binago',
'pagehist'                    => 'Kasaysayan ng pahina',
'deletedhist'                 => 'Naburang kasaysayan',
'revdelete-content'           => 'nilalaman',
'revdelete-summary'           => 'buod ng pagbabago',
'revdelete-uname'             => 'pangalang pantagagamit',
'revdelete-restricted'        => 'nilapat na mga paghihigpit sa mga tagapangasiwa',
'revdelete-unrestricted'      => 'tinanggal ang mga pagbabawal para sa mga tagapangasiwa',
'revdelete-hid'               => 'itinago $1',
'revdelete-unhid'             => 'pinalitaw $1',
'revdelete-log-message'       => '$1 para sa $2 {{PLURAL:$2|pagbabago|mga pagbabago}}',
'logdelete-log-message'       => '$1 para sa $2 {{PLURAL:$2|kaganapan|mga kaganapan}}',
'revdelete-hide-current'      => 'May kamalian sa pagtatago ng bagay na may petsang $2, $1: ito ang kasalukuyang pagbabago.
Hindi ito maikukubli.',
'revdelete-show-no-access'    => 'May kamalian sa pagpapakita ng bagay na may petsang $2, $1: tinatakan ang bagay na ito bilang "ipinagbabawal".
Hindi mo ito mapupuntahan.',
'revdelete-modify-no-access'  => 'May mali sa pagbabago ng bagay na may petsang $2, $1: tinatakan ang bagay na ito bilang "ipinagbabawal":
Hindi mo ito mapupuntahan.',
'revdelete-modify-missing'    => 'Pagkakamali sa pagbabago ng ID ng bagay na $1: nawawala ito mula sa kalipunan ng datos!',
'revdelete-no-change'         => "'''Babala:''' ang bagay na may petsang $2, $1 ay mayroon na ng hiniling na pagtatakdang pangkaantasan ng pagpapakita.",
'revdelete-concurrent-change' => 'May mali sa pagbabago ng bagay na may petsang $2, $1: ang katayuan nito ay tila nagpapakitang binago ng ibang tao habang sinubukan mong baguhin ito.
Pakitingnan ang mga talaan.',
'revdelete-only-restricted'   => 'May mali sa pagtatago ng bagay na may petsang $2, $1: hindi mo mapipigil ang mga bagay na matingnan ng mga tagapangasiwa na hindi rin pipili ng isa sa mga pagpipiliang kaugnay ng antas ng pagpapakita.',
'revdelete-reason-dropdown'   => '*Mga karaniwang dahilan sa pagbubura
** Paglabag sa karapatang-ari (kopirayt)
** Hindi nararapat na personal na impormasyon
** Impormasyong maaaring mapanirang-puri',
'revdelete-otherreason'       => 'Iba/karagdagang dahilan:',
'revdelete-reasonotherlist'   => 'Ibang dahilan',
'revdelete-edit-reasonlist'   => 'Baguhin ang mga dahilan ng pagbura',
'revdelete-offender'          => 'May-akda ng pagbabago:',

# Suppression log
'suppressionlog'     => 'Tala ng pagpipigil',
'suppressionlogtext' => "Nasa ibaba ang isang tala ng mga pagbura at mga pagharang/paghadlang na kinakasangkutan ng nilalamang nakatago sa mga ''sysop''.
Tingnan ang [[Special:IPBlockList|talaan ng hinarang na/hinadlangang IP]] para sa isang talaan ng mga pangkasalukuyan at gumaganang mga pinagbawalan at mga pagharang/paghadlang.",

# Revision move
'moverevlogentry'              => 'inilipat {{PLURAL:$3|isang pagbabago|$3 mga pagbabago}} mula $1 patungo sa $2',
'revisionmove'                 => 'Ilipat ang mga pagbabago mula sa "$1"',
'revmove-explain'              => 'Ang sumusuno na mga pagbabago ay ililipat mula sa $1 papunta sa tinukoy na puntiryang pahina.  Kapag hindi umiiral ang puntirya, lilikhain ito.  Kung hindi, ang mga pagbabagong ito ay isasanib sa kasaysayan ng pahina.',
'revmove-legend'               => 'Itakda ang puntiryang pahina at buod',
'revmove-submit'               => 'Ilipat ang mga pagbabago papunta sa napiling pahina',
'revisionmoveselectedversions' => 'Ilipat ang napiling mga pagbabago',
'revmove-reasonfield'          => 'Dahilan:',
'revmove-titlefield'           => 'Puntiryang pahina:',
'revmove-badparam-title'       => 'Masamang mga parametro',
'revmove-badparam'             => 'Naglalaman ang kahilingan mo ng ilegal o hindi sapat na mga parametro.  Pakipindot ang "bumalik" at subukan uli.',
'revmove-norevisions-title'    => 'Hindi tanggap na puntiryang pagbabago',
'revmove-norevisions'          => 'Hindi ka tumukoy ng isa o higit pang puntiryang mga pagbabago upang magampanan ang tungkuling ito o hindi umiiral ang tinukoy na pagbabago.',
'revmove-nullmove-title'       => 'Masamang pamagat',
'revmove-nullmove'             => 'Magakawangis ang pahinang pinagmulan at puntirya.  Pakipindot ang "bumalik" at maglagay ng isang pahinang naiiba sa "[[$1]]".',
'revmove-success-existing'     => '{{PLURAL:$1|Isang pagbabago mula sa [[$2]] ang|$1 mga pagbabago mula sa [[$2]] ang}} inilipat papunta sa umiiral na pahinang [[$3]].',
'revmove-success-created'      => '{{PLURAL:$1|Isang pagbabago mula sa [[$2]] ang|$1 mga pagbabago mula sa [[$2]] ang}} nailipat papunta sa bagong-likhang pahinang [[$3]].',

# History merging
'mergehistory'                     => 'Pagsanibin mga pahina ng kasaysayan',
'mergehistory-header'              => 'Pinapahintuluan ka ng pahinang ito upang mapagsanib ang mga kasaysayan ng isang pinagmulang pahina patungo sa isang mas bagong pahina.
Tiyakin na ang pagbabago ay makapagpapanatili ng pagkakatuluy-tuloy ng pahinang pangkasaysayan.',
'mergehistory-box'                 => 'Pagsamahin ang mga pagbabago ng dalawang mga pahina:',
'mergehistory-from'                => 'Pinagmulang pahina:',
'mergehistory-into'                => 'Kapupuntahang pahina:',
'mergehistory-list'                => 'Mapagsasanib na kasaysayan ng pagbabago',
'mergehistory-merge'               => "Ang mga sumusunod na mga pagbabago ng [[:$1]] ay maaaring pag-isahin sa [[:$2]]. Gamitin ang hanay ng radyong buton upang pag-isahin lamang ang mga pagbabagong nilikha sa at bago ang binigay na oras.  Tandaan na ma-re-''reset'' ang paggamit ng mga ugnay ng panlibot (nabigasyon) ng hanay na ito.",
'mergehistory-go'                  => 'Ipakita ang mga pagbabagong mapagsasanib',
'mergehistory-submit'              => 'Pagsanibin ang mga pagbabago',
'mergehistory-empty'               => 'Walang mga pagbabagong mapagsasanib.',
'mergehistory-success'             => '$3 {{PLURAL:$3|pagbabago|pagbabago}} ng [[:$1]] ay matagumpay na naisanib sa [[:$2]].',
'mergehistory-fail'                => 'Hindi magawa ang pagsasanib ng kasaysayan, pakisuri ang parametro ng pahina at oras.',
'mergehistory-no-source'           => 'Hindi umiiral ang pagmumulang pahinang $1.',
'mergehistory-no-destination'      => 'Hindi umiiral ang patutunguhang pahinang $1.',
'mergehistory-invalid-source'      => 'Tanggap na pamagat dapat ang pagmumulang pahina.',
'mergehistory-invalid-destination' => 'Tanggap na pamagat dapat ang kapupuntahang pahina.',
'mergehistory-autocomment'         => 'Pinagsanib ang [[:$1]] sa [[:$2]]',
'mergehistory-comment'             => 'Pinagsanib ang [[:$1]] sa [[:$2]]: $3',
'mergehistory-same-destination'    => 'Pinagmulan at patutunguhan hindi dapat magkatulad',
'mergehistory-reason'              => 'Dahilan:',

# Merge log
'mergelog'           => 'Tala ng pagsasanib',
'pagemerge-logentry' => 'sinanib ang [[$1]] sa [[$2]] (mga pagbabago hanggang sa $3)',
'revertmerge'        => 'Paghiwalayin',
'mergelogpagetext'   => 'Nasa ibaba ang isang talaan ng mga pinakakamakailan lamang na mga pagsasanib ng isang kasaysayan ng pahina patungo sa isa pa.',

# Diffs
'history-title'            => 'Kasaysayan ng pagbabago ng "$1"',
'difference'               => '(Pagkakaiba sa pagitan ng mga pagbabago)',
'difference-multipage'     => '(Pagkakaiba sa pagitan ng mga pahina)',
'lineno'                   => 'Linya $1:',
'compareselectedversions'  => 'Paghambingin ang mga napiling bersyon',
'showhideselectedversions' => 'Ipakita/itago ang napiling mga bersyon',
'editundo'                 => 'ibalik',
'diff-multi'               => '({{PLURAL:$1|Isang panggitnang pagbabago|$1 panggitnang mga pagbabago}} ng {{PLURAL:$2|isang tagagamit|$2 mga tagagamit}} ang hindi ipinakikita.)',
'diff-multi-manyusers'     => '({{PLURAL:$1|Isang panggitnang pagbabago|$1 panggitnang mga pagbabago}} ng {{PLURAL:$2|isang tagagamit|$2 mga tagagamit}} ang hindi ipinapakikita.)',

# Search results
'searchresults'                    => 'Kinalabasan/Resulta ng paghahanap',
'searchresults-title'              => 'Resulta ng paghahanap para sa "$1"',
'searchresulttext'                 => 'Para sa mas maraming kabatiran hinggil sa paghahanap sa {{SITENAME}}, tingnan ang [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Hinanap mo ang \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|lahat ng mga pahinang nagsisimula sa "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|lahat ng mga pahinang nakakawing sa "$1"]])',
'searchsubtitleinvalid'            => "Hinanap mo ang '''$1'''",
'toomanymatches'                   => 'Napakaraming mga tumutugmang ibinalik, pakisubok ang isang ibang tanong',
'titlematches'                     => 'Tumutugma ang pamagat ng pahina',
'notitlematches'                   => 'Walang tumutugmang pamagat ng pahina',
'textmatches'                      => 'Tumutugma ang teksto ng pahina',
'notextmatches'                    => 'Walang katugmang pahina ng teksto',
'prevn'                            => 'nauna {{PLURAL:$1|$1}}',
'nextn'                            => 'kasunod {{PLURAL:$1|$1}}',
'prevn-title'                      => 'Nakaraang $1 {{PLURAL:$1|resulta|mga resulta}}',
'nextn-title'                      => 'Susunod na $1 {{PLURAL:$1|resulta|mga resulta}}',
'shown-title'                      => 'Ipakita ang $1 {{PLURAL:$1|resulta|mga resulta}} na para sa bawat isang pahina',
'viewprevnext'                     => 'Tingnan ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Mga pagpipilian para sa paghahanap',
'searchmenu-exists'                => "'''Mayroong pahinang may pangalang \"[[:\$1]]\" dito sa wiking ito'''",
'searchmenu-new'                   => "'''Likhain ang pahinang \"[[:\$1]]\" sa wiking ito!'''",
'searchhelp-url'                   => 'Help:Nilalaman',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Tingnan-tingnan ang mga pahinang may ganitong unahan/unlapi]]',
'searchprofile-articles'           => 'Mga pahina ng nilalaman',
'searchprofile-project'            => 'Mga pahina ng Tulong at Proyekto',
'searchprofile-images'             => 'Multimidya',
'searchprofile-everything'         => 'Lahat ng bagay',
'searchprofile-advanced'           => 'Mas mataas na antas',
'searchprofile-articles-tooltip'   => 'Hanapin sa $1',
'searchprofile-project-tooltip'    => 'Hanapin sa $1',
'searchprofile-images-tooltip'     => 'Maghanap ng mga talaksan',
'searchprofile-everything-tooltip' => 'Hanapin ang lahat ng nilalaman (kabilang ang mga pahina ng usapan)',
'searchprofile-advanced-tooltip'   => 'Hanapin sa pinasadyang mga espasyo ng pangalan',
'search-result-size'               => '$1 ({{PLURAL:$2|1 salita|$2 salita}})',
'search-result-category-size'      => '{{PLURAL:$1|isang kasapi|$1 kasapi}} ({{PLURAL:$2|isang subkategorya|$2 subkategorya}}, {{PLURAL:$3|isang talaksan|$3 talaksan}})',
'search-result-score'              => 'Kaugnayan: $1%',
'search-redirect'                  => '(ipanuto/ituro ang $1)',
'search-section'                   => '(seksyong $1)',
'search-suggest'                   => 'Ito ba ang ibig mong sabihin: $1',
'search-interwiki-caption'         => 'Kapatid na mga proyekto',
'search-interwiki-default'         => '$1 mga resulta:',
'search-interwiki-more'            => '(mas marami pa)',
'search-mwsuggest-enabled'         => 'may mga mungkahi',
'search-mwsuggest-disabled'        => 'walang mga mungkahi',
'search-relatedarticle'            => 'Kaugnay',
'mwsuggest-disable'                => 'Huwag paganahin ang mga mungkahi ng AJAX',
'searcheverything-enable'          => 'Maghanap sa lahat ng ngalan-espasyo:',
'searchrelated'                    => 'kaugnay',
'searchall'                        => 'lahat',
'showingresults'                   => "Ipinapakita sa ibaba ang magpahanggang sa {{PLURAL:$1|'''1''' resultang|'''$1''' mga resultang}} nagsisimula sa #'''$2'''.",
'showingresultsnum'                => "Ipinapakita sa ibaba ang {{PLURAL:$3|'''1''' resultang|'''$3''' mga resultang}} nagsisimula sa #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Resultang '''$1''' ng '''$3'''|Mga resultang '''$1 - $2''' ng '''$3'''}} para sa '''$4'''",
'nonefound'                        => "'''Paunawa''': Ilang mga ngalan-espasyo lamang ang hinahanap ayon sa likas na pagkakatakda.
Subuking lagyan ng unlapi/paunang ''all:'' upang hanapin ang lahat ng mga nialalaman (kabilang ang mga pahina ng usapan, mga suleras, atbp), o gamitin ang ninanais na espasyo ng pangalan bilang unlapi.",
'search-nonefound'                 => 'Walang mga resultang tumutugma sa katanungan/pagtatanong.',
'powersearch'                      => 'Paghahanap na may mas mataas na antas',
'powersearch-legend'               => 'Paghahanap na may mas mataas na antas',
'powersearch-ns'                   => 'Maghanap sa mga espasyo ng pangalan:',
'powersearch-redir'                => 'Itala ang mga panuto',
'powersearch-field'                => 'Hanapin ang',
'powersearch-togglelabel'          => 'Suriin:',
'powersearch-toggleall'            => 'Lahat',
'powersearch-togglenone'           => 'Wala',
'search-external'                  => 'Panlabas na paghahanap',
'searchdisabled'                   => 'Nakapatay ang paghahanap sa {{SITENAME}}. Maaari kang pansamantalang maghanap sa pamamagitan ng Google. Tandaan na maaaring luma na ang kanilang mga indeks sa nilalaman ng {{SITENAME}}.',

# Quickbar
'qbsettings'               => 'Quickbar',
'qbsettings-none'          => 'Wala',
'qbsettings-fixedleft'     => 'Inayos ang kaliwa',
'qbsettings-fixedright'    => 'Inayos ang kanan',
'qbsettings-floatingleft'  => 'Kaliwa lumulutang',
'qbsettings-floatingright' => 'Kanan lumulutang',

# Preferences page
'preferences'                   => 'Mga kagustuhan',
'mypreferences'                 => 'Aking mga kagustuhan',
'prefs-edits'                   => 'Bilang ng mga pagbabago:',
'prefsnologin'                  => 'Hindi nakalagda/nakatala',
'prefsnologintext'              => 'Kailangan mong <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} lumagda/tumala]</span> para makapagtakda ng mga kagustuhang ng tagagamit.',
'changepassword'                => 'Baguhin ang hudyat',
'prefs-skin'                    => 'Pabalat',
'skin-preview'                  => 'Unang tingin',
'prefs-math'                    => 'Matematika',
'datedefault'                   => 'Walang kagustuhan',
'prefs-datetime'                => 'Petsa at oras',
'prefs-personal'                => 'Sanligang pangkatangian ng tagagamit',
'prefs-rc'                      => 'Kamakailan lamang na mga pagbabago',
'prefs-watchlist'               => 'Talaan ng mga binabantayan',
'prefs-watchlist-days'          => 'Mga araw na ipapakita sa talaan ng mga binabantayan:',
'prefs-watchlist-days-max'      => '(pinakamarami ang 7 mga araw)',
'prefs-watchlist-edits'         => 'Pinakamaraming bilang ng mga pagbabagong ipapakita sa pinalawak na talaan ng mga binabantayan:',
'prefs-watchlist-edits-max'     => '(pinakamataas na bilang: 1000)',
'prefs-watchlist-token'         => 'Balap ng talaan ng mga binabantayan:',
'prefs-misc'                    => 'Bala-balaki',
'prefs-resetpass'               => 'Baguhin ang hudyat',
'prefs-email'                   => 'Mga pagpipilian para sa e-liham',
'prefs-rendering'               => 'Hitsura',
'saveprefs'                     => 'Sagipin',
'resetprefs'                    => 'Hawanin ang hindi nasagip na mga pagbabago',
'restoreprefs'                  => 'Ibalik ang lahat ng likas na mga pagtatakda',
'prefs-editing'                 => 'May binabago',
'prefs-edit-boxsize'            => 'Sukat ng dungawan ng ginagawang pagbabago.',
'rows'                          => 'Mga pahalang na hanay:',
'columns'                       => 'Mga pahabang hanay:',
'searchresultshead'             => 'Hanapin',
'resultsperpage'                => 'Bilang ng pagtama sa bawat pahina:',
'contextlines'                  => 'Linya bawat pagtama:',
'contextchars'                  => 'Konteksto ng bawat guhit:',
'stub-threshold'                => 'Kakayanan para sa pagpopormat ng <a href="#" class="usbong">kawing ng usbong</a> (mga \'\'byte\'\'):',
'stub-threshold-disabled'       => 'Hindi pinagagana',
'recentchangesdays'             => 'Mga araw na ipapakita sa kamakailan lamang na mga pagbabago:',
'recentchangesdays-max'         => '(pinakamataas na ang $1 {{PLURAL:$1|araw|mga araw}})',
'recentchangescount'            => 'Bilang ng mga pagbabagong ipapakita sa pamamagitan ng likas na katakdaan:',
'prefs-help-recentchangescount' => 'Kasama nito ang mga huling binago, kasaysayan ng mga pahina, at mga tala.',
'prefs-help-watchlist-token'    => "Ang pagpupuno sa lugar na ito na ginagamitan ng lihim na susi ay lilikha ng pakaing RSS para sa iyong talaan ng mga binabantayan.  Ang sinumang nakakaalam ng susi sa loob ng lugar na ito ay makababasa ng iyong talaan ng mga binabantayan, kaya't pumili ng ligtas na halaga.  Narito ang magagamit mong isang halagang nilikha ng pagkakataon: $1",
'savedprefs'                    => 'Nasagip na ang mga kagustuhan mo.',
'timezonelegend'                => 'Sona ng oras:',
'localtime'                     => 'Lokal na oras:',
'timezoneuseserverdefault'      => 'Gamitin ang itinakda ng serbidor',
'timezoneuseoffset'             => "Iba pa (tukuyin ang pambawi o ''offset'')",
'timezoneoffset'                => "Pambawi/pambalanse (''offset'')¹:",
'servertime'                    => 'Oras sa serbidor',
'guesstimezone'                 => "Punuin ng mula sa pantingin-tingin (''browser'')",
'timezoneregion-africa'         => 'Aprika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antartika',
'timezoneregion-arctic'         => 'Artiko',
'timezoneregion-asia'           => 'Asya',
'timezoneregion-atlantic'       => 'Karagatang Atlantiko',
'timezoneregion-australia'      => 'Australya',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Karagatang Indyano',
'timezoneregion-pacific'        => 'Karagatang Pasipiko',
'allowemail'                    => 'Pahintulutan ang e-liham mula sa ibang mga tagagamit',
'prefs-searchoptions'           => 'Mga pagpipilian para sa paghahanap',
'prefs-namespaces'              => 'Mga espasyo ng pangalan',
'defaultns'                     => 'O kaya maghanap sa mga pangalan ng espasyong ito:',
'default'                       => 'Likas na pagtatakda',
'prefs-files'                   => 'Mga talaksan',
'prefs-custom-css'              => 'Pasadyang CSS',
'prefs-custom-js'               => 'Pasadyang JS',
'prefs-common-css-js'           => 'Naibahaging CSS/JS para sa lahat ng pabalat:',
'prefs-reset-intro'             => 'Magagamit mo ang pahinang ito upang muling maitakda ang mga kagustuhan mo sa likas na pagtatakda ng sityo.
Hindi ito maibabalik sa dating gawi.',
'prefs-emailconfirm-label'      => 'Kumpirmasyon ng e-liham:',
'prefs-textboxsize'             => 'Sukat ng bintana ng pagbabago',
'youremail'                     => 'E-liham:',
'username'                      => 'Bansag:',
'uid'                           => 'ID ng tagagamit:',
'prefs-memberingroups'          => 'Kasapi ng {{PLURAL:$1|na pangkat|na mga pangkat}}:',
'prefs-registration'            => 'Oras ng pagtatala:',
'yourrealname'                  => 'Tunay na pangalan:',
'yourlanguage'                  => 'Wika:',
'yourvariant'                   => 'Naiiba pa:',
'yournick'                      => 'Panglagda:',
'prefs-help-signature'          => 'Ang mga puna sa mga pahina ng usapan ay dapat na lagdaan ng "<nowiki>~~~~</nowiki>" na magiging lagda mo at marka ng oras.',
'badsig'                        => 'Hindi tamang hilaw na lagda.
Pakisuri ang mga tatak ng HTML.',
'badsiglength'                  => 'Napakahaba ng iyong lagda.
Dapat na mas mababa kaysa $1 {{PLURAL:$1|panitik|mga panitik}}.',
'yourgender'                    => 'Kasarian:',
'gender-unknown'                => 'Hindi tinukoy',
'gender-male'                   => 'Lalaki',
'gender-female'                 => 'Babae',
'prefs-help-gender'             => 'Maaaring wala nito: ginagamit para sa pagbanggit ng tamang kasarian sa pamamagitan ng sopwer. Magging pangmadla ang kabatiran ito.',
'email'                         => 'E-liham',
'prefs-help-realname'           => "Opsyonal ('di-talaga kailangan) ang tunay na pangalan.
Kung pipiliin mong ibigay ito, gagamitin ito para mabigyan ka ng pagkilala para iyong mga ginawa.",
'prefs-help-email'              => 'Opsyonal (hindi talaga kailangan) ang adres ng e-liham, subalit makapagpapahintulot ito sa pagpapadala ng bagong hudyat mo kapag nakalimutan mo ang iyong lumang hudyat.
Mapipili mo ring payagan ang ibang tagagamit na makapagugnayan sa iyo sa pamamagitan ng iyong pahina ng tagagamit o pahina ng usapan na hindi na kailangan pang ipakilala ang iyong katauhan.',
'prefs-help-email-required'     => 'Kailangan ang adres ng e-liham.',
'prefs-info'                    => 'Payak na kabatiran',
'prefs-i18n'                    => 'Internasyonalisasyon',
'prefs-signature'               => 'Lagda',
'prefs-dateformat'              => 'Anyo ng petsa',
'prefs-timeoffset'              => 'Pagtatama ng oras',
'prefs-advancedediting'         => 'Masulong na mga mapagpipilian',
'prefs-advancedrc'              => 'Masulong na mga mapagpipilian',
'prefs-advancedrendering'       => 'Masulong na mga mapagpipilian',
'prefs-advancedsearchoptions'   => 'Masulong na mga mapagpipilian',
'prefs-advancedwatchlist'       => 'Masulong na mga mapagpipilian',
'prefs-displayrc'               => 'Ipakita ang mga pagpipilian',
'prefs-displaysearchoptions'    => 'Ipakita ang mga pagpipilian',
'prefs-displaywatchlist'        => 'Ipakita ang mga pagpipilian',
'prefs-diffs'                   => 'Mga pagkakaiba',

# User rights
'userrights'                   => 'Pamamahala ng mga karapatan ng tagagamit',
'userrights-lookup-user'       => 'Pamahalaan ang mga pangkat ng tagagamit',
'userrights-user-editname'     => 'Magpasok ng isang pangalan ng tagagamit:',
'editusergroup'                => 'Baguhin ang mga pangkat ng tagagamit',
'editinguser'                  => "Binabago ang mga karapatang pangtagagamit ng tagagamit na si '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Baguhin ang mga pangkat ng tagagamit',
'saveusergroups'               => 'Sagipin ang mga pangkat ng tagagamit',
'userrights-groupsmember'      => 'Kasapi ng:',
'userrights-groupsmember-auto' => 'Karamay na kasapi ng:',
'userrights-groups-help'       => 'Maaari mong baguhin ang mga pangkat ng tagagamit na ito sa:
* Kahon na naka-tsek na nangangahulugang ang tagagamit ay kasapi sa pangkat.
* Kahon na hindi naka-tsek na nangangahulugang na hindi kasapi ang tagagamit sa pangkat.
* Ipinapahiwatig ng * na maaaring tanggalng ang pangkat kapag dinagdag ito, o ang kabaglitaran nito.',
'userrights-reason'            => 'Dahilan:',
'userrights-no-interwiki'      => 'Wala kang pahintulot na baguhin ang mga karapatan ng tagagamit sa ibang mga wiki.',
'userrights-nodatabase'        => 'Hindi umiiral o hindi lokal ang kalipunan ng datos na $1',
'userrights-nologin'           => 'Kailangang [[Special:UserLogin|nakalagda ka]] bilang tagapangasiwa upang maitalaga ang mga karapatan ng tagagamit.',
'userrights-notallowed'        => 'Walang pahintulot ang iyong akawnt na magtalaga ng mga karapatan ng tagagamit.',
'userrights-changeable-col'    => 'Mga pangkat na maaari mong baguhin',
'userrights-unchangeable-col'  => 'Mga pangkat na hindi mo mababago',

# Groups
'group'               => 'Pangkat:',
'group-user'          => 'Mga tagagamit',
'group-autoconfirmed' => 'Mga tagagamit na nakompirma sa kusang paraan (autokompirmasyon)',
'group-bot'           => "Mga ''bot''",
'group-sysop'         => 'Mga tagapangasiwa',
'group-bureaucrat'    => 'Mga burokrato',
'group-suppress'      => 'Mga tagapagingat-tago',
'group-all'           => '(lahat)',

'group-user-member'          => 'Tagagamit',
'group-autoconfirmed-member' => 'Kusang nakumpirmang tagagamit',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'tagapangasiwa',
'group-bureaucrat-member'    => 'Burokrato',
'group-suppress-member'      => 'Tagapagingat-tago',

'grouppage-user'          => '{{ns:project}}:Mga tagagamit',
'grouppage-autoconfirmed' => '{{ns:project}}:Kusang nakumpirmang mga tagagamit',
'grouppage-bot'           => "{{ns:project}}:Mga ''bot''",
'grouppage-sysop'         => '{{ns:project}}:Mga tagapangasiwa',
'grouppage-bureaucrat'    => '{{ns:project}}:Mga burokrato',
'grouppage-suppress'      => '{{ns:project}}:Mga tagapagingat-tago<!---katulad ng "ingat-yaman"-->',

# Rights
'right-read'                  => 'Basahin ang mga pahina',
'right-edit'                  => 'Baguhin ang mga pahina',
'right-createpage'            => 'Lumikha ng mga pahina (na hindi mga pahina ng usapan)',
'right-createtalk'            => 'Lumikha ng mga pahina ng usapan',
'right-createaccount'         => 'Lumikha ng bagong mga kuwenta ng tagagamit',
'right-minoredit'             => 'Itatak ang mga pagbabago bilang maliit',
'right-move'                  => 'Ilipat ang mga pahina',
'right-move-subpages'         => 'Ilipat ang mga pahina kasama ang pahinang nasa ilalim nito',
'right-move-rootuserpages'    => 'Ilipat ang pinagugatang mga pahina ng tagagamit',
'right-movefile'              => 'Ilipat ang mga talaksan',
'right-suppressredirect'      => 'Hindi nilikha sa isang pagkarga mula sa lumang pangalan kapag naglipat ng isang pahina',
'right-upload'                => 'Magkarga ng mga talaksan',
'right-reupload'              => 'Patungan ang mayroon nang mga talaksan',
'right-reupload-own'          => 'Patungan ang talaksang kinarga ng sarili',
'right-reupload-shared'       => 'Patungan ang mga talaksan sa binabahaging repositoryo midya sa lokal',
'right-upload_by_url'         => 'Magkarga ng isang talaksan mula sa isang adres na URL',
'right-purge'                 => 'Sariwain ang baunan ng sayt para sa isang pahina na walang kumpirmasyon',
'right-autoconfirmed'         => 'Baguhin ang medyo-nakaprotektang mga pahina',
'right-bot'                   => 'Maging isang awtomatikong proseso',
'right-nominornewtalk'        => 'Walang maliit na pagbabago sa mga pahina ng usapan na pasimula ang bagong paglitaw ng mga mensahe',
'right-apihighlimits'         => 'Gumamit ng mga matataas ng hangganan sa mga pagtatanong sa API',
'right-writeapi'              => 'Gamit ng sinulat na API',
'right-delete'                => 'Burahin ang mga pahina',
'right-bigdelete'             => 'Burahin ang mga pahinang may malaking mga kasaysayan',
'right-deleterevision'        => 'Burahin at tanggalin sa pagkabura ang isang partikular na mga pagbabago ng mga pahina',
'right-deletedhistory'        => 'Tingnan ang mga binurang pinasok na kasaysayan, na wala ang kanilang nakakabit na teksto',
'right-deletedtext'           => 'Tingnan ang naburang teksto at mga pagbabago sa pagitan ng dalawang mga rebisyon',
'right-browsearchive'         => 'Hanapin ang mga binurang mga pahina',
'right-undelete'              => 'Buhayin muli ang isang pahina',
'right-suppressrevision'      => "Suriing muli at ibalik ang mga pagbabagong itinago mula sa mga ''Sysop''.",
'right-suppressionlog'        => 'Tingnan ang pansariling mga pagtatala.',
'right-block'                 => 'Harangin sa paggawa ng pagbabago ang ibang mga tagagamit',
'right-blockemail'            => 'Harangin sa pagpapadala ng e-liham ang isang tagagamit',
'right-hideuser'              => 'Harangin ang isang tagagamit, na itinatago mula sa publiko',
'right-ipblock-exempt'        => 'Laktawan ang mga pagharang/paghadlang na pang-IP, kusang pagharang/paghadlang at mga saklaw ng pagharang/paghadlang',
'right-proxyunbannable'       => 'Laktawan ang mga kusang pagharang ng mga kahalili',
'right-unblockself'           => 'Tanggalin ang pagkakaharang ng kanilang mga sarili',
'right-protect'               => 'Baguhin ang mga antas ng panananggalang at baguhin ang mga pahinang nakasanggalang',
'right-editprotected'         => 'Baguhin ang mga pahinang nakasanggalang (walang baita-baitang na panananggalang)',
'right-editinterface'         => 'Baguhin ang ugnayang-hangganan ng tagagamit',
'right-editusercssjs'         => 'Baguhin ang mga talaksang CSS at JS ng ibang mga tagagamit',
'right-editusercss'           => 'Baguhin ang mga talaksang CSS ng ibang mga tagagamit',
'right-edituserjs'            => 'Baguhin ang mga talaksang JS ng ibang mga tagagamit',
'right-rollback'              => 'Mabilisang pagulungin pabalik sa dati ang mga pagbabago ng huling tagagamit na nagbago ng isang partikular na pahina',
'right-markbotedits'          => 'Itatak ang mga binalik na mga pagbabago bilang pagbabagong bot',
'right-noratelimit'           => 'Hindi maaapektuhan ng antas ng mga hangganan',
'right-import'                => 'Umangkat ng mga pahina mula sa ibang mga wiki',
'right-importupload'          => 'Umangkat ng mga pahina mula sa isang talaksang ikinarga',
'right-patrol'                => 'Tatakan bilang napatrolya ang mga pagbabago ng iba',
'right-autopatrol'            => 'Kusang tatakan bilang napatrolya ang sariling mga pagbabago',
'right-patrolmarks'           => 'Tingnan ang mga kamakailang pagbabagong natatakan bilang napatrolya',
'right-unwatchedpages'        => 'Tingnan ang isang talaan ng mga pahinang hindi binabantayan',
'right-trackback'             => "Magpasa ng isang balikan-ang-bakas (''trackback'')",
'right-mergehistory'          => 'Pagsanibin ang kasaysayan ng mga pahina',
'right-userrights'            => 'Baguhin ang lahat ng karapatan ng tagagamit',
'right-userrights-interwiki'  => 'Baguhin ang karapatan ng mga tagagamit na nasa ibang mga wiki',
'right-siteadmin'             => 'Ikandado at alisin ang pagkakakandado ng kalipunan ng dato',
'right-reset-passwords'       => 'Mulng itakda ang mga hudyat ng iba pang mga tagagamit',
'right-override-export-depth' => 'Iluwas ang mga pahina na kabilang ang mga pahinang nakakawing magpahanggang sa isang lalim na 5',
'right-sendemail'             => 'Magpadala ng e-liham sa ibang mga tagagamit',
'right-revisionmove'          => 'Ilipat ang mga pagbabago',

# User rights log
'rightslog'      => 'Tala ng mga karapatan ng tagagamit',
'rightslogtext'  => 'Isa itong tala ng mga pagbabago sa mga karapatan ng tagagamit.',
'rightslogentry' => 'binago ang kasapiang pampangkat para kay $1 mula sa $2 patungong $3',
'rightsnone'     => '(wala)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'basahin itong pahina',
'action-edit'                 => 'baguhin itong pahina',
'action-createpage'           => 'lumikha ng mga pahina',
'action-createtalk'           => 'lumikha ng mga pahina ng usapan',
'action-createaccount'        => 'likhain itong kuwenta ng tagagamit',
'action-minoredit'            => 'tatakan ito bilang isang maliit na pagbabago',
'action-move'                 => 'ilipat itong pahina',
'action-move-subpages'        => 'ilipat itong pahina, pati ang mga kabahaging pahina (subpahina) nito',
'action-move-rootuserpages'   => 'ilipat ang mga pinagugatang mga pahina ng tagagamit',
'action-movefile'             => 'ilipat ang talaksang ito',
'action-upload'               => 'ikarga itong talaksan',
'action-reupload'             => 'patungan itong pahinang umiiral',
'action-reupload-shared'      => 'daigin itong talaksan sa isang pinagsasaluhang taguan/repositoryo',
'action-upload_by_url'        => 'ikarga itong talaksan mula sa isang adres ng URL',
'action-writeapi'             => 'gamitin ang pagsulat na API',
'action-delete'               => 'burahin itong pahina',
'action-deleterevision'       => 'burahin ang pagbabagong ito',
'action-deletedhistory'       => 'tingnan ang binurang kasaysayan ng pahinang ito',
'action-browsearchive'        => 'hanapin ang binurang mga pahina',
'action-undelete'             => 'ibalik mula sa pagkakabura ang pahinang ito',
'action-suppressrevision'     => 'suriing muli at ibalik ang nakatagong pagbabagong ito',
'action-suppressionlog'       => 'tingnan itong pribadong tala',
'action-block'                => 'harangin sa paggawa ng pagbabago ang tagagamit na ito',
'action-protect'              => 'baguhin ang mga antas ng pagsasanggalang para sa pahinang ito',
'action-import'               => 'angkatin itong pahina mula sa ibang wiki',
'action-importupload'         => 'angkatin ang pahinang ito mula sa isang ikinargang talaksan',
'action-patrol'               => 'tatakan bilang napatrolya na ang mga pagbabagong ginawa ng iba',
'action-autopatrol'           => 'tatakan ang pagbabago mo bilang napatrolya na',
'action-unwatchedpages'       => 'tingnan ang talaan ng mga pahinang hindi nababantayan',
'action-trackback'            => "magpasa ng isang balikan-ang-bakas (''trackback'')",
'action-mergehistory'         => 'pagsanibin ang kasaysayan nitong pahina',
'action-userrights'           => 'baguhin ang lahat ng karapatan ng tagagamit',
'action-userrights-interwiki' => 'baguhin ang mga karapatan ng tagagamit na nasa ibang mga wiki',
'action-siteadmin'            => 'ikandado o tanggalin ang pagkakakandado ng kalipunan ng dato',
'action-revisionmove'         => 'ilipat ang mga pagbabago',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|pagbabago|mga pagbabago}}',
'recentchanges'                     => 'Kamakailang pagbabago',
'recentchanges-legend'              => 'Mga pagpipilian para sa kamakailang mga pagbabago',
'recentchangestext'                 => 'Subaybayan ang mga pinakahuling pagbabago sa wiki sa pahinang ito.',
'recentchanges-feed-description'    => 'Sundan ang pinakahuling mga pagbabago sa wiki sa pamamagitan ng feed na ito.',
'recentchanges-label-newpage'       => 'Lumikha ng isang bagong pahina ang pagbabagong ito',
'recentchanges-label-minor'         => 'Isa itong munting pagbabago',
'recentchanges-label-bot'           => 'Gawa ng isang bot ang pagbabagong ito',
'recentchanges-label-unpatrolled'   => 'Hindi pa napapatrulyahan ang pagbabagong ito',
'rcnote'                            => "Nasa ibaba {{PLURAL:$1|ang '''1''' pagbabago|ang pinakahuling '''$1''' mga pagbabago}} sa huling {{PLURAL:$2|araw|'''$2''' mga araw}}, mula noong $5, $4.",
'rcnotefrom'                        => "Nasa ibaba ang mga pagbabago mula pa noong '''$2''' (ipinapakita ang magpahanggang sa '''$1''').",
'rclistfrom'                        => 'Ipakita ang bagong mga pagbabago simula sa $1',
'rcshowhideminor'                   => '$1 maliliit na mga pagbabago',
'rcshowhidebots'                    => "$1 mga ''bot''",
'rcshowhideliu'                     => '$1 nakalagdang mga tagagamit',
'rcshowhideanons'                   => '$1 hindi kilalang mga tagagamit',
'rcshowhidepatr'                    => '$1 napatrolyang mga pagbabago',
'rcshowhidemine'                    => '$1 mga pagbabago ko',
'rclinks'                           => 'Ipakita ang huling $1 mga pagbabago sa loob ng huling $2 mga araw<br />$3',
'diff'                              => 'pagkakaiba',
'hist'                              => 'kasaysayan',
'hide'                              => 'Itago',
'show'                              => 'Ipakita',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'B',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 binabantayang {{PLURAL:$1|tagagamit|mga tagagamit}}]',
'rc_categories'                     => 'Itakda lang sa mga kaurian (ihiwalay sa pamamagitan ng "|")',
'rc_categories_any'                 => 'Kahit ano',
'newsectionsummary'                 => '/* $1 */ bagong seksyon',
'rc-enhanced-expand'                => 'Ipakita ang mga detalye (kailangan ng JavaScript)',
'rc-enhanced-hide'                  => 'Itago ang mga detalye',

# Recent changes linked
'recentchangeslinked'          => 'Kaugnay na mga pagbabago',
'recentchangeslinked-feed'     => 'Kaugnay na mga pagbabago',
'recentchangeslinked-toolbox'  => 'Kaugnay na mga pagbabago',
'recentchangeslinked-title'    => 'Mga pagbabagong kaugnay ng "$1"',
'recentchangeslinked-noresult' => 'Walang mga pagbabago sa mga pahinang nakakawing sa ibinigay na kapanahunan.',
'recentchangeslinked-summary'  => "Nililista ng natatanging pahina na ito ang huling mga pagbabago na nakaugnay. Naka '''matapang na teksto''' ang iyong mga binabantayan.",
'recentchangeslinked-page'     => 'Pangalan ng pahina:',
'recentchangeslinked-to'       => 'Ipakita ang mga pagbabago sa mga pahinang nakaugnay sa isang binigay na pahina sa halip',

# Upload
'upload'                      => 'Magkarga ng talaksan',
'uploadbtn'                   => 'Magkarga ng talaksan',
'reuploaddesc'                => 'Kanselahin/Iurong ang pagkarga at magbalik sa pormularyo ng pagkakarga',
'upload-tryagain'             => 'Ipasa ang binagong paglalarawan ng talaksan',
'uploadnologin'               => 'Hindi nakalagda',
'uploadnologintext'           => 'Dapat ikaw ay [[Special:UserLogin|nakalagda]]
upang makapagkarga ng talaksan.',
'upload_directory_missing'    => 'Nawawala ang direktoryo ng pagkarga ($1) at hindi na mailikha ng webserver.',
'upload_directory_read_only'  => 'Ang direktoryo ng pagkarga ($1) ay hindi maisulat ng webserver.',
'uploaderror'                 => 'Kamalian sa pagkarga',
'upload-recreate-warning'     => "'''Babala: Ang isang talaksan na may ganyang pangalan ay nabura o inilipat.'''

Ang talaan ng pagbubura at paglipat para sa pahinang ito ay ibinigay dito para sa kaginhawahan:",
'uploadtext'                  => "Gamitin ang pormularyong nasa ibaba para magkarga ng mga talaksan.
Para tingnan o maghanap ng mga dati nang naikargang mga talaksan pumunta sa  [[Special:FileList|talaan ng ikinargang mga talaksan]], ang (muling) mga pagkakarga ay nakatala rin sa [[Special:Log/upload|talaan ng pagkarga]], ang mga binura/nabura sa  [[Special:Log/delete|talaan ng pagbubura]].

Para maisama ang isang talaksan sa loob ng isang pahina, gumamit ng isang kawing na nasa loob ng isa sa mga sumusunod na mga pormularyo:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' para magamit ang buong bersyon ng talaksan
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></tt>''' para magamit ang isang may 200 piksel na paghabi sa loob ng isang kahong nasa kaliwang pataan na may 'tekstong pamalit' ('' 'alt text' '') bilang paglalarawan
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' para sa tuwirang pagkakawing sa isang pahina na hindi ipinapakita ang talaksan",
'upload-permitted'            => 'Pinapahintulutang mga uri ng talaksan: $1.',
'upload-preferred'            => 'Mas iniibig na mga uri ng talaksan: $1.',
'upload-prohibited'           => 'Ipinagbabawal na mga uri ng talaksan: $1.',
'uploadlog'                   => 'tala ng pagkarga',
'uploadlogpage'               => 'Tala ng pagkarga',
'uploadlogpagetext'           => 'Nasa ibaba ang tala ng pinakahuling mga karga ng talaksan.',
'filename'                    => 'Pangalan ng talaksan',
'filedesc'                    => 'Buod',
'fileuploadsummary'           => 'Buod:',
'filereuploadsummary'         => 'Mga pagbabago sa talaksan:',
'filestatus'                  => 'Kalagayan ng karapang-ari:',
'filesource'                  => 'Pinagmulan:',
'uploadedfiles'               => 'Naikargang mga talaksan',
'ignorewarning'               => 'Balewalain ang babala at sagipin basta ang talaksan',
'ignorewarnings'              => 'Balewalain ang anumang mga babala',
'minlength1'                  => 'Dapat may kahit na isang titik lang ang mga pangalan ng talaksan.',
'illegalfilename'             => 'Ang pangalan ng talaksan (filename) na "$1" ay mayroon mga karakter na hindi pinapahintulot bilang pamagat ng isang pahina. Paki palitan ang pangalan at subukang ikarga muli.',
'badfilename'                 => 'Pinalitan ang pangalan ng talaksan na naging "$1".',
'filetype-mime-mismatch'      => 'Hindi tumutugma ang dugtong ng talaksan sa uri ng MIME.',
'filetype-badmime'            => 'Hindi pinapahintulutang maikarga ang uring "$1" ng mga talaksang MIME.',
'filetype-bad-ie-mime'        => 'Hindi maikarga ang talaksang ito dahil mapapansin/mapupuna ito ng Internet Explorer bilang "$1",
na hindi pinapahintulutan at maaaring isang mapanganib na uri ng talaksan.',
'filetype-unwanted-type'      => "Isang hindi ninanais na uri ng talaksan ang '''\".\$1\"'''.
Ang ninanais na {{PLURAL:\$3|uri ng talaksan ay ang|mga uri ng talaksan ay ang mga}} \$2.",
'filetype-banned-type'        => "Isang hindi pinapahintulutang uri ng talaksan ang '''\".\$1\"'''.
Ang pinapahintulutang {{PLURAL:\$3|uri ng talaksan ay ang|mga uri ng talaksan ay ang mga}} \$2.",
'filetype-missing'            => 'Walang karugtong/hulapi ang talaksan (katulad ng ".jpg").',
'empty-file'                  => 'Walang laman ang ipinasa mong talaksan.',
'file-too-large'              => 'Napakalaki ng ipinasa mong talaksan.',
'filename-tooshort'           => 'Napakaikli ng pangalan ng talaksan.',
'filetype-banned'             => 'Pinagbabawal ang ganitong uri ng talaksan.',
'verification-error'          => 'Hindi pumasa sa pagpapatunay na pangtalaksan ang ganitong talaksan.',
'hookaborted'                 => 'Ang pagbabagong sinubok mong gawin ay hindi itinuloy ng isang kawil ng dugtong.',
'illegal-filename'            => 'Hindi pinapayagan ang pangalan ng talaksan.',
'overwrite'                   => 'Hindi pinapahintulutan ang pagsusulat sa ibabaw ng umiiral na talaksan.',
'unknown-error'               => 'Naganap ang isang hindi nakikilalang kamalian.',
'tmp-create-error'            => 'Hindi malikha ang pansamantalang talaksan.',
'tmp-write-error'             => 'Kamalian sa pagsusulat ng pansamantalang talaksan.',
'large-file'                  => 'Iminumungkahing hindi hihigit ang laki ng mga talaksan sa $1;
ang talaksang ito ay $2.',
'largefileserver'             => 'Mas malaki ang talaksan kaysa nakatakdang papahintulutan ng serbidor.',
'emptyfile'                   => 'Mukhang walang laman ang talaksan (file) na ikinarga mo. Maaaring dahil ito sa maling pagkapasok ng pangalan ng talaksan.  Paki tingin kung gusto mo talagang ikarga ang talaksan na ito.',
'fileexists'                  => "Mayroon ng talaksan na ganitong pangalan, paki tingin ang '''<tt>[[:$1]]</tt>''' kung tiyak ka na babaguhin ito.
[[$1|thumb]]",
'filepageexists'              => "Ang pahina ng paglalarawan para sa talaksan na ito ay nalikha na sa '''<tt>[[:$1]]</tt>''', ngunit walang talaksang umiiral na may ganitong pangalan.
Hindi lilitaw ang buod na ipapasok mo sa pahina ng paglalarawan.
Para lumitaw ang buod mo doon, kailangan mong kinakamay na baguhin ito.
[[$1|thumb]]",
'fileexists-extension'        => "Mayroon talaksan na ganitong pangalan: [[$2|thumb]]
* Pangalan ng ikakargang talaksan: '''<tt>[[:$1]]</tt>'''
* Pangalan ng mayroon nang talaksan: '''<tt>[[:$2]]</tt>'''
Pumili ng ibang pangalan.",
'fileexists-thumbnail-yes'    => "Mukhang pinaliit ''(thumbnail)'' na larawan ang talaksan. [[$1|thumb]]
Paki tingin ang talaksan '''<tt>[[:$1]]</tt>'''.
Kung ang tinignan na talaksan ay ang kaparehong larawan ng orihinal na laki, hindi na kailangang magkarga ng panibagong ''thumbnail''.",
'file-thumbnail-no'           => "Nagsisimula ang pangalan ng talaksan sa '''<tt>$1</tt>'''.  Tila ito'y isang larawan na may pinaliit na sukat''(thumbnail)''.
Kung mayroon ang larawang ito ng pinakamataas na resolution, ikarga ito, kung hindi paki palitan ang pangalan ng talaksan.",
'fileexists-forbidden'        => 'Umiiral na ang isang talaksang may ganitong pangalan, at hindi maaaring patungan.
Kung nais mo pa ring ikarga pataas ang iyong talaksan, paki bumalik lamang at gumamit ng isang bagong pangalan.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Mayroon nang ganitong talaksan sa binabahaging repositoryo;
bumalik at ikarga ang talaksan na ito sa bagong pangalan. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Ang talaksang ito ay isang kakambal ng sumusunod na {{PLURAL:$1|talaksan|mga talaksan}}:',
'file-deleted-duplicate'      => 'Dating nabura ang isang talaksang katulad ng talaksang ito ([[$1]]).  Dapat mong suriin ang kasaysayan ng pagbubura ng talaksang iyon bago magpatuloy sa muling pagkarga nito.',
'uploadwarning'               => 'Babala sa pagkakarga',
'uploadwarning-text'          => 'Pakibago ang nasa ibabang paglalarawan ng talaksan at subukan muli.',
'savefile'                    => 'Sagipin ang talaksan',
'uploadedimage'               => 'ikinarga ang "[[$1]]"',
'overwroteimage'              => 'nagkarga ng isang bagong bersyon ng "[[$1]]"',
'uploaddisabled'              => 'Hindi pinagana ang mga pagkarga',
'copyuploaddisabled'          => 'Ikargang paitaas ayon sa hindi pinaganang URL.',
'uploadfromurl-queued'        => 'Inilagay sa pila ang iyong pagkakargang paitaas.',
'uploaddisabledtext'          => 'Hindi pinagana ang mga pagkakarga ng talaksan.',
'php-uploaddisabledtext'      => 'Hindi pinapagana ang mga pagkakarga ng talaksang PHP.  Pakisuri ang katakdaan ng mga_pagkakarga_ng_talaksan.',
'uploadscripted'              => 'Naglalaman ang talaksan na ito ng HTML o kodigong script na maaaring mali ang pagkaintindi ng isang web browser.',
'uploadvirus'                 => 'Naglalaman ng virus ang talaksan! Mga detalye: $1',
'upload-source'               => 'Pinagmulang talaksan',
'sourcefilename'              => 'Pangalan ng panggagalingang talaksan:',
'sourceurl'                   => 'Pinagmulang URL:',
'destfilename'                => 'Pangalan ng patutunguhang talaksan:',
'upload-maxfilesize'          => 'Pinakamataas na sukat ng talaksan: $1',
'upload-description'          => 'Paglalarawan ng talaksan',
'upload-options'              => 'Ikargang pataas ang mga pagpipilian',
'watchthisupload'             => 'Bantayan ang talaksang ito',
'filewasdeleted'              => 'Isang talaksan na may ganitong pangalan ay naikarga dati at nabura. Kailangan mong tingnan ang $1 bago magpatuloy sa pagkarga nito muli.',
'upload-wasdeleted'           => "'''Babala: Kinakarga mo ang isang talaksan na nabura na.'''

Ikunsidera mo kung nararapat ba na ipagpatuloy ang pagkarga ng talaksang ito.
Ibinigay ang tala ng pagbura ng talaksang ito para konbinyente:",
'filename-bad-prefix'         => "Ang talaksan na ikakarga mo ay nagsisimula sa '''\"\$1\"''', na isang hindi naglalarawang pangalan na karaniwang tinatakda ng mga kamerang digital. Paki pili ang isang mas naglalarawang pangalan para sa iyong talaksan.",
'upload-success-subj'         => 'Matagumpay na pagkakarga',
'upload-success-msg'          => 'Matagumpay ang ikinarga mo mula sa [$2].  Makukuha ito mula rito: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Problema sa pagkarga',
'upload-failure-msg'          => 'May suliranin sa iyong pagkakarga mula sa [$2]:

$1',
'upload-warning-subj'         => 'Babala sa pagkakarga',
'upload-warning-msg'          => 'May suliranin sa iyong pagkakarga mula sa [$2]. Maaari kang magbalik sa [[Special:Upload/stash/$1|upload form]] upang itama ang suliraning ito.',

'upload-proto-error'        => 'Maling protokolo',
'upload-proto-error-text'   => 'Nangangailangan ang malayong pagkarga ng mga URL na nagsisimula sa <code>http://</code> o <code>ftp://</code>.',
'upload-file-error'         => 'Panloob na kamalian',
'upload-file-error-text'    => 'Isang panloob na kamalian ang nangyari nang sinubukang likhain ang isang pansamantalang talaksan sa ibabaw ng tagapaghain.  Makipag-ugnayan sa isang [[Special:ListUsers/sysop|tagapangasiwa]].',
'upload-misc-error'         => 'Hindi nalalamang kamalian sa pagkakarga',
'upload-misc-error-text'    => 'Naganap ang isang hindi nalalamang kamalian sa panahon ng pagkakarga.
Pakisuri kung katanggap-tanggap at mapupuntahan ang URL at subukin uli.
Kapag nagpatuloy ang suliranin, makipagugnayan sa isang [[Special:ListUsers/sysop|tagapangasiwa]].',
'upload-too-many-redirects' => 'Naglalaman ng napakaraming panlipat ng pupuntahan ang URL',
'upload-unknown-size'       => 'Hindi nalalamang laki',
'upload-http-error'         => 'Naganap ang isang kamaliang pang-HTTP: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Hindi pinayagan ang pagpunta',
'img-auth-nopathinfo'   => 'Nawawalang PATH_INFO.
Ang tagapaghain mo ay hindi nakatakdang na maipasa ang kabatirang ito.
Maaaring pang-CGI ito at hindi makatangkilik ng img_auth.
Tingnan ang http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Ang hiniling na daan ay wala sa loob ng isinaayos na direktoryo ng pagkarga.',
'img-auth-badtitle'     => 'Hindi nagawang makabuo ng tanggap na pamagat mula sa "$1".',
'img-auth-nologinnWL'   => 'Hindi ka nakalagda at ang "$1" ay wala sa puting talaan.',
'img-auth-nofile'       => 'Hindi umiiral ang talaksang "$1".',
'img-auth-isdir'        => 'Sinusubok mong puntahan ang direktoryong "$1".
Tanging ang pagpunta lang sa talaksan ang pinapayagan.',
'img-auth-streaming'    => 'Pinapaagos ang "$1".',
'img-auth-public'       => 'Ang tungkulin ng img_auth.php ay maglabas ng mga talaksan mula sa isang pribadong wiki.
Isinaayos ang wiking ito bilang isang pampublikong wiki.
Para sa pinakamatatag na kaligtasan, hindi pinagana ang img_auth.php.',
'img-auth-noread'       => 'Hindi makakapunta ang tagagamit para mabasa ang "$1".',

# HTTP errors
'http-invalid-url'      => 'Hindi tanggap na URL: $1',
'http-invalid-scheme'   => 'Hindi tinatangkilik ang mga URL na may panukalang "$1".',
'http-request-error'    => 'Nabigo ang kahilingang HTTP dahil sa hindi kilalang kamalian.',
'http-read-error'       => 'Kamalian sa pagbasa ng HTTP.',
'http-timed-out'        => 'Huminto ang kahilingang HTTP.',
'http-curl-error'       => 'Kamalian sa pagsalok ng URL: $1',
'http-host-unreachable' => 'Hindi marating ang URL.',
'http-bad-status'       => 'Nagkaroon ng suliranin habang hinihiling ang HTTP na: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Hindi marating ang URL',
'upload-curl-error6-text'  => 'Hindi marating ang ibinigay na URL.
Pakisuring muli kung tama ang URL at kung buhay ang sityo/sayt.',
'upload-curl-error28'      => 'Pahinga sa pagkakarga',
'upload-curl-error28-text' => 'Napakatagal bago tumugon ang sityo/sayt.
Pakisuri kung buhay ang sayt, maghintay ng kaunti at subukin uli.
Maaaring ibigin mong subukin uli sa isang hindi gaanong abalang panahon.',

'license'            => 'Paglilisensya:',
'license-header'     => 'Paglilisensya',
'nolicense'          => 'Walang napili',
'license-nopreview'  => '(Walang makuhang paunang tingin)',
'upload_source_url'  => ' (isang tanggap at napupuntahan ng publikong URL)',
'upload_source_file' => ' (isang talaksan sa iyong kompyuter)',

# Special:ListFiles
'listfiles-summary'     => 'Ipinapakita nitong natatanging pahinang ang lahat ng naikargang mga talaksan.
Bilang naitakda ipinapakita sa itaas ng talaan ang huling ikinargang mga talaksan.
Mababago ang pagkakapangkat-pangkat sa pamamagitan ng pagpindot sa isang paulo ng pahabang kahanayan.',
'listfiles_search_for'  => 'Hanapin ang pangalan ng midya:',
'imgfile'               => 'talaksan',
'listfiles'             => 'Talaan ng talaksan',
'listfiles_thumb'       => 'Kagyat',
'listfiles_date'        => 'Petsa',
'listfiles_name'        => 'Pangalan',
'listfiles_user'        => 'Tagagamit',
'listfiles_size'        => 'Sukat',
'listfiles_description' => 'Paglalarawan',
'listfiles_count'       => 'Mga bersyon',

# File description page
'file-anchor-link'          => 'Talaksan',
'filehist'                  => 'Kasaysayan ng talaksan',
'filehist-help'             => 'Pindutin ang isang petsa/oras para makita ang anyo ng talaksan noong panahong iyon.',
'filehist-deleteall'        => 'burahin lahat',
'filehist-deleteone'        => 'burahin',
'filehist-revert'           => 'ibalik',
'filehist-current'          => 'kasalukuyan',
'filehist-datetime'         => 'Petsa/Oras',
'filehist-thumb'            => "Kagyat (''thumbnail'')",
'filehist-thumbtext'        => "Kagyat (''thumbnail'') para sa bersyon mula noong $1",
'filehist-nothumb'          => "Walang kagyat (''thumbnail'')",
'filehist-user'             => 'Tagagamit',
'filehist-dimensions'       => 'Mga sukat',
'filehist-filesize'         => 'Sukat ng talaksan',
'filehist-comment'          => 'Puna/Kumento',
'filehist-missing'          => 'Nawawala ang talaksan',
'imagelinks'                => 'Mga kawing ng talaksan',
'linkstoimage'              => 'Nakakawing ang sumusunod na {{PLURAL:$1|pahina|$1 mga pahina}} sa talaksang ito.',
'linkstoimage-more'         => 'Mahigit sa $1 {{PLURAL:$1|pahina|mga pahina}} ang nakakawing sa talaksang ito.
Ipinapakita sa sumusunod na talaan ang {{PLURAL:$1|unang pahina lamang|unang $1 mga pahina lamang}} na nakakawing sa talaksang ito.
Mayroong makukuhang [[Special:WhatLinksHere/$2|buong talaan]].',
'nolinkstoimage'            => 'Walang pahinang nakakawing sa talaksang ito.',
'morelinkstoimage'          => 'Tingnan ang [[Special:WhatLinksHere/$1|mas marami pang mga kawing]] para sa pahinang ito.',
'redirectstofile'           => 'Tumuturo ang sumusunod na {{PLURAL:$1|talaksan|$1 mga talaksan}} patungo sa talaksang ito:',
'duplicatesoffile'          => 'Ang sumusunod na {{PLURAL:$1|file is a duplicate|$1 mga talaksan ay mga kapareho}} ng talaksang ito ([[Special:FileDuplicateSearch/$2|mas marami pang mga detalye]]):',
'sharedupload'              => 'Ang talaksang ito ay nagmula sa $1 at maaaring gamitin ng iba pang mga proyekto.',
'sharedupload-desc-there'   => 'Ang talaksang ito ay nagmula sa $1 at maaaring gamitin sa ibang mga proyekto.
Pakitingnan ang [$2 pahina ng paglalarawan ng talaksan] para sa iba pang kabatiran.',
'sharedupload-desc-here'    => 'Ang talaksang ito ay nagmula sa $1 at maaaring gamitin sa ibang mga proyekto.
Ang paglalarawang nasa ibabaw ng [$2 pahina ng paglalarawan ng talaksan] nito doon ay ipinapakita sa ibaba.',
'filepage-nofile'           => 'Walang talaksang umiiral sa pangalang ito.',
'filepage-nofile-link'      => 'Walang talaksang umiiral sa pangalang ito, ngunit maaari mong [$1 kargahin ito].',
'uploadnewversion-linktext' => 'Magkarga ng isang bagong bersyon ng talaksang ito',
'shared-repo-from'          => 'mula sa $1',
'shared-repo'               => 'isang pinagsasaluhang repositoryo',

# File reversion
'filerevert'                => 'Ibalik sa dati ang $1',
'filerevert-backlink'       => '← $1',
'filerevert-legend'         => 'Ibalik ang talaksan',
'filerevert-intro'          => '<span class="plainlinks">Ibinabalik mo sa dati ang \'\'\'[[Media:$1|$1]]\'\'\' patungo sa [$4 bersyon noong $3, $2].</span>',
'filerevert-comment'        => 'Dahilan:',
'filerevert-defaultcomment' => 'Ibinalik sa dating bersyon mula pa noong $2, $1',
'filerevert-submit'         => 'Ibalik',
'filerevert-success'        => '<span class="plainlinks">Ibinalik sa dati ang \'\'\'[[Media:$1|$1]]\'\'\' patungo sa [$4 bersyon noong $3, $2].</span>',
'filerevert-badversion'     => 'Walang nakaraang lokal/katutubong bersyon para sa talaksang ito na may kasamang ibinigay na <i>tatak ng oras</i>.',

# File deletion
'filedelete'                  => 'Burahin ang $1',
'filedelete-backlink'         => '← $1',
'filedelete-legend'           => 'Burahin ang talaksan',
'filedelete-intro'            => "Buburahin mo na ang talaksang '''[[Media:$1|$1]]''' na kasama ang lahat ng kasaysayan nito.",
'filedelete-intro-old'        => '<span class="plainlinks">Binubura mo ang bersyon ng \'\'\'[[Media:$1|$1]]\'\'\' mula noong [$4 $3, $2].</span>',
'filedelete-comment'          => 'Dahilan:',
'filedelete-submit'           => 'Burahin',
'filedelete-success'          => "Nabura na ang '''$1'''.",
'filedelete-success-old'      => "Nabura ang bersyon ng '''[[Media:$1|$1]]''' mula noong $2, $3.",
'filedelete-nofile'           => "Hindi umiiral ang '''$1'''.",
'filedelete-nofile-old'       => "Walang sininop/nakaarkibong bersyon ng '''$1''' na may tinukoy na mga katangian.",
'filedelete-otherreason'      => 'Iba pa/karagdagang dahilan:',
'filedelete-reason-otherlist' => 'Iba pang dahilan',
'filedelete-reason-dropdown'  => '*Karaniwang mga dahilan ng pagbubura
** Paglabag sa karapatang-ari
** Nagkadalawang talaksan',
'filedelete-edit-reasonlist'  => 'Baguhin ang mga dahilan ng pagbura',
'filedelete-maintenance'      => 'Pansamantalang hindi pinagana ang pagbura at pagpapnumbalik ng mga talaksan habang nagpapanatili ng kaayusan.',

# MIME search
'mimesearch'         => 'Maghanap ng MIME',
'mimesearch-summary' => 'Pinapagana ng pahinang ito ang pagsasala ng mga talaksan para sa kanyang uri ng MIME. Pagpapasok: uringnilalaman/mababangkabahaginguri, hal. <tt>image/jpeg</tt>.',
'mimetype'           => 'Uri ng MIME:',
'download'           => "magkargang-pakuha ng talaksan (''download'')",

# Unwatched pages
'unwatchedpages' => 'Mga pahinang hindi binabantayan',

# List redirects
'listredirects' => 'Tala ng mga karga',

# Unused templates
'unusedtemplates'     => 'Hindi ginagamit na mga suleras',
'unusedtemplatestext' => 'Tinatala ng pahinang ito ang lahat ng mga pahina sa espasyong pangalan ng suleras na hindi kasama sa ibang pahina. Tandaan na tingnan ang ibang mga ugnay sa mga suleras bago burahin ito.',
'unusedtemplateswlh'  => 'ibang mga ugnay',

# Random page
'randompage'         => 'Pahinang walang-pili',
'randompage-nopages' => 'Walang mga pahina sa sumusunod na {{PLURAL:$2|ngalan-espasyo|mga ngalan-espasyo}}: $1.',

# Random redirect
'randomredirect'         => 'Alinmang panuto',
'randomredirect-nopages' => 'Walang mga panuto sa pangalan-espasyong "$1".',

# Statistics
'statistics'                   => 'Mga estadistika',
'statistics-header-pages'      => 'Mga estadistika ng pahina',
'statistics-header-edits'      => 'Baguhin ang mga estadistika',
'statistics-header-views'      => 'Tingnan ang mga estadistika',
'statistics-header-users'      => 'Mga estadistika sa mga tagagamit',
'statistics-header-hooks'      => 'Ibang mga estadistika',
'statistics-articles'          => 'Mga pahina ng nilalaman',
'statistics-pages'             => 'Mga pahina',
'statistics-pages-desc'        => 'Lahat ng mga pahina sa loob ng wiki, kabilang ang mga pahina ng usapan, mga panuto, atbp.',
'statistics-files'             => 'Ikinargang mga talaksan',
'statistics-edits'             => 'Naihanda na ang mga pagbabago ng pahina mula sa {{SITENAME}}',
'statistics-edits-average'     => 'Karaniwang pagbabago sa bawat pahina',
'statistics-views-total'       => 'Kalahatang pagdayo',
'statistics-views-peredit'     => 'Pagtingin sa bawat pagbabago',
'statistics-users'             => 'Mga nakatalang [[Special:ListUsers|tagagamit]]',
'statistics-users-active'      => 'Mga masusugid na tagagamit <small>(mga nakatalang mang-aambag sa buwang ito)</small>',
'statistics-users-active-desc' => 'Mga tagagamit na nagsagawa ng isang galaw/gawain sa huling {{PLURAL:$1|araw|$1 mga araw}}',
'statistics-mostpopular'       => 'Mga pinakarinarayong pahina',

'disambiguations'      => 'Mga pahina ng paglilinaw',
'disambiguationspage'  => 'Template:disambig',
'disambiguations-text' => "Ang sumusunod ay mga pahinang may ugnay (link) sa isang '''pahinang naglilinaw'''.
Dapat silang umugnay sa tamang paksa<br />
Tinuturing ang isang pahina bilang pahinang naglilinaw kung ginagamit nito ang isang suleras (template) na nakaugnay mula sa [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'Mga dobleng karga',
'doubleredirectstext'        => 'Nagtatala ang pahinang ito ng mga pahinang pumupunta sa iba pang mga pahinang nililipatan.  Naglalaman ang bawat hanay ng mga kawing sa una ang pangalawang kapupuntahan, maging ng puntiryang pangalawang kapupuntahan, na karaniwang "tunay" na puntiryang pahina, na dapat kinatuturuan ng unang pupuntahan.
Nasugpo na ang mga ipinasok na <del>inekisan</del>.',
'double-redirect-fixed-move' => 'Inilipat na ang [[$1]], isa na ngayon itong panuto/panturo patungo sa [[$2]]',
'double-redirect-fixer'      => 'Tagapagayos ng panuto/panturo',

'brokenredirects'        => 'Bali/putol na mga panuto o panturo',
'brokenredirectstext'    => 'Ang sumusunod na mga panturo papunta sa ibang pahina ay kumakawing patungo sa mga pahinang hindi pa umiiral.',
'brokenredirects-edit'   => 'baguhin',
'brokenredirects-delete' => 'burahin',

'withoutinterwiki'         => 'Mga pahinang walang mga ugnay pang-wika',
'withoutinterwiki-summary' => 'Walang ugnay ang mga sumusunod ng pahina sa mga ibang bersyon na wika:',
'withoutinterwiki-legend'  => 'Unlapi',
'withoutinterwiki-submit'  => 'Ipakita',

'fewestrevisions' => 'Mga artikulong may kakaunting pagbabago',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|mga byte}}',
'ncategories'             => '$1 {{PLURAL:$1|kategorya|mga kategorya}}',
'nlinks'                  => '$1 {{PLURAL:$1|ugnay|mga ugnay}}',
'nmembers'                => '$1 {{PLURAL:$1|kasapi|mga kasapi}}',
'nrevisions'              => '$1 {{PLURAL:$1|pagbabago|mga pagbabago}}',
'nviews'                  => '$1 {{PLURAL:$1|nakita|mga nakikita}}',
'specialpage-empty'       => 'Walang resulta para sa ulat na ito.',
'lonelypages'             => 'Mga inulilang pahina',
'lonelypagestext'         => 'Ang mga sumusunod ng mga pahina ay hindi nakaturo mula sa ibang mga pahina sa wiking ito.',
'uncategorizedpages'      => 'Hindi nakakategoryang mga pahina',
'uncategorizedcategories' => 'Hindi nakakategoryang mga kategorya',
'uncategorizedimages'     => 'Hindi nakakategoryang mga larawan',
'uncategorizedtemplates'  => 'Hindi nakakategoryang mga suleras',
'unusedcategories'        => 'Hindi ginagamit na mga kategorya',
'unusedimages'            => 'Hindi ginagamit na mga talaksan',
'popularpages'            => 'Mga popular na pahina',
'wantedcategories'        => 'Kinakailangang mga kategorya',
'wantedpages'             => 'Kinakailangang mga pahina',
'wantedpages-badtitle'    => 'Hindi tanggap na pamagat sa loob ng pangkat ng kinalabasan: $1',
'wantedfiles'             => 'Ninanais na mga talaksan',
'wantedtemplates'         => 'Ninanais na mga suleras',
'mostlinked'              => 'Pinakamaraming ugnay sa mga pahina',
'mostlinkedcategories'    => 'Pinakamaraming ugnay sa mga kategorya',
'mostlinkedtemplates'     => 'Pinakamaraming ugnay sa mga suleras',
'mostcategories'          => 'Mga artikulong may pinakamaraming kategorya',
'mostimages'              => 'Pinakamaraming ugnay sa mga larawan',
'mostrevisions'           => 'Mga artikulong may pinakamaraming pagbabago',
'prefixindex'             => 'Lahat ng mga pahinang may unlapi',
'shortpages'              => 'Mga maiikling pahina',
'longpages'               => 'Mga mahahabang pahina',
'deadendpages'            => 'Mga pahinang walang panloob na ugnay (internal link)',
'deadendpagestext'        => "Ang mga sumusunod na mga pahina'y hindi umuugnay sa ibang mga pahina sa wiking ito.",
'protectedpages'          => 'Mga nakaprotektang pahina',
'protectedpages-indef'    => 'Mga walang katiyakang proteksyon lamang',
'protectedpages-cascade'  => 'Baita-baitang na mga panananggalang lamang',
'protectedpagestext'      => 'Nakasanggalang ang sumusunod na mga pahina laban sa paglipat o pagbabago',
'protectedpagesempty'     => 'Sa kasalukuyan, walang mga pahinang nakasanggalang na may ganitong mga parametro.',
'protectedtitles'         => 'Nakasanggalang na mga pamagat',
'protectedtitlestext'     => 'Ang sumusunod ay mga pamagat na nakaprotekta mula sa pagkalikha.',
'protectedtitlesempty'    => 'Walang pamagat ang kasalukuyang nakaprotekta sa binigay na parametro.',
'listusers'               => 'Tala ng tagagamit',
'listusers-editsonly'     => 'Ipakita lamang ang mga tagagamit na gumawa/nakagawa na ng mga pagbabago',
'listusers-creationsort'  => 'Pagsama-samahin ayon sa petsa ng pagkakalikha',
'usereditcount'           => '$1 {{PLURAL:$1|pagbabago|mga pagbabago}}',
'usercreated'             => 'Nalikha noong $1 sa ika-$2',
'newpages'                => 'Mga bagong pahina',
'newpages-username'       => 'Bansag:',
'ancientpages'            => 'Mga pinakalumang pahina',
'move'                    => 'Ilipat',
'movethispage'            => 'Ilipat itong pahina',
'unusedimagestext'        => "Umiiral ang sumusunod na mga talaksan subalit hindi nakabaon sa anumang pahina.
Pakitandaan lamang na ang iba mga websayt ay maaaring nakakawing sa isang talaksang may isang tuwirang URL, kaya't maaaring nakatala pa rin dito bagaman masigla pa ring ginagamit.",
'unusedcategoriestext'    => 'Mayroon ang mga sumusunod na mga kategorya bagaman walang ibang artikulo o kategorya ang gumagamit sa mga ito.',
'notargettitle'           => 'Walang pupuntahan',
'notargettext'            => 'Hindi ka nagbigay ng pupuntahang pahina o tagagamit upang gumana ito.',
'nopagetitle'             => 'Wala ganyang pahina',
'nopagetext'              => 'Wala ang binigay mong pahina',
'pager-newer-n'           => '{{PLURAL:$1|mas bagong 1|mas bagong $1}}',
'pager-older-n'           => '{{PLURAL:$1|mas lumang 1|mas lumang $1}}',
'suppress'                => 'Tagapagingat-tago',

# Book sources
'booksources'               => 'Mapagkukuhanang mga aklat',
'booksources-search-legend' => 'Maghanap ng mapagkukunang aklat',
'booksources-go'            => 'Punta',
'booksources-text'          => 'Matatagpuan sa ibaba ang mga tala ng mga ugnay sa ibang mga websayt na nagbebenta ng bago at nagamit na mga aklat, at maaring mayroon din
na iba pang impormasyon tungkol sa mga aklat na hinahanap mo:',
'booksources-invalid-isbn'  => 'Tila mukhang hindi yata katanggap-tanggap ang ibinigay na ISBN; pakisuri kung may mga kamalian ang pagkakasip/pagkakakopya mula sa orihinal na pinagmulan.',

# Special:Log
'specialloguserlabel'  => 'Tagagamit:',
'speciallogtitlelabel' => 'Pamagat:',
'log'                  => 'Mga talaan',
'all-logs-page'        => 'Lahat ng mga pampublikong tala',
'alllogstext'          => 'Pinagsama-samang mga pagpapakita ng makukuhang mga talaan ng {{SITENAME}}.
Maaari mong pakitirin/pakiputin ang ipinapakita sa pamamagitan ng pagpili ng uri ng mga talaan, ang pangalan ng tagagamit (maselan ang pagmamakiniliya ng panitik), o ang naaapektuhang pahina (maselan din ang pagmamakinilya ng panitik).',
'logempty'             => 'Walang katumbas na bagay sa talaan.',
'log-title-wildcard'   => 'Hanapin ang mga pamagat na nagsisimula sa tekstong ito',

# Special:AllPages
'allpages'          => 'Lahat ng pahina',
'alphaindexline'    => '$1 hanggang $2',
'nextpage'          => 'Susunod na pahina ($1)',
'prevpage'          => 'Nakaraang pahina ($1)',
'allpagesfrom'      => 'Pinapakita ang mga pahina na nagsisimula sa:',
'allpagesto'        => 'Ipakita ang mga pahinang nagtatapos sa:',
'allarticles'       => 'Lahat ng mga pahina',
'allinnamespace'    => 'Lahat ng mga pahina ($1 espasyo ng pangalan)',
'allnotinnamespace' => 'Lahat ng mga pahina (wala sa $1 espasyo ng pangalan)',
'allpagesprev'      => 'Nakaraan',
'allpagesnext'      => 'Susunod',
'allpagessubmit'    => 'Ipatupad/Sumige',
'allpagesprefix'    => 'Ipakita ang mga pahinang may unlaping:',
'allpagesbadtitle'  => 'Ang binagay na pamagat ng pahina ay hindi tinatanggap o may unlapi na tumuturo sa ibang wika o wiki.  Maaaring naglalaman ito ng isa o higit pa na mga karakter na hindi ginagamit bilang pamagat.',
'allpages-bad-ns'   => 'Wala sa {{SITENAME}} ang espasyo ng pangalang "$1".',

# Special:Categories
'categories'                    => 'Mga kategorya',
'categoriespagetext'            => 'Naglalaman ang sumusunod na {{PLURAL:$1|kategorya|mga kategorya}} ng mga pahina o midya.
Hindi ipinapakita rito ang [[Special:UnusedCategories|mga kategoryang hindi ginagamit]].
Tingnan din ang [[Special:WantedCategories|ninanais na mga kategorya]].',
'categoriesfrom'                => 'Ipakita ang mga kategoryang nagsisimula sa:',
'special-categories-sort-count' => 'ayusin sa pamamagitan ng bilang',
'special-categories-sort-abc'   => 'ayusin sa pamamagitan ng alpabeto',

# Special:DeletedContributions
'deletedcontributions'             => 'Naburang ambag ng tagagamit',
'deletedcontributions-title'       => 'Naburang ambag ng tagagamit',
'sp-deletedcontributions-contribs' => 'mga ambag',

# Special:LinkSearch
'linksearch'       => 'Panlabas na mga kawing',
'linksearch-pat'   => 'Huwaran ng hanap',
'linksearch-ns'    => 'Pangalang espasyo',
'linksearch-ok'    => 'Hanapin',
'linksearch-text'  => 'Maaaring gamitin ang mga "barahang panghalili/pamalit" (mga \'\'wildcard\'\') katulad ng "*.wikipedia.org".<br />
Sinusuportahang mga protokolo: <tt>$1</tt>',
'linksearch-line'  => '$1 nakakawing/nakaugnay mula sa $2',
'linksearch-error' => "Lilitaw lamang ang mga \"barahang-pamalit\" (''wildcard'') sa simula ng pangunahin/punong-abalang pangalan.",

# Special:ListUsers
'listusersfrom'      => 'Ipakita ang mga tagagamit na nagsisimula sa:',
'listusers-submit'   => 'Ipakita',
'listusers-noresult' => 'Walang nahanap na tagagamit.',
'listusers-blocked'  => '(hinarang)',

# Special:ActiveUsers
'activeusers'            => 'Tala ng mga aktibong tagagamit',
'activeusers-intro'      => 'Isa itong talaan ng mga tagagamit na nagkaroon ng ilang uri ng galaw sa loob ng huling $1 {{PLURAL:$1|araw|mga araw}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|pagbabago|mga pagbabago}} sa loob ng huling {{PLURAL:$3|araw|$3 mga araw}}',
'activeusers-from'       => 'Ipakita ang mga tagagamit simula sa:',
'activeusers-hidebots'   => 'Itago ang mga bots',
'activeusers-hidesysops' => 'Itago ang mga tagapangasiwa',
'activeusers-noresult'   => 'Walang natagpuang mga tagagamit.',

# Special:Log/newusers
'newuserlogpage'              => 'Talaan ng paglikha ng tagagamit',
'newuserlogpagetext'          => 'Isa itong talaan ng mga paglikha ng tagagamit.',
'newuserlog-byemail'          => 'Ipinadala ang hudyat sa pamamagitan ng e-liham',
'newuserlog-create-entry'     => 'Bagong tagagamit',
'newuserlog-create2-entry'    => 'nalikha ang bagong akawnt na $1',
'newuserlog-autocreate-entry' => 'Awtomatikong nalikha ang akawnt',

# Special:ListGroupRights
'listgrouprights'                      => 'Mga uri ng tagagamit',
'listgrouprights-summary'              => 'Ang sumusunod ay isang talaan ng mga pangkat ng tagagamit na binigyang kahulugang sa wiking ito, kasama ang kanilang mga kaugnay na mga karapatan.
Maaaring may mga [[{{MediaWiki:Listgrouprights-helppage}}|karagdagang kabatiran]] tungkol sa bawat isang mga karapatan sa [[{{MediaWiki:Listgrouprights-helppage}}]].',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Ipinagkaloob na karapatan</span> * <span class="listgrouprights-revoked">Nabawing karapatan</span>',
'listgrouprights-group'                => 'Pangkat',
'listgrouprights-rights'               => 'Mga karapatan',
'listgrouprights-helppage'             => 'Help:Mga pangkat ng karapatan',
'listgrouprights-members'              => '(tala ng mga kasapi)',
'listgrouprights-addgroup'             => 'Maaaring idagdag ang {{PLURAL:$2|pangkat|mga pangkat}} na: $1',
'listgrouprights-removegroup'          => 'Maaaring tanggalin ang {{PLURAL:$2|pangkat|mga pangkat}} na: $1',
'listgrouprights-addgroup-all'         => 'Maaaring idagdag ang lahat ng mga pangkat',
'listgrouprights-removegroup-all'      => 'Maaaring tanggalin ang lahat ng mga pangkat',
'listgrouprights-addgroup-self'        => 'Idagdag ang {{PLURAL:$2|pangkat|mga pangkat}} na magmamay-ari ng akawnt: $1',
'listgrouprights-removegroup-self'     => 'Tanggalin ang {{PLURAL:$2|pangkat|mga pangkat}} mula sa sariling akawnt: $1',
'listgrouprights-addgroup-self-all'    => 'Idagdag ang lahat ng mga pangkat sa sariling akawnt',
'listgrouprights-removegroup-self-all' => 'Alisin ang lahat ng mga pangkat mula sa sariling akawnt',

# E-mail user
'mailnologin'          => 'Walang adres na mapagpapadalahan',
'mailnologintext'      => 'Kailangan mong [[Special:UserLogin|lumagda]] at magkaroon ng tanggap na e-liham sa iyong [[Special:Preferences|mga kagustuhan]] para makapagpadala ng e-liham sa ibang mga tagagamit.',
'emailuser'            => 'Padalhan ng e-liham ang tagagamit',
'emailpage'            => 'Magpadala ng e-liham sa tagagamit',
'emailpagetext'        => 'Magagamit mo ang pormularyo sa ibaba para makapagpadala ng mensahe sa pamamagitan ng isang e-liham para sa tagagamit na ito.
Ang ipinasok mong adres ng e-liham sa [[Special:Preferences|iyong mga kagustuhan ng tagagamit]] ay lilitaw bilang adres na "Mula kay" ng e-liham, para tuwirang makatugon sa iyo ang nakatanggap.',
'usermailererror'      => 'Pagkakamaling sanhi ng pagkakabalik ng liham mula sa puntirya:',
'defemailsubject'      => 'E-liham ng {{SITENAME}}',
'usermaildisabled'     => 'Hindi pinagana ang e-liham ng tagagamit',
'usermaildisabledtext' => 'Hindi maaaring magpadala ng e-liham sa ibang mga tagagamit sa wiki na ito.',
'noemailtitle'         => 'Walang adres ng e-liham',
'noemailtext'          => 'Ang tagagamit na ito ay hindi tumukoy ng isang tanggap na adres ng e-liham.',
'nowikiemailtitle'     => 'Walang pinapahintulutang e-liham',
'nowikiemailtext'      => 'Pinili ng tagagamit na ito na huwag makatanggap ng e-liham mula sa ibang mga tagagamit.',
'email-legend'         => 'Magpadala ng e-liham patungo sa isa pang tagagamit ng {{SITENAME}}',
'emailfrom'            => 'Mula kay:',
'emailto'              => 'Para kay:',
'emailsubject'         => 'Paksa:',
'emailmessage'         => 'Mensahe:',
'emailsend'            => 'Ipadala',
'emailccme'            => 'Padalhan ako ng sipi ng aking mensahe sa pamamagitan ng e-liham.',
'emailccsubject'       => 'Kopya ng iyong mensahe sa $1: $2',
'emailsent'            => 'Naipadala na ang e-liham',
'emailsenttext'        => 'Naipadala na ang mensahe ng iyong e-liham.',
'emailuserfooter'      => 'Ipinadala ang e-liham na ito ni $1 para kay $2 sa pamamagitan ng tungkuling "Magpadala ng e-liham" na nasa {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Nag-iiwan ng mensaheng pangsistema.',
'usermessage-editor'  => 'Mensahero ng sistema',

# Watchlist
'watchlist'            => 'Mga binabantayan ko',
'mywatchlist'          => 'Bantayan ko',
'watchlistfor2'        => 'Para sa $1 $2',
'nowatchlist'          => 'Wala kang pahinang binabantayan.',
'watchlistanontext'    => 'Paki $1 upang makita o mabago ang mga aytem sa iyong binabantayan.',
'watchnologin'         => 'Di ka naka-lagda',
'watchnologintext'     => 'Dapat kang [[Special:UserLogin|nakalagda]] upang mabago ang talaan mo ng mga binabantayan.',
'addedwatch'           => 'Dinagdag na sa mga Babantayan',
'addedwatchtext'       => "Dinagdag na ang pahinang \"[[:\$1]]\" sa iyong [[Special:Watchlist|Babantayan]].
Makikita doon ang lahat ng mga susunod na pagbabago sa pahinang ito pati na ang usapang pahina, at ang pahina ay makikitang sa '''malalaking titik''' ('''''bold''''') sa [[Special:RecentChanges|tala ng mga huling binago]] para madaling makita.",
'removedwatch'         => 'Tinigil na ang pagbabantay',
'removedwatchtext'     => 'Ang pahinang "[[:$1]]" ay tinanggal na mula sa [[Special:Watchlist|iyong talaan ng binabantayan]].',
'watch'                => 'Bantayan ito',
'watchthispage'        => 'Bantayan ang pahinang ito',
'unwatch'              => 'Huwag bantayan',
'unwatchthispage'      => 'Tigil Bantay',
'notanarticle'         => 'Hindi isang nilalamang pahina',
'notvisiblerev'        => 'Nabura na ang pagbabago',
'watchnochange'        => 'Wala sa binabantayan mo ang binago sa oras na nakikita.',
'watchlist-details'    => '{{PLURAL:$1|$1 pahinang|$1 mga pahinang}} nasa iyong talaan ng mga binabantayan, hindi binibilang ang mga pahina ng usapan.',
'wlheader-enotif'      => '* Umiiral ang pagpapahayag sa pamamagitan ng e-liham.',
'wlheader-showupdated' => "* Ipinapakitang may '''makakapal na mga panitik''' ang nabagong/binagong mga pahina mula pa noong huli mong pagdalaw sa kanila",
'watchmethod-recent'   => 'sinusuri ang kamakailan lamang na mga pagbabago para sa binabantayang mga pahina',
'watchmethod-list'     => 'sinusuri ang binabantayang mga pahina para sa mga kamakailan lamang na mga pagbabago',
'watchlistcontains'    => 'Naglalaman ng $1 {{PLURAL:$1|pahina|mga pahina}} ang iyong talaan ng mga binabantayan.',
'iteminvalidname'      => "May suliranin ang bagay na '$1', hindi tanggap na pangalan...",
'wlnote'               => "Nasa ibaba ang {{PLURAL:$1|pinakahuling pagbabago|pinakahuling '''$1''' mga pagbabago}} sa loob ng huling {{PLURAL:$2|oras|'''$2''' mga oras}}.",
'wlshowlast'           => 'Ipakita ang huling $1 mga oras $2 mga araw $3',
'watchlist-options'    => 'Mga pagpipilian para sa talaan ng mga binabantayan',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Isinasama sa mga binabantayan...',
'unwatching' => 'Tinatanggal mula sa mga binabantayan...',

'enotif_mailer'                => 'Tagapagpadala ng mga Pahayag ng {{SITENAME}}',
'enotif_reset'                 => 'Tatakan ang lahat ng pahina bilang nadalaw na',
'enotif_newpagetext'           => 'Isa itong bagong pahina.',
'enotif_impersonal_salutation' => 'Tagagamit ng {{SITENAME}}',
'changed'                      => 'binago',
'created'                      => 'nilikha',
'enotif_subject'               => 'Ang pahinang $PAGETITLE sa {{SITENAME}} ay $CHANGEDORCREATED ni $PAGEEDITOR',
'enotif_lastvisited'           => 'Tingnan ang $1 para sa lahat ng mga pagbabago magmula noong huling pagdalaw mo.',
'enotif_lastdiff'              => 'Tingnan ang $1 para makita ang pagbabagong ito.',
'enotif_anon_editor'           => 'hindi nakikilalang tagagamit $1',
'enotif_body'                  => 'Mahal na $WATCHINGUSERNAME,


Ang pahinang $PAGETITLE ng {{SITENAME}} ay $CHANGEDORCREATED noong $PAGEEDITDATE ni $PAGEEDITOR, tingnan ang $PAGETITLE_URL para sa pangkasalukuyang rebisyon.

$NEWPAGE

Buod mula sa patnugot: $PAGESUMMARY $PAGEMINOREDIT

Makipagugnayan sa patnugot:
liham: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Wala nang iba pang mga pagpapahayag sa pagkakataon ng pagkakaroon ng karagdagang mga pagbabago maliban na lamang kung dadalawin mo ang pahinang ito.
Maaari mo ring muling itakda ang mga watawat na pangpag-uulat para sa lahat ng mga pahinang binabantayan mo sa loob ng iyong talaan ng mga binabantayan.

             Ang iyong palakaibigang sistemang pangpag-uulat ng {{SITENAME}}

--
Para baguhin ang mga pagtatakda ng iyong talaan ng mga binabantayan, puntahan ang
$UNWATCHURL

Balik-tugon at karagdagang tulong:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Burahin ang pahina',
'confirm'                => 'Tiyakin',
'excontent'              => "ang dating nilalaman ay: '$1'",
'excontentauthor'        => "ang nilalaman ay: '$1' (at ang tanging nag-ambag ay si '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "nilalaman bago nablangko: '$1'",
'exblank'                => 'walang laman ang pahina',
'delete-confirm'         => 'Burahin "$1"',
'delete-legend'          => 'Burahin',
'historywarning'         => "'''Babala''': May kasaysayan ang pahinang buburahin mo na tinatayang may $1 {{PLURAL:$1|pagbabago|mga pagbabago}}:",
'confirmdeletetext'      => 'Lubos mo nang buburahin ang pahinang ito pati ang kalahatan ng kasaysayan nito.
Pakitiyak lamang na ito ang nais mong gawin, na nauunawaan mo ang mga kahihinatnan, at ginagawa mo ito alinsunod sa [[{{MediaWiki:Policy-url}}|patakaran]].',
'actioncomplete'         => 'Naisakatuparan na ang gawain',
'actionfailed'           => 'Hindi nagtagumpay ang galaw',
'deletedtext'            => 'Nabura na ang "$1".  Tingnan ang $2 para sa talaan ng kamakailan lamang na mga pagbubura.',
'deletedarticle'         => 'binura ang "[[$1]]"',
'suppressedarticle'      => 'pinigil/sinupil ang "[[$1]]"',
'dellogpage'             => 'Talaan ng pagbubura',
'dellogpagetext'         => 'Nasa ibaba ang isang talaan ng pinakakamailan lamang na mga pagbubura.',
'deletionlog'            => 'tala ng pagbubura',
'reverted'               => 'Ibinalik sa mas sinaunang pagbabago',
'deletecomment'          => 'Dahilan:',
'deleteotherreason'      => 'Iba pa/karagdagang dahilan:',
'deletereasonotherlist'  => 'Ibang dahilan',
'deletereason-dropdown'  => '*Pangkaraniwang mga dahilan ng pagbura
** Kahilingan ng may-akda
** Paglabag sa karapatang-ari/kopirayt
** Bandalismo',
'delete-edit-reasonlist' => 'Baguhin ang mga dahilan ng pagbura',
'delete-toobig'          => 'May isang malaking kasaysayan ng pagbabago ang pahinang ito, mahigit sa $1 {{PLURAL:$1|pagbabago|mga pagbabago}}.
Ipanagbabawal ang pagbura ng ganyang mga pahina upang maiwasan ang hindi sinasadyang pagantala/paggambala sa {{SITENAME}}.',
'delete-warning-toobig'  => 'May malaking kasaysayan ng pagbabago ang pahinang ito, mahigit sa $1 {{PLURAL:$1|pagbabago|mga pagbabago}}.
Maaaring makagambala/makaabala sa pagpapatakbo sa kalipunan ng dato ng {{SITENAME}};
magpatuloy na may pagiingat.',

# Rollback
'rollback'          => 'Mga pagbabagong may kaugnayan sa pagpapagulong na pabalik sa (mas) dati',
'rollback_short'    => 'Pagulunging pabalik sa (mas) dati',
'rollbacklink'      => 'pagulunging pabalik sa (mas) dati',
'rollbackfailed'    => 'Nabigo ang pagpapagulong na pabalik sa (mas) dati',
'cantrollback'      => 'Hindi maibalik ang pagbabago; tanging ang may-akda lamang ng pahinang ito ang huling tagapagambag/tagapaglathala.',
'alreadyrolled'     => 'Hindi mapagulong na pabalik sa dati ang huling pagbabago ng [[$1]] ni ([[User talk:$2|Usapan]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
mayroon nang ibang taong nagbago o nagpagulong pabalik sa dati ng pahina.

Ang huling pagbabago sa pahina ay ginawa ni [[User:$3|$3]] ([[User talk:$3|Usapan]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Ang buod ng pagbabago ay: \"''\$1''\".",
'revertpage'        => 'Ibinalik ang mga pagbabago ni [[Special:Contributions/$2|$2]] ([[User talk:$2|Usapan]]) patungo sa huling rebisyon ni [[User:$1|$1]]',
'revertpage-nouser' => 'Ibinalik ang mga pagbabago ni (tinanggal ang bansag) patungo sa huling rebisyon ni [[User:$1|$1]]',
'rollback-success'  => 'Ibinalik ang mga pagbabago ni $1; ibinalik sa huling bersyon ni $2.',

# Edit tokens
'sessionfailure-title' => 'Nabigong pulong',
'sessionfailure'       => "Tila mayroong suliraning may kaugnayan sa iyong sesyon/panahon ng pagkakalagda;
Kinansela ang galaw/gawaing ito bilang pagiingat laban sa pagnanakaw (panghahaydyak) ng sesyon/panahon.
Pakipindot ang pindutang \"ibalik\" (''back'') at ikarga uli ang pinanggalingan mong pahina, sumubok uli pagkaraan.",

# Protect
'protectlogpage'              => 'Talaan ng pagsasanggalang',
'protectlogtext'              => 'Nasa ibaba ang isang talaan ng mga pagkandado at pagtanggal na mga pagkandado ng pahina.
Tingnan ang [[Special:ProtectedPages|talaan ng nakasanggalang na mga pahina]] para sa talaan ng mga pangkasalukuyang gumaganang mga pagsasanggalang ng pahina.',
'protectedarticle'            => 'ipinagsanggalang ang "[[$1]]"',
'modifiedarticleprotection'   => 'binago ang antas ng panananggalang para sa "[[$1]]"',
'unprotectedarticle'          => 'tinanggal sa panananggalang ang "[[$1]]"',
'movedarticleprotection'      => 'inilipat ang pagtatakdang pampanananggalang mula sa "[[$2]]" patungong "[[$1]]"',
'protect-title'               => 'Palitan ang antas ng panananggalang para sa "$1"',
'prot_1movedto2'              => 'Inilipat ang [[$1]] patungo sa [[$2]]',
'protect-backlink'            => '← $1',
'protect-legend'              => 'Pagtibayin/tiyakin ang panananggalang',
'protectcomment'              => 'Dahilan:',
'protectexpiry'               => 'Magtatapos sa:',
'protect_expiry_invalid'      => 'Hindi tanggap/hindi tama ang oras ng pagtatapos.',
'protect_expiry_old'          => 'Nasa nakaraan ang oras ng pagtatapos.',
'protect-unchain-permissions' => 'Huwag ikandado ang iba pang mga pagpipilian ng pagprutekta',
'protect-text'                => "Maaari mong tingnan at baguhin dito ang antas ng pananananggalang para sa pahinang '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Hindi mo maaaring baguhin ang mga antas ng panananggalang habang may pagharang/paghadlang.
Narito ang mga pangkasalukuyang pagtatakda para sa pahinang '''$1''':",
'protect-locked-dblock'       => "Hindi mababago ang mga antas ng panananggalang dahil sa isang umiiral na pagkandado ng kalipunan ng dato.
Narito ang pangkasalukuyang mga pagtatakda para sa pahinang '''$1''':",
'protect-locked-access'       => "Wala kapahintulutan ang iyong kuwenta/patnugutan/akawnt para makapagbago ng mga antas ng panananggalang ng pahina.
Narito ang pangkasalukuyang mga pagtatakda para sa pahinang '''$1''':",
'protect-cascadeon'           => 'Kasalukuyang nakasanggalang na ang pahinang ito dahil kabilang/kasama ito sa sumusunod na {{PLURAL:$1|pahinang may|mga pahinang may}} buhay/umiiral na baita-baitang na mga panananggalang.
Maaari mong baguhin ang antas ng panananggalang ng pahina, ngunit hindi ito makakaapekto sa baita-baitang na panananggalang.',
'protect-default'             => 'Pahintulutan ang lahat ng mga tagagamit',
'protect-fallback'            => 'Nangangailangan ng kapahintulutang "$1"',
'protect-level-autoconfirmed' => 'Hadlangan ang bago at hindi nagpapatalang mga tagagamit',
'protect-level-sysop'         => "Mga tagapangasiwa (''sysop'') lamang",
'protect-summary-cascade'     => 'baita-baitang',
'protect-expiring'            => 'mawawalan ng bisa sa $1 (UTC)',
'protect-expiry-indefinite'   => 'walang katiyakan',
'protect-cascade'             => 'Ipagsanggalang ang mga pahinang kasama/kabilang sa pahinang ito (baita-baitang na panananggalang)',
'protect-cantedit'            => 'Hindi mo mababago ang mga antas ng panananggalang ng pahinang ito, dahil wala kang pahintulot para baguhin ito.',
'protect-othertime'           => 'Ibang oras:',
'protect-othertime-op'        => 'ibang oras',
'protect-existing-expiry'     => 'Umiiral na panahon/oras ng pagtatapos: $3, $2',
'protect-otherreason'         => 'Iba pa/karagdagang dahilan:',
'protect-otherreason-op'      => 'Iba pang dahilan',
'protect-dropdown'            => "*Mga pangkaraniwang dahilan ng pagsasanggalang
** Labis na bandalismo/pambababoy
** Labis na bilang ng mga mapanlusob na patalastas (''spam'')
** Hindi kapakipakinabang na alitan hinggil sa pagbabago
** Pahinang may mataas na antas ng daloy (matrapik)",
'protect-edit-reasonlist'     => 'Mga dahilan ng panananggalang laban sa pagbabago',
'protect-expiry-options'      => '1 oras:1 hour,1 araw:1 day,1 linggo:1 week,2 linggo:2 weeks,1 buwan:1 month,3 buwan:3 months,6 buwan:6 months,1 taon:1 year,walang hanggan:infinite',
'restriction-type'            => 'Pahintulot:',
'restriction-level'           => 'Antas ng kabawalan:',
'minimum-size'                => 'Pinakamaliit na sukat',
'maximum-size'                => 'Pinakamalaking sukat',
'pagesize'                    => '(mga byte)',

# Restrictions (nouns)
'restriction-edit'   => 'Pagpatnugot',
'restriction-move'   => 'Ilipat',
'restriction-create' => 'Likhain',
'restriction-upload' => 'Magkarga',

# Restriction levels
'restriction-level-sysop'         => 'buong nakasanggalang',
'restriction-level-autoconfirmed' => 'bahagyang nakasanggalang',
'restriction-level-all'           => 'anumang antas',

# Undelete
'undelete'                     => 'Tingnan ang mga binurang pahina',
'undeletepage'                 => 'Tingnan at ibalik ang mga naburang mga pahina',
'undeletepagetitle'            => "'''Binubuo ang sumusunod ng binurang pagbabago ng [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Tingnan ang binurang mga pahina',
'undeletepagetext'             => 'Ang sumusunod na {{PLURAL:$1|pahina ay nabura na subalit |$1 mga pahina ay nabura na subalit}} nananatili pa rin sa sinupan/arkibo at maaaring bang ibalik mula sa pagkakabura.
Maaaring palagiang linisin o tanggalan ng laman ang sinupan/arkibo.',
'undelete-fieldset-title'      => 'Ibalik ang mga pagbabago',
'undeleteextrahelp'            => "Para maibalik ang kabuoan ng kasaysayan ng pahina, iwanang walang laman ang mga kahong lagayan ng mga tsek at pindutin ang '''''Ibalik'''''. Para maisagawa ang pagbabalik na may pagpili, lagyan ng tsek ang mga kahong may kaugnayan sa pagpapabalik ng mga pagbabago at pindutin ang '''''Ibalik'''''.
Malilinis ang pook ng kumento/puna at iba pang mga kahong lagayan ng mga tsek kapag pinindot ang '''''Magtakda uli'''''.",
'undeleterevisions'            => 'Sininop/nilagay sa sinupan o arkibo ang $1 {{PLURAL:$1|pagbabago|mga pagbabago}}',
'undeletehistory'              => 'Kapag ibinalik mo ang pahina, ibabalik ang lahat ng mga pagbabago sa kasaysayan.
Kapag nalikha ang isang bagong pahinang may katulad na pangalan mula noong pagbura, lilitaw ang naibalik na mga pagbabago sa sinaunang kasaysayan.',
'undeleterevdel'               => 'Hindi gagawin ang pagpapabalik kung magreresulta sa bahaging pagkakabura ng itaas ng pahina o ng pagbabago sa talaksan.
Sa ganitong mga pagkakataon, dapat mong tanggalin ang tsek o huwag itago ang pinakabagong naburang pagbabago.',
'undeletehistorynoadmin'       => 'Nabura ang artikulong ito. Ipinapakita ang dahilan sa buod sa ibaba, kasama ang mga detalye ng mga tagagamit na binago ang pahinang ito bago nabura. Makikita lamang ng mga tagapangasiwa ang aktwal ng teksto ng mga naburang pagbabagong ito.',
'undelete-revision'            => 'Naburang pagbabago ng $1 (mula noong $4, sa $5) ni $3:',
'undeleterevision-missing'     => 'Inbalido o nawawalang pagbabago. Maaaring mayroon kang masamang ugnay (link), o ibinalik o tinanggal mula sa arkibo ang pagbabago.',
'undelete-nodiff'              => 'Walang mahanap na nakaraang pagbabago.',
'undeletebtn'                  => 'Ibalik',
'undeletelink'                 => 'tingnan/ibalik muli',
'undeleteviewlink'             => 'tingnan',
'undeletereset'                => 'I-reset',
'undeleteinvert'               => 'Baligtarin ang pagpili/pilian',
'undeletecomment'              => 'Dahilan:',
'undeletedarticle'             => 'ibinalik "[[$1]]"',
'undeletedrevisions'           => '{{PLURAL:$1|1 pagbabago|$1 mga pagbabagong}} naibalik na',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 pagbabago|$1 mga pagbabago}} at {{PLURAL:$2|1 talaksang|$2 mga talaksang}} naibalik na',
'undeletedfiles'               => '{{PLURAL:$1|1 talaksang|$1 mga talaksang}} naibalik na',
'cannotundelete'               => 'Hindi matagumpay ang pagpapabalik mula sa pagkakabura; maaaring may isang nakauna na sa pagpapabalik ng pahina mula sa pagkakabura.',
'undeletedpage'                => "'''Naibalik na ang $1'''

Tingnan ang [[Special:Log/delete|talaan ng pagbubura]] para sa isang talaan ng mga kamakailan lamang na mga pagbubura at mga pagbabalik mula sa pagkakabura.",
'undelete-header'              => 'Tingnan ang [[Special:Log/delete|talaan ng pagbubura]] para sa kamakailan lamang na  binura/naburang mga pahina.',
'undelete-search-box'          => 'Hanapin ang binura/naburang mga pahina',
'undelete-search-prefix'       => 'Ipakita ang mga pahinang nagsisimula sa:',
'undelete-search-submit'       => 'Maghanap',
'undelete-no-results'          => 'Walang mahanap na kaparis/katulad na mga pahina mula sa sinupan/arkibo ng mga nabura.',
'undelete-filename-mismatch'   => 'Hindi maibalik mula sa pagkakabura ang pagbabago ng talaksang may kasamang tatak ng oras na $1: hindi nagtutugma ang pangalan ng talaksan',
'undelete-bad-store-key'       => 'Hindi maibalik mula sa pagkakabura ang pagbabagong pangtalaksang may tatak ng oras na $1: nawawala na ang talaksan bago pa maganap ang pagbura.',
'undelete-cleanup-error'       => 'Mali ang pagbura sa hindi ginagamit na talaksan ng sinupan/arkibong "$1".',
'undelete-missing-filearchive' => 'Hindi naibalik mula sa pagkakabura ang sinupan/arkibo ng talaksang may ID na $1 dahil wala ito sa kalipunan ng dato.  Maaaring naibalik na ito dati mula sa pagkakabura.',
'undelete-error-short'         => 'May mali sa pagkakabalik mula sa pagkakabura ng talaksang: $1',
'undelete-error-long'          => 'Nakaranas ng mga kamalian habang ibinabalik mula sa pagkakabura ang talaksang:

$1',
'undelete-show-file-confirm'   => 'Nakatitiyak ka bang ibig mong tanawin ang isang nabura nang pagbabago ng talaksang "<nowiki>$1</nowiki>" mula $2 noong $3?',
'undelete-show-file-submit'    => 'Oo',

# Namespace form on various pages
'namespace'      => 'Espasyo ng pangalan:',
'invert'         => 'Baligtarin and pinili',
'blanknamespace' => '(Pangunahin)',

# Contributions
'contributions'       => 'Mga ambag ng tagagamit',
'contributions-title' => 'Mga ambag ng tagagamit na si $1',
'mycontris'           => 'Aking mga ginawa',
'contribsub2'         => 'Para kay $1 ($2)',
'nocontribs'          => 'Walang pagbabagong nakita sa binigay na kondisyon.',
'uctop'               => ' (itaas)',
'month'               => 'Mula sa buwan (at nauna):',
'year'                => 'Mula sa taon (at nauna):',

'sp-contributions-newbies'             => 'Ipakita ang mga ambag ng mga bagong kuwenta lamang',
'sp-contributions-newbies-sub'         => 'Para sa mga bagong kuwenta',
'sp-contributions-newbies-title'       => 'Mga ambag ng tagagamit para sa mga bagong kuwenta/akawnt',
'sp-contributions-blocklog'            => 'Tala ng paglipat',
'sp-contributions-deleted'             => 'naburang mga ambag ng tagagamit',
'sp-contributions-logs'                => 'mga tala',
'sp-contributions-talk'                => 'usapan',
'sp-contributions-userrights'          => 'pamamahala ng mga karapatan ng tagagamit',
'sp-contributions-blocked-notice'      => 'Kasalukuyang hinarang ang tagagamit na ito.
Ang pinakahuling entrada sa talaan  ng pagharang ay ibinigay sa ibaba para sa pagsangguni:',
'sp-contributions-blocked-notice-anon' => 'Kasalukuyang hinarang ang adres ng IP na ito.
Ang pinakahuling entrada sa talaan  ng pagharang ay ibinigay sa ibaba para sa pagsangguni:',
'sp-contributions-search'              => 'Maghanap ng ambag',
'sp-contributions-username'            => 'IP Address o bansag:',
'sp-contributions-toponly'             => 'Ipakita lang ang mga pamamatnugot na mga huling rebisyon',
'sp-contributions-submit'              => 'Hanapin',

# What links here
'whatlinkshere'            => 'Mga nakaturo dito',
'whatlinkshere-title'      => 'Mga pahinang kumakawing sa $1',
'whatlinkshere-page'       => 'Pahina:',
'linkshere'                => "Nakakawing ang sumusunod na mga pahina sa '''[[:$1]]''':",
'nolinkshere'              => "Walang pahinang nakakawing sa '''[[:$1]]'''.",
'nolinkshere-ns'           => "Walang pahinang nakakawing sa '''[[:$1]]''' mula sa loob ng napiling espasyo ng pangalan.",
'isredirect'               => 'pahinang panturo/panuto',
'istemplate'               => 'pagsasali',
'isimage'                  => 'kawing ng/sa larawan',
'whatlinkshere-prev'       => '{{PLURAL:$1|nakaraang|nakaraang $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|susunod|susunod na $1}}',
'whatlinkshere-links'      => '← mga kawing',
'whatlinkshere-hideredirs' => '$1 mga pagturo/pagpapanuto',
'whatlinkshere-hidetrans'  => '$1 paglipat-sali (transklusyon)',
'whatlinkshere-hidelinks'  => '$1 mga kawing',
'whatlinkshere-hideimages' => '$1 mga kawing ng/sa larawan',
'whatlinkshere-filters'    => 'Mga pansala',

# Block/unblock
'blockip'                         => 'Harangin/hadlangan ang tagagamit',
'blockip-title'                   => 'Harangin ang tagagamit',
'blockip-legend'                  => 'Iharang ang tagagamit',
'blockiptext'                     => 'Gamitin ang mga lahok sa ibaba upang maharang ang akses sa pagsulat mula sa isang espesipikong IP address o bansag.
Gawin lamang ito para maiwasan ang bandalismo, at napapaloob sa [[{{MediaWiki:Policy-url}}|patakaran]].
Punan ang espesipikong dahilan sa ibaba (halimbawa, magbanggit ng partikular na mga pahina na nagkaroon ng bandalismo).',
'ipaddress'                       => 'Direksyong IP:',
'ipadressorusername'              => 'Direksyong IP o bansag:',
'ipbexpiry'                       => 'Pagkawalang-bisa:',
'ipbreason'                       => 'Dahilan:',
'ipbreasonotherlist'              => 'Ibang dahilan',
'ipbreason-dropdown'              => '*Mga karaniwang dahilan sa paghaharang
** Pagpasok ng hindi totoong impormasyon
** Pag-alis ng nilalaman mula sa mga pahina
** Walang-itinatanging paglalagay ng mga kawing panlabas
** Pagpasok ng impormasyong walang kabuluhan/satsat sa mga pahina
** Ugaling nananakot/pagligalig
** Pagmamalabis ng maramihang kuwenta
** Hindi kanais-nais na bansag',
'ipbanononly'                     => 'Harangin/hadlangan lamang ang mga hindi nakikilalang mga tagagamit',
'ipbcreateaccount'                => 'Hadlangan ang paglikha ng kuwenta',
'ipbemailban'                     => 'Hadlangan ang tagagamit sa pagpapadala ng e-liham',
'ipbenableautoblock'              => 'Kusang harangin/hadlangan ang huling adres ng IP na ginamit ng tagagamit na ito, at anumang susunod pang mga IP na susubukan nilang gamitin para makapagbago/mamatnugot',
'ipbsubmit'                       => 'Harangin/hadlangan ang tagagamit na ito',
'ipbother'                        => 'Ibang oras:',
'ipboptions'                      => '2 oras:2 hours,1 araw:1 day,3 araw:3 days,1 linggo:1 week,2 linggo:2 weeks,1 buwan:1 month,3 buwan:3 months,6 buwan:6 months,1 taon:1 year,walang hanggan:infinite',
'ipbotheroption'                  => 'iba',
'ipbotherreason'                  => 'Iba/karagdagang dahilan:',
'ipbhidename'                     => 'Itago ang pangalan ng tagagamit mula sa pagbabago at mga talaan',
'ipbwatchuser'                    => 'Bantayan ang pahinang pantagagamit at pahina ng usapan ng tagagamit na ito',
'ipballowusertalk'                => 'Pahintulutan ang tagagamit na ito na baguhin ang sariling pahina ng usapan habang hinahadlangan/may paghaharang',
'ipb-change-block'                => 'Muling harangin/hadlangan ang tagagamit na ginagamitan ng ganitong mga pagtatakda',
'badipaddress'                    => 'Hindi tanggap na adres ng IP',
'blockipsuccesssub'               => 'Matagumpay ang pagharang/paghadlang',
'blockipsuccesstext'              => 'Hinadlangan ang  [[Special:Contributions/$1|$1]].<br />
Tingnan ang [[Special:IPBlockList|talaan ng mga hinadlangang IP]] upang makita ang mga paghadlang.',
'ipb-edit-dropdown'               => 'Baguhin ang mga dahilan sa pagharang',
'ipb-unblock-addr'                => 'Tanggalin ang pagkaharang ng $1',
'ipb-unblock'                     => 'Tanggalin ang pagkaharang ng isang bansag o IP address',
'ipb-blocklist'                   => 'Tingnan ang umiiral na mga pagharang/paghadlang',
'ipb-blocklist-contribs'          => 'Mga ambag ni $1',
'unblockip'                       => 'Tanggalin ang pagharang/paghadlang sa tagagamit',
'unblockiptext'                   => 'Gamitin ang pormularyo sa ibaba upang ibalik ang akses ng pagsulat sa isang dating nakaharang na IP address o bansag.',
'ipusubmit'                       => 'Tanggalin ang paghadlang na ito',
'unblocked'                       => 'Natanggal sa pagkaharang ang tagagamit na [[User:$1|$1]]',
'unblocked-id'                    => 'Tinanggal na ang pagharang/paghadlang na $1',
'ipblocklist'                     => 'Tala ng mga hinarang na mga IP address at bansag',
'ipblocklist-legend'              => 'Hanapin ang isang hinarang na tagagamit',
'ipblocklist-username'            => 'Bansag o IP address:',
'ipblocklist-sh-userblocks'       => '$1 mga paghadlang o pagharang sa kuwenta/akawnt',
'ipblocklist-sh-tempblocks'       => '$1 pansamantalang mga pagharang/paghadlang',
'ipblocklist-sh-addressblocks'    => '$1 isahang pagharang/paghalang sa IP',
'ipblocklist-submit'              => 'Hanapin',
'ipblocklist-localblock'          => 'Lokal na pagharang',
'ipblocklist-otherblocks'         => 'Ibang {{PLURAL:$1|harang|mga harang}}',
'blocklistline'                   => '$1, $2 hinarang si $3 (magtatapos sa $4)',
'infiniteblock'                   => 'walang katapusan',
'expiringblock'                   => 'magtatapos sa $1 sa ganap na $2',
'anononlyblock'                   => 'di kilala lamang',
'noautoblockblock'                => 'hindi gumagana ang awtomatikong pagharang',
'createaccountblock'              => 'Hinarang ang paglikha ng akawnt',
'emailblock'                      => 'Hinarang/hinadlangan ang e-liham',
'blocklist-nousertalk'            => 'hindi mo mababago ang iyong pansariling pahina ng usapan',
'ipblocklist-empty'               => 'Walang laman ang talaan ng pagharang/paghadlang.',
'ipblocklist-no-results'          => 'Nakaharang ang hiniling na IP address o bansag.',
'blocklink'                       => 'harangin/hadlangan',
'unblocklink'                     => 'tanggalin ang pagharang/paghadlang',
'change-blocklink'                => 'baguhin ang pagharang/paghadlang',
'contribslink'                    => 'ambag',
'autoblocker'                     => 'Kusang hinarang dahil ang iyong adres ng IP ay kamakailan lamang na ginamit ni "[[User:$1|$1]]".
Ang dahilang ibinigay para sa pagharang kay $1 ay: "$2"',
'blocklogpage'                    => 'Tala ng pagharang',
'blocklog-showlog'                => 'Dati nang naharang ang tagagamit na ito.
Ibinigay sa ibaba ang talaan ng pagharang upang mapagsanggunian:',
'blocklog-showsuppresslog'        => 'Hinadlangang ang tagagamit na ito at dati nang itinago.
Ang tala ng pagpigil ay ibinigay sa ibaba upang mapagsanggunian:',
'blocklogentry'                   => 'hinarang/hinadlangan si [[$1]] na may oras/panahon ng pagtatapos na $2 $3',
'reblock-logentry'                => 'binago ang itinakdang pagharang/paghadlang kay [[$1]] na may oras/panahon ng pagtatapos na $2 $3',
'blocklogtext'                    => 'Tala ito ng paghaharang at pagpapawawalang bisa ng pagharang/paghadlang.
Hindi nakatala rito ang mga awtomatiko/kusang hinarang/hinadlangang mga adres ng IP.
Tingnan ang [[Special:IPBlockList|talaan ng mga hinarang na/hinadlangang IP]] para sa talaan ng pangkasalukuyang gumagana pang mga pinagbabawalan at mga pagharang/paghadlang.',
'unblocklogentry'                 => 'tinanggal ang pagharang/paghadlang kay $1',
'block-log-flags-anononly'        => 'mga di-kilalang tagagamit lamang',
'block-log-flags-nocreate'        => 'Nakapatay ang paglikha ng akawnt',
'block-log-flags-noautoblock'     => 'Nakapatay ang awtomatikong pagharang',
'block-log-flags-noemail'         => 'hinadlangan/hinarang ang e-liham',
'block-log-flags-nousertalk'      => 'hindi mo mababago ang iyong pansariling pahina ng usapan',
'block-log-flags-angry-autoblock' => 'pinaandar ang pinainam/pinagibayong kusang paghadlang o awtomatikong pagharang',
'block-log-flags-hiddenname'      => 'nakatago ang pangalan ng tagagamit',
'range_block_disabled'            => 'Hindi gumagana ang kakayahan ng tagapangasiwa para makalikha ng mga pagharang/paghadlang na may sakop.',
'ipb_expiry_invalid'              => 'Hindi tama ang oras ng pagtatapos.',
'ipb_expiry_temp'                 => 'Kinakailangang pamalagian ang mga nakatagong paghadlang ng pangalan ng tagagamit.',
'ipb_hide_invalid'                => 'Hindi nasupil ang akawnt na ito; maaaring mayroon itong napakaraming mga pagbabago.',
'ipb_already_blocked'             => 'Nakaharang na ang "$1"',
'ipb-needreblock'                 => '== Hinarang/hinadlangan na ==
Hinarang/hinadlangan na si $1.  Ibig mo bang baguhin ang mga pagtatakda?',
'ipb-otherblocks-header'          => 'Ibang {{PLURAL:$1|harang|mga harang}}',
'ipb_cant_unblock'                => 'Kamalian: Hindi natagpuan ang ID ng pagharang/paghadlang na $1.  Maaaring natanggal na ang pagkakaharang nito/paghahadlang dito.',
'ipb_blocked_as_range'            => 'Mali: Hindi diretsong nakaharang ang IP na $1 at hindi maaaring tanggalin sa pagkakaharang. Bagaman, bahagi ito sa sakop na $2, na maaaring tanggalin sa pagkaharang.',
'ip_range_invalid'                => 'Hindi tamang sakop ng IP.',
'ip_range_toolarge'               => 'Hindi pinapayagan ang mga saklaw ng pagharang na mas malaki kaysa /$1.',
'blockme'                         => 'Harangin ako',
'proxyblocker'                    => 'Pangharang ng proxy',
'proxyblocker-disabled'           => 'Nakapatay ang pagharang sa proxy.',
'proxyblockreason'                => 'Hinarang ang IP address mo dahil bukas na proxy ito. Makipag-ugnayan sa iyong tagabigay ng serbisyong Internet o suportang teknikal at ipaalam sa kanila itong seryesong suliranin sa seguridad.',
'proxyblocksuccess'               => 'Tapos na.',
'sorbsreason'                     => 'Nakalista ang IP address mo bilang isang bukas na proxy sa DNSBL na ginagamit ng sayt na ito.',
'sorbs_create_account_reason'     => 'Nakalista ang IP address mo bilang isang bukas na proxy sa DNSBL na ginagamit ng sayt na ito. Hindi ka makakalikha ng akawnt',
'cant-block-while-blocked'        => 'Hindi mo mahahadlangan/mahaharang ang ibang mga tagagamit habang hinahadlangan ka.',
'cant-see-hidden-user'            => 'Ang tagagamit na sinusubukan mong hadlangan ay naharang at naikubli na.
Dahil wala kang karapatang magkubli ng tagagamit, hindi mo makikita o mababago ang paghadlang sa tagagamit.',
'ipbblocked'                      => 'Hindi mo mahahadlangan o tanggalin ang hadlang ng ibang mga tagagamit, dahil hinadlangan ka rin',
'ipbnounblockself'                => 'Hindi ka pinahihintulutang tanggalin ang pagharang sa iyo.',

# Developer tools
'lockdb'              => 'Ikandado ang kalipunan ng datos',
'unlockdb'            => 'Buksan/tanggalin ang kandado ng kalipunan ng datos',
'lockdbtext'          => 'Maaantala ng pagkakandado ng kalipunan ng dato ang kakayahang magbago ng mga pahina ng lahat ng mga tagagamit, magbago ng kanilang mga kagustuhan, magbago ng kanilang mga talaan ng mga binabantayan, at iba pang mga bagay-bagay na nangangailangan ng mga pagbabago sa loob ng kalipunan ng dato.
Pakitiyak lamang kung ito ang nais mong gawin, at tatanggalin mo ang pagkakakandado ng kalipunan ng dato pagkatapos ng ginawa mong pagpapanatili.',
'unlockdbtext'        => 'Ang pagtatanggal ng kandado ng kalipunan ng dato ay makapagpapabalik sa kakayahang makapagbago ng mga pahina ng lahat ng mga tagagamit, magbago ng kanilang mga kagustuhan, magbago ng kanilang mga talaan ng mga binabantayan, at iba pang mga bagay-bagay na nangangailangan ng mga pagbabago sa loob ng kalipunan ng dato.
Pakitiyak kung ito ang nais mong gawin.',
'lockconfirm'         => 'Oo, nais ko talagang ikandado ang kalipunan ng dato.',
'unlockconfirm'       => 'Oo, nais ko talagang tanggalin ang kandado at buksan na ang kalipunan ng dato.',
'lockbtn'             => 'Ikandado ang kalipunan ng dato',
'unlockbtn'           => 'Tanggalin ang kandado at buksan na ang kalipunan ng dato',
'locknoconfirm'       => 'Hindi mo nilagyan ng tsek ang kahon ng kumpirmasyon/pagpapatotoo.',
'lockdbsuccesssub'    => 'Matagumpay ang pagkakandado ng kalipunan ng dato',
'unlockdbsuccesssub'  => 'Tinanggal ang kandado ng kalipunan ng dato',
'lockdbsuccesstext'   => 'Ikinandado ang kalipunan ng dato.<br />
Huwag kalimutang [[Special:UnlockDB|tanggalin ang kandado]] pagkaraan mong maisagawa ang pagpapanatili.',
'unlockdbsuccesstext' => 'Tinanggal na ang kandado at nabuksan na ang kalipunan ng dato.',
'lockfilenotwritable' => "Hindi masusulatan ang talaksang pangkandado ng kalipunan ng dato.
Para ikandado o tanggalin ang kandado ng kalipunan ng dato, kailangan nitong maging nasusulatan/masusulatan ng serbidor ng ''web''.",
'databasenotlocked'   => 'Hindi nakakandado ang kalipunan ng datos.',

# Move page
'move-page'                    => 'Ilipat ang $1',
'move-page-backlink'           => '← $1',
'move-page-legend'             => 'Ilipat ang pahina',
'movepagetext'                 => "Mapapalitan ang pangalan ng isang pahina kapag ginamit mo ang pormularyong nasa ibaba, malilipat ang lahat ng kasaysayan nito patungo sa bagong pangalan.
Magiging isang pahina ng panuto/panturo patungo sa bagong pamagat ang dati/lumang pangalan.
Maaari mong isapanahon ang mga panutong tumuturo sa orihinal na pamagat sa pamamagitan ng kusang pamamaraan (paraang awtomatiko).
Kung pipiliin mong huwag gawin ito, dapat mong tiyakin kung may [[Special:DoubleRedirects|dalawahan o doble]] o [[Special:BrokenRedirects|bali o putol na mga panturo]].
Tungkulin mong tiyakin kung magpapatuloy sa pagturo ang mga kawing patungo sa dapat nilang puntahan.

Tandaan '''hindi''' ililipat ang pahina kapag mayroon nang isang pahina sa bagong pamagat, maliban na lamang kung wala itong laman o isang panuto/panturo at walang nakaraang kasaysayan ng pagbabago.
Nangangahulugan ito na maaari mong muling pangalanan ang isang pahina pabalik sa kung saan ito  muling pinangalanan/pinalitan ng pangalan kung sakaling magkamali ka, at hindi mo maaaring patungan/pangibabawan ang isang umiiral na pahina.

'''BABALA!'''
Maaaring itong maging isang marahas at hindi inaaasang pagbabago para sa isang bantog na pahina;
pakitiyak na nauunawaan mo ang mga kahihinatnan nito bago magpatuloy.",
'movepagetalktext'             => "Kusa/awtomatikong ililipat din ang mga kasama/kakabit na mga kaugnay na mga pahina '''maliban na lamang kung''':
*Mayroon nang isang pahina ng usapang may laman na at umiiral na sa ilalim ng isang bagong pangalan, o
*Hindi mo nilagyan ng tsek ang kahong nasa ibaba.

Sa mga kasong ganoon, kailangan mong ilipat o pagsamahin/pagsanibin ang pahina sa manwal o kinakamay na paraan kung nanaisin.",
'movearticle'                  => 'Ilipat ang pahina:',
'moveuserpage-warning'         => "'''Babala:''' Ililipat mo ang isang pahina ng tagagamit. Pakitandaan na tanging ang pahina lamang ang malilipat at ''hindi'' babaguhin ang pangalan ng tagagamit.",
'movenologin'                  => 'Hindi nakalagda',
'movenologintext'              => 'Dapat na isa kang nagpatalang tagagamit at [[Special:UserLogin|nakalagdang papasok]] upang makapaglipat ng isang pahina.',
'movenotallowed'               => 'Wala kang permisong maglipat ng pahina.',
'movenotallowedfile'           => 'Wala kang pahintulot upang makapaglipat ng mga talaksan.',
'cant-move-user-page'          => 'Wala kang pahintulot para makapaglipat ng mga pahina ng tagagamit (bukod pa sa kabahaging mga pahina o subpahina).',
'cant-move-to-user-page'       => 'Wala kang pahintulot para makapaglipat ng isang pahina papunta sa isang pahina ng tagagamit (maliban na lamang sa isang kabahaging pahina o subpahina ng tagagamit).',
'newtitle'                     => 'Papunta sa bagong pamagat:',
'move-watch'                   => 'Bantayan ang pahinang ito',
'movepagebtn'                  => 'Ilipat ang pahina',
'pagemovedsub'                 => 'Matagumpay ang paglipat',
'movepage-moved'               => '\'\'\'Inilipat ang "$1" patungo sa "$2"\'\'\'',
'movepage-moved-redirect'      => 'Nalikha ang isang panturo patungo sa ibang pahina.',
'movepage-moved-noredirect'    => 'Pinigilan ang paglikha ng isang panturo.',
'articleexists'                => 'May umiiral nang pahinang may ganyang pangalan, o ang
pangalang pinili mo ay hindi tanggap.
Pumili muli ng ibang pangalan.',
'cantmove-titleprotected'      => 'Hindi mo malilipatan ang isang pahina sa lokasyong ito, dahil nakasanggalang sa paglikha ang baong pamagat',
'talkexists'                   => "'''Tagumpay na nailipat ang pahina mismo, ngunit hindi mailipat ang pahina ng usapan dahil mayroon ng ganito sa bagong pamagat. Ipagsama ito sa manwal na paraan.'''",
'movedto'                      => 'inilipat sa',
'movetalk'                     => 'Ilipat ang kaugnay na pahinang usapan',
'move-subpages'                => 'Ilipat ang kabahaging mga pahina (hanggang sa $1)',
'move-talk-subpages'           => 'Ilipat ang kabahaging mga pahina ng usapan (hanggang sa $1)',
'movepage-page-exists'         => 'Mayroon na ang pahinang $1 at hindi na ito awtomatikong mapapatungan.',
'movepage-page-moved'          => 'Nailipat na ang pahinang $1 sa $2.',
'movepage-page-unmoved'        => 'Hindi na mailipat ang pahinang $1 sa $2.',
'movepage-max-pages'           => 'Ang pinakamataas na $1 {{PLURAL:$1|pahina|mga pahina}} ay nailipat at wala nang maililipat ng awtomatiko.',
'1movedto2'                    => 'Ang [[$1]] ay inilipat sa [[$2]]',
'1movedto2_redir'              => 'Ang [[$1]] ay inilipat sa [[$2]] sa ibabaw ng pangkarga',
'move-redirect-suppressed'     => 'Sinupil ang pagturo papunta sa ibang pahina',
'movelogpage'                  => 'Tala ng paglipat',
'movelogpagetext'              => 'Sumusunod ang mga tala ng mga pahinang nailipat.',
'movesubpage'                  => '{{PLURAL:$1|Kabahaging pahina|Kabahaging mga pahina}}',
'movesubpagetext'              => 'Ang pahinang ito ay mayroong $1 {{PLURAL:$1|kabahaging pahina|kabahaging mga pahina}}ng ipinapakita sa ibaba.',
'movenosubpage'                => 'Ang pahinang ito ay walang kabahaging mga pahina.',
'movereason'                   => 'Dahilan:',
'revertmove'                   => 'ibalik',
'delete_and_move'              => 'Burahin at ilipat',
'delete_and_move_text'         => '==Kinakailangan ang pagbura==

Mayroon na ang pupuntahang artikulo na "[[$1]]". Nais mo bang burahin ito para magbigay daan para sa paglipat?',
'delete_and_move_confirm'      => 'Oo, burahin ang pahina',
'delete_and_move_reason'       => 'Binura upang makalipat',
'selfmove'                     => 'Magkatulad ang pinagmulan at pupuntahan ng mga titulo; hindi mailipat ang isang pahina sa kanyang sarili.',
'immobile-source-namespace'    => 'Hindi mailipat ang mga pahinang nasa espasyo ng pangalang "$1"',
'immobile-target-namespace'    => 'Hindi mailipat ang mga pahina patungo sa espasyo ng pangalang "$1"',
'immobile-target-namespace-iw' => "Hindi isang tanggap na puntirya para sa isang paglilipat ng pahina ang isang kawing na pang-''interwiki'' (ugnayang pangwiki).",
'immobile-source-page'         => 'Hindi naililipat ang pahinang ito.',
'immobile-target-page'         => 'Hindi makakalipat papunta sa ganyang kapupuntahang pamagat.',
'imagenocrossnamespace'        => 'Hindi mailipat ang talaksan patungo sa hindi pangtalaksang espasyo ng pangalan',
'nonfile-cannot-move-to-file'  => 'Hindi maililipat ang hindi talaksan sa puwang na pampangalan ng talaksan',
'imagetypemismatch'            => 'Hindi tumutugma sa uri nito ang bagong pandugtong/karugtong ng talaksan',
'imageinvalidfilename'         => 'Hindi tanggap ang patutunguhan/puntiryang pangalan ng talaksan.',
'fix-double-redirects'         => 'Isapanahon ang kahit anong panuto/panutong tumuturo sa orihinal na pamagat',
'move-leave-redirect'          => 'Mag-iwan ng isang panturo',
'protectedpagemovewarning'     => "'''Babala:''' Ikinandado ang pahinang ito upang mga tagagamit lamang na may karapatan ng tagapangasiwa ang makakapaglipat nito.
Ang pinakahuling entrada sa talaan ay ibinigay sa baba para sa iyong pagsasangguni:",
'semiprotectedpagemovewarning' => "'''Paunawa:''' Ikinandado ang pahinang ito upang mga nakatalang tagagamit lamang ang makakapaglipat nito.
Ang pinakahuling entrada sa talaan ay ibinigay sa baba para sa iyong pagsasangguni:",
'move-over-sharedrepo'         => '== Umiiral ang talaksan ==
Umiiral ang [[:$1]] sa tabihang ipinamamahagi.  Masasapawan ang ipinamamahaging talaksan kapag inilipat ang isang talaksan sa ganitong pamaagat.',
'file-exists-sharedrepo'       => 'Ang piniling pangalan ng talaksan ay ginagamit na sa isang binabahaging repositoryo.
Pumili lang po ng ibang pangalan.',

# Export
'export'            => 'Iluwas/ipadala ang mga pahina',
'exporttext'        => 'Maaari mong ilabas ang isang teksto at baguhin ang kasaysayan ng isang partikular na pahina o kumpol na mga pahina na nakalagay sa XML.  Maaari itong iangkat sa ibang wiki gamit ang MediaWiki sa pamamagitan ng [[Special:Import|pahinang angkat]].

Para ilabas ang mga pahina, ipasok ang mga pamagat sa tekstong kahon sa ibaba, isang pamagat bawat guhit, at piliin kung gusto mo rin ang kasalukuyang bersyon o mga lumang bersyon, kasama ang mga pahina ng kasaysayan, o iyon lamang kasalukuyang bersyon kasama ang mga kaalaman tungkol sa huling binago.

Sa huling kaso, maaari mong gumamit ng ungay, hal. [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] para sa pahinang "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'     => 'Isama lamang ang kasalukuyang rebisyon, hindi ang buong kasaysayan',
'exportnohistory'   => "----
'''Tandaan:''' Nakapatay ang paglalabas ng buong kasaysayan ng pahina ng mga pahina sa pamamagitan ng ''form'' na ito dahil maaaring bumagal ang sayt.",
'export-submit'     => 'Magluwas',
'export-addcattext' => 'Magdagdag ng mga pahina mula sa kategorya:',
'export-addcat'     => 'Magdagdag',
'export-addnstext'  => 'Magdagdag ng mga pahina mula sa espasyo ng pangalan:',
'export-addns'      => 'Idagdag',
'export-download'   => 'Itala bilang talaksan',
'export-templates'  => 'Kabilang ang mga suleras',
'export-pagelinks'  => 'Isama ang nakakawing na mga pahina magpahanggang sa isang lalim na:',

# Namespace 8 related
'allmessages'                   => 'Mga mensaheng pansistema',
'allmessagesname'               => 'Pangalan',
'allmessagesdefault'            => 'Tinakdang teksto',
'allmessagescurrent'            => 'Kasalukuyang teksto',
'allmessagestext'               => 'Isa itong talaan ng mga mensahe ng sistema na makukuha mula sa espasyo ng pangalang MediaWiki.
Pakidalaw ang [http://www.mediawiki.org/wiki/Localisation Lokalisasyong MediaWiki] at [http://translatewiki.net translatewiki.net] kung ibig mong magambag sa heneriko o pangkalahatang lokalisasyon ng MediaWiki.',
'allmessagesnotsupportedDB'     => "Hindi magagamit ang '''{{ns:special}}:AllMessages''' dahil hindi gumagana ang '''\$wgUseDatabaseMessages'''.",
'allmessages-filter-legend'     => 'Salain',
'allmessages-filter'            => 'Salain ayon sa katayuan ng pagbabagay:',
'allmessages-filter-unmodified' => 'Hindi pa nababago',
'allmessages-filter-all'        => 'Lahat',
'allmessages-filter-modified'   => 'Nabago na',
'allmessages-prefix'            => 'Salain ayon sa unlapi:',
'allmessages-language'          => 'Wika:',
'allmessages-filter-submit'     => 'Gawin',

# Thumbnails
'thumbnail-more'           => 'Palakihin',
'filemissing'              => 'Nawawala ang talaksan',
'thumbnail_error'          => "May kamalian sa paglikha ng kagyat (''thumbnail''): $1",
'djvu_page_error'          => 'Wala sa nasasakupan ang pahinang DjVu',
'djvu_no_xml'              => 'Hindi makuha ang XML para sa talaksang DjVu',
'thumbnail_invalid_params' => "Hindi tanggap ang mga parametro para sa kagyat (''thumbnail'')",
'thumbnail_dest_directory' => 'Hindi malikha ang papuntahang direktoryo',
'thumbnail_image-type'     => 'Hindi tinatangkili ang uri ng larawan',
'thumbnail_gd-library'     => 'Hindi kumpletong pagkakaayos ng aklatang GD: nawawala ang tungkuling $1',
'thumbnail_image-missing'  => 'Tila nawawala sa talaksan ang : $1',

# Special:Import
'import'                     => 'Mag-angkat ng pahina',
'importinterwiki'            => 'Angkat na transwiki',
'import-interwiki-text'      => 'Pumili ng isang wiki at pamagat ng pahina na iaangkat.
Mapapanatili ang mga petsa ng pagbabago at mga pangalan ng patnugot.
Naitatala sa [[Special:Log/import|tala ng inangkat]] ang lahat ng mga transwiking aksyon para sa pag-angkat.',
'import-interwiki-source'    => 'Pinagmulang wiki/pahina:',
'import-interwiki-history'   => 'Kopyahin ang lahat ng mga bersyon ng kasaysayan para sa pahinang ito',
'import-interwiki-templates' => 'Isama ang lahat ng mga suleras',
'import-interwiki-submit'    => 'Mag-angkat',
'import-interwiki-namespace' => 'Kapupuntahang espasyo ng pangalan:',
'import-upload-filename'     => 'Pangalan ng talaksan:',
'import-comment'             => 'Komento:',
'importtext'                 => 'Pakiluwas/pakikuha ang talaksan mula sa pinagmulang wiki na ginagamit ang [[Special:Export|kasangkapang pangluwas]].  Sagipin mo ito sa iyong kompyuter at ikarga rito.',
'importstart'                => 'Inaangkat na ang mga pahina...',
'import-revision-count'      => '$1 {{PLURAL:$1|pagbabago|mga pagbabago}}',
'importnopages'              => 'Walang mga pahinang maangkat.',
'imported-log-entries'       => 'Inangkat na $1 {{PLURAL:$1|pagpasok sa talaan|mga pagpasok sa talaan}}.',
'importfailed'               => 'Bigo ang pag-angkat: $1',
'importunknownsource'        => 'Hindi alam na pinagmulang uri ng angkat',
'importcantopen'             => 'Hindi mabuksan ang talaksan ng angkat',
'importbadinterwiki'         => 'Masamang ugnay na interwiki',
'importnotext'               => 'Walang laman o teksto',
'importsuccess'              => 'Tapos na ang pag-angkat!',
'importhistoryconflict'      => 'Mayroong sumasalungat sa pagbabago ng kasaysayan (maaaring naiangkat na ito dati)',
'importnosources'            => 'Walang binigay na kahulugan para sa mapagkukunang angkat na transwiki at nakapatay ang diretsong pagkakarga ng kasaysayan.',
'importnofile'               => 'Walang nakargang talaksan ng angkat.',
'importuploaderrorsize'      => 'Bigo ang pagkarga ng inangkat na talaksan.  Mas malaki ang talaksan kaysa pinapahintulot na laki.',
'importuploaderrorpartial'   => 'Bigo ang pagkarga ng inangkat na talaksan.  Bahagi lamang ang nakargang talaksan.',
'importuploaderrortemp'      => 'Bigo ang pagkarga ng inangkat na talaksan.  Nawawala ang pasamantalang polder.',
'import-parse-failure'       => 'Bigo ang pagsuri sa inangkat na XML',
'import-noarticle'           => 'Walang pahinang maangkat!',
'import-nonewrevisions'      => 'Naangkat na ang lahat ng pagbabago.',
'xml-error-string'           => '$1 sa linya $2, hanay $3 (byte $4): $5',
'import-upload'              => 'Magkarga ng datos na XML',
'import-token-mismatch'      => 'Nawala ang dato ng pagpupulong.  Pakisubok muli.',
'import-invalid-interwiki'   => 'Hindi maangkat mula sa tinukoy na wiki.',

# Import log
'importlogpage'                    => 'Talaan ng pagaangkat',
'importlogpagetext'                => 'Mga administratibong pagaangkat ng mga pahinang may kasaysayan ng pagbabago mula sa ibang mga wiki.',
'import-logentry-upload'           => 'inangkat ang [[$1]] sa pamamagitan ng pagkarga ng talaksan (file upload)',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|pagbabago|mga pagbabago}}',
'import-logentry-interwiki'        => 'Na-i-transwiki na ang $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|pagbabago|mga pagbabago}} mula sa $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ang iyong pahina ng tagagamit',
'tooltip-pt-anonuserpage'         => 'Ang pahina ng tagagamit para sa IP na iyong binabago bilang',
'tooltip-pt-mytalk'               => 'Ang iyong pahina ng usapan',
'tooltip-pt-anontalk'             => 'Usapang tungkol sa mga pagbabagong ginawa sa ip address na ito',
'tooltip-pt-preferences'          => 'Aking mga kagustuhan',
'tooltip-pt-watchlist'            => 'Ang talaan ng mga pagbabago sa mga pahinang binabantayan mo',
'tooltip-pt-mycontris'            => 'Talaan ng mga ambag mo',
'tooltip-pt-login'                => 'Hinihimok kang lumagda, bagaman hindi ito kinakailangan.',
'tooltip-pt-anonlogin'            => 'Hinihimok kang lumagda, bagaman hindi ito kinakailangan.',
'tooltip-pt-logout'               => 'Umalis sa pagkakalagda',
'tooltip-ca-talk'                 => 'Usapan tungkol sa nilalaman ng pahinang ito',
'tooltip-ca-edit'                 => 'Maaaring baguhin ang pahinang ito. Paki gamit ang buton ng paunang tingin bago itala.',
'tooltip-ca-addsection'           => 'Magsimula ng isang bagong seksyon',
'tooltip-ca-viewsource'           => 'Nakaprotekta ang pahinang ito. Makikita mo lamang ang pinagmulan (source) nito.',
'tooltip-ca-history'              => 'Nakaraang bersyon ng pahinang ito.',
'tooltip-ca-protect'              => 'Iprotekta ang pahinang ito',
'tooltip-ca-unprotect'            => 'Huwag prutektahan ang pahinang ito',
'tooltip-ca-delete'               => 'Burahin ang pahinang ito',
'tooltip-ca-undelete'             => 'Ibalik ang mga pagbabagong ginawa sa pahinang ito bago ito binura',
'tooltip-ca-move'                 => 'Ilipat ang pahinang ito',
'tooltip-ca-watch'                => 'Iragdag ang pahinang ito sa iyong babantayan',
'tooltip-ca-unwatch'              => 'Alisin ang pahinang ito sa iyong babantayan',
'tooltip-search'                  => 'Maghanap sa {{SITENAME}}',
'tooltip-search-go'               => 'Puntahan ang isang pahina na may ganitong tumpak na pangalan',
'tooltip-search-fulltext'         => 'Hanapin ang mga pahina para sa tekstong ito',
'tooltip-p-logo'                  => 'Unang Pahina',
'tooltip-n-mainpage'              => 'Dalawin ang Unang Pahina',
'tooltip-n-mainpage-description'  => 'Dalawin ang unang pahina',
'tooltip-n-portal'                => 'Hinggil sa proyekto, ano ang magagawa mo, saan matatagpuan ang mga bagay-bagay',
'tooltip-n-currentevents'         => 'Maghanap ng sanligang impormasyon hinggil sa mga kasalukuyang kaganapan',
'tooltip-n-recentchanges'         => 'Ang tala ng mga kamakailang pagbabago sa loob ng wiki.',
'tooltip-n-randompage'            => 'Magkarga ng anumang pahina',
'tooltip-n-help'                  => 'Pook kung saan ito matutuklasan.',
'tooltip-t-whatlinkshere'         => 'Tala ng lahat ng pahina ng mga wiking nakakawing dito',
'tooltip-t-recentchangeslinked'   => 'Kamakailang mga pagbabago na nakakawing mula sa pahinang ito',
'tooltip-feed-rss'                => 'Subo/Kargang RSS para sa pahinang ito',
'tooltip-feed-atom'               => 'Subo/kargang Atom para sa pahinang ito',
'tooltip-t-contributions'         => 'Tunghayan ang tala ng mga ambag ng tagagamit na ito',
'tooltip-t-emailuser'             => 'Magpadala ng isang e-liham sa tagagamit na ito',
'tooltip-t-upload'                => 'Magkarga ng mga talaksan',
'tooltip-t-specialpages'          => 'Tala ng lahat ng mga natatanging pahina',
'tooltip-t-print'                 => 'Nalilimbag na bersyon ng pahinang ito',
'tooltip-t-permalink'             => 'Palagiang kawing sa bersyong ito ng pahina',
'tooltip-ca-nstab-main'           => 'Tingnan ang pahina ng nilalaman',
'tooltip-ca-nstab-user'           => 'Tingnan ang pahina ng tagagamit',
'tooltip-ca-nstab-media'          => 'Tingnan ang pahina ng midya',
'tooltip-ca-nstab-special'        => 'Isa itong natatanging pahina, hindi mo mababago ang mismong pahina',
'tooltip-ca-nstab-project'        => 'Tingnan ang pahina ng proyekto',
'tooltip-ca-nstab-image'          => 'Tingnan ang pahina ng talaksan',
'tooltip-ca-nstab-mediawiki'      => 'Tingnan ang mensahe ng sistema',
'tooltip-ca-nstab-template'       => 'Tingnan ang suleras',
'tooltip-ca-nstab-help'           => 'Tingnan ang pahina ng tulong',
'tooltip-ca-nstab-category'       => 'Tingnan ang pahina ng kaurian/kategorya',
'tooltip-minoredit'               => 'Tandaan ito bilang isang maliit na pagbabago',
'tooltip-save'                    => 'Sagipin ang iyong mga pagbabago',
'tooltip-preview'                 => 'Paunang-tingnan ang mga pagbabago mo, pakigamit muna ito bago sagipin o magtala!',
'tooltip-diff'                    => 'Ipakita ang mga pagbabagong ginawa mo sa teksto.',
'tooltip-compareselectedversions' => 'Tingnan ang pagkakaiba sa pagitan ng dalawang napiling bersyon ng pahinang ito.',
'tooltip-watch'                   => 'Idagdag ang pahinang ito sa iyong tala ng mga binabantayan',
'tooltip-recreate'                => 'Muling likhain ang pahina kahit na nabura na ito',
'tooltip-upload'                  => 'Simulan ang pagkarga',
'tooltip-rollback'                => 'Ibinabalik ng "Pagulungin pabalik sa dati" ang (mga) pagbabago sa pahinang ito patungo sa huling bersyon ng huling tagapagambag sa pamamagitan ng isang pindot lamang.',
'tooltip-undo'                    => 'Ibinabalit ng "Ibalik" ang pagbabagong ito at binubuksan ang pahinang gawaan ng pagbabago sa anyong paunang-tingin muna.  Nagpapahintulot na makapagdagdag ng dahilan sa buod.',
'tooltip-preferences-save'        => 'Itakda ang mga kagustuhan',
'tooltip-summary'                 => 'Magbigay ng maikling buod',

# Stylesheets
'common.css'      => '/* Ang inilagay na CSS dito ay gagamitin para sa lahat ng mga pabalat */',
'standard.css'    => '/* Ang inilagay na CSS dito ay makakaapekto sa mga tagagamit ng Karaniwang pabalat */',
'nostalgia.css'   => '/* Ang CSS na inilagay dito ay makakaapekto sa mga tagagamit ng pabalat na Nostalgia */',
'cologneblue.css' => "/* Ang CSS na inilagay dito ay makakaapekto sa mga tagagamit ng pabalat na Bugkaw na Kolon (''Cologne Blue'') */",
'monobook.css'    => '/* Ang CSS na inilagay dito ay makakaapekto sa mga tagagamit ng pabalat na Monobook */',
'myskin.css'      => "/* Ang CSS na inilagay dito ay makakaapekto sa lahat ng mga tagagamit ng pabalat na Balatko (''Myskin'') */",
'chick.css'       => "/* Ang CSS na inilagay dito ay makakaapekto sa mga tagagamit ng pabalat na ''Chick'' */",
'simple.css'      => "/* Ang CSS na iniligay dito ay makakaapekto sa mga tagagamit ng Payak (''Simple'') na pabalat */",
'modern.css'      => "/* Ang CSS na iniligay dito ay makakaapekto sa tagagamit ng Makabagong (''Modern'') pabalat */",
'print.css'       => '/* Ang CSS na inilagay dito ay makakaapekto sa kalalabasan o resulta ng paglilimbag */',
'handheld.css'    => "/* Ang CSS na inilagay dito ay makakaapekto sa mga aparatong nahahawakan (''handheld device'') batay sa itinakdang pabalat sa ''\$wgHandheldStyle'' */",

# Scripts
'common.js'      => '/* Anumang JavaScript dito ay ikakarga para sa lahat ng mga tagagamit ng bawat pahinang ikinarga. */',
'standard.js'    => '/* Anumang JavaScript dito ay ikakarga para lahat ng mga tagagamit na gumagamit ng Karaniwang pabalat */',
'nostalgia.js'   => '/* Anumang JavaScript dito ay ikakarga para lahat ng mga tagagamit na gumagamit ng pabalat na Nostalgia */',
'cologneblue.js' => '/* Anumang JavaScript dito ay ikakarga para sa tagagamit ng pabalat na Bughaw na Kolon */',
'monobook.js'    => '/* Anumang JavaScript dito ay ikakarga para sa mga tagagamit na gumagamit ng pabalat na MonoBook */',
'myskin.js'      => '/* Anumang JavaScript dito ay ikakarga para sa tagagamit na gumagamit ng pabalat na Balatko */',
'chick.js'       => "/* Anumang JavaScript dito ay ikakarga para sa mga tagagamit na gumagamit ng pabalat na ''Chick'' */",
'simple.js'      => '/* Anumang JavaScript dito ay ikakarga para sa tagagamit na gumagamit ng Payak na pabalat */',
'modern.js'      => '/* Anumang JavaScript dito ay ikakarga para sa mga tagagamit na gumagamit ng Makabagong pabalat */',

# Metadata
'nodublincore'      => 'Hindi pinagana ang metadatang Dublin Core RDF para sa serbidor na ito.',
'nocreativecommons' => 'Hindi pinagana ang metadatang Creative Commons RDF para sa serbidor na ito.',
'notacceptable'     => 'Hindi makapagbigay ng dato ang serbidor ng wiki sa anyong mababasa ng iyong kliyente.',

# Attribution
'anonymous'        => 'Hindi kilalang {{PLURAL:$1|tagagamit|mga tagagamit}} ng {{SITENAME}}',
'siteuser'         => 'Tagagamit $1 ng {{SITENAME}}',
'anonuser'         => 'Hindi nakikilalang tagagamit na $1 ng {{SITENAME}}',
'lastmodifiedatby' => 'Huling binago ang pahinang ito noong $2, $1 ni $3.',
'othercontribs'    => 'Batay sa gawa ni/nina $1.',
'others'           => 'iba pa',
'siteusers'        => '{{PLURAL:$2|tagagamit|mga tagagamit}} $1 ng {{SITENAME}}',
'anonusers'        => 'Hindi nakikilalang $1 na {{PLURAL:$2|tagagamit|mga tagagamit}} ng {{SITENAME}}',
'creditspage'      => 'Pahina ng pagkilala sa gumawa (mga kredito)',
'nocredits'        => 'Walang mga kredito/pagkilala sa gumawa na makuha para sa pahinang ito.',

# Spam protection
'spamprotectiontitle' => "Pansala na pananggalang laban sa ''Spam''",
'spamprotectiontext'  => "Ang pahinang ibig mong sagipin ay naharang ng pansala ng ''spam''.
Maaaring dahil ito sa isang kawing sa isang nakatalang hinarang dahil di-kinaisnais na panlabas na sityo/sayt.",
'spamprotectionmatch' => "Ang sumusunod na teksto ang bumuhay sa aming panala ng ''spam'': $1",
'spambot_username'    => "Paglilinis ng ''spam'' ng MediaWiki",
'spam_reverting'      => "Ibinabalik sa huling bersyon na 'di-naglalaman ng mga kawing sa $1",
'spam_blanking'       => 'Lahat ng mga pagbabago ay naglalaman ng mga kawing sa $1, pagpapatlang',

# Info page
'infosubtitle'   => 'Kabatiran para sa pahina',
'numedits'       => 'Bilang ng mga pagbabago (pahina): $1',
'numtalkedits'   => 'Bilang ng mga pagbabago (pahinang usapan): $1',
'numwatchers'    => 'Bilang ng mga tagatingin: $1',
'numauthors'     => 'Bilang ng mga bukdo-tanging mga may-akda (pahina): $1',
'numtalkauthors' => 'Bilang ng bukod-tanging mga may-akda (pahinang usapan): $1',

# Skin names
'skinname-standard'    => 'Klasiko',
'skinname-nostalgia'   => 'Nostalhiya',
'skinname-cologneblue' => 'Bughaw na Kolon',
'skinname-monobook'    => 'MonoAklat ("isang aklat")',
'skinname-myskin'      => 'PabalatKo',
'skinname-chick'       => "\"Pambabae\" (''Chick'')",
'skinname-simple'      => 'Payak',
'skinname-modern'      => 'Makabago (Moderno)',

# Math options
'mw_math_png'    => 'Palaging ilarawan sa anyong PNG',
'mw_math_simple' => 'HTML kung napakapayak o kaya PNG kung iba',
'mw_math_html'   => 'HTML kung maaari o kaya PNG kapag iba',
'mw_math_source' => "Iwanan bilang TeX (para sa mga panghanap na pangteksto o ''text browser'')",
'mw_math_modern' => 'Irekomenda para sa makabagong mga panghanap',
'mw_math_mathml' => 'MathML kung maaari (sinusubok pa)',

# Math errors
'math_failure'          => 'Nabigo sa pagbanghay',
'math_unknown_error'    => 'hindi nalalamang kamalian',
'math_unknown_function' => 'hindi nalalamang tungkulin',
'math_lexing_error'     => 'kamalian sa pagbabatas',
'math_syntax_error'     => 'kamalian sa palaugnayan',
'math_image_error'      => 'Nabigo ang pagpapalit patungong PNG;
pakisuri kung tama ang pagiinstala ng latex, dvips, gs, at palitan',
'math_bad_tmpdir'       => 'Hindi maisulat sa o makalikha ng pansamantalang direktoryong pangmatematika',
'math_bad_output'       => 'Hindi maisulat sa o makalikha ng direktoryo ng produktong pangmatematika',
'math_notexvc'          => 'Nawawala ang maisasakatuparang texvc;
pakitingnan ang matematika/BASAHINAKO para maisaayos ang konpigurasyon.',

# Patrolling
'markaspatrolleddiff'                 => 'Tatakan bilang napatrolya na',
'markaspatrolledtext'                 => 'Tatakan ang pahinang ito bilang napatrolya na',
'markedaspatrolled'                   => 'Tatakan bilang napatrolya na',
'markedaspatrolledtext'               => 'Ang napiling pagbabago ng [[:$1]] ay natatakan bilang napatrolya na.',
'rcpatroldisabled'                    => 'Hindi pinagana ang Patrolyang Pangkamailan-Lamang na Pagbabago',
'rcpatroldisabledtext'                => 'Kasalukuyang hindi pinagagana ang kasangkapang Patrolyang Pangkamakailang-lamang na Pagbabago.',
'markedaspatrollederror'              => 'Hindi matatakan bilang napatrolya na',
'markedaspatrollederrortext'          => 'Kailangang tukuyin mo ang isang pagbabago para matatakan ito bilang napatrolya na.',
'markedaspatrollederror-noautopatrol' => 'Wala kang pahintulot para tatakan ang ginawa mong mga pagbabago bilang napatrolya na.',

# Patrol log
'patrol-log-page'      => 'Tala ng Pagpapatrolya',
'patrol-log-header'    => 'Tala ito ng mga pagbabagong napatrolya na.',
'patrol-log-line'      => 'tinatakang $1 ng $2 napatrolya $3',
'patrol-log-auto'      => '(awtomatiko)',
'patrol-log-diff'      => 'rebisyong $1',
'log-show-hide-patrol' => '$1 tala ng pagpatrolya',

# Image deletion
'deletedrevision'                 => 'Binurang lumang pagbabago $1',
'filedeleteerror-short'           => 'Kamalian sa pagbubura ng talaksan: $1',
'filedeleteerror-long'            => 'Nakaranas ng mga kamalian habang binubura ang talaksan:

$1',
'filedelete-missing'              => 'Hindi mabura ang talaksang "$1", dahil wala namang ganito.',
'filedelete-old-unregistered'     => 'Wala sa himpilan ng dato ang tinutukoy na pagbabago sa talaksan "$1".',
'filedelete-current-unregistered' => 'Wala sa himpilan ng dato ang tinukoy na talaksang "$1".',
'filedelete-archive-read-only'    => 'Hindi maisulat ng serbidor ng sapot (web) ang direktoryo ng sinupang "$1".',

# Browsing diffs
'previousdiff' => '← Mas lumang pagbabago',
'nextdiff'     => 'Mas bagong pagbabago →',

# Media information
'mediawarning'         => "'''Babala''': Maaaring naglalaman ng kodigong malisyoso ang uri ng talaksang ito.
Maaaring manganib ang iyong sistema kapag ipinagana mo ito.",
'imagemaxsize'         => "Takdang hangganan sa laki ng larawan: <br />''(para sa mga pahina ng paglalarawan ng talaksan)''",
'thumbsize'            => 'Maliit na sukat (parang "kuko sa hinlalaki" lamang):',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|pahina|mga pahina}}',
'file-info'            => '(sukat ng talaksan: $1, tipo ng MIME: $2)',
'file-info-size'       => '($1 × $2 piksel, sukat ng talaksan: $3, tipo ng MIME: $4)',
'file-nohires'         => '<small>Walang makuhang mas mataas na resolusyon (kalinawan).</small>',
'svg-long-desc'        => '(Talaksang SVG, nasa mga bilang na $1 × $2 mga piksel, sukat ng talaksan: $3)',
'show-big-image'       => 'Buong resolusyon (kalinawan)',
'show-big-image-thumb' => '<small>Laki ng paunang tinging ganito: $1 × $2 mga piksel</small>',
'file-info-gif-looped' => 'nasilo na',
'file-info-gif-frames' => '$1 {{PLURAL:$1|banhay|mga banhay}}',
'file-info-png-looped' => 'nakalikaw',
'file-info-png-repeat' => 'pinaandar ng $1 {{PLURAL:$1|ulit|mga ulit}}',
'file-info-png-frames' => ' $1 {{PLURAL:$1|kuwadro|mga kuwadro}}',

# Special:NewFiles
'newimages'             => 'Galerya ng mga bagong talaksan',
'imagelisttext'         => "Nasa ibaba ang isang tala ng '''$1''' {{PLURAL:$1|talaksan|mga talakasang}} nauri na $2.",
'newimages-summary'     => 'Nagpapakita ang natatanging pahinang ito ng huling naikargang mga talaksan.',
'newimages-legend'      => 'Pansala',
'newimages-label'       => 'Pangalan ng talaksan (o bahagi nito):',
'showhidebots'          => "($1 mga ''bot'')",
'noimages'              => 'Walang makikita dito.',
'ilsubmit'              => 'Hanapin',
'bydate'                => 'ayon sa petsa',
'sp-newimages-showfrom' => 'Ipakita ang mga bagong talaksang nagsisimula mula $2, $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'hours-abbrev' => 'o',

# Bad image list
'bad_image_list' => 'Ang anyo ay ang mga sumusunod:

Tanging mga nakatalang bagay lamang (mga linyang nagsisimula sa *) ang pinaguukulan ng pansin.
Ang unang kawing sa isang linya ay dapat na nakakawing sa isang talaksang may masamang kalagayan.
Anumang susunod na mga kawing sa pinanggalingang linya ay tinuturing na mga eksepsyon o bukod-tangi, iyong mga pahina kung saan ang mga talaksan ay maaaring lumitaw sa loob ng linya.',

# Metadata
'metadata'          => 'Metadatos',
'metadata-help'     => 'Naglalaman ang talaksang ito ng karagdagang kabatiran na maaaring idinagdag mula sa isang kamerang dihital o iskaner na ginamit para likhain o para maging dihital ito.
Kapag nabago ang talaksan mula sa anyong orihinal nito, may ilang detalyeng hindi ganap na maipapakita ang nabagong talaksan.',
'metadata-expand'   => 'Ipakita ang karugtong na mga detalye',
'metadata-collapse' => 'Itago ang karugtong na mga detalye',
'metadata-fields'   => 'Ang mga pook ng metadatang EXIF na nakatala sa mensaheng ito ay masasama sa ipinapakitang pahina ng larawan kapag tumiklop ang tabla ng metadata.
Nakatakdang itago ang iba pa.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Lapad',
'exif-imagelength'                 => 'Taas',
'exif-bitspersample'               => 'Mga bit (piraso) ng bawat komponente (bahagi)',
'exif-compression'                 => 'Plano ng kumpresyon (pagkakasiksik)',
'exif-photometricinterpretation'   => 'Mga taglay (komposisyon) ng piksel',
'exif-orientation'                 => 'Oryentasyon',
'exif-samplesperpixel'             => 'Bilang ng mga komponente (sangkap)',
'exif-planarconfiguration'         => 'Pagkakaayos ng mga dato',
'exif-ycbcrsubsampling'            => "Halimbawang bahagi ng rata (''ratio'') ng Y sa C",
'exif-ycbcrpositioning'            => 'Pagkakaposisyon ng Y at C',
'exif-xresolution'                 => 'Pahalang na resolusyon (kalinawan)',
'exif-yresolution'                 => 'Bertikal (patayo) na resolusyon/kalinawan',
'exif-resolutionunit'              => 'Yunit ng resolusyong X at Y',
'exif-stripoffsets'                => 'Lokasyon ng dato ng larawan',
'exif-rowsperstrip'                => 'Bilang ng pahalang na hanay bawat manipis na piraso',
'exif-stripbytecounts'             => 'Mga byte ng bawat siniksik na piraso',
'exif-jpeginterchangeformat'       => "Bawiin at ibalanse (i-''offset'') patungo sa JPEG SOI",
'exif-jpeginterchangeformatlength' => 'Mga byte ng datong JPEG',
'exif-transferfunction'            => 'Tungkuling panglipat',
'exif-whitepoint'                  => 'Kadalisayan (kromatisidad) ng punto o hangganan ng kaputian',
'exif-primarychromaticities'       => 'Mga kadalisayan (kromatisidad) ng mga pangunahing kulay (mga primarya)',
'exif-ycbcrcoefficients'           => 'Mga koepisyente (katuwang na bilang) ng matris na pambago ng espasyo ng kulay',
'exif-referenceblackwhite'         => 'Pares ng mga itim at puting sangguniang halaga',
'exif-datetime'                    => 'Petsa at oras ng pagbabago ng talaksan',
'exif-imagedescription'            => 'Pamagat ng larawan',
'exif-make'                        => 'Kumpanyang tagagawa ng kamera',
'exif-model'                       => 'Modelo ng kamera',
'exif-software'                    => 'Ginamit na sopwer',
'exif-artist'                      => 'May-akda',
'exif-copyright'                   => 'May-hawak ng karapatang-ari (kopirayt)',
'exif-exifversion'                 => 'Bersyong Exif',
'exif-flashpixversion'             => 'Bersyon ng sinusuportahang Flashpix',
'exif-colorspace'                  => 'Espasyo ng kulay',
'exif-componentsconfiguration'     => 'Kahulugan ng bawat komponente',
'exif-compressedbitsperpixel'      => 'Modalidad (paraan) ng pagsisiksik ng larawan',
'exif-pixelydimension'             => 'Tanggap na lapad ng larawan',
'exif-pixelxdimension'             => 'Tanggap na taas ng larawan',
'exif-makernote'                   => 'Mga tala mula sa kumpanyang tagagawa',
'exif-usercomment'                 => 'Mga kumento ng tagagamit',
'exif-relatedsoundfile'            => 'Kaugnay na talaksang nadidinig (audio)',
'exif-datetimeoriginal'            => 'Petsa at oras ng paglikha ng mga dato',
'exif-datetimedigitized'           => 'Petsa at oras ng pagsasadihital',
'exif-subsectime'                  => 'PetsaOras mga subsegundo (bahagi ng segundo)',
'exif-subsectimeoriginal'          => 'PetsaOrasOrihinal subsegundo (bahagi ng segundo)',
'exif-subsectimedigitized'         => 'PetsaOrasDihitalisasyon subsegundo (bahagi ng segundo)',
'exif-exposuretime'                => 'Oras ng pagkakalantad',
'exif-exposuretime-format'         => '$1 seg ($2)<!--seg = segundo (seconds)-->',
'exif-fnumber'                     => 'F Bilang',
'exif-exposureprogram'             => 'Programa ng paglalantad',
'exif-spectralsensitivity'         => 'Sensitibidad sa ispektrum',
'exif-isospeedratings'             => 'Grado ng bilis ng ISO',
'exif-oecf'                        => 'Paktora ng optoelektronikong pagpapalit',
'exif-shutterspeedvalue'           => "Bilis ng pansara (''shutter'')",
'exif-aperturevalue'               => 'Apertura (butas na daanan ng liwanag)',
'exif-brightnessvalue'             => 'Kaningningan',
'exif-exposurebiasvalue'           => 'Panig ng kalantaran',
'exif-maxaperturevalue'            => 'Pinakamataas na aperturang (daanan ng liwanag) panglupa',
'exif-subjectdistance'             => 'Layo ng paksa',
'exif-meteringmode'                => 'Modalidad ng pagmemetro (pagsusukat)',
'exif-lightsource'                 => 'Pinagmumulan ng liwanag',
'exif-flash'                       => "Pangkisap (''flash'')",
'exif-focallength'                 => 'Haba ng lenteng pampokus (pantuon)',
'exif-subjectarea'                 => 'Saklaw na paksa',
'exif-flashenergy'                 => "Lakas ng kisap (''flash'')",
'exif-spatialfrequencyresponse'    => 'Tugon ng kalimitan na pangespasyo',
'exif-focalplanexresolution'       => 'Resolusyong X ng kalatagan o lapyang pampokus',
'exif-focalplaneyresolution'       => 'Resolusyong Y ng kalatagan o lapyang pampokus',
'exif-focalplaneresolutionunit'    => 'Yunit ng resolusyon (kalinawan) ng kalatagan o lapyang pampokus',
'exif-subjectlocation'             => 'Lokasyon ng paksa',
'exif-exposureindex'               => 'Pang-antas o indeks ng pagkakalantad',
'exif-sensingmethod'               => 'Paraang pandama',
'exif-filesource'                  => 'Pinagmulang talaksan',
'exif-scenetype'                   => 'Uri ng tagpuan',
'exif-cfapattern'                  => 'Gawi ng CFA',
'exif-customrendered'              => 'Pagpoproseso ng pinasadyang larawan',
'exif-exposuremode'                => 'Modalidad ng paglalantad',
'exif-whitebalance'                => 'Balanse ng Kaputian',
'exif-digitalzoomratio'            => "Rata/Antas ng sukat ng dihital na paglapit (''zoom'')",
'exif-focallengthin35mmfilm'       => 'Haba ng pokus sa pilm na 35 mm',
'exif-scenecapturetype'            => 'Uri ng panghuli ng tagpuan',
'exif-gaincontrol'                 => 'Kontrol na pangtagpuan',
'exif-contrast'                    => "Pagkakaiba ng pagsasalungat (''contrast'')",
'exif-saturation'                  => 'Saturasyon (pagkakababad/pagkakapuno)',
'exif-sharpness'                   => 'Katalasan',
'exif-devicesettingdescription'    => 'Paglalarawan sa mga pagtatakdang pangaparato',
'exif-subjectdistancerange'        => 'Antas ng layo ng paksa',
'exif-imageuniqueid'               => 'Natatanging ID ng larawan',
'exif-gpsversionid'                => 'Bersyon ng GPS tag',
'exif-gpslatituderef'              => 'Hilaga o Timog na Latitud',
'exif-gpslatitude'                 => 'Latitud',
'exif-gpslongituderef'             => 'Silangan o Kanlurang Longhitud',
'exif-gpslongitude'                => 'Longhitud',
'exif-gpsaltituderef'              => 'Sanggunian ng kataasan',
'exif-gpsaltitude'                 => 'Kataasan',
'exif-gpstimestamp'                => 'Oras ng GPS (atomikong orasan)',
'exif-gpssatellites'               => 'Mga satelayt na ginamit para sa sukat',
'exif-gpsstatus'                   => 'Katayuan ng tagatanggap',
'exif-gpsmeasuremode'              => 'Paraan ng sukat',
'exif-gpsdop'                      => 'Tumpak na sukat',
'exif-gpsspeedref'                 => 'Yunit ng bilis',
'exif-gpsspeed'                    => 'Bilis ng tagatanggap ng GPS',
'exif-gpstrackref'                 => 'Sanggunian para sa direksyon ng galaw',
'exif-gpstrack'                    => 'Direksyon ng galaw',
'exif-gpsimgdirectionref'          => 'Sanggunian para sa direksyon ng larawan',
'exif-gpsimgdirection'             => 'Direksyon ng larawan',
'exif-gpsmapdatum'                 => 'Ginamit na datos para sa geodetic survey',
'exif-gpsdestlatituderef'          => 'Sanggunian para sa latitud ng patutunguhan',
'exif-gpsdestlatitude'             => 'Latitud ng patutunguhan',
'exif-gpsdestlongituderef'         => 'Sanggunian para sa longhitud ng patutunguhan',
'exif-gpsdestlongitude'            => 'Longhitud ng patutunguhan',
'exif-gpsdestbearingref'           => 'Sanggunian para sa oryentasyon ng patutunguhan',
'exif-gpsdestbearing'              => 'Oryentasyon ng patutunguhan',
'exif-gpsdestdistanceref'          => 'Sanggunian para sa layo ng patutunguhan',
'exif-gpsdestdistance'             => 'Layo ng patutunguhan',
'exif-gpsprocessingmethod'         => 'Pangalan ng kaparaanan ng pagproseso ng GPS',
'exif-gpsareainformation'          => 'Pangalan ng lugar ng GPS',
'exif-gpsdatestamp'                => 'Petsa ng GPS',
'exif-gpsdifferential'             => 'Pagtatama sa pakakaiba ng GPS',

# EXIF attributes
'exif-compression-1' => 'Walang kompresyon',

'exif-unknowndate' => 'Hindi alam na araw',

'exif-orientation-1' => 'Karaniwan',
'exif-orientation-2' => 'Pinihit ng pahiga',
'exif-orientation-3' => 'Pinaikot ng 180°',
'exif-orientation-4' => 'Pinihit ng patayo',
'exif-orientation-5' => 'Pinaikot ng 90° CCW at pinihit ng patayo',
'exif-orientation-6' => 'Pinaikot ng 90° CW',
'exif-orientation-7' => 'Pinaikot ng 90° CW at pinihit ng patayo',
'exif-orientation-8' => 'Pinaikot ng 90° CCW',

'exif-planarconfiguration-1' => 'pagkaayos sa malalaking bahagi (chunky)',
'exif-planarconfiguration-2' => 'planar na pagkaayos',

'exif-componentsconfiguration-0' => 'wala',

'exif-exposureprogram-0' => 'Hindi nabigyan ng kahulugan',
'exif-exposureprogram-1' => 'Manwal',
'exif-exposureprogram-2' => 'Karaniwang programa',
'exif-exposureprogram-3' => 'Prayoridad ng apertura',
'exif-exposureprogram-4' => 'Prayoridad ng shutter',
'exif-exposureprogram-5' => 'Programang malikhain (bias sa lalim ng kuha)',
'exif-exposureprogram-6' => 'Programang aksyon (bias sa bilis ng shutter)',
'exif-exposureprogram-7' => 'Naka-portrait (para sa malapitang kuha kasama ang malabong paligid)',
'exif-exposureprogram-8' => 'Naka-tanawin (para mga kuhang tanawin na nakapokus ang paligid)',

'exif-subjectdistance-value' => '$1 mga metro',

'exif-meteringmode-0'   => 'Hindi alam',
'exif-meteringmode-1'   => 'Karaniwan',
'exif-meteringmode-2'   => 'Gitnang tinambang na karaniwan',
'exif-meteringmode-3'   => "Batik (''spot'')",
'exif-meteringmode-4'   => 'Maramihang batik',
'exif-meteringmode-5'   => 'Padron',
'exif-meteringmode-6'   => 'Bahagi lamang',
'exif-meteringmode-255' => 'Iba pa',

'exif-lightsource-0'   => 'Hindi alam',
'exif-lightsource-1'   => 'Pangumaga/pang-araw na liwanag',
'exif-lightsource-2'   => "''Fluorescent''",
'exif-lightsource-3'   => "Tungsteno (nagbabagang liwanag/ilaw o ''incandescent'')",
'exif-lightsource-4'   => "Pangkisap (''flash'')",
'exif-lightsource-9'   => 'Magandang panahon',
'exif-lightsource-10'  => 'Maulap na panahon',
'exif-lightsource-11'  => 'Lilim',
'exif-lightsource-12'  => "''Fluorescent'' na pangumaga/pang-araw (D 5700 – 7100K)",
'exif-lightsource-13'  => "''Fluorescent'' na maputi at pangumaga/pang-araw (N 4600 – 5400K)",
'exif-lightsource-14'  => "''Fluorescent'' na may kalamigan ang pagkaputi (W 3900 – 4500K)",
'exif-lightsource-15'  => "Puting ''fluorescent'' (WW 3200 – 3700K)",
'exif-lightsource-17'  => 'Pangkarinawang liwanag A',
'exif-lightsource-18'  => 'Pangkaraniwang liwanag B',
'exif-lightsource-19'  => 'Pangkaraniwang liwanag C',
'exif-lightsource-20'  => 'D55',
'exif-lightsource-21'  => 'D65',
'exif-lightsource-22'  => 'D75',
'exif-lightsource-23'  => 'D50',
'exif-lightsource-24'  => 'Tungstenong pang-istudyo ng ISO',
'exif-lightsource-255' => 'Iba pang pagmumulan ng liwanag',

# Flash modes
'exif-flash-fired-0'    => "Hindi kumislap/sumiklab ang pangkisap (''flash'')",
'exif-flash-fired-1'    => "Sumiklab/kumislap ang pangkisap (''flash'')",
'exif-flash-return-0'   => 'walang tungkuling pambalik kung makapansin ng liwanag ang istroboskopyo',
'exif-flash-return-2'   => 'hindi makapansin ng bumabalik na liwanag ang istroboskopyo',
'exif-flash-return-3'   => 'nakapansin ng bumabalik na liwanag ang istroboskopyo',
'exif-flash-mode-1'     => "ipinatutupad na sapilitang pagpapasiklab ng pangkisap (''flash'')",
'exif-flash-mode-2'     => "pagpipigil sa sapilitang pagpapasiklab ng pangkisap (''flash'')",
'exif-flash-mode-3'     => 'automatikong modalidad',
'exif-flash-function-1' => "Tungkuling walang pagpapakisap (''flash'')",
'exif-flash-redeye-1'   => 'Modalidad na pambawas na mapulang mata/pula sa mata',

'exif-focalplaneresolutionunit-2' => 'mga pulgada',

'exif-sensingmethod-1' => 'Walang kahulugan',
'exif-sensingmethod-2' => "Pandama (''sensor'') sa pook ng kulay na may isang piyesang \"tisa\" (''chip'')",
'exif-sensingmethod-3' => "Pandama (''sensor'') sa pook ng kulay na may dalawang piyesang \"tisa\" (''chip'')",
'exif-sensingmethod-4' => "Pandama (''sensor'') sa pook ng kulay na may tatlong piyesang \"tisa\" (''chip'')",
'exif-sensingmethod-5' => "Pandama (''sensor'') sa pook na may nagsusunud-sunurang mga kulay",
'exif-sensingmethod-7' => "Pandama (''sensor'') ng mga paligid na may tatlong guhit (''trilinear'')",
'exif-sensingmethod-8' => 'Linear sensor na sunod-sunod na kulay',

'exif-scenetype-1' => 'Isang larawang diretsong kinuha',

'exif-customrendered-0' => 'Karaniwang proseso',
'exif-customrendered-1' => 'Pasadyang proseso',

'exif-exposuremode-0' => 'Awtomatikong eksposisyon',
'exif-exposuremode-1' => 'Manwal na eksposisyon',
'exif-exposuremode-2' => 'Awtomatikong bracket',

'exif-whitebalance-0' => 'Awtomatikong timbang ng puti',
'exif-whitebalance-1' => 'Manwal na timbang ng puti',

'exif-scenecapturetype-0' => 'Karaniwan',
'exif-scenecapturetype-1' => 'Tanawin',
'exif-scenecapturetype-2' => 'Kuwadro',
'exif-scenecapturetype-3' => 'Eksena sa gabi',

'exif-gaincontrol-0' => 'Wala',
'exif-gaincontrol-1' => 'Mababang gain pataas',
'exif-gaincontrol-2' => 'Mataas na gain pataas',
'exif-gaincontrol-3' => 'Mababang gain pababa',
'exif-gaincontrol-4' => 'Mataas na gain pababa',

'exif-contrast-0' => 'Karaniwan',
'exif-contrast-1' => 'Malambot',
'exif-contrast-2' => 'Matigas',

'exif-saturation-0' => 'Karaniwan',
'exif-saturation-1' => 'Mababang saturasyon',
'exif-saturation-2' => 'Mataas na saturasyon',

'exif-sharpness-0' => 'Karaniwan',
'exif-sharpness-1' => 'Malambot',
'exif-sharpness-2' => 'Matigas',

'exif-subjectdistancerange-0' => 'Hindi alam',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Malapitang tingin',
'exif-subjectdistancerange-3' => 'Malayuang tingin',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Hilagang latitud',
'exif-gpslatitude-s' => 'Katimugang latitud',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Silangang longhitud',
'exif-gpslongitude-w' => 'Kanlurang longhitud',

'exif-gpsstatus-a' => 'Kasalukuyang nagsusukat',
'exif-gpsstatus-v' => 'Interoperabilidad (pagiging naisasagawa) ng sukat',

'exif-gpsmeasuremode-2' => 'Sukat na may 2 dimensyon',
'exif-gpsmeasuremode-3' => 'Sukat na may 3 dimensyon',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Mga kilometro bawat oras',
'exif-gpsspeed-m' => 'Mga milya bawat oras',
'exif-gpsspeed-n' => "Mga ''knot''",

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Totoong direksyon',
'exif-gpsdirection-m' => 'Mabato-balaning (magnetikong) direksyon',

# External editor support
'edit-externally'      => 'Baguhin ang talaksang ito sa pamamagitan ng isang panlabas na aplikasyon',
'edit-externally-help' => 'Tingnan ang [http://meta.wikimedia.org/wiki/Help:External_editors mga kaalaman/paraan sa paghahanda at pagaayos] para sa mas marami pang kabatiran.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'lahat',
'imagelistall'     => 'lahat',
'watchlistall2'    => 'lahat',
'namespacesall'    => 'lahat',
'monthsall'        => 'lahat',
'limitall'         => 'lahat',

# E-mail address confirmation
'confirmemail'              => 'Patotohanan ang adres ng e-liham',
'confirmemail_noemail'      => 'Wala kang nakatakdang tanggap na adres ng e-liham sa iyong [[Special:Preferences|mga kagustuhan ng tagagamit]].',
'confirmemail_text'         => "Pinagagawa ng {{SITENAME}} na patotohanan mo ang iyong adres ng e-liham bago gamitin ang mga kasangkapang-katangian ng e-liham.  Pindutin at buhayin ang pindutan sa ibaba para makapagpadala ng isang makapagpapatotoong e-liham (kompirmasyon) patungo sa iyong adres.
Makakasama sa liham ang isang kawing na naglalaman ng kodigo;
Ikarga ang kawing sa iyong pantingin-tingin (''browser'') para mapatotohanang katanggap-tanggap ang iyong adres ng e-liham.",
'confirmemail_pending'      => 'Naipadala na sa iyong e-liham ang kodigo ng pagpapatotoo (kumpirmasyon); kung kamakailan mo lamang nilikha ang iyong kuwenta/akawnt, maaaring ibigin mong maghintay ng ilang minuto para makarating muna ito bago subuking humiling ng isang bagong kodigo.',
'confirmemail_send'         => 'Magpadala ng isang kodigo ng pagpapatotoo (kumpirmasyon)',
'confirmemail_sent'         => 'Naipadala na ang magpapatotoong e-liham (kumpirmasyon).',
'confirmemail_oncreate'     => 'Nagpadala na ng isang kodigo ng pagpapatotoo (kumpirmasyon) patungo sa iyong adres ng e-liham.  Hindi kailangan ang kodigong ito para makalagda, ngunit kailangan mong ibigay muna ito bago paganahin/paandarin ang anumang pang e-liham na kasangkapang-katangiang nasa loob ng wiki.',
'confirmemail_sendfailed'   => 'Hindi maipadala ng {{SITENAME}} ang iyong liham ng pagpapatotoo (kumpirmasyon).
Pakisuri ang iyong adres ng e-liham kung may mga hindi tanggap na mga panitik/karakter.

Ibinalik ng tagapagpadala ang: $1',
'confirmemail_invalid'      => 'Hindi tamang kodigo ng kumpirmasyon.  Maaaring lumagpas na sa taning ang kodigo.',
'confirmemail_needlogin'    => 'Kailangan mong $1 upang kumpirmahin/mapatotohanan ang iyong adres ng e-liham.',
'confirmemail_success'      => 'Nakumpirma/napatotohanan na ang adres ng e-liham mo. Maaari ka ng [[Special:UserLogin|lumagda]] at maglibang sa wiki.',
'confirmemail_loggedin'     => 'Nakumpirma/napatotohanan na ngayon ang adres ng e-liham mo.',
'confirmemail_error'        => 'May nangyaring kamalian sa pagsasagip ng iyong kumpirmasyon.',
'confirmemail_subject'      => 'Kumpirmasyon/pagpapatotoong pang-adres ng e-liham ng {{SITENAME}}',
'confirmemail_body'         => 'May isa, maaaring ikaw, na mula sa adres ng IP na $1,
ang nagtala ng isang akawnt/kuwentang "$2" na mayroong ganitong adres ng e-liham sa {{SITENAME}}.

Para patotohanang ikaw nga ang may-ari ng kuwentang ito at para buhayin ang mga kasangkapang-katanginan ng e-liham sa {{SITENAME}}, buksan ang kawing na ito sa iyong pantingin-tingin (\'\'browser\'\'):

$3

Kung *hindi* mo itinala/inirehistro ang kuwenta, sundan mo ang kawing na ito
para kanselahin o huwag nang ituloy ang pagpapatotoo (kumpirmasyon) ng adres ng e-liham:

$5

Magwawakas ang pagiging mabisa ng kodigo ng pagpapatotoong ito sa $4.',
'confirmemail_body_changed' => 'May isa, maaaring ikaw, na mula sa adres ng IP na $1,
ang nagbago ng adres ng e-liham ng akawnt na "$2" sa ganitong adres sa {{SITENAME}}.

Para patotohanang ikaw nga ang may-ari ng kuwentang ito at para buhaying muli ang mga kasangkapang-katanginan ng e-liham sa {{SITENAME}}, buksan ang kawing na ito sa iyong pantingin-tingin:

$3

Kung *hindi* iyo ang akawnt, sundan mo ang kawing na ito
para huwag nang ituloy ang pagpapatotoo ng adres ng e-liham:

$5

Magwawakas ang kodigo ng pagpapatotoong ito sa $4.',
'confirmemail_invalidated'  => 'Hindi itinuloy/kinansela ang pagpapatotoo ng e-liham',
'invalidateemail'           => 'Huwag ituloy/kanselahin ang pagpapatotoo ng e-liham',

# Scary transclusion
'scarytranscludedisabled' => '[Nakapatay ang interwiki transcluding]',
'scarytranscludefailed'   => '[Bigo ang pagkuha ng suleras na $1; paumanhin]',
'scarytranscludetoolong'  => '[Masyadong mahaba ang URL; paumanhin]',

# Trackbacks
'trackbackbox'      => "Mga ''trackback'' para sa pahinang ito:<br />
$1",
'trackbackremove'   => '([$1 Nabura])',
'trackbacklink'     => 'Balikan ang bakas<!--trackback-->',
'trackbackdeleteok' => "Tagumpay na nabura ang ''trackback''.",

# Delete conflict
'deletedwhileediting' => 'Babala: Binura ang pahinang ito pagkaraan mong simulan ang pagbago!',
'confirmrecreate'     => "Binura ni [[User:$1|$1]] ([[User talk:$1|usapan]]) ang pahinang ito pagkaraan mong magumpisang magbago na may dahilang:
: ''$2''
Pakitiyak kung ibig mo talagang likhain muli ang pahinang ito.",
'recreate'            => 'Likhain muli',

'unit-pixel' => 'px',

# action=purge
'confirm_purge_button' => "Sige/Ayos 'yan/Okey",
'confirm-purge-top'    => 'Linisin/hawiin ang taguan ng pahinang ito?',
'confirm-purge-bottom' => 'Nililinis ng pagdadalisay ng isang pahina ang taguan at mapipilitang palitawin ang pinakapangkasalukuyang bersyon.',

# Multipage image navigation
'imgmultipageprev' => '← nakaraang pahina',
'imgmultipagenext' => 'susunod na pahina →',
'imgmultigo'       => 'Punta!',
'imgmultigoto'     => 'Pumunta sa pahinang $1',

# Table pager
'ascending_abbrev'         => 'taas',
'descending_abbrev'        => 'baba',
'table_pager_next'         => 'Susunod na pahina',
'table_pager_prev'         => 'Nakaraang pahina',
'table_pager_first'        => 'Unang pahina',
'table_pager_last'         => 'Huling pahina',
'table_pager_limit'        => 'Ipakita ang $1 aytem bawat pahina',
'table_pager_limit_label'  => 'Mga bagay sa bawat pahina:',
'table_pager_limit_submit' => 'Punta',
'table_pager_empty'        => 'Walang resulta',

# Auto-summaries
'autosumm-blank'   => 'Tinanggal ang laman ng pahina',
'autosumm-replace' => "Ipinapalit ang pahina ng may nilalamang '$1'",
'autoredircomment' => 'Ikinakarga sa [[$1]]',
'autosumm-new'     => "Nilikha ang pahina na may '$1'",

# Live preview
'livepreview-loading' => 'Ikinakarga...',
'livepreview-ready'   => 'Ikinakarga… Handa na!',
'livepreview-failed'  => 'Nabigo ang umiiral na paunang tingin!  Subukan ang normal/pangkaraniwang paunang tingin.',
'livepreview-error'   => 'Hindi tagumpay ang pagkabit (connect): $1 "$2". Subukan ang karaniwang paunang tingin.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Maaaring hindi naipapakita sa talaang ito ang mga pagbabagong mas bago pa kaysa $1 {{PLURAL:$1|segundo|mga segundo}}.',
'lag-warn-high'   => 'Dahil mataas na bilang ng mga naiiwanan/antas ng kabagalan ng serbidor ng kalipunan ng dato,
maaaring hindi naipapakita sa talaang ito ang mga pagbabagong mas bago pa kaysa $1 {{PLURAL:$1|segundo|mga segundo}}.',

# Watchlist editor
'watchlistedit-numitems'       => 'Naglalaman ang iyong talaan ng mga binabantayan ng {{PLURAL:$1|1 pamagat|$1 mga pamagat}}, hindi kabilang ang mga pahina ng usapan.',
'watchlistedit-noitems'        => 'Hindi naglalaman ng mga pamagat ang iyong talaan ng mga binabantayan.',
'watchlistedit-normal-title'   => 'Baguhin ang talaan ng mga binabantayan',
'watchlistedit-normal-legend'  => 'Tanggalin ang mga pamagat mula sa binabantayan',
'watchlistedit-normal-explain' => 'Pinapakita sa ibaba ang mga pamagat na nasa talaan mo ng mga binabantayan.
Para tanggalin ang isang pamagat, lagyan ng tsek ang kahon katabi nito, at pindutin ang "{{int:Watchlistedit-normal-submit}}".
Maaari mo ring [[Special:Watchlist/raw|baguhin ang hilaw na talaan]].',
'watchlistedit-normal-submit'  => 'Tanggalin ang mga Pamagat',
'watchlistedit-normal-done'    => 'Tinatanggal mula sa iyong talaan ng mga binabantayan ang {{PLURAL:$1|1 pamagat|$1 mga pamagat}}:',
'watchlistedit-raw-title'      => 'Baguhin ang hilaw na talaan ng mga binabantayan',
'watchlistedit-raw-legend'     => 'Baguhin ang hilaw na talaan ng mga binabantayan',
'watchlistedit-raw-explain'    => 'Ipinapakita sa ibaba ang mga pamagat na nasa iyong talaan ng mga binabantayan, at maaaring baguhin sa pamamagitan ng pagdaragdag at pagtatanggal mula sa talaan;
isang pamagat bawat linya.
Kapag nakatapos na, pindutin ang "{{int:Watchlistedit-raw-submit}}".
Maaari mo ring [[Special:Watchlist/edit|gamitin ang pangkaraniwang pampatnugot]].',
'watchlistedit-raw-titles'     => 'Mga pamagat:',
'watchlistedit-raw-submit'     => 'Baguhin ang talaan ng mga binabantayan',
'watchlistedit-raw-done'       => 'Isinapanahon ang iyong talaan ng mga binabantayan.',
'watchlistedit-raw-added'      => 'Naidagdag ang {{PLURAL:$1|1 pamagat|$1 mga pamagat}}:',
'watchlistedit-raw-removed'    => 'Natanggal ang {{PLURAL:$1|1 pamagat|$1 mga pamagat}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Tingnan ang kaugnay na mga pagbabago',
'watchlisttools-edit' => 'Tingnan at baguhin ang talaan ng mga binabantayan',
'watchlisttools-raw'  => 'Baguhin ang hilaw na talaan ng mga binabantayan',

# Iranian month names
'iranian-calendar-m1'  => 'Farvardin',
'iranian-calendar-m2'  => 'Ordibehesht',
'iranian-calendar-m3'  => 'Khordad',
'iranian-calendar-m4'  => 'Tir',
'iranian-calendar-m5'  => 'Mordad',
'iranian-calendar-m6'  => 'Shahrivar',
'iranian-calendar-m7'  => 'Mehr',
'iranian-calendar-m8'  => 'Aban',
'iranian-calendar-m9'  => 'Azar',
'iranian-calendar-m10' => 'Dey',
'iranian-calendar-m11' => 'Bahman',
'iranian-calendar-m12' => 'Esfand',

# Hijri month names
'hijri-calendar-m1'  => 'Muharram',
'hijri-calendar-m2'  => 'Safar',
'hijri-calendar-m3'  => "Rabi' al-awwal",
'hijri-calendar-m4'  => "Rabi' al-thani",
'hijri-calendar-m5'  => 'Jumada al-awwal',
'hijri-calendar-m6'  => 'Jumada al-thani',
'hijri-calendar-m7'  => 'Rajab',
'hijri-calendar-m8'  => "Sha'aban",
'hijri-calendar-m9'  => 'Ramadan',
'hijri-calendar-m10' => 'Shawwal',
'hijri-calendar-m11' => "Dhu al-Qi'dah",
'hijri-calendar-m12' => 'Dhu al-Hijjah',

# Hebrew month names
'hebrew-calendar-m1'  => 'Tishrei',
'hebrew-calendar-m2'  => 'Cheshvan',
'hebrew-calendar-m3'  => 'Kislev',
'hebrew-calendar-m4'  => 'Tevet',
'hebrew-calendar-m5'  => 'Shevat',
'hebrew-calendar-m6'  => 'Adar',
'hebrew-calendar-m6a' => 'Adar I',
'hebrew-calendar-m6b' => 'Adar II',
'hebrew-calendar-m7'  => 'Nisan',
'hebrew-calendar-m8'  => 'Iyar',
'hebrew-calendar-m9'  => 'Sivan',
'hebrew-calendar-m10' => 'Tamuz',
'hebrew-calendar-m11' => 'Av',
'hebrew-calendar-m12' => 'Elul',

# Core parser functions
'unknown_extension_tag' => 'Hindi nalalamang tatak ng karugtong na "$1"',
'duplicate-defaultsort' => 'Babala: Madadaig ng susi ng pagtatakdang "$2" ang mas naunang susi ng pagtatakdang "$1".',

# Special:Version
'version'                          => 'Bersyon',
'version-extensions'               => 'Nakaluklok/Nakainstalang mga karugtong',
'version-specialpages'             => 'Natatanging mga pahina',
'version-parserhooks'              => "Mga pangkawit ng banghay (''parser'')",
'version-variables'                => 'Mga bagay na nababago/nagbabago',
'version-other'                    => 'Iba pa',
'version-mediahandlers'            => 'Mga tagahawak/tagapamahala ng midya',
'version-hooks'                    => 'Mga pangkawit',
'version-extension-functions'      => 'Mga tungkuling pangkarugtong',
'version-parser-extensiontags'     => "Mga tatak ng banghay (''parser'')",
'version-parser-function-hooks'    => "Mga pangkawit ng/sa tungkuling pambanghay (''parser'')",
'version-skin-extension-functions' => 'Mga tungkulin ng karugtong na pabalat',
'version-hook-name'                => 'Pangalan ng pangkawit',
'version-hook-subscribedby'        => 'Sinuskribi ng/ni/nina',
'version-version'                  => '(Bersyon $1)',
'version-license'                  => 'Lisensiya',
'version-poweredby-credits'        => "Ang wiking ito ay pinapatakbo ng '''[http://www.mediawiki.org/ MediaWiki]''', karapatang-ari © 2001-$1 $2.",
'version-poweredby-others'         => 'iba pa',
'version-license-info'             => 'Ang MediaWiki ay isang malayang sopwer; maaari mo itong ipamahagi at/o baguhin ito sa ilalim ng mga patakaran ng Pangkalahatang Pangmadlang Lisensiyang GNU ayon sa pagkakalathala ng Pundasyon ng Malayang Sopwer; na maaaring bersyong 2 ng Lisensiya, o (kung nais mo) anumang susunod na bersyon.
Ang MediaWiki ay ipinamamahagi na umaasang magiging gamitin, subaliut WALANG ANUMANG KATIYAKAN; ni walang pahiwatig ng PAGIGING MABENTA o KAANGKUPAN PARA ISANG TIYAK NA LAYUNIN.  Tingnan ang Pangkalahatang Pangmadlang Lisensiyang GNU para sa mas marami pang mga detalye.
Dapat na nakatanggap ka ng [{{SERVER}}{{SCRIPTPATH}}/COPYING isang sipi ng Pangkalahatang Pangmadlang Lisensiyang GNU] kasama ang programang ito; kung hindi, sumulat sa Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA o [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html basahin ito habang nasa Internet].',
'version-software'                 => 'Inistalang software',
'version-software-product'         => 'Produkto',
'version-software-version'         => 'Bersyon',

# Special:FilePath
'filepath'         => 'Lokasyon ng talaksan (file path)',
'filepath-page'    => 'Talaksan:',
'filepath-submit'  => 'Gawin',
'filepath-summary' => 'Ibinabalik ng natatanging pahinang ito ang buong daanan ng isang talaksan.  Ipinapakita ang mga larawan na may buong resolusyon (kalinawan), tuwirang sinimulan ang ibang uri ng mga talaksan sa pamamagitan ng kaugnay nilang mga programa.

Ipasok ang pangalan ng talaksan na hindi kasama ang unlaping "{{ns:image}}:".',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Maghanap ng kaparehong mga talaksan',
'fileduplicatesearch-summary'  => "Maghanap ng mga kaparehong mga talaksan sa baba ng kanyang halaga ng ''hash''.

Ipasok ang pangalan ng talaksan na wala ang unlaping \"{{ns:image}}:\".",
'fileduplicatesearch-legend'   => 'Maghanap ng mga kapareho',
'fileduplicatesearch-filename' => 'Pangalan ng talaksan:',
'fileduplicatesearch-submit'   => 'Hanapin',
'fileduplicatesearch-info'     => '$1 × $2 pixel<br />Laki ng talaksan: $3<br />Uri ng MIME: $4',
'fileduplicatesearch-result-1' => 'Walang katulad ang talaksan na "$1".',
'fileduplicatesearch-result-n' => 'Ang talaksan na "$1" ay may {{PLURAL:$2|1 kapareho|$2 mga kapareho}}.',

# Special:SpecialPages
'specialpages'                   => 'Mga natatanging pahina',
'specialpages-note'              => '----
* Karaniwang natatanging pahina.
* <strong class="mw-specialpagerestricted">Mga pinaghihigpitang natatanging pahina.</strong>',
'specialpages-group-maintenance' => 'Mga pagpapanatiling ulat',
'specialpages-group-other'       => 'Iba pang natatanging mga pahina',
'specialpages-group-login'       => 'Lumagda/tumala',
'specialpages-group-changes'     => 'Mga huling binago at mga tala',
'specialpages-group-media'       => 'Mga ulat ng midya at mga pagkarga',
'specialpages-group-users'       => 'Mga tagagamit at mga karapatan',
'specialpages-group-highuse'     => 'Mga pahinang mataas ang paggamit',
'specialpages-group-pages'       => 'Mga talaan ng mga pahina',
'specialpages-group-pagetools'   => 'Mga kagamitang pang-pahina',
'specialpages-group-wiki'        => 'Kagamitan at datos ng wiki',
'specialpages-group-redirects'   => 'Mga natatanging pahinang pang-talon',
'specialpages-group-spam'        => 'Mga kagamitang pang-spam',

# Special:BlankPage
'blankpage'              => 'Walang laman na pahina',
'intentionallyblankpage' => 'Sinadyang walang laman ang pahinang ito',

# External image whitelist
'external_image_whitelist' => '  #Pabayaang talagang ganito lang ang hanay na ito<pre>
#Ilagay ang mga piraso ng karaniwang pagpapahayag (ang bahagi lang na napupunta sa pagitan ng //) sa ibaba
#Tutugmaan ang mga ito ng mga URL ng panlabas (mainit na kawing) na mga larawan
#Ang mga magtutugma ay ipapakita bilang mga larawan, kung hindi bilang isang kawing lamang patungo sa larawan ang ipapakita
#Ituturing bilang mga kumento ang mga hanay na nagsisimula sa #

#Ilagay sa ibabaw ng hanay na ito ang mga piraso ng karaniwang pagpapahayag.  Pabayaang ganito lang talaga ang hanay na ito</pre>',

# Special:Tags
'tags'                    => 'Tanggap na mga tatak ng pagbabago',
'tag-filter'              => '[[Special:Tags|Tatakan]] ang pansala:',
'tag-filter-submit'       => 'Pansala',
'tags-title'              => 'Mga tatak',
'tags-intro'              => 'Itinatala ng pahinang ito ang mga tatak na maaaring ipantatak ng sopwer sa isang pagbabago, at ang kanilang kahulugan.',
'tags-tag'                => 'Tatakan ang pangalan',
'tags-display-header'     => 'Anyo sa ibabaw ng mga talaan ng pagbabago',
'tags-description-header' => 'Buong paglalarawan ng kahulugan',
'tags-hitcount-header'    => 'Natatakang mga pagbabago',
'tags-edit'               => 'baguhin',
'tags-hitcount'           => '$1 {{PLURAL:$1|pagbabago|mga pagbabago}}',

# Special:ComparePages
'comparepages'     => 'Paghambingin ang mga pahina',
'compare-selector' => 'Paghambingin ang mga pahina ng rebisyon',
'compare-page1'    => 'Pahina 1',
'compare-page2'    => 'Pahina 2',
'compare-rev1'     => 'Rebisyon 1',
'compare-rev2'     => 'Rebisyon 2',
'compare-submit'   => 'Paghambingin',

# Database error messages
'dberr-header'      => 'May isang suliranin ang wiking ito',
'dberr-problems'    => 'Paumanhin! Dumaranas ng mga kahirapang teknikal ang sityong ito.',
'dberr-again'       => 'Subuking maghintay ng ilang mga minuto at muling magkarga.',
'dberr-info'        => '(Hindi makaugnay sa tagapaghain ng kalipunan ng dato: $1)',
'dberr-usegoogle'   => 'Pansamantalang maaaring subukin mong maghanap muna sa pamamagitan ng Google.',
'dberr-outofdate'   => 'Pakiunawang maaaring wala na sa panahon ang kanilang mga talatuntunan ng aming mga nilalaman.',
'dberr-cachederror' => 'Ang sumusunod ay isang nakatagong sipi ng hiniling na pahina, at maaaring wala na sa panahon.',

# HTML forms
'htmlform-invalid-input'       => 'May mga suliran ang ilan sa mga ipinasok mo',
'htmlform-select-badoption'    => 'Ang halagang tinukoy mo ay hindi isang tanggap na pagpili.',
'htmlform-int-invalid'         => 'Ang tinukoy mong halaga ay hindi isang buumbilang.',
'htmlform-float-invalid'       => 'Ang tinukoy mong halagay ay hindi isang bilang.',
'htmlform-int-toolow'          => 'Ang tinukoy mong halaga ay mas mababa kaysa sa pinakamababa ng $1',
'htmlform-int-toohigh'         => 'Ang tinukoy mong halaga ay mahigit kaysa pinakamataas ng $1',
'htmlform-required'            => 'Kailangan ang halagang ito',
'htmlform-submit'              => 'Ipasa',
'htmlform-reset'               => 'Bawiin ang mga pagbabago',
'htmlform-selectorother-other' => 'Iba pa',

# SQLite database support
'sqlite-has-fts' => '$1 na may suportang paghahanap ng buong teksto',
'sqlite-no-fts'  => '$1 na walang suporta ng paghahanap ng buong teksto',

);
