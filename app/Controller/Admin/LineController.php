<?php
namespace App\Controller\Admin;
use Service\Mail;
use Service\Log;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as DB;
/**
* 路线管理管理
*/

class LineController extends Base{
  public function index(){
    // 获取数据
    $data = M('lines') -> get() -> toArray();
    // 获取地区数据
  	$address = M('address') -> get() -> toArray();
    // 处理关联数据
    foreach ($data as $key => $value) {
      foreach ($address as $v) {
        if($value['start'] == $v['id']){
          $data[$key]['start'] = $v['name'];
        }else if($value['end'] == $v['id']){
          $data[$key]['end'] = $v['name'];
        }
      }
    }
    // 渲染模板
    $this -> display('Admin.Line.lists',['data'=>$data]);
  }
  public function add(){
    // 获取地区数据
  	$address = M('address') -> get() -> toArray();
    // 处理地区级别缩进
    foreach($address as $key => $value){
      $address[$key]['name'] = '|'.str_repeat('|----',substr_count($value['path'],',')).$value['name'];
    }
    // 渲染模板
  	$this -> display('Admin.Line.add',['address'=>$address]);
  }
  public function insert(){
    // 判断出发地和目的地是否想同
    if($_POST['start'] == $_POST['end']){
      redirect('/Admin/Line/add.html?status=danger&message=添加失败&description=出发地和目的地不能相同');
    }
  	// 获取模型
  	$lines = M('lines');
    // 出发地
    $lines -> start = $_POST['start'];
    // 目的地
    $lines -> end = $_POST['end'];
    // 路线状态:0=审核中,1=正常运营,2=已经停运
    $lines -> status = 1;
  	// 执行添加
  	if( $lines -> save() ){
  		redirect('/Admin/Line/add.html?status=success&message=添加成功&description=路线添加成功');
  	}else{
  		redirect('/Admin/Line/add.html?status=danger&message=添加失败&description=路线添加失败');
  	}
  }
  public function edit(){
    // 获取数据
  	$data = M('lines') -> find($_GET['id']) -> toArray();
    // 获取地区数据
    $address = M('address') -> get() -> toArray();
    // 处理地区级别缩进
    foreach($address as $key => $value){
      $address[$key]['name'] = '|'.str_repeat('|----',substr_count($value['path'],',')).$value['name'];
    }
    // 渲染模板
  	$this -> display('Admin.Line.edit',['data'=>$data,'address'=>$address]);
  }
  public function save(){
  	// 执行修改
  	if( M('lines') -> where(['id'=>$_GET['id']]) -> update($_POST) ){
  		redirect('/Admin/Line/edit.html?status=success&message=修改成功&description=路线修改成功&id='.$_GET['id']);
  	}else{
  		redirect('/Admin/Line/edit.html?status=danger&message=修改失败&description=路线修改失败&id='.$_GET['id']);
  	}
  }
  public function delete(){
  	if( M('users') -> where(['id'=>$_GET['id']]) -> delete() ){
  		redirect('/Admin/Line/lists.html?status=success&message=删除成功&description=路线删除成功');
  	}
  }
}