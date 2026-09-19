const authState = {
    token: null,
    step: null
};


//start verify phone and select step
document
.querySelector('#send-phone')
.addEventListener('click', function () {
    
    const phone = document.querySelector('#phone').value;

    const formData = new FormData();
    

    formData.append('action', 'octo_check_phone');
    formData.append('phone', phone);
    
    const btn = document.querySelector('#send-phone');
    btn.disabled = true;
    btn.innerText = 'Checking...';

    fetch(octoAuth.ajaxUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        

        if (!result.success) {
            
            showStatus();
            showStatus(result.data.message);
            btn.disabled = false;
            btn.innerText = 'Continue';

            return;
        }
        
        switch (result.data.step) {

            case 'password':
                authState.token = result.data.token;
                goStep('step-password');
                break;
        
            case 'otp':
                authState.token = result.data.token;
                updateOtpView('register');
                goStep('step-otp');
                sendOtp(authState.token);
        }

    });

});
//end verify phone and select step



//start sending otp
function sendOtp(token)
{
    const formData = new FormData();

    formData.append('action', 'octo_send_otp');
    formData.append('token', token);

    fetch(octoAuth.ajaxUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {

        if (!result.success) {

            alert(result.data.message);
            return;
        }

        startTimer(result.data.expire);

    });
}
//end sending otp



//Start of otp confirmation operation
document
.querySelector('#verify-otp')
.addEventListener('click', function () {

    const code = document.querySelector('#otp-code').value.trim();

    if (!code) {
        alert('Please enter the verification code.');
        return;
    }

    verifyOtp(authState.token, code);

});

function verifyOtp(token, code)
{
    const formData = new FormData();

    formData.append('action', 'octo_verify_otp');
    formData.append('token', token);
    formData.append('code', code);

    fetch(octoAuth.ajaxUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {

        if (!result.success) {

            showStatus('step-otp',"");
            
            setTimeout(() => {
                showStatus('step-otp', result.data.message);
            }, 10);
            
            return;
        }

        
        switch(result.data.step){

            case 'register':
        
                goStep('step-register');
                break;
        
            case 'login':
        
                loginWithOtp();
                break;
        
            case 'reset_password':
        
                goStep('step-reset-password');
                break;
        
        }


    });

}
//End of otp confirmation operation



