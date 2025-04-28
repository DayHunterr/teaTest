import * as Yup from 'yup';

class ValidationHandler {
    constructor(schema) {
        this.schema = schema;
    }

    validate(values) {
        try {
            this.schema.validateSync(values, { abortEarly: false });
            return []; // Если ошибок нет, возвращаем пустой объект
        } catch (error) {
            if (error instanceof Yup.ValidationError) {
                return error.inner.reduce((acc, err) => {
                    acc[err.path] = err.message;
                    return acc;
                }, {});
            }
            throw error;
        }
    }
}

export default ValidationHandler;
