/*
存放基础的方法
*/
Vue.http.options.emulateJSON = true;
//加载对应的模板
function gmallComponent(url, vuecom) {
    if (!vuecom) vuecom = {};
    return function (resolve, reject) {
        $.get(url).done(function (r) {
            var rl = r.toLowerCase();
            var style = '';
            var styleIndexEnd = rl.lastIndexOf('</style>');
            var styleIndex = rl.lastIndexOf('<style', styleIndexEnd);
            if (styleIndexEnd !== -1 && styleIndex !== -1) {
                style = r.substring(styleIndex, styleIndexEnd);
                style = style.substr(style.indexOf('>') + 1);
                style = '<component scoped :is="\'style\'">' + style + '</component>';
            }

            var scriptIndexEnd = rl.lastIndexOf('<\/script>');
            var scriptIndex = rl.lastIndexOf('<script', scriptIndexEnd);
            if (scriptIndexEnd !== -1 && scriptIndex !== -1) {
                var script = r.substring(scriptIndex, scriptIndexEnd);
                script = script.substr(script.indexOf('>') + 1);
                script = script.replace(/docexport\s*=\s*\{/i, '{').replace(/(^\s*)|(\s*$)/g, "");
                if (script.substr(script.length - 1, 1) == ";") {
                    script = script.substr(0, script.length - 1);
                }
                script = '(' + script + ')';
                if (script != "()") {
                    var obj = eval(script);
                    for (var a in obj) vuecom[a] = obj[a];
                }
            }
            styleIndex = styleIndex == -1 ? r.length : styleIndex;
            scriptIndex = scriptIndex == -1 ? r.length : scriptIndex;
            var template = r.substring(0, Math.min(styleIndex, scriptIndex));
            if (style) {
                var index = template.lastIndexOf('</');
                if (index !== -1) template = template.substr(0, index) + style + template.substr(index);
            }
            vuecom.template = template;
            resolve(vuecom);
        });
    };
}
