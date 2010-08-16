<?php
/** Bahasa Banjar (Bahasa Banjar)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alamnirvana
 * @author Ezagren
 * @author J Subhi
 */

$fallback = 'id';

$messages = array(
# User preference toggles
'tog-underline'             => 'Garisi di bawah tautan',
'tog-highlightbroken'       => 'Bantuk tautan pagat <a href="" class="puga">nangkaya ini</a> (pilihan: nangkaya ini<a href="" class="internal">?</a>)',
'tog-justify'               => 'Ratakan paragraf',
'tog-hideminor'             => 'Sungkupakan babakan sapalih dalam parubahan tahanyar',
'tog-newpageshidepatrolled' => 'Sungkupakan tungkaran nang diitihi matan daptar tungkaran hanyar',
'tog-watchcreations'        => 'Tambahi tungkaran nang ulun ulah ka daptar itihan',
'tog-watchdefault'          => 'Tambahi tungkaran nang ulun babak ka daptar itihan ulun',
'tog-watchmoves'            => 'Tambahi tungkaran nang ulun pindah ka daptar itihan ulun',
'tog-watchdeletion'         => 'Tambahi tungkaran nang ulun hapus ka daptar itihan ulun',

'underline-never' => 'Kada suah',

# Font style option in Special:Preferences
'editfont-monospace' => 'Tulisan Monospace',
'editfont-sansserif' => 'Tulisan Sans-serif',
'editfont-serif'     => 'Tulisan Serif',

# Dates
'sunday'        => 'Ahat',
'monday'        => 'Sanayan',
'tuesday'       => 'Salasa',
'wednesday'     => 'Arba',
'thursday'      => 'Kemés',
'friday'        => 'Jumahat',
'saturday'      => 'Saptu',
'sun'           => 'Aha',
'mon'           => 'San',
'tue'           => 'Sal',
'wed'           => 'Arb',
'thu'           => 'Kem',
'fri'           => 'Jum',
'sat'           => 'Sap',
'january'       => 'Januari',
'february'      => 'Pibuari',
'march'         => 'Maret',
'april'         => 'April',
'may_long'      => 'Mai',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'Agustus',
'september'     => 'Siptimbir',
'october'       => 'Uktubir',
'november'      => 'Nupimbir',
'december'      => 'Disimbir',
'january-gen'   => 'Januari',
'february-gen'  => 'Pibuari',
'march-gen'     => 'Marit',
'april-gen'     => 'April',
'may-gen'       => 'Mai',
'june-gen'      => 'Juni',
'july-gen'      => 'Juli',
'august-gen'    => 'Agustus',
'september-gen' => 'Siptimbir',
'october-gen'   => 'Oktober',
'november-gen'  => 'Nopember',
'december-gen'  => 'Desember',
'jan'           => 'Jan',
'feb'           => 'Pib',
'mar'           => 'Mar',
'apr'           => 'Apr',
'may'           => 'Mai',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Agu',
'sep'           => 'Sip',
'oct'           => 'Ukt',
'nov'           => 'Nup',
'dec'           => 'Dis',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Kataguri|Kataguri}}',
'category_header'        => "Tungkaran-tungkaran dalam kataguri ''$1''",
'subcategories'          => 'Subkataguri',
'hidden-categories'      => '{{PLURAL:$1|Kataguri tasungkup|Kataguri tasungkup}}',
'category-subcat-count'  => '{{PLURAL:$2|Katagri ini baisi asa subkataguri barikut.|Kataguri ini baisi {{PLURAL:$1|subkataguri|$1 subkatagori}} barikut, matan sabarataan $2.}}',
'category-article-count' => '{{PLURAL:$2|Kataguri ni baisi asa tungkaran barikut haja.|Kataguri ini baisi {{PLURAL:$1|tungkaran|$1 tungkaran}}, matan sabarataan $2.}}',
'listingcontinuesabbrev' => 'samb.',

'article'       => 'Tulisan',
'newwindow'     => '(buka di lalungkang hanyar)',
'cancel'        => 'walangi',
'moredotdotdot' => 'Lainnya...',
'mypage'        => 'Tungkaran ulun',
'mytalk'        => 'Pamandiran ulun',
'navigation'    => 'Napigasi',
'and'           => '&#32;wan',

# Cologne Blue skin
'qbfind'         => 'Paugaian',
'qbedit'         => 'Babak',
'qbpageoptions'  => 'Tungkaran ini',
'qbmyoptions'    => 'Tungkaran ulun',
'qbspecialpages' => 'Tungkaran istimiwa',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-delete'       => 'Hapus',
'vector-action-move'         => 'Pindahakan',
'vector-action-protect'      => 'Lindungi',
'vector-action-undelete'     => 'Pawalangan pahapusan',
'vector-namespace-help'      => 'Patulung',
'vector-namespace-image'     => 'Barakas',
'vector-namespace-main'      => 'Tungkaran',
'vector-namespace-media'     => 'Tungkaran media',
'vector-namespace-mediawiki' => 'Pasan',
'vector-namespace-project'   => 'Rangka gawian',
'vector-namespace-special'   => 'Tungkaran istimiwa',
'vector-namespace-template'  => 'Citakan',
'vector-namespace-user'      => 'Tungkaran Pamuruk',
'vector-view-create'         => 'Ulah',
'vector-view-edit'           => 'Ubah',
'vector-view-history'        => 'Sajarah bahari',
'vector-view-view'           => 'Baca',
'actions'                    => 'Tindakan',
'namespaces'                 => 'Ngaran kamar',

