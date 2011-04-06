package testing;

import java.io.*;
import java.net.*;
import com.google.gson.*;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.swing.JTextArea;
//import com.google.gson.stream.*;

/*
 javac -cp gson-1.6.jar test.java && java -cp .:gson-1.6.jar Test test.txt
*/

public class Test{

	private BufferedReader br;
	private String cookie = null;
	private int verbose;
	private String configCodeBase;
	private String configFileName;
	private int currentLine = 0;
	private int testOnLine;
        private File fi;
	//public JTextArea data = new JTextArea();
        private boolean exitonerror;
	
	/*   
	public static void main(String ... args) throws Exception{
	
		
		try{
			br = new BufferedReader(new FileReader(args[0]));
		}catch(Exception e){
			System.out.println("Imposible leer el archivo de entrada.");
		}
		
		boolean exitonerror = true;
		
		try{
			if( args[1].equals("--noexit") ){
				exitonerror = false;
			}
			
			if( args[1].equals("--noverbose") ){
				normalVerbose = false;
			}			
		}catch(Exception e){
		
		}
		
		TestCase caso;
		while((caso = nextTest()) != null){

			if(!test( caso )){
				if(exitonerror){
					return;
				}	
			}
			
		}
	}
	*/
	
	/* contructor recibe:
	*	-archivo a probar (file)
	*	-exit on error	(true|false)
	*	-verbose (int):
	*		+ 1 : no verbose
	*		+ 2 : normal verbose //default
	*		+ 3 : full verbose
	*/

	public Test(File file, boolean _exitonerror, int _verbose) throws Exception {
        	try {
            		br = new BufferedReader(new FileReader(fi=file));
            		exitonerror = _exitonerror;
			verbose=(_verbose > 3 || _verbose < 1) ? 2 : _verbose ;
        	} catch (Exception e) {
			Data.data.append("No se pudo abrir el archivo");
		}
    	}

	/*
     	* playTesting() solo ejecuta un caso de prueba por vez,
     	* permitiendo obtener los datos del caso probado para
     	* mostrarlo en la interfaz grafica... se espera
     	* que el complemento sea similar a:
	* while(test.playTesting()){
	*	jTextArea.append(test.data);
	* }
	* ó sugieren agregarlo a un objeto jTextArea y agregarlo
	* despues a la GUI
     	*/
    
    	public boolean playTesting() throws Exception {
        	TestCase caso;
        	if((caso = nextTest()) != null)
            		if (!test(caso))
                		return !exitonerror;
            		else
                		return true;
        	else
            		return false;
    	}
    	
    	//playTesting2() para usar con el atributo data estatico
    	public void playTesting2() throws Exception {
            TestCase caso;
            while ((caso = nextTest()) != null)
                if (!test(caso) && exitonerror)
                    return;
    	}
	
	public int getCases() throws IOException{
            int number_of_cases = 0;
            String line = "";
            while( (line = br.readLine()) != null ){
                if( line.trim().startsWith("#beginTest"))
                    number_of_cases++;
            }
            br = new BufferedReader(new FileReader(fi));
            return number_of_cases;
	}
	
