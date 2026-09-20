<!-- BOTTOM NAV -->
<nav class="bottom-nav">

    <div class="header-container">

        <div class="d-flex align-items-center justify-content-between gap-3">

            <?php

            wp_nav_menu([
            
                'theme_location' => 'main-menu',
            
                'container' => false,
            
                'menu_class' => 'nav gap-4',
            
                'fallback_cb' => false,
            
                'walker' => new Octo_Mega_Menu_Walker(),
            
            ]);

            ?>

            <!-- LOCATION -->
            <button class="location-btn d-flex align-items-center gap-2 my-1">

                <i class="bi bi-geo-alt"></i>

                <span>انتخاب آدرس</span>

            </button>

        </div>

    </div>

</nav>

