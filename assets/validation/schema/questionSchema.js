import * as Yup from 'yup';
import { Translator } from '../../component/translator/Translator';

const translator = new Translator();

const questionSchema = Yup.object().shape({
    'company[email]': Yup.string()
        .required(translator.trans('not_blank', {}, 'validators'))
        .trim(translator.trans('not_blank', {}, 'validators'))
        .email(translator.trans('invalid_email', {}, 'validators'))
        .max(255, translator.trans('max_length', {}, 'validators')),

    'company[agree]': Yup.boolean()
        .required(translator.trans('must_be_accepted', {}, 'validators'))
});

export default questionSchema;
