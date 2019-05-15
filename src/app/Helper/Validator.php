<?php
namespace App\Helper;

/**
 * 静态检查帮助类, 只能检查格式. 不要在这里对 Domain / Model 层进行访问.
 * @author ZhangZijing <i@pluvet.com> 2019-5-15
 */
class Validator
{
    /**
     * 检查邮箱格式是否正确
     *
     * @param string $mailAddress 邮箱地址
     * @return bool 正确返回true
     */
    public static function checkEmailFormat(string $mailAddress)
    {
        return preg_match('/^[_a-z0-9-\.]+@([-a-z0-9]+\.)+[a-z]{2,}$/i', $mailAddress);
    }
}
