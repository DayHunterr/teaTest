import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['btn', 'close', 'list', 'shadow']

    connect() {
        this.btnTarget.addEventListener('click', () => {
            this.listTarget.classList.toggle('menu__list--open');
            this.shadowTarget.classList.toggle('menu--open');
        });

        this.closeTarget.addEventListener('click', () => {
            this.listTarget.classList.remove('menu__list--open');
            this.shadowTarget.classList.remove('menu--open');
        });
    }
}
