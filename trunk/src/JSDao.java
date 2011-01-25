import java.io.*;
import java.util.ArrayList;




class VO{

	static BufferedReader br, sqlFile;
	static PrintWriter pw;
	static ArrayList<String> includes;




	static void readFile( String file )
	{
		try{
			br = new BufferedReader(new FileReader( file ));
			sqlFile = new BufferedReader(new FileReader( file ));			
		}catch(IOException ioe){
			System.out.println( "Cant read File:" );
			System.out.println( ioe );
			System.exit(1);
		}
	}


	
	static void closeFile( )
	{
		try{
			br.close();			
		}catch(IOException ioe){
			System.out.println( "Cant close File:" );
			System.out.println( ioe );
			System.exit(1);			
		}

	}
	
	
	static String toCamelCase(String s)
	{
			//convertir a camelCase

			String foo [] = s.split("_");

			String camelCase = "";

			for(String bar : foo){
				camelCase += Character.toUpperCase( bar.charAt(0) ) + bar.substring( 1 );
			}
			
			return camelCase;
	}
	
	
	

	/*
	 *
	 *
	 * */
	static void parseTable(String t_name) throws IOException 
	{
		System.out.println( "parseando tabla: " + t_name );
		
		String tline ;
		
		ArrayList<Field> fields = new ArrayList<Field>();	
		
		int no_pks = 0;
		
		while( (tline = br.readLine()).trim().startsWith("`") )
		{

			String campo = tline.substring( tline.indexOf("`") + 1, tline.lastIndexOf("`") );
			
			String comentario = (tline.indexOf("COMMENT ") != -1) ? tline.substring( tline.indexOf("COMMENT ") + 9, tline.lastIndexOf("'") ) : " [Campo no documentado]";
			
			boolean autoInc = (tline.indexOf("AUTO INCREMENT ") != -1) || (tline.indexOf("auto_increment ") != -1 || (tline.indexOf("AUTOINCREMENT ") != -1));

			String tipo = tline.trim().split(" ")[1];


		
			if(tline.indexOf("PRIMARY KEY") != -1) {
				//la definicion de la llave primara esta 'inline' 
				fields.add( new Field(campo , tipo , comentario, true, autoInc) );
				no_pks++;
			}else{
				fields.add( new Field(campo , tipo , comentario, false, autoInc) );				
			}
		
		}
		

		
		
		//buscar llave primaria, PRIMARY KEY
		if( (tline.trim().startsWith("PRIMARY KEY") ) )
		{

			//hay definicion de llave primaria
			String pk = tline.substring( tline.indexOf("(")+1, tline.indexOf(")") );
			
			String pks[] = pk.split(",");
			
			for(String i: pks)
			{
				i= i.trim().substring( 1, i.length()-1 ) ;
				
				//cicle trough the fields, and add pk to the ones on i
				for(int a = 0; a < fields.size(); a++){

					if(fields.get( a ).title.equals(i)){
						fields.get( a ).isPrimary = true;
						no_pks ++;
					}
				}

				
			}
		}
		
		if(no_pks == 0){
			System.out.println("ERROR: "+t_name+" no contiene llave primaria, saltando tabla.");
				
			
			return ;
		}
		
		writeVO( t_name, fields );
	}
	
	
	
	
	
