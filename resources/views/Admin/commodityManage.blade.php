@extends("Admin.index")

@section("second_nav")
    <div class="col-sm-12 opa">
        <div class="panel panel-default">
            <div class="panel-body shadow_div">
                <h2>商品管理主界面</h2>
            </div>
        </div>
    </div>
@append
@section("left_nav")
    @include("Admin.commodity_left_nav")
@append



@section("main")
    <div class="col-sm-10 opa" ng-controller="admin_commodity">
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
    <script src="/scripts/controllers/admin/commodity/sCommodity.js"></script>
    <script src="/scripts/controllers/admin/commodity/sCommodityClass.js"></script>
    <script src="/scripts/controllers/admin/commodity/sGoods.js"></script>
    <script src="/scripts/services/SelectPageService.js"></script>
@stop

