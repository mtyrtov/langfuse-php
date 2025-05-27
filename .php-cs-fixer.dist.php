<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        // Добавьте другие директории, если нужно
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setRiskyAllowed(true) // Разрешить "рискованные" правила (могут менять поведение кода)
    ->setRules([
        '@PSR12' => true, // Включаем стандарт PSR-12
        '@PHP80Migration' => true, // Миграция на синтаксис PHP 8.0
        '@PHP81Migration' => true, // Миграция на синтаксис PHP 8.1
        // '@PHP82Migration' => true, // Миграция на синтаксис PHP 8.2
        // '@PHP83Migration' => true, // Раскомментируйте, если используете PHP 8.3
        // '@PHP84Migration' => true, // Раскомментируйте, если используете PHP 8.4

        // Современные правила для PHP 8+
        'attribute_empty_parentheses' => true, // Поддержка атрибутов без скобок (PHP 8.0+)
        'no_trailing_comma_in_singleline' => true, // Удаление запятых в однострочных конструкциях
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays', 'arguments', 'parameters'], // Запятые в многострочных массивах, аргументах и параметрах
        ],
        'nullable_type_declaration' => true, // Использование ?string вместо ?string|null
        'nullable_type_declaration_for_default_null_value' => true, // ?string вместо string|null для значений по умолчанию
        'explicit_string_variable' => true, // Явное использование строковых переменных
        'no_extra_blank_lines' => [
            'tokens' => [
                'attribute',
                'case',
                'continue',
                'curly_brace_block',
                'default',
                'extra',
                'parenthesis_brace_block',
                'square_brace_block',
                'switch',
                'throw',
                'use',
            ],
        ],
        'blank_line_before_statement' => [
            'statements' => ['break', 'continue', 'declare', 'throw', 'try'],
        ],
        'blank_lines_before_namespace' => true, // Пустая строка перед namespace
        'single_line_empty_body' => true, // Однострочные пустые тела функций/методов
        'array_syntax' => ['syntax' => 'short'], // Короткий синтаксис массивов []
        'concat_space' => ['spacing' => 'one'], // Пробел вокруг оператора конкатенации
        'binary_operator_spaces' => [
            'default' => 'single_space',
            'operators' => [
                '=>' => 'single_space',
                '=' => 'single_space',
            ],
        ],
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'magic',
                'method_public',
                'method_protected',
                'method_private',
            ],
        ],
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['class', 'function', 'const'],
        ],
        'phpdoc_align' => ['align' => 'vertical'], // Выравнивание PHPDoc
        'phpdoc_separation' => true, // Разделение разных типов аннотаций в PHPDoc
        'phpdoc_summary' => true, // Точка в конце PHPDoc summary
        'strict_comparison' => true, // Использование === вместо ==
        'strict_param' => true, // Строгие типы в параметрах
        'declare_strict_types' => true, // Добавление declare(strict_types=1)
        'yoda_style' => false, // Отключение Yoda-стиля (опционально, включите true, если хотите)
        'native_function_invocation' => [
            'include' => ['@all'], // Добавление пространства имен для нативных функций
            'scope' => 'namespaced',
        ],
        'modernize_types_casting' => true, // Использование (int) вместо intval(), и т.д.
        'modernize_strpos' => true, // Замена strpos() !== false на str_contains() (PHP 8.0+)

        // Дополнительные оптимизации
        'no_unneeded_braces' => true,
        'no_unused_imports' => true, // Удаление неиспользуемых use
        'single_quote' => true, // Использование одинарных кавычек для строк
        'no_empty_comment' => true, // Удаление пустых комментариев
        'no_empty_phpdoc' => true, // Удаление пустых PHPDoc
        'no_empty_statement' => true, // Удаление пустых операторов
        'simplified_null_return' => true, // Замена return null на return
        'simplified_if_return' => true, // Упрощение if с return
    ])
    ->setFinder($finder)
    ->setParallelConfig(\PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect()) // Параллельная обработка
    ->setUsingCache(true) // Кэширование для ускорения
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');
