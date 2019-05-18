<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

/**
 * 
 * @author ZhangZijing <i@pluvet.com> 2019-5-17
 */




class Upload extends NotORM
{
    private const UPLOAD_TEMP_TALBE = "upload_temp";

    public function insertTempFile($data)
    {
        $table = self::UPLOAD_TEMP_TALBE;
        $ormNonce = \PhalApi\DI()->notorm->$table;
        return $ormNonce->insert($data);
    }

    public function getTempImage(int $id)
    {
        $table = self::UPLOAD_TEMP_TALBE;
        $ormNonce = \PhalApi\DI()->notorm->$table;
        return $ormNonce->where('id', $id)->fetchOne();
    }

    public function removeTempImage(int $id)
    {
        $table = self::UPLOAD_TEMP_TALBE;
        $ormNonce = \PhalApi\DI()->notorm->$table;
        return $ormNonce->where('id', $id)->delete();
    }
}
