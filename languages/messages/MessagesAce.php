<?php
/** Achinese (Acèh)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Abi Azkia
 * @author Andri.h
 * @author Ayie7791
 * @author Ezagren
 * @author Fadli Idris
 * @author Meno25
 * @author Rachmat.Wahidi
 * @author Sayed Muddasir
 * @author Si Gam Acèh
 */

$fallback = 'id';

$namespaceNames = array(
	NS_MEDIA            => 'Alat',
	NS_SPECIAL          => 'Kusuih',
	NS_TALK             => 'Marit',
	NS_USER             => 'Ureuëng_Nguy',
	NS_USER_TALK        => 'Marit_Ureuëng_Nguy',
	NS_PROJECT_TALK     => 'Marit_$1',
	NS_FILE             => 'Beureukaih',
	NS_FILE_TALK        => 'Marit_Beureukaih',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Marit_MediaWiki',
	NS_TEMPLATE         => 'Pola',
	NS_TEMPLATE_TALK    => 'Marit_Pola',
	NS_HELP             => 'Beunantu',
	NS_HELP_TALK        => 'Marit_Beunantu',
	NS_CATEGORY         => 'Kawan',
	NS_CATEGORY_TALK    => 'Marit_Kawan',
);

$namespaceAliases = array(
	'Istimewa'              => NS_SPECIAL,
	'Bicara'                => NS_TALK,
	'Pembicaraan'           => NS_TALK,
	'Pengguna'              => NS_USER,
	'Bicara_Pengguna'       => NS_USER_TALK,
	'Pembicaraan_Pengguna'  => NS_USER_TALK,
	'Pembicaraan_$1'        => NS_PROJECT_TALK,
	'Berkas'                => NS_FILE,
	'Gambar'                => NS_FILE,
	'Pembicaraan_Berkas'    => NS_FILE_TALK,
	'Pembicaraan_Gambar'    => NS_FILE_TALK,
	'AlatWiki'              => NS_MEDIAWIKI,
	'Marit_AlatWiki'        => NS_MEDIAWIKI_TALK,
	'Pembicaraan_MediaWiki' => NS_MEDIAWIKI_TALK,
	'MediaWiki_Pembicaraan' => NS_MEDIAWIKI_TALK,
	'Templat'               => NS_TEMPLATE,
	'Pembicaraan_Templat'   => NS_TEMPLATE_TALK,
	'Templat_Pembicaraan'   => NS_TEMPLATE_TALK,
	'Bantuan'               => NS_HELP,
	'Bantuan_Pembicaraan'   => NS_HELP_TALK,
	'Pembicaraan_Bantuan'   => NS_HELP_TALK,
	'Kategori'              => NS_CATEGORY,
	'Kategori_Pembicaraan'  => NS_CATEGORY_TALK,
	'Pembicaraan_Kategori'  => NS_CATEGORY_TALK,
	'Gambar_Pembicaraan'    => NS_FILE_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Ureueng_nguy_udep' ),
	'Allmessages'               => array( 'MandumPeusan' ),
	'Allpages'                  => array( 'Dapeuta_on' ),
	'Ancientpages'              => array( 'Teunuleh_trep' ),
	'Blankpage'                 => array( 'On_soh' ),
	'Block'                     => array( 'Theun_ureueng_nguy' ),
	'Blockme'                   => array( 'Theun_lon' ),
	'Booksources'               => array( 'Ne_kitab' ),
	'BrokenRedirects'           => array( 'Peuninah_reuloh' ),
	'Categories'                => array( 'Dapeuta_kawan' ),
	'ChangePassword'            => array( 'Gantoe_lageuem_rahsia' ),
	'Confirmemail'              => array( 'Peunyo_surat-e' ),
	'Contributions'             => array( 'Peuneugot_ureueng_nguy' ),
	'CreateAccount'             => array( 'Peugot_nan' ),
	'Deadendpages'              => array( 'On_mate' ),
	'DeletedContributions'      => array( 'Peuneugot_nyang_geusampoh' ),
	'Disambiguations'           => array( 'Hana_jeulaih' ),
	'DoubleRedirects'           => array( 'Peuninah_ganda' ),
	'Emailuser'                 => array( 'Surat-e_ureueng_nguy' ),
	'Export'                    => array( 'Peuteubiet' ),
	'Fewestrevisions'           => array( 'Neuubah_paleng_dit' ),
	'FileDuplicateSearch'       => array( 'Mita_beureukaih_saban' ),
	'Filepath'                  => array( 'Neuduek_beureukaih' ),
	'Import'                    => array( 'Peutamong' ),
	'Invalidateemail'           => array( 'Peubateue_peusah_surat-e' ),
	'BlockList'                 => array( 'Dapeuta_neutheun' ),
	'LinkSearch'                => array( 'Mita_hubong' ),
	'Listadmins'                => array( 'Dapeuta_ureueng_uroh' ),
	'Listbots'                  => array( 'Dapeuta_bot' ),
	'Listfiles'                 => array( 'Dapeuta_beureukaih' ),
	'Listgrouprights'           => array( 'Dapeuta_khut_(hak)_kawan' ),
	'Listredirects'             => array( 'Dapeuta_peuninah' ),
	'Listusers'                 => array( 'Dapeuta_ureueng_nguy' ),
	'Lockdb'                    => array( 'Gunci_basis_data' ),
	'Log'                       => array( 'Ceunatat' ),
	'Lonelypages'               => array( 'On_hana_soe_po' ),
	'Longpages'                 => array( 'On_panyang' ),
	'MergeHistory'              => array( 'Riwayat_peusapat' ),
	'MIMEsearch'                => array( 'Mita_MIME' ),
	'Mostcategories'            => array( 'Kawan_paleng_le' ),
	'Mostimages'                => array( 'Beureukaih_nyang_paleng_le_geunguy' ),
	'Mostlinked'                => array( 'On_nyang_paleng_le_geunguy' ),
	'Mostlinkedcategories'      => array( 'Kawan_nyang_paleng_le_geunguy' ),
	'Mostlinkedtemplates'       => array( 'Templat_nyang_paleng_le_geunguy' ),
	'Mostrevisions'             => array( 'Neuubah_paleng_le' ),
	'Movepage'                  => array( 'Peupinah_on' ),
	'Mycontributions'           => array( 'Atra_lon_peugot' ),
	'Mypage'                    => array( 'On_lon' ),
	'Mytalk'                    => array( 'Peugah_haba_lon' ),
	'Newimages'                 => array( 'Beureukaih_baro' ),
	'Newpages'                  => array( 'On_baro' ),
	'Popularpages'              => array( 'On_meuceuhu' ),
	'Preferences'               => array( 'Geunalak' ),
	'Prefixindex'               => array( 'Dapeuta_neuaway' ),
	'Protectedpages'            => array( 'On_nyang_geupeulindong' ),
	'Protectedtitles'           => array( 'Nan_nyang_geupeulindong' ),
	'Randompage'                => array( 'On_beurangkari' ),
	'Randomredirect'            => array( 'Peuninah_beurangkari' ),
	'Recentchanges'             => array( 'Neuubah_baro' ),
	'Recentchangeslinked'       => array( 'Neuubah_meuhubong' ),
	'Revisiondelete'            => array( 'Sampoh_peugot_ulang' ),
	'Search'                    => array( 'Mita' ),
	'Shortpages'                => array( 'On_paneuek' ),
	'Specialpages'              => array( 'On_khusoih' ),
	'Statistics'                => array( 'Keunira' ),
	'Tags'                      => array( 'Tag' ),
	'Uncategorizedcategories'   => array( 'Kawan_hana_roh_lam_kawan' ),
	'Uncategorizedimages'       => array( 'Beureukaih_hana_roh_lam_kawan' ),
	'Uncategorizedpages'        => array( 'On_hana_roh_lam_kawan' ),
	'Uncategorizedtemplates'    => array( 'Templat_hana_roh_lam_kawan' ),
	'Undelete'                  => array( 'Peubateue_sampoh' ),
	'Unlockdb'                  => array( 'Peuhah_gunci_basis_data' ),
	'Unusedcategories'          => array( 'Kawan_soh' ),
	'Unusedimages'              => array( 'Beureukaih_hana_teunguy' ),
	'Unusedtemplates'           => array( 'Templat_hana_soe_nguy' ),
	'Unwatchedpages'            => array( 'On_hana_soe_kalon' ),
	'Upload'                    => array( 'Pasoe' ),
	'Userlogin'                 => array( 'Tamong_log' ),
	'Userlogout'                => array( 'Teubiet_log' ),
	'Userrights'                => array( 'Khut_(hak)_ureueng_nguy' ),
	'Version'                   => array( 'Seunalen' ),
	'Wantedcategories'          => array( 'Kawan_nyang_geuh\'eut' ),
	'Wantedfiles'               => array( 'Beureukaih_nyang_geuh\'eut' ),
	'Wantedpages'               => array( 'On_nyang_geuh\'eut' ),
	'Wantedtemplates'           => array( 'Templat_nyang_geuh\'eut' ),
	'Watchlist'                 => array( 'Dapeuta_kalon' ),
	'Whatlinkshere'             => array( 'Hubong_gisa' ),
	'Withoutinterwiki'          => array( 'Hana_interwiki' ),
);

