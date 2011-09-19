<?php


	require_once("../../server/bootstrap.php");
   $mid = $_GET["m"];


?><html>
<head>
	<link rel="stylesheet" type="text/css" href="../css/final_api.css">
	<script type="text/javascript" src="http://api.caffeina.mx/jquery/jquery-1.4.2.min.js"></script>
	<title></title>
	<script type="text/javascript">
		var param_count = -1;

		function addParam(){
			param_count ++;
			document.getElementById("numero_argumentos").value=param_count+1;
			var html = '';
			
			html += 	'<tr valign=top>';
			html += 	'<td>';
			html += 		'<input type="text" name="nombre_argumento_'+param_count+'" placeholder="nombre" id="args_nombre_'+param_count+'" onKeyUp="m.render()">';
			html += 	'</td>';
			
			html += 	'<td>';
			html += 		'<select name="tipo_argumento_'+param_count+'" id="args_tipo_'+param_count+'" onChange="m.render()">';
			html += 			'<option value="string">string</option>';
			html += 			'<option value="bool">bool</option>';
			html += 			'<option value="int">int</option>';
			html += 			'<option value="float">float</option>';
			html += 			'<option value="json">json</option>';
			html += 		'</select>';
			html += 	'</td>';
			
			html += 	'<td>';
			html += 		'<select name="ahuevo_'+param_count+'" id="args_ahuevo_'+param_count+'" onChange="m.render()">';
			html += 			'<option value=1>Obligatorio</option>';
			html += 			'<option value=0>Opcional</option>';
			html += 		'</select>';
			html += 	'</td>'; 
			
			html += 	'<td>';
			html += 		'<textarea name="descripcion_argumento_'+param_count+'""placeholder="descripcion" id="args_desc_'+param_count+'" onKeyUp="m.render()"></textarea>';
			html += 	'</td>';
			
			html += 	'<td><input name="default_'+param_count+'" id="args_default_'+param_count+'" value="null" onKeyUp="m.render()"></td>';
			html +=		'<td>Borrar<input type="checkbox" name="borrar_argumento_'+param_count+'"></td>';
			html += 	'</tr>'	;
					
			$("#param_space").append(html);

			m.render();
		}
		
		function addParamEdit(nombre,tipo,ahuevo,descripcion,Default){
			param_count ++;
			document.getElementById("numero_argumentos").value=param_count+1;
			var html = '';
			
			html += 	'<tr valign=top>';
			html += 	'<td>';
			html += 		'<input type="text" name="nombre_argumento_'+param_count+'" value="'+nombre+'" id="args_nombre_'+param_count+'" onKeyUp="m.render()">';
			html += 	'</td>';
			
			html += 	'<td>';
			html += 		'<select name="tipo_argumento_'+param_count+'" id="args_tipo_'+param_count+'" onChange="m.render()">';
			if(tipo=="string")
			html += 			'<option value="string" selected>string</option>';
			else
			html += 			'<option value="string">string</option>';
			if(tipo=="bool")
			html += 			'<option value="bool" selected>bool</option>';
			else
			html += 			'<option value="bool">bool</option>';
			if(tipo=="int")
			html += 			'<option value="int" selected>int</option>';
			else
			html += 			'<option value="int">int</option>';
			if(tipo=="float")
			html += 			'<option value="float" selected>float</option>';
			else
			html += 			'<option value="float">float</option>';
			if(tipo=="json")
			html += 			'<option value="json" selected>json</option>';
			else
			html += 			'<option value="json">json</option>';
			html += 		'</select>';
			html += 	'</td>';
			
			html += 	'<td>';
			html += 		'<select name="ahuevo_'+param_count+'" id="args_ahuevo_'+param_count+'" onChange="m.render()">';
			if(ahuevo)
			{
			html += 			'<option value=1 selected>Obligatorio</option>';
			html += 			'<option value=0>Opcional</option>';
			}
			else
			{
			html += 			'<option value=1>Obligatorio</option>';
			html += 			'<option value=0 selected>Opcional</option>';
			}
			html += 		'</select>';
			html += 	'</td>'; 
			
			html += 	'<td>';
			html += 		'<textarea name="descripcion_argumento_'+param_count+'""placeholder="descripcion" id="args_desc_'+param_count+'" onKeyUp="m.render()">'+descripcion+'</textarea>';
			html += 	'</td>';
			
			html += 	'<td><input name="default_'+param_count+'" id="args_default_'+param_count+'" value="'+Default+'" onKeyUp="m.render()"></td>';
			html +=		'<td>Borrar<input type="checkbox" name="borrar_argumento_'+param_count+'"></td>';
			html += 	'</tr>'	;
					
			$("#param_space").append(html);
			
		}

		var response_count = -1;
		function addResponse(){
			response_count++;
			document.getElementById("numero_respuestas").value=response_count+1;
			var html = '';
			
			html += 	'<tr valign=top>';
				html += 	'<td>';
				html += 		'<input type="text" name="nombre_respuesta_'+response_count+'" placeholder="nombre" id="response_nombre_'+response_count+'" onKeyUp="m.render()">';
				html += 	'</td>';
				
				html += 	'<td>';
				html += 		'<select name="tipo_respuesta_'+response_count+'" id="response_tipo_'+response_count+'" onChange="m.render()">';
				html += 			'<option value="string">string</option>';
				html += 			'<option value="bool">bool</option>';
				html += 			'<option value="int">int</option>';
				html += 			'<option value="float">float</option>';
				html += 			'<option value="json">json</option>';
				html += 		'</select>';
				html += 	'</td>';
				
				html += 	'<td>';
				html += 		'<textarea name="descripcion_respuesta_'+response_count+'" placeholder="descripcion" id="response_desc_'+response_count+'" onKeyUp="m.render()"></textarea>';
				html += 	'</td>';
				
				html +=		'<td>';
				html +=			'Borrar<input type="checkbox" name="borrar_respuesta_'+response_count+'">';
				html +=		'</td>';
				
			html += 	'</tr>'	;
					
			$("#response_space").append(html);
			m.render();
		}
		
		function addResponseEdit(nombre,tipo,descripcion){
			response_count++;
			document.getElementById("numero_respuestas").value=response_count+1;
			var html = '';
			
			html += 	'<tr valign=top>';
				html += 	'<td>';
				html += 		'<input type="text" name="nombre_respuesta_'+response_count+'" value="'+nombre+'" id="response_nombre_'+response_count+'" onKeyUp="m.render()">';
				html += 	'</td>';
				
				html += 	'<td>';
				html += 		'<select name="tipo_respuesta_'+response_count+'" id="response_tipo_'+response_count+'" onChange="m.render()">';
				if(tipo=="string")
				html += 			'<option value="string" selected>string</option>';
				else
				html += 			'<option value="string">string</option>';
				if(tipo=="bool")
				html += 			'<option value="bool" selected>bool</option>';
				else
				html += 			'<option value="bool">bool</option>';
				if(tipo=="int")
				html += 			'<option value="int" selected>int</option>';
				else
				html += 			'<option value="int">int</option>';
				if(tipo=="float")
				html += 			'<option value="float" selected>float</option>';
				else
				html += 			'<option value="float">float</option>';
				if(tipo=="json")
				html += 			'<option value="json" selected>json</option>';
				else
				html += 			'<option value="json">json</option>';
				html += 		'</select>';
				html += 	'</td>';
				
				html += 	'<td>';
				html += 		'<textarea name="descripcion_respuesta_'+response_count+'" placeholder="descripcion" id="response_desc_'+response_count+'" onKeyUp="m.render()">'+descripcion+'</textarea>';
				html += 	'</td>';
				
				html +=		'<td>';
				html +=			'Borrar<input type="checkbox" name="borrar_respuesta_'+response_count+'">';
				html +=		'</td>';
				
			html += 	'</tr>'	;
					
			$("#response_space").append(html);
		}

		var ApiMethod = function(){
			
			this.nombre 	= "";
			this.subtitulo	= "";
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
				$("#preview_subtitle").html(this.subtitulo);
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
						preview_arg_table += '<span class="c7">' +  $( "#args_nombre_"+a ).val() + '</span>';
						preview_arg_table += '</p></td><td class="c61"><p class="c3">';
						preview_arg_table += '<span class="c7">' + $( "#args_desc_"+a ).val() +'</span>';
						preview_arg_table += '</p></td><td class="c96"><p class="c3">';
						preview_arg_table += '<span class="c7">' + $( "#args_ahuevo_"+a ).val() +'</span>';
						preview_arg_table += '</p></td><td class="c82"><p class="c3">';
						preview_arg_table += '<span class="c7">'+ $( "#args_tipo_"+a ).val() + '</span>';
						preview_arg_table += '</p></td></tr>';
				}
				$("#preview_arg_table").html(preview_arg_table);



				var preview_resp_table = "";
				for( a = 0; a <= response_count ; a ++ )
				{
						preview_resp_table += '<tr><td class="c135"><p class="c3">';
						preview_resp_table += '<span class="c7">' +  $( "#response_nombre_"+a ).val() + '</span>';
						preview_resp_table += '</p></td><td class="c61"><p class="c3">';
						preview_resp_table += '<span class="c7">' + $( "#response_desc_"+a ).val() +'</span>';
						preview_resp_table += '</p></td><td class="c96"><p class="c3">';
						preview_resp_table += '<span class="c7">' + $( "#response_tipo_"+a ).val() +'</span>';
						preview_resp_table += '</p></td></tr>';
				}
				$("#preview_resp_table").html(preview_resp_table);

				
			}
		};

		

		var m = new ApiMethod();

        function cambioMetodo(id)
		{
		     window.location="cambio_metodo.php?id="+id;
		}
		
		

	</script>
