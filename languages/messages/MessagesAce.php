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
'tog-underline' => 'Bôh garéh yup bak hubông:',
'tog-justify' => 'Peurata paragraf',
'tog-hideminor' => 'Peusom neuandam bacut bak neuubah paléng barô',
'tog-hidepatrolled' => 'Peusom neuandam teupatroli bak neuubah paléng barô',
'tog-newpageshidepatrolled' => 'Peusom ôn teupatroli nibak dapeuta ôn barô',
'tog-extendwatchlist' => 'Peuhah dapeuta keunalön keu peuleumah ban dum neuubah, kon nyang paléng barô mantöng',
'tog-usenewrc' => 'Peusaho neuandam bak neuleumah neuubah barô ngon dapeuta keunalon meunurot ôn',
'tog-numberheadings' => 'Bôh numbô nan keudroë',
'tog-showtoolbar' => 'Peuleumah bateuëng alat andam',
'tog-editondblclick' => 'Andam ôn ngon duwa go teugon',
'tog-editsection' => 'Peujeuet andam bideueng rot hubong [andam]',
'tog-editsectiononrightclick' => 'Peujeuet andam bideueng ngon teugon blah uneun bak nan bideueng',
'tog-showtoc' => 'Peuleumah dapeuta asoe (keu on-on nyang na leubeh nibak 3 boh aneuk ulee)',
'tog-rememberpassword' => 'Ingat lôn tamong bak peuramban nyoë (keu paleng trep $1 {{PLURAL:$1|uroë|uroë}})',
'tog-watchcreations' => 'Tamah halaman nyang lonpeugot u dapeuta keunalon',
'tog-watchdefault' => 'Tamah halaman nyang lon-andam u dapeuta keunalon',
'tog-watchmoves' => 'Tamah halaman nyang lonpupinah u dapeuta keunalon',
'tog-watchdeletion' => 'Tamah halaman nyang lonsampoh u dapeuta keunalon',
'tog-minordefault' => 'Boh tanda mandum neuandam sibagoe neuandam bacut ngon baku',
'tog-previewontop' => 'Peuleumah hase yoh goh kutak andam',
'tog-previewonfirst' => 'Peuleumah hase bak neuandam phon',
'tog-nocache' => 'Pumate pumeugot beun on peuramban nyoe',
'tog-enotifwatchlistpages' => "Peu'ek surat-e keu lon meunyo saboh halaman nyang lonkalon meuubah",
'tog-enotifusertalkpages' => "Peu'ek keu lon surat-e meunyo on marit lon meuubah",
'tog-enotifminoredits' => "Peu'ek cit surat-e keu lon bak neuubah ubit",
'tog-enotifrevealaddr' => 'Peuleumah alamat surat-e lon bak neubrithee surat-e',
'tog-shownumberswatching' => 'Peuleumah jumeulah ureueng kalon',
'tog-oldsig' => 'Tanda jaroe jinoe:',
'tog-fancysig' => 'Peujeuet tanda jaroe sibagoe naseukah wiki (hana hubong keudroe)',
'tog-uselivepreview' => 'Nguy peuleumah hase langsong (baci)',
'tog-forceeditsummary' => 'Peuingat lon meunyo plok neuringkaih neuandam mantong soh',
'tog-watchlisthideown' => 'Peusöm nyang lôn andam nibak dapeuta keunalön',
'tog-watchlisthidebots' => 'Peusöm nyang teu andam nibak sagoö nyang bak dapeuta keunalön',
'tog-watchlisthideminor' => 'Peusöm Andam Bacut bak dapeuta keunalön',
'tog-watchlisthideliu' => 'Peusöm andam ureuëng nguy nyang tamöng nibak dapeuta keunalön',
'tog-watchlisthideanons' => 'Peusöm andam ureuëng nguy hana taturi nibak dapeuta keunalön',
'tog-watchlisthidepatrolled' => 'Peusom neuandam teukaway bak dapeuta keunalon',
'tog-ccmeonemails' => "Peu'ek keu lon seunalen surat-e nyang lonpeu'ek keu ureueng la'en",
'tog-diffonly' => 'Bek peuleumah asoe halaman di yup beunida neuandam',
'tog-showhiddencats' => 'Peuleumah kawan teusom',
'tog-norollbackdiff' => "Bek peudeuh beunida 'oh lheueh geupeuriwang",
'tog-useeditwarning' => 'Neupeuingat lôn meunyoë meukubah ôn andam ngon hana teukeubah',
'tog-prefershttps' => 'Sabè neunguy seunambông teulindông meunyoë neutamöng log',

'underline-always' => 'Sabe',
'underline-never' => "H'an tom",
'underline-default' => 'Kulet atawa ngon peuhah web teupasang',

# Font style option in Special:Preferences
'editfont-style' => 'Gaya seunurat komputer bak plok andam',
'editfont-default' => 'Bawaan penjelajah web',
'editfont-monospace' => 'Seunurat Monospace',
'editfont-sansserif' => 'Seunurat Sans-serif',
'editfont-serif' => 'Seunurat Serif',

# Dates
'sunday' => 'Aleuhat',
'monday' => 'Seulanyan',
'tuesday' => 'Seulasa',
'wednesday' => 'Rabu',
'thursday' => 'Hameh',
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
'may_long' => 'Buleuën Limong',
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
'may-gen' => 'Buleuën Limong',
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
'may' => 'Limong',
'jun' => 'Nam',
'jul' => 'Tujôh',
'aug' => 'Lapan',
'sep' => 'Sikureuëng',
'oct' => 'Siplôh',
'nov' => 'Siblaih',
'dec' => 'Duwa Blaih',
'january-date' => '$1 Buleuën Sa',
'february-date' => '$1 Buleuën Duwa',
'march-date' => '$1 Buleuën Lhèë',
'april-date' => '$1 Buleuën Peuët',
'may-date' => '$1 Buleuën Limong',
'june-date' => '$1 Buleuën Nam',
'july-date' => '$1 Buleuën Tujôh',
'august-date' => '$1 Buleuën Lapan',
'september-date' => '$1 Buleuën Sikureuëng',
'october-date' => '$1 Buleuën Siplôh',
'november-date' => '$1 Buleuën Siblaih',
'december-date' => '$1 Buleuën Duwa Blaih',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Kawan|Kawan}}',
'category_header' => 'Teunuléh lam kawan "$1"',
'subcategories' => 'Aneuk kawan',
'category-media-header' => 'Peukakaih lam kawan "$1"',
'category-empty' => "''Kawan nyoë jinoë hat hana halaman atawa media.''",
'hidden-categories' => '{{PLURAL:$1|Kawan teusom|Kawan teusom}}',
'hidden-category-category' => 'Kawan teusom',
'category-subcat-count' => '{{PLURAL:$2|Kawan nyoë  cit na saboh yupkawan nyoë.|Kawan nyoë na {{PLURAL:$1|yupkawan|$1 yupkawan}} nyoë, dari ban dum $2.}}',
'category-subcat-count-limited' => 'Kawan nyoe na {{PLURAL:$1|aneuk kawan|$1 aneuk kawan}} lagee di yup.',
'category-article-count' => '{{PLURAL:$2|Kawan nyoë cit na saboh ôn nyoë.|Kawan nyoë na  {{PLURAL:$1|ôn|$1 ôn }}, dari ban dum $2.}}',
'category-article-count-limited' => 'Kawan nyoe na {{PLURAL:$1|saboh halaman|$1 halaman}} lagee di yup.',
'category-file-count' => '{{PLURAL:$2|Kawan nyoe cit na beureukaih nyoe sagay.|{{PLURAL:$1|beureukaih|$1 beureukaih}} nyoe na lam kawan nyoe, nibak ban dum $2.}}',
'category-file-count-limited' => 'Kawan nyoe na {{PLURAL:$1|beureukaih|$1 beureukaih}} lagee di yup.',
'listingcontinuesabbrev' => 'samb.',
'index-category' => 'On nyang geuindex',
'noindex-category' => 'On nyang hana geuindex',
'broken-file-category' => 'On ngon gamba reuloh',

