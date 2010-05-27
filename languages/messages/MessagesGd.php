<?php
/** Scottish Gaelic (Gàidhlig)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Akerbeltz
 * @author Alison
 * @author Caoimhin
 * @author Sionnach
 * @author Steafan31
 * @author לערי ריינהארט
 */

$messages = array(
# User preference toggles
'tog-underline'             => 'Fo-loidhneadh nan ceanglaichean:',
'tog-highlightbroken'       => 'An cleachdar am fòrmat <a href="" class="new">seo</a> airson ceanglaichean briste (no am fear seo<a href="" class="internal">?</a>)',
'tog-justify'               => 'Taobhaich na h-earrannan',
'tog-hideminor'             => 'Falaich mùthaidhean beaga ann an liosta nam mùthaidhean ùra',
'tog-hidepatrolled'         => 'Falaich mùthaidhean fo fhaire ann an liosta nam mùthaidhean ùra',
'tog-newpageshidepatrolled' => 'Falaich duilleagan fo fhaire ann an liosta nan duilleagan ùra',
'tog-extendwatchlist'       => "Leudaich an clàr-faire gus an seall e gach mùthadh 's chan ann an fheadhainn as ùire a-mhàin",
'tog-rememberpassword'      => "Cuimhnichear air a' choimpiutair seo gu bheil mi air logadh a-steach",

# Dates
'sunday'        => 'Didòmhnaich',
'monday'        => 'Diluain',
'tuesday'       => 'Dimàirt',
'wednesday'     => 'Diciadain',
'thursday'      => 'Diardaoin',
'friday'        => 'Dihaoine',
'saturday'      => 'Disathairne',
'sun'           => 'DiD',
'mon'           => 'DiL',
'tue'           => 'DiM',
'wed'           => 'DiC',
'thu'           => 'DiA',
'fri'           => 'DiH',
'sat'           => 'DiS',
'january'       => 'am Faoilteach',
'february'      => 'an Gearran',
'march'         => 'am Màrt',
'april'         => 'an Giblean',
'may_long'      => 'An Cèitean',
'june'          => 'an t-Òg-mhios',
'july'          => 'an t-Iuchar',
'august'        => 'an Lùnastal',
'september'     => 'an t-Sultain',
'october'       => 'an Dàmhair',
'november'      => 'an t-Samhain',
'december'      => 'an Dùbhlachd',
'january-gen'   => 'an Fhaoillich',
'february-gen'  => "a' Ghearrain",
'march-gen'     => "a' Mhàirt",
'april-gen'     => "a' Ghiblein",
'may-gen'       => "a' Chèitein",
'june-gen'      => 'an Òg-mhiosa',
'july-gen'      => 'an Iuchair',
'august-gen'    => 'an Lùnastail',
'september-gen' => 'na Sultaine',
'october-gen'   => 'na Dàmhair',
'november-gen'  => 'na Samhna',
'december-gen'  => 'na Dùbhlachd',
'may'           => 'an Cèitean',

# Categories related messages
'category_header' => 'Altan sa ghnè "$1"',
'subcategories'   => 'Fo-ghnethan',

'about'          => 'Mu',
'newwindow'      => '(a’ fosgladh ann an uinneag ùr)',
'qbfind'         => 'Lorg',
'qbedit'         => 'Deasaich',
'qbpageoptions'  => 'An duilleag seo',
'qbmyoptions'    => 'Na duilleagan agam',
'qbspecialpages' => 'Duilleagan àraidh',
'moredotdotdot'  => 'Barrachd...',
'mypage'         => 'Mo dhuilleag',
'mytalk'         => 'Mo chonaltradh',
'anontalk'       => 'Labhairt air an IP seo',
'and'            => '&#32;agus',

'errorpagetitle'    => 'Mearachd',
'returnto'          => 'Till gu $1.',
'tagline'           => "Às a' {{SITENAME}}",
'help'              => 'Cuideachadh',
'search'            => 'Lorg',
'searchbutton'      => 'Lorg',
'go'                => 'Rach',
'searcharticle'     => 'Rach',
'history'           => 'Eachdraidh dhuilleag',
'history_short'     => 'Eachdraidh',
'info_short'        => 'Fiosrachadh',
'printableversion'  => 'Lethbhreac so-chlòbhualadh',
'permalink'         => 'Ceangal maireannach',
'edit'              => 'Deasaich',
'create'            => 'Cruthaich',
'editthispage'      => 'Deasaich an duilleag seo',
'delete'            => 'Dubh às',
'deletethispage'    => 'Dubh às an duilleag seo',
'protect'           => 'Dìon',
'protectthispage'   => 'Dìon an duilleag seo',
'unprotect'         => 'Neo-dhìon',
'unprotectthispage' => 'Neo-dìon an duilleag seo',
'newpage'           => 'Duilleag ùr',
'talkpage'          => "Deasbair mu'n duilleig seo",
'talkpagelinktext'  => 'Deasbaireachd',
'specialpage'       => 'Duilleag àraidh',
'personaltools'     => 'Innealan pearsanta',
'talk'              => 'Deasbaireachd',
'userpage'          => 'Seall duilleag cleachdair',
'imagepage'         => 'Seall duilleag ìomhaigh',
'otherlanguages'    => 'Cànanan eile',
'redirectedfrom'    => '(Ath-stiùirte o $1)',
'protectedpage'     => 'Duilleag dìonta',
'jumptosearch'      => 'lorg',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => "Mu dheidhinn a' {{SITENAME}}",
'aboutpage'            => 'Project:Mu',
'copyright'            => 'Gheibhear brìgh na duilleig seo a-rèir an $1.',
'copyrightpagename'    => '{{SITENAME}} dlighe-sgrìobhaidh',
'copyrightpage'        => '{{ns:project}}:Dlighean-sgrìobhaidh',
'currentevents'        => 'Cùisean an latha',
'currentevents-url'    => 'Project:Cùisean an latha',
'disclaimers'          => 'Àicheidhean',
'disclaimerpage'       => 'Project:General disclaimer',
'edithelp'             => 'Cobhair deasachaidh',
'edithelppage'         => 'Help:Deasachadh',
'helppage'             => 'Help:Cuideachadh',
'mainpage'             => 'Prìomh-Dhuilleag',
'mainpage-description' => 'Prìomh-Dhuilleag',
'portal'               => 'Doras na Coimhearsnachd',
'portal-url'           => 'Project:Doras na coimhearsnachd',
'privacy'              => 'Polasaidh uaigneachd',
'privacypage'          => 'Project:Polasaidh uaigneachd',

'retrievedfrom'           => 'Air tarraing à "$1"',
'youhavenewmessages'      => 'Tha $1 ($2) agad.',
'newmessageslink'         => 'teachdaireachdan ùra',
'newmessagesdifflink'     => 'mùthadh mu dheireadh',
'youhavenewmessagesmulti' => 'Tha teachdaireachdan ùra agad ann an $1',
'editsection'             => 'deasaich',
'editlink'                => 'deasaich',
'toc'                     => 'Clàr-innse',
'showtoc'                 => 'nochd',
'hidetoc'                 => 'falaich',
'red-link-title'          => '$1 (chan eil aiste ann fhathast)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Aiste',
'nstab-user'      => 'Duilleag cleachdair',
'nstab-media'     => 'Meadhanan',
'nstab-special'   => 'Duilleag àraidh',
'nstab-project'   => 'Duilleag na pròiseict',
'nstab-image'     => 'Ìomhaigh',
'nstab-mediawiki' => 'Teachdaireachd',
'nstab-template'  => 'Cumadair',
'nstab-help'      => 'Cuideachadh',
'nstab-category'  => 'Gnè',

# Main script and global functions
'nospecialpagetext' => "Tha thu air duilleig àraidh nach aithneachas an wiki a dh'iarraidh.",

# General errors
'error'           => 'Mearachd',
'databaseerror'   => 'Mearachd an stor-dàta',
'noconnect'       => "Tha sinn duilich! Tha trioblaidean teicneòlais aig a' wiki an dràsda, is cha gabh fios a chur gu frithealaiche an stòr-dàta. <br />
$1",
'nodb'            => 'Cha do thaghadh stòr-dàta $1',
'cachederror'     => "Is e lethbhreac taisgte na duilleig a dh'iarr thu a leanas, agus dh'fhaoite nach eil e nuadh-aimsireil.",
'readonly'        => 'Stor-dàta glaiste',
'badarticleerror' => 'Cha ghabh an gnìomh seo a dhèanamh air an duilleig seo.',
'badtitle'        => 'Droch thiotal',

# Login and logout pages
'logouttitle'                => 'Log a-mach an neach-cleachdaidh',
'loginpagetitle'             => 'Log a-steach an neach-cleachdaidh',
'yourname'                   => "D' ainm-cleachdaidh",
'yourpassword'               => 'Am facal-faire agad',
'yourpasswordagain'          => 'Ath-sgrìobh facal-faire',
'remembermypassword'         => 'Cuimhnichear air a’ choimpiutair seo gu bheil mi logged a-stigh',
'login'                      => 'Log a-steach',
'nav-login-createaccount'    => 'Log a-steach / Cruthaich cunntas',
'userlogin'                  => 'Log a-steach',
'logout'                     => 'Log a-mach',
'userlogout'                 => 'Log a-mach',
'nologinlink'                => 'Cruthaich cunntas',
'createaccount'              => 'Cruthaich cunntas ùr',
'youremail'                  => 'Post dealain:',
'username'                   => 'Ainm-cleachdaidh:',
'yourrealname'               => "An dearbh ainm a th' ort*",
'yourlanguage'               => 'Cànan:',
'yournick'                   => 'An leth-ainm agad (a chuirear ri teachdaireachdan)',
'loginerror'                 => 'Mearachd log a-steach',
'noname'                     => 'Chan eil thu air ainm-cleachdair iomchaidh a chomharrachadh.',
'nosuchusershort'            => 'Chan eil cleachdair leis an ainm "$1" ann; sgrùd an litreachadh agad no cleachd am billeag gu h-ìseal gus cùnntas ùr a chrùthachadh.',
'wrongpassword'              => "Chan eil am facal-faire a sgrìobh thu a-steach ceart. Feuch a-rithist, ma's e do thoil e.",
'acct_creation_throttle_hit' => 'Tha sinn duilich; tha thu air $1 {{PLURAL:$1|cùnntas|chùnntas|cùnntasan|cùnntas}} a chruthachadh cheana agus chan fhaod tu barrachd a dhèanamh.',
'accountcreated'             => 'Cunntas cruthaichte',

# Password reset dialog
'oldpassword' => 'Seann fhacal-faire',
'newpassword' => 'Facal-faire ùr',
'retypenew'   => 'Ath-sgrìobh facal-faire ùr',

# Edit page toolbar
'italic_sample'   => 'Teacsa eadailteach',
'italic_tip'      => 'Teacsa eadailteach',
'headline_sample' => 'Teacsa ceann-loidhne',
'headline_tip'    => 'Ceann-loidhne ìre 2',
'image_sample'    => 'Eisimpleir.jpg',
'media_sample'    => 'Eisimpleir.ogg',

# Edit pages
'summary'           => 'Geàrr-chùnntas:',
'subject'           => 'Cuspair/ceann-loidhne:',
'minoredit'         => 'Seo mùthadh beag',
'watchthis'         => 'Cùm sùil air an aithris seo',
'savearticle'       => 'Sàbhail duilleag',
'preview'           => 'Roi-shealladh',
'showpreview'       => 'Nochd roi-shealladh',
'showdiff'          => 'Seall atharrachaidhean',
'blockedtitle'      => 'Tha an cleachdair air a bhacadh',
'loginreqlink'      => 'log a-steach',
'accmailtitle'      => 'Facal-faire air a chur.',
'accmailtext'       => "Tha am facal-faire aig '$1' air a chur ri $2.",
'newarticle'        => '(Ùr)',
'noarticletext'     => '(Chan eil teacsa sam bith anns an duilleag seo a-nis)',
'updated'           => '(Nua-dheasaichte)',
'previewnote'       => "'''Cuimhnich nach e ach roi-shealladh a tha seo, agus chan eil e air a shàbhaladh fhathast!'''",
'editing'           => "A' deasaicheadh $1",
'editconflict'      => 'Mì-chòrdadh deasachaidh: $1',
'explainconflict'   => "Tha cuideigin eile air an duilleig seo a mhùthadh o'n thòisich tu fhèin a dheasaicheadh. Tha am bocsa teacsa shuas a' nochdadh na duilleig mar a tha e an dràsda. Tha na mùthaidhean agadsa anns a' bhocsa shios. Feumaidh tu na mùthaidhean agad a choimeasgachadh leis an teacsa làithreach. Cha tèid <b>ach an teacsa shuas</b> a shàbhaladh an uair a bhriogas tu \"Sàbhail duilleag\".<p>",
'yourtext'          => 'An teacsa agad',
'storedversion'     => 'Lethbhreac taisgte',
'editingold'        => "'''RABHADH: Tha thu a' deasaicheadh lethbhreac sean-aimsireil na duilleig seo. Ma shàbhalas tu e, bithidh uile na mùthaidhean dèanta as dèidh an lethbhreac seo air chall.'''",
'yourdiff'          => 'Caochlaidhean',
'copyrightwarning'  => "Tha uile na cuideachaidhean ri {{SITENAME}} air an leigeil mu sgaoil fo chùmhnantan $2 a' Chead GNU Aithriseachd Saor (GFDL) (seall $1 airson barrachd fiosrachaidh). 
Mur eil thu ag iarraidh an sgrìobhaidh agad a dheasaichear is a sgaoilear le càch,  na cuir e.<br />
Ma dh'fhoilleachas tu rudeigin an seo, bidh tu a' dearbhadh gun do sgrìobh thu fhèin e, no gur ann às an raon phòballach a thàinig e; thoir aire '''nach eil''' sin a' gabhail a-staigh duilleagan-lìn mar as àbhaist.<br />
'''NA CLEACHDAIBH SAOTHAIR FO DHLIGHE-SGRÌOBHAIDH GUN CHEAD!'''",
'copyrightwarning2' => "Ged a thatar gur moladh {{SITENAME}} a chruthachadh, a mheudachadh, is a leasachadh, thèid droch dheasaicheidhean a chur air imrich gu luath. 
Mur eil thu ag iarraidh an sgrìobhaidh agad a dheasaichear is a sgaoilear le càch, na cuir e.<br />
Ma dh'fhoilleachas tu rudeigin an seo, bidh tu a' dearbhadh gun do sgrìobh thu fhèin e, no gur ann às an raon phòballach a thàinig e; thoir aire '''nach eil''' sin a' gabhail a-staigh duilleagan-lìn mar as àbhaist (seall $1 airson barrachd fiosrachaidh). <br />
'''NA CLEACHDAIBH SAOTHAIR FO DHLIGHE-SGRÌOBHAIDH GUN CHEAD!'''",

# History pages
'nohistory'  => 'Chan eil eachdraidh deasachaidh aig an duilleig seo.',
'currentrev' => 'Lethbhreac làithreach',
'cur'        => 'làith',
'next'       => 'ath',
'last'       => 'mu dheireadh',

# Diffs
'lineno'                  => 'Loidhne $1:',
'compareselectedversions' => 'Coimeas lethbhreacan taghta',

# Search results
'searchresults'         => 'Toraidhean rannsachaidh',
'noexactmatch-nocreate' => "'''Chan eil duilleag ann leis an ainm “$1”.'''",
'notitlematches'        => "Chan eil tiotal duilleig a' samhlachadh",
'notextmatches'         => "Chan eil teacsa duilleig a' samhlachadh",
'prevn'                 => '$1 mu dheireadh',
'nextn'                 => 'an ath $1',
'viewprevnext'          => 'Seall ($1) ($2) ($3).',
'searchhelp-url'        => 'Help:Cuideachadh',
'showingresults'        => "A' nochdadh $1 {{PLURAL:$1|thoradh|toradh|toraidhean|toradh}} gu h-ìosal a' tòiseachadh le #'''$2'''.",
'showingresultsnum'     => "A' nochdadh '''$3''' {{PLURAL:$3|thoradh|toradh|toraidhean|toradh}}  gu h-ìosal a' tòiseachadh le #'''$2'''.",
'powersearch'           => 'Rannsaich',

# Preferences page
'preferences'        => 'Roghainnean',
'mypreferences'      => 'Mo roghainnean',
'changepassword'     => 'Atharraich facal-faire',
'skin'               => 'Bian',
'skin-preview'       => 'Roi-shealladh',
'dateformat'         => 'Cruth nan ceann-latha',
'math_unknown_error' => 'mearachd neo-aithnichte',
'prefs-personal'     => "Dàta a' chleachdair",
'saveprefs'          => 'Sàbhail roghainnean',
'resetprefs'         => 'Ath-shuidhich taghaidhean',
'rows'               => 'Sreathan',
'columns'            => 'Colbhan',
'savedprefs'         => 'Tha na roghainnean agad air an sàbhaladh.',
'default'            => 'Gnàth',

# User rights
'userrights-changeable-col' => "Buidhnean a dh' atharraicheas tu",

# Associated actions - in the sentence "You do not have permission to X"
'action-move' => 'gluais an duilleag seo',

# Recent changes
'nchanges'          => '$1 {{PLURAL:$1|mùthadh|mhùthadh|mùthaidhean|mùthadh}}',
'recentchanges'     => 'Mùthaidhean ùra',
'recentchangestext' => 'Lean mùthaidhean ùra aig an wiki air an duilleag seo.',
'rcnote'            => "Tha na {{PLURAL:$1|'''1''' mùthadh|$1 mùthaidhean}} deireanach air na {{PLURAL:$2|là|'''$2''' laithean}} deireanach gu h-ìosal as  $5, $4.",
'rcnotefrom'        => "Gheibhear na mùthaidhean o chionn <b>$2</b> shios (a'nochdadh suas ri <b>$1</b>).",
'rclistfrom'        => 'Nochd mùthaidhean ùra o chionn $1',
'rclinks'           => 'Nochd na $1 mùthaidhean deireanach air na $2 laithean deireanach<br />$3',
'diff'              => 'diof',
'hist'              => 'eachd',
'hide'              => 'falaich',
'show'              => 'nochd',
'minoreditletter'   => 'b',
'newpageletter'     => 'Ù',
'boteditletter'     => 'r',

# Recent changes linked
'recentchangeslinked'      => 'Mùthaidhean buntainneach',
'recentchangeslinked-page' => 'Ainm na duilleige:',

# Upload
'upload'        => 'Cuir ri fhaidhle',
'filename'      => 'Ainm-faidhle',
'filedesc'      => 'Geàrr-chùnntas',
'filestatus'    => 'Cor dlighe-sgrìobhaidh:',
'ignorewarning' => 'Leig an rabhadh seachad agus sàbhail am faidhle codhiù.',
'badfilename'   => 'Ainm ìomhaigh air atharrachadh ri "$1".',
'fileexists'    => 'Tha faidhle leis an ainm seo ann cheana; nach faigh sibh cinnt air $1 gu bheil sibh ag iarraidh atharrachadh.
[[$1|thumb]]',
'savefile'      => 'Sàbhail faidhle',

# Special:ListFiles
'listfiles' => 'Liosta nan ìomhaigh',

# File description page
'filehist-user' => 'Neach-cleachdaidh',

# Random page
'randompage' => 'Duilleag thuairmeach',

'doubleredirects' => 'Ath-stiùireidhean dùbailte',

'brokenredirects' => 'Ath-stiùireidhean briste',

# Miscellaneous special pages
'nviews'                  => '$1 {{PLURAL:$1|shealladh|sealladh|seallaidhean|sealladh}}',
'uncategorizedpages'      => 'Duilleagan neo-ghnethichte',
'uncategorizedcategories' => 'Gnethan neo-ghnethichte',
'unusedimages'            => 'Ìomhaighean neo-chleachdte',
'shortpages'              => 'Duilleagan goirid',
'longpages'               => 'Duilleagan fada',
'listusers'               => 'Liosta nan cleachdair',
'newpages'                => 'Duilleagan ùra',
'ancientpages'            => 'Duilleagan as sìne',
'move'                    => 'Gluais',
'movethispage'            => 'Gluais an duilleag seo',

# Book sources
'booksources-go' => 'Rach',

# Special:Log
'all-logs-page' => 'Clàraidhean',

# Special:AllPages
'allpages'       => 'Duilleagan uile',
'nextpage'       => 'An ath dhuilleag ($1)',
'allpagessubmit' => 'Rach',

# Special:Categories
'categories'         => 'Gnethan',
'categoriespagetext' => "Tha na gnethan a leanas anns a'wiki.",

# E-mail user
'emailfrom'    => 'Bho',
'emailto'      => 'Ri',
'emailsubject' => 'Cuspair',
'emailmessage' => 'Teachdaireachd',
'emailsend'    => 'Cuir',

# Watchlist
'watchlist'          => 'Clàr-faire',
'mywatchlist'        => 'Mo chlàr-faire',
'nowatchlist'        => "Chan eil altan air d' fhaire.",
'addedwatch'         => 'Cuirte ri coimheadlìosta',
'addedwatchtext'     => "Tha an duilleag \"[[:\$1]]\" cuirte ri [[Special:Watchlist|do fhaire]] agad.  Ri teachd, bith chuir an àireamh an-sin mùthaidhean na duilleige sin agus a' dhuilleag \"Talk\", agus bith an duilleag '''tromte''' ann an [[Special:RecentChanges|lìosta nam mùthaidhean ùra]] a dh'fhurasdaich i a sheall.

<p> Ma bu toil leat a dhubh an\\ duilleag as do  fhaire agad nas fadalache, cnap air \"Caisg a\\' coimhead\" air an taobh-colbh.",
'watch'              => 'Faire',
'watchthispage'      => 'Cùm sùil air an duilleag seo',
'watchnochange'      => "Cha deach na duilleagan air d' fhaire a dheasachadh anns a' chuairt ùine taisbeanta.",
'watchmethod-recent' => "A' sgrùdadh deasachaidhean ùra airson duilleagan air d' fhaire",
'watchmethod-list'   => "A' sgrùdadh duilleagan air d' fhaire airson deasachaidhean ùra",
'watchlistcontains'  => 'Tha $1 {{PLURAL:$1|duilleag|dhuilleag| duilleagan|duilleag}} air do chlàr-faire.',
'wlnote'             => 'Seo $1 {{PLURAL:$1|mhùthadh mu dheireadh|mhùthadh mu dheireadh|na mùthaidhean mu dheireadh|mùthadh mu dheireadh}} anns na $2 {{PLURAL:$2|uair|uair|uairean|uair}} mu dheireadh.',
'wlshowlast'         => 'Nochd $1 uairean $2 laithean mu dheireadh $3',
'watchlist-options'  => 'Roghainnean clàr-faire',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Ri faireadh...',

# Delete
'deletepage'             => 'Dubh às duilleag',
'confirm'                => 'Daingnich',
'excontent'              => "stuth a bh' ann: '$1'",
'exblank'                => 'bha duilleag falamh',
'delete-confirm'         => 'Dubh às "$1"',
'delete-legend'          => 'Dubh às',
'actioncomplete'         => 'Gnìomh coileanta',
'reverted'               => 'Tillte ri lethbhreac as ùire',
'deletecomment'          => 'Adhbhar airson sguabadh às:',
'delete-edit-reasonlist' => 'Deasaich adhbharan dubhadh às',

# Rollback
'editcomment' => "Bha mìneachadh an deasaicheidh: \"''\$1''\".", # only shown if there is an edit comment
'revertpage'  => 'Tillte deasachadh aig [[Special:Contributions/$2|$2]] ([[User talk:$2|Deasbaireachd]]) ais ri lethbhreac mu dheireadh le [[User:$1|$1]]', # Additionally available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from

# Protect
'protectedarticle'   => 'dìonta "[[$1]]"',
'unprotectedarticle' => '"[[$1]]" neo-dhìonta',
'protect-title'      => 'A\' dìonadh "$1"',
'prot_1movedto2'     => '[[$1]] gluaiste ri [[$2]]',
'protect-legend'     => 'Daingnich dìonadh',
'protectcomment'     => 'Aobhar airson dìonaidh',

# Undelete
'undeleterevisions' => '$1 {{PLURAL:$1|leth-bhreac|leth-bhreac|leth-bhreacan|leth-bhreac}} taisge',

# Namespace form on various pages
'blanknamespace' => '(Prìomh)',

# Contributions
'mycontris' => 'Mo chuideachaidhean',
'uctop'     => ' (bàrr)',

'sp-contributions-username' => 'Seòladh IP no ainm-cleachdair:',
'sp-contributions-submit'   => 'Lorg',

# What links here
'whatlinkshere'      => "Dè tha a' ceangal ri seo?",
'whatlinkshere-page' => 'Duilleag:',
'isredirect'         => 'duilleag ath-stiùireidh',

# Block/unblock
'blockip'            => 'Bac cleachdair',
'ipaddress'          => 'IP Seòladh/ainm-cleachdair',
'ipbreason'          => 'Aobhar',
'ipbsubmit'          => 'Bac an cleachdair seo',
'badipaddress'       => "Chan eil an seòladh IP aig a' cleachdair seo iomchaidh",
'blockipsuccesssub'  => 'Shoirbhich bacadh',
'blockipsuccesstext' => "Tha [[Special:Contributions/$1|$1]] air a bhacadh.
<br />Faic [[Special:IPBlockList|Liosta nan IP baicte]] na bacaidhean a dh'ath-sgrùdadh.",
'unblockip'          => 'Neo-bhac cleachdair',
'ipusubmit'          => 'Neo-bhac an seòladh seo',
'ipblocklist'        => 'Liosta seòlaidhean IP agus ainmean-cleachdair air am bacadh',
'blocklink'          => 'bac',
'unblocklink'        => 'neo-bhac',
'contribslink'       => 'mùthaidhean',
'blocklogentry'      => 'Chaidh [[$1]] a bhacadh le ùine crìochnachaidh de $2 $3',
'unblocklogentry'    => '"$1" air neo-bhacadh',
'ipb_expiry_invalid' => 'Ùine-crìochnaidh neo-iomchaidh.',
'ip_range_invalid'   => 'Raon IP neo-iomchaidh.',
'proxyblocksuccess'  => 'Dèanta.',

# Developer tools
'lockdb'           => 'Glais stòr-dàta',
'lockconfirm'      => 'Seadh, is ann a tha mi ag iarraidh an stòr-dàta a ghlasadh.',
'lockbtn'          => 'Glais stor-dàta',
'lockdbsuccesssub' => 'Shoirbhich glasadh an stor-dàta',

# Move page
'move-page-legend'        => 'Gluais duilleag',
'movearticle'             => 'Gluais duilleag:',
'movepagebtn'             => 'Gluais duilleag',
'pagemovedsub'            => 'Gluasad soirbheachail',
'movedto'                 => 'air gluasad gu',
'1movedto2'               => '$1 gluaiste ri $2',
'1movedto2_redir'         => '$1 gluaiste ri $2 thairis air ath-stiùireadh',
'delete_and_move'         => 'Sguab às agus gluais',
'delete_and_move_confirm' => 'Siuthad, sguab às an duilleag',

# Namespace 8 related
'allmessages'     => 'Brathan an t-siostaim uile',
'allmessagestext' => 'Seo liosta de\'n a h-uile teachdaireachd an t-siostam ri fhaotainn anns an fhànais-ainm "Mediawiki:".',

# Thumbnails
'thumbnail-more' => 'Meudaich',
'filemissing'    => "Faidhle a dh'easbhaidh",

# Special:Import
'importnotext' => 'Falamh no gun teacsa',

# Tooltip help for the actions
'tooltip-pt-logout'       => 'Log a-mach',
'tooltip-n-mainpage'      => "Tadhail air a' Phrìomh-Dhuilleig",
'tooltip-n-portal'        => "Mun phròiseact, na 's urrainn dhuit dhèanamh, far an lorgar nithean",
'tooltip-n-recentchanges' => 'Liosta nam mùthaidhean ùra aig an wiki.',
'tooltip-t-emailuser'     => 'Cuir p-d dhan neach-cleachdaidh seo',
'tooltip-minoredit'       => 'Comharraich seo mar meanbh-dheasachadh',
'tooltip-save'            => 'Sàbhail na mùthaidhean agad',
'tooltip-preview'         => 'Roi-sheallaibh na mùthaidhean agad; cleachd seo mas sàbhail thu iad!',

# Attribution
'othercontribs' => 'Stèidhichte air obair le $1.',
'others'        => 'eile',

# Info page
'infosubtitle' => 'Fiosrachadh air duilleig',
'numwatchers'  => 'Aireamh luchd-faire: $1',

# Special:NewFiles
'ilsubmit' => 'Rannsaich',
'bydate'   => 'air ceann-latha',

# Watchlist editor
'watchlistedit-normal-title' => 'Deasaich clàr-faire',
'watchlistedit-raw-submit'   => 'Ùraich Clàr-faire',

# Special:Version
'version' => 'Tionndadh', # Not used as normal message but as header for the special page itself

# Special:SpecialPages
'specialpages' => 'Duilleagan àraidh',

);
