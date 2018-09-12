-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-08-19 18:35:11
-- 服务器版本： 10.1.14-MariaDB-1~precise

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `biss`
--

-- --------------------------------------------------------

--
-- 表的结构 `base`
--

CREATE TABLE `base` (
  `id` smallint(5) UNSIGNED NOT NULL COMMENT '自增主键',
  `name` char(20) NOT NULL COMMENT '基地名称',
  `trainee_num` smallint(5) UNSIGNED NOT NULL COMMENT '运动员数量'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `base`
--


-- --------------------------------------------------------

--
-- 表的结构 `coach`
--

CREATE TABLE `coach` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `name` char(6) NOT NULL DEFAULT '' COMMENT '教练员姓名',
  `password` binary(60) NOT NULL DEFAULT ' \0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0' COMMENT '密码',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '教练用户状态。\r\n0 - 正常；1 - 锁定；2 - 删除',
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `login_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '登录时间',
  `role_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '角色ID'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `coach`
--

-- --------------------------------------------------------

--
-- 表的结构 `evaluation`
--

CREATE TABLE `evaluation` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增主键',
  `trainee_id` int(10) UNSIGNED NOT NULL COMMENT '运动员ID',
  `create_date` date NOT NULL COMMENT '填表日期',
  `create_time` time NOT NULL COMMENT '填表时间',
  `self_rating` tinyint(3) UNSIGNED NOT NULL COMMENT '自我评价（7分制）',
  `desire` tinyint(3) UNSIGNED NOT NULL COMMENT '训练欲望（7分制）',
  `sleep` tinyint(3) UNSIGNED NOT NULL COMMENT '睡眠质量（7分制）',
  `appetite` tinyint(3) UNSIGNED NOT NULL COMMENT '食欲（7分制）',
  `rpe_before` tinyint(3) UNSIGNED NOT NULL COMMENT '训练前 RPE，疲劳指数',
  `rpe_after` tinyint(3) UNSIGNED NOT NULL COMMENT '训练后 RPE，疲劳指数',
  `hrv_before` tinyint(3) UNSIGNED NOT NULL COMMENT '训练前 HRV，心率变异性',
  `hrv_after` tinyint(3) UNSIGNED NOT NULL COMMENT '训练后 HRV，心率变异性',
  `hrv_cold` tinyint(3) UNSIGNED NOT NULL COMMENT '冷疗后 HRV',
  `omega_wave` tinyint(3) UNSIGNED NOT NULL COMMENT 'Omegawave测试（7分制）',
  `morning_pulse` tinyint(3) UNSIGNED NOT NULL COMMENT '晨脉',
  `attitude` tinyint(3) UNSIGNED NOT NULL COMMENT '训练态度',
  `quality` tinyint(3) UNSIGNED NOT NULL COMMENT '完成质量',
  `comment` text NOT NULL COMMENT '训练评价',
  `pain` text NOT NULL COMMENT '疼痛部位和疼痛等级'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `evaluation`
--

-- --------------------------------------------------------

--
-- 表的结构 `role`
--

CREATE TABLE `role` (
  `id` tinyint(3) UNSIGNED NOT NULL COMMENT '自增主键',
  `name` char(5) DEFAULT '' COMMENT '角色名称',
  `privilege` text COMMENT '权限数组。例如：\r\n[10,20]'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `role`
--

-- --------------------------------------------------------

--
-- 表的结构 `trainee`
--

CREATE TABLE `trainee` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增主键',
  `name` char(5) NOT NULL DEFAULT '' COMMENT '运动员姓名',
  `password` binary(120) NOT NULL DEFAULT ' \0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0' COMMENT '密码',
  `role_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '4' COMMENT '角色ID，关联 role.id',
  `gender` char(1) DEFAULT 'M' COMMENT '性别。'''' = 未设定，''M'' = 男，''F'' = 女',
  `dob` date NOT NULL DEFAULT '0000-00-00' COMMENT '出生日期',
  `height` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '身高',
  `weight` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '体重',
  `mobile` char(20) NOT NULL COMMENT '手机号码',
  `nationality` char(10) NOT NULL DEFAULT '' COMMENT '民族',
  `sport` char(10) NOT NULL DEFAULT '' COMMENT '运动项目',
  `team` char(10) NOT NULL DEFAULT '' COMMENT '运动队',
  `base_id` smallint(5) UNSIGNED NOT NULL,
  `base_name` char(10) NOT NULL DEFAULT '' COMMENT '训练基地',
  `dot` date NOT NULL DEFAULT '0000-00-00' COMMENT '进入专业队时间',
  `grade` char(10) NOT NULL DEFAULT '' COMMENT '运动等级',
  `last_train_date` date NOT NULL DEFAULT '0000-00-00' COMMENT '上次训练日期，如果等于当天日期，表示学员已入场训练',
  `last_status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上次训练状态，0 = 保留，1 = 训练已开始，2 = 训练已完成',
  `avatar` char(160) NOT NULL COMMENT '头像URL',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户状态，0 = 未审核；1 = 正常；2 = 锁定；3 = 删除；4：审核被拒绝',
  `native` char(10) NOT NULL COMMENT '籍贯',
  `day` tinyint(4) NOT NULL DEFAULT '0' COMMENT '运动员训练天数'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `trainee`
--


-- --------------------------------------------------------

--
-- 表的结构 `training`
--

CREATE TABLE `training` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '自增主键',
  `trainee_id` int(10) UNSIGNED NOT NULL COMMENT '运动员ID',
  `training_date` date NOT NULL COMMENT '训练日期',
  `training_time` time NOT NULL COMMENT '训练时间',
  `movement_id` int(10) UNSIGNED NOT NULL COMMENT '训练动作编号',
  `number_1` float DEFAULT NULL,
  `number_2` float DEFAULT NULL COMMENT '数字型字段2，如果用于保存小数，注意先乘以10的整数倍再保存',
  `number_3` float DEFAULT NULL COMMENT '数字型字段3，如果用于保存小数，注意先乘以10的整数倍再保存',
  `number_4` float DEFAULT NULL COMMENT '数字型字段4，如果用于保存小数，注意先乘以10的整数倍再保存',
  `number_5` float DEFAULT NULL COMMENT '数字型字段5，如果用于保存小数，注意先乘以10的整数倍再保存',
  `number_6` float DEFAULT NULL COMMENT '数字型字段6，如果用于保存小数，注意先乘以10的整数倍再保存',
  `number_7` float DEFAULT NULL COMMENT '数字型字段7，如果用于保存小数，注意先乘以10的整数倍再保存',
  `number_8` float DEFAULT NULL COMMENT '数字型字段8，如果用于保存小数，注意先乘以10的整数倍再保存',
  `string_1` varchar(20) DEFAULT NULL COMMENT '字符串字段1，最大20个字符（汉字）',
  `string_2` varchar(200) DEFAULT NULL COMMENT '字符串字符2，最大200个字符（汉字）',
  `group_id` int(11) NOT NULL COMMENT '分组编号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `training`
--


--
-- Indexes for dumped tables
--

--
-- Indexes for table `base`
--
ALTER TABLE `base`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coach`
--
ALTER TABLE `coach`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trainee`
--
ALTER TABLE `trainee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `training`
--
ALTER TABLE `training`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `base`
--
ALTER TABLE `base`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增主键', AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `coach`
--
ALTER TABLE `coach`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=1;
insert into coach values(null, '闫琪','$2y$10$mJOVUKdRmHkVOT6MdNcFIevnF8z0GEPBCBMeeBsqYzKsZbt1ZnDDq',0,now(),0,2);
--
-- AUTO_INCREMENT for table `evaluation`
--
ALTER TABLE `evaluation`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增主键', AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增主键', AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `trainee`
--
ALTER TABLE `trainee`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增主键', AUTO_INCREMENT=1000;
--
-- AUTO_INCREMENT for table `training`
--
ALTER TABLE `training`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增主键', AUTO_INCREMENT=1;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


DROP TABLE IF EXISTS `sport`;
CREATE TABLE `sport` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` CHAR(20) NOT NULL DEFAULT '',
    `trainee_num` INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `coach_sport`;
CREATE TABLE `coach_sport` (
    `sport_id`  INT UNSIGNED NOT NULL DEFAULT 0,
    `coach_id` INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY(`sport_id`, `coach_id`)
) ENGINE=MyISAM;

DROP TABLE IF EXISTS `coach_trainee`;
CREATE TABLE `coach_trainee` (
    `coach_id` INT UNSIGNED NOT NULL DEFAULT 0,
    `trainee_id` INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY(`coach_id`, `trainee_id`)
) ENGINE=MyISAM;

ALTER TABLE trainee ADD COLUMN sport_id INT UNSIGNED NOT NULL DEFAULT 0 AFTER nationality;

INSERT INTO sport(name) SELECT DISTINCT(sport) FROM trainee;
UPDATE trainee t, sport s SET t.sport_id = s.id WHERE t.sport = s.name;
