<!-- Content Wrapper. Contains page content -->
<!-- <div class="content-wrapper"> -->
<style>
    /*css for elastic search */
    .right-menu {
        display: inline-flex !important;
    }

    .search-box-wrap {
        position: relative;
        top: 13px;
        left: -10px;
        width: 100%;
    }

    .search-box form {
        border: 1px solid transparent;
        border-radius: 90px;
        -webkit-border-radius: 90px;
    }

    .search-box {
        height: 38px;
        background: #fff;
        position: relative;
        display: block;
        margin: 0px 0px 0px 0px !important;
        margin: 0 auto;
        border: none;
        padding: 0px;
        border: 1px solid #ccc;
        border-radius: 5px;
        -webkit-border-radius: 5px;
    }

    .search-box input {
        width: 290px;
        color: #333 !important;
        background: transparent !important;
        font-size: 13px;
        height: 38px;
        border: 0px solid #fff;
        padding: 0px 29px 0px 10px;
        border-radius: 15px;
        -webkit-border-radius: 15px;
    }

    .search-box div {
        line-height: 32px;
    }

    .search-box button {
        float: right;
        position: relative;
        top: 1px;
        right: 6px;
        background: #fff;
        border: none;
        font-size: 14px;
        cursor: pointer;
    }

    .search_title {
        color: #333;
        font-size: 12px;
        font-weight: bold;
        text-align: left;
        padding: 8px 10px 6px 10px;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
        border-radius: 0px;
    }

    .search-box ul {
        background: #fff;
        width: 1100px;
        position: absolute;
        margin: -3px 0px 0px 0px;
        padding: 0px;
        min-height: 55px;
        max-height: 195px;
        overflow: auto;
        border-top: 1px solid #eee;
        border-right: 1px solid #ccc;
        border-left: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        z-index: 99;
    }

    .search-box .search_hghlt {
        margin: 0px;
        padding: 8px 10px;
        font-size: 18px;
        border-top: 1px solid #e8e8e8;
    }

    .search-box .search-overlay {
        background: #fff;
        min-height: 205px;
        position: absolute;
        width: 290px;
        padding: 0px 0px 20px 0px;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        display: contents;
        z-index: 999;
    }

    .search-box ul li {
        font-size: 13px;
        list-style: none;
        padding: 5px 5px 5px 5px;
        line-height: 20px;
        border-bottom: 1px solid #eee;
    }

    .search-box ul a svg {
        margin: 3px 10px 8px 10px;
        vertical-align: top !important;
        float: left !important;
    }

    .search-box ul li a {
        background-color: transparent;
        font-size: 13px;
        color: #333;
        text-decoration: none;
    }

    .search-box ul li:hover {
        background: #eee;
    }

    .search-box ul li img {
        width: 28px !important;
        height: 27px !important;
        padding: 1px;
        margin: 0px 5px 0px 0px;
        border: 1px solid #d6d6d6;
        float: left;
        border-radius: 5px;
        -moz-border-radius: 5px;
    }

    .search-box .search_hghlt {
        margin: 0px;
        padding: 8px 10px;
        font-size: 14px;
        border-top: 1px solid #e8e8e8;
    }

    .search-box .search_hghlt a {
        color: #adadad;
    }

    #search-results {
        padding-top: 0px;
        position: absolute;
        border: none;
        display: none;
        border-radius: 0px;
    }

    .search-box ul a i {
        margin: 3px 10px 8px 10px;
        vertical-align: top !important;
        float: left !important;
    }

    .search-box input:focus {
        background: #fff;
        border: 0px solid #fff;
        padding-bottom: 0px;
        margin-bottom: 0px;
        outline: none;
    }

    .search-box ul::-webkit-scrollbar {
        width: 10px;
        height: 6px;
    }

    .search-box ul::-webkit-scrollbar {
        width: 5px;
        height: 6px;
    }

    .search-box ul::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.2);
        -webkit-border-radius: 10px;
        border-radius: 10px;
    }

    .search-box ul::-webkit-scrollbar-thumb {
        -webkit-border-radius: 10px;
        border-radius: 10px;
        background: #4caf50;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
    }

    .uk-normal-scroll::-webkit-scrollbar-thumb {
        -webkit-border-radius: 10px;
        border-radius: 10px;
        background: #4caf50;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
    }

    .search-box ul::-webkit-scrollbar-thumb:window-inactive {
        background: rgb(193 192 192);
    }

    /* end of css for elastic search */
