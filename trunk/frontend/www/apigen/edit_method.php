<?php


	require_once("../../server/bootstrap.php");
   	$mid = $_GET["m"];


?><html>
<head>
	<link rel="stylesheet" type="text/css" href="../media/final_api.css">
	<script type="text/javascript" src="http://api.caffeina.mx/jquery/jquery-1.4.2.min.js"></script>
	<title></title>
	<script type="text/javascript">
		
                function validarCampos()
                {
                    if($.trim($("#n_metodo").val())=="")
                    {
                        alert("Falta el nombre del metodo");
                        return;
                    }
                    else if($.trim($("#n_metodo").val()).search(/['"]+/)>=0)
                    {
                        alert("El nombre contiene comillas simples o dobles, use en su lugar ` ");
                        return;
                    }
                    if($.trim($("#sub_metodo").val())=="")
                    {
                        alert("Falta el subtitulo");
                        return;
                    }
                    else if($.trim($("#sub_metodo").val()).search(/['"]+/)>=0)
                    {
                        alert("El subtitulo contiene comillas simples o dobles, use en su lugar ` ");
                        return;
                    }
                    if($.trim($("#desc_metodo").val())=="")
                    {
                        alert("Falta la descripcion del metodo");
                        return;
                    }
                    else if($.trim($("#desc_metodo").val()).search(/['"]+/)>=0)
                    {
                        alert("La descripcion contiene comillas simples o dobles, use en su lugar ` ");
                        return;
                    }
                    if($.trim($("#auth_grupo_metodo").val())=="")
                    {
                        alert("Falta el grupo del metodo");
                        return;
                    }
                    else if($.trim($("#auth_grupo_metodo").val()).search(/\D+/)>=0)
                    {
                        alert("El grupo solo puede ser un numero");
                        return;
                    }
                    for(var i = 0; i<= param_count; i++)
                        {
                            if($.trim($("#args_nombre_"+i).val())=="")
                            {
                                alert("Falta el nombre del argumento "+(i+1));
                                return;
                            }
                            else if($.trim($("#args_nombre_"+i).val()).search(/['"]+/)>=0)
                            {
                                alert("El nombre del argumento "+ (i+1) +" contiene comillas simples o dobles, use en su lugar ` ");
                                return;
                            }
                            if($.trim($("#args_desc_"+i).val()).search(/'+/)>=0)
                            {
                                alert("La descripcion del argumento "+ (i+1) +" contiene comillas simples, use en su lugar ` ");
                                return;
                            }
                            if($.trim($("#args_default_"+i).val()).search(/'+/)>=0)
                            {
                                alert("El valor default del argumento "+ (i+1) +" contiene comillas simples, use en su lugar ` ");
                                return;
                            }
                        }
                    for(var i = 0; i<= response_count; i++)
                        {
                            if($.trim($("#response_nombre_"+i).val())=="")
                            {
                                alert("Falta el nombre de la respuesta "+(i+1));
                                return;
                            }
                            else if($.trim($("#response_nombre_"+i).val()).search(/['"]+/)>=0)
                            {
                                alert("El nombre de la respuesta "+ (i+1) +" contiene comillas simples o dobles, use en su lugar ` ");
                                return;
                            }
                            if($.trim($("#response_desc_"+i).val()).search(/'+/)>=0)
                            {
                                alert("La descripcion de la respuesta "+ (i+1) +" contiene comillas simples, use en su lugar ` ");
                                return;
                            }
                        }
                    if($.trim($("#ejemplo_peticion").val()).search(/'+/)>=0)
                    {
                        alert("El ejemplo de la peticion contiene comillas simples, use en su lugar ` ");
                        return;
                    }
                    if($.trim($("#ejemplo_respuesta").val()).search(/'+/)>=0)
                    {
                        alert("El ejemplo de la respuesta contiene comillas simples, use en su lugar ` ");
                        return;
                    }
                    $("#form_insercion").submit()
                }
                
                var param_count = -1;

		function addParam(){
			param_count ++;
			document.getElementById("numero_argumentos").value=param_count+1;
			var html = '';
			
			html += 	'<tr valign="top" style="border-bottom:1px solid #ddd;">';
			html += 	'<td style="border:1px solid #ddd;">';
			html += 		'<input type="text" name="nombre_argumento_'+param_count+'" placeholder="nombre" id="args_nombre_'+param_count+'" >';
			html += 	'</td>';
			
			html += 	'<td style="border:1px solid #ddd;">';
			html += 		'<select name="tipo_argumento_'+param_count+'" id="args_tipo_'+param_count+'" onChange="m.render()">';
			html += 			'<option value="string">string</option>';
			html += 			'<option value="bool">bool</option>';
			html += 			'<option value="int">int</option>';
			html += 			'<option value="float">float</option>';
			html += 			'<option value="json">json</option>';
			html += 		'</select>';
			html += 	'</td>';
			
			html += 	'<td style="border:1px solid #ddd;">';
			html += 		'<select name="ahuevo_'+param_count+'" id="args_ahuevo_'+param_count+'" onChange="m.render()">';
			html += 			'<option value=1>Obligatorio</option>';
			html += 			'<option value=0>Opcional</option>';
			html += 		'</select>';
			html += 	'</td>'; 
			
			html += 	'<td style="border:1px solid #ddd;">';
			html += 		'<textarea rows=6 name="descripcion_argumento_'+param_count+'""placeholder="descripcion" id="args_desc_'+param_count+'" ></textarea>';
			html += 	'</td>';
			
			html += 	'<td style="border:1px solid #ddd;"><input name="default_'+param_count+'" id="args_default_'+param_count+'" value="null" ></td>';
			html +=		'<td style="border:1px solid #ddd;">Borrar<input type="checkbox" name="borrar_argumento_'+param_count+'"></td>';
			html += 	'</tr>'	;
					
			$("#param_space").append(html);

			m.render();
		}
		
		function addParamEdit(nombre,tipo,ahuevo,descripcion,Default){
			param_count ++;
			document.getElementById("numero_argumentos").value=param_count+1;
			var html = '';
			
			html += 	'<tr valign=top style="border:1px solid #ddd;">';
			html += 	'<td>';
			html += 		'<input type="text" name="nombre_argumento_'+param_count+'" value="'+nombre+'" id="args_nombre_'+param_count+'" >';
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
			html += 		'<textarea rows=6 name="descripcion_argumento_'+param_count+'""placeholder="descripcion" id="args_desc_'+param_count+'" >'+descripcion+'</textarea>';
			html += 	'</td>';
			
			html += 	'<td><input name="default_'+param_count+'" id="args_default_'+param_count+'" value="'+Default+'" ></td>';
			html +=		'<td>Borrar<input type="checkbox" name="borrar_argumento_'+param_count+'"></td>';
			html += 	'</tr>'	;
					
			$("#param_space").append(html);
			
		}

		var response_count = -1;
		function addResponse(){
			response_count++;
			document.getElementById("numero_respuestas").value=response_count+1;
			var html = '';
			
			html += 	'<tr valign=top style="border:1px solid #ddd;">';
				html += 	'<td>';
				html += 		'<input type="text" name="nombre_respuesta_'+response_count+'" placeholder="nombre" id="response_nombre_'+response_count+'" >';
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
				html += 		'<textarea name="descripcion_respuesta_'+response_count+'" placeholder="descripcion" id="response_desc_'+response_count+'" ></textarea>';
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
			
			html += 	'<tr valign=top style="border:1px solid #ddd;">';
				html += 	'<td>';
				html += 		'<input type="text" name="nombre_respuesta_'+response_count+'" value="'+nombre+'" id="response_nombre_'+response_count+'" >';
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
				html += 		'<textarea name="descripcion_respuesta_'+response_count+'" placeholder="Descripcion" id="response_desc_'+response_count+'" >'+descripcion+'</textarea>';
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
			this.html		= false;
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
				
				$("#preview_regresa_html").html(  this.html ? "Si" : "No" );

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
	$info_metodo=mysql_fetch_assoc($r) or die(mysql_error());
	
	$query_argumentos="select * from argumento where id_metodo=".$mid;
	$r=mysql_query($query_argumentos) or die(mysql_error());
	$i=0;
	$argumentos=-1;
	while($row=mysql_fetch_assoc($r))
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
	while($row=mysql_fetch_assoc($r))
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
		<tr >
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
										$sql="select * from clasificacion where id_proyecto = ".$_GET["project"];
										$result=mysql_query($sql);
										while($row=mysql_fetch_assoc($result))
										{
											if($row["id_clasificacion"]==(int)$info_metodo["id_clasificacion"])
												echo 
											'
												<option value="'.$row["id_clasificacion"].'" selected>'.$row["nombre"].'</option>
											';
											else
											echo 
											'
												<option value="'.$row["id_clasificacion"].'">'.$row["nombre"].'</option>
											';
										}
										
									?>
								</select>
				     		</td>
				     	</tr>
				     	<tr>
				     		<td>Nombre</td>
				     		<td>
								<input type="text" name="nombre_metodo" id="n_metodo" style="width:100%" value="<?php echo $info_metodo["nombre"];?>" onKeyUp="m.nombre = this.value; m.render()">
				     		</td>
				     	</tr>
						<tr>
				     		<td>Subtitulo</td>
				     		<td>
								<input type="text" name="subtitulo" id="sub_metodo" style="width:100%" value="<?php echo $info_metodo["subtitulo"];?>" onKeyUp="m.subtitulo = this.value; m.render()">
				     		</td>
				     	</tr>
				     	<tr>
				     		<td>Descripcion</td>
				     		<td>
								<textarea 
									rows=12 
									name="descripcion_metodo" 
									id="desc_metodo" 
									style="width:100%" 
									onKeyUp="m.desc = this.value; m.render()"><?php 
										echo str_replace("<br>","\n", $info_metodo["descripcion"])
									?></textarea>
				     		</td>
				     	</tr>				     	
				     	<tr>
				     		<td>Method</td>
				     		<td>
				     		<select name="tipo_metodo" id="http_metodo" onChange="m.http = this.value; m.render()">
							<?php
							    if($info_metodo["tipo"]=="GET")
								echo '<option value="GET" selected>GET</option> ';
								else
								echo '<option value="GET">GET</option> ';
								if($info_metodo["tipo"]=="POST")
								echo '<option value="POST" selected>POST</option> ';
								else
								echo '<option value="POST">POST</option> ';
								if($info_metodo["tipo"]=="POST/GET")
								echo '<option value="POST/GET" selected>POST/GET</option> ';
								else
								echo '<option value="POST/GET">POST/GET</option> ';
							?>
							</select>
							</td>
				     	</tr>
						<tr>
							<td>Regresa HTML</td>
							<td><input type="checkbox" name="regresa_html" value="false" <?php if($info_metodo["regresa_html"]) echo "checked"?> id="html_metodo" onChange="m.html = !m.html; m.render()"></td>
						</tr>
				     	<tr>
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Autenticacion</h3></td>
				     	</tr>	
				     	
				     	<tr><td >Sesion Valida</td>		
				     	<td ><input type="checkbox" name="sesion_valida" value="true" <?php if($info_metodo["sesion_valida"]) echo "checked"?> id="auth_session_metodo" onChange="m.auth.sesion = !m.auth.sesion; m.render()"> </td>
				     	</tr>
				     	
				     	<tr><td >Grupo</td>		
				     	<td ><input type="text" name="grupo" value="<?php echo $info_metodo["grupo"];?>" id="auth_grupo_metodo" onKeyUp="m.auth.grupo = this.value; m.render()"></td>
				     	</tr>
				     	
				     	<tr><td >Permiso</td>		
				     	<td ><input type="text" disabled ></td>
				     	</tr>
				     		
				     	
				     	<tr>
				     		
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;">
								<h3 style="color: white;">Argumentos <a onClick='addParam()' style="color: white;">[+]</a></h3></td>
				     	</tr>
						<tr>
				     		<td colspan="2">
								<table id="param_space">
								</table>
				     		</td>
				     	</tr>				     	
				     	<tr>
				     		
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Respuesta <a onClick='addResponse()' style="color: white;">[+]</a></h3></td>
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
				     		<td colspan="2">
								<textarea 
								rows=12 
								style="width:100%;<?php
								
									try{
										$a = json_decode($info_metodo["ejemplo_peticion"]);
									}catch(Exception $e){
										echo  "box-shadow: 0 0 5px 2px #f00;  -webkit-box-shadow: 0 0 5px 2px #f00;  -moz-box-shadow: 0 0 5px 2px #f00;";										
									}

									if(is_null($a)){
										echo  "box-shadow: 0 0 5px 2px #f00;  -webkit-box-shadow: 0 0 5px 2px #f00;  -moz-box-shadow: 0 0 5px 2px #f00;";
									}
									
								?>" 
								name="ejemplo_peticion" 
								id="entrada_metodo" 
								onKeyUp="m.entrada = this.value; m.render();"><?php echo $info_metodo["ejemplo_peticion"];?></textarea>
							</td>
				     	</tr>
				     	<tr>
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Ejemplo Respuesta</h3></td>
				     	</tr>
				     	<tr>

				     		<td colspan="2">
								<textarea
								 rows=12
								 style="width:100%; <?php

									try{
										$a = json_decode($info_metodo["ejemplo_respuesta"]);
									}catch(Exception $e){
										echo  "box-shadow: 0 0 5px 2px #f00;  -webkit-box-shadow: 0 0 5px 2px #f00;  -moz-box-shadow: 0 0 5px 2px #f00;";										
									}

									if(is_null($a)){
										echo  "box-shadow: 0 0 5px 2px #f00;  -webkit-box-shadow: 0 0 5px 2px #f00;  -moz-box-shadow: 0 0 5px 2px #f00;";
									}

								?>"
								 name="ejemplo_respuesta"
								 id="salida_metodo"
								 onKeyUp="m.salida = this.value; m.render()"><?php echo $info_metodo["ejemplo_respuesta"];?></textarea></td>
				     	</tr>
				     	

				     </table>	

			</td>

		</tr>

	</table>	
	<input type="button" value="Editar" onClick="validarCampos();">
	<input type="hidden" name="numero_argumentos" id="numero_argumentos" value=0>
	<input type="hidden" name="numero_respuestas" id="numero_respuestas" value=0>
	<input type="hidden" name="id_metodo" id="id_metodo" value=<?php echo $mid;?>>
        <input type="hidden" name="id_proyecto" id="id_proyecto" value="<?php echo $_GET["project"]?>">
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
		m.html = document.getElementById('html_metodo').value;
		
	<?php
		if($argumentos!=-1)
		for($i=0;$i<count($argumentos);$i++)
		{
			echo "addParamEdit('"
			. $argumentos[$i]["nombre"] 
			. "','"
			.$argumentos[$i]["tipo"]
			."',"
			.htmlspecialchars(str_replace("\n","<br>",$argumentos[$i]["ahuevo"]))
			.",'"
			. preg_replace('/[^(\x20-\x7F)]*/','', $argumentos[$i]["descripcion"] )
			."','"
			.$argumentos[$i]["defaults"]."');\n";
		}
		if($respuestas!=-1)
		for($i=0;$i<count($respuestas);$i++)
		{
			echo "addResponseEdit('".$respuestas[$i]["nombre"]."','".$respuestas[$i]["tipo"]."',\"". htmlentities(preg_replace('/[^(\x20-\x7F)]*/','',str_replace("\t","",str_replace("\n","\n",$respuestas[$i]["descripcion"]))))."\");\n"; 
		}
	?>
		m.render();
		//a.render();
	</script>
</body>
</html>
