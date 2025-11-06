<?php  

defined('BASEPATH') OR exit('No direct script access allowed');

class X_report extends Admin_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'X-Report';
		$this->load->model('model_reports');
		$this->load->model('model_pos_orders');
	}

	public function index()
	{
		// $this->pr($this->permission);
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
		
		$x_report_data = $this->getXReportData($today, $today);
		if($x_report_data) {
			$this->data = array_merge($this->data, $x_report_data);
		}

		$this->render_template('x_report/index', $this->data);
	}

	public function fetchByDate()
	{
		if(!in_array('viewReport', $this->permission)) {
            echo json_encode(array('success' => false, 'message' => 'Permission denied'));
            return;
        }

        $response = array();
        
        if($this->input->post('date')) {
        	$date_data = $this->input->post('date');
        	$from_date = $date_data['fromDate'];
        	$to_date = $date_data['toDate'];
        	
        	$x_report_data = $this->getXReportData($from_date, $to_date);
        	
        	if($x_report_data) {
        		$response = $x_report_data;
        		$response['success'] = true;
        	} else {
        		$response['success'] = false;
        		$response['message'] = 'No data found';
        	}
        } else {
        	$response['success'] = false;
        	$response['message'] = 'Invalid date range';
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
        
        if($this->input->post('date')) {
        	$date_data = $this->input->post('date');
        	$from_date = $date_data['fromDate'];
        	$to_date = $date_data['toDate'];
        }
        
        $x_report_data = $this->getXReportData($from_date, $to_date);
        $this->data['currency'] = htmlspecialchars_decode($this->company_currency(), ENT_QUOTES);
        $this->data['decimalLength'] = 2;
        $this->data['from_date'] = $from_date;
        $this->data['to_date'] = $to_date;
        date_default_timezone_set('Asia/Kolkata');
        $this->data['taken_time'] = date('d-m-Y h:i a');
        
        if($x_report_data) {
        	$this->data = array_merge($this->data, $x_report_data);
        }
        
        $this->load->view('x_report/print', $this->data);
	}

	private function getXReportData($from_date, $to_date)
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
		
		$data['currency'] = html_entity_decode($this->company_currency(), ENT_QUOTES, 'UTF-8');
		$data['decimalLength'] = 2;
		
		return $data;
	}
}	