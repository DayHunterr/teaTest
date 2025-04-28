import Swal from 'sweetalert2';
import { Translator } from '../translator/Translator';

export class InternalResponse {
    static CODE_SUCCESS = 200;
    static CODE_FAILURE = 400;
    static CODE_VALID_ERR = 405;

    code = null;
    message = null;
    data = [];

    /**
     * @param {Object} response
     * @param {String} response.code
     * @param {String} response.message
     * @param {Array} response.data
     */
    constructor(response) {
        this.code = response.code || InternalResponse.CODE_FAILURE;
        this.message = response.message;
        this.data = response.data || [];

        this.translator = new Translator();
    }

    /**
     * @returns {null|String}
     */
    get code() {
        return this.code;
    }

    /**
     * @returns {null|String}
     */
    get message() {
        return this.message;
    }

    /**
     * @returns {Array}
     */
    get data() {
        return this.data;
    }

    /**
     * @returns {boolean}
     */
    isSuccess() {
        return parseInt(this.code) === InternalResponse.CODE_SUCCESS;
    }

    /**
     * @returns {boolean}
     */
    isValidationErr() {
        return this.code === InternalResponse.CODE_VALID_ERR;
    }

    showErrorDialog() {
        Swal.fire({
            icon: 'error',
            title: this.message !== '' ? this.translator.trans('attention') : this.translator.trans('server_error'),
            html: this.message !== '' ? this.message : this.translator.trans('something_went_wrong'),
        });
    }
}