</head>
<body>
<?php
   if($mid==-1)
	return;
	
	$info_metodo="select * from metodo where id_metodo=".$mid;
	$r=mysql_query($info_metodo) or die(mysql_error());
	$info_metodo=mysql_fetch_row($r) or die(mysql_error());
	
	$query_argumentos="select * from argumento where id_metodo=".$mid;
	$r=mysql_query($query_argumentos) or die(mysql_error());
	$i=0;
	$argumentos=-1;
	while($row=mysql_fetch_row($r))
	{
		if($argumentos==-1)
		   unset($argumentos);
	   $argumentos[$i]=$row;
	   $i++;
	}
	$respuestas=-1;
	$query_respuestas="select * from respuesta where id_metodo=".$mid;
	$r=mysql_query($query_respuestas) or die(mysql_error());
	$i=0;
	while($row=mysql_fetch_row($r))
	{
	   if($respuestas==-1)
		   unset($respuestas);
	   $respuestas[$i]=$row;
	   $i++;
	}
	
	if(isset($_GET["mensaje"])) echo $_GET["mensaje"];
	?>
<form id="form_insercion" method="POST" action="negocios_editar.php">
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
				     		<td>Clasificacion</td>
				     		<td>
								<select name="clasificacion_metodo" style="width:100%" >
									<?php
										$sql="select * from clasificacion";
										$result=mysql_query($sql);
										while($row=mysql_fetch_row($result))
										{
											if($row[0]==(int)$info_metodo[1])
												echo 
											'
												<option value="'.$row[0].'" selected>'.$row[1].'</option>
											';
											else
											echo 
											'
												<option value="'.$row[0].'">'.$row[1].'</option>
											';
										}
										
									?>
								</select>
				     		</td>
				     	</tr>
				     	<tr>
				     		<td>Nombre</td>
				     		<td>
								<input type="text" name="nombre_metodo" id="n_metodo" style="width:100%" value="<?php echo $info_metodo[2];?>" onKeyUp="m.nombre = this.value; m.render()">
				     		</td>
				     	</tr>
						<tr>
				     		<td>Subtitulo</td>
				     		<td>
								<input type="text" name="subtitulo" id="sub_metodo" style="width:100%" value="<?php echo $info_metodo[9];?>" onKeyUp="m.subtitulo = this.value; m.render()">
				     		</td>
				     	</tr>
				     	<tr>
				     		<td>Descripcion</td>
				     		<td>
								<textarea name="descripcion_metodo" id="desc_metodo" style="width:100%" onKeyUp="m.desc = this.value; m.render()"><?php echo $info_metodo[8];?></textarea>
				     		</td>
				     	</tr>				     	
				     	<tr>
				     		<td>Method</td>
				     		<td>
				     		<select name="tipo_metodo" id="http_metodo" onChange="m.http = this.value; m.render()">
							<?php
							    if($info_metodo[3]=="GET")
								echo '
								<option value="GET" selected>GET</option> ';
								else
								echo '
								<option value="GET">GET</option> ';
								if($info_metodo[3]=="POST")
								echo '
								<option value="POST" selected>POST</option> ';
								else
								echo '
								<option value="POST">POST</option> ';
								if($info_metodo[3]=="POST/GET")
								echo '
								<option value="POST/GET" selected>POST/GET</option> ';
								else
								echo '
								<option value="POST/GET">POST/GET</option> ';
							?>
							</select>
							</td>
				     	</tr>
				     	<tr>
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Autenticacion</h3></td>
				     	</tr>	
				     	
				     	<tr><td >Sesion Valida</td>		
				     	<td ><input type="checkbox" name="sesion_valida" value="true" <?php if($info_metodo[4]) echo "checked"?> id="auth_session_metodo" onChange="m.auth.sesion = !m.auth.sesion; m.render()"> </td>
				     	</tr>
				     	
				     	<tr><td >Grupo</td>		
				     	<td ><input type="text" name="grupo" value="<?php echo $info_metodo[5];?>" id="auth_grupo_metodo" onKeyUp="m.auth.grupo = this.value; m.render()"></td>
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
				     		<td colspan="2"><textarea style="width:100%" name="ejemplo_peticion" id="entrada_metodo" onKeyUp="m.entrada = this.value; m.render()"><?php echo $info_metodo[6];?></textarea></td>
				     	</tr>
				     	<tr>
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Ejemplo Respuesta</h3></td>
				     	</tr>
				     	<tr>

				     		<td colspan="2"><textarea style="width:100%" name="ejemplo_respuesta" id="salida_metodo" onKeyUp="m.salida = this.value; m.render()"><?php echo $info_metodo[7];?></textarea></td>
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
	<input type=submit value="Editar">
	<input type="hidden" name="numero_argumentos" id="numero_argumentos" value=0>
	<input type="hidden" name="numero_respuestas" id="numero_respuestas" value=0>
	<input type="hidden" name="id_metodo" id="id_metodo" value=<?php echo $mid;?>>
