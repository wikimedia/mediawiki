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
 * @author Meno25
 * @author Si Gam Acèh
 */

$fallback = 'id';

$messages = array(
# User preference toggles
'tog-watchlisthideown'   => 'Peusöm nyang lôn andam nibak dapeuta keunalön',
'tog-watchlisthidebots'  => 'Peusöm nyang teu andam nibak sagoö nyang bak dapeuta keunalön',
'tog-watchlisthideminor' => 'Peusöm Andam Bacut bak dapeuta keunalön',
'tog-watchlisthideliu'   => 'Peusöm andam nyang nguy nyang tamöng nibak dapeuta keunalön',
'tog-watchlisthideanons' => 'Peusöm andam nyang nguy hana taturi nibak dapeuta keunalön',

# Dates
'sun'           => 'Aleu',
'mon'           => 'Seun',
'tue'           => 'Seul',
'wed'           => 'Rab',
'thu'           => 'Ham',
'fri'           => 'Jum',
'sat'           => 'Sab',
'january'       => 'Buleuën Sa',
'february'      => 'Buleuën Duwa',
'march'         => 'Buleuën Lhèë',
'april'         => 'Buleuën Peuët',
'may_long'      => 'Buleuën Limong',
'june'          => 'Buleuën Nam',
'july'          => 'Buleuën Tujôh',
'august'        => 'Buleuën Lapan',
'september'     => 'Buleuën Sikureuëng',
'october'       => 'Buleuën Siplôh',
'november'      => 'Buleuën Siblah',
'december'      => 'Buleuën Duwa Blah',
'january-gen'   => 'Buleuën Sa',
'february-gen'  => 'Buleuën Duwa',
'march-gen'     => 'Buleuën Lhèë',
'april-gen'     => 'Buleuën Peuët',
'may-gen'       => 'Buleuën Limong',
'june-gen'      => 'Buleuën Nam',
'july-gen'      => 'Buleuën Tujôh',
'august-gen'    => 'Buleuën Lapan',
'september-gen' => 'Buleuën Sikureuëng',
'october-gen'   => 'Buleuën Siplôh',
'november-gen'  => 'Buleuën Siblah',
'december-gen'  => 'Buleuën Duwa Blah',
'jan'           => 'Sa',
'feb'           => 'Duwa',
'mar'           => 'Lhèë',
'apr'           => 'Peuët',
'may'           => 'Lim',
'jun'           => 'Nam',
'jul'           => 'Tuj',
'aug'           => 'Lap',
'sep'           => 'Sik',
'oct'           => 'Sip',
'nov'           => 'Sib',
'dec'           => 'Dub',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Kawan|Kawan}}',
'category_header'        => 'Teunuléh lam kawan "$1"',
'subcategories'          => 'Subkategori',
'category-media-header'  => 'Alat lam kawan "$1"',
'category-empty'         => "''Kawan nyoë jinoë hat hana teunuléh atawa media.''",
'hidden-categories'      => '{{PLURAL:$1|Kawan teusom|Kawan teusom}}',
'category-subcat-count'  => '{{PLURAL:$2|Kawan nyoë  cit na saboh yupkawan nyoë.|Kawan nyoë na {{PLURAL:$1|yupkawan|$1 yupkawan}} nyoë, dari ban dum $2.}}',
'category-article-count' => '{{PLURAL:$2|Kawan nyoë cit na saboh ôn nyoë.|Kawan nyoë na  {{PLURAL:$1|ôn|$1 ôn }}, dari ban dum $2.}}',
'listingcontinuesabbrev' => 'samb.',

'about'      => 'Bhah',
'newwindow'  => '(peuhah bak tingkap barô)',
'cancel'     => 'Peubateuë',
'qbfind'     => 'Mita',
'qbedit'     => 'Andam',
'mytalk'     => 'Peugah haba lôn',
'navigation' => 'Navigasi',

'errorpagetitle'   => 'Seunalah',
'returnto'         => 'Gisa u $1.',
'tagline'          => 'Nibak {{SITENAME}}',
'help'             => 'Beunantu',
'search'           => 'Mita',
'searchbutton'     => 'Mita',
'searcharticle'    => 'Jak u',
'history'          => 'Riwayat barosa',
'history_short'    => 'Riwayat away',
'printableversion' => 'Seunalén citak',
'permalink'        => 'Hubông teutap',
'edit'             => 'Andam',
'create'           => 'Peugöt',
'editthispage'     => 'Andam ôn nyoë',
'delete'           => 'Sampôh',
'protect'          => 'Peulindông',
'protect_change'   => 'ubah',
'newpage'          => 'Ôn barô',
'talkpage'         => 'Peugah haba bhah ôn nyoë',
'talkpagelinktext' => 'Peugah haba',
'personaltools'    => 'Alat droë',
'talk'             => 'Peugah haba',
'views'            => 'Leumah',
'toolbox'          => 'Plôk alat',
'otherlanguages'   => 'Bahsa la’én',
'redirectedfrom'   => '(Geupeupinah nibak $1)',
'redirectpagesub'  => 'Ôn peupinah',
'lastmodifiedat'   => 'Ôn nyoë keuneulheuëh geu’ubah bak $2, $1.', # $1 date, $2 time
'jumpto'           => 'Langsông u:',
'jumptonavigation' => 'navigasi',
'jumptosearch'     => 'mita',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Bhah {{SITENAME}}',
'aboutpage'            => 'Project:Bhah Ôn',
'copyright'            => 'Asoë nyang na seusuai ngön $1.',
'copyrightpage'        => '{{ns:project}}:Hak karang',
'currentevents'        => 'Peristiwa paléng barô',
'currentevents-url'    => 'Project:Peristiwa paléng barô',
'disclaimers'          => 'Beunantah',
'disclaimerpage'       => 'Project:Beunantah umôm',
'edithelp'             => 'Bantu andam',
'edithelppage'         => 'Help:Andam',
'helppage'             => 'Help:Asoë',
'mainpage'             => 'Ôn Keuë',
'mainpage-description' => 'Ôn Keuë',
'portal'               => 'Portal Komunitas',
'portal-url'           => 'Project:Portal komunitas',
'privacy'              => 'Jaga rahsia',
'privacypage'          => 'Project:Jaga rahsia',

'badaccess' => 'Salah hak tamong',

