-- ----------------------------
-- Table structure for `groups`
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`name`),
  UNIQUE KEY `name` (`name`)
);

-- ----------------------------
-- Table structure for `privileges`
-- ----------------------------
DROP TABLE IF EXISTS `privileges`;
CREATE TABLE `privileges` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `controller` varchar(100) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `group_id` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
);