<div class="top-header">

    <div class="header-container">

        <div class="wrapper d-flex align-items-center justify-content-between gap-3 py-3">

            <!-- RIGHT -->
            <div class="d-flex align-items-center gap-3 flex-grow-1">

                <!-- LOGO -->
                <a href="#" class="header-logo text-decoration-none">

                    <img src="https://www.digikala.com/statics/img/svg/logo.svg" alt="logo">

                </a>

                <!-- SEARCH -->
                <div class="search-box">

                    <form>

                        <input type="text"
                            class="form-control search-input"
                            placeholder="Search">

                    </form>

                </div>

            </div>

            <!-- LEFT -->
            <div class="d-flex align-items-center gap-2">

                <!-- NOTIFICATION -->
                <button class="btn btn-light icon-btn">

                    <i class="bi bi-bell fs-5"></i>

                </button>

                <!-- LOGIN -->
                <?php if (is_user_logged_in()) : ?>
                    <a href="#"
                        class="btn btn-outline-dark login-btn d-flex align-items-center gap-2">
                        <i class="bi bi-person"></i>
                    
                        <span>my account</span>
                    </a>
                <?php else : ?>
                    <a href="<?php echo site_url('/auth'); ?>"
                        class="btn btn-outline-dark login-btn d-flex align-items-center gap-2">
                        <i class="bi bi-person"></i>
                    
                        <span>Sign in | Sign up</span>
                    </a>
                <?php endif; ?>

                <!-- CART -->
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>"
                   class="btn btn-light icon-btn position-relative">
                
                    <i class="bi bi-cart3 fs-5"></i>
                
                    <span id="octo-cart-count" class="cart-count">
                        <?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
                    </span>
                
                </a>

            </div>

        </div>

    </div>

</div>