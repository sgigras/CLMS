create definer = itbp_final_user@`%` view itbp_clds.liquor_details as
select `ld`.`id`                 AS `liquor_description_id`,
       `ld`.`liquor_description` AS `liquor_description`,
       `lb`.`brand`              AS `brand`,
       `lt`.`liquor_type`        AS `liquor_type`,
       `lbs`.`bottle_size`       AS `bottle_size`,
       `lm`.`liquor_ml`          AS `liquor_ml`,
       `ld`.`liquor_image`       AS `liquor_image`
from ((((`itbp_clms`.`liquor_description` `ld` join `itbp_clms`.`liquor_brand` `lb`
         on ((`lb`.`id` = `ld`.`liquor_brand_id`))) join `itbp_clms`.`liquor_type` `lt`
        on ((`lt`.`id` = `ld`.`liquor_type_id`))) join `itbp_clms`.`liquor_ml` `lm`
       on ((`lm`.`id` = `ld`.`liquor_ml_id`))) join `itbp_clms`.`liquor_bottle_size` `lbs`
      on ((`lbs`.`id` = `ld`.`liquor_bottle_size_id`)));

