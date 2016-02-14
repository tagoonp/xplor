var originX = 500;
    var originY = 250;
    var width = 1000, height = 1000;
    var innerCircleRadius = 100;
    var outerCircleRadius = 250;
    var outerCircleRadius2 = 270;
    // var outcome = new Array("csar","lbw","pret");
    var outcomeList = ["csar","lbw","pret"];
    // var outcomeList = ["csar","lbw"];
    var paramColor = ["#59BED1","#A4AAAD","#F50030"]; //Parametor symbol color : sig, not-sig, outcome
    var paramList = ["age_e", "rel", "edu", "dm", "ht", "hd", "grav", "para", "abor", "anc", "ga1st", "noanc", "tyl", "hiv", "syp", "hep", "prot", "sbpad", "dbpad", "pr", "fhr", "labora", "gaadm", "gadel", "moddel","typa","bll2h"];
    // var paramList = ["age_e", "rel", "edu", "dm", "ht", "hd", "grav", "para", "abor", "anc", "ga1st", "noanc", "tyl", "hiv", "syp", "hep", "prot", "sbpad"];
    var outcomeValue = [
    [0.042,0.001,0.003],
    [0.039,0.261,0.351],
    [0.955,0.411,0.992],
    [0.433,0.411,0.821],
    [0.939,0.825,0.567],
    [0.370,0.860,0.794],
    [0.290,0.319,0.042],
    [0.876,0.195,0.528],
    [0.491,0.639,0.404],
    [0.046,0.031,0.493],
    [0.164,0.784,0.822],
    [0.787,0.851,0.578],
    [0.188,0.530,0.108],
    [0.918,0.876,0.836],
    [0.986,0.876,0.251],
    [0.191,0.54,0.422],
    [0.001,0.097,0.116],
    [0.287,0.345,0.187],
    [0.751,0.474,0.448],
    [0.835,0.014,0.497],
    [0.281,0.978,0.504],
    [0.797,0.411,0.481],
    [0.894,0.827,0.537],
    [0.797,0.001,0.001],
    [0.001,0.958,0.060],
    [0.036,0.094,0.005],
    [0.001,0.251,0.006]
    // [1.0,0.449,0.271],
    // [0.276,1.0,0.001],
    // [0.109,0.001,1.0]
    ];
    var chairWidth = 20;
    var chair;
    var input = paramList.length , outcome = outcomeList.length;
    var allParam = 0;
    var eachAngle = 0;
    var textLb;
    var m0;
    var body;
    var svg,div,ocPos,widthSize, circumference = 0;
    var caseofposition = 'a';
    var lur,sur; //Radious of unit: largr and small
    var outcomeCheck = 0;
    var container;
    var rotate = 0;
    var coordinateOutcome = [], coordinateParam = [], elementParam = [];

    $(document).ready(function(){

      $('.main').css("height",$(document).height() - 50 + "px");
      $('.main').css("width",($(document).width() - 300) + "px");

      width = $('.main').width();
      height = $('.main').height() - 50;

      outerCircleRadius = (height/2) - 100 - (chairWidth/2) - chairWidth;
      outerCircleRadius2 = (height/2) - 80;
      allParam = input + outcome;
      eachAngle = 360/allParam;

      ocPos = ((input/outcome) + 1).toFixed(0);

      if((input%outcome)!=0){
        caseofposition = 'b';
      }

      //Check width of screen
      if(width>height){
           widthSize = (((height/2)/2)+100);
      }else{
           widthSize = (((width/2)/2)+100);
      }

      originX = width/2;
      originY = height/2;

      //คำนวนเส้นรอบวงกลม
      circumference = (2 * Math.PI * widthSize).toFixed(2);
      lur = ((circumference/allParam)/2) * 0.8;
      sur = ((circumference/allParam)/2) * 0.4;

      outerCircleRadius2 = outerCircleRadius2 + (sur/2);

      div = d3.select("body").insert("div", "h2")
        .style("top", "50px")
        .style("left", "270px")
        .style("bottom", "10px")
        .style("width", width + "px")
        .style("height", height + "px")
        .style("position", "absolute")
        .style("-webkit-backface-visibility", "hidden")
        .on("mousemove", mousemove)
        .on("mousedown", mousedown)
        .on("mouseup", mouseup);

      init();
    });

    function init(){
     svg = div.append("svg:svg")
          .attr("width",width + "px")
          .attr("height",height + "px")
          .attr("fill","blue")
          .attr("transform", "translate(" + originX + "," + originY + ")")

     container = svg.append("g")

      var lbOutcome = 0, lbInputnum = 0;
      var c = 1;
      //แต่ละองศาจากการคำนวน นำไปสร้างวงกลม และ label ของวงกลม
      for(i = 0; i <= 360; i = (i+eachAngle)){
        var chairOriginX = outerCircleRadius * Math.cos(i * Math.PI / 180) + originX;
        var chairOriginY = outerCircleRadius * Math.sin(i * Math.PI / 180) + originY;
        var textOriginX = outerCircleRadius2 * Math.cos(i * Math.PI / 180) + originX;
        var textOriginY = outerCircleRadius2 * Math.sin(i * Math.PI / 180) + originY;

        var ccolor = paramColor[1];
        var rs = 10;
        var numCoutcome = 0;
        var label = "none";

        if(outcomeCheck==0){
          ccolor = paramColor[2];
          rs = lur;
          numCoutcome++;
          label = outcomeList[lbOutcome];
          lbOutcome++;
        }else{
          rs = sur;
          ccolor = paramColor[1];
          label = paramList[lbInputnum];
          lbInputnum++;

          if(outcomeCheck==ocPos){
            if(numCoutcome!=outcome){
              ccolor = paramColor[2];
              rs = lur;
              numCoutcome++;
              label = outcomeList[lbOutcome];
              lbOutcome++;
              lbInputnum--;
            }else{
              rs = sur;
              ccolor = paramColor[1];
              numCoutcome++;
            }
            outcomeCheck = 0; //Reset
          }
        }

        var coor = [];
        var element = [];
        var cordata = chairOriginX + ":" +chairOriginY;
        coor.push(cordata);
        // coor.push(chairOriginY);
        var elmIs = 'p';
        if(ccolor==paramColor[2]){
          elmIs = 'o';
        }

        if(c<=allParam){
          //เรียกใช้ฟังก์ชันสำหรับสร้างวงกลม
          element.push(chairOriginX);
          element.push(chairOriginY);
          element.push(rs);
          element.push(ccolor);
          element.push(elmIs);
          elementParam.push(element);
          // createCircle(chairOriginX, chairOriginY, rs, ccolor);

          if(rs==lur){
               coordinateOutcome.push(coor);
          }else{
               coordinateParam.push(coor);
          }
        }



        //เรียกใช้ฟังก์ชันสำหรับสร้าง label ของวงกลม
        createCircleLabel(svg, textOriginX, textOriginY, Math.abs(i), label, outcomeCheck, rs);
        outcomeCheck++;
        c++;
      }//End for

      for(var i = 0; i < coordinateParam.length ; i++){
          createLine(String(coordinateParam[i]), 0, i);
      }

      for(var i = 0; i < (coordinateOutcome.length) ; i++){
          // createLine2(String(coordinateOutcome[i]), 0, i);
      }

      for(var i = 0; i < elementParam.length ; i++){
        // if((elementParam[i][4]=='o') && (i!=0)){
        //   i--;
        // }
          createCircle(elementParam[i][0], elementParam[i][1], elementParam[i][2], elementParam[i][3], elementParam[i][4], i);
      }

    }

    var maxWidth = 8;

    //สรา้งเส้นความสัมพันธ์
    function createLine(paramPoint, rs, index){
      var p1 = paramPoint.split(":");

      var value = outcomeValue[index];


      for(var i = 0; i < coordinateOutcome.length ; i++){
        var p2 = String(coordinateOutcome[i]).split(":");

        if(value[i]<=0.05){
          var SigLevel = 8 - ((value[i] * maxWidth)/0.05);

          linkLine = container.append("line")
                .attr("x1", p1[0]-rs)
                .attr("y1", p1[1]-rs)
                .attr("x2", p2[0]-rs)
                .attr("y2", p2[1]-rs)
                .attr("stroke-width", SigLevel)
                .attr("stroke", "#888")
        }else{
          // linkLine = container.append("line")
          //       .attr("x1", p1[0]-rs)
          //       .attr("y1", p1[1]-rs)
          //       .attr("x2", p2[0]-rs)
          //       .attr("y2", p2[1]-rs)
          //       .attr("stroke-width", 1)
          //       .attr("stroke", "#ccc")
          //       .style("stroke-dasharray", ("5, 5"))  // <== This line here!!
        }
      } //End for
    }

    var checkParamIndex = 0;
    //สรา้งวงกลม
    function createCircle(chairOriginX, chairOriginY, rs, ccolor, elmIs, index){
      if(index==0){
        checkParamIndex = index;
      }

      var value2;
      var color = ccolor;
      var check = 0;
      console.log(elmIs);

      if(elmIs=='p'){
        value2 = outcomeValue[checkParamIndex];
        checkParamIndex++;
      }

      if(ccolor!=paramColor[2]){
        for(i = 0; i < value2.length; i++){
            if(value2[i]<=0.05){
              check++;
            }
        }

        if(check!=0){
          color = paramColor[0];
        }else{
          color = paramColor[1];
        }
      }

      if (elmIs=='p') {
        var chair = container.append("circle")
        .attr("cx", originX)
        .attr("cy", originY)
        .attr("r", rs)
        .attr("fill", color)
        .attr("stroke","none")
        .transition()
        .attr("cx", chairOriginX)
        .attr("cy", chairOriginY)
        .duration(1000)
        .delay(100)
        .ease("elastic");
      }else{
        var chair = container.append("circle")
        .attr("cx", originX)
        .attr("cy", originY)
        .attr("r", rs)
        .attr("fill", ccolor)
        .attr("stroke","none")
        .attr("cursor","pointer")
        .on("mouseover", mouseover2)
        .on("mouseout", mouseout2)
        .transition()
        .attr("cx", chairOriginX)
        .attr("cy", chairOriginY)
        .duration(1000)
        .delay(100)
        .ease("elastic");
      }
    }

    //สร้าง Label ของวงกลมแต่ละวง
    function createCircleLabel(svg, chairOriginX, chairOriginY, angles, label, outcomeCheck, rs){
      var fsize = "15px";
      var outcomePosition = rs + rs;
      if(outcomeCheck==0){
        outcomePosition = rs;
        fsize = "25px";
      }

      var textLb = container.append("svg:text")
        .attr("dx", chairOriginX - (chairWidth / 2) + ((chairWidth )) - outcomePosition)
        .attr("dy", chairOriginY - (chairWidth / 2) + ((chairWidth )) - 6)
        .attr("font-family", "sans-serif")
        .attr("font-size", fsize)
        .attr("fill", "black")
        .attr("text-anchor", function(d) { var a = angles; if(a < 180){ return "start" ; }else{return "start";} })
        .attr("transform", function(d) { var a = angles; if(a < 180){ return "rotate(" + (0 + angles) + ", " + (chairOriginX) + ", " + (chairOriginY) + ")"; }else{return  "rotate(" + (0 + angles)  + ", " + (chairOriginX) + ", " + (chairOriginY) + ")";} })
        .transition()
        .attr("cx", chairOriginX)
        .attr("cy", chairOriginY)
        .duration(1600)
        .delay(100)
        .ease("elastic")
        .text(label);

    }

    function mouse(e) {
      return [e.pageX - originX , e.pageY - originY ];
    }

    function mouse2(e) {
      return [e.pageX - 270, e.pageY - 50];
    }

    function mousedown() {
      m0 = mouse(d3.event);
      d3.event.preventDefault();
    }

    function mousemove() {
      if (m0) {
        var m1 = mouse(d3.event),
            dm = Math.atan2(cross(m0, m1), dot(m0, m1)) * 180 / Math.PI;
            rotate += dm;

            container.attr("transform", " rotate(" + (rotate) + ", " + originX + " , " + originY + ")");
      }
    }

    function mouseup() {
      if (m0) {
        var m1 = mouse(d3.event),
            dm = Math.atan2(cross(m0, m1), dot(m0, m1)) * 180 / Math.PI;

        rotate += dm;
        if (rotate > 360) rotate -= 360;
        else if (rotate < 0) rotate += 360;
        m0 = null;
        div.style("-webkit-transform", null);

        svg
            .attr("transform", "translate(" + originX + "," + originY + ")rotate(" + rotate + ")")
      }
    }

    function mouseout2() {
      svg.selectAll("rect").remove();
    }

    function mouseover2() {
      var md = mouse2(d3.event);
      // alert(md[0] + ' ' + md[1]);

      var tooltip = d3.select("svg")
        .append("rect")
        .attr("x", md[0] )
        .attr("y", md[1] - 50)
        .attr("width", "200px")
        .attr("height", "100px")
        .style("border", "solid")
        .attr("stroke-width", "2px")
        .attr("rx","10")
        .attr("ry","10")
        .style("border-radius", "5px")
        .attr("opacity", "0.8")
        .style("stroke", "#ccc")
        .attr("fill","#fff");

      var textText = tooltip.append("svg:text")
        // .attr("dx", chairOriginX - (chairWidth / 2) + ((chairWidth )) - outcomePosition)
        // .attr("dy", chairOriginY - (chairWidth / 2) + ((chairWidth )) - 6)
        .attr("font-family", "sans-serif")
        .attr("font-size", fsize)
        .attr("fill", "black")
        // .attr("text-anchor", function(d) { var a = angles; if(a < 180){ return "start" ; }else{return "start";} })
        // .attr("transform", function(d) { var a = angles; if(a < 180){ return "rotate(" + (0 + angles) + ", " + (chairOriginX) + ", " + (chairOriginY) + ")"; }else{return  "rotate(" + (0 + angles)  + ", " + (chairOriginX) + ", " + (chairOriginY) + ")";} })
        // .transition()
        // .attr("cx", chairOriginX)
        // .attr("cy", chairOriginY)
        // .duration(1600)
        // .delay(100)
        // .ease("elastic")
        .text("asd");


      // var svgDetail = svg.append("circle")
      //     .attr("cx", md[0])
      //     .attr("cy", md[1])
      //      .attr("width", "200px")
      //      .attr("height", "100px")
      //      .attr("fill","blue")
          //  .attr("transform", "translate(" + md[0] + "," + md[1] + ")")


      d3.event.preventDefault();
    }

    function cross(a, b) {
      return a[0] * b[1] - a[1] * b[0];
    }

    function dot(a, b) {
      return a[0] * b[0] + a[1] * b[1];
    }
