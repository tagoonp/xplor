var ceasar = 555;
var lbw = 208;
var pret = 151;
var outcomeList = ["CSAR", "LBW", "PRET"];

var coordinateOutcome = [];
var coordinateParam = [];
var cirData = [];
var angle = [];

var numofParam_original = 27;
var outcome_origin = 3;

var w;
var h;
var bodySelection;
var svgSelection;
var linkLine;

$(document).ready(function(){

  w = $('.chartResult-panel').width();
  h = $('.chartResult-panel').height();

  // bodySelection = d3.select(".chartResult-panel");
  $('#btnRun').click(function(){
    if($('#methodSelection').val()=='0'){
      $('.btn-param').removeClass('btn-primary');
      $('.btn-param').addClass('btn-success');
    }
    // $('#btnRun').addClass('inactive');
    // $('.chartResult-panel').trigger('click');
    $('#btnRun').blur();
    generateVisual();
  });

  $('.btn-param').click(function(){
    if($(this).hasClass('btn-success')){
      $(this).removeClass('btn-success');
      $(this).addClass('btn-primary');
    }
  });

});

function generateVisual(){
  // alert('W: ' + w + ' H: ' + h);
  // alert(w);
  bodySelection = d3.select(".chartResult-panel");
  svgSelection = bodySelection.append("svg")
                   .attr("width", w)
                   .attr("height", h);

  runCl1(); //Line
  runCl2(); //Circle
  runCl3(); //Text

  // svgSelection.selectAll("text")
  //         .style("text-anchor", "middle")
          // .attr("dx", "0em")
          // .attr("dy", "0em")
          // .attr("transform", "rotate(-15)" );
}

function removeVisual(){
  svgSelection.remove();
  coordinateOutcome = [];
  coordinateParam = [];
}

function updateData() {

  // w = $('.chartResult-panel').width();
  // h = $('.chartResult-panel').height();

  // alert(w);


  generateVisual();
  // svgSelection.remove();
  // svgSelection.remove();
  // // bodySelection = d3.select(".chartResult-panel");
  // svgSelection = bodySelection.append("svg")
  //               .attr("width", w)
  //               .attr("height", h);
  //
  // runCl1();
  // runCl2();
}

function createText(cx, cy, svgSelection, i, ang){
  // alert(angle.lenght);
    console.log(ang);
    // var text = svgSelection.selectAll("text")
    //     .enter()
    //     .append("text");
    // alert(cx + ',' + cy);
    var tran1 = 0;
    var tran2 = 0;
    var check = 0;
    if(ang<=90){
      check = 1;
      tran1 = cx;
      tran2 = cy;
    }else if((ang>90) && (ang<=180)){
      check = 2;
      tran1 = cx;
      tran2 = cy ;
    }else if((ang>180) && (ang<=270)){
      check = 3;
      tran1 = ((w/2) - cx - (cx/2));
      tran2 = ((h/2) - cy);
    }else{
      check = 4;
      tran1 = ((w/2)-(cx+cx));
      tran2 = ((h/2)+cy);
    }
    var textLabels = svgSelection.append("text")
        .attr("x", cx)
        .attr("y", cy)
        // .attr("transform", "translate(" + cx +"," +cy + ")")
        .attr("font-family", "sans-serif")
        .attr("font-size", "20px")
        .attr("fill", "#000")
        .attr("text-anchor", "middle")
        // .attr("transform", "translate(" + ((w/2)) + "," + ((h/2)) + ") rotate(" + ang + ")")
        .attr("transform", "translate(" + tran1 +"," + tran2 + ") rotate(" + "0" + ")")


        .text(outcomeList[i] + '(' + check + ' ' + cx + ',' + cy + ',' + ang +')');

      // alert(ang);

}
//
// function update(nAngle) {
//
//   // adjust the text on the range slider
//   d3.select("#nAngle-value").text(nAngle);
//   d3.select("#nAngle").property("value", nAngle);
//
//   // rotate the text
//   holder.select("text")
//     .attr("transform", "translate(300,150) rotate("+nAngle+")");
// }

function createLine(svgSelection, paramPoint, rs){
  var p1 = paramPoint.split(":");
  for(var i = 0; i < coordinateOutcome.length ; i++){
    var p2 = String(coordinateOutcome[i]).split(":");
    linkLine = svgSelection.append("line")
          .attr("x1", p1[0]-rs)
          .attr("y1", p1[1]-rs)
          .attr("x2", p2[0]-rs)
          .attr("y2", p2[1]-rs)
          .attr("stroke-width", 1)
          // .attr("stroke", "#F2F2F2")
          .attr("stroke", "#ccc")
          .style("stroke-dasharray", ("5, 5"))  // <== This line here!!
  } //End for
}

