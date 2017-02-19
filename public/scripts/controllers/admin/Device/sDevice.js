/**
 * Created by zc on 2016/5/6.
 */


/**
 * Created by zc on 2016/4/27.
 */
$admin_deviceController = $app.controller("admin_device",function($scope,$location,SelectPageService) {

    $scope.goDevice = function()
    {
        $location.path("/manage_device");
    };


    $scope.selectPage = SelectPageService;

});

$admin_deviceController.config(["$routeProvider",function($routeProvider){
    $routeProvider.when("/manage_device",{
        templateUrl:"/views/admin/sDevice.html",
        controller:"admin_manage_device"
    });
}]);


/**
 * 商品控制器
 */

$admin_deviceController.controller("admin_manage_device",function($scope,$location,$http){


    $scope.deviceTypes = [{
        type: 0,
        name: "RFID"

    }, {
        type: 1,
        name: "CAMERA"
    }];


    $scope.deviceControl = [{
        type: 0,
        name: "不可控制"

    }, {
        type: 1,
        name: "可控制"
    }];

    $scope.orders = [{
        type: "start",
        name: "开启"

    }, {
        type: "stop",
        name: "关闭"
    }, {
        type: "config",
        name: "配置"
    }];

    /**
     * 获取设备信息
     *
     * 访问路由：/admin_device_sDevice  GET 设备信息
     *         /admin_device_sDeviceEntrance 入口信息
     */
    $scope.getDevice = function()
    {
        //设备信息
        $scope.selectPage.getDataUrl="/admin_device_sDevice";
        $scope.selectPage.getData();


        //入口信息
        $http.get("/admin_device_sDeviceEntrance").success(function(response) {
            if(response.status == true)
            {
               $scope.deviceEntrances = response.data.data;
            }
            else
            {
                $scope.deviceEntrances = response.data;
            }
        });

    };



    /**
     * 修改商品
     *
     * 发送数据
     * |-device_id
     * |-self_id
     * |-device_name
     * |-device_type
     * |_device_intro
     * |-device_entrance
     * |-device_control
     *
     *访问路由：/admin_device_uDevice POST
     */
    $scope.updateDevice = function($deviceId)
    {
        var $device_id = $deviceId;
        var $device_name = $('#'+$device_id+'_name').val();
        var $self_id = $('#'+$device_id+'_self_id').val();
        var $device_type = $('#'+$device_id+'_type').val();
        var $device_intro = $('#'+$device_id+'_intro').val();
        var $device_entrance = $('#'+$device_id+'_entrance').val();
        var $device_control = $('#'+$device_id+'_control').val();
        var commodityData = {device_control:$device_control,device_id:$device_id,device_name:$device_name,self_id:$self_id,device_type:
            $device_type,device_intro:$device_intro,device_entrance:$device_entrance};
        $http.post("/admin_device_uDevice",commodityData).success(function(response){
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getDevice();
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });

    };


    /**
     * 添加设备
     *
     *
     * 发送数据
     * |-device_name
     * |-device_type
     * |-self_id
     * |_device_intro
     * |-device_entrance
     * |-device_control
     *
     * 访问路由：/admin_device_aDevice  POST
     */
    $scope.addDevice = function()
    {
        var deviceData = {device_control:$scope.device_control,device_name:$scope.device_name,device_type:$scope.device_type,self_id:
            $scope.self_id,device_intro:$scope.device_intro,device_entrance:$scope.device_entrance};
        $http.post("/admin_device_aDevice",deviceData).success(function(response){
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getDevice();
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
     * |-device_id
     *
     * 访问路由：/admin_device_dDevice  GET
     */
    $scope.deleteDevice = function($device_id)
    {

        var limit = {device_id:$device_id};
        var url = $scope.buildUrlParam(limit,"/admin_device_dDevice");
        $http.get(url).success(function(response) {
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getDevice();
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });

    };


    /**
     * 禁用/启用设备
     *
     * 发送数据
     * |-device_id
     * |-device_use bool值
     *
     * 访问路由：/admin_device_uDeviceUse GET
     */
    $scope.uDeviceUse = function($deviceId)
    {

        var limit = {device_id:$deviceId};
        //var url = $scope.buildUrlParam(limit,"/admin_device_toggleDevice");
        $http.post("/admin_device_toggleDevice",limit)
            .success(function(response) {
            if(response.status == true)
            {
                __component_messageBar_setMessage(true,response.message);
                __component_messageBar_open();
                $scope.getDevice();
            }
            else
            {
                __component_messageBar_setMessage(false,response.message);
                __component_messageBar_open();
            }
        });


    };


    /**
     * 控制设备
     */



    $scope.getDevice();



});

