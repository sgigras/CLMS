ALTER TABLE liquor_description ADD brewery_id int;
-- ALTER TABLE liquor_description MODIFY COLUMN brewery_id int;

DROP PROCEDURE SP_INSERT_UPDATE_LIQUOR_DETAILS;

create
    definer = root@localhost procedure SP_INSERT_UPDATE_LIQUOR_DETAILS(IN P_DATA json)
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
				/*

					SELECT id INTO V_ID FROM liquor_description WHERE liquor_name=V_LIQUOR_NAME;
        */

                    INSERT INTO liquor_description(
									liquor_description,liquor_image,liquor_brand_id,liquor_bottle_size_id,liquor_type_id,
										liquor_ml_id,creation_time,created_by,brewery_id
                                    )
								VALUES(
									V_LIQUOR_DESCRIPTION,V_LIQUOR_IMAGE,V_LIQUOR_BRAND_ID,V_LIQUOR_BOTTLE_SIZE_ID,V_LIQUOR_TYPE_ID,
                                            V_LIQUOR_ML_ID,NOW(),V_USER_ID,V_BREWEY_ID
									);


			/*		INSERT INTO log_liquor_description(liquor_description_id,liquor_name,liquor_image,liquor_type,creation_time,created_by)
					VALUES(V_ID,V_LIQUOR_NAME,V_LIQUOR_IMAGE,V_LIQUOR_TYPE_ID,NOW(),V_USER_ID);*/
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
                /*
                INSERT INTO log_liquor_description(liquor_description_id,liquor_name,liquor_image,liquor_type)
				SELECT id,liquor_name,liquor_image,liquor_type from liquor_description where id=V_ID;
                */
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
        /*
                UPDATE log_liquor_description
                SET creation_time=now(),created_by=V_USER_ID
                WHERE liquor_description_id=V_ID
                ORDER BY id DESC LIMIT 1;
		*/
			END;
        END IF;
    COMMIT;
        SELECT V_SWAL_TITLE,V_SWAL_MESSAGE,V_SWAL_TYPE;
END;

