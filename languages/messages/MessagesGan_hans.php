<?php
/** Simplified Gan script (赣语（简体）‎)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Kaganer
 * @author Liangent
 * @author Lokal Profil
 * @author PhiLiP
 * @author Philip <philip.npc@gmail.com>
 * @author Reedy
 * @author Symane
 * @author Urhixidur
 * @author Vipuser
 * @author Xiaomingyan
 */

$fallback = 'zh-hans';

$namespaceNames = array(
	NS_TALK             => '谈詑',
	NS_USER_TALK        => '用户谈詑',
	NS_PROJECT_TALK     => '$1谈詑',
	NS_FILE_TALK        => '文件谈詑',
	NS_MEDIAWIKI_TALK   => 'MediaWiki谈詑',
	NS_TEMPLATE_TALK    => '模板谈詑',
	NS_HELP_TALK        => '帮助谈詑',
	NS_CATEGORY_TALK    => '分类谈詑',
);

$messages = array(
# User preference toggles
'tog-underline' => '下划链接',
'tog-justify' => '对到段落',
'tog-hideminor' => '该朝子𠮶改动弆到𠮶细修改',
'tog-hidepatrolled' => '到个昼子𠮶修改里头弆到巡查过𠮶编辑',
'tog-newpageshidepatrolled' => '到新页清单里头弆到巡查过𠮶页面',
'tog-extendwatchlist' => '增加监视清单来显示全部改动，不净系最晏𠮶',
'tog-usenewrc' => '用强化版最晏𠮶改动（需要JavaScript）',
'tog-numberheadings' => '标题自动编号',
'tog-showtoolbar' => '显示编辑工具栏（JavaScript）',
'tog-editondblclick' => '按两下改吖（JavaScript）',
'tog-editsection' => '可以用[编写]链接来编写个别段落',
'tog-editsectiononrightclick' => '可以按右键来编写只把子段落（JavaScript）',
'tog-showtoc' => '超过三只标题就显到目录',
'tog-rememberpassword' => '到个只电脑记到我𠮶密码（至多$1{{PLURAL:$1|日|日}}）',
'tog-watchcreations' => '拿偶开嘞𠮶页面加到偶𠮶监视列表',
'tog-watchdefault' => '拿偶改嘞𠮶页面加到偶𠮶监视列表',
'tog-watchmoves' => '拿偶动嘞𠮶页面加到偶𠮶监视列表',
'tog-watchdeletion' => '拿偶删撇𠮶页面加到偶𠮶监视列表',
'tog-minordefault' => '全部𠮶编辑设成细修改',
'tog-previewontop' => '到编辑框𠮶上首显示预览',
'tog-previewonfirst' => '头道修改时显示预览',
'tog-nocache' => '停用页面𠮶缓存',
'tog-enotifwatchlistpages' => '偶监视框𠮶页面一有改动发电子邮件到偶',
'tog-enotifusertalkpages' => '偶对话框𠮶页面一有改动发email到偶',
'tog-enotifminoredits' => '有细𠮶改动都要发email到偶',
'tog-enotifrevealaddr' => '通知邮件可话到人听偶𠮶email地址',
'tog-shownumberswatching' => '显示有几多人监视',
'tog-oldsig' => '现有𠮶签名：',
'tog-fancysig' => '搦签名以维基字对待（冇自动连结）',
'tog-externaleditor' => '默认用外部编辑器（专家用𠮶功能，要到倷𠮶电脑上头特别𠮶设置一下）',
'tog-externaldiff' => '默认用外部差异比较器（专家用𠮶功能，要到汝𠮶电脑上头特别𠮶设置下。[//www.mediawiki.org/wiki/Manual:External_editors 别𠮶信息]）',
'tog-showjumplinks' => '启用“跳到”访问链接',
'tog-uselivepreview' => '使用即时预览（JavaScript）（实验中）',
'tog-forceeditsummary' => '冇改动注解时要同偶话',
'tog-watchlisthideown' => '监视列表弆到偶𠮶编辑',
'tog-watchlisthidebots' => '监视列表弆到机器人𠮶编辑',
'tog-watchlisthideminor' => '监视列表弆到细修改',
'tog-watchlisthideliu' => '到监视清单里头弆到登入用户',
'tog-watchlisthideanons' => '到监视清单里头弆到匿名用户',
'tog-watchlisthidepatrolled' => '到监视清单里头弆到巡查过𠮶编辑',
'tog-ccmeonemails' => '偶发email到人家时也发封副本到偶',
'tog-diffonly' => '比较两只版本差异𠮶时间伓显示文章𠮶内容',
'tog-showhiddencats' => '显示弆到𠮶分类',
'tog-norollbackdiff' => '舞吥回退之后略过差别',

'underline-always' => '总归要用',
'underline-never' => '绝伓使用',
'underline-default' => '浏览器默认',

# Font style option in Special:Preferences
'editfont-style' => '编辑区字型样式：',
'editfont-default' => '浏览器预设',
'editfont-monospace' => '固定间距字型',
'editfont-sansserif' => '冇脚字型',
'editfont-serif' => '有脚字型',

# Dates
'sunday' => '礼拜天',
'monday' => '礼拜一',
'tuesday' => '礼拜二',
'wednesday' => '礼拜三',
'thursday' => '礼拜四',
'friday' => '礼拜五',
'saturday' => '礼拜六',
'sun' => '礼拜天',
'mon' => '礼拜一',
'tue' => '礼拜二',
'wed' => '礼拜三',
'thu' => '礼拜四',
'fri' => '礼拜五',
'sat' => '礼拜六',
'january' => '1月',
'february' => '2月',
'march' => '3月',
'april' => '4月',
'may_long' => '5月',
'june' => '6月',
'july' => '7月',
'august' => '8月',
'september' => '9月',
'october' => '10月',
'november' => '11月',
'december' => '12月',
'january-gen' => '一月',
'february-gen' => '二月',
'march-gen' => '三月',
'april-gen' => '四月',
'may-gen' => '五月',
'june-gen' => '六月',
'july-gen' => '七月',
'august-gen' => '八月',
'september-gen' => '九月',
'october-gen' => '十月',
'november-gen' => '十一月',
'december-gen' => '十二月',
'jan' => '1月',
'feb' => '2月',
'mar' => '3月',
'apr' => '4月',
'may' => '5月',
'jun' => '6月',
'jul' => '7月',
'aug' => '8月',
'sep' => '9月',
'oct' => '10月',
'nov' => '11月',
'dec' => '12月',

# Categories related messages
'pagecategories' => '$1只分类',
'category_header' => '“$1”分类里头𠮶文章',
'subcategories' => '亚分类',
'category-media-header' => '“$1”分类里头𠮶媒体',
'category-empty' => '“个只分类有包到任何文章或媒体”',
'hidden-categories' => '{{PLURAL:$1|只隐藏分类|只隐藏分类}}',
'hidden-category-category' => '弆到𠮶分类',
'category-subcat-count' => '{{PLURAL:$2|个只分类净系有下头𠮶细分类。|个只分类有下头𠮶$1只细分类，拢共有$2类。}}',
'category-subcat-count-limited' => '个只类别里头有$1只细类别。',
'category-article-count' => '{{PLURAL:$2|个只分类净系有下头𠮶版本。|个只分类有下头𠮶$1版本，拢共有$2版。}}',
'category-article-count-limited' => '个只类别里头有$1只页面。',
'category-file-count' => '{{PLURAL:$2|个类净系有下头𠮶档案。|个类有下头𠮶$1只档案，拢共有$2只档案。}}',
'category-file-count-limited' => '个只类别里头有$1只档案。',
'listingcontinuesabbrev' => '续',
'index-category' => '做正索引𠮶页面',
'noindex-category' => '冇做索引𠮶页面',

'about' => '关于',
'article' => '文章',
'newwindow' => '（开只新窗口）',
'cancel' => '取消',
'moredotdotdot' => '别𠮶...',
'mypage' => '偶𠮶页面',
'mytalk' => '偶𠮶对话框',
'anontalk' => '个只IP𠮶对话框',
'navigation' => '导航',
'and' => ' 同到',

# Cologne Blue skin
'qbfind' => '寻',
'qbbrowse' => '查看',
'qbedit' => '编写',
'qbpageoptions' => '个页',
'qbpageinfo' => '个页信息',
'qbmyoptions' => '偶𠮶选项',
'qbspecialpages' => '特殊页',
'faq' => 'FAQ',
'faqpage' => 'Project:问得蛮多𠮶问题',

# Vector skin
'vector-action-addsection' => '添主题',
'vector-action-delete' => '删吥',
'vector-action-move' => '移吥',
'vector-action-protect' => '护到',
'vector-action-undelete' => '望下删吥𠮶页面',
'vector-action-unprotect' => '解除保护',
'vector-view-create' => '创建',
'vector-view-edit' => '编辑',
'vector-view-history' => '望下历史',
'vector-view-view' => '读',
'vector-view-viewsource' => '望下原始码',
'actions' => '动作',
'namespaces' => '空间名',
'variants' => '变换',

'errorpagetitle' => '错误',
'returnto' => '回到$1。',
'tagline' => '出自{{SITENAME}}',
'help' => '帮助',
'search' => '寻',
'searchbutton' => '寻',
'go' => '去',
'searcharticle' => '去',
'history' => '文章历史',
'history_short' => '历史',
'updatedmarker' => '最末道浏览后𠮶改动',
'printableversion' => '可打印版本',
'permalink' => '永久链接',
'print' => '打印',
'view' => '眵',
'edit' => '编写',
'create' => '创建',
'editthispage' => '编写个页',
'create-this-page' => '创建个页',
'delete' => '删吥去',
'deletethispage' => '删吥个页',
'undelete_short' => '反删吥$1𠮶修改',
'viewdeleted_short' => '眵$1只拕删吥𠮶版本',
'protect' => '保护',
'protect_change' => '修改',
'protectthispage' => '保护个页',
'unprotect' => '改变保护',
'unprotectthispage' => '改吥个页𠮶保护',
'newpage' => '新文章',
'talkpage' => '谈吖个页',
'talkpagelinktext' => '谈詑',
'specialpage' => '特殊页',
'personaltools' => '个人工具',
'postcomment' => '话滴想法',
'articlepage' => '看吖文章',
'talk' => '谈詑',
'views' => '眵',
'toolbox' => '工具盒',
'userpage' => '眵吖用户页',
'projectpage' => '眵吖项目页',
'imagepage' => '眵吖文件页',
'mediawikipage' => '眵吖消息页',
'templatepage' => '眵吖模板页',
'viewhelppage' => '眵吖帮助页',
'categorypage' => '眵吖分类页',
'viewtalkpage' => '眵吖讨论页',
'otherlanguages' => '别𠮶话',
'redirectedfrom' => '（从$1跳过来）',
'redirectpagesub' => '跳转页',
'lastmodifiedat' => '个页最晏𠮶改动系：$1 $2。',
'viewcount' => '个页拖人眵嘞$1回。',
'protectedpage' => '拖保护页',
'jumpto' => '跳到:',
'jumptonavigation' => '导航',
'jumptosearch' => '寻',
'view-pool-error' => '不过意，个只伺服器到个时间超吥最大负荷。
多伤哩𠮶用户较得去望个页。
想望过个页𠮶话请等多一下。

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => '关于 {{SITENAME}}',
'aboutpage' => 'Project:关于',
'copyright' => '个版𠮶内容系根据$1𠮶条款发布。',
'copyrightpage' => '{{ns:project}}:版权资讯',
'currentevents' => '新出𠮶事',
'currentevents-url' => 'Project:新出𠮶事',
'disclaimers' => '免责声明',
'disclaimerpage' => 'Project:免责声明',
'edithelp' => '编写帮助',
'edithelppage' => 'Help:啷编写文章',
'helppage' => 'Help:说明',
'mainpage' => '封面',
'mainpage-description' => '封面',
'policy-url' => 'Project:政策',
'portal' => '社区',
'portal-url' => 'Project:社区',
'privacy' => '隐私政策',
'privacypage' => 'Project:隐私政策',

'badaccess' => '权限错误',
'badaccess-group0' => '倷𠮶要求冇拖批准。',
'badaccess-groups' => '汝要求𠮶操作单就$1𠮶用户（{{PLURAL:$2|组|组员}}）才扤得正。',

'versionrequired' => '需要$1版𠮶mediawiki',
'versionrequiredtext' => '$1版𠮶mediawiki才用得正个页。参看[[Special:Version|版本页]]。',

'ok' => '做得',
'retrievedfrom' => '版本页 "$1"',
'youhavenewmessages' => '倷有 $1 （$2）.',
'newmessageslink' => '新消息',
'newmessagesdifflink' => '最晏𠮶改动',
'youhavenewmessagesmulti' => '$1 上有倷𠮶新消息',
'editsection' => '编写',
'editold' => '编写',
'viewsourceold' => '眵吖源代码',
'editlink' => '编辑',
'viewsourcelink' => '望吖原码',
'editsectionhint' => '编写段落: $1',
'toc' => '目录',
'showtoc' => '敨开',
'hidetoc' => '收到',
'collapsible-collapse' => '收拢',
'collapsible-expand' => '敨开',
'thisisdeleted' => '眵吖或还原$1？',
'viewdeleted' => '眵吖$1?',
'restorelink' => '$1只拖删吥𠮶版本',
'feedlinks' => '锁定:',
'feed-invalid' => '冇用𠮶锁定类型。',
'feed-unavailable' => '同步订阅源到{{SITENAME}}用伓正',
'site-rss-feed' => '$1𠮶RSS讯息',
'site-atom-feed' => '$1𠮶Atom讯息',
'page-rss-feed' => '"$1"𠮶RSS讯息',
'page-atom-feed' => '"$1" Atom Feed',
'red-link-title' => '$1 （哈冇开始写）',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => '文章',
'nstab-user' => '用户页',
'nstab-media' => '媒体页',
'nstab-special' => '特殊页',
'nstab-project' => '项目页',
'nstab-image' => '文件',
'nstab-mediawiki' => '消息',
'nstab-template' => '模版',
'nstab-help' => '帮助页',
'nstab-category' => '分类',

# Main script and global functions
'nosuchaction' => '冇有个只命令',
'nosuchactiontext' => 'Wiki识别伓到个只URL命令',
'nosuchspecialpage' => '冇有个只特殊页',
'nospecialpagetext' => '<strong>倷要求𠮶特殊页冇有用。</strong>

[[Special:SpecialPages]]上寻得到用得上𠮶特殊页。',

# General errors
'error' => '错误',
'databaseerror' => '数据库错误',
'dberrortext' => '数据库查询语法有错。
可能系软件有错。
最晏𠮶数据库指令系:
<blockquote><tt>$1</tt></blockquote>
来自函数 "<tt>$2</tt>"。
MySQL回到错误 "<tt>$3: $4</tt>"。',
'dberrortextcl' => '数据库查询语法有错。
最晏𠮶数据库指令系:
“$1”
来自函数“$2”。
MySQL回到错误“$3: $4”。',
'laggedslavemode' => '警告：页面可能冇有新近内容。',
'readonly' => '数据库上正锁啰',
'enterlockreason' => '请输入锁到数据库𠮶理由，包括预计几时间解锁',
'readonlytext' => '数据库上嘞锁改伓正，可能佢正维修中，搞正嘞仰上会还原。管理员𠮶解释： $1',
'missing-article' => '资料库冇寻到倷要𠮶版面，「$1」 $2。

通常个系因为修订历史页上头，过时𠮶连结连到删吥𠮶版面咁舞得𠮶。

如果不系咁，倷可能系寻到软件里头𠮶bug。
请记得 URL 𠮶地址，向[[Special:ListUsers/sysop|管理员]]报告。',
'missingarticle-rev' => '（修订#: $1）',
'missingarticle-diff' => '（差异: $1, $2）',
'readonly_lag' => '附属数据库服务器拿缓存更新到主服务器，数据库自动锁到嘞',
'internalerror' => '内部错误',
'internalerror_info' => '内部错误: $1',
'filecopyerror' => '复制伓正档案 "$1" 到 "$2"。',
'filerenameerror' => '重命名伓正档案 "$1" 到 "$2"。',
'filedeleteerror' => '删伓正档案 "$1"。',
'directorycreateerror' => '创建伓正目录 "$1"。',
'filenotfound' => '寻伓到档案 "$1"。',
'fileexistserror' => '文件 "$1" 写伓正进去：佢已存在',
'unexpected' => '伓正常值： "$1"="$2"。',
'formerror' => '错误：交伓正表格',
'badarticleerror' => '个只操作到个页用伓正。',
'cannotdelete' => '拣正𠮶页面或图像“$1”删伓正。（佢可能拕人家删吥哩。）',
'badtitle' => '错误𠮶标题',
'badtitletext' => '所要求𠮶页面标题伓正确，伓存在，跨语言或跨wiki链接。标题错误，佢可能有只或好几只伓合𠮶标题字符。',
'perfcached' => '底下系缓存资料，可能伓系最新𠮶。 A maximum of {{PLURAL:$1|one result is|$1 results are}} available in the cache.',
'perfcachedts' => '底下系缓存资料，佢最晏更新𠮶时间系 $1。 A maximum of {{PLURAL:$4|one result is|$4 results are}} available in the cache.',
'querypage-no-updates' => '个页目前改伓正，佢𠮶资料伓能仰上更新。',
'wrong_wfQuery_params' => '参数错误斢到嘞 wfQuery()<br />
函数： $1<br />
查询： $2',
'viewsource' => '源代码',
'protectedpagetext' => '个页锁到嘞，改伓正。',
'viewsourcetext' => '倷可以眵吖或复制个页𠮶源代码：',
'protectedinterface' => '个页给正嘞软件𠮶界面文本，佢拖锁到怕人乱扤。',
'editinginterface' => "'''Warning:''' You are editing a page which is used to provide interface text for the software.
Changes to this page will affect the appearance of the user interface for other users.
For translations, please consider using [//translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], the MediaWiki localisation project.",
'sqlhidden' => '（SQL 弆到𠮶查询）',
'cascadeprotected' => '个页已拖保护，因为佢拖“联锁保护”𠮶{{PLURAL:$1|一只|几只}}拖保护页包到：
$2',
'namespaceprotected' => "倷冇权编写'''$1'''空间里度𠮶页面。",
'ns-specialprotected' => '编写伓正{{ns:special}}空间𠮶页面。',

# Virus scanner
'virus-unknownscanner' => '不晓得𠮶防病毒:',

# Login and logout pages
'logouttext' => "'''汝退出正哩。'''

接到汝得匿名使用{{SITENAME}}，或[[Special:UserLogin|登入过]]。除非汝删吥浏览器缓存，只把子页面可能会接到话汝系登入状态。",
'welcomecreation' => '== 欢迎, $1! ==

建正哩汝𠮶帐户，莫𫍧记设置 [[Special:Preferences|{{SITENAME}}𠮶个人参数]]。',
'yourname' => '用户名：',
'yourpassword' => '密码：',
'yourpasswordagain' => '输过道密码：',
'remembermypassword' => '记定我𠮶密码（顶多$1{{PLURAL:$1|日|日}}）',
'yourdomainname' => '倷𠮶域名：',
'externaldberror' => '外部验证数据库出错，或倷更新伓正倷𠮶外部帐户。',
'login' => '登入',
'nav-login-createaccount' => '登入/新开只帐户',
'loginprompt' => '要开到cookies才登入得正{{SITENAME}}。',
'userlogin' => '登入/新开只帐户',
'userloginnocreate' => '登入',
'logout' => '退出',
'userlogout' => '退出',
'notloggedin' => '冇登入',
'nologin' => "倷冇得帐户啊？ '''$1'''。",
'nologinlink' => '新开只帐户',
'createaccount' => '新开只帐户',
'gotaccount' => "有喽帐户？ '''$1'''.",
'gotaccountlink' => '登入',
'userlogin-resetlink' => '𫍧记汝𠮶登录信息？',
'createaccountmail' => '通过email',
'createaccountreason' => '原因:',
'badretype' => '倷输𠮶密码伓合。',
'userexists' => '汝输𠮶用户名系人家𠮶，拣过只啰！',
'loginerror' => '登入错误',
'nocookiesnew' => '帐户扤正嘞！测到倷关吥嘞Cookies，麻烦倷开到佢登入过。',
'nocookieslogin' => '个首要用 Cookies 登入，测到倷关吥嘞Cookies，麻烦倷开到佢登入过。',
'noname' => '倷冇输正有效𠮶用户名。',
'loginsuccesstitle' => '登入正嘞',
'loginsuccess' => '倷搦到"$1"𠮶身份登到{{SITENAME}}。',
'nosuchuser' => '个首冇叫"$1"𠮶用户。望吖倷𠮶拼写，要伓建过只新帐户。',
'nosuchusershort' => '个首冇叫"$1"𠮶用户。请望吖倷𠮶拼写。',
'nouserspecified' => '倷要指正一只用户名。',
'wrongpassword' => '倷输𠮶密码错误伓对，请试过吖啰。',
'wrongpasswordempty' => '倷冇输入密码，请试过吖啰。',
'passwordtooshort' => '汝𠮶密码伓佮或短伤哩，佢只少要有$1只字符，哈要同用户名伓共样。',
'mailmypassword' => '拿新密码寄到偶',
'passwordremindertitle' => '{{SITENAME}}𠮶密码提醒',
'passwordremindertext' => '有人（可能系汝，IP位址$1）要我俚搦新𠮶{{SITENAME}} （$4） 𠮶登入密码寄到汝。眼下用户"$2"𠮶密码系"$3"。请仰上就登入同到换吥密码。要系别𠮶人发𠮶请求，或者倷寻回嘞倷𠮶密码，伓想改佢，倷可以嫑搭个只消息，接得用旧密码。临时密码{{PLURAL:$5|一日|$5日}}之内会失效。',
'noemail' => '冇有用户"$1"𠮶email地址。',
'passwordsent' => '新𠮶密码已经寄到用户"$1"𠮶email去喽。收到后请再登入过。',
'blocked-mailpassword' => '倷𠮶IP地址拖封到嘞。用伓正密码复原功能以防乱用。',
'eauthentsent' => '确认email寄到话正𠮶地址去喽。别𠮶email发到个只帐户之前，倷起先要按个封email话𠮶佢系否倷𠮶。',
'throttled-mailpassword' => '$1𠮶钟头前发出嘞密码提醒。怕别𠮶人乱扤，$1𠮶钟头之内就只会发一只密码提醒。',
'mailerror' => '发送email错误: $1',
'acct_creation_throttle_hit' => '对伓住，倷建嘞$1只帐号。倷再建伓正啰。',
'emailauthenticated' => '倷𠮶电子邮件地址到$2 $3拖确认为系有效𠮶。',
'emailnotauthenticated' => '倷𠮶email<strong>哈冇拖认证</strong>。底下𠮶功能都伓会发任何邮件。',
'noemailprefs' => '话正只email来用个只功能',
'emailconfirmlink' => '确认倷𠮶email',
'invalidemailaddress' => '电子邮件地址𠮶格式伓对，请输只对𠮶电子邮件地址或者清吥个只输入框。',
'accountcreated' => '帐户扤正喽',
'accountcreatedtext' => '扤正喽$1𠮶帐户。',
'createaccount-title' => '到{{SITENAME}}创建𠮶帐户',
'createaccount-text' => '有人到{{SITENAME}}用倷𠮶电子邮件地址开设喽只名字系 "$2" 𠮶新帐户（$4），密码系 "$3" 。请倷仰上登录同到修改密码。

要系帐户创建不对𠮶话，倷就莫搭个只消息。',
'loginlanguagelabel' => '语言: $1',

# Change password dialog
'resetpass' => '设过帐户密码',
'resetpass_announce' => '倷系用到临时email𠮶代码登入𠮶。要登正入，倷要到个首设定只新密码:',
'resetpass_header' => '设过密码',
'oldpassword' => '老密码：',
'newpassword' => '新密码：',
'retypenew' => '确认密码:',
'resetpass_submit' => '设定密码同到登入',
'resetpass_success' => '倷𠮶密码改正喽！正帮倷登入...',
'resetpass_forbidden' => '到{{SITENAME}}上改伓正密码',
'resetpass-submit-loggedin' => '设过帐户密码',
'resetpass-submit-cancel' => '取消',

# Edit page toolbar
'bold_sample' => '粗体字',
'bold_tip' => '粗体字',
'italic_sample' => '斜体字',
'italic_tip' => '斜体字',
'link_sample' => '链接标题',
'link_tip' => '内部链接',
'extlink_sample' => 'http://www.example.com 链接标题',
'extlink_tip' => '外部链接（头上加 http://）',
'headline_sample' => '标题文字',
'headline_tip' => '二级标题',
'nowiki_sample' => '到个首扻入非格式文本',
'nowiki_tip' => '扻入非格式文本',
'image_tip' => '扻进文件',
'media_tip' => '档案链接',
'sig_tip' => '倷带时间𠮶签名',
'hr_tip' => '横线 （好生使用）',

# Edit pages
'summary' => '摘要:',
'subject' => '主题/头条:',
'minoredit' => '个系只细修改',
'watchthis' => '眏到个页',
'savearticle' => '存到著',
'preview' => '预览',
'showpreview' => '望吖起',
'showlivepreview' => '即时预览',
'showdiff' => '望吖差别',
'anoneditwarning' => "'''警告:'''倷哈冇登入，个页𠮶编写历史会记到倷𠮶IP。",
'missingsummary' => "'''提示:''' 倷冇提供编写摘要。要系倷再按系保存𠮶话，倷保存𠮶编辑就会冇编辑摘要。",
'missingcommenttext' => '请到底下评论。',
'missingcommentheader' => "''提示:''' 汝𠮶评论冇提供标题。若系汝捺过到{{int:savearticle}}𠮶话，汝保存𠮶编辑就会冇标题。",
'summary-preview' => '摘要预览:',
'subject-preview' => '主题/头条预览:',
'blockedtitle' => '用户封到嘞',
'blockedtext' => "倷𠮶用户名或IP地址拖$1封到嘞。

个道封锁系$1封𠮶。个中原因系''$2''。

* 个回封锁𠮶开始时间系：$8
* 个回封锁𠮶到期时间系：$6
* 对于拖查封𠮶人：$7

倷联系得正$1或别𠮶[[{{MediaWiki:Grouppage-sysop}}|管理员]]，讨论个回封锁。除非倷到倷𠮶[[Special:Preferences|帐号参数设置]]里度设正嘞有效𠮶email，伓然𠮶话倷系用伓正“email到个只用户”𠮶功能。设正嘞有效𠮶email后，个只功能系伓会拖封到𠮶。倷𠮶IP地址系$3，许拖封到𠮶ID系 #$5。请倷到全部𠮶查询里度注明个只地址同／或查封ID。",
'autoblockedtext' => '别𠮶人用过倷𠮶IP地址，故系佢拖自动锁到嘞。封佢𠮶人系$1.
下首系封锁𠮶理由:

:\'\'$2\'\'

* 封锁开始: $8
* 封锁过期: $6

倷联系得正$1或别𠮶[[{{MediaWiki:Grouppage-sysop}}|管理员]]去谈下个道封锁。

注意𠮶系话伓定倷冇"e-mail个只用户"𠮶功能，除非倷到[[Special:Preferences|用户设置]]有只注册email地址，再就系倷冇因为用佢拖封过。

倷𠮶封锁ID系$5。请到查询𠮶时间都要紧标到佢。',
'blockednoreason' => '冇话理由',
'whitelistedittext' => '起先倷要$1才编得正个页。',
'confirmedittext' => '确认嘞email才能编写个页。麻烦用[[Special:Preferences|参数设置]]设置同确认倷𠮶email。',
'nosuchsectiontitle' => '冇个只段落',
'nosuchsectiontext' => '汝试得编写𠮶段落伓存在。',
'loginreqtitle' => '需要登入',
'loginreqlink' => '登入',
'loginreqpagetext' => '倷要$1才眵得正别𠮶页面。',
'accmailtitle' => '密码寄出嘞',
'accmailtext' => "'$1'𠮶密码发到$2嘞。",
'newarticle' => '（新）',
'newarticletext' => '个系只冇拕建立𠮶页面。
要新开个只页面，请到下首𠮶方框里头编写内容（望吖[[{{MediaWiki:Helppage}}|说明]]𠮶细节）。
若系汝伓系特事来到个首，捺吖浏览器𠮶「去还」键即得去还。',
'anontalkpagetext' => "---- ''个系匿名用户𠮶讨论页，话伓定佢哈冇开只帐户。别人单用得正IP地址同佢联系。个只IP地址可能有好几只用户共用。如果倷系匿名用户，觉得个页𠮶内容同倷冇关，欢迎去[[Special:UserLogin|开只新帐户或登入]]，省得同别𠮶匿名用户扤混来。''",
'noarticletext' => '眼下个页哈冇内容，倷可以到别𠮶页面[[Special:Search/{{PAGENAME}}|寻吖个页𠮶标题]]，
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 寻吖有关日志]或[{{fullurl:{{FULLPAGENAME}}|action=edit}} 编写个页]</span>。',
'noarticletext-nopermission' => '眼下个页哈冇内容，汝可以到别𠮶页面[[Special:Search/{{PAGENAME}}|寻吖个页𠮶标题]]，
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 寻吖有关日志]或[{{fullurl:{{FULLPAGENAME}}|action=edit}} 编写个页]</span>。',
'clearyourcache' => "'''注意:''' 保存之后, 倷要清吥浏览器𠮶缓存才眵得正改𠮶内容。 '''Mozilla / Firefox / Safari:''' 按到 ''Shift'' 接到按''刷新''（或按吖''Ctrl-Shift-R''，到苹果Mac上按''Cmd-Shift-R''）；'''IE:''' 按到 ''Ctrl''接到按''刷新''，或按吖''Ctrl-F5''；'''Konqueror:''' 单只要按 ''刷新''；'''Opera:''' 用户要到 ''工具-设置'' 完全𠮶清除缓存。",
'usercssyoucanpreview' => "'''提示:''' 存到前请用'望吖起'来测吖倷𠮶新CSS 。",
'userjsyoucanpreview' => "'''提示:''' 存到前请用'望吖起'来测吖倷𠮶新JS 。",
'usercsspreview' => "'''注意倷单系到预览倷个人𠮶 CSS，内容哈冇保存！'''",
'userjspreview' => "'''注意倷单系到测试／预览倷个人𠮶 JavaScript，内容哈冇保存！'''",
'userinvalidcssjstitle' => "'''警告:''' 冇\"\$1\"𠮶皮肤。请记到自定义𠮶 .css 同 .js 页要用小写。就话，{{ns:user}}:Foo/vector.css 伓等同 {{ns:user}}:Foo/Vector.css。",
'updated' => '（更新正喽）',
'note' => "'''注意:'''",
'previewnote' => "'''请记到个光系预览，内容哈冇保存！'''",
'previewconflict' => '个只预览系上首文字编辑区𠮶内容。倷选择保存𠮶话佢才会保存到。',
'session_fail_preview' => "'''对伓住！个只段落𠮶资料跌吥嘞，偶个俚处理伓正倷𠮶编辑。请试过吖。哈系扤伓正𠮶话，试吖退出后登入过。'''",
'session_fail_preview_html' => "伓过意！相关𠮶程式资料跌吥哩，我俚处理伓正汝𠮶编辑。'''

