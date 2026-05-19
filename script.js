let selectedService = '';
let selectedPrice = 0;
let selectedTimeVal = '';

function selectService(name, price) {
    selectedService = name;
    selectedPrice = price;
    
    // Update booking form fields
    const paymentSections = document.querySelectorAll('.payment-card');
    paymentSections.forEach(section => {
        section.style.display = 'block';
        const serviceNameEl = section.querySelector('#selected-service-name');
        const servicePriceEl = section.querySelector('#service-price');
        
        if (serviceNameEl) serviceNameEl.textContent = 'الخدمة: ' + name;
        if (servicePriceEl) servicePriceEl.textContent = price.toLocaleString('ar-SD');
    });

    // Scroll to form
    document.querySelector('.payment-card').scrollIntoView({ behavior: 'smooth' });
}

function selectTime(element, time) {
    // Remove active class from all slots
    const slots = document.querySelectorAll('.slot');
    slots.forEach(slot => slot.classList.remove('active'));
    
    // Add active class to clicked slot
    element.classList.add('active');
    selectedTimeVal = time;
    
    const timeInput = document.getElementById('selected-time-value');
    if (timeInput) timeInput.value = time;
}

function processPayment() {
    const patientName = document.getElementById('patient-name').value.trim();
    const patientPhone = document.getElementById('patient-phone').value.trim();
    
    if (!patientName) {
        alert('الرجاء إدخال اسم المريض');
        return;
    }
    if (!patientPhone) {
        alert('الرجاء إدخال رقم الهاتف');
        return;
    }
    if (!selectedTimeVal) {
        alert('الرجاء اختيار الموعد المتاح');
        return;
    }
    if (!selectedService) {
        alert('الرجاء اختيار الخدمة أولاً');
        return;
    }

    const bookingData = {
        patient_name: patientName,
        phone: patientPhone,
        service_name: selectedService,
        booking_time: selectedTimeVal,
        price: selectedPrice
    };

    fetch('process_booking.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(bookingData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Reset form
            document.getElementById('patient-name').value = '';
            document.getElementById('patient-phone').value = '';
            const slots = document.querySelectorAll('.slot');
            slots.forEach(slot => slot.classList.remove('active'));
            selectedTimeVal = '';
            
            // Hide section
            const paymentSections = document.querySelectorAll('.payment-card');
            paymentSections.forEach(section => section.style.display = 'none');
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء الاتصال بالخادم، يرجى المحاولة لاحقاً.');
    });
}