</style>

<!-- Core Style CSS -->
<link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/core-style.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/dist/css/style.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/animation/animate.min.css">

<?php
$state_select_array = (isset($state_record)) ? $state_record : array();
$city_select_array = array();
?>
<script>
    var liquor_count = 0;
    var cart_type = "<?= $cart_type ?>";
    var mode = "new";
    var action = 'ADD';
    var cart_id = 0;
    // alert(cart_type);
</script>
<!-- Main content -->
<section class="content">
    <div class="container">
        <!-- <div class="card"> -->
        <div class="products-catagories-area clearfix">
            <div class="search-container section-padding-50">
                <div class="container">
                    <div class="row">
                        <div class="col-12 ">
                            <div class="search-content-div">
                                <form action="#" method="get">
                                    <!--state and city name-->
                                    <!-- <div class="row">
                                        <div class="col-6">
                                            <?php if ($cart_type == 'consumer') {
                                                $this->load->view('master/select_field', array("field_id" => "select_state", "label" => "state", "place_holder" => "Select a state", "option_record" => $state_select_array, "option_value" => "id", "option_text" => "state", "selected_value" => ''));
                                            } ?>

                                            <?php // $this->load->view('master/select_field', array("field_id" => "select_state", "label" => "state", "place_holder" => "Select a state", "option_record" => $state_select_array, "option_value" => "id", "option_text" => "state", "selected_value" => '')); 
                                            ?>
                                        </div>
                                        <div class="col-6">
                                            <?php // $this->load->view('master/select_field', array("field_id" => "select_city", "label" => "city", "place_holder" => "Select a city", "option_record" => $city_select_array, "option_value" => "id", "option_text" => "city_district_name", "selected_value" => '')); 
                                            ?>



                                        </div>
                                    </div> -->
                                    <div class="row">
                                        <div class="col-12">
                                            <!-- <input type="search"  style="z-index: 1;" class="rounded" name="search" id="search" placeholder="Type your keyword...">
                                            <button type="submit" style="z-index: 99999;position: fixed;"><i class="fa fa-search fa-sm" style="color: white; padding: 10px;"></i></button>
                                            -->
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" name="search_product" id="search_product" placeholder="Search Product..." autocomplete="off">
                                                <span class="input-group-append">
                                                    <button type="button" class="btn btn-info btn-flat"><i class="fa fa-search fa-sm" style="color: white;"></i></button>
                                                </span>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger btn-flat" id="checkout" style="background-color:orange;"><i class="fa fa-shopping-cart"></i></button>
                                                </div>
                                            </div>
                                            <div class="search-box" id="search-results" style="display: block;">

                                            </div>
                                        </div>

                                    </div>


                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="amado-pro-catagory clearfix" id="product_data">

            </div>
            <br>
            <!-- <div>
                <center>
                    <div class="pagination">
                        <a href="#">&laquo;</a>
                        <a href="#rowone" class="active">1</a>
                        <a href="#rowtwo" >2</a>
                        <a href="#rowone">3</a>
                        <a href="#">4</a>
                        <a href="#">5</a>
                        <a href="#">6</a>
                        <a href="#">&raquo;</a>
                    </div>
                </center>
            </div> -->

        </div>
        <!-- </div> -->
    </div>
    <!--/. container-fluid -->
</section>
<!-- /.content -->
</div>

<!-- /.content-wrapper -->
<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var baseurl = "<?php echo base_url(); ?>";
</script>

