create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_ADDITIONAL_SHEETS(IN P_DATA json)
BEGIN

    DECLARE V_SWAL_TYPE, V_SWAL_TITLE, V_SWAL_TEXT, V_SALES_TYPE, V_SELECT_TYPE, V_PURPOSE,V_LIQUOR_NAME,V_LIQUOR_LIST,V_ORDER_CODE,V_GENERATE_ORDER_CODE VARCHAR(225);
    DECLARE V_MESSAGE_LIQUOR_QTY TEXT;
    DECLARE V_ADDITIONAL_SHEETS_DATA JSON;
    DECLARE V_USER_ID,V_IRLA BIGINT;
    DECLARE V_ADDITIONAL_SHEETS_DATA_COUNT, V1, V_LIQUOR_ENTITY_ID, V_QUANTITY, V_TOTAL, V_ENTITY_ID,
    V_AVAILABLE_QUANTITY,V_NEW_QUANTITY, V_CHECK_QUANTITY, V_ACTUAL_AVAILABLE_QUANTITY,V_LIQUOR_DESCRIPTION_ID,V_BALANCE,V_CURRENT_AVAILABLE_QUANTITY, V_ORDERED_TO_ENTITY_TYPE,V_CART_ID INT DEFAULT 0;
    DECLARE V_SELLING_PRICE,V_PURCHASE_PRICE,V_UNIT_COST,V_UNIT_PROFIT,V_TOTAL_PURCHASE_PRICE,V_TOTAL_COST,V_TOTAL_PROFIT FLOAT(11,2);
    DECLARE V_QUOTA_EXCEEDED,V_USED_QUOTA,V_ALLOWED_QUOTA,V_ALLOCATED_QUOTA,V_TOTAL_QUANTITY, V_QUOTA INT;
    DECLARE V_DATE_OF_BIRTH DATE;


    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        GET DIAGNOSTICS CONDITION 1
        @P1 = MESSAGE_TEXT, @P2 = RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name, sp_name, data_passed,  error_message, returne_sql_state)
        VALUES('ADDITIONAL_SHEETS', 'SP_NEW_AVAILABLE_STOCK', P_DATA, @P1, @P2);
        SET V_SWAL_TYPE = "warning", V_SWAL_TITLE = "unable to issue", V_SWAL_TEXT = "";
        SELECT V_SWAL_TYPE, V_SWAL_TITLE, V_SWAL_TEXT, @P1, @P2;

    END;

    START TRANSACTION;
        SET V_CHECK_QUANTITY = 0;

        SET V_USER_ID = FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
        SET V_ENTITY_ID = FN_EXTRACT_JSON_DATA(P_DATA,0,'entity_id');
        SET V_SALES_TYPE = FN_EXTRACT_JSON_DATA(P_DATA,0,'sales_type');
        SET V_SELECT_TYPE = FN_EXTRACT_JSON_DATA(P_DATA,0,'select_type');
        SET V_PURPOSE = FN_EXTRACT_JSON_DATA(P_DATA,0,'purpose');
        SET V_ADDITIONAL_SHEETS_DATA = FN_EXTRACT_JSON_DATA(P_DATA,0,'additional_sheets_data');


        SET V_ADDITIONAL_SHEETS_DATA_COUNT = JSON_LENGTH(V_ADDITIONAL_SHEETS_DATA);
        SET V1 = 0;
        SET V_LIQUOR_LIST='';
        SET V_MESSAGE_LIQUOR_QTY='';

        SELECT entity_type
        INTO V_ORDERED_TO_ENTITY_TYPE
        FROM master_entities where id=V_ENTITY_ID;


        SET V_ORDER_CODE="";
        WHILE(V_ORDER_CODE="") DO
            SET V_GENERATE_ORDER_CODE=lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0);
            IF NOT EXISTS (SELECT order_code FROM cart_details WHERE order_code=V_GENERATE_ORDER_CODE) THEN
                BEGIN

                    SET V_ORDER_CODE=V_GENERATE_ORDER_CODE;
                END;
            END IF;
        END WHILE;

        INSERT INTO cart_details
                (order_code,ordered_to_entity_id,liquor_count,ordered_to_entity_type,order_from_id,order_from_entity_type,is_active,order_by_userid,order_time,cart_type,is_order_placed,is_order_delivered,is_order_cancel,is_order_received,is_additional_sale)
            VALUES(
                    V_ORDER_CODE,V_ENTITY_ID,V_ADDITIONAL_SHEETS_DATA_COUNT,V_ORDERED_TO_ENTITY_TYPE,V_SELECT_TYPE,6,1,V_SELECT_TYPE,NOW(),'consumer',1,1,0,1,1
        );

        SELECT id INTO V_CART_ID FROM cart_details WHERE order_code=V_ORDER_CODE;

        SET V_TOTAL_QUANTITY=0;

        WHILE(V1<V_ADDITIONAL_SHEETS_DATA_COUNT) DO
        BEGIN

                SET V_LIQUOR_ENTITY_ID = FN_EXTRACT_JSON_DATA(V_ADDITIONAL_SHEETS_DATA, V1, 'liquor_entity_id');
                SET V_SELLING_PRICE = FN_EXTRACT_JSON_DATA(V_ADDITIONAL_SHEETS_DATA, V1, 'selling_price');
                SET V_UNIT_COST = FN_EXTRACT_JSON_DATA(V_ADDITIONAL_SHEETS_DATA, V1, 'selling_price');
                SET V_QUANTITY = FN_EXTRACT_JSON_DATA(V_ADDITIONAL_SHEETS_DATA, V1, 'quantity');
                SET V_TOTAL_COST = FN_EXTRACT_JSON_DATA(V_ADDITIONAL_SHEETS_DATA, V1,'total');

                SET V_TOTAL_QUANTITY=V_TOTAL_QUANTITY+V_QUANTITY;

                SELECT lem.liquor_description_id,lem.selling_price,lem.purchase_price,(lem.selling_price-lem.purchase_price),lem.available_quantity, lem.actual_available_quantity,CONcat(ld.brand,' ',liquor_type)
                INTO V_LIQUOR_DESCRIPTION_ID,V_SELLING_PRICE,V_PURCHASE_PRICE,V_UNIT_PROFIT,V_AVAILABLE_QUANTITY ,V_ACTUAL_AVAILABLE_QUANTITY ,V_LIQUOR_NAME
                FROM liquor_entity_mapping lem
                INNER JOIN liquor_details ld on ld.liquor_description_id =lem.liquor_description_id
                WHERE id = V_LIQUOR_ENTITY_ID;





                IF ((V_AVAILABLE_QUANTITY < V_QUANTITY) OR (V_ACTUAL_AVAILABLE_QUANTITY < V_QUANTITY) OR (V_ACTUAL_AVAILABLE_QUANTITY<1) OR (V_AVAILABLE_QUANTITY<1)) THEN
                    BEGIN
                        IF(V_AVAILABLE_QUANTITY=0)THEN
                            BEGIN
                                SET V_MESSAGE_LIQUOR_QTY=CONCAT(V_MESSAGE_LIQUOR_QTY,V_LIQUOR_NAME,' is not available');
                            END;
                        ELSE
                            BEGIN
                                SET V_MESSAGE_LIQUOR_QTY=CONCAT(V_MESSAGE_LIQUOR_QTY,', ONLY ',V_AVAILABLE_QUANTITY ,' bottles are left for ',V_LIQUOR_NAME);
                            END;
                        END IF;
                        SET V_MESSAGE_LIQUOR_QTY=TRIM(BOTH "," FROM V_MESSAGE_LIQUOR_QTY);
                        SET V_SWAL_TYPE = "warning", V_SWAL_TITLE = "", V_SWAL_TEXT = V_MESSAGE_LIQUOR_QTY;
                        SET V_CHECK_QUANTITY = 1;
                    END;
                ELSE
                    BEGIN
                        SET V_AVAILABLE_QUANTITY = V_AVAILABLE_QUANTITY - V_QUANTITY ;
                        SET V_ACTUAL_AVAILABLE_QUANTITY = V_ACTUAL_AVAILABLE_QUANTITY - V_QUANTITY ;


                        INSERT INTO order_details(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,dispatch_quantity,dispatch_cost_lot_size,dispatch_total_cost_bottles,recevied_quantity,recevied_cost_lot_size,recevied_total_cost_bottles,is_liquor_added,liquor_added_by,liquor_add_time,order_process,order_time,order_by,dispatch_time,dispatch_by)
                                 VALUES(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_SELLING_PRICE,V_TOTAL_COST,V_QUANTITY,V_SELLING_PRICE,V_TOTAL_COST,V_QUANTITY,V_SELLING_PRICE,V_TOTAL_COST,1,V_SELECT_TYPE,NOW(),3,NOW(),V_SELECT_TYPE,NOW(),V_USER_ID);

                        INSERT INTO additional_sheets (user_id,order_code,cart_id, entity_id, sales_type, select_type, purpose, liquor_entity_id, selling_price, quantity, total, sale_by, sale_time)
                                values (V_USER_ID,V_ORDER_CODE,V_CART_ID, V_ENTITY_ID, V_SALES_TYPE, V_SELECT_TYPE, V_PURPOSE, V_LIQUOR_ENTITY_ID, V_SELLING_PRICE, V_QUANTITY, V_TOTAL, V_USER_ID, NOW());

                        IF NOT EXISTS(SELECT id FROM liquor_stock_sales WHERE liquor_entity_id=V_LIQUOR_ENTITY_ID AND insert_date=curdate())THEN
                            BEGIN

                                SET V_TOTAL_PURCHASE_PRICE=V_QUANTITY*V_PURCHASE_PRICE;

                                SET V_TOTAL_PROFIT=V_TOTAL_COST-V_TOTAL_PURCHASE_PRICE;

                                SET V_BALANCE=V_ACTUAL_AVAILABLE_QUANTITY;

                                INSERT INTO liquor_stock_sales(entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_total_purchase_price,liquor_total_sale_price,liquor_profit,liquor_balance,modification_time,modified_by,insert_date)
                                            VALUES(V_ENTITY_ID,V_LIQUOR_ENTITY_ID,V_LIQUOR_DESCRIPTION_ID,(V_ACTUAL_AVAILABLE_QUANTITY+V_QUANTITY),V_QUANTITY,V_PURCHASE_PRICE,V_UNIT_COST,V_UNIT_PROFIT,V_TOTAL_PURCHASE_PRICE,V_TOTAL_COST,V_TOTAL_PROFIT,V_BALANCE,NOW(),V_USER_ID,CURDATE());

                            END;
                        ELSE
                            BEGIN

                                INSERT INTO log_liquor_stock_sales(entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_total_purchase_price,liquor_total_sale_price,liquor_profit,liquor_balance,modification_time,modified_by,insert_date,order_code)
                                SELECT  entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_total_purchase_price,liquor_total_sale_price,liquor_profit,liquor_balance,modification_time,modified_by,insert_date,(SELECT V_ORDER_CODE) FROM liquor_stock_sales where liquor_entity_id=V_LIQUOR_ENTITY_ID;

                                SELECT liquor_sale_qty
                                INTO V_CURRENT_AVAILABLE_QUANTITY
                                FROM liquor_stock_sales
                                WHERE entity_id=V_ENTITY_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID AND  insert_date=curdate() order by id desc LIMIT 1;

                                SET V_NEW_QUANTITY=V_CURRENT_AVAILABLE_QUANTITY+V_QUANTITY;

                                SET V_TOTAL_PURCHASE_PRICE=V_NEW_QUANTITY*V_PURCHASE_PRICE;

                                SET V_TOTAL_COST=V_NEW_QUANTITY*V_SELLING_PRICE;

                                SET V_TOTAL_PROFIT=V_TOTAL_COST-V_TOTAL_PURCHASE_PRICE;

                                SET V_BALANCE=V_ACTUAL_AVAILABLE_QUANTITY;

                                UPDATE liquor_stock_sales
                                SET
                                liquor_sale_qty=V_NEW_QUANTITY,
                                liquor_total_purchase_price=V_TOTAL_PURCHASE_PRICE,
                                liquor_total_sale_price=V_TOTAL_COST,
                                liquor_profit=V_TOTAL_PROFIT,
                                liquor_balance=V_BALANCE,
                                modification_time=NOW(),
                                modified_by=V_USER_ID
                                WHERE entity_id=V_ENTITY_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID  and insert_date=CURDATE();

                            END;
                        END IF;

                        INSERT INTO log_liquor_entity_mapping(
                        `liquor_entity_id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
                        `entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
                        `selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,
                        `actual_available_quantity`,`city_id`,`state_id`,`creation_time`,`created_type`,`reorder_level`,`updated_from`
                        )SELECT `id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
                        `entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
                        `selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,
                        `actual_available_quantity`,`city_id`,`state_id`,(SELECT NOW()),(SELECT V_USER_ID),`reorder_level`,'ADDITIONAL_SHEETS'
                        FROM liquor_entity_mapping WHERE id=V_LIQUOR_ENTITY_ID;

                        UPDATE liquor_entity_mapping
                        SET available_quantity = V_AVAILABLE_QUANTITY, actual_available_quantity = V_ACTUAL_AVAILABLE_QUANTITY
                        WHERE id = V_LIQUOR_ENTITY_ID;
                    END;
                END IF;
            SET V1 = V1+1;
        END;
        END WHILE;



    IF(V_SALES_TYPE='Mess')THEN
        BEGIN
           SET V_QUOTA_EXCEEDED=0;
        END;
    ELSE
        BEGIN



			SET V_QUOTA=24;

            SELECT IFNULL(SUM(luq.liquor_count),0) INTO V_USED_QUOTA FROM
            liquor_user_used_quota luq
            WHERE userid=V_SELECT_TYPE AND MONTH(luq.insert_time)= MONTH(NOW()) AND is_beer=1 AND  luq.order_status!=3;

            SET V_ALLOWED_QUOTA=V_QUOTA-V_USED_QUOTA;

            IF(V_ALLOWED_QUOTA<V_TOTAL_QUANTITY)THEN
                BEGIN
                    SET V_SWAL_TYPE = "warning", V_SWAL_TITLE = "", V_SWAL_TEXT = CONCAT(V_ALLOWED_QUOTA,' bottles are only allowed') ;
                    SET V_QUOTA_EXCEEDED=1;
                END;
            ELSE
                BEGIN
                    INSERT INTO liquor_user_used_quota(userid,liquor_count,order_code,order_status,insert_time,created_by,is_beer,isactive)
                                                VALUES(V_SELECT_TYPE,V_TOTAL_QUANTITY,V_ORDER_CODE,2,NOW(),V_SELECT_TYPE,1,1);
                  SET V_QUOTA_EXCEEDED=0;
                END;
            END IF;
        END;
    END IF;

    IF (V_CHECK_QUANTITY = 0 AND V_QUOTA_EXCEEDED=0) THEN
        BEGIN

            COMMIT;
            SET V_SWAL_TYPE = "success", V_SWAL_TITLE = "Liquor issued sucessfully.", V_SWAL_TEXT = "";
        END;
    ELSE
        BEGIN
            ROLLBACK;
        END;
    END IF;

SELECT
    V_USER_ID,
    V_QUOTA,
    V_TOTAL_QUANTITY,
    V_USED_QUOTA,
    V_QUOTA_EXCEEDED,
    V_ALLOWED_QUOTA,
    V_ORDER_CODE,
    V_ENTITY_ID,
    V_SALES_TYPE,
    V_SELECT_TYPE,
    V_PURPOSE,
    V_ADDITIONAL_SHEETS_DATA,
    V_ADDITIONAL_SHEETS_DATA_COUNT,
    V_SWAL_TYPE,
    V_SWAL_TITLE,
    V_SWAL_TEXT,
    V_AVAILABLE_QUANTITY,
    V_ACTUAL_AVAILABLE_QUANTITY,
    V_QUANTITY;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_ADD_REMOVE_LIQUOR(IN P_DATA json)
BEGIN
	DECLARE V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_MODE,V_ACTION VARCHAR(200);
    DECLARE V_CART_ID,V_USER_ID,V_LIQUOR_ENTITY_ID,V_LIQUOR_ID,V_ORDERED_TO_ENTITY_ID INT;
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('CART','SP_ADD_REMOVE_LIQUOR',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Unable to perform the action',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

   START TRANSACTION;

        SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');
        SET V_ACTION=FN_EXTRACT_JSON_DATA(P_DATA,0,'action');
        SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
        SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');

		SELECT ordered_to_entity_id
		INTO V_ORDERED_TO_ENTITY_ID
		FROM cart_details WHERE id=V_CART_ID;

        IF(V_MODE='A')THEN
			BEGIN
				SET V_LIQUOR_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_id');

                SELECT id INTO V_LIQUOR_ENTITY_ID
				FROM liquor_entity_mapping
				WHERE entity_id=V_ORDERED_TO_ENTITY_ID and liquor_description_id=V_LIQUOR_ID LIMIT 1;

			END;
		ELSE
			BEGIN
				SET V_LIQUOR_ENTITY_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_id');
			END;
		END IF;

        UPDATE cart_liquor
        SET is_liquor_removed=V_ACTION
        WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

        SET V_SWAL_TYPE='success',V_SWAL_TITLE='Unable to perform the action',V_SWAL_TEXT='Cart failed';

   COMMIT;
   SELECT V_SWAL_TYPE,V_LIQUOR_ENTITY_ID,V_CART_ID,V_ACTION;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_ADD_STOCK(IN P_DATA json)
BEGIN
	DECLARE V_MODE,V_LIQUOR_TYPE,V_LIQUOR_NAME,V_ENTITY_TYPE,  V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE VARCHAR(225);
    DECLARE V_LIQUOR_TYPE_ID,V_ID,V_USER_ID,V_MOQ,V_ML,V_ENTITY_ID,V_STOCK_SALE_ID,V_OPENING_QTY,V_NEW_OPENING_QTY,V_LIQUOR_BALANCE,V_SALE_QTY,V_NEW_STOCK,V_TOTAL_STOCK INT;
    DECLARE V_UNIT_PROFIT,V_LIQUOR_DESCRIPTION_ID,V_QUANTITY_DIFF,V_DISPLAY_AVAILABLE_QUANTITY,V_PHYSICAL_AVAILABLE_QUANTITY,V_AVAILABLE_QUANTITY,V_REORDER_LEVEL INT;
    DECLARE V_SELL_PRICE,V_BASE_PRICE,V_PURCHASE_PRICE,V_TOTAL_SELL_PRICE,V_TOTAL_PURCHASE_PRICE,V_TOTAL_PROFIT FLOAT(10,2);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;

        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
        VALUES('ADD STOCK','SP_ADD_STOCK',P_DATA,@P2,@P1);

         SET V_SWAL_TITLE='Error!',V_SWAL_MESSAGE='Something Went wrong, Please Contact Ats team',V_SWAL_TYPE='error';

        SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,@P1,@P2;
    END;

    START TRANSACTION;
            INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
        VALUES('ADD/EDIT PRODUCT MAPPING','SP_INSERT_UPDATE_LIQUOR_MAPPING_DETAILS',P_DATA,@P2,@P1);
		SET V_SWAL_TITLE='SUCCESS',V_SWAL_TYPE='success';


		SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
        SET V_TOTAL_STOCK=FN_EXTRACT_JSON_DATA(P_DATA,0,'total_stock');
		SET V_NEW_STOCK=FN_EXTRACT_JSON_DATA(P_DATA,0,'new_stock');
		SET V_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'id');


		SELECT entity_id,liquor_description_id,base_price,selling_price,purchase_price,available_quantity,actual_available_quantity
        INTO V_ENTITY_ID,V_LIQUOR_DESCRIPTION_ID,V_BASE_PRICE,V_SELL_PRICE,V_PURCHASE_PRICE,V_DISPLAY_AVAILABLE_QUANTITY,V_PHYSICAL_AVAILABLE_QUANTITY
		FROM liquor_entity_mapping WHERE id =V_ID;

		SET V_UNIT_PROFIT=V_SELL_PRICE-V_PURCHASE_PRICE;

		INSERT INTO liquor_stock_added(entity_id,liquor_entity_id,liquor_description_id,liquor_qty,liquor_unit_base_price,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,insert_date,insert_time,inserted_by)
        VALUES(V_ENTITY_ID,V_ID,V_LIQUOR_DESCRIPTION_ID,V_NEW_STOCK,V_BASE_PRICE,V_PURCHASE_PRICE,V_SELL_PRICE,V_UNIT_PROFIT,curdate(),NOW(),V_USER_ID);

		SET V_AVAILABLE_QUANTITY=V_DISPLAY_AVAILABLE_QUANTITY+V_NEW_STOCK;

		UPDATE liquor_entity_mapping
		SET available_quantity=V_AVAILABLE_QUANTITY,
			actual_available_quantity=V_TOTAL_STOCK,
			modified_by=V_USER_ID,
			modification_time=now(),
			reorder_level=V_REORDER_LEVEL
		WHERE id= V_ID;

		 INSERT INTO log_liquor_entity_mapping(
		`liquor_entity_id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
		`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
		`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`previous_available_quantity`,`available_quantity`,
		`previous_actual_available_quantity`,`actual_available_quantity`,`city_id`,`state_id`,`creation_time`,`created_type`,`reorder_level`,`updated_from`
		)SELECT  `id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
		`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
		`selling_price`,`minimun_order_type`,`single_piece_in_lot`,(SELECT V_DISPLAY_AVAILABLE_QUANTITY),`available_quantity`,(SELECT V_PHYSICAL_AVAILABLE_QUANTITY),
		`actual_available_quantity`,`city_id`,`state_id`,(SELECT NOW()),(SELECT V_USER_ID),`reorder_level`,'ADD_STOCK'
		FROM  liquor_entity_mapping  WHERE id=V_ID;


		IF EXISTS(SELECT id FROM liquor_stock_sales WHERE liquor_entity_id=V_ID AND insert_date=curdate())THEN
			BEGIN
				SELECT id,liquor_opening_qty,liquor_sale_qty,liquor_unit_profit
				INTO V_STOCK_SALE_ID,V_OPENING_QTY,V_SALE_QTY,V_UNIT_PROFIT
				FROM liquor_stock_sales
				WHERE liquor_entity_id=V_ID
                AND insert_date=curdate();

                SET V_NEW_OPENING_QTY=V_OPENING_QTY+V_NEW_STOCK;

				SET V_LIQUOR_BALANCE=V_TOTAL_STOCK;

                UPDATE liquor_stock_sales
				SET liquor_opening_qty=V_NEW_OPENING_QTY,
				liquor_balance=V_LIQUOR_BALANCE,
				modification_time=NOW(),
				modified_by=V_USER_ID
				WHERE id=V_STOCK_SALE_ID;

               INSERT INTO log_liquor_stock_sales(entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_total_purchase_price,liquor_total_sale_price,liquor_profit,liquor_balance,modification_time,modified_by,insert_date)
				SELECT 	entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_total_purchase_price,liquor_total_sale_price,liquor_profit,liquor_balance,modification_time,modified_by,insert_date FROM liquor_stock_sales
                where liquor_entity_id=V_ID;
            END;
        ELSE
			BEGIN

                INSERT INTO
				liquor_stock_sales(entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_balance,insert_date)
				SELECT entity_id,id,liquor_description_id,actual_available_quantity,0,purchase_price,selling_price,(selling_price-purchase_price),actual_available_quantity,curdate()
				from liquor_entity_mapping where id=V_ID;
            END;
        END IF;


		IF EXISTS(SELECT id FROM liquor_month_stock_sales WHERE liquor_entity_id=V_ID AND sale_month=MONTH(now()) AND sale_year=YEAR(NOW()))THEN
			BEGIN
				SELECT id,liquor_opening_qty,liquor_sale_qty,liquor_unit_profit
				INTO V_STOCK_SALE_ID,V_OPENING_QTY,V_SALE_QTY,V_UNIT_PROFIT
				FROM liquor_month_stock_sales
				WHERE liquor_entity_id=V_ID
                AND sale_month=MONTH(NOW()) AND sale_year=YEAR(NOW());

                SET V_NEW_OPENING_QTY=V_OPENING_QTY+V_NEW_STOCK;

				SET V_LIQUOR_BALANCE=V_TOTAL_STOCK;

                UPDATE liquor_month_stock_sales
				SET liquor_opening_qty=V_NEW_OPENING_QTY,
				liquor_balance=V_LIQUOR_BALANCE,
				modification_time=NOW(),
				modified_by=V_USER_ID
				WHERE id=V_STOCK_SALE_ID;

            END;
		ELSE
			BEGIN
				INSERT INTO
				liquor_month_stock_sales(entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,
                liquor_unit_sell_price,liquor_unit_profit,liquor_unit_tax,liquor_total_purchase_price,liquor_total_sale_price,liquor_profit,liquor_balance,sale_month,sale_year,insert_date)
				SELECT entity_id,id,liquor_description_id,actual_available_quantity,0,purchase_price,selling_price,(selling_price-purchase_price),0,0.00,0.00,0.00,actual_available_quantity,MONTH(NOW()),YEAR(NOW()),curdate()
				from liquor_entity_mapping where id=V_ID;
            END;
        END IF;



				SET V_SWAL_MESSAGE='Product details updated successfully';

    COMMIT;
        SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,V_LIQUOR_TYPE,V_LIQUOR_NAME,V_ENTITY_ID,V_REORDER_LEVEL;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_ADD_UPDATE_RETIREE_DATA(IN P_DATA longtext)
BEGIN
    DECLARE V_SWAL_TYPE,V_FORCE_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_NAME,V_EMAIL,V_MOBILE_NO,V_ADHAAR_CARD,V_PROFILE_IMAGE_IMG,V_CARD_PHOTO,V_POSTING_UNIT_TYPE,V_RANK,V_PERSSONEL_PHOTO,V_PPO_PHOTO,V_ADDRESS VARCHAR(500) DEFAULT '';
    DECLARE V_ENTITY_ID,V_RANK_ID INT;
    DECLARE V_USER_ID,V_PPO_NO,V_PERSSONEL_NO,V_CI_ADMIN_ID,V_APPROVAL_USERID BIGINT;
    DECLARE V_DATE_OF_BIRTH,V_VALID_UPTO,V_DATE_OF_JOINING,V_DATE_OF_RETIREMENT DATE;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                VALUES('ADD RETIREE','SP_ADD_UPDATE_RETIREE_DATA',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Unable to add Data';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;
        SET V_ADHAAR_CARD= FN_EXTRACT_JSON_DATA(P_DATA,0,'adhaar_card');
        SET V_DATE_OF_BIRTH= FN_EXTRACT_JSON_DATA(P_DATA,0,'date_of_birth');
        SET V_EMAIL= FN_EXTRACT_JSON_DATA(P_DATA,0,'email');
        SET V_FORCE_TYPE= FN_EXTRACT_JSON_DATA(P_DATA,0,'force_type');
        SET V_MOBILE_NO= FN_EXTRACT_JSON_DATA(P_DATA,0,'mobile_no');
        SET V_PERSSONEL_NO= FN_EXTRACT_JSON_DATA(P_DATA,0,'perssonel_no');
        SET V_POSTING_UNIT_TYPE= FN_EXTRACT_JSON_DATA(P_DATA,0,'posting_unit_type');
        SET V_PPO_NO= FN_EXTRACT_JSON_DATA(P_DATA,0,'ppo_no');
        SET V_PERSSONEL_PHOTO= REPLACE(FN_EXTRACT_JSON_DATA(P_DATA,0,'personnel_photo'),'\\','');
        SET V_PPO_PHOTO= REPLACE(FN_EXTRACT_JSON_DATA(P_DATA,0,'ppo_photo'),'\\','');
        SET V_CARD_PHOTO= REPLACE(FN_EXTRACT_JSON_DATA(P_DATA,0,'card_photo'),'\\','');
        SET V_ADDRESS=FN_EXTRACT_JSON_DATA(P_DATA,0,'address');
        SET V_RANK = FN_EXTRACT_JSON_DATA(P_DATA,0,'rank');
        SET V_VALID_UPTO= DATE_ADD(CURDATE() , INTERVAL 1 YEAR);
        SET V_USER_ID= FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
        SET V_NAME=FN_EXTRACT_JSON_DATA(P_DATA,0,'name');
        SET V_DATE_OF_JOINING=FN_EXTRACT_JSON_DATA(P_DATA,0,'date_of_joining');
        SET V_DATE_OF_RETIREMENT=FN_EXTRACT_JSON_DATA(P_DATA,0,'date_of_retirement');

        SELECT id INTO V_RANK_ID from master_rank WHERE `rank`=V_RANK;

        IF NOT EXISTS(SELECT admin_id FROM ci_admin WHERE username=V_PERSSONEL_NO AND date_of_birth=V_DATE_OF_BIRTH)THEN
            BEGIN
                IF EXISTS(SELECT * FROM ci_admin WHERE username=V_PERSSONEL_NO AND date_of_birth=V_DATE_OF_BIRTH)THEN
                    BEGIN
                        UPDATE ci_admin
                        SET mobile_no=V_MOBILE_NO,email=V_EMAIL,valid_upto=V_VALID_UPTO,capf_force=V_FORCE_TYPE,
                            personnel_photo=V_PERSSONEL_PHOTO,card_photo=V_CARD_PHOTO,ppo_photo=V_PPO_PHOTO,is_verify=0,ppo_no=V_PPO_NO,updated_at=NOW(),
                            retirement_date=V_DATE_OF_RETIREMENT,joining_date=V_DATE_OF_JOINING,permanent_address=V_ADDRESS
                        WHERE username=V_PERSSONEL_NO AND date_of_birth=V_DATE_OF_BIRTH;
                    END;
                ELSE
                    BEGIN

                        INSERT INTO ci_admin
                        (username,firstname,mobile_no,date_of_birth,rankid,user_rank,status,email,created_at,
                        created_by,valid_upto,retirement_date,joining_date,capf_force,personnel_photo,ppo_photo,card_photo,updated_at,updated_by,
                        is_verify,UnitName,ppo_no,adhaar_card,permanent_address)
                        VALUES
                        (V_PERSSONEL_NO,V_NAME,V_MOBILE_NO,V_DATE_OF_BIRTH,V_RANK_ID,V_RANK,'RETIRED',V_EMAIL,NOW(),V_USER_ID,V_VALID_UPTO,V_DATE_OF_RETIREMENT,V_DATE_OF_JOINING,V_FORCE_TYPE,
                        V_PERSSONEL_PHOTO,V_PPO_PHOTO,V_CARD_PHOTO,NOW(),V_USER_ID,0,V_POSTING_UNIT_TYPE,V_PPO_NO,V_ADHAAR_CARD,V_ADDRESS);
                    END;
                END IF;

                SET V_SWAL_TYPE='success',V_SWAL_TITLE='Success',V_SWAL_TEXT='User details added successful';

                SELECT admin_id INTO V_CI_ADMIN_ID FROM ci_admin WHERE date_of_birth=V_DATE_OF_BIRTH AND username=V_PERSSONEL_NO;

                SELECT id,executive  INTO V_ENTITY_ID,V_APPROVAL_USERID  FROM master_entities
                 WHERE supervisor=V_USER_ID OR executive=V_USER_ID;

                INSERT INTO retiree_verification_details(ci_admin_id,approval_by,entity_id,requested_by,requested_time,is_registered,is_verified)
                VALUES(V_CI_ADMIN_ID,V_APPROVAL_USERID,V_ENTITY_ID,V_USER_ID,NOW(),0,0);

            END;
        ELSE
            BEGIN
                SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='User has already been Registered';
            END;
        END IF;
COMMIT;
    SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_BEER_CART_CHECKOUT()
BEGIN
     DECLARE V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_LIQUOR_DETAILS,V_MODE VARCHAR(500) DEFAULT '';
    DECLARE V_PAGE_MODE,V_ORDER_CODE,V_CART_TYPE VARCHAR(100);
    DECLARE V_CART_DATA JSON;
    DECLARE V_LIQUOR_KEY,V_CART_AVAIBLE,V_ORDERED_TO_ENTITY_ID,V_NOT_AVAILABLE,V_CAN_PLACE_ORDER,V_ORDER_FROM_ENITY_ID,V_ORDER_FROM_ENTITY_TYPE,V_ALLOWED_QUOTA,
			V_REMOVE_COUNT,V_LIQUOR_PER_BOTTLE,V_QUANTITY_UPDATED,V_CART_ID,V_USER_ID,V_REMOVED,V_LIQUOR_DESCRIPTION_ID,V_LIQUOR_ENTITY_ID,V_CART_DATA_COUNT,V_COUNTER,
			V_QUANTITY,V_LIQUOR_COUNT,V_QUOTA,V_USED_QUOTA,V_CURRENT_AVAILABLE_QUANTITY,V_NEW_QUANTITY,V_LIQUOR_ID,V_LIQUOR_ENTITY_COUNT INT DEFAULT 0;
    DECLARE V_TOTAL_COST_BOTTLES, V_UNIT_COST_LOT_SIZE FLOAT(10,2);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
                ROLLBACK;
        GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('CART','SP_NEW_CART_CHECK_OUT',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Order could not be placed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;
		INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('CART','SP_CART_CHECK_OUT',P_DATA,@P1,@P2);
        SET V_CART_DATA=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_data');
        SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');
        SET V_PAGE_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'page_mode');
        SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
        SET V_LIQUOR_COUNT=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_count');
        SET V_LIQUOR_PER_BOTTLE=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_per_bottle');
        SET V_CART_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_type');
        SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');
        SET V_CART_DATA_COUNT=JSON_LENGTH(V_CART_DATA);
        SET V_COUNTER=0;
        SET V_REMOVE_COUNT=0;
        SET V_QUANTITY_UPDATED=0;
        SET V_LIQUOR_ENTITY_COUNT=0;
        SET V_NOT_AVAILABLE=0;

        SELECT lrq.quota,ca.entity_id
        INTO V_QUOTA,V_ORDER_FROM_ENITY_ID  FROM
        ci_admin ca
        INNER JOIN liquor_rank_quota_mapping lrq ON lrq.rankid=ca.rankid
        WHERE admin_id=V_USER_ID;

        SELECT IFNULL(SUM(luq.liquor_count),0) INTO V_USED_QUOTA FROM
        liquor_user_used_quota luq
        WHERE userid=V_USER_ID AND MONTH(luq.insert_time)= MONTH(NOW()) AND YEAR(luq.insert_time)=YEAR(NOW()) AND luq.order_status!=3 and luq.is_beer=0;

        SET V_USED_QUOTA=V_USED_QUOTA+V_LIQUOR_PER_BOTTLE;

        SET V_CAN_PLACE_ORDER=0;

		SELECT ordered_to_entity_id,order_code
		INTO V_ORDERED_TO_ENTITY_ID,V_ORDER_CODE
		FROM cart_details WHERE id=V_CART_ID;

		INSERT INTO order_code_user_details(cart_id,userid,order_code,insert_mode,insert_time,call_mode)
		VALUES(V_CART_ID,V_USER_ID,V_ORDER_CODE,'New Cart Checkout',NOW(),V_PAGE_MODE);

        IF(V_CART_TYPE='consumer')THEN
            BEGIN
                IF(V_QUOTA>V_USED_QUOTA || V_QUOTA=V_USED_QUOTA )THEN
                    BEGIN
                        SET V_CAN_PLACE_ORDER=1;

						SET V_ORDER_FROM_ENITY_ID=0;

                        SELECT id INTO V_ORDER_FROM_ENTITY_TYPE FROM master_entity_type WHERE entity_type='consumer';

                        SELECT ORDER_CODE INTO V_ORDER_CODE FROM cart_details WHERE id=V_CART_ID;

                        INSERT INTO liquor_user_used_quota(userid,liquor_count,order_code,order_status,insert_time,created_by,isactive)
                        VALUES(
                            V_USER_ID,V_LIQUOR_PER_BOTTLE,V_ORDER_CODE,1,NOW(),V_USER_ID,1
                        );

                        SET V_SWAL_TEXT=CONCAT('Kindly produce the given code at canteen to get the liquors order code: ',V_ORDER_CODE);
                    END;
               END IF;
            END;
        ELSE
            BEGIN
                SELECT entity_type
                INTO V_ORDER_FROM_ENTITY_TYPE
                FROM master_entities WHERE id=V_ORDER_FROM_ENITY_ID;

				SET V_SWAL_TEXT=CONCAT('Kindly use the given code during recieving of the liquors order code: ',V_ORDER_CODE);

                SET V_CAN_PLACE_ORDER=1;
            END;
        END IF;

        IF((V_CART_TYPE!='consumer') OR (V_CART_TYPE='consumer' AND V_CAN_PLACE_ORDER=1) )THEN
            BEGIN
				SET V_SWAL_TYPE='success',V_SWAL_TITLE='Checkot';
                IF(V_PAGE_MODE='shopping_cart')THEN
                    BEGIN
                        SET V_COUNTER=0;
                        WHILE (V_CART_DATA_COUNT>V_COUNTER)DO
                            BEGIN
                                SET V_TOTAL_COST_BOTTLES=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'total_cost_quantity');

                                SET V_UNIT_COST_LOT_SIZE =FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'unit_cost_quantity');

                                SET V_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'quantity');

                                SET V_REMOVED=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'remove');

								SET V_LIQUOR_ENTITY_ID=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'liquor_id');

                                IF(V_REMOVED=0)THEN
                                    BEGIN
                                        IF NOT EXISTS(SELECT id FROM cart_liquor WHERE cart_id= V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID AND quantity=V_QUANTITY)THEN
                                            BEGIN

                                                SET V_QUANTITY_UPDATED=V_QUANTITY_UPDATED+1;

                                                UPDATE cart_liquor SET is_liquor_removed=1,modified_by=V_USER_ID,modification_time=NOW()
                                                        WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                                                SET V_TOTAL_COST_BOTTLES= V_UNIT_COST_LOT_SIZE * V_QUANTITY;

                                                INSERT INTO cart_liquor(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,created_by,creation_time)
                                                values(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_USER_ID,NOW());
                                            END;
                                        END IF;
                                        IF(V_CART_TYPE='consumer')THEN
                                            BEGIN
											   SELECT available_quantity INTO V_CURRENT_AVAILABLE_QUANTITY
											   FROM liquor_entity_mapping WHERE id=V_LIQUOR_ENTITY_ID;

											   IF NOT EXISTS(SELECT id FROM liquor_entity_mapping where id=V_LIQUOR_ENTITY_ID and ((available_quantity > V_QUANTITY)|| (available_quantity = V_QUANTITY)))THEN
													BEGIN
														IF(V_NOT_AVAILABLE=0)THEN
															BEGIN
																SET V_SWAL_TEXT='';
															END;
														END IF;
														SET V_NOT_AVAILABLE=1;



														SELECT liquor_description_id
														INTO V_LIQUOR_DESCRIPTION_ID
														FROM liquor_entity_mapping
														WHERE id=V_LIQUOR_ENTITY_ID;

														SELECT CONCAT(brand,' ',liquor_description,' ',liquor_type,' ',liquor_ml,' ml') INTO V_LIQUOR_DETAILS FROM liquor_details WHERE liquor_description_id=V_LIQUOR_DESCRIPTION_ID;
														SET V_NOT_AVAILABLE=1;
														SET V_SWAL_TYPE='warning';
														SET V_SWAL_TEXT=CONCAT(V_SWAL_TEXT,' Only ',V_CURRENT_AVAILABLE_QUANTITY,' bottles are avaible for ',V_LIQUOR_DETAILS, ' Kindly reduce the quantity or remove the liquor');
													END;
												ELSE
													BEGIN


														SET V_NEW_QUANTITY=V_CURRENT_AVAILABLE_QUANTITY-V_QUANTITY;



														UPDATE liquor_entity_mapping SET available_quantity= V_NEW_QUANTITY WHERE  id=V_LIQUOR_ENTITY_ID;


														INSERT INTO log_liquor_entity_mapping(
														`liquor_entity_id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
														`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
														`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`previous_available_quantity`,`available_quantity`,
														`previous_actual_available_quantity`,`actual_available_quantity`,`city_id`,`state_id`,`creation_time`,`created_type`,`reorder_level`,`updated_from`,`cart_id`
														)SELECT  `id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
														`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
														`selling_price`,`minimun_order_type`,`single_piece_in_lot`,(SELECT V_CURRENT_AVAILABLE_QUANTITY),`available_quantity`,
														`actual_available_quantity`,`actual_available_quantity`,`city_id`,`state_id`,(SELECT NOW()),(SELECT V_USER_ID),`reorder_level`,'NEW_CART_CHECK OUT',V_CART_ID
														FROM  liquor_entity_mapping  WHERE id=V_LIQUOR_ENTITY_ID;
													END;
												END IF;
                                            END;
                                        END IF;
                                    END;
                                ELSE
                                    BEGIN
                                        SET V_REMOVE_COUNT=V_REMOVE_COUNT+1;

                                        UPDATE cart_liquor SET
                                            is_liquor_removed=1,modified_by=V_USER_ID,modification_time=NOW()
                                        WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                                    END;
                                END IF;
                                SET V_COUNTER=V_COUNTER+1;
                            END;
                        END WHILE;
                    END;
                END IF;

                INSERT INTO log_cart_details(cart_id,order_code,liquor_count,ordered_to_entity_id,ordered_to_entity_type,order_from_id,order_from_entity_id,order_from_entity_type,is_active,is_order_placed,is_order_delivered,is_order_cancel,order_by_userid,order_time,order_mode)
                SELECT id,order_code,liquor_count,ordered_to_entity_id,ordered_to_entity_type,order_from_id,order_from_entity_id,order_from_entity_type,is_active,is_order_placed,is_order_delivered,is_order_cancel,(SELECT V_USER_ID),(SELECT NOW()),order_mode from cart_details where id=V_CART_ID;

                UPDATE cart_details SET  liquor_count=V_LIQUOR_COUNT,order_from_entity_id=V_ORDER_FROM_ENITY_ID,order_from_entity_type=V_ORDER_FROM_ENTITY_TYPE
                ,is_order_placed=1, order_time=now(),is_order_delivered=0
                WHERE id= V_CART_ID;

                INSERT INTO order_details
                (cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,dispatch_quantity,dispatch_cost_lot_size,dispatch_total_cost_bottles,recevied_quantity,
                recevied_cost_lot_size,recevied_total_cost_bottles,order_time,order_by,order_process
                )
                SELECT cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,quantity,unit_cost_lot_size,total_cost_bottles,quantity,
                unit_cost_lot_size,total_cost_bottles, (SELECT NOW()),(SELECT V_USER_ID),(SELECT '1')
                from cart_liquor
                WHERE cart_id=V_CART_ID AND is_liquor_removed=0;

            END;
        ELSE
            BEGIN
                IF(V_CAN_PLACE_ORDER=0)THEN
                    BEGIN
                        SET V_ALLOWED_QUOTA=V_USED_QUOTA-V_QUOTA;
                        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Maximum allowed Quota Excedded',V_SWAL_TEXT=CONCAT('Kindly remove ',V_ALLOWED_QUOTA,' bottles to successfully place the order');
                    END;
                END IF;
           END;
        END IF;

		IF(V_NOT_AVAILABLE=0)THEN
			BEGIN
				COMMIT;
			END;
		ELSE
			BEGIN
				ROLLBACK;
			END;
		END IF;

    SELECT V_NOT_AVAILABLE,V_LIQUOR_ID,V_ORDERED_TO_ENTITY_ID,V_UNIT_COST_LOT_SIZE,V_QUANTITY,V_CART_TYPE,V_COUNTER,V_CAN_PLACE_ORDER,V_LIQUOR_ENTITY_ID,V_SWAL_TYPE,V_USED_QUOTA,V_ORDER_CODE,V_PAGE_MODE,V_SWAL_TITLE,V_SWAL_TEXT,V_ORDER_FROM_ENITY_ID,V_ORDER_FROM_ENTITY_TYPE,V_CART_ID,V_USER_ID,V_QUANTITY_UPDATED,V_REMOVE_COUNT,V_LIQUOR_PER_BOTTLE,V_LIQUOR_KEY,V_CAN_PLACE_ORDER;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_BREWERY(IN P_BREWERY_ID int, IN P_BREWERY_NAME varchar(200),
                                                                 IN P_BREWERY_ADDRESS varchar(500),
                                                                 IN P_CONTACTPERSON varchar(200),
                                                                 IN P_MOBILENUMBER varchar(100),
                                                                 IN P_EMAILADDRESS varchar(200),
                                                                 IN P_STATE varchar(100), IN P_USERID varchar(20),
                                                                 IN P_MODE varchar(20))
BEGIN
    DECLARE V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_SWAL_MESSAGE VARCHAR(500);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
						VALUES('SEARCH PRODUCT','SP_CART_INSERT_UPDATE_ITEM',P_BREWERY_NAME,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,'fail' as MESSAGE,@P1,@P2;
    END;
    START TRANSACTION;
    IF (P_MODE = 'A') THEN
        BEGIN
            IF NOT EXISTS(SELECT * FROM master_brewery WHERE brewery_name=P_BREWERY_NAME) THEN
                BEGIN
                    INSERT INTO master_brewery(brewery_name,address,contact_person_name,mobile_no
                        ,mail_id,state,serving_entity,isactive)
                    VALUES(P_BREWERY_NAME,P_BREWERY_ADDRESS,P_CONTACTPERSON,P_MOBILENUMBER,P_EMAILADDRESS
                    ,P_STATE,P_USERID,1);
                END;
            END IF;
            IF NOT EXISTS(SELECT * FROM master_entities WHERE entity_name=P_BREWERY_NAME) THEN
                BEGIN
                    INSERT INTO master_entities(entity_name,entity_type,address,chairman_mobileno
                        ,chairman_mailid,state,created_by,creation_time)
                    VALUES(P_BREWERY_NAME,4,P_BREWERY_ADDRESS,P_MOBILENUMBER,P_EMAILADDRESS
                    ,P_STATE,P_USERID,NOW());
                END;
            END IF;
            SET V_SWAL_TITLE='Registeration Completed';
            SET V_SWAL_MESSAGE=CONCAT(P_BREWERY_NAME,' has been added successfully');
            SET V_SWAL_TYPE="success";
        END;
    ELSEIF (P_MODE = 'E') THEN
        BEGIN
            IF EXISTS(SELECT * FROM master_brewery WHERE id=P_BREWERY_ID) THEN
                BEGIN
                    UPDATE master_brewery SET
                      brewery_name = P_BREWERY_NAME,
                      contact_person_name = P_CONTACTPERSON,
                      mobile_no = P_MOBILENUMBER,
                      mail_id = P_EMAILADDRESS,
                      address=P_BREWERY_ADDRESS,
                      state = P_STATE
                    WHERE id=P_BREWERY_ID;
                END;
            END IF;
            SET V_SWAL_TITLE='Update Completed';
            SET V_SWAL_MESSAGE=CONCAT(P_BREWERY_NAME,' has been updated successfully');
            SET V_SWAL_TYPE="success";
        END;
    END IF;
    COMMIT;
    SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_CANCEL_EXPIRED_ORDER(IN V_DATA varchar(200))
BEGIN

DECLARE V_BLOCK_DATA,V_ORDER_CODE,V_ENTITY_ID,V_CUSTOMER_USER_ID,V_CART_ID,V_LIQUOR_DATA,V_LIQUOR_COUNT,V_BLOCK_LIQUOR_DATA,V_LIQUOR_ENTITY_ID,V_LIQUOR_QUANTITY,V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT LONGTEXT;
DECLARE V_COUNT,V_COUNT1,V_COUNT_LIQUOR,V_PREVIOUS_QUANTITY,V_ACTUAL_AVAILABLE_QUANTITY,V_NEW_QUANTITY INT DEFAULT 0;

DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('CANCEL ORDER','SP_CANCEL_EXPIRED_ORDER',V_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Order could not be placed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;
		SET V_ORDER_CODE=FN_STRING_SPLIT(V_DATA,'#',1);
		SET V_ENTITY_ID=FN_STRING_SPLIT(V_DATA,'#',2);
		SET V_CUSTOMER_USER_ID=FN_STRING_SPLIT(V_DATA,'#',3);
		SET V_CART_ID=FN_STRING_SPLIT(V_DATA,'#',4);

		IF EXISTS(SELECT id FROM cart_details WHERE id=V_CART_ID AND is_order_cancel=0)THEN
			BEGIN
				SELECT GROUP_CONCAT(liquor_entity_id,'#',quantity),COUNT(cart_id)
				INTO V_LIQUOR_DATA,V_LIQUOR_COUNT
				FROM order_details
				WHERE cart_id=V_CART_ID;

				SET V_COUNT_LIQUOR=1;

				WHILE(V_COUNT_LIQUOR <= V_LIQUOR_COUNT)DO
					BEGIN
						SET V_BLOCK_LIQUOR_DATA=FN_STRING_SPLIT(V_LIQUOR_DATA,',',V_COUNT_LIQUOR);
						SET V_LIQUOR_ENTITY_ID=FN_STRING_SPLIT(V_BLOCK_LIQUOR_DATA,'#',1);
						SET V_LIQUOR_QUANTITY=FN_STRING_SPLIT(V_BLOCK_LIQUOR_DATA,'#',2);

						SELECT available_quantity,actual_available_quantity
						INTO V_PREVIOUS_QUANTITY,V_ACTUAL_AVAILABLE_QUANTITY
						FROM liquor_entity_mapping WHERE
						ID=V_LIQUOR_ENTITY_ID;



						IF(V_ACTUAL_AVAILABLE_QUANTITY=0 AND V_ACTUAL_AVAILABLE_QUANTITY<0)THEN
							BEGIN
								SET V_NEW_QUANTITY=0;
							END;
						ELSE
							BEGIN
								SET V_NEW_QUANTITY=V_LIQUOR_QUANTITY+V_PREVIOUS_QUANTITY;

                                IF(V_NEW_QUANTITY>V_ACTUAL_AVAILABLE_QUANTITY)THEN
									BEGIN
										SET V_NEW_QUANTITY=V_ACTUAL_AVAILABLE_QUANTITY;
									END;
								END IF;
							END;
						END IF;


						UPDATE liquor_entity_mapping
						SET available_quantity=V_NEW_QUANTITY
						WHERE ID=V_LIQUOR_ENTITY_ID;

                         INSERT INTO log_liquor_entity_mapping(
						`liquor_entity_id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
						`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
						`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,
						`actual_available_quantity`,`city_id`,`state_id`,`creation_time`,`created_type`,`reorder_level`,`updated_from`,`cart_id`
						)SELECT  `id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
						`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
						`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,
						`actual_available_quantity`,`city_id`,`state_id`,(SELECT NOW()),'35',`reorder_level`,'EXPIRED_ORDER',V_CART_ID
						FROM  liquor_entity_mapping  WHERE id=V_LIQUOR_ENTITY_ID;


						SET V_COUNT_LIQUOR = V_COUNT_LIQUOR + 1;


					END;
				END WHILE;

				UPDATE cart_details SET is_order_cancel=1,cancel_mode='A',cancel_time=NOW(),is_active=0 WHERE id=V_CART_ID;

				UPDATE order_details SET is_order_cancel=1,order_cancel_time=NOW(),order_process=0 WHERE cart_id=V_CART_ID;

				UPDATE liquor_user_used_quota SET order_status='3',isactive='0' WHERE order_code=V_ORDER_CODE;

				INSERT INTO log_cancelled_order (`order_code`, `cart_id`, `orderedby_user_id`, `entity_id`) VALUES (V_ORDER_CODE, V_CART_ID, V_CUSTOMER_USER_ID, V_ENTITY_ID);
			END;
		END IF;
COMMIT;
    SELECT 'SUCCESS' AS V_SWAL_TYPE,V_LIQUOR_DATA,V_LIQUOR_COUNT,V_ORDER_CODE,V_ENTITY_ID,V_CUSTOMER_USER_ID,V_CART_ID,V_DATA,V_BLOCK_DATA;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_CANTEEN_REPORT(IN P_MODE int)
BEGIN
    IF (P_MODE=1) THEN
        BEGIN
            SELECT
                me.id,
                me.entity_name,
                mt.entity_type AS canteen_club,
                (SELECT state FROM master_state WHERE id = me.state) as state,
                (SELECT CONCAT(user_rank,'. ',firstname) FROM ci_admin WHERE admin_id=me.chairman) AS chairman,
                (SELECT CONCAT(user_rank,'. ',firstname) FROM ci_admin WHERE admin_id=me.supervisor) AS supervisor,
                (SELECT CONCAT(user_rank,'. ',firstname) FROM ci_admin WHERE admin_id=me.executive) AS executive
            FROM master_entities me
            INNER JOIN master_entity_type mt ON me.entity_type=mt.id
            where mt.entity_type NOT IN ('Brewery','consumer');
        END;
    ELSEIF (P_MODE = 2 OR P_MODE = 5) THEN
        BEGIN
            SELECT
                entity_name
            FROM master_entities
            WHERE id IN (SELECT DISTINCT entity_id FROM liquor_stock_sales);
        END;
    ELSEIF (P_MODE = 3 OR P_MODE = 4) THEN
        BEGIN
            SELECT
                me.id,
                me.entity_name,
                mt.entity_type AS canteen_club,
                (SELECT state FROM master_state WHERE id = me.state) as state,
                (SELECT CONCAT(user_rank,'. ',firstname) FROM ci_admin WHERE admin_id=me.chairman) AS chairman,
                (SELECT CONCAT(user_rank,'. ',firstname) FROM ci_admin WHERE admin_id=me.supervisor) AS supervisor,
                (SELECT CONCAT(user_rank,'. ',firstname) FROM ci_admin WHERE admin_id=me.executive) AS executive
            FROM master_entities me
            INNER JOIN master_entity_type mt ON me.entity_type=mt.id
            where mt.entity_type NOT IN ('Brewery','consumer')
            AND (
                    chairman NOT IN ('1898', '1919', '1905')
                    or executive NOT IN ('1898', '1919', '1905')
                    or supervisor NOT IN ('1898', '1919', '1905')
                );
        END;
    END IF;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_CART_CHECK_OUT(IN P_DATA json)
BEGIN
    DECLARE V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_LIQUOR_DETAILS,V_MODE VARCHAR(500) DEFAULT '';
    DECLARE V_PAGE_MODE,V_ORDER_CODE,V_CART_TYPE VARCHAR(100);
    DECLARE V_CART_DATA JSON;
    DECLARE V_LIQUOR_KEY,V_CART_AVAIBLE,V_ORDERED_TO_ENTITY_ID,V_NOT_AVAILABLE,V_CAN_PLACE_ORDER,V_ORDER_FROM_ENITY_ID,V_ORDER_FROM_ENTITY_TYPE,V_ALLOWED_QUOTA,V_REMOVE_COUNT,V_LIQUOR_PER_BOTTLE,V_QUANTITY_UPDATED,V_CART_ID,V_USER_ID,V_REMOVED,V_LIQUOR_DESCRIPTION_ID,V_LIQUOR_ENTITY_ID,V_CART_DATA_COUNT,V_COUNTER ,V_QUANTITY,V_LIQUOR_COUNT,V_QUOTA,V_USED_QUOTA,V_CURRENT_AVAILABLE_QUANTITY,V_NEW_QUANTITY,V_LIQUOR_ID,V_LIQUOR_ENTITY_COUNT INT DEFAULT 0;
    DECLARE V_TOTAL_COST_BOTTLES, V_UNIT_COST_LOT_SIZE FLOAT(10,2);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
        GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('CART','SP_CART_CHECK_OUT',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Order could not be placed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;
   INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('CART','SP_CART_CHECK_OUT',P_DATA,@P1,@P2);
        SET V_CART_DATA=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_data');
        SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');
        SET V_PAGE_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'page_mode');
        SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
        SET V_LIQUOR_COUNT=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_count');
        SET V_LIQUOR_PER_BOTTLE=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_per_bottle');
        SET V_CART_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_type');
        SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');
        SET V_CART_DATA_COUNT=  JSON_LENGTH(V_CART_DATA);
        SET V_COUNTER=0;
        SET V_REMOVE_COUNT=0;
        SET V_QUANTITY_UPDATED=0;
        SET V_LIQUOR_ENTITY_COUNT=0;
		SET V_NOT_AVAILABLE=0;

        SELECT lem.selling_price INTO V_UNIT_COST_LOT_SIZE
		FROM liquor_entity_mapping lem where id=V_LIQUOR_ENTITY_ID;

		SET V_TOTAL_COST_BOTTLES =V_UNIT_COST_LOT_SIZE*V_QUANTITY;



        SELECT (lrq.quota*4),ca.entity_id
        INTO V_QUOTA,V_ORDER_FROM_ENITY_ID  FROM
        ci_admin ca
        INNER JOIN liquor_rank_quota_mapping lrq ON lrq.rankid=ca.rankid
        WHERE admin_id=V_USER_ID;

        SELECT IFNULL(SUM(luq.liquor_count),0) INTO V_USED_QUOTA FROM
        liquor_user_used_quota luq
        WHERE userid=V_USER_ID AND MONTH(luq.insert_time) BETWEEN MONTH(NOW())-1 AND MONTH(NOW()) AND YEAR(luq.insert_time)=YEAR(NOW()) AND luq.order_status!=3 and luq.is_beer=0;

        SET V_USED_QUOTA=V_USED_QUOTA+V_LIQUOR_PER_BOTTLE;

        SET V_CAN_PLACE_ORDER=0;

		SELECT ordered_to_entity_id,order_code
		INTO V_ORDERED_TO_ENTITY_ID,V_ORDER_CODE
		FROM cart_details WHERE id=V_CART_ID;


        IF(V_CART_TYPE='consumer')THEN
            BEGIN
                IF(V_QUOTA>V_USED_QUOTA || V_QUOTA=V_USED_QUOTA )THEN
                    BEGIN
                        SET V_CAN_PLACE_ORDER=1;

						SET V_ORDER_FROM_ENITY_ID=0;

                        SELECT id INTO V_ORDER_FROM_ENTITY_TYPE FROM master_entity_type WHERE entity_type='consumer';

                        SELECT ORDER_CODE INTO V_ORDER_CODE  FROM cart_details WHERE  id=V_CART_ID;

                        INSERT INTO liquor_user_used_quota(userid,liquor_count,order_code,order_status,insert_time,created_by,isactive)
                        VALUES(
                            V_USER_ID,V_LIQUOR_PER_BOTTLE,V_ORDER_CODE,1,NOW(),V_USER_ID,1
                        );

                        SET V_SWAL_TEXT=CONCAT('Kindly produce the given code at canteen to get the liquors order code: ',V_ORDER_CODE);
                    END;
               END IF;
            END;
        ELSE
            BEGIN
                SELECT entity_type
                INTO V_ORDER_FROM_ENTITY_TYPE
                FROM master_entities WHERE id=V_ORDER_FROM_ENITY_ID;

				SET V_SWAL_TEXT=CONCAT('Kindly use the given code during recieving of the liquors order code: ',V_ORDER_CODE);

                SET V_CAN_PLACE_ORDER=1;
            END;
        END IF;

        IF((V_CART_TYPE!='consumer') OR (V_CART_TYPE='consumer' AND V_CAN_PLACE_ORDER=1) )THEN
            BEGIN
				SET V_SWAL_TYPE='success',V_SWAL_TITLE='Checkot';
                IF(V_PAGE_MODE='shopping_cart')THEN
                    BEGIN
                        SET V_COUNTER=0;
                        WHILE (V_CART_DATA_COUNT>V_COUNTER)DO
                            BEGIN
                                SET  V_TOTAL_COST_BOTTLES=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'total_cost_quantity');

                                SET  V_UNIT_COST_LOT_SIZE =FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'unit_cost_quantity');

                                SET V_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'quantity');

                                SET V_REMOVED=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'remove');

								SET V_LIQUOR_ENTITY_ID=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'liquor_id');

                                IF(V_REMOVED=0)THEN
                                    BEGIN
                                        IF NOT EXISTS(SELECT id FROM cart_liquor WHERE cart_id= V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID AND quantity=V_QUANTITY)THEN
                                            BEGIN

                                                SET V_QUANTITY_UPDATED=V_QUANTITY_UPDATED+1;

                                                UPDATE cart_liquor SET is_liquor_removed=1,modified_by=V_USER_ID,modification_time=NOW()
                                                        WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                                                SET	 V_TOTAL_COST_BOTTLES=	V_UNIT_COST_LOT_SIZE * V_QUANTITY;

                                                INSERT INTO cart_liquor(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,created_by,creation_time)
                                                        values(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_USER_ID,NOW());
                                            END;
                                        END IF;
                                        IF(V_CART_TYPE='consumer')THEN
                                            BEGIN
												SELECT available_quantity INTO V_CURRENT_AVAILABLE_QUANTITY
													FROM liquor_entity_mapping WHERE id=V_LIQUOR_ENTITY_ID;

												IF NOT EXISTS(SELECT id FROM liquor_entity_mapping where id=V_LIQUOR_ENTITY_ID and ((available_quantity > V_QUANTITY)|| (available_quantity = V_QUANTITY)))THEN
													BEGIN
														IF(V_NOT_AVAILABLE=0)THEN
															BEGIN
																SET V_SWAL_TEXT='';
                                                            END;
                                                        END IF;
                                                        SET V_NOT_AVAILABLE=1;



                                                        SELECT liquor_description_id
														INTO V_LIQUOR_DESCRIPTION_ID
														FROM liquor_entity_mapping
														WHERE id=V_LIQUOR_ENTITY_ID;

														SELECT CONCAT(brand,' ',liquor_description,' ',liquor_type,' ',liquor_ml,' ml') INTO V_LIQUOR_DETAILS FROM liquor_details WHERE liquor_description_id=V_LIQUOR_DESCRIPTION_ID;
														SET V_NOT_AVAILABLE=1;
														SET V_SWAL_TYPE='warning';
														SET V_SWAL_TEXT=CONCAT(V_SWAL_TEXT,' Only ',V_CURRENT_AVAILABLE_QUANTITY,' bottles are avaible for ',V_LIQUOR_DETAILS, ' Kindly reduce the quantity or remove the liquor');
													END;
												ELSE
													BEGIN


														SET V_NEW_QUANTITY=V_CURRENT_AVAILABLE_QUANTITY-V_QUANTITY;



														UPDATE liquor_entity_mapping SET available_quantity= V_NEW_QUANTITY WHERE  id=V_LIQUOR_ENTITY_ID;

                                                        														 INSERT INTO log_liquor_entity_mapping(
														`liquor_entity_id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
														`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
														`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`previous_available_quantity`,`available_quantity`,
														`previous_actual_available_quantity`,`actual_available_quantity`,`city_id`,`state_id`,`creation_time`,`created_type`,`reorder_level`,`updated_from`,`cart_id`
														)SELECT  `id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
														`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
														`selling_price`,`minimun_order_type`,`single_piece_in_lot`,(SELECT V_CURRENT_AVAILABLE_QUANTITY),`available_quantity`,
														`actual_available_quantity`,`actual_available_quantity`,`city_id`,`state_id`,(SELECT NOW()),(SELECT V_USER_ID),`reorder_level`,'NEW_CART_CHECK OUT',V_CART_ID
														FROM  liquor_entity_mapping  WHERE id=V_LIQUOR_ENTITY_ID;

                                                    END;
												END IF;
                                            END;
                                        END IF;
                                    END;
                                ELSE
                                    BEGIN
                                        SET V_REMOVE_COUNT=V_REMOVE_COUNT+1;

                                        UPDATE cart_liquor SET
                                            is_liquor_removed=1,modified_by=V_USER_ID,modification_time=NOW()
                                        WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                                    END;
                                END IF;
                                SET V_COUNTER=V_COUNTER+1;
                            END;
                        END WHILE;
                    END;
                END IF;

				INSERT INTO log_cart_details(cart_id,order_code,liquor_count,ordered_to_entity_id,ordered_to_entity_type,order_from_id,order_from_entity_id,order_from_entity_type,is_active,is_order_placed,is_order_delivered,is_order_cancel,order_by_userid,order_time)
                SELECT id,order_code,liquor_count,ordered_to_entity_id,ordered_to_entity_type,order_from_id,order_from_entity_id,order_from_entity_type,is_active,is_order_placed,is_order_delivered,is_order_cancel,(SELECT V_USER_ID),(SELECT NOW()) from cart_details where id=V_CART_ID;

                UPDATE cart_details SET  liquor_count=V_LIQUOR_COUNT,order_from_entity_id=V_ORDER_FROM_ENITY_ID,order_from_entity_type=V_ORDER_FROM_ENTITY_TYPE
                ,is_order_placed=1, order_by_userid=V_USER_ID,order_time=now(),is_order_delivered=0
                WHERE id= V_CART_ID;

                INSERT INTO order_details
                (cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,dispatch_quantity,dispatch_cost_lot_size,dispatch_total_cost_bottles,recevied_quantity,
                recevied_cost_lot_size,recevied_total_cost_bottles,order_time,order_by,order_process
                )
                SELECT cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,quantity,unit_cost_lot_size,total_cost_bottles,quantity,
                unit_cost_lot_size,total_cost_bottles, (SELECT NOW()),(SELECT V_USER_ID),(SELECT '1')
                from cart_liquor
                WHERE cart_id=V_CART_ID AND is_liquor_removed=0;


            END;
        ELSE
            BEGIN
                IF(V_CAN_PLACE_ORDER=0)THEN
                    BEGIN
                        SET V_ALLOWED_QUOTA=V_USED_QUOTA-V_QUOTA;
                        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Maximum allowed Quota Excedded',V_SWAL_TEXT=CONCAT('Kindly remove ',V_ALLOWED_QUOTA,' bottles to successfully place the order');
                    END;
                END IF;
           END;
        END IF;
    IF(V_NOT_AVAILABLE=0)THEN
		BEGIN
			COMMIT;
		END;
    ELSE
		BEGIN
			ROLLBACK;
        END;
	END IF;

    SELECT V_NOT_AVAILABLE,V_LIQUOR_ID,V_ORDERED_TO_ENTITY_ID,V_UNIT_COST_LOT_SIZE,V_QUANTITY,V_CART_TYPE,V_COUNTER,V_CAN_PLACE_ORDER,V_LIQUOR_ENTITY_ID,V_SWAL_TYPE,V_USED_QUOTA,V_ORDER_CODE,V_PAGE_MODE,V_SWAL_TITLE,V_SWAL_TEXT,V_ORDER_FROM_ENITY_ID,V_ORDER_FROM_ENTITY_TYPE,V_CART_ID,V_USER_ID,V_QUANTITY_UPDATED,V_REMOVE_COUNT,V_LIQUOR_PER_BOTTLE,V_LIQUOR_KEY,V_CAN_PLACE_ORDER;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_CART_CHECK_OUT_BACKUP(IN P_DATA json)
BEGIN
	DECLARE V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT VARCHAR(500) DEFAULT '';
    DECLARE V_PAGE_MODE VARCHAR(100);
    DECLARE V_CART_DATA JSON;
    DECLARE V_REMOVE_COUNT,V_QUANTITY_UPDATED,V_CART_ID,V_USER_ID,V_REMOVED,V_LIQUOR_ENTITY_ID,V_CART_DATA_COUNT,V_COUNTER, V_TOTAL_COST_BOTTLES, V_UNIT_COST_LOT_SIZE ,V_QUANTITY,V_LIQUOR_COUNT INT;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
						VALUES('CART','SP_CART_ORDER_INSERT_UPDATE',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;
		SET V_CART_DATA=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_data');
		SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');
		SET V_PAGE_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'page_mode');
        SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
		SET V_LIQUOR_COUNT=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_count');
		SET	V_CART_DATA_COUNT=	JSON_LENGTH(V_CART_DATA);
        SET V_COUNTER=0;
        SET V_REMOVE_COUNT=0;
		SET V_QUANTITY_UPDATED=0;

		IF(V_PAGE_MODE='shopping_cart')THEN
			BEGIN
				WHILE (V_CART_DATA_COUNT>V_COUNTER)DO
					BEGIN
						SET  V_TOTAL_COST_BOTTLES=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'total_cost_quantity');

                        SET  V_UNIT_COST_LOT_SIZE =FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'unit_cost_quantity');

                        SET V_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'quantity');

                        SET V_REMOVED=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'remove');

                        SET V_LIQUOR_ENTITY_ID=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'liquor_id');

                        IF(V_REMOVED=0)THEN
							BEGIN
								IF NOT EXISTS(SELECT id FROM cart_liquor WHERE cart_id= V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID AND quantity=V_QUANTITY)THEN
									BEGIN
                                        SET V_QUANTITY_UPDATED=V_QUANTITY_UPDATED+1;

										UPDATE cart_liquor SET is_liquor_removed=1,modified_by=V_USER_ID,modification_time=NOW()
												WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;


										INSERT INTO cart_liquor(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,created_by,creation_time)
												values(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_USER_ID,NOW());
									END;
								END IF;
							END;
						ELSE
							BEGIN
								SET V_REMOVE_COUNT=V_REMOVE_COUNT+1;

								UPDATE cart_liquor SET
									is_liquor_removed=1,modified_by=V_USER_ID,modification_time=NOW()
								WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

								UPDATE cart_details SET liquor_count=V_LIQUOR_COUNT WHERE id=V_CART_ID;
                            END;
                        END IF;
						SET V_COUNTER=V_COUNTER+1;
                    END;
                END WHILE;
			END;
        END IF;

        SET V_SWAL_TYPE='success',V_SWAL_TITLE='Checkot',V_SWAL_TEXT='checkout';

    COMMIT;
    SELECT V_SWAL_TYPE,V_PAGE_MODE,V_SWAL_TITLE,V_SWAL_TEXT,V_CART_ID,V_USER_ID,V_QUANTITY_UPDATED,V_REMOVE_COUNT;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_CART_CHECK_OUT_BACKU_LATEST(IN P_DATA json)
BEGIN
	DECLARE V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_LIQUOR_DETAILS VARCHAR(500) DEFAULT '';
    DECLARE V_PAGE_MODE,V_ORDER_CODE,V_CART_TYPE VARCHAR(100);
    DECLARE V_CART_DATA JSON;
    DECLARE V_LIQUOR_KEY,V_CART_AVAIBLE,V_NOT_AVAILABLE,V_CAN_PLACE_ORDER,V_ORDER_FROM_ENITY_ID,V_ORDER_FROM_ENTITY_TYPE,V_ALLOWED_QUOTA,V_REMOVE_COUNT,V_LIQUOR_PER_BOTTLE,V_QUANTITY_UPDATED,V_CART_ID,V_USER_ID,V_REMOVED,V_LIQUOR_DESCRIPTION_ID,V_LIQUOR_ENTITY_ID,V_CART_DATA_COUNT,V_COUNTER, V_TOTAL_COST_BOTTLES, V_UNIT_COST_LOT_SIZE ,V_QUANTITY,V_LIQUOR_COUNT,V_QUOTA,V_USED_QUOTA,V_CURRENT_AVAILABLE_QUANTITY,V_NEW_QUANTITY INT DEFAULT 0;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
						VALUES('CART','SP_CART_ORDER_INSERT_UPDATE',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Order could not be placed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;
		SET V_CART_DATA=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_data');
		SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');
		SET V_PAGE_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'page_mode');
        SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
		SET V_LIQUOR_COUNT=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_count');
        SET V_LIQUOR_PER_BOTTLE=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_per_bottle');
		SET V_CART_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_type');
		SET	V_CART_DATA_COUNT=	JSON_LENGTH(V_CART_DATA);
        SET V_COUNTER=0;
        SET V_REMOVE_COUNT=0;
		SET V_QUANTITY_UPDATED=0;

        SELECT lrq.quota,ca.entity_id INTO V_QUOTA,V_ORDER_FROM_ENITY_ID FROM
		ci_admin ca
		INNER JOIN bsf_hrms_data bh ON bh.irla=ca.username
		INNER JOIN master_rank mr ON mr.rank=bh.rank
		INNER JOIN liquor_rank_quota_mapping lrq ON lrq.rankid=mr.id
		WHERE admin_id=V_USER_ID;

		SELECT IFNULL(SUM(luq.liquor_count),0) INTO V_USED_QUOTA FROM
		liquor_user_used_quota luq
		WHERE userid=V_USER_ID AND MONTH(luq.insert_time)= MONTH(NOW()) AND luq.order_status!=3;

		SET V_USED_QUOTA=V_USED_QUOTA+V_LIQUOR_PER_BOTTLE;

        SET V_CAN_PLACE_ORDER=0;

        IF(V_CART_TYPE='consumer')THEN
			BEGIN
				IF(V_QUOTA>V_USED_QUOTA || V_QUOTA=V_USED_QUOTA )THEN
					BEGIN
						SET V_CAN_PLACE_ORDER=1;
                        SET V_ORDER_FROM_ENITY_ID=0;
                        SELECT id INTO V_ORDER_FROM_ENTITY_TYPE FROM master_entity_type WHERE entity_type='consumer';

                        WHILE (V_CART_DATA_COUNT>V_COUNTER)DO
							BEGIN
								SET V_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'quantity');

								SET V_REMOVED=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'remove');

								SET V_LIQUOR_ENTITY_ID=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'liquor_id');

								IF(V_REMOVED=0)THEN
									BEGIN
										SELECT available_quantity INTO V_CURRENT_AVAILABLE_QUANTITY
										FROM liquor_entity_mapping WHERE id=V_LIQUOR_ENTITY_ID;

										IF NOT EXISTS(SELECT id FROM liquor_entity_mapping where id=V_LIQUOR_ENTITY_ID and ((available_quantity > V_QUANTITY)|| (available_quantity = V_QUANTITY)))THEN
											BEGIN
												SELECT CONCAT(brand,' ',liquor_description,' ',liquor_type,' ',liquor_ml,' ml') INTO V_LIQUOR_DETAILS FROM liquor_details WHERE liquor_description_id=V_LIQUOR_DESCRIPTION_ID;
												SET V_NOT_AVAILABLE=1;
												SET V_SWAL_TYPE='warning';
												SET V_SWAL_TEXT=CONCAT(V_SWAL_TEXT,' Only ',V_CURRENT_AVAILABLE_QUANTITY,' bottles are avaible for ',V_LIQUOR_DETAILS, ' Kindly reduce the quantity or remove the liquor');
											END;
										END IF;
                                    END;
                                END IF;
								SET V_COUNTER=V_COUNTER+1;
							END;
                       END WHILE;
					END;
               END IF;
            END;
		ELSE
			BEGIN
				SELECT entity_type INTO V_ORDER_FROM_ENTITY_TYPE FROM master_entities WHERE id=V_ORDER_FROM_ENITY_ID;
				SET V_CAN_PLACE_ORDER=1;
			END;
        END IF;

		IF((V_CART_TYPE!='consumer') OR (V_CART_TYPE='consumer' AND V_NOT_AVAILABLE=0 AND V_CAN_PLACE_ORDER=1) )THEN
			BEGIN
				IF(V_PAGE_MODE='shopping_cart')THEN
					BEGIN
						SET V_COUNTER=0;
						WHILE (V_CART_DATA_COUNT>V_COUNTER)DO
							BEGIN
								SET  V_TOTAL_COST_BOTTLES=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'total_cost_quantity');

								SET  V_UNIT_COST_LOT_SIZE =FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'unit_cost_quantity');

								SET V_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'quantity');

								SET V_REMOVED=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'remove');

								SET V_LIQUOR_ENTITY_ID=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'liquor_id');

								IF(V_REMOVED=0)THEN
									BEGIN
										IF NOT EXISTS(SELECT id FROM cart_liquor WHERE cart_id= V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID AND quantity=V_QUANTITY)THEN
											BEGIN

                                                SET V_QUANTITY_UPDATED=V_QUANTITY_UPDATED+1;

												UPDATE cart_liquor SET is_liquor_removed=1,modified_by=V_USER_ID,modification_time=NOW()
														WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;


												INSERT INTO cart_liquor(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,created_by,creation_time)
														values(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_USER_ID,NOW());
											END;
										END IF;
                                        IF(V_CART_TYPE='consumer')THEN
											BEGIN
												SELECT available_quantity INTO V_CURRENT_AVAILABLE_QUANTITY
												FROM liquor_entity_mapping WHERE id=V_LIQUOR_ENTITY_ID;

												SET V_NEW_QUANTITY=V_CURRENT_AVAILABLE_QUANTITY-V_QUANTITY;

												UPDATE liquor_entity_mapping SET available_quantity= V_NEW_QUANTITY WHERE  id=V_LIQUOR_ENTITY_ID;
											END;
										END IF;
									END;
								ELSE
									BEGIN
										SET V_REMOVE_COUNT=V_REMOVE_COUNT+1;

										UPDATE cart_liquor SET
											is_liquor_removed=1,modified_by=V_USER_ID,modification_time=NOW()
										WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;


									END;
								END IF;
								SET V_COUNTER=V_COUNTER+1;
							END;
						END WHILE;
					END;
				END IF;

                UPDATE cart_details SET  liquor_count=V_LIQUOR_COUNT,order_from_entity_id=V_ORDER_FROM_ENITY_ID,order_from_entity_type=V_ORDER_FROM_ENTITY_TYPE
                ,is_order_placed=1, order_by_userid=V_USER_ID,order_time=now(),is_order_delivered=0
                WHERE id= V_CART_ID;

				INSERT INTO order_details
				(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,recevied_quantity,
				recevied_cost_lot_size,recevied_total_cost_bottles,order_time,order_by,order_process
				)
				SELECT cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,quantity,
				unit_cost_lot_size,total_cost_bottles, (SELECT NOW()),(SELECT V_USER_ID),(SELECT '1')
				from cart_liquor
				WHERE cart_id=V_CART_ID AND is_liquor_removed=0;

				IF(V_CART_TYPE='consumer')THEN
					BEGIN

						SELECT IFNULL(SUM(luq.liquor_count),0) INTO V_USED_QUOTA FROM
						liquor_user_used_quota luq
						WHERE userid=V_USER_ID AND MONTH(luq.insert_time)= MONTH(NOW()) AND luq.order_status!=3;

						SELECT ORDER_CODE INTO V_ORDER_CODE  FROM cart_details WHERE  id=V_CART_ID;

						INSERT INTO liquor_user_used_quota(userid,liquor_count,order_code,order_status,insert_time,created_by,isactive)
                        VALUES(
							V_USER_ID,V_LIQUOR_PER_BOTTLE,V_ORDER_CODE,1,NOW(),V_USER_ID,1
						);

                        SET V_SWAL_TEXT=CONCAT('Kindly produce the given code at canteen to get the liquors order code: ',V_ORDER_CODE);
					END;
				ELSE
					BEGIN
						SET V_SWAL_TEXT=CONCAT('Kindly use the given code during recieving of the liquors order code: ',V_ORDER_CODE);
                    END;
				END IF;
				SET V_SWAL_TYPE='success',V_SWAL_TITLE='Checkot';
			END;
		ELSE
			BEGIN
				IF(V_CAN_PLACE_ORDER=0)THEN
					BEGIN
						SET V_ALLOWED_QUOTA=V_USED_QUOTA-V_QUOTA;
						SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Maximum allowed Quota Excedded',V_SWAL_TEXT=CONCAT('Kindly remove ',V_ALLOWED_QUOTA,' bottles to successfully place the order');
                    END;
                END IF;
           END;
		END IF;
    COMMIT;
    SELECT V_NOT_AVAILABLE,V_QUANTITY,V_CART_TYPE,V_COUNTER,V_CAN_PLACE_ORDER,V_LIQUOR_ENTITY_ID,V_SWAL_TYPE,V_USED_QUOTA,V_ORDER_CODE,V_PAGE_MODE,V_SWAL_TITLE,V_SWAL_TEXT,V_ORDER_FROM_ENITY_ID,V_ORDER_FROM_ENTITY_TYPE,V_CART_ID,V_USER_ID,V_QUANTITY_UPDATED,V_REMOVE_COUNT,V_LIQUOR_PER_BOTTLE,V_LIQUOR_KEY;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_CART_INSERT_UPDATE_ITEM(IN P_DATA json)
BEGIN
	DECLARE V_MODE,V_CART_TYPE,V_GENERATE_ORDER_CODE,V_ORDER_CODE,V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_CART_ACTION,V_MESAAGE,V_ANDROID_WEB VARCHAR(100) default '';
    DECLARE V_ORDERED_TO_ENTITY_ID,V_CART_ID,V_ORDERED_TO_ENTITY_TYPE,V_ORDER_FROM_ID,V_ORDER_FROM_ENTITY_TYPE,V_LIQUOR_COUNT INT;
    DECLARE V_ORDER_BY_USERID,V_ORDERED_BY_ID,V_NEW_QUANTITY,V_PREVIOUS_QUANTITY, V_LIQUOR_ENTITY_ID,V_QUANTITY INT;
	DECLARE V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES FLOAT(10,2);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
						VALUES('SEARCH PRODUCT','SP_CART_INSERT_UPDATE_ITEM',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,'fail' as MESSAGE,@P1,@P2;
    END;

    START TRANSACTION;
			SET V_CART_ACTION=FN_EXTRACT_JSON_DATA(P_DATA,0,'action');
			SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');
            SET V_LIQUOR_ENTITY_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_entity_id');
			SET V_QUANTITY=FN_EXTRACT_JSON_DATA(P_DATA,0,'quantity');


			SET V_ORDER_FROM_ENTITY_TYPE =FN_EXTRACT_JSON_DATA(P_DATA,0,'order_from_entity_type');
			SET V_ORDER_BY_USERID =FN_EXTRACT_JSON_DATA(P_DATA,0,'order_by_userid');
			SET V_CART_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_type');
			SET V_ANDROID_WEB=	FN_EXTRACT_JSON_DATA(P_DATA,0,'android_web');

		IF(V_CART_ACTION='ADD')THEN
			BEGIN
				SET V_MESAAGE='INSIDE ADD';
				 SELECT lem.entity_id,me.entity_type,lem.selling_price
                 INTO V_ORDERED_TO_ENTITY_ID,V_ORDERED_TO_ENTITY_TYPE,V_UNIT_COST_LOT_SIZE

                 FROM liquor_entity_mapping lem
                 INNER JOIN master_entities me on lem.entity_id=me.id WHERE lem.id= V_LIQUOR_ENTITY_ID;

				 SET V_LIQUOR_COUNT =FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_count');

				 SET V_ORDER_FROM_ID =FN_EXTRACT_JSON_DATA(P_DATA,0,'order_from_id');


                  SET V_TOTAL_COST_BOTTLES =V_UNIT_COST_LOT_SIZE*V_QUANTITY;

				IF(V_MODE='New')THEN
				BEGIN
                	SET V_MESAAGE='INSIDE NEW';
					WHILE(V_ORDER_CODE="") DO
						SET V_GENERATE_ORDER_CODE=lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0);
						IF NOT EXISTS (SELECT order_code FROM cart_details WHERE order_code=V_GENERATE_ORDER_CODE) THEN
									BEGIN
										SET V_ORDER_CODE=V_GENERATE_ORDER_CODE;
									END;
							END IF;
						END WHILE;

						INSERT INTO cart_details
								(order_code,ordered_to_entity_id,liquor_count,ordered_to_entity_type,order_from_id,order_from_entity_type,is_active,order_by_userid,order_time,cart_type,order_mode)
							VALUES(
									V_ORDER_CODE,V_ORDERED_TO_ENTITY_ID,V_LIQUOR_COUNT,V_ORDERED_TO_ENTITY_TYPE,V_ORDER_FROM_ID,V_ORDER_FROM_ENTITY_TYPE,1,V_ORDER_BY_USERID,NOW(),V_CART_TYPE,V_ANDROID_WEB
								);

					   SELECT id INTO V_CART_ID FROM cart_details WHERE order_code=V_ORDER_CODE and order_by_userid=V_ORDER_BY_USERID  order by id desc limit 1;

					   INSERT INTO cart_liquor(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,created_by,creation_time)
						values(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_ORDER_BY_USERID,NOW());

					END;
				ELSEIF(V_MODE='delivery_cart_page')THEN
					BEGIN
						SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');

						SELECT order_by_userid
                        INTO V_ORDERED_BY_ID
                        FROM cart_details WHERE id=V_CART_ID;

						IF NOT EXISTS(SELECT id FROM order_details WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID AND is_liquor_removed=0)THEN
							BEGIN
								INSERT INTO order_details(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,dispatch_quantity,dispatch_cost_lot_size,dispatch_total_cost_bottles,recevied_quantity,recevied_cost_lot_size,recevied_total_cost_bottles,is_liquor_added,liquor_added_by,liquor_add_time,order_by)
								VALUES(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,1,V_ORDER_BY_USERID,NOW(),V_ORDERED_BY_ID);
							END;
						ELSE
							BEGIN
								SELECT minimum_order_quantity INTO V_QUANTITY
                                FROM liquor_entity_mapping where id = V_LIQUOR_ENTITY_ID;
								SELECT recevied_quantity
                                INTO  V_PREVIOUS_QUANTITY
                                FROM order_details
                                WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

								SET V_NEW_QUANTITY= V_PREVIOUS_QUANTITY + V_QUANTITY;

								SET V_TOTAL_COST_BOTTLES=V_NEW_QUANTITY * V_UNIT_COST_LOT_SIZE;



                                UPDATE order_details
                                SET dispatch_quantity=V_NEW_QUANTITY,dispatch_cost_lot_size=V_UNIT_COST_LOT_SIZE,dispatch_total_cost_bottles=V_TOTAL_COST_BOTTLES,
                                recevied_quantity=V_NEW_QUANTITY,recevied_cost_lot_size=V_UNIT_COST_LOT_SIZE,
                                recevied_total_cost_bottles=V_TOTAL_COST_BOTTLES,is_liquor_quatity_change=1,is_liquor_removed=0
                                WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                            END;
                        END IF;
                    END;
                ELSE
					BEGIN

						SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');

					IF NOT EXISTS(SELECT * FROM  cart_liquor WHERE cart_id=V_CART_ID AND liquor_entity_id =V_liquor_entity_id AND is_liquor_removed=0)then
						BEGIN
							INSERT INTO cart_liquor(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,created_by,creation_time)
							values(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_ORDER_BY_USERID,NOW());

							UPDATE cart_details SET liquor_count=V_LIQUOR_COUNT WHERE id=V_CART_ID;
				  		END;
				  	ELSE
				 		BEGIN

    				 		SELECT quantity INTO V_PREVIOUS_QUANTITY
    				 		FROM cart_liquor WHERE liquor_entity_id=V_LIQUOR_ENTITY_ID AND cart_id=V_CART_ID;

    				 		SET V_NEW_QUANTITY=V_PREVIOUS_QUANTITY+V_QUANTITY;
    			            SET V_TOTAL_COST_BOTTLES=V_NEW_QUANTITY*V_UNIT_COST_LOT_SIZE;

    			            UPDATE cart_liquor
    			            SET quantity=V_NEW_QUANTITY,total_cost_bottles=V_TOTAL_COST_BOTTLES
    			            WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;
    					END;
					END IF;
				END;
			END IF;
			END;
        ELSEIF(V_CART_ACTION='UPDATE')THEN
			BEGIN
				SET V_MESAAGE='INSIDE UPDATE';
				SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');

                UPDATE cart_liquor SET is_liquor_removed=0,modified_by=V_ORDER_BY_USERID,modification_time=NOW()
                WHERE ID=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                SELECT lem.selling_price
                INTO V_UNIT_COST_LOT_SIZE
				FROM liquor_entity_mapping lem where lem.id=V_LIQUOR_ENTITY_ID;
				SET V_TOTAL_COST_BOTTLES =V_UNIT_COST_LOT_SIZE*V_QUANTITY;

				INSERT INTO cart_liquor(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,created_by,creation_time)
				values(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_ORDER_BY_USERID,NOW());

			END;
         ELSE
			BEGIN
                SET V_MESAAGE='INSIDE REMOVE';
				SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');

				UPDATE cart_liquor SET
					is_liquor_removed=1,modified_by=V_ORDER_BY_USERID,modification_time=NOW()
                WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                UPDATE cart_details SET liquor_count=V_LIQUOR_COUNT WHERE id=V_CART_ID;

            END;
         END IF;
    COMMIT;
    SELECT V_CART_ID,V_MESAAGE,'success' as MESSAGE, V_CART_ACTION,V_ORDERED_TO_ENTITY_ID,V_ORDERED_TO_ENTITY_TYPE,V_ORDER_FROM_ID,V_ORDER_FROM_ENTITY_TYPE,V_ORDER_BY_USERID,V_MODE,V_CART_TYPE,V_ORDER_CODE,V_LIQUOR_COUNT,V_TOTAL_COST_BOTTLES;
    END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_CART_INSERT_UPDATE_ITEM_BACKUP(IN P_DATA json)
BEGIN
	DECLARE V_MODE,V_CART_TYPE,V_GENERATE_ORDER_CODE,V_ORDER_CODE,V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_CART_ACTION,V_MESAAGE VARCHAR(100) default '';
    DECLARE V_ORDERED_TO_ENTITY_ID,V_CART_ID,V_ORDERED_TO_ENTITY_TYPE,V_ORDER_FROM_ID,V_ORDER_FROM_ENTITY_TYPE,V_LIQUOR_COUNT INT;
    DECLARE V_ORDER_BY_USERID,V_ORDERED_BY_ID,V_NEW_QUANTITY,V_PREVIOUS_QUANTITY, V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES INT;
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
						VALUES('SEARCH PRODUCT','SP_CART_INSERT_UPDATE_ITEM',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,'fail' as MESSAGE,@P1,@P2;
    END;

    START TRANSACTION;
			SET V_CART_ACTION=FN_EXTRACT_JSON_DATA(P_DATA,0,'action');
			SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');
            SET V_LIQUOR_ENTITY_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_entity_id');
			SET V_QUANTITY=FN_EXTRACT_JSON_DATA(P_DATA,0,'quantity');
			SET V_UNIT_COST_LOT_SIZE=FN_EXTRACT_JSON_DATA(P_DATA,0,'unit_cost_lot_size');
			SET V_TOTAL_COST_BOTTLES=FN_EXTRACT_JSON_DATA(P_DATA,0,'total_cost_bottles');
			SET V_ORDER_FROM_ENTITY_TYPE =FN_EXTRACT_JSON_DATA(P_DATA,0,'order_from_entity_type');
			SET V_ORDER_BY_USERID =FN_EXTRACT_JSON_DATA(P_DATA,0,'order_by_userid');
			SET V_CART_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_type');


		IF(V_CART_ACTION='ADD')THEN
			BEGIN
				SET V_MESAAGE='INSIDE ADD';
				SELECT lem.entity_id,me.entity_type INTO V_ORDERED_TO_ENTITY_ID,V_ORDERED_TO_ENTITY_TYPE
                FROM liquor_entity_mapping lem
                INNER JOIN master_entities me on lem.entity_id=me.id WHERE lem.id= V_LIQUOR_ENTITY_ID;

				SET V_LIQUOR_COUNT =FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_count');

				SET V_ORDER_FROM_ID =FN_EXTRACT_JSON_DATA(P_DATA,0,'order_from_id');

				IF(V_MODE='New')THEN
				BEGIN
                	SET V_MESAAGE='INSIDE NEW';
					WHILE(V_ORDER_CODE="") DO
						SET V_GENERATE_ORDER_CODE=lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0);
						IF NOT EXISTS (SELECT order_code FROM cart_details WHERE order_code=V_GENERATE_ORDER_CODE AND is_active=1) THEN
									BEGIN
										SET V_ORDER_CODE=CONCAT('I-',V_GENERATE_ORDER_CODE);
										SET V_ORDER_CODE=V_GENERATE_ORDER_CODE;
									END;
							END IF;
						END WHILE;

						INSERT INTO cart_details
								(order_code,ordered_to_entity_id,liquor_count,ordered_to_entity_type,order_from_id,order_from_entity_type,is_active,order_by_userid,order_time,cart_type)
							VALUES(
									V_ORDER_CODE,V_ORDERED_TO_ENTITY_ID,V_LIQUOR_COUNT,V_ORDERED_TO_ENTITY_TYPE,V_ORDER_FROM_ID,V_ORDER_FROM_ENTITY_TYPE,1,V_ORDER_BY_USERID,NOW(),V_CART_TYPE
								);

					   SELECT id INTO V_CART_ID FROM cart_details WHERE order_code=V_ORDER_CODE;

					   INSERT INTO cart_liquor(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,created_by,creation_time)
						values(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_ORDER_BY_USERID,NOW());
				  END;
				ELSEIF(V_MODE='delivery_cart_page')THEN
					BEGIN
						SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');

						SELECT order_by_userid
                        INTO V_ORDERED_BY_ID
                        FROM cart_details WHERE id=V_CART_ID;

						IF NOT EXISTS(SELECT id FROM order_details WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID)THEN
							BEGIN
								INSERT INTO order_details(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,dispatch_quantity,dispatch_cost_lot_size,dispatch_total_cost_bottles,recevied_quantity,recevied_cost_lot_size,recevied_total_cost_bottles,is_liquor_added,liquor_added_by,liquor_add_time,order_by)
								VALUES(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,1,V_ORDER_BY_USERID,NOW(),V_ORDERED_BY_ID);
							END;
						ELSE
							BEGIN
								SELECT recevied_quantity
                                INTO  V_PREVIOUS_QUANTITY
                                FROM order_details
                                WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

								SET V_NEW_QUANTITY= V_PREVIOUS_QUANTITY + V_QUANTITY;

								SET V_TOTAL_COST_BOTTLES=V_NEW_QUANTITY * V_UNIT_COST_LOT_SIZE;

                                UPDATE order_details
                                SET dispatch_quantity=V_NEW_QUANTITY,dispatch_cost_lot_size=V_UNIT_COST_LOT_SIZE,dispatch_total_cost_bottles=V_TOTAL_COST_BOTTLES,
                                recevied_quantity=V_NEW_QUANTITY,recevied_cost_lot_size=V_UNIT_COST_LOT_SIZE,
                                recevied_total_cost_bottles=V_TOTAL_COST_BOTTLES,is_liquor_quatity_change=1,is_liquor_removed=0
                                WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                            END;
                        END IF;
                    END;
                ELSE
					BEGIN

						SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');

					IF NOT EXISTS(SELECT * FROM  cart_liquor WHERE cart_id=V_CART_ID AND liquor_entity_id =V_liquor_entity_id)then
						BEGIN
							INSERT INTO cart_liquor(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,created_by,creation_time)
							values(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_ORDER_BY_USERID,NOW());

							UPDATE cart_details SET liquor_count=V_LIQUOR_COUNT WHERE id=V_CART_ID;
				  		END;
				  	ELSE
				 		BEGIN

    				 		SELECT quantity INTO V_PREVIOUS_QUANTITY
    				 		FROM cart_liquor WHERE liquor_entity_id=V_LIQUOR_ENTITY_ID AND cart_id=V_CART_ID;

    				 		SET V_NEW_QUANTITY=V_PREVIOUS_QUANTITY+V_QUANTITY;
    			            SET V_TOTAL_COST_BOTTLES=V_NEW_QUANTITY*V_UNIT_COST_LOT_SIZE;

    			            UPDATE cart_liquor
    			            SET quantity=V_NEW_QUANTITY,total_cost_bottles=V_TOTAL_COST_BOTTLES
    			            WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;
    					END;
					END IF;
				END;
			END IF;
			END;
        ELSEIF(V_CART_ACTION='UPDATE')THEN
			BEGIN
				SET V_MESAAGE='INSIDE UPDATE';
				SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');

                UPDATE cart_liquor SET is_liquor_removed=0,modified_by=V_ORDER_BY_USERID,modification_time=NOW()
                WHERE ID=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

				INSERT INTO cart_liquor(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,created_by,creation_time)
				values(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_ORDER_BY_USERID,NOW());

			END;
         ELSE
			BEGIN
                SET V_MESAAGE='INSIDE REMOVE';
				SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');

				UPDATE cart_liquor SET
					is_liquor_removed=1,modified_by=V_ORDER_BY_USERID,modification_time=NOW()
                WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                UPDATE cart_details SET liquor_count=V_LIQUOR_COUNT WHERE id=V_CART_ID;

            END;
         END IF;
    COMMIT;
    SELECT V_CART_ID,V_MESAAGE,'success' as MESSAGE, V_CART_ACTION,V_ORDERED_TO_ENTITY_ID,V_ORDERED_TO_ENTITY_TYPE,V_ORDER_FROM_ID,V_ORDER_FROM_ENTITY_TYPE,V_ORDER_BY_USERID,V_MODE,V_CART_TYPE,V_ORDER_CODE,V_LIQUOR_COUNT;

END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_CART_ORDER_INSERT_UPDATE(IN P_DATA json)
BEGIN
	DECLARE V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT VARCHAR(500) DEFAULT '';
    DECLARE V_PAGE_MODE VARCHAR(100);
    DECLARE V_CART_DATA JSON;
    DECLARE V_CART_ID,V_USER_ID,V_LIQUOR_DESCRIPTION_ID,V_CART_DATA_COUNT,V_COUNTER,V_TOTAL_COST_QUANTITY,V_UNIT_COST_QUANTITY,V_QUANTITY INT;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
						VALUES('CART','SP_CART_ORDER_INSERT_UPDATE',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;
		SET V_CART_DATA=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_data');
		SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');
		SET V_PAGE_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'page_mode');
        SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
		SET	V_CART_DATA_COUNT=	JSON_LENGTH(V_CART_DATA);
        SET V_COUNTER=0;

        IF(V_PAGE_MODE='place_order')THEN
        BEGIN

        END;
        END IF;

		IF(V_PAGE_MODE='shopping_cart')THEN
			BEGIN
				WHILE (V_CART_DATA_COUNT>V_COUNTER)DO
					BEGIN
						SET V_TOTAL_COST_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'total_cost_quantity');

                        SET V_UNIT_COST_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'unit_cost_quantity');

                        SET V_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'quantity');

                        SET V_LIQUOR_DESCRIPTION_ID=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'liquor_id');

                        INSERT INTO cart_liquor(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,created_by,creation_time)
                        VALUES(V_CART_ID,V_LIQUOR_DESCRIPTION_ID,V_QUANTITY,V_UNIT_COST_QUANTITY,V_TOTAL_COST_QUANTITY,V_USER_ID,NOW());

						SET V_COUNTER=V_COUNTER+1;
                    END;
                END WHILE;
                		SET V_TOTAL_COST_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,0,'total_cost_quantity');

                        SET V_UNIT_COST_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,0,'unit_cost_quantity');

                        SET V_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,0,'quantity');

                        SET V_LIQUOR_DESCRIPTION_ID=FN_EXTRACT_JSON_DATA(V_CART_DATA,0,'liquor_id');
			END;
        END IF;
    COMMIT;
    SELECT V_CART_DATA,V_CART_ID,V_PAGE_MODE,V_USER_ID,V_TOTAL_COST_QUANTITY,V_UNIT_COST_QUANTITY,V_LIQUOR_DESCRIPTION_ID,V_QUANTITY,V_COUNTER,V_CART_DATA_COUNT;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_CART_PLACE_ORDER(IN P_DATA json)
BEGIN
	DECLARE V_CART_ID,V_USER_ID INT;
    DECLARE V_MODE,V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_ORDER_CODE VARCHAR(225);
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
						VALUES('SEARCH PRODUCT','SP_CART_INSERT_UPDATE_ITEM',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;
	START TRANSACTION;
		SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');

        SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');

        SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');

        SET V_SWAL_TYPE='success',V_SWAL_TITLE='Order Placed Kindly copy the code';

        IF(V_MODE='order')THEN
			BEGIN
				UPDATE cart_details SET is_order_placed=1, order_by_userid=V_USER_ID,order_time=now(),is_order_delivered=0 WHERE id= V_CART_ID;

                INSERT INTO order_details
                (cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,recevied_quantity,
                recevied_cost_lot_size,recevied_total_cost_bottles,order_time,order_by,is_liquor_removed,liquor_removed_by,
                liquor_removal_time,liquor_removal_mode
                )
				SELECT cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,quantity,
                unit_cost_lot_size,total_cost_bottles, (SELECT NOW()),(SELECT V_USER_ID),is_liquor_removed,
                (SELECT V_USER_ID), (SELECT NOW()),'P'
                from cart_liquor
                WHERE cart_id=V_CART_ID;

                SELECT ORDER_CODE INTO V_ORDER_CODE  FROM cart_details WHERE  id=V_CART_ID;

                SET V_SWAL_TEXT=CONCAT('Kindly produce the given code at canteen to get the liquors order code: ',V_ORDER_CODE);
            END;
        ELSEIF(V_MODE='cancel order')THEN
			BEGIN

			END;
        END IF;

    COMMIT;

    SELECT V_SWAL_TEXT,V_SWAL_TITLE,V_SWAL_TYPE,V_CART_ID,V_ORDER_CODE;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_CART_PLACE_ORDER_BACKUP(IN P_DATA json)
BEGIN
	DECLARE V_CART_ID,V_USER_ID INT;
    DECLARE V_MODE,V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_ORDER_CODE VARCHAR(225);
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
						VALUES('SEARCH PRODUCT','SP_CART_INSERT_UPDATE_ITEM',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;
	START TRANSACTION;
		SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');

        SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');

        SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');

        SET V_SWAL_TYPE='success',V_SWAL_TITLE='Order Placed Kindly copy the code';

        IF(V_MODE='order')THEN
			BEGIN
				UPDATE cart_details SET is_order_placed=1, order_by_userid=V_USER_ID,order_time=now(),is_order_delivered=0 WHERE id= V_CART_ID;

                INSERT INTO order_details
                (cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,recevied_quantity,
                recevied_cost_lot_size,recevied_total_cost_bottles,order_time,order_by,is_liquor_removed,liquor_removed_by,
                liquor_removal_time,liquor_removal_mode
                )
				SELECT cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,quantity,
                unit_cost_lot_size,total_cost_bottles, (SELECT NOW()),(SELECT V_USER_ID),is_liquor_removed,
                (SELECT V_USER_ID), (SELECT NOW()),'P'
                from cart_liquor
                WHERE cart_id=V_CART_ID;

                SELECT ORDER_CODE INTO V_ORDER_CODE  FROM cart_details WHERE  id=V_CART_ID;

                SET V_SWAL_TEXT=CONCAT('Kindly produce the given code at canteen to get the liquors order code: ',V_ORDER_CODE);
            END;
        ELSEIF(V_MODE='cancel order')THEN
			BEGIN

			END;
        END IF;

    COMMIT;

    SELECT V_SWAL_TEXT,V_SWAL_TITLE,V_SWAL_TYPE,V_CART_ID,V_ORDER_CODE;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_CHECK_DETAILS()
BEGIN

SELECT entity_id,id,liquor_description_id,actual_available_quantity,0,purchase_price,selling_price,(selling_price-purchase_price),actual_available_quantity,curdate()
				from liquor_entity_mapping where entity_id=24 and actual_available_quantity>0;

END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_CORRECT_STOCKS()
BEGIN
	DECLARE V_LIQUOR_LIST,V_ENTITY_LIST LONGTEXT;
    DECLARE V_LIQUOR_DATA TEXT;
    DECLARE  V_LIQUOR_ENTITY_ID,V_ENTITY_ID,V_PENDING_QUANTITY,V_ORDERED_QUANTITY,V_ACTUAL_AVAILABLE_QUANTITY,V_NEW_QUANTITY,V_LIQOUR_COUNT,V_COUNTER,V_ENTITY_COUNTER,V_ENTITY_COUNT INT DEFAULT 0;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        SELECT @P1,@P2;
    END;

    START TRANSACTION;

		SELECT group_concat(id),count(id)
        INTO V_ENTITY_LIST,V_ENTITY_COUNT
		FROM master_entities WHERE entity_type=2;


		SET V_ENTITY_COUNTER=0;
		WHILE(V_ENTITY_COUNT>V_ENTITY_COUNTER)DO
			BEGIN
				SET V_ENTITY_COUNTER=V_ENTITY_COUNTER+1;
				SET V_ENTITY_ID=FN_STRING_SPLIT(V_ENTITY_LIST,',',V_ENTITY_COUNTER);

				SELECT GROUP_CONCAT(id,'#',(actual_available_quantity-available_quantity),'#',actual_available_quantity),count(id)
				INTO V_LIQUOR_LIST,V_LIQOUR_COUNT
				FROM liquor_entity_mapping
				WHERE entity_id=V_ENTITY_ID;

				SET V_COUNTER=0;
				WHILE(V_LIQOUR_COUNT>V_COUNTER)DO
					BEGIN
						SET V_COUNTER=V_COUNTER+1;

						SET V_LIQUOR_DATA=FN_STRING_SPLIT(V_LIQUOR_LIST,',',V_COUNTER);

						SET V_LIQUOR_ENTITY_ID=FN_STRING_SPLIT(V_LIQUOR_DATA,'#',1);

						SET V_PENDING_QUANTITY=FN_STRING_SPLIT(V_LIQUOR_DATA,'#',2);

						SET V_ACTUAL_AVAILABLE_QUANTITY=FN_STRING_SPLIT(V_LIQUOR_DATA,'#',3);

						SELECT IFNULL(sum(od.quantity),0)
						INTO V_ORDERED_QUANTITY
						FROM liquor_entity_mapping lem
						INNER JOIN order_details od ON od.liquor_entity_id=lem.id
						INNER JOIN cart_details cd ON od.cart_id=cd.id
						WHERE lem.id=V_LIQUOR_ENTITY_ID AND lem.actual_available_quantity>lem.available_quantity AND od.order_process=1 AND cd.cart_type='consumer';

						IF(V_ORDERED_QUANTITY!=V_PENDING_QUANTITY)THEN
							BEGIN
								SET V_NEW_QUANTITY=V_ACTUAL_AVAILABLE_QUANTITY-V_ORDERED_QUANTITY;
							END;
						END IF;

                         INSERT INTO log_liquor_entity_mapping(
						`liquor_entity_id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
						`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
						`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,
						`actual_available_quantity`,`city_id`,`state_id`,`creation_time`,`created_type`,`reorder_level`,`updated_from`
						)SELECT  `id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
						`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
						`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,
						`actual_available_quantity`,`city_id`,`state_id`,(SELECT NOW()),'35',`reorder_level`,'CREATE_STOCKS'
						FROM  liquor_entity_mapping  WHERE id=V_LIQUOR_ENTITY_ID;

						UPDATE liquor_entity_mapping set available_quantity=V_NEW_QUANTITY where id=V_LIQUOR_ENTITY_ID;

					END;
				END WHILE;
			END;
        END WHILE;
    COMMIT;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_CORRECT_STOCKS_AVAILABLE()
BEGIN
	DECLARE V_LIQUOR_LIST LONGTEXT;
    DECLARE V_LIQUOR_ENTITY_ID,V_AVAILABLE_QUANTITY,V_LIQUOR_COUNTER,V_LIQUOR_COUNT INT;


	select group_concat(id),count(id)
    INTO V_LIQUOR_LIST,V_LIQUOR_COUNT
    FROM liquor_entity_mapping WHERE  actual_available_quantity <available_quantity;

	SET V_LIQUOR_COUNTER=0;
	WHILE(V_LIQUOR_COUNT>V_LIQUOR_COUNTER)DO
		BEGIN
			SET V_LIQUOR_COUNTER=V_LIQUOR_COUNTER+1;
			SET V_LIQUOR_ENTITY_ID=FN_STRING_SPLIT(V_LIQUOR_LIST,',',V_LIQUOR_COUNTER);

            SELECT available_quantity
            INTO V_AVAILABLE_QUANTITY
            FROM log_liquor_entity_mapping WHERE liquor_entity_id=V_LIQUOR_ENTITY_ID order by id desc limit 1;

            UPDATE liquor_entity_mapping set available_quantity=V_AVAILABLE_QUANTITY where id=V_LIQUOR_ENTITY_ID;

        END;
	END WHILE;
    select V_LIQUOR_COUNT;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_CREATE_TODAYS_STOCK()
BEGIN
	DECLARE V_ENTITY_ID_LIST TEXT;
	DECLARE V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT VARCHAR(500);
    DECLARE V_ENTITY_ID,V_ENTITY_COUNT,V_COUNT INT DEFAULT 0;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
        GET DIAGNOSTICS CONDITION 1
        @P1=RETURNED_SQLSTATE,@P2=MESSAGE_TEXT;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('todays_Stock','SP_CREATE_TODAYS_STOCK','',@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Cart failed';
        SELECT @P1,@P2;
    END;

	START TRANSACTION;

		SELECT group_concat(DISTINCT entity_id),count(DISTINCT entity_id) INTO V_ENTITY_ID_LIST,V_ENTITY_COUNT
        FROM liquor_entity_mapping;

        WHILE(V_ENTITY_COUNT>V_COUNT)DO
			BEGIN
				SET V_COUNT=V_COUNT+1;
                SET V_ENTITY_ID=FN_STRING_SPLIT(V_ENTITY_ID_LIST,',',V_COUNT);


                INSERT INTO
				liquor_stock_sales(entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_balance,insert_date)
				SELECT entity_id,id,liquor_description_id,actual_available_quantity,0,purchase_price,selling_price,(selling_price-purchase_price),actual_available_quantity,curdate()
				from liquor_entity_mapping where entity_id=V_ENTITY_ID and actual_available_quantity>0;
            END;
        END WHILE;


    COMMIT;

    SELECT V_ENTITY_ID,V_COUNT,V_ENTITY_ID_LIST,V_ENTITY_COUNT;

END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_DELIVERY_CART_CHECK_OUT(IN P_DATA json)
BEGIN
    DECLARE V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_CART_TYPE,V_LIQUOR_DETAILS VARCHAR(500) DEFAULT '';
    DECLARE V_PAGE_MODE,V_ORDER_CODE,V_ORDER_FROM_ENTITY_TYPE VARCHAR(100);
    DECLARE V_CART_DATA JSON;
    DECLARE V_REMOVE_COUNT,V_QUANTITY_UPDATED,V_NOT_AVAILABLE,V_ALLOWED_QUOTA,V_CURRENT_AVAILABLE_QUANTITY,V_PHYISCAL_QUANTITY,V_CAN_PLACE_ORDER,V_QUOTA,V_ORDER_FROM_ENITY_ID,V_CART_ID,V_USER_ID,V_ENTITY_ID,V_ORDER_BY_USERID,V_USED_QUOTA INT;
    DECLARE V_LIQUOR_PER_BOTTLE,V_LIQUOR_DESCRIPTION_ID,V_PREVIOUS_LIQUOR_BOTTLE,V_REMOVED,V_LIQUOR_ENTITY_ID,V_CART_DATA_COUNT,V_COUNTER ,V_QUANTITY,V_LIQUOR_COUNT INT;
    DECLARE V_PREVIOUS_TOTAL_COST_BOTTLES,V_TOTAL_COST_BOTTLES,V_UNIT_COST_LOT_SIZE FLOAT(10,2);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
        GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('CART','SP_DELIVERY_CART_CHECK_OUT',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;

        SET V_CART_DATA=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_data');
        SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');
        SET V_PAGE_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'page_mode');
        SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
        SET V_LIQUOR_COUNT=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_count');
        SET V_LIQUOR_PER_BOTTLE=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_per_bottle');
        SET V_CART_DATA_COUNT=  JSON_LENGTH(V_CART_DATA);
        SET V_COUNTER=0;
        SET V_REMOVE_COUNT=0;
        SET V_QUANTITY_UPDATED=0;
        SET V_NOT_AVAILABLE=0;

        SELECT cart_type,order_by_userid
        INTO V_CART_TYPE,V_ORDER_BY_USERID
        FROM cart_details WHERE id=V_CART_ID;

        SELECT lrq.quota,ca.entity_id
        INTO V_QUOTA,V_ORDER_FROM_ENITY_ID
        FROM ci_admin ca
        INNER JOIN bsf_hrms_data bh ON bh.irla=ca.username
        INNER JOIN master_rank mr ON mr.rank=bh.rank
        INNER JOIN liquor_rank_quota_mapping lrq ON lrq.rankid=mr.id
        WHERE admin_id=V_ORDER_BY_USERID;

        SELECT SUM(if(cd.cart_type='consumer',recevied_quantity,recevied_total_cost_bottles))
        INTO V_PREVIOUS_LIQUOR_BOTTLE
        FROM order_details od
        INNER JOIN cart_details cd ON cd.id=od.cart_id
        WHERE od.cart_id= V_CART_ID AND  od.is_liquor_removed=0 AND od.is_liquor_added!=1;

        SELECT IFNULL(SUM(luq.liquor_count),0)
        INTO V_USED_QUOTA
        FROM liquor_user_used_quota luq
        WHERE userid=V_ORDER_BY_USERID AND MONTH(luq.insert_time)= MONTH(NOW()) AND luq.order_status!=3 AND luq.is_beer=0;

        SET @V_PREVIOUS_USED_QUOTA=V_USED_QUOTA;

         SET V_USED_QUOTA=ABS(V_USED_QUOTA-V_PREVIOUS_LIQUOR_BOTTLE);

         SET V_USED_QUOTA=V_USED_QUOTA+V_LIQUOR_PER_BOTTLE;

         SET V_CAN_PLACE_ORDER=0;



 IF(V_CART_TYPE='consumer')THEN
            BEGIN
                IF(V_QUOTA>V_USED_QUOTA || V_QUOTA=V_USED_QUOTA )THEN
                    BEGIN
                        SET V_CAN_PLACE_ORDER=1;
                        SET V_ORDER_FROM_ENITY_ID=0;

                        SELECT id
                        INTO V_ORDER_FROM_ENTITY_TYPE
                        FROM master_entity_type
                        WHERE entity_type='consumer';

                        WHILE (V_CART_DATA_COUNT>V_COUNTER)DO
                            BEGIN
                                SET V_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'quantity');

                                SET V_REMOVED=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'remove');

                                SET V_LIQUOR_ENTITY_ID=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'liquor_id');

                                IF(V_REMOVED=0)THEN
                                    BEGIN

                                        SELECT available_quantity
                                        INTO V_CURRENT_AVAILABLE_QUANTITY
                                        FROM liquor_entity_mapping
                                        WHERE id=V_LIQUOR_ENTITY_ID;

                                        IF EXISTS(SELECT id FROM order_details WHERE cart_id= V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID)THEN
                                            BEGIN
                                                SELECT if(cd.cart_type='consumer',recevied_quantity,recevied_total_cost_bottles)
                                                INTO V_PREVIOUS_TOTAL_COST_BOTTLES
                                                FROM order_details od
                                                INNER JOIN cart_details cd ON cd.id=od.cart_id
                                                WHERE od.cart_id= V_CART_ID AND od.liquor_entity_id=V_LIQUOR_ENTITY_ID AND od.is_liquor_removed=0;

                                                SET V_CURRENT_AVAILABLE_QUANTITY =V_CURRENT_AVAILABLE_QUANTITY+V_PREVIOUS_TOTAL_COST_BOTTLES;

                                            END;
                                        ELSE
                                            BEGIN
                                                SELECT available_quantity
                                                INTO V_CURRENT_AVAILABLE_QUANTITY
                                                FROM liquor_entity_mapping where id=V_LIQUOR_ENTITY_ID;
                                            END;
                                        END IF;




                                        IF((V_CURRENT_AVAILABLE_QUANTITY < V_QUANTITY) OR (V_CURRENT_AVAILABLE_QUANTITY =0))THEN
                                            BEGIN
                                                SELECT liquor_description_id
                                                INTO V_LIQUOR_DESCRIPTION_ID
                                                FROM liquor_entity_mapping
                                                WHERE id=V_LIQUOR_ENTITY_ID;

                                                SELECT CONCAT(brand,' ',liquor_description,' ',liquor_type,' ',liquor_ml,' ml')
                                                INTO V_LIQUOR_DETAILS
                                                FROM liquor_details
                                                WHERE liquor_description_id=V_LIQUOR_DESCRIPTION_ID;

                                                SET V_NOT_AVAILABLE=1;
                                                SET V_SWAL_TYPE='warning';
                                                SET V_SWAL_TEXT=CONCAT(V_SWAL_TEXT,' Only ',V_CURRENT_AVAILABLE_QUANTITY,' bottles are avaible for ',V_LIQUOR_DETAILS, ' Kindly reduce the quantity or remove the liquor');
                                            END;
                                        END IF;
                                    END;
                                END IF;
                                SET V_COUNTER=V_COUNTER+1;
                            END;
                       END WHILE;
                    END;
                 END IF;
            END;
        ELSE
            BEGIN
                SET V_CAN_PLACE_ORDER=1;
            END;
        END IF;

        SET V_COUNTER=0;



        IF((V_CART_TYPE!='consumer') OR (V_CART_TYPE='consumer' AND V_NOT_AVAILABLE=0 AND V_CAN_PLACE_ORDER=1) )THEN
            BEGIN

                WHILE (V_CART_DATA_COUNT>V_COUNTER)DO
                    BEGIN
                        SET  V_TOTAL_COST_BOTTLES=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'total_cost_quantity');

                        SET  V_UNIT_COST_LOT_SIZE =FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'unit_cost_quantity');

                        SET V_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'quantity');

                        SET V_REMOVED=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'remove');

                        SET V_LIQUOR_ENTITY_ID=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'liquor_id');



                        SELECT available_quantity,actual_available_quantity
                        INTO V_CURRENT_AVAILABLE_QUANTITY,V_PHYISCAL_QUANTITY
                        FROM liquor_entity_mapping
                        WHERE id=V_LIQUOR_ENTITY_ID;

                        IF(V_REMOVED=0)THEN
                            BEGIN


                                IF EXISTS(SELECT id FROM order_details WHERE cart_id= V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID)THEN
                                    BEGIN

                                        SET V_QUANTITY_UPDATED=V_QUANTITY_UPDATED+1;

                                        SELECT if(cd.cart_type='consumer',recevied_quantity,recevied_total_cost_bottles)
                                        INTO V_PREVIOUS_TOTAL_COST_BOTTLES
                                        FROM order_details od
                                        INNER JOIN cart_details cd ON cd.id=od.cart_id
                                        WHERE od.cart_id= V_CART_ID AND od.liquor_entity_id=V_LIQUOR_ENTITY_ID AND od.is_liquor_removed=0;

                                        SET V_CURRENT_AVAILABLE_QUANTITY =V_CURRENT_AVAILABLE_QUANTITY+V_PREVIOUS_TOTAL_COST_BOTTLES;

                                        SET V_CURRENT_AVAILABLE_QUANTITY=V_CURRENT_AVAILABLE_QUANTITY-V_QUANTITY;

                                        UPDATE order_details
                                        SET dispatch_quantity=V_QUANTITY,
                                            dispatch_cost_lot_size=V_UNIT_COST_LOT_SIZE,
                                            dispatch_total_cost_bottles=V_TOTAL_COST_BOTTLES,
                                            dispatch_by=V_USER_ID,
                                            is_liquor_removed=0,
                                            recevied_quantity=V_QUANTITY,
                                            recevied_cost_lot_size=V_UNIT_COST_LOT_SIZE,
                                            recevied_total_cost_bottles=V_TOTAL_COST_BOTTLES,
                                            receive_by=V_USER_ID,
                                            receive_time=NOW()
                                        WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                                    END;
                                ELSE
                                    BEGIN
                                        SET V_CURRENT_AVAILABLE_QUANTITY=V_CURRENT_AVAILABLE_QUANTITY-V_QUANTITY;

                                        INSERT INTO order_details
                                        (cart_id,liquor_entity_id,recevied_quantity,recevied_cost_lot_size,recevied_total_cost_bottles,receive_by,receive_time,
                                        is_liquor_added,liquor_added_by,liquor_add_time)
                                        VALUES(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,
                                        V_TOTAL_COST_BOTTLES,V_USER_ID,NOW(),1,V_USER_ID,Now());

                                    END;
                                END IF;


                            END;
                        ELSE
                            BEGIN
                                SET V_REMOVE_COUNT=V_REMOVE_COUNT+1;

                                SELECT quantity
                                INTO V_PREVIOUS_TOTAL_COST_BOTTLES
                                FROM order_details
                                WHERE cart_id= V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                                SET V_CURRENT_AVAILABLE_QUANTITY =V_CURRENT_AVAILABLE_QUANTITY+V_PREVIOUS_TOTAL_COST_BOTTLES;

                                IF(V_CURRENT_AVAILABLE_QUANTITY>V_PHYISCAL_QUANTITY)THEN
									BEGIN
										SET V_CURRENT_AVAILABLE_QUANTITY=V_PHYISCAL_QUANTITY;
                                    END;
                                END IF;

                                UPDATE liquor_entity_mapping
                                SET available_quantity=V_CURRENT_AVAILABLE_QUANTITY
                                WHERE id=V_LIQUOR_ENTITY_ID;


								INSERT INTO log_liquor_entity_mapping(
								`liquor_entity_id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
								`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
								`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`previous_available_quantity`,`available_quantity`,
								`previous_actual_available_quantity`,`actual_available_quantity`,`city_id`,`state_id`,`creation_time`,`created_type`,`reorder_level`,`updated_from`,`cart_id`
								)SELECT  `id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
								`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
								`selling_price`,`minimun_order_type`,`single_piece_in_lot`,(SELECT V_CURRENT_AVAILABLE_QUANTITY),`available_quantity`,
								`actual_available_quantity`,`actual_available_quantity`,`city_id`,`state_id`,(SELECT NOW()),(SELECT V_USER_ID),`reorder_level`,'DELIVERY_CART_CHECK OUT',V_CART_ID
								FROM  liquor_entity_mapping  WHERE id=V_LIQUOR_ENTITY_ID;




                                UPDATE order_details
                                SET is_liquor_receive=0,
                                    is_liquor_removed=1,
                                    liquor_removed_by=V_USER_ID,
                                    liquor_removal_time=NOW(),
                                    liquor_removal_mode='E',
                                    dispatch_quantity=V_QUANTITY,
                                    dispatch_cost_lot_size=V_UNIT_COST_LOT_SIZE,
                                    dispatch_total_cost_bottles=V_TOTAL_COST_BOTTLES,
                                    dispatch_by=V_USER_ID,
                                    recevied_quantity=V_QUANTITY,
                                    recevied_cost_lot_size=V_UNIT_COST_LOT_SIZE,
                                    recevied_total_cost_bottles=V_TOTAL_COST_BOTTLES,
                                    receive_by=V_ORDER_BY_USERID,
                                    receive_time=NOW()
                                WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                            END;
                        END IF;



                        UPDATE liquor_entity_mapping
                        SET available_quantity=V_CURRENT_AVAILABLE_QUANTITY
                        WHERE id=V_LIQUOR_ENTITY_ID;

                        SET V_COUNTER=V_COUNTER+1;
                    END;
                END WHILE;



                SELECT ordered_to_entity_id,order_code into V_ENTITY_ID,V_ORDER_CODE FROM cart_details WHERE id= V_CART_ID;

                UPDATE liquor_user_used_quota SET liquor_count=V_LIQUOR_PER_BOTTLE WHERE order_code=V_ORDER_CODE;

                SET V_SWAL_TYPE='success',V_SWAL_TITLE='Checkot',V_SWAL_TEXT='checkout';

            END;
        ELSE
            BEGIN
                IF(V_CAN_PLACE_ORDER=0)THEN
                    BEGIN
                        SET V_ALLOWED_QUOTA=V_USED_QUOTA-V_QUOTA;
                        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Maximum allowed Quota Excedded',V_SWAL_TEXT=CONCAT('Kindly remove ',V_ALLOWED_QUOTA,' bottles to successfully place the order');
                    END;
                END IF;
            END;
        END IF;



    COMMIT;
    SELECT V_CURRENT_AVAILABLE_QUANTITY,V_ORDER_BY_USERID,@V_PREVIOUS_USED_QUOTA,V_USED_QUOTA,V_ALLOWED_QUOTA,V_QUOTA,V_PREVIOUS_LIQUOR_BOTTLE,
    V_PREVIOUS_TOTAL_COST_BOTTLES,V_PREVIOUS_LIQUOR_BOTTLE,V_LIQUOR_PER_BOTTLE,V_SWAL_TYPE,V_PAGE_MODE,V_SWAL_TITLE,V_SWAL_TEXT,V_ORDER_CODE,
    V_CART_ID,V_USER_ID,V_QUANTITY_UPDATED,V_REMOVE_COUNT,V_COUNTER,V_ENTITY_ID,
    V_CAN_PLACE_ORDER,V_NOT_AVAILABLE,V_LIQUOR_ENTITY_ID;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_DELIVERY_CART_CHECK_OUT_BACKUP(IN P_DATA json)
