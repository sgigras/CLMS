USE itb_clms;
-- 105 Tables
create table additional_sheets
(
    id               int auto_increment
        primary key,
    user_id          bigint      null,
    order_code       varchar(50) null,
    cart_id          int         null,
    entity_id        int         null,
    sales_type       varchar(55) null,
    select_type      varchar(55) null,
    purpose          varchar(55) null,
    liquor_entity_id int         null,
    selling_price    varchar(55) null,
    quantity         varchar(55) null,
    total            varchar(55) null,
    sale_by          varchar(55) null,
    sale_time        varchar(55) null
)
    charset = latin1;

create table alerts_log
(
    id                   bigint auto_increment
        primary key,
    alert_code           varchar(5)                     null,
    alert_type           enum ('m', 'n', 's', 'w', 'o') null comment 'm - mail, n - notification, s - sms, w - whatsapp msg, o - others',
    receiver_mailid      text                           null,
    sender_mailid        varchar(100)                   null,
    subject              varchar(200)                   null,
    msg_body             longtext                       null,
    cc_receiver_mailid   text                           null,
    mobileno             varchar(200)                   null,
    fcm                  varchar(500)                   null,
    is_alert_sent        enum ('n', 'y') default 'n'    null,
    is_alert_delivered   enum ('n', 'y')                null,
    insert_time          datetime                       not null,
    attachment_path      varchar(200)                   null,
    is_mail_last_attempt tinyint         default 0      null
);

create table brand_stockist_mapping
(
    id            bigint auto_increment
        primary key,
    entity_id     int          null,
    brand_id      varchar(240) null,
    created_by    int          null,
    creation_time datetime     not null,
    updated_by    int          null,
    updation_time datetime     null,
    is_active     int          null
)
    charset = latin1;

create table brewery_order
(
    id                 int auto_increment
        primary key,
    brewery_id         int                                null,
    brewery_order_code varchar(10)                        null,
    approval_status    varchar(10)                        null comment 'A-Approved R-Rejected P-Pending',
    approval_from      int                                null,
    approved_by        int                                null,
    approved_time      datetime default CURRENT_TIMESTAMP null,
    created_by         int                                null,
    creation_time      datetime default CURRENT_TIMESTAMP null,
    modification_time  datetime                           null,
    modified_by        int                                null,
    remarks            varchar(70)                        null
)
    charset = utf8mb3;

create table brewery_order_liquor_details
(
    id                    int auto_increment
        primary key,
    brewery_order_id      int                                null,
    liquor_description_id int                                null,
    liquor_brewery_id     tinyint                            null,
    total_quantity        int                                null,
    liquor_base_price     float(12, 2)                       null,
    total_purchase_price  float(12, 2)                       null,
    insert_time           datetime default CURRENT_TIMESTAMP null,
    inserted_by           int                                null
)
    charset = utf8mb3;

create table bsf_hrms_data
(
    id                 int auto_increment
        primary key,
    irla               varchar(45)                           null comment 'IRLA Number Of BSF User',
    name               varchar(45)                           null,
    mobile_no          bigint                                null,
    permanent_address  varchar(255)                          null,
    adhaar_card        varchar(15)                           null,
    date_of_birth      varchar(45)                           null,
    `rank`             varchar(55)                           null,
    present_appoitment varchar(45)                           null,
    status             varchar(45)                           null comment '''S'' -> Serving  ''R'' -> Retired',
    location_name      varchar(95)                           null,
    district_name      varchar(95)                           null,
    state_name         varchar(75)                           null,
    email_id           varchar(145)                          null,
    posting_unit       varchar(45)                           null,
    frontier           varchar(45)                           null,
    created_by         varchar(55)                           null,
    creation_time      datetime    default CURRENT_TIMESTAMP null,
    valid_upto         date                                  null,
    retirement_date    varchar(55)                           null,
    joining_date       varchar(55)                           null,
    `force`            int                                   null,
    user_photo         varchar(100)                          null,
    signed_photo       varchar(100)                          null,
    modified_by        bigint                                null,
    is_verified        int         default 0                 null,
    ppo_no             bigint                                null,
    capf_force         varchar(15) default 'BSF'             null,
    ppo_photo          varchar(250)                          null,
    card_photo         varchar(250)                          null,
    modification_time  datetime                              null,
    entity_id          int                                   null
)
    comment 'Data dump provided by BSF' charset = latin1;

create table bsf_hrms_data2
(
    id                 int auto_increment
        primary key,
    irla               int          null comment 'IRLA Number Of BSF User',
    name               varchar(45)  null,
    mobile_no          bigint       null,
    date_of_birth      varchar(45)  null,
    `rank`             varchar(55)  null,
    present_appoitment varchar(45)  null,
    status             varchar(45)  null comment '''S'' -> Serving  ''R'' -> Retired',
    location_name      varchar(95)  null,
    district_name      varchar(95)  null,
    state_name         varchar(75)  null,
    email_id           varchar(145) null
)
    comment 'Data dump provided by BSF' charset = latin1;

create table bsf_hrms_data_dump_backup
(
    id           int auto_increment
        primary key,
    irla_no      int          null,
    bsf_rank     varchar(100) null,
    name         varchar(100) null,
    unit         varchar(100) null,
    location     varchar(100) null,
    mobile       varchar(100) null,
    dob          varchar(100) null,
    status       varchar(45)  null,
    state        varchar(100) null,
    district     varchar(100) null,
    home_address longtext     null
)
    charset = latin1;

create table bsf_posting_unit
(
    id           int auto_increment
        primary key,
    posting_unit varchar(45) null
)
    charset = latin1;

