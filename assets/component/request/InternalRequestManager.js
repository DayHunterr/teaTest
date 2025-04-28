import { InternalResponse } from "../response/InternalResponse";
import { Translator } from "../translator/Translator";

export class InternalRequestManager
{
    constructor() {
        this.headers = {};
        this.timeout = 45000;
    }

    /**
     * @param name {String}
     * @param value {String}
     */
    addHeader(name, value)
    {
        this.headers[name] = value;
    }

    /**
     * @param timeout {number}
     */
    setTimeout(timeout)
    {
        this.timeout = timeout;
    }

    /**
     * @param method {String}
     * @param url {String}
     * @param body {Object}
     *
     * @return {Promise<InternalResponse>}
     */
    async request(method, url, body = null)
    {
        return new Promise((resolve) => {
            const xhr = new XMLHttpRequest();
            xhr.open(method, url, true);

            xhr.timeout = this.timeout;

            for (const [key, value] of Object.entries(this.headers)) {
                xhr.setRequestHeader(key, value);
            }

            xhr.ontimeout = function () {
                const translator = new Translator();

                return resolve(new InternalResponse({
                    message: translator.trans('timeout_error_msg'),
                }));
            };

            xhr.onerror = function () {
                const translator = new Translator();

                return resolve(new InternalResponse({
                    message: translator.trans('timeout_error_msg'),
                }));
            };

            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        return resolve(new InternalResponse(JSON.parse(xhr.responseText)));
                    } catch (e) {
                        const translator = new Translator();

                        return resolve(new InternalResponse({
                            message: translator.trans('something_went_wrong'),
                        }));
                    }
                }

                const translator = new Translator();
                return resolve(new InternalResponse({
                    message: translator.trans('something_went_wrong'),
                }));
            };

            xhr.send(body ?? null);
        });
    }

    /**
     * @param url {String}
     * @param params {Object}
     *
     * @return {Promise<InternalResponse>}
     */
    async get(url, params = {})
    {
        if (Object.keys(params).length > 0) {
            url += '?' + new URLSearchParams(params).toString();
        }

        return this.request('GET', url);
    }

    /**
     * @param url {String}
     * @param body {Object}
     *
     * @return {Promise<InternalResponse>}
     */
    async post(url, body = null)
    {
        this.addHeader('Content-Type', 'application/x-www-form-urlencoded');

        return this.request('POST', url, body);
    }

    /**
     * @param url {String}
     * @param body {Object}
     *
     * @return {Promise<InternalResponse>}
     */
    async put(url, body = null)
    {
        return this.request('PUT', url, body);
    }

    /**
     * @param url {String}
     *
     * @return {Promise<InternalResponse>}
     */
    async delete(url)
    {
        return this.request('DELETE', url);
    }
}