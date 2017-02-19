@extends("Admin.index")

@section("second_nav")
    <div class="col-sm-12 opa">
        <div class="panel panel-default">
            <div class="panel-body shadow_div">
                <h2>记录主界面</h2>
            </div>
        </div>
    </div>
@append

@section("main")
    <div class="col-sm-12 opa" ng-controller="admin_sLog">
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
    <link rel="stylesheet" href="/style/client/one.css">
    <script src="/scripts/controllers/admin/sLog.js"></script>
    <script src="/scripts/services/SelectPageService.js"></script>
@stop