'errorpagetitle'   => 'Kasalahan',
'returnto'         => 'Bulik ka $1.',
'tagline'          => 'Matan {{SITENAME}}',
'help'             => 'Patulung',
'search'           => 'Gagai',
'searchbutton'     => 'Gagai',
'go'               => 'Tulak',
'searcharticle'    => 'Tulak',
'history'          => 'Tungkaran halam',
'history_short'    => 'Tungkaran halam',
'printableversion' => 'Nang kawa dicitak',
'permalink'        => 'Tautan tatap',
'print'            => 'Citak',
'edit'             => 'Babak',
'create'           => 'Ulah',
'editthispage'     => 'Babak tungkaran ini',
'create-this-page' => 'Ulah tungkaran ini',
'delete'           => 'Hapus',
'deletethispage'   => 'Hapus tungkaran ini',
'protect'          => 'Lindungi',
'protect_change'   => 'ubah',
'protectthispage'  => 'Lindungi tungkaran ini',
'newpage'          => 'Tungkaran hanyar',
'talkpage'         => 'Pandirakan tungkaran ini',
'talkpagelinktext' => 'Pandir',
'specialpage'      => 'Tungkaran istimiwa',
'personaltools'    => 'Pakakas surang',
'articlepage'      => 'Tiringi isi tulisan',
'talk'             => 'Pamandiran',
'views'            => 'Titiringan',
'toolbox'          => 'Wadah pakakas',
'userpage'         => 'Tiringi tungkaran pamuruk',
'imagepage'        => 'Tiringi tungkaran barakas',
'mediawikipage'    => 'Tiringi tungkaran pasan sistim',
'templatepage'     => 'Tiringi tungkaran citakan',
'viewhelppage'     => 'Tiringi tungkaran patulung',
'viewtalkpage'     => 'Tiringi tungkaran pamandiran',
'otherlanguages'   => 'Dalam bahasa lain',
'redirectedfrom'   => '(Diugahakan matan $1)',
'redirectpagesub'  => 'Tungkaran paugahan',
'lastmodifiedat'   => 'Tungkaran ini tarakhir digaganti pada $2, $1.',
'jumpto'           => 'Malacung ka',
'jumptonavigation' => 'napigasi',
'jumptosearch'     => 'Gagai',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Pasal {{SITENAME}}',
'aboutpage'            => 'Project:Pasal',
'copyright'            => 'Isi tasadia sasuai lawan $1.',
'copyrightpage'        => '{{ns:project}}:Hak cipta',
'disclaimers'          => 'Panyangkalan',
'disclaimerpage'       => 'Project:Panyangkalan umum',
'edithelp'             => 'Patulung mambabak',
'edithelppage'         => 'Help:Pambabakan',
'helppage'             => 'Help:Isi',
'mainpage'             => 'Tungkaran Tatambaian',
'mainpage-description' => 'Tungkaran tatambaian',
'privacy'              => 'Kaaripan paribadi',
'privacypage'          => 'Project:Kaaripan paribadi',

'badaccess' => 'Parijinan tasalah',

'retrievedfrom'           => 'Dijumput matan "$1"',
'youhavenewmessages'      => 'Pian baisi $1 ($2)',
'newmessageslink'         => 'pasan hanyar',
'newmessagesdifflink'     => 'parubahan tarakhir',
'youhavenewmessagesmulti' => 'Pian baisi pasan hanyar dalam $1',
'editsection'             => 'babak',
'editold'                 => 'babak',
'viewsourceold'           => 'tiringi asal mulanya',
'editlink'                => 'babak',
'viewsourcelink'          => 'tiringi asal mulanya',
'editsectionhint'         => 'Ubah hapat: $1',
'toc'                     => 'Isi',
'showtoc'                 => 'tampaiakan',
'hidetoc'                 => 'sungkupakan',
'feedlinks'               => 'Kitihan',
'site-rss-feed'           => 'Kitihan RSS $1',
'site-atom-feed'          => 'Kitihan Atum $1',
'page-rss-feed'           => "Kitihan RSS ''$1''",
'page-atom-feed'          => "Kitihan Atum ''$1''",
'red-link-title'          => '$1 (tungkaran baluman ada)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Tungkaran',
'nstab-user'      => 'Tungkaran Pamuruk',
'nstab-special'   => 'Tungkaran istimiwa',
'nstab-project'   => 'Rangka gawian',
'nstab-image'     => 'Barakas',
'nstab-mediawiki' => 'Pasan',
'nstab-template'  => 'Citakan',
'nstab-help'      => 'Patulung',
'nstab-category'  => 'Kataguri',

# Main script and global functions
'nosuchaction' => 'Kadada tindakan',

# General errors
'missing-article'      => 'Basis data kada ulihan manggagai kata matan tungkaran nang saharusnya ada, ialah "$1" $2.

Nangkaya ini biasanya dimargakan tautan lawas ka sabuah tungkaran nang halamnya sudah dihapus.

Munnya lainan ini pasalnya, Pian mungkin batamu bug dalam perangkat lunak.
Silakan lapurakan ini ka saurang [[Special:ListUsers/sysop|pambakal]], ulah catatan URL nang ditulaki',
'missingarticle-rev'   => '(ralatan#: $1)',
'fileappenderror'      => 'Kada kawa mamasukakan "$1" ka "$2".',
'filecopyerror'        => 'Kada kawa manyalin "$1" ka "$2".',
'filerenameerror'      => 'Kada kawa maubah ngaran barakas "$1" manjadi "$2".',
'filedeleteerror'      => 'Kada kawa mahapus barakas "$1".',
'filenotfound'         => 'Kada kawa maugai barakas "$1".',
'badtitletext'         => 'Judul tungkaran nang diminta kada sah, kada baisi, atawa kada pasnya tautan judul antar-bahasa atawa antar-wiki.
Nangini bisa baisi satu atawa labih hurup nang saharusnya kadada di judul.',
'querypage-no-updates' => 'Pamugaan matan tungkaran ini rahat dipajahkan. Data nang ada di sia wayahini kada akan dimuat ulang.',
'viewsource'           => 'Tiringi asal mulanya',
'viewsourcefor'        => 'gasan $1',

# Login and logout pages
'yourname'                => 'Ngaran pamuruk',
'yourpassword'            => 'Katasunduk:',
'remembermypassword'      => 'Ingatan log babuat ulun dalam komputer ini (salawas $1{{PLURAL:$1|hari|hari}})',
'login'                   => 'Babuat',
'nav-login-createaccount' => 'Babuat log/ulah akun',
'loginprompt'             => "Pian harus mengaktipakan ''cookies'' hagan kawa babuat log ka {{SITENAME}}.",
'userlogin'               => 'Babuat log / ulah akun',
'userloginnocreate'       => 'Babuat log',
'logout'                  => 'Kaluar',
'userlogout'              => 'Kaluar',
'notloggedin'             => 'Balum babuat log',
'nologinlink'             => 'Daptarkan akun hanyar',
'gotaccountlink'          => 'Babuat log',
'mailmypassword'          => 'Kirimi katasunduk hanyar',
'accountcreated'          => 'Akun diulah',
'accountcreatedtext'      => 'Akun pamuruk gasan $1 sudah diulah.',
'createaccount-title'     => 'Paulahan akun gasan {{SITENAME}}',
'loginlanguagelabel'      => 'Bahasa: $1',

