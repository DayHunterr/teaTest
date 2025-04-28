// assets/controllers/mailing_controller.js
import { Controller } from '@hotwired/stimulus'
import { FullScreenLoader } from "../component/loader/FullScreenLoader";

export default class extends Controller {
    static targets = ['form', 'message']

    submit(event) {
        event.preventDefault()

        const url = this.formTarget.action
        const formData = new FormData(this.formTarget)

        fetch(url, {
            method: 'POST',
            body: formData,
        })
            .then(async (response) => {
                const data = await response.json()

                if (response.ok) {
                    this.messageTarget.innerHTML = `<div class="success">${data.message}</div>`
                    this.formTarget.reset()

                    FullScreenLoader.create();
                    const client = new InternalRequestManager();
                    const response = await client.post(this.askQuestionUrlValue, $(e.target).serialize());
                    FullScreenLoader.destroy();

                } else {
                    this.messageTarget.innerHTML = `<div class="error">${data.errors.join('<br>')}</div>`
                }


            })
            .catch((error) => {
                this.messageTarget.innerHTML = `<div class="error">Ошибка отправки: ${error}</div>`
            })


    }


}
