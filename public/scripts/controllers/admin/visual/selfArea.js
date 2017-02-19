
//1.在 body 里添加一个 SVG 画布
var width = 500;  //画布的宽度
var height = 250;   //画布的高度

var margin = {left:50,top:30,right:20,bottom:20};

var g_width = width - margin.left - margin.right;

var g_height = height - margin.top - margin.bottom;

var svg = d3.select("#container")     //选择文档中的body元素
    .append("svg")          //添加一个svg元素
    .attr("width", width)       //设定画布宽度
    .attr("height", height);       //设定画布高度

var g = d3.select("svg")
    .append("g")
    .attr("transform", "translate("+margin.left+","+margin.top+")"); //g元素向x轴偏移50，向y轴偏移50




var data=[1,3,5,7,8,4,3,7];

//定义比例尺
var scale_x = d3.scale.linear()
    .domain([0,data.length -1])
    .range([0,g_width]);

var scale_y = d3.scale.linear()
    .domain([0,d3.max(data)])
    .range([g_height,0]);


//生成坐标点
var line_generator = d3.svg.line()
    .x(function(d,i){return scale_x(i);})//0,1,2,3
    .y(function(d){return scale_y(d);}) //1,3,5,,
    .interpolate("cardinal"); //让曲线看起来比较光滑

//绘成线
d3.select("g")
    .append("path")
    .attr("d",line_generator(data));  //line_generator会生成类似于M1，0L20，40L40，,,d=path.data


//定义坐标轴
//定义x轴
var x_axis = d3.svg.axis().scale(scale_x);
//定义y轴
var y_axis = d3.svg.axis().scale(scale_y).orient("left"); //方向为朝左



//实现x轴
g.append("g")
    .call(x_axis)
    .attr("transform","translate(0,"+g_height+")");

g.append("g")
    .call(y_axis)
    .append("text")
    .text("price($)")
    .attr("transform","rotate(-90)") //逆时针旋转90度
    .attr("text-anchor","end")    //文字的末尾与纵坐标末尾对齐
    .attr("dy","1em");  //沿着此y轴相应的y轴方向平移一个字体的位置