''个只wiki开放正原HTML码，预览拕弆到以防止JavaScript𠮶攻击。''

'''要系佢系合法编辑𠮶，请较过吖。哈系扤伓正𠮶话，试得[[Special:UserLogout|退出]]后登入过。'''",
'token_suffix_mismatch' => "'''倷𠮶用户端𠮶编辑信毁吥嘞嚸标点符号字符，啖𠮶话倷𠮶编辑就拖拒绝嘞。
个种情况通常系含到好多臭虫、以网络为主𠮶匿名代理服务扤得。'''",
'editing' => '编辑嘚$1',
'editingsection' => '编辑嘚$1 （段落）',
'editingcomment' => '编辑嘚$1 （新段落）',
'editconflict' => '编辑仗: $1',
'explainconflict' => "倷起手编辑之后有人动过个页。
上首𠮶方框显示𠮶系眼下本页𠮶内容。
倷𠮶修改到下底𠮶方框显示。
倷要拿倷𠮶修改并到现存𠮶内容。
'''单只系'''上首方框𠮶内容会等倷按\"存到著\"之后拖保存。",
'yourtext' => '倷编𠮶内容',
'storedversion' => '存到𠮶版本',
'nonunicodebrowser' => "'''警告：倷𠮶浏览器伓兼容Unicode。个度有只办法方便倷安全𠮶编写得正文章：伓系ASCII𠮶字符会到编辑框里度用十六进制编码显到。'''",
'editingold' => "'''警告：倷于今正编写个页𠮶旧版本。
要系倷存到佢𠮶话，个只版本𠮶全部改动会都跌吥去。'''",
'yourdiff' => '差异',
'copyrightwarning' => "请记得到{{SITENAME}}𠮶全部贡献会拖认为系$2之下发出𠮶（望吖$1有别𠮶资料）。要系倷伓想自家𠮶编辑好嚟嚟拖乱扤吥，唉就莫递交。<br />
倷都要话正倷𠮶文字系倷自家写𠮶，或者系公有领域或别𠮶自由资源复制到𠮶。<br />
'''冇任何许可𠮶情况下请莫递交有版权𠮶作品！'''",
'copyrightwarning2' => "请记得别𠮶人编得正、改得正或者删得正倷到{{SITENAME}}𠮶全部贡献。要系倷伓想自家𠮶编辑好嚟嚟拖改吥，唉就莫递交。<br />
倷都要话正倷𠮶文字系倷自家写𠮶，或者系公有领域或别𠮶自由资源复制到𠮶（望吖$1有别𠮶资料）。
'''冇任何许可𠮶情况下请莫递交有版权𠮶作品！'''",
'longpageerror' => "'''错误：倷递交𠮶文字有$1 kilobytes咁长，佢长过最大𠮶$2 kilobytes。存伓正倷递交𠮶文字。'''",
'readonlywarning' => "'''警告: 数据库锁到嘞进行定期修护，眼下倷存伓正倷𠮶改动。倷可以拿佢存到文档再著。'''",
'protectedpagewarning' => "'''警告: 个页拕锁到哩，单就管理员许可权𠮶用户才改得正。'''
下首系供得汝参考𠮶最晏𠮶日志。",
'semiprotectedpagewarning' => "'''注意：'''个页拕锁到哩，单就注册用户编得正。
下首系供得汝参考𠮶最晏𠮶日志。",
'cascadeprotectedwarning' => '警告: 个页已经受保护，单只管理员权限𠮶用户才改得正，因为个页同底下𠮶连锁保护𠮶{{PLURAL:$1|一只|多只}}页面包到嘞:',
'titleprotectedwarning' => "'''警告：个只页锁到哩，需要[[Special:ListGroupRights|指定𠮶权限]]才建立得正。'''
下首系供得汝参考𠮶最晏𠮶日志。",
'templatesused' => '个只页面使用𠮶有{{PLURAL:$1|模板|模板}}:',
'templatesusedpreview' => '个只预览使用𠮶有{{PLURAL:$1|模板|模板}}',
'templatesusedsection' => '个只段落使用𠮶{{PLURAL:$1|模板|模板}}有:',
'template-protected' => '（保护）',
'template-semiprotected' => '（半保护）',
'hiddencategories' => '个只版面系属于$1只隐藏类𠮶成员：',
'edittools' => '<!--个首𠮶文本会到下底𠮶编辑同上传列表里坨显示。 -->',
'nocreatetitle' => '新建页面拖限制',
'nocreatetext' => '个只网站限制新建页面𠮶功能。倷可以回头去编辑有嘞𠮶页面，或者[[Special:UserLogin|登入或新开帐户]]。',
'nocreate-loggedin' => '倷到 {{SITENAME}} 冇权新开页面。',
'permissionserrors' => '权限错误',
'permissionserrorstext' => '根据底下𠮶{{PLURAL:$1|原因|原因}}，倷冇权限去扤:',
'permissionserrorstext-withaction' => '根据下头𠮶{{PLURAL:$1|原因|原因}}，你冇权力去舞$2：',
'recreate-moveddeleted-warn' => "'''警告：汝想建过一只先头拕删吥𠮶页面。'''

汝要想下接得编辑个页𠮶必要性。
为到方便，个页𠮶删除记录已经提供嘚下首：",
'moveddeleted-notice' => '个只版面已经拕删吥喽。
下头提供个只版面𠮶删除日志，以供参考。',
'edit-conflict' => '编辑仗。',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''警告：'''含到𠮶模板尺寸大伤哩。
有滴模板不会拕包到。",
'post-expand-template-inclusion-category' => '模板包到超吥上限𠮶页',
'post-expand-template-argument-warning' => "'''警告：'''个页有只少一只模板参数𠮶尺寸过大。
个滴参数会拕略过。",
'post-expand-template-argument-category' => '含到略过模板参数𠮶页',