# Password reset dialog
'resetpass-submit-cancel' => 'Walangi',

# Edit page toolbar
'bold_sample'     => 'Naskah kandal',
'bold_tip'        => 'Naskah kandal',
'italic_sample'   => 'Naskah hiring',
'italic_tip'      => 'Naskah hiring',
'link_sample'     => 'Judul tautan',
'link_tip'        => 'Tautan dalam',
'extlink_sample'  => 'http://www.example.com judul tautan',
'extlink_tip'     => 'Tautan luar (Ingatakan bamula wan http://)',
'headline_sample' => 'Naskah judul',
'headline_tip'    => 'Judul tingkat 2',
'math_sample'     => 'Masukakan rumus di sia',
'math_tip'        => 'Rumus matamatika (LaTeX)',
'nowiki_sample'   => 'Masukakan kata kada baformat di sia',
'nowiki_tip'      => 'Halinakan pambantukan/purmat wiki',
'image_tip'       => 'Maktub-akan barakas',
'media_tip'       => 'Tautan barakas',
'sig_tip'         => 'Tanda teken Pian wan bacap wayah',
'hr_tip'          => 'Garis horisontal',

# Edit pages
'summary'                          => 'Kasimpulan:',
'subject'                          => 'Subyek/judul:',
'minoredit'                        => 'Ini adalah babakan sapalih',
'watchthis'                        => 'Itihi tungkaran ini',
'savearticle'                      => 'Simpan tungkaran',
'preview'                          => 'Tilik',
'showpreview'                      => 'Tampaiakan titilikan',
'showdiff'                         => 'Tampaiakan parubahan',
'anoneditwarning'                  => "'''Paringatan:''' Pian baluman babuat log.
Alamat IP Pian akan dirakam dalam tungkaran babakan halam",
'summary-preview'                  => 'Tilikan kasimpulan:',
'blockednoreason'                  => 'kadada alasan nang diunjukakan',
'newarticle'                       => '(Hanyar)',
'newarticletext'                   => "Pian maumpati sabuah tautan ka tungkaran nang baluman ada lagi. Gasan maulah tungkaran, mulai ja mangatik pada kutak di bawah (lihati [[{{MediaWiki:Helppage}}|tungkaran patulung]] gasan panjalasan labih). Amun Pian ka sia cagaran tasalah, klik picikan '''back''' di panangadah web Pian.",
'noarticletext'                    => 'Parhatan ini kadada naskah di tungkaran ini.
Pian kawa [[Special:Search/{{PAGENAME}}|manggagai gasan judul ini]] pintang tungkaran lain,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} manggagai log barait].</span>,
atawa [{{fullurl:{{FULLPAGENAME}}|action=edit}} mambabak tungkaran ini]</span>.',
'previewnote'                      => "'''Ingatakanlah bahua ini titilikan haja nang balum disimpan!'''",
'editing'                          => 'Mambabak $1',
'editingsection'                   => 'Mambabak $1 (hapat)',
'editingcomment'                   => 'Mambaiki $1 (hapat hanyar)',
'copyrightwarning'                 => "Muhun dicatat bahwasanya samunyaan sumbangan ka {{SITENAME}} adalah sudah dipartimbangkan diedarakan di bawah $2 (lihati $1 gasan rincian). Amun Pian kada handak tulisan Pian dibabak wan diedarakan, laluai kada usah mangirim ini ka sia. <br />
Pian jua bajanji ka kami amun Pian manulis ini saurangan, atawa manjumput ini matan sabuah asal mula ampun umum atawa asal mula lainnya nang samacam.
'''Jangan kirimkan gawian bahak cipta kada baijin!'''",
'templatesused'                    => '{{PLURAL:$1|Citakan|Citakan}} nang digunakan di tungkaran ini:',
'templatesusedpreview'             => '{{PLURAL:$1|Citakan|Citakan}} nang digunakan di titilikan ini:',
'template-protected'               => '(dilindungi)',
'template-semiprotected'           => '(semi-dilindungi)',
'hiddencategories'                 => 'Tungkaran ini adalah angguta matan {{PLURAL:$1|1 kataguri tasungkup|$1 kataguri tasungkup}}:',
'permissionserrorstext-withaction' => 'Pian kada mamiliki hak manarusakan gasan $2, karana {{PLURAL:$1|alasan|alasan}} ini:',

# Account creation failure
'cantcreateaccounttitle' => 'Akun kada kawa diulah',

# History pages
'viewpagelogs'           => 'Tiringi log tungkaran ini',
'currentrev-asof'        => 'Ralatan pahanyarnya pada $1',
'revisionasof'           => 'Ralatan matan $1',
'previousrevision'       => '←Ralatan talawas',
'nextrevision'           => 'Ralatan salanjutnya→',
'currentrevisionlink'    => 'Ralatan wayahini',
'cur'                    => 'dmn',
'last'                   => 'Sabalum',
'histlegend'             => "Pilihan mananding: tandai kutak-kutak radiu ralatan-ralatan nang handak ditanding wan picik enter atawa picikan di bawah.<br />Legend: '''({{dmn}})''' =lainnya awan ralatan pahanyarnya, '''({{sabalum}})''' = lainnya awan ralatan sabalumnya, '''{{s}}''' = babakan sapalih.",
'history-fieldset-title' => 'Tangadahi halam',
'histfirst'              => 'Palawasnya',
'histlast'               => 'Pahanyarnya',

# Revision deletion
'rev-delundel'               => 'tampaiakan/sungkupakan',
'rev-showdeleted'            => 'tampaiakan',
'revdelete-show-file-submit' => 'Ya',
'revdelete-radio-set'        => 'Ya',
'revdelete-radio-unset'      => 'Kada',
'revdel-restore'             => 'Ubah tampilan',
'pagehist'                   => 'Sajarah tungkaran',
'revdelete-reasonotherlist'  => 'Alasan lain',

