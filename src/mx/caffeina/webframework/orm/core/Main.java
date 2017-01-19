
package mx.caffeina.webframework.orm.client;

import mx.caffeina.webframework.orm.core.*;
import java.io.File;


class Main {
	public static void main(String ... args) {
		String author, outlang;
		File in, out;

		// Defaults
		author = "Anon";
		outlang = null;
		in = null;
		out = null;

		// Options
		boolean omitGeneratedCall = false;
		boolean useUnixTimestamps = false;
		boolean useSpaces = false;

		// Header
		header();

		if (args.length == 0) {
			usage();
			return;
		}

		// Parse command line
		for(int i = 0; i < args.length; i++) {
			if (args[i].startsWith("-help")) {
				usage();
				return;

			}else if(args[i].startsWith("-in")) {
				in =  new File (args[i].split("=")[1]);

			}else if(args[i].startsWith("-out")) {
				out =  new File (args[i].split("=")[1]);

			}else if(args[i].startsWith("-lang")) {
				outlang = args[i].split("=")[1];

			}else if(args[i].startsWith("-author")) {
				author = args[i].split("=")[1];

			}else if(args[i].equals("-omit-call")) {
				omitGeneratedCall = true;

			}else if(args[i].equals("-unix-timestamps")) {
				useUnixTimestamps = true;

			}else if(args[i].equals("-spaces")) {
				useSpaces = true;

			}
		}

		// Required
		if ( outlang == null
			|| in == null
			|| out == null ) {
				System.out.println("Invalid arguments.");
				usage();
				return;
		}

		if (outlang.equals("php")) {
			PhpDAO phpDao = new PhpDAO();
			phpDao.setOmitGeneratedCall(omitGeneratedCall);
			phpDao.setUseUnixTimestamps(useUnixTimestamps);
			phpDao.setUseSpaces(useSpaces);
			phpDao.playParser(in, out, author);
		}

		if (outlang.equals("js")) {
			JsDAO jsDao = new JsDAO();
			jsDao.playParser(in, out, author);
		}
	}

	private static void header() {
		System.out.println("Caffeina Software Object Relational Mapper\n"
							+ "-----------------------------------------");
	}

	private static void usage() {
		System.out.println("orm-client.jar "
						+ "-out=/path "
						+ "-in=/path/db.sql "
						+ "-lang=php|js "
						+ "-author=\"John Doe <john@example.com>\""
						+ "[-omit-call]"
						+ "[-help]"
						+ "\n\n");
	}
}
