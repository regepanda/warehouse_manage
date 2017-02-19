/*
 * Created by PengLiang on 2016/4/6.
 */
$app.factory("Polling",function($interval)
{
    var returnData = {};
    returnData.canNextPolling = true;
    returnData.intervalPolling = undefined;
    returnData.ajax_get_data = function($polling_data,$url,$type,$timeout,$callback,$infinite)
    {
        if(returnData.intervalPolling != undefined) //如果以前有轮询实例的话
        {
            returnData.stopPolling();               //关掉
            returnData.canNextPolling = true;       //可以进行下一次
        }

        returnData.intervalPolling= $interval(function()
        {
            console.log("call polling");
            if(returnData.canNextPolling == true)
            {
                returnData.canNextPolling = false;
                $.ajax({
                    type:$type,
                    url:$url,
                    timeout:$timeout,
                    data:$polling_data,
                    success:function(data)
                    {
                        if(data.status == true)
                        {

                            if($infinite != true)
                            {
                                returnData.canNextPolling = false;  //如果非无限轮询，那么将不能再进入下一次
                                //returnData.stopPolling();
                                return $callback(data);
                                //return
                            }
                            else
                            {
                                returnData.canNextPolling = true;   //如果是，可以进入下一次
                                return $callback(data);
                            }

                        }
                        else
                        {
                            returnData.canNextPolling = true;
                        }

                    }
                });
            }
        }, $timeout);

    };
    returnData.stopPolling = function()
    {
        $interval.cancel(returnData.intervalPolling);
    };
    return returnData;
});

/*
 {
 "data" : {
 "goods_id" : ObjectId("571740b0149ad66c20000039")
 }
 },
 {
 "data" : {
 "goods_id" : ObjectId("571740b0149ad66c2000003a")
 }
 }
 */