'about' => 'Bhaih',
'article' => 'Teunuléh',
'newwindow' => '(peuhah bak tingkap barô)',
'cancel' => 'Peubateuë',
'moredotdotdot' => 'Lom...',
'morenotlisted' => 'Dapeuta nyoe hana leungkap',
'mypage' => 'Ôn',
'mytalk' => 'Marit',
'anontalk' => 'Peugah haba IP nyoë.',
'navigation' => 'Navigasi',
'and' => '&#32;ngon',

# Cologne Blue skin
'qbfind' => 'Mita',
'qbbrowse' => 'Lop',
'qbedit' => 'Andam',
'qbpageoptions' => 'Ôn nyoe',
'qbmyoptions' => 'Ôn lôn',
'qbspecialpages' => 'Ôn kusuih',
'faq' => 'FAQ',
'faqpage' => 'Project:FAQ',

# Vector skin
'vector-action-addsection' => 'Tamah bhaih',
'vector-action-delete' => 'Sampôh',
'vector-action-move' => 'Peupinah',
'vector-action-protect' => 'Peulindông',
'vector-action-undelete' => 'Bateuë sampôh',
'vector-action-unprotect' => 'Gantoe neulindông',
'vector-simplesearch-preference' => 'Peuudep beunteueng mita biasa (kulet Vector khong)',
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
'history' => 'Riwayat ôn',
'history_short' => 'Riwayat',
'updatedmarker' => 'geuubah yoh seunaweue keuneulheueh lon phon kon',
'printableversion' => 'Seunalén rakam',
'permalink' => 'Peunawôt teutap',
'print' => 'Rakam',
'view' => 'Beuet',
'edit' => 'Andam',
'create' => 'Peugöt',
'editthispage' => 'Andam ôn nyoë',
'create-this-page' => 'Peugèt ôn nyoe',
'delete' => 'Sampôh',
'deletethispage' => 'Sampôh ôn nyoe',
'undeletethispage' => 'Bèk neusampôh ôn nyoë',
'undelete_short' => 'Bateuë sampôh {{PLURAL:$1|one edit|$1 edits}}',
'viewdeleted_short' => 'Eu {{PLURAL:$1|saboh neuandam|$1 neuandam}} nyang geusampoh',
'protect' => 'Peulindông',
'protect_change' => 'ubah',
'protectthispage' => 'Peulindong on nyoe',
'unprotect' => 'Gantoe neulindong',
'unprotectthispage' => 'Gantoe neulindông ôn nyoë',
'newpage' => 'Ôn barô',
'talkpage' => 'Peugah haba bhah ôn nyoë',
'talkpagelinktext' => 'Marit',
'specialpage' => 'Ôn kusuih',
'personaltools' => 'Peukakaih droë',
'postcomment' => 'Beunagi baro',
'articlepage' => 'Eu ôn asoë',
'talk' => 'Marit',
'views' => 'Ôn',
'toolbox' => 'Alat',
'userpage' => 'Eu on ureueng nguy',
'projectpage' => 'Eu ôn buët',
'imagepage' => 'Eu on beureukaih',
'mediawikipage' => 'Eu on peusan sistem',
'templatepage' => 'Eu on seunaleuek',
'viewhelppage' => 'Eu on beunantu',
'categorypage' => 'Eu ôn kawan',
'viewtalkpage' => 'Eu on marit',
'otherlanguages' => 'Bahsa la’én',
'redirectedfrom' => '(Geupeupinah nibak $1)',
'redirectpagesub' => 'Ôn peuninah',
'lastmodifiedat' => 'Ôn nyoë seuneulheuëh geuubah bak $2, $1.',
'viewcount' => 'On nyoe ka geusaweue {{PLURAL:$1|sigo|$sigo}}.<br />',
'protectedpage' => 'Ôn teupeulindông',
'jumpto' => 'Grôp u:',
'jumptonavigation' => 'navigasi',
'jumptosearch' => 'mita',
'view-pool-error' => "Meu'ah, server teungoh sibuk jinoe
Le that ureueng nyang meuh'eut jak eu on nyoe
Neupreh si'at yoh goh neubaci lom

$1",
'pool-timeout' => 'Liwat watee preh gunci',
'pool-queuefull' => 'Seunapat neupreh peunoh',
'pool-errorunknown' => 'Salah hana meukon',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'Bhaih {{SITENAME}}',
'aboutpage' => 'Project:Bhaih',
'copyright' => "Asoë na meunurot $1 keucuali meunyo na hay la'en nyang geupeugah",
'copyrightpage' => '{{ns:project}}:Hak karang',
'currentevents' => 'Haba barô',
'currentevents-url' => 'Project:Haba barô',
'disclaimers' => 'Beunantah',
'disclaimerpage' => 'Project:Beunantah umom',
'edithelp' => 'Bantu andam',
'helppage' => 'Help:Asoë',
'mainpage' => 'Ôn Keuë',
'mainpage-description' => 'Ôn Keuë',
'policy-url' => 'Project:Neuatô',
'portal' => 'Meusapat',
'portal-url' => 'Project:Meusapat',
'privacy' => 'Jaga rahsia',
'privacypage' => 'Project:Jaga rahsia',

'badaccess' => 'Salah khut/hak tamöng',
'badaccess-group0' => 'Droeneuh hana geupeuidin keu neupeulaku buet nyang neulakee',
'badaccess-groups' => 'Buet nyang neulakee geupeubatah keu ureueng nguy lam {{PLURAL:$2|kawan|salah saboh nibak kawan}}: $1.',

'versionrequired' => 'Peureulee MediaWiki versi $1',
'versionrequiredtext' => "MediaWiki versi $1 geupeureulee keu neunguy on nyoe. Neu'eu [[Special:Version|on versi]]",

'ok' => 'Ka göt',
'retrievedfrom' => 'Geurumpok nibak "$1"',
'youhavenewmessages' => 'Droëneuh na $1 ($2).',
'newmessageslink' => 'peusan barô',
'newmessagesdifflink' => 'neuubah seuneulheuëh',
'youhavenewmessagesfromusers' => "Droeneuh na $1 nibak {{PLURAL:$3|ureueng nguy la'en|$3 ureueng nguy}} ($2).",
'youhavenewmessagesmanyusers' => "Droeneuh na $1 nibak ureueng nguy la'en ($2)",
'newmessageslinkplural' => '{{PLURAL:$1|saboh peusan baro|peusan baro}}',
'newmessagesdifflinkplural' => '{{PLURAL:$1|neuubah}} keuneulheueh',
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
'feedlinks' => 'Umpeuen:',
'feed-invalid' => 'Jeuneh neulakee umpeuen hana paih',
'feed-unavailable' => 'Umpeuen sindikasi hana',
'site-rss-feed' => 'Umpeuën RSS $1',
'site-atom-feed' => 'Umpeuën Atôm $1',
'page-rss-feed' => 'Umpeuën RSS "$1"',
'page-atom-feed' => 'Umpeuën Atom "$1"',
'red-link-title' => '$1 (ôn goh na)',
'sort-descending' => 'Peuurot tren',
'sort-ascending' => 'Peuurot ek',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Ôn',
'nstab-user' => 'Ureuëng nguy',
'nstab-media' => 'Ôn media',
'nstab-special' => 'Kusuih',
'nstab-project' => 'Buët ôn',
'nstab-image' => 'Beureukaih',
'nstab-mediawiki' => 'Peusan',
'nstab-template' => 'Seunaleuëk',
'nstab-help' => 'Beunantu',
'nstab-category' => 'Kawan',

