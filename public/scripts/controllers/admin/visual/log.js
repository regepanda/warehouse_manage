$admin_visualController.controller("admin_manage_log",function($scope,$timeout){



    $scope.getVisual = function()
    {

// /zc_d3_requestEn /api_D3_getEnvironment


        d3.json("/api_D3_getEnvironment",function(error,data) {


                var time = [];  //时间数组
                var temp = [];  //温度数组
                var humi = [];  //湿度数组

            //截取请求数据的前10个
            if(data.length > 10)
            {
                data = data.slice(0,10);
            }

            data.forEach(function(d) {

                time.push(d.ID);
                temp.push(d.temperature);
                humi.push(d.humidity);
            });

            //1.在 body 里添加一个 SVG 画布
            var width = 900;  //画布的宽度
            var height = 800;   //画布的高度

            var margin = {left:50,top:60,right:50,bottom:130};

            var g_width = width - margin.left - margin.right;

            var g_height = height - margin.top - margin.bottom;

            var svg = d3.select("#container")     //选择文档中的body元素
                .append("svg")          //添加一个svg元素
                .attr("width", width)       //设定画布宽度
                .attr("height", height);       //设定画布高度

            var g = d3.select("svg")
                .append("g")
                .attr("transform", "translate("+margin.left+","+margin.top+")"); //g元素向x轴偏移50，向y轴偏移50


            var data2 = temp;

            //定义比例尺

            //实际x轴比例尺子
            var scale_x = d3.scale.ordinal()
               // .domain(data.map(function(d){return d.ID}))  //map用于合成数组
                .domain(time)
                .rangeBands([0, g_width],1);    //0.1为padding


            var scale_y = d3.scale.linear()
                .domain([-20,d3.max(data2)])
                .range([g_height,0]);


            //曲线构造器提供path.d，生成坐标点
            var line_generator = d3.svg.line()
                .x(function(d){return scale_x(d.ID);})//0,1,2,3
                .y(function(d){return scale_y(d.temperature);}) //1,3,5,,
                .interpolate("cardinal"); //让曲线看起来比较光滑

            //绘成线
            d3.select("g")
                .append("path")
                .attr("d",line_generator(data));  //line_generator会生成类似于M1，0L20，40L40，,,d=path.data


            //定义坐标轴
            //定义x轴
            var x_axis = d3.svg.axis().scale(scale_x);
            //定义y轴
            var y_axis = d3.svg.axis().scale(scale_y).orient("left").ticks(30); //方向为朝左


            var quyum = width- margin.right - margin.left+30;  //“区域名” 在x轴的偏移

            //实现x轴
            g.append("g")
                .call(x_axis)
                .attr("transform","translate(0,"+g_height+")")
                .append("text")
                .text("时间")
                .attr("transform","rotate(0)") //逆时针旋转90度
                .attr("text-anchor","end")    //文字的末尾与纵坐标末尾对齐
                .attr("dx",quyum);

            g.append("g")
                .call(y_axis)
                .append("text")
                .text("温度(c)")
                .attr("transform","rotate(-90)") //逆时针旋转90度
                .attr("text-anchor","end")    //文字的末尾与纵坐标末尾对齐
                .attr("dy","1em");  //沿着此y轴相应的y轴方向平移一个字体的位置


            //"温度与时间关系图"文本
            var d_x = width/2 - margin.left;
            var d_y = 25;
            svg.append("text")
                .text("最近温度与时间关系")
                .attr("x", d_x)
                .attr("y", d_y);



            //定义横轴网格线
            var xInner = d3.svg.axis()
                .scale(scale_x)
                .tickSize(-(g_height),0,0)  //画布高度-top编剧-bottom编剧
                .orient("bottom")
                .ticks(time.length);  //x轴数据长度
            //添加横轴网格线
            svg.append("g")
                .attr("class","inner_line")
                .attr("transform", "translate("+margin.left+ "," + (height-margin.bottom) + ")")
                .call(xInner)
                .style("stroke-opacity",0.2)  //zc
                .selectAll("text")
                .text("");


            //定义纵轴网格线
            var yInner = d3.svg.axis()
                .scale(scale_y)
                .tickSize(-(g_width),0,0)  //画布宽度-左右边距
                .tickFormat("")
                .orient("left")
                .ticks(20);
            //添加纵轴网格线
            var yBar=svg.append("g")
                .attr("class", "inner_line")
                .attr("transform", "translate("+margin.left+","+margin.top+")")
                .style("stroke-opacity",0.2)
                .call(yInner);

            //添加系列的小圆点
            svg.selectAll("circle")
                .data(data)
                .enter()
                .append("circle")
                .attr("cx", function(d) {
                    return scale_x(d.ID) + margin.left;
                })
                .attr("cy", function(d) {
                    return scale_y(d.temperature) + margin.top;
                })
                .transition()
                .delay(function(d,i){
                    return i * 200;
                })
                .duration(2000)
                .ease("bounce")
                .attr("r",5)
                .attr("fill","#09F")
        });
    };


    $timeout(function()
    {
        $scope.getVisual();
    },500);


});