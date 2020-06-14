<?php

namespace AmphiBee\Eloquent;

use Thunder\Shortcode\Shortcode\ShortcodeInterface;

/**
 * Interface Shortcode
 *
 * @package AmphiBee\Eloquent
 * @author Junior Grossi <juniorgro@gmail.com>
 */
interface Shortcode
{
    /**
     * @param ShortcodeInterface $shortcode
     * @return string
     */
    public function render(ShortcodeInterface $shortcode);
}
