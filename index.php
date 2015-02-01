<!DOCTYPE html>
<html lang="es">

	<head>
		<title>Abarrotes en general</title>
		<meta http-equiv="content-type" 
		content="text/html;charset=utf-8" />
		<meta name="author" content="Ernesto Garcia">
		
		<link rel="stylesheet" type="text/css" href="css/normalize.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap-glyphicons.css">
		<link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

		<script src="js/jquery-2.1.1.js"></script>
		<script src="js/functions.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/jquery.dataTables.min.js"></script>
		<script src="js/dataTables.bootstrap.js"></script>

		<script src="http://code.highcharts.com/highcharts.js"></script>
		<script src="http://code.highcharts.com/modules/exporting.js"></script>
	</head>

	<body>

		<div id="mainDiv">
			<header>
				<h1> SuperMarket</h1>
			</header>
			<nav>
				<ul class="nav nav-tabs" role="tablist" id="myTab">
				  <li class="active"><a href="#CashRegister" role="tab" data-toggle="tab">Caja</a></li>
				  <li><a href="#priceVerifier" role="tab" data-toggle="tab">Verificador</a></li>
				  <li><a href="#reports" role="tab" data-toggle="tab">Reportes</a></li>
				</ul>
			</nav>

			<section>
				<article id="CashRegister">
					<!-- Caja -->
					<h3>Caja</h3>
					<!-- Text input-->
					<div class="control-group">
					  <!--<label class="control-label" for="textinput">Código de barras:</label>-->
					  	<div class="input">
					    	<input id="barcode" name="textinput" type="text" placeholder="#Código" class="form-control" required>
						</div>
					  	<div class="input">
					  		<input id="quantity" class="form-control" type="text" placeholder="Cantidad" required>
					  	</div>
					</div>
					<div class="row" id="divRow">
						<div class="col-md-8">
							<!-- Add item section -->
							<button id="addRow" type="button" class="btn btn-primary">Agregar producto</button>

							<div class="row" id="newitem">
	  							<div class="col-xs-4">
	  								<input id="newdescription" class="form-control" type="text" placeholder="Descripción">
	  							</div>
	  							<div class="col-xs-4">
	  								<input id="newquantity" class="form-control" type="number" placeholder="Cantidad">
	  							</div>
	  							<div class="col-xs-4">

		  							<div class="input-group"><!-- input-group -->
		  								<span class="input-group-addon">$</span>
		  								<input id="newprice" class="form-control" type="text" placeholder="Precio">
								      	<span class="input-group-btn">
								        	<button class="btn btn-success" id="saveItemBtn" type="button">Agregar</button>
								      	</span>
								    </div>					<!-- /input-group -->

								</div>
	  						</div>

							<table id="salesTable" class="table table-striped" cellspacing="1" width="60%">
								<thead>
						            <tr>
						            	<th></th>					            	
						                <th>Descripción</th>
						                <th>Cantidad</th>
						                <th>Precio</th>
						            </tr>
						        </thead>
						        <tbody></tbody>
							</table>
						</div>
						<div class="col-md-4">
							<div class="panel panel-primary">
								<!-- Default panel contents -->
	  							<div class="panel-heading">
	  								<div class="row">
	  									<div class="col-xs-4">
	  										<h2>Total:</h2>
	  									</div>
	  									<div class="col-xs-8">
	  										<h2 id="totalPay">$ 0.00 MXN</h2>
	  									</div>
	  								</div>
	  							</div>
	  							<div class="panel-body">
	  								<!-- Pay section -->
	    							<div class="row">
	  									<div class="col-xs-6">
	  										<h4>Pagar:</h4>
	  									</div>
	  									<div class="col-xs-6">
	  										<div class="input-group">
											  	<span class="input-group-addon">$</span>
											  	<input type="text" class="form-control" id="change">
											</div>
	  									</div>
	  								</div>
	  								<!-- Change section -->
	    							<div class="row">
	  									<div class="col-xs-6">
	  										<h4>Cambio:</h4>
	  									</div>
	  									<div class="col-xs-6">
	  										<h4 id="cashChange">$ 0.00 MXN</h4>
	  									</div>
	  								</div>
	  								<!-- save and cancel buttons -->
	  								<div class="row">
	  									<div class="col-xs-6">
	  										<button id="saveSale" type="button" class="btn btn-success">Guardar venta</button>
	  									</div>
	  									<div class="col-xs-6">
	  										<button id="cancelSale" type="button" class="btn btn-danger">Cancelar venta</button>
	  									</div>
	  								</div>
	    						</div>
							</div>
						</div>
						
					</div>
				</article>

			<!-- .......Price Verifier section.......... -->
				<article id="priceVerifier">
					<h3>Verificador de precios</h3>
					<div class="row" style="margin-top: 3%">
	       				<div class="col-md-3">
							<div class="form-group">
				                <div class="icon-addon addon-md">
				                    <input type="text" placeholder="Buscar" class="form-control" id="searchData">
				                    <label for="search" class="glyphicon glyphicon-search" rel="tooltip"></label>
				                </div>
				            </div>
			        	</div>
			        	<div class="col-md-3">
			        		<button id="registerItem" type="button" class="btn btn-primary">Registrar producto</button>
			        	</div>
		    		</div>
		    		<div class="row col-md-6">
			    		<table id="searchTable" class="table table-striped" cellspacing="1" width="60%">
							<thead>
							    <tr>
							        <th>Descripción</th>
							        <th>Precio</th>
							        <th></th>
							    </tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>

					<!-- .... Modal for register new product ...... -->
					<div class="modal fade" id="myModalRegister" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        							<h4 class="modal-title">Registrar producto</h4>
      							</div>
      							<div class="modal-body">
      								<div class="row spaced">
      									<div class="col-xs-2">
      										<p>Descripción:</p>
      									</div>
      									<div class="col-xs-6">
      										<input class="form-control" id="registerDescription">
      									</div>
      								</div>
      								<div class="row spaced">
      									<div class="col-xs-2">
      										<p>Precio:</p>
      									</div>
      									<div class="col-xs-6">
      										<div class="input-group">
											  	<span class="input-group-addon">$</span>
											  	<input type="text" class="form-control" id="registerPrice">
											</div>
      									</div>
      								</div>
      								<div class="row spaced">
      									<div class="col-xs-2">
      										<p>Código:</p>
      									</div>
      									<div class="col-xs-6">
      										<input class="form-control" id="registerCode">
      									</div>
      								</div>
      							</div>
      							<div class="modal-footer">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        							<button type="button" class="btn btn-success" id="saveNewProduct">Guardar</button>
      							</div>
      							
							</div>
						</div>
					</div>

					<!-- .... Modal for edit product ...... -->
					<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        							<h4 class="modal-title">Editar producto</h4>
      							</div>
      							<div class="modal-body">
      								<div class="row spaced">
      									<div class="col-xs-2">
      										<p>Descripción:</p>
      									</div>
      									<div class="col-xs-6">
      										<input class="form-control" id="editDescription">
      									</div>
      								</div>
      								<div class="row spaced">
      									<div class="col-xs-2">
      										<p>Precio:</p>
      									</div>
      									<div class="col-xs-6">
      										<div class="input-group">
											  	<span class="input-group-addon">$</span>
											  	<input type="text" class="form-control" id="editPrice">
											</div>
      									</div>
      								</div>
      								<div class="row spaced">
      									<div class="col-xs-2">
      										<p>Código:</p>
      									</div>
      									<div class="col-xs-6">
      										<input class="form-control" id="editCode">
      									</div>
      								</div>
      								<div class="alert alert-success" role="alert" id="saveSuccess">
      									Los cambios se han guardado con <strong>éxito.</strong>
	      							</div>
      							</div>
      							<div class="modal-footer">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        							<button type="button" class="btn btn-success" id="saveChanges">Guardar cambios</button>
        							<button type="button" class="btn btn-danger" id="deleteProduct">Borrar producto</button>
      							</div>
      							
							</div>
						</div>
					</div>

					<!-- Modal for register success -->
					<div class="modal fade" id="myModalRegisterSuccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        							<h4 class="modal-title">Registrar producto</h4>
      							</div>
      							<div class="modal-body">
      								<div class="alert alert-info alert-dismissable" role="alert" id="registerSuccess">
      									Nuevo producto <strong>registrado.</strong>
	      							</div>
      							</div>
      							<div class="modal-footer">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        						</div>
							</div>
						</div>
					</div>

					<!-- Modal for delete success -->
					<div class="modal fade" id="myModalSuccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
        							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        							<h4 class="modal-title">Editar producto</h4>
      							</div>
      							<div class="modal-body">
      								<div class="alert alert-danger alert-dismissable" role="alert" id="deleteSuccess">
      									El producto ha sido <strong>eliminado.</strong>
	      							</div>
      							</div>
      							<div class="modal-footer">
        							<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        						</div>
							</div>
						</div>
					</div>

		    		

				</article>
				<article id="reports">
					<h3>Reporte de ventas</h3>

					<table id="salesReport" class="table table-striped table-bordered" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				                <th>Descripción</th>
				                <th>Cantidad</th>
				                <th>Total</th>
				                <th>Fecha</th>
				                <th>Código</th>
				            </tr>
				        </thead>
				 		<!--
				        <tfoot>
				            <tr>
				                <th>Descripción</th>
				                <th>Cantidad</th>
				                <th>Total</th>
				                <th>Fecha</th>
				                <th>Código</th>
				            </tr>
				        </tfoot>
						-->
				    </table>

				    <div id="graphContainer" style="min-width: 95%; height: 500px; margin: 0 auto"></div>


				</article>
			</section>
			
		</div>

		<footer>
			
		</footer>
	</body>
</html>