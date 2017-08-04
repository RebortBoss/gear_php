<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/4/12
 * Time: 11:10
 */

namespace src\traits;


use src\plugins\db\Db;

class RbacBasedNotORM implements IRbac
{
    /** @var $orm \NotORM */
    /** @var $db Db */

    protected $db;
    protected $orm;
    protected $tb_prefix = 'rbac_';
    protected $db_config = '';
    protected $tb_user = '';
    protected $tb_role = '';
    protected $tb_action = '';
    protected $tb_role_action = '';
    protected $tb_role_user = '';

    public function __construct()
    {
        $this->db = maker()->db($this->db_config);
        $this->orm = $this->db->getOrm();
        $this->tb_user = $this->tb_user ? $this->tb_user : $this->tb_prefix . 'user';
        $this->tb_role = $this->tb_role ? $this->tb_role : $this->tb_prefix . 'role';
        $this->tb_action = $this->tb_action ? $this->tb_action : $this->tb_prefix . 'action';
        $this->tb_role_action = $this->tb_role_action ? $this->tb_role_action : $this->tb_prefix . 'role_action';
        $this->tb_role_user = $this->tb_role_user ? $this->tb_role_user : $this->tb_prefix . 'role_user';
    }

    public function getUser($user_id,$asArray=true)
    {
        $user = $this->orm->{$this->tb_user}->whereId($user_id);
        return $user ? ($asArray?$user->fetchAsArray():$user->fetch()) : null;
    }

    public function getUsersFromRole($role_id)
    {
        $rel=[];
        $role=$this->getRole($role_id,false);
        if ($role){
            foreach ($role->{$this->tb_role_user}() as $role_user){
                /** @var  \NotORM_Row */
                $row=$role_user->{$this->tb_user};
                if ($row){
                    $rel[]=$row ->getRowArray();
                }
            }
            return $rel;
        }else{
            return null;
        }
    }

    public function getAction($action_id,$asArray=true)
    {
        $action = $this->orm->{$this->tb_action}->whereId($action_id);
        return $action ? ($asArray?$action->fetchAsArray():$action->fetch()) : null;
    }

    public function getActionsFromUser($user_id)
    {
        $roles=$this->getRolesFromUser($user_id);
        if(!$roles){$roles=[];}
        $rel=[];
        foreach ($roles as $role){
            $actions=$this->getActionsFromRole($role['id']);
            $rel=array_merge($rel,$actions);
        }
        return $rel;
    }

    public function getActionsFromRole($role_id)
    {
        $rel=[];
        $role=$this->getRole($role_id,false);
        if ($role){
            foreach ($role->{$this->tb_role_action}() as $role_user){
                /** @var  \NotORM_Row */
                $rel[]= $role_user->{$this->tb_action}->getRowArray();
            }
            return $rel;
        }else{
            return null;
        }
    }

    public function isUserHasAction($user_id, $action_id)
    {
        $actions=$this->getActionsFromUser($user_id);
        foreach ($actions as $action){
            if ($action['id']==$action_id){
                return true;
            }
        }
        return false;
    }

    public function isRoleHasAction($role_id, $action_id)
    {
        $actions=$this->getActionsFromRole($role_id);
        foreach ($actions as $action){
            if ($action['id']==$action_id){
                return true;
            }
        }
        return false;
    }

    public function getRole($role_id,$asArray=true)
    {
        $role = $this->orm->{$this->tb_role}->whereId($role_id);
        return $role ? ($asArray?$role->fetchAsArray():$role->fetch()) : false;
    }

    public function getRolesFromUser($user_id)
    {
        $rel=[];
        $user=$this->getUser($user_id,false);
        if ($user){
            foreach ($user->{$this->tb_role_user}() as $role_user){
                /** @var  \NotORM_Row */
                $rel[]= $role_user->{$this->tb_role}->getRowArray();
            }
            return $rel;
        }else{
            return [];
        }
    }

    public function isUserHasRole($user_id, $role_id)
    {
        $roles=$this->getRolesFromUser($user_id);
        foreach ($roles as $role){
            if ($role['id']==$role_id){
                return true;
            }
        }
        return false;
    }

    public function addUserToRole($user_id,$role_id){
        $user=$this->getUser($user_id);
        $role=$this->getRole($role_id);
        if ($user and $role){
            $data=[
                $this->tb_prefix.'user_id'=>$user_id,
                $this->tb_prefix.'role_id'=>$role_id,
            ];
            return $this->orm->{$this->tb_role_user}->insert_update($data,$data,$data);
        }else{
            return false;
        }
    }

    public function removeUserFromRole($user_id,$role_id){
        $condition=[
            $this->tb_prefix.'user_id'=>$user_id,
            $this->tb_prefix.'role_id'=>$role_id,
        ];
        return $this->orm->{$this->tb_role_user}->where($condition)->delete();
    }

    public function removeActionRole($action_id,$role_id){
        $condition=[
            $this->tb_prefix.'action_id'=>$action_id,
            $this->tb_prefix.'role_id'=>$role_id,
        ];
        return $this->orm->{$this->tb_role_action}->where($condition)->delete();
    }
    public function addActionRole($action_id,$role_id){
        $condition=[
            $this->tb_prefix.'action_id'=>$action_id,
            $this->tb_prefix.'role_id'=>$role_id,
        ];
        $tb=$this->orm->{$this->tb_role_action};
        $isExist=$tb->where($condition)->fetch();
        if ($isExist){return false;}
        else{
            return $tb->insert($condition);
        }
    }

    public function addAction($action_name){
        $condition=['name'=>$action_name];
        $tb=$this->orm->{$this->tb_action};
        $isExist=$tb->where($condition)->fetch();
        if ($isExist){return false;}
        else{
            $isSuccess=$tb->insert($condition);
            if ($isSuccess){
                return $tb->insert_id();
            }else{
                return false;
            }
        }
    }

    public function removeAction($action_id){
        $condition=[
            'id'=>$action_id,
        ];
        return $this->orm->{$this->tb_action}->where($condition)->delete();
    }

    public function addRole($role_name){
        $condition=['name'=>$role_name];
        $tb=$this->orm->{$this->tb_role};
        $isExist=$tb->where($condition)->fetch();
        if ($isExist){return false;}
        else{
            $isSuccess=$tb->insert($condition);
            if ($isSuccess){
                return $tb->insert_id();
            }else{
                return false;
            }
        }
    }

    public function removeRole($role_id){
        $condition=[
            'id'=>$role_id,
        ];
        return $this->orm->{$this->tb_role}->where($condition)->delete();
    }

}