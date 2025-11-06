  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-file-text"></i> Z-Report
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Reports</li>
        <li class="active">Z-Report</li>
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
                    <div class="col-md-6 mb-2">
                      <input id="endDate" name="endDate" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>"/>
                    </div>
                  </div>
                  
                  <!-- Action buttons and legend -->
                  <div class="row" style="margin-top: 15px;">
                    <div class="col-md-6">
                      <button class="btn btn-success" id="print">Print</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="table-responsive" style="margin-top: 20px;">
                <table class="table table-hover table-bordered" id="sampleTable">
                  <thead>
                    <tr>
                      <th class="text-center">Invoice ID</th>
                      <th class="text-center">Bill No.</th>
                      <th class="text-center">Date</th>
                      <th class="text-center">Total Products</th>
                      <th class="text-center">Payment Type</th>
                      <th class="text-center">Payment Status</th>
                      <th class="text-center">Total Amt</th>
                      <th class="text-center">Received Amt</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(isset($invoices) && !empty($invoices)): ?>
                      <?php 
                        $totalAmount = 0;
                        foreach($invoices as $invoice): 
                          $totalAmount += floatval($invoice['net_amt'] ?? 0);
                          $paymentStatus = ($invoice['paid_status'] == 1) ? '<span class="label label-success">Paid</span>' : '<span class="label label-danger">Not Paid</span>';
                      ?>
                      <tr>
                        <td class="text-center"><?php echo (1000 + $invoice['id']); ?></td>
                        <td class="text-center"><?php echo $invoice['bill_no']; ?></td>
                        <td class="text-center"><?php echo date('d-m-Y', strtotime($invoice['created_at'])); ?></td>
                        <td class="text-center"><?php echo intval($invoice['product_count'] ?? 0); ?></td>
                        <td class="text-center"><?php echo ucfirst($invoice['payment_type']); ?></td>
                        <td class="text-center"><?php echo $paymentStatus; ?></td>
                        <td class="text-center">
                          <span><?php echo isset($currency) ? $currency : '$'; ?></span>
                          <?php echo isset($decimalLength) ? number_format($invoice['total_amount'], $decimalLength) : number_format($invoice['total_amount'], 2); ?>
                        </td>
                        <td class="text-center">
                          <span><?php echo isset($currency) ? $currency : '$'; ?></span>
                          <?php echo isset($decimalLength) ? number_format($invoice['received_amt'] ?? 0, $decimalLength) : number_format($invoice['received_amt'] ?? 0, 2); ?>
                        </td>
                        <td class="text-center">
                          <a class="btn btn-info btn-sm" href="<?php echo base_url('pos_orders/modernPrint/' . $invoice['id']); ?>">
                            <i class="fa fa-eye"></i>
                          </a>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="8" class="text-center">No invoices found for the selected criteria.</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td class="text-right"><b>Total</b></td>
                      <td id="tot-amt" class="text-center">
                        <span><?php echo isset($currency) ? $currency : '$'; ?></span>
                        <?php 
                          $totalAmt = isset($totalAmount) ? $totalAmount : 0;
                          echo isset($decimalLength) ? number_format($totalAmt, $decimalLength) : number_format($totalAmt, 2); 
                        ?>
                      </td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tfoot>
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
  $('#zReportSubMenu').addClass('active');
  $("#ReportMainNav").addClass('active');
  
  let fetchTimeout;
  
  $('#startDate,#endDate').on('change', function () {
    let fromDate = $('#startDate').val();
    let toDate = $('#endDate').val();
    
    clearTimeout(fetchTimeout);
    console.log(fromDate, toDate);
    
    fetchTimeout = setTimeout(function() {
      const data = {
        "fromDate": fromDate,
        "toDate": toDate,
      }
      fetchInvoices(data);
    }, 300);
  });

  function fetchInvoices(data) {
    if(data) {
      $('#sampleTable tbody').html(`
        <tr>
          <td colspan="8" class="text-center">
            <i class="fa fa-spinner fa-spin"></i> Loading...
          </td>
        </tr>
      `);
      
      $.ajax({
        url: base_url + 'z_report/fetchInvoices',
        type: 'POST',
        data: {
          reportData: data
        },
        dataType: 'json',
        success: function(response) {
          try {
            console.log("Success", response);  
            $('#sampleTable tbody').empty();
            const currency = response.currency + " ";
            const decimalLength = response.decimalLength;
            const invoiceData = response.invoices;
            
            let totalAmt = 0;
            if (invoiceData.length > 0) {
              invoiceData.forEach(invoice => {
                totalAmt += parseFloat(invoice.total_amount || 0);
                
                let paymentStatus = (invoice.paid_status == 1) ? 
                  '<span class="label label-success">Paid</span>' : 
                  '<span class="label label-danger">Not Paid</span>';
                
                $('#sampleTable tbody').append(`
                    <tr>
                      <td class="text-center">${1000 + parseInt(invoice.id)}</td>
                      <td class="text-center">${invoice.bill_no || (1000 + parseInt(invoice.id))}</td>
                      <td class="text-center">${new Date(invoice.created_at).toLocaleDateString('en-GB')}</td>
                      <td class="text-center">${parseInt(invoice.product_count || 0)}</td>
                      <td class="text-center">${paymentStatus}</td>
                      <td class="text-center">${currency + parseFloat(invoice.total_amount || 0).toFixed(decimalLength)}</td>
                      <td class="text-center">${currency + parseFloat(invoice.received_amt || 0).toFixed(decimalLength)}</td>
                      <td class="text-center">
                        <a class="btn btn-info btn-sm" target="_blank" href="${base_url}pos_orders/modernPrint/${parseInt(invoice.id)}"><i class="fa fa-eye"></i></a>
                      </td>
                    </tr>
                `);
              });
              
              $('#tot-amt').html(currency + parseFloat(totalAmt).toFixed(decimalLength));
            } else {
              $('#sampleTable tbody').append('<tr><td colspan="8" class="text-center">No invoices found for this date range.</td></tr>');
              $('#tot-amt').html(currency + '0.00');
            }               
          } catch(error) {
            console.log("Failed", error);
            $('#sampleTable tbody').html(`
              <tr>
                <td colspan="8" class="text-center text-danger">
                  <i class="fa fa-exclamation-triangle"></i> Error processing data. Please try again.
                </td>
              </tr>
            `);
            $('#tot-amt').html('0.00');
          }
        },
        error: function(xhr) {
          $('#sampleTable tbody').html(`
            <tr>
              <td colspan="8" class="text-center text-danger">
                <i class="fa fa-exclamation-triangle"></i> Error loading data. Please try again.
              </td>
            </tr>
          `);
          $('#tot-amt').html('0.00');
          console.log("AJAX Error:", xhr);
        }
      });
    } else {
      console.log("Failed - no data provided");
    }
  }

  $('#print').on('click', function () {
    let fromDate = $('#startDate').val();
    let toDate = $('#endDate').val();
    
    const data = {
      "fromDate": fromDate,
      "toDate": toDate,
    }
    console.log(data);
    printData(data);
  });

  function printData(data) {
    if(data) {
      $.ajax({
        url: base_url + 'z_report/print',
        type: 'POST',
        data: {
          reportData: data
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