# Main script and global functions
'nosuchaction' => 'Hana buet nyan',
'nosuchactiontext' => 'Buet nyang geulakee le URL nyan hana sah. Droeneuh kadang salah neukeutik URL, atawa neuseutot saboh neuhubong nyang hana beutoy. Hay nyoe kadang jeuet keu lageuem saboh bug bak alat leumiek nyang geunguy le {{SITENAME}}.',
'nosuchspecialpage' => 'Hana on kusuih lagee nyan',
'nospecialpagetext' => "<strong>Droeneuh ka neulakee on kusuih nyang hana sah.</strong>
Dapeuta on kusuih nyang sah jeuet neu'eu bak [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error' => 'Seunalah',
'databaseerror' => 'Kesalahan basis data',
'databaseerror-text' => 'Saboh salah bak nè data ka teujadi. Nyoë meuhat na nyang han paih bak peukakaih droëneuh',
'databaseerror-textcl' => 'Teunanyong basis data teungoh kacho',
'databaseerror-query' => 'Teunanyong: $1',
'databaseerror-function' => 'Guna: $1',
'databaseerror-error' => 'Salah: $1',
'laggedslavemode' => 'Peuneugah: On nyoe kadang hana neuubah baro',
'readonly' => 'Basis data geurok',
'enterlockreason' => 'Pasoe daleh neurok ngon pajan jeuet geupeuhah',
'readonlytext' => "Basis data hat nyoe geurok keu teunamong baro ngon geunantoe la'en, kadang keu peulara basis data rutin, lheueh nyan baro lagee biasa teuma.

Ureueng uroh nyang rok nyoe geupeutaba jeuneulaih nyoe: $1",
'missing-article' => 'Basis data h’an jeuët jiteumèë naseukah nibak ôn nyang sipatôtjih na, nakeuh "$1" $2.

Nyoë biasajih sabab hubông useuëng u geunantoë away nyang ka teusampôh.

