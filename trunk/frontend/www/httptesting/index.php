<?php

	require_once("../../server/bootstrap.php");

	if(!isset($_GET["url"])){
		
	}
        
        $t = null;
	
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<script type="text/javascript" charset="utf-8" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<title>HTTP Testing | Caffeina WebFramework</title>
	<link type="text/css" rel="stylesheet" href="../media/f.css"/>
	<link rel="stylesheet" href="http://api.caffeina.mx/facebox/facebox.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<script type="text/javascript" src="http://api.caffeina.mx/facebox/facebox.js" charset="utf-8"></script>
	
	<script type="text/javascript" charset="utf-8">
	
        function ProjectChange(val)
      {
          window.location = "index.php?project="+val;
      }
        
		$.fn.selectRange = function(start, end) {
		    return this.each(function() {
		        if (this.setSelectionRange) {
		            this.focus();
		            this.setSelectionRange(start, end);
		        } else if (this.createTextRange) {
		            var range = this.createTextRange();
		            range.collapse(true);
		            range.moveEnd('character', end);
		            range.moveStart('character', start);
		            range.select();
		        }
		    });
		};
	
		$.extend({
		  getUrlVars: function(){
		    var vars = [], hash;
		    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		    for(var i = 0; i < hashes.length; i++)
		    {
		      hash = hashes[i].split('=');
		      vars.push(hash[0]);
		      vars[hash[0]] = hash[1];
		    }
		    return vars;
		  },
		  getUrlVar: function(name){
		    return $.getUrlVars()[name];
		  }
		});
		
		
		
		var httptesting = {
			
			ajax : function(p, c){
				
				$.ajax({
					url: "api.php",
					type : "POST",
					data: p,
					success: function(a,b,parseOnReturn){
						var r;
						if(parseOnReturn !== true){
							if(c === undefined){
								alert("ok");
							}else{
								c.call(null, a);							
							}
							return;
						}
						try{
				    		r = $.parseJSON(a);
						}catch( e){
							console.error(e);
							alert("Error");
							return;
						}
						
						if(r.status != "ok"){
							alert("error");
							return;
						}
						
						if(c === undefined){
							alert("ok");
						}else{
							c.call(null, r);							
						}


				  	}
				});
			},
			
			test : function(){
				
				//Test params
				var url 	= $.getUrlVar('url'),
					test 	= $.getUrlVar('test');
				
				if(
					url === undefined 
					|| test === undefined
				){
					alert("Faltan parametros");
					return;
				}
				
				this.editar_paquete_cancel();
				
				var callback = function( res ){
					$("#response").html(res).show();
				}
				
				$("textarea").hide();
				
				$("#response").html("<div align=center><img src='l.gif'> Realizando pruebas...</div>").show();
				
				this.ajax({
					metodo	: "test",
					url 	: url,
					test_id	: test
				},callback, false);
			},
			

			
			nuevo_url_show : function(){
				html = '<div id="agregar_url" >';
				html += '	<h3>Agregar direccion de url</h3>';
				html += '	<input type="text" id="new_url_name" placeholder="Nombre">';
				html += '	<input type="text" id="new_url_url" placeholder="http://example.com/">			';
				html += '	<input type="button" value="Agregar" onClick="httptesting.nuevo_url()" >';
				html += '</div>';
				$.facebox(html);				
			},
                        
                        nuevo_paquete_show : function(){
				html = '<div id="agregar_paquete" >';
				html += '	<h3>Agregar paquete de pruebas</h3>';
                                html += '       <table width="100%">';
                                html += '       <tr>';
				html += '	<td colspan="2"><input type="text" id="new_paquete_name" placeholder="Nombre"></td>';
                                html += '       </tr>';
                                html += '       <tr>';
				html += '	<td colspan="2"><textarea id="new_paquete_descripcion" placeholder="Descripcion" style="width:70%; height: 50px"></textarea></td>';
                                html += '       </tr>';
                                html += '       <tr>';
                                html += '       <td colspan="2"><textarea id="new_paquete_pruebas" placeholder="Pruebas" style="width:100%; height: 100px"></textarea></td>';
                                html += '       </tr>';
                                html += '       <tr>';
                                html += '       <td><input type="checkbox" id="new_paquete_locked" value="0"> Locked</td>';
				html += '	<td><input type="button" value="Agregar" onClick="httptesting.nuevo_paquete()"></td>';
                                html += '       </tr>';
                                html += '       </table>';
				html += '</div>';
				$.facebox(html);				
			},
			
			swap_url : function(new_url_id){
				//Test params
				var test = $.getUrlVar('test');
				
				if( test === undefined ){
					window.location = "?url=" + new_url_id;
				}else{
					window.location = "?test="+ test +"&url=" + new_url_id;					
				}
			},
			
			nuevo_url : function(){
				var nombre  = $("#new_url_name").val(),
					url 	= $("#new_url_url").val();
					
				var callback = function(){
					window.location.reload();
				}

				this.ajax({
					metodo  : "nuevaRuta",
					nombre  : nombre,
					ruta    : url,
                                        proyecto: <?php if(isset($_GET["project"]) && is_numeric($_GET["project"])) echo $_GET["project"]; else echo '""';?>
				}, callback, true);
			},
                        
                        nuevo_paquete : function(){
				var nombre  = $("#new_paquete_name").val(),
                                    descripcion 	= $("#new_paquete_descripcion").val(),
                                    pruebas = $("#new_paquete_pruebas").val(),
                                    locked = ($("#new_paquete_locked").attr("checked") != "undefined" && $("#new_paquete_locked").attr("checked") == "checked");
					 
                                        
				var callback = function(a){
                                    
                                        try{
			    		r = $.parseJSON(a);
					}catch( e){
						console.error(e);
						alert("Error: Json no se pudo parsear");
						return;
					}
					
					if(r.status == "ok"){
						window.location.reload();						
					}else{
						alert(r.reason);
					}
				}

				this.ajax({
					metodo          : "nuevoPaquete",
					nombre          : nombre,
					descripcion     : descripcion,
                                        pruebas         : pruebas,
                                        locked          : locked,
                                        proyecto: <?php if(isset($_GET["project"]) && is_numeric($_GET["project"])) echo $_GET["project"]; else echo '""';?>
				}, callback, true);
			},
	
			<?php
			if(isset($_GET["test"])){
				$t = mysql_fetch_assoc(
						mysql_query(
							"select * from httptesting_paquete_de_pruebas where id_paquete_de_pruebas = " . $_GET["test"]));
							
				$casos = str_replace ( "\n", " " , htmlspecialchars($t["pruebas"]) );
				
				$casos = "";
			}
			?>
			editar_paquete_show : function(){
				
				$("#editar_paquete_show").show();
				$("#editar_paquete_pruebas").prop("disabled", false);
				$(".editar_paquete_normal").hide();
				$("textarea").show();
				$("#response").hide();
			},
			
			editar_paquete_show_at : function( line ){
				this.editar_paquete_show();
				$('#editar_paquete_pruebas').selectRange(322,333);
				
			},
			
			editar_paquete_cancel: function(){
				$("#editar_paquete_show").hide();
				$("#editar_paquete_pruebas").prop("disabled", true);
				$(".editar_paquete_normal").show();				
				
			},
			
			editar_paquete : function(){
				var nombre 			= $("#editar_paquete_nombre").val(),
					descripcion 	= $("#editar_paquete_descripcion").val(),
					pruebas			= $("#editar_paquete_pruebas").val();
					
				var callback = function(a){

					try{
			    		r = $.parseJSON(a);
					}catch( e){
						console.error(e);
						alert("Error");
						return;
					}
					
					if(r.status == "ok"){
						window.location.reload();						
					}else{
						alert(r.reason);
					}
					
				}

				this.ajax({
					metodo 		: "editarPaquete",
					nombre 		: nombre,
					descripcion	: descripcion,
					pruebas 	: pruebas,
					id_paquete_de_pruebas : <?php echo is_null($t["id_paquete_de_pruebas"]) ? "-1": $t["id_paquete_de_pruebas"];?>
				}, callback, true);
			}
			
			
			
		}
		

	</script>
