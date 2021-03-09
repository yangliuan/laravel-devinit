<?php

namespace Yangliuan\LaravelDevinit\Traits;

trait Register
{
    public function regAppConfig()
    {
        $this->replaceInFile(
            config_path('app.php'),
            ['\'timezone\' => \'UTC\'', '\'locale\' => \'en\'', '\'fallback_locale\' => \'en\'', '\'faker_locale\' => \'en_US\''],
            ['\'timezone\' => \'PRC\'', '\'locale\' => \'zh_CN\'', '\'fallback_locale\' => \'zh_CN\'', '\'faker_locale\' => \'zh_CN\'']
        );
    }

    public function regCorsConfig()
    {
        $this->replaceInFile(
            config_path('cors.php'),
            ['\'paths\' => [\'api/*\', \'sanctum/csrf-cookie\']'],
            ['\'paths\' => [\'api/*\', \'admin/*\', \'common/*\',  \'sanctum/csrf-cookie\']']
        );
    }

    public function regEloquentfilterConfig()
    {
        $this->replaceInFile(
            config_path('eloquentfilter.php'),
            'ModelFilters',
            'Filters'
        );
    }

    public function regAuthConfigPassport()
    {
        //$this->replaceInFile(config_path('auth.php'));
    }

    /**
     * Replace a given string in a given file.
     *
     * @param  string  $path
     * @param  string  $search
     * @param  string  $replace
     * @return void
     */
    protected function replaceInFile($path, $search, $replace)
    {
        file_put_contents(
            $path,
            str_replace($search, $replace, file_get_contents($path))
        );
    }
}