create table cart_details
(
    id                     int auto_increment
        primary key,
    order_code             varchar(45)       null,
    liquor_count           int               null,
    ordered_to_entity_id   int               null comment 'THe entity id which will be providing the product',
    ordered_to_entity_type int               null comment 'in order to find to locate table to find the details whether the enity is to retrive from master_brewery or master_entities',
    order_from_id          int               null comment 'the entity that places the order
if order is placed by Consumer than admin_id table
if order is placed as entity then id master  entities table ',
    order_from_entity_id   int     default 0 null,
    order_from_entity_type int               null comment 'fro',
    is_active              tinyint default 1 null comment '1-ACTIVE 0-INACTIVE',
    is_order_placed        tinyint           null,
    is_order_delivered     tinyint           null comment '1-delivered 0-not delivered',
    is_order_received      tinyint default 0 null comment '0-no issue,1-issue',
    is_deliver_issue       tinyint default 0 null comment '0-no issue,1-issue',
    is_order_cancel        tinyint default 0 not null,
    order_by_userid        bigint            null,
    order_time             datetime          null,
    modified_by            int               null,
    modification_time      datetime          null,
    deactivated_by         bigint            null,
    deactivation_time      datetime          null,
    deactivation_mode      varchar(5)        null comment 'A-AUTO M-MANUAL',
    cart_type              varchar(45)       null comment 'E-ENTITY C-Cosumer',
    cart_detailscol        varchar(45)       null,
    cancel_mode            varchar(10)       null comment 'U-USER A-AUTO',
    cancel_time            datetime          null,
    is_additional_sale     tinyint default 0 null comment ' 0-normal sale 1-additional sale',
    order_mode             varchar(5)        null comment 'W-web A-android'
);

create table cart_liquor
(
    id                 int auto_increment
        primary key,
    cart_id            int                                null,
    liquor_entity_id   tinyint                            null comment 'from liquor_entity_mapping table',
    quantity           int                                null,
    unit_cost_lot_size float(12, 2)                       null,
    total_cost_bottles float(12, 2)                       null,
    is_liquor_removed  tinyint  default 0                 null comment '1-removed 0-present',
    created_by         int                                null,
    creation_time      datetime default CURRENT_TIMESTAMP null,
    modification_time  datetime                           null,
    modified_by        int                                null
);

create table cart_products
(
    id                 int auto_increment
        primary key,
    cart_id            int                                null,
    product_id         tinyint                            null comment 'from mapping_product_entity able',
    quantity           int                                null,
    is_product_removed tinyint  default 0                 null comment '1-removed 0-present',
    created_by         int                                null,
    creation_time      datetime default CURRENT_TIMESTAMP null
);

create table ci_activity_log
(
    id          int auto_increment
        primary key,
    activity_id tinyint  not null,
    user_id     int      not null,
    admin_id    int      not null,
    created_at  datetime not null
)
    charset = latin1;

create table ci_admin
(
    admin_id            int auto_increment
        primary key,
    admin_role_id       int                                not null,
    entity_id           int                                null,
    plant_id            varchar(255)                       null,
    transporter_id      int      default 0                 not null,
    driver_id           int                                null,
    username            varchar(100) charset utf8mb3       not null,
    firstname           varchar(255)                       null,
    lastname            varchar(255)                       null,
    email               varchar(255)                       not null,
    mobile_no           varchar(255)                       not null,
    date_of_birth       date                               null,
    password            varchar(255)                       not null,
    is_verify           tinyint  default 1                 not null,
    is_admin            tinyint  default 1                 not null,
    is_active           tinyint  default 0                 not null,
    is_supper           tinyint  default 0                 not null,
    token               varchar(5000)                      null,
    password_reset_code varchar(255)                       null,
    android_uuid        varchar(255)                       null,
    gcm_id              varchar(255)                       null,
    last_ip             varchar(255)                       null,
    rankid              int                                null,
    user_rank           varchar(100)                       null,
    updated_at          datetime default CURRENT_TIMESTAMP null,
    created_at          datetime default CURRENT_TIMESTAMP null,
    last_login          datetime default CURRENT_TIMESTAMP null,
    image               varchar(300)                       null
)
    charset = latin1;

create table ci_admin_roles
(
    admin_role_id          int auto_increment
        primary key,
    role_plant_mapping     varchar(45)                  null,
    admin_role_title       varchar(100) charset utf8mb3 not null,
    admin_role_status      int                          not null,
    admin_role_created_by  int                          not null,
    admin_role_created_on  datetime                     not null,
    admin_role_modified_by int                          not null,
    admin_role_modified_on datetime                     not null,
    roleid                 int                          null
)
    charset = latin1;

create table ci_email_templates
(
    id          int auto_increment
        primary key,
    name        varchar(255)                       not null,
    slug        varchar(100)                       not null,
    subject     varchar(255)                       not null,
    body        text                               not null,
    last_update datetime default CURRENT_TIMESTAMP not null
)
    charset = latin1;

create table ci_general_settings
(
    id                   int auto_increment
        primary key,
    favicon              varchar(255) null,
    logo                 varchar(255) null,
    application_name     varchar(255) null,
    timezone             varchar(255) null,
    currency             varchar(100) null,
    default_language     int          not null,
    copyright            tinytext     null,
    email_from           varchar(100) not null,
    smtp_host            varchar(255) null,
    smtp_port            int          null,
    smtp_user            varchar(50)  null,
    smtp_pass            varchar(50)  null,
    facebook_link        varchar(255) null,
    twitter_link         varchar(255) null,
    google_link          varchar(255) null,
    youtube_link         varchar(255) null,
    linkedin_link        varchar(255) null,
    instagram_link       varchar(255) null,
    recaptcha_secret_key varchar(255) null,
    recaptcha_site_key   varchar(255) null,
    recaptcha_lang       varchar(50)  null,
    created_date         datetime     null,
    updated_date         datetime     null
)
    engine = MyISAM
    charset = utf8mb3;

create table ci_language
(
    id         int auto_increment
        primary key,
    name       varchar(225) collate utf8mb3_unicode_ci not null,
    short_name varchar(15)                             not null,
    status     int      default 1                      not null,
    created_at datetime default CURRENT_TIMESTAMP      not null
)
    charset = latin1;

create table ci_states
(
    id         int          not null
        primary key,
    name       varchar(100) null,
    country_id int          null
)
    charset = latin1;

