<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class MXALFWPCPTGenerator
{

    /*
    * Observe function
    */
    public static function createCPT()
    {

        // create CPT
        add_action( 'init', ['MXALFWPCPTGenerator', 'customPostsInit'] );

        // manage columns
        // add ID column to the table
        add_filter( 'manage_mxalfwp_books_posts_columns', ['MXALFWPCPTGenerator', 'addIdColumn'], 20, 1 );

            // manage ID column
            add_action( 'manage_mxalfwp_books_posts_custom_column', ['MXALFWPCPTGenerator', 'booksColumnRow'], 20, 2 );

    }

    /*
    * Manage new column
    */
    public static function booksColumnRow( $column, $post_id )
    {

        if ($column === 'book_id') {
            echo 'Book ID = ' . $post_id;
        }

    }

    /*
    * Add new column to the Custom Post Type
    */
    public static function addIdColumn( $columns )
    {

        $newColumn  = ['book_id' => 'Book ID'];

        $newColumns = mxalfwpInsertNewColumnToPosition( $columns, 3, $newColumn );

        return $newColumns;

    }

    /*
    * Create a Custom Post Type
    */
    public static function customPostsInit()
    {
        
        register_post_type( 'mxalfwp_books', [

            'labels' => [
                'name'               => 'Books',
                'singular_name'      => 'Book',
                'add_new'            => 'Add a new one',
                'add_new_item'       => 'Add a new book',
                'edit_item'          => 'Edit the book',
                'new_item'           => 'New book',
                'view_item'          => 'See the book',
                'search_items'       => 'Find a book',
                'not_found'          =>  'Books not found',
                'not_found_in_trash' => 'No books found in the trash',
                'parent_item_colon'  => '',
                'menu_name'          => 'Books'

            ],
            'show_in_rest'       => true,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => true,
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments']

        ] );

        // Rewrite rules
        if (is_admin() && get_option( 'mxalfwp_flush_rewrite_rules' ) == 'go_flush_rewrite_rules') {

            delete_option( 'mxalfwp_flush_rewrite_rules' );

            flush_rewrite_rules();

        }

        /*
        * add metaboxes
        */
        
        // add text input
        new MXALFWPMetaboxesGenerator(
            [
                'id'         => 'text-metabox',
                'post_types' => 'mxalfwp_books',
                'name'       => esc_html( 'Text field', 'mxalfwp-domain' )
            ]
        );

        // add email input
        new MXALFWPMetaboxesGenerator(
            [
                'id'           => 'email-metabox',
                'post_types'   => 'mxalfwp_books',
                'name'         => esc_html( 'E-mail field', 'mxalfwp-domain' ),
                'metabox_type' => 'input-email'
            ]
        );

        // add url input
        new MXALFWPMetaboxesGenerator(
            [
                'id'           => 'url-metabox',
                'post_types'   => 'mxalfwp_books',
                'name'         => esc_html( 'URL field', 'mxalfwp-domain' ),
                'metabox_type' => 'input-url'
            ]
        );

        // description
        new MXALFWPMetaboxesGenerator(
            [
                'id'           => 'desc-metabox',
                'post_types'   => 'mxalfwp_books',
                'name'         => esc_html( 'Some Description', 'mxalfwp-domain' ),
                'metabox_type' => 'textarea'
            ]
        );

        // add checkboxes
        new MXALFWPMetaboxesGenerator(
            [
                'id'           => 'checkboxes-metabox',
                'post_types'   => 'mxalfwp_books',
                'name'         => esc_html( 'Checkbox Buttons', 'mxalfwp-domain' ),
                'metabox_type' => 'checkbox',
                'options' => [
                    [
                        'value'   => 'Dog',
                        'checked' => true
                    ],
                    [
                        'value'   => 'Cat'
                    ],
                    [
                        'value'   => 'Fish'
                    ]        
                ]
            ]
        );

        // add radio buttons
        new MXALFWPMetaboxesGenerator(
            [
                'id'           => 'radio-buttons-metabox',
                'post_types'   => 'mxalfwp_books',
                'name'         => esc_html( 'Radio Buttons', 'mxalfwp-domain' ),
                'metabox_type' => 'radio',
                'options' => [
                    [
                        'value'   => 'red'
                    ],
                    [
                        'value'   => 'green'
                    ],
                    [
                        'value'   => 'Yellow',
                        'checked' => true
                    ]        
                ]
            ]
        );

        // image upload
        new MXALFWPMetaboxesGenerator(
            [
                'id'           => 'featured-image-metabox',
                'post_types'   => 'mxalfwp_books',
                'name'         => esc_html( 'Featured image', 'mxalfwp-domain' ),
                'metabox_type' => 'image'
            ]
        );

    }

}
