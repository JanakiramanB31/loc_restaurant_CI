  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-file-text"></i> X-Report
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Reports</a></li>
        <li class="active">X-Report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            
            <!-- Alert Error Section -->
            <div id="alert-message" class="alert alert-danger" role="alert" style="display:none;"></div>
            
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <label for="startDate">Date :</label>
                  <div class="row">
                    <div class="col-md-5 mb-2">
                      <input id="startDate" name="startDate" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"/>
                    </div>
                    <div class="col-md-1 d-flex justify-content-center align-items-center">
                      <p class="text-center" style="margin-top: 8px;">To</p>
                    </div>
                    <div class="col-md-5 mb-2">
                      <input id="endDate" name="endDate" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"/>
                    </div>
                    <div class="col-md-1">
                      <button id="date-submit" class="btn btn-success">View</button>
                    </div>
                  </div>
                  <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12">
                      <button class="btn btn-success" id="print">Print</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="table-responsive" style="margin-top: 20px;">
                <table class="table table-hover table-bordered" id="sampleTable">
                  <thead>
                    <tr>
                      <th><b>Title</b></th>
                      <th><b>Qty</b></th>
                      <th><b>Title</b></th>
                      <th><b>Amount</b></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><b>No. of Cash Sales</b></td>
                      <td id="cashSalesCount"><?php echo isset($cashSalesCount) ? $cashSalesCount : '0'; ?></td>
                      <td><b>Amount of Cash Sales</b></td>
                      <td><p class="text-right" id="cashSalesAmount"><span><?php echo isset($currency) ? html_entity_decode($currency, ENT_QUOTES, 'UTF-8') : '$'; ?></span> <?php echo isset($cashSalesAmount) && isset($decimalLength) ? number_format($cashSalesAmount, $decimalLength) : '0.00'; ?></p></td>
                    </tr>
                    <tr>
                      <td><b>No. of Card Sales</b></td>
                      <td id="cardSalesCount"><?php echo isset($cardSalesCount) ? $cardSalesCount : '0'; ?></td>
                      <td><b>Amount of Card Sales</b></td>
                      <td><p class="text-right" id="cardSalesAmount"><span><?php echo isset($currency) ? html_entity_decode($currency, ENT_QUOTES, 'UTF-8') : '$'; ?></span> <?php echo isset($cardSalesAmount) && isset($decimalLength) ? number_format($cardSalesAmount, $decimalLength) : '0.00'; ?></p></td>
                    </tr>
                    <tr>
                      <td><b>No. of Table Sales</b></td>
                      <td id="dineInSalesCount"><?php echo isset($dineInSalesCount) ? $dineInSalesCount : '0'; ?></td>
                      <td><b>Amount of Table Sales</b></td>
                      <td><p class="text-right" id="dineInSalesAmount"><span><?php echo isset($currency) ? html_entity_decode($currency, ENT_QUOTES, 'UTF-8') : '$'; ?></span> <?php echo isset($dineInSalesAmount) && isset($decimalLength) ? number_format($dineInSalesAmount, $decimalLength) : '0.00'; ?></p></td>
                    </tr>
                    <tr>
                      <td><b>No. of Takeaway Sales</b></td>
                      <td id="takeawaySalesCount"><?php echo isset($takeawaySalesCount) ? $takeawaySalesCount : '0'; ?></td>
                      <td><b>Amount of Takeaway Sales</b></td>
                      <td><p id="takeawaySalesAmount" class="text-right"><span><?php echo isset($currency) ? html_entity_decode($currency, ENT_QUOTES, 'UTF-8') : '$'; ?></span> <?php echo isset($takeawaySalesAmount) && isset($decimalLength) ? number_format($takeawaySalesAmount, $decimalLength) : '0.00'; ?></p></td>
                    </tr>
                    <!-- <tr>
                      <td><b>No. of Paid Sales</b></td>
                      <td id="paidSalesCount"><?php echo isset($paidSalesCount) ? $paidSalesCount : '0'; ?></td>
                      <td><b>Amount of Paid Sales</b></td>
                      <td><p id="paidSalesAmount" class="text-right"><span><?php echo isset($currency) ? html_entity_decode($currency, ENT_QUOTES, 'UTF-8') : '$'; ?></span> <?php echo isset($paidSalesAmount) && isset($decimalLength) ? number_format($paidSalesAmount, $decimalLength) : '0.00'; ?></p></td>
                    </tr>
                    <tr>
                      <td><b>No. of Unpaid Sales</b></td>
                      <td id="unpaidSalesCount"><?php echo isset($unpaidSalesCount) ? $unpaidSalesCount : '0'; ?></td>
                      <td><b>Amount of Unpaid Sales</b></td>
                      <td><p id="unpaidSalesAmount" class="text-right"><span><?php echo isset($currency) ? html_entity_decode($currency, ENT_QUOTES, 'UTF-8') : '$'; ?></span> <?php echo isset($unpaidSalesAmount) && isset($decimalLength) ? number_format($unpaidSalesAmount, $decimalLength) : '0.00'; ?></p></td>
                    </tr> -->
                    <tr>
                      <td></td>
                      <td></td>
                      <td><b>Total Net Amount</b></td>
                      <td><p class="text-right" id="totalNetAmount"><span><?php echo isset($currency) ? html_entity_decode($currency, ENT_QUOTES, 'UTF-8') : '$'; ?></span> <?php echo isset($totalNetAmount) && isset($decimalLength) ? number_format($totalNetAmount, $decimalLength) : '0.00'; ?></p></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- col-md-12 -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<script type="text/javascript">
