// assets/controllers/mailing_controller.js
import { Controller } from '@hotwired/stimulus'
import { FullScreenLoader } from "../component/loader/FullScreenLoader";
import ValidationHandler from '../validation/ValidationHandler';
import questionSchema from '../validation/schema/questionSchema';
import Swal from "sweetalert2";

export default class extends Controller {
    static targets = ['form', 'message']

    submit(event) {
        event.preventDefault();

        FullScreenLoader.create();

        const url = this.formTarget.action;
        const formData = new FormData(this.formTarget);
        const validationData = Object.fromEntries(formData.entries());

        const validator = new ValidationHandler(questionSchema);
        const errors = validator.validate(validationData);

        if (Object.keys(errors).length > 0) {
            // Если есть ошибки валидации, выводим их в message
            // const errorMessages = Object.values(errors).join('<br>');
            const errorMessages = Object.values(errors)[0];
            this.messageTarget.innerHTML = `<div class="error">${errorMessages}</div>`;
            FullScreenLoader.destroy();
            return;
        }

        fetch(url, {
            method: 'POST',
            body: formData,
        })
            .then(async (response) => {
                const data = await response.json();

                if (response.ok) {
                    this.messageTarget.innerHTML = `<div class="success">${data.message}</div>`;
                    this.formTarget.reset();
                } else {
                    this.messageTarget.innerHTML = `<div class="error">${data.errors.join('<br>')}</div>`;
                }
            })
            .catch((error) => {
                this.messageTarget.innerHTML = `<div class="error">Ошибка отправки: ${error}</div>`;
            })
            .finally(() => {
                setTimeout(() => {
                    FullScreenLoader.destroy();

                    Swal.fire({
                        title: "Success!",
                        icon: "success",
                        draggable: true
                    });
                }, 1000);
            });
    }


}
