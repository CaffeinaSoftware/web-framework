
import DAO.PhpDAO;
import DAO.LogData;
import java.io.*;
public class Main{
    public static void main(String ... args)
    {
        System.out.println("hola");
        File fileSql = new File(args[0]);
        File fileDirectory = new File(".\\out\\");
        PhpDAO phpDao = new PhpDAO();
        phpDao.playParser(fileSql, fileDirectory, "alan@caffeina.mx");
        System.out.println(LogData.epLog.getText());
    }
}