$messages = array(
# User preference toggles
'tog-underline' => 'Bôh garéh yup peunawôt:',
'tog-justify' => 'Peurata paragraf',
'tog-hideminor' => 'Peusom neuandam bacut bak neuubah barô',
'tog-hidepatrolled' => 'Peusom neuandam teurunda bak neuubah barô',
'tog-newpageshidepatrolled' => 'Peusom laman teurunda nibak dapeuta ôn barô',
'tog-extendwatchlist' => 'Peuhah dapeuta keunalön keu peuleumah ban dum neuubah, kön nyang barô mantöng',
'tog-usenewrc' => 'Peusaho neuandam bak neuleumah neuubah barô ngön dapeuta keunalön meunurôt ôn',
'tog-numberheadings' => 'Bôh numbôi nan keudroë',
'tog-showtoolbar' => 'Peuleumah bateuëng alat andam',
'tog-editondblclick' => 'Andam laman ngön duwa gö teugön',
'tog-editsection' => 'Peujeuet andam bideuëng röt hubông [andam]',
'tog-editsectiononrightclick' => 'Peujeuët andam bideueng ngön teugön blah uneun bak nan bideueng',
'tog-showtoc' => 'Peuleumah dapeuta asoe (keu laman-laman nyang na leubèh nibak 3 boh aneuk ulèë)',
'tog-rememberpassword' => 'Ingat lôn tamöng bak peuramban nyoë (keu paléng trép $1 {{PLURAL:$1|uroë}})',
'tog-watchcreations' => 'Tamah laman nyang lôn peugöt u dapeuta keunalön',
'tog-watchdefault' => 'Tamah laman nyang lôn-andam u dapeuta keunalon',
'tog-watchmoves' => 'Tamah laman nyang lôn peupinah u dapeuta keunalon',
'tog-watchdeletion' => 'Tamah laman nyang lôn sampôh u dapeuta keunalon',
'tog-minordefault' => 'Bôh tanda mandum neuandam sibagoe neuandam bacut ngön baku',
'tog-previewontop' => 'Peuleumah hasé yôh goh plôk andam',
'tog-previewonfirst' => 'Peuleumah hasé bak neuandam phôn',
'tog-nocache' => 'Pumaté pumeugöt beun laman peuramban nyoe',
'tog-enotifwatchlistpages' => "Peu'ék surat-e keu lôn meunyo saboh halaman nyang lôn kalon meuubah",
'tog-enotifusertalkpages' => "Peu'ek keu lôn surat-e meunyo ôn marit lôn meuubah",
'tog-enotifminoredits' => "Peu'ék cit surat-e keu lôn bak neuubah ubit",
'tog-enotifrevealaddr' => 'Peuleumah alamat surat-e lôn bak neubrithèë surat-e',
'tog-shownumberswatching' => 'Peuleumah jumeulah ureueng kalon',
'tog-oldsig' => 'Tanda jaroe jinoe:',
'tog-fancysig' => 'Peujeuet tanda jaroe sibagoe naseukah wiki (hana hubông keudroe)',
'tog-externaleditor' => 'Nguy editor eksternal nyang ka na (keu nyang utoih khong, peureulee neuato kusuih bak kompute droeneuh.

[//www.mediawiki.org/wiki/Manual:External_editors Haba leubeh leungkap.])',
'tog-externaldiff' => 'Nguy diff eksternal nyang ka na (keu nyang utoih mantong, peureulee neuato kusuih bak kompute droeneuh
[//www.mediawiki.org/wiki/Manual:External_editors More information.])',
'tog-showjumplinks' => 'Peuudep hubong keu ngon bantu "langsong u"',
'tog-uselivepreview' => 'Ngui peuleumah hasé langsông (baci)',
'tog-forceeditsummary' => 'Peuingat lôn meunyo plôk neuringkaih neuandam mantöng soh',
'tog-watchlisthideown' => 'Peusöm nyang lôn andam nibak dapeuta keunalön',
'tog-watchlisthidebots' => 'Peusöm nyang teuandam nibak dapeuta keunalön',
'tog-watchlisthideminor' => 'Peusom Andam Bacut bak dapeuta keunalön',
'tog-watchlisthideliu' => 'Peusom andam ureuëng ngui nyang tamöng nibak dapeuta keunalön',
'tog-watchlisthideanons' => 'Peusöm andam ureuëng ngui hana taturi nibak dapeuta keunalön',
'tog-watchlisthidepatrolled' => 'Peusom neuandam teukawai bak dapeuta keunalön',
'tog-ccmeonemails' => "Peu'ék keu lôn seunalén surat-e nyang lôn peu'ék keu ureueng la'én",
'tog-diffonly' => 'Bek peuleumah asoë laman di yup beunida neuandam',
'tog-showhiddencats' => 'Peuleumah kawan teusom',
'tog-norollbackdiff' => "Bek peudeuh beunida 'oh lheueh geupeuriwang",

'underline-always' => 'Sabé',
'underline-never' => "H'an tom",
'underline-default' => 'Kulét atawa ngön peuhah wèb teupasang',

# Font style option in Special:Preferences
'editfont-style' => 'Gaya seunurat komputer bak plôk andam',
'editfont-default' => 'Bawaan penjelajah web',
'editfont-monospace' => 'Seunurat Monospace',
'editfont-sansserif' => 'Seunurat Sans-serif',
'editfont-serif' => 'Seunurat Serif',

# Dates
'sunday' => 'Aleuhad',
'monday' => 'Seulanyan',
'tuesday' => 'Seulasa',
'wednesday' => 'Rabu',
'thursday' => 'Hamèh',
'friday' => "Jeumeu'at",
'saturday' => 'Sabtu',
'sun' => 'Aleu',
'mon' => 'Seun',
'tue' => 'Seul',
'wed' => 'Rab',
'thu' => 'Ham',
'fri' => 'Jum',
'sat' => 'Sab',
'january' => 'Buleuën Sa',
'february' => 'Buleuën Duwa',
'march' => 'Buleuën Lhèë',
'april' => 'Buleuën Peuët',
'may_long' => 'Buleuën Limöng',
'june' => 'Buleuën Nam',
'july' => 'Buleuën Tujôh',
'august' => 'Buleuën Lapan',
'september' => 'Buleuën Sikureuëng',
'october' => 'Buleuën Siplôh',
'november' => 'Buleuën Siblaih',
'december' => 'Buleuën Duwa Blaih',
'january-gen' => 'Buleuën Sa',
'february-gen' => 'Buleuën Duwa',
'march-gen' => 'Buleuën Lhèë',
'april-gen' => 'Buleuën Peuët',
'may-gen' => 'Buleuën Limöng',
'june-gen' => 'Buleuën Nam',
'july-gen' => 'Buleuën Tujôh',
'august-gen' => 'Buleuën Lapan',
'september-gen' => 'Buleuën Sikureuëng',
'october-gen' => 'Buleuën Siplôh',
'november-gen' => 'Buleuën Siblaih',
'december-gen' => 'Buleuën Duwa Blaih',
'jan' => 'Sa',
'feb' => 'Duwa',
'mar' => 'Lhèë',
'apr' => 'Peuët',
'may' => 'Limöng',
'jun' => 'Nam',
'jul' => 'Tujôh',
'aug' => 'Lapan',
'sep' => 'Sikureuëng',
'oct' => 'Siplôh',
'nov' => 'Siblaih',
'dec' => 'Duwa Blaih',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Kawan}}',
'category_header' => 'Teunuléh lam kawan "$1"',
'subcategories' => 'Aneuk kawan',
'category-media-header' => 'Peukakaih lam kawan "$1"',
'category-empty' => "''Kawan nyoë jinoë hat hana halaman atawa media.''",
'hidden-categories' => '{{PLURAL:$1|Kawan teusom|Kawan teusom}}',
'hidden-category-category' => 'Kawan teusom',
'category-subcat-count' => '{{PLURAL:$2|Kawan nyoë  cit na saboh yupkawan nyoë.|Kawan nyoë na {{PLURAL:$1|yupkawan|$1 yupkawan}} nyoë, dari ban dum $2.}}',
'category-subcat-count-limited' => 'Kawan nyoë na {{PLURAL:$1|aneuk kawan|$1 aneuk kawan}} lagèë di yup.',
'category-article-count' => '{{PLURAL:$2|Kawan nyoë cit na saboh ôn nyoë.|Kawan nyoë na  {{PLURAL:$1|ôn|$1 ôn }}, dari ban dum $2.}}',
'category-article-count-limited' => 'Kawan nyoë na {{PLURAL:$1|saboh halaman|$1 halaman}} lagèë di yup.',
'category-file-count' => '{{PLURAL:$2|Kawan nyoë cit na beureukaih nyoë sagay.|{{PLURAL:$1|beureukaih|$1 beureukaih}} nyoë na lam kawan nyoë, nibak ban dum $2.}}',
'category-file-count-limited' => 'Kawan nyoe na {{PLURAL:$1|beureukaih|$1 beureukaih}} lagèë di yup.',
'listingcontinuesabbrev' => 'samb.',
'index-category' => 'Laman nyang geuindex',
'noindex-category' => 'Laman nyang hana geuindex',
'broken-file-category' => 'Laman ngön gamba reulöh',

'about' => 'Bhaih',
'article' => 'Teunuléh',
'newwindow' => '(peuhah bak tingkap barô)',
'cancel' => 'Peubateuë',
'moredotdotdot' => 'Lom...',
'morenotlisted' => 'Dapeuta nyoe hana leungkap',
'mypage' => 'Laman',
'mytalk' => 'Marit',
'anontalk' => 'Peugah haba IP nyoë.',
'navigation' => 'Keumudoë',
'and' => '&#32;ngön',

# Cologne Blue skin
'qbfind' => 'Mita',
'qbbrowse' => 'Lop',
'qbedit' => 'Andam',
'qbpageoptions' => 'Laman nyoe',
'qbmyoptions' => 'Laman lôn',
'qbspecialpages' => 'Laman kusuih',
'faq' => 'Teunanyöng Umom',
'faqpage' => 'Project:FAQ',

# Vector skin
'vector-action-addsection' => 'Tamah bhaih',
'vector-action-delete' => 'Sampôh',
'vector-action-move' => 'Pupinah',
'vector-action-protect' => 'Peulindông',
'vector-action-undelete' => 'Bateuë sampôh',
'vector-action-unprotect' => 'Gantoe neulindông',
'vector-simplesearch-preference' => 'Peuudép beunteueng mita biasa (kulét Vèctor khöng)',
'vector-view-create' => 'Peugöt',
'vector-view-edit' => 'Andam',
'vector-view-history' => 'Eu riwayat',
'vector-view-view' => 'Beuët',
'vector-view-viewsource' => 'Eu nè',
'actions' => 'Buët',
'namespaces' => 'Ruweuëng nan',
'variants' => 'Ragam',

'navigation-heading' => 'Menu navigasi',
'errorpagetitle' => 'Seunalah',
'returnto' => 'Gisa u $1.',
'tagline' => 'Nibak {{SITENAME}}',
'help' => 'Beunantu',
'search' => 'Mita',
'searchbutton' => 'Mita',
'go' => 'Jak u',
'searcharticle' => 'Jak u',
'history' => 'Riwayat laman',
'history_short' => 'Riwayat',
'updatedmarker' => 'geuubah yôh seunaweue keuneulheueh lôn phôn kön',
'printableversion' => 'Seunalén rakam',
'permalink' => 'Peunawôt teutap',
'print' => 'Rakam',
'view' => 'Beuët',
'edit' => 'Andam',
'create' => 'Peugöt',
'editthispage' => 'Andam laman nyoë',
'create-this-page' => 'Peugöt laman nyoë',
'delete' => 'Sampôh',
'deletethispage' => 'Sampôh laman nyoë',
'undelete_short' => 'Bateuë sampôh {{PLURAL:$1|one edit|$1 edits}}',
'viewdeleted_short' => 'Eu {{PLURAL:$1|saboh neuandam|$1 neuandam}} nyang geusampôh',
'protect' => 'Peulindông',
'protect_change' => 'ubah',
'protectthispage' => 'Peulindông laman nyoë',
'unprotect' => 'Gantoë neulindông',
'unprotectthispage' => 'Gantoë neulindông laman nyoë',
'newpage' => 'Laman barô',
'talkpage' => 'Peugah haba bhah laman nyoë',
'talkpagelinktext' => 'Marit',
'specialpage' => 'Laman kusuih',
'personaltools' => 'Peukakaih droë',
'postcomment' => 'Beunagi barô',
'articlepage' => 'Eu asoë laman',
'talk' => 'Marit',
'views' => 'Seuneudeuih',
'toolbox' => 'Alat',
'userpage' => 'Eu laman ureuëng ngui',
'projectpage' => 'Eu laman buët',
'imagepage' => 'Eu laman beureukaih',
'mediawikipage' => 'Eu laman peusan sistem',
'templatepage' => 'Eu laman seunaleuëk',
'viewhelppage' => 'Eu laman beunantu',
'categorypage' => 'Eu laman kawan',
'viewtalkpage' => 'Eu laman marit',
'otherlanguages' => 'Bahsa la’én',
'redirectedfrom' => '(Geupeupinah nibak $1)',
'redirectpagesub' => 'Laman peuninah',
'lastmodifiedat' => 'Laman nyoë seuneulheuëh geuubah bak $1 poh $2.',
'viewcount' => 'Laman nyoë ka geusaweuë {{PLURAL:$1|sigo|$sigo}}.<br />',
'protectedpage' => 'Laman teupeulindông',
'jumpto' => 'Grôp u:',
'jumptonavigation' => 'keumudoë',
'jumptosearch' => 'mita',
'view-pool-error' => "Meu'ah, server teungöh sibôk jinoe
Le that ureueng nyang meuh'eut jak eu laman nyoe
Neuprèh si'at yôh goh neubaci lom

$1",
'pool-timeout' => 'Liwat watèë prèh gunci',
'pool-queuefull' => 'Seunapat neuprèh peunoh',
'pool-errorunknown' => 'Salah hana meukön',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Bhaih {{SITENAME}}',
'aboutpage' => 'Project:Bhaih',
'copyright' => "Asoë na meunurôt $1 keucuali meunyö na hay la'én nyang geupeugah",
'copyrightpage' => '{{ns:project}}:Hak karang',
'currentevents' => 'Haba barô',
'currentevents-url' => 'Project:Haba barô',
'disclaimers' => 'Beunantah',
'disclaimerpage' => 'Project:Beunantah umom',
'edithelp' => 'Bantu andam',
'edithelppage' => 'Help:Andam',
'helppage' => 'Help:Asoë',
'mainpage' => 'Ôn Keuë',
'mainpage-description' => 'Ôn Keuë',
'policy-url' => 'Project:Neuatô',
'portal' => 'Meusapat',
'portal-url' => 'Project:Meusapat',
'privacy' => 'Jaga rahsia',
'privacypage' => 'Project:Jaga rahsia',

'badaccess' => 'Salah khut/hak tamöng',
'badaccess-group0' => 'Droeneuh hana geupeuidin keu neupeulaku buët nyang neulakèë',
'badaccess-groups' => 'Buët nyang neulakèë geupeubatah keu ureuëng ngui lam {{PLURAL:$2|kawan|salah saboh nibak kawan}}: $1.',

'versionrequired' => 'Peureulèë MediaWiki vèrsi $1',
'versionrequiredtext' => "MediaWiki versi $1 geupeureulèë keu neungui laman nyoë. Neu'eu [[Special:Version|on versi]]",

'ok' => 'Ka göt',
'retrievedfrom' => 'Geurumpok nibak "$1"',
'youhavenewmessages' => 'Droëneuh na $1 ($2).',
'newmessageslink' => 'peusan barô',
'newmessagesdifflink' => 'neuubah seuneulheuëh',
'youhavenewmessagesfromusers' => "Droeneuh na $1 nibak {{PLURAL:$3|ureueng nguy la'en|$3 ureueng nguy}} ($2).",
'youhavenewmessagesmanyusers' => "Droeneuh na $1 nibak ureueng nguy la'en ($2)",
'newmessageslinkplural' => '{{PLURAL:$1|saboh peusan baro|peusan baro}}',
'newmessagesdifflinkplural' => '{{PLURAL:$1|neuubah}} keuneulheuëh',
'youhavenewmessagesmulti' => 'Droëneuh na padum boh peusan barô bak $1',
'editsection' => 'andam',
'editold' => 'andam',
'viewsourceold' => 'Eu nè',
'editlink' => 'andam',
'viewsourcelink' => 'eu nè',
'editsectionhint' => 'Andam bideuëng: $1',
'toc' => 'Asoë',
'showtoc' => 'peuleumah',
'hidetoc' => 'peusom',
'collapsible-collapse' => 'Peuubeut',
'collapsible-expand' => 'Peuluwaih',
'thisisdeleted' => 'Eu atawa peuriwang $1?',
'viewdeleted' => 'Eu $1?',
'restorelink' => '$1 {{PLURAL:$1|neuandam|neuandam}} nyang ka geusampoh',
'feedlinks' => 'Umpeuën:',
'feed-invalid' => 'Jeunèh neulakèë umpeuën hana paih',
'feed-unavailable' => 'Umpeuën sindikasi hana',
'site-rss-feed' => 'Umpeuën RSS $1',
'site-atom-feed' => 'Umpeuën Atôm $1',
'page-rss-feed' => 'Umpeuën RSS "$1"',
'page-atom-feed' => 'Umpeuën Atom "$1"',
'red-link-title' => '$1 (laman hana)',
'sort-descending' => 'Peuurôt tren',
'sort-ascending' => 'Peuurôt ék',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Laman',
'nstab-user' => 'Ureuëng ngui',
'nstab-media' => 'Laman media',
'nstab-special' => 'Laman kusuih',
'nstab-project' => 'Laman buët',
'nstab-image' => 'Beureukaih',
'nstab-mediawiki' => 'Peusan',
'nstab-template' => 'Seunaleuëk',
'nstab-help' => 'Beunantu',
'nstab-category' => 'Kawan',

# Main script and global functions
'nosuchaction' => 'Hana buët nyan',
'nosuchactiontext' => 'Buët nyang geulakèë lé URL nyan hana sah. Droeneuh kadang salah neukeutik URL, atawa neuseutöt saboh neuhubông nyang hana beutôi. Hai nyoë kadang jeuët keu lageuëm saboh bug bak alat leumiëk nyang geungui lé {{SITENAME}}.',
'nosuchspecialpage' => 'Hana laman kusuih lagèë nyan',
'nospecialpagetext' => "<strong>Droeneuh ka neulakèë laman kusuih nyang hana sah.</strong>
Dapeuta laman kusuih nyang sah jeuet neu'eu bak [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error' => 'Seunalah',
'databaseerror' => 'Kesalahan basis data',
'dberrortext' => 'Na salah bak syntax neulakee basis data.
Nyoe kadang tanda na saboh bug lam alat leumiek.
Neulakee basis data keuneulheueh nakeuh:
<blockquote><code>$1</code></blockquote>
nibak function "<code>$2</code>".
Basis data na salah "<samp>$3: $4</samp>".',
'dberrortextcl' => 'Na salah sintaks bak neulakee basis data.
Neulakee basis data nyang keuneulheueh nakeuh:
"$1"
nibak fungsi "$2"
Basis data geupeuhase salah "$3: $4".',
'laggedslavemode' => 'Peuneugah: Laman nyoë kadang hana neuubah barô',
'readonly' => 'Basis data geurôk',
'enterlockreason' => 'Pasoë dalèh neurôk ngön pajan jeuët geupeuhah',
'readonlytext' => "Basis data hat nyoë geurôk keu teunamöng barô ngön geunantoë la'én, kadang keu peulara basis data rutin, lheuëh nyan barô lagèë biasa teuma.

Ureueng urôh nyang rôk nyoe geupeutaba jeuneulaih nyoe: $1",
'missing-article' => 'Basis data hana jiteumèë naseukah nibak laman nyang sipatôtjih na, nyakni "$1" $2.

Hai nyoë kayém jipeusabab lé peunawôt useuëng u laman nyang ka geusampôh.

Meunyö kön nyoë sababjih, droëneuh kadang ka neuteumeung saboh \'\'bug\'\' lam peukakaih leumiëk.
Neutulông bri thèë hai nyoë keu salah sidroë [[Special:ListUsers/sysop|ureuëng urôih]], ngön neupeugah alamat URL-jih.',
'missingarticle-rev' => '(revisi#: $1)',
'missingarticle-diff' => '(Bida: $1, $2)',
'readonly_lag' => 'Basis data ka geurôk otomatis silawét basis data sekunder teungöh geupeusinkron ngön basis data utama',
'internalerror' => 'Salah bak dalam',
'internalerror_info' => 'Salah bak dalam: $1',
'fileappenderrorread' => 'H\'an jitém beuet "$1" \'oh geutamah',
'fileappenderror' => 'H\'an jeuet jipasoë "$1" u "$2"',
'filecopyerror' => 'H\'an jeuet salén beureukaih "$1" u "$2".',
'filerenameerror' => 'H\'an jeuët bôh nan beureukaih "$1" u "$2".',
'filedeleteerror' => 'H\'an jeuët sampôh beureukaih "$1".',
'directorycreateerror' => 'H\'an jeuet peugöt direktori "$1".',
'filenotfound' => 'Beureukaih "$1" hana meurumpök.',
'fileexistserror' => 'H\'an jeuet geusalén u beureukaih "$1": Beureukaih ka na.',
'unexpected' => 'Yum hana geuharap: "$1"="$2".',
'formerror' => "Reulöh: H'an jeuet peu'ék formulir.",
'badarticleerror' => "Buet nyoe h'an jeuët geupeulaku bak laman nyoe.",
'cannotdelete' => 'Laman atawa beureukaih "$1" h\'an jeuët geusampôh.
Kadang ka na soe sampôh.',
'cannotdelete-title' => 'H\'an jeuet sampôh laman "$1"',
'delete-hook-aborted' => "Seunampôh geupeubateuë lé kaw'ét parser.
Hana jeuneulaih.",
'badtitle' => 'Nan hana beutôi',
'badtitletext' => 'Nan laman nyang neulakèë hana sah, soh, atawa nan antarabahsa atawa antarawiki nyang salah sambông.',
'perfcached' => 'Data di yup nyoe geupeusom ngön kadang kön data barô. {{PLURAL:$1|saboh hase|$1 hase}} maksimum na lam beujana.',
'perfcachedts' => 'Data di yup nyoe geupeusom, ngön geupeubarô seuneulheueh bak $1. {{PLURAL:$4|saboh hase|$4 hase}} paléng le na lam beujana.',
'querypage-no-updates' => "Beunarô keu laman nyoe hat nyoe teungöh h'an jeuët.
Data sinoe h'an geupasoe ulang.",
'wrong_wfQuery_params' => 'Parameter salah u wfQuery()<br />
Meunafaat: $1<br />
Neulakee: $2',
'viewsource' => 'Eu nè',
'viewsource-title' => 'Eu ne keu $1',
'actionthrottled' => 'Buet geupeubataih',
'actionthrottledtext' => 'Sibagoe saboh seunipat lawan-spam, droeneuh geupeubataih nibak neupeulaku buet nyoe le that gö lam watèë paneuk, ngön droeneuh ka leubèh nibak bataih.
Neuci lom lam padum minèt.',
'protectedpagetext' => 'Laman nyoe ka geupeulindông mangat bèk jeuet geuandam',
'viewsourcetext' => 'Droëneuh  jeuët neu’eu',
'viewyourtext' => 'Droëneuh meuidzin kalön ngön neucok nè andam droëneuh u laman nyoë',
'protectedinterface' => 'Halaman nyoe na tèks muka keu muka keu peukakaih leumiëk ngön geupeulindông mangat bek jeuet jipeureuloh.
Keu neuk tamah atawa ubah teujeumah keu ban dum wiki, neungui [//translatewiki.net/ translatewiki.net], proyek lokalisasi MediaWiki.',
'ns-specialprotected' => 'Laman khusuih bèk neuandam',
'titleprotected' => 'Nan nyoe ka geupeulindông nibak neuandam lé [[User:$1|$1]].
Dalèhjih nakeuh "\'\'$2\'\'".',
'invalidtitle-knownnamespace' => 'Nan nyang hana sah ngön ruweueng nan "$2" ngön "$3"',
'exception-nologin' => 'Hana tamöng lom',
'exception-nologin-text' => 'halaman atawa buët nyoe beu neutamöng dilèë bak wiki nyoe.',

# Virus scanner
'virus-unknownscanner' => 'Antivirus hana meuturi:',

# Login and logout pages
'logouttext' => "'''Droeneuh ka neutubiet log.'''

Beuneuteupue meunyoe na padum-padum laman nyang deuh lagèe na neutamöng log, sampoe ka lheuh neupeugléh ''cache''.",
'welcomeuser' => 'Seulamat trôk teuka, $1 !',
'welcomecreation-msg' => 'Nan droëneuh ka geupeugöt. 
Bèk tuwo neuatô [[Special:Preferences|geunalak {{SITENAME}}]] droëneuh.',
'yourname' => 'Ureuëng ngui:',
'yourpassword' => 'Lageuëm:',
'yourpasswordagain' => 'Pasoë lom lageuëm rahsia:',
'remembermypassword' => 'Ingat lôn tamöng bak peuramban nyoë (keu paléng trép $1 {{PLURAL:$1|uroë|days}})',
'yourdomainname' => 'Domain droeneuh:',
'password-change-forbidden' => 'Droëneuh h‘an jeuët neuubah lageuëm rahsia bak wiki nyoë.',
'externaldberror' => 'Na seunalah bak peusahèh basis data luwa atawa droëneuh hana geubri idin keu neupeubarô akun luwa droëneuh',
'login' => 'Tamöng',
'nav-login-createaccount' => 'Tamöng / dapeuta',
'loginprompt' => "Droëneuh suwah/payah neupeu’udép ''cookies'' mangat jeuët neutamong u {{SITENAME}}",
'userlogin' => 'Tamöng / dapeuta',
'userloginnocreate' => 'Tamöng',
'logout' => 'Teubiët',
'userlogout' => 'Teubiët',
'notloggedin' => 'Hana tamöng lom',
'nologin' => 'Goh lom neudapeuta? $1.',
'nologinlink' => 'Peudapeuta nan barô',
'createaccount' => 'Peudapeuta nan barô',
'gotaccount' => 'Ka lheuëh neudapeuta? $1.',
'gotaccountlink' => 'Tamöng',
'userlogin-resetlink' => 'Tuwo rincian tamöng droëneuh?',
'createaccountmail' => 'Neungui lageuëm rahsia beurangkapeuë keu si’at nyoë. Lheuëh nyan neupeu’et u surat-e nyang droëneuh meuh’eut',
'createaccountreason' => 'Choë:',
'badretype' => 'Lageuëm rahsia nyang neupasoë salah.',
'userexists' => "Nan ureuëng ngui nyang neupasoë ka na soë ngui.
Neupiléh nan nyang la'én.",
'loginerror' => 'Salah bak tamöng',
'createaccounterror' => 'H‘an jeuët peudapeuta nan: $1',
'nocookiesnew' => "Nan ureueng ngui nyoe ka meupeugöt, tapi goh meutamöng.
{{SITENAME}} jingui ''cookies'' keu peutamöng ureueng ngui.
''Cookies'' droeneuh hana meupeuudép.
Neupeuudép ''cookies'' dilèe, lheuh nyan neutamöng ngön nan ureueng ngui ngön lageuem rahsia droeneuh.",
'noname' => 'Nan ureuëng ngui nyang Droënueh peutamöng hana sah.',
'loginsuccesstitle' => 'Meuhasé tamöng',
'loginsuccess' => "'''Droëneuh  jinoë ka neutamöng di {{SITENAME}} sibagoë \"\$1\".'''",
'nosuchuser' => 'Hana ureuëng ngui ngön nan "$1".
Nan ureuëng ngui jipeubida haraih rayek.
Tulông neuparéksa keulayi neuija Droëneuh, atawa [[Special:UserLogin/signup|neudapeuta barô]].',
'nosuchusershort' => 'Hana ureuëng ngui ngön nan "$1".
Préksa keulayi neu’ija Droëneuh.',
'nouserspecified' => 'Neupasoë nan Droëneuh.',
'login-userblocked' => 'Ureuëng ngui nyoë ka teublokir, hana idin/hanjeut tamöng.',
'wrongpassword' => 'Lageuëm nyang neupasoë salah. Neuci lom.',
'wrongpasswordempty' => 'Droëneuh hana neupasoë lageuëm. Neuci lom.',
'passwordtooshort' => "Lageuëm paléng h'an harôh na {{PLURAL:$1|1 karakter|$1 karakter}}.",
'password-name-match' => 'Lageuëm Droeuneuh beubida nibak nan Ureuëng ngui.',
'password-login-forbidden' => 'Ngui nan ureuëng ngui ngön lageuëm nyoë ka jitham.',
'mailmypassword' => "Peu'ét lageuëm rahsia barô u surat-e",
'passwordremindertitle' => 'Lageuëm seumeuntara barô keu {{SITENAME}}',
'passwordremindertext' => 'Salah sidroë (kadang Droëneuh, ngön alamat IP $1) geulakèë lageuëm barô keu {{SITENAME}} ($4). Lageuëm si\'at keu ureuëng ngui "$2" ka geupeuna ngön ka geuatô jeuet keu "$3". Meunyö nyoe nakeuh meukeusud droeneuh, droeneuh peureulèë neutamöng ngön neupiléh lageuëm barô jinoe. Lageuem siat droeneuh meung abéh lam {{PLURAL:$5|siuroe|$5 uroe}}.

Meunyö ureuëng la\'én nyang peugöt neulakèë nyoe, atawa meunyö droeneuh ka neuingat lageuëm droeneuh, ngön droeneuh h\'an ék neugantoë lé, droeneuh jeuet hana neupeureumeuën peusan nyoe ngön neulanjut neungui lageuem awaineuh.',
'noemail' => 'Hana alamat surat-e nyang teucatat keu ureuëng ngui "$1".',
'noemailcreate' => 'Droeneuh suwah neuseudia alamt surat-e nyang jeut ngui.',
'passwordsent' => 'Lageuëm barô ka geupeu\'et u surat-e nyang geupeudapeuta keu "$1". Neutamöng teuma lheuëh neuteurimöng surat-e nyan.',
'eauthentsent' => 'Saboh surat-e keu peunyö ka geukirém u alamat surat-e Droëneuh. Droëneuh beuneuseutöt préntah lam surat nyan keu neupeunyö meunyö alamat nyan nakeuh beutôi atra Droëneuh. {{SITENAME}} h‘an geupeuudép surat Droëneuh meunyö langkah nyoë hana neupeubuet lom.',
'cannotchangeemail' => 'Alamat surat-e han jeut geugantoe bak wiki nyoe.',
'emaildisabled' => 'Situs nyoe han jeut geukirém surat-e.',
'accountcreated' => 'Ureuëng ngui ka teupeugöt',
'accountcreatedtext' => 'Ureuëng ngui keu [[{{ns:User}}:$1|$1]]([[{{ns:User talk}}:$1|talk]]) ka teupeugöt.',
'createaccount-title' => 'Peugöt ureuëng ngui keu {{SITENAME}}',
'usernamehasherror' => 'Nan ureueng ngui han jeut na tanda pageue',
'loginlanguagelabel' => 'Bahsa: $1',

# Email sending
'user-mail-no-addy' => 'Ujoe kirém surat-e ngön hana alamat surat-e.',

# Change password dialog
'resetpass' => 'Gantoë lageuëm rahsia',
'resetpass_header' => 'Gantoë lageuëm rahsia nan ureuëng ngui',
'oldpassword' => 'Lageuëm rahsia awai:',
'newpassword' => 'Lageuëm rahsia barô:',
'retypenew' => 'Pasoë lom lageuëm barô:',
'resetpass_submit' => 'Atô lageuëm rahsia lheuëh nyan tamöng',
'resetpass_forbidden' => "Lageuëm rahsia h'an jeuët geugantoë",
'resetpass-no-info' => "Droëneuh suwah neutamöng mangat jeuët neu'eu laman nyoë",
'resetpass-submit-loggedin' => 'Gantoë lageuëm rahsia',
'resetpass-submit-cancel' => 'Pubateuë',
'resetpass-temp-password' => 'Lageuem rahsia keu siat:',

# Special:PasswordReset
'passwordreset-username' => 'Ureueng ngui:',
'passwordreset-capture' => 'Eu hasé surat-e?',
'passwordreset-email' => 'Alamat surat-e:',
'passwordreset-emailtitle' => 'Teuneurang nan ureueng ngui bak {{SITENAME}}',

# Special:ChangeEmail
'changeemail' => 'Gantoe alamat surat-e',
'changeemail-header' => 'Gantoe alamat surat-e',
'changeemail-no-info' => "Droeneuh suwah neutamöng mangat jeuet neu'eu laman nyoe",
'changeemail-oldemail' => 'Alamat surat-e jinoe:',
'changeemail-newemail' => 'Alamat surat-e barô:',
'changeemail-none' => '(hana)',
'changeemail-password' => 'Lageuem rahsia {{SITENAME}} droeneuh:',
'changeemail-submit' => 'Gantoe surat-e',
'changeemail-cancel' => 'Peubateue',

# Edit page toolbar
'bold_sample' => 'Rakam teubai',
'bold_tip' => 'Haraih teubai',
'italic_sample' => 'Rakam singèt naseukah nyoë',
'italic_tip' => 'Rakam singèt',
'link_sample' => 'Nan peunawôt',
'link_tip' => 'Peunawôt dalam',
'extlink_sample' => 'http://www.example.com nan peunawôt',
'extlink_tip' => 'Peunawôt luwa (neubôh http:// bak awai)',
'headline_sample' => 'Naseukah nan',
'headline_tip' => 'Aneuk beunagi tingkat 1',
'nowiki_sample' => 'Bèk format naseukah nyoë',
'nowiki_tip' => 'Bèk seutot beuntuk wiki',
'image_tip' => 'Pasoë beureukaih',
'media_tip' => 'Peunawôt beureukaih',
'sig_tip' => 'Tanda jaroë Droëneuh ngön tanda watèë',
'hr_tip' => 'Garéh data',

# Edit pages
'summary' => 'Éhtisa:',
'subject' => 'Bhah/nan:',
'minoredit' => 'Nyoë lôn andam bacut',
'watchthis' => 'Kalön laman nyoë',
'savearticle' => 'Keubah laman',
'preview' => 'Eu dilèë',
'showpreview' => 'Peuleumah hasé',
'showdiff' => 'Peuleumah neuubah',
'anoneditwarning' => 'Droëneuh   hana teudapeuta tamong. Alamat IP Droëneuh   teucatat lam tarèh (riwayat away) ôn nyoë.',
'summary-preview' => 'Eu dilèë neuringkaih:',
'blockedtitle' => 'Ureueng ngui geutheun',
'blockedtext' => "'''Nan ureuëng nguy atawa alamat IP Droëneuh  ka geutheun.'''

Geutheun lé $1. Dalèh jih nakeuh ''$2''.

* Geutheun yôh: $8
* Neutheun maté tanggay bak: $6
* Nyang geutheun: $7

Droëneuh   jeuët neutanyong bak $1 atawa [[{{MediaWiki:Grouppage-sysop}}|nyang urôh nyang la’én]] keu peugah haba bhah nyoë.

Droëneuh   h’an jeuët neunguy alat 'Kirém surat-e ureuëng nguy nyoë' keucuali ka neupasoë alamat surat-e nyang sah di [[Special:Preferences|Geunalak]] Droëneuh ngön Droëneuh ka geutheun keu nguy nyan.

Alamat IP Droëneuh nakeuh $3, ngön ID neutheun nakeuh $5. Tulông peuseureuta salah saboh atawa ban duwa beurita nyoë bak tiëp teunanyöng nyang neupeugöt.",
'autoblockedtext' => "'''Nan ureuëng nguy atawa alamat IP Droëneuh  ka geutheun.'''

Geutheun lé $1. Dalèh jih nakeuh ''$2''.

* Geutheun yôh: $8
* Neutheun maté tanggay bak: $6
* Nyang geutheun: $7

Droëneuh   jeuët neutanyong bak $1 atawa [[{{MediaWiki:Grouppage-sysop}}|nyang urôh nyang la’én]] keu peugah haba bhah nyoë.

Droëneuh   h’an jeuët neunguy alat 'Kirém surat-e ureuëng nguy nyoë' keucuali ka neupasoë alamat surat-e nyang sah di [[Special:Preferences|Geunalak]] Droëneuh ngön Droëneuh ka geutheun keu nguy nyan.

Alamat IP Droëneuh nakeuh $3, ngön ID neutheun nakeuh $5. Tulông peuseureuta salah saboh atawa ban duwa beurita nyoë bak tiëp teunanyöng nyang neupeugöt.",
'blockednoreason' => 'hana dalèh nyang geubri',
'whitelistedittext' => 'Droeneuh suwah $1 keu neuandam ôn.',
'nosuchsectiontitle' => 'Bideueng hana geutumèe',
'loginreqtitle' => 'Droeneuh payah neutamöng log.',
'loginreqlink' => 'tamöng',
'loginreqpagetext' => "Droeneuh payah $1 keu neu'eu ôn-ôn la'én.",
'accmailtitle' => 'Lageuem rahsia ka meukirém',
'newarticle' => '(Barô)',
'newarticletext' => "Droëneuh ka neuseutöt peunawôt u laman nyang goh na.
Keu neupeugöt laman nyan, neukeutik lam plôk di yup (eu [[{{MediaWiki:Helppage}}|laman beunantu]] keu haba leubèh le).
Meunyö droëneuh trôk keunoë hana neusaja, neuteugön tèk '''back''' bak ''browser'''droëneuh.",
'anontalkpagetext' => "----''Nyoe nakeuh ôn marit ureueng ngui nyang hana tamöng atawa hana geungui.''
Saweub nyan, kamoe payah meukubah alamat IP-geuh keu meuparéksa. 
Alamat IP mungkén jingui lé padum-padum droe ureueng.
Meunyoe droeneuh ureueng nyang hana tamöng nyan, tulông [[Special:UserLogin/signup|peugöt nan ureueng ngui]] atawa [[Special:UserLogin|tamöng log]] mangat meuteugah nibak bhah nyang hana meuphôm bak uroe la'én.",
'noarticletext' => 'Hana naseukah jinoë lam laman nyoë.
Ji Droëneuh jeuët [[Special:Search/{{PAGENAME}}|neumita keu nan ôn nyoë]] bak ôn-ôn la’én, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} log nyang na hubôngan], atawa [{{fullurl:{{FULLPAGENAME}}|action=edit}} neu\'andam ôn nyoë]</span>.',
'noarticletext-nopermission' => 'Hana asoë bak laman nyoë jinoë.
Droëneuh jeuët [[Special:Search/{{PAGENAME}}|neumita keu nan ôn nyoë]] bak laman-laman la\'én,
atawa <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} neumita log nyang na meuhubông]</span>, tapi Droëneuh hana idin keu neupeugöt laman nyoë',
'userpage-userdoesnotexist-view' => 'Ureueng ngui "$1" hana teudapeuta.',
'updated' => '(Seubarô)',
'note' => "'''Hareutoë:'''",
'previewnote' => "'''Beu neuingat meunyo laman nyoë goh lom neukeubah!'''",
'editing' => 'Andam $1',
'creating' => 'Teungöh meupeugöt $1',
'editingsection' => 'Andam $1 (bideuëng)',
'editingcomment' => 'Andam $1 (bideuëng)',
'storedversion' => 'Riwayat meukubah',
'yourdiff' => 'Bida',
'copyrightwarning' => "Beu neuingat bahwa ban mandum nyang Droëneuh   tuléh keu {{SITENAME}} geukira geupeuteubiët di yup $2 (ngiëng $1 keu leubèh jeulah). Meunyoë Droëneuh h‘an neutém teunuléh Droëneuh  ji’andam ngön jiba ho ho la’én, bèk neupasoë teunuléh Droëneuh  keunoë.<br />Droëneuh  neumeujanji chit meunyoë teunuléh nyoë nakeuh atra neutuléh keudroë, atawa neucok nibak nè nè atra umôm atawa nè bibeuëh la’én.
'''BÈK NEUPASOË TEUNULÉH NYANG GEUPEULINDÔNG HAK KARANG NYANG HANA IDIN'''",
'templatesused' => '{{PLURAL:$1|Templat|Templates}} nyang geungui bak laman nyoë:',
'templatesusedpreview' => '{{PLURAL:$1|Templat|Templates}} nyang geungui bak eu dilèë nyoë:',
'template-protected' => '(geulindông)',
'template-semiprotected' => '(siteungoh-lindông)',
'hiddencategories' => 'Laman nyoë nakeuh anggèëta nibak {{PLURAL:$1|1 kawan teusom |$1 kawan teusom}}:',
'nocreatetext' => '{{SITENAME}} ka jiköt bak peugöt laman barô. Ji Droëneuh   jeuët neuriwang teuma ngön neu’andam laman nyang ka na, atawa [[Special:UserLogin|neutamong atawa neudapeuta]].',
'nocreate-loggedin' => 'Droeneuh hana khut keu neupeugöt laman-laman barô.',
'sectioneditnotsupported-title' => 'Andam bideung hana meudukông',
'sectioneditnotsupported-text' => 'Andam bideung hana meudukông bak ôn nyoe.',
'permissionserrors' => 'Salah khut/hak tamöng',
'permissionserrorstext' => 'Droëneuh hana hak tamöng keu $2, muroë {{PLURAL:$1|choë|choë}} nyoë:',
'permissionserrorstext-withaction' => 'Droëneuh hana hak tamöng keu $2, muroë {{PLURAL:$1|choë|choë}} nyoë:',
'recreate-moveddeleted-warn' => "'''Ingat: Droëneuh neupeugöt ulang saboh laman nyang ka tom geusampôh. ''',

Neutimang-timang dilèë peuë ék patôt neupeulanjut atra nyang teungöh neu’andam.
Nyoë pat nakeuh log seunampôh nibak laman nyoë:",
'moveddeleted-notice' => 'Laman nyoë ka geusampôh.
Log seunampôh ngön log pinah laman nyoë geupeuseudia di yup nyoë keu keuneubah.',
'log-fulllog' => 'Eu ban dum ceunatat',
'edit-hook-aborted' => "Seunampôh geupeubateuë lé kaw'ét parser.
Hana jeuneulaih.",
'edit-gone-missing' => 'Han jeut peubarô ôn.
Ôn nyoe mungkén ka geusampôh.',
'edit-already-exists' => 'Han jeut peugöt ôn barô.
Ôn nyoe ka lheuh na.',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''Ingat:''' Seunipat seunaleuëk nyang neunguy rayek that.
Ladôm seunaleuëk hana geupeurôh",
'post-expand-template-inclusion-category' => 'Laman ngön seunipat seunaleuëk nyang leubèh bataih',
'post-expand-template-argument-warning' => "'''Ingat:''' Laman nyoe na paléng h'an saboh alasan seunaleuëk nyang na sunipat èkspansi nyang raya that.
Alasan-alasan nyan hana geupeureumeuën.",
'post-expand-template-argument-category' => 'Laman ngön dalèh seunaleuëk nyang hana geupeureumeuën',

# Account creation failure
'cantcreateaccounttitle' => 'Han jeut peugöt nan ureueng ngui',
'cantcreateaccount-text' => "Peuneugöt nan ureueng ngui nibak alamat IP ('''$1''') ka geutheun lé [[User:$3|$3]].

Dalèh $3 nyoe nakeuh ''$2''",

# History pages
'viewpagelogs' => 'Eu log laman nyoë',
'nohistory' => 'Hana riwayat neuandam awai keu ôn nyoe.',
'currentrev' => 'Geunantoë jinoë',
'currentrev-asof' => 'Geunantoë barô bak $1',
'revisionasof' => 'Geunantoë tiëp $1',
'revision-info' => 'Geunantoë tiëp $1; $2',
'previousrevision' => '← Geunantoë awai',
'nextrevision' => 'Geunantoë lheuëh nyan→',
'currentrevisionlink' => 'Geunantoë jinoë',
'cur' => 'jin',
'next' => 'u keu',
'last' => 'sigohlom',
'page_first' => 'phôn',
'page_last' => 'keuneulheuëh',
'histlegend' => "Piléh duwa teuneugön radiô, lheuëh nyan teugön teuneugön ''peubandéng'' keu peubandéng seunalén. Teugön saboh tanggay keu eu seunalén ôn bak tanggay nyan.<br />(skr) = bida ngön seunalén jinoë, (akhé) = bida ngön seunalén sigohlomjih. '''u''' = andam ubeut, '''b''' = andam bot, → = andam bideuëng, ← = ehtisa keudroë",
'history-fieldset-title' => 'Eu riwayat awai',
'history-show-deleted' => 'Nyang geusampôh mantöng',
'histfirst' => 'paléng trép',
'histlast' => 'paléng barô',
'historyempty' => '(soh)',

# Revision feed
'history-feed-title' => 'Riwayat neupeupah',
'history-feed-description' => 'Riwayat neupeupah keu ôn nyoe bak wiki',
'history-feed-item-nocomment' => '$1 bak $2',

# Revision deletion
'rev-deleted-comment' => '(mohtasa neuandam geusampôh)',
'rev-deleted-user' => '(nan ureueng ngui geusampôh)',
'rev-deleted-user-contribs' => '[nan ureueng ngui atawa alamat IP geusampôh - neuandam geupeusom bak dapeuta beuneuri]',
'rev-delundel' => 'peuleumah/peusom',
'rev-showdeleted' => 'peudeuh',
'revdelete-show-file-submit' => 'Nyoe',
'revdelete-hide-comment' => 'Mohtasa neuandam',
'revdelete-radio-same' => '(bèk neugantoe)',
'revdelete-radio-set' => 'Deuh',
'revdelete-radio-unset' => 'Teusom',
'revdelete-log' => 'Dalèh:',
'revdel-restore' => 'Gantoë seuneudeuih',
'revdel-restore-deleted' => 'geunantoe nyang ka geusampôh',
'revdel-restore-visible' => 'geunantoë nyang deuih',
'pagehist' => 'Taréh laman',
'deletedhist' => 'Taréh nyang meusampôh',

# History merging
'mergehistory-from' => 'Asai ôn:',
'mergehistory-invalid-source' => 'Asai ôn payah nan nyang beutôi.',
'mergehistory-reason' => 'Dalèh:',

# Merge log
'mergelog' => 'Peugabông log',
'revertmerge' => 'Hana jadèh peugabông',

# Diffs
'history-title' => 'Riwayat geunantoë nibak "$1"',
'lineno' => 'Baréh $1:',
'compareselectedversions' => 'Peubandéng curak teupiléh',
'editundo' => 'pubateuë',
'diff-multi' => '({{PLURAL:$1|Saboh|$1}} geunantoë antara nyang geupeugot le {{PLURAL:$2|sidroe|$2}} ureueng nguy hana geupeuleumah)',

# Search results
'searchresults' => 'Hasé mita',
'searchresults-title' => 'Hasé mita keu "$1"',
'searchresulttext' => 'Keu beurita leubèh le bhah meunita bak {{SITENAME}}, eu [[{{MediaWiki:Helppage}}|ôn beunantu]].',
'searchsubtitle' => 'Droëneuh neumita \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|ban dum ôn nyang geupuphôn ngön "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|bandum ôn nyang teuhubông u "$1"]])',
'searchsubtitleinvalid' => "Droëneuh neumita '''$1'''",
'notitlematches' => 'Hana nan laman nyang pah',
'notextmatches' => 'Hana naseukah laman nyang pah',
'prevn' => '{{PLURAL:$1|$1}} sigohlomjih',
'nextn' => '{{PLURAL:$1|$1}} lheuëh nyan',
'prevn-title' => '$1 {{PLURAL:$1|hasé|hasé}} sigohlomjih',
'nextn-title' => '$1 {{PLURAL:$1|hasé}} lheuëh nyan',
'shown-title' => 'Peuleumah $1 {{PLURAL:$1|hasé}} tiëp laman',
'viewprevnext' => 'Eu ($1 {{int:pipe-separator}} $2)($3)',
'searchmenu-exists' => "'''Na laman ngön nan \"[[:\$1]]\" bak wiki nyoe.'''",
'searchmenu-new' => "'''Peugöt laman \"[[:\$1]]\" bak wiki nyoë!'''",
'searchhelp-url' => 'Help:Asoë',
'searchprofile-articles' => 'Laman asoë',
'searchprofile-project' => 'Laman Beunantu ngön Buët',
'searchprofile-images' => 'Multimedia',
'searchprofile-everything' => 'Ban dum',
'searchprofile-advanced' => 'Tingkat lanjut',
'searchprofile-articles-tooltip' => 'Mita bak $1',
'searchprofile-project-tooltip' => 'Mita bak $1',
'searchprofile-images-tooltip' => 'Mita beureukaih',
'searchprofile-everything-tooltip' => 'Mita ban dum laman asoë (rôh ôn marit)',
'searchprofile-advanced-tooltip' => 'Mita bak ruweuëng nan meupat-pat',
'search-result-size' => '$1 ({{PLURAL:$2|1 narit|$2 narit}})',
'search-result-category-size' => '{{PLURAL:$1|1 anggeeta|$1 anggeeta}} ({{PLURAL:$2|1 aneuk kawan|$2 aneuk kawan}}, {{PLURAL:$3|1 beureukaih|$3 beureukaih}})',
'search-redirect' => '(peuninah $1)',
'search-section' => '(bideuëng $1)',
'search-suggest' => 'Kadang meukeusud Droëneuh nakeuh: $1',
'search-interwiki-caption' => 'Buët la’én',
'search-interwiki-default' => 'Hasé $1:',
'search-interwiki-more' => '(lom)',
'searchrelated' => 'meusambat',
'searchall' => 'ban dum',
'showingresultsheader' => "{{PLURAL:$5|Hase '''$1''' nibak '''$3'''|Hase '''$1 - $2''' nibak '''$3'''}} keu '''$4'''",
'nonefound' => "'''Teuneurang''': Ladôm ruweuëng nan mantöng nyang geumita. 
Neubaci puphôn neulakèë droëneuh ngön ''all:'' keu jak mita ban dum asoë (rôh lam nyan laman marit, seunaleuëk, ngön nyang la’én nibak nyan), atawa neungui ruweuëng nan nyang neumeuh’eut sibagoë neuawai.",
'search-nonefound' => 'Hana hasé nyang paih lagèë neulakèë',
'powersearch' => 'Mita lanjut',
'powersearch-legend' => 'Mita lanjut',
'powersearch-ns' => 'Mita bak ruweuëng nan:',
'powersearch-redir' => 'Dapeuta peuninah',
'powersearch-field' => 'Mita',
'powersearch-toggleall' => 'Ban dum',
'powersearch-togglenone' => 'Hana',

# Preferences page
'preferences' => 'Galak',
'mypreferences' => 'Atô',
'prefs-edits' => 'Jumeulah neuandam:',
'prefsnologin' => 'Hana tamöng lom',
'changepassword' => 'Gantoe lageuem rahsia',
'prefs-skin' => 'Kulét',
'skin-preview' => 'Eu dilèe',
'datedefault' => 'Hana geunalak',
'prefs-beta' => 'Fitur bèta',
'prefs-datetime' => 'Uroe ngön jeum',
'prefs-user-pages' => 'Laman ureueng ngui',
'prefs-personal' => 'Profil ureueng ngui',
'prefs-rc' => 'Ban meuubah',
'prefs-watchlist' => 'Dapeuta keunalön',
'prefs-watchlist-days' => 'Jumeulah uroe nyang meupeudeuh bak dapeuta keunalön:',
'prefs-watchlist-days-max' => '{{PLURAL:$1|uroë}}',
'prefs-misc' => "La'én-la'én",
'prefs-resetpass' => 'Gantoe lageuem rahsia',
'prefs-changeemail' => 'Gantoe alamat surat-e',
'prefs-setemail' => 'Pasoe alamat surat-e',
'prefs-email' => 'Peuniléh surat-e',
'prefs-rendering' => 'Seuneudeuh',
'saveprefs' => 'Kubah',
'resetprefs' => 'Peugléh neuubah nyang goh meukubah',
'prefs-editing' => 'Neuandam',
'rows' => 'Baréh:',
'searchresultshead' => 'Mita',
'resultsperpage' => 'Hasé lam saboh laman:',
'stub-threshold-disabled' => 'Geupeumaté',
'timezoneuseoffset' => "La'én (peuteuntèe bidajih)",
'timezoneoffset' => 'Bida:',
'timezoneregion-america' => 'Amirika',
'timezoneregion-antarctica' => 'Antartika',
'timezoneregion-atlantic' => 'Laôt Atlantik',
'timezoneregion-europe' => 'Ierupa',
'timezoneregion-indian' => 'Laôt India',
'timezoneregion-pacific' => 'Laôt Pasifik',
'allowemail' => "Peuudép surat-e nibak ureueng ngui la'én",
'prefs-searchoptions' => 'Mita',
'prefs-namespaces' => 'Ruweuëng nan',
'defaultns' => 'Atawa neumita lam ruweueng nan nyoe:',
'default' => 'meuneumat',
'prefs-files' => 'Beureukaih',
'youremail' => 'Surat-e:',
'prefs-registration' => 'Watèe neudapeuta:',
'yourrealname' => 'Nan aseuli:',
'yourlanguage' => 'Bahsa:',
'yournick' => 'Tanda jaroe barô:',
'prefs-help-signature' => 'Komèntar bak ôn marit suwah neubôh "<nowiki>~~~~</nowiki>", nyang eunteuk meugantoe keu tanda jaroe droeneuh ngön watèe jinoe.',
'badsiglength' => 'Tanda jaroe droeneuh panyang that.
Panyangjih bèk leubèh nibak $1 {{PLURAL:$1|haraih|haraih}}.',
'gender-unknown' => 'Hana geupeunyata',
'gender-male' => 'Ureueng agam',
'gender-female' => 'Ureueng inöng',
'email' => 'Surat-e',
'prefs-help-realname' => '* Nan aseuli hana meucéh neupasoë.
Meunyö neupasoë, euntreuk nan Droëneuh nyan geupeuleumah mangat jitupeuë soë nyang tuléh.',
'prefs-help-email' => 'Alamat surat-e hana meucéh na, tapi geupeureulèë keu seumeugöt ulang lageuem, meunyö droeneuh tuwö lageuëm.',
'prefs-help-email-others' => "Droeneuh jeuet cit neupiléh neupubiyeuë ureuëng la'én geupeu'et surat keu droeneuh röt surat-e röt seunambat bak laman ureueng ngui atawa on mariët.
Surat-e droeneuh h'an geupeugah keu ureuëng nyan.",
'prefs-help-email-required' => 'Peureulèe alamat surat-e.',
'prefs-signature' => 'Tanda jaroe',
'prefs-dateformat' => 'Format uroe/watèe',
'prefs-timeoffset' => 'Bida watèe',
'prefs-advancedediting' => 'Peuniléh umom',
'prefs-diffs' => 'Bida',

# User preference: email validation using jQuery
'email-address-validity-valid' => 'Alamat surat-e sah',
'email-address-validity-invalid' => 'Pasoe alamat surat-e nyang sah',

# User rights
'userrights-user-editname' => 'Pasoe nan ureueng ngui:',
'editusergroup' => 'Ubah kawan ureueng ngui',
'editinguser' => "Gantoe khut ureueng ngui '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => 'Ubah kawan ureueng ngui',
'saveusergroups' => 'Ubah kawan ureueng ngui',
'userrights-groupsmember' => 'Anggèeta nibak:',
'userrights-reason' => 'Dalèh:',
'userrights-no-interwiki' => "Droeneuh hana izin keu neuubah khut ureueng ngui bak wiki la'én.",
'userrights-notallowed' => 'Droeneuh hana izin keu neutamah atawa neupeugadöh khut ureueng ngui.',
'userrights-changeable-col' => 'Kawan nyang jeut neugantoe',
'userrights-unchangeable-col' => 'Kawan nyang han jeut neugantoe',

# Groups
'group' => 'Kawan:',
'group-user' => 'Ureueng-ureueng ngui',
'group-autoconfirmed' => 'Ureueng ngui nyang meu-konfirmasi otomatis',
'group-sysop' => 'Ureuëng urôh',
'group-bureaucrat' => 'Birôkrat',
'group-suppress' => 'Ureueng kalön',
'group-all' => '(ban dum)',

'group-user-member' => '{{GENDER:$1|ureueng ngui}}',
'group-autoconfirmed-member' => '{{GENDER:$1|ureueng ngui meu-konfirmasi otomatis}}',
'group-bot-member' => '{{GENDER:$1|bot}}',
'group-sysop-member' => '{{GENDER:$1|ureueng urôh}}',
'group-bureaucrat-member' => '{{GENDER:$1|birôkrat}}',
'group-suppress-member' => '{{GENDER:$1|ureueng kalön}}',

'grouppage-user' => '{{ns:project}}:Ureueng ngui',
'grouppage-autoconfirmed' => '{{ns:project}}:Ureueng ngui meu-konfirmasi otomatis',
'grouppage-bot' => '{{ns:project}}:Bots',
'grouppage-sysop' => '{{ns:project}}:Ureuëng urôh',
'grouppage-bureaucrat' => '{{ns:project}}:Birôkrat',
'grouppage-suppress' => '{{ns:project}}:Ureueng kalön',

# Rights
'right-read' => 'Beuet laman',
'right-edit' => 'Andam laman',
'right-createpage' => 'Peugöt laman barô (nyang kön laman marit)',
'right-createtalk' => 'Peugöt ôn marit',
'right-createaccount' => 'Peugöt nan ureueng ngui barô',
'right-minoredit' => 'Bôh tanda seubagoe andam ubeut',
'right-move' => 'Pinah laman',
'right-move-subpages' => 'Pinah laman ngön ban dum aneuk laman',
'right-move-rootuserpages' => 'Pinah laman ureueng ngui',
'right-movefile' => 'Pinah beureukaih',
'right-upload' => 'Peutamöng beureukaih',
'right-upload_by_url' => 'Peutamöng beureukaih nibak URL',
'right-delete' => 'Sampôh laman',
'right-bigdelete' => 'Sampôh laman ngön ban dum riwayatjih',
'right-browsearchive' => 'Mita laman nyang geusampôh',

# Special:Log/newusers
'newuserlogpage' => 'Ureuëng ngui barô',

# User rights log
'rightslog' => 'Log neuubah hak peuhah',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'beuët laman nyoe',
'action-edit' => 'andam laman nyoë',
'action-createpage' => 'peugöt laman',
'action-move' => 'Peupinah laman nyoë',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|neuubah}}',
'recentchanges' => 'Neuubah barô',
'recentchanges-legend' => 'Peuniléh neuubah barô',
'recentchanges-summary' => "Di yup nyoë nakeuh neuubah barô nyang na bak Wikipèdia nyoë.
Hareutoë: (bida) = neuubah, (riwayat) = riwayat teumuléh, '''B''' = laman barô, '''u''' = neuandam ubeut, '''b''' = neuandam bot, (± ''bit'') = jeumeulah asoë meutamah/meukureuëng, → = neuandam bideuëng, ← = mohtasa otomatis.
----",
'recentchanges-feed-description' => 'Seutöt neuubah barô lam wiki bak umpeuën nyoë.',
'recentchanges-label-newpage' => 'Neuandam nyoë jipeugöt laman barô',
'recentchanges-label-minor' => 'Nyoe neuandam ubeut',
'recentchanges-label-bot' => 'Neuandam nyoe geupubuet le bot',
'recentchanges-label-unpatrolled' => 'Neuandam nyoe goh lom geukalon',
'rcnote' => "Di yup nyoë nakeuh {{PLURAL:$1|nakeuh '''1''' neu’ubah barô |nakeuh '''$1''' neu’ubah barô}} lam {{PLURAL:$2|'''1''' uroë|'''$2''' uroë}} nyoë, trôk ‘an $5, $4.",
'rcnotefrom' => 'Di yup nyoë nakeuh neuubah yôh <strong>$2</strong> (geupeudeuh trôh ‘an <strong>$1</strong> neuubah).',
'rclistfrom' => 'Peudeuih neuubah barô yôh $1 kön',
'rcshowhideminor' => '$1 andam bacut',
'rcshowhidebots' => '$1 bot',
'rcshowhideliu' => '$1 ureuëng ngui tamöng',
'rcshowhideanons' => '$1 ureuëng ngui hana nan',
'rcshowhidepatr' => '$1 andam teurunda',
'rcshowhidemine' => '$1 atra lôn andam',
'rclinks' => 'Peudeuih $1 neuubah barô lam $2 uroë nyoë<br />$3',
'diff' => 'bida',
'hist' => 'riwayat',
'hide' => 'Peusom',
'show' => 'Peuleumah',
'minoreditletter' => 'b',
'newpageletter' => 'B',
'boteditletter' => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|ureueng kalön|ureueng kalön}}]',
'rc_categories_any' => 'Pue-pue mantöng',
'rc-change-size-new' => '$1 {{PLURAL:$1|bita|bita}} lheuh meuandam',
'newsectionsummary' => '/* $1 */ bideung barô',
'rc-enhanced-expand' => 'Peuleumah rincian',
'rc-enhanced-hide' => 'Peusom rincian',
'rc-old-title' => 'sigohlom nyan geupeugöt "$1"',

