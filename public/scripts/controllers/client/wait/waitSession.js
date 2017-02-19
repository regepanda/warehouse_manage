/**
 * Created by zc on 2016/4/11.
 */
/**
 * Created by RagPanda on 2016/3/9.
 */
$client_operateController = $app.controller("client_operate",function($scope,$location)
{

       $scope.goWaitSession = function()
    {
        $location.path("/wait_session");
    };

    $scope.goManageGoods = function()
    {
        $scope.go("#/manage_goods");
    };

});
$client_operateController.config(["$routeProvider",function($routeProvider){
    $routeProvider.when("/wait_session",{
        templateUrl:"/views/client/sWait/waitSession.html",
        controller:"client_operate_waitSession"
    }).when("/manage_goods",{
        templateUrl:"/views/client/sWait/manageGoods.html",
        controller:"client_operate_manageGoods"
    }).otherwise({redirectTo:'/wait_session'});
}]);

/**
 * 等待会话控制器
 */
$client_operateController.controller("client_operate_waitSession",function($scope,$location,Polling){
    $scope.polling = Polling;

    /**
     * 启动验证操作者轮询(传一个回调函数进去，让轮询知道轮询后的操作)
     * 一旦有操作员操作，跳转到等待硬件数据界面（即跳转到路由/wait_hardware）
     * 传入路由
     * /client_wait_waitSession POST
     */
    $scope.startOperatorPolling = function()
    {

        $scope.polling.ajax_get_data({},"/client_wait_waitSession","post",2000,function(data)
        {
            __component_messageBar_setMessage(true,"取得一个会话" + data.message);
            __component_messageBar_open();
            $scope.goManageGoods();

        });
    };

    /**
     * 自动启动轮询
     */
    $scope.startOperatorPolling();



});
