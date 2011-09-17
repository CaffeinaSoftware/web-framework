<html>
<head>
	<link rel="stylesheet" type="text/css" href="../css/final_api.css">
	<script type="text/javascript" src="http://api.caffeina.mx/jquery/jquery-1.4.2.min.js"></script>
	<title></title>
	<script type="text/javascript">
		var param_count = -1;

		function addParam(){
			param_count ++;

			var html = '';
			
			html += 	'<tr valign=top>';
			html += 	'<td>';
			html += 		'<input type="text" placeholder="nombre" id="args_nombre_'+param_count+'" onKeyUp="m.render()">';
			html += 	'</td>';
			html += 	'<td>';
			html += 		'<textarea placeholder="descripcion" id="args_desc_'+param_count+'" onKeyUp="m.render()"></textarea>';
			html += 	'</td>';

			html += 	'<td>';
			html += 		'<select id="args_ahuevo_'+param_count+'" onChange="m.render()">';
			html += 			'<option>Obligatorio</option>';
			html += 			'<option>Opcional</option>';
			html += 		'</select>';
			html += 	'</td>'; 

			html += 	'<td>';
			html += 		'<select id="args_tipo_'+param_count+'" onChange="m.render()">';
			html += 			'<option>string</option>';
			html += 			'<option>bool</option>';
			html += 			'<option>int</option>';
			html += 			'<option>float</option>';
			html += 			'<option>json</option>';
			html += 		'</select>';
			html += 	'</td>';

			html += 	'<td><input id="args_default_'+param_count+'" placeholder="default" onKeyUp="m.render()"></td>';
			html += 	'</tr>'	;
					
			$("#param_space").append(html);

			m.render();
		}

		var response_count = -1;
		function addResponse(){
			response_count++;
			var html = '';
			
			html += 	'<tr valign=top>';
				html += 	'<td>';
				html += 		'<input type="text" placeholder="nombre" id="response_nombre_'+response_count+'" onKeyUp="m.render()">';
				html += 	'</td>';
				html += 	'<td>';
				html += 		'<textarea placeholder="descripcion" id="response_desc_'+response_count+'" onKeyUp="m.render()"></textarea>';
				html += 	'</td>';

				html += 	'<td>';
				html += 		'<select id="response_tipo_'+response_count+'" onChange="m.render()">';
				html += 			'<option>string</option>';
				html += 			'<option>bool</option>';
				html += 			'<option>int</option>';
				html += 			'<option>float</option>';
				html += 			'<option>json</option>';
				html += 		'</select>';
				html += 	'</td>';
			html += 	'</tr>'	;
					
			$("#response_space").append(html);
			m.render();
		}

		var ApiMethod = function(){
			
			this.nombre 	= "";
			this.metodo 	= "";
			this.http 		= "GET";
			this.desc 		= "";
			this.auth 		= {
				sesion  : true,
				grupo 	: null,
				permiso : null
			};

			this.params 	= [];
			this.response 	= [];
			this.entrada = "";
			this.salida	 = "";

			this.render = function(){
				$("#preview_nombre").html(this.http + " " + this.nombre);
				$("#preview_desc").html(this.desc);
				//clean the space
				

				$("#preview_auth_sesion").html(  this.auth.sesion ? "Si" : "No" );
				$("#preview_auth_grupo").html(  this.auth.grupo );
				$("#preview_auth_permiso").html(  this.nombre );

				$("#preview_respuesta").html(this.salida)
				$("#preview_peticion").html(this.entrada)
				

				var preview_arg_table = "";
				for( a = 0; a <= param_count ; a ++ )
				{
						preview_arg_table += '<tr><td class="c135"><p class="c3">';
						preview_arg_table += '<span class="c7">' +  $( "#args_nombre_"+param_count ).val() + '</span>';
						preview_arg_table += '</p></td><td class="c61"><p class="c3">';
						preview_arg_table += '<span class="c7">' + $( "#args_desc_"+param_count ).val() +'</span>';
						preview_arg_table += '</p></td><td class="c96"><p class="c3">';
						preview_arg_table += '<span class="c7">' + $( "#args_ahuevo_"+param_count ).val() +'</span>';
						preview_arg_table += '</p></td><td class="c82"><p class="c3">';
						preview_arg_table += '<span class="c7">'+ $( "#args_tipo_"+param_count ).val() + '</span>';
						preview_arg_table += '</p></td></tr>';
				}
				$("#preview_arg_table").html(preview_arg_table);



				var preview_resp_table = "";
				for( a = 0; a <= response_count ; a ++ )
				{
						preview_resp_table += '<tr><td class="c135"><p class="c3">';
						preview_resp_table += '<span class="c7">' +  $( "#response_nombre_"+param_count ).val() + '</span>';
						preview_resp_table += '</p></td><td class="c61"><p class="c3">';
						preview_resp_table += '<span class="c7">' + $( "#response_desc_"+param_count ).val() +'</span>';
						preview_resp_table += '</p></td><td class="c96"><p class="c3">';
						preview_resp_table += '<span class="c7">' + $( "#response_tipo_"+param_count ).val() +'</span>';
						preview_resp_table += '</p></td><td class="c82"><p class="c3">';
						preview_resp_table += '<span class="c7">'+ $( "#response_tipo_"+param_count ).val() + '</span>';
						preview_resp_table += '</p></td></tr>';
				}
				$("#preview_resp_table").html(preview_resp_table);

				
			}
		};



		var m = new ApiMethod();



	</script>
