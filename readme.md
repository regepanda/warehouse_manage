{}中是要求传过来的参数。  
没有特殊说明的GET接口都可以按照后面给出的统一参数格式来查询/操作    
   
   
## 公共接口，直接可以访问，无需权限    
__________________________________________________________________________________________
    查看公告板:GET /api_getBillboard
    创建用户接口:POST /api_createUser
        |-username
        |-password
        |-phone
        |-nickname
        |-sex
        |-birthday
    重置密码接口:GET /api_resetPassword
        |-username
        |—phone （两者任意选择一个)
    验证手机接口:GET /api_verifyPhone
        |-phone
    登录接口:GET    /api_login
        |-username
        |-password
    ###登录可能用OAuth或者直接用cookie伪造session，待定
    登出接口：GET /api_logout
    ###取决于登录设计，待定

## 用户相关接口，  需要权限认证后访问,否则返回错误：  


    获取个人信息的接口:GET /api_getUserInfo
    修改个人信息接口:POST /api_updateUserInfo
        |-username
        |-phone
        |-nickname
        |-sex
        |-birthday

    ————————————————————————————————————

    获取真实信息接口:GET  /api_getTrueInfo
    上传真实信息接口:POST /api_addTrueInfo
        |-name
        |-intro
        |-ic_id
    删除真实信息接口:GET  /api_delTrueInfo
        |-id

    ————————————————————————————————————

    获取账户信息接口:GET    /api_getAccount
    账户绑定接口:POST     /api_addAccount
        |-class
        |-key
    删除账户接口:GET      /api_delAccount
        |-id


    ————————————————————————————————————

    获取房屋信息的接口:GET   /api_getHouse
    上传房屋信息接口:POST   /api_addHouse
        |-area(面积)
        |-address
    修改房屋信息:POST     /api_updateHouse
    删除房屋信息:GET      /api_delHouse
        |-id


    ————————————————————————————————————

    获取车辆信息的接口:GET /api_getCar
    上传车辆信息接口:POST /api_addCar
        |-name
        |-brand
        |-color
        |-model(品牌)
        |-insurance_start(保险开始时间)
        |-insurance_end(保险结束时间)
        |-plate_id(牌照)
    删除车辆信息接口:GET  /api_delCar
    修改车辆信息接口:POST  /api_updateCar
        |-name
        |-brand
        |-color
        |-model(品牌)
        |-insurance_start(保险开始时间)
        |-insurance_end(保险结束时间)
        |-plate_id(牌照)

    ______________________________

    获取请求:GET    /api_getRequest
    提交请求：POST   /api_addRequest
        |-class
        |-user_intro
    修改请求: POST  /api_updateRequest
        |-class
        |-user_intro
        |-status
    删除请求:GET    /api_delRequest


    _____________________________


    获取缴费单:GET  /api_getTax
    缴费请求:POST   /api_addTax
    |-id

    ——————————————————————————————

    获取支付单:GET       /api_getPayment
    支付页面（传回页面,非json,需要指定payment_id）GET  /phone_payPage/{payment_id}

    ————————————————————————————

    获取消息箱消息:GET     /api_getMessage
    标记消息为已读:GET     /api_markReadMessage
    |-id

__________________________________________________________________________________________

## 响应方式

json数据  
|-status 是否成功true/false  
|-message 描述  
|-data 需要的数据 （如果需要返回数据，比如像查询）  
|-totalCount 符合条件的总数（主要用在分页上，如果安卓端不需要可以去除）  
_______________________________________________________________________
## 查询请求方式
/*
 * POST Array/Get Array
 * |-start  从哪一条起始（默认0）
 * |-num    需要多少条（默认数按照系统配置）
 * |-class  类别（如果该数据有类别字段的话，请填入相关数字，默认空）
 * |-sort   排序（如果需要排序，输入排序的字段，默认按照时间来排序）
 * |-search 搜索关键字（按照一个固定的关键字来作为条件（无法选择使用那一个字段来筛选，往往是后台确定的，比如关键字查找用户，筛选字段就是用户名，默认空））
 * |-desc   是否逆转排序即倒序(true倒序，false正序，默认是正序)
 * |-id     按照id获取一条记录（指定一个固定id，默认空）
 * |*/
 这些条件都是叠加的（and）关系，比如，你需要一条id=1，class=2，sort=user_id,desc=true  
 作为筛选条件的用户数据  
那么json应该是  
{id:1, class:2, sort:"user_id", desc:true}

__________________________________________________________________________________________
