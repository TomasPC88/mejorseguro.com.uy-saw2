export let RULES = {
    NOTEMPTY: {
        alias: 'NE',
        message: '%alias% no puede estar vacio',
        regex: /\S/
    },
    INTEGER: {
        alias: 'INT',
        message: '%alias% debe ser un numero',
        regex: /^\d+$/
    },
    NUMERIC: {
        alias: 'NUM',
        message: '%alias% debe ser un valor numerico',
        regex: /^\d+(?:[,\s]\d{3})*(?:\.\d+)?$/
    },
    MIXED: {
        alias: 'MIX',
        message: '%alias% debe ser un valor alfanumerico',
        regex: /^[\w\s-]+$/
    },
    NOSPACE: {
        alias: 'NS',
        message: '%alias% no debe contener espacios',
        regex: /^(?!\s)\S*$/
    },
    NONZERO: {
        alias: 'NZ',
        message: 'Debe seleccionar una opción para %alias%',
        regex: /^((?!0$))/
    },
    TRIM: {
        alias: 'TRIM',
        message: '%alias% sin espacios al inicio o al final',
        regex: /^[^\s].*[^\s]$/
    },
    DATE: {
        alias: 'DATE',
        message: '%alias% debe tener el formato dd-mm-aaaa o dd/mm/aaaa',
        regex: /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/
    },
    URL: {
        alias: 'URL',
        message: '%alias% no es una direccion correcta',
        regex: /^((?:(https?):\/\/)?((?:25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[0-9][0-9]|[0-9])\.(?:(?:25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[0-9][0-9]|[0-9])\.)(?:(?:25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[0-9][0-9]|[0-9])\.)(?:(?:25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[0-9][0-9]|[0-9]))|(?:(?:(?:\w+\.){1,2}[\w]{2,3})))(?::(\d+))?((?:\/[\w]+)*)(?:\/|(\/[\w]+\.[\w]{3,4})|(\?(?:([\w]+=[\w]+)&)*([\w]+=[\w]+))?|\?(?:(wsdl|wadl))))$/
    },
    CELLPHONE: {
        alias: 'CEL',
        message: '%alias% debe ser solo numeros',
        regex: /^(0)?[9]([3-9])(\d{6})$/
    },
    COMPARISION: {
        alias: 'COMP',
        message: 'aaaaaaaaaaaaaaaaaaaaaaaa',
        regex: /^([LV])([<>]=?|==|!=)([^<>=!]+?)$/
    },
    OPTIONAL: {
        alias: 'OPT',
        message: '',
        regex: /\S/
    },
    EMAIL: {
        alias: 'EMAIL',
        message: '%alias% debe ser un email',
        regex: /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i
    },
    CEDULA: {
        alias: 'CI',
        message: '%alias% debe ser solo numeros (7 u 8)',
        regex: /\d{8}/,
        func: 'is_ci',
        func_message: '%alias% no es una cedula correcta'
    }
}

export default class Wo2Validate {

    static validate(value, validations)
    {
        let result = true;

        for(let vali of validations) {

            if(RULES.COMPARISION.regex.test(vali))
            {
                let comparision_result = this.comparision(vali, value);
                if(comparision_result !== true) {
                    result = comparision_result;
                    break;
                }
            } else {
                let rule = Object.keys(RULES).find(key => RULES[key]['alias'] === vali);

                if(!RULES[rule].regex.test(value)) {
                    result = RULES[rule].message;
                    break;
                } else {
                    if( RULES[rule].hasOwnProperty('func') && !this[RULES[rule].func](value) ) {
                        result = RULES[rule].func_message;
                        break;
                    }
                }
            }

        }

        return result;
    }

    static comparision(vali, value)
    {
        let re = RULES.COMPARISION.regex.exec(vali);
        let message = '';
        let number = re[3];
        let result = true;

        switch(re[2]) {
            case '==':
                if(re[1] == 'L'){
                    message = '%alias% debe tener '+number+' caracteres';
                    result = value.length == parseInt(number);
                } else {
                    message = '%alias% debe ser igual a '+number;
                    result = parseInt(value) == parseInt(number);
                }
            break;
            case '>':
                if(re[1] == 'L'){
                    message = '%alias% debe tener mas de '+number+' caracteres';
                    result = value.length > parseInt(number);
                } else {
                    message = '%alias% debe ser mayor a '+number;
                    result = parseInt(value) > parseInt(number);
                }
            break;
            case '<':
                if(re[1] == 'L'){
                    message = '%alias% debe tener menos de '+number+' caracteres';
                    result = value.length < parseInt(number);
                } else {
                    message = '%alias% debe ser menor a '+number;
                    result = parseInt(value) < parseInt(number);
                }
            break;
            case '>=':
                if(re[1] == 'L'){
                    message = '%alias% debe tener '+number+' o mas caracteres';
                    result = value.length >= parseInt(number);
                } else {
                    message = '%alias% debe ser igual o mayor a '+number;
                    result = parseInt(value) >= parseInt(number);
                }
            break;
            case '<=':
                if(re[1] == 'L'){
                    message = '%alias% debe tener '+number+' o menos caracteres';
                    result = value.length <= parseInt(number);
                } else {
                    message = '%alias% debe ser igual o menor a '+number;
                    result = parseInt(value) <= parseInt(number);
                }
            break;
            case '!=':
                if(re[1] == 'L'){
                    message = '%alias% no debe tener '+number+' caracteres';
                    result = value.length != parseInt(number);
                } else {
                    message = '%alias% no debe ser igual a '+number;
                    result = parseInt(value) != parseInt(number);
                }
            break;
        }

        return (!result) ? message : result;
    }

    static is_ci(ci)
    {
        let o, mn, chd, da, ds;

        ci = ci.toString();
        o = ci.lenght < 8 ? '0'+ci : ci;
        mn = [2,9,8,7,6,3,4];
        chd = o.substr(o.length - 1);

        da = Array.from( o.slice(0, -1) ).map( (cid, index) => mn[index] * cid );
        ds = da.reduce( (a, b) => a + b, 0 );

        return ( (10 - (ds % 10) ) % 10 ) === parseInt(chd);
    }
}
