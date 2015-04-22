<!-- Navigation & Logo-->
        <div class="mainmenu-wrapper">
	        <div class="container">
	            @if (Auth::check())
		        	<div class="menuextras">
						<div class="extras">
							<ul>
				        		<li>Bienvenido(a): {{ Auth::user()->usu_nombre }}</li>
				        	</ul>
						</div>
			        </div>			    
			    @else
		        	<div class="menuextras">
						<div class="extras">
							<ul>
				        		<li><a href="{{ URL::to('login') }}">Login</a></li>
				        	</ul>
						</div>
			        </div>
		        @endif
		        <nav id="mainmenu" class="mainmenu">
					<ul>
						<li class="logo-wrapper"><a href="{{ URL::to('/') }}"><img src="{{ URL::to('/') }}/img/mPurpose-logo.png" width="100px" height="auto" alt="Multipurpose Twitter Bootstrap Template"></a></li>
						<li class="active">
							<a href="{{ URL::to('/') }}">Inicio</a>
						</li>
						@if (Auth::check())
						<li class="has-submenu">
							<a href="#">Facturación Electrónica</a>
							<div class="mainmenu-submenu">
								<div class="mainmenu-submenu-inner"> 
									<div>
										<h4>Parámetros</h4>
										<ul>
											<li><a href="{{ URL::to('emisor') }}">Emisor</a></li>
											<li><a href="{{ URL::to('cliente') }}">Cliente</a></li>
                                       		<li><a href="{{ URL::to('directorio') }}">Directorios</a></li>
	                                        <li><a href="{{ URL::to('productos') }}">Productos</a></li>
	                                        <li><a href="{{ URL::to('sucursales') }}">Sucursales</a></li>
	                                        <li><a href="{{ URL::to('secuencia-documento') }}">Secuencia documento</a></li>
										</ul>
										<h4>Comprobantes Electrónicos</h4>
										<ul>
											<li><a href="{{ URL::to('factura-electronica')}}">Factura electrónica</a></li>
											<li><a href="{{ URL::to('retenciones') }}">Retenciones</a></li>
											<li><a href="{{ URL::to('nota-credito') }}">Nota de crédito</a></li>
											<li><a href="{{ URL::to('nota-debito') }}">Guía de remisión</a></li>
										</ul>
									</div>
									<div>
										<h4>Facturación Electrónica</h4>
										<ul>
											<li><a href="{{ URL::to('consultar-facturas')}}">Consultar factura</a></li>
											<li><a href="{{ URL::to('reportes') }}">Ver facturas</a></li>
											<li><a href="{{ URL::to('nota-debito') }}">Nota de crédito</a></li>
											<li><a href="{{ URL::to('nota-debito') }}">Guía de remisión</a></li>
											<li><a href="{{ URL::to('factura-intereses') }}">Facturar Intereses</a></li>
										</ul>

										<h4>Configuraciones</h4>
										<ul>
											<li><a href="{{ URL::to('catalogo') }}">Catálogo</a></li>	
										</ul>
										<h4>Cerrar Sesión</h4>
										<ul>
											<li><a href="{{ URL::to('logout') }}">Logout</a></li>
										</ul>
									</div>
                                    <div>
                                        <h4>Reportes</h4>
                                        <ul>
                                            <li><a href="{{ URL::to('reporte-facturas')}}">Facturas</a></li>
                                            <li><a href="{{ URL::to('reporte-retenciones')}}">Retenciones</a></li>
                                            <li><a href="{{ URL::to('reporte-notas')}}">Notas de crédito</a></li>
                                        </ul>
                                    </div>
								</div><!-- /mainmenu-submenu-inner -->
							</div><!-- /mainmenu-submenu -->
						</li>
						@endif
					</ul>
				</nav>
			</div>
		</div>