# "Undo" feature
'undo-success' => '个只编辑可以拖取销。请检查吖以确定个系倷想扤𠮶，接到保存修改去完成撤销编辑。',
'undo-failure' => '半中𠮶编辑有人挭仗，个只编辑伓可以拖取销。',
'undo-summary' => '取消由[[Special:Contributions/$2|$2]] （[[User talk:$2|对话]]）所修订𠮶 $1',

# Account creation failure
'cantcreateaccounttitle' => '新开伓正帐户',
'cantcreateaccount-text' => "IP 地址伓能 （'''$1'''） 新开帐户。个可能系因为经常有来自倷𠮶学堂或网络供应商 （ISP）故意𠮶破坏扤得。",

# History pages
'viewpagelogs' => '眵吖个页𠮶日志',
'nohistory' => '个页冇修改历史。',
'currentrev' => '眼前𠮶修改版本',
'currentrev-asof' => '到 $1 𠮶眼下改动',
'revisionasof' => '$1𠮶修改版本',
'revision-info' => '$2到$1扤𠮶修订版本',
'previousrevision' => '←之前𠮶修改',
'nextrevision' => '接到𠮶修改→',
'currentrevisionlink' => '眼前𠮶修改',
'cur' => '眼前',
'next' => '之后',
'last' => '之前',
'page_first' => '最早',
'page_last' => '最晏',
'histlegend' => '差异选择: 标到伓同版本𠮶单选键，接到按吖督上𠮶键比较下。<br />
说明: （眼下） 指同目前版本𠮶比较，（之前） 指同之前修改版本𠮶比较，细 = 细修改。',
'history-fieldset-title' => '浏览历史',
'history-show-deleted' => '光系删吥𠮶',
'histfirst' => '最早版本',
'histlast' => '最晏版本',
'historysize' => '（{{PLURAL:$1|1 字节|$1 字节}}）',
'historyempty' => '（空）',

# Revision feed
'history-feed-title' => '修改历史',
'history-feed-description' => '本站个页𠮶修改历史',
'history-feed-item-nocomment' => '$1到$2',
'history-feed-empty' => '要求𠮶页面伓存在。佢可能拖删吥嘞或改嘞名。试吖[[Special:Search|到本站寻]]有关𠮶新页面内容。',

# Revision deletion
'rev-deleted-comment' => '（注释挪吥嘞）',
'rev-deleted-user' => '（用户名挪吥嘞）',
'rev-deleted-event' => '（项目挪吥嘞）',
'rev-deleted-text-permission' => '个页𠮶改动从共用文档挪吥嘞。到[{{fullurl:{{#Special:Log}}/suppress|page={{PAGENAMEE}}}} 删除日志] 里度倷话伓定有详细𠮶资料。',
'rev-deleted-text-view' => '个页𠮶改动从共用文档挪吥嘞。作为本站𠮶管理员，倷查看得正；到[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 删除日志] 里度有详细𠮶资料。',
'rev-delundel' => '显示/弆到',
'rev-showdeleted' => '敨开',
'revisiondelete' => '删除/反删除修改',
'revdelete-nooldid-title' => '冇目标修订',
'revdelete-nooldid-text' => '倷冇话个只操作𠮶目标修改。',
'revdelete-selected' => "'''拣[[:$1]]𠮶$2回修订:'''",
'logdelete-selected' => "'''拣'''$1'''𠮶$2只日志事件:'''",
'revdelete-text' => "'''删吥𠮶改动哈会到页面历史里头显示, 但公众浏览伓正佢𠮶内容。'''

个站别𠮶管理员哈系能眵吖弆到𠮶内容，同到通过同佢一样𠮶界面恢复删除，除非设正嘞附加𠮶限制。",
'revdelete-legend' => '设置可见性𠮶限制',
'revdelete-hide-text' => '弆到修改内容',
'revdelete-hide-image' => '弆到档内容',
'revdelete-hide-name' => '弆到动作同目标',
'revdelete-hide-comment' => '弆到编辑说明',
'revdelete-hide-user' => '弆到编者𠮶用户名/IP',
'revdelete-hide-restricted' => '同样𠮶限制应用到管理员，接到锁定个只界面',
'revdelete-suppress' => '同时压到由操作员同别𠮶用户𠮶资料',
'revdelete-unsuppress' => '移吥恢复正𠮶改动𠮶限制',
'revdelete-log' => '原因:',
'revdelete-submit' => '应用到选正𠮶修改',
'revdelete-success' => "'''修订𠮶可见性设置正喽。'''",
'logdelete-success' => "'''事件𠮶可见性设置正喽。'''",
'revdel-restore' => '改动可见性',
'revdel-restore-deleted' => '删吥𠮶修订版',
'revdel-restore-visible' => '相得到𠮶修订版',
'pagehist' => '文章历史',
'deletedhist' => '删吥𠮶历史',

# History merging
'mergehistory' => '合并页面𠮶历史',
'mergehistory-box' => '合并两只页面𠮶版本：',
'mergehistory-from' => '来𠮶页面：',
'mergehistory-into' => '要去𠮶页面：',
'mergehistory-list' => '合并得正𠮶修改历史',
'mergehistory-go' => '显示合并得正𠮶修改',
'mergehistory-submit' => '合并版本',
'mergehistory-empty' => '冇版本合并得正.',
'mergehistory-no-source' => '冇个只 $1 来𠮶页面。',
'mergehistory-no-destination' => '冇个只 $1 要去𠮶页面。',
'mergehistory-invalid-source' => '来𠮶页面题目要写正。',
'mergehistory-invalid-destination' => '要去𠮶页面题目要写正。',

# Merge log
'mergelog' => '合并记录',
'revertmerge' => '伓合并',

# Diffs
'history-title' => '历史版本𠮶 "$1"',
'lineno' => '第$1行:',
'compareselectedversions' => '比较拣正𠮶版本',
'editundo' => '还原',
'diff-multi' => '{{PLURAL:$2|1只用户|$2只用户}}舞𠮶{{PLURAL:$1|一只中途修改|$1只中途修改}}冇拕显示）',

# Search results
'searchresults' => '寻到𠮶结果',
'searchresults-title' => '对"$1"寻到𠮶结果',
'searchresulttext' => '有关𠮶{{SITENAME}}𠮶更多资料,请参看[[{{MediaWiki:Helppage}}|{{int:help}}]]。',
'searchsubtitle' => "用'''[[:$1]]'''",
'searchsubtitleinvalid' => "用'''$1'''寻",
'toomanymatches' => '返回多伤喽𠮶结果，请试吖用别𠮶词语寻过',
'titlematches' => '文章标题符合',
'notitlematches' => '冇页面同文章标题符合',
'textmatches' => '页面内容符合',
'notextmatches' => '冇页面内容符合',
'prevn' => '前{{PLURAL:$1|$1}}只',
'nextn' => '后{{PLURAL:$1|$1}}只',
'prevn-title' => '头$1只{{PLURAL:$1|结果}}',
'nextn-title' => '后$1只结果',
'shown-title' => '每页显示$1只{{PLURAL:$1|结果}}',
'viewprevnext' => '眵吖（$1 {{int:pipe-separator}} $2） （$3）',
'searchmenu-exists' => "'''个只wiki已有一只叫「[[:$1]]」𠮶页。'''",
'searchmenu-new' => "'''嘚个只wiki上建立「[[:$1]]」页！'''",
'searchhelp-url' => 'Help:说明',
'searchprofile-articles' => '内容页',
'searchprofile-project' => '帮助同得计划页',
'searchprofile-images' => '多媒体',
'searchprofile-everything' => '所有',
'searchprofile-advanced' => '高级',
'searchprofile-articles-tooltip' => '到$1里头寻',
'searchprofile-project-tooltip' => '到$1里头寻',
'searchprofile-images-tooltip' => '寻档案',
'searchprofile-everything-tooltip' => '寻所有内容（包括谈𫍡页）',
'searchprofile-advanced-tooltip' => '到自定名字空间里头寻',
'search-result-size' => '$1 （$2只字）',
'search-result-category-size' => '$1只成员（$2只子分类，$3只档案）',
'search-redirect' => '（重定向 $1）',
'search-section' => '（小节 $1）',
'search-suggest' => '倷系要寻：$1',
'search-interwiki-caption' => '姊妹计划',
'search-interwiki-default' => '$1只结果：',
'search-interwiki-more' => '（更多）',
'searchrelated' => '相关',
'searchall' => '所有',
'showingresults' => '底下从第<b>$2</b>条显示起先𠮶<b>$1</b>条结果:',
'showingresultsnum' => '底下从第<b>$2</b>条显示起先𠮶<b>$3</b>条结果:',
'showingresultsheader' => "'''$4'''𠮶{{PLURAL:$5|第'''$1'''到第'''$3'''只结果|第'''$1 - $2'''只，拢共'''$3'''只结果}}",
'nonefound' => '<strong>注意：</strong>寻伓到往往系因为搜索夹到像“𠮶”或“同”之类𠮶常用字扤得。',
'search-nonefound' => '冇合到𠮶查询结果。',
'powersearch' => '高级寻',
'powersearch-legend' => '高级搜寻',
'powersearch-ns' => '到名子空间里头寻：',
'powersearch-redir' => '重定向𠮶表单',
'powersearch-field' => '寻',
'searchdisabled' => '{{SITENAME}}𠮶搜索功能已经关闭。倷可以用Google寻吖。但系佢𠮶索引可能系早先𠮶。',

# Quickbar
'qbsettings' => '快捷导航条',
'qbsettings-none' => '冇',
'qbsettings-fixedleft' => '左首固定',
'qbsettings-fixedright' => '右首固定',
'qbsettings-floatingleft' => '左首漂移',
'qbsettings-floatingright' => '左首漂移',

# Preferences page
'preferences' => '参数设置',
'mypreferences' => '偶𠮶参数设置',
'prefs-edits' => '编辑数:',
'prefsnologin' => '哈冇登入',
'prefsnologintext' => '汝要<span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} 登入]</span>后才设得正个人参数。',
'changepassword' => '改过密码',
'prefs-skin' => '皮',
'skin-preview' => '（预览）',
'datedefault' => '默认项目',
'prefs-datetime' => '日期同到时间',
'prefs-personal' => '用户介绍',
'prefs-rc' => '最近更改',
'prefs-watchlist' => '监视列表',
'prefs-watchlist-days' => '监视列表显示最久𠮶日数:',
'prefs-watchlist-edits' => '加强版𠮶监视列表显示最多更改数目:',
'prefs-misc' => '杂项',
'saveprefs' => '存到参数',
'resetprefs' => '设过参数',
'prefs-editing' => '编写',
'rows' => '横:',
'columns' => '竖:',
'searchresultshead' => '设置寻到𠮶结果',
'resultsperpage' => '设置寻到𠮶链接数',
'stub-threshold' => '<a href="#" class="stub">细文链接</a>格式门槛:',
'recentchangesdays' => '最近更改中𠮶显示日数:',
'recentchangescount' => '最近更改中𠮶编辑数:',
'savedprefs' => '倷𠮶个人参数设置保存正嘞。',
'timezonelegend' => '时区：',
'localtime' => '当地时区',
'timezoneoffset' => '时差¹',
'servertime' => '服务器时间',
'guesstimezone' => '到浏览器上填',
'allowemail' => '接受别𠮶用户𠮶邮件',
'defaultns' => '默认搜索𠮶名字空间:',
'default' => '默认',
'prefs-files' => '档案',
'youremail' => '电子邮件：',
'username' => '用户名：',
'uid' => '用户ID：',
'yourrealname' => '真名：',
'yourlanguage' => '语言：',
'yourvariant' => '多款内容语言：',
'yournick' => '签名：',
'badsig' => '原始签名错误，请检查HTML。',
'badsiglength' => '花名咁长？佢𠮶长度要少过$1只字符。',
'email' => '电子邮件',
'prefs-help-realname' => '真名系选填𠮶，要系倷填嘞，倷𠮶作品就会标到倷𠮶名字。',
'prefs-help-email' => 'email系选填𠮶，佢可以等汝𫍧记密码𠮶时间寄email告诵汝。',
'prefs-help-email-others' => '汝不公开自家𠮶用户身分也得通过用户页或用户谈𫍡页跟得汝联系。',
'prefs-help-email-required' => '需要电子邮件地址。',

