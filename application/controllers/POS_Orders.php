<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class POS_Orders extends Admin_Controller 
{
	var $currency_code = '';

	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'POS Orders';

		$this->load->model('model_pos_orders');
		$this->load->model('model_category');
		$this->load->model('model_tables');
		$this->load->model('model_products');
		$this->load->model('model_company');
		$this->load->model('model_stores');

		$this->currency_code = $this->company_currency();
	}

	/* 
	* It only redirects to the manage order page
	*/
	public function index()
	{
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->data['page_title'] = 'Manage POS Orders';
		$this->render_template('pos_orders/index', $this->data);		
	}

	/*
	* Fetches the orders data from the orders table 
	* this function is called from the datatable ajax function
	*/
	public function fetchOrdersData()
	{
		if(!in_array('viewOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$result = array('data' => array());

		$data = $this->model_pos_orders->getOrdersData();

		foreach ($data as $key => $value) {
			$order_type = 'Take Away';
			if ($value['table_id']) {
				$order_type = 'Table - '.$value['table_id']; 
			}

			$store_data = $this->model_stores->getStoresData($value['store_id']);

			$count_total_item = $this->model_pos_orders->countOrderItem($value['id']);
			$date = date('d-m-Y', $value['date_time']);
			// $time = date('h:i a', $value['date_time']);

			$date_time = $date; //.' '.$time

			// button
			$buttons = '';

			if(in_array('viewOrder', $this->permission)) {
				$buttons .= '<a target="_blank" href="'.base_url('pos_orders/modernPrint/'.$value['id']).'" class="btn btn-success" title="Receipt Print"><i class="fa fa-print fa-2x"></i></a>';
				$buttons .= ' <a target="_blank" href="'.base_url('pos_orders/kitchenPrint/'.$value['id']).'" class="btn btn-warning" title="Kitchen Print"><i class="fa fa-file-text-o fa-2x"></i></a>';
			}

			if(in_array('updateOrder', $this->permission)) {
				$buttons .= ' <a href="'.base_url('pos_orders/update/'.$value['id']).'" class="btn btn-info"><i class="fa fa-pencil fa-2x"></i></a>';
			}

			if(in_array('deleteOrder', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-danger" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash fa-2x"></i></button>';
			}

			if($value['paid_status'] == 1) {
				$paid_status = '<h4><span class="label label-success">Paid</span></h4>';	
			}
			else {
				$paid_status = '<h4><span class="label label-danger">Not Paid</span></h4>';
			}

			$result['data'][$key] = array(
				$value['bill_no'],
				$order_type,
				$date_time,
				$count_total_item,
				$value['net_amount'],
				$paid_status,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	/*
	* If the validation is not valid, then it redirects to the create page.
	* If the validation for each input field is valid then it inserts the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function create()
	{
		if(!in_array('createOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
	
		$this->data['page_title'] = 'Add Order';

		// Check if this is a pause action
		$is_pause = ($this->input->post('paid_status') == '0');

		// Order validation rules
		$this->form_validation->set_rules('product[]', 'Product', 'trim|required', array(
			'required' => 'Please select at least one product for the order.'
		));
		
		$this->form_validation->set_rules('qty[]', 'Quantity', 'trim|required|numeric|greater_than[0]', array(
			'required' => 'Product quantity is required.',
			'numeric' => 'Quantity must be a valid number.',
			'greater_than' => 'Quantity must be greater than 0.'
		));
		
		$this->form_validation->set_rules('gross_amount_value', 'Gross Amount', 'trim|required|numeric|greater_than[0]', array(
			'required' => 'Gross amount is required.',
			'numeric' => 'Gross amount must be a valid number.',
			'greater_than' => 'Gross amount must be greater than 0.'
		));
		
		$this->form_validation->set_rules('net_amount_value', 'Net Amount', 'trim|required|numeric|greater_than[0]', array(
			'required' => 'Net amount is required.',
			'numeric' => 'Net amount must be a valid number.',
			'greater_than' => 'Net amount must be greater than 0.'
		));
		
		$this->form_validation->set_rules('discount', 'Discount', 'trim|numeric|greater_than_equal_to[0]', array(
			'numeric' => 'Discount must be a valid number.',
			'greater_than_equal_to' => 'Discount cannot be negative.'
		));

		$this->form_validation->set_rules('payment_type', 'Payment Type', 'trim|in_list[cash,card]',
			array('in_list' => 'Payment Type must be either cash or card.')
		);
		
		// Only validate paid_amount for non-pause actions
		if (!$is_pause) {
			$this->form_validation->set_rules('paid_amount', 'Paid Amount', 'trim|numeric|greater_than_equal_to[0]', array(
				'numeric' => 'Paid amount must be a valid number.',
				'greater_than_equal_to' => 'Paid amount cannot be negative.'
			));
		}
		
		// Table validation for Dine In orders
		if($this->input->post('table_name') && $this->input->post('table_name') != '0') {
			$this->form_validation->set_rules('table_name', 'Table', 'trim|required|numeric|greater_than[0]', array(
				'required' => 'Please select a table for dine-in orders.',
				'numeric' => 'Invalid table selection.',
				'greater_than' => 'Please select a valid table.'
			));
		}
		
	
        if ($this->form_validation->run() == TRUE) {        	
        	
        	// For pause actions, ensure paid_amount is set to 0.00
        	if ($is_pause) {
        		$_POST['paid_amount'] = '0.00';
        	}
        	
        	$order_id = $this->model_pos_orders->create();
        	
        	if($order_id) {
        		$this->session->set_flashdata('success', 'Successfully created');
        		
        		// Redirect based on action type
        		if ($is_pause) {
        			// redirect('pos_orders/', 'refresh'); // Redirect to index for pause
        			redirect('pos_orders/kitchenPrint/'.$order_id, 'refresh'); // Redirect to print for paid
        		} else {
        			redirect('pos_orders/modernPrint/'.$order_id, 'refresh'); // Redirect to print for paid
        		}
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('pos_orders/create/', 'refresh');
        	}
        }
        else {
            // false case
						$this->data['categories_data'] = $this->model_category->getActiveCategory();
            $this->data['table_data'] = $this->model_tables->getActiveTable();
						// $this->pr( $this->data['categories_data']);
						// exit;
        	$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;

        	$this->data['products'] = $this->model_products->getActiveProductData();
        	$this->data['currency_symbol'] = $this->currency_code;      	
					// $this->pr( $this->data['products']);
					// exit;
            $this->render_template('pos_orders/create', $this->data);
        }	
	}

	/*
	* It gets the product id passed from the ajax method.
	* It checks retrieves the particular product data from the product id 
	* and return the data into the json format.
	*/
	public function getProductValueById()
	{
		$product_id = $this->input->post('product_id');
		if($product_id) {
			$product_data = $this->model_products->getProductData($product_id);
			echo json_encode($product_data);
		}
	}

	/*
	* It gets the all the active product inforamtion from the product table 
	* This function is used in the order page, for the product selection in the table
	* The response is return on the json format.
	*/
	public function getTableProductRow()
	{
		$products = $this->model_products->getActiveProductData();
		echo json_encode($products);
	}

	/*
	* If the validation is not valid, then it redirects to the edit orders page 
	* If the validation is successfully then it updates the data into the database 
	* and it stores the operation message into the session flashdata and display on the manage group page
	*/
	public function update($id)
	{
		if(!in_array('updateOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		if(!$id) {
			redirect('dashboard', 'refresh');
		}

		//$this->pr($this->input->post());
			//exit;
			
		$this->data['page_title'] = 'Update POS Order';

		// Check if this is a pause action
		$is_pause = ($this->input->post('paid_status') == '0');

		// Order validation rules
		$this->form_validation->set_rules('product[]', 'Product', 'trim|required', array(
			'required' => 'Please select at least one product for the order.'
		));
		
		$this->form_validation->set_rules('qty[]', 'Quantity', 'trim|required|numeric|greater_than[0]', array(
			'required' => 'Product quantity is required.',
			'numeric' => 'Quantity must be a valid number.',
			'greater_than' => 'Quantity must be greater than 0.'
		));
		
		$this->form_validation->set_rules('gross_amount_value', 'Gross Amount', 'trim|required|numeric|greater_than[0]', array(
			'required' => 'Gross amount is required.',
			'numeric' => 'Gross amount must be a valid number.',
			'greater_than' => 'Gross amount must be greater than 0.'
		));
		
		$this->form_validation->set_rules('net_amount_value', 'Net Amount', 'trim|required|numeric|greater_than[0]', array(
			'required' => 'Net amount is required.',
			'numeric' => 'Net amount must be a valid number.',
			'greater_than' => 'Net amount must be greater than 0.'
		));
		
		$this->form_validation->set_rules('discount', 'Discount', 'trim|numeric|greater_than_equal_to[0]', array(
			'numeric' => 'Discount must be a valid number.',
			'greater_than_equal_to' => 'Discount cannot be negative.'
		));
		
		// Only validate paid_amount for non-pause actions
		if (!$is_pause) {
			$this->form_validation->set_rules('paid_amount', 'Paid Amount', 'trim|numeric|greater_than_equal_to[0]', array(
				'numeric' => 'Paid amount must be a valid number.',
				'greater_than_equal_to' => 'Paid amount cannot be negative.'
			));
		}
		
		// Optional validation for balance and due amounts (these are calculated fields)
		$this->form_validation->set_rules('bal_amount', 'Balance Amount', 'trim|numeric|greater_than_equal_to[0]', array(
			'numeric' => 'Balance amount must be a valid number.',
			'greater_than_equal_to' => 'Balance amount cannot be negative.'
		));
		
		$this->form_validation->set_rules('due_amount', 'Due Amount', 'trim|numeric|greater_than_equal_to[0]', array(
			'numeric' => 'Due amount must be a valid number.',
			'greater_than_equal_to' => 'Due amount cannot be negative.'
		));
		
		// Table validation - only required for Dine In orders (you may need to adjust this based on your business logic)
		if($this->input->post('table_name') && $this->input->post('table_name') != '0') {
			$this->form_validation->set_rules('table_name', 'Table', 'trim|required|numeric|greater_than[0]', array(
				'required' => 'Please select a table.',
				'numeric' => 'Invalid table selection.',
				'greater_than' => 'Please select a valid table.'
			));
		}
		
	
        if ($this->form_validation->run() == TRUE) {        	

        	// For pause actions, ensure paid_amount is set to 0.00
        	if ($is_pause) {
        		$_POST['paid_amount'] = '0.00';
        	}

        	$update = $this->model_pos_orders->update($id);
        	
        	if($update == true) {
        		$this->session->set_flashdata('success', 'Successfully updated');
        		
        		// Redirect based on action type
        		if ($is_pause) {
        			// redirect('pos_orders/', 'refresh'); // Redirect to index for pause
        			redirect('pos_orders/kitchenPrint/'.$id, 'refresh'); // Redirect to print for paid
        		} else {
        			redirect('pos_orders/modernPrint/'.$id, 'refresh'); // Redirect to print for paid
        		}
        	}
        	else {
        		$this->session->set_flashdata('errors', 'Error occurred!!');
        		redirect('pos_orders/update/'.$id, 'refresh');
        	}
        }
        else {
            // false case
        	$this->data['table_data'] = $this->model_tables->getActiveTable();
			// echo '<pre>';
			// print_r($this->data['table_data']);

        	$company = $this->model_company->getCompanyData(1);
        	$this->data['company_data'] = $company;
        	$this->data['is_vat_enabled'] = ($company['vat_charge_value'] > 0) ? true : false;
        	$this->data['is_service_enabled'] = ($company['service_charge_value'] > 0) ? true : false;

        	$result = array();
        	$orders_data = $this->model_pos_orders->getOrdersData($id);

        	if(empty($orders_data)) {
        		$this->session->set_flashdata('errors', 'The request data does not exists');
        		redirect('pos_orders', 'refresh');
        	}

    		$result['order'] = $orders_data;
    		$orders_item = $this->model_pos_orders->getOrdersItemData($orders_data['id']);

    		foreach($orders_item as $k => $v) {
    			$result['order_item'][] = $v;
    		}

    		$table_id = $result['order']['table_id'];
    		$table_data = $this->model_tables->getTableData($table_id);
			// print_r($table_data);
			// exit;

    		$result['order_table'] = $table_data;

    		$this->data['order_data'] = $result;

        	$this->data['products'] = $this->model_products->getActiveProductData();
        	$this->data['currency_symbol'] = $this->currency_code;      	

            $this->render_template('pos_orders/edit', $this->data);
        }
	}

	/*
	* It removes the data from the database
	* and it returns the response into the json format
	*/
	public function remove()
	{
		if(!in_array('deleteOrder', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$order_id = $this->input->post('order_id');

        $response = array();
        
        // Debug: Log the received order ID
        log_message('debug', 'Received order_id: ' . var_export($order_id, true));
        
        if($order_id !== null && $order_id !== '' && $order_id !== false) {
            // Debug: Check if order exists before deletion
            $order_exists = $this->model_pos_orders->getOrdersData($order_id);
            if(!$order_exists) {
                $response['success'] = false;
                $response['messages'] = "Order not found with ID: " . $order_id;
                echo json_encode($response);
                return;
            }
            
            $delete = $this->model_pos_orders->remove($order_id);
            if($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed"; 
            }
            else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the order information";
            }
        }
        else {
            $response['success'] = false;
            $response['messages'] = "Order ID is required";
        }

        echo json_encode($response); 
	}

	/*
	* It gets the product id and fetch the order data. 
	* The order print logic is done here 
	*/
	public function printDiv($id)
	{
		if(!in_array('viewOrder', $this->permission)) {
          	redirect('dashboard', 'refresh');
  		}
        
		if($id) {
			$order_data = $this->model_pos_orders->getOrdersData($id);
			$orders_items = $this->model_pos_orders->getOrdersItemData($id);
			$company_info = $this->model_company->getCompanyData(1);
			$store_data = $this->model_stores->getStoresData($order_data['store_id']);

			$order_date = date('d/m/Y', $order_data['date_time']);
			$paid_status = ($order_data['paid_status'] == 1) ? "Paid" : "Unpaid";

			$table_data = $this->model_tables->getTableData($order_data['table_id']);

			if ($order_data['discount'] > 0) {
				$discount = $this->currency_code . ' ' .$order_data['discount'];
			}
			else {
				$discount = '0';
			}


			$html = '<!-- Main content -->
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8">
			  <meta http-equiv="X-UA-Compatible" content="IE=edge">
			  <title>Invoice</title>
			  <!-- Tell the browser to be responsive to screen width -->
			  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
			  <!-- Bootstrap 3.3.7 -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
			  <link rel="stylesheet" href="'.base_url('assets/dist/css/AdminLTE.min.css').'">
			</head>
			<body onload="window.print();">
			
			<div class="wrapper">
			  <section class="invoice">
			    <!-- title row -->
			    <div class="row">
			      <div class="col-xs-12">
			        <h2 class="page-header">
			          '.$company_info['company_name'].'
			          <small class="pull-right">Date: '.$order_date.'</small>
			        </h2>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- info row -->
			    <div class="row invoice-info">
			      
			      <div class="col-sm-4 invoice-col">
			        <b>Bill ID: </b> '.$order_data['bill_no'].'<br>
			        <b>Store Name: </b> '.$store_data['name'].'<br>
			        <b>Table name: </b> '.(isset($table_data['table_name']) ? $table_data['table_name'] : 'Take Away').'<br>
			        <b>Total items: </b> '.count($orders_items).'<br><br>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <!-- Table row -->
			    <div class="row">
			      <div class="col-xs-12 table-responsive">
			        <table class="table table-striped">
			          <thead>
			          <tr>
			            <th>Product name</th>
			            <th>Price</th>
			            <th>Qty</th>
			            <th>Amount</th>
			          </tr>
			          </thead>
			          <tbody>'; 

			          foreach ($orders_items as $k => $v) {

			          	$product_data = $this->model_products->getProductData($v['product_id']); 
			          	
			          	$html .= '<tr>
				            <td>'.$product_data['name'].'</td>
				            <td>'.$this->currency_code . ' ' .$v['rate'].'</td>
				            <td>'.$v['qty'].'</td>
				            <td>'.$this->currency_code . ' ' .$v['amount'].'</td>
			          	</tr>';
			          }
			          
			          $html .= '</tbody>
			        </table>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->

			    <div class="row">
			      
			      <div class="col-xs-6 pull pull-right">

			        <div class="table-responsive">
			          <table class="table">
			            <tr>
			              <th style="width:50%">Gross Amount:</th>
			              <td>'.$this->currency_code . ' ' .$order_data['gross_amount'].'</td>
			            </tr>';
			            
			            
			            $html .=' <tr>
			              <th>Discount:</th>
			              <td>'.$discount.'</td>
			            </tr>
			            <tr>
			              <th>Net Amount:</th>
			              <td>'.$this->currency_code . ' ' .$order_data['net_amount'].'</td>
			            </tr>
			            <tr>
			              <th>Paid Amount:</th>
			              <td>'.$this->currency_code . ' ' .($order_data['paid_amount'] ?? '0.00').'</td>
			            </tr>';
			            
			            // Show balance or due amount if applicable
			            if(isset($order_data['bal_amount']) && $order_data['bal_amount'] > 0) {
			            	$html .= '<tr>
				              <th style="color: #28a745;">Balance (Change):</th>
				              <td style="color: #28a745;">'.$this->currency_code . ' ' .$order_data['bal_amount'].'</td>
				            </tr>';
			            } elseif(isset($order_data['due_amount']) && $order_data['due_amount'] > 0) {
			            	$html .= '<tr>
				              <th style="color: #dc3545;">Amount Due:</th>
				              <td style="color: #dc3545;">'.$this->currency_code . ' ' .$order_data['due_amount'].'</td>
				            </tr>';
			            }
			            
			            $html .= '<tr>
			              <th>Paid Status:</th>
			              <td>'.$paid_status.'</td>
			            </tr>
			          </table>
			        </div>
			      </div>
			      <!-- /.col -->
			    </div>
			    <!-- /.row -->
			  </section>
			  <!-- /.content -->
			</div>
		</body>
	</html>';

			  echo $html;
		}
	}

	/*
	* Modern print template function based on sample template
	* Creates a receipt-style print layout with company branding
	*/
	public function kitchenPrint($id)
	{
		if(!in_array('viewOrder', $this->permission)) {
          	redirect('dashboard', 'refresh');
  		}

		if($id) {
			$order_data = $this->model_pos_orders->getOrdersData($id);
			$orders_items = $this->model_pos_orders->getOrdersItemDataForKprint($id);


			$company_info = $this->model_company->getCompanyData(1);
			$store_data = $this->model_stores->getStoresData($order_data['store_id']);
			$user_data = $this->model_users->getUserData($order_data['user_id']);

			$order_date = date('d-m-Y', $order_data['date_time']);
			$order_time = date('h:i a', $order_data['date_time']);
			$paid_status = ($order_data['paid_status'] == 1) ? "Paid" : "Unpaid";

			if($order_data['table_id'] !== 0) {
				$table_data = $this->model_tables->getTableData($order_data['table_id']);
			}

			if ($order_data['discount'] > 0) {
				$discount = $this->currency_code . ' ' .$order_data['discount'];
			}
			else {
				$discount = '0';
			}

			$html = '<!DOCTYPE html>
			<html>
			<head>
			  <title>Print Order</title>
			  <!-- Bootstrap CSS -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
			  <style>
			    body { 
			      font-family: monospace; 
			      margin: 0; 
			      padding: 20px;
			      padding-top: 0px;
						width: 250px;
			      height: auto;
			      display: flex;
			      flex-direction: column;
			      justify-content: center;
			      align-items: center;
			      /* background-color: #f8f9fa; */
			    }
			    #receipt {
			      width: 250px;
						height: auto;
			      /* padding: 15px; */
			      /* box-shadow: 0 0 10px rgba(0,0,0,0.1); */
			      border-radius: 5px;
			    }
			    .currency {
			      margin-right: 5px;
			      float: right;
			    }
			    td, th, p, h4, h5 {
			      font-size: 16px;
			    }
			    .text-center { text-align: center; }
			    .text-end { text-align: end; }
			    .hr-line { 
						border: 1px solid black; 
						margin-top: 10px;
    				margin-bottom: 10px;
					}
			    table { width: 100%; border-collapse: collapse; margin: 0; }
			    .totals-table th, .totals-table td {
			      padding: 1px 2px;
			      margin: 0;
			      line-height: 1.2;
			    }
			    .items-table th, .items-table td { 
			      padding: 0px 1px; 
			      vertical-align: top;
			      white-space: nowrap;
			    }
			    .items-table th {
			      font-weight: bold;
			      padding-bottom: 0px;
			    }
			    .items-table th:nth-child(1), .items-table td:nth-child(1) { 
			      width: 50%; 
			      text-align: left;
			      white-space: normal;
			      word-wrap: break-word;
			    }
			    .items-table th:nth-child(2), .items-table td:nth-child(2) { 
			      width: 10%; 
			      text-align: center; 
			    }
			    .items-table th:nth-child(3), .items-table td:nth-child(3) { 
			      width: 20%; 
			      text-align: right; 
			    }
			    .items-table th:nth-child(4), .items-table td:nth-child(4) { 
			      width: 20%; 
			      text-align: right; 
			    }
			    .company-header { text-align: center; margin-bottom: 10px; }
			    .receipt-info { margin: 0px 0; }
			    .thank-you { text-align: center; margin-top: 0px; }
			    @page {
						size: auto; /* Use content size */
						margin: 15px;  /* Remove default print margins */
						padding: 0;  /* Remove default print margins */
						margin-top: 0px;  /* Remove default print margins */
					}
					@media print {
			      .no-print { display: none; }
			      body {
			        background-color: white !important;
			        padding: 10px !important;
							padding-top: 0 !important;
							margin: 0 !important;
							height: auto !important;
			      }
			      #receipt {
			        box-shadow: none !important;
			        border-radius: 0 !important;
			        padding: 5 !important;
			        padding-top: 0 !important;
			      }
			    }
			  </style>
			</head>
			<body>
			  <div id="receipt">
			    <!-- Company Header -->
			    <div class="company-header">
			      <!-- <h3 style="margin: 5px 0; text-align: center;">'.$company_info['company_name'].'</h3>
						<p style="margin: 2px 0;">'.$company_info['address'].'</p>
			      <p style="margin: 2px 0;">TEL: '.$company_info['phone'].'</p>  
						--!>
			     <!--  <h4 style="margin: 10px 0;">KITCHEN RECEIPT</h4>
						<h5 style="margin: 5px 0;">Store: '.$store_data['name'].'</h5>
						
			      <p style="margin: 2px 0;">Printed On: '.date('d-m-Y').'</p>
			      <hr class="hr-line"/> -->
			    </div>

			    <!-- Receipt Info -->
			    <table class="receipt-info">
			      <tr>
			        <td>OrderID:</td>
			        <td class="text-end"><b>#'.$order_data['bill_no'].'</b></td>
			      </tr>';
			      
			      if($order_data['table_id'] !== 0) {
			      	$html .= '<tr>
			        <td>Table Number:</td>
			        <td class="text-end"><b>'.(isset($table_data['table_name']) ? $table_data['table_name'] : 'N/A').'</b></td>
			      </tr>';
			      }
			      
			      $html .= '
			      <tr>
			        <td>Date:</td>
			        <td class="text-end"><b>'.$order_date.'</b></td>
			      </tr>
			      <tr>
			        <td colspan="2"><hr class="hr-line"/></td>
			      </tr>
			    </table>

			    <!-- Items Table -->
			    <table class="items-table">
			      <tr>
			        <th>Product</th>
			        <th>Qty</th>
			      </tr>
			      <tr>
			        <td colspan="4"><hr class="hr-line"/></td>
			      </tr>';

			    // Add order items
			    foreach($orders_items as $item) {
			    	$product_data = $this->model_products->getProductData($item['product_id']);
			    	$display_qty = isset($item['display_qty']) ? $item['display_qty'] : $item['qty'];
			    	$html .= '<tr>
			    	  <td>'.$product_data['name'].'</td>
			    	  <td>'.$display_qty.'</td>
			    	</tr>';
			    }

			    $html .= '<tr>
			        <td colspan="4"><hr class="hr-line"/></td>
			      </tr>
			    </table>
			      
			    <!-- Totals Section -->
			    <table class="totals-table">
			      ';

			    if($order_data['discount'] > 0) {
			    	$html .= '<tr>
			    	  <td colspan="2"><b>Discount</b></td>
			    	  <td colspan="2" class="text-end"><b>(-) '.$discount.'</b></td>
			    	</tr>';
			    }

			    $html .= '
			    </table>

			    <!-- Footer -->
			    <!-- <div class="thank-you">
			      <p><b>Thank you for shopping with us!</b></p>
			    </div>
					 <tr>
			        <td colspan="4"><hr class="hr-line"/></td>
			      </tr> -->
			  </div>

			  <!-- Print Buttons -->
			  <div class="no-print" style="text-align: center; margin-top: 20px;">
			    <button onclick="printReceipt()" class="btn btn-success"><i class="fa fa-print"></i> Print</button>
			    <button onclick="goToIndex()" class="btn btn-danger"><i class="fa fa-times"></i> Close</button>
			  </div>

			  <script src="'.base_url('assets/bower_components/jquery/dist/jquery.min.js').'"></script>
			  <script>
			    function printReceipt() {
			      // Mark kitchen print as viewed when print button is clicked
			      $.ajax({
			        url: "'.base_url('pos_orders/markKprintViewed').'",
			        method: "POST",
			        data: {order_id: '.$id.'},
			        dataType: "json",
			        success: function(response) {
			          console.log("Kprint status updated");
			        },
			        error: function() {
			          console.log("Error updating kprint status");
			        }
			      });

			      // Proceed with printing
			      window.print();
			    }

			    function goToIndex() {
			      window.location.href = "'.base_url('pos_orders/').'";
			    }

			    // Auto focus for better UX
			    window.onload = function() {
			      window.focus();
			    }
			  </script>
			</body>
			</html>';

			echo $html;
		}
	}

	/*
	* AJAX method to mark order items as kprint viewed
	*/
	public function markKprintViewed()
	{
		$order_id = $this->input->post('order_id');

		if($order_id) {
			$result = $this->model_pos_orders->markOrderItemsAsKprintViewed($order_id);

			if($result) {
				echo json_encode(array('success' => true));
			} else {
				echo json_encode(array('success' => false));
			}
		} else {
			echo json_encode(array('success' => false, 'message' => 'Order ID required'));
		}
	}

	public function modernPrint($id)
	{
		if(!in_array('viewOrder', $this->permission)) {
          	redirect('dashboard', 'refresh');
  		}
        
		if($id) {
			$order_data = $this->model_pos_orders->getOrdersData($id);
			$orders_items = $this->model_pos_orders->getOrdersItemData($id);
			$company_info = $this->model_company->getCompanyData(1);
			$store_data = $this->model_stores->getStoresData($order_data['store_id']);
			$user_data = $this->model_users->getUserData($order_data['user_id']);

			$order_date = date('d-m-Y', $order_data['date_time']);
			$order_time = date('h:i a', $order_data['date_time']);
			$paid_status = ($order_data['paid_status'] == 1) ? "Paid" : "Unpaid";

			$table_data = $this->model_tables->getTableData($order_data['table_id']);
			$table_info = '';
			if (isset($table_data['table_name'] ) && $table_data['table_name'] != '') {
				$table_info ='<tr>
			        <td>Order Type: </td>
			        <td class="text-end"><b>Dine in</b></td>
			      </tr><tr>
			        <td>Table Name:</td>
			        <td class="text-end"><b>'.$table_data['table_name'] .'</b></td>
			      </tr';
			} else {
				$table_info ='<tr>
			        <td>Order Type: </td>
			        <td class="text-end"><b>Take Away</b></td>
			      </tr';
			}
		
			if ($order_data['discount'] > 0) {
				$discount = $this->currency_code . ' ' .$order_data['discount'];
			}
			else {
				$discount = '0';
			}

			$html = '<!DOCTYPE html>
			<html>
			<head>
			  <title>Print Order</title>
			  <!-- Bootstrap CSS -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css').'">
			  <!-- Font Awesome -->
			  <link rel="stylesheet" href="'.base_url('assets/bower_components/font-awesome/css/font-awesome.min.css').'">
			  <style>
			    body { 
			      font-family: monospace; 
			      margin: 0; 
			      padding: 20px;
			      padding-top: 0px;
						width: 250px;
			      height: auto;
			      display: flex;
			      flex-direction: column;
			      justify-content: center;
			      align-items: center;
			      /* background-color: #f8f9fa; */
			    }
			    #receipt {
			      width: 250px;
						height: auto;
			      background-color: white;
			      /* padding: 15px; */
			      /* box-shadow: 0 0 10px rgba(0,0,0,0.1); */
			      border-radius: 5px;
			    }
			    .currency {
			      margin-right: 5px;
			      float: right;
			    }
			    td, th, p, h4, h5 {
			      font-size: 14px;
			    }
			    .text-center { text-align: center; }
			    .text-end { text-align: end; }
			    .hr-line { 
						border: 1px solid black; 
						margin-top: 10px;
    				margin-bottom: 10px;
					}
			    table { width: 100%; border-collapse: collapse; margin: 0; }
			    .totals-table th, .totals-table td {
			      padding: 1px 2px;
			      margin: 0;
			      line-height: 1.2;
			    }
			    .items-table th, .items-table td { 
			      padding: 0px 1px; 
			      vertical-align: top;
			      white-space: nowrap;
			    }
			    .items-table th {
			      font-weight: bold;
			      padding-bottom: 0px;
			    }
			    .items-table th:nth-child(1), .items-table td:nth-child(1) { 
			      width: 50%; 
			      text-align: left;
			      white-space: normal;
			      word-wrap: break-word;
			    }
			    .items-table th:nth-child(2), .items-table td:nth-child(2) { 
			      width: 10%; 
			      text-align: center; 
			    }
			    .items-table th:nth-child(3), .items-table td:nth-child(3) { 
			      width: 20%; 
			      text-align: right; 
			    }
			    .items-table th:nth-child(4), .items-table td:nth-child(4) { 
			      width: 20%; 
			      text-align: right; 
			    }
			    .company-header { text-align: center; }
			    .receipt-info { margin: 0; }
			    .thank-you { text-align: center; margin-top: 0px; }
			    @page {
						size: auto; /* Use content size */
						margin: 15px;  /* Remove default print margins */
						padding: 0;  /* Remove default print margins */
						margin-top: 0px;  /* Remove default print margins */
					}
					@media print {
			      .no-print { display: none; }
			      body {
			        background-color: white !important;
			        padding: 10px !important;
			        padding-top: 0 !important;
							margin: 0 !important;
							height: auto !important;
			      }
			      #receipt {
			        box-shadow: none !important;
			        border-radius: 0 !important;
			        padding: 5 !important;
			        padding-top: 0 !important;
			      }
			    }
			  </style>
			</head>
			<body>
			  <div id="receipt">
			    <!-- Company Header -->
			    <div class="company-header">
			      <h3 style="margin: 5px 0; text-align: center;">'.$company_info['company_name'].'</h3>
			      <p style="margin: 2px 0;">'.$company_info['address'].'</p>
			      <p style="margin: 2px 0;">TEL: '.$company_info['phone'].'</p>
			      <!-- <h4 style="margin: 10px 0;">RECEIPT</h4>
			      <h5 style="margin: 5px 0;">Store: '.$store_data['name'].'</h5>
				  <p style="margin: 2px 0;">OrderID: '.$order_data['bill_no'].'</p>
			      <p style="margin: 2px 0;">DATE: '.date('d-m-Y').'</p> -->
			      <hr class="hr-line"/>
			    </div>

			    <!-- Receipt Info -->
			    <table class="receipt-info">
			      <!-- <tr>
			        <td>Operator:</td>
			        <td class="text-end"><b>'.$user_data['firstname'].' '.$user_data['lastname'].'</b></td>
			      </tr> --!>
			      <tr>
			        <td>OrderID: </td>
			        <td class="text-end"><b>#'.$order_data['bill_no'].'</b></td>
			      </tr>
			     <!-- <tr>
			        <td>Type:</td>
			        <td class="text-end"><b>'.(isset($table_data['table_name']) ? $table_data['table_name'] : 'Take Away').'</b></td>
			      </tr> --!>
				  '.$table_info.'
			      <tr>
			        <td>Date:</td>
			        <td class="text-end"><b>'.$order_date.'</b></td>
			      </tr>
			      <tr>
			        <td colspan="2"><hr class="hr-line"/></td>
			      </tr>
			    </table>

			    <!-- Items Table -->
			    <table class="items-table">
			      <tr>
			        <th>Product</th>
			        <th>Qty</th>
			        <th>Price</th>
			        <th>Amt</th>
			      </tr>
			      <tr>
			        <td colspan="4"><hr class="hr-line"/></td>
			      </tr>';

			    // Add order items
			    foreach($orders_items as $item) {
			    	$product_data = $this->model_products->getProductData($item['product_id']);
			    	$html .= '<tr>
			    	  <td>'.$product_data['name'].'</td>
			    	  <td>'.$item['qty'].'</td>
			    	  <td>'.$this->currency_code.number_format($item['rate'], 2).'</td>
			    	  <td><b>'.$this->currency_code.number_format($item['amount'], 2).'</b></td>
			    	</tr>';
			    }

			    $html .= '<tr>
			        <td colspan="4"><hr class="hr-line"/></td>
			      </tr>
			    </table>
			      
			    <!-- Totals Section -->
			    <table class="totals-table">
			      ';

			    if($order_data['discount'] > 0) {
			    	$html .= '<tr>
			    	  <td colspan="2"><b>Discount</b></td>
			    	  <td colspan="2" class="text-end"><b>(-) '.$discount.'</b></td>
			    	</tr>';
			    }

			    $html .= '
			      <tr>
			        <td colspan="2"><b>Total Amt</b></td>
			        <td colspan="2" class="text-end"><b>'.$this->currency_code.number_format($order_data['net_amount'], 2).'</b></td>
			      </tr>
						<tr>
			        <td colspan="2"><b>Payment Type</b></td>
			        <td colspan="2" style="text-transform:capitalize;" class="text-end"><b>'. $order_data['payment_type'].'</b></td>
			      </tr>';
					if($order_data['payment_type'] == 'cash') {
						$html .= '<tr>
			        <td colspan="2"><b>Paid Amt</b></td>
			        <td colspan="2" class="text-end"><b>'.$this->currency_code.number_format($order_data['paid_amount'] ?? 0, 2).'</b></td>
			      </tr>';
					}

			    // Show balance or due amount
			    if(isset($order_data['bal_amount']) && $order_data['bal_amount'] > 0) {
			    	$html .= '<tr>
			    	  <td colspan="2"><b>Balance</b></td>
			    	  <td colspan="2" class="text-end"><b>'.$this->currency_code.number_format($order_data['bal_amount'], 2).'</b></td>
			    	</tr>';
			    } elseif(isset($order_data['due_amount']) && $order_data['due_amount'] > 0) {
			    	$html .= '<tr>
			    	  <td colspan="2"><b>Amount Due</b></td>
			    	  <td colspan="2" class="text-end"><b>'.$this->currency_code.number_format($order_data['due_amount'], 2).'</b></td>
			    	</tr>';
			    }

			    $html .= '
			      <tr>
			        <td colspan="4"><hr class="hr-line"/></td>
			      </tr>
			    </table>

			    <!-- Footer -->
			    <div class="thank-you">
			      <p><b>Thank you</b></p>
			    </div>
					 <tr>
			        <td colspan="4"><hr class="hr-line"/></td>
			      </tr>
			  </div>

			  <!-- Print Buttons -->
			  <div class="no-print" style="text-align: center; margin-top: 20px;">
			    <button onclick="printReceipt()" class="btn btn-success"><i class="fa fa-print"></i> Print</button>
			    <button onclick="goToIndex()" class="btn btn-danger"><i class="fa fa-times"></i> Close</button>
			  </div>

			  <script>
			    function printReceipt() {
			      window.print();
			    }
			    
			    function goToIndex() {
			      window.location.href = "'.base_url('pos_orders/').'";
			    }
			    
			    // Auto focus for better UX
			    window.onload = function() {
			      window.focus();
			    }
			  </script>
			</body>
			</html>';

			echo $html;
		}
	}

}