</head>
<body class="mac Locale_en_US">
	<div class="devsitePage">
            <div id="top-bar">
                <div id="header-content">
                    <p class="logo">
                        <a href="">caffeina</a>
                    </p>
                    <ul class="nav">
                        <li><a href="https://labs.caffeina.mx/oficina/">Home</a></li>
                        <li><a href="https://labs.caffeina.mx/oficina/mantis">Bugs</a></li>
                        <li><a href="https://webframework.labs.caffeina.mx/">Web Framework</a></li>
                        <li class="last"><a href="https://labs.caffeina.mx/oficina/websvn/">WebSVN</a></li>
                    </ul>
                </div><!-- header-content -->
            </div><!-- top-bar -->
		<div class="menu">
			<div class="content">
				<a class="logo" href="../index.php">
				<img class="img" src="../media/cwhite.png" alt="Facebook Developers" width="166" height="17"/>
				</a>
				<a class="l" href="index.php">UnitTester</a>
				<a class="l" href="../apigen/">ApiGen</a>
                                <a class="l">Proyecto: 
                            
                                    <select name="project" id="project" onChange = "ProjectChange(this.value)" >
                                        <option value = "null"> ------------ </option>
                                        <?php

                                        $query = "select id as id_proyecto,name as nombre from mantis_project_table";
                                        $res = mysql_query($query);
                                        while($row = mysql_fetch_assoc($res))
                                        {
                                            if(isset($_GET["project"]) && $_GET["project"] == $row["id_proyecto"])
                                            {
                                                echo "<option value = ".$row["id_proyecto"]." selected>".$row["nombre"]."</option>";
                                            }
                                            else
                                            {
                                                echo "<option value = ".$row["id_proyecto"].">".$row["nombre"]."</option>";
                                            }
                                        }

                                        ?>
                                    </select>

                                    </a>
				<div class="clear">
				</div>
			</div>
		</div>
		<div class="body nav">
			<div class="content">
				<div id="bodyMenu" class="bodyMenu">
					<?php
                                        
                                            if(isset($_GET["project"]) && is_numeric($_GET["project"]))
                                            {
                                                
                                                echo
                                                '
                                                    <div id="form_nueva_categoria">
                                                        <a onClick="httptesting.nuevo_paquete_show()">Nuevo paquete de pruebas</a>
                                                        
                                                    </div>
                                                ';
                                                
						$s = mysql_query("select * from httptesting_paquete_de_pruebas where id_proyecto = ".$_GET["project"]);
						while( ($row = mysql_fetch_assoc($s))  != null){
							if(isset($_GET["test"]) && $_GET["test"] == $row["id_paquete_de_pruebas"]){
								$style = "background: gray; ";							
							}else{
								$style = "";
							}
							
							echo "<div style='". $style ."'>"; 
							$hop = "?test=" . $row["id_paquete_de_pruebas"] . "&" ; 
							if(isset( $_GET["url"] )){ 
								$hop .= "url=" . $_GET["url"]; 
							} 
							echo "<a href='".$hop."project=".$_GET["project"]."'><h3 style='margin-bottom:0px'>" . $row["nombre"] . "</h3></a>"; 
							echo "<p>" . $row["descripcion"] . "</p>"; 
							echo "</div>"; 
						}
                                            }
						?>
			</div>
			<div id="bodyText" class="bodyText">
				<div class="header">
					<div class="content">
						<h1>HTTP REST API Test Suite</h1>
						<p>
							Hacer llamadas HTTP al API.
						</p>
					</div>
				</div>
                            
                            <?php
                            if(isset($_GET["project"]) && is_numeric($_GET["project"]))
                            {
                                
                            
                            ?>
				<span id="selector">
				URL de pruebas
				<select name="cSelect" onChange="httptesting.swap_url(this.value)">
					<?php
					/**
					 * Obtener las urls
					 * 
					 * */
					$res = mysql_query("select * from httptesting_ruta where id_proyecto = ".$_GET["project"]);
					while( ($row = mysql_fetch_assoc($res)) != null ){
						if(isset($_GET["url"]) && $_GET["url"] == $row["id_ruta"]){
							echo "<option selected value='". $row["id_ruta"] ."'>" . $row["nombre"] . " | " . $row["ruta"] . "</option>"; 
						}else{ 
							echo "<option value='". $row["id_ruta"] ."'>" . $row["nombre"] . " | " . $row["ruta"] . "</option>"; 
						} 
					} 
					
					?>
				</select>
				<input type="button" name="" value="Iniciar pruebas" onClick="httptesting.test();" >
				<input type="button" name="" value="Agregar url" onClick="httptesting.nuevo_url_show()">
				
				<?php
				if(isset($t)){
					echo '<input type="button" class="editar_paquete_normal" 	 name="" value="Editar paquete" onClick="httptesting.editar_paquete_show()">';
				}
				?>
				

				
				</span>
                            <?php } ?>
				<hr />
				
				<div id="response"></div>

				<div id="editar_paquete_show" style="display: none">
					

					<h2 style="margin:0px">Editar este paquete</h2>
					<input type="button"   value="Guardar" id="editar_paquete_show_btn" onClick="httptesting.editar_paquete()" >
					<input type="button"   value="Cancelar" id="editar_paquete_cancel_btn" onClick="httptesting.editar_paquete_cancel()" >
										
					<br><br>Nombre del paquete<br>
					<input type="text" value="<?php echo $t["nombre"]; ?>"	id="editar_paquete_nombre"  ><br>
					
					Descripcion<br>
					<textarea 	cols="80" rows=4	id="editar_paquete_descripcion"><?php echo $t["descripcion"]; ?></textarea><br>
					Pruebas
				</div>

				<?php
					if(isset($t))
						echo '<textarea disabled cols="80" rows="20" id="editar_paquete_pruebas">' . $t["pruebas"] . "</textarea>"; 
				?>
				<div class="mtm pvm uiBoxWhite topborder">
					<div class="mbm">
					</div>
					<abbr title="Monday, September 5, 2011 at 8:28pm" data-date="Mon, 05 Sep 2011 18:28:49 -0700" class="timestamp">Ultima modifiacion</abbr>
				</div>
			</div>
			<div class="clear">
			</div>
		</div>
	</div>
	


	
	<div class="footer">
		<div class="content">
			<div class="copyright">
			</div>
			<div class="links">
				<a href="">About</a>
				<a href="">Platform Policies</a>
				<a href="">Privacy Policy</a>
			</div>
		</div>
	</div>
	</div>
</body>
</html>


