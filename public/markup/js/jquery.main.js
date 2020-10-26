jQuery(function() {
  initGraph();
  initAccordion();
  initVideoBlock();
});

function initVideoBlock() {
  /* Handle Vimeo video */
      var iframe = document.getElementById("video");
      var player = new Vimeo.Player(iframe);

      $(".js-video-opener").click(function(e){
        e.preventDefault();
        videoPlay(true);
      });

      $(".btn-play").click(function(e){
        e.preventDefault();
        videoPlay(true);
      });

      $(".video-close").click(function(e){
        e.preventDefault();
        videoReset();
      });

      player.on('ended', function(data) {
        videoReset();
      });

      function videoPlay(scroll) {
        $(".video-holder").show();
        $(".video-close").show();
        $("#video").show();

        // Disable scrolling
        $('body').css({overflow: 'hidden'});

        setTimeout(function play() {
            player.play();
        }, 300);
      }

      function videoReset() {
        player.unload();

        // Enable scrolling
        $('body').css({overflow: 'auto'});

        $("#video").hide();
        $(".video-close").hide();
        $(".video-holder").hide();
      }
}

// accordion menu init
function initAccordion() {
  ResponsiveHelper.addRange({
    '..991': {
      on: function() {
        jQuery('ul.view-accordion').slideAccordion({
          opener: 'a.js-opener',
          slider: 'div.slide',
          event: 'click',
          animSpeed: 300
        });
      },
      off: function() {
        jQuery('.view-accordion').slideAccordion('destroy');
      }
    }
  });

  jQuery('.accordion').slideAccordion({
    opener: '.opener',
    slider: '.slide',
    animSpeed: 300
  });
}