//use from otp login and call in verifyotp function
function loginWithOtp()
{
    const formData = new FormData();

    formData.append('action', 'octo_login_with_otp');
    formData.append('token', authState.token);

    fetch(octoAuth.ajaxUrl, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(result => {

        if (!result.success) {

            showStatus('step-otp', result.data.message);
            return;

        }

        if (result.data.redirect) {

            window.location.href = result.data.redirect;
            return;

        }

    });

}




//Start resend otp
document
.querySelector('#resend-btn')
.addEventListener('click', function () {

    sendOtp(authState.token);

});
//End resend otp



//Start register user
document
.querySelector('#register-btn')
.addEventListener('click', function () {

    const formData = new FormData();

    formData.append('action', 'octo_register');
    formData.append('token', authState.token);
    formData.append(
        'name',
        document.querySelector('#register-name').value.trim()
    );
    formData.append(
        'password',
        document.querySelector('#register-password').value
    );
    formData.append(
        'password_confirmation',
        document.querySelector('#register-password-confirm').value
    );

    fetch(octoAuth.ajaxUrl, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(result => {

        if (!result.success) {
            
            showStatus('step-register',"");

            setTimeout(() => {
                showStatus('step-register', result.data.message);
            }, 10);
            return;

        }

        window.location.reload();

    });

});
//End register user



//start user login
document
.querySelector('#login-btn')
.addEventListener('click', loginWithPassword);

function loginWithPassword()
{
    const formData = new FormData();

    formData.append('action', 'octo_login');
    formData.append('token', authState.token);
    formData.append(
        'password',
        document.querySelector('#login-password').value
    );

    fetch(octoAuth.ajaxUrl,{
        method:'POST',
        body:formData
    })
    .then(res=>res.json())
    .then(result => {
    
        if (!result.success) {
            
            showStatus('step-password',"");

            setTimeout(() => {
                showStatus('step-password', result.data.message);
            }, 10);
            
            return;
    
        }
    
        if (result.data.redirect) {
    
            window.location.href = result.data.redirect;
            return;
    
        }
    
    });
}
//end user login


//start login with otp
document
.getElementById('login-otp-btn')
.addEventListener('click', function () {
    
    updateOtpView('login');

    goStep('step-otp');

    sendOtp(authState.token);

});
//end login with otp


//start forget password
document
.getElementById('forgot-password-btn')
.addEventListener('click', forgotPassword);


function forgotPassword()
{
    const formData = new FormData();

    formData.append(
        'action',
        'octo_forgot_password'
    );

    formData.append(
        'token',
        authState.token
    );

    fetch(octoAuth.ajaxUrl,{
        method:'POST',
        body:formData
    })
    .then(res => res.json())
    .then(result => {
        
        if (!result.success) {

            if (result.data.errors) {
        
                showStatusList(
                    'step-password',
                    result.data.errors
                );
        
            } else {
        
                showStatus(
                    'step-password',
                    result.data.message
                );
        
            }
        
            return;
        }

        authState.token = result.data.token;
        
        updateOtpView('reset_password');

        goStep('step-otp');

        sendOtp(authState.token);

    });

}
//end forget password


//start reset password
document
.getElementById('reset-password-btn')
.addEventListener(
    'click',
    resetPassword
);
function resetPassword()
{
    const formData = new FormData();

    formData.append(
        'action',
        'octo_reset_password'
    );

    formData.append(
        'token',
        authState.token
    );

    formData.append(
        'password',
        document
            .querySelector('#reset-password')
            .value
    );

    formData.append(
        'password_confirm',
        document
            .querySelector('#reset-password-confirm')
            .value
    );

    fetch(octoAuth.ajaxUrl,{
        method:'POST',
        body:formData
    })
    .then(res=>res.json())
    .then(result=>{
        
        if (!result.success) {

            if (result.data.errors) {
        
                showStatusList(
                    'step-reset-password',
                    result.data.errors
                );
        
            } else {
        
                showStatus(
                    'step-reset-password',
                    result.data.message
                );
        
            }
        
            return;
        }

        window.location.href =
            result.data.redirect;

    });

}
//end reset password

//helpers
function showStatus(stepId, message, type = 'octo-error')
{
    const step = document.getElementById(stepId);

    if (!step) return;

    const status = step.querySelector('.octo-status');

    if (!status) return;
    
    if (Array.isArray(message)) {
        showStatusList(stepId, message);
        return;
    }

    status.innerText = message;
    status.className = 'octo-status ' + type;
}

function showStatusList(stepId, items, type = 'octo-error')
{
    const step = document.getElementById(stepId);

    if (!step) return;

    const status = step.querySelector('.octo-status');

    if (!status) return;

    status.className = type;

    status.innerHTML =
        '<ul><li>' +
        items.join('</li><li>') +
        '</li></ul>';
    status.className = 'octo-status ' + type;
}
function goStep(id){

    document.querySelectorAll('.step').forEach(el=>{
        el.classList.remove('active');
    });

    document.getElementById(id).classList.add('active');

}

let otpTimer = null;

function startTimer(seconds)
{
    const counter = document.querySelector('#otp-counter');
    const resendBtn = document.querySelector('#resend-btn');

    clearInterval(otpTimer);

    let timeLeft = seconds;

    resendBtn.disabled = true;

    otpTimer = setInterval(() => {

        const minutes = Math.floor(timeLeft / 60);
        const secs = timeLeft % 60;

        counter.textContent =
            `${minutes}:${secs.toString().padStart(2, '0')}`;

        timeLeft--;

        if (timeLeft < 0) {

            clearInterval(otpTimer);

            counter.textContent =
                "Didn't receive the code?";

            resendBtn.disabled = false;

        }

    }, 1000);
}

function updateOtpView(flow)
{
    const title = document.querySelector('#step-otp h5');
    const button = document.querySelector('#step-otp button');
    const input = document.getElementById('otp-code');

    switch (flow) {

        case 'register':

            title.innerText = 'Verify your phone number';
            button.innerText = 'Verify';
            input.placeholder = 'Verification code';
            break;

        case 'login':

            title.innerText = 'Login with One-Time Password';
            button.innerText = 'Sign In';
            input.placeholder = 'One-time password';
            break;

        case 'reset_password':

            title.innerText = 'Reset Password';
            button.innerText = 'Continue';
            input.placeholder = 'Reset code';
            break;

    }
}
