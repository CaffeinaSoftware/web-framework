<?php 
require_once("../../server/bootstrap.php");
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="../css/final_api.css">
	<script type="text/javascript" src="http://api.caffeina.mx/jquery/jquery-1.4.2.min.js"></script>
	<title></title>
	<script type="text/javascript">
                
                function validarCampos()
                {
                    if($.trim($("#nombre_metodo").val())=="")
                    {
                        alert("Falta el nombre del metodo");
                        return;
                    }
                    else if($.trim($("#nombre_metodo").val()).search(/['"]+/)>=0)
                    {
                        alert("El nombre contiene comillas simples o dobles, use en su lugar ` ");
                        return;
                    }
                    if($.trim($("#subtitulo").val())=="")
                    {
                        alert("Falta el subtitulo");
                        return;
                    }
                    else if($.trim($("#subtitulo").val()).search(/['"]+/)>=0)
                    {
                        alert("El subtitulo contiene comillas simples o dobles, use en su lugar ` ");
                        return;
                    }
                    if($.trim($("#descripcion_metodo").val())=="")
                    {
                        alert("Falta la descripcion del metodo");
                        return;
                    }
                    else if($.trim($("#descripcion_metodo").val()).search(/['"]+/)>=0)
                    {
                        alert("La descripcion contiene comillas simples o dobles, use en su lugar ` ");
                        return;
                    }
                    if($.trim($("#grupo").val())=="")
                    {
                        alert("Falta el grupo del metodo");
                        return;
                    }
                    else if($.trim($("#grupo").val()).search(/\D+/)>=0)
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
			
			html += 	'<td><input name="default_'+param_count+'" id="args_default_'+param_count+'" placeholder="default" onKeyUp="m.render()"></td>';
			html += 	'</tr>'	;
					
			$("#param_space").append(html);

			m.render();
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
				
			html += 	'</tr>'	;
					
			$("#response_space").append(html);
			m.render();
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



	</script>
</head>
<body>
<?php if(isset($_GET["mensaje"])) echo $_GET["mensaje"];?>
<form id="form_insercion" method="POST" action="negocios.php">
	<table border=0 width="100%">
		<!-- <tr >
			<td valign=top>
				<!-- --------------------------------------------------------------------
						EDITOR
				     -------------------------------------------------------------------- -->
				<!--     <table border=0 width=100%> -->
				     	<tr>
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">General</h3></td>
				     	</tr> 
						<tr>
				     		<td>Clasificacion</td>
				     		<td>
								<select name="clasificacion_metodo" style="width:100%" >
									<?php
										if(isset($_GET["cat"]))
										$cat=$_GET["cat"];
										else
										$cat=-1;
										$sql="select * from clasificacion where id_proyecto=".$_GET["project"];
										$result=mysql_query($sql);
										while($row=mysql_fetch_assoc($result))
										{
											if($cat==$row["id_clasificacion"])
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
								<input type="text" name="nombre_metodo" id="nombre_metodo" style="width:100%" onKeyUp="m.nombre = this.value; m.render()" >
				     		</td>
				     	</tr>
						<tr>
				     		<td>Subtitulo</td>
				     		<td>
								<input type="text" name="subtitulo" id="subtitulo" style="width:100%" onKeyUp="m.subtitulo = this.value; m.render()" >
				     		</td>
				     	</tr>
				     	<tr>
				     		<td>Descripcion</td>
				     		<td>
								<textarea name="descripcion_metodo" id="descripcion_metodo" style="width:100%" onKeyUp="m.desc = this.value; m.render()"></textarea>
				     		</td>
				     	</tr>				     	
				     	<tr>
				     		<td>Method</td>
				     		<td>
				     		<select name="tipo_metodo" onChange="m.http = this.value; m.render()">
								<option value="GET">GET</option>
								<option value="POST">POST</option>
								<option value="POST/GET">POST/GET</option>
							</select>
							</td>
				     	</tr>
						<tr>
							<td>Regresa HTML</td>
							<td><input type="checkbox" name="regresa_html" value="false" onChange="m.html = !m.html; m.render()"></td>
						</tr>
				     	<tr>
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Autenticacion</h3></td>
				     	</tr>	
				     	
				     	<tr><td >Sesion Valida</td>		
				     	<td ><input type="checkbox" name="sesion_valida" value="true" checked onChange="m.auth.sesion = !m.auth.sesion; m.render()"> </td>
				     	</tr>
				     	
				     	<tr><td >Grupo</td>		
				     	<td ><input type="text" name="grupo" id="grupo" onKeyUp="m.auth.grupo = this.value; m.render()"></td>
				     	</tr>
				     	
				     	<tr><td >Permiso</td>		
				     	<td ><input type="text" disabled ></td>
				     	</tr>
				     		
				     	
				     	<tr>
				     		
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Argumentos <a onClick='addParam()' style="color: white;" >[+]</a></h3></td>
				     	</tr>
						<tr>
				     		<td colspan="2">
								<table id="param_space">
								</table>
				     		</td>
				     	</tr>				     	
				     	<tr>
				     		
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Respuesta <a onClick='addResponse()' style="color: white;" >[+]</a></h3></td>
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
				     		<td colspan="2"><textarea style="width:100%" name="ejemplo_peticion" id="ejemplo_peticion" onKeyUp="m.entrada = this.value; m.render()"></textarea></td>
				     	</tr>
				     	<tr>
				     		<td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Ejemplo Respuesta</h3></td>
				     	</tr>
				     	<tr>

				     		<td colspan="2"><textarea style="width:100%" name="ejemplo_respuesta" id="ejemplo_respuesta" onKeyUp="m.salida = this.value; m.render()"></textarea></td>
				     	</tr>
				     	
<!--
				     </table>	

			</td>
                        
			<td width="50%" valign=top>
				<!-- --------------------------------------------------------------------
						PREVIEW
				     -------------------------------------------------------------------- -->
                                                <!--
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
<br>
<h4 class="c15"><span>Regresa HTML</span></h4><span class="c7" id="preview_regresa_html">No</span>
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
<!--
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
-->

	</table>	
	<input type="button" value="Insertar" onClick="validarCampos();">
	<input type="hidden" name="numero_argumentos" id="numero_argumentos" value=0>
	<input type="hidden" name="numero_respuestas" id="numero_respuestas" value=0>
        <input type="hidden" name="id_proyecto" id="id_proyecto" value="<?php echo $_GET["project"] ?>"></input>
</form>
	<script type="text/javascript">
		var a = new ApiMethod();
		a.nombre = "api/hola/say_hola";
		a.metodo = "GET";
		
		//a.render();
	</script>

</body>
</html>
