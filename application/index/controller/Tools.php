<?php

namespace app\index\controller;

use think\Request;

class Tools
{
    /**
     * Notes:文件夹文件批量重命名
     * User: bohan
     * Date: 2022/1/15
     * Time: 14:12
     */
    public function changeNamePic(){

        $postData = request()->param();
        $dirname = $postData['path'];
        $name = $postData['name'];
        if (!is_dir($dirname)) {
            echo "{$dirname}不是一个有效的目录！";
            exit();
        }
        $handle = opendir($dirname);
        $i = 1;
        while (($fn = readdir($handle)) !== false) {
            if ($fn != '.' && $fn != '..') {
                echo "<br>将名为：" . $fn . "\n\r";
                $curDir = $dirname . '/' . $fn;
                $path = pathinfo($curDir);
                //改成你自己想要的新名字
                $nameFile = $name.$i.'_'.time().rand(100,999) . '.' . $path['extension'];
                $newname = $path['dirname'] . '/' . $nameFile;
                echo "替换成:" .$nameFile . "\r\n";
                rename($curDir, $newname);
                $i++;

            }
        }

    }


}