create table ci_users
(
    id                  int auto_increment
        primary key,
    username            varchar(50)       not null,
    firstname           varchar(30)       not null,
    lastname            varchar(30)       not null,
    email               varchar(50)       not null,
    mobile_no           varchar(30)       not null,
    password            varchar(255)      not null,
    address             varchar(255)      not null,
    role                tinyint default 1 not null,
    is_active           tinyint default 1 not null,
    is_verify           tinyint default 0 not null,
    is_admin            tinyint default 0 not null,
    token               varchar(255)      not null,
    password_reset_code varchar(255)      not null,
    last_ip             varchar(30)       not null,
    created_at          datetime          not null,
    updated_at          datetime          not null
)
    engine = MyISAM
    charset = utf8mb3;

create table city_state
(
    City     varchar(21) null,
    State    varchar(39) null,
    Region   varchar(16) null,
    state_id int         not null
)
    charset = utf8mb3;

create table clms_force
(
    id         int auto_increment
        primary key,
    force_code varchar(45)  null,
    force_name varchar(100) null
)
    charset = latin1;

create table config_variable
(
    id          int auto_increment
        primary key,
    variable    varchar(45)  not null,
    value       varchar(45)  not null,
    description varchar(250) not null,
    status      tinyint      not null,
    update_time datetime     not null,
    insert_time datetime     not null
)
    charset = latin1;

create table cron_log
(
    id             bigint auto_increment
        primary key,
    file_name      varchar(200)                       not null,
    execution_time datetime default CURRENT_TIMESTAMP null,
    mail_error_log text                               null
)
    charset = latin1;

create table curl_response
(
    id           int auto_increment
        primary key,
    response     varchar(100) null,
    curl_request varchar(800) null,
    insert_time  datetime     not null
)
    charset = latin1;

create table entity_location_mapping
(
    entity_id          tinyint not null,
    entity_name        tinyint not null,
    city_district_name tinyint not null,
    state              tinyint not null
)
    engine = MyISAM;

create table entity_new_location_mapping
(
    entity_id          tinyint not null,
    entity_name        tinyint not null,
    city_district_name tinyint not null,
    state              tinyint not null,
    entity_type        tinyint not null
)
    engine = MyISAM;

create table file_upload
(
    id            int auto_increment
        primary key,
    img_path      varchar(50)                        null,
    uploaded_by   int                                null,
    creation_time datetime default CURRENT_TIMESTAMP null
)
    charset = latin1;

create table itbp_posting_unit
(
    ID               int auto_increment
        primary key,
    UnitName         text   null,
    UnitType         int    null,
    PhoneNumber      bigint null,
    Address          text   null,
    ShqUnitId        int    null,
    ShqId            int    null,
    ShqUnitName      text   null,
    FrontierUnitId   int    null,
    FrontierUnitName text   null,
    Active           int    null,
    UpdatedBy        text   null,
    UpdatedOn        text   null
)
    charset = latin1;

create table itbp_rank_group
(
    Id          int  null,
    Description text null,
    RankGroupId int  null,
    Active      int  null,
    UpdatedBy   text null,
    UpdatedOn   text null
)
    charset = latin1;

create table itbp_user
(
    ï»¿Id     int  null,
    RegtlNo   text null,
    Name      text null,
    RankId    int  null,
    RoleId    int  null,
    CanteenId int  null,
    Active    int  null,
    UpdatedBy text null,
    UpdatedOn text null
)
    charset = latin1;

create table liquor_bottle_size
(
    id            int          not null
        primary key,
    bottle_size   varchar(500) null,
    created_by    varchar(45)  null,
    creation_time varchar(45)  null
);

create table liquor_brand
(
    id                int auto_increment
        primary key,
    brand             varchar(200) null,
    created_by        bigint       null,
    creation_time     datetime     null,
    modified_by       varchar(255) null,
    modification_time datetime     null
);

create table liquor_brand_log
(
    id            int         null,
    brand         varchar(55) null,
    created_by    varchar(55) null,
    creation_time varchar(55) null
)
    charset = latin1;

create table liquor_description
(
    id                    int auto_increment
        primary key,
    liquor_description    varchar(200) null,
    liquor_image          varchar(200) null,
    liquor_brand_id       varchar(100) null,
    liquor_bottle_size_id int          null,
    liquor_type_id        varchar(45)  null,
    liquor_ml_id          varchar(45)  null,
    creation_time         datetime     null,
    created_by            int          null,
    modification_time     datetime     null,
    modified_by           int          null,
    liquor_descriptioncol varchar(45)  null,
    brewery_id            int          null
);

create table liquor_description_back
(
    id                    int auto_increment
        primary key,
    liquor_description    varchar(200) null,
    liquor_image          varchar(200) null,
    liquor_brand_id       varchar(100) null,
    liquor_bottle_size_id int          null,
    liquor_type_id        varchar(45)  null,
    liquor_ml_id          varchar(45)  null,
    creation_time         datetime     null,
    created_by            int          null,
    modification_time     datetime     null,
    modified_by           int          null,
    liquor_descriptioncol varchar(45)  null
);

create table liquor_description_copy
(
    id                    int auto_increment
        primary key,
    liquor_description    varchar(200) null,
    liquor_image          varchar(200) null,
    liquor_brand_id       varchar(100) null,
    liquor_bottle_size_id int          null,
    liquor_type_id        varchar(45)  null,
    liquor_ml_id          varchar(45)  null,
    creation_time         datetime     null,
    created_by            int          null,
    modification_time     datetime     null,
    modified_by           int          null,
    liquor_descriptioncol varchar(45)  null
);

create table liquor_entity_mapping
(
    id                          int auto_increment
        primary key,
    liquor_description_id       int           null,
    liquor_type_id              int           null,
    ml_id                       int           null,
    entity_id                   int           null,
    entity_type                 int           null,
    minimum_order_quantity      int           null,
    sale_minimum_order_quantity int           null,
    base_price                  float(10, 2)  null,
    purchase_price              float(10, 2)  null,
    selling_price               float(10, 2)  null,
    minimun_order_type          int           null comment '0-Single_unit 1-lot',
    single_piece_in_lot         int           null comment 'NO of bottles available in one lot',
    available_quantity          int default 0 not null,
    actual_available_quantity   int           null,
    city_id                     int           null,
    state_id                    int           null,
    creation_time               datetime      null,
    created_type                int           null,
    modification_time           datetime      null,
    modified_by                 int           null,
    reorder_level               varchar(55)   null
);