# User rights
'userrights' => '用户权限管理',
'userrights-lookup-user' => '管理用户群',
'userrights-user-editname' => '输入用户名:',
'editusergroup' => '编辑用户群',
'editinguser' => "眼下编辑嘚用户𠮶权限 '''[[User:$1|$1]]''' （[[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]）",
'userrights-editusergroup' => '编辑用户群',
'saveusergroups' => '存储用户群',
'userrights-groupsmember' => '归到:',
'userrights-reason' => '原因:',
'userrights-no-interwiki' => '倷冇得权改吥别𠮶wiki网站上个只用户𠮶权利。',
'userrights-nodatabase' => '冇得个只数据库 $1 或系冇在本地。',

# Groups
'group' => '群:',
'group-autoconfirmed' => '自动确认用户',
'group-bot' => '机器人',
'group-sysop' => '操作员',
'group-bureaucrat' => '行政员',
'group-all' => '（全部）',

'group-autoconfirmed-member' => '自动确认用户',
'group-bot-member' => '机器人',
'group-sysop-member' => '操作员',
'group-bureaucrat-member' => '行政员',

'grouppage-autoconfirmed' => '{{ns:project}}:自动确认用户',
'grouppage-bot' => '{{ns:project}}:机器人',
'grouppage-sysop' => '{{ns:project}}:操作员',
'grouppage-bureaucrat' => '{{ns:project}}:行政员',

# User rights log
'rightslog' => '用户权限日志',
'rightslogtext' => '底下记到用户权限𠮶更改记录。',
'rightslogentry' => '拿 $1 𠮶权限从 $2 改到 $3',
'rightsnone' => '（冇）',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => '编辑个页',

# Recent changes
'nchanges' => '$1道改动',
'recentchanges' => '最晏𠮶改动',
'recentchanges-legend' => '个朝子𠮶更改选项',
'recentchanges-summary' => '跟到个只wiki上𠮶最新改动。',
'recentchanges-feed-description' => '跟到个只 wiki 上集合𠮶最后改动。',
'recentchanges-label-newpage' => '个只编辑会建立只新页',
'recentchanges-label-minor' => '个系只细修改',
'recentchanges-label-bot' => '个只编辑系机器人舞𠮶',
'recentchanges-label-unpatrolled' => '个只编辑冇拕查过',
'rcnote' => "下底系到$4 $5，个'''$2'''日𠮶'''$1'''回改动:",
'rcnotefrom' => "底下系自'''$2'''𠮶更改（顶多显示'''$1'''）:",
'rclistfrom' => '显示自$1后𠮶新改动',
'rcshowhideminor' => '$1细编辑',
'rcshowhidebots' => '$1机器人𠮶编辑',
'rcshowhideliu' => '$1登入用户𠮶编辑',
'rcshowhideanons' => '$1匿名用户𠮶编辑',
'rcshowhidepatr' => '$1检查过𠮶编辑',
'rcshowhidemine' => '$1偶𠮶编辑',
'rclinks' => '显示最晏$2日之内最新𠮶$1回改动。<br />$3',
'diff' => '差异',
'hist' => '历史',
'hide' => '弆到',
'show' => '显示',
'minoreditletter' => '细',
'newpageletter' => '新',
'boteditletter' => '机',
'number_of_watching_users_pageview' => '[$1只监视用户]',
'rc_categories' => '分类界定（用"|"隔开）',
'rc_categories_any' => '任何',
'newsectionsummary' => '/* $1 */ 新段落',
'rc-enhanced-expand' => '显到细节（需要 JavaScript）',
'rc-enhanced-hide' => '弆到细节',

# Recent changes linked
'recentchangeslinked' => '链接页𠮶更改',
'recentchangeslinked-feed' => '链接页𠮶更改',
'recentchangeslinked-toolbox' => '链接页𠮶更改',
'recentchangeslinked-title' => '链接页𠮶改动到 "$1"',
'recentchangeslinked-noresult' => '个段时间𠮶链接页冇更改。',
'recentchangeslinked-summary' => "个只特殊页列出个页连出去页面𠮶最晏改动（或系某只分类𠮶页面）。
[[Special:Watchlist|倷𠮶监视列表]]页面会用'''粗体'''显到。",
'recentchangeslinked-page' => '页面名子：',
'recentchangeslinked-to' => '显示连到拿出来𠮶页面',

# Upload
'upload' => '上传档案',
'uploadbtn' => '上传档案',
'reuploaddesc' => '返回上传列表。',
'uploadnologin' => '冇登入',
'uploadnologintext' => '倷要[[Special:UserLogin|登入]]再上传得正档案。',
'upload_directory_read_only' => '上传目录（$1）伓存在或冇写入权限。',
'uploaderror' => '上传出错',
'uploadtext' => "用下底𠮶表格上传档案。
要眵或要寻先前上传𠮶图像请去[[Special:FileList|图像列表]]，上传同删除会记到[[Special:Log/upload|上传日志]]里度。

要系想扻文件到页面，用得正下底𠮶方式链接:
'''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''',
'''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|alt text]]</nowiki></code>''' 或
'''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' 直接连接到个只文件。",
'upload-permitted' => '容许𠮶文件类型：$1。',
'upload-preferred' => '优先𠮶文件类型：$1。',
'upload-prohibited' => '禁止𠮶文件类型：$1。',
'uploadlog' => '上传日志',
'uploadlogpage' => '上传日志',
'uploadlogpagetext' => '底下系最近上传档𠮶通览表。',
'filename' => '档案名',
'filedesc' => '摘要',
'fileuploadsummary' => '摘要:',
'filestatus' => '版权状态:',
'filesource' => '来源:',
'uploadedfiles' => '上传档案中',
'ignorewarning' => '伓搭警告同存到档案',
'ignorewarnings' => '伓搭所有警告',
'minlength1' => '档案名字至少要有一只字。',
'illegalfilename' => '档案名"$1"有页面标题伓容许𠮶字符。请改吖名再上传过。',
'badfilename' => '档案名已经拖改成"$1"。',
'filetype-badmime' => 'MIME类别"$1"系伓容许𠮶格式。',
'filetype-missing' => '个只档案名称并冇副档名 （就像 ".jpg"）。',
'large-file' => '建议档案𠮶大小伓要超吥$1；本档案大小系$2。',
'largefileserver' => '个只档案要大过服务器配置容允𠮶大小。',
'emptyfile' => '倷上传𠮶档案伓存在。个可能系因为档案名按错嘞。请检查倷系否真𠮶要上传个只档案。',
'fileexists' => '个只档案名已存在。如果倷确定伓正倷系否要改佢，请检查<strong>[[:$1]]</strong>。 [[$1|thumb]]',
'fileexists-extension' => '有嘞只飞像𠮶档名: [[$2|thumb]]
* 上载文档𠮶档名: <strong>[[:$1]]</strong>
* 目前档𠮶档名: <strong>[[:$2]]</strong>
请拣只伓同𠮶名字。',
'fileexists-thumbnail-yes' => "个只档案好像系一只图像𠮶缩小版''（缩图）''。 [[$1|thumb]]
请检查清楚个只档案<strong>[[:$1]]</strong>。
如果检查后𠮶档同原先图像𠮶大小系一样𠮶话，就嫑再上传多一只缩图。",
'file-thumbnail-no' => "个只档案名系以<strong>$1</strong>开头。佢好像一只图像𠮶缩小版''（缩图）''。如果倷有个只图像𠮶完整版，伓然请再改过只档名。",
'fileexists-forbidden' => '个只档案名已存在；请回头并换过只新𠮶名称来上传个只档案。[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => '到共用档案库里度有嘞同名𠮶档案；请回头并换过只新𠮶名称来上传个只档案。[[File:$1|thumb|center|$1]]',
'uploadwarning' => '上传警告',
'savefile' => '保存盘案',
'uploadedimage' => '上传正嘞"[[$1]]"',
'overwroteimage' => '上传正嘞"[[$1]]"𠮶新版本',
'uploaddisabled' => '上传伓正',
'uploaddisabledtext' => '上传伓正文件到{{SITENAME}}。',
'uploadscripted' => '个只档案包到可能会误导网络浏览器错误解释𠮶 HTML 或脚本代码。',
'uploadvirus' => '个只档案有病毒！详情: $1',
'sourcefilename' => '原始档案名:',
'destfilename' => '目标档案名:',
'watchthisupload' => '眏到个页',
'filewasdeleted' => '先前有只同名档案上传后又拖删吥嘞。上传个只档案之前倷非要检查$1。',
'filename-bad-prefix' => "倷上传𠮶档案名系以'''\"\$1\"'''做开头𠮶，通常个种冇意义𠮶名字系数码相机度𠮶自动编排。请到倷𠮶档案拣过只更加有意义𠮶名字。",
'upload-success-subj' => '上传正嘞',

'upload-proto-error' => '协定错误',
'upload-proto-error-text' => '远程上传要求 URL 用 <code>http://</code> 或 <code>ftp://</code> 开头。',
'upload-file-error' => '内部错误',
'upload-file-error-text' => '创建临时档案时服务器出现内部错误。请联系系统管理员。',
'upload-misc-error' => '未知𠮶上传错误',
'upload-misc-error-text' => '上传𠮶时间发生未知𠮶错误。请确认输𠮶系正确同访问得正𠮶 URL，接到试过吖。要系哈有问题，请联系系统管理员。',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => '访问伓正 URL',
'upload-curl-error6-text' => '访问伓正输入𠮶 URL。请检查过个只URL 系否正确，再就系网站𠮶访问系否正常。',
'upload-curl-error28' => '上传超时',
'upload-curl-error28-text' => '站点回应时间过长。请检查个只网站𠮶访问系否正常，过吖再试过。倷可能要等网络伓咁卡𠮶时间再试吖。',

'license' => '授权:',
'license-header' => '授权',
'nolicense' => '冇选定',
'license-nopreview' => '（冇预览用得正）',
'upload_source_url' => '（一只有效𠮶，公开𠮶 URL）',
'upload_source_file' => '（倷电脑𠮶一只档案）',

# Special:ListFiles
'listfiles_search_for' => '按媒体名字寻:',
'imgfile' => '档案',
'listfiles' => '档案列表',
'listfiles_date' => '日期',
'listfiles_name' => '名称',
'listfiles_user' => '用户',
'listfiles_size' => '大细',
'listfiles_description' => '简话',

# File description page
'file-anchor-link' => '文件',
'filehist' => '档案历史',
'filehist-help' => '按到日期／时间去眵吖许时间有过𠮶档案。',
'filehist-deleteall' => '全部删掉',
'filehist-deleteone' => '删吥个只',
'filehist-revert' => '恢复',
'filehist-current' => '眼前',
'filehist-datetime' => '日期／时间',
'filehist-thumb' => '缩图',
'filehist-thumbtext' => '到$1𠮶缩图版本',
'filehist-user' => '用户',
'filehist-dimensions' => '尺寸',
'filehist-filesize' => '档案大细',
'filehist-comment' => '说明',
'imagelinks' => '档案使用',
'linkstoimage' => '底下𠮶$1只页面连结到个只档案：',
'nolinkstoimage' => '冇页面链接到个只档案。',
'sharedupload' => '个只档案来自$1，佢可能到别𠮶项目拕应用。',
'sharedupload-desc-there' => '个只档案来自$1，佢可能拕应用嘚别𠮶项目。
请相吖[$2 档案描述页面]以了解佢𠮶相关资讯。',
'sharedupload-desc-here' => '个只档案来自$1，佢可能拕应用嘚别𠮶项目。
佢𠮶[$2 档案描述页面]显示嘚下头。',
'uploadnewversion-linktext' => '上传个只档案𠮶新版本',

# File reversion
'filerevert' => '恢复$1',
'filerevert-legend' => '恢复档案',
'filerevert-intro' => "眼下倷恢复嘚'''[[Media:$1|$1]]'''到[$4 于$2 $3𠮶版本]。",
'filerevert-comment' => '理由：',
'filerevert-defaultcomment' => '恢复到嘞$1, $2𠮶版本',
'filerevert-submit' => '恢复',
'filerevert-success' => "'''[[Media:$1|$1]]'''恢复到嘞[$4 于$2 $3𠮶版本]。",
'filerevert-badversion' => '个只档案所提供𠮶时间标记并冇早先𠮶本地版本。',

# File deletion
'filedelete' => '删吥 $1',
'filedelete-legend' => '删吥档案',
'filedelete-intro' => "倷正删吥'''[[Media:$1|$1]]'''。",
'filedelete-intro-old' => "倷正删吥'''[[Media:$1|$1]]'''到[$4 $2 $3]𠮶版本。",
'filedelete-comment' => '原因:',
'filedelete-submit' => '删吥',
'filedelete-success' => "'''$1'''删吥嘞。",
'filedelete-success-old' => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\'于 $2 $3 𠮶版本删吥嘞。</span>',
'filedelete-nofile' => "{{SITENAME}}个只网站伓存在'''$1'''。",
'filedelete-nofile-old' => "按到指定属性𠮶情况，个首冇'''$1'''到𠮶版本。",
'filedelete-otherreason' => '别𠮶/附加缘故:',
'filedelete-reason-otherlist' => '别𠮶缘故',
'filedelete-reason-dropdown' => '*常用删除理由
** 侵犯版权
** 档案重复',

# MIME search
'mimesearch' => 'MIME搜索',
'mimesearch-summary' => '个只页面启用档案MIME类型筛检程式。输入：内容类型/子类型，像 <code>image/jpeg</code>。',
'mimetype' => 'MIME 类型:',
'download' => '下载',

# Unwatched pages
'unwatchedpages' => '冇眏到𠮶页面',

# List redirects
'listredirects' => '重定向页面列表',

# Unused templates
'unusedtemplates' => '冇使用𠮶模板',
'unusedtemplatestext' => '个只页面列出模板空间名下底冇拖别𠮶页面使用𠮶页面。删掉个兮模板前请检查别𠮶连到个只模板𠮶页面。',
'unusedtemplateswlh' => '别𠮶链接',

# Random page
'randompage' => '随机文章',
'randompage-nopages' => '个只名字空间冇𠮶页面。',

# Random redirect
'randomredirect' => '随机重定向页面',
'randomredirect-nopages' => '个只名字空间冇重定向页面。',

# Statistics
'statistics' => '数据',
'statistics-header-pages' => '页面数据',
'statistics-header-edits' => '编辑数据',
'statistics-header-views' => '查看数据',
'statistics-header-users' => '用户数据',
'statistics-header-hooks' => '别𠮶数据',
'statistics-articles' => '内容页',
'statistics-pages' => '页面',
'statistics-pages-desc' => 'wiki上头所有页面，包到谈詑页、重定向等',
'statistics-files' => '上载正𠮶档案',
'statistics-edits' => '自从{{SITENAME}}设定𠮶页面编辑数',
'statistics-edits-average' => '每页𠮶平均编辑数',
'statistics-views-total' => '查看𠮶统共数',
'statistics-views-peredit' => '每到编辑查看数',
'statistics-users' => '注册过𠮶[[Special:ListUsers|用户]]',
'statistics-users-active' => '活跃用户',
'statistics-users-active-desc' => '头$1日操作过𠮶用户',
'statistics-mostpopular' => '眵𠮶人最多𠮶页面',

