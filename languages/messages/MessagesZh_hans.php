<?php
/** Simplified Chinese (中文（简体）‎)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author A911504820
 * @author Alebcay
 * @author Anakmalaysia
 * @author Arlin
 * @author Bencmq
 * @author Biŋhai
 * @author Breawycker
 * @author Byfserag
 * @author Chenxiaoqino
 * @author Chenzw
 * @author Chinalace
 * @author Cicku
 * @author Cwek
 * @author Dimension
 * @author Dingyuang
 * @author Fantasticfears
 * @author Fengchao
 * @author Franklsf95
 * @author Gakmo
 * @author Gaoxuewei
 * @author GeneralNFS
 * @author Gzdavidwong
 * @author Happy
 * @author Hercule
 * @author Horacewai2
 * @author Hydra
 * @author Hzy980512
 * @author Jding2010
 * @author Jetlag
 * @author Jidanni
 * @author Jienus
 * @author Jimmy xu wrk
 * @author Kaganer
 * @author KaiesTse
 * @author Kuailong
 * @author Li3939108
 * @author Liangent
 * @author Linforest
 * @author Liuxinyu970226
 * @author M13253
 * @author Makecat
 * @author Mark85296341
 * @author MarkAHershberger
 * @author Mys 721tx
 * @author Nemo bis
 * @author O
 * @author Onecountry
 * @author PhiLiP
 * @author Qiyue2001
 * @author Shinjiman
 * @author Shirayuki
 * @author Shizhao
 * @author Simon Shek
 * @author Slboat
 * @author StephDC
 * @author Stevenliuyi
 * @author Supaiku
 * @author Tommyang
 * @author User670839245
 * @author Waihorace
 * @author Wilsonmess
 * @author Wmr89502270
 * @author Wong128hk
 * @author Wrightbus
 * @author Xiaomingyan
 * @author Yfdyh000
 * @author Zoglun
 * @author 乌拉跨氪
 * @author 御坂美琴
 * @author 燃玉
 * @author 范
 * @author 阿pp
 */

$fallback8bitEncoding = 'windows-936';

$namespaceNames = [
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
];

$namespaceAliases = [
	'媒体'	=> NS_MEDIA,
	'特殊'  => NS_SPECIAL,
	'对话'  => NS_TALK,
	'讨论'	=> NS_TALK,
	'用户'  => NS_USER,
	'用户对话' => NS_USER_TALK,
	'用户讨论' => NS_USER_TALK,
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
];