create table liquor_master
(
    ID               int unsigned                       not null
        primary key,
    LIQUOR_TYPE      varchar(50)                        null,
    LIQUOR_BRAND     varchar(50)                        null,
    MOQ              int unsigned                       null,
    UNIT_IN_LOT_SIZE int unsigned                       null,
    REMARKS          varchar(200)                       null,
    INSERT_TIME      datetime default CURRENT_TIMESTAMP not null
)
    charset = latin1;

create table liquor_master_statewise
(
    ID                     int                      not null,
    LIQUORID               int                      null,
    STATEID                int                      null,
    STATE                  varchar(50)              null,
    CURRENT_PURCHASE_PRICE float(8, 2)              null,
    CURRENT_SELL_PRICE     float(8, 2)              null,
    NEW_PURCHASE_PRICE     int          default 0   null,
    NEW_SELL_PRICE         int          default 0   null,
    CURRENT_STOCK          int unsigned default '0' not null,
    NEW_STOCK              int unsigned default '0' not null,
    INSERT_TIME            datetime                 not null
)
    charset = latin1;

create table liquor_ml
(
    id            int auto_increment
        primary key,
    liquor_ml     int                                null,
    creation_time datetime default CURRENT_TIMESTAMP null,
    created_by    int                                null
)
    charset = latin1;

create table liquor_ml_description
(
    id                    int auto_increment
        primary key,
    liquor_description_id int                                null comment 'from liquor description',
    liquor_ml_id          int                                null,
    liquor_bottle_id      int                                null,
    creation_time         datetime default CURRENT_TIMESTAMP null,
    created_by            int                                null
)
    charset = latin1;

create table liquor_month_stock_sales
(
    id                          bigint auto_increment
        primary key,
    entity_id                   int                                null,
    liquor_entity_id            int                                null,
    liquor_description_id       int                                null,
    liquor_opening_qty          float(12, 2)                       null,
    liquor_sale_qty             varchar(45)                        null,
    liquor_unit_purchase_price  float(10, 2)                       null,
    liquor_unit_sell_price      float(10, 2)                       null,
    liquor_unit_profit          int                                null,
    liquor_unit_tax             int                                null,
    liquor_total_purchase_price float(10, 2)                       null,
    liquor_total_sale_price     float(10, 2)                       null,
    liquor_profit               int                                null,
    liquor_balance              int                                null,
    modification_time           datetime                           null,
    modified_by                 int                                null,
    sale_month                  int                                null,
    sale_year                   int                                null,
    insert_date                 date                               null,
    insert_time                 datetime default CURRENT_TIMESTAMP null,
    constraint liquor_stock_sales_ibmfk_1
        foreign key (liquor_entity_id) references liquor_entity_mapping (id)
)
    charset = latin1;

create index liquor_entity_id_idx
    on liquor_month_stock_sales (liquor_entity_id);

create table liquor_order_details
(
    id                          int auto_increment
        primary key,
    cart_id                     int      null,
    liquor_entity_id            int      null,
    quantity                    int      null,
    unit_cost_lot_size          int      null,
    total_cost_bottles          int      null,
    dispatch_quantity           int      null,
    dispatch_cost_lot_size      int      null,
    dispatch_total_cost_bottles int      null,
    recevied_quantity           int      null,
    recevied_cost_lot_size      int      null,
    recevied_total_cost_bottles int      null,
    replace_order_id            bigint   null,
    is_liquor_receive           tinyint  null,
    is_liquor_replace           tinyint  null,
    is_liquor_quatity_change    tinyint  null,
    is_order_cancel             tinyint  null,
    order_time                  datetime null,
    order_by                    bigint   null,
    dispatch_time               datetime null,
    dispatch_by                 bigint   null,
    receive_time                datetime null,
    receive_by                  bigint   null,
    order_cancel_time           datetime null,
    order_cancel_by             int      null,
    order_process               tinyint  null comment '0-cancel,1-order,2-dispatch,3-recevied'
);

create table liquor_rank_quota_mapping
(
    id            int auto_increment
        primary key,
    rankid        int         null,
    quota         int         null,
    created_by    int         null,
    creation_time datetime    null,
    isactive      varchar(45) null
)
    charset = latin1;

create table liquor_stock_added
(
    id                         bigint auto_increment
        primary key,
    entity_id                  int                                null,
    liquor_entity_id           int                                null,
    liquor_description_id      int                                null,
    liquor_qty                 int                                null,
    liquor_opening_qty         float(12, 2)                       null,
    liquor_sale_qty            varchar(45)                        null,
    liquor_unit_base_price     float(10, 2)                       null,
    liquor_unit_purchase_price float(10, 2)                       null,
    liquor_unit_sell_price     float(10, 2)                       null,
    liquor_unit_profit         int                                null,
    insert_date                date                               null,
    insert_time                datetime default CURRENT_TIMESTAMP null,
    inserted_by                int                                null
)
    charset = latin1;

create table liquor_stock_sales
(
    id                          bigint auto_increment
        primary key,
    entity_id                   int         null,
    liquor_entity_id            int         null,
    liquor_description_id       int         null,
    liquor_opening_qty          int         null,
    liquor_sale_qty             varchar(45) null,
    liquor_unit_purchase_price  int         null,
    liquor_unit_sell_price      int         null,
    liquor_unit_profit          int         null,
    liquor_unit_tax             int         null,
    liquor_total_purchase_price int         null,
    liquor_total_sale_price     int         null,
    liquor_profit               int         null,
    liquor_balance              int         null,
    modification_time           datetime    null,
    modified_by                 int         null,
    insert_date                 date        null
);

create table liquor_type
(
    id            int auto_increment
        primary key,
    liquor_type   varchar(50)                        null,
    created_by    int                                null,
    creation_time datetime default CURRENT_TIMESTAMP null
)
    charset = latin1;

create table liquor_user_used_quota
(
    id           int auto_increment
        primary key,
    userid       int               null,
    liquor_count int     default 0 null,
    beer_count   int     default 0 null,
    order_code   varchar(45)       null,
    order_status varchar(45)       null comment '1-orderplace 2-delivered 3-cancelled',
    insert_time  datetime          null,
    created_by   int               null,
    isactive     varchar(45)       null,
    is_beer      tinyint default 0 null comment '0-NOT BEER QUOTA 1-BEER QUOTA'
)
    charset = latin1;

