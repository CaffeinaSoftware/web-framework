<html>
<head>
	<link rel="stylesheet" type="text/css" href="../css/final_api.css">
	<script type="text/javascript" src="http://api.caffeina.mx/jquery/jquery-1.4.2.min.js"></script>
	<title></title>
	<script type="text/javascript">
		function addParam(){
			var html = '';
			html += '';
			$("#param_space").append()
		}


		var ApiMethod = function(){
			
			this.nombre 	= null;
			this.metodo 	= null;

			this.auth 		= {
				group 	: null,
				permiso : null
			};

			this.params 	= [];
			this.response 	= [];
			this.ejemplo_entrada = null;
			this.ejemplo_salida	 = null;

			this.render = function(){
				var o = $("#render_preview");
				//clean the space
				o.html("");

				
			}
		};


	

	</script>
</head>
<body>


	<table>
		<tr>
			<td>
				<!-- --------------------------------------------------------------------
						EDITOR
				     -------------------------------------------------------------------- -->
				     <table>
				     	<tr>
				     		<td colspan=2>General</td>
				     	</tr>
				     	<tr>
				     		<td>Nombre</td>
				     		<td>
								<input type="text">
				     		</td>
				     	</tr>
				     	<tr>
				     		<td>Descripcion</td>
				     		<td>
								<textarea>
								</textarea>
				     		</td>
				     	</tr>				     	
				     	<tr>
				     		<td>Method</td>
				     		<td>
				     		<select>
								<option>GET</option>
								<option>POST</option>
								<option>Ambos</option>
							</select>
							</td>
				     	</tr>
				     	<tr>
				     		<td colspan=2>Autenticacion</td>
				     	</tr>				     	
				     	<tr>
				     		<td>Autenticacion</td>
				     		<td></td>
				     	</tr>
				     	<tr>
				     		<td></td>
				     		<td></td>
				     	</tr>
				     	<tr>
				     		<td></td>
				     		<td></td>
				     	</tr>

				     </table>	
					<form>


						<h2></h2>
						

						<h2>Autenticacion</h2>
						<input >
							
						<h2>Parametros</h2>
						<div id="param_space">
							<table>
								<tr>
									<td>Nombre</td>
									<td>Descripcion</td>
									<td>Tipo</td>
									<td>Default</td>
								</tr>

								<tr>
								<td>
									<input type="text" placeholder="nombre">
								</td>

								<td>
									<textarea placeholder="descripcion" ></textarea>
								</td>

								<td>
									<select>
										<option>Obligatorio</option>
										<option>Opcional</option>
									</select>
								</td>

								<td>
									<select>
										<option>string</option>
										<option>bool</option>
										<option>int</option>
										<option>float</option>
										<option>json</option>
									</select>
								</td>

								<td><input placeholder="default"></td>
								</tr>

							</table>
						</div>
					</form>





			</td>
			<td width="50%">
				<!-- --------------------------------------------------------------------
						PREVIEW
				     -------------------------------------------------------------------- -->
				<div id="render_preview">

<table cellpadding="0" cellspacing="0" class="c0">
<tbody>
<tr class="c1">
	<td class="c25 c41">
		<p class="c3">
			<span class="c19">GET api/sesion/iniciar</span>
		</p>
		<p class="c3">
			<span class="c31">Validar e iniciar una sesion</span><span class="c31">.</span>
		</p>
	</td>
</tr>
</tbody>
</table>
<p class="c22 c15">
	<span>Valida las credenciales de un usuario y regresa un url a donde se debe de redireccionar. Este m&eacute;todo no necesita de ning&uacute;n tipo de autenticaci&oacute;n. Si se detecta un tipo de usuario inferior a admin y no se ha llamado antes a </span><span class="c8">api/sucursal/revisar_sucursal</span><span>&nbsp;se regresar&aacute; un </span><span class="c8">403 Authorization Required </span><span>y la sesi&oacute;n no se iniciar&aacute;. &nbsp;Si el usuario que esta intentando iniciar sesion, esta descativado... </span><span class="c8">403 Authorization Required supongo</span>
</p>
<h4 class="c15"><a name="h.u52pfb3h2ec5"></a><span>Autenticaci&oacute;n</span></h4>
<table cellpadding="0" cellspacing="0" class="c0">
<tbody>
<tr>
	<td class="c9">
		<p class="c3">
			<span class="c6">Sesion valida</span>
		</p>
	</td>
	<td class="c16">
		<p class="c3">
			<span class="c7">No</span>
		</p>
	</td>
</tr>
<tr class="c169">
	<td class="c9">
		<p class="c3">
			<span class="c6">Grupo</span>
		</p>
	</td>
	<td class="c16">
		<p class="c3">
			<span class="c7">Cualquiera</span>
		</p>
	</td>
</tr>
<tr class="c1">
	<td class="c9">
		<p class="c3">
			<span class="c6">Permiso</span>
		</p>
	</td>
	<td class="c16">
		<p class="c22 c15">
			<span class="c8 c7">api/sesion/iniciar</span>
		</p>
	</td>
</tr>
</tbody>
</table>
<h4 class="c15"><a name="h.fmkw97a0ehmh"></a><span>Argumentos</span></h4>
<table cellpadding="0" cellspacing="0" class="c0">
<tbody>
<tr>
	<td class="c135">
		<p class="c3">
			<span class="c7">usuario</span>
		</p>
	</td>
	<td class="c61">
		<p class="c3">
			<span class="c7">string</span>
		</p>
	</td>
	<td class="c96">
		<p class="c3">
			<span class="c7">Obligatorio</span>
		</p>
	</td>
	<td class="c82">
		<p class="c3">
			<span class="c7">El id de usuario a intentar iniciar sesi&oacute;n.</span>
		</p>
	</td>