'retrievedfrom'       => 'Meurumpok nibak "$1"',
'youhavenewmessages'  => 'Droëneuh   na $1 ($2).',
'newmessageslink'     => 'peusan barô',
'newmessagesdifflink' => 'neu’ubah keuneulheuëh',
'editsection'         => 'andam',
'editold'             => 'andam',
'editlink'            => 'andam',
'viewsourcelink'      => 'eu nè',
'editsectionhint'     => 'Andam bideuëng: $1',
'toc'                 => 'Asoë',
'showtoc'             => 'peuleumah',
'hidetoc'             => 'peusom',
'site-rss-feed'       => 'Eumpeuën RSS $1',
'site-atom-feed'      => 'Eumpeuën Atôm $1',
'page-rss-feed'       => 'Eumpeuën RSS "$1"',
'page-atom-feed'      => 'Umpeuën Atom "$1"',
'red-link-title'      => '$1 (ôn goh na)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Ôn',
'nstab-user'     => 'Nyang nguy',
'nstab-special'  => 'Husôh',
'nstab-project'  => 'Proyèk ôn',
'nstab-image'    => 'Beureukah',
'nstab-template' => 'Templat',
'nstab-category' => 'Kawan',

# General errors
'missing-article'    => 'Basis data h’an jeuët jiteumèë naseukah nibak ôn nyang sipatôtjih na, nakeuh "$1" $2.

Nyoë biasajih sabab hubông useuëng u geunantoë away nyang ka teusampôh.

Meunyo kön nyoë sababjih, Droëneuh kadang ka neuteumèë saboh bug lam software. Neutulông peugah bhah nyoë bak salah sidroë [[Special:ListUsers/sysop|Nyang urôh]], ngön neupeugah alamat URL nyang neusaweuë.',
'missingarticle-rev' => '(revisi#: $1)',
'badtitle'           => 'Nan hana sah',
'badtitletext'       => 'Nan ôn nyang neulakèë hana sah, soh, atawa nan antarabahsa atawa antarawiki nyang salah sambông.',
'viewsource'         => 'Eu nè',
'viewsourcefor'      => 'keu $1',
'viewsourcetext'     => 'Droëneuh  jeuët neu’eu',

# Login and logout pages
'yourname'                => 'Nan nyang nguy:',
'yourpassword'            => 'Lageuëm rahsia:',
'remembermypassword'      => 'Ingat lageuëm rahsia lôn bak komputer nyoë',
'login'                   => 'Tamong',
'nav-login-createaccount' => 'Tamong / dapeuta',
'loginprompt'             => "Droëneuh payah neupeu’udép ''cookies'' beujeuët neutamong u {{SITENAME}}",
'userlogin'               => 'Tamong / dapeuta',
'logout'                  => 'Teubiët',
'userlogout'              => 'Teubiët',
'nologin'                 => 'Goh na nan nyang nguy? $1.',
'nologinlink'             => 'Peudapeuta nan barô',
'createaccount'           => 'Peudapeuta nan barô',
'gotaccount'              => 'Ka lheuëh neudapeuta? $1.',
'gotaccountlink'          => 'Tamong',
'yourrealname'            => 'Nan aseuli:',
'prefs-help-realname'     => '* Nan aseuli hana meucéh neupasoë.
Meunyo neupasoë, euntreuk nan Droëneuh nyan geupeuleumah mangat jitupeuë soë nyang tuléh.',
'loginsuccesstitle'       => 'Meuhasé tamong',
'loginsuccess'            => "'''Droëneuh  jinoë ka neutamong di {{SITENAME}} sibagoë \"\$1\".'''",
'nosuchuser'              => 'Hana nyang nguy ngön nan "$1". 
Tulông neupréksa keulayi neu’ija Droëneuh, atawa neudapeuta barô.',
'nosuchusershort'         => 'Hana nyang nguy ngön nan "<nowiki>$1</nowiki>". 
Préksa keulayi neu’ija Droëneuh.',
'nouserspecified'         => 'Neupasoë nan Droëneuh.',
'wrongpassword'           => 'Lageuëm rahsia nyang neupasoë salah. Neubaci lom.',
'wrongpasswordempty'      => 'Droëneuh hana neupasoë lageuëm rahsia. Neubaci lom.',
'passwordtooshort'        => 'Lageuëm rahsia Droëneuh hana sah atawa paneuk that. 
Lageuëm rahsia paléng kureung {{PLURAL:$1|1 karakter|$1 karakter}} ngön beubida ngön nan Droëneuh.',
'mailmypassword'          => 'Kirém lageuëm rahsia barô',
'passwordremindertitle'   => 'Lageuëm rahsia seumeuntara barô keu {{SITENAME}}',
'passwordremindertext'    => 'Salah sidroë (kadang Droëneuh, ngön alamat IP $1) geulakèë kamoë keu meukirém lageuëm rahsia nyang barô keu {{SITENAME}} ($4). 
Lageuëm rahsia keu nyang nguy "$2" jinoë nakeuh "$3". 
Droëneuh geupeusaran keu neutamong sigra, lheuëh nyan neugantoë lageuëm rahsia.',
'noemail'                 => 'Hana alamat surat-e nyang teucatat keu nyang nguy "$1".',
'passwordsent'            => 'Lageuëm rahsia barô ka geukirém u surat-e nyang geupeudapeuta keu "$1". Neutamong teuma lheuëh neuteurimong surat-e nyan.',
'eauthentsent'            => 'Saboh surat èlèktronik keu peunyoë ka geukirém u alamat surat èlèktronik Droëneuh. Droëneuh beuneuseutöt préntah lam surat nyan keu neupeunyoë meunyo alamat nyan nakeuh beutôy atra Droëneuh. {{SITENAME}} h‘an geupeuudép surat Droëneuh meunyo langkah nyoë hana neupeulaku lom.',

# Password reset dialog
'retypenew' => 'Pasoë keulayi lageuëm rahsia barô:',

# Edit page toolbar
'bold_sample'     => 'Citak teubay naseukah nyoë',
'bold_tip'        => 'Citak teubay',
'italic_sample'   => 'Citak singèt naseukah nyoë',
'italic_tip'      => 'Citak singèt',
'link_sample'     => 'Nan hubông',
'link_tip'        => 'Hubông dalam',
'extlink_sample'  => 'http://www.example.com nan hubông',
'extlink_tip'     => 'Hubông luwa (bèk tuwoë bôh http:// bak away)',
'headline_sample' => 'Naseukah nan',
'headline_tip'    => 'Subbagian tingkat 1',
'math_sample'     => 'Pasoë rumuh nyoë pat',
'math_tip'        => 'Rumuh matematik (LaTeX)',
'nowiki_sample'   => 'Bèk format naseukah nyoë',
'nowiki_tip'      => 'Bèk seutot beuntuk wiki',
'image_tip'       => 'Pasoë beureukah',
'media_tip'       => 'Hubông beureukah alat',
'sig_tip'         => 'Tanda jaroë Droëneuh  ngön tanda watèë',
'hr_tip'          => 'Garéh data',

