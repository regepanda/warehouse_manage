@extends("Admin.powerManage")


@section("main")

    <div class="col-sm-10">
        <div class="panel panel-default">
            <div class="panel-body shadow_div">
                <h2 class="sub-header">操作员 | <button class="btn  btn-success"  data-toggle="modal" data-target="#aAdmin" type="button">添加操作员</button></h2>
                <div class="modal fade" id="aAdmin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">添加操作员</h4>
                            </div>
                            <div class="modal-body">
                                <form action="/operator_aOperator" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <h4>操作员用户名</h4>
                                    <input type="text "  class="form-control" name="operator_username" placeholder="operator username">
                                    <h4>操作员名</h4>
                                    <input type="text " class="form-control" name="operator_name" placeholder="operator name">
                                    <h4>操作员指纹数据</h4>
                                    <input type="text " class="form-control" name="operator_finger_key" placeholder="operator finger">
                                    <h4>操作员RFID数据</h4>
                                    <input type="text " class="form-control" name="operator_rfid_key" placeholder="operator rfid">
                                    <h4>密码</h4>
                                    <input type="password" class="form-control" name="operator_password" placeholder="operator password">
                                    <h4>所属操作员组</h4>
                                    <select class="form-control" name="operator_group">
                                        @foreach($groupData as $value)
                                            <option type="select" value="{{$value["_id"]}}" selected="selected">{{$value["name"]}}</option>
                                        @endforeach
                                    </select>

                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-sm btn-primary" type="submit">提交</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">返回</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>


                <div class="table-responsive">

                    <table class="table table-striped" class="table table-hover" >
                        <thead class="container">
                        <tr class="container">
                            <th><span class="glyphicon glyphicon-tag">  ID</span></th>
                            <th><span class="glyphicon glyphicon-zoom-in">  操作员用户名</span></th>
                            <th><span class="glyphicon glyphicon-leaf">  操作员名</span></th>
                            <th><span class="glyphicon glyphicon-indent-right">  所属操作员组</span></th>
                            <th><span class="glyphicon glyphicon-cog">  操作</span></th>
                        </tr>
                        </thead>
                        <tbody class="container">

                        @foreach ($data as $single)
                            <tr class="container">
                                <td class="container">{{$single["face_key"]}}</td>
                                <td class="container">{{$single["username"]}}</td>
                                <td class="container">{{$single["name"]}}</td>
                                <td class="container">
                                @foreach($groupData as $value)
                                    @if($value["_id"] == $single["group"])
                                       {{$value["name"]}}
                                   @endif
                                @endforeach
                                </td>

                                <td class="container"><!-- Button trigger modal -->

                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#add_image_{{$single["_id"]}}"><span class="glyphicon glyphicon-arrow-up">上传操作员图片</span></button>
                                    <div class="modal fade" id="add_image_{{$single["_id"]}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                                aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">添加图片</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" action="/operator_aOperatorImage" enctype="multipart/form-data">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="hidden" name="operator_id" value="{{$single["_id"]}}">

                                                        <div class="form-group">
                                                            <label>请选择你要上传的图片文件</label>
                                                            <input type="file" id="exampleInputFile" name="operator_image">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>请输入图片名</label>
                                                            <?php echo "<br/>"; ?>
                                                            <input type="text" class="form-control" name="image_name">
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">提交</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">返回</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>



                                    <a type="button" href="/operator_sOperatorImage/{{$single["_id"]}}" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-search">图片查看</span></a>

                                    <butto type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#upd_{{$single["_id"]}}"><span class="glyphicon glyphicon-edit">修改</span></butto>

                                    <div class="modal fade" id="upd_{{$single["_id"]}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">当前操作员ID：{{$single["face_key"]}}</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="/operator_uOperator" method="post">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="hidden" name="operator_id" value="{{$single["_id"]}}">
                                                        <h4>请输入新的操作员用户名</h4>
                                                        <input class="form-control" name="operator_username" value="{{$single["username"]}}">
                                                        <h4>请输入新的操作员名</h4>
                                                        <input class="form-control" name="operator_name" value="{{$single["name"]}}">
                                                        <h4>请输入新的操作员指纹数据</h4>
                                                        <input type="text " class="form-control" name="operator_finger_key" value="{{$single["finger_key"]}}">
                                                        <h4>请输入新的操作员RFID数据</h4>
                                                        <input type="text " class="form-control" name="operator_rfid_key" value="{{$single["rfid_key"]}}">
                                                        <h4>请选择新的操作员组</h4>
                                                        <select class="form-control" name="operator_group">
                                                            @foreach($groupData as $value)
                                                                @if($value["_id"] == $single["group"])
                                                                    <option type="select" value="{{$value["_id"]}}" selected="selected">{{$value["name"]}}</option>
                                                                @else
                                                                    <option type="select" value="{{$value["_id"]}}">{{$value["name"]}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button  class="btn btn-danger btn-sm" type="submit">提交</button>
                                                    </form>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">返回</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#del_{{$single["_id"]}}"><span class="glyphicon glyphicon-trash">删除</span></button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="del_{{$single["_id"]}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">警告！</h4>
                                                </div>
                                                <div class="modal-body">
                                                    将要删除该操作员！
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="/operator_dOperator/{{$single["_id"]}}" class="btn btn-danger btn-sm">删除</a>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">返回</button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>

                    <hr>

                </div>
            </div>
        </div>
    </div>






@stop

