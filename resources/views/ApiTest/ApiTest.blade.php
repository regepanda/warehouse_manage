@extends("base")

@section("body")

    <div ng-controller="admin_sApiDeviceTest">

        <hr>
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body shadow_div">
            <button ng-click="addGoods()" class="btn btn-success btn-lg">添加扫描货物</button>
                    </div>
                </div>
        </div>

        <div class="panel panel-default ">
            <div class="panel-body shadow_div" >
                <div class="form-group">
            <textarea class="form-control" rows="3">
         添加扫描货物返回数据： @{{deviceData}}
            </textarea>
                    </div>
                </div>
            </div>

        <br>
        <br>


        <hr>

        <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body shadow_div">
                <div class="form-group">
                    <h1>硬件会话请求模拟</h1>
                    <label >发送链接</label>
                    <input type="text" class="form-control" ng-model="url" placeholder="/api_xxx">
                </div>
                <div class="form-group">
                     <label>id</label>
                    <input type="text" class="form-control" ng-model="id" placeholder="id">
                    <label>类型</label>
                    <select class="form-control" ng-model="type" ng-options="m.type as m.name for m in models">
                    </select>
                    <label>数据</label>
                    <textarea class="form-control" ng-model="data" rows="3"></textarea>
                </div>
                <button ng-click="getMethod()" class="btn btn-default btn-sm">GET</button>
                <button ng-click="postMethod()" class="btn btn-default btn-sm">POST</button>
            </div>
        </div>
            </div>

        <div class="panel panel-default ">
            <div class="panel-body shadow_div" >
                <div class="form-group">
                    <textarea class="form-control" rows="3">
                        硬件会话请求返回数据： @{{ returnData }}
                    </textarea>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body shadow_div">
                <div class="form-group">
                    <h1>app模拟</h1>
                    <label >发送链接</label>
                    <input type="text" class="form-control" ng-model="url2" placeholder="/api_xxx">
                </div>
                <div class="form-group">
                    <label >发送数据：</label>
                    <textarea class="form-control" ng-model="inputData2"  placeholder='POST写为json格式{"a":"as","b":12},GET为?key=value&key2=v2' rows="3"></textarea>
                </div>
                <button ng-click="getMethod2()" class="btn btn-default btn-sm">GET</button>
                <button ng-click="postMethod2()" class="btn btn-default btn-sm">POST</button>
            </div>
        </div>
            </div>
        <div class="panel panel-default ">
            <div class="panel-body shadow_div" >
                <div class="form-group">
                    <textarea class="form-control" rows="3">
                       app模拟返回数据：@{{ returnData2 }}
                    </textarea>
                </div>
            </div>
        </div>


    </div>


@stop

@section("bottom")
    @include("lib.ng_lib")
    <link rel="stylesheet" href="/style/viewAnimate.css">
    <script src="/scripts/controllers/apiTest/sApiTest.js"></script>

@stop