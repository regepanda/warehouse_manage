<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/22
 * Time: 10:42
 */
Route::group(["middleware"=>["AdminLoginCheck"]],function() {
    Route::get("/operator_sOperator", "Admin\Operator\OperatorController@sOperator");
    Route::post("/operator_aOperator", "Admin\Operator\OperatorController@aOperator");
    Route::post("/operator_aOperatorImage", "Admin\Operator\OperatorController@aOperatorImage");
    Route::post("/operator_uOperator", "Admin\Operator\OperatorController@uOperator");
    Route::get("/operator_dOperator/{operator_id}", "Admin\Operator\OperatorController@dOperator");

    Route::get("/operator_sOperatorImage/{operator_id}", "Admin\Operator\OperatorController@sOperatorImage");
    Route::get("/operator_dOperatorImage/{image_id}", "Admin\Operator\OperatorController@dOperatorImage");
    //获取图片
    Route::get("/getImage/{image_id}", "Admin\Operator\OperatorController@getImage");
    //训练，交由服务器处理图片
    Route::get("operator_practiceOperatorImage/{image_id}", "Admin\Operator\OperatorController@practiceOperatorImage");
    //重新训练
    Route::get("operator_practiceAgainOperatorImage/{image_id}", "Admin\Operator\OperatorController@practiceAgainOperatorImage");


});
