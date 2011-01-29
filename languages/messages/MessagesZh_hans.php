<?php
/** Simplified Chinese (‪中文(简体)‬)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alebcay
 * @author Bencmq
 * @author Biŋhai
 * @author Chenxiaoqino
 * @author Chinalace
 * @author Fantasticfears
 * @author Franklsf95
 * @author Gaoxuewei
 * @author Gzdavidwong
 * @author Hercule
 * @author Horacewai2
 * @author Hydra
 * @author Jding2010
 * @author Jidanni
 * @author Jimmy xu wrk
 * @author KaiesTse
 * @author Kuailong
 * @author Liangent
 * @author Mark85296341
 * @author MarkAHershberger
 * @author O
 * @author Onecountry
 * @author PhiLiP
 * @author Shinjiman
 * @author Shizhao
 * @author Tommyang
 * @author Waihorace
 * @author Wilsonmess
 * @author Wmr89502270
 * @author Wong128hk
 * @author Xiaomingyan
 * @author Yfdyh000
 */

$fallback8bitEncoding = 'windows-936';

$namespaceNames = array(
	NS_MEDIA            => '媒体文件',
	NS_SPECIAL          => '特殊',
	NS_TALK             => '讨论',
	NS_USER             => '用户',
	NS_USER_TALK        => '用户讨论',
	NS_PROJECT_TALK     => '$1讨论',
	NS_FILE             => '文件',
	NS_FILE_TALK        => '文件讨论',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki讨论',
	NS_TEMPLATE         => '模板',
	NS_TEMPLATE_TALK    => '模板讨论',
	NS_HELP             => '帮助',
	NS_HELP_TALK        => '帮助讨论',
	NS_CATEGORY         => '分类',
	NS_CATEGORY_TALK    => '分类讨论',
);

$namespaceAliases = array(
	'媒体'	=> NS_MEDIA,
	'特殊'  => NS_SPECIAL,
	'对话'  => NS_TALK,
	'讨论'	=> NS_TALK,
	'用户'  => NS_USER,
	'用户对话' => NS_USER_TALK,
	'用户讨论' => NS_USER_TALK,
	# This has never worked so it's unlikely to annoy anyone if I disable it -- TS
	# '{{SITENAME}}_对话' => NS_PROJECT_TALK
	'图像' => NS_FILE,
	'档案' => NS_FILE,
	'文件' => NS_FILE,
	'Image' => NS_FILE,
	'Image_talk' => NS_FILE_TALK,
	'图像对话' => NS_FILE_TALK,
	'图像讨论' => NS_FILE_TALK,
	'档案对话' => NS_FILE_TALK,
	'档案讨论' => NS_FILE_TALK,
	'文件对话' => NS_FILE_TALK,
	'文件讨论' => NS_FILE_TALK,
	'模板'	=> NS_TEMPLATE,
	'模板对话' => NS_TEMPLATE_TALK,
	'模板讨论' => NS_TEMPLATE_TALK,
	'帮助'	=> NS_HELP,
	'帮助对话' => NS_HELP_TALK,
	'帮助讨论' => NS_HELP_TALK,
	'分类'	=> NS_CATEGORY,
	'分类对话' => NS_CATEGORY_TALK,
	'分类讨论' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( '双重重定向页面' ),
	'BrokenRedirects'           => array( '损坏的重定向页' ),
	'Disambiguations'           => array( '消歧义页' ),
	'Userlogin'                 => array( '用户登录' ),
	'Userlogout'                => array( '用户登出' ),
	'CreateAccount'             => array( '创建账户' ),
	'Preferences'               => array( '参数设置' ),
	'Watchlist'                 => array( '监视列表' ),
	'Recentchanges'             => array( '最近更改' ),
	'Upload'                    => array( '上传文件' ),
	'Listfiles'                 => array( '文件列表' ),
	'Newimages'                 => array( '新建文件' ),
	'Listusers'                 => array( '用户列表' ),
	'Listgrouprights'           => array( '群组权限' ),
	'Statistics'                => array( '统计信息' ),
	'Randompage'                => array( '随机页面' ),
	'Lonelypages'               => array( '孤立页面' ),
	'Uncategorizedpages'        => array( '未归类页面' ),
	'Uncategorizedcategories'   => array( '未归类分类' ),
	'Uncategorizedimages'       => array( '未归类文件' ),
	'Uncategorizedtemplates'    => array( '未归类模板' ),
	'Unusedcategories'          => array( '未使用分类' ),
	'Unusedimages'              => array( '未使用文件' ),
	'Wantedpages'               => array( '待撰页面' ),
	'Wantedcategories'          => array( '待撰分类' ),
	'Wantedfiles'               => array( '需要的文件' ),
	'Wantedtemplates'           => array( '需要的模板' ),
	'Mostlinked'                => array( '最多链接页面' ),
	'Mostlinkedcategories'      => array( '最多链接分类' ),
	'Mostlinkedtemplates'       => array( '最多链接模板' ),
	'Mostimages'                => array( '最多链接文件' ),
	'Mostcategories'            => array( '最多分类页面' ),
	'Mostrevisions'             => array( '最多修订页面' ),
	'Fewestrevisions'           => array( '最少修订页面' ),
	'Shortpages'                => array( '短页面' ),
	'Longpages'                 => array( '长页面' ),
	'Newpages'                  => array( '最新页面' ),
	'Ancientpages'              => array( '最早页面' ),
	'Deadendpages'              => array( '断链页面' ),
	'Protectedpages'            => array( '已保护页面' ),
	'Protectedtitles'           => array( '已保护标题' ),
	'Allpages'                  => array( '所有页面' ),
	'Prefixindex'               => array( '前缀索引' ),
	'Ipblocklist'               => array( '封禁列表' ),
	'Unblock'                   => array( '解除封禁' ),
	'Specialpages'              => array( '特殊页面' ),
	'Contributions'             => array( '用户贡献' ),
	'Emailuser'                 => array( '邮件用户' ),
	'Confirmemail'              => array( '确认电子邮件' ),
	'Whatlinkshere'             => array( '链入页面' ),
	'Recentchangeslinked'       => array( '链出更改' ),
	'Movepage'                  => array( '移动页面' ),
	'Blockme'                   => array( '封禁我' ),
	'Booksources'               => array( '网络书源' ),
	'Categories'                => array( '页面分类' ),
	'Export'                    => array( '导出页面' ),
	'Version'                   => array( '版本信息' ),
	'Allmessages'               => array( '所有信息' ),
	'Log'                       => array( '日志' ),
	'Blockip'                   => array( '查封用户' ),
	'Undelete'                  => array( '恢复被删页面' ),
	'Import'                    => array( '导入页面' ),
	'Lockdb'                    => array( '锁定数据库' ),
	'Unlockdb'                  => array( '解除数据库锁定' ),
	'Userrights'                => array( '用户权限' ),
	'MIMEsearch'                => array( 'MIME搜索' ),
	'FileDuplicateSearch'       => array( '搜索重复文件' ),
	'Unwatchedpages'            => array( '未受监视页面' ),
	'Listredirects'             => array( '重定向页面列表' ),
	'Revisiondelete'            => array( '删除或恢复版本' ),
	'Unusedtemplates'           => array( '未使用模板' ),
	'Randomredirect'            => array( '随机重定向页面' ),
	'Mypage'                    => array( '我的用户页' ),
	'Mytalk'                    => array( '我的讨论页' ),
	'Mycontributions'           => array( '我的贡献' ),
	'Listadmins'                => array( '管理员列表' ),
	'Listbots'                  => array( '机器人列表' ),
	'Popularpages'              => array( '热点页面' ),
	'Search'                    => array( '搜索' ),
	'Resetpass'                 => array( '修改密码' ),
	'Withoutinterwiki'          => array( '无跨wiki链接页面' ),
	'MergeHistory'              => array( '合并历史' ),
	'Filepath'                  => array( '文件路径' ),
	'Invalidateemail'           => array( '不可识别的电邮地址' ),
	'Blankpage'                 => array( '空白页面' ),
	'LinkSearch'                => array( '搜索网页链接' ),
	'DeletedContributions'      => array( '已删除的用户贡献' ),
	'Tags'                      => array( '标签' ),
	'Activeusers'               => array( '活跃用户' ),
);

$linkTrail = '/^()(.*)$/sD';

$extraUserToggles = array(
	'nolangconversion',
);
$datePreferences = array(
	'default',
	'ISO 8601',
);
$defaultDateFormat = 'zh';
$dateFormats = array(
	'zh time' => 'H:i',
	'zh date' => 'Y年n月j日 (l)',
	'zh both' => 'Y年n月j日 (D) H:i',
);

$bookstoreList = array(
	'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
	'PriceSCAN' => 'http://www.pricescan.com/books/bookDetail.asp?isbn=$1',
	'Barnes & Noble' => 'http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1',
	'亚马逊' => 'http://www.amazon.com/exec/obidos/ISBN=$1',
	'卓越亚马逊' => 'http://www.amazon.cn/mn/advancedSearchApp?isbn=$1',
	'当当网' => 'http://search.dangdang.com/search.aspx?key=$1',
	'博客来书店' => 'http://www.books.com.tw/exep/prod/booksfile.php?item=$1',
	'三民书店' => 'http://www.sanmin.com.tw/page-qsearch.asp?ct=search_isbn&qu=$1',
	'天下书店' => 'http://www.cwbook.com.tw/search/result1.jsp?field=2&keyWord=$1',
	'新丝路书店' => 'http://www.silkbook.com/function/Search_list_book_data.asp?item=5&text=$1'
);

