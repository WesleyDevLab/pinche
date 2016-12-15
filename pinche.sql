/*
Navicat MariaDB Data Transfer

Source Server         : localhost_3306
Source Server Version : 100113
Source Host           : localhost:3306
Source Database       : pinche

Target Server Type    : MariaDB
Target Server Version : 100113
File Encoding         : 65001

Date: 2016-12-15 15:56:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '后台用户id',
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `permissions` varchar(255) DEFAULT NULL COMMENT '权限分配(每个操作的技能id)权限为-1则是超级管理员以逗号分割',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', 'itxiao6', 'a46da1ad6aac4605b22621068816e21c', '-1');

-- ----------------------------
-- Table structure for cart
-- ----------------------------
DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '车辆id',
  `line` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `license` varchar(255) DEFAULT NULL COMMENT '行驶证',
  `status` int(11) DEFAULT '0' COMMENT '状态:0=未审核,1=正常,2=行驶中,3=返程,4=不接单,-1冻结',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cart
-- ----------------------------

-- ----------------------------
-- Table structure for code
-- ----------------------------
DROP TABLE IF EXISTS `code`;
CREATE TABLE `code` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '数据id',
  `type` int(11) DEFAULT NULL COMMENT '验证码类型:1=邮箱注册验证码,2=手机注册验证码',
  `code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of code
-- ----------------------------

-- ----------------------------
-- Table structure for lines
-- ----------------------------
DROP TABLE IF EXISTS `lines`;
CREATE TABLE `lines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start` int(11) DEFAULT NULL COMMENT '出发地id',
  `end` int(11) DEFAULT NULL COMMENT '目的地',
  `status` int(11) DEFAULT '0' COMMENT '路线状态:0=审核中,1=正常运营,2=已经停运',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lines
-- ----------------------------

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单id',
  `line` int(11) NOT NULL COMMENT '路线',
  `uid` int(11) NOT NULL COMMENT '司机id',
  `num` int(11) NOT NULL COMMENT '乘车人数',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '订单状态:0=订单生成,1=已付款,2=已上车,3=已到达,4=已评价,',
  `passenger` varchar(255) NOT NULL COMMENT '乘客信息(姓名:xxx,手机号:xxxxxxxxxxx)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of order
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` varchar(50) DEFAULT NULL COMMENT '用户名',
  `password` varchar(50) DEFAULT NULL COMMENT '用户密码',
  `phone` varchar(11) DEFAULT NULL COMMENT '手机号',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `name` varchar(50) DEFAULT NULL COMMENT '用户名称',
  `truename` varchar(50) DEFAULT NULL COMMENT '真实姓名',
  `number` varchar(30) DEFAULT NULL COMMENT '身份证号',
  `money` float DEFAULT NULL,
  `license` varchar(255) DEFAULT NULL COMMENT '驾照照片',
  `status` int(11) DEFAULT '2' COMMENT '用户状态:0=司机(未审核),1=司机(已审核)2=司机(已冻结),3=正常用户(乘客),',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