# Edit pages
'summary'                          => 'Reuningkah:',
'subject'                          => 'Bhah/nan:',
'minoredit'                        => 'Nyoë lôn andam bacut',
'watchthis'                        => 'Kalön ôn nyoë',
'savearticle'                      => 'Keubah ôn',
'preview'                          => 'Eu dilèë',
'showpreview'                      => 'Peuleumah hasé',
'showdiff'                         => 'Peuleumah neu’ubah',
'anoneditwarning'                  => 'Droëneuh   hana teudapeuta tamong. Alamat IP Droëneuh   teucatat lam tarèh (riwayat away) ôn nyoë.',
'summary-preview'                  => 'Eu dilèë reuningkah:',
'blockedtext'                      => "<big>'''Nan nyang nguy atawa alamat IP Droëneuh  ka geutheun.'''</big> 

Geutheun lé $1. Dalèh jih nakeuh ''$2''. 

* Geutheun yôh: $8 
* Neutheun maté tanggay bak: $6 
* Nyang geutheun: $7 

Droëneuh   jeuët neutanyong bak $1 atawa [[{{MediaWiki:Grouppage-sysop}}|nyang urôh nyang la’én]] keu peugah haba bhah nyoë.

Droëneuh   h’an jeuët neunguy alat 'Kirém surat-e nyang nguy nyoë' keucuali ka neupasoë alamat surat-e nyang sah di [[Special:Preferences|Geunalak]] Droëneuh ngön Droëneuh ka geutheun keu nguy nyan.

Alamat IP Droëneuh nakeuh $3, ngön ID neutheun nakeuh $5. Tulông peuseureuta salah saboh atawa ban duwa beurita nyoë bak tiëp teunanyöng nyang neupeugöt.",
'newarticle'                       => '(Barô)',
'newarticletext'                   => "Droëneuh   ka neuseutot u ôn nyang goh na. Keu peugöt ôn nyan, neukeutik asoë ôn di  kutak di yup nyoë (ngiëng [[{{MediaWiki:Helppage}}|ôn bantu]] keu beurita leubèh lanjut). Meunyo Droëneuh  hana neusaja ka trôk keunoë, teugon '''back''' nyang na bak layeuë.",
'noarticletext'                    => 'Hana naseukah jinoë lam ôn nyoë. Ji Droëneuh jeuët [[Special:Search/{{PAGENAME}}|neumita keu nan ôn nyoë]] bak ôn-ôn la’én atawa [{{fullurl:{{FULLPAGENAME}}|action=edit}} andam ôn nyoë].',
'updated'                          => '(Seubarô)',
'note'                             => "'''Ceunatat:'''",
'previewnote'                      => "'''Beuneuingat meunyo nyoë goh lom neukeubah!'''",
'editing'                          => 'Andam $1',
'editingsection'                   => 'Andam $1 (bideuëng)',
'copyrightwarning'                 => "Beuneuingat bahwa ban mandum nyang Droëneuh   tuléh keu {{SITENAME}} geukira geupeuteubiët di yup $2 (ngiëng $1 keu leubèh jeulah). Meunyoë Droëneuh h‘an neutém teunuléh Droëneuh  ji’andam ngön jiba ho ho la’én, bèk neupasoë teunuléh Droëneuh  keunoë.<br />Droëneuh  neumeujanji chit meunyoë teunuléh nyoë nakeuh atra neutuléh keudroë, atawa neucok nibak nè nè atra umôm atawa nè bibeuëh la’én.
'''BÈK NEUPASOË TEUNULÉH NYANG GEUPEULINDÔNG HAK KARANG NYANG HANA IDIN'''",
'longpagewarning'                  => "'''INGAT: Ôn nyoë panyangjih nakeuh $1 kilobit; ladôm alat rawoh web kadang na masalah bak ji’andam ôn nyang panyangjih 32 kb atawa leubèh. Beu neupeutimang keu neuplah jeuët padum boh beunagi nyang leubèh cut. '''",
'templatesused'                    => 'Templat nyang geunguy bak ôn nyoë:',
'templatesusedpreview'             => 'Templat nyang geunguy bak eu dilèë nyoë',
'template-protected'               => '(geulindông)',
'template-semiprotected'           => '(seumi-lindông)',
'hiddencategories'                 => 'Ôn nyoë nakeuh anggèëta nibak {{PLURAL:$1|1 kawan teusom |$1 kawan teusom}}:',
'nocreatetext'                     => '{{SITENAME}} ka jikot bak peugöt ôn barô. Ji Droëneuh   jeuët neuriwang teuma ngön neu’andam ôn nyang ka na, atawa [[Special:UserLogin|neutamong atawa neudapeuta]].',
'permissionserrorstext-withaction' => 'Droëneuh hana hak tamöng keu $2, muroë {{PLURAL:$1|choë|choë}} nyoë:',
'recreate-deleted-warn'            => "'''Ingat: Droëneuh  teungoh neupeugöt ulang saboh ôn nyang ka tom geusampôh. ''',

Neutimang-timang dilèë peuë ék patôt neupeulanjut atra nyang teungoh neu’andam.
Nyoë pat nakeuh log seunampôh nibak ôn nyoë:",

# History pages
'viewpagelogs'           => 'Eu log ôn nyoë',
'currentrev'             => 'Geunantoë jinoë',
'currentrev-asof'        => 'Geunantoë paléng barô bak $1',
'revisionasof'           => 'Gantoë tiëp $1',
'revision-info'          => 'Geunantoë tiëp $1; $2', # Additionally available: $3: revision id
'previousrevision'       => '←Geunantoë sigohlomjih',
'nextrevision'           => 'Geunantoë lheuëh nyan→',
'currentrevisionlink'    => 'Geunantoë jinoë',
'cur'                    => 'jin',
'last'                   => 'akhé',
'page_first'             => 'phôn',
'page_last'              => 'keuneulheuëh',
'histlegend'             => "Piléh duwa teuneugön radiô, lheuëh nyan teugön teuneugön ''peubandéng'' keu peubandéng seunalén. Teugön saboh tanggay keu eu seunalén ôn bak tanggay nyan.<br />(skr) = bida ngön seunalén jinoë, (akhé) = bida ngön seunalén sigohlomjih. '''b''' = andam bacut, '''b''' = andam bot, → = andam bideuëng, ← = reuningkah keudroë",
'history-fieldset-title' => 'Jeulajah riwayat away',
'histfirst'              => 'Paléng trép',
'histlast'               => 'Paléng barô',

