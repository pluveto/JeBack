<?php
namespace App\Helper;

/**
 * 校验帮助类
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
    public static function checkEmailFormat($mailAddress)
    {
        return preg_match('/^[_a-z0-9-\.]+@([-a-z0-9]+\.)+[a-z]{2,}$/i', $mailAddress);
    }
}