BEGIN
	DECLARE V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT VARCHAR(500) DEFAULT '';
    DECLARE V_PAGE_MODE,V_ORDER_CODE VARCHAR(100);
    DECLARE V_CART_DATA JSON;
    DECLARE V_REMOVE_COUNT,V_QUANTITY_UPDATED,V_CART_ID,V_USER_ID,V_ENTITY_ID,V_REMOVED,V_LIQUOR_ENTITY_ID,V_CART_DATA_COUNT,V_COUNTER, V_TOTAL_COST_BOTTLES, V_UNIT_COST_LOT_SIZE ,V_QUANTITY,V_LIQUOR_COUNT INT;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
						VALUES('CART','SP_CART_ORDER_INSERT_UPDATE',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;
		SET V_CART_DATA=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_data');
		SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');
		SET V_PAGE_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'page_mode');
        SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
		SET V_LIQUOR_COUNT=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_count');
		SET	V_CART_DATA_COUNT=	JSON_LENGTH(V_CART_DATA);
        SET V_COUNTER=0;
        SET V_REMOVE_COUNT=0;
		SET V_QUANTITY_UPDATED=0;

        WHILE (V_CART_DATA_COUNT>V_COUNTER)DO
			BEGIN
				SET  V_TOTAL_COST_BOTTLES=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'total_cost_quantity');

				SET  V_UNIT_COST_LOT_SIZE =FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'unit_cost_quantity');

				SET V_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'quantity');

				SET V_REMOVED=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'remove');

				SET V_LIQUOR_ENTITY_ID=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'liquor_id');

				SET V_COUNTER=V_COUNTER+1;


				IF(V_REMOVED=0)THEN
							BEGIN
								IF NOT EXISTS(SELECT id FROM order_details WHERE cart_id= V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID AND quantity=V_QUANTITY)THEN
									BEGIN
                                        SET V_QUANTITY_UPDATED=V_QUANTITY_UPDATED+1;

										UPDATE order_details SET recevied_quantity=V_QUANTITY,recevied_cost_lot_size=V_UNIT_COST_LOT_SIZE,recevied_total_cost_bottles=V_TOTAL_COST_BOTTLES,receive_by=V_USER_ID,receive_time=NOW()
												WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;
										END;
								END IF;
							END;
						ELSE
							BEGIN
								SET V_REMOVE_COUNT=V_REMOVE_COUNT+1;

								UPDATE order_details SET
									is_liquor_receive=0,recevied_quantity=V_QUANTITY,recevied_cost_lot_size=V_UNIT_COST_LOT_SIZE,recevied_total_cost_bottles=V_TOTAL_COST_BOTTLES,receive_by=V_USER_ID,receive_time=NOW()
								WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

								UPDATE order_details SET liquor_count=V_LIQUOR_COUNT WHERE cart_id=V_CART_ID;
                            END;
                        END IF;
			END;
		END WHILE;
        SELECT ordered_to_entity_id,order_code into V_ENTITY_ID,V_ORDER_CODE FROM cart_details WHERE id= V_CART_ID;
                SET V_SWAL_TYPE='success',V_SWAL_TITLE='Checkot',V_SWAL_TEXT='checkout';

    COMMIT;
    SELECT V_SWAL_TYPE,V_PAGE_MODE,V_SWAL_TITLE,V_SWAL_TEXT,V_ORDER_CODE,V_CART_ID,V_USER_ID,V_QUANTITY_UPDATED,V_REMOVE_COUNT,V_ENTITY_ID;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_DELIVER_LIQUOR(IN P_ORDER_CODE varchar(50), IN P_USERID int)
