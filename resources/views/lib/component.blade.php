<div id="__component_messageBar">
<h3><label id="__component_messageBar_text">{{session("other.__component_messageBar_message")}}</label>
    <small style="color: #E0F2F1"> | <a href="#" id="__component_messageBar_close"> 关闭</a></small>
</h3>

</div>
<style>
    #__component_messageBar
    {
        width:100%;

        background-color:gray;
        position: fixed;
        bottom:0%;
        left: 0%;
        text-align: center;
        display: none;
        /*border-radius: 10px;*/
        color: #B2DFDB;
        /*box-shadow: 0px 10px 30px #004D40;*/



    }
    #__component_messageBar_close
    {
        color: whitesmoke;
    }
    #__component_messageBar_close:hover{
        text-decoration: none;
    }
    #__component_messageBar_close:active{
        text-decoration: none;
    }

</style>
<script>
    function __component_messageBar_setMessage(status,string)
    {
        $("#__component_messageBar_text").html(string);
        if(status == true)
        {
            $("#__component_messageBar").css("background-color","#009688");
        }
        else
        {
            $("#__component_messageBar").css("background-color","#EF5350");
        }
    };
    function __component_messageBar_open()
    {
        $("#__component_messageBar").fadeIn(500);

    };
    function __component_messageBar_close()
    {
        $("#__component_messageBar").fadeOut(500);

    };

    $(document).ready(function(){
       $("#__component_messageBar_close").click(function(){
           __component_messageBar_close();
           return false;
        });
        <?php

        if (($__component_messageBar_message = session("other.__component_messageBar_message") )!=null)
        {

            if (session("other.__component_messageBar_status") == true)
            {
                echo ";__component_messageBar_setMessage(true, '".$__component_messageBar_message."');";
            }
            else
            {
                echo ";__component_messageBar_setMessage(false, '".$__component_messageBar_message."');";
            }
            echo ";__component_messageBar_open();";
            //Session::
        }

        ?>
    });

</script>