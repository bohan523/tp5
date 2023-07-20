<?php


namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Cache;
use think\Request;

class Test extends Controller
{
    public function index(Request $request)
    {
        return 123;
    }

    //遍历文件夹
    public function dg_dir($dir){
        if(!is_dir($dir)){
            return;
        }
        $arr = scandir($dir);
        foreach ($arr as $k =>$v){
            $item=$dir.'/'.$v;
            if(is_dir($v)&&$v != "."&&$v != ".."){
                echo "目录：".$v."<br>";
                $this->dg_dir($v);

            }else{
                echo "文件：".$v."<br>";
            }
        }
    }

    public function test(){
//        //文件缓存
//        $value = 'bohan';
//        $ret = Cache::set('name',$value,3600);
//        var_dump($ret);

//        $dir = "C:\Users\Administrator\Desktop\商品图";
//        $this->dg_dir($dir);
//        exit;

        // 切换到redis操作
        Cache::store('redis')->set('name','value');
        $name = Cache::get('name');
        var_dump($name);

    }


    //请求 签名
    public function login(){
        $ret = ['code'=>200,'msg'=>'cehngong'];
//        dd($data);
//        $data = request()->post();
        $data = request()->header();
//        dd($data['userid']);
//        dd($data);
//
       $data = Request::instance()->header();
//        returnJson($data);
        $str = '456';
        $str .= "name:".$data['name']."&timestamp:".$data['timestamp']."&456";
        $sign = sha1($str);
//        dd($sign);
//        returnJson();

        if($sign == $data['sign']){
            dd('验签成功');
        }else{
            dd('失败');
        }
    }

}