$(function () {
    //Initialize Select2 Elements
    $('.select2').select2();
    // getListOfStates();

})


$('#taxname').on('select2:select', function (e) {
    $("#liquorlist").select2("val", "");
    $('#taxdetailsbody').empty();
});

$('#liquorlist').on('select2:select select2:unselect', function (e) {
    var selectedValues = [];
    $("#liquorlist :selected").each(function () {
        selectedValues.push($(this).val());
    });

    // var taxname = $('#taxname').select2('data');
    // var taxid = taxname[0].id;
    var taxid = '';
    selectedValues = selectedValues.toString()
    alert(selectedValues);
    // return false;

    // var selectedarray = JSON.parse("[" + selectedValues + "]");
    var liquoridlist = selectedValues;
    alert(liquoridlist);
    var databaseObject = { csrf_test_name: csrfHash, liquoridlist: liquoridlist };
    $.ajax({
        url: baseurl + 'admin/tax/Tax/fetchliquortaxesmapped',
        type: 'post',
        data: databaseObject,
        dataType: 'json',
        success: function (response) {
            // console.log(response['taxlist']);
            var html = "";
            var count = 0;
            $('#collapsible_container').empty();
            var tax_category = "tax_category";
            // console.log(response);
            // return false;
            for (const key in response['taxlist']) {
                console.log(tax_category);
                var checkedstatus = "";
                var percentagechecked="";
                var absolutechecked="";
                if (response['taxlist'][key]['isactive'] == "1") {
                    checkedstatus = "checked";
                } else {
                    checkedstatus = "unchecked";
                }
                if (response['taxlist'][key]['tax_type_id'] == "1") {
                    absolutechecked='checked';
                } else {
                    percentagechecked='checked';
                }
                var isstatemapped = "";
                var showheader = "";
                if ((response['taxlist'][key]['mappingid'] === null) || (response['taxlist'][key]['mappingid'] === undefined)) {
                    isstatemapped = "display:none;";
                    showheader = "no";
                } else {
                    isstatemapped = "display:block;";
                    showheader = "yes";
                }

                if ((response['taxlist'][key]['tax_percent'] === null) || (response['taxlist'][key]['tax_percent'] === undefined)) {
                    response['taxlist'][key]['tax_percent'] = 0.00;
                }
                count++;

                //LOOPING TRHOUGH TAX DETAILS FROM TAX MASTER AND STATE TAX MAPPING TABLE
                // html += "<tr><td style='display:none;'>" + response[key]['mappingid'] + "</td><td>" + count + "</td><td>" + response[key]['state'] + "</td><td>" + response[key]['tax_name'] + "</td><td><input value=" + response[key]['tax_amount'] + "></td></tr>";
                html += `<input type='hidden' value="j'++'">`+
                ' <div id="infotable">' +
                    '            <div class="col-md-12">' +
                    '            <div class="card  collapsed-card">' +
                    '              <div class="card-header bg-info-gradient">' +
                    '                <h3 class="card-title">' + response['taxlist'][key]['tax_name'] + '</h3>' +
                    '                <div class="card-tools">' +
                    '               <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-plus"></i>' +
                    '                 </button>' +
                    '                </div>' +
                    '              </div>' +
                    '              <div class="card-body">' +
                    '<table id="taxstatetable" class="table table-bordered table-hover">' +
                    '					<thead>' +
                    '						<tr>' +
                    '							<th>Tax type</th>' +
                    '							<th>Tax Rate</th>' ;
                if (showheader == "yes") {
                    html += '							<th width="50">Status</th>';
                    html += '							<th width="50">Action</th> ';
                } else {
                    html += '							<th width="50">Action</th>';
                }
                $radio_count =  response['taxlist'][key]['taxid'];
                $tax_category_id =  response['taxlist'][key]['tax_category_id'];
                html += '						</tr>' +
                    '					</thead>' +
                    '					<tbody id="taxdetailsbody">' +
                    '<tr><td><div ><label style="margin-right:5px;">Percentage</label><input type="hidden" id="tax_category'+response['taxlist'][key]['taxid']+'" value="'+$tax_category_id+'"><input '+percentagechecked+' type="radio" onchange="change_radio(' + $radio_count + ')" id="percentagecheck'+$radio_count+'" name="taxvalue_' + $radio_count + '" value="0"><label style="margin-left:45px;">Absolute</label>&nbsp;&nbsp;<input '+absolutechecked+' type="radio" onchange="change_radio(' + $radio_count + ')" id="absolutevaluescheck'+$radio_count+'" name="taxvalue_' + $radio_count + '" value="1"></div></td><td><input maxlength="6" onkeydown="validateInputPercentage(event,' + $radio_count + ');"class="interestinputclass" id="interest_' + $radio_count + '" value="' + response['taxlist'][key]['tax_percent'] + '"/></td>';
                if (showheader == "yes") {

                    // $radio_count =  response['taxlist'][key]['taxid'];

                    // html += '<td><div ><label style="margin-right:5px;">Percentage</label><input type="radio" onchange="change_radio(' + radio_count + ')" id="percentagecheck" name="taxvalue_' + radio_count + '" value="0"><label style="margin-left:45px;">Absolute</label>&nbsp;&nbsp;<input type="radio" onchange="change_radio(' + radio_count + ')" id="absolutevaluescheck" name="taxvalue_' + radio_count + '" value="1"></div></td><td><input onkeydown="validateInputPercentage(event,' + radio_count + ');"class="interestinputclass" id="interest_' + radio_count + '" value="' + response['taxlist'][key]['tax_percent'] + '"/></td>';


                    html += '<td>' +
                        '<div style="' + isstatemapped + '" id="mappedstatesdiv">' +
                        '<input style="margin-left:30px;" class="tgl_checkbox tgl-ios" data-id="' + response['taxlist'][key]['mappingid'] + '" id="' + response['taxlist'][key]['mappingid'] + '" type="checkbox" ' + checkedstatus + '>' +
                        '<label style="margin-left:20px;" for="' + response['taxlist'][key]['mappingid'] + '"></label>' + '</td>';
                    html += '<td>' +

                        '<button type="button" id="' + response['taxlist'][key]['taxid'] + '" mappingid="' + response['taxlist'][key]['mappingid'] + '" taxid="' + response['taxlist'][key]['taxid'] + '" interestinputid="interest_' + $radio_count + '" update="yes" style="color:blue;" class="btn btn-app">' +
                        '                  <i class="fa fa-save"></i>Update Tax Rate' +
                        '                </button>' +
                        '</td>';
                } else {

                    // $radio_count =  response['taxlist'][key]['taxid'];
                    

                    // html += '<td><div ><label style="margin-right:5px;">Percentage</label><input type="radio" onchange="change_radio(' + radio_count + ')" id="percentagecheck" name="taxvalue_' + radio_count + '" value="0"><label style="margin-left:45px;">Absolute</label>&nbsp;&nbsp;<input type="radio" onchange="change_radio(' + radio_count + ')" id="absolutevaluescheck" name="taxvalue_' + radio_count + '" value="1"></div></td><td><input onkeydown="validateInputPercentage(event,' + radio_count + ');"class="interestinputclass" id="interest_' + radio_count + '" value="' + response['taxlist'][key]['tax_percent'] + '"/></td>';

                    html += '<td>' +
                        // '<input type="radio" id="' + response['taxtype'][key]['checked_value'] + '"taxtypeid="' + response['taxtype'][key]['taxtypeid'][key]['checked_value'] + '"percentagecheck="taxvalue_' + count + '">' +
                        '<button type="button" id="' + response['taxlist'][key]['taxid'] + '" taxid="' + response['taxlist'][key]['taxid'] + '"interestinputid="interest_' + $radio_count + '" save="yes" style="color:blue;" class="btn btn-app">' +
                        '                  <i class="fa fa-save"></i>Assign Tax To Liquor' +
                        '                </button>' +
                        '</td>';
                }
                html += '				</tr></div>	</tbody>' +
                    '				</table>' +
                    '              </div>' +
                    '            </div>' +
                    '          </div>' +
                    '            </div>';


            }

            // BSF MARGIN FOR EACH STATE
            // html += '<div id="infotable">' +
            //     '            <div class="col-md-12">' +
            //     '            <div class="card card-info collapsed-card">' +
            //     '              <div class="card-header">' +
            //     '                <h3 class="card-title">ITBP MARGIN</h3>' +
            //     '                <div class="card-tools">' +
            //     '                  <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-plus"></i>' +
            //     '                  </button>' +
            //     '                </div>' +
            //     '              </div>' +
            //     '              <div class="card-body">' +
            //     '<table id="taxstatetable" class="table table-bordered table-hover">' +
            //     '					<thead>' +
            //     '						<tr>' +
            //     '							<th> Profit Per Bottle (%)</th>' +
            //     '							<th> Liquor Type</th>' +
            //     '							<th width="50">Action</th>' +
            //     '						</tr>' +
            //     '					</thead>' +
            //     '					<tbody id="taxdetailsbody">' +
            //     '<tr><td><input class="interestinputclass" id="priceperbottle" value=""/></td>' +
            //     '<td style="width:100%"><select class="form-control select2" id="liquortypeselect" placeholder="Select Liquor Type"></select></td>' +
            //     '<td>' +
            //     '<button type="button" id="update_' + '" mappingid="' + '" taxid="' + '" interestinputid="interest_' + '" bsfmargin="yes" style="color:blue;" class="btn btn-app">' +
            //     '                  <i class="fa fa-save"></i>Update Liquor Margin' +
            //     '                </button>' +
            //     '</td>' +
            //     '				</tr></div>	</tbody>' +
            //     '				</table>' +
            //     '              </div>' +
            //     '            </div>' +
            //     '          </div>' +
            //     '            </div>';

            $('#collapsible_container').append(html);

            // document.getElementById("liquortypeselect").innerHTML += '<option></option>';

            // for (i = 0; i < response['liquortypes'].length; i++) {
            //     document.getElementById("liquortypeselect").innerHTML += '<option id="' + response['liquortypes'][i].id + '">' + response['liquortypes'][i].liquor_type + '</option>';
            // }
            // $('#liquortypeselect').select2({
            //     placeholder: "Select Liquor Type",
            //     width: '60%'
            // });

            // $('#liquortypeselect').on('select2:select', function (e) {
            //     var selectedValues = [];
            //     $("#statelist :selected").each(function () {
            //         selectedValues.push($(this).val());
            //     });
            //     selectedValues = selectedValues.toString();
            //     var statesidlist = selectedValues;
            //     var stateid = statesidlist;

            //     // alert(stateid);
            //     var liquortypedata = $('#liquortypeselect').select2('data');
            //     console.log(liquortypedata);
            //     var liquorid = $('#liquortypeselect option:selected').attr('id');
            //     // alert(liquorid);

            //     var databaseObject = { csrf_test_name: csrfHash, stateid: stateid, liquortypeid: liquorid };
            //     $.ajax({
            //         url: baseurl + 'admin/tax/Tax/getBSFMarginData',
            //         type: 'post',
            //         data: databaseObject,
            //         dataType: 'json',
            //         success: function (response) {
            //            console.log(response);
            //            if(response.length>0){
            //             $('#priceperbottle').val(response[0].amount);
            //            }else{
            //             $('#priceperbottle').val(0);
            //            }
            //         }
            //     });


            // });

            const pasteBox = document.getElementsByClassName("interestinputclass");
            pasteBox.onpaste = e => {
                e.preventDefault();
                return false;
            };

            $("button[save='yes']").click(function (e) {
                var buttonid = this.id;
                var selectedValues = [];
                $("#liquorlist :selected").each(function () {
                    selectedValues.push($(this).val());
                });
                var tax_category_value = document.getElementById('tax_category'+buttonid).value;
                selectedValues = selectedValues.toString();
                var liquoridlist = selectedValues;
                var liquorid = liquoridlist;
                var taxid = $('#' + buttonid).attr("taxid");
                var interestinputid = $('#' + buttonid).attr("interestinputid");
                var interestvalue = $('#' + interestinputid).val();
                var checked_value = $('input[name="taxvalue_' + buttonid + '"]:checked').val();
                var databaseObject = { csrf_test_name: csrfHash, liquorid: liquorid, taxid: taxid, interestvalue: interestvalue, checked_value: checked_value, tax_category: tax_category_value };
                $.ajax({
                    url: baseurl + 'admin/tax/Tax/addTaxToLiquor',
                    type: 'post',
                    data: databaseObject,
                    dataType: 'json',
                    success: function (response) {
                        Swal.fire({
                            title: 'Saved!',
                            text: "The Tax Has Been Mapped To The Liquor!",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.reload();
                            }
                        })
                    }
                });


            });

            $("button[update='yes']").click(function (e) {
                // if (!checkInputEmpty("taxvalue_", "Kindly enter tax rate.")) {
                // Your code
                // alert('clicked');
                var buttonid = this.id;
                var mappingid = $('#' + buttonid).attr("mappingid");
                var interestinputid = $('#' + buttonid).attr("interestinputid");
                var interestvalue = $('#' + interestinputid).val();
                var checked_value = $('input[name="taxvalue_' + buttonid + '"]:checked').val();
                var tax_category_value=document.getElementById('tax_category'+buttonid).value;

                var databaseObject = { csrf_test_name: csrfHash, mappingid: mappingid, interestvalue: interestvalue, checked_value: checked_value, tax_category: tax_category_value };
                $.ajax({
                    url: baseurl + 'admin/tax/Tax/updateTaxToLiquor',
                    type: 'post',
                    data: databaseObject,
                    dataType: 'json',
                    success: function (response) {
                        Swal.fire({
                            title: 'Updated!',
                            text: "The Tax Has Been Updated!",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                //   window.location.reload();
                            }
                        })
                    }
                });

            // }
            });


            $("button[bsfmargin='yes']").click(function (e) {
                var selectedValues = [];
                $("#statelist :selected").each(function () {
                    selectedValues.push($(this).val());
                });
                selectedValues = selectedValues.toString();
                var statesidlist = selectedValues;
                var stateid = statesidlist;

                // alert(stateid);
                var liquortypedata = $('#liquortypeselect').select2('data');
                console.log(liquortypedata);
                var liquorid = $('#liquortypeselect option:selected').attr('id');
                // alert(liquorid);
                var priceperbottle = $('#priceperbottle').val();

                var databaseObject = { csrf_test_name: csrfHash, stateid: stateid, liquortypeid: liquorid, priceperbottle: priceperbottle };
                $.ajax({
                    url: baseurl + 'admin/tax/Tax/mapnewBSFMarginData',
                    type: 'post',
                    data: databaseObject,
                    dataType: 'json',
                    success: function (response) {
                        Swal.fire({
                            title: 'Updated!',
                            text: "Liquor Margin Updated!",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                //   window.location.reload();
                            }
                        })
                    }
                });


            });


        }
    });
});


