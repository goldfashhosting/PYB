<?php
/*
Plugin Name: PYB
Plugin URI: 
Description: Nice Proposals for your business. Our Advanced plugins Adds functionality to create proposals and send short email with link to proposal.
Version: v0.1
Author: G1.C Devs
Author URI: http://goldfash.com
*/

// TO DO : hook up to Freshbooks, etc



/**
 * Handles sending email to client
 * 
 * @since 0.1
 */
function pyb_email_link( $post_ID ) {
     
     // If this is just a revision, don't send the email.
	if ( wp_is_post_revision( $post_ID ) )
		return;
     
     // set a security key which will be added as post_meta '_pyb_security_key'
     // and appended to the URL sent to the client
     $security_key = pyb_get_key( $post_ID );
     
     // set parameters for email to be sent
     $from       = ( get_post_meta( $post_ID, '_pyb_prepared_person', true ) ) ? get_post_meta( $post_ID, '_pyb_prepared_person', true ) : '';
     $from_email = ( get_post_meta( $post_ID, '_pyb_prepared_email', true ) ) ? get_post_meta( $post_ID, '_pyb_prepared_email', true ) : '';
     $to         = get_post_meta( $post_ID, '_pyb_client_email', true );
     $subject    = get_the_title( $post_ID );
     $message    = pyb_generate_message( $post_ID, $security_key );     
     
     $headers[] = 'From: "' . $from . '" <' . $from_email . '>';
     $headers[] = "Reply-To: $from_email";
     $headers[] = "MIME-Version: 1.0\r\n";
     $headers[] = "Content-Type: text/html; charset=ISO-8859-1\r\n";
     
     //wp_mail( $to, $subject, $message, $headers );
       if (!empty($to)) {
       
       

	
	        $html  = '<div id="major-publishing-actions" style="overflow:hidden">';
	        $html .= '<div id="publishing-action">';
	        $html .= '<input type="submit" accesskey="p" tabindex="5" value="Send Email" class="button-primary" id="custom" name="sendnow">';
	        $html .= '<input type="hidden" value="1" class="button-primary" id="custom" name="sendnow">';
	        $html .= '</div>';
	        $html .= '</div>';
	        echo $html;
	        if (isset($_REQUEST['_pyb_sendnow']) && ($_REQUEST['_pyb_sendnow']) == 1 ) {
	        
	        wp_mail( $to, $subject, $message, $headers );
	        }
	        
	}

          
          }
    
add_action( 'save_post', 'pyb_email_link', 20 );

// generate random string to used as a "security" check 
// don't want nonce functionality because we want the client to be able to visit
// the link multiple times
function pyb_get_key( $post_ID ) {
              
     if( $output = get_post_meta( $post_ID, '_pyb_security_key', true ) )
          return $output;
     
     $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
     $output = '';

     // loop 16 times, selecting a random character from $characters string
     // store into $output and return     
     for( $i = 0; $i < 16; $i++ ) {
          $output .= substr( $characters, rand( 0, 61 ), 1 );
     }

     if( update_post_meta( $post_ID, '_pyb_security_key', $output ) )
          return $output;     
}

