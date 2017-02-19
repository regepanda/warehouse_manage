@extends("Client.base")

@section("second_nav")
    <div class="col-sm-12 opa">
        <div class="panel panel-default">
            <div class="panel-body shadow_div">
                <h2>操作主界面</h2>
            </div>
        </div>
    </div>
@append

@section("main")
    <div class="col-sm-12 opa" ng-controller="client_operate">
        <div class="panel panel-default">
            <div class="panel-body shadow_div">
                <div id="pageView"  ng-view>

                </div>
            </div>
        </div>
    </div>
@stop

@section("bottom")
    @include("lib.ng_lib")
    <link rel="stylesheet" href="/style/viewAnimate.css">
    <script src="/scripts/controllers/client/wait/waitSession.js"></script>
    <script src="/scripts/controllers/client/wait/manageGoods.js"></script>
    <script src="/lib/myClass/polling.js"></script>

    <link rel="stylesheet" href="/style/client/one.css">
@stop
