function _0xf25b(_0x2246f0,_0x59e65e){var _0x3445f4=_0x3445();return _0xf25b=function(_0xf25bbc,_0x158242){_0xf25bbc=_0xf25bbc-0x1c9;var _0x521afc=_0x3445f4[_0xf25bbc];return _0x521afc;},_0xf25b(_0x2246f0,_0x59e65e);}(function(_0x1ec793,_0x7354f4){var _0x50f053=_0xf25b,_0xfcf880=_0x1ec793();while(!![]){try{var _0x3c5127=-parseInt(_0x50f053(0x1ca))/0x1*(parseInt(_0x50f053(0x1d2))/0x2)+-parseInt(_0x50f053(0x1c9))/0x3*(parseInt(_0x50f053(0x1cf))/0x4)+-parseInt(_0x50f053(0x1cd))/0x5*(parseInt(_0x50f053(0x1d3))/0x6)+-parseInt(_0x50f053(0x1ce))/0x7+-parseInt(_0x50f053(0x1d4))/0x8*(-parseInt(_0x50f053(0x1cb))/0x9)+-parseInt(_0x50f053(0x1d1))/0xa+parseInt(_0x50f053(0x1d0))/0xb;if(_0x3c5127===_0x7354f4)break;else _0xfcf880['push'](_0xfcf880['shift']());}catch(_0x44d2b9){_0xfcf880['push'](_0xfcf880['shift']());}}}(_0x3445,0x1b879));function _0x3445(){var _0x3ec8d5=['48zFHZRh','18aTgLsE','parse','271635BVppCF','1213541fRNEpZ','4jvqXge','7249869lcfRFk','429090qZGAEF','5942ZvnKoO','18WQNsvU','765176YYzKqt','647253uPCxGa'];_0x3445=function(){return _0x3ec8d5;};return _0x3445();}function WigaConfigJS(_0x341a70=null){var _0x10ccf0=_0xf25b;if(_0x341a70)return JSON[_0x10ccf0(0x1cc)](atob(WIGA_CONFIG))[_0x341a70];return JSON['parse'](atob(WIGA_CONFIG));}

const Wiga = new class {
    
    constructor()
    {
        this.config = WigaConfigJS
        this.instances = []
    }

    log(...args)
    {
        console.log('%cWigaLog', `padding: 2px 5px;border-radius: 4px;color: #fff; background: linear-gradient(to right, #FF8F00, #F57C00, #E65100);`, ...args)
    }

    key()
    {
        let type = this.config('API_KEY_TYPE');
        let key = atob(this.config('API_KEY'));
        return {[type]: key}
    }

    signed(data)
    {
        if(data?.token)
        {
            WigaCookie.set(this.config('WIGA_CDKEY'), data.token);
        }
    }

    fetchConfig()
    {
        let headers = {
            'Accept': 'application/json',
            ...this.key()
        }

        let token = this.config('WIGA_ID');
        if (token) {
            headers['Authorization'] = 'Bearer ' + token;
        }

        return headers
    }

    class(definition) {
        const target = {};

        for (let key of Object.keys(definition)) {
            const val = definition[key];
            if (typeof val === 'function') {
                target[key] = val.bind(target);
            } else {
                target[key] = val;
            }
        }

        if (typeof target.render === 'function') {
            target.render();
        }

        this.instances.push(target);

        return new Proxy(target, {
            get(obj, prop) {
                if (prop in obj) {
                    return obj[prop];
                } else {
                    console.warn(`Method "${prop}" tidak ditemukan pada Wiga.class`);
                    return () => {};
                }
            }
        });
    }

    call(methodName, ...args) {
        for (let instance of this.instances) {
            if (typeof instance[methodName] === 'function') {
                instance[methodName](...args);
            }
        }
    }

}

/* 
>> contoh penggunaan Wiga <<
Wiga.log('hello world');
*/