$specialPageAliases = [
	'Activeusers'               => [ '活跃用户' ],
	'Allmessages'               => [ '所有信息' ],
	'AllMyUploads'              => [ '我上传的所有文件', '我的所有文件' ],
	'Allpages'                  => [ '所有页面' ],
	'ApiHelp'                   => [ 'Api帮助' ],
	'Ancientpages'              => [ '最老页面' ],
	'Badtitle'                  => [ '错误标题', '无效标题' ],
	'Blankpage'                 => [ '空白页面' ],
	'Block'                     => [ '封禁', '封禁IP', '封禁用户', '封' ],
	'Booksources'               => [ '网络书源' ],
	'BrokenRedirects'           => [ '受损重定向' ],
	'Categories'                => [ '页面分类' ],
	'ChangeEmail'               => [ '修改邮箱地址' ],
	'ChangePassword'            => [ '修改密码', '重置密码', '找回密码' ],
	'ComparePages'              => [ '对比页面' ],
	'Confirmemail'              => [ '确认电子邮件' ],
	'Contributions'             => [ '用户贡献', '贡献' ],
	'CreateAccount'             => [ '创建账户' ],
	'Deadendpages'              => [ '断链页面' ],
	'DeletedContributions'      => [ '已删除的用户贡献' ],
	'Diff'                      => [ '编辑差异' ],
	'DoubleRedirects'           => [ '双重重定向', '两次重定向' ],
	'EditWatchlist'             => [ '编辑监视列表' ],
	'Emailuser'                 => [ '电邮联系' ],
	'ExpandTemplates'           => [ '展开模板' ],
	'Export'                    => [ '导出页面', '导出' ],
	'Fewestrevisions'           => [ '版本最少页面', '最少修订页面' ],
	'FileDuplicateSearch'       => [ '搜索重复文件' ],
	'Filepath'                  => [ '文件路径' ],
	'Import'                    => [ '导入页面', '导入' ],
	'Invalidateemail'           => [ '无效电邮地址' ],
	'JavaScriptTest'            => [ 'JavaScript测试' ],
	'BlockList'                 => [ '封禁列表', 'IP封禁列表' ],
	'LinkSearch'                => [ '搜索网页链接' ],
	'Listadmins'                => [ '管理员列表' ],
	'Listbots'                  => [ '机器人列表' ],
	'Listfiles'                 => [ '文件列表', '图像列表' ],
	'Listgrouprights'           => [ '用户组权限' ],
	'Listredirects'             => [ '重定向页列表' ],
	'ListDuplicatedFiles'       => [ '重复文件列表' ],
	'Listusers'                 => [ '用户列表' ],
	'Lockdb'                    => [ '锁定数据库' ],
	'Log'                       => [ '日志' ],
	'Lonelypages'               => [ '孤立页面' ],
	'Longpages'                 => [ '长页面' ],
	'MediaStatistics'           => [ '媒体统计' ],
	'MergeHistory'              => [ '合并历史' ],
	'MIMEsearch'                => [ 'MIME搜索' ],
	'Mostcategories'            => [ '最多分类页面' ],
	'Mostimages'                => [ '最多链接文件' ],
	'Mostinterwikis'            => [ '最多跨wiki链接页面' ],
	'Mostlinked'                => [ '最多链接页面' ],
	'Mostlinkedcategories'      => [ '最多链接分类' ],
	'Mostlinkedtemplates'       => [ '最多嵌入页面', '最多链接模板', '最多使用模板' ],
	'Mostrevisions'             => [ '最多修订页面' ],
	'Movepage'                  => [ '移动页面' ],
	'Mycontributions'           => [ '我的贡献' ],
	'MyLanguage'                => [ '我的语言' ],
	'Mypage'                    => [ '我的用户页' ],
	'Mytalk'                    => [ '我的讨论页', '我的对话页' ],
	'Myuploads'                 => [ '我上传的文件', '我的文件' ],
	'Newimages'                 => [ '新建文件', '新建图像' ],
	'Newpages'                  => [ '新建页面' ],
	'PagesWithProp'             => [ '带属性的页面', '基于属性的页面' ],
	'PageLanguage'              => [ '页面语言' ],
	'PasswordReset'             => [ '重设密码' ],
	'PermanentLink'             => [ '固定链接', '永久链接' ],
	'Preferences'               => [ '参数设置', '设置' ],
	'Prefixindex'               => [ '前缀索引' ],
	'Protectedpages'            => [ '已保护页面' ],
	'Protectedtitles'           => [ '已保护标题' ],
	'Randompage'                => [ '随机', '随机页面' ],
	'RandomInCategory'          => [ '分类内随机' ],
	'Randomredirect'            => [ '随机重定向', '随机重定向页' ],
	'Randomrootpage'            => [ '随机根页面' ],
	'Recentchanges'             => [ '最近更改' ],
	'Recentchangeslinked'       => [ '最近链出更改', '相关更改' ],
	'Redirect'                  => [ '重定向' ],
	'ResetTokens'               => [ '重置权标' ],
	'Revisiondelete'            => [ '删除修订', '恢复修订' ],
	'RunJobs'                   => [ '运行工作' ],
	'Search'                    => [ '搜索' ],
	'Shortpages'                => [ '短页面' ],
	'Specialpages'              => [ '特殊页面' ],
	'Statistics'                => [ '统计信息' ],
	'Tags'                      => [ '标签' ],
	'TrackingCategories'        => [ '追踪分类' ],
	'Unblock'                   => [ '解除封禁', '解封' ],
	'Uncategorizedcategories'   => [ '未分类分类' ],
	'Uncategorizedimages'       => [ '未分类文件', '未分类图像' ],
	'Uncategorizedpages'        => [ '未分类页面' ],
	'Uncategorizedtemplates'    => [ '未分类模板' ],
	'Undelete'                  => [ '恢复被删页面' ],
	'Unlockdb'                  => [ '解除数据库锁定' ],
	'Unusedcategories'          => [ '未使用分类' ],
	'Unusedimages'              => [ '未使用文件', '未使用图像' ],
	'Unusedtemplates'           => [ '未使用模板' ],
	'Unwatchedpages'            => [ '未受监视页面' ],
	'Upload'                    => [ '上传文件' ],
	'UploadStash'               => [ '上传藏匿' ],
	'Userlogin'                 => [ '用户登录', '登录' ],
	'Userlogout'                => [ '用户退出', '退出' ],
	'Userrights'                => [ '用户权限' ],
	'Version'                   => [ '版本', '版本信息' ],
	'Wantedcategories'          => [ '需要的分类', '待撰分类' ],
	'Wantedfiles'               => [ '需要的文件' ],
	'Wantedpages'               => [ '需要的页面', '待撰页面', '受损链接' ],
	'Wantedtemplates'           => [ '需要的模板' ],
	'Watchlist'                 => [ '监视列表' ],
	'Whatlinkshere'             => [ '链入页面' ],
	'Withoutinterwiki'          => [ '无跨wiki', '无跨wiki链接页面' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#重定向', '#REDIRECT' ],
	'notoc'                     => [ '0', '__无目录__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__无图库__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__强显目录__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__目录__', '__TOC__' ],
	'noeditsection'             => [ '0', '__无编辑段落__', '__无段落编辑__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', '本月', '本月2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', '本月1', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', '本月名', '本月名称', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', '本月名属格', '本月名称属格', 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'        => [ '1', '本月简称', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', '今天', 'CURRENTDAY' ],
	'currentday2'               => [ '1', '今天2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', '星期', '今天名', '今天名称', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', '今年', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', '当前时间', '此时', 'CURRENTTIME' ],
	'currenthour'               => [ '1', '当前小时', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', '本地月', '本地月2', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', '本地月1', 'LOCALMONTH1' ],
	'localmonthname'            => [ '1', '本地月份名', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', '本地月历', 'LOCALMONTHNAMEGEN' ],
	'localmonthabbrev'          => [ '1', '本地月缩写', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', '本地日', 'LOCALDAY' ],
	'localday2'                 => [ '1', '本地日2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', '本地日名', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', '本地年', 'LOCALYEAR' ],
	'localtime'                 => [ '1', '本地时间', 'LOCALTIME' ],
	'localhour'                 => [ '1', '本地小时', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', '页面数', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', '条目数', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', '文件数', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', '用户数', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', '活跃用户数', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', '编辑数', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', '页名', '页面名', '页面名称', 'PAGENAME' ],
	'pagenamee'                 => [ '1', '页面名等同', '页面名称等同', 'PAGENAMEE' ],
	'namespace'                 => [ '1', '名字空间', 'NAMESPACE' ],
	'namespacee'                => [ '1', '名字空间等同', 'NAMESPACEE' ],
	'namespacenumber'           => [ '1', '名字空间编号', 'NAMESPACENUMBER' ],
	'talkspace'                 => [ '1', '讨论空间', '讨论名字空间', 'TALKSPACE' ],
	'talkspacee'                => [ '1', '讨论空间等同', '讨论名字空间等同', 'TALKSPACEE' ],
	'subjectspace'              => [ '1', '主名字空间', '条目名字空间', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', '主名字空间等同', '条目名字空间等同', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'fullpagename'              => [ '1', '页面全称', '完整页面名称', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', '完整页面名称等同', 'FULLPAGENAMEE' ],
	'subpagename'               => [ '1', '子页面名称', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', '子页面名称等同', 'SUBPAGENAMEE' ],
	'rootpagename'              => [ '1', '根页面名称', 'ROOTPAGENAME' ],
	'rootpagenamee'             => [ '1', '根页面名称等同', 'ROOTPAGENAMEE' ],
	'basepagename'              => [ '1', '基础页面名称', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', '基础页面名称等同', 'BASEPAGENAMEE' ],
	'talkpagename'              => [ '1', '讨论页面名称', '对话页面名称', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', '讨论页面名称等同', '对话页面名称等同', 'TALKPAGENAMEE' ],
	'subjectpagename'           => [ '1', '主名字空间页面名称', '条目页面名称', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', '主名字空间页面名称等同', '条目页面名称等同', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'subst'                     => [ '0', '替代:', 'SUBST:' ],
	'safesubst'                 => [ '0', '安全替代:', 'SAFESUBST:' ],
	'img_thumbnail'             => [ '1', '缩略图', 'thumbnail', 'thumb' ],
	'img_manualthumb'           => [ '1', '缩略图=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', '右', 'right' ],
	'img_left'                  => [ '1', '左', 'left' ],
	'img_none'                  => [ '1', '无', 'none' ],
	'img_width'                 => [ '1', '$1像素', '$1px' ],
	'img_center'                => [ '1', '居中', 'center', 'centre' ],
	'img_framed'                => [ '1', '有框', 'framed', 'enframed', 'frame' ],
	'img_frameless'             => [ '1', '无框', 'frameless' ],
	'img_lang'                  => [ '1', '语言=$1', 'lang=$1' ],
	'img_page'                  => [ '1', '页数=$1', '$1页', 'page=$1', 'page $1' ],
	'img_upright'               => [ '1', '右上', '右上=$1', '右上$1', 'upright', 'upright=$1', 'upright $1' ],
	'img_border'                => [ '1', '边框', 'border' ],
	'img_baseline'              => [ '1', '基线', 'baseline' ],
	'img_sub'                   => [ '1', '子', 'sub' ],
	'img_super'                 => [ '1', '超', 'super', 'sup' ],
	'img_top'                   => [ '1', '顶部', 'top' ],
	'img_text_top'              => [ '1', '文字顶部', 'text-top' ],
	'img_middle'                => [ '1', '中间', 'middle' ],
	'img_bottom'                => [ '1', '底部', 'bottom' ],
	'img_text_bottom'           => [ '1', '文字底部', 'text-bottom' ],
	'img_link'                  => [ '1', '链接=$1', 'link=$1' ],
	'img_alt'                   => [ '1', '替代=$1', '替代文本=$1', 'alt=$1' ],
	'img_class'                 => [ '1', '类=$1', 'class=$1' ],
	'int'                       => [ '0', '界面:', 'INT:' ],
	'sitename'                  => [ '1', '站点名称', 'SITENAME' ],
	'ns'                        => [ '0', '名称空间:', 'NS:' ],
	'nse'                       => [ '0', '名称空间E:', 'NSE:' ],
	'localurl'                  => [ '0', '本地URL:', 'LOCALURL:' ],
	'localurle'                 => [ '0', '本地URLE:', 'LOCALURLE:' ],
	'articlepath'               => [ '0', '条目路径', 'ARTICLEPATH' ],
	'pageid'                    => [ '0', '页面ID', 'PAGEID' ],
	'server'                    => [ '0', '服务器', 'SERVER' ],
	'servername'                => [ '0', '服务器名', 'SERVERNAME' ],
	'scriptpath'                => [ '0', '脚本路径', 'SCRIPTPATH' ],
	'stylepath'                 => [ '0', '样式路径', 'STYLEPATH' ],
	'grammar'                   => [ '0', '语法:', 'GRAMMAR:' ],
	'gender'                    => [ '0', '性别:', 'GENDER:' ],
	'notitleconvert'            => [ '0', '__不转换标题__', '__NOTITLECONVERT__', '__NOTC__' ],
	'nocontentconvert'          => [ '0', '__不转换内容__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'currentweek'               => [ '1', '本周', 'CURRENTWEEK' ],
	'currentdow'                => [ '1', '当前DOW', 'CURRENTDOW' ],
	'localweek'                 => [ '1', '本地周', 'LOCALWEEK' ],
	'localdow'                  => [ '1', '本地DOW', 'LOCALDOW' ],
	'revisionid'                => [ '1', '修订ID', 'REVISIONID' ],
	'revisionday'               => [ '1', '修订日', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', '修订日2', 'REVISIONDAY2' ],
	'revisionmonth'             => [ '1', '修订月', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', '修订月1', 'REVISIONMONTH1' ],
	'revisionyear'              => [ '1', '修订年', 'REVISIONYEAR' ],
	'revisiontimestamp'         => [ '1', '修订时间戳', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', '修订用户', 'REVISIONUSER' ],
	'revisionsize'              => [ '1', '修订大小', 'REVISIONSIZE' ],
	'plural'                    => [ '0', '复数:', 'PLURAL:' ],
	'fullurl'                   => [ '0', '完整URL:', 'FULLURL:' ],
	'fullurle'                  => [ '0', '完整URL等同:', 'FULLURLE:' ],
	'canonicalurl'              => [ '0', '规范URL:', 'CANONICALURL:' ],
	'canonicalurle'             => [ '0', '规范URL等同:', 'CANONICALURLE:' ],
	'lcfirst'                   => [ '0', '小写首字:', 'LCFIRST:' ],
	'ucfirst'                   => [ '0', '大写首字:', 'UCFIRST:' ],
	'lc'                        => [ '0', '小写:', 'LC:' ],
	'uc'                        => [ '0', '大写:', 'UC:' ],
	'raw'                       => [ '0', '原始:', 'RAW:' ],
	'displaytitle'              => [ '1', '显示标题', 'DISPLAYTITLE' ],
	'newsectionlink'            => [ '1', '__新段落链接__', '__NEWSECTIONLINK__' ],
	'nonewsectionlink'          => [ '1', '__无新段落链接__', '__NONEWSECTIONLINK__' ],
	'currentversion'            => [ '1', '当前版本', 'CURRENTVERSION' ],
	'urlencode'                 => [ '0', 'URL编码:', 'URLENCODE:' ],
	'anchorencode'              => [ '0', '锚编码', 'ANCHORENCODE' ],
	'currenttimestamp'          => [ '1', '当前时间戳', 'CURRENTTIMESTAMP' ],
	'localtimestamp'            => [ '1', '本地时间戳', 'LOCALTIMESTAMP' ],
	'directionmark'             => [ '1', '方向标记', 'DIRECTIONMARK', 'DIRMARK' ],
	'language'                  => [ '0', '#语言:', '#LANGUAGE:' ],
	'contentlanguage'           => [ '1', '内容语言', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'pagesinnamespace'          => [ '1', '名字空间中页面数:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'numberofadmins'            => [ '1', '管理员数', 'NUMBEROFADMINS' ],
	'formatnum'                 => [ '0', '格式化数字', 'FORMATNUM' ],
	'padleft'                   => [ '0', '左填充', 'PADLEFT' ],
	'padright'                  => [ '0', '右填充', 'PADRIGHT' ],
	'special'                   => [ '0', '特殊', 'special' ],
	'speciale'                  => [ '0', '特殊等同', 'speciale' ],
	'defaultsort'               => [ '1', '默认排序:', '默认排序关键字:', '默认分类排序:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'filepath'                  => [ '0', '文件路径:', 'FILEPATH:' ],
	'tag'                       => [ '0', '标记', 'tag' ],
	'hiddencat'                 => [ '1', '__隐藏分类__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', '分类中页面数', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', '页面大小', 'PAGESIZE' ],
	'index'                     => [ '1', '__索引__', '__INDEX__' ],
	'noindex'                   => [ '1', '__无索引__', '__NOINDEX__' ],
	'numberingroup'             => [ '1', '组中用户数', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'staticredirect'            => [ '1', '__静态重定向__', '__STATICREDIRECT__' ],
	'protectionlevel'           => [ '1', '保护级别', 'PROTECTIONLEVEL' ],
	'cascadingsources'          => [ '1', '级联来源', 'CASCADINGSOURCES' ],
	'formatdate'                => [ '0', '格式化日期', '日期格式化', 'formatdate', 'dateformat' ],
	'url_path'                  => [ '0', '路径', 'PATH' ],
	'url_query'                 => [ '0', '查询', 'QUERY' ],
	'defaultsort_noerror'       => [ '0', '不报错', 'noerror' ],
	'defaultsort_noreplace'     => [ '0', '不替换', 'noreplace' ],
	'displaytitle_noerror'      => [ '0', '无错误', 'noerror' ],
	'displaytitle_noreplace'    => [ '0', '无代替', 'noreplace' ],
	'pagesincategory_all'       => [ '0', '所有', 'all' ],
	'pagesincategory_pages'     => [ '0', '页面', 'pages' ],
	'pagesincategory_subcats'   => [ '0', '子分类', 'subcats' ],
	'pagesincategory_files'     => [ '0', '文件', 'files' ],
];

$linkTrail = '/^()(.*)$/sD';

$datePreferences = [
	'default',
	'ISO 8601',
];
$defaultDateFormat = 'zh';
$dateFormats = [
	'zh time' => 'H:i',
	'zh date' => 'Y年n月j日 (l)',
	'zh both' => 'Y年n月j日 (D) H:i',
];

$bookstoreList = [
	'AddALL' => 'http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN',
	'Barnes & Noble' => 'http://search.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1',
	'亚马逊' => 'http://www.amazon.com/exec/obidos/ISBN=$1',
	'卓越亚马逊' => 'http://www.amazon.cn/mn/advancedSearchApp?isbn=$1',
	'当当网' => 'http://search.dangdang.com/search.aspx?key=$1',
	'博客来书店' => 'http://www.books.com.tw/exep/prod/booksfile.php?item=$1',
	'三民书店' => 'http://www.sanmin.com.tw/page-qsearch.asp?ct=search_isbn&qu=$1',
	'天下书店' => 'http://www.cwbook.com.tw/search/result1.jsp?field=2&keyWord=$1',
	'新丝路书店' => 'http://www.silkbook.com/function/Search_list_book_data.asp?item=5&text=$1'
];