# Merge log
'revertmerge' => 'Walang panggabungan',

# Diffs
'history-title'           => "Halam ralatan matan ''$1''",
'difference'              => '(Nang balain antar ralatan)',
'lineno'                  => 'Baris $1:',
'compareselectedversions' => 'Tandingakan ralatan nang dipilih',
'editundo'                => 'walangi',

# Search results
'searchresults'             => 'Kulihan panggagaian',
'searchresults-title'       => 'Kulihan gagai gasan "$1"',
'searchresulttext'          => 'Gasan panjalasan labih lanjut pasal panggagaian pintangan {{SITENAME}}, lihati [[{{MediaWiki:Helppage}}|tungkaran patulung]].',
'searchsubtitle'            => 'Pian manggagai \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|samunyaan tungkaran bamula wan "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|samunyaan tungkaran nang bataut ka "$1"]])',
'searchsubtitleinvalid'     => "Pian manggagai '''$1'''",
'notitlematches'            => 'Kadada tungkaran bajudul pas',
'notextmatches'             => 'Kadada tungkaran banaskah pas',
'prevn'                     => '{{PLURAL:$1|$1}} tadahulu',
'nextn'                     => '{{PLURAL:$1|$1}} dudinya',
'viewprevnext'              => 'Tiringi ($1 {{int:pipe-separator}} $2) ($3)',
'searchprofile-articles'    => 'Tulisan',
'searchprofile-everything'  => 'Samunyaan',
'search-result-size'        => '$1 ({{PLURAL:$2|1 ujar|$2 ujar}})',
'search-redirect'           => '(Paugahan $1)',
'search-section'            => '(hapat $1)',
'search-suggest'            => 'Inikah maksud Pian: $1',
'search-interwiki-caption'  => 'Dingsanak rangka gawian',
'search-interwiki-default'  => 'Kulihan $1',
'search-interwiki-more'     => '(lagi)',
'search-mwsuggest-enabled'  => 'awan saran',
'search-mwsuggest-disabled' => 'kadada saran',
'searchall'                 => 'samunyaan',
'nonefound'                 => "'''Catatan''': babarapa ngaran kamar haja nang baku digagai.
Cubai parmintaan Pian lawan ''all:'' gasan manggagai samunyaan isi (tamasuk tungkaran pamandiran, citakan, dll), atawa puruk ngaran kamar nang dihandaki sabagai awalan.",
'powersearch'               => 'Panggagaian mahir',
'powersearch-legend'        => 'Panggagaian mahir',
'powersearch-ns'            => 'Manggagai di ngaran kamar:',
'powersearch-redir'         => 'Daptar paugahan',
'powersearch-field'         => 'Manggagai',
'powersearch-toggleall'     => 'Samunyaan',
'powersearch-togglenone'    => 'Kadada',

# Quickbar
'qbsettings-none'          => 'Kadada',
'qbsettings-floatingleft'  => 'Mangambang sabalah kiwa',
'qbsettings-floatingright' => 'Mangambang sabalah kanan',

# Preferences page
'preferences'               => 'Kakatujuan',
'mypreferences'             => 'Nang ulun katuju',
'prefs-skin'                => 'Kulimbit',
'datedefault'               => 'Kadada katujuan',
'prefs-personal'            => 'Data awak',
'prefs-watchlist'           => 'Paitihan',
'saveprefs'                 => 'Simpan',
'searchresultshead'         => 'Gagai',
'timezoneregion-america'    => 'Amirika',
'timezoneregion-antarctica' => 'Antarktika',
'timezoneregion-asia'       => 'Asia',
'timezoneregion-australia'  => 'Australia',
'prefs-files'               => 'Barakas',
'prefs-textboxsize'         => 'Ukuran kutak ubahan',
'username'                  => 'Ngaran pamuruk:',
'yourrealname'              => 'Ngaran asli:',
'yourlanguage'              => 'Bahasa:',
'badsiglength'              => 'Tapak tangan Sampian talalu panjang. Jangan malabihi pada $1 {{PLURAL:$1|karakter|karakter}}.',
'gender-male'               => 'Lalakian',
'gender-female'             => 'Bibinian',
'prefs-displayrc'           => 'Pilihan tampilan',
'prefs-diffs'               => 'Bida',

# User rights
'userrights-editusergroup' => 'Babak galambang pamuruk',
'saveusergroups'           => 'Simpan galambang pamuruk',

# Groups
'group'       => 'Galambang:',
'group-bot'   => 'Bot',
'group-sysop' => 'Pambakal',

'group-user-member'  => 'Pamuruk',
'group-bot-member'   => 'Bot',
'group-sysop-member' => 'Pambakal',

'grouppage-bot'   => '{{ns:project}}:Bot',
'grouppage-sysop' => '{{ns:project}}:Pambakal',

# Rights
'right-read'          => 'Mambaca tungkaran',
'right-edit'          => 'Mambaiki tungkaran',
'right-move'          => 'Mamindahakan tungkaran',
'right-movefile'      => 'Mamindahakan barakas',
'right-delete'        => 'Mahapus tungkaran',
'right-browsearchive' => 'Manggagai tungkaran nang sudah dihapus',
'right-trackback'     => 'Mangirimakan sabuah panjajakan balik',

# User rights log
'rightslog'  => 'Log parubahan hak masuk',
'rightsnone' => '(kadada)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'babak tungkaran ini',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|parubahan|parubahan}}',
'recentchanges'                  => 'Parubahan tahanyar',
'recentchanges-legend'           => 'Pilihan parubahan tahanyar',
'recentchanges-feed-description' => 'Susuri parubahan tahanyarnya dalam wiki di kitihan ini',
'rcnote'                         => "Di bawah ni {{PLURAL:$1|'''1'''|'''$1'''}} parubahan tahanyar dalam {{PLURAL:$2|'''1''' hari|'''$2''' hari}} tarakhir, sampai $4 pukul $5.",
'rclistfrom'                     => 'Tampaiakan parubahan tahanyar matan $1',
'rcshowhideminor'                => '$1 pambabakan sapalih',
'rcshowhidebots'                 => '$1 bot',
'rcshowhideliu'                  => '$1 pamuruk nang babuat di log',
'rcshowhideanons'                => '$1 pamuruk kada bangaran',
'rcshowhidemine'                 => '$1 babakan ulun',
'rclinks'                        => 'Tampaiakan $1 parubahan tahanyarnya dalam $2 hari tarakhir<br />$3',
'diff'                           => 'lain',
'hist'                           => 'Hal',
'hide'                           => 'Sungkupakan',
'show'                           => 'Tampaiakan',
'minoreditletter'                => 's',
'newpageletter'                  => 'H',
'boteditletter'                  => 'b',
'rc-enhanced-expand'             => 'Tampaiakan rincian (harus ada JavaScript)',
'rc-enhanced-hide'               => 'Sungkupakan rincian',