# Recent changes linked
'recentchangeslinked' => "Neuubah meukaw'èt",
'recentchangeslinked-feed' => 'Neuubah meuhubông',
'recentchangeslinked-toolbox' => "Neuubah teukaw'èt",
'recentchangeslinked-title' => "Neuubah nyang meukaw'èt ngön $1",
'recentchangeslinked-noresult' => 'Hana neu’ubah bak ôn-ôn meuhubông silawét masa nyang ka geupeuteuntèë.',
'recentchangeslinked-summary' => "Nyoë nakeuh dapeuta neuubah nyang geupeugèt ban-ban nyoë keu on-on nyang meuhubông nibak ôn ka kusuih (atawa keu anggèëta kawan kusuih).
Ôn-ôn bak [[Special:Watchlist|keunalon droeneuh]] geucitak '''teubay'''.",
'recentchangeslinked-page' => 'Nan laman:',
'recentchangeslinked-to' => 'Peuleumah neuubah nibak laman-laman nyang mupawôt ngön laman nyang geubri',

# Upload
'upload' => 'Peutamöng beureukaih',
'uploadbtn' => 'Peutamong beureukaih',
'reuploaddesc' => 'Riwang u laman peutamöng',
'uploadnologin' => 'Hana lom meutamöng',
'uploadlog' => 'ceunatat peutamöng',
'uploadlogpage' => 'Log peutamöng',
'uploadlogpagetext' => 'Nyoe nakeuh dapeuta peutamöng barô.
Eu [[Special:NewFiles|galeri beureukaih barô]] keu seuneudeuh barô.',
'filename' => 'Nan beureukaih',
'filedesc' => 'Ehtisa',
'fileuploadsummary' => 'Éhtisa:',
'filesource' => 'Nè',
'uploadedfiles' => 'Beureukaih nyang meupeutamöng',
'minlength1' => 'Nan beureukaih beuna saboh haraih.',
'illegalfilename' => 'Nan beureukaih "$1" meuasoe seunurat nyang han jeut na bak nan. Tulông gantoe nan nyan sigohlom neupeutamöng lom.',
'filename-toolong' => 'Nan beureukaih han jeut leubèh nibak 240 bita.',
'badfilename' => 'Nan beureukaih ka meugantoe keu "$1".',
'empty-file' => 'Beureukaih nyang neupeutamöng soh.',
'file-too-large' => 'Beureukaih nyang neupeutamöng rayek that.',
'filename-tooshort' => 'Nan beureukaih paneuk that.',
'filetype-banned' => 'Jeunèh beureukaih nyoe geutheun.',
'illegal-filename' => 'Nan beureukaih han jeut lagèe nyoe.',
'savefile' => 'Kubah beureukaih',
'uploadedimage' => 'peutamöng "[[$1]]"',
'overwroteimage' => 'peutamöng vèrsi barô "[[$1]]"',
'upload-source' => 'Asai beureukaih',
'sourcefilename' => 'Asai nan beureukaih:',
'sourceurl' => 'Asai URL:',
'upload-maxfilesize' => 'Paléng rayek beureukaih: $1',
'upload-description' => 'Teuneurang beureukaih',
'watchthisupload' => 'Kalön beureukaih nyoe',
'upload-success-subj' => 'Ka meupeutamöng',