var base_url = "<?php echo base_url(); ?>";

$(document).ready(function () {
  $('#xReportSubMenu').addClass('active');
  $("#ReportMainNav").addClass('active');
  $('#date-submit').on('click', function () {
    let selectedStartDate = $('#startDate').val();
    let selectedEndDate = $('#endDate').val();
    console.log(selectedEndDate)
    let today = new Date().toISOString().split('T')[0];

    if (!selectedStartDate) {
      $('#alert-message').show();
      $('#alert-message').html("Please Select From Date");
      setTimeout(function() {
        $('#alert-message').hide();
      }, 3000);
    } else if (selectedStartDate > today) {
      $('#alert-message').show();
      $('#alert-message').html("Start Date cannot be in the future");
      setTimeout(function() {
        $('#alert-message').hide();
      }, 3000);
    } else if (!selectedEndDate) {
      $('#alert-message').show();
      $('#alert-message').html("Please Select End Date");
      setTimeout(function() {
        $('#alert-message').hide();
      }, 3000);
    } else if (selectedEndDate < selectedStartDate) {
      $('#alert-message').show();
      $('#alert-message').html("End Date must be greater than or equal to Start Date");
      setTimeout(function() {
        $('#alert-message').hide();
      }, 3000);
    } else {
      fetchInvoiceByDate(selectedStartDate, selectedEndDate);
    }
  });

  function fetchInvoiceByDate(selectedStartDate, selectedendDate) {
    console.log("coming")
    const data = {
      "fromDate": selectedStartDate,
      "toDate": selectedendDate
    }

    if(selectedStartDate && selectedendDate) {
      $.ajax({
        url: base_url + 'x_report/fetchByDate',
        type: 'POST',
        data: {
          date: data
        },
        dataType: 'json',
        success: function(response) {
          try {
            const currency = response.currency + " ";
            var decimalLength = response.decimalLength;
            console.log(response)
            $('#dineInSalesCount').text(response.dineInSalesCount);
            $('#dineInSalesAmount').text(currency + response.dineInSalesAmount.toFixed(decimalLength));
            $('#takeawaySalesCount').text(response.takeawaySalesCount);
            $('#takeawaySalesAmount').text(currency + response.takeawaySalesAmount.toFixed(decimalLength));
            $('#paidSalesCount').text(response.paidSalesCount);
            $('#paidSalesAmount').text(currency + response.paidSalesAmount.toFixed(decimalLength));
            $('#unpaidSalesCount').text(response.unpaidSalesCount);
            $('#unpaidSalesAmount').text(currency + response.unpaidSalesAmount.toFixed(decimalLength));
            $('#totalNetAmount').text(currency + (response.totalNetAmount).toFixed(decimalLength));
                
          } catch(error) {
            console.log("Failed",error);
          }
        },
        error: function(xhr) {
          var errorMessage = 'An error occurred. Please try again.';
          $('#alert-message').html(errorMessage).show();
        }
      });
    } else {
      console.log("Failed")
    }
  }

  $('#print').on('click', function () {
    let selectedStartDate = $('#startDate').val();
    let selectedEndDate = $('#endDate').val();
    let dineInSalesCount = $('#dineInSalesCount').text();
    let dineInSalesAmount = $('#dineInSalesAmount').text().replace("$", "").replace(" ", "").replace("(","").replace(")","").replace("-","");
    let takeawaySalesCount = $('#takeawaySalesCount').text();
    let takeawaySalesAmount = $('#takeawaySalesAmount').text().replace("$", "").replace(" ", "").replace("(","").replace(")","").replace("-","");
    let paidSalesCount = $('#paidSalesCount').text();
    let paidSalesAmount = $('#paidSalesAmount').text().replace("$", "").replace(" ", "").replace("(","").replace(")","").replace("-","");
    let unpaidSalesCount = $('#unpaidSalesCount').text();
    let unpaidSalesAmount = $('#unpaidSalesAmount').text().replace("$", "").replace(" ", "").replace("(","").replace(")","").replace("-","");
    let totalNetAmount = $('#totalNetAmount').text().replace("$", "").replace(" ", "").replace("(","").replace(")","");

    const data = {
      "selectedStartDate": selectedStartDate,
      "selectedEndDate": selectedEndDate,
      "dineInSalesCount": dineInSalesCount,
      "dineInSalesAmount": dineInSalesAmount,
      "takeawaySalesCount": takeawaySalesCount,
      "takeawaySalesAmount": takeawaySalesAmount,
      "paidSalesCount": paidSalesCount,
      "paidSalesAmount": paidSalesAmount,
      "unpaidSalesCount": unpaidSalesCount,
      "unpaidSalesAmount": unpaidSalesAmount,
      "totalNetAmount": totalNetAmount
    }
    console.log(data)
    printData(data);
  });

  function printData(data) {
    if(data) {
      $.ajax({
        url: base_url + 'x_report/print',
        type: 'POST',
        data: {
          date: {
            fromDate: data.selectedStartDate,
            toDate: data.selectedEndDate
          }
        },
        dataType: 'html',
        success: function(response) {
          var printWindow = window.open('', '_blank');
          printWindow.document.write(response);
          printWindow.document.close();
        },
        error: function(xhr, status, error) {
          console.error('Error fetching report data:', error);
          alert('Error generating report. Please try again.');
        }
      });
    } else {
      console.log("No data to print");
    }
  }
});
</script>