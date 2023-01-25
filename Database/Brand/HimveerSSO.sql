alter table ci_admin
    drop column last_login;

alter table ci_admin
    drop column created_at;

alter table ci_admin
    drop column updated_at;

alter table ci_admin
    modify token varchar(5000) null;

alter table ci_admin
    drop column image;

alter table ci_admin
    modify last_ip varchar(255) null;

alter table ci_admin
    add last_login datetime default CURRENT_TIMESTAMP null;
alter table ci_admin
    add created_at datetime default CURRENT_TIMESTAMP null;
alter table ci_admin
    add updated_at datetime default CURRENT_TIMESTAMP null;
alter table ci_admin
    add image varchar(300) null;

drop procedure SP_INSERT_UPDATE_HIMVEER_USER_DETAIL;
create
    definer = itbp_final_user@`%` procedure SP_INSERT_UPDATE_HIMVEER_USER_DETAIL(IN V_USERNAME varchar(50),
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
                                     user_rank)
                    VALUES(
                           '63',
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
                           V_RANK);
            END;
        ELSE
            BEGIN
                UPDATE ci_admin SET
                    firstname = V_NAME,
                    mobile_no = V_MOBILENO,
                    email = V_EMAIL,
                    date_of_birth = V_DOB,
                    user_rank = V_RANK,
                    updated_at = NOW()
                WHERE username=V_USERNAME;
            END;
        END IF;

    COMMIT;
END;

