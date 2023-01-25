<?php
extract($brewerysummary[0]);
?>
    <style>
        table {
            margin: 0 auto;
            font-size: large;
            border: 1px solid rgb(230, 9, 9);
        }

        h1 {
            text-align: center;
            color: #000000;
            font-size: xx-large;
            font-family: 'Gill Sans', 'Gill Sans MT',
                ' Calibri', 'Trebuchet MS', 'sans-serif';
        }

        td {
            background-color: #f9f9f9;
            border: 1px solid black;
        }

        th,
        td {
            font-weight: bold;
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }

        td {
            font-weight: lighter;
        }

        h3 {
            text-align: right;
        }
    </style>
</head>
<section class="content">
    <div class="card card-default">
        <div class="card-header">
            <div class="d-inline-block">
                <h3 class="card-title"> <i class="fa fa-plus"></i>
                    Order to Brewery Print View
                </h3>
            </div>
        </div>
</section>
<body>
    <div style="margin-top: 50px;text-align:center">
        <h5>DTE General</h5>
        <h5>Block 2 CGO Complex</h5>
    </div>

    <div style="margin-left: 40px;">
        <h5>No:-<?php echo $brewerysummary[0]["brewery_order_code"];?></h5>
    </div>

    <div style="margin-left: 800px; margin-top: -30px;">
        <h5>Date:-<?php echo date("Y-m-d H:i:s") ;?></h5>
    </div>

    <div style="margin-left: 40px;">
        To,<br>Mr.Pernod Recard India(P)
    </div>
    <div style="margin-left: 40px;">
        <b>Subject:</b>Regarding Supply Order
    </div>

    <div style="margin-top: 40px; margin-left: 40px; ">
        Approval of the competent authority has been obtained for purchase of followingquantity/brand of IMFL/FL/Beer
        products from your firm
    </div>
    <br>
    <table class="table table-striped" style="width: auto;">
        <thead bgcolor="grey" class="m1 mb3">
            <th style="width:150px;">Sr.No</th>
            <th style="border-color: #007bff">Brewery Name</th>
            <th style="border-color: #007bff">Order Code</th>
            <th style="border-color: #007bff">Requested By</th>
            <th style="border-color: #007bff">Requested Date</th>
            <th style="border-color: #007bff">Approved By</th>
            <th style="border-color: #007bff">Approval Date</th>
            <th style="border-color: #007bff">Status</th>
        </thead>
        <?php
            $row_count = 1;
            foreach ($brewerysummary as $row) {
        ?>
        <tr>
            <td><?php echo $row_count; ?>.</td>
            <td><?php echo $row["brewery_name"]; ?></td>
            <td><?php echo $row["brewery_order_code"]; ?></td>
            <td><?php echo $row["requested_by"]; ?></td>
            <td><?php echo $row["creation_time"]; ?></td>
            <td><?php echo $row["approved_by"]; ?></td>
            <td><?php echo $row["approved_time"]; ?></td>
            <td><?php echo $row["approval_status"];  ?></td>
        </tr>
        <?php 
            $row_count++;
        } ?>  
    </table>
    <br>
    <div style="margin-left: 40px;">
        <ol>
            <li>1. Please collect above four numbers of impory permits from the commissioner of prohibition and Excise Department
            for early supply as per the terms and condition of liquor brand as per the permits</li>
            <li>2. PRINTED IN RED COLOUR ON THE LABEL" FOR SALE SERVICE AND RETIRED CAPF PERSONALL ONLY"</li>
            <li>3. Invoice should be in favor of <b>'HOO/Comdt,'Block 2, CGO Complex'</b></li>
            <li>4. The bill/invoice in four copies after payment action duly signed Re1/- Revenue stamp may also be forwarded to
            this office . The payments are to be made to your firm directly into the bank account through RTGS/ECS</li>
            <li>5. F.O.R at <b>'HOO/Comdt,'Block 2, CGO Complex'</b></li>
            <li>6. Above goods are supplies within 30 Days</li>
            <li>7. Supplier will responsible for any Breakage leakage</li>
    </ol>
    </div>

    <div style="margin-left: 800px;">
        <b>Officer-In-charge<br>Dte Gen</b><br>Indo tibetian border police
    </div>