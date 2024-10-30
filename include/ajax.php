<?php
    /**
     * Created by IntelliJ IDEA.
     * User: daveburke
     * Date: 9/9/13
     * Time: 2:08 PM
     * To change this template use File | Settings | File Templates.
     */

    if (isset($_POST['modo'])) {
        if ($_POST['modo'] == 'ajaxget') {

            $post_id = $_POST['post_id'];
            $comment_redirection_url = '';

            //
            require_once('config.php');
            $database = CR_NAME;
            $query = "select meta_value from " . CR_TABLE_PREFIX . "postmeta where post_id = $post_id and meta_key = 'comment_redirection_url'";

            if ($database === "") {
                require_once('../../../../wp-config.php');
                $dbc = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
                $database = DB_NAME;
                $query = "select meta_value from " . $table_prefix . "postmeta where post_id = $post_id and meta_key = 'comment_redirection_url'";
            } else {
                $dbc = mysql_connect(CR_HOST, CR_USER, CR_PASSWORD);
            }

            if (!$dbc) {
                die('Not Connected: ' . mysql_error());
            }
            $db = mysql_select_db($database);
            if (!$db) {
                echo "Check database configuration settings...";
            }

            $result = mysql_query($query);
            if (mysql_num_rows($result) > 0) {
                $comment_redirection_url = mysql_result($result, 0);
            }

            if ($result) {
                mysql_free_result($result);
            }
            mysql_close($dbc);

            echo $comment_redirection_url;

            return;

        }
    }
