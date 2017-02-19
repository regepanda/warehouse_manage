<?php
/**
 * Created by PhpStorm.
 * User: zc
 * Date: 2016/4/25
 * Time: 11:05
 */
Route::group(["middleware"=>["AdminLoginCheck"]],function() {
    Route::get("/area_sArea", "Admin\AreaController@sArea");
    Route::post("/area_aArea", "Admin\AreaController@aArea");
    Route::post("/area_uArea", "Admin\AreaController@uArea");
    Route::get("/area_dArea/{area_id}", "Admin\AreaController@dArea");
});