</form>
	<script type="text/javascript">
		var a = new ApiMethod();
		a.nombre = "api/hola/say_hola";
		a.metodo = "GET";
		m.nombre = document.getElementById('n_metodo').value;
		m.subtitulo = document.getElementById('sub_metodo').value;
		m.desc = document.getElementById('desc_metodo').value;
		m.http = document.getElementById('http_metodo').value;
		m.auth.session = document.getElementById('auth_session_metodo').value;
		m.auth.grupo = document.getElementById('auth_grupo_metodo').value;
		m.entrada = document.getElementById('entrada_metodo').value;
		m.salida = document.getElementById('salida_metodo').value;
		
	<?php
		if($argumentos!=-1)
		for($i=0;$i<count($argumentos);$i++)
		{
				echo "addParamEdit('".$argumentos[$i][2]."','".$argumentos[$i][5]."',".$argumentos[$i][4].",'".$argumentos[$i][3]."','".$argumentos[$i][6]."');";
		}
		if($respuestas!=-1)
		for($i=0;$i<count($respuestas);$i++)
		{
				echo "addResponseEdit('".$respuestas[$i][2]."','".$respuestas[$i][4]."','".$respuestas[$i][3]."');"; 
		}
	?>
		m.render();
		//a.render();
	</script>
</body>
</html>
