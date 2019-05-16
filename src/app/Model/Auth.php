<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Auth extends NotORM
{

    private const NONCE_TABLE = "auth_nonce";
    private const CAPTCH_TALBE = "auth_captch";

    protected function getTableName($id)
    {
        return null;
    }
    /**
     * 获取 nonce
     *
     * @param string $nonce
     * @return array nonce 的完整数组
     */
    public function getNonce($nonce)
    {
        $table = self::NONCE_TABLE;
        $ormNonce = \PhalApi\DI()->notorm->$table;
        return $ormNonce->where('nonce', $nonce)->fetchOne();
    }
    /**
     * 删除 nonce
     *
     * @param string $nonce
     * @return void
     */
    public function deleteNonce($nonce)
    {
        $table = self::NONCE_TABLE;
        $ormNonce = \PhalApi\DI()->notorm->$table;
        $ormNonce->where('nonce', $nonce)->delete();
    }
    /**
     * 插入 Nonce
     *
     * @param array $data
     * @return void
     */
    public function insertNonce($data)
    {
        $table = self::NONCE_TABLE;
        $ormNonce = \PhalApi\DI()->notorm->$table;
        $ormNonce->insert($data);
    }
    /**
     * 插入验证码
     *
     * @param array $data
     * @return void
     */
    public function insertCaptch($data)
    {
        $table = self::CAPTCH_TALBE;
        $ormCaptch = \PhalApi\DI()->notorm->$table;
        $ormCaptch->insert($data);
    }

    public function clearExpiredCaptch($type = 0)
    {
        $table = self::CAPTCH_TALBE;
        $ormCaptch = \PhalApi\DI()->notorm->$table;
        // 删除过期 15 分钟的.
        $ormCaptch->where('created_at < ?', time() - 900)->where('type', $type)->delete();
    }
    /**
     * 清除某邮箱下的验证码
     *
     * @param string $email
     * @param integer $type
     * @return void
     */
    public function clearUserCaptch(string $title, int $type)
    {
        $table = self::CAPTCH_TALBE;
        $ormCaptch = \PhalApi\DI()->notorm->$table;
        // 删除过期 15 分钟的.
        $ormCaptch->where('title', $title)->where('type', $type)->delete();
    }

    public function clearExpiredNonce()
    {
        $table = self::NONCE_TABLE;
        $ormCaptch = \PhalApi\DI()->notorm->$table;
        // 删除过期 30s 的.
        $ormCaptch->where('created_at < ?', time() - 30)->delete();
    }

    /**
     * 获取最新存入数据库的验证码
     * * **注意** 务必先执行 clearExpiredCaptch 清理无效验证码.
     * @param int $type 类型, 0 为邮箱验证码, 1 为手机验证码
     * @param string $title 查询凭据. 即邮箱地址或手机号码(11位). 手机号码目前只支持国内.
     * @return array 验证码完整数组, 若获取不到则返回 null
     */
    public function getLastCaptch($type, $title)
    {
        $table = self::CAPTCH_TALBE;
        $ormCaptch = \PhalApi\DI()->notorm->$table;
        return $ormCaptch->where('created_at > ?', time() - 900)
            ->where('type', $type)
            ->where('title',  $title)
            ->order('created_at DESC')->fetchOne();
    }
}