create table log_bsf_hrms_data
(
    id                 int auto_increment
        primary key,
    bsf_hrms_data_id   int                                null,
    irla               int                                null comment 'IRLA Number Of BSF User',
    name               varchar(45)                        null,
    mobile_no          bigint                             null,
    date_of_birth      varchar(45)                        null,
    `rank`             varchar(55)                        not null,
    present_appoitment varchar(45)                        null,
    status             varchar(45)                        null comment '''S'' -> Serving  ''R'' -> Retired',
    location_name      varchar(95)                        null,
    district_name      varchar(95)                        null,
    state_name         varchar(75)                        null,
    email_id           varchar(145)                       null,
    creation_time      datetime default CURRENT_TIMESTAMP not null
)
    comment 'Data dump provided by BSF' charset = latin1;

create table log_bsf_hrms_data2
(
    id                 int auto_increment
        primary key,
    bsf_hrms_data2_id  int          null,
    irla               int          null comment 'IRLA Number Of BSF User',
    name               varchar(45)  null,
    mobile_no          bigint       null,
    date_of_birth      varchar(45)  null,
    `rank`             varchar(55)  null,
    present_appoitment varchar(45)  null,
    status             varchar(45)  null comment '''S'' -> Serving  ''R'' -> Retired',
    location_name      varchar(95)  null,
    district_name      varchar(95)  null,
    state_name         varchar(75)  null,
    email_id           varchar(145) null
)
    comment 'Data dump provided by BSF' charset = latin1;

create table log_cancelled_order
(
    id                int auto_increment
        primary key,
    order_code        varchar(45)                         null,
    cart_id           bigint                              null,
    orderedby_user_id bigint                              null,
    entity_id         int                                 null,
    insert_time       timestamp default CURRENT_TIMESTAMP null
)
    charset = latin1;

create table log_cart_details
(
    id                     int auto_increment
        primary key,
    cart_id                bigint            null,
    order_code             varchar(45)       null,
    liquor_count           int               null,
    ordered_to_entity_id   int               null comment 'THe entity id which will be providing the product',
    ordered_to_entity_type int               null comment 'in order to find to locate table to find the details whether the enity is to retrive from master_brewery or master_entities',
    order_from_id          int               null comment 'the entity that places the order
if order is placed by Consumer than admin_id table
if order is placed as entity then id master  entities table ',
    order_from_entity_id   int     default 0 null,
    order_from_entity_type int               null comment 'fro',
    is_active              tinyint default 1 null comment '1-ACTIVE 0-INACTIVE',
    is_order_placed        tinyint           null,
    is_order_delivered     tinyint           null comment '1-delivered 0-not delivered',
    is_order_cancel        tinyint default 0 null,
    order_by_userid        bigint            null,
    order_time             datetime          null,
    modified_by            int               null,
    modification_time      datetime          null,
    deactivated_by         bigint            null,
    deactivation_time      datetime          null,
    deactivation_mode      varchar(5)        null comment 'A-AUTO M-MANUAL',
    cart_type              varchar(45)       null comment 'E-ENTITY C-Cosumer',
    cart_detailscol        varchar(45)       null,
    cancel_mode            varchar(10)       null comment 'U-USER A-AUTO',
    cancel_time            datetime          null
)
    charset = latin1;

create table log_ci_admin
(
    id                  bigint auto_increment
        primary key,
    admin_id            int                          null,
    admin_role_id       int                          not null,
    entity_id           int                          null,
    username            varchar(100) charset utf8mb3 not null,
    firstname           varchar(255)                 null,
    lastname            varchar(255)                 null,
    email               varchar(255)                 not null,
    mobile_no           varchar(255)                 not null,
    date_of_birth       date                         null,
    image               varchar(300)                 not null,
    password            varchar(255)                 not null,
    last_login          datetime                     not null,
    is_verify           tinyint default 1            not null,
    is_admin            tinyint default 1            not null,
    is_active           tinyint default 0            not null,
    is_supper           tinyint default 0            not null,
    token               varchar(255)                 not null,
    password_reset_code varchar(255)                 not null,
    android_uuid        varchar(255)                 null,
    gcm_id              varchar(255)                 null,
    last_ip             varchar(255)                 not null,
    created_at          datetime                     not null,
    updated_at          datetime                     not null
)
    charset = latin1;

create table log_liquor_brand
(
    id                int auto_increment
        primary key,
    brand             varchar(200) null,
    created_by        bigint       null,
    creation_time     datetime     null,
    modified_by       varchar(255) null,
    modification_time datetime     null,
    brand_id          int          null
);

create table log_liquor_entity_mapping
(
    id                                 int auto_increment
        primary key,
    liquor_entity_id                   int           null,
    liquor_description_id              int           null,
    liquor_type_id                     int           null,
    ml_id                              int           null,
    entity_id                          int           null,
    entity_type                        int           null,
    minimum_order_quantity             int           null,
    sale_minimum_order_quantity        int           null,
    purchase_price                     float(10, 2)  null,
    selling_price                      float(10, 2)  null,
    minimun_order_type                 int           null comment '0-Single_unit 1-lot',
    single_piece_in_lot                int           null comment 'NO of bottles available in one lot',
    available_quantity                 int default 0 not null,
    previous_available_quantity        int           null,
    actual_available_quantity          int           null,
    previous_actual_available_quantity int           null,
    city_id                            int           null,
    state_id                           int           null,
    creation_time                      datetime      null,
    created_type                       int           null,
    modification_time                  datetime      null,
    modified_by                        int           null,
    reorder_level                      int           null,
    updated_from                       varchar(200)  null,
    cart_id                            bigint        null
)
    charset = latin1;

