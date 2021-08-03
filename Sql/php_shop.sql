/*
 Navicat Premium Data Transfer

 Source Server         : VM-CentOS
 Source Server Type    : MySQL
 Source Server Version : 50728
 Source Host           : localhost:3306
 Source Schema         : php_shop

 Target Server Type    : MySQL
 Target Server Version : 50728
 File Encoding         : 65001

 Date: 10/06/2021 17:54:21
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '管理员账号',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '管理员密码',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '管理员名字',
  `tel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '管理员电话',
  `note` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '管理员备注',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES (1, 'admin', '123456', 'zs1', '133123456789', '超级管理员');
INSERT INTO `admin` VALUES (2, 'admin22', '123456', 'sss', NULL, NULL);

-- ----------------------------
-- Table structure for car
-- ----------------------------
DROP TABLE IF EXISTS `car`;
CREATE TABLE `car`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '购物车ID',
  `cover` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品封面',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品名称',
  `buynum` int(11) NOT NULL COMMENT '购买数量',
  `price` decimal(10, 2) NULL DEFAULT NULL COMMENT '商品单价',
  `specs` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '默认' COMMENT '商品规格',
  `good_id` int(11) NULL DEFAULT NULL COMMENT '商品id',
  `user_id` int(11) NULL DEFAULT NULL COMMENT '购物车所属者id',
  `status` tinyint(4) NULL DEFAULT 0 COMMENT '购物车状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 42 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of car
-- ----------------------------
INSERT INTO `car` VALUES (29, 'http://10.0.0.100/Shop/Web/pay/uploads/home_img7.jpg', '台灯', 3, 22.00, '默认', 36, 1, 1);
INSERT INTO `car` VALUES (30, 'http://10.0.0.100/Shop/Web/pay/uploads/home_img7.jpg', '台灯', 1, 22.00, '默认', 36, 1, 1);
INSERT INTO `car` VALUES (32, 'http://10.0.0.100/Shop/Web/pay/uploads/home_img1.png', '枕头2233', 1, 33.00, '默认', 38, 1, 1);
INSERT INTO `car` VALUES (33, 'http://10.0.0.100/Shop/Web/pay/uploads/home_img1.png', '枕头12', 1, 11.00, '', 30, 1, 1);
INSERT INTO `car` VALUES (34, 'http://10.0.0.100/Shop/Web/pay/uploads/home_img1.png', '枕头1232', 1, 11.00, '', 32, 1, 1);
INSERT INTO `car` VALUES (38, 'http://10.0.0.100:9501/pay/uploads/home_img10.jpg', '浴球', 1, 12.00, '默认', 40, 1, 1);
INSERT INTO `car` VALUES (39, 'http://10.0.0.100/Shop/Web/pay/uploads/home_img1.png', '枕头123222', 1, 11.00, '默认', 33, 1, 1);
INSERT INTO `car` VALUES (41, 'http://10.0.0.100:9501/pay/uploads/home_img10.jpg', '浴球', 1, 12.00, '默认', 40, 1, 1);

-- ----------------------------
-- Table structure for goods
-- ----------------------------
DROP TABLE IF EXISTS `goods`;
CREATE TABLE `goods`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品名称',
  `type` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '商品类型',
  `type_id` int(11) NULL DEFAULT 0 COMMENT '类型id',
  `num` int(11) NULL DEFAULT 0 COMMENT '商品数量',
  `price` float(10, 2) NOT NULL COMMENT '商品价格',
  `specs` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '默认' COMMENT '商品规格',
  `cover` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品封面图',
  `detail` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品描述',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 41 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of goods
-- ----------------------------
INSERT INTO `goods` VALUES (22, '后现代轻奢吊灯', '灯具', 1, 108, 122.00, '默认', 'http://10.0.0.100/Shop/Web/pay/uploads/1-15Ma3555-44X.jpg', '后现代轻奢吊灯全铜水晶客厅灯饰北欧简约餐厅卧室灯复古奢华灯具');
INSERT INTO `goods` VALUES (25, '台灯23', '灯具', 1, 123, 23.00, '默认', 'http://10.0.0.100/Shop/Web/pay/uploads/home_img7.jpg', '台灯');
INSERT INTO `goods` VALUES (26, '台灯2322', '灯具', 1, 123, 23.00, '默认', 'http://10.0.0.100/Shop/Web/pay/uploads/home_img7.jpg', '台灯');
INSERT INTO `goods` VALUES (27, '被子', '家纺', 2, 109, 22.00, '默认', 'http://10.0.0.100/Shop/Web/pay/uploads/home_img12.jpg', 'hhhhhh');
INSERT INTO `goods` VALUES (33, '枕头123222', '家纺', 2, 109, 11.00, '默认', 'http://10.0.0.100/Shop/Web/pay/uploads/home_img1.png', '111');
INSERT INTO `goods` VALUES (36, '台灯', '灯具', 1, 117, 22.00, '默认', 'http://10.0.0.100/Shop/Web/pay/uploads/home_img7.jpg', '台灯');
INSERT INTO `goods` VALUES (37, '灯', '灯具', 1, 113, 123.00, '默认', 'http://10.0.0.100/Shop/Web/pay/uploads/goods_img4.jpg', '11111111111111111111定位器');
INSERT INTO `goods` VALUES (38, '枕头2233', '家纺', 2, 127, 33.00, '默认', 'http://10.0.0.100/Shop/Web/pay/uploads/home_img1.png', '12333');
INSERT INTO `goods` VALUES (40, '浴球', '家纺', 2, 121, 12.00, '默认', 'http://10.0.0.100:9501/pay/uploads/home_img10.jpg', '好11');

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `order_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '订单编号',
  `goodItem` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '订单商品',
  `bill` float(10, 2) NOT NULL COMMENT '订单金额',
  `num` int(11) NOT NULL COMMENT '订单商品数量',
  `buyername` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '收货人姓名',
  `buyeraddress` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '收货人地址',
  `buyertel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '收货人电话',
  `notes` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单备注',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '订单状态',
  `user_id` int(11) UNSIGNED NOT NULL COMMENT '购买者ID',
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '下单时间',
  PRIMARY KEY (`id`, `order_id`) USING BTREE,
  UNIQUE INDEX `order_id`(`order_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of orders
-- ----------------------------
INSERT INTO `orders` VALUES (21, '2021060751579849', 'a:1:{i:0;s:2:\"29\";}', 66.00, 3, '张', '福建省万科', '13312345678', NULL, 3, 1, '2021-06-07 17:37:25');
INSERT INTO `orders` VALUES (22, '2021060748979952', 'a:1:{i:0;s:2:\"30\";}', 22.00, 1, '张', '福建省厦门市湖里区万科', '13312345678', NULL, 1, 1, '2021-06-07 17:34:46');
INSERT INTO `orders` VALUES (24, '2021060910097485', 'a:1:{i:0;s:2:\"33\";}', 11.00, 1, '张', '福建省万科', '13312345678', NULL, 1, 1, '2021-06-09 16:37:43');
INSERT INTO `orders` VALUES (25, '2021060950505610', 'a:1:{i:0;s:2:\"32\";}', 33.00, 1, '张', '福建省厦门市湖里区万科', '13312345678', NULL, 1, 1, '2021-06-09 16:40:41');
INSERT INTO `orders` VALUES (26, '2021060953971009', 'a:1:{i:0;s:2:\"34\";}', 11.00, 1, '张', '福建省厦门市湖里区万科', '13312345678', NULL, 3, 1, '2021-06-09 16:41:16');
INSERT INTO `orders` VALUES (27, '2021061010149995', 'a:1:{i:0;s:2:\"38\";}', 12.00, 1, '张', '福建省厦门市湖里区万科', '13312345678', NULL, 1, 1, '2021-06-10 17:04:50');
INSERT INTO `orders` VALUES (28, '2021061050499899', 'a:2:{i:0;s:2:\"39\";i:1;s:2:\"41\";}', 23.00, 2, '张', '福建省厦门市湖里区万科', '13312345678', NULL, 1, 1, '2021-06-10 17:09:13');

-- ----------------------------
-- Table structure for type
-- ----------------------------
DROP TABLE IF EXISTS `type`;
CREATE TABLE `type`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品类型ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品类型名称',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of type
-- ----------------------------
INSERT INTO `type` VALUES (1, '灯具');
INSERT INTO `type` VALUES (2, '家纺');
INSERT INTO `type` VALUES (3, '111');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户账号',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户密码',
  `nickname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '用户昵称',
  `sex` char(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '男' COMMENT '用户性别',
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收货地址',
  `tel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收货电话',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '收货人姓名',
  `money` float NULL DEFAULT 0 COMMENT '账户余额',
  `status` char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '正常' COMMENT '账号状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, '123123', '123123', 'jack', ' 男', '福建省厦门市湖里区万科', '13312345678', '张', 733, '正常');
INSERT INTO `user` VALUES (2, '123456', '123456', 'tom', '男', '福建省厦门市', '133123456780', '李', 1000, '正常');

SET FOREIGN_KEY_CHECKS = 1;
