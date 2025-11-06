<style type='text/css'>
  .pos-order button,
  .pos-order .btn {
    font-size: 16px;
    font-weight: 700;
  }

  .pos-order p {
    font-size: 16px;
    font-weight: 600;
  }

  .pos-order label {
    font-size: 16px;
    font-weight: 600;
  }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <!--  <section class="content-header" >
    <div class="box-header">
      <h3 class="box-title">Add Order</h3>
    </div>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Orders</li>
    </ol>
  </section> -->

  <!-- Main content -->
  <section class="content pos-order">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

        <?php if ($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php elseif ($this->session->flashdata('error')): ?>
          <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>


        <div class="box">
          <!-- <div class="box-header">
            <h3 class="box-title">Add Order</h3>
          </div> -->
          <!-- /.box-header -->
          <form role="form" action="<?php echo base_url('pos_orders/create') ?>" method="post" class="form-horizontal">
            <div class="box-body">

              <?php echo validation_errors(); ?>

              <div class="w-100" style="display: flex;justify-content:space-between;align-items:center;">
                <div class="form-group" style="margin:0;">
                  <label for="gross_amount" class="control-label" style="margin:0px 20px">Date: <?php echo date('d-m-Y') ?></label>
                  <label for="gross_amount" class="control-label">
                    Time: <span id="live-time"></span>
                  </label>
                </div>

                <div>
                  <div class="form-group" style="margin:0;">
                    <div class="col-sm-12">
                      <span id='table-list' style="margin:0px 20px" hidden>
                        <?php foreach ($table_data as $key => $value): ?>
                          <button style="margin:0px 3px" type="button" class="btn btn-warning table-select" id="<?php echo $value['id'] ?>">&nbsp;<?php echo $value['table_name'] ?>&nbsp;</button>
                        <?php endforeach ?>
                      </span>
                      <button id="dine-in" type="button" class="btn btn-success">Dine In</button>
                      <button id="take-away" type="button" class="btn btn-success">Take Away</button>
                      <input id="order-type" type="hidden" />
                      <input id="table_name" type="hidden" name="table_name" />
                    </div>
                  </div>

                </div>
              </div>

              <!-- <div class="form-group">
                  <label for="gross_amount" class="col-sm-12 control-label">Date: <?php echo date('Y-m-d') ?></label>
                </div>
                <div class="form-group">
                  <label for="gross_amount" class="col-sm-12 control-label">Time: <?php echo date('h:i a') ?></label>
                </div> -->
              <br /> <br />
              <div class="row" style="padding: 10px;">
                <div class="col-md-6" style="min-height:60vh;max-height: 60vh;overflow-y:auto">
                  <div style="width:100%;display:flex;gap:10px;flex-wrap: wrap;margin-top:10px;">
                    <button type="button" value="0" class="btn btn-success prod-cat">All</button>
                    <?php foreach ($categories_data as $key => $value): ?>
                      <button type="button" value="<?php echo $value['id'] ?>" class="btn btn-success prod-cat"><?php echo $value['name'] ?></button>
                    <?php endforeach ?>
                  </div>
                  <div>
                    <h3 id="prod-cat-title"></h3>
                    <div id="prod-img-container" style="width:100%;display:flex;gap:10px;flex-wrap: wrap;">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                    <table class="table table-bordered table-hover" id="product_info_table">
                      <thead>
                        <tr>
                          <th style="width:25%; text-align: left;">Product</th>
                          <th style="width:25%; text-align: center;">Qty</th>
                          <th style="width:15%; text-align: right;">Rate</th>
                          <th style="width:20%; text-align: right;">Amount</th>
                          <th style="width:10%"></th>
                        </tr>
                      </thead>

                      <tbody>
                        <tr id="default-table-row">
                          <td colspan="5" style="text-align: center;">No Product Selected</td>
                        </tr>
                        <!-- <tr id="row_1">
                          <td>
                            <select class="form-control select_group product" data-row-id="row_1" id="product_1" name="product[]" style="width:100%;" onchange="getProductData(1)" required>
                                <option value=""></option>
                                <?php foreach ($products as $k => $v): ?>
                                  <option value="<?php echo $v['id'] ?>"><?php echo $v['name'] ?></option>
                                <?php endforeach ?>
                              </select>
                            </td>
                            <td><input type="text" name="qty[]" id="qty_1" class="form-control" required onkeyup="getTotal(1)"></td>
                            <td>
                              <input type="text" name="rate[]" id="rate_1" class="form-control" disabled autocomplete="off">
                              <input type="hidden" name="rate_value[]" id="rate_value_1" class="form-control" autocomplete="off">
                            </td>
                            <td>
                              <input type="text" name="amount[]" id="amount_1" class="form-control" disabled autocomplete="off">
                              <input type="hidden" name="amount_value[]" id="amount_value_1" class="form-control" autocomplete="off">
                            </td>
                            <td><button type="button" class="btn btn-danger" onclick="removeRow('1')"><i class="fa fa-close"></i></button></td>
                        </tr> -->
                      </tbody>
                    </table>
                  </div>

                  <br /> <br />

                  <div class="col-md-12 col-xs-12 pull pull-right" style="padding-right: 0px;">
                    <div class="col-md-6 col-xs-12 button-container" style="padding-right: 0px;">
                      <div class="quick-amount-buttons">
                        <div class="button-row">
                          <button type="button" class="btn btn-success quick-amount-btn" data-amount="5">5.00</button>
                          <button type="button" class="btn btn-success quick-amount-btn" data-amount="10">10.00</button>
                        </div>
                        <div class="button-row">
                          <button type="button" class="btn btn-success quick-amount-btn" data-amount="20">20.00</button>
                          <button type="button" class="btn btn-info quick-amount-btn exact-amount-btn" data-amount="exact" id="exact-amount-btn">
                            <span id="exact-amount-text">0.00</span>
                          </button>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6 col-xs-8 pull pull-right">

                      <div class="form-group" hidden>
                        <label for="gross_amount" class="col-sm-5 control-label">Gross Amount</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control" id="gross_amount" name="gross_amount" disabled autocomplete="off" style="text-align: right;">
                          <input type="hidden" class="form-control" id="gross_amount_value" name="gross_amount_value" autocomplete="off">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="discount" class="col-sm-5 control-label">Discount</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control" id="discount" name="discount" placeholder="Discount" onkeyup="subAmount()" autocomplete="off" style="text-align: right;">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="net_amount" class="col-sm-5 control-label">Total Amt</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control" id="net_amount" name="net_amount" disabled autocomplete="off" style="text-align: right;">
                          <input type="hidden" class="form-control" id="net_amount_value" name="net_amount_value" autocomplete="off">
                        </div>
                      </div>

                      <div class="form-group" id="paid_amount_field">
                        <label for="paid_amount" class="col-sm-5 control-label">Paid Amt</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control" id="paid_amount" name="paid_amount" autocomplete="off" placeholder="0.00" style="text-align: right;">
                          <input type="hidden" class="form-control" id="paid_amount_value" name="paid_amount_value" autocomplete="off">
                        </div>
                      </div>

                      <div class="form-group" hidden>
                        <label for="paid_status" class="col-sm-5 control-label">Paid Status</label>
                        <div class="col-sm-7">
                          <select type="text" class="form-control" id="paid_status" name="paid_status">
                            <option value="1">Paid</option>
                            <option value="0">Unpaid</option>
                          </select>
                        </div>
                      </div>

                      <!-- Balance Display -->
                      <div class="form-group" id="balance-form-group" style="display: none;">
                        <label class="col-sm-5 control-label" style="color: #28a745; font-weight: bold;font-size: 20px;">Balance</label>
                        <div class="col-sm-7">
                          <div style="padding-top: 7px; color: #28a745; font-weight: bold; text-align: right;font-size: 20px;">
                            <?php echo isset($currency_symbol) ? $currency_symbol : '€'; ?> <span id="balance-amount" style="font-size: 20px;">0.00</span>
                          </div>
                        </div>
                      </div>

                      <!-- Due Amount Display -->
                      <div class="form-group" id="due-form-group" style="display: none;">
                        <label class="col-sm-5 control-label" style="color: #dc3545; font-weight: bold; font-size: 20px;">Amt Due</label>
                        <div class="col-sm-7">
                          <div style="padding-top: 7px; color: #dc3545; font-weight: bold; text-align: right;font-size: 20px;">
                            <?php echo isset($currency_symbol) ? $currency_symbol : '€'; ?> <span id="due-amount-display" style="font-size: 20px;">0.00</span>
                          </div>
                        </div>
                      </div>

                      <div id="balance_amt" class="form-group" hidden>
                        <label for="bal_amount" class="col-sm-7 control-label">Bal Amount</label>
                        <div class="col-sm-5">
                          <input type="hidden" class="form-control" id="bal_amount" name="bal_amount" autocomplete="off" readonly style="text-align: right;">
                          <!-- <input type="hidden" class="form-control" id="bal_amount_value" name="bal_amount_value" autocomplete="off"> -->
                        </div>
                      </div>

                      <div id="amount_due" class="form-group" hidden>
                        <label for="due_amount" class="col-sm-7 control-label">Amount Due</label>
                        <div class="col-sm-5">
                          <input type="hidden" class="form-control" id="due_amount" name="due_amount" autocomplete="off" readonly style="text-align: right;">
                          <!-- <input type="hidden" class="form-control" id="due_amount_value" name="due_amount_value" autocomplete="off"> -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer"  style="display: flex; justify-content: flex-end;">
              
              <div  style="display: flex; justify-content: flex-start;gap: 5px;margin-right:100px;">
                <input type="hidden" id="payment-type" name="payment_type" />
                <button id="card-btn" type="button" class="btn btn-success payment-type-btn" data-payment-type="card">Card</button>
                <button id="cash-btn" type="button" class="btn btn-success payment-type-btn" data-payment-type="cash">Cash</button>
              </div>
              
              <div  style="display: flex; justify-content: flex-end;gap: 5px;">
                <button type="submit" class="btn btn-success" data-paid-status="1">Paid</button>
                <button type="submit" class="btn btn-warning" data-paid-status="0"><i class="fa fa-pause"></i></button>
                <a href="<?php echo base_url('orders/') ?>" class="btn btn-danger">Cancel</a>
              </div>
            </div>
          </form>
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

<style>
  .button-container {
    display: flex;
    justify-content: end;
    align-items: flex-end;
    /* min-height: 300px; */
  }

  .quick-amount-buttons {
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: 100%;
  }

  .button-row {
    display: flex;
    gap: 8px;
    width: 100%;
  }

  .quick-amount-btn {
    height: 50px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 8px 12px;
    flex: 1;
    min-width: 0;
    white-space: nowrap;
    border: 2px solid transparent;
  }

  .quick-amount-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  }

  .quick-amount-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .quick-amount-btn.active {
    background-color: #007bff !important;
    border-color: #007bff !important;
    color: #fff !important;
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3) !important;
  }

  .quick-amount-btn:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
  }

  #exact-amount-text {
    word-wrap: break-word;
    text-align: center;
    line-height: 1.2;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 14px;
  }

  /* Responsive design for smaller screens */
  @media (max-width: 768px) {

    .quick-amount-buttons {
      gap: 6px;
    }

    .button-row {
      gap: 6px;
    }

    .quick-amount-btn {
      height: 45px;
      font-size: 12px;
      padding: 6px 8px;
    }

    #exact-amount-text {
      font-size: 12px;
    }
  }

  /* Extra small screens - stack buttons vertically if needed */
  @media (max-width: 480px) {
    .button-container {
      align-items: flex-end
    }

    .button-row {
      flex-direction: column;
      gap: 4px;
    }

    .quick-amount-btn {
      height: 40px;
      font-size: 11px;
    }
  }

  /* Scrollable table styling */
  .table-responsive {
    border: 1px solid #ddd;
    border-radius: 4px;
  }

  #product_info_table thead th {
    position: sticky;
    top: 0;
    background-color: #f5f5f5;
    z-index: 10;
    border-bottom: 2px solid #ddd;
  }

  .table-responsive::-webkit-scrollbar {
    width: 8px;
  }

  .table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }

  .table-responsive::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
  }

  .table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
  }

  /* Input validation error styling */
  .input-error {
    border: 2px solid #ff6b6b !important;
    box-shadow: 0 0 5px rgba(255, 107, 107, 0.3) !important;
  }

  /* Center align quantity field text */
  input[name="qty[]"] {
    text-align: center !important;
  }

  /* Right align amount fields */
  input[name="rate[]"],
  input[name="amount[]"],
  #discount,
  #net_amount,
  #paid_amount,
  #bal_amount,
  #due_amount {
    text-align: right !important;
  }

  /* Right align labels for amount fields (Bootstrap default) */
  /* .control-label {
    text-align: right !important;
  } */

  /* Product table styling */
  #product_info_table th:nth-child(1),
  #product_info_table td:nth-child(1) {
    text-align: center !important;
  }

  #product_info_table th:nth-child(2),
  #product_info_table td:nth-child(2) {
    text-align: center !important;
  }

  #product_info_table th:nth-child(3),
  #product_info_table td:nth-child(3),
  #product_info_table th:nth-child(4),
  #product_info_table td:nth-child(4) {
    text-align: right !important;
  }
