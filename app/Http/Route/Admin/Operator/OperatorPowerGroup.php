<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/22
 * Time: 10:50
 */

Route::group(["middleware"=>["AdminLoginCheck"]],function() {
    Route::get("/operator_sOperatorPowerGroup", "Admin\Operator\OperatorPowerGroupController@sOperatorPowerGroup");  //zc
    Route::post("/operator_aOperatorPowerGroup", "Admin\Operator\OperatorPowerGroupController@aOperatorPowerGroup");  //zc
    Route::post("/operator_uOperatorPowerGroup", "Admin\Operator\OperatorPowerGroupController@uOperatorPowerGroup");  //zc
    Route::get("/operator_dOperatorPowerGroup/{group_id}", "Admin\Operator\OperatorPowerGroupController@dOperatorPowerGroup");  //zc

    Route::get("/operator_moreOperatorPowerGroup/{group_id}", "Admin\Operator\OperatorPowerGroupController@moreOperatorPowerGroup");  //zc
    Route::post("/operator_removeOperatorToOperatorPowerGroup", "Admin\Operator\OperatorPowerGroupController@removeOperatorToOperatorPowerGroup");
    Route::post("/operator_addOperatorToOperatorPowerGroup", "Admin\Operator\OperatorPowerGroupController@addOperatorToOperatorPowerGroup");
    Route::post("/operator_removePowerToOperatorPowerGroup", "Admin\Operator\OperatorPowerGroupController@removePowerToOperatorPowerGroup");
    Route::post("/operator_addPowerToOperatorPowerGroup", "Admin\Operator\OperatorPowerGroupController@addPowerToOperatorPowerGroup");

});