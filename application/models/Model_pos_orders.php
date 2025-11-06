<?php 

class Model_pos_orders extends CI_Model
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_tables');
		$this->load->model('model_users');
	}

	/* get the orders data */
	public function getOrdersData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM pos_orders WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$user_id = $this->session->userdata('id');
		if($user_id == 1) {
			$sql = "SELECT * FROM pos_orders ORDER BY id DESC";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
		else {
			$user_data = $this->model_users->getUserData($user_id);
			$sql = "SELECT * FROM pos_orders WHERE store_id = ? ORDER BY id DESC";
			$query = $this->db->query($sql, array($user_data['store_id']));
			return $query->result_array();	
		}
	}

	// get the orders item data
	public function getOrdersItemData($order_id = null)
	{
		if(!$order_id) {
			return false;
		}

		$sql = "SELECT * FROM order_items WHERE order_id = ?";
		$query = $this->db->query($sql, array($order_id));
		return $query->result_array();
	}

	// get the orders item data for kprint (only unviewed items for paused orders, all items for paid orders)
	public function getOrdersItemDataForKprint($order_id = null)
	{
		if(!$order_id) {
			return false;
		}

		// Check if this is a paused order
		$order_data = $this->getOrdersData($order_id);
		if($order_data && $order_data['paid_status'] == 0) {
			// For paused orders, only show items that haven't been kprint viewed
			$sql = "SELECT * FROM order_items WHERE order_id = ? AND is_kprint_viewed = 0";
			$query = $this->db->query($sql, array($order_id));
		} else {
			// For paid orders, show all items
			$sql = "SELECT * FROM order_items WHERE order_id = ?";
			$query = $this->db->query($sql, array($order_id));
		}

		$items = $query->result_array();

		// Calculate display quantity for items (extra quantity for updated items)
		foreach($items as &$item) {
			$item['display_qty'] = $this->calculateKprintDisplayQty($order_id, $item);
		}

		return $items;
	}

	/*
	* Calculate the quantity to display in kitchen print
	* For new items: show full quantity
	* For updated items: show only additional quantity
	*/
	private function calculateKprintDisplayQty($order_id, $item)
	{
		// Check if we have quantity difference data stored in session
		$qty_differences = $this->session->userdata('kprint_qty_differences_' . $order_id);

		if($qty_differences && isset($qty_differences[$item['product_id'] . '_' . $item['rate']])) {
			$diff_qty = $qty_differences[$item['product_id'] . '_' . $item['rate']];
			return max(0, $diff_qty); // Return only the additional quantity
		}

		return $item['qty']; // Return full quantity for new items
	}

	public function create()
	{
		$user_id = $this->session->userdata('id');
		// get store id from user id 
		$user_data = $this->model_users->getUserData($user_id);
		$store_id = $user_data['store_id'];

		$bill_no = 'Royal-'.strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
    	$data = array(
    		'bill_no' => $bill_no,
    		'date_time' => strtotime(date('Y-m-d h:i:s a')),
    		'gross_amount' => $this->input->post('gross_amount_value'),
    		'table_id' => $this->input->post('table_id'),
    		'bal_amount' => $this->input->post('bal_amount'),
    		'due_amount' => $this->input->post('due_amount'),
    		'paid_amount' => $this->input->post('paid_amount'),
    		'net_amount' => $this->input->post('net_amount_value'),
    		'discount' => $this->input->post('discount'),
    		'payment_type' => $this->input->post('payment_type'),
    		'paid_status' => $this->input->post('paid_status'),
    		'user_id' => $user_id,
    		'table_id' => $this->input->post('table_name'),
    		'store_id' => $store_id,
    	);

		$insert = $this->db->insert('pos_orders', $data);
		$order_id = $this->db->insert_id();

		$count_product = count($this->input->post('product'));
		$is_paused = ($this->input->post('paid_status') == '0');

    	for($x = 0; $x < $count_product; $x++) {
    		$items = array(
    			'order_id' => $order_id,
    			'product_id' => $this->input->post('product')[$x],
    			'qty' => $this->input->post('qty')[$x],
    			'rate' => $this->input->post('rate_value')[$x],
    			'amount' => $this->input->post('amount_value')[$x],
    			'is_kprint_viewed' => 0,
    		);

    		$this->db->insert('order_items', $items);
    	}

    	// update the table status
    	$this->load->model('model_tables');
    	$this->model_tables->update($this->input->post('table_name'), array('available' => 2));

		return ($order_id) ? $order_id : false;
	}

	public function countOrderItem($order_id)
	{
		if($order_id) {
			$sql = "SELECT * FROM order_items WHERE order_id = ?";
			$query = $this->db->query($sql, array($order_id));
			return $query->num_rows();
		}
	}

	public function update($id)
	{
		if($id) {
			$user_id = $this->session->userdata('id');
			$user_data = $this->model_users->getUserData($user_id);
			$store_id = $user_data['store_id'];
			// update the table info

			

			$order_data = $this->getOrdersData($id);
			$data = $this->model_tables->update($order_data['table_id'], array('available' => 1));

			if($this->input->post('paid_status') == 1) {
	    		$this->model_tables->update($this->input->post('table_name'), array('available' => 1));	
	    	}
	    	else {
	    		$this->model_tables->update($this->input->post('table_name'), array('available' => 2));	
	    	}

			$data = array(
					'table_id' => $this->input->post('table_id'),
	    		'gross_amount' => $this->input->post('gross_amount_value'),
	    		'bal_amount' => $this->input->post('bal_amount'),
	    		'due_amount' => $this->input->post('due_amount'),
	    		'paid_amount' => $this->input->post('paid_amount'),
	    		'net_amount' => $this->input->post('net_amount_value'),
	    		'discount' => $this->input->post('discount'),
	    		'paid_status' => $this->input->post('paid_status'),
	    		'user_id' => $user_id,
	    		'table_id' => $this->input->post('table_name'),
	    		'store_id' => $store_id
	    	);

			// Get existing order items to preserve kprint_viewed status BEFORE deleting
			$existing_items = $this->getOrdersItemData($id);
			$existing_kprint_status = array();

			foreach($existing_items as $item) {
				$key = $item['product_id'] . '_' . $item['rate'];
				$existing_kprint_status[$key] = array(
					'is_kprint_viewed' => $item['is_kprint_viewed'],
					'qty' => $item['qty']
				);
			}

			$this->db->where('id', $id);
			$update = $this->db->update('pos_orders', $data);

			// now remove the order item data
			$this->db->where('order_id', $id);
			$this->db->delete('order_items');

			$count_product = count($this->input->post('product'));
			$is_paused = ($this->input->post('paid_status') == '0');
			$qty_differences = array(); // Store quantity differences for kprint

	    	for($x = 0; $x < $count_product; $x++) {
	    		$product_id = $this->input->post('product')[$x];
	    		$rate = $this->input->post('rate_value')[$x];
	    		$new_qty = $this->input->post('qty')[$x];
	    		$key = $product_id . '_' . $rate;

	    		// Check if this item existed before and preserve its kprint status
	    		$kprint_viewed = 0; // Default for new items
	    		if(isset($existing_kprint_status[$key])) {
	    			$old_qty = $existing_kprint_status[$key]['qty'];
	    			$old_kprint_status = $existing_kprint_status[$key]['is_kprint_viewed'];

	    			// If quantity increased, show only the additional quantity
	    			if($new_qty > $old_qty) {
	    				$kprint_viewed = 0; // Show item
	    				$qty_differences[$key] = $new_qty - $old_qty; // Store difference
	    			} else if($new_qty == $old_qty) {
	    				$kprint_viewed = $old_kprint_status; // Keep original status
	    			} else {
	    				// Quantity decreased - don't show in kprint
	    				$kprint_viewed = 1;
	    			}
	    		} else {
	    			// New item - show full quantity
	    			$qty_differences[$key] = $new_qty;
	    		}

	    		$items = array(
	    			'order_id' => $id,
	    			'product_id' => $product_id,
	    			'qty' => $new_qty,
	    			'rate' => $rate,
	    			'amount' => $this->input->post('amount_value')[$x],
	    			'is_kprint_viewed' => $kprint_viewed,
	    		);
	    		$this->db->insert('order_items', $items);
	    	}

	    	// Store quantity differences in session for kprint display
	    	$this->session->set_userdata('kprint_qty_differences_' . $id, $qty_differences);

	    	
	    	

			return true;
		}
	}



	public function remove($id)
	{
		if($id) {
			// Start transaction
			$this->db->trans_start();
			
			// First delete order items (child records)
			$this->db->where('order_id', $id);
			$delete_item = $this->db->delete('order_items');
			
			// Then delete the main order
			$this->db->where('id', $id);
			$delete = $this->db->delete('pos_orders');
			
			// Complete transaction
			$this->db->trans_complete();
			
			// Check if transaction was successful
			if ($this->db->trans_status() === FALSE) {
				return false;
			}
			
			return ($delete == true && $delete_item) ? true : false;
		}
		return false;
	}

	public function countTotalPaidOrders()
	{
		$sql = "SELECT * FROM pos_orders WHERE paid_status = ?";
		$query = $this->db->query($sql, array(1));
		return $query->num_rows();
	}

	public function countTotalOrders()
	{
		$sql = "SELECT * FROM pos_orders";
		$query = $this->db->query($sql, array(1));
		return $query->num_rows();
	}

	public function countTotalUnPaidOrders()
	{
		$sql = "SELECT * FROM pos_orders WHERE paid_status = 2";
		$query = $this->db->query($sql, array(1));
		return $query->num_rows();
	}

	/*
	* Mark all order items as kprint viewed for a specific order
	*/
	public function markOrderItemsAsKprintViewed($order_id)
	{
		if($order_id) {
			$this->db->where('order_id', $order_id);
			$update = $this->db->update('order_items', array('is_kprint_viewed' => 1));

			// Clear the quantity differences from session after printing
			$this->session->unset_userdata('kprint_qty_differences_' . $order_id);

			return $update;
		}
		return false;
	}

}