Meunyo kön nyoë sababjih, Droëneuh kadang ka neuteumèë saboh bug lam software. Neutulông peugah bhah nyoë bak salah sidroë [[Special:ListUsers/sysop|Nyang urôh]], ngön neupeugah alamat URL nyang neusaweuë.',
'missingarticle-rev' => '(revisi#: $1)',
'missingarticle-diff' => '(Bida: $1, $2)',
'readonly_lag' => 'Basis data ka geurok otomatis silawet basis data sekunder teungoh geupeusinkron ngon basis data utama',
'internalerror' => 'Salah bak dalam',
'internalerror_info' => 'Salah bak dalam: $1',
'fileappenderrorread' => 'H\'an jitem beuet "$1" \'oh geutamah',
'fileappenderror' => 'H\'an jeuet jipasoe "$1" u "$2"',
'filecopyerror' => 'H\'an jeuet salen beureukaih "$1" u "$2".',
'filerenameerror' => 'H\'an jeuet boh nan beureukaih "$1" u "$2".',
'filedeleteerror' => 'H\'an jeuet sampoh beureukaih "$1".',
'directorycreateerror' => 'H\'an jeuet peugot direktori "$1".',
'filenotfound' => 'Beureukaih "$1" hana meurumpok.',
'fileexistserror' => 'H\'an jeuet geusalen u beureukaih "$1": Beureukaih ka na.',
'unexpected' => 'Yum hana geuharap: "$1"="$2".',
'formerror' => "Reuloh: H'an jeuet peu'et formulir.",
'badarticleerror' => "Buet nyoe h'an jeuet geupeulaku bak on nyoe.",
'cannotdelete' => 'On atawa beureukaih "$1" h\'an jeuet geusampoh.
Kadang ka na soe sampoh.',
'cannotdelete-title' => 'H\'an jeuet sampoh on "$1"',
'delete-hook-aborted' => "Seunampoh geupeubateue le kaw'et parser.
Hana jeuneulaih.",
'no-null-revision' => 'H\'an jeuet peugot revisi null baro keu halaman "$1"',
'badtitle' => 'Nan hana sah',
'badtitletext' => 'Nan ôn nyang neulakèë hana sah, soh, atawa nan antarabahsa atawa antarawiki nyang salah sambông.',
'perfcached' => 'Data di yup nyoe geupeusom ngon kadang kon data baro. {{PLURAL:$1|saboh hase|$1 hase}} maksimum na lam beujana.',
'perfcachedts' => 'Data di yup nyoe geupeusom, ngon geupeubaro seuneulheueh bak $1. {{PLURAL:$4|saboh hase|$4 hase}} paleng le na lam beujana.',
'querypage-no-updates' => "Beunaro keu on nyoe hat nyoe teungoh h'an jeuet.
Data sinoe h'an geupasoe ulang.",
'wrong_wfQuery_params' => 'Parameter salah u wfQuery()<br />
Meunafaat: $1<br />
Neulakee: $2',
'viewsource' => 'Eu nè',
'viewsource-title' => 'Eu ne keu $1',
'actionthrottled' => 'Buet geupeubataih',
'actionthrottledtext' => 'Sibagoe saboh seunipat lawan-spam, droeneuh geupeubataih nibak neupeulaku buet nyoe le that go lam watee paneuk, ngon droeneuh ka leubeh nibak bataih.
Neuci lom lam padum minet.',
'protectedpagetext' => 'Laman nyoe ka geupeulindong mangat bek jeuet geuandam',
'viewsourcetext' => 'Droëneuh  jeuët neu’eu',
'viewyourtext' => 'Droëneuh meuidzin kalon ngon neucok nè andam droëneuh u ôn nyoë',
'protectedinterface' => 'Halaman nyoe na teks muka keu muka keu peukakaih leumiek ngon geupeulindong mangat bek jeuet jipeureuloh.
Keu neuk tamah atawa ubah teujeumah keu ban dum wiki, neungui [//translatewiki.net/ translatewiki.net], proyek lokalisasi MediaWiki.',
'ns-specialprotected' => 'Ôn khusuih bèk neuandam',
'exception-nologin' => 'Hana tamong lom',

# Login and logout pages
'welcomeuser' => 'Seulamat trôk teuka, $1 !',
'welcomecreation-msg' => 'Nan droëneuh ka geupeugöt. 
Bèk tuwo neuatô [[Special:Preferences|geunalak {{SITENAME}}]] droëneuh.',
'yourname' => 'Ureuëng nguy:',
'userlogin-yourname' => 'Ureuëng nguy',
'userlogin-yourname-ph' => 'Peutamöng nan ureuëng nguy droëneuh',
'createacct-another-username-ph' => 'Pasoë nan ureuëng nguy droëneuh',
'yourpassword' => 'Lageuëm:',
'userlogin-yourpassword' => 'Lageuëm rahsia',
'userlogin-yourpassword-ph' => 'Pasoë lageuëm rahsia droëneuh',
'createacct-yourpassword-ph' => 'Pasoë lageuëm rahsia',
'yourpasswordagain' => 'Pasoë lom lageuëm:',
'createacct-yourpasswordagain' => 'Peunyo lageuëm rahsia',
'createacct-yourpasswordagain-ph' => 'Pasoë lom lageuëm rahsia',
'remembermypassword' => 'Ingat lôn tamong bak peuramban nyoë (keu paleng trep $1 {{PLURAL:$1|uroë|uroë}})',
'userlogin-remembermypassword' => 'Peubiyeuë lôn tamöng',
'userlogin-signwithsecure' => 'Nguy server aman',
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
'notloggedin' => 'Hana tamong lom',
'userlogin-noaccount' => 'Goh lom neudapeuta?',
'userlogin-joinproject' => 'Neugabông ngön {{SITENAME}}',
'nologin' => "Goh na nan ureuëng nguy? '''$1'''.",
'nologinlink' => 'Peudapeuta nan barô',
'createaccount' => 'Peudapeuta nan barô',
'gotaccount' => "Ka lheuëh neudapeuta? '''$1'''.",
'gotaccountlink' => 'Tamong',
'userlogin-resetlink' => 'Tuwo ngon rincian tamong Droeneuh?',
'userlogin-resetpassword-link' => 'Peugöt lageuëm rahsia la’én',
'helplogin-url' => 'Help:Tamong',
'userlogin-helplink' => '[[{{MediaWiki:helplogin-url}}|Bantu tamöng]]',
'userlogin-loggedin' => 'Droëneuh ka neutamöng seubagoë $1. Neunguy blangko di yup keu neutamöng seubagoë ureuëng nguy la’én',
'userlogin-createanother' => 'Peudapeuta nan barô',
'createacct-join' => 'Neupasoë keutrangan bhaih droëneuh di yup nyoë',
'createacct-another-join' => 'Neupasoë keutrangan nan ureuëng nguy barô di yup nyoë',
'createacct-emailrequired' => 'Alamat surat-e',
'createacct-emailoptional' => 'Alamat surat-e (hana wajéb)',
'createacct-email-ph' => 'Neupasoë alamat surat-e droëneuh',
'createacct-another-email-ph' => 'Pasoë alamat surat-e',
'createaccountmail' => 'Neunguy lageuëm rahsia beurangkapeuë keu si’at nyoë. Lheuëh nyan neupeu’ét u surat-e nyang droëneuh meuh’eut',
'createacct-realname' => 'Nan aseuli (hana wajéb)',
'createaccountreason' => 'Choë:',
'createacct-reason' => 'Choë:',
'createacct-reason-ph' => 'Pakön droëneuh neupeugöt nan ureuëng nguy la’én',
'createacct-captcha' => 'Paréksa aman',
'createacct-imgcaptcha-ph' => "Pasoë seunurat nyang neu'eu di ateuëh",
'createacct-submit' => 'Peudapeuta nan barô',
'createacct-another-submit' => 'Peugöt nan ureuëng nguy la’én',
'createacct-benefit-heading' => '{{SITENAME}} geupeugöt lé ureuëng lagèë droëneuh.',
'createacct-benefit-body1' => '{{PLURAL:$1|andam}}',
'createacct-benefit-body2' => '{{PLURAL:$1|$1 halaman}}',
'createacct-benefit-body3' => '{{PLURAL:$1|ureuëng tuléh}} seuneulheuëh',
'badretype' => 'Lageuëm rahsia nyang neupasoë salah.',
'userexists' => "Nan ureuëng nguy nyang neupasoë ka na soë nguy.
Neupiléh nan nyang la'én.",
'loginerror' => 'Salah bak tamong',
'createacct-error' => 'Peudapeuta nan barô hana meuhasé',
'createaccounterror' => 'H‘an jeuët peudapeuta nan: $1',
'loginsuccesstitle' => 'Meuhasé tamong',
'loginsuccess' => "'''Droëneuh  jinoë ka neutamong di {{SITENAME}} sibagoë \"\$1\".'''",
'nosuchuser' => 'Hana ureuëng nguy ngön nan "$1".
Nan ureuëng nguy jipeubida harah rayek.
Tulông neuparéksa keulayi neuija Droëneuh, atawa [[Special:UserLogin/signup|neudapeuta barô]].',
'nosuchusershort' => 'Hana ureuëng nguy ngön nan "$1".
Préksa keulayi neu’ija Droëneuh.',
'nouserspecified' => 'Neupasoë nan Droëneuh.',
'wrongpassword' => 'Lageuëm nyang neupasoë salah. Neuci lom.',
'wrongpasswordempty' => 'Droëneuh hana neupasoë lageuëm. Neuci lom.',
'passwordtooshort' => "Lageuëm paléng h'an haroh na {{PLURAL:$1|1 karakter|$1 karakter}}.",
'mailmypassword' => "Peu'ét lageuëm barô",
'passwordremindertitle' => 'Lageuëm seumeuntara barô keu {{SITENAME}}',
'passwordremindertext' => 'Salah sidroë (kadang Droëneuh, ngön alamat IP $1) geulakèë lageuëm barô keu {{SITENAME}} ($4). Lageuëm si\'at keu ureuëng nguy "$2" ka geupeuna ngon ka geuato jeuet keu "$3". Meunyo nyoe nakeuh meukeusud droeneuh, droeneuh peureulee neutamong ngon neupileh lageuem baro jinoe. Lageuem siat droeneuh meung abeh lam {{PLURAL:$5|siuroe|$5 uroe}}.

Meunyo ureueng la\'en nyang peugot neulakee nyoe, atawa meunyo droeneuh ka neuingat lageuem droeneuh, ngon droeneuh h\'an ek neugantoe le, droeneuh jeuet hana neupeureumeuen peusan nyoe ngon neulanjut neunguy lageuem awayneuh.',
'noemail' => 'Hana alamat surat-e nyang teucatat keu ureuëng nguy "$1".',
'passwordsent' => 'Lageuëm barô ka geupeu\'ét u surat-e nyang geupeudapeuta keu "$1". Neutamong teuma lheuëh neuteurimong surat-e nyan.',
'eauthentsent' => 'Saboh surat èlèktronik keu peunyoë ka geukirém u alamat surat èlèktronik Droëneuh. Droëneuh beuneuseutöt préntah lam surat nyan keu neupeunyoë meunyo alamat nyan nakeuh beutôy atra Droëneuh. {{SITENAME}} h‘an geupeuudép surat Droëneuh meunyo langkah nyoë hana neupeulaku lom.',
'loginlanguagelabel' => 'Bahsa: $1',

# Change password dialog
'retypenew' => 'Pasoë teuma lageuëm barô:',

# Edit page toolbar
'bold_sample' => 'Rakam teubay',
'bold_tip' => 'Haraih teubay',
'italic_sample' => 'Rakam singèt naseukah nyoë',
'italic_tip' => 'Rakam singèt',
'link_sample' => 'Nan hubông',
'link_tip' => 'Hubông dalam',
'extlink_sample' => 'http://www.example.com nan hubông',
'extlink_tip' => 'Hubông luwa (bèk tuwoë bôh http:// bak away)',
'headline_sample' => 'Naseukah nan',
'headline_tip' => 'Aneuk beunagi tingkat 1',
'nowiki_sample' => 'Bèk format naseukah nyoë',
'nowiki_tip' => 'Bèk seutot beuntuk wiki',
'image_tip' => 'Pasoë beureukaih',
'media_tip' => 'Hubông beureukaih',
'sig_tip' => 'Tanda jaroë Droëneuh ngön tanda watèë',
'hr_tip' => 'Garéh data',

# Edit pages
'summary' => 'Ehtisa:',
'subject' => 'Bhah/nan:',
'minoredit' => 'Nyoë lôn andam bacut',
'watchthis' => 'Kalön ôn nyoë',
'savearticle' => 'Keubah ôn',
'preview' => 'Eu dilèë',
'showpreview' => 'Peuleumah hasé',
'showdiff' => 'Peuleumah neuubah',
'anoneditwarning' => 'Droëneuh   hana teudapeuta tamong. Alamat IP Droëneuh   teucatat lam tarèh (riwayat away) ôn nyoë.',
'summary-preview' => 'Eu dilèë neuringkaih:',
'blockedtext' => "'''Nan ureuëng nguy atawa alamat IP Droëneuh  ka geutheun.'''

Geutheun lé $1. Dalèh jih nakeuh ''$2''.

* Geutheun yôh: $8
* Neutheun maté tanggay bak: $6
* Nyang geutheun: $7

Droëneuh   jeuët neutanyong bak $1 atawa [[{{MediaWiki:Grouppage-sysop}}|nyang urôh nyang la’én]] keu peugah haba bhah nyoë.

Droëneuh   h’an jeuët neunguy alat 'Kirém surat-e ureuëng nguy nyoë' keucuali ka neupasoë alamat surat-e nyang sah di [[Special:Preferences|Geunalak]] Droëneuh ngön Droëneuh ka geutheun keu nguy nyan.

Alamat IP Droëneuh nakeuh $3, ngön ID neutheun nakeuh $5. Tulông peuseureuta salah saboh atawa ban duwa beurita nyoë bak tiëp teunanyöng nyang neupeugöt.",
'newarticle' => '(Barô)',
'newarticletext' => "Droëneuh   ka neuseutot u ôn nyang goh na. Keu peugöt ôn nyan, neukeutik asoë ôn di  kutak di yup nyoë (ngiëng [[{{MediaWiki:Helppage}}|ôn bantu]] keu beurita leubèh lanjut). Meunyo Droëneuh  hana neusaja ka trôk keunoë, teugon '''back''' nyang na bak layeuë.",
'noarticletext' => 'Hana naseukah jinoë lam ôn nyoë.
Ji Droëneuh jeuët [[Special:Search/{{PAGENAME}}|neumita keu nan ôn nyoë]] bak ôn-ôn la’én, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} log nyang na hubôngan], atawa [{{fullurl:{{FULLPAGENAME}}|action=edit}} neu\'andam ôn nyoë]</span>.',
'noarticletext-nopermission' => 'Hana asoë bak ôn nyoë jinoë.
Droëneuh jeuët [[Special:Search/{{PAGENAME}}|neumita keu nan ôn nyoë]] bak ôn-ôn la\'én,
atawa <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} neumita log nyang na meuhubông]</span>, tapi Droëneuh hana idin keu neupeugöt ôn nyoë',
'updated' => '(Seubarô)',
'note' => "'''Ceunatat:'''",
'previewnote' => "'''Beu neuingat meunyo laman nyoë goh lom neukeubah!'''",
'editing' => 'Andam $1',
'editingsection' => 'Andam $1 (bideuëng)',
'copyrightwarning' => "Beu neuingat bahwa ban mandum nyang Droëneuh   tuléh keu {{SITENAME}} geukira geupeuteubiët di yup $2 (ngiëng $1 keu leubèh jeulah). Meunyoë Droëneuh h‘an neutém teunuléh Droëneuh  ji’andam ngön jiba ho ho la’én, bèk neupasoë teunuléh Droëneuh  keunoë.<br />Droëneuh  neumeujanji chit meunyoë teunuléh nyoë nakeuh atra neutuléh keudroë, atawa neucok nibak nè nè atra umôm atawa nè bibeuëh la’én.
'''BÈK NEUPASOË TEUNULÉH NYANG GEUPEULINDÔNG HAK KARANG NYANG HANA IDIN'''",
'templatesused' => '{{PLURAL:$1|Templat|Templat}} nyang geunguy bak ôn nyoë:',
'templatesusedpreview' => '{{PLURAL:$1|Templat|Templat}} nyang geunguy bak eu dilèë nyoë:',
'template-protected' => '(geulindông)',
'template-semiprotected' => '(siteungoh-lindông)',
'hiddencategories' => 'Ôn nyoë nakeuh anggèëta nibak {{PLURAL:$1|1 kawan teusom |$1 kawan teusom}}:',
'nocreatetext' => '{{SITENAME}} ka jikot bak peugöt ôn barô. Ji Droëneuh   jeuët neuriwang teuma ngön neu’andam ôn nyang ka na, atawa [[Special:UserLogin|neutamong atawa neudapeuta]].',
'permissionserrorstext-withaction' => 'Droëneuh hana hak tamöng keu $2, muroë {{PLURAL:$1|choë|choë}} nyoë:',
'recreate-moveddeleted-warn' => "'''Ingat: Droëneuh neupeugöt ulang saboh ôn nyang ka tom geusampôh. ''',

