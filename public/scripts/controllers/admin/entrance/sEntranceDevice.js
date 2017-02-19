/**
 * Created by zc on 2016/5/7.
 */

$admin_entranceController.controller("admin_manage_entranceDevice",function($scope,$location,$http,$routeParams) {


    /**
     * 获取入口设备信息
     * 发送数据
     * |-entrance_id
     *
     * 访问路由：/entrance_manage_sEntranceDevice  GET
     *
     */
    $scope.getEntranceDevice = function()
    {
       $scope.entrance_id = $routeParams.entrance_id;


        var limit = {entrance_id:$scope.entrance_id};
        var url = $scope.buildUrlParam(limit,"/entrance_manage_sEntranceDevice");
        $http.get(url).success(function(response) {
            if(response.status == true)
            {
               $scope.entranceDevices = response.data;
            }
            else if(response.status == -1)
            {
                $scope.entranceDevices = null;

            }
            else
            {
                $scope.entranceDevices = null;
            }
        });
    };




    /**
     * 移除入口设备
     *
     * 发送数据
     * |-entrance_id
     * |-device_id
     *
     * 访问路由：/entrance_manage_removeEntranceDevice  GET
     *
     */
    $scope.removeEntranceDevice = function($deviceId)
    {

        var limit = {entrance_id:$scope.entrance_id,device_id:$deviceId};
        var url = $scope.buildUrlParam(limit,"/entrance_manage_removeEntranceDevice");
        $http.get(url).success(function(response) {
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getEntranceDevice();
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });



    };


    $scope.getEntranceDevice();

    /*
     ,
     {
     "id" : ObjectId("572b4224149ad6cc26000034"),
     "name" : "设备2 CAMERA"
     }
     */




});