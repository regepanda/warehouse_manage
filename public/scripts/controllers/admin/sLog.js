/**
 * Created by zc on 2016/4/11.
 */
$admin_sLogController = $app.controller("admin_sLog",function($scope,$location,SelectPageService)
{
    $scope.goIndex = function(){
        $location.path("/index");
    };
    $scope.goDetail = function($id){
        $location.path("/detail/"+$id);
    };

    $scope.selectPage = SelectPageService;

});
$admin_sLogController.config(["$routeProvider",function($routeProvider){
    $routeProvider.when("/index",{
        templateUrl:"/views/admin/sLog/index.html",
        controller:"admin_sLog_index"
    }).when("/detail/:id",{
        templateUrl:"/views/admin/sLog/detail.html",
        controller:'admin_sLog_detail'
    }).otherwise({redirectTo:'/index'});

}]);

$admin_sLogController.controller("admin_sLog_index",function($scope,$http){


    //得到所有的请求的日志等级
    $scope.levelData = [
        {level_id:1,level_name:"ERROR"},
        {level_id:2,level_name:"WARNING"},
        {level_id:3,level_name:"DEBUG"},
        {level_id:4,level_name:"INFO"},
        {level_id:5,level_name:"SYSTEMINFO"}
    ];



    $scope.buildUrlParam = function($limit,$url)
    {
        var url = $url;
        url+="?";
        for(var i in $limit)
        {
            url += "&" + i + "=";
            url += $limit[i] ;//+ "&";

        };
        return url;
    };


    /**
     *
     *
     * 访问类型：admin_log_sAllLog
     */

    $scope.getAllLog = function(){



        $scope.selectPage.getDataUrl="/admin_log_sAllLog";
        $scope.selectPage.getData();




        /*
        var $limit = {num:5,desc:true};
        var url = $scope.buildUrlParam($limit,"/client_log_sAllLog");
        $http.get(url).success(function(response){
            $scope.logData =  response.data;
        });
        */


    };




    $scope.getAllLog();

});

$admin_sLogController.controller("admin_sLog_detail",function($scope,$http,$routeParams){
    $scope.id = $routeParams.id;
    $scope.getDetail = function(){
        var $limit = {log_id:$scope.id};
        var url = $scope.buildUrlParam($limit,"/admin_log_sDetailLog");
        $http.get(url).success(function(response){
             $scope.logDetail = response;
        });
    };
    $scope.getDetail();
});
