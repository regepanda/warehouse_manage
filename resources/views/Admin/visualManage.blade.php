@extends("Admin.index")

@section("second_nav")
    <div class="col-sm-12 opa">
        <div class="panel panel-default">
            <div class="panel-body shadow_div">
                <h2>数据可视化主界面</h2>
            </div>
        </div>
    </div>
@append
@section("left_nav")
    @include("Admin.visual_left_nav")
@append



@section("main")
    <div class="col-sm-10 opa" ng-controller="admin_visual">
        <div class="panel panel-default">
            <div class="panel-body shadow_div">
                <div ng-view>


                </div>
            </div>
        </div>
    </div>
@stop


@section("bottom")
    @include("lib.ng_lib")
    <script src="/lib/D3/d3.js"></script>
    <link rel="stylesheet" href="/style/visual/area.css">
    <script src="/scripts/controllers/admin/visual/area.js"></script>
    <script src="/scripts/controllers/admin/visual/log.js"></script>
    <script src="/scripts/controllers/admin/visual/humidity.js"></script>

@stop

