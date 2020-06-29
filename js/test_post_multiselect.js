
$(document).ready(function(){
	$('.selectpicker').selectpicker();
	$('#framework').change(function(){
		$('#hidden_framework').val($('#framework').val());
	});
	$('#multiple_select_form').on('submit', function(event){
		event.preventDefault();
		//var framework = $('#framework').val();
		if($('#framework').val() != '')
		{
			/*var form_data = $(this).serialize();
			$.ajax({
				url:"insert.php",
				method:"POST",
				data:form_data,
				success:function(data){
					$('#hidden_framework').val('');
					$('.selectpicker').selectpicker('val', '');
					alert(data);
				}
			});*/
			alert("Inserted Value - '"+$('#hidden_framework').val()+"'");
			$('#hidden_framework').val('');
			$('.selectpicker').selectpicker('val', '');
			
		}
		else
		{
			alert("Please select framework");
			return false;
		}
	});
});