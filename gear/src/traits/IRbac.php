<?php
/**
 * Created by PhpStorm.
 * User: Yuri2
 * Date: 2017/4/12
 * Time: 10:43
 */

namespace src\traits;


interface IRbac
{
    public function getUser($user_id);
    public function getUsersFromRole($role_id);
    public function getAction($action_id);
    public function getActionsFromUser($user_id);
    public function getActionsFromRole($role_id);
    public function isUserHasAction($user_id,$action_id);
    public function isRoleHasAction($role_id,$action_id);
    public function getRole($role_id);
    public function getRolesFromUser($user_id);
    public function isUserHasRole($user_id,$role_id);
}