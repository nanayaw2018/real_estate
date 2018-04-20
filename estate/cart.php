<?php 
	require_once 'core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	include 'includes/header_full.php';


	if($cart_id != ''){
		$cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
		$result = mysqli_fetch_assoc($cartQ);
		$items = json_decode($result['items'], true);

		$i = 1;
		$sub_total = 0;
		$item_count = 0;
	}
?>
<div class="container">
<div class="col-md-12">
	<div class="row">
		<h2 class="text-center">My Shopping Cart</h2><hr>
		<?php if($cart_id == ''): ?>
			<div class="bg-danger">
				<p class="text-center text-danger">
					Your shopping cart is empty!
				</p>
			</div>
		<?php else: ?>
			<table class="table table-condensed table-bordered table-striped">
				<thead>
					<th>#</th>
					<th>Item</th>
					<th>Price</th>
					<th>Quantity</th>
					<th>Size</th>
					<th>Sub Total</th>
				</thead>

				<tbody>
					<?php 
					foreach ($items as $item) {
						$product_id = $item['id'];
						$productQ = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
						$product = mysqli_fetch_assoc($productQ);
						$sArray = explode(',', $product['sizes']);
						foreach ($sArray as $sizeString) {
							$s = explode(':',$sizeString);
							if($s[0] == $item['size']){
								$available = $s[1];
							}
						}
					?>
					<tr>
						<td><?=$i?></td>
						<td><?=$product['title'];?></td>
						<td><?=money($product['price']);?></td>
						<td>

						<button class="btn btn-xs btn-default" onclick="update_cart('removeone','<?=$product['id'];?>', '<?=$item['size'];?>');">-</button>

						<?=$item['quantity'];?>
						<?php if($item['quantity'] < $available): ?>
						<button class="btn btn-xs btn-default" onclick="update_cart('addone', '<?=$product['id'];?>', '<?=$item['size'];?>');">+</button>
						<?php else: ?>
							<span class="text-danger">Max</span>
						<?php endif; ?>
						</td>
						<td><?=$item['size'];?></td>
						<td><?=money($product['price']);?></td>
					</tr>
					<?php 
					$i++;
					$item_count += $item['quantity'];
					$sub_total += ($product['price'] * $item['quantity']);
					} 

					$tax = TAXRATE * $sub_total;
					$tax = number_format($tax, 2);
					$grand_total = $tax + $sub_total;
					?>
				</tbody>
			</table>
			<table class="table table-bordered table-condensed text-right">
				<legend>Totals</legend>
				<thead class="totals-table-header">
					<th>Total Items</th>
					<th>Sub Total</th>
					<th>Tax</th>
					<th>Grand Total</th>
				</thead>
				<tbody>
					<tr>
						<td><?=$item_count;?></td>
						<td><?=money($sub_total);?></td>
						<td><?=money($tax);?></td>
						<td class="bg-success"><?=money($grand_total);?></td>
					</tr>
				</tbody>
			</table>


		<!-- Check Out Button -->
		<button type="button" class="btn btn-success btn-md pull-right" data-toggle="modal" data-target="#checkoutModal">
		  <span class="glyphicon glyphicon-shopping-cart"></span> Check Out >>
		</button>

		<!-- Modal -->
		<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="checkoutModalLabel">Shipping Address</h4>
		      </div>
		      <div class="modal-body">
		      	<div class="row">
			        <form action="thankYou.php" method="post" id="payment-form">
			        	<span class="bg-danger" id="payment_errors"></span>
			        	<div id="step1" style="display:block;">
			        		<div class="form-group col-md-6">
			        			<label for="full_name">Full Name:</label>
			        			<input class="form-control" id="full_name" name="full_name" type="text">
			        		</div>
			        		<div class="form-group col-md-6">
			        			<label for="email">Email:</label>
			        			<input class="form-control" id="email" name="email" type="email">
			        		</div>
			        		<div class="form-group col-md-6">
			        			<label for="street">Street Address:</label>
			        			<input class="form-control" id="street" name="street" type="text">
			        		</div>
			        		<div class="form-group col-md-6">
			        			<label for="street2">Street Address 2:</label>
			        			<input class="form-control" id="street2" name="street2" type="text">
			        		</div>
			        		<div class="form-group col-md-6">
			        			<label for="city">City:</label>
			        			<input class="form-control" id="city" name="city" type="text">
			        		</div>
			        		<div class="form-group col-md-6">
			        			<label for="state">State:</label>
			        			<input class="form-control" id="state" name="state" type="text">
			        		</div>
			        		<div class="form-group col-md-6">
			        			<label for="zip_code">Zip Code:</label>
			        			<input class="form-control" id="zip_code" name="zip_code" type="text">
			        		</div>
			        		<div class="form-group col-md-6">
			        			<label for="country">Country:</label>
			        			<input class="form-control" id="country" name="country" type="text">
			        		</div>
			        	</div>
			        	<div id="step2" style="display:none;">
			        		<div class="form-group col-md-3">
			        			<label for="name">Name On Card:</label>
			        			<input type="text" id="name" class="form-control">
			        		</div>
			        		<div class="form-group col-md-3">
			        			<label for="card_number">Card Number:</label>
			        			<input type="text" id="card_number" class="form-control">
			        		</div>
			        		<div class="form-group col-md-2">
			        			<label for="cvc">CVC:</label>
			        			<input type="text" id="cvc" class="form-control">
			        		</div>
			        		<div class="form-group col-md-2">
			        			<label for="expire_month">Expire Month:</label>
			        			<select id="expire_month" class="form-control">
			        				<option value=""></option>
			        				<?php for($i = 1; $i < 13; $i++):?>
			        					<option value="<?=$i;?>"><?=$i;?></option>
			        				<?php endfor; ?>
			        			</select>
			        		</div>
			        		<div class="form-group col-md-2">
			        			<label for="expire_year">Expire Year:</label>
			        			<select id="expire_year" class="form-control">
			        				<option value=""></option>
			        				<?php $yr = date("Y"); ?>
			        				<?php for($i=0; $i < 11; $i++): ?>
			        					<option value="<?=$yr + $i;?>"><?=$yr + $i;?></option>
			        				<?php endfor; ?>
			        			</select>
			        		</div>
			        	</div>
			        </form>
		        </div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <button type="button" class="btn btn-primary" id="next_button" onclick="check_address();">Next >></button>
		         <button type="button" class="btn btn-primary" id="back_button" onclick="back_address();" style="display:none;"><< Back</button>
		        <button type="submit" class="btn btn-primary" id="check_out_button" style="display:none;"> Check Out >></button>
		      </div>
		    </div>
		  </div>
		</div>


		<?php endif; ?>
	</div>