$messages = array(
# User preference toggles
'tog-underline'               => '链接下划线：',
'tog-highlightbroken'         => '无效链接显示为<a href="" class="new">这样</a>（或者<a href="" class="internal">?</a>）',
'tog-justify'                 => '段落对齐',
'tog-hideminor'               => '在最近更改中隐藏小修改',
'tog-hidepatrolled'           => '在最近更改中隐藏已巡查编辑',
'tog-newpageshidepatrolled'   => '在新页面列表中隐藏已巡查页面',
'tog-extendwatchlist'         => '展开监视列表以显示所有更改而不只是最近更改',
'tog-usenewrc'                => '启用增强最近更改（JavaScript）',
'tog-numberheadings'          => '标题自动编号',
'tog-showtoolbar'             => '显示编辑工具条（JavaScript）',
'tog-editondblclick'          => '双击时编辑页面（JavaScript）',
'tog-editsection'             => '允许点击[编辑]链接编辑段落',
'tog-editsectiononrightclick' => '允许右击标题编辑段落（JavaScript）',
'tog-showtoc'                 => '显示目录（针对超过3个标题的页面）',
'tog-rememberpassword'        => '在此浏览器上保留我的登录信息（最长$1{{PLURAL:$1|日|日}}）',
'tog-watchcreations'          => '将我创建的页面添加到我的监视列表',
'tog-watchdefault'            => '将我编辑的页面添加到我的监视列表',
'tog-watchmoves'              => '将我移动的页面添加到我的监视列表',
'tog-watchdeletion'           => '将我删除的页面添加到我的监视列表',
'tog-minordefault'            => '默认将编辑设置为小编辑',
'tog-previewontop'            => '在编辑框上方显示预览',
'tog-previewonfirst'          => '在首次编辑时显示预览',
'tog-nocache'                 => '禁止浏览器页面缓存',
'tog-enotifwatchlistpages'    => '当我的监视列表中的页面改变时发送电子邮件通知我',
'tog-enotifusertalkpages'     => '当我的讨论页更改时发送电子邮件通知我',
'tog-enotifminoredits'        => '当页面有小修改时也发送电子邮件通知我',
'tog-enotifrevealaddr'        => '在通知电子邮件列表中显示我的电子邮件地址',
'tog-shownumberswatching'     => '显示监视此页的用户数',
'tog-oldsig'                  => '原签名预览：',
'tog-fancysig'                => '将签名以wiki文本对待（不产生自动链接）',
'tog-externaleditor'          => '默认使用外部编辑器（供高级用户使用，需要在您的计算机上作出一些特别设置。[http://www.mediawiki.org/wiki/Manual:External_editors 更多信息。]）',
'tog-externaldiff'            => '默认使用外部差异分析（供高级用户使用，需要在您的计算机上作出一些特别设置。[http://www.mediawiki.org/wiki/Manual:External_editors 更多信息。]）',
'tog-showjumplinks'           => '启用“跳转到”访问链接',
'tog-uselivepreview'          => '使用实时预览（Javascript）（试验中）',
'tog-forceeditsummary'        => '当没有输入摘要时提醒我',
'tog-watchlisthideown'        => '在监视列表中隐藏我的编辑',
'tog-watchlisthidebots'       => '在监视列表中隐藏机器人的编辑',
'tog-watchlisthideminor'      => '在监视列表中隐藏小修改',
'tog-watchlisthideliu'        => '在监视列表中隐藏登录用户',
'tog-watchlisthideanons'      => '在监视列表中隐藏匿名用户',
'tog-watchlisthidepatrolled'  => '在监视列表中隐藏已巡查的编辑',
'tog-nolangconversion'        => '不进行字词转换',
'tog-ccmeonemails'            => '把我给其他用户发送的电子邮件的副本发送给我',
'tog-diffonly'                => '比较差异时不显示页面内容',
'tog-showhiddencats'          => '显示隐藏分类',
'tog-noconvertlink'           => '不进行标题／链接转换',
'tog-norollbackdiff'          => '执行回退后不显示差异',

'underline-always'  => '总是使用',
'underline-never'   => '从不使用',
'underline-default' => '浏览器默认',

# Font style option in Special:Preferences
'editfont-style'     => '编辑区字体样式：',
'editfont-default'   => '浏览器默认',
'editfont-monospace' => '等宽字体',
'editfont-sansserif' => '无衬线字体',
'editfont-serif'     => '衬线字体',

# Dates
'sunday'        => '星期日',
'monday'        => '星期一',
'tuesday'       => '星期二',
'wednesday'     => '星期三',
'thursday'      => '星期四',
'friday'        => '星期五',
'saturday'      => '星期六',
'sun'           => '日',
'mon'           => '一',
'tue'           => '二',
'wed'           => '三',
'thu'           => '四',
'fri'           => '五',
'sat'           => '六',
'january'       => '1月',
'february'      => '2月',
'march'         => '3月',
'april'         => '4月',
'may_long'      => '5月',
'june'          => '6月',
'july'          => '7月',
'august'        => '8月',
'september'     => '9月',
'october'       => '10月',
'november'      => '11月',
'december'      => '12月',
'january-gen'   => '一月',
'february-gen'  => '二月',
'march-gen'     => '三月',
'april-gen'     => '四月',
'may-gen'       => '五月',
'june-gen'      => '六月',
'july-gen'      => '七月',
'august-gen'    => '八月',
'september-gen' => '九月',
'october-gen'   => '十月',
'november-gen'  => '十一月',
'december-gen'  => '十二月',
'jan'           => '1月',
'feb'           => '2月',
'mar'           => '3月',
'apr'           => '4月',
'may'           => '5月',
'jun'           => '6月',
'jul'           => '7月',
'aug'           => '8月',
'sep'           => '9月',
'oct'           => '10月',
'nov'           => '11月',
'dec'           => '12月',

# Categories related messages
'pagecategories'                 => '$1个分类',
'category_header'                => '分类“$1”的页面',
'subcategories'                  => '子分类',
'category-media-header'          => '分类“$1”的媒体文件',
'category-empty'                 => "''本分类尚未包含任何页面或媒体文件。''",
'hidden-categories'              => '$1个隐藏分类',
'hidden-category-category'       => '隐藏分类',
'category-subcat-count'          => '{{PLURAL:$2|本分类只有下列一个子分类。|本分类包含下列$1个子分类，共有$2个子分类。}}',
'category-subcat-count-limited'  => '本分类包含下列$1个子分类。',
'category-article-count'         => '{{PLURAL:$2|本分类只有下列一个页面。|本分类包含下列$1个页面，共有$2个页面。}}',
'category-article-count-limited' => '本分类包含下列$1个页面。',
'category-file-count'            => '{{PLURAL:$2|本分类只有下列一个文件。|本分类包含下列$1个文件，共有$2个文件。}}',
'category-file-count-limited'    => '本分类包含下列$1个文件。',
'listingcontinuesabbrev'         => '续',
'index-category'                 => '已做索引的页面',
'noindex-category'               => '未做索引的页面',

'mainpagetext'      => "'''已成功安装MediaWiki。'''",
'mainpagedocfooter' => '请查阅[http://meta.wikimedia.org/wiki/Help:Contents 用户指南]以获取使用本wiki软件的信息！

== 入门 ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings MediaWiki 配置设置列表]
* [http://www.mediawiki.org/wiki/Manual:FAQ/zh-hans MediaWiki常见问题]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki发布邮件列表]',

'about'         => '关于',
'article'       => '内容页面',
'newwindow'     => '（在新窗口中打开）',
'cancel'        => '取消',
'moredotdotdot' => '更多',
'mypage'        => '我的页面',
'mytalk'        => '讨论页',
'anontalk'      => '该IP的讨论页',
'navigation'    => '导航',
'and'           => '和',

# Cologne Blue skin
'qbfind'         => '查找',
'qbbrowse'       => '浏览',
'qbedit'         => '编辑',
'qbpageoptions'  => '页面选项',
'qbpageinfo'     => '页面信息',
'qbmyoptions'    => '我的选项',
'qbspecialpages' => '特殊页面',
'faq'            => '常见问题',
'faqpage'        => 'Project:常见问题',

# Vector skin
'vector-action-addsection'       => '添加话题',
'vector-action-delete'           => '删除',
'vector-action-move'             => '移动',
'vector-action-protect'          => '保护',
'vector-action-undelete'         => '恢复被删页面',
'vector-action-unprotect'        => '解除保护',
'vector-simplesearch-preference' => '打开增强搜索建议（仅限Vector皮肤）',
'vector-view-create'             => '创建',
'vector-view-edit'               => '编辑',
'vector-view-history'            => '查看历史',
'vector-view-view'               => '阅读',
'vector-view-viewsource'         => '查看源代码',
'actions'                        => '动作',
'namespaces'                     => '名字空间',
'variants'                       => '变换',

'errorpagetitle'    => '错误',
'returnto'          => '返回到$1。',
'tagline'           => '出自{{SITENAME}}',
'help'              => '帮助',
'search'            => '搜索',
'searchbutton'      => '搜索',
'go'                => '进入',
'searcharticle'     => '进入',
'history'           => '页面历史',
'history_short'     => '历史',
'updatedmarker'     => '我上次访问以来的修改',
'info_short'        => '信息',
'printableversion'  => '可打印版',
'permalink'         => '永久链接',
'print'             => '打印',
'view'              => '查看',
'edit'              => '编辑',
'create'            => '创建',
'editthispage'      => '编辑此页',
'create-this-page'  => '创建此页',
'delete'            => '删除',
'deletethispage'    => '删除此页',
'undelete_short'    => '恢复被删除的$1项修订',
'viewdeleted_short' => '查看被删除的$1项修订',
'protect'           => '保护',
'protect_change'    => '更改',
'protectthispage'   => '保护此页',
'unprotect'         => '解除保护',
'unprotectthispage' => '解除此页保护',
'newpage'           => '新建页面',
'talkpage'          => '讨论此页',
'talkpagelinktext'  => '讨论',
'specialpage'       => '特殊页面',
'personaltools'     => '个人工具',
'postcomment'       => '新段落',
'articlepage'       => '查看内容页面',
'talk'              => '讨论',
'views'             => '查看',
'toolbox'           => '工具箱',
'userpage'          => '查看用户页面',
'projectpage'       => '查看计划页面',
'imagepage'         => '查看文件页面',
'mediawikipage'     => '查看消息页面',
'templatepage'      => '查看模板页面',
'viewhelppage'      => '查看帮助页面',
'categorypage'      => '查看分类页面',
'viewtalkpage'      => '查看讨论页面',
'otherlanguages'    => '其他语言',
'redirectedfrom'    => '（重定向自$1）',
'redirectpagesub'   => '重定向页面',
'lastmodifiedat'    => '此页面最后修订于$1 $2。',
'viewcount'         => '此页面已被浏览过$1次。',
'protectedpage'     => '已保护页面',
'jumpto'            => '跳转到：',
'jumptonavigation'  => '导航',
'jumptosearch'      => '搜索',
'view-pool-error'   => '抱歉，服务器超负荷运转。
过多用户正尝试查看本页面。
请在再次尝试访问本页面之前稍等片刻。

$1',
'pool-timeout'      => '等待锁超时',
'pool-queuefull'    => '请求池已满',
'pool-errorunknown' => '未知错误',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '关于{{SITENAME}}',
'aboutpage'            => 'Project:关于',
'copyright'            => '本站全部文字内容使用$1授权。',
'copyrightpage'        => '{{ns:project}}:版权信息',
'currentevents'        => '当前事件',
'currentevents-url'    => 'Project:当前事件',
'disclaimers'          => '免责声明',
'disclaimerpage'       => 'Project:免责声明',
'edithelp'             => '编辑帮助',
'edithelppage'         => 'Help:如何编辑页面',
'helppage'             => 'Help:目录',
'mainpage'             => '首页',
'mainpage-description' => '首页',
'policy-url'           => 'Project:方针',
'portal'               => '社区入口',
'portal-url'           => 'Project:社区',
'privacy'              => '隐私政策',
'privacypage'          => 'Project:隐私政策',

'badaccess'        => '权限错误',
'badaccess-group0' => '你被禁止执行你刚才请求的操作。',
'badaccess-groups' => '您刚才请求的操作只有{{PLURAL:$2|这个用户组|以下用户组}}中的用户才能使用： $1',

'versionrequired'     => '需要版本为$1的MediaWiki',
'versionrequiredtext' => '需要版本为$1的MediaWiki才能使用本页。请见[[Special:Version|版本页面]]。',

'ok'                      => '确定',
'retrievedfrom'           => '取自“$1”',
'youhavenewmessages'      => '您有$1（$2）。',
'newmessageslink'         => '新信息',
'newmessagesdifflink'     => '上次更改',
'youhavenewmessagesmulti' => '您在$1有一条新信息',
'editsection'             => '编辑',
'editold'                 => '编辑',
'viewsourceold'           => '查看源码',
'editlink'                => '编辑',
'viewsourcelink'          => '查看源码',
'editsectionhint'         => '编辑段落：$1',
'toc'                     => '目录',
'showtoc'                 => '显示',
'hidetoc'                 => '隐藏',
'collapsible-collapse'    => '折叠',
'collapsible-expand'      => '展开',
'thisisdeleted'           => '查看或恢复$1？',
'viewdeleted'             => '查看$1？',
'restorelink'             => '$1个被删除的版本',
'feedlinks'               => '订阅：',
'feed-invalid'            => '无效的订阅类型。',
'feed-unavailable'        => '不提供联合订阅源',
'site-rss-feed'           => '$1的RSS订阅',
'site-atom-feed'          => '$1的Atom订阅',
'page-rss-feed'           => '“$1”的RSS订阅',
'page-atom-feed'          => '“$1”的Atom订阅',
'red-link-title'          => '$1（尚未撰写）',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => '页面',
'nstab-user'      => '用户页面',
'nstab-media'     => '媒体页面',
'nstab-special'   => '特殊页面',
'nstab-project'   => '计划页面',
'nstab-image'     => '文件',
'nstab-mediawiki' => '信息',
'nstab-template'  => '模板',
'nstab-help'      => '帮助页面',
'nstab-category'  => '分类',

# Main script and global functions
'nosuchaction'      => '这个命令不存在',
'nosuchactiontext'  => 'URL指定的命令无效。你可能误输入了URL地址，或者点击了错误的链接。这一错误亦有可能是由{{SITENAME}}所使用软件自身的错误导致的。',
'nosuchspecialpage' => '此特殊页面不存在',
'nospecialpagetext' => '<strong>您请求的特殊页面无效。</strong>

[[Special:SpecialPages|{{int:specialpages}}]]中列出了所有有效的特殊页面。',

# General errors
'error'                => '错误',
'databaseerror'        => '数据库错误',
'dberrortext'          => '发生了数据库查询语法错误，可能是由于软件自身的错误所引起。最后一次数据库查询指令是：
<blockquote><tt>$1</tt></blockquote>
来自函数“<tt>$2</tt>”内。数据库返回错误“<tt>$3: $4</tt>”。',
'dberrortextcl'        => '发生了数据库查询语法错误。最后一次数据库查询指令是：
“$1”
来自函数“$2”内。数据库返回错误“$3: $4”。',
'laggedslavemode'      => "'''警告'''：页面可能未包含最近的更新。",
'readonly'             => '数据库被锁定',
'enterlockreason'      => '请输入锁定的原因，包括预计重新开放的时间',
'readonlytext'         => '数据库目前禁止输入新内容及更改，
这很可能是由于数据库正在维修，完成后即可恢复。

管理员有如下解释：$1',
'missing-article'      => '数据库找不到预期的页面文字：“$1”$2。

这通常是由于点击了链向旧有差异或历史的链接，而原有修订已被删除导致的。

如果情况不是这样，您可能找到了软件的一个内部错误。请记录下URL地址，并向[[Special:ListUsers/sysop|管理员]]报告。',
'missingarticle-rev'   => '（修订#：$1）',
'missingarticle-diff'  => '（差异：$1、$2）',
'readonly_lag'         => '从数据库服务器正在从主服务器上更新，数据库已被自动锁定',
'internalerror'        => '内部错误',
'internalerror_info'   => '内部错误：$1',
'fileappenderrorread'  => '当附加时无法读取"$1"。',
'fileappenderror'      => '无法将“$1”附加到“$2”。',
'filecopyerror'        => '无法将文件“$1”复制到“$2”。',
'filerenameerror'      => '无法将文件“$1”重命名为“$2”。',
'filedeleteerror'      => '无法删除文件“$1”。',
'directorycreateerror' => '无法创建目录“$1”。',
'filenotfound'         => '找不到文件“$1”。',
'fileexistserror'      => '无法写入文件“$1”：文件已存在',
'unexpected'           => '非正常值：“$1”=“$2”。',
'formerror'            => '错误：无法提交表单',
'badarticleerror'      => '无法在此页进行该操作。',
'cannotdelete'         => '无法删除页面或图像 "$1"。
它可能已被其他人删除了。',
'badtitle'             => '错误的标题',
'badtitletext'         => '所请求页面的标题是无效的、不存在，跨语言或跨wiki链接的标题错误。它可能包含一个或更多的不能用于标题的字符。',
'perfcached'           => '下列是缓存数据，因此可能不是最新的：',
'perfcachedts'         => '下列是缓存数据，其最后更新时间是$1。',
'querypage-no-updates' => '当前禁止对此页面进行更新。此处的数据将不能被立即刷新。',
'wrong_wfQuery_params' => '错误的参数被传递到 wfQuery（）<br />
函数：$1<br />
查询：$2',
'viewsource'           => '查看源代码',
'viewsourcefor'        => '对$1的源代码',
'actionthrottled'      => '操作被限制',
'actionthrottledtext'  => '基于反垃圾链接的考量，您被限制在短时间内多次重复该操作，但您已超过此上限。请在数分钟后再尝试。',
'protectedpagetext'    => '该页面已被保护以防止编辑。',
'viewsourcetext'       => '您可以查看并复制此页面的源代码：',
'protectedinterface'   => '该页提供了软件的界面文本，它已被保护以防止随意的修改。',
'editinginterface'     => "'''警告：''' 您正在编辑的页面是用于提供软件的界面文本。改变此页将影响其他用户的界面外观。如要翻译，请考虑使用[http://translatewiki.net/wiki/Main_Page?setlang=zh-hans translatewiki.net]，一个用来为MediaWiki软件本地化的计划。",
'sqlhidden'            => '（SQL查询已隐藏）',
'cascadeprotected'     => '此页面已被保护，因为这个页面被以下已标注“联锁保护”的{{PLURAL:$1|一个|多个}}被保护页面包含：
$2',
'namespaceprotected'   => "您并没有权限编辑'''$1'''名字空间内的页面。",
'customcssjsprotected' => '您并无权限去编辑这个页面，因为它包含了另一位用户的个人设定。',
'ns-specialprotected'  => '特殊页面是不可以编辑的。',
'titleprotected'       => "这个标题已经被[[User:$1|$1]]保护以防止创建。理由是''$2''。",

# Virus scanner
'virus-badscanner'     => "损坏设置：未知的反病毒扫描器：''$1''",
'virus-scanfailed'     => '扫描失败（代码 $1）',
'virus-unknownscanner' => '未知的反病毒扫描器：',

# Login and logout pages
'logouttext'                 => "'''您现在已经退出。'''

您可以继续以匿名方式使用{{SITENAME}}，或再次以相同或不同用户身份[[Special:UserLogin|登录]]。
请注意一些页面可能仍然显示您为登录状态，直到您清空您的浏览器缓存为止。",
'welcomecreation'            => '== 欢迎，$1！ ==
您的账户已经建立。
不要忘记设置[[Special:Preferences|{{SITENAME}}的个人参数]]。',
'yourname'                   => '用户名：',
'yourpassword'               => '密码：',
'yourpasswordagain'          => '再次输入密码：',
'remembermypassword'         => '在此浏览器上保留我的登录信息（最长$1{{PLURAL:$1|日|日}}）',
'securelogin-stick-https'    => '登陆后继续使用 HTTPS 连接',
'yourdomainname'             => '您的域名：',
'externaldberror'            => '这可能是由于验证数据库错误或您被禁止更新您的外部账号。',
'login'                      => '登录',
'nav-login-createaccount'    => '登录／创建账户',
'loginprompt'                => '您必须启用Cookies才能登录{{SITENAME}}。',
'userlogin'                  => '登录／创建账户',
'userloginnocreate'          => '登录',
'logout'                     => '退出',
'userlogout'                 => '退出',
'notloggedin'                => '未登录',
'nologin'                    => '您还没有账户吗？$1。',
'nologinlink'                => '创建新账户',
'createaccount'              => '创建新账户',
'gotaccount'                 => '已经拥有账户？$1。',
'gotaccountlink'             => '登录',
'createaccountmail'          => '通过电子邮件',
'createaccountreason'        => '理由：',
'badretype'                  => '您所输入的密码并不相同。',
'userexists'                 => '您所输入的用户名称已经存在，请另选一个名称。',
'loginerror'                 => '登录错误',
'createaccounterror'         => '无法建立账户：$1',
'nocookiesnew'               => '已成功创建新账户！侦测到您已关闭Cookies，请开启它并登录。',
'nocookieslogin'             => '本站利用Cookies进行用户登录，侦测到您已关闭Cookies，请开启它并重新登录。',
'nocookiesfornew'            => '这位用户的账户未建立，我们不能确认它的来源。
请肯定您已经开启 cookies，重载后再试。',
'noname'                     => '你没有输入有效的用户名。',
'loginsuccesstitle'          => '登录成功',
'loginsuccess'               => "'''你现在以“$1”的身份登录到{{SITENAME}}。'''",
'nosuchuser'                 => '找不到用户“$1”。用户名是大小写敏感且区分繁简体的。请检查您的拼写，或者[[Special:UserLogin/signup|建立一个新账户]]。',
'nosuchusershort'            => '没有一个名为“<nowiki>$1</nowiki>”的用户。请检查您输入的文字是否有错误。',
'nouserspecified'            => '你需要指定一个用户名。',
'login-userblocked'          => '这位用户已被封锁。不容许登录。',
'wrongpassword'              => '您输入的密码错误，请再试一次。',
'wrongpasswordempty'         => '您没有输入密码，请重试！',
'passwordtooshort'           => '您的密码至少需要$1个字符。',
'password-name-match'        => '您的密码必须和您的用户名不相同。',
'password-login-forbidden'   => '这个用户名称及密码的使用是被禁止的。',
'mailmypassword'             => '将新密码寄给我',
'passwordremindertitle'      => '{{SITENAME}}的新临时密码',
'passwordremindertext'       => '有人（可能是您，来自IP地址$1）已请求{{SITENAME}}的新密码（$4）。
用户“$2”的一个新临时密码现在已被设置好为“$3”。
如果这个动作是您所指示的，您便需要立即登录并选择一个新的密码。
您的临时密码会于$5天内过期。

如果是其他人发出了该请求，或者您已经记起了您的密码并不准备改变它，
您可以忽略此消息并继续使用您的旧密码。',
'noemail'                    => '用户"$1"没有登记电子邮件地址。',
'noemailcreate'              => '您需要提供一个有效的电子邮件地址',
'passwordsent'               => '用户"$1"的新密码已经寄往所登记的电子邮件地址。
请在收到后再登录。',
'blocked-mailpassword'       => '您的IP地址处于查封状态而不允许编辑，为了安全起见，密码恢复功能已被禁用。',
'eauthentsent'               => '一封确认信已经发送到推荐的地址。在发送其它邮件到此账户前，您必须首先依照这封信中的指导确认这个电子邮箱真实有效。',
'throttled-mailpassword'     => '密码提醒已在最近$1小时内发送。为了安全起见，在每$1小时内只能发送一个密码提醒。',
'mailerror'                  => '发送邮件错误：$1',
'acct_creation_throttle_hit' => '抱歉！您已经创建了$1个账号。你不能再创建了。',
'emailauthenticated'         => '您的电子邮箱地址已经于$2 $3确认有效。',
'emailnotauthenticated'      => '您的邮箱地址<strong>还没被认证</strong>。以下功能将不会发送任何邮件。',
'noemailprefs'               => '指定一个电子邮箱地址以使用此功能。',
'emailconfirmlink'           => '确认您的邮箱地址',
'invalidemailaddress'        => '邮箱地址格式不正确，请输入正确的邮箱地址或清空该输入框。',
'accountcreated'             => '已建立账户',
'accountcreatedtext'         => '$1的账户已经被创建。',
'createaccount-title'        => '在{{SITENAME}}中创建新账户',
'createaccount-text'         => '有人在{{SITENAME}}中利用您的邮箱创建了一个名为 "$2" 的新帐户（$4），密码是 "$3" 。您应该立即登录并更改密码。

如果该账户创建错误的话，您可以忽略此信息。',
'usernamehasherror'          => '用户名称不可以包含切细字符',
'login-throttled'            => '您已经尝试多次的登录动作。
请稍等多一会再试。',
'loginlanguagelabel'         => '语言：$1',
'suspicious-userlogout'      => '您登出的要求已经被拒绝，因为它可能是由已损坏的浏览器或者缓存代理传送。',

# E-mail sending
'php-mail-error-unknown' => '在 PHP 的 mail() 函数中的未知错误',

# JavaScript password checks
'password-strength'            => '预估密码强度： $1',
'password-strength-bad'        => '差',
'password-strength-mediocre'   => '一般',
'password-strength-acceptable' => '可接受',
'password-strength-good'       => '好',
'password-retype'              => '再次输入密码',
'password-retype-mismatch'     => '密码不匹配',

# Password reset dialog
'resetpass'                 => '更改密码',
'resetpass_announce'        => '您是通过一个临时的发送到邮件中的代码登录的。要完成登录，您必须在这里设定一个新密码：',
'resetpass_text'            => '<!-- 在此处添加文本 -->',
'resetpass_header'          => '更改账户密码',
'oldpassword'               => '旧密码：',
'newpassword'               => '新密码：',
'retypenew'                 => '确认密码：',
'resetpass_submit'          => '设定密码并登录',
'resetpass_success'         => '您的密码已经被成功更改！
现在正为您登录...',
'resetpass_forbidden'       => '无法更改密码',
'resetpass-no-info'         => '您必须登录后直接进入这个页面。',
'resetpass-submit-loggedin' => '更改密码',
'resetpass-submit-cancel'   => '取消',
'resetpass-wrong-oldpass'   => '无效的临时或现有的密码。
您可能已成功地更改了您的密码，或者已经请求一个新的临时密码。',
'resetpass-temp-password'   => '临时密码：',

# Edit page toolbar
'bold_sample'     => '粗体文字',
'bold_tip'        => '粗体文字',
'italic_sample'   => '斜体文本',
'italic_tip'      => '斜体文本',
'link_sample'     => '链接标题',
'link_tip'        => '内部链接',
'extlink_sample'  => 'http://www.example.com 链接标题',
'extlink_tip'     => '外部链接（加前缀 http://）',
'headline_sample' => '大标题文字',
'headline_tip'    => '2级标题文字',
'math_sample'     => '在此插入数学公式',
'math_tip'        => '插入数学公式 （LaTeX）',
'nowiki_sample'   => '在此插入非格式文本',
'nowiki_tip'      => '插入非格式文本',
'image_tip'       => '插入文件',
'media_tip'       => '文件链接',
'sig_tip'         => '带时间戳的签名',
'hr_tip'          => '水平线 （小心使用）',

# Edit pages
'summary'                          => '摘要：',
'subject'                          => '标题：',
'minoredit'                        => '这是一个小修改',
'watchthis'                        => '监视本页',
'savearticle'                      => '保存本页',
'preview'                          => '预览',
'showpreview'                      => '显示预览',
'showlivepreview'                  => '实时预览',
'showdiff'                         => '显示差异',
'anoneditwarning'                  => "'''警告：'''您没有登录。
您的IP地址将记录在此页的编辑历史中。",
'anonpreviewwarning'               => "''您没有登录。保存页面将会把您的IP地址记录在此页的编辑历史中。''",
'missingsummary'                   => "'''提示：''' 您没有提供一个编辑摘要。如果您再次单击“{{int:savearticle}}”，您的编辑将不带编辑摘要保存。",
'missingcommenttext'               => '请在下面输入评论。',
'missingcommentheader'             => "'''提示：''' 您没有为此评论提供一个标题。如果您再次单击“{{int:savearticle}}”，您的编辑将不带标题保存。",
'summary-preview'                  => '摘要预览：',
'subject-preview'                  => '标题预览：',
'blockedtitle'                     => '用户被查封',
'blockedtext'                      => "你的用户名或IP地址已经被$1查封。

这次查封是由$1所封的。当中的原因是''$2''。

* 这次查封开始的时间是：$8
* 这次查封到期的时间是：$6
* 对于被查封者：$7

你可以联络$1或者其他的[[{{MediaWiki:Grouppage-sysop}}|管理员]]，讨论这次查封。
除非你已经在你的[[Special:Preferences|帐号参数设置]]中设置了一个有效的电子邮件地址，否则你是不能使用「电邮这位用户」的功能。当设置定了一个有效的电子邮件地址后，这个功能是不会封锁的。

你的IP地址是$3，而该查封ID是 #$5。 请在你的查询中注明以上所有的资料。",
'autoblockedtext'                  => "你的IP地址已经被自动查封，由于先前的另一位用户被$1所查封。
而查封的原因是：

:''$2''

* 这次查封的开始时间是：$8
* 这次查封的到期时间是：$6
* 对于被查封者：$7

你可以联络$1或者其他的[[{{MediaWiki:Grouppage-sysop}}|管理员]]，讨论这次查封。

除非你已经在你的[[Special:Preferences|帐号参数设置]]中设置了一个有效的电子邮件地址，否则你是不能使用「电邮这位用户」的功能。当设置定了一个有效的电子邮件地址后，这个功能是不会封锁的。

您现时正在使用的 IP 地址是 $3，查封ID是 #$5。 請在你的查詢中註明以上所有的資料。",
'blockednoreason'                  => '无给出原因',
'blockedoriginalsource'            => "以下是'''$1'''的源码：",
'blockededitsource'                => "你对'''$1'''进行'''编辑'''的文字如下:",
'whitelistedittitle'               => '登录后才可编辑',
'whitelistedittext'                => '您必须先$1才可编辑页面。',
'confirmedittext'                  => '在编辑此页之前您必须确认您的邮箱地址。请通过[[Special:Preferences|参数设置]]设置并验证您的邮箱地址。',
'nosuchsectiontitle'               => '没有这个段落',
'nosuchsectiontext'                => '您尝试编辑的章节并不存在。
可能是在您查看页面时已经移动或删除。',
'loginreqtitle'                    => '需要登录',
'loginreqlink'                     => '登录',
'loginreqpagetext'                 => '您必须$1才能查看其它页面。',
'accmailtitle'                     => '密码已寄出',
'accmailtext'                      => "'$1'的密码已经被发送到$2。",
'newarticle'                       => '（新）',
'newarticletext'                   => '您进入了一个尚未创建的页面。
要创建该页面，请在下面的编辑框中输入内容（详情参见[[{{MediaWiki:Helppage}}|帮助]]）。
如果您是不小心来到此页面，直接点击您浏览器中的“返回”按钮返回。',
'anontalkpagetext'                 => "---- ''这是一个还未建立账户的匿名用户的讨论页, 因此我们只能用IP地址来与他或她联络。该IP地址可能由几名用户共享。如果您是一名匿名用户并认为此页上的评语与您无关，请[[Special:UserLogin/signup|创建新账户]]或[[Special:UserLogin|登录]]以避免在未来与其他匿名用户混淆。''",
'noarticletext'                    => '此页面目前没有内容。您可以在其他页面中[[Special:Search/{{PAGENAME}}|搜索此页标题]]、<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 搜索相关日志]或[{{fullurl:{{FULLPAGENAME}}|action=edit}} 编辑此页]。</span>',
'noarticletext-nopermission'       => '此页目前没有内容，您可以在其它页[[Special:Search/{{PAGENAME}}|搜索此页标题]]，
或<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 搜索有关日志]</span>。',
'userpage-userdoesnotexist'        => '用户账户“$1”未曾创建。请在创建／编辑这个页面前先检查一下。',
'userpage-userdoesnotexist-view'   => '用户账户“$1”未曾创建。',
'blocked-notice-logextract'        => '这位用户现正被封锁。
下面有最近的封锁纪录以供参考：',
'clearyourcache'                   => "'''注意：在保存以后，您必须清除浏览器的缓存才能看到所作出的改变。'''
'''Mozilla / Firefox / Safari'''：按住''Shift''再点击''刷新''，或按下''Ctrl-F5''或''Ctrl-R''（在Macintosh上按下''Command-R''）；
'''Konqueror'''：只需点击''刷新''或按下''F5''；
'''Opera'''：在''工具→首选项''中完整清除它们的缓存，或按下''Alt-F5''；
'''Internet Explorer'''：按住''Ctrl''再点击''刷新''，或按下''Ctrl-F5''。",
'usercssyoucanpreview'             => "'''提示：''' 在保存前请用“{{int:showpreview}}”按钮来测试您新的 CSS 。",
'userjsyoucanpreview'              => "'''提示：''' 在保存前请用“{{int:showpreview}}”按钮来测试您新的 JavaScript 。",
'usercsspreview'                   => "'''记住您只是在预览您的个人 CSS。'''
'''还没有保存！'''",
'userjspreview'                    => "'''记住您只是在测试／预览您的个人 JavaScript。'''
'''还没有保存！'''",
'sitecsspreview'                   => "'''记住你现在只是预览此 CSS。'''
'''还没有保存！'''",
'sitejspreview'                    => "'''记住你现在只是预览此 JavaScript 代码。'''
'''还没有保存！'''",
'userinvalidcssjstitle'            => "'''警告：''' 不存在皮肤\"\$1\"。注意自定义的 .css 和 .js 页要使用小写标题，例如，{{ns:user}}:Foo/vector.css 不同于 {{ns:user}}:Foo/Vector.css。",
'updated'                          => '（已更新）',
'note'                             => "'''注意：'''",
'previewnote'                      => "'''请记住这只是预览。'''内容还未保存！",
'previewconflict'                  => '这个预览显示了上面文字编辑区中的内容。它将在你选择保存后出现。',
'session_fail_preview'             => "'''抱歉！由于会话数据丢失，我们不能处理你的编辑。'''请重试。如果再次失败，请尝试[[Special:UserLogout|退出]]后重新登录。",
'session_fail_preview_html'        => "'''抱歉！我们不能处理你在进程数据丢失时的编辑。'''

''由于{{SITENAME}}允许使用原始的 HTML，为了防范 JavaScript 攻击，预览已被隐藏。''

'''如果这是一次合法的编辑，请重新进行尝试。'''如果还不行，请[[Special:UserLogout|退出]]并重新登录。",
'token_suffix_mismatch'            => "'''由于您用户端中的编辑令牌毁损了一些标点符号字元，为防止编辑的文字损坏，您的编辑已经被拒绝。'''
这种情况通常出现于使用含有很多臭虫、以网络为主的匿名代理服务的时候。",
'editing'                          => '正在编辑$1',
'editingsection'                   => '正在编辑$1（段落）',
'editingcomment'                   => '正在编辑$1（新段落）',
'editconflict'                     => '编辑冲突：$1',
'explainconflict'                  => "有人在你开始编辑后更改了页面。
上面的文字框内显示的是目前本页的内容。
你所做的修改显示在下面的文字框中。
你应当将你所做的修改加入现有的内容中。
'''只有'''在上面文字框中的内容会在你点击“{{int:savearticle}}”后被保存。",
'yourtext'                         => '您的文字',
'storedversion'                    => '已保存修订版本',
'nonunicodebrowser'                => "'''警告：您的浏览器不兼容Unicode编码。'''这里有一个工作区将使您能安全地编辑页面：非ASCII字符将以十六进制编码方式出现在编辑框中。",
'editingold'                       => "'''警告：你正在编辑的是本页的旧版本。'''
如果你保存它的话，在本版本之后的任何修改都会丢失。",
'yourdiff'                         => '差异',
'copyrightwarning'                 => "请注意您对{{SITENAME}}的所有贡献都被认为是在$2下发布，请查看在$1的细节。
如果您不希望您的文字被任意修改和再散布，请不要提交。<br />
您同时也要向我们保证您所提交的内容是您自己所作，或得自一个不受版权保护或相似自由的来源。
'''不要在未获授权的情况下发表！'''<br />",
'copyrightwarning2'                => "请注意您对{{SITENAME}}的所有贡献
都可能被其他贡献者编辑，修改或删除。
如果您不希望您的文字被任意修改和再散布，请不要提交。<br />
您同时也要向我们保证您所提交的内容是您自己所作，或得自一个不受版权保护或相似自由的来源（参阅$1的细节）。
'''不要在未获授权的情况下发表！'''",
'longpageerror'                    => "'''错误：您所提交的文本长度有$1KB，这大于$2KB的最大值。'''该文本不能被保存。",
'readonlywarning'                  => "'''警告：数据库被锁定以进行维护，所以您目前将无法保存您的修改。'''您或许希望将本段文字先复制并保存到文本文件，并在稍后进行修改。

锁定数据库的管理员有如下解释：$1",
'protectedpagewarning'             => "'''警告：此页已经被保护，只有拥有管理员权限的用户才可修改。'''
最近的日志在下面提供以便参考：",
'semiprotectedpagewarning'         => "'''注意：''' 本页面被保护，仅限注册用户编辑。
最近的日志在下面提供以便参考：",
'cascadeprotectedwarning'          => "'''警告：'''本页已经被保护，只有拥有管理员权限的用户才可修改，因为本页已被以下连锁保护的{{PLURAL:$1|一个|多个}}页面所包含：",
'titleprotectedwarning'            => "'''警告：本页面已被保护，需要[[Special:ListGroupRights|指定权限]]方可创建。'''
最近的日志在下面提供以便参考：",
'templatesused'                    => '在这个页面上使用的{{PLURAL:$1|模板|模板}}有：',
'templatesusedpreview'             => '此次预览中使用的{{PLURAL:$1|模板|模板}}有：',
'templatesusedsection'             => '在这个段落上使用的{{PLURAL:$1|模板|模板}}有：',
'template-protected'               => '（保护）',
'template-semiprotected'           => '（半保护）',
'hiddencategories'                 => '这个页面是属于$1个隐藏分类的成员：',
'edittools'                        => '<!-- 此处的文本将被显示在编辑和上传表单以下。 -->',
'nocreatetitle'                    => '创建页面受限',
'nocreatetext'                     => '{{SITENAME}}限制了创建新页面的功能。你可以返回并编辑已有的页面，或者[[Special:UserLogin|登录或创建新账户]]。',
'nocreate-loggedin'                => '您并无权限去创建新页面。',
'sectioneditnotsupported-title'    => '不支持编辑段落',
'sectioneditnotsupported-text'     => '此页面不支持编辑段落。',
'permissionserrors'                => '权限错误',
'permissionserrorstext'            => '根据以下的{{PLURAL:$1|原因|原因}}，您并无权限去做以下的动作：',
'permissionserrorstext-withaction' => '根据以下的{{PLURAL:$1|原因|原因}}，您无权限进行$2操作：',
'recreate-moveddeleted-warn'       => "'''警告: 你现在重新创建一个先前曾经删除过的页面。'''

