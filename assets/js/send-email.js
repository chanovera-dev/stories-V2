const form = document.querySelector('.wpcf7-form');
const formParagraphs = document.querySelectorAll('.wpcf7-form p');
const showFormElement = document.getElementById('show-form');
const response = document.querySelector('.wpcf7-response-output');

function checkClassAndChangeStyles() {
    const titleElement = document.getElementById('title--get-in-touch');
    
    if (form.classList.contains('submitting') || form.classList.contains('resetting')) {
        titleElement.classList.remove('hide');
    } else if (form.classList.contains('sent')) {
        titleElement.classList.add('hide');
        formParagraphs.forEach(p => p.classList.add('hide'));
        showFormElement.classList.add('show');
    }
}

const observerContact = new MutationObserver(mutations => {
    mutations.forEach(mutation => {
        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
            checkClassAndChangeStyles();
        }
    });
});

if (form) {
    observerContact.observe(form, {
        attributes: true
    });
    checkClassAndChangeStyles();
}

function showForm() {
    const titleElement = document.getElementById('title--get-in-touch');
    titleElement.classList.remove('hide');
    showFormElement.classList.remove('show');
    formParagraphs.forEach(p => p.classList.remove('hide'));
    form.classList.remove('sent');
    form.setAttribute('data-status', 'init');
}

if (showFormElement) {
    showFormElement.addEventListener('click', function () {
        if (showFormElement.classList.contains('show')) {
            showForm();
            if (response) {
                response.innerHTML = '';
            }
        }
    });
}