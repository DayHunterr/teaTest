import translations from '../../../public/translations/translations.json';

export class Translator
{
    constructor() {
        this.translations = translations;
        this.currentLocale = document.documentElement.lang || 'en';
    }

    setLocale(locale) {
        this.currentLocale = locale;
    }

    /**
     * @param key {String}
     * @param args {Object}
     * @param {'messages'|'validators'|String} domain
     *
     * @returns String
     */
    trans(key, args = {}, domain = 'messages') {
        const localeTranslations = this.translations[this.currentLocale];

        if (!localeTranslations) return key;

        const domainTranslations = localeTranslations[domain];
        if (!domainTranslations) return key;

        let translation = domainTranslations[key];
        if (!translation) return key;

        Object.entries(args).forEach(([placeholder, value]) => {
            const regex = new RegExp(placeholder, 'g');
            translation = translation.replace(regex, value);
        });

        return translation;
    }
}