# img_auth script messages
'img-auth-nofile' => 'Hana beureukaih "$1".',

'license' => 'Jeunèh lisensi:',
'license-header' => 'Jeunèh lisensi',

# Special:ListFiles
'imgfile' => 'beureukaih',
'listfiles' => 'Dapeuta beureukah',
'listfiles_thumb' => 'Beuntuk ubeut',
'listfiles_date' => 'Uroe',
'listfiles_name' => 'Nan',
'listfiles_user' => 'Ureueng ngui',
'listfiles_size' => 'Rayek',
'listfiles_description' => 'Teuneurang',
'listfiles_count' => 'Vèrsi',

# File description page
'file-anchor-link' => 'Beureukaih',
'filehist' => 'Riwayat beureukaih',
'filehist-help' => "Neuteugon bak uroë buleuën/watèë keu neu'eu beureukaih nyoë ‘oh watèë nyan.",
'filehist-deleteall' => 'sampôh ban dum',
'filehist-deleteone' => 'sampôh',
'filehist-revert' => 'peuriwang',
'filehist-current' => 'jinoë hat',
'filehist-datetime' => 'Uroë buleuën/Watèë',
'filehist-thumb' => 'Beuntuk ubeut',
'filehist-thumbtext' => 'Beuntuk ubeut keu seunalén tiëp $1',
'filehist-nothumb' => 'Hana beuntuk ubeut',
'filehist-user' => 'Ureuëng ngui',
'filehist-dimensions' => 'Dimènsi',
'filehist-filesize' => 'Rayek beureukah',
'filehist-comment' => "Seuneu'ôt",
'filehist-missing' => 'Beureukaih hana meutumèe',
'imagelinks' => 'Seuneungui beureukaih',
'linkstoimage' => '{{PLURAL:$1|laman}} di yup nyoë mupawôt u beureukaih nyoë:',
'nolinkstoimage' => 'Hana laman nyang na meupawôt u beureukaih nyoë.',
'sharedupload' => 'Beureukah nyoë dari $1 ngön kadang geunguy lé buët-buët la’én.',
'sharedupload-desc-here' => "Beureukaih nyoe nejih nibak $1 ngon kadang geunguy le proyek-proyek la'en.
Teuneurang bak [$2 on teuneurangjih] geupeuleumah di yup nyoe.",
'uploadnewversion-linktext' => 'Peulöt seunalén nyang leubèh barô nibak beureukah nyoë.',

