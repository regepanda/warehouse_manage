@extends("Admin.index")

@section("left_nav")
<button class="btn btn-warning btn-sm" id="test">测试</button>

<script language="JavaScript">
    $("#test").click(function(){
        var polling = new Polling();
        $polling_data = { "A":"a","B":"b","C":"c" };
        $url = "/_test";
        $type = "post";
        $timeout = 20000;
        polling.ajax_get_data($polling_data,$url,$type,$timeout);
    });

</script>

@stop