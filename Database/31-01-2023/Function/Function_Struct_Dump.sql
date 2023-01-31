create
    definer = itbp_final_user@`%` function itbp_clds.FN_DATA_TYPE_ADD_QUOTES(P_DATA_TYPE varchar(50), P_DATA text) returns text
    deterministic
BEGIN
DECLARE V_DATA TEXT;
	IF(P_DATA_TYPE='string')THEN
		BEGIN
			SET V_DATA=CONCAT('"',P_DATA,'"');
		END;
    ELSE
		BEGIN
			SET V_DATA=P_DATA;
		END;
    END IF;

RETURN V_DATA;
END;

create
    definer = itbp_final_user@`%` function itbp_clds.FN_EXTRACT_JSON_DATA(P_DATA json, P_COUNTER int, P_KEY varchar(100)) returns longtext
    deterministic
BEGIN
  RETURN TRIM(BOTH '"' FROM JSON_EXTRACT(P_DATA,CONCAT('$[',P_COUNTER,'].',P_KEY)));
END;

create
    definer = itbp_final_user@`%` function itbp_clds.FN_GET_BOTTEL_SIZE(P_SIZEID varchar(10)) returns text deterministic
BEGIN
DECLARE V_DATA TEXT;

    SELECT
        IFNULL(bottle_size,'N/A')
        INTO
            V_DATA
    FROM
        liquor_bottle_size WHERE id=P_SIZEID;

RETURN IFNULL(V_DATA,'N/A');
END;

create
    definer = itbp_final_user@`%` function itbp_clds.FN_GET_BRAND_NAME(P_BRANDID varchar(10)) returns text deterministic
BEGIN
DECLARE V_DATA TEXT;

    SELECT
        IFNULL(brand,'N/A')
        INTO
            V_DATA
    FROM
        liquor_brand WHERE id=P_BRANDID;

RETURN IFNULL(V_DATA,'N/A');
END;

create
    definer = itbp_final_user@`%` function itbp_clds.FN_GET_BREWERY_NAME(P_BREWERYID varchar(10)) returns text
    deterministic
BEGIN
DECLARE V_DATA TEXT;

    SELECT
        IFNULL(brewery_name,'N/A')
        INTO
            V_DATA
    FROM
        master_brewery WHERE id=P_BREWERYID;

RETURN IFNULL(V_DATA,'N/A');
END;

create
    definer = itbp_final_user@`%` function itbp_clds.FN_GET_CITYNAME(P_CITYID varchar(10)) returns text deterministic
BEGIN
DECLARE V_DATA TEXT;

    SELECT
        IFNULL(city,'N/A')
        INTO
            V_DATA
    FROM
        city_state WHERE state_id=P_CITYID;

RETURN IFNULL(V_DATA,'N/A');
END;

create
    definer = itbp_final_user@`%` function itbp_clds.FN_GET_LIQUOR_BRAND(P_LIQUOR_BRAND_ID varchar(10)) returns text
    deterministic
BEGIN
DECLARE V_DATA TEXT;

    SELECT
        IFNULL(brand,'N/A')
        INTO
            V_DATA
    FROM
        liquor_brand WHERE id = P_LIQUOR_BRAND_ID;

RETURN IFNULL(V_DATA,'N/A');
END;

create
    definer = itbp_final_user@`%` function itbp_clds.FN_GET_LIQUOR_ML(P_LIQUOR_ML_ID varchar(10)) returns text
    deterministic
BEGIN
DECLARE V_DATA TEXT;

    SELECT
        concat(IFNULL(liquor_ml,'N/A'),' ML')
        INTO
            V_DATA
    FROM
        liquor_ml WHERE id = P_LIQUOR_ML_ID;

RETURN IFNULL(V_DATA,'N/A');
END;

create
    definer = itbp_final_user@`%` function itbp_clds.FN_GET_LIQUOR_TYPE(P_LIQUOR_TYPE_ID varchar(10)) returns text
    deterministic
BEGIN
DECLARE V_DATA TEXT;

    SELECT
        IFNULL(liquor_type,'N/A')
        INTO
            V_DATA
    FROM
        liquor_type WHERE id = P_LIQUOR_TYPE_ID;

RETURN IFNULL(V_DATA,'N/A');
END;

create
    definer = itbp_final_user@`%` function itbp_clds.FN_GET_STATENAME(P_STATEID varchar(10)) returns text deterministic
BEGIN
DECLARE V_DATA TEXT;

    SELECT
        IFNULL(state,'N/A')
        INTO
            V_DATA
    FROM
        master_state WHERE id=P_STATEID;

RETURN IFNULL(V_DATA,'N/A');
END;

create
    definer = itbp_final_user@`%` function itbp_clds.FN_GET_UNITNAME(P_UNITID varchar(10)) returns text deterministic
BEGIN
DECLARE V_DATA TEXT;

    SELECT
        IFNULL(UnitName,'N/A')
        INTO
            V_DATA
    FROM
        itbp_posting_unit WHERE id=P_UNITID;

RETURN IFNULL(V_DATA,'N/A');
END;

create
    definer = itbp_final_user@`%` function itbp_clds.FN_GET_USER_BY_ID(P_ADMINID varchar(10)) returns text deterministic
BEGIN
DECLARE V_DATA TEXT;

    SELECT     
            IFNULL(firstname, 'N/A')
        INTO
            V_DATA
    FROM
        ci_admin WHERE admin_id=P_ADMINID;

RETURN IFNULL(V_DATA,'N/A');
END;

