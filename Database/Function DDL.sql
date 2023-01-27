-- 4 Function
create
    function FN_DATA_TYPE_ADD_QUOTES(P_DATA_TYPE varchar(50), P_DATA text) returns text
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
    function FN_EXTRACT_JSON_DATA(P_DATA json, P_COUNTER int, P_KEY varchar(100)) returns longtext
    deterministic
BEGIN
  RETURN TRIM(BOTH '"' FROM JSON_EXTRACT(P_DATA,CONCAT('$[',P_COUNTER,'].',P_KEY)));
END;

create
    function FN_PREPARE_INSERT_UPDATE_STATEMENT(P_MODE varchar(10),
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
    function FN_STRING_SPLIT(P_SPLIT_STRING text, P_DELIMITER varchar(20), P_POSITION int) returns varchar(500)
    deterministic
BEGIN
  RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(P_SPLIT_STRING, P_DELIMITER, P_POSITION), LENGTH(SUBSTRING_INDEX(P_SPLIT_STRING, P_DELIMITER, P_POSITION - 1)) + 1), P_DELIMITER, '');
END;

