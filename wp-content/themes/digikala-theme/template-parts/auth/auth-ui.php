<div class="auth-wrapper">

    <div class="auth-box">

        <!-- STEP 1: PHONE -->
        <div class="step active" id="step-phone">

            <h5 class="mb-3">Sign in | Sign up</h5>
            
            <div class="octo-status"></div>

            <input 
                id="phone" 
                type="text" 
                class="form-control mb-1" 
                placeholder="Phone Number" 
                autocomplete="off">
                

            <button class="btn btn-octo w-100 mt-3" id="send-phone">
                Continue
            </button>

        </div>

        <!-- STEP 2: PASSWORD LOGIN -->
        <div class="step" id="step-password">
        
            <h5 class="mb-3">Sign In with Password</h5>
            
            <div class="octo-status"></div>
        
            <input
                type="password"
                id="login-password"
                class="form-control mb-3"
                placeholder="Password"
                autocomplete="current-password">
                
            
            <button
                class="btn btn-octo w-100 mb-2"
                id="login-btn">
            
                Sign In
            
            </button>
        
            <div class="d-flex justify-content-between small-links">
        
                <a id="login-otp-btn">
                    Use One-Time Password
                </a>
        
                <a id="forgot-password-btn">
                    Forgot Password
                </a>
        
            </div>
        
        </div>
        
        <!-- STEP 3: OTP -->
        <div class="step" id="step-otp">
        
            <h5 class="mb-3">Verification Code</h5>
            
            <div class="octo-status"></div>
            
            <input
                type="text"
                id="otp-code"
                class="form-control mb-3"
                placeholder="Verification code" 
                autocomplete="one-time-code">
            
            <button
                id="verify-otp"
                class="btn btn-octo w-100">
                Verify
            </button>
        
            <div class="text-center mt-3">
        
                <span id="otp-counter">
                    120 seconds
                </span>
        
            </div>
        
            <div class="text-center mt-2">
        
                <button
                    type="button"
                    id="resend-btn"
                    class="btn btn-link p-0"
                    disabled>
        
                    Resend Code
        
                </button>
        
            </div>
        
        </div>
        
        <!-- STEP 4: REGISTER USER -->
        <div class="step" id="step-register">
        
            <h5 class="mb-3">
                Create Account
            </h5>
            
            <div class="octo-status"></div>
        
            <input
                type="text"
                class="form-control mb-3"
                id="register-name"
                placeholder="Full Name"
                autocomplete="off">
        
            <input
                type="password"
                class="form-control mb-3"
                id="register-password"
                placeholder="Password"
                autocomplete="new-password">
        
            <input
                type="password"
                class="form-control mb-3"
                id="register-password-confirm"
                placeholder="Confirm Password"
                autocomplete="new-password">
                
        
            <button
                class="btn btn-octo w-100"
                id="register-btn">
        
                Create Account
        
            </button>
        
            <div class="status mt-3"></div>
        
        </div>
        
        <!-- STEP : RESET PASSWORD -->
        <div class="step" id="step-reset-password">
        
            <h5 class="mb-3">
                Create New Password
            </h5>
        
            <p class="text-muted small mb-3">
                Your identity has been verified. Please choose a new password.
            </p>
        
            <div class="octo-status"></div>
        
            <input
                type="password"
                id="reset-password"
                class="form-control mb-3"
                placeholder="New Password"
                autocomplete="new-password">
        
            <input
                type="password"
                id="reset-password-confirm"
                class="form-control mb-3"
                placeholder="Confirm New Password"
                autocomplete="new-password">
                
        
            <button
                id="reset-password-btn"
                class="btn btn-octo w-100">
        
                Save Password
        
            </button>
        
        </div>
        

    </div>

</div>