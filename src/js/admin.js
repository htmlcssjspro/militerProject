'use strict';

import {newFetch, fetchForm}
    from 'D:/WebDevelopment/Projects/LIBS/js/modules/Fetch';
import {clickHandler, popupHandler, dropdownHandler}
    from 'D:/WebDevelopment/Projects/LIBS/js/modules/Handler';


window.addEventListener('popstate', () => newFetch(history.state));

clickHandler({
    del(t) {
        const $label = t.closest('label');
        const $input = $label.querySelector('input[name="newname[]"]');
        $input.value = 'Удалить';
        t.textContent = 'Отменить';
        t.dataset.cb = 'cancel';
    },
    cancel(t) {
        const $label = t.closest('label');
        const $input = $label.querySelector('input[name="newname[]"]');
        $input.value = $input.defaultValue;
        t.textContent = 'Удалить';
        t.dataset.cb = 'del';
    },
    delNew(t) {
        const $label = t.closest('label');
        const $next = $label.nextElementSibling;
        $label.remove();
        $next && $next.classList.contains('error_input') && $next.remove();
    },
    addNewCountry(t) {
        const label = `
        <span>Новая страна</span>
        <span><input type="text" name="newcountry[]" data-required="required"></span>
        <button class="delnew" type="button"  data-cb="delNew">Удалить</button>
    `;
        this.addNew(t, label);
    },
    addNewTown(t) {
        const label = `
        <span>Новый город</span>
        <span><input type="text" name="newtown[]" data-required="required"></span>
        <button class="delnew" type="button" data-cb="delNew">Удалить</button>
    `;
        this.addNew(t, label);
    },
    addNew(t, label) {
        const $label = document.createElement('label');
        $label.innerHTML = label;
        t.before($label);
        $label.focus();
    }
});
