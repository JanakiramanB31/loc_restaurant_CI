<?php  

defined('BASEPATH') OR exit('No direct script access allowed');

class Z_report extends Admin_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'Z-Report';
		$this->load->model('model_pos_orders');
		$this->load->model('model_company');
		$this->load->model('model_stores');
		$this->load->model('model_tables');
		$this->load->model('model_products');
		$this->load->model('model_users');
	}

	public function index()
	{
		if(!in_array('viewReport', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
		
		$today = date('Y-m-d');
		
		$this->data['cashSalesCount'] = 0;
		$this->data['cardInSalesCount'] = 0;
		$this->data['dineInSalesCount'] = 0;
		$this->data['takeawaySalesCount'] = 0;
		// $this->data['paidSalesCount'] = 0;
		// $this->data['unpaidSalesCount'] = 0;
		$this->data['returnsCount'] = 0;
		
		$this->data['cashSalesAmount'] = 0;
		$this->data['cardSalesAmount'] = 0;
		$this->data['dineInSalesAmount'] = 0;
		$this->data['takeawaySalesAmount'] = 0;
		// $this->data['paidSalesAmount'] = 0;
		// $this->data['unpaidSalesAmount'] = 0;
		$this->data['returnsAmount'] = 0;
		$this->data['expenseAmount'] = 0;
		$this->data['totalAmount'] = 0;
		$this->data['totalNetAmount'] = 0;
		
		$this->data['currency'] = htmlspecialchars_decode($this->company_currency(), ENT_QUOTES);
		$this->data['decimalLength'] = 2;
		
		$z_report_data = $this->getZReportData($today, $today);
		if($z_report_data) {
			$this->data = array_merge($this->data, $z_report_data);
		}
		
		$invoice_data = $this->getInvoiceData($today, $today);
		$this->data['invoices'] = $invoice_data;

		$this->render_template('z_report/index', $this->data);
	}

	public function fetchInvoices()
	{
		if(!in_array('viewReport', $this->permission)) {
            echo json_encode(array('success' => false, 'message' => 'Permission denied'));
            return;
        }

        $response = array();
        
        if($this->input->post('reportData')) {
        	$report_data = $this->input->post('reportData');
        	$from_date = $report_data['fromDate'] ?? date('Y-m-d');
        	$to_date = $report_data['toDate'] ?? date('Y-m-d');
        	$company_name = $report_data['companyName'] ?? '';
        	
        	$invoice_data = $this->getInvoiceData($from_date, $to_date, $company_name);
        	
        	if($invoice_data !== false) {
        		$response['invoices'] = $invoice_data;
        		$response['currency'] = htmlspecialchars_decode($this->company_currency(), ENT_QUOTES);
        		$response['decimalLength'] = 2;
        		$response['success'] = true;
        	} else {
        		$response['success'] = false;
        		$response['message'] = 'No data found';
        	}
        } else {
        	$response['success'] = false;
        	$response['message'] = 'Invalid request data';
        }
        
        echo json_encode($response);
	}

	public function print()
	{
		if(!in_array('viewReport', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        $from_date = date('Y-m-d');
        $to_date = date('Y-m-d');
        
        if($this->input->post('reportData')) {
        	$report_data = $this->input->post('reportData');
        	$from_date = $report_data['fromDate'] ?? date('Y-m-d');
        	$to_date = $report_data['toDate'] ?? date('Y-m-d');
        } else if($this->input->post('date')) {
        	$date_data = $this->input->post('date');
        	$from_date = $date_data['fromDate'];
        	$to_date = $date_data['toDate'];
        }
        
        $z_report_data = $this->getZReportData($from_date, $to_date);
        $this->data['currency'] = htmlspecialchars_decode($this->company_currency(), ENT_QUOTES);
        $this->data['decimalLength'] = 2;
        $this->data['from_date'] = $from_date;
        $this->data['to_date'] = $to_date;
        // Set timezone to IST 
        date_default_timezone_set('Asia/Kolkata');
        $this->data['taken_time'] = date('d-m-Y h:i a');
        
        if($z_report_data) {
        	$this->data = array_merge($this->data, $z_report_data);
        }
        
        $invoice_data = $this->getInvoiceData($from_date, $to_date);
        $this->data['invoices'] = $invoice_data;
        
        $this->load->view('z_report/print', $this->data);
	}

	private function getZReportData($from_date, $to_date)
	{
		$data = array();
		
		// Convert dates to timestamp range
		$start_timestamp = strtotime($from_date . ' 00:00:00');
		$end_timestamp = strtotime($to_date . ' 23:59:59');
		
		// Get all orders within date range from pos_orders table
		$this->db->where('date_time >=', $start_timestamp);
		$this->db->where('date_time <=', $end_timestamp);
		$orders = $this->db->get('pos_orders')->result_array();
		
		// Initialize counters for the new categorization
		$cash_sales_count = 0; $cash_sales_amount = 0;
		$card_sales_count = 0; $card_sales_amount = 0;
		$dine_in_count = 0; $dine_in_amount = 0;
		$takeaway_count = 0; $takeaway_amount = 0;
		// $paid_count = 0; $paid_amount = 0;
		// $unpaid_count = 0; $unpaid_amount = 0;
		$total_net_amount = 0;
		
		foreach($orders as $order) {
			$order_amount = floatval($order['net_amount']);
			// $paid_status = $order['paid_status'];
			$table_id = $order['table_id'];
			$payment_type = $order['payment_type'];

			$total_net_amount += $order_amount;

			if($payment_type == 'card') {
				$card_sales_count++;
				$card_sales_amount += $order_amount;
			} else {
				$cash_sales_count++;
				$cash_sales_amount += $order_amount;
			}

			if($table_id) {
				$dine_in_count++;
				$dine_in_amount += $order_amount;
			} else {
				$takeaway_count++;
				$takeaway_amount += $order_amount;
			}
			
			// Categorize by payment status
			// if($paid_status == 1) { 
			// 	$paid_count++;
			// 	$paid_amount += $order_amount;
			// } else if($paid_status == 0) { 
			// 	$unpaid_count++;
			// 	$unpaid_amount += $order_amount;
			// }
		}

		$total_sales_count = $dine_in_count + $takeaway_count;
		$total_sales_amount = $dine_in_amount + $takeaway_amount;
		
		$data['cashSalesCount'] = $cash_sales_count;   
		$data['cardSalesCount'] = $card_sales_count;
		$data['dineInSalesCount'] = $dine_in_count;    
		$data['takeawaySalesCount'] = $takeaway_count;   
		$data['totalSalesCount'] = $total_sales_count; 
		// $data['paidSalesCount'] = $paid_count;
		// $data['unpaidSalesCount'] = $unpaid_count;
		
		$data['cashSalesAmount'] = $cash_sales_amount;     
		$data['cardSalesAmount'] = $card_sales_amount;
		$data['dineInSalesAmount'] = $dine_in_amount;  
		$data['takeawaySalesAmount'] = $takeaway_amount; 
		$data['totalAmount'] = $total_sales_amount;  
		// $data['paidSalesAmount'] = $paid_amount; 
		// $data['unpaidSalesAmount'] = $unpaid_amount;
		$data['totalNetAmount'] = $total_net_amount;  
		
		$data['currency'] = htmlspecialchars_decode($this->company_currency(), ENT_QUOTES);
		$data['decimalLength'] = 2;
		
		return $data;
	}

	private function getInvoiceData($from_date, $to_date)
	{
		// Convert dates to timestamp range
		$start_timestamp = strtotime($from_date . ' 00:00:00');
		$end_timestamp = strtotime($to_date . ' 23:59:59');
		
		// Build query for pos_orders with joins
		$this->db->select('
			pos_orders.id,
			pos_orders.bill_no,
			pos_orders.date_time,
			pos_orders.net_amount,
			pos_orders.paid_amount,
			pos_orders.due_amount,
			pos_orders.bal_amount,
			pos_orders.payment_type,
			pos_orders.paid_status,
			pos_orders.table_id,
			pos_orders.store_id,
			pos_orders.user_id,
			tables.table_name as table_name
		');
		
		$this->db->from('pos_orders');
		$this->db->join('tables', 'pos_orders.table_id = tables.id', 'left');
		$this->db->where('pos_orders.date_time >=', $start_timestamp);
		$this->db->where('pos_orders.date_time <=', $end_timestamp);
		
		$this->db->order_by('pos_orders.date_time', 'DESC');
		$query = $this->db->get();
		
		if($query === false) {
			return false;
		}
		
		$results = $query->result_array();
		
		$processed_results = array();
		foreach($results as $row) {
			$product_count = $this->model_pos_orders->countOrderItem($row['id']);
			
			$processed_row = array(
				'id' => $row['id'],
				'bill_no' => $row['bill_no'],
				'created_at' => date('Y-m-d H:i:s', $row['date_time']),
				'product_count' => intval($product_count),
				'payment_type' => $row['payment_type'], 
				'paid_status' => intval($row['paid_status']), 
				'total_amount' => floatval($row['net_amount'] ?: 0),
				'received_amt' => floatval($row['paid_amount'] ?: 0),
				'table_info' => $row['table_name'] ?: 'Take Away'
			);
			$processed_results[] = $processed_row;
		}
		
		return $processed_results;
	}

	public function clearPaidOrders()
	{
		if(!in_array('viewReport', $this->permission)) {
            echo json_encode(array('success' => false, 'message' => 'Permission denied'));
            return;
        }

        $response = array();
        
        $from_date = $this->input->post('from_date') ?? date('Y-m-d');
        $to_date = $this->input->post('to_date') ?? date('Y-m-d');
        
        try {
            // Convert dates to timestamp range
            $start_timestamp = strtotime($from_date . ' 00:00:00');
            $end_timestamp = strtotime($to_date . ' 23:59:59');
            
            $this->db->select('id');
            $this->db->where('paid_status', 1);
            $this->db->where('date_time >=', $start_timestamp);
            $this->db->where('date_time <=', $end_timestamp);
            $order_ids_query = $this->db->get('pos_orders');
            $order_ids = array_column($order_ids_query->result_array(), 'id');
            
            if(!empty($order_ids)) {
                // Delete related order items first
                $this->db->where_in('order_id', $order_ids);
                $this->db->delete('order_items');
                
                // Delete the paid orders within date range
                $this->db->where('paid_status', 1);
                $this->db->where('date_time >=', $start_timestamp);
                $this->db->where('date_time <=', $end_timestamp);
                $delete_orders = $this->db->delete('pos_orders');
                
                if($delete_orders) {
                    $count = count($order_ids);
                    $date_range = ($from_date == $to_date) ? 
                        date('d-m-Y', strtotime($from_date)) : 
                        date('d-m-Y', strtotime($from_date)) . ' to ' . date('d-m-Y', strtotime($to_date));
                    
                    $response['success'] = true;
                    $response['message'] = "Successfully cleared {$count} paid orders from {$date_range}";
                    $response['cleared_count'] = $count;
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Failed to clear orders from database';
                }
            } else {
                $date_range = ($from_date == $to_date) ? 
                    date('d-m-Y', strtotime($from_date)) : 
                    date('d-m-Y', strtotime($from_date)) . ' to ' . date('d-m-Y', strtotime($to_date));
                
                $response['success'] = true;
                $response['message'] = "No paid orders found for {$date_range}";
            }
        } catch (Exception $e) {
            $response['success'] = false;
            $response['message'] = 'Error occurred while clearing orders: ' . $e->getMessage();
        }
        
        echo json_encode($response);
	}
}