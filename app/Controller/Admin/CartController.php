<?php
namespace App\Controller\Admin;
use Service\Mail;
use Service\Log;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as DB;
// 图片上传
use Service\Upload;
/**
* 车辆管理管理
*/

class CartController extends Base{
  public function index(){
    // 获取数据
    $data = M('cart') -> get() -> toArray();
    // 获取路线信息
    $lines = M('lines') -> get() -> toArray();
    // 获取用户信息
    $users = M('users') -> get() -> toArray();
    // 获取地区信息
    $address = M('address') -> get() -> toArray();
    // 处理地区信息
    foreach ($lines as $key => $value) {
      foreach ($address as $v) {
        if($value['start']==$v['id']){
          $lines[$key]['start'] = $v['name'];
        }else if($value['end']==$v['id']){
          $lines[$key]['end'] = $v['name'];
        }
      }
    }
    // 处理路线数据
    foreach ($data as $key => $value){
      // 处理路线信息
      foreach ($lines as $v) {
        if($value['line']==$v['id']){
          $data[$key]['line'] = $v['start'].'-->'.$v['end'];
        }
      }
      // 处理用户信息
      foreach ($users as $u) {
        if($u['id']==$value['uid']){
          $data[$key]['uid'] = $u['phone'].'-->'.$u['truename'];
        }
      }
      // 处理状态信息
      // 状态:0=未审核,1=正常,2=行驶中,3=返程,4=不接单,-1冻结
      switch ($value['status']) {
        case '0':
          $data[$key]['status'] = "未审核";
          break;
        case '1':
          $data[$key]['status'] = "正常";
          break;
        case '2':
          $data[$key]['status'] = "行驶中";
          break;
        case '3':
          $data[$key]['status'] = "返程";
          break;
        case '4':
          $data[$key]['status'] = "不接单";
          break;
        case '-1':
          $data[$key]['status'] = "冻结";
          break;
        
        default:
          $data[$key]['status'] = "未审核";
          break;
      }
    }
    // 渲染模板
    $this -> display('Admin.Cart.lists',['data'=>$data]);
  }
  public function add(){
    // 获取地区数据
  	$address = M('address') -> get() -> toArray();
    // 获取路线信息
    $lines = M('lines') -> get() -> toArray();
    // 获取用户信息
    $users = M('users') -> get() -> toArray();
    // 处理路线和地址的关系
    foreach ($lines as $key => $value) {
      foreach ($address as $v) {
        if ($v['id']==$value['start']) {
          $lines[$key]['start_name'] = $v['name'];
        }else if($v['id']==$value['end']){
          $lines[$key]['end_name'] = $v['name'];
        }
      }
    }
    // 渲染模板
  	$this -> display('Admin.Cart.add',['address'=>$address,'lines'=>$lines,'users'=>$users]);
  }
  public function insert(){
    // 实例化文件上传
    $upload = new upload();
    // 获取模型
    $cart = M('cart');
    // 上传文件
  	$cart -> license = $upload -> upload_one($_FILES['license']);
    // 出发地
    $cart -> line = $_POST['line'];
    // 目的地
    $cart -> status = $_POST['status'];
    // 车辆拥有人
    $cart -> uid = $_POST['uid'];
    // 车辆状态
    $cart -> status = $_POST['status'];
  	// 执行添加
  	if( $cart -> save() ){
  		redirect('/Admin/Cart/add.html?status=success&message=添加成功&description=车辆添加成功');
  	}else{
  		redirect('/Admin/Cart/add.html?status=danger&message=添加失败&description=车辆添加失败');
  	}
  }
  public function edit(){
    // 获取要修改的数据
    $data = M('cart') -> find($_GET['id']) -> toArray();
    // 获取地区数据
    $address = M('address') -> get() -> toArray();
    // 获取路线信息
    $lines = M('lines') -> get() -> toArray();
    // 获取用户信息
    $users = M('users') -> get() -> toArray();
    // 处理路线和地址的关系
    foreach ($lines as $key => $value) {
      foreach ($address as $v) {
        if ($v['id']==$value['start']) {
          $lines[$key]['start_name'] = $v['name'];
        }else if($v['id']==$value['end']){
          $lines[$key]['end_name'] = $v['name'];
        }
      }
    }
    // 渲染模板
    $this -> display('Admin.Cart.edit',['address'=>$address,'lines'=>$lines,'users'=>$users,'data'=>$data]);
  }
  public function save(){
    // 实例化文件上传
    $upload = new upload();
    // 实例化车辆类
    $cart = M('cart');
    // 判断是否修改了行驶证图片
    if(count($_FILES) > 0){
      // 上传文件
      $_POST['license'] = $upload -> upload_one($_FILES['license']);
    }else{
      // 清除要修改的行驶证
      unset($_POST['license']);
    }
  	// 执行修改
  	if( $cart -> where(['id'=>$_GET['id']]) -> update($_POST) ){
  		redirect('/Admin/Cart/edit.html?status=success&message=修改成功&description=车辆修改成功&id='.$_GET['id']);
  	}else{
  		redirect('/Admin/Cart/edit.html?status=danger&message=修改失败&description=车辆修改失败&id='.$_GET['id']);
  	}
  }
  public function delete(){
  	if( M('cart') -> where(['id'=>$_GET['id']]) -> delete() ){
  		redirect('/Admin/Cart/lists.html?status=success&message=删除成功&description=车辆删除成功');
  	}
  }
}