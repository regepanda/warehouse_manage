@extends("base")
<style>
    span.icon_1 {
        font-size: 700%;
        line-height: 70%;
        color: #4FD2C2;
        margin: 30% 50% 40% 31%;
        display: block;
    }
    .left
    {
        border:1px #4FD2C2 solid;
        height: 45%;
        margin-top:30%;
        margin-left: 47%;
        border-radius: 50px;
    }

    .right
    {
        border:1px #4FD2C2 solid;
        height: 45%;
        margin-top:30%;
        margin-left:13%;
        border-radius: 50px;
    }

    .feature-content-link {
        text-decoration: none;
        border: 1px solid #4FD2C2 ;
        background-color: transparent;
        color: #4FD2C2 !important;
        border-radius: 5px;
        font-size: 120%;
        padding: 5% 19%;
        text-transform: capitalize;
        -webkit-transition: all 0.3s ease;
        transition: all 0.3s ease;
    }
    .feature-content-link:hover
    {
        background-color: #4FD2C2;
        color: white !important;
    }
    #submit_button_left
    {
        position: relative;
        width:60%;
        left: 22%;
        top: -10%;
    }
    #submit_button_right
    {
        position: relative;
        width:60%;
        left: 28%;
        top:-10%;
    }

</style>
@section("body")
    <div class="bgi_div">
        <img class="full_image" src="/image/bg_2.jpg">
    </div>
      <div class="col-md-6">
        <div class="col-md-5 left">
            <span class="glyphicon glyphicon-user icon_1" aria-hidden="true"></span>
            <div id="submit_button_left">
                <a href="/admin" class="feature-content-link">管理员登录</a>
            </div>
        </div>
      </div>
    <div class="col-md-6">
        <div class="col-md-5 right">
            <span class="glyphicon glyphicon-home icon_1" aria-hidden="true"></span>
            <div id="submit_button_right">
                <a href="/user" class="feature-content-link">用户登录</a>
            </div>
       </div>
    </div>
    <div class="col-md-6">
        <div class="col-md-5 right">
            <span class="glyphicon glyphicon-home icon_1" aria-hidden="true"></span>
            <div id="submit_button_right">
                <a href="/Admin_Init_initDataSet" class="feature-content-link">数据初始化</a>
            </div>
        </div>
    </div>
@stop