	//vo para una tabla
	static void writeVO( String tabla, ArrayList<Field> fields ) throws IOException
	{
		//convertir a camelCase
	
		String camelCaseTabla = toCamelCase( tabla );

		pw.println("/** Value Object file for table "+ tabla +"." );
		pw.println("  * ");
		pw.println("  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).");
		pw.println("  * @author Alan Gonzalez <alan@caffeina.mx> ");
		pw.println("  * @access public");
		pw.println("  * @package docs");
		pw.println("  * ");
		pw.println("  */");
		
		pw.println();
		pw.println("var " + camelCaseTabla + " = function ( config )"  );
		pw.println("{");
		
		/*
		//constructor
		{
			pw.println( "	/**");
			pw.println( "	  * Constructor de " + camelCaseTabla );
			pw.println( "	  * " );
			pw.println( "	  * Para construir un objeto de tipo "+ camelCaseTabla + " debera llamarse a el constructor " );
			pw.println( "	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo " );
			pw.println( "	  * cuyos campos son iguales a las variables que constituyen a este objeto." );
			pw.println( "	  * @return "+ toCamelCase(tabla) );
			pw.println( "	  *"+"/");
			pw.println("	function __construct( $data )");
			pw.println("	{ ");

			//pw.println("		if(isset($data))");
			//pw.println("		{");
		
		
			for(Field f : fields){
				pw.println("			if( isset($data['" + f.title + "']) ){");
				pw.println("				$this->"+f.title+" = $data['"+ f.title +"'];");
				pw.println("			}");
			}
		
		pw.println("		}");

		pw.println("	}");		
		pw.println("");
		}
*/




		boolean first = true;
		int x = 0;

		//actual fields
		for( Field f : fields)
		{
			pw.println( " /**");
			pw.println( "	* "+f.title );
			pw.println( "	* ");			
			pw.println( "	* "+f.comment );

			if(f.isPrimary)
			pw.println( "	* <b>Llave Primaria</b>");
				
			if(f.isAutoIncrement) 
			pw.println( "	* <b>Auto Incremento</b>");

			pw.println( "	* @access private" );
			pw.println( "	* @var "+ f.type );
			pw.println( "	*/");
			
			if(first){
				pw.print( "	var _" + f.title + " = config === undefined ? null : config." + f.title + " || null" );	
				first = false;
			}else{
				pw.print( "	_" + f.title + " = config === undefined ? null : config." + f.title + " || null" );				
			}

			x = x +1;
			if( x == fields.size() ){
				pw.println(";");
			}else{
				pw.println(",");				
			}

			pw.println();

		}
		
		

		
		//getters and setters
		for( Field f : fields)
		{
			
			String camelCaseCampo = toCamelCase( f.title );
			
			pw.println( "	/**");
			pw.println( "	  * get" + camelCaseCampo );
			pw.println( "	  * " );
			pw.println( "	  * Get the <i>" + f.title + "</i> property for this object. Donde <i>" + f.title +"</i> es " + f.comment );
			pw.println( "	  * @return "+ f.type );
			pw.println( "	  */");
			
			pw.println( "	this.get" + camelCaseCampo + " = function ()" );
			pw.println( "	{" );
			pw.println( "		return _"+ f.title +";");
			pw.println( "	};" );
			pw.println();


			pw.println( "	/**");
			pw.println( "	  * set" + camelCaseCampo + "( $"+f.title+" )" );
			pw.println( "	  * " );
			pw.println( "	  * Set the <i>" + f.title + "</i> property for this object. Donde <i>" + f.title +"</i> es " + f.comment +"." );
			pw.println( "	  * Una validacion basica se hara aqui para comprobar que <i>" + f.title + "</i> es de tipo <i>" + f.type +"</i>. " );
			pw.println( "	  * Si esta validacion falla, se arrojara... algo. " );
			
			if(f.isAutoIncrement) {
				pw.println( "	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>");
				pw.println( "	  * No deberias usar set" + camelCaseCampo + "( ) a menos que sepas exactamente lo que estas haciendo.<br>");
			}
			
			if(f.isPrimary) {
				pw.println( "	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>");
				pw.println( "	  * No deberias usar set" + camelCaseCampo + "( ) a menos que sepas exactamente lo que estas haciendo.<br>");
			}	
			
			pw.println( "	  * @param "+ f.type );
			pw.println( "	  */");
			
			pw.println( "	this.set" + camelCaseCampo + "  = function ( "+f.title+" )" );
			pw.println( "	{" );
			pw.println( "		_"+f.title+ " = " + f.title+";" );
			pw.println( "	};" );
			pw.println();
		}
		
		
		
			/*	
				metodo save
			*/
			{
				pw.println("	/**");
				pw.println("	  *	Guardar registros. ");
				pw.println("	  *	");
				pw.println("	  *	Este metodo guarda el estado actual del objeto {@link "+toCamelCase(tabla)+"} pasado en la base de datos. La llave ");
				pw.println("	  *	primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves");
				pw.println("	  *	primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando");
				pw.println("	  *	en ese objeto el ID recien creado.");
				pw.println("	  *	");
				pw.println("	  *	@static");
				pw.println("	  * @throws Exception si la operacion fallo.");
				pw.println("	  * @param "+toCamelCase(tabla)+" [$"+tabla+"] El objeto de tipo " + toCamelCase(tabla));
				pw.println("	  * @return Un entero mayor o igual a cero denotando las filas afectadas.");
				pw.println("	  **/");

				pw.println("	this.save = function( )");
				pw.println("	{");

				String pks = "";
				String foo = "(";

				for(Field f : fields){
					if(!f.isPrimary) continue;
					pks +=  " this.get"+ toCamelCase(f.title)+"() ,";
					//foo +=  " isset($"+tabla+".get"+ toCamelCase(f.title)+"()) && ";
				}

				//foo = foo.substring(0, foo.length() -3 ) + ")";

				pks = pks.substring(0, pks.length() -1 );

				pw.println("		if(  "+toCamelCase(tabla)+".getByPK( " + pks + ") !== null ) {");
				pw.println("			try{ return update( this ) ; } catch( eUp ) { throw eUp; }");
				pw.println("		}else{");
				pw.println("			try{ return create( this ) ; } catch( eCr ) { throw eCr; }");
				pw.println("		}");
				pw.println("	};");
				pw.println();
				pw.println();		
			}
			
			
			
			
			
			
			
			/*	
				metodo search
			*/

			{
				pw.println("	/**");
				pw.println("	  *	Buscar registros.");
				pw.println("	  *	");
				pw.println("	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link "+toCamelCase(tabla)+"} de la base de datos. ");
				pw.println("	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento. ");
				pw.println("	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.");
				pw.println("	  *	");

				pw.println("	  * <code>");
				pw.println("	  *  / **");
				pw.println("	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000");
				pw.println("	  *   {@*} ");

				pw.println("	  *	  cliente = new Cliente();");
				pw.println("	  *	  cliente.setLimiteCredito(\"20000\");");
				pw.println("	  *	  resultados = cliente.search();");
				pw.println("	  *	  ");
				pw.println("	  *	  foreach(resultados as c ){");
				pw.println("	  *	  	console.log( c.getNombre() );");
				pw.println("	  *	  }");

				pw.println("	  * </code>");
				pw.println("	  *	@static");
				pw.println("	  * @param "+toCamelCase(tabla)+" [$"+tabla+"] El objeto de tipo " + toCamelCase(tabla));
				pw.println("	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.");		
				pw.println("	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'");			

				pw.println("	  **/");

				pw.println("	this.search = function( $orderBy , $orden )");
				pw.println("	{");


				pw.println("		$sql = \"SELECT * from "+tabla+" WHERE (\"; ");
				pw.println("		$val = [];");

				for(Field f : fields)
				{
					pw.println("		if( this.get"+toCamelCase(f.title)+"() != null){");
					pw.println("			$sql += \" "+ f.title +" = ? AND\";");
					pw.println("			$val.push( this.get"+toCamelCase(f.title)+"() );");
					pw.println("		}");
					pw.println();
				}

	  			pw.println("		if( $val.length == 0){return [];}");

				pw.println("		$sql = $sql.substr( 0, $sql.length - 3) + \" )\";" );

	  			pw.println("		if( $orderBy !== null ){");
				pw.println("		    $sql += \" order by \" + $orderBy + \" \" + $orden ;");
				pw.println("		");
				pw.println("		}");

				pw.println("		//global $conn;");
				pw.println("		//$rs = $conn->Execute($sql, $val);");


				pw.println("		//$ar = array();");
				pw.println("		//foreach ($rs as $foo) {");
				pw.println("    	//	array_push( $ar, new "+ toCamelCase( tabla ) +"($foo));");
				pw.println("		//}");
				pw.println("		return $sql;");			


				pw.println("	};");
				pw.println();
				pw.println();		
			}		
			
			
			
		
		
			/*	
				metodo create
			*/
			{

				pw.println("	/**");
				pw.println("	  *	Crear registros.");
				pw.println("	  *	");
				pw.println("	  * Este metodo creara una nueva fila en la base de datos de acuerdo con los ");
				pw.println("	  * contenidos del objeto "+toCamelCase(tabla)+" suministrado. Asegurese");
				pw.println("	  * de que los valores para todas las columnas NOT NULL se ha especificado ");
				pw.println("	  * correctamente. Despues del comando INSERT, este metodo asignara la clave ");
				pw.println("	  * primaria generada en el objeto "+toCamelCase(tabla)+" dentro de la misma transaccion.");
				pw.println("	  *	");
				pw.println("	  * @internal private information for advanced developers only");
				pw.println("	  * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error");
				pw.println("	  * @param "+toCamelCase(tabla)+" [$"+tabla+"] El objeto de tipo " + toCamelCase(tabla) +" a crear." );
				pw.println("	  **/");
				pw.println("	var create = function( "+tabla+" )");
				pw.println("	{");
				String sql = "";
				String args = "";
				String sqlnames = "";
				String pk_ai = " ";

				for(Field f : fields){

					if(f.isPrimary && f.isAutoIncrement)
					{
						pk_ai += "$"+tabla+"->set"+ toCamelCase(f.title) + "( $conn->Insert_ID() );";
					}

					args += tabla + ".get"+ toCamelCase(f.title) + "(), \n			";
					sqlnames += f.title+", ";
					sql +=  "?, ";	

				}

				sqlnames = sqlnames.substring(0, sqlnames.length() -2 ) ;
				args = args.substring(0, args.length() -1 ) ;
				sql = sql.substring(0, sql.length() -2 );

				pw.println("		$sql = \"INSERT INTO "+tabla+" ( "+ sqlnames + " ) VALUES ( "+ sql +");\";" );
				pw.println("		$params = [\n			"+ args +" ];");

				pw.println("		//try{$conn->Execute($sql, $params);}");			
				pw.println("		//catch(Exception $e){ throw new Exception ($e->getMessage()); }");
				pw.println("		//$ar = $conn->Affected_Rows();");
				pw.println("		//if($ar == 0) return 0;");
				pw.println("		//" + pk_ai);
				pw.println("		return $sql;");
				pw.println("	};");
				pw.println();
			}	
		
		
		
			/*	
				metodo update
			*/
			{
				pw.println("	/**");
				pw.println("	  *	Actualizar registros.");
				pw.println("	  *	");
				pw.println("	  * Este metodo es un metodo de ayuda para uso interno. Se ejecutara todas las manipulaciones");
				pw.println("	  * en la base de datos que estan dadas en el objeto pasado.No se haran consultas SELECT ");
				pw.println("	  * aqui, sin embargo. El valor de retorno indica cuántas filas se vieron afectadas.");
				pw.println("	  *	");
				pw.println("	  * @internal private information for advanced developers only");
				pw.println("	  * @return Filas afectadas o un string con la descripcion del error");
				pw.println("	  * @param "+toCamelCase(tabla)+" [$"+tabla+"] El objeto de tipo " + toCamelCase(tabla) + " a actualizar.");
				pw.println("	  **/");


				pw.println("	var update = function( $"+tabla+" )");
				pw.println("	{");

				String sql = "";
				String args = "";
				String pk = "";
				String pkargs = "";

				for(Field f : fields){
					if( f.isPrimary ) 
					{
						pk += " " + f.title + " = ? AND";
						pkargs += "$"+tabla+".get" + toCamelCase(f.title)+"(),";
					}else{
						args += "$"+tabla+".get"+ toCamelCase(f.title) + "(), \n			";
						sql += f.title+" = ?, ";
					}
				}

				if(args.length()==0){

				}else{
					args = args.substring(0, args.length() -1 ) ;
					pk = pk.substring(0, pk.length() - 4 ) ;
					sql = sql.substring(0, sql.length() -2 );

					pw.println("		$sql = \"UPDATE "+tabla+" SET  "+ sql + " WHERE " +pk+ ";\";" );
					pw.println("		$params = [ \n			"+ args +"	"+ pkargs +"  ] ;");
					pw.println("		//global $conn;");
					pw.println("		//try{$conn->Execute($sql, $params);}");			
					pw.println("		//catch(Exception $e){ throw new Exception ($e->getMessage()); }");				
					pw.println("		return $sql;");
				}

				pw.println("	}");
				pw.println();
				pw.println();
			}		
		
			/*	
				metodo delete
			*/
			{


					pw.println("	/**");
					pw.println("	  *	Eliminar registros.");
					pw.println("	  *	");
					pw.println("	  * Este metodo eliminara la informacion de base de datos identificados por la clave primaria");
					pw.println("	  * en el objeto "+toCamelCase(tabla)+" suministrado. Una vez que se ha suprimido un objeto, este no ");
					pw.println("	  * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila ");
					pw.println("	  * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado. ");
					pw.println("	  * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.");
					pw.println("	  *	");
					pw.println("	  *	@throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.");
					pw.println("	  *	@return boolean Verdadero si todo salio bien.");
					pw.println("	  **/");

					pw.println("	this.delete = function(  )");
					pw.println("	{");


					String sql = "";
					String args = "";
					String pk = "";
					String pkargs = "";

					for(Field f : fields){

						if( f.isPrimary ) 
						{
							pk += " " + f.title + " = ? AND";
							pkargs += "this.get" + toCamelCase(f.title)+"(), ";
						}
					}

					if(pkargs.length() == 0){

					}else{
						pkargs = pkargs.substring(0, pkargs.length() -2 ) ;
						pk = pk.substring(0, pk.length() - 4 ) ;

						pw.println("		if( "+toCamelCase(tabla)+".getByPK("+ pkargs +") === null) throw new Exception('Campo no encontrado.');");
											//DELETE FROM `pos`.`cliente` WHERE `cliente`.`id_cliente` = 54 LIMIT 1
						pw.println("		$sql = \"DELETE FROM "+tabla+" WHERE " +pk+ ";\";" );

						pw.println("		$params = [ "+ pkargs +" ];");
						pw.println("		//$conn->Execute($sql, $params);");	
						pw.println("		return $sql;");	


					}


					pw.println("	};");
					pw.println();
					pw.println();
			}		
			
			
			
			
				/*	
					metodo byrange
				*/

				{
					pw.println("	/**");
					pw.println("	  *	Buscar por rango.");
					pw.println("	  *	");
					pw.println("	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link "+toCamelCase(tabla)+"} de la base de datos siempre y cuando ");
					pw.println("	  * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link "+toCamelCase(tabla)+"}.");
					pw.println("	  * ");
					pw.println("	  * Aquellas variables que tienen valores NULL seran excluidos en la busqueda. ");
					pw.println("	  * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.");
					pw.println("	  * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.");
					pw.println("	  *	");

					pw.println("	  * <code>");
					pw.println("	  *  /**");
					pw.println("	  *   * Ejemplo de uso - buscar todos los clientes que tengan limite de credito ");
					pw.println("	  *   * mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.");
					pw.println("	  *   {@*} ");

					pw.println("	  *	  $cr1 = new Cliente();");
					pw.println("	  *	  $cr1->setLimiteCredito(\"2000\");");
					pw.println("	  *	  $cr1->setDescuento(\"50\");");
					pw.println("	  *	  ");
					pw.println("	  *	  $cr2 = new Cliente();");
					pw.println("	  *	  $cr2->setLimiteCredito(\"5000\");");					
					pw.println("	  *	  $resultados = ClienteDAO::byRange($cr1, $cr2);");
					pw.println("	  *	  ");
					pw.println("	  *	  foreach($resultados as $c ){");
					pw.println("	  *	  	echo $c->getNombre() . \"<br>\";");
					pw.println("	  *	  }");

					pw.println("	  * </code>");
					pw.println("	  *	@static");
					pw.println("	  * @param "+toCamelCase(tabla)+" [$"+tabla+"] El objeto de tipo " + toCamelCase(tabla));
	    			pw.println("	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.");		
		    		pw.println("	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'");			
					pw.println("	  **/");

					pw.println("	this.byRange = function( $"+tabla+" , $orderBy , $orden )");
					pw.println("	{");

					pw.println("		$sql = \"SELECT * from "+tabla+" WHERE (\"; ");
					pw.println("		$val = [];");

					for(Field f : fields)
					{

						pw.println("		if( (($a = this.get"+toCamelCase(f.title)+"()) != null) & ( ($b = $"+tabla+".get"+toCamelCase(f.title)+"()) != null) ){");
						pw.println("				$sql += \" "+ f.title +" >= ? AND "+ f.title +" <= ? AND\";");
						pw.println("				$val.push( Math.min($a,$b)); ");
						pw.println("				$val.push( Math.max($a,$b)); ");
						pw.println("		}else{ ");
						pw.println("			if( $a || $b ){");
						pw.println("				$sql += \" "+ f.title +" = ? AND\"; ");
						pw.println("				$a = $a == null ? $b : $a;");
						pw.println("				$val.push( $a);");
						pw.println("			}");
						pw.println("		}");
						pw.println();
					}

					pw.println("		$sql = $sql.substr( 0, $sql.length -3) + \" )\";" );
	  	    		pw.println("		if( $orderBy !== null ){");
		    		pw.println("		    $sql += \" order by \" + $orderBy + \" \" + $orden ;");
		    		pw.println("		");
	    			pw.println("		}");
					pw.println("		//global $conn;");
					pw.println("		//$rs = $conn->Execute($sql, $val);");
					pw.println("		//$ar = array();");
					pw.println("		//foreach ($rs as $foo) {");
					pw.println("    	//	array_push( $ar, new "+ toCamelCase( tabla ) +"($foo));");
					pw.println("		//}");
					pw.println("		return $sql;");			

					pw.println("	};");
					pw.println();
					pw.println();		

			}
			
			
			
			
		
		//termina la clase
		pw.println("}");
		
		
		/*	
			metodo getAll
		*/
		{
			
			pw.println("	/**");
			pw.println("	  *	Obtener todas las filas.");
			pw.println("	  *	");
			pw.println("	  * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira");
			pw.println("	  * un vector que contiene objetos de tipo {@link "+toCamelCase(tabla)+"}. Tenga en cuenta que este metodo");
			pw.println("	  * consumen enormes cantidades de recursos si la tabla tiene muchas filas. ");
			pw.println("	  * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.");
			pw.println("	  *	");
			pw.println("	  *	@static");
			pw.println("	  * @param config Un objeto de configuracion con por lo menos success, y failure");			
			pw.println("	  * @param $pagina Pagina a ver.");			
			pw.println("	  * @param $columnas_por_pagina Columnas por pagina.");		
			pw.println("	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.");		
			pw.println("	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'");			
			pw.println("	  **/");
			pw.println("	" + toCamelCase(tabla) + ".getAll = function ( config, $pagina , $columnas_por_pagina, $orden, $tipo_de_orden )");
			pw.println("	{");
			pw.println("		$sql = \"SELECT * from "+tabla+"\";");				

			pw.println("		if($orden != null)");
			pw.println("		{ $sql += \" ORDER BY \" + $orden + \" \" + $tipo_de_orden;	}");
			
			pw.println("		if($pagina != null)");
			pw.println("		{");
			pw.println("			$sql += \" LIMIT \" + (( $pagina - 1 )*$columnas_por_pagina) + \",\" + $columnas_por_pagina; ");
			pw.println("		}");

			pw.println("		db.query($sql, [], function(tx,results){ ");
			pw.println("				fres = [];");
			pw.println("				for( i = 0; i < results.rows.length ; i++ ){ fres.push( new "+ toCamelCase(tabla) +"( results.rows.item(i) ) ) }");
			pw.println("				console.log(fres, config) ");
			pw.println("			});");
			pw.println("		return;");
			pw.println("	};");
			
			pw.println();		
		}
		
		
		
		
	
		/*	
			metodo getByPK
		*/
		{

			String pks = "";
			String sql = "";

			for(Field f : fields){

				if(!f.isPrimary) continue;

				pks +=  " $"+f.title+",";
				sql +=  f.title +" = ? AND ";
			}

			pks = pks.substring( 0, pks.length() -1 );			
			sql = sql.substring( 0, sql.length() -4 );


			pw.println("	/**");
			pw.println("	  *	Obtener {@link "+toCamelCase(tabla)+"} por llave primaria. ");
			pw.println("	  *	");
			pw.println("	  * Este metodo cargara un objeto {@link "+toCamelCase(tabla)+"} de la base de datos ");
		    pw.println("	  * usando sus llaves primarias. ");
			pw.println("	  *	");
			pw.println("	  *	@static");
			pw.println("	  * @return @link "+toCamelCase(tabla)+" Un objeto del tipo {@link "+toCamelCase(tabla)+"}. NULL si no hay tal registro.");
			pw.println("	  **/");

			pw.println("	"+toCamelCase(tabla)+".getByPK = function( " + pks + ", config )");
			pw.println("	{");
			pw.println("		$sql = \"SELECT * FROM "+tabla+" WHERE ("+ sql + ") LIMIT 1;\";");
			pw.println("		db.query($sql, [" +pks+ "] , function(tx,results){ ");
			pw.println("				if(  )");			
			pw.println("				fres = results.rows.item(0)");
			pw.println("				console.log(fres, config) ");
			pw.println("			});");
			pw.println("		return;");			
			pw.println("	};");
			pw.println();
			pw.println();			
		}
		
		

	}





















	
	
