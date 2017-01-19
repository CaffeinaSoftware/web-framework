package mx.caffeina.webframework.orm.core;

import java.io.*;
import java.util.ArrayList;

/**
 *
 * @author Alan Gonzalez <alanboy@alanboy.net>
 */
public class PhpDAO
{
	private BufferedReader      br;
	private ArrayList<String>   includes;
	private String              author = "someone@caffeina.mx";
	private File                path; //solo puede ser necesario un String con absolutepath
	private boolean             omitGeneratedCall;  // No genera la función __call.
	private boolean             useUnixTimestamps;  // Usa UNIX timestamps exclusivamente para fechas.
	private boolean             useSpaces;          // Usa espacios en vez de tabs.

	private boolean readFile(File file)
	{
		try{
			br = new BufferedReader(new FileReader( file ));
			return true;

		}catch(IOException ioe){
			System.out.println("Cant read file\nError:\t" + ioe);
			return false;

		}
	}

	private boolean closeFile()
	{
		try{
			br.close();
			return true;
		}catch(IOException ioe){
			System.out.println("Cant close File\nError:\t" + ioe);
			return false;
		}
	}

	private String toCamelCase(String s)
	{
		String foo [] = s.split("_");
		String camelCase = "";
		for(String bar : foo)
		{
			camelCase += Character.toUpperCase( bar.charAt(0) ) + bar.substring( 1 );
		}
		return camelCase;
	}

	private static String parseToken(String line, int idx) {
		char firstChar = line.charAt(idx);
		int finalIdx = idx + 1;
		if (firstChar == '\'' || firstChar == '"') {
			while (finalIdx < line.length() && line.charAt(finalIdx) != firstChar) {
				if (line.charAt(finalIdx) == '\\') finalIdx++;
				finalIdx++;
			}
			if (finalIdx < line.length()) {
				finalIdx++; // include the closing quote.
			}
		} else {
			while (finalIdx < line.length() && line.charAt(finalIdx) != ',' &&
					line.charAt(finalIdx) != ' ' && line.charAt(finalIdx) != ')') {
				finalIdx++;
			}
		}
		return line.substring(idx, finalIdx);
	}

	private void parseTable(String t_name) throws IOException
	{
		String tline ;
		ArrayList<Field> fields = new ArrayList<Field>();
		int no_pks = 0;

		System.out.println( "Procesando tabla: " + t_name );

		while( (tline = br.readLine()).trim().startsWith("`") )
		{
			String campo = tline.substring( tline.indexOf("`") + 1, tline.lastIndexOf("`") );
			String comentario = (tline.indexOf("COMMENT ") != -1) ? tline.substring( tline.indexOf("COMMENT ") + 9, tline.lastIndexOf("'") ) : " [Campo no documentado]";
			boolean autoInc = (tline.indexOf("AUTO_INCREMENT") != -1) || (tline.indexOf("auto_increment") != -1);
			String tipo = tline.trim().split(" ")[1].toLowerCase();
			String defaultValue = null;

			// Posibles combinaciones de valures
			// default:
			// -DEFAULT NULL
			// -NULL DEFAULT '0'
			// -NOT NULL DEFAULT '0'
			if (tline.contains("DEFAULT NULL")){
				// No incluir nada en este caso
			}
			else if (tline.contains("DEFAULT"))
			{
			    defaultValue = parseToken(tline, tline.indexOf("DEFAULT ") + "DEFAULT ".length());
			}

			fields.add(new Field(campo , tipo , comentario, false, autoInc, defaultValue));
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
				for(int a = 0; a < fields.size(); a++)
				{
					if(fields.get( a ).title.equals(i))
					{
						fields.get( a ).isPrimary = true;
						no_pks ++;
					}
				}
			}
		}

		writeVO( t_name, fields, no_pks != 0 );

		writeDAOBase( t_name, fields, no_pks != 0 );