function initGraph(){
  var isTouchDevice = ('ontouchstart' in window) || window.DocumentTouch && document instanceof window.DocumentTouch,
    isWinPhoneDevice = /Windows Phone/.test(navigator.userAgent);
  var mouseEnter = 'mouseenter';
  var mouseLeave = 'mouseleave';
  var firstAnimFlag = false;

  if (isTouchDevice || isWinPhoneDevice) {
    mouseEnter = 'click';
    mouseLeave = 'click';
  }

  var colorGreen = '#8cc640';
  var colorBlue = '#5bc5e5';
  var colorGrey = '#9b9997';
  var popupHolder = jQuery('.graph-details');

  var width = 500,
    height = 500,
    radius = Math.min(width, height) / 2 - 50,
    innerRadius = 0.2 * radius;

  const PI = Math.PI,
    arcMinRadius = innerRadius,
    arcPadding = 10,
    numArcs = 3;

  var pie = d3.pie()
        .sort(null)
        .value(function(d) { return d.width; });

  var arc = d3.arc()
    .innerRadius(innerRadius)
    .outerRadius(function (d) {
      return (radius - innerRadius) * (d.data.score / 100.0) + innerRadius - 4;
    });

  var arcStroke = d3.arc()
    .innerRadius(innerRadius)
    .outerRadius(function (d) {
      return (radius - innerRadius) * (d.data.score / 100.0) + innerRadius;
    });

  var arc2 = d3.arc()
    .innerRadius(40)
    .outerRadius(60);

  var outlineArc = d3.arc()
    .innerRadius(innerRadius)
    .outerRadius(radius);

  var svg = d3.select("#graph").append("svg")
    .attr("width", '100%')
    .attr("height", height)
    .attr('preserveAspectRatio', 'none')
    .attr('viewBox', '0 0 500 500')
    .attr('pointer-events', 'none')
    .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");


  var shadowTimer;

  ///////////////////////////////////// shadow filters
  /////////////////////////////////////
  var defs = svg.append("defs");

  // var filter1 = defs.append("filter")
  //   .attr("id", "drop-shadow")
  //   .attr("height", "130%");

  //   filter1.append("feGaussianBlur")
  //     .attr("in", "SourceAlpha")
  //     .attr("stdDeviation", 7)
  //     .attr("result", "blur");

  //   filter1.append("feOffset")
  //     .attr("in", "blur")
  //     .attr("dx", -6)
  //     .attr("dy", 15)
  //     .attr("result", "offsetBlur");

  //   filter1.append("feFlood")
  //     .attr("in", "offsetBlur")
  //     .attr("flood-color", "#141414")
  //     .attr("flood-opacity", "0.6")
  //     .attr("result", "offsetColor");

  //   filter1.append("feComposite")
  //     .attr("in", "offsetColor")
  //     .attr("in2", "offsetBlur")
  //     .attr("operator", "in")
  //     .attr("result", "offsetBlur");

  // var feMerge = filter1.append("feMerge");

  //   feMerge.append("feMergeNode")
  //     .attr("in", "offsetBlur");
  //   feMerge.append("feMergeNode")
  //     .attr("in", "SourceGraphic");

  // var filter2 = defs.append("filter")
  //   .attr("id", "drop-shadow-2")
  //   .attr("height", "130%");

  //   filter2.append("feGaussianBlur")
  //     .attr("in", "SourceAlpha")
  //     .attr("stdDeviation", 7)
  //     .attr("result", "blur");

  //   filter2.append("feOffset")
  //     .attr("in", "blur")
  //     .attr("dx", 12)
  //     .attr("dy", -3)
  //     .attr("result", "offsetBlur");

  //   filter2.append("feFlood")
  //     .attr("in", "offsetBlur")
  //     .attr("flood-color", "#141414")
  //     .attr("flood-opacity", "0.6")
  //     .attr("result", "offsetColor");

  //   filter2.append("feComposite")
  //     .attr("in", "offsetColor")
  //     .attr("in2", "offsetBlur")
  //     .attr("operator", "in")
  //     .attr("result", "offsetBlur");

  // var feMerge = filter2.append("feMerge");

  //   feMerge.append("feMergeNode")
  //     .attr("in", "offsetBlur")
  //   feMerge.append("feMergeNode")
  //     .attr("in", "SourceGraphic");

  // var filter3 = defs.append("filter")
  //   .attr("id", "drop-shadow-3")
  //   .attr("height", "130%");

  //   filter3.append("feGaussianBlur")
  //     .attr("in", "SourceAlpha")
  //     .attr("stdDeviation", 7)
  //     .attr("result", "blur");

  //   filter3.append("feOffset")
  //     .attr("in", "blur")
  //     .attr("dx", 2)
  //     .attr("dy", 10)
  //     .attr("result", "offsetBlur");

  //   filter3.append("feFlood")
  //     .attr("in", "offsetBlur")
  //     .attr("flood-color", "#141414")
  //     .attr("flood-opacity", "0.6")
  //     .attr("result", "offsetColor");

  //   filter3.append("feComposite")
  //     .attr("in", "offsetColor")
  //     .attr("in2", "offsetBlur")
  //     .attr("operator", "in")
  //     .attr("result", "offsetBlur");

  // var feMerge = filter3.append("feMerge");

  //   feMerge.append("feMergeNode")
  //     .attr("in", "offsetBlur")
  //   feMerge.append("feMergeNode")
  //     .attr("in", "SourceGraphic");


  // var filter4 = defs.append("filter")
  //   .attr("id", "drop-shadow-4")
  //   .attr("height", "130%");

  //   filter4.append("feGaussianBlur")
  //     .attr("in", "SourceAlpha")
  //     .attr("stdDeviation", 6)
  //     .attr("result", "blur");

  //   filter4.append("feOffset")
  //     .attr("in", "blur")
  //     .attr("dx", -6)
  //     .attr("dy", 3)
  //     .attr("result", "offsetBlur");

  //   filter4.append("feFlood")
  //     .attr("in", "offsetBlur")
  //     .attr("flood-color", "#141414")
  //     .attr("flood-opacity", "0.5")
  //     .attr("result", "offsetColor");

  //   filter4.append("feComposite")
  //     .attr("in", "offsetColor")
  //     .attr("in2", "offsetBlur")
  //     .attr("operator", "in")
  //     .attr("result", "offsetBlur");

  // var feMerge = filter4.append("feMerge");

  //   feMerge.append("feMergeNode")
  //     .attr("in", "offsetBlur")
  //   feMerge.append("feMergeNode")
  //     .attr("in", "SourceGraphic");

  // var filter5 = defs.append("filter")
  //   .attr("id", "drop-shadow-5")
  //   .attr("height", "130%");

  //   filter5.append("feGaussianBlur")
  //     .attr("in", "SourceAlpha")
  //     .attr("stdDeviation", 6)
  //     .attr("result", "blur");

  //   filter5.append("feOffset")
  //     .attr("in", "blur")
  //     .attr("dx", -2)
  //     .attr("dy", -7)
  //     .attr("result", "offsetBlur");

  //   filter5.append("feFlood")
  //     .attr("in", "offsetBlur")
  //     .attr("flood-color", "#141414")
  //     .attr("flood-opacity", "0.5")
  //     .attr("result", "offsetColor");

  //   filter5.append("feComposite")
  //     .attr("in", "offsetColor")
  //     .attr("in2", "offsetBlur")
  //     .attr("operator", "in")
  //     .attr("result", "offsetBlur");

  // var feMerge = filter5.append("feMerge");

  //   feMerge.append("feMergeNode")
  //     .attr("in", "offsetBlur")
  //   feMerge.append("feMergeNode")
  //     .attr("in", "SourceGraphic");

  var filterCircle = defs.append("filter")
    .attr("id", "filterCircle")
    .attr("height", "130%");

    filterCircle.append("feGaussianBlur")
      .attr("in", "SourceAlpha")
      .attr("stdDeviation", 1)
      .attr("result", "blur");

    filterCircle.append("feOffset")
      .attr("in", "blur")
      .attr("dx", 0)
      .attr("dy", 1)
      .attr("result", "offsetBlur");

    filterCircle.append("feFlood")
      .attr("in", "offsetBlur")
      .attr("flood-color", "#141414")
      .attr("flood-opacity", "0.3")
      .attr("result", "offsetColor");

    filterCircle.append("feComposite")
      .attr("in", "offsetColor")
      .attr("in2", "offsetBlur")
      .attr("operator", "in")
      .attr("result", "offsetBlur");

  var feMerge = filterCircle.append("feMerge");

    feMerge.append("feMergeNode")
      .attr("in", "offsetBlur")
    feMerge.append("feMergeNode")
      .attr("in", "SourceGraphic");


  ///////////////////////////////////// main data graph
  /////////////////////////////////////
  d3.csv('https://cyberturity-media-mktg.dev.steel.kiwi/static/data.csv', function(error, data) {

    const arcWidth = (radius - innerRadius - numArcs * arcPadding) / numArcs;

    radialAxis = svg.append('g')
      .attr('class', 'r axis')
      .selectAll('g')
      .data(data)
      .enter().append('g');

    radialAxis.append('circle')
      .attr('r', function(d, i){
        if (getOuterRadius(i) + arcPadding > 0) {
          return getOuterRadius(i) + arcPadding
        }
      })
      .attr("fill", "none")
      .attr("stroke-width", function(d, i){
        return 3 - i;
      })
      .attr("stroke", "#626e76")
      .attr("opacity", "0.2");

    data.forEach(function(d) {
      d.order  = +d.order;
      d.color  =  d.color;
      d.weight = +d.weight;
      d.score  = +d.score;
      d.width  = +d.weight;
    });


    ///////////////////////////////////// main arc
    /////////////////////////////////////
    var path = svg
      .selectAll(".solidArc")
      .data(pie(data))
      .enter()
      .append("g").attr("class", function(d) { return 'solidArcGroup order-' + d.data.order; })
      .append("path")
      .attr("fill", function(d) { return d.data.color; })
      .attr("class", "solidArc")
      .attr("stroke", function(d) { return d.data.color; })
      .attr("stroke-width", "0")
      .attr('opacity', '1')
      .each(function(d) {
        var gradientRadius;

        if (d.data.score == 100){
          gradientRadius = 220;
        }else if(d.data.score < 100 && d.data.score > 50){
          gradientRadius = 160;
        }else if(d.data.score < 50 && d.data.score > 0){
          gradientRadius = 100;
        }

        var gradient = defs.append("radialGradient")
          .attr("id", "gradient" + (d.data.order-1))
          .attr("gradientUnits", "userSpaceOnUse")
          .attr("cx", "0")
          .attr("cy", "0")
          .attr("r", (gradientRadius*1))
          .attr("spreadMethod", "pad");

        if (d.data.color == colorGreen) {
          gradient.append("stop").attr("offset", "30%")
            .attr("stop-color", "#21aa49")
            .attr("stop-opacity", 1);

          gradient.append("stop").attr("offset", "52%")
            .attr("stop-color", "#58b947")
            .attr("stop-opacity", 1);

          gradient.append("stop").attr("offset", "90%")
            .attr("stop-color", d.data.color);

        }

        if (d.data.color == colorBlue) {
          gradient.append("stop").attr("offset", "25%")
            .attr("stop-color", "#3276ae")
            .attr("stop-opacity", 1);

          gradient.append("stop").attr("offset", "45%")
            .attr("stop-color", "#1f86bb")
            .attr("stop-opacity", 1);

          gradient.append("stop").attr("offset", "90%")
            .attr("stop-color", d.data.color);

        }

        if (d.data.color == colorGrey) {
          gradient.append("stop").attr("offset", "0%")
            .attr("stop-color", "#3f3e41")
            .attr("stop-opacity", 1);

          gradient.append("stop").attr("offset", "60%")
            .attr("stop-color", "#85827e")
            .attr("stop-opacity", 1);

          gradient.append("stop").attr("offset", "90%")
            .attr("stop-color", d.data.color);

        }

        d3.select(this)
              .attr("fill", "url(#gradient"+ (d.data.order-1) +")")
              .attr("d", arc2)
              .transition()
              .duration(500)
              .attr("d", arc);

      })
      .attr("transform","rotate(180)")
      .transition()
      .duration(500)
      .delay(1000)
      .attr("transform","rotate(0)");

    var pathGroup = svg.selectAll(".solidArcGroup");

    ///////////////////////////////////// stroke arc
    /////////////////////////////////////
    var pathStroke = svg
      .selectAll(".solidArcGroup")
      .data(pie(data))
      .append("path")
      .attr("fill", function(d) { return d.data.color; })
      .attr("class", "solidArcStroke")
      .attr("stroke", function(d) { return d3.color(d.data.color).darker(0.4); })
      .attr("stroke-width", "0")
      .attr('opacity', '1')
      .each(function(d) {
        if (d.data.color == colorGreen) {
          d3.select(this)
            .attr("fill", "#279f46")
            .attr("d", arc2)
            .transition()
            .duration(500)
            .attr("d", arcStroke);
        }

        if (d.data.color == colorBlue) {
          d3.select(this)
            .attr("fill", "#2075a6")
            .attr("d", arc2)
            .transition()
            .duration(500)
            .attr("d", arcStroke);
        }

        if (d.data.color == colorGrey) {
          d3.select(this)
            .attr("fill", "#6a6668")
            .attr("d", arc2)
            .transition()
            .duration(500)
            .attr("d", arcStroke);
        }
      })
      .attr("transform","rotate(180)")
      .transition()
      .duration(500)
      .delay(1000)
      .attr("transform","rotate(0)");

    svg
      .selectAll(".solidArc")
      .each(function() {
        this.parentNode.appendChild(this);
      });


    ///////////////////////////////////// arc animation
    /////////////////////////////////////
    var timer, timer1;

    svg.selectAll(".solidArc")
      .on('mouseenter click touchstart', function(d){

        if (isTouchDevice || isWinPhoneDevice){
          svg.selectAll("text").each(function(d){
              d3.select(this)
                .interrupt()
                .transition()
                .duration(100)
                .delay(0)
                .attr("opacity", "1")
                .attr("font-size", "12px");
            });

            svg.selectAll("text").each(function(d){
              svg.select(".text-circle-stroke-" + d.data.order)
                .interrupt()
                .attr("fill", "#abb0b4")
                .attr("r", 16)
                .style("filter", "none");

              svg.select(".text-circle-" + d.data.order)
                .interrupt()
                .attr("fill", "#58595b")
                .attr("r", 14)
                .style("filter", "url(#filterCircle)");
            });
        }

        d3.select(".text-" + d.data.order)
              .interrupt()
              .transition()
              .duration(100)
              .delay(0)
              .attr("opacity", "1")
              .attr("font-size", "16px");

        svg.select(".text-circle-stroke-" + d.data.order)
          .interrupt()
          .attr("fill", d.data.color)
          .attr("r", 20)
          .style("filter", "url(#filterCircle)");
        svg.select(".text-circle-" + d.data.order)
          .interrupt()
          .attr("fill", d.data.color)
          .attr("r", 18)
          .style("filter", "none");

        if (popupHolder.hasClass('graph-block')) {
          popupHolder.removeClass();
          popupHolder.addClass('graph-block graph-details');
        }else {
          popupHolder.removeClass();
          popupHolder.addClass('graph-details');
        }
        popupHolder.addClass('popup-'+ d.data.order +'-active');
      })
      .on('mouseleave click touchend', function(d){
        var self = this;

        if (isTouchDevice || isWinPhoneDevice) {
          clearTimeout(timer);
          timer = setTimeout(function(){
            svg.selectAll("text").each(function(d){
              d3.select(this)
                .interrupt()
                .transition()
                .duration(100)
                .delay(0)
                .attr("opacity", "1")
                .attr("font-size", "12px");
            });

            svg.selectAll("text").each(function(d){
              svg.select(".text-circle-stroke-" + d.data.order)
                .interrupt()
                .attr("fill", "#abb0b4")
                .attr("r", 16)
                .style("filter", "none");

              svg.select(".text-circle-" + d.data.order)
                .interrupt()
                .attr("fill", "#58595b")
                .attr("r", 14)
                .style("filter", "url(#filterCircle)");
            });

            if (popupHolder.hasClass('graph-block')) {
              popupHolder.removeClass();
              popupHolder.addClass('graph-block graph-details');
            }else {
              popupHolder.removeClass();
              popupHolder.addClass('graph-details');
            }
          }, 3000);
        }else {
          svg.selectAll("text").each(function(d){
            d3.select(this)
              .transition()
              .duration(100)
              .delay(0)
              .attr("opacity", "1")
              .attr("font-size", "12px");
          });

          svg.selectAll("text").each(function(d){
            svg.select(".text-circle-stroke-" + d.data.order)
              .attr("fill", "#abb0b4")
              .attr("r", 16)
              .style("filter", "none");

            svg.select(".text-circle-" + d.data.order)
              .attr("fill", "#58595b")
              .attr("r", 14)
              .style("filter", "url(#filterCircle)");
          });

          popupHolder.removeClass('popup-'+ d.data.order +'-active');
        }
      });

    svg.selectAll(".solidArcGroup")
      .on('mouseenter click touchstart',function(d){
        svg.selectAll(".solidArcGroup")
          .classed("active", false)
          .attr('opacity', '0.4');

        d3.select(this)
          .classed("active", true)
          .attr('opacity', '0.4')
          .transition()
          .duration(1000)
          .attr('opacity', '1');
      })
      .on('mouseleave click touchend',function(d){
        if (isTouchDevice || isWinPhoneDevice) {
          clearTimeout(timer1);
          timer1 = setTimeout(function(){
            svg.selectAll(".solidArcGroup")
              .interrupt()
              .classed("active",false)
              .attr('opacity', '1');
          }, 3000);
        }else {
          svg.selectAll(".solidArcGroup")
            .interrupt()
            .classed("active",false)
            .attr('opacity', '1');
        }
      });

    var dx1 = 15;
    var dy1 = 3;

    var dx2 = 3;
    var dy2 = 15;

    var dx3 = -15;
    var dy3 = 0;

    var dx4 = 3;
    var dy4 = -15;

    var dxdyoffset = 3;
    ////////// filters
    svg.selectAll(".solidArcGroup").each(function(d){
      var filter = defs.append("svg:filter")
        .attr("id", "drop-shadow" + d.index);

      if ((d.startAngle + d.endAngle)/2 < Math.PI/2){
        filter.append("svg:feOffset")
          .attr("dx", dx1)
          .attr("dy", dy1);

        dx1 = dx1 + dxdyoffset;
        dy1 = dy1 - dxdyoffset;
      } else if((d.startAngle + d.endAngle)/2 >= Math.PI/2 && (d.startAngle + d.endAngle)/2 < Math.PI){
        filter.append("svg:feOffset")
          .attr("dx", dx2)
          .attr("dy", dy2);

        dx2 = dx2 - dxdyoffset;
        dy2 = dy2 + dxdyoffset;
      } else if((d.startAngle + d.endAngle)/2 >= Math.PI && (d.startAngle + d.endAngle)/2 <= Math.PI * 1.5){
        filter.append("svg:feOffset")
          .attr("dx", dx3)
          .attr("dy", dy3);

        dx3 = dx3 + dxdyoffset;
        dy3 = dy3 - dxdyoffset;
      }else if((d.startAngle + d.endAngle)/2 >= Math.PI * 1.5 && (d.startAngle + d.endAngle)/2 < Math.PI * 2){
        filter.append("svg:feOffset")
            .attr("dx", dx4)
            .attr("dy", dy4);

        dx4 = dx4 + dxdyoffset;
        dy4 = dy4 + dxdyoffset;
      }

      filter.append("svg:feGaussianBlur")
        .attr("stdDeviation", 4)
        .attr("result", "offset-blur");

      filter.append("svg:feComposite")
        .attr("operator", "out")
        .attr("in", "SourceGraphic")
        .attr("in2", "offset-blur")
        .attr("result", "inverse");

      filter.append("svg:feFlood")
        .attr("flood-color", "black")
        .attr("flood-opacity", 0.5)
        .attr("result", "color");

      filter.append("svg:feComposite")
        .attr("operator", "in")
        .attr("in", "color")
        .attr("in2", "inverse")
        .attr("result", "shadow");

      filter.append("svg:feComposite")
        .attr("operator", "over")
        .attr("in", "shadow")
        .attr("in2", "SourceGraphic");

      svg.select(".order-" + (d.index+1))
        .style("filter", "url(#drop-shadow"+ d.index +")")
    });


    ///////////////////////////////////// auto animation
    /////////////////////////////////////
    var delay = 20000, delay2 = 20000, delay3 = 20000;
    var stepDelay = 3000;
    var dispatchTimer;

    if (d3.select("#graph").classed("first-anim-done")) {
      delay = 20000;
    }

    // animate();
    // function animate(){
    animate(false);
    function animate(shadowDelay){
      if (shadowDelay) {
        svg.selectAll("feFlood").attr('flood-opacity', '0');
        shadowTimer = setTimeout(function(){
          svg.selectAll("feFlood").attr('flood-opacity', '0.5');
        }, 2000);
      }

      pathGroup.each(function(obj){
        var self = this;

        dispatchTimer = setTimeout(function(){
          d3.select(self).dispatch("mouseleave touchend");
          d3.select(self).dispatch("mouseenter");

          if (d3.select(self).classed("order-" + data.length)){
            setTimeout(function(){
              d3.select("#graph").select("svg").remove();
              d3.select("#graph").classed("first-anim-done", true);
              if (popupHolder.hasClass('graph-block')) {
                popupHolder.removeClass();
                popupHolder.addClass('graph-block graph-details');
              }else {
                popupHolder.removeClass();
                popupHolder.addClass('graph-details');
              }
              initGraph();
              popupHolder.addClass('popup-0-active');
            }, 2000);
          }
        }, delay + (obj.index + 1) * stepDelay);
      });

      path.each(function(obj){
        var self = this;

        dispatchTimer = setTimeout(function(){
          d3.select(self).dispatch("mouseleave");
          d3.select(self).dispatch("mouseenter");
        }, delay + (obj.index + 1) * stepDelay);
      });

      pathStroke.each(function(obj){
        var self = this;

        dispatchTimer = setTimeout(function(){
          d3.select(self).dispatch("mouseleave");
          d3.select(self).dispatch("mouseenter");
        }, delay + (obj.index + 1) * stepDelay);
      });
    }


    ///////////////////////////////////// change order
    /////////////////////////////////////
    // svg.select(".order-2").style("filter", "url(#drop-shadow-4)")
    //   .each(function() {
    //     this.parentNode.appendChild(this);
    //   });
    // svg.select(".order-4").style("filter", "url(#drop-shadow-5)")
    //   .each(function() {
    //     this.parentNode.appendChild(this);
    //   });
    // svg.select(".order-1").style("filter", "url(#drop-shadow)")
    //   .each(function() {
    //     this.parentNode.appendChild(this);
    //   });
    // svg.select(".order-6").style("filter", "url(#drop-shadow-2)")
    //   .each(function() {
    //     this.parentNode.appendChild(this);
    //   });
    // svg.select(".order-8").style("filter", "url(#drop-shadow-3)")
    //   .each(function() {
    //     this.parentNode.appendChild(this);
    //   });


    ///////////////////////////////////// text and text animations
    /////////////////////////////////////
    svg.selectAll(".text-circle-stroke")
      .data(pie(data))
      .enter()
      .append("circle")
      .attr("class", function(d){return "text-circle-stroke-" + d.data.order})
      .attr("cx", 0)
      .attr("cy", 0)
      .attr("r", 16)
      .attr('pointer-events', 'none')
      .attr("stroke-width", "0")
      .attr("stroke", "none")
      .attr("fill", "#abb0b4")
      .attr("transform", function(d) {
        var xOffset = Math.sin(d.startAngle + (360/data.length * (Math.PI / 360))) * ((radius - innerRadius) * (d.data.score / 100.0) + innerRadius);
        var yOffset = -Math.cos(d.startAngle + (360/data.length * (Math.PI / 360))) * ((radius - innerRadius) * (d.data.score / 100.0) + innerRadius);

        return "translate(" + xOffset + "," + (yOffset) + ")";
      })
      // .attr("opacity", "0")
      .classed("hidden", true)
      .transition()
      .delay(2000)
      // .attr("opacity", "1");;
      .on("end", function(){
        svg.selectAll(".hidden").classed("hidden", false);
        svg.selectAll(".hidden-text").classed("hidden-text", false);
        svg.selectAll("feFlood").attr('flood-opacity', '0.5');
      });


    svg.selectAll(".text-circle")
      .data(pie(data))
      .enter()
      .append("circle")
      .attr("class", function(d){return "text-circle-" + d.data.order})
      .attr("cx", 0)
      .attr("cy", 0)
      .attr("r", 14)
      .attr('pointer-events', 'none')
      .attr("stroke-width", "0")
      .attr("stroke", "none")
      .attr("fill", "#58595b")
      .style("filter", "url(#filterCircle)")
      .attr("transform", function(d) {
        var xOffset = Math.sin(d.startAngle + (360/data.length * (Math.PI / 360))) * ((radius - innerRadius) * (d.data.score / 100.0) + innerRadius);
        var yOffset = -Math.cos(d.startAngle + (360/data.length * (Math.PI / 360))) * ((radius - innerRadius) * (d.data.score / 100.0) + innerRadius);

        return "translate(" + xOffset + "," + (yOffset) + ")";
      })
      // .attr("opacity", "0")
      .classed("hidden", true)
      .transition()
      .delay(2000)
      // .attr("opacity", "1");;
      .on("end", function(){
        svg.selectAll(".hidden").classed("hidden", false);
      });


    svg.selectAll(".text")
      .data(pie(data))
      .enter()
      .append('text')
      .attr('pointer-events', 'none')
      .attr("transform", function(d) {
        var xOffset = Math.sin(d.startAngle + (360/data.length * (Math.PI / 360))) * ((radius - innerRadius) * (d.data.score / 100.0) + innerRadius);
        var yOffset = -Math.cos(d.startAngle + (360/data.length * (Math.PI / 360))) * ((radius - innerRadius) * (d.data.score / 100.0) + innerRadius);

        return "translate(" + xOffset + "," + (yOffset+4) + ")";
      })
      .attr("text-anchor", "middle")
      .attr("font-size", "12px")
      .attr("fill", "#ffffff")
      .attr("class", function(d){return "text-" + d.data.order})
      .text(function(d) {return d.data.order})
      // .attr("opacity", "0")
      .classed("hidden-text", true)
      .transition()
      .delay(2000)
      // .attr("opacity", "1");
      .on("end", function(){
        svg.selectAll(".hidden-text").classed("hidden-text", false);
      });


    svg.selectAll("text")
      .transition()
      .delay(function(d, i) { return i+1 * 2000; })
      .on("start", function repeat() {
        d3.active(this)
          .attr("opacity", "0.4")
          .attr("font-size", "12px")
          .transition()
          .duration(1000)
          .attr("opacity", "1")
          .attr("font-size", "12px")
          .transition()
          .duration(1000)
          .on("start", repeat);
      })
      .on("end", function(){
        jQuery('#graph').addClass('animation-finished');
        svg.selectAll(".hidden-text").classed("hidden-text", false);
        popupHolder.addClass('popup-0-active');
      });

    // svg
    //   .on('mouseenter click touchstart', function(){
    //       for(i=0; i<dispatchTimer; i++)
    //         {
    //           clearTimeout(i);
    //         }

    //       svg.selectAll(".solidArcGroup").dispatch("mouseleave");
    //       svg.selectAll(".solidArc").dispatch("mouseleave");
    //       svg.selectAll(".solidArcStroke").dispatch("mouseleave");

    //       svg.selectAll("text").each(function(d){
    //         d3.select(this)
    //           .transition()
    //           .duration(300)
    //           .delay(0)
    //           .attr("opacity", "1")
    //           .attr("font-size", "12px");
    //       });
    //     })
    //   .on('mouseleave click touchend', function(){
    //     svg.selectAll("text")
    //       .transition()
    //       .delay(function(d, i) { return i * 100; })
    //       .on("start", function repeat() {
    //         d3.active(this)
    //           .attr("opacity", "0.4")
    //           .attr("font-size", "12px")
    //           .transition()
    //           .duration(1000)
    //           .attr("opacity", "1")
    //           .attr("font-size", "12px")
    //           .transition()
    //           .duration(1000)
    //           .on("start", repeat);
    //       });

    //     delay = 15000;

    //     animate();
    //   });


    ///////////////////////////////////// logo image
    /////////////////////////////////////
    var img = svg
      .append("svg:image")
      .attr('x', -90)
      .attr('y', -90)
      .attr('width',180)
      .attr('height', 180)
      .attr("xlink:href", "https://cyberturity-media-mktg.dev.steel.kiwi/static/img/icon.png")
      // .attr('pointer-events', 'none')
      .attr('class', 'icon');

      // img
      //   .on('mouseenter click touchstart',function(d){
      //     popupHolder.addClass('popup-0-active');
      //   })
      //   .on('mouseleave click touchend',function(d){
      //     popupHolder.removeClass('popup-0-active');
      //   });

    ///////////////////////////////////// save to png function
    /////////////////////////////////////
    d3.select('#saveButton').on('click', function(){
      if (!jQuery('#graph').hasClass('animation-finished')) {
        svg.selectAll("text").each(function(d){
          d3.select(this).finish();
        });

        svg.selectAll(".solidArc").each(function(d){
          d3.select(this).finish();
        });
      }
      saveSvgAsPng(document.getElementById("graph").querySelector('svg'), "diagram.png");
    });

    d3.selection.prototype.finish = function() {
      var slots = this.node().__transition;
      var keys = Object.keys(slots);

      keys.forEach(function(d,i) {
        if(slots[d]) slots[d].timer._call();
      })
    }

    ///////////////////////////////////// additional functions
    /////////////////////////////////////
    function getInnerRadius(index) {
      return arcMinRadius + (numArcs - (index + 1)) * (arcWidth + arcPadding);
    }

    function getOuterRadius(index) {
      return getInnerRadius(index) + arcWidth;
    }

    Math.degrees = function(radians) {
      return radians * 180 / Math.PI;
    };

  });
}

