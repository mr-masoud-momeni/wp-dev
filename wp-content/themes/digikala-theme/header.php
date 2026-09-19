<!DOCTYPE html>
<html lang="en" dir="itr">

<head>

    <meta charset="<?php bloginfo('charset'); ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <!-- HEADER -->
    <header class="main-header">
        <?php get_template_part('template-parts/header/top-header'); ?>
        <!-- TOP HEADER -->

        <?php get_template_part('template-parts/header/bottom-nav'); ?>
        <!-- BOTTOM NAV -->
        
    </header>