Neutimang-timang dilèë peuë ék patôt neupeulanjut atra nyang teungoh neu’andam.
Nyoë pat nakeuh log seunampôh nibak ôn nyoë:",
'moveddeleted-notice' => 'Ôn nyoë ka geusampôh.
Log seunampôh ngon log peuninah ôn nyoë geupeuseudiya di yup nyoe keu keuneubah.',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''Ingat:''' Seunipat seunaleuëk nyang neunguy rayek that.
Ladôm seunaleuëk hana geupeurôh",
'post-expand-template-inclusion-category' => 'Ôn ngön seunipat seunaleuëk nyang leubèh bataih',
'post-expand-template-argument-warning' => "'''Ingat:''' On nyoe na paleng h'an saboh alasan seunaleuek nyang na sunipat ekspansi nyang raya that.
Alasan-alasan nyan hana geupeureumeuen.",
'post-expand-template-argument-category' => 'On ngon alasan seunaleuek nyang hana geupeureumeuen',

# History pages
'viewpagelogs' => 'Eu log ôn nyoë',
'currentrev' => 'Geunantoë jinoë',
'currentrev-asof' => 'Geunantoë barô bak $1',
'revisionasof' => 'Geunantoë tiëp $1',
'revision-info' => 'Geunantoë tiëp $1; $2',
'previousrevision' => '←Geunantoë sigohlomjih',
'nextrevision' => 'Geunantoë lheuëh nyan→',
'currentrevisionlink' => 'Geunantoë jinoë',
'cur' => 'jin',
'last' => 'akhé',
'page_first' => 'phôn',
'page_last' => 'keuneulheuëh',
'histlegend' => "Piléh duwa teuneugön radiô, lheuëh nyan teugön teuneugön ''peubandéng'' keu peubandéng seunalén. Teugön saboh tanggay keu eu seunalén ôn bak tanggay nyan.<br />(skr) = bida ngön seunalén jinoë, (akhé) = bida ngön seunalén sigohlomjih. '''u''' = andam ubeut, '''b''' = andam bot, → = andam bideuëng, ← = ehtisa keudroë",
'history-fieldset-title' => 'Eu riwayat away',
'history-show-deleted' => 'Nyang geusampoh mantong',
'histfirst' => 'paléng trép',
'histlast' => 'paléng barô',

# Revision feed
'history-feed-item-nocomment' => '$1 bak $2',

# Revision deletion
'rev-delundel' => 'peuleumah/peusom',
'revdel-restore' => 'Ubah neuleumah',
'revdel-restore-deleted' => 'geunantoe nyang ka geusampôh',
'revdel-restore-visible' => 'geunantoe nyang leumah',

# Merge log
'revertmerge' => 'Hana jadèh peugabông',

# Diffs
'history-title' => 'Riwayat geunantoë nibak "$1"',
'lineno' => 'Baréh $1:',
'compareselectedversions' => 'Peubandéng curak teupiléh',
'editundo' => 'peubateuë',
'diff-multi' => '({{PLURAL:$1|Saboh|$1}} geunantoë antara nyang geupeugot le {{PLURAL:$2|sidroe|$2}} ureueng nguy hana geupeuleumah)',

