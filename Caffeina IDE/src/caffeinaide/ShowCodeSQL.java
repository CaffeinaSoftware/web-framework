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

    public void show(File sql){
        codeview = new TextArea();
        try {
            BufferedReader lector = new BufferedReader(new FileReader(sql));
            String linea;
            while ((linea = lector.readLine()) != null) {
                codeview.append(linea + "\n");
            }
            mostrar();
        } catch (IOException e) {
            codeview.append("\n\nError al leer el archivo...");
        }
    }

    public void mostrar(){
        final Frame frm = new Frame("DataEntry frame");
        frm.setSize(350, 200);
        frm.setVisible(true);
        frm.addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosing(WindowEvent e) {
                frm.setVisible(false);
            }
        });
        frm.add(codeview);
    }
}