$('#submitstates').on('click', function (event) {

    /*Get Header*/
    var xKey = [];
    $("#taxstatetable th").each(function () {
        var tempColName = $(this).text(); {
            xKey.push(tempColName);
        }
    });
    /*Get Data*/
    var xValue = [];
    $("#taxstatetable tr").each(function () {
        var arrayOfThisRow = [];
        // var tableData = $(this).find('td,td input');
        var tableData = $(this).closest('tr').find("td,td>input");
        if (tableData.length > 0) {
            tableData.each(function () {
                var elementtype = $(this).prop('tagName');
                console.log(elementtype);
                if (elementtype == "INPUT") {
                    console.log($(this));
                    alert($(this)[0].value);
                    arrayOfThisRow.push($(this)[0].value);
                } else {
                    arrayOfThisRow.push($(this).text());
                }
            });
            xValue.push(arrayOfThisRow);
        }
    });

    var xFinalArr = [];
    for (var i = 0; i < xValue.length; i++) {
        var xRowObj = {};
        for (var j = 0; j < xKey.length; j++) {
            xRowObj[xKey[j]] = xValue[i][j];
        }
        xFinalArr.push(xRowObj);
    }

    // console.log(xFinalArr);


    var databaseObject = { csrf_test_name: csrfHash, xFinalArr: xFinalArr };
    $.ajax({
        url: baseurl + 'admin/tax/Tax/mapTaxToStates',
        type: 'post',
        data: databaseObject,
        dataType: 'json',
        success: function (response) {
            alert('success');
        }
    });
});