/*
 * jQuery Accordion plugin new
 */
;(function(root, factory) {

  'use strict';
  if (typeof define === 'function' && define.amd) {
    define(['jquery'], factory);
  } else if (typeof exports === 'object') {
    module.exports = factory(require('jquery'));
  } else {
    root.SlideAccordion = factory(jQuery);
  }
}(this, function($) {

  'use strict';
  var accHiddenClass = 'js-acc-hidden';

  function SlideAccordion(options) {
    this.options = $.extend(true, {
      allowClickWhenExpanded: false,
      activeClass:'active',
      opener:'.opener',
      slider:'.slide',
      animSpeed: 300,
      collapsible:true,
      event: 'click',
      scrollToActiveItem: {
        enable: false,
        breakpoint: 767, // max-width
        animSpeed: 600,
        extraOffset: null
      }
    }, options);
    this.init();
  }

  SlideAccordion.prototype = {
    init: function() {
      if (this.options.holder) {
        this.findElements();
        this.setStateOnInit();
        this.attachEvents();
        this.makeCallback('onInit');
      }
    },

    findElements: function() {
      this.$holder = $(this.options.holder).data('SlideAccordion', this);
      this.$items = this.$holder.find(':has(' + this.options.slider + ')');
    },

    setStateOnInit: function() {
      var self = this;

      this.$items.each(function() {
        if (!$(this).hasClass(self.options.activeClass)) {
          $(this).find(self.options.slider).addClass(accHiddenClass);
        }
      });
    },

    attachEvents: function() {
      var self = this;

      this.accordionToggle = function(e) {
        var $item = jQuery(this).closest(self.$items);
        var $actiItem = self.getActiveItem($item);

        if (!self.options.allowClickWhenExpanded || !$item.hasClass(self.options.activeClass)) {
          e.preventDefault();
          self.toggle($item, $actiItem);
        }
      };

      this.$items.on(this.options.event, this.options.opener, this.accordionToggle);
    },

    toggle: function($item, $prevItem) {
      if (!$item.hasClass(this.options.activeClass)) {
        this.show($item);
      } else if (this.options.collapsible) {
        this.hide($item);
      }

      if (!$item.is($prevItem) && $prevItem.length) {
        this.hide($prevItem);
      }

      this.makeCallback('beforeToggle');
    },

    show: function($item) {
      var $slider = $item.find(this.options.slider);

      $item.addClass(this.options.activeClass);
      $slider.stop().hide().removeClass(accHiddenClass).slideDown({
        duration: this.options.animSpeed,
        complete: function() {
          $slider.removeAttr('style');
          if (
            this.options.scrollToActiveItem.enable &&
            window.innerWidth <= this.options.scrollToActiveItem.breakpoint
          ) {
            this.goToItem($item);
          }
          this.makeCallback('onShow', $item);
        }.bind(this)
      });

      this.makeCallback('beforeShow', $item);
    },

    hide: function($item) {
      var $slider = $item.find(this.options.slider);

      $item.removeClass(this.options.activeClass);
      $slider.stop().show().slideUp({
        duration: this.options.animSpeed,
        complete: function() {
          $slider.addClass(accHiddenClass);
          $slider.removeAttr('style');
          this.makeCallback('onHide', $item);
        }.bind(this)
      });

      this.makeCallback('beforeHide', $item);
    },

    goToItem: function($item) {
      var itemOffset = $item.offset().top;

      if (itemOffset < $(window).scrollTop()) {
        // handle extra offset
        if (typeof this.options.scrollToActiveItem.extraOffset === 'number') {
          itemOffset -= this.options.scrollToActiveItem.extraOffset;
        } else if (typeof this.options.scrollToActiveItem.extraOffset === 'function') {
          itemOffset -= this.options.scrollToActiveItem.extraOffset();
        }

        $('body, html').animate({
          scrollTop: itemOffset
        }, this.options.scrollToActiveItem.animSpeed);
      }
    },

    getActiveItem: function($item) {
      return $item.siblings().filter('.' + this.options.activeClass);
    },

    makeCallback: function(name) {
      if (typeof this.options[name] === 'function') {
        var args = Array.prototype.slice.call(arguments);
        args.shift();
        this.options[name].apply(this, args);
      }
    },

    destroy: function() {
      this.$holder.removeData('SlideAccordion');
      this.$items.off(this.options.event, this.options.opener, this.accordionToggle);
      this.$items.removeClass(this.options.activeClass).each(function(i, item) {
        $(item).find(this.options.slider).removeAttr('style').removeClass(accHiddenClass);
      }.bind(this));
      this.makeCallback('onDestroy');
    }
  };

  $.fn.slideAccordion = function(opt) {
    var args = Array.prototype.slice.call(arguments);
    var method = args[0];

    return this.each(function() {
      var $holder = jQuery(this);
      var instance = $holder.data('SlideAccordion');

      if (typeof opt === 'object' || typeof opt === 'undefined') {
        new SlideAccordion($.extend(true, {
          holder: this
        }, opt));
      } else if (typeof method === 'string' && instance) {
        if(typeof instance[method] === 'function') {
          args.shift();
          instance[method].apply(instance, args);
        }
      }
    });
  };

  (function() {
    var tabStyleSheet = $('<style type="text/css">')[0];
    var tabStyleRule = '.' + accHiddenClass;
    tabStyleRule += '{position:absolute !important;left:-9999px !important;top:-9999px !important;display:block !important; width: 100% !important;}';
    if (tabStyleSheet.styleSheet) {
      tabStyleSheet.styleSheet.cssText = tabStyleRule;
    } else {
      tabStyleSheet.appendChild(document.createTextNode(tabStyleRule));
    }
    $('head').append(tabStyleSheet);
  }());

  return SlideAccordion;
}));

