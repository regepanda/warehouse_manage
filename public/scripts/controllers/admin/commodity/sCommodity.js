/**
 * Created by zc on 2016/4/27.
 */
$admin_commodityController = $app.controller("admin_commodity",function($scope,$location,SelectPageService) {

    $scope.goCommodity = function()
    {
        $location.path("/manage_commodity");
    };

    $scope.goCommodityClass = function()
    {
        $location.path("/manage_commodity_class");
    };


    $scope.selectPage = SelectPageService;

});

$admin_commodityController.config(["$routeProvider",function($routeProvider){
    $routeProvider.when("/manage_commodity",{
        templateUrl:"/views/admin/sCommodity.html",
        controller:"admin_manage_commodity"
    }).when("/manage_commodity_class",{
        templateUrl:"/views/admin/sCommodityClass.html",
        controller:"admin_manage_commodityClass"
    }).when("/manage_goods",{
        templateUrl:"/views/admin/sGoods.html",
        controller:"admin_manage_goods"
    });
}]);


/**
 * 商品控制器
 */

$admin_commodityController.controller("admin_manage_commodity",function($scope,$location,$http){


    /**
     * 获取商品信息
     *
     * 访问路由：/commodity_manage_sCommodity  GET
     *          /commodity_manage_sCommodityCslass GET
     */
    $scope.getCommodity = function()
    {

        //commodity

        $scope.selectPage.getDataUrl="/commodity_manage_sCommodity";
        $scope.selectPage.getData();

        //commodityClass
        $scope.commodityClassData = {};
        $http.get("/commodity_manage_sCommodityClass").success(function(response) {
            $scope.commodityClassData = response.data;
        });
    };



    /**
     * 修改商品
     *
     * 发送数据
     * |-commodity_id
     * |-commodity_name
     * |-commodity_price
     * |-commodity_model
     * |_commodity_class
     * |_commodity_detail
     *
     *访问路由：/commodity_manage_uCommodity  POST
     */
    $scope.updateCommodity = function($commodityId)
    {
        var $commodity_id = $commodityId;
        var $commodity_name = $('#'+$commodity_id+'_name').val();
        var $commodity_price = $('#'+$commodity_id+'_price').val();
        var $commodity_model = $('#'+$commodity_id+'_model').val();
        var $commodity_class = $('#'+$commodity_id+'_class').val();
        var $commodity_detail = $('#'+$commodity_id+'_detail').val();
        var commodityData = {commodity_id:$commodity_id,commodity_name:$commodity_name,commodity_price:$commodity_price,commodity_model:
        $commodity_model,commodity_class:$commodity_class,commodity_detail:$commodity_detail};
        $http.post("/commodity_manage_uCommodity",commodityData).success(function(response){
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getCommodity();

            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });

    };


    /**
     * 添加商品
     *
     *
     * 发送数据
     * |-commodity_name
     * |-commodity_price
     * |-commodity_model
     * |_commodity_class
     * |-commodity_detail
     *
     * 访问路由：/commodity_manage_aCommodity  POST
     */
    $scope.addCommodity = function()
    {
        var commodityData = {commodity_name:$scope.commodity_name,commodity_price:$scope.commodity_price,commodity_model:
            $scope.commodity_model,commodity_class:$scope.commodity_class,commodity_detail:$scope.commodity_detail};
        $http.post("/commodity_manage_aCommodity",commodityData).success(function(response){
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getCommodity();
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });


    };


    /**
     * 删除商品
     *
     * 发送数据
     * |-commodity_id
     *
     * 访问路由：/commodity_manage_dCommodity  GET
     */
    $scope.deleteCommodity = function($commodity_id)
    {

        var limit = {commodity_id:$commodity_id};
        var url = $scope.buildUrlParam(limit,"/commodity_manage_dCommodity");
        $http.get(url).success(function(response) {
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getCommodity();
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });

    };


    $scope.getCommodity();



});

