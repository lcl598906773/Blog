/*
Navicat MySQL Data Transfer

Source Server         : 本地数据库
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : lcl_blog

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2018-01-17 09:34:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for blog_article
-- ----------------------------
DROP TABLE IF EXISTS `blog_article`;
CREATE TABLE `blog_article` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(600) DEFAULT NULL,
  `content` mediumtext NOT NULL,
  `addtime` int(12) NOT NULL,
  `replycount` int(12) NOT NULL DEFAULT '0',
  `icon` varchar(255) DEFAULT 'public/index/images/s.jpg',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=160 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_article
-- ----------------------------

-- ----------------------------
-- Table structure for blog_comment
-- ----------------------------
DROP TABLE IF EXISTS `blog_comment`;
CREATE TABLE `blog_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comments` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_comment
-- ----------------------------

-- ----------------------------
-- Table structure for blog_grallery
-- ----------------------------
DROP TABLE IF EXISTS `blog_grallery`;
CREATE TABLE `blog_grallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `master_img` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_grallery
-- ----------------------------
INSERT INTO `blog_grallery` VALUES ('6', '0', '/uploads/20180115/87b57b6666e5579de82f95621a0b97d0.jpg', 'We Are Happy!', '');
INSERT INTO `blog_grallery` VALUES ('7', '0', '/uploads/20180115/c923bfab9a9bd1037224bbd96924b1f6.jpg', 'Our friendship will last!', '');
INSERT INTO `blog_grallery` VALUES ('5', '0', '/uploads/20180115/96de67421c285b0d5c021bed16d082d1.jpg', 'Life is my college!', '');
INSERT INTO `blog_grallery` VALUES ('17', '7', '/uploads/20180116/c2852a24d3f0726b8b64d26e63fba2d7.jpg', 'qweqweq', '');
INSERT INTO `blog_grallery` VALUES ('18', '5', '/uploads/20180116/c257d8e3faa70d12df2c117f0abf4ff2.jpg', 'aasdas', '');
INSERT INTO `blog_grallery` VALUES ('16', '6', '/uploads/20180116/009a9abd4d927f8b28011ef404155a3c.jpg', '', '');

-- ----------------------------
-- Table structure for blog_reply
-- ----------------------------
DROP TABLE IF EXISTS `blog_reply`;
CREATE TABLE `blog_reply` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `authorid` int(10) NOT NULL,
  `content` mediumtext NOT NULL,
  `replytime` int(12) NOT NULL,
  `tid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=223 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_reply
-- ----------------------------

-- ----------------------------
-- Table structure for blog_user
-- ----------------------------
DROP TABLE IF EXISTS `blog_user`;
CREATE TABLE `blog_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(16) NOT NULL,
  `pwd` char(32) NOT NULL,
  `regtime` int(12) NOT NULL,
  `regip` int(12) NOT NULL,
  `icon` varchar(255) DEFAULT 'public/images/y.jpg',
  `email` char(30) NOT NULL,
  `weibo` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `lasttime` int(12) NOT NULL,
  `level` tinyint(2) NOT NULL DEFAULT '0',
  `intro` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_user
-- ----------------------------
INSERT INTO `blog_user` VALUES ('1', 'lcl', 'e10adc3949ba59abbe56e057f20f883e', '1492759932', '2130706433', 'public/images/my.jpg', '598906773@qq.com', 'http://weibo.com/1782702825/profile?topnav=1&wvr=6', '17610820903', '1496999755', '1', '你不必去讨好所有人，正如不必铭记所有“昨天”;时光如雨，我们都是在雨中行走的人，找到属于自己的伞，建造小天地，朝前走，一直走到风停雨住，美好晴天，一切都会过去。');
