<?php

class Octo_Mega_Menu_Walker extends Walker_Nav_Menu
{

    private $current_parent = null;

    private $child_items = [];



    /*
    |--------------------------------------------------------------------------
    | Start Level
    |--------------------------------------------------------------------------
    */

    function start_lvl(&$output, $depth = 0, $args = null)
    {


    }



    /*
    |--------------------------------------------------------------------------
    | End Level
    |--------------------------------------------------------------------------
    */

    function end_lvl(&$output, $depth = 0, $args = null)
    {
            if ($depth === 0) {

            $output .= '<div class="mega-menu">';
            $output .= '<div class="mega-menu-wrapper">';

            /*
            |--------------------------------------------------------------------------
            | Sidebar
            |--------------------------------------------------------------------------
            */

            $output .= '<div class="mega-sidebar">';

            foreach ($this->child_items as $index => $item) {

                $slug = get_post_meta(
                    $item->ID,
                    '_octo_mega_menu_slug',
                    true
                );

                $active = $index === 0 ? 'active' : '';

                $output .= '

                    <button
                        class="mega-link ' . $active . '"
                        data-target="' . esc_attr($slug) . '"
                    >

                        ' . esc_html($item->title) . '

                    </button>

                ';
            }

            $output .= '</div>';



            /*
            |--------------------------------------------------------------------------
            | Content
            |--------------------------------------------------------------------------
            */

            $output .= '<div class="mega-content">';

            foreach ($this->child_items as $index => $item) {

                $slug = get_post_meta(
                    $item->ID,
                    '_octo_mega_menu_slug',
                    true
                );

                $block = get_page_by_path(
                    $slug,
                    OBJECT,
                    'octo_block'
                );

                if (!$block) {
                    continue;
                }

                $active = $index === 0 ? 'active' : '';

                $output .= '

                    <div
                        class="mega-panel ' . $active . '"
                        id="' . esc_attr($slug) . '"
                    >

                ';

                $output .= apply_filters(
                    'the_content',
                    $block->post_content
                );

                $output .= '</div>';
            }

            $output .= '</div>';

            $output .= '</div>';

            $output .= '</div>';
        }
    }



    /*
    |--------------------------------------------------------------------------
    | Start Element
    |--------------------------------------------------------------------------
    */

    function start_el(
        &$output,
        $item,
        $depth = 0,
        $args = null,
        $id = 0
    ) {

        $classes = empty($item->classes)
            ? []
            : (array) $item->classes;

        $has_children = in_array(
            'menu-item-has-children',
            $classes
        );



        /*
        |--------------------------------------------------------------------------
        | Parent Item
        |--------------------------------------------------------------------------
        */

        if ($depth === 0) {

            if ($has_children) {

                $this->current_parent = $item;

                $this->child_items = [];

                $output .= '

                    <li class="nav-item mega-menu-item position-relative">

                        <a
                            href="' . esc_url($item->url) . '"
                            class="nav-link fw-bold text-dark d-flex align-items-center gap-2"
                        >

                            ' . esc_html($item->title) . '

                        </a>

                ';
            } else {

                $output .= '

                    <li class="nav-item">

                        <a
                            href="' . esc_url($item->url) . '"
                            class="nav-link"
                        >

                            ' . esc_html($item->title) . '

                        </a>

                    </li>

                ';
            }
        }



        /*
        |--------------------------------------------------------------------------
        | Child Items
        |--------------------------------------------------------------------------
        */

        if ($depth === 1) {

            $this->child_items[] = $item;
        }
    }



    /*
    |--------------------------------------------------------------------------
    | End Element
    |--------------------------------------------------------------------------
    */

    function end_el(&$output, $item, $depth = 0, $args = null)
    {

        $classes = empty($item->classes)
            ? []
            : (array) $item->classes;

        $has_children = in_array(
            'menu-item-has-children',
            $classes
        );

        if ($depth === 0 && $has_children) {

            $output .= '</li>';
        }
    }
}