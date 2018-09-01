<?php require_once("inc/top.inc.php"); ?>

<div class="devsitePage">

<?php require_once("inc/menu.inc.php"); ?>

    <div class="body nav">
        <div class="content">
            <div id="bodyMenu" class="bodyMenu">
            <?php require_once("inc/toplevnav.inc.php"); ?>
            </div>
            <div id="bodyText" class="bodyText">
                <div class="header">
                    <div class="content">
                        <h1>Nuevo metodo</h1>
                    </div>
                </div>

                <hr/>
                    <?php require_once( "inc/newmethod.inc.php" ); ?>
                <hr/>

                <div class="mtm pvm uiBoxWhite topborder">
                    <div class="mbm"> </div>
                    <abbr title="" data-date="" class="">Ultima modifiacion</abbr>
                </div>

            </div>
            <div class="clear"> </div>
        </div>
    </div>

    <div class="footer">
        <div class="content">
            <div class="copyright">
                 &copy; 2011
            </div>
            <div class="links">
                <a href="build_bd.php?project=<?php echo ( isset($_GET["project"]) && is_numeric($_GET["project"]) )? $_GET["project"] : "null" ; ?>">Respaldar Base de Datos</a>
            </div>
        </div>
    </div>

    <div id="fb-root"> </div>
    <input type="hidden" autocomplete="off" id="post_form_id" name="post_form_id" value="d8f38124ed9e31ef3947198c6d26bff1"/>
    <div id="fb-root"> </div>
</div>
</body>
</html>
