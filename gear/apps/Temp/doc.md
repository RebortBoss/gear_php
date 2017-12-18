* Activity->sales:电信团购
* Activity->infoSub:电信团购提交
* 数据库:WeChat.activity_dianxin_group
~~~
/*
Navicat SQL Server Data Transfer

Source Server         : 美菱数据库
Source Server Version : 90000
Source Host           : sql.meiling.com:1433
Source Database       : WeChat
Source Schema         : dbo

Target Server Type    : SQL Server
Target Server Version : 90000
File Encoding         : 65001

Date: 2017-12-18 15:41:27
*/


-- ----------------------------
-- Table structure for activity_dianxin_group
-- ----------------------------
DROP TABLE [dbo].[activity_dianxin_group]
GO
CREATE TABLE [dbo].[activity_dianxin_group] (
[id_number] char(32) NOT NULL ,
[name] nvarchar(32) NOT NULL ,
[job_number] char(32) NULL ,
[part] nvarchar(255) NOT NULL ,
[contact] nvarchar(255) NOT NULL ,
[sub_state] varchar(255) NULL ,
[update_time] varchar(40) NULL ,
[sub_time] varchar(40) NULL
)


GO
IF ((SELECT COUNT(*) from fn_listextendedproperty('MS_Description',
'SCHEMA', N'dbo',
'TABLE', N'activity_dianxin_group',
'COLUMN', N'update_time')) > 0)
EXEC sp_updateextendedproperty @name = N'MS_Description', @value = N'状态码
0 未上报
1 已上报'
, @level0type = 'SCHEMA', @level0name = N'dbo'
, @level1type = 'TABLE', @level1name = N'activity_dianxin_group'
, @level2type = 'COLUMN', @level2name = N'update_time'
ELSE
EXEC sp_addextendedproperty @name = N'MS_Description', @value = N'状态码
0 未上报
1 已上报'
, @level0type = 'SCHEMA', @level0name = N'dbo'
, @level1type = 'TABLE', @level1name = N'activity_dianxin_group'
, @level2type = 'COLUMN', @level2name = N'update_time'
GO

-- ----------------------------
-- Indexes structure for table activity_dianxin_group
-- ----------------------------

-- ----------------------------
-- Primary Key structure for table activity_dianxin_group
-- ----------------------------
ALTER TABLE [dbo].[activity_dianxin_group] ADD PRIMARY KEY ([id_number])
GO

~~~