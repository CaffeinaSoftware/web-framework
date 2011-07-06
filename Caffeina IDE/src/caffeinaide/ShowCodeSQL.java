/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package caffeinaide;

import java.awt.*;
import java.awt.event.*;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.IOException;

/**
 *
 * @author franckie
 */
public class ShowCodeSQL {

    TextArea codeview;
    Frame frm;
    boolean saved_file = false;

    public void showFile(File sql){
        codeview = new TextArea();
        try {
            BufferedReader lector = new BufferedReader(new FileReader(sql));
            String linea;
            while ((linea = lector.readLine()) != null) {
                codeview.append(linea + "\n");
            }
            lector.close();
            mostrar(sql.getName());
        } catch (IOException e) {
            codeview.append("\n\nError al leer el archivo...");
        }
    }

    public void writeCode(){
        codeview = new TextArea();
        mostrar("Escribir/pegar codigo sql");
    }

    public void mostrar(String name){
        frm = new Frame(name);
        frm.setSize(650, 400);
        frm.setVisible(true);
        frm.addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosing(WindowEvent e) {
                frm.setVisible(false);
            }
        });
        frm.add(codeview);
    }

    public void setVisible(boolean value){
        frm.setVisible(value);
    }

    public boolean getVisible(){
        return frm.isVisible();
    }

    public boolean getStatus(){
        return saved_file;
    }

    public String getCode(){
        return codeview.getText();
    }

}