</head>
<body>


	<table border=0>
		<tr style="width:50%">
			<td valign=top>
				<!-- --------------------------------------------------------------------
						EDITOR
				     -------------------------------------------------------------------- -->
				     <table border=0 width=100%>
				     	<tr>
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">General</h3></td>
				     	</tr>
				     	<tr>
				     		<td>Nombre</td>
				     		<td>
								<input type="text" style="width:100%" onKeyUp="m.nombre = this.value; m.render()" >
				     		</td>
				     	</tr>
				     	<tr>
				     		<td>Descripcion</td>
				     		<td>
								<textarea style="width:100%" onKeyUp="m.desc = this.value; m.render()"></textarea>
				     		</td>
				     	</tr>				     	
				     	<tr>
				     		<td>Method</td>
				     		<td>
				     		<select onChange="m.http = this.value; m.render()">
								<option>GET</option>
								<option>POST</option>
								<option>POST/GET</option>
							</select>
							</td>
				     	</tr>
				     	<tr>
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Autenticacion</h3></td>
				     	</tr>	
				     	
				     	<tr><td >Sesion Valida</td>		
				     	<td ><input type="checkbox"  value="Si" checked onChange="m.auth.sesion = !m.auth.sesion; m.render()"> </td>
				     	</tr>
				     	
				     	<tr><td >Grupo</td>		
				     	<td ><input type="text" onKeyUp="m.auth.grupo = this.value; m.render()"></td>
				     	</tr>
				     	
				     	<tr><td >Permiso</td>		
				     	<td ><input type="text" disabled ></td>
				     	</tr>
				     		
				     	
				     	<tr>
				     		
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Argumentos <a onClick='addParam()'>[+]</a></h3></td>
				     	</tr>
						<tr>
				     		<td colspan="2">
								<table id="param_space">
								</table>
				     		</td>
				     	</tr>				     	
				     	<tr>
				     		
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Respuesta <a onClick='addResponse()'>[+]</a></h3></td>
				     	</tr>
				     	<tr>
							<td colspan="2">
								<table id="response_space">
								</table>
				     		</td>
				     	</tr>
				     	<tr>
				     		
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Ejemplo Peticion</h3></td>
				     	</tr>
				     	<tr>
				     		<td colspan="2"><textarea style="width:100%" onKeyUp="m.entrada = this.value; m.render()"></textarea></td>
				     	</tr>
				     	<tr>
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Ejemplo Respuesta</h3></td>
				     	</tr>
				     	<tr>

				     		<td colspan="2"><textarea style="width:100%" onKeyUp="m.salida = this.value; m.render()"></textarea></td>
				     	</tr>
				     	

				     </table>	

			</td>
			<td width="50%" valign=top>
				<!-- --------------------------------------------------------------------
						PREVIEW
				     -------------------------------------------------------------------- -->
				<div id="render_preview">

<table cellpadding="0" cellspacing="0" class="c0">
<tbody>
<tr class="c1">
	<td class="c25 c41">
		<p class="c3">
			<span class="c19" id="preview_nombre"></span>
		</p>
		<p class="c3">
			<span class="c31" id="preview_subtitle"></span>
		</p>
	</td>
</tr>
</tbody>
</table>
<p class="c22 c15" id="preview_desc"></p>
<h4 class="c15"><span>Autenticaci&oacute;n</span></h4>
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
			<span class="c7" id="preview_auth_sesion">Si</span>
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
			<span class="c7" id="preview_auth_grupo"></span>
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
			<span class="c8 c7" id="preview_auth_permiso"></span>
		</p>
	</td>
</tr>
</tbody>
</table>
<h4 class="c15"><a name="h.fmkw97a0ehmh"></a><span>Argumentos</span></h4>
<table cellpadding="0" cellspacing="0" class="c0">
	<tbody id="preview_arg_table">

		<!-- -------------------- -->

	</tbody>
</table>
<h4 class="c15"><a name="h.vzpf48a1ugdo"></a><span>Respuesta</span></h4>
<table cellpadding="0" cellspacing="0" class="c0">
	<tbody id="preview_resp_table">

	

	</tbody>
</table>
<h4 class="c15"><span>Ejemplo</span></h4>
<p class="c15">
	<span>Peticion</span>
</p>
<table cellpadding="0" cellspacing="0" class="c0">
<tbody>
<tr>
	<td class="c25 c13">
		<p class="code" id="preview_peticion"></p>
	</td>
</tr>
</tbody>
</table>

<p class="c15">
	<span>Respuesta</span>
</p>

<table cellpadding="0" cellspacing="0" class="c0">
<tbody>
<tr class="c1">
	<td class="c25 c13">
		<p class="code" id="preview_respuesta"></p>
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