BEGIN
    DECLARE V_SWAL_TYPE,V_ENTITY_NAME,V_SWAL_TITLE,V_SWAL_TEXT,V_CART_TYPE,V_LIQUOR_ENTITY_ID,V_LIQUOR_COUNT,V_QUANTITY,V_NEW_QUANTITY,V_CURRENT_AVAILABLE_QUANTITY,V_LIQUOR VARCHAR(500) DEFAULT '';
    DECLARE V_LIQUOR_ENTITY_DETAILS,V_LIQUOR_ENTITY_ID_QUANTITY,V_LIQUOR_DETAILS,V_LIQUOR_NA_LIST  TEXT DEFAULT '';
    DECLARE V_ORDER_DELIVER_STATUS,V_COUNTER,V_COUNT,V_USER_ENTITY_ID,V1,V_NOT_AVAILABLE,V_LIQUOR_DESCRIPTION_ID,V_UNIT_PROFIT,V_TOTAL_PROFIT,V_ENTITY_ID,V_CART_ID,V_ORDER_PROCESS,V_ORDER_BY_USERID,V_AVAILABLE_QUANTITY,V_BOOKING_QUANTITY,V_BALANCE,V_LIQUOR_NOT_AVAILABLE,V_LIQUOR_OPENING_QTY INT DEFAULT 0;
    DECLARE V_TOTAL_PURCHASE_PRICE,V_TOTAL_COST,V_UNIT_COST,V_PURCHASE_PRICE,V_SELLING_PRICE FLOAT(12,2);
    DECLARE V_LIQUOR_LIST LONGTEXT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
        GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('CART','SP_DELIVER_LIQUOR',P_ORDER_CODE,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;
        SET V_COUNTER=0;
        SET V_NOT_AVAILABLE=0;
        SET V_ORDER_PROCESS=3;
		SET V_LIQUOR_NOT_AVAILABLE=0;
		SET V_LIQUOR_NA_LIST='';

		SELECT IFNULL(entity_id,0) INTO V_USER_ENTITY_ID FROM ci_admin WHERE admin_id= P_USERID;

        IF EXISTS(SELECT id FROM cart_details WHERE order_code=P_ORDER_CODE AND ordered_to_entity_id=V_USER_ENTITY_ID)THEN
			BEGIN
				SELECT id,cart_type,order_by_userid,is_order_delivered
				INTO V_CART_ID,V_CART_TYPE,V_ORDER_BY_USERID,V_ORDER_DELIVER_STATUS
				FROM cart_details WHERE order_code=P_ORDER_CODE AND is_order_delivered=0 AND is_order_cancel=0 ORDER BY id DESC LIMIT 1;

				IF(V_ORDER_DELIVER_STATUS=0)THEN
					BEGIN
						 UPDATE cart_details
						 SET is_order_delivered=1,is_active=0
						 WHERE id=V_CART_ID;

						IF(V_CART_TYPE='entity')THEN
							BEGIN
								UPDATE order_details
								SET order_process=2,dispatch_time=NOW(),dispatch_by=P_USERID
								   WHERE cart_id=V_CART_ID;


								SELECT group_concat(od.liquor_entity_id,'#',od.dispatch_quantity,'#',lem.selling_price,'#',od.dispatch_quantity*lem.selling_price),COUNT(od.id)
                                INTO V_LIQUOR_LIST,V_COUNT
								FROM order_details od
                                INNER JOIN liquor_entity_mapping lem on lem.id=od.liquor_entity_id
                                WHERE cart_id=V_CART_ID AND is_liquor_removed=0;

								SET V_SWAL_TYPE='success',V_SWAL_TITLE='Liquor Dispatched',V_SWAL_TEXT='';
							END;
						ELSE
							BEGIN
								UPDATE order_details
								SET order_process=3,dispatch_time=NOW(),dispatch_by=P_USERID,
								 receive_time=NOW(),order_by=V_ORDER_BY_USERID
								WHERE cart_id=V_CART_ID;

								UPDATE liquor_user_used_quota SET order_status=2 WHERE order_code= P_ORDER_CODE and userid=V_ORDER_BY_USERID;

								SELECT group_concat(liquor_entity_id,'#',dispatch_quantity,'#',dispatch_cost_lot_size,'#',dispatch_total_cost_bottles),COUNT(id)
								INTO V_LIQUOR_LIST,V_COUNT FROM
								order_details WHERE cart_id=V_CART_ID AND is_liquor_removed=0;

								SET V_SWAL_TYPE='success',V_SWAL_TITLE='Liquor Issued',V_SWAL_TEXT='';
							END;
						 END IF;


                                SET V1=0;

								WHILE(V_COUNT>V1)DO
									BEGIN



										SET V1=V1+1;
										SET V_LIQUOR_DETAILS=FN_STRING_SPLIT(V_LIQUOR_LIST,',',V1);
										SET V_LIQUOR_ENTITY_ID=FN_STRING_SPLIT(V_LIQUOR_DETAILS,'#',1);
										SET V_QUANTITY=FN_STRING_SPLIT(V_LIQUOR_DETAILS,'#',2);
										SET V_UNIT_COST=FN_STRING_SPLIT(V_LIQUOR_DETAILS,'#',3);
										SET V_TOTAL_COST=FN_STRING_SPLIT(V_LIQUOR_DETAILS,'#',4);

										SELECT liquor_description_id,entity_id,available_quantity,actual_available_quantity,purchase_price,selling_price
										INTO V_LIQUOR_DESCRIPTION_ID,V_ENTITY_ID,V_BOOKING_QUANTITY,V_AVAILABLE_QUANTITY,V_PURCHASE_PRICE,V_SELLING_PRICE
										FROM liquor_entity_mapping WHERE ID=V_LIQUOR_ENTITY_ID;

										IF(V_AVAILABLE_QUANTITY>V_QUANTITY OR V_AVAILABLE_QUANTITY=V_QUANTITY)THEN
											BEGIN

												SET V_UNIT_PROFIT=V_UNIT_COST-V_PURCHASE_PRICE;


                                                IF NOT EXISTS(SELECT id FROM liquor_stock_sales WHERE  liquor_entity_id=V_LIQUOR_ENTITY_ID AND insert_date=CURDATE())THEN
													BEGIN
														SET V_TOTAL_PURCHASE_PRICE=V_QUANTITY*V_PURCHASE_PRICE;

														SET V_TOTAL_PROFIT=V_TOTAL_COST-V_TOTAL_PURCHASE_PRICE;

														SET V_BALANCE=V_AVAILABLE_QUANTITY-V_QUANTITY;

														INSERT INTO liquor_stock_sales(entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_total_purchase_price,liquor_total_sale_price,liquor_profit,liquor_balance,modification_time,modified_by,insert_date)
																	VALUES(V_ENTITY_ID,V_LIQUOR_ENTITY_ID,V_LIQUOR_DESCRIPTION_ID,V_AVAILABLE_QUANTITY,V_QUANTITY,V_PURCHASE_PRICE,V_UNIT_COST,V_UNIT_PROFIT,V_TOTAL_PURCHASE_PRICE,V_TOTAL_COST,V_TOTAL_PROFIT,V_BALANCE,NOW(),P_USERID,CURDATE());


													END;


												ELSE
													BEGIN
														INSERT INTO log_liquor_stock_sales(entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_total_purchase_price,liquor_total_sale_price,liquor_profit,liquor_balance,modification_time,modified_by,insert_date,order_code)
														SELECT 	entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_total_purchase_price,liquor_total_sale_price,liquor_profit,liquor_balance,modification_time,modified_by,insert_date,(select P_ORDER_CODE) FROM liquor_stock_sales where liquor_entity_id=V_LIQUOR_ENTITY_ID;

														SELECT liquor_opening_qty,liquor_sale_qty
														INTO V_LIQUOR_OPENING_QTY,V_CURRENT_AVAILABLE_QUANTITY
														FROM liquor_stock_sales
														WHERE entity_id=V_ENTITY_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID AND liquor_description_id=V_LIQUOR_DESCRIPTION_ID
														AND liquor_unit_sell_price=V_UNIT_COST AND insert_date=curdate() order by id desc LIMIT 1;

														SET V_NEW_QUANTITY=V_CURRENT_AVAILABLE_QUANTITY+V_QUANTITY;

														SET V_TOTAL_PURCHASE_PRICE=V_NEW_QUANTITY*V_PURCHASE_PRICE;

														SET V_TOTAL_COST=V_NEW_QUANTITY*V_SELLING_PRICE;

														SET V_TOTAL_PROFIT=V_TOTAL_COST-V_TOTAL_PURCHASE_PRICE;

														SET V_BALANCE=V_LIQUOR_OPENING_QTY-V_NEW_QUANTITY;



														UPDATE liquor_stock_sales
														SET
														liquor_sale_qty=V_NEW_QUANTITY,
														liquor_total_purchase_price=V_TOTAL_PURCHASE_PRICE,
														liquor_total_sale_price=V_TOTAL_COST,
														liquor_profit=V_TOTAL_PROFIT,
														liquor_balance=V_BALANCE,
														modification_time=NOW(),
														modified_by=P_USERID
														WHERE entity_id=V_ENTITY_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID AND liquor_description_id=V_LIQUOR_DESCRIPTION_ID
														AND liquor_unit_sell_price=V_UNIT_COST and insert_date=CURDATE();

													END;
												END IF;

														IF(V_BALANCE<V_BOOKING_QUANTITY)THEN
															BEGIN
																SET V_BOOKING_QUANTITY=V_BALANCE;
															END;
                                                        END IF;


														UPDATE liquor_entity_mapping SET available_quantity=V_BOOKING_QUANTITY,actual_available_quantity=V_BALANCE WHERE id=V_LIQUOR_ENTITY_ID;

														INSERT INTO log_liquor_entity_mapping(
														`liquor_entity_id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
														`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
														`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`previous_available_quantity`,`available_quantity`,
														`previous_actual_available_quantity`,`actual_available_quantity`,`city_id`,`state_id`,`creation_time`,`created_type`,`reorder_level`,`updated_from`,`cart_id`
														)SELECT  `id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
														`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
														`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,`available_quantity`,
														(SELECT V_AVAILABLE_QUANTITY),`actual_available_quantity`,`city_id`,`state_id`,(SELECT NOW()),(SELECT P_USERID),`reorder_level`,'DELIVER_LIQUOR',V_CART_ID
														FROM  liquor_entity_mapping  WHERE id=V_LIQUOR_ENTITY_ID;
											END;

										ELSE
											BEGIN
												SELECT CONCAT(brand,' ',liquor_description) INTO V_LIQUOR FROM liquor_details WHERE liquor_description_id=V_LIQUOR_DESCRIPTION_ID;

                                                SET V_LIQUOR_NOT_AVAILABLE=1;

												SET V_LIQUOR_NA_LIST=CONCAT(V_LIQUOR_NA_LIST,',',V_LIQUOR);

                                            END;
										END IF;

									END;
								END WHILE;

                                SET V_LIQUOR_NA_LIST=TRIM(BOTH "," FROM V_LIQUOR_NA_LIST);


						END;
                    ELSE
						BEGIN
							SET V_SWAL_TYPE='success',V_SWAL_TITLE='Liquor Issued',V_SWAL_TEXT='';
                        END;
				END IF;



            END;
         ELSE
			 BEGIN
					Select entity_name INTO V_ENTITY_NAME from master_entities where id in (select ordered_to_entity_id from cart_details where order_code =P_ORDER_CODE);
				    SET V_SWAL_TYPE='warning',V_SWAL_TEXT='Failed',V_SWAL_TITLE=Concat('This order code has been generated for canteen ',V_ENTITY_NAME);
			 END;
         END IF;
    IF(V_LIQUOR_NOT_AVAILABLE=0)THEN
		BEGIN
			COMMIT;
		END;
    ELSE
		BEGIN
			ROLLBACK;
				SET V_SWAL_TITLE=CONCAT(V_LIQUOR_NA_LIST,"Liquor not available.");
                SET V_SWAL_TYPE='warning';
        END;
	END IF;

    SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_CART_TYPE,V_COUNTER,V_CURRENT_AVAILABLE_QUANTITY,V_QUANTITY,V_NOT_AVAILABLE,V_LIQUOR_ENTITY_ID_QUANTITY,V_LIQUOR_ENTITY_ID,V_LIQUOR_DESCRIPTION_ID,V_ENTITY_ID,V_UNIT_COST ;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_DELIVER_LIQUOR_BACKUP(IN P_ORDER_CODE varchar(50))
BEGIN
	DECLARE V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_CART_TYPE,V_LIQUOR_ENTITY_ID,V_LIQUOR_COUNT,V_QUANTITY,V_NEW_QUANTITY,V_CURRENT_AVAILABLE_QUANTITY VARCHAR(225) DEFAULT '';
    DECLARE V_LIQUOR_ENTITY_DETAILS,V_LIQUOR_ENTITY_ID_QUANTITY,V_LIQUOR_DETAILS  TEXT DEFAULT '';
    DECLARE V_COUNTER,V_NOT_AVAILABLE,V_LIQUOR_DESCRIPTION_ID,V_CART_ID,V_ORDER_PROCESS INT DEFAULT 0;
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
						VALUES('CART','SP_CART_ORDER_INSERT_UPDATE',P_ORDER_CODE,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;
		SET V_COUNTER=0;
        SET V_NOT_AVAILABLE=0;
        SET V_ORDER_PROCESS=3;

        SELECT id,cart_type
        INTO V_CART_ID,V_CART_TYPE
        FROM cart_details WHERE order_code=P_ORDER_CODE;




		SELECT group_concat(recevied_quantity,'#',liquor_entity_id),COUNT(quantity) INTO V_LIQUOR_ENTITY_DETAILS,V_LIQUOR_COUNT
        FROM order_details where cart_id=V_CART_ID AND is_liquor_removed=0;

        WHILE(V_COUNTER<V_LIQUOR_COUNT)DO
			BEGIN

                SET V_COUNTER=V_COUNTER+1;
                SET V_LIQUOR_ENTITY_ID_QUANTITY=FN_STRING_SPLIT(V_LIQUOR_ENTITY_DETAILS,',',V_COUNTER);
                SET V_QUANTITY=FN_STRING_SPLIT(V_LIQUOR_ENTITY_ID_QUANTITY,'#',1);
                SET V_LIQUOR_ENTITY_ID=FN_STRING_SPLIT(V_LIQUOR_ENTITY_ID_QUANTITY,'#',2);

                SELECT liquor_description_id,available_quantity INTO V_LIQUOR_DESCRIPTION_ID,V_CURRENT_AVAILABLE_QUANTITY
                FROM liquor_entity_mapping WHERE id=V_LIQUOR_ENTITY_ID;

                IF NOT EXISTS(SELECT id FROM liquor_entity_mapping where id=V_LIQUOR_ENTITY_ID and ((available_quantity > V_QUANTITY)|| (available_quantity = V_QUANTITY)))THEN
					BEGIN
						SELECT CONCAT(brand,' ',liquor_description,' ',liquor_type,' ',liquor_ml,' ml') INTO V_LIQUOR_DETAILS FROM liquor_details WHERE liquor_description_id=V_LIQUOR_DESCRIPTION_ID;
						SET V_NOT_AVAILABLE=1;
                        SET V_SWAL_TYPE='warning';
                        SET V_SWAL_TEXT=CONCAT(V_SWAL_TEXT,' Only ',V_CURRENT_AVAILABLE_QUANTITY,' bottles are avaible for ',V_LIQUOR_DETAILS, ' Kindly reduce the quantity or remove the liquor');
                    END;
                END IF;
            END;
        END WHILE;

        IF(V_NOT_AVAILABLE=0)THEN
			BEGIN
				SET V_COUNTER=0;
				WHILE(V_COUNTER < V_LIQUOR_COUNT)DO
					BEGIN
						SET V_COUNTER=V_COUNTER+1;
						SET V_LIQUOR_ENTITY_ID_QUANTITY=FN_STRING_SPLIT(V_LIQUOR_ENTITY_DETAILS,',',V_COUNTER);
						SET V_QUANTITY=FN_STRING_SPLIT(V_LIQUOR_ENTITY_ID_QUANTITY,'#',1);
						SET V_LIQUOR_ENTITY_ID=FN_STRING_SPLIT(V_LIQUOR_ENTITY_ID_QUANTITY,'#',2);

						SELECT liquor_description_id,available_quantity INTO V_LIQUOR_DESCRIPTION_ID,V_CURRENT_AVAILABLE_QUANTITY
						FROM liquor_entity_mapping WHERE id=V_LIQUOR_ENTITY_ID;

                        IF(V_CART_TYPE='entity')THEN
							BEGIN
								SET V_NEW_QUANTITY=V_CURRENT_AVAILABLE_QUANTITY-V_QUANTITY;
								UPDATE liquor_entity_mapping SET available_quantity= V_NEW_QUANTITY WHERE  id=V_LIQUOR_ENTITY_ID;
								SET V_ORDER_PROCESS=2;
                            END;
						END IF;
					END;
				END WHILE;

				UPDATE cart_details
				SET is_order_delivered=1
				WHERE order_code=P_ORDER_CODE;

				UPDATE order_details SET order_process=V_ORDER_PROCESS,dispatch_time=NOW() WHERE cart_id=V_CART_ID;

				SET V_SWAL_TYPE='success',V_SWAL_TITLE='Liquor Delivered',V_SWAL_TEXT='';
			END;
         END IF;
    COMMIT;

    SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_CART_TYPE,V_COUNTER,V_CURRENT_AVAILABLE_QUANTITY,V_QUANTITY,V_NOT_AVAILABLE,V_LIQUOR_ENTITY_ID_QUANTITY ;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_EDIT_LIQUOR_DETAILS(IN P_DATA json)
BEGIN
        DECLARE V_MODE,V_LIQUOR_TYPE,V_LIQUOR_NAME,V_ENTITY_TYPE,  V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE VARCHAR(225);
    DECLARE V_LIQUOR_TYPE_ID,V_ID,V_USER_ID,V_MOQ,V_ML,V_ENTITY_ID,V_STOCK_SALE_ID,V_OPENING_QTY,V_PRE_DISPLAY_AVAILABLE_QUANTITY,V_PRE_PHYSICAL_AVAILABLE_QUANTITY,V_LIQUOR_BALANCE,V_SALE_QTY,V_UNIT_PROFIT,V_LIQUOR_DESCRIPTION_ID,V_QUANTITY_DIFF,V_DISPLAY_AVAILABLE_QUANTITY,V_PHYSICAL_AVAILABLE_QUANTITY,V_AVAILABLE_QUANTITY,V_REORDER_LEVEL INT;
    DECLARE V_SELL_PRICE,V_PURCHASE_PRICE,V_TOTAL_SELL_PRICE,V_TOTAL_PURCHASE_PRICE,V_TOTAL_PROFIT FLOAT(10,2);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
                ROLLBACK;
                GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;

        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
        VALUES('ADD/EDIT PRODUCT MAPPING','SP_INSERT_UPDATE_LIQUOR_MAPPING_DETAILS',P_DATA,@P2,@P1);

         SET V_SWAL_TITLE='Error!',V_SWAL_MESSAGE='Something Went wrong, Please Contact Ats team',V_SWAL_TYPE='error';

        SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,@P1,@P2;
    END;

    START TRANSACTION;
		INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
		VALUES('ADD/EDIT PRODUCT MAPPING','SP_INSERT_UPDATE_LIQUOR_MAPPING_DETAILS',P_DATA,@P2,@P1);
		SET V_SWAL_TITLE='SUCCESS',V_SWAL_TYPE='success';


		SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
		SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');
		SET V_AVAILABLE_QUANTITY=FN_EXTRACT_JSON_DATA(P_DATA,0,'available_quantity');
		SET V_REORDER_LEVEL=FN_EXTRACT_JSON_DATA(P_DATA,0,'reorder_level');
		SET V_LIQUOR_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_type');
		SET V_LIQUOR_NAME =FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_brand');
		SET V_LIQUOR_DESCRIPTION_ID=FN_STRING_SPLIT(V_LIQUOR_NAME,'#',1);
		SET V_ENTITY_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'entity_type');
		SET V_ENTITY_ID =FN_EXTRACT_JSON_DATA(P_DATA,0,'entity');
		SET V_ML =FN_EXTRACT_JSON_DATA(P_DATA,0,'select_ml');
		SET V_MOQ=FN_EXTRACT_JSON_DATA(P_DATA,0,'moq');
		SET V_SELL_PRICE=FN_EXTRACT_JSON_DATA(P_DATA,0,'sell_price');
		SET V_PURCHASE_PRICE=FN_EXTRACT_JSON_DATA(P_DATA,0,'purchase_price');

		IF(V_MODE='U')THEN
		BEGIN
			SET V_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'id');

			SELECT entity_id,liquor_description_id,selling_price,purchase_price
			INTO V_ENTITY_ID,V_LIQUOR_DESCRIPTION_ID,V_SELL_PRICE,V_PURCHASE_PRICE
			FROM liquor_entity_mapping WHERE id =V_ID;

			INSERT INTO log_liquor_entity_mapping(
			`liquor_entity_id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
			`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
			`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,
			`actual_available_quantity`,`city_id`,`state_id`,`creation_time`,`created_type`,`reorder_level`,`updated_from`
			)SELECT  `id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
			`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
			`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,
			`actual_available_quantity`,`city_id`,`state_id`,(SELECT NOW()),V_USER_ID,`reorder_level`,'EDIT STOCKS'
			FROM  liquor_entity_mapping  WHERE id=V_ID;

			UPDATE liquor_entity_mapping
			SET available_quantity=V_AVAILABLE_QUANTITY,
			actual_available_quantity=V_AVAILABLE_QUANTITY where id=V_ID;



			IF EXISTS(SELECT id FROM liquor_stock_sales where insert_date=curdate() and liquor_entity_id=V_ID)THEN
				BEGIN
					SELECT id INTO V_STOCK_SALE_ID FROM liquor_stock_sales where insert_date=curdate() and liquor_entity_id=V_ID;

					UPDATE liquor_stock_sales
					SET liquor_opening_qty=V_AVAILABLE_QUANTITY,liquor_balance=V_AVAILABLE_QUANTITY
					WHERE id=V_STOCK_SALE_ID;

				END;
			END IF;

			SET V_SWAL_TITLE='Success';
			SET V_SWAL_MESSAGE='Details updated successfully';
			SET V_SWAL_TYPE='warning';
		END;
		END IF;

    COMMIT;
SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,V_LIQUOR_TYPE,V_LIQUOR_NAME,V_ENTITY_ID,V_REORDER_LEVEL;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_GET_ORDER_DETAIL_BY_ORDER_CODE(IN P_ORDERCODE varchar(20))
BEGIN
SELECT
    mainorder.*,
    entitydetail.entity_name,
    entitydetail.state,
    entitydetail.entity_type,
    Concat(IFNULL(entitydetail.entity_name,'N/A'),' - ',IFNULL(entitydetail.state,'N/A')) as entity_details
FROM
    (SELECT
            ocd.*,
            concat(ocd.recevied_quantity, '_', lled.id, '_', ocd.cart_id) AS quantity,
            (lled.selling_price * ocd.recevied_total_cost_bottles)        as total_cost,
            lled.selling_price                                            as selling_price,
            (select username from ci_admin where admin_id = ocd.order_by)    irla,
            (select firstname from ci_admin where admin_id = ocd.order_by)   name,
            lled.liquor_description_id,
            lled.liquor_type,
            lled.liquor_ml,
            lled.liquor_image,
            lled.liquor_id,
            lled.liquor_name
            -- ocd.order_from_entity_type
     FROM (SELECT cd.id                                              as cart_id,
                  cd.cart_type,
                  cd.order_code                                      AS order_code,
                  cd.liquor_count,
                  cd.ordered_to_entity_id,

                  od.recevied_cost_lot_size                          as unit_lot_cost,
                  od.liquor_entity_id,
                  (od.recevied_cost_lot_size * od.recevied_quantity) AS total_quantity_cost,
                  od.is_liquor_removed                               AS is_liquor_removed,
                  od.recevied_quantity,
                  od.recevied_total_cost_bottles,
                  od.order_by,
                  cd.order_from_entity_type,
                  cd.order_from_entity_id
           FROM cart_details cd
                    INNER JOIN
                order_details od ON
                            cd.id = od.cart_id
                        AND cd.order_code = P_ORDERCODE
                        AND cd.is_order_placed = '1'
                        AND cd.is_order_delivered = 0
                        AND cd.is_order_cancel = 0) AS ocd
              JOIN
          (SELECT lem.id,
                  lem.selling_price,
                  lem.liquor_description_id,
                  Concat(ld.brand, ' ', ld.liquor_description, ' ', ld.bottle_size) AS liquor_name,
                  ld.liquor_description_id                                          AS liquor_id,
                  ld.liquor_type,
                  ld.liquor_ml,
                  ld.liquor_image
           FROM liquor_entity_mapping as lem
                    INNER JOIN
                liquor_details as ld on
                    ld.liquor_description_id = lem.liquor_description_id) AS lled
          ON ocd.liquor_entity_id = lled.id
          ) mainorder
    LEFT JOIN
          (
            SELECT
                me.id ofei,
                met.id ofet,
                me.entity_name,
                met.entity_type,
                (SELECT state FROM master_state WHERE id=me.state) state
            FROM master_entities me
                INNER JOIN
                master_entity_type met ON me.entity_type=met.id AND met.id=6 AND me.id=0
          ) entitydetail
    ON
        mainorder.order_from_entity_id = entitydetail.ofei
            AND
        mainorder.order_from_entity_type = entitydetail.ofet;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_GET_PURACHASE_PRICE_TAX(IN P_LIQUOR_DESCRIPTION_ID varchar(10),
                                                                                 IN P_ENTITY_ID varchar(10),
                                                                                 IN P_PURCHASE_PRICE double)
BEGIN
    SELECT
        IFNULL(SUM(tax_percent),0) AS tax_value,
        IFNULL(tax_type_id,0),
        P_PURCHASE_PRICE purchase_price
    FROM
        master_tax_liquor_mapping
    WHERE
        liquor_description_id= P_LIQUOR_DESCRIPTION_ID
      AND entity_id= P_ENTITY_ID
      AND tax_id!='38'
      AND isactive='1'
    GROUP BY tax_type_id
    ORDER BY tax_type_id DESC;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_GET_SELLING_PRICE_TAX(IN P_LIQUOR_DESCRIPTION_ID varchar(10),
                                                                               IN P_ENTITY_ID varchar(10),
                                                                               IN P_ENTITY_TYPE varchar(10),
                                                                               IN P_SELLING_PRICE double)
BEGIN
    IF (ISNULL(P_ENTITY_TYPE) OR P_ENTITY_TYPE = '') THEN
        BEGIN
           SELECT entity_type INTO P_ENTITY_TYPE FROM master_entities WHERE id=P_ENTITY_ID;
        END;
    END IF;
    IF (P_ENTITY_TYPE = 1) THEN
        BEGIN
            IF EXISTS(SELECT * FROM
                        master_tax_liquor_mapping
                    WHERE
                        liquor_description_id= P_LIQUOR_DESCRIPTION_ID
                      AND entity_id= P_ENTITY_ID
                      AND isactive='1') THEN
                BEGIN
                    SELECT
                        IFNULL(SUM(tax_percent),0) AS tax_value,
                        IFNULL(tax_type_id,0) tax_type_id,
                        P_SELLING_PRICE selling_price
                    FROM
                        master_tax_liquor_mapping
                    WHERE
                        liquor_description_id= P_LIQUOR_DESCRIPTION_ID
                      AND entity_id= P_ENTITY_ID
                      AND isactive='1'
                    GROUP BY tax_type_id
                    ORDER BY tax_type_id DESC;
                END;
            ELSE
                BEGIN
                    SELECT
                        0 AS tax_value,
                        0 tax_type_id,
                        P_SELLING_PRICE selling_price;
                END;
            END IF;
        END;
    ELSEIF (P_ENTITY_TYPE = 2) THEN
        BEGIN
            IF EXISTS(SELECT * FROM
                    master_tax_liquor_mapping
                WHERE
                    liquor_description_id= P_LIQUOR_DESCRIPTION_ID
                  AND entity_id= P_ENTITY_ID
                  AND isactive='1') THEN
                BEGIN
                    SELECT
                        IFNULL(SUM(tax_percent),0) AS tax_value,
                        IFNULL(tax_type_id,0) tax_type_id,
                        P_SELLING_PRICE selling_price
                    FROM
                        master_tax_liquor_mapping
                    WHERE
                        liquor_description_id= P_LIQUOR_DESCRIPTION_ID
                      AND entity_id= P_ENTITY_ID
                      AND isactive='1'
                    GROUP BY tax_type_id
                    ORDER BY tax_type_id DESC;
                END;
            ELSE
                BEGIN
                    SELECT
                        0 AS tax_value,
                        0 tax_type_id,
                        P_SELLING_PRICE selling_price;
                END;
            END IF;
        END;
    ELSE
        BEGIN
            SELECT
                0 AS tax_value,
                0 tax_type_id,
                P_SELLING_PRICE selling_price;
        END;
    END IF;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_INSERT_CANTEEN_DETAILS(IN P_DATA json)
BEGIN
	DECLARE V_ENTITY_NAME,V_ADDRESS, V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,V_CHAIRMAN_MAIL_ID,V_SUPERVISOR_MAIL_ID,V_EXECUTIVE_MAIL_ID VARCHAR(225);
    DECLARE V_CITY,V_ENTITY_ID,V_USER_ID,V_STATE,V_CHAIRMAN,V_SUPERVISOR,V_EXECUTIVE,V_DISTRIBUTING_ENTITY_TYPE,V_AUTHORISED_ENTITY,V_BREWERY_ENTITY_TYPE_ID,V_OUTLET_TYPE INT;
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
		INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
			VALUES('ADD/EDIT CANTEEN MASTER','SP_INSERT_CANTEEN_DETAILS',P_DATA,@P2,@P1);
        SET V_SWAL_TITLE='Error!',V_SWAL_MESSAGE='Something Went wrong, Please Contact Ats team',V_SWAL_TYPE='error';
		SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,@P1,@P2;
    END;

    START TRANSACTION;

        SET V_OUTLET_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'outlet_type');
		SET V_ENTITY_NAME =FN_EXTRACT_JSON_DATA(P_DATA,0,'entity_name');
        SET V_ADDRESS =FN_EXTRACT_JSON_DATA(P_DATA,0,'address');
        SET V_CITY =FN_EXTRACT_JSON_DATA(P_DATA,0,'city');
        SET V_STATE=FN_EXTRACT_JSON_DATA(P_DATA,0,'state');
        SET V_CHAIRMAN=FN_EXTRACT_JSON_DATA(P_DATA,0,'chairman');
		SET V_SUPERVISOR=FN_EXTRACT_JSON_DATA(P_DATA,0,'supervisor');
		SET V_EXECUTIVE=FN_EXTRACT_JSON_DATA(P_DATA,0,'executive');
		SET V_DISTRIBUTING_ENTITY_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'distrubuting_entity_type');
		SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');

		SET V_AUTHORISED_ENTITY=FN_EXTRACT_JSON_DATA(P_DATA,0,'distributor_authorised_entity');



        SELECT email INTO V_CHAIRMAN_MAIL_ID FROM ci_admin WHERE admin_id=V_CHAIRMAN;
		SELECT email INTO V_SUPERVISOR_MAIL_ID FROM ci_admin WHERE admin_id=V_SUPERVISOR;
		SELECT email INTO V_EXECUTIVE_MAIL_ID FROM ci_admin WHERE admin_id=V_EXECUTIVE;

        SELECT ID INTO V_BREWERY_ENTITY_TYPE_ID FROM master_entity_type WHERE entity_type='Brewery';

        IF(V_DISTRIBUTING_ENTITY_TYPE=V_BREWERY_ENTITY_TYPE_ID)THEN
			BEGIN
				INSERT INTO master_entities(entity_name,address,city,state,chairman,supervisor,executive,
                chairman_mailid,supervisor_mailid,executive_mailid,authorised_distributor,authorised_brewery,
                entity_type,created_by,creation_time)VALUES
                (V_ENTITY_NAME,V_ADDRESS,V_CITY,V_STATE,V_CHAIRMAN,V_SUPERVISOR,V_EXECUTIVE,
                V_CHAIRMAN_MAIL_ID,V_SUPERVISOR_MAIL_ID,V_EXECUTIVE_MAIL_ID,V_DISTRIBUTING_ENTITY_TYPE,V_AUTHORISED_ENTITY,
                V_OUTLET_TYPE,V_USER_ID,NOW()
                );
                SET V_ENTITY_ID=last_insert_id();
			END;
        ELSE
			BEGIN
				INSERT INTO master_entities(entity_name,address,city,state,chairman,supervisor,executive,
                chairman_mailid,supervisor_mailid,executive_mailid,authorised_distributor,authorised_entity,
                entity_type)VALUES
                (V_ENTITY_NAME,V_ADDRESS,V_CITY,V_STATE,V_CHAIRMAN,V_SUPERVISOR,V_EXECUTIVE,
                V_CHAIRMAN_MAIL_ID,V_SUPERVISOR_MAIL_ID,V_EXECUTIVE_MAIL_ID,V_DISTRIBUTING_ENTITY_TYPE,V_AUTHORISED_ENTITY,
                V_OUTLET_TYPE
                );

                SET V_ENTITY_ID=last_insert_id();
			END;
        END IF;

        UPDATE ci_admin SET entity_id=V_ENTITY_ID WHERE ID IN (V_CHAIRMAN,V_SUPERVISOR,V_EXECUTIVE);
                SET V_SWAL_TITLE='',V_SWAL_MESSAGE='Registered Successfully',V_SWAL_TYPE='success';



	COMMIT;
SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_INSERT_UPDATE_CANTEEN_DETAILS(IN P_DATA json)
BEGIN
		DECLARE V_MODE,V_ENTITY_NAME,V_BATTALION_UNIT,V_ADDRESS,V_REGISTERED_BY,V_REGISTRATION_TIME,V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,V_CHAIRMAN_MAIL_ID,V_SUPERVISOR_MAIL_ID,V_EXECUTIVE_MAIL_ID,V_TABLE_NAME,V_AUTHORISED_ENTITY VARCHAR(255);
		DECLARE V_CITY,V_ENTITY_ID,V_ID,V_LAST_UPDATE_RECORD_LOG_ID,V_STATE,V_USER_ID,V_CURRENT_CHAIRMAN,V_CURRENT_SUPERVISOR,V_CURRENT_EXECUTIVE,V_CHAIRMAN,V_SUPERVISOR,V_DISTRIBUTING_ENTITY_TYPE,V_EXECUTIVE,V_OUTLET_TYPE,V_BREWERY_ENTITY_TYPE_ID VARCHAR(100);
        DECLARE V_CHAIRMAN_MOBILE_NO,V_SUPERVISOR_MOBILE_NO,V_EXECUTIVE_MOBILE_NO VARCHAR(20);

        DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			ROLLBACK;
			GET DIAGNOSTICS CONDITION 1
			@P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
			CALL SP_LOG_APPLICATION_ERROR('ADD/EDIT CANTEEN MASTER','SP_INSERT_CANTEEN_DETAILS'
			    ,P_DATA,@P1,@P2);
			SET V_SWAL_TITLE='Error!',V_SWAL_MESSAGE='Something Went wrong, Please Contact Ats team',V_SWAL_TYPE='error';
			SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,@P1,@P2;
		END;

		START TRANSACTION;
			SET V_OUTLET_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'outlet_type');
            SET V_BATTALION_UNIT=FN_EXTRACT_JSON_DATA(P_DATA,0,'battalion_unit');
			SET V_ENTITY_NAME =FN_EXTRACT_JSON_DATA(P_DATA,0,'entity_name');
			SET V_ADDRESS =FN_EXTRACT_JSON_DATA(P_DATA,0,'address');
			SET V_CITY =FN_EXTRACT_JSON_DATA(P_DATA,0,'city');
			SET V_STATE=FN_EXTRACT_JSON_DATA(P_DATA,0,'state');
			SET V_CHAIRMAN=FN_EXTRACT_JSON_DATA(P_DATA,0,'chairman');
			SET V_SUPERVISOR=FN_EXTRACT_JSON_DATA(P_DATA,0,'supervisor');
			SET V_EXECUTIVE=FN_EXTRACT_JSON_DATA(P_DATA,0,'executive');
			SET V_DISTRIBUTING_ENTITY_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'distrubuting_entity_type');
			SET V_AUTHORISED_ENTITY=FN_EXTRACT_JSON_DATA(P_DATA,0,'distributor_authorised_entity');
			SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');
			SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');

		    SET V_AUTHORISED_ENTITY = IFNULL(V_AUTHORISED_ENTITY,'0');
		    SET V_DISTRIBUTING_ENTITY_TYPE = IFNULL(V_DISTRIBUTING_ENTITY_TYPE,'0');

		    SET V_AUTHORISED_ENTITY = IF(UCASE(V_AUTHORISED_ENTITY)='NULL' OR LTRIM(RTRIM(V_AUTHORISED_ENTITY))='','0',V_AUTHORISED_ENTITY);
            SET V_DISTRIBUTING_ENTITY_TYPE = IF(UCASE(V_DISTRIBUTING_ENTITY_TYPE)='NULL' OR LTRIM(RTRIM(V_DISTRIBUTING_ENTITY_TYPE))='','0',V_DISTRIBUTING_ENTITY_TYPE);


			SELECT email,mobile_no INTO V_CHAIRMAN_MAIL_ID,V_CHAIRMAN_MOBILE_NO FROM ci_admin WHERE admin_id=V_CHAIRMAN;
			SELECT email,mobile_no INTO V_SUPERVISOR_MAIL_ID,V_SUPERVISOR_MOBILE_NO FROM ci_admin WHERE admin_id=V_SUPERVISOR;
			SELECT email,mobile_no INTO V_EXECUTIVE_MAIL_ID,V_EXECUTIVE_MOBILE_NO FROM ci_admin WHERE admin_id=V_EXECUTIVE;

		    CALL SP_LOG_USER_ACTIVITY(V_USER_ID,'CANTEEN',V_MODE,P_DATA);

			IF(V_MODE='A')THEN
				BEGIN
					IF NOT EXISTS(SELECT id FROM master_entities WHERE entity_name =V_ENTITY_NAME)THEN
						BEGIN
							INSERT INTO master_entities(entity_name,battalion_unit,address,city,state,chairman,supervisor,executive,
							chairman_mailid,supervisor_mailid,executive_mailid,authorised_distributor_entity_type_id,authorised_distributor,
							entity_type,created_by,creation_time,chairman_mobileno,supervisor_mobileno,executive_mobileno)VALUES
							(V_ENTITY_NAME,V_BATTALION_UNIT,V_ADDRESS,V_CITY,V_STATE,V_CHAIRMAN,V_SUPERVISOR,V_EXECUTIVE,
							V_CHAIRMAN_MAIL_ID,V_SUPERVISOR_MAIL_ID,V_EXECUTIVE_MAIL_ID,V_DISTRIBUTING_ENTITY_TYPE,
							V_AUTHORISED_ENTITY,V_OUTLET_TYPE,V_USER_ID,NOW()
							,V_CHAIRMAN_MOBILE_NO,V_SUPERVISOR_MOBILE_NO,V_EXECUTIVE_MOBILE_NO
							);

							SELECT id INTO V_ENTITY_ID FROM master_entities WHERE entity_name=V_ENTITY_NAME;
							SET V_SWAL_TITLE='Registeration Completed';
                            SET V_SWAL_MESSAGE=CONCAT(V_ENTITY_NAME,' has been registered successfully');
                            SET V_SWAL_TYPE="success";

                        END;
					ELSE
						BEGIN
							SELECT CONCAT(ca.firstname,' - ',username),date_format(me.creation_time,'%d-%m-%Y %H:%i:%s')
                            INTO  V_REGISTERED_BY,V_REGISTRATION_TIME
                            FROM master_entities me
                            INNER JOIN ci_admin ca on ca.admin_id= me.created_by
                            WHERE entity_name = V_ENTITY_NAME;

                            SET V_SWAL_TITLE='Already Registered';
                            SET V_SWAL_MESSAGE=CONCAT(V_ENTITY_NAME,' is already registered by ',V_REGISTERED_BY,' on :',V_REGISTRATION_TIME);
                            SET V_SWAL_TYPE="warning";

                        END;
					END IF;
				END;
			ELSE
				BEGIN
                	SET V_ENTITY_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'entity_id');

					SELECT chairman,supervisor,executive
					INTO V_CURRENT_CHAIRMAN,V_CURRENT_SUPERVISOR,V_CURRENT_EXECUTIVE
					FROM master_entities WHERE id =V_ENTITY_ID;

                	UPDATE master_entities
                	    SET
                	        chairman=V_CHAIRMAN,supervisor=V_SUPERVISOR,executive=V_EXECUTIVE
                	        ,city=V_CITY,state=V_STATE,address=V_ADDRESS,entity_name=V_ENTITY_NAME,
                	         chairman_mailid=V_CHAIRMAN_MAIL_ID,chairman_mobileno=V_CHAIRMAN_MOBILE_NO,
                	         supervisor_mailid=V_SUPERVISOR_MAIL_ID,supervisor_mobileno=V_SUPERVISOR_MOBILE_NO,
                	         executive_mailid=V_EXECUTIVE_MAIL_ID,executive_mobileno=V_EXECUTIVE_MOBILE_NO
                	WHERE id =V_ENTITY_ID;

					UPDATE ci_admin
                    SET entity_id=0,admin_role_id=5
                    WHERE admin_id IN (V_CURRENT_CHAIRMAN,V_CURRENT_SUPERVISOR,V_CURRENT_EXECUTIVE);

                    SET V_SWAL_TITLE='Details Updated';
					SET V_SWAL_MESSAGE=CONCAT('Details have been updated into the system ',V_ENTITY_NAME);
					SET V_SWAL_TYPE="success";
				END;
			END IF;
            UPDATE ci_admin SET
                entity_id=V_ENTITY_ID,
                admin_role_id=2 WHERE admin_id IN (V_CHAIRMAN,V_SUPERVISOR,V_EXECUTIVE);
		COMMIT;
	 SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_INSERT_UPDATE_CANTEEN_DETAILS_BACKUP(IN P_DATA json)
