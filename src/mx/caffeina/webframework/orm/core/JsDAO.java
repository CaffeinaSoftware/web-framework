package mx.caffeina.webframework.orm.core;

import java.io.*;
import java.util.ArrayList;

/**
 *
 * @author Alan Gonzalez <alanboy@alanboy.net>
 */
public class JsDAO {
	static BufferedReader br, sqlFile;
	static PrintWriter pw;
	static ArrayList<String> includes;

   //solo puede ser necesario un String con absolutepath
    private File                path;

        //anterior  boolean readFile( String file )
    private boolean readFile( File file )
    {
        try{
            br = new BufferedReader(new FileReader( file ));
            sqlFile = new BufferedReader(new FileReader( file ));
            return true;

        }catch(IOException ioe){
            System.out.println("Cant read file\nError:\t" + ioe);
            return false;

        }
    }


    static void closeFile( )
	{
		try{
			br.close();
		}catch(IOException ioe){
			System.out.println( "Cant close File:\n" + ioe );
			//System.out.println( ioe );
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
		System.out.println( "\nparseando tabla: " + t_name );

		String tline ;

		ArrayList<JsField> fields = new ArrayList<JsField>();

		int no_pks = 0;

		while( (tline = br.readLine()).trim().startsWith("`") )
		{

			String campo = tline.substring( tline.indexOf("`") + 1, tline.lastIndexOf("`") );

			String comentario = (tline.indexOf("COMMENT ") != -1) ? tline.substring( tline.indexOf("COMMENT ") + 9, tline.lastIndexOf("'") ) : " [Campo no documentado]";

			boolean autoInc = (tline.indexOf("AUTO INCREMENT") != -1) || (tline.indexOf("auto_increment") != -1 || (tline.indexOf("AUTOINCREMENT ") != -1));

			String tipo = tline.trim().split(" ")[1];



			if(tline.indexOf("PRIMARY KEY") != -1) {
				//la definicion de la llave primara esta 'inline'
				fields.add( new JsField(campo , tipo , comentario, true, autoInc) );
				no_pks++;
			}else{
				fields.add( new JsField(campo , tipo , comentario, false, autoInc) );
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
    static void writeVO( String tabla, ArrayList<JsField> fields ) throws IOException
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


        boolean first = true;
        int x = 0;
/***aqui esta no que la falta
         /* ####################################################################

                ##     ##    ###    ##       ##     ## ########  ######
                ##     ##   ## ##   ##       ##     ## ##       ##    ##
                ##     ##  ##   ##  ##       ##     ## ##       ##
                ##     ## ##     ## ##       ##     ## ######    ######
                 ##   ##  ######### ##       ##     ## ##             ##
                  ## ##   ##     ## ##       ##     ## ##       ##    ##
                   ###    ##     ## ########  #######  ########  ######

        ##################################################################### */
        for( JsField f : fields)
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
                        pw.print( "	var _" + f.title + " = config === undefined ? '' : config." + f.title + " || ''" );
                        first = false;
                }else{
                        pw.print( "	_" + f.title + " = config === undefined ? '' : config." + f.title + " || ''" );
                }

                x = x +1;
                if( x == fields.size() ){
                        pw.println(";");
                }else{
                        pw.println(",");
                }

                pw.println();

        }

        /* ####################################################################

             ######   ######## ########     ######  ######## ########
            ##    ##  ##          ##       ##    ## ##          ##
            ##        ##          ##       ##       ##          ##
            ##   #### ######      ##        ######  ######      ##
            ##    ##  ##          ##             ## ##          ##
            ##    ##  ##          ##       ##    ## ##          ##
             ######   ########    ##        ######  ########    ##

        ##################################################################### */


        //getters and setters
        for( JsField f : fields)
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



        /* ####################################################################

                              ##  ######   #######  ##    ##
                              ## ##    ## ##     ## ###   ##
                              ## ##       ##     ## ####  ##
                              ##  ######  ##     ## ## ## ##
                        ##    ##       ## ##     ## ##  ####
                        ##    ## ##    ## ##     ## ##   ###
                         ######   ######   #######  ##    ##

        ##################################################################### */

        pw.println( "	this.json = {" );
        
        pw.println( "	" );
        pw.println( "	" );
        x = 0;
        for( JsField f : fields)
        {

            pw.print( "	" + f.title + " : _" + f.title  );

            x = x +1;
            if( x < fields.size() ){
                    pw.print(",");
            }
            pw.println();
        }

        pw.println( "	" );
        pw.println( "   };" );




        /* ####################################################################

         ######     ###    ##       ##       ########     ###     ######  ##    ##
        ##    ##   ## ##   ##       ##       ##     ##   ## ##   ##    ## ##   ##
        ##        ##   ##  ##       ##       ##     ##  ##   ##  ##       ##  ##
        ##       ##     ## ##       ##       ########  ##     ## ##       #####
        ##       ######### ##       ##       ##     ## ######### ##       ##  ##
        ##    ## ##     ## ##       ##       ##     ## ##     ## ##    ## ##   ##
         ######  ##     ## ######## ######## ########  ##     ##  ######  ##    ##

        ##################################################################### */
        pw.println("	var _callback_stack = [];");
        pw.println("	this.pushCallback = function( fn, context ){");
        pw.println("	    _callback_stack.push({ f: fn, c: context });");
        pw.println("	};");
        pw.println("	this.callCallback = function(params){");
        pw.println("	    var t = _callback_stack.pop();");
        pw.println("	    t.f.call(t.c, params);");
        pw.println("	};");


        /* ####################################################################

             ######     ###    ##     ## ########
            ##    ##   ## ##   ##     ## ##
            ##        ##   ##  ##     ## ##
             ######  ##     ## ##     ## ######
                  ## #########  ##   ##  ##
            ##    ## ##     ##   ## ##   ##
             ######  ##     ##    ###    ########

        ##################################################################### */
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
                pw.println("	  * @param Function to callback");
                pw.println("	  * @return Un entero mayor o igual a cero denotando las filas afectadas.");
                pw.println("	  **/");

                pw.println("	this.save = function( callback )");
                pw.println("	{");
                

                pw.println("		if(DEBUG) console.log('estoy en save()');");
                pw.println("		this.pushCallback(callback, this);");

                pw.println("		var cb = function(res){");
                pw.println("                if(DEBUG)console.log('estoy de regreso en save(',res,') con el contexto:', this);");
                pw.println("                if(res == null){");
                pw.println("                    create.call(this,null);");
                pw.println("                }else{");
                pw.println("                    update.call(this,null);");
                pw.println("                }");
                pw.println("		};");

                String pks = "";
                for(JsField f : fields){
                    if(!f.isPrimary) continue;
                    pks +=  " this.get"+ toCamelCase(f.title)+"() ,";
                }
                
                pks = pks.substring(0, pks.length() -1 );
                
                pw.println("		"+toCamelCase(tabla)+".getByPK( " + pks + ", { context : this, callback : cb } ) ");
                pw.println("	}; //save()\n");
                
        }



         /* ####################################################################

             ######  ########    ###    ########   ######  ##     ##
            ##    ## ##         ## ##   ##     ## ##    ## ##     ##
            ##       ##        ##   ##  ##     ## ##       ##     ##
             ######  ######   ##     ## ########  ##       #########
                  ## ##       ######### ##   ##   ##       ##     ##
            ##    ## ##       ##     ## ##    ##  ##    ## ##     ##
             ######  ######## ##     ## ##     ##  ######  ##     ##

        ##################################################################### */

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
                pw.println("	  *	  	//console.log( c.getNombre() );");
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

                for(JsField f : fields)
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





         /* ####################################################################


             ######  ########  ########    ###    ######## ########
            ##    ## ##     ## ##         ## ##      ##    ##
            ##       ##     ## ##        ##   ##     ##    ##
            ##       ########  ######   ##     ##    ##    ######
            ##       ##   ##   ##       #########    ##    ##
            ##    ## ##    ##  ##       ##     ##    ##    ##
             ######  ##     ## ######## ##     ##    ##    ########

        ##################################################################### */
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
                pw.println("	var create = function(  )");
                pw.println("	{");
                String sql = "";
                String args = "";
                String sqlnames = "";
                String pk_ai = " ";

                for(JsField f : fields){

                        if(f.isPrimary && f.isAutoIncrement)
                        {
                                //@TODO
                                //pk_ai += "$"+tabla+"->set"+ toCamelCase(f.title) + "( $conn->Insert_ID() );";
                        }

                        args += "this.get"+ toCamelCase(f.title) + "(), \n			";
                        sqlnames += f.title+", ";
                        sql +=  "?, ";

                }

                sqlnames = sqlnames.substring(0, sqlnames.length() -2 ) ;
                args = args.substring(0, args.length() -1 ) ;
                sql = sql.substring(0, sql.length() -2 );

                pw.println("		if(DEBUG)console.log('estoy en create(this)');" );
                pw.println("		$sql = \"INSERT INTO "+tabla+" ( "+ sqlnames + " ) VALUES ( "+ sql +");\";" );
                pw.println("		$params = [\n			"+ args +" ];");
                pw.println("            var old_this = this;");
                pw.println("		db.query($sql, $params, function(tx, results){ ");
                pw.println("                if(DEBUG) console.log('ya termine el query de insert():',tx,results);");
                
                pw.println("                old_this.callCallback(old_this);");
                pw.println("		});");

                pw.println("		return; ");
                pw.println("	};");
                pw.println();
        }