# MIME search
'mimesearch' => 'Mita MIME',

# List redirects
'listredirects' => 'Dapeuta peuninah',

# Unused templates
'unusedtemplates' => 'Templat nyang hana geungui',

# Random page
'randompage' => 'Laman baranggari',

# Random redirect
'randomredirect' => 'Peuninah saban sakri',

# Statistics
'statistics' => 'Keunira',

'disambiguations' => 'Ôn disambiguasi',
'disambiguationspage' => 'Template:disambig',

'doubleredirects' => 'Peuninah ganda',

'brokenredirects' => 'Peuninah reulöh',

'withoutinterwiki' => 'Laman tan na hubông bahsa',

'fewestrevisions' => 'Teunuléh ngön neu’ubah paléng dit',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|bit|bit}}',
'nlinks' => '$1 {{PLURAL:$1|hubông|hubông}}',
'nmembers' => '$1 {{PLURAL:$1|asoë|asoë}}',
'lonelypages' => 'Laman tan hubông balék',
'uncategorizedpages' => 'Laman hana kawan',
'uncategorizedcategories' => 'Kawan hana kawan',
'uncategorizedimages' => 'Beureukaih hana kawan',
'uncategorizedtemplates' => 'Seunaleuëk hana kawan',
'unusedcategories' => 'Kawan hana geungui',
'unusedimages' => 'Beureukaih hana geungui',
'popularpages' => 'Laman jithèë',
'wantedcategories' => "Kawan geuh'eut",
'wantedpages' => 'Laman geuh‘eut',
'wantedpages-badtitle' => 'Nan hana sah lam kawan hasé: $1',
'wantedfiles' => "Beureukaih nyang geumeuh'eut",
'mostlinked' => 'Laman nyang paléng kayém geutuju',
'mostlinkedcategories' => 'Kawan nyang paléng kayém geutuju',
'mostlinkedtemplates' => 'Templat nyang paléng kayém geutuju',
'mostcategories' => 'Teunuléh ngön kawan paléng le',
'mostimages' => 'Beureukah nyang paléng kayém geunguy',
'mostrevisions' => 'Teunuléh ngön neu’ubah paléng le',
'prefixindex' => 'Ban dum laman ngön haraih awai',
'shortpages' => 'Laman paneuk',
'longpages' => 'Laman panyang',
'deadendpages' => 'Laman buntu',
'protectedpages' => 'Laman nyang geulindông',
'listusers' => 'Dapeuta ureuëng ngui',
'usercreated' => '{{GENDER:$3|Geupeugot}} bak $1 poh $2',
'newpages' => 'Laman barô',
'newpages-username' => 'Ureuëng ngui:',
'ancientpages' => 'Laman paléng awai',
'move' => 'Pupinah',
'movethispage' => 'Pupinah laman nyoë',
'pager-newer-n' => '{{PLURAL:$1|1 leubèh barô |$1 leubèh barô}}',
'pager-older-n' => '{{PLURAL:$1|1 leubèh awai|$1 leubèh awai}}',

