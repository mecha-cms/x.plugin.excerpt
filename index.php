<?php

namespace x {
    function excerpt($excerpt) {
        // `excerpt` data has been set
        if ($excerpt) {
            return $excerpt; // Return the initial value
        }
        $content = $this->content;
        $exist = \strpos($content, "\f");
        // Page’s `content` is empty or excerpt marker does not exist, return the page’s `description`
        if (!$content || !$exist) {
            // If page’s `description` is empty, create a fake excerpt generated by the page’s `content`
            return '<p>' . ($this->description ?? \To::description((string) $content)) . '</p>';
        }
        $content = \trim(\substr($content, 0, $exist));
        return "" !== $content ? $content : null;
    }
    \Hook::set('page.excerpt', __NAMESPACE__ . "\\excerpt", 2.1);
}

namespace x\excerpt {
    function n($content) {
        if (!$content) {
            return $content;
        }
        $exist = \strpos($content, "\f") ?: \strpos($content, '&#12;') ?: \stripos($content, '&#xc;');
        if (!$exist) {
            return $content;
        }
        // Normalize `&#12;` and `&#xc;` to a literal `\f`. Also, remove the surrounding HTML element if any (usually a paragraph element)
        return \preg_replace('/\s*<([\w:-]+)(?:\s[^>]*)?>\s*(?:[\f]|&#(?:12|x[cC]);)\s*<\/\1>\s*|\s*(?:[\f]|&#(?:12|x[cC]);)\s*/', "\f", $content);
    }
    \Hook::set('page.content', __NAMESPACE__ . "\\n", 2.1);
}