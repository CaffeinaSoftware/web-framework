/*
 * CaffeinaIDEView.java
 */

package caffeinaide;

import org.jdesktop.application.Action;
import org.jdesktop.application.ResourceMap;
import org.jdesktop.application.SingleFrameApplication;
import org.jdesktop.application.FrameView;
import org.jdesktop.application.TaskMonitor;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import javax.swing.Timer;
import javax.swing.Icon;
import javax.swing.JDialog;
import javax.swing.JFrame;
import DAO.*;
import java.awt.BorderLayout;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintWriter;
import javax.swing.JFileChooser;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.filechooser.FileNameExtensionFilter;

/**
 * The application's main frame.
 */
public class CaffeinaIDEView extends FrameView {

    /**************     GLOBAL      ******************/
    File fileSql;
    File fileDirectory;
    PhpDAO phpDao;
    JsDAO jsDao;
    boolean nofile = false;
    String[] languajes= { "PHP" , "JavaScript" };
    String nameTemporalFile = "temporal.sql"; //se usa cuando se escribe o pega codigo
    /*************************************************/

    public CaffeinaIDEView(SingleFrameApplication app) {
        super(app);
        
        initComponents();

        // set textarea into panel
        LogData.epLog = new JTextArea();
        LogData.epLog.setEditable(false);
        JScrollPane scrollLog = new JScrollPane(LogData.epLog);
        this.pnlLog.setLayout( new BorderLayout());
        this.pnlLog.add(scrollLog, BorderLayout.CENTER);

        // set languajes
        for (String languaje : languajes) {
            cbxLanguajes.addItem(languaje);
        }

        // size frames
        fmeViewFile.setSize(600, 450);
        fmeWriteCode.setSize(600, 450);

        // status bar initialization - message timeout, idle icon and busy animation, etc
        ResourceMap resourceMap = getResourceMap();
        int messageTimeout = resourceMap.getInteger("StatusBar.messageTimeout");
        messageTimer = new Timer(messageTimeout, new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                statusMessageLabel.setText("");
            }
        });
        messageTimer.setRepeats(false);
        int busyAnimationRate = resourceMap.getInteger("StatusBar.busyAnimationRate");
        for (int i = 0; i < busyIcons.length; i++) {
            busyIcons[i] = resourceMap.getIcon("StatusBar.busyIcons[" + i + "]");
        }
        busyIconTimer = new Timer(busyAnimationRate, new ActionListener() {
            public void actionPerformed(ActionEvent e) {
                busyIconIndex = (busyIconIndex + 1) % busyIcons.length;
                statusAnimationLabel.setIcon(busyIcons[busyIconIndex]);
            }
        });
        idleIcon = resourceMap.getIcon("StatusBar.idleIcon");
        statusAnimationLabel.setIcon(idleIcon);
        progressBar.setVisible(false);

        // connecting action tasks to status bar via TaskMonitor
        TaskMonitor taskMonitor = new TaskMonitor(getApplication().getContext());
        taskMonitor.addPropertyChangeListener(new java.beans.PropertyChangeListener() {
            public void propertyChange(java.beans.PropertyChangeEvent evt) {
                String propertyName = evt.getPropertyName();
                if ("started".equals(propertyName)) {
                    if (!busyIconTimer.isRunning()) {
                        statusAnimationLabel.setIcon(busyIcons[0]);
                        busyIconIndex = 0;
                        busyIconTimer.start();
                    }
                    progressBar.setVisible(true);
                    progressBar.setIndeterminate(true);
                } else if ("done".equals(propertyName)) {
                    busyIconTimer.stop();
                    statusAnimationLabel.setIcon(idleIcon);
                    progressBar.setVisible(false);
                    progressBar.setValue(0);
                } else if ("message".equals(propertyName)) {
                    String text = (String)(evt.getNewValue());
                    statusMessageLabel.setText((text == null) ? "" : text);
                    messageTimer.restart();
                } else if ("progress".equals(propertyName)) {
                    int value = (Integer)(evt.getNewValue());
                    progressBar.setVisible(true);
                    progressBar.setIndeterminate(false);
                    progressBar.setValue(value);
                }
            }
        });
    }

    @Action
    public void showAboutBox() {
        if (aboutBox == null) {
            JFrame mainFrame = CaffeinaIDEApp.getApplication().getMainFrame();
            aboutBox = new CaffeinaIDEAboutBox(mainFrame);
            aboutBox.setLocationRelativeTo(mainFrame);
        }
        CaffeinaIDEApp.getApplication().show(aboutBox);
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        mainPanel = new javax.swing.JPanel();
        pnlFileAndDirectory = new javax.swing.JPanel();
        lblInputFile = new javax.swing.JLabel();
        lblOutputDirectory = new javax.swing.JLabel();
        txtSqlFile = new javax.swing.JTextField();
        txtOutputDirectory = new javax.swing.JTextField();
        btnSelectSqlFile = new javax.swing.JButton();
        btnShowSqlFile = new javax.swing.JButton();
        btnCodeWriter = new javax.swing.JButton();
        btnSelectDirectory = new javax.swing.JButton();
        jPanel2 = new javax.swing.JPanel();
        lblAuthor = new javax.swing.JLabel();
        txtAuthor = new javax.swing.JTextField();
        lblLanguajes = new javax.swing.JLabel();
        cbxLanguajes = new javax.swing.JComboBox();
        pnlGenerate = new javax.swing.JPanel();
        btnGenerate = new javax.swing.JButton();
        pnlLog = new javax.swing.JPanel();
        menuBar = new javax.swing.JMenuBar();
        javax.swing.JMenu fileMenu = new javax.swing.JMenu();
        javax.swing.JMenuItem exitMenuItem = new javax.swing.JMenuItem();
        jMenu1 = new javax.swing.JMenu();
        jMenu2 = new javax.swing.JMenu();
        jMenu4 = new javax.swing.JMenu();
        rbtNativeAbstraction = new javax.swing.JRadioButtonMenuItem();
        rbtAdodbAbstraction = new javax.swing.JRadioButtonMenuItem();
        jMenu3 = new javax.swing.JMenu();
        jMenu5 = new javax.swing.JMenu();
        ckbxMinified = new javax.swing.JCheckBoxMenuItem();
        javax.swing.JMenu helpMenu = new javax.swing.JMenu();
        javax.swing.JMenuItem aboutMenuItem = new javax.swing.JMenuItem();
        statusPanel = new javax.swing.JPanel();
        javax.swing.JSeparator statusPanelSeparator = new javax.swing.JSeparator();
        statusMessageLabel = new javax.swing.JLabel();
        statusAnimationLabel = new javax.swing.JLabel();
        progressBar = new javax.swing.JProgressBar();
        rbtgAbstractionLevel = new javax.swing.ButtonGroup();
        fmeWriteCode = new javax.swing.JFrame();
        pnlOptions = new javax.swing.JPanel();
        btnPasteCode = new javax.swing.JButton();
        jButton1 = new javax.swing.JButton();
        jScrollPane1 = new javax.swing.JScrollPane();
        txaWriteCode = new javax.swing.JTextArea();
        fmeViewFile = new javax.swing.JFrame();
        jScrollPane2 = new javax.swing.JScrollPane();
        txaViewFile = new javax.swing.JTextArea();

        mainPanel.setName("mainPanel"); // NOI18N

        org.jdesktop.application.ResourceMap resourceMap = org.jdesktop.application.Application.getInstance(caffeinaide.CaffeinaIDEApp.class).getContext().getResourceMap(CaffeinaIDEView.class);
        pnlFileAndDirectory.setBorder(javax.swing.BorderFactory.createTitledBorder(resourceMap.getString("pnlFileAndDirectory.border.title"))); // NOI18N
        pnlFileAndDirectory.setName("pnlFileAndDirectory"); // NOI18N

        lblInputFile.setText(resourceMap.getString("lblInputFile.text")); // NOI18N
        lblInputFile.setName("lblInputFile"); // NOI18N

        lblOutputDirectory.setText(resourceMap.getString("lblOutputDirectory.text")); // NOI18N
        lblOutputDirectory.setName("lblOutputDirectory"); // NOI18N

        txtSqlFile.setEditable(false);
        txtSqlFile.setText(resourceMap.getString("txtSqlFile.text")); // NOI18N
        txtSqlFile.setName("txtSqlFile"); // NOI18N

        txtOutputDirectory.setEditable(false);
        txtOutputDirectory.setText(resourceMap.getString("txtOutputDirectory.text")); // NOI18N
        txtOutputDirectory.setName("txtOutputDirectory"); // NOI18N

        btnSelectSqlFile.setIcon(resourceMap.getIcon("btnSelectSqlFile.icon")); // NOI18N
        btnSelectSqlFile.setText(resourceMap.getString("btnSelectSqlFile.text")); // NOI18N
        btnSelectSqlFile.setToolTipText(resourceMap.getString("btnSelectSqlFile.toolTipText")); // NOI18N
        btnSelectSqlFile.setName(resourceMap.getString("jButton1.name")); // NOI18N
        btnSelectSqlFile.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                btnSelectSqlFileActionPerformed(evt);
            }
        });

        btnShowSqlFile.setIcon(resourceMap.getIcon("btnShowSqlFile.icon")); // NOI18N
        btnShowSqlFile.setText(resourceMap.getString("btnShowSqlFile.text")); // NOI18N
        btnShowSqlFile.setToolTipText(resourceMap.getString("btnShowSqlFile.toolTipText")); // NOI18N
        btnShowSqlFile.setName("btnShowSqlFile"); // NOI18N
        btnShowSqlFile.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                btnShowSqlFileActionPerformed(evt);
            }
        });

        btnCodeWriter.setIcon(resourceMap.getIcon("btnCodeWriter.icon")); // NOI18N
        btnCodeWriter.setText(resourceMap.getString("btnCodeWriter.text")); // NOI18N
        btnCodeWriter.setToolTipText(resourceMap.getString("btnCodeWriter.toolTipText")); // NOI18N
        btnCodeWriter.setName("btnCodeWriter"); // NOI18N
        btnCodeWriter.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                btnCodeWriterActionPerformed(evt);
            }
        });

        btnSelectDirectory.setIcon(resourceMap.getIcon("btnSelectDirectory.icon")); // NOI18N
        btnSelectDirectory.setText(resourceMap.getString("btnSelectDirectory.text")); // NOI18N
        btnSelectDirectory.setToolTipText(resourceMap.getString("btnSelectDirectory.toolTipText")); // NOI18N
        btnSelectDirectory.setName("btnSelectDirectory"); // NOI18N
        btnSelectDirectory.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                btnSelectDirectoryActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout pnlFileAndDirectoryLayout = new javax.swing.GroupLayout(pnlFileAndDirectory);
        pnlFileAndDirectory.setLayout(pnlFileAndDirectoryLayout);
        pnlFileAndDirectoryLayout.setHorizontalGroup(
            pnlFileAndDirectoryLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(pnlFileAndDirectoryLayout.createSequentialGroup()
                .addContainerGap()
                .addGroup(pnlFileAndDirectoryLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(lblInputFile)
                    .addComponent(lblOutputDirectory)
                    .addGroup(pnlFileAndDirectoryLayout.createSequentialGroup()
                        .addGap(12, 12, 12)
                        .addGroup(pnlFileAndDirectoryLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING, false)
                            .addComponent(txtOutputDirectory, javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(txtSqlFile, javax.swing.GroupLayout.Alignment.LEADING, javax.swing.GroupLayout.DEFAULT_SIZE, 198, Short.MAX_VALUE))
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addGroup(pnlFileAndDirectoryLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addGroup(pnlFileAndDirectoryLayout.createSequentialGroup()
                                .addComponent(btnSelectSqlFile)
                                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                                .addComponent(btnShowSqlFile)
                                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                                .addComponent(btnCodeWriter))
                            .addComponent(btnSelectDirectory))))
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        pnlFileAndDirectoryLayout.setVerticalGroup(
            pnlFileAndDirectoryLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(pnlFileAndDirectoryLayout.createSequentialGroup()
                .addContainerGap()
                .addComponent(lblInputFile)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addGroup(pnlFileAndDirectoryLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                    .addComponent(btnCodeWriter, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(btnSelectSqlFile, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(txtSqlFile, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(btnShowSqlFile, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addGap(18, 18, 18)
                .addGroup(pnlFileAndDirectoryLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING)
                    .addGroup(pnlFileAndDirectoryLayout.createSequentialGroup()
                        .addComponent(lblOutputDirectory)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(txtOutputDirectory, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                    .addComponent(btnSelectDirectory))
                .addContainerGap())
        );

        jPanel2.setBorder(javax.swing.BorderFactory.createTitledBorder(resourceMap.getString("jPanel2.border.title"))); // NOI18N
        jPanel2.setName("jPanel2"); // NOI18N

        lblAuthor.setText(resourceMap.getString("lblAuthor.text")); // NOI18N
        lblAuthor.setName("lblAuthor"); // NOI18N

        txtAuthor.setText(resourceMap.getString("txtAuthor.text")); // NOI18N
        txtAuthor.setName("txtAuthor"); // NOI18N

        lblLanguajes.setText(resourceMap.getString("lblLanguajes.text")); // NOI18N
        lblLanguajes.setName("lblLanguajes"); // NOI18N

        cbxLanguajes.setName("cbxLanguajes"); // NOI18N

        javax.swing.GroupLayout jPanel2Layout = new javax.swing.GroupLayout(jPanel2);
        jPanel2.setLayout(jPanel2Layout);
        jPanel2Layout.setHorizontalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel2Layout.createSequentialGroup()
                        .addContainerGap()
                        .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                                .addGroup(jPanel2Layout.createSequentialGroup()
                                    .addGap(12, 12, 12)
                                    .addComponent(txtAuthor, javax.swing.GroupLayout.DEFAULT_SIZE, 166, Short.MAX_VALUE))
                                .addComponent(lblAuthor))
                            .addComponent(lblLanguajes)))
                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel2Layout.createSequentialGroup()
                        .addGap(24, 24, 24)
                        .addComponent(cbxLanguajes, 0, 166, Short.MAX_VALUE)))
                .addContainerGap())
        );
        jPanel2Layout.setVerticalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(lblAuthor)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(txtAuthor, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, 18, Short.MAX_VALUE)
                .addComponent(lblLanguajes)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(cbxLanguajes, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addGap(18, 18, 18))
        );

        pnlGenerate.setBorder(javax.swing.BorderFactory.createTitledBorder(""));
        pnlGenerate.setName("pnlGenerate"); // NOI18N

        btnGenerate.setText(resourceMap.getString("btnGenerate.text")); // NOI18N
        btnGenerate.setToolTipText(resourceMap.getString("btnGenerate.toolTipText")); // NOI18N
        btnGenerate.setName("btnGenerate"); // NOI18N
        btnGenerate.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                btnGenerateActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout pnlGenerateLayout = new javax.swing.GroupLayout(pnlGenerate);
        pnlGenerate.setLayout(pnlGenerateLayout);
        pnlGenerateLayout.setHorizontalGroup(
            pnlGenerateLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(pnlGenerateLayout.createSequentialGroup()
                .addGap(213, 213, 213)
                .addComponent(btnGenerate, javax.swing.GroupLayout.PREFERRED_SIZE, 145, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addContainerGap(199, Short.MAX_VALUE))
        );
        pnlGenerateLayout.setVerticalGroup(
            pnlGenerateLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(btnGenerate, javax.swing.GroupLayout.DEFAULT_SIZE, 40, Short.MAX_VALUE)
        );

        pnlLog.setBorder(javax.swing.BorderFactory.createTitledBorder(resourceMap.getString("pnlLog.border.title"))); // NOI18N
        pnlLog.setName("pnlLog"); // NOI18N

        javax.swing.GroupLayout pnlLogLayout = new javax.swing.GroupLayout(pnlLog);
        pnlLog.setLayout(pnlLogLayout);
        pnlLogLayout.setHorizontalGroup(
            pnlLogLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGap(0, 557, Short.MAX_VALUE)
        );
        pnlLogLayout.setVerticalGroup(
            pnlLogLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGap(0, 198, Short.MAX_VALUE)
        );

        javax.swing.GroupLayout mainPanelLayout = new javax.swing.GroupLayout(mainPanel);
        mainPanel.setLayout(mainPanelLayout);
        mainPanelLayout.setHorizontalGroup(
            mainPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(mainPanelLayout.createSequentialGroup()
                .addContainerGap()
                .addGroup(mainPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING, false)
                    .addComponent(pnlGenerate, javax.swing.GroupLayout.Alignment.LEADING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addGroup(javax.swing.GroupLayout.Alignment.LEADING, mainPanelLayout.createSequentialGroup()
                        .addComponent(pnlFileAndDirectory, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(jPanel2, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                    .addComponent(pnlLog, javax.swing.GroupLayout.Alignment.LEADING, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        mainPanelLayout.setVerticalGroup(
            mainPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(mainPanelLayout.createSequentialGroup()
                .addGroup(mainPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                    .addComponent(jPanel2, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                    .addComponent(pnlFileAndDirectory, javax.swing.GroupLayout.DEFAULT_SIZE, 183, Short.MAX_VALUE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(pnlGenerate, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(pnlLog, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addContainerGap())
        );

        menuBar.setName("menuBar"); // NOI18N

        fileMenu.setText(resourceMap.getString("fileMenu.text")); // NOI18N
        fileMenu.setName("fileMenu"); // NOI18N

        javax.swing.ActionMap actionMap = org.jdesktop.application.Application.getInstance(caffeinaide.CaffeinaIDEApp.class).getContext().getActionMap(CaffeinaIDEView.class, this);
        exitMenuItem.setAction(actionMap.get("quit")); // NOI18N
        exitMenuItem.setName("exitMenuItem"); // NOI18N
        fileMenu.add(exitMenuItem);

        menuBar.add(fileMenu);

        jMenu1.setText(resourceMap.getString("jMenu1.text")); // NOI18N
        jMenu1.setName("jMenu1"); // NOI18N

        jMenu2.setText(resourceMap.getString("jMenu2.text")); // NOI18N
        jMenu2.setName("jMenu2"); // NOI18N

        jMenu4.setText(resourceMap.getString("jMenu4.text")); // NOI18N
        jMenu4.setName("jMenu4"); // NOI18N

        rbtgAbstractionLevel.add(rbtNativeAbstraction);
        rbtNativeAbstraction.setSelected(true);
        rbtNativeAbstraction.setText(resourceMap.getString("rbtNativeAbstraction.text")); // NOI18N
        rbtNativeAbstraction.setName("rbtNativeAbstraction"); // NOI18N
        jMenu4.add(rbtNativeAbstraction);

        rbtgAbstractionLevel.add(rbtAdodbAbstraction);
        rbtAdodbAbstraction.setText(resourceMap.getString("rbtAdodbAbstraction.text")); // NOI18N
        rbtAdodbAbstraction.setName("rbtAdodbAbstraction"); // NOI18N
        jMenu4.add(rbtAdodbAbstraction);

        jMenu2.add(jMenu4);

        jMenu1.add(jMenu2);

        jMenu3.setText(resourceMap.getString("jMenu3.text")); // NOI18N
        jMenu3.setName("jMenu3"); // NOI18N

        jMenu5.setText(resourceMap.getString("jMenu5.text")); // NOI18N
        jMenu5.setName("jMenu5"); // NOI18N

        ckbxMinified.setSelected(true);
        ckbxMinified.setText(resourceMap.getString("ckbxMinified.text")); // NOI18N
        ckbxMinified.setName("ckbxMinified"); // NOI18N
        jMenu5.add(ckbxMinified);

        jMenu3.add(jMenu5);

        jMenu1.add(jMenu3);

        menuBar.add(jMenu1);

        helpMenu.setText(resourceMap.getString("helpMenu.text")); // NOI18N
        helpMenu.setName("helpMenu"); // NOI18N

        aboutMenuItem.setAction(actionMap.get("showAboutBox")); // NOI18N
        aboutMenuItem.setName("aboutMenuItem"); // NOI18N
        helpMenu.add(aboutMenuItem);

        menuBar.add(helpMenu);

        statusPanel.setName("statusPanel"); // NOI18N

        statusPanelSeparator.setName("statusPanelSeparator"); // NOI18N

        statusMessageLabel.setName("statusMessageLabel"); // NOI18N

        statusAnimationLabel.setHorizontalAlignment(javax.swing.SwingConstants.LEFT);
        statusAnimationLabel.setName("statusAnimationLabel"); // NOI18N

        progressBar.setName("progressBar"); // NOI18N

        javax.swing.GroupLayout statusPanelLayout = new javax.swing.GroupLayout(statusPanel);
        statusPanel.setLayout(statusPanelLayout);
        statusPanelLayout.setHorizontalGroup(
            statusPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(statusPanelSeparator, javax.swing.GroupLayout.DEFAULT_SIZE, 593, Short.MAX_VALUE)
            .addGroup(statusPanelLayout.createSequentialGroup()
                .addContainerGap()
                .addComponent(statusMessageLabel)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, 407, Short.MAX_VALUE)
                .addComponent(progressBar, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(statusAnimationLabel)
                .addContainerGap())
        );
        statusPanelLayout.setVerticalGroup(
            statusPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(statusPanelLayout.createSequentialGroup()
                .addComponent(statusPanelSeparator, javax.swing.GroupLayout.PREFERRED_SIZE, 2, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGroup(statusPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(statusMessageLabel)
                    .addComponent(statusAnimationLabel)
                    .addComponent(progressBar, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addGap(3, 3, 3))
        );

        fmeWriteCode.setName("fmeWriteCode"); // NOI18N

        pnlOptions.setName("pnlOptions"); // NOI18N

        btnPasteCode.setText(resourceMap.getString("btnPasteCode.text")); // NOI18N
        btnPasteCode.setName("btnPasteCode"); // NOI18N
        btnPasteCode.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                btnPasteCodeActionPerformed(evt);
            }
        });

        jButton1.setText(resourceMap.getString("jButton1.text")); // NOI18N
        jButton1.setName("jButton1"); // NOI18N
        jButton1.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton1ActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout pnlOptionsLayout = new javax.swing.GroupLayout(pnlOptions);
        pnlOptions.setLayout(pnlOptionsLayout);
        pnlOptionsLayout.setHorizontalGroup(
            pnlOptionsLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, pnlOptionsLayout.createSequentialGroup()
                .addContainerGap(264, Short.MAX_VALUE)
                .addComponent(jButton1)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(btnPasteCode)
                .addContainerGap())
        );
        pnlOptionsLayout.setVerticalGroup(
            pnlOptionsLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(pnlOptionsLayout.createSequentialGroup()
                .addContainerGap()
                .addGroup(pnlOptionsLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(btnPasteCode)
                    .addComponent(jButton1))
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );

        jScrollPane1.setName("jScrollPane1"); // NOI18N

        txaWriteCode.setColumns(20);
        txaWriteCode.setRows(5);
        txaWriteCode.setName("txaWriteCode"); // NOI18N
        jScrollPane1.setViewportView(txaWriteCode);

        javax.swing.GroupLayout fmeWriteCodeLayout = new javax.swing.GroupLayout(fmeWriteCode.getContentPane());
        fmeWriteCode.getContentPane().setLayout(fmeWriteCodeLayout);
        fmeWriteCodeLayout.setHorizontalGroup(
            fmeWriteCodeLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(pnlOptions, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
            .addComponent(jScrollPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 431, Short.MAX_VALUE)
        );
        fmeWriteCodeLayout.setVerticalGroup(
            fmeWriteCodeLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(fmeWriteCodeLayout.createSequentialGroup()
                .addComponent(pnlOptions, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jScrollPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 248, Short.MAX_VALUE))
        );

        fmeViewFile.setName("fmeViewFile"); // NOI18N

        jScrollPane2.setName("jScrollPane2"); // NOI18N

        txaViewFile.setColumns(20);
        txaViewFile.setRows(5);
        txaViewFile.setName("txaViewFile"); // NOI18N
        jScrollPane2.setViewportView(txaViewFile);

        javax.swing.GroupLayout fmeViewFileLayout = new javax.swing.GroupLayout(fmeViewFile.getContentPane());
        fmeViewFile.getContentPane().setLayout(fmeViewFileLayout);
        fmeViewFileLayout.setHorizontalGroup(
            fmeViewFileLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jScrollPane2, javax.swing.GroupLayout.DEFAULT_SIZE, 400, Short.MAX_VALUE)
        );
        fmeViewFileLayout.setVerticalGroup(
            fmeViewFileLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jScrollPane2, javax.swing.GroupLayout.DEFAULT_SIZE, 300, Short.MAX_VALUE)
        );

        setComponent(mainPanel);
        setMenuBar(menuBar);
        setStatusBar(statusPanel);
    }// </editor-fold>//GEN-END:initComponents

    private void btnCodeWriterActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_btnCodeWriterActionPerformed
        this.codeWriter();
    }//GEN-LAST:event_btnCodeWriterActionPerformed

    private void btnShowSqlFileActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_btnShowSqlFileActionPerformed
        this.showFile();
    }//GEN-LAST:event_btnShowSqlFileActionPerformed

    private void btnSelectSqlFileActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_btnSelectSqlFileActionPerformed
        this.searchSQL();
    }//GEN-LAST:event_btnSelectSqlFileActionPerformed

    private void btnSelectDirectoryActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_btnSelectDirectoryActionPerformed
        this.selectDirectory();
    }//GEN-LAST:event_btnSelectDirectoryActionPerformed

    private void btnGenerateActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_btnGenerateActionPerformed
        this.generateDAO();
    }//GEN-LAST:event_btnGenerateActionPerformed

    private void btnPasteCodeActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_btnPasteCodeActionPerformed
        this.txaWriteCode.paste();
    }//GEN-LAST:event_btnPasteCodeActionPerformed

    private void jButton1ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton1ActionPerformed
        this.txaWriteCode.setText(" ");
    }//GEN-LAST:event_jButton1ActionPerformed

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton btnCodeWriter;
    private javax.swing.JButton btnGenerate;
    private javax.swing.JButton btnPasteCode;
    private javax.swing.JButton btnSelectDirectory;
    private javax.swing.JButton btnSelectSqlFile;
    private javax.swing.JButton btnShowSqlFile;
    private javax.swing.JComboBox cbxLanguajes;
    private javax.swing.JCheckBoxMenuItem ckbxMinified;
    private javax.swing.JFrame fmeViewFile;
    private javax.swing.JFrame fmeWriteCode;
    private javax.swing.JButton jButton1;
    private javax.swing.JMenu jMenu1;
    private javax.swing.JMenu jMenu2;
    private javax.swing.JMenu jMenu3;
    private javax.swing.JMenu jMenu4;
    private javax.swing.JMenu jMenu5;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JScrollPane jScrollPane2;
    private javax.swing.JLabel lblAuthor;
    private javax.swing.JLabel lblInputFile;
    private javax.swing.JLabel lblLanguajes;
    private javax.swing.JLabel lblOutputDirectory;
    private javax.swing.JPanel mainPanel;
    private javax.swing.JMenuBar menuBar;
    private javax.swing.JPanel pnlFileAndDirectory;
    private javax.swing.JPanel pnlGenerate;
    private javax.swing.JPanel pnlLog;
    private javax.swing.JPanel pnlOptions;
    private javax.swing.JProgressBar progressBar;
    private javax.swing.JRadioButtonMenuItem rbtAdodbAbstraction;
    private javax.swing.JRadioButtonMenuItem rbtNativeAbstraction;
    private javax.swing.ButtonGroup rbtgAbstractionLevel;
    private javax.swing.JLabel statusAnimationLabel;
    private javax.swing.JLabel statusMessageLabel;
    private javax.swing.JPanel statusPanel;
    private javax.swing.JTextArea txaViewFile;
    private javax.swing.JTextArea txaWriteCode;
    private javax.swing.JTextField txtAuthor;
    private javax.swing.JTextField txtOutputDirectory;
    private javax.swing.JTextField txtSqlFile;
    // End of variables declaration//GEN-END:variables

    private final Timer messageTimer;
    private final Timer busyIconTimer;
    private final Icon idleIcon;
    private final Icon[] busyIcons = new Icon[15];
    private int busyIconIndex = 0;

    private JDialog aboutBox;



    /**********************     METHOD ZONE     ************************/

    public void searchSQL() {
        JFileChooser fchooser = new JFileChooser();
        FileNameExtensionFilter filter = new FileNameExtensionFilter(".sql files", "sql");
        fchooser.setFileFilter(filter);
        if ((fchooser.showOpenDialog(txtSqlFile)) != JFileChooser.APPROVE_OPTION) {
            return;
        }
        fileSql = fchooser.getSelectedFile();
        txtSqlFile.setText(fileSql.getName());
        btnShowSqlFile.setEnabled(true);
        txaWriteCode.setText(" ");
        fmeWriteCode.setVisible(false);
        LogData.epLog.setText("\nAhora se usará el archivo "+fileSql.getName()+" para generar los DAO");
        nofile = false;
    }

    public void selectDirectory() {
        JFileChooser fchooser = new JFileChooser();
        fchooser.setFileSelectionMode(JFileChooser.DIRECTORIES_ONLY);
        if ((fchooser.showOpenDialog(txtOutputDirectory)) != JFileChooser.APPROVE_OPTION) {
            return;
        }
        fileDirectory = fchooser.getSelectedFile();
        txtOutputDirectory.setText(fileDirectory.getAbsolutePath());
    }

    public void codeWriter(){
        fmeWriteCode.setVisible(true);
        btnShowSqlFile.setEnabled(false);
        txaViewFile.setText(" ");
        fmeViewFile.setVisible(false);
        LogData.epLog.setText("\nAhora se usará el codigo escrito para generar los DAO");
        nofile = true;
    }

    public boolean savedTemporalFile(){
        PrintWriter writer = null;
        try {
            if( txaWriteCode.getText().isEmpty()){
                return false;
            }
            writer = new PrintWriter(new FileWriter("temporal.sql"));
            writer.append(txaWriteCode.getText());
            writer.flush();
            writer.close();
            return true;
        } catch (IOException ex) {
            return false;
        }
    }

    public boolean showFile(){
        fmeViewFile.setVisible(true);
        try {
            BufferedReader lector = new BufferedReader(new FileReader(fileSql));
            String linea;
            while ((linea = lector.readLine()) != null) {
                txaViewFile.append(linea + "\n");
            }
            lector.close();
            return true;
        } catch (IOException e) {
            LogData.epLog.setText("\nError al leer el archivo...");
            return false;
        }
    }

    public void generateDAO() {
        if ( !nofile && fileSql == null ) {
            LogData.epLog.setText("\nNo se seleccionó archivo .sql");
            return;
        }
        if ( fileDirectory == null ) {
            LogData.epLog.setText("\nNo se seleccionó directorio de salida");
            return;
        }
        if( nofile ){
            if( savedTemporalFile() )
                fileSql = new File("temporal.sql");
            else{
                LogData.epLog.setText("\nEl codigo ingresado es nulo");
                return;
            }
        }
        LogData.epLog.setText(" ");
        btnGenerate.setEnabled(false);
        String target_lang = (String) this.cbxLanguajes.getSelectedItem();
        if (target_lang.equals("PHP")) {
            phpDao = new PhpDAO();
            phpDao.playParser(fileSql, fileDirectory, txtAuthor.getText());
        }
        if (target_lang.equals("JavaScript")) {
            jsDao = new JsDAO();
            jsDao.playParser(fileSql, fileDirectory, txtAuthor.getText());
        }
        LogData.epLog.append("\n\nTerminado !");
        btnGenerate.setEnabled(true);
    }

}