# Book sources
'booksources' => 'Nè kitab',
'booksources-search-legend' => 'Mita bak nè kitab',
'booksources-go' => 'Mita',

# Special:Log
'specialloguserlabel' => 'Ureuëng ngui:',
'speciallogtitlelabel' => 'Sasaran (nan atawa ureuëng ngui):',
'log' => 'Log',
'all-logs-page' => 'Ban dum log umom',

# Special:AllPages
'allpages' => 'Ban dum laman',
'alphaindexline' => '$1 u $2',
'nextpage' => 'Laman lheuëh nyan ($1)',
'prevpage' => 'Laman sigohlomjih ($1)',
'allpagesfrom' => 'Peuleumah laman peuphôn nibak:',
'allpagesto' => 'Peuleumah laman geupeuakhé bak:',
'allarticles' => 'Dapeuta teunuléh',
'allpagesprev' => 'U likôt',
'allpagesnext' => 'U keue',
'allpagessubmit' => 'Mita',
'allpagesprefix' => 'Peuleumah laman ngön harah phôn:',
'allpages-hide-redirects' => 'Peusom peuninah',

# Special:Categories
'categories' => 'Dapeuta kawan',
'special-categories-sort-count' => 'atôe meunurôt jumeulah',
'special-categories-sort-abc' => 'atôe meunurôt seunurat',

