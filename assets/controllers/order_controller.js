// assets/controllers/order_controller.js
import { Controller } from '@hotwired/stimulus';
import Swal from 'sweetalert2';

export default class extends Controller {
    static targets = ['form'];

    submit(event) {
        event.preventDefault();

        const form = this.formTarget;
        const formData = new FormData(form);

        const fullname = formData.get('order[fullname]');
        const email = formData.get('order[email]');
        const phone = formData.get('order[phone]');
        const teaType = formData.get('order[tea_type]');
        const quantity = formData.get('order[quantity]');

        if (!fullname || !teaType || !quantity || (!email && !phone)) {
            Swal.fire('Error', 'Please fill in all required fields. Email or Phone must be provided.', 'error');
            return;
        }

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success', data.message, 'success');
                    form.reset();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error', 'An error occurred. Please try again.', 'error');
            });
    }
}