create
    definer = itbp_final_user@`%` function itbp_clds.FN_GET_USER_WITH_RANK_AND_IRLA(P_IRLARECID varchar(10)) returns text
    deterministic
BEGIN
DECLARE V_DATA TEXT;

    SELECT
        CONCAT(
            username,
            '(',
            IFNULL(user_rank, 'N/A'),
            ') ',
            IFNULL(firstname, 'N/A'),
            ' - ',
            IFNULL(mobile_no, 'N/A')
            )
        INTO
            V_DATA
    FROM
        ci_admin WHERE admin_id=P_IRLARECID;

RETURN IFNULL(V_DATA,'N/A');
END;

create
    definer = itbp_final_user@`%` function itbp_clds.FN_PREPARE_INSERT_UPDATE_STATEMENT(P_MODE varchar(10),
                                                                                        P_TABLE_NAME varchar(55),
                                                                                        P_DATA_TYPE_LIST text,
                                                                                        P_FIELD_LIST text,
                                                                                        P_DATA_LIST text,
                                                                                        P_WHERE_FIELD_LIST text,
                                                                                        P_WHERE_DATA_LIST text,
                                                                                        P_WHERE_DATA_TYPE_LIST text) returns longtext
    deterministic
BEGIN
	DECLARE V_QUERY_STATMENT LONGTEXT DEFAULT '';
    DECLARE V_FIELD_LIST TEXT DEFAULT '';
    DECLARE V_FIELD VARCHAR(100);
    DECLARE V_DATA_LIST LONGTEXT default '';
    DECLARE V_DATA_TYPE VARCHAR(100)  DEFAULT '';
    DECLARE V_DATA TEXT;
    DECLARE V_UPDATE_SET_LIST,V_UPDATE_WHERE_LIST LONGTEXT DEFAULT '';
    DECLARE V_COUNT,V_WHERE_DATA_COUNT,V_FIELD_LIST_COUNT INT DEFAULT 0;
    IF(P_MODE='A')THEN
		BEGIN
                SET V_FIELD_LIST_COUNT=FN_STR_COUNT_WITH_STRING(P_FIELD_LIST,',');
                SET V_FIELD_LIST_COUNT=V_FIELD_LIST_COUNT+1;
			SET V_QUERY_STATMENT=CONCAT('INSERT INTO',' ',P_TABLE_NAME);
			WHILE(V_COUNT <V_FIELD_LIST_COUNT)DO
				BEGIN
					SET V_COUNT=V_COUNT+1;
                    SET V_FIELD=FN_STRING_SPLIT(P_FIELD_LIST,',',V_COUNT);
                    SET V_FIELD_LIST=CONCAT(V_FIELD_LIST,',',V_FIELD);

                    SET V_DATA =FN_STRING_SPLIT(P_DATA_LIST,',',V_COUNT);
                    SET V_DATA_TYPE=FN_STRING_SPLIT(P_DATA_TYPE_LIST,',',V_COUNT);

					SET V_DATA=FN_DATA_TYPE_ADD_QUOTES(V_DATA_TYPE,V_DATA);
                    SET V_DATA_LIST =CONCAT(V_DATA_LIST,',',V_DATA);
                END;
			END WHILE;
            SET V_FIELD_LIST=TRIM(BOTH "," FROM V_FIELD_LIST);

            SET V_DATA_LIST=TRIM(BOTH "," FROM V_DATA_LIST);

            SET V_QUERY_STATMENT=CONCAT(V_QUERY_STATMENT,'(',P_FIELD_LIST,')','VALUES(',V_DATA_LIST,')');
		END;
        ELSE
			BEGIN
				SET V_QUERY_STATMENT=CONCAT('UPDATE',' ',P_TABLE_NAME,' ','SET',' ');
                SET V_FIELD_LIST_COUNT=FN_STR_COUNT_WITH_STRING(P_FIELD_LIST,',');
                SET V_FIELD_LIST_COUNT=V_FIELD_LIST_COUNT+1;
                SET V_UPDATE_SET_LIST=FN_UPDATE_SET_WHERE_CREATE(P_FIELD_LIST,P_DATA_LIST,P_DATA_TYPE_LIST,V_FIELD_LIST_COUNT,",");
				SET V_WHERE_DATA_COUNT=FN_STR_COUNT_WITH_STRING(P_WHERE_FIELD_LIST,',');
                SET V_WHERE_DATA_COUNT=V_WHERE_DATA_COUNT+1;
                SET V_UPDATE_WHERE_LIST=FN_UPDATE_SET_WHERE_CREATE(P_WHERE_FIELD_LIST,P_WHERE_DATA_LIST ,P_WHERE_DATA_TYPE_LIST,V_WHERE_DATA_COUNT,"and");
				SET V_QUERY_STATMENT=CONCAT(V_QUERY_STATMENT,V_UPDATE_SET_LIST,' ','WHERE',' ',V_UPDATE_WHERE_LIST);
			END;
		END IF;
RETURN V_QUERY_STATMENT;
END;

create
    definer = itbp_final_user@`%` function itbp_clds.FN_STRING_SPLIT(P_SPLIT_STRING text, P_DELIMITER varchar(20), P_POSITION int) returns varchar(500)
    deterministic
BEGIN
  RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(P_SPLIT_STRING, P_DELIMITER, P_POSITION), LENGTH(SUBSTRING_INDEX(P_SPLIT_STRING, P_DELIMITER, P_POSITION - 1)) + 1), P_DELIMITER, '');
END;

