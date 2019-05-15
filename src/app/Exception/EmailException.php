<?php
namespace App\Exception;


/**
 * EmailException 邮件发送失败
 *
 * 邮件发送失败
 *
 * @author ZhangZijing <i@pluvet.com>
 */

class EmailException extends \PhalApi\Exception
{

    public function __construct($message, $code = 0)
    {
        parent::__construct(
            \PhalApi\T('邮件发送异常: {message}', array('message' => $message)),
            400 + $code
        );
    }
}
