<?php
namespace app\photo\controller;
use think\Session;
use think\Db;
use think\Controller;
use think\Request;
use think\Image;


class photo extends \think\Controller
{

    // 文件上传表单
    public function index()
    {
        return $this->fetch();
    }

    //图片压缩
    public function photo(){
         // 获取表单上传文件
       
    }
    // 图片上传处理
    public function picture(Request $request)
    {
        // 获取表单上传文件
        $file = $request->file('image');
        if (true !== $this->validate(['image' => $file], ['image' => 'require|image'])) {
            $this->error('请选择图像文件');
        } else {
            // 读取图片
            $image = Image::open($file);
            // 图片处理
            switch ($request->param('type')) {
                case 1: // 图片裁剪
                    $image->crop(300, 300);
                    break;
                case 2: // 缩略图
                    $image->thumb(767,237, Image::THUMB_CENTER);
                    break;
                case 3: // 垂直翻转
                    $image->flip();
                    break;
                case 4: // 水平翻转
                    $image->flip(Image::FLIP_Y);
                    break;
                case 5: // 图片旋转
                    $image->rotate();
                    break;
                case 6: // 图片水印
                    $image->water('./logo.png', Image::WATER_NORTHWEST, 50);
                    break;
                case 7: // 文字水印
                    $image->text('ThinkPHP', VENDOR_PATH . 'topthink/think-captcha/assets/ttfs/1.ttf', 20, '#ffffff');
                    break;
            }
            // 保存图片（以当前时间戳）
            $saveName = $request->time() . '.png';
            $image->save(ROOT_PATH . 'public/uploads/' . $saveName);
            $this->success('图片处理完毕...', '/uploads/' . $saveName, 1);
        }
    }

}