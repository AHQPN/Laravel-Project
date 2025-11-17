document.addEventListener('DOMContentLoaded', function () {

    // --- Helpers ---
    const showError = (input, message) => {
        const formGroup = input.parentElement;
        const messageEl = formGroup.querySelector('.form-message');
        messageEl.innerText = message;
        input.classList.add('is-invalid');
    };

    const showSuccess = (input) => {
        const formGroup = input.parentElement;
        const messageEl = formGroup.querySelector('.form-message');
        messageEl.innerText = '';
        input.classList.remove('is-invalid');
    };

    // ✅ SỬA: Validate số điện thoại thay vì email
    const validatePhone = (input) => {
        const re = /^[0-9]{10}$/; // Số điện thoại Việt Nam: 10 chữ số
        if (re.test(String(input.value).trim())) {
            showSuccess(input);
            return true;
        } else {
            showError(input, 'Số điện thoại không hợp lệ (phải là 10 chữ số).');
            return false;
        }
    };

    const validateEmail = (input) => {
        const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (re.test(String(input.value).toLowerCase())) {
            showSuccess(input);
            return true;
        } else {
            showError(input, 'Email không hợp lệ.');
            return false;
        }
    };

    const validateRequired = (input, fieldName) => {
        if (input.value.trim() === '') {
            showError(input, `${fieldName} là bắt buộc.`);
            return false;
        } else {
            showSuccess(input);
            return true;
        }
    };

    const validateLength = (input, min, fieldName) => {
        if (input.value.length < min) {
            showError(input, `${fieldName} phải có ít nhất ${min} ký tự.`);
            return false;
        } else {
            showSuccess(input);
            return true;
        }
    };

    const validateMatch = (input1, input2) => {
        if (input1.value !== input2.value) {
            showError(input2, 'Mật khẩu xác nhận không khớp.');
            return false;
        } else {
            showSuccess(input2);
            return true;
        }
    };

    // --- Form 1: Tìm kiếm chuyến đi ---
    const findTripForm = document.getElementById('find-trip-form');
    if (findTripForm) {
        findTripForm.addEventListener('submit', function (e) {
            let isValid = true;

            const fromCity = document.getElementById('fromCity');
            const toCity = document.getElementById('toCity');
            const date = document.getElementById('date');

            if (!validateRequired(fromCity, 'Điểm đi')) isValid = false;
            if (!validateRequired(toCity, 'Điểm đến')) isValid = false;
            if (!validateRequired(date, 'Ngày đi')) isValid = false;

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // --- Form 2: Đăng nhập ---
    const loginForm = document.getElementById('login-form1');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            let isValid = true;

            // ✅ SỬA: Đổi ID thành 'Phone-number-login' (từ form)
            const phone = document.getElementById('Phone-number-login');
            const password = document.getElementById('Pw-login');

            // ✅ SỬA: Validate số điện thoại thay vì email
            if (!validateRequired(phone, 'Số điện thoại')) isValid = false;
            else if (!validatePhone(phone)) isValid = false;

            if (!validateRequired(password, 'Mật khẩu')) isValid = false;

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // --- Form 3: Đăng ký ---
    const registerForm = document.getElementById('register-form1');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            let isValid = true;

            const tenkh = document.getElementById('tenkh');
            // ✅ SỬA: Đổi ID thành 'Phone-number' (từ form đăng ký)
            const phone = document.getElementById('Phone-number');
            const address = document.getElementById('Address');
            const password = document.getElementById('Pw-register');
            const passwordConfirm = document.getElementById('Pw-confrim');

            if (!validateRequired(tenkh, 'Họ tên')) isValid = false;

            // ✅ SỬA: Validate số điện thoại thay vì email
            if (!validateRequired(phone, 'Số điện thoại')) isValid = false;
            else if (!validatePhone(phone)) isValid = false;

            if (!validateRequired(password, 'Mật khẩu')) isValid = false;
            else if (!validateLength(password, 6, 'Mật khẩu')) isValid = false;

            if (!validateRequired(passwordConfirm, 'Xác nhận mật khẩu')) isValid = false;
            else if (password.value.length >= 6 && !validateMatch(password, passwordConfirm)) isValid = false;

            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});
