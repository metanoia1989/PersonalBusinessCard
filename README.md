# 名片小程序开发

架构说明：      
前端框架：uniapp
后台框架：vue-element-admin     
后端：mixphp 待定  后续来人了也方便维护 之前也弄过了 减少开发成本      

就一个页面，先抄小瓶云个人名片，然后再做后端处理，
设置我可以直接写接口，然后后台管理也用uniapp写H5。  


# 小程序端说明
主题颜色 `#a83031`		    


几个技术点，保存到手机需要绘制海报功能，绘制海报的话，就用自定义名片的形式，不用这么详细的展示。    
用了 
使用 [limeui-painter](https://ext.dcloud.net.cn/plugin?id=2389) 来绘制的，不过宽度单位啥的都是写死的，  
如果想要自适应的，就需要用js来获取屏幕宽度。       
    
# 后台开发
使用 `vue-admin-template` 简化版的，可以自己从零开始构建，然后需要啥组件可以直接从 vue-element-admin 里直接拿就行。      

名片小程序需要管理的数据分析说明：      
个人信息管理
* 头像 
* 姓名
* 职位
* 描述 多段描述文字
* 电话、邮箱、单位、网址、详细地址  
* 地址经纬度  GCJ02 火星坐标       

样式定义管理    
目前就是主题颜色，能够切换主题色就可以      

需要移植的有个人信息页面 用来修改账号密码的。            
然后给个预览页面，因为 uniapp 本质也是vue，我直接拿过来就可以，不需要那些交互，只要样式就可以。     
然后就是各个配置项的修改了，固定的信息写死就行，选项都是固定的，左侧编辑，右侧预览，完美。  

# 后端开发
相关数据表：用户表【用来登录】、个人信息表【一个信息一个字段】
样式也放到
```sql
--- 用户表 
CREATE TABLE IF NOT EXISTS `yang_user` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL COMMENT '用户名',
    `mobile` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '手机号',
    `password` VARCHAR(255) NOT NULL COMMENT '密码',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at` timestamp NULL DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`),
    UNIQUE INDEX `username`(`username`)
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
COMMENT='用户登录表';

CREATE TABLE IF NOT EXISTS `yang_profile` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL COMMENT '关联的用户ID',
    `type` VARCHAR(255) NOT NULL COMMENT '信息分类',
    `key`  VARCHAR(255) NOT NULL COMMENT '键 用来取的',
    `name`  VARCHAR(255) NOT NULL DEFAULT '' COMMENT '信息名称',
    `value`  TEXT COMMENT '内容',
    PRIMARY KEY (`id`),
    INDEX `user_id` (`user_id`),
    UNIQUE INDEX `unique_info` (`user_id`, `type`, `key`) 
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
COMMENT='个人信息表';
```