# Search results
'searchresults' => 'Hasé mita',
'searchresults-title' => 'Hasé mita keu "$1"',
'searchresulttext' => 'Keu beurita leubèh le bhah meunita bak {{SITENAME}}, eu [[{{MediaWiki:Helppage}}|ôn beunantu]].',
'searchsubtitle' => 'Droëneuh neumita \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|ban dum ôn nyang geupuphôn ngön "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|bandum ôn nyang teuhubông u "$1"]])',
'searchsubtitleinvalid' => "Droëneuh neumita '''$1'''",
'notitlematches' => 'Hana nan ôn nyang pah',
'notextmatches' => 'Hana naseukah ôn nyang pah',
'prevn' => '{{PLURAL:$1|$1}} sigohlomjih',
'nextn' => '{{PLURAL:$1|$1}} lheuëh nyan',
'prevn-title' => '$1 {{PLURAL:$1|hasé|hasé}} sigohlomjih',
'nextn-title' => '$1 {{PLURAL:$1|hasé}} lheuëh nyan',
'shown-title' => 'Peuleumah $1 {{PLURAL:$1|hasé}} tiëp ôn',
'viewprevnext' => 'Eu ($1 {{int:pipe-separator}} $2)($3)',
'searchmenu-exists' => "'''Na on ngon nan \"[[:\$1]]\" bak wiki nyoe.'''",
'searchmenu-new' => "'''Peugöt ôn \"[[:\$1]]\" bak wiki nyoë!'''",
'searchprofile-articles' => 'Ôn asoë',
'searchprofile-project' => 'Ôn Beunantu ngön Buët',
'searchprofile-images' => 'Multimedia',
'searchprofile-everything' => 'Ban dum',
'searchprofile-advanced' => 'Tingkat lanjut',
'searchprofile-articles-tooltip' => 'Mita bak $1',
'searchprofile-project-tooltip' => 'Mita bak $1',
'searchprofile-images-tooltip' => 'Mita beureukaih',
'searchprofile-everything-tooltip' => 'Mita ban dum ôn asoë (rôh ôn marit)',
'searchprofile-advanced-tooltip' => 'Mita bak ruweuëng nan meupat-pat',
'search-result-size' => '$1 ({{PLURAL:$2|1 narit|$2 narit}})',
'search-result-category-size' => '{{PLURAL:$1|1 anggeeta|$1 anggeeta}} ({{PLURAL:$2|1 aneuk kawan|$2 aneuk kawan}}, {{PLURAL:$3|1 beureukaih|$3 beureukaih}})',
'search-redirect' => '(peuninah $1)',
'search-section' => '(beunagi $1)',
'search-suggest' => 'Kadang meukeusud Droëneuh nakeuh: $1',
'search-interwiki-caption' => 'Buët la’én',
'search-interwiki-default' => 'Hasé $1:',
'search-interwiki-more' => '(lom)',
'searchrelated' => 'meusambat',
'searchall' => 'ban dum',
'showingresultsheader' => "{{PLURAL:$5|Hase '''$1''' nibak '''$3'''|Hase '''$1 - $2''' nibak '''$3'''}} keu '''$4'''",
'nonefound' => "'''Ceunatat''': Cit ladôm ruweuëng nyang seucara baku geupeutamöng lam meunita. Ci neupuphôn leunakèë Droëneuh ngön ''all:'' keu mita ban dum asoë (rôh cit ôn peugah haba, tèmplat, ngön nyang la’én (nnl)), atawa neunguy ruweuëng nan nyang neumeuh’eut sibagoë neu’away.",
'search-nonefound' => 'Hana hase nyang paih lagee atra neulakee',
'powersearch' => 'Mita lanjut',
'powersearch-legend' => 'Mita lanjut',
'powersearch-ns' => 'Mita bak ruweuëng nan:',
'powersearch-redir' => 'Dapeuta peuninah',
'powersearch-field' => 'Mita',

# Preferences page
'preferences' => 'Galak',
'mypreferences' => 'Atô',
'prefs-rc' => 'Ban meuubah',
'prefs-email' => 'Peunileh surat-e',
'searchresultshead' => 'Mita',
'youremail' => 'Surat-e:',
'yourrealname' => 'Nan aseuli:',
'prefs-help-realname' => '* Nan aseuli hana meucéh neupasoë.
Meunyo neupasoë, euntreuk nan Droëneuh nyan geupeuleumah mangat jitupeuë soë nyang tuléh.',
'prefs-help-email' => 'Alamat surat-e hana meuceh na, tapi geupeureulee keu seumeugot ulang lageuem, meunyo droeneuh tuwoe lageuem.',
'prefs-help-email-others' => "Droeneuh jeuet cit neupileh neupubiyeue ureueng la'en geupeu'et surat keu droeneuh rot surat-e rot seunambat bak on ureueng nguy atawa on marit.
Surat-e droeneuh h'an geupeugah keu ureueng nyan.",

# Groups
'group-sysop' => 'Ureuëng urôh',

'grouppage-sysop' => '{{ns:project}}:Ureuëng urôh',

# Special:Log/newusers
'newuserlogpage' => 'ureuëng nguy barô',

# User rights log
'rightslog' => 'Log neuubah hak peuhah',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'andam laman nyoë',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|neuubah}}',
'recentchanges' => 'Neuubah barô',
'recentchanges-legend' => 'Peuniléh neuubah barô',
'recentchanges-summary' => "Di yup nyoë nakeuh neuubah barô nyang na bak Wikipèdia nyoë.
Ceunatat: (bida) = neuubah, (riwayat) = riwayat teumuléh, '''B''' = ôn barô, '''u''' = neuandam ubeut, '''b''' = neuandam bot, (± ''bit'') = jumeulah asoë meutamah/meukureuëng, → = neuandam beunagi, ← = mohtasa otomatis.
----",
'recentchanges-feed-description' => 'Seutot neuubah barô lam wiki bak umpeuën nyoë.',
'recentchanges-label-newpage' => 'Neuandam nyoe jipeugot on baro',
'recentchanges-label-minor' => 'Nyoe neuandam ubeut',
'recentchanges-label-bot' => 'Neuandam nyoe geupubuet le bot',
'recentchanges-label-unpatrolled' => 'Neuandam nyoe goh lom geukalon',
'rcnote' => "Di yup nyoë nakeuh {{PLURAL:$1|nakeuh '''1''' neu’ubah barô |nakeuh '''$1''' neu’ubah barô}} lam {{PLURAL:$2|'''1''' uroë|'''$2''' uroë}} nyoë, trôk ‘an $5, $4.",
'rcnotefrom' => 'Di yup nyoë nakeuh neuubah yôh <strong>$2</strong> (geupeudeuh trôh ‘an <strong>$1</strong> neuubah).',
'rclistfrom' => 'Peuleumah neuubah paléng barô yôh $1 kön',
'rcshowhideminor' => '$1 andam bacut',
'rcshowhidebots' => '$1 bot',
'rcshowhideliu' => '$1 ureuëng nguy tamong',
'rcshowhideanons' => '$1 ureuëng nguy hana nan',
'rcshowhidepatr' => '$1 andam teurunda',
'rcshowhidemine' => '$1 atra lôn andam',
'rclinks' => 'Peuleumah $1 neuubah paléng barô lam $2 uroë nyoë<br />$3',
'diff' => 'bida',
'hist' => 'riwayat',
'hide' => 'Peusom',
'show' => 'Peuleumah',
'minoreditletter' => 'b',
'newpageletter' => 'B',
'boteditletter' => 'b',
'rc-enhanced-expand' => 'Peuleumah rincian',
'rc-enhanced-hide' => 'Peusom rincian',

# Recent changes linked
'recentchangeslinked' => 'Neuubah meuhubông',
'recentchangeslinked-feed' => 'Neuubah meuhubông',
'recentchangeslinked-toolbox' => "Neuubah teukaw'èt",
'recentchangeslinked-title' => 'Neuubah nyang meuhubông ngön $1',
'recentchangeslinked-summary' => "Nyoë nakeuh dapeuta neuubah nyang geupeugèt ban-ban nyoë keu on-on nyang meuhubông nibak ôn ka kusuih (atawa keu anggèëta kawan kusuih).
Ôn-ôn bak [[Special:Watchlist|keunalon droeneuh]] geucitak '''teubay'''.",
'recentchangeslinked-page' => 'Nan ôn:',
'recentchangeslinked-to' => 'Peuleumah neu’ubah nibak ôn-ôn nyang meusambông ngön ôn nyang geubri',

# Upload
'upload' => 'Peutamong beureukaih',
'uploadbtn' => 'Peutamong beureukah',
'uploadlogpage' => 'Log peutamöng',
'filedesc' => 'Ehtisa',
'uploadedimage' => 'peutamöng "[[$1]]"',

'license' => 'Jeuneh lisensi:',
'license-header' => 'Jeuneh lisensi',

# Special:ListFiles
'listfiles' => 'Dapeuta beureukah',