# Revision feed
'history-feed-item-nocomment' => '$1 bak $2', # user at time

# Revision deletion
'rev-delundel'   => 'peuleumah/peusom',
'revdel-restore' => 'Ubah leumah',

# Merge log
'revertmerge' => 'Hana  jadèh peugabông',

# Diffs
'history-title'           => 'Riwayat geunantoë nibak "$1"',
'difference'              => '(Bida antara geunantoë)',
'lineno'                  => 'Baréh $1:',
'compareselectedversions' => 'Peubandéng curak teupiléh',
'editundo'                => 'peubateuë',
'diff-multi'              => '({{PLURAL:$1|Sa|$1}} geunantoë antara hana geupeuleumah.)',

# Search results
'searchresults'             => 'Hasé mita',
'searchresults-title'       => 'Hasé mita keu "$1"',
'searchresulttext'          => 'Keu beurita leubèh le bhah meunita bak {{SITENAME}}, eu [[{{MediaWiki:Helppage}}|ôn beunantu]].',
'searchsubtitle'            => 'Droëneuh neumita \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|ban dum ôn nyang geupuphôn ngön "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|bandum ôn nyang teuhubông u "$1"]])',
'searchsubtitleinvalid'     => "Droëneuh neumita '''$1'''",
'noexactmatch'              => "'''Hana ôn nyang nanjih ''$1''. ''' Droëneuh   jeuët [[:$1|peugèt ôn nyoë]].",
'noexactmatch-nocreate'     => "'''Hana ôn ngön nan \"\$1\".'''",
'notitlematches'            => 'Hana nan ôn nyang pah',
'notextmatches'             => 'Hana naseukah ôn nyang pah',
'prevn'                     => '$1 sigohlomjih',
'nextn'                     => '$1 lheuëh nyan',
'viewprevnext'              => 'Eu ($1)($2)($3)',
'searchhelp-url'            => 'Help:Asoë',
'search-result-size'        => '$1 ({{PLURAL:$2|1 kata|$2 kata}})',
'search-redirect'           => '(peuninah $1)',
'search-section'            => '(bagian $1)',
'search-suggest'            => 'Kadang meukeusud Droëneuh nakeuh: $1',
'search-interwiki-caption'  => 'Buët la’én',
'search-interwiki-default'  => 'Hasé $1:',
'search-interwiki-more'     => '(lom)',
'search-mwsuggest-enabled'  => 'ngon saran',
'search-mwsuggest-disabled' => 'hana saran',
'showingresultstotal'       => "Hasé mita {{PLURAL:$4|'''$1'''|'''$1 - $2'''}} dari '''$3'''",
'nonefound'                 => "'''Ceunatat''': Cit ladôm ruweuëng nyang seucara baku geupeutamöng lam meunita. Ci neupuphôn leunakèë Droëneuh ngön ''all:'' keu mita ban dum asoë (rôh cit ôn peugah haba, tèmplat, ngön nyang la’én (nnl)), atawa neunguy ruweuëng nan nyang neumeuh’eut sibagoë neu’away.",
'powersearch'               => 'Mita lanjut',
'powersearch-legend'        => 'Mita lanjôt',
'powersearch-ns'            => 'Mita bak ruweuëng nan:',
'powersearch-redir'         => 'Dapeuta peuninah',
'powersearch-field'         => 'Mita',

# Preferences page
'preferences'   => 'Geunalak',
'mypreferences' => 'Geunalak lôn',

# Groups
'group-sysop' => 'Nyang urôh',

'grouppage-sysop' => '{{ns:project}}:Nyang urôh',

# User rights log
'rightslog' => 'Log neu’ubah hak peuhah',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'andam ôn nyoë',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|neu’ubah|neu’ubah}}',
'recentchanges'                  => 'Neu’ubah paléng barô',
'recentchanges-legend'           => 'Peuniléh neu’ubah paléng barô',
'recentchanges-feed-description' => 'Peutumèë neu’ubah paléng barô lam wiki bak eumpeuën nyoë.',
'rcnote'                         => "Di yup nyoë nakeuh {{PLURAL:$1|nakeuh '''1''' neu’ubah paléng barô |nakeuh '''$1''' neu’ubah paléng barô}} lam {{PLURAL:$2|'''1''' uroë|'''$2''' uroë}} nyoë, trôk ‘an $5, $4.",
'rcnotefrom'                     => 'Di yup nyoë nakeuh neu’ubah yôh <strong>$2</strong> (geupeuleumah trôh ‘an <strong>$1</strong> neu’ubah).',
'rclistfrom'                     => 'Peuleumah neu’ubah paléng barô yôh $1 kön',
'rcshowhideminor'                => '$1 andam bacut',
'rcshowhidebots'                 => '$1 bot',
'rcshowhideliu'                  => '$1 nyang nguy tamong',
'rcshowhideanons'                => '$1 nyang nguy hana nan',
'rcshowhidepatr'                 => '$1 andam teurunda',
'rcshowhidemine'                 => '$1 atra lôn andam',
'rclinks'                        => 'Peuleumah $1 neu’ubah paléng barô lam $2 uroë nyoë<br />$3',
'diff'                           => 'bida',
'hist'                           => 'riwayat',
'hide'                           => 'Peusom',
'show'                           => 'Peuleumah',
'minoreditletter'                => 'b',
'newpageletter'                  => 'B',
'boteditletter'                  => 'b',
'rc-enhanced-expand'             => 'Peuleumah reuninci (peureulèë JavaScript)',
'rc-enhanced-hide'               => 'Peusom reuninci',

