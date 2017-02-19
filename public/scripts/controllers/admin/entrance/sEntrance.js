/**
 * Created by zc on 2016/5/7.
 */
$admin_entranceController = $app.controller("admin_entrance",function($scope,$location,SelectPageService) {

    $scope.goDevice = function()
    {
        $location.path("/manage_entrance");
    };


    $scope.selectPage = SelectPageService;

});

$admin_entranceController.config(["$routeProvider",function($routeProvider){
    $routeProvider.when("/manage_entrance",{
        templateUrl:"/views/admin/sEntrance.html",
        controller:"admin_manage_entrance"
    }).when("/manage_entrance_device/:entrance_id",{
        templateUrl:"/views/admin/sEntranceDevice.html",
        controller:"admin_manage_entranceDevice"
    });
}]);


/**
 * 入口控制器
 */
$admin_entranceController.controller("admin_manage_entrance",function($scope,$location,$http) {


    /**
     * 获取入口信息
     *
     * 访问路由：/entrance_manage_sEntrance  GET
     *
     */
    $scope.getEntrance = function()
    {
        $scope.selectPage.getDataUrl="/entrance_manage_sEntrance";
        $scope.selectPage.getData();
    };

    /**
     * 修改入口
     *
     * 发送数据
     * |-entrance_id
     * |-entrance_name
     * |-entrance_login_name
     *
     * 访问路由：/entrance_manage_uEntrance  POST
     *
     */
    $scope.updateEntrance = function($entranceId)
    {
        var $entrance_id = $entranceId;
        var $entrance_name = $('#'+$entrance_id+'_name').val();
        var $entrance_login_name = $('#'+$entrance_id+'_login_name').val();
        var entranceData = {entrance_id:$entrance_id,entrance_name:$entrance_name,entrance_login_name:$entrance_login_name};
        $http.post("/entrance_manage_uEntrance",entranceData).success(function(response){
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getEntrance();
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });

    };


    /**
     * 添加入口
     *
     *
     * 发送数据
     * |-entrance_name
     * |-entrance_login_name
     * |-entrance_password
     *
     * 访问路由：/entrance_manage_aEntrance POST
     */
    $scope.addEntrance = function()
    {
        var entranceData = {entrance_name:$scope.entrance_name,entrance_login_name:$scope.entrance_login_name,entrance_password:
            $scope.entrance_password};
        $http.post("/entrance_manage_aEntrance",entranceData).success(function(response){
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getEntrance();
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });


    };


    /**
     * 删除设备
     *
     * 发送数据
     * |-entrance_id
     *
     * 访问路由：/entrance_manage_dEntrance  GET
     */
    $scope.deleteEntrance = function($entrance_id)
    {

        var limit = {entrance_id:$entrance_id};
        var url = $scope.buildUrlParam(limit,"/entrance_manage_dEntrance");
        $http.get(url).success(function(response) {
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getEntrance();
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });

    };


    $scope.getEntrance();


});