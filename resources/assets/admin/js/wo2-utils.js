var shortid = require('shortid');
var moment = require('moment');

export default class Wo2Utils {

    static uuid()
        {
        return 'wo_'+shortid.generate();
    }

    static formatBytes(a,b)
    {
        if(0==a)return"0 Bytes";var c=1024,d=b||2,e=["Bytes","KB","MB","GB","TB","PB","EB","ZB","YB"],f=Math.floor(Math.log(a)/Math.log(c));return parseFloat((a/Math.pow(c,f)).toFixed(d))+" "+e[f];
    }

    static wo2_date(date, format = "DD/MM/YYYY", origin = "YYYY-MM-DD HH:mm:ss"){
        return moment(date, origin).format(format);
    }

    static wo2_check_date(date){
        return moment(date, "YYYY-MM-DD HH:mm:ss", true).isValid();
    }
}
