<?php
/** Lingala (Lingála)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Eruedin
 * @author Moyogo
 */

$fallback = 'fr';

$defaultUserOptionOverrides = array(
	'editfont' => 'sans-serif', # poor font support
);

$linkPrefixExtension = true;

# Same as the French (bug 8485)
$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'        => 'Kotíya sulimá na bikangisi :',
'tog-rememberpassword' => 'Komíkundola bokitoli na molúki (ekosúkisa na {{PLURAL:$1|mokɔlɔ|mikɔlɔ}} $1)',

# Dates
'monday'        => 'mokɔlɔ ya libosó',
'tuesday'       => 'mokɔlɔ ya míbalé',
'wednesday'     => 'mokɔlɔ ya mísáto',
'thursday'      => 'mokɔlɔ ya mínei',
'friday'        => 'mokɔlɔ ya mítáno',
'sun'           => 'eye',
'mon'           => 'm1',
'tue'           => 'm2',
'wed'           => 'm3',
'thu'           => 'm4',
'fri'           => 'm5',
'sat'           => 'mps',
'january'       => 'sánzá ya yambo',
'february'      => 'sánzá ya míbalé',
'march'         => 'sánzá ya mísáto',
'april'         => 'sánzá ya mínei',
'may_long'      => 'sánzá ya mítáno',
'june'          => 'sánzá ya motóbá',
'july'          => 'sánzá ya nsambo',
'august'        => 'sánzá ya mwambe',
'september'     => 'sánzá ya libwá',
'october'       => 'sánzá ya zómi',
'november'      => 'sánzá ya zómi na mɔ̌kɔ́',
'december'      => 'sánzá ya zómi na míbalé',
'january-gen'   => 'sánzá ya yambo',
'february-gen'  => 'sánzá ya míbalé',
'march-gen'     => 'sánzá ya mísáto',
'april-gen'     => 'sánzá ya mínei',
'may-gen'       => 'sánzá ya mítáno',
'june-gen'      => 'sánzá ya motóbá',
'july-gen'      => 'sánzá ya nsambo',
'august-gen'    => 'sánzá ya mwambe',
'september-gen' => 'sánzá ya libwá',
'october-gen'   => 'sánzá ya zómi',
'november-gen'  => 'novɛ́mbɛ',
'december-gen'  => 'sánzá ya zómi na míbalé',
'jan'           => 's1',
'feb'           => 's2',
'mar'           => 's3',
'apr'           => 's4',
'may'           => 's4',
'jun'           => 's5',
'jul'           => 's7',
'aug'           => 's8',
'sep'           => 's9',
'oct'           => 's10',
'nov'           => 's11',
'dec'           => 's12',

# Categories related messages
'pagecategories'  => '{{PLURAL:$1|Katégoli|Katégoli}}',
'category_header' => 'Nkásá o molɔngɔ́ ya bilɔkɔ ya loléngé mɔ̌kɔ́ « $1 »',
'subcategories'   => 'Ndéngé-bǎna',
'category-empty'  => "''Loléngé loye ezalí na ekakola tɛ̂, loléngé-mwǎna tɛ̂ tǒ nkásá mitímediá tɛ̂.''",

'about'      => 'elɔ́kɔ elobámí',
'article'    => 'ekakoli',
'newwindow'  => '(ekofúngola na lininísa lya sika)',
'cancel'     => 'Kozóngela',
'mytalk'     => 'Ntembe na ngáí',
'navigation' => 'Botamboli',
'and'        => '&#32;mpé',

# Cologne Blue skin
'qbfind'         => 'Koluka',
'qbbrowse'       => 'Kolúka',
'qbedit'         => 'Kobɔngisa',
'qbspecialpages' => 'Nkásá gudi',
'faq'            => 'Mitúná Mizóngelaka (MM)',

# Vector skin
'vector-action-addsection'       => 'Kobakisa mpɔ̂',
'vector-action-delete'           => 'Kolímwisa',
'vector-action-move'             => 'Kobóngola nkómbó',
'vector-action-protect'          => 'Kobátela',
'vector-action-undelete'         => 'Kolímwisa tɛ̂',
'vector-action-unprotect'        => ' Kobátela tɛ̂',
'vector-simplesearch-preference' => 'Kolamusa bokáni bwa boluki bobakísámí (káka na Vector)',
'vector-view-create'             => 'Kokela',
'vector-view-edit'               => 'Kobɔngisa',
'vector-view-history'            => 'Komɔ́nisa mokóló',
'vector-view-view'               => 'Kotánga',
'vector-view-viewsource'         => 'Komɔ́nisa mosólo',
'namespaces'                     => 'Ntáká ya nkómbó',