# Special:DeletedContributions
'deletedcontributions' => 'Beuneuri nyang geusampôh',
'deletedcontributions-title' => 'Beuneuri nyang geusampôh',
'sp-deletedcontributions-contribs' => 'beuneuri',

# Special:LinkSearch
'linksearch' => 'Mita seuneumat luwa',
'linksearch-pat' => 'Pola mita:',
'linksearch-ns' => 'Ruweueng nan:',
'linksearch-ok' => 'Mita',
'linksearch-line' => '$1 meupawôt nibak $2',

# Special:ListUsers
'listusersfrom' => 'Peuleumah ureueng ngui nyang neuawai ngön:',
'listusers-submit' => 'Peuleumah',
'listusers-noresult' => 'Hana ureueng ngui nyang meutumèe.',
'listusers-blocked' => '(geutheun)',

# Special:ActiveUsers
'activeusers' => 'Dapeuta ureueng ngui udép',
'activeusers-intro' => 'Nyoe nakeuh dapeuta ureueng ngui nyang na geuandam $1 {{PLURAL:$1|uroe|uroe}} u likôt.',
'activeusers-count' => '$1 {{PLURAL:$1|buet|buet}} lam {{PLURAL:$3|uroe|$3 uroe}} u likôt',
'activeusers-from' => 'Peuleumah ureueng ngui nyang neuawai ngön:',
'activeusers-hidebots' => 'Peusom bot',
'activeusers-hidesysops' => 'Peusom ureueng urôh',
'activeusers-noresult' => 'Hana ureueng ngui nyang meutumèe.',

# Special:ListGroupRights
'listgrouprights' => 'Dapeuta khut ureueng ngui',
'listgrouprights-key' => 'Teuneurang:
* <span class="listgrouprights-granted">Khut nyang geubri</span>
* <span class="listgrouprights-revoked">Khut nyang hana geubri</span>',
'listgrouprights-group' => 'Kawan',
'listgrouprights-rights' => 'Khut',
'listgrouprights-helppage' => 'Beunantu:Khut kawan',
'listgrouprights-members' => '(dapeuta anggèëta)',
'listgrouprights-addgroup' => 'Tamah {{PLURAL:$2|kawan|kawan}}: $1',
'listgrouprights-removegroup' => 'Sampôh {{PLURAL:$2|kawan|kawan}}: $1',
'listgrouprights-addgroup-all' => 'Tamah ban dum kawan',
'listgrouprights-removegroup-all' => 'Sampôh ban dum kawan',

# Email user
'emailuser' => 'Surat-e ureuëng ngui',
'emailuser-title-target' => "Peu'ét surat-e keu {{GENDER:$1|ureuëng ngui}} nyoë",
'emailuser-title-notarget' => "Peu'ét surat-e",
'emailpage' => "Peu'ét surat-e keu ureuëng ngui",
'emailusername' => 'Ureueng ngui:',
'emailusernamesubmit' => 'Kirém',
'email-legend' => "Kirém surat-e keu ureueng ngui {{SITENAME}} la'én",
'emailfrom' => 'Ureueng kirém:',
'emailto' => 'Ureueng teurimöng:',
'emailsubject' => 'Bhah:',
'emailmessage' => 'Peusan:',
'emailsend' => 'Kirém',
'emailccme' => 'Kubah saboh seunalén surat-e lôn.',
'emailccsubject' => 'Salén peusan droeneuh keu $1: $2',
'emailsent' => 'Surat-e meukirém',
'emailsenttext' => 'Surat-e droeneuh ka meukirém.',

# Watchlist
'watchlist' => 'Dapeuta keunalön',
'mywatchlist' => 'Keunalön',
'watchlistfor2' => 'Keu $1 $2',
'addedwatchtext' => "Ôn \"[[:\$1]]\" ka geupeutamah u [[Special:Watchlist|dapeuta keunalön]] Droëneuh. Neu’ubah-neu’ubah bak masa u keuë bak ôn nyan ngön bak ôn peugah habajih, euntreuk leumah nyoë pat. Ôn nyan euntreuk geupeuleumah ''teubay'' bak [[Special:RecentChanges|dapeuta neu’ubah paléng barô]] mangat leubèh mudah leumah.",
'removedwatchtext' => 'Ôn "[[:$1]]" ka geusampôh nibak [[Special:Watchlist|dapeuta keunalön]] Droëneuh.',
'watch' => 'Kalön',
'watchthispage' => 'Kalön ôn nyoë',
'unwatch' => 'Bateuë kalön',
'watchlist-details' => '{{PLURAL:$1|$1 ôn|$1 ôn}} geukalön, hana kira ôn peugah haba.',
'wlshowlast' => 'Peudeuh $1 jeum $2 uroë $3 seuneulheuëh',
'watchlist-options' => 'Peuniléh dapeuta kalön',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Kalön...',
'unwatching' => 'Hana kalön...',

# Delete
'deletepage' => 'Sampôh laman',
'historywarning' => "'''Peuneugah:''' Laman nyang keumeung neusampôh na riwayat ngön kureuëng leubèh $1 {{PLURAL:$1|geunantoë}}:",
'confirmdeletetext' => 'Droëneuh neuk neusampôh laman atawa beureukaih nyoë keu sabé. Meunan cit ban mandum riwayatjih nibak basis data. Neupeupaseuti meunyo Droëneuh cit keubiët meung neusampôh, neutupeuë ban mandum akébatjih, ngön peuë nyang neupeulaku nyoë nakeuh meunurôt [[{{MediaWiki:Policy-url}}|kebijakan{{SITENAME}}]].',
'actioncomplete' => 'Seuleusoë',
'actionfailed' => 'Hana meuhasé',
'deletedtext' => '"$1" ka geusampôh. Eu $2 keu log paléng barô bak laman nyang ka geusampôh.',
'dellogpage' => 'Log seunampôh',
'deletecomment' => 'Choë:',
'deleteotherreason' => 'Nyang la’én/choë la’én:',
'deletereasonotherlist' => 'Choë la’én',

# Rollback
'rollbacklink' => 'pulang',

# Protect
'protectlogpage' => 'Log lindông',
'protectedarticle' => 'peulindông "[[$1]]"',
'modifiedarticleprotection' => 'Ubah tingkat lindông "[[$1]]"',
'prot_1movedto2' => 'peupinah [[$1]] u [[$2]]',
'protectcomment' => 'Choë:',
'protectexpiry' => 'Maté tanggay:',
'protect_expiry_invalid' => 'Watèë maté tanggay hana sah.',
'protect_expiry_old' => 'Watèë maté tanggay nakeuh bak masa u likôt.',
'protect-text' => "Droëneuh jeuët neu’eu atawa neugantoë tingkat lindông keu ôn '''$1''' nyoë pat.",
'protect-locked-access' => "Nan dapeuta Droëneuh hana hak keu jak gantoë tingkat lindông ôn. Nyoë pat nakeuh konfigurasi atra jinoë keu ôn '''$1''':",
'protect-cascadeon' => 'Ôn nyoë teungöh geulindông kareuna geupeuseureuta lam {{PLURAL:$1|ôn|ôn-ôn}} nyoë nyang ka geulindông ngön peuniléh lindông meuturôt geupeuudép.
Droëneuh jeuët neugantoë tingkat lindông keu ôn nyoë, tapi nyan hana peungarôh keu lindông meuturôt.',
'protect-default' => 'Peuidin ban dum ureuëng ngui',
'protect-fallback' => 'Peuidin ureuëng ngui ngön idin "$1" mantöng',
'protect-level-autoconfirmed' => 'Peuidin ureuëng ngui nyang teudapeuta keudroë mantöng',
'protect-level-sysop' => 'Peuidin ureuëng urôh mantöng',
'protect-summary-cascade' => 'riti',
'protect-expiring' => 'maté tanggay $1 (UTC)',
'protect-expiring-local' => 'maté tanggai $1',
'protect-expiry-indefinite' => 'sabé',
'protect-cascade' => 'Peulindông ban mandum ôn nyang rôh lam ôn nyoë (lindông meuturôt).',
'protect-cantedit' => 'Droëneuh h‘an jeuët neu’ubah tingkat lindông ôn nyoë kareuna Droëneuh hana hak keu neupeulaku nyan.',
'protect-expiry-options' => '1 jeum:1 hour,1 uroë:1 day,1 minggu:1 week,2 minggu:2 weeks,1 buleuën:1 month,3 buleuën:3 months,6 buleuën:6 months,1 thôn:1 year,sabé:infinite',
'restriction-type' => 'Lindông:',
'restriction-level' => 'Tingkat:',

# Undelete
'undeletebtn' => 'Peuriwang!',
'undeletelink' => 'eu/peuriwang',
'undeleteviewlink' => 'eu',
'undelete-search-submit' => 'Mita',

# Namespace form on various pages
'namespace' => 'Ruweuëng nan:',
'invert' => 'Peubalék peuniléh',
'namespace_association' => 'Ruweuëng nan meuhubông',
'blanknamespace' => '(Keuë)',

# Contributions
'contributions' => 'Beuneuri {{GENDER:$1|ureuëng ngui}}',
'contributions-title' => 'Beuneuri ureuëng ngui keu $1',
'mycontris' => 'Beuneuri',
'contribsub2' => 'Keu $1 ($2)',
'uctop' => '(jinoë)',
'month' => 'Mula phôn buleuën (ngön sigohlomjih)',
'year' => 'Mula phôn thôn (ngön sigohlomjih)',

'sp-contributions-newbies' => 'Peudeuh beuneuri atra ureuëng ban dapeuta mantöng',
'sp-contributions-newbies-sub' => 'Keu ureuëng nguy barô',
'sp-contributions-blocklog' => 'Log peutheun',
'sp-contributions-uploads' => 'peunasoe',
'sp-contributions-logs' => 'log',
'sp-contributions-talk' => 'marit',
'sp-contributions-search' => 'Mita soë nyang tuléh',
'sp-contributions-username' => 'Alamat IP atawa nan ureuëng ngui:',
'sp-contributions-toponly' => 'Peuleumah geunantoe nyang baro mantong',
'sp-contributions-submit' => 'Mita',

# What links here
'whatlinkshere' => 'Peunawôt balék',
'whatlinkshere-title' => 'Laman nyang mupawôt u $1',
'whatlinkshere-page' => 'Laman:',
'linkshere' => "Laman-laman nyoë meupawôt u '''[[:$1]]''':",
'nolinkshere' => "Hana halaman nyang teukaw'et u '''[[:$1]]'''.",
'isredirect' => 'laman peuninah',
'istemplate' => 'ngön seunaleuëk',
'isimage' => 'peunawôt beureukaih',
'whatlinkshere-prev' => '$1 {{PLURAL:$1|sigohlomjih|sigohlomjih}}',
'whatlinkshere-next' => '$1 {{PLURAL:$1|lheuëh nyan|lheuëh nyan}}',
'whatlinkshere-links' => '← peunawôt',
'whatlinkshere-hideredirs' => '$1 peuninah',
'whatlinkshere-hidetrans' => '$1 transklusi',
'whatlinkshere-hidelinks' => '$1 peunawôt',
'whatlinkshere-hideimages' => '$1 peunawôt beureukaih',
'whatlinkshere-filters' => 'Saréng',

# Block/unblock
'blockip' => 'Theun ureuëng ngui',
'ipboptions' => '2 jeum:2 hours,1 uroë:1 day,3 uroë:3 days,1 minggu:1 week,2 minggu:2 weeks,1 buleuën:1 month,3 buleuën:3 months,6 buleuën:6 months,1 thôn:1 year,sabé:infinite',
'ipblocklist' => 'Ureuëng ngui teutheun',
'ipblocklist-submit' => 'Mita',
'blocklink' => 'theun',
'unblocklink' => 'peugadöh theun',
'change-blocklink' => 'gantoë theun',
'contribslink' => 'beuneuri',
'blocklogpage' => 'Log peutheun',
'blocklogentry' => 'theun [[$1]] ngön watèë maté tanggay $2 $3',
'unblocklogentry' => 'peugadöh theun "$1"',
'block-log-flags-nocreate' => 'pumeugöt akun geupumaté',

# Move page
'movepagetext' => "Formulir di yup nyoë geunguy keu jak ubah nan saboh ôn ngön jak peupinah ban dum data riwayat u nan barô. Nan nyang trép euntreuk jeuët keu ôn peupinah u nan nyang barô. Hubông u nan trép hana meu’ubah. Neupeupaseuti keu neupréksa peuninah ôn nyang reulöh atawa meuganda lheuëh neupinah. Droëneuh nyang mat tanggông jaweuëb keu neupeupaseuti meunyo hubông laju teusambông u ôn nyang patôt.

Beu neuingat that meunyo ôn '''h’an''' jan geupeupinah meunyo ka na ôn nyang geunguy nan barô, keucuali meunyo ôn nyan soh atawa nakeuh ôn peuninah ngön hana riwayat andam. Nyoë areutijih Droëneuh jeuët neu’ubah nan ôn keulayi lagèë söt meunyo Droëneuh neupeugöt seunalah, ngön Droëneuh h‘an jeuët neutimpa ôn nyang ka na.
'''INGAT'''
Nyoë jeuët geupeuakébat neu’ubah nyang h’an neuduga ngön kreuëh ngön bacah keu ôn nyang meuceuhu. Neupeupaseuti Droëneuh meuphôm akébat nibak buët nyoë sigohlom neulanjut.",
'movepagetalktext' => "Ôn peugah haba nyang na hubôngan euntreuk teupinah keudroë '''keucuali meunyo:'''