# Recent changes linked
'recentchangeslinked'          => 'Seundi meuhubông',
'recentchangeslinked-title'    => 'Neu’ubah nyang meuhubông ngön $1',
'recentchangeslinked-noresult' => 'Hana neu’ubah bak ôn-ôn meuhubông silawét masa nyang ka geupeuteuntèë.',
'recentchangeslinked-summary'  => "Ôn husôh nyoë geupeuleumah dapeuta neu’ubah keuneulheuëh bak ôn ôn meuhubông. Ôn nyang neukalön geubri tanda ngön '''citak teubay'''.",
'recentchangeslinked-page'     => 'Nan ôn:',
'recentchangeslinked-to'       => 'Peuleumah neu’ubah nibak ôn-ôn nyang meusambông ngön ôn nyang geubri',

# Upload
'upload'        => 'Peutamong',
'uploadbtn'     => 'Peutamong beureukah',
'uploadlogpage' => 'Log peutamong',
'uploadedimage' => 'peutamong "[[$1]]"',

# Special:ListFiles
'listfiles' => 'Dapeuta beureukah',

# File description page
'filehist'                  => 'Riwayat beureukah',
'filehist-help'             => 'Teugon bak tanggay/watèë keu eu beureukah nyoë ‘oh watèë nyan.',
'filehist-current'          => 'jinoë hat',
'filehist-datetime'         => 'Tanggay/Watèë',
'filehist-thumb'            => 'Beuntuk ubeut',
'filehist-thumbtext'        => 'Beuntuk ubeut keu seunalén tiëp $1',
'filehist-user'             => 'Nyang nguy',
'filehist-dimensions'       => 'Dimènsi',
'filehist-filesize'         => 'Rayek beureukah',
'filehist-comment'          => 'Tapeusé',
'imagelinks'                => 'Hubông beureukah',
'linkstoimage'              => 'Ôn di yup nyoë na {{PLURAL:$1|hubông|$1 hubông}} u beureukah nyoë:',
'nolinkstoimage'            => 'Hana ôn nyang na hubông u beureukah nyoë.',
'sharedupload'              => 'Beureukah nyoë dari $1 ngön kadang geunguy lé buët-buët la’én.', # $1 is the repo name, $2 is shareduploadwiki(-desc)
'noimage'                   => 'Hana beureukah ngön nan nyan, Droëneuh jeuët $1.',
'noimage-linktext'          => 'peutamong beureukah',
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
'statistics' => 'Statistik',

'disambiguations' => 'Ôn disambiguasi',

'doubleredirects' => 'Peuninah ganda',

'brokenredirects' => 'Peuninah reulöh',

'withoutinterwiki' => 'Ôn tan na hubông bahsa',

'fewestrevisions' => 'Teunuléh ngön neu’ubah paléng dit',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bit|bit}}',
'nlinks'                  => '$1 {{PLURAL:$1|hubông|hubông}}',
'nmembers'                => '$1 {{PLURAL:$1|asoë|asoë}}',
'lonelypages'             => 'Ôn tan hubông balék',
'uncategorizedpages'      => 'Ôn nyang hana rôh lam kawan',
'uncategorizedcategories' => 'Kawan nyang hana rôh lam kawan',
'uncategorizedimages'     => 'Beureukah nyang hana rôh lam kawan',
'uncategorizedtemplates'  => 'Templat nyang hana rôh lam kawan',
'unusedcategories'        => 'Kawan nyang hana geunguy',
'unusedimages'            => 'Beureukah nyang hana geunguy',
'wantedcategories'        => 'Kawan nyang geuhawa',
'wantedpages'             => 'Ôn nyang geuh‘eut',
'mostlinked'              => 'Ôn nyang paléng kayém geusaweuë',
'mostlinkedcategories'    => 'Kawan nyang paléng kayém geunguy',
'mostlinkedtemplates'     => 'Templat nyang paléng kayém geunguy',
'mostcategories'          => 'Teunuléh ngön kawan paléng le',
'mostimages'              => 'Beureukah nyang paléng kayém geunguy',
'mostrevisions'           => 'Teunuléh ngön neu’ubah paléng le',
'prefixindex'             => 'Ban dum ôn ngön neuaway',
'shortpages'              => 'Ôn paneuk',
'longpages'               => 'Ôn panyang',
'deadendpages'            => 'Ôn buntu',
'protectedpages'          => 'Ôn nyang geulindông',
'listusers'               => 'Dapeuta nyang nguy',
'newpages'                => 'Ôn barô',
'ancientpages'            => 'Teunuléh away',
'move'                    => 'Peupinah',
'movethispage'            => 'Peupinah ôn nyoë',
'pager-newer-n'           => '{{PLURAL:$1|1 leubèh barô |$1 leubèh barô}}',
'pager-older-n'           => '{{PLURAL:$1|1 leubèh trép|$1 leubèh trép}}',

# Book sources
'booksources'               => 'Nè kitab',
'booksources-search-legend' => 'Mita bak sumber buku',
'booksources-go'            => 'Mita',

# Special:Log
'specialloguserlabel'  => 'Nyang nguy:',
'speciallogtitlelabel' => 'Nan:',
'log'                  => 'Log',
'all-logs-page'        => 'Ban dum log',

# Special:AllPages
'allpages'       => 'Dapeuta ôn',
'alphaindexline' => '$1 u $2',
'nextpage'       => 'Ôn lheuëh nyan ($1)',
'prevpage'       => 'Ôn sigohlomjih ($1)',
'allpagesfrom'   => 'Peuleumah ôn peuphôn nibak:',
'allpagesto'     => 'Peuleumah ôn geupeuakhé bak:',
'allarticles'    => 'Dapeuta teunuléh',
'allpagessubmit' => 'Mita',
'allpagesprefix' => 'Peuleumah ôn ngön harah phôn:',

# Special:Categories
'categories' => 'Dapeuta kawan',

# Special:LinkSearch
'linksearch' => 'Hubông luwa',

# Special:Log/newusers
'newuserlogpage'          => 'nyang nguy barô',
'newuserlog-create-entry' => 'dapeuta  jeuët anggèëta',

# Special:ListGroupRights
'listgrouprights-members' => '(dapeuta anggèëta)',

# E-mail user
'emailuser' => 'Surat-e nyang nguy',