'errorpagetitle'    => 'Mbéba',
'returnto'          => 'Kozóngisa na $1.',
'tagline'           => 'Útá {{SITENAME}}.',
'help'              => 'Bosálisi',
'search'            => 'Boluki',
'searchbutton'      => 'Boluki',
'go'                => 'Kokɛndɛ',
'searcharticle'     => 'Kokɛndɛ',
'history'           => 'Mokóló mwa lonkásá',
'history_short'     => 'Mokóló',
'printableversion'  => 'Mpɔ̂ na kofínela',
'permalink'         => 'Ekangeli ya ntángo yɔ́nsɔ',
'print'             => 'kobimisa nkomá',
'view'              => 'Komɔ́nisa',
'edit'              => 'kobɔngisa',
'create'            => 'Kokela',
'editthispage'      => 'Kokoma lonkásá óyo',
'create-this-page'  => 'Kokela lonkásá loye',
'delete'            => 'Kolímwisa',
'deletethispage'    => 'Kolímwisa lonkásá loye',
'protect'           => 'Kobátela',
'protect_change'    => 'kobóngola',
'protectthispage'   => 'Kobátela lonkásá loye',
'unprotect'         => 'Kobátela tɛ̂',
'unprotectthispage' => 'Kobátela lonkásá loye tɛ̂',
'newpage'           => 'Lonkásá la sika',
'talkpage'          => 'Ntembe ya lonkásá loye',
'talkpagelinktext'  => 'Ntembe',
'specialpage'       => 'Lonkásá gudi',
'personaltools'     => 'Bisáleli ya moto-mɛ́i',
'articlepage'       => 'Komɔ́nisa káti',
'talk'              => 'Ntembe',
'views'             => 'Bomɔ́nisi',
'toolbox'           => 'Bisáleli',
'userpage'          => 'Komɔ́nisa lonkásá la mosáleli',
'imagepage'         => 'Komɔ́nisa lonkásá la kásá',
'viewhelppage'      => 'Komɔ́nisa lonkásá la bosálisi',
'categorypage'      => 'Komɔ́nisa lonkásá la katégori',
'otherlanguages'    => 'Na nkótá isúsu',
'redirectedfrom'    => '(Eyendísí útá $1)',
'redirectpagesub'   => 'Lonkásá la boyendisi',
'lastmodifiedat'    => 'Lonkásá loye lobóngwámí o mokɔlɔ $1, ngonga $2.',
'protectedpage'     => 'Lonkásá lobátélámí',
'jumpto'            => 'Kokɛndɛ na:',
'jumptonavigation'  => 'bolúki',
'jumptosearch'      => 'boluki',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Elɔ́kɔ elobí {{SITENAME}}',
'aboutpage'            => 'Project:Etalí',
'copyright'            => 'Maloba ma nkomá mazalí na ndingisa ya $1.',
'copyrightpage'        => '{{ns:project}}:Mikokisi',
'currentevents'        => 'Elɔ́kɔ ya sika',
'disclaimers'          => 'Ndelo ya boyanoli',
'disclaimerpage'       => 'Project:Boyanoli ndelo',
'edithelp'             => 'Kobimisela bosálisi',
'edithelppage'         => 'Help:Libɔngeli',
'mainpage'             => 'Lonkásá ya libosó',
'mainpage-description' => 'Lokásá ya libosó',
'portal'               => 'Bísó na bísó',
'privacy'              => 'Politíki ya viplívɛ',
'privacypage'          => 'Project:Politíki ya viplívɛ',

'ok'                 => 'Nandimi',
'retrievedfrom'      => 'Ezwámí úta « $1 »',
'youhavenewmessages' => 'Ozweí $1 ($2).',
'newmessageslink'    => 'nsango ya sika',
'editsection'        => 'kobɔngisa',
'editold'            => 'kobɔngisa',
'editlink'           => 'kobɔngisa',
'viewsourcelink'     => 'komɔ́nisa mosólo',
'editsectionhint'    => 'Kokoma sɛksíɔ : $1',
'toc'                => 'Etápe',
'showtoc'            => 'komɔ́nisa',
'hidetoc'            => 'kobomba',
'site-rss-feed'      => 'Ebale RSS ya $1',
'site-atom-feed'     => 'Ebale Atom ya « $1 »',
'page-rss-feed'      => 'Ebale RSS ya « $1 »',
'page-atom-feed'     => 'Ebale Atom ya « $1 »',
'red-link-title'     => '$1 (lonkásá  ezalí tɛ̂)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'lonkásá',
'nstab-user'      => 'Lonkásá la mosáleli',
'nstab-media'     => 'Mediá',
'nstab-special'   => 'Lonkásá gudi',
'nstab-project'   => 'Etalí',
'nstab-image'     => 'Kásá',
'nstab-mediawiki' => 'Liyébísí',
'nstab-template'  => 'Emekisele',
'nstab-help'      => 'Lonkásá ya lisálisi',
'nstab-category'  => 'Katégori',