# File description page
'file-anchor-link' => 'Beureukaih',
'filehist' => 'Riwayat beureukaih',
'filehist-help' => "Neuteugon bak uroë buleuën/watèë keu neu'eu beureukaih nyoë ‘oh watèë nyan.",
'filehist-revert' => 'peuriwang',
'filehist-current' => 'jinoë hat',
'filehist-datetime' => 'Uroë buleuën/Watèë',
'filehist-thumb' => 'Beuntuk ubeut',
'filehist-thumbtext' => 'Beuntuk ubeut keu seunalén tiëp $1',
'filehist-user' => 'Ureuëng nguy',
'filehist-dimensions' => 'Dimènsi',
'filehist-filesize' => 'Rayek beureukah',
'filehist-comment' => "Seuneu'ôt",
'imagelinks' => 'Neunguy beureukaih',
'linkstoimage' => 'Ôn di yup nyoë na {{PLURAL:$1|hubông|$1 hubông}} u beureukah nyoë:',
'nolinkstoimage' => 'Hana ôn nyang na hubông u beureukah nyoë.',
'sharedupload' => 'Beureukah nyoë dari $1 ngön kadang geunguy lé buët-buët la’én.',
'sharedupload-desc-here' => "Beureukaih nyoe nejih nibak $1 ngon kadang geunguy le proyek-proyek la'en.
Teuneurang bak [$2 on teuneurangjih] geupeuleumah di yup nyoe.",
'uploadnewversion-linktext' => 'Peulöt seunalén nyang leubèh barô nibak beureukah nyoë.',

# MIME search
'mimesearch' => 'Mita MIME',

# List redirects
'listredirects' => 'Dapeuta peuninah',

# Unused templates
'unusedtemplates' => 'Templat nyang hana geunguy',

# Random page
'randompage' => 'Ôn beurangkari',

# Random redirect
'randomredirect' => 'Peuninah saban sakri',

# Statistics
'statistics' => 'Keunira',

'doubleredirects' => 'Peuninah ganda',

'brokenredirects' => 'Peuninah reulöh',

'withoutinterwiki' => 'Ôn tan na hubông bahsa',

'fewestrevisions' => 'Teunuléh ngön neu’ubah paléng dit',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|bit|bit}}',
'nlinks' => '$1 {{PLURAL:$1|hubông|hubông}}',
'nmembers' => '$1 {{PLURAL:$1|asoë|asoë}}',
'lonelypages' => 'Ôn tan hubông balék',
'uncategorizedpages' => 'Ôn nyang hana rôh lam kawan',
'uncategorizedcategories' => 'Kawan nyang hana rôh lam kawan',
'uncategorizedimages' => 'Beureukah nyang hana rôh lam kawan',
'uncategorizedtemplates' => 'Templat nyang hana rôh lam kawan',
'unusedcategories' => 'Kawan nyang hana geunguy',
'unusedimages' => 'Beureukah nyang hana geunguy',
'wantedcategories' => 'Kawan nyang geuhawa',
'wantedpages' => 'Ôn nyang geuh‘eut',
'mostlinked' => 'Ôn nyang paléng kayém geusaweuë',
'mostlinkedcategories' => 'Kawan nyang paléng kayém geunguy',
'mostlinkedtemplates' => 'Templat nyang paléng kayém geunguy',
'mostcategories' => 'Teunuléh ngön kawan paléng le',
'mostimages' => 'Beureukah nyang paléng kayém geunguy',
'mostrevisions' => 'Teunuléh ngön neu’ubah paléng le',
'prefixindex' => 'Ban dum ôn ngön haraih away',
'shortpages' => 'Ôn paneuk',
'longpages' => 'Ôn panyang',
'deadendpages' => 'Ôn buntu',
'protectedpages' => 'Ôn nyang geulindông',
'listusers' => 'Dapeuta ureuëng nguy',
'usercreated' => '{{GENDER:$3|Geupeugot}} bak $1 poh $2',
'newpages' => 'Ôn barô',
'ancientpages' => 'Teunuléh away',
'move' => 'Peupinah',
'movethispage' => 'Peupinah ôn nyoë',
'pager-newer-n' => '{{PLURAL:$1|1 leubèh barô |$1 leubèh barô}}',
'pager-older-n' => '{{PLURAL:$1|1 leubèh trép|$1 leubèh trép}}',

# Book sources
'booksources' => 'Nè kitab',
'booksources-search-legend' => 'Mita bak nè kitab',
'booksources-go' => 'Mita',

# Special:Log
'specialloguserlabel' => 'Ureuëng nguy:',
'speciallogtitlelabel' => 'Nan:',
'log' => 'Log',
'all-logs-page' => 'Ban dum log umom',

# Special:AllPages
'allpages' => 'Dapeuta ôn',
'alphaindexline' => '$1 u $2',
'nextpage' => 'Ôn lheuëh nyan ($1)',
'prevpage' => 'Ôn sigohlomjih ($1)',
'allpagesfrom' => 'Peuleumah ôn peuphôn nibak:',
'allpagesto' => 'Peuleumah ôn geupeuakhé bak:',
'allarticles' => 'Dapeuta teunuléh',
'allpagessubmit' => 'Mita',
'allpagesprefix' => 'Peuleumah ôn ngön harah phôn:',

# Special:Categories
'categories' => 'Dapeuta kawan',

# Special:LinkSearch
'linksearch' => 'Mita seuneumat luwa',
'linksearch-ok' => 'Mita',
'linksearch-line' => '$1 meusambat nibak $2',

# Special:ListGroupRights
'listgrouprights-members' => '(dapeuta anggèëta)',

# Email user
'emailuser' => 'Surat-e ureuëng nguy',

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
'deletepage' => 'Sampôh ôn',
'historywarning' => 'Ingat: Ôn nyang hawa neusampôh na riwayat:',
'confirmdeletetext' => 'Droëneuh neuk neusampôh ôn atawa beureukah nyoë keu sabé. Meunan cit ban mandum riwayatjih nibak basis data. Neupeupaseuti meunyo Droëneuh cit keubiët meung neusampôh, neutupeuë ban mandum akébatjih, ngön peuë nyang neupeulaku nyoë nakeuh meunurôt [[{{MediaWiki:Policy-url}}|kebijakan{{SITENAME}}]].',
'actioncomplete' => 'Seuleusoë',
'actionfailed' => 'Hana meuhase',
'deletedtext' => '"$1" ka geusampôh. Eu $2 keu log paléng barô bak ôn nyang ka geusampôh.',
'dellogpage' => 'Log seunampoh',
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
'protect-default' => 'Peuidin ban dum ureuëng nguy',
'protect-fallback' => 'Peureulèë hak peuhah "$1"',
'protect-level-autoconfirmed' => 'Theun ureuëng nguy barô ngön hana teudapeuta',
'protect-level-sysop' => 'Ureuëng urôh mantöng',
'protect-summary-cascade' => 'riti',
'protect-expiring' => 'maté tanggay $1 (UTC)',
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
'contributions' => 'Beuneuri {{GENDER:$1|ureuëng nguy}}',
'contributions-title' => 'Beuneuri ureuëng nguy keu $1',
'mycontris' => 'Beuneuri',
'contribsub2' => 'Keu {{GENDER:$3|$1}} ($2)',
'uctop' => '(jinoë)',
'month' => 'Yôh buleuën (ngön yôh goh lom nyan)',
'year' => 'Yôh thôn (ngön yôh goh lom nyan)',

'sp-contributions-newbies' => 'Peudeuh beuneuri atra ureuëng ban dapeuta mantöng',
'sp-contributions-newbies-sub' => 'Keu ureuëng nguy barô',
'sp-contributions-blocklog' => 'Log peutheun',
'sp-contributions-uploads' => 'peunasoe',
'sp-contributions-logs' => 'log',
'sp-contributions-talk' => 'marit',
'sp-contributions-search' => 'Mita soë nyang tuléh',
'sp-contributions-username' => 'Alamat IP atawa nan ureuëng nguy:',
'sp-contributions-toponly' => 'Peuleumah geunantoe nyang baro mantong',
'sp-contributions-submit' => 'Mita',