# Watchlist
'watchlist'         => 'Dapeuta keunalön lôn',
'mywatchlist'       => 'Keunalön lôn',
'watchlistfor'      => "(keu '''$1''')",
'addedwatch'        => 'Ka geupeutamah u dapeuta kalön',
'addedwatchtext'    => "Ôn \"[[:\$1]]\" ka geupeutamah u [[Special:Watchlist|dapeuta keunalön]] Droëneuh. Neu’ubah-neu’ubah bak masa u keuë bak ôn nyan ngön bak ôn peugah habajih, euntreuk leumah nyoë pat. Ôn nyan euntreuk geupeuleumah ''teubay'' bak [[Special:RecentChanges|dapeuta neu’ubah paléng barô]] mangat leubèh mudah leumah.",
'removedwatch'      => 'Ka geusampôh nibak dapeuta keunalön',
'removedwatchtext'  => 'Ôn "<nowiki>$1</nowiki>" ka geusampôh bak dapeuta kalön.',
'watch'             => 'Kalön',
'watchthispage'     => 'Kalön ôn nyoë',
'unwatch'           => 'Bateuë kalön',
'watchlist-details' => '{{PLURAL:$1|$1 ôn|$1 ôn}} geukalön, hana kira ôn peugah haba.',
'wlshowlast'        => 'Peuleumah $1 jeum $2 uroë $3 keuneulheuëh',
'watchlist-options' => 'Peuniléh dapeuta kalön',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Kalön...',
'unwatching' => 'Hana kalön...',

# Delete
'deletepage'            => 'Sampôh ôn',
'historywarning'        => 'Ingat: Ôn nyang hawa neusampôh na riwayat:',
'confirmdeletetext'     => 'Droëneuh neuk neusampôh ôn atawa beureukah nyoë keu sabé. Meunan cit ban mandum riwayatjih nibak basis data. Neupeupaseuti meunyo Droëneuh cit keubiët meung neusampôh, neutupeuë ban mandum akébatjih, ngön peuë nyang neupeulaku nyoë nakeuh meunurôt [[{{MediaWiki:Policy-url}}|kebijakan{{SITENAME}}]].',
'actioncomplete'        => 'Proses seuleusoë',
'deletedtext'           => '"<nowiki>$1</nowiki>" ka geusampôh. Eu $2 keu log paléng barô bak ôn nyang ka geusampôh.',
'deletedarticle'        => 'sampôh "[[$1]]"',
'dellogpage'            => 'Log seunampoh',
'deletecomment'         => 'Choë sampôh',
'deleteotherreason'     => 'Nyang la’én/choë la’én:',
'deletereasonotherlist' => 'Choë la’én',

# Rollback
'rollbacklink' => 'pulang',

# Protect
'protectlogpage'              => 'Log lindông',
'protectedarticle'            => 'peulindông "[[$1]]"',
'modifiedarticleprotection'   => 'Ubah tingkat lindông "[[$1]]"',
'prot_1movedto2'              => 'peupinah [[$1]] u [[$2]]',
'protectcomment'              => 'Bri peunapat:',
'protectexpiry'               => 'Maté tanggay:',
'protect_expiry_invalid'      => 'Watèë maté tanggay hana sah.',
'protect_expiry_old'          => 'Watèë maté tanggay nakeuh bak masa u likôt.',
'protect-unchain'             => 'Peuhah neulindông peupinah',
'protect-text'                => "Droëneuh jeuët neu’eu atawa neugantoë tingkat lindông keu ôn '''<nowiki>$1</nowiki>''' nyoë pat.",
'protect-locked-access'       => "Nan dapeuta Droëneuh hana hak keu jak gantoë tingkat lindông ôn. Nyoë pat nakeuh konfigurasi atra jinoë keu ôn '''$1''':",
'protect-cascadeon'           => 'Ôn nyoë teungöh geulindông kareuna geupeuseureuta lam {{PLURAL:$1|ôn|ôn-ôn}} nyoë nyang ka geulindông ngön peuniléh lindông meuturôt geupeuudép.
Droëneuh jeuët neugantoë tingkat lindông keu ôn nyoë, tapi nyan hana peungarôh keu lindông meuturôt.',
'protect-default'             => 'Peuidin ban dum nyang nguy',
'protect-fallback'            => 'Peureulèë hak peuhah "$1"',
'protect-level-autoconfirmed' => 'Theun nyang nguy barô ngön hana teudapeuta',
'protect-level-sysop'         => 'Nyang urôh mantöng',
'protect-summary-cascade'     => 'riti',
'protect-expiring'            => 'maté tanggay $1 (UTC)',
'protect-cascade'             => 'Peulindông ban mandum ôn nyang rôh lam ôn nyoë (lindông meuturôt).',
'protect-cantedit'            => 'Droëneuh h‘an jeuët neu’ubah tingkat lindông ôn nyoë kareuna Droëneuh hana hak keu neupeulaku nyan.',
'protect-expiry-options'      => '2 jeum:2 hours,1 uroë:1 day,3 uroë:3 days,1 minggu:1 week,2 minggu:2 weeks,1 buleuën:1 month,3 buleuën:3 months,6 buleuën:6 months,1 thôn:1 year,sabé:infinite', # display1:time1,display2:time2,...
'restriction-type'            => 'Lindông:',
'restriction-level'           => 'Tingkat:',

# Undelete
'undeletebtn'      => 'Peuriwang!',
'undeletelink'     => 'eu/peuriwang',
'undeletedarticle' => '"$1" ka geupeuriwang',

# Namespace form on various pages
'namespace'      => 'Ruweuëng nan:',
'invert'         => 'Peubalék peuniléh',
'blanknamespace' => '(Utama)',

# Contributions
'contributions'       => 'Nyang ka jituléh lé nyang nguy',
'contributions-title' => 'Peuneugèt nyang nguy keu $1',
'mycontris'           => 'Nyang lôn peugèt',
'contribsub2'         => 'Keu $1 ($2)',
'uctop'               => '(ateuëh)',
'month'               => 'Yôh buleuën (ngön yôh goh lom nyan)',
'year'                => 'Yôh thôn (ngön yôh goh lom nyan)',

'sp-contributions-newbies'     => 'Keu ureuëng-ureuëng nyang ban nguy mantöng',
'sp-contributions-newbies-sub' => 'Keu nyang nguy barô',
'sp-contributions-blocklog'    => 'Log peutheun',
'sp-contributions-search'      => 'Mita soë nyang tuléh',
'sp-contributions-username'    => 'Alamat IP atawa nan nyang nguy:',
'sp-contributions-submit'      => 'Mita',