# Recent changes linked
'recentchangeslinked'         => 'Parubahan tarait',
'recentchangeslinked-title'   => 'Parubahan nang tarait lawan "$1"',
'recentchangeslinked-summary' => "Ini adalah sabuah daptar parubahan nang diulah hanyar-hanyar ini pada tungkaran batautan matan sabuah tungkaran tartantu (atawa ka angguta matan sabuah kataguri tartantu).
Tungkaran-tungkaran dalam [[Special:Watchlist|daptar itihan Pian]] ditandai '''kandal'''.",
'recentchangeslinked-page'    => 'Ngaran tungkaran:',
'recentchangeslinked-to'      => 'Tiringakan pahurupan matan tungkaran-tungkaran nang tasambung lawan tungkaran nang disurungakan',

# Upload
'upload'             => 'Buat naik barakas',
'uploadlogpage'      => 'Log mambuati',
'verification-error' => 'Barakas nangini kada lulus paitihan.',
'tmp-create-error'   => 'Kada kawa maulah barakas pahadangan.',
'tmp-write-error'    => 'Kasalahan sawaktu manulis barakas pahadangan.',
'uploadedimage'      => "sudah dibuatakan ''[[$1]]''",

'upload-misc-error' => 'Tasalah buat nang kada dipinandui',

# File description page
'filehist'                  => 'Barakas halam',
'filehist-help'             => 'Klik pada tanggal/waktu gasan maniringi barakas ini pada wayah itu.',
'filehist-deleteall'        => 'hapus samunyaan',
'filehist-deleteone'        => 'hapus',
'filehist-revert'           => 'bulikakan',
'filehist-current'          => 'daminian',
'filehist-datetime'         => 'Tanggal/Waktu',
'filehist-thumb'            => 'Pahalusan',
'filehist-thumbtext'        => 'Pahalusan gasan bantuk per $1',
'filehist-user'             => 'Pamuruk',
'filehist-dimensions'       => 'Matra',
'filehist-comment'          => 'Ulasan',
'imagelinks'                => 'Tautan barakas',
'linkstoimage'              => '{{PLURAL:$1|tautan tungkaran|$1 tautan tungkaran}} dudi ka barakas ini:',
'sharedupload'              => 'Barakas ini matan $1 wan mungkin dipuruk rangka-rangka gawian lain.',
'uploadnewversion-linktext' => 'Buatakan bantuk nang labih hanyar matan barakas ini',
'shared-repo-from'          => 'matan $1',

# File reversion
'filerevert' => 'Bulikakan $1',

# File deletion
'filedelete'        => 'Mahapus $1',
'filedelete-submit' => 'Hapus',

# MIME search
'download' => 'buat turun',

# Statistics
'statistics'       => 'Statistik',
'statistics-pages' => 'Jumlah tungkaran',

'brokenredirects-delete' => 'hapus',

'withoutinterwiki-submit' => 'Tampaiakan',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|bit|bit}}',
'nmembers'      => '$1 {{PLURAL:$1|isi|isi}}',
'prefixindex'   => 'Samunyaan tungkaran wan awalan',
'newpages'      => 'Tungkaran hanyar',
'move'          => 'Pindahakan',
'movethispage'  => 'Pindahakan tungkaran ini',
'pager-newer-n' => '{{PLURAL:$1|tahanyar 1|tahanyar $1}}',
'pager-older-n' => '{{PLURAL:$1|talawas 1|talawas $1}}',

# Book sources
'booksources'               => 'Buku bamula',
'booksources-search-legend' => 'Gagai gasan buku asal mula',
'booksources-go'            => 'Tulak ka',

# Special:Log
'log' => 'Log',

# Special:AllPages
'allpages'       => 'Samunyaan tungkaran',
'alphaindexline' => '$1 sampai $2',
'prevpage'       => 'Tungkaran sabalumnya ($1)',
'allpagesfrom'   => 'Tampaiakan tungkaran mulai matan:',
'allpagesto'     => 'Tampaiakan ujung pahabisan tungkaran:',
'allarticles'    => 'Samunyaan tungkaran',
'allpagessubmit' => 'Tulak',

# Special:LinkSearch
'linksearch' => 'Tautan luar',

# Special:Log/newusers
'newuserlogpage'          => 'Log pamuruk hanyar',
'newuserlog-create-entry' => 'Akun pamuruk hanyar',

# Special:ListGroupRights
'listgrouprights-members' => '(daptar angguta)',

# E-mail user
'emailuser' => 'Surel pamuruk',

# Watchlist
'watchlist'         => 'Daptar itihan ulun',
'mywatchlist'       => 'Daptar itihan ulun',
'addedwatch'        => 'Sudah ditambahakan ka daptar itihan',
'addedwatchtext'    => "Tungkaran \"[[:\$1]]\" sudah ditambahakan ke [[Special:Watchlist|daptar itihan]] Pian.
Parubahan-parubahan salanjutnya pada tungkaran ini dan tungkaran pamandiran taraitnya akan takambit di sia, wan tungkaran itu akan ditampaiakan '''kandal''' pada [[Special:RecentChanges|daptar parubahan tahanyar]] cagar labih mudah diitihi.",
'removedwatch'      => 'Sudah dibuang matan daptar itihan',
'removedwatchtext'  => 'Tungkaran "[[:$1]]" sudah dihapus matan [[Special:Watchlist|daptar itihan]] Pian.',
'watch'             => 'Itih',
'watchthispage'     => 'Itihi tungkaran ini',
'unwatch'           => 'walang maitihi',
'watchlist-details' => '{{PLURAL:$1|$1 tungkaran|$1 tungkaran}} dalam daptar itihan Pian, kada mahitung tungkaran pamandiran.',
'wlshowlast'        => 'Tampaiakan $1 jam $2 hari pahabisan $3',
'watchlist-options' => 'Pilihan daptar itihan',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Maitihi...',
'unwatching' => 'Kada jadi maitihi...',

