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

   	$('.alert').hide();

    $('#salesReport').dataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "serverProcessing.php"
    } );

	//------trigger on enter (barcode and quantity)-----------
	$('.input').bind("enterKey",function(e){
	   //do stuff here
	   makeAjaxRequest();
	   setTimeout(calculateTotal, 400);
	   setTimeout(calculateChange, 400);
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
		searchData();
	});
	/*
	$('#searchData').keypress(function() {
		//search any data related with
		cleanTable();
	});	
	*/
	//-------save sold items-----------
	$('#saveSale').on('click', function(){
		var value = $('h2#totalPay').text().split(" ");
		var total = parseFloat(value[1]);

		if ( isNaN (total)){
			alert("Error: el precio total no es un número");
		}
		else{
			saveSoldItems();
			clearAll();
		}
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
		$("input#editPrice").val(precio.toFixed(2));
		$.ajax({
			url: 'search.php',
			type: 'get',
			data: {description: descr, price: precio},
			success: function(response) {
				//$('table#salesTable tbody').html(response);
				var arr = (response).split("-");
				id = arr[0];
				$("input#editCode").val(arr[1]);
			}
		});
	});

	//--------Save changes of product (modal)---------
	$('#saveChanges').on('click', function(){
		//console.log(id);
		var description = $('#editDescription').val();
		var price = $('#editPrice').val();
		var code = $('#editCode').val();
		console.log(description);
		console.log(price);
		console.log(code);
		if (description == "" || price == "" || code == ""){
			alert("Llena todos los campos (descripción, precio y código)");
		}
		else{
			$.ajax({
				url: 'search.php',
				type: 'get',
				data: {id: id, newdescription: description, newprice: price, newcode: code},
				success: function(response) {
					//$('table#salesTable tbody').html(response);
					console.log(response);
				}
			});
			$('#saveSuccess').show();
			setTimeout(hideAlerts, 3000);
		}
		searchData();
	});

	//--------Delete product--------
	$('#deleteProduct').on('click', function(){
		console.log(id);
		$.ajax({
				url: 'search.php',
				type: 'get',
				data: {deleteid: id},
				success: function(response) {
					//$('table#salesTable tbody').html(response);
					console.log(response);
				}
			});
		$('#myModal').modal('hide');
		$('#myModalSuccess').modal('show');
		$('#deleteSuccess').show();
		searchData();
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

	//------register new product---------
	$('#registerItem').on('click', function(){
		$('#registerDescription').val('');
		$('#registerPrice').val('');
		$('#registerCode').val('');
		$('#myModalRegister').modal('show');
	});

	//-------export to excel-------------
	$('#exportExcel').on('click', function(){
		$('#salesReport').tableExport({
			type:'excel',
			escape:'false',
			tableName:'reporte de ventas'
		});
	});

	//--------export to PDF----------
	$('#exportPDF').on('click', function(){
		$('#salesReport').tableExport({
			type:'pdf',
			escape:'false',
			tableName:'reporte de ventas',
			pdfFontSize:9,
			pdfLeftMargin:20,
			htmlContent:'true'
		});
	});

	//-----save new product----------
	$('#saveNewProduct').on('click', function(){
		var description = $('#registerDescription').val();
		var price = $('#registerPrice').val();
		var code = $('#registerCode').val();
		console.log(description);
		console.log(price);
		console.log(code);
		if (description == "" || price == "" || code == ""){
			alert("Llena todos los campos (descripción, precio y código)");
		}
		else{
			$.ajax({
				url: 'search.php',
				type: 'get',
				data: {registerdescription: description, registerprice: price, registercode: code},
				success: function(response) {
					//$('table#salesTable tbody').html(response);
					console.log(response);
				}
			});
			$('#myModalRegister').modal('hide');
			$('#myModalRegisterSuccess').modal('show');
			$('#registerSuccess').show();
		}
		searchData();
	});
});

$(function () {
    $('#graphContainer').highcharts({
        chart: {
            type: 'area'
        },
        title: {
            text: 'US and USSR nuclear stockpiles'
        },
        subtitle: {
            text: 'Source: <a href="http://thebulletin.metapress.com/content/c4120650912x74k7/fulltext.pdf">' +
                'thebulletin.metapress.com</a>'
        },
        xAxis: {
            allowDecimals: false,
            labels: {
                formatter: function () {
                    return this.value; // clean, unformatted number for year
                }
            }
        },
        yAxis: {
            title: {
                text: 'Nuclear weapon states'
            },
            labels: {
                formatter: function () {
                    return this.value / 1000 + 'k';
                }
            }
        },
        tooltip: {
            pointFormat: '{series.name} produced <b>{point.y:,.0f}</b><br/>warheads in {point.x}'
        },
        plotOptions: {
            area: {
                pointStart: 1940,
                marker: {
                    enabled: false,
                    symbol: 'circle',
                    radius: 2,
                    states: {
                        hover: {
                            enabled: true
                        }
                    }
                }
            }
        },
        series: [{
            name: 'USA',
            data: [null, null, null, null, null, 6, 11, 32, 110, 235, 369, 640,
                1005, 1436, 2063, 3057, 4618, 6444, 9822, 15468, 20434, 24126,
                27387, 29459, 31056, 31982, 32040, 31233, 29224, 27342, 26662,
                26956, 27912, 28999, 28965, 27826, 25579, 25722, 24826, 24605,
                24304, 23464, 23708, 24099, 24357, 24237, 24401, 24344, 23586,
                22380, 21004, 17287, 14747, 13076, 12555, 12144, 11009, 10950,
                10871, 10824, 10577, 10527, 10475, 10421, 10358, 10295, 10104]
        }, {
            name: 'USSR/Russia',
            data: [null, null, null, null, null, null, null, null, null, null,
                5, 25, 50, 120, 150, 200, 426, 660, 869, 1060, 1605, 2471, 3322,
                4238, 5221, 6129, 7089, 8339, 9399, 10538, 11643, 13092, 14478,
                15915, 17385, 19055, 21205, 23044, 25393, 27935, 30062, 32049,
                33952, 35804, 37431, 39197, 45000, 43000, 41000, 39000, 37000,
                35000, 33000, 31000, 29000, 27000, 25000, 24000, 23000, 22000,
                21000, 20000, 19000, 18000, 18000, 17000, 16000]
        }]
    });
});

function hideAlerts(){
	$('.alert').hide();
}

function makeAjaxRequest() {
	var barCode = $('input#barcode').val();
	if (barCode == ''){
		alert("ingresa el codigo de barras");
	}
	else{
		if (barCode[0] == '0'){
			arrtemp = barCode.split("0");
			barCode = arrtemp[1];
		}
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
				//$('#salesTable tbody').append(response);
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
	var keyword = $('#searchData').val();
	if (keyword[0] == '0'){
		arrtemp = keyword.split("0");
		keyword = arrtemp[1];
	}
	$.ajax({
			url: 'search.php',
			type: 'get',
			data: {keyword: keyword},
			success: function(response) {
				//$('table#salesTable tbody').html(response);
				$('#searchTable tbody').html(response);
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