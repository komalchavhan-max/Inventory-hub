// validation.js - Complete validation for all forms

const Validation = {
    init: function() {
        const forms = document.querySelectorAll('.needs-validation');
    
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!Validation.validateForm(this)) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                this.classList.add('was-validated');
            });

            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    Validation.validateField(this);
                });
                input.addEventListener('blur', function() {
                    Validation.validateField(this);
                });
            });
        });
        
        Validation.initCharacterCounters();
    },
    
    initCharacterCounters: function() {
        document.querySelectorAll('[data-maxlength]').forEach(textarea => {
            const maxLength = parseInt(textarea.dataset.maxlength);
            const counterId = textarea.id + 'Count';
            
            function updateCount() {
                const length = textarea.value.length;
                const counter = document.getElementById(counterId);
                if (counter) {
                    counter.textContent = length;
                    if (length > maxLength) {
                        counter.classList.add('text-danger');
                    } else {
                        counter.classList.remove('text-danger');
                    }
                }
            }
            
            textarea.addEventListener('input', updateCount);
            updateCount();
        });
    },
    
    validateForm: function(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            if (!Validation.validateField(input)) {
                isValid = false;
            }
        });
        
        return isValid;
    },
     
    validateField: function(field) {
        if (!field) return true;
        
        // Required field validation
        if (field.hasAttribute && field.hasAttribute('required') && !field.value.trim()) {
            Validation.showError(field, 'This field is required');
            return false;
        }
        
        // Skip validation if field is empty and not required
        if ((!field.hasAttribute || !field.hasAttribute('required')) && !field.value.trim()) {
            Validation.showNeutral(field);
            return true;
        }
        
        const value = field.value.trim();
        
        // Email validation
        if (field.type === 'email') {
            const emailPattern = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
            if (!emailPattern.test(value)) {
                Validation.showError(field, 'Please enter a valid email address');
                return false;
            }
        }
        
        // Password validation
        else if (field.id === 'password' || field.name === 'password') {
            if (value.length < 8) {
                Validation.showError(field, 'Password must be at least 8 characters');
                return false;
            }
            if (value.length > 50) {
                Validation.showError(field, 'Password must be less than 50 characters');
                return false;
            }
        }
        
        // Password confirmation
        else if (field.id === 'password_confirmation' || field.name === 'password_confirmation') {
            const passwordField = field.form ? field.form.querySelector('#password, [name="password"]') : null;
            if (passwordField && value !== passwordField.value) {
                Validation.showError(field, 'Password confirmation does not match');
                return false;
            }
        }
        
        // Equipment Name validation
        else if (field.name === 'name' && field.closest && field.closest('#equipmentForm')) {
            if (value.length < 3) {
                Validation.showError(field, 'Equipment name must be at least 3 characters');
                return false;
            }
            if (value.length > 255) {
                Validation.showError(field, 'Equipment name must be less than 255 characters');
                return false;
            }
        }
        
        // Category Name validation
        else if (field.name === 'name' && field.closest && field.closest('#categoryForm')) {
            if (value.length < 2) {
                Validation.showError(field, 'Category name must be at least 2 characters');
                return false;
            }
            if (value.length > 100) {
                Validation.showError(field, 'Category name must be less than 100 characters');
                return false;
            }
        }
        
        // Category selection validation
        else if (field.name === 'category_id') {
            if (!value) {
                Validation.showError(field, 'Please select a category');
                return false;
            }
        }
        
        // Condition validation
        else if (field.name === 'condition') {
            const validConditions = ['New', 'Good', 'Fair', 'Poor'];
            if (!validConditions.includes(value)) {
                Validation.showError(field, 'Please select a valid condition');
                return false;
            }
        }
        
        // Request/Exchange/Repair Reason validation
        else if (['request_reason', 'exchange_reason', 'issue_description', 'return_reason'].includes(field.name)) {
            if (value.length < 5) {
                Validation.showError(field, 'Reason must be at least 10 characters');
                return false;
            }
            if (value.length > 1000) {
                Validation.showError(field, 'Reason must be less than 1000 characters');
                return false;
            }
        }
        
        // Priority validation
        else if (field.name === 'priority') {
            const validPriorities = ['Low', 'Normal', 'High', 'Urgent'];
            if (!validPriorities.includes(value)) {
                Validation.showError(field, 'Please select a valid priority');
                return false;
            }
        }
        
        // Urgency validation
        else if (field.name === 'urgency') {
            const validUrgencies = ['Low', 'Medium', 'High', 'Critical'];
            if (!validUrgencies.includes(value)) {
                Validation.showError(field, 'Please select a valid urgency');
                return false;
            }
        }
        
        // Cost validation
        else if (field.name === 'cost') {
            const cost = parseFloat(value);
            if (isNaN(cost) || cost < 0) {
                Validation.showError(field, 'Please enter a valid cost amount');
                return false;
            }
            if (cost > 999999) {
                Validation.showError(field, 'Cost cannot exceed 999,999');
                return false;
            }
        }
        
        // Date validation
        else if (field.type === 'date') {
            if (field.name === 'purchase_date') {
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const purchaseDate = new Date(value);
                if (purchaseDate > today && purchaseDate.toDateString() !== today.toDateString()) {
                    Validation.showError(field, 'Purchase date cannot be in the future');
                    return false;
                }
            }
            else if (field.name === 'warranty_expiry' || field.name === 'warranty_until') {
                const purchaseDate = field.form ? field.form.querySelector('[name="purchase_date"]') : null;
                if (purchaseDate && purchaseDate.value) {
                    const purchase = new Date(purchaseDate.value);
                    const warranty = new Date(value);
                    if (warranty <= purchase) {
                        Validation.showError(field, 'Warranty expiry must be after purchase date');
                        return false;
                    }
                }
            }
            else if (field.name === 'repair_date') {
                const repairDate = new Date(value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                if (repairDate > today) {
                    Validation.showError(field, 'Repair date cannot be in the future');
                    return false;
                }
            }
        }
        
        Validation.showSuccess(field);
        return true;
    },
    
    showError: function(field, message) {
        if (!field || !field.classList) return;
        field.classList.add('is-invalid');
        field.classList.remove('is-valid');
        
        let feedback = field.nextElementSibling;
        if (!feedback || !feedback.classList || !feedback.classList.contains('invalid-feedback')) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            if (field.parentNode) {
                field.parentNode.insertBefore(feedback, field.nextSibling);
            }
        }
        if (feedback) feedback.textContent = message;
    },
    
    showSuccess: function(field) {
        if (!field || !field.classList) return;
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        
        const feedback = field.nextElementSibling;
        if (feedback && feedback.classList && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = '';
        }
    },
    
    showNeutral: function(field) {
        if (!field || !field.classList) return;
        field.classList.remove('is-invalid');
        field.classList.remove('is-valid');
    },
    
    blockFutureDates: function() {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const maxDate = `${yyyy}-${mm}-${dd}`;
        
        document.querySelectorAll('input[type="date"][name="purchase_date"]').forEach(input => {
            if (input) input.setAttribute('max', maxDate);
        });
        
        document.querySelectorAll('input[type="date"][name="repair_date"]').forEach(input => {
            if (input) input.setAttribute('max', maxDate);
        });
    },
    
    // Convert normal text to JSON format
    convertToJSON: function(field) {
        // CRITICAL FIX: Check if field exists and has classList
        if (!field || !field.value === undefined || !field.classList) {
            return;
        }
        
        let text = field.value.trim();
        
        if (text === '') {
            field.value = '';
            field.classList.remove('is-valid', 'is-invalid');
            return;
        }
        
        // Check if already valid JSON
        try {
            JSON.parse(text);
            field.classList.remove('is-invalid').addClass('is-valid');
            return;
        } catch(e) {
            // Not valid JSON, continue
        }
        
        let jsonObject = {};
        
        if (text.includes(':') || text.includes('=')) {
            const pairs = text.split(/[,\n]/);
            pairs.forEach(pair => {
                let [key, value] = pair.split(/[:=]/);
                if (key && value) {
                    key = key.trim();
                    value = value.trim();
                    jsonObject[key] = value;
                }
            });
        } else {
            jsonObject = { 'specification': text };
        }
        
        if (Object.keys(jsonObject).length > 0) {
            const jsonString = JSON.stringify(jsonObject, null, 2);
            field.value = jsonString;
            field.classList.remove('is-invalid').addClass('is-valid');
        }
    },
    
    // Auto-convert specifications on blur
    initJSONConversion: function() {
        const specFields = document.querySelectorAll('textarea[name="specifications"]');
        if (specFields.length === 0) return;
        
        specFields.forEach(field => {
            if (field) {
                field.addEventListener('blur', function() {
                    if (this && Validation && Validation.convertToJSON) {
                        Validation.convertToJSON(this);
                    }
                });
            }
        });
    },
    
    initFormEnhancements: function() {
        this.blockFutureDates();
        this.initJSONConversion();
    }
};

// Single initialization when page loads
document.addEventListener('DOMContentLoaded', function() {
    Validation.init();
    Validation.initFormEnhancements();
});