# What links here
'whatlinkshere'            => 'Hubông balék',
'whatlinkshere-title'      => 'Ôn nyang na hubông u $1',
'whatlinkshere-page'       => 'Ôn:',
'linkshere'                => "Ôn-ôn nyoë meuhubông u '''[[:$1]]''':",
'nolinkshere'              => "Hana ôn nyang teuhubông u '''[[:$1]]'''.",
'isredirect'               => 'ôn peupinah',
'istemplate'               => 'deungön templat',
'isimage'                  => 'hubông beureukah',
'whatlinkshere-prev'       => '$1 {{PLURAL:$1|sigohlomjih|sigohlomjih}}',
'whatlinkshere-next'       => '$1 {{PLURAL:$1|lheuëh nyan|lheuëh nyan}}',
'whatlinkshere-links'      => '← hubông',
'whatlinkshere-hideredirs' => '$1 peuninah',
'whatlinkshere-hidetrans'  => '$1 transklusi',
'whatlinkshere-hidelinks'  => '$1 hubông',
'whatlinkshere-filters'    => 'Saréng',

# Block/unblock
'blockip'                  => 'Theun nyang nguy',
'ipboptions'               => '2 jeum:2 hours,1 uroë:1 day,3 uroë:3 days,1 minggu:1 week,2 minggu:2 weeks,1 buleuën:1 month,3 buleuën:3 months,6 buleuën:6 months,1 thôn:1 year,sabé:infinite', # display1:time1,display2:time2,...
'ipblocklist'              => 'Dapeuta neutheun',
'blocklink'                => 'theun',
'unblocklink'              => 'peugadöh theun',
'change-blocklink'         => 'ubah theun',
'contribslink'             => 'nyang geupeugèt',
'blocklogpage'             => 'Log peutheun',
'blocklogentry'            => 'theun [[$1]] ngön watèë maté tanggay $2 $3',
'unblocklogentry'          => 'peugadöh theun "$1"',
'block-log-flags-nocreate' => 'pumeugöt nan geupumaté',

# Move page
'movepagetext'     => "Formulir di yup nyoë geunguy keu jak ubah nan saboh ôn ngön jak peupinah ban dum data riwayat u nan barô. Nan nyang trép euntreuk jeuët keu ôn peupinah u nan nyang barô. Hubông u nan trép hana meu’ubah. Neupeupaseuti keu neupréksa peuninah ôn nyang reulöh atawa meuganda lheuëh neupinah. Droëneuh nyang mat tanggông jaweuëb keu neupeupaseuti meunyo hubông laju teusambông u ôn nyang patôt.

Beuneuingat that meunyo ôn '''h’an''' jan geupeupinah meunyo ka na ôn nyang geunguy nan barô, keucuali meunyo ôn nyan soh atawa nakeuh ôn peuninah ngön hana riwayat andam. Nyoë areutijih Droëneuh jeuët neu’ubah nan ôn keulayi lagèë söt meunyo Droëneuh neupeugöt seunalah, ngön Droëneuh h‘an jeuët neutimpa ôn nyang ka na.
'''INGAT'''
Nyoë jeuët geupeuakébat neu’ubah nyang h’an neuduga ngön kreuëh ngön bacah keu ôn nyang meuceuhu. Neupeupaseuti Droëneuh meuphôm akébat nibak buët nyoë sigohlom neulanjut.",
'movepagetalktext' => "Ôn peugah haba nyang na hubôngan euntreuk teupinah keudroë '''keucuali meunyo:'''

*Saboh ôn peugah haba nyang hana soh ka na di yup nan barô, atawa
*Droëneuh hana neubôh tanda cunténg bak kutak di yup nyoë

Lam masalah nyoë, meunyo neuhawa, Droëneuh jeuët neupeupinah atawa neupeugabông ôn keudroë.",
'movearticle'      => 'Peupinah ôn:',
'newtitle'         => 'U nan barô:',
'move-watch'       => 'Kalön ôn nyoë',
'movepagebtn'      => 'Peupinah ôn',
'pagemovedsub'     => 'Peupinah meuhasé',
'movepage-moved'   => '<big>\'\'\'"$1" ka geupeupinah u "$2".\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => 'Ôn ngön nan nyan ka na atawa nan nyang neupiléh hana sah. Neupiléh nan la’én.',
'talkexists'       => 'Ôn nyan ka geupeupinah, tapi ôn peugah haba bak ôn nyan h‘an jeuët geupeupinah kareuna ka na ôn peugah haba bak nan barô. Neupeusapat mantöng ôn ôn peugah haba nyan keudroë.',
'movedto'          => 'geupeupinah u',
'movetalk'         => 'Peupinah ôn peugah haba nyang na hubôngan.',
'1movedto2'        => 'peupinah [[$1]] u [[$2]]',
'1movedto2_redir'  => 'pupinah [[$1]] u [[$2]] röt peuninah',
'movelogpage'      => 'Log pinah',
'movereason'       => 'Choë:',
'revertmove'       => 'peuriwang',

# Export
'export' => 'Èkspor ôn',

# Namespace 8 related
'allmessages' => 'Peusan sistem',

# Thumbnails
'thumbnail-more'  => 'Peurayek',
'thumbnail_error' => 'Salah bak peugöt gamba cut: $1',