BEGIN
		DECLARE V_MODE,V_ENTITY_NAME,V_ADDRESS,V_REGISTERED_BY,V_REGISTRATION_TIME, V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,V_CHAIRMAN_MAIL_ID,V_SUPERVISOR_MAIL_ID,V_EXECUTIVE_MAIL_ID,V_TABLE_NAME VARCHAR(225);
		DECLARE V_CITY,V_ENTITY_ID,V_ID,V_LAST_UPDATE_RECORD_LOG_ID,V_STATE,V_USER_ID,V_CURRENT_CHAIRMAN,V_CURRENT_SUPERVISOR,V_CURRENT_EXECUTIVE,V_CHAIRMAN,V_SUPERVISOR,V_EXECUTIVE,V_DISTRIBUTING_ENTITY_TYPE,V_AUTHORISED_ENTITY,V_BREWERY_ENTITY_TYPE_ID,V_OUTLET_TYPE INT;

		DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			ROLLBACK;
			GET DIAGNOSTICS CONDITION 1
			@P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
			INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
				VALUES('ADD/EDIT CANTEEN MASTER','SP_INSERT_CANTEEN_DETAILS',P_DATA,@P2,@P1);
			SET V_SWAL_TITLE='Error!',V_SWAL_MESSAGE='Something Went wrong, Please Contact Ats team',V_SWAL_TYPE='error';
			SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,@P1,@P2;
		END;

		START TRANSACTION;

			SET V_OUTLET_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'outlet_type');
			SET V_ENTITY_NAME =FN_EXTRACT_JSON_DATA(P_DATA,0,'entity_name');
			SET V_ADDRESS =FN_EXTRACT_JSON_DATA(P_DATA,0,'address');
			SET V_CITY =FN_EXTRACT_JSON_DATA(P_DATA,0,'city');
			SET V_STATE=FN_EXTRACT_JSON_DATA(P_DATA,0,'state');
			SET V_CHAIRMAN=FN_EXTRACT_JSON_DATA(P_DATA,0,'chairman');
			SET V_SUPERVISOR=FN_EXTRACT_JSON_DATA(P_DATA,0,'supervisor');
			SET V_EXECUTIVE=FN_EXTRACT_JSON_DATA(P_DATA,0,'executive');
			SET V_DISTRIBUTING_ENTITY_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'distrubuting_entity_type');
			SET V_AUTHORISED_ENTITY=FN_EXTRACT_JSON_DATA(P_DATA,0,'distributor_authorised_entity');
			SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');
			SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');

			SELECT email INTO V_CHAIRMAN_MAIL_ID FROM ci_admin WHERE admin_id=V_CHAIRMAN;
			SELECT email INTO V_SUPERVISOR_MAIL_ID FROM ci_admin WHERE admin_id=V_SUPERVISOR;
			SELECT email INTO V_EXECUTIVE_MAIL_ID FROM ci_admin WHERE admin_id=V_EXECUTIVE;

			INSERT INTO log_ci_admin(admin_id,admin_role_id,entity_id,username,firstname,lastname,email,mobile_no,date_of_birth,
            image,password,last_login,is_verify,is_admin,is_active,is_supper,token,password_reset_code,android_uuid,gcm_id,last_ip,
            created_at,updated_at)
			SELECT admin_id,admin_role_id,entity_id,username,firstname,lastname,email,mobile_no,date_of_birth,
            image,password,last_login,is_verify,is_admin,is_active,is_supper,token,password_reset_code,android_uuid,gcm_id,last_ip,
            created_at,(SELECT NOW()) FROM ci_admin WHERE admin_id IN (V_CHAIRMAN,V_SUPERVISOR,V_EXECUTIVE);

			IF(V_MODE='A')THEN
				BEGIN
					IF NOT EXISTS(SELECT id FROM master_entities WHERE entity_name =V_ENTITY_NAME)THEN
						BEGIN
							INSERT INTO master_entities(entity_name,address,city,state,chairman,supervisor,executive,
							chairman_mailid,supervisor_mailid,executive_mailid,authorised_distributor_entity_type_id,authorised_distributor,
							entity_type,created_by,creation_time)VALUES
							(V_ENTITY_NAME,V_ADDRESS,V_CITY,V_STATE,V_CHAIRMAN,V_SUPERVISOR,V_EXECUTIVE,
							V_CHAIRMAN_MAIL_ID,V_SUPERVISOR_MAIL_ID,V_EXECUTIVE_MAIL_ID,V_DISTRIBUTING_ENTITY_TYPE,
							V_AUTHORISED_ENTITY,V_OUTLET_TYPE,V_USER_ID,NOW());

							SELECT id INTO V_ENTITY_ID FROM master_entities WHERE entity_name=V_ENTITY_NAME;
							SET V_SWAL_TITLE='Registeration Completed';
                            SET V_SWAL_MESSAGE=CONCAT(V_ENTITY_NAME,' has been registered successfully');
                            SET V_SWAL_TYPE="success";

                        END;
					ELSE
						BEGIN
							SELECT CONCAT(ca.firstname,' ',ca.lastname,' - ',username),date_format(me.creation_time,'%d-%m-%Y %H:%i:%s')
                            INTO  V_REGISTERED_BY,V_REGISTRATION_TIME
                            FROM master_entities me
                            INNER JOIN ci_admin ca on ca.admin_id= me.created_by
                            WHERE entity_name = V_ENTITY_NAME;

                            SET V_SWAL_TITLE='Already Registered';
                            SET V_SWAL_MESSAGE=CONCAT(V_ENTITY_NAME,' is already registered by ',V_REGISTERED_BY,' on :',V_REGISTRATION_TIME);
                            SET V_SWAL_TYPE="warning";

                        END;
					END IF;
				END;
			ELSE
				BEGIN
                	SET V_ENTITY_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'entity_id');

					INSERT INTO log_master_entities(entity_id,entity_name,address,city,state,chairman,supervisor,executive,
					chairman_mailid,supervisor_mailid,executive_mailid,authorised_distributor_entity_type_id,authorised_distributor,
					entity_type,isactive)
					SELECT id,entity_name,address,city,state,chairman,supervisor,executive,
					chairman_mailid,supervisor_mailid,executive_mailid,authorised_distributor_entity_type_id,authorised_distributor,
					entity_type,isactive FROM master_entities WHERE id=V_ENTITY_ID;

					SELECT chairman,supervisor,executive
					INTO V_CURRENT_CHAIRMAN,V_CURRENT_SUPERVISOR,V_CURRENT_EXECUTIVE
					FROM master_entities WHERE id =V_ENTITY_ID;

					UPDATE ci_admin
                    SET entity_id=0,admin_role_id=63
                    WHERE admin_id IN (V_CURRENT_CHAIRMAN,V_CURRENT_SUPERVISOR,V_CURRENT_EXECUTIVE);

					UPDATE master_entities
					SET  entity_name=V_ENTITY_NAME, address=V_ADDRESS, city=V_CITY, state=V_STATE,
						 chairman=V_CHAIRMAN, supervisor=V_SUPERVISOR, executive=V_EXECUTIVE,
						 chairman_mailid=V_CHAIRMAN_MAIL_ID, supervisor_mailid=V_SUPERVISOR_MAIL_ID,
						 executive_mailid=V_EXECUTIVE_MAIL_ID,authorised_distributor_entity_type_id=V_DISTRIBUTING_ENTITY_TYPE,authorised_distributor=V_AUTHORISED_ENTITY,
						 entity_type=V_OUTLET_TYPE,modified_by=V_USER_ID,modification_time=now()
					WHERE  id=V_ENTITY_ID;

                    SET V_SWAL_TITLE='Details Updated';
					SET V_SWAL_MESSAGE=CONCAT('Details have been updated into the system ',V_ENTITY_NAME);
					SET V_SWAL_TYPE="success";
				END;
			END IF;
            UPDATE ci_admin SET entity_id=V_ENTITY_ID,admin_role_id=64 WHERE admin_id IN (V_CHAIRMAN,V_SUPERVISOR,V_EXECUTIVE);
		COMMIT;
	 SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE;
	END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_INSERT_UPDATE_HIMVEER_USER_DETAIL(IN V_USERNAME varchar(50),
                                                                                           IN V_PIN varchar(200),
                                                                                           IN V_NAME varchar(200),
                                                                                           IN V_DOB varchar(20),
                                                                                           IN V_EMAIL varchar(100),
                                                                                           IN V_MOBILENO varchar(20),
                                                                                           IN V_TOKEN varchar(5000),
                                                                                           IN V_RANK varchar(75),
                                                                                           IN V_SUB_RANK varchar(75))
BEGIN
    DECLARE V_RANK_ID INT;
    DECLARE V_SUB_RANK_ID INT;
    DECLARE V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT VARCHAR(255);


    START TRANSACTION;
        IF NOT EXISTS (SELECT * FROM master_rank WHERE `rank` = LTRIM(RTRIM(V_RANK))) THEN
            BEGIN
                INSERT INTO master_rank(`rank`) VALUES (V_RANK);
                SET V_RANK_ID = LAST_INSERT_ID();
            END;
        ELSE
            BEGIN
                SELECT id INTO V_RANK_ID FROM master_rank WHERE LTRIM(RTRIM(`rank`)) = RTRIM(LTRIM(V_RANK));
            END;
        END IF;
        select V_RANK,V_RANK_ID;

        IF NOT EXISTS (SELECT * FROM master_sub_rank WHERE sub_rank=LTRIM(RTRIM(V_SUB_RANK))) THEN
            BEGIN
                INSERT INTO master_sub_rank(sub_rank) VALUES(V_SUB_RANK);
                SET V_SUB_RANK_ID = LAST_INSERT_ID();
            END;
        ELSE
            BEGIN
                SELECT id INTO V_SUB_RANK_ID FROM master_sub_rank WHERE sub_rank=V_SUB_RANK;
            END;
        END IF;
        IF NOT EXISTS (SELECT * FROM ci_admin WHERE username=V_USERNAME) THEN
            BEGIN
                INSERT INTO ci_admin(
                                     admin_role_id,
                                     username,
                                     password,
                                     firstname,
                                     email,
                                     mobile_no,
                                     date_of_birth,
                                     last_login,
                                     is_verify,
                                     is_admin,
                                     is_active,
                                     token,
                                     created_at,
                                     rankid,
                                     user_rank,is_hrms_user)
                    VALUES(
                           '5',
                           V_USERNAME,
                           V_PIN,
                           V_NAME,
                           V_EMAIL,
                           V_MOBILENO,
                           V_DOB,
                           NOW(),
                           '1',
                           '1',
                           '1',
                           V_TOKEN,
                           NOW(),
                           V_RANK_ID,
                           V_RANK,'1');
            END;
        ELSE
            BEGIN
                UPDATE ci_admin SET
                    firstname = V_NAME,
                    mobile_no = V_MOBILENO,
                    email = V_EMAIL,
                    date_of_birth = V_DOB,
                    user_rank = V_RANK,
                    rankid = V_RANK_ID,
                    updated_at = NOW(),
                    is_hrms_user = '1'
                WHERE username=V_USERNAME;
            END;
        END IF;

    COMMIT;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_INSERT_UPDATE_LIQUOR_DETAILS(IN P_DATA json)
