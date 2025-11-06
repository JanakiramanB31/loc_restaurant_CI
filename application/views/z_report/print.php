<?php
    $paperWidth = "300px";
    $startDate = isset($from_date) ? date('d-m-Y', strtotime($from_date)) : date('d-m-Y');
    $endDate = isset($to_date) ? date('d-m-Y', strtotime($to_date)) : date('d-m-Y');
?>

<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row" id="zReport">
    <style>
      .currency {
        margin-right: 5px;
        float: right;
      }
      .boldfont {
        font-weight: bolder;
      }
      th, td {
        font-weight: bolder;
      }
      body {
        font-family: Arial, sans-serif;
      }
      table {
        width: 100%;
        border-collapse: collapse;
        font-size: 16px;
      }
      th, td {
        padding: 4px 2px;
        font-size: 16px;
      }
      .print-header {
        text-align: center;
        margin-bottom: 20px;
      }
      .date-inline {
        display: inline-block;
        margin: 0 10px;
      }
    </style>
    <div class="col-lg-12" style="width: <?php echo $paperWidth; ?>; text-align:center;">
      <div class="ibox float-e-margins">
        <div class="ibox-content">
          <div class="hr-line-dashed"></div>
          <div class="print-header" style="width: 100%;">
            <h3><span style="text-align: center;" class="boldfont">Z Report</span></h3>
            <h5><span style="text-align: center;" class="boldfont">Taken: <?php 
                date_default_timezone_set('Asia/Kolkata');
                echo date('d-m-Y h:i a');
            ?></span></h5>
            <hr />
            <?php if ($startDate == $endDate): ?>
            <div class="d-flex align-items-space-between justify-content-space-between" class="boldfont">
              <b style="font-size: 16px;" class="boldfont">Date: <?php echo $startDate; ?></b>
            </div>
            <?php else: ?>
            <div style="text-align: center;">
              <span class="date-inline"><b style="font-size: 16px;" class="boldfont">From: <?php echo $startDate; ?></b></span>
              <span class="date-inline"><b style="font-size: 16px;" class="boldfont">To: <?php echo $endDate; ?></b></span>
            </div>
            <?php endif; ?>
            <div style="text-align: left;">
              <hr/>
            </div>
            <div class="hr-line-dashed"></div>
            <table class="table" style="width: <?php echo $paperWidth; ?>; text-align: left;">
              
              <tr>
                <th>No of Sales: </th>
                <td class="currency"><?php echo isset($totalSalesCount) ? $totalSalesCount : '0'; ?></td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              <tr>
                <th>No. of Table Sales: </th>
                <td class="currency"><?php echo isset($dineInSalesCount) ? $dineInSalesCount : '0'; ?></td>
              </tr>
              <tr>
                <th>No. of Takeaway Sales:</th>
                <td class="currency"><?php echo isset($takeawaySalesCount) ? $takeawaySalesCount : '0'; ?></td>
              </tr>

              
              <tr>
                <th>No. of Cash Sales: </th>
                <td class="currency"><?php echo isset($cashSalesCount) ? $cashSalesCount : '0'; ?></td>
              </tr>
              <tr>
                <th>No. of Card Sales:</th>
                <td class="currency"><?php echo isset($cardSalesCount) ? $cardSalesCount : '0'; ?></td>
              </tr>

              <tr>
                <td colspan="2"><hr/></td>
              </tr>
              
              <tr>
                <th>Total Table Sales:</th>
                <td class="currency"><?php echo isset($currency) ? html_entity_decode($currency, ENT_QUOTES, 'UTF-8') : '£'; ?> <?php echo isset($dineInSalesAmount) && isset($decimalLength) ? number_format($dineInSalesAmount, $decimalLength) : '0.00'; ?></td>
              </tr>
              <tr>
                <th>Total Takeaway Sales: </th>
                <td class="currency"><?php echo isset($currency) ? html_entity_decode($currency, ENT_QUOTES, 'UTF-8') : '£'; ?> <?php echo isset($takeawaySalesAmount) && isset($decimalLength) ? number_format($takeawaySalesAmount, $decimalLength) : '0.00'; ?></td>
              </tr>
              <tr>
                <td colspan="2"><hr/></td>
              </tr>

              <!-- <tr>
                <th>No. of Paid Sales:</th>
                <td class="currency"><?php echo isset($paidSalesCount) ? $paidSalesCount : '0'; ?></td>
              </tr>
              <tr>
                <th>No. of Unpaid Sales:</th>
                <td class="currency"><?php echo isset($unpaidSalesCount) ? $unpaidSalesCount : '0'; ?></td>
              </tr> -->
             <!--  <tr>
                <td colspan="2"><hr/></td>
              </tr> -->

               <tr>
                <th>Total Cash Sales:</th>
                <td class="currency"><?php echo isset($currency) ? html_entity_decode($currency, ENT_QUOTES, 'UTF-8') : '£'; ?> <?php echo isset($cashSalesAmount) && isset($decimalLength) ? number_format($cashSalesAmount, $decimalLength) : '0.00'; ?></td>
              </tr>
              <tr>
                <th>Total Card Sales:</th>
                <td class="currency"><?php echo isset($currency) ? html_entity_decode($currency, ENT_QUOTES, 'UTF-8') : '£'; ?> <?php echo isset($cardSalesAmount) && isset($decimalLength) ? number_format($cardSalesAmount, $decimalLength) : '0.00'; ?></td>
              </tr>

              <!-- <tr>
                <th>Total Paid Sales:</th>
                <td class="currency"><?php echo isset($currency) ? html_entity_decode($currency, ENT_QUOTES, 'UTF-8') : '£'; ?> <?php echo isset($paidSalesAmount) && isset($decimalLength) ? number_format($paidSalesAmount, $decimalLength) : '0.00'; ?></td>
              </tr>
              <tr>
                <th>Total Unpaid Sales:</th>
                <td class="currency"><?php echo isset($currency) ? html_entity_decode($currency, ENT_QUOTES, 'UTF-8') : '£'; ?> <?php echo isset($unpaidSalesAmount) && isset($decimalLength) ? number_format($unpaidSalesAmount, $decimalLength) : '0.00'; ?></td>
              </tr> -->
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
             
              <tr>
                <th>Total Sales:</th>
                <td class="currency"><?php echo isset($currency) ? html_entity_decode($currency, ENT_QUOTES, 'UTF-8') : '£'; ?> <?php echo isset($totalAmount) && isset($decimalLength) ? number_format($totalAmount, $decimalLength) : '0.00'; ?></td>
              </tr>
              <!-- <tr>
                <td colspan="2" class="boldfont"><hr style="border: 1px solid #000;"/></td>
              </tr> -->
              <!-- <tr>
                <th style="font-size: 16px;">Net Amount: </th>
                <td class="currency" style="font-size: 16px;">
                <?php echo isset($currency) ? html_entity_decode($currency, ENT_QUOTES, 'UTF-8') : '£'; ?> <?php echo isset($totalNetAmount) && isset($decimalLength) ? number_format($totalNetAmount, $decimalLength) : '0.00'; ?>
                </td>
              </tr>
              <tr>
                <td colspan="2" class="boldfont"><hr style="border: 1px solid #000;"/></td>
              </tr>
              <tr>
                <th>Cash in Hand:</th>
                <td class="currency"><?php echo isset($currency) ? html_entity_decode($currency, ENT_QUOTES, 'UTF-8') : '£'; ?> <?php echo isset($paidSalesAmount) && isset($decimalLength) ? number_format($paidSalesAmount, $decimalLength) : '0.00'; ?></td>
              </tr> -->
              <tr>
                <td colspan="2"><hr/></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="hidden-print" style="margin-left:10px; text-align: center; width: <?php echo $paperWidth; ?>;">
  <button class="btn btn-primary printbutton" id="print">Print</button>
  <button><a class="btn btn-primary nextbutton" style="text-decoration: none;color: #000;" href="<?php echo base_url('z_report'); ?>"><i class="fa fa-plus"></i> Close</a></button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    
    $('.printbutton').on('click', function () {
      printAndClear();
    });

    function printAndClear() {
      printDiv();
      
      setTimeout(function() {
        $.ajax({
          url: '<?php echo base_url('z_report/clearPaidOrders'); ?>',
          type: 'POST',
          data: {
            from_date: '<?php echo isset($from_date) ? $from_date : date('Y-m-d'); ?>',
            to_date: '<?php echo isset($to_date) ? $to_date : date('Y-m-d'); ?>'
          },
          dataType: 'json',
          success: function(response) {
            if(response.success) {
              console.log(response.message);
            } else {
              alert('Error: ' + response.message);
            }
          },
          error: function() {
            alert('Error occurred while clearing paid orders. Please try again.');
          }
        });
      }, 2000);
    }

    function printDiv() {
      console.log("Printing Z-Report")
      var content = $('#zReport').html();
      var printWindow = window.open('', '', 'height=800,width=600');
      printWindow.document.write('<html><head><title>Z-Report Print</title>');
      printWindow.document.write('<style>'); 
      printWindow.document.write('.currency { margin-right: 5px; float: right; }');
      printWindow.document.write('.boldfont { font-weight: bolder; }');
      printWindow.document.write('th, td { font-weight: bolder; padding: 4px 2px; font-size: 14px; }');
      printWindow.document.write('body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; }');
      printWindow.document.write('table { width: 100%; border-collapse: collapse; font-size: 14px; line-height: 1.6; }');
      printWindow.document.write('.net-amount-row { border-top: 2px solid #000; border-bottom: 2px solid #000; font-weight: bold; }');
      printWindow.document.write('.date-inline { display: inline-block; margin: 0 10px; }');
      printWindow.document.write('hr { margin: 5px 0; }');
      printWindow.document.write('</style>'); 
      printWindow.document.write('</head><body><center>');
      printWindow.document.write(content);
      printWindow.document.write('</center></body></html>');
      printWindow.document.close();
      printWindow.print();
    }

  });
</script> 