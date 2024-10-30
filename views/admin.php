<?php
    /**
     * Updates the Comment Redirector Message Template
     *
     * @package   Comment Redirector
     * @author    Dave Burke <mintster@nixmash.com>
     * @license   GPL-2.0+
     * @link      http://github.com/mintster/wordpress-comment-redirector-plugin/
     * @copyright 2013 Dave Burke
     */

    require_once(plugin_dir_path(dirname(__FILE__)) . 'include/functions.php');

    $messages[] = '';
    $message_type = null;

    if (!empty($_POST)) {
        $message_type = 'update';
        if (isset($_POST['comment-redirector-template']) && isset($_POST['submit'])) {
            update_option('comment_redirector_template', $_POST['comment-redirector-template']);
            $messages[] = 'Comment Redirector Settings updated!';
        }
        if (isset($_POST['restore'])) {
            update_option('comment_redirector_template', COMMENT_REDIRECTOR_DEFAULT_TEMPLATE);
            $messages[] = 'Comment Redirector Template restored to default';
        }
    }

    function checkbox_value($name) {
        return (isset($_POST[$name]) ? 1 : 0);
    }

?>

<div class="wrap">

    <?php screen_icon(); ?>
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <?php echo Functions_GetMessages($messages, $message_type) ?>
    <form method="post" action="?page=comment-redirector">
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <label for="comment-redirector-template">Enter the master HTML for your comment redirection link to
                        display at the conclusion of your posts. Click "Restore Default" to use default template HTML
                    </label></th>
            </tr>
            <tr>
                <td><textarea name="comment-redirector-template" id="comment-redirector-template"
                              class="regular-text"><?php echo stripslashes(get_option('comment_redirector_template')); ?></textarea>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes"/>
            <input type="submit" name="restore" id="restore" class="button" value="Restore Default"/>
        </p>
    </form>

</div>
