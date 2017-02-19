@extends("Admin.powerManage")


@section("main")


    <div class="col-sm-10">
        <div class="panel panel-default">
            <div class="panel-body shadow_div">
                <div id="pageView">
                    <h2>查看操作员图片 | 操作员id:{{$data[0]["operator"]}}

                    </h2>

                    <div class="col-sm-10">
                        @foreach($data as $single)
                            <div class="col-sm-3">
                                <div class="thumbnail">
                                    <img src="/getImage/{{ $single["_id"] }}" class="img-rounded img-responsive"
                                         alt="..." style="width: 280px;height: 200px">

                                    <div class="caption">
                                        <h3>图片ID：{{ $single["ID"] }}</h3>
                                        <h3>图片名：{{ $single["name"] }}</h3>
                                        <div>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#del_{{$single["_id"]}}"><span class="glyphicon glyphicon-trash">删除</span></button>
                                           @if($single["practice"] == false)
                                            <a href="/operator_practiceOperatorImage/{{$single["_id"]}}" type="button" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-trash">训练</span><a/>
                                            @endif
                                                <a href="/operator_practiceAgainOperatorImage/{{$single["_id"]}}" type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-trash">重新训练</span><a/>

                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Modal -->
                            <div class="modal fade" id="del_{{$single["_id"]}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">警告！</h4>
                                        </div>
                                        <div class="modal-body">
                                            将要删除该图片！
                                        </div>
                                        <div class="modal-footer">
                                            <a href="/operator_dOperatorImage/{{$single["_id"]}}" class="btn btn-danger btn-sm">删除</a>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">返回</button>

                                        </div>
                                    </div>
                                </div>
                            </div>


                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>






@stop