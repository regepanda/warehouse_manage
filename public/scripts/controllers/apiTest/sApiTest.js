/**
 * Created by RagPanda on 2016/3/24.
 */
$admin_sApiTestController = $app.controller("admin_sApiDeviceTest",function($scope,$http){

try {
    //声明变量
    $scope.url = "";
    $scope.data = "";
    $scope.id = "";
    $scope.type = "";

    $scope.selected = '';
    $scope.models = [{
        type: 4,
        name: "RFID"

    }, {
        type: 3,
        name: "FACE"
    }];


    $scope.postMethod = function () {
        $scope.dstUrl = $scope.url;
        $http.post($scope.url, {
            "id": $scope.id,
            "type": $scope.type,
            "data": $scope.data
        }).success(function (response) {
            $scope.returnData = response;
        });
    };

    $scope.getMethod = function () {
        $scope.dstUrl = $scope.url + "?" + "id" + "=" + $scope.id + "&" + "type" + "=" + $scope.type + "&" + "data" + "=" + $scope.data;
        $http.get($scope.dstUrl).success(function (response) {
            $scope.returnData = response;
        });
    };


    $scope.addGoods = function()
    {
        //异步请求调用函数插入数据
        $http.get("/api_addGoodsData").success(function(response) {
            $scope.deviceData = response;
        });
    };

    $scope.inputData2 = "";
    $scope.returnData2="";
    $scope.url2="";
    $scope.postMethod2 =function(){
        $scope.dstUrl2 = $scope.url2;
        $http.post($scope.url2,$scope.inputData2).success(function(response){
            $scope.returnData2 = response;
        });

    };
    $scope.getMethod2 = function()
    {
        $scope.dstUrl2=$scope.url2+$scope.inputData2;
        $http.get($scope.dstUrl2).success(function(response){
            $scope.returnData2 = response;
        });
    }


}
catch($e)
{
    for(var $v in $e)
    {
        __component_messageBar_setMessage(false,$v.message);
        __component_messageBar_open();
    }

}

});


/*
 <div class="col-sm-6">
 <div class="panel panel-default">
 <div class="panel-body shadow_div">
 <div class="form-group">
 <h1>操作员登录模拟</h1>
 <label >发送链接</label>
 <input type="text" class="form-control" ng-model="url" placeholder="/api_xxx">
 </div>
 <div class="form-group">
 <label>操作员名</label>
 <input type="text" class="form-control" ng-model="operator_name" placeholder="operator name">
 <label>入口id</label>
 <input type="text" class="form-control" ng-model="entrance_id" placeholder="entrance id">
 </div>
 <button ng-click="getMethod()" class="btn btn-default btn-sm">GET</button>
 <button ng-click="postMethod()" class="btn btn-default btn-sm">POST</button>
 </div>
 </div>
 </div>

 <div class="panel panel-default ">
 <div class="panel-body shadow_div" >
 <h2>返回数据 <small>URL : @{{ dstUrl }}</small></h2>
 <div class="form-group">
 <label >缓存界面，你可以在这里放一些查询的结果：</label>
 <textarea class="form-control" rows="3">
 @{{ returnData }}
 </textarea>
 </div>
 </div>
 </div>


 */