</style>

<!-- remove product modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeProductModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Remove Product</h4>
      </div>

      <form role="form" action="#" method="post" id="removeProductForm">
        <div class="modal-body">
          <p id="removeProductMessage">Do you really want to remove?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Save changes</button>
        </div>
      </form>


    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
  var base_url = "<?php echo base_url(); ?>";
  var currency_symbol = "<?php echo isset($currency_symbol) ? $currency_symbol : '€'; ?>";
  var currentCategoryRequest = null; // Store current AJAX request
  var isLoadingCategory = false; // Track loading state
  var isPauseAction = false; // Track if pause button was clicked
  var hideProductErrorIfExists; // Function to hide product validation errors
  var clearPaidAmountErrors; // Function to clear payment validation errors

  function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString(); // includes seconds and AM/PM
    $('#live-time').text(timeString);
  }

  // Initial call and then update every second
  updateTime();
  setInterval(updateTime, 1000);

  $(document).ready(function() {
    $('body').addClass('sidebar-collapse');
    // $(".select_group").select2();
    // $("#description").wysihtml5();

    $("#POSOrderMainNav").addClass('active');
    $("#createPOSOrderSubMenu").addClass('active');
    $('#payment-type').val('cash');
    $('#cash-btn').addClass('btn-info');


    // Initialize exact amount button
    updateExactAmountButton();

    // Input validation for numeric fields
    setupNumericValidation();

    // Setup form validation
    setupFormValidation();

    // Setup real-time validation to hide errors when valid values are entered
    setupRealTimeValidation();

    // Setup form submission handling for different button types
    setupFormSubmission();

    // Set default order type to Take Away
    $('#order-type').val('Take Away');
    $('#take-away').addClass('btn-info');
    $('#table-list').attr('hidden', true);

    // Auto-load first category products on page load
    loadFirstCategory();

    $('.payment-type-btn').on('click', function() {

      $('#cash-btn, #card-btn').removeClass('btn-info');

      let paymentType = $(this).data('payment-type');
      $('#payment-type').val(paymentType);
      if (paymentType == 'card') {
        $('#card-btn').addClass('btn-info');
        var netAmount = $("#net_amount").val() || '0';
        var displayAmount = Number(netAmount).toFixed(2); // Show decimal format
        $('#paid_amount').val(displayAmount);
        $('#paid_amount_value').val(displayAmount);
        $('.quick-amount-buttons').css('visibility', 'hidden')
        // $('#paid_amount_field').css('visibility', 'hidden')

        $('#bal_amount').val('');
        $('#bal_amount_value').val('');
        $('#due_amount').val('');
        $('#due_amount_value').val('');

        // Show balance form group
        $('#balance-form-group').hide();
        $('#due-form-group').hide();
      } else {
        $('#cash-btn').addClass('btn-info');
        $('#paid_amount').val('0.0');
        $('#paid_amount_value').val('0.0');
        $('#paid_amount_field').css('visibility', 'visible')
        $('.quick-amount-buttons').css('visibility', 'visible')
      }
    });

    $('#dine-in, #take-away').on('click', function() {
      let orderType = $(this).text();
      $('#order-type').val(orderType);
      console.log($('#order-type').val());

      $('#dine-in, #take-away').removeClass('btn-info');
      $(this).addClass('btn-info');

      if (orderType == "Dine In") {
        $('#table-list').attr('hidden', false);
      } else {
        $('#table-list').attr('hidden', true);
      }
    });

    $(document).on('click', '.table-select', function() {
      let tableId = $(this).attr('id');
      let tableName = $(this).text().trim();
      $('#table_name').val(tableName);
      console.log('Selected Table:', tableName);

      $('.table-select').removeClass('btn-info').addClass('btn-warning');
      $(this).removeClass('btn-warning').addClass('btn-info');
      $('#table_name').val(tableName);
    });

    $('.prod-cat').on('click', function() {
      // Prevent multiple rapid clicks
      if (isLoadingCategory) {
        return;
      }

      let prodCatValue = $(this).val();
      let prodCatName = $(this).text();

      // Cancel any ongoing request
      if (currentCategoryRequest && currentCategoryRequest.readyState !== 4) {
        currentCategoryRequest.abort();
        console.log('Previous category request cancelled');
      }

      // Set loading state
      isLoadingCategory = true;

      // Update UI immediately
      $('#prod-cat-title').text(prodCatName);
      $('.prod-cat').removeClass('btn-info');
      $(this).addClass('btn-info');
      $('#prod-img-container').html('');

      let loadingMsg = `
        <div style="width:100%;text-align:center;">
          <p>Loading...</p>
        </div>
      `;

      $('#prod-img-container').html(loadingMsg);

      /* Ajax function to fetch the product data for selected Category */
      currentCategoryRequest = $.ajax({
        url: base_url + 'products/fetchProductDataByCategory/' + prodCatValue,
        type: 'get',
        dataType: 'json',
        success: function(response) {
          if (response.length > 0) {
            let prodImgData = response.map((data) => `
              <div class="prod-img" style="cursor:pointer;">
                <img src="${base_url}${data.image}" alt="${data.name}" class="img-circle" data-id="${data.id}" data-name="${data.name}" data-price="${data.price}" width="100" height="80" />
                <p style="text-align:center;width:100px;word-wrap: break-word; overflow-wrap: break-word;">${data.name} - ${data.price}</p>
                </div>
            `).join('');

            $('#prod-img-container').html(prodImgData);
          } else {
            let emptyMsg = `
              <div style="width:100%;text-align:center;">
                <p>No Products for this Category</p>
              </div>
            `;
            $('#prod-img-container').html(emptyMsg);
          }
        },
        error: function(xhr, status, error) {
          // Only show error if request wasn't aborted
          if (status !== 'abort') {
            let errorMessage = `
              <div style="width:100%;$text-align:center;">
                <p>An error occurred. Please try again.</p>
              </div>
            `;
            $('#prod-img-container').html(errorMessage);
          }
          console.log('Category request error:', status, error);
        },
        complete: function(xhr, status) {
          // Reset loading state when request completes (success, error, or abort)
          isLoadingCategory = false;
          console.log('Category request completed with status:', status);
        }
      });
    });

    $(document).on('click', '.prod-img', function() {
      let prodID = $(this).find('img').data('id');
      let prodName = $(this).find('img').data('name');
      let prodPrice = $(this).find('img').data('price');
      console.log(prodID, prodName, prodPrice);

      $('#default-table-row').remove();

      /* Check if product already exists in the table */
      let existingProductRow = null;
      $("#product_info_table tbody tr").each(function() {
        let existingProdID = $(this).find('input[name="product[]"]').val();
        if (existingProdID == prodID) {
          existingProductRow = $(this);
          return false;
        }
      });

      if (existingProductRow) {
        /* Product already exists, increment quantity */
        let rowId = existingProductRow.attr('id').replace('row_', '');
        let currentQty = parseInt($('#qty_' + rowId).val()) || 0;
        let newQty = currentQty + 1;
        $('#qty_' + rowId).val(newQty);

        /* Trigger quantity change to recalculate totals */
        getTotal(rowId);

        // Hide product error if it exists since we just incremented a product
        if (typeof hideProductErrorIfExists === 'function') {
          hideProductErrorIfExists();
        }

        // Visual feedback - briefly highlight the row
        // existingProductRow.addClass('bg-warning');
        // setTimeout(function() {
        //   existingProductRow.removeClass('bg-warning');
        // }, 1000);
      } else {
        // Product doesn't exist, add new row
        let table = $("#product_info_table");
        let count_table_tbody_tr = $("#product_info_table tbody tr").length;
        // let row_id = count_table_tbody_tr + 1;
        let row_id = prodID;

        let html = `
          <tr id="row_${row_id}">
            <td>
              <input type="text" title="${prodName}" value="${prodName}" data-row-id="${row_id}" id="product_${row_id}" class="form-control" disabled>
              <input type="hidden" value="${prodID}" data-row-id="${row_id}" name="product[]" id="product_${row_id}" class="form-control">
            </td>
            <td style="display:flex;">
              <button type="button" class="qty-minus" data-id="qty_${row_id}"><i class="fa fa-minus"></i></button>
              <input type="text" value="1" name="qty[]" id="qty_${row_id}" class="form-control" onkeyup="getTotal(${row_id})">
              <button type="button" class="qty-add" data-id="qty_${row_id}"><i class="fa fa-plus"></i></button>
              </td>
            <td>
              <input type="text" value="${Number(prodPrice).toFixed(2)}" name="rate[]" id="rate_${row_id}" class="form-control" disabled style="text-align: right;">
              <input type="hidden" value="${prodPrice}" name="rate_value[]" id="rate_value_${row_id}" class="form-control">
            </td>
            <td>
              <input type="text" value="${Number(prodPrice).toFixed(2)}" name="amount[]" id="amount_${row_id}" class="form-control" disabled style="text-align: right;">
              <input type="hidden" value="${prodPrice}" name="amount_value[]" id="amount_value_${row_id}" class="form-control">
            </td>
            <td>
              <button type="button" class="btn btn-danger" onclick="removeProductFunc('${row_id}', '${prodName}')">
                <i class="fa fa-close"></i>
              </button>
            </td>
          </tr>
        `;

        if (count_table_tbody_tr >= 1) {
          $("#product_info_table tbody tr:last").after(html);
        } else {
          $("#product_info_table tbody").html(html);
        }
        subAmount();

        // Hide product error if it exists since we just added a product
        if (typeof hideProductErrorIfExists === 'function') {
          hideProductErrorIfExists();
        }
      }
    });

    $('#paid_amount').on('keyup input', function() {
      let paidAmtValue = $(this).val();
      let paidAmt = Number(paidAmtValue);
      let netAmt = Number($('#net_amount').val());

      // Clear active state from quick amount buttons when manually typing
      $('.quick-amount-btn').removeClass('active');

      // Show balance field if there's a paid amount and it's different from net amount
      if (paidAmtValue && paidAmtValue.trim() !== '' && paidAmt !== netAmt) {
        let balAmt = Number(paidAmt - netAmt);

        if (paidAmt > netAmt) {
          // Show balance amount (change given to customer)
          //$('#balance_amt').attr('hidden', false);
          //$('#amount_due').attr('hidden', true);
          $('#bal_amount').val(balAmt.toFixed(2));
          $('#bal_amount_value').val(balAmt.toFixed(2));
          $('#due_amount').val('');
          $('#due_amount_value').val('');

          // Show balance form group
          $('#balance-form-group').show();
          $('#due-form-group').hide();
          $('#balance-amount').text(balAmt.toFixed(2));
        } else {
          // Show amount due (remaining amount to be paid)
          // $('#amount_due').attr('hidden', false);
          // $('#balance_amt').attr('hidden', true);
          $('#due_amount').val(Math.abs(balAmt).toFixed(2));
          $('#due_amount_value').val(Math.abs(balAmt).toFixed(2));
          $('#bal_amount').val('');
          $('#bal_amount_value').val('');

          // Show due form group
          $('#due-form-group').show();
          $('#balance-form-group').hide();
          $('#due-amount-display').text(Math.abs(balAmt).toFixed(2));
        }
      } else {
        // Hide both fields when paid amount is 0 or equal to net amount
        // $('#balance_amt').attr('hidden', true);
        // $('#amount_due').attr('hidden', true);
        $('#bal_amount').val('');
        $('#bal_amount_value').val('');
        $('#due_amount').val('');
        $('#due_amount_value').val('');

        // Hide form groups
        $('#balance-form-group').hide();
        $('#due-form-group').hide();
      }
    });

    // Quick amount buttons functionality
    $(document).on('click', '.quick-amount-btn', function() {
      let amount = $(this).data('amount');

      // Remove active class from all buttons
      $('.quick-amount-btn').removeClass('active');
      // Add active class to clicked button
      $(this).addClass('active');

      if (amount === 'exact') {
        // Set paid amount to exact net amount (use the actual value, not display)
        let netAmount = $('#net_amount').val() || '0';
        let formattedAmount = Number(netAmount).toFixed(2);
        $('#paid_amount').val(formattedAmount);
      } else {
        // Set paid amount to the button value with proper decimal format
        let formattedAmount = Number(amount).toFixed(2);
        $('#paid_amount').val(formattedAmount);
      }

      // Trigger the keyup event to calculate balance
      $('#paid_amount').trigger('keyup');
    });

    $(document).on('click', '.qty-add', function() {
      let qtyID = $(this).data('id');
      let qtyVal = Number($(`#${qtyID}`).val());
      console.log("qtyID", qtyID, qtyVal)
      $(`#${qtyID}`).val(qtyVal + 1);

      // Extract row ID and recalculate totals
      let rowId = qtyID.replace('qty_', '');
      getTotal(rowId);
    });

    $(document).on('click', '.qty-minus', function() {
      let qtyID = $(this).data('id');
      let qtyVal = Number($(`#${qtyID}`).val());
      console.log("qtyID", qtyID, qtyVal)

      if (qtyVal > 1) {
        $(`#${qtyID}`).val(qtyVal - 1);

        // Extract row ID and recalculate totals
        let rowId = qtyID.replace('qty_', '');
        getTotal(rowId);
      } else if (qtyVal === 1) {
        // Show confirmation modal when trying to decrease to 0
        let rowId = qtyID.replace('qty_', '');
        let productName = $(`#product_${rowId}`).val();

        // Call remove function following existing pattern
        removeProductFunc(rowId, productName);
      }
    });

  }); // /document

  // Function to automatically load first category products
  function loadFirstCategory() {
    console.log('Loading first category...');

    // Add a small delay to ensure DOM is fully ready
    setTimeout(function() {
      // Find all category buttons
      var allCategoryBtns = $('.prod-cat');
      console.log('Found category buttons:', allCategoryBtns.length);

      if (allCategoryBtns.length === 0) {
        console.log('No category buttons found');
        return;
      }

      // Find the first specific category (excluding "All" button with value="0")
      var targetBtn = $('.prod-cat').filter(function() {
        return $(this).val() !== '0'; // Exclude "All" button
      }).first();

      // If no specific category found, fallback to "All" button
      if (targetBtn.length === 0) {
        console.log('No specific category found, falling back to All button');
        targetBtn = $('.prod-cat[value="0"]'); // "All" button
      }

      console.log('Selected category button:', targetBtn.text(), 'Value:', targetBtn.val());

      // Trigger click on the selected button
      if (targetBtn.length > 0) {
        targetBtn.click(); // Use click() instead of trigger('click')
        console.log('Category button clicked');
      } else {
        console.log('No category button to click');
      }
    }, 500); // 500ms delay for reliability
  }

  function getTotal(row = null) {
    if (row) {
      var total = Number($("#rate_value_" + row).val()) * Number($("#qty_" + row).val());
      total = total.toFixed(2);
      $("#amount_" + row).val(total);
      $("#amount_value_" + row).val(total);

      subAmount();

    } else {
      alert('no row !! please refresh the page');
    }
  }

  // calculate the total amount of the order
  function subAmount() {
    // Clear paid amount errors first when amounts are being recalculated
    if (typeof clearPaidAmountErrors === 'function') {
      clearPaidAmountErrors();
    }

    // var service_charge = <?php echo ($company_data['service_charge_value'] > 0) ? $company_data['service_charge_value'] : 0; ?>;
    // var vat_charge = <?php echo ($company_data['vat_charge_value'] > 0) ? $company_data['vat_charge_value'] : 0; ?>;

    var tableProductLength = $("#product_info_table tbody tr").length;
    var totalSubAmount = 0;
    for (x = 0; x < tableProductLength; x++) {
      var tr = $("#product_info_table tbody tr")[x];
      var count = $(tr).attr('id');
      count = count.substring(4);

      totalSubAmount = Number(totalSubAmount) + Number($("#amount_" + count).val());
    } // /for

    totalSubAmount = totalSubAmount.toFixed(2);
    console.log("totalSubAmount", totalSubAmount)

    // sub total
    $("#gross_amount").val(totalSubAmount);
    $("#gross_amount_value").val(totalSubAmount);

    // total amount
    var totalAmount = (Number(totalSubAmount));
    totalAmount = totalAmount.toFixed(2);
    // $("#net_amount").val(totalAmount);
    // $("#totalAmountValue").val(totalAmount);

    var discount = $("#discount").val();
    if (discount) {
      var grandTotal = Number(totalAmount) - Number(discount);
      grandTotal = grandTotal.toFixed(2);
      $("#net_amount").val(grandTotal);
      $("#net_amount_value").val(grandTotal);
    } else {
      $("#net_amount").val(totalAmount);
      $("#net_amount_value").val(totalAmount);

    } // /else discount 

    // Update exact amount button text
    updateExactAmountButton();

  } // /sub total amount

  // Function to update exact amount button text
  function updateExactAmountButton() {
    var netAmount = $("#net_amount").val() || '0';
    var displayAmount = Number(netAmount).toFixed(2); // Show decimal format
    $('#exact-amount-text').text(displayAmount);
  }

  function removeRow(tr_id) {
    $("#product_info_table tbody tr#row_" + tr_id).remove();
    subAmount();

    // Clear paid amount errors when products are removed and amounts change
    clearPaidAmountErrors();

    var tableProductLength = $("#product_info_table tbody tr").length;

    if (tableProductLength == 0) {
      let html = `
        <tr id="default-table-row">
          <td colspan="5" style="text-align: center;">No Product Selected</td>
        </tr>
      `;
      $("#product_info_table tbody").html(html);

    }
  }

  // remove product functions 
  function removeProductFunc(rowId, productName) {
    if (rowId) {
      // Update modal message with product name
      $('#removeProductMessage').text(`Do you really want to remove "${productName}"?`);

      // Show the modal
      $('#removeProductModal').modal('show');

      $("#removeProductForm").on('submit', function() {

        var form = $(this);

        // remove the text-danger
        $(".text-danger").remove();

        // Call removeRow function directly since this is client-side only
        removeRow(rowId);

        // hide the modal
        $("#removeProductModal").modal('hide');

        return false;
      });
    }
  }

  // Numeric validation setup function
  function setupNumericValidation() {
    // Validation for paid amount field
    $('#paid_amount').on('input keyup', function() {
      validateNumericInput($(this), true); // true allows decimals
    });

    // Validation for discount field
    $('#discount').on('input keyup', function() {
      validateNumericInput($(this), true); // true allows decimals
    });

    // Validation for quantity fields (delegated event for dynamically added fields)
    $(document).on('input keyup', 'input[name="qty[]"]', function() {
      validateNumericInput($(this), false); // false for integers only
    });
  }

  // Generic numeric validation function
  function validateNumericInput(inputElement, allowDecimals) {
    var value = inputElement.val();
    var hasError = false;

    if (value === '') {
      // Remove error styling for empty values
      inputElement.removeClass('input-error');
      return;
    }

    var cleanedValue = value;

    if (allowDecimals) {
      // For paid amount: allow positive numbers with up to 2 decimal places
      cleanedValue = value.replace(/[^0-9.]/g, ''); // Remove non-numeric chars except decimal

      // Ensure only one decimal point
      var parts = cleanedValue.split('.');
      if (parts.length > 2) {
        cleanedValue = parts[0] + '.' + parts.slice(1).join('');
      }

      // Limit to 2 decimal places
      if (parts[1] && parts[1].length > 2) {
        cleanedValue = parts[0] + '.' + parts[1].substring(0, 2);
      }
    } else {
      // For quantity: allow positive integers only
      cleanedValue = value.replace(/[^0-9]/g, ''); // Remove all non-numeric chars
    }

    // Check if value needed cleaning (had invalid characters)
    if (cleanedValue !== value) {
      hasError = true;
      inputElement.val(cleanedValue);
    }

    // Prevent NaN in calculations by ensuring valid number
    var numericValue = allowDecimals ? parseFloat(cleanedValue) : parseInt(cleanedValue);
    if (isNaN(numericValue) || numericValue < 0) {
      hasError = true;
      inputElement.val(allowDecimals ? '0.00' : '1');
    }

    // Apply or remove error styling based on validation
    if (hasError) {
      inputElement.addClass('input-error');
    } else {
      inputElement.removeClass('input-error');
    }
  }

  // Form validation setup function
  function setupFormValidation() {
    $('form[role="form"]').on('submit', function(e) {
      // Clear all previous error messages
      $('.field-error').remove();
      $('.error-row').remove();
      $('.input-error').removeClass('input-error');

      // Check if order type is selected
      var orderType = $('#order-type').val();
      if (!orderType) {
        showFieldError('#take-away', 'Please select order type (Dine In or Take Away).');
        e.preventDefault();
        return false;
      }

      // Check if table is selected for Dine In orders
      if (orderType === 'Dine In') {
        var tableId = $('#table_name').val();
        if (!tableId || tableId === '0') {
          showFieldError('#table_name', 'Please select a table for Dine In orders.');
          e.preventDefault();
          return false;
        }
      }

      // Check if at least one product is selected
      var productCount = $("#product_info_table tbody tr").length;
      var hasProducts = false;

      $("#product_info_table tbody tr").each(function() {
        if ($(this).attr('id') !== 'default-table-row') {
          hasProducts = true;
          return false;
        }
      });

      if (!hasProducts) {
        showFieldError('#product_info_table', 'Please add at least one product to the order.');
        e.preventDefault();
        return false;
      }

      // Validate quantity fields for each product
      var quantityError = false;
      var firstInvalidQtyField = null;

      $("#product_info_table tbody tr").each(function() {
        if ($(this).attr('id') !== 'default-table-row') {
          var qtyInput = $(this).find('input[name="qty[]"]');
          var qtyValue = qtyInput.val();

          // Check if quantity is empty
          if (!qtyValue || qtyValue.trim() === '') {
            if (!firstInvalidQtyField) {
              firstInvalidQtyField = qtyInput;
              showFieldError('#' + qtyInput.attr('id'), 'Product quantity is required.');
            }
            quantityError = true;
          }
          // Check if quantity is a valid number greater than 0
          else if (isNaN(qtyValue) || parseFloat(qtyValue) <= 0) {
            if (!firstInvalidQtyField) {
              firstInvalidQtyField = qtyInput;
              showFieldError('#' + qtyInput.attr('id'), 'Quantity must be a valid number greater than 0.');
            }
            quantityError = true;
          }
        }
      });

      if (quantityError) {
        if (firstInvalidQtyField) {
          firstInvalidQtyField.addClass('input-error');
        }
        e.preventDefault();
        return false;
      }

      // Validate gross amount
      var grossAmount = $('#gross_amount_value').val();
      if (!grossAmount || parseFloat(grossAmount) <= 0) {
        showFieldError('#gross_amount', 'Order must have a valid gross amount greater than 0.');
        e.preventDefault();
        return false;
      }

      // Validate net amount
      var netAmount = $('#net_amount_value').val();
      if (!netAmount || parseFloat(netAmount) <= 0) {
        showFieldError('#net_amount', 'Order must have a valid net amount greater than 0.');
        e.preventDefault();
        return false;
      }

      // Validate discount (if provided)
      var discount = $('#discount').val();
      if (discount && (isNaN(discount) || parseFloat(discount) < 0)) {
        showFieldError('#discount', 'Discount must be a valid number greater than or equal to 0.');
        $('#discount').addClass('input-error');
        e.preventDefault();
        return false;
      }

      // Skip paid amount validation for pause actions
      if (!isPauseAction) {
        // Validate paid amount
        var paidAmount = $('#paid_amount').val();
        var netAmount = parseFloat($('#net_amount_value').val()) || 0;

        // Check if paid amount is provided
        if (!paidAmount || paidAmount.trim() === '') {
          showFieldError('#paid_amount', 'Paid amount is required.');
          $('#paid_amount').addClass('input-error');
          e.preventDefault();
          return false;
        }

        // Check if paid amount is a valid number
        if (isNaN(paidAmount) || parseFloat(paidAmount) < 0) {
          showFieldError('#paid_amount', 'Paid amount must be a valid number greater than or equal to 0.');
          $('#paid_amount').addClass('input-error');
          e.preventDefault();
          return false;
        }

        // Check if paid amount is sufficient to cover the order
        if (parseFloat(paidAmount) < netAmount) {
          showFieldError('#paid_amount', 'Paid amount must be at least ' + netAmount.toFixed(2) + ' to cover the order total.');
          $('#paid_amount').addClass('input-error');
          e.preventDefault();
          return false;
        }
      }

      return true;
    });
  }

  // Function to show field-specific error messages
  function showFieldError(fieldSelector, message) {
    var field = $(fieldSelector);
    var errorHtml = '<div class="field-error" style="color: #d9534f; font-size: 12px; margin-top: 5px;">' + message + '</div>';

    // Add error styling to the field
    field.addClass('input-error');

    var errorElement;

    // Position error message based on field type
    if (fieldSelector === '#product_info_table') {
      // For product table, show error below the table
      field.closest('.table-responsive').after(errorHtml);
      errorElement = field.closest('.table-responsive').next('.field-error');
    } else if (fieldSelector === '#take-away') {
      // For order type buttons, show error below the button group
      field.closest('div').after(errorHtml);
      errorElement = field.closest('div').next('.field-error');
    } else if (fieldSelector === '#paid_amount') {
      // For paid amount field, show error below the input field but above the quick amount buttons
      var quickButtons = field.siblings('.quick-amount-buttons');
      if (quickButtons.length > 0) {
        quickButtons.before(errorHtml);
        errorElement = quickButtons.prev('.field-error');
      } else {
        // Fallback: show after the input field
        field.after(errorHtml);
        errorElement = field.next('.field-error');
      }
    } else if (fieldSelector.includes('qty_')) {
      // For quantity fields in the product table, show error below the row
      var tableRow = field.closest('tr');
      tableRow.after('<tr class="error-row"><td colspan="5" style="padding: 5px; background: #fdf2f2; color: #d9534f; font-size: 12px; text-align: center;">' + message + '</td></tr>');
      errorElement = tableRow.next('.error-row');

      // Auto-hide error after 3 seconds for quantity fields
      setTimeout(function() {
        if (errorElement && errorElement.length > 0) {
          errorElement.fadeOut(300, function() {
            $(this).remove();
          });
          // Also remove error styling from the field
          field.removeClass('input-error');
        }
      }, 3000);

      return; // Exit early for quantity fields as we handle them differently
    } else {
      // For regular form fields, show error below the form group
      var formGroup = field.closest('.form-group');
      if (formGroup.length > 0) {
        formGroup.after(errorHtml);
        errorElement = formGroup.next('.field-error');
      } else {
        // Fallback: show after the field itself
        field.after(errorHtml);
        errorElement = field.next('.field-error');
      }
    }

    // Auto-hide error after 3 seconds
    setTimeout(function() {
      if (errorElement && errorElement.length > 0) {
        errorElement.fadeOut(300, function() {
          $(this).remove();
        });
        // Also remove error styling from the field
        field.removeClass('input-error');
      }
    }, 3000);
  }

  // Real-time validation setup function
  function setupRealTimeValidation() {
    // Monitor paid amount field
    $('#paid_amount').on('input keyup', function() {
      var paidAmount = $(this).val();
      var netAmount = parseFloat($('#net_amount_value').val()) || 0;

      // Hide error if valid value is entered
      if (paidAmount && paidAmount.trim() !== '' && !isNaN(paidAmount) && parseFloat(paidAmount) >= 0) {
        // Remove error styling and hide error message
        $(this).removeClass('input-error');
        $(this).siblings('.field-error').fadeOut(200, function() {
          $(this).remove();
        });

        // Also check if payment covers the order total
        if (parseFloat(paidAmount) >= netAmount) {
          // All paid amount validations pass - hide any remaining errors
          $('.field-error').filter(':contains("Paid amount")').fadeOut(200, function() {
            $(this).remove();
          });
        }
      }
    });

    // Monitor discount field
    $('#discount').on('input keyup', function() {
      var discount = $(this).val();

      // Hide error if valid value is entered (empty or valid number >= 0)
      if (discount === '' || (!isNaN(discount) && parseFloat(discount) >= 0)) {
        $(this).removeClass('input-error');
        $(this).closest('.form-group').next('.field-error').fadeOut(200, function() {
          $(this).remove();
        });
      }
    });

    // Monitor quantity fields (delegated event for dynamically added fields)
    $(document).on('input keyup', 'input[name="qty[]"]', function() {
      var qtyValue = $(this).val();
      var currentRow = $(this).closest('tr');

      // Hide error if valid value is entered
      if (qtyValue && qtyValue.trim() !== '' && !isNaN(qtyValue) && parseFloat(qtyValue) > 0) {
        $(this).removeClass('input-error');
        // Remove error row if it exists
        if (currentRow.next('.error-row').length > 0) {
          currentRow.next('.error-row').fadeOut(200, function() {
            $(this).remove();
          });
        }
      }
    });

    // Monitor table selection for Dine In orders
    $('#table_name').on('change', function() {
      var tableId = $(this).val();
      var orderType = $('#order-type').val();

      // Hide error if valid table is selected for Dine In orders
      if (orderType === 'Dine In' && tableId && tableId !== '0') {
        $(this).removeClass('input-error');
        $(this).closest('.form-group').next('.field-error').fadeOut(200, function() {
          $(this).remove();
        });
      }
    });

    // Monitor order type buttons
    $('#dine-in, #take-away').on('click', function() {
      // Hide order type error when any button is clicked
      $(this).removeClass('input-error');
      $(this).closest('div').next('.field-error').fadeOut(200, function() {
        $(this).remove();
      });
    });

    // Monitor product additions - integrate into existing product click handler
    // This will be called from the product click handler
    hideProductErrorIfExists = function() {
      // Check if products exist
      var hasProducts = false;
      $("#product_info_table tbody tr").each(function() {
        if ($(this).attr('id') !== 'default-table-row') {
          hasProducts = true;
          return false;
        }
      });

      // Hide product error if products are added
      if (hasProducts) {
        // Remove error styling from the product table
        $('#product_info_table').removeClass('input-error');
        $('.table-responsive').removeClass('input-error');

        // Hide error messages
        $('.table-responsive').next('.field-error').fadeOut(200, function() {
          $(this).remove();
        });
        // Also check for errors below the table
        $('#product_info_table').closest('.table-responsive').next('.field-error').fadeOut(200, function() {
          $(this).remove();
        });
      }
    };

    // Function to clear paid amount errors when amounts change
    clearPaidAmountErrors = function() {
      // Remove error styling from paid amount field
      $('#paid_amount').removeClass('input-error');

      // Remove error messages near paid amount field (multiple possible locations)
      $('#paid_amount').siblings('.field-error').fadeOut(200, function() {
        $(this).remove();
      });
      $('.quick-amount-buttons').siblings('.field-error').fadeOut(200, function() {
        $(this).remove();
      });
      $('.quick-amount-buttons').next('.field-error').fadeOut(200, function() {
        $(this).remove();
      });
      $('#paid_amount').closest('.form-group').next('.field-error').fadeOut(200, function() {
        $(this).remove();
      });

      // Also remove any error messages that contain "Paid amount"
      $('.field-error').filter(function() {
        return $(this).text().indexOf('Paid amount') !== -1;
      }).fadeOut(200, function() {
        $(this).remove();
      });

      // Clear paid amount field to avoid confusion with new totals
      $('#paid_amount').val('');

      // Hide both balance and amount due sections
      // $('#balance_amt').attr('hidden', true);
      // $('#amount_due').attr('hidden', true);
      $('#bal_amount').val('');
      $('#bal_amount_value').val('');
      $('#due_amount').val('');
      $('#due_amount_value').val('');

      // Hide form groups
      $('#balance-form-group').hide();
      $('#due-form-group').hide();

      // Clear active state from quick amount buttons
      $('.quick-amount-btn').removeClass('active');
    };
  }

  // Form submission setup function
  function setupFormSubmission() {
    // Reset pause action flag
    isPauseAction = false;

    // Handle form submission for both Paid and Pause buttons
    $('button[type="submit"]').on('click', function(e) {
      var paidStatus = $(this).data('paid-status');

      // Set the paid_status value based on which button was clicked
      if (paidStatus !== undefined) {
        $('#paid_status').val(paidStatus);
        console.log('Setting paid_status to:', paidStatus);

        // If it's a pause action (paid_status = 0), set paid_amount to 0.00 and set flag
        if (paidStatus == '0') {
          isPauseAction = true;
          $('#paid_amount').val('0.00');
          console.log('Setting paid_amount to 0.00 for pause action');
        } else {
          isPauseAction = false;
        }
      }
    });
  }
</script>