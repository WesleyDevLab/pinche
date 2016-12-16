<?php
namespace App\Controller\Admin;
use Service\Mail;
use Service\Log;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as DB;
// 图片上传
use Service\Upload;
/**
* 用户管理管理
*/

class UserController extends Base{
  public function index(){
    // 获取数据
  	$data = M('users') -> get() -> toArray();
    // 渲染模板
    $this -> display('Admin.User.lists',['data'=>$data,'one'=>$one]);
  }
  public function add(){
    // 获取数据
  	$data = M('users') -> get() -> toArray();
    // 渲染模板
  	$this -> display('Admin.User.add',['data'=>$data]);
  }
  public function insert(){
    // 实例化文件上传
    $upload = new upload();
    // 获取模型
    $users = M('users');
    // 判断是否上传了用户头像
    if(count($_FILES) > 0){
      // 用户头像
      $users -> header_url = $upload -> upload_one($_FILES['header_url']);
    }else{
      // 清除用户头像的数据
      unset($_POST['header_url']);
    }
    // 用户名
    $users -> username = $_POST['username'];
    // 用户密码
    $users -> password = md5($_POST['password']);
    // 用户邮箱
    $users -> email = $_POST['email'];
    // 用户手机号码
    $users -> phone = $_POST['phone'];
    // 用户真实姓名
  	$users -> truename = $_POST['truename'];
    // 用户身份证号码
  	$users -> number = $_POST['number'];
    // 用户余额
    $users -> money = 0;
    // 用户状态:0=司机(未审核),1=司机(已审核)2=司机(已冻结),3=正常用户(乘客),
    $users -> status = $_POST['status'];
  	// 执行添加
  	if( $users -> save() ){
  		redirect('/Admin/User/add.html?status=success&message=添加成功&description=用户添加成功');
  	}else{
  		redirect('/Admin/User/add.html?status=danger&message=添加失败&description=用户添加失败');
  	}
  }
  public function edit(){
    // 获取数据
  	$data = M('users') -> find($_GET['id']) -> toArray();

    // 渲染模板
  	$this -> display('Admin.User.edit',['data'=>$data]);
  }
  public function save(){
    // 加密密码
    if(!empty($_POST['password'])){
      $_POST['password'] = md5($_POST['password']);
    }else{
      unset($_POST['password']);
    }
    // 实例化文件上传
    $upload = new upload();
    // 判断是否上传了用户头像
    if(count($_FILES) > 0){
      // 用户头像
      $_POST['header_url'] = $upload -> upload_one($_FILES['header_url']);
    }else{
      // 清除用户头像的数据
      unset($_POST['header_url']);
    }
  	// 执行修改
  	if( M('users') -> where(['id'=>$_GET['id']]) -> update($_POST) ){
  		redirect('/Admin/User/edit.html?status=success&message=修改成功&description=用户修改成功&id='.$_GET['id']);
  	}else{
  		redirect('/Admin/User/edit.html?status=danger&message=修改失败&description=用户修改失败&id='.$_GET['id']);
  	}
  }
  public function delete(){
  	if( M('users') -> where(['id'=>$_GET['id']]) -> delete() ){
  		redirect('/Admin/User/lists.html?status=success&message=删除成功&description=用户删除成功');
  	}
  }
}