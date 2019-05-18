<?php
namespace App\Domain;

use App\Model\Group as Model_Auth_Group;

/**
 * 组领域类
 *
 * @author hms
 */
class Group
{
    private static $Model = null;

    public function  __construct()
    {
        if (self::$Model == null) {
            self::$Model = new Model_Auth_Group();
        }
    }

    /**获取组列表
     * @param $apiObj Api对象，方便多参数获取
     * @return array 数据对象
     * @return array 数据对象[items] 数据项
     * @return int 数据对象[count] 数据总数 用于分页
     */
    public function getGroupList($apiObj)
    {
        $rs = array('items' => array(), 'count' => 0);
        $param = get_object_vars($apiObj);
        $rs['count'] = self::$Model->getGroupCount($param['keyWord']);
        $rs['items'] = self::$Model->getGroupList($param);
        return $rs;
    }

    /**添加组
     * @param $apiObj api对象
     * @return int 成功返回0，失败返回1，名称重复返回2，
     */
    public function addGroup($apiObj)
    {
        $param = get_object_vars($apiObj);
        //检查名称重复，重复返回2
        $r = self::$Model->checkRepeat($param['title']);
        if ($r)
            return 2;
        //成功返回0，失败返回2
        $r = self::$Model->addGroup($param);
        return $r == true ? 0 : 1;
    }

    /**修改组
     * @param $apiObj
     * @return int 成功返回0，失败返回1，名称重复返回2
     */
    public function editGroup($apiObj)
    {
        $param = get_object_vars($apiObj);
        //检查名称重复，重复返回2
        $r = self::$Model->checkRepeat($param['title'], $param['id']);
        if ($r)
            return 2;
        //处理参数
        $info['title'] = $param['title'];
        if ($param['status'] !== null) {
            $info['status'] = $param['status'];
        }
        $r = self::$Model->editGroup($param['id'], $info);
        return $r == true ? 0 : 1;
    }

    /** 删除组
     * @param $ids id列表 如1,2,3
     * @return int
     */
    public function delGroup($ids)
    {
        $arrIds = explode(',', $ids);
        $r = self::$Model->delGroup($arrIds);
        return $r == true ? 0 : 1;
    }

    /** 设置规则
     * @param $id
     * @param $rules
     * @return int
     */
    public function setRules($id, $rules)
    {
        $info['rules'] = $rules;
        $r = self::$Model->setRules($id, $info);
        return $r == true ? 0 : 1;
    }

    /** 获取单个组信息
     * @param $id
     * @return mixed
     */
    public function getGroupOne($id)
    {
        $r = self::$Model->getGroupOne($id);
        return $r;
    }

    /**组与用户关联操作
     * 此操作会先把传递过来的用户id关联的所有组删除，然后在进行添加。
     * 如果传过来的组id为空，则代表解除指定用户id所有关联的组。
     * @param $apiObj
     * @return int
     */
    public function assUser($apiObj)
    {
        $param = get_object_vars($apiObj);
        $accessModel = new \Phalapi\Auth\Auth\Model\Access();

        //先删除当前用户的所有关联组
        $accessModel->delByUid($param['uid']);
        if ($param['group_id'] == '') {
            return 0;
        }

        //因为使用批量添加操作，所以得处理参数
        $gids = explode(',', $param['group_id']);
        $arr = array();
        foreach ($gids as $key => $v) {
            $arr[$key]['uid'] = $param['uid'];
            $arr[$key]['group_id'] = $v;
        }

        //执行批量添加
        $r = $accessModel->assUser($arr);
        return $r == true ? 0 : 1;
    }

    public function getUserInGroups($uid)
    {
        if (\Phalapi\DI()->cache === null) {
            $r = self::$Model->getUserInGroups($uid);
        } else {
            $r = self::$Model->getUserInGroupsCache($uid);
        }
        return $r;
    }
}