# Delete
'deletepage'            => 'Hapus tungkaran',
'confirmdeletetext'     => 'Pian handak mahapus sabuah tungkaran awan samunyaan halamnya.
Muhun mamastiakan amun Pian handak manggawi ini, bahwasa Pian paham akibatnya, wan apa nang Pian gawi ini sasuai awan [[{{MediaWiki:Policy-url}}|kabijakan {{SITENAME}}]].',
'actioncomplete'        => 'Pangulahan tuntung',
'deletedtext'           => '"<nowiki>$1</nowiki>" sudah dihapus. Lihati $2 gasan log wayahini tungkaran nang sudah dihapus.',
'deletedarticle'        => '"[[$1]]" tahapus',
'dellogpage'            => 'Log pahapusan',
'deletecomment'         => 'Alasan:',
'deleteotherreason'     => 'Alasan lain/tambahan:',
'deletereasonotherlist' => 'Alasan lain',

# Rollback
'rollbacklink' => 'bulikakan',

# Protect
'protectlogpage'              => 'Log parlindungan',
'protectedarticle'            => "dilindungi ''[[$1]]''",
'modifiedarticleprotection'   => 'maubah tingkat perlindungan "[[$1]]"',
'protectcomment'              => 'Alasan:',
'protectexpiry'               => 'Kadaluwarsa:',
'protect_expiry_invalid'      => 'Waktu kadaluwarsa kada sah.',
'protect_expiry_old'          => 'Waktu kadaluwarsa adalah pada masa bahari.',
'protect-text'                => "Pian kawa maniring atawa mangganti tingkatan parlindungan gasan tungkaran '''<nowiki>$1</nowiki>''' di sia.",
'protect-locked-access'       => "Akun Pian kada baisi ijin gasan maubah tingkatan palindungan tungkaran.
Di sia adalah setelan wayah ini gasan tungkaran '''$1''':",
'protect-cascadeon'           => 'Tungkaran ini rahatan dilindungi lantaran diumpatakan dalam {{PLURAL:$1|tungkaran|tungkaran-tungkaran}} barikut nang sudah aktip palindungan barentengnya.
Pian kawa maubah tingkatan palindungan gasan tungkaran ini, tagal ini kada pacang mangaruhi palindungan barenteng.',
'protect-default'             => 'Bulihakan samua pamuruk',
'protect-fallback'            => 'Mamarluakan ijin "$1"',
'protect-level-autoconfirmed' => 'Blukir pamuruk hanyar wan kada tadaptar',
'protect-level-sysop'         => 'Hanya pambakal',
'protect-summary-cascade'     => 'barenteng',
'protect-expiring'            => 'kadaluwarsa $1 (UTC)',
'protect-cascade'             => 'Lindungi tungkaran-tungkaran nang tamasuk dalam tungkaran ini (palindungan barenteng)',
'protect-cantedit'            => 'Pian kada kawa maubah tingkatan parlindungan tungkaran ini karana Pian kada baisi hak gasan itu.',
'restriction-type'            => 'Parijinan:',
'restriction-level'           => 'Tingkatan pambatasan:',

# Undelete
'undeletelink'              => 'tiring/bulikakan',
'undeletedarticle'          => "''[[$1]]'' sudah dibulikakan",
'undelete-show-file-submit' => 'Ya',

# Namespace form on various pages
'namespace'      => 'Ngaran kamar:',
'invert'         => 'Bulikakan pilihan',
'blanknamespace' => '(Tatambaian)',

# Contributions
'contributions'       => 'Sumbangan pamuruk',
'contributions-title' => 'Sumbangan pamuruk gasan $1',
'mycontris'           => 'Sumbangan ulun',
'contribsub2'         => 'Gasan $1 ($2)',
'uctop'               => ' (atas)',
'month'               => 'Matan bulan (wan sabalumnya):',
'year'                => 'Matan tahun (wan sabalumnya):',

'sp-contributions-newbies'  => 'Tunjukakan sumbangan hanya matan pamuruk-pamuruk hanyar',
'sp-contributions-blocklog' => 'Log blukir',
'sp-contributions-talk'     => 'pandir',
'sp-contributions-search'   => 'Gagai gasan andil/sumbangan',
'sp-contributions-username' => 'Alamat IP atawa ngaran pamuruk:',
'sp-contributions-submit'   => 'Gagai',

# What links here
'whatlinkshere'            => 'Tautan apa di sia',
'whatlinkshere-title'      => "Tungkaran-tungkaran nang batautan ka ''$1''",
'whatlinkshere-page'       => 'Tungkaran:',
'linkshere'                => "Tungkaran-tungkaran barikut batautan ka '''[[:$1]]''':",
'isredirect'               => 'tungkaran paugahan',
'istemplate'               => 'transklusi',
'isimage'                  => 'Tautan barakas',
'whatlinkshere-prev'       => '$1 {{PLURAL:$1|sabalumnya|sabalumnya}}',
'whatlinkshere-next'       => '{{PLURAL:$1|dudi|dudi $1}}',
'whatlinkshere-links'      => '← tautan',
'whatlinkshere-hideredirs' => '$1 paugahan',
'whatlinkshere-hidetrans'  => '$1 transklusi',
'whatlinkshere-hidelinks'  => '$1 tautan',
'whatlinkshere-filters'    => 'Saringan',