function createLine2(svgSelection, paramPoint, rs){
  var p1 = paramPoint.split(":");
  for(var i = 0; i < coordinateOutcome.length ; i++){
    var p2 = String(coordinateOutcome[i]).split(":");
    linkLine = svgSelection.append("line")
          .attr("x1", p1[0]-rs)
          .attr("y1", p1[1]-rs)
          .attr("x2", p2[0]-rs)
          .attr("y2", p2[1]-rs)
          .attr("stroke-width", 3)
          // .attr("stroke", "#F2F2F2")
          .attr("stroke", "#ccc")
          .style("stroke-dasharray", ("5, 5"))  // <== This line here!!

    // createText(p1[0]-rs, p1[1]-rs, svgSelection, i);
  } //End for
}

function createLine3(svgSelection, paramPoint, rs, i){
  //var p1 = paramPoint.split(":");
  //for(var i = 0; i < coordinateOutcome.length ; i++){
    var p2 = String(coordinateOutcome[i]).split(":");
    // linkLine = svgSelection.append("line")
    //       .attr("x1", p1[0]-rs)
    //       .attr("y1", p1[1]-rs)
    //       .attr("x2", p2[0]-rs)
    //       .attr("y2", p2[1]-rs)
    //       .attr("stroke-width", 3)
    //       // .attr("stroke", "#F2F2F2")
    //       .attr("stroke", "#ccc")
    //       .style("stroke-dasharray", ("5, 5"))  // <== This line here!!

    createText(p2[0]-rs, p2[1]-rs, svgSelection, i ,angle[i]);
  // } //End for
}

function createCircle(px,py,svgSelection,delay_val, oc, ccolor){
  // alert(oc);
  var circleSelection = svgSelection.append("circle")
        //  .attr("cx", px)
        //  .attr("cy", py)
         .attr("cx", w/2)
         .attr("cy", h/2)
         .attr("r", oc - 2)
        //  .style("fill", "purple");
         .style("cursor","pointer")
         .style("fill", ccolor)
         .transition()
         .attr("cx",px)
         .attr("cy",py)
         .duration(1500)
         .delay(delay_val)
         .ease("elastic")
}

function PointOnCircle(radius,angle){

  var CenterX = w/2;
  var CenterY = h/2;

  var x = radius * Math.cos(angle * Math.PI / 180) + CenterX;
  var y = radius * Math.sin(angle * Math.PI / 180) + CenterY;

  var pos = [];
  pos.push(x.toFixed(2));
  pos.push(y.toFixed(2));
  // return point + ". angle at " + angle + ": (" + x.toFixed(2) + "," + y.toFixed(2) +")<br>";
  return pos;
}

function PointOnCircle2(radius,angle, rs){

  var CenterX = w/2 + rs/1.5;
  var CenterY = h/2 + rs/1.5;

  var x = radius * Math.cos(angle * Math.PI / 180) + CenterX;
  var y = radius * Math.sin(angle * Math.PI / 180) + CenterY;

  var pos = [];
  pos.push(x.toFixed(2));
  pos.push(y.toFixed(2));
  return pos;
}

function runCl1(){

         var result = [];

         var outcome = outcome_origin;;
         var c = 1;
         var radius = 50;
         var delay_val = 100;

         var oc = true;
         var ocPos = ((numofParam_original/outcome) + 1).toFixed(0);
         var cas = 'a';
         if((numofParam_original%outcome)!=0){
           ocPos = ((numofParam_original/outcome)+1).toFixed(0);
           cas = 'b';
         }

         var ocCheck = 0;

         var numofParam = numofParam_original + outcome;
         var eachAngle = 360/numofParam;

        //  alert(ocPos);
         var rs = 10;
         var numCoutcome = 0;

         var ws ;
         if(w>h){
           ws = (((h/2)/2)+100);
         }else{
           ws = (((w/2)/2)+100);
         }

         var paramSig = "#61B7E1";
         var paramNotsig = "#A4AAAD";
         var paramOutcome = "#F50030";

         //Calculate circular
         var circular = (2 * Math.PI * ws).toFixed(2);
         var LargeUnitRadius = ((circular/numofParam)/2) * 0.8;
         var SmallUnitRadius = ((circular/numofParam)/2) * 0.5;

         var ParamData = [];
         for(var i = 0; i <= 360; i = (i + eachAngle)){

           result = [];
           var buffParam = [];
           var x = 0;
           var y = 0;
           var ccolor = paramNotsig;

           if(ocCheck==0){
             ccolor = paramOutcome;
             rs = LargeUnitRadius;
             numCoutcome++;
           }else{
             rs = SmallUnitRadius;
             ccolor = paramSig;
             if(ocCheck==ocPos){
               if(numCoutcome!=outcome){
                //  alert(numCoutcome);
                ccolor = paramOutcome;
                 rs = LargeUnitRadius;
                 numCoutcome++;
               }else{
                 rs = SmallUnitRadius;
                 ccolor = paramSig;
                 numCoutcome++;
               }
               ocCheck = 0;
             }
           }

           var coor;
           if(c<=numofParam){
             result = PointOnCircle(ws, i);
             coor = result[0] + ":" + result[1];
           }


           if(rs==LargeUnitRadius){
             coordinateOutcome.push(coor);
           }else{
             coordinateParam.push(coor);
            //  createLine(svgSelection, String(coor), 0);
           }

           ocCheck++;
           delay_val += 20;
           c++;
         }

         for(var i = 0; i < coordinateParam.length ; i++){
           createLine(svgSelection, String(coordinateParam[i]), 0);
         }

         for(var i = 0; i < coordinateOutcome.length ; i++){
           createLine2(svgSelection, String(coordinateOutcome[i]), 0);
         }
}

function runCl2(){

         var result = [];

         // จำนวนตัวแปรต้นที่สนใจ
        //  var numofParam = 27;

         //จำนวน outcome ที่สนใจ
         var outcome = outcome_origin;
         var c = 1;
         var radius = 50;
         var delay_val = 100;

         alert(numofParam_original);

         var oc = true;
         var ocPos = ((numofParam_original/outcome) + 1).toFixed(0);
         var cas = 'a';

         if((numofParam_original%outcome)!=0){
           ocPos = ((numofParam_original/outcome)+1).toFixed(0);
           cas = 'b';
         }

         var ocCheck = 0;

         var numofParam = numofParam_original + outcome;
         var eachAngle = 360/numofParam;

        //  alert(ocPos);
         var rs = 10;
         var numCoutcome = 0;

         var ws ;
         if(w>h){
           ws = (((h/2)/2)+100);
         }else{
           ws = (((w/2)/2)+100);
         }

         var paramSig = "#61B7E1";
         var paramNotsig = "#A4AAAD";
         var paramOutcome = "#F50030";

         //Calculate circular
         var circular = (2 * Math.PI * ws).toFixed(2);
         var LargeUnitRadius = ((circular/numofParam)/2) * 0.8;
         var SmallUnitRadius = ((circular/numofParam)/2) * 0.5;

         var ParamData = [];
         for(var i = 0; i <= 360; i = (i + eachAngle)){

           result = [];
           var buffParam = [];
           var x = 0;
           var y = 0;
           var ccolor = paramNotsig;

           if(ocCheck==0){
             ccolor = paramOutcome;
             rs = LargeUnitRadius;
             numCoutcome++;
            //  angle.push(i + eachAngle);
           }else{
             rs = SmallUnitRadius;
             ccolor = paramSig;
             if(ocCheck==ocPos){
               if(numCoutcome!=outcome){
                //  alert(numCoutcome);
                ccolor = paramOutcome;
                 rs = LargeUnitRadius;
                //  angle.push(i + eachAngle);
                 numCoutcome++;
               }else{
                 rs = SmallUnitRadius;
                 ccolor = paramSig;
                 numCoutcome++;
                //  angle.push(i + eachAngle);
               }
               ocCheck = 0;
             }
           }

           if(rs!=SmallUnitRadius){
             angle.push(i);
           }
          //  var circleData;
           if(c<=numofParam){
             result = PointOnCircle(ws, i);
             var coor = result[0] + ":" + result[1];
             createCircle(result[0],result[1],svgSelection, delay_val, rs, ccolor);
           }


           ParamData.push(buffParam);
           ocCheck++;
           delay_val += 20;
           c++;
         }
}

function runCl3(){
  for(var i = 0; i < coordinateOutcome.length ; i++){
    createLine3(svgSelection, String(coordinateOutcome[i]), 0, i);
  }
}
