/**
 * Created by RagPanda on 2016/4/12.
 */
$test_wjt_controller = $app.controller("test_wjt_controller",function($scope,$http)
{
    var pollRequest = Polling();
    pollRequest.ajax_get_data({name:"nima"},"/_test_wjt_polling","post",10000,function(data)
    {alert("wocaonima1");});

});