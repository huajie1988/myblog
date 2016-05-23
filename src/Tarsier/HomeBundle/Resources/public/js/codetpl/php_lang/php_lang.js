var php_lang={
    'keyword':{
        'class':'php_keyword',
        'list':[
            'and',
            'or',
            'xor',
            '__FILE__',
            'exception',
            '__LINE__',
            'array',
            'as',
            'break',
            'case',
            'class',
            'const',
            'continue',
            'declare',
            'default',
            'die',
            'do',
            'echo',
            'else',
            'elseif',
            'empty',
            'enddeclare',
            'endfor',
            'endforeach',
            'endif',
            'endswitch',
            'endwhile',
            'eval',
            'exit',
            'extends',
            'for',
            'foreach',
            'function',
            'global',
            'if',
            'include',
            'include_once',
            'isset',
            'new',
            'print',
            'require',
            'require_once',
            'return',
            'static',
            'switch',
            'unset',
            'use',
            'var',
            'while',
            '__FUNCTION__',
            '__CLASS__',
            '__METHOD__',
            'final',
            'php_user_filter',
            'interface',
            'implements',
            'extends',
            'public',
            'private',
            'protected',
            'abstract',
            'clone',
            'try',
            'catch',
            'throw',
            'cfunction',
            'this',
            'self',
            'parent',
            'false',
            'true',
        ]
    },
    'string':{
        'class':'php_string',
        'list':['"',"'"]
    },
    'operate':{
        'class':'php_operate',
        'list':[
            '\\b\\+\\b',
            '\\b\\-\\b',
            '\\b\\*\\b',
            '\\b\\/\\b',
            '\\b\\=\\b',
            '\\(',
            '\\)',
            '\\[',
            '\\]',
        ]
    },
    'annotation':{
        'class':'php_annotation',
        'list':{'single':['\/\/'],"multiline":['\/\\* \\*\/']}
    }
}