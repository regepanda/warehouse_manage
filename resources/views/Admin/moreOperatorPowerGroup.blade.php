@extends("Admin.powerManage")

@section("main")
    <script type="text/javascript">
        $(function(){
            //用户在添加权限到当前权限组的时候可以一键全选
            $("#CheckedAllPower").click(function(){
                if(this.checked)
                {
                    //$("input[name='newsletter']").attr("checked", true);
                    $("[name='power_id_array[]']").prop("checked",true);
                }
                else
                {
                    $("[name='power_id_array[]']").prop("checked",false);
                }
            });
            //用户在添加用户到当前权限组的时候可以一键全选
            $("#CheckedAllOperator").click(function(){
                if(this.checked)
                {
                    $("[name='operator_id_array[]']").prop("checked",true);
                }
                else
                {
                    $("[name='operator_id_array[]']").prop("checked",false);
                }
            });
        });
    </script>

    <div class="col-sm-10">
        <div class="panel panel-default">
            <div class="panel-body shadow_div">
                <h2 class="sub-header">当前操作员权限组：{{$data["name"]}}</h2>
                <hr>

                <div class="col-sm-5 ">
                    <table class="table">

                        <thead>
                        <tr>
                            <th>当前权限</th>

                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($data["power_list"] != null)
                        @foreach ($data["power_list"] as $singlePower)
                            <tr>
                                <td>{{$singlePower["power_name"]}}</td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#del_{{$singlePower["power_id"]}}">
                                        移除
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="del_{{$singlePower["power_id"]}}" tabindex="-1"
                                         role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">


                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span
                                                                aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">警告！</h4>

                                                </div>
                                                <div class="modal-body">
                                                    <form action="/operator_removePowerToOperatorPowerGroup" method="post">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="hidden" name="group_id"
                                                               value="{{$data["_id"]}}">
                                                        <input type="hidden" name="power_id"
                                                               value="{{$singlePower["power_id"]}}">
                                                        将要移除该权限！
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-danger btn-sm" type="submit">移除</button>
                                                    <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">返回
                                                    </button>
                                                </div>

                                                </form>

                                            </div>
                                        </div>

                                </td>
                            </tr>

                        @endforeach
                            @endif

                        </tbody>
                    </table>

                </div>



                <div class="col-sm-5 ">
                    <table class="table">

                        <thead>
                        <tr>
                            <th>当前操作员</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                            @if($data["operator_list"] != null)
                            @foreach ($data["operator_list"] as $singleOperator)

                                    <td>{{$singleOperator["operator_name"]}}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#del_operator_{{$singleOperator["operator_id"]}}">移除
                                        </button>
                                        <div class="modal fade" id="del_operator_{{$singleOperator["operator_id"]}}" tabindex="-1" role="dialog"
                                             aria-labelledby="myModalLabel">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title" id="myModalLabel">警告！</h4>
                                                    </div>

                                                    <div class="modal-body">
                                                        <form action="/operator_removeOperatorToOperatorPowerGroup" method="post">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <input type="hidden" name="group_id" value="{{$data["_id"]}}">
                                                            <input type="hidden" name="operator_id" value="{{$singleOperator["operator_id"] }}">
                                                            将要移除该操作员！
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-danger btn-sm" type="submit">移除</button>
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">返回
                                                        </button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                        </tr>
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>

                </form>


                <div class="col-sm-2">
                    <button type="button" class="btn  btn-primary" data-toggle="modal"
                            data-target="#add_power_{{$data["_id"]}}">添加权限
                    </button>
                    <div class="modal fade" id="add_power_{{$data["_id"]}}" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">请选择要添加的权限</h4>
                                </div>
                                 <form action="/operator_addPowerToOperatorPowerGroup" method="post">
                                    <div class="modal-body">

                                        <input type="checkbox" id="CheckedAllPower"><b>全选/全不选</b>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="group_id" value="{{$data["_id"]}}">
                                        @foreach($all_power as $value1)
                                            @if(!in_array("$value1",$power_ids))
                                                <h4><input type="checkbox" name="power_id_array[]"
                                                           value="{{$value1}}">{{$value1}}</h4>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary " type="submit">添加</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">返回</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#add_operator_{{$data["_id"]}}">添加操作员
                    </button>
                    <div class="modal fade" id="add_operator_{{$data["_id"]}}" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">请选择要添加的操作员</h4>
                                </div>
                                <form action="/operator_addOperatorToOperatorPowerGroup" method="post">
                                    <div class="modal-body">

                                        <input type="checkbox" id="CheckedAllOperator"><b>全选/全不选</b>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="group_id" value="{{$data["_id"]}}">
                                        @foreach($all_operator as $value1)

                                            @if(!in_array($value1["_id"],$operator_ids))
                                                <h4><input type="checkbox" name="operator_id_array[]"
                                                           value="{{$value1["_id"]}}">{{$value1["name"]}}</h4>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary " type="submit">添加</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">返回</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


@stop