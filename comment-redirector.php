<?php
/**
 * WordPress Comment Redirector.
 *
 * A foundation off of which to build well-documented WordPress plugins that also follow
 * WordPress coding standards and PHP best practices.
 *
 * @package   Comment_Redirector
 * @author    Dave Burke <burke@nixmash.com>
 * @license   GPL-2.0+
 * @link      http://nixmash.com
 * @copyright 2013 Dave Burke
 *
 * @wordpress-plugin
 * Plugin Name: Comment Redirector
 * Plugin URI:  http://nixmash.com/projects/wordpress-comment-redirector-plugin/
 * Description: Redirects visitors to a Google+ post permalink where the post's comments are entered, displayed and managed
 * Version:     1.2
 * Author:      Dave Burke
 * Author URI:  http://nixmash.com
 * Text Domain: comment-redirector-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

require_once(plugin_dir_path(__FILE__) . 'include/functions.php');

//register_activation_hook(__FILE__, array('Comment_Redirector', 'activate'));
//register_deactivation_hook(__FILE__, array('Comment_Redirector', 'deactivate'));

Comment_Redirector::get_instance();

class Comment_Redirector
{

    // region Properties

    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since   1.0.0
     *
     * @const   string
     */
    const VERSION = '1.2';

    /**
     * Unique identifier for your plugin.
     *
     * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
     * match the Text Domain file header in the main plugin file.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_slug = 'comment-redirector';

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;

    // endregion

    // region __construct()

    /**
     * Initialize the plugin by setting localization, filters, and administration functions.
     *
     * @since     1.0.0
     */
    private function __construct()
    {

        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));
        add_action('admin_head', 'comment_redirector_adminCSS');

        // Load admin style sheet and JavaScript.
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));

        // Load public-facing style sheet and JavaScript.
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));

        add_filter('the_content', array($this, 'filter_show_redirector'));

        add_filter('manage_post_posts_columns', array(&$this, 'comment_redirector_columns_head'), 10);
        add_action('manage_post_posts_custom_column', array(&$this, 'comment_redirector_columns_content'), 10, 2);

        add_action('quick_edit_custom_box', array(&$this, 'display_quickedit_comment_redirector_url'), 10, 2);
        add_action('save_post', array(&$this, 'save_quick_edit_comment_redirection_url'));
        add_action('admin_head-edit.php', array(&$this, 'admin_edit_comment_redirection_url_head'));

        $this->comment_redirector_set_default_template();

    }

