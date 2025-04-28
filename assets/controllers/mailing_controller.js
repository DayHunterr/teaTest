// assets/controllers/mailing_controller.js
import { Controller } from '@hotwired/stimulus'
import { FullScreenLoader } from "../component/loader/FullScreenLoader";
import ValidationHandler from '../validation/ValidationHandler';
import questionSchema from '../validation/schema/questionSchema';

export default class extends Controller {
    static targets = ['form', 'message']

    // submit(event) {
    //     event.preventDefault()
    //
    //     FullScreenLoader.create();
    //
    //     const url = this.formTarget.action
    //     const formData = new FormData(this.formTarget)
    //
    //     fetch(url, {
    //         method: 'POST',
    //         body: formData,
    //     })
    //         .then(async (response) => {
    //             const data = await response.json()
    //
    //             if (response.ok) {
    //                 this.messageTarget.innerHTML = `<div class="success">${data.message}</div>`
    //                 this.formTarget.reset()
    //             } else {
    //                 this.messageTarget.innerHTML = `<div class="error">${data.errors.join('<br>')}</div>`
    //             }
    //         })
    //         .catch((error) => {
    //             this.messageTarget.innerHTML = `<div class="error">Ошибка отправки: ${error}</div>`
    //         })
    //         .finally(() => {
    //             setTimeout(() => {
    //                 FullScreenLoader.destroy();
    //             }, 1500); // 800мс
    //         });
    // }
    // submit(event) {
    //     event.preventDefault();
    //
    //     FullScreenLoader.create(); // Показать лоадер сразу
    //
    //     const url = this.formTarget.action;
    //     const formData = new FormData(this.formTarget);
    //
    //     // Соберем данные в обычный объект
    //     const values = {};
    //     formData.forEach((value, key) => {
    //         values[key] = value;
    //     });
    //
    //     const validator = new ValidationHandler(questionSchema);
    //     const errors = validator.validate(values);
    //
    //     if (Object.keys(errors).length > 0) {
    //         // Если есть ошибки валидации
    //         this.messageTarget.innerHTML = `<div class="error">${Object.values(errors).join('<br>')}</div>`;
    //         FullScreenLoader.destroy(); // Убираем лоадер сразу
    //         return;
    //     }
    //
    //     // Если валидация прошла успешно, отправляем форму
    //     fetch(url, {
    //         method: 'POST',
    //         body: formData,
    //     })
    //         .then(async (response) => {
    //             const data = await response.json();
    //
    //             if (response.ok) {
    //                 this.messageTarget.innerHTML = `<div class="success">${data.message}</div>`;
    //                 this.formTarget.reset();
    //             } else {
    //                 this.messageTarget.innerHTML = `<div class="error">${data.errors.join('<br>')}</div>`;
    //             }
    //         })
    //         .catch((error) => {
    //             this.messageTarget.innerHTML = `<div class="error">Ошибка отправки: ${error}</div>`;
    //         })
    //         .finally(() => {
    //             setTimeout(() => {
    //                 FullScreenLoader.destroy();
    //             }, 1500); // 1.5 секунды
    //         });
    // }

    submit(event) {
        event.preventDefault();

        FullScreenLoader.create(); // Показать лоадер сразу

        const url = this.formTarget.action;
        const formData = new FormData(this.formTarget);

        // Соберем данные в обычный объект
        const values = {};
        formData.forEach((value, key) => {
            values[key] = value;
        });

        const validator = new ValidationHandler(questionSchema);
        const errors = validator.validate(values);

        if (Object.keys(errors).length > 0) {
            // Если есть ошибки валидации, выводим их в message
            const errorMessages = Object.values(errors).join('<br>');
            this.messageTarget.innerHTML = `<div class="error">${errorMessages}</div>`;
            FullScreenLoader.destroy(); // Убираем лоадер сразу
            return;
        }

        // Если валидация прошла успешно, отправляем форму
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
                }, 1500); // 1.5 секунды
            });
    }

}
