const orderAddresses = document.getElementById('order_addresses');
const sectionCacAddresses = document.getElementById('sectionCacAddresses');

orderAddresses.onclick = function () {
    sectionCacAddresses.classList.remove('visually-hidden');
    sectionCacAddresses.classList.add('animationSlideIn');
};

const cacOrderAddresses = document.querySelector('#order_cacaddresses');
const sectionContact = document.querySelector('#sectionContact');

cacOrderAddresses.onclick = function () {
    sectionContact.classList.remove('visually-hidden');
    sectionContact.classList.add('animationSlideIn');
};

const orderContactPref = document.querySelector('#order_contact_preference');
const sectionAvailab = document.querySelector('#sectionAvailab');

orderContactPref.onclick = function () {
    sectionAvailab.classList.remove('visually-hidden');
    sectionAvailab.classList.add('animationSlideIn');
};

const orderAvailab = document.querySelector('#order_availabilities');
const sectionSubmit = document.querySelector('#sectionSubmit');

orderAvailab.onclick = function () {
    sectionSubmit.classList.remove('visually-hidden');
    sectionSubmit.classList.add('animationSlideIn');
};