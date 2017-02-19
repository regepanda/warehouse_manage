/**
 * Created by zc on 2016/4/27.
 */
$admin_visualController = $app.controller("admin_visual",function($scope,$location) {

    $scope.goArea = function()
    {
        $location.path("/manage_area");
    };

    $scope.goLog = function()
    {
        $location.path("/manage_log");
    };

    $scope.goHumidity = function()
    {
        $location.path("/manage_humidity");
    };

});

$admin_visualController.config(["$routeProvider",function($routeProvider){
    $routeProvider.when("/manage_area",{
        templateUrl:"/views/admin/areaVisual.html",
        controller:"admin_manage_area"
    }).when("/manage_log",{
        templateUrl:"/views/admin/logVisual.html",
        controller:"admin_manage_log"
    }).when("/manage_humidity",{
        templateUrl:"/views/admin/humidityVisual.html",
        controller:"admin_manage_humidity"
    });
}]);


/**
 *
 */

$admin_visualController.controller("admin_manage_area",function($scope,$timeout){

//  /zc_d3_request /api_D3_getArea
    $scope.getVisual = function()
    {

        d3.json("/api_D3_getArea",function(error,data) {

            var dataset=[];    //dataset里存放货物量
            var areaNames=[];    //areaNames里存放区域名
            var nowNum=[];     //现在占据的货物量

            data.forEach(function(d) {
                dataset.push(d.capacity);
                areaNames.push(d.no);
                nowNum.push(d.nowCapacity);
            });



             //1.在 body 里添加一个 SVG 画布
             var width = 900;  //画布的宽度  400
             var height = 600;   //画布的高度  400
             var svg = d3.select("#container_area")     //选择文档中的body元素
             .append("svg")          //添加一个svg元素
             .attr("width", width)       //设定画布宽度
             .attr("height", height);    //设定画布高度

             //画布周边的空白，避免图形绘制到边界上。
             var padding = {left:50, right:180, top:30, bottom:30};


             //2.定义比例尺

             //x轴的比例尺,序数比例尺
             var xScale = d3.scale.ordinal()
             .domain(d3.range(dataset.length))
            .rangeRoundBands([0,width - padding.left - padding.right]);


            //实际x轴比例尺子
            var xScale2 = d3.scale.ordinal()
                .domain(data.map(function(d){return d.no}))  //map用于合成数组
                .rangeBands([0, width - padding.left - padding.right]);    //0.1为padding


             //y轴的比例尺，线性比例尺
             var yScale = d3.scale.linear()
             .domain([0,d3.max(dataset)])
              .range([height - padding.top - padding.bottom, 0]);



             //3.画布里的每一个矩形

             //矩形之间的空白
             var rectPadding = 40;  //4

            //矩形宽度
              var rect_width = xScale.rangeBand()/2 - rectPadding/2;

             //添加矩形元素(库存量)
             var rects = svg.selectAll(".MyRect")
             .data(dataset)   //绑定数组,数组中的每个元素对应每个标签内的值
             .enter()          //指定选择集的enter部分
             .append("rect")   //添加足够数量的矩形元素
             .attr("class","MyRect")   //若要加入交互效果,则要把css定义的样式清空
             .attr("transform","translate(" + padding.left + "," + padding.top + ")")
             .attr("x", function(d,i){    //2.矩形位置，左上角的x坐标
             return xScale(i) + rectPadding/2;
             } )
             .attr("y",function(d){         //2.矩形位置，左上角的y坐标 3.d 代表与当前元素绑定的数据(相应标签内的值)，i 代表索引号
             return yScale(d);
             })
             .attr("width", xScale.rangeBand()/2 - rectPadding/2 )  //3.矩形的大小，矩形的宽度
             .attr("height", function(d){                       //3.矩形的大小，矩形的高度
             return height - padding.top - padding.bottom - yScale(d);
             })
             .attr("fill","steelblue")       //填充颜色不要写在CSS里

             .on("mouseover",function(d,i){     //加入交互效果，mouseover 监听器函数的内容为：将当前元素变为黄色
             d3.select(this)     //选择当前元素
             .attr("fill","yellow");
             })
             .on("mouseout",function(d,i){    //mouseout 监听器函数的内容为：缓慢地将元素变为原来的颜色（蓝色）
             d3.select(this)
             .transition()
             .duration(500)
             .attr("fill","steelblue");
             });


             //添加文字元素（库存量）
             var texts = svg.selectAll(".MyText")
             .data(dataset)   //绑定数组,数组中的每个元素对应每个标签内的值
             .enter()           //指定选择集的enter部分
             .append("text")
           //  .attr("class","MyText")
             .attr("transform","translate(" + padding.left + "," + padding.top + ")")
             .attr("x", function(d,i){
             return xScale(i) ;
             } )
             .attr("text-anchor","middle")   //让文本居中显示
             .attr("y",function(d){                 //y轴方向的动画效果
             var min = yScale.domain()[0];
             return yScale(min);
             })
             .transition()
             .delay(function(d,i){
             return i * 200;
             })
             .duration(2000)
             .ease("bounce")

             .attr("y",function(d){
             return yScale(d);
             })
             .attr("dx",function(){
             return (xScale.rangeBand() - rectPadding)/2-padding.left/2+12;
             })
             .attr("dy",function(d){
             return 20;
             })
             .text(function(d){
             return d;
             });



            //添加矩形元素(当前库存量)

                    svg.selectAll(".second")
                        .data(nowNum)   //绑定数组,数组中的每个元素对应每个标签内的值
                        .enter()
                        .append("rect")   //添加足够数量的矩形元素
                       // .attr("class", "second")   //若要加入交互效果,则要把css定义的样式清空
                        .attr("transform", "translate(" + padding.left + "," + padding.top + ")")
                        .attr("x", function (d, i) {    //2.矩形位置，左上角的x坐标
                            return xScale(i) + rectPadding/2 + rect_width;
                        })
                        .attr("y", function (d) {         //2.矩形位置，左上角的y坐标 3.d 代表与当前元素绑定的数据(相应标签内的值)，i 代表索引号
                            return yScale(d);
                        })
                        .attr("width", xScale.rangeBand() / 2 - rectPadding / 2)  //3.矩形的大小，矩形的宽度
                        .attr("height", function (d) {                       //3.矩形的大小，矩形的高度
                            return height - padding.top - padding.bottom - yScale(d);
                        })
                        .attr("fill", "#1DE9B6")       //填充颜色不要写在CSS里
                        .on("mouseover", function (d, i) {     //加入交互效果，mouseover 监听器函数的内容为：将当前元素变为黄色
                            d3.select(this)     //选择当前元素
                                .attr("fill", "blueviolet");
                        })
                        .on("mouseout", function (d, i) {    //mouseout 监听器函数的内容为：缓慢地将元素变为原来的颜色（蓝色）
                            d3.select(this)
                                .transition()
                                .duration(500)
                                .attr("fill", "#1DE9B6");
                        });



            //添加文字元素（当前库存量）

              svg.selectAll(".secondR")
                .data(nowNum)   //绑定数组,数组中的每个元素对应每个标签内的值
                .enter()           //指定选择集的enter部分
                .append("text")
                .attr("class","secondR")
                .attr("transform","translate(" + padding.left +"," + padding.top + ")")
                .attr("x", function(d,i){
                    return xScale(i)  + rect_width;
                } )
                .attr("text-anchor","middle")   //让文本居中显示
                .attr("y",function(d){                 //y轴方向的动画效果
                    var min = yScale.domain()[0];
                    return yScale(min);
                })
                .transition()
                .delay(function(d,i){
                    return i * 200;
                })
                .duration(2000)
                .ease("bounce")

                .attr("y",function(d){
                    return yScale(d);
                })
                .attr("dx",function(){
                    return (xScale.rangeBand() - rectPadding)/2 - padding.left/2+12;
                })
                .attr("dy",function(d){
                    return 15;
                })
                .text(function(d){
                    return d;
                });



            //颜色标识

            var bs_width = 30;  //矩形的宽度
            var bs_height = 20; //矩形的高度

              //"库存量"颜色标识
                  var bs_x = width-padding.right+40; //矩形x坐标
                  var bs_y = 10; //矩形y坐标
            svg.append("rect")
                .attr("x", bs_x)
                .attr("y", bs_y)
                .attr("width", bs_width)  //3.矩形的大小，矩形的宽度
                .attr("height", bs_height)
                .attr("fill", "steelblue");       //填充颜色不要写在CSS里

              //"库存量"颜色标识说明
              var d_x = width-padding.right+70;
              var d_y = 25;
            svg.append("text")
                .text("区域库存量")
                .attr("x", d_x)
                .attr("y", d_y);

             //"区域当前货物量"颜色标识
            svg.append("rect")
                .attr("x", bs_x)
                .attr("y", bs_y + 30)
                .attr("width", bs_width)  //3.矩形的大小，矩形的宽度
                .attr("height", bs_height)
                .attr("fill", "#1DE9B6");

            //"区域当前货物量"颜色标识说明
            svg.append("text")
                .text("区域当前货物量")
                .attr("x", d_x)
                .attr("y", d_y+30);


            //4.定义坐标轴，名为axis

             //定义x轴
             var xAxis = d3.svg.axis()
             .scale(xScale2)      //4.0.1  指定比例尺
             .orient("bottom");  //4.0.2  指定刻度的方向

             //定义y轴
             var yAxis = d3.svg.axis()
             .scale(yScale)
             .orient("left");


             //4.1 调用定义的坐标轴，显示到画布上

             //添加x轴

            var quyum = width- padding.right - padding.left;  //“区域名” 在x轴的偏移

             svg.append("g")
             .attr("class","axis")  //4.1.1 调用定义的坐标轴样式
             .attr("transform","translate(" + padding.left + "," + (height - padding.bottom) + ")") //4.1.2 设置坐标轴位置 ，一参为横坐标，二参为纵坐标
             .call(xAxis)
             .append("text")
              .text("区域名")
              .attr("transform","rotate(0)") //逆时针旋转90度
             .attr("text-anchor","start")    //文字的末尾与纵坐标末尾对齐
             .attr("dx",quyum);  //沿着此y轴相应的y轴方向平移一个字体的位置

             //添加y轴
             svg.append("g")
             .attr("class","axis")
             .attr("transform","translate(" + padding.left + "," + padding.top + ")")
             .call(yAxis)
             .append("text")
             .text("货物量(单位：件)")
             .attr("transform","rotate(-90)") //逆时针旋转90度
             .attr("text-anchor","end")    //文字的末尾与纵坐标末尾对齐
             .attr("dy","1em");  //沿着此y轴相应的y轴方向平移一个字体的位置
        });


    };

    $timeout(function()
    {
        $scope.getVisual();
    },500);





});








