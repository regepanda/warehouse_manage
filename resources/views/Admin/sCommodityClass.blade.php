@extends("Admin.commodityManage")


@section("main")

    <div class="col-sm-8">
        <div class="panel panel-default">
            <div class="panel-body shadow_div">
                <h2 class="sub-header">商品类型 | <button class="btn  btn-success"  data-toggle="modal" data-target="#aAdmin" type="button">添加商品类型</button></h2>
                <div class="modal fade" id="aAdmin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">添加商品类型</h4>
                            </div>
                            <div class="modal-body">
                                <form action="/commodity_manage_aCommodityClass" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <h4>商品类型名</h4>
                                    <input type="text " id="inputText" class="form-control" name="commodity_class_name" placeholder="commodity class name">
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
                            <th><span class="glyphicon glyphicon-eye-open">  商品类型名</span></th>
                            <th><span class="glyphicon glyphicon-cog">  操作</span></th>
                        </tr>
                        </thead>
                        <tbody class="container">

                        @foreach ($data as $single)
                            <tr class="container">
                                <td class="container">{{$single["_id"]}}</td>
                                <td class="container">{{$single["name"]}}</td>
                                <td class="container"><!-- Button trigger modal -->

                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#upd_{{$single["_id"]}}"><span class="glyphicon glyphicon-edit">修改</span></button>

                                    <div class="modal fade" id="upd_{{$single["_id"]}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">当前商品类型ID：{{$single["_id"]}}</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="/commodity_manage_uCommodityClass" method="post">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="hidden" name="commodity_class_id" value="{{$single["_id"]}}">
                                                        <h4>请输入新的商品类型名</h4>
                                                        <input class="form-control" name="commodity_class_name" value="{{$single["name"]}}">
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
                                                    将要删除该商品！
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="/commodity_manage_dCommodityClass/{{$single["_id"]}}" class="btn btn-danger btn-sm">删除</a>
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