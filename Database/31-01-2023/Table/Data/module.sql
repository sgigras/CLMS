insert into itbp_clds.module (module_id, module_name, controller_name, fa_icon, operation, sort_order, roleid)
values  (2, 'role_and_permissions', 'admin/admin_roles', 'fa fa-sun', 'view|add|edit|delete|change_status|access', 10, 63),
        (3, 'users', 'admin/users', '<h1>HTML</h1>', 'view|add|edit|delete|change_status|access', 11, 63),
        (7, 'backup_and_export', 'admin/export', '[removed]alert&#40;1&#41;[removed]', 'access', 12, 63),
        (8, 'settings', 'admin/general_settings', 'fa fa-cogs', 'view|add|edit|access', 13, 63),
        (9, 'dashboard', 'admin/dashboard', 'fa fa-dashboard', 'view|index|index_2|index_3|access', 14, 63),
        (10, 'codeigniter_examples', 'admin/example', 'fa fa-snowflake-o', 'access', 15, 63),
        (11, 'invoicing_system', 'admin/invoices', 'fa fa-files-o', 'access', 16, 63),
        (12, 'database_joins_example', 'admin/joins', 'fa fa-external-link-square', 'access', 17, 63),
        (13, 'language_setting', 'admin/languages', 'fa fa-language', 'access', 18, 63),
        (14, 'locations', 'admin/location', 'fa fa-map-pin', 'access', 19, 63),
        (15, 'widgets', 'admin/widgets', 'fa fa-th', 'access', 20, 63),
        (16, 'charts', 'admin/charts', 'fa fa-line-chart', 'access', 21, 63),
        (17, 'ui_elements', 'admin/ui', 'fa fa-tree', 'access', 22, 63),
        (18, 'forms', 'admin/forms', 'fa fa-edit', 'access', 23, 63),
        (19, 'tables', 'admin/tables', 'fa fa-table', 'access', 24, 63),
        (21, 'mailbox', 'admin/mailbox', 'fa fa-envelope-o', 'access', 25, 63),
        (22, 'pages', 'admin/pages', 'fa fa-book', 'access', 26, 63),
        (23, 'extras', 'admin/extras', 'fa fa-plus-square-o', 'access', 27, 63),
        (25, 'profile', 'admin/profile', 'fa fa-user', 'access', 28, 63),
        (26, 'activity_log', 'admin/activity', 'fa fa-flag-o', 'access', 29, 63),
        (37, 'brewery', 'master/BreweryMaster', 'fa fa-beer', 'view|add|edit|delete|change_status|access', 3, 63),
        (38, 'canteen', 'master/CanteenMaster', 'fa fa-beer', 'index|addCanteenClub|editCanteenClub|getCityList|getDistrubutors|access', 3, 63),
        (41, 'tax', 'master/Tax_masterAPI', 'fa fa-inr', 'add|edit|delete|index|addTaxNames|editTaxNames|tax_Mapping|access', 3, 63),
        (43, 'cart', 'cart/CartDetails', 'fa fa-shopping-basket', 'index|cardTotal|placeOrder|access', 3, 63),
        (45, 'city', 'master/City_masterAPI', 'fa fa-building', 'index|addCityDetails|editCityDetails|access', 4, 63),
        (46, 'alcohol', 'master/Alcohol_masterAPI', 'fa fa-beer', 'index|addalcholType|editalcoholNames|access', 5, 63),
        (49, 'liquor_catalogue', 'admin/order/Ordering', 'fa fa-beer', 'your_order|getProductList|getCityList|displayProducts|addToCart|updateQuantityInCartSession|access', 6, 63),
        (51, 'liquor_inventory', 'admin/order/Liquor_Inventory', 'fa fa-beer', 'index|access', 8, 63),
        (52, 'liquor_issue', 'order/OrderDetails', 'fas fa-receipt', 'index|loadOrderCode|fetchOrderDetails|modifyCartDetails|completeDeliveryProcess|fetchCartDetails|displaySessionDeliveryOrder|access', 3, 63),
        (53, 'home', 'admin/home', 'fa fa-home', 'index', 1, 63),
        (54, 'your_orders', 'order/YourOrderAPI/', 'fas fa-receipt', 'index|cancelOrder', 11, 63),
        (55, 'import_data', 'admin/Import_excel', 'fa fa-file-excel-o', 'import|access', 15, 63),
        (59, 'register_retiree', 'user_details/RegisterRetiree', 'fa fa-user', 'index|fetchRetireeInitialFormDetails|addRetiree|updateRetiree|fetchRetireeDetails|checkRetireeData|uploadPics', 12, 65),
        (60, 'verify_retiree', 'user_details/VerifyRetireeDetails', 'far fa-check-circle', 'index|access|fetchRetireeDetails|verifyRetiree|approve_deny_user', 11, null),
        (61, 'test_module1', 'module_test', 'fa fa-search', 'index|find_liquor|find_liquor_store|access', 1, null),
        (62, 'personnel_details', 'user_details/User_details', 'fas fa-user', 'index', 10, null),
        (63, 'register_nok', 'user_details/NOKRegister', 'fa fa-user', 'index', 14, null),
        (64, 'registration_status', 'user_details/RegistrationReport/', 'fa fa-user', 'index|getRetireeDetails|viewPostinUnit|GetPostingWise', 10, null),
        (66, 'Liquor->Alcohol Master', '$alcohol_type', '<H1>HTML</H1>', '<H1>HTML</H1>', 127, null),
        (68, 'received_liquor', 'order/ReceivedLiquorAPI', 'fa fa-inr', 'received', 1, null);