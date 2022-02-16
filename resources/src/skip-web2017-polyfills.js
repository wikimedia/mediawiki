return 'IntersectionObserver' in window &&
    typeof fetch === 'function' &&
    // Ensure:
    // - standards compliant URL
    // - standards compliant URLSearchParams
    // - URL#toJSON method (came later)
    //
    // Facts:
    // - All browsers with URL also have URLSearchParams, don't need to check.
    // - Safari <= 7 and Chrome <= 31 had a buggy URL implementations.
    // - Firefox 29-43 had an incomplete URLSearchParams implementation. https://caniuse.com/urlsearchparams
    // - URL#toJSON was released in Firefox 54, Safari 11, and Chrome 71. https://caniuse.com/mdn-api_url_tojson
    //   Thus we don't need to check for buggy URL or incomplete URLSearchParams.
    typeof URL === 'function' && 'toJSON' in URL.prototype;