# Block/unblock
'blockip'                  => 'Blukir pamuruk',
'ipboptions'               => '2 jam:2 hours,1 hari:1 day,3 hari:3 days,1 minggu:1 week,2 minggu:2 weeks,1 bulan:1 month,3 bulan:3 months,6 bulan:6 months,1 tahun:1 year,salawasan:infinite',
'ipbotheroption'           => 'lainnya',
'ipblocklist'              => 'Alamat IP wan ngaran pamuruk nang diblukir',
'ipblocklist-submit'       => 'Gagai',
'blocklink'                => 'blukir',
'unblocklink'              => 'hilangakan blukir',
'change-blocklink'         => 'ubah blukir',
'contribslink'             => 'sumbangan',
'blocklogpage'             => 'Log blukir',
'blocklogentry'            => 'diblukir [[$1]] sampai wayah $2 $3',
'unblocklogentry'          => 'Mahilangakan blukir "$1"',
'block-log-flags-nocreate' => 'Paulahan akun dipajahkan',

# Move page
'move-page-legend' => 'Pindahakan tungkaran',
'movepagetext'     => "Mamuruk purmulir di bawah akan mangganti ngaran sabuah tungkaran, mamindahakan samunyaan halam ka ngaran nang hanyar. Judul lawas akan jadi sabuah tungkaran paugahan ka judul hanyar. Pian kawa ma-update bahwasanya paugahan-paugahan manuju ka judul nang samustinya langsung. Amun kada, pastiakan cuntring gasan  [[Special:DoubleRedirects|ganda]] atawa [[Special:BrokenRedirects|paugahan pagat]]. Pian batanggung jawab gasan mamastiakan tautan-tautan tatarusan manuju ka mana nang samustinya.

Catatan bahwasanya tungkaran '''kada''' akan tapindah amun sudah ada tungkaran nang bangaran hanyar itu, kacuali amun tungkaran itu kusung atau sabuah paugahan wan kadada halam babakan.

'''Paringatan!'''
Ini kawa maakibatakan parubahan kada taduga wan drastis gasan sabuah tungkaran rami; muhun mamastiakan Pian paham akibatnya sabalum manarusakan.",
'movepagetalktext' => "Tungkaran pamandiran tarait akan langsung dipindahakan baimbai wan ini '''kacuali amun:'''
*Sabuah tungkaran pamandiran nang kada kusung sudah baisi awan judul hanyar, atawa
*Pian kada manyuntring kutak di bawah.",
'movearticle'      => 'Pindahakan tungkaran:',
'newtitle'         => 'Ka judul hanyar:',
'move-watch'       => 'Itihi tungkaran asal mula wan tungkaran tujuan',
'movepagebtn'      => 'Pindahakan tungkaran',
'pagemovedsub'     => 'Pamindahan tajadi',
'movepage-moved'   => '\'\'\'"$1" sudah dipindahakan ka "$2"\'\'\'',
'articleexists'    => 'Tungkaran lawan ngaran itu sudah ada atawa ngaran nang dipilih kada sah. Silakan pilih ngaran lain.',
'talkexists'       => "'''Tungkaran tasabut sudah tajadi dipindahakan, tapi tungkaran pamandirannya kada kawa tapindah karana sudah ada tungkaran pamandiran bajudul hanyar. Muhun gabungakan manual haja tungkaran-tungkaran tasabut.'''",
'movedto'          => 'dipindahakan ka',
'movetalk'         => 'Pindahakan tungkaran pamandiran nang tarait',
'1movedto2'        => 'dipindahakan [[$1]] ka [[$2]]',
'1movedto2_redir'  => 'mamindahakan [[$1]] ka [[$2]] malalui paugahan',
'movelogpage'      => 'Log pamindahan',
'movereason'       => 'Alasan:',
'revertmove'       => 'bulikakan',

# Export
'export'        => 'Kirimi tungkaran ka luar',
'export-submit' => 'Pangaluar',

# Namespace 8 related
'allmessages-language' => 'Bahasa:',

# Thumbnails
'thumbnail-more' => 'Ganali',