// Create HTML message for sending to client
function pyb_generate_message( $id, $key ) {
     
     // get the post for use later
     $title   = get_the_title( $id );
     $excerpt = wpautop( get_post_meta( $id, '_pyb_email_body', true ) );
     
     $permalink = get_permalink( $id );
     $link = ''. $permalink .'/'. $key .'';
     
     $message_body  = '';
     $message_body .= '
          <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
          <html>
               <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                    <style type="text/css">
                         body, #backgroundTable{
                              background: #0057C2;
                              color: #828282;
                              font-family: "PT Sans", "Helvetica Neue", Helvetica, sans-serif;
                         }
                         h1, h1 a, h2, h2 a, h3, h3 a, h4, h4 a, h5, h5 a, h6, h6 a {
                              color: #424242;
                              font-family: "PT Sans", "Helvetica Neue", Helvetica, sans-serif;
                              font-weight: 700;
                         }
                         h1 {
                              font-size: 27px;
                              line-height: 1.4;     
                         }
                         h2 {
                              font-size: 23px;
                              line-height: 1.4;
                         }
                         h3 {
                              font-size: 17px;
                         }
                         h4 {
                              font-size: 14px;
                         }
                         h5 {
                              font-size: 14px;
                         }
                         h6 {
                              font-size: 14px;
                         }
                         p {
                              font-size: 14px;
                              font-weight: normal;
                              line-height: 1.5;
                              margin-bottom: 10px;     
                         }
                         a { 
                              color: #828282;
                         }
                         #templateContainer {
                              background: #fff;
                         }
                         #templateBody {
                              text-align: center;
                         }
                         .entry-link a {
                              color: #45C6F0;
                              display: block;
                              font-size: 23px;
                              margin: 40px 0 10px;
                              text-decoration: none;
                         }
                    </style>
               </head>
               <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
                   	<center>
                       	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="background: #efefef;">
                           	<tr>
                               	<td align="center" valign="top">
                                   	<table border="0" cellpadding="0" cellspacing="0" width="600" id="templateContainer">
                                       	     <tr>
                                       	          <td align="center" valign="top">
                                                       <table border="0" cellpadding="0" cellspacing="20" width="600" id="templateBody">
                                                            <tr>
                                                                 <td valign="top" class="bodyContent">
                                                                      <table border="0" cellpadding="20" cellspacing="0" width="100%" style="background: #ffffff;">
                                                                           <tr>
                                                                                <td valign="top">
                                                                                     <h1>'. $title . '</h1>
                                                                                     <div class="entry-content">
                                                                                          ' . $excerpt .'
                                                                                     </div>
                                                                                     <div class="entry-link">
                                                                                          <a href="' . $link . '">View Proposal</a>
                                                                                     </div>
                                                                                </td>
                                                                           </tr>
                                                                      </table>                                                               
                                                                 </td>
                                                            </tr>
                                                       </table><!--#templateBody-->
                                                  </td>
                                             </tr>
                                        </table><!--#templateContainer-->
                                   </td>
                              </tr>
                         </table>
                    </center>
               </body>
          </html>';
     
     return $message_body;
}



/**
 * Sets up proposal custom post type
 * 
 * @since 0.1
 */

add_action( 'init', 'pyb_create_post_type' );
function pyb_create_post_type() {
  $labels = array(
    'name' => 'Proposals',
    'singular_name' => 'Proposal',
    'add_new' => 'Add New',
    'add_new_item' => 'Add New Proposal',
    'edit_item' => 'Edit Proposal',
    'new_item' => 'New Proposal',
    'all_items' => 'All Proposals',
    'view_item' => 'View Proposal',
    'search_items' => 'Search Proposals',
    'not_found' =>  'No Proposals found',
    'not_found_in_trash' => 'No Proposals found in Trash', 
    'parent_item_colon' => '',
    'menu_name' => 'Proposals'
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => array( 'slug' => 'proposal' ),
    'capability_type' => 'post',
    'has_archive' => false, 
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array( 'title', 'editor', 'comments' )
  ); 

  register_post_type( 'proposal', $args );
}

function pyb_rewrite_flush() {
    // First, we "add" the custom post type via the above written function.
    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
    // They are only referenced in the post_type column with a post entry, 
    // when you add a post of this CPT.
    pyb_create_post_type();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'pyb_rewrite_flush' );



/**
 * Uses custom template instead of standard theme single.php
 * 
 * @since 0.1
 */

// Filter the single_template with our custom function
function pyb_get_custom_post_type_template( $single_template ) {

     global $post;

     if ( $post->post_type == 'proposal' ) {
          $single_template = dirname( __FILE__ ) . '/website-template.php';
     }
     
     return $single_template;
}
add_filter( 'single_template', 'pyb_get_custom_post_type_template' );



/**
 * Re-write rules to make sure a visitor is coming from a sent email
 * 
 * @since 0.1
 */

// add rewrite rule for URL parsing 
function pyb_add_rewrite_rules() {
     
     add_rewrite_rule( 'proposal/([^/]+)/([^/]+)/?$', 'index.php?&post_type=proposal&name=$matches[1]&key=$matches[2]', 'top' );
     
}
add_action( 'init', 'pyb_add_rewrite_rules' );

// add query var for later comparing to post meta '_pyb_security_key'
function pyb_add_query_vars( $vars ) {
     
     $vars[] = 'key';
     return $vars;
     
}
add_filter( 'query_vars', 'pyb_add_query_vars' );

function pyb_security_check() {
     
     global $post;

     if( get_post_type() == 'proposal' ) {
          
          $key = get_post_meta( $post->ID, '_pyb_security_key', true );
          $incoming_key = get_query_var( 'key' );
     
          if( $key != $incoming_key && !is_user_logged_in()  ) {
               wp_die(
                    'You Shouldn\'t Be Here'
               );
          }
          
     }
}
add_action( 'template_redirect', 'pyb_security_check' );



