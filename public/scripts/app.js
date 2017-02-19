var $app = angular.module("warehouse",['ngAnimate','ngRoute']);
$app.run(function($rootScope){


    $rootScope.buildUrlParam = function($limit,$url)
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
    $rootScope.go=function(url)
    {
        window.location.href=url;
    };


    $rootScope.keywordStatusMap = {
        0:"库外",
        1:"库内",
        2:"冻结"
    };

    $rootScope.keywordDeviceTypeMap = {
        0:"RFID",
        1:"CAMERA",
        2:"ANDROID"
    };
    $rootScope.keywordDeviceUseMap = {
        0:"关闭",
        1:"开启"
    };
    $rootScope.keywordLevelMap = {
        0:0,
        1:1,
        2:2,
        3:3,
        4:4,
        5:4
    };

});