你应该要考虑一下继续编辑这一个页面是否合适。
为方便起见，这一个页面的删除记录已经在下面提供:",
'moveddeleted-notice'              => '这个页面已经删除。
这个页面的删除和移动日志已在下面提供以便参考。',
'log-fulllog'                      => '查看完整日志',
'edit-hook-aborted'                => '编辑被钩取消。
它并无给出解释。',
'edit-gone-missing'                => '不能更新页面。
它可能刚刚被删除。',
'edit-conflict'                    => '编辑冲突。',
'edit-no-change'                   => '您的编辑已经略过，因为文字无任何改动。',
'edit-already-exists'              => '不可以建立一个新页面。
它已经存在。',

# Parser/template warnings
'expensive-parserfunction-warning'        => '警告：这个页面有太多高昂的语法功能调用。

它应该少过$2次呼叫，现在有$1次呼叫。',
'expensive-parserfunction-category'       => '页面中有太多耗费的语法功能呼叫',
'post-expand-template-inclusion-warning'  => '警告：包含模板大小过大。
一些模板将不会包含。',
'post-expand-template-inclusion-category' => '模板包含上限已经超过的页面',
'post-expand-template-argument-warning'   => '警告：这个页面有最少一个模参数有过大扩展大小。
这些参数会被略过。',
'post-expand-template-argument-category'  => '包含着略过模板参数的页面',
'parser-template-loop-warning'            => '检查到模板循环：[[$1]]',
'parser-template-recursion-depth-warning' => '模板递归深度越限（$1）',
'language-converter-depth-warning'        => '字词转换器深度越限（$1）',

# "Undo" feature
'undo-success' => '此编辑可以被撤销。请检查以下比较以核实这正是您想做的，然后保存以下更改完成撤销编辑。',
'undo-failure' => '由于中途不一致的编辑，此编辑不能撤销。',
'undo-norev'   => '由于其修订版本不存在或已删除，此编辑不能撤销。',
'undo-summary' => '撤销由[[Special:Contributions/$2|$2]]（[[User talk:$2|讨论]]）所作出的修订$1',

# Account creation failure
'cantcreateaccounttitle' => '无法创建账户',
'cantcreateaccount-text' => "从这个IP地址 （<b>$1</b>） 创建账户已经被[[User:$3|$3]]禁止。

当中被$3封禁的原因是''$2''",

# History pages
'viewpagelogs'           => '查看此页面的日志',
'nohistory'              => '此页没有修订记录。',
'currentrev'             => '最新修订版本',
'currentrev-asof'        => '在$1的最新修订版本',
'revisionasof'           => '在$1所做的修订版本',
'revision-info'          => '在$1由$2所做的修订版本',
'previousrevision'       => '←上一修订',
'nextrevision'           => '下一修订→',
'currentrevisionlink'    => '最新修订',
'cur'                    => '当前',
'next'                   => '后继',
'last'                   => '先前',
'page_first'             => '最前',
'page_last'              => '最后',
'histlegend'             => "差异选择：标记要比较修订版本的单选按钮并点击底部的按钮进行比较。<br />
说明：'''（{{int:cur}}）''' 指与最新修订版本比较，'''（{{int:last}}）''' 指与前一个修订版本比较，'''{{int:minoreditletter}}''' = 小修改。",
'history-fieldset-title' => '浏览历史',
'history-show-deleted'   => '仅已删除的',
'histfirst'              => '最早版本',
'histlast'               => '最新版本',
'historysize'            => '（$1字节）',
'historyempty'           => '（空）',

# Revision feed
'history-feed-title'          => '修订历史',
'history-feed-description'    => '本站上此页的修订历史',
'history-feed-item-nocomment' => '$1在$2',
'history-feed-empty'          => '所请求的页面不存在。它可能已被删除或重命名。
尝试[[Special:Search|搜索本站]]获得相关的新建页面。',

