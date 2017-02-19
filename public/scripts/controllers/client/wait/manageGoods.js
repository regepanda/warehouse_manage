/**
 * Created by zc on 2016/4/12.
 */

/**
 * 管理货物的出库入库控制器
 */
$client_operateController.controller("client_operate_manageGoods",function($scope,$http,$interval,Polling){

    /**
     *本地存贮被扫描到的货物信息
     */
    $scope.database = new Array();
    $scope.logData = new Array();
    $scope.polling = Polling;
    $scope.goods_id = "";
    $scope.goods_status = "";
    $scope.goodsData = {};
    $scope.i = 0;
    $scope.goodsAutoInStatus =false;
    $scope.goodsAutoOutStatus =false;

    $scope.sessionId = null;
    $scope.entranceId = null;
    $scope.operateCache = [];
    /**
     * 1.在本控制器把扫描到的货物信息存贮到$scope.database
     * 2.列出所有被扫描到的货物信息相应到页面上（goods_id和goods_status）
     * 传入路由
     * /client_operate_getGoodsDynamic   GET
     */
    $scope.dynamicGoods = function()
    {

        $scope.polling.ajax_get_data({},"/client_operate_getGoodsDynamic","get",1500,function(response)
        {
            var remoteData = response.data;
            for (var i = 0; i < remoteData.length; i++)
            {
                var repeat = false;
                for(var j = 0; j < $scope.database.length; j++ )
                {
                    if($scope.database[j].goods_id == remoteData[i].goods_id)
                    {
                        console.log("有重复的");
                        var repeat = true;
                        break;
                    }

                }
                if(repeat == false)
                {
                    $scope.database.push(remoteData[i]);
                }


            }
            $scope.$apply();
        },true);
};


    /**
     * 自动入库(1.点击自动出库按钮后，发送相应goods_id,goods_status=0,调函数把他们的状态改为库外状态（轮询）
     *          2.删除本控制器$scope.database中相应goods_status==0的元素)
     * 传入路由
     * /client_operate_autoUGoodsInStatus     POST
     */

    $scope.goodsAutoIn = function()
    {

        if($scope.goodsAutoInStatus == true)
        {
            $scope.goodsAutoInStatus =false;
            $interval.cancel($scope.intervalAutoIn);
        }
        else
        {
            $scope.goodsAutoInStatus =true;
            $scope.intervalAutoIn = $interval(function()
            {
                console.log("goodsAutoIn call");
                var data = {};
                var i = 0;
                var len = $scope.database.length;
                for(;i<len;)
                {
                    if($scope.database[i].goods_status == 0)
                    {
                        data = $scope.database[i];
                        $scope.database.splice(i, 1);
                        break;
                    }
                    i++;
                }
                if(i == len)
                {
                    $scope.goodsAutoInStatus =false;
                    $interval.cancel($scope.intervalAutoIn);
                    return
                }

                $http.post("/client_operate_uGoodsStatus",{goods_id:data.goods_id,goods_status:data.goods_status})
                    .success(
                    function(response)
                    {
                        if(response.status == true)
                        {

                            //成功时的显示的操作记录
                            var log = {"status":1,"time":new Date(),"id":response.data._id,"data":"入库成功"};
                            $scope.logData.push(log);
                            $scope.getCache(0);
                            return;
                        }
                        else
                        {
                            //失败时的显示的操作记录
                            var log = {"status":.0,"time":new Date(),"id":"","data":"入库失败"};
                            $scope.logData.push(log);

                            console.log("出现错误"+data.goods_id);
                            $scope.goodsAutoInStatus = false;
                            $interval.cancel($scope.intervalAutoIn);
                        }

                    });


            },1000);

        }

    };


    /**
     * 自动出库(1.点击自动出库按钮后，发送相应goods_id,goods_status=1,调函数把他们的状态改为库外状态（轮询）
     *          2.删除本控制器$scope.database中相应goods_status==1的元素)
     *          3.操作记录显示
     * 传入路由
     * /client_operate_autoUGoodsOutStatus   POST
     */

    $scope.goodsAutoOut = function()
    {
        if($scope.goodsAutoOutStatus == true)
        {
            $scope.goodsAutoOutStatus =false;
            $interval.cancel($scope.intervalAutoOut);
        }
        else
        {
            $scope.goodsAutoOutStatus =true;
            $scope.intervalAutoOut = $interval(function()
            {
                console.log("goodsAutoIn call");
                var data = {};
                var i = 0;
                var len = $scope.database.length;
                for(;i<len;)
                {
                    if($scope.database[i].goods_status == 1)
                    {
                        data = $scope.database[i];
                        $scope.database.splice(i, 1);
                        break;
                    }
                    i++;
                }
                if(i == len)
                {
                    $scope.goodsAutoOutStatus =false;
                    $interval.cancel($scope.intervalAutoOut);
                    return
                }

                $http.post("/client_operate_uGoodsStatus",{goods_id:data.goods_id,goods_status:data.goods_status})
                    .success(
                    function(response)
                    {
                        if(response.status == true)
                        {

                            //成功时的显示的操作记录
                            var log = {"status":1,"time":new Date(),"id":response.data._id,"data":"出库成功"};
                            $scope.logData.push(log);
                            $scope.getCache(0);
                            return
                        }
                        else
                        {
                            //失败时的显示的操作记录
                            var log = {"status":0,"time":new Date(),"id":"","data":"出库失败"};

                            $scope.logData.push(log);
                            console.log("出现错误"+data.goods_id);
                            $scope.goodsAutoOutStatus = false;
                            $interval.cancel($scope.intervalAutoOut);
                        }

                        //$scope.$apply();
                    });


            },1000);

        }

    };

    /**
     * 人工入库/出库(1.点击人工入库/出库按钮后，把所有的要出库的货物id传过来
     *               2.调函数把他们的状态改为库外状态
     *               3.删除本控制器$scope.database中相应id的元素)
     *               4.操作记录显示
     * 获取页面数据
     * |-goods_id
     * |-goods_status
     * 传入路由
     * /client_operate_uGoodsStatus   POST
     */

    $scope.uGoodsStatus = function($goods_id,$goods_status)
    {

        $scope.goodsData = {goods_id:$goods_id,goods_status:$goods_status};
        $http.post("/client_operate_uGoodsStatus",$scope.goodsData).success(
            function(response){
                if(response.status == true)
                {
                    //删除$scope.database中的数据
                   $scope.index = "";
                   $scope.index = $scope.getIndex($scope.database,$goods_id);
                   $scope.return = $scope.database.splice($scope.index,1);
                    __component_messageBar_setMessage(true,"处理货物成功" + response.message);
                    __component_messageBar_open();

                    //成功时的显示的操作记录
                    var data="";
                    if($goods_status == 0)
                    {
                        data = "入库成功";
                    }
                    else
                    {
                        data = "出库成功"
                    }
                    var log = {"status":1,"time":new Date(),"id":response.data._id,"data":data};
                    $scope.logData.push(log);
                    $scope.getCache(0);
                }
                else
                {
                    __component_messageBar_setMessage(false,"处理货物失败"+response.message);
                    __component_messageBar_open();
                    //成功时的显示的操作记录
                    var data="";
                    if($goods_status == 0)
                    {
                        data = "入库失败";
                    }
                    else
                    {
                        data = "出库失败"
                    }
                    var log = {"status":0,"time":new Date(),"id":response.data._id,"data":data};
                    $scope.logData.push(log);
                    $scope.getCache(0);
                }
            });
    };

    /**
     *
     * 动态的获取存储在session某个值
     *
     * @param key
     * @param recive
     * recive应该是对象，其data存储session值
     */
    $scope.getSessionVal = function(key,recive)
    {
        $http.get("/client_base_getSessionVal?key="+key).success(function(response)
        {
            if(response.status == true)
            {
                //alert(response.data);

                $scope[recive]=  response.data;

                console.log("收到回话值，对象"+recive+"="+$scope[recive]);
            }
            else
            {
                return null;
            }
        });
    }


    /**
     * 获取缓存
     * @param offset
     */
    $scope.getCache = function(offset)
    {
        $scope.showCache = false;
        if($scope.sessionId == undefined)
        {
            $scope.sessionId = undefined;
            $scope.getSessionVal("operatorSession.operatorSession_id", "sessionId");
            $scope.cacheInteraval = $interval(function(){
                //alert($scope.sessionId);
                //$scope.$apply();
                if($scope.sessionId != undefined)
                {
                    $http.get("/api_app_sOperatorCache?offset="+offset+"&session_id="+$scope.sessionId).success(function(response)
                    {
                        if(response.status == true)
                        {

                            console.log("获取缓存");
                            $scope.operateCache = response.data;
                            $scope.showCache=true;
                        }
                        else
                        {
                            console.log("获取到缓存失败");
                        }
                        $interval.cancel($scope.cacheInteraval);
                    });
                }
            },500);
        }
        else
        {
            $http.get("/api_app_sOperatorCache?offset="+offset+"&session_id="+$scope.sessionId).success(function(response)
            {
                if(response.status == true)
                {
                    console.log("获取缓存");
                    $scope.operateCache = response.data;
                    $scope.showCache=true;
                }
                else
                {
                    console.log("获取到缓存失败");
                }
                $interval.cancel($scope.cacheInteraval);
            });
        }





    };




    /**
     *根据goods_id返回相应的元素下标
     * @param $arr
     * @param $goods_id
     * @returns {number}
     */
    $scope.getIndex = function($arr,$goods_id)
    {
        for(var i= 0;i < $arr.length;i++)
        {
            var a;
           for(a in $arr[i])
           {
              if($arr[i][a] == $goods_id)
              {
                  return i;
              }
           }
            return -1;
        }
    };



    /**
     * 结束会话
     * 传入路由：
     * /client_wait_logoutSession GET
     */
    $scope.endSession = function()
    {

        $http.get("/client_wait_logoutSession").success(function(response) {
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.goWaitSession();    //关闭会话成功，回到等待会话界面
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });
    };


    /**
     *
     /**
     *  获取当前登录的操作员信息+头像
     *  接收数据
     * |-status
     * |-data={
     *  operator_username:操作用户名
     *  operator_name:操作员名
     *  operator_image:操作员头像id
     * }
     * |-message
     * 访问路由：/client_base_getOperator GET
     */
    $scope.getOperator = function()
    {
        $http.get("/client_base_getOperator").success(function(response) {
            if(response.status == true)
            {
                $scope.operator = response.data;
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });
    };

    /**
     * 提交缓存
     */
    $scope.commitCache = function()
    {
        $http.post("/api_app_commitOperatorCache",{session_id:$scope.sessionId}).success(
        function(response){
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getCache(0);
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
                $scope.getCache(0);
            }
        });
    }
    /**
     * 删除一个指定id的缓存
     * @param id
     */
    $scope.delCache = function(id)
    {
        $http.post("/api_app_delOperatorCache",{id:id,session_id:$scope.sessionId}).success(
            function(response)
            {
                if(response.status == true)
                {
                    __component_messageBar_setMessage(true,response.message);
                    __component_messageBar_open();
                    $scope.getCache(0);
                }
                else
                {
                    __component_messageBar_setMessage(false,response.message);
                    __component_messageBar_open();
                    $scope.getCache(0);
                }
            }
        )
    }

    /*
    *
    * */
    $scope.cancelGoods = function(goodsId)
    {
        var i = $scope.getIndex($scope.database,goodsId);
        $scope.database.splice(i,1);

    }


    $scope.getOperator();
    $scope.dynamicGoods();  //进页面自动刷新调用
    $scope.getSessionVal("entrance.entrance_id","entranceId" );
    //$scope.sessionId = $scope.getSessionVal("operatorSession.operatorSession_id");
    $scope.getCache(0);



});