create table log_liquor_stock_sales
(
    id                           bigint auto_increment
        primary key,
    entity_id                    int                                null,
    liquor_entity_id             int                                null,
    liquor_description_id        int                                null,
    liquor_opening_qty           int                                null,
    liquor_sale_qty              varchar(45)                        null,
    liquor_total_sale_quantity   int                                null,
    liquor_unit_purchase_price   int                                null,
    liquor_unit_sell_price       int                                null,
    liquor_unit_profit           int                                null,
    liquor_unit_tax              int                                null,
    liquor_total_purchase_price  int                                null,
    liquor_total_sale_price      int                                null,
    liquor_profit                int                                null,
    liquor_balance               int                                null,
    modification_time            datetime                           null,
    modified_by                  int                                null,
    insert_date                  date                               null,
    insert_time                  datetime default CURRENT_TIMESTAMP null,
    order_code                   varchar(30)                        null,
    liquor_current_sale_quantity int                                null
)
    charset = latin1;

create table log_master_alcohol_type
(
    id                     int auto_increment
        primary key,
    master_alcohol_type_id int                                null,
    alcohol_type           varchar(50)                        null,
    created_by             int                                null,
    creation_time          datetime default CURRENT_TIMESTAMP null
)
    charset = latin1;

create table log_master_city_district
(
    id                      int auto_increment
        primary key,
    master_city_district_id int                                null,
    city_district_name      varchar(500)                       null,
    stateid                 int                                null comment 'From master_state_table',
    creation_time           datetime default CURRENT_TIMESTAMP null,
    created_by              varchar(45)                        null
)
    charset = latin1;

create table log_master_entities
(
    id                                    int auto_increment
        primary key,
    entity_id                             int               null,
    entity_name                           varchar(145)      null,
    address                               varchar(145)      null,
    city                                  varchar(145)      null,
    state                                 varchar(145)      null,
    latitude                              decimal(11, 7)    null,
    longitude                             decimal(11, 7)    null,
    chairman                              int               null,
    supervisor                            int               null,
    executive                             int               null,
    chairman_mailid                       varchar(145)      null,
    supervisor_mailid                     varchar(145)      null,
    executive_mailid                      varchar(145)      null,
    chairman_mobileno                     bigint            null,
    supervisor_mobileno                   bigint            null,
    executive_mobileno                    bigint            null,
    authorised_distributor_entity_type_id int               null,
    authorised_distributor                int               null,
    entity_type                           tinyint default 1 null comment '1-Canteen 2-Club  3-Brewery',
    created_by                            int               null,
    creation_time                         datetime          null,
    modified_by                           int               null,
    modification_time                     datetime          null,
    isactive                              tinyint default 1 null comment '1-active 0-inactive',
    deactive_time                         datetime          null,
    deactivated_by                        int unsigned      null
)
    charset = latin1;

create table log_master_liquor
(
    id               int auto_increment
        primary key,
    master_liquor_id int          null,
    liquor_name      varchar(100) null,
    liquor_image     varchar(45)  null,
    liquor_type      varchar(45)  null,
    creation_time    datetime     null comment 'creation time is the modification time',
    created_by       int          null
);

create table log_master_tax
(
    id              int auto_increment
        primary key,
    master_tax_id   int                                null comment 'from master_tax table',
    tax_name        varchar(100)                       null,
    entity_type     int                                null,
    creation_time   datetime default CURRENT_TIMESTAMP null,
    created_by      int                                null,
    tax_category_id int                                null
)
    charset = latin1;

create table mail_format
(
    ID              int auto_increment
        primary key,
    MESSAGE_NAME    varchar(50)  default 'NULL' null,
    MESSAGE_SUBJECT varchar(255) default 'NULL' null,
    MESSAGE_BODY    text                        null,
    ISACTIVE        int          default 0      null
)
    charset = latin1;

create table mapping_liquor_entity
(
    id                          int auto_increment
        primary key,
    liquor_id                   int      null,
    ml_id                       int      null,
    entity_id                   int      null,
    entity_type                 int      null,
    minimum_order_quantity      int      null,
    sale_minimum_order_quantity int      null,
    purchase_price              int      null,
    selling_price               int      null,
    minimun_order_type          int      null comment '0-Single_unit 1-lot',
    single_piece_in_lot         int      null comment 'NO of bottles available in one lot',
    city_id                     int      null,
    state_id                    int      null,
    creation_time               datetime null,
    created_type                int      null,
    modification_time           datetime null,
    modified_by                 int      null
);

create table master_brewery
(
    id                  int auto_increment
        primary key,
    brewery_name        varchar(100) null,
    address             varchar(255) null,
    contact_person_name varchar(255) null,
    mobile_no           bigint       null,
    mail_id             varchar(255) null,
    state               varchar(255) null,
    serving_entity      varchar(255) null comment 'Serving entity like club/canteen/stockist',
    isactive            varchar(45)  null
)
    charset = latin1;

create table master_city_district
(
    id                 int auto_increment
        primary key,
    city_district_name varchar(500)                       null,
    stateid            int                                null comment 'From master_state_table',
    creation_time      datetime default CURRENT_TIMESTAMP null,
    created_by         varchar(45)                        null
)
    charset = latin1;

create table master_distributor_authority
(
    id                    int          not null
        primary key,
    distributor_authority varchar(100) null,
    details_map_table     varchar(500) null,
    column_name           varchar(150) null
)
    charset = latin1;

create table master_entities
(
    id                                    int auto_increment
        primary key,
    entity_name                           varchar(145)      null,
    battalion_unit                        int               null,
    address                               varchar(145)      null,
    city                                  varchar(145)      null,
    state                                 varchar(145)      null,
    latitude                              decimal(11, 7)    null,
    longitude                             decimal(11, 7)    null,
    chairman                              int               null,
    supervisor                            int               null,
    executive                             int               null,
    chairman_mailid                       varchar(145)      null,
    supervisor_mailid                     varchar(145)      null,
    executive_mailid                      varchar(145)      null,
    chairman_mobileno                     bigint            null,
    supervisor_mobileno                   bigint            null,
    executive_mobileno                    bigint            null,
    authorised_distributor_entity_type_id int     default 0 null,
    authorised_distributor                varchar(255)      null,
    entity_type                           tinyint default 1 null comment '1-Canteen 2-Club  3-Brewery',
    created_by                            int               null,
    creation_time                         datetime          null,
    modified_by                           int               null,
    modification_time                     datetime          null,
    isactive                              tinyint default 1 null comment '1-active 0-inactive',
    deactive_time                         datetime          null,
    deactivated_by                        int               null
)
    charset = latin1;

