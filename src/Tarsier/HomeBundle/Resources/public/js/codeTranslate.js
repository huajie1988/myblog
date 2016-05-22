
$('#content').find('pre').each(function(i,e) {
    var cls = $(e).attr('class')
    if($('script[src="/js/codetpl/'+cls+'_lang/'+cls+'_lang.js"]').length<=0)
        $('body').append('<script src="/js/codetpl/'+cls+'_lang/'+cls+'_lang.js"></script>')
    if($('link[href="/js/codetpl/'+cls+'_lang/'+cls+'_lang.css"]').length<=0)
        $('body').append('<link href="/js/codetpl/'+cls+'_lang/'+cls+'_lang.css" rel="stylesheet" type="text/css">')
})

function codeTranslate(){
    $('#content').find('pre').each(function(i,e){
        var cls=$(e).attr('class')
        var lang=eval(cls+"_lang")
        if(cls!=undefined && lang){
            var code=$(e).text();


            if(lang['string'] && lang['string']['list']){
                for(var i=0;i<lang['string']['list'].length;i++){
                    var s=lang['string']['list'][i];
                    var reg=new RegExp("("+s+"[^"+s+"]*"+s+")","ig");
                    code=code.replace(reg,'<span data_rep_cls="'+lang['string']['class']+'">$1</span>')
                }
            }


            if(lang['operate'] && lang['operate']['list']){
                for(var i=0;i<lang['operate']['list'].length;i++){
                    var s=lang['operate']['list'][i];
                    var reg=new RegExp("(\\s*"+s+"\\s*)","ig");
                    code=code.replace(reg,'<span data_rep_cls="'+lang['operate']['class']+'">$1</span>')
                }
            }

            if(lang['keyword'] && lang['keyword']['list']){
                for(var i=0;i<lang['keyword']['list'].length;i++){
                    var s=lang['keyword']['list'][i];
                    var reg=new RegExp("\\b("+s+")\\b","ig");
                    code=code.replace(reg,'<span data_rep_cls="'+lang['keyword']['class']+'">$1</span>')
                }
            }

            if(lang['annotation'] && lang['annotation']['list']){
                if(lang['annotation']['list']['single']){
                    for(var i=0;i<lang['annotation']['list']['single'].length;i++){
                        var s=lang['annotation']['list']['single'][i];
                        var reg=new RegExp("("+s+".*)","ig");
                        code=code.replace(reg,'<span data_rep_cls="'+lang['annotation']['class']+'">$1</span>')
                    }
                }

                if(lang['annotation']['list']['multiline']){
                    for(var i=0;i<lang['annotation']['list']['multiline'].length;i++){
                        var s=lang['annotation']['list']['multiline'][i].split(' ');
                        var reg=new RegExp("("+s[0]+".*"+s[1]+")","ig");
                        code=code.replace(reg,'<span data_rep_cls="'+lang['annotation']['class']+'">$1</span>')
                    }
                }

            }

            $(e).html(code)
        }
    })

    $("span[data_rep_cls]").each(function(i,e){
        $(this).addClass($(this).attr('data_rep_cls'))
    })
}