/**
 * Include and setup custom metaboxes and fields.
 *
 * Shout out to the homies who created this library
 * https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'pyb_proposal_metaboxes' );
function pyb_proposal_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_pyb_';

	$meta_boxes[] = array(
		'id'         => 'email_metabox',
		'title'      => 'Email Details',
		'pages'      => array( 'proposal' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => 'Send email',
				'desc' => 'Enter a 1 to send a propsal email.',
				'id'   => $prefix . 'sendnow',
				'type' => 'text',
			),
			array(
				'name' => 'Client (company)',
				'desc' => 'Company name of client',
				'id'   => $prefix . 'client_company',
				'type' => 'text',
			),
			array(
				'name' => 'Client <br>(name)',
				'desc' => 'Name of client',
				'id'   => $prefix . 'client_name',
				'type' => 'text',
			),
			array(
				'name' => 'Step One',
				'desc' => 'General Summary of Step 1 in Project',
				'id'   => $prefix . 'step_one',
				'type' => 'text',
			),
			array(
				'name' => 'Step One Detailed',
				'desc' => 'Describe all actions to take place in Step 1',
				'id'   => $prefix . 'step_one_detailed',
				'type' => 'wysiwyg',
			),	
			array(
				'name' => 'ETC for Step One',
				'desc' => 'Estimated time of completion for Step 1',
				'id'   => $prefix . 'step_one_etc',
				'type' => 'text_date',
			),
			array(
				'name' => 'Step Two',
				'desc' => 'General Summary of Step 2 in Project',
				'id'   => $prefix . 'step_two',
				'type' => 'text',
			),
			array(
				'name' => 'Step Two Detailed',
				'desc' => 'Describe all actions to take place in Step 2',
				'id'   => $prefix . 'step_two_detailed',
				'type' => 'wysiwyg',
			),	
			array(
				'name' => 'ETC for Step Two',
				'desc' => 'Estimated time of completion for Step 2',
				'id'   => $prefix . 'step_two_etc',
				'type' => 'text_date',
			),	
			array(
				'name' => 'Step Three',
				'desc' => 'General Summary of Step 3 in Project',
				'id'   => $prefix . 'step_three',
				'type' => 'text',
			),
			array(
				'name' => 'Step Three Detailed',
				'desc' => 'Describe all actions to take place in Step 3',
				'id'   => $prefix . 'step_three_detailed',
				'type' => 'wysiwyg',
			),	
			array(
				'name' => 'ETC for Step Three',
				'desc' => 'Estimated time of completion for Step 3',
				'id'   => $prefix . 'step_three_etc',
				'type' => 'text_date',
			),						
			array(
				'name' => 'Client Email',
				'desc' => 'Enter email to send proposal to',
				'id'   => $prefix . 'client_email',
				'type' => 'text',
			),
			array(
				'name' => 'Prepared By (company)',
				'desc' => 'Company of preparer',
				'id'   => $prefix . 'prepared_company',
				'type' => 'text',
			),
			array(
				'name' => 'Prepared By (name)',
				'desc' => 'Name of preparer',
				'id'   => $prefix . 'prepared_person',
				'type' => 'text',
			),												
			array(
				'name' => 'Preparer Email',
				'desc' => 'Email of preparer',
				'id'   => $prefix . 'prepared_email',
				'type' => 'text',
			),
			array(
				'name' => 'Email Message',
				'desc' => 'Short message within email template',
				'id'   => $prefix . 'email_body',
				'type' => 'wysiwyg',
			),
			array(
				'name' => 'Start Date',
				'desc' => 'Start Date for Proposal',
				'id'   => $prefix . 'startdate',
				'type' => 'text_date',
			),
			array(
	            'name' => 'ETC',
	            'desc' => 'Estimated time of Completion',
	            'id'   => $prefix . 'etc',
	            'type' => 'text_date',
	      		),
	      		array(
				'name' => 'Preferred Budget',
				'desc' => 'The Contingent budget needed for project ',
				'id'   => $prefix . 'pre_budget',
				'type' => 'text_money',
			),
			array(
				'name' => 'Cost',
				'desc' => 'Total Cost',
				'id'   => $prefix . 'cost',
				'type' => 'text_money',
			),
      array(
				'name'    => 'Status',
				'desc'    => 'Status of Proposal (optional)',
				'id'      => $prefix . 'status',
				'type'    => 'select',
				'options' => array(
					array( 'name' => 'Awaiting Approval', 'value' => 'pending', ),
					array( 'name' => 'Accepted', 'value' => 'active', ),
					array( 'name' => 'Denied', 'value' => 'none', ),
				),
		),
	);

	

			
			),
			
			
			
			
		),
	);

	$meta_boxes[] = array(
		'id'         => 'about_page_metabox',
		'title'      => 'About Page Metabox',
		'pages'      => array( 'page', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'show_on'    => array( 'key' => 'id', 'value' => array( 2, ), ), // Specific post IDs to display this metabox
		'fields' => array(
			array(
				'name' => 'Test Text',
				'desc' => 'field description (optional)',
				'id'   => $prefix . 'test_text',
				'type' => 'text',
			),
		)
	);

	return $meta_boxes;
}

add_action( 'init', 'pyb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function pyb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'meta-boxes/init.php';

}
function pyb_remove_disqus() {

     if( 'proposal' == get_post_type() ) {
     
          add_filter( 'comments_template', 'pyb_plugin_comment_template' );
          
          remove_action('init', 'dsq_request_handler');
          remove_action('parse_request', 'dsq_parse_query');
          remove_action('the_posts', 'dsq_maybe_add_post_ids');
          remove_action('loop_end', 'dsq_loop_end');
          remove_action('wp_footer', 'dsq_output_footer_comment_js');
          remove_action('pre_post_update', 'dsq_prev_permalink');
          remove_action('pre_comment_on_post', 'dsq_pre_comment_on_post');
          
          remove_filter('plugin_action_links', 'dsq_plugin_action_links', 10, 2);
          remove_filter('comments_open', 'dsq_comments_open');
          remove_filter('comments_template', 'dsq_comments_template');
          remove_filter('comments_number', 'dsq_comments_text');
          remove_filter('get_comments_number', 'dsq_comments_number');
          remove_filter('bloginfo_url', 'dsq_bloginfo_url');
     }
}

add_filter( 'comment_post_redirect', 'pyb_comment_redirect' );
function pyb_comment_redirect( $url ) {    
     
     global $post;
     
     $url  = explode( '/', $url );
     $hash = array_pop( $url );
     $url  = implode( '/', $url );
     $url  = $url . '/' . get_post_meta( $post->ID, '_pyb_security_key', true ) . '/' . $hash;      

     wp_safe_redirect( $url );
}


add_action( 'template_redirect', 'pyb_remove_disqus' );

// Use our own plugin custom comments template
function pyb_plugin_comment_template( $comment_template ) {
     global $post;
     if ( !( is_singular() && ( have_comments() || 'open' == $post->comment_status ) ) ) {
        return;
     }
     if( $post->post_type == 'proposal' ) {
        return dirname(__FILE__) . '/comments.php';
     }
}



// custom comment display function
function pyb_comment( $comment ) { ?>

	<li <?php comment_class( 'row' ); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>">
     		<div class="three columns mobile-one">
     		<?php 
				
     			$avatar_size = 68;
          		if ( '0' != $comment->comment_parent )
          			$avatar_size = 39;
											
          		echo get_avatar( $comment, $avatar_size ); ?>		
          		
          		<footer class="comment-meta">
          		     <div class="comment-author vcard">
          		     	<?php
          		
          		     		/* translators: 1: comment author, 2: date and time */
          		     		printf( __( '%1$s %2$s', 'pyb' ),
          		     			sprintf( '<span class="fn">%s</span>', get_comment_author() ),
          		     			sprintf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
          		     				esc_url( get_comment_link( $comment->comment_ID ) ),
          		     				get_comment_time( 'c' ),
          		     				/* translators: 1: date, 2: time */
          		     				sprintf( __( '%1$s at %2$s', 'pyb' ), get_comment_date(), get_comment_time() )
          		     			)
          		     		);
          		     	?>
          		
          		     	<?php edit_comment_link( __( 'Edit', 'pyb' ), '<span class="edit-link">', '</span>' ); ?>
          		     </div><!-- .comment-author .vcard -->          		
          		</footer>          		
     		</div>

			<div class="comment-content offset-by-one eight columns mobile-three">
     			<?php comment_text(); ?>
               </div>
		</article><!-- #comment-## --><?php

}
