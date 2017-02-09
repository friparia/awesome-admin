<?php

namespace Friparia\Admin\Models;

use Friparia\RestModel\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $_listable = ['name', 'role'];
    protected $_creatable = ['name', 'password', 'confirm_password', 'role'];
    protected $_searchable = ['name'];
    protected $_editable = ['password', 'confirm_password', 'role'];
    protected $_filterabel = ['role'];

    protected function configure(){
        $this->addField('string', 'username')->description("账户");
        $this->addField('string', 'remember_token');
        $this->addField('string', 'password')->description("密码")->password();
        $this->addField('boolean', 'is_admin')->default(false);
        $this->addRelation('many', 'role', 'Friparia\\Admin\\Models\\Role')->description('用户组')->descriptor('name');
        $this->addField('string', 'confirm_password')->description("密码确认")->extended()->password();
        $this->addAction('modal', 'add')->single()->color('success')->description("添加");
        $this->addAction('modal', 'edit')->each()->color('info')->description("编辑");
    }

    public function role(){
        return $this->belongsToMany('Friparia\\Admin\\Models\\Role');
    }

    public function hasPermission($permission_name){
        if($this->is_admin){
            return true;
        }
        if(!count(Permission::where('name', $permission_name)->get())){
            return true;
        };
        foreach($this->role as $role){
            if($role->hasPermission($permission_name)){
                return true;
            }
        }
        return false;
    }

    public function hasRole($role_id){
        foreach($this->role as $role){
            if($role->id == $role_id){
                return true;
            }
        }
        return false;
    }

    public function canVisit($url){
        $segments = explode('?', $url);
        $uri = $segments[0];
        return $this->hasPermission(implode('.', explode('/', $uri)));
    }
}
