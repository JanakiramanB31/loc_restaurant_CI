
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manage
      <small>Orders</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Orders</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

        <?php if($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('success'); ?>
          </div>
        <?php elseif($this->session->flashdata('error')): ?>
          <div class="alert alert-error alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?php echo $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>


        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Add Order</h3>
          </div>
          <!-- /.box-header -->
          <form role="form" action="<?php base_url('orders/create') ?>" method="post" class="form-horizontal">
              <div class="box-body">

                <?php echo validation_errors(); ?>

                <div class="w-100" style="display: flex;justify-content:space-between;align-items:center;">
                  <div class="form-group" style="margin:0;">
                    <label for="gross_amount" class="control-label">Date: <?php echo date('d-m-Y') ?></label>
                  </div>
                  <div class="form-group" style="margin:0;">
                    <!-- <label for="gross_amount" class="control-label">Time: <?php echo date('h:i:s a') ?></label> -->
                    <label for="gross_amount" class="control-label">
                      Time: <span id="live-time"></span>
                    </label>
                  </div>
                  <div>
                  <button id="dine-in" type="button" class="btn btn-success">Dine In</button>
                  <button id="take-away" type="button" class="btn btn-success">Take Away</button>
                  <input id="order-type" type="hidden"/>
                  </div>
                </div>

                <!-- <div class="form-group">
                  <label for="gross_amount" class="col-sm-12 control-label">Date: <?php echo date('Y-m-d') ?></label>
                </div>
                <div class="form-group">
                  <label for="gross_amount" class="col-sm-12 control-label">Time: <?php echo date('h:i a') ?></label>
                </div> -->

                <div id="table-list" class="col-md-4 col-xs-12 pull pull-left">

                  <div class="form-group">
                    <label for="gross_amount" class="col-sm-5 control-label" style="text-align:left;">Table</label>
                    <div class="col-sm-7">
                      <select class="form-control" id="table_name" name="table_name">
                        <option value="0">Select Table</option>  
                        <?php foreach ($table_data as $key => $value): ?>
                          <option value="<?php echo $value['id'] ?>"><?php echo $value['table_name'] ?></option>  
                        <?php endforeach ?>
                        
                      </select>
                    </div>
                  </div>

                </div>
                
                
                <br /> <br/>
                <div class="row" style="padding: 10px;">
                <div class="col-md-6" style="min-height:50vh;max-height: 50vh;overflow-y:auto">
                  <div style="width:100%;display:flex;gap:10px;flex-wrap: wrap;margin-top:10px;">
                    <button type="button"  value="0" class="btn btn-success prod-cat">All</button>
                    <?php foreach ($categories_data as $key => $value): ?>
                      <button type="button"  value="<?php echo $value['id'] ?>" class="btn btn-success prod-cat"><?php echo $value['name'] ?></button>
                    <?php endforeach ?>
                  </div>
                  <div>
                    <h3 id="prod-cat-title"></h3>
                    <div id="prod-img-container" style="width:100%;display:flex;gap:10px;flex-wrap: wrap;">

                    </div>

                  </div>
                </div>
                <div class="col-md-6">
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

                  <br /> <br/>

                  <div class="col-md-8 col-xs-12 pull pull-right">

                    <div class="form-group">
                      <label for="gross_amount" class="col-sm-5 control-label">Gross Amount</label>
                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="gross_amount" name="gross_amount" disabled autocomplete="off">
                        <input type="hidden" class="form-control" id="gross_amount_value" name="gross_amount_value" autocomplete="off">
                      </div>
                    </div>
                   <!--  <?php if($is_service_enabled == true): ?>
                    <div class="form-group">
                      <label for="service_charge" class="col-sm-5 control-label">S-Charge: <?php echo $company_data['service_charge_value'] ?> %</label>
                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="service_charge" name="service_charge" disabled autocomplete="off">
                        <input type="hidden" class="form-control" id="service_charge_value" name="service_charge_value" autocomplete="off">
                      </div>
                    </div>
                    <?php endif; ?> -->
                    <!-- <?php if($is_vat_enabled == true): ?>
                    <div class="form-group">
                      <label for="vat_charge" class="col-sm-5 control-label">Vat <?php echo $company_data['vat_charge_value'] ?> %</label>
                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="vat_charge" name="vat_charge" disabled autocomplete="off">
                        <input type="hidden" class="form-control" id="vat_charge_value" name="vat_charge_value" autocomplete="off">
                      </div>
                    </div>
                    <?php endif; ?> -->
                    <div class="form-group">
                      <label for="discount" class="col-sm-5 control-label">Discount</label>
                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="discount" name="discount" placeholder="Discount" onkeyup="subAmount()" autocomplete="off">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="net_amount" class="col-sm-5 control-label">Net Amount</label>
                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="net_amount" name="net_amount" disabled autocomplete="off">
                        <input type="hidden" class="form-control" id="net_amount_value" name="net_amount_value" autocomplete="off">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="paid_amount" class="col-sm-5 control-label">Paid Amount</label>
                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="paid_amount" name="paid_amount" autocomplete="off">
                        <input type="hidden" class="form-control" id="paid_amount_value" name="paid_amount_value" autocomplete="off">
                      </div>
                    </div>

                    <div id="balance_amt" class="form-group" hidden>
                      <label for="bal_amount" class="col-sm-5 control-label">Bal Amount</label>
                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="bal_amount" name="bal_amount" autocomplete="off">
                        <input type="hidden" class="form-control" id="bal_amount_value" name="bal_amount_value" autocomplete="off">
                      </div>
                    </div>

                  </div>
                </div>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
               <!--  <input type="hidden" name="service_charge_rate" value="<?php echo $company_data['service_charge_value'] ?>" autocomplete="off">
                <input type="hidden" name="vat_charge_rate" value="<?php echo $company_data['vat_charge_value'] ?>" autocomplete="off"> -->
                <button type="submit" class="btn btn-success">Create Order</button>
                <a href="<?php echo base_url('orders/') ?>" class="btn btn-danger">Back</a>
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

<script type="text/javascript">
  var base_url = "<?php echo base_url(); ?>";

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

    $('.prod-cat').on('click', function () {
      let prodCatValue = $(this).val();
      let prodCatName = $(this).text();
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
      $.ajax({
        url: base_url + 'products/fetchProductDataByCategory/'+ prodCatValue,
        type: 'get',
        dataType: 'json',
        success:function(response) {
          console.log(response);
          if (response.length > 0) {
            let prodImgData = response.map((data) => `
              <div class="prod-img" style="cursor:pointer;">
                <img src="${base_url}/assets/dist/img/products/default_image.png" alt="${data.name}" class="img-circle" data-id="${data.id}" data-name="${data.name}" data-price="${data.price}" width="100" height="80" />
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
        error: function(xhr) {
          let errorMessage = `
            <div style="width:100%;text-align:center;">
              <p>An error occurred. Please try again.</p>
            </div>
          `;
          $('#prod-img-container').html(errorMessage).show();
        }
      });
    });

    $(document).on('click', '.prod-img', function () {
      let prodID = $(this).find('img').data('id');
      let prodName = $(this).find('img').data('name');
      let prodPrice = $(this).find('img').data('price');
      console.log(prodID, prodName, prodPrice);

      $('#default-table-row').remove();

      let table = $("#product_info_table");
      let count_table_tbody_tr = $("#product_info_table tbody tr").length;
      let row_id = count_table_tbody_tr + 1;

      /* <select class="form-control select_group product" data-row-id="${row_id}" id="product_${row_id}" name="product[]" style="width:100%;" disabled>
        <option value="${prodID}">${prodName}</option>
      </select> */

      let html =`
        <tr id="row_${row_id}">
          <td>
            <input type="text" title="${prodName}" value="${prodName}" data-row-id="${row_id}" id="product_${row_id}" class="form-control" disabled>
            <input type="hidden" value="${prodID}" data-row-id="${row_id}" name="product[]" id="product_${row_id}" class="form-control" disabled>
          </td>
          <td style="display:flex;">
            <button type="button" class="qty-minus" data-id="qty_${row_id}"><i class="fa fa-minus"></i></button>
            <input type="number" value="1" name="qty[]" id="qty_${row_id}" class="form-control" onkeyup="getTotal(${row_id})">
            <button type="button" class="qty-add" data-id="qty_${row_id}"><i class="fa fa-plus"></i></button>
            </td>
          <td>
            <input type="text" value="${prodPrice}" name="rate[]" id="rate_${row_id}" class="form-control" disabled>
            <input type="hidden" value="${prodPrice}" name="rate_value[]" id="rate_value_${row_id}" class="form-control">
          </td>
          <td>
            <input type="text" value="${prodPrice}" name="amount[]" id="amount_${row_id}" class="form-control" disabled>
            <input type="hidden" value="${prodPrice}" name="amount_value[]" id="amount_value_${row_id}" class="form-control">
          </td>
          <td>
            <button type="button" class="btn btn-default" onclick="removeRow('${row_id}')">
              <i class="fa fa-close"></i>
            </button>
          </td>
        </tr>
      `;

      if(count_table_tbody_tr >= 1) {
        $("#product_info_table tbody tr:last").after(html);  
      }
      else {
        $("#product_info_table tbody").html(html);
      }
      subAmount();
    });

    $('#paid_amount').on('keyup', function() {
      let paidAmt = Number($(this).val());
      let netAmt = Number($('#net_amount').val());

      if (paidAmt > netAmt) {
        $('#balance_amt').attr('hidden', false);
        let balAmt = Number(paidAmt - netAmt).toFixed(2);
        $('#bal_amount').val(balAmt);
      } else {
        $('#balance_amt').attr('hidden', true);
        $('#bal_amount').val('');
      }
    });

    $(document).on('click', '.qty-add',function () {
      let qtyID = $(this).data('id');
      let qtyVal = Number($(`#${qtyID}`).val());
      console.log("qtyID", qtyID, qtyVal)
      $(`#${qtyID}`).val(qtyVal + 1);
    });

    $(document).on('click', '.qty-minus',function () {
      let qtyID = $(this).data('id');
      let qtyVal = Number($(`#${qtyID}`).val());
      console.log("qtyID", qtyID, qtyVal)
      $(`#${qtyID}`).val(qtyVal - 1);
    });

  }); // /document

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
    // var service_charge = <?php echo ($company_data['service_charge_value'] > 0) ? $company_data['service_charge_value']:0; ?>;
    // var vat_charge = <?php echo ($company_data['vat_charge_value'] > 0) ? $company_data['vat_charge_value']:0; ?>;

    var tableProductLength = $("#product_info_table tbody tr").length;
    var totalSubAmount = 0;
    for(x = 0; x < tableProductLength; x++) {
      var tr = $("#product_info_table tbody tr")[x];
      var count = $(tr).attr('id');
      count = count.substring(4);

      totalSubAmount = Number(totalSubAmount) + Number($("#amount_"+count).val());
    } // /for

    totalSubAmount = totalSubAmount.toFixed(2);
    console.log("totalSubAmount", totalSubAmount)

    // sub total
    $("#gross_amount").val(totalSubAmount);
    $("#gross_amount_value").val(totalSubAmount);

    // vat
    // var vat = (Number($("#gross_amount").val())/100) * vat_charge;
    // vat = vat.toFixed(2);
    // $("#vat_charge").val(vat);
    // $("#vat_charge_value").val(vat);

    // service
    // var service = (Number($("#gross_amount").val())/100) * service_charge;
    // service = service.toFixed(2);
    // $("#service_charge").val(service);
    // $("#service_charge_value").val(service);
    
    // total amount
    var totalAmount = (Number(totalSubAmount));
    totalAmount = totalAmount.toFixed(2);
    // $("#net_amount").val(totalAmount);
    // $("#totalAmountValue").val(totalAmount);

    var discount = $("#discount").val();
    if(discount) {
      var grandTotal = Number(totalAmount) - Number(discount);
      grandTotal = grandTotal.toFixed(2);
      $("#net_amount").val(grandTotal);
      $("#net_amount_value").val(grandTotal);
    } else {
      $("#net_amount").val(totalAmount);
      $("#net_amount_value").val(totalAmount);
      
    } // /else discount 

  } // /sub total amount

  function removeRow(tr_id)
  {
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

  
</script>