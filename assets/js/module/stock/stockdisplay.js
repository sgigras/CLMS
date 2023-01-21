$(function () {
    //Initialize Select2 Elements
    $('.select2').select2();
    // getListOfStates();

})


$('#taxname').on('select2:select', function (e) {
    $("#statelist").select2("val", "");
    $('#taxdetailsbody').empty();
});

$('#statelist').on('select2:select select2:unselect', function (e) {
    var selectedValues = [];
    $("#statelist :selected").each(function () {
        selectedValues.push($(this).val());
    });

    // var taxname = $('#taxname').select2('data');
    // var taxid = taxname[0].id;
    var taxid = '';
    selectedValues = selectedValues.toString()
    console.log(selectedValues);
    // return false;

    // var selectedarray = JSON.parse("[" + selectedValues + "]");
    var statesidlist = selectedValues;

    var databaseObject = { csrf_test_name: csrfHash, statesidlist: statesidlist };
    $.ajax({
        url: baseurl + 'admin/tax/Tax/fetchstatestaxesmapped',
        type: 'post',
        data: databaseObject,
        dataType: 'json',
        success: function (response) {
            console.log(response);
            var html = "";
            var count = 0;
            $('#collapsible_container').empty();
            for (const key in response['taxlist']) {
                var checkedstatus = "";
                if (response['taxlist'][key]['isactive'] == "1") {
                    checkedstatus = "checked";
                } else {
                    checkedstatus = "unchecked";
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
                html += ' <div id="infotable">' +
                    '            <div class="col-md-12">' +
                    '            <div class="card card-info collapsed-card">' +
                    '              <div class="card-header">' +
                    '                <h3 class="card-title">' + response['taxlist'][key]['tax_name'] + '</h3>' +
                    '                <div class="card-tools">' +
                    '                  <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-plus"></i>' +
                    '                  </button>' +
                    '                </div>' +
                    '              </div>' +
                    '              <div class="card-body">' +
                    '<table id="taxstatetable" class="table table-bordered table-hover">' +
                    '					<thead>' +
                    '						<tr>' +
                    '							<th> Tax Rate %</th>';
                if (showheader == "yes") {
                    html += '							<th width="50">Status</th>';
                    html += '							<th width="50">Action</th>';
                } else {
                    html += '							<th width="50">Action</th>';
                }
                html += '						</tr>' +
                    '					</thead>' +
                    '					<tbody id="taxdetailsbody">' +
                    '<tr><td><input onkeydown="validateInputPercentage(event);" class="interestinputclass" id="interest_' + count + '" value="' + response['taxlist'][key]['tax_percent'] + '"/></td>';
                if (showheader == "yes") {
                    html += '<td>' +
                        '<div style="' + isstatemapped + '" id="mappedstatesdiv">' +
                        '<input style="margin-left:30px;" class="tgl_checkbox tgl-ios" data-id="' + response['taxlist'][key]['mappingid'] + '" id="' + response['taxlist'][key]['mappingid'] + '" type="checkbox" ' + checkedstatus + '>' +
                        '<label style="margin-left:20px;" for="' + response['taxlist'][key]['mappingid'] + '"></label>' + '</td>';
                    html += '<td>' +

                        '<button type="button" id="update_' + response['taxlist'][key]['mappingid'] + '" mappingid="' + response['taxlist'][key]['mappingid'] + '" taxid="' + response['taxlist'][key]['taxid'] + '" interestinputid="interest_' + count + '" update="yes" style="color:blue;" class="btn btn-app">' +
                        '                  <i class="fa fa-save"></i>Update Tax Rate' +
                        '                </button>' +
                        '</td>';
                } else {
                    html += '<td>' +

                        '<button type="button" id="' + response['taxlist'][key]['taxid'] + '" taxid="' + response['taxlist'][key]['taxid'] + '" interestinputid="interest_' + count + '" save="yes" style="color:blue;" class="btn btn-app">' +
                        '                  <i class="fa fa-save"></i>Assign Tax To State' +
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
            html += '<div id="infotable">' +
                '            <div class="col-md-12">' +
                '            <div class="card card-info collapsed-card">' +
                '              <div class="card-header">' +
                '                <h3 class="card-title">BSF MARGIN</h3>' +
                '                <div class="card-tools">' +
                '                  <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-plus"></i>' +
                '                  </button>' +
                '                </div>' +
                '              </div>' +
                '              <div class="card-body">' +
                '<table id="taxstatetable" class="table table-bordered table-hover">' +
                '					<thead>' +
                '						<tr>' +
                '							<th> Price Per Bottle (₹)</th>' +
                '							<th> Liquor Type</th>' +
                '							<th width="50">Action</th>' +
                '						</tr>' +
                '					</thead>' +
                '					<tbody id="taxdetailsbody">' +
                '<tr><td><input class="interestinputclass" id="priceperbottle" value=""/></td>' +
                '<td style="width:100%"><select class="form-control select2" id="liquortypeselect" placeholder="Select Liquor Type"></select></td>' +
                '<td>' +
                '<button type="button" id="update_' + '" mappingid="' + '" taxid="' + '" interestinputid="interest_' + '" bsfmargin="yes" style="color:blue;" class="btn btn-app">' +
                '                  <i class="fa fa-save"></i>Update Liquor Margin' +
                '                </button>' +
                '</td>' +
                '				</tr></div>	</tbody>' +
                '				</table>' +
                '              </div>' +
                '            </div>' +
                '          </div>' +
                '            </div>';

            $('#collapsible_container').append(html);

            document.getElementById("liquortypeselect").innerHTML += '<option></option>';

            for (i = 0; i < response['liquortypes'].length; i++) {
                document.getElementById("liquortypeselect").innerHTML += '<option id="' + response['liquortypes'][i].id + '">' + response['liquortypes'][i].liquor_type + '</option>';
            }
            $('#liquortypeselect').select2({
                placeholder: "Select Liquor Type",
                width: '60%'
            });

            $('#liquortypeselect').on('select2:select', function (e) {
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

                var databaseObject = { csrf_test_name: csrfHash, stateid: stateid, liquortypeid: liquorid };
                $.ajax({
                    url: baseurl + 'admin/tax/Tax/getBSFMarginData',
                    type: 'post',
                    data: databaseObject,
                    dataType: 'json',
                    success: function (response) {
                       console.log(response);
                       if(response.length>0){
                        $('#priceperbottle').val(response[0].amount);
                       }else{
                        $('#priceperbottle').val(0);
                       }
                    }
                });


            });

            const pasteBox = document.getElementsByClassName("interestinputclass");
            pasteBox.onpaste = e => {
                e.preventDefault();
                return false;
            };

            $("button[save='yes']").click(function (e) {
                // var stateid = this.id;
                var buttonid = this.id;

                var selectedValues = [];
                $("#statelist :selected").each(function () {
                    selectedValues.push($(this).val());
                });
                selectedValues = selectedValues.toString();
                var statesidlist = selectedValues;
                var stateid = statesidlist;

                var taxid = $('#' + buttonid).attr("taxid");
                var interestinputid = $('#' + buttonid).attr("interestinputid");
                var interestvalue = $('#' + interestinputid).val();

                var databaseObject = { csrf_test_name: csrfHash, stateid: stateid, taxid: taxid, interestvalue: interestvalue };
                $.ajax({
                    url: baseurl + 'admin/tax/Tax/addTaxToState',
                    type: 'post',
                    data: databaseObject,
                    dataType: 'json',
                    success: function (response) {
                        Swal.fire({
                            title: 'Saved!',
                            text: "The Tax Has Been Mapped To The State!",
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
                // Your code
                // alert('clicked');
                var buttonid = this.id;
                var mappingid = $('#' + buttonid).attr("mappingid");
                var interestinputid = $('#' + buttonid).attr("interestinputid");
                var interestvalue = $('#' + interestinputid).val();
                var databaseObject = { csrf_test_name: csrfHash, mappingid: mappingid, interestvalue: interestvalue };
                $.ajax({
                    url: baseurl + 'admin/tax/Tax/updateTaxToState',
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
                var priceperbottle=$('#priceperbottle').val();

                var databaseObject = { csrf_test_name: csrfHash, stateid: stateid, liquortypeid: liquorid ,priceperbottle:priceperbottle};
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

function validateInputPercentage(event) {
    var value = parseFloat(event.target.value);
    // alert(value);
    // console.log(event);
    // alert('in function');
    // return s.match(/^(^100(\.0{1,2})?$)|(^([1-9]([0-9])?|0)(\.[0-9]{1,2})?$)$/) != null;
    const keyCode = event.keyCode;

    //PREVENTS TEXTUAL INPUT START
    const excludedKeys = [8, 37, 39, 46, 110];

    if (!((keyCode >= 48 && keyCode <= 57) ||
        (keyCode >= 96 && keyCode <= 105) ||
        (excludedKeys.includes(keyCode)))) {
        event.preventDefault();
    }

    //PREVENT TEXT INPUT END

    //VALIDATION OF PERCENTAGE START
    const main = () => {
        $('.interestinputclass').bind({
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
    //VALIDATION OF PERCENTAGE END
}









