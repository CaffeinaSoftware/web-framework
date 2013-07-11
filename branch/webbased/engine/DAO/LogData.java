/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package DAO;

//import javax.swing.JEditorPane;
import javax.swing.JTextArea;

/**
 *
 * @author JManuel
 */

public class LogData {
    public static JTextArea epLog;

    static {
        System.out.println("init"); epLog = new JTextArea();
    }
}