// endregion

    // region Default functions

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance()
    {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function activate($network_wide)
    {
        // TODO: Define activation functionality here
    }

    public static function deactivate($network_wide)
    {
        // TODO: Define deactivation functionality here
    }


    /**
     * Register and enqueue admin-specific style sheet.
     *
     * @since     1.0.0
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_styles()
    {

        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        $screen = get_current_screen();
        if ($screen->id == $this->plugin_screen_hook_suffix) {
            wp_enqueue_style($this->plugin_slug . '-admin-styles', plugins_url('css/admin.css', __FILE__), array(), self::VERSION);
        }

    }

    /**
     * Register and enqueue public-facing style sheet.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_slug . '-plugin-styles', plugins_url('css/public.css', __FILE__), array(), self::VERSION);
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu()
    {

        $this->plugin_screen_hook_suffix = add_options_page(
            __('Comment Redirector', $this->plugin_slug),
            __('Comment Redirector', $this->plugin_slug),
            'read',
            $this->plugin_slug,
            array($this, 'display_plugin_admin_page')
        );

    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_page()
    {
        include_once('views/admin.php');
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links($links)
    {

        return array_merge(
            array(
                'settings' => '<a href="' . admin_url('plugins.php?page=comment-redirector') . '">' . __('Settings', $this->plugin_slug) . '</a>'
            ),
            $links
        );

    }

    //   endregion

    // region Show Redirector on Post

    /**
     * NOTE:  Filters are points of execution in which WordPress modifies data
     *        before saving it or sending it to the browser.
     *
     *        WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
     *        Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
     *
     * @since    1.0.0
     */
    public function filter_show_redirector($content)
    {
        if (is_single()) {
            global $post;
            $CommentRedirectionUrl = get_post_meta($post->ID, 'comment_redirection_url', true);
            if (!empty($CommentRedirectionUrl)) {
                $template = stripslashes(get_option('comment_redirector_template'));
                $template = str_replace("%plugin_url%", COMMENT_REDIRECTOR_URL, $template);
                $template = str_replace("%post_title%", $post->post_title, $template);
                $template = str_replace("%comment_redirection_url%", $CommentRedirectionUrl, $template);
                return $content . '<p>' . $template;
            } else
                return $content;
        } else
            return $content;
    }

    // endregion

    // region Quick Edit

    function comment_redirector_columns_head($defaults)
    {
        $defaults['comments_redirected'] = sprintf("&nbsp;");
        return $defaults;
    }

    function comment_redirector_columns_content($column_name, $post_ID)
    {
        if ($column_name == 'comments_redirected') {
            echo '<input type="checkbox" disabled ', ($this->are_comments_redirected($post_ID) ? ' checked' : ''), '/>';
        }

    }

    function are_comments_redirected($post_id)
    {
        $post = get_post($post_id);
        if (isset($post->comment_redirection_url))
            return true;
        else
            return false;
    }

    function display_quickedit_comment_redirector_url($column_name, $post_type)
    {
        if ($column_name != 'comments_redirected') return;
        global $post;
        $CommentRedirectionUrl = get_post_meta($post->ID, 'comment_redirection_url', true);
        ?>
        <fieldset class="inline-edit-col-right">
            <div class="inline-edit-col">
                <span class="title comment_redirection_url_title">Comment Redirection Url</span><input type="text"
                                                                                                       id="comment_redirection_url"
                                                                                                       name="comment_redirection_url"
                                                                                                       value=""
                                                                                                       class="comment_redirection_url_textbox"/>
            </div>
        </fieldset>
    <?php
    }

    function save_quick_edit_comment_redirection_url($post_id)
    {
        // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
        // to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;
        // Check permissions

        if (!current_user_can('edit_post', $post_id))
            return $post_id;

        // OK, we're authenticated: we need to find and save the data
        $post = get_post($post_id);
        if (isset($_POST['comment_redirection_url']) && ($post->post_type != 'revision')) {
            $comment_redirection_url = esc_attr($_POST['comment_redirection_url']);
            if ($comment_redirection_url)
                update_post_meta($post_id, 'comment_redirection_url', htmlentities(stripslashes($comment_redirection_url)));
            else
                delete_post_meta($post_id, 'comment_redirection_url');

            return $comment_redirection_url;
        } else
            return $post_id;
    }

    /* load scripts in the footer */
    function admin_edit_comment_redirection_url_head()
    {
        global $current_screen;
        if (($current_screen->id != 'edit-post') || ($current_screen->post_type != 'post')) return;

        $html = '<script type="text/javascript">' . "\n";
        $html .= 'jQuery(document).ready(function() {' . "\n";
        $html .= 'jQuery("#the-list").delegate("a.editinline","click", function() {' . "\n";

        $html .= 'var id = inlineEditPost.getId(this);' . "\n";
        $html .= 'jQuery.post("' . COMMENT_REDIRECTOR_URL . 'include/ajax.php",{ post_id: id, modo: "ajaxget" },' . "\n";
        $html .= 'function(data){ jQuery("#comment_redirection_url").val(data); }' . "\n";

        $html .= ');});});' . "\n";
        $html .= '</script>' . "\n\n";
        echo $html;

    }

// endregion

    // region Utilities

    public function comment_redirector_set_default_template()
    {
        $deprecated = null;
        if (get_option('comment_redirector_template') === false) {
            add_option('comment_redirector_template', COMMENT_REDIRECTOR_DEFAULT_TEMPLATE, $deprecated, 'yes');
        }
    }

    // endregion

} // END class Comment_Redirector