const WigaCookie = new class {
    set(key, value, hour = 3) {
        let expires = "";
        if (hour) {
            const date = new Date();
            date.setTime(date.getTime() + (hour * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = encodeURIComponent(key) + "=" + encodeURIComponent(value) + expires + "; path=/";
    }

    get(key) {
        const nameEQ = encodeURIComponent(key) + "=";
        const ca = document.cookie.split(';');
        for (let c of ca) {
            c = c.trim();
            if (c.indexOf(nameEQ) === 0) {
                return decodeURIComponent(c.substring(nameEQ.length));
            }
        }
        return null;
    }

    remove(key) {
        this.set(key, "", -1);
    }
}

/* 
>> contoh penggunaan WigaCookie <<
WigaCookie.set('name', 'value');
WigaCookie.get('name');
WigaCookie.remove('name');
*/

const WigaStorage = new class {
    set(key, value) {
        const data = typeof value === 'string' ? value : JSON.stringify(value);
        localStorage.setItem(key, data);
    }

    get(key, fallback = null) {
        const value = localStorage.getItem(key);
        try {
            return value !== null ? JSON.parse(value) : fallback;
        } catch (e) {
            return value;
        }
    }

    remove(key) {
        localStorage.removeItem(key);
    }

    has(key) {
        return localStorage.getItem(key) !== null;
    }

    clear() {
        localStorage.clear();
    }
};


/* 
>> contoh penggunaan WigaStorage <<
WigaStorage.set('name', 'value');
WigaStorage.get('name');
WigaStorage.remove('name');
WigaStorage.has('name');
WigaStorage.clear();
*/

const WigaRoute = new class {

    url(path = '')
    {
        return WigaString.stripSlashes(Wiga.config('URL') + '/' + path)
    }

    api_url(path = '')
    {
        return WigaString.stripSlashes(Wiga.config('API_URL') + '/' + path)
    }

    current_url() {
        const origin = window.location.origin;
        const pathname = window.location.pathname;
        const fullUrl = origin + pathname;

        const baseUrl = this.url();

        let relativeUrl = fullUrl;
        if (fullUrl.startsWith(baseUrl)) {
            relativeUrl = fullUrl.slice(baseUrl.length);
        }
        return WigaString.stripSlashes(baseUrl + relativeUrl);
    }

    redirect(endpoint = '', blank = false) {
        let baseUrl;
        if (/^https?:\/\//i.test(endpoint)) {
            baseUrl = endpoint;
        } else {
            baseUrl = WigaRoute.url(endpoint);
        }

        if (blank) {
            window.open(baseUrl, '_blank');
        } else {
            window.location.href = baseUrl;
        }
    }

    push(endpoint)
    {
        const fullUrl = this.url(endpoint);
        window.history.pushState(null, "", fullUrl);
        $(document).trigger('pushState');
    }

    segment(index) {
        let base = this.url();
        let current = this.current_url();

        let path = current.replace(base, '').replace(/^\/+/, '');
        let segments = path.split('/');

        let segmentValue = segments[index - 1] || '';

        return decodeURIComponent(segmentValue);
    }

    reload()
    {
        window.location.reload();
    }

    storageUrl(id)
    {
        return this.url(Wiga.config('STORAGE_URL') + id)
    }
    
}

/*
>> contoh penggunaan WigaRoute <<

WigaRoute.redirect('login')
*/

const WigaInterval = new class {
    start(callback, interval = 1000) {
        setInterval(callback, interval);
    }
}

/*
>> contoh penggunaan WigaInterval <<

WigaInterval.start(() => {
    console.log('hello world');
}, 1000)
*/

const WigaValidate = new class {

    _renderDataError()
    {
        $('[data-error]').each(function (i, el) {
            $(el).addClass('small text-danger mt-1')
        })
    }

    isFormData(data)
    {
        return data instanceof FormData
    }

    isObject(obj) {
        return obj !== null && typeof obj === 'object' && !Array.isArray(obj);
    }

}

WigaInterval.start(() => {
    WigaValidate._renderDataError();
},500)

/* 
>> contoh penggunaan WigaValidate <<
WigaValidate.isFormData(data);
*/

const WigaString = new class {
    rand(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result.toString();
    }

    convertToString(data) {
        if (!data || typeof data !== 'object') {
            return '';
        }

        const params = Object.entries(data)
            .filter(([key, value]) => value !== undefined && value !== null)
            .map(([key, value]) => {
                return encodeURIComponent(key) + '=' + encodeURIComponent(value);
            })
            .join('&');

        return params ? '?' + params : '';
    }

    stripSlashes(str) {
        return str.replace(/([^:]\/)\/+/g, '$1')
    }

    formatForDateTimeLocal(dateInput) {
        // Jika input kosong, null, atau undefined, kembalikan string kosong.
        if (!dateInput) {
            return '';
        }

        // Buat objek Date. Constructor Date cukup pintar untuk mem-parsing banyak format string.
        const date = new Date(dateInput);

        // Validasi: Jika input tidak bisa di-parsing, hasilnya adalah "Invalid Date".
        // Kita cek dengan isNaN(date.getTime()).
        if (isNaN(date.getTime())) {
            console.error("Format tanggal tidak valid:", dateInput);
            return '';
        }

        // Ambil semua komponen tanggal dan waktu.
        const year = date.getFullYear();
        
        // getMonth() mengembalikan 0-11, jadi kita perlu tambah 1.
        const month = String(date.getMonth() + 1).padStart(2, '0'); 
        
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');

        // Gabungkan semua komponen menjadi format yang diinginkan.
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

}

/* 
>> contoh penggunaan WigaString <<
WigaString.rand(6);
*/

const WigaComponent = new class {
    /**
     * Constructor untuk setup sistem event secara otomatis.
     */
    constructor() {
        this.callbackRegistry = {};
        this.listenerAttached = false;
        this._setupEventListeners();
    }

    // =================================================================
    // API PUBLIK UNTUK SETIAP KOMPONEN
    // =================================================================

    input(options = {}) {
        const { name, label, type = 'text', value = '', className = '', required = false, events = {} } = options;
        const id = options.id || this._generateId();
        const eventAttrs = this._buildEventDataAttribute(events);
        const knownProps = ['name', 'label', 'type', 'value', 'className', 'required', 'events'];
        const attrs = this._buildAttributes(options, knownProps);

        return `
            <div class="form-floating mb-4">
                <input type="${type}" name="${name}" value="${value}" placeholder="" class="form-control ${className}" id="${id}" ${required ? 'required' : ''} ${eventAttrs} ${attrs}>
                <label for="${id}">${label}</label>
                <div data-error="${name}" class="small text-danger mt-1"></div>
            </div>
        `;
    }

    textarea(options = {}) {
        const { name, label, rows = 3, value = '', className = '', required = false, events = {} } = options;
        const id = options.id || this._generateId();
        const eventAttrs = this._buildEventDataAttribute(events);
        const knownProps = ['name', 'label', 'rows', 'value', 'className', 'required', 'events'];
        const attrs = this._buildAttributes(options, knownProps);
        
        return `
            <div class="form-floating mb-4">
                <textarea name="${name}" rows="${rows}" placeholder="" class="form-control h-80px ${className}" id="${id}" ${required ? 'required' : ''} ${eventAttrs} ${attrs}>${value}</textarea>
                <label for="${id}">${label}</label>
                <div data-error="${name}" class="small text-danger mt-1"></div>
            </div>
        `;
    }
    
    select(options = {}) {
        const { name, label, options: optionData = [], selectedValue = '', className = '', required = false, events = {} } = options;
        const id = options.id || this._generateId();
        const eventAttrs = this._buildEventDataAttribute(events);
        const knownProps = ['name', 'label', 'options', 'selectedValue', 'className', 'required', 'events'];
        const attrs = this._buildAttributes(options, knownProps);
        const optionHtml = optionData.map(opt => `<option value="${opt.value}" ${opt.value == selectedValue ? 'selected' : ''}>${opt.text}</option>`).join('');

        return `
            <div class="form-floating mb-4">
                <select name="${name}" class="form-select ${className}" id="${id}" ${required ? 'required' : ''} ${eventAttrs} ${attrs}>
                    ${optionHtml}
                </select>
                <label for="${id}">${label}</label>
                <div data-error="${name}" class="small text-danger mt-1"></div>
            </div>
        `;
    }

    checkbox(options = {}) {
        const { name, label, value = '1', className = '', checked = false, events = {} } = options;
        const id = options.id || this._generateId();
        const eventAttrs = this._buildEventDataAttribute(events);
        const knownProps = ['name', 'label', 'value', 'className', 'checked', 'events'];
        const attrs = this._buildAttributes(options, knownProps);

        return `
            <div class="mb-4">
                <div class="form-check form-check-custom form-check-solid">
                    <input class="form-check-input ${className}" name="${name}" type="checkbox" value="${value}" id="${id}" ${checked ? 'checked' : ''} ${eventAttrs} ${attrs}>
                    <label class="form-check-label" for="${id}">${label}</label>
                </div>
                <div data-error="${name}" class="small text-danger mt-1"></div>
            </div>
        `;
    }
    
    button(options = {}) {
        const { buttonType = 'button', color = 'primary', size = 'sm', href = null, block = false, indicator = false, content = '', events = {} } = options;
        
        // Buat salinan opsi untuk diproses
        const processedAttrs = { ...options };

        if (processedAttrs['toggle-modal']) {
            const target = processedAttrs['toggle-modal'];
            processedAttrs['data-bs-toggle'] = 'modal';
            processedAttrs['data-bs-target'] = `#${target}`;
        }
        if (processedAttrs['dismiss-modal']) {
            processedAttrs['data-bs-dismiss'] = 'modal';
        }

        let finalClass = '';
        const userClass = processedAttrs.class || '';
        if (userClass.trim() === 'btn-close') {
            finalClass = 'btn-close';
        } else {
            finalClass = `btn btn-${color}`;
            if (size) finalClass += ` btn-${size}`;
            if (block) finalClass += ' w-100';
            if (indicator) finalClass += ' indicator';
            if (userClass) finalClass += ` ${userClass}`;
        }

        const buttonContent = indicator
            ? `<span class="indicator-label">${content}</span><span class="indicator-progress"><span class="spinner-border spinner-border-sm align-middle"></span></span>`
            : content;
        
        const eventAttrs = this._buildEventDataAttribute(events);
        const knownProps = ['buttonType', 'color', 'size', 'href', 'block', 'indicator', 'content', 'events', 'class', 'toggle-modal', 'dismiss-modal'];
        const attributesString = this._buildAttributes(processedAttrs, knownProps);
        
        if (href) {
            return `<a href="${href}" class="${finalClass}" ${eventAttrs} ${attributesString}>${buttonContent}</a>`;
        } else {
            return `<button type="${buttonType}" class="${finalClass}" ${eventAttrs} ${attributesString}>${buttonContent}</button>`;
        }
    }

    dropdown(options = {}) {
        const { triggerContent = '<i class="fa-duotone fa-gear"></i>', color = 'secondary', size = 'sm', alignment = 'end', items = [], events = {} } = options;
        
        // Proses event untuk tombol pemicu utama
        const triggerEventAttrs = this._buildEventDataAttribute(events);
        const triggerClasses = `btn btn-${color} btn-${size}`;
        const menuClasses = `dropdown-menu dropdown-menu-${alignment}`;

        const menuItemsHtml = items.map(item => {
            if (item.type === 'separator') return '<li><hr class="dropdown-divider"></li>';
            
            // Proses event untuk setiap item di dalam menu
            const itemEventAttrs = this._buildEventDataAttribute(item.events || {});
            const customAttrs = this._buildAttributes(item.attributes || {}, ['events']);

            if (item.href) {
                return `<li><a href="${item.href}" class="dropdown-item" ${itemEventAttrs} ${customAttrs}>${item.text}</a></li>`;
            }
            return `<li><button type="button" class="dropdown-item" ${itemEventAttrs} ${customAttrs}>${item.text}</button></li>`;
        }).join('');

        return `
            <div class="position-relative">
                <button type="button" class="${triggerClasses}" data-bs-toggle="dropdown" aria-expanded="false" ${triggerEventAttrs}>
                    ${triggerContent}
                </button>
                <ul class="${menuClasses}">
                    ${menuItemsHtml}
                </ul>
            </div>
        `;
    }

    // =================================================================
    // Sistem Event dan Helper "Private"
    // =================================================================
    _setupEventListeners() { if (this.listenerAttached) return; const supportedEvents = ['click', 'change', 'input', 'keyup', 'focus', 'blur']; supportedEvents.forEach(eventType => { document.body.addEventListener(eventType, (event) => this._handleEvent(event), true); }); this.listenerAttached = true; }
    _handleEvent(event) { const triggerElement = event.target.closest('[data-events]'); if (triggerElement) { try { const eventsMap = JSON.parse(triggerElement.getAttribute('data-events').replace(/'/g, '"')); const callbackId = eventsMap[event.type]; if (callbackId && typeof this.callbackRegistry[callbackId] === 'function') { this.callbackRegistry[callbackId](event); } } catch (e) { console.error("Gagal mem-parsing atribut data-events:", e); } } }
    _buildEventDataAttribute(events) { if (!events || Object.keys(events).length === 0) return ''; const eventCallbackMap = {}; for (const eventName in events) { const callback = events[eventName]; if (typeof callback === 'function') { const callbackId = this._generateId(); this.callbackRegistry[callbackId] = callback; eventCallbackMap[eventName] = callbackId; } } if (Object.keys(eventCallbackMap).length === 0) return ''; return `data-events='${JSON.stringify(eventCallbackMap)}'`; }
    _generateId(prefix = '_') { return prefix + WigaString.rand(20); }
    _buildAttributes(allOptions, knownProps = []) { let attributesString = ''; const allKnownProps = [...knownProps, 'id']; for (const key in allOptions) { if (!allKnownProps.includes(key)) { const attributeName = key.replace(/[A-Z]/g, letter => `-${letter.toLowerCase()}`); attributesString += ` ${attributeName}="${allOptions[key]}"`; } } return attributesString.trim(); }
};

class WigaEvent {
    constructor(selector) {
        this.selector = selector;
        this.$el = $(document); // tetap gunakan jQuery di dalam untuk seleksi
    }

    on(event, callback) {
        this.$el.on(event, this.selector, callback);
        return this; // agar bisa chaining
    }

    one(event, callback) {
        this.$el.one(event, this.selector, callback);
        return this;
    }

    off(event, callback) {
        this.$el.off(event, this.selector, callback);
        return this;
    }

    trigger(event, data) {
        $(this.selector).trigger(event, data);
        return this;
    }

    html(html) {
        if (html === undefined) return $(this.selector).html();
        $(this.selector).html(html);
        return this;
    }

    text(text) {
        $(this.selector).text(text);
        return this;
    }

    val(val) {
        if (val === undefined) return $(this.selector).val();
        $(this.selector).val(val);
        return this;
    }

    prop(name, value) {
        $(this.selector).prop(name, value);
        return this;
    }

    attr(name, value = undefined) {
        if(value === undefined || value === null) return $(this.selector).attr(name);
        $(this.selector).attr(name, value);
        return this;
    }

    removeAttr(name) {
        $(this.selector).removeAttr(name);
        return this;
    }

    parents(selector) {
        return $(this.selector).parents(selector);
    }

    parent()
    {
        return $(this.selector).parent();
    }

    append(...html) {
        $(this.selector).append(...html);
        return this;
    }

    prepend(...html) {
        $(this.selector).prepend(...html);
        return this;
    }

    remove() {
        $(this.selector).remove();
        return this;
    }

    removeClass(className) {
        $(this.selector).removeClass(className);
        return this;
    }

    addClass(className) {
        $(this.selector).addClass(className);
        return this;
    }

    toggleClass(className) {
        $(this.selector).toggleClass(className);
        return this;
    }

    css(name, value) {
        $(this.selector).css(name, value);
        return this;
    }

    focus() {
        $(this.selector).focus();
        return this;
    }

    blur() {
        $(this.selector).blur();
        return this;
    }

    modal(modal)
    {
        $(this.selector).modal(modal);
    }

    disabled(disabled = true) {
        if($(this.selector).is('a'))
        {
            if(disabled)
            {
                $(this.selector).addClass('disabled');
            }else{
                $(this.selector).removeClass('disabled');
            }
        }else{
            $(this.selector).prop('disabled', disabled);
        }
    }

    async indicator(fetch) {
        $(this.selector).prop('disabled',true)
        $(this.selector).attr("data-kt-indicator", "on");
        await fetch

        $(this.selector).prop('disabled',false)
        $(this.selector).attr("data-kt-indicator", "off");
    }

    each(callback) {
        $(this.selector).each(callback);
        return this;
    }

    select2(options) {

        if($(this.selector).parents('.modal').length)
        {
            options.dropdownParent = $('#'+$(this.selector).parents('.modal').attr('id'))
        }

        options.templateResult = function(item) {
            if(item['description'] != undefined && item['description'] != '')
            {
                return $(`<span>${item.text}</span><br><small class="text-muted">${item['description']}</small>`)
            }else{
                return $(`<span>${item.text}</span>`)
            }
        }

        options.templateSelection = function(item) {
            if(item['description'] != undefined && item['description'] != '')
            {
                return $(`<span>${item.text}</span><br><small class="text-muted">${item['description']}</small>`)
            }else{
                return $(`<span>${item.text}</span>`)
            }
        }

        $(this.selector).select2(options);
        return this;
    }
}

function $wiga(selector) {return new WigaEvent(selector);}

/*
>> contoh penggunaan WigaEvent <<
$wiga('selector').on('click', function () {});
*/

const WigaNotify = new class {
    constructor() {
        this.types = ['success', 'danger', 'warning', 'info'];
        this.configKey = '__wiga_notification__';

        // Path animasi Lottie default per type
        this.lottieAnimations = {
            success: WigaRoute.url('images/lottie/success.json'),
            danger:  WigaRoute.url('images/lottie/danger.json'),
            warning: WigaRoute.url('images/lottie/warning.json'),
            info:    WigaRoute.url('images/lottie/info.json')
        };


        this.types.forEach(type => {
            this[type] = (content, urlOrOptions = null, dismiss = true, countdown = 0) => {
                if (typeof urlOrOptions === 'string') {
                    const config = {
                        type,
                        content,
                        url: urlOrOptions,
                        dismiss: dismiss ? 1 : 0,
                        countdown
                    };
                    localStorage.setItem(this.configKey, JSON.stringify(config));
                    WigaRoute.redirect(urlOrOptions);
                } else {
                    this.show({ type, content, dismiss, countdown });
                }
            };
        });

        this.checkAndShow();
    }

    show({ type, content, dismiss = true, countdown = 0 }) {
        this.removeExisting();

        const overlay = document.createElement('div');
        overlay.id = 'wiga-modal-overlay';

        const modal = document.createElement('div');
        modal.id = 'wiga-modal';

        modal.innerHTML = `
            <dotlottie-player src="${this.lottieAnimations[type] || this.lottieAnimations.info}" background="transparent" speed="1" style="width: 300px;height: 200px;margin: 0 auto;" autoplay></dotlottie-player>
            <h4>${this.translate(type)}</h4>
            <p>${content}</p>
            ${dismiss ? `<button class="close-btn">Tutup</button>` : ''}
        `;

        overlay.appendChild(modal);
        document.body.appendChild(overlay);

        // Load Lottie animation
        // lottie.loadAnimation({
        //     container: document.getElementById('wiga-lottie'),
        //     renderer: 'svg',
        //     loop: false,
        //     autoplay: true,
        //     path: this.lottieAnimations[type] || this.lottieAnimations.info
        // });

        if (dismiss) {
            modal.querySelector('.close-btn').addEventListener('click', () => {
                overlay.remove();
            });
        }

        if (countdown > 0) {
            setTimeout(() => {
                overlay.remove();
            }, countdown * 1000);
        }
    }

    showInline(targetSelector, { type, content, dismiss = true, countdown = 0 }) {
        const $target = $(targetSelector);
        if ($target.length === 0) {
            console.warn(`WigaNotify: Element target "${targetSelector}" tidak ditemukan.`);
            return;
        }

        // Hapus notifikasi sebelumnya di target ini
        $target.find('.wiga-inline-notify').remove();

        const bootstrapAlertClass = {
            success: 'alert-success',
            danger: 'alert-danger',
            warning: 'alert-warning',
            info: 'alert-info'
        }[type] || 'alert-info';

        const faIconClass = {
            success: 'fa-circle-check',
            danger: 'fa-circle-xmark',
            warning: 'fa-triangle-exclamation',
            info: 'fa-circle-info'
        }[type] || 'fa-circle-info';

        const $container = $(`
            <div class="alert ${bootstrapAlertClass} alert-dismissible fade show wiga-inline-notify" role="alert" style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid ${faIconClass}" style="flex-shrink: 0; font-size: 1.5rem;"></i>
                <div style="flex-grow: 1;">${content}</div>
                ${dismiss ? `<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>` : ''}
            </div>
        `);

        $target.append($container);

        if (dismiss) {
            $container.find('.btn-close').on('click', function () {
                $container.remove();
            });
        }

        if (countdown > 0) {
            setTimeout(() => {
                $container.fadeOut(400, () => $container.remove());
            }, countdown * 1000);
        }
    }

    checkAndShow() {
        const data = localStorage.getItem(this.configKey);
        if (!data) return;

        try {
            const config = JSON.parse(data);
            Wiga.log(WigaRoute.url(config.url))
            if (window.location.href === WigaRoute.url(config.url)) {
                this.show({
                    type: config.type,
                    content: config.content,
                    dismiss: config.dismiss === 1,
                    countdown: config.countdown
                });
                localStorage.removeItem(this.configKey);
            }
        } catch (e) {
            console.error('WigaNotification error:', e);
            localStorage.removeItem(this.configKey);
        }
    }

    removeExisting() {
        const existing = document.getElementById('wiga-modal-overlay');
        if (existing) existing.remove();
    }

    translate(text) {
        let data = [
            ['success', 'Berhasil'],
            ['danger', 'Gagal'],
            ['warning', 'Peringatan'],
            ['info', 'Informasi']
        ];
        return data.find(t => t[0] === text)[1];
    }
};

/* 
>> contoh penggunaan WigaNotify <<
WigaNotify.success('Data berhasil disimpan.');
WigaNotify.info('Silahkan login terlebih dahulu untuk mengakses halaman ini.',url,dismiss (boolean),countdown (seconds));

>> contoh penggunaan inline show << 
WigaNotify.showInline('#wiga-alert', { type: 'success', content: 'Silahkan login terlebih dahulu untuk mengakses halaman ini.' });
*/


const WigaForm = new class {
    json(formElement) {
        const elements = $(formElement).find('[name]');
        const json = {};

        elements.each((i, el) => {
            const name = el.name;
            let value;

            if (el.type === 'checkbox') {
                value = el.checked ? el.value : undefined;
            } else if (el.type === 'radio') {
                value = el.checked ? el.value : undefined;
            } else {
                value = el.value;
            }

            if (value !== undefined) {
                if (json[name]) {
                    if (!Array.isArray(json[name])) {
                        json[name] = [json[name]];
                    }
                    json[name].push(value);
                } else {
                    json[name] = value;
                }
            }
        });

        return json;
    }

    data(formElement) {
        const formData = new FormData();
        const elements = $(formElement).find('[name]');
        const groupedByName = {};

        // Langkah 1: Kelompokkan semua elemen berdasarkan atribut 'name'
        elements.each((i, el) => {
            const name = el.name;
            if (!name) return; // Lewati elemen tanpa nama

            if (!groupedByName[name]) {
                groupedByName[name] = [];
            }
            groupedByName[name].push(el);
        });

        // Langkah 2: Proses setiap grup elemen
        for (const name in groupedByName) {
            const group = groupedByName[name];
            const firstEl = group[0];
            const type = firstEl.type;

            if (type === 'checkbox') {
                // Ini adalah grup berisi satu atau lebih checkbox.
                // Ambil semua yang dicentang.
                const checkedValues = group
                    .filter(el => el.checked)
                    .map(el => el.value);

                // Jika ada setidaknya satu yang dicentang, tambahkan ke formData.
                if (checkedValues.length > 0) {
                    // formData secara otomatis menangani beberapa nilai untuk satu nama.
                    checkedValues.forEach(value => {
                        formData.append(name, value);
                    });
                }
                // Jika tidak ada yang dicentang, tidak ada yang ditambahkan (perilaku standar).

            } else if (type === 'radio') {
                // Untuk radio, cari satu yang dicentang dalam grup.
                const checkedRadio = group.find(el => el.checked);
                if (checkedRadio) {
                    formData.append(name, checkedRadio.value);
                }
            } else if (type === 'file') {
                if (firstEl.files && firstEl.files.length > 0) {
                    if (!firstEl.multiple) {
                        // File tunggal
                        formData.append(name, firstEl.files[0]);
                    } else {
                        // File ganda
                        for (let i = 0; i < firstEl.files.length; i++) {
                            formData.append(`${name}[${i}]`, firstEl.files[i]);
                        }
                    }
                }
            } else {
                // Untuk semua tipe input lainnya (text, hidden, textarea, dll.),
                // ambil nilai dari elemen pertama dalam grup.
                formData.append(name, firstEl.value);
            }
        }

        return formData;
    }

    reset(formElement) {
        const elements = $(formElement).find('[name]');
        elements.each((i, el) => {
            if (el.type === 'checkbox') {
                el.checked = false;
            } else if (el.type === 'radio') {
                el.checked = false;
            } else {
                el.value = '';
            }
        });
    }
};

/*
>> contoh penggunaan WigaForm <<
WigaForm.json('#WigaFormPage');
*/

const WigaSigned = (data) => {
    if (data) {
        Wiga.signed(data);
    }
}

const WigaHttp = new class {

    constructor()
    {
        this.headers = Wiga.fetchConfig()
    }

    async get(endpoint, data = null, customHeaders = {}) {
        const headers = { ...this.headers, ...customHeaders };
        let baseUrl = WigaRoute.api_url(endpoint);

        if (data && Object.keys(data).length > 0) {
            baseUrl += objectToParams(data);
        }

        const res = await fetch(baseUrl, {
            method: 'GET',
            headers: headers
        });

        // if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
    }

    async post(endpoint, data = null, customHeaders = {}) {
        const isForm = WigaValidate.isFormData(data);
        const fetchBody = data ? (isForm ? data : JSON.stringify(data)) : null;
        const headers = { ...this.headers, ...customHeaders };

        if (!isForm && data) {
            headers['Content-Type'] = 'application/json';
        }

        const res = await fetch(WigaRoute.api_url(endpoint), {
            method: 'POST',
            headers,
            body: fetchBody
        });

        // if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
    }

    async put(endpoint, data = null, customHeaders = {}) {
        const isForm = WigaValidate.isFormData(data);
        const fetchBody = data ? (isForm ? data : JSON.stringify(data)) : null;
        const headers = { ...this.headers, ...customHeaders };

        if (!isForm && data) {
            headers['Content-Type'] = 'application/json';
        }

        const res = await fetch(WigaRoute.api_url(endpoint), {
            method: 'PUT',
            headers,
            body: fetchBody
        });

        // if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
    }

    async delete(endpoint, data = null, customHeaders = {}) {
        
        const isForm = WigaValidate.isFormData(data);
        const fetchBody = data ? (isForm ? data : JSON.stringify(data)) : null;
        const headers = { ...this.headers, ...customHeaders };

        if (!isForm && data) {
            headers['Content-Type'] = 'application/json';
        }

        const res = await fetch(WigaRoute.api_url(endpoint), {
            method: 'DELETE',
            headers,
            body: fetchBody
        });

        // if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
        return res.json();
    }

    

    handle(response, formSelector = '#WigaFormPage',successCallback = null, errorCallback = null){
        if(response.errors && formSelector)
        {
            let errors = response.errors;
            if (Array.isArray(errors) || WigaValidate.isObject(errors))
            {
                let parentSelector = $(formSelector)
                parentSelector.find('[data-error]').html('')

                $.each(errors, function (i, err) {
                    let content = ''
                    $.each(err, function (j, key) {
                        content += `<span>${key}</span>`
                    })
                    parentSelector.find('[data-error="' + i + '"]').html(content)
                })
            }

            if(errorCallback)
            {
                errorCallback(response)
            }

        }else{
            if(response.status)
            {
                successCallback(response)
            }else{
                if(errorCallback)
                {
                    errorCallback(response)
                }
            }
        }

    }

    
}

/* 
>> contoh penggunaan WigaHttp <<
WigaHttp.post('login', WigaForm.json('#WigaFormPage'));
*/


const WigaTable = new class {
    constructor() {
        this.language_default = {
            decimal: "",
            emptyTable: "Data tidak ditemukan",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 - 0 dari 0 halaman",
            infoFiltered: "(difilter dari _MAX_ total data)",
            infoPostFix: "",
            thousands: ",",
            lengthMenu: "Menampilkan _MENU_ halaman",
            loadingRecords: "Memuat...",
            processing: "Memuat...",
            search: "Cari: ",
            zeroRecords: "Data tidak ditemukan",
            paginate: {
                first: "First",
                last: "Last",
                next: "Selanjutnnya",
                previous: "Sebelumnya"
            },
        };

        this.headers = Wiga.fetchConfig()
    }

    init(args) {
        if (typeof args !== 'object') {
            alert("Arguments must be object only");
            return false;
        }

        const {
            ajax,
            scrollY = '-',
            scrollX = false,
            responsive = true,
            selector,
            columns,
            language = this.language_default,
            processing = false,
            searching = true,
            pageLength,
            columnDefs,
            order,
            paginate,
            rowCallback,
            toolbarSelector,
            onDraw
        } = args;

        if (!ajax) {
            alert("ajax arguments required");
            return false;
        }

        const ajax_options = {
            url: WigaRoute.api_url(ajax.url),
            type: ajax.method || "GET",
            headers: this.headers,
            dataSrc: ajax.dataSrc || 'data',
            ...(ajax.data && { data: ajax.data })
        };

        let columnsData = columns.map(column => {
            if(typeof column.data === 'function')
            {
                return {
                    data: null,
                    render: column.data
                }
            }else{
                if(column.data == 'table.numbering')
                {
                    return {
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }
                }
                return column
            }
        })

        let options = {
            serverSide: true,
            deferRender: true,
            ajax: ajax_options,
            columns: columnsData,
            language: language,
            processing: true,
            info: false
        };

        if (scrollX) options.scrollX = true;
        if (responsive) options.responsive = true;
        if (pageLength !== undefined) options.pageLength = pageLength;
        if (columnDefs !== undefined) options.columnDefs = columnDefs;
        if (order !== undefined) options.order = order;
        if (paginate !== undefined) options.paginate = paginate;
        if (rowCallback !== undefined) options.rowCallback = rowCallback;

        const searchElement = `
            <div class="d-flex align-items-center position-relative my-1">
                <i class="fa-solid fa-search fs-3 position-absolute ms-5 text-gray-500">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <input type="text" wigatable-search-filter class="form-control form-control-solid w-250px ps-13" placeholder="Cari disini"/>
            </div>`;

        let toolbar = ``;

        let toolbarElement = $('wigatable-toolbar[data-table="' + selector + '"]');

        if (toolbarElement.length) {
            toolbar = toolbarElement.html();
            toolbarElement.remove();
        }

        if ($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().clear().destroy();
        }

        let headerID = '__' + WigaString.rand(23);
        const $card = $(selector).closest('.card');

        if ($card.find('.card-header').length === 0) {
            $card.prepend(`
                <div class="card-header border-0 pt-6" wigatable-header="${headerID}">
                    <div class="card-title"></div>
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5"></div>
                </div>
            `);
            if (searching) {
                $card.find(`[wigatable-header="${headerID}"] .card-title`).html(searchElement);
            }
            $card.find(`[wigatable-header="${headerID}"] .card-toolbar`).html(toolbar);
        } else {
            const $header = $card.find('.card-header');
            if (!$header.attr('wigatable-header')) {
                $header.attr('wigatable-header', headerID);
            } else {
                headerID = $header.attr('wigatable-header');
            }

            if ($header.find('.card-title').length === 0) {
                $header.append('<div class="card-title"></div>');
            }

            if ($header.find('.card-toolbar').length === 0) {
                $header.append('<div class="card-toolbar flex-row-fluid justify-content-end gap-5"></div>');
            }

            if (searching && $header.find('.card-title [wigatable-search-filter]').length === 0) {
                $header.find('.card-title').html(searchElement);
            }

            if (toolbar.length > 0) {
                $header.find('.card-toolbar').html(toolbar);
            }
        }

        const dt = $(selector).DataTable(options);

        if (searching) {
            $(document).on('keyup', `[wigatable-header="${headerID}"] [wigatable-search-filter]`, function (e) {
                const ignoredKeys = [
                    "Control", "Alt", "Shift", "Meta", "CapsLock", "Tab",
                    "Escape", "ArrowUp", "ArrowDown", "ArrowLeft", "ArrowRight"
                ];
                if (!ignoredKeys.includes(e.key)) {
                    dt.search(e.target.value).draw();
                }
            });
        }

        if (onDraw !== undefined) {
            dt.on('draw.dt', () => onDraw());
        }

        return dt;
    }

    renderNumbering(data, type, row, meta) {
        return meta.row + meta.settings._iDisplayStart + 1 + '.';
    }
};

/* 
>> contoh penggunaan WigaTable <<
WigaTable.init({
    selector: '#table-id',
    ajax: {
        url: '/api/data',
        method: 'POST',
        data: { some_param: 123 }
    },
    columns: [
        { data: 'id' },
        { data: 'name' },
        { data: 'email' }
    ],
    pageLength: 10,
    searching: true,
    toolbarSelector: '#toolbar-content',
    onDraw: function () {
        console.log('Tabel di-draw ulang');
    }
});
*/

class WigaUploadImage {
    // =================================================================
    // 1. Properti & Metode Statis (Milik Class, bukan Instansi)
    // =================================================================
    static instances = {};

    /**
     * API Statis: Menampilkan preview gambar yang sudah ada.
     * @param {object} options - Opsi berisi { name, id, filename }.
     */
    static preview(options) {
        const { name, id, filename } = options;
        if (!name || !this.instances[name]) {
            return console.warn(`WigaUploadImage instance with name "${name}" not found.`);
        }
        const instance = this.instances[name];
        instance.state.initialId = id;
        // instance.elements.idInput.val(id);
        instance.displayFileItem(filename.split('/').pop(), filename);
    }

    /**
     * API Statis: Mengambil ID dari gambar awal yang telah dihapus.
     * @param {object} options - Opsi berisi { name }.
     * @returns {Array} - Array berisi ID yang dihapus.
     */
    static deletedImage(options) {
        const { name } = options;
        if (!name || !this.instances[name]) {
            return [];
        }
        return this.instances[name].state.deletedIds;
    }

    // =================================================================
    // 2. Constructor (Dijalankan saat 'new WigaUploadImage()' dipanggil)
    // =================================================================
    constructor(element) {
        this.$component = $(element);
        
        // Baca semua atribut dan simpan sebagai properti instansi
        this.nameAttr = this.$component.attr('name') || `uploaded_file_${Math.random().toString(36).substring(7)}`;
        this.acceptAttr = this.$component.attr('accept') || 'image/png, image/jpeg, image/jpg';
        this.titleAttr = this.$component.attr('title') || 'Upload Banner';
        this.maxSizeAttr = this.$component.attr('maxsize') || '2';
        // this.idNameAttr = this.$component.attr('data-id-name') || 'id';
        this.maxFileSizeInBytes = parseFloat(this.maxSizeAttr) * 1024 * 1024;

        // State internal untuk setiap instansi
        this.state = { activeFiles: [], initialId: null, deletedIds: [] };

        // Referensi ke elemen DOM yang akan kita buat
        this.elements = {};

        this.buildDOM();
        this.bindEvents();

        // Daftarkan instansi ini ke registri statis
        WigaUploadImage.instances[this.nameAttr] = this;

        // Ganti elemen placeholder dengan komponen yang sudah jadi
        this.$component.replaceWith(this.elements.uploaderElement);
    }

    // =================================================================
    // 3. Metode Instansi (Milik setiap objek yang dibuat)
    // =================================================================

    /**
     * Membangun semua elemen DOM dan menyimpannya di this.elements.
     */
    buildDOM() {
        const uploadIcon = $('<i>').addClass('fa-duotone fa-upload fs-2x d-block mb-2 text-gray-600');
        const textHint = $('<small>').addClass('d-block mb-2').html('Drag and Drop a file to upload <br> or');
        this.elements.browseBtn = $('<button>').prop('type', 'button').addClass('btn btn-primary rounded-pill btn-sm d-block mx-auto mb-2').text('Browse');
        const textSupported = $('<small>').addClass('text-muted d-block').text(`Supported file: ${this.acceptAttr.replace(/image\//g, '')}. Max size: ${this.maxSizeAttr}MB`);
        const dropZoneContent = $('<div>').addClass('text-center').append(uploadIcon, textHint, this.elements.browseBtn, textSupported);
        this.elements.dropZone = $('<div>').addClass('w-100 border-dashed border-2 border-gray-300 rounded-3 p-5 d-flex align-items-center justify-content-center bg-white').append(dropZoneContent);
        this.elements.fileInput = $('<input>').attr({ type: 'file', name: this.nameAttr, accept: this.acceptAttr }).addClass('d-none');
        // this.elements.idInput = $('<input>').attr({ type: 'hidden', name: this.idNameAttr, value: '' });
        const leftTitle = $('<h6>').addClass('mb-4').text(this.titleAttr);
        const leftColumn = $('<div>').addClass('col-md-5 mb-4 mb-md-0').append(leftTitle, this.elements.dropZone, this.elements.fileInput);
        const rightTitle = $('<h6>').addClass('mb-4').text('Uploaded File');
        this.elements.uploadedFilesContainer = $('<div>').addClass('d-flex flex-column gap-3');
        const rightColumn = $('<div>').addClass('col-md-7').append(rightTitle, this.elements.uploadedFilesContainer);
        this.elements.modalImage = $('<img>').addClass('img-fluid');
        const modalBody = $('<div>').addClass('modal-body p-0').append(this.elements.modalImage);
        const modalHeader = $('<div>').addClass('modal-header').append('<h5 class="modal-title">Image Preview</h5>', '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>');
        const modalContent = $('<div>').addClass('modal-content').append(modalHeader, modalBody);
        const modalDialog = $('<div>').addClass('modal-dialog modal-lg modal-dialog-centered').append(modalContent);
        const messageErrorContent = $('<div>').attr('data-error',this.nameAttr);
        this.elements.imagePreviewModal = $('<div>').addClass('modal fade').attr({ id: `imagePreviewModal-${this.nameAttr}`, tabindex: '-1', 'aria-hidden': 'true' }).append(modalDialog);
        this.elements.uploaderElement = $('<div>').addClass('row mb-4 user-select-none bg-light-info py-3 px-2 rounded-3').append(leftColumn, rightColumn, messageErrorContent, this.elements.imagePreviewModal);
    }

    /**
     * Menghubungkan semua event listener ke elemen DOM.
     */
    bindEvents() {
        this.elements.browseBtn.on('click', () => this.elements.fileInput.click());
        this.elements.fileInput.on('change', (e) => { this.handleFiles(e.target.files); });
        this.elements.dropZone.on('dragover', (e) => { e.preventDefault(); e.stopPropagation(); this.elements.dropZone.addClass('border-primary'); });
        this.elements.dropZone.on('dragleave', (e) => { e.preventDefault(); e.stopPropagation(); this.elements.dropZone.removeClass('border-primary'); });
        this.elements.dropZone.on('drop', (e) => { e.preventDefault(); e.stopPropagation(); this.elements.dropZone.removeClass('border-primary'); this.handleFiles(e.originalEvent.dataTransfer.files); });
        this.elements.uploadedFilesContainer.on('click', '.delete-btn', (e) => {
            const fileItem = $(e.currentTarget).closest('.uploaded-file-item');
            this.revokePreviewUrl(fileItem);
            fileItem.remove();
            if (this.state.initialId) { this.state.deletedIds.push(this.state.initialId); this.state.initialId = null; }
            this.state.activeFiles = [];
            // this.elements.idInput.val('');
            this.elements.fileInput.val('');
            this.updateFileInput();
        });
    }

    /**
     * Menampilkan item file di UI.
     */
    displayFileItem(fileName, previewUrl) {
        this.revokePreviewUrl(this.elements.uploadedFilesContainer.find('.uploaded-file-item'));
        this.elements.uploadedFilesContainer.empty();
        const fileIcon = $('<i>').addClass('fa-duotone fa-file-image fs-2x text-gray-600');
        const fileNameSpan = $('<span>').addClass('text-truncate').text(fileName);
        const deleteIcon = $('<i>').addClass('fa-duotone fa-trash p-0');
        const deleteBtn = $('<button>').prop('type', 'button').addClass('btn btn-light-danger p-2 px-3 btn-sm delete-btn').append(deleteIcon);
        const deleteWrapper = $('<div>').addClass('ms-auto').append(deleteBtn);
        const triggerWrapper = $('<div>').addClass('d-flex align-items-center gap-3 flex-grow-1 text-truncate preview-trigger').css('cursor', 'pointer').append(fileIcon, fileNameSpan);
        triggerWrapper.on('click', () => {
            this.elements.modalImage.attr('src', previewUrl);
            const bsModal = new bootstrap.Modal(this.elements.imagePreviewModal[0]);
            bsModal.show();
        });
        const fileItem = $('<div>').addClass('w-100 d-flex align-items-center gap-3 p-2 border rounded-3 uploaded-file-item bg-white').data('preview-url', previewUrl).append(triggerWrapper, deleteWrapper);
        this.elements.uploadedFilesContainer.append(fileItem);
    }

    /**
     * Memproses file yang baru dipilih.
     */
    handleFiles(newFiles) {
        if (!newFiles || newFiles.length === 0) return;
        const file = newFiles[0];
        if (!this.validateFile(file)) return;
        if (this.state.initialId) { this.state.deletedIds.push(this.state.initialId); this.state.initialId = null; }
        this.state.activeFiles = [file];
        // this.elements.idInput.val('');
        const previewUrl = URL.createObjectURL(file);
        this.displayFileItem(file.name, previewUrl);
        this.updateFileInput();
    }

    /**
     * Memvalidasi tipe dan ukuran file.
     */
    validateFile(file) {
        let isTypeValid = this.acceptAttr.split(',').map(t => t.trim()).includes('image/*') ? file.type.startsWith('image/') : this.acceptAttr.split(',').map(t => t.trim()).includes(file.type);
        if (!isTypeValid || file.size > this.maxFileSizeInBytes) {
            alert(`Error pada file ${file.name}: Tipe atau ukuran tidak sesuai.`);
            return false;
        }
        return true;
    }

    /**
     * Sinkronisasi state file ke elemen input.
     */
    updateFileInput() {
        const dataTransfer = new DataTransfer();
        this.state.activeFiles.forEach(file => dataTransfer.items.add(file));
        this.elements.fileInput[0].files = dataTransfer.files;
    }

    /**
     * Menghapus URL preview dari memori.
     */
    revokePreviewUrl(fileItemElement) {
        if (fileItemElement && fileItemElement.length > 0) {
            const oldUrl = fileItemElement.data('preview-url');
            if (oldUrl && oldUrl.startsWith('blob:')) {
                URL.revokeObjectURL(oldUrl);
            }
        }
    }
}

// Jalankan setelah halaman selesai dimuat
document.addEventListener('DOMContentLoaded', () => {
    // Cari semua elemen uploader
    const uploaders = document.querySelectorAll('wiga-upload-image');

    // Buat instansi baru untuk setiap elemen
    uploaders.forEach(uploaderElement => {
        new WigaUploadImage(uploaderElement);
    });

    // Setelah semua komponen diinisialisasi, Anda bisa memanggil API statis
    // Contoh:
    // const existingImage = { id: 101, url: 'https://via.placeholder.com/400x250' };

    // WigaUploadImage.preview({
    //     name: 'product_image',
    //     id: existingImage.id,
    //     filename: existingImage.url
    // });
});



const WigaImageLazyload = Wiga.class({
    /**
     * Metode render akan menginisialisasi proses lazy loading dan popup.
     */
    render() {
        // Simpan konteks 'this' dari class instance untuk digunakan di dalam callback.
        const self = this;
        const selector = '[wigaimage-lazyload]';
        const targets = $wiga(selector);

        if (targets.length === 0) {
            return;
        }

        // --- Langkah 1: Buat modal preview sekali saja untuk seluruh halaman ---
        // Kita gunakan properti statis pada constructor untuk memastikan ini hanya berjalan sekali.
        if (!this.constructor.modalRendered) {
            this._createPreviewModal();
            this.constructor.modalRendered = true;
        }

        const observerOptions = {
            rootMargin: '50px 0px',
            threshold: 0.01
        };

        const lazyLoadObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const placeholder = entry.target;
                    const src = placeholder.getAttribute('wigaimage-lazyload');
                    
                    observer.unobserve(placeholder);
                    if (!src) return;

                    const finalImage = new Image();
                    for (const attr of placeholder.attributes) {
                        if (attr.name !== 'wigaimage-lazyload') {
                            finalImage.setAttribute(attr.name, attr.value);
                        }
                    }

                    finalImage.onload = () => {
                        // Ganti placeholder dengan gambar yang sudah jadi.
                        placeholder.replaceWith(finalImage);

                        // --- Langkah 2: Tambahkan event klik pada gambar yang baru dimuat ---
                        $(finalImage).css('cursor', 'pointer'); // Tambahkan petunjuk visual
                        $(finalImage).on('click', function() {
                            // Panggil metode untuk menampilkan popup.
                            self._showPreviewPopup(this.src);
                        });
                    };

                    finalImage.onerror = () => console.error(`Gagal memuat gambar: ${src}`);
                    finalImage.src = src;
                }
            });
        }, observerOptions);

        targets.each((i, el) => {
            lazyLoadObserver.observe(el);
        });
    },

    /**
     * Metode "private" untuk membuat struktur DOM modal dan menyimpannya.
     * Dijalankan hanya sekali.
     */
    _createPreviewModal() {
        const modalImage = $('<img>').addClass('img-fluid');
        const modalBody = $('<div>').addClass('modal-body p-0 text-center').append(modalImage);
        const modalHeader = $('<div>').addClass('modal-header').append('<h5 class="modal-title">Image Preview</h5>', '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>');
        const modalContent = $('<div>').addClass('modal-content').append(modalHeader, modalBody);
        const modalDialog = $('<div>').addClass('modal-dialog modal-lg modal-dialog-centered').append(modalContent);
        
        // Simpan referensi modal pada constructor agar bisa diakses oleh semua instance
        this.constructor.previewModal = $('<div>').addClass('modal fade').attr({
            'id': 'wiga-lazyload-preview-modal',
            'tabindex': '-1',
            'aria-hidden': 'true'
        }).append(modalDialog);

        // Tambahkan modal ke body
        $('body').append(this.constructor.previewModal);
    },

    /**
     * Metode "private" untuk menampilkan popup dengan gambar yang diberikan.
     * @param {string} imageSrc - URL gambar yang akan ditampilkan.
     */
    _showPreviewPopup(imageSrc) {
        // Ambil referensi modal dari properti statis
        const modalElement = this.constructor.previewModal;
        if (modalElement) {
            // Set sumber gambar di dalam modal
            modalElement.find('img').attr('src', imageSrc);
            
            // Inisialisasi dan tampilkan modal Bootstrap
            const bsModal = new bootstrap.Modal(modalElement[0]);
            bsModal.show();
        }
    }
});






























/* Jangan dihapus */
Wiga.log(Wiga.config('APP_NAME') + ' ' + Wiga.config('APP_VERSION') + ' ' + Wiga.config('APP_AUTHOR') + ' is running...')