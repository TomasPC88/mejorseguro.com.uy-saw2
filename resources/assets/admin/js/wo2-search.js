import Wo2Utils from './wo2-utils.js';
import * as Ps from 'perfect-scrollbar';

export default class Wo2Search {
    constructor(search_input, search_list_element) {
        this.list_element = search_list_element;
        this.input = search_input;
        this.globalTimeout = null;

        this.list_element_template = `<article class="media">
            <div class="media-content">
                <div class="content">
                    <a href="#">
                        <h1></h1>
                    </a>
                </div>
            </div>
        </article>`;

        this.results_list = `<a href="#">
            <p>
                <small></small> <strong></strong> <small></small>
                <br/>
                <span></span> <span></span>
            </p>
        </a>`;

        this.not_results = `<h1>No se han encontrado resultados...</h1>`;

        this.init()
    }

    init()
    {
        this.input.addEventListener('keyup', this.change.bind(this));
        Ps.initialize(this.list_element, {
            wheelSpeed: 1,
            minScrollbarLength: 20,
            theme: 'wo2'
        });
    }

    change(ev)
    {
        ev.preventDefault();
        if(ev.keyCode === 27) return;

        let _self = this;
        let textSearch = _self.input.value.trim();
        if ( textSearch ) {
            if (_self.globalTimeout != null) {
                clearTimeout(_self.globalTimeout);
            }
            let promises = [];
            _self.globalTimeout = setTimeout( () => {

                _self.input.parentNode.classList.add('is-loading');

                let searchResults = [];
                _self.globalTimeout = null
                let controllersArray = wo2_settings.search_list
                controllersArray.forEach( (key, value) => {
                    let controller = key;
                    let url = wo2_base+controller+'/buscar/'+textSearch;
                    promises.push(axios.get(url))
                });
                axios.all(promises)
                .then( (results) => {
                    results.forEach( (response) => {
                        if(response.status === 200){
                            searchResults.push(response.data);
                        }
                    });
                    _self.showSearch(searchResults, textSearch);
                })
                .catch( (error) => {
                    console.log(error);
                });
            }, 500);
        }else{
            this.list_element.classList.add('is-hidden');
        }
    }

    showSearch(search, textSearch){
        // console.log(search);
        this.input.parentNode.removeClass('is-loading');
        this.list_element.innerHTML = '';
        let empty = true;
        search.forEach( (element) => {
            this.list_element.removeClass('is-hidden');
            document.addEventListener('keyup',this.closeSearch.bind(this));

            if ( element.info[0] ) {
                empty = false;
                let ele = document.querySelector(this.list_element_template);
                let category = this.capitalize(element.from);
                ele.querySelector('.media-content > .content > a').attr("href", wo2_base+element.from);
                ele.querySelector('.media-content > .content > a > h1').innerText = category;
                this.list_element.appendChild(ele);
                element.info[0].forEach( (list) => {
                    if ( Object.keys(list).includes("currency") ) {
                        switch (list.currency) {
                            case 840:
                                list.currency = "USD";
                                break;
                            default:
                                list.currency = "$U";
                        }
                    }
                    let resultList = Object.values(list);
                    console.log(resultList);
                    let ele_list = document.querySelector(this.results_list);
                    if ( Object.keys(list).includes("friendly_url") ) {
                        ele_list.href = wo2_base+element.from+'/buscar/'+resultList[0]+'/'+textSearch;
                        ele_list.querySelector('p > strong').innerText = resultList[2];
                    }else {
                        ele_list.href = wo2_base+element.from+'/buscar/'+resultList[0]+'/'+textSearch;
                        ele_list.querySelector('p > strong').innerText = resultList[1];
                    }
                    ele_list.querySelector('p > small').eq(0).innerText = resultList[0];
                    ele_list.querySelector('p > small').eq(1).innerText = Wo2Utils.wo2_ate( resultList[resultList.length - 2]);
                    if ( !Wo2Utils.wo2_check_date(resultList[2]) ) {
                        ele_list.querySelector('p > span').eq(0).innerText = resultList[2];
                    }
                    if ( !Wo2Utils.wo2_check_date(resultList[3]) ) {
                        ele_list.querySelector('p > span').eq(1).innerText = resultList[3];
                    }
                    let view_list = ele.querySelector('.media-content > .content');
                    view_list.appendChild(ele_list);
                });
            }
        });
        if (empty) {
            this.list_element.appendChild(document.querySelector(this.not_results));
        }
    }

    closeSearch(e)
    {
        if(e.keyCode === 27) {
            document.removeEventListener('keyup');
            this.input.value = '';
            this.input.blur();
            this.list_element.classList.add('is-hidden');
            e.preventDefault();
            e.stopPropagation();
        }
    }

    capitalize(string) {
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }


}
