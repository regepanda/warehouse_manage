@extends("base")

@section("body")

@section("top_nav")
    <link rel="stylesheet" type="text/css" href="/css/Index/index.css">
    <div class="bgi_div">
        <img class="full_image" src="/image/index_bg.jpg">
    </div>


    <nav class="navbar navbar-default navbar-fixed-top" style="opacity: 0.9;background-color: gainsboro" id="top_nav">
        <div class="container" style="">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/user_index">Warehouse</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="/client_operate_index"> <span class="glyphicon glyphicon glyphicon-wrench" aria-hidden="true"></span>
                            操作
                        </a></li>
                    <li><a href="/api_test" href="#menu-toggle" id="menu-toggle">
                            <span class="glyphicon glyphicon glyphicon-wrench" aria-hidden="true"></span>
                            测试
                        </a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a  href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="glyphicon glyphicon-cog"></span>  操作 <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">用户信息</a></li>
                            <li><a href="/client_base_logout">登出</a></li>
                        </ul>
                    </li>
                </ul>


            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
@show


@section("second_nav")

@show

@section("main")

@show

@append




