<div class="top-header">

    <div class="header-container">

        <div class="wrapper d-flex align-items-center justify-content-between gap-3 py-3">

            <!-- RIGHT -->
            <div class="d-flex align-items-center gap-3 flex-grow-1">

                <!-- LOGO -->
                <a href="<?php echo esc_url(home_url('/')); ?>" class="header-logo text-decoration-none">

                    <img src="https://www.digikala.com/statics/img/svg/logo.svg" alt="<?php bloginfo('name'); ?>">

                </a>

                <!-- SEARCH -->
                <div class="search-box">

                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">

                        <input type="text"
                            name="s"
                            value="<?php echo esc_attr(get_search_query()); ?>"
                            class="form-control search-input"
                            placeholder="جستجو در فروشگاه...">

                    </form>

                </div>

            </div>

            <!-- LEFT -->
            <div class="d-flex align-items-center gap-2">

                <!-- NOTIFICATION -->
                <button class="btn icon-btn" type="button" aria-label="اعلان‌ها">

                    <i class="bi bi-bell fs-5"></i>

                </button>

                <!-- LOGIN -->
                <?php if (is_user_logged_in()) : ?>
                    <a href="#"
                        class="btn login-btn d-flex align-items-center gap-2">
                        <i class="bi bi-person"></i>
                        <span>حساب کاربری</span>
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(site_url('/auth')); ?>"
                        class="btn login-btn d-flex align-items-center gap-2">
                        <i class="bi bi-person"></i>
                        <span>ورود | ثبت‌نام</span>
                    </a>
                <?php endif; ?>

                <!-- CART -->
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>"
                   class="btn icon-btn position-relative"
                   aria-label="سبد خرید">

                    <i class="bi bi-cart3 fs-5"></i>

                    <span id="octo-cart-count" class="cart-count">
                        <?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
                    </span>

                </a>

            </div>

        </div>

    </div>

</div>