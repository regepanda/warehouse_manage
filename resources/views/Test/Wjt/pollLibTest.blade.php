@extends("base")

@section("body")
   <div ng-controller="test_wjt_controller">
        测试一下轮询
   </div>
@append


@section("bottom")
    @include("lib.ng_lib")
    <link rel="stylesheet" href="/style/viewAnimate.css">
    <script src="/lib/myClass/polling.js"></script>

    <script src="/scripts/controllers/apiTest/wjtTest.js"></script>
@stop