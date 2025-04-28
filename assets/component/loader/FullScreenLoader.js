export class FullScreenLoader
{
    static create() {
        const body = document.querySelector('body');
        const loader = document.createElement('div');

        loader.className = 'full-screen-loader';
        body.append(loader);
    }

    static destroy() {
        const loader = document.querySelector('.full-screen-loader');

        if (loader) {
            loader.remove();
        }
    }
}

export default new FullScreenLoader();