BEGIN
	DECLARE V_MODE,V_LIQUOR_IMAGE,V_LIQUOR_NAME, V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,V_LIQUOR_DESCRIPTION VARCHAR(225);
    DECLARE V_LIQUOR_TYPE_ID,V_LIQUOR_ML_ID,V_LIQUOR_BOTTLE_SIZE_ID,V_LIQUOR_BRAND_ID,V_ID,V_USER_ID INT;
    DECLARE V_BREWEY_ID INT;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;

        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
        VALUES('ADD/EDIT PRODUCT MASTER','SP_INSERT_UPDATE_PRODUCT_DETAILS',P_DATA,@P2,@P1);

        SET V_SWAL_TITLE='Error!',V_SWAL_MESSAGE='Something Went wrong, Please Contact Ats team',V_SWAL_TYPE='error';

        SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,@P1,@P2;
    END;

    START TRANSACTION;
		SET V_SWAL_TITLE='SUCCESS',V_SWAL_TYPE='success';

        SET V_LIQUOR_IMAGE=REPLACE(FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_image'),'\\','');

        SET V_LIQUOR_NAME =FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_name');

        SET V_LIQUOR_TYPE_ID =FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_type_id');

        SET V_LIQUOR_BRAND_ID =FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_brand_id');

        SET V_LIQUOR_DESCRIPTION =FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_description');

		SET V_LIQUOR_ML_ID =FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_ml_id');

        SET V_LIQUOR_BOTTLE_SIZE_ID =FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_bottle_size_id');

        SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');

        SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');

        SET V_BREWEY_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'brewery_id');

        IF(V_MODE='A')THEN
			BEGIN
				IF NOT EXISTS(SELECT id FROM liquor_description WHERE liquor_description=V_LIQUOR_DESCRIPTION AND liquor_brand_id=V_LIQUOR_BRAND_ID AND liquor_bottle_size_id=V_LIQUOR_BOTTLE_SIZE_ID AND liquor_type_id=V_LIQUOR_TYPE_ID AND liquor_ml_id=V_LIQUOR_ML_ID)THEN
                BEGIN
					SET V_SWAL_MESSAGE='Product added successfully';
                    INSERT INTO liquor_description(
									liquor_description,liquor_image,liquor_brand_id,liquor_bottle_size_id,liquor_type_id,
										liquor_ml_id,creation_time,created_by,brewery_id
                                    )
								VALUES(
									V_LIQUOR_DESCRIPTION,V_LIQUOR_IMAGE,V_LIQUOR_BRAND_ID,V_LIQUOR_BOTTLE_SIZE_ID,V_LIQUOR_TYPE_ID,
                                            V_LIQUOR_ML_ID,NOW(),V_USER_ID,V_BREWEY_ID
									);
					END;
                ELSE
					BEGIN
						SET V_SWAL_MESSAGE='Product is already registered',V_SWAL_TYPE='warning';
                    END;
                END IF;

			END;
        ELSE
			BEGIN
				SET V_SWAL_MESSAGE='Product details updated successfully';
                set V_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'id');
                UPDATE liquor_description
                SET liquor_description= V_LIQUOR_DESCRIPTION,
					liquor_image=V_LIQUOR_IMAGE,
					liquor_brand_id=V_LIQUOR_BRAND_ID,
					liquor_bottle_size_id=V_LIQUOR_BOTTLE_SIZE_ID,
					liquor_type_id=V_LIQUOR_TYPE_ID,
					liquor_ml_id=V_LIQUOR_ML_ID,
					creation_time=NOW(),
					created_by=V_USER_ID,
					brewery_id=V_BREWEY_ID
                WHERE id=V_ID;
			END;
        END IF;
    COMMIT;
        SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_INSERT_UPDATE_LIQUOR_MAPPING_DETAILS(IN P_DATA json)
BEGIN
	DECLARE V_MODE,V_LIQUOR_TYPE,V_LIQUOR_NAME,V_ENTITY_TYPE,  V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE VARCHAR(225);
    DECLARE V_LIQUOR_TYPE_ID,V_ID,V_USER_ID,V_MOQ,V_ML,V_ENTITY_ID,V_STOCK_SALE_ID,V_OPENING_QTY,V_PRE_DISPLAY_AVAILABLE_QUANTITY,V_PRE_PHYSICAL_AVAILABLE_QUANTITY,V_LIQUOR_BALANCE,V_SALE_QTY,V_UNIT_PROFIT,V_LIQUOR_DESCRIPTION_ID,V_QUANTITY_DIFF,V_DISPLAY_AVAILABLE_QUANTITY,V_PHYSICAL_AVAILABLE_QUANTITY,V_AVAILABLE_QUANTITY,V_REORDER_LEVEL INT;
    DECLARE V_SELL_PRICE,V_PURCHASE_PRICE,V_TOTAL_SELL_PRICE,V_TOTAL_PURCHASE_PRICE,V_TOTAL_PROFIT,V_BASE_PRICE FLOAT(10,2);

    /*DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;

		CALL SP_LOG_APPLICATION_ERROR('ADD/EDIT PRODUCT MAPPING','SP_INSERT_UPDATE_LIQUOR_MAPPING_DETAILS'
            ,P_DATA,@P2,@P1);

         SET V_SWAL_TITLE='Error!',V_SWAL_MESSAGE='Something Went wrong, Please Contact Ats team',V_SWAL_TYPE='error';

        SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,@P1,@P2;
    END;
    */
    START TRANSACTION;

		SET V_SWAL_TITLE='SUCCESS',V_SWAL_TYPE='success';

		SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
        SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');
        SET V_AVAILABLE_QUANTITY=FN_EXTRACT_JSON_DATA(P_DATA,0,'available_quantity');
        SET V_REORDER_LEVEL=FN_EXTRACT_JSON_DATA(P_DATA,0,'reorder_level');
		SET V_LIQUOR_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_type');
		SET V_LIQUOR_NAME =FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_brand');
		SET V_LIQUOR_DESCRIPTION_ID=FN_STRING_SPLIT(V_LIQUOR_NAME,'#',1);
		SET V_ENTITY_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'entity_type');
		SET V_ENTITY_ID =FN_EXTRACT_JSON_DATA(P_DATA,0,'entity');
		SET V_ML =FN_EXTRACT_JSON_DATA(P_DATA,0,'select_ml');
		SET V_MOQ=FN_EXTRACT_JSON_DATA(P_DATA,0,'moq');
		SET V_SELL_PRICE=FN_EXTRACT_JSON_DATA(P_DATA,0,'sell_price');
		SET V_PURCHASE_PRICE=FN_EXTRACT_JSON_DATA(P_DATA,0,'purchase_price');
        -- SET V_BASE_PRICE=FN_EXTRACT_JSON_DATA(P_DATA,0,'base_price');

        CALL SP_LOG_USER_ACTIVITY(V_USER_ID,'ADD STOCK',@V_MODE,P_DATA);

        IF(V_MODE='U')THEN
			BEGIN
				SET V_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'id');

				SELECT entity_id,liquor_description_id,selling_price,purchase_price INTO V_ENTITY_ID,V_LIQUOR_DESCRIPTION_ID,V_SELL_PRICE,V_PURCHASE_PRICE
                FROM liquor_entity_mapping WHERE id =V_ID;
            END;
        END IF;


        IF NOT EXISTS(SELECT id FROM liquor_entity_mapping WHERE entity_id=V_ENTITY_ID AND liquor_description_id=V_LIQUOR_DESCRIPTION_ID AND selling_price=V_SELL_PRICE AND purchase_price=V_PURCHASE_PRICE)THEN
			BEGIN

                INSERT INTO liquor_entity_mapping(liquor_description_id,liquor_type_id,ml_id,entity_id,entity_type,selling_price,purchase_price,available_quantity,actual_available_quantity,minimum_order_quantity,created_type,creation_time,reorder_level)
                VALUES(V_LIQUOR_DESCRIPTION_ID,V_LIQUOR_TYPE,V_ML,V_ENTITY_ID,V_ENTITY_TYPE,V_SELL_PRICE,V_PURCHASE_PRICE,V_AVAILABLE_QUANTITY,V_AVAILABLE_QUANTITY,V_MOQ,V_USER_ID,NOW(),V_REORDER_LEVEL);

                SET V_SWAL_MESSAGE='Product added successfully';

			END;
        ELSE
			BEGIN
				SET V_SWAL_TITLE='Warning';
				SET V_SWAL_MESSAGE='Kindly Add stock through list as it already exist in the list with same purchase and selling price';
                SET V_SWAL_TYPE='warning';
			END;
        END IF;

    COMMIT;
        SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,V_LIQUOR_TYPE,V_LIQUOR_NAME,V_ENTITY_ID,V_REORDER_LEVEL;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_INSERT_UPDATE_PRODUCT_DETAILS(IN P_DATA json)
BEGIN
	DECLARE V_MODE,V_LIQUOR_IMAGE,V_LIQUOR_NAME, V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE VARCHAR(225);
    DECLARE V_LIQUOR_TYPE_ID,V_ID,V_USER_ID INT;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;

        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
        VALUES('ADD/EDIT PRODUCT MASTER','SP_INSERT_UPDATE_PRODUCT_DETAILS',P_DATA,@P2,@P1);

        SET V_SWAL_TITLE='Error!',V_SWAL_MESSAGE='Something Went wrong, Please Contact Ats team',V_SWAL_TYPE='error';

        SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,@P1,@P2;
    END;

    START TRANSACTION;
		SET V_SWAL_TITLE='SUCCESS',V_SWAL_TYPE='success';

        SET V_LIQUOR_IMAGE=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_image_h');
		SET V_LIQUOR_NAME =FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_name');
		SET V_LIQUOR_TYPE_ID =FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_type');
        set V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
        SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');

        IF(V_MODE='A')THEN
			BEGIN
				SET V_SWAL_MESSAGE='Product added successfully';

                INSERT INTO master_liquor(liquor_name,liquor_image,liquor_type,creation_time,created_by)
                VALUES(V_LIQUOR_NAME,V_LIQUOR_IMAGE,V_LIQUOR_TYPE_ID,NOW(),V_USER_ID);

                SELECT id INTO V_ID FROM master_liquor WHERE liquor_name=V_LIQUOR_NAME;

                INSERT INTO log_master_liquor(master_liquor_id,liquor_name,liquor_image,liquor_type,creation_time,created_by)
                VALUES(V_ID,V_LIQUOR_NAME,V_LIQUOR_IMAGE,V_LIQUOR_TYPE_ID,NOW(),V_USER_ID);

			END;
        ELSE
			BEGIN
				SET V_SWAL_MESSAGE='Product details updated successfully';

                INSERT INTO log_master_liquor(master_liquor_id,liquor_name,liquor_image,liquor_type)
				SELECT id,liquor_name,liquor_image,liquor_type from master_liquor where id=V_ID;

                UPDATE master_liquor
                SET liquor_name=V_LIQUOR_NAME,
					liquor_image=V_LIQUOR_IMAGE,
					liquor_type=V_LIQUOR_TYPE_ID,
                    modification_time=NOW(),
                    modified_by=V_USER_ID WHERE id=V_ID;

                UPDATE log_master_liquor
                SET creation_time=now(),created_by=V_USER_ID
                WHERE master_liquor_id=V_ID
                ORDER BY id DESC LIMIT 1;

			END;
        END IF;
    COMMIT;
        SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_LOG_APPLICATION_ERROR(IN P_PAGE_NAME varchar(100),
                                                                               IN P_SP_NAME varchar(100),
                                                                               IN P_DATA text, IN P_ERROR_MESSAGE text,
                                                                               IN P_SQL_STATE varchar(500))
BEGIN
    INSERT INTO sp_error_log_data (page_name,sp_name,data_passed,error_message,returne_sql_state)
        values(
               P_PAGE_NAME,P_SP_NAME,P_DATA,P_ERROR_MESSAGE,P_SQL_STATE
              );
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_LOG_USER_ACTIVITY(IN P_USERID varchar(10),
                                                                           IN P_MODULE varchar(50),
                                                                           IN P_MODE varchar(1), IN P_DATA text)
BEGIN
    INSERT INTO ci_application_log (userid,module,mode,data,activity_date)
        values(
               P_USERID,P_MODULE,P_MODE,P_DATA,NOW()
              );
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_NEW_AVAILABLE_STOCK(IN P_DATA json)
BEGIN

	DECLARE V_SWAL_TYPE, V_SWAL_TITLE, V_SWAL_TEXT, V_INVOICE_NO VARCHAR(55);
    DECLARE V_STOCK_DATA JSON;
    DECLARE V_USER_ID BIGINT;
    DECLARE V_STOCK_DATA_COUNT, V1, V_LIQUOR_ENTITY_ID, V_SELLING_PRICE, V_AVAILABLE_STOCK, V_NEW_STOCK, V_TOTAL, V_ENTITY_ID, V_AVAILABLE_QUANTITY, V_ACTUAL_AVAIABLE_QUANTITY INT DEFAULT 0;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
        GET DIAGNOSTICS CONDITION 1
        @P1 = MESSAGE_TEXT, @P2 = RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name, sp_name, data_passed,  error_message, returne_sql_state)
        VALUES('NEW_AVAILABLE_STOCK', 'SP_NEW_AVAILABLE_STOCK', P_DATA, @P1, @P2);
        SET V_SWAL_TYPE = "warning", V_SWAL_TITLE = "New stock could not be added", V_SWAL_TEXT = "";
        SELECT V_SWAL_TYPE, V_SWAL_TITLE, V_SWAL_TEXT, @P1, @P2;
	END;

	START TRANSACTION;
		SET V_USER_ID = FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
        SET V_ENTITY_ID = FN_EXTRACT_JSON_DATA(P_DATA,0,'entity_id');
		SET V_INVOICE_NO = FN_EXTRACT_JSON_DATA(P_DATA,0,'invoice_no');
        SET V_STOCK_DATA = FN_EXTRACT_JSON_DATA(P_DATA,0,'stock_data');

        SET V_STOCK_DATA_COUNT=JSON_LENGTH(V_STOCK_DATA);
        SET V1=0;
        WHILE(V1<V_STOCK_DATA_COUNT) DO
			BEGIN
				SET V_LIQUOR_ENTITY_ID = FN_EXTRACT_JSON_DATA(V_STOCK_DATA, V1, 'liquor_entity_id');
                SET V_SELLING_PRICE = FN_EXTRACT_JSON_DATA(V_STOCK_DATA, V1, 'selling_price');
                SET V_AVAILABLE_STOCK = FN_EXTRACT_JSON_DATA(V_STOCK_DATA, V1, 'available_stock');
                SET V_NEW_STOCK = FN_EXTRACT_JSON_DATA(V_STOCK_DATA, V1, 'new_stock');
                SET V_TOTAL = FN_EXTRACT_JSON_DATA(V_STOCK_DATA, V1, 'total');

                INSERT INTO new_available_stock (entity_id, liquor_entity_id, selling_price, invoice_no, new_stock, available_stock, total, created_by, creation_time)
                values (V_ENTITY_ID, V_LIQUOR_ENTITY_ID, V_SELLING_PRICE, V_INVOICE_NO, V_NEW_STOCK, V_AVAILABLE_STOCK, V_TOTAL, V_USER_ID, NOW());

                INSERT INTO log_liquor_entity_mapping(
				`liquor_entity_id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
				`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
				`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,
				`actual_available_quantity`,`city_id`,`state_id`,`creation_time`,`created_type`,`reorder_level`
				)SELECT `id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
				`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
				`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,
				`actual_available_quantity`,`city_id`,`state_id`,(SELECT NOW()),(SELECT V_USER_ID),`reorder_level`
				FROM liquor_entity_mapping WHERE id=V_LIQUOR_ENTITY_ID;

                SELECT available_quantity, actual_available_quantity
                INTO V_AVAILABLE_QUANTITY, V_ACTUAL_AVAIABLE_QUANTITY
                FROM liquor_entity_mapping WHERE id=V_LIQUOR_ENTITY_ID;

                SET V_AVAILABLE_QUANTITY = V_AVAILABLE_QUANTITY + V_NEW_STOCK ;
                SET V_ACTUAL_AVAIABLE_QUANTITY = V_ACTUAL_AVAIABLE_QUANTITY + V_NEW_STOCK ;

                UPDATE liquor_entity_mapping
                SET available_quantity = V_AVAILABLE_QUANTITY, actual_available_quantity = V_ACTUAL_AVAIABLE_QUANTITY,
                modified_by = V_USER_ID, modification_time = NOW()
                WHERE id = V_LIQUOR_ENTITY_ID;

                SET V1=V1+1;
            END;
		END WHILE;

        SET V_SWAL_TYPE = "SUCCESS", V_SWAL_TITLE = "New stock has been added sucessfully.", V_SWAL_TEXT = "";





    COMMIT;
	SELECT V_USER_ID, V_INVOICE_NO, V_STOCK_DATA, V_STOCK_DATA_COUNT, V_SWAL_TYPE, V_SWAL_TITLE, V_SWAL_TEXT,V_AVAILABLE_QUANTITY, V_ACTUAL_AVAIABLE_QUANTITY,V_NEW_STOCK;

END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_NEW_CART_CHECK_OUT(IN P_DATA json)
BEGIN
     DECLARE V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_LIQUOR_DETAILS,V_MODE VARCHAR(500) DEFAULT '';
    DECLARE V_PAGE_MODE,V_ORDER_CODE,V_CART_TYPE VARCHAR(100);
    DECLARE V_CART_DATA JSON;
    DECLARE V_LIQUOR_KEY,V_CART_AVAIBLE,V_ORDERED_TO_ENTITY_ID,V_NOT_AVAILABLE,V_CAN_PLACE_ORDER,V_ORDER_FROM_ENITY_ID,V_ORDER_FROM_ENTITY_TYPE,V_ALLOWED_QUOTA,
			V_REMOVE_COUNT,V_LIQUOR_PER_BOTTLE,V_QUANTITY_UPDATED,V_CART_ID,V_USER_ID,V_REMOVED,V_LIQUOR_DESCRIPTION_ID,V_LIQUOR_ENTITY_ID,V_CART_DATA_COUNT,V_COUNTER,
			V_QUANTITY,V_LIQUOR_COUNT,V_QUOTA,V_USED_QUOTA,V_CURRENT_AVAILABLE_QUANTITY,V_NEW_QUANTITY,V_LIQUOR_ID,V_LIQUOR_ENTITY_COUNT INT DEFAULT 0;
    DECLARE V_TOTAL_COST_BOTTLES, V_UNIT_COST_LOT_SIZE FLOAT(10,2);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
                ROLLBACK;
        GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('CART','SP_NEW_CART_CHECK_OUT',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Order could not be placed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;
		INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('CART','SP_NEW_CART_CHECK_OUT',P_DATA,@P1,@P2);
        SET V_CART_DATA=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_data');
        SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');
        SET V_PAGE_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'page_mode');
        SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
        SET V_LIQUOR_COUNT=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_count');
        SET V_LIQUOR_PER_BOTTLE=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_per_bottle');
        SET V_CART_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_type');
        SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');
        SET V_CART_DATA_COUNT=JSON_LENGTH(V_CART_DATA);
        SET V_COUNTER=0;
        SET V_REMOVE_COUNT=0;
        SET V_QUANTITY_UPDATED=0;
        SET V_LIQUOR_ENTITY_COUNT=0;
        SET V_NOT_AVAILABLE=0;

        SELECT if(V_CART_TYPE="consumer",lem.selling_price,lem.minimum_order_quantity) INTO V_UNIT_COST_LOT_SIZE
		FROM liquor_entity_mapping lem where id=V_LIQUOR_ENTITY_ID;

		SET V_TOTAL_COST_BOTTLES =V_UNIT_COST_LOT_SIZE*V_QUANTITY;


        SELECT lrq.quota,ca.entity_id
        INTO V_QUOTA,V_ORDER_FROM_ENITY_ID  FROM
        ci_admin ca
        INNER JOIN liquor_rank_quota_mapping lrq ON lrq.rankid=ca.rankid
        WHERE admin_id=V_USER_ID;

        SELECT IFNULL(SUM(luq.liquor_count),0) INTO V_USED_QUOTA FROM
        liquor_user_used_quota luq
        WHERE userid=V_USER_ID AND MONTH(luq.insert_time)= MONTH(NOW()) AND YEAR(luq.insert_time)=YEAR(NOW()) AND luq.order_status!=3 and luq.is_beer=0;

        SET V_USED_QUOTA=V_USED_QUOTA+V_LIQUOR_PER_BOTTLE;

        SET V_CAN_PLACE_ORDER=0;

		SELECT ordered_to_entity_id,order_code
		INTO V_ORDERED_TO_ENTITY_ID,V_ORDER_CODE
		FROM cart_details WHERE id=V_CART_ID;

		INSERT INTO order_code_user_details(cart_id,userid,order_code,insert_mode,insert_time,call_mode)
		VALUES(V_CART_ID,V_USER_ID,V_ORDER_CODE,'New Cart Checkout',NOW(),V_PAGE_MODE);

        IF(V_CART_TYPE='consumer')THEN
            BEGIN
                IF(V_QUOTA>V_USED_QUOTA || V_QUOTA=V_USED_QUOTA )THEN
                    BEGIN
                        SET V_CAN_PLACE_ORDER=1;

						SET V_ORDER_FROM_ENITY_ID=0;

                        SELECT id INTO V_ORDER_FROM_ENTITY_TYPE FROM master_entity_type WHERE entity_type='consumer';

                        SELECT ORDER_CODE INTO V_ORDER_CODE FROM cart_details WHERE id=V_CART_ID;

                        INSERT INTO liquor_user_used_quota(userid,liquor_count,order_code,order_status,insert_time,created_by,isactive)
                        VALUES(
                            V_USER_ID,V_LIQUOR_PER_BOTTLE,V_ORDER_CODE,1,NOW(),V_USER_ID,1
                        );

                        SET V_SWAL_TEXT=CONCAT('Kindly produce the given code at canteen to get the liquors order code: ',V_ORDER_CODE);
                    END;
               END IF;
            END;
        ELSE
            BEGIN
                SELECT entity_type
                INTO V_ORDER_FROM_ENTITY_TYPE
                FROM master_entities WHERE id=V_ORDER_FROM_ENITY_ID;

				SET V_SWAL_TEXT=CONCAT('Kindly use the given code during recieving of the liquors order code: ',V_ORDER_CODE);

                SET V_CAN_PLACE_ORDER=1;
            END;
        END IF;

        IF((V_CART_TYPE!='consumer') OR (V_CART_TYPE='consumer' AND V_CAN_PLACE_ORDER=1) )THEN
            BEGIN
				SET V_SWAL_TYPE='success',V_SWAL_TITLE='Checkot';
                IF(V_PAGE_MODE='shopping_cart')THEN
                    BEGIN
                        SET V_COUNTER=0;
                        WHILE (V_CART_DATA_COUNT>V_COUNTER)DO
                            BEGIN
                                SET V_TOTAL_COST_BOTTLES=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'total_cost_quantity');

                                SET V_UNIT_COST_LOT_SIZE =FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'unit_cost_quantity');

                                SET V_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'quantity');

                                SET V_REMOVED=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'remove');

								SET V_LIQUOR_ENTITY_ID=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'liquor_id');

                                IF(V_REMOVED=0)THEN
                                    BEGIN
                                        IF NOT EXISTS(SELECT id FROM cart_liquor WHERE cart_id= V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID AND quantity=V_QUANTITY)THEN
                                            BEGIN

                                                SET V_QUANTITY_UPDATED=V_QUANTITY_UPDATED+1;

                                                UPDATE cart_liquor SET is_liquor_removed=1,modified_by=V_USER_ID,modification_time=NOW()
                                                        WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                                                SET V_TOTAL_COST_BOTTLES= V_UNIT_COST_LOT_SIZE * V_QUANTITY;

                                                INSERT INTO cart_liquor(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,created_by,creation_time)
                                                values(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_USER_ID,NOW());
                                            END;
                                        END IF;
                                        IF(V_CART_TYPE='consumer')THEN
                                            BEGIN
											   SELECT available_quantity INTO V_CURRENT_AVAILABLE_QUANTITY
											   FROM liquor_entity_mapping WHERE id=V_LIQUOR_ENTITY_ID;

											   IF NOT EXISTS(SELECT id FROM liquor_entity_mapping where id=V_LIQUOR_ENTITY_ID and ((available_quantity > V_QUANTITY)|| (available_quantity = V_QUANTITY)))THEN
													BEGIN
														IF(V_NOT_AVAILABLE=0)THEN
															BEGIN
																SET V_SWAL_TEXT='';
															END;
														END IF;
														SET V_NOT_AVAILABLE=1;



														SELECT liquor_description_id
														INTO V_LIQUOR_DESCRIPTION_ID
														FROM liquor_entity_mapping
														WHERE id=V_LIQUOR_ENTITY_ID;

														SELECT CONCAT(brand,' ',liquor_description,' ',liquor_type,' ',liquor_ml,' ml') INTO V_LIQUOR_DETAILS FROM liquor_details WHERE liquor_description_id=V_LIQUOR_DESCRIPTION_ID;
														SET V_NOT_AVAILABLE=1;
														SET V_SWAL_TYPE='warning';
														SET V_SWAL_TEXT=CONCAT(V_SWAL_TEXT,' Only ',V_CURRENT_AVAILABLE_QUANTITY,' bottles are avaible for ',V_LIQUOR_DETAILS, ' Kindly reduce the quantity or remove the liquor');
													END;
												ELSE
													BEGIN


														SET V_NEW_QUANTITY=V_CURRENT_AVAILABLE_QUANTITY-V_QUANTITY;



														UPDATE liquor_entity_mapping SET available_quantity= V_NEW_QUANTITY WHERE  id=V_LIQUOR_ENTITY_ID;


														INSERT INTO log_liquor_entity_mapping(
														`liquor_entity_id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
														`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
														`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`previous_available_quantity`,`available_quantity`,
														`previous_actual_available_quantity`,`actual_available_quantity`,`city_id`,`state_id`,`creation_time`,`created_type`,`reorder_level`,`updated_from`,`cart_id`
														)SELECT  `id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
														`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
														`selling_price`,`minimun_order_type`,`single_piece_in_lot`,(SELECT V_CURRENT_AVAILABLE_QUANTITY),`available_quantity`,
														`actual_available_quantity`,`actual_available_quantity`,`city_id`,`state_id`,(SELECT NOW()),(SELECT V_USER_ID),`reorder_level`,'NEW_CART_CHECK OUT',V_CART_ID
														FROM  liquor_entity_mapping  WHERE id=V_LIQUOR_ENTITY_ID;
													END;
												END IF;
                                            END;
                                        END IF;
                                    END;
                                ELSE
                                    BEGIN
                                        SET V_REMOVE_COUNT=V_REMOVE_COUNT+1;

                                        UPDATE cart_liquor SET
                                            is_liquor_removed=1,modified_by=V_USER_ID,modification_time=NOW()
                                        WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                                    END;
                                END IF;
                                SET V_COUNTER=V_COUNTER+1;
                            END;
                        END WHILE;
                    END;
                END IF;

                INSERT INTO log_cart_details(cart_id,order_code,liquor_count,ordered_to_entity_id,ordered_to_entity_type,order_from_id,order_from_entity_id,order_from_entity_type,is_active,is_order_placed,is_order_delivered,is_order_cancel,order_by_userid,order_time,order_mode)
                SELECT id,order_code,liquor_count,ordered_to_entity_id,ordered_to_entity_type,order_from_id,order_from_entity_id,order_from_entity_type,is_active,is_order_placed,is_order_delivered,is_order_cancel,(SELECT V_USER_ID),(SELECT NOW()),order_mode from cart_details where id=V_CART_ID;

                UPDATE cart_details SET  liquor_count=V_LIQUOR_COUNT,order_from_entity_id=V_ORDER_FROM_ENITY_ID,order_from_entity_type=V_ORDER_FROM_ENTITY_TYPE
                ,is_order_placed=1, order_time=now(),is_order_delivered=0
                WHERE id= V_CART_ID;

                INSERT INTO order_details
                (cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,dispatch_quantity,dispatch_cost_lot_size,dispatch_total_cost_bottles,recevied_quantity,
                recevied_cost_lot_size,recevied_total_cost_bottles,order_time,order_by,order_process
                )
                SELECT cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,quantity,unit_cost_lot_size,total_cost_bottles,quantity,
                unit_cost_lot_size,total_cost_bottles, (SELECT NOW()),(SELECT V_USER_ID),(SELECT '1')
                from cart_liquor
                WHERE cart_id=V_CART_ID AND is_liquor_removed=0;

            END;
        ELSE
            BEGIN
                IF(V_CAN_PLACE_ORDER=0)THEN
                    BEGIN
                        SET V_ALLOWED_QUOTA=V_USED_QUOTA-V_QUOTA;
                        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Maximum allowed Quota Excedded',V_SWAL_TEXT=CONCAT('Kindly remove ',V_ALLOWED_QUOTA,' bottles to successfully place the order');
                    END;
                END IF;
           END;
        END IF;

		IF(V_NOT_AVAILABLE=0)THEN
			BEGIN
				COMMIT;
			END;
		ELSE
			BEGIN
				ROLLBACK;
			END;
		END IF;

    SELECT V_NOT_AVAILABLE,V_LIQUOR_ID,V_ORDERED_TO_ENTITY_ID,V_UNIT_COST_LOT_SIZE,V_QUANTITY,V_CART_TYPE,V_COUNTER,V_CAN_PLACE_ORDER,V_LIQUOR_ENTITY_ID,V_SWAL_TYPE,V_USED_QUOTA,V_ORDER_CODE,V_PAGE_MODE,V_SWAL_TITLE,V_SWAL_TEXT,V_ORDER_FROM_ENITY_ID,V_ORDER_FROM_ENTITY_TYPE,V_CART_ID,V_USER_ID,V_QUANTITY_UPDATED,V_REMOVE_COUNT,V_LIQUOR_PER_BOTTLE,V_LIQUOR_KEY,V_CAN_PLACE_ORDER;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_NEW_CART_CHECK_OUT_BEER(IN P_DATA json)
BEGIN
    DECLARE V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_LIQUOR_DETAILS,V_MODE VARCHAR(500) DEFAULT '';
    DECLARE V_PAGE_MODE,V_ORDER_CODE,V_CART_TYPE VARCHAR(100);
    DECLARE V_CART_DATA JSON;
    DECLARE V_LIQUOR_KEY,V_CART_AVAIBLE,V_ORDERED_TO_ENTITY_ID,V_NOT_AVAILABLE,V_CAN_PLACE_ORDER,V_ORDER_FROM_ENITY_ID,
			V_ORDER_FROM_ENTITY_TYPE,V_ALLOWED_QUOTA,V_REMOVE_COUNT,V_LIQUOR_PER_BOTTLE,V_QUANTITY_UPDATED,V_CART_ID,
            V_USER_ID,V_REMOVED,V_LIQUOR_DESCRIPTION_ID,V_LIQUOR_ENTITY_ID,V_CART_DATA_COUNT,V_COUNTER ,V_QUANTITY,
            V_LIQUOR_COUNT,V_QUOTA,V_USED_QUOTA,V_CURRENT_AVAILABLE_QUANTITY,V_NEW_QUANTITY,V_LIQUOR_ID,
            V_LIQUOR_ENTITY_COUNT,V_SELECTED_LIQUOR_QUANTITY,V_BEER_QUANTITY INT DEFAULT 0;
    DECLARE V_TOTAL_COST_BOTTLES, V_UNIT_COST_LOT_SIZE FLOAT(10,2);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
        GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('CART','SP_CART_CHECK_OUT',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Order could not be placed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;
    INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('CART','SP_CART_CHECK_OUT',P_DATA,@P1,@P2);
        SET V_CART_DATA=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_data');
        SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');
        SET V_PAGE_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'page_mode');
        SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
        SET V_LIQUOR_COUNT=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_count');
        SET V_LIQUOR_PER_BOTTLE=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_per_bottle');
        SET V_CART_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_type');
        SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');
        SET V_CART_DATA_COUNT=  JSON_LENGTH(V_CART_DATA);
        SET V_COUNTER=0;
        SET V_REMOVE_COUNT=0;
        SET V_QUANTITY_UPDATED=0;
        SET V_LIQUOR_ENTITY_COUNT=0;
		SET V_NOT_AVAILABLE=0;



        SELECT (lrq.quota*4),ca.entity_id
        INTO V_QUOTA,V_ORDER_FROM_ENITY_ID  FROM
        ci_admin ca
        INNER JOIN liquor_rank_quota_mapping lrq ON lrq.rankid=ca.rankid
        WHERE admin_id=V_USER_ID;

        SELECT IFNULL(SUM(luq.liquor_count),0) INTO V_USED_QUOTA FROM
        liquor_user_used_quota luq
        WHERE userid=V_USER_ID AND MONTH(luq.insert_time) BETWEEN MONTH(NOW())-1 AND MONTH(NOW()) AND YEAR(luq.insert_time)=YEAR(NOW()) AND luq.order_status!=3 and luq.is_beer=0;

        SET V_USED_QUOTA=V_USED_QUOTA+V_LIQUOR_PER_BOTTLE;

        SET V_CAN_PLACE_ORDER=0;

		SELECT ordered_to_entity_id,order_code
		INTO V_ORDERED_TO_ENTITY_ID,V_ORDER_CODE
		FROM cart_details WHERE id=V_CART_ID;


        IF(V_CART_TYPE='consumer')THEN
            BEGIN
                IF(V_QUOTA>V_USED_QUOTA || V_QUOTA=V_USED_QUOTA )THEN
                    BEGIN
                        SET V_CAN_PLACE_ORDER=1;

						SET V_ORDER_FROM_ENITY_ID=0;

                        SELECT id INTO V_ORDER_FROM_ENTITY_TYPE FROM master_entity_type WHERE entity_type='consumer';

                        SELECT ORDER_CODE INTO V_ORDER_CODE  FROM cart_details WHERE  id=V_CART_ID;

                        INSERT INTO liquor_user_used_quota(userid,liquor_count,order_code,order_status,insert_time,created_by,isactive)
                        VALUES(
                            V_USER_ID,V_LIQUOR_PER_BOTTLE,V_ORDER_CODE,1,NOW(),V_USER_ID,1
                        );

                        SET V_SWAL_TEXT=CONCAT('Kindly produce the given code at canteen to get the liquors order code: ',V_ORDER_CODE);
                    END;
               END IF;
            END;
        ELSE
            BEGIN
                SELECT entity_type
                INTO V_ORDER_FROM_ENTITY_TYPE
                FROM master_entities WHERE id=V_ORDER_FROM_ENITY_ID;

				SET V_SWAL_TEXT=CONCAT('Kindly use the given code during recieving of the liquors order code: ',V_ORDER_CODE);

                SET V_CAN_PLACE_ORDER=1;
            END;
        END IF;

        IF((V_CART_TYPE!='consumer') OR (V_CART_TYPE='consumer' AND V_CAN_PLACE_ORDER=1) )THEN
            BEGIN
				SET V_SWAL_TYPE='success',V_SWAL_TITLE='Checkot';
                IF(V_PAGE_MODE='shopping_cart')THEN
                    BEGIN
                        SET V_COUNTER=0;
                        WHILE (V_CART_DATA_COUNT>V_COUNTER)DO
                            BEGIN
                                SET  V_TOTAL_COST_BOTTLES=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'total_cost_quantity');

                                SET  V_UNIT_COST_LOT_SIZE =FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'unit_cost_quantity');

                                SET V_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'quantity');

                                SET V_REMOVED=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'remove');

								SET V_LIQUOR_ENTITY_ID=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'liquor_id');

                                IF(V_REMOVED=0)THEN
                                    BEGIN
                                        IF NOT EXISTS(SELECT id FROM cart_liquor WHERE cart_id= V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID AND quantity=V_QUANTITY)THEN
                                            BEGIN

                                                SET V_QUANTITY_UPDATED=V_QUANTITY_UPDATED+1;

                                                UPDATE cart_liquor SET is_liquor_removed=1,modified_by=V_USER_ID,modification_time=NOW()
                                                        WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                                                SET	 V_TOTAL_COST_BOTTLES=	V_UNIT_COST_LOT_SIZE * V_QUANTITY;

                                                INSERT INTO cart_liquor(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,created_by,creation_time)
                                                        values(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_USER_ID,NOW());
                                            END;
                                        END IF;
                                        IF(V_CART_TYPE='consumer')THEN
                                            BEGIN
												SELECT available_quantity INTO V_CURRENT_AVAILABLE_QUANTITY
													FROM liquor_entity_mapping WHERE id=V_LIQUOR_ENTITY_ID;

												IF NOT EXISTS(SELECT id FROM liquor_entity_mapping where id=V_LIQUOR_ENTITY_ID and ((available_quantity > V_QUANTITY)|| (available_quantity = V_QUANTITY)))THEN
													BEGIN
														IF(V_NOT_AVAILABLE=0)THEN
															BEGIN
																SET V_SWAL_TEXT='';
                                                            END;
                                                        END IF;
                                                        SET V_NOT_AVAILABLE=1;



                                                        SELECT liquor_description_id
														INTO V_LIQUOR_DESCRIPTION_ID
														FROM liquor_entity_mapping
														WHERE id=V_LIQUOR_ENTITY_ID;

														SELECT CONCAT(brand,' ',liquor_description,' ',liquor_type,' ',liquor_ml,' ml') INTO V_LIQUOR_DETAILS FROM liquor_details WHERE liquor_description_id=V_LIQUOR_DESCRIPTION_ID;
														SET V_NOT_AVAILABLE=1;
														SET V_SWAL_TYPE='warning';
														SET V_SWAL_TEXT=CONCAT(V_SWAL_TEXT,' Only ',V_CURRENT_AVAILABLE_QUANTITY,' bottles are avaible for ',V_LIQUOR_DETAILS, ' Kindly reduce the quantity or remove the liquor');
													END;
												ELSE
													BEGIN


														SET V_NEW_QUANTITY=V_CURRENT_AVAILABLE_QUANTITY-V_QUANTITY;



														UPDATE liquor_entity_mapping SET available_quantity= V_NEW_QUANTITY WHERE  id=V_LIQUOR_ENTITY_ID;


														 INSERT INTO log_liquor_entity_mapping(
														`liquor_entity_id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
														`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
														`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`previous_available_quantity`,`available_quantity`,
				`previous_actual_available_quantity`,`actual_available_quantity`,`city_id`,`state_id`,`creation_time`,`created_type`,`reorder_level`,`updated_from`,`cart_id`
														)SELECT  `id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
														`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
														`selling_price`,`minimun_order_type`,`single_piece_in_lot`,(SELECT V_CURRENT_AVAILABLE_QUANTITY),`available_quantity`,
														`actual_available_quantity`,`actual_available_quantity`,`city_id`,`state_id`,(SELECT NOW()),(SELECT V_USER_ID),`reorder_level`,'NEW_CART_CHECK OUT',V_CART_ID
														FROM  liquor_entity_mapping  WHERE id=V_LIQUOR_ENTITY_ID;
                                                    END;
												END IF;
                                            END;
                                        END IF;
                                    END;
                                ELSE
                                    BEGIN
                                        SET V_REMOVE_COUNT=V_REMOVE_COUNT+1;

                                        UPDATE cart_liquor SET
                                            is_liquor_removed=1,modified_by=V_USER_ID,modification_time=NOW()
                                        WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                                    END;
                                END IF;
                                SET V_COUNTER=V_COUNTER+1;
                            END;
                        END WHILE;
                    END;
                END IF;

				INSERT INTO log_cart_details(cart_id,order_code,liquor_count,ordered_to_entity_id,ordered_to_entity_type,order_from_id,order_from_entity_id,order_from_entity_type,is_active,is_order_placed,is_order_delivered,is_order_cancel,order_by_userid,order_time)
                SELECT id,order_code,liquor_count,ordered_to_entity_id,ordered_to_entity_type,order_from_id,order_from_entity_id,order_from_entity_type,is_active,is_order_placed,is_order_delivered,is_order_cancel,(SELECT V_USER_ID),(SELECT NOW()) from cart_details where id=V_CART_ID;

                UPDATE cart_details SET  liquor_count=V_LIQUOR_COUNT,order_from_entity_id=V_ORDER_FROM_ENITY_ID,order_from_entity_type=V_ORDER_FROM_ENTITY_TYPE
                ,is_order_placed=1,order_time=now(),is_order_delivered=0
                WHERE id= V_CART_ID;

                INSERT INTO order_details
                (cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,dispatch_quantity,dispatch_cost_lot_size,dispatch_total_cost_bottles,recevied_quantity,
                recevied_cost_lot_size,recevied_total_cost_bottles,order_time,order_by,order_process
                )
                SELECT cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,quantity,unit_cost_lot_size,total_cost_bottles,quantity,
                unit_cost_lot_size,total_cost_bottles, (SELECT NOW()),(SELECT V_USER_ID),(SELECT '1')
                from cart_liquor
                WHERE cart_id=V_CART_ID AND is_liquor_removed=0;


            END;
        ELSE
            BEGIN
                IF(V_CAN_PLACE_ORDER=0)THEN
                    BEGIN
                        SET V_ALLOWED_QUOTA=V_USED_QUOTA-V_QUOTA;
                        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Maximum allowed Quota Excedded',V_SWAL_TEXT=CONCAT('Kindly remove ',V_ALLOWED_QUOTA,' bottles to successfully place the order');
                    END;
                END IF;
           END;
        END IF;
    IF(V_NOT_AVAILABLE=0)THEN
		BEGIN
			COMMIT;
		END;
    ELSE
		BEGIN
			ROLLBACK;
        END;
	END IF;

    SELECT V_NOT_AVAILABLE,V_LIQUOR_ID,V_ORDERED_TO_ENTITY_ID,V_UNIT_COST_LOT_SIZE,V_QUANTITY,V_CART_TYPE,V_COUNTER,V_CAN_PLACE_ORDER,V_LIQUOR_ENTITY_ID,V_SWAL_TYPE,V_USED_QUOTA,V_ORDER_CODE,V_PAGE_MODE,V_SWAL_TITLE,V_SWAL_TEXT,V_ORDER_FROM_ENITY_ID,V_ORDER_FROM_ENTITY_TYPE,V_CART_ID,V_USER_ID,V_QUANTITY_UPDATED,V_REMOVE_COUNT,V_LIQUOR_PER_BOTTLE,V_LIQUOR_KEY,V_CAN_PLACE_ORDER;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_NEW_CART_CHECK_OUT_EXCESS_QUOTA(IN P_DATA json)
