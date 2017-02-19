@extends("Admin.index")

@section("second_nav")
    <div class="col-sm-12 opa">
        <div class="panel panel-default">
            <div class="panel-body shadow_div">
                <h2>权限管理主界面</h2>
            </div>
        </div>
    </div>
@append
@section("left_nav")
    @include("Admin.operator_left_nav")
@append



@section("main")
    <div class="col-sm-10">
        <div class="panel panel-default">
            <div class="panel-body shadow_div">
                <h2>权限管理主界面</h2>
            </div>
        </div>
    </div>
@stop

