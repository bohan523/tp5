<?php

namespace app\common;

class UserHelp
{
    public static function getInfo(){
        $userId = 1;
        $userInfo = [
            'name' => 'bohan',
            'age' => 18,
            'money' => 1000000,
            'iamge' => 'https://image.baidu.com/search/detail?ct=503316480&z=undefined&tn=baiduimagedetail&ipn=d&word=%E5%88%98%E5%BE%B7%E5%8D%8E&step_word=&ie=utf-8&in=&cl=2&lm=-1&st=undefined&hd=undefined&latest=undefined&copyright=undefined&cs=3966565920,1994981666&os=4177239927,3203283603&simid=3966565920,1994981666&pn=5&rn=1&di=168300&ln=1477&fr=&fmq=1641278160162_R&fm=&ic=undefined&s=undefined&se=&sme=&tab=0&width=undefined&height=undefined&face=undefined&is=0,0&istype=0&ist=&jit=&bdtype=0&spn=0&pi=0&gsm=0&objurl=https%3A%2F%2Fgimg2.baidu.com%2Fimage_search%2Fsrc%3Dhttp%253A%252F%252Fnimg.ws.126.net%252F%253Furl%253Dhttp%253A%252F%252Fdingyue.ws.126.net%252F2021%252F0127%252Fdaeb5f23j00qnln4l0010d000ci00iip.jpg%2526thumbnail%253D650x2147483647%2526quality%253D80%2526type%253Djpg%26refer%3Dhttp%253A%252F%252Fnimg.ws.126.net%26app%3D2002%26size%3Df9999%2C10000%26q%3Da80%26n%3D0%26g%3D0n%26fmt%3Djpeg%3Fsec%3D1643870158%26t%3D0a02ed819abbff07d69d1402a3a18d26&rpstart=0&rpnum=0&adpicid=0&nojc=undefined&dyTabStr=MCwzLDEsNCw1LDcsNiwyLDgsOQ%3D%3D'
        ];
        return $userInfo;
    }

}