'disambiguations' => '扤清楚页',
'disambiguationspage' => 'Template:扤清楚',
'disambiguations-text' => "底下𠮶页面都有到'''扤清楚页'''𠮶链接, 但系佢俚应当系连到正当𠮶标题。<br />
如果一只页面系链接自[[MediaWiki:Disambiguationspage]]，佢会拖当成扤清楚页。",

'doubleredirects' => '双重重定向页面',
'doubleredirectstext' => '底下𠮶重定向链接到别只重定向页面:',
'double-redirect-fixed-move' => '[[$1]]拕移动正，佢个下拕重定向到[[$2]]。',
'double-redirect-fixer' => '重定向𠮶修正器',

'brokenredirects' => '坏吥𠮶重定向页',
'brokenredirectstext' => '底下𠮶重定向页面指到𠮶系伓存在𠮶页面:',
'brokenredirects-edit' => '编写',
'brokenredirects-delete' => '删吥',

'withoutinterwiki' => '冇语言链接𠮶页面',
'withoutinterwiki-summary' => '底下𠮶页面系冇语言链接到别𠮶语言版本:',
'withoutinterwiki-legend' => '前缀',
'withoutinterwiki-submit' => '显到',

'fewestrevisions' => '改得最少𠮶文章',

# Miscellaneous special pages
'nbytes' => '$1字节',
'ncategories' => '$1只分类',
'nlinks' => '$1只链接',
'nmembers' => '$1只成员',
'nrevisions' => '$1只改动',
'nviews' => '$1回浏览',
'specialpage-empty' => '个只报告𠮶结果系空𠮶。',
'lonelypages' => '孤立𠮶页面',
'lonelypagestext' => '底下页面冇链接到{{SITENAME}}个别𠮶页面。',
'uncategorizedpages' => '冇归类𠮶页面',
'uncategorizedcategories' => '冇归类𠮶分类',
'uncategorizedimages' => '冇归类𠮶文件',
'uncategorizedtemplates' => '冇归类𠮶模版',
'unusedcategories' => '冇使用𠮶分类',
'unusedimages' => '冇使用𠮶图像',
'popularpages' => '热门页面',
'wantedcategories' => '等撰𠮶分类',
'wantedpages' => '等撰𠮶页面',
'mostlinked' => '最多链接𠮶页面',
'mostlinkedcategories' => '最多链接𠮶分类',
'mostlinkedtemplates' => '最多链接𠮶模版',
'mostcategories' => '最多分类𠮶文章',
'mostimages' => '链接最多𠮶图像',
'mostrevisions' => '最常改动𠮶文章',
'prefixindex' => '首码索引',
'shortpages' => '短文章',
'longpages' => '长文章',
'deadendpages' => '脱接页面',
'deadendpagestext' => '下底个页面冇连到{{SITENAME}}𠮶别只页面:',
'protectedpages' => '受保护页面',
'protectedpagestext' => '底下页面已经受保护以防止乱动',
'protectedpagesempty' => '个兮参数下冇页面拖保护到。',
'protectedtitles' => '保护题目',
'listusers' => '用户列表',
'usercreated' => '到$1𠮶$2{{GENDER:$3|建立}}',
'newpages' => '新页面',
'newpages-username' => '用户名:',
'ancientpages' => '老早𠮶页面',
'move' => '移动',
'movethispage' => '移动个页',
'unusedimagestext' => '请注意别𠮶网站直接用得正URL链接到个只图像，故系个首列到𠮶图像可能哈会拖使用。',
'unusedcategoriestext' => '话系话冇拖别𠮶文章或分类采用，但列表𠮶分类页哈系存在。',
'notargettitle' => '冇目标',
'notargettext' => '倷冇指正只功能要用到𠮶对象系页面或用户。',
'pager-newer-n' => '{{PLURAL:$1|更新𠮶 1|更新𠮶 $1}}',
'pager-older-n' => '{{PLURAL:$1|更旧𠮶 1|更旧𠮶 $1}}',

# Book sources
'booksources' => '书籍来源',
'booksources-search-legend' => '寻吖书籍来源',
'booksources-go' => '跳到',
'booksources-text' => '底下系一部分网络书店𠮶链接列表，可以提供到倷要找𠮶书籍𠮶更多资料:',

# Special:Log
'specialloguserlabel' => '用户:',
'speciallogtitlelabel' => '标题:',
'log' => '日志',
'all-logs-page' => '所有日志',
'alllogstext' => '拢共显到全部𠮶日志。倷能选只日志类型、用户名或关联页面缩小显示𠮶范围。',
'logempty' => '日志里头冇符合𠮶项目。',
'log-title-wildcard' => '寻吖个只字开头𠮶标题',

# Special:AllPages
'allpages' => '所有𠮶页面',
'alphaindexline' => '$1到$2',
'nextpage' => '下页（$1）',
'prevpage' => '上页（$1）',
'allpagesfrom' => '显示以个底开始𠮶页面:',
'allpagesto' => '显到下头位置结束𠮶页面：',
'allarticles' => '全部文章',
'allinnamespace' => '全部文章（归$1空间名）',
'allnotinnamespace' => '全部文章（伓归$1空间名）',
'allpagesprev' => '前',
'allpagesnext' => '后',
'allpagessubmit' => '交',
'allpagesprefix' => '以个只开头𠮶页面:',
'allpagesbadtitle' => '提供𠮶页面标题冇用，或有只跨语言或跨wiki𠮶字头。佢可能含到一只或几只字伓合标题。',
'allpages-bad-ns' => '{{SITENAME}}冇名字空间叫"$1"𠮶。',

# Special:Categories
'categories' => '页面分类',
'categoriespagetext' => '下底𠮶分类包到页面或系媒体文件。
[[Special:UnusedCategories|Unused categories]] are not shown here.
Also see [[Special:WantedCategories|wanted categories]].',

# Special:LinkSearch
'linksearch' => '外部连结',
'linksearch-ok' => '寻吖',
'linksearch-line' => '$1连自$2',

# Special:ListUsers
'listusersfrom' => '显示啖样用户条件:',
'listusers-submit' => '显示',
'listusers-noresult' => '寻伓到用户。',

# Special:Log/newusers
'newuserlogpage' => '新开户𠮶人名单',

# Special:ListGroupRights
'listgrouprights-members' => '（成员名单）',

# E-mail user
'mailnologin' => '冇email地址',
'mailnologintext' => '倷要[[Special:UserLogin|登入]] 起同到倷𠮶[[Special:Preferences|参数设置]] 有只有效𠮶email才发得正email到别𠮶用户。',
'emailuser' => '发email到个只用户',
'emailpage' => '发email到用户',
'emailpagetext' => '要系个只用户到佢𠮶参数设置页填哩有效𠮶email位置，下底𠮶表格会寄只信息到个只用户。
倷到倷参数设置填𠮶email位置会显到email𠮶“发信人”个栏，咁样个只用户就回得正倷啰。',
'usermailererror' => 'Mail位置返回错误:',
'defemailsubject' => '{{SITENAME}} 电子邮件',
'noemailtitle' => '冇电子邮件地址',
'noemailtext' => '个只用户哈冇指定正一只有效𠮶email，或者佢伓愿收别𠮶用户𠮶电子邮件。',
'emailfrom' => '发信人',
'emailto' => '收信人',
'emailsubject' => '主题',
'emailmessage' => '消息',
'emailsend' => '发出',
'emailccme' => '拿偶𠮶消息𠮶副本发到偶𠮶邮箱。',
'emailccsubject' => '拿倷𠮶消息复制到 $1: $2',
'emailsent' => 'email发卟嘞',
'emailsenttext' => '倷𠮶email发卟嘞。',

# Watchlist
'watchlist' => '监视列表',
'mywatchlist' => '偶𠮶监视列表',
'watchlistfor2' => '$1𠮶监视列表$2',
'nowatchlist' => '倷𠮶监视列表什哩都冇有。',
'watchlistanontext' => '请$1眵吖或改吖倷𠮶监视列表。',
'watchnologin' => '冇登入',
'watchnologintext' => '倷要[[Special:UserLogin|登入]]起才改得正倷𠮶监视列表。',
'addedwatchtext' => "页面\"[[:\$1]]\" 加到嘞倷𠮶[[Special:Watchlist|监视列表]]。个页同佢𠮶讨论页𠮶全部改动以后都会列到许首，佢会用'''粗体''' 列到[[Special:RecentChanges|最近更改]]让倷更加容易识别。 倷以后要系拿佢到监视列表删卟佢𠮶话，就到导航条点吖“莫眏到”。",
'removedwatchtext' => '页面[[:$1]]到[[Special:Watchlist|倷𠮶监视列表]]删卟哩。',
'watch' => '眏到',
'watchthispage' => '眏到个页',
'unwatch' => '莫眏到',
'unwatchthispage' => '莫眏到个页',
'notanarticle' => '伓系文章',
'watchnochange' => '一径到显示𠮶时间之内，倷眏到𠮶页面冇改动。',
'watchlist-details' => '$1只页面（伓算讨论页） 拖眏到哩',
'wlheader-enotif' => '* 启动嘞email通知功能。',
'wlheader-showupdated' => "* 上回倷眵𠮶页面改动𠮶部分用'''粗体'''显到",
'watchmethod-recent' => '眵吖拖眏到𠮶页面𠮶最近编辑',
'watchmethod-list' => '望吖监视页里头最晏𠮶改动',
'watchlistcontains' => '倷𠮶监视列表包含$1只页面。',
'iteminvalidname' => "页面'$1'出错，无效命名...",
'wlnote' => "下底系最近'''$2'''钟头内𠮶最晏'''$1'''道修改:",
'wlshowlast' => '显示近来$1钟头$2日$3𠮶改动',
'watchlist-options' => '监视清单选项',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => '眏到...',
'unwatching' => '莫眏到...',

'enotif_mailer' => '{{SITENAME}}邮件报告员',
'enotif_reset' => '拿全部文章标成已读',
'enotif_newpagetext' => '个系新开𠮶页面。',
'enotif_impersonal_salutation' => '{{SITENAME}}用户',
'changed' => '改卟嘞',
'created' => '建正嘞',
'enotif_subject' => '{{SITENAME}}有页面 $PAGETITLE拖$PAGEEDITOR $CHANGEDORCREATED',
'enotif_lastvisited' => '眵倷上回访问后𠮶全部改动请去$1。',
'enotif_lastdiff' => '想眵改动请去$1。',
'enotif_anon_editor' => '匿名用户$1',
'enotif_body' => '$WATCHINGUSERNAME先生/小姐倷好，

$CHANGEDORCREATED{{SITENAME}}𠮶 $PAGETITLE 页面已经由$PAGEEDITOR到 $PAGEEDITDATE，请到 $PAGETITLE_URL眵吖目前𠮶版本。

$NEWPAGE
编辑摘要: $PAGESUMMARY $PAGEMINOREDIT
联络个只编辑人: mail: $PAGEEDITOR_EMAIL

本站: $PAGEEDITOR_WIKI 今后伓会通知倷将来𠮶改动，除非接到来到个页。倷也能设过倷全部监视页𠮶通知标记。