# Main script and global functions
'nosuchaction'      => 'Ekelá eyébani tɛ̂',
'nosuchspecialpage' => 'Lonkásá gudi lwangó lozalí tɛ̂',
'nospecialpagetext' => '<strong>Otúní lonkásá gudi kasi yangó ezalí tɛ̂.</strong>

Ezalí listɛ́ ya nkásá gudi bizalí  na [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'              => 'Mbéba',
'databaseerror'      => 'Zíko ya litákoli ya kabo',
'laggedslavemode'    => "'''Likébisi''' : lonkásá óyo ekokí kokwa mbóngwana ya nsúka nyɔ́nsɔ tɛ̂",
'readonly'           => 'Litákoli ya kabo efúngámí',
'enterlockreason'    => 'Ópésa ntína mpé ntángo ya kokanisa ya bofúngi ya litákoli ya kabo',
'internalerror'      => 'Zíko ya káti',
'internalerror_info' => 'Zíko ya káti : $1',
'perfcached'         => 'Bipeseli byangó bizalí o mobómbisi-lombángu mpé bikokí kozala ya lɛlɔ́ tɛ̂.',
'perfcachedts'       => 'Bipeseli byangó bizalí o mobómbisi-lombángu mpé bikokí kozala ya lɛlɔ́ tɛ̂. Bizalí bya $1.',
'viewsource'         => 'Komɔ́nisa mosólo',
'viewsourcefor'      => 'na $1',

# Login and logout pages
'yourname'                => 'Nkómbó ya mosáleli :',
'yourpassword'            => 'Banda nayó:',
'yourpasswordagain'       => 'Banda naíno :',
'remembermypassword'      => 'Mɛ́i-komíkitola na molúki moye mbala ilandí (ekosúkisa na {{PLURAL:$1|mokɔlɔ|mikɔlɔ}} $1)',
'login'                   => 'komíkitola (log in)',
'nav-login-createaccount' => 'Komíkomisa tǒ kokɔtɔ',
'userlogin'               => 'Komíkomisa tǒ komíkitola',
'logout'                  => 'kolongwa',
'userlogout'              => 'Kolongwa',
'nologin'                 => "Omíkomísí naíno tɛ̂? '''$1'''.",
'nologinlink'             => 'Míkomísá yɔ̌-mɛ́i',
'gotaccount'              => "Omíkomísí naíno ? '''$1'''.",
'createaccountmail'       => 'na mokánda',
'mailmypassword'          => 'Kotíndisa liloba-nzelá lya sika na mɛ́lɛ',

# Edit page toolbar
'bold_sample'     => 'Nkomá ya mbinga',
'bold_tip'        => 'Nkomá ya mbinga',
'italic_sample'   => 'Nkomá ya kotɛ́ngama',
'italic_tip'      => 'Nkomá ya kotɛ́ngama',
'link_sample'     => 'Títɛlɛ ya ekangisele',
'extlink_sample'  => 'http://www.example.com Litɛ́mɛ ya ekangisi',
'extlink_tip'     => 'Ekangisele na libándá (óbósana http:// o libóso tɛ̂)',
'headline_sample' => 'Nkomá ya litɛ́mɛ',
'headline_tip'    => 'Litɛ́mɛ ya emeko 2',
'image_tip'       => 'Kásá eyíngísámí',
'media_tip'       => 'Ekangisele ya kásá',
'hr_tip'          => 'Monkɔ́lɔ́tɔ́ molálí sémba (kosálela mwâ mokɛ́)',

# Edit pages
'summary'                => 'Likwé ya mokusé:',
'subject'                => 'Mokonza/litɛ́mɛ:',
'minoredit'              => 'Ezalí mbóngwana ya mokɛ́',
'watchthis'              => 'Kolanda lonkásá óyo',
'savearticle'            => 'kobómbisa lonkásá',
'preview'                => 'Botáli',
'showpreview'            => 'Kotála yambo',
'showdiff'               => 'Komɔ́nisa mbóngwana',
'newarticle'             => '(Sika)',
'editing'                => 'Kokoma « $1 »',
'editingsection'         => 'Bobɔngisi ya « $1 » (sɛksió)',
'editingcomment'         => 'Bobɔngisi ya « $1 » (sɛksió ya sika)',
'yourtext'               => 'Nkomá na yɔ̌',
'templatesused'          => '{{PLURAL:$1|Emekisele esálélí|Bimekisele bisálélí}} o lonkásá óyo :',
'templatesusedpreview'   => '{{PLURAL:$1|Emekisele esálélí|Bimekisele bisálélí}} o botáli boye :',
'template-protected'     => '(na bobáteli)',
'template-semiprotected' => '(na bobáteli ya ndámbo)',

# History pages
'viewpagelogs'        => 'Komɔ́nisa zuluná ya lonkásá loye',
'currentrev'          => 'Lizóngeli na mosálá',
'revisionasof'        => 'Lizóngeli ya $1',
'previousrevision'    => '← Lizóngeli lilekí',
'nextrevision'        => 'Lizóngeli lilandí →',
'currentrevisionlink' => 'Lizóngeli na mosálá',
'cur'                 => 'sika',
'next'                => 'bolɛngɛli',
'last'                => 'ya nsúka',
'histfirst'           => 'ya yambo',
'histlast'            => 'ya nsúka',

# Revision deletion
'rev-delundel'   => 'komɔ́nisa/kobomba',
'revdel-restore' => 'kobóngola emɔnanela',

# Merge log
'revertmerge' => 'Kotangola',

# Diffs
'history-title' => 'Mokóló mwa mbóngwana mwa « $1 »',
'lineno'        => 'Mokɔlɔ́tɔ $1 :',
'editundo'      => 'kozóngela',

# Search results
'searchresults'             => 'Bozwi bwa boluki',
'searchresults-title'       => 'Bozwi bwa boluka bwa «$1»',
'searchresulttext'          => 'Mpɔ̂ na liyébísí lya {{SITENAME}}, ótala [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => "Olukákí '''[[:$1]]'''  ([[Special:Prefixindex/$1|nkásá yɔ́nsɔ ibandí na « $1 »]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|nkásá yɔ́nsɔ ikangísí na « $1 »]])",
'searchsubtitleinvalid'     => "Olukákí « '''$1''' »",
'notitlematches'            => 'Títɛlɛ yɔ̌kɔ́ tɛ́ ekokánísí',
'notextmatches'             => 'Nkomá ya nkásá yɔ̌kɔ́ tɛ́ ekokánísí',
'prevn'                     => '{{PLURAL:$1|$1}} ya libosó',
'nextn'                     => 'bolɛngɛli {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Komɔ́na ($1 {{int:pipe-separator}} $2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|lokomá 1|nkomá $2}})',
'search-result-score'       => 'Ntína : $1%',
'search-redirect'           => '(boyendisi útá $1)',
'search-section'            => '(sɛksió ya $1)',
'search-suggest'            => 'Ómeka na lokomá : $1',
'search-interwiki-default'  => 'Bozwi bwa $1 :',
'search-interwiki-more'     => '(elekí)',
'search-mwsuggest-enabled'  => 'na bokáni',
'search-mwsuggest-disabled' => 'na bokáni tɛ́',
'nonefound'                 => "'''Notí''' : Boluki bwa likwá bosálí na ndámbo ya ntáká ya nkómbó.
Ómeka kobakisa ''all:'' o libóso lya esɛ́ngi mpɔ̂ na koluka maloba ma nkomá mánsɔ (ekɔ́tí ntembe, bimekisele, b.n.b.), tǒ kobakisa ntáka ya nkómbó eye olingí o libóso.",
'powersearch'               => 'Boluki',
'powersearch-legend'        => 'Boluki bopúsání',
'powersearch-ns'            => 'Koluka o ntáká ya nkómbó :',
'powersearch-redir'         => 'Kotíya molɔngɔ́ mwa mayendisi',
'powersearch-field'         => 'Koluka',

# Preferences page
'preferences'       => 'Malúli',
'mypreferences'     => 'Malúli ma ngáí',
'prefs-datetime'    => 'Mokɔlɔ mpé ntángo',
'prefs-rc'          => 'Mbóngwana ya nsúka',
'saveprefs'         => 'kobómbisa',
'searchresultshead' => 'Boluki',
'allowemail'        => 'Enable mokánda from other users',
'youremail'         => 'Mokandá (e-mail) *',
'username'          => 'Nkómbó ya mosáleli :',
'yourrealname'      => 'nkómbó ya sɔ̂lɔ́',
'yourlanguage'      => 'Lokótá',
'email'             => 'Mokánda',

# Groups
'group-sysop' => 'Bayángeli',

'group-sysop-member' => 'Moyángeli',

# User rights log
'rightslog' => 'Zuluná ya makokí ma basáleli',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'Kobɔngisa lonkásá loye',

# Recent changes
'recentchanges'                  => 'Mbóngwana ya nsúka',
'recentchanges-feed-description' => 'Kolanda mbóngwana ya nsúka ya wiki o ebale eye.',
'rcnote'                         => "Áwa o nsé {{PLURAL:$1|ezalí mbóngwana '''1''' ya nsúka|izalí mbóngwana '''$1''' ya nsúka}} o {{PLURAL:$2|mokɔlɔ|mikɔlɔ '''$2'''}} ya nsúka, o ntángo $5 o mokɔlɔ $4.",
'rcshowhideminor'                => '$1 mbóngwana ya mokɛ́',
'rcshowhidebots'                 => '$1 barobot',
'rcshowhideliu'                  => '$1 basáleli bamíkitólí',
'rcshowhideanons'                => '$1 basáleli sóngóló',
'rcshowhidemine'                 => '$1 mbóngwana ya ngáí',
'rclinks'                        => 'Komɔ́nisa mbóngwana $1 ya nsúka o mikɔ́lɔ $2<br />$3',
'diff'                           => 'mbó.',
'hist'                           => 'mokóló',
'hide'                           => 'kobomba',
'show'                           => 'Komɔ́nisa',
'minoreditletter'                => 'm',
'newpageletter'                  => 'S',
'boteditletter'                  => 'b',
'rc-enhanced-expand'             => 'Komɔ́nisa ndámbo-ndámbo (esengélí JavaScript)',

# Recent changes linked
'recentchangeslinked'         => 'Bolandi ekangisi',
'recentchangeslinked-feed'    => 'Bolandi ekangisi',
'recentchangeslinked-toolbox' => 'Bolandi ekangisi',
'recentchangeslinked-title'   => 'Mbóngwana ikangání na « $1 »',
'recentchangeslinked-page'    => 'Nkómbó ya lonkásá :',

# Upload
'upload'        => 'Kotíya kásá yɔ̌kɔ́',
'uploadbtn'     => 'Kotíya nkásá mɔ̌kɔ́',
'uploadlogpage' => 'Zuluná ya botómbisi likoló',
'savefile'      => 'kobómbisa kásá-kásá',
'uploadedimage' => '« [[$1]] » etómbísámí likoló',

# Special:ListFiles
'listfiles_date' => 'Mokɔlɔ',

# File description page
'file-anchor-link'          => 'Elilingi',
'filehist'                  => 'Mokóló mwa kásá',
'filehist-current'          => 'ya sikáwa',
'filehist-datetime'         => 'Mokɔlɔ mpé ntángo',
'filehist-thumb'            => 'Miniátilɛ',
'filehist-thumbtext'        => 'Miniátilɛ ya versió ya $1',
'filehist-user'             => 'Mosáleli',
'filehist-dimensions'       => 'Dimasió',
'filehist-comment'          => 'Ntembe',
'imagelinks'                => 'Bikangisele  bya kásá',
'uploadnewversion-linktext' => 'Kotómbisa likoló kásá eye lisúsu',

# File deletion
'filedelete-submit' => 'Kolímwisa',

# Unused templates
'unusedtemplates' => 'Bimekoli na mosálá tɛ̂',

# Random page
'randompage' => 'Lonkásá na mbɛsɛ',

# Statistics
'statistics' => 'Mitúya',

'disambiguations' => 'Bokokani',

'doubleredirects' => 'Boyendisi mbala míbalé',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|okté|baokté}}',
'nmembers'                => '{{PLURAL:$1|ekakoli|bikakoli}} $1',
'uncategorizedpages'      => 'Nkásá izángí loléngé',
'uncategorizedcategories' => 'Ndéngé izángí loléngé',
'uncategorizedimages'     => 'Bilílí bizángí loléngé',
'uncategorizedtemplates'  => 'Bimekoli bizángí loléngé',
'unusedcategories'        => 'Ndéngé na mosálá tɛ̂',
'prefixindex'             => 'Nkásá yɔ́nsɔ na libandi...',
'shortpages'              => 'Nkásá ya mokúsé',
'longpages'               => 'Nkásá ya molaí',
'newpages'                => 'Ekakoli ya sika',
'newpages-username'       => 'Nkómbó ya mosáleli :',
'move'                    => 'Kobóngola nkómbó',
'movethispage'            => 'Kobóngola nkómbó ya lonkásá loye',

# Book sources
'booksources-go' => 'Kɛndɛ́',

# Special:Log
'log' => 'Bapasɔ́',

# Special:AllPages
'allpages'       => 'Nkásá ínsɔ',
'alphaindexline' => '$1 kina $2',
'nextpage'       => 'Lonkásá ya nsima ($1)',
'prevpage'       => 'Lonkasá o libosó ($1)',
'allarticles'    => 'Nkásá ínsɔ',
'allpagesprev'   => '< ya libosó',
'allpagesnext'   => 'bolɛngɛli >',
'allpagessubmit' => 'kokɛndɛ',

# Special:Categories
'categories' => 'Ndéngé',

# Special:Log/newusers
'newuserlogpage'          => 'Zuluná ya bokeli bwa konti ya mosáleli',
'newuserlog-create-entry' => 'Konti ya mosáleli ya sika',

# E-mail user
'emailuser'       => 'Kotíndela yě mɛ́lɛ',
'defemailsubject' => '{{SITENAME}} mokánda',
'emailfrom'       => 'útá',
'emailto'         => 'epái',
'emailmessage'    => 'Nsango',
'emailsend'       => 'kotínda',
'emailsent'       => 'nkandá etíndámá',
'emailsenttext'   => 'Nkandá ya yɔ̌ etíndámá',

# Watchlist
'watchlist'         => 'Nkásá nalandí',
'mywatchlist'       => 'Nkásá nalandí',
'addedwatch'        => 'Ebakísámí na nkásá olandí',
'addedwatchtext'    => "Lonkásá « [[:$1]] » lobakísámí na [[Special:Watchlist|nkásá olandí]]. Mbóngwana o lonkásá loye mpé o lonkásá la ntembe ikomɔ́nisama áwa, ikokɔ́mana '''mbinga''' o [[Special:RecentChanges|Lístɛ ya mbóngwana ya nsúka]] mpɔ̂ na mpási tɛ̂.",
'removedwatch'      => 'Elongólámí na nkásá olandí',
'watch'             => 'Kolanda',
'watchthispage'     => 'Kolanda lonkásá óyo',
'unwatch'           => 'Kolanda tɛ́',
'watchlist-details' => '{{PLURAL:$1|Lonkásá $1 elandámí|Nkásá $1 bilandámí}}, longola nkásá ya ntembe.',
'wlnote'            => "Áwa o nsé {{PLURAL:$1|ezalí mbóngwana ya nsúka|izalí mbóngwana '''$1''' ya nsúka}} o {{PLURAL:$2|ngonga|ngonga '''$2'''}} ya nsúka.",
'wlshowlast'        => 'Komɔ́nisa ngónga $1 ya nsúka, mikɔ́lɔ $2 mya nsúka tǒ $3',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Kolanda...',
'unwatching' => 'Kolanda tɛ́...',

'created' => 'ekomákí',

# Delete
'deletepage'            => 'Kolímwisa lonkásá loye',
'deletedarticle'        => 'elímwísí "[[$1]]"',
'dellogpage'            => 'zuluná ya bolímwisi',
'deletionlog'           => 'zuluná ya bolímwisi',
'deletecomment'         => 'Ntína :',
'deleteotherreason'     => 'Ntína káka tǒ esúsu :',
'deletereasonotherlist' => 'Ntína esúsu',

# Rollback
'rollbacklink' => 'kolíngola',

# Protect
'protectlogpage'              => 'Zuluná ya mbátela',
'protectcomment'              => 'Ntína :',
'protectexpiry'               => 'Esílí :',
'protect_expiry_invalid'      => 'Ntángo ya bosílisi ebɔngɛ́lí tɛ̂',
'protect_expiry_old'          => 'Ntángo ya bosílisi ezalí o ntángo elékákí',
'protect-default'             => 'Kolingisa basáleli bánsɔ',
'protect-fallback'            => 'Esengélí ndingisa « $1 »',
'protect-level-autoconfirmed' => 'Kotɛ́lɛmisa basáleli bamíkomísí tɛ́ tǒ ya sika',
'protect-level-sysop'         => 'Káka bayángeli',
'protect-summary-cascade'     => 'bobáteli ya kokitana',
'protect-expiring'            => 'Esílí o $1 (UTC)',
'restriction-type'            => 'Ndingisa :',
'restriction-level'           => 'nivó ya bondimi ndámbo',

# Restrictions (nouns)
'restriction-edit' => 'Kobɔngisa',
'restriction-move' => 'Kobóngola nkómbó',

# Undelete
'undeletelink'     => 'komɔnisa / kozóngisa',
'undeletedarticle' => 'Ezóngísí « [[$1]] »',

# Namespace form on various pages
'namespace'      => 'Ntáká ya nkómbó :',
'blanknamespace' => '(Ya libosó)',

# Contributions
'contributions'       => 'Mosálá mwa mosáleli óyo',
'contributions-title' => 'Mosálá mwa mosáleli $1',
'mycontris'           => 'Nkásá nakomí',
'contribsub2'         => 'Mpɔ̂ na $1 ($2)',
'month'               => 'Bandá sánzá (mpé yangó ilekí) :',
'year'                => 'Bandá mobú (mpé myangó milekí) :',

'sp-contributions-blocklog' => 'zuluná ya botɛ́lɛmisi',
'sp-contributions-talk'     => 'Ntembe',
'sp-contributions-submit'   => 'Boluki',

# What links here
'whatlinkshere'            => 'Ekangísí áwa',
'whatlinkshere-title'      => 'Nkásá ikangísí na « $1 »',
'whatlinkshere-page'       => 'Lonkásá:',
'isredirect'               => 'Lonkásá la boyendisi',
'whatlinkshere-links'      => '← bikangiseli',
'whatlinkshere-hideredirs' => '$1 mayendisi',
'whatlinkshere-hidetrans'  => '$1 botíyi na káti',
'whatlinkshere-hidelinks'  => '$1 bikangiseli',
'whatlinkshere-filters'    => 'Bikɔngɔlɛlɛ',

# Block/unblock
'blockip'                  => 'Kotɛ́lɛmisa mosáleli',
'ipboptions'               => 'ngonga 2:2 hours, mokɔlɔ 1:1 day, mikɔlɔ 3:3 days,mpɔ́sɔ 1:1 week,mpɔ́sɔ 2:2 weeks,sánzá 1:1 month,sánzá 3:3 months,sánzá 6:6 months,mobú 1:1 year,na nsúka tɛ̂:infinite',
'ipblocklist'              => 'Adɛ́lɛsɛ IP mpé basáleli batɛ́lɛmísámí',
'blocklink'                => 'kotɛ́lɛmisa',
'unblocklink'              => 'koboma botɛ́lɛmisi',
'change-blocklink'         => 'kobóngola botɛ́lɛmisi',
'contribslink'             => 'bíteni ya mosálá',
'blocklogpage'             => 'Zuluná ya botɛ́lɛmisi',
'blocklogentry'            => '[[$1]] atɛ́lɛ́mísámí ; bosílisi : $2 $3',
'unblocklogentry'          => 'ebomí botɛ́lɛmisi $1',
'block-log-flags-nocreate' => 'bokeli bwa konti botendísámí',

# Move page
'movearticle'             => 'Kobóngola nkómbó ya ekakoli :',
'move-watch'              => 'Kolánda lonkásá la líziba mpé lonkásá la tíndamelo',
'movepagebtn'             => 'Kobóngola lonkásá',
'movedto'                 => 'nkómbó ya sika',
'movelogpage'             => 'Zuluná ya bobóngoli nkómbó',
'movereason'              => 'Ntína :',
'revertmove'              => 'kozóngela',
'delete_and_move'         => 'Kolímwisa mpé kobóngola nkómbó',
'delete_and_move_confirm' => 'Boye, kolímwisa lonkásá',
'delete_and_move_reason'  => 'Ntína ya bolímwisi mpé bobóngoli bwa nkómbó',

# Export
'export'        => 'komɛmɛ na...',
'export-submit' => 'Komɛmɛ',

# Thumbnails
'thumbnail-more' => 'Koyéisa monɛ́nɛ',

# Special:Import
'import' => 'koútisa...',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'Lonkásá na ngáí',
'tooltip-pt-mytalk'              => 'Lonkásá ntembe lwa ngáí',
'tooltip-pt-preferences'         => 'Malúli ma ngáí',
'tooltip-pt-watchlist'           => 'Nkásá nalandí mpɔ̂ na mbóngwana',
'tooltip-pt-mycontris'           => 'Molɔngɔ́ mwa nkásá nakomí',
'tooltip-pt-login'               => 'Epésí lilako lya komíkitola yɔ̂, kasi esengélí tê.',
'tooltip-pt-logout'              => 'Kolongwa',
'tooltip-ca-talk'                => 'Ntembe etálí lonkásá lwa nkomá',
'tooltip-ca-edit'                => 'Okokí kokoma lonkásá loye. Ósálela butɔ́ ya botáli-yambo o libóso na kobómbisa.',
'tooltip-ca-history'             => 'Mbóngwana ya kala ya lonkásá loye (na basáleli)',
'tooltip-ca-protect'             => 'Kobátela lonkásá loye',
'tooltip-ca-delete'              => 'Kolímwisa lonkásá loye',
'tooltip-ca-move'                => 'Kobóngola nkómbó ya lonkásá loye',
'tooltip-ca-watch'               => 'Kobakisa na nkásá olandaka',
'tooltip-ca-unwatch'             => 'Kolongola na nkásá olandaka',
'tooltip-search'                 => 'Boluki {{SITENAME}}',
'tooltip-search-go'              => 'Kokɛndɛ na lonkásá na nkómbó óyo sɔ́kí ezalí',
'tooltip-search-fulltext'        => 'Koluka nkásá na nkomá yangó.',
'tooltip-p-logo'                 => 'Lokásá ya libosó',
'tooltip-n-mainpage'             => 'Kokɛndɛ na Lonkásá ya libosó',
'tooltip-n-mainpage-description' => 'Kokɛndɛ na lonkásá ya libosó',
'tooltip-n-portal'               => 'Etalí mwángo moye',
'tooltip-n-recentchanges'        => 'Lístɛ ya mbóngwana ya nsúka o wiki',
'tooltip-n-randompage'           => 'Tómbisa lonkásá na mbɛsɛ',
'tooltip-n-help'                 => 'Esíká ya bosálisi',
'tooltip-t-whatlinkshere'        => 'Lístɛ ya nkásá wiki nyɔ́nsɔ iye ikangísí áwa',
'tooltip-feed-rss'               => 'Ebale RSS mpɔ̂ na lonkásá loye',
'tooltip-feed-atom'              => 'Ebale Atom mpɔ̂ na lonkásá loye',
'tooltip-t-emailuser'            => 'Kotíndela mosáleli óyo mɛ́lɛ',
'tooltip-t-upload'               => 'Kotíya kásá ebelé',
'tooltip-t-specialpages'         => 'Lístɛ ya nkásá gudi nyɔ́nsɔ',
'tooltip-t-print'                => 'Loléngé la lonkásá loye la kobimisa',
'tooltip-t-permalink'            => 'Ekangisele ya koúmela na versió eye ya lonkánsá',
'tooltip-ca-nstab-main'          => 'Komɔ́nisa káti',
'tooltip-ca-nstab-user'          => 'Komɔ́nisa lonkásá la mosáleli',
'tooltip-ca-nstab-special'       => 'Eye ezalí lonkásá gudi, okokí kobɔngisa eye tɛ̂',
'tooltip-ca-nstab-project'       => 'Komɔ́nisa lonkásá la mwǎngo',
'tooltip-ca-nstab-image'         => 'Komɔ́nisa lonkásá la kásá',
'tooltip-ca-nstab-template'      => 'Komɔ́nisela emekisele',
'tooltip-ca-nstab-category'      => 'Komɔ́nisa lonkásá la katégori',
'tooltip-save'                   => 'Kobómbisa mbóngwana ya yɔ̌',
'tooltip-watch'                  => 'Kobakisa na nkásá olandaka',

# Browsing diffs
'previousdiff' => '← Bobóngoli bwa libosó',

# Special:NewFiles
'ilsubmit' => 'Boluki',

# EXIF tags
'exif-artist' => 'Mokeli',

'exif-subjectdistancerange-2' => 'kokanga view',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'nyɔ́nsɔ',
'namespacesall' => 'Nyɔ́nsɔ',
'monthsall'     => 'nyɔ́nsɔ',

# action=purge
'confirm_purge_button' => 'Nandimi',

# Multipage image navigation
'imgmultigo' => 'Kɛndɛ́!',

# Table pager
'table_pager_limit_submit' => 'kokɛndɛ',

# Watchlist editing tools
'watchlisttools-view' => 'Komɔ́nisela mbóngwana ya ntína',
'watchlisttools-edit' => 'Komɔ́nisela mpé kobóngola nkásá nalandí',
'watchlisttools-raw'  => 'Kobimisela nkásá nalandí (na pɛpɛ)',

# Special:SpecialPages
'specialpages' => 'Nkásá gudi',

# HTML forms
'htmlform-submit'              => 'Kotínda',
'htmlform-selectorother-other' => 'Mosúsu',

# Special:DisableAccount
'disableaccount'        => 'Kotendisa kónti ya mosáleli',
'disableaccount-user'   => 'Nkómbó ya mosáleli :',
'disableaccount-reason' => 'Ntína :',

);