         /* ####################################################################

                ##     ## ########  ########     ###    ######## ########
                ##     ## ##     ## ##     ##   ## ##      ##    ##
                ##     ## ##     ## ##     ##  ##   ##     ##    ##
                ##     ## ########  ##     ## ##     ##    ##    ######
                ##     ## ##        ##     ## #########    ##    ##
                ##     ## ##        ##     ## ##     ##    ##    ##
                 #######  ##        ########  ##     ##    ##    ########

        ##################################################################### */

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


                pw.println("	var update = function(  )");
                pw.println("	{");

                String sql = "";
                String args = "";
                String pk = "";
                String pkargs = "";

                for(JsField f : fields){
                        if( f.isPrimary )
                        {
                                pk += " " + f.title + " = ? AND";
                                pkargs += "this.get" + toCamelCase(f.title)+"(),";
                        }else{
                                args += "this.get"+ toCamelCase(f.title) + "(), \n			";
                                sql += f.title+" = ?, ";
                        }
                }

                if(args.length()==0){

                }else{
                        args = args.substring(0, args.length() -1 ) ;
                        pk = pk.substring(0, pk.length() - 4 ) ;
                        sql = sql.substring(0, sql.length() -2 );
                        pw.println("		if(DEBUG)console.log('estoy en update(',this,')');" );
                        pw.println("		$sql = \"UPDATE "+tabla+" SET  "+ sql + " WHERE " +pk+ ";\";" );
                        pw.println("		$params = [ \n			"+ args +"	"+ pkargs +"  ] ;");

                        
                        pw.println("            var old_this = this;");
                        pw.println("		db.query($sql, $params, function(tx, results){ ");
                        pw.println("                    if(DEBUG)console.log('ya termine el query de update():', tx, results, this);");
                        pw.println("			old_this.callCallback(old_this);  ");
                        pw.println("                });");

                        pw.println("		return; ");

                }