# Import log
'importlogpage' => 'Log impor',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ôn nguy Droëneuh',
'tooltip-pt-mytalk'               => 'Ôn peugah haba Droëneuh',
'tooltip-pt-preferences'          => 'Geunalak lôn',
'tooltip-pt-watchlist'            => 'Dapeuta ôn nyang lôn kalön',
'tooltip-pt-mycontris'            => 'Dapeuta peuneugèt Droëneuh',
'tooltip-pt-login'                => 'Droën geupeusaran keu tamong log, bahpih nyan hana geupeuwajéb.',
'tooltip-pt-logout'               => 'Teubiët',
'tooltip-ca-talk'                 => 'Peugah haba ôn asoë',
'tooltip-ca-edit'                 => 'Andam ôn nyoë. Nguy tumbôy euë dilèë yôh goh lom keumeubah.',
'tooltip-ca-addsection'           => 'Puphôn beunagi barô',
'tooltip-ca-viewsource'           => 'Ôn nyoë geupeulindông. 
Droëneuh cuman jeuët neu’eu nèjih.',
'tooltip-ca-history'              => 'Seunalén-seunalén sigohlomjih nibak ôn nyoë',
'tooltip-ca-protect'              => 'Peulindông ôn nyoë',
'tooltip-ca-delete'               => 'Sampôh ôn nyoë',
'tooltip-ca-move'                 => 'Peupinah ôn nyoë',
'tooltip-ca-watch'                => 'Peutamah ôn nyoë u dapeuta kalön Droëneuh',
'tooltip-ca-unwatch'              => 'Sampôh ôn nyoë nibak dapeuta keunalön Droëneuh',
'tooltip-search'                  => 'Mita lam {{SITENAME}} nyoë',
'tooltip-search-go'               => 'Mita saboh ôn ngon nan nyang peureuséh lagèë nyoë meunyo na',
'tooltip-search-fulltext'         => 'Mita ôn nyang na asoë lagèë nyoë',
'tooltip-n-mainpage'              => 'Jak u Ôn Keuë',
'tooltip-n-portal'                => 'Bhah buët, peuë nyang jeuët neupeulaku, pat tamita sipeuë hay',
'tooltip-n-currentevents'         => 'Mita beurita nyang paléng barô',
'tooltip-n-recentchanges'         => 'Dapeuta nyang ban meu’ubah lam wiki.',
'tooltip-n-randompage'            => 'Peuleumah beurangkari ôn',
'tooltip-n-help'                  => 'Bak mita bantu.',
'tooltip-t-whatlinkshere'         => 'Dapeuta mandum ôn wiki nyang na hubông u ôn nyoë',
'tooltip-t-recentchangeslinked'   => 'Neu’ubah paléng barô ôn-ôn nyang na hubông u ôn nyoë',
'tooltip-feed-rss'                => 'Umpeuën RSS keu ôn nyoë',
'tooltip-feed-atom'               => 'Umpeuën Atom keu ôn nyoë',
'tooltip-t-contributions'         => 'Eu dapeuta nyang ka geutuléh lé nyang nguy nyoë',
'tooltip-t-emailuser'             => 'Kirém surat-e u nyang nguy nyoë',
'tooltip-t-upload'                => 'Peutamong gamba atawa beureukah alat',
'tooltip-t-specialpages'          => 'Dapeuta mandum ôn husôh',
'tooltip-t-print'                 => 'Seunalén citak ôn nyoë',
'tooltip-t-permalink'             => '
Hubông teutap keu revisi ôn nyoë',
'tooltip-ca-nstab-main'           => 'Eu ôn asoë',
'tooltip-ca-nstab-user'           => 'Eu ôn nyang nguy',
'tooltip-ca-nstab-special'        => 'Nyoë nakeuh ôn husôh nyang h’an jeuët geu’andam.',
'tooltip-ca-nstab-project'        => 'Eu ôn buët',
'tooltip-ca-nstab-image'          => 'Eu ôn beureukah',
'tooltip-ca-nstab-template'       => 'Eu templat',
'tooltip-ca-nstab-help'           => 'Eu ôn beunantu',
'tooltip-ca-nstab-category'       => 'Eu ôn kawan',
'tooltip-minoredit'               => 'Bôh tanda keu nyoë sibagoë andam bacut',
'tooltip-save'                    => 'Keubah neu’ubah Droëneuh',
'tooltip-preview'                 => 'Peuleumah neu’ubah Droëneuh, nguy nyoë sigohlom keubah!',
'tooltip-diff'                    => 'Peuleumah neu’ubah nyang ka Droëneuh peugèt',
'tooltip-compareselectedversions' => 'Ngiëng bida antara duwa curak ôn nyang jipilèh.',
'tooltip-watch'                   => 'Peutamah ôn nyoë u dapeuta keunalön Droëneuh',
'tooltip-rollback'                => 'Peuriwang neu’andam-neu’andam bak ôn nyoë u nyang tuléh keuneulheuëh lam sigo teugön',
'tooltip-undo'                    => 'Peuriwang geunantoë nyoë ngön peuhah plôk neu’andam ngön cara eu dilèë. Choë jeuët geupeutamah bak plôk reuningkah.',

# Browsing diffs
'previousdiff' => '← Bida away',
'nextdiff'     => 'Geunantoë lheuëh nyan →',

# Media information
'file-info-size'       => '($1 × $2 piksel, rayek beureukah: $3, MIME jeunèh: $4)',
'file-nohires'         => '<small>Hana resolusi nyang leubèh manyang.</small>',
'svg-long-desc'        => '(Beureukah SVG, nominal $1 x $2 piksel, rayek beureukah: $3)',
'show-big-image'       => 'Resolusi peunoh',
'show-big-image-thumb' => '<small>Rayek atra nyoë: $1 x $2 piksel</small>',

# Special:NewFiles
'newimages' => 'Beureukah barô',

# Bad image list
'bad_image_list' => 'Beuntukjih lagèë di miyub nyoë:

Chit buté dapeuta (baréh nyang geupeuphôn ngon tanda *) nyang geukira. Hubông phôn bak saboh baréh beukeu hubông u beureukah nyang brôk.
Hubông-hubông lheuëh nyan bak baréh nyang saban geukira sibagoë keucuali, nakeu teunuléh nyang jeuët peuleumah beureukah nyan.',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Beureukah nyoë na beurita tambahan nyang mungkén geutamah lé kamèra digital atawa peuminday nyang geunguy keu peugöt atawa peudigitalisasi beureukah. Meunyo beureukah nyoë ka geu’ubah, tapeusili nyang na mungkén hana seucara peunoh meurefleksikan beurita nibak gamba nyang ka geu’ubah nyoë.',
'metadata-expand'   => 'Peuleumah tapeusili teunamah',
'metadata-collapse' => 'Peusom tapeusili teunamah',
'metadata-fields'   => 'Èntri mètadata EXIF nyoë keuneuk geupeuleumah bak ôn beurita gamba meunyo tabel mètadata geupeusom. Èntri la’én seucara baku keuneuk geupeusom.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength', # Do not translate list items

# External editor support
'edit-externally'      => 'Andam beureukah nyoë ngön aplikasi luwa',
'edit-externally-help' => '(Ngiëng [http://meta.wikimedia.org/wiki/Help:External_editors arah atô] keu beurita leubèh lanjôt)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ban dum',
'namespacesall' => 'ban dum',
'monthsall'     => 'ban dum',

# Watchlist editing tools
'watchlisttools-view' => 'Peuleumah neu’ubah meuhubông',
'watchlisttools-edit' => 'Peuleumah ngön andam dapeuta kaeunalön',
'watchlisttools-raw'  => 'Andam dapeuta keunalön meuntah',

# Special:Version
'version' => 'Curak', # Not used as normal message but as header for the special page itself

# Special:SpecialPages
'specialpages' => 'Ôn husôh',

);
