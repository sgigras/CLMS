/*
 * Author: Ujwal Jain
 * Date: 4 Jan 2014
 * Description:
 *      This is a demo file used only for the main dashboard (index.html)
 **/

$(function () {

  // 'use strict'
  // $('#table_div').hide();
  $("#irla_no").select2({
    createTag: function(term, data) {
        var value = term.term;
            return {
                id: value,
                text: value
            };                
    },
    placeholder: "Select irla No./Regiment No.",
    ajax: {
        url:baseurl +'user_details/User_details/GetIrlaNumber',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                q: params.term // search term
            };
        },
        processResults: function(data, params) {

            var resData = [];
            data.forEach(function(value) {
                if (value.irla.toUpperCase().indexOf(params.term.toUpperCase()) != -1)
                    resData.push(value)
            })
            return {
                results: $.map(resData, function(item) {
                    return {
                        text: item.option_data,
                        id: item.irla
                    }
                })
            };
        },
        cache: true
    },
    minimumInputLength: 4
    
});

$("#posting_unit").select2({
  createTag: function(term, data) {
      var value = term.term;
          return {
              id: value,
              text: value
          };                
  },
  placeholder: "Select Posting Unit",
  ajax: {
      url:baseurl +'user_details/User_details/GetPostingUnit',
      dataType: 'json',
      delay: 250,
      data: function(params) {
          return {
              q: params.term // search term
          };
      },
      processResults: function(data, params) {

          var resData = [];
          data.forEach(function(value) {
              if (value.posting_unit.toUpperCase().indexOf(params.term.toUpperCase()) != -1)
                  resData.push(value)
          })
          return {
              results: $.map(resData, function(item) {
                  return {
                      text: item.posting_unit,
                      id: item.posting_unit
                  }
              })
          };
      },
      cache: true
  },
  minimumInputLength: 2
  
});

$("#posting_unit").change(function() {
  var posting_unit= $("#posting_unit").val();
  var databaseObject = {
    csrf_test_name: csrfHash,
    posting_unit:posting_unit
  };
  $.ajax({
    url: baseurl + 'user_details/User_details/GetPostingunitdata',
    type: 'post',
    data: databaseObject,
    dataType: 'html',
    success: function (response) {
      $('#posting_unit_div').html(response);
    }
  });
});

