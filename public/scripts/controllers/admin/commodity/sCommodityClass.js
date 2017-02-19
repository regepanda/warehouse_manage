/**
 * Created by zc on 2016/4/27.
 */


/**
 * 商品类控制器
 */

$admin_commodityController.controller("admin_manage_commodityClass",function($scope,$location,$http){

    /**
     * 获取商品类型信息
     *
     *  访问路由:/commodity_manage_sCommodityClass  GET
     */
    $scope.getCommodityClass = function()
    {
        $scope.selectPage.getDataUrl="/commodity_manage_sCommodityClass";
        $scope.selectPage.getData();

    };
    /**
     * 获取所有区域信息，为商品类绑定区域
     *
     *  访问路由:/commodity_manage_getArea  GET
     */
    $scope.getArea = function()
    {
        $http.get("/commodity_manage_getArea",{}).success(function(response){
            if(response.status == true)
            {
                $scope.areaData = response;
            }
        });
    };
    $scope.getArea();

    /**
     * 修改商品类型
     *
     * 发送数据
     * |-commodity_class_id
     * |-commodity_class_name
     *
     *  访问路由：/commodity_manage_uCommodityClass  POST
     */
    $scope.updateCommodityClass = function($commodityClassId)
    {
        var $commodity_class_id = $commodityClassId;
        var $commodity_class_name = $('#'+$commodityClassId+'_name').val();
        var $commodity_class_area = $('#'+$commodityClassId+'_area').val();
        var $commodity_class_areaCapacity = $('#'+$commodityClassId+'_areaCapacity').val();
        var commodityClassData = {
            commodity_class_id:$commodity_class_id,
            commodity_class_name:$commodity_class_name,
            commodity_class_area:$commodity_class_area,
            commodity_class_areaCapacity:$commodity_class_areaCapacity
        };
        $http.post("/commodity_manage_uCommodityClass",commodityClassData).success(function(response){
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getCommodityClass();
                $scope.getArea();
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });

    };


    /**
     * 添加商品类型
     *
     *
     * 发送数据
     * |-commodity_class_name
     *
     *
     * 访问路由： /commodity_manage_aCommodityClass  POST
     */
    $scope.addCommodityClass = function()
    {

        var commodityClassData = {
            commodity_class_name:$scope.commodity_class_name,
            commodity_class_area_num:$scope.commodity_class_area_num,
            area_id:$scope.area_id
        };
        $http.post("/commodity_manage_aCommodityClass",commodityClassData).success(function(response){
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getCommodityClass();
                $scope.getArea();
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });


    };


    /**
     * 删除商品类型
     *
     * 发送数据
     * |-commodity_class_id
     *
     * 访问路由： /commodity_manage_dCommodityClass  GET
     */
    $scope.deleteCommodityClass = function($commodity_class_id,$commodity_class_area)
    {
        var limit = {
            commodity_class_id:$commodity_class_id,
            commodity_class_area:$commodity_class_area
        };
        var url = $scope.buildUrlParam(limit," /commodity_manage_dCommodityClass");
        $http.get(url).success(function(response) {
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getCommodityClass();
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });

    };


    $scope.getCommodityClass();




});