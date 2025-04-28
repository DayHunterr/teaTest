import * as Yup from 'yup';
import { Translator } from '../../component/translator/Translator';

const translator = new Translator();

const questionSchema = Yup.object().shape({
    'mailing[email]': Yup.string()
        .email(translator.trans('invalid_email', {}, 'validators'))
        .required(translator.trans('not_blank', {}, 'validators'))
        .max(255, translator.trans('max_length', {}, 'validators')),

    'mailing[agree]': Yup.boolean()
        .oneOf([true], translator.trans('must_be_accepted', {}, 'validators'))
});

export default questionSchema;
