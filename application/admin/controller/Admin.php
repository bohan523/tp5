<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;

class Admin extends Controller
{
    public function index(){
        return $this->fetch();
    }

    /**
     * 获取菜单数据
     * @return \think\response\Json
     */
    public function getSystemInit(){
        $homeInfo = [
            'title' => '首页',
            'href'  => 'page/welcome-1.html?t=1',
        ];
        $logoInfo = [
            'title' => 'LAYUI MINI',
            'image' => 'images/logo.png',
        ];
        $menuInfo = $this->getMenuList();
        $systemInit = [
            'homeInfo' => $homeInfo,
            'logoInfo' => $logoInfo,
            'menuInfo' => $menuInfo,
        ];
        return json($systemInit);
    }

    // 获取菜单列表
    private function getMenuList(){
        $menuList = Db::name('system_menu')
            ->field('id,pid,title,icon,href,target')
            ->where('status', 1)
            ->order('sort', 'desc')
            ->select();
        $menuList = $this->buildMenuChild(0, $menuList);
        return $menuList;
    }

    //递归获取子菜单
    private function buildMenuChild($pid, $menuList){
        $treeList = [];
        foreach ($menuList as $v) {
            if ($pid == $v['pid']) {
                $node = $v;
                $child = $this->buildMenuChild($v['id'], $menuList);
                if (!empty($child)) {
                    $node['child'] = $child;
                }
                // todo 后续此处加上用户的权限判断
                $treeList[] = $node;
            }
        }
        return $treeList;
    }
}