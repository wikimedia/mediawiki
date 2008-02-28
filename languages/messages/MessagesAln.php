<?php
/** Gheg Albanian (Gegë)
 *
 * @addtogroup Language
 *
 * @author Cradel
 * @author Dardan
 */

$fallback = 'sq';

$messages = array(
# User preference toggles
'tog-underline'               => 'Nënvizoji lidhjet',
'tog-highlightbroken'         => 'Shfaqi lidhjet për në faqe të zbrazëta <a href="" class="new">kështu </a> (ndryshe: kështu<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Drejtoji kryeradhët',
'tog-hideminor'               => 'Mshefi redaktimet e vogla të bame së voni',
'tog-extendwatchlist'         => 'Zgjano listën mbikëqyrëse me i pa të tana ndryshimet përkatëse',
'tog-usenewrc'                => 'Ndryshimet e mëdhaja të bame së voni (JavaScript)',
'tog-numberheadings'          => 'Vetshenjo me numër mbititujt',
'tog-showtoolbar'             => 'Shfaqi veglat për redaktim (JavaScript)',
'tog-editondblclick'          => 'Redaktoji faqet me klikim të dyfishtë (JavaScript)',
'tog-editsection'             => 'Lejoje redaktimin e seksioneve me opcionin [redaktoje]',
'tog-editsectiononrightclick' => 'Lejoje redaktimin e seksioneve tue klikue me të djathtë mbi titull (JavaScript)',
'tog-showtoc'                 => 'Shfaqe përmbajtjen<br />(për faqet me ma shum se 3 tituj)',
'tog-rememberpassword'        => 'Rueje fjalëkalimin në këtë kompjuter',
'tog-editwidth'               => 'Kutia për redaktim ka gjanësi të plotë',
'tog-watchcreations'          => 'Shtoji në listë mbikëqyrëse faqet që i krijoj',
'tog-watchdefault'            => 'Shtoji në listë mbikëqyrëse faqet që i redaktoj',
'tog-watchmoves'              => 'Shtoji në listë mbikëqyrëse faqet që i zhvendosi',
'tog-watchdeletion'           => 'Shtoji në listë mbikëqyrëse faqet që i fshij',
'tog-minordefault'            => 'Shënoji paraprakisht si të vogla të tana redaktimet',
'tog-previewontop'            => 'Vendose parapamjen përpara kutisë redaktuese',
'tog-previewonfirst'          => 'Shfaqe parapamjen në redaktimin e parë',
'tog-nocache'                 => 'Mos ruej kopje të faqeve',
'tog-enotifwatchlistpages'    => 'Njoftomë me email kur ndryshojnë faqet nën mbikëqyrje',
'tog-enotifusertalkpages'     => 'Njoftomë me email kur ndryshon faqja ime e diskutimit',
'tog-enotifminoredits'        => 'Njoftomë me email për redaktime të vogla të faqeve',
'tog-enotifrevealaddr'        => 'Shfaqe adresën time në emailat njoftues',
'tog-shownumberswatching'     => 'Shfaqe numrin e përdoruesve mbikëqyrës',
'tog-fancysig'                => 'Mos e përpuno nënshkrimin për formatim',
'tog-externaleditor'          => 'Përdor program të jashtem për redaktime',
'tog-externaldiff'            => 'Përdor program të jashtem për të tréguar ndryshimét',
'tog-showjumplinks'           => 'Lejo lidhjet é afrueshmerisë "kapërce tek"',
'tog-uselivepreview'          => 'Trego parapamjén meniheré (JavaScript) (Eksperimentale)',
'tog-forceeditsummary'        => 'Pyetem kur e le përmbledhjen e redaktimit zbrazt',
'tog-watchlisthideown'        => "M'sheh redaktimet e mia nga lista mbikqyrëse",
'tog-watchlisthidebots'       => "M'sheh redaktimet e robotave nga lista mbikqyrëse",
'tog-watchlisthideminor'      => "M'sheh redaktimet e vogla nga lista mbikqyrëse",
'tog-ccmeonemails'            => 'Më ço kopje të mesazhevé qi u dërgoj të tjerëve',
'tog-diffonly'                => 'Mos e trego përmbájtjen e fáqes nën ndryshimin',
'tog-showhiddencats'          => "Trego katégoritë e m'shefta",

'underline-always'  => 'gjithmonë',
'underline-never'   => 'kurrë',
'underline-default' => 'sipas shfletuesit',

'skinpreview' => '(Parapamje)',

# Dates
'sunday'        => 'E diel',
'monday'        => 'E háne',
'tuesday'       => 'E márte',
'wednesday'     => 'E mërkure',
'thursday'      => 'E énjte',
'friday'        => 'E prémte',
'saturday'      => 'E shtuné',
'sun'           => 'Diel',
'mon'           => 'Hán',
'tue'           => 'Már',
'wed'           => 'Mër',
'thu'           => 'Énj',
'fri'           => 'Pré',
'sat'           => 'Sht',
'january'       => 'kallnor',
'february'      => 'shkurt',
'march'         => 'mars',
'april'         => 'Prill',
'may_long'      => 'Maj',
'june'          => 'Qershor',
'july'          => 'Korrik',
'august'        => 'Gusht',
'september'     => 'Shtator',
'october'       => 'Tetor',
'november'      => 'Nëntor',
'december'      => 'Dhjetor',
'january-gen'   => 'kallnorit',
'february-gen'  => 'shkurtit',
'march-gen'     => 'marsit',
'april-gen'     => 'prillit',
'may-gen'       => 'majit',
'june-gen'      => 'qershorit',
'july-gen'      => 'korrikut',
'august-gen'    => 'Gusht',
'september-gen' => 'Shtator',
'october-gen'   => 'Tetor',
'november-gen'  => 'Nëntor',
'december-gen'  => 'Dhétor',
'jan'           => 'Jan',

# Bits of text used by many pages
'categories'     => 'Kategori',
'subcategories'  => 'Nën-kategori',
'category-empty' => "''Kjo kategori tashpërtash nuk përmban asnji faqe apo media.''",

'about'          => 'Rreth',
'qbedit'         => 'Redaktoni',
'qbmyoptions'    => 'Opsionet e mia',
'qbspecialpages' => 'Fáqet speciále',
'moredotdotdot'  => 'Ma shumë...',
'mypage'         => 'Fáqja jémé',

# File deletion
'filedelete-reason-otherlist' => 'Arsyje tjera',

# MIME search
'download' => 'shkarkim',

'withoutinterwiki' => 'Artikuj pa lidhje interwiki',

);
