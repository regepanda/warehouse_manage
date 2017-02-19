@extends("base")
@section("body")
    <div class="bgi_div">
        <img class="full_image" src="/image/computer2.jpg">
    </div>
    @section("nav")
        <nav class="navbar navbar-default shadow_div">
            <div class="container ">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">后台管理</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li @if(session("other.nowModule")=="commodityManage")class="active"@endif ><a href="/admin_commodity_manage" class="full_div_a"><span class="glyphicon glyphicon-list"></span> 商品货物管理 <span class="sr-only">(current)</span></a></li>
                        <li @if(session("other.nowModule")=="powerManage")class="active"@endif ><a  href="/admin_power_manage" class="full_div_a"><span class="glyphicon glyphicon-check"></span>  权限管理<span class="sr-only">(current)</span></a></li>
                        <li @if(session("other.nowModule")=="sEntrance")class="active"@endif ><a  href="/admin_entrance_manage#/manage_entrance" class="full_div_a"><span class="glyphicon glyphicon-ok-sign"></span>  入口管理<span class="sr-only">(current)</span></a></li>
                        <li @if(session("other.nowModule")=="sArea")class="active"@endif ><a  href="/area_sArea" class="full_div_a"><span class="glyphicon glyphicon-warning-sign"></span>  区域管理<span class="sr-only">(current)</span></a></li>
                        <li @if(session("other.nowModule")=="sDevice")class="active"@endif ><a  href="/admin_device_manage#/manage_device" class="full_div_a"><span class="glyphicon glyphicon-wrench"></span>  设备管理<span class="sr-only">(current)</span></a></li>
                        <li @if(session("other.nowModule")=="sLog")class="active"@endif ><a  href="/admin_log_manage" class="full_div_a"><span class="glyphicon glyphicon-book"></span>  日志管理<span class="sr-only">(current)</span></a></li>
                        <li @if(session("other.nowModule")=="visualManage")class="active"@endif ><a  href="/admin_visual_manage" class="full_div_a"><span class="glyphicon glyphicon-eye-open"></span>  可视化数据<span class="sr-only">(current)</span></a></li>

                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a  href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="glyphicon glyphicon-cog"></span>  操作 <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">管理员信息</a></li>
                                <li><a href="/admin_logout">登出</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>



    @show

    @section("second_nav")

    @show
    @section("left_nav")

    @show

    @section("main")

    @show




@append