<?php

namespace Friparia\Admin\Controllers;

use Friparia\Admin\Controllers\AdminBaseController as BaseController;
use Friparia\Admin\Models\Role;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    protected $model = 'Friparia\\Admin\\Models\\Role';

    protected $actions = ['create', 'update'];

    public function update(Request $request, $id){
        $input = \Request::input();
        $role = Role::find($id);
        if(!isset($input['name'])){
            \Request::session()->flash("error", "请输入名称");
            return back();
        }
        $role->name = $input['name'];
        $role->save();
        if(isset($input['permission'])){
            $data = [];
            foreach($input['permission'] as $key => $value){
                if($value == 'on'){
                    $data[] = $key;
                }
            }
            $role->permission()->sync($data);
        }else{
            $role->permission()->sync([]);
        }
        $role->save();
        \Request::session()->flash("success", '操作成功');
        return back();
    }

    public function create(Request $request){
        $input = \Request::input();
        $role = new Role;
        if(!isset($input['name'])){
            \Request::session()->flash("error", "请输入名称");
            return back();
        }
        $role->name = $input['name'];
        $role->save();
        if(isset($input['permission'])){
            $data = [];
            foreach($input['permission'] as $key => $value){
                if($value == 'on'){
                    $data[] = $key;
                }
            }
            $role->permission()->attach($data);
        }
        $role->save();
        \Request::session()->flash("success", '操作成功');
        return back();

    }
}

