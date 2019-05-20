<?php

namespace _\page {
    function excerpt($excerpt, array $lot = []) {
        // `excerpt` data has been set or does not have `path` data, skip!
        if ($excerpt || !$this->path) {
            return $excerpt; // Return the initial value
        }
        $cut = \plugin('excerpt')['cut'];
        // Excerpt cropper does not exist, return the page’s `description`
        $content = $this->content;
        if (!$content || \strpos($content, $cut) === false) {
            // If page’s `description` is empty, create a fake excerpt generated by the page’s `content`
            return $this->description ?: \To::snippet((string) $content);
        }
        // Return the first part!
        return \trim(\explode($cut, $content)[0]);
    }
    \Hook::set('page.excerpt', __NAMESPACE__ . "\\excerpt", 2.1, 1);
}

namespace _\page\excerpt {
    function anchor($content, array $lot = []) {
        extract(\plugin('excerpt'), \EXTR_SKIP);
        if (\Config::is('pages') || \strpos($content, $cut) === false) {
            return $content;
        }
        return \implode('<div class="fi" id="' . \sprintf($anchor, $this->id) . '"></div>', \explode($cut, $content, 2));
    }
    \Hook::set('page.content', __NAMESPACE__ . "\\anchor", 2.1);
}