</tr>
<tr>
	<td class="c135">
		<p class="c3">
			<span class="c7">password</span>
		</p>
	</td>
	<td class="c61">
		<p class="c3">
			<span class="c7">string</span>
		</p>
	</td>
	<td class="c96">
		<p class="c3">
			<span class="c7">Obligatorio</span>
		</p>
	</td>
	<td class="c82">
		<p class="c3">
			<span class="c7">La contrase&ntilde;a del usuario.</span>
		</p>
	</td>
</tr>
<tr>
	<td class="c135">
		<h4 class="c3 c12"><a name="h.9lm2h3uody6n"></a><span class="c7">request_token</span></h4>
	</td>
	<td class="c61">
		<h4 class="c3 c12"><a name="h.dag43ipxnhz7"></a><span class="c7">bool</span></h4>
	</td>
	<td class="c96">
		<h4 class="c3 c12"><a name="h.ftmijvku8bem"></a><span class="c7">Opcional</span></h4>
		<p class="c15">
			<span class="c8">false</span>
		</p>
	</td>
	<td class="c82">
		<h4 class="c3 c12"><a name="h.xwpnmx5udl7t"></a><span class="c7">Si se env&iacute;a, y es verdadero, el seguimiento de esta sesi&oacute;n se har&aacute; mediante un token, de lo contrario se har&aacute; mediante cookies.</span></h4>
	</td>
</tr>
</tbody>
</table>
<h4 class="c15"><a name="h.vzpf48a1ugdo"></a><span>Respuesta</span></h4>
<table cellpadding="0" cellspacing="0" class="c0">
<tbody>
<tr>
	<td class="c93">
		<p class="c3">
			<span class="c7">login_succesful</span>
		</p>
	</td>
	<td class="c40">
		<p class="c3">
			<span class="c7">bool</span>
		</p>
	</td>
	<td class="c60">
		<p class="c3">
			<span class="c7">Si la validaci&oacute;n del usuario es correcta.</span>
		</p>
	</td>
</tr>
<tr class="c121">
	<td class="c93">
		<p class="c3">
			<span class="c7">siguiente_url</span>
		</p>
	</td>
	<td class="c40">
		<p class="c3">
			<span class="c7">string</span>
		</p>
	</td>
	<td class="c60">
		<p class="c3">
			<span class="c7">La url a donde se debe de redirigir.</span>
		</p>
	</td>
</tr>
<tr>
	<td class="c93">
		<p class="c3">
			<span class="c7">usuario_grupo</span>
		</p>
	</td>
	<td class="c40">
		<p class="c3">
			<span class="c7">int</span>
		</p>
	</td>
	<td class="c60">
		<p class="c3">
			<span class="c7">El grupo al que este usuario pertenece.</span>
		</p>
	</td>
</tr>
<tr>
	<td class="c93">
		<p class="c3">
			<span class="c7">auth_token</span>
		</p>
	</td>
	<td class="c40">
		<p class="c3">
			<span class="c7">string</span>
		</p>
	</td>
	<td class="c60">
		<p class="c3">
			<span class="c7">El token si es que fue solicitado.</span>
		</p>
	</td>
</tr>
</tbody>
</table>
<h4 class="c15"><a name="h.prszvv6aw8cg"></a><span>Ejemplo</span></h4>
<p class="c15">
	<span>Peticion</span>
</p>
<table cellpadding="0" cellspacing="0" class="c0">
<tbody>
<tr>
	<td class="c25 c13">
		<p class="c15">
			<span class="c8">{</span>
		</p>
		<p class="c15">
			<span class="c8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&ldquo;</span><span class="c4">usuario&rdquo;</span><span class="c8">&nbsp;: &ldquo;123&rdquo;,</span>
		</p>
		<p class="c15">
			<span class="c8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&ldquo;</span><span class="c4">password&rdquo;</span><span class="c8">: &ldquo;abcde&rdquo;,</span>
		</p>
		<p class="c15">
			<span class="c8">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&ldquo;</span><span class="c4">request_token&rdquo;</span><span class="c8">: true</span>
		</p>
		<p class="c15">
			<span class="c8">}</span>
		</p>
	</td>
</tr>
</tbody>
</table>
<p class="c10 c15">
	<span></span>
</p>
<p class="c15">
	<span>Respuesta</span>
</p>
<table cellpadding="0" cellspacing="0" class="c0">
<tbody>
<tr class="c1">
	<td class="c25 c13">
		<p class="c15">
			<span>{</span>
		</p>
		<p class="c15">
			<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="c83">&ldquo;success&rdquo;</span><span>&nbsp;: true,</span>
		</p>
		<p class="c15">
			<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="c83">&ldquo;login_succesful&rdquo;</span><span>&nbsp;: true,</span>
		</p>
		<p class="c15">
			<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="c83">&ldquo;siguiente_url&rdquo;</span><span>: &ldquo;clientes.php&rdquo;,</span>
		</p>
		<p class="c15">
			<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span class="c83">&ldquo;auth_token&rdquo;</span><span>: &ldquo;912ec803b2ce49e4a541068d495ab570&rdquo;</span>
		</p>
		<p class="c15">
			<span>}</span>
		</p>
	</td>
</tr>
</tbody>
</table>

					







				</div>


			</td>
		</tr>

	</table>	

	<script type="text/javascript">
		var a = new ApiMethod();
		a.nombre = "api/hola/say_hola";
		a.metodo = "GET";
		
		//a.render();
	</script>

</body>
</html>

<!--

 Nombre del metodo
 POST/GET
 Autorizacion
 Parametros obligatorios
 Parametros opcionales

 Ejemplo de entrada
 Ejemplo de salida

 MimeType de Salida


 -->