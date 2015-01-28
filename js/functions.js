$(document).ready(function(){
	$('nav li:eq(0)').on('click', function(){
		$('section article').hide();
		$('section article:eq(0)').show();
	})

	$('nav li:eq(1)').on('click', function(){
		$('section article').hide();
		$('section article:eq(1)').show();
	})

	$('nav li:eq(2)').on('click', function(){
		$('section article').hide();
		$('section article:eq(2)').show();
	})

    

    $('#salesReport').dataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "serverProcessing.php"
    } );

	//------trigger on enter (barcode and quantity)-----------
	$('.input').bind("enterKey",function(e){
	   //do stuff here
	   makeAjaxRequest();
	   setTimeout(calculateTotal, 100);
	   setTimeout(calculateChange, 100);
	   $('input#quantity').val('');
	   $('input#barcode').val('');
	   $('input#barcode').focus();
	   //calculateTotal();
	   //$("#salesTable").last().append("<tr><td>New row</td></tr>");
	});
	$('.input').keyup(function(e){
	    if(e.keyCode == 13)
	    {
	        $(this).trigger("enterKey");
	    }
	});
	
	//-------trigger on upkey (price verifier)--------
	$('#searchData').keyup(function() {
		//search any data related with
		cleanTable();
		searchData();
	});

	//-------save sold items-----------
	$('#saveSale').on('click', function(){
		saveSoldItems();
		clearAll();
	});

	//-------cancel sale---------
	$('#cancelSale').on('click', function(){
		clearAll();
	});

	//-----add no registered article----------
	$('#addRow').on('click', function(){
		$('#newitem').show();
	});

	//-----modal---------
	$('#myModal').on('shown.modal', function () {
    	$('#myInput').focus()
  	})

	$('#saveItemBtn').on('click', function(){
		var desc = $('input#newdescription').val();
		var quant = $('input#newquantity').val();
		var newprice = $('input#newprice').val();

		if ((desc == '') || (quant == '') || (newprice == '')){
			alert("llena todos los campos (descripción, cantidad y precio)");
		}
		else{
			var precio = parseFloat(newprice);
			$('#salesTable tbody').append(
				"<tr>\
					<td><button type='button' class='btn btn-link btn-xs' id='removeButton'>Borrar</button></td>\
					<td>"+desc+"</td>\
					<td>"+quant+"</td>\
					<td>$ "+precio.toFixed(2)+"</td>\
				</tr>"
			);
			setTimeout(calculateTotal, 100);
			setTimeout(calculateChange, 100);
			//--- clean values ---
			$('input#newdescription').val('');
			$('input#newquantity').val('');
			$('input#newprice').val('');

			//$('input#barcode').focus();
			$('#newitem').hide();
		}

	});
	
	//-----remove row----------
	$('#salesTable').on('click', '#removeButton', function(){
		$(this).parents('tr').first().remove();
		setTimeout(calculateTotal, 100);
		setTimeout(calculateChange, 100);
		//$('#removeButton').parent().parent().remove();
	});

	//------get edit information of product--------
	var id=0;
	$('#searchTable').on('click', '#editItem', function(){
		var descr = $(this).parents('tr').find('td:eq(0)').text();
		var value = $(this).parents('tr').find('td:eq(1)').text().split(" ");
		var precio = parseFloat(value[1]);

		$("input#editDescription").val(descr);
		$("input#editPrice").val("$ "+precio.toFixed(2));
		$.ajax({
			url: 'search.php',
			type: 'get',
			data: {description: descr, price: precio},
			success: function(response) {
				//$('table#salesTable tbody').html(response);
				id = (response);
				$("input#editCode").val(response);
			}
		});
		
	});

	//-----calculate change on enter---------
	$('input#change').bind("enterKey",function(e){
	   //do stuff here
	   calculateChange();
	});
	$('input#change').keyup(function(e){
	    if(e.keyCode == 13)
	    {
	        $(this).trigger("enterKey");
	    }
	});
});


function makeAjaxRequest() {
	var barCode = $('input#barcode').val();
	if (barCode == ''){
		alert("ingresa el codigo de barras");
	}
	else{
		var quantity = $('input#quantity').val();
		if (quantity == ''){
			quantity = 1;
		}
		else{
			quantity = $('input#quantity').val();
		}
		$.ajax({
			url: 'search.php',
			type: 'get',
			data: {barCode: $('input#barcode').val(), quantity: quantity},
			success: function(response) {
				//$('table#salesTable tbody').html(response);
				$('#salesTable tbody').append(response);
			}
		});
	}
	
}

function calculateTotal(){
	var MyRows = $('table#salesTable').find('tbody').find('tr');
	var total = 0;
	for (var i = 0; i < MyRows.length; i++) {
		var value = $(MyRows[i]).find('td:eq(3)').text().split(" ");
		var MyIndexValue = parseFloat(value[1]);
		total += MyIndexValue;
	}
	//alert(total);
	//total.toFixed(2);
	$("#totalPay").text("$ "+total.toFixed(2)+" MXN");
}

function calculateChange(){
	var value = $('h2#totalPay').text().split(" ");
	var total = parseFloat(value[1]);
	
	var pago = $('input#change').val();
	if (pago == ''){
		//alert("ingresa el monto para pagar");
	}
	else{
		var change = pago - total;
		$("#cashChange").text("$ "+change.toFixed(2)+" MXN");
		//alert(change);
	}
}

function saveSoldItems(){
	var array = [];
	var MyRows = $('table#salesTable').find('tbody').find('tr');
	for (var i = 0; i < MyRows.length; i++) {
		var description = $(MyRows[i]).find('td:eq(1)').text();
		var quantity = $(MyRows[i]).find('td:eq(2)').text();
		var value = $(MyRows[i]).find('td:eq(3)').text().split(" ");
		var MyIndexValue = parseFloat(value[1]);
		array[i] = [description, quantity, MyIndexValue];
	}
	//console.log(array);
	var myJSONText = JSON.stringify(array);
	//console.log(myJSONText);
	$.ajax({
			url: 'saveSale.php',
			type: 'get',
			data: {values: myJSONText},
			success: function(response) {
				$('#salesTable tbody').append(response);
			}
		});
}

function clearAll(){
	//-----quantity and bar code section
	$('input#quantity').val('');
	$('input#barcode').val('');

	//---register new item section----
	$('input#newdescription').val('');
	$('input#newquantity').val('');
	$('input#newprice').val('');
	$('#newitem').hide();

	//-----table section-----
	var MyRows = $('table#salesTable').find('tbody').find('tr');
	for (var i = 0; i < MyRows.length; i++) {
		MyRows[i].remove();
	}

	//----total pay section------
	$("#totalPay").text("$ 0.00 MXN");
	$('input#change').val("");
	$("#cashChange").text("$ 0.00 MXN");
}

function searchData(){
	$.ajax({
			url: 'search.php',
			type: 'get',
			data: {keyword: $('#searchData').val()},
			success: function(response) {
				//$('table#salesTable tbody').html(response);
				$('#searchTable tbody').append(response);
			}
		});
}

function cleanTable(){
	var MyRows = $('table#searchTable').find('tbody').find('tr');
	for (var i = 0; i < MyRows.length; i++) {
		MyRows[i].remove();
	}
}
/*
function showSalesReport(){
	$.ajax({
			url: 'search.php',
			type: 'get',
			data: {getSales: 1},
			success: function(response) {
				//$('table#salesTable tbody').html(response);
				$('#salesReport tbody').append(response);
			}
		});
}
*/