<!-- PAGE PLUGINS -->
<!-- SparkLine -->
<script src="<?= base_url() ?>assets/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jVectorMap -->
<script src="<?= base_url() ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>>
<script>
    fetch_products('ALL');
    $("#checkout").click(function() {
        // cart_id,cart_type;
        window.location.href = DOMAIN + "cart/CartDetails/viewSessionCart";
    });

    $("#select_state").select2({
        width: '100%',
        placeholder: 'Select a state'
    });
    $("#select_city").select2({
        width: '100%',
        placeholder: 'Select a city'
    });
    $("#select_state").change(function() {

        checkInputEmpty("select_state", "Kindly select a state");
        var state_id = $(this).val();
        $("#select_city").val('').trigger('change');
        $.ajax({
            url: DOMAIN + 'admin/order/Ordering/getCityList',
            method: 'POST',
            data: {
                csrf_test_name: csrfHash,
                state_id: state_id
            },
            success: function(response) {
                var result = JSON.parse(response);
                var city_option_html = '<option></option>';
                for (var i = 0; i < result.length; i++) {
                    city_option_html += "<option value='" + result[i].id + "'>" + result[i].city_district_name + "</option>"
                }
                $("#select_city").html(city_option_html);
            },
            errror: function() {
                swal("Can't reach to the server");
            }
        });
    });

    $('#search_product').keyup(function() {
        var keyword = $('#search_product').val();
        var selected_state = $("#select_state").val();
        var selected_city = $("#select_city").val();
        //        alert(keyword);
        if (keyword != "" && keyword.length > 2) {
            $.ajax({
                //        url: "https://search.tutorialspoint.com/market_urls_suggestion.php",
                url: DOMAIN + 'admin/order/Ordering/getProductList',
                method: 'POST',
                data: {
                    csrf_test_name: csrfHash,
                    keyword: keyword,
                    selected_state: selected_state,
                    selected_city: selected_city,
                    cart_type: cart_type
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    console.log(data['list_product_name']);
                    if (data['list_product_name'].length > 0 || data['list_product_type'].length > 0) {
                        var str = '<div class="search-overlay"><ul>';
                        for (var i = 0; i < data['list_product_name'].length; i++) {
                            var iconStr = '<i class="fa fa-beer"></i>';
                            str = str + '<li class="clsHeadQuestion" onclick="fetch_products(this.id)" id="' + data['list_product_name'][i].product_name + '">' + iconStr + ' ' + data['list_product_name'][i].product_name + '</li>';
                        }
                        for (var i = 0; i < data['list_product_type'].length; i++) {
                            var iconStr = '<i class="fa fa-beer"></i>';
                            str = str + '<li class="clsHeadQuestion" onclick="fetch_products(this.id)" id="' + data['list_product_type'][i].product_type + '">' + iconStr + ' ' + data['list_product_type'][i].product_type + '</li>';
                        }
                        str = str + '</ul><br/></div><div class="clear"></div>';
                        $('#search-results').show();
                        $('#search-results').html(str);
                    } else {
                        $('#search-results').hide();
                        $('#search-results').html('');
                    }
                },
                error: function(err) {
                    swal("Can't reach to the server");
                    console.log(err);
                }
            });
        } else {
            $('#search-results').hide();
            $('#search-results').html('');
        }
    });

    function increment_decrement_quantity(quantity_box_id, product_id, add_sub_type) {
        // alert('clicked');
        var quantity_value = document.getElementById(quantity_box_id).value;
        if (add_sub_type == 'A') {
            document.getElementById(quantity_box_id).value = ++quantity_value;
        } else if (add_sub_type == 'S') {
            quantity_value = quantity_value > 1 ? --quantity_value : 1;
            document.getElementById(quantity_box_id).value = quantity_value;
        }

        $.ajax({
            url: DOMAIN + 'admin/order/Ordering/updateQuantityInCartSession',
            method: 'POST',
            data: {
                csrf_test_name: csrfHash,
                product_id: product_id,
                product_quantity: quantity_value
            },
            success: function(response) {
                var data = JSON.parse(response);
                console.log(data.message);
                console.log(data.session_data);
            },
            error: function(err) {
                swal("Can't reach to the server");
                console.log(err);
            }
        });
    }

    function cart_processing(product_id, product_name, product_price, quantity_box_id, individual_product_cart_id, redirect_path, add_remove_type) {
        if (add_remove_type == '0') {
            document.getElementById(individual_product_cart_id).text = 'Liquor Added';
            // document.getElementById(individual_product_cart_id).setAttribute("onclick", "cart_processing('" + product_id + "','" + product_name + "','" + product_price + "','" + quantity_box_id + "','" + individual_product_cart_id + "','" + redirect_path + "','1')");
            // document.getElementById(individual_product_cart_id).removeAttribute("background-color");
            // document.getElementById(individual_product_cart_id).setAttribute("background-color", "green");
            $("#" + individual_product_cart_id).css({
                "background-color": "green !important"
            });
        }
        // else if (add_remove_type == '1') {
        //     document.getElementById(individual_product_cart_id).text = 'Add to Cart';
        //     document.getElementById(individual_product_cart_id).setAttribute("onclick", "cart_processing('" + product_id + "','" + product_name + "','" + product_price + "','" + quantity_box_id + "','" + individual_product_cart_id + "','" + redirect_path + "','0')");
        // }
        ++liquor_count;
        var quantity_value = document.getElementById(quantity_box_id).value;
        var product_details = {
            csrf_test_name: csrfHash,
            product_id: product_id,
            product_name: product_name,
            product_price: product_price,
            product_quantity: quantity_value,
            add_remove_type: add_remove_type,
            liquor_count: liquor_count,
            cart_type: cart_type,
            mode: mode,
            cart_id: cart_id,
        }
        console.log(product_details);

        $.ajax({
            url: DOMAIN + 'admin/order/Ordering/addToCart',
            method: 'POST',
            data: product_details,
            success: function(response) {
                var data = JSON.parse(response);
                if (data[0].MESSAGE == 'success') {
                    cart_id = data[0].V_CART_ID;
                    mode = 'existing';
                    Swal.fire({
                        title: 'Product added',
                        text: '',
                        icon: 'success',
                        showConfirmButton: false,
                        toast: true
                    })


                }

                console.log(cart_id);
                console.log(mode);


                // console.log(data);
                // console.log(data.message);
                // console.log(data.session_data);
            },
            error: function(err) {
                swal("Can't reach to the server");
                console.log(err);
            }
        });
        //        
        //        var cart_text = document.getElementById(individual_product_cart_id).text;
        //        
        //        var cart_data = {product_id:product_id, product_name:product_name, product_price:product_price};
        //        var product_cart_BSF = {CART1:cart_data};
        //        
        //        sessionStorage.setItem('product_cart_BSF', JSON.stringify(product_cart_BSF));
        //        var temp = sessionStorage.getItem('product_cart_BSF');
        //        var viewName = $.parseJSON(temp);
        //        var div = '<div>' + viewName.FirstName + ' ' + viewName.LastName + ' is ' + viewName.Age + ' years old.' + '</div>';
        //        
        //        var objectLength = Object.keys(product_cart_BSF).length;
    }

    function fetch_products(keyword) {
        var selected_state = $("#select_state").val();
        var selected_city = $("#select_city").val();
        $('#search-results').hide();
        $('#search-results').html('');
        $.ajax({
            //        url: "https://search.tutorialspoint.com/market_urls_suggestion.php",
            url: DOMAIN + 'admin/order/Ordering/displayProducts',
            method: 'POST',
            data: {
                csrf_test_name: csrfHash,
                keyword: keyword,
                selected_state: selected_state,
                selected_city: selected_city,
                cart_type: cart_type
            },
            success: function(response) {
                //            var data = JSON.parse(response);
                //        alert(products_name.length+'--'+response);
                //            if (data.length > 0) {
                $('#product_data').show();
                $('#product_data').html(response);
                //            } else {
                //                $('#product_data').hide();
                //                $('#product_data').html('');
                //            }
            },
            error: function(err) {
                swal("Can't reach to the server");
                console.log(err);
            }
        });
    }
</script>
<script src="<?= base_url() ?>assets/js/module/common/validation.js"></script>