$("#irla_no").change(function() {
  var irlano= $("#irla_no").val();
  var databaseObject = {
    csrf_test_name: csrfHash,
    irlano:irlano
  };
  $.ajax({
    url: baseurl + 'user_details/User_details/GetUserdetails',
    type: 'post',
    data: databaseObject,
    dataType: 'html',
    success: function (response) {
      $('#table_div').html(response);
    }
  });
});



  // Make the dashboard widgets sortable Using jquery UI
  // $('.connectedSortable').sortable({
  //   placeholder         : 'sort-highlight',
  //   connectWith         : '.connectedSortable',
  //   handle              : '.card-header, .nav-tabs',
  //   forcePlaceholderSize: true,
  //   zIndex              : 999999
  // })
  // $('.connectedSortable .card-header, .connectedSortable .nav-tabs-custom').css('cursor', 'move')

  // jQuery UI sortable for the todo list
  // $('.todo-list').sortable({
  //   placeholder         : 'sort-highlight',
  //   handle              : '.handle',
  //   forcePlaceholderSize: true,
  //   zIndex              : 999999
  // })

  // bootstrap WYSIHTML5 - text editor
  // $('.textarea').wysihtml5()

  // $('.daterange').daterangepicker({
  //   ranges   : {
  //     'Today'       : [moment(), moment()],
  //     'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
  //     'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
  //     'Last 30 Days': [moment().subtract(29, 'days'), moment()],
  //     'This Month'  : [moment().startOf('month'), moment().endOf('month')],
  //     'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
  //   },
  //   startDate: moment().subtract(29, 'days'),
  //   endDate  : moment()
  // }, function (start, end) {
  //   window.alert('You chose: ' + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
  // })

  /* jQueryKnob */
  // $('.knob').knob()

  // jvectormap data
  var visitorsData = {
    'US': 398, //USA
    'SA': 400, //Saudi Arabia
    'CA': 1000, //Canada
    'DE': 500, //Germany
    'FR': 760, //France
    'CN': 300, //China
    'AU': 700, //Australia
    'BR': 600, //Brazil
    'IN': 800, //India
    'GB': 320, //Great Britain
    'RU': 3000 //Russia
  }
  // World map by jvectormap
  // $('#world-map').vectorMap({
  //   map              : 'world_mill_en',
  //   backgroundColor  : 'transparent',
  //   regionStyle      : {
  //     initial: {
  //       fill            : 'rgba(255, 255, 255, 0.7)',
  //       'fill-opacity'  : 1,
  //       stroke          : 'rgba(0,0,0,.2)',
  //       'stroke-width'  : 1,
  //       'stroke-opacity': 1
  //     }
  //   },
  //   series           : {
  //     regions: [{
  //       values           : visitorsData,
  //       scale            : ['#ffffff', '#0154ad'],
  //       normalizeFunction: 'polynomial'
  //     }]
  //   },
  //   onRegionLabelShow: function (e, el, code) {
  //     if (typeof visitorsData[code] != 'undefined')
  //       el.html(el.html() + ': ' + visitorsData[code] + ' new visitors')
  //   }
  // })

  // Sparkline charts
  // var myvalues = [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021]
  // $('#sparkline-1').sparkline(myvalues, {
  //   type     : 'line',
  //   lineColor: '#92c1dc',
  //   fillColor: '#ebf4f9',
  //   height   : '50',
  //   width    : '80'
  // })
  // myvalues = [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921]
  // $('#sparkline-2').sparkline(myvalues, {
  //   type     : 'line',
  //   lineColor: '#92c1dc',
  //   fillColor: '#ebf4f9',
  //   height   : '50',
  //   width    : '80'
  // })
  // myvalues = [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21]
  // $('#sparkline-3').sparkline(myvalues, {
  //   type     : 'line',
  //   lineColor: '#92c1dc',
  //   fillColor: '#ebf4f9',
  //   height   : '50',
  //   width    : '80'
  // })

  // The Calender
  // $('#calendar').datepicker()

  // SLIMSCROLL FOR CHAT WIDGET
  // $('#chat-box').slimScroll({
  //   height: '250px'
  // })

  /* Morris.js Charts */
  // Sales chart
  // var area = new Morris.Area({
  //   element   : 'revenue-chart',
  //   resize    : true,
  //   data      : [
  //     { y: '2011 Q1', item1: 2666, item2: 2666 },
  //     { y: '2011 Q2', item1: 2778, item2: 2294 },
  //     { y: '2011 Q3', item1: 4912, item2: 1969 },
  //     { y: '2011 Q4', item1: 3767, item2: 3597 },
  //     { y: '2012 Q1', item1: 6810, item2: 1914 },
  //     { y: '2012 Q2', item1: 5670, item2: 4293 },
  //     { y: '2012 Q3', item1: 4820, item2: 3795 },
  //     { y: '2012 Q4', item1: 15073, item2: 5967 },
  //     { y: '2013 Q1', item1: 10687, item2: 4460 },
  //     { y: '2013 Q2', item1: 8432, item2: 5713 }
  //   ],
  //   xkey      : 'y',
  //   ykeys     : ['item1', 'item2'],
  //   labels    : ['Item 1', 'Item 2'],
  //   lineColors: ['#495057', '#007cff'],
  //   hideHover : 'auto'
  // })
  // var line = new Morris.Line({
  //   element          : 'line-chart',
  //   resize           : true,
  //   data             : [
  //     { y: '2011 Q1', item1: 2666 },
  //     { y: '2011 Q2', item1: 2778 },
  //     { y: '2011 Q3', item1: 4912 },
  //     { y: '2011 Q4', item1: 3767 },
  //     { y: '2012 Q1', item1: 6810 },
  //     { y: '2012 Q2', item1: 5670 },
  //     { y: '2012 Q3', item1: 4820 },
  //     { y: '2012 Q4', item1: 15073 },
  //     { y: '2013 Q1', item1: 10687 },
  //     { y: '2013 Q2', item1: 8432 }
  //   ],
  //   xkey             : 'y',
  //   ykeys            : ['item1'],
  //   labels           : ['Item 1'],
  //   lineColors       : ['#efefef'],
  //   lineWidth        : 2,
  //   hideHover        : 'auto',
  //   gridTextColor    : '#fff',
  //   gridStrokeWidth  : 0.4,
  //   pointSize        : 4,
  //   pointStrokeColors: ['#efefef'],
  //   gridLineColor    : '#efefef',
  //   gridTextFamily   : 'Open Sans',
  //   gridTextSize     : 10
  // })

  // Donut Chart
  // var donut = new Morris.Donut({
  //   element  : 'sales-chart',
  //   resize   : true,
  //   colors   : ['#007bff', '#dc3545', '#28a745'],
  //   data     : [
  //     { label: 'Download Sales', value: 12 },
  //     { label: 'In-Store Sales', value: 30 },
  //     { label: 'Mail-Order Sales', value: 20 }
  //   ],
  //   hideHover: 'auto'
  // })

  // Fix for charts under tabs
  // $('.box ul.nav a').on('shown.bs.tab', function () {
  //   area.redraw()
  //   donut.redraw()
  //   line.redraw()
  // })
})

function edituser(){
  var irla=$("#irlaid").val();
  var dob=$("#dob").val();
  var mobile=$("#mobile").val();
  var email=$("#email").val();
  var error= true;
  if(mobile==""){
    $("#mobile_err").html("Please Enter Mobile Number");
    error= false;
  }
  if(email==""){
    $("#email_err").html("Please Enter Email Address");
    error= false;
  }
  if(error){
    var databaseObject = {
      csrf_test_name: csrfHash,
      irla:irla,
      dob:dob,
      mobile:mobile,
      email:email
    };
    $.ajax({
      url: baseurl + 'user_details/User_details/update_user',
      type: 'post',
      data: databaseObject,
      dataType: 'JSON',
      success: function (response) {
        Swal.fire({
          title: response,
          // text: result[0].V_SWAL_TEXT,
          icon: 'success',
        });
        location.reload();
      }
    });

  }

}

function open_table() {
  $('#table_div').html('<div class="overlay">' +
    '<i class="fa fa-refresh fa-spin"></i>' +
    '</div>');
    $('#table_div').show();
  $.ajax({
    url: baseurl + 'user_details/User_details',
    type: 'post',
    data: databaseObject,
    dataType: 'html',
    success: function (response) {
      $('#table_div').html(response);
      // $('#master_table').DataTable({});
      document.getElementById("icon_btn").className='fa fa-refresh';
    }
  });
}
