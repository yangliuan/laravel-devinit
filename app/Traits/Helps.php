<?php
/**
 * 全局辅助方法
 */
namespace App\Traits;

trait Helps
{
    /**
     * 递归使用array_filter
     *
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public function arrayFilterRecursive(array $array, callable $callback)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                $value = $this->arrayFilterRecursive($value, $callback);
            }
        }

        return array_filter($array, $callback);
    }
}