BEGIN
    DECLARE V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_LIQUOR_DETAILS,V_MODE VARCHAR(500) DEFAULT '';
    DECLARE V_PAGE_MODE,V_ORDER_CODE,V_CART_TYPE VARCHAR(100);
    DECLARE V_CART_DATA JSON;
    DECLARE V_LIQUOR_KEY,V_CART_AVAIBLE,V_ORDERED_TO_ENTITY_ID,V_NOT_AVAILABLE,V_CAN_PLACE_ORDER,V_ORDER_FROM_ENITY_ID,
			V_ORDER_FROM_ENTITY_TYPE,V_ALLOWED_QUOTA,V_REMOVE_COUNT,V_LIQUOR_PER_BOTTLE,V_QUANTITY_UPDATED,V_CART_ID,
            V_USER_ID,V_REMOVED,V_LIQUOR_DESCRIPTION_ID,V_LIQUOR_ENTITY_ID,V_CART_DATA_COUNT,V_COUNTER ,V_QUANTITY,
            V_LIQUOR_COUNT,V_QUOTA,V_USED_QUOTA,V_CURRENT_AVAILABLE_QUANTITY,V_NEW_QUANTITY,V_LIQUOR_ID,
            V_LIQUOR_ENTITY_COUNT,V_SELECTED_LIQUOR_QUANTITY,V_BEER_QUANTITY INT DEFAULT 0;
    DECLARE V_TOTAL_COST_BOTTLES, V_UNIT_COST_LOT_SIZE FLOAT(10,2);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
        GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('CART','SP_CART_CHECK_OUT',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Order could not be placed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;
    INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('CART','SP_CART_CHECK_OUT',P_DATA,@P1,@P2);
        SET V_CART_DATA=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_data');
        SET V_CART_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_id');
        SET V_PAGE_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'page_mode');
        SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
        SET V_LIQUOR_COUNT=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_count');
        SET V_LIQUOR_PER_BOTTLE=FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_per_bottle');
        SET V_CART_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'cart_type');
        SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');
        SET V_CART_DATA_COUNT=  JSON_LENGTH(V_CART_DATA);
        SET V_COUNTER=0;
        SET V_REMOVE_COUNT=0;
        SET V_QUANTITY_UPDATED=0;
        SET V_LIQUOR_ENTITY_COUNT=0;
		SET V_NOT_AVAILABLE=0;



        SELECT (lrq.quota*4),ca.entity_id
        INTO V_QUOTA,V_ORDER_FROM_ENITY_ID  FROM
        ci_admin ca
        INNER JOIN liquor_rank_quota_mapping lrq ON lrq.rankid=ca.rankid
        WHERE admin_id=V_USER_ID;

        SELECT IFNULL(SUM(luq.liquor_count),0) INTO V_USED_QUOTA FROM
        liquor_user_used_quota luq
        WHERE userid=V_USER_ID AND MONTH(luq.insert_time) BETWEEN MONTH(NOW())-1 AND MONTH(NOW()) AND YEAR(luq.insert_time)=YEAR(NOW()) AND luq.order_status!=3 and luq.is_beer=0;

        SET V_USED_QUOTA=V_USED_QUOTA+V_LIQUOR_PER_BOTTLE;

        SET V_CAN_PLACE_ORDER=0;

		SELECT ordered_to_entity_id,order_code
		INTO V_ORDERED_TO_ENTITY_ID,V_ORDER_CODE
		FROM cart_details WHERE id=V_CART_ID;


        IF(V_CART_TYPE='consumer')THEN
            BEGIN
                IF(V_QUOTA>V_USED_QUOTA || V_QUOTA=V_USED_QUOTA )THEN
                    BEGIN
                        SET V_CAN_PLACE_ORDER=1;

						SET V_ORDER_FROM_ENITY_ID=0;

                        SELECT id INTO V_ORDER_FROM_ENTITY_TYPE FROM master_entity_type WHERE entity_type='consumer';

                        SELECT ORDER_CODE INTO V_ORDER_CODE  FROM cart_details WHERE  id=V_CART_ID;

                        INSERT INTO liquor_user_used_quota(userid,liquor_count,order_code,order_status,insert_time,created_by,isactive)
                        VALUES(
                            V_USER_ID,V_LIQUOR_PER_BOTTLE,V_ORDER_CODE,1,NOW(),V_USER_ID,1
                        );

                        SET V_SWAL_TEXT=CONCAT('Kindly produce the given code at canteen to get the liquors order code: ',V_ORDER_CODE);
                    END;
               END IF;
            END;
        ELSE
            BEGIN
                SELECT entity_type
                INTO V_ORDER_FROM_ENTITY_TYPE
                FROM master_entities WHERE id=V_ORDER_FROM_ENITY_ID;

				SET V_SWAL_TEXT=CONCAT('Kindly use the given code during recieving of the liquors order code: ',V_ORDER_CODE);

                SET V_CAN_PLACE_ORDER=1;
            END;
        END IF;

        IF((V_CART_TYPE!='consumer') OR (V_CART_TYPE='consumer' AND V_CAN_PLACE_ORDER=1) )THEN
            BEGIN
				SET V_SWAL_TYPE='success',V_SWAL_TITLE='Checkot';
                IF(V_PAGE_MODE='shopping_cart')THEN
                    BEGIN
                        SET V_COUNTER=0;
                        WHILE (V_CART_DATA_COUNT>V_COUNTER)DO
                            BEGIN
                                SET  V_TOTAL_COST_BOTTLES=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'total_cost_quantity');

                                SET  V_UNIT_COST_LOT_SIZE =FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'unit_cost_quantity');

                                SET V_QUANTITY=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'quantity');

                                SET V_REMOVED=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'remove');

								SET V_LIQUOR_ENTITY_ID=FN_EXTRACT_JSON_DATA(V_CART_DATA,V_COUNTER,'liquor_id');

                                IF(V_REMOVED=0)THEN
                                    BEGIN
                                        IF NOT EXISTS(SELECT id FROM cart_liquor WHERE cart_id= V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID AND quantity=V_QUANTITY)THEN
                                            BEGIN

                                                SET V_QUANTITY_UPDATED=V_QUANTITY_UPDATED+1;

                                                UPDATE cart_liquor SET is_liquor_removed=1,modified_by=V_USER_ID,modification_time=NOW()
                                                        WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                                                SET	 V_TOTAL_COST_BOTTLES=	V_UNIT_COST_LOT_SIZE * V_QUANTITY;

                                                INSERT INTO cart_liquor(cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,created_by,creation_time)
                                                        values(V_CART_ID,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_UNIT_COST_LOT_SIZE,V_TOTAL_COST_BOTTLES,V_USER_ID,NOW());
                                            END;
                                        END IF;
                                        IF(V_CART_TYPE='consumer')THEN
                                            BEGIN
												SELECT available_quantity INTO V_CURRENT_AVAILABLE_QUANTITY
													FROM liquor_entity_mapping WHERE id=V_LIQUOR_ENTITY_ID;

												IF NOT EXISTS(SELECT id FROM liquor_entity_mapping where id=V_LIQUOR_ENTITY_ID and ((available_quantity > V_QUANTITY)|| (available_quantity = V_QUANTITY)))THEN
													BEGIN
														IF(V_NOT_AVAILABLE=0)THEN
															BEGIN
																SET V_SWAL_TEXT='';
                                                            END;
                                                        END IF;
                                                        SET V_NOT_AVAILABLE=1;



                                                        SELECT liquor_description_id
														INTO V_LIQUOR_DESCRIPTION_ID
														FROM liquor_entity_mapping
														WHERE id=V_LIQUOR_ENTITY_ID;

														SELECT CONCAT(brand,' ',liquor_description,' ',liquor_type,' ',liquor_ml,' ml') INTO V_LIQUOR_DETAILS FROM liquor_details WHERE liquor_description_id=V_LIQUOR_DESCRIPTION_ID;
														SET V_NOT_AVAILABLE=1;
														SET V_SWAL_TYPE='warning';
														SET V_SWAL_TEXT=CONCAT(V_SWAL_TEXT,' Only ',V_CURRENT_AVAILABLE_QUANTITY,' bottles are avaible for ',V_LIQUOR_DETAILS, ' Kindly reduce the quantity or remove the liquor');
													END;
												ELSE
													BEGIN


														SET V_NEW_QUANTITY=V_CURRENT_AVAILABLE_QUANTITY-V_QUANTITY;



														UPDATE liquor_entity_mapping SET available_quantity= V_NEW_QUANTITY WHERE  id=V_LIQUOR_ENTITY_ID;


														 INSERT INTO log_liquor_entity_mapping(
														`liquor_entity_id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
														`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
														`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`previous_available_quantity`,`available_quantity`,
				`previous_actual_available_quantity`,`actual_available_quantity`,`city_id`,`state_id`,`creation_time`,`created_type`,`reorder_level`,`updated_from`,`cart_id`
														)SELECT  `id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
														`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
														`selling_price`,`minimun_order_type`,`single_piece_in_lot`,(SELECT V_CURRENT_AVAILABLE_QUANTITY),`available_quantity`,
														`actual_available_quantity`,`actual_available_quantity`,`city_id`,`state_id`,(SELECT NOW()),(SELECT V_USER_ID),`reorder_level`,'NEW_CART_CHECK OUT',V_CART_ID
														FROM  liquor_entity_mapping  WHERE id=V_LIQUOR_ENTITY_ID;
                                                    END;
												END IF;
                                            END;
                                        END IF;
                                    END;
                                ELSE
                                    BEGIN
                                        SET V_REMOVE_COUNT=V_REMOVE_COUNT+1;

                                        UPDATE cart_liquor SET
                                            is_liquor_removed=1,modified_by=V_USER_ID,modification_time=NOW()
                                        WHERE cart_id=V_CART_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID;

                                    END;
                                END IF;
                                SET V_COUNTER=V_COUNTER+1;
                            END;
                        END WHILE;
                    END;
                END IF;

				INSERT INTO log_cart_details(cart_id,order_code,liquor_count,ordered_to_entity_id,ordered_to_entity_type,order_from_id,order_from_entity_id,order_from_entity_type,is_active,is_order_placed,is_order_delivered,is_order_cancel,order_by_userid,order_time)
                SELECT id,order_code,liquor_count,ordered_to_entity_id,ordered_to_entity_type,order_from_id,order_from_entity_id,order_from_entity_type,is_active,is_order_placed,is_order_delivered,is_order_cancel,(SELECT V_USER_ID),(SELECT NOW()) from cart_details where id=V_CART_ID;

                UPDATE cart_details SET  liquor_count=V_LIQUOR_COUNT,order_from_entity_id=V_ORDER_FROM_ENITY_ID,order_from_entity_type=V_ORDER_FROM_ENTITY_TYPE
                ,is_order_placed=1,order_time=now(),is_order_delivered=0
                WHERE id= V_CART_ID;

                INSERT INTO order_details
                (cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,dispatch_quantity,dispatch_cost_lot_size,dispatch_total_cost_bottles,recevied_quantity,
                recevied_cost_lot_size,recevied_total_cost_bottles,order_time,order_by,order_process
                )
                SELECT cart_id,liquor_entity_id,quantity,unit_cost_lot_size,total_cost_bottles,quantity,unit_cost_lot_size,total_cost_bottles,quantity,
                unit_cost_lot_size,total_cost_bottles, (SELECT NOW()),(SELECT V_USER_ID),(SELECT '1')
                from cart_liquor
                WHERE cart_id=V_CART_ID AND is_liquor_removed=0;


            END;
        ELSE
            BEGIN
                IF(V_CAN_PLACE_ORDER=0)THEN
                    BEGIN
                        SET V_ALLOWED_QUOTA=V_USED_QUOTA-V_QUOTA;
                        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Maximum allowed Quota Excedded',V_SWAL_TEXT=CONCAT('Kindly remove ',V_ALLOWED_QUOTA,' bottles to successfully place the order');
                    END;
                END IF;
           END;
        END IF;
    IF(V_NOT_AVAILABLE=0)THEN
		BEGIN
			COMMIT;
		END;
    ELSE
		BEGIN
			ROLLBACK;
        END;
	END IF;

    SELECT V_NOT_AVAILABLE,V_LIQUOR_ID,V_ORDERED_TO_ENTITY_ID,V_UNIT_COST_LOT_SIZE,V_QUANTITY,V_CART_TYPE,V_COUNTER,V_CAN_PLACE_ORDER,V_LIQUOR_ENTITY_ID,V_SWAL_TYPE,V_USED_QUOTA,V_ORDER_CODE,V_PAGE_MODE,V_SWAL_TITLE,V_SWAL_TEXT,V_ORDER_FROM_ENITY_ID,V_ORDER_FROM_ENTITY_TYPE,V_CART_ID,V_USER_ID,V_QUANTITY_UPDATED,V_REMOVE_COUNT,V_LIQUOR_PER_BOTTLE,V_LIQUOR_KEY,V_CAN_PLACE_ORDER;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_NEW_DELIVER_LIQUOR(IN P_ORDER_CODE varchar(50), IN P_USERID int)
BEGIN
    DECLARE V_SWAL_TYPE,V_ENTITY_NAME,V_SWAL_TITLE,V_SWAL_TEXT,V_CART_TYPE,V_LIQUOR_ENTITY_ID,V_LIQUOR_COUNT,V_QUANTITY,V_NEW_QUANTITY,V_CURRENT_AVAILABLE_QUANTITY,V_LIQUOR VARCHAR(500) DEFAULT '';
    DECLARE V_LIQUOR_ENTITY_DETAILS,V_LIQUOR_ENTITY_ID_QUANTITY,V_LIQUOR_DETAILS,V_LIQUOR_NA_LIST  TEXT DEFAULT '';
    DECLARE V_ORDER_DELIVER_STATUS,V_COUNTER,V_COUNT,V_USER_ENTITY_ID,V1,V_NOT_AVAILABLE,V_LIQUOR_DESCRIPTION_ID,V_UNIT_PROFIT,V_TOTAL_PROFIT,V_ENTITY_ID,V_CART_ID,V_ORDER_PROCESS,V_ORDER_BY_USERID,V_AVAILABLE_QUANTITY,V_BALANCE,V_LIQUOR_NOT_AVAILABLE INT DEFAULT 0;
    DECLARE V_TOTAL_PURCHASE_PRICE,V_TOTAL_COST,V_UNIT_COST,V_PURCHASE_PRICE,V_SELLING_PRICE FLOAT(12,2);
    DECLARE V_LIQUOR_LIST LONGTEXT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
        GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('CART','SP_DELIVER_LIQUOR',P_ORDER_CODE,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;
        SET V_COUNTER=0;
        SET V_NOT_AVAILABLE=0;
        SET V_ORDER_PROCESS=3;
		SET V_LIQUOR_NOT_AVAILABLE=0;
		SET V_LIQUOR_NA_LIST='';

		SELECT IFNULL(entity_id,0) INTO V_USER_ENTITY_ID FROM ci_admin WHERE admin_id= P_USERID;

        IF EXISTS(SELECT id FROM cart_details WHERE order_code=P_ORDER_CODE AND ordered_to_entity_id=V_USER_ENTITY_ID)THEN
			BEGIN
				SELECT id,cart_type,order_by_userid,is_order_delivered
				INTO V_CART_ID,V_CART_TYPE,V_ORDER_BY_USERID,V_ORDER_DELIVER_STATUS
				FROM cart_details WHERE order_code=P_ORDER_CODE;

				IF(V_ORDER_DELIVER_STATUS=0)THEN
					BEGIN
						 UPDATE cart_details
						 SET is_order_delivered=1
						 WHERE order_code=P_ORDER_CODE;


						IF(V_CART_TYPE='entity')THEN
							BEGIN

								UPDATE order_details
								SET order_process=2,dispatch_time=NOW(),dispatch_by=P_USERID
								   WHERE cart_id=V_CART_ID;

								SET V_SWAL_TYPE='success',V_SWAL_TITLE='Liquor Dispatched',V_SWAL_TEXT='';
							END;
						ELSE
							BEGIN

								UPDATE order_details
								SET order_process=3,dispatch_time=NOW(),dispatch_by=P_USERID,
								 receive_time=NOW(),order_by=V_ORDER_BY_USERID
								WHERE cart_id=V_CART_ID;

								UPDATE liquor_user_used_quota SET order_status=2 WHERE order_code= P_ORDER_CODE and userid=V_ORDER_BY_USERID;



								SELECT group_concat(liquor_entity_id,'#',dispatch_quantity,'#',dispatch_cost_lot_size,'#',dispatch_total_cost_bottles),COUNT(id)
								INTO V_LIQUOR_LIST,V_COUNT FROM
								order_details WHERE cart_id=V_CART_ID AND is_liquor_removed=0;

								SET V1=0;


								WHILE(V_COUNT>V1)DO
									BEGIN
										SET V1=V1+1;
										SET V_LIQUOR_DETAILS=FN_STRING_SPLIT(V_LIQUOR_LIST,',',V1);
										SET V_LIQUOR_ENTITY_ID=FN_STRING_SPLIT(V_LIQUOR_DETAILS,'#',1);
										SET V_QUANTITY=FN_STRING_SPLIT(V_LIQUOR_DETAILS,'#',2);
										SET V_UNIT_COST=FN_STRING_SPLIT(V_LIQUOR_DETAILS,'#',3);
										SET V_TOTAL_COST=FN_STRING_SPLIT(V_LIQUOR_DETAILS,'#',4);

										SELECT liquor_description_id,entity_id,actual_available_quantity,purchase_price,selling_price
										INTO V_LIQUOR_DESCRIPTION_ID,V_ENTITY_ID,V_AVAILABLE_QUANTITY,V_PURCHASE_PRICE,V_SELLING_PRICE
										FROM liquor_entity_mapping WHERE ID=V_LIQUOR_ENTITY_ID;

										IF(V_AVAILABLE_QUANTITY>V_QUANTITY OR V_AVAILABLE_QUANTITY=V_QUANTITY)THEN
											BEGIN

												SET V_UNIT_PROFIT=V_UNIT_COST-V_PURCHASE_PRICE;

												IF NOT EXISTS(SELECT id FROM liquor_stock_sales WHERE entity_id=V_ENTITY_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID AND liquor_description_id=V_LIQUOR_DESCRIPTION_ID AND liquor_unit_sell_price=V_UNIT_COST AND insert_date=CURDATE())THEN
													BEGIN
														SET V_TOTAL_PURCHASE_PRICE=V_QUANTITY*V_PURCHASE_PRICE;

														SET V_TOTAL_PROFIT=V_TOTAL_COST-V_TOTAL_PURCHASE_PRICE;

														SET V_BALANCE=V_AVAILABLE_QUANTITY-V_QUANTITY;

														INSERT INTO liquor_stock_sales(entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_total_purchase_price,liquor_total_sale_price,liquor_profit,liquor_balance,modification_time,modified_by,insert_date)
																	VALUES(V_ENTITY_ID,V_LIQUOR_ENTITY_ID,V_LIQUOR_DESCRIPTION_ID,V_AVAILABLE_QUANTITY,V_QUANTITY,V_PURCHASE_PRICE,V_UNIT_COST,V_UNIT_PROFIT,V_TOTAL_PURCHASE_PRICE,V_TOTAL_COST,V_TOTAL_PROFIT,V_BALANCE,NOW(),P_USERID,CURDATE());


													END;
												ELSE
													BEGIN
														INSERT INTO log_liquor_stock_sales(entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_total_purchase_price,liquor_total_sale_price,liquor_profit,liquor_balance,modification_time,modified_by,insert_date,order_code)
														SELECT 	entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_total_purchase_price,liquor_total_sale_price,liquor_profit,liquor_balance,modification_time,modified_by,insert_date,(select P_ORDER_CODE) FROM liquor_stock_sales where liquor_entity_id=V_LIQUOR_ENTITY_ID;

														SELECT liquor_sale_qty
														INTO V_CURRENT_AVAILABLE_QUANTITY
														FROM liquor_stock_sales
														WHERE entity_id=V_ENTITY_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID AND liquor_description_id=V_LIQUOR_DESCRIPTION_ID
														AND liquor_unit_sell_price=V_UNIT_COST AND insert_date=curdate() order by id desc LIMIT 1;

														SET V_NEW_QUANTITY=V_CURRENT_AVAILABLE_QUANTITY+V_QUANTITY;

														SET V_TOTAL_PURCHASE_PRICE=V_NEW_QUANTITY*V_PURCHASE_PRICE;

														SET V_TOTAL_COST=V_NEW_QUANTITY*V_SELLING_PRICE;

														SET V_TOTAL_PROFIT=V_TOTAL_COST-V_TOTAL_PURCHASE_PRICE;

														SET V_BALANCE=V_AVAILABLE_QUANTITY-V_NEW_QUANTITY;

														UPDATE liquor_stock_sales
														SET
														liquor_sale_qty=V_NEW_QUANTITY,
														liquor_total_purchase_price=V_TOTAL_PURCHASE_PRICE,
														liquor_total_sale_price=V_TOTAL_COST,
														liquor_profit=V_TOTAL_PROFIT,
														liquor_balance=V_BALANCE,
														modification_time=NOW(),
														modified_by=P_USERID
														WHERE entity_id=V_ENTITY_ID AND liquor_entity_id=V_LIQUOR_ENTITY_ID AND liquor_description_id=V_LIQUOR_DESCRIPTION_ID
														AND liquor_unit_sell_price=V_UNIT_COST and insert_date=CURDATE();

													END;
												END IF;
											END;
										ELSE
											BEGIN
												SELECT CONCAT(brand,' ',liquor_description) INTO V_LIQUOR FROM liquor_details WHERE liquor_description_id=V_LIQUOR_DESCRIPTION_ID;

                                                SET V_LIQUOR_NOT_AVAILABLE=1;

												SET V_LIQUOR_NA_LIST=CONCAT(V_LIQUOR_NA_LIST,',',V_LIQUOR);

                                            END;
										END IF;
                                        UPDATE liquor_entity_mapping SET actual_available_quantity=V_BALANCE WHERE id=V_LIQUOR_ENTITY_ID;
									END;
								END WHILE;

                                SET V_LIQUOR_NA_LIST=TRIM(BOTH "," FROM V_LIQUOR_NA_LIST);


								SET V_SWAL_TYPE='success',V_SWAL_TITLE='Liquor Issued',V_SWAL_TEXT='';
							END;
						 END IF;
					END;
                    ELSE
						BEGIN
							SET V_SWAL_TYPE='success',V_SWAL_TITLE='Liquor Issued',V_SWAL_TEXT='';
                        END;
				END IF;
            END;
         ELSE
			 BEGIN
					Select entity_name INTO V_ENTITY_NAME from master_entities where id in (select ordered_to_entity_id from cart_details where order_code =P_ORDER_CODE);
				    SET V_SWAL_TYPE='warning',V_SWAL_TEXT='Failed',V_SWAL_TITLE=Concat('This order code has been generated for canteen ',V_ENTITY_NAME);
			 END;
         END IF;
    IF(V_LIQUOR_NOT_AVAILABLE=0)THEN
		BEGIN
			COMMIT;
		END;
    ELSE
		BEGIN
			ROLLBACK;
				SET V_SWAL_TITLE=CONCAT(V_LIQUOR_NA_LIST," liquor not available");
                SET V_SWAL_TYPE='warning';
        END;
	END IF;

    SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_CART_TYPE,V_COUNTER,V_CURRENT_AVAILABLE_QUANTITY,V_QUANTITY,V_NOT_AVAILABLE,V_LIQUOR_ENTITY_ID_QUANTITY,V_LIQUOR_ENTITY_ID,V_LIQUOR_DESCRIPTION_ID,V_ENTITY_ID,V_UNIT_COST ;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_NEW_USER_CANCEL_ORDER(IN P_DATA json)
BEGIN
	DECLARE V_ORDER_CODE VARCHAR(100);
    DECLARE V_ORDER_LIQUOR_DETAILS,V_LIQUOR_ENTITY_QUANTITY,V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT VARCHAR(500);
    DECLARE V_USERID,V_CART_ID BIGINT;
    DECLARE V_LIQUOR_ENTITY_ID,V_QUANTITY,V_PREVIOUS_QUANTITY,V_ACTUAL_AVAILABLE_QUANTITY,V_LIQUOR_COUNT,V_COUNTER,V_NEW_QUANTITY INT;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
						VALUES('YOUR ORDERS','SP_USER_CANCEL_ORDER',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Order cancel failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,'fail' as MESSAGE,@P1,@P2;
    END;

    START TRANSACTION;
		SET V_COUNTER=0;
		SET V_USERID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');

        SET V_ORDER_CODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'order_code');

		SELECT id INTO V_CART_ID FROM cart_details WHERE order_code=V_ORDER_CODE AND order_by_userid =V_USERID;


		IF EXISTS(SELECT id FROM cart_details WHERE id=V_CART_ID AND is_order_cancel=0)THEN
			BEGIN
				UPDATE order_details SET is_order_cancel=1,order_process=0,order_cancel_by=V_USERID
				WHERE cart_id=V_CART_ID AND order_by =V_USERID;

				UPDATE cart_details SET is_order_cancel=1,is_active=0,cancel_mode='U',cancel_time=now()
				WHERE id=V_CART_ID AND order_by_userid =V_USERID;

				UPDATE liquor_user_used_quota SET order_status=3
				WHERE order_code=V_ORDER_CODE AND userid=V_USERID;

				SELECT group_concat(liquor_entity_id,'#',quantity),COUNT(id)
				INTO V_ORDER_LIQUOR_DETAILS,V_LIQUOR_COUNT
				FROM order_details WHERE cart_id= V_CART_ID;

				WHILE(V_LIQUOR_COUNT>V_COUNTER)DO
					BEGIN
						SET V_COUNTER=V_COUNTER+1;
						SET V_LIQUOR_ENTITY_QUANTITY=FN_STRING_SPLIT(V_ORDER_LIQUOR_DETAILS,',',V_COUNTER);

						SET V_LIQUOR_ENTITY_ID=FN_STRING_SPLIT(V_LIQUOR_ENTITY_QUANTITY,'#',1);
						SET V_QUANTITY=FN_STRING_SPLIT(V_LIQUOR_ENTITY_QUANTITY,'#',2);

						SELECT available_quantity,actual_available_quantity
                        INTO V_PREVIOUS_QUANTITY,V_ACTUAL_AVAILABLE_QUANTITY
						FROM liquor_entity_mapping WHERE
						ID=V_LIQUOR_ENTITY_ID;

						IF(V_ACTUAL_AVAILABLE_QUANTITY=0 AND V_ACTUAL_AVAILABLE_QUANTITY<0)THEN
							BEGIN
								SET V_NEW_QUANTITY=0;
							END;
						ELSE
							BEGIN
								SET V_NEW_QUANTITY=V_QUANTITY+V_PREVIOUS_QUANTITY;
								IF(V_NEW_QUANTITY>V_ACTUAL_AVAILABLE_QUANTITY)THEN
									BEGIN
										SET V_NEW_QUANTITY=V_ACTUAL_AVAILABLE_QUANTITY;
									END;
								END IF;
							END;
						END IF;



						UPDATE liquor_entity_mapping
						SET available_quantity=V_NEW_QUANTITY
						WHERE ID=V_LIQUOR_ENTITY_ID;

                         INSERT INTO log_liquor_entity_mapping(
						`liquor_entity_id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
						`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
						`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,
						`actual_available_quantity`,`city_id`,`state_id`,`creation_time`,`created_type`,`reorder_level`,`updated_from`,`cart_id`
						)SELECT  `id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
						`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
						`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,
						`actual_available_quantity`,`city_id`,`state_id`,(SELECT NOW()),'35',`reorder_level`,'CANCEL_ORDER',V_CART_ID
						FROM  liquor_entity_mapping  WHERE id=V_LIQUOR_ENTITY_ID;


					END;
				END WHILE;
			END;
		END IF;
		SET V_SWAL_TYPE='success',V_SWAL_TITLE='Success',V_SWAL_TEXT='Order cancel successfully';
    COMMIT;
    SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_CART_ID,V_USERID,V_ORDER_CODE,P_DATA,V_ORDER_LIQUOR_DETAILS,V_LIQUOR_COUNT,V_NEW_QUANTITY,V_PREVIOUS_QUANTITY,V_ACTUAL_AVAILABLE_QUANTITY,V_LIQUOR_ENTITY_ID,V_QUANTITY,V_COUNTER;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_ORDER_TO_BREWERY(IN P_DATA json)
BEGIN

		DECLARE V_APPROVAL_STATUS,V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_PURPOSE,V_LIQUOR_NAME,V_LIQUOR_LIST,V_ORDER_CODE,V_GENERATE_ORDER_CODE VARCHAR(225);
		DECLARE V_MESSAGE_LIQUOR_QTY TEXT;
		DECLARE V_LIQUOR_DETAILS_DATA JSON;
        DECLARE V_USER_ID,V_IRLA,V_CHAIRMAN_ID BIGINT;
		DECLARE V_LIQUOR_DETAILS_DATA_COUNT,V1,V_TOTAL_QUANTITY,V_LIQUOR_ID ,V_QUANTITY,V_ENTITY_ID,V_BREWERY_ID,V_LIQUOR_DESCRIPTION_ID,V_BREWERY_ORDER_ID INT DEFAULT 0;
        DECLARE V_BASE_PRICE,V_TOTAL_PER_LIQUOR_PRICE,V_TOTAL_COST FLOAT(11,2);



        START TRANSACTION;
			SET V_USER_ID = FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');
			SET V_BREWERY_ID = FN_EXTRACT_JSON_DATA(P_DATA,0,'brewery_id');
			SET V_ENTITY_ID = FN_EXTRACT_JSON_DATA(P_DATA,0,'entity_id');
			SET V_LIQUOR_DETAILS_DATA = FN_EXTRACT_JSON_DATA(P_DATA,0,'liquor_details_data');
			SET V_LIQUOR_DETAILS_DATA_COUNT = JSON_LENGTH(V_LIQUOR_DETAILS_DATA);
			SET V1 = 0;
			SET V_LIQUOR_LIST='';
			SET V_MESSAGE_LIQUOR_QTY='';
			SET V_APPROVAL_STATUS = 'P';
            SET V_ORDER_CODE = "";

            SELECT chairman INTO V_CHAIRMAN_ID FROM master_entities WHERE id=V_ENTITY_ID;

            	WHILE(V_ORDER_CODE="") DO
					SET V_GENERATE_ORDER_CODE=lpad(conv(floor(rand()*pow(36,8)), 10, 36), 8, 0);
					IF NOT EXISTS (SELECT id FROM brewery_order WHERE brewery_order_code=V_GENERATE_ORDER_CODE) THEN
						BEGIN
							SET V_ORDER_CODE=V_GENERATE_ORDER_CODE;
						END;
					END IF;
				END WHILE;


		   INSERT INTO brewery_order
					(brewery_id,brewery_order_code,approval_status,approval_from,approved_time,created_by,creation_time,entityid)
				VALUES(V_BREWERY_ID,V_ORDER_CODE,V_APPROVAL_STATUS,V_CHAIRMAN_ID,NOW(),V_USER_ID,NOW(),V_ENTITY_ID);

			SELECT id INTO V_BREWERY_ORDER_ID FROM brewery_order ORDER BY brewery_order_code=V_ORDER_CODE DESC LIMIT 1;

			 WHILE(V1<V_LIQUOR_DETAILS_DATA_COUNT) DO
				BEGIN
					SET V_LIQUOR_ID  = FN_EXTRACT_JSON_DATA(V_LIQUOR_DETAILS_DATA, V1, 'liquor_entity_id');
					SET V_BASE_PRICE = FN_EXTRACT_JSON_DATA(V_LIQUOR_DETAILS_DATA, V1, 'selling_price');
					SET V_QUANTITY = FN_EXTRACT_JSON_DATA(V_LIQUOR_DETAILS_DATA, V1, 'quantity');
					SET V_TOTAL_COST = FN_EXTRACT_JSON_DATA(V_LIQUOR_DETAILS_DATA, V1,'total');
					SET V_TOTAL_QUANTITY=V_TOTAL_QUANTITY+V_QUANTITY;

					INSERT INTO brewery_order_liquor_details
					(brewery_order_id,liquor_description_id,liquor_brewery_id,total_quantity,liquor_base_price,total_purchase_price,insert_time,inserted_by)
					VALUES(V_BREWERY_ORDER_ID,V_LIQUOR_ID,V_LIQUOR_ID ,V_QUANTITY,V_BASE_PRICE,V_TOTAL_COST,NOW(),V_USER_ID);

				SET V1 = V1+1;
			END;
			END WHILE;

            SET V_SWAL_TYPE = "success", V_SWAL_TITLE = CONCAT("Order Code:",V_ORDER_CODE),V_SWAL_TEXT = "order has been sent for confirmation to the chairman";
	COMMIT;
		SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_REARRANGE_STOCK(IN P_DATE date)
BEGIN
	DECLARE V_ENTITY_ID_LIST,V_LIQUOR_ENTITY_ID_LIST LONGTEXT;
    DECLARE V_LIQUOR_ENTITY_ID,V_ROW_ID,V_ENTITY_ID,V_LIQUOR_OPENING_QTY,V_LIQUOR_SALES_QTY,V_LIQUOR_BALANCE,V_LIQUOR_ENTITY_ID_COUNT,V_LIQUOR_ENTITY_ID_COUNTER,V_DAYS_COUNTER,V_NO_OF_DAYS INT;
	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=returned_sqlstate;
        SELECT @P1,@P2;
	END;

    START TRANSACTION;
		SET V_NO_OF_DAYS=DATEDIFF(curdate(),P_DATE);
        SET V_DAYS_COUNTER=0;

                SELECT group_concat(liquor_entity_id),count(liquor_entity_id)
                INTO  V_LIQUOR_ENTITY_ID_LIST,V_LIQUOR_ENTITY_ID_COUNT
                FROM liquor_stock_sales WHERE  insert_date=P_DATE and entity_id=24;

                SET V_LIQUOR_ENTITY_ID_COUNTER=0;

                WHILE(V_LIQUOR_ENTITY_ID_COUNT>V_LIQUOR_ENTITY_ID_COUNTER)DO
					BEGIN
						SET V_LIQUOR_ENTITY_ID_COUNTER=V_LIQUOR_ENTITY_ID_COUNTER+1;
						SET V_LIQUOR_ENTITY_ID=FN_STRING_SPLIT(V_LIQUOR_ENTITY_ID_LIST,',',V_LIQUOR_ENTITY_ID_COUNTER);

                        IF EXISTS(SELECT id FROM liquor_stock_sales WHERE liquor_entity_id=V_LIQUOR_ENTITY_ID and insert_date BETWEEN '2022-02-01' AND P_DATE )THEN
							BEGIN
								SELECT liquor_balance INTO V_LIQUOR_OPENING_QTY
                                FROM liquor_stock_sales
                                WHERE liquor_entity_id=V_LIQUOR_ENTITY_ID
                                AND insert_date<P_DATE ORDER BY id DESC LIMIT 1;

                                SELECT id,liquor_sale_qty INTO V_ROW_ID,V_LIQUOR_SALES_QTY
                                FROM liquor_stock_sales
                                WHERE liquor_entity_id=V_LIQUOR_ENTITY_ID
                                AND insert_date=P_DATE;

                                SET V_LIQUOR_BALANCE=V_LIQUOR_OPENING_QTY-V_LIQUOR_SALES_QTY;

                                UPDATE liquor_stock_sales SET liquor_opening_qty= V_LIQUOR_OPENING_QTY,liquor_balance=V_LIQUOR_BALANCE
                                WHERE id=V_ROW_ID;
                            END;
						END IF;
                    END;
                END WHILE;

    COMMIT;
		SELECT V_NO_OF_DAYS,V_LIQUOR_ENTITY_ID_LIST,V_DAYS_COUNTER,V_LIQUOR_ENTITY_ID_COUNTER,V_LIQUOR_BALANCE,P_DATE,V_LIQUOR_OPENING_QTY,V_LIQUOR_SALES_QTY;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_RECEIVED_LIQUOR_DELIVERY(IN P_DATA json)
BEGIN
	DECLARE V_USER_ID,V_ENTITY_ID BIGINT;
    DECLARE V_ORDER_CODE,V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_NAME VARCHAR(255);
    DECLARE V_LIQUOR_DETAILS JSON;
    DECLARE V_IS_DELIVERY_ISSUE,V_ENTITY_TYPE TINYINT(6);
    DECLARE V_LIQUOR_COUNT,V_UNIT_PROFIT,V_TOTAL_PROFIT,V_OPENING_QTY,V_LIQUOR_ENTITY_ID,V_COUNTER,V_ORDER_DETAILS_ID,V_PREVIOUS_QUANTITY,V_PREVIOUS_ACTUAL_AVAILABLE_QUNATITY,V_NEW_QUANTITY,V_NEW_ACTUAL_AVAILABLE_QUNATITY,V_LIQUOR_DESCRIPTION_ID,V_LEM_ID,V_RECEIVE_TOTAL_QUANTITY,V_UNIT_LOT_SIZE,V_LOT_QUANTITY,V_LIQUOR_TYPE_ID,V_ML_ID,V_SALE_QTY,V_LIQUOR_BALANCE INT;
    DECLARE V_TOTAL_RECEVIED_COST,V_UNIT_SELL_PRICE,V_PURCHASE_PRICE,V_TOTAL_SELL_PRICE,V_TOTAL_PURCHASE_PRICE,V_STOCK_SALE_ID FLOAT(11,2);

	DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
		ROLLBACK;
        GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('RECEIVED LIQUOR','SP_RECEIVED_LIQUOR_DELIVERY',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Unable to add into the stock';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;
    START TRANSACTION;


        SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,'0','user_id');
        SET V_ENTITY_ID=FN_EXTRACT_JSON_DATA(P_DATA,'0','entity_id');
        SET V_ORDER_CODE=FN_EXTRACT_JSON_DATA(P_DATA,'0','order_code');
        SET V_LIQUOR_DETAILS=FN_EXTRACT_JSON_DATA(P_DATA,'0','received_liquor_details');
        SET V_IS_DELIVERY_ISSUE=FN_EXTRACT_JSON_DATA(P_DATA,'0','damage_quantity_flag');

		SET V_LIQUOR_COUNT=JSON_LENGTH(V_LIQUOR_DETAILS);
		SET V_COUNTER=0;

		SELECT entity_type INTO V_ENTITY_TYPE FROM master_entities WHERE id=V_ENTITY_ID;

        IF NOT EXISTS(SELECT id FROM cart_details WHERE order_code=V_ORDER_CODE AND is_order_received=1)THEN
			BEGIN
				WHILE(V_LIQUOR_COUNT>V_COUNTER)DO
					BEGIN

						SET V_ORDER_DETAILS_ID=FN_EXTRACT_JSON_DATA(V_LIQUOR_DETAILS,V_COUNTER,'order_details_id');
						SET V_LEM_ID =FN_EXTRACT_JSON_DATA(V_LIQUOR_DETAILS,V_COUNTER,'lem_id');
						SET V_RECEIVE_TOTAL_QUANTITY=FN_EXTRACT_JSON_DATA(V_LIQUOR_DETAILS,V_COUNTER,'receive_total_quantity');
						SET V_TOTAL_RECEVIED_COST=FN_EXTRACT_JSON_DATA(V_LIQUOR_DETAILS,V_COUNTER,'total_recevied_cost');

						SELECT dispatch_cost_lot_size
						INTO V_UNIT_LOT_SIZE
						FROM order_details WHERE id=V_ORDER_DETAILS_ID;

						SET V_LOT_QUANTITY=CEIL(V_RECEIVE_TOTAL_QUANTITY/V_UNIT_LOT_SIZE);

						UPDATE order_details
						SET recevied_quantity=V_LOT_QUANTITY,
						recevied_cost_lot_size=V_UNIT_LOT_SIZE,
						recevied_total_cost_bottles=V_RECEIVE_TOTAL_QUANTITY,
						receive_by=V_USER_ID,
                        order_process=3,
						receive_time=NOW()
						WHERE id=V_ORDER_DETAILS_ID;



                        SELECT liquor_description_id,liquor_type_id,ml_id,purchase_price,selling_price
                        INTO V_LIQUOR_DESCRIPTION_ID,V_LIQUOR_TYPE_ID,V_ML_ID,V_PURCHASE_PRICE,V_UNIT_SELL_PRICE
                        FROM liquor_entity_mapping
                        WHERE id=V_LEM_ID;


                        IF NOT EXISTS(SELECT id FROM liquor_entity_mapping WHERE entity_id= V_ENTITY_ID AND  liquor_description_id=V_LIQUOR_DESCRIPTION_ID AND purchase_price=V_PURCHASE_PRICE)THEN
							BEGIN
								INSERT INTO liquor_entity_mapping(liquor_description_id,liquor_type_id,ml_id,entity_id,entity_type,minimum_order_quantity,purchase_price,selling_price,available_quantity,actual_available_quantity,creation_time,created_type,reorder_level)
															VALUES(V_LIQUOR_DESCRIPTION_ID,V_LIQUOR_TYPE_ID,V_ML_ID,V_ENTITY_ID,V_ENTITY_TYPE,V_UNIT_LOT_SIZE,V_PURCHASE_PRICE,V_UNIT_SELL_PRICE,V_RECEIVE_TOTAL_QUANTITY,V_RECEIVE_TOTAL_QUANTITY,NOW(),V_USER_ID,100);


								SELECT id
                                INTO V_LIQUOR_ENTITY_ID
                                FROM liquor_entity_mapping
                                WHERE entity_id= V_ENTITY_ID AND  liquor_description_id=V_LIQUOR_DESCRIPTION_ID AND purchase_price=V_PURCHASE_PRICE;

								INSERT INTO
								liquor_stock_sales(entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_balance,insert_date)
								SELECT entity_id,id,liquor_description_id,actual_available_quantity,0,purchase_price,selling_price,(selling_price-purchase_price),actual_available_quantity,curdate()
								from liquor_entity_mapping where id=V_LIQUOR_ENTITY_ID;

							END;
						ELSE
							BEGIN

                                SELECT id,available_quantity,actual_available_quantity
                                INTO V_LIQUOR_ENTITY_ID,V_PREVIOUS_QUANTITY,V_PREVIOUS_ACTUAL_AVAILABLE_QUNATITY
                                FROM liquor_entity_mapping
                                WHERE entity_id= V_ENTITY_ID AND  liquor_description_id=V_LIQUOR_DESCRIPTION_ID AND purchase_price=V_PURCHASE_PRICE;

                                SET V_NEW_QUANTITY=V_PREVIOUS_QUANTITY+V_RECEIVE_TOTAL_QUANTITY;
                                SET V_NEW_ACTUAL_AVAILABLE_QUNATITY=V_PREVIOUS_ACTUAL_AVAILABLE_QUNATITY+V_RECEIVE_TOTAL_QUANTITY;

								INSERT INTO log_liquor_entity_mapping(
								`liquor_entity_id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
								`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
								`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,
								`actual_available_quantity`,`city_id`,`state_id`,`creation_time`,`created_type`,`updated_from`
								)SELECT  `id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
								`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
								`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,
								`actual_available_quantity`,`city_id`,`state_id`,(SELECT NOW()),(SELECT V_USER_ID),'RECEVIED_LIQUOR'
								FROM  liquor_entity_mapping WHERE id=V_LIQUOR_ENTITY_ID;

                                UPDATE liquor_entity_mapping
                                SET available_quantity=V_NEW_QUANTITY,actual_available_quantity=V_NEW_ACTUAL_AVAILABLE_QUNATITY
                                WHERE id=V_LIQUOR_ENTITY_ID;

								SELECT id,liquor_opening_qty,liquor_sale_qty,liquor_unit_profit
                                INTO V_STOCK_SALE_ID,V_OPENING_QTY,V_SALE_QTY,V_UNIT_PROFIT
								FROM liquor_stock_sales
                                WHERE liquor_entity_id=V_LIQUOR_ENTITY_ID AND insert_date=curdate();

								SET V_OPENING_QTY=V_OPENING_QTY+V_RECEIVE_TOTAL_QUANTITY;
                                SET V_LIQUOR_BALANCE=V_OPENING_QTY-V_SALE_QTY;
								SET V_TOTAL_SELL_PRICE=V_SALE_QTY * V_UNIT_SELL_PRICE;
                                SET V_TOTAL_PURCHASE_PRICE=V_SALE_QTY*V_PURCHASE_PRICE;
								SET V_TOTAL_PROFIT=V_SALE_QTY*V_UNIT_PROFIT;

                                INSERT INTO log_liquor_stock_sales(entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_total_purchase_price,liquor_total_sale_price,liquor_profit,liquor_balance,modification_time,modified_by,insert_date,order_code)
								SELECT 	entity_id,liquor_entity_id,liquor_description_id,liquor_opening_qty,liquor_sale_qty,liquor_unit_purchase_price,liquor_unit_sell_price,liquor_unit_profit,liquor_total_purchase_price,liquor_total_sale_price,liquor_profit,liquor_balance,modification_time,modified_by,insert_date,(select V_ORDER_CODE) FROM liquor_stock_sales where liquor_entity_id=V_LIQUOR_ENTITY_ID;

                                UPDATE liquor_stock_sales
                                SET liquor_opening_qty=V_OPENING_QTY,
                                liquor_total_purchase_price=V_TOTAL_PURCHASE_PRICE,
                                liquor_total_sale_price=V_TOTAL_SELL_PRICE,
                                liquor_profit=V_TOTAL_PROFIT,
                                liquor_balance=V_LIQUOR_BALANCE,
                                modification_time=NOW(),
                                modified_by=V_USER_ID
                                WHERE id=V_STOCK_SALE_ID;
                            END;
                        END IF;

                        SET V_COUNTER=V_COUNTER+1;
				   END;
				END WHILE;

				UPDATE cart_details SET is_order_received=1,is_deliver_issue=V_IS_DELIVERY_ISSUE,is_active=0
				WHERE order_code=V_ORDER_CODE AND order_from_entity_id=V_ENTITY_ID;

				SET V_SWAL_TYPE='success';
				SET V_SWAL_TITLE='';
                SET V_SWAL_TEXT ='Liquor received successfully';
			END;
		ELSE
			BEGIN
				SELECT firstname
                INTO V_NAME
                FROM ci_admin
                WHERE admin_id= V_USER_ID;

                SET V_SWAL_TYPE='warning',V_SWAL_TITLE='';
                SET V_SWAL_TEXT =CONCAT(V_NAME,' has already received this delivery');
            END;
        END IF;

    COMMIT;
    SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_USER_ID,V_COUNTER,V_LOT_QUANTITY,V_UNIT_LOT_SIZE,V_USER_ID,V_ORDER_DETAILS_ID,V_RECEIVE_TOTAL_QUANTITY,V_ORDER_DETAILS_ID,V_LIQUOR_COUNT,V_IS_DELIVERY_ISSUE,V_LIQUOR_DETAILS,V_ENTITY_ID,V_ORDER_CODE;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_REGISTER_USER(IN P_DATA json)
BEGIN
    DECLARE V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_PASSWORD,V_EMAIL,V_FIRSTNAME,V_MOBILE_NO,V_RANK,V_USERNAME VARCHAR(255);
    DECLARE V_RANK_ID INT;
    DECLARE V_DATE_OF_BIRTH DATE;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                        VALUES('Register','SP_REGISTER_USER',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Order could not be placed',V_SWAL_TEXT='Cart failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;

        SET V_DATE_OF_BIRTH=FN_EXTRACT_JSON_DATA(P_DATA,0,'date_of_birth');
        SET V_USERNAME=FN_EXTRACT_JSON_DATA(P_DATA,0,'username');


        IF NOT EXISTS(SELECT admin_id FROM ci_admin WHERE username=V_USERNAME AND date_of_birth=V_DATE_OF_BIRTH)THEN
            BEGIN
                SET V_PASSWORD=FN_EXTRACT_JSON_DATA(P_DATA,0,'password');
                SET V_EMAIL=FN_EXTRACT_JSON_DATA(P_DATA,0,'email');
                SET V_FIRSTNAME=FN_EXTRACT_JSON_DATA(P_DATA,0,'firstname');
                SET V_MOBILE_NO=FN_EXTRACT_JSON_DATA(P_DATA,0,'mobile_no');

                SELECT mr.id,mr.rank INTO V_RANK_ID,V_RANK
                FROM bsf_hrms_data bh
                INNER JOIN master_rank mr ON mr.rank=bh.rank
                WHERE bh.irla=V_USERNAME AND bh.date_of_birth=V_DATE_OF_BIRTH;

                INSERT INTO ci_admin(admin_role_id,username,firstname,email,mobile_no,date_of_birth,image,last_login,password,is_verify,is_admin,is_active,token,created_at,rankid,user_rank)
                                VALUES('63',V_USERNAME,V_FIRSTNAME,V_EMAIL,V_MOBILE_NO,V_DATE_OF_BIRTH,'',NOW(),V_PASSWORD,'1','1','1','',NOW(),V_RANK_ID,V_RANK);

            END;
        END IF;
        SET V_SWAL_TYPE='success';
    COMMIT;
    SELECT V_SWAL_TYPE;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_SEND_EMAIL(IN P_MODE varchar(255),
                                                                    IN V_EMAIL_ADDRESS varchar(255),
                                                                    IN V_OTP_CODE varchar(255))
BEGIN

DECLARE V_SUBJECT,V_SUBMAILBODY,V_MAIL_FORMAT,V_FINAL_MAIL_BODY LONGTEXT;

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        GET DIAGNOSTICS CONDITION 1
        @p2 = MESSAGE_TEXT;
		SELECT @p2,0 AS V_FLAG, 'Something Went Wrong Please Contact Ats team';
      ROLLBACK;
    END;

    START TRANSACTION;

    IF(P_MODE="SEND OTP") THEN
    BEGIN


    SELECT MESSAGE_SUBJECT, MESSAGE_BODY INTO V_SUBJECT,V_SUBMAILBODY
	    FROM mail_format
		 WHERE MESSAGE_NAME='USER REGISTRATION OTP';

    SET V_MAIL_FORMAT='Dear User, <br><br>To complete user registration enter OTP as shown below.<br>
		    <table style="width:100%;" border="0" align="center" cellpadding="10" cellspacing="0" id = "FIRST">
				<tr height="37px;">
                    <th colspan= "2" style = "font: bold 13px Trebuchet MS, Verdana, Arial, Helvetica, sans-serif; color: #4f6b72;border-right: 1px solid #C1DAD7; border-top: 1px solid #C1DAD7;border-left: 1px solid #C1DAD7;letter-spacing: 2px;text-transform: uppercase;padding: 6px 6px 6px 12px;background: #CAE8EA  no-repeat; text-align:left;font-weight:bold;"><center>USER REGISTRATION </center></th>
                </tr>
				<tr>
                    <th style = "font: bold 11px Trebuchet MS, Verdana, Arial, Helvetica, sans-serif; color: #4f6b72; border-bottom: 1px solid #C1DAD7;border-top: 1px solid #C1DAD7;border-left: 1px solid #C1DAD7;letter-spacing: 2px;text-transform: uppercase;padding: 6px 6px 6px 12px;background: #CAE8EA  no-repeat; text-align:left;"><strong>OTP</strong></th>
                    <td style = "padding: 6px 6px 6px 12px;border-bottom: 1px solid #C1DAD7;border-left: 1px solid #C1DAD7;border-top: 0;font:12px Trebuchet MS, Verdana, Arial, Helvetica, sans-serif; border-right: 1px solid #C1DAD7;">[OTP_CODE]</td>
                </tr>
                </table>
			<br>To Know More Go To Our Website https://server.aniruddhagps.in/bsfdev/CLMS/admin<br>This is an auto generated mail. Please do not reply back to this.<br><br><br>Thanks<br>Aniruddha Telemetry Systems';

          SET V_MAIL_FORMAT=REPLACE(V_MAIL_FORMAT,'[OTP_CODE]',V_OTP_CODE);
		  SET V_FINAL_MAIL_BODY=REPLACE(V_SUBMAILBODY,'[table]',V_MAIL_FORMAT);



             INSERT INTO alerts_log
			(
				ALERT_CODE,ALERT_TYPE,RECEIVER_MAILID,SENDER_MAILID,SUBJECT,MSG_BODY,IS_ALERT_SENT,INSERT_TIME
			)VALUES
			(
				'OTP', 'M',V_EMAIL_ADDRESS,'noreply@aniruddhagps.com', V_SUBJECT, V_FINAL_MAIL_BODY, 'N', NOW()
			);



            END;
            END IF;


 SELECT 'SUCCESS' AS  `STATUS`;
     COMMIT;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_SEND_SMS(IN V_USERNAME varchar(255), IN V_PHONENO bigint, IN V_OTP_CODE int)
BEGIN

DECLARE V_MESSAGE LONGTEXT;

  DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
     SELECT '0' AS VFLAG, 'Something went wrong, try Again later' AS VMSG;
     GET DIAGNOSTICS CONDITION 1
            @p1 = RETURNED_SQLSTATE, @p2 = MESSAGE_TEXT;
            SELECT @p1 as RETURNED_SQLSTATE  , @p2 as MESSAGE_TEXT;
      ROLLBACK;
    END;

    START TRANSACTION;

    BEGIN
    SET V_MESSAGE=CONCAT('https://smsgw.sms.gov.in/failsafe/HttpLink?username=recttbsf.sms&pin=95gdbmqq&message=Dear ',V_USERNAME,' , Your OTP for CLMS registration is ',V_OTP_CODE,'. Use this passcode to validate your registration process. Thank you. CLMS TEAM&mnumber=',V_PHONENO,'&signature=BSFSMS&dlt_entity_id=1701160101013178098&dlt_template_id=1707163531266975285');
		INSERT INTO sms_log (SMS_CODE,MOBILENO,MSG,IS_SMS_SENT,IS_SMS_DELIVERED,INSERT_TIME) values('REG_H',V_PHONENO,V_MESSAGE,'N','N',NOW());
	END;

SELECT '1' AS VFLAG,'SUCCESS' AS VMSG;
COMMIT;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_TEST(IN country char(3), OUT cities int)
BEGIN
         SELECT COUNT(*) INTO cities FROM world.city
         WHERE CountryCode = country;
       END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_UPDATE_CHAIRMAN_CANTEEN_DETAILS(IN P_DATA json)
BEGIN
        DECLARE V_MODE,V_ENTITY_NAME,V_ADDRESS,V_REGISTERED_BY,V_REGISTRATION_TIME, V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,V_CHAIRMAN_MAIL_ID,V_SUPERVISOR_MAIL_ID,V_EXECUTIVE_MAIL_ID,V_TABLE_NAME VARCHAR(225);
        DECLARE V_CITY,V_ENTITY_ID,V_ID,V_LAST_UPDATE_RECORD_LOG_ID,V_STATE,V_USER_ID,V_CURRENT_CHAIRMAN,V_CURRENT_SUPERVISOR,V_CURRENT_EXECUTIVE,V_CHAIRMAN,V_SUPERVISOR,V_EXECUTIVE,V_DISTRIBUTING_ENTITY_TYPE,V_AUTHORISED_ENTITY,V_BREWERY_ENTITY_TYPE_ID,V_OUTLET_TYPE INT;

        DECLARE EXIT HANDLER FOR SQLEXCEPTION
        BEGIN
            ROLLBACK;
            GET DIAGNOSTICS CONDITION 1
            @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
            INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                VALUES('EDIT CANTEEN MASTER BY CHAIRMAN','SP_UPDATE_CHAIRMAN_CANTEEN_DETAILS',P_DATA,@P2,@P1);
            SET V_SWAL_TITLE='Error!',V_SWAL_MESSAGE='Something Went wrong, Please Contact Admin',V_SWAL_TYPE='error';
            SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE,@P1,@P2;
        END;

        START TRANSACTION;
            SET V_SUPERVISOR=FN_EXTRACT_JSON_DATA(P_DATA,0,'supervisor');
            SET V_EXECUTIVE=FN_EXTRACT_JSON_DATA(P_DATA,0,'executive');
            SET V_DISTRIBUTING_ENTITY_TYPE=FN_EXTRACT_JSON_DATA(P_DATA,0,'distrubuting_entity_type');
            SET V_AUTHORISED_ENTITY=FN_EXTRACT_JSON_DATA(P_DATA,0,'distributor_authorised_entity');
            SET V_MODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'mode');
            SET V_USER_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');


            SELECT email INTO V_SUPERVISOR_MAIL_ID FROM ci_admin WHERE admin_id=V_SUPERVISOR;
            SELECT email INTO V_EXECUTIVE_MAIL_ID FROM ci_admin WHERE admin_id=V_EXECUTIVE;

            INSERT INTO log_ci_admin(admin_id,admin_role_id,entity_id,username,firstname,email,mobile_no,date_of_birth,
            image,password,last_login,is_verify,is_admin,is_active,is_supper,token,password_reset_code,android_uuid,gcm_id,last_ip,
            created_at,updated_at)
            SELECT admin_id,admin_role_id,entity_id,username,firstname,email,mobile_no,date_of_birth,
            image,password,last_login,is_verify,is_admin,is_active,is_supper,token,password_reset_code,android_uuid,gcm_id,last_ip,
            created_at,(SELECT NOW()) FROM ci_admin WHERE admin_id IN (V_SUPERVISOR,V_EXECUTIVE);

            SET V_ENTITY_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'entity_id');

            INSERT INTO log_master_entities(entity_id,entity_name,address,city,state,chairman,supervisor,executive,
            chairman_mailid,supervisor_mailid,executive_mailid,authorised_distributor_entity_type_id,authorised_distributor,entity_type,isactive)
            SELECT id,entity_name,address,city,state,chairman,supervisor,executive,chairman_mailid,supervisor_mailid,executive_mailid,authorised_distributor_entity_type_id,authorised_distributor,entity_type,isactive
            FROM master_entities WHERE id=V_ENTITY_ID;

            SELECT chairman,supervisor,executive
            INTO V_CURRENT_CHAIRMAN,V_CURRENT_SUPERVISOR,V_CURRENT_EXECUTIVE
            FROM master_entities WHERE id =V_ENTITY_ID;

            UPDATE ci_admin
            SET entity_id=0,admin_role_id=5
            WHERE admin_id IN (V_CURRENT_SUPERVISOR,V_CURRENT_EXECUTIVE);

            UPDATE master_entities
            SET  supervisor=V_SUPERVISOR, executive=V_EXECUTIVE,supervisor_mailid=V_SUPERVISOR_MAIL_ID,
                executive_mailid=V_EXECUTIVE_MAIL_ID,authorised_distributor_entity_type_id=V_DISTRIBUTING_ENTITY_TYPE,authorised_distributor=V_AUTHORISED_ENTITY,
                modified_by=V_USER_ID,modification_time=now()
            WHERE  id=V_ENTITY_ID;

            SET V_SWAL_TITLE='Details Updated';
            SET V_SWAL_MESSAGE='Details have been updated into the system ';
            SET V_SWAL_TYPE="success";


            UPDATE ci_admin SET entity_id=V_ENTITY_ID,admin_role_id=2 WHERE admin_id IN (V_SUPERVISOR);
            UPDATE ci_admin SET entity_id=V_ENTITY_ID,admin_role_id=2 WHERE admin_id IN (V_EXECUTIVE);
        COMMIT;
     SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE;
    END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_USER_CANCEL_ORDER(IN P_DATA json)
BEGIN
	DECLARE V_ORDER_CODE VARCHAR(100);
    DECLARE V_ORDER_LIQUOR_DETAILS,V_LIQUOR_ENTITY_QUANTITY,V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT VARCHAR(500);
    DECLARE V_USERID,V_CART_ID BIGINT;
    DECLARE V_LIQUOR_ENTITY_ID,V_QUANTITY,V_PREVIOUS_QUANTITY,V_ACTUAL_AVAILABLE_QUANTITY,V_LIQUOR_COUNT,V_COUNTER,V_NEW_QUANTITY,v_ORDER_DELIVERED,V_ORDER_CANCELED INT;

	DECLARE EXIT HANDLER FOR SQLEXCEPTION
	BEGIN
		ROLLBACK;
		GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
						VALUES('YOUR ORDERS','SP_USER_CANCEL_ORDER',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Order cancel failed';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,'fail' as MESSAGE,@P1,@P2;
    END;

    START TRANSACTION;
		SET V_COUNTER=0;
		SET V_USERID=FN_EXTRACT_JSON_DATA(P_DATA,0,'user_id');

        SET V_ORDER_CODE=FN_EXTRACT_JSON_DATA(P_DATA,0,'order_code');

		SELECT id,is_order_delivered,is_order_cancel INTO V_CART_ID,V_ORDER_DELIVERED,V_ORDER_CANCELED FROM cart_details WHERE order_code=V_ORDER_CODE AND order_by_userid =V_USERID;



		IF EXISTS(SELECT id FROM cart_details WHERE id=V_CART_ID AND is_order_cancel=0 and is_order_delivered=0)THEN
			BEGIN
				UPDATE order_details SET is_order_cancel=1,order_process=0,order_cancel_by=V_USERID
				WHERE cart_id=V_CART_ID AND order_by =V_USERID;

				UPDATE cart_details SET is_order_cancel=1,is_active=0,cancel_mode='U',cancel_time=now()
				WHERE id=V_CART_ID AND order_by_userid =V_USERID;

				UPDATE liquor_user_used_quota SET order_status=3
				WHERE order_code=V_ORDER_CODE AND userid=V_USERID;

				SELECT group_concat(liquor_entity_id,'#',quantity),COUNT(id)
				INTO V_ORDER_LIQUOR_DETAILS,V_LIQUOR_COUNT
				FROM order_details WHERE cart_id= V_CART_ID;

				WHILE(V_LIQUOR_COUNT>V_COUNTER)DO
					BEGIN
						SET V_COUNTER=V_COUNTER+1;
						SET V_LIQUOR_ENTITY_QUANTITY=FN_STRING_SPLIT(V_ORDER_LIQUOR_DETAILS,',',V_COUNTER);

						SET V_LIQUOR_ENTITY_ID=FN_STRING_SPLIT(V_LIQUOR_ENTITY_QUANTITY,'#',1);
						SET V_QUANTITY=FN_STRING_SPLIT(V_LIQUOR_ENTITY_QUANTITY,'#',2);

						SELECT available_quantity,actual_available_quantity
                        INTO V_PREVIOUS_QUANTITY,V_ACTUAL_AVAILABLE_QUANTITY
						FROM liquor_entity_mapping WHERE
						ID=V_LIQUOR_ENTITY_ID;

						IF(V_ACTUAL_AVAILABLE_QUANTITY=0 OR V_ACTUAL_AVAILABLE_QUANTITY<0)THEN
							BEGIN
								SET V_NEW_QUANTITY=0;
							END;
						ELSE
							BEGIN
								SET V_NEW_QUANTITY=V_QUANTITY+V_PREVIOUS_QUANTITY;
								IF(V_NEW_QUANTITY>V_ACTUAL_AVAILABLE_QUANTITY)THEN
									BEGIN
										SET V_NEW_QUANTITY=V_ACTUAL_AVAILABLE_QUANTITY;
									END;
								END IF;
							END;
						END IF;



						UPDATE liquor_entity_mapping
						SET available_quantity=V_NEW_QUANTITY
						WHERE ID=V_LIQUOR_ENTITY_ID;

                         INSERT INTO log_liquor_entity_mapping(
						`liquor_entity_id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
						`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
						`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,
						`actual_available_quantity`,`city_id`,`state_id`,`creation_time`,`created_type`,`reorder_level`,`updated_from`,`cart_id`
						)SELECT  `id`,`liquor_description_id`,`liquor_type_id`,`ml_id`,`entity_id`,
						`entity_type`,`minimum_order_quantity`,`sale_minimum_order_quantity`,`purchase_price`,
						`selling_price`,`minimun_order_type`,`single_piece_in_lot`,`available_quantity`,
						`actual_available_quantity`,`city_id`,`state_id`,(SELECT NOW()),'35',`reorder_level`,'CANCEL_ORDER',V_CART_ID
						FROM  liquor_entity_mapping  WHERE id=V_LIQUOR_ENTITY_ID;


					END;
				END WHILE;
                		SET V_SWAL_TYPE='success',V_SWAL_TITLE='Success',V_SWAL_TEXT='Order cancel successfully';
			END;
		ELSEIF(V_ORDER_DELIVERED=1)THEN
			BEGIN
				SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Order is delivered',V_SWAL_TEXT='Cannot cancel this order';
            END;
		ELSE
			BEGIN
				SET V_SWAL_TYPE='success',V_SWAL_TITLE='Success',V_SWAL_TEXT='Order is canceled';
            END;
		END IF;


    COMMIT;
    SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,V_CART_ID,V_USERID,V_ORDER_CODE,P_DATA;