</div>
</div>

<script type="text/javascript">
	function back_address(){
		jQuery('#payment_errors').html("");
		jQuery('#step1').css("display","block");
		jQuery('#step2').css("display","none");
		jQuery('#next_button').css("display","inline-block");
		jQuery('#back_button').css("display","none");
		jQuery('#check_out_button').css("display","none");
		jQuery('#checkoutModalLabel').html("Shipping Address");

	}

	function check_address(){
		var data = {
			'full_name' : jQuery('#full_name').val(),
			'email' : jQuery('#email').val(),
			'street' : jQuery('#street').val(),
			'street2' : jQuery('#street2').val(),
			'city' : jQuery('#city').val(),
			'state' : jQuery('#state').val(),
			'zip_code' : jQuery('#zip_code').val(),
			'country' : jQuery('#country').val()
		};

		jQuery.ajax({
			url: '/estate/admin/parsers/check_address.php',
			method: 'POST',
			data: data,
			success: function(data){
				if (data != 'passed') {
					jQuery('#payment_errors').html(data); 
				} 
				if (data == 'passed') {
					jQuery('#payment_errors').html("");
					jQuery('#step1').css("display","none");
					jQuery('#step2').css("display","block");
					jQuery('#next_button').css("display","none");
					jQuery('#back_button').css("display","inline-block");
					jQuery('#check_out_button').css("display","inline-block");
					jQuery('#checkoutModalLabel').html("Enter Your Card Details");
				}
			},
			error: function(){
				alert('Something went wrong');
			}
		});
	}
</script>
<?php include 'includes/footer.php' ?>