create table master_entities_change_log
(
    id                     int auto_increment
        primary key,
    entity_id              int               null,
    entity_name            varchar(145)      null,
    address                varchar(145)      null,
    city                   varchar(145)      null,
    state                  varchar(145)      null,
    latitude               decimal(11, 7)    null,
    longitude              decimal(11, 7)    null,
    chairman               int               null,
    supervisor             int               null,
    executive              int               null,
    chairman_mailid        varchar(145)      null,
    supervisor_mailid      varchar(145)      null,
    executive_mailid       varchar(145)      null,
    chairman_mobileno      bigint            null,
    supervisor_mobileno    bigint            null,
    executive_mobileno     bigint            null,
    authorised_distributor int               null,
    authorised_entity      int               null,
    authorised_brewery     int               null,
    entity_type            tinyint default 1 null comment '1-Canteen 2-Club ',
    created_by             int               null,
    creation_time          datetime          null,
    modified_by            int               null,
    modification_time      datetime          null,
    isactive               tinyint default 1 null comment '1-active 0-inactive',
    deactive_time          datetime          null,
    deactivated_by         int               null
)
    charset = latin1;

create table master_entity_type
(
    id          int auto_increment
        primary key,
    entity_type varchar(45) null
)
    charset = latin1;

create table master_liquor
(
    id                int auto_increment
        primary key,
    liquor_name       varchar(100) null,
    liquor_image      varchar(45)  null,
    liquor_type       varchar(45)  null,
    creation_time     datetime     null,
    created_by        int          null,
    modification_time datetime     null,
    modified_by       int          null
);

create table master_liquor_ml
(
    id            int auto_increment
        primary key,
    liquor_ml     int                                null,
    creation_time datetime default CURRENT_TIMESTAMP null,
    created_by    int                                null
)
    charset = latin1;

create table master_liquor_type
(
    id            int auto_increment
        primary key,
    liquor_type   varchar(50)                        null,
    created_by    int                                null,
    creation_time datetime default CURRENT_TIMESTAMP null
)
    charset = latin1;

create table master_product
(
    id            int auto_increment
        primary key,
    product_name  varchar(100) null,
    product_image varchar(45)  null,
    product_type  varchar(45)  null,
    creation_time datetime     null,
    created_by    int          null
);

create table master_rank
(
    id       int auto_increment
        primary key,
    `rank`   varchar(75) null,
    isactive varchar(45) null
)
    charset = latin1;

create table master_state
(
    id    int auto_increment
        primary key,
    state varchar(225) null
)
    charset = latin1;

create table master_sub_rank
(
    id       int auto_increment
        primary key,
    sub_rank varchar(75) null,
    isactive varchar(45) null
)
    charset = latin1;

create table master_tax
(
    id              int auto_increment
        primary key,
    tax_name        varchar(100)                       null,
    entity_type     int                                null,
    creation_time   datetime default CURRENT_TIMESTAMP null,
    created_by      int                                null,
    tax_category_id int                                not null
)
    charset = latin1;

create table master_tax_liquor_mapping
(
    id                    int auto_increment
        primary key,
    entity_id             int                                null,
    liquor_description_id int                                null,
    tax_id                int                                null,
    tax_percent           float(15, 2)                       null,
    created_by            int                                null,
    created_on            datetime default CURRENT_TIMESTAMP null,
    modified_by           int                                null,
    modified_on           datetime default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    isactive              tinyint  default 1                 null,
    tax_type_id           int                                null,
    tax_category          varchar(50)                        not null
)
    comment 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP' charset = latin1;

create table mess_type
(
    id        int auto_increment
        primary key,
    mess_type varchar(50) null
)
    charset = latin1;

create table mess_types
(
    id        int auto_increment
        primary key,
    mess_type varchar(50) null
)
    charset = latin1;

create table mobile_login_details
(
    id            int auto_increment
        primary key,
    data_passed   longtext                              null,
    creation_time datetime    default CURRENT_TIMESTAMP null,
    action_mode   varchar(40) default 'LOGIN'           null
)
    charset = latin1;

create table module
(
    module_id       int auto_increment
        primary key,
    module_name     varchar(255) not null,
    controller_name varchar(255) not null,
    fa_icon         varchar(100) not null,
    operation       text         not null,
    sort_order      tinyint      not null,
    roleid          int          null
)
    charset = latin1;

create table module_access
(
    id            int auto_increment
        primary key,
    admin_role_id int          not null,
    module        varchar(255) not null,
    operation     varchar(255) not null
)
    charset = latin1;

create index RoleId
    on module_access (admin_role_id);

create table new_available_stock
(
    id               int auto_increment
        primary key,
    entity_id        int          null,
    liquor_entity_id int          null,
    invoice_no       varchar(55)  null,
    liquor_name      varchar(55)  null,
    selling_price    varchar(255) null,
    new_stock        varchar(50)  null,
    available_stock  varchar(50)  null,
    total            varchar(55)  null,
    created_by       varchar(55)  null,
    creation_time    varchar(55)  null
)
    charset = latin1;

create table new_table
(
    id                     int auto_increment
        primary key,
    cart_code              varchar(100)                       not null,
    created_by             varchar(45)                        null,
    creation_time          datetime default CURRENT_TIMESTAMP null,
    is_cart_active         tinyint                            null comment '1-active 0-deactivate',
    is_order_placed        tinyint                            null comment '1-order placed 0-order pending',
    cart_deactivation_mode varchar(4)                         null comment 'A-auto U-User',
    deactivation_time      datetime                           null,
    deactivated_by         bigint                             null
)
    charset = latin1;

