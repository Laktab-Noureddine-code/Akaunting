export default class Errors {
    constructor() {
        this.errors = {};
    }
    has(field) {
        return this.errors.hasOwnProperty(field);
    }
    any() {
        return Object.keys(this.errors).length > 0;
    }
    set(key, field) {
        return this.errors[key] = field;
    }
    get(field) {
        if (this.has(field)) {
            return this.errors[field][0];
        }
    }
    record(errors) {
        if (errors instanceof Object) {
            this.errors = errors;
        }
    }
    clear(field) {
        if (field) {
            return delete this.errors[field];
        }
        this.errors = {};
    }
}