# What links here
'whatlinkshere' => 'Peunawôt balék',
'whatlinkshere-title' => 'Ôn nyang na neuhubông u $1',
'whatlinkshere-page' => 'Ôn:',
'linkshere' => "Ôn-ôn nyoë meuhubông u '''[[:$1]]''':",
'nolinkshere' => "Hana halaman nyang teukaw'et u '''[[:$1]]'''.",
'isredirect' => 'ôn peupinah',
'istemplate' => 'ngön seunaleuëk',
'isimage' => 'hubông beureukaih',
'whatlinkshere-prev' => '$1 {{PLURAL:$1|sigohlomjih|sigohlomjih}}',
'whatlinkshere-next' => '$1 {{PLURAL:$1|lheuëh nyan|lheuëh nyan}}',
'whatlinkshere-links' => '← hubông',
'whatlinkshere-hideredirs' => '$1 peuninah',
'whatlinkshere-hidetrans' => '$1 transklusi',
'whatlinkshere-hidelinks' => '$1 hubông',
'whatlinkshere-hideimages' => '$1 seuneumat beureukaih',
'whatlinkshere-filters' => 'Saréng',

# Block/unblock
'blockip' => 'Theun ureuëng nguy',
'ipboptions' => '2 jeum:2 hours,1 uroë:1 day,3 uroë:3 days,1 minggu:1 week,2 minggu:2 weeks,1 buleuën:1 month,3 buleuën:3 months,6 buleuën:6 months,1 thôn:1 year,sabé:infinite',
'ipblocklist' => 'Ureuëng nguy teutheun',
'ipblocklist-submit' => 'Mita',
'blocklink' => 'theun',
'unblocklink' => 'peugadöh theun',
'change-blocklink' => 'ubah theun',
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
'movearticle' => 'Peupinah ôn:',
'newtitle' => 'U nan barô:',
'move-watch' => 'Kalön ôn nyoë',
'movepagebtn' => 'Peupinah ôn',
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
'export' => 'Èkspor ôn',

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
'tooltip-pt-userpage' => 'Ôn ureuëng nguy Droëneuh',
'tooltip-pt-mytalk' => 'Ôn marit Droëneuh',
'tooltip-pt-preferences' => 'Atô',
'tooltip-pt-watchlist' => 'Dapeuta ôn nyang lônkalön',
'tooltip-pt-mycontris' => 'Dapeuta beuneuri Droëneuh',
'tooltip-pt-login' => 'Droëneuh geupadan keu tamong log, bah pih nyan hana geupeuwajéb.',
'tooltip-pt-logout' => 'Teubiët',
'tooltip-ca-talk' => 'Marit ôn asoë',
'tooltip-ca-edit' => 'Droëneuh jeuët neuandam ôn nyoë. Neunguy tumbôy eu dilèë yôh goh neukeubah.',
'tooltip-ca-addsection' => 'Puphôn beunagi barô',
'tooltip-ca-viewsource' => 'Ôn nyoë geupeulindông.
Droëneuh jeuët neu’eu nèjih.',
'tooltip-ca-history' => 'Seunalén away nibak ôn nyoë',
'tooltip-ca-protect' => 'Peulindông ôn nyoë',
'tooltip-ca-delete' => 'Sampôh ôn nyoë',
'tooltip-ca-move' => 'Peupinah ôn nyoë',
'tooltip-ca-watch' => 'Peutamah ôn nyoë u dapeuta kalön Droëneuh',
'tooltip-ca-unwatch' => 'Sampôh ôn nyoë nibak dapeuta keunalön Droëneuh',
'tooltip-search' => 'Mita {{SITENAME}}',
'tooltip-search-go' => 'Mita saboh ôn ngon nan nyang peureuséh lagèë nyoë meunyo na',
'tooltip-search-fulltext' => 'Mita ôn nyang na asoë lagèë nyoë',
'tooltip-p-logo' => 'Saweuë ôn keuë',
'tooltip-n-mainpage' => 'Jak u ôn keuë',
'tooltip-n-mainpage-description' => 'Saweuë ôn keuë',
'tooltip-n-portal' => 'Bhaih buët, peuë nyang jeuët neupubuët, pat keu mita sipeuë hay',
'tooltip-n-currentevents' => 'Mita haba barô',
'tooltip-n-recentchanges' => 'Dapeuta neuubah baro lam wiki.',
'tooltip-n-randompage' => 'Peuleumah ôn beurangkari',
'tooltip-n-help' => 'Bak mita bantu.',
'tooltip-t-whatlinkshere' => 'Dapeuta ban dum ôn wiki nyang meuhubông keunoë',
'tooltip-t-recentchangeslinked' => 'Neuubah barô ôn nyang na seuneumat u ôn nyoë',
'tooltip-feed-rss' => 'Umpeuën RSS keu ôn nyoë',
'tooltip-feed-atom' => 'Umpeuën Atom keu ôn nyoë',
'tooltip-t-contributions' => 'Eu dapeuta nyang ka geutuléh lé ureuëng nguy nyoë',
'tooltip-t-emailuser' => "Peu'ét surat-e keu ureuëng nguy nyoë",
'tooltip-t-upload' => 'Peutamong beureukaih',
'tooltip-t-specialpages' => 'Dapeuta ban dum ôn kusuih',
'tooltip-t-print' => 'Seunalén rakam ôn nyoë',
'tooltip-t-permalink' => 'Seuneumat teutap keu geunantoë ôn nyoë',
'tooltip-ca-nstab-main' => 'Eu ôn asoë',
'tooltip-ca-nstab-user' => 'Eu ôn ureuëng nguy',
'tooltip-ca-nstab-special' => 'Nyoë nakeuh ôn kusuih nyang h’an jeuët geu’andam.',
'tooltip-ca-nstab-project' => 'Eu ôn buët',
'tooltip-ca-nstab-image' => 'Eu ôn beureukaih',
'tooltip-ca-nstab-template' => 'Eu seunaleuëk',
'tooltip-ca-nstab-help' => 'Eu ôn beunantu',
'tooltip-ca-nstab-category' => 'Eu ôn kawan',
'tooltip-minoredit' => 'Bôh tanda keu nyoë sibagoë andam bacut',
'tooltip-save' => 'Keubah neuubah Droëneuh',
'tooltip-preview' => 'Peuleumah neuubah Droëneuh, neunguy nyoë sigohlom neukeubah!',
'tooltip-diff' => 'Peuleumah neuubah nyang ka Droëneuh peugöt',
'tooltip-compareselectedversions' => 'Ngiëng bida antara duwa curak ôn nyang jipilèh.',
'tooltip-watch' => 'Peutamah ôn nyoë u dapeuta keunalön Droëneuh',
'tooltip-rollback' => 'Peuriwang neu’andam-neu’andam bak ôn nyoë u nyang tuléh keuneulheuëh lam sigo teugön',
'tooltip-undo' => 'Peuriwang geunantoë nyoë ngön peuhah plôk neu’andam ngön cara eu dilèë. Choë jeuët geupeutamah bak plôk ehtisa.',
'tooltip-summary' => 'Pasoë éhtisa paneuk',

# Info page
'pageinfo-toolboxlink' => 'Teuneurang laman',

# Browsing diffs
'previousdiff' => '← Bida away',
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
'edit-externally-help' => '(Ngiëng [//meta.wikimedia.org/wiki/Help:External_editors arah atô] keu beurita leubèh lanjôt)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ban dum',
'namespacesall' => 'ban dum',
'monthsall' => 'ban dum',

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
'specialpages' => 'Ôn kusuih',

# External image whitelist
'external_image_whitelist' => '#Neupubiyeue bareh nyoe lagee na<pre>
#Neunguy fragmen-fragmen ekspresi regular (bak bagian antara // mantong) di yup nyoe
#fragmen-fragmen nyoe eunteuk geupeupaih ngon URL nibak gamba-gamba luwa (nyang geupeuhubong lansong)
#Fragmen nyang paih eunteuk geupeuleumah sibagoe gamba, seuhjih keu link mantong
#Bareh nyang geupuphon ngon # eunteuk geupeujeuet keu bareh beunalah
#Nyoe hana geupubida haraih rayek ngon ubeut
#Neupeuduek ban dum beunagi ekspresi biasa di yup bareh nyoe. Neupubiyeue bareh nyoe lagee na</pre>',

# Special:Tags
'tag-filter' => 'Filter [[Special:Tags|tag]]:',

# Search suggestions
'searchsuggest-search' => 'Mita',

);