create table nok_details
(
    id                int auto_increment
        primary key,
    hrms_id           int          null,
    name              varchar(200) null,
    nok_name          varchar(100) null,
    adhaar_card       varchar(200) null,
    date_of_birth     varchar(55)  null,
    relationship_type varchar(50)  null,
    email             varchar(50)  null,
    mobile_no         varchar(50)  null,
    `rank`            varchar(55)  null,
    personnel_photo   varchar(200) null,
    aadhar_card_photo varchar(200) null,
    card_photo        varchar(200) null,
    signed_form_photo varchar(200) null,
    modification_time datetime     null,
    modified_by       int          null,
    created_by        int          null,
    creation_time     datetime     null,
    is_active         int          null
)
    charset = latin1;

create table nok_relationship
(
    id                int auto_increment
        primary key,
    relationship_type varchar(50) null
)
    charset = latin1;

create table order_code_user_details
(
    ID          int          not null
        primary key,
    cart_id     int          null,
    userid      varchar(45)  null,
    order_code  varchar(45)  null,
    insert_mode varchar(145) null,
    insert_time varchar(45)  null,
    create_code varchar(45)  null
)
    charset = latin1;

create table order_details
(
    id                          int auto_increment
        primary key,
    cart_id                     int                     null,
    liquor_entity_id            int                     null,
    quantity                    int                     null,
    unit_cost_lot_size          int                     null,
    total_cost_bottles          int                     null,
    dispatch_quantity           int                     null,
    dispatch_cost_lot_size      int                     null,
    dispatch_total_cost_bottles int                     null,
    recevied_quantity           int                     null,
    recevied_cost_lot_size      int                     null,
    recevied_total_cost_bottles int                     null,
    replace_order_id            bigint                  null,
    is_liquor_receive           tinyint                 null,
    is_liquor_replace           tinyint                 null,
    is_liquor_quatity_change    tinyint                 null,
    is_order_cancel             tinyint                 null,
    order_time                  datetime                null,
    order_by                    bigint                  null,
    dispatch_time               datetime                null,
    dispatch_by                 bigint                  null,
    receive_time                datetime                null,
    receive_by                  bigint                  null,
    order_cancel_time           datetime                null,
    order_cancel_by             int                     null,
    order_process               tinyint                 null comment '0-cancel,1-order,2-dispatch,3-recevied',
    is_liquor_added             tinyint     default 0   null comment 'new liquor is added using delivery cart page',
    liquor_added_by             bigint                  null,
    liquor_add_time             datetime                null,
    is_liquor_removed           int         default 0   null,
    liquor_removed_by           int                     null,
    liquor_removal_time         datetime                null,
    liquor_removal_mode         varchar(45)             null comment 'P-PURCHASER,E-ENTITY',
    order_detailscol            varchar(45) default 'P' null
);

create table otp_log
(
    id        int auto_increment
        primary key,
    otp_code  int          null,
    email_id  varchar(255) null,
    mobile_no bigint       null,
    irla_no   int          null,
    isactive  varchar(45)  null
)
    charset = latin1;

create table posting_unit
(
    id            int                                not null
        primary key,
    posting_unit  varchar(60)                        null,
    capf_force    int                                null,
    created_by    int                                null,
    creation_time datetime default CURRENT_TIMESTAMP null
)
    charset = latin1;

create table retiree_verification_details
(
    id                 int auto_increment
        primary key,
    hrms_id            int                                null,
    approval_by        int                                null,
    entity_id          int                                null,
    requested_by       int                                null,
    requested_time     datetime default CURRENT_TIMESTAMP null,
    retiree_valid_upto date                               null,
    is_registered      int      default 0                 null,
    is_verified        int      default 0                 null,
    isactive           int                                null,
    is_nok             int                                null,
    verification_time  datetime                           null,
    action             int      default 0                 null,
    denial_remarks     varchar(100)                       null,
    modified_by        int                                null,
    modification_time  datetime                           null
)
    charset = latin1;

create table sms_log
(
    ID                bigint auto_increment
        primary key,
    SMS_CODE          varchar(5)        null,
    MOBILENO          varchar(200)      null,
    MSG               longtext          null,
    IS_SMS_SENT       enum ('N', 'Y')   null,
    IS_SMS_DELIVERED  enum ('N', 'Y')   null,
    INSERT_TIME       datetime          not null,
    SMS_RESPONSE      longtext          null,
    SMS_RESPONSE_TIME datetime          null,
    SMS_ATTEMPT       tinyint default 0 null
)
    charset = latin1;

create table sp_error_log_data
(
    id                int auto_increment
        primary key,
    page_name         varchar(100)                       null,
    sp_name           varchar(100)                       null,
    data_passed       text                               null,
    error_message     text                               null,
    returne_sql_state varchar(200)                       null,
    error_time        datetime default CURRENT_TIMESTAMP null
)
    charset = latin1;

create table states_liquor_margin
(
    id           int auto_increment
        primary key,
    stateid      int            null,
    liquortypeid int            null,
    amount       decimal(15, 2) null
)
    charset = latin1;

create table states_taxes_mapping
(
    id          int auto_increment
        primary key,
    stateid     int            null,
    taxid       int            null,
    tax_percent decimal(15, 2) null,
    insert_time datetime       null,
    created_by  int            null,
    modified_by varchar(45)    null,
    isactive    varchar(45)    null
)
    charset = latin1;

create table sub_module
(
    id          int auto_increment
        primary key,
    parent      int          not null,
    name        varchar(255) not null,
    link        varchar(255) not null,
    mobile_link varchar(255) null,
    sort_order  int          not null
)
    charset = latin1;

create index `Parent Module ID`
    on sub_module (parent);

create table tax_category
(
    id           int auto_increment
        primary key,
    tax_category varchar(50)       not null,
    created_by   varchar(50)       null,
    created_at   datetime          null,
    is_active    tinyint default 1 null
)
    charset = latin1;

create table user_login_details
(
    id               int auto_increment
        primary key,
    username         int          null,
    login_time       datetime     null,
    logout_time      datetime     null,
    password         varchar(250) null,
    browser_agent    varchar(250) null,
    browser_name     varchar(250) null,
    browser_platform varchar(250) null,
    browser_version  varchar(250) null,
    ip_address       varchar(250) null,
    login_status     varchar(150) null
)
    charset = latin1;

create table user_token
(
    id          int auto_increment
        primary key,
    token       varchar(150) null,
    username    varchar(50)  null,
    insert_time datetime     null
)
    charset = latin1;

