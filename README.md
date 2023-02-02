# 小项目php框架  
安装方式 composer create-project daiyong/php-frame

# 数据库插入验证笔记
所有表主键必须为id
public function rule()
{
    return array(
        '键值|must(是否必须)' => array(
            array('string', '不满足后的提示信息',  最小长度, 最大长度),
            array('only', '是否唯一', '表名'),
            array('reg', '正则匹配', '正则语句'),
            array('idInTable','是否在另外一个表中','另外一个表的表名'),
            array('stringName', '1中文计1个字符,3个英文记1个字符,如小于最小长度则1个英文占1个字符', 最小长度, 最大长度),
            array('mail', '请填写正确的邮箱地址'),
            array('phone', '请填写正确的手机号码'),
        ),
    );
}