                pw.println("	};");
                pw.println();
                pw.println();
        }


        

         /* ####################################################################


                ########  ######## ##       ######## ######## ########
                ##     ## ##       ##       ##          ##    ##
                ##     ## ##       ##       ##          ##    ##
                ##     ## ######   ##       ######      ##    ######
                ##     ## ##       ##       ##          ##    ##
                ##     ## ##       ##       ##          ##    ##
                ########  ######## ######## ########    ##    ########

        ##################################################################### */
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

            pw.println("	this.destroy = function( config )");
            pw.println("	{");


            String sql = "";
            String args = "";
            String pk = "";
            String pkargs = "";

            for(JsField f : fields){

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

                //pw.println("		if( "+toCamelCase(tabla)+".getByPK("+ pkargs +") === null) throw new Exception('Campo no encontrado.');");
                pw.println("		$sql = \"DELETE FROM "+tabla+" WHERE " +pk+ ";\";" );
                pw.println("		$params = [ "+ pkargs +" ];");
                
                pw.println("		if(DEBUG){console.log( $sql, $params );}" );

                pw.println("		db.query($sql, $params, function(tx,results){ ");
                pw.println("                        config.callback.call(config.context || null, results);");
                pw.println("			});");
                pw.println("		return;");

            }


            pw.println("	};");
            pw.println();
            pw.println();
        }


         /* ####################################################################


            ########  ##    ## ########     ###    ##    ##  ######   ########
            ##     ##  ##  ##  ##     ##   ## ##   ###   ## ##    ##  ##
            ##     ##   ####   ##     ##  ##   ##  ####  ## ##        ##
            ########     ##    ########  ##     ## ## ## ## ##   #### ######
            ##     ##    ##    ##   ##   ######### ##  #### ##    ##  ##
            ##     ##    ##    ##    ##  ##     ## ##   ### ##    ##  ##
            ########     ##    ##     ## ##     ## ##    ##  ######   ########

        ##################################################################### */

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

            for(JsField f : fields)
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

        
         /* ####################################################################

                 ######   ######## ########    ###    ##       ##
                ##    ##  ##          ##      ## ##   ##       ##
                ##        ##          ##     ##   ##  ##       ##
                ##   #### ######      ##    ##     ## ##       ##
                ##    ##  ##          ##    ######### ##       ##
                ##    ##  ##          ##    ##     ## ##       ##
                 ######   ########    ##    ##     ## ######## ########

        ##################################################################### */
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
                pw.println("	  * @param config Un objeto de configuracion con por lo menos success, y failure y..");
                pw.println("	  * @param $pagina Pagina a ver.");
                pw.println("	  * @param $columnas_por_pagina Columnas por pagina.");
                pw.println("	  * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.");
                pw.println("	  * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'");
                pw.println("	  **/");
                pw.println("	" + toCamelCase(tabla) + ".getAll = function ( config )");
                pw.println("	{");
                pw.println("		$sql = \"SELECT * from "+tabla+"\";");

                pw.println("		if(config.orden !== undefined)");
                pw.println("                $sql += \" ORDER BY \" + config.orden + \" \" + config.tipo_de_orden;");

                pw.println("		if(config.pagina !== undefined)");
                pw.println("                $sql += \" LIMIT \" + (( config.pagina - 1 )* config.columnas_por_pagina) + \",\" + config.columnas_por_pagina; ");
                

                pw.println("		db.query($sql, [], function(tx,results){ ");
                pw.println("				fres = [];");
                pw.println("				for( i = 0; i < results.rows.length ; i++ ){ "
                        + "                                 fres.push( new "+ toCamelCase(tabla) +"( results.rows.item(i) ) ) "
                        + "                             }");
                pw.println("                            config.callback.call(config.context || null, fres);");
                pw.println("			});");
                pw.println("		return;");
                pw.println("	};");

                pw.println();
        }



         /* ####################################################################

             ######   ######## ######## ########  ##    ## ########  ##    ##
            ##    ##  ##          ##    ##     ##  ##  ##  ##     ## ##   ##
            ##        ##          ##    ##     ##   ####   ##     ## ##  ##
            ##   #### ######      ##    ########     ##    ########  #####
            ##    ##  ##          ##    ##     ##    ##    ##        ##  ##
            ##    ##  ##          ##    ##     ##    ##    ##        ##   ##
             ######   ########    ##    ########     ##    ##        ##    ##

        ##################################################################### */



        {

                String pks = "";
                String sql = "";

                for(JsField f : fields){

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
                pw.println("		if(DEBUG) console.log('estoy en getbypk()');");
                pw.println("		$sql = \"SELECT * FROM "+tabla+" WHERE ("+ sql + ") LIMIT 1;\";");
                pw.println("		db.query($sql, [" +pks+ "] , function(tx,results){ ");
                pw.println("			if(DEBUG)console.log('ya termine el query de getByPK():',tx,results);");
                pw.println("			if(results.rows.length == 0) fres = null;");
                pw.println("			else fres = new "+toCamelCase(tabla)+"(results.rows.item(0)); ");
                pw.println("			config.callback.call(config.context || null, fres);");
                pw.println("                });");
                pw.println("		return;");
                pw.println("	};");
                pw.println();
                pw.println();
        }



    }























	/*
		init
	*/

	 void parseContent() throws IOException
	{

		System.out.println("Starting...");

                //crear directorios
                //File f = new File("dao");
                //f.mkdir();

                File f = new File(path.getAbsolutePath());
                f.mkdir();
		String line ;
		int table_count = 0;
		int views_count = 0;




		String fileName = "/DAO.js";

			pw = new PrintWriter(new FileWriter( path.getAbsolutePath() + fileName ));

			pw.println("var Database = function (){");
			pw.println("	this.db = openDatabase(\"pos\", \"1.0\", \"Point of Sale\", 200000);");

			pw.println("	// Crear la esctructura de la base de datos");
			pw.println("	this.db.transaction( function(tx) {");
			pw.println("	    tx.executeSql(");
			pw.println("			\"\"" );

			while( (line = sqlFile.readLine()) != null ){

				//continuar si son comentarios
				if(line.trim().startsWith("--")) continue;

				//continuar si despues del trim resulta no haber nada
				if(line.trim().length() == 0 ) continue;

				//quitar los sets, de mysql
				if(line.trim().startsWith("SET ")) continue;
  
				//quitar los comentarios de mysql
				if(line.trim().startsWith("/*") && line.trim().endsWith("*/;")) continue;

				if(line.trim().indexOf("enum") != -1) {
					System.out.println("EPA ! Enums no son soportados por Sqlite !*");
					//`escala` enum('kilogramo','pieza','litro','unidad') COLLATE utf8_unicode_ci NOT NULL,
                                        line = line.trim().substring(0, line.trim().lastIndexOf("`")+1);
                                        line += " varchar(32),";
				}

				//
				if(	line.trim().startsWith("PRIMARY KEY")
					&& line.trim().endsWith(",")
				){
					line = " " + line.trim().substring( 0, line.trim().length() -1 );
				}

				//
				if(line.trim().startsWith("KEY") ) continue;

				//
				if(line.trim().startsWith(")") ) {
					pw.println("			+ \");\"" );

					//its over boy, start new transaction
					pw.println("			," );
					pw.println("	        [],");
					pw.println("	        sqlWin,");
					pw.println("	        sqlFail");
					pw.println("	    	);");
					pw.println("	    tx.executeSql(");

					pw.println("			\"\"" );
					continue;
				}




				line = line.replaceAll("COLLATE utf8_unicode_ci", "");
				line = line.replaceAll("SET latin1", "");
				line = line.replaceAll("COLLATE latin1_general_cs", "");
				line = line.replaceAll("CHARACTER", "");
				line = line.replaceAll("unsigned", "");


				if(line.trim().startsWith("UNIQUE KEY") ) {

					continue;
				}

				int where = -1;
				if((where = line.indexOf(" ON UPDATE CURRENT_TIMESTAMP")) != -1){
					line = line.substring( 0, where ) + ",";
				}


				where = -1;
				if((where = line.indexOf("AUTO_INCREMENT")) != -1){
					line = line.replaceFirst("AUTO_INCREMENT", "");
				}

				where = -1;
				if(( where = line.indexOf("COMMENT ")) != -1){
					line = line.substring( 0, where ) + ", ";
				}

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


			pw.println("	var txFail = function (err) { console.error(\"TX failed: \" + err.message); }");
			pw.println("	var txWin = function(tx){  }	");

			pw.println("	var sqlFail = function(err) { console.error(\"SQL failed: \" + err.message, err); }");
			pw.println("	var sqlWin = function(tx, response) { /*console.log(\"SQL succeeded: \" + response.rows.length + \" rows.\");*/ }");

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







        public boolean playParser(File sql, File dir, String aut){
            br = null;
            /*if(!aut.isEmpty())
                author = aut;*/

            includes = new ArrayList<String>();

            if(!(sql.isFile() && sql.getName().endsWith(".sql")) ) {
                System.out.println("No sql file specified...");
                return false;
            }
            if(dir.isDirectory()) {
                path = dir;
            } else {
                System.out.println("No path specified...");
                return false;
            }

            readFile( sql );

            try{
                parseContent();
            }catch( IOException ioe ){
                //System.out.println(ioe);
                System.out.println("Error al parsear...\n"+ioe);
            }

            //writeIncludes();
            closeFile();
            return true;

        }

}



class JsField{

	String title;
	String type;
	String comment;
	boolean isPrimary;
	boolean isAutoIncrement;

	JsField( String title )
	{
		this.title = title;
	}

	JsField( String title, String type, String comment, boolean isPrimary, boolean isAutoIncrement )
	{
		this.title = title;
		this.type = type;
		this.comment = comment;
		this.isPrimary = isPrimary;
		this.isAutoIncrement = isAutoIncrement;
	}

}
