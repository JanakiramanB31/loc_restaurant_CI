<style type='text/css'>
  .pos-order button, .pos-order .btn {
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
      <h3 class="box-title">Edit POS Order</h3>
    </div>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo base_url('pos_orders/') ?>">POS Orders</a></li>
      <li class="active">Edit</li>
    </ol>
  </section> -->

  <!-- Main content -->
  <section class="content pos-order">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

        <?php if($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php elseif($this->session->flashdata('errors')): ?>
          <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('errors'); ?>
          </div>
        <?php endif; ?>


        <div class="box">
          <!-- /.box-header -->
          <form role="form" action="<?php echo base_url('pos_orders/update/'.$order_data['order']['id']) ?>" method="post" class="form-horizontal">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                

                <div class="w-100" style="display: flex;justify-content:space-between;align-items:center;">
                <div class="form-group" style="margin:0;">
                  <label for="gross_amount" class="control-label" style="margin:0px 20px">Date: <?php echo date('d-m-Y') ?></label>
                  <label for="gross_amount" class="control-label">
                    Time: <span id="live-time"></span>
                  </label>
                  <label for="gross_amount" class="control-label" style="margin:0px 20px">Bill No: <?php echo $order_data['order']['bill_no'] ?></label>
                </div>

                <div>
                  <div class="form-group" style="margin:0;">
                    <div class="col-sm-12">
                      <span id='table-list' style="margin:0px 20px" hidden>
                        <?php // echo '<pre>'; print_r($table_data); echo '</pre>';
                        foreach ($table_data as $key => $value): ?>
                          <?php 
                            $className = 'btn-warning';
                            if ($order_data['order']['table_id'] == $value['id']) {
                              $className = 'btn-info'; 
                            }
                            $className;
                          ?>
                          <button style="margin:0px 3px" type="button" class="btn table-select <?php echo $className; ?>" id="<?php echo $value['id'] ?>">
                            &nbsp;<?php echo $value['id'] ?>&nbsp;
                          </button>
                        <?php endforeach ?>
                      </span>
                      <button id="dine-in" type="button" class="btn btn-success">Dine In</button>
                      <button id="take-away" type="button" class="btn btn-success">Take Away</button>
                      <input id="order-type" type="hidden" value="<?php echo $order_data['order']['table_id'] > 0 ? 'Dine In' : 'Take Away'; ?>"/>
                      <input id="table_name" type="hidden" name="table_name" value="<?php echo $order_data['order']['table_id']; ?>"/>
                    </div>
                  </div>

                </div>
              </div>

                
                
                <br /> <br/>
                <div class="row" style="padding: 10px;">
                <div class="col-md-6" style="min-height:60vh;max-height: 60vh;overflow-y:auto">
                  <div style="width:100%;display:flex;gap:10px;flex-wrap: wrap;margin-top:10px;">
                    <button type="button" value="0" class="btn btn-success prod-cat">All</button>
                    <?php 
                    $categories_data = $this->model_category->getActiveCategory();
                    foreach ($categories_data as $key => $value): ?>
                      <button type="button" value="<?php echo $value['id'] ?>" class="btn btn-success prod-cat"><?php echo $value['name'] ?></button>
                    <?php endforeach ?>
                  </div>
                  <div>
                    <h3 id="prod-cat-title">All Products</h3>
                    <div id="prod-img-container" style="width:100%;display:flex;gap:10px;flex-wrap: wrap;">
                      <!-- Products will be loaded here via AJAX -->
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                    <table class="table table-bordered table-hover" id="product_info_table">
                      <thead>
                        <tr>
                          <th style="width:25%">Product</th>
                          <th style="width:25%">Qty</th>
                          <th style="width:15%">Rate</th>
                          <th style="width:20%">Amount</th>
                          <th style="width:10%"></th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php if(isset($order_data['order_item']) && !empty($order_data['order_item'])): ?>
                          <?php $x = 1; ?>
                          <?php foreach ($order_data['order_item'] as $key => $val): ?>
                            <?php 
                            $product_data = $this->model_products->getProductData($val['product_id']); 
                            ?>
                           <tr id="row_<?php echo $x; ?>">
                             <td>
                              <input type="text" title="<?php echo $product_data['name'] ?>" value="<?php echo $product_data['name'] ?>" data-row-id="<?php echo $x ?>" id="product_<?php echo $x ?>" class="form-control" disabled>
                              <input type="hidden" value="<?php echo $val['product_id'] ?>" data-row-id="<?php echo $x ?>" name="product[]" class="form-control">
                            </td>
                            <td style="display:flex;">
                              <button type="button" class="qty-minus" data-id="qty_<?php echo $x ?>"><i class="fa fa-minus"></i></button>
                              <input type="text" value="<?php echo $val['qty'] ?>" name="qty[]" id="qty_<?php echo $x ?>" class="form-control" onkeyup="getTotal(<?php echo $x ?>)">
                              <button type="button" class="qty-add" data-id="qty_<?php echo $x ?>"><i class="fa fa-plus"></i></button>
                            </td>
                            <td>
                              <input type="text" value="<?php echo number_format($val['rate'], 2) ?>" name="rate[]" id="rate_<?php echo $x ?>" class="form-control" disabled>
                              <input type="hidden" value="<?php echo $val['rate'] ?>" name="rate_value[]" id="rate_value_<?php echo $x ?>" class="form-control">
                            </td>
                            <td>
                              <input type="text" value="<?php echo number_format($val['amount'], 2) ?>" name="amount[]" id="amount_<?php echo $x ?>" class="form-control" disabled>
                              <input type="hidden" value="<?php echo $val['amount'] ?>" name="amount_value[]" id="amount_value_<?php echo $x ?>" class="form-control">
                            </td>
                            <td>
                              <button type="button" class="btn btn-danger" onclick="removeProductFunc('<?php echo $x ?>', '<?php echo $product_data['name'] ?>')">
                                <i class="fa fa-close"></i>
                              </button>
                            </td>
                           </tr>
                           <?php $x++; ?>
                         <?php endforeach; ?>
                       <?php else: ?>
                        <tr id="default-table-row">
                          <td colspan="5" style="text-align: center;">No Product Selected</td>
                        </tr>
                       <?php endif; ?>
                      </tbody>
                    </table>
                  </div>

                  <br /> <br/>

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
                    <div class="col-md-6 col-xs-12" style="padding-right: 10px;">
                      <div class="form-group" hidden>
                        <label for="gross_amount" class="col-sm-7 control-label">Gross Amount</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control" id="gross_amount" name="gross_amount" disabled value="<?php echo number_format($order_data['order']['gross_amount'], 2) ?>" autocomplete="off" style="text-align: right;">
                          <input type="hidden" class="form-control" id="gross_amount_value" name="gross_amount_value" value="<?php echo $order_data['order']['gross_amount'] ?>" autocomplete="off">
                        </div>
                      </div>
                    
                      <div class="form-group">
                        <label for="discount" class="col-sm-5 control-label">Discount</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control" id="discount" name="discount" placeholder="Discount" onkeyup="subAmount()" value="<?php echo $order_data['order']['discount'] ?>" autocomplete="off" style="text-align: right;">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="net_amount" class="col-sm-5 control-label">Total Amt</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control" id="net_amount" name="net_amount" disabled value="<?php echo number_format($order_data['order']['net_amount'], 2) ?>" autocomplete="off" style="text-align: right;">
                          <input type="hidden" class="form-control" id="net_amount_value" name="net_amount_value" value="<?php echo $order_data['order']['net_amount'] ?>" autocomplete="off">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="paid_amount" class="col-sm-5 control-label">Paid Amt</label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control" id="paid_amount" name="paid_amount" autocomplete="off" value="<?php echo number_format($order_data['order']['paid_amount'], 2) ?>" placeholder="0.00" style="text-align: right;">
                          <input type="hidden" class="form-control" id="paid_amount_value" name="paid_amount_value" value="<?php echo $order_data['order']['paid_amount'] ?>" autocomplete="off">
                        </div>
                      </div>

                      <!-- <div class="form-group" hidden>
                        <label for="paid_status" class="col-sm-5 control-label">Paid Status</label>
                        <div class="col-sm-7">
                          <select type="text" class="form-control" id="paid_status" name="paid_status">
                            <option value="1" <?php echo ($order_data['order']['paid_status'] == 1) ? 'selected' : ''; ?>>Paid</option>
                            <option value="0" <?php echo ($order_data['order']['paid_status'] == 0) ? 'selected' : ''; ?>>Unpaid</option>
                          </select>
                        </div>
                      </div> -->

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
                        <label for="bal_amount" class="col-sm-5 control-label">Bal Amount</label>
                        <div class="col-sm-7">
                          <input type="hidden" class="form-control" id="bal_amount" name="bal_amount" autocomplete="off" readonly style="text-align: right;">
                        </div>
                      </div>

                      <div id="amount_due" class="form-group" hidden>
                        <label for="due_amount" class="col-sm-5 control-label">Amount Due</label>
                        <div class="col-sm-7">
                          <input type="hidden" class="form-control" id="due_amount" name="due_amount" autocomplete="off" readonly style="text-align: right;">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer" style="display: flex; justify-content: flex-end;gap: 5px;">
                <input type="hidden" name="paid_status" id="paid_status" value="<?php echo $order_data['order']['paid_status']; ?>">
                <button type="submit" class="btn btn-success" data-paid-status="1">Paid</button>
                <button type="submit" class="btn btn-warning" data-paid-status="0"><i class="fa fa-pause"></i></button>
                <a href="<?php echo base_url('pos_orders/') ?>" class="btn btn-danger">Cancel</a>
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
    flex-direction: column;
    justify-content: flex-end;
    align-items: flex-end;
    height: 100%;
   /*  min-height: 200px; */
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
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 0;
    flex: 1;
    min-width: 0;
    white-space: nowrap;
    border: 2px solid transparent;
  }
  
  .quick-amount-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
  }
  
  .quick-amount-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  .quick-amount-btn.active {
    background-color: #007bff !important;
    border-color: #007bff !important;
    color: #fff !important;
    box-shadow: 0 4px 8px rgba(0,123,255,0.3) !important;
  }
  
  .exact-amount-btn {
    /* No special sizing needed in 2x2 grid layout */
  }
  
  .quick-amount-btn:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.25);
  }
  
  #exact-amount-text {
    word-wrap: break-word;
    text-align: center;
    line-height: 1.1;
    overflow: hidden;
    text-overflow: ellipsis;
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
  var currentCategoryRequest = null;
  var isLoadingCategory = false;
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

    $("#POSOrderMainNav").addClass('active');
    $("#managePOSOrderSubMenu").addClass('active');
    
    // Initialize exact amount button
    updateExactAmountButton();
    
    // Load all products initially
    loadProductsByCategory(0, 'All Products');
    
    // Input validation for numeric fields
    setupNumericValidation();
    
    // Setup form validation
    setupFormValidation();
    
    // Setup real-time validation to hide errors when valid values are entered
    setupRealTimeValidation();
    
    // Setup form submission handling for different button types
    setupFormSubmission();
    
    // Initialize balance/due calculations and trigger calculation on load
    $('#paid_amount').trigger('keyup');
    
    // Set order type based on existing data
    var orderType = $('#order-type').val();
    if (orderType === 'Dine In') {
      $('#dine-in').addClass('btn-info');
      $('#table-list').attr('hidden', false);
    } else {
      $('#take-away').addClass('btn-info');
      $('#table-list').attr('hidden', true);
    }
    
    $('#dine-in, #take-away').on('click', function() {
      let orderType = $(this).text();
      $('#order-type').val(orderType);

      $('#dine-in, #take-away').removeClass('btn-info');
      $(this).addClass('btn-info');

      if (orderType == "Dine In") {
        $('#table-list').attr('hidden', false);
      } else {
        $('#table-list').attr('hidden', true);
      }
    });
    setupFormValidation();

    $(document).on('click', '.table-select', function() {
      let tableId = $(this).attr('id');
      let tableName = $(this).text().trim();
      $('#table_name').val(tableName);
      console.log('Selected Table:', tableName);

      $('.table-select').removeClass('btn-info').addClass('btn-warning');
      $(this).removeClass('btn-warning').addClass('btn-info');
      $('#table_name').val(tableName);
    });

    $('.prod-cat').on('click', function () {
      if (isLoadingCategory) {
        return;
      }

      let prodCatValue = $(this).val();
      let prodCatName = $(this).text();
      
      loadProductsByCategory(prodCatValue, prodCatName);
    });

    $(document).on('click', '.prod-img', function () {
      let prodID = $(this).find('img').data('id');
      let prodName = $(this).find('img').data('name');
      let prodPrice = $(this).find('img').data('price');

      $('#default-table-row').remove();

      // Check if product already exists in the table
      let existingProductRow = null;
      $("#product_info_table tbody tr").each(function() {
        let existingProdID = $(this).find('input[name="product[]"]').val();
        if (existingProdID == prodID) {
          existingProductRow = $(this);
          return false;
        }
      });

      if (existingProductRow) {
        // Product already exists, increment quantity
        let rowId = existingProductRow.attr('id').replace('row_', '');
        let currentQty = parseInt($('#qty_' + rowId).val()) || 0;
        let newQty = currentQty + 1;
        $('#qty_' + rowId).val(newQty);
        
        // Trigger quantity change to recalculate totals
        getTotal(rowId);
        
        // Hide product errors since we now have products
        if (typeof hideProductErrorIfExists === 'function') {
          hideProductErrorIfExists();
        }
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
              <input type="hidden" value="${prodID}" data-row-id="${row_id}" name="product[]" class="form-control">
            </td>
            <td style="display:flex;">
              <button type="button" class="qty-minus" data-id="qty_${row_id}"><i class="fa fa-minus"></i></button>
              <input type="text" value="1" name="qty[]" id="qty_${row_id}" class="form-control" onkeyup="getTotal(${row_id})">
              <button type="button" class="qty-add" data-id="qty_${row_id}"><i class="fa fa-plus"></i></button>
            </td>
            <td>
              <input type="text" value="${Number(prodPrice).toFixed(2)}" name="rate[]" id="rate_${row_id}" class="form-control" disabled>
              <input type="hidden" value="${prodPrice}" name="rate_value[]" id="rate_value_${row_id}" class="form-control">
            </td>
            <td>
              <input type="text" value="${Number(prodPrice).toFixed(2)}" name="amount[]" id="amount_${row_id}" class="form-control" disabled>
              <input type="hidden" value="${prodPrice}" name="amount_value[]" id="amount_value_${row_id}" class="form-control">
            </td>
            <td>
              <button type="button" class="btn btn-danger" onclick="removeProductFunc('${row_id}', '${prodName}')">
                <i class="fa fa-close"></i>
              </button>
            </td>
          </tr>
        `;

        if(count_table_tbody_tr >= 1) {
          $("#product_info_table tbody tr:last").after(html);  
        } else {
          $("#product_info_table tbody").html(html);
        }
        subAmount();
        
        // Hide product errors since we now have products
        if (typeof hideProductErrorIfExists === 'function') {
          hideProductErrorIfExists();
        }
      }
    });

    $(document).on('click', '.qty-add', function () {
      let qtyID = $(this).data('id');
      let qtyVal = Number($(`#${qtyID}`).val());
      $(`#${qtyID}`).val(qtyVal + 1);
      
      let rowId = qtyID.replace('qty_', '');
      getTotal(rowId);
    });

    $(document).on('click', '.qty-minus', function () {
      let qtyID = $(this).data('id');
      let qtyVal = Number($(`#${qtyID}`).val());
      
      if (qtyVal > 1) {
        $(`#${qtyID}`).val(qtyVal - 1);
        let rowId = qtyID.replace('qty_', '');
        getTotal(rowId);
      } else if (qtyVal === 1) {
        let rowId = qtyID.replace('qty_', '');
        let productName = $(`#product_${rowId}`).val();
        removeProductFunc(rowId, productName);
      }
    });

  }); // /document

  function loadProductsByCategory(categoryId, categoryName) {
    if (currentCategoryRequest && currentCategoryRequest.readyState !== 4) {
      currentCategoryRequest.abort();
    }

    isLoadingCategory = true;
    
    $('#prod-cat-title').text(categoryName);
    $('.prod-cat').removeClass('btn-info');
    $(`.prod-cat[value="${categoryId}"]`).addClass('btn-info');
    $('#prod-img-container').html('<div style="width:100%;text-align:center;"><p>Loading...</p></div>');

    currentCategoryRequest = $.ajax({
      url: base_url + 'products/fetchProductDataByCategory/' + categoryId,
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
          $('#prod-img-container').html('<div style="width:100%;text-align:center;"><p>No Products for this Category</p></div>');
        }
      },
      error: function(xhr, status, error) {
        if (status !== 'abort') {
          $('#prod-img-container').html('<div style="width:100%;text-align:center;"><p>An error occurred. Please try again.</p></div>');
        }
      },
      complete: function(xhr, status) {
        isLoadingCategory = false;
      }
    });
  }

  function getTotal(row = null) {
    if(row) {
      var total = Number($("#rate_value_"+row).val()) * Number($("#qty_"+row).val());
      total = total.toFixed(2);
      $("#amount_"+row).val(total);
      $("#amount_value_"+row).val(total);
      
      subAmount();
    } else {
      alert('no row !! please refresh the page');
    }
  }

  // calculate the total amount of the order
  function subAmount() {
    // Clear paid amount errors when amounts change
    if (typeof clearPaidAmountErrors === 'function') {
      clearPaidAmountErrors();
    }
    
    var tableProductLength = $("#product_info_table tbody tr").length;
    var totalSubAmount = 0;
    for(x = 0; x < tableProductLength; x++) {
      var tr = $("#product_info_table tbody tr")[x];
      var count = $(tr).attr('id');
      if(count && count.indexOf('row_') !== -1) {
        count = count.substring(4);
        totalSubAmount = Number(totalSubAmount) + Number($("#amount_"+count).val());
      }
    }

    totalSubAmount = totalSubAmount.toFixed(2);

    // sub total
    $("#gross_amount").val(totalSubAmount);
    $("#gross_amount_value").val(totalSubAmount);
    
    // total amount with discount
    var discount = $("#discount").val();
    if(discount) {
      var grandTotal = Number(totalSubAmount) - Number(discount);
      grandTotal = grandTotal.toFixed(2);
      $("#net_amount").val(grandTotal);
      $("#net_amount_value").val(grandTotal);
    } else {
      $("#net_amount").val(totalSubAmount);
      $("#net_amount_value").val(totalSubAmount);
    }
    
    // Update exact amount button with new total
    updateExactAmountButton();
  }

  function removeRow(tr_id) {
    $("#product_info_table tbody tr#row_"+tr_id).remove();
    subAmount();
    
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
    if(rowId) {
      $('#removeProductMessage').text(`Do you really want to remove "${productName}"?`);
      $('#removeProductModal').modal('show');
      
      $("#removeProductForm").off('submit').on('submit', function() {
        removeRow(rowId);
        $("#removeProductModal").modal('hide');
        return false;
      });
    }
  }

  // Numeric validation setup function
  function setupNumericValidation() {
    $('#paid_amount').on('input keyup', function() {
      validateNumericInput($(this), true);
    });

    $('#discount').on('input keyup', function() {
      validateNumericInput($(this), true);
    });

    $(document).on('input keyup', 'input[name="qty[]"]', function() {
      validateNumericInput($(this), false);
    });
  }

  function validateNumericInput(inputElement, allowDecimals) {
    var value = inputElement.val();
    var hasError = false;
    
    if (value === '') {
      inputElement.removeClass('input-error');
      return;
    }

    var cleanedValue = value;
    
    if (allowDecimals) {
      cleanedValue = value.replace(/[^0-9.]/g, '');
      var parts = cleanedValue.split('.');
      if (parts.length > 2) {
        cleanedValue = parts[0] + '.' + parts.slice(1).join('');
      }
      if (parts[1] && parts[1].length > 2) {
        cleanedValue = parts[0] + '.' + parts[1].substring(0, 2);
      }
    } else {
      cleanedValue = value.replace(/[^0-9]/g, '');
    }
    
    if (cleanedValue !== value) {
      hasError = true;
      inputElement.val(cleanedValue);
    }
    
    var numericValue = allowDecimals ? parseFloat(cleanedValue) : parseInt(cleanedValue);
    if (isNaN(numericValue) || numericValue < 0) {
      hasError = true;
      inputElement.val(allowDecimals ? '0.00' : '1');
    }
    
    if (hasError) {
      inputElement.addClass('input-error');
    } else {
      inputElement.removeClass('input-error');
    }
  }

  // Real-time payment validation and balance/due amount calculations
  function setupRealTimeValidation() {
    // Real-time calculation for paid amount
    $('#paid_amount').on('input keyup', function() {
      let paidAmtValue = $(this).val();
      let paidAmt = Number(paidAmtValue);
      let netAmt = Number($('#net_amount').val());

      // Immediately clear errors when user starts typing
      if (paidAmtValue && paidAmtValue.trim() !== '') {
        // Remove error styling from the field
        $(this).removeClass('input-error');
        
        // Remove all error messages related to paid amount
        $(this).siblings('.field-error').fadeOut(100, function() { $(this).remove(); });
        $(this).closest('.form-group').siblings('.field-error').fadeOut(100, function() { $(this).remove(); });
        $('.quick-amount-buttons').siblings('.field-error').fadeOut(100, function() { $(this).remove(); });
        $('.quick-amount-buttons').prev('.field-error').fadeOut(100, function() { $(this).remove(); });
        
        // Remove any error messages that contain "Paid amount"
        $('.field-error').filter(function() {
          return $(this).text().indexOf('Paid amount') !== -1 || $(this).text().indexOf('paid amount') !== -1;
        }).fadeOut(100, function() { $(this).remove(); });
      }

      // Clear active state from quick amount buttons when manually typing
      $('.quick-amount-btn').removeClass('active');

      // Show balance field if there's a paid amount and it's different from net amount
      if (paidAmtValue && paidAmtValue.trim() !== '' && paidAmt !== netAmt) {
        let balAmt = Number(paidAmt - netAmt);
        
        if (balAmt > 0) {
          // Customer overpaid - show balance
          $('#balance-form-group').show();
          $('#due-form-group').hide();
          $('#balance-amount').text(balAmt.toFixed(2));
          
          // Set hidden field values
          $('#bal_amount').val(balAmt.toFixed(2));
          $('#due_amount').val('0.00');
        } else if (balAmt < 0) {
          // Customer underpaid - show due amount
          $('#due-form-group').show();
          $('#balance-form-group').hide();
          $('#due-amount-display').text(Math.abs(balAmt).toFixed(2));
          
          // Set hidden field values
          $('#due_amount').val(Math.abs(balAmt).toFixed(2));
          $('#bal_amount').val('0.00');
        } else {
          // Exact amount - hide both
          $('#balance-form-group').hide();
          $('#due-form-group').hide();
          $('#bal_amount').val('0.00');
          $('#due_amount').val('0.00');
        }
      } else {
        // Hide form groups and reset values when paid amount is empty
        $('#balance-form-group').hide();
        $('#due-form-group').hide();
        $('#bal_amount').val('0.00');
        $('#due_amount').val('0.00');
      }
    });
    
    // Real-time calculation for discount
    $('#discount').on('input keyup', function() {
      subAmount();
      updateExactAmountButton();
      
      // Recalculate balance/due when discount changes
      $('#paid_amount').trigger('keyup');
    });
  }
  
  // Function to update exact amount button text
  function updateExactAmountButton() {
    var netAmount = $("#net_amount").val() || '0';
    var displayAmount = Number(netAmount).toFixed(2); // Show decimal format
    $('#exact-amount-text').text(displayAmount);
  }
  
  // Quick amount buttons functionality
  $(document).on('click', '.quick-amount-btn', function() {
    let amount = $(this).data('amount');
    
    // Remove active class from all buttons
    $('.quick-amount-btn').removeClass('active');
    // Add active class to clicked button
    $(this).addClass('active');
    
    if (amount === 'exact') {
      // Set paid amount to exact net amount (use the actual value, not display)
      let netAmountValue = $('#net_amount_value').val();
      let exactAmount = Number(netAmountValue).toFixed(2);
      $('#paid_amount').val(exactAmount);
    } else {
      // Set to fixed amount
      $('#paid_amount').val(Number(amount).toFixed(2));
    }
    
    // Trigger paid amount change to update balance/due display
    $('#paid_amount').trigger('keyup');
  });

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
          
          if (!qtyValue || qtyValue.trim() === '' || isNaN(qtyValue) || parseFloat(qtyValue) <= 0) {
            if (!firstInvalidQtyField) {
              firstInvalidQtyField = qtyInput;
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
    } else {
      // Default: show error after the field
      field.after(errorHtml);
      errorElement = field.next('.field-error');
    }
    
    // Auto-scroll to the error if it's not visible
    if (errorElement && errorElement.length > 0) {
      setTimeout(function() {
        var elementTop = errorElement.offset().top;
        var windowHeight = $(window).height();
        var scrollTop = $(window).scrollTop();
        
        if (elementTop < scrollTop || elementTop > scrollTop + windowHeight) {
          $('html, body').animate({
            scrollTop: elementTop - 100
          }, 300);
        }
      }, 100);
    }
  }

  // Error clearing functions
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
      $('.table-responsive').next('.field-error').fadeOut(200, function() { $(this).remove(); });
      // Also check for errors below the table
      $('#product_info_table').closest('.table-responsive').next('.field-error').fadeOut(200, function() { $(this).remove(); });
    }
  };

  clearPaidAmountErrors = function() {
    // Remove error styling from paid amount field
    $('#paid_amount').removeClass('input-error');
    
    // Remove error messages near paid amount field (multiple possible locations)
    $('#paid_amount').siblings('.field-error').fadeOut(200, function() { $(this).remove(); });
    $('.quick-amount-buttons').siblings('.field-error').fadeOut(200, function() { $(this).remove(); });
    $('.quick-amount-buttons').next('.field-error').fadeOut(200, function() { $(this).remove(); });
    $('#paid_amount').closest('.form-group').next('.field-error').fadeOut(200, function() { $(this).remove(); });
    
    // Also remove any error messages that contain "Paid amount"
    $('.field-error').filter(function() {
      return $(this).text().indexOf('Paid amount') !== -1;
    }).fadeOut(200, function() { $(this).remove(); });
    
    // Clear paid amount field to avoid confusion with new totals
    $('#paid_amount').val('');
    
    // Hide form groups
    $('#balance-form-group').hide();
    $('#due-form-group').hide();
    
    // Clear active state from quick amount buttons
    $('.quick-amount-btn').removeClass('active');
  };

  // Form submission setup function
  function setupFormSubmission() {
    // Reset pause action flag
    isPauseAction = false;
    
    // Handle form submission for both Paid and Pause buttons
    $('button[type="submit"]').on('click', function(e) {
      e.preventDefault(); // Prevent immediate form submission
      
      var paidStatus = $(this).data('paid-status');
      var $form = $(this).closest('form');
      
      console.log('Button clicked with data-paid-status:', paidStatus);
      console.log('Button text/html:', $(this).html());
      
      // Set the paid_status value based on which button was clicked
      if (paidStatus !== undefined) {
        $('#paid_status').val(paidStatus);
        console.log('Setting paid_status to:', paidStatus);
        console.log('Hidden field value after setting:', $('#paid_status').val());
        
        // If it's a pause action (paid_status = 0), set paid_amount to 0.00 and set flag
        if (paidStatus == '0') {
          isPauseAction = true;
          $('#paid_amount').val('0.00');
          console.log('Setting paid_amount to 0.00 for pause action');
        } else {
          isPauseAction = false;
          console.log('Setting isPauseAction to false for paid action');
        }
        
        // Small delay to ensure values are set, then submit the form
        setTimeout(function() {
          console.log('Final paid_status value before submit:', $('#paid_status').val());
          $form.submit();
        }, 100);
      } else {
        console.log('No data-paid-status found on clicked button');
        // For buttons without data-paid-status, just submit normally
        $form.submit();
      }
    });
  }

</script>