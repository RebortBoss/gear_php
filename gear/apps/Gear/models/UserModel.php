<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/6/16
 * Time: 10:27
 */

namespace apps\Gear\models;


use Think\Db\Adapter;
use Think\Model;

class UserModel extends Model\RelationModel
{
    protected $tablePrefix='tb_';
    protected $connection='CON_LOCAL';
    protected $_link =[
        'UserInfo'=>array(
            'mapping_type'      => self::HAS_ONE,
            'foreign_key'=>'id',
            'as_fields'=>'info,id:info_id',
        ),
    ];
}