	/*
		init
	*/

	static void parseContent() throws IOException
	{
		
		System.out.println("Starting...");
		
        //crear directorios
        File f = new File("dao");
        f.mkdir();

		String line ;
		int table_count = 0;
		int views_count = 0;
		
		
		
		
		String fileName = "dao/DAO.js";

			pw = new PrintWriter(new FileWriter( fileName ));

			pw.println("var Database = function (){");
			pw.println("	this.db = openDatabase(\"pos\", \"1.0\", \"Point of Sale\", 200000);");

			pw.println("	// Crear la esctructura de la base de datos");
			pw.println("	this.db.transaction( function(tx) {");
			pw.println("	    tx.executeSql(");

			pw.println("			\"\"" ); 			

			
			while( (line = sqlFile.readLine()) != null ){
				if(line.trim().startsWith("--")) continue;
				if(line.trim().length() == 0 ) continue;	
				pw.println("			+ \"" + line + "\"" ); 
			}
			pw.println("			," );
			pw.println("	        [],");
			pw.println("	        sqlWin,");
			pw.println("	        sqlFail");
			pw.println("	    	);");
			pw.println("		}, txFail, txWin);");
			pw.println("");
			pw.println("		this.query = function( query, params, fn ){");
			pw.println("			this.db.transaction( function(tx) {");
			pw.println("			    tx.executeSql(");
			pw.println("			        query,");
			pw.println("			        params,");
			pw.println("			        fn,");
			pw.println("			        sqlFail");
			pw.println("			    );");
			pw.println("			}, txFail, txWin);");
			pw.println("		}");


			pw.println("	var txFail = function (err) { alert(\"TX failed: \" + err.message); }");
			pw.println("	var txWin = function(tx){  }	");
			
			pw.println("	var sqlFail = function(err) { alert(\"SQL failed: \" + err.message); }");			
			pw.println("	var sqlWin = function(tx, response) { console.log(\"SQL succeeded: \" + response.rows.length + \" rows.\"); }");

			pw.println("};");
			
			pw.println("var db = new Database();");


		while( (line = br.readLine()) != null )
		{
			if( line.indexOf("CREATE TABLE") != -1 ) 
			{
				table_count++;
				
				String t_name = line.substring( line.indexOf("`") + 1, line.lastIndexOf("`") );
				
				parseTable( t_name );
			}

		}
		
		pw.close();		
		
		System.out.println("Parsed tables: " + table_count);
		System.out.println("Parsed views: " + views_count);		
	}
	
	
	
	
	
	
	



	
	public static void main(String ... args)
	{
		
		br = null;

		includes = new ArrayList<String>();

		if(args.length < 1){
			System.out.println("No sql file specified... using bd.sql");
			readFile( "bd.sql" );
		}else{
			readFile( args[0] );
		}
				
		try{
			parseContent(  );			
		}catch(IOException ioe){
			System.out.println( "Error al parsear..." );
			System.out.println( ioe );
		}

		
		
		closeFile();
	}


}







class Field{
	
	String title;
	String type;
	String comment;
	boolean isPrimary;
	boolean isAutoIncrement;
	
	Field( String title )
	{
		this.title = title;
	}
	
	Field( String title, String type, String comment, boolean isPrimary, boolean isAutoIncrement )
	{
		this.title = title;
		this.type = type;
		this.comment = comment;
		this.isPrimary = isPrimary;
		this.isAutoIncrement = isAutoIncrement;
	}
	
}

