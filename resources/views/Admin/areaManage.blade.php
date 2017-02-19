
@extends("Admin.index")

@section("second_nav")
    <div class="col-sm-12 opa">
        <div class="panel panel-default">
            <div class="panel-body shadow_div">
                <h2>区域管理主界面</h2>
            </div>
        </div>
    </div>
@append
@section("left_nav")
@append



@section("main")


    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body shadow_div">
                <h2 class="sub-header">仓库区域 | <button class="btn  btn-success"  data-toggle="modal" data-target="#aAdmin" type="button">添加区域</button></h2>
                <div class="modal fade" id="aAdmin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">添加区域</h4>
                            </div>
                            <div class="modal-body">
                                <form action="/area_aArea" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <h4>区域编号</h4>
                                    <input type="text "  class="form-control" name="area_no" placeholder="area number">
                                    <h4>区域距离</h4>
                                    <input type="text " class="form-control" name="area_distance" placeholder="area distance">
                                    <h4>区域介绍</h4>
                                    <textarea class="form-control" rows="3"  name="area_intro" placeholder="area intro"></textarea>

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
                            <th><span class="glyphicon glyphicon-bookmark">  区域编号</span></th>
                            <th><span class="glyphicon glyphicon-retweet">  区域距离</span></th>
                            <th><span class="glyphicon glyphicon-folder-open">  区域介绍</span></th>
                            <th><span class="glyphicon glyphicon-cog">  操作</span></th>
                        </tr>
                        </thead>
                        <tbody class="container">

                        @foreach ($data as $single)
                            <tr class="container">
                                <td class="container">{{$single["ID"]}}</td>
                                <td class="container">{{$single["no"]}}</td>
                                <td class="container">{{$single["distance"]}}</td>
                                <td class="container">{{$single["intro"]}}</td>


                                <td class="container"><!-- Button trigger modal -->

                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#upd_{{$single["_id"]}}"><span class="glyphicon glyphicon-edit">修改</span></button>

                                    <div class="modal fade" id="upd_{{$single["_id"]}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">当前区域ID：{{$single["ID"]}}</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="/area_uArea" method="post">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="hidden" name="area_id" value="{{$single["_id"]}}">
                                                        <h4>请输入新的区域编号</h4>
                                                        <input class="form-control" name="area_no" value="{{$single["no"]}}">
                                                        <h4>请输入新的区域距离</h4>
                                                        <input class="form-control" name="area_distance" value="{{$single["distance"]}}">
                                                        <h4>请选择新的区域介绍</h4>
                                                        <textarea class="form-control" rows="3"  name="area_intro">{{$single["intro"]}}</textarea>

                                                </div>
                                                <div class="modal-footer">
                                                    <button  class="btn btn-danger btn-sm" type="submit">提交</button>
                                                    </form>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">返回</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#del_{{$single["_id"]}}"><span class="glyphicon glyphicon-trash">删除</span></button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="del_{{$single["_id"]}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">警告！</h4>
                                                </div>
                                                <div class="modal-body">
                                                    将要删除该区域！
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="/area_dArea/{{$single["_id"]}}" class="btn btn-danger btn-sm">删除</a>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">返回</button>

                                                </div>
                                            </div>
                                        </div>
                                    </div></td>
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