	private TestCase nextTest(){
		try{
			TestCase foo = new TestCase();	
			String l;

			while(true){
				/* se estaba usando mucho la funcion trim() sin ser necesaria
				* if( l.trim().startsWith("/*") ){ ...
				* if( l.trim().startsWith("#startConfig") ){...
				* ya antes se le hizo el trim()
				*/

				if( (l = br.readLine()) == null) {
					return null;
				} else {
					l.trim();
					currentLine++;
				}

				if( l.startsWith("/*") ){
					//no esta comprobando si se recibe linea nula
					while(!(l = br.readLine()).trim().startsWith("*/")){
						currentLine ++;
					}
					continue; 
				}


				if( l.startsWith("#startConfig") ){
					//no esta comprobando si se recibe linea nula
					while(!(l = br.readLine()).trim().startsWith("#endConfig")){
						currentLine ++;
						String configOption = l.trim().substring( 0, l.indexOf(' ') );
						if( configOption.startsWith("#codeBase") ){
							configCodeBase = l.trim().substring( l.indexOf(' ') ).trim();
						}
						if( configOption.startsWith("#fileName") ){
							configFileName = l.trim().substring( l.indexOf(' ') ).trim();
						}
					}
					continue;
				}
				
				if( l.equals("#beginTest") ){
					testOnLine = currentLine;
					break;
				}
			}
			
						
			//leer la descripcion
			while(!(l = br.readLine()).trim().equals("#endTest")){
				currentLine ++;
				
				if( l.trim().startsWith("#beginOutput") ){
					foo.expected = "";

					while(!(l = br.readLine()).trim().startsWith("#endOutput")){
						currentLine ++;					
						foo.expected += l + '\n';
					}
					
				}
				
				if(l.indexOf(' ') == -1){
					continue;
				}
				
				String configOption = l.trim().substring( 0, l.indexOf(' ') );

				if( configOption.startsWith("#Desc") ){
					foo.desc = l.trim().substring( l.indexOf(' ') ).trim();
				}

				if( configOption.startsWith("#Input") ){
					foo.input = l.trim().substring( l.indexOf(' ') ).trim();
				}
				
				if( configOption.startsWith("#Output") ){
					foo.expected = l.trim().substring( l.indexOf(' ') ).trim();
				}
				
				if( configOption.startsWith("#JSONOutput") ){
					foo.expected = l.trim().substring( l.indexOf(' ') ).trim();
					foo.expectedJson = true;
				}				
					
				
				if( configOption.startsWith("#fileName") ){
					foo.fileName = l.trim().substring( l.indexOf(' ') ).trim();
				}
				
			}
			
			if(foo.codeBase == null){
				foo.codeBase = configCodeBase;
			}

			if(foo.fileName == null){
				foo.fileName = configFileName;
			}			
			
			return foo;

		}catch(Exception e){
			System.out.println(e);
			return null;
		}
	}



	
	private boolean test ( TestCase caso ) throws IOException {
	
		URL                 url;
		URLConnection       urlConn;
		DataOutputStream    printout;
		DataInputStream     input;
		
		// URL of CGI-Bin script.
		url = new URL (caso.codeBase + caso.fileName);
		urlConn = url.openConnection();
		urlConn.setDoInput (true);
		urlConn.setDoOutput (true);
		urlConn.setUseCaches (false);
		
		urlConn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");
		urlConn.setRequestProperty("User-Agent", "thisial98789897");
		
		if(cookie != null)
			urlConn.setRequestProperty("Cookie", cookie );

		
		printout = new DataOutputStream ( urlConn.getOutputStream () );


		String content = caso.input; //URLEncoder.encode (args);
			
		printout.writeBytes (content);
		printout.flush ();
		printout.close ();
		
		// get response data
		input = new DataInputStream (urlConn.getInputStream ());
		
		if(verbose > 1){
			Data.data.append("\n============= Request Headers ================" +
                    	"\nUser-Agent:" + urlConn.getRequestProperty("User-Agent") +
                    	"\nCookie:" + urlConn.getRequestProperty("Cookie"));
			/*System.out.println("============= Request Headers ================");
			* System.out.println("User-Agent:" + urlConn.getRequestProperty("User-Agent"));
			* System.out.println("Cookie:" + urlConn.getRequestProperty("Cookie"));
			*/
		}
		
		if(verbose > 2){
			Data.data.append("\n\n============= Response Headers ================");
			//System.out.println("============= Response Headers ================");
		}
		
		for(int a = 0; ; a++){
			String r = urlConn.getHeaderField(a);
			
			if( r== null){
				break;
			}
			
			if(r.startsWith("POS_ID=")){
				cookie = r;
			}
			
			if(verbose > 2){
				Data.data.append("\n" + r);
				//System.out.println( r );
			}
			
		}		
		

		

		String str, out = "";
		while (null != ((str = input.readLine())))
		{
			out += str + '\n';
		}
		
		input.close ();
		
		/*
		boolean ok = false;		
		
		if(caso.expectedJson){
			//ok comparar los dos jsons		
			ok = jsonCompare(caso.expected, out);
		}else{
		
			ok = out.trim().equals(caso.expected.trim());
		}
		*/
		
		boolean ok = caso.expectedJson?jsonCompare(caso.expected, out):out.trim().equals(caso.expected.trim());
		
		if( !ok ){
			Data.data.append("\n\n"+"============= Test failed ! ================" +
                    	"\n" + caso.desc +
                    	"\n\n============= Request URL ==================" +
                    	"\n" + url + "?" + caso.input + 
		    	(caso.expectedJson?"\n\n============= Expected JSON ================":"\n\n============= Expected response ============") +
		    	"\n" + caso.expected +
                    	"\n\n============= Full Response ================" +
                    	"\n" + out + "\n");
			/*
			System.out.println		("============= Test failed ! ================");		
			System.out.println		(  caso.desc )  ;		
			
			System.out.println		("============= Request URL ==================");
			System.out.println		( url + "?" + caso.input );

			if(caso.expectedJson)
				System.out.println	("============= Expected JSON ================");					
			else
				System.out.println	("============= Expected response ============");					
							
			System.out.println( caso.expected );

			System.out.println		("============= Full Response ================");					
			System.out.println( out );
			
			System.out.println(  );						
			*/
		}else{
			if(verbose > 1)
				Data.data.append("\n[PASSED] " + caso.desc + " on line " + testOnLine);
				//System.out.println(  "[PASSED] " + caso.desc + " on line " + testOnLine )  ;
		}
		
		return out.trim().equals(caso.expected.trim());

	}
	
	
	
	
	 private boolean jsonCompare(String a, String b){
	
		JsonObject one = null, two = null;
		
		try{
			one = new Gson().fromJson(a, JsonObject.class);
		}catch(Exception mje){
			//System.out.println("Malformed JSON !");
			Data.data.append("\nMalformed JSON !");
			return false;
		}


		try{
			two = new Gson().fromJson(b, JsonObject.class);
		}catch(Exception mje){
			//System.out.println("Malformed JSON !");
			Data.data.append("\nMalformed JSON !");
			return false;
		}		


				
		return a.trim().equals( b.trim());
	}
}





class TestCase{

	public String desc;
	public String input;	//action=2099
	public String expected;
	public boolean expectedJson;
	public String codeBase; //http://127.0.0.1/pos/trunk/www/
	public String fileName; //proxy.php
	
	
	public TestCase(){
		this.desc = "";
		this.input = "";
		this.expected = "";
		this.codeBase = null;
		this.fileName = null;
		this.expectedJson = false;
	}
	
}


