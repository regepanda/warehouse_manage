/**
 * Created by zc on 2016/5/8.
 */


/**
 * 商品类控制器
 */

$admin_commodityController.controller("admin_manage_goods",function($scope,$location,$http){



    $scope.goodsStatus = [{
        id: 0,
        name: "库外"

    }, {
        id: 1,
        name: "库内"
    },
        {
        id:2,
        name:"冻结"
    }];


    /**
     * 获取货物信息
     *
     *  访问路由:/goods_manage_sGoods  GET   //货物信息
     *          /goods_manage_sGoodsArea GET  //货物区域信息
     */
    $scope.getGoods = function()
    {
        $scope.selectPage.getDataUrl="/goods_manage_sGoods";
        $scope.selectPage.getData();

        //货物区域信息
        $http.get("/goods_manage_sGoodsArea").success(function(response) {
            if(response.status == true)
            {
               $scope.goodsArea = response.data;
            }
            else
            {
                $scope.goodsArea = null;
            }
        });
    };



    /**
     * 修改货物信息
     *
     * 发送数据
     * |-goods_id
     * |-goods_status
     * |-goods_rfid
     * |-goods_two_dimension
     * |-goods_bar_code
     * |-goods_area
     *
     *  访问路由：/goods_manage_uGoods  POST
     */
    $scope.updateGoods = function($goodsId)
    {
        var $goods_id = $goodsId;
        var $goods_status = $('#'+$goods_id+'_status').val();
        var $goods_rfid = $('#'+$goods_id+'_rfid').val();
        var $goods_two_dimension = $('#'+$goods_id+'_two_dimension').val();
        var $goods_bar_code = $('#'+$goods_id+'_bar_code').val();
        var $goods_area = $('#'+$goods_id+'_area').val();
        var $goods_commodity = $('#'+$goods_id+'_commodity').val();
        var goodsData = {goods_bar_code:$goods_bar_code,goods_id:$goods_id,goods_status:$goods_status,goods_rfid:$goods_rfid,goods_two_dimension:$goods_two_dimension
        ,goods_area:$goods_area,goods_commodity:$goods_commodity};
        $http.post("/goods_manage_uGoods",goodsData).success(function(response){
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getGoods();
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });

    };


    /**
     * 添加货物信息
     *
     * 发送数据
     * |-goods_rfid
     * |-goods_tow_dimension
     * |-goods_area
     * |-goods_commodity
     * |-goods_bar_code
     * |-goods_status
     *
     * 访问路由：/goods_manage_aGoods  POST
     */
    $scope.addGoods = function()
    {

        var goodsData = {goods_bar_code:$scope.goods_bar_code,goods_rfid:$scope.goods_rfid,goods_two_dimension:$scope.goods_two_dimension,goods_area:$scope.goods_area,goods_commodity:$scope.goods_commodity};
        $http.post("/goods_manage_aGoods",goodsData).success(function(response){
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getGoods();
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });
    };


    /**
     * 删除货物
     *
     * 发送数据
     * |-goods_id
     *
     * 访问路由：/goods_manage_dGoods  GET
     */
    $scope.deleteGoods = function($goodsId)
    {
        var limit = {goods_id:$goodsId};
        var url = $scope.buildUrlParam(limit,"/goods_manage_dGoods");
        $http.get(url).success(function(response) {
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getGoods();
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });

    };


    $scope.control = function($deviceId)
    {

    };


    $scope.getGoods();




});