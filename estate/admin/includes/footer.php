</div><br><br>
<footer class="text-center" id="footer">&copy; Copyright 2018-2019 Real Estate</footer>


<script type="text/javascript">
	function updateSizes(){
		var sizeString = '';
		for(var i=1; i<=12; i++){
			if(jQuery('#size'+i).val()!=''){
				sizeString += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+',';
			}
		}

		jQuery('#sizes').val(sizeString);
	}

	function get_child_options(selected){
		if(typeof selected === 'undefined'){
			var selected = '';
		}
		var parentId = jQuery('#parent').val();
		jQuery.ajax({
			url:'/estate/admin/parsers/child_categories.php',
			type: 'POST',
			data: {parentId : parentId, selected: selected},
			success: function(data){
				jQuery('#child').html(data);
			},
			error: function(){alert("Something went wrong with the child options")},
		});
	}
	jQuery('select[name="parent"]').change(function(){
		get_child_options();
	});

	$('document').ready(function(){
		$('#show_password').on('click', function(){
			var password = $('#password');
			var inputType = $('#password').attr('type');

			if (inputType == 'password') {
				$('#password').attr('type', 'text');
			}else{
				$('#password').attr('type', 'password');
			}
		});
	});
</script>
</body>
 