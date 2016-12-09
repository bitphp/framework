// ToDo plantillas
// ToDo cache
var App = {

   log: {
      // ToDo implements this
      fail: function(msg) {
        console.log(msg);
      },

      info: function(msg) {
        console.log(msg);
      }, 

      debug: function(msg) {
        console.log(msg);
      }
   },

   template: {

      cache: {},

      origins: {},

      origin: function(n, p) {
          App.template.origins[n] = p;
      },

      load: function(n, callback) {
        n = n.split('@');

        if(n[0] == 'local') {
          callback($('#' + n[1]).html());
          return;
        }

        if(typeof App.template.cache[n] !== 'undefined') {
            callback(App.template.cache[n]);
            return;
        }

        if(typeof App.template.origins[n[0]] === 'undefined') {
          App.log.debug(' +- Undefinded template origin /'+ n +'/');
          return;
        }

        var url = App.template.origins[n[0]] + '/' + (n[1].split(' ').join('/')) + '.html';

        $.ajax({
            url: url
          , method: 'GET'
        })

        .done(function(r) {
          App.template.cache[n] = r;
          callback(r);
        })

        .fail(function(r) {
            if(typeof r.responseJSON !== 'undefined') {
              callback(r.responseJSON);
              return;
            }
      
            callback(r.responseText);
        });
      }
   },
   
   route: {

      map: {},
      routes: [],

      reload: function() {
          var hash = App.route.getHash();
          
          for (var i=0, c=App.route.routes.length; i<c; i++) {
            var route = App.route.routes[i];
            if (App.route.checkRoute(hash, route))
              return;
          }
      },

      checkRoute: function(path, route) {
        var args  = {};
        var match = route.regex.pattern.exec(path);

        if(!match) return;

        for (var i = 1, len = match.length; i < len; ++i) {
            var key = route.regex.names[i-1];
            var val = (typeof match[i] == 'string') ? decodeURIComponent(match[i]): match[i];

            if(key)
              args[key.name] = val;
        }

        route.callback(args);
      },

      match: function(path, callback){
        App.route.routes.push({ 
            path: path 
          , callback: callback
          , regex: App.route.pathToRegex(path)
        });

        App.route.reload();
      },

      navigate: function(path, silent) {
          if(silent === 'undefined')
            silent = false;

          if(silent)
            App.route.removeListener();

          setTimeout(function() {
            window.location.hash = path;
            if(silent)
              setTimeout(function(){ App.route.regitreListener(); }, 1);
          }, 1)
      },

      pathToRegex: function(path) {
          var keys = [];

          path = path
            .replace(/\/\(/g, '(?:/')
            .replace(/\+/g, '__plus__')
            .replace(/(\/)?(\.)?:(\w+)(?:(\(.*?\)))?(\?)?/g, function(_, slash, format, key, capture, optional){
              keys.push({ name: key, optional: !! optional });
              slash = slash || '';
              return '' + (optional ? '' : slash) + '(?:' + (optional ? slash : '') + (format || '') + (capture || (format && '([^/.]+?)' || '([^/]+?)')) + ')' + (optional || '');
            })
            .replace(/([\/.])/g, '\\$1')
            .replace(/__plus__/g, '(.+)')
            .replace(/\*/g, '(.*)');

          var result = { 
              names: keys
            , pattern: new RegExp('^' + path + '$')
          };

          return result;
      },

      getHash: function() {
          return window.location.hash.substring(1);
      },

      regitreListener: function() {
          if(window.addEventListener) {
            window.addEventListener('hashchange', App.route.reload, false);
            return;
          }

          window.attachEvent('onhashchange', App.route.reload);
      }, 

      removeListener: function() {
          if(window.removeEventListener) {
            window.removeEventListener('hashchange', App.route.reload);
            return;
          }

          window.detachEvent('onhashchange', App.route.reload);
      }
   },

   data:  {

      origins: {},

      fetchParams: {
          url: null
        , element: null
        , urlParams: ''
        , method: null
        , payload: null
        , headers: null
      },

      element: function(a) {
         e = [];
         for(i in a) {
            e.push(a[i]);
         }

         App.data.fetchParams.element = e.join("/");
         return App.data;
      },

      fetch: function(m) {
         App.data.fetchParams.method = m;
         return App.data;
      },

      get: function() {
         App.data.element(arguments);
         App.data.fetch('GET');
         return App.data;
      },

      post: function() {
         App.data.element(arguments);
         App.data.fetch('POST');
         return App.data;
      },

      put: function() {
         App.data.element(arguments);
         App.data.fetch('PUT');
         return App.data;
      },

      patch: function() {
         App.data.element(arguments);
         App.data.fetch('PATCH');
         return App.data;
      },

      delete: function() {
         App.data.element(arguments);
         App.data.fetch('DELETE');
         return App.data;
      },

      done: function(callback) {
        var params = App.data.fetchParams;
        var url = params.url + params.element + params.urlParams;
        
        App.log.debug('+- ' + params.method + ' ' + url)

        $.ajax({
            url: url
          , method: params.method
          , data: params.payload
          , headers: params.headers
        })

        .done(callback)

        .fail(function(r) {
            if(typeof r.responseJSON !== 'undefined') {
              callback(r.responseJSON);
              return;
            }
      
            callback(r.responseText);
        });
      },

      params: function(p) {
         var result = [];
         for(i in p) {
            result.push( encodeURIComponent(i) + "=" + encodeURIComponent(p[i]));
         }

         App.data.fetchParams.urlParams = "?" + result.join('&');
         return App.data;
      },

      headers: function(p) {
        App.data.fetchParams.headers = p;
        return App.data;
      },

      payload: function(p) {
        App.data.fetchParams.payload = p;
        return App.data;
      },

      from: function(name) {
         if(typeof App.data.origins[name] === 'undefined') {
            App.log.debug(' +- Undefinded data origin /' + name + '/');
            return;
         }

         App.data.fetchParams.url = App.data.origins[name];
         return App.data;
      },

      origin: function(name, url) {
         App.data.origins[name] = url + '/';
      },
   },

   dom: {

      parse:  function(element) {

         element
            .find('.dom')
            .each(function(){

               var obj = $(this);
               var ids = obj.attr('id').split('-');
               
               var last = App.dom;
               for (var i = 0; i <= ids.length - 1; i++) {
                  if(typeof last[ids[i]] === 'undefined')
                     last[ids[i]] = obj;

                  last = last[ids[i]];
               };
            });
      }
   },
   
   form: function(id, callback){
      $("#"+ id).submit(function(event) {
         event.preventDefault();
         callback(event.target);
      });
   },

   init: function() {
      App.dom.parse($('html'));
      App.route.regitreListener();
   }
}