*Saboh ôn peugah haba nyang hana soh ka na di yup nan barô, atawa
*Droëneuh hana neubôh tanda cunténg bak kutak di yup nyoë

Lam masalah nyoë, meunyo neuhawa, Droëneuh jeuët neupeupinah atawa neupeugabông ôn keudroë.",
'movearticle' => 'Peupinah laman:',
'newtitle' => 'U nan barô:',
'move-watch' => 'Kalön laman nyoë',
'movepagebtn' => 'Peupinah laman',
'pagemovedsub' => 'Peupinah meuhasé',
'movepage-moved' => '\'\'\'"$1" ka geupeupinah u "$2".\'\'\'',
'articleexists' => 'Ôn ngön nan nyan ka na atawa nan nyang neupiléh hana sah. Neupiléh nan la’én.',
'talkexists' => 'Ôn nyan ka geupeupinah, tapi ôn peugah haba bak ôn nyan h‘an jeuët geupeupinah kareuna ka na ôn peugah haba bak nan barô. Neupeusapat mantöng ôn ôn peugah haba nyan keudroë.',
'movedto' => 'geupeupinah u',
'movetalk' => 'Peupinah ôn peugah haba nyang na hubôngan.',
'movelogpage' => 'Log pinah',
'movereason' => 'Choë:',
'revertmove' => 'peuriwang',

# Export
'export' => 'Peuteubiët laman',

# Namespace 8 related
'allmessages' => 'Peusan sistem',
'allmessagesname' => 'Nan',
'allmessagesdefault' => 'Naseukah pukok',

# Thumbnails
'thumbnail-more' => 'Peurayek',
'thumbnail_error' => 'Salah bak peugöt gamba cut: $1',

# Import log
'importlogpage' => 'Log impor',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Laman ureuëng ngui droëneuh',
'tooltip-pt-mytalk' => 'Laman marit droëneuh',
'tooltip-pt-preferences' => 'Geunalak',
'tooltip-pt-watchlist' => 'Dapeuta laman nyang lônkalön',
'tooltip-pt-mycontris' => 'Dapeuta beuneuri Droëneuh',
'tooltip-pt-login' => 'Droëneuh geupadan keu tamong log, bah pih nyan hana geupeuwajéb.',
'tooltip-pt-logout' => 'Teubiët',
'tooltip-ca-talk' => 'Marit laman asoë',
'tooltip-ca-edit' => 'Droëneuh jeuët neuandam laman nyoë. Neungui tumbôi eu dilèë sigoh neukeubah.',
'tooltip-ca-addsection' => 'Puphôn beunagi barô',
'tooltip-ca-viewsource' => 'Laman nyoë geulindông.
Droëneuh jeuët neu’eu nèjih mantöng.',
'tooltip-ca-history' => 'Geunantoë awai nibak laman nyoë',
'tooltip-ca-protect' => 'Peulindông laman nyoë',
'tooltip-ca-delete' => 'Sampôh laman nyoë',
'tooltip-ca-move' => 'Pupinah laman nyoë',
'tooltip-ca-watch' => 'Tamah laman nyoë u dapeuta kalön droëneuh',
'tooltip-ca-unwatch' => 'Sampôh laman nyoë nibak dapeuta kalön droëneuh',
'tooltip-search' => 'Mita {{SITENAME}}',
'tooltip-search-go' => 'Mita saboh laman ngon nan nyang peureuséh lagèë nyoë meunyo na',
'tooltip-search-fulltext' => 'Mita laman nyang na asoë lagèë nyoë',
'tooltip-p-logo' => 'Saweuë ôn keuë',
'tooltip-n-mainpage' => 'Saweuë ôn keuë',
'tooltip-n-mainpage-description' => 'Saweuë ôn keuë',
'tooltip-n-portal' => 'Bhaih buët, peuë nyang jeuët neupubuët, pat keu mita sipeuë hai',
'tooltip-n-currentevents' => 'Mita haba barô',
'tooltip-n-recentchanges' => 'Dapeuta neuubah barô lam wiki.',
'tooltip-n-randompage' => 'Peudeuih laman baranggari',
'tooltip-n-help' => 'Bak mita bantu.',
'tooltip-t-whatlinkshere' => 'Dapeuta ban dum laman wiki nyang mupawôt keunoë',
'tooltip-t-recentchangeslinked' => 'Neuubah barô lam laman nyang meupawôt nibak laman nyoë',
'tooltip-feed-rss' => 'Umpeuën RSS keu laman nyoë',
'tooltip-feed-atom' => 'Umpeuën Atom keu laman nyoë',
'tooltip-t-contributions' => 'Dapeuta beuneuri ureuëng ngui nyoë',
'tooltip-t-emailuser' => "Peu'ét surat-e keu ureuëng ngui nyoë",
'tooltip-t-upload' => 'Peutamong beureukaih',
'tooltip-t-specialpages' => 'Dapeuta ban dum laman kusuih',
'tooltip-t-print' => 'Seunalén rakam laman nyoë',
'tooltip-t-permalink' => 'Peunawôt teutap keu geunantoë laman nyoë',
'tooltip-ca-nstab-main' => 'Eu laman asoë',
'tooltip-ca-nstab-user' => 'Eu laman ureuëng ngui',
'tooltip-ca-nstab-special' => 'Nyoë nakeuh laman kusuih nyang h’an jeuët geuandam.',
'tooltip-ca-nstab-project' => 'Eu laman buët',
'tooltip-ca-nstab-image' => 'Eu laman beureukaih',
'tooltip-ca-nstab-template' => 'Eu seunaleuëk',
'tooltip-ca-nstab-help' => 'Eu laman beunantu',
'tooltip-ca-nstab-category' => 'Eu laman kawan',
'tooltip-minoredit' => 'Bôh tanda keu nyoë sibagoë andam bacut',
'tooltip-save' => 'Keubah neuubah Droëneuh',
'tooltip-preview' => 'Peuleumah neuubah Droëneuh, neungui nyoë sigohlom neukeubah!',
'tooltip-diff' => 'Peuleumah neuubah nyang ka Droëneuh peugöt',
'tooltip-compareselectedversions' => 'Ngiëng bida nibak duwa geunantoë laman nyang teupiléh',
'tooltip-watch' => 'Tamah laman nyoë u dapeuta kalön droëneuh',
'tooltip-rollback' => 'Peuriwang neu’andam-neu’andam bak laman nyoë u nyang tuléh keuneulheuëh lam sigo teugön',
'tooltip-undo' => 'Peuriwang geunantoë nyoë ngön peuhah plôk neu’andam ngön cara eu dilèë. Choë jeuët geupeutamah bak plôk ehtisa.',
'tooltip-summary' => 'Pasoë éhtisa paneuk',

# Info page
'pageinfo-toolboxlink' => 'Teuneurang laman',

# Browsing diffs
'previousdiff' => '← Bida awai',
'nextdiff' => 'Geunantoë lheuëh nyan →',

# Media information
'file-info-size' => '$1 × $2 piksel, rayek beureukaih: $3, MIME jeunèh: $4',
'file-nohires' => 'Hana resolusi nyang leubèh manyang.',
'svg-long-desc' => 'Beureukah SVG, nominal $1 x $2 piksel, rayek beureukah: $3',
'show-big-image' => 'Resolusi peunoh',

# Special:NewFiles
'newimages' => 'Beureukah barô',
'ilsubmit' => 'Mita',

# Bad image list
'bad_image_list' => 'Beuntukjih lagèë di miyub nyoë:

Cit buté dapeuta (baréh nyang geupeuphôn ngon tanda *) nyang geukira. Hubông phôn bak saboh baréh beukeuh hubông u beureukaih nyang brôk.
Hubông-hubông lheuëh nyan bak baréh nyang saban geukira sibagoë keucuali, nakeuh teunuléh nyang jeuët peuleumah beureukaih nyan.',

# Metadata
'metadata' => 'Metadata',
'metadata-help' => 'Beureukah nyoë na beurita tambahan nyang mungkén geutamah lé kamèra digital atawa peuminday nyang geunguy keu peugöt atawa peudigitalisasi beureukah. Meunyo beureukah nyoë ka geu’ubah, tapeusili nyang na mungkén hana seucara peunoh meurefleksikan beurita nibak gamba nyang ka geu’ubah nyoë.',
'metadata-expand' => 'Peuleumah tapeusili teunamah',
'metadata-collapse' => 'Peusom tapeusili teunamah',
'metadata-fields' => "Bideuëng mètadata gamba nyang na lam peusan nyoë keuneuk geupasoë bak tampilan halaman gamba 'oh watèë tabel mètadata geutôp.
Data nyang la'én eunteuk teupeusom keudroë.
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
* gpsaltitude",

# External editor support
'edit-externally' => 'Andam beureukah nyoë ngön aplikasi luwa',
'edit-externally-help' => '(Ngiëng [//meta.wikimedia.org/wiki/Help:External_editors peurintah atô] keu haba leubèh lanjôt)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ban dum',
'namespacesall' => 'ban dum',
'monthsall' => 'ban dum',

# Delete conflict
'recreate' => 'Peugöt ulang',

# action=purge
'confirm_purge_button' => 'Ka göt',
'confirm-purge-top' => 'Sampôh peuniyôh laman nyoë?',

# action=watch/unwatch
'confirm-watch-button' => 'Ka göt',
'confirm-watch-top' => 'Tamah laman nyoë u dapeuta keunalön droëneuh?',
'confirm-unwatch-button' => 'Ka göt',
'confirm-unwatch-top' => 'Sampôh laman nyoë nibak dapeuta keunalön droëneuh?',

# Multipage image navigation
'imgmultipageprev' => '← laman sigohlomjih',

# Auto-summaries
'autosumm-new' => "Geupeugöt laman ngön asoë '$1'",

# Live preview
'livepreview-loading' => 'Pumasoë...',
'livepreview-ready' => 'Pumasoë... Ka lheuëh!',
'livepreview-failed' => 'Peudeuih hasé langsông hana meuhasé!
Neuci peudeuih hasé biasa.',
'livepreview-error' => 'H\'an jitém teusambat: $1 "$2"
Neuci peudeuih hasé biasa.',

# Watchlist editing tools
'watchlisttools-view' => "Peudeuh neuubah meukaw'èt",
'watchlisttools-edit' => 'Peudeuh ngön andam dapeuta keunalön',
'watchlisttools-raw' => 'Andam dapeuta keunalön meuntah',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'Ingat:\'\'\' Gunci meuurot pukok "$2" jipeuhiro gunci meuurot pukok "$1" sigohlomjih.',

# Special:Version
'version' => 'Curak',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Mita',

# Special:SpecialPages
'specialpages' => 'Laman kusuih',
'specialpages-group-maintenance' => 'Beuneuri thèë plara',
'specialpages-group-other' => "La'én-la'én",
'specialpages-group-login' => 'Tamöng / dapeuta',
'specialpages-group-changes' => 'Neuubah barô ngön log',
'specialpages-group-media' => 'Beuneuri thèë ngön pumasoë beureukaih',
'specialpages-group-users' => 'Ureuëng ngui ngön khut ureuëng ngui',
'specialpages-group-highuse' => 'Laman kayém geusaweuë',
'specialpages-group-pages' => 'Dapeuta laman',
'specialpages-group-pagetools' => 'Alat laman',
'specialpages-group-wiki' => 'Data ngön alat',
'specialpages-group-redirects' => 'Mita ngön pupinah',
'specialpages-group-spam' => 'Alat spam',

# Special:BlankPage
'blankpage' => 'Laman soh',
'intentionallyblankpage' => 'Laman nyoë geusaja peusoh',

# External image whitelist
'external_image_whitelist' => '#Neupubiyeue bareh nyoe lagee na<pre>
#Neunguy bagian-bagian ekspresi regular (bak bagian antara // mantong) di yup nyoe
#Bagian-bagian nyoe eunteuk geupupaih ngon URL nibak gamba-gamba luwa (nyang geupeuhubong lansong)
#Fragmen nyang paih eunteuk geupeuleumah sibagoe gamba, seuhjih keu link mantong
#Bareh nyang geupuphon ngon # eunteuk geupeujeuet keu bareh beunalah
#Nyoe hana geupubida haraih rayek ngon ubeut
#Neupeuduek ban dum beunagi ekspresi biasa di yup bareh nyoe. Neupubiyeue bareh nyoe lagee na</pre>',

# Special:Tags
'tags' => 'Tag neuubah nyang sah',
'tag-filter' => 'Saréng [[Special:Tags|tag]]:',
'tag-filter-submit' => 'Saréng',

# Search suggestions
'searchsuggest-search' => 'Mita',

# Durations
'duration-seconds' => '{{PLURAL:$1|deutik}}',
'duration-minutes' => '{{PLURAL:$1|minèt}}',
'duration-hours' => '{{PLURAL:$1|jeum}}',
'duration-days' => '{{PLURAL:$1|uroë}}',
'duration-weeks' => "{{PLURAL:$1|jeumeu'at}}",
'duration-years' => '{{PLURAL:$1|thôn}}',
'duration-decades' => '{{PLURAL:$1|dekade}}',
'duration-centuries' => '{{PLURAL:$1|abad}}',
'duration-millennia' => '{{PLURAL:$1|milenium}}',

);
