<?php


    // region BEGIN - Global Constants
    define('COMMENT_REDIRECTOR_PATH', plugin_dir_path(__FILE__));
    define('COMMENT_REDIRECTOR_URL', plugins_url('../', __FILE__));
    define('COMMENT_REDIRECTOR_DEFAULT_TEMPLATE',
    '<div class="commentRedirectArea">
    <div class="commentRedirectLogoArea">
        <a href="%comment_redirection_url%" /><img src="%plugin_url%images/gplus100x100.png" alt="" class="commentRedirectLogo" /></a>
    </div>
    <div class="commentRedirectText">
        Comments to <span class="commentRedirectTitle">%post_title%</span> are located on <a href="%comment_redirection_url%" />this Google+ Post.</a> Please join in or read what others are saying!
    </div>
</div>');


    // endregion

    // BEGIN - ADMIN CSS ------------------------------------------------- */

    function comment_redirector_adminCSS()
    {
        global $current_screen;
        if (($current_screen->id != 'edit-post') || ($current_screen->post_type != 'post')) return;

        $img_url = COMMENT_REDIRECTOR_URL . 'images/comment-red-bubble.png';
        echo '<style type=\'text/css\'>
                    .widefat thead th.column-comments_redirected {
                        background-image: url(\'' . $img_url . '\');
                        background-repeat: no-repeat;
                        height: 12px;
                        width: 12px;
                        background-position: center;
                        }
                        .inline-edit-row fieldset input[type="text"].comment_redirection_url_textbox
                          {
                                clear: left;
                                display: block;
                                margin-left: 0;
                                text-align: left;
                                width: 100%;
                           }
                        .inline-edit-row fieldset span.comment_redirection_url_title
                        {
                                display: inline-flex;
                                margin-top: 7px;
                        }
                     </style>';
    }

    // END - ADMIN CSS ------------------------------------------------- */


