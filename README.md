# 小项目php框架  
安装方式 composer create-project daiyong/php-frame

## 目录结构介绍
.  
├── code #代码文件夹  
│   ├── controller  
│   │   ├── Admin #例:admin命名空间  
│   │   │   ├── Admin.php #例:控制器  
│   │   │   └── Index.php #例:控制器  
│   │   ├── Cmd #例:cmd命名空间  
│   │   │   └── Index.php #例:控制器  
│   │   └── Common.php #controller公用类  
│   └── model  
│       ├── Admin.php #例:admin模型  
│       └── Common.php #model公用类  
├── composer.json  
├── composer.lock  
├── config.php #配置文件  
├── data #一般用来存放文件  
├── entry #访问入口,外部一切访问从这个文件夹进入  
│   ├── cmd.php #文件内填写对应的命名空间  
│   └── index.php #文件内填写对应的命名空间  
└── README.md  

## 修改数据库数据时,model类rule写法笔记
注:所有表主键必须为id  
```
public function rule()
{
    return array(
        '键值|must(新增时是否必须)' => array(//这是具体说明,如果只有一条可以为一维数组
            array('string', '不满足后的提示信息',  最小长度, 最大长度),
            array('only', '是否唯一', '表名'),
            array('reg', '正则匹配', '正则语句'),
            array('idInTable','该字段值是否对应另一个表的id','另外一个表的表名'),
            array('stringName', '1中文计1个字符,3个英文记1个字符,如小于最小长度则1个英文占1个字符', 最小长度, 最大长度),
            array('mail', '请填写正确的邮箱地址'),
            array('phone', '请填写正确的手机号码'),
        ),
        'username|must' => array(//这是一个例子
            array('string', '用户名必须在6~15个字符之间',  6, 15),
            array('only', '用户名重复', 'admin'),
            array('reg', '用户名必须为英文或数字组合', '/^[\w]+$/'),
        ),
        'nickname'=>array(//这是一个例子
            array('stringName', '昵称必须在2~6个字符之间', 2, 6),
        ),
        'mail'=>array('mail', '请填写正确的邮箱地址'),//这是一个例子
        'phone'=>array('phone', '请填写正确的手机号码'),//这是一个例子
        'group'=>array('idInTable','您选择的类型不存在','admin_type')//这是一个例子
    );
}
```