/*
 * Responsive Layout helper
 */
window.ResponsiveHelper = (function($){
  // init variables
  var handlers = [],
    prevWinWidth,
    win = $(window),
    nativeMatchMedia = false;

  // detect match media support
  if(window.matchMedia) {
    if(window.Window && window.matchMedia === Window.prototype.matchMedia) {
      nativeMatchMedia = true;
    } else if(window.matchMedia.toString().indexOf('native') > -1) {
      nativeMatchMedia = true;
    }
  }

  // prepare resize handler
  function resizeHandler() {
    var winWidth = win.width();
    if(winWidth !== prevWinWidth) {
      prevWinWidth = winWidth;

      // loop through range groups
      $.each(handlers, function(index, rangeObject){
        // disable current active area if needed
        $.each(rangeObject.data, function(property, item) {
          if(item.currentActive && !matchRange(item.range[0], item.range[1])) {
            item.currentActive = false;
            if(typeof item.disableCallback === 'function') {
              item.disableCallback();
            }
          }
        });

        // enable areas that match current width
        $.each(rangeObject.data, function(property, item) {
          if(!item.currentActive && matchRange(item.range[0], item.range[1])) {
            // make callback
            item.currentActive = true;
            if(typeof item.enableCallback === 'function') {
              item.enableCallback();
            }
          }
        });
      });
    }
  }
  win.bind('load resize orientationchange', resizeHandler);

  // test range
  function matchRange(r1, r2) {
    var mediaQueryString = '';
    if(r1 > 0) {
      mediaQueryString += '(min-width: ' + r1 + 'px)';
    }
    if(r2 < Infinity) {
      mediaQueryString += (mediaQueryString ? ' and ' : '') + '(max-width: ' + r2 + 'px)';
    }
    return matchQuery(mediaQueryString, r1, r2);
  }

  // media query function
  function matchQuery(query, r1, r2) {
    if(window.matchMedia && nativeMatchMedia) {
      return matchMedia(query).matches;
    } else if(window.styleMedia) {
      return styleMedia.matchMedium(query);
    } else if(window.media) {
      return media.matchMedium(query);
    } else {
      return prevWinWidth >= r1 && prevWinWidth <= r2;
    }
  }

  // range parser
  function parseRange(rangeStr) {
    var rangeData = rangeStr.split('..');
    var x1 = parseInt(rangeData[0], 10) || -Infinity;
    var x2 = parseInt(rangeData[1], 10) || Infinity;
    return [x1, x2].sort(function(a, b){
      return a - b;
    });
  }

  // export public functions
  return {
    addRange: function(ranges) {
      // parse data and add items to collection
      var result = {data:{}};
      $.each(ranges, function(property, data){
        result.data[property] = {
          range: parseRange(property),
          enableCallback: data.on,
          disableCallback: data.off
        };
      });
      handlers.push(result);

      // call resizeHandler to recalculate all events
      prevWinWidth = null;
      resizeHandler();
    }
  };
}(jQuery));