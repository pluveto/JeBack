<?php
namespace App\Helper;


/**
 * 系统邮件帮助类
 * @author ZhangZijing <i@pluvet.com> 2019-5-15
 */
class SysEmail
{
    /*    public function test()
    {

        $mail = new \Nette\Mail\Message;
        $mail->setFrom('Bipubipu <test@azalea.moe>')
            ->addTo('reg@pluvet.com')
            ->setSubject('Order Confirmation')
            ->setBody("Hello, Your order has been accepted.");
        $mailer = new \Nette\Mail\SmtpMailer([
            'host' => 'mail.azalea.moe',
            'username' => 'test@azalea.moe',
            'password' => 'wAp3tgcm5C9BiyT',
            'port' => 465,
            'secure' => 'ssl',
        ]);
        $mailer->send($mail);
    }
    */
    /**
     * 发送邮件(支持HTML内容)
     *
     * @param string $receiver 接受者
     * @param string $subject 主题
     * @param string $body 正文
     * @return void
     */
    public function send($receiver, $subject, $body)
    {

        $mail = new \Nette\Mail\Message;

        $fromName = \PhalApi\DI()->config->get('je.sys_email.from');

        $mail->setFrom($fromName)
            ->addTo($receiver)
            ->setSubject($subject)
            ->setHtmlBody($body);

        $mailConfig = \PhalApi\DI()->config->get('je.sys_email.auth');
        $mailer = new \Nette\Mail\SmtpMailer($mailConfig);

        try {
            $mailer->send($mail);
        } catch (SmtpException $e) {
            throw \App\Exception\EmailException($e->getMessage());
        }
    }
    /**
     * 发送邮箱验证码
     *
     * @param string $receiver
     * @param string/int $captch
     * @return void
     */
    public function sendCaptch($receiver, $captch)
    {
        $mailTemplate = \PhalApi\DI()->config->get('je.sys_email.template.captch');

        $subject =  str_replace('{captch}', $captch,  $mailTemplate['subject']);
        $body =     str_replace('{captch}', $captch,  $mailTemplate['body']);

        $this->send($receiver, $subject, $body);
    }
}