# Special:Import
'import'                 => 'Pamasuk tungkaran',
'import-upload-filename' => 'Ngaran barakas:',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Tungkaran pamuruk Pian',
'tooltip-pt-mytalk'               => 'Tungkaran pamandiran Pian',
'tooltip-pt-preferences'          => 'Nang Pian katuju',
'tooltip-pt-watchlist'            => 'Daptar tungkaran-tungkaran nang Pian itihi parubahannya',
'tooltip-pt-mycontris'            => 'Daptar sumbangan Pian',
'tooltip-pt-login'                => 'Pian sabaiknya babuat ka dalam log; tagal ini kada kawajiban pang',
'tooltip-pt-logout'               => 'Kaluar',
'tooltip-ca-talk'                 => 'Pamandiran pasal isi tungkaran',
'tooltip-ca-edit'                 => 'Pian kawa mambabak tungkaran ini. Sabaiknya puruk picikan titilikan sabalum manyimpan',
'tooltip-ca-addsection'           => 'Mula sabuah hapat hanyar',
'tooltip-ca-viewsource'           => 'Tungkaran ini dilindungi. Pian kawa maniring asal mulanya.',
'tooltip-ca-history'              => 'Ralatan-ralatan bahari tungkaran ini',
'tooltip-ca-protect'              => 'Lindungi tungkaran ini',
'tooltip-ca-delete'               => 'Hapus tungkaran ini',
'tooltip-ca-move'                 => 'Pindahakan tungkaran ini',
'tooltip-ca-watch'                => 'Tambahi tungkaran ini ka daptar itihan Pian',
'tooltip-ca-unwatch'              => 'Buang tungkaran ini matan daptar itihan Pian',
'tooltip-search'                  => 'Gagai {{SITENAME}}',
'tooltip-search-go'               => 'Tulak ka sabuah tungkaran bangaran sama munnya sudah ada',
'tooltip-search-fulltext'         => 'Gagai tungkaran nang baisi kata nangkaya ini',
'tooltip-n-mainpage'              => 'Ilangi tungkaran tatambaian',
'tooltip-n-mainpage-description'  => 'Ilangi Tungkaran Tatambaian',
'tooltip-n-portal'                => 'Pasal rangka gawian, apa nang kawa pian gawi, di mana maugai sasuatu',
'tooltip-n-currentevents'         => 'Gagai panjalasan prihal paristiwa wayahini',
'tooltip-n-recentchanges'         => 'Daptar parubahan tahanyar dalam wiki',
'tooltip-n-randompage'            => 'Tampaiakan sabuah babarang tungkaran',
'tooltip-n-help'                  => 'Wadah maugai pangganian',
'tooltip-t-whatlinkshere'         => 'Daptar samunyaan tungkaran wiki nang ada tautan ka sia',
'tooltip-t-recentchangeslinked'   => 'Parubahan tahanyar dalam tungkaran-tungkaran tataut matan tungkaran ini',
'tooltip-feed-rss'                => 'Kitihan RSS gasan tungkaran ini',
'tooltip-feed-atom'               => 'Kitihan Atum gasan tungkaran ini',
'tooltip-t-contributions'         => 'Tampaiakan daptar sumbangan pamuruk ini',
'tooltip-t-emailuser'             => 'Kirimi surel ka pamuruk ini',
'tooltip-t-upload'                => 'Buati gambar atawa barakas média',
'tooltip-t-specialpages'          => 'Daptar samunyaan tungkaran istimiwa',
'tooltip-t-print'                 => 'Nang kawa dicitaknya tungkaran ini',
'tooltip-t-permalink'             => 'Tautan tatap gasan ralatan tungkaran ini',
'tooltip-ca-nstab-main'           => 'Tiringi tungkaran tulisan',
'tooltip-ca-nstab-user'           => 'Tiring tungkaran pamuruk',
'tooltip-ca-nstab-special'        => 'Nangini sabuah tungkaran istimiwa nang kada kawa dibabak.',
'tooltip-ca-nstab-project'        => 'Tiring tungkaran rangka gawian',
'tooltip-ca-nstab-image'          => 'Tiringi barakas tungkaran',
'tooltip-ca-nstab-template'       => 'Tiring citakan',
'tooltip-ca-nstab-category'       => 'Lihati tungkaran kataguri',
'tooltip-minoredit'               => 'Tandai ini sabagai sabuah pambabakan sapalih',
'tooltip-save'                    => 'Simpan parubahan Pian',
'tooltip-preview'                 => 'Tilik parubahan Pian, muhun puruk ini sabalum manyimpan!',
'tooltip-diff'                    => 'Tampaiakan nang apa parubahan nang Pian ulah',
'tooltip-compareselectedversions' => 'Lihati nang balain antara dua ralatan tungkaran tapilih ini',
'tooltip-watch'                   => 'Tambahakan tungkaran ini ka daptar itihan Pian',
'tooltip-upload'                  => 'Mulai pangunggahan',
'tooltip-rollback'                => 'Bulikakan ka babakan-babakan tungkaran ini matan panyumbang tarakhir dalam asa klik.',
'tooltip-undo'                    => "''Undo'' mambulikakan babakan wan mambuka purmulir babakan batitilikan. Ini maijinakan gasan manambahi sabuah alasan dalam kasimpulan.",

# Attribution
'others' => 'lainnya',

# Browsing diffs
'previousdiff' => 'Ralatan talawas',
'nextdiff'     => 'Babakan tahanyar→',

# Media information
'file-info-size'       => '($1 × $2 piksel, ukuran barakas: $3, tipe MIME: $4)',
'file-nohires'         => '<small> kadada tasadia resolusi tapancau.</small>',
'svg-long-desc'        => '(Barakas SVG, nominal $1 × $2 piksel, basar barakas: $3)',
'show-big-image'       => 'Ukuran hibak',
'show-big-image-thumb' => '<small>Ukurannya tilikan ini: $1 × $2 piksel</small>',

# Special:NewFiles
'ilsubmit' => 'Gagai',

# Bad image list
'bad_image_list' => 'Purmatnya nangkaya di bawah ni:

Daptar buting(baris bamula wan *) haja nang dipartimbangkan.
Tautan taasa dalam sabuah baris mustinya sabuah tautan ka barakas nang buruk.
Tautan-tautan  abis tu pada baris sama dipartimbangkan sabagai pangacualian, nangkaya tungkaran-tungkaran di mana barakas itu ada.',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Barakas ini mangandung panjalasan tambahan, mungkin ditambahakan ulih kamera atawa paundai nang dipurukakan gasan maulah atawa digitalisasi barakas. Amun barakas ini sudah diubah, parincian nang ada mungkin kada sapanuhnya sasuai lawan barakas nang diubah.',
'metadata-expand'   => 'Tampaiakan tambahan rincian',
'metadata-collapse' => 'Sungkupakan tambahan rincian',
'metadata-fields'   => 'EXIF metadata tadaptar dalam pasan ini akan masuk dalam tungkaran pancitraan wayah tabel metadata tasungkup. Nang lainnya cagaran babaku tasungkup.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-jpeginterchangeformat' => 'Ofset ka JPEG SOI',

# External editor support
'edit-externally'      => 'Babak barakas ini puruk sabuah aplikasi luar',
'edit-externally-help' => '(Lihati [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions] untuk panjalasan labih)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'samunyaan',
'namespacesall' => 'samunyaan',
'monthsall'     => 'samunyaan',

# action=purge
'confirm_purge_button' => 'OK',

# Multipage image navigation
'imgmultigoto' => 'Tulak ka tungkaran $1',

# Table pager
'table_pager_limit_submit' => 'Tulak ka',

# Watchlist editor
'watchlistedit-normal-title' => 'Babak daptar itihan',

# Watchlist editing tools
'watchlisttools-view' => 'Tampaiakan parubahan tarait',
'watchlisttools-edit' => 'Tampaiakan dan babak daptar itihan',
'watchlisttools-raw'  => 'Babak daptar itihan mantah',

# Special:Version
'version-specialpages' => 'Tungkaran istimiwa',
'version-other'        => 'Lain-lain',
'version-hook-name'    => 'Ngaran kait',

# Special:FilePath
'filepath'        => 'Wadah barakas',
'filepath-page'   => 'Barakas:',
'filepath-submit' => 'Gagai',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'Gagai',

# Special:SpecialPages
'specialpages' => 'Tungkaran istimiwa',

# Special:Tags
'tags-edit' => 'babak',

# Special:ComparePages
'compare-page1' => 'Tungkaran 1',
'compare-page2' => 'Tungkaran 2',
'compare-rev1'  => 'Ralatan 1',
'compare-rev2'  => 'Ralatan 2',

# HTML forms
'htmlform-selectorother-other' => 'Lain-lain',

);