# Revision deletion
'rev-deleted-comment'         => '（注释已移除）',
'rev-deleted-user'            => '（用户名已移除）',
'rev-deleted-event'           => '（日志动作已移除）',
'rev-deleted-user-contribs'   => '[用户名或IP地址已移除 - 从贡献中隐藏编辑]',
'rev-deleted-text-permission' => "该页面修订已被'''删除'''。
在[{{fullurl:{{#Special:Log}}/delete|page={{PAGENAMEE}}}} 删除日志]中可以找到详细的信息。",
'rev-deleted-text-unhide'     => "该页面修订已经被'''删除'''。
在[{{fullurl:{{#Special:Log}}/delete|page={{PAGENAMEE}}}} 删除日志]中可以找到详细的信息。
作为管理员，如果您想继续的话，您可以仍然[$1 去查看这次修订]。",
'rev-suppressed-text-unhide'  => "该页面修订已经被'''废止'''。
在[{{fullurl:{{#Special:Log}}/suppress|page={{PAGENAMEE}}}} 废止日志]中可以找到详细的信息。
作为管理员，如果您想继续的话，您可以仍然[$1 去查看这次修订]。",
'rev-deleted-text-view'       => "该页面修订已经被'''删除'''。作为管理员，您可以查看它；
在[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 删除日志]中可以找到详细的信息。",
'rev-suppressed-text-view'    => "该页面修订已经被'''废止'''。作为管理员，您可以查看它；
在[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 废止日志]中可以找到详细的信息。",
'rev-deleted-no-diff'         => "因为其中一次修订已被'''删除'''，您不可以查看这个差异。
在[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 删除日志]中可以找到更多的信息。",
'rev-suppressed-no-diff'      => "该页面的其中一次修订已经被'''删除'''，你不可以查看这次的修订。",
'rev-deleted-unhide-diff'     => "该页面的其中一次修订已经被'''删除'''。
在[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 删除日志]中可以找到更多的信息。
作为管理员，如果您想继续的话，您可以仍然[$1 去查看这次修订]。",
'rev-suppressed-unhide-diff'  => "该页面的其中一次修订已经被'''废止'''。
在[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 废止日志]中可以找到更多的资料。
作为管理员，如果您想继续的话，您可以仍然[$1 去查看这次修订]。",
'rev-deleted-diff-view'       => "差异中的一次修订已被'''删除'''。
作为管理员，您可以查看此差异。详细信息可在[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 删除日志]中找到。",
'rev-suppressed-diff-view'    => "差异中的一次修订已被'''废止'''。
作为管理员，您可以查看此差异。详细信息可在[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 废止日志]中找到。",
'rev-delundel'                => '显示/隐藏',
'rev-showdeleted'             => '显示',
'revisiondelete'              => '删除/恢复删除修订',
'revdelete-nooldid-title'     => '无效的目标修订',
'revdelete-nooldid-text'      => '您尚未指定一个目标修订去进行这个功能、
所指定的修订不存在，或者您尝试去隐藏现时的修订。',
'revdelete-nologtype-title'   => '没有给出日志类型',
'revdelete-nologtype-text'    => '您尚未指定一种日志类型去做这个动作。',
'revdelete-nologid-title'     => '无效的日志项目',
'revdelete-nologid-text'      => '您尚未指定一个目标日志项目去进行这个动作或指定的项目不存在。',
'revdelete-no-file'           => '指定的文件不存在。',
'revdelete-show-file-confirm' => '你是否真的要查看文件“<nowiki>$1</nowiki>”在$2 $3时的已删除修订？',
'revdelete-show-file-submit'  => '是',
'revdelete-selected'          => "'''选取'''[[:$1]]'''的$2次修订：'''",
'logdelete-selected'          => "'''选取'''$1'''的日志项目：'''",
'revdelete-text'              => "'''删除的修订仍将显示在页面历史中, 但它们的文本内容已不能被公众访问。'''
在{{SITENAME}}的其他管理员将仍能访问隐藏的内容并通过与此相同的界面恢复删除，除非站点工作者进行了一些附加的限制。",
'revdelete-confirm'           => '请确认您肯定去做的话，您就要明白到后果，以及这个程序符合[[{{MediaWiki:Policy-url}}|政策]]。',
'revdelete-suppress-text'     => "'''只有'''出现以下的情况下才应阻止访问：
* 不合适的个人信息
*: ''家庭地址、电话号码、身份证号码等。''",
'revdelete-legend'            => '设置可见性之限制',
'revdelete-hide-text'         => '隐藏修订文本',
'revdelete-hide-image'        => '隐藏文件内容',
'revdelete-hide-name'         => '隐藏动作和目标',
'revdelete-hide-comment'      => '隐藏编辑摘要',
'revdelete-hide-user'         => '隐藏编辑者的用户名/IP地址',
'revdelete-hide-restricted'   => '同时阻止管理员与其他用户查看数据',
'revdelete-radio-same'        => '(勿更改)',
'revdelete-radio-set'         => '是',
'revdelete-radio-unset'       => '否',
'revdelete-suppress'          => '同时阻止管理员与其他用户查看数据',
'revdelete-unsuppress'        => '在已恢复的修订中移除限制',
'revdelete-log'               => '理由：',
'revdelete-submit'            => '应用于选中的{{PLURAL:$1|修订}}',
'revdelete-logentry'          => '“[[$1]]”的修订可见性已更改',
'logdelete-logentry'          => '“[[$1]]”的事件可见性已更改',
'revdelete-success'           => "'''修订的可见性已经成功更新。'''",
'revdelete-failure'           => "'''修订的可见性无法更新：'''
$1",
'logdelete-success'           => "'''事件的可见性已经成功设置。'''",
'logdelete-failure'           => "'''事件的可见性无法设置：'''
$1",
'revdel-restore'              => '更改可见性',
'revdel-restore-deleted'      => '已删除的修订版本',
'revdel-restore-visible'      => '可见的修订版本',
'pagehist'                    => '页面历史',
'deletedhist'                 => '已删除之历史',
'revdelete-content'           => '内容',
'revdelete-summary'           => '编辑摘要',
'revdelete-uname'             => '用户名',
'revdelete-restricted'        => '已将限制应用到管理员',
'revdelete-unrestricted'      => '已移除对管理员的限制',
'revdelete-hid'               => '隐藏 $1',
'revdelete-unhid'             => '不隐藏 $1',
'revdelete-log-message'       => '$1的$2次修订',
'logdelete-log-message'       => '$1的$2项事件',
'revdelete-hide-current'      => '正在隐藏于$1 $2之项目错误：这个是现时的修订，不可以隐藏。',
'revdelete-show-no-access'    => '正在显示于$1 $2之项目错误：这个项目已经标示为"已限制"，您对它并无通行权。',
'revdelete-modify-no-access'  => '正在更改于$1 $2之项目错误：这个项目已经标示为"已限制"，您对它并无通行权。',
'revdelete-modify-missing'    => '正在更改项目ID $1错误：它在资料库中遗失！',
'revdelete-no-change'         => '警告：于$1 $2之项目已经请求了可见性的设置。',
'revdelete-concurrent-change' => '正在更改于$1 $2之项目错误：当我们尝试更改它的设置时，已经被另一些人更改过。请检查纪录。',
'revdelete-only-restricted'   => '在隐藏$1 $2的项目时发生错误：您不能在选择了另一可见性选项后废止管理员查看该项目。',
'revdelete-reason-dropdown'   => '*常用删除理由
** 侵犯版权
** 不合适的个人资料',
'revdelete-otherreason'       => '其它／附加的理由：',
'revdelete-reasonotherlist'   => '其它理由',
'revdelete-edit-reasonlist'   => '编辑删除埋由',
'revdelete-offender'          => '修订著者：',

# Suppression log
'suppressionlog'     => '阻止日志',
'suppressionlogtext' => '以下是删除以及由操作员牵涉到内容封锁的列表。
参看[[Special:IPBlockList|IP封锁名单]]去参看现时进行中的禁止以及封锁之名单。',

# Revision move
'moverevlogentry'              => '移动了$1的{{PLURAL:$3|一次修订版本|$3次修订版本}}至$2',
'revisionmove'                 => '由 "$1" 移动修订版本',
'revmove-explain'              => '以下的修订版本将会由$1移动至所指定的目标页面。如果目标不存在的话，它就将会建立。否则，这些的修订版本就将会合并到页面历史中。',
'revmove-legend'               => '设置目标页面以及摘要',
'revmove-submit'               => '移动修订版本到所选定的页面上',
'revisionmoveselectedversions' => '移动已选取的修订版本',
'revmove-reasonfield'          => '理由：',
'revmove-titlefield'           => '目标页面：',
'revmove-badparam-title'       => '坏的参数',
'revmove-badparam'             => '您的请求含有不合法的者不足的参数。
请返回先前的页面再试。',
'revmove-norevisions-title'    => '无效的目标修订版本',
'revmove-norevisions'          => '您尚未指定一个或者多个目标修订版本去做这项功能或者所指定的修订版本不存在。',
'revmove-nullmove-title'       => '坏的标题',
'revmove-nullmove'             => '目标页面不可以跟来源页面相同。
请返回先前的页面再输入跟 "$1" 不相同的名字。',
'revmove-success-existing'     => '由[[$2]]中的{{PLURAL:$1|一次修订版本|$1次修订版本}}已经移动至现有的页面[[$3]]。',
'revmove-success-created'      => '由[[$2]]中的{{PLURAL:$1|一次修订版本|$1次修订版本}}已经移动至新建的页面[[$3]]。',

# History merging
'mergehistory'                     => '合并页面历史',
'mergehistory-header'              => '这一页可以让您将来源页面的修订历史合并到新页面中去。
请确保此次更改能继续保持历史页面的连续性。',
'mergehistory-box'                 => '合并两个页面的修订历史：',
'mergehistory-from'                => '来源页面：',
'mergehistory-into'                => '目的页面：',
'mergehistory-list'                => '可以合并的编辑历史',
'mergehistory-merge'               => '以下[[:$1]]的修订可以合并到[[:$2]]。用该选项按钮列去合并只有在指定时间以前所创建的修订。要留意的是使用导航链接便会重设这一栏。',
'mergehistory-go'                  => '显示可以合并的编辑',
'mergehistory-submit'              => '合并修订',
'mergehistory-empty'               => '没有修订可以合并',
'mergehistory-success'             => '[[:$1]]的$3次修订已经成功地合并到[[:$2]]。',
'mergehistory-fail'                => '不可以进行历史合并，请重新检查该页面以及时间参数。',
'mergehistory-no-source'           => '来源页面$1不存在。',
'mergehistory-no-destination'      => '目的页面$1不存在。',
'mergehistory-invalid-source'      => '来源页面必须是一个有效的标题。',
'mergehistory-invalid-destination' => '目的页面必须是一个有效的标题。',
'mergehistory-autocomment'         => '已经合并[[:$1]]去到[[:$2]]',
'mergehistory-comment'             => '已经合并[[:$1]]去到[[:$2]]：$3',
'mergehistory-same-destination'    => '来源页面与目的页面不可以相同',
'mergehistory-reason'              => '原因：',

# Merge log
'mergelog'           => '合并日志',
'pagemerge-logentry' => '已合并[[$1]]到[[$2]] （修订截至$3）',
'revertmerge'        => '解除合并',
'mergelogpagetext'   => '以下是一个最近由一个页面的修订历史合并到另一个页面的列表。',

# Diffs
'history-title'            => '“$1”的修订历史',
'difference'               => '（修订版本间的差异）',
'difference-multipage'     => '（页面间的差异）',
'lineno'                   => '第$1行：',
'compareselectedversions'  => '比较选定的修订版本',
'showhideselectedversions' => '显示／隐藏选定的修订版本',
'editundo'                 => '撤销',
'diff-multi'               => '（由{{PLURAL:$2|1名用户|$2名用户}}作出的{{PLURAL:$1|一个中途修订版本|$1个中途修订版本}}未被显示）',
'diff-multi-manyusers'     => '（由多于$2名用户作出的{{PLURAL:$1|一个中途修订版本|$1个中途修订版本}} 未被显示）',

# Search results
'searchresults'                    => '搜索结果',
'searchresults-title'              => '对“$1”的搜索结果',
'searchresulttext'                 => '有关搜索{{SITENAME}}的更多信息，参见[[{{MediaWiki:Helppage}}|{{int:help}}]]。',
'searchsubtitle'                   => '搜索\'\'\'[[:$1]]\'\'\'（[[Special:Prefixindex/$1|所有以 "$1" 开头的页面]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|所有链接到“$1”的页面]]）',
'searchsubtitleinvalid'            => "搜索'''$1'''",
'toomanymatches'                   => '返回的匹配结果过多，请尝试不同的查询词',
'titlematches'                     => '页面标题匹配',
'notitlematches'                   => '没有找到匹配页面题目',
'textmatches'                      => '页面内容匹配',
'notextmatches'                    => '没有页面内容匹配',
'prevn'                            => '前$1个',
'nextn'                            => '后$1个',
'prevn-title'                      => '前$1项结果',
'nextn-title'                      => '后$1项结果',
'shown-title'                      => '每页显示$1项结果',
'viewprevnext'                     => '查看（$1 {{int:pipe-separator}} $2）（$3）',
'searchmenu-legend'                => '搜索选项',
'searchmenu-exists'                => "'''在该wiki上已有页面名叫“[[:$1]]”。'''",
'searchmenu-new'                   => "'''在该wiki上新建页面“[[:$1]]”！'''",
'searchmenu-new-nocreate'          => '“$1”是一个无效的页面名称或无法被您创建。',
'searchhelp-url'                   => 'Help:目录',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|去浏览以此为首的页面]]',
'searchprofile-articles'           => '内容页面',
'searchprofile-project'            => '帮助和计划页面',
'searchprofile-images'             => '多媒体',
'searchprofile-everything'         => '全部',
'searchprofile-advanced'           => '高级',
'searchprofile-articles-tooltip'   => '在$1中搜索',
'searchprofile-project-tooltip'    => '在$1中搜索',
'searchprofile-images-tooltip'     => '搜索文件',
'searchprofile-everything-tooltip' => '搜索全部（包括讨论页面）',
'searchprofile-advanced-tooltip'   => '在自定义的名字空间中搜索',
'search-result-size'               => '$1（$2个字）',
'search-result-category-size'      => '$1位成员（$2个子分类，$3个文件）',
'search-result-score'              => '相关度：$1%',
'search-redirect'                  => '（重定向 $1）',
'search-section'                   => '（段落 $1）',
'search-suggest'                   => '您是不是要找：$1',
'search-interwiki-caption'         => '姐妹项目',
'search-interwiki-default'         => '$1项结果：',
'search-interwiki-more'            => '（更多）',
'search-mwsuggest-enabled'         => '有建议',
'search-mwsuggest-disabled'        => '无建议',
'search-relatedarticle'            => '相关',
'mwsuggest-disable'                => '禁用AJAX建议',
'searcheverything-enable'          => '在所有名字空间中搜索',
'searchrelated'                    => '相关',
'searchall'                        => '所有',
'showingresults'                   => "下面显示从第'''$2'''条开始的'''{{PLURAL:$1|1|$1}}'''条结果。",
'showingresultsnum'                => "下面显示从第'''$2'''条开始的'''{{PLURAL:$3|1|$3}}'''条结果。",
'showingresultsheader'             => "对'''$4'''的{{PLURAL:$5|第'''$1'''至第'''$3'''项结果|第'''$1－$2'''项，共'''$3'''项结果}}",
'nonefound'                        => "'''注意'''：只有部分名字空间的页面会被默认搜索。尝试在您的搜索语句前添加“all:”前缀，这样可以搜索全部页面（包括讨论页、模板等），或者亦可使用所需名字空间作为前缀。",
'search-nonefound'                 => '找不到和查询相匹配的结果。',
'powersearch'                      => '高级搜索',
'powersearch-legend'               => '高级搜索',
'powersearch-ns'                   => '在以下的名字空间中搜索：',
'powersearch-redir'                => '重定向列表',
'powersearch-field'                => '搜索',
'powersearch-togglelabel'          => '选择：',
'powersearch-toggleall'            => '全选',
'powersearch-togglenone'           => '全不选',
'search-external'                  => '外部搜索',
'searchdisabled'                   => '{{SITENAME}}的搜索已被禁用。您可以暂时使用Google进行搜索，须注意他们索引的{{SITENAME}}内容可能会过时。',

# Quickbar
'qbsettings'               => '快速导航栏',
'qbsettings-none'          => '无',
'qbsettings-fixedleft'     => '左侧固定',
'qbsettings-fixedright'    => '右侧固定',
'qbsettings-floatingleft'  => '左侧漂移',
'qbsettings-floatingright' => '右侧漂移',

# Preferences page
'preferences'                   => '参数设置',
'mypreferences'                 => '设置',
'prefs-edits'                   => '编辑数量：',
'prefsnologin'                  => '尚未登录',
'prefsnologintext'              => '您必须先<span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} 登录]</span>才能设置个人参数。',
'changepassword'                => '更改密码',
'prefs-skin'                    => '皮肤',
'skin-preview'                  => '预览',
'prefs-math'                    => '数学公式',
'datedefault'                   => '默认值',
'prefs-datetime'                => '日期和时间',
'prefs-personal'                => '用户资料',
'prefs-rc'                      => '最近更改',
'prefs-watchlist'               => '监视列表',
'prefs-watchlist-days'          => '监视列表中显示记录的天数：',
'prefs-watchlist-days-max'      => '最多 7 天',
'prefs-watchlist-edits'         => '在增强的监视列表中显示最多更改次数：',
'prefs-watchlist-edits-max'     => '最多数量：1000',
'prefs-watchlist-token'         => '监视列表权标：',
'prefs-misc'                    => '杂项',
'prefs-resetpass'               => '更改密码',
'prefs-email'                   => '邮箱选项',
'prefs-rendering'               => '外观',
'saveprefs'                     => '保存',
'resetprefs'                    => '清除未保存的更改',
'restoreprefs'                  => '恢复所有默认设置',
'prefs-editing'                 => '编辑',
'prefs-edit-boxsize'            => '编辑框尺寸',
'rows'                          => '行：',
'columns'                       => '列：',
'searchresultshead'             => '搜索',
'resultsperpage'                => '每页显示链接数：',
'contextlines'                  => '每链显示行数：',
'contextchars'                  => '每行显示字数：',
'stub-threshold'                => '<a href="#" class="stub">短页面链接</a>格式门槛值（字节）：',
'stub-threshold-disabled'       => '已停用',
'recentchangesdays'             => '最近更改中的显示日数：',
'recentchangesdays-max'         => '最多 $1 {{PLURAL:$1|天|天}}',
'recentchangescount'            => '默认显示的编辑数：',
'prefs-help-recentchangescount' => '这个包括最近更改、页面历史以及日志。',
'prefs-help-watchlist-token'    => '此栏填写的密钥可以生成您监视列表的RSS源。任何知晓本栏密钥的人都能阅读您的监视列表，因此请使用安全的数值。这里已提供了一个随机生成的数值供您选择：$1',
'savedprefs'                    => '您的个人参数设置已经保存。',
'timezonelegend'                => '时区：',
'localtime'                     => '当地时间：',
'timezoneuseserverdefault'      => '使用服务器默认值',
'timezoneuseoffset'             => '其它（指定时差）',
'timezoneoffset'                => '时差¹：',
'servertime'                    => '服务器时间：',
'guesstimezone'                 => '从浏览器填写',
'timezoneregion-africa'         => '非洲',
'timezoneregion-america'        => '美洲',
'timezoneregion-antarctica'     => '南极洲',
'timezoneregion-arctic'         => '北极',
'timezoneregion-asia'           => '亚洲',
'timezoneregion-atlantic'       => '大西洋',
'timezoneregion-australia'      => '澳大利亚',
'timezoneregion-europe'         => '欧洲',
'timezoneregion-indian'         => '印度洋',
'timezoneregion-pacific'        => '太平洋',
'allowemail'                    => '接受来自其他用户的邮件',
'prefs-searchoptions'           => '搜索选项',
'prefs-namespaces'              => '名字空间',
'defaultns'                     => '否则在这些名字空间搜索：',
'default'                       => '默认',
'prefs-files'                   => '文件',
'prefs-custom-css'              => '自定义CSS',
'prefs-custom-js'               => '自定义JavaScript',
'prefs-common-css-js'           => '共享CSS/JavaScript于所有皮肤中：',
'prefs-reset-intro'             => '您可以利用这个页面去重设您的参数设置到网站默认值。这个动作无法复原。',
'prefs-emailconfirm-label'      => '电子邮件确认：',
'prefs-textboxsize'             => '编辑框大小',
'youremail'                     => '电子邮件：',
'username'                      => '用户名：',
'uid'                           => '用户ID：',
'prefs-memberingroups'          => '{{PLURAL:$1|一|多}}组的成员：',
'prefs-registration'            => '注册时间：',
'yourrealname'                  => '真实姓名：',
'yourlanguage'                  => '界面语言：',
'yourvariant'                   => '字体变换：',
'yournick'                      => '新签名：',
'prefs-help-signature'          => '在讨论页上的发言应该使用“<nowiki>~~~~</nowiki>”签名，它会被自动转换成你的签名并附带时间戳。',
'badsig'                        => '错误的原始签名。请检查HTML标签。',
'badsiglength'                  => '签名过长。
它的长度不可超过$1个字符。',
'yourgender'                    => '性别：',
'gender-unknown'                => '未指定',
'gender-male'                   => '男',
'gender-female'                 => '女',
'prefs-help-gender'             => '可选：用以软件中的性别指定。此项资料将会被公开。',
'email'                         => '电子邮箱',
'prefs-help-realname'           => '真实姓名为选填项。
如果提供，可用于贡献署名。',
'prefs-help-email'              => '电子邮件地址是可选的但是需要密码重置为别忘了您的密码。',
'prefs-help-email-others'       => '您亦可以在您没有公开自己的用户身分时透过您的用户页或用户讨论页与您联系。',
'prefs-help-email-required'     => '需要电子邮件地址。',
'prefs-info'                    => '基本资料',
'prefs-i18n'                    => '国际化',
'prefs-signature'               => '签名',
'prefs-dateformat'              => '日期格式',
'prefs-timeoffset'              => '时差',
'prefs-advancedediting'         => '高级选项',
'prefs-advancedrc'              => '高级选项',
'prefs-advancedrendering'       => '高级选项',
'prefs-advancedsearchoptions'   => '高级选项',
'prefs-advancedwatchlist'       => '高级选项',
'prefs-displayrc'               => '显示选项',
'prefs-displaysearchoptions'    => '显示选项',
'prefs-displaywatchlist'        => '显示选项',
'prefs-diffs'                   => '差异',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => '电子邮件地址有效',
'email-address-validity-invalid' => '请提供一个有效的电子邮件地址',

# User rights
'userrights'                   => '用户权限管理',
'userrights-lookup-user'       => '管理用户群组',
'userrights-user-editname'     => '输入用户名：',
'editusergroup'                => '编辑用户群组',
'editinguser'                  => "正在更改用户'''[[User:$1|$1]]''' （[[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]） 的用户权限",
'userrights-editusergroup'     => '编辑用户群组',
'saveusergroups'               => '存储用户群组',
'userrights-groupsmember'      => '隶属于：',
'userrights-groupsmember-auto' => '固有隶属于：',
'userrights-groups-help'       => '您可以改动这位用户所属的组群:
* 已剔选的核取方块代表该用户属于该组群。
* 未剔选的核取方块代表该用户不是属于该组群。
* 有 * 项目表示一旦您加入该群组之后便不能移除它，反之亦然。',
'userrights-reason'            => '原因：',
'userrights-no-interwiki'      => '您并没有权限去编辑在其它wiki上的用户权限。',
'userrights-nodatabase'        => '数据库$1不存在或并非为本地的。',
'userrights-nologin'           => '您必须要以管理员帐户[[Special:UserLogin|登录]]之后才可以指定用户权限。',
'userrights-notallowed'        => '您的帐户无权限去指定用户权限。',
'userrights-changeable-col'    => '您可以更改的组群',
'userrights-unchangeable-col'  => '您不可以更改的组群',

# Groups
'group'               => '群组：',
'group-user'          => '用户',
'group-autoconfirmed' => '自动确认用户',
'group-bot'           => '机器人',
'group-sysop'         => '管理员',
'group-bureaucrat'    => '行政员',
'group-suppress'      => '监督',
'group-all'           => '（全部）',

'group-user-member'          => '用户',
'group-autoconfirmed-member' => '自动确认用户',
'group-bot-member'           => '机器人',
'group-sysop-member'         => '管理员',
'group-bureaucrat-member'    => '行政员',
'group-suppress-member'      => '监督',

'grouppage-user'          => '{{ns:project}}:用户',
'grouppage-autoconfirmed' => '{{ns:project}}:自动确认用户',
'grouppage-bot'           => '{{ns:project}}:机器人',
'grouppage-sysop'         => '{{ns:project}}:管理员',
'grouppage-bureaucrat'    => '{{ns:project}}:行政员',
'grouppage-suppress'      => '{{ns:project}}:監督',

# Rights
'right-read'                  => '阅读页面',
'right-edit'                  => '编辑页面',
'right-createpage'            => '建立页面（不含讨论页面）',
'right-createtalk'            => '建立讨论页面',
'right-createaccount'         => '创建新用户账户',
'right-minoredit'             => '标示作小编辑',
'right-move'                  => '移动页面',
'right-move-subpages'         => '移动页面跟它的子页面',
'right-move-rootuserpages'    => '移动根用户页面',
'right-movefile'              => '移动文件',
'right-suppressredirect'      => '当移动页面时不建立来源页面之重定向',
'right-upload'                => '上传文件',
'right-reupload'              => '覆盖现有的文件',
'right-reupload-own'          => '覆盖由同一位上传的文件',
'right-reupload-shared'       => '于本地无视共用媒体文件库上的文件',
'right-upload_by_url'         => '由一个URL上传文件',
'right-purge'                 => '不需要确认之下清除网站快取',
'right-autoconfirmed'         => '编辑半保护页面',
'right-bot'                   => '视为一个自动程序',
'right-nominornewtalk'        => '小编辑不引发新信息提示',
'right-apihighlimits'         => '在API查询中使用更高的上限',
'right-writeapi'              => '使用编写的API',
'right-delete'                => '删除页面',
'right-bigdelete'             => '删除大量历史之页面',
'right-deleterevision'        => '删除及同反删除页面中的指定修订',
'right-deletedhistory'        => '查看已删除的历史项目，不含关联的文本',
'right-deletedtext'           => '查看已删除修订中之已删除的字以及更改',
'right-browsearchive'         => '搜索已删除之页面',
'right-undelete'              => '反删除页面',
'right-suppressrevision'      => '查看和恢复由管理员隐藏的修订',
'right-suppressionlog'        => '查看私人的日志',
'right-block'                 => '封锁其他用户防止编辑',
'right-blockemail'            => '封锁用户不可发电邮',
'right-hideuser'              => '封锁用户名，对公众隐藏',
'right-ipblock-exempt'        => '绕过IP封锁、自动封锁以及范围封锁',
'right-proxyunbannable'       => '绕过Proxy的自动封锁',
'right-unblockself'           => '自我解除封锁',
'right-protect'               => '更改保护等级以及编辑保护页面',
'right-editprotected'         => '编辑保护页面（无连锁保护）',
'right-editinterface'         => '编辑用户接口',
'right-editusercssjs'         => '编辑其他用户的CSS和JavaScript文件',
'right-editusercss'           => '编辑其他用户的CSS文件',
'right-edituserjs'            => '编辑其他用户的JavaScript文件',
'right-rollback'              => '快速回退上一位用户对特定页面的编辑',
'right-markbotedits'          => '标示复原编辑作机械人编辑',
'right-noratelimit'           => '没有使用频率限制',
'right-import'                => '由其它wiki中导入页面',
'right-importupload'          => '由文件上传中导入页面',
'right-patrol'                => '标示其它编辑作已巡查的',
'right-autopatrol'            => '将自己的编辑自动标示为已巡查的',
'right-patrolmarks'           => '查看最近巡查标记更改',
'right-unwatchedpages'        => '查看未监视页面列表',
'right-trackback'             => '提交trackback',
'right-mergehistory'          => '合并页面历史',
'right-userrights'            => '编辑所有用户的权限',
'right-userrights-interwiki'  => '编辑在其它wiki上的用户权限',
'right-siteadmin'             => '锁定和解除锁定数据库',
'right-reset-passwords'       => '重设其他用户的密码',
'right-override-export-depth' => '导出含有五层深度链接页面之页面',
'right-sendemail'             => '发电子邮件给其他用户',
'right-revisionmove'          => '移动修订版本',
'right-disableaccount'        => '禁用账户',

# User rights log
'rightslog'      => '用户权限日志',
'rightslogtext'  => '以下记录了用户权限的更改记录。',
'rightslogentry' => '将 $1 的权限从 $2 改为 $3',
'rightsnone'     => '（无）',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => '阅读这个页面',
'action-edit'                 => '编辑这个页面',
'action-createpage'           => '建立这个页面',
'action-createtalk'           => '建立讨论页面',
'action-createaccount'        => '建立这个用户账户',
'action-minoredit'            => '标示这个编辑为小的',
'action-move'                 => '移动这个页面',
'action-move-subpages'        => '移动这个页面跟它的子页面',
'action-move-rootuserpages'   => '移动根用户页面',
'action-movefile'             => '移动这个文件',
'action-upload'               => '上传这个文件',
'action-reupload'             => '覆盖这个现有的文件',
'action-reupload-shared'      => '覆盖在共用文件库上的文件',
'action-upload_by_url'        => '由一个URL中上传文件',
'action-writeapi'             => '用来写API',
'action-delete'               => '删除这个页面',
'action-deleterevision'       => '删除这次修订',
'action-deletedhistory'       => '查看这个页面的删除历史',
'action-browsearchive'        => '搜索已删除的页面',
'action-undelete'             => '反删除这个页面',
'action-suppressrevision'     => '翻查和恢复这次隐藏修订',
'action-suppressionlog'       => '查看这个私有日志',
'action-block'                => '封锁这位用户的编辑',
'action-protect'              => '更改这个页面的保护等级',
'action-import'               => '由另一个wiki导入这个页面',
'action-importupload'         => '由一个文件上传中导入这个页面',
'action-patrol'               => '标示其它的编辑为已巡查的',
'action-autopatrol'           => '将您的编辑标示为已巡查的',
'action-unwatchedpages'       => '查看未监视页面列表',
'action-trackback'            => '提交trackback',
'action-mergehistory'         => '合并这个页面的历史',
'action-userrights'           => '编辑所有的权限',
'action-userrights-interwiki' => '编辑在其它wiki上用户的权限',
'action-siteadmin'            => '锁定和解除锁定数据库',
'action-revisionmove'         => '移动修订',

# Recent changes
'nchanges'                          => '$1次更改',
'recentchanges'                     => '最近更改',
'recentchanges-legend'              => '最近更改选项',
'recentchangestext'                 => '跟踪这个wiki上的最新更改。',
'recentchanges-feed-description'    => '跟踪此订阅在 wiki 上的最近更改。',
'recentchanges-label-newpage'       => '这次编辑建立了一个新页面',
'recentchanges-label-minor'         => '这是一个小编辑',
'recentchanges-label-bot'           => '这次编辑是由机器人进行',
'recentchanges-label-unpatrolled'   => '这次编辑尚未巡查过',
'rcnote'                            => "以下是在$4 $5，最近'''$2'''天内的'''$1'''次最近更改记录：",
'rcnotefrom'                        => "以下是自'''$2'''的更改（最多显示'''$1'''）：",
'rclistfrom'                        => '显示自$1以来的新更改',
'rcshowhideminor'                   => '$1小编辑',
'rcshowhidebots'                    => '$1机器人的编辑',
'rcshowhideliu'                     => '$1登录用户的编辑',
'rcshowhideanons'                   => '$1匿名用户的编辑',
'rcshowhidepatr'                    => '$1巡查过的编辑',
'rcshowhidemine'                    => '$1我的编辑',
'rclinks'                           => '显示最近$2天内最新的$1次改动。<br />$3',
'diff'                              => '差异',
'hist'                              => '历史',
'hide'                              => '隐藏',
'show'                              => '显示',
'minoreditletter'                   => '小',
'newpageletter'                     => '新',
'boteditletter'                     => '机',
'number_of_watching_users_pageview' => '[$1个关注用户]',
'rc_categories'                     => '分类界限（以“|”分割）',
'rc_categories_any'                 => '任意',
'newsectionsummary'                 => '/* $1 */ 新段落',
'rc-enhanced-expand'                => '显示细节（需JavaScript支持）',
'rc-enhanced-hide'                  => '隐藏细节',

# Recent changes linked
'recentchangeslinked'          => '链出更改',
'recentchangeslinked-feed'     => '链出更改',
'recentchangeslinked-toolbox'  => '链出更改',
'recentchangeslinked-title'    => '对于“$1”有关的链出更改',
'recentchangeslinked-noresult' => '在这一段时间中链接的页面并无更改。',
'recentchangeslinked-summary'  => "这一个特殊页面列示''由''所给出的一个页面之链接到页面的最近更改（或者是对于指定分类的成员）。
在[[Special:Watchlist|您的监视列表]]中的页面会以'''粗体'''显示。",
'recentchangeslinked-page'     => '页面名称：',
'recentchangeslinked-to'       => '显示链到所给出的页面',

# Upload
'upload'                      => '上传文件',
'uploadbtn'                   => '上传文件',
'reuploaddesc'                => '取消上传并返回上传表单',
'upload-tryagain'             => '提交修改后的文件描述',
'uploadnologin'               => '未登录',
'uploadnologintext'           => '您必须先[[Special:UserLogin|登录]]才能上传文件。',
'upload_directory_missing'    => '上传目录（$1）遗失，不能由网页服务器建立。',
'upload_directory_read_only'  => '上传目录（$1）不存在或无写权限。',
'uploaderror'                 => '上传错误',
'upload-recreate-warning'     => "'''警告：一个相同名字的文件曾经被删除或者移动至别处。'''

这个页面的删除和移动日志在这里提供以便参考：",
'uploadtext'                  => "使用下面的表单来上传文件。
要查看或搜索以前上传的文件，可以进入[[Special:FileList|文件上传列表]]，（重新）上传也将在[[Special:Log/upload|上传日志]]中记录，而删除将在[[Special:Log/delete|删除日志]]中记录。

要在页面中加入文件，使用以下其中一种形式的链接：
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>'''使用文件的完整版本
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|替换文字]]</nowiki></tt>'''使用放置于左侧的一个框内的200像素宽的图片，同时使用“替换文字”作为描述
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>'''直接链接到文件而不显示文件",
'upload-permitted'            => '允许的文件类型：$1。',
'upload-preferred'            => '建议的文件类型：$1。',
'upload-prohibited'           => '禁止的文件类型：$1。',
'uploadlog'                   => '上传日志',
'uploadlogpage'               => '上传日志',
'uploadlogpagetext'           => '以下是一个最近上传文件的列表。
查看[[Special:NewFiles|新文件画廊]]去看更富图像的总览。',
'filename'                    => '文件名',
'filedesc'                    => '文件描述',
'fileuploadsummary'           => '文件描述：',
'filereuploadsummary'         => '文件更改：',
'filestatus'                  => '版权状态：',
'filesource'                  => '来源：',
'uploadedfiles'               => '已上传文件',
'ignorewarning'               => '忽略警告并保存文件',
'ignorewarnings'              => '忽略所有警告',
'minlength1'                  => '文件名字必须至少有一个字母。',
'illegalfilename'             => '文件名「$1」包含有页面标题所禁止的字符。请改名后重新上传。',
'badfilename'                 => '文件名已被改为“$1”。',
'filetype-mime-mismatch'      => '文件扩展名 ".$1" 不配所侦测档案的MIME类型 ($2)。',
'filetype-badmime'            => 'MIME类别"$1"不是容许的文件格式。',
'filetype-bad-ie-mime'        => '不可以上传这个文件，因为 Internet Explorer 会将它侦测为 "$1"，它是一种不容许以及有潜在危险性之文件类型。',
'filetype-unwanted-type'      => "'''\".\$1\"'''是一种不需要的文件类型。
建议的{{PLURAL:\$3|一种|多种}}文件类型有\$2。",
'filetype-banned-type'        => '\'\'\'".$1"\'\'\'{{PLURAL:$4|不是一个允许的文件类型|不是一个允许的文件类型}}。
允许 {{PLURAL:$3|文件类型是|文件类型是}} $2。',
'filetype-missing'            => '该文件名称并没有扩展名（例如“.jpg”）。',
'empty-file'                  => '您所提交的文件为空文件。',
'file-too-large'              => '您所提交的文件过大。',
'filename-tooshort'           => '文件名过短。',
'filetype-banned'             => '此类文件被禁止。',
'verification-error'          => '文件未通过验证。',
'hookaborted'                 => '您所尝试的修改被插件钩子舍弃。',
'illegal-filename'            => '文件名非法。',
'overwrite'                   => '不允许覆盖现有文件。',
'unknown-error'               => '发生未知错误。',
'tmp-create-error'            => '无法创建临时文件。',
'tmp-write-error'             => '临时文件写入发生错误。',
'large-file'                  => '建议文件大小不能超过 $1；本文件大小为 $2。',
'largefileserver'             => '这个文件的大小比服务器配置允许的大小还要大。',
'emptyfile'                   => '您所上传的文件不存在。这可能是由于文件名键入错误。请检查您是否真的要上传此文件。',
'fileexists'                  => '已存在相同名称的文件，如果您无法确定您是否要改变它，请检查<strong><tt>[[:$1]]</tt></strong>。 [[$1|thumb]]',
'filepageexists'              => '这个文件的描述页已经于<strong><tt>[[:$1]]</tt></strong>创建，但是这个名称的文件尚不存在。
您输入的摘要不会显示在该描述页中。
要令该摘要在该处中出现，您需要手动地编辑该页。
[[$1|thumb]]',
'fileexists-extension'        => "一个相似名称的文件已经存在: [[$2|thumb]]
* 上传文件的文件名：'''<tt>[[:$1]]</tt>'''
* 现有文件的文件名：'''<tt>[[:$2]]</tt>'''
请选择一个不同的名字。",
'fileexists-thumbnail-yes'    => "此文件可能是另一幅图像的缩小版本''（缩略图）''。 [[$1|thumb]]
请仔细检查该文件'''<tt>[[:$1]]</tt>'''。
如果被检查文件与原始大小的图像是同一幅图像，您无需上传多余的缩略图。",
'file-thumbnail-no'           => "文件名以'''<tt>$1</tt>'''开头。它可能是另一幅图像的缩小版本''（缩略图）''。
如果你有该图像完整分辨率的版本，请上传该完整版本。否则请修改文件名。",
'fileexists-forbidden'        => '已存在相同名称的文件，且不能覆盖；请返回并用一个新的名称来上传此文件。[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => '在共享文件库中已存在此名称的文件。
如果你仍然想去上传它的话，请返回并用一个新的名称来上传此文件。[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => '这个文件与以下{{PLURAL:$1|一|多}}个文件重复：',
'file-deleted-duplicate'      => '一个相同名称的文件 （[[$1]]） 在先前删除过。您应该在重新上传之前检查一下该文件之删除纪录。',
'uploadwarning'               => '上传警告',
'uploadwarning-text'          => '请修改以下的文件描述并重试。',
'savefile'                    => '保存文件',
'uploadedimage'               => '已上传“[[$1]]”',
'overwroteimage'              => '已上传“[[$1]]”的新版本',
'uploaddisabled'              => '上传己停用。',
'copyuploaddisabled'          => '通过网址上传功能未开通。',
'uploadfromurl-queued'        => '上传已被列入队列。',
'uploaddisabledtext'          => '文件上传不可用。',
'php-uploaddisabledtext'      => 'PHP 文件上传已经停用。请检查 file_uploads 设置。',
'uploadscripted'              => '该文件包含可能被网络浏览器错误解释的 HTML 或脚本代码。',
'uploadvirus'                 => '该文件包含病毒！
详情：$1',
'upload-source'               => '来源文件',
'sourcefilename'              => '源文件名：',
'sourceurl'                   => '来源网址：',
'destfilename'                => '目标文件名：',
'upload-maxfilesize'          => '文件最大限制大小: $1',
'upload-description'          => '文件描述',
'upload-options'              => '上传选项',
'watchthisupload'             => '监视这个文件',
'filewasdeleted'              => '之前已经有一个同名文件被上传后又被删除了。在上传此文件之前您需要检查$1。',
'upload-wasdeleted'           => "'''警告：您现在重新上传一个先前曾经删除过的文件。'''

您应该要考虑一下继续上传一个文件页面是否合适。
为方便起见，这一个文件的删除记录已经在下面提供:",
'filename-bad-prefix'         => '您上传的文件名称是以<strong>“$1”</strong>作为开头，通常这种没有含意的文件名称是由数码相机中自动编排。请在您的文件中重新选择一个更加有意义的文件名称。',
'upload-success-subj'         => '上传成功',
'upload-success-msg'          => '您在[$2]的上传已经成功，可以在这里找到：[[:{{ns:file}}:$1]]',
'upload-failure-subj'         => '上传问题',
'upload-failure-msg'          => '您在[$2]的上传出现了问题：

$1',
'upload-warning-subj'         => '上传警告',
'upload-warning-msg'          => '您自[$2]的上传出错。您可以返回[[Special:Upload/stash/$1|上传表单]]并更正问题。',

'upload-proto-error'        => '协议错误',
'upload-proto-error-text'   => '远程上传要求 URL 以 <code>http://</code> 或 <code>ftp://</code> 开头。',
'upload-file-error'         => '内部错误',
'upload-file-error-text'    => '当试图在服务器上创建临时文件时发生内部错误。请与[[Special:ListUsers/sysop|管理员]]联系。',
'upload-misc-error'         => '未知的上传错误',
'upload-misc-error-text'    => '在上传时发生未知的错误。请确认您使用了正确并可访问的URL，然后进行重试。如果问题仍然存在，请与[[Special:ListUsers/sysop|管理员]]联系。',
'upload-too-many-redirects' => '在网址中有太多重新定向',
'upload-unknown-size'       => '未知大小',
'upload-http-error'         => '发生HTTP错误：$1',

# Special:UploadStash
'uploadstash'          => '文件贮藏',
'uploadstash-summary'  => '这个页面提供已经上传（或者上传中）但未发布到wiki之文件存取。这些文件除了上传的用户之外不会被其他人可见。',
'uploadstash-clear'    => '清除贮藏文件',
'uploadstash-nofiles'  => '你没有已贮藏的文件。',
'uploadstash-badtoken' => '进行这个动作不成功，或者你的编辑信息已经过期。请再试。',
'uploadstash-errclear' => '清除文件不成功。',
'uploadstash-refresh'  => '更新文件清单',

# img_auth script messages
'img-auth-accessdenied' => '拒绝访问',
'img-auth-nopathinfo'   => 'PATH_INFO遗失。您的服务器尚未设置传送该信息。它可能是基于CGI的，因而不支持img_auth。参见http://www.mediawiki.org/wiki/Manual:Image_Authorization<nowiki></nowiki>。',
'img-auth-notindir'     => '在已设置的上传目录中找不到请求的路径。',
'img-auth-badtitle'     => '无法为“$1”创建合法的标题。',
'img-auth-nologinnWL'   => '您尚未登录，且“$1”不在白名单上。',
'img-auth-nofile'       => '文件“$1”不存在。',
'img-auth-isdir'        => '您正试图访问目录“$1”。您只能访问文件。',
'img-auth-streaming'    => '流式化“$1”中。',
'img-auth-public'       => 'img_auth.php的功能是从私有wiki输出文件。但本wiki已被设置为公共wiki。出于安全考虑，img_auth.php已被停用。',
'img-auth-noread'       => '用户无权读取“$1”。',

# HTTP errors
'http-invalid-url'      => '无效URL：$1',
'http-invalid-scheme'   => '不支持带有“$1”的URL',
'http-request-error'    => '未知的错误令到HTTP请求失败。',
'http-read-error'       => 'HTTP读取错误。',
'http-timed-out'        => 'HTTP请求已过时。',
'http-curl-error'       => '撷取URL时出错：$1',
'http-host-unreachable' => '无法到达URL。',
'http-bad-status'       => '进行HTTP请求时出现问题：$1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => '无法访问URL',
'upload-curl-error6-text'  => '无法访问所提供的URL。请复查该URL是否正确，及其网站是否在线。',
'upload-curl-error28'      => '上传超时',
'upload-curl-error28-text' => '站点响应时间过长。请检查此网站的访问是否正常，过一会再进行尝试。您可能需要在网络访问空闲时间再次进行尝试。',

'license'            => '授权：',
'license-header'     => '授权',
'nolicense'          => '未选定',
'license-nopreview'  => '（无预览可用）',
'upload_source_url'  => '（有效、可以公开访问的URL）',
'upload_source_file' => '（您计算机上的一个文件）',

# Special:ListFiles
'listfiles-summary'     => '这个特殊页面显示所有已上传文件。
默认设置中，最后上传的文件会显示在这个列表的顶端。
点击任一列标题可修改排序方式。',
'listfiles_search_for'  => '按媒体名称搜索：',
'imgfile'               => '文件',
'listfiles'             => '文件列表',
'listfiles_thumb'       => '缩略图',
'listfiles_date'        => '日期',
'listfiles_name'        => '名称',
'listfiles_user'        => '用户',
'listfiles_size'        => '大小',
'listfiles_description' => '描述',
'listfiles_count'       => '版本',

# File description page
'file-anchor-link'                  => '文件',
'filehist'                          => '文件历史',
'filehist-help'                     => '点击日期／时间以查看当时出现过的文件。',
'filehist-deleteall'                => '删除全部',
'filehist-deleteone'                => '删除',
'filehist-revert'                   => '恢复',
'filehist-current'                  => '当前',
'filehist-datetime'                 => '日期／时间',
'filehist-thumb'                    => '缩略图',
'filehist-thumbtext'                => '于$1的缩图版本',
'filehist-nothumb'                  => '没有缩图',
'filehist-user'                     => '用户',
'filehist-dimensions'               => '维度',
'filehist-filesize'                 => '文件大小',
'filehist-comment'                  => '注解',
'filehist-missing'                  => '文件遗失',
'imagelinks'                        => '文件链接',
'linkstoimage'                      => '以下的$1个页面链接到本文件：',
'linkstoimage-more'                 => '多于$1个页面连接到这个文件。
下面的列表只列示了连去这个文件的最首$1个页面。
一个[[Special:WhatLinksHere/$2|完整的列表]]可以提供。',
'nolinkstoimage'                    => '没有页面链接到本文件。',
'morelinkstoimage'                  => '查看连接到这个文件的[[Special:WhatLinksHere/$1|更多链接]]。',
'redirectstofile'                   => '以下的$1个文件重新定向到这个文件：',
'duplicatesoffile'                  => '以下的$1个文件跟这个文件重复（[[Special:FileDuplicateSearch/$2|更多细节]]）：',
'sharedupload'                      => '该文件来自于$1，它可能在其它计划项目中被应用。',
'sharedupload-desc-there'           => '该文件来自于$1，它可能在其它计划项目中被应用。
请参阅在[$2 文件描述页面]以了解其相关信息。',
'sharedupload-desc-here'            => '该文件来自于$1，它可能在其它计划项目中被应用。
它在[$2 文件描述页面]那边上的描述于下面显示。',
'filepage-nofile'                   => '不存在此名称的文件。',
'filepage-nofile-link'              => '不存在此名称的文件，但您可以[$1 上传它]。',
'uploadnewversion-linktext'         => '上传该文件的新版本',
'shared-repo-from'                  => '出自$1',
'shared-repo'                       => '一个共用文件库',
'shared-repo-name-wikimediacommons' => '维基共享资源',

# File reversion
'filerevert'                => '恢复$1',
'filerevert-legend'         => '恢复文件',
'filerevert-intro'          => "您现正在恢复文件'''[[Media:$1|$1]]'''到[$4 于$2 $3的版本]。",
'filerevert-comment'        => '理由：',
'filerevert-defaultcomment' => '已经恢复到于$1 $2的版本',
'filerevert-submit'         => '恢复',
'filerevert-success'        => "'''[[Media:$1|$1]]'''已经恢复到[$4 于$2 $3的版本]。",
'filerevert-badversion'     => '文件并无所请求时间戳下的早期本地版本。',

# File deletion
'filedelete'                  => '删除$1',
'filedelete-legend'           => '删除文件',
'filedelete-intro'            => "您现正删除文件'''[[Media:$1|$1]]'''。",
'filedelete-intro-old'        => "你现正删除'''[[Media:$1|$1]]'''于[$4 $2 $3]的版本。",
'filedelete-comment'          => '理由：',
'filedelete-submit'           => '删除',
'filedelete-success'          => "'''$1'''已经删除。",
'filedelete-success-old'      => "'''[[Media:$1|$1]]'''于 $2 $3 的版本已经删除。",
'filedelete-nofile'           => "'''$1'''不存在。",
'filedelete-nofile-old'       => "在已指定属性的情况下，这里没有'''$1'''的保存版本。",
'filedelete-otherreason'      => '其它／附加的理由：',
'filedelete-reason-otherlist' => '其他原因',
'filedelete-reason-dropdown'  => '
*常用删除理由
** 侵犯版权
** 重复文件',
'filedelete-edit-reasonlist'  => '编辑删除埋由',
'filedelete-maintenance'      => '当在维护时已经暂时停用文件删除和恢复。',

# MIME search
'mimesearch'         => 'MIME 搜索',
'mimesearch-summary' => '本页面启用文件MIME类型过滤器。输入：内容类型/子类型，如 <tt>image/jpeg</tt>。',
'mimetype'           => 'MIME 类型：',
'download'           => '下载',

# Unwatched pages
'unwatchedpages' => '未被监视的页面',

# List redirects
'listredirects' => '重定向页面列表',

# Unused templates
'unusedtemplates'     => '未使用的模板',
'unusedtemplatestext' => '此页面列出{{ns:template}}名字空间下所有未被其它页面使用的页面。请在删除这些模板前检查其它链入该模板的页面。',
'unusedtemplateswlh'  => '其它链接',

# Random page
'randompage'         => '随机页面',
'randompage-nopages' => '在以下的{{PLURAL:$2|名字空间}}中没有页面：$1',

# Random redirect
'randomredirect'         => '随机重定向页面',
'randomredirect-nopages' => '在 "$1" 名字空间中没有重定向页面。',

# Statistics
'statistics'                   => '统计',
'statistics-header-pages'      => '页面统计',
'statistics-header-edits'      => '编辑统计',
'statistics-header-views'      => '查看统计',
'statistics-header-users'      => '用户统计',
'statistics-header-hooks'      => '其它统计',
'statistics-articles'          => '内容页面',
'statistics-pages'             => '页面',
'statistics-pages-desc'        => '在wiki上的所有页面，包括对话页面、重新定向等',
'statistics-files'             => '已经上传的文件',
'statistics-edits'             => '自从{{SITENAME}}设置的页面编辑数',
'statistics-edits-average'     => '每一页面的平均编辑数',
'statistics-views-total'       => '查看总数',
'statistics-views-total-desc'  => '不存在页面和特殊页面的查看数未计入',
'statistics-views-peredit'     => '每次编辑查看数',
'statistics-users'             => '已注册[[Special:ListUsers|用户]]',
'statistics-users-active'      => '活跃用户',
'statistics-users-active-desc' => '在前$1天中操作过的用户',
'statistics-mostpopular'       => '浏览最多的页面',

'disambiguations'      => '消歧义页',
'disambiguationspage'  => 'Template:disambig
Template:消含糊
Template:消除含糊
Template:消歧义
Template:消除歧义
Template:消歧義
Template:消除歧義',
'disambiguations-text' => '以下的页面都有到<b>消歧义页</b>的链接, 但它们应该是链到适当的标题。<br />一个页面会被视为消歧义页如果它是链自[[MediaWiki:Disambiguationspage]]。',

'doubleredirects'            => '双重重定向页面',
'doubleredirectstext'        => '此页列出了所有重定向到另一重定向页面的页面。每一行都包含有到第一和第二个重定向页面的链接，以及第二个重定向页面的目标——通常就是“真正的”目标页面，亦即是第一个重定向页面应该指向的页面。<del>已划去</del>的为已经解决的项目。',
'double-redirect-fixed-move' => '[[$1]]已经完成移动，它现在重定向到[[$2]]。',
'double-redirect-fixer'      => '重定向修正器',

'brokenredirects'        => '损坏的重定向页',
'brokenredirectstext'    => '以下的重定向页面指向的是不存在的页面：',
'brokenredirects-edit'   => '编辑',
'brokenredirects-delete' => '删除',

'withoutinterwiki'         => '未有语言链接的页面',
'withoutinterwiki-summary' => '以下的页面是未有语言链接到其它语言版本。',
'withoutinterwiki-legend'  => '前缀',
'withoutinterwiki-submit'  => '显示',

'fewestrevisions' => '最少修订的页面',

# Miscellaneous special pages
'nbytes'                  => '$1字节',
'ncategories'             => '$1个分类',
'nlinks'                  => '$1个链接',
'nmembers'                => '$1个成员',
'nrevisions'              => '$1个修订',
'nviews'                  => '$1次浏览',
'nimagelinks'             => '用于$1个页面中',
'ntransclusions'          => '用于$1个页面中',
'specialpage-empty'       => '这个报告的结果为空。',
'lonelypages'             => '孤立页面',
'lonelypagestext'         => '以下页面尚未被{{SITENAME}}中的其它页面链接或被之包含。',
'uncategorizedpages'      => '未归类页面',
'uncategorizedcategories' => '未归类分类',
'uncategorizedimages'     => '未归类文件',
'uncategorizedtemplates'  => '未归类模板',
'unusedcategories'        => '未使用分类',
'unusedimages'            => '未使用图像',
'popularpages'            => '热点页面',
'wantedcategories'        => '待撰分类',
'wantedpages'             => '待撰页面',
'wantedpages-badtitle'    => '在结果组上的无效标题：$1',
'wantedfiles'             => '需要的文件',
'wantedtemplates'         => '需要的模板',
'mostlinked'              => '最多链接页面',
'mostlinkedcategories'    => '最多链接分类',
'mostlinkedtemplates'     => '最多链接模板',
'mostcategories'          => '最多分类页面',
'mostimages'              => '最多链接文件',
'mostrevisions'           => '最多修订页面',
'prefixindex'             => '所有页面之前缀',
'shortpages'              => '短页面',
'longpages'               => '长页面',
'deadendpages'            => '断链页面',
'deadendpagestext'        => '以下页面没有链接到{{SITENAME}}中的其它页面。',
'protectedpages'          => '已保护页面',
'protectedpages-indef'    => '只有无期之保护页面',
'protectedpages-cascade'  => '只有连锁之保护页面',
'protectedpagestext'      => '以下页面已经被保护以防止移移或编辑',
'protectedpagesempty'     => '在这些参数下没有页面正在保护。',
'protectedtitles'         => '已保护的标题',
'protectedtitlestext'     => '以下的页面已经被保护以防止创建',
'protectedtitlesempty'    => '在这些参数之下并无标题正在保护。',
'listusers'               => '用户列表',
'listusers-editsonly'     => '只显示有编辑的用户',
'listusers-creationsort'  => '按建立日期排序',
'usereditcount'           => '$1次编辑',
'usercreated'             => '于$1 $2建立',
'newpages'                => '最新页面',
'newpages-username'       => '用户名：',
'ancientpages'            => '最旧页面',
'move'                    => '移动',
'movethispage'            => '移动此页',
'unusedimagestext'        => '下列文件已存在，但并未插入任何页面。
请注意其它网站可能会直接通过URL链接此文件，因此下面列出的文件依然有可能被使用。',
'unusedcategoriestext'    => '虽然没有被其它页面或者分类所采用，但列表中的分类页依然存在。',
'notargettitle'           => '无目标',
'notargettext'            => '您还没有指定一个目标页面或用户以进行此项操作。',
'nopagetitle'             => '无目标页面',
'nopagetext'              => '您所指定的目标页面并不存在。',
'pager-newer-n'           => '新$1次',
'pager-older-n'           => '旧$1次',
'suppress'                => '监督',
'querypage-disabled'      => '此特殊页面基于效能的原因已经被停用。',

# Book sources
'booksources'               => '网络书源',
'booksources-search-legend' => '搜索网络书源',
'booksources-isbn'          => '国际书号:',
'booksources-go'            => '转到',
'booksources-text'          => '以下是一些网络书店的链接列表，其中可能有您要找的书籍的更多信息：',
'booksources-invalid-isbn'  => '提供的ISBN号码并不正确，请检查原始复制来源号码是否有误。',

# Special:Log
'specialloguserlabel'  => '用户：',
'speciallogtitlelabel' => '标题：',
'log'                  => '日志',
'all-logs-page'        => '所有公共日志',
'alllogstext'          => '综合显示{{SITENAME}}所有的可用日志。
您可以选择日志类型，用户名（区分大小写）或者相关页面（区分大小写）来缩小查询范围。',
'logempty'             => '在日志中不存在匹配项。',
'log-title-wildcard'   => '搜索以这个文字开始的标题',

# Special:AllPages
'allpages'          => '所有页面',
'alphaindexline'    => '$1到$2',
'nextpage'          => '下一页（$1）',
'prevpage'          => '上一页（$1）',
'allpagesfrom'      => '显示从此处开始的页面：',
'allpagesto'        => '显示从此处结束的页面：',
'allarticles'       => '所有页面',
'allinnamespace'    => '所有页面（属于$1名字空间）',
'allnotinnamespace' => '所有页面（不属于$1名字空间）',
'allpagesprev'      => '前',
'allpagesnext'      => '后',
'allpagessubmit'    => '提交',
'allpagesprefix'    => '显示具有此前缀（名字空间）的页面：',
'allpagesbadtitle'  => '给定的页面标题是非法的，或者具有一个内部语言或内部 wiki 的前缀。它可能包含一个或更多的不能用于标题的字符。',
'allpages-bad-ns'   => '在{{SITENAME}}中没有一个叫做"$1"的名字空间。',

# Special:Categories
'categories'                    => '页面分类',
'categoriespagetext'            => '以下的{{PLURAL:$1|分类}}中包含了页面或媒体。
[[Special:UnusedCategories|未用分类]]不会在这里列示。
请同时参阅[[Special:WantedCategories|需要的分类]]。',
'categoriesfrom'                => '显示由此项起之分类：',
'special-categories-sort-count' => '按数量排列',
'special-categories-sort-abc'   => '按字母排列',

# Special:DeletedContributions
'deletedcontributions'             => '已删除的用户贡献',
'deletedcontributions-title'       => '已删除的用户贡献',
'sp-deletedcontributions-contribs' => '贡献',

# Special:LinkSearch
'linksearch'       => '外部链接',
'linksearch-pat'   => '搜索网址：',
'linksearch-ns'    => '名字空间：',
'linksearch-ok'    => '搜索',
'linksearch-text'  => '可以使用类似"*.wikipedia.org"的通配符。<br />
已支持：<tt>$1</tt>',
'linksearch-line'  => '$1 链自 $2',
'linksearch-error' => '通配符仅可在主机名称的开头使用。',

# Special:ListUsers
'listusersfrom'      => '给定显示用户条件：',
'listusers-submit'   => '显示',
'listusers-noresult' => '找不到用户。',
'listusers-blocked'  => '（已封锁）',

# Special:ActiveUsers
'activeusers'            => '活跃用户列表',
'activeusers-intro'      => '这个列表列出了最近$1天进行过操作的用户。',
'activeusers-count'      => '最近$3天编辑了$1次',
'activeusers-from'       => '显示用户开始于：',
'activeusers-hidebots'   => '隐藏机器人',
'activeusers-hidesysops' => '隐藏管理员',
'activeusers-noresult'   => '找不到用户。',

# Special:Log/newusers
'newuserlogpage'              => '用户创建日志',
'newuserlogpagetext'          => '本日志是显示新注册用户的日志',
'newuserlog-byemail'          => '密码已由电子邮件发出',
'newuserlog-create-entry'     => '新用户账户',
'newuserlog-create2-entry'    => '已创建$1的新账户',
'newuserlog-autocreate-entry' => '已自动建立账户',

# Special:ListGroupRights
'listgrouprights'                      => '用户群组权限',
'listgrouprights-summary'              => '以下面是一个在这个wiki中定义出来的用户权限列表，以及它们的访问权。
更多有关个别权限的细节可以在[[{{MediaWiki:Listgrouprights-helppage}}|这里]]找到。',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">已授予的权限</span>
* <span class="listgrouprights-revoked">已撤除的权限</span>',
'listgrouprights-group'                => '群组',
'listgrouprights-rights'               => '权限',
'listgrouprights-helppage'             => 'Help:群组权限',
'listgrouprights-members'              => '（成员列表）',
'listgrouprights-addgroup'             => '加入的{{PLURAL:$2|一个|多个}}群组：$1',
'listgrouprights-removegroup'          => '移除的{{PLURAL:$2|一个|多个}}群组：$1',
'listgrouprights-addgroup-all'         => '加入所有群组',
'listgrouprights-removegroup-all'      => '移除所有群组',
'listgrouprights-addgroup-self'        => '在自己的账户中加入的{{PLURAL:$2|一个|多个}}群组: $1',
'listgrouprights-removegroup-self'     => '在自己的账户中移除的{{PLURAL:$2|一个|多个}}群组: $1',
'listgrouprights-addgroup-self-all'    => '在自己的账户中加入所有群组',
'listgrouprights-removegroup-self-all' => '在自己的账户中移除所有群组',

# E-mail user
'mailnologin'          => '无电邮地址',
'mailnologintext'      => '您必须先[[Special:UserLogin|登录]]
并在[[Special:Preferences|参数设置]]
中有一个有效的电子邮箱地址才可以向其他用户发邮件。',
'emailuser'            => '向该用户发邮件',
'emailpage'            => '向用户发邮件',
'emailpagetext'        => '您可以用下面的表格去寄一封电邮给这位用户。
您在[[Special:Preferences|您参数设置]]中所输入的电子邮箱地址将出现在邮件"发件人"一栏中，这样该用户就可以回复您。',
'usermailererror'      => 'Mail 对象返回错误：',
'defemailsubject'      => '{{SITENAME}}电子邮件',
'usermaildisabled'     => '用户电邮已停用',
'usermaildisabledtext' => '您不可以发送电邮到这个wiki上的其他用户',
'noemailtitle'         => '无电子邮件地址',
'noemailtext'          => '该用户还没有指定一个有效的电子邮件地址。',
'nowikiemailtitle'     => '不容许电子邮件',
'nowikiemailtext'      => '这位用户选择不接收其他用户的电子邮件。',
'email-legend'         => '发一封电子邮件至另一位{{SITENAME}}用户',
'emailfrom'            => '发件人：',
'emailto'              => '收件人：',
'emailsubject'         => '主题：',
'emailmessage'         => '信息：',
'emailsend'            => '发送',
'emailccme'            => '将我的消息的副本发送一份到我的邮箱。',
'emailccsubject'       => '将您的消息复制到 $1：$2',
'emailsent'            => '电子邮件已发送',
'emailsenttext'        => '您的电子邮件已经发出。',
'emailuserfooter'      => '这封电邮是由$1寄给$2经{{SITENAME}}的“电邮用户”功能发出的。',

# User Messenger
'usermessage-summary' => '留下系统信息。',
'usermessage-editor'  => '系统界面',

# Watchlist
'watchlist'            => '监视列表',
'mywatchlist'          => '监视列表',
'watchlistfor2'        => '$1的监视列表 $2',
'nowatchlist'          => '您的监视列表为空。',
'watchlistanontext'    => '请$1以查看或编辑您的监视列表。',
'watchnologin'         => '未登录',
'watchnologintext'     => '您必须先[[Special:UserLogin|登录]]才能更改您的监视列表。',
'addedwatch'           => '已添加至监视列表',
'addedwatchtext'       => "页面“[[:$1]]”已经被加入到您的[[Special:Watchlist|监视列表]]中。将来有关此页面及其讨论页的任何修改将会该列表中列出，且会在[[Special:RecentChanges|最近更改]]中以'''粗体'''形式列出以便识别。",
'removedwatch'         => '已停止监视',
'removedwatchtext'     => '页面[[:$1]]已经从[[Special:Watchlist|您的监视页面]]中移除。',
'watch'                => '监视',
'watchthispage'        => '监视此页',
'unwatch'              => '取消监视',
'unwatchthispage'      => '停止监视',
'notanarticle'         => '不是页面',
'notvisiblerev'        => '上次由不同用户所作的修订版本已经删除',
'watchnochange'        => '在显示的时间段内您所监视的页面没有更改。',
'watchlist-details'    => '不包含讨论页，有 $1 页在您的监视列表上。',
'wlheader-enotif'      => '* 已经启动电子邮件通知功能。',
'wlheader-showupdated' => "* 在你上次查看后有被修改过的页面会显示为'''粗体'''",
'watchmethod-recent'   => '检查被监视页面的最近编辑',
'watchmethod-list'     => '查看监视页中的最新修改',
'watchlistcontains'    => '您的监视列表包含$1个页面。',
'iteminvalidname'      => "页面'$1'错误，无效命名...",
'wlnote'               => "以下是最近'''$2'''小时内的最后'''$1'''次修改:",
'wlshowlast'           => '显示最近$1小时 $2天 $3的修改',
'watchlist-options'    => '监视列表选项',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => '监视……',
'unwatching' => '解除监视……',

'enotif_mailer'                => '{{SITENAME}}邮件通知器',
'enotif_reset'                 => '将所有页面标为已读',
'enotif_newpagetext'           => '这是新建页面。',
'enotif_impersonal_salutation' => '{{SITENAME}}用户',
'changed'                      => '修改了',
'created'                      => '建立了',
'enotif_subject'               => '{{SITENAME}}有页面 $PAGETITLE 被 $PAGEEDITOR $CHANGEDORCREATED',
'enotif_lastvisited'           => '查看您上次访问后的所有更改请访问$1。',
'enotif_lastdiff'              => '检视更改请访问$1。',
'enotif_anon_editor'           => '匿名用户$1',
'enotif_body'                  => '亲爱的 $WATCHINGUSERNAME，

$PAGEEDITOR已经在$PAGEEDITDATE$CHANGEDORCREATED{{SITENAME}}的$PAGETITLE页面，请到$PAGETITLE_URL查看当前修订版本。

$NEWPAGE

编辑摘要：$PAGESUMMARY $PAGEMINOREDIT

联系此编辑者：

邮件：$PAGEEDITOR_EMAIL

本站：$PAGEEDITOR_WIKI

在您访问此页之前，将来的更改将不会向您发通知。您也可以重设您所有监视页面的通知标记。

                {{SITENAME}}通知系统

--
要改变您的监视列表设置，请访问
{{fullurl:{{#special:Watchlist}}/edit}}

要删除您监视列表中的该页面，请访问
$UNWATCHURL

反馈和进一步的帮助:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => '删除页面',
'confirm'                => '确认',
'excontent'              => '内容为：“$1”',
'excontentauthor'        => '内容为：“$1”（而且唯一贡献者为“$2”）',
'exbeforeblank'          => '被清空前的内容为：“$1”',
'exblank'                => '页面为空',
'delete-confirm'         => '删除“$1”',
'delete-legend'          => '删除',
'historywarning'         => '警告：您将要删除的页面有约$1个{{PLURAL:$1|修订|修订}}版本的历史：',
'confirmdeletetext'      => '您即将删除一个页面或图像以及其历史。
请确定您要进行此项操作，并且了解其后果，同时您的行为符合[[{{MediaWiki:Policy-url}}]]。',
'actioncomplete'         => '操作完成',
'actionfailed'           => '操作失败',
'deletedtext'            => '"<nowiki>$1</nowiki>"已经被删除。最近删除的记录请参见$2。',
'deletedarticle'         => '已删除“[[$1]]”',
'suppressedarticle'      => '已隐藏“[[$1]]”',
'dellogpage'             => '删除日志',
'dellogpagetext'         => '以下是最近的删除的列表。',
'deletionlog'            => '删除日志',
'reverted'               => '恢复到早期版本',
'deletecomment'          => '理由：',
'deleteotherreason'      => '其它／附加的理由：',
'deletereasonotherlist'  => '其他原因',
'deletereason-dropdown'  => '*常用删除理由
** 作者请求
** 侵犯版权
** 破坏',
'delete-edit-reasonlist' => '编辑删除理由',
'delete-toobig'          => '这个页面有一个十分大量的编辑历史，超过$1次修订。删除此类页面的动作已经被限制，以防止在{{SITENAME}}上的意外扰乱。',
'delete-warning-toobig'  => '这个页面有一个十分大量的编辑历史，超过$1次修订。删除它可能会扰乱{{SITENAME}}的数据库操作；在继续此动作前请小心。',

# Rollback
'rollback'          => '回退编辑',
'rollback_short'    => '回退',
'rollbacklink'      => '回退',
'rollbackfailed'    => '回退失败',
'cantrollback'      => '无法恢复编辑；最后的贡献者是本文的唯一作者。',
'alreadyrolled'     => '无法回退由[[User:$2|$2]]（[[User talk:$2|讨论]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]在[[:$1]]上的编辑；其他人已经编辑或者回退了该页。

该页最后的编辑者是[[User:$3|$3]]（[[User talk:$3|讨论]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]）。',
'editcomment'       => '编辑摘要："<i>$1</i>"。',
'revertpage'        => '已恢复[[Special:Contributions/$2|$2]]（[[User talk:$2|讨论]]）的编辑至[[User:$1|$1]]的最后一个修订版本',
'revertpage-nouser' => '恢复由（移除了的用户名）的编辑到[[User:$1|$1]]的最后一个修订版本',
'rollback-success'  => '已恢复$1的编辑；
更改回$2的最后修订版本。',

# Edit tokens
'sessionfailure-title' => '登录信息失败',
'sessionfailure'       => '似乎您的登录会话有问题；
为了防止会话劫持，这个操作已经被取消。
请返回先前的页面，重新载入该页面，然后重试。',

# Protect
'protectlogpage'              => '保护日志',
'protectlogtext'              => '下面是页面保护和取消保护的列表。请参考[[Special:ProtectedPages|保护页面列表]]以查看当前进行的页面保护。',
'protectedarticle'            => '已保护“[[$1]]”',
'modifiedarticleprotection'   => '已更改“[[$1]]”的保护等级',
'unprotectedarticle'          => '已取消“[[$1]]”的保护',
'movedarticleprotection'      => '已将“[[$2]]”的保护设置移动至“[[$1]]”',
'protect-title'               => '更改“$1”的保护等级',
'prot_1movedto2'              => '[[$1]]已移动至[[$2]]',
'protect-legend'              => '确认保护',
'protectcomment'              => '原因：',
'protectexpiry'               => '到期：',
'protect_expiry_invalid'      => '输入的终止时间无效。',
'protect_expiry_old'          => '终止时间已过去。',
'protect-unchain-permissions' => '解除锁定更多的保护选项',
'protect-text'                => '你可以在这里浏览和修改对页面<strong><nowiki>$1</nowiki></strong>的保护级别。',
'protect-locked-blocked'      => '您不能在被查封时更改保护级别。
以下是<strong>$1</strong>现时的保护级别：',
'protect-locked-dblock'       => '在数据库被锁定时无法更改保护级别。
以下是<strong>$1</strong>现时的保护级别：',
'protect-locked-access'       => '您的帐户权限不能修改保护级别。
以下是<strong>$1</strong>现时的保护级别：',
'protect-cascadeon'           => '以下的{{PLURAL:$1|一个|多个}}页面包含  本页面的同时，启动了连锁保护，因此本页面目前也被保护，未能编辑。您可以设置本页面的保护级别，但这并不会对连锁保护有所影响。',
'protect-default'             => '容许所有用户',
'protect-fallback'            => '需要“$1”的许可',
'protect-level-autoconfirmed' => '禁止新的和未注册的用户',
'protect-level-sysop'         => '仅管理员',
'protect-summary-cascade'     => '联锁',
'protect-expiring'            => '终止于$1（UTC）',
'protect-expiry-indefinite'   => '无期',
'protect-cascade'             => '保护本页中包含的页面（连锁保护）',
'protect-cantedit'            => '您无法更改这个页面的保护等级，因为您没有权限去编辑它。',
'protect-othertime'           => '其它时间：',
'protect-othertime-op'        => '其它时间',
'protect-existing-expiry'     => '现时到期之时间：$2 $3',
'protect-otherreason'         => '其它／附加的理由：',
'protect-otherreason-op'      => '其它理由',
'protect-dropdown'            => '*通用保护理由
** 遭到过度破坏
** 过度张贴垃圾链接
** 无意义的编辑战
** 高流量页面',
'protect-edit-reasonlist'     => '编辑保护理由',
'protect-expiry-options'      => '1小时:1 hour,1天:1 day,1周:1 week,2周:2 weeks,1个月:1 month,3个月:3 months,6个月:6 months,1年:1 year,永久:infinite',
'restriction-type'            => '权限：',
'restriction-level'           => '限制级别：',
'minimum-size'                => '最小大小',
'maximum-size'                => '最大大小：',
'pagesize'                    => '（字节）',

# Restrictions (nouns)
'restriction-edit'   => '编辑',
'restriction-move'   => '移动',
'restriction-create' => '创建',
'restriction-upload' => '上传',

# Restriction levels
'restriction-level-sysop'         => '全保护',
'restriction-level-autoconfirmed' => '半保护',
'restriction-level-all'           => '任何级别',

# Undelete
'undelete'                     => '恢复被删页面',
'undeletepage'                 => '浏览及恢复被删页面',
'undeletepagetitle'            => "'''以下包含[[:$1]]的已删除之修订版本'''。",
'viewdeletedpage'              => '查看被删页面',
'undeletepagetext'             => '以下的$1个页面已经被删除，但依然在存档中并可以被恢复。
档案库可能被定时清理。',
'undelete-fieldset-title'      => '恢复修订',
'undeleteextrahelp'            => "恢复整个页面时，请清除所有复选框后点击'''''{{int:undeletebtn}}'''''。恢复特定版本时，请选择相应版本前的复选框后点击'''''{{int:undeletebtn}}'''''。点击'''''{{int:undeletereset}}'''''将清除评论内容及所有复选框。",
'undeleterevisions'            => '$1版本存档',
'undeletehistory'              => '如果您恢复了该页面，所有版本都会被恢复到修订历史中。
如果本页删除后有一个同名的新页面建立，被恢复的版本将会出现在先前的历史中。',
'undeleterevdel'               => '如果把最新修订部份删除，反删除便无法进行。如果遇到这种情况，您必须反选或反隐藏最新已删除的修订。',
'undeletehistorynoadmin'       => '这个页面已被删除。删除原因显示在下方编辑摘要中，被删除前的所有修订文本连同删除前贡献用户的细节信息只对管理员可见。',
'undelete-revision'            => '删除$1时由$3（在$4 $5）所编写的修订版本：',
'undeleterevision-missing'     => '无效或丢失的修订版本。您可能使用了错误的链接，或者此修订版本已经被从存档中恢复或移除。',
'undelete-nodiff'              => '找不到先前的修订版本。',
'undeletebtn'                  => '恢复',
'undeletelink'                 => '查看／恢复',
'undeleteviewlink'             => '查看',
'undeletereset'                => '重设',
'undeleteinvert'               => '反向选择',
'undeletecomment'              => '理由：',
'undeletedarticle'             => '已恢复的"[[$1]]"',
'undeletedrevisions'           => '$1个修订版本已恢复',
'undeletedrevisions-files'     => '$1个修订版本和$2个文件已经被恢复',
'undeletedfiles'               => '$1个文件已经被恢复',
'cannotundelete'               => '恢复删除失败；可能已有其他人先行恢复了此页面。',
'undeletedpage'                => "'''$1已经被恢复'''

参考[[Special:Log/delete|删除日志]]查看删除及恢复记录。",
'undelete-header'              => '如要查询最近的记录请参阅[[Special:Log/delete|删除日志]]。',
'undelete-search-box'          => '搜索已删除页面',
'undelete-search-prefix'       => '显示页面自：',
'undelete-search-submit'       => '搜索',
'undelete-no-results'          => '删除记录里没有符合的结果。',
'undelete-filename-mismatch'   => '不能删除带有时间戳的文件修订$1：文件不匹配',
'undelete-bad-store-key'       => '不能删除带有时间戳的文件修订$1：文件在删除前遗失。',
'undelete-cleanup-error'       => '删除无用的存档文件“$1”时发生错误。',
'undelete-missing-filearchive' => '由于文件存档 ID $1 不在数据库中，不能在文件存档中恢复。它可能已经被恢复了。',
'undelete-error-short'         => '恢复被删文件时发生错误：$1',
'undelete-error-long'          => '恢复被删除的文件时出错：

$1',
'undelete-show-file-confirm'   => '确定要查看在 $2 $3 ，"<nowiki>$1</nowiki>"的已删除修订版本吗？',
'undelete-show-file-submit'    => '是',

# Namespace form on various pages
'namespace'      => '名字空间：',
'invert'         => '反向选定',
'blanknamespace' => '（主）',

# Contributions
'contributions'       => '用户贡献',
'contributions-title' => '$1的用户贡献',
'mycontris'           => '贡献',
'contribsub2'         => '$1的贡献 （$2）',
'nocontribs'          => '没有找到符合特征的更改。',
'uctop'               => '（最新修改）',
'month'               => '从该月份 （或更早）：',
'year'                => '从该年份 （或更早）：',

'sp-contributions-newbies'             => '只显示新创建之用户的贡献',
'sp-contributions-newbies-sub'         => '新手',
'sp-contributions-newbies-title'       => '新手的用户贡献',
'sp-contributions-blocklog'            => '封禁日志',
'sp-contributions-deleted'             => '已删除的用户贡献',
'sp-contributions-uploads'             => '上载',
'sp-contributions-logs'                => '日志',
'sp-contributions-talk'                => '讨论',
'sp-contributions-userrights'          => '用户权限管理',
'sp-contributions-blocked-notice'      => '这位用户现时正在被封锁中。
最近的封锁日志项目在下面提供以便参考：',
'sp-contributions-blocked-notice-anon' => '这个IP地址现时正在被封锁中。
最近的封锁日志项目在下面提供以便参考：',
'sp-contributions-search'              => '搜索贡献记录',
'sp-contributions-username'            => 'IP地址或用户名称：',
'sp-contributions-toponly'             => '只显示最新修订版本的编辑',
'sp-contributions-submit'              => '搜索',

# What links here
'whatlinkshere'            => '链入页面',
'whatlinkshere-title'      => '链接到“$1”的页面',
'whatlinkshere-page'       => '页面：',
'linkshere'                => '以下页面链接到[[:$1]]：',
'nolinkshere'              => '没有页面链接到[[:$1]]。',
'nolinkshere-ns'           => '在所选的名字空间内没有页面链接到[[:$1]]。',
'isredirect'               => '重定向页',
'istemplate'               => '包含',
'isimage'                  => '文件链接',
'whatlinkshere-prev'       => '前$1个',
'whatlinkshere-next'       => '后$1个',
'whatlinkshere-links'      => '←链入',
'whatlinkshere-hideredirs' => '$1重定向',
'whatlinkshere-hidetrans'  => '$1包含',
'whatlinkshere-hidelinks'  => '$1链接',
'whatlinkshere-hideimages' => '$1文件链接',
'whatlinkshere-filters'    => '过滤器',

# Block/unblock
'blockip'                         => '封禁用户',
'blockip-title'                   => '封禁用户',
'blockip-legend'                  => '封禁用户',
'blockiptext'                     => '使用下方的表单来禁止来自特定IP地址或用户名的写访问。
只有在为了防止破坏，并符合[[{{MediaWiki:Policy-url}}|方针]]的情况下才可采取此行动。
请在下面输入一个具体的理由（例如引述一个被破坏的页面）。',
'ipaddress'                       => 'IP地址：',
'ipadressorusername'              => 'IP地址或用户名：',
'ipbexpiry'                       => '期限：',
'ipbreason'                       => '原因：',
'ipbreasonotherlist'              => '其他原因',
'ipbreason-dropdown'              => '*常见封禁理由
** 增加不实资料
** 移除页面内容
** 添加外部垃圾链接
** 在页面中增加无意义文字
** 胁迫行为／骚扰他人
** 滥用多个帐号
** 不能接受的用户名',
'ipbanononly'                     => '仅阻止匿名用户',
'ipbcreateaccount'                => '阻止创建新账号',
'ipbemailban'                     => '阻止用户发送电邮',
'ipbenableautoblock'              => '自动查封此用户最后所用的IP地址，以及后来试图编辑所用的所有地址',
'ipbsubmit'                       => '查封该地址',
'ipbother'                        => '其它时间：',
'ipboptions'                      => '2小时:2 hours,1天:1 day,3天:3 days,1周:1 week,2周:2 weeks,1个月:1 month,3个月:3 months,6个月:6 months,1年:1 year,永久:infinite',
'ipbotheroption'                  => '其他',
'ipbotherreason'                  => '其它／附带原因：',
'ipbhidename'                     => '在编辑及列表中隐藏用户名',
'ipbwatchuser'                    => '监视这位用户的用户页面以及其对话页面',
'ipballowusertalk'                => '允许该用户在封禁期内编辑自己的讨论页',
'ipb-change-block'                => '使用这些设置重新封禁用户',
'badipaddress'                    => '无效IP地址',
'blockipsuccesssub'               => '封禁成功',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]]已被封禁。<br />
参见[[Special:IPBlockList|封禁列表]]以复核封禁。',
'ipb-edit-dropdown'               => '编辑封禁理由',
'ipb-unblock-addr'                => '解封$1',
'ipb-unblock'                     => '解封用户名或IP地址',
'ipb-blocklist'                   => '查看现有封禁',
'ipb-blocklist-contribs'          => '$1的贡献',
'unblockip'                       => '解封用户',
'unblockiptext'                   => '使用下列表单来恢复之前被封禁的IP地址或用户名的写权限。',
'ipusubmit'                       => '解除此封禁',
'unblocked'                       => '[[User:$1|$1]]已经被解封',
'unblocked-id'                    => '封禁$1已被解除',
'ipblocklist'                     => '已封禁IP地址和用户名',
'ipblocklist-legend'              => '查找已封禁用户',
'ipblocklist-username'            => '用户名或IP地址：',
'ipblocklist-sh-userblocks'       => '$1账户封禁',
'ipblocklist-sh-tempblocks'       => '$1临时封禁',
'ipblocklist-sh-addressblocks'    => '$1单IP封禁',
'ipblocklist-submit'              => '搜索',
'ipblocklist-localblock'          => '本地封禁',
'ipblocklist-otherblocks'         => '其他{{PLURAL:$1|封禁|封禁}}',
'blocklistline'                   => '$1，$3被$2封禁（$4）',
'infiniteblock'                   => '永久',
'expiringblock'                   => '$1 $2到期',
'anononlyblock'                   => '仅限匿名用户',
'noautoblockblock'                => '禁用自动封禁',
'createaccountblock'              => '禁止创建账户',
'emailblock'                      => '禁止电子邮件',
'blocklist-nousertalk'            => '禁止编辑自己的用户讨论页',
'ipblocklist-empty'               => '封禁列表为空。',
'ipblocklist-no-results'          => '请求的IP地址或用户名没有被封禁。',
'blocklink'                       => '封禁',
'unblocklink'                     => '解除封禁',
'change-blocklink'                => '更改封禁',
'contribslink'                    => '贡献',
'autoblocker'                     => '由于您与“[[User:$1|$1]]”共享一个IP地址而被自动封禁。
$1被封禁的理由是：“$2”',
'blocklogpage'                    => '封禁日志',
'blocklog-showlog'                => '此用户曾被封禁。以下列出封禁日志以供参考：',
'blocklog-showsuppresslog'        => '此用户曾被封禁并隐藏。以下列出隐藏日志以供参考：',
'blocklogentry'                   => '[[$1]]已被封禁，终止时间为$2 $3',
'reblock-logentry'                => '更改[[$1]]的封禁终止时间 $2 $3',
'blocklogtext'                    => '此处给出了封禁和解封用户的操作日志，被自动封禁的IP地址不在此表。请查看[[Special:IPBlockList|封禁列表]]获知当前被封禁的用户和IP地址。',
'unblocklogentry'                 => '$1已被解除封禁',
'block-log-flags-anononly'        => '仅限匿名用户',
'block-log-flags-nocreate'        => '创建账户已禁用',
'block-log-flags-noautoblock'     => '禁用自动封禁',
'block-log-flags-noemail'         => '禁止电子邮件',
'block-log-flags-nousertalk'      => '禁止编辑自己的用户讨论页',
'block-log-flags-angry-autoblock' => '已启用增强型自动封禁',
'block-log-flags-hiddenname'      => '隐藏用户名',
'range_block_disabled'            => '管理员执行段封禁的权限已被禁用。',
'ipb_expiry_invalid'              => '无效的终止时间。',
'ipb_expiry_temp'                 => '隐藏用户名的封禁必须是永久性的。',
'ipb_hide_invalid'                => '无法隐藏此账户，它可能有太多编辑。',
'ipb_already_blocked'             => '“$1”已被封禁',
'ipb-needreblock'                 => '== 已封禁 ==
$1已被封禁。您是否想更改封禁设置？',
'ipb-otherblocks-header'          => '其他{{PLURAL:$1|封禁|封禁}}',
'ipb_cant_unblock'                => '错误：找不到封禁ID$1。可能已经解除封禁。',
'ipb_blocked_as_range'            => '错误：IP地址$1未被直接封禁，故无法解除封禁。然而，它位于IP地址段$2的封禁范围内，后者可被解除封禁。',
'ip_range_invalid'                => '无效的IP地址段。',
'ip_range_toolarge'               => '不允许大于/$1的段封禁。',
'blockme'                         => '封禁我',
'proxyblocker'                    => '代理封禁器',
'proxyblocker-disabled'           => '此功能已禁用。',
'proxyblockreason'                => '您的IP地址为已被封禁的公开代理。请联系您的互联网服务提供商或技术支持者，并告知他们此严重的安全问题。',
'proxyblocksuccess'               => '完成。',
'sorbsreason'                     => '在{{SITENAME}}使用的DNSBL中，您的IP地址被列为公开代理。',
'sorbs_create_account_reason'     => '在{{SITENAME}}使用的DNSBL中，您的IP地址被列为公开代理，因此您不能创建新账户。',
'cant-block-while-blocked'        => '您无法在封禁期内封禁其他用户。',
'cant-see-hidden-user'            => '您尝试封禁的用户已被封禁并隐藏。
由于您尚无隐藏用户的权限，您无法查看或编辑此用户的封禁。',
'ipbblocked'                      => '您无法封禁或解封其他用户，因为您自己已被封禁',
'ipbnounblockself'                => '您无法自我解除封禁',

# Developer tools
'lockdb'              => '锁定数据库',
'unlockdb'            => '解锁数据库',
'lockdbtext'          => '锁定数据库将禁止所有用户编辑页面、更改参数、编辑监视列表以及其他需要更改数据库的操作。请确认您的决定，并确保在维护工作结束后会重新开放数据库。',
'unlockdbtext'        => '解锁数据库将会恢复所有用户编辑页面、修改参数、编辑监视列表以及其他需要更改数据库的操作。请确认您的决定。',
'lockconfirm'         => '是，我的确想要锁定数据库。',
'unlockconfirm'       => '是，我的确想要解锁数据库。',
'lockbtn'             => '数据库锁定',
'unlockbtn'           => '解锁数据库',
'locknoconfirm'       => '您没有勾选确认框。',
'lockdbsuccesssub'    => '数据库锁定成功',
'unlockdbsuccesssub'  => '数据库解锁成功',
'lockdbsuccesstext'   => '数据库已锁定。<br />请记住在维护工作完成后[[Special:UnlockDB|解锁数据库]]。',
'unlockdbsuccesstext' => '数据库已解锁。',
'lockfilenotwritable' => '数据库锁定文件不可写。要锁定和解锁数据库，该文件必须对网络服务器可写。',
'databasenotlocked'   => '数据库没有锁定。',

# Move page
'move-page'                    => '移动$1',
'move-page-legend'             => '移动页面',
'movepagetext'                 => "用下面的表单来重命名一个页面，并将其修订历史同时移动到新页面。
老的页面将成为新页面的重定向页。
您可以自动地更新指到原标题的重定向。
如果您选择不去做的话，请检查[[Special:DoubleRedirects|双重]]或[[Special:BrokenRedirects|损坏重定向]]链接。
您应当负责确定所有链接依然会链到指定的页面。

注意如果新页面已经有内容的话，页面将'''不会'''被移动，
除非新页面无内容或是重定向页，而且没有修订历史。
这意味着您再必要时可以在移动到新页面后再移回老的页面，
同时您也无法覆盖现有页面。

'''警告！'''
对一个经常被访问的页面而言这可能是一个重大与唐突的更改；
请在行动前先了解其所可能带来的后果。",
'movepagetext-noredirectfixer' => "用下面的表单来重命名一个页面，并将其修订历史同时移动到新页面。
老的页面将成为新页面的重定向页。
请检查[[Special:DoubleRedirects|双重重定向]]或[[Special:BrokenRedirects|损坏重定向]]链接。
您应当负责确定所有链接依然会链到指定的页面。

注意如果新页面已经有内容的话，页面将'''不会'''被移动，
除非新页面无内容或是重定向页，而且没有修订历史。
这意味着您再必要时可以在移动到新页面后再移回老的页面，
同时您也无法覆盖现有页面。

'''警告！'''
对一个经常被访问的页面而言这可能是一个重大与唐突的更改；
请在行动前先确定您了解其所可能带来的后果。",
'movepagetalktext'             => "有关的讨论页将被自动与该页面一起移动，'''除非''':
*新页面已经有一个包含内容的讨论页，或者
*您不勾选下面的复选框。

在这些情况下，您在必要时必须手工移动或合并页面。",
'movearticle'                  => '移动页面：',
'moveuserpage-warning'         => "'''警告：'''您将会移动一个用户页面。请留意该页面在移动后该用户的名字是''不会''变更的。",
'movenologin'                  => '未登录',
'movenologintext'              => '您必须是一名登记用户并且[[Special:UserLogin|登录]]
后才可移动一个页面。',
'movenotallowed'               => '您并没有权限去移动页面。',
'movenotallowedfile'           => '您并没有权限去移动文件。',
'cant-move-user-page'          => '您并没有许可权去移动用户页面（它的子页面除外）。',
'cant-move-to-user-page'       => '您并没有许可权去移动到用户页面（它的子页面除外）。',
'newtitle'                     => '新标题：',
'move-watch'                   => '监视来源以及目标页',
'movepagebtn'                  => '移动页面',
'pagemovedsub'                 => '移动成功',
'movepage-moved'               => "'''“$1”已移动到“$2”'''",
'movepage-moved-redirect'      => '重定向已创建。',
'movepage-moved-noredirect'    => '重定向的创建已阻止。',
'articleexists'                => '该名称的页面已存在，或者您使用的名称无效。请另选一名。',
'cantmove-titleprotected'      => '您无法将页面移动到该位置，因为新标题已被保护以防止创建。',
'talkexists'                   => "'''页面本身移动成功，但由于新标题下已有讨论页存在，故讨论页无法移动。请手工合并这两个页面。'''",
'movedto'                      => '移动到',
'movetalk'                     => '移动关联的讨论页',
'move-subpages'                => '移动子页面（上至$1页）',
'move-talk-subpages'           => '如果可能，移动子对话页面（上至$1页）',
'movepage-page-exists'         => '页面$1已存在，无法自动覆盖。',
'movepage-page-moved'          => '页面$1已经移动到$2。',
'movepage-page-unmoved'        => '页面$1无法移动到$2。',
'movepage-max-pages'           => '所移动$1个页面的数量已达最大限额，无法同时自动移动更多页面。',
'1movedto2'                    => '[[$1]]移动到[[$2]]',
'1movedto2_redir'              => '[[$1]]移动到重定向页[[$2]]',
'move-redirect-suppressed'     => '已阻止重定向',
'movelogpage'                  => '移动日志',
'movelogpagetext'              => '以下是所有移动的页面列表：',
'movesubpage'                  => '{{PLURAL:$1|子页面|子页面}}',
'movesubpagetext'              => '这个页面有$1个子页面，列示如下。',
'movenosubpage'                => '这个页面没有子页面。',
'movereason'                   => '原因：',
'revertmove'                   => '恢复',
'delete_and_move'              => '删除并移动',
'delete_and_move_text'         => '==　需要删除　==

目标页面“[[:$1]]”已存在。是否确认删除该页面以便进行移动？',
'delete_and_move_confirm'      => '是，删除该页面',
'delete_and_move_reason'       => '删除以便移动',
'selfmove'                     => '原始标题和目标标题相同，无法对页面进行自我移动。',
'immobile-source-namespace'    => '无法移动名字空间为“$1”的页面',
'immobile-target-namespace'    => '无法将页面移动到“$1”名字空间',
'immobile-target-namespace-iw' => '在移动页面时，跨wiki链接不是有效的目标。',
'immobile-source-page'         => '此页面不能移动。',
'immobile-target-page'         => '无法移动至该目标标题。',
'imagenocrossnamespace'        => '无法将文件移动到非文件名字空间',
'nonfile-cannot-move-to-file'  => '无法将非文件移动到文件名字空间',
'imagetypemismatch'            => '该新扩展名与其类型不匹配',
'imageinvalidfilename'         => '目标文件名称无效',
'fix-double-redirects'         => '更新所有指向原始标题的重定向',
'move-leave-redirect'          => '保留重定向',
'protectedpagemovewarning'     => "'''警告：'''这个页面已经被保护，只有拥有管理员权限的用户才可以移动它。
最近的日志在下面提供以便参考：",
'semiprotectedpagemovewarning' => "'''注意：'''这个页面已经被保护，只有已经注册的用户才可以移动它。
最近的日志在下面提供以便参考：",
'move-over-sharedrepo'         => '== 文件已存在 ==
[[:$1]]已于共享资源存在，将文件移动到此标题会覆盖共享资源中的文件。',
'file-exists-sharedrepo'       => '同名文件已于共享资源存在。
请选择另一个文件名。',

# Export
'export'            => '导出页面',
'exporttext'        => '您可以将特定页面或一组页面的文本以及编辑历史以 XML 格式导出；这样可以将有关页面通过“[[Special:Import|导入页面]]”页面导入到另一个运行 MediaWiki 的网站。

要导出页面，请在下面的文本框中输入页面标题，每行一个标题，
并选择你是否需要导出带有页面历史的以前的修订本，
或是只选择导出带有最后一次编辑信息的当前修订版本。

此外你还可以利用链接导出文件，例如你可以使用[[{{#Special:Export}}/{{MediaWiki:Mainpage}}]]导出“[[{{MediaWiki:Mainpage}}]]”页面。',
'exportcuronly'     => '仅包含当前的修订，而不是全部的历史。',
'exportnohistory'   => "----
'''注意：'''由于性能原因，从此表单导出页面的全部历史已被禁用。",
'export-submit'     => '导出',
'export-addcattext' => '由分类中添加页面：',
'export-addcat'     => '添加',
'export-addnstext'  => '由名字空间中添加页面：',
'export-addns'      => '添加',
'export-download'   => '另存为文件',
'export-templates'  => '包含模板',
'export-pagelinks'  => '包含链接页面的搜索深度：',

# Namespace 8 related
'allmessages'                   => '系统界面',
'allmessagesname'               => '名称',
'allmessagesdefault'            => '默认消息文本',
'allmessagescurrent'            => '当前消息文本',
'allmessagestext'               => '此处列出了MediaWiki名字空间下的所有有效系统消息。
如果想为MediaWiki的本地化贡献翻译，请访问[http://www.mediawiki.org/wiki/Localisation MediaWiki本地化]和[http://translatewiki.net translatewiki.net]。',
'allmessagesnotsupportedDB'     => "此页面无法使用，因为'''\$wgUseDatabaseMessages'''已被设置关闭。",
'allmessages-filter-legend'     => '过滤',
'allmessages-filter'            => '以自定状况过滤：',
'allmessages-filter-unmodified' => '未修改',
'allmessages-filter-all'        => '所有',
'allmessages-filter-modified'   => '曾修改',
'allmessages-prefix'            => '以前缀过滤：',
'allmessages-language'          => '语言：',
'allmessages-filter-submit'     => '进入',

# Thumbnails
'thumbnail-more'           => '放大',
'filemissing'              => '无法找到文件',
'thumbnail_error'          => '创建缩略图错误：$1',
'djvu_page_error'          => 'DjVu页面超出范围',
'djvu_no_xml'              => '无法在DjVu文件中获取XML',
'thumbnail_invalid_params' => '不正确的缩略图参数',
'thumbnail_dest_directory' => '无法建立目标目录',
'thumbnail_image-type'     => '图像类型不支持',
'thumbnail_gd-library'     => '未完成的GD设置：功能遗失 $1',
'thumbnail_image-missing'  => '文件似乎遗失：$1',

# Special:Import
'import'                     => '导入页面',
'importinterwiki'            => '跨wiki导入',
'import-interwiki-text'      => '选择要导入的wiki和页面标题，导入修订的日期和编辑者名称会被保存。所有的跨wiki导入操作都将记录到[[Special:Log/import|导入日志]]。',
'import-interwiki-source'    => '来源wiki／页面：',
'import-interwiki-history'   => '复制此页的所有历史修订版本',
'import-interwiki-templates' => '包含所有模板',
'import-interwiki-submit'    => '导入',
'import-interwiki-namespace' => '目标名字空间：',
'import-upload-filename'     => '文件名：',
'import-comment'             => '注释：',
'importtext'                 => '请使用[[Special:Export|导出功能]]从源 wiki 导出文件，
保存到您的磁盘并上传到这里。',
'importstart'                => '页面导入中……',
'import-revision-count'      => '$1个修订',
'importnopages'              => '没有导入的页面。',
'imported-log-entries'       => '导入了$1项日志记录。',
'importfailed'               => '导入失败：<nowiki>$1</nowiki>',
'importunknownsource'        => '未知的源导入类型',
'importcantopen'             => '无法打开导入文件',
'importbadinterwiki'         => '无效的跨wiki链接',
'importnotext'               => '空或没有文本',
'importsuccess'              => '导入完成！',
'importhistoryconflict'      => '存在冲突的修订历史（可能在之前已经导入过此页面）',
'importnosources'            => '跨Wiki导入源没有定义，同时不允许直接的历史上传。',
'importnofile'               => '没有上传导入文件。',
'importuploaderrorsize'      => '上传导入文件失败。文件大于可以允许的上传大小。',
'importuploaderrorpartial'   => '上传导入文件失败。文件只有部份已经上传。',
'importuploaderrortemp'      => '上传导入文件失败。临时文件夹已遗失。',
'import-parse-failure'       => 'XML导入语法失败',
'import-noarticle'           => '没有页面作导入！',
'import-nonewrevisions'      => '所有的修订之前曾已导入。',
'xml-error-string'           => '$1于行$2，列$3（$4字节）：$5',
'import-upload'              => '上传XML数据',
'import-token-mismatch'      => '会话数据遗失。请重试。',
'import-invalid-interwiki'   => '不能从指定的wiki导入。',

# Import log
'importlogpage'                    => '导入日志',
'importlogpagetext'                => '由管理员从其他wiki导入的带有编辑历史的页面。',
'import-logentry-upload'           => '通过文件上传导入的[[$1]]',
'import-logentry-upload-detail'    => '$1个修订',
'import-logentry-interwiki'        => '跨 wiki $1',
'import-logentry-interwiki-detail' => '来自$2的$1个修订',

# Tooltip help for the actions
'tooltip-pt-userpage'             => '您的用户页',
'tooltip-pt-anonuserpage'         => '您编辑本站所用IP的对应用户页',
'tooltip-pt-mytalk'               => '您的对话页',
'tooltip-pt-anontalk'             => '对于来自此IP地址的编辑的对话',
'tooltip-pt-preferences'          => '您的参数设置',
'tooltip-pt-watchlist'            => '您所监视页面的更改列表',
'tooltip-pt-mycontris'            => '您的贡献列表',
'tooltip-pt-login'                => '我们鼓励您登录，但这并不是必须的',
'tooltip-pt-anonlogin'            => '我们鼓励您登录，但这并不是必须的',
'tooltip-pt-logout'               => '退出',
'tooltip-ca-talk'                 => '关于页面正文的讨论',
'tooltip-ca-edit'                 => '你可编辑此页，请在保存前先预览一下。',
'tooltip-ca-addsection'           => '开始新段落',
'tooltip-ca-viewsource'           => '此页面已被保护，你可以查看其源代码。',
'tooltip-ca-history'              => '此页面的早前修订版本',
'tooltip-ca-protect'              => '保护此页',
'tooltip-ca-unprotect'            => '解除保护此页',
'tooltip-ca-delete'               => '删除此页',
'tooltip-ca-undelete'             => '将这个页面恢复到被删除以前的状态',
'tooltip-ca-move'                 => '移动此页',
'tooltip-ca-watch'                => '将此页面加入监视列表',
'tooltip-ca-unwatch'              => '将此页面从监视列表中移去',
'tooltip-search'                  => '搜索该网站',
'tooltip-search-go'               => '如果相同的标题存在的话便直接前往该页面',
'tooltip-search-fulltext'         => '搜索该文字的页面',
'tooltip-p-logo'                  => '访问首页',
'tooltip-n-mainpage'              => '访问首页',
'tooltip-n-mainpage-description'  => '访问首页',
'tooltip-n-portal'                => '关于本计划，您可以做什么，应该如何做',
'tooltip-n-currentevents'         => '查找当前事件的背景信息',
'tooltip-n-recentchanges'         => '列出该网站的最近修改',
'tooltip-n-randompage'            => '随机载入一个页面',
'tooltip-n-help'                  => '寻求帮助',
'tooltip-t-whatlinkshere'         => '列出所有与此页相链的页面',
'tooltip-t-recentchangeslinked'   => '从此页链出的所有页面的更改',
'tooltip-feed-rss'                => '此页的 RSS 订阅',
'tooltip-feed-atom'               => '此页的 Atom 订阅',
'tooltip-t-contributions'         => '查看该用户的贡献列表',
'tooltip-t-emailuser'             => '向该用户发送一封电子邮件',
'tooltip-t-upload'                => '上传文件',
'tooltip-t-specialpages'          => '所有特殊页面列表',
'tooltip-t-print'                 => '这个页面的可打印版本',
'tooltip-t-permalink'             => '这个页面修订版本的永久链接',
'tooltip-ca-nstab-main'           => '查看页面内容',
'tooltip-ca-nstab-user'           => '查看用户页面',
'tooltip-ca-nstab-media'          => '查看媒体页面',
'tooltip-ca-nstab-special'        => '这是一个特殊页面，您不能对它进行编辑',
'tooltip-ca-nstab-project'        => '查看计划页面',
'tooltip-ca-nstab-image'          => '查看文件页面',
'tooltip-ca-nstab-mediawiki'      => '查看系统界面消息',
'tooltip-ca-nstab-template'       => '查看模板',
'tooltip-ca-nstab-help'           => '查看帮助页面',
'tooltip-ca-nstab-category'       => '查看分类页面',
'tooltip-minoredit'               => '将此标记为小更改',
'tooltip-save'                    => '保存您的更改',
'tooltip-preview'                 => '预览您的更改，请在保存前使用此功能！',
'tooltip-diff'                    => '显示您对该文字所做的更改',
'tooltip-compareselectedversions' => '查看此页面两个选定的修订版本间的差异。',
'tooltip-watch'                   => '将该页面加到您的监视列表',
'tooltip-recreate'                => '重建该页面，无论是否被删除。',
'tooltip-upload'                  => '开始上传',
'tooltip-rollback'                => '“回退”单击恢复上一位贡献者对此页面作出的编辑',
'tooltip-undo'                    => '“撤销”可以恢复该编辑并在预览模式下打开编辑表单。它允许在摘要中加入原因。',
'tooltip-preferences-save'        => '保存设定',
'tooltip-summary'                 => '输入一个简短的摘要',

# Stylesheets
'common.css'      => '/* 此处的 CSS 将应用于所有的皮肤 */',
'standard.css'    => '/* 此处的 CSS 将影响使用标准皮肤的用户 */',
'nostalgia.css'   => '/* 此处的 CSS 将影响使用怀旧皮肤的用户 */',
'cologneblue.css' => '/* 此处的 CSS 将影响使用科隆香水蓝皮肤的用户 */',
'monobook.css'    => '/* 此处的 CSS 将影响使用 Monobook 皮肤的用户 */',
'myskin.css'      => '/* 此处的 CSS 将影响使用 MySkin 皮肤的用户 */',
'chick.css'       => '/* 此处的 CSS 将影响使用 Chick 皮肤的用户 */',
'simple.css'      => '/* 此处的 CSS 将影响使用 Simple 皮肤的用户 */',
'modern.css'      => '/* 此处的 CSS 将影响使用 Modern 皮肤的用户 */',
'vector.css'      => '/* 此处的 CSS 将影响使用 Vector 皮肤的用户 */',
'print.css'       => '/* 此处的 CSS 将影响打印输出 */',
'handheld.css'    => '/* 此处的 CSS 将影响在 $wgHandheldStyle 设置手提装置面板 */',

# Scripts
'common.js'      => '/* 此处的JavaScript将加载于所有用户每一个页面。 */',
'standard.js'    => '/* 此处的JavaScript将加载于使用标准皮肤的用户 */',
'nostalgia.js'   => '/* 此处的JavaScript将加载于使用怀旧皮肤的用户 */',
'cologneblue.js' => '/* 此处的JavaScript将加载于使用科隆香水蓝皮肤的用户 */',
'monobook.js'    => '/* 此处的JavaScript将加载于使用Monobook皮肤的用户 */',
'myskin.js'      => '/* 此处的JavaScript将加载于使用MySkin皮肤的用户 */',
'chick.js'       => '/* 此处的JavaScript将加载于使用Chick皮肤的用户 */',
'simple.js'      => '/* 此处的JavaScript将加载于使用Simple皮肤的用户 */',
'modern.js'      => '/* 此处的JavaScript将加载于使用Modern皮肤的用户 */',
'vector.js'      => '/* 此处的JavaScript将加载于使用Vector皮肤的用户 */',

# Metadata
'nodublincore'      => 'Dublin Core RDF 元数据在该服务器不可用。',
'nocreativecommons' => 'Creative Commons RDF 元数据在该服务器不可用。',
'notacceptable'     => '该网站服务器不能提供您的客户端能识别的数据格式。',

# Attribution
'anonymous'        => '{{SITENAME}}的匿名{{PLURAL:$1|用户|用户}}',
'siteuser'         => '{{SITENAME}}用户$1',
'anonuser'         => '{{SITENAME}}匿名用户$1',
'lastmodifiedatby' => '此页由$3于$1 $2的最后更改。',
'othercontribs'    => '在$1的工作基础上。',
'others'           => '其他',
'siteusers'        => '{{SITENAME}}{{PLURAL:$2|用户|用户}}$1',
'anonusers'        => '{{SITENAME}}匿名{{PLURAL:$2|用户|用户}}$1',
'creditspage'      => '页面致谢',
'nocredits'        => '该页没有致谢名单信息。',

# Spam protection
'spamprotectiontitle' => '垃圾链接过滤器',
'spamprotectiontext'  => '您要保存的文本被垃圾过滤器阻止。
这可能是由于一个链往匹配黑名单的外部站点的链接引起的。',
'spamprotectionmatch' => '以下文本触发了我们的垃圾链接过滤器：$1',
'spambot_username'    => 'MediaWiki垃圾链接清理器',
'spam_reverting'      => '恢复到不包含链接的最近修订版本$1',
'spam_blanking'       => '消隐所有包含链接至$1的修订',

# Info page
'infosubtitle'   => '页面信息',
'numedits'       => '编辑数（页面）：$1',
'numtalkedits'   => '编辑数（讨论页）：$1',
'numwatchers'    => '监视者数目：$1',
'numauthors'     => '作者数量（页面）：$1',
'numtalkauthors' => '作者数量（讨论页）：$1',

# Skin names
'skinname-standard'    => '标准',
'skinname-nostalgia'   => '怀旧',
'skinname-cologneblue' => '科隆香水蓝',
'skinname-simple'      => '简单',
'skinname-modern'      => '现代',

# Math options
'mw_math_png'    => '永远使用PNG图像',
'mw_math_simple' => '如果是简单的公式使用HTML，否则使用PNG图像',
'mw_math_html'   => '如果可以用HTML，否则用PNG图像',
'mw_math_source' => '显示为TeX代码（使用文字浏览器时）',
'mw_math_modern' => '推荐为新版浏览器使用',
'mw_math_mathml' => '尽可能使用MathML（试验中）',

# Math errors
'math_failure'          => '解析失败',
'math_unknown_error'    => '未知错误',
'math_unknown_function' => '未知函数',
'math_lexing_error'     => '句法错误',
'math_syntax_error'     => '语法错误',
'math_image_error'      => 'PNG 转换失败 ；检查正确安装的 latex 和 dvipng （或 dvips + gs + convert）',
'math_bad_tmpdir'       => '无法写入或建立数学公式临时目录',
'math_bad_output'       => '无法写入或建立数学公式输出目录',
'math_notexvc'          => '"texvc"执行文件遗失；请参照math/README进行配置。',

# Patrolling
'markaspatrolleddiff'                 => '标记为已巡查',
'markaspatrolledtext'                 => '标记此页面为已巡查',
'markedaspatrolled'                   => '标记为已检查',
'markedaspatrolledtext'               => '[[:$1]]的已选中修订版本已被标识为已巡查。',
'rcpatroldisabled'                    => '最新更改检查被关闭',
'rcpatroldisabledtext'                => '最新更改检查的功能目前已关闭。',
'markedaspatrollederror'              => '不能标志为已检查',
'markedaspatrollederrortext'          => '你需要指定某个版本才能标志为已检查。',
'markedaspatrollederror-noautopatrol' => '您无法将你自己所作的更改标记为已检查。',

# Patrol log
'patrol-log-page'      => '巡查日志',
'patrol-log-header'    => '这是已巡查的修订版本的日志。',
'patrol-log-line'      => '$2的版本$1已被标记为已巡查$3',
'patrol-log-auto'      => '（自动）',
'patrol-log-diff'      => '修订$1',
'log-show-hide-patrol' => '$1巡查纪录',

# Image deletion
'deletedrevision'                 => '已删除旧版本$1',
'filedeleteerror-short'           => '删除文件发生错误：$1',
'filedeleteerror-long'            => '删除文件时出错：

$1',
'filedelete-missing'              => '文件“$1”不存在而无法删除。',
'filedelete-old-unregistered'     => '所指定的文件修订“$1”在数据库中不存在。',
'filedelete-current-unregistered' => '所指定的文件“$1”在数据库中不存在。',
'filedelete-archive-read-only'    => '存档目录“$1”在网页服务器中不可写。',

# Browsing diffs
'previousdiff' => '←上一版本',
'nextdiff'     => '下一版本→',

# Media information
'mediawarning'         => "'''警告'''：该文件类型可能包含恶意代码。
运行它可能对您的系统带来危险。",
'imagemaxsize'         => "图像大小限制：<br />''（用于文件描述页面）''",
'thumbsize'            => '缩略图大小：',
'widthheightpage'      => '$1×$2，$3页',
'file-info'            => '文件大小：$1，MIME类型：$2',
'file-info-size'       => '$1×$2像素，文件大小：$3，MIME类型：$4',
'file-nohires'         => '<small>无更高分辨率可提供。</small>',
'svg-long-desc'        => 'SVG文件，名义大小：$1×$2像素，文件大小：$3',
'show-big-image'       => '完整分辨率',
'show-big-image-thumb' => '<small>这幅缩略图的分辨率：$1×$2像素</small>',
'file-info-gif-looped' => '循环',
'file-info-gif-frames' => '$1帧',
'file-info-png-looped' => '循环',
'file-info-png-repeat' => '已播放$1遍',
'file-info-png-frames' => '$1帧',

# Special:NewFiles
'newimages'             => '新文件画廊',
'imagelisttext'         => "以下是按$2排列的'''$1'''个文件列表。",
'newimages-summary'     => '此特殊页面显示最近上传的文件。',
'newimages-legend'      => '过滤',
'newimages-label'       => '文件名（或它的一部份）：',
'showhidebots'          => '(机器人$1)',
'noimages'              => '无可查看文件。',
'ilsubmit'              => '搜索',
'bydate'                => '按日期',
'sp-newimages-showfrom' => '从$1 $2开始显示新文件',

# Bad image list
'bad_image_list' => '请按照下列格式编写：

只有（以 * 开头）列出的项目会被考虑。每一行的第一个链接必须是不雅文件的链接。
然后同一行后方的链接会被视为例外，即是该文件可以在哪些页面内被显示。',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => '简体',
'variantname-zh-hant' => '繁体',
'variantname-zh-cn'   => '大陆简体',
'variantname-zh-tw'   => '台湾正体',
'variantname-zh-hk'   => '香港繁体',
'variantname-zh-sg'   => '新加坡简体',
'variantname-zh'      => '不转换',

# Variants for Gan language
'variantname-gan-hans' => '简体中文',

# Variants for Kazakh language
'variantname-kk-cyrl' => '西里尔',

# Metadata
'metadata'          => '元数据',
'metadata-help'     => '此文件中包含有扩展的信息。这些信息可能是由数码相机或扫描仪在创建或数字化过程中所添加的。

如果此文件的源文件已经被修改，一些信息在修改后的文件中将不能完全反映出来。',
'metadata-expand'   => '显示详细资料',
'metadata-collapse' => '隐藏详细资料',
'metadata-fields'   => '在本信息中所列出的 EXIF 元数据域将包含在图片显示页面,
当元数据表损坏时只显示以下信息，其他的元数据默认为隐藏。
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => '宽度',
'exif-imagelength'                 => '高度',
'exif-bitspersample'               => '每像素字节数',
'exif-compression'                 => '压缩方式',
'exif-photometricinterpretation'   => '像素构成',
'exif-orientation'                 => '方位',
'exif-samplesperpixel'             => '像素数',
'exif-planarconfiguration'         => '数据排列',
'exif-ycbcrsubsampling'            => '黄色对洋红二次抽样比率',
'exif-ycbcrpositioning'            => '黄色和洋红配置',
'exif-xresolution'                 => '水平分辨率',
'exif-yresolution'                 => '垂直分辨率',
'exif-resolutionunit'              => 'X和Y轴分辨率单位',
'exif-stripoffsets'                => '图像数据区',
'exif-rowsperstrip'                => '每带行数',
'exif-stripbytecounts'             => '每压缩带字节数',
'exif-jpeginterchangeformat'       => 'JPEG SOI偏移',
'exif-jpeginterchangeformatlength' => 'JPEG数据字节',
'exif-transferfunction'            => '转移功能',
'exif-whitepoint'                  => '白点色度',
'exif-primarychromaticities'       => '主要色度',
'exif-ycbcrcoefficients'           => '颜色空间转换矩阵系数',
'exif-referenceblackwhite'         => '黑白参照值对',
'exif-datetime'                    => '文件更改日期和时间',
'exif-imagedescription'            => '图像标题',
'exif-make'                        => '照相机制造商',
'exif-model'                       => '照相机型号',
'exif-software'                    => '所用软件',
'exif-artist'                      => '作者',
'exif-copyright'                   => '版权所有者',
'exif-exifversion'                 => 'Exif版本',
'exif-flashpixversion'             => '支持的Flashpix版本',
'exif-colorspace'                  => '颜色空间',
'exif-componentsconfiguration'     => '每分量含义',
'exif-compressedbitsperpixel'      => '图像压缩模式',
'exif-pixelydimension'             => '有效图像宽度',
'exif-pixelxdimension'             => '有效图像高度',
'exif-makernote'                   => '制造商注释',
'exif-usercomment'                 => '用户注释',
'exif-relatedsoundfile'            => '相关的音频文件',
'exif-datetimeoriginal'            => '数据产生时间',
'exif-datetimedigitized'           => '数字化处理时间',
'exif-subsectime'                  => '日期时间秒',
'exif-subsectimeoriginal'          => '原始日期时间秒',
'exif-subsectimedigitized'         => '数字化日期时间秒',
'exif-exposuretime'                => '曝光时间',
'exif-exposuretime-format'         => '$1秒（$2）',
'exif-fnumber'                     => '光圈（F值）',
'exif-exposureprogram'             => '曝光模式',
'exif-spectralsensitivity'         => '感光',
'exif-isospeedratings'             => 'ISO速率',
'exif-oecf'                        => '光电转换因子',
'exif-shutterspeedvalue'           => '快门速度',
'exif-aperturevalue'               => '光圈',
'exif-brightnessvalue'             => '亮度',
'exif-exposurebiasvalue'           => '曝光补偿',
'exif-maxaperturevalue'            => '最大陆地光圈',
'exif-subjectdistance'             => '物距',
'exif-meteringmode'                => '测量模式',
'exif-lightsource'                 => '光源',
'exif-flash'                       => '闪光灯',
'exif-focallength'                 => '焦距',
'exif-subjectarea'                 => '主体区域',
'exif-flashenergy'                 => '闪光灯强度',
'exif-spatialfrequencyresponse'    => '空间频率响应',
'exif-focalplanexresolution'       => 'X轴焦平面分辨率',
'exif-focalplaneyresolution'       => 'Y轴焦平面分辨率',
'exif-focalplaneresolutionunit'    => '焦平面分辨率单位',
'exif-subjectlocation'             => '主题位置',
'exif-exposureindex'               => '曝光指数',
'exif-sensingmethod'               => '感光模式',
'exif-filesource'                  => '文件源',
'exif-scenetype'                   => '场景类型',
'exif-cfapattern'                  => 'CFA模式',
'exif-customrendered'              => '自定义图像处理',
'exif-exposuremode'                => '曝光模式',
'exif-whitebalance'                => '白平衡',
'exif-digitalzoomratio'            => '数字变焦比率',
'exif-focallengthin35mmfilm'       => '35毫米胶片焦距',
'exif-scenecapturetype'            => '情景拍摄类型',
'exif-gaincontrol'                 => '场景控制',
'exif-contrast'                    => '对比度',
'exif-saturation'                  => '饱和度',
'exif-sharpness'                   => '锐化',
'exif-devicesettingdescription'    => '设备设定描述',
'exif-subjectdistancerange'        => '主体距离范围',
'exif-imageuniqueid'               => '唯一图像ID',
'exif-gpsversionid'                => 'GPS标签版本',
'exif-gpslatituderef'              => '北纬或南纬',
'exif-gpslatitude'                 => '纬度',
'exif-gpslongituderef'             => '东经或西经',
'exif-gpslongitude'                => '经度',
'exif-gpsaltituderef'              => '海拔正负参照',
'exif-gpsaltitude'                 => '海拔',
'exif-gpstimestamp'                => 'GPS时间（原子钟）',
'exif-gpssatellites'               => '测量使用的卫星',
'exif-gpsstatus'                   => '接收器状态',
'exif-gpsmeasuremode'              => '测量模式',
'exif-gpsdop'                      => '测量精度',
'exif-gpsspeedref'                 => '速度单位',
'exif-gpsspeed'                    => 'GPS接收器速度',
'exif-gpstrackref'                 => '运动方位参照',
'exif-gpstrack'                    => '运动方位',
'exif-gpsimgdirectionref'          => '图像方位参照',
'exif-gpsimgdirection'             => '图像方位',
'exif-gpsmapdatum'                 => '使用地理测绘数据',
'exif-gpsdestlatituderef'          => '目标纬度参照',
'exif-gpsdestlatitude'             => '目标纬度',
'exif-gpsdestlongituderef'         => '目标经度的参照',
'exif-gpsdestlongitude'            => '目标经度',
'exif-gpsdestbearingref'           => '目标方位参照',
'exif-gpsdestbearing'              => '目标方位',
'exif-gpsdestdistanceref'          => '目标距离参照',
'exif-gpsdestdistance'             => '目标距离',
'exif-gpsprocessingmethod'         => 'GPS处理方法名称',
'exif-gpsareainformation'          => 'GPS区域名称',
'exif-gpsdatestamp'                => 'GPS日期',
'exif-gpsdifferential'             => 'GPS差动修正',

# EXIF attributes
'exif-compression-1' => '未压缩',
'exif-compression-6' => '联合图像专家组格',

'exif-unknowndate' => '未知的日期',

'exif-orientation-1' => '标准',
'exif-orientation-2' => '水平翻转',
'exif-orientation-3' => '旋转180°',
'exif-orientation-4' => '垂直翻转',
'exif-orientation-5' => '逆时针旋转90°并垂直翻转',
'exif-orientation-6' => '顺时针旋转90°',
'exif-orientation-7' => '顺时针旋转90°并垂直翻转',
'exif-orientation-8' => '逆时针旋转90°',

'exif-planarconfiguration-1' => '矮胖格式',
'exif-planarconfiguration-2' => '平面格式',

'exif-componentsconfiguration-0' => '不存在',

'exif-exposureprogram-0' => '未定义',
'exif-exposureprogram-1' => '手动',
'exif-exposureprogram-2' => '标准程序',
'exif-exposureprogram-3' => '光圈优先模式',
'exif-exposureprogram-4' => '快门优先模式',
'exif-exposureprogram-5' => '艺术程序（景深优先）',
'exif-exposureprogram-6' => '运动程序（高快门速度优先）',
'exif-exposureprogram-7' => '肖像模式（适用于背景在焦距以外的近距摄影）',
'exif-exposureprogram-8' => '风景模式（适用于背景在焦距上的风景照片）',

'exif-subjectdistance-value' => '$1米',

'exif-meteringmode-0'   => '未知',
'exif-meteringmode-1'   => '平均水平',
'exif-meteringmode-2'   => '中心加权平均测量',
'exif-meteringmode-3'   => '点测',
'exif-meteringmode-4'   => '多点测',
'exif-meteringmode-5'   => '模式测量',
'exif-meteringmode-6'   => '局部测量',
'exif-meteringmode-255' => '其他',

'exif-lightsource-0'   => '未知',
'exif-lightsource-1'   => '日光灯',
'exif-lightsource-2'   => '荧光灯',
'exif-lightsource-3'   => '钨丝灯（白炽灯）',
'exif-lightsource-4'   => '闪光灯',
'exif-lightsource-9'   => '晴天',
'exif-lightsource-10'  => '多云',
'exif-lightsource-11'  => '深色调阴影',
'exif-lightsource-12'  => '日光荧光灯（色温 D 5700 – 7100K）',
'exif-lightsource-13'  => '日温白色荧光灯（N 4600 – 5400K）',
'exif-lightsource-14'  => '冷白色荧光灯（W 3900 – 4500K）',
'exif-lightsource-15'  => '白色荧光 （WW 3200 – 3700K）',
'exif-lightsource-17'  => '标准灯光A',
'exif-lightsource-18'  => '标准灯光B',
'exif-lightsource-19'  => '标准灯光C',
'exif-lightsource-24'  => 'ISO摄影棚钨灯',
'exif-lightsource-255' => '其他光源',

# Flash modes
'exif-flash-fired-0'    => '闪光灯无开火',
'exif-flash-fired-1'    => '闪光灯开火',
'exif-flash-return-0'   => '无频闪观测器功能',
'exif-flash-return-2'   => '频闪观测器未侦测到光',
'exif-flash-return-3'   => '频闪观测器侦测到光',
'exif-flash-mode-1'     => '强制闪光灯开火',
'exif-flash-mode-2'     => '强制压制闪光灯',
'exif-flash-mode-3'     => '自动方式',
'exif-flash-function-1' => '无闪光灯功能',
'exif-flash-redeye-1'   => '红眼减退方式',

'exif-focalplaneresolutionunit-2' => '英寸',

'exif-sensingmethod-1' => '未定义',
'exif-sensingmethod-2' => '一块彩色区域传感器',
'exif-sensingmethod-3' => '两块彩色区域传感器',
'exif-sensingmethod-4' => '三块彩色区域传感器',
'exif-sensingmethod-5' => '连续彩色区域传感器',
'exif-sensingmethod-7' => '三线传感器',
'exif-sensingmethod-8' => '连续彩色线性传感器',

'exif-scenetype-1' => '直接照像图片',

'exif-customrendered-0' => '标准处理',
'exif-customrendered-1' => '自定义处理',

'exif-exposuremode-0' => '自动曝光',
'exif-exposuremode-1' => '手动曝光',
'exif-exposuremode-2' => '自动曝光感知调节',

'exif-whitebalance-0' => '自动白平衡',
'exif-whitebalance-1' => '手动白平衡',

'exif-scenecapturetype-0' => '标准',
'exif-scenecapturetype-1' => '风景',
'exif-scenecapturetype-2' => '肖像',
'exif-scenecapturetype-3' => '夜景',

'exif-gaincontrol-0' => '无',
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
'exif-subjectdistancerange-1' => '宏程序',
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
'exif-gpsspeed-k' => '千米每小时',
'exif-gpsspeed-m' => '英里每小时',
'exif-gpsspeed-n' => '海里每小时（节）',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => '真方位',
'exif-gpsdirection-m' => '地磁方位',

# External editor support
'edit-externally'      => '用外部程序编辑此文件',
'edit-externally-help' => '（请参见[http://www.mediawiki.org/wiki/Manual:External_editors 设置步骤]了解详细信息）',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => '全部',
'imagelistall'     => '全部',
'watchlistall2'    => '全部',
'namespacesall'    => '全部',
'monthsall'        => '全部',
'limitall'         => '全部',

# E-mail address confirmation
'confirmemail'              => '确认邮箱地址',
'confirmemail_noemail'      => '您还没有在您的[[Special:Preferences|参数设置]]中设定一个有效的电子邮件地址。',
'confirmemail_text'         => '{{SITENAME}}要求您在使用邮件功能之前验证您的邮箱地址。
点击以下按钮可向您的邮箱发送一封确认邮件。该邮件包含有一行代码链接；
请在您的浏览器中加载此链接以确认您的邮箱地址是有效的。',
'confirmemail_pending'      => '一个确认码已经被发送到您的邮箱，您可能需要等几分钟才能收到。如果无法收到，请再申请一个新的确认码。',
'confirmemail_send'         => '邮件发送确认代码',
'confirmemail_sent'         => '确认邮件已发送。',
'confirmemail_oncreate'     => '一个确认代码已经被发送到您的邮箱。登录时无需使用该代码，
但若要启用在此维基的电子邮件相关功能，则必须先提交此代码。',
'confirmemail_sendfailed'   => '{{SITENAME}}不能发送确认邮件，请检查您的邮箱地址是否包含无效字符。

邮件发送器的返回信息：$1',
'confirmemail_invalid'      => '无效的确认码，该代码可能已经过期。',
'confirmemail_needlogin'    => '您需要$1以确认您的邮箱地址。',
'confirmemail_success'      => '您的邮箱已经被确认。您现在可以[[Special:UserLogin|登录]]并使用此网站了。',
'confirmemail_loggedin'     => '您的邮箱地址现在已被确认。',
'confirmemail_error'        => '你的确认过程发生错误。',
'confirmemail_subject'      => '{{SITENAME}}邮箱地址确认',
'confirmemail_body'         => '来自IP地址$1的用户（可能是您）在{{SITENAME}}上创建了账户“$2”，并提交了您的电子邮箱地址。

请确认这个账户是属于您的，并同时激活在{{SITENAME}}上的
电子邮件功能。请在浏览器中打开下面的链接：

$3

如果您*未曾*注册账户，
请打开下面的链接去取消电子邮件确认：

$5

确认码会在$4过期。',
'confirmemail_body_changed' => '拥有IP地址$1的用户（可能是您）在{{SITENAME}}更改了账户"$2"的电子邮箱地址。

请确认这个账户是属于您的，并同时重新激活在{{SITENAME}}上的
电子邮件功能。请在浏览器中打开下面的链接：

$3

如果这个账户*不是*属于您的，
请打开下面的链接去取消电子邮件确认：

$5

确认码会在$4过期。',
'confirmemail_body_set'     => '有人，可能是您，来自IP地址$1，
已设置的帐户"$2"这个地址{{SITENAME}}网站名称电邮地址。

为了确认这个帐号确实属于自己的，重新激活
电子邮件功能于{{SITENAME}}网站名称，在浏览器中打开这个链接：

$3

如果该帐户不*不*属于你，请点击此链接
取消电子邮件地址确认：

$5

这个确认码会在$4时过期。',
'confirmemail_invalidated'  => '电邮地址确认已取消',
'invalidateemail'           => '取消电邮确认',

# Scary transclusion
'scarytranscludedisabled' => '[跨网站的编码转换不可用]',
'scarytranscludefailed'   => '[提取$1失败]',
'scarytranscludetoolong'  => '[URL过长]',

# Trackbacks
'trackbackbox'      => '此页面的引用：<br />
$1',
'trackbackremove'   => '（[$1删除]）',
'trackbacklink'     => '引用',
'trackbackdeleteok' => '该引用已被成功删除。',

# Delete conflict
'deletedwhileediting' => "'''警告'''：此页在您开始编辑之后已经被删除！",
'confirmrecreate'     => "在您开始编辑这个页面后，用户[[User:$1|$1]] （[[User talk:$1|讨论]]）以下列原因删除了这个页面：
: ''$2''
请确认在您重新创建页面前三思。",
'recreate'            => '重建',

# action=purge
'confirm_purge_button' => '确定',
'confirm-purge-top'    => '要清除此页面的缓存吗?',
'confirm-purge-bottom' => '清理一页将会清除快取以及强迫显示最现时之修订版本。',

# Separators for various lists, etc.
'comma-separator' => '、',
'word-separator'  => '',
'parentheses'     => '（$1）',

# Multipage image navigation
'imgmultipageprev' => '← 上一页',
'imgmultipagenext' => '下一页 →',
'imgmultigo'       => '确定！',
'imgmultigoto'     => '到第$1页',

# Table pager
'ascending_abbrev'         => '升',
'descending_abbrev'        => '降',
'table_pager_next'         => '下一页',
'table_pager_prev'         => '上一页',
'table_pager_first'        => '第一页',
'table_pager_last'         => '末一页',
'table_pager_limit'        => '每页显示$1项',
'table_pager_limit_label'  => '每页项目数：',
'table_pager_limit_submit' => '到',
'table_pager_empty'        => '没有结果',

# Auto-summaries
'autosumm-blank'   => '清空页面',
'autosumm-replace' => '以“$1”替换内容',
'autoredircomment' => '重定向页面到[[$1]]',
'autosumm-new'     => '以内容“$1”创建新页面',

# Size units
'size-bytes' => '$1 字节',

# Live preview
'livepreview-loading' => '载入中……',
'livepreview-ready'   => '载入中……完成！',
'livepreview-failed'  => '实时预览失败！
尝试标准预览。',
'livepreview-error'   => '连接失败：$1“$2”。
尝试标准预览。',

# Friendlier slave lag warnings
'lag-warn-normal' => '过去$1秒内的更改未必会在这个列表中显示。',
'lag-warn-high'   => '由于数据库的过度延迟，过去$1秒的更改未必会在这个列表中显示。',

# Watchlist editor
'watchlistedit-numitems'       => '您的监视列表中共有$1个标题，当中不包括对话页面。',
'watchlistedit-noitems'        => '您的监视列表并无标题。',
'watchlistedit-normal-title'   => '编辑监视列表',
'watchlistedit-normal-legend'  => '从监视列表中移除标题',
'watchlistedit-normal-explain' => '在您的监视列表中的标题在下面显示。
要移除一个标题，在它前面剔一下，接着点击“{{int:Watchlistedit-normal-submit}}”。
您亦都可以[[Special:Watchlist/raw|编辑原始监视列表]]。',
'watchlistedit-normal-submit'  => '移除标题',
'watchlistedit-normal-done'    => '$1个标题已经从您的监视列表中移除：',
'watchlistedit-raw-title'      => '编辑原始监视列表',
'watchlistedit-raw-legend'     => '编辑原始监视列表',
'watchlistedit-raw-explain'    => '您的监视列表中的标题在下面显示，同时亦都可以通过编辑这个表去加入以及移除标题；一行一个标题。当完成以后，点击{{int:Watchlistedit-raw-submit}}。你亦都可以去用[[Special:Watchlist/edit|标准编辑器]]。',
'watchlistedit-raw-titles'     => '标题：',
'watchlistedit-raw-submit'     => '更新监视列表',
'watchlistedit-raw-done'       => '您的监视列表已经更新。',
'watchlistedit-raw-added'      => '已经加入了$1个标题：',
'watchlistedit-raw-removed'    => '已经移除了$1个标题：',

# Watchlist editing tools
'watchlisttools-view' => '查看有关更改',
'watchlisttools-edit' => '查看并编辑监视列表',
'watchlisttools-raw'  => '编辑源监视列表',

# Core parser functions
'unknown_extension_tag' => '不明的扩展标签“$1”',
'duplicate-defaultsort' => '警告：默认的排序键“$2”覆盖先前的默认排序键“$1”。',

# Special:Version
'version'                          => '版本',
'version-extensions'               => '已安装插件',
'version-specialpages'             => '特殊页面',
'version-parserhooks'              => '解析器钩',
'version-variables'                => '变量',
'version-antispam'                 => '垃圾防止',
'version-skins'                    => '皮肤',
'version-other'                    => '其他',
'version-mediahandlers'            => '媒体处理器',
'version-hooks'                    => '钩',
'version-extension-functions'      => '扩展函数',
'version-parser-extensiontags'     => '解析器扩展标签',
'version-parser-function-hooks'    => '解析器函数钩',
'version-skin-extension-functions' => '皮肤扩展函数',
'version-hook-name'                => '钩名',
'version-hook-subscribedby'        => '署名',
'version-version'                  => '（版本$1）',
'version-license'                  => '授权',
'version-poweredby-credits'        => "本Wiki由'''[http://www.mediawiki.org/ MediaWiki]'''驱动，版权所有 © 2001-$1 $2。",
'version-poweredby-others'         => '其他',
'version-license-info'             => 'MediaWiki为自由软件；您可依据自由软件基金会所发表的GNU通用公共授权条款规定，就本程序再为发布与／或修改；无论您依据的是本授权的第二版或（您自行选择的）任一日后发行的版本。

MediaWiki是基于使用目的而加以发布，然而不负任何担保责任；亦无对适售性或特定目的适用性所为的默示性担保。详情请参照GNU通用公共授权。

您应已收到附随于本程序的[{{SERVER}}{{SCRIPTPATH}}/COPYING GNU通用公共授权的副本]；如果没有，请写信至自由软件基金会：51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA，或[http://www.gnu.org/licenses/old-licenses/gpl-2.0.html 在线阅读]。',
'version-software'                 => '已安装软件',
'version-software-product'         => '产品',
'version-software-version'         => '版本',

# Special:FilePath
'filepath'         => '文件路径',
'filepath-page'    => '文件名：',
'filepath-submit'  => '前往',
'filepath-summary' => '此特殊页面返回文件的完整路径。图像会以完整的分辨率显示，其它的文件类型亦将直接通过关联的应用程序打开。

请输入文件名，不要包含“{{ns:file}}:”前缀。',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => '搜索重复文件',
'fileduplicatesearch-summary'  => '根据散列值（Hash值）搜索重复文件。

输入文件名时不需要输入“{{ns:file}}:”前缀。',
'fileduplicatesearch-legend'   => '搜索重复文件',
'fileduplicatesearch-filename' => '文件名：',
'fileduplicatesearch-submit'   => '搜索',
'fileduplicatesearch-info'     => '$1×$2像素<br />文件大小：$3<br />MIME类型：$4',
'fileduplicatesearch-result-1' => '文件“$1”没有完全相同的重复副本。',
'fileduplicatesearch-result-n' => '文件“$1”有$2项完全相同的重复副本。',

# Special:SpecialPages
'specialpages'                   => '特殊页面',
'specialpages-note'              => '----
* 标准特殊页面。
* <strong class="mw-specialpagerestricted">有限制的特殊页面。</strong>',
'specialpages-group-maintenance' => '维护报告',
'specialpages-group-other'       => '其它特殊页面',
'specialpages-group-login'       => '登录／注册',
'specialpages-group-changes'     => '最近更改和日志',
'specialpages-group-media'       => '媒体报告和上传',
'specialpages-group-users'       => '用户和权限',
'specialpages-group-highuse'     => '高度使用页面',
'specialpages-group-pages'       => '页面列表',
'specialpages-group-pagetools'   => '页面工具',
'specialpages-group-wiki'        => 'Wiki数据和工具',
'specialpages-group-redirects'   => '重定向特殊页面',
'specialpages-group-spam'        => '反垃圾链接工具',

# Special:BlankPage
'blankpage'              => '空白页面',
'intentionallyblankpage' => '这个页面被故意留为空白',

# External image whitelist
'external_image_whitelist' => ' #请保留此行文本<pre>
#在下方书写正则表达式（//中间的部份）
#这些规则将与外部（盗链）图像的URL匹配
#匹配的URL将被显示为图像，否则只会显示链向图像的链接
#以#开头的行会被视为注释
#大小写不敏感

#在此行上方输入所有的正则表达式。请保留此行文本</pre>',

# Special:Tags
'tags'                    => '有效更改过的标签',
'tag-filter'              => '[[Special:Tags|标签]]过滤器：',
'tag-filter-submit'       => '过滤器',
'tags-title'              => '标签',
'tags-intro'              => '这个页面列出了在软件中已标示的编辑，以及它们的解释。',
'tags-tag'                => '标签名称',
'tags-display-header'     => '在更改列表中的出现方式',
'tags-description-header' => '解释完整描述',
'tags-hitcount-header'    => '已加上标签的更改',
'tags-edit'               => '编辑',
'tags-hitcount'           => '$1次更改',

# Special:ComparePages
'comparepages'     => '比较页面',
'compare-selector' => '比较页面的修订',
'compare-page1'    => '第1页',
'compare-page2'    => '第2页',
'compare-rev1'     => '修订版本1',
'compare-rev2'     => '修订版本2',
'compare-submit'   => '比较',

# Database error messages
'dberr-header'      => '本wiki出现了问题',
'dberr-problems'    => '抱歉！
本网站出现了一些技术问题。',
'dberr-again'       => '请尝试等待数分钟后，然后再试。',
'dberr-info'        => '（无法连接到数据库服务器：$1）',
'dberr-usegoogle'   => '在此时您可以尝试通过Google搜索。',
'dberr-outofdate'   => '须注意他们索引出来的内容可能不是最新的。',
'dberr-cachederror' => '这是所请求页面的缓存副本，可能不是最新的。',

# HTML forms
'htmlform-invalid-input'       => '您输入的内容存在问题',
'htmlform-select-badoption'    => '您所指定的值不是有效的选项。',
'htmlform-int-invalid'         => '您所指定的值不是一个整数。',
'htmlform-float-invalid'       => '您所指定的值不是一个数字。',
'htmlform-int-toolow'          => '您所指定的值低于最小值$1',
'htmlform-int-toohigh'         => '您所指定的值高于最大值$1',
'htmlform-required'            => '本值必填',
'htmlform-submit'              => '提交',
'htmlform-reset'               => '撤销更改',
'htmlform-selectorother-other' => '其他',

# SQLite database support
'sqlite-has-fts' => '带全文搜索的版本$1',
'sqlite-no-fts'  => '不带全文搜索的版本$1',

# Special:DisableAccount
'disableaccount'             => '禁用用户账户',
'disableaccount-user'        => '用户名：',
'disableaccount-reason'      => '理由：',
'disableaccount-confirm'     => "禁用此用户账户。
该用户将无法登录、重置其密码或收到电子邮件通知。如果用户当前仍保持登录，其账户将被强制退出。
''留意若无系统管理员的干预，被禁用的账户不可重新启用。''",
'disableaccount-mustconfirm' => '请确认您的确要禁用此账户。',
'disableaccount-nosuchuser'  => '用户账户“$1”不存在。',
'disableaccount-success'     => '用户账户“$1”已被永久禁用。',
'disableaccount-logentry'    => '永久禁用用户账户[[$1]]',

);