		writeDAO( t_name, fields, no_pks != 0 );
	}

	private void writeVO( String tabla, ArrayList<Field> fields, boolean has_pk ) throws IOException
	{
		String camelCaseTabla = toCamelCase( tabla );
		String fileName = path.getAbsolutePath() + "/dao/base/" + tabla + ".vo.base.php";

		PrintWriter pw = new PrintWriter(new TabFilterWriter(new FileWriter( fileName ), this.useSpaces, 4));

		pw.println("<?php");
		pw.println();
		pw.println("/** ******************************************************************************* *");
		pw.println("  *                    !ATENCION!                                                   *");
		pw.println("  *                                                                                 *");
		pw.println("  * Este codigo es generado automaticamente. Si lo modificas tus cambios seran      *");
		pw.println("  * reemplazados la proxima vez que se autogenere el codigo.                        *");
		pw.println("  *                                                                                 *");
		pw.println("  * ******************************************************************************* */");
		pw.println();

		pw.println("/**");
		pw.println(" * Value Object file for table "+ tabla +"." );
		pw.println(" *");
		if (omitGeneratedCall)
		{
			pw.println(" * VO does not have any behaviour.");
		}
		else
		{
			pw.println(" * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).");
		}
		pw.println(" * @access public");
		pw.println(" */");

		pw.println("class " + camelCaseTabla + " extends VO {"  );

		{
			pw.println( "	/**");
			pw.println( "	 * Constructor de " + toCamelCase(tabla) );
			pw.println( "	 *" );
			pw.println( "	 * Para construir un objeto de tipo "+ toCamelCase(tabla) + " debera llamarse a el constructor" );
			pw.println( "	 * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo" );
			pw.println( "	 * cuyos campos son iguales a las variables que constituyen a este objeto." );
			pw.println( "	 */");
			pw.println("	function __construct($data = null) {");
			pw.println("		if (is_null($data)) {");
			pw.println("			return;");
			pw.println("		}");
		}

		for(Field f : fields)
		{
			pw.println("		if (isset($data['" + f.title + "'])) {");
			pw.println("			$this->"+f.title+" = $data['"+ f.title +"'];");
			pw.println("		}");
		}

		pw.println("	}");
		pw.println("");

		{
			pw.println( "	/**");
			pw.println( "	 * Obtener una representacion en String" );
			pw.println( "	 *" );
			pw.println( "	 * Este metodo permite tratar a un objeto "+toCamelCase(tabla)+ " en forma de cadena." );
			pw.println( "	 * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.");
			pw.println( "	 * @return String");
			pw.println( "	 */");
			pw.println("	public function __toString() {");

			pw.println( "		return json_encode([");

			int x = 0;
			for( Field f : fields)
			{
				pw.println( "			'"+ f.title+ "' => $this->" + f.title +  "," );
			}
			pw.println( "		]);" );
			pw.println("	}");
			pw.println();
		}

		if( !useUnixTimestamps )
		{
			pw.println("	/**");
			pw.println("	 * Converts date fields to timestamps");
			pw.println("	 */");
			pw.println("	public function toUnixTime(array $fields = []) {");
			pw.println("		if (count($fields) > 0) {");
			pw.println("			parent::toUnixTime($fields);");
			pw.println("		} else {");

			String time_fields = "  ";
			for (Field f : fields) {
				if (f.type.equals("timestamp")) {
					time_fields += "'" + f.title + "', ";
				}
			}

			pw.println("			parent::toUnixTime([" + time_fields.substring(0, time_fields.length() - 2).trim() + "]);");
			pw.println("		}");
			pw.println("	}");
		}

		for( Field f : fields)
		{
			pw.println();
			pw.println( "	/**");
			pw.println( "	  * "+f.comment );

			if(f.isPrimary)
				pw.println( "	  * Llave Primaria");

			if(f.isAutoIncrement)
				pw.println( "	  * Auto Incremento");

			pw.println( "	  * @access public" );
			if (f.type.length() > 0)
				pw.println( "	  * @var "+ f.type );
			pw.println( "	  */");

			pw.println( "	public $" + f.title + ";" );
		}

		pw.println("}");
		pw.close();
	}

	//dao normal para tabla
	private void writeDAO(String tabla, ArrayList<Field> fields, boolean has_pk ) throws IOException
	{
		String fileName = path.getAbsolutePath() + "/dao/" + tabla + ".dao.php";

		File f = new File(fileName);
		if (f.exists())
		{
			System.out.println("Warning: El archivo /dao/" + tabla + ".dao.php ya existe.");
			return;
		}

		includes.add(fileName);

		String className = toCamelCase( tabla );
		PrintWriter pw = new PrintWriter(new TabFilterWriter(new FileWriter( fileName ), this.useSpaces, 4));

		pw.println("<?php");
		pw.println();
		pw.println("include_once('base/"+tabla+".dao.base.php');");
		pw.println("include_once('base/"+tabla+".vo.base.php');");

		pw.println("/** "+ toCamelCase(tabla) +" Data Access Object (DAO)." );
		pw.println("  *");
		pw.println("  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para");
		pw.println("  * almacenar de forma permanente y recuperar instancias de objetos {@link "+toCamelCase(tabla)+" }.");
		pw.println("  * @access public");
		pw.println("  *");
		pw.println("  */");

		pw.println("class " + className+ "DAO extends " + className+"DAOBase {" );
		pw.println("}");

		pw.flush();
		pw.close();
	}

	//dao base para tabla
	private void writeDAOBase(String tabla, ArrayList<Field> fields, boolean has_pk ) throws IOException
	{

		String fileName = path.getAbsolutePath() + "/dao/base/" + tabla + ".dao.base.php";
		String className = toCamelCase( tabla );

		PrintWriter pw = new PrintWriter(new TabFilterWriter(new FileWriter( fileName ), this.useSpaces, 4));
		String [] pksArray = null;

		if (has_pk)
		{
			String pksVars = "";
			for(Field f : fields){
				if(!f.isPrimary)
					continue;
				pksVars +=  " $"+f.title+",";
			}

			pksArray = pksVars.replace(" $", "").split(",");
			pksVars = pksVars.substring( 0, pksVars.length() -1 );
		}

		pw.println("<?php");
		pw.println();
		pw.println("/** ******************************************************************************* *");
		pw.println("  *                    !ATENCION!                                                   *");
		pw.println("  *                                                                                 *");
		pw.println("  * Este codigo es generado automaticamente. Si lo modificas tus cambios seran      *");
		pw.println("  * reemplazados la proxima vez que se autogenere el codigo.                        *");
		pw.println("  *                                                                                 *");
		pw.println("  * ******************************************************************************* */");
		pw.println();
		pw.println("/** "+ toCamelCase(tabla) +" Data Access Object (DAO) Base." );
		pw.println(" *");
		pw.println(" * Esta clase contiene toda la manipulacion de bases de datos que se necesita para");
		pw.println(" * almacenar de forma permanente y recuperar instancias de objetos {@link "+toCamelCase(tabla)+" }.");
		pw.println(" * @access public");
		pw.println(" * @abstract");
		pw.println(" *");
		pw.println(" */");

		pw.println("abstract class " + className+ "DAOBase extends DAO {" );

		{
			pw.println("	/**");
			pw.println("	 * Campos de la tabla.");
			pw.println("	 */");
			pw.println("	const FIELDS = '" + getFields(tabla, fields) + "';");
			pw.println();
		}

		{
			pw.println("	/**");
			pw.println("	 * Guardar registros.");
			pw.println("	 *");
			if (has_pk)
			{
				pw.println("	 * Este metodo guarda el estado actual del objeto {@link "+toCamelCase(tabla)+"} pasado en la base de datos. La llave");
				pw.println("	 * primaria indicara que instancia va a ser actualizado en base de datos. Si la llave primara o combinacion de llaves");
				pw.println("	 * primarias describen una fila que no se encuentra en la base de datos, entonces save() creara una nueva fila, insertando");
				pw.println("	 * en ese objeto el ID recien creado.");
			}
			else
			{
				pw.println("	 * Este metodo guarda el estado actual del objeto {@link "+toCamelCase(tabla)+"} pasado en la base de datos.");
				pw.println("	 * save() siempre creara una nueva fila.");
			}
			pw.println("	 *");
			pw.println("	 * @static");
			pw.println("	 * @throws Exception si la operacion fallo.");
			pw.println("	 * @param "+toCamelCase(tabla)+" [$"+tabla+"] El objeto de tipo " + toCamelCase(tabla));
			pw.println("	 * @return Un entero mayor o igual a cero denotando las filas afectadas.");
			pw.println("	 */");

			pw.println("	final public static function save(" + toCamelCase(tabla) + " $"+tabla+") {");

			if (has_pk)
			{
				String pks = "";

				for(Field f : fields){
					if(!f.isPrimary) continue;
					pks +=  " $"+tabla+"->"+ f.title+",";
				}

				pks = pks.substring(1, pks.length() -1 );
				pw.println("		if (!is_null(self::getByPK(" + pks + "))) {");
				pw.println("			return " + className +"DAOBase::update($"+ tabla +");");
				pw.println("		} else {");
				pw.println("			return " + className +"DAOBase::create($"+ tabla +");");
				pw.println("		}");
			}
			else
			{
				pw.println("		return " + className +"DAOBase::create($"+ tabla +");");
			}

			pw.println("	}");
		}

		/* ********************************************
		 *	getByPK()
		 ******************************************** */
		if (has_pk)
		{

			String pks = "";
			String sql = "";
			String nulls = "";
			String pks_redis = "";

			for(Field f : fields) {
				if(!f.isPrimary) continue;

				pks +=          " $"+f.title+",";
				pks_redis +=    " . $" + f.title+".\"-\"";
				sql +=          " " + f.title +" = ? AND";
				nulls +=        " is_null($"+ f.title +") ||" ;
			}

			pks = pks.substring( 1, pks.length() -1 );
			sql = sql.substring( 1, sql.length() -4 );
			nulls = nulls.substring( 1, nulls.length() -3 ) ;
			pks_redis = pks_redis.substring( 1, pks_redis.length() - 4 ) ;

			pw.println();
			pw.println("	/**");
			pw.println("	 * Obtener {@link "+toCamelCase(tabla)+"} por llave primaria.");
			pw.println("	 *");
			pw.println("	 * Este metodo cargara un objeto {@link "+toCamelCase(tabla)+"} de la base de datos");
			pw.println("	 * usando sus llaves primarias.");
			pw.println("	 *");
			pw.println("	 * @static");
			pw.println("	 * @return @link "+toCamelCase(tabla)+" Un objeto del tipo {@link "+toCamelCase(tabla)+"}. NULL si no hay tal registro.");
			pw.println("	 */");

			pw.println("	final public static function getByPK(" + pks + ") {");
			pw.println("		if ("+ nulls +") {");
			pw.println("			return null;");
			pw.println("		}");

			pw.println("		$sql = 'SELECT " + getFields(tabla, fields) + " FROM "+tabla+" WHERE ("+ sql + ") LIMIT 1;';");
			pw.println("		$params = ["+ pks +"];");
			pw.println("		global $conn;");
			pw.println("		$rs = $conn->GetRow($sql, $params);");
			pw.println("		if (count($rs) == 0) {");
			pw.println("			return null;");
			pw.println("		}");
			pw.println("		return new " + toCamelCase(tabla) + "($rs);");
			pw.println("	}");
		}

		/* ********************************************
		 *	getAll()
		 ******************************************** */
		{
			pw.println();
			pw.println("	/**");
			pw.println("	 * Obtener todas las filas.");
			pw.println("	 *");
			pw.println("	 * Esta funcion leera todos los contenidos de la tabla en la base de datos y construira");
			pw.println("	 * un vector que contiene objetos de tipo {@link "+toCamelCase(tabla)+"}. Tenga en cuenta que este metodo");
			pw.println("	 * consumen enormes cantidades de recursos si la tabla tiene muchas filas.");
			pw.println("	 * Este metodo solo debe usarse cuando las tablas destino tienen solo pequenas cantidades de datos o se usan sus parametros para obtener un menor numero de filas.");
			pw.println("	 *");
			pw.println("	 * @static");
			pw.println("	 * @param $pagina Pagina a ver.");
			pw.println("	 * @param $columnas_por_pagina Columnas por pagina.");
			pw.println("	 * @param $orden Debe ser una cadena con el nombre de una columna en la base de datos.");
			pw.println("	 * @param $tipo_de_orden 'ASC' o 'DESC' el default es 'ASC'");
			pw.println("	 * @return Array Un arreglo que contiene objetos del tipo {@link "+toCamelCase(tabla)+"}.");
			pw.println("	 */");
			pw.println("	final public static function getAll($pagina = null, $columnas_por_pagina = null, $orden = null, $tipo_de_orden = 'ASC') {");
			pw.println("		$sql = 'SELECT " + getFields(tabla, fields) + " from "+tabla+"';");

			pw.println("		if (!is_null($orden)) {");
			pw.println("			$sql .= ' ORDER BY `' . mysql_real_escape_string($orden) . '` ' . mysql_real_escape_string($tipo_de_orden);");
			pw.println("		}");

			pw.println("		if (!is_null($pagina)) {");
			pw.println("			$sql .= ' LIMIT ' . (($pagina - 1) * $columnas_por_pagina) . ', ' . (int)$columnas_por_pagina;");
			pw.println("		}");
			pw.println("		global $conn;");
			pw.println("		$rs = $conn->Execute($sql);");
			pw.println("		$allData = [];");
			pw.println("		foreach ($rs as $row) {");
			pw.println("    		$allData[] = new "+ toCamelCase( tabla ) +"($row);");
			pw.println("		}");
			pw.println("		return $allData;");
			pw.println("	}");
		}

		{
			pw.println();
			pw.println("	/**");
			pw.println("	  * Buscar registros.");
			pw.println("	  *");
			pw.println("	  * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link "+toCamelCase(tabla)+"} de la base de datos.");
			pw.println("	  * Consiste en buscar todos los objetos que coinciden con las variables permanentes instanciadas de objeto pasado como argumento.");
			pw.println("	  * Aquellas variables que tienen valores NULL seran excluidos en busca de criterios.");
			pw.println("	  *");

			pw.println("	  * <code>");
			pw.println("	  *   // Ejemplo de uso - buscar todos los clientes que tengan limite de credito igual a 20000");

			pw.println("	  *   $cliente = new Cliente();");
			pw.println("	  *   $cliente->setLimiteCredito('20000');");
			pw.println("	  *   $resultados = ClienteDAO::search($cliente);");
			pw.println("	  *");
			pw.println("	  *   foreach ($resultados as $c){");
			pw.println("	  *       echo $c->nombre . '<br>';");
			pw.println("	  *   }");

			pw.println("	  * </code>");
			pw.println("	  * @static");
			pw.println("	  * @param "+toCamelCase(tabla)+" [$"+tabla+"] El objeto de tipo " + toCamelCase(tabla));
			pw.println("	  * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.");
			pw.println("	  * @param $orden 'ASC' o 'DESC' el default es 'ASC'");

			pw.println("	  */");

			pw.println("	final public static function search($"+tabla+", $orderBy = null, $orden = 'ASC', $offset = 0, $rowcount = null, $likeColumns = null) {");

			pw.println("		if (!($"+tabla+" instanceof "+toCamelCase(tabla)+")) {");
			pw.println("			return self::search(new "+toCamelCase(tabla)+"($"+tabla+"));");
			pw.println("		}");
			pw.println();

			pw.println("		$clauses = [];");
			pw.println("		$params = [];");

			for(Field f : fields)
			{
				pw.println("		if (!is_null($"+tabla+"->"+f.title+")) {");
				pw.println("			$clauses[] = '`"+ f.title +"` = ?';");
				pw.println("			$params[] = $"+tabla+"->"+f.title+";");
				pw.println("		}");
			}

			pw.println("		if (!is_null($likeColumns)) {");
			pw.println("			foreach ($likeColumns as $column => $value) {");
			pw.println("				$escapedValue = mysql_real_escape_string($value);");
			pw.println("				$clauses[] = \"`{$column}` LIKE '%{$value}%'\";");
			pw.println("			}");
			pw.println("		}");

			pw.println("		if (sizeof($clauses) == 0) {");
			pw.println("			return self::getAll();");
			pw.println("		}");
			pw.println("		$sql = 'SELECT " + getFields(tabla, fields) + " FROM `"+tabla+"`';");
			pw.println("		$sql .= ' WHERE (' . implode(' AND ', $clauses) . ')';" );

			pw.println("		if (!is_null($orderBy)) {");
			pw.println("			$sql .= ' ORDER BY `' . mysql_real_escape_string($orderBy) . '` ' . mysql_real_escape_string($orden);");
			pw.println("		}");

			pw.println("		// Add LIMIT offset, rowcount if rowcount is set");
			pw.println("		if (!is_null($rowcount)) {");
			pw.println("			$sql .= ' LIMIT '. (int)$offset . ', ' . (int)$rowcount;");
			pw.println("		}");

			pw.println("		global $conn;");
			pw.println("		$rs = $conn->Execute($sql, $params);");

			pw.println("		$ar = [];");
			pw.println("		foreach ($rs as $row) {");
			pw.println("			$ar[] = new "+ toCamelCase( tabla ) +"($row);");
			pw.println("		}");
			pw.println("		return $ar;");

			pw.println("	}");
		}

		if (has_pk)
		{
			pw.println();
			pw.println("	/**");
			pw.println("	  * Actualizar registros.");
			pw.println("	  *");
			pw.println("	  * @return Filas afectadas");
			pw.println("	  * @param "+toCamelCase(tabla)+" [$"+tabla+"] El objeto de tipo " + toCamelCase(tabla) + " a actualizar.");
			pw.println("	  */");

			pw.println("	final private static function update(" + toCamelCase(tabla) + " $"+tabla+") {");

			String sql = "";
			String args = "";
			String pk = "";
			String pkargs = "";

			for(Field f : fields){
				if( f.isPrimary )
				{
					pk += " `" + f.title + "` = ? AND";
					pkargs += "$"+tabla+"->" + f.title + ",";
				}else{
					args += "$"+tabla+"->"+ f.title + ",\n			";
					if( useUnixTimestamps && ( f.type.equals("timestamp") || f.type.equals("date") || f.type.equals("datetime") ) )
					{
						sql += "`"+f.title+"` = FROM_UNIXTIME(?), ";
					}else{
						sql += "`"+f.title+"` = ?, ";
					}
				}
			}

			if(args.length()==0){

			}else{
				args = args.substring(0, args.length() -1 ) ;
				pk = pk.substring(1, pk.length() - 4 ) ;
				sql = sql.substring(0, sql.length() -2 );

				pw.println("		$sql = 'UPDATE `"+tabla+"` SET "+ sql + " WHERE " +pk+ ";';" );
				pw.println("		$params = [\n			"+ args +"	"+ pkargs +"\n		];");
				pw.println("		global $conn;");
				pw.println("		$conn->Execute($sql, $params);");
				pw.println("		return $conn->Affected_Rows();");
			}

			pw.println("	}");
		}

		{
			pw.println();
			pw.println("	/**");
			pw.println("	 * Crear registros.");
			pw.println("	 *");
			pw.println("	 * Este metodo creara una nueva fila en la base de datos de acuerdo con los");
			pw.println("	 * contenidos del objeto "+toCamelCase(tabla)+" suministrado. Asegurese");
			pw.println("	 * de que los valores para todas las columnas NOT NULL se ha especificado");
			pw.println("	 * correctamente. Despues del comando INSERT, este metodo asignara la clave");
			pw.println("	 * primaria generada en el objeto "+toCamelCase(tabla)+" dentro de la misma transaccion.");
			pw.println("	 *");
			pw.println("	 * @return Un entero mayor o igual a cero identificando las filas afectadas, en caso de error, regresara una cadena con la descripcion del error");
			pw.println("	 * @param "+toCamelCase(tabla)+" [$"+tabla+"] El objeto de tipo " + toCamelCase(tabla) +" a crear." );
			pw.println("	 */");
			pw.println("	final private static function create(" + toCamelCase(tabla) + " $"+tabla+") {");

			String sql = "";
			String args = "";
			String sqlnames = "";
			String pk_ai = "";

			for(Field f : fields) {
				String fieldName = "$"+tabla+"->"+ f.title;

				if(f.isAutoIncrement) {
					pk_ai += "		" + fieldName + " = $conn->Insert_ID();\n";

				} else if (f.defaultValue != null) {
					String defaultValue = f.defaultValue.equals("CURRENT_TIMESTAMP") ? (useUnixTimestamps ? "time()" : "gmdate('Y-m-d H:i:s')") : f.defaultValue;
					pw.println("		if (is_null(" + fieldName + ")) {");
					pw.println("			" + fieldName + " = " + defaultValue + ";");
					pw.println("		}");
				}

				args += fieldName + ",\n			";
				sqlnames += "`"+f.title+"`, ";
				if( useUnixTimestamps && ( f.type.equals("timestamp") || f.type.equals("date") || f.type.equals("datetime") ) )
				{
					sql += "FROM_UNIXTIME(?), ";
				}else{
					sql += "?, ";
				}
			}

			sqlnames = sqlnames.substring(0, sqlnames.length() -2 ) ;
			args = args.substring(0, args.length() -1 ) ;
			sql = sql.substring(0, sql.length() -2 );

			pw.println("		$sql = 'INSERT INTO "+tabla+" ("+ sqlnames + ") VALUES ("+ sql +");';" );
			pw.println("		$params = [\n			"+ args +"];");
			pw.println("		global $conn;");
			pw.println("		$conn->Execute($sql, $params);");
			pw.println("		$ar = $conn->Affected_Rows();");
			pw.println("		if ($ar == 0) {");
			pw.println("			return 0;");
			pw.println("		}");
			pw.println(pk_ai);
			pw.println("		return $ar;");
			pw.println("	}");
		}

		{
			pw.println();
			pw.println("	/**");
			pw.println("	 * Buscar por rango.");
			pw.println("	 *");
			pw.println("	 * Este metodo proporciona capacidad de busqueda para conseguir un juego de objetos {@link "+toCamelCase(tabla)+"} de la base de datos siempre y cuando");
			pw.println("	 * esten dentro del rango de atributos activos de dos objetos criterio de tipo {@link "+toCamelCase(tabla)+"}.");
			pw.println("	 *");
			pw.println("	 * Aquellas variables que tienen valores NULL seran excluidos en la busqueda (los valores 0 y false no son tomados como NULL) .");
			pw.println("	 * No es necesario ordenar los objetos criterio, asi como tambien es posible mezclar atributos.");
			pw.println("	 * Si algun atributo solo esta especificado en solo uno de los objetos de criterio se buscara que los resultados conicidan exactamente en ese campo.");
			pw.println("	 *");

			pw.println("	 * <code>");
			pw.println("	 *   // Ejemplo de uso - buscar todos los clientes que tengan limite de credito");
			pw.println("	 *   // mayor a 2000 y menor a 5000. Y que tengan un descuento del 50%.");

			pw.println("	 *   $cr1 = new Cliente();");
			pw.println("	 *   $cr1->limite_credito = \"2000\";");
			pw.println("	 *   $cr1->descuento = \"50\";");
			pw.println("	 *");
			pw.println("	 *   $cr2 = new Cliente();");
			pw.println("	 *   $cr2->limite_credito = \"5000\";");
			pw.println("	 *   $resultados = ClienteDAO::byRange($cr1, $cr2);");
			pw.println("	 *");
			pw.println("	 *   foreach($resultados as $c ){");
			pw.println("	 *   	echo $c->nombre . \"<br>\";");
			pw.println("	 *   }");

			pw.println("	 * </code>");
			pw.println("	 * @static");
			pw.println("	 * @param "+toCamelCase(tabla)+" [$"+tabla+"] El objeto de tipo " + toCamelCase(tabla));
			pw.println("	 * @param "+toCamelCase(tabla)+" [$"+tabla+"] El objeto de tipo " + toCamelCase(tabla));
			pw.println("	 * @param $orderBy Debe ser una cadena con el nombre de una columna en la base de datos.");
			pw.println("	 * @param $orden 'ASC' o 'DESC' el default es 'ASC'");
			pw.println("	 */");

			pw.println("	final public static function byRange(" + toCamelCase(tabla) + " $"+tabla+"A, " + toCamelCase(tabla) + " $"+tabla+"B, $orderBy = null, $orden = 'ASC') {");
			pw.println("		$clauses = [];");
			pw.println("		$params = [];");
			pw.println();

			for(Field f : fields)
			{
				pw.println("		$a = $"+tabla+"A->"+f.title+";");
				pw.println("		$b = $"+tabla+"B->"+f.title+";");
				pw.println("		if (!is_null($a) && !is_null($b)) {");
				pw.println("			$clauses[] = '`"+ f.title +"` >= ? AND `"+ f.title +"` <= ?';");
				pw.println("			$params[] = min($a, $b);");
				pw.println("			$params[] = max($a, $b);");
				pw.println("		} elseif (!is_null($a) || !is_null($b)) {");
				pw.println("			$clauses[] = '`"+ f.title +"` = ?';");
				pw.println("			$params[] = is_null($a) ? $b : $a;");
				pw.println("		}");
				pw.println();
			}

			pw.println("		$sql = 'SELECT * FROM `"+tabla+"`';");
			pw.println("		$sql .= ' WHERE (' . implode(' AND ', $clauses) . ')';" );
			pw.println("		if (!is_null($orderBy)) {");
			pw.println("		    $sql .= ' ORDER BY `' . $orderBy . '` ' . $orden;");
			pw.println("		}");
			pw.println("		global $conn;");
			pw.println("		$rs = $conn->Execute($sql, $params);");

			pw.println("		$ar = [];");
			pw.println("		foreach ($rs as $row) {");
			pw.println("			$ar[] = new "+ toCamelCase( tabla ) +"($row);");
			pw.println("		}");
			pw.println("		return $ar;");
			pw.println("	}");
		}

		if (has_pk)
		{
			pw.println();
			pw.println("	/**");
			pw.println("	 * Eliminar registros.");
			pw.println("	 *");
			pw.println("	 * Este metodo eliminara la informacion de base de datos identificados por la clave primaria");
			pw.println("	 * en el objeto "+toCamelCase(tabla)+" suministrado. Una vez que se ha suprimido un objeto, este no");
			pw.println("	 * puede ser restaurado llamando a save(). save() al ver que este es un objeto vacio, creara una nueva fila");
			pw.println("	 * pero el objeto resultante tendra una clave primaria diferente de la que estaba en el objeto eliminado.");
			pw.println("	 * Si no puede encontrar eliminar fila coincidente a eliminar, Exception sera lanzada.");
			pw.println("	 *");
			pw.println("	 * @throws Exception Se arroja cuando el objeto no tiene definidas sus llaves primarias.");
			pw.println("	 * @return int El numero de filas afectadas.");
			pw.println("	 * @param "+toCamelCase(tabla)+" [$"+tabla+"] El objeto de tipo " + toCamelCase(tabla)+ " a eliminar");
			pw.println("	 */");

			pw.println("	final public static function delete(" + toCamelCase(tabla) + " $"+tabla+") {");

			String sql = "";
			String args = "";
			String pk = "";
			String pkargs = "";

			for(Field f : fields)
			{
				if (f.isPrimary)
				{
					pk += " " + f.title + " = ? AND";
					pkargs += "$"+tabla+"->" + f.title+", ";
				}
			}

			if (pkargs.length() == 0)
			{

			}else{
				pkargs = pkargs.substring(0, pkargs.length() -2 ) ;
				pk = pk.substring(1, pk.length() - 4 ) ;

				pw.println("		if (is_null(self::getByPK("+ pkargs +"))) {");
				pw.println("			throw new Exception('Campo no encontrado.');");
				pw.println("		}");

				pw.println("		$sql = 'DELETE FROM `"+tabla+"` WHERE " +pk+ ";';" );
				pw.println("		$params = ["+ pkargs +"];");
				pw.println("		global $conn;");
				pw.println();
				pw.println("		$conn->Execute($sql, $params);");
				pw.println("		return $conn->Affected_Rows();");
			}

			pw.println("	}");
		}

		pw.println("}");
		pw.close();
	}

	//parse view
	private void parseView(String vname) throws IOException
	{
	}

	//vo para vista
	private void parseViewTableVO( String tabla, ArrayList<Field> fields ) throws IOException
	{
	}

	//ado y adobase para vista
	private void parseViewTableADO( String tabla, ArrayList<Field> fields ) throws IOException
	{
	}

	private void parseContent() throws IOException
	{
		System.out.println("Starting...");

		File f = new File(path.getAbsolutePath()+"/dao");
		f.mkdir();

		f = new File(path.getAbsolutePath()+"/dao/base");
		f.mkdir();

		String line ;

		int table_count = 0;
		int views_count = 0;

		while( (line = br.readLine()) != null )
		{
			if( line.indexOf("CREATE TABLE") != -1 )
			{
				table_count++;
				String t_name = line.substring( line.indexOf("`") + 1, line.lastIndexOf("`") );
				parseTable( t_name );
			}
		}

		//escribir el archivo de la estructura de las clases
		PrintWriter pw = new PrintWriter(new TabFilterWriter(new FileWriter(path.getAbsolutePath()+"/dao/Estructura.php"), this.useSpaces, 4));

		pw.println("<?php");
		pw.println();
		pw.println("/** ******************************************************************************* *");
		pw.println("  *                    !ATENCION!                                                   *");
		pw.println("  *                                                                                 *");
		pw.println("  * Este codigo es generado automaticamente. Si lo modificas tus cambios seran      *");
		pw.println("  * reemplazados la proxima vez que se autogenere el codigo.                        *");
		pw.println("  *                                                                                 *");
		pw.println("  * ******************************************************************************* */");
		pw.println();

		pw.println("/** Table Data Access Object.");
		pw.println(" *");
		pw.println(" * Esta clase abstracta comprende metodos comunes para todas las clases DAO que mapean una tabla");
		pw.println(" * @access private");
		pw.println(" * @abstract");
		pw.println(" */");
		pw.println("abstract class DAO {");

		pw.println("	protected static function log($m = null) {");
		pw.println("		// Your logging call here.");
		pw.println("	}");

		pw.println();

		pw.println("	public static function transBegin() {");
		pw.println("		self::log('Iniciando transaccion');");
		pw.println("		global $conn;");
		pw.println("		$conn->StartTrans();");
		pw.println("	}");

		pw.println();

		pw.println("	public static function transEnd(){");
		pw.println("		self::log('Transaccion commit');");
		pw.println("		global $conn;");
		pw.println("		$conn->CompleteTrans();");
		pw.println("	}");

		pw.println();

		pw.println("	public static function transRollback(){");
		pw.println("		self::log('Transaccion rollback');");
		pw.println("		global $conn;");
		pw.println("		$conn->FailTrans();");
		pw.println("	}");

		pw.println("}");

		pw.println("");

		pw.println("/** Value Object.");
		pw.println(" *");
		pw.println(" * Esta clase abstracta comprende metodos comunes para todas los objetos VO");
		pw.println(" * @access private");
		pw.println(" * @package docs");
		pw.println(" *");
		pw.println(" */");
		pw.println("abstract class VO {");

		pw.println("	function asArray(){");
		pw.println("		return get_object_vars($this);");
		pw.println("	}");

		if (!omitGeneratedCall)
		{
			pw.println();
			pw.println("	function __call($method, $params) {");
			pw.println("		 $var = substr($method, 3);");
			pw.println("		 $var = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $var));");
			pw.println();
			pw.println("		 if (strncasecmp($method, \"get\", 3)==0) {");
			pw.println("			 return $this->$var;");
			pw.println("		 } else if (strncasecmp($method, \"set\", 3)==0) {");
			pw.println("			 $this->$var = $params[0];");
			pw.println("		 } else {");
			pw.println("			 throw new BadMethodCallException($method);");
			pw.println("		 }");
			pw.println("	}");
		}

		pw.println();
		pw.println("	public function asFilteredArray($filters) {");
		pw.println("		// Get the complete representation of the array");
		pw.println("		$completeArray = get_object_vars($this);");
		pw.println("		// Declare an empty array to return");
		pw.println("		$returnArray = [];");
		pw.println("		foreach ($filters as $filter) {");
		pw.println("			// Only return properties included in $filters array");
		pw.println("			if (isset($completeArray[$filter])) {");
		pw.println("				$returnArray[$filter] = $completeArray[$filter];");
		pw.println("			} else {");
		pw.println("				$returnArray[$filter] = null;");
		pw.println("			}");
		pw.println("		}");
		pw.println("		return $returnArray;");
		pw.println("	}");

		if( !useUnixTimestamps )
		{
			pw.println();
			pw.println("	protected function toUnixTime(array $fields) {");
			pw.println("		foreach ($fields as $f) {");
			pw.println("			$this->$f = strtotime($this->$f);");
			pw.println("		}");
			pw.println("	}");
		}

		pw.println("}");

		pw.flush();
		pw.close();

		System.out.println("\nParsed tables: " + table_count);
		System.out.println("\nParsed views: " + views_count);
	}

	private void writeIncludes(  )
	{
		try{
			PrintWriter pw = new PrintWriter(new TabFilterWriter(new FileWriter(path.getAbsolutePath()+"/dao/model.inc.php"), this.useSpaces, 4));

			pw.println("<?php");
			pw.println("require_once('Estructura.php');");
			pw.println();
			pw.println("spl_autoload_register(function ($class) {");
			pw.println("	if (substr($class, -3) == 'DAO') {");
			pw.println("		$class = substr($class, 0, -3);");
			pw.println("	}");
			pw.println("	$file_name = preg_replace('/([a-z])([A-Z])/', '$1_$2', $class);");

			pw.println("");
			pw.println("	if (file_exists(__DIR__ . '/' . $file_name . '.dao.php')) {");
			pw.println("		include __DIR__ . '/' . $file_name . '.dao.php';");
			pw.println("	}");

			pw.println("});");

			pw.close();
		}catch(IOException ioe){
			System.out.println("\n\nInclues\t"+ioe);
		}
	}

	public boolean playParser(File sql, File dir, String aut)
	{
		br = null;
		if(!aut.isEmpty())
			author = aut;

		includes = new ArrayList<String>();

		if (!(sql.isFile() && sql.getName().endsWith(".sql")))
		{
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
			System.out.println("Error al parsear...\n"+ioe);
		}

		writeIncludes();
		closeFile();
		return true;

	}

	private String getFields( String tabla, ArrayList<Field> fields )
	{
		StringBuffer fieldList = new StringBuffer();
		for( Field field : fields )
		{
			if( fieldList.length() != 0 )
			{
				fieldList.append(", ");
			}
			if( useUnixTimestamps && ( field.type.equals("timestamp") || field.type.equals("date") || field.type.equals("datetime") ) )
			{
				fieldList.append("UNIX_TIMESTAMP(`" + tabla + "`.`" + field.title + "`) AS `" + field.title + "`");
			}
			else
			{
				fieldList.append("`" + tabla + "`.`" + field.title + "`");
			}
		}
		return fieldList.toString();
	}

	public void setOmitGeneratedCall( boolean omitGeneratedCall )
	{
		this.omitGeneratedCall = omitGeneratedCall;
	}

	public void setUseUnixTimestamps( boolean useUnixTimestamps )
	{
		this.useUnixTimestamps = useUnixTimestamps;
	}

	public void setUseSpaces( boolean useSpaces )
	{
		this.useSpaces = useSpaces;
	}
}