{{SITENAME}}通知系统 – 会改卟倷𠮶监视列表设置，请去 {{canonicalurl:{{#special:EditWatchlist}}}}

回馈同到别𠮶说明: {{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage' => '删卟页面',
'confirm' => '确认',
'excontent' => "内容系: '$1'",
'excontentauthor' => '内容系: \'$1\' （唯一𠮶贡献者系"$2"）',
'exbeforeblank' => "拖清空之前𠮶内容系: '$1'",
'exblank' => '页面冇内容',
'delete-confirm' => '删卟"$1"去',
'delete-legend' => '删卟去',
'historywarning' => "'''警告''': 倷要删卟𠮶页面含到$1到{{PLURAL:$1|修订|修订}}𠮶历史",
'confirmdeletetext' => '仰上倷就要永久删卟数据库𠮶一只页面或图像同佢𠮶历史。请确定倷要啖做，哈要晓得佢𠮶后果，更加伓能违反[[{{MediaWiki:Policy-url}}]]。',
'actioncomplete' => '扤正嘞',
'actionfailed' => '操作冇舞正',
'deletedtext' => '"$1"删卟嘞。最晏𠮶删除记录请望$2。',
'dellogpage' => '删除日志',
'dellogpagetext' => '下底系最晏删除𠮶记录列表:',
'deletionlog' => '删除日志',
'reverted' => '恢复到早先𠮶版本',
'deletecomment' => '原因:',
'deleteotherreason' => '别𠮶/附加理由:',
'deletereasonotherlist' => '别𠮶理由',
'deletereason-dropdown' => '*常用删除𠮶理由
** 写𠮶人自家𠮶要求
** 侵犯版权
** 特试破坏',

# Rollback
'rollback' => '还原修改',
'rollback_short' => '还原',
'rollbacklink' => '还原',
'rollbackfailed' => '还原失败',
'cantrollback' => '还原伓正；最末𠮶贡献人系文章𠮶唯一作者。',
'alreadyrolled' => '还原伓正由[[User:$2|$2]] （[[User talk:$2|讨论]]）做𠮶[[$1]]𠮶最晏编写；
别𠮶人编辑过或系恢复嘞个页。

最晏编辑人: [[User:$3|$3]] （[[User talk:$3|讨论]]）。',
'editcomment' => "编辑介绍: \"''\$1''\"。",
'revertpage' => '返回由[[Special:Contributions/$2|$2]] （[[User talk:$2|对话]]）𠮶编辑；恢复到[[User:$1|$1]]𠮶最末一只版本',
'rollback-success' => '返回由$1𠮶编辑；恢复到$2𠮶最末一只版本。',

# Edit tokens
'sessionfailure' => '倷𠮶登入好像有嚸问题，为到防范未然，个只动作拖取消嘞。

请按吖“后退”再试过啰！',

# Protect
'protectlogpage' => '保护日志',
'protectlogtext' => '下底系页面锁定同到解除锁定𠮶列表。请望下[[Special:ProtectedPages|保护页面列表]]来监察目前𠮶页面保护情况。',
'protectedarticle' => '保护正嘞“[[$1]] ”',
'modifiedarticleprotection' => '改变嘞“[[$1]] ” 𠮶保护等级',
'unprotectedarticle' => '撤销保护“[[$1]] ”',
'protect-title' => '保护“$1”中',
'prot_1movedto2' => '[[$1]]移到[[$2]]',
'protect-legend' => '确认保护',
'protectcomment' => '原因:',
'protectexpiry' => '期限:',
'protect_expiry_invalid' => '到期时间无效。',
'protect_expiry_old' => '到期时间已过。',
'protect-text' => "倷到个首能浏览或修改页面'''$1'''𠮶保护级别。",
'protect-locked-blocked' => "倷改伓正拖封锁时𠮶保护级别。下底系'''$1'''现今𠮶保护级别:",
'protect-locked-dblock' => "数据库锁到嘞就改伓正保护级别。下底系'''$1'''现今𠮶保护级别:",
'protect-locked-access' => "倷𠮶权限改伓正保护级别。

下底系'''$1'''现今𠮶保护级别:",
'protect-cascadeon' => '下底𠮶{{PLURAL:$1|一只|多只}}页面含到个页，佢哈启动嘞连锁保护，故系个页也就拖保护到嘞，编伓正。倷能设过个页𠮶保护级别，但系个伓会影响到连锁保护。',
'protect-default' => '（默认）',
'protect-fallback' => '非要“$1”𠮶许可',
'protect-level-autoconfirmed' => '禁止冇注册𠮶用户',
'protect-level-sysop' => '只限操作员',
'protect-summary-cascade' => '联锁',
'protect-expiring' => '$1 （UTC）到期',
'protect-cascade' => '保护个页含到𠮶页面 （连锁保护）',
'protect-cantedit' => '倷改伓正个页𠮶保护程度，因为倷冇搦到编辑授权。',
'protect-expiry-options' => '两个钟头:2 hours,一日:1 day,三日:3 days,一个礼拜:1 week,两个礼拜:2 weeks,一个月:1 month,三个月:3 months,六个月:6 months,一年:1 year,一世:infinite',
'restriction-type' => '权限:',
'restriction-level' => '限制级别:',
'minimum-size' => '最细码子',
'maximum-size' => '最大码子:',
'pagesize' => '（字节）',

# Restrictions (nouns)
'restriction-edit' => '编写',
'restriction-move' => '斢动',
'restriction-create' => '建立',

# Restriction levels
'restriction-level-sysop' => '全保护',
'restriction-level-autoconfirmed' => '半保护',
'restriction-level-all' => '任何等级',

# Undelete
'undelete' => '望吖删卟𠮶页面',
'undeletepage' => '望吖同恢复删卟𠮶页面',
'viewdeletedpage' => '望吖删卟𠮶页面',
'undeletepagetext' => '下底𠮶页面拖删卟嘞，但到档案许首哈系恢复得正𠮶。档案库会定时清理。',
'undeleteextrahelp' => "要恢复艮只页面，请清除全部选择方块接到揿吖 '''''恢复'''''。要恢复选正𠮶版本，就请拣到相应版本前𠮶选择方块接到揿吖 '''''恢复'''''。揿 '''''重设''''' 就会清卟评论文字同到全部𠮶选择方块。",
'undeleterevisions' => '$1版本存正档',
'undeletehistory' => '如果倷要恢复个页，全部𠮶版本都会跟到恢复到修改历史去。如果个页删卟后又有只同名𠮶新页面，拖恢复𠮶版本会系先前𠮶历史，而新页面𠮶如今修改伓会自动复原。',
'undeleterevdel' => '如果最晏𠮶修改拖删卟，啖就扤得反删除进行伓正。要系咁𠮶话，倷就要反选到或反弆到最晏删卟𠮶修改。对于倷冇权限望𠮶修改系恢复伓正𠮶。',
'undeletehistorynoadmin' => '个篇文章删卟嘞。下底𠮶摘要会话原因，删卟之前𠮶全部编写文本同到贡献人𠮶细节资料就管理员望得到。',
'undelete-revision' => '删卟$1由$3（到$2）编写𠮶修改版本:',
'undeleterevision-missing' => '冇用或跌掉𠮶修改版本。话伓定倷碰到只错误𠮶链接，要卟就系个只版本早从存盘恢复或换卟嘞。',
'undelete-nodiff' => '冇寻到以前𠮶版本。',
'undeletebtn' => '恢复',
'undeletelink' => '还原',
'undeleteviewlink' => '望吖',
'undeletereset' => '设过',
'undeletecomment' => '评论:',
'undeletedrevisions' => '$1只修改版本恢复正嘞',
'undeletedrevisions-files' => '$1只修改版本同$2只档案恢复正嘞',
'undeletedfiles' => '$1只档案恢复正嘞',
'cannotundelete' => '反删除伓正；话伓定别𠮶人先倷恢复嘞个只页面。',
'undeletedpage' => "'''$1恢复正嘞'''

望吖[[Special:Log/delete|删除日志]]𠮶删除同恢复记录。",
'undelete-header' => '要查最晏𠮶记录𠮶话请望[[Special:Log/delete|删除日志]]。',
'undelete-search-box' => '寻吖删卟𠮶页面',
'undelete-search-prefix' => '显示以下底开头𠮶页面:',
'undelete-search-submit' => '寻吖',
'undelete-no-results' => '删卟记录冇合到𠮶结果。',
'undelete-filename-mismatch' => '删伓正带到时间标记𠮶档案修订 $1: 档案伓匹配',
'undelete-bad-store-key' => '删伓正带到时间标记𠮶档案修订 $1: 档案删卟之前就跌卟嘞。',
'undelete-cleanup-error' => '删卟冇用𠮶存盘文件 "$1" 时出错。',
'undelete-missing-filearchive' => '数据库冇档案存盘 ID $1 ，故系佢也就到档案存盘恢复伓正。佢话伓定早反删除嘞。',
'undelete-error-short' => '反删除档案𠮶时间出错: $1',
'undelete-error-long' => '反删除档案当中出错:

$1',

# Namespace form on various pages
'namespace' => '空间名:',
'invert' => '反选',
'blanknamespace' => '（主要）',

# Contributions
'contributions' => '用户贡献',
'contributions-title' => '$1𠮶用户贡献',
'mycontris' => '偶𠮶贡献',
'contribsub2' => '$1𠮶贡献 （$2）',
'nocontribs' => '冇寻到合到条件𠮶改动。',
'uctop' => '（头上）',
'month' => '从个月 （或更早）:',
'year' => '从个年 （或更早）:',

'sp-contributions-newbies' => '单显到新用户𠮶贡献',
'sp-contributions-newbies-sub' => '新用户𠮶贡献',
'sp-contributions-blocklog' => '封锁记录',
'sp-contributions-uploads' => '上载',
'sp-contributions-logs' => '日志',
'sp-contributions-talk' => '谈𫍡',
'sp-contributions-userrights' => '用户权限管理',
'sp-contributions-search' => '寻贡献',
'sp-contributions-username' => 'IP地址或用户名：',
'sp-contributions-toponly' => '光显示最晏修订版本𠮶编辑',
'sp-contributions-submit' => '寻',

# What links here
'whatlinkshere' => '有什哩连到个首',
'whatlinkshere-title' => '连到 $1 𠮶页面',
'whatlinkshere-page' => '页面:',
'linkshere' => '下底𠮶页面链接到[[:$1]]：',
'nolinkshere' => '冇页面链接到[[:$1]]。',
'nolinkshere-ns' => '选正𠮶空间名内冇页面链接到[[:$1]]。',
'isredirect' => '重定向页',
'istemplate' => '含到',
'isimage' => '档案连结',
'whatlinkshere-prev' => '先$1只',
'whatlinkshere-next' => '末$1只',
'whatlinkshere-links' => '←链接',
'whatlinkshere-hideredirs' => '$1重定向',
'whatlinkshere-hidetrans' => '$1含到',
'whatlinkshere-hidelinks' => '$1连结',
'whatlinkshere-hideimages' => '$1档案连结',
'whatlinkshere-filters' => '筛滤器',

# Block/unblock
'blockip' => '封到IP地址',
'blockiptext' => '用下底𠮶表格去阻止某一IP𠮶修改权限。除非倷系为到怕佢乱扤，接到非要符合[[{{MediaWiki:Policy-url}}|守则]]𠮶条件下才能啖做。请到下底话只确切原因（比如引用一只拖破坏𠮶页面）。',
'ipadressorusername' => 'IP地址或用户名:',
'ipbexpiry' => '期限:',
'ipbreason' => '原因:',
'ipbreasonotherlist' => '别𠮶原因',
'ipbreason-dropdown' => '*一般𠮶封锁原因
** 紧编写假𠮶内容
** 删卟文章内容
** 乱加外部链接
** 写冇油盐𠮶话
** 吓人／骚扰别𠮶
** 滥用帐号
** 乱起用户名',
'ipbcreateaccount' => '防止开新帐号',
'ipbemailban' => '防止用户发email',
'ipbenableautoblock' => '自动封锁个只用户最晏𠮶IP，同后来佢编写用过𠮶地址',
'ipbsubmit' => '封锁个只地址',
'ipbother' => '别𠮶时间:',
'ipboptions' => '两个钟头:2 hours,一日:1 day,三日:3 days,一个礼拜:1 week,两个礼拜:2 weeks,一个月:1 month,三个月:3 months,六个月:6 months,一年:1 year,一世:infinite',
'ipbotheroption' => '别𠮶',
'ipbotherreason' => '别𠮶／附加原因:',
'ipbhidename' => '封锁日志、活跃封锁列表同用户列表里头弆到用户名',
'badipaddress' => 'IP位置伓对。',
'blockipsuccesssub' => '封锁正嘞',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]]封卟嘞。 <br />望吖[[Special:BlockList|拖封IP列表]]来审过封锁。',
'ipb-edit-dropdown' => '编写封锁原因',
'ipb-unblock-addr' => '解封$1',
'ipb-unblock' => '解封用户名或IP地址',
'ipb-blocklist' => '望吖目前𠮶封禁',
'unblockip' => '解封IP地址',
'unblockiptext' => '用下底𠮶表格去恢复早先拖封𠮶IP𠮶编写权。',
'ipusubmit' => '解封个只地址',
'unblocked' => '[[User:$1|$1]]解封嘞',
'unblocked-id' => '封禁$1拖删卟嘞',
'ipblocklist' => '拕封用户𠮶名单',
'ipblocklist-legend' => '寻吖拖封锁𠮶用户',
'ipblocklist-submit' => '寻',
'infiniteblock' => '伓限期',
'expiringblock' => '$1 $2到期',
'anononlyblock' => '单限制匿名用户',
'noautoblockblock' => '停用自动封锁',
'createaccountblock' => '禁止新开帐户',
'emailblock' => '禁止email',
'ipblocklist-empty' => '封锁列表系空𠮶。',
'ipblocklist-no-results' => '请求𠮶IP地址/用户名冇拖封到。',
'blocklink' => '封到',
'unblocklink' => '解封',
'change-blocklink' => '改动封禁',
'contribslink' => '贡献',
'autoblocker' => '倷同"[[$1]]"共用一只IP，故系倷也拖自动锁到嘞。$1封锁𠮶缘故系"$2"。',
'blocklogpage' => '封锁日志',
'blocklogentry' => '[[$1]]拖封到$3 ，结束时间到$2',
'blocklogtext' => '个系用户封锁同解封操作𠮶日志。拖自动封锁𠮶IP冇列出。请参看[[Special:BlockList|拖封IP地址列表]]。',
'unblocklogentry' => '$1 拖解封嘞',
'block-log-flags-anononly' => '单限制匿名用户',
'block-log-flags-nocreate' => '禁止个只IP/用户新开帐户',
'block-log-flags-noautoblock' => '禁用自动封禁',
'block-log-flags-noemail' => '禁止email',
'range_block_disabled' => '就管理员建得正禁止封锁𠮶范围。',
'ipb_expiry_invalid' => '冇用𠮶结束时间。',
'ipb_already_blocked' => '锁到嘞"$1"',
'ipb_cant_unblock' => '错误: 冇发现Block ID $1。个只IP话伓定拖解封喽。',
'ip_range_invalid' => '冇用𠮶IP范围。',
'blockme' => '封吥偶去',
'proxyblocker' => '代理封锁器',
'proxyblocker-disabled' => '个只功能用伓正喽。',
'proxyblockreason' => '倷𠮶IP系一只公开𠮶代理，佢拖封到嘞。请联络倷𠮶Internet服务提供商或技术帮助再告诵佢俚个只严重𠮶安全问题。',
'proxyblocksuccess' => '扤正啰。',
'sorbsreason' => '{{SITENAME}}用𠮶 DNSBL 查到倷𠮶IP地址系只公开代理服务器。',
'sorbs_create_account_reason' => '{{SITENAME}}用𠮶 DNSBL 检查到倷𠮶IP地址系只公开代理服务器，倷也就新开伓正帐户。',

# Developer tools
'lockdb' => '锁到数据库',
'unlockdb' => '莫锁到数据库',
'lockdbtext' => '锁住数据库将让所有用户编伓正页面、更伓正参数、监视列表同到别𠮶需要改动数据库𠮶操作。请确定倷要啖做，接到要话正等维护工作结束后倷会重新开到数据库。',
'unlockdbtext' => '开到数据库将让所有用户重新编辑得正页面、修改得正参数、编辑得正监视列表同到别𠮶需要改动数据库𠮶操作。请确定倷要啖做。',
'lockconfirm' => '系𠮶，偶系真𠮶想锁定数据库。',
'unlockconfirm' => '系𠮶，偶系真𠮶想解锁数据库。',
'lockbtn' => '锁到数据库',
'unlockbtn' => '莫锁到数据库',
'locknoconfirm' => '倷冇选正确认键。',
'lockdbsuccesssub' => '数据库锁正嘞',
'unlockdbsuccesssub' => '数据库解锁',
'lockdbsuccesstext' => '{{SITENAME}}数据库锁正嘞。 <br />请记得维护正后重新开到数据库。',
'unlockdbsuccesstext' => '{{SITENAME}}数据库重新开放。',
'lockfilenotwritable' => '数据库锁定档案写伓正。要锁定或解锁数据库，需要由网络服务器写进才行。',
'databasenotlocked' => '数据库冇锁正。',

# Move page
'move-page-legend' => '换动页面',
'movepagetext' => "用下底𠮶表格拿一只页面改名，跟到拿佢𠮶历史一齐般到新页面。
旧𠮶页面就系新页𠮶重定向页。
连到旧页面𠮶链接伓会自动更改；
劳烦检查吖双重或坏𠮶重定向链接。
倷有责任确保全部链接会连到指正𠮶页面。

注意如果新页面早就有𠮶话，页面'''伓会'''搬过去，要不新页面就系冇内容或系重定向页，也冇修订历史。
啖就系话必要时倷能等换到新页面之后再又回到旧𠮶页面，跟到倷也覆盖不正目前页面。

'''警告！'''
对一只访问得多𠮶页面啖会系一只重要同关键𠮶改动；
请扤之前了解正佢啖可能𠮶后果。",
'movepagetalktext' => "相关𠮶讨论页会自动同个页一齐搬走，'''除非''':
*新页面有嘞只有内容𠮶讨论页，或
*倷伓选下底𠮶选择方块。
啖倷就非要手工移动或合并页面。",
'movearticle' => '换动页面:',
'movenologin' => '冇登入',
'movenologintext' => '倷要系登记用户接到[[Special:UserLogin|登入]]后才移动得正页面。',
'movenotallowed' => '倷到{{SITENAME}}冇权移动页面。',
'newtitle' => '新标题:',
'move-watch' => '眏到个页',
'movepagebtn' => '换卟个页',
'pagemovedsub' => '移正嘞',
'movepage-moved' => "'''“$1”拖移到“$2”'''",
'articleexists' => '已经有页面叫个只名字，要伓倷拣𠮶名字冇用。请拣过只名字。',
'cantmove-titleprotected' => '倷移伓正一只页面到个只位置，个只新题目已经拖保护起来嘞，新建伓正。',
'talkexists' => '页面本身移动正嘞，但系新标题下底有嘞对话页，所以对话页移伓正。请手工合并两页。',
'movedto' => '移到',
'movetalk' => '移动相关𠮶讨论页',
'movelogpage' => '移动日志',
'movelogpagetext' => '下底系移动嘞𠮶页面列表:',
'movereason' => '原因:',
'revertmove' => '恢复',
'delete_and_move' => '删除跟到移动',
'delete_and_move_text' => '==需要删除==

目标文章"[[:$1]]"存在嘞。为到移动佢，倷要删卟旧页面？',
'delete_and_move_confirm' => '系𠮶，删卟个页',
'delete_and_move_reason' => '为到移动删卟佢',
'selfmove' => '原始标题同目标标题一样，一只页面移伓正到佢自家。',

# Export
'export' => '导出页面',
'exporttext' => '通过XML格式倷能搦特定𠮶页面或一组页面𠮶文本同到佢编辑𠮶历史一齐导出；啖通过"[[Special:Import|导入页面]]"就导入得到别𠮶MediaWiki网站。要导出页面𠮶话，请到下底𠮶文字框写正标题，一行一只标题，再话正倷系否要导出含历史𠮶旧版本，或单就选导出最晏一回编辑𠮶相关内容。

再就系通过链接倷哈导出得正档案，比如倷用得正[[{{#Special:Export}}/{{MediaWiki:Mainpage}}]]导出"[[{{MediaWiki:Mainpage}}]]"页面',
'exportcuronly' => '独今吖𠮶改动，伓系全部𠮶历史。',
'exportnohistory' => "----
'''注意:''' 由于性能𠮶原因，个只表格导出𠮶页面𠮶全部历史都拖禁用。",
'export-submit' => '导出',
'export-addcattext' => '从分类里头加进页面:',
'export-addcat' => '加入',
'export-download' => '提供一只档案去另存',
'export-templates' => '包括模板',

# Namespace 8 related
'allmessages' => '系统消息',
'allmessagesname' => '名字',
'allmessagesdefault' => '默认文字',
'allmessagescurrent' => '眼前𠮶文字',
'allmessagestext' => '个首列到全部制定得正𠮶系统界面。
Please visit [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation] and [//translatewiki.net translatewiki.net] if you wish to contribute to the generic MediaWiki localisation.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:系统界面功能'''关卟嘞（'''\$wgUseDatabaseMessages'''）。",

# Thumbnails
'thumbnail-more' => '放大',
'filemissing' => '寻伓到档案',
'thumbnail_error' => '缩略图冇扤正: $1',
'djvu_page_error' => 'DjVu页超出范围',
'djvu_no_xml' => 'DjVu档案拿伓出XML',
'thumbnail_invalid_params' => '缩略图参数系错𠮶',
'thumbnail_dest_directory' => '建伓正目标目录',

# Special:Import
'import' => '导入页面',
'importinterwiki' => '跨wiki导入',
'import-interwiki-text' => '拣正只wiki同页面标题去导入。修订日期同编辑人会一齐存到。全部𠮶跨 wiki 导入操作会到[[Special:Log/import|导入日志]]记到。',
'import-interwiki-history' => '复制个页𠮶全部历史',
'import-interwiki-submit' => '导入',
'import-interwiki-namespace' => '拿页面移到空间名:',
'import-comment' => '说明:',
'importtext' => '请用 Special:Export 从源 wiki 导出档案，再存到倷𠮶磁盘然后上传到个首。',
'importstart' => '导入页面中...',
'import-revision-count' => '$1只修改',
'importnopages' => '冇导入𠮶页面。',
'importfailed' => '导入伓正: $1',
'importunknownsource' => '不明𠮶源导入类型',
'importcantopen' => '开伓正导入档案',
'importbadinterwiki' => '扤坏𠮶内部wiki链接',
'importnotext' => '空白或冇字',
'importsuccess' => '导进去喽！',
'importhistoryconflict' => '挭过仗𠮶修改历史（之前就话伓定导过个只页面）',
'importnosources' => '跨Wiki导入源冇定义，哈伓准直接𠮶历史上传。',
'importnofile' => '冇上传导入档案。',
'importuploaderrorsize' => '导入文件上传𠮶时间冇扤正。个只文件大伤喽，上传伓正咁大𠮶文件。',
'importuploaderrorpartial' => '导入文件上传𠮶时间冇扤正。个只文件就传喽一滴子。',
'importuploaderrortemp' => '导入文件上传𠮶时间冇扤正。冇寻到临时文件夹。',
'import-parse-failure' => 'XML 导进分析失败',
'import-noarticle' => '冇页面导入！',
'import-nonewrevisions' => '早先𠮶改动全部扤进去喽。',
'xml-error-string' => '$1 位到 $2 行，$3 列 （$4字节）：$5',

# Import log
'importlogpage' => '导入日志',
'importlogpagetext' => '管理员由别𠮶 wiki 导入页面同到佢俚𠮶编辑历史记录。',
'import-logentry-upload' => '通过档案上传导入𠮶[[$1]]',
'import-logentry-upload-detail' => '$1只修改',
'import-logentry-interwiki' => '跨wiki $1',
'import-logentry-interwiki-detail' => '$2𠮶$1只修改',

# Tooltip help for the actions
'tooltip-pt-userpage' => '偶𠮶用户页',
'tooltip-pt-anonuserpage' => '倷编辑本站用𠮶IP对应𠮶用户页',
'tooltip-pt-mytalk' => '偶𠮶对话页',
'tooltip-pt-anontalk' => '对个只IP𠮶编辑𠮶话𠮶事',
'tooltip-pt-preferences' => '偶𠮶参数设置',
'tooltip-pt-watchlist' => '偶𠮶监视列表',
'tooltip-pt-mycontris' => '偶𠮶贡献列表',
'tooltip-pt-login' => '登入系伓强制𠮶，但佢会有蛮多好处',
'tooltip-pt-anonlogin' => '登入系伓强制𠮶，但佢会有蛮多好处',
'tooltip-pt-logout' => '登出',
'tooltip-ca-talk' => '内容页𠮶讨论',
'tooltip-ca-edit' => '倷编得正个页，但劳烦倷望佢一眼起再存到佢。',
'tooltip-ca-addsection' => '开只新𠮶讨论',
'tooltip-ca-viewsource' => '个页已拖保护。但倷能望吖佢𠮶源代码。',
'tooltip-ca-history' => '个页早先𠮶版本',
'tooltip-ca-protect' => '护到个页',
'tooltip-ca-unprotect' => '改吥个页𠮶保护',
'tooltip-ca-delete' => '删卟个页',
'tooltip-ca-undelete' => '拿个页还原到删卟之前𠮶样子',
'tooltip-ca-move' => '移动个页',
'tooltip-ca-watch' => '拿个页加到监视列表',
'tooltip-ca-unwatch' => '拿个页从监视列表移走',
'tooltip-search' => '寻吖{{SITENAME}}',
'tooltip-search-go' => '要系一样𠮶标题存在𠮶话就直接去个一版',
'tooltip-search-fulltext' => '寻个只文字𠮶页面',
'tooltip-p-logo' => '封面',
'tooltip-n-mainpage' => '眵吖封面',
'tooltip-n-mainpage-description' => '眵吖封面',
'tooltip-n-portal' => '对于个只计划，倷能做什哩，又啷做',
'tooltip-n-currentevents' => '提供目前𠮶事𠮶背景',
'tooltip-n-recentchanges' => '列出个只网站该朝子𠮶改动',
'tooltip-n-randompage' => '随机载进一只页面',
'tooltip-n-help' => '求人帮',
'tooltip-t-whatlinkshere' => '列出全部同个页连到𠮶页面',
'tooltip-t-recentchangeslinked' => '从个页连出𠮶全部页面𠮶改动',
'tooltip-feed-rss' => '个页𠮶RSS订阅',
'tooltip-feed-atom' => '个页𠮶Atom订阅',
'tooltip-t-contributions' => '望吖个只用户𠮶贡献',
'tooltip-t-emailuser' => '发封邮件到个只用户',
'tooltip-t-upload' => '上传图像或多媒体文件',
'tooltip-t-specialpages' => '全部特殊页列表',
'tooltip-t-print' => '个只页面𠮶打印版',
'tooltip-t-permalink' => '个只页面𠮶永久链接',
'tooltip-ca-nstab-main' => '望吖内容页',
'tooltip-ca-nstab-user' => '望吖用户页',
'tooltip-ca-nstab-media' => '望吖媒体页',
'tooltip-ca-nstab-special' => '个系只特殊页，倷编佢伓正',
'tooltip-ca-nstab-project' => '望吖计划页',
'tooltip-ca-nstab-image' => '望吖图像页',
'tooltip-ca-nstab-mediawiki' => '望吖系统消息',
'tooltip-ca-nstab-template' => '望吖模板',
'tooltip-ca-nstab-help' => '望吖帮助页',
'tooltip-ca-nstab-category' => '望吖分类页',
'tooltip-minoredit' => '拿佢设成细修改',
'tooltip-save' => '存到倷𠮶修改',
'tooltip-preview' => '预览倷𠮶改动，存到佢之前劳烦啖扤吖！',
'tooltip-diff' => '显出倷对文章𠮶改动。',
'tooltip-compareselectedversions' => '望吖个页两只选定版本之间𠮶伓同之处。',
'tooltip-watch' => '拿个页加到倷𠮶监视列表',
'tooltip-recreate' => '管佢系否会拖删卟都重新扤过个页。',
'tooltip-upload' => '开始上传',
'tooltip-rollback' => '『反转』可以一捺复原头一位贡献者对个版𠮶编辑',
'tooltip-undo' => '『复原』可以到编辑模式里头开只编辑表以便复原。佢容许到摘要里头加进原因。',
'tooltip-summary' => '输入只简要',

# Stylesheets
'common.css' => '/** 个首𠮶CSS会用到全部𠮶皮肤 */',
'monobook.css' => '/* 个首𠮶 CSS 会碍到正用Monobook皮肤𠮶用户 */',

# Scripts
'common.js' => '/* 个首𠮶JavaScript仰上载进到所有用户全部页面。 */',
'monobook.js' => '/* 伓再使用；请用[[MediaWiki:common.js]] */',

# Metadata
'notacceptable' => '个只网站服务器提供伓正倷𠮶用户端认得𠮶格式。',

# Attribution
'anonymous' => '{{SITENAME}}𠮶匿名用户',
'siteuser' => '{{SITENAME}}用户$1',
'anonuser' => '{{SITENAME}}匿名用户$1',
'lastmodifiedatby' => '个页由$3对$1 $2最晏𠮶改动。',
'othercontribs' => '以$1为基础。',
'others' => '别𠮶',
'siteusers' => '{{SITENAME}}用户$1',
'anonusers' => '{{SITENAME}}匿名{{PLURAL:$2|用户|用户}}$1',
'creditspage' => '页面感谢',
'nocredits' => '个页冇致谢名单。',

# Spam protection
'spamprotectiontitle' => '垃圾广告隔离器',
'spamprotectiontext' => '倷想存𠮶页面拖垃圾广告隔离器测到。啖可能系外部链接扤得。',
'spamprotectionmatch' => '下底系触发垃圾广告隔离器𠮶内容: $1',
'spambot_username' => 'MediaWiki 广告清除',
'spam_reverting' => '返回到伓包连到$1最晏𠮶版本',
'spam_blanking' => '全部包含连到$1𠮶改动，留空',

# Patrolling
'markaspatrolleddiff' => '标到系检查过𠮶',
'markaspatrolledtext' => '标到个篇文章系检查过𠮶',
'markedaspatrolled' => '标到系检查过𠮶',
'markedaspatrolledtext' => '选正𠮶版本标到系检查过𠮶。',
'rcpatroldisabled' => '近来修改检查拖关闭',
'rcpatroldisabledtext' => '该朝子改动检查𠮶功能拖关闭嘞。',
'markedaspatrollederror' => '标伓正佢系检查过𠮶',
'markedaspatrollederrortext' => '倷要指正某只版本才标得正佢系检查过𠮶。',
'markedaspatrollederror-noautopatrol' => '倷标伓正倷自家𠮶修改系检查过𠮶。',

# Patrol log
'patrol-log-page' => '巡查记录',

# Image deletion
'deletedrevision' => '删卟嘞旧版本$1。',
'filedeleteerror-short' => '删卟档案出错: $1',
'filedeleteerror-long' => '删卟档案出嘞错:

$1',
'filedelete-missing' => '档案 "$1" 伓存在，所以删佢伓正。',
'filedelete-old-unregistered' => '指正𠮶档案修改 "$1" 数据库里伓存在。',
'filedelete-current-unregistered' => '指正𠮶档案 "$1" 数据库里伓存在。',
'filedelete-archive-read-only' => '存盘目录 "$1" 服务器里写伓正。',

# Browsing diffs
'previousdiff' => '←上一只差异',
'nextdiff' => '下一只差异→',

# Media information
'mediawarning' => "'''警告''': 话伓定个只档案含到恶意代码，执行佢话伓定会损坏倷𠮶系统。",
'imagemaxsize' => '档案解释页𠮶图像大细限制到:',
'thumbsize' => '缩略图大细:',
'widthheightpage' => '$1 × $2,$3页',
'file-info' => '档案大细: $1, MIME 类型: $2',
'file-info-size' => '$1 × $2 像素，档案大细：$3 ，MIME类型：$4',
'file-nohires' => '冇更高分辨率𠮶图像。',
'svg-long-desc' => 'SVG档案，表面大细： $1 × $2 像素，档案大细：$3',
'show-big-image' => '完整分辨率',

# Special:NewFiles
'newimages' => '新建图像画廊',
'imagelisttext' => '底下系按$2排列𠮶$1只档案列表。',
'showhidebots' => '（$1机器人）',
'noimages' => '冇什哩可望。',
'ilsubmit' => '寻',
'bydate' => '按日子',
'sp-newimages-showfrom' => '显示从 $1 起𠮶新文件',

# Bad image list
'bad_image_list' => '请根据下底𠮶格式去写:

会考虑单列到𠮶项目（以*开头𠮶项目）。
头只链接非要连到只坏图。
之后同一行𠮶链接会考虑系特殊，也就系话系幅图都能到哪篇文章同时显示得正。',

# Metadata
'metadata' => '元数据',
'metadata-help' => '个只档案含到额外𠮶信息。咁可能系数码相机或扫描仪扤得。 要系改吥个只档𠮶源档案，佢𠮶资料伓见得会同改过后一样。',
'metadata-expand' => '显到详细资料',
'metadata-collapse' => '弆到详细资料',
'metadata-fields' => '个只信息列到𠮶 EXIF 元数据表会含到图片显示页面里头，要系元数据表扤坏哩就光会显下底𠮶资料，别𠮶元数据会自动弆到。
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

# EXIF tags
'exif-imagewidth' => '阔',
'exif-imagelength' => '高',
'exif-bitspersample' => '每像素byte数',
'exif-compression' => '压缩方法',
'exif-photometricinterpretation' => '像素合成',
'exif-orientation' => '摆放方向',
'exif-samplesperpixel' => '像素数',
'exif-planarconfiguration' => '数据排列',
'exif-ycbcrsubsampling' => '黄色对洋红二次抽样比率',
'exif-ycbcrpositioning' => '黄色同洋红配置',
'exif-xresolution' => '横分辨率',
'exif-yresolution' => '直分辨率',
'exif-stripoffsets' => '图像资料位置',
'exif-rowsperstrip' => '每带行数',
'exif-stripbytecounts' => '每压缩带byte数',
'exif-jpeginterchangeformat' => 'JPEG SOI𠮶偏移量',
'exif-jpeginterchangeformatlength' => 'JPEG𠮶byte数',
'exif-whitepoint' => '白点色度',
'exif-primarychromaticities' => '主要𠮶色度',
'exif-ycbcrcoefficients' => '颜色空间转换矩阵系数',
'exif-referenceblackwhite' => '黑白参照值',
'exif-datetime' => '档案改动日期同时间',
'exif-imagedescription' => '图像标题',
'exif-make' => '相机厂商',
'exif-model' => '相机型号',
'exif-software' => '用𠮶软件',
'exif-artist' => '作者',
'exif-copyright' => '版权人',
'exif-exifversion' => 'Exif版本',
'exif-flashpixversion' => '支持𠮶Flashpix版本',
'exif-colorspace' => '颜色空间',
'exif-componentsconfiguration' => '每部分𠮶意思',
'exif-compressedbitsperpixel' => '图像压缩模式',
'exif-pixelydimension' => '有效图像𠮶阔',
'exif-pixelxdimension' => '有效图像𠮶高',
'exif-usercomment' => '用户摘要',
'exif-relatedsoundfile' => '相关𠮶声气资料',
'exif-datetimeoriginal' => '资料创作时间',
'exif-datetimedigitized' => '数码化𠮶时间',
'exif-subsectime' => '日期时间秒',
'exif-subsectimeoriginal' => '原始日期时间秒',
'exif-subsectimedigitized' => '数码化日期时间秒',
'exif-exposuretime' => '曝光长度',
'exif-exposuretime-format' => '$1 秒 （$2）',
'exif-fnumber' => '光圈（F值）',
'exif-exposureprogram' => '曝光模式',
'exif-spectralsensitivity' => '感光度',
'exif-isospeedratings' => 'ISO速率',
'exif-shutterspeedvalue' => '快门速度',
'exif-aperturevalue' => '光圈',
'exif-brightnessvalue' => '亮度',
'exif-exposurebiasvalue' => '曝光补偿',
'exif-maxaperturevalue' => '最大陆地光圈',
'exif-subjectdistance' => '物距',
'exif-meteringmode' => '测量模式',
'exif-lightsource' => '光源',
'exif-flash' => '闪光灯',
'exif-focallength' => '焦距',
'exif-subjectarea' => '主体区域',
'exif-flashenergy' => '闪光灯强度',
'exif-focalplanexresolution' => '焦平面X轴𠮶分辨率',
'exif-focalplaneyresolution' => '焦平面Y轴𠮶分辨率',
'exif-focalplaneresolutionunit' => '焦平面𠮶分辨率单位',
'exif-subjectlocation' => '主体位置',
'exif-exposureindex' => '曝光指数',
'exif-sensingmethod' => '感光模式',
'exif-filesource' => '档案来源',
'exif-scenetype' => '场景类型',
'exif-customrendered' => '自定义图像处理',
'exif-exposuremode' => '曝光模式',
'exif-whitebalance' => '白平衡',
'exif-digitalzoomratio' => '数码放大比例',
'exif-focallengthin35mmfilm' => '35毫米胶片焦距',
'exif-scenecapturetype' => '场景拍摄类型',
'exif-gaincontrol' => '场景控制',
'exif-contrast' => '对比度',
'exif-saturation' => '饱和度',
'exif-sharpness' => '清晰度',
'exif-devicesettingdescription' => '设备设定描述',
'exif-subjectdistancerange' => '主体距离范围',
'exif-imageuniqueid' => '图像独有ID',
'exif-gpsversionid' => 'GPS定位（tag）版本',
'exif-gpslatituderef' => '南北纬',
'exif-gpslatitude' => '纬度',
'exif-gpslongituderef' => '东西经',
'exif-gpslongitude' => '经度',
'exif-gpsaltituderef' => '海拔参照值',
'exif-gpsaltitude' => '海拔',
'exif-gpstimestamp' => 'GPS时间（原子钟）',
'exif-gpssatellites' => '测量用𠮶卫星',
'exif-gpsstatus' => '接收器状态',
'exif-gpsmeasuremode' => '测量模式',
'exif-gpsdop' => '测量精度',
'exif-gpsspeedref' => '速度单位',
'exif-gpsspeed' => 'GPS接收器速度',
'exif-gpstrackref' => '移动方位参照',
'exif-gpstrack' => '移动方位',
'exif-gpsimgdirectionref' => '图像方位参照',
'exif-gpsimgdirection' => '图像方位',
'exif-gpsmapdatum' => '用𠮶地理测量资料',
'exif-gpsdestlatituderef' => '目标纬度参照',
'exif-gpsdestlatitude' => '目标纬度',
'exif-gpsdestlongituderef' => '目标经度𠮶参照',
'exif-gpsdestlongitude' => '目标经度',
'exif-gpsdestbearingref' => '目标方位参照',
'exif-gpsdestbearing' => '目标方位',
'exif-gpsdestdistanceref' => '目标距离参照',
'exif-gpsdestdistance' => '目标距离',
'exif-gpsprocessingmethod' => 'GPS处理方法名',
'exif-gpsareainformation' => 'GPS区功能变量名',
'exif-gpsdatestamp' => 'GPS日期',
'exif-gpsdifferential' => 'GPS差动修正',

# EXIF attributes
'exif-compression-1' => '冇压缩',

'exif-unknowndate' => '未知𠮶日期',

'exif-orientation-1' => '标准',
'exif-orientation-2' => '左右斢转',
'exif-orientation-3' => '转动180°',
'exif-orientation-4' => '上下翻转',
'exif-orientation-5' => '逆时针转90°接到上下翻转',
'exif-orientation-6' => '顺时针转90°',
'exif-orientation-7' => '顺时针转90°接到上下翻转',
'exif-orientation-8' => '逆时针转90°',

'exif-planarconfiguration-1' => 'chunky格式',
'exif-planarconfiguration-2' => 'planar格式',

'exif-componentsconfiguration-0' => '伓存在',

'exif-exposureprogram-0' => '冇定义',
'exif-exposureprogram-1' => '手动',
'exif-exposureprogram-2' => '标准程式',
'exif-exposureprogram-3' => '光圈优先模式',
'exif-exposureprogram-4' => '快门优先模式',
'exif-exposureprogram-5' => '艺术程式（着重景深）',
'exif-exposureprogram-6' => '运动程式（着重快门速度）',
'exif-exposureprogram-7' => '人像模式（背景朦胧）',
'exif-exposureprogram-8' => '风景模式（聚焦背景）',

'exif-subjectdistance-value' => '$1米',

'exif-meteringmode-0' => '未知',
'exif-meteringmode-1' => '平均水平',
'exif-meteringmode-2' => '中心加权平均测量',
'exif-meteringmode-3' => '单点测',
'exif-meteringmode-4' => '多点测',
'exif-meteringmode-5' => '模式测量',
'exif-meteringmode-6' => '局部测量',
'exif-meteringmode-255' => '别𠮶',

'exif-lightsource-0' => '未知',
'exif-lightsource-1' => '日光灯',
'exif-lightsource-2' => '萤光灯',
'exif-lightsource-3' => '白炽灯',
'exif-lightsource-4' => '闪光灯',
'exif-lightsource-9' => '天晴',
'exif-lightsource-10' => '多云',
'exif-lightsource-11' => '深色调阴影',
'exif-lightsource-12' => '日光萤光灯（色温 D 5700 – 7100K）',
'exif-lightsource-13' => '日温白色萤光灯（N 4600 – 5400K）',
'exif-lightsource-14' => '冷白色萤光灯（W 3900 – 4500K）',
'exif-lightsource-15' => '白色萤光 （WW 3200 – 3700K）',
'exif-lightsource-17' => '标准光A',
'exif-lightsource-18' => '标准光B',
'exif-lightsource-19' => '标准光C',
'exif-lightsource-24' => 'ISO摄影棚钨灯',
'exif-lightsource-255' => '别𠮶光源',

'exif-focalplaneresolutionunit-2' => '英寸',

'exif-sensingmethod-1' => '冇定义',
'exif-sensingmethod-2' => '一只彩色区域感应器',
'exif-sensingmethod-3' => '两只彩色区域感应器',
'exif-sensingmethod-4' => '三只彩色区域感应器',
'exif-sensingmethod-5' => '连续彩色区域感应器',
'exif-sensingmethod-7' => '三线感应器',
'exif-sensingmethod-8' => '连续彩色线性感应器',

'exif-scenetype-1' => '直接照像图片',

'exif-customrendered-0' => '标准程式',
'exif-customrendered-1' => '自定义程式',

'exif-exposuremode-0' => '自动曝光',
'exif-exposuremode-1' => '手动曝光',
'exif-exposuremode-2' => '自动曝光感知调节',

'exif-whitebalance-0' => '自动白平衡',
'exif-whitebalance-1' => '手动白平衡',

'exif-scenecapturetype-0' => '标准',
'exif-scenecapturetype-1' => '风景',
'exif-scenecapturetype-2' => '人像',
'exif-scenecapturetype-3' => '夜景',

'exif-gaincontrol-0' => '冇',
'exif-gaincontrol-1' => '低增益',
'exif-gaincontrol-2' => '高增益',
'exif-gaincontrol-3' => '低减益',
'exif-gaincontrol-4' => '高减益',

'exif-contrast-0' => '标准',
'exif-contrast-1' => '低',
'exif-contrast-2' => '高',

'exif-saturation-0' => '标准',
'exif-saturation-1' => '低饱和度',
'exif-saturation-2' => '高饱和度',

'exif-sharpness-0' => '标准',
'exif-sharpness-1' => '低',
'exif-sharpness-2' => '高',

'exif-subjectdistancerange-0' => '未知',
'exif-subjectdistancerange-1' => '宏观',
'exif-subjectdistancerange-2' => '近景',
'exif-subjectdistancerange-3' => '远景',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => '北纬',
'exif-gpslatitude-s' => '南纬',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => '东经',
'exif-gpslongitude-w' => '西经',

'exif-gpsstatus-a' => '测量过程',
'exif-gpsstatus-v' => '互动测量',

'exif-gpsmeasuremode-2' => '二维测量',
'exif-gpsmeasuremode-3' => '三维测量',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => '公里每小时',
'exif-gpsspeed-m' => '英里每小时',
'exif-gpsspeed-n' => '海浬每小时（节）',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => '真实方位',
'exif-gpsdirection-m' => '地磁方位',

# External editor support
'edit-externally' => '用外部程式来编辑个只档案',
'edit-externally-help' => '请参看[//www.mediawiki.org/wiki/Manual:External_editors 设置步骤]了解别𠮶内容。',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => '全部',
'namespacesall' => '全部',
'monthsall' => '全部',

# E-mail address confirmation
'confirmemail' => '确认email地址',
'confirmemail_noemail' => '倷冇到倷𠮶[[Special:Preferences|用户设置]]设正一只有效𠮶电子邮件地址。',
'confirmemail_text' => '个只网站要求倷用email功能之前确认下倷𠮶email地址。按吖下底𠮶键来发封确认邮件到倷𠮶邮箱。佢会附带一只代码链接；请到倷𠮶浏览器打开个只链接来确认倷𠮶email地址系有效𠮶。',
'confirmemail_pending' => '一只确认代码发到倷𠮶邮箱，啖可能要等几分钟。
要系冇收到，请申请过新𠮶确认码！',
'confirmemail_send' => '寄出确认码',
'confirmemail_sent' => '确认邮件发出嘞。',
'confirmemail_oncreate' => '一只确认码发到倷𠮶邮箱。个只代码伓系话倷要仰上登入，但要系倷想用 wiki 𠮶任何email𠮶相关功能，就非要先提交个只代码。',
'confirmemail_sendfailed' => '发送伓正确认邮件，请检查email地址系否含到伓合字符。

邮件发送人回应: $1',
'confirmemail_invalid' => '无效𠮶确认码，个只代码过嘞期。',
'confirmemail_needlogin' => '倷要$1去确认倷𠮶email地址。',
'confirmemail_success' => '倷𠮶邮箱已得到嘞确认。嘎倷能登得正入同到使用个只网站。',
'confirmemail_loggedin' => '倷𠮶email地址已得到确认。',
'confirmemail_error' => '确认过程出错。',
'confirmemail_subject' => '{{SITENAME}}电子邮件地址确认',
'confirmemail_body' => 'IP地址$1𠮶用户（可能系倷）到{{SITENAME}}注册嘞帐户"$2"，并一同用嘞倷𠮶email地址。

请确认个只帐户系归倷𠮶，接到启动{{SITENAME}}里头𠮶email功能。请到浏览器开到下底𠮶链接:

$3

如果个*伓系*倷，就冇必要打开个只链接。确认码会到$4时间过期。',

# Scary transclusion
'scarytranscludedisabled' => '[跨网站𠮶编码转换用伓正]',
'scarytranscludefailed' => '[对伓住，提取$1失败]',
'scarytranscludetoolong' => '[对伓住，URL 太长]',

# Delete conflict
'deletedwhileediting' => '警告: 倷编辑𠮶时间有人删卟嘞个页！',
'confirmrecreate' => "倷编辑𠮶时间，用户[[User:$1|$1]]（[[User talk:$1|对话]]）因为下底原因删卟嘞个页:
: ''$2''
请想正后再重建页面。",
'recreate' => '重建',

# action=purge
'confirm_purge_button' => '做得',
'confirm-purge-top' => '想清卟个页𠮶缓存?',

# Separators for various lists, etc.
'comma-separator' => '、',
'parentheses' => '（$1）',

# Multipage image navigation
'imgmultipageprev' => '← 上页',
'imgmultipagenext' => '下页 →',
'imgmultigo' => '确定！',

# Table pager
'ascending_abbrev' => '增',
'descending_abbrev' => '减',
'table_pager_next' => '下页',
'table_pager_prev' => '上页',
'table_pager_first' => '首页',
'table_pager_last' => '末页',
'table_pager_limit' => '每页显到$1项',
'table_pager_limit_submit' => '去',
'table_pager_empty' => '冇结果',

# Auto-summaries
'autosumm-blank' => '移卟页面𠮶全部内容',
'autosumm-replace' => "搦页面换到 '$1'",
'autoredircomment' => '重定向到[[$1]]',
'autosumm-new' => '新页: $1',

# Live preview
'livepreview-loading' => '加载中…',
'livepreview-ready' => '加载中… 舞正哩!',
'livepreview-failed' => '即时预览失败! 试吖标准预览。',
'livepreview-error' => '连接失败: $1 "$2" 试吖标准预览。',

# Friendlier slave lag warnings
'lag-warn-normal' => '将将𠮶$1秒之内𠮶改动话伓正伓会显到列表里头。',
'lag-warn-high' => '数据库咁慢，将将𠮶$1秒𠮶改动话伓正伓会显到列表里头。',

# Watchlist editor
'watchlistedit-numitems' => '倷𠮶监视列表拢共有$1只标题，佢伓包括对话页。',
'watchlistedit-noitems' => '倷𠮶监视列表冇标题。',
'watchlistedit-normal-title' => '编写监视列表',
'watchlistedit-normal-legend' => '到监视列表移卟标题',
'watchlistedit-normal-explain' => '倷𠮶监视列表𠮶标题会到下底显到。想移卟只标题，到佢前头勾吖，跟到按吖移除标题。倷也能[[Special:EditWatchlist/raw|编辑原始监视列表]]或[[Special:Watchlist/clear|移除所全部标题]]。',
'watchlistedit-normal-submit' => '移除标题',
'watchlistedit-normal-done' => '$1只标题从倷𠮶监视列表移卟嘞:',
'watchlistedit-raw-title' => '编写原始监视列表',
'watchlistedit-raw-legend' => '编写原始监视列表',
'watchlistedit-raw-explain' => '倷𠮶监视列表𠮶标题会到下底显到，哈能利用个只表去加进同到移除标题；一行一只标题。扤完后，按更新监视列表。倷也能[[Special:EditWatchlist|标准编辑器]]。',
'watchlistedit-raw-titles' => '标题:',
'watchlistedit-raw-submit' => '更新监视列表',
'watchlistedit-raw-done' => '倷𠮶监视列表更新正嘞。',
'watchlistedit-raw-added' => '加嘞$1只标题:',
'watchlistedit-raw-removed' => '移嘞$1只标题:',

# Watchlist editing tools
'watchlisttools-view' => '眵吖相关更改',
'watchlisttools-edit' => '眵吖同到编写监视列表',
'watchlisttools-raw' => '编写原始监视列表',

# Core parser functions
'unknown_extension_tag' => '伓认得𠮶扩展标签 "$1"',
'duplicate-defaultsort' => '\'\'\'警告：\'\'\'预设𠮶排序键 "$2" 覆蓋先头𠮶预设排序键 "$1"。',

# Special:Version
'version' => '版本',
'version-extensions' => '装正𠮶插件',
'version-specialpages' => '特别𠮶页面',
'version-parserhooks' => '解析器钩子',
'version-variables' => '变量',
'version-other' => '别𠮶',
'version-mediahandlers' => '媒体处理程序',
'version-extension-functions' => '扩展功能',
'version-parser-extensiontags' => '解析器扩展标签',
'version-hook-name' => '钩子名',
'version-hook-subscribedby' => '订阅人',
'version-version' => '（版本 $1）',
'version-license' => '许可证',
'version-poweredby-credits' => "个只 Wiki 由 '''[//www.mediawiki.org/ MediaWiki]''' 驱动，版权所有 © 2001-$1 $2。",
'version-software' => '装正𠮶软件',
'version-software-version' => '版本',

# Special:FilePath
'filepath' => '文件路径',
'filepath-page' => '文件：',
'filepath-submit' => '路径',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => '文件名:',
'fileduplicatesearch-submit' => '寻',

# Special:SpecialPages
'specialpages' => '特殊页',
'specialpages-group-redirects' => '重定向特殊页面',

# External image whitelist
'external_image_whitelist' => '#留住个行字<pre>
#到下首（//𠮶中间）输入正规表达式
#佢俚会同得外部（已超连结𠮶）图片配合
#许滴配合到出来𠮶会显示做图片，否则就光会显示做连结
#有 # 开头𠮶行会当做注解
#大小写冇有差别

#到个行上首输入所有𠮶regex。留住个行字</pre>',

# Special:Tags
'tag-filter' => '[[Special:Tags|标签]]过滤器：',

# Search suggestions
'searchsuggest-search' => '寻吖',

);
