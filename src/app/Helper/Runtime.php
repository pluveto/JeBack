<?php
namespace App\Helper;

/**
 * 获取当前登录的用户.
 * 
 * **注意** 使用该功能的 Api, 必然已经进行了前置签名检查, 所以不要再判断是否获取到null!!!
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-15
 */
class CurrentUser
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
    /**
     * 检查用户名格式是否正确. 不能以@开头, 可以含有?.@_所有中英文和emoji.
     *
     * @param string $username
     * @return void
     */
    public static function checkUsernameFormat(string $username)
    {
        return preg_match('/^(?!@)[?.@\-a-zA-Z0-9_\x4e00-\x9fa5\xD83C-\xDBFF\xDC00-\xDFFF]+$/i', $username);
    }
}