<!DOCTYPE html>
<html lang="en">
<head>
    <title>Vendor Payment Tracking</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <div style="text-align: center;background: #ffffff;padding: 10px;margin-bottom: 10px">
        <a href="" style="text-decoration: none">
            <img src="<?php echo base_url('assets/'); ?>/images/logo.png" alt="" height="70px">
            <span style="font-size: 18px;margin-left: 10px;color: black;"><?php echo $report['one'][0]['CompanyName']; ?></span>
        </a>
        <p style="color: black;margin-top: 10px">ACI Centre 245, Tejgaon Industrial Area, Dhaka-1208, Bangladesh.</p>
    </div>
<!--    <div class="panel panel-default">-->
<!--        <form action="--><?php //echo base_url('/'); ?><!--" method="post">-->
<!--            <div class="panel-body">-->
<!--                <div class="row">-->
<!--                    <div class="col-md-6">-->
<!--                        <div class="form-group">-->
<!--                            <input type="text" class="form-control" name="TrackingID" -->
<!--                                value="--><?php //echo isset($tracking_id) ? $tracking_id : '' ?><!--" placeholder="Enter TrackingID"-->
<!--                                autocomplete="off" required="">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-2">-->
<!--                        <div class="form-group">-->
<!--                            <button type="submit" value="Submit" class="btn btn-success">Submit</button>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </form>-->
<!--    </div>-->