class Field{

	String title;
	String type;
	String comment;
	boolean isPrimary;
	boolean isAutoIncrement;
	String defaultValue;

	Field( String title )
	{
		this.title = title;
	}

	Field( String title, String type, String comment, boolean isPrimary, boolean isAutoIncrement, String defaultValue )
	{
		this.title = title;
		this.type = type;
		this.comment = comment;
		this.isPrimary = isPrimary;
		this.isAutoIncrement = isAutoIncrement;
		this.defaultValue = defaultValue;
	}

}

class TabFilterWriter extends FilterWriter
{
	boolean replaceTabsWithSpaces;
	String replacement;

	TabFilterWriter( Writer out, boolean replaceTabsWithSpaces, int indentWidth )
	{
		super(out);
		this.replaceTabsWithSpaces = replaceTabsWithSpaces;
		this.replacement = "";
		for (int i = 0; i < indentWidth; i++)
		{
			this.replacement += " ";
		}
	}

	@Override
	public void write(int c) throws IOException
	{
		if (!this.replaceTabsWithSpaces || c != '\t')
		{
			super.write(c);
			return;
		}
		out.write(this.replacement);
	}

	@Override
	public void write(char cbuf[], int off, int len) throws IOException
	{
		if (!this.replaceTabsWithSpaces)
		{
			super.write(cbuf, off, len);
			return;
		}
		for (int i = 0; i < len; i++)
		{
			if (cbuf[i + off] == '\t')
				out.write(this.replacement);
			else
				out.write(cbuf[i + off]);
		}
	}

	@Override
	public void write(String str, int off, int len) throws IOException
	{
		if (!this.replaceTabsWithSpaces)
		{
			super.write(str, off, len);
			return;
		}
		for (int i = 0; i < len; i++)
		{
			if (str.charAt(i + off) == '\t')
				out.write(this.replacement);
			else
				out.write(str.charAt(i + off));
		}
	}
}

// phpdoc -f *.php, base/*.php -s on -pp on -dn docs -ti "Point of Sale - Data Access Object Model" -t docs/