END;

create
    definer = itbp_final_user@`%` procedure itbp_clds.SP_VERIFY_RETIREE(IN P_DATA json)
BEGIN
    DECLARE V_HRMS_ID,V_RETIREE_VEFICATION_ID,V_ACTION BIGINT;
    DECLARE V_SWAL_TITLE,V_SWAL_TEXT,V_SWAL_TYPE VARCHAR(100);
    DECLARE V_VALID_UPTO DATE;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        GET DIAGNOSTICS CONDITION 1
        @P1=MESSAGE_TEXT,@P2=RETURNED_SQLSTATE;
        INSERT INTO sp_error_log_data(page_name,sp_name,data_passed,error_message,returne_sql_state)
                VALUES('ADD RETIREE','SP_VERIFY_RETIREE',P_DATA,@P1,@P2);
        SET V_SWAL_TYPE='warning',V_SWAL_TITLE='Failed',V_SWAL_TEXT='Unable to add Data';
        SELECT V_SWAL_TYPE,V_SWAL_TITLE,V_SWAL_TEXT,@P1,@P2;
    END;

    START TRANSACTION;
       SET V_HRMS_ID=FN_EXTRACT_JSON_DATA(P_DATA,0,'hrms_id');
       SET V_ACTION=FN_EXTRACT_JSON_DATA(P_DATA,0,'action');
       SET V_RETIREE_VEFICATION_ID =FN_EXTRACT_JSON_DATA(P_DATA,0,'id');
       SET V_VALID_UPTO=DATE_ADD(CURDATE(),INTERVAL 1 YEAR);

       UPDATE bsf_hrms_data SET is_verified=V_ACTION WHERE id= V_HRMS_ID;
       UPDATE retiree_verification_details SET retiree_valid_upto=V_VALID_UPTO,is_verified=1,verification_time=NOW(),ACTION=V_ACTION WHERE id= V_RETIREE_VEFICATION_ID;

        IF(V_ACTION=1)THEN
            BEGIN
                SET V_SWAL_TITLE='Verfication Completed';
                SET V_SWAL_TEXT='Perssonel Approved';
                SET V_SWAL_TYPE='success';
            END;
        ELSE
            BEGIN
                SET V_SWAL_TITLE='Verfication Completed';
                SET V_SWAL_TEXT='Perssonel Denied';
                SET V_SWAL_TYPE='success';
            END;
        END IF;

    COMMIT;
    SELECT V_SWAL_TITLE,V_SWAL_TEXT,V_SWAL_TYPE;
END;