$("body").on("change", ".tgl_checkbox", function () {
    // console.log('checked');
    $.post(baseurl + 'admin/tax/Tax/change_status',
        {
            csrf_test_name: csrfHash,
            id: $(this).data('id'),
            status: $(this).is(':checked') == true ? 1 : 0
        },
        function (data) {
            $.notify("Status Changed Successfully", "success");
        });
});

//INTEREST RATE INPUT VALIDATION FUNCTION

function change_radio(count) {
    $('#interest_' + count).unbind();
    $('#interest_' + count).val("");
}

function validateInputPercentage(event, count) {
    var checked_value = $('input[name="taxvalue_' + count + '"]:checked').val();
    if (checked_value == 1 || checked_value == 0) {
        var value = parseFloat(event.target.value);
        // alert(value);
        // console.log(event);
        // alert('in function');
        // return s.match(/^(^100(\.0{1,2})?$)|(^([1-9]([0-9])?|0)(\.[0-9]{1,2})?$)$/) != null;

        // return s.match(/^(100(\.0{0,2})?|(\d|[1-9]\d)(\.\d{0,2})?)$/) != null;

        const keyCode = event.keyCode;

        //PREVENTS TEXTUAL INPUT START
        const excludedKeys = [8, 37, 39, 46, 110];


        // if (!((charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
        //     (charCode < 48 || charCode > 57))
        //     return false;
        //     return true;    

        if (!((keyCode >= 48 && keyCode <= 57) ||
            (keyCode >= 96 && keyCode <= 105) ||
            (excludedKeys.includes(keyCode)))) {
            event.preventDefault();
        }

        //PREVENT TEXT INPUT END
        if (checked_value == 0) {
            //VALIDATION OF PERCENTAGE START
            const main = () => {
                $('#interest_' + count).bind({
                    focusin: onFocusIn,
                    keyup: onChange
                });
            };

            const onFocusIn = (e) => {
                const $target = $(e.currentTarget);
                $target.data('val', $target.val());
            };

            const onChange = (e) => {
                const regex = /^((100)|(\d{1,2}(\.\d*)?))%?$/,
                    $target = $(e.currentTarget),
                    value = $target.val(),
                    event = e || window.event,
                    keyCode = event.keyCode || event.which,
                    isValid = value.trim().length === 0 ||
                        (keyInRange(keyCode) && regex.test(value));
                if (!isValid) {
                    $target.val($target.data('val'));
                    event.preventDefault();
                } else {
                    $target.data('val', value);
                }
            };


            const keyInRange = (keyCode) =>
                (keyCode >= 48 && keyCode <= 57) || /* top row numbers       */
                (keyCode >= 96 && keyCode <= 105) || /* keypad numbers        */
                (keyCode === 110 || keyCode === 190) || /* decimal separator     */
                (keyCode === 53) || /* percentage            */
                (keyCode === 8 || keyCode === 46);      /* back-space and delete */

            main();



            // return s.match(/^(100(\.0{0,2})?|(\d|[1-9]\d)(\.\d{0,2})?)$/) != null;

            //VALIDATION OF PERCENTAGE END
        } else {
            // alert("in else condition");
            //VALIDATION OF ABSOLUTE START
            const main = () => {
                $('#interest_' + count).bind({
                    focusin: onFocusIn,
                    keyup: onChange
                });
            };

            const onFocusIn = (e) => {
                const $target = $(e.currentTarget);
                $target.data('val', $target.val());
            };

            const onChange = (e) => {
                const regex = /^((\d{1,3}(\.\d*)?))%?$/,
                    $target = $(e.currentTarget),
                    value = $target.val(),
                    event = e || window.event,
                    keyCode = event.keyCode || event.which,
                    isValid = value.trim().length === 0 ||
                        (keyInRange(keyCode) && regex.test(value));
                if (!isValid) {
                    $target.val($target.data('val'));
                    event.preventDefault();
                } else {
                    $target.data('val', value);
                }
            };

            const keyInRange = (keyCode) =>
                (keyCode >= 48 && keyCode <= 57) || /* top row numbers       */
                (keyCode >= 96 && keyCode <= 105) || /* keypad numbers        */
                (keyCode === 110 || keyCode === 190) || /* decimal separator     */
                (keyCode === 53) || /* percentage            */
                (keyCode === 8 || keyCode === 46);      /* back-space and delete */
            main();
            // return s.match(/^(100(\.0{0,2})?|(\d|[1-9]\d)(\.\d{0,2})?)$/) != null;
            //VALIDATION OF ABSOLUTE END ̰
        }
    }
    else {
        // alert("Please select tax type.");
    }

}

// function send_quan(value){
//     alert(value);
// }