<?php
if($report == false && !empty($tracking_id)){
    echo "<font class='text-danger bold font-size-25'>Invalid tracking number.</font>";
    return;
}
if (isset($report)) { ?>

    <div style="text-align: center">
        <h2>Statement of payment</h2>
<!--        <p>Details of Bill</p>-->
    </div>
    <form action="<?php echo base_url('vendorpayment/paymenttrackingprint'); ?>" method="post" target="_blank">
        <input type="hidden" value="<?php echo $tracking_id; ?>" name="TrackingID">
        <button type="submit" class="btn btn-success">Print PDF</button>
    </form>
    <div class="row">
        <div class="col-md-8">
            <p>Business : <?php echo $report['two'][0]['Business']; ?></p>
        </div>
        <div class="col-md-4">
            <p style="text-align: right">Issue Date : <?php echo date('Y-m-d'); ?></p>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>MID</th>
                        <td><?php echo $report['two'][0]['MATPlanVendorID']; ?></td>
                        <th class="text-left">GID</th>
                        <td><?php echo $report['one'][0]['VendorId']; ?></td>
                    </tr>
                    <tr>
                        <th>Vendor Name</th>
                        <td><?php echo $report['one'][0]['VendorName']; ?></td>
                        <th class="text-left bold">Voucher Ref.</th>
                        <td class="text-left">Date</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo $report['one'][0]['VendorAddress']; ?></td>
                        <th class="text-left bold"><?php echo $report['two'][0]['JVNo']; ?></th>
                        <td class="text-left"><?php echo $report['two'][0]['JVDate']; ?></td>
                    </tr>
                    <tr>
                        <th>BIN</th>
                        <td><?php echo $report['one'][0]['VATRegNo']; ?></td>
                        <th class="text-left bold"><?php echo $report['one'][0]['JVNo']; ?></th>
                        <td class="text-left"><?php echo $report['one'][0]['JVDate']; ?></td>
                    </tr>
                    <tr>
                        <th>TIN</th>
                        <td><?php echo $report['one'][0]['TINNo']; ?></td>
                        <th class="text-left"></th>
                        <td class="text-left"></td>
                    </tr>
                    <tr>
                        <th>SMS Sent To</th>
                        <td><?php echo $report['one'][0]['VendorTelephone']; ?></td>
                        <th class="text-left bold">Time</th>
                        <td class="text-left"><?php echo $report['two'][0]['SMSDate']; ?></td>
                    </tr>
                </tbody>
            </table>
            </div>
            <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th scope="col" class="text-center">SL #</th>
                    <th scope="col" class="text-center">Bill No.</th>
                    <th scope="col" class="text-center">Bill Date</th>
                    <th scope="col" class="text-center">Bill Submission Date</th>
                    <th scope="col" class="text-center">Bill Tracking No.</th>
                    <th scope="col" class="text-center">Bill Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $total_bill_amount = 0;
                $total_vat_amount = 0;
                $total_tax_perc = 0;
                $total_tax_amount = 0;
                $total_AdvanceAmount = 0;
                $total_SecurityDeposit = 0;
                foreach ($report['one'] as $key => $DS1){
                    $total_AdvanceAmount += $DS1['AdvanceAmount'];
                    $total_SecurityDeposit += $DS1['SecurityDeposit'];

                     if ($DS1['TransType'] == 1) {
                        $total_bill_amount += $DS1['BillAmount'];
                        $total_vat_amount += $DS1['VATAmount'];
                        $total_tax_perc += $DS1['TaxPerc'];
                        $total_tax_amount += $DS1['TaxAmount'];
                ?>
                    <tr>
                        <th scope="row" class="text-center"><?php echo ++$key; ?></th>
                        <td class="text-center"><?php echo $DS1['BillNo']; ?></td>
                        <td class="text-center"><?php echo $DS1['BillDate']; ?></td>
                        <td class="text-center"><?php echo $DS1['BillSubDate']; ?></td>
                        <td class="text-center"><?php echo $DS1['ACIBillNo']; ?></td>
                        <td class="text-right"><?php echo number_format($DS1['BillAmount'],2); ?></td>
                    </tr>
                <?php
                    }elseif($DS1['TransType'] == 5){
                         $taxamount =$DS1['TaxAmount'];
                         $taxpercentage =$DS1['TaxPerc'];
                     }

                 }
                ?>

                <tr>
                    <th colspan="5">Total Amount:</th>
                    <td class="text-right"><?php echo number_format($total_bill_amount,2);?></td>
                </tr>
                <tr>
                    <th colspan="5">Advance Adjustment:</th>
                    <td class="text-right"><?php echo number_format($total_AdvanceAmount,2);?></td>
                </tr>
                <tr>
                    <th colspan="5">Security Money Adjustment:</th>
                    <td class="text-right"><?php echo number_format($total_SecurityDeposit,2);?></td>
                </tr>
                <tr>
                    <th colspan="5">VAT Deduction:</th>
                    <td class="text-right"><?php echo number_format($total_vat_amount,2);?></td>
                </tr>
                <tr>
                    <th colspan="5">Tax Deduction @ <?php echo $taxpercentage; ?> %</th>
                    <td class="text-right"><?php echo number_format(($taxamount),2);?></td>
                </tr>
                <tr>
                    <th colspan="5">Net Amount:</th>
                    <td class="text-right">
                        <?php
                            //$total_tax = $total_tax_perc + $total_tax_amount;
                            $net_amount = $total_bill_amount - ($taxamount + $total_vat_amount+$total_AdvanceAmount+$total_SecurityDeposit);
                            echo number_format($net_amount,2);
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <tbody>
                        <tr>
                            <th>1</th>
                            <td colspan="6">The net amount has been transmitted to your designated Bank account. Please inform us immediately if you are yet
                                to realize the amount or if you have any issue whatever.
                            </td>
                        </tr>
                        <tr>
                            <th>2</th>
                            <td colspan="6">Any query related to Tax and VAT, please contact respective department.</td>
                        </tr>
                        <tr>
                            <th>3</th>
                            <td colspan="6">For any issue related to bill, please contact <?php echo $report['two'][0]['PreparedBy']; ?>.</td>
    <!--                        <p>Commercial/SCM</p>-->
                        </tr>
                        <tr>
                            <th>4</th>
                            <td colspan="6">Please take a print of this copy before expiry.</td>
                        </tr>
                    </tbody>
                </table>
                <!--    <p>Bill Prepared by : --><?php //echo $report['two'][0]['PreparedBy']; ?><!--</p>-->
                <p style="font-size: 12px;font-weight: bold">Disclaimer :</p>
                <p style="font-size: 12px">This computer generated statement issued for information only and it contains no evidentiary value. For final confirmation and official document, please contact with the concerned.</p>
            </div>
        </div>
    